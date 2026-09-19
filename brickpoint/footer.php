<?php
/**
 * Footer: renders the Elementor Pro "footer" location when a template is
 * assigned; otherwise falls back to the BrickPoint LM Arena design footer.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>

<footer class="bp-footer">
	<div class="bp-container bp-footer-grid">
		<div class="bp-footer-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="bp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Brick<span>Point</span></a>
			<?php endif; ?>
			<p><?php esc_html_e( 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.', 'brickpoint' ); ?></p>
			<p class="bp-footer-contact">
				<span><?php echo esc_html( bp_phone_display() ); ?></span>
				<span><?php echo esc_html( bp_email() ); ?></span>
			</p>
			<?php get_template_part( 'template-parts/social-links' ); ?>
		</div>
		<div class="bp-footer-col">
			<h4><?php esc_html_e( 'Products', 'brickpoint' ); ?></h4>
			<?php
			$pcats = get_terms( array(
				'taxonomy'   => 'bp_product_category',
				'number'     => 6,
				'hide_empty' => false,
			) );
			if ( $pcats && ! is_wp_error( $pcats ) ) {
				echo '<ul>';
				foreach ( $pcats as $t ) {
					echo '<li><a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</div>
		<div class="bp-footer-col">
			<h4><?php esc_html_e( 'Company', 'brickpoint' ); ?></h4>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'bp-footer-menu',
				'fallback_cb'    => false,
			) );
			?>
		</div>
		<div class="bp-footer-col">
			<h4><?php esc_html_e( 'Get a Quotation', 'brickpoint' ); ?></h4>
			<p><?php esc_html_e( 'Send your material list on WhatsApp for availability and final quotation.', 'brickpoint' ); ?></p>
			<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ); ?>"><?php esc_html_e( 'Chat on WhatsApp', 'brickpoint' ); ?></a>
		</div>
	</div>
	<div class="bp-footer-bottom">
		<div class="bp-container bp-footer-bottom-inner">
			<span><?php echo esc_html( bp_get( 'bp_copyright', '© BrickPoint. All rights reserved.' ) ); ?></span>
			<span class="bp-footer-legal">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'brickpoint' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'brickpoint' ); ?></a>
			</span>
		</div>
	</div>
</footer>

<a class="bp-float-wa" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'brickpoint' ); ?>" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ); ?>">
	<?php echo bp_icon( 'whatsapp', array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</a>

<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
