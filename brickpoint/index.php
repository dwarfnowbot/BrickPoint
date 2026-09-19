<?php
/**
 * Fallback template.
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
		<h1><?php echo esc_html( get_the_archive_title() ); ?></h1>
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
			<p class="bp-lead"><?php esc_html_e( 'Nothing found. Please check back soon.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
