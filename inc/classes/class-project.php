<?php
/**
 * Bootstraps the Theme.
 *
 * @package FWPWooXstoreChild
 */

namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;

class Project {
	use Singleton;

	protected function __construct() {

		// Load class.
		Assets::get_instance();
		Core::get_instance();
		// Widgets::get_instance();
		// Notices::get_instance();
		Admin::get_instance();
		// Bulks::get_instance();

		// Blocks::get_instance();
		// Menus::get_instance();
		Meta_Boxes::get_instance();
		Update::get_instance();
		Rewrite::get_instance();
		Shortcode::get_instance();
		// Register_Post_Types::get_instance();
		// Register_Taxonomies::get_instance();

		// $this->setup_hooks();
	}

	protected function setup_hooks() {
		add_action( 'body_class', [ $this, 'body_class' ], 10, 1 );
	}
	public function body_class( $classes ) {
		$classes = (array) $classes;
		$classes[] = 'FWPWOOXSTORECHILD';
		return $classes;
	}
}
