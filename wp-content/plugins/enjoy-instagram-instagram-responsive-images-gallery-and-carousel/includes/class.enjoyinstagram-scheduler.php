<?php
/**
 * Class EI_Scheduler
 *
 * @since 6.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

final class EI_Scheduler {
	/**
	 * Single plugin instance
	 *
	 * @var EI_Scheduler
	 */
	protected static $instance;

	/**
	 * Returns single instance of the class
	 *
	 * @return EI_Scheduler
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Init the plugin scheduler inside the wp cron
	 *
	 * @return void
	 */
	public function init() {
		if ( ! wp_next_scheduled( 'ei_twicedaily' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'ei_twicedaily' );
		}

		add_action( 'ei_twicedaily', [ $this, 'do_token_refresh' ] );
	}

	/**
	 * Try refreshing user tokens
	 *
	 * @return void
	 */
	public function do_token_refresh() {
		$users = enjoyinstagram()->get_users();

		if ( empty( $users ) ) {
			return;
		}

		foreach ( $users as $user ) {
			if ( ! $user['business'] ) {

				$should_refresh = ! isset( $user['token_expires_timestamp'] );

				if ( ! $should_refresh ) {
					$threshold      = $user['token_expires_timestamp'] - ENJOYINSTAGRAM_REFRESH_THRESHOLD_OFFSET;
					$should_refresh = $threshold < time();
				}

				if ( $should_refresh ) {
					$api  = ei_api();
					$resp = $api->refresh_access_token( $user );

					if ( false === $resp ) {
						enjoyinstagram_add_notice( $api->last_error, 'error' );
						continue;
					}

					$now                                  = time();
					$user['access_token']                 = $resp['access_token'];
					$user['token_expires_timestamp']      = $now + (int) $resp['expires_in'];
					$user['last_token_refresh_timestamp'] = $now;

					enjoyinstagram()->update_user( $user );
				}
			}
		}
	}

	/**
	 * Remove EI schedules from the wp cron
	 *
	 * @return void
	 */
	public function unregister() {
		wp_clear_scheduled_hook( 'ei_twicedaily' );
	}
}
