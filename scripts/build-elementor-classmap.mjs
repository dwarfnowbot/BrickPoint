/**
 * Build a classmap autoloader mu-plugin for the Elementor GitHub SOURCE build.
 *
 * The wordpress.org Elementor release ships a composer vendor/ dir; the GitHub
 * source tree does not, so its internal autoloader (classes_map) is incomplete.
 * This shim maps every Elementor\* class found in the source to its file using
 * VFS paths (/wordpress/...) and must live in the test env's mu-plugins only —
 * it is never shipped with the theme.
 *
 * Run: node scripts/build-elementor-classmap.mjs [pluginDir] [outFile]
 */
import fs from "node:fs";
import path from "node:path";

const PLUGIN_HOST = process.argv[2] || "/home/user/testenv/wproot/wp-content/plugins/elementor";
const OUT = process.argv[3] || "/home/user/testenv/wproot/wp-content/mu-plugins/zz-test-elementor-classmap.php";
const HOST_ROOT = "/home/user/testenv/wproot";
const VFS_ROOT = "/wordpress";

const mainFile = ["elementor.php", "elementor-pro.php"].map((f) => PLUGIN_HOST + "/" + f).find((f) => fs.existsSync(f));
if (!mainFile) {
	console.error("Elementor/Elementor Pro source not found at " + PLUGIN_HOST);
	process.exit(1);
}

const NS_FILTER = process.argv[4] || "";
const mapping = {};
const classRe = /^\s*(?:abstract\s+|final\s+)?(?:class|interface|trait)\s+(\w+)/gm;
const nsRe = /^\s*namespace\s+([\w\\]+)\s*;/m;

(function walk(dir) {
	for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
		const p = path.join(dir, e.name);
		if (e.isDirectory()) walk(p);
		else if (e.name.endsWith(".php")) {
			const src = fs.readFileSync(p, "utf8");
			const ns = src.match(nsRe);
			const prefix = ns ? ns[1] + "\\" : "";
			if (NS_FILTER && !prefix.startsWith(NS_FILTER)) continue;
			let m;
			classRe.lastIndex = 0;
			while ((m = classRe.exec(src))) {
				const fq = prefix + m[1];
				if (!mapping[fq]) mapping[fq] = p;
			}
		}
	}
})(PLUGIN_HOST);

const fnName = "bp_test_" + (NS_FILTER ? NS_FILTER.replace(/\\/g, "_").toLowerCase() + "_" : "elem_") + "classmap_autoload";
const lines = [
	"<?php",
	"/**",
	" * TEST-ENV ONLY: classmap autoloader for the Elementor GitHub SOURCE build.",
	" * Real installs use the wordpress.org release (composer vendor/ included),",
	" * so this shim is never shipped with the theme.",
	" */",
	"if ( ! defined( 'ABSPATH' ) ) { exit; }",
	`function ${fnName}( $class ) {`,
	"  static $map = null;",
	"  if ( null === $map ) { $map = array(",
];
let n = 0;
for (const [fq, p] of Object.entries(mapping)) {
	const vp = VFS_ROOT + p.slice(HOST_ROOT.length);
	lines.push(`    '${fq.replace(/\\/g, "\\\\")}' => '${vp}',`);
	n++;
}
lines.push("  ); }");
lines.push("  if ( isset( $map[ $class ] ) && ! class_exists( $class, false ) ) {");
lines.push("    require $map[ $class ];");
lines.push("  }");
lines.push("}");
lines.push(`spl_autoload_register( '${fnName}', true, false );`, "");

fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, lines.join("\n"));
console.log(`classmap mu-plugin written: ${n} classes, ${fs.statSync(OUT).size} bytes`);
