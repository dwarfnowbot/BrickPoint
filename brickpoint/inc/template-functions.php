<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function brickpoint_posted_meta() {
  printf(
    '<div class="entry-meta"><span class="byline">%1$s</span> <span class="posted-on">%2$s</span></div>',
    esc_html( get_the_author() ),
    esc_html( get_the_date() )
  );
}
function brickpoint_pagination() {
  the_posts_pagination( array(
    'mid_size'  => 2,
    'prev_text' => __( '← Previous', 'brickpoint' ),
    'next_text' => __( 'Next →', 'brickpoint' ),
  ) );
}
function brickpoint_related_posts( $count = 3 ) {
  $cats = wp_get_post_categories( get_the_ID() );
  if ( empty( $cats ) ) { return; }
  $q = new WP_Query( array(
    'category__in' => $cats, 'posts_per_page' => absint( $count ),
    'post__not_in' => array( get_the_ID() ), 'ignore_sticky_posts' => true,
  ) );
  if ( $q->have_posts() ) {
    echo '<section class="related-posts"><h2>' . esc_html__( 'Related Articles', 'brickpoint' ) . '</h2><div class="bp-grid cols-3">';
    while ( $q->have_posts() ) { $q->the_post(); get_template_part( 'template-parts/content' ); }
    echo '</div></section>';
  }
  wp_reset_postdata();
}
function brickpoint_hero_defaults() {
  return array(
    'title'    => __( 'Building Strength. Delivering Quality. Shaping Tomorrow.', 'brickpoint' ),
    'subtitle' => __( 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.', 'brickpoint' ),
  );
}
