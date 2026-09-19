<?php
/**
 * Single video.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$bp_url = bp_video_embed_html( get_the_ID() );
	?>
	<section class="bp-pagehead">
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="bp-section">
		<div class="bp-container bp-narrow">
			<?php if ( $bp_url ) : ?>
				<?php echo $bp_url; // phpcs:ignore WordPress.Security.EscapeOutput -- built with esc_url internally; kses strips <source>. ?>
			<?php elseif ( has_post_thumbnail() ) : ?>
				<p><?php the_post_thumbnail( 'bp-video', array( 'class' => 'bp-single-img' ) ); ?></p>
			<?php endif; ?>
			<div class="bp-entry"><?php the_content(); ?></div>
			<p><a class="btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'bp_video' ) ); ?>">← <?php esc_html_e( 'All Videos', 'brickpoint' ); ?></a></p>
		</div>
	</section>
	<?php
endwhile;

get_footer();
