<?php
/**
 * Register Post Types
 *
 * @package FWPWooXstoreChild
 */

namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;

class Register_Post_Types {
	use Singleton;

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'create_movie_cpt' ], 0 );

	}

	// Register Custom Post Type Movie
	public function create_movie_cpt() {

		$labels = [
			'name'                  => _x( 'Movies', 'Post Type General Name', 'woocommerce-checkout-video-snippet' ),
			'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'woocommerce-checkout-video-snippet' ),
			'menu_name'             => _x( 'Movies', 'Admin Menu text', 'woocommerce-checkout-video-snippet' ),
			'name_admin_bar'        => _x( 'Movie', 'Add New on Toolbar', 'woocommerce-checkout-video-snippet' ),
			'archives'              => __( 'Movie Archives', 'woocommerce-checkout-video-snippet' ),
			'attributes'            => __( 'Movie Attributes', 'woocommerce-checkout-video-snippet' ),
			'parent_item_colon'     => __( 'Parent Movie:', 'woocommerce-checkout-video-snippet' ),
			'all_items'             => __( 'All Movies', 'woocommerce-checkout-video-snippet' ),
			'add_new_item'          => __( 'Add New Movie', 'woocommerce-checkout-video-snippet' ),
			'add_new'               => __( 'Add New', 'woocommerce-checkout-video-snippet' ),
			'new_item'              => __( 'New Movie', 'woocommerce-checkout-video-snippet' ),
			'edit_item'             => __( 'Edit Movie', 'woocommerce-checkout-video-snippet' ),
			'update_item'           => __( 'Update Movie', 'woocommerce-checkout-video-snippet' ),
			'view_item'             => __( 'View Movie', 'woocommerce-checkout-video-snippet' ),
			'view_items'            => __( 'View Movies', 'woocommerce-checkout-video-snippet' ),
			'search_items'          => __( 'Search Movie', 'woocommerce-checkout-video-snippet' ),
			'not_found'             => __( 'Not found', 'woocommerce-checkout-video-snippet' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'woocommerce-checkout-video-snippet' ),
			'featured_image'        => __( 'Featured Image', 'woocommerce-checkout-video-snippet' ),
			'set_featured_image'    => __( 'Set featured image', 'woocommerce-checkout-video-snippet' ),
			'remove_featured_image' => __( 'Remove featured image', 'woocommerce-checkout-video-snippet' ),
			'use_featured_image'    => __( 'Use as featured image', 'woocommerce-checkout-video-snippet' ),
			'insert_into_item'      => __( 'Insert into Movie', 'woocommerce-checkout-video-snippet' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Movie', 'woocommerce-checkout-video-snippet' ),
			'items_list'            => __( 'Movies list', 'woocommerce-checkout-video-snippet' ),
			'items_list_navigation' => __( 'Movies list navigation', 'woocommerce-checkout-video-snippet' ),
			'filter_items_list'     => __( 'Filter Movies list', 'woocommerce-checkout-video-snippet' ),
		];
		$args   = [
			'label'               => __( 'Movie', 'woocommerce-checkout-video-snippet' ),
			'description'         => __( 'The movies', 'woocommerce-checkout-video-snippet' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-video-alt',
			'supports'            => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'author',
				'comments',
				'trackbacks',
				'page-attributes',
				'custom-fields',
			],
			'taxonomies'          => [],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		];

		register_post_type( 'movies', $args );

	}


}
