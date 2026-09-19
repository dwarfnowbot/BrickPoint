<?php
/**
 * Elementor widget: video grid with play overlays.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Video_Grid_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-video-grid';
	}

	public function get_title() {
		return __( 'BrickPoint Video Grid', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-video-playlist';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_query', array(
			'label' => __( 'Videos', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'source', array(
			'label'   => __( 'Source', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'latest',
			'options' => array(
				'latest'  => __( 'Latest videos', 'brickpoint' ),
				'featured' => __( 'Featured videos', 'brickpoint' ),
				'archive' => __( 'Current archive query', 'brickpoint' ),
			),
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of videos', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 3,
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
			'eyebrow' => __( 'Inside BrickPoint', 'brickpoint' ),
			'title'   => __( 'See the Strength Behind Every Brick', 'brickpoint' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_card', array(
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
			'label'        => __( 'Show description', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_duration', array(
			'label'        => __( 'Show duration badge', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_featured_badge', array(
			'label'        => __( 'Show "Featured" badge', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_all_btn', array(
			'label'        => __( 'Show "View All Videos" button', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'all_btn_label', array(
			'label'     => __( 'Button label', 'brickpoint' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => __( 'View All Videos', 'brickpoint' ),
			'condition' => array( 'show_all_btn' => 'yes' ),
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
			'post_type'           => 'bp_video',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) ( $s['count'] ?: 3 ),
			'ignore_sticky_posts' => true,
		);

		if ( 'featured' === $s['source'] ) {
			$args['meta_query'] = array( array( 'key' => '_bpv_featured', 'value' => '1' ) );
		}

		$q = ( 'archive' === $s['source'] ) ? $GLOBALS['wp_query'] : new \WP_Query( $args );

		if ( ! $q->have_posts() ) {
			echo '<p class="bp-lead">' . esc_html__( 'No videos yet. Add videos in WordPress → Videos.', 'brickpoint' ) . '</p>';
			return;
		}

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . '">';
		while ( $q->have_posts() ) :
			$q->the_post();
			$id        = get_the_ID();
			$duration  = bp_meta( $id, '_bpv_duration', '' );
			$featured  = bp_meta( $id, '_bpv_featured', '0' );
			$cats      = get_the_terms( $id, 'bp_video_category' );
			?>
			<article class="bp-card">
				<a class="bp-video-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'bp-video', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
					<span class="bp-play-btn" aria-hidden="true"><?php echo bp_icon( 'play', array( 'width' => 20, 'height' => 20, 'fill' => 'currentColor' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php if ( 'yes' === $s['show_duration'] && $duration ) : ?><span class="bp-duration"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
					<?php if ( 'yes' === $s['show_featured_badge'] && '1' === $featured ) : ?><span class="bp-feat"><?php esc_html_e( 'Featured', 'brickpoint' ); ?></span><?php endif; ?>
				</a>
				<div class="bp-card-body">
					<?php if ( 'yes' === $s['show_category'] && $cats && ! is_wp_error( $cats ) ) : ?>
						<span class="bp-card-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( 'yes' === $s['show_excerpt'] ) : ?><p><?php echo esc_html( bp_excerpt( 16 ) ); ?></p><?php endif; ?>
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
				'<p class="bp-center bp-mt"><a class="btn-ghost" href="%s">%s</a></p>',
				esc_url( get_post_type_archive_link( 'bp_video' ) ),
				esc_html( $s['all_btn_label'] )
			);
		}

		if ( 'archive' !== $s['source'] ) {
			wp_reset_postdata();
		}
	}
}
