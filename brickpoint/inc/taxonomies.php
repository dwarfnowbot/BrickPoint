<?php
/**
 * Taxonomies: product, video and project categories + term meta UI.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_register_taxonomies() {
	register_taxonomy( 'bp_product_category', 'bp_product', array(
		'labels'            => array(
			'name'          => __( 'Product Categories', 'brickpoint' ),
			'singular_name' => __( 'Product Category', 'brickpoint' ),
			'menu_name'     => __( 'Product Categories', 'brickpoint' ),
			'add_new_item'  => __( 'Add New Product Category', 'brickpoint' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'product-category', 'with_front' => false ),
		'show_in_rest'      => true,
		'show_admin_column' => true,
	) );

	register_taxonomy( 'bp_video_category', 'bp_video', array(
		'labels'            => array(
			'name'          => __( 'Video Categories', 'brickpoint' ),
			'singular_name' => __( 'Video Category', 'brickpoint' ),
			'menu_name'     => __( 'Video Categories', 'brickpoint' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'video-category', 'with_front' => false ),
		'show_in_rest'      => true,
		'show_admin_column' => true,
	) );

	register_taxonomy( 'bp_project_category', 'bp_project', array(
		'labels'            => array(
			'name'          => __( 'Project Categories', 'brickpoint' ),
			'singular_name' => __( 'Project Category', 'brickpoint' ),
			'menu_name'     => __( 'Project Categories', 'brickpoint' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'project-category', 'with_front' => false ),
		'show_in_rest'      => true,
		'show_admin_column' => true,
	) );
}
add_action( 'init', 'brickpoint_register_taxonomies' );

/**
 * Category image / icon / banner / video term meta fields.
 */
function brickpoint_tax_meta_fields( $term = null ) {
	$id     = $term ? $term->term_id : 0;
	$image  = $id ? get_term_meta( $id, 'bp_cat_image', true ) : '';
	$icon   = $id ? get_term_meta( $id, 'bp_cat_icon', true ) : '';
	$banner = $id ? get_term_meta( $id, 'bp_cat_banner', true ) : '';
	$video  = $id ? get_term_meta( $id, 'bp_cat_video', true ) : '';
	$desc   = $id ? get_term_meta( $id, 'bp_cat_desc', true ) : '';
	?>
	<tr class="form-field"><th><label for="bp_cat_image"><?php esc_html_e( 'Category image URL', 'brickpoint' ); ?></label></th>
		<td><input type="url" name="bp_cat_image" id="bp_cat_image" value="<?php echo esc_attr( $image ); ?>" class="regular-text" />
		<p class="description"><?php esc_html_e( 'Paste an image URL from the Media Library.', 'brickpoint' ); ?></p></td></tr>
	<tr class="form-field"><th><label for="bp_cat_icon"><?php esc_html_e( 'Category icon (name)', 'brickpoint' ); ?></label></th>
		<td><input type="text" name="bp_cat_icon" id="bp_cat_icon" value="<?php echo esc_attr( $icon ); ?>" class="regular-text" /></td></tr>
	<tr class="form-field"><th><label for="bp_cat_desc"><?php esc_html_e( 'Short description', 'brickpoint' ); ?></label></th>
		<td><textarea name="bp_cat_desc" id="bp_cat_desc" rows="3" class="large-text"><?php echo esc_textarea( $desc ); ?></textarea></td></tr>
	<tr class="form-field"><th><label for="bp_cat_banner"><?php esc_html_e( 'Category banner URL', 'brickpoint' ); ?></label></th>
		<td><input type="url" name="bp_cat_banner" id="bp_cat_banner" value="<?php echo esc_attr( $banner ); ?>" class="regular-text" /></td></tr>
	<tr class="form-field"><th><label for="bp_cat_video"><?php esc_html_e( 'Featured video URL (optional)', 'brickpoint' ); ?></label></th>
		<td><input type="url" name="bp_cat_video" id="bp_cat_video" value="<?php echo esc_attr( $video ); ?>" class="regular-text" /></td></tr>
	<?php
}

function brickpoint_tax_meta_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	foreach ( array( 'bp_cat_image', 'bp_cat_icon', 'bp_cat_banner', 'bp_cat_video' ) as $k ) {
		if ( isset( $_POST[ $k ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified by core term screens.
			update_term_meta( $term_id, $k, esc_url_raw( wp_unslash( $_POST[ $k ] ) ) );
		}
	}
	if ( isset( $_POST['bp_cat_desc'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_term_meta( $term_id, 'bp_cat_desc', sanitize_textarea_field( wp_unslash( $_POST['bp_cat_desc'] ) ) );
	}
}
add_action( 'bp_product_category_add_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'bp_product_category_edit_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'created_bp_product_category', 'brickpoint_tax_meta_save' );
add_action( 'edited_bp_product_category', 'brickpoint_tax_meta_save' );
add_action( 'bp_video_category_add_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'bp_video_category_edit_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'created_bp_video_category', 'brickpoint_tax_meta_save' );
add_action( 'edited_bp_video_category', 'brickpoint_tax_meta_save' );
add_action( 'bp_project_category_add_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'bp_project_category_edit_form_fields', 'brickpoint_tax_meta_fields' );
add_action( 'created_bp_project_category', 'brickpoint_tax_meta_save' );
add_action( 'edited_bp_project_category', 'brickpoint_tax_meta_save' );
