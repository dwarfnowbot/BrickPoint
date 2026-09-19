/**
 * Lint every theme PHP file with the Playground PHP runtime (php -l).
 * No WordPress boot needed: mount the theme dir into a bare PHP runtime.
 */
import fs from "node:fs";
import path from "node:path";

const NP = "/home/user/testenv/node_modules/";
const THEME = "/home/user/BrickPoint/brickpoint";

const { loadNodeRuntime, createNodeFsMountHandler } = await import(NP + "@php-wasm/node/index.js");
const { PHP } = await import(NP + "@php-wasm/universal/index.js");

const files = [];
(function walk(dir) {
	for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
		const p = path.join(dir, e.name);
		if (e.isDirectory()) walk(p);
		else if (e.name.endsWith(".php")) files.push(p);
	}
})(THEME);
files.sort();

const runtimeId = await loadNodeRuntime("8.2", { emscriptenOptions: { processId: 7 } });
const php = new PHP(runtimeId);
await php.mount("/theme", createNodeFsMountHandler(THEME));

let bad = 0;
let skipped = 0;
for (const f of files) {
	const vfs = "/theme" + f.slice(THEME.length);
	let out = "";
	try {
		const r = await php.run({
			code: `<?php define('ABSPATH', '/theme/'); require ${JSON.stringify(vfs)};`,
		});
		out = (r.text ?? "") + (r.stderr ?? "");
		if (/Fatal error|Parse error|Warning/.test(out) && !/No syntax errors/.test(out)) {
			// Undefined-function etc. are expected without WP loaded; parse errors are not.
			if (/Parse error|syntax error|Unclosed/i.test(out)) {
				bad++;
				console.log("PARSE FAIL", f.replace(THEME + "/", ""), "->", out.replace(/<[^>]+>/g, "").split("\n").slice(0, 3).join(" | "));
			} else {
				skipped++;
			}
		}
	} catch (e) {
		out = String(e?.response?.text ?? e?.response?.stderr ?? e?.message ?? e);
		if (/Parse error|syntax error|Unclosed/i.test(out)) {
			bad++;
			console.log("PARSE FAIL", f.replace(THEME + "/", ""), "->", out.replace(/<[^>]+>/g, "").split("\n").slice(0, 3).join(" | "));
		} else {
			skipped++;
		}
	}
}
console.log(`Linted ${files.length} files, ${bad} parse errors, ${skipped} runtime-skipped (WP symbols absent, expected).`);
process.exit(bad ? 1 : 0);
