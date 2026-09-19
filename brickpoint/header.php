<?php
/**
 * Header: renders the Elementor Pro "header" location when a template is
 * assigned; otherwise falls back to the BrickPoint LM Arena design header.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>

<div class="bp-topbar">
	<div class="bp-container bp-topbar-inner">
		<a class="bp-topbar-phone" href="tel:+<?php echo esc_attr( bp_phone_intl() ); ?>">
			<?php echo bp_icon( 'phone', array( 'width' => 12, 'height' => 12, 'fill' => '#ea580c', 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo esc_html( bp_phone_display() ); ?>
		</a>
		<span class="bp-topbar-units"><?php esc_html_e( 'Masha Allah Bricks Co. • Fine Bricks Co. • SS7 Bricks', 'brickpoint' ); ?></span>
		<span class="bp-topbar-links">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'bp_location' ) ); ?>"><?php esc_html_e( 'Our Bhattas', 'brickpoint' ); ?></a>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'bp_video' ) ); ?>"><?php esc_html_e( 'Videos', 'brickpoint' ); ?></a>
		</span>
	</div>
</div>

<header class="bp-header" id="bpHeader">
	<div class="bp-container bp-header-inner">
		<div class="bp-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="bp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Brick<span>Point</span></a>
			<?php endif; ?>
		</div>
		<nav class="bp-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'brickpoint' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'bp-menu',
				'fallback_cb'    => false,
			) );
			?>
		</nav>
		<div class="bp-header-cta">
			<a class="bp-header-phone" href="tel:+<?php echo esc_attr( bp_phone_intl() ); ?>"><?php echo esc_html( bp_phone_display() ); ?></a>
			<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ); ?>"><?php esc_html_e( 'WhatsApp Us', 'brickpoint' ); ?></a>
			<a class="btn-brick btn-sm" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request Quote', 'brickpoint' ); ?></a>
		</div>
		<button class="bp-menu-toggle" id="bpMenuToggle" aria-label="<?php esc_attr_e( 'Open menu', 'brickpoint' ); ?>" aria-expanded="false" aria-controls="bpMobileMenu">☰</button>
	</div>
	<div class="bp-mobile-menu" id="bpMobileMenu" hidden>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'mobile',
			'container'      => false,
			'menu_class'     => 'bp-menu-mobile',
			'fallback_cb'    => false,
		) );
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'bp-menu-mobile',
			'fallback_cb'    => false,
		) );
		?>
		<div class="bp-mobile-cta">
			<a class="btn-whatsapp btn-block" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ); ?>"><?php esc_html_e( 'WhatsApp Us', 'brickpoint' ); ?></a>
		</div>
	</div>
</header>

<?php endif; ?>

<main id="main" class="bp-main">
