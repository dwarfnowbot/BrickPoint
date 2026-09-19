<?php
/**
 * Elementor widget: archive page head (title + description + term filters).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Archive_Head_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-archive-head';
	}

	public function get_title() {
		return __( 'BrickPoint Archive Head', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-site-identity';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Archive head', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_filters', array(
			'label'        => __( 'Show category filters', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'filter_taxonomy', array(
			'label'   => __( 'Filter taxonomy', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'auto',
			'options' => array(
				'auto'                => __( 'Auto (current archive)', 'brickpoint' ),
				'bp_product_category' => __( 'Product Categories', 'brickpoint' ),
				'bp_video_category'   => __( 'Video Categories', 'brickpoint' ),
				'bp_project_category' => __( 'Project Categories', 'brickpoint' ),
			),
			'condition' => array( 'show_filters' => 'yes' ),
		) );

		$this->add_control( 'bg_color', array(
			'label'     => __( 'Background', 'brickpoint' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#141210',
			'selectors' => array( '{{WRAPPER}} .bp-pagehead' => 'background: {{VALUE}};' ),
		) );

		$this->add_control( 'bg_image', array(
			'label'       => __( 'Background image', 'brickpoint' ),
			'type'        => Controls_Manager::MEDIA,
			'description' => __( 'Optional banner image behind the title.', 'brickpoint' ),
			'selectors'   => array( '{{WRAPPER}} .bp-pagehead' => 'background-image: url({{URL}}); background-size: cover; background-position: center;' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$taxonomy = 'auto' === $s['filter_taxonomy'] ? $this->bp_current_taxonomy() : $s['filter_taxonomy'];

		$title = get_the_archive_title();
		if ( is_post_type_archive() ) {
			$qo = get_queried_object();
			if ( $qo && ! empty( $qo->labels->name ) ) {
				$title = $qo->labels->name;
			}
		}

		$desc = get_the_archive_description();
		if ( ! $desc && is_tax() ) {
			$desc = get_term_meta( get_queried_object_id(), 'bp_cat_desc', true );
			$desc = $desc ? '<p>' . esc_html( $desc ) . '</p>' : '';
		}
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php echo esc_html( $title ); ?></h1>
				<?php echo wp_kses_post( $desc ); ?>
				<?php if ( 'yes' === $s['show_filters'] && $taxonomy ) : ?>
					<div class="bp-filters">
						<?php
						$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
						if ( $terms && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $t ) {
								printf( '<a href="%s">%s</a>', esc_url( get_term_link( $t ) ), esc_html( $t->name ) );
							}
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}

	protected function bp_current_taxonomy() {
		if ( is_tax( 'bp_product_category' ) ) {
			return 'bp_product_category';
		}
		if ( is_tax( 'bp_video_category' ) ) {
			return 'bp_video_category';
		}
		if ( is_tax( 'bp_project_category' ) ) {
			return 'bp_project_category';
		}
		if ( is_post_type_archive( 'bp_product' ) || is_singular( 'bp_product' ) ) {
			return 'bp_product_category';
		}
		if ( is_post_type_archive( 'bp_video' ) || is_singular( 'bp_video' ) ) {
			return 'bp_video_category';
		}
		if ( is_post_type_archive( 'bp_project' ) || is_singular( 'bp_project' ) ) {
			return 'bp_project_category';
		}
		return '';
	}
}
