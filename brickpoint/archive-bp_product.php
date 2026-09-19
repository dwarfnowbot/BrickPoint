<?php
/**
 * Products archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$bp_banner = get_theme_mod( 'bp_products_banner', '' );
?>
<section class="bp-pagehead<?php echo $bp_banner ? ' bp-pagehead-img" style="background-image:url(' . esc_url( $bp_banner ) . '"' : ''; ?>">
	<div class="bp-container">
		<h1><?php esc_html_e( 'Construction Materials & Bricks', 'brickpoint' ); ?></h1>
		<p><?php esc_html_e( 'Bricks, blocks, crush, sand and everything your site needs — quality-checked at the yard and delivered across the city.', 'brickpoint' ); ?></p>
		<div class="bp-filters">
			<?php
			$bp_terms = get_terms( array( 'taxonomy' => 'bp_product_category', 'hide_empty' => false ) );
			if ( $bp_terms && ! is_wp_error( $bp_terms ) ) {
				foreach ( $bp_terms as $bp_t ) {
					echo '<a href="' . esc_url( get_term_link( $bp_t ) ) . '">' . esc_html( $bp_t->name ) . '</a>';
				}
			}
			?>
		</div>
	</div>
</section>
<section class="bp-section">
	<div class="bp-container">
		<?php if ( have_posts() ) : ?>
			<div class="bp-grid cols-4">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/product-card' );
				endwhile;
				?>
			</div>
			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<p class="bp-lead"><?php esc_html_e( 'Products are being restocked. Please check back or ask us on WhatsApp.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
