<?php
/**
 * Location card (PHP fallback / loops).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_address  = bp_meta( get_the_ID(), '_bpl_address', '' );
$bp_maps     = bp_meta( get_the_ID(), '_bpl_maps', '' );
$bp_phone    = bp_meta( get_the_ID(), '_bpl_phone', bp_phone_display() );
$bp_hours    = bp_meta( get_the_ID(), '_bpl_hours', '' );
$bp_wa       = bp_meta( get_the_ID(), '_bpl_whatsapp', bp_phone_intl() );
?>
<article class="bp-loc-card reveal">
	<div class="bp-loc-media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'bp-card-wide', array( 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
	</div>
	<div class="bp-loc-body">
		<h3><?php the_title(); ?></h3>
		<?php if ( $bp_address ) : ?><p><?php echo esc_html( $bp_address ); ?></p><?php endif; ?>
		<?php if ( $bp_hours ) : ?><p><?php echo esc_html( $bp_hours ); ?></p><?php endif; ?>
		<?php if ( $bp_phone ) : ?><p>📞 <a href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', $bp_phone ) ); ?>"><?php echo esc_html( $bp_phone ); ?></a></p><?php endif; ?>
		<div class="bp-loc-actions">
			<?php if ( $bp_maps ) : ?><a class="btn-ghost btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $bp_maps ); ?>"><?php esc_html_e( 'Get Directions', 'brickpoint' ); ?></a><?php endif; ?>
			<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( sprintf( /* translators: %s: location name */ __( 'Assalam-o-Alaikum BrickPoint, I need bricks delivered from %s.', 'brickpoint' ), get_the_title() ), $bp_wa ) ); ?>"><?php esc_html_e( 'WhatsApp', 'brickpoint' ); ?></a>
		</div>
	</div>
</article>
