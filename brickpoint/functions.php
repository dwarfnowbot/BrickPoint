<?php
/**
 * BrickPoint theme bootstrap.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRICKPOINT_VERSION', '2.0.0' );
define( 'BRICKPOINT_DIR', get_template_directory() );
define( 'BRICKPOINT_URI', get_template_directory_uri() );

require BRICKPOINT_DIR . '/inc/setup.php';
require BRICKPOINT_DIR . '/inc/enqueue.php';
require BRICKPOINT_DIR . '/inc/helpers.php';
require BRICKPOINT_DIR . '/inc/template-functions.php';
require BRICKPOINT_DIR . '/inc/post-types.php';
require BRICKPOINT_DIR . '/inc/taxonomies.php';
require BRICKPOINT_DIR . '/inc/meta-fields.php';
require BRICKPOINT_DIR . '/inc/whatsapp.php';
require BRICKPOINT_DIR . '/inc/video-functions.php';
require BRICKPOINT_DIR . '/inc/project-functions.php';
require BRICKPOINT_DIR . '/inc/customizer.php';
require BRICKPOINT_DIR . '/inc/elementor.php';

/**
 * Elementor-dependent modules load only when Elementor is active. By the time
 * the theme loads, plugins (incl. Elementor) are already registered, so this
 * is safe for every activation order (theme first or plugin first).
 */
if ( bp_has_elementor() ) {
	require BRICKPOINT_DIR . '/inc/elementor-widgets.php';
	require BRICKPOINT_DIR . '/inc/elementor-dynamic-tags.php';
	require BRICKPOINT_DIR . '/inc/elementor-conditions.php';
}

require BRICKPOINT_DIR . '/inc/demo/importer.php';
require BRICKPOINT_DIR . '/inc/demo/content.php';
require BRICKPOINT_DIR . '/inc/admin.php';
