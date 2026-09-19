<?php
/**
 * Asset enqueuing.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_assets() {
	$ver = BRICKPOINT_VERSION;

	wp_enqueue_style(
		'brickpoint-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'brickpoint-main', BRICKPOINT_URI . '/assets/css/main.css', array(), $ver );
	wp_enqueue_style( 'brickpoint-animations', BRICKPOINT_URI . '/assets/css/animations.css', array( 'brickpoint-main' ), $ver );
	wp_enqueue_style( 'brickpoint-responsive', BRICKPOINT_URI . '/assets/css/responsive.css', array( 'brickpoint-main' ), $ver );
	wp_enqueue_style( 'brickpoint-style', get_stylesheet_uri(), array( 'brickpoint-main' ), $ver );

	wp_enqueue_script( 'brickpoint-navigation', BRICKPOINT_URI . '/assets/js/navigation.js', array(), $ver, true );
	wp_enqueue_script( 'brickpoint-animations', BRICKPOINT_URI . '/assets/js/animations.js', array(), $ver, true );
	wp_enqueue_script( 'brickpoint-main', BRICKPOINT_URI . '/assets/js/main.js', array(), $ver, true );

	if ( is_singular( 'bp_product' ) || is_post_type_archive( 'bp_product' ) || is_tax( 'bp_product_category' ) ) {
		wp_enqueue_script( 'brickpoint-product', BRICKPOINT_URI . '/assets/js/product.js', array(), $ver, true );
	}
	if ( is_singular( 'bp_video' ) || is_post_type_archive( 'bp_video' ) || is_tax( 'bp_video_category' ) ) {
		wp_enqueue_script( 'brickpoint-video', BRICKPOINT_URI . '/assets/js/video.js', array(), $ver, true );
	}

	wp_enqueue_script( 'brickpoint-ajax', BRICKPOINT_URI . '/assets/js/ajax.js', array( 'jquery' ), $ver, true );
	wp_localize_script( 'brickpoint-ajax', 'BRICKPOINT', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'brickpoint_nonce' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brickpoint_assets' );

/**
 * Design-token CSS variables driven by the Customizer.
 */
function brickpoint_css_vars() {
	$primary   = sanitize_hex_color( bp_get( 'bp_primary', '#c2410c' ) );
	$secondary = sanitize_hex_color( bp_get( 'bp_secondary', '#141210' ) );
	$accent    = sanitize_hex_color( bp_get( 'bp_accent', '#ea580c' ) );
	$sand      = sanitize_hex_color( bp_get( 'bp_sand', '#f6f1ea' ) );
	$radius    = absint( bp_get( 'bp_radius', 18 ) );
	$container = absint( bp_get( 'bp_container', 1200 ) );

	$css = sprintf(
		':root{--bp-brick:%1$s;--bp-ink:%2$s;--bp-ember:%3$s;--bp-sand:%4$s;--bp-radius:%5$dpx;--bp-container:%6$dpx;}',
		$primary ? $primary : '#c2410c',
		$secondary ? $secondary : '#141210',
		$accent ? $accent : '#ea580c',
		$sand ? $sand : '#f6f1ea',
		$radius,
		$container
	);
	wp_add_inline_style( 'brickpoint-main', $css );
}
add_action( 'wp_enqueue_scripts', 'brickpoint_css_vars', 20 );
