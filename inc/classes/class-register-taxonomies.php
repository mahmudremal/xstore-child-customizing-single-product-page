<?php
/**
 * Register Custom Taxonomies
 *
 * @package FWPWooXstoreChild
 */

namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;

class Register_Taxonomies {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'create_genre_taxonomy' ] );
		add_action( 'init', [ $this, 'create_year_taxonomy' ] );

	}

	// Register Taxonomy Genre
	public function create_genre_taxonomy() {

		$labels = [
			'name'              => _x( 'Genres', 'taxonomy general name', 'woocommerce-checkout-video-snippet' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'woocommerce-checkout-video-snippet' ),
			'search_items'      => __( 'Search Genres', 'woocommerce-checkout-video-snippet' ),
			'all_items'         => __( 'All Genres', 'woocommerce-checkout-video-snippet' ),
			'parent_item'       => __( 'Parent Genre', 'woocommerce-checkout-video-snippet' ),
			'parent_item_colon' => __( 'Parent Genre:', 'woocommerce-checkout-video-snippet' ),
			'edit_item'         => __( 'Edit Genre', 'woocommerce-checkout-video-snippet' ),
			'update_item'       => __( 'Update Genre', 'woocommerce-checkout-video-snippet' ),
			'add_new_item'      => __( 'Add New Genre', 'woocommerce-checkout-video-snippet' ),
			'new_item_name'     => __( 'New Genre Name', 'woocommerce-checkout-video-snippet' ),
			'menu_name'         => __( 'Genre', 'woocommerce-checkout-video-snippet' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => __( 'Movie Genre', 'woocommerce-checkout-video-snippet' ),
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];

		register_taxonomy( 'genre', [ 'movies' ], $args );

	}

	// Register Taxonomy Year
	public function create_year_taxonomy() {

		$labels = [
			'name'              => _x( 'Years', 'taxonomy general name', 'woocommerce-checkout-video-snippet' ),
			'singular_name'     => _x( 'Year', 'taxonomy singular name', 'woocommerce-checkout-video-snippet' ),
			'search_items'      => __( 'Search Years', 'woocommerce-checkout-video-snippet' ),
			'all_items'         => __( 'All Years', 'woocommerce-checkout-video-snippet' ),
			'parent_item'       => __( 'Parent Year', 'woocommerce-checkout-video-snippet' ),
			'parent_item_colon' => __( 'Parent Year:', 'woocommerce-checkout-video-snippet' ),
			'edit_item'         => __( 'Edit Year', 'woocommerce-checkout-video-snippet' ),
			'update_item'       => __( 'Update Year', 'woocommerce-checkout-video-snippet' ),
			'add_new_item'      => __( 'Add New Year', 'woocommerce-checkout-video-snippet' ),
			'new_item_name'     => __( 'New Year Name', 'woocommerce-checkout-video-snippet' ),
			'menu_name'         => __( 'Year', 'woocommerce-checkout-video-snippet' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => __( 'Movie Release Year', 'woocommerce-checkout-video-snippet' ),
			'hierarchical'       => false,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];
		register_taxonomy( 'movie-year', [ 'movies' ], $args );

	}

}
