<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bp_get( $key, $default = '' ) {
  $v = get_theme_mod( $key, $default );
  return $v !== '' ? $v : $default;
}
function bp_phone_display() { return bp_get( 'bp_phone_display', '0315 2850818' ); }
function bp_phone_intl() {
  $p = preg_replace( '/\D/', '', bp_get( 'bp_whatsapp_number', '923152850818' ) );
  return $p ? $p : '923152850818';
}
function bp_email() { return sanitize_email( bp_get( 'bp_email', 'info@brickpoint.pk' ) ); }
function bp_social( $network ) {
  $defaults = array(
    'facebook'  => 'https://www.facebook.com/brickpoint.pk/',
    'instagram' => 'https://www.instagram.com/brickpoint.pk/',
    'twitter'   => 'https://x.com/BrickPointPK',
    'tiktok'    => 'https://www.tiktok.com/@brickpoint.pk/',
  );
  return esc_url( bp_get( 'bp_social_' . $network, isset( $defaults[ $network ] ) ? $defaults[ $network ] : '' ) );
}
function bp_meta( $post_id, $key, $default = '' ) {
  $v = get_post_meta( $post_id, $key, true );
  return ( $v === '' || $v === null ) ? $default : $v;
}
function bp_term_image( $term_id, $key = 'bp_cat_image' ) {
  return esc_url( get_term_meta( $term_id, $key, true ) );
}
function bp_breadcrumbs( $sep = ' / ' ) {
  $items = array( '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'brickpoint' ) . '</a>' );
  if ( is_singular( 'bp_product' ) ) {
    $items[] = '<a href="' . esc_url( get_post_type_archive_link( 'bp_product' ) ) . '">' . esc_html__( 'Products', 'brickpoint' ) . '</a>';
    $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
  } elseif ( is_singular( 'bp_video' ) ) {
    $items[] = '<a href="' . esc_url( get_post_type_archive_link( 'bp_video' ) ) . '">' . esc_html__( 'Videos', 'brickpoint' ) . '</a>';
    $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
  } elseif ( is_singular( 'post' ) ) {
    $items[] = '<a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'brickpoint' ) . '</a>';
    $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
  } elseif ( is_archive() ) {
    $items[] = '<span>' . esc_html( get_the_archive_title() ) . '</span>';
  } elseif ( is_search() ) {
    $items[] = '<span>' . sprintf( esc_html__( 'Search: %s', 'brickpoint' ), esc_html( get_search_query() ) ) . '</span>';
  } else {
    $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
  }
  return '<nav class="bp-breadcrumbs" aria-label="Breadcrumb">' . wp_kses_post( implode( esc_html( $sep ), $items ) ) . '</nav>';
}
/**
 * Inline an SVG icon from assets/icons/.
 *
 * The markup is theme-owned, so it is echoed after a targeted kses pass that
 * allows SVG tags only. Inline icons keep working in Safari (external <use>
 * references are not supported there) and stay styleable via currentColor.
 *
 * @param string $name  Icon file name without extension.
 * @param array  $attrs Extra attributes for the root <svg> tag (e.g. width, fill).
 * @return string SVG markup.
 */
function bp_icon( $name, $attrs = array() ) {
  static $allowed = null;
  $name = preg_replace( '/[^a-z0-9-_]/', '', (string) $name );
  if ( '' === $name ) {
    return '';
  }
  $file = BRICKPOINT_DIR . '/assets/icons/' . $name . '.svg';
  if ( ! file_exists( $file ) ) {
    return '';
  }
  if ( null === $allowed ) {
    $allowed = array(
      'svg'  => array( 'xmlns' => true, 'viewbox' => true, 'fill' => true, 'width' => true, 'height' => true, 'aria-hidden' => true, 'class' => true, 'style' => true, 'focusable' => true ),
      'path' => array( 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ),
      'g'    => array( 'fill' => true, 'transform' => true ),
      'circle' => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true ),
      'rect' => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'fill' => true ),
    );
  }
  $svg = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
  $svg = trim( (string) $svg );
  // Normalize: ensure viewBox so inline icons scale with width/height attrs.
  if ( false === stripos( $svg, 'viewbox' ) ) {
    $svg = preg_replace( '/<svg\b/i', '<svg viewBox="0 0 24 24"', $svg, 1 );
  }
  if ( $attrs ) {
    $attrs_str = '';
    foreach ( $attrs as $attr => $val ) {
      if ( preg_match( '/<(svg\b[^>]*?)\b' . $attr . '="/i', $svg ) ) {
        continue; // Respect the file's own attribute.
      }
      $attrs_str .= ' ' . $attr . '="' . esc_attr( (string) $val ) . '"';
    }
    if ( $attrs_str ) {
      $svg = preg_replace( '/<svg\b/i', '<svg' . $attrs_str, $svg, 1 );
    }
  }
  return wp_kses( $svg, $allowed );
}

function bp_excerpt( $length = 24 ) {
  $text = get_the_excerpt() ? get_the_excerpt() : get_the_content();
  return wp_trim_words( wp_strip_all_tags( $text ), absint( $length ), '…' );
}
