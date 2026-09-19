/**
 * BrickPoint — demo media processor.
 *
 * Converts raw generated images into optimized theme media and builds the
 * bundled MP4 demo videos (Ken Burns slideshows) with ffmpeg.
 *
 * Run: node scripts/process-media.mjs
 * Requires: ffmpeg binary (auto-detected or FFMPEG env var).
 */
import { execFileSync } from "node:child_process";
import fs from "node:fs";
import path from "node:path";
import os from "node:os";

const RAW = process.env.RAW_MEDIA || "/home/user/demo-media/raw";
const MEDIA = path.resolve(process.cwd(), "brickpoint/demo-content/media");
const VIDEOS = path.resolve(process.cwd(), "brickpoint/demo-content/videos");
fs.mkdirSync(MEDIA, { recursive: true });
fs.mkdirSync(VIDEOS, { recursive: true });

const FFMPEG = process.env.FFMPEG || "/tmp/ffm/imageio_ffmpeg/binaries/ffmpeg-linux-x86_64-v7.0.2";

function run(args) {
  execFileSync(FFMPEG, ["-hide_banner", "-loglevel", "error", "-y", ...args], { stdio: "inherit" });
}

/** Fit/crop an image to exact dimensions with high quality. */
function image(src, out, w, h, q = 4) {
  run([
    "-i", src,
    "-vf", `scale=${w}:${h}:force_original_aspect_ratio=increase,crop=${w}:${h},unsharp=3:3:0.4`,
    "-q:v", String(q),
    out,
  ]);
  console.log("image:", path.basename(out), `${w}x${h}`);
}

/** Build a Ken Burns slideshow MP4 from stills. */
function slideshow(stills, out, secondsPerStill = 3.2) {
  const dir = fs.mkdtempSync(path.join(os.tmpdir(), "bpvid-"));
  const list = path.join(dir, "list.txt");
  const size = "1280x720";

  const clips = stills.map((src, i) => {
    const clip = path.join(dir, `c${i}.mp4`);
    // Zoom-in pan for even indexes, zoom-out for odd.
    const z = i % 2 === 0
      ? `scale=2560:1440,zoompan=z='min(zoom+0.0012,1.25)':x='iw/2-(iw/zoom/2)':y='ih/2-(ih/zoom/2)':d=${Math.round(secondsPerStill * 25)}:s=${size}:fps=25`
      : `scale=2560:1440,zoompan=z='if(lte(zoom,1.0),1.25,max(1.001,zoom-0.0012))':x='iw/2-(iw/zoom/2)':y='ih/2-(ih/zoom/2)':d=${Math.round(secondsPerStill * 25)}:s=${size}:fps=25`;
    run([
      "-loop", "1", "-i", src,
      "-vf", z,
      "-t", String(secondsPerStill),
      "-c:v", "libx264", "-preset", "medium", "-crf", "27", "-pix_fmt", "yuv420p",
      clip,
    ]);
    return clip;
  });

  const concatList = clips.map((c) => `file '${c}'`).join("\n");
  fs.writeFileSync(path.join(dir, "concat.txt"), concatList);

  // Crossfade concat + gentle fade in/out.
  run([
    "-f", "concat", "-safe", "0", "-i", path.join(dir, "concat.txt"),
    "-vf", "fade=t=in:st=0:d=0.8,fade=t=out:st=" + (secondsPerStill * stills.length - 0.9) + ":d=0.9,format=yuv420p",
    "-c:v", "libx264", "-preset", "medium", "-crf", "28", "-movflags", "+faststart",
    "-an",
    out,
  ]);

  fs.rmSync(dir, { recursive: true, force: true });
  console.log("video:", path.basename(out));
}

const raw = (f) => path.join(RAW, f);
const out = (f) => path.join(MEDIA, f);

/* ------------------------------------------------------------------ */
/* Processed stills (theme media)                                      */
/* ------------------------------------------------------------------ */
if (fs.existsSync(raw("hero-bricks.jpg"))) {
  image(raw("hero-bricks.jpg"), out("hero-bricks.jpg"), 1600, 900);
  image(raw("hero-bricks.jpg"), out("hero-thumb.jpg"), 640, 360, 5);
}
if (fs.existsSync(raw("ss7-bricks.jpg"))) image(raw("ss7-bricks.jpg"), out("ss7-bricks.jpg"), 1200, 900);
for (const f of ["cat-awami", "cat-fine", "cat-tile", "cat-blocks", "cat-pavers", "cat-crush", "cat-sand"]) {
  if (fs.existsSync(raw(`${f}.jpg`))) image(raw(`${f}.jpg`), out(`${f}.jpg`), 800, 450);
}
if (fs.existsSync(raw("kiln-1.jpg"))) image(raw("kiln-1.jpg"), out("kiln-1.jpg"), 1600, 900);
for (const f of ["kiln-2", "yard-trucks", "proj-housing", "proj-plaza", "proj-wall", "blog-1", "blog-2", "blog-3", "blog-4", "blog-5"]) {
  if (fs.existsSync(raw(`${f}.jpg`))) image(raw(`${f}.jpg`), out(`${f}.jpg`), 1200, 675);
}

/* ------------------------------------------------------------------ */
/* Bundled demo videos                                                 */
/* ------------------------------------------------------------------ */
const M = (f) => path.join(MEDIA, f);
const have = (f) => fs.existsSync(M(f));

// Hero tour: hero + kiln + awami bricks.
if (have("hero-bricks.jpg") && have("kiln-1.jpg")) {
  slideshow([M("hero-bricks.jpg"), M("kiln-1.jpg"), M("cat-awami.jpg")], path.join(VIDEOS, "hero-tour.mp4"), 3.4);
}
// SS7 manufacturing.
if (have("ss7-bricks.jpg") && have("cat-fine.jpg")) {
  slideshow([M("ss7-bricks.jpg"), M("cat-fine.jpg"), M("cat-tile.jpg")], path.join(VIDEOS, "ss7-manufacturing.mp4"), 3.2);
}
// Quality check.
if (have("cat-fine.jpg") && have("ss7-bricks.jpg")) {
  slideshow([M("cat-fine.jpg"), M("ss7-bricks.jpg")], path.join(VIDEOS, "quality-check.mp4"), 3.2);
}
// Delivery.
if (have("cat-awami.jpg") && have("kiln-1.jpg")) {
  slideshow([M("cat-awami.jpg"), M("kiln-1.jpg"), M("cat-crush.jpg")], path.join(VIDEOS, "delivery.mp4"), 3.2);
}
// Bhatta tour.
if (have("kiln-1.jpg")) {
  slideshow([M("kiln-1.jpg"), M("cat-sand.jpg")], path.join(VIDEOS, "bhatta-tour.mp4"), 3.6);
}
// Project montage.
if (have("cat-blocks.jpg") && have("cat-pavers.jpg")) {
  slideshow([M("cat-blocks.jpg"), M("cat-pavers.jpg"), M("cat-crush.jpg")], path.join(VIDEOS, "project-montage.mp4"), 3.2);
}

console.log("Media processing complete.");
