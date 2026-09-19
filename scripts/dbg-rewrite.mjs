import fs from "node:fs";
const NP = "/home/user/testenv/node_modules/";
const { bootWordPressAndRequestHandler } = await import(NP + "@wp-playground/wordpress/index.js");
const { loadNodeRuntime, createNodeFsMountHandler } = await import(NP + "@php-wasm/node/index.js");
const h = await bootWordPressAndRequestHandler({
	siteUrl: "http://playground.internal",
	phpVersion: "8.2",
	createPhpRuntime: async () => loadNodeRuntime("8.2", { emscriptenOptions: { processId: 1 } }),
	wordPressInstallMode: "do-not-attempt-installing",
	hooks: { beforeWordPressFiles: async php => php.mount("/wordpress", createNodeFsMountHandler("/home/user/testenv/wproot")) },
	sqliteIntegrationPluginZip: new File([new Uint8Array(fs.readFileSync("/home/user/testenv/sqlite.zip"))], "sqlite.zip", { type: "application/zip" }),
	constants: { WP_DEBUG: true, WP_DEBUG_LOG: true, WP_DEBUG_DISPLAY: true },
	phpIniEntries: { memory_limit: "512M", display_errors: "1" },
});
const php = await h.getPrimaryPhp();
// replicate the rig bootstrap
await php.run({ code: `<?php
require '/wordpress/wp-load.php';
if ( '/%postname%/' !== get_option('permalink_structure') ) {
  update_option('permalink_structure', '/%postname%/');
  global $wp_rewrite;
  $wp_rewrite->flush_rules(true);
}
echo "bootstrap done, struct=" . get_option('permalink_structure') . "\\n";
` });
const r = await php.run({ code: `<?php
require '/wordpress/wp-load.php';
echo "struct=" . get_option('permalink_structure') . "\\n";
$rr = get_option('rewrite_rules');
echo "rules option: " . (is_array($rr) ? count($rr) : var_export($rr, true)) . "\\n";
$p = get_page_by_path('brick-ring-test', OBJECT, 'post');
echo "post: " . ($p ? $p->ID . " " . $p->post_status . " " . $p->post_name : 'missing') . "\\n";
global $wp_rewrite;
echo "has rule brick-ring-test: " . var_export(isset($rr['brick-ring-test(/page/([0-9]+)?/?)?$']), true) . "\\n";
$match = $wp_rewrite->match;
echo "match=" . var_export($match, true) . "\\n";
` });
console.log(r.text);
const rr2 = await php.run({ code: `<?php require '/wordpress/wp-load.php'; $rr = get_option('rewrite_rules'); echo "rules now: " . (is_array($rr)?count($rr):'x') . "\\n"; $i=0; foreach ($rr as $k => $v) { if (++$i <= 8 || strpos($k, 'brick') !== false || strpos($k, '([^/]+)') !== false) echo "RULE: " . $k . " => " . $v . "\\n"; }` });
console.log(rr2.text);
const res = await h.request({ url: "/brick-ring-test/" });
console.log("/brick-ring-test/ →", res.httpStatusCode, (res.text || "").length, "loc:", JSON.stringify(res.headers?.location));
const log = await php.readFileAsText("/wordpress/wp-content/bp-query-log.txt");
console.log("QUERY LOG:\n" + log);
process.exit(0);
