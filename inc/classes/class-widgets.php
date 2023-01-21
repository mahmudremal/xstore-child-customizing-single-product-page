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
 class Widgets {
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
		// add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		// add_action( 'widgets_init', [ $this, 'register_clock_widget' ] );
		/**
		 * Elementor Widgets register.
		 */
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ], 10, 1 );
	}
	/**
	 * Register widgets.
	 *
	 * @action widgets_init
	 */
	public function register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'woocommerce-checkout-video-snippet' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
		register_sidebar(
			[
				'name'          => esc_html__( 'Footer', 'woocommerce-checkout-video-snippet' ),
				'id'            => 'sidebar-2',
				'description'   => '',
				'before_widget' => '<div id="%1$s" class="widget widget-footer cell column %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			]
		);
	}
	public function register_clock_widget() {
		register_widget( 'FWPWOOXSTORECHILD_THEME\Inc\Clock_Widget' );
	}
  public function register_widgets( $widgets_manager ) {
    $file = FWPWOOXSTORECHILD_DIR_PATH . '/inc/widgets/elementor/widget-custom-category.php';
    if( ! file_exists( $file ) ) {return;}
    include_once $file;
    $widgets_manager->register( new Widgets_CustomCategory() );
  }
}
