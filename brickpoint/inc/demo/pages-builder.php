<?php
/**
 * Demo page content builders (Elementor JSON for inner pages).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ */
/* Elementor JSON helpers                                              */
/* ------------------------------------------------------------------ */

function bp_el_id() {
	static $i = 0;
	$i++;
	return substr( str_pad( (string) $i, 7, '0' ) . dechex( wp_rand( 0, 999999 ) ), 0, 7 );
}

function bp_el_widget( $type, $settings = array() ) {
	return array(
		'id'         => bp_el_id(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
		'elements'   => array(),
	);
}

function bp_el_column( $widgets, $settings = array() ) {
	return array(
		'id'       => bp_el_id(),
		'elType'   => 'column',
		'settings' => array_merge( array( '_column_size' => 100, '_inline_size' => null ), $settings ),
		'elements' => $widgets,
		'isInner'  => false,
	);
}

function bp_el_section( $columns, $settings = array() ) {
	return array(
		'id'       => bp_el_id(),
		'elType'   => 'section',
		'settings' => array_merge( array( 'layout' => 'boxed' ), $settings ),
		'elements' => $columns,
		'isInner'  => false,
	);
}

function bp_el_padding( $top, $bottom = null ) {
	$bottom = null === $bottom ? $top : $bottom;
	return array(
		'unit' => 'px',
		'top' => (string) $top,
		'right' => '20',
		'bottom' => (string) $bottom,
		'left' => '20',
		'isLinked' => false,
	);
}

function bp_el_pagehead( $title, $lead = '', $eyebrow = '' ) {
	$widgets = array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) );
	return bp_el_section( array( bp_el_column( $widgets ) ), array(
		'padding'            => bp_el_padding( 0, 0 ),
		'background_background' => 'classic',
		'background_color'   => '#141210',
	) );
}

/* ------------------------------------------------------------------ */
/* Page builders                                                       */
/* ------------------------------------------------------------------ */

/**
 * Build Elementor content for a demo page by slug.
 */
function bp_demo_page_content( $slug ) {
	switch ( $slug ) {
		case 'about':
			return bp_page_about();
		case 'contact':
			return bp_page_contact();
		case 'contractors':
			return bp_page_audience(
				__( 'Materials that keep your sites running', 'brickpoint' ),
				__( 'Daily-site supply for contractors — yard-direct pricing, stack-counted loads and WhatsApp ordering that replies in minutes, not days.', 'brickpoint' ),
				array(
					__( 'Priority dispatch windows for active sites', 'brickpoint' ),
					__( 'Fixed price locks for 30/60/90-day projects', 'brickpoint' ),
					__( 'One coordinator for all your yards', 'brickpoint' ),
					__( 'Breakage allowance included in bulk deals', 'brickpoint' ),
				)
			);
		case 'builders':
			return bp_page_audience(
				__( 'Build more homes with fewer site delays', 'brickpoint' ),
				__( 'Builders rely on BrickPoint for matched brick batches, scheduled deliveries and materials that pass inspection the first time.', 'brickpoint' ),
				array(
					__( 'Batch-matched SS7 color for clean elevations', 'brickpoint' ),
					__( 'Weekly scheduled loads to match your block work', 'brickpoint' ),
					__( 'Crush gradation certificates on request', 'brickpoint' ),
					__( 'Tuff tiles and pavers for finishing packages', 'brickpoint' ),
				)
			);
		case 'construction-companies':
			return bp_page_audience(
				__( 'Procurement your PM will thank you for', 'brickpoint' ),
				__( 'Multi-site construction companies get consolidated invoicing, dedicated quota allocation and documented quality checks across every yard.', 'brickpoint' ),
				array(
					__( 'Company accounts with consolidated billing', 'brickpoint' ),
					__( 'Reserved kiln quota for project timelines', 'brickpoint' ),
					__( 'Weight tickets and gradation docs per load', 'brickpoint' ),
					__( 'SLA-backed delivery scheduling', 'brickpoint' ),
				)
			);
		case 'ss7-bricks':
			return bp_page_ss7();
		case 'construction-materials':
			return bp_page_materials();
		case 'privacy-policy':
			return bp_page_legal(
				__( 'Privacy Policy', 'brickpoint' ),
				bp_legal_privacy_text()
			);
		case 'terms-and-conditions':
			return bp_page_legal(
				__( 'Terms and Conditions', 'brickpoint' ),
				bp_legal_terms_text()
			);
		case 'blog':
			return array(
				bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ), ), array(
					'padding'              => bp_el_padding( 0, 0 ),
					'background_background' => 'classic',
					'background_color'     => '#141210',
				) ),
				bp_el_section( array( bp_el_column( array(
					bp_el_widget( 'bp-posts-grid', array(
						'source'       => 'archive',
						'show_heading' => '',
						'show_pagination' => 'yes',
						'show_date'    => 'yes',
						'show_author'  => 'yes',
					) ),
				) ) ), array( 'padding' => bp_el_padding( 56, 56 ) ) ),
			);
	}

	return array();
}

/**
 * About page.
 */
