<?php
/**
 * Elementor widget: homepage hero (LM Arena design).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-hero';
	}

	public function get_title() {
		return __( 'BrickPoint Hero', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_keywords() {
		return array( 'hero', 'home', 'banner', 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Hero Content', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'kicker', array(
			'label'   => __( 'Kicker line', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Masha Allah • Fine Bricks • SS7', 'brickpoint' ),
			'label_block' => true,
		) );

		$this->add_control( 'title', array(
			'label'   => __( 'Headline', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Building Strength. Delivering Quality. Shaping Tomorrow.', 'brickpoint' ),
			'rows'    => 2,
		) );

		$this->add_control( 'subtitle', array(
			'label'   => __( 'Subtitle', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.', 'brickpoint' ),
			'rows'    => 3,
		) );

		$this->add_control( 'btn1_label', array(
			'label'   => __( 'Button 1 label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Explore Products', 'brickpoint' ),
		) );
		$this->add_control( 'btn1_link', array(
			'label'   => __( 'Button 1 link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => get_post_type_archive_link( 'bp_product' ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->add_control( 'btn2_label', array(
			'label'   => __( 'Button 2 label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Request a Quote', 'brickpoint' ),
		) );
		$this->add_control( 'btn2_link', array(
			'label'   => __( 'Button 2 link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => home_url( '/contact/' ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->add_control( 'btn3_label', array(
			'label'   => __( 'WhatsApp button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'WhatsApp Us', 'brickpoint' ),
		) );
		$this->add_control( 'btn3_link', array(
			'label'   => __( 'WhatsApp link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_media', array(
			'label' => __( 'Hero Media', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'media_type', array(
			'label'   => __( 'Media type', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'video',
			'options' => array(
				'video' => __( 'Self-hosted video', 'brickpoint' ),
				'image' => __( 'Image', 'brickpoint' ),
				'none'  => __( 'None', 'brickpoint' ),
			),
		) );

		$this->add_control( 'video_url', array(
			'label'      => __( 'Video (MP4 URL)', 'brickpoint' ),
			'type'       => Controls_Manager::MEDIA,
			'media_type' => 'video',
			'default'    => array( 'url' => '' ),
			'condition'  => array( 'media_type' => 'video' ),
			'dynamic'    => array( 'active' => true ),
		) );

		$this->add_control( 'poster_image', array(
			'label'     => __( 'Poster image', 'brickpoint' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => array( 'url' => '' ),
			'condition' => array( 'media_type!' => 'none' ),
		) );

		$this->add_control( 'show_ss7', array(
			'label'        => __( 'Show floating SS7 brick', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'separator'    => 'before',
		) );

		$this->add_control( 'ss7_text', array(
			'label'   => __( 'Floating brick text', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Flagship Bricks', 'brickpoint' ),
			'condition' => array( 'show_ss7' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_style', array(
			'label' => __( 'Style', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bg_color', array(
			'label'     => __( 'Hero background', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#141210',
			'selectors' => array( '{{WRAPPER}} .bp-hero' => 'background: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'padding', array(
			'label'      => __( 'Padding', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 20, 'max' => 140 ) ),
			'default'    => array( 'size' => 64, 'unit' => 'px' ),
			'selectors'  => array( '{{WRAPPER}} .bp-hero' => 'padding: {{SIZE}}{{UNIT}} 0;' ),
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'title_typography',
			'label'    => __( 'Headline typography', 'brickpoint' ),
			'selector' => '{{WRAPPER}} .bp-hero h1',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="bp-hero">
			<div class="bp-container bp-hero-grid">
				<div class="bp-hero-copy">
					<?php if ( $s['kicker'] ) : ?>
						<p class="bp-hero-kicker"><?php echo esc_html( $s['kicker'] ); ?></p>
					<?php endif; ?>
					<h1><?php echo esc_html( $s['title'] ); ?></h1>
					<p><?php echo esc_html( $s['subtitle'] ); ?></p>
					<p class="bp-hero-cta">
						<?php if ( $s['btn1_label'] ) : ?>
							<a class="btn-brick" href="<?php echo esc_url( $s['btn1_link']['url'] ?: get_post_type_archive_link( 'bp_product' ) ); ?>"><?php echo esc_html( $s['btn1_label'] ); ?></a>
						<?php endif; ?>
						<?php if ( $s['btn2_label'] ) : ?>
							<a class="btn-ghost" href="<?php echo esc_url( $s['btn2_link']['url'] ?: home_url( '/contact/' ) ); ?>"><?php echo esc_html( $s['btn2_label'] ); ?></a>
						<?php endif; ?>
						<?php if ( $s['btn3_label'] ) : ?>
							<a class="btn-whatsapp" target="_blank" rel="noopener" href="<?php echo esc_url( $s['btn3_link']['url'] ?: bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) ) ); ?>"><?php echo esc_html( $s['btn3_label'] ); ?></a>
						<?php endif; ?>
					</p>
				</div>
				<div class="bp-hero-media">
					<?php
					$video_url = 'video' === $s['media_type'] && ! empty( $s['video_url']['url'] ) ? $s['video_url']['url'] : '';
					$poster    = ! empty( $s['poster_image']['url'] ) ? $s['poster_image']['url'] : '';
					if ( $video_url ) :
						?>
						<video class="bp-hero-video" autoplay muted loop playsinline preload="metadata" <?php if ( $poster ) : ?>poster="<?php echo esc_url( $poster ); ?>"<?php endif; ?>>
							<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4" />
						</video>
					<?php elseif ( 'image' === $s['media_type'] && $poster ) : ?>
						<img class="bp-hero-video" src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" />
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_ss7'] ) : ?>
						<div class="bp-ss7-float"><div class="ss7-brick"><strong>SS7</strong><span><?php echo esc_html( $s['ss7_text'] ); ?></span></div></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
