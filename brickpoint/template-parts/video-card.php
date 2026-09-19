<?php
/**
 * Video card (PHP fallback / loops).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_duration = bp_meta( get_the_ID(), '_bpv_duration', '' );
$bp_featured = bp_meta( get_the_ID(), '_bpv_featured', '0' );
$bp_cats     = get_the_terms( get_the_ID(), 'bp_video_category' );
?>
<article class="bp-card">
	<a class="bp-video-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'bp-video', array( 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
		<span class="bp-play-btn" aria-hidden="true"><?php echo bp_icon( 'play', array( 'width' => 20, 'height' => 20, 'fill' => 'currentColor' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php if ( $bp_duration ) : ?><span class="bp-duration"><?php echo esc_html( $bp_duration ); ?></span><?php endif; ?>
		<?php if ( '1' === $bp_featured ) : ?><span class="bp-feat"><?php esc_html_e( 'Featured', 'brickpoint' ); ?></span><?php endif; ?>
	</a>
	<div class="bp-card-body">
		<?php if ( $bp_cats && ! is_wp_error( $bp_cats ) ) : ?><span class="bp-card-cat"><?php echo esc_html( $bp_cats[0]->name ); ?></span><?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( bp_excerpt( 18 ) ); ?></p>
	</div>
</article>
