<?php
/**
 * Single post (blog).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="bp-pagehead">
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
			<?php brickpoint_posted_meta(); ?>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container bp-entry bp-narrow">
			<?php if ( has_post_thumbnail() ) : ?>
				<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
			<?php endif; ?>
			<?php the_content(); ?>
			<?php if ( has_tag() ) : ?>
				<p class="bp-tags"><?php the_tags( '🏷 ', ', ' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<section class="bp-section bp-section-tint">
		<div class="bp-container">
			<div class="bp-sec-head">
				<p class="bp-eyebrow"><?php esc_html_e( 'Keep Reading', 'brickpoint' ); ?></p>
				<h2><?php esc_html_e( 'Related Articles', 'brickpoint' ); ?></h2>
			</div>
			<div class="bp-grid cols-3">
				<?php
				$bp_related = new WP_Query( array(
					'post_type'           => 'post',
					'posts_per_page'      => 3,
					'post__not_in'        => array( get_the_ID() ),
					'category__in'        => wp_get_post_categories( get_the_ID() ),
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				) );
				if ( ! $bp_related->have_posts() ) {
					$bp_related = new WP_Query( array(
						'post_type'           => 'post',
						'posts_per_page'      => 3,
						'post__not_in'        => array( get_the_ID() ),
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					) );
				}
				while ( $bp_related->have_posts() ) :
					$bp_related->the_post();
					get_template_part( 'template-parts/content' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
