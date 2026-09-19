<?php
/**
 * BrickPoint one-click demo importer.
 *
 * Admin page: Appearance → BrickPoint Demo.
 * Runs idempotent, step-based imports via AJAX and creates the complete
 * demo website: pages, products, videos, projects, locations, blog, menus,
 * media, Elementor templates and Theme Builder conditions.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once BRICKPOINT_DIR . '/inc/demo/pages-builder.php';

class BP_Demo_Importer {

	const STATE_OPTION    = 'bp_demo_state';
	const REGISTRY_OPTION = 'bp_demo_registry';
	const DONE_OPTION     = 'bp_demo_done';
	const MEDIA_OPTION    = 'bp_demo_media';

	/**
	 * Boot: admin page + AJAX.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'wp_ajax_bp_demo_step', array( __CLASS__, 'ajax_step' ) );
		add_action( 'after_switch_theme', array( __CLASS__, 'flush_after_switch' ) );
	}

	public static function flush_after_switch() {
		flush_rewrite_rules();
	}

	/* ------------------------------------------------------------------ */
	/* Steps                                                               */
	/* ------------------------------------------------------------------ */

	public static function steps() {
		return array(
			'preparing'  => __( 'Preparing demo', 'brickpoint' ),
			'media'      => __( 'Importing media', 'brickpoint' ),
			'pages'      => __( 'Creating pages', 'brickpoint' ),
			'products'   => __( 'Creating products & categories', 'brickpoint' ),
			'videos'     => __( 'Creating videos', 'brickpoint' ),
			'projects'   => __( 'Creating projects', 'brickpoint' ),
			'locations'  => __( 'Creating locations', 'brickpoint' ),
			'blog'       => __( 'Importing blog', 'brickpoint' ),
			'menus'      => __( 'Creating menus', 'brickpoint' ),
			'templates'  => __( 'Importing Elementor templates', 'brickpoint' ),
			'homepage'   => __( 'Configuring homepage', 'brickpoint' ),
			'theme'      => __( 'Configuring theme settings', 'brickpoint' ),
			'finalizing' => __( 'Finalizing', 'brickpoint' ),
		);
	}

	/* ------------------------------------------------------------------ */
	/* Admin page                                                          */
	/* ------------------------------------------------------------------ */

	public static function admin_menu() {
		add_theme_page(
			__( 'BrickPoint Demo Import', 'brickpoint' ),
			__( 'BrickPoint Demo', 'brickpoint' ),
			'manage_options',
			'brickpoint-demo',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	public static function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'brickpoint' ) );
		}

		$done = get_option( self::DONE_OPTION );
		?>
		<div class="wrap bp-demo-wrap">
			<h1>
				<span class="dashicons dashicons-building" style="font-size:1.4em;margin-right:6px;color:#ea580c"></span>
				<?php esc_html_e( 'BrickPoint Demo Import', 'brickpoint' ); ?>
			</h1>
			<p><?php esc_html_e( 'One click recreates the complete BrickPoint website: pages, products, videos, projects, locations, blog, menus, media and Elementor Pro templates (header, footer, homepage, archives and singles).', 'brickpoint' ); ?></p>

			<?php if ( ! bp_has_elementor() ) : ?>
				<div class="notice notice-warning"><p>
					<?php esc_html_e( 'Elementor is not active. The demo will still import everything as WordPress content with the theme design, but install Elementor (and Elementor Pro) to edit everything visually.', 'brickpoint' ); ?>
				</p></div>
			<?php elseif ( ! bp_has_elementor_pro() ) : ?>
				<div class="notice notice-info"><p>
					<?php esc_html_e( 'Elementor Pro is not active. Pages and templates import fine; activate Elementor Pro to enable the Theme Builder header/footer/archive/single templates.', 'brickpoint' ); ?>
				</p></div>
			<?php endif; ?>

			<div id="bp-demo-panel" class="card" style="max-width:720px;padding:24px">
				<?php if ( $done ) : ?>
					<div class="bp-demo-success" style="border-left:4px solid #00a32a;padding-left:12px;margin-bottom:16px">
						<h2 style="margin-top:0"><?php esc_html_e( 'BrickPoint Demo Imported Successfully', 'brickpoint' ); ?></h2>
					</div>
				<?php endif; ?>

				<h3 style="margin-top:0"><?php esc_html_e( 'Import BrickPoint Demo', 'brickpoint' ); ?></h3>
				<p class="description"><?php esc_html_e( 'The importer is repeatable — running it again updates the demo instead of duplicating it.', 'brickpoint' ); ?></p>

				<ul id="bp-demo-steps" style="margin:16px 0">
					<?php foreach ( self::steps() as $id => $label ) : ?>
						<li data-step="<?php echo esc_attr( $id ); ?>" style="padding:4px 0;color:#8c8f94">
							<span class="bp-step-icon">○</span> <?php echo esc_html( $label ); ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<p>
					<button id="bp-demo-run" class="button button-primary button-hero"><?php esc_html_e( 'Import Demo', 'brickpoint' ); ?></button>
					<span id="bp-demo-status" style="margin-left:12px;font-weight:600"></span>
				</p>

				<div id="bp-demo-result" style="display:none;margin-top:20px">
					<p>
						<a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"><?php esc_html_e( 'View Website', 'brickpoint' ); ?></a>
						<?php
						$edit_home = admin_url( 'post.php?post=' . self::registry_get( 'page-home' ) . '&action=elementor' );
						?>
						<a class="button" href="<?php echo esc_url( $edit_home ); ?>" target="_blank"><?php esc_html_e( 'Edit Homepage', 'brickpoint' ); ?></a>
						<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=elementor-app#/site-editor/templates/header' ) ); ?>" target="_blank"><?php esc_html_e( 'Edit Header', 'brickpoint' ); ?></a>
						<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=elementor-app#/site-editor/templates/footer' ) ); ?>" target="_blank"><?php esc_html_e( 'Edit Footer', 'brickpoint' ); ?></a>
						<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library' ) ); ?>" target="_blank"><?php esc_html_e( 'Open Elementor Library', 'brickpoint' ); ?></a>
					</p>
				</div>
			</div>

			<script>
			(function () {
				var runBtn = document.getElementById('bp-demo-run');
				if (!runBtn) { return; }
				var steps = document.querySelectorAll('#bp-demo-steps li');
				var status = document.getElementById('bp-demo-status');
				var result = document.getElementById('bp-demo-result');
				var idx = 0;
				var running = false;

				function mark(state, text) {
					var li = steps[idx];
					if (!li) { return; }
					var icon = li.querySelector('.bp-step-icon');
					if (state === 'done') { icon.textContent = '✔'; li.style.color = '#00a32a'; }
					else if (state === 'error') { icon.textContent = '✖'; li.style.color = '#d63638'; }
					else { icon.textContent = '…'; li.style.color = '#2271b1'; }
					if (text) { li.innerHTML = icon.outerHTML + ' ' + li.textContent.replace(/^[^\s]+\s*/, '') + '<br><small style="color:#8c8f94">' + text + '</small>'; }
				}

				function next() {
					if (idx >= steps.length) {
						status.textContent = '<?php echo esc_js( __( 'BrickPoint Demo Imported Successfully', 'brickpoint' ) ); ?>';
						status.style.color = '#00a32a';
						result.style.display = 'block';
						runBtn.disabled = false;
						runBtn.textContent = '<?php echo esc_js( __( 'Re-run Import', 'brickpoint' ) ); ?>';
						return;
					}
					steps[idx].style.color = '#2271b1';
					mark('run');
					status.textContent = steps[idx].textContent + '…';

					var body = new FormData();
					body.append('action', 'bp_demo_step');
					body.append('step', steps[idx].getAttribute('data-step'));
					body.append('nonce', '<?php echo esc_js( wp_create_nonce( 'bp_demo_import' ) ); ?>');

					fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', { method: 'POST', credentials: 'same-origin', body: body })
						.then(function (r) { return r.json(); })
						.then(function (res) {
							if (res.success) {
								mark('done', res.data.message || '');
								idx++;
								next();
							} else {
								mark('error', (res.data && res.data.message) || 'Failed');
								status.textContent = 'Import stopped at: ' + steps[idx].textContent;
								status.style.color = '#d63638';
								runBtn.disabled = false;
							}
						})
						.catch(function (e) {
							mark('error', String(e));
							status.style.color = '#d63638';
							runBtn.disabled = false;
						});
				}

				runBtn.addEventListener('click', function () {
					if (running) { return; }
					running = true;
					runBtn.disabled = true;
					idx = 0;
					steps.forEach(function (li) { li.style.color = '#8c8f94'; li.querySelector('.bp-step-icon').textContent = '○'; var sm = li.querySelector('small'); if (sm) { sm.remove(); } });
					next();
				});
			})();
			</script>
		</div>
		<?php
	}

	/* ------------------------------------------------------------------ */
	/* AJAX                                                                */
	/* ------------------------------------------------------------------ */

	public static function ajax_step() {
		check_ajax_referer( 'bp_demo_import', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'brickpoint' ) ), 403 );
		}

		$step = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';
		if ( ! array_key_exists( $step, self::steps() ) ) {
			wp_send_json_error( array( 'message' => __( 'Unknown step.', 'brickpoint' ) ), 400 );
		}

		// Allow generous execution time for media imports on shared hosting.
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		}

		try {
			$message = call_user_func( array( __CLASS__, 'step_' . str_replace( '-', '_', $step ) ) );
			wp_send_json_success( array( 'message' => $message ) );
		} catch ( \Throwable $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/* ------------------------------------------------------------------ */
	/* Registry helpers (idempotency)                                      */
	/* ------------------------------------------------------------------ */

	public static function registry() {
		return get_option( self::REGISTRY_OPTION, array() );
	}

	public static function registry_get( $uid ) {
		$reg = self::registry();
		return isset( $reg[ $uid ]['id'] ) ? (int) $reg[ $uid ]['id'] : 0;
	}

	public static function registry_set( $uid, $type, $id ) {
		$reg = self::registry();
		$reg[ $uid ] = array( 'type' => $type, 'id' => (int) $id );
		update_option( self::REGISTRY_OPTION, $reg, false );
	}

	public static function media_id( $key ) {
		$map = get_option( self::MEDIA_OPTION, array() );
		return isset( $map[ $key ] ) ? (int) $map[ $key ] : 0;
	}

	public static function media_url( $key, $size = 'large' ) {
		$id = self::media_id( $key );
		if ( ! $id ) {
			return '';
		}
		$url = wp_get_attachment_image_url( $id, $size );
		return $url ? $url : wp_get_attachment_url( $id );
	}

	/* ------------------------------------------------------------------ */
	/* Steps implementation                                                */
	/* ------------------------------------------------------------------ */

	public static function step_preparing() {
		// Register post types and taxonomies right now (importer may run early).
		brickpoint_register_post_types();
		brickpoint_register_taxonomies();

		// Allow .mp4 uploads for admins during import.
		add_filter( 'upload_mimes', array( __CLASS__, 'allow_video_uploads' ) );

		if ( ! get_option( self::REGISTRY_OPTION ) ) {
			update_option( self::REGISTRY_OPTION, array(), false );
		}

		// Remove WordPress starter content so the demo starts clean. Safe to
		// re-run: each lookup simply finds nothing on later imports.
		foreach ( array( 'sample-page', 'hello-world', 'privacy-policy' ) as $bp_starter_slug ) {
			foreach ( array( 'page', 'post' ) as $bp_starter_type ) {
				$bp_starter = get_posts( array(
					'name'           => $bp_starter_slug,
					'post_type'      => $bp_starter_type,
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'fields'         => 'ids',
				) );
				foreach ( (array) $bp_starter as $bp_starter_id ) {
					wp_delete_post( (int) $bp_starter_id, true );
				}
			}
		}

		// Welcome the demo author for blog posts.
		$uid = self::registry_get( 'user-editor' );
		if ( ! $uid ) {
			$existing = get_user_by( 'login', 'brickpoint' );
			if ( ! $existing ) {
				$uid = wp_insert_user( array(
					'user_login'   => 'brickpoint',
					'user_pass'    => wp_generate_password( 20 ),
					'user_email'   => bp_email(),
					'display_name' => __( 'BrickPoint Team', 'brickpoint' ),
					'role'         => 'author',
				) );
				$uid = is_wp_error( $uid ) ? 0 : $uid;
			} else {
				$uid = $existing->ID;
			}
			if ( $uid ) {
				self::registry_set( 'user-editor', 'user', $uid );
			}
		}

		return __( 'Post types registered, demo workspace ready.', 'brickpoint' );
	}

	public static function allow_video_uploads( $mimes ) {
		$mimes['mp4|m4v'] = 'video/mp4';
		return $mimes;
	}

	public static function step_media() {
		$manifest = bp_demo_media_manifest();
		$dir      = BRICKPOINT_DIR . '/';
		$imported = 0;

		foreach ( $manifest as $key => $data ) {
			if ( self::media_id( $key ) ) {
				continue; // Already imported — idempotent.
			}
			$file = $dir . $data[0];
			if ( ! file_exists( $file ) ) {
				continue;
			}

			$name      = basename( $file );
			$size      = filesize( $file );
			$type      = wp_check_filetype( $name );
			$upload    = wp_upload_dir( gmdate( 'Y-m' ) );
			$filename  = wp_unique_filename( $upload['path'], $name );
			$new_file  = trailingslashit( $upload['path'] ) . $filename;

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
			if ( ! copy( $file, $new_file ) ) {
				continue;
			}
			// phpcs:ignore WordPress.WP.AlternativeFunctions.FileSystemIsLost_filesystem_changes
			chmod( $new_file, 0644 );

			$attachment = array(
				'post_mime_type' => $type['type'],
				'post_title'     => $data[1],
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'    => get_current_user_id(),
			);
			$attach_id = wp_insert_attachment( $attachment, $new_file );
			if ( is_wp_error( $attach_id ) || ! $attach_id ) {
				continue;
			}

			update_post_meta( $attach_id, '_wp_attachment_image_alt', $data[2] );

			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $new_file ) );

			$map         = get_option( self::MEDIA_OPTION, array() );
			$map[ $key ] = (int) $attach_id;
			update_option( self::MEDIA_OPTION, $map, false );
			$imported++;
		}

		return sprintf(
			/* translators: %d: number of media files imported */
			__( '%d media files in the Media Library.', 'brickpoint' ),
			count( get_option( self::MEDIA_OPTION, array() ) )
		);
	}

	public static function step_pages() {
		$pages = bp_demo_pages();
		$made  = 0;

		foreach ( $pages as $slug => $data ) {
			$page_id = self::upsert_page( 'page-' . $slug, $data['title'], $slug );

			if ( $page_id && ! empty( $data['elementor'] ) ) {
				// Homepage: use the bundled home.json Elementor template.
				$content = self::load_template_json( 'home' );
				if ( $content ) {
					self::set_elementor_content( $page_id, $content['content'], $content['page_settings'], 'page' );
				}
			} elseif ( $page_id && ! empty( $data['content'] ) && 'raw' === ( $data['content_type'] ?? '' ) ) {
				wp_update_post( array(
					'ID'           => $page_id,
					'post_content' => $data['content'],
				) );
			} elseif ( $page_id && 'blog' !== $slug && ! bp_is_elementor_built( $page_id ) ) {
				// Landing pages: build Elementor content from PHP builders.
				$built = bp_demo_page_content( $slug );
				if ( $built ) {
					self::set_elementor_content( $page_id, $built, array(), 'page' );
				}
			}
			$made++;
		}

		return sprintf(
			/* translators: %d: number of pages */
			__( '%d pages created.', 'brickpoint' ),
			$made
		);
	}

	public static function step_products() {
		$made = 0;

		foreach ( bp_demo_product_categories() as $slug => $cat ) {
			$term_id = self::upsert_term( 'cat-product-' . $slug, $cat['name'], 'bp_product_category', $cat['desc'], $slug );
			if ( $term_id ) {
				$media_id = self::media_id( $cat['media'] );
				if ( $media_id ) {
					update_term_meta( $term_id, 'bp_cat_image', wp_get_attachment_image_url( $media_id, 'bp-card-wide' ) );
					update_term_meta( $term_id, 'bp_cat_image_id', $media_id );
				}
			}
		}

		foreach ( bp_demo_products() as $p ) {
			$post_id = self::upsert_post( 'product-' . $p['slug'], $p['title'], 'bp_product', array(
				'slug'      => $p['slug'],
				'content'   => $p['content'],
				'excerpt'   => $p['short'],
				'thumbnail' => self::media_id( $p['media'] ),
				'meta'      => array(
					'_bp_price'       => $p['price'],
					'_bp_unit'        => $p['unit'],
					'_bp_badge'       => $p['badge'],
					'_bp_availability' => $p['availability'],
					'_bp_short'       => $p['short'],
					'_bp_specs'       => $p['specs'],
					'_bp_features'    => $p['features'],
					'_bp_sort'        => $p['sort'],
					'_bp_featured'    => (string) $p['featured'],
				),
				'terms'     => array( 'bp_product_category' => $p['cats'] ),
			) );
			if ( $post_id ) {
				// Gallery: media keys → attachment IDs.
				$gallery = array_filter( array_map( array( __CLASS__, 'media_id' ), $p['gallery'] ) );
				if ( $gallery ) {
					update_post_meta( $post_id, '_bp_gallery_ids', implode( ',', $gallery ) );
					update_post_meta( $post_id, '_bp_gallery', implode( "\n", array_filter( array_map( function ( $id ) {
						return wp_get_attachment_image_url( $id, 'large' );
					}, $gallery ) ) ) );
				}
				$made++;
			}
		}

		return sprintf(
			/* translators: %d: number of products */
			__( '%d products in %d categories.', 'brickpoint' ),
			$made,
			count( bp_demo_product_categories() )
		);
	}

	public static function step_videos() {
		$made = 0;

		foreach ( bp_demo_video_categories() as $slug => $cat ) {
			$term_id = self::upsert_term( 'cat-video-' . $slug, $cat['name'], 'bp_video_category', $cat['desc'], $slug );
			if ( $term_id ) {
				$media_id = self::media_id( $cat['media'] );
				if ( $media_id ) {
					update_term_meta( $term_id, 'bp_cat_image', wp_get_attachment_image_url( $media_id, 'bp-card-wide' ) );
					update_term_meta( $term_id, 'bp_cat_image_id', $media_id );
				}
			}
		}

		foreach ( bp_demo_videos() as $v ) {
			$file_id = self::media_id( $v['media'] );
			$file    = $file_id ? wp_get_attachment_url( $file_id ) : '';
			$post_id = self::upsert_post( 'video-' . $v['slug'], $v['title'], 'bp_video', array(
				'slug'      => $v['slug'],
				'content'   => $v['content'],
				'excerpt'   => $v['excerpt'],
				'thumbnail' => self::media_id( $v['thumb'] ),
				'meta'      => array(
					'_bpv_source'   => 'mp4',
					'_bpv_url'      => '',
					'_bpv_file'     => $file,
					'_bpv_duration' => $v['duration'],
					'_bpv_order'    => $v['order'],
					'_bpv_featured' => (string) $v['featured'],
				),
				'terms'     => array( 'bp_video_category' => $v['cats'] ),
			) );
			if ( $post_id ) {
				$made++;
			}
		}

		return sprintf(
			/* translators: %d: number of videos */
			__( '%d videos with self-hosted MP4 players.', 'brickpoint' ),
			$made
		);
	}

	public static function step_projects() {
		$made = 0;

		foreach ( bp_demo_project_categories() as $slug => $cat ) {
			$term_id = self::upsert_term( 'cat-project-' . $slug, $cat['name'], 'bp_project_category', $cat['desc'], $slug );
			if ( $term_id ) {
				$media_id = self::media_id( $cat['media'] );
				if ( $media_id ) {
					update_term_meta( $term_id, 'bp_cat_image', wp_get_attachment_image_url( $media_id, 'bp-card-wide' ) );
					update_term_meta( $term_id, 'bp_cat_image_id', $media_id );
				}
			}
		}

		foreach ( bp_demo_projects() as $p ) {
			$post_id = self::upsert_post( 'project-' . $p['slug'], $p['title'], 'bp_project', array(
				'slug'      => $p['slug'],
				'content'   => $p['content'],
				'excerpt'   => $p['excerpt'],
				'thumbnail' => self::media_id( $p['media'] ),
				'meta'      => array(
					'_bpp_location'     => $p['location'],
					'_bpp_status'       => __( 'Illustrative construction reference', 'brickpoint' ),
					'_bpp_sort'         => $p['sort'],
					'_bpp_featured'     => (string) $p['featured'],
					'_bpp_illustrative' => '1',
				),
				'terms'     => array( 'bp_project_category' => $p['cats'] ),
			) );
			if ( $post_id ) {
				$gallery = array_filter( array_map( array( __CLASS__, 'media_id' ), $p['gallery'] ) );
				if ( $gallery ) {
					update_post_meta( $post_id, '_bpp_gallery', implode( "\n", array_filter( array_map( function ( $id ) {
						return wp_get_attachment_image_url( $id, 'large' );
					}, $gallery ) ) ) );
				}
				$made++;
			}
		}

		return sprintf(
			/* translators: %d: number of projects */
			__( '%d projects in %d categories.', 'brickpoint' ),
			$made,
			count( bp_demo_project_categories() )
		);
	}

	public static function step_locations() {
		$made = 0;

		foreach ( bp_demo_locations() as $l ) {
			$post_id = self::upsert_post( 'location-' . $l['slug'], $l['title'], 'bp_location', array(
				'slug'      => $l['slug'],
				'content'   => $l['content'],
				'excerpt'   => $l['excerpt'],
				'thumbnail' => self::media_id( $l['media'] ),
				'meta'      => array(
					'_bpl_address'  => $l['address'],
					'_bpl_phone'    => $l['phone'],
					'_bpl_hours'    => $l['hours'],
					'_bpl_coords'   => $l['coords'],
					'_bpl_maps'     => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $l['maps_query'] ),
					'_bpl_order'    => $l['order'],
					'_bpl_whatsapp' => bp_phone_intl(),
				),
			) );
			if ( $post_id ) {
				$made++;
			}
		}

		return sprintf(
			/* translators: %d: number of locations */
			__( '%d bhatta locations with maps links.', 'brickpoint' ),
			$made
		);
	}

	public static function step_blog() {
		$made = 0;

		foreach ( bp_demo_post_categories() as $slug => $name ) {
			self::upsert_term( 'cat-post-' . $slug, $name, 'category', '' );
		}

		$author = self::registry_get( 'user-editor' );

		foreach ( bp_demo_posts() as $p ) {
			$date    = gmdate( 'Y-m-d H:i:s', time() - ( $p['date_offset'] * DAY_IN_SECONDS ) );
			$post_id = self::upsert_post( 'post-' . $p['slug'], $p['title'], 'post', array(
				'slug'      => $p['slug'],
				'content'   => $p['content'],
				'excerpt'   => $p['excerpt'],
				'thumbnail' => self::media_id( $p['media'] ),
				'author'    => $author,
				'date'      => $date,
				'terms'     => array( 'category' => $p['cats'] ),
			) );
			if ( $post_id ) {
				$made++;
			}
		}

		return sprintf(
			/* translators: %d: number of posts */
			__( '%d blog posts with featured images.', 'brickpoint' ),
			$made
		);
	}

	public static function step_menus() {
		$defs = bp_demo_menus();
		$made = 0;

		foreach ( $defs as $location => $def ) {
			$menu    = wp_get_nav_menu_object( $def['name'] );
			$menu_id = $menu ? (int) $menu->term_id : 0;

			if ( ! $menu_id ) {
				$menu_id = wp_create_nav_menu( $def['name'] );
			}
			if ( is_wp_error( $menu_id ) || ! $menu_id ) {
				continue;
			}

			// Replace existing items to stay idempotent.
			$existing_items = wp_get_nav_menu_items( $menu_id );
			if ( $existing_items ) {
				foreach ( $existing_items as $item ) {
					wp_delete_post( $item->ID, true );
				}
			}

			$items = $def['items'] ? $def['items'] : $defs['primary']['items'];
			foreach ( $items as $i => $item ) {
				$url = self::resolve_token( $item['url'] );
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => $item['label'],
					'menu-item-url'       => $url,
					'menu-item-type'      => 'custom',
					'menu-item-status'    => 'publish',
					'menu-item-position'  => $i + 1,
				) );
			}

			self::registry_set( 'menu-' . $location, 'menu', $menu_id );
			$made++;
		}

		// Assign locations.
		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = self::registry_get( 'menu-primary' );
		$locations['footer']  = self::registry_get( 'menu-footer' );
		$locations['mobile']  = self::registry_get( 'menu-mobile' ) ?: self::registry_get( 'menu-primary' );
		set_theme_mod( 'nav_menu_locations', $locations );

		return sprintf(
			/* translators: %d: number of menus */
			__( '%d menus created and assigned (primary, footer, mobile).', 'brickpoint' ),
			$made
		);
	}

	public static function step_templates() {
		if ( ! bp_has_elementor() ) {
			return __( 'Elementor not active — skipped (re-run after installing Elementor).', 'brickpoint' );
		}

		bp_elementor_ensure_kit();

		$files = glob( BRICKPOINT_DIR . '/elementor/templates/*.json' );
		$made  = 0;

		foreach ( (array) $files as $file ) {
			$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( ! $data || empty( $data['content'] ) ) {
				continue;
			}

			$name    = isset( $data['bp_name'] ) ? sanitize_key( $data['bp_name'] ) : basename( $file, '.json' );
			$uid     = 'template-' . $name;
			$type    = isset( $data['bp_type'] ) ? $data['bp_type'] : 'page';
			$title   = isset( $data['title'] ) ? $data['title'] : ucwords( $name );
			$post_id = self::registry_get( $uid );

			if ( ! $post_id || ! get_post( $post_id ) ) {
				$post_id = wp_insert_post( array(
					'post_title'  => $title,
					'post_type'   => 'elementor_library',
					'post_status' => 'publish',
				) );
				if ( is_wp_error( $post_id ) || ! $post_id ) {
					continue;
				}
				self::registry_set( $uid, 'template', $post_id );
			} else {
				wp_update_post( array( 'ID' => $post_id, 'post_title' => $title ) );
			}

			$doc_type = in_array( $type, array( 'header', 'footer', 'single', 'archive', 'section', 'page' ), true ) ? $type : 'page';

			update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $post_id, '_elementor_template_type', $doc_type );
			update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );

			if ( in_array( $doc_type, array( 'header', 'footer', 'single', 'archive' ), true ) ) {
				update_post_meta( $post_id, '_elementor_location', $doc_type );
			}

			// Content with tokens resolved.
			$content = self::resolve_tokens_deep( $data['content'] );
			$page_settings = ! empty( $data['page_settings'] ) ? self::resolve_tokens_deep( $data['page_settings'] ) : array();
			self::write_elementor_data( $post_id, $content, $page_settings );

			// Theme Builder display conditions.
			if ( ! empty( $data['bp_condition'] ) && in_array( $doc_type, array( 'header', 'footer', 'single', 'archive' ), true ) ) {
				bp_set_template_conditions( $post_id, $data['bp_condition'] );
			}

			$made++;
		}

		return sprintf(
			/* translators: %d: number of templates */
			__( '%d Elementor templates imported with Theme Builder conditions.', 'brickpoint' ),
			$made
		);
	}

	public static function step_homepage() {
		$home_id = self::registry_get( 'page-home' );
		$blog_id = self::registry_get( 'page-blog' );

		if ( $home_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_id );
		}
		if ( $blog_id ) {
			update_option( 'page_for_posts', $blog_id );
		}

		// Custom logo from bundled SVG.
		$logo_id = self::media_id( 'logo' );
		if ( ! $logo_id ) {
			$logo_id = self::import_single_file( BRICKPOINT_DIR . '/assets/images/logo-white.svg', __( 'BrickPoint Logo', 'brickpoint' ), 'logo' );
		}
		if ( $logo_id ) {
			set_theme_mod( 'custom_logo', $logo_id );
		}

		// Hero video + poster from the Media Library.
		$hero_video  = self::media_id( 'video-hero' );
		$hero_poster = self::media_id( 'hero' );
		if ( $hero_video ) {
			set_theme_mod( 'bp_hero_video', wp_get_attachment_url( $hero_video ) );
		}
		if ( $hero_poster ) {
			set_theme_mod( 'bp_hero_poster', wp_get_attachment_image_url( $hero_poster, 'bp-hero' ) );
		}

		// Blog page title band.
		if ( $blog_id ) {
			update_post_meta( $blog_id, '_bp_page_subtitle', __( 'Guides, quality notes and company updates from the BrickPoint yards.', 'brickpoint' ) );
		}

		return __( 'Homepage set as front page, blog page assigned, logo + hero media configured.', 'brickpoint' );
	}

	public static function step_theme() {
		// Permalinks: always the clean post-name structure (Playground and some
		// hosts preinstall date-based structures). set_permalink_structure() is
		// the official API — it updates the option, the runtime property and
		// the verbose-page-rules flag so the next flush is correct.
		if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
			$GLOBALS['wp_rewrite']->set_permalink_structure( '/%postname%/' );
		}

		// Theme options: WhatsApp + contact (same values as the LM Arena site).
		set_theme_mod( 'bp_phone_display', '0315 2850818' );
		set_theme_mod( 'bp_whatsapp_number', '923152850818' );
		set_theme_mod( 'bp_email', 'info@brickpoint.pk' );
		set_theme_mod( 'bp_default_wa', __( 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.', 'brickpoint' ) );
		set_theme_mod( 'bp_copyright', __( '© BrickPoint. All rights reserved.', 'brickpoint' ) );
		set_theme_mod( 'bp_social_facebook', 'https://www.facebook.com/brickpoint.pk/' );
		set_theme_mod( 'bp_social_instagram', 'https://www.instagram.com/brickpoint.pk/' );
		set_theme_mod( 'bp_social_twitter', 'https://x.com/BrickPointPK' );
		set_theme_mod( 'bp_social_tiktok', 'https://www.tiktok.com/@brickpoint.pk/' );

		// Seed default terms flag (kept for compatibility with older theme data).
		update_option( 'brickpoint_seeded', 1 );

		return __( 'Permalinks, WhatsApp contact and social settings configured.', 'brickpoint' );
	}

	public static function step_finalizing() {
		// Ensure rewrite generation uses the final structure (step_theme may
		// have changed the option during this same request).
		$GLOBALS['wp_rewrite']->set_permalink_structure( get_option( 'permalink_structure' ) );
		flush_rewrite_rules();
		bp_rebuild_conditions_cache();
		update_option( self::DONE_OPTION, time(), false );

		// Force Elementor to regenerate CSS for imported content.
		if ( bp_has_elementor() && class_exists( '\Elementor\Plugin' ) ) {
			try {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			} catch ( \Throwable $e ) { // phpcs:ignore
				// Cache will regenerate on demand.
			}
		}

		remove_filter( 'upload_mimes', array( __CLASS__, 'allow_video_uploads' ) );

		return __( 'Rewrite rules flushed, Elementor CSS regenerated. The demo is live!', 'brickpoint' );
	}

	/* ------------------------------------------------------------------ */
	/* Updaters (idempotent content creation)                             */
	/* ------------------------------------------------------------------ */

	public static function upsert_page( $uid, $title, $slug ) {
		$post_id = self::registry_get( $uid );

		if ( $post_id && get_post( $post_id ) ) {
			return $post_id;
		}

		$post_id = wp_insert_post( array(
			'post_title'     => $title,
			'post_name'      => $slug,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
		) );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 0;
		}

		self::registry_set( $uid, 'page', $post_id );
		return (int) $post_id;
	}

	public static function upsert_post( $uid, $title, $type, $args = array() ) {
		$post_id = self::registry_get( $uid );
		$exists  = $post_id && get_post( $post_id );

		$postarr = array(
			'post_title'   => $title,
			'post_name'    => isset( $args['slug'] ) ? $args['slug'] : '',
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_content' => isset( $args['content'] ) ? $args['content'] : '',
			'post_excerpt' => isset( $args['excerpt'] ) ? $args['excerpt'] : '',
		);

		if ( ! empty( $args['author'] ) ) {
			$postarr['post_author'] = (int) $args['author'];
		}
		if ( ! empty( $args['date'] ) ) {
			$postarr['post_date']         = $args['date'];
			$postarr['post_date_gmt']     = get_gmt_from_date( $args['date'] );
		}

		if ( $exists ) {
			$postarr['ID'] = $post_id;
			$post_id       = wp_update_post( $postarr );
			if ( is_wp_error( $post_id ) ) {
				return 0;
			}
		} else {
			$post_id = wp_insert_post( $postarr );
			if ( is_wp_error( $post_id ) || ! $post_id ) {
				return 0;
			}
			self::registry_set( $uid, $type, $post_id );
		}

		$post_id = (int) $post_id;

		if ( ! empty( $args['thumbnail'] ) ) {
			set_post_thumbnail( $post_id, (int) $args['thumbnail'] );
		}

		if ( ! empty( $args['meta'] ) ) {
			foreach ( $args['meta'] as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		}

		if ( ! empty( $args['terms'] ) ) {
			foreach ( $args['terms'] as $taxonomy => $names ) {
				$term_ids = array();
				foreach ( $names as $name ) {
					$term = get_term_by( 'slug', $name, $taxonomy );
					if ( ! $term ) {
						$term = get_term_by( 'name', $name, $taxonomy );
					}
					if ( $term ) {
						$term_ids[] = (int) $term->term_id;
					}
				}
				if ( $term_ids ) {
					wp_set_object_terms( $post_id, $term_ids, $taxonomy, false );
				}
			}
		}

		return $post_id;
	}

	public static function upsert_term( $uid, $name, $taxonomy, $description = '', $slug = '' ) {
		$term_id = self::registry_get( $uid );

		if ( $term_id && get_term( $term_id, $taxonomy ) && ! is_wp_error( get_term( $term_id, $taxonomy ) ) ) {
			if ( $slug ) {
				// Keep the demo slug stable (content links point at it).
				$term_obj = get_term( $term_id, $taxonomy );
				if ( $term_obj instanceof WP_Term && $term_obj->slug !== $slug ) {
					wp_update_term( $term_id, $taxonomy, array( 'slug' => $slug ) );
				}
			}
			return $term_id;
		}

		// Match existing terms by slug first so re-imports stay deduplicated.
		$term = $slug ? term_exists( $slug, $taxonomy ) : null;
		if ( ( ! $term || is_wp_error( $term ) ) && $slug ) {
			$by_slug = get_term_by( 'slug', $slug, $taxonomy );
			if ( $by_slug ) {
				$term = array( 'term_id' => $by_slug->term_id );
			}
		}
		if ( ! $term || is_wp_error( $term ) ) {
			$term = term_exists( $name, $taxonomy );
		}
		if ( $term && ! is_wp_error( $term ) ) {
			$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		} else {
			$args = array( 'description' => $description );
			if ( $slug ) {
				$args['slug'] = $slug;
			}
			$term = wp_insert_term( $name, $taxonomy, $args );
			if ( is_wp_error( $term ) ) {
				return 0;
			}
			$term_id = (int) $term['term_id'];
		}

		if ( $description || $slug ) {
			$update = array();
			if ( $description ) {
				$update['description'] = $description;
			}
			if ( $slug ) {
				$update['slug'] = $slug;
			}
			wp_update_term( $term_id, $taxonomy, $update );
		}

		self::registry_set( $uid, 'term_' . $taxonomy, $term_id );
		return $term_id;
	}

	public static function import_single_file( $path, $title, $media_key ) {
		if ( ! file_exists( $path ) || self::media_id( $media_key ) ) {
			return self::media_id( $media_key );
		}

		$upload   = wp_upload_dir( gmdate( 'Y-m' ) );
		$filename = wp_unique_filename( $upload['path'], basename( $path ) );
		$new_file = trailingslashit( $upload['path'] ) . $filename;

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
		if ( ! copy( $path, $new_file ) ) {
			return 0;
		}

		$type      = wp_check_filetype( $new_file );
		$attach_id = wp_insert_attachment( array(
			'post_mime_type' => $type['type'] ? $type['type'] : 'image/svg+xml',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		), $new_file );

		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		if ( 'image/svg+xml' === ( $type['type'] ? $type['type'] : 'image/svg+xml' ) ) {
			// SVGs need no thumbnails. Write metadata directly so the
			// `wp_update_attachment_metadata` filter (Elementor's SVG dims
			// handler) doesn't do a remote fetch for a bundled vector file.
			update_post_meta( $attach_id, '_wp_attachment_metadata', array() );
		} else {
			wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $new_file ) );
		}

		$map                 = get_option( self::MEDIA_OPTION, array() );
		$map[ $media_key ]   = (int) $attach_id;
		update_option( self::MEDIA_OPTION, $map, false );

		return (int) $attach_id;
	}

	/* ------------------------------------------------------------------ */
	/* Elementor helpers                                                   */
	/* ------------------------------------------------------------------ */

	public static function load_template_json( $name ) {
		$file = BRICKPOINT_DIR . '/elementor/templates/' . $name . '.json';
		if ( ! file_exists( $file ) ) {
			return array();
		}
		$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return is_array( $data ) ? $data : array();
	}

	/**
	 * Write Elementor data meta for a post.
	 */
	public static function set_elementor_content( $post_id, $content, $page_settings = array(), $doc_type = 'page' ) {
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_template_type', $doc_type );
		update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
		self::write_elementor_data( $post_id, $content, $page_settings );
	}

	public static function write_elementor_data( $post_id, $content, $page_settings ) {
		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $content ) ) );
		if ( $page_settings ) {
			update_post_meta( $post_id, '_elementor_page_settings', $page_settings );
		}
		delete_post_meta( $post_id, '_elementor_css' );
		delete_post_meta( $post_id, '_elementor_global_css' );
	}

	/**
	 * Resolve a single {{token}}.
	 */
	public static function resolve_token( $token ) {
		$token = trim( (string) $token, '{}' );

		if ( 'home' === $token ) {
			return home_url( '/' );
		}
		if ( 'wa' === $token ) {
			return bp_whatsapp_url( bp_get( 'bp_default_wa', 'Assalam-o-Alaikum BrickPoint, I need a quotation for construction materials.' ) );
		}
		if ( 'tel' === $token ) {
			return 'tel:+' . bp_phone_intl();
		}

		$parts = explode( ':', $token, 2 );
		if ( count( $parts ) !== 2 ) {
			return $token;
		}

		list( $kind, $key ) = $parts;

		switch ( $kind ) {
			case 'media':
				return self::media_url( $key );
			case 'media_id':
				return (string) self::media_id( $key );
			case 'video':
				$id = self::media_id( $key );
				return $id ? (string) wp_get_attachment_url( $id ) : '';
			case 'video_id':
				return (string) self::media_id( $key );
			case 'archive':
				return (string) get_post_type_archive_link( $key );
			case 'page':
				$pid = self::registry_get( 'page-' . $key );
				return $pid ? (string) get_permalink( $pid ) : home_url( '/' );
			case 'term':
				$link = get_term_link( sanitize_title( $key ), 'bp_product_category' );
				return is_wp_error( $link ) ? '' : (string) $link;
		}

		return $token;
	}

	/**
	 * Walk a data structure replacing tokens in every string.
	 */
	public static function resolve_tokens_deep( $data ) {
		if ( is_string( $data ) ) {
			return preg_replace_callback( '/\{\{[^}]+\}\}/', function ( $m ) {
				return self::resolve_token( $m[0] );
			}, $data );
		}
		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				$data[ $k ] = self::resolve_tokens_deep( $v );
			}
		}
		return $data;
	}
}

BP_Demo_Importer::init();
