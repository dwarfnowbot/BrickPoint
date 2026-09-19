<?php
/**
 * BrickPoint demo content definitions.
 *
 * All demo data mirrors the real BrickPoint business (Masha Allah Bricks Co.,
 * Fine Bricks Co., SS7 Bricks) and the LM Arena website copy. Nothing is
 * lorem-ipsum placeholder text.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ */
/* Media manifest                                                      */
/* ------------------------------------------------------------------ */

/**
 * Bundled demo media: key => array( file, title, alt ).
 * Files live in demo-content/media/ and demo-content/videos/.
 */
function bp_demo_media_manifest() {
	$dir  = 'demo-content/media/';
	$vdir = 'demo-content/videos/';

	return array(
		'hero'        => array( $dir . 'hero-bricks.jpg', __( 'Brick yard rows at golden hour', 'brickpoint' ), __( 'BrickPoint brick yard with stacked red clay bricks at sunset', 'brickpoint' ) ),
		'ss7'         => array( $dir . 'ss7-bricks.jpg', __( 'SS7 premium bricks', 'brickpoint' ), __( 'SS7 machine-molded premium red bricks', 'brickpoint' ) ),
		'cat-awami'   => array( $dir . 'cat-awami.jpg', __( 'Awami bricks stack', 'brickpoint' ), __( 'Awami red clay bricks stacked at the yard', 'brickpoint' ) ),
		'cat-fine'    => array( $dir . 'cat-fine.jpg', __( 'Fine bricks stack', 'brickpoint' ), __( 'Fine quality red bricks neatly stacked', 'brickpoint' ) ),
		'cat-tile'    => array( $dir . 'cat-tile.jpg', __( 'Brick tile face wall', 'brickpoint' ), __( 'Exposed red brick tile wall on a modern facade', 'brickpoint' ) ),
		'cat-blocks'  => array( $dir . 'cat-blocks.jpg', __( 'Concrete blocks', 'brickpoint' ), __( 'Concrete cement blocks on pallets', 'brickpoint' ) ),
		'cat-pavers'  => array( $dir . 'cat-pavers.jpg', __( 'Tuff tile pavers', 'brickpoint' ), __( 'Interlocking paver tiles driveway', 'brickpoint' ) ),
		'cat-crush'   => array( $dir . 'cat-crush.jpg', __( 'Crush / bajri pile', 'brickpoint' ), __( 'Grey crushed stone aggregate pile', 'brickpoint' ) ),
		'cat-sand'    => array( $dir . 'cat-sand.jpg', __( 'River sand pile', 'brickpoint' ), __( 'Golden river sand with shovel', 'brickpoint' ) ),
		'kiln-1'      => array( $dir . 'kiln-1.jpg', __( 'Brick kiln chimney', 'brickpoint' ), __( 'Traditional brick kiln bhatta with tall chimney', 'brickpoint' ) ),
		'kiln-2'      => array( $dir . 'kiln-2.jpg', __( 'Kiln workers carrying bricks', 'brickpoint' ), __( 'Workers carrying bricks at a brick kiln yard', 'brickpoint' ) ),
		'yard-trucks' => array( $dir . 'yard-trucks.jpg', __( 'Bricks being loaded for delivery', 'brickpoint' ), __( 'Tractor trolley being loaded with bricks for delivery', 'brickpoint' ) ),
		'proj-housing' => array( $dir . 'proj-housing.jpg', __( 'Housing scheme under construction', 'brickpoint' ), __( 'Housing scheme houses under construction with brick masonry', 'brickpoint' ) ),
		'proj-plaza'  => array( $dir . 'proj-plaza.jpg', __( 'Commercial plaza under construction', 'brickpoint' ), __( 'Commercial plaza with brick masonry and concrete columns', 'brickpoint' ) ),
		'proj-wall'   => array( $dir . 'proj-wall.jpg', __( 'Brick boundary wall', 'brickpoint' ), __( 'Long brick boundary wall of a housing society', 'brickpoint' ) ),
		'blog-1'      => array( $dir . 'blog-1.jpg', __( 'Freshly molded bricks drying', 'brickpoint' ), __( 'Freshly molded bricks drying in rows before firing', 'brickpoint' ) ),
		'blog-2'      => array( $dir . 'blog-2.jpg', __( 'Quality testing a brick', 'brickpoint' ), __( 'Mason tapping a brick to test ring and quality', 'brickpoint' ) ),
		'blog-3'      => array( $dir . 'blog-3.jpg', __( 'Brick delivery truck', 'brickpoint' ), __( 'Bricks loaded on a delivery truck at a site', 'brickpoint' ) ),
		'blog-4'      => array( $dir . 'blog-4.jpg', __( 'Mason laying bricks', 'brickpoint' ), __( 'Mason laying red bricks with mortar on a wall', 'brickpoint' ) ),
		'blog-5'      => array( $dir . 'blog-5.jpg', __( 'Brick wall close-up', 'brickpoint' ), __( 'Close-up of precise brickwork with clean mortar joints', 'brickpoint' ) ),

		'video-hero'  => array( $vdir . 'hero-tour.mp4', __( 'BrickPoint yard tour', 'brickpoint' ), __( 'BrickPoint yard tour video', 'brickpoint' ) ),
		'video-ss7'   => array( $vdir . 'ss7-manufacturing.mp4', __( 'SS7 manufacturing', 'brickpoint' ), __( 'SS7 bricks manufacturing video', 'brickpoint' ) ),
		'video-quality' => array( $vdir . 'quality-check.mp4', __( 'Quality check', 'brickpoint' ), __( 'Brick quality check video', 'brickpoint' ) ),
		'video-delivery' => array( $vdir . 'delivery.mp4', __( 'Delivery', 'brickpoint' ), __( 'Brick delivery video', 'brickpoint' ) ),
		'video-bhatta' => array( $vdir . 'bhatta-tour.mp4', __( 'Bhatta tour', 'brickpoint' ), __( 'Brick kiln bhatta tour video', 'brickpoint' ) ),
		'video-project' => array( $vdir . 'project-montage.mp4', __( 'Projects montage', 'brickpoint' ), __( 'Construction projects montage video', 'brickpoint' ) ),
	);
}

