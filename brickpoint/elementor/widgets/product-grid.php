<?php
/**
 * Elementor widget: product grid with full card styling controls.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Product_Grid_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-product-grid';
	}

	public function get_title() {
		return __( 'BrickPoint Product Grid', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return array( 'product', 'grid', 'bricks', 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_query', array(
			'label' => __( 'Products', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'source', array(
			'label'   => __( 'Source', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'featured',
			'options' => array(
				'featured' => __( 'Featured products', 'brickpoint' ),
				'latest'   => __( 'Latest products', 'brickpoint' ),
				'category' => __( 'From category', 'brickpoint' ),
				'archive'  => __( 'Current archive query', 'brickpoint' ),
			),
		) );

		$this->add_control( 'category', array(
			'label'     => __( 'Category', 'brickpoint' ),
			'type'      => Controls_Manager::SELECT2,
			'multiple'  => true,
			'options'   => $this->bp_term_options(),
			'condition' => array( 'source' => array( 'category', 'featured' ) ),
			'label_block' => true,
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of products', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 8,
			'min'     => 1,
			'max'     => 24,
			'conditions' => array( 'relation' => 'or', 'terms' => array(
				array( 'name' => 'source', 'operator' => '!==', 'value' => 'archive' ),
			) ),
		) );

		$this->add_control( 'show_pagination', array(
			'label'        => __( 'Pagination (archive source)', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'condition'    => array( 'source' => 'archive' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_head', array(
			'label' => __( 'Section Heading', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->bp_heading_controls( '', array(
			'eyebrow' => __( 'Featured Products', 'brickpoint' ),
			'title'   => __( 'Materials contractors ask for by name', 'brickpoint' ),
		) );

		$this->add_control( 'show_heading', array(
			'label'        => __( 'Show heading', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_cta', array(
			'label' => __( 'Card Content', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_category', array(
			'label'        => __( 'Show category', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );
		$this->add_control( 'show_excerpt', array(
			'label'        => __( 'Show short description', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );
		$this->add_control( 'show_price', array(
			'label'        => __( 'Show price', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );
		$this->add_control( 'show_badges', array(
			'label'        => __( 'Show badges (featured/availability)', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );
		$this->add_control( 'wa_label', array(
			'label'   => __( 'WhatsApp button label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Order on WhatsApp', 'brickpoint' ),
		) );
		$this->add_control( 'details_label', array(
			'label'   => __( 'Details link label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Details', 'brickpoint' ),
		) );
		$this->add_control( 'show_all_btn', array(
			'label'        => __( 'Show "Browse All" button below', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
		) );
		$this->add_control( 'all_btn_label', array(
			'label'     => __( 'Browse All label', 'brickpoint' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => __( 'Browse All Products', 'brickpoint' ),
			'condition' => array( 'show_all_btn' => 'yes' ),
		) );
		$this->add_control( 'all_btn_link', array(
			'label'     => __( 'Browse All link', 'brickpoint' ),
			'type'      => Controls_Manager::URL,
			'default'   => array( 'url' => get_post_type_archive_link( 'bp_product' ) ),
			'condition' => array( 'show_all_btn' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_grid', array(
			'label' => __( 'Grid Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->bp_grid_controls( 4 );

		$this->add_control( 'card_radius', array(
			'label'      => __( 'Card radius', 'brickpoint' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
			'selectors'  => array( '{{WRAPPER}} .bp-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
		) );

		$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), array(
			'name'     => 'card_shadow',
			'label'    => __( 'Card hover shadow', 'brickpoint' ),
			'selector' => '{{WRAPPER}} .bp-card:hover',
		) );

		$this->add_responsive_control( 'image_ratio', array(
			'label'   => __( 'Image ratio', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '4/3',
			'options' => array(
				'4/3'   => '4:3',
				'1/1'   => '1:1',
				'3/4'   => '3:4',
				'16/9'  => '16:9',
			),
			'selectors' => array( '{{WRAPPER}} .bp-card-media' => 'aspect-ratio: {{VALUE}};' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_typo', array(
			'label' => __( 'Card Typography', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array(
			'name'     => 'title_typo',
			'label'    => __( 'Title', 'brickpoint' ),
			'selector' => '{{WRAPPER}} .bp-card-body h3',
		) );

		$this->add_control( 'title_color', array(
			'label'     => __( 'Title color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .bp-card-body h3 a' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'price_color', array(
			'label'     => __( 'Price color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .bp-price' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'text_color', array(
			'label'     => __( 'Description color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .bp-card-body p' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'cat_color', array(
			'label'     => __( 'Category label color', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .bp-card-cat' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function bp_term_options() {
		$options = array();
		$terms   = get_terms( array( 'taxonomy' => 'bp_product_category', 'hide_empty' => false ) );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$options[ $t->term_id ] = $t->name;
			}
		}
		return $options;
	}

	protected function bp_query() {
		$s    = $this->get_settings_for_display();
		$args = array(
			'post_type'           => 'bp_product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		);

		if ( 'archive' === $s['source'] ) {
			return $GLOBALS['wp_query'];
		}

		$args['posts_per_page'] = (int) ( $s['count'] ?: 8 );

		if ( 'featured' === $s['source'] ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array( 'key' => '_bp_featured', 'value' => '1' ),
				array( 'key' => '_bp_featured', 'compare' => 'NOT EXISTS' ),
			);
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_bp_sort';
			$args['order'] = 'ASC';
		}

		if ( ! empty( $s['category'] ) ) {
			$args['tax_query'] = array( array(
				'taxonomy' => 'bp_product_category',
				'field'    => 'term_id',
				'terms'    => (array) $s['category'],
			) );
		}

		return new \WP_Query( $args );
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$q = $this->bp_query();

		if ( $q instanceof \WP_Query && ! $q->have_posts() && 'featured' === $s['source'] ) {
			$q = new \WP_Query( array(
				'post_type'           => 'bp_product',
				'post_status'         => 'publish',
				'posts_per_page'      => (int) ( $s['count'] ?: 8 ),
				'ignore_sticky_posts' => true,
			) );
		}

		echo $this->bp_render_heading(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! $q->have_posts() ) {
			echo '<p class="bp-lead">' . esc_html__( 'No products yet. Add products in WordPress → Products.', 'brickpoint' ) . '</p>';
			return;
		}

		$show_cat    = 'yes' === $s['show_category'];
		$show_price  = 'yes' === $s['show_price'];
		$show_badges = 'yes' === $s['show_badges'];
		$show_ex     = 'yes' === $s['show_excerpt'];

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . '">';
		while ( $q->have_posts() ) :
			$q->the_post();
			$id        = get_the_ID();
			$price     = bp_meta( $id, '_bp_price', '' );
			$unit      = bp_meta( $id, '_bp_unit', '' );
			$badge     = bp_meta( $id, '_bp_badge', '' );
			$avail     = bp_meta( $id, '_bp_availability', '' );
			$cats      = get_the_terms( $id, 'bp_product_category' );
			$cat_names = ( $cats && ! is_wp_error( $cats ) ) ? wp_list_pluck( $cats, 'name' ) : array();
			?>
			<article class="bp-card">
				<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
					<?php if ( $show_badges && $badge ) : ?><span class="bp-badge"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
					<?php if ( $show_badges && $avail ) : ?><span class="bp-avail"><?php echo esc_html( $avail ); ?></span><?php endif; ?>
				</a>
				<div class="bp-card-body">
					<?php if ( $show_cat && $cat_names ) : ?><span class="bp-card-cat"><?php echo esc_html( implode( ' • ', $cat_names ) ); ?></span><?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( $show_ex ) : ?><p><?php echo esc_html( bp_excerpt( 14 ) ); ?></p><?php endif; ?>
					<?php if ( $show_price && $price ) : ?>
						<span class="bp-price"><?php echo esc_html( $price ); ?></span>
						<?php if ( $unit ) : ?><span class="bp-unit"><?php echo esc_html( $unit ); ?></span><?php endif; ?>
					<?php endif; ?>
					<div class="bp-card-cta">
						<?php echo bp_whatsapp_button( bp_product_whatsapp_message( $id ), $s['wa_label'], 'btn-whatsapp btn-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $s['details_label'] ) : ?>
							<a class="btn-ghost btn-sm" href="<?php the_permalink(); ?>"><?php echo esc_html( $s['details_label'] ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</article>
			<?php
		endwhile;
		echo '</div>';

		if ( 'archive' === $s['source'] && 'yes' === $s['show_pagination'] ) {
			brickpoint_pagination();
		}

		if ( 'yes' === $s['show_all_btn'] ) {
			printf(
				'<p class="bp-center bp-mt"><a class="btn-brick" href="%s">%s</a></p>',
				esc_url( ! empty( $s['all_btn_link']['url'] ) ? $s['all_btn_link']['url'] : get_post_type_archive_link( 'bp_product' ) ),
				esc_html( $s['all_btn_label'] )
			);
		}

		if ( 'archive' !== $s['source'] ) {
			wp_reset_postdata();
		}
	}
}
