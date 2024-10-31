<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Revisions_Manager {

	public function __construct() {
		self::register_actions();
	}

	public static function handle_revision() {
		add_filter( 'wp_save_post_revision_post_has_changed', '__return_true' );
		add_action( '_wp_put_post_revision', [ __CLASS__, 'save_revision' ] );
	}

	public static function get_revisions( $post_id = 0, $query_args = [], $parse_result = true ) {
		$post = get_post( $post_id );

		if ( ! $post || empty( $post->ID ) ) {
			return [];
		}

		$revisions = [];

		$query_args['meta_key'] = '_qazana_data';

		$posts = wp_get_post_revisions( $post->ID, $query_args );

		if ( ! $parse_result ) {
			return $posts;
		}

		/** @var \WP_Post $revision */
		foreach ( $posts as $revision ) {
			$date = date_i18n( _x( 'M j @ H:i', 'revision date format', 'qazana' ), strtotime( $revision->post_date ) );

			$human_time = human_time_diff( strtotime( $revision->post_date ), current_time( 'timestamp' ) );

			if ( false !== strpos( $revision->post_name, 'autosave' ) ) {
				$type = 'autosave';
			} else {
				$type = 'revision';
			}

			$revisions[] = [
				'id' => $revision->ID,
				'author' => get_the_author_meta( 'display_name' , $revision->post_author ),
				'date' => sprintf( __( '%1$s ago (%2$s)', 'qazana' ), $human_time, $date ),
				'type' => $type,
				'gravatar' => get_avatar( $revision->post_author, 22 ),
			];
		}

		return $revisions;
	}

	public static function save_revision( $revision_id ) {
		$parent_id = wp_is_post_revision( $revision_id );

		if ( ! $parent_id ) {
			return;
		}

		qazana()->db->copy_qazana_meta( $parent_id, $revision_id );
	}

	public static function restore_revision( $parent_id, $revision_id ) {
		qazana()->db->copy_qazana_meta( $revision_id, $parent_id );

		$post_css = new Post_CSS_File( $parent_id );

		$post_css->update();

	}

	public static function on_revision_data_request() {

		if ( empty( $_REQUEST['id'] ) ) {
			wp_send_json_error( 'You must set the revision ID' );
		}

		$revision = qazana()->db->get_plain_editor( $_REQUEST['id'] );

		if ( empty( $revision ) ) {
			wp_send_json_error( 'Invalid Revision' );
		}

		wp_send_json_success( $revision );
	}

	public static function on_delete_revision_request() {

		if ( empty( $_REQUEST['id'] ) ) {
			wp_send_json_error( __( 'You must set the id', 'qazana' ) );
		}

		$revision = qazana()->db->get_plain_editor( $_REQUEST['id'] );

		if ( empty( $revision ) ) {
			wp_send_json_error( __( 'Invalid Revision', 'qazana' ) );
		}

		$deleted = wp_delete_post_revision( $_REQUEST['id'] );

		if ( $deleted && ! is_wp_error( $deleted ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error( __( 'Cannot delete this Revision', 'qazana' ) );
		}
	}

	private static function register_actions() {
		add_action( 'wp_restore_post_revision', [ __CLASS__, 'restore_revision' ], 10, 2 );

		if ( Utils::is_ajax() ) {
			add_action( 'wp_ajax_qazana_get_revision_data', [ __CLASS__, 'on_revision_data_request' ] );
			add_action( 'wp_ajax_qazana_delete_revision', [ __CLASS__, 'on_delete_revision_request' ] );
		}
	}
}
