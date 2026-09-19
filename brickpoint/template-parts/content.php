<?php
/**
 * Blog card (PHP fallback / loops).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="bp-card">
	<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
	</a>
	<div class="bp-card-body">
		<span class="bp-card-cat"><?php echo esc_html( get_the_date() ); ?></span>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( bp_excerpt( 18 ) ); ?></p>
		<div class="bp-card-cta">
			<a class="btn-ghost btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'brickpoint' ); ?></a>
		</div>
	</div>
</article>
