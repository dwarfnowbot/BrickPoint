<?php
/**
 * Locations archive (Our Bhattas).
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
		<h1><?php esc_html_e( 'Our Bhattas (Brick Yards)', 'brickpoint' ); ?></h1>
		<p><?php esc_html_e( 'Visit a BrickPoint yard near you — call ahead and we will keep your stock ready.', 'brickpoint' ); ?></p>
	</div>
</section>
<section class="bp-section">
	<div class="bp-container">
		<?php if ( have_posts() ) : ?>
			<div class="bp-grid cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/location-card' );
				endwhile;
				?>
			</div>
			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<p class="bp-lead"><?php esc_html_e( 'Yard locations are being updated.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
