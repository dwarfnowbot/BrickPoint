<?php
/**
 * Elementor widget: full LM Arena footer (brand + menus + CTA + bottom bar).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class BrickPoint_Site_Footer_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-site-footer';
	}

	public function get_title() {
		return __( 'BrickPoint Footer', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_keywords() {
		return array( 'footer', 'copyright', 'brickpoint' );
	}

	protected function register_controls() {
		/* ---------- Brand column ---------- */
		$this->start_controls_section( 'sec_brand', array(
			'label' => __( 'Brand Column', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'logo_source', array(
			'label'   => __( 'Logo', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'site',
			'options' => array(
				'site'   => __( 'Site identity / text', 'brickpoint' ),
				'image'  => __( 'Custom image', 'brickpoint' ),
			),
		) );

		$this->add_control( 'logo_image', array(
			'label'     => __( 'Logo image', 'brickpoint' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => array( 'logo_source' => 'image' ),
		) );

		$this->add_control( 'about_text', array(
			'label'   => __( 'About text', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Premium bricks and reliable construction materials for homes, commercial developments, and large-scale building projects.', 'brickpoint' ),
			'rows'    => 3,
		) );

		$this->add_control( 'show_social', array(
			'label'        => __( 'Show social icons', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		/* ---------- Columns ---------- */
		$this->start_controls_section( 'sec_columns', array(
			'label' => __( 'Footer Columns', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_products_col', array(
			'label'        => __( 'Show "Products" column (product categories)', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'products_title', array(
			'label'       => __( 'Products column title', 'brickpoint' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Products', 'brickpoint' ),
			'condition'   => array( 'show_products_col' => 'yes' ),
		) );

		$this->add_control( 'menu_location', array(
			'label'       => __( 'Company menu', 'brickpoint' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'footer',
			'options'     => $this->bp_menu_options(),
		) );

		$this->add_control( 'company_title', array(
			'label'   => __( 'Company column title', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Company', 'brickpoint' ),
		) );

		$this->end_controls_section();

		/* ---------- CTA column ---------- */
		$this->start_controls_section( 'sec_cta', array(
			'label' => __( 'Quotation Column', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_cta', array(
			'label'        => __( 'Show quotation column', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'cta_title', array(
			'label'   => __( 'Title', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Get a Quotation', 'brickpoint' ),
		) );

		$this->add_control( 'cta_text', array(
			'label'   => __( 'Text', 'brickpoint' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Send your material list on WhatsApp for availability and final quotation.', 'brickpoint' ),
		) );

		$this->add_control( 'cta_label', array(
			'label'   => __( 'Button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Chat on WhatsApp', 'brickpoint' ),
		) );

		$this->add_control( 'cta_link', array(
			'label'   => __( 'WhatsApp link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->end_controls_section();

		/* ---------- Bottom bar ---------- */
		$this->start_controls_section( 'sec_bottom', array(
			'label' => __( 'Bottom Bar', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'copyright', array(
			'label'       => __( 'Copyright', 'brickpoint' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => bp_get( 'bp_copyright', '© BrickPoint. All rights reserved.' ),
			'label_block' => true,
			'dynamic'     => array( 'active' => true ),
		) );

		$legal = new Repeater();
		$legal->add_control( 'label', array(
			'label'   => __( 'Label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
		) );
		$legal->add_control( 'link', array(
			'label'   => __( 'Link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '' ),
			'dynamic' => array( 'active' => true ),
		) );
		$this->add_control( 'legal_links', array(
			'label'       => __( 'Legal links', 'brickpoint' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $legal->get_controls(),
			'default'     => array(
				array(
					'label' => __( 'Privacy Policy', 'brickpoint' ),
					'link'  => array( 'url' => home_url( '/privacy-policy/' ) ),
				),
				array(
					'label' => __( 'Terms & Conditions', 'brickpoint' ),
					'link'  => array( 'url' => home_url( '/terms-and-conditions/' ) ),
				),
			),
			'title_field' => '{{{ label }}}',
		) );

		$this->add_control( 'show_float_wa', array(
			'label'        => __( 'Floating WhatsApp button', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'float_wa_link', array(
			'label'   => __( 'Floating WhatsApp link', 'brickpoint' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) ) ),
			'dynamic' => array( 'active' => true ),
		) );

		$this->end_controls_section();

		/* ---------- Style ---------- */
		$this->start_controls_section( 'sec_style', array(
			'label' => __( 'Style', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'bg_color', array(
			'label'     => __( 'Footer background', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#141210',
			'selectors' => array( '{{WRAPPER}} .bp-footer' => 'background: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'padding', array(
			'label'      => __( 'Grid padding (top/bottom)', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'rem' ),
			'range'      => array( 'px' => array( 'min' => 20, 'max' => 120 ) ),
			'default'    => array( 'size' => 56, 'unit' => 'px' ),
			'selectors'  => array( '{{WRAPPER}} .bp-footer-grid' => 'padding: {{SIZE}}{{UNIT}} 0 40px;' ),
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'link_typography',
			'label'    => __( 'Link typography', 'brickpoint' ),
			'selector' => '{{WRAPPER}} .bp-footer a, {{WRAPPER}} .bp-footer ul a',
		) );

		$this->end_controls_section();
	}

	protected function bp_menu_options() {
		$options = array( '' => __( '— Select —', 'brickpoint' ) );
		foreach ( wp_get_nav_menus() as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}
		return $options;
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$cta_url = ! empty( $s['cta_link']['url'] ) ? $s['cta_link']['url'] : bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint' ) );
		?>
		<footer class="bp-footer">
			<div class="bp-container bp-footer-grid">
				<div class="bp-footer-brand">
					<?php
					if ( 'image' === $s['logo_source'] && ! empty( $s['logo_image']['url'] ) ) {
						printf( '<p class="bp-logo"><img src="%s" alt="%s" /></p>',
							esc_url( $s['logo_image']['url'] ),
							esc_attr( get_bloginfo( 'name' ) )
						);
					} elseif ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						printf( '<p class="bp-logo">Brick<span>Point</span></p>' );
					}
					?>
					<p><?php echo esc_html( $s['about_text'] ); ?></p>
					<p class="bp-footer-contact">
						<a href="tel:+<?php echo esc_attr( bp_phone_intl() ); ?>"><?php echo esc_html( bp_phone_display() ); ?></a>
						<a href="mailto:<?php echo esc_attr( bp_email() ); ?>"><?php echo esc_html( bp_email() ); ?></a>
					</p>
					<?php if ( 'yes' === $s['show_social'] ) : ?>
						<?php get_template_part( 'template-parts/social-links' ); ?>
					<?php endif; ?>
				</div>
				<?php if ( 'yes' === $s['show_products_col'] ) : ?>
					<div class="bp-footer-col">
						<h4><?php echo esc_html( $s['products_title'] ); ?></h4>
						<?php
						$pcats = get_terms( array(
							'taxonomy'   => 'bp_product_category',
							'number'     => 6,
							'hide_empty' => false,
						) );
						if ( $pcats && ! is_wp_error( $pcats ) ) {
							echo '<ul>';
							foreach ( $pcats as $t ) {
								echo '<li><a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a></li>';
							}
							echo '</ul>';
						}
						?>
					</div>
				<?php endif; ?>
				<div class="bp-footer-col">
					<h4><?php echo esc_html( $s['company_title'] ); ?></h4>
					<?php
					if ( ! empty( $s['menu_location'] ) ) {
						wp_nav_menu( array(
							'menu'        => $s['menu_location'],
							'container'   => false,
							'menu_class'  => 'bp-footer-menu',
							'fallback_cb' => false,
						) );
					}
					?>
				</div>
				<?php if ( 'yes' === $s['show_cta'] ) : ?>
					<div class="bp-footer-col">
						<h4><?php echo esc_html( $s['cta_title'] ); ?></h4>
						<p><?php echo esc_html( $s['cta_text'] ); ?></p>
						<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $s['cta_label'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
			<div class="bp-footer-bottom">
				<div class="bp-container bp-footer-bottom-inner">
					<span><?php echo esc_html( $s['copyright'] ); ?></span>
					<span class="bp-footer-legal">
						<?php
						foreach ( (array) $s['legal_links'] as $link ) {
							if ( empty( $link['label'] ) ) {
								continue;
							}
							printf( '<a href="%s">%s</a>',
								esc_url( ! empty( $link['link']['url'] ) ? $link['link']['url'] : '#' ),
								esc_html( $link['label'] )
							);
						}
						?>
					</span>
				</div>
			</div>
		</footer>
		<?php if ( 'yes' === $s['show_float_wa'] ) : ?>
			<a class="bp-float-wa" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'brickpoint' ); ?>" href="<?php echo esc_url( ! empty( $s['float_wa_link']['url'] ) ? $s['float_wa_link']['url'] : $cta_url ); ?>">
				<?php echo bp_icon( 'whatsapp', array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endif; ?>
		<?php
	}
}
