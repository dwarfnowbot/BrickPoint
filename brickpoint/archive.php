<?php
/**
 * Generic archive template.
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
		<?php
		$bp_desc = get_the_archive_description();
		if ( $bp_desc ) {
			echo '<div class="bp-lead">' . wp_kses_post( $bp_desc ) . '</div>';
		}
		?>
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
			<p class="bp-lead"><?php esc_html_e( 'No posts found in this archive yet.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
