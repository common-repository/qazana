<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cron {

	public static function activation() {
		wp_clear_scheduled_hook( 'qazana/tracker/send_event' );

		wp_schedule_event( time(), 'daily', 'qazana/tracker/send_event' );
	}

	public static function uninstall() {
		wp_clear_scheduled_hook( 'qazana/tracker/send_event' );
	}

	public function __construct() {
		register_activation_hook( qazana()->basename, [ __CLASS__, 'activation' ] );
		register_uninstall_hook( qazana()->basename, [ __CLASS__, 'uninstall' ] );
	}
}
