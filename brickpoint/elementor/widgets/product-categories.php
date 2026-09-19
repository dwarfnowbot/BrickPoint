<?php
/**
 * Elementor widget: product categories grid.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Product_Categories_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-product-categories';
	}

	public function get_title() {
		return __( 'BrickPoint Product Categories', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Categories', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of categories', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 8,
			'min'     => 1,
			'max'     => 20,
		) );

		$this->add_control( 'show_heading', array(
			'label'        => __( 'Show heading', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->bp_heading_controls( '', array(
			'eyebrow' => __( 'Why BrickPoint', 'brickpoint' ),
			'title'   => __( 'A construction-materials partner you can build on', 'brickpoint' ),
		) );

		$this->add_control( 'show_wa', array(
			'label'        => __( 'Show WhatsApp quote buttons under grid', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_grid', array(
			'label' => __( 'Grid', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->bp_grid_controls( 4 );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'yes' === $s['show_heading'] ) {
			echo $this->bp_render_heading(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		$terms = get_terms( array(
			'taxonomy'   => 'bp_product_category',
			'number'     => (int) ( $s['count'] ?: 8 ),
			'hide_empty' => false,
		) );

		if ( ! $terms || is_wp_error( $terms ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'No product categories yet.', 'brickpoint' ) . '</p>';
			return;
		}

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . '">';
		foreach ( $terms as $t ) {
			$img     = get_term_meta( $t->term_id, 'bp_cat_image', true );
			$desc    = get_term_meta( $t->term_id, 'bp_cat_desc', true );
			$img_id  = (int) get_term_meta( $t->term_id, 'bp_cat_image_id', true );
			if ( ! $img && $img_id ) {
				$img = wp_get_attachment_image_url( $img_id, 'bp-card-wide' );
			}
			?>
			<a class="bp-cat-card reveal" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
				<?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $t->name ); ?>" loading="lazy" /><?php endif; ?>
				<span class="bp-cat-card-body">
					<strong><?php echo esc_html( $t->name ); ?></strong>
					<?php if ( $desc ) : ?><small><?php echo esc_html( wp_trim_words( $desc, 10, '…' ) ); ?></small><?php endif; ?>
				</span>
			</a>
			<?php
		}
		echo '</div>';

		if ( 'yes' === $s['show_wa'] ) {
			$first = $terms[0];
			printf(
				'<p class="bp-center bp-mt"><a class="btn-whatsapp" target="_blank" rel="noopener" href="%s">%s</a></p>',
				esc_url( bp_whatsapp_url( bp_category_whatsapp_message( $first->name ) ) ),
				esc_html__( 'Ask for a quotation', 'brickpoint' )
			);
		}
	}
}
