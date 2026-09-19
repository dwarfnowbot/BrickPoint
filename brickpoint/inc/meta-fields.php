<?php
/**
 * Meta boxes / custom fields for Products, Videos, Projects and Locations.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brickpoint_product_meta_fields() {
	return array(
		'_bp_price'       => __( 'Product price (e.g. Rs. 14,500)', 'brickpoint' ),
		'_bp_price_label' => __( 'Price label (e.g. Market-competitive bulk pricing)', 'brickpoint' ),
		'_bp_unit'        => __( 'Unit (e.g. 1000 bricks)', 'brickpoint' ),
		'_bp_availability' => __( 'Availability status', 'brickpoint' ),
		'_bp_badge'       => __( 'Product badge (e.g. Best Seller)', 'brickpoint' ),
		'_bp_sku'         => __( 'SKU / internal reference', 'brickpoint' ),
		'_bp_short'       => __( 'Short description', 'brickpoint' ),
		'_bp_gallery'     => __( 'Gallery image URLs (one per line)', 'brickpoint' ),
		'_bp_specs'       => __( 'Specifications (one per line as Label: Value)', 'brickpoint' ),
		'_bp_features'    => __( 'Features (one per line)', 'brickpoint' ),
		'_bp_video'       => __( 'Product video URL (MP4 / YouTube / Vimeo)', 'brickpoint' ),
		'_bp_video_type'  => __( 'Video type: mp4, youtube, vimeo', 'brickpoint' ),
		'_bp_brochure'    => __( 'Brochure / PDF URL', 'brickpoint' ),
		'_bp_whatsapp'    => __( 'Custom WhatsApp message (optional override)', 'brickpoint' ),
		'_bp_sort'        => __( 'Display order (number, ascending)', 'brickpoint' ),
		'_bp_related'     => __( 'Related product IDs (comma separated)', 'brickpoint' ),
	);
}

function brickpoint_add_meta_boxes() {
	add_meta_box( 'bp_product_details', __( 'Product Details', 'brickpoint' ), 'brickpoint_product_meta_box', 'bp_product', 'normal', 'high' );
	add_meta_box( 'bp_product_featured', __( 'Featured Product', 'brickpoint' ), 'brickpoint_featured_meta_box', 'bp_product', 'side', 'default' );
	add_meta_box( 'bp_video_details', __( 'Video Details', 'brickpoint' ), 'brickpoint_video_meta_box', 'bp_video', 'normal', 'high' );
	add_meta_box( 'bp_project_details', __( 'Project Details', 'brickpoint' ), 'brickpoint_project_meta_box', 'bp_project', 'normal', 'high' );
	add_meta_box( 'bp_location_details', __( 'Location Details', 'brickpoint' ), 'brickpoint_location_meta_box', 'bp_location', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'brickpoint_add_meta_boxes' );

function brickpoint_product_meta_box( $post ) {
	wp_nonce_field( 'bp_product_meta', 'bp_product_meta_nonce' );
	foreach ( brickpoint_product_meta_fields() as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br />';
		if ( in_array( $key, array( '_bp_short', '_bp_gallery', '_bp_specs', '_bp_features', '_bp_whatsapp' ), true ) ) {
			echo '<textarea name="' . esc_attr( $key ) . '" rows="3" style="width:100%">' . esc_textarea( $val ) . '</textarea></p>';
		} else {
			echo '<input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%" /></p>';
		}
	}
	echo '<p class="description">' . esc_html__( 'Tip: use the main content editor for the full description and the Featured Image panel for the main product image.', 'brickpoint' ) . '</p>';
}

function brickpoint_featured_meta_box( $post ) {
	$v = get_post_meta( $post->ID, '_bp_featured', true );
	echo '<label><input type="checkbox" name="_bp_featured" value="1"' . checked( $v, '1', false ) . ' /> ' . esc_html__( 'Mark as featured product', 'brickpoint' ) . '</label>';
}

function brickpoint_video_meta_box( $post ) {
	wp_nonce_field( 'bp_video_meta', 'bp_video_meta_nonce' );
	$fields = array(
		'_bpv_source'            => __( 'Source type: mp4, youtube, vimeo, external', 'brickpoint' ),
		'_bpv_url'               => __( 'Video URL (YouTube/Vimeo/embed/MP4)', 'brickpoint' ),
		'_bpv_file'              => __( 'Self-hosted file URL (optional)', 'brickpoint' ),
		'_bpv_duration'          => __( 'Duration (e.g. 2:30)', 'brickpoint' ),
		'_bpv_order'             => __( 'Display order (number)', 'brickpoint' ),
		'_bpv_captions'          => __( 'Captions/subtitles URL', 'brickpoint' ),
		'_bpv_related_products'  => __( 'Related product IDs (comma separated)', 'brickpoint' ),
		'_bpv_related_projects'  => __( 'Related project IDs', 'brickpoint' ),
		'_bpv_related_locations' => __( 'Related location IDs', 'brickpoint' ),
	);
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br /><input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%" /></p>';
	}
	$f = get_post_meta( $post->ID, '_bpv_featured', true );
	echo '<p><label><input type="checkbox" name="_bpv_featured" value="1"' . checked( $f, '1', false ) . ' /> ' . esc_html__( 'Featured video', 'brickpoint' ) . '</label></p>';
}

function brickpoint_project_meta_box( $post ) {
	wp_nonce_field( 'bp_project_meta', 'bp_project_meta_nonce' );
	$fields = array(
		'_bpp_location'    => __( 'Location (e.g. DHA Lahore)', 'brickpoint' ),
		'_bpp_category'    => __( 'Project category', 'brickpoint' ),
		'_bpp_status'      => __( 'Status (default: Illustrative construction reference)', 'brickpoint' ),
		'_bpp_link'        => __( 'External project link (optional)', 'brickpoint' ),
		'_bpp_gallery'     => __( 'Gallery URLs (one per line)', 'brickpoint' ),
		'_bpp_video'       => __( 'Project video URL', 'brickpoint' ),
		'_bpp_sort'        => __( 'Display order (number, ascending)', 'brickpoint' ),
		'_bpp_related'     => __( 'Related product IDs', 'brickpoint' ),
	);
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br />';
		if ( '_bpp_gallery' === $key ) {
			echo '<textarea name="' . esc_attr( $key ) . '" rows="3" style="width:100%">' . esc_textarea( $val ) . '</textarea></p>';
		} else {
			echo '<input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%" /></p>';
		}
	}
	$f   = get_post_meta( $post->ID, '_bpp_featured', true );
	$ill = get_post_meta( $post->ID, '_bpp_illustrative', true );
	if ( '' === $ill ) {
		$ill = '1';
	}
	echo '<p><label><input type="checkbox" name="_bpp_featured" value="1"' . checked( $f, '1', false ) . ' /> ' . esc_html__( 'Featured project', 'brickpoint' ) . '</label></p>';
	echo '<p><label><input type="checkbox" name="_bpp_illustrative" value="1"' . checked( $ill, '1', false ) . ' /> ' . esc_html__( 'Illustrative / inspiration visual (not a claimed BrickPoint project)', 'brickpoint' ) . '</label></p>';
}

function brickpoint_location_meta_box( $post ) {
	wp_nonce_field( 'bp_location_meta', 'bp_location_meta_nonce' );
	$fields = array(
		'_bpl_address'  => __( 'Address', 'brickpoint' ),
		'_bpl_maps'     => __( 'Google Maps URL', 'brickpoint' ),
		'_bpl_phone'    => __( 'Phone', 'brickpoint' ),
		'_bpl_whatsapp' => __( 'WhatsApp number (intl, no +, optional override)', 'brickpoint' ),
		'_bpl_coords'   => __( 'Coordinates (lat, lng)', 'brickpoint' ),
		'_bpl_hours'    => __( 'Opening hours', 'brickpoint' ),
		'_bpl_video'    => __( 'Related video URL (optional)', 'brickpoint' ),
		'_bpl_order'    => __( 'Display order', 'brickpoint' ),
	);
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<p><label><strong>' . esc_html( $label ) . '</strong></label><br /><input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%" /></p>';
	}
}

function brickpoint_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$maps = array(
		'bp_product_meta'  => array_merge( array_keys( brickpoint_product_meta_fields() ), array( '_bp_featured' ) ),
		'bp_video_meta'    => array( '_bpv_source', '_bpv_url', '_bpv_file', '_bpv_duration', '_bpv_order', '_bpv_captions', '_bpv_related_products', '_bpv_related_projects', '_bpv_related_locations', '_bpv_featured' ),
		'bp_project_meta'  => array( '_bpp_location', '_bpp_category', '_bpp_status', '_bpp_link', '_bpp_gallery', '_bpp_video', '_bpp_sort', '_bpp_related', '_bpp_featured', '_bpp_illustrative' ),
		'bp_location_meta' => array( '_bpl_address', '_bpl_maps', '_bpl_phone', '_bpl_whatsapp', '_bpl_coords', '_bpl_hours', '_bpl_video', '_bpl_order' ),
	);
	$url_keys = array( '_bp_video', '_bp_brochure', '_bpp_video', '_bpp_link', '_bpl_maps', '_bpv_file', '_bpv_captions', '_bpl_video' );
	$flag_keys = array( '_bp_featured', '_bpv_featured', '_bpp_featured', '_bpp_illustrative' );

	foreach ( $maps as $nonce => $keys ) {
		if ( ! isset( $_POST[ $nonce ] ) ) {
			continue;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce ] ) ), $nonce ) ) {
			continue;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}
		foreach ( $keys as $k ) {
			if ( ! isset( $_POST[ $k ] ) ) {
				if ( in_array( $k, $flag_keys, true ) ) {
					update_post_meta( $post_id, $k, '0' );
				}
				continue;
			}
			$raw = wp_unslash( $_POST[ $k ] );
			if ( in_array( $k, $url_keys, true ) ) {
				update_post_meta( $post_id, $k, esc_url_raw( $raw ) );
			} else {
				update_post_meta( $post_id, $k, sanitize_textarea_field( $raw ) );
			}
		}
	}
}
add_action( 'save_post', 'brickpoint_save_meta' );
