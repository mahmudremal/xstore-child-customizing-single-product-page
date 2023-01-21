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
 class Helpers {
	use Singleton;
	private $dateFormate;
	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->dateFormate = 'd-M-Y H:i:s';
		$this->setup_hooks();
	}
	/**
	 * To register action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_filter( 'futurewordpress/project/filter/server/time', [ $this, 'serverTime' ], 10, 2 );
		add_filter( 'futurewordpress/project/filesystem/filemtime', [ $this, 'filemtime' ], 10, 2 );
	}
	/**
	 * Given a date in the timezone of the site, returns that date in UTC.
	 * 
	 * @return string
	 */
	public function serverTime( $time, $args = [] ) {
    return get_gmt_from_date( date( $this->dateFormate, $time ), $this->dateFormate );
  }
	/**
	 * Given a date in UTC or GMT timezone, returns that date in the timezone of the site.
	 * 
	 * @return string
	 */
	private function getLocalTime( $time, $args = [] ) {
    return get_date_from_gmt( date( $this->dateFormate, $time ), $this->dateFormate );
  }
	/**
	 * File Modification time.
	 * 
	 * @return string
	 */
	public function filemtime( $version, $path ) {
		return ( file_exists( $path ) && ! is_dir( $path ) ) ? filemtime( $path ) : $version;
	}
	/**
	 * Sending mail using filter.
	 * 
	 * @return void
	 */
	public function sendMail( $args = [] ) {
		$request = wp_parse_args( $args, [
			'id' => 0, 'to' => '', 'name' => '', 'email' => '', 'subject' => '', 'message' => ''
		] );
		// can be verify by "id" as company ID Author ID
		$to = $request[ 'to' ];
		$subject = $request[ 'subject' ];
		$body = $request[ 'message' ];
		$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
		$headers[] = 'Reply-To: ' . $request[ 'name' ] . ' <' . $request[ 'email' ] . '>';

		wp_mail( $to, $subject, $body, $headers );
		// $msg = [ 'status' => 'success', 'message' => __( get_fwp_option( 'msg_profile_edit_success_txt', 'Changes saved' ), FUTUREWORDPRESS_PROJECT_TEXT_DOMAIN ) ];
		// set_transient( 'status_successed_message-' . get_current_user_id(), $msg, 300 );
		wp_safe_redirect( wp_get_referer() );
  }

}
