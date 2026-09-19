<?php
/**
 * Elementor widget: single video layout (dynamic).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Video_Single_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-video-single';
	}

	public function get_title() {
		return __( 'BrickPoint Single Video', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-video';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'width', array(
			'label'   => __( 'Player width', 'brickpoint' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'bp-narrow',
			'options' => array(
				'bp-narrow' => __( 'Narrow (readable)', 'brickpoint' ),
				''          => __( 'Full container', 'brickpoint' ),
			),
		) );

		$this->add_control( 'all_label', array(
			'label'   => __( 'Back label', 'brickpoint' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'All Videos', 'brickpoint' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = get_the_ID();

		if ( 'bp_video' !== get_post_type( $id ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'This widget displays the current video. Use it inside a Single Video template.', 'brickpoint' ) . '</p>';
			return;
		}
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section class="bp-section">
			<div class="bp-container <?php echo esc_attr( $s['width'] ); ?>">
				<h1 style="font-family:Archivo;font-size:clamp(1.6rem,3vw,2.2rem)"><?php the_title(); ?></h1>
				<p class="entry-meta"><?php echo esc_html( get_the_date() ); ?></p>
				<?php echo bp_video_embed_html( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with esc_url internally; kses strips <source>. ?>
				<?php if ( ! bp_video_embed_html( $id ) && has_post_thumbnail() ) : ?>
					<p><?php the_post_thumbnail( 'bp-video', array( 'class' => 'bp-single-img' ) ); ?></p>
				<?php endif; ?>
				<div class="bp-entry"><?php the_content(); ?></div>
				<p><a class="btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'bp_video' ) ); ?>">← <?php echo esc_html( $s['all_label'] ); ?></a></p>
			</div>
		</section>
		<?php
	}
}
