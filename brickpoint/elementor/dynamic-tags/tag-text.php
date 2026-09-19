<?php
/**
 * Dynamic text tag: BrickPoint custom fields.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BrickPoint_Dynamic_Text_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'bp-field-text';
	}

	public function get_title() {
		return __( 'BrickPoint Field', 'brickpoint' );
	}

	public function get_group() {
		return 'brickpoint';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}

	protected function register_controls() {
		$this->add_control( 'field', array(
			'label'   => __( 'Field', 'brickpoint' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '_bp_price',
			'options' => array(
				'_bp_price'  => __( 'Product price', 'brickpoint' ),
				'_bp_unit'   => __( 'Product unit', 'brickpoint' ),
				'_bp_short'  => __( 'Product short description', 'brickpoint' ),
				'_bp_badge'  => __( 'Product badge', 'brickpoint' ),
				'_bpv_url'   => __( 'Video URL', 'brickpoint' ),
				'_bpv_duration' => __( 'Video duration', 'brickpoint' ),
				'_bpp_location' => __( 'Project location', 'brickpoint' ),
				'_bpl_address'  => __( 'Location address', 'brickpoint' ),
				'_bpl_phone'    => __( 'Location phone', 'brickpoint' ),
				'_bpl_hours'    => __( 'Location hours', 'brickpoint' ),
			),
		) );

		$this->add_control( 'fallback', array(
			'label'   => __( 'Fallback', 'brickpoint' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => '',
		) );
	}

	public function render() {
		$field = $this->get_settings( 'field' );
		$value = bp_meta( get_the_ID(), $field, $this->get_settings( 'fallback' ) );
		echo wp_kses_post( $value );
	}
}
