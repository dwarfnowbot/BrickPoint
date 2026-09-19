<?php
/**
 * Elementor widget: blog cards grid.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Posts_Grid_Widget extends BP_Widget_Base {

	public function get_name() {
		return 'bp-posts-grid';
	}

	public function get_title() {
		return __( 'BrickPoint Blog Grid', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_query', array(
			'label' => __( 'Posts', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'source', array(
			'label'   => __( 'Source', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'latest',
			'options' => array(
				'latest'  => __( 'Latest posts', 'brickpoint' ),
				'archive' => __( 'Current archive query', 'brickpoint' ),
			),
		) );

		$this->add_control( 'count', array(
			'label'   => __( 'Number of posts', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 3,
			'min'     => 1,
			'max'     => 24,
		) );

		$this->add_control( 'category', array(
			'label'       => __( 'Category', 'brickpoint' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => $this->bp_cat_options(),
			'label_block' => true,
			'condition'   => array( 'source' => 'latest' ),
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
			'eyebrow' => __( 'From the Blog', 'brickpoint' ),
			'title'   => __( 'Build smarter with BrickPoint guides', 'brickpoint' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_card', array(
			'label' => __( 'Card Content', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_date', array(
			'label'        => __( 'Show date', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_author', array(
			'label'        => __( 'Show author', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_excerpt', array(
			'label'        => __( 'Show excerpt', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'link_label', array(
			'label'   => __( 'Read more label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Read More', 'brickpoint' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'sec_grid', array(
			'label' => __( 'Grid', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->bp_grid_controls( 3 );

		$this->end_controls_section();
	}

	protected function bp_cat_options() {
		$options = array();
		$cats    = get_categories( array( 'hide_empty' => false ) );
		foreach ( $cats as $c ) {
			$options[ $c->term_id ] = $c->name;
		}
		return $options;
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'yes' === $s['show_heading'] ) {
			echo $this->bp_render_heading(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) ( $s['count'] ?: 3 ),
			'ignore_sticky_posts' => true,
		);

		if ( ! empty( $s['category'] ) ) {
			$args['category__in'] = (array) $s['category'];
		}

		$q = ( 'archive' === $s['source'] ) ? $GLOBALS['wp_query'] : new \WP_Query( $args );

		if ( ! $q->have_posts() ) {
			echo '<p class="bp-lead">' . esc_html__( 'No posts yet.', 'brickpoint' ) . '</p>';
			return;
		}

		echo '<div class="' . esc_attr( $this->bp_grid_class() ) . ' bp-post-grid">';
		while ( $q->have_posts() ) :
			$q->the_post();
			?>
			<article class="bp-card">
				<a class="bp-card-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'bp-card', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
				</a>
				<div class="bp-card-body">
					<?php if ( 'yes' === $s['show_date'] ) : ?>
						<span class="bp-card-cat bp-post-date"><?php echo esc_html( get_the_date() ); ?></span>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( 'yes' === $s['show_excerpt'] ) : ?><p><?php echo esc_html( bp_excerpt( 16 ) ); ?></p><?php endif; ?>
					<?php if ( 'yes' === $s['show_author'] ) : ?>
						<div class="bp-author-row">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?>
							<span class="entry-meta"><?php the_author(); ?></span>
						</div>
					<?php endif; ?>
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
