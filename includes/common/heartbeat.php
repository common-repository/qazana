<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Heartbeat {

	/**
	 * Handle the post lock in the editor.
	 *
     * @access public
	 * @since 1.0.0
	 *
	 * @param array $response
	 * @param array $data
	 *
	 * @return array
	 */
	public function heartbeat_received( $response, $data ) {
		if ( isset( $data['qazana_post_lock']['post_ID'] ) ) {
			$post_id = $data['qazana_post_lock']['post_ID'];
			$locked_user = qazana()->editor->get_locked_user( $post_id );

			if ( ! $locked_user || ! empty( $data['qazana_force_post_lock'] ) ) {
				qazana()->editor->lock_post( $post_id );
			} else {
				$response['locked_user'] = $locked_user->display_name;
			}

            $response['qazanaNonce'] = qazana()->editor->create_nonce();
		}
		return $response;
    }

    /**
	 * @access public
	 */
	public function refresh_nonces( $response, $data ) {
		if ( isset( $data['qazana_post_lock']['post_ID'] ) ) {
			$response['qazana-refresh-nonces'] = [
				'qazanaNonce' => qazana()->editor->create_nonce(),
				'heartbeatNonce' => wp_create_nonce( 'heartbeat-nonce' ),
			];
		}

		return $response;
	}

	/**
	 * Heartbeat constructor.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'heartbeat_received', [ $this, 'heartbeat_received' ], 10, 2 );
		add_filter( 'wp_refresh_nonces', [ $this, 'refresh_nonces' ], 30, 2 );
	}
}
