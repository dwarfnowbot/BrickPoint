<?php
/**
 * Default page template.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// Elementor-built pages render full-bleed: no PHP page head or wrappers.
	if ( bp_is_elementor_built( get_the_ID() ) ) {
		the_content();
		continue;
	}
	?>
	<section class="bp-pagehead<?php echo has_post_thumbnail() ? ' bp-pagehead-img' : ''; ?>">
		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-pagehead-bg' ) ); endif; ?>
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container bp-entry">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
