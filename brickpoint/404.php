<?php
/**
 * 404 page.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="bp-section bp-dark" style="min-height:50vh">
	<div class="bp-container bp-center">
		<p class="bp-eyebrow"><?php esc_html_e( 'Error 404', 'brickpoint' ); ?></p>
		<h2><?php esc_html_e( 'This page took a wrong turn on the brick road.', 'brickpoint' ); ?></h2>
		<p class="bp-lead" style="margin:0.8rem auto 1.4rem"><?php esc_html_e( 'The page you are looking for was moved or never existed. Explore our products or talk to us on WhatsApp.', 'brickpoint' ); ?></p>
		<p class="bp-hero-cta" style="justify-content:center">
			<a class="btn-brick" href="<?php echo esc_url( get_post_type_archive_link( 'bp_product' ) ); ?>"><?php esc_html_e( 'Explore Products', 'brickpoint' ); ?></a>
			<a class="btn-whatsapp" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) ) ); ?>"><?php esc_html_e( 'WhatsApp Us', 'brickpoint' ); ?></a>
		</p>
	</div>
</section>
<?php
get_footer();
