<?php
/**
 * Elementor widget: animated statistics band (LM Arena dark style).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class BrickPoint_Stats_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-stats';
	}

	public function get_title() {
		return __( 'BrickPoint Stats', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Statistics', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_heading', array(
			'label'        => __( 'Show heading', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
		) );

		$this->bp_heading_controls( '', array(
			'eyebrow' => __( 'By the numbers', 'brickpoint' ),
			'title'   => __( 'Trusted by builders across the region', 'brickpoint' ),
		) );

		$stats = new Repeater();
		$stats->add_control( 'number', array(
			'label'   => __( 'Number', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 100,
		) );
		$stats->add_control( 'suffix', array(
			'label'   => __( 'Suffix', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '+',
		) );
		$stats->add_control( 'label', array(
			'label'   => __( 'Label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Stat items', 'brickpoint' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $stats->get_controls(),
			'default'     => array(
				array( 'number' => 3, 'suffix' => '', 'label' => __( 'Operating brick yards', 'brickpoint' ) ),
				array( 'number' => 500, 'suffix' => '+', 'label' => __( 'Brick kilns supplied', 'brickpoint' ) ),
				array( 'number' => 1200, 'suffix' => '+', 'label' => __( 'Projects delivered to', 'brickpoint' ) ),
				array( 'number' => 15, 'suffix' => '+', 'label' => __( 'Years of experience', 'brickpoint' ) ),
			),
			'title_field' => '{{{ label }}}',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'yes' === $s['show_heading'] ) {
			echo $this->bp_render_heading(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		echo '<div class="bp-stats">';
		foreach ( (array) $s['items'] as $item ) {
			printf(
				'<div class="bp-stat reveal"><strong data-bp-counter="%1$s">%1$s%2$s</strong><span>%3$s</span></div>',
				esc_attr( $item['number'] ),
				esc_html( $item['suffix'] ),
				esc_html( $item['label'] )
			);
		}
		echo '</div>';
	}
}