/* ------------------------------------------------------------------ */
/* Taxonomies                                                          */
/* ------------------------------------------------------------------ */

function bp_demo_product_categories() {
	return array(
		'awami-bricks'    => array( 'name' => 'Awami Bricks', 'media' => 'cat-awami', 'desc' => __( 'Economical red clay bricks for boundary walls, plasters and general masonry work.', 'brickpoint' ) ),
		'fine-bricks'     => array( 'name' => 'Fine Bricks (Class A)', 'media' => 'cat-fine', 'desc' => __( 'First-quality machine-molded bricks with sharp edges and uniform burning for exposed and load-bearing work.', 'brickpoint' ) ),
		'ss7-bricks'      => array( 'name' => 'SS7 Bricks', 'media' => 'ss7', 'desc' => __( 'Our flagship premium brick — high compressive strength, consistent color, crisp molding for premium facades.', 'brickpoint' ) ),
		'brick-tiles'     => array( 'name' => 'Brick Tiles & Facing', 'media' => 'cat-tile', 'desc' => __( 'Thin brick tiles and facing bricks for elevations, pillars and feature walls.', 'brickpoint' ) ),
		'concrete-blocks' => array( 'name' => 'Concrete Blocks', 'media' => 'cat-blocks', 'desc' => __( 'Solid and hollow concrete blocks in 4, 6 and 8 inch sizes for walls and lintels.', 'brickpoint' ) ),
		'tuff-tiles'      => array( 'name' => 'Tuff Tiles & Pavers', 'media' => 'cat-pavers', 'desc' => __( 'Interlocking pavers and tuff tiles for driveways, streets and terraces.', 'brickpoint' ) ),
		'crush-bajri'     => array( 'name' => 'Crush / Bajri', 'media' => 'cat-crush', 'desc' => __( 'Margalla and Sargodha crush in all sizes, washed and graded for concrete works.', 'brickpoint' ) ),
		'sand-raith'      => array( 'name' => 'Sand / Rait', 'media' => 'cat-sand', 'desc' => __( 'Chenab and Ravi river sand plus clean rait for concrete and plaster.', 'brickpoint' ) ),
	);
}

function bp_demo_video_categories() {
	return array(
		'yard-tours'   => array( 'name' => 'Yard & Bhatta Tours', 'media' => 'kiln-1', 'desc' => __( 'Walkthroughs of our bhattas and stock yards.', 'brickpoint' ) ),
		'manufacturing' => array( 'name' => 'Brick Manufacturing', 'media' => 'kiln-2', 'desc' => __( 'From clay molding to kiln firing — see how our bricks are made.', 'brickpoint' ) ),
		'quality'      => array( 'name' => 'Quality & Testing', 'media' => 'blog-2', 'desc' => __( 'Drop tests, ring tests and strength checks before dispatch.', 'brickpoint' ) ),
		'delivery'     => array( 'name' => 'Delivery & Logistics', 'media' => 'yard-trucks', 'desc' => __( 'Loading, transport and on-time site delivery.', 'brickpoint' ) ),
		'projects'     => array( 'name' => 'Construction Projects', 'media' => 'proj-plaza', 'desc' => __( 'Material showcases from live construction sites.', 'brickpoint' ) ),
		'brand'        => array( 'name' => 'Company & Brand', 'media' => 'hero', 'desc' => __( 'Promotional and company profile videos.', 'brickpoint' ) ),
	);
}

function bp_demo_project_categories() {
	return array(
		'housing'   => array( 'name' => 'Housing Schemes', 'media' => 'proj-housing', 'desc' => __( 'Residential schemes and colony developments supplied with BrickPoint materials.', 'brickpoint' ) ),
		'commercial' => array( 'name' => 'Commercial Buildings', 'media' => 'proj-plaza', 'desc' => __( 'Plazas, shops and offices under construction with our bricks and materials.', 'brickpoint' ) ),
		'boundary'  => array( 'name' => 'Boundary Walls', 'media' => 'proj-wall', 'desc' => __( 'Long-span boundary walls in brick masonry for societies and factories.', 'brickpoint' ) ),
	);
}

/* ------------------------------------------------------------------ */
/* Products                                                            */
/* ------------------------------------------------------------------ */

