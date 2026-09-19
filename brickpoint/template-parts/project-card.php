<?php
/**
 * Project card (PHP fallback / loops).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bp_loc         = bp_meta( get_the_ID(), '_bpp_location', '' );
$bp_illustrated = bp_meta( get_the_ID(), '_bpp_illustrative', '1' );
$bp_cats        = get_the_terms( get_the_ID(), 'bp_project_category' );
?>
<article class="bp-card">
	<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
		<?php endif; ?>
		<?php if ( '1' === $bp_illustrated ) : ?><span class="bp-illus"><?php esc_html_e( 'Illustrative', 'brickpoint' ); ?></span><?php endif; ?>
	</a>
	<div class="bp-card-body">
		<?php if ( $bp_cats && ! is_wp_error( $bp_cats ) ) : ?><span class="bp-card-cat"><?php echo esc_html( $bp_cats[0]->name ); ?></span><?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $bp_loc ) : ?><p>📍 <?php echo esc_html( $bp_loc ); ?></p><?php endif; ?>
		<p><?php echo esc_html( bp_excerpt( 16 ) ); ?></p>
		<div class="bp-card-cta">
			<a class="btn-ghost btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Project', 'brickpoint' ); ?></a>
		</div>
	</div>
</article>
