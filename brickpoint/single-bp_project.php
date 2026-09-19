<?php
/**
 * Single project.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$bp_loc   = bp_meta( get_the_ID(), '_bpp_location', '' );
	$bp_link  = bp_meta( get_the_ID(), '_bpp_link', '' );
	$bp_stat  = bp_meta( get_the_ID(), '_bpp_status', __( 'Illustrative construction reference', 'brickpoint' ) );
	$bp_ill   = bp_meta( get_the_ID(), '_bpp_illustrative', '1' );
	?>
	<section class="bp-pagehead">
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( $bp_loc ) : ?><p><?php echo esc_html( '📍 ' . $bp_loc ); ?></p><?php endif; ?>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container bp-entry bp-narrow">
			<?php if ( '1' === $bp_ill ) : ?>
				<p class="bp-notice"><?php esc_html_e( 'Illustrative reference image — shown to communicate the type of construction this material supports.', 'brickpoint' ); ?></p>
			<?php endif; ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
			<?php endif; ?>
			<div class="bp-entry"><?php the_content(); ?></div>
			<div class="bp-box">
				<h3><?php esc_html_e( 'Project details', 'brickpoint' ); ?></h3>
				<dl>
					<?php if ( $bp_loc ) : ?><div><dt><?php esc_html_e( 'Location', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $bp_loc ); ?></dd></div><?php endif; ?>
					<?php if ( $bp_stat ) : ?><div><dt><?php esc_html_e( 'Status', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $bp_stat ); ?></dd></div><?php endif; ?>
				</dl>
				<div class="bp-card-cta">
					<a class="btn-ghost btn-sm" href="<?php echo esc_url( get_post_type_archive_link( 'bp_project' ) ); ?>">← <?php esc_html_e( 'All Projects', 'brickpoint' ); ?></a>
					<?php if ( $bp_link ) : ?><a class="btn-brick btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $bp_link ); ?>"><?php esc_html_e( 'Project Link', 'brickpoint' ); ?></a><?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
