<?php
/**
 * Elementor widget: full LM Arena header (top bar + nav + CTAs + mobile menu).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class BrickPoint_Site_Header_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-site-header';
	}

	public function get_title() {
		return __( 'BrickPoint Header', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-header';
	}

	public function get_keywords() {
		return array( 'header', 'menu', 'topbar', 'brickpoint' );
	}

	protected function register_controls() {
		/* ---------- Content: top bar ---------- */
		$this->start_controls_section( 'sec_topbar', array(
			'label' => __( 'Top Bar', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_topbar', array(
			'label'        => __( 'Show top bar', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'phone_display', array(
			'label'       => __( 'Phone (display)', 'brickpoint' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => bp_phone_display(),
			'dynamic'     => array( 'active' => true ),
		) );

		$phone_link = $this->add_control( 'phone_link', array(
			'label'       => __( 'Phone link', 'brickpoint' ),
			'type'        => Controls_Manager::URL,
			'default'     => array( 'url' => 'tel:+' . bp_phone_intl() ),
			'dynamic'     => array( 'active' => true ),
		) );

		$this->add_control( 'units_text', array(
			'label'       => __( 'Units line', 'brickpoint' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Masha Allah Bricks Co. • Fine Bricks Co. • SS7 Bricks', 'brickpoint' ),
			'label_block' => true,
		) );

		$top_links = new Repeater();
		$top_links->add_control( 'label', array(
			'label'   => __( 'Label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$top_links->add_control( 'link', array(
			'label'   => __( 'Link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '' ),
			'dynamic' => array( 'active' => true ),
		) );
		$this->add_control( 'top_links', array(
			'label'       => __( 'Top bar links', 'brickpoint' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $top_links->get_controls(),
			'default'     => array(
				array(
					'label' => __( 'Our Bhattas', 'brickpoint' ),
					'link'  => array( 'url' => get_post_type_archive_link( 'bp_location' ) ),
				),
				array(
					'label' => __( 'Videos', 'brickpoint' ),
					'link'  => array( 'url' => get_post_type_archive_link( 'bp_video' ) ),
				),
			),
			'title_field' => '{{{ label }}}',
		) );

		$this->end_controls_section();

		/* ---------- Content: brand + menu ---------- */
		$this->start_controls_section( 'sec_brand', array(
			'label' => __( 'Brand & Menu', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'logo_source', array(
			'label'   => __( 'Logo', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'site',
			'options' => array(
				'site'  => __( 'Site identity (Customizer logo / text)', 'brickpoint' ),
				'custom' => __( 'Custom text', 'brickpoint' ),
				'image' => __( 'Custom image', 'brickpoint' ),
			),
		) );

		$this->add_control( 'logo_text', array(
			'label'     => __( 'Logo text (accent part after last word is highlighted)', 'brickpoint' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'BrickPoint',
			'condition' => array( 'logo_source' => 'custom' ),
		) );

		$this->add_control( 'logo_image', array(
			'label'     => __( 'Logo image', 'brickpoint' ),
			'type'      => Controls_Manager::MEDIA,
			'default'   => array( 'url' => BRICKPOINT_URI . '/assets/images/logo-white.svg' ),
			'condition' => array( 'logo_source' => 'image' ),
		) );

		$this->add_control( 'logo_size', array(
			'label'      => __( 'Logo max height', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 28, 'max' => 120 ) ),
			'default'    => array( 'size' => 52, 'unit' => 'px' ),
			'selectors'  => array(
				'{{WRAPPER}} .bp-logo img, {{WRAPPER}} .bp-brand img' => 'max-height: {{SIZE}}{{UNIT}}; width: auto;',
			),
		) );

		$this->add_control( 'menu_location', array(
			'label'   => __( 'Menu', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'primary',
			'options' => $this->bp_menu_options(),
		) );

		$this->end_controls_section();

		/* ---------- Content: CTA buttons ---------- */
		$this->start_controls_section( 'sec_cta', array(
			'label' => __( 'Header Buttons', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_phone_btn', array(
			'label'        => __( 'Show phone pill', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'wa_label', array(
			'label'   => __( 'WhatsApp button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'WhatsApp Us', 'brickpoint' ),
		) );

		$this->add_control( 'wa_link', array(
			'label'   => __( 'WhatsApp link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->add_control( 'quote_label', array(
			'label'   => __( 'Quote button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Request Quote', 'brickpoint' ),
		) );

		$this->add_control( 'quote_link', array(
			'label'   => __( 'Quote link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => home_url( '/contact/' ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->end_controls_section();

		/* ---------- Content: mobile ---------- */
		$this->start_controls_section( 'sec_mobile', array(
			'label' => __( 'Mobile Menu', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'mobile_location', array(
			'label'   => __( 'Mobile menu', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'mobile',
			'options' => $this->bp_menu_options(),
		) );

		$this->add_control( 'mobile_wa_label', array(
			'label'   => __( 'Mobile WhatsApp label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'WhatsApp Us', 'brickpoint' ),
		) );

		$this->end_controls_section();

		/* ---------- Style ---------- */
		$this->start_controls_section( 'sec_style', array(
			'label' => __( 'Style', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bg_color', array(
			'label'     => __( 'Header background', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(20,18,16,0.92)',
			'selectors' => array( '{{WRAPPER}} .bp-header' => 'background: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'header_height', array(
			'label'      => __( 'Header height', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 56, 'max' => 140 ) ),
			'default'    => array( 'size' => 72, 'unit' => 'px' ),
			'selectors'  => array( '{{WRAPPER}} .bp-header-inner' => 'height: {{SIZE}}{{UNIT}};' ),
		) );

		$this->add_control( 'menu_color', array(
			'label'     => __( 'Menu link color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.85)',
			'selectors' => array( '{{WRAPPER}} .bp-menu a' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'menu_typography',
			'label'    => __( 'Menu typography', 'brickpoint' ),
			'selector' => '{{WRAPPER}} .bp-menu a',
		) );

		$this->add_control( 'sticky', array(
			'label'        => __( 'Sticky header', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'description'  => __( 'The LM Arena design uses a sticky glass header.', 'brickpoint' ),
		) );

		$this->end_controls_section();
	}

	protected function bp_menu_options() {
		$options  = array( '' => __( '— Select —', 'brickpoint' ) );
		$menus    = wp_get_nav_menus();
		$assigned = get_nav_menu_locations();
		foreach ( $menus as $menu ) {
			$tag = '';
			foreach ( $assigned as $loc => $id ) {
				if ( (int) $id === (int) $menu->term_id ) {
					/* translators: %s: menu location name */
					$tag = sprintf( __( ' (%s location)', 'brickpoint' ), $loc );
				}
			}
			$options[ $menu->slug ] = $menu->name . $tag;
		}
		return $options;
	}

	protected function render() {
		$s           = $this->get_settings_for_display();
		$sticky      = ( 'yes' === $s['sticky'] ) ? '' : 'position:static;';
		$wa_url      = ! empty( $s['wa_link']['url'] ) ? $s['wa_link']['url'] : bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) );
		$quote_url   = ! empty( $s['quote_link']['url'] ) ? $s['quote_link']['url'] : home_url( '/contact/' );
		$phone_url   = ! empty( $s['phone_link']['url'] ) ? $s['phone_link']['url'] : 'tel:+' . bp_phone_intl();

		$this->bp_render_topbar( $s, $phone_url );
		?>
		<header class="bp-header" id="bpHeader" style="<?php echo esc_attr( $sticky ); ?>">
			<div class="bp-container bp-header-inner">
				<div class="bp-brand">
					<?php
					if ( 'image' === $s['logo_source'] && ! empty( $s['logo_image']['url'] ) ) {
						printf( '<a class="bp-logo" href="%s"><img src="%s" alt="%s" /></a>',
							esc_url( home_url( '/' ) ),
							esc_url( $s['logo_image']['url'] ),
							esc_attr( get_bloginfo( 'name' ) )
						);
					} elseif ( 'custom' === $s['logo_source'] && ! empty( $s['logo_text'] ) ) {
						$parts = explode( ' ', $s['logo_text'] );
						$last  = array_pop( $parts );
						printf( '<a class="bp-logo" href="%s">%s<span>%s</span></a>',
							esc_url( home_url( '/' ) ),
							esc_html( implode( ' ', $parts ) . ( $parts ? ' ' : '' ) ),
							esc_html( $last )
						);
					} elseif ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						printf( '<a class="bp-logo" href="%s">Brick<span>Point</span></a>', esc_url( home_url( '/' ) ) );
					}
					?>
				</div>
				<nav class="bp-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'brickpoint' ); ?>">
					<?php
					if ( ! empty( $s['menu_location'] ) ) {
						wp_nav_menu( array(
							'menu'        => $s['menu_location'],
							'container'   => false,
							'menu_class'  => 'bp-menu',
							'fallback_cb' => false,
						) );
					}
					?>
				</nav>
				<div class="bp-header-cta">
					<?php if ( 'yes' === $s['show_phone_btn'] ) : ?>
						<a class="bp-header-phone" href="<?php echo esc_attr( $phone_url ); ?>"><?php echo esc_html( $s['phone_display'] ); ?></a>
					<?php endif; ?>
					<?php if ( $s['wa_label'] ) : ?>
						<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $wa_url ); ?>"><?php echo esc_html( $s['wa_label'] ); ?></a>
					<?php endif; ?>
					<?php if ( $s['quote_label'] ) : ?>
						<a class="btn-brick btn-sm" href="<?php echo esc_url( $quote_url ); ?>"><?php echo esc_html( $s['quote_label'] ); ?></a>
					<?php endif; ?>
				</div>
				<button class="bp-menu-toggle" id="bpMenuToggle" aria-label="<?php esc_attr_e( 'Open menu', 'brickpoint' ); ?>" aria-expanded="false" aria-controls="bpMobileMenu">☰</button>
			</div>
			<div class="bp-mobile-menu" id="bpMobileMenu" hidden>
				<?php
				if ( ! empty( $s['mobile_location'] ) ) {
					wp_nav_menu( array(
						'menu'        => $s['mobile_location'],
						'container'   => false,
						'menu_class'  => 'bp-menu-mobile',
						'fallback_cb' => false,
					) );
				}
				?>
				<div class="bp-mobile-cta">
					<a class="btn-whatsapp btn-block" target="_blank" rel="noopener" href="<?php echo esc_url( $wa_url ); ?>"><?php echo esc_html( $s['mobile_wa_label'] ?: $s['wa_label'] ); ?></a>
				</div>
			</div>
		</header>
		<?php
	}

	protected function bp_render_topbar( $s, $phone_url ) {
		if ( 'yes' !== $s['show_topbar'] ) {
			return;
		}
		?>
		<div class="bp-topbar">
			<div class="bp-container bp-topbar-inner">
				<a class="bp-topbar-phone" href="<?php echo esc_attr( $phone_url ); ?>"><?php echo esc_html( $s['phone_display'] ); ?></a>
				<span class="bp-topbar-units"><?php echo esc_html( $s['units_text'] ); ?></span>
				<span class="bp-topbar-links">
					<?php
					foreach ( (array) $s['top_links'] as $link ) {
						if ( empty( $link['label'] ) ) {
							continue;
						}
						$url = ! empty( $link['link']['url'] ) ? $link['link']['url'] : '#';
						printf( '<a href="%s"%s>%s</a>',
							esc_url( $url ),
							! empty( $link['link']['is_external'] ) ? ' target="_blank" rel="noopener"' : '',
							esc_html( $link['label'] )
						);
					}
					?>
				</span>
			</div>
		</div>
		<?php
	}
}
