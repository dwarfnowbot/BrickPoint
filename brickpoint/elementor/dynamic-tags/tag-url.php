<?php
/**
 * Dynamic URL tag: WhatsApp + custom field links.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BrickPoint_Dynamic_URL_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'bp-field-url';
	}

	public function get_title() {
		return __( 'BrickPoint URL', 'brickpoint' );
	}

	public function get_group() {
		return 'brickpoint';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY );
	}

	protected function register_controls() {
		$this->add_control( 'url_type', array(
			'label'   => __( 'URL type', 'brickpoint' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'whatsapp_product',
			'options' => array(
				'whatsapp_product' => __( 'Product WhatsApp order link', 'brickpoint' ),
				'whatsapp_default' => __( 'Default WhatsApp link', 'brickpoint' ),
				'custom_field'     => __( 'Custom field (URL)', 'brickpoint' ),
			),
		) );

		$this->add_control( 'field', array(
			'label'     => __( 'Custom field key', 'brickpoint' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'default'   => '_bpl_maps',
			'condition' => array( 'url_type' => 'custom_field' ),
		) );
	}

	public function render() {
		$type = $this->get_settings( 'url_type' );

		if ( 'whatsapp_product' === $type ) {
			echo esc_url( bp_whatsapp_url( bp_product_whatsapp_message( get_the_ID() ) ) );
			return;
		}
		if ( 'whatsapp_default' === $type ) {
			echo esc_url( bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) ) );
			return;
		}

		$field = $this->get_settings( 'field' );
		echo esc_url( (string) get_post_meta( get_the_ID(), $field, true ) );
	}
}
