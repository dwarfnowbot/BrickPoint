import fs from "node:fs";

const NP = "/home/user/testenv/node_modules/";
const { bootRequestHandler } = await import(NP + "@wp-playground/wordpress/index.js");
const { loadNodeRuntime } = await import(NP + "@php-wasm/node/index.js");

console.log("Booting...");
const t0 = Date.now();
try {
  const handler = await bootRequestHandler({
    siteUrl: "http://playground.internal",
    phpVersion: "8.2",
    createPhpRuntime: async () => loadNodeRuntime("8.2", { emscriptenOptions: { processId: 1 } }),
    wordPressZip: new File([new Uint8Array(fs.readFileSync("/tmp/wpenv/wordpress.zip"))], "wordpress.zip", { type: "application/zip" }),
    sqliteIntegrationPluginZip: new File([new Uint8Array(fs.readFileSync("/tmp/wpenv/sqlite.zip"))], "sqlite.zip", { type: "application/zip" }),
    onProgress: (c) => console.log("progress:", c),
    constants: { WP_DEBUG: false },
  });
  console.log("Booted in", Date.now() - t0, "ms");
  const php = await handler.getPrimaryPhp();
  console.log("/", await php.listFiles("/"));
  console.log("/wordpress", await php.listFiles("/wordpress"));
  const res = await handler.request({ url: "/", method: "GET" });
  console.log("request /:", res.statusCode, (res.text || "").slice(0, 200));
} catch (e) {
  console.error("BOOT ERR:", e.message?.slice(0, 500));
  process.exit(1);
}
