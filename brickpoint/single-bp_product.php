<?php
/**
 * Single product.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$bp_price  = bp_meta( get_the_ID(), '_bp_price', '' );
	$bp_unit   = bp_meta( get_the_ID(), '_bp_unit', '' );
	$bp_short  = bp_meta( get_the_ID(), '_bp_short', '' );
	$bp_specs  = bp_meta( get_the_ID(), '_bp_specs', '' );
	$bp_feats  = bp_meta( get_the_ID(), '_bp_features', '' );
	$bp_badge  = bp_meta( get_the_ID(), '_bp_badge', '' );
	$bp_avail  = bp_meta( get_the_ID(), '_bp_availability', '' );
	$bp_price_label = bp_meta( get_the_ID(), '_bp_price_label', '' );
	?>
	<section class="bp-pagehead">
		<div class="bp-container">
			<?php echo wp_kses_post( bp_breadcrumbs() ); ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( $bp_short ) : ?><p><?php echo esc_html( $bp_short ); ?></p><?php endif; ?>
		</div>
	</section>
	<section>
		<div class="bp-container bp-product-layout">
			<div class="bp-product-gallery">
				<?php if ( has_post_thumbnail() ) : ?>
					<img class="bp-product-img" id="bpProductMain" src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
				<?php endif; ?>
				<?php
				$bp_gallery = array_filter( preg_split( '/\r\n|\r|\n/', (string) bp_meta( get_the_ID(), '_bp_gallery', '' ) ) );
				if ( $bp_gallery ) :
					?>
					<div class="bp-thumbs">
						<?php foreach ( $bp_gallery as $bp_g ) : ?>
							<img src="<?php echo esc_url( $bp_g ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="bp-product-info">
				<?php if ( $bp_badge || $bp_avail ) : ?>
					<p>
						<?php if ( $bp_badge ) : ?><span class="bp-badge" style="position:static;display:inline-block"><?php echo esc_html( $bp_badge ); ?></span> <?php endif; ?>
						<?php if ( $bp_avail ) : ?><span class="bp-avail" style="position:static;display:inline-block"><?php echo esc_html( $bp_avail ); ?></span><?php endif; ?>
					</p>
				<?php endif; ?>
				<?php if ( $bp_price ) : ?>
					<div class="bp-price-tag">
						<span class="bp-price"><?php echo esc_html( $bp_price ); ?></span>
						<?php if ( $bp_unit ) : ?><span class="bp-unit"><?php echo esc_html( $bp_unit ); ?></span><?php endif; ?>
					</div>
					<?php if ( $bp_price_label ) : ?><span class="bp-price-label"><?php echo esc_html( $bp_price_label ); ?></span><?php endif; ?>
				<?php endif; ?>
				<div class="bp-entry"><?php the_content(); ?></div>
				<div class="bp-product-cta">
					<?php echo bp_whatsapp_button( bp_product_whatsapp_message( get_the_ID() ), null, 'btn-whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<a class="btn-brick" href="<?php echo esc_url( get_post_type_archive_link( 'bp_product' ) ); ?>"><?php esc_html_e( 'All Products', 'brickpoint' ); ?></a>
				</div>
				<?php if ( $bp_specs ) : ?>
					<div class="bp-box">
						<h3><?php esc_html_e( 'Specifications', 'brickpoint' ); ?></h3>
						<dl>
							<?php
							foreach ( preg_split( '/\r\n|\r|\n/', $bp_specs ) as $bp_line ) :
								$bp_parts = array_map( 'trim', explode( ':', $bp_line, 2 ) );
								if ( count( $bp_parts ) !== 2 ) {
									continue;
								}
								?>
								<div><dt><?php echo esc_html( $bp_parts[0] ); ?></dt><dd><?php echo esc_html( $bp_parts[1] ); ?></dd></div>
							<?php endforeach; ?>
						</dl>
					</div>
				<?php endif; ?>
				<?php if ( $bp_feats ) : ?>
					<div class="bp-box">
						<h3><?php esc_html_e( 'Why buyers choose it', 'brickpoint' ); ?></h3>
						<ul>
							<?php foreach ( preg_split( '/\r\n|\r|\n/', $bp_feats ) as $bp_line ) : ?>
								<li><?php echo esc_html( $bp_line ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
