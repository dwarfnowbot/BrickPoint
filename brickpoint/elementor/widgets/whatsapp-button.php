<?php
/**
 * Elementor widget: WhatsApp button.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_WhatsApp_Button_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-whatsapp-button';
	}

	public function get_title() {
		return __( 'BrickPoint WhatsApp Button', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Button', 'brickpoint' ),
			'type'  => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'label', array(
			'label'   => __( 'Label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Order on WhatsApp', 'brickpoint' ),
		) );

		$this->add_control( 'number', array(
			'label'       => __( 'WhatsApp number (international, no +)', 'brickpoint' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => bp_phone_intl(),
			'description' => __( 'Leave default to use the Customizer number.', 'brickpoint' ),
		) );

		$this->add_control( 'message', array(
			'label'   => __( 'Pre-filled message', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.', 'brickpoint' ),
			'rows'    => 3,
		) );

		$this->add_control( 'size', array(
			'label'   => __( 'Size', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => array(
				''        => __( 'Normal', 'brickpoint' ),
				'btn-sm'  => __( 'Small', 'brickpoint' ),
				'btn-block' => __( 'Full width', 'brickpoint' ),
			),
		) );

		$this->add_control( 'align', array(
			'label'   => __( 'Alignment', 'brickpoint' ),
			'type'    => Controls_Manager::CHOOSE,
			'default' => 'bp-center',
			'options' => array(
				'left'   => array( 'title' => __( 'Left', 'brickpoint' ), 'icon' => 'eicon-text-align-left' ),
				'bp-center' => array( 'title' => __( 'Center', 'brickpoint' ), 'icon' => 'eicon-text-align-center' ),
			),
			'selectors' => array( '{{WRAPPER}}' => 'text-align: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$number = preg_replace( '/\D/', '', $s['number'] ?: bp_phone_intl() );
		$url    = 'https://wa.me/' . $number . '?text=' . rawurlencode( $s['message'] );
		printf(
			'<a class="btn-whatsapp %s" target="_blank" rel="noopener" href="%s">%s</a>',
			esc_attr( $s['size'] ),
			esc_url( $url ),
			esc_html( $s['label'] )
		);
	}
}
