<?php
/**
 * Elementor widget: location (bhatta) cards.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Location_Cards_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-location-cards';
	}

	public function get_title() {
		return __( 'BrickPoint Locations', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_query', array(
			'label' => __( 'Locations', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of locations', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 6,
			'min'     => 1,
			'max'     => 24,
		) );

		$this->add_control( 'show_heading', array(
			'label'        => __( 'Show heading', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->bp_heading_controls( '', array(
			'eyebrow' => __( 'Our Bhattas', 'brickpoint' ),
			'title'   => __( 'Pick up or get delivery from a yard near you', 'brickpoint' ),
		) );

		$this->add_control( 'wa_label', array(
			'label'   => __( 'WhatsApp label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'WhatsApp', 'brickpoint' ),
		) );

		$this->add_control( 'maps_label', array(
			'label'   => __( 'Directions label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Get Directions', 'brickpoint' ),
		) );

		$this->add_control( 'show_phone', array(
			'label'        => __( 'Show phone', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_hours', array(
			'label'        => __( 'Show opening hours', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_grid', array(
			'label' => __( 'Grid', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->bp_grid_controls( 3 );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'yes' === $s['show_heading'] ) {
			echo $this->bp_render_heading(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		$q = new \WP_Query( array(
			'post_type'           => 'bp_location',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) ( $s['count'] ?: 6 ),
			'orderby'             => 'meta_value_num title',
			'meta_key'            => '_bpl_order',
			'order'               => 'ASC',
			'ignore_sticky_posts' => true,
		) );

		if ( ! $q->have_posts() ) {
			echo '<p class="bp-lead">' . esc_html__( 'No locations yet. Add locations in WordPress → Locations.', 'brickpoint' ) . '</p>';
			return;
		}

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . '">';
		while ( $q->have_posts() ) :
			$q->the_post();
			$id      = get_the_ID();
			$address = bp_meta( $id, '_bpl_address', '' );
			$maps    = bp_meta( $id, '_bpl_maps', '' );
			$phone   = bp_meta( $id, '_bpl_phone', bp_phone_display() );
			$hours   = bp_meta( $id, '_bpl_hours', '' );
			$wa      = preg_replace( '/\D/', '', bp_meta( $id, '_bpl_whatsapp', bp_phone_intl() ) );
			?>
			<article class="bp-loc-card">
				<div class="bp-loc-media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'bp-card-wide', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
				</div>
				<div class="bp-loc-body">
					<h3><?php the_title(); ?></h3>
					<?php if ( $address ) : ?><p><?php echo esc_html( $address ); ?></p><?php endif; ?>
					<?php if ( 'yes' === $s['show_hours'] && $hours ) : ?><p><?php echo esc_html( $hours ); ?></p><?php endif; ?>
					<?php if ( 'yes' === $s['show_phone'] && $phone ) : ?>
						<p>📞 <a href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<div class="bp-loc-actions">
						<?php if ( $maps ) : ?>
							<a class="btn-ghost btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $maps ); ?>"><?php echo esc_html( $s['maps_label'] ); ?></a>
						<?php endif; ?>
						<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( sprintf( /* translators: %s: location name */ __( 'Assalam-o-Alaikum BrickPoint, I need bricks delivered from %s.', 'brickpoint' ), get_the_title() ), $wa ) ); ?>"><?php echo esc_html( $s['wa_label'] ); ?></a>
					</div>
				</div>
			</article>
			<?php
		endwhile;
		echo '</div>';
		wp_reset_postdata();
	}
}
