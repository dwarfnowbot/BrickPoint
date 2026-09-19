<?php
/**
 * Dynamic image tag: featured image fallback for cards.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BrickPoint_Dynamic_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'bp-field-image';
	}

	public function get_title() {
		return __( 'BrickPoint Featured Image', 'brickpoint' );
	}

	public function get_group() {
		return 'brickpoint';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	protected function register_controls() {
		$this->add_control( 'fallback', array(
			'label'     => __( 'Fallback image', 'brickpoint' ),
			'type'      => \Elementor\Controls_Manager::MEDIA,
			'default'   => array( 'url' => '' ),
		) );
	}

	public function get_value( array $options = array() ) {
		$thumb_id = get_post_thumbnail_id( get_the_ID() );

		if ( $thumb_id ) {
			$src = wp_get_attachment_image_src( $thumb_id, 'full' );
			if ( $src ) {
				return array( 'id' => $thumb_id, 'url' => $src[0] );
			}
		}

		$fallback = $this->get_settings( 'fallback' );
		return array( 'id' => '', 'url' => isset( $fallback['url'] ) ? $fallback['url'] : '' );
	}
}
