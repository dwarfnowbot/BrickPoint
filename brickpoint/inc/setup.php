<?php
/**
 * Theme setup: supports, image sizes, menus, widget areas.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_setup() {
	load_theme_textdomain( 'brickpoint', BRICKPOINT_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 220,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	// Elementor support (free + Pro Theme Builder locations).
	add_theme_support( 'elementor' );

	add_image_size( 'bp-card', 800, 600, true );
	add_image_size( 'bp-card-wide', 800, 450, true );
	add_image_size( 'bp-video', 1280, 720, true );
	add_image_size( 'bp-hero', 1600, 900, true );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'brickpoint' ),
		'footer'  => __( 'Footer Menu', 'brickpoint' ),
		'mobile'  => __( 'Mobile Menu', 'brickpoint' ),
	) );
}
add_action( 'after_setup_theme', 'brickpoint_setup' );

function brickpoint_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'brickpoint_content_width', 1200 );
}
add_action( 'after_setup_theme', 'brickpoint_content_width', 0 );

function brickpoint_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'brickpoint' ),
		'id'            => 'sidebar-blog',
		'description'   => __( 'Widgets shown beside blog posts when the Elementor blog templates are not in use.', 'brickpoint' ),
		'before_widget' => '<section class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			/* translators: %d: footer column number. */
			'name'          => sprintf( __( 'Footer Column %d', 'brickpoint' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<div class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'brickpoint_widgets_init' );
