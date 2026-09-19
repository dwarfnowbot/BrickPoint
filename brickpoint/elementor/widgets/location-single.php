<?php
/**
 * Elementor widget: single location (bhatta) layout (dynamic).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Location_Single_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-location-single';
	}

	public function get_title() {
		return __( 'BrickPoint Single Location', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_box', array(
			'label'        => __( 'Show contact box', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'wa_label', array(
			'label'   => __( 'WhatsApp label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'WhatsApp This Bhatta', 'brickpoint' ),
		) );

		$this->add_control( 'maps_label', array(
			'label'   => __( 'Directions label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Get Directions', 'brickpoint' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = get_the_ID();

		if ( 'bp_location' !== get_post_type( $id ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'This widget displays the current location. Use it inside a Single Location template.', 'brickpoint' ) . '</p>';
			return;
		}

		$address = bp_meta( $id, '_bpl_address', '' );
		$maps    = bp_meta( $id, '_bpl_maps', '' );
		$phone   = bp_meta( $id, '_bpl_phone', bp_phone_display() );
		$hours   = bp_meta( $id, '_bpl_hours', '' );
		$wa      = preg_replace( '/\D/', '', bp_meta( $id, '_bpl_whatsapp', bp_phone_intl() ) );
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section class="bp-section">
			<div class="bp-container bp-entry bp-narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
				<?php endif; ?>
				<div class="bp-entry"><?php the_content(); ?></div>
				<?php if ( 'yes' === $s['show_box'] ) : ?>
					<div class="bp-box">
						<h3><?php esc_html_e( 'Visit this bhatta', 'brickpoint' ); ?></h3>
						<?php if ( $address ) : ?><p>📍 <?php echo esc_html( $address ); ?></p><?php endif; ?>
						<?php if ( $hours ) : ?><p>🕐 <?php echo esc_html( $hours ); ?></p><?php endif; ?>
						<?php if ( $phone ) : ?><p>📞 <a href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p><?php endif; ?>
						<div class="bp-card-cta">
							<?php if ( $maps ) : ?>
								<a class="btn-ghost btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $maps ); ?>"><?php echo esc_html( $s['maps_label'] ); ?></a>
							<?php endif; ?>
							<a class="btn-whatsapp btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( bp_whatsapp_url( sprintf( /* translators: %s: location name */ __( 'Assalam-o-Alaikum BrickPoint, I need bricks delivered from %s.', 'brickpoint' ), get_the_title() ), $wa ) ); ?>"><?php echo esc_html( $s['wa_label'] ); ?></a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