function bp_page_about() {
	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'heading', array(
				'title'       => __( 'Three yards. One standard.', 'brickpoint' ),
				'header_size' => 'h2',
				'align'       => 'center',
			) ),
			bp_el_widget( 'text-editor', array(
				'editor' => sprintf(
					/* translators: 1: CEO name, 2: sales manager name */
					'<p style="text-align:center;max-width:720px;margin:0.8rem auto 0">%s</p>',
					esc_html__( 'BrickPoint is the retail face of Masha Allah Bricks Co. and Fine Bricks Co. — family-run bhattas that have been firing brick in Punjab for over fifteen years. Under the leadership of Syed Iftikhar Haider, and with sales manager Qasim Iqbal coordinating city-wide deliveries, we supply everything from a single wall of Awami bricks to palletized SS7 contracts for housing schemes.', 'brickpoint' )
				),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 56, 24 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'image', array(
				'image'     => array( 'url' => '{{media:hero}}', 'id' => '{{media_id:hero}}' ),
				'image_size' => 'full',
				'align'     => 'center',
				'link_to'   => 'none',
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 40 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-stats' ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ), 'background_background' => 'classic', 'background_color' => '#1C1A17' ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-cta' ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
	);
}

/**
 * Contact page.
 */
function bp_page_contact() {
	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-whatsapp-button', array(
				'align'   => 'bp-center',
				'label'   => __( 'Message us on WhatsApp', 'brickpoint' ),
				'message' => __( 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.', 'brickpoint' ),
			) ),
			bp_el_widget( 'text-editor', array(
				'editor' => sprintf(
					'<p style="text-align:center">%s<br><strong>%s</strong> • %s</p>',
					esc_html__( 'Fastest replies come on WhatsApp. Prefer to talk? Call us — we pick up.', 'brickpoint' ),
					esc_html( bp_phone_display() ),
					esc_html( bp_email() )
				),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 48, 24 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-location-cards', array(
				'count'       => 12,
				'show_heading' => 'yes',
				'eyebrow'     => __( 'Our Bhattas', 'brickpoint' ),
				'title'       => __( 'Visit a yard near you', 'brickpoint' ),
				'lead'        => __( 'Call ahead and we will keep fresh stock ready for your inspection.', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
	);
}

/**
 * Audience landing pages (contractors / builders / companies).
 */
function bp_page_audience( $title, $lead, $points ) {
	$items = array();
	foreach ( $points as $i => $point ) {
		$items[] = array(
			'text'          => $point,
			'selected_icon' => array( 'value' => 'fas fa-check', 'library' => 'fa-solid' ),
			'_id'           => bp_el_id(),
		);
	}

	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'text-editor', array(
				'editor' => sprintf( '<h2>%s</h2><p class="bp-lead">%s</p>', esc_html( $title ), esc_html( $lead ) ),
			) ),
			bp_el_widget( 'icon-list', array( 'icon_list' => $items ) ),
			bp_el_widget( 'bp-whatsapp-button', array(
				'label'   => __( 'Get a quotation', 'brickpoint' ),
				'message' => __( 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 48, 40 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-product-categories', array(
				'show_heading' => 'yes',
				'eyebrow'      => __( 'What we supply', 'brickpoint' ),
				'title'        => __( 'Everything your site needs, one order', 'brickpoint' ),
				'count'        => 8,
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-product-grid', array(
				'source'    => 'featured',
				'count'     => 4,
				'show_heading' => 'yes',
				'eyebrow'   => __( 'Popular with your peers', 'brickpoint' ),
				'title'     => __( 'Materials contractors reorder every week', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ), 'background_background' => 'classic', 'background_color' => '#1C1A17' ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-cta' ),
		) ) ), array( 'padding' => bp_el_padding( 24, 56 ) ) ),
	);
}

/**
 * SS7 Bricks landing page.
 */
function bp_page_ss7() {
	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'image', array(
				'image'      => array( 'url' => '{{media:ss7}}', 'id' => '{{media_id:ss7}}' ),
				'image_size' => 'full',
				'align'      => 'center',
			) ),
		) ) ), array( 'padding' => bp_el_padding( 48, 16 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'text-editor', array(
				'editor' => sprintf(
					'<h2 style="text-align:center">%s</h2><p style="text-align:center;max-width:720px;margin:0.8rem auto 0">%s</p>',
					esc_html__( 'SS7 — the brick we stamp our name on', 'brickpoint' ),
					esc_html__( 'Machine-molded for dimensional accuracy and fired in our modern tunnel kiln, SS7 delivers 7.5+ MPa compressive strength with a deep, consistent red. When the facade is the architecture, architects specify SS7.', 'brickpoint' )
				),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 40 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-product-grid', array(
				'source'    => 'category',
				'category'  => 'ss7',
				'count'     => 4,
				'show_heading' => 'yes',
				'eyebrow'   => __( 'SS7 Range', 'brickpoint' ),
				'title'     => __( 'Buy SS7 by the stack or by the pallet', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ), 'background_background' => 'classic', 'background_color' => '#F6F1EA' ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-video-grid', array(
				'source'    => 'latest',
				'count'     => 3,
				'show_heading' => 'yes',
				'eyebrow'   => __( 'See it made', 'brickpoint' ),
				'title'     => __( 'SS7 from clay to kiln', 'brickpoint' ),
				'show_all_btn' => '',
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-cta', array(
				'title'    => __( 'Planning a premium facade?', 'brickpoint' ),
				'text'     => __( 'Ask for SS7 batch samples — we deliver sample stacks to your site free within Lahore.', 'brickpoint' ),
				'btn1_label' => __( 'Request SS7 samples', 'brickpoint' ),
				'btn2_label' => __( 'All Products', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 24, 56 ) ) ),
	);
}

