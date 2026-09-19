<?php
/**
 * Elementor dynamic tags for BrickPoint content.
 *
 * Registered in the "BrickPoint" group so users can bind any Elementor
 * control to product / video / project / location fields.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register dynamic tags.
 */
function bp_register_dynamic_tags( $dynamic_tags_manager ) {
	if ( ! class_exists( '\Elementor\Core\DynamicTags\Tag' ) && ! class_exists( '\Elementor\Core\DynamicTags\Data_Tag' ) ) {
		return;
	}

	$files = array(
		'tag-text'  => 'BrickPoint_Dynamic_Text_Tag',
		'tag-url'   => 'BrickPoint_Dynamic_URL_Tag',
		'tag-image' => 'BrickPoint_Dynamic_Image_Tag',
	);

	foreach ( $files as $file => $class ) {
		$path = BRICKPOINT_DIR . '/elementor/dynamic-tags/' . $file . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
			if ( class_exists( $class ) ) {
				$dynamic_tags_manager->register( new $class() );
			}
		}
	}
}
add_action( 'elementor/dynamic_tags/register', 'bp_register_dynamic_tags' );
