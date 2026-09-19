<?php
/**
 * Elementor widget: project grid.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Project_Grid_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-project-grid';
	}

	public function get_title() {
		return __( 'BrickPoint Project Grid', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_query', array(
			'label' => __( 'Projects', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'source', array(
			'label'   => __( 'Source', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'latest',
			'options' => array(
				'latest'  => __( 'Latest projects', 'brickpoint' ),
				'featured' => __( 'Featured projects', 'brickpoint' ),
				'archive' => __( 'Current archive query', 'brickpoint' ),
			),
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of projects', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 6,
			'min'     => 1,
			'max'     => 24,
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

		$this->add_control( 'show_heading', array(
			'label'        => __( 'Show heading', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->bp_heading_controls( '', array(
			'eyebrow' => __( 'Projects', 'brickpoint' ),
			'title'   => __( 'Built with BrickPoint materials', 'brickpoint' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_card', array(
			'label' => __( 'Card Content', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_location', array(
			'label'        => __( 'Show location', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_excerpt', array(
			'label'        => __( 'Show description', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_illustrative', array(
			'label'        => __( 'Show "Illustrative" badge', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'link_label', array(
			'label'   => __( 'Card link label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'View Project', 'brickpoint' ),
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

		$args = array(
			'post_type'           => 'bp_project',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) ( $s['count'] ?: 6 ),
			'ignore_sticky_posts' => true,
			'orderby'             => 'meta_value_num title',
			'meta_key'            => '_bpp_sort',
			'order'               => 'ASC',
		);

		if ( 'featured' === $s['source'] ) {
			$args['meta_query'] = array( array( 'key' => '_bpp_featured', 'value' => '1' ) );
		}

		$q = ( 'archive' === $s['source'] ) ? $GLOBALS['wp_query'] : new \WP_Query( $args );

		if ( ! $q->have_posts() ) {
			echo '<p class="bp-lead">' . esc_html__( 'No projects yet. Add projects in WordPress → Projects.', 'brickpoint' ) . '</p>';
			return;
		}

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . '">';
		while ( $q->have_posts() ) :
			$q->the_post();
			$id    = get_the_ID();
			$loc   = bp_meta( $id, '_bpp_location', '' );
			$ill   = bp_meta( $id, '_bpp_illustrative', '1' );
			$cats  = get_the_terms( $id, 'bp_project_category' );
			?>
			<article class="bp-card">
				<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_illustrative'] && '1' === $ill ) : ?>
						<span class="bp-illus"><?php esc_html_e( 'Illustrative', 'brickpoint' ); ?></span>
					<?php endif; ?>
				</a>
				<div class="bp-card-body">
					<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
						<span class="bp-card-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( 'yes' === $s['show_location'] && $loc ) : ?>
						<p><?php echo bp_icon( 'pin', array( 'width' => 12, 'height' => 12, 'fill' => '#ea580c', 'style' => 'vertical-align:-1px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $loc ); ?></p>
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_excerpt'] ) : ?><p><?php echo esc_html( bp_excerpt( 14 ) ); ?></p><?php endif; ?>
					<div class="bp-card-cta">
						<?php if ( $s['link_label'] ) : ?>
							<a class="btn-ghost btn-sm" href="<?php the_permalink(); ?>"><?php echo esc_html( $s['link_label'] ); ?></a>
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

		if ( 'archive' !== $s['source'] ) {
			wp_reset_postdata();
		}
	}
}
