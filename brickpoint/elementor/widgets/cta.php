<?php
/**
 * Elementor widget: CTA banner (gradient band).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_CTA_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-cta';
	}

	public function get_title() {
		return __( 'BrickPoint CTA', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'CTA', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Ready to order bricks for your next project?', 'brickpoint' ),
			'label_block' => true,
		) );

		$this->add_control( 'text', array(
			'label'   => __( 'Text', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Send your material list on WhatsApp — we reply with availability and final pricing the same day.', 'brickpoint' ),
		) );

		$this->add_control( 'btn1_label', array(
			'label'   => __( 'Primary button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Chat on WhatsApp', 'brickpoint' ),
		) );

		$this->add_control( 'btn1_link', array(
			'label'   => __( 'Primary button link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->add_control( 'btn2_label', array(
			'label'   => __( 'Secondary button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Browse Products', 'brickpoint' ),
		) );

		$this->add_control( 'btn2_link', array(
			'label'   => __( 'Secondary button link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => get_post_type_archive_link( 'bp_product' ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->add_control( 'bg_from', array(
			'label'     => __( 'Gradient from', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#c2410c',
			'selectors' => array( '{{WRAPPER}} .bp-cta' => 'background: linear-gradient(135deg, {{VALUE}}, {{bg_to.VALUE}});' ),
			'inline'    => true,
		) );

		$this->add_control( 'bg_to', array(
			'label'     => __( 'Gradient to', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ea580c',
			'selectors' => array( '{{WRAPPER}} .bp-cta' => 'background: linear-gradient(135deg, {{bg_from.VALUE}}, {{VALUE}});' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="bp-cta">
			<div>
				<h2><?php echo esc_html( $s['title'] ); ?></h2>
				<p><?php echo esc_html( $s['text'] ); ?></p>
			</div>
			<div class="bp-cta-actions">
				<?php if ( $s['btn1_label'] ) : ?>
					<a class="btn-whatsapp" target="_blank" rel="noopener" href="<?php echo esc_url( $s['btn1_link']['url'] ); ?>"><?php echo esc_html( $s['btn1_label'] ); ?></a>
				<?php endif; ?>
				<?php if ( $s['btn2_label'] ) : ?>
					<a class="btn-ghost" href="<?php echo esc_url( $s['btn2_link']['url'] ); ?>"><?php echo esc_html( $s['btn2_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
