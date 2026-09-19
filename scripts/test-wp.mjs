/**
 * BrickPoint — end-to-end WordPress test rig.
 *
 * Boots a real WordPress (Playground PHP runtime + SQLite) inside Node with a
 * host-mounted wp root, runs the demo importer steps, then fetches frontend
 * pages and reports errors.
 *
 * Run: node scripts/test-wp.mjs [--skip-import] [--rerun] [--sync-theme]
 */
import fs from "node:fs";
import path from "node:path";
import { execSync } from "node:child_process";

const NP = "/home/user/testenv/node_modules/";
const WPROOT = process.env.WPROOT || "/home/user/testenv/wproot";
const SQLITE_ZIP = process.env.SQLITE_ZIP || "/home/user/testenv/sqlite.zip";
const THEME_DIR = path.resolve(process.cwd(), "brickpoint");

const args = process.argv.slice(2);
const SKIP_IMPORT = args.includes("--skip-import");
const RERUN = args.includes("--rerun");
const SYNC_THEME = args.includes("--sync-theme");
const FRESH = args.includes("--fresh");

let failures = 0;
const ok = (msg) => console.log(`  \x1b[32m✔\x1b[0m ${msg}`);
const fail = (msg) => {
  failures++;
  console.log(`  \x1b[31m✖\x1b[0m ${msg}`);
};
const section = (msg) => console.log(`\n== ${msg} ==`);

function syncTheme() {
  execSync(`rm -rf ${WPROOT}/wp-content/themes/brickpoint && cp -r ${THEME_DIR} ${WPROOT}/wp-content/themes/brickpoint`);
}

