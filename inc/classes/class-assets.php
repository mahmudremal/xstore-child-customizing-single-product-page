<?php
/**
 * Enqueue theme assets
 *
 * @package FWPWooXstoreChild
 */


namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;

class Assets {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		/**
		 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend,
		 * except when is_admin() is used to include them conditionally
		 */
		// add_action( 'enqueue_block_assets', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
	}

	public function register_styles() {
		// Register styles.
		// wp_register_style( 'bootstrap-css', FWPWOOXSTORECHILD_BUILD_LIB_URI . '/css/bootstrap.min.css', [], false, 'all' );
		// wp_register_style( 'slick-css', FWPWOOXSTORECHILD_BUILD_LIB_URI . '/css/slick.css', [], false, 'all' );
		// wp_register_style( 'slick-theme-css', FWPWOOXSTORECHILD_BUILD_LIB_URI . '/css/slick-theme.css', ['slick-css'], false, 'all' );

		wp_register_style( 'FWPWOOXSTORECHILDVideo', FWPWOOXSTORECHILD_BUILD_CSS_URI . '/frontend.css', [], $this->filemtime( FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH . '/frontend.css' ), 'all' );
		wp_register_style( 'FWPWOOXSTORECHILDVideo-checkout', FWPWOOXSTORECHILD_BUILD_CSS_URI . '/checkout.css', [], $this->filemtime( FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH . '/checkout.css' ), 'all' );

		// Enqueue Styles.
		if( $this->allow_enqueue() ) {wp_enqueue_style( 'FWPWOOXSTORECHILDVideo' );wp_enqueue_style( 'FWPWOOXSTORECHILDVideo-checkout' );}

		// wp_enqueue_style( 'bootstrap-css' );
		// wp_enqueue_style( 'slick-css' );
		// wp_enqueue_style( 'slick-theme-css' );

	}

	public function register_scripts() {
		// Register scripts.
		// wp_register_script( 'slick-js', FWPWOOXSTORECHILD_BUILD_LIB_URI . '/js/slick.min.js', ['jquery'], false, true );
		wp_register_script( 'FWPWOOXSTORECHILDVideo', FWPWOOXSTORECHILD_BUILD_JS_URI . '/frontend.js', ['jquery'], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/frontend.js' ), true );
		wp_register_script( 'FWPWOOXSTORECHILDVideo-checkout', FWPWOOXSTORECHILD_BUILD_JS_URI . '/checkout.js', ['FWPWOOXSTORECHILDVideo'], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/checkout.js' ), true );
		// wp_register_script( 'single-js', FWPWOOXSTORECHILD_BUILD_JS_URI . '/single.js', ['jquery', 'slick-js'], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/single.js' ), true );
		// wp_register_script( 'author-js', FWPWOOXSTORECHILD_BUILD_JS_URI . '/author.js', ['jquery'], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/author.js' ), true );
		// wp_register_script( 'bootstrap-js', FWPWOOXSTORECHILD_BUILD_LIB_URI . '/js/bootstrap.min.js', ['jquery'], false, true );

		// Enqueue Scripts.
		// Both of is_order_received_page() and is_wc_endpoint_url( 'order-received' ) will work to check if you are on the thankyou page in the frontend.
		if( $this->allow_enqueue() ) {wp_enqueue_script( 'FWPWOOXSTORECHILDVideo' );wp_enqueue_script( 'FWPWOOXSTORECHILDVideo-checkout' );}
		
		// wp_enqueue_script( 'bootstrap-js' );
		// wp_enqueue_script( 'slick-js' );

		// If single post page
		// if ( is_single() ) {
		// 	wp_enqueue_script( 'single-js' );
		// }

		// If author archive page
		// if ( is_author() ) {
		// 	wp_enqueue_script( 'author-js' );
		// }
		// 

		wp_localize_script( 'FWPWOOXSTORECHILDVideo', 'fwpSiteConfig', [
			'ajaxUrl'    		=> admin_url( 'admin-ajax.php' ),
			'ajax_nonce' 		=> wp_create_nonce( 'futurewordpress_project_nonce' ),
			'buildPath'  		=> FWPWOOXSTORECHILD_BUILD_URI,
			'sureToSubmit'	=> __( 'Want to submit it? You can retake.', 'woocommerce-checkout-video-snippet' ),
			'videoClips'		=> (array) WC()->session->get( 'checkout_video_clip' ),
			'i18n'					=> [
				'uploading'								=> __( 'Uploading', 'woocommerce-checkout-video-snippet' ),
				'click_here'								=> __( 'Click here', 'woocommerce-checkout-video-snippet' ),
			],
		] );
	}
	private function allow_enqueue() {
		return ( function_exists( 'is_checkout' ) && ( is_checkout() || is_order_received_page() || is_wc_endpoint_url( 'order-received' ) ) );
	}

	/**
	 * Enqueue editor scripts and styles.
	 */
	public function enqueue_editor_assets() {

		$asset_config_file = sprintf( '%s/assets.php', FWPWOOXSTORECHILD_BUILD_PATH );

		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}

		$asset_config = require_once $asset_config_file;

		if ( empty( $asset_config['js/editor.js'] ) ) {
			return;
		}

		$editor_asset    = $asset_config['js/editor.js'];
		$js_dependencies = ( ! empty( $editor_asset['dependencies'] ) ) ? $editor_asset['dependencies'] : [];
		$version         = ( ! empty( $editor_asset['version'] ) ) ? $editor_asset['version'] : $this->filemtime( $asset_config_file );

		// Theme Gutenberg blocks JS.
		if ( is_admin() ) {
			wp_enqueue_script(
				'aquila-blocks-js',
				FWPWOOXSTORECHILD_BUILD_JS_URI . '/blocks.js',
				$js_dependencies,
				$version,
				true
			);
		}

		// Theme Gutenberg blocks CSS.
		$css_dependencies = [
			'wp-block-library-theme',
			'wp-block-library',
		];

		wp_enqueue_style(
			'aquila-blocks-css',
			FWPWOOXSTORECHILD_BUILD_CSS_URI . '/blocks.css',
			$css_dependencies,
			$this->filemtime( FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH . '/blocks.css' ),
			'all'
		);

	}
	public function admin_enqueue_scripts( $curr_page ) {
		global $post;
		if( ! in_array( $curr_page, [ 'edit.php', 'post.php' ] ) || 'shop_order' !== $post->post_type ) {return;}
		wp_register_style( 'FWPWOOXSTORECHILDVideoBackendCSS', FWPWOOXSTORECHILD_BUILD_CSS_URI . '/backend.css', [], $this->filemtime( FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH . '/backend.css' ), 'all' );
		wp_register_script( 'FWPWOOXSTORECHILDVideoBackendJS', FWPWOOXSTORECHILD_BUILD_JS_URI . '/backend.js', [ 'jquery' ], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/backend.js' ), true );
		// wp_register_script( 'FWPWOOXSTORECHILDVideoBackendJS-checkout', FWPWOOXSTORECHILD_BUILD_JS_URI . '/checkout.js', ['FWPWOOXSTORECHILDVideoBackendJS'], $this->filemtime( FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH . '/checkout.js' ), true );
		
		wp_enqueue_style( 'FWPWOOXSTORECHILDVideoBackendCSS' );
		wp_enqueue_script( 'FWPWOOXSTORECHILDVideoBackendJS' );
		// wp_enqueue_script( 'FWPWOOXSTORECHILDVideoBackendJS-checkout' );

		wp_localize_script( 'FWPWOOXSTORECHILDVideoBackendJS', 'fwpSiteConfig', [
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'futurewordpress_project_nonce' ),
			'buildPath'  => FWPWOOXSTORECHILD_BUILD_URI,
			'sureToSubmit' => __( 'Want to submit it? Canbe retaken.', 'woocommerce-checkout-video-snippet' ),
		] );
	}
	private function filemtime( $file ) {
		return ( file_exists( $file ) && ! is_dir( $file ) ) ? filemtime( $file ) : rand( 0, 9999 );
	}

}
