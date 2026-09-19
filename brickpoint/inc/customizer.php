<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function brickpoint_customize( $wp_customize ) {
  $wp_customize->add_section( 'bp_contact', array( 'title' => __( 'BrickPoint: Contact', 'brickpoint' ), 'priority' => 30 ) );
  $fields = array(
    'bp_phone_display' => array( __( 'Phone display', 'brickpoint' ), '0315 2850818', 'text' ),
    'bp_whatsapp_number' => array( __( 'WhatsApp number (intl, no +)', 'brickpoint' ), '923152850818', 'text' ),
    'bp_email' => array( __( 'Email', 'brickpoint' ), 'info@brickpoint.pk', 'email' ),
    'bp_address' => array( __( 'Company address', 'brickpoint' ), 'Lahore, Punjab, Pakistan', 'text' ),
    'bp_ceo' => array( __( 'CEO name', 'brickpoint' ), 'Syed Iftikhar Haider', 'text' ),
    'bp_sales' => array( __( 'Sales Manager name', 'brickpoint' ), 'Qasim Iqbal', 'text' ),
    'bp_default_wa' => array( __( 'Default WhatsApp message', 'brickpoint' ), 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.', 'textarea' ),
    'bp_copyright' => array( __( 'Footer copyright', 'brickpoint' ), '© BrickPoint. All rights reserved.', 'text' ),
  );
  foreach ( $fields as $key => $def ) {
    list( $label, $default, $type ) = $def;
    $wp_customize->add_setting( $key, array( 'default' => $default, 'sanitize_callback' => ( $type === 'email' ? 'sanitize_email' : ( $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field' ) ) ) );
    $ctl = $type === 'textarea' ? 'WP_Customize_Control' : ( $type === 'email' ? 'WP_Customize_Control' : 'WP_Customize_Control' );
    $wp_customize->add_control( new $ctl( $wp_customize, $key, array( 'label' => $label, 'section' => 'bp_contact', 'type' => $type ) ) );
  }

  $wp_customize->add_section( 'bp_social', array( 'title' => __( 'BrickPoint: Social Links', 'brickpoint' ), 'priority' => 31 ) );
  foreach ( array( 'facebook' => 'Facebook URL', 'instagram' => 'Instagram URL', 'twitter' => 'X / Twitter URL', 'tiktok' => 'TikTok URL' ) as $net => $label ) {
    $defaults = array(
      'facebook' => 'https://www.facebook.com/brickpoint.pk/', 'instagram' => 'https://www.instagram.com/brickpoint.pk/',
      'twitter' => 'https://x.com/BrickPointPK', 'tiktok' => 'https://www.tiktok.com/@brickpoint.pk/',
    );
    $wp_customize->add_setting( 'bp_social_' . $net, array( 'default' => $defaults[ $net ], 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'bp_social_' . $net, array( 'label' => __( $label, 'brickpoint' ), 'section' => 'bp_social', 'type' => 'url' ) );
  }

  $wp_customize->add_section( 'bp_hero', array( 'title' => __( 'BrickPoint: Hero Video', 'brickpoint' ), 'priority' => 32 ) );
  $hero = array(
    'bp_hero_video' => __( 'Hero video file/URL (MP4)', 'brickpoint' ),
    'bp_hero_poster' => __( 'Hero poster image URL', 'brickpoint' ),
    'bp_hero_overlay' => __( 'Hero overlay opacity (0-90)', 'brickpoint' ),
  );
  foreach ( $hero as $key => $label ) {
    $wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => ( $key === 'bp_hero_overlay' ? 'absint' : 'esc_url_raw' ) ) );
    $wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'bp_hero', 'type' => ( $key === 'bp_hero_overlay' ? 'number' : 'url' ) ) );
  }
  $wp_customize->add_setting( 'bp_hero_video_pos', array( 'default' => 'right', 'sanitize_callback' => 'sanitize_text_field' ) );
  $wp_customize->add_control( 'bp_hero_video_pos', array( 'label' => __( 'Hero video position', 'brickpoint' ), 'section' => 'bp_hero', 'type' => 'select', 'choices' => array( 'right' => 'Right', 'background' => 'Background', 'below' => 'Below content' ) ) );

  $wp_customize->add_section( 'bp_colors', array( 'title' => __( 'BrickPoint: Colors & Design', 'brickpoint' ), 'priority' => 33 ) );
  foreach ( array(
    'bp_primary' => array( __( 'Primary color', 'brickpoint' ), '#c2410c' ),
    'bp_secondary' => array( __( 'Secondary / ink', 'brickpoint' ), '#141210' ),
    'bp_accent' => array( __( 'Accent color', 'brickpoint' ), '#ea580c' ),
  ) as $key => $def ) {
    $wp_customize->add_setting( $key, array( 'default' => $def[1], 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $def[0], 'section' => 'bp_colors' ) ) );
  }
  $wp_customize->add_setting( 'bp_radius', array( 'default' => '18', 'sanitize_callback' => 'absint' ) );
  $wp_customize->add_control( 'bp_radius', array( 'label' => __( 'Card border radius (px)', 'brickpoint' ), 'section' => 'bp_colors', 'type' => 'number' ) );
  $wp_customize->add_setting( 'bp_container', array( 'default' => '1200', 'sanitize_callback' => 'absint' ) );
  $wp_customize->add_control( 'bp_container', array( 'label' => __( 'Container width (px)', 'brickpoint' ), 'section' => 'bp_colors', 'type' => 'number' ) );
}
add_action( 'customize_register', 'brickpoint_customize' );
