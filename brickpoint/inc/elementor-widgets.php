<?php
/**
 * Elementor widgets registration + shared base class.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Loaded from functions.php only when Elementor is active; double-guarded
// here so a direct include can never declare classes that do not exist.
if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

/**
 * Shared helpers for all BrickPoint widgets.
 */
abstract class BP_Widget_Base extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'brickpoint' );
	}

	public function get_style_depends() {
		return array( 'brickpoint-main', 'brickpoint-responsive', 'brickpoint-animations' );
	}

	/**
	 * Standard section-heading controls (eyebrow + title + lead + alignment).
	 */
	protected function bp_heading_controls( $prefix = '', $defaults = array() ) {
		$d = wp_parse_args( $defaults, array(
			'eyebrow' => '',
			'title'   => '',
			'lead'    => '',
		) );

		$this->add_control( $prefix . 'eyebrow', array(
			'label'       => __( 'Eyebrow (small label)', 'brickpoint' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => $d['eyebrow'],
			'label_block' => true,
		) );
		$this->add_control( $prefix . 'title', array(
			'label'       => __( 'Title', 'brickpoint' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => $d['title'],
			'label_block' => true,
		) );
		$this->add_control( $prefix . 'lead', array(
			'label'       => __( 'Lead text', 'brickpoint' ),
			'type'        => \Elementor\Controls_Manager::TEXTAREA,
			'default'     => $d['lead'],
		) );
		$this->add_control( $prefix . 'center', array(
			'label'        => __( 'Center align', 'brickpoint' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'bp-center',
		) );
	}

	/**
	 * Grid column controls.
	 */
	protected function bp_grid_controls( $default_columns = 4 ) {
		$this->add_responsive_control( 'columns', array(
			'label'           => __( 'Columns', 'brickpoint' ),
			'type'            => \Elementor\Controls_Manager::SELECT,
			'desktop_default' => $default_columns,
			'tablet_default'  => 2,
			'mobile_default'  => 1,
			'options'         => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5 ),
			'selectors'       => array(
				'{{WRAPPER}} .bp-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			),
		) );
		$this->add_responsive_control( 'grid_gap', array(
			'label'      => __( 'Gap', 'brickpoint' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
			'default'    => array( 'size' => 20, 'unit' => 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .bp-grid' => 'gap: {{SIZE}}{{UNIT}};',
			),
		) );
	}

	/**
	 * CTA buttons repeater (text + link + style).
	 */
	protected function bp_cta_repeater( $defaults = array() ) {
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'label', array(
			'label'   => __( 'Button label', 'brickpoint' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => '',
		) );
		$repeater->add_control( 'style', array(
			'label'   => __( 'Button style', 'brickpoint' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'btn-brick',
			'options' => array(
				'btn-brick'    => __( 'Brick (primary)', 'brickpoint' ),
				'btn-ghost'    => __( 'Ghost', 'brickpoint' ),
				'btn-whatsapp' => __( 'WhatsApp (green)', 'brickpoint' ),
				'btn-dark'     => __( 'Dark', 'brickpoint' ),
			),
		) );
		$repeater->add_control( 'link', array(
			'label'       => __( 'Link', 'brickpoint' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'default'     => array( 'url' => '' ),
			'placeholder' => 'https://…',
			'dynamic'     => array( 'active' => true ),
		) );
		return $repeater;
	}

	/**
	 * Render the shared section heading markup.
	 */
	protected function bp_render_heading( $prefix = '' ) {
		$eyebrow = $this->get_settings_for_display( $prefix . 'eyebrow' );
		$title   = $this->get_settings_for_display( $prefix . 'title' );
		$lead    = $this->get_settings_for_display( $prefix . 'lead' );
		$center  = $this->get_settings_for_display( $prefix . 'center' );

		$out = '<div class="bp-section-head ' . esc_attr( $center ) . '">';
		if ( $eyebrow ) {
			$out .= '<p class="bp-eyebrow">' . esc_html( $eyebrow ) . '</p>';
		}
		if ( $title ) {
			$out .= '<h2>' . esc_html( $title ) . '</h2>';
		}
		if ( $lead ) {
			$out .= '<p class="bp-lead' . ( $center ? '' : '' ) . '">' . esc_html( $lead ) . '</p>';
		}
		$out .= '</div>';
		return $out;
	}

	/**
	 * Map responsive 'columns' setting onto the grid class.
	 */
	protected function bp_grid_class( $fallback = 4 ) {
		$cols = (int) ( $this->get_settings_for_display( 'columns' ) ?: $fallback );
		$cols = in_array( $cols, array( 1, 2, 3, 4, 5 ), true ) ? $cols : $fallback;
		return 'bp-grid cols-' . $cols;
	}
}

/**
 * Register all BrickPoint widgets.
 */
function brickpoint_register_elementor_widgets( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}

	$widgets = array(
		'site-header.php'      => 'BrickPoint_Site_Header_Widget',
		'site-footer.php'      => 'BrickPoint_Site_Footer_Widget',
		'hero.php'             => 'BrickPoint_Hero_Widget',
		'product-grid.php'     => 'BrickPoint_Product_Grid_Widget',
		'product-categories.php' => 'BrickPoint_Product_Categories_Widget',
		'product-price.php'    => 'BrickPoint_Product_Price_Widget',
		'whatsapp-button.php'  => 'BrickPoint_WhatsApp_Button_Widget',
		'video-grid.php'       => 'BrickPoint_Video_Grid_Widget',
		'project-grid.php'     => 'BrickPoint_Project_Grid_Widget',
		'location-cards.php'   => 'BrickPoint_Location_Cards_Widget',
		'posts-grid.php'       => 'BrickPoint_Posts_Grid_Widget',
		'stats.php'            => 'BrickPoint_Stats_Widget',
		'cta.php'              => 'BrickPoint_CTA_Widget',
		'social-links.php'     => 'BrickPoint_Social_Links_Widget',
		'archive-head.php'     => 'BrickPoint_Archive_Head_Widget',
		'product-single.php'   => 'BrickPoint_Product_Single_Widget',
		'video-single.php'     => 'BrickPoint_Video_Single_Widget',
		'project-single.php'   => 'BrickPoint_Project_Single_Widget',
		'location-single.php'  => 'BrickPoint_Location_Single_Widget',
		'post-single.php'      => 'BrickPoint_Post_Single_Widget',
	);

	$base = BRICKPOINT_DIR . '/elementor/widgets/';

	foreach ( $widgets as $file => $class ) {
		$path = $base . $file;
		if ( file_exists( $path ) ) {
			require_once $path;
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}
}
add_action( 'elementor/widgets/register', 'brickpoint_register_elementor_widgets' );
