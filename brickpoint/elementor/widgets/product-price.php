<?php
/**
 * Elementor widget: dynamic product price (single product).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Product_Price_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-product-price';
	}

	public function get_title() {
		return __( 'BrickPoint Product Price', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-price-tag';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Price', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'prefix', array(
			'label'   => __( 'Prefix text', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'fallback', array(
			'label'   => __( 'Fallback text (no price set)', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Ask for price', 'brickpoint' ),
		) );

		$this->add_control( 'show_unit', array(
			'label'        => __( 'Show unit', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'price_color', array(
			'label'     => __( 'Price color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#141210',
			'selectors' => array( '{{WRAPPER}} .bp-price' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$id    = get_the_ID();
		$price = bp_meta( $id, '_bp_price', '' );
		$unit  = bp_meta( $id, '_bp_unit', '' );

		if ( ! $price ) {
			$price = $s['fallback'];
		}

		printf(
			'<div class="bp-price-tag"><span class="bp-price">%s%s</span>%s</div>',
			esc_html( $s['prefix'] ),
			esc_html( $price ),
			( 'yes' === $s['show_unit'] && $unit ) ? ' <span class="bp-unit">' . esc_html( $unit ) . '</span>' : ''
		);
	}
}
