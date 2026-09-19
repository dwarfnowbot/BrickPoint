<?php
/**
 * Elementor widget: single product layout (dynamic content).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Product_Single_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-product-single';
	}

	public function get_title() {
		return __( 'BrickPoint Single Product', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_badges', array(
			'label'        => __( 'Show badges', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_specs', array(
			'label'        => __( 'Show specifications box', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_features', array(
			'label'        => __( 'Show features box', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_thumbs', array(
			'label'        => __( 'Show gallery thumbnails', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'wa_label', array(
			'label'   => __( 'WhatsApp CTA label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Order on WhatsApp', 'brickpoint' ),
		) );

		$this->add_control( 'all_label', array(
			'label'   => __( 'All products label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'All Products', 'brickpoint' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = get_the_ID();

		if ( 'bp_product' !== get_post_type( $id ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'This widget displays the current product. Use it inside a Single Product template.', 'brickpoint' ) . '</p>';
			return;
		}

		$price   = bp_meta( $id, '_bp_price', '' );
		$unit    = bp_meta( $id, '_bp_unit', '' );
		$short   = bp_meta( $id, '_bp_short', '' );
		$specs   = bp_meta( $id, '_bp_specs', '' );
		$feats   = bp_meta( $id, '_bp_features', '' );
		$badge   = bp_meta( $id, '_bp_badge', '' );
		$avail   = bp_meta( $id, '_bp_availability', '' );
		$gallery = array_filter( preg_split( '/\r\n|\r|\n/', (string) bp_meta( $id, '_bp_gallery', '' ) ) );
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( $short ) : ?><p><?php echo esc_html( $short ); ?></p><?php endif; ?>
			</div>
		</section>
		<section>
			<div class="bp-container bp-product-layout">
				<div class="bp-product-gallery">
					<?php if ( has_post_thumbnail() ) : ?>
						<img class="bp-product-img" id="bpProductMain" src="<?php echo esc_url( get_the_post_thumbnail_url( $id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_thumbs'] && $gallery ) : ?>
						<div class="bp-thumbs">
							<?php foreach ( $gallery as $g ) : ?>
								<img src="<?php echo esc_url( $g ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="bp-product-info">
					<?php if ( 'yes' === $s['show_badges'] && ( $badge || $avail ) ) : ?>
						<p>
							<?php if ( $badge ) : ?><span class="bp-badge" style="position:static;display:inline-block"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
							<?php if ( $avail ) : ?><span class="bp-avail" style="position:static;display:inline-block"><?php echo esc_html( $avail ); ?></span><?php endif; ?>
						</p>
					<?php endif; ?>
					<?php if ( $price ) : ?>
						<div class="bp-price-tag">
							<span class="bp-price"><?php echo esc_html( $price ); ?></span>
							<?php if ( $unit ) : ?><span class="bp-unit"><?php echo esc_html( $unit ); ?></span><?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $short ) : ?><p class="bp-lead" style="max-width:none"><?php echo esc_html( $short ); ?></p><?php endif; ?>
					<div class="bp-entry"><?php the_content(); ?></div>
					<div class="bp-product-cta" style="grid-template-columns:1fr 1fr">
						<?php echo bp_whatsapp_button( bp_product_whatsapp_message( $id ), $s['wa_label'], 'btn-whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a class="btn-brick" href="<?php echo esc_url( get_post_type_archive_link( 'bp_product' ) ); ?>"><?php echo esc_html( $s['all_label'] ); ?></a>
					</div>
					<?php if ( 'yes' === $s['show_specs'] && $specs ) : ?>
						<div class="bp-box">
							<h3><?php esc_html_e( 'Specifications', 'brickpoint' ); ?></h3>
							<dl>
								<?php
								foreach ( preg_split( '/\r\n|\r|\n/', $specs ) as $line ) :
									$parts = array_map( 'trim', explode( ':', $line, 2 ) );
									if ( count( $parts ) !== 2 ) {
										continue;
									}
									?>
									<div><dt><?php echo esc_html( $parts[0] ); ?></dt><dd><?php echo esc_html( $parts[1] ); ?></dd></div>
								<?php endforeach; ?>
							</dl>
						</div>
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_features'] && $feats ) : ?>
						<div class="bp-box">
							<h3><?php esc_html_e( 'Why buyers choose it', 'brickpoint' ); ?></h3>
							<ul>
								<?php foreach ( preg_split( '/\r\n|\r|\n/', $feats ) as $line ) : ?>
									<li><?php echo esc_html( $line ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
