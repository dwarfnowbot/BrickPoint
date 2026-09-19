<?php
/**
 * Elementor Pro Theme Builder condition helpers.
 *
 * Conditions are stored per template in `_elementor_conditions` post meta and
 * resolved through Pro's Conditions_Manager. We register a small extra
 * condition ("post_archive") that Pro does not register when the blog page is
 * a static page.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register BrickPoint theme-builder conditions.
 */
function bp_register_theme_conditions( $conditions_manager ) {
	// "Posts Archive" — Pro registers `{post_type}_archive` conditions only for
	// post types that expose an archive link. The blog page (is_home) needs one.
	if ( ! $conditions_manager->get_condition( 'post_archive' ) && class_exists( '\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base' ) ) {
		$conditions_manager->register_condition_instance( new BP_Post_Archive_Condition() );
	}
}
add_action( 'elementor/theme/register_conditions', 'bp_register_theme_conditions' );

if ( class_exists( '\ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base' ) ) {

	/**
	 * Blog / posts-archive condition.
	 */
	class BP_Post_Archive_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

		public static function get_type() {
			return 'archive';
		}

		public static function get_priority() {
			return 69;
		}

		public function get_name() {
			return 'post_archive';
		}

		public function get_label() {
			return __( 'Posts Archive', 'brickpoint' );
		}

		public function get_all_label() {
			return __( 'Posts Archive', 'brickpoint' );
		}

		public function check( $args ) {
			return is_home();
		}
	}
}

/**
 * Assign display conditions to a Theme Builder template.
 *
 * @param int   $post_id    Template post ID (elementor_library).
 * @param array $conditions Array of condition arrays, e.g.
 *                          array( array( 'type' => 'include', 'name' => 'general' ) ).
 * @return bool
 */
function bp_set_template_conditions( $post_id, $conditions ) {
	$strings = array();
	foreach ( (array) $conditions as $condition ) {
		$condition = array_filter( array_intersect_key( $condition, array_flip( array( 'type', 'name', 'sub_name', 'sub_id' ) ) ) );
		if ( empty( $condition['type'] ) || empty( $condition['name'] ) ) {
			continue;
		}
		$strings[] = rtrim( implode( '/', $condition ), '/' );
	}

	if ( ! $strings ) {
		return false;
	}

	// Preferred path: let Elementor Pro handle storage + cache regeneration.
	if ( bp_has_elementor_pro() && class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		try {
			$module = \ElementorPro\Modules\ThemeBuilder\Module::instance();
			$module->get_conditions_manager()->save_conditions( $post_id, $conditions );
			return true;
		} catch ( \Throwable $e ) {
			// Fall through to the manual path below.
		}
	}

	// Manual path (identical storage format to Pro).
	update_post_meta( $post_id, '_elementor_conditions', $strings );
	bp_rebuild_conditions_cache();
	return true;
}

/**
 * Rebuild the Pro conditions cache option in the exact format Pro uses:
 * [ location => [ document_id => [ condition strings ] ] ].
 */
function bp_rebuild_conditions_cache() {
	$cache = array();

	$query = new WP_Query( array(
		'post_type'      => 'elementor_library',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_key'       => '_elementor_conditions',
		'no_found_rows'  => true,
	) );

	foreach ( $query->posts as $template_id ) {
		$location = get_post_meta( $template_id, '_elementor_location', true );
		$type     = get_post_meta( $template_id, '_elementor_template_type', true );

		if ( ! $location && in_array( $type, array( 'header', 'footer', 'single', 'archive' ), true ) ) {
			$location = $type;
		}
		if ( ! $location ) {
			continue;
		}

		$conditions = get_post_meta( $template_id, '_elementor_conditions', true );
		if ( is_string( $conditions ) ) {
			$conditions = (array) json_decode( $conditions, true );
		}
		if ( ! $conditions ) {
			continue;
		}

		if ( ! isset( $cache[ $location ] ) ) {
			$cache[ $location ] = array();
		}
		$cache[ $location ][ $template_id ] = $conditions;
	}

	update_option( 'elementor_pro_theme_builder_conditions', $cache );

	// Also refresh through Pro when available (keeps in-memory copy in sync).
	if ( bp_has_elementor_pro() && class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		try {
			$module = \ElementorPro\Modules\ThemeBuilder\Module::instance();
			$module->get_conditions_manager()->get_cache()->refresh();
		} catch ( \Throwable $e ) { // phpcs:ignore
			// Option already updated above.
		}
	}
}
