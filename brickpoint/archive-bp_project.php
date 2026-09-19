<?php
/**
 * Projects archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="bp-pagehead">
	<div class="bp-container">
		<h1><?php esc_html_e( 'Projects & Construction References', 'brickpoint' ); ?></h1>
		<p><?php esc_html_e( 'Housing schemes, plazas and boundary walls built with BrickPoint materials.', 'brickpoint' ); ?></p>
		<div class="bp-filters">
			<?php
			$bp_terms = get_terms( array( 'taxonomy' => 'bp_project_category', 'hide_empty' => false ) );
			if ( $bp_terms && ! is_wp_error( $bp_terms ) ) {
				foreach ( $bp_terms as $bp_t ) {
					echo '<a href="' . esc_url( get_term_link( $bp_t ) ) . '">' . esc_html( $bp_t->name ) . '</a>';
				}
			}
			?>
		</div>
	</div>
</section>
<section class="bp-section">
	<div class="bp-container">
		<?php if ( have_posts() ) : ?>
			<div class="bp-grid cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/project-card' );
				endwhile;
				?>
			</div>
			<?php brickpoint_pagination(); ?>
		<?php else : ?>
			<p class="bp-lead"><?php esc_html_e( 'Project showcases are being prepared.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
