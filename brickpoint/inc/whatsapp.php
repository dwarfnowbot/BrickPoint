<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bp_whatsapp_url( $message, $phone = null ) {
  if ( $phone === null ) { $phone = bp_phone_intl(); }
  return 'https://wa.me/' . preg_replace( '/\D/', '', $phone ) . '?text=' . rawurlencode( $message );
}
function bp_product_whatsapp_message( $post_id ) {
  $custom = bp_meta( $post_id, '_bp_whatsapp', '' );
  if ( $custom ) { return $custom; }
  $cats = get_the_terms( $post_id, 'bp_product_category' );
  $cat = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '-';
  $price = bp_meta( $post_id, '_bp_price', '' );
  if ( ! $price ) { $price = __( 'Please quote', 'brickpoint' ); }
  $unit = bp_meta( $post_id, '_bp_unit', '-' );
  $lines = array(
    __( 'Assalam-o-Alaikum BrickPoint,', 'brickpoint' ), '',
    __( 'I am interested in the following product:', 'brickpoint' ), '',
    sprintf( __( 'Product: %s', 'brickpoint' ), get_the_title( $post_id ) ),
    sprintf( __( 'Category: %s', 'brickpoint' ), $cat ),
    sprintf( __( 'Price: %s', 'brickpoint' ), $price ),
    sprintf( __( 'Unit: %s', 'brickpoint' ), $unit ), '',
    __( 'Please share availability, delivery details, and final quotation.', 'brickpoint' ), '',
    __( 'Thank you.', 'brickpoint' ),
  );
  return implode( "\n", $lines );
}
function bp_category_whatsapp_message( $cat_name ) {
  return implode( "\n", array(
    __( 'Assalam-o-Alaikum BrickPoint,', 'brickpoint' ), '',
    sprintf( __( 'I want a quotation for: %s', 'brickpoint' ), $cat_name ), '',
    __( 'Please share price, availability and delivery details.', 'brickpoint' ), '',
    __( 'Thank you.', 'brickpoint' ),
  ) );
}
function bp_whatsapp_button( $message, $label = null, $class = 'btn-whatsapp' ) {
  if ( $label === null ) { $label = __( 'Order on WhatsApp', 'brickpoint' ); }
  return '<a class="' . esc_attr( $class ) . '" target="_blank" rel="noopener" href="' . esc_url( bp_whatsapp_url( $message ) ) . '">' . esc_html( $label ) . '</a>';
}
