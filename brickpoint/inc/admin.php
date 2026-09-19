<?php
/**
 * Admin UX: columns, notices, recommendations.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_admin_columns_product( $cols ) {
	$cols['bp_price']    = __( 'Price', 'brickpoint' );
	$cols['bp_featured'] = __( 'Featured', 'brickpoint' );
	return $cols;
}
add_filter( 'manage_bp_product_posts_columns', 'brickpoint_admin_columns_product' );

function brickpoint_admin_column_product( $col, $post_id ) {
	if ( 'bp_price' === $col ) {
		echo esc_html( bp_meta( $post_id, '_bp_price', '—' ) );
	}
	if ( 'bp_featured' === $col ) {
		echo bp_meta( $post_id, '_bp_featured', '0' ) === '1' ? '★' : '—'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'manage_bp_product_posts_custom_column', 'brickpoint_admin_column_product', 10, 2 );

function brickpoint_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'bp_demo_done' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_brickpoint-demo' === $screen->id ) {
		return;
	}
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong><?php esc_html_e( 'BrickPoint:', 'brickpoint' ); ?></strong>
			<?php esc_html_e( 'Recreate the complete BrickPoint website (pages, products, videos, projects, locations, blog, menus and Elementor templates) with one click.', 'brickpoint' ); ?>
			<a class="button button-small button-primary" style="margin-left:6px" href="<?php echo esc_url( admin_url( 'themes.php?page=brickpoint-demo' ) ); ?>"><?php esc_html_e( 'Import Demo', 'brickpoint' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'brickpoint_admin_notice' );

// Legacy seed flag for older versions of the theme data.
function brickpoint_seed_terms() {
	if ( get_option( 'brickpoint_seeded' ) ) {
		return;
	}
	update_option( 'brickpoint_seeded', 1 );
}
add_action( 'after_switch_theme', 'brickpoint_seed_terms' );
