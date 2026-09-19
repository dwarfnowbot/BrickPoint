<?php
/**
 * Front page hero (PHP fallback).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_hero_defaults = brickpoint_hero_defaults();
$bp_hero_video    = bp_get( 'bp_hero_video', '' );
$bp_hero_poster   = bp_get( 'bp_hero_poster', '' );
?>
<section class="bp-hero">
	<div class="bp-container bp-hero-grid">
		<div class="bp-hero-copy">
			<p class="bp-hero-kicker"><?php esc_html_e( 'Masha Allah • Fine Bricks • SS7', 'brickpoint' ); ?></p>
			<h1><?php echo esc_html( $bp_hero_defaults['title'] ); ?></h1>
			<p><?php echo esc_html( $bp_hero_defaults['subtitle'] ); ?></p>
			<p class="bp-hero-cta">
				<a class="btn-brick" href="<?php echo esc_url( get_post_type_archive_link( 'bp_product' ) ); ?>"><?php esc_html_e( 'Explore Products', 'brickpoint' ); ?></a>
				<a class="btn-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request a Quote', 'brickpoint' ); ?></a>
				<a class="btn-whatsapp" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ); ?>"><?php esc_html_e( 'WhatsApp Us', 'brickpoint' ); ?></a>
			</p>
		</div>
		<div class="bp-hero-media">
			<?php if ( $bp_hero_video ) : ?>
				<video class="bp-hero-video" autoplay muted loop playsinline preload="metadata" <?php if ( $bp_hero_poster ) : ?>poster="<?php echo esc_url( $bp_hero_poster ); ?>"<?php endif; ?>>
					<source src="<?php echo esc_url( $bp_hero_video ); ?>" type="video/mp4" />
				</video>
			<?php elseif ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'bp-hero' ); ?>
			<?php endif; ?>
			<div class="bp-ss7-float"><div class="ss7-brick"><strong>SS7</strong><span><?php esc_html_e( 'Flagship Bricks', 'brickpoint' ); ?></span></div></div>
		</div>
	</div>
</section>
