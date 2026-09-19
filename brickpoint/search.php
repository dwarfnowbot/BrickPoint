<?php
/**
 * Search results.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="bp-pagehead">
	<div class="bp-container">
		<h1>
			<?php
			/* translators: %s: search query. */
			echo esc_html( sprintf( __( 'Search: %s', 'brickpoint' ), get_search_query() ) );
			?>
		</h1>
	</div>
</section>
<section class="bp-section">
	<div class="bp-container">
		<?php if ( have_posts() ) : ?>
			<div class="bp-grid cols-3 bp-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'bp_product' === get_post_type() ) {
						get_template_part( 'template-parts/product-card' );
					} elseif ( 'bp_video' === get_post_type() ) {
						get_template_part( 'template-parts/video-card' );
					} elseif ( 'bp_project' === get_post_type() ) {
						get_template_part( 'template-parts/project-card' );
					} else {
						get_template_part( 'template-parts/content' );
					}
				endwhile;
				?>
			</div>
			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<p class="bp-lead"><?php esc_html_e( 'No results found. Try another search.', 'brickpoint' ); ?></p>
			<p><?php get_search_form(); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
