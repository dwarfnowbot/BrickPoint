/**
 * BrickPoint — Elementor template JSON builder.
 *
 * Generates the Elementor template JSON files shipped in the theme under
 * `brickpoint/elementor/templates/`. The demo importer reads these files,
 * substitutes {{tokens}} and inserts them as real Elementor templates with
 * Theme Builder display conditions.
 *
 * Run: node scripts/build-templates.mjs
 */
import fs from "node:fs";
import path from "node:path";

const OUT = path.resolve(process.cwd(), "brickpoint/elementor/templates");
fs.mkdirSync(OUT, { recursive: true });

let idCounter = 0;
const uid = () => {
  idCounter += 1;
  return (idCounter.toString(36) + "bp0000").slice(0, 7).padEnd(7, "0");
};

const widget = (widgetType, settings = {}) => ({
  id: uid(),
  elType: "widget",
  widgetType,
  settings,
  elements: [],
});

const column = (elements, settings = {}) => ({
  id: uid(),
  elType: "column",
  settings: { _column_size: 100, _inline_size: null, ...settings },
  elements,
  isInner: false,
});

const section = (elements, settings = {}) => ({
  id: uid(),
  elType: "section",
  settings: {
    layout: "boxed",
    ...settings,
  },
  elements: elements.map((els) => column(els)),
  isInner: false,
});

const pad = (top, bottom = top) => ({
  unit: "px",
  top: String(top),
  right: "20",
  bottom: String(bottom),
  left: "20",
  isLinked: false,
});

const darkBg = { background_background: "classic", background_color: "#141210" };
const charcoalBg = { background_background: "classic", background_color: "#1C1A17" };

const template = ({ name, title, bpType, condition, location, content, pageSettings = {} }) => {
  const doc = {
    content,
    page_settings: pageSettings,
    version: "0.4",
    title,
    type: bpType === "page" ? "page" : bpType === "section" ? "section" : "page",
    bp_name: name,
    bp_type: bpType,
    bp_condition: condition,
    bp_location: location || "",
  };
  fs.writeFileSync(path.join(OUT, `${name}.json`), JSON.stringify(doc, null, 2) + "\n");
};

/* ------------------------------------------------------------------ */
/* HEADER                                                              */
/* ------------------------------------------------------------------ */
template({
  name: "header",
  title: "BrickPoint Header",
  bpType: "header",
  condition: [{ type: "include", name: "general" }],
  content: [section([[widget("bp-site-header")]], { padding: pad(0, 0) })],
});

/* ------------------------------------------------------------------ */
/* FOOTER                                                              */
/* ------------------------------------------------------------------ */
template({
  name: "footer",
  title: "BrickPoint Footer",
  bpType: "footer",
  condition: [{ type: "include", name: "general" }],
  content: [section([[widget("bp-site-footer")]], { padding: pad(0, 0) })],
});

