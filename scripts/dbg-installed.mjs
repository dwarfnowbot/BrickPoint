import fs from "node:fs";
const NP = "/home/user/testenv/node_modules/";
const { bootWordPressAndRequestHandler } = await import(NP + "@wp-playground/wordpress/index.js");
const { loadNodeRuntime, createNodeFsMountHandler } = await import(NP + "@php-wasm/node/index.js");
const h = await bootWordPressAndRequestHandler({
	siteUrl: "http://playground.internal",
	phpVersion: "8.2",
	createPhpRuntime: async () => loadNodeRuntime("8.2", { emscriptenOptions: { processId: 1 } }),
	wordPressInstallMode: "install-from-existing-files-if-needed",
	hooks: { beforeWordPressFiles: async php => php.mount("/wordpress", createNodeFsMountHandler("/home/user/testenv/wproot")) },
	sqliteIntegrationPluginZip: new File([new Uint8Array(fs.readFileSync("/home/user/testenv/sqlite.zip"))], "sqlite.zip", { type: "application/zip" }),
	constants: { WP_DEBUG: true, WP_DEBUG_LOG: true, WP_DEBUG_DISPLAY: true },
	phpIniEntries: { memory_limit: "512M", display_errors: "1" },
});
const php = await h.getPrimaryPhp();
const r0 = await php.run({ code: `<?php echo "BOOT struct=" . var_export(file_get_contents("php://input"), true) . " "; ` });
const r = await php.run({ code: `<?php
require '/wordpress/wp-load.php';
echo "struct_at_boot=" . get_option('permalink_structure') . "\n";
echo "onProgress-installed-check ran: site title=" . get_option('blogname') . "\n";
global $wp_version, $table_prefix;
echo "version=" . $wp_version . " prefix=" . $table_prefix . "\\n";
echo "blog_installed=" . var_export(is_blog_installed(), true) . "\\n";
echo "siteurl=" . get_option('siteurl') . "\\n";
echo "tables: ";
foreach (array('posts', 'options', 'users') as $t) {
  global $wpdb;
  echo $t . "=" . var_export($wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->$t}"), true) . " ";
}
echo "\\n";
echo "missed_tables=" . json_encode($wpdb->get_results("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE '%posts%'", ARRAY_A)) . "\\n";
` });
console.log(r.text);
console.log("STDERR:", (r.stderr || "").slice(0, 300));
process.exit(0);
