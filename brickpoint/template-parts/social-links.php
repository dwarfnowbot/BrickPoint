<?php
/**
 * Social links row (editable defaults from the Customizer).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_networks = array(
	'facebook'  => array( __( 'Facebook', 'brickpoint' ), 'facebook' ),
	'instagram' => array( __( 'Instagram', 'brickpoint' ), 'instagram' ),
	'twitter'   => array( __( 'X / Twitter', 'brickpoint' ), 'x-twitter' ),
	'tiktok'    => array( __( 'TikTok', 'brickpoint' ), 'tiktok' ),
);
?>
<div class="bp-social">
	<?php foreach ( $bp_networks as $bp_net => $bp_data ) : ?>
		<?php $bp_url = bp_social( $bp_net ); ?>
		<?php if ( $bp_url ) : ?>
			<a href="<?php echo esc_url( $bp_url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $bp_data[0] ); ?>">
				<?php echo bp_icon( $bp_data[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
