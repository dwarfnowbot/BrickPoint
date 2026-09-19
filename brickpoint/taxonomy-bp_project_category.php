<?php
/**
 * Project category archive.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$bp_term  = get_queried_object();
$bp_img   = get_term_meta( $bp_term->term_id, 'bp_cat_image', true );
$bp_desc  = get_term_meta( $bp_term->term_id, 'bp_cat_desc', true );
?>
<section class="bp-pagehead<?php echo $bp_img ? ' bp-pagehead-img" style="background-image:url(' . esc_url( $bp_img ) . '"' : ''; ?>">
	<div class="bp-container">
		<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
		<h1><?php echo esc_html( $bp_term->name ); ?></h1>
		<p><?php echo esc_html( $bp_desc ? $bp_desc : wp_strip_all_tags( get_the_archive_description() ) ); ?></p>
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
			<p class="bp-lead"><?php esc_html_e( 'No projects in this category yet.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<section class="bp-section bp-sand">
	<div class="bp-container bp-center">
		<h2><?php esc_html_e( 'Planning a construction project?', 'brickpoint' ); ?></h2>
		<p class="bp-lead" style="margin:0.6rem auto 1.2rem"><?php esc_html_e( 'Tell us the scope — we supply consistent, tested bricks and materials for housing schemes, plazas and boundary walls.', 'brickpoint' ); ?></p>
		<?php echo bp_whatsapp_button( __( 'Assalam-o-Alaikum BrickPoint, I am planning a construction project and need a material supply quotation.', 'brickpoint' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
<?php
get_footer();
