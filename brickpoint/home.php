<?php
/**
 * Blog posts index (fallback when no Elementor blog template is assigned).
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
		<h1><?php echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'BrickPoint Blog', 'brickpoint' ) ); ?></h1>
		<p><?php esc_html_e( 'Guides, quality notes and company updates from the BrickPoint yards.', 'brickpoint' ); ?></p>
	</div>
</section>
<section class="bp-section">
	<div class="bp-container">
		<?php if ( have_posts() ) : ?>
			<div class="bp-grid cols-3 bp-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content' );
				endwhile;
				?>
			</div>
			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<p class="bp-lead"><?php esc_html_e( 'No blog posts yet.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
