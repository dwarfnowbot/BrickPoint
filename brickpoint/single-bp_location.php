<?php
/**
 * Single location (bhatta).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$bp_address = bp_meta( get_the_ID(), '_bpl_address', '' );
	$bp_maps    = bp_meta( get_the_ID(), '_bpl_maps', '' );
	$bp_phone   = bp_meta( get_the_ID(), '_bpl_phone', bp_phone_display() );
	$bp_hours   = bp_meta( get_the_ID(), '_bpl_hours', '' );
	?>
	<section class="bp-pagehead">
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container bp-entry bp-narrow">
			<?php if ( has_post_thumbnail() ) : ?>
				<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
			<?php endif; ?>
			<?php the_content(); ?>
			<div class="bp-box">
				<?php if ( $bp_address ) : ?><p>📍 <?php echo esc_html( $bp_address ); ?></p><?php endif; ?>
				<?php if ( $bp_hours ) : ?><p>🕐 <?php echo esc_html( $bp_hours ); ?></p><?php endif; ?>
				<?php if ( $bp_phone ) : ?><p>📞 <a href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', $bp_phone ) ); ?>"><?php echo esc_html( $bp_phone ); ?></a></p><?php endif; ?>
				<?php if ( $bp_maps ) : ?><p><a class="btn-ghost btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $bp_maps ); ?>"><?php esc_html_e( 'Get Directions', 'brickpoint' ); ?></a></p><?php endif; ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