/**
 * Construction materials landing page.
 */
function bp_page_materials() {
	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'text-editor', array(
				'editor' => sprintf(
					'<h2 style="text-align:center">%s</h2><p style="text-align:center;max-width:720px;margin:0.8rem auto 0">%s</p>',
					esc_html__( 'One order. Every material.', 'brickpoint' ),
					esc_html__( 'Bricks, blocks, tiles, crush and sand — order the whole list in one WhatsApp message and get it loaded, counted and delivered together.', 'brickpoint' )
				),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 48, 32 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-product-categories', array(
				'count'        => 8,
				'show_heading' => '',
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 40 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-product-grid', array(
				'source'    => 'latest',
				'count'     => 8,
				'show_heading' => 'yes',
				'eyebrow'   => __( 'This week at the yard', 'brickpoint' ),
				'title'     => __( 'Fresh stock, ready to load', 'brickpoint' ),
			) ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'bp-cta' ),
		) ) ), array( 'padding' => bp_el_padding( 0, 56 ) ) ),
	);
}

/**
 * Legal pages (privacy / terms).
 */
function bp_page_legal( $title, $paragraphs ) {
	$html = '<h1>' . esc_html( $title ) . '</h1>';
	foreach ( $paragraphs as $p ) {
		$html .= '<p>' . esc_html( $p ) . '</p>';
	}

	return array(
		bp_el_section( array( bp_el_column( array( bp_el_widget( 'bp-archive-head', array( 'show_filters' => '' ) ) ) ) ), array(
			'padding'              => bp_el_padding( 0, 0 ),
			'background_background' => 'classic',
			'background_color'     => '#141210',
		) ),
		bp_el_section( array( bp_el_column( array(
			bp_el_widget( 'text-editor', array(
				'editor'   => wpautop( implode( "\n\n", $paragraphs ) ),
				'typography_typography' => 'custom',
			) ),
		) ) ), array( 'padding' => bp_el_padding( 48, 56 ) ) ),
	);
}

function bp_legal_privacy_text() {
	return array(
		__( 'BrickPoint (operated by Masha Allah Bricks Co. / Fine Bricks Co.) respects your privacy. This policy explains what we collect when you contact us or order materials, and how we use it.', 'brickpoint' ),
		__( 'What we collect: your name, phone number, site address and material requirements when you contact us on WhatsApp, by phone or through this website. We also collect standard, anonymous website usage data.', 'brickpoint' ),
		__( 'How we use it: only to prepare quotations, schedule deliveries and follow up on your orders. We never sell your information to third parties. Delivery partners receive only the details needed to complete your delivery.', 'brickpoint' ),
		__( 'Data retention: order records are kept for accounting and warranty purposes. You may ask us to delete your contact details at any time by messaging us on WhatsApp.', 'brickpoint' ),
		__( 'Cookies: this website may use essential cookies for site functionality. No advertising trackers are installed.', 'brickpoint' ),
		sprintf( __( 'Questions? Contact us at %s or call %s.', 'brickpoint' ), bp_email(), bp_phone_display() ),
	);
}

function bp_legal_terms_text() {
	return array(
		__( 'These terms govern orders placed with BrickPoint (Masha Allah Bricks Co. / Fine Bricks Co.) through this website, WhatsApp or by phone. By confirming an order you accept these terms.', 'brickpoint' ),
		__( 'Quotations: prices quoted are valid for 7 days unless stated otherwise. Bulk site deals may carry longer validity as noted in the written quotation. Prices are ex-yard or delivered as agreed, exclusive of unloading unless arranged.', 'brickpoint' ),
		__( 'Orders and delivery: orders are confirmed on WhatsApp/phone and scheduled on a first-come basis. Delivery windows are estimates; site access and road conditions may affect timing. Someone must receive and count the load at the site.', 'brickpoint' ),
		__( 'Payments: payment is due on delivery unless a credit account is agreed in writing. Cheques clear before the next dispatch.', 'brickpoint' ),
		__( 'Quality and returns: bricks are stack-counted and quality-sorted before loading. Report shortages, breakage beyond the agreed allowance or quality issues at the time of delivery — claims after unloading may not be accepted.', 'brickpoint' ),
		__( 'Illustrative content: project images marked "Illustrative" are reference visuals and are not represented as executed BrickPoint contracts.', 'brickpoint' ),
		sprintf( __( 'For any questions about these terms, contact %s.', 'brickpoint' ), bp_email() ),
	);
}
