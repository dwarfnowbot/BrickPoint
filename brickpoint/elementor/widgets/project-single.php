<?php
/**
 * Elementor widget: single project layout (dynamic).
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class BrickPoint_Project_Single_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bp-project-single';
	}

	public function get_title() {
		return __( 'BrickPoint Single Project', 'brickpoint' );
	}

	public function get_icon() {
		return 'eicon-single-page';
	}

	public function get_categories() {
		return array( 'brickpoint' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'sec_content', array(
			'label' => __( 'Layout', 'brickpoint' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_notice', array(
			'label'        => __( 'Show illustrative notice', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'show_box', array(
			'label'        => __( 'Show details box', 'brickpoint' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = get_the_ID();

		if ( 'bp_project' !== get_post_type( $id ) ) {
			echo '<p class="bp-lead">' . esc_html__( 'This widget displays the current project. Use it inside a Single Project template.', 'brickpoint' ) . '</p>';
			return;
		}

		$loc  = bp_meta( $id, '_bpp_location', '' );
		$link = bp_meta( $id, '_bpp_link', '' );
		$stat = bp_project_status( $id );
		$ill  = bp_meta( $id, '_bpp_illustrative', '1' );
		?>
		<section class="bp-pagehead">
			<div class="bp-container">
				<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( $loc ) : ?><p><?php echo esc_html( '📍 ' . $loc ); ?></p><?php endif; ?>
			</div>
		</section>
		<section class="bp-section">
			<div class="bp-container bp-entry bp-narrow">
				<?php if ( 'yes' === $s['show_notice'] && '1' === $ill ) : ?>
					<p class="bp-notice"><?php esc_html_e( 'Illustrative reference image — shown to communicate the type of construction this material supports.', 'brickpoint' ); ?></p>
				<?php endif; ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<p><?php the_post_thumbnail( 'bp-hero', array( 'class' => 'bp-single-img' ) ); ?></p>
				<?php endif; ?>
				<div class="bp-entry"><?php the_content(); ?></div>
				<?php if ( 'yes' === $s['show_box'] ) : ?>
					<div class="bp-box">
						<h3><?php esc_html_e( 'Project details', 'brickpoint' ); ?></h3>
						<dl>
							<?php if ( $loc ) : ?><div><dt><?php esc_html_e( 'Location', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $loc ); ?></dd></div><?php endif; ?>
							<?php if ( $stat ) : ?><div><dt><?php esc_html_e( 'Status', 'brickpoint' ); ?></dt><dd><?php echo esc_html( $stat ); ?></dd></div><?php endif; ?>
						</dl>
						<div class="bp-card-cta">
							<a class="btn-ghost btn-sm" href="<?php echo esc_url( get_post_type_archive_link( 'bp_project' ) ); ?>">← <?php esc_html_e( 'All Projects', 'brickpoint' ); ?></a>
							<?php if ( $link ) : ?><a class="btn-brick btn-sm" target="_blank" rel="noopener" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Project Link', 'brickpoint' ); ?></a><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
