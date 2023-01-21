<?php
/**
 * This plugin ordered by a client and done by Remal Mahmud (fiverr.com/mahmud_remal). Authority dedicated to that cient.
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Checkout Video
 * Plugin URI:        https://github.com/mahmudremal/woocommerce-checkout-video-snippet/
 * Description:       Woocommerce endline order video integration plugin made with love by Remal Mahmud.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Remal Mahmud
 * Author URI:        https://github.com/mahmudremal/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woocommerce-checkout-video-snippet
 * Domain Path:       /languages
 * 
 * @package FWPWooXstoreChild
 * @author  Remal Mahmud (https://github.com/mahmudremal)
 * @version 1.0.1
 * @link https://github.com/mahmudremal/woocommerce-checkout-video-snippet/
 * @category	WooComerce Plugin
 * @copyright	Copyright (c) 2023-25
 * 
 * payment custom link https://mysite.com/checkout/payment/39230/?pay_for_order=true&key=wc_order_UWdhxxxYYYzzz or get link $order->get_checkout_payment_url();
 */

/**
 * Bootstrap the plugin.
 */

defined( 'FWPWOOXSTORECHILD_PROJECT__FILE__' ) || define( 'FWPWOOXSTORECHILD_PROJECT__FILE__', untrailingslashit( __FILE__ ) );
defined( 'FWPWOOXSTORECHILD_DIR_PATH' ) || define( 'FWPWOOXSTORECHILD_DIR_PATH', untrailingslashit( plugin_dir_path( FWPWOOXSTORECHILD_PROJECT__FILE__ ) ) );
defined( 'FWPWOOXSTORECHILD_DIR_URI' ) || define( 'FWPWOOXSTORECHILD_DIR_URI', untrailingslashit( plugin_dir_url( FWPWOOXSTORECHILD_PROJECT__FILE__ ) ) );
defined( 'FWPWOOXSTORECHILD_BUILD_URI' ) || define( 'FWPWOOXSTORECHILD_BUILD_URI', untrailingslashit( FWPWOOXSTORECHILD_DIR_URI ) . '/assets/build' );
defined( 'FWPWOOXSTORECHILD_BUILD_PATH' ) || define( 'FWPWOOXSTORECHILD_BUILD_PATH', untrailingslashit( FWPWOOXSTORECHILD_DIR_PATH ) . '/assets/build' );
defined( 'FWPWOOXSTORECHILD_BUILD_JS_URI' ) || define( 'FWPWOOXSTORECHILD_BUILD_JS_URI', untrailingslashit( FWPWOOXSTORECHILD_DIR_URI ) . '/assets/build/js' );
defined( 'FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH' ) || define( 'FWPWOOXSTORECHILD_BUILD_JS_DIR_PATH', untrailingslashit( FWPWOOXSTORECHILD_DIR_PATH ) . '/assets/build/js' );
defined( 'FWPWOOXSTORECHILD_BUILD_IMG_URI' ) || define( 'FWPWOOXSTORECHILD_BUILD_IMG_URI', untrailingslashit( FWPWOOXSTORECHILD_DIR_URI ) . '/assets/build/src/img' );
defined( 'FWPWOOXSTORECHILD_BUILD_CSS_URI' ) || define( 'FWPWOOXSTORECHILD_BUILD_CSS_URI', untrailingslashit( FWPWOOXSTORECHILD_DIR_URI ) . '/assets/build/css' );
defined( 'FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH' ) || define( 'FWPWOOXSTORECHILD_BUILD_CSS_DIR_PATH', untrailingslashit( FWPWOOXSTORECHILD_DIR_PATH ) . '/assets/build/css' );
defined( 'FWPWOOXSTORECHILD_BUILD_LIB_URI' ) || define( 'FWPWOOXSTORECHILD_BUILD_LIB_URI', untrailingslashit( FWPWOOXSTORECHILD_DIR_URI ) . '/assets/build/library' );
defined( 'FWPWOOXSTORECHILD_ARCHIVE_POST_PER_PAGE' ) || define( 'FWPWOOXSTORECHILD_ARCHIVE_POST_PER_PAGE', 9 );
defined( 'FWPWOOXSTORECHILD_SEARCH_RESULTS_POST_PER_PAGE' ) || define( 'FWPWOOXSTORECHILD_SEARCH_RESULTS_POST_PER_PAGE', 9 );

require_once FWPWOOXSTORECHILD_DIR_PATH . '/inc/helpers/autoloader.php';
require_once FWPWOOXSTORECHILD_DIR_PATH . '/inc/helpers/template-tags.php';

if( ! function_exists( 'future_wordpress_starting_xstore_child_get_theme_instance' ) ) {
	function future_wordpress_starting_xstore_child_get_theme_instance() {\FWPWOOXSTORECHILD_THEME\Inc\Project::get_instance();}
}
future_wordpress_starting_xstore_child_get_theme_instance();



