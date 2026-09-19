<?php
/**
 * Product card (PHP fallback / loops).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_badge  = bp_meta( get_the_ID(), '_bp_badge', '' );
$bp_avail  = bp_meta( get_the_ID(), '_bp_availability', '' );
$bp_price  = bp_meta( get_the_ID(), '_bp_price', '' );
$bp_unit   = bp_meta( get_the_ID(), '_bp_unit', '' );
$bp_cats   = get_the_terms( get_the_ID(), 'bp_product_category' );
$bp_terms  = array();
if ( $bp_cats && ! is_wp_error( $bp_cats ) ) {
	$bp_terms = wp_list_pluck( $bp_cats, 'name' );
}
?>
<article class="bp-card">
	<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
		<?php if ( $bp_badge ) : ?><span class="bp-badge"><?php echo esc_html( $bp_badge ); ?></span><?php endif; ?>
		<?php if ( $bp_avail ) : ?><span class="bp-avail"><?php echo esc_html( $bp_avail ); ?></span><?php endif; ?>
	</a>
	<div class="bp-card-body">
		<?php if ( $bp_terms ) : ?><span class="bp-card-cat"><?php echo esc_html( implode( ' • ', $bp_terms ) ); ?></span><?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( bp_excerpt( 16 ) ); ?></p>
		<?php if ( $bp_price ) : ?>
			<span class="bp-price"><?php echo esc_html( $bp_price ); ?></span>
			<?php if ( $bp_unit ) : ?><span class="bp-unit"><?php echo esc_html( $bp_unit ); ?></span><?php endif; ?>
		<?php endif; ?>
		<div class="bp-card-cta">
			<?php echo bp_whatsapp_button( bp_product_whatsapp_message( get_the_ID() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in function. ?>
			<a class="btn-ghost btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Details', 'brickpoint' ); ?></a>
		</div>
	</div>
</article>