/* ------------------------------------------------------------------ */
/* HOMEPAGE (content of the Home page)                                 */
/* ------------------------------------------------------------------ */
template({
  name: "home",
  title: "BrickPoint Homepage",
  bpType: "page",
  content: [
    // 1 — Hero (LM Arena: dark ink hero with video + SS7 float)
    section(
      [
        [
          widget("bp-hero", {
            kicker: "Masha Allah • Fine Bricks • SS7",
            title: "Building Strength. Delivering Quality. Shaping Tomorrow.",
            subtitle:
              "Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.",
            btn1_label: "Explore Products",
            btn1_link: { url: "{{archive:bp_product}}", is_external: "", nofollow: "" },
            btn2_label: "Request a Quote",
            btn2_link: { url: "{{page:contact}}", is_external: "", nofollow: "" },
            btn3_label: "WhatsApp Us",
            btn3_link: { url: "{{wa}}", is_external: "yes", nofollow: "" },
            media_type: "video",
            video_url: { url: "{{video:hero}}", id: "{{video_id:hero}}" },
            poster_image: { url: "{{media:hero}}", id: "{{media_id:hero}}" },
            show_ss7: "yes",
            ss7_text: "Flagship Bricks",
          }),
        ],
      ],
      { padding: pad(0, 0), ...darkBg }
    ),
    // 2 — Why BrickPoint (product categories grid)
    section([[widget("bp-product-categories", { count: 8, show_heading: "yes" })]], {
      padding: pad(56, 56),
    }),
    // 3 — Featured products (dark band)
    section(
      [
        [
          widget("bp-product-grid", {
            source: "featured",
            count: 8,
            show_heading: "yes",
            eyebrow: "Featured Products",
            title: "Materials contractors ask for by name",
            wa_label: "Order on WhatsApp",
            details_label: "Details",
            show_all_btn: "yes",
            all_btn_label: "Browse All Products",
            all_btn_link: { url: "{{archive:bp_product}}", is_external: "", nofollow: "" },
          }),
        ],
      ],
      { padding: pad(56, 56), ...charcoalBg }
    ),
    // 4 — Videos
    section(
      [
        [
          widget("bp-video-grid", {
            source: "latest",
            count: 3,
            show_heading: "yes",
            eyebrow: "Inside BrickPoint",
            title: "See the Strength Behind Every Brick",
            show_all_btn: "yes",
            all_btn_label: "View All Videos",
          }),
        ],
      ],
      { padding: pad(56, 56) }
    ),
  ],
});

/* ------------------------------------------------------------------ */
/* ARCHIVES                                                            */
/* ------------------------------------------------------------------ */
template({
  name: "product-archive",
  title: "BrickPoint Products Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "archive", sub_name: "bp_product_archive" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_product_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [
        [
          widget("bp-product-grid", {
            source: "archive",
            show_heading: "no",
            show_pagination: "yes",
            wa_label: "Order on WhatsApp",
            details_label: "Details",
          }),
        ],
      ],
      { padding: pad(56, 56) }
    ),
  ],
});

template({
  name: "product-category-archive",
  title: "BrickPoint Product Category Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "bp_product_category" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_product_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [
        [
          widget("bp-product-grid", {
            source: "archive",
            show_heading: "no",
            show_pagination: "yes",
            wa_label: "Order on WhatsApp",
            details_label: "Details",
          }),
        ],
      ],
      { padding: pad(56, 56) }
    ),
    // WhatsApp quotation band (source design: sand CTA under category)
    section(
      [
        [
          widget("bp-cta", {
            title: "Need a bulk quotation?",
            text: "Send your material list on WhatsApp — we reply with availability and final pricing the same day.",
            btn1_label: "Chat on WhatsApp",
            btn1_link: { url: "{{wa}}", is_external: "yes", nofollow: "" },
            btn2_label: "All Products",
            btn2_link: { url: "{{archive:bp_product}}", is_external: "", nofollow: "" },
          }),
        ],
      ],
      { padding: pad(56, 56), background_background: "classic", background_color: "#F6F1EA" }
    ),
  ],
});