async function main() {
  if (FRESH) {
    section("Fresh state (wiping database + uploads)");
    execSync(`rm -rf ${WPROOT}/wp-content/database ${WPROOT}/wp-content/uploads`);
    ok("wiped wp-content/database and wp-content/uploads");
  }

  if (SYNC_THEME || !fs.existsSync(`${WPROOT}/wp-content/themes/brickpoint/style.css`)) {
    section("Syncing theme into WordPress");
    syncTheme();
    ok("theme synced");
  }

  const { bootWordPressAndRequestHandler } = await import(NP + "@wp-playground/wordpress/index.js");
  const { loadNodeRuntime } = await import(NP + "@php-wasm/node/index.js");

  section("Booting WordPress 6.7.1 (host-mounted) + SQLite");
  const t0 = Date.now();
  let handler;
  try {
    handler = await bootWordPressAndRequestHandler({
    siteUrl: "http://playground.internal",
    phpVersion: "8.2",
    createPhpRuntime: async () => loadNodeRuntime("8.2", { emscriptenOptions: { processId: 1 } }),
    wordPressInstallMode: "install-from-existing-files-if-needed",
    hooks: {
      beforeWordPressFiles: async (php) => {
        const { createNodeFsMountHandler } = await import(NP + "@php-wasm/node/index.js");
        await php.mount("/wordpress", createNodeFsMountHandler(WPROOT));
      },
    },
    sqliteIntegrationPluginZip: new File(
      [new Uint8Array(fs.readFileSync(SQLITE_ZIP))],
      "sqlite.zip",
      { type: "application/zip" }
    ),
    constants: {
      WP_DEBUG: true,
      WP_DEBUG_LOG: true,
      WP_DEBUG_DISPLAY: true,
      WP_DEVELOPMENT_MODE: "all",
    },
    phpIniEntries: {
      memory_limit: "512M",
      max_execution_time: "300",
      display_errors: "1",
      error_reporting: "32767",
    },
    });
  } catch (e) {
    const body = e?.response?.text ?? String(e);
    const h1 = (String(body).match(/<h1>([^<]*)<\/h1>/) || [])[1];
    const p = (String(body).match(/<p>([\s\S]*?)<\/p>/) || [])[1];
    console.error("BOOT FAILED:", h1, "|", p ? p.replace(/<[^>]+>/g, "").slice(0, 300) : "");
    console.error("BOOT STDERR:", (e?.response?.stderr || "").slice(0, 600));
    console.error("BOOT STDOUT tail:", (e?.response?.stdout || "").slice(-600));
    console.error("ERR:", String(e?.errors?.[0]?.message ?? e?.message ?? e).slice(0, 400));
    throw e;
  }
  console.log(`  booted in ${Math.round((Date.now() - t0) / 100) / 10}s`);

  const php = await handler.getPrimaryPhp();

  // Print WP boot error page message if present.
  {
    try {
      const probe = await php.run({ code: '<?php require "/wordpress/wp-load.php"; echo "WPROOT_OK";' });
      if (!/WPROOT_OK/.test(probe.text ?? "")) console.log("WP probe:", (probe.text || "").slice(0, 200));
    } catch (e) {
      const body = e.response?.text ?? String(e);
      const h1 = (body.match(/<h1>([^<]*)<\/h1>/) || [])[1];
      const p = (body.match(/<p>([\s\S]*?)<\/p>/) || [])[1];
      console.log("WP ERROR:", h1, "|", p ? p.replace(/<[^>]+>/g, "").slice(0, 300) : "");
      console.log("PHP STDERR:", (e.response?.stderr || e.stderr || "").slice(0, 400));
      console.log("BOOT STDOUT:", (e.response?.stdout || "").slice(0, 400));
    }
  }

  const evalPhp = async (code) => {
    const res = await php.run({
      code: `<?php\nif (!function_exists('BP_TEST_BOOTED')) { define('BP_TEST_BOOTED', 1); require '/wordpress/wp-load.php'; }\n${code}`,
    });
    if (res.exitCode !== 0 || /Fatal error/.test(res.text ?? "")) {
      throw new Error("PHP failed: " + (res.text ?? "") + (res.stderr ?? ""));
    }
    return res.text ?? "";
  };
  const request = async (url) => {
    let res = await handler.request({ url, method: "GET" });
    let hops = 0;
    while (res.httpStatusCode >= 300 && res.httpStatusCode < 400 && hops < 4) {
      const locHdr = (res.headers?.location || res.headers?.Location || [])[0];
      if (!locHdr) break;
      res = await handler.request({ url: locHdr.startsWith("http") ? locHdr.replace("http://playground.internal", "") : locHdr, method: "GET" });
      hops++;
    }
    // Copy fields explicitly: spreading PHPResponse drops prototype getters.
    return { statusCode: res.httpStatusCode, text: res.text, stderr: res.stderr, headers: res.headers, exitCode: res.exitCode };
  };

  // Playground force-writes date-based permalinks after each boot; assert
  // the demo structure like the importer does on real sites.
  {
    const res = await evalPhp(`
      global $wp_rewrite;
      $wp_rewrite->set_permalink_structure('/%postname%/');
      $wp_rewrite->flush_rules(true);
      echo 'flushed';
    `);
    ok("rewrite rules asserted for /%postname%/ (" + res + ")");
  }

  section("Activation state");
  {
    const state = await evalPhp(
      `echo get_option('stylesheet') . '|' . implode(',', get_option('active_plugins', array()));`
    );
    if (state.includes("brickpoint") && state.includes("elementor/elementor.php")) ok(state);
    else {
      await evalPhp(
        `update_option('active_plugins', array('elementor/elementor.php')); switch_theme('brickpoint'); do_action('after_switch_theme');`
      );
      const state2 = await evalPhp(`echo get_option('stylesheet') . '|' . implode(',', get_option('active_plugins', array()));`);
      state2.includes("brickpoint") ? ok(`activated: ${state2}`) : fail(`could not activate: ${state2}`);
    }
  }

  if (!SKIP_IMPORT) {
    section("Running demo importer (all steps)");
    const stepList = JSON.parse(await evalPhp(`echo json_encode(array_keys(BP_Demo_Importer::steps()));`));
    for (const step of stepList) {
      try {
        const msg = JSON.parse(await evalPhp(`echo json_encode(array('m' => BP_Demo_Importer::step_${step}()));`));
        ok(`${step}: ${msg.m}`);
      } catch (e) {
        fail(`step ${step} FAILED: ${String(e.message).slice(0, 600)}`);
      }
    }

    if (RERUN) {
      section("Re-running importer (idempotency check)");
      for (const step of stepList) {
        try {
          await evalPhp(`echo json_encode(array('m' => BP_Demo_Importer::step_${step}()));`);
          ok(`re-run ${step}`);
        } catch (e) {
          fail(`re-run ${step} FAILED: ${String(e.message).slice(0, 200)}`);
        }
      }
    }

    section("Content integrity");
    const integrity = await evalPhp(`
      $out = array(
        'products' => (int) wp_count_posts('bp_product')->publish,
        'videos' => (int) wp_count_posts('bp_video')->publish,
        'projects' => (int) wp_count_posts('bp_project')->publish,
        'locations' => (int) wp_count_posts('bp_location')->publish,
        'posts' => (int) wp_count_posts('post')->publish,
        'pages' => (int) wp_count_posts('page')->publish,
        'templates' => (int) wp_count_posts('elementor_library')->publish,
        'product_cats' => count(get_terms(array('taxonomy'=>'bp_product_category','hide_empty'=>false))),
        'video_cats' => count(get_terms(array('taxonomy'=>'bp_video_category','hide_empty'=>false))),
        'project_cats' => count(get_terms(array('taxonomy'=>'bp_project_category','hide_empty'=>false))),
        'media' => (int) wp_count_posts('attachment')->inherit,
        'menus' => count(wp_get_nav_menus()),
        'front_page' => (int) get_option('page_on_front'),
        'blog_page' => (int) get_option('page_for_posts'),
        'permalink' => get_option('permalink_structure'),
        'logo' => (int) get_theme_mod('custom_logo'),
        'hero_video' => get_theme_mod('bp_hero_video') !== '',
        'featured_products' => count(get_posts(array('post_type'=>'bp_product','meta_key'=>'_bp_featured','meta_value'=>'1','fields'=>'ids','posts_per_page'=>20))),
        'thumbs' => count(get_posts(array('post_type'=>'any','meta_key'=>'_thumbnail_id','fields'=>'ids','posts_per_page'=>100,'numberposts'=>100))),
      );
      echo json_encode($out);
    `);
    const I = JSON.parse(integrity);
    console.log("  ", JSON.stringify(I));
    const checks = [
      ["12 products", I.products === 12],
      ["6 videos", I.videos === 6],
      ["6 projects", I.projects === 6],
      ["4 locations", I.locations === 4],
      ["6 posts", I.posts === 6],
      ["11 pages", I.pages === 11],
      ["19 Elementor docs (18 templates + kit)", I.templates === 19],
      ["8 product categories", I.product_cats === 8],
      ["6 video categories", I.video_cats === 6],
      ["3 project categories", I.project_cats === 3],
      ["media imported (>=26)", I.media >= 26],
      ["3 menus", I.menus === 3],
      ["front page set", I.front_page > 0],
      ["blog page set", I.blog_page > 0],
      ["pretty permalinks", I.permalink.includes("postname")],
      ["custom logo", I.logo > 0],
      ["hero video configured", I.hero_video === "1" || I.hero_video === true],
      ["featured products >= 6", I.featured_products >= 6],
      ["featured images assigned (>= 30)", I.thumbs >= 30],
    ];
    for (const [label, pass] of checks) (pass ? ok : fail)(label);

    section("Elementor templates & conditions");
    const conditions = await evalPhp(`
      $out = array();
      $q = new WP_Query(array('post_type'=>'elementor_library','posts_per_page'=>-1,'post_status'=>'publish'));
      foreach ($q->posts as $p) {
        $out[] = array(
          'name' => $p->post_name ?: sanitize_title($p->post_title),
          'type' => get_post_meta($p->ID, '_elementor_template_type', true),
          'loc' => get_post_meta($p->ID, '_elementor_location', true),
          'cond' => get_post_meta($p->ID, '_elementor_conditions', true),
          'data' => (bool) get_post_meta($p->ID, '_elementor_data', true),
        );
      }
      echo json_encode($out);
    `);
    const conds = JSON.parse(conditions);
    for (const c of conds) {
      const condStr = Array.isArray(c.cond) ? c.cond.join(",") : String(c.cond || "-");
      (c.data ? ok : fail)(`${c.name} [${c.type}${c.loc ? "/" + c.loc : ""}] conditions: ${condStr}`);
    }

    section("Homepage is Elementor-built");
    const homeBuilt = await evalPhp(
      `echo bp_is_elementor_built((int) get_option('page_on_front')) ? 'yes' : 'no';`
    );
    (homeBuilt === "yes" ? ok : fail)(`home page built with Elementor: ${homeBuilt}`);
  }

  section("Frontend rendering");
  const pages = [
    ["/", "home", ["bp-hero", "bp-cat-card", "bp-card", "bp-play-btn", "Masha Allah"]],
    ["/about/", "about", ["bp-pagehead", "Syed Iftikhar Haider"]],
    ["/contact/", "contact", ["bp-pagehead", "WhatsApp", "bp-loc-card"]],
    ["/products/", "products archive", ["bp-pagehead", "Awami", "Order on WhatsApp"]],
    ["/videos/", "videos archive", ["bp-pagehead", "bp-play-btn"]],
    ["/projects/", "projects archive", ["bp-pagehead", "Gulberg"]],
    ["/locations/", "locations archive", ["bp-pagehead", "Bhatta", "google.com/maps"]],
    ["/blog/", "blog archive", ["bp-pagehead", "Ring Test"]],
    ["/products/ss7-premium-bricks/", "product single", ["Specifications", "Order on WhatsApp", "7.5+ MPa"]],
    ["/videos/ss7-manufacturing-tour/", "video single", ["<video", ".mp4"]],
    ["/projects/gulberg-commercial-plaza/", "project single", ["Illustrative", "Gulberg"]],
    ["/locations/masha-allah-bricks-bhatta/", "location single", ["google.com/maps", "Bhaini Road"]],
    ["/brick-ring-test/", "post single", ["Related Articles", "metallic"]],
    ["/product-category/ss7-bricks/", "product category", ["Flagship", "bp-card"]],
    ["/video-category/quality/", "video category", ["Ring Test"]],
    ["/privacy-policy/", "privacy", ["Privacy"]],
    ["/terms-and-conditions/", "terms", ["delivery"]],
    ["/?s=brick", "search", ["Search"]],
  ];

  for (const [url, name, expects] of pages) {
    try {
      const res = await request(url);
      const html = res.text || "";
      const hasFatal = html.includes("Fatal error") || res.statusCode >= 500;
      // Theme defects fail the run; plugin/core PHP 8.2 deprecations are
      // reported but tolerated (e.g. Elementor's offline API call).
      const warnings = [...html.matchAll(/<b>(?:Warning|Notice|Deprecated|Parse error)<\/b>:\s+[^<]*<b>([^<]*)<\/b> on line <b>(\d+)</g)]
        .filter((m) => /wp-content\/themes\//.test(m[1]) || /wp-content\/themes\//.test(m[0]));
      const pluginWarnings = [...html.matchAll(/<b>(?:Warning|Notice|Deprecated|Parse error)<\/b>:\s+([^<]{0,140})/g)]
        .filter((m) => !m[1].includes("themes/"));
      const hasWarnings = warnings.length > 0;
      const missing = expects.filter((e) => !html.includes(e));
      const dump = path.dirname(new URL(import.meta.url).pathname) + "/../.rig-html";
      fs.mkdirSync(dump, { recursive: true });
      fs.writeFileSync(`${dump}/${name.replace(/[^a-z0-9]+/gi, "-")}.html`, html);
      if (hasFatal) fail(`${name} (${url}): FATAL — ${html.slice(Math.max(0, html.indexOf("Fatal error") - 120), html.indexOf("Fatal error") + 250)}`);
      else if (res.statusCode >= 400) fail(`${name} (${url}): HTTP ${res.statusCode} — ${(res.text||'').slice(0,120)}`);
      else if (hasWarnings) {
        const all = [...html.matchAll(/<b>(?:Warning|Notice|Deprecated|Parse error)<\/b>:\s+([^<]{0,180})/g)].map((m) => m[1]);
        fail(`${name} (${url}): PHP warning present — ${all.slice(0, 3).join(" || ")}`);
      } else if (missing.length) fail(`${name} (${url}): missing ${JSON.stringify(missing)} [status ${res.statusCode}, ${html.length}b, exit ${res.exitCode}]`);
      else ok(`${name} (${url}) [${res.statusCode}, ${Math.round(html.length / 1024)}KB]`);
    } catch (e) {
      fail(`${name} (${url}): ${String(e.message).slice(0, 200)}`);
    }
  }

  section("Homepage specifics");
  {
    const res = await request("/");
    const html = res.text || "";
    const wa = (html.match(/https:\/\/wa\.me\/923152850818/g) || []).length;
    (wa > 0 ? ok : fail)(`wa.me links present (${wa})`);
    (html.includes("Masha Allah Bricks Co. • Fine Bricks Co. • SS7 Bricks") ? ok : fail)("topbar units line");
    (html.includes('class="bp-menu"') ? ok : fail)("primary menu rendered");
    (html.includes("ss7-brick") ? ok : fail)("SS7 floating brick");
    (html.includes("<video") || html.includes("poster=") ? ok : fail)("hero video/poster");
    (html.includes("elementor-widget") ? ok : fail)("rendered via Elementor widgets");
  }

  section("Menu URLs resolve");
  {
    const menuUrls = JSON.parse(await evalPhp(`
      $loc = get_theme_mod('nav_menu_locations');
      $items = isset($loc['primary']) ? wp_get_nav_menu_items($loc['primary']) : array();
      echo json_encode($items ? array_map(function($i){ return $i->url; }, $items) : array());
    `));
    for (const url of menuUrls) {
      const p = url.replace("http://playground.internal", "") || "/";
      const res = await request(p);
      (res.statusCode < 400 ? ok : fail)(`${p} → ${res.statusCode}`);
    }
  }

  console.log(
    `\n${failures === 0 ? "\x1b[32mALL CHECKS PASSED\x1b[0m" : `\x1b[31m${failures} CHECK(S) FAILED\x1b[0m`}`
  );
  process.exit(failures === 0 ? 0 : 1);
}

main().catch((e) => {
  console.error("RIG FAILURE:", String(e).slice(0, 900));
  process.exit(2);
});
