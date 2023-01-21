<?php
/**
 * Theme Sidebars.
 *
 * @package FWPWooXstoreChild
 */
namespace FWPWOOXSTORECHILD_THEME\Inc;
use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;
/**
 * Class Widgets.
 */
class Rewrite {
	use Singleton;
	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}
	/**
	 * To register action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		/**
		 * Actions
		 */
		add_action( 'init', [ $this, 'init' ], 10, 1 );
		add_filter( 'query_vars', [ $this, 'query_vars' ], 10, 1 );
		add_filter( 'template_include', [ $this, 'template_include' ], 10, 1 );
	}
  public function init() {
		// add_rewrite_rule( 'clip/([^/]*)/([^/]*)/?', 'index.php?checkout_clip=$matches[1]&order_id=$matches[2]', 'top' );
		add_rewrite_rule( 'clip/([^/]*)/?', 'index.php?checkout_clip=$matches[1]', 'top' );
  }
	public function query_vars( $query_vars  ) {
		$query_vars[] = 'checkout_clip';
		$query_vars[] = 'clip';
		// $query_vars[] = 'order_id';
    return $query_vars;
	}
	public function template_include( $template ) {
    $checkout_clip = get_query_var( 'checkout_clip' );// $order_id = get_query_var( 'order_id' );
		if ( ( $checkout_clip == false || $checkout_clip == '' ) 
		// && ( $order_id == false || $order_id == '' )
		 ) {
      return $template;
    } else {
			$file = FWPWOOXSTORECHILD_DIR_PATH . '/templates/checkout/video-clip.php';
			if( file_exists( $file ) && ! is_dir( $file ) ) {
          return $file;
        } else {
          return $template;
        }
		}
	}
}
