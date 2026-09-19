<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bp_video_source( $post_id ) {
  $s = bp_meta( $post_id, '_bpv_source', 'mp4' );
  return in_array( $s, array( 'mp4', 'youtube', 'vimeo', 'external' ), true ) ? $s : 'mp4';
}
function bp_video_embed_html( $post_id, $args = array() ) {
  $source = bp_video_source( $post_id );
  $url = bp_meta( $post_id, '_bpv_url', '' );
  $file = bp_meta( $post_id, '_bpv_file', '' );
  $poster = get_the_post_thumbnail_url( $post_id, 'bp-hero' );
  $a = wp_parse_args( $args, array( 'autoplay' => false, 'muted' => true, 'loop' => false, 'controls' => true ) );
  if ( $source === 'youtube' || $source === 'vimeo' ) {
    if ( ! $url ) { return ''; }
    $src = esc_url( $url );
    if ( strpos( $src, 'youtube.com/watch' ) !== false ) {
      parse_str( (string) wp_parse_url( $src, PHP_URL_QUERY ), $qv );
      if ( ! empty( $qv['v'] ) ) { $src = 'https://www.youtube.com/embed/' . sanitize_text_field( $qv['v'] ); }
    } elseif ( strpos( $src, 'youtu.be/' ) !== false ) {
      $code = trim( (string) wp_parse_url( $src, PHP_URL_PATH ), '/' );
      $src = 'https://www.youtube.com/embed/' . sanitize_text_field( $code );
    } elseif ( $source === 'vimeo' && strpos( $src, 'player.vimeo' ) === false ) {
      $parts = explode( '/', trim( (string) wp_parse_url( $src, PHP_URL_PATH ), '/' ) );
      $code = end( $parts );
      if ( is_numeric( $code ) ) { $src = 'https://player.vimeo.com/video/' . $code; }
    }
    return '<div class="bp-video-embed"><iframe src="' . esc_url( $src ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '" loading="lazy" allow="accelerometer; autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe></div>';
  }
  $src = $file ? $file : $url;
  if ( ! $src ) { return ''; }
  $attrs = 'playsinline preload="metadata"';
  if ( $a['controls'] ) { $attrs .= ' controls'; }
  if ( $a['autoplay'] ) { $attrs .= ' autoplay muted'; }
  elseif ( $a['muted'] ) { $attrs .= ' muted'; }
  if ( $a['loop'] ) { $attrs .= ' loop'; }
  if ( $poster ) { $attrs .= ' poster="' . esc_url( $poster ) . '"'; }
  return '<video class="bp-video-player" ' . $attrs . '><source src="' . esc_url( $src ) . '" type="video/mp4" /></video>';
}
function bp_video_thumbnail_first( $post_id ) {
  // Lightbox-style: thumbnail + play button, loads player on click for performance.
  $thumb = get_the_post_thumbnail_url( $post_id, 'bp-card' );
  $title = get_the_title( $post_id );
  $url = get_permalink( $post_id );
  ob_start(); ?>
  <a class="bp-video-thumb" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Watch %s', 'brickpoint' ), $title ) ); ?>">
    <?php if ( $thumb ) : ?>
      <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
    <?php else : ?>
      <span class="bp-video-thumb-fallback"></span>
    <?php endif; ?>
    <span class="bp-play-btn" aria-hidden="true">▶</span>
  </a>
  <?php
  return ob_get_clean();
}
