<?php
/**
 * Elementor integration: support flags, category, Pro locations,
 * kit bootstrap and template import helpers.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * True when Elementor (free) is loaded.
 */
function bp_has_elementor() {
	// Constant check, not class_exists(): class_exists() can fire a class
	// autoloader and load Elementor files outside the plugin bootstrap on
	// some hosts (fatal). ELEMENTOR_VERSION is defined first thing in the
	// plugin main file, so this is exact and side-effect free.
	return defined( 'ELEMENTOR_VERSION' );
}

/**
 * True when Elementor Pro is loaded.
 */
function bp_has_elementor_pro() {
	return defined( 'ELEMENTOR_PRO_VERSION' );
}

/**
 * True when a given post was built with Elementor.
 */
function bp_is_elementor_built( $post_id ) {
	if ( ! bp_has_elementor() ) {
		return false;
	}
	return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/**
 * Render an Elementor Pro Theme Builder location; returns true when handled.
 */
function brickpoint_do_location( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}
	return false;
}

/**
 * Register the BrickPoint widget panel category.
 */
function brickpoint_elementor_category( $elements_manager ) {
	$elements_manager->add_category( 'brickpoint', array(
		'title' => __( 'BrickPoint', 'brickpoint' ),
		'icon'  => 'eicon-brickpoint',
	) );
}
add_action( 'elementor/elements/categories_registered', 'brickpoint_elementor_category' );

/**
 * Editor styles for Elementor canvas previews.
 */
function brickpoint_elementor_editor_styles() {
	if ( ! bp_has_elementor() ) {
		return;
	}
	wp_enqueue_style( 'brickpoint-main', BRICKPOINT_URI . '/assets/css/main.css', array(), BRICKPOINT_VERSION );
	wp_enqueue_style( 'brickpoint-responsive', BRICKPOINT_URI . '/assets/css/responsive.css', array( 'brickpoint-main' ), BRICKPOINT_VERSION );
}
add_action( 'elementor/editor/after_enqueue_styles', 'brickpoint_elementor_editor_styles' );

/**
 * Force Elementor to generate uniform default container widths that match
 * the LM Arena 1200px container.
 */
function brickpoint_elementor_defaults( $defaults ) {
	$defaults['container_width']['size']   = 1200;
	$defaults['space_between_widgets']     = 0;
	$defaults['page_title_selector']       = 'h1.entry-title';
	return $defaults;
}
add_filter( 'elementor/element/default_settings', 'brickpoint_elementor_defaults' );

/**
 * Ensure an Elementor kit exists with the BrickPoint design system
 * (global colors + typography mirror the LM Arena tokens).
 */
function bp_elementor_ensure_kit() {
	if ( ! bp_has_elementor() ) {
		return 0;
	}

	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( $kit_id && get_post( $kit_id ) ) {
		return $kit_id;
	}

	$existing = get_posts( array(
		'post_type'      => 'elementor_library',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'title'          => 'BrickPoint Kit',
		'fields'         => 'ids',
	) );
	if ( $existing ) {
		update_option( 'elementor_active_kit', (int) $existing[0] );
		return (int) $existing[0];
	}

	$kit_id = wp_insert_post( array(
		'post_title'  => 'BrickPoint Kit',
		'post_type'   => 'elementor_library',
		'post_status' => 'publish',
	) );
	if ( is_wp_error( $kit_id ) || ! $kit_id ) {
		return 0;
	}

	update_post_meta( $kit_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $kit_id, '_elementor_data', '[]' );
	update_post_meta( $kit_id, '_elementor_template_type', 'kit' );
	update_post_meta( $kit_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $kit_id, '_elementor_page_settings', array(
		'system_colors'       => array(
			array( '_id' => 'primary', 'title' => 'Primary', 'color' => '#EA580C' ),
			array( '_id' => 'secondary', 'title' => 'Secondary', 'color' => '#141210' ),
			array( '_id' => 'text', 'title' => 'Text', 'color' => '#3D3833' ),
			array( '_id' => 'accent', 'title' => 'Accent', 'color' => '#C2410C' ),
		),
		'custom_colors'       => array(
			array( '_id' => 'bp-ink', 'title' => 'BP Ink', 'color' => '#141210' ),
			array( '_id' => 'bp-charcoal', 'title' => 'BP Charcoal', 'color' => '#1C1A17' ),
			array( '_id' => 'bp-brick', 'title' => 'BP Brick', 'color' => '#C2410C' ),
			array( '_id' => 'bp-ember', 'title' => 'BP Ember', 'color' => '#EA580C' ),
			array( '_id' => 'bp-sand', 'title' => 'BP Sand', 'color' => '#F6F1EA' ),
			array( '_id' => 'bp-whatsapp', 'title' => 'BP WhatsApp', 'color' => '#128C4B' ),
		),
		'system_typography'   => array(
			array(
				'_id'                        => 'primary',
				'title'                      => 'Primary',
				'typography_typography'      => 'custom',
				'typography_font_family'     => 'Archivo',
				'typography_font_weight'     => '800',
			),
			array(
				'_id'                        => 'secondary',
				'title'                      => 'Secondary',
				'typography_typography'      => 'custom',
				'typography_font_family'     => 'Archivo',
				'typography_font_weight'     => '700',
			),
			array(
				'_id'                        => 'text',
				'title'                      => 'Text',
				'typography_typography'      => 'custom',
				'typography_font_family'     => 'Inter',
				'typography_font_weight'     => '400',
			),
			array(
				'_id'                        => 'accent',
				'title'                      => 'Accent',
				'typography_typography'      => 'custom',
				'typography_font_family'     => 'Archivo',
				'typography_font_weight'     => '800',
			),
		),
		'body_typography_typography'  => 'custom',
		'body_typography_font_family' => 'Inter',
		'body_typography_font_weight' => '400',
		'container_width'             => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
		'title'                       => 'BrickPoint Design System',
		'description'                 => 'LM Arena design tokens: ink/charcoal/brick/ember palette, Archivo + Inter type.',
	) );

	update_option( 'elementor_active_kit', (int) $kit_id );

	return (int) $kit_id;
}
