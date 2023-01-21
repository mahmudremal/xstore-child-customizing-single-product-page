<?php
/**
 * Block Patterns
 *
 * @package FWPWooXstoreChild
 */

namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;

class Core {
	use Singleton;
	private $theUploadDir;
	protected function __construct() {
		$this->theUploadDir = false;
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		// add_action( 'get_template_part_templates/blog/header', [ $this, 'get_template_part' ], 10, 3 );
		// add_action( 'get_template_part', [ $this, 'get_template_part' ], 10, 4 );
		
		// woocommerce_checkout_before_order_review_heading | woocommerce_checkout_before_order_review | woocommerce_checkout_after_order_review
		add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'woocommerce_checkout_before_order_review' ], 10, 0 );

		add_action( 'wp_ajax_futurewordpress/project/filesystem/upload', [ $this, 'uploadFile' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_futurewordpress/project/filesystem/upload', [ $this, 'uploadFile' ], 10, 0 );
		add_action( 'wp_ajax_futurewordpress/project/filesystem/remove', [ $this, 'removeFile' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_futurewordpress/project/filesystem/remove', [ $this, 'removeFile' ], 10, 0 );
		add_action( 'admin_post_futurewordpress/project/filesystem/download', [ $this, 'downloadFile' ], 10, 0 );
		add_action( 'admin_post_nopriv_futurewordpress/project/filesystem/download', [ $this, 'downloadFile' ], 10, 0 );
		add_action( 'futurewordpress/project/order/meta', [ $this, 'orderMeta' ], 10, 2 );

		// add_action( 'woocommerce_add_order_item_meta', [ $this, 'woocommerce_add_order_item_meta' ], 10, 3 );
		add_filter( 'woocommerce_before_cart_item_quantity_zero', [ $this, 'woo_remove_item_data' ], 10, 1 );
		add_filter( 'woocommerce_cart_emptied', [ $this, 'woo_remove_item_data' ], 10, 1 );

		add_action( 'woocommerce_admin_order_preview_end', [ $this, 'woocommerce_admin_order_preview_end' ], 10, 0 );
		add_filter( 'woocommerce_admin_order_preview_get_order_details', [ $this, 'woocommerce_admin_order_preview_get_order_details' ], 10, 2 );
		// add_filter( 'woocommerce_admin_order_preview_actions', [ $this, 'woocommerce_admin_order_preview_actions' ], 10, 2 );

		// add_action( 'woocommerce_thankyou', [ $this, 'woocommerce_thankyou' ], 99, 1 );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'woocommerce_checkout_update_order_meta' ], 10, 1 );
		add_action( 'wp_trash_post', [ $this, 'wp_trash_post' ], 10, 1 );
		add_action( 'init', [ $this, 'remove_expired_clips' ], 10, 0 );
	}
	public function get_template_part( $slug, $name = null, $templates = '', $args = [] ) {
		// print_r( [ $slug, $name, $templates, $args ] );wp_die( 'hi' );
	}
	/**
	 * Woocommerce Checkpout screen visual hooks
	 * https://www.businessbloomer.com/woocommerce-visual-hook-guide-checkout-page/
	 * 
	 * @return void
	 */
	public function woocommerce_checkout_before_order_review() {
		?>
		<!-- <div class="col-inner has-border"> -->
			<div class="fwp-section-container">
				<h3 class="fwp-h3"><?php esc_html_e( 'Add a video message to your order.', 'woocommerce-checkout-video-snippet' ); ?><a class="dashicons dashicons-editor-help" data-popup="<?php esc_attr_e( '', 'woocommerce-checkout-video-snippet' ); ?>" data-react="onclick" href="#fwp-popup-overlay-1"></a></h3>
				<!-- Start PopUp -->
				<div id="fwp-popup-overlay-1" class="fwp-popup-overlay">
					<div class="fwp-popup-body">
						<h2><?php esc_html_e( 'How to Upload video clip?', 'woocommerce-checkout-video-snippet' ); ?></h2>
						<a class="close" href="#">&times;</a>
						<div class="content">
							<ul>
								<li><?php esc_html_e( 'Upload your 30 second short video from here. Click over camera icon, allow to access camera, and there you go, record your short clip. So simple how said. Right?', 'woocommerce-checkout-video-snippet' ); ?></li>
								<li><?php echo wp_kses_post( sprintf( __( 'You can also submit video on the "Upload Video" tab from your device or computer. Try to upload on allowed video format. Allowed formates are %sMP4, AVI, WEBP%s.', 'woocommerce-checkout-video-snippet' ), '<b>', '</b>' ) ); ?></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- End PopUp -->
				<div class="fwp-tabs__container">
					<div class="fwp-tabs__wrap">
						<div class="fwp-tabs__navs">
							<div class="fwp-tabs__nav-item active" data-target="#record-video"><?php esc_html_e( 'Record Video', 'woocommerce-checkout-video-snippet' ); ?></div>
							<div class="fwp-tabs__nav-item" data-target="#upload-video"><?php esc_html_e( 'Upload Video', 'woocommerce-checkout-video-snippet' ); ?></div>
						</div>
						<div class="fwp-tabs__tabs-field">
							<div class="fwp-tabs__content active" id="record-video">
							<!-- controls preload="auto" width="640" height="264" poster="MY_VIDEO_POSTER.jpg" data-setup="{}" -->
								<video id="fwp-videojs-record-field" playsinline class="video-js vjs-default-skin"></video>
							</div>
							<div class="fwp-tabs__content" id="upload-video">
								<div class="fwp-dropzone-field" style="background-image: url('<?php echo esc_url( FWPWOOXSTORECHILD_BUILD_URI . '/icons/drag-n-drop.svg' ); ?>');">
									<div class="fallback">
										<input name="file" type="file" multiple />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- </div> -->
		<?php
	}
	public function woocommerce_add_order_item_meta( $item_id, $values, $cart_item_key ) {
		$session_var  = 'checkout_video_clip';
		$session_data = (array) WC()->session->get( $session_var );
		// print_r( [$item_id, $session_var, $session_data] );wp_die( 'oooooooooooooookay', 'noooo' );
		// if( count( $session_data ) >= 1 ) {
			wc_add_order_item_meta( $item_id, $session_var, $session_data );
		// } else {error_log("no session data", 0);
		// }
		wp_send_json_success( wc_get_order_item_meta( $item_id, $session_var ) );
	}
	public function woocommerce_checkout_update_order_meta( $order_id ) {
		$session_var  = 'checkout_video_clip';
		$session_data = (array) WC()->session->get( $session_var );
		// print_r( [$item_id, $session_var, $session_data] );wp_die( 'oooooooooooooookay', 'noooo' );
		if( count( $session_data ) >= 1 ) {
			$session_data[ 'order_id' ] = $order_id;
			add_post_meta( $order_id, $session_var, $session_data );
		// } else {error_log("no session data", 0);
		}
	}
	public function uploadFile() {
		if( ! function_exists( 'WC' ) ) {wp_send_json_error( __( 'Woo not installed', 'woocommerce-checkout-video-snippet' ), 200 );}
		check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );

		if( isset( $_FILES[ 'blobFile' ] ) || isset( $_FILES[ 'file' ] ) ) {
			$file = isset( $_FILES[ 'blobFile' ] ) ? $_FILES[ 'blobFile' ] : $_FILES[ 'file' ];
			$blobInfo = isset( $_POST[ 'blobInfo' ] ) ? (array) json_decode( $_POST[ 'blobInfo' ] ) : [];
			// ABSPATH . WP_CONTENT_URL . 
			$file[ 'name' ] = isset( $file[ 'name' ] ) ? (
				( $file[ 'name' ] == 'blob' ) ? ( isset( $blobInfo[ 'name' ] ) ? $blobInfo[ 'name' ] : 'captured.webm' ) : $file[ 'name' ]
			) : 'captured.' . explode( '/', $file[ 'type' ] )[1];
			$file[ 'full_path' ] = $this->uploadDir( time() . '-' . basename( $file[ 'name' ] ), true );$error = false;
			if( $file[ 'size' ] > 5000000000 ) {
				$error = sprintf( __( 'File is larger then allowed range. (%d)', 'woocommerce-checkout-video-snippet' ), $file[ 'size' ] );
			}
			$extension = strtolower( pathinfo( $file[ 'name' ], PATHINFO_EXTENSION ) );
			$mime = mime_content_type( $file[ 'tmp_name' ] );$extension = empty( $extension ) ? $mime : $extension;
			if( ! in_array( $extension, [ 'mp4', 'text/html' ] ) && ! strstr( $mime, "video/" ) ) {
				$error = sprintf( __( 'File format (%s) is not allowed.', 'woocommerce-checkout-video-snippet' ), $extension );
			}
			if( $error === false && move_uploaded_file( $file[ 'tmp_name' ], $file[ 'full_path' ] ) ) {
				$file[ 'full_url' ] = str_replace( [ $this->theUploadDir[ 'basedir' ] ], [ $this->theUploadDir[ 'baseurl' ] ], $file[ 'full_path' ] );
				$meta = [
					// 'time' => time(),
					'date' => date( 'Y:M:d H:i:s' ),
					'wp_date' => wp_date( 'Y:M:d H:i:s' ),
					...$file
				];
				$oldMeta = (array) WC()->session->get( 'checkout_video_clip' );
				if( isset( $oldMeta[ 'full_path' ] ) && ! empty( $oldMeta[ 'full_path' ] ) && file_exists( $oldMeta[ 'full_path' ] ) && ! is_dir( $oldMeta[ 'full_path' ] ) ) {unlink( $oldMeta[ 'full_path' ] );}
				WC()->session->set( 'checkout_video_clip', $meta );
				wp_send_json_success( [
					'message'			=> __( 'Uploaded successfully', 'woocommerce-checkout-video-snippet' ),
					'dropZone'		=> $meta
				], 200 );
			} else {
				$error = ( $error ) ? $error : __( 'Something went wrong while tring to upload short clip video.', 'woocommerce-checkout-video-snippet' );
				wp_send_json_error( $error, 200 );
			}
		}
		wp_send_json_error( __( 'Error happens.', 'woocommerce-checkout-video-snippet' ), 200 );

	}
	public function removeFile() {
		// check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );
		$fileInfo = isset( $_POST[ 'fileinfo' ] ) ? (array) json_decode( str_replace( "\\", "", $_POST[ 'fileinfo' ] ) ) : [];

		// if( isset( $fileInfo[ 'full_path' ] ) ) {$_POST[ 'todelete' ] = $fileInfo[ 'full_path' ];}
		// if( isset( $_POST[ 'todelete' ] ) && file_exists( $this->uploadDir( basename( $_POST[ 'todelete' ] ), true ) ) && ! is_dir( $this->uploadDir( basename( $_POST[ 'todelete' ] ), true ) ) ) {

		if( isset( $fileInfo[ 'full_path' ] ) && file_exists( $fileInfo[ 'full_path' ] ) && ! is_dir( $fileInfo[ 'full_path' ] ) ) {
			// unlink( $this->uploadDir( basename( $fileInfo[ 'full_path' ] ), true ) );
			unlink( $fileInfo[ 'full_path' ] );
			WC()->session->set( 'checkout_video_clip', [] );
			wp_send_json_success( __( 'Clip removed from server.', 'woocommerce-checkout-video-snippet' ), 200 );
		}
		wp_send_json_error( __( 'Failed to delete.', 'woocommerce-checkout-video-snippet' ), 400 );
	}
	public function downloadFile() {
		// check_ajax_referer( 'futurewordpress_project_nonce', '_nonce' );
		$order_id = isset( $_GET[ 'order_id' ] ) ? $_GET[ 'order_id' ] : false;$fileInfo = [];
		$meta = get_post_meta( $order_id, 'checkout_video_clip', true );
		if( $meta && !empty( $meta ) && isset( $meta[ 'name' ] ) ) {$fileInfo = $meta;}

		if( isset( $fileInfo[ 'full_url' ] ) && isset( $fileInfo[ 'full_path' ] ) && file_exists( $fileInfo[ 'full_path' ] ) && ! is_dir( $fileInfo[ 'full_path' ] ) ) {
			wp_redirect( $fileInfo[ 'full_url' ] );
		} else {
			print_r( $fileInfo );
			wp_die( __( 'File not found', 'woocommerce-checkout-video-snippet' ), __( '404 not found', 'woocommerce-checkout-video-snippet' ) );
		}
	}
	public function orderMeta( $order_id, $is_single = true ) {
		return get_post_meta( $order_id, 'checkout_video_clip', $is_single );
	}
	private function uploadDir( $file = false, $force = false ) {
		$uploadDir = $this->theUploadDir;
		if( $this->theUploadDir === false ) {
			$uploadDir = wp_get_upload_dir();
			$uploadDir[ 'basedir' ] = $uploadDir[ 'basedir' ] . '/checkout-video';
			$uploadDir[ 'baseurl' ] = $uploadDir[ 'baseurl' ] . '/checkout-video';
			$this->theUploadDir = $uploadDir;
		}
		// wp_die( print_r( $uploadDir ) );
		$basedir = $uploadDir[ 'basedir' ];
		if( ! is_dir( $basedir ) ) {wp_mkdir_p( $basedir );}
		return ( $file && file_exists( $basedir . '/' . $file ) ) ? $basedir . '/' . $file : ( ( $force ) ? $basedir . '/' . $file : $basedir );
	}
	public function woo_remove_item_data( $cart_item_key = null, $key = null ) {
		// https://gist.github.com/RadGH/e3444fc661554a0f8c6f
		// https://stackoverflow.com/questions/29965578/woocommerce-how-do-i-add-metadata-to-a-cart-item
		// https://woocommerce.github.io/code-reference/classes/WC-Session.html
		$data = (array) WC()->session->get( 'checkout_video_clip' );
		// If no item is specified, delete *all* item data. This happens when we clear the cart (eg, completed checkout)
		if( $cart_item_key == null ) {
			$data = [];
		}
		WC()->session->set( 'checkout_video_clip', $data );
	}
	public function woocommerce_admin_order_preview_end() {
		?>
		<# if ( data.checkout_video_clip ) { #>
			<div class="fwp-video-player-wraper">
				<div class="fwp-video-wrap">

					<h2><?php esc_html_e( 'Attached Video Clip', 'woocommerce-checkout-video-snippet' ); ?></h2>
					<div class="fwp-tabs__container">
						<div class="fwp-tabs__wrap">
							<div class="fwp-tabs__navs">
								<div class="fwp-tabs__nav-item active" data-target="#the-qrcode"><?php esc_html_e( 'Scan Code', 'woocommerce-checkout-video-snippet' ); ?></div>
								<div class="fwp-tabs__nav-item" data-target="#the-video"><?php esc_html_e( 'Play Video', 'woocommerce-checkout-video-snippet' ); ?></div>
							</div>
							<div class="fwp-tabs__tabs-field">
								<div class="fwp-tabs__content active" id="the-qrcode">
									<canvas class="fwp-qrzone-field" data-code="{{ data.checkout_video_clip.shortnedQR }}"></canvas>
									<p class="qrcode-subtitle">{{ data.checkout_video_clip.shortned }}</p>
								</div>
								<div class="fwp-tabs__content" id="the-video">
									<div class="fwp-video-player-wraper">
										<div class="fwp-video-wrap">
											<video playsinline class="video-js vjs-default-skin fwp-videojs-playing-field" controls preload="auto" data-temp-poster="" data-setup='{ "controls": true, "autoplay": false, "preload": "none" }'>
												<source src="{{ data.checkout_video_clip.full_url }}" type="{{ data.checkout_video_clip.type }}"></source>
												<p class="vjs-no-js">
													<?php esc_html_e( 'To view this video please enable JavaScript, and consider upgrading to a
													web browser that', 'woocommerce-checkout-video-snippet' ); ?>
													<a href="https://videojs.com/html5-video-support/" target="_blank">
														<?php esc_html_e( 'supports HTML5 video', 'woocommerce-checkout-video-snippet' ); ?>
													</a>
												</p>
											</video>
											<!-- <a class="fwp-metabox-download-button" href="<?php echo esc_url( $meta['full_url'] ); ?>" download="<?php echo esc_url( $meta['name'] ); ?>"><?php esc_html_e( 'Download this Video', 'woocommerce-checkout-video-snippet' ); ?></a> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		<# } #>
		<?php
	}
	public function woocommerce_admin_order_preview_get_order_details( $data, $order ) {
		$meta = get_post_meta( $order->get_id(), 'checkout_video_clip', true );
		if( $meta && isset( $meta[ 'full_url' ] ) ) {
			$meta[ 'shortned' ] = str_replace( [ 'https://www.', 'http://www.' ], [ '', '' ], site_url( '/clip/' . dechex( $order->get_id() ) ) );
			$meta[ 'shortnedQR' ] = site_url( '/clip/' . dechex( $order->get_id() ) );
			$data[ 'checkout_video_clip' ] = $meta;
		}
		return $data;
	}
	public function woocommerce_admin_order_preview_actions( $actions, $order ) {
		$meta = get_post_meta( $order->get_id(), 'checkout_video_clip', true );
		if( $meta && isset( $meta[ 'full_url' ] ) ) {
			$actions[ 'checkout_video_clip' ] = [
				'group'   => __( 'Download: ', 'woocommerce-checkout-video-snippet' ),
				'actions' => [
					[
						'url'		 => esc_url( $meta[ 'full_url' ] ),
						// 'url'    => wp_nonce_url( admin_url( 'admin-post.php?action=futurewordpress/project/filesystem/download&order_id=' . $order->get_id() ), 'futurewordpress_project_nonce' ),
						'name'   => __( 'Download Video', 'woocommerce-checkout-video-snippet' ),
						'title'  => __( 'Checkout video clip download.', 'woocommerce-checkout-video-snippet' ),
						'action' => 'download-clip'
					]
				],
			];
		}
		return $actions;
	}

	public function woocommerce_thankyou( $order_id ) {
		$meta = (array) get_post_meta( $order_id, 'checkout_video_clip', true );
		if( ! isset( $meta['full_url'] ) )  {return;}
		?>
		<div class="fwp-video-player-wraper">
			<div class="fwp-video-wrap">
				<strong><?php esc_html_e( 'Attached Clip', 'woocommerce-checkout-video-snippet' ); ?></strong>
				<video playsinline class="video-js vjs-default-skin fwp-videojs-playing-field" controls preload="auto" data-temp-poster="" data-setup='{}'>
					<source src="<?php echo esc_url( $meta['full_url'] ); ?>" type="<?php echo esc_attr( $meta['type'] ); ?>"></source>
					<p class="vjs-no-js">
						<?php esc_html_e( 'To view this video please enable JavaScript, and consider upgrading to a
						web browser that', 'woocommerce-checkout-video-snippet' ); ?>
						<a href="https://videojs.com/html5-video-support/" target="_blank">
							<?php esc_html_e( 'supports HTML5 video', 'woocommerce-checkout-video-snippet' ); ?>
						</a>
					</p>
				</video>
			</div>
		</div>
		<?php
	}
	public function wp_trash_post( $order_id ) {
		if( get_post_type($order_id) !== 'shop_order' ) {return;}
		$meta = get_post_meta( $order_id, 'checkout_video_clip', true );
		if( isset( $meta[ 'full_path' ] ) && file_exists( $meta[ 'full_path' ] ) && ! is_dir( $meta[ 'full_path' ] ) ) {
			unlink( $meta[ 'full_path' ] );
		}
	}
	public function remove_expired_clips() {
		global $wp, $wpdb;
		$dateNow = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( "-1 month" ) ), 'Y-m-d H:i:s' );
		$get_results = $wpdb->get_results( $wpdb->prepare(
			"SELECT m.*, p.* FROM {$wpdb->postmeta} AS m LEFT JOIN {$wpdb->posts} AS p ON p.ID = m.post_id WHERE m.meta_key = %s AND p.post_type = %s AND m.meta_value != '' AND p.post_date_gmt < %s;", 
			'checkout_video_clip', 'shop_order', $dateNow
		), ARRAY_A );
		foreach( $get_results as $i => $res ) {
			$res[ 'meta_value' ] = maybe_unserialize( $res[ 'meta_value' ] );
			$meta = $res[ 'meta_value' ];
			if( isset( $meta[ 'full_path' ] ) && file_exists( $meta[ 'full_path' ] ) && ! is_dir( $meta[ 'full_path' ] ) 
			&& unlink( $meta[ 'full_path' ] ) 
			) {
				$meta[ 'full_path' ] = false;$meta[ 'expired' ] = $dateNow;
				update_post_meta( $res[ 'post_id' ], 'checkout_video_clip', $meta );
			}
		}
		// print_r( $get_results );wp_die();
	}
}
