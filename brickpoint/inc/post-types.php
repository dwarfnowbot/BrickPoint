<?php
/**
 * Custom post types: Products, Videos, Projects, Locations.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_register_post_types() {
	register_post_type( 'bp_product', array(
		'labels'        => array(
			'name'               => __( 'Products', 'brickpoint' ),
			'singular_name'      => __( 'Product', 'brickpoint' ),
			'add_new'            => __( 'Add New', 'brickpoint' ),
			'add_new_item'       => __( 'Add New Product', 'brickpoint' ),
			'edit_item'          => __( 'Edit Product', 'brickpoint' ),
			'new_item'           => __( 'New Product', 'brickpoint' ),
			'view_item'          => __( 'View Product', 'brickpoint' ),
			'search_items'       => __( 'Search Products', 'brickpoint' ),
			'not_found'          => __( 'No products found', 'brickpoint' ),
			'all_items'          => __( 'All Products', 'brickpoint' ),
			'menu_name'          => __( 'Products', 'brickpoint' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 21,
		'rewrite'       => array( 'slug' => 'products', 'with_front' => false ),
		'menu_icon'     => 'dashicons-building',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'custom-fields' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'bp_video', array(
		'labels'        => array(
			'name'               => __( 'Videos', 'brickpoint' ),
			'singular_name'      => __( 'Video', 'brickpoint' ),
			'add_new_item'       => __( 'Add New Video', 'brickpoint' ),
			'edit_item'          => __( 'Edit Video', 'brickpoint' ),
			'view_item'          => __( 'View Video', 'brickpoint' ),
			'search_items'       => __( 'Search Videos', 'brickpoint' ),
			'all_items'          => __( 'All Videos', 'brickpoint' ),
			'menu_name'          => __( 'Videos', 'brickpoint' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 22,
		'rewrite'       => array( 'slug' => 'videos', 'with_front' => false ),
		'menu_icon'     => 'dashicons-video-alt3',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'bp_project', array(
		'labels'        => array(
			'name'               => __( 'Projects', 'brickpoint' ),
			'singular_name'      => __( 'Project', 'brickpoint' ),
			'add_new_item'       => __( 'Add New Project', 'brickpoint' ),
			'edit_item'          => __( 'Edit Project', 'brickpoint' ),
			'view_item'          => __( 'View Project', 'brickpoint' ),
			'all_items'          => __( 'All Projects', 'brickpoint' ),
			'menu_name'          => __( 'Projects', 'brickpoint' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 23,
		'rewrite'       => array( 'slug' => 'projects', 'with_front' => false ),
		'menu_icon'     => 'dashicons-portfolio',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'custom-fields' ),
		'show_in_rest'  => true,
	) );

	register_post_type( 'bp_location', array(
		'labels'        => array(
			'name'               => __( 'Locations', 'brickpoint' ),
			'singular_name'      => __( 'Location', 'brickpoint' ),
			'add_new_item'       => __( 'Add New Location', 'brickpoint' ),
			'edit_item'          => __( 'Edit Location', 'brickpoint' ),
			'view_item'          => __( 'View Location', 'brickpoint' ),
			'all_items'          => __( 'All Locations', 'brickpoint' ),
			'menu_name'          => __( 'Locations', 'brickpoint' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 24,
		'rewrite'       => array( 'slug' => 'locations', 'with_front' => false ),
		'menu_icon'     => 'dashicons-location-alt',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'brickpoint_register_post_types' );

/**
 * Filter the main archive queries: sensible ordering + pagination.
 */
function brickpoint_archive_queries( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'bp_product' ) || $query->is_tax( 'bp_product_category' ) ) {
		$query->set( 'posts_per_page', 12 );
		$query->set( 'meta_key', '_bp_sort' );
		$query->set( 'orderby', 'meta_value_num title' );
		$query->set( 'order', 'ASC' );
	}

	if ( $query->is_post_type_archive( 'bp_video' ) || $query->is_tax( 'bp_video_category' ) ) {
		$query->set( 'posts_per_page', 12 );
	}

	if ( $query->is_post_type_archive( 'bp_project' ) || $query->is_tax( 'bp_project_category' ) ) {
		$query->set( 'posts_per_page', 12 );
		$query->set( 'meta_key', '_bpp_sort' );
		$query->set( 'orderby', 'meta_value_num title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'brickpoint_archive_queries' );
