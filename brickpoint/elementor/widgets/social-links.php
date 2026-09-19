<?php
/**
 * Elementor widget: social icon links.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class BrickPoint_Social_Links_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-social-links';
	}

	public function get_title() {
		return __( 'BrickPoint Social Links', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-social-icons';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Social links', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$networks = new Repeater();
		$networks->add_control( 'network', array(
			'label'   => __( 'Icon', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'facebook',
			'options' => array(
				'facebook'  => 'Facebook',
				'instagram' => 'Instagram',
				'twitter'   => 'X / Twitter',
				'tiktok'    => 'TikTok',
			),
		) );
		$networks->add_control( 'url', array(
			'label'   => __( 'Link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'dynamic' => array( 'active' => true ),
		) );
		$networks->add_responsive_control( 'col', array(
			'type' => Controls_Manager::RAW_HTML,
			'raw'  => '',
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Networks', 'brickpoint' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => array_filter( $networks->get_controls(), function ( $f ) {
				return 'col' !== $f['name'];
			} ),
			'default'     => array(
				array( 'network' => 'facebook', 'url' => array( 'url' => 'https://www.facebook.com/brickpoint.pk/' ) ),
				array( 'network' => 'instagram', 'url' => array( 'url' => 'https://www.instagram.com/brickpoint.pk/' ) ),
				array( 'network' => 'twitter', 'url' => array( 'url' => 'https://x.com/BrickPointPK' ) ),
				array( 'network' => 'tiktok', 'url' => array( 'url' => 'https://www.tiktok.com/@brickpoint.pk/' ) ),
			),
			'title_field' => '{{{ network }}}',
		) );

		$this->add_responsive_control( 'size', array(
			'label'      => __( 'Icon size', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 24, 'max' => 72 ) ),
			'default'    => array( 'size' => 40, 'unit' => 'px' ),
			'selectors'  => array( '{{WRAPPER}} .bp-social a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$labels = array(
			'facebook'  => __( 'Facebook', 'brickpoint' ),
			'instagram' => __( 'Instagram', 'brickpoint' ),
			'twitter'   => __( 'X / Twitter', 'brickpoint' ),
			'tiktok'    => __( 'TikTok', 'brickpoint' ),
		);
		echo '<div class="bp-social">';
		foreach ( (array) $s['items'] as $item ) {
			$net = $item['network'];
			$url = ! empty( $item['url']['url'] ) ? $item['url']['url'] : bp_social( $net );
			if ( ! $url ) {
				continue;
			}
			printf(
				'<a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a>',
				esc_url( $url ),
				esc_attr( isset( $labels[ $net ] ) ? $labels[ $net ] : $net ),
				bp_icon( 'twitter' === $net ? 'x-twitter' : $net ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized in bp_icon().
			);
		}
		echo '</div>';
	}
}