function bp_demo_products() {
	return array(
		array(
			'slug' => 'awami-bricks-240', 'title' => __( 'Awami Bricks (240mm)', 'brickpoint' ),
			'cats' => array( 'awami-bricks' ), 'featured' => 1, 'sort' => 1,
			'price' => 'Rs. 13,800', 'unit' => __( 'per 1,000 bricks', 'brickpoint' ),
			'badge' => __( 'Budget Pick', 'brickpoint' ), 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-awami', 'gallery' => array( 'cat-awami', 'kiln-1' ),
			'short' => __( 'Reliable everyday red clay bricks for walls, plasters and boundary work at yard-direct pricing.', 'brickpoint' ),
			'content' => __( 'Awami bricks are the workhorse of Pakistani construction. Molded from screened clay and fired in traditional kilns, they offer dependable strength for non-load-bearing walls, compound walls and general masonry. Every consignment is stack-counted at the yard so you receive the exact quantity ordered.', 'brickpoint' ),
			'specs' => "Size: 240 x 115 x 55 mm\nCompressive strength: 3.5+ MPa\nWater absorption: under 20%\nColor: Natural red\nPacking: Stack counted",
			'features' => "Yard-direct bulk pricing\nConsistent firing, minimal breakage\nSame-week site delivery in bulk\nIdeal for walls, plaster backing and boundary work",
		),
		array(
			'slug' => 'fine-bricks-class-a', 'title' => __( 'Fine Bricks — Class A', 'brickpoint' ),
			'cats' => array( 'fine-bricks' ), 'featured' => 1, 'sort' => 2,
			'price' => 'Rs. 16,500', 'unit' => __( 'per 1,000 bricks', 'brickpoint' ),
			'badge' => __( 'Best Seller', 'brickpoint' ), 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-fine', 'gallery' => array( 'cat-fine', 'blog-5' ),
			'short' => __( 'First-quality Class A bricks with sharp edges and uniform color for exposed brickwork.', 'brickpoint' ),
			'content' => __( 'Fine Bricks (Class A) are hand-selected after firing for shape, ring and color. The crisp edges and uniform terracotta tone make them the standard choice for exposed brick facades, pillars and premium boundary walls across Lahore.', 'brickpoint' ),
			'specs' => "Size: 240 x 115 x 55 mm\nCompressive strength: 5+ MPa\nWater absorption: under 15%\nColor: Uniform terracotta\nPacking: Stack counted",
			'features' => "Hand-selected after firing\nRinging sound — fully burnt core\nUniform size for clean mortar joints\nTrusted for exposed and load-bearing work",
		),
		array(
			'slug' => 'ss7-premium-bricks', 'title' => __( 'SS7 Premium Bricks', 'brickpoint' ),
			'cats' => array( 'ss7-bricks', 'fine-bricks' ), 'featured' => 1, 'sort' => 3,
			'price' => 'Rs. 19,900', 'unit' => __( 'per 1,000 bricks', 'brickpoint' ),
			'badge' => __( 'Flagship', 'brickpoint' ), 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'ss7', 'gallery' => array( 'ss7', 'cat-tile', 'kiln-2' ),
			'short' => __( 'Our flagship machine-molded brick — maximum strength, crisp molding and a rich consistent color.', 'brickpoint' ),
			'content' => __( 'SS7 is the brick we build our name on. Machine-molded for dimensional accuracy and fired in our most modern kiln, SS7 delivers the highest compressive strength in our range with a deep, consistent red finish. Architects specify SS7 for premium facades where every course is visible.', 'brickpoint' ),
			'specs' => "Size: 240 x 115 x 55 mm\nCompressive strength: 7.5+ MPa\nWater absorption: under 12%\nColor: Deep consistent red\nPacking: Palletized on request",
			'features' => "Machine-molded dimensional accuracy\nHighest strength grade in our range\nRich color that weathers beautifully\nPalletized delivery for premium sites",
		),
		array(
			'slug' => 'ss7-brick-tiles', 'title' => __( 'SS7 Brick Tiles (Facing)', 'brickpoint' ),
			'cats' => array( 'brick-tiles', 'ss7-bricks' ), 'featured' => 1, 'sort' => 4,
			'price' => 'Rs. 62', 'unit' => __( 'per sq. ft.', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-tile', 'gallery' => array( 'cat-tile', 'ss7' ),
			'short' => __( 'Thin-cut facing tiles from SS7 stock for elevations, pillars and interior feature walls.', 'brickpoint' ),
			'content' => __( 'SS7 brick tiles are sliced from our flagship bricks, giving you authentic kiln-fired texture at a fraction of the wall weight. Ideal for elevation cladding, porch pillars, TV feature walls and café interiors.', 'brickpoint' ),
			'specs' => "Thickness: 15–20 mm\nCoverage: approx. 7 pcs per sq. ft.\nFinish: Natural kiln-fired face\nFixing: Adhesive or mortar",
			'features' => "Authentic fired-clay face\nLightweight cladding for columns and walls\nConsistent SS7 color batch matching\nIndoor and outdoor rated",
		),
		array(
			'slug' => 'concrete-block-6-inch', 'title' => __( 'Concrete Blocks 6"', 'brickpoint' ),
			'cats' => array( 'concrete-blocks' ), 'featured' => 1, 'sort' => 5,
			'price' => 'Rs. 105', 'unit' => __( 'per block', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-blocks', 'gallery' => array( 'cat-blocks' ),
			'short' => __( 'Machine-vibrated 6 inch hollow blocks for fast walling with clean finishes.', 'brickpoint' ),
			'content' => __( 'Our 6 inch concrete blocks are machine-vibrated and steam-cured for uniform strength. They go up faster than brick, use less mortar and keep walls square — a favorite for boundary walls and commercial partitions.', 'brickpoint' ),
			'specs' => "Size: 390 x 90 x 190 mm\nType: Hollow, 2-core\nCompressive strength: 7+ MPa\nCuring: Steam cured",
			'features' => "Fast walling, less mortar\nUniform size and square corners\nConsistent gray color for paint or render\nBulk pallet delivery",
		),
		array(
			'slug' => 'concrete-block-8-inch', 'title' => __( 'Concrete Blocks 8"', 'brickpoint' ),
			'cats' => array( 'concrete-blocks' ), 'featured' => 0, 'sort' => 6,
			'price' => 'Rs. 130', 'unit' => __( 'per block', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-blocks', 'gallery' => array( 'cat-blocks', 'proj-wall' ),
			'short' => __( '8 inch hollow blocks for boundary walls and load-bearing partitions.', 'brickpoint' ),
			'content' => __( 'The 8 inch block adds the mass and strength needed for tall boundary walls and load-bearing partitions. Same machine-vibrated quality as our 6 inch line.', 'brickpoint' ),
			'specs' => "Size: 390 x 140 x 190 mm\nType: Hollow, 2-core\nCompressive strength: 7+ MPa\nCuring: Steam cured",
			'features' => "Extra mass for tall walls\nReinforcement-friendly cores\nUniform cure for reliable strength\nSite delivery in bulk",
		),
		array(
			'slug' => 'tuff-tile-pavers-60mm', 'title' => __( 'Tuff Tile Pavers 60mm', 'brickpoint' ),
			'cats' => array( 'tuff-tiles' ), 'featured' => 1, 'sort' => 7,
			'price' => 'Rs. 95', 'unit' => __( 'per sq. ft.', 'brickpoint' ),
			'badge' => __( 'Popular', 'brickpoint' ), 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-pavers', 'gallery' => array( 'cat-pavers' ),
			'short' => __( 'Interlocking 60mm pavers for driveways, streets and terraces in red, grey and charcoal.', 'brickpoint' ),
			'content' => __( 'Our 60mm interlocking pavers are pressed at high tonnage for a dense, low-absorption tile that shrugs off car traffic and monsoon rain. Available in red, grey and charcoal with matching kerb stones.', 'brickpoint' ),
			'specs' => "Thickness: 60 mm\nStrength: 30+ MPa\nColors: Red, grey, charcoal\nLayout: Interlocking I and wave",
			'features' => "Handles car and light truck loads\nLow water absorption\nEasy spot repairs — no pouring\nMatching kerb and kicker stones",
		),
		array(
			'slug' => 'margalla-crush-1', 'title' => __( 'Margalla Crush (1 inch)', 'brickpoint' ),
			'cats' => array( 'crush-bajri' ), 'featured' => 1, 'sort' => 8,
			'price' => 'Rs. 118', 'unit' => __( 'per cubic ft.', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-crush', 'gallery' => array( 'cat-crush' ),
			'short' => __( 'Washed, graded Margalla crush for slabs, columns and beams.', 'brickpoint' ),
			'content' => __( 'Margalla crush is the standard for structural concrete in Punjab. We supply washed and graded 1 inch (down) aggregate, loaded by weight and delivered with a site-passed gradation.', 'brickpoint' ),
			'specs' => "Size: 1 inch down\nSource: Margalla hills\nWashing: Washed, dust-free\nTest: Gradation on request",
			'features' => "Clean, washed aggregate\nWeight-based loading, no short counts\nSame-day delivery at project volume\nGradation certificate on request",
		),
		array(
			'slug' => 'sargodha-crush-2', 'title' => __( 'Sargodha Crush (2 inch)', 'brickpoint' ),
			'cats' => array( 'crush-bajri' ), 'featured' => 0, 'sort' => 9,
			'price' => 'Rs. 108', 'unit' => __( 'per cubic ft.', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-crush', 'gallery' => array( 'cat-crush', 'proj-housing' ),
			'short' => __( 'Economical 2 inch Sargodha crush for foundations and soling.', 'brickpoint' ),
			'content' => __( 'Sargodha 2 inch crush is the economical choice for foundation concrete, soling and mass filling where fine grading matters less than bulk strength.', 'brickpoint' ),
			'specs' => "Size: 2 inch down\nSource: Sargodha quarries\nUse: Foundations, soling\nPacking: Weight-based",
			'features' => "Budget-friendly bulk supply\nGreat for foundations and fills\nReliable quarry source\nFast trolley delivery",
		),
		array(
			'slug' => 'chenab-sand', 'title' => __( 'Chenab Sand (Washed)', 'brickpoint' ),
			'cats' => array( 'sand-raith' ), 'featured' => 1, 'sort' => 10,
			'price' => 'Rs. 68', 'unit' => __( 'per cubic ft.', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-sand', 'gallery' => array( 'cat-sand' ),
			'short' => __( 'Coarse washed Chenab sand for concrete and plaster work.', 'brickpoint' ),
			'content' => __( 'Chenab river sand is prized for its coarse, clean grains that bond tightly in cement mortar. Washed to cut silt, it is our default recommendation for both concrete and plaster sand.', 'brickpoint' ),
			'specs' => "Type: Coarse (zonal III)\nWashing: Washed\nSilt content: under 3%\nUse: Concrete + plaster",
			'features' => "Washed, low-silt grains\nStrong mortar bonding\nConsistent supply year-round\nWeight-measured delivery",
		),
		array(
			'slug' => 'ravi-raith', 'title' => __( 'Ravi Rait (Sand & Gravel Mix)', 'brickpoint' ),
			'cats' => array( 'sand-raith' ), 'featured' => 0, 'sort' => 11,
			'price' => 'Rs. 58', 'unit' => __( 'per cubic ft.', 'brickpoint' ),
			'badge' => '', 'availability' => __( 'In Stock', 'brickpoint' ),
			'media' => 'cat-sand', 'gallery' => array( 'cat-sand', 'blog-3' ),
			'short' => __( 'Natural sand-and-gravel mix for lean concrete and filling.', 'brickpoint' ),
			'content' => __( 'Ravi rait is the traditional pit-run sand and gravel mix used in lean concrete, PCC beds and backfilling — an economical base material for every project.', 'brickpoint' ),
			'specs' => "Type: Pit-run mix\nGravel: Up to 20mm\nUse: PCC, filling\nPacking: Weight-based",
			'features' => "Economical base material\nReady mixed — no blending needed\nCompacts well\nBulk trolley loads",
		),
		array(
			'slug' => 'ss7-pallet-deal', 'title' => __( 'SS7 Bulk Site Deal (Palletized)', 'brickpoint' ),
			'cats' => array( 'ss7-bricks', 'fine-bricks' ), 'featured' => 1, 'sort' => 12,
			'price' => 'Rs. 18,700', 'unit' => __( 'per 1,000 bricks, 50k+ order', 'brickpoint' ),
			'badge' => __( 'Bulk Deal', 'brickpoint' ), 'availability' => __( 'Advance Booking', 'brickpoint' ),
			'media' => 'ss7', 'gallery' => array( 'ss7', 'yard-trucks', 'blog-3' ),
			'short' => __( 'Palletized SS7 supply for housing schemes and commercial projects with scheduled site delivery.', 'brickpoint' ),
			'content' => __( 'For housing schemes, plazas and contractors running continuous work, our SS7 bulk deal locks your price for 90 days, palletizes every load and schedules deliveries to match your block work plan. One WhatsApp message starts the quotation.', 'brickpoint' ),
			'specs' => "Brick: SS7 premium\nMinimum: 50,000 bricks\nPricing: Locked 90 days\nDelivery: Scheduled batches",
			'features' => "90-day price protection\nPalletized, breakage-free handling\nDedicated site coordinator\nWeekly delivery scheduling",
		),
	);
}

/* ------------------------------------------------------------------ */
/* Videos                                                              */
/* ------------------------------------------------------------------ */

function bp_demo_videos() {
	return array(
		array(
			'slug' => 'ss7-manufacturing-tour', 'title' => __( 'How SS7 Bricks Are Made — Full Tour', 'brickpoint' ),
			'cats' => array( 'manufacturing', 'brand' ), 'featured' => 1, 'duration' => '3:45',
			'media' => 'video-ss7', 'thumb' => 'kiln-2', 'order' => 1,
			'excerpt' => __( 'From clay screening to kiln firing — the complete SS7 production line at our Masha Allah brick yard.', 'brickpoint' ),
			'content' => __( 'Walk the full SS7 line: clay screening, pug mixing, machine molding, drying rows and the firing tunnel. You will see why SS7 comes out with sharper edges and deeper color than ordinary bricks.', 'brickpoint' ),
		),
		array(
			'slug' => 'brick-yard-tour', 'title' => __( 'BrickPoint Yard Tour — Stock & Stacking', 'brickpoint' ),
			'cats' => array( 'yard-tours' ), 'featured' => 1, 'duration' => '2:58',
			'media' => 'video-hero', 'thumb' => 'hero', 'order' => 2,
			'excerpt' => __( 'A walkthrough of our main yard: stack counting, quality segregation and loading bays.', 'brickpoint' ),
			'content' => __( 'See how every BrickPoint order is stack-counted, segregated by class and staged for loading. No guesswork, no short counts.', 'brickpoint' ),
		),
		array(
			'slug' => 'brick-quality-check', 'title' => __( 'The Brick Ring Test — Quality You Can Hear', 'brickpoint' ),
			'cats' => array( 'quality' ), 'featured' => 1, 'duration' => '1:47',
			'media' => 'video-quality', 'thumb' => 'blog-2', 'order' => 3,
			'excerpt' => __( 'Tap a fully burnt brick and it rings. Watch our team sort by sound before dispatch.', 'brickpoint' ),
			'content' => __( 'A metallic ring means the brick was fired through. Our sorters tap every suspect brick — under-burnt bricks never reach your site.', 'brickpoint' ),
		),
		array(
			'slug' => 'bhatta-tour-kiln', 'title' => __( 'Inside the Bhatta — Kiln Firing Day', 'brickpoint' ),
			'cats' => array( 'yard-tours', 'manufacturing' ), 'featured' => 0, 'duration' => '2:12',
			'media' => 'video-bhatta', 'thumb' => 'kiln-1', 'order' => 4,
			'excerpt' => __( 'Kiln firing day at our Fine Bricks bhatta — temperatures, timing and the cooling cycle.', 'brickpoint' ),
			'content' => __( 'Firing turns molded clay into lasting brick. This short tour shows the fire holes, the firing schedule and the controlled cooling that protects brick color.', 'brickpoint' ),
		),
		array(
			'slug' => 'bulk-delivery-day', 'title' => __( 'Bulk Delivery Day — 40,000 Bricks to Site', 'brickpoint' ),
			'cats' => array( 'delivery' ), 'featured' => 0, 'duration' => '2:35',
			'media' => 'video-delivery', 'thumb' => 'yard-trucks', 'order' => 5,
			'excerpt' => __( 'Loading and dispatching 40,000 Fine Bricks to a housing site — on schedule, on count.', 'brickpoint' ),
			'content' => __( 'Watch a full bulk dispatch: count verification, careful loading to cut breakage, and delivery to a DHA phase site before 8am.', 'brickpoint' ),
		),
		array(
			'slug' => 'project-material-showcase', 'title' => __( 'Project Showcase — Plaza Masonry with SS7', 'brickpoint' ),
			'cats' => array( 'projects' ), 'featured' => 1, 'duration' => '1:58',
			'media' => 'video-project', 'thumb' => 'proj-plaza', 'order' => 6,
			'excerpt' => __( 'SS7 bricks on a three-storey commercial plaza — clean courses, tight joints.', 'brickpoint' ),
			'content' => __( 'A short showcase of SS7 in action on a commercial plaza: crisp arrises, consistent color band by band, exactly what the architect drew.', 'brickpoint' ),
		),
	);
}

/* ------------------------------------------------------------------ */
/* Projects                                                            */
/* ------------------------------------------------------------------ */

function bp_demo_projects() {
	return array(
		array(
			'slug' => 'model-town-housing-phase', 'title' => __( 'Model Town Housing Phase — 60 Units', 'brickpoint' ),
			'cats' => array( 'housing' ), 'featured' => 1, 'sort' => 1,
			'location' => __( 'Model Town, Lahore', 'brickpoint' ),
			'media' => 'proj-housing', 'gallery' => array( 'proj-housing', 'blog-4' ),
			'excerpt' => __( 'Fine Bricks and crush supplied across a 60-unit residential scheme.', 'brickpoint' ),
			'content' => __( 'A 60-unit housing scheme raised on Fine Bricks Class A masonry with Margalla crush slabs. BrickPoint supplied scheduled weekly loads for eight months, keeping every block of the scheme on plan and on count.', 'brickpoint' ),
		),
		array(
			'slug' => 'gulberg-commercial-plaza', 'title' => __( 'Gulberg Commercial Plaza', 'brickpoint' ),
			'cats' => array( 'commercial' ), 'featured' => 1, 'sort' => 2,
			'location' => __( 'Gulberg III, Lahore', 'brickpoint' ),
			'media' => 'proj-plaza', 'gallery' => array( 'proj-plaza', 'cat-tile' ),
			'excerpt' => __( 'Three-storey plaza with SS7 facing brickwork on the front elevation.', 'brickpoint' ),
			'content' => __( 'For this plaza the architect specified exposed SS7 facades. Batch-matched SS7 deliveries kept the color continuous from ground to parapet — no patchwork bands, no color drift.', 'brickpoint' ),
		),
		array(
			'slug' => 'society-boundary-wall', 'title' => __( 'Society Boundary Wall — 2.4 km', 'brickpoint' ),
			'cats' => array( 'boundary' ), 'featured' => 1, 'sort' => 3,
			'location' => __( 'Ferozepur Road, Lahore', 'brickpoint' ),
			'media' => 'proj-wall', 'gallery' => array( 'proj-wall' ),
			'excerpt' => __( 'A 2.4 km brick boundary wall fed by twice-weekly Awami Bricks deliveries.', 'brickpoint' ),
			'content' => __( 'Long walls eat bricks. Twice-weekly Awami Bricks deliveries kept this 2.4 km boundary wall running without a single day of mason standby.', 'brickpoint' ),
		),
		array(
			'slug' => 'dha-villa-extensions', 'title' => __( 'DHA Villa Extensions', 'brickpoint' ),
			'cats' => array( 'housing' ), 'featured' => 0, 'sort' => 4,
			'location' => __( 'DHA Phase 6, Lahore', 'brickpoint' ),
			'media' => 'blog-4', 'gallery' => array( 'blog-4', 'blog-5' ),
			'excerpt' => __( 'Premium villa extensions in SS7 brickwork with matching brick tiles.', 'brickpoint' ),
			'content' => __( 'Renovation work demands material that matches. SS7 bricks and cut tiles let these villa extensions blend into the original elevation seamlessly.', 'brickpoint' ),
		),
		array(
			'slug' => 'ring-road-factory-wall', 'title' => __( 'Ring Road Factory Compound', 'brickpoint' ),
			'cats' => array( 'boundary', 'commercial' ), 'featured' => 0, 'sort' => 5,
			'location' => __( 'Ring Road, Lahore', 'brickpoint' ),
			'media' => 'proj-wall', 'gallery' => array( 'proj-wall', 'cat-blocks' ),
			'excerpt' => __( 'Factory compound walls in 8 inch block and brick masonry combination.', 'brickpoint' ),
			'content' => __( 'A hybrid wall system — 8 inch block piers with brick infill panels — gave this factory compound strength at speed, all supplied from one BrickPoint account.', 'brickpoint' ),
		),
		array(
			'slug' => 'street-paving-package', 'title' => __( 'Housing Colony Street Paving', 'brickpoint' ),
			'cats' => array( 'commercial' ), 'featured' => 0, 'sort' => 6,
			'location' => __( 'Bahria Town, Lahore', 'brickpoint' ),
			'media' => 'cat-pavers', 'gallery' => array( 'cat-pavers' ),
			'excerpt' => __( 'Interior streets paved with 60mm interlocking tuff tiles.', 'brickpoint' ),
			'content' => __( 'Over 40,000 sq. ft. of 60mm interlocking pavers turned dusty lanes into clean, all-weather streets for this housing colony.', 'brickpoint' ),
		),
	);
}

/* ------------------------------------------------------------------ */
/* Locations (bhattas)                                                 */
/* ------------------------------------------------------------------ */

function bp_demo_locations() {
	return array(
		array(
			'slug' => 'masha-allah-bricks-bhatta', 'title' => __( 'Masha Allah Bricks Co. (Main Bhatta)', 'brickpoint' ),
			'order' => 1,
			'address' => __( 'Bhaini Road, District Kasur, Punjab', 'brickpoint' ),
			'hours' => __( 'Open daily, 7:00 AM – 7:00 PM', 'brickpoint' ),
			'phone' => '0315 2850818',
			'media' => 'kiln-1',
			'excerpt' => __( 'Our flagship bhatta producing SS7 and Fine Bricks with the modern firing tunnel.', 'brickpoint' ),
			'content' => __( 'The Masha Allah bhatta is where SS7 is born. With a modern firing tunnel, covered drying rows and a mechanized pug mill, it produces the most consistent bricks in our range. Yard visitors are welcome — call ahead and we will keep fresh stock aside for your inspection.', 'brickpoint' ),
			'maps_query' => 'Bhaini Road, Kasur, Punjab, Pakistan',
			'coords' => '31.108,74.220',
		),
		array(
			'slug' => 'fine-bricks-bhatta', 'title' => __( 'Fine Bricks Co. (Bhatta 2)', 'brickpoint' ),
			'order' => 2,
			'address' => __( 'Raiwind Road, Lahore, Punjab', 'brickpoint' ),
			'hours' => __( 'Open daily, 7:00 AM – 7:00 PM', 'brickpoint' ),
			'phone' => '0315 2850818',
			'media' => 'kiln-2',
			'excerpt' => __( 'High-volume Fine Bricks production yard with stack counting bays.', 'brickpoint' ),
			'content' => __( 'Fine Bricks Co. runs high-volume production for Lahore city supply. The yard features stack-counted loading bays and a dedicated quality segregation line where under-burnt bricks are pulled before dispatch.', 'brickpoint' ),
			'maps_query' => 'Raiwind Road, Lahore, Punjab, Pakistan',
			'coords' => '31.240,74.180',
		),
		array(
			'slug' => 'ss7-stock-yard', 'title' => __( 'SS7 Bricks Stock Yard', 'brickpoint' ),
			'order' => 3,
			'address' => __( 'Ferozepur Road, Lahore, Punjab', 'brickpoint' ),
			'hours' => __( 'Open daily, 8:00 AM – 8:00 PM', 'brickpoint' ),
			'phone' => '0315 2850818',
			'media' => 'ss7',
			'excerpt' => __( 'City-side stock yard for SS7 pallets and brick tiles with same-day pickup.', 'brickpoint' ),
			'content' => __( 'Our city-side yard keeps SS7 pallets, brick tiles and tuff tiles ready for same-day pickup — perfect for renovation jobs and small orders that cannot wait for a bhatta run.', 'brickpoint' ),
			'maps_query' => 'Ferozepur Road, Lahore, Punjab, Pakistan',
			'coords' => '31.480,74.300',
		),
		array(
			'slug' => 'materials-depot', 'title' => __( 'BrickPoint Materials Depot (Crush & Sand)', 'brickpoint' ),
			'order' => 4,
			'address' => __( 'Multan Road, Lahore, Punjab', 'brickpoint' ),
			'hours' => __( 'Open daily, 6:30 AM – 8:00 PM', 'brickpoint' ),
			'phone' => '0315 2850818',
			'media' => 'cat-crush',
			'excerpt' => __( 'Weight-based crush, sand and rait depot with loader loading.', 'brickpoint' ),
			'content' => __( 'The Multan Road depot supplies Margalla and Sargodha crush, Chenab sand and rait by weight with loader loading — one trolley can combine bricks from the yard with your aggregate order.', 'brickpoint' ),
			'maps_query' => 'Multan Road, Lahore, Punjab, Pakistan',
			'coords' => '31.520,74.240',
		),
	);
}

/* ------------------------------------------------------------------ */
/* Blog posts                                                          */
/* ------------------------------------------------------------------ */

function bp_demo_post_categories() {
	return array(
		'buying-guides' => __( 'Buying Guides', 'brickpoint' ),
		'quality-notes' => __( 'Quality Notes', 'brickpoint' ),
		'company-news'  => __( 'Company News', 'brickpoint' ),
	);
}

function bp_demo_posts() {
	return array(
		array(
			'slug' => 'awami-vs-fine-vs-ss7', 'title' => __( 'Awami vs Fine vs SS7 — Which Brick Should You Buy?', 'brickpoint' ),
			'cats' => array( 'buying-guides' ), 'media' => 'cat-fine', 'date_offset' => 3,
			'excerpt' => __( 'A plain-language comparison of the three brick classes we fire — price, strength and where each one belongs in your build.', 'brickpoint' ),
			'content' => "<p>Every week someone asks us the same question at the yard: <em>what is the real difference between Awami, Fine and SS7 bricks?</em> Here is the honest, no-jargon answer.</p><h2>Awami — the budget workhorse</h2><p>Awami bricks are fired in traditional kilns and sorted to a fair, working standard. Expect small color variation and slightly softer edges. For boundary walls, plaster backing and compound work, they are the most economical brick that still does the job properly.</p><h2>Fine (Class A) — the safe default</h2><p>Fine bricks are hand-selected after firing. Edges stay sharp, the ring is solid, and color runs consistent. If your walls will be visible, this is the class most builders choose.</p><h2>SS7 — the flagship</h2><p>Machine-molded and fired in our modern tunnel, SS7 gives you the highest compressive strength and a deep, even red. Load-bearing spans, premium facades, anywhere the brick is the architecture.</p><h2>Quick rule of thumb</h2><p>Hidden masonry → Awami. Visible walls → Fine. Statement brickwork or heavy loads → SS7. Send your drawings on WhatsApp and our team will price the mix that fits.</p>",
		),
		array(
			'slug' => 'brick-ring-test', 'title' => __( 'The 5-Second Ring Test: Check Brick Quality Yourself', 'brickpoint' ),
			'cats' => array( 'quality-notes' ), 'media' => 'blog-2', 'date_offset' => 8,
			'excerpt' => __( 'Two bricks, one tap — hear the difference between a fully fired brick and an under-burnt one.', 'brickpoint' ),
			'content' => "<p>You do not need a lab to catch a bad brick. Pick up a brick, hold it loosely and tap it with a trowel or coin.</p><h2>What to listen for</h2><p>A fully fired brick gives a clear metallic <em>ring</em>. A dull <em>thud</em> means the core never reached firing temperature — the brick will soak water, crumble at edges and weaken your wall.</p><h2>Three more 10-second checks</h2><p>1. <strong>Drop test:</strong> drop two bricks flat from knee height. Good bricks survive; weak ones crack.<br>2. <strong>Color break:</strong> a uniform terracotta body with no dark unburnt patches.<br>3. <strong>Size check:</strong> stack ten bricks — if heights wander more than a finger width, masonry lines will suffer.</p><p>Our sorters run these checks on every consignment before loading. Ask for a demo next time you visit the yard.</p>",
		),
		array(
			'slug' => 'order-bricks-whatsapp', 'title' => __( 'How to Order Bricks on WhatsApp (Without Site Visits)', 'brickpoint' ),
			'cats' => array( 'buying-guides', 'company-news' ), 'media' => 'blog-3', 'date_offset' => 14,
			'excerpt' => __( 'From your material list to a loaded trolley: the BrickPoint WhatsApp ordering flow explained.', 'brickpoint' ),
			'content' => "<p>No app, no account, no advance. Ordering bricks from BrickPoint is a conversation.</p><h2>The flow</h2><p>1. Message us your material list — brick class, quantity and site area.<br>2. We reply the same day with availability and a final quotation.<br>3. Confirm, and your load is scheduled with a delivery window.<br>4. Pay on delivery — cash or transfer.</p><h2>What to include in your first message</h2><p>Site location (a pin helps), required quantity, target date and whether you need unloading help. Photos of your existing brickwork help us match color if you are extending.</p><p>Save our number — <strong>0315 2850818</strong> — and your next order is one message away.</p>",
		),
		array(
			'slug' => 'crush-and-sand-guide', 'title' => __( 'Crush & Sand for RCC: Getting the Grading Right', 'brickpoint' ),
			'cats' => array( 'quality-notes' ), 'media' => 'cat-crush', 'date_offset' => 21,
			'excerpt' => __( 'Why washed Margalla crush and low-silt Chenab sand decide the strength of your slabs.', 'brickpoint' ),
			'content' => "<p>Concrete strength is decided as much by your aggregate as by your cement. Here is what to demand.</p><h2>Crush: washed and graded</h2><p>Dust-coated crush weakens the paste-to-stone bond. Washed Margalla crush in 1 inch down gives slabs and columns the interlock engineers design for. Always ask for the gradation — reputable suppliers hand it over without fuss.</p><h2>Sand: watch the silt</h2><p>Silt over 3% chokes mortar strength. Washed Chenab sand stays coarse and clean, which is why we stock it as our default for both concrete and plaster.</p><h2>On-site checks that matter</h2><p>Fill a water bottle a quarter full with sand, top with water, shake and rest. If silt clearly layers on top above a thin line, reject the load. For crush, spread a shovel-full — flaky, dusty piles are a warning.</p><p>Every BrickPoint aggregate load is weight-tickets and wash-verified before it leaves the depot.</p>",
		),
		array(
			'slug' => 'new-firing-tunnel', 'title' => __( 'Company News: Our New Firing Tunnel Is Live', 'brickpoint' ),
			'cats' => array( 'company-news' ), 'media' => 'kiln-1', 'date_offset' => 30,
			'excerpt' => __( 'The Masha Allah bhatta just doubled its SS7 output with a new firing tunnel.', 'brickpoint' ),
			'content' => "<p>Big news from the Masha Allah bhatta: our new firing tunnel is commissioned and running.</p><h2>What changes for buyers</h2><p>SS7 output roughly doubles, which means shorter lead times for bulk site deals and steadier batch color — the tunnel holds temperature far more evenly than batch kilns.</p><h2>What does not change</h2><p>Same brick dimensions, same testing routine, same stack counting. Every SS7 load still passes the ring test and drop test before dispatch.</p><p>Book a yard visit to see the tunnel running — the 7am firing shift is worth the early alarm.</p>",
		),
		array(
			'slug' => 'boundary-wall-brickwork', 'title' => __( 'Building a Boundary Wall That Survives 20 Monsoons', 'brickpoint' ),
			'cats' => array( 'buying-guides' ), 'media' => 'proj-wall', 'date_offset' => 38,
			'excerpt' => __( 'Brick class, mortar ratios, damp-proof courses and coping — the details that keep walls standing.', 'brickpoint' ),
			'content' => "<p>Boundary walls fail from the ground up. Get these four details right and your wall will outlive its builder.</p><h2>1. Brick class</h2><p>Use Fine Class A or better for the first six courses — the splash zone where water cycles do the damage. Awami is fine above.</p><h2>2. Mortar ratio</h2><p>1:4 cement-sand for the base courses, 1:6 above. Rich mortar at the base is cheap insurance.</p><h2>3. Damp-proof course</h2><p>A DPC at plinth level stops rising damp from blooming salts across your wall face.</p><h2>4. Coping</h2><p>Top the wall with sloped brick coping or tuff tile so rain runs off the face, not into the core.</p><p>Send us your wall length and height on WhatsApp — we will price the exact brick count including breakage allowance.</p>",
		),
	);
}

/* ------------------------------------------------------------------ */
/* Pages                                                               */
/* ------------------------------------------------------------------ */

function bp_demo_pages() {
	return array(
		'home' => array( 'title' => __( 'Home', 'brickpoint' ), 'elementor' => true ),
		'about' => array( 'title' => __( 'About Us', 'brickpoint' ) ),
		'contact' => array( 'title' => __( 'Contact', 'brickpoint' ) ),
		'blog' => array( 'title' => __( 'Blog', 'brickpoint' ) ),
		'contractors' => array( 'title' => __( 'For Contractors', 'brickpoint' ) ),
		'builders' => array( 'title' => __( 'For Builders', 'brickpoint' ) ),
		'construction-companies' => array( 'title' => __( 'For Construction Companies', 'brickpoint' ) ),
		'ss7-bricks' => array( 'title' => __( 'SS7 Bricks', 'brickpoint' ) ),
		'construction-materials' => array( 'title' => __( 'Construction Materials', 'brickpoint' ) ),
		'privacy-policy' => array( 'title' => __( 'Privacy Policy', 'brickpoint' ) ),
		'terms-and-conditions' => array( 'title' => __( 'Terms and Conditions', 'brickpoint' ) ),
	) + array();
}

function bp_demo_menus() {
	return array(
		'primary' => array(
			'name'   => __( 'BrickPoint Primary Menu', 'brickpoint' ),
			'items'  => array(
				array( 'label' => __( 'Home', 'brickpoint' ), 'url' => '{{home}}' ),
				array( 'label' => __( 'Products', 'brickpoint' ), 'url' => '{{archive:bp_product}}' ),
				array( 'label' => __( 'Videos', 'brickpoint' ), 'url' => '{{archive:bp_video}}' ),
				array( 'label' => __( 'Projects', 'brickpoint' ), 'url' => '{{archive:bp_project}}' ),
				array( 'label' => __( 'Our Bhattas', 'brickpoint' ), 'url' => '{{archive:bp_location}}' ),
				array( 'label' => __( 'Blog', 'brickpoint' ), 'url' => '{{page:blog}}' ),
				array( 'label' => __( 'About', 'brickpoint' ), 'url' => '{{page:about}}' ),
				array( 'label' => __( 'Contact', 'brickpoint' ), 'url' => '{{page:contact}}' ),
			),
		),
		'footer' => array(
			'name'   => __( 'BrickPoint Footer Menu', 'brickpoint' ),
			'items'  => array(
				array( 'label' => __( 'About Us', 'brickpoint' ), 'url' => '{{page:about}}' ),
				array( 'label' => __( 'Our Bhattas', 'brickpoint' ), 'url' => '{{archive:bp_location}}' ),
				array( 'label' => __( 'Projects', 'brickpoint' ), 'url' => '{{archive:bp_project}}' ),
				array( 'label' => __( 'SS7 Bricks', 'brickpoint' ), 'url' => '{{page:ss7-bricks}}' ),
				array( 'label' => __( 'Construction Materials', 'brickpoint' ), 'url' => '{{page:construction-materials}}' ),
				array( 'label' => __( 'For Contractors', 'brickpoint' ), 'url' => '{{page:contractors}}' ),
				array( 'label' => __( 'For Builders', 'brickpoint' ), 'url' => '{{page:builders}}' ),
				array( 'label' => __( 'Blog', 'brickpoint' ), 'url' => '{{page:blog}}' ),
				array( 'label' => __( 'Privacy Policy', 'brickpoint' ), 'url' => '{{page:privacy-policy}}' ),
				array( 'label' => __( 'Terms and Conditions', 'brickpoint' ), 'url' => '{{page:terms-and-conditions}}' ),
			),
		),
		'mobile' => array(
			'name'   => __( 'BrickPoint Mobile Menu', 'brickpoint' ),
			'items'  => array(), // Falls back to the primary menu, exactly like the source design.
		),
	);
}
