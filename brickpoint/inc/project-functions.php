<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bp_project_status( $post_id ) {
  $s = bp_meta( $post_id, '_bpp_status', '' );
  return $s ? $s : __( 'Illustrative construction reference', 'brickpoint' );
}
function bp_project_gallery( $post_id ) {
  $raw = bp_meta( $post_id, '_bpp_gallery', '' );
  if ( ! $raw ) { return array(); }
  return array_values( array_filter( array_map( 'trim', preg_split( '/\r?\n/', $raw ) ) ) );
}
function bp_locations_list( $args = array() ) {
  $a = wp_parse_args( $args, array( 'posts_per_page' => 20, 'orderby' => 'meta_value_num', 'meta_key' => '_bpl_order', 'order' => 'ASC' ) );
  return new WP_Query( array_merge( $a, array( 'post_type' => 'bp_location', 'post_status' => 'publish' ) ) );
}
