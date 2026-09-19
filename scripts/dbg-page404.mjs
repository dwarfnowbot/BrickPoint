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
// unconditional flush like the rig
await php.run({ code: `<?php
require '/wordpress/wp-load.php';
global $wp_rewrite;
update_option('permalink_structure', '/%postname%/');
$wp_rewrite->permalink_structure = '/%postname%/';
$wp_rewrite->flush_rules(true);
echo 'flushed; struct=' . get_option('permalink_structure') . " rules=" . count((array) get_option('rewrite_rules')) . "\\n";
` });
const res = await h.request({ url: "/about/" });
console.log("/about/ →", res.httpStatusCode, (res.text || "").length);
const probe = await php.run({ code: `<?php
require '/wordpress/wp-load.php';
echo "struct=" . get_option('permalink_structure') . "\\n";
$rules = (array) get_option('rewrite_rules');
$i = 0;
foreach ($rules as $k => $v) { $i++; if ($i <= 3 || stripos($k, 'about') !== false || $k === '([^/]+)(?:/([0-9]+))?/?$' || $k === '(.?.+?)(?:/([0-9]+))?/?$') echo "RULE[$i]: $k => $v\\n"; }
$pg = get_page_by_path('about');
echo "page about: " . ($pg ? $pg->ID . " " . $pg->post_status : "MISSING") . "\\n";
$q = new WP_Query(array('pagename' => 'about'));
echo "query pagename: found=" . count($q->posts) . " is404=" . var_export($q->is_404, true) . "\\n";
$log = file_get_contents(WP_CONTENT_DIR . '/bp-query-log.txt');
echo "LOGTAIL:\\n" . implode("\\n", array_slice(explode("\\n", trim($log)), -3)) . "\\n";
` });
console.log(probe.text);
process.exit(0);
