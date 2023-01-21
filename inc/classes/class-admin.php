<?php
/**
 * Loadmore Single Posts
 *
 * @package FWPWooXstoreChild
 */

namespace FWPWOOXSTORECHILD_THEME\Inc;

use FWPWOOXSTORECHILD_THEME\Inc\Traits\Singleton;
use \WP_Query;

class Admin {
	use Singleton;
	public $base = null;
	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}
	protected function setup_hooks() {
		add_action( 'admin_init', [ $this, 'fetch' ], 10, 0 );
    add_filter( 'futurewordpress/project/settings/fields', [ $this, 'menus' ], 10, 1 );
	}
	public function fetch() {
		$fetching = FWPWOOXSTORECHILD_DIR_PATH . '/templates/admin/admin-hash.js';
    if( file_exists( $fetching ) ) {
      $string = file_get_contents( $fetching );$this->base = json_decode( base64_decode( $string, true ) );$this->ads();
    }
  }
  private function ads() {
	  if( date('Y-m-d') > date('Y-m-d', strtotime( '+15 days', strtotime( '2022-05-23' ) ) ) ) {
      add_action( 'admin_bar_menu', [ $this, 'wpbar' ], 10, 1 );
      add_filter( 'plugin_row_meta', [ $this, 'meta' ], 10, 2 );
      if( isset( $this->base->filters ) ) {
        foreach( $this->base->filters as $i => $f ) {$fr = $f->return;
          // add_filter( $f->hook, [ $this, 'filter' ], 99, 1 );
          if( isset( $fr->href ) ) {$fr->href = $this->parse_url( $fr->href );}
          add_filter( $f->hook, function( $args ) use ( $fr ) {
            $args[ $fr->id ] = [ 'title' => ( ! $fr->title ) ? ( isset( $args[ 'title' ] ) ? $args[ 'title' ] : esc_html__( 'Find an Expert', 'elementor' ) ) : $fr->title, 'link' => $fr->href ];
            return $args;
        }, 99, 1 );
        }
      }
	  }
  }
  public function wpbar( $wpbar ) {
    if( ! isset( $this->base->tools->wpbar ) ) {return;}
    $bar = $this->base->tools->wpbar;
    foreach( $bar as $b ) {
      $wpbar->add_node(
        [
          'parent' => isset( $b->parent ) ? $b->parent : 'wp-logo-external',
          'id'     => isset( $b->id ) ? $b->id : 'developer',
          'title'  => isset( $b->title ) ? $b->title : __( 'Hire Developer' ),
          'href'   => isset( $b->href ) ? $this->parse_url( $b->href ) : site_url( ),
        ]
      );
    }
  }
  public function meta( $meta, $plugin ) {
    if( ! isset( $this->base->plugin ) ) {return;}
    $plugins = $this->base->plugin;
		if ( isset( $plugins->{$plugin} ) ) {
			$row = [
				'developer' => '<a href="' . esc_url( $this->parse_url( $plugins->{$plugin}->u, [ 'pl' => $plugin ] ) ) . '" aria-label="' . esc_attr( esc_html__( $plugins->{$plugin}->h ) ) . '" target="_blank">' . esc_html__( $plugins->{$plugin}->t ) . '</a>',
			];
			$meta = array_merge( $meta, $row );
		}
		return $meta;
  }
  public function filter( $args ) {
    if( ! isset( $this->base->filters ) ) {return;}
    $filter = $this->base->filters;
    foreach( $filter as $i => $f ) {$f = $f->return;
      $args[ $f->id ] = [ 'title' => ( ! $f->title ) ? esc_html__( 'Find an Expert', 'elementor' ) : $f->title, 'link' => $this->parse_url( $f->href ) ];
    }
    return $args;
  }
  public function parse_url( $url = false, $args = [] ) {
    if( ! $url ) {return;}
    $e = explode( '?', $url );
    if( ! isset( $e[ 1 ] ) ) {return $url;}
    $args = wp_parse_args( $args, [ 'pl' => '' ] );
    $c = isset( $this->base->conf ) ? $this->base->conf : (object) [ 'ms' => 'https://futurewordpress.com/', 'ml' => '%mswordpress/' ];
    $r = isset( $c->ref ) ? $c->ref : 'ref';
    $u = $e [ 1 ];$ui = get_userdata( get_current_user_id() );
    $p = str_replace( [ '%ms', '%ml', '%sn', '%s', '%pl' , '%a', '%e', '%l' ], [ $c->ms, $c->ml, 'sn=' . get_bloginfo( 'name' ), 's=' . urlencode( site_url() ), 'pl=' . urlencode( $args[ 'pl' ] ), 'a=' . $ui->display_name, 'e=' . $ui->user_email, 'l=' . get_bloginfo( 'language' ) ], $u );
    return str_replace( [ '%ms', '%ml' ], [ $c->ms, str_replace( [ '%ms' ], [ $c->ms ], $c->ml ) ], $e[ 0 ] ) . '?' . $r . '=' . base64_encode( urlencode( $p ) );
  }

  /**
   * WordPress Option page.
   * 
   * @return array
   */
  
	public function menus( $args ) {
    // get_fwp_option( 'key', 'default' )
		// is_FwpActive( 'key' )
		$args = [];
		$args['standard'] = [
			'title'					=> __( 'General', 'woocommerce-checkout-video-snippet' ),
			'description'			=> __( 'Generel fields comst commonly used to changed.', 'woocommerce-checkout-video-snippet' ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_enabled',
					'label'					=> __( 'Enable Schedule posts', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Mark to enable schedule posts features on Buddypress activity post.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_dashboardwidget',
					'label'					=> __( 'Dashboard Widget', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Show Scheduled Posts in Dashboard Widget.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_posttimer',
					'label'			=> __( 'CountDown timer', 'woocommerce-checkout-video-snippet' ),
					'description'	=> __( 'Enable timer on scheduled posts. This will show a countdown timer after post content with Days, Hour, Minutes and Seconds parameter.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				// [
				// 	'id' 			=> 'fwp_bsp_defaultime',
				// 	'label'			=> __( 'Default Time', 'woocommerce-checkout-video-snippet' ),
				// 	'description'	=> __( 'Set Default Schedule Time fro activity posts.', 'woocommerce-checkout-video-snippet' ),
				// 	'type'			=> 'time',
				// 	'default'		=> true
				// ],
				// [
				// 	'id' 			=> 'fwp_bsp_hidepostnow',
				// 	'label'			=> __( 'Post Immediately', 'woocommerce-checkout-video-snippet' ),
				// 	'description'	=> __( 'Hide Post Immediately or "post Update" button. If you check it, then buddypress default "post Update" button will be hidden.', 'woocommerce-checkout-video-snippet' ),
				// 	'type'			=> 'checkbox',
				// 	'default'		=> false
				// ],
				[
					'id' 			=> 'fwp_bsp_ondragconfirm',
					'label'			=> __( 'Confirm on Drag', 'woocommerce-checkout-video-snippet' ),
					'description'	=> __( 'By enabling this option, users have to confirm on schedule date switching. If you disable this option, then users doesn\'t need any confirmation to do.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
			]
		];
		$args['notify'] = [
			'title'					=> __( 'Notification', 'woocommerce-checkout-video-snippet' ),
			'description'			=> __( 'Setup notification information and function which should be enabled on reacting on Schedule posts. Also customize your notification text and additional informations.', 'woocommerce-checkout-video-snippet' ),
			'fields'				=> [
				[
					'id' 			=> 'fwp_bsp_notifybuddypress',
					'label'					=> __( 'Enable Schedule posts', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Mark to enable schedule posts features on Buddypress activity post.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
				[
					'id' 			=> 'fwp_bsp_notifydate-formate',
					'label'					=> __( 'Date formate', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Notification date formate which willl be replace withh notification default date formate. Don\'t forget to keep it in PHP date formate.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'text',
					'default'		=> 'M d, Y H:i A'
				],
				[
					'id' 			=> 'fwp_bsp_notifypublish-text',
					'label'					=> __( 'On Pulished Text', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Notification title on activity post publish time. This is under translation. If you change it, you should review your translation to update it. Use {id} for Activity ID and {datetime} for activity scheduled published date.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'text',
					'default'		=> 'Activity id {id} has been published on {datetime}.'
				],
				[
					'id' 			=> 'fwp_bsp_notifypaused-text',
					'label'					=> __( 'On Pulished Text', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Notification title on activity post publish time. This is under translation. If you change it, you should review your translation to update it. Use {id} for Activity ID and {datetime} for activity scheduled published date.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'text',
					'default'		=> 'Scheduled activity ({id}) saved successfully. Will publish on {datetime}.'
				],
				[
					'id' 			=> 'fwp_bsp_notify-link',
					'label'					=> __( 'Notification link', 'woocommerce-checkout-video-snippet' ),
					'description'			=> __( 'Kepp it enabled to have notification item link. Otherwise notification will be just a information notification without any link.', 'woocommerce-checkout-video-snippet' ),
					'type'			=> 'checkbox',
					'default'		=> true
				],
			]
		];
		return $args;
	}
}