template({
  name: "video-archive",
  title: "BrickPoint Videos Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "archive", sub_name: "bp_video_archive" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_video_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [[widget("bp-video-grid", { source: "archive", show_heading: "no", show_pagination: "yes", show_all_btn: "no" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

template({
  name: "video-category-archive",
  title: "BrickPoint Video Category Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "bp_video_category" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_video_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [[widget("bp-video-grid", { source: "archive", show_heading: "no", show_pagination: "yes", show_all_btn: "no" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

template({
  name: "project-archive",
  title: "BrickPoint Projects Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "archive", sub_name: "bp_project_archive" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_project_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [[widget("bp-project-grid", { source: "archive", show_heading: "no", show_pagination: "yes" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

template({
  name: "project-category-archive",
  title: "BrickPoint Project Category Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "bp_project_category" }],
  content: [
    section(
      [[widget("bp-archive-head", { show_filters: "yes", filter_taxonomy: "bp_project_category" })]],
      { padding: pad(0, 0), ...darkBg }
    ),
    section(
      [[widget("bp-project-grid", { source: "archive", show_heading: "no", show_pagination: "yes" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

template({
  name: "location-archive",
  title: "BrickPoint Locations Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "archive", sub_name: "bp_location_archive" }],
  content: [
    section([[widget("bp-archive-head", { show_filters: "no" })]], { padding: pad(0, 0), ...darkBg }),
    section([[widget("bp-location-cards", { count: 12, show_heading: "no" })]], {
      padding: pad(56, 56),
    }),
  ],
});

template({
  name: "blog-archive",
  title: "BrickPoint Blog Archive",
  bpType: "archive",
  location: "archive",
  condition: [{ type: "include", name: "archive", sub_name: "post_archive" }],
  content: [
    section([[widget("bp-archive-head", { show_filters: "no" })]], { padding: pad(0, 0), ...darkBg }),
    section(
      [[widget("bp-posts-grid", { source: "archive", show_heading: "no", show_pagination: "yes" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

/* ------------------------------------------------------------------ */
/* SINGLES                                                             */
/* ------------------------------------------------------------------ */
template({
  name: "product-single",
  title: "BrickPoint Single Product",
  bpType: "single",
  location: "single",
  condition: [{ type: "include", name: "singular", sub_name: "bp_product" }],
  content: [section([[widget("bp-product-single")]], { padding: pad(0, 0) })],
});

template({
  name: "video-single",
  title: "BrickPoint Single Video",
  bpType: "single",
  location: "single",
  condition: [{ type: "include", name: "singular", sub_name: "bp_video" }],
  content: [section([[widget("bp-video-single")]], { padding: pad(0, 0) })],
});

template({
  name: "project-single",
  title: "BrickPoint Single Project",
  bpType: "single",
  location: "single",
  condition: [{ type: "include", name: "singular", sub_name: "bp_project" }],
  content: [section([[widget("bp-project-single")]], { padding: pad(0, 0) })],
});

template({
  name: "location-single",
  title: "BrickPoint Single Location",
  bpType: "single",
  location: "single",
  condition: [{ type: "include", name: "singular", sub_name: "bp_location" }],
  content: [section([[widget("bp-location-single")]], { padding: pad(0, 0) })],
});

template({
  name: "post-single",
  title: "BrickPoint Single Post",
  bpType: "single",
  location: "single",
  condition: [{ type: "include", name: "singular", sub_name: "post" }],
  content: [section([[widget("bp-post-single")]], { padding: pad(0, 0) })],
});

/* ------------------------------------------------------------------ */
/* Reusable page template (Elementor library entry)                    */
/* ------------------------------------------------------------------ */
template({
  name: "page",
  title: "BrickPoint Page",
  bpType: "page",
  content: [
    section([[widget("bp-archive-head", { show_filters: "no" })]], { padding: pad(0, 0), ...darkBg }),
    section(
      [[widget("text-editor", { editor: "<p>Write your page content here with the BrickPoint design system.</p>" })]],
      { padding: pad(56, 56) }
    ),
  ],
});

/* ------------------------------------------------------------------ */
/* CTA section (reusable section template)                             */
/* ------------------------------------------------------------------ */
template({
  name: "cta-section",
  title: "BrickPoint CTA Section",
  bpType: "section",
  content: [
    section(
      [
        [
          widget("bp-cta", {
            title: "Ready to order bricks for your next project?",
            text: "Send your material list on WhatsApp — we reply with availability and final pricing the same day.",
            btn1_label: "Chat on WhatsApp",
            btn1_link: { url: "{{wa}}", is_external: "yes", nofollow: "" },
            btn2_label: "Browse Products",
            btn2_link: { url: "{{archive:bp_product}}", is_external: "", nofollow: "" },
          }),
        ],
      ],
      { padding: pad(56, 56) }
    ),
  ],
});

console.log(`Built ${fs.readdirSync(OUT).length} Elementor templates in ${OUT}`);
