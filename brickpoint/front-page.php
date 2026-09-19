<?php
/**
 * Front page: if the Elementor homepage is built, page.php content wins.
 * This PHP template mirrors the LM Arena homepage as a fallback.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// When the homepage has been built with Elementor (demo import), its content
// owns the page completely — render it without the PHP fallback sections.
if ( have_posts() ) :
	the_post();
	if ( bp_is_elementor_built( get_the_ID() ) ) {
		the_content();
		get_footer();
		return;
	}
	rewind_posts();
endif;

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
	get_template_part( 'template-parts/hero' );
	?>
	<section class="bp-section">
		<div class="bp-container">
			<p class="bp-eyebrow"><?php esc_html_e( 'Why BrickPoint', 'brickpoint' ); ?></p>
			<h2><?php esc_html_e( 'A construction-materials partner you can build on', 'brickpoint' ); ?></h2>
			<div class="bp-grid cols-4 bp-mt">
				<?php
				$bp_cats = get_terms( array(
					'taxonomy'   => 'bp_product_category',
					'number'     => 8,
					'hide_empty' => false,
				) );
				if ( $bp_cats && ! is_wp_error( $bp_cats ) ) :
					foreach ( $bp_cats as $bp_t ) :
						$bp_img = get_term_meta( $bp_t->term_id, 'bp_cat_image', true );
						?>
						<a class="bp-cat-card" href="<?php echo esc_url( get_term_link( $bp_t ) ); ?>">
							<?php if ( $bp_img ) : ?><img src="<?php echo esc_url( $bp_img ); ?>" alt="<?php echo esc_attr( $bp_t->name ); ?>" loading="lazy" /><?php endif; ?>
							<span class="bp-cat-card-body"><strong><?php echo esc_html( $bp_t->name ); ?></strong></span>
						</a>
						<?php
					endforeach;
				endif;
				?>
			</div>
		</div>
	</section>
	<section class="bp-section bp-dark">
		<div class="bp-container">
			<p class="bp-eyebrow"><?php esc_html_e( 'Featured Products', 'brickpoint' ); ?></p>
			<h2><?php esc_html_e( 'Materials contractors ask for by name', 'brickpoint' ); ?></h2>
			<div class="bp-grid cols-4 bp-mt">
				<?php
				$bp_q = new WP_Query( array(
					'post_type'      => 'bp_product',
					'posts_per_page' => 8,
					'meta_key'       => '_bp_featured',
					'meta_value'     => '1',
				) );
				if ( ! $bp_q->have_posts() ) {
					$bp_q = new WP_Query( array( 'post_type' => 'bp_product', 'posts_per_page' => 8 ) );
				}
				while ( $bp_q->have_posts() ) :
					$bp_q->the_post();
					get_template_part( 'template-parts/product-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<p class="bp-center bp-mt"><a class="btn-brick" href="<?php echo esc_url( get_post_type_archive_link( 'bp_product' ) ); ?>"><?php esc_html_e( 'Browse All Products', 'brickpoint' ); ?></a></p>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container">
			<p class="bp-eyebrow"><?php esc_html_e( 'Inside BrickPoint', 'brickpoint' ); ?></p>
			<h2><?php esc_html_e( 'See the Strength Behind Every Brick', 'brickpoint' ); ?></h2>
			<div class="bp-grid cols-3 bp-mt">
				<?php
				$bp_v = new WP_Query( array( 'post_type' => 'bp_video', 'posts_per_page' => 3 ) );
				while ( $bp_v->have_posts() ) :
					$bp_v->the_post();
					get_template_part( 'template-parts/video-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<p class="bp-center bp-mt"><a class="btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'bp_video' ) ); ?>"><?php esc_html_e( 'View All Videos', 'brickpoint' ); ?></a></p>
		</div>
	</section>
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	endif;
endif;

get_footer();
