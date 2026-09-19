<?php
/**
 * Elementor widget: single blog post layout (dynamic) with related posts.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Post_Single_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-post-single';
	}

	public function get_title() {
		return __( 'BrickPoint Single Post', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_thumbnail', array(
			'label'        => __( 'Show featured image', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_meta', array(
			'label'        => __( 'Show author + date', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_tags', array(
			'label'        => __( 'Show tags', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'related_count', array(
			'label'   => __( 'Related posts', 'brickpoint' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 3,
			'min'     => 0,
			'max'     => 6,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = get_the_ID();

		if ( 'post' !== get_post_type( $id ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'This widget displays the current blog post. Use it inside a Single Post template.', 'brickpoint' ) . '</p>';
			return;
		}
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( 'yes' === $s['show_meta'] ) : ?>
					<?php brickpoint_posted_meta(); ?>
				<?php endif; ?>
			</div>
		</section>
		<section class="bp-section">
			<div class="bp-container bp-entry bp-narrow">
				<?php if ( 'yes' === $s['show_thumbnail'] && has_post_thumbnail() ) : ?>
					<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php if ( 'yes' === $s['show_tags'] && has_tag() ) : ?>
					<p class="bp-tags"><?php the_tags( '🏷 ', ', ' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
		$related = (int) $s['related_count'];
		if ( $related > 0 ) {
			$cats = wp_get_post_categories( $id );
			if ( $cats ) {
				$q = new \WP_Query( array(
					'category__in'       => $cats,
					'posts_per_page'     => $related,
					'post__not_in'       => array( $id ),
					'ignore_sticky_posts' => true,
				) );
				if ( $q->have_posts() ) {
					echo '<section class="bp-section" style="padding-top:0"><div class="bp-container"><h2>' . esc_html__( 'Related Articles', 'brickpoint' ) . '</h2><div class="bp-grid cols-3 bp-mt bp-post-grid">';
					while ( $q->have_posts() ) {
						$q->the_post();
						get_template_part( 'template-parts/content' );
					}
					echo '</div></div></section>';
				}
				wp_reset_postdata();
			}
		}
	}
}
