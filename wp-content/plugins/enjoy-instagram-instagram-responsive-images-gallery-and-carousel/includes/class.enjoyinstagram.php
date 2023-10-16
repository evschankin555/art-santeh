<?php
/**
 * This main plugin class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

final class EnjoyInstagram {

	/**
	 * Single plugin instance
	 *
	 * @var EnjoyInstagram
	 */
	protected static $instance;

	/**
	 * Plugin users array
	 *
	 * @var array
	 */
	private $_users;

	/**
	 * @var EnjoyInstagram_Settings
	 */
	public $settings;

	/**
	 * @var int
	 */
	private $_processed = 0;

	/**
	 * Returns single instance of the class
	 *
	 * @return EnjoyInstagram
	 * @since 1.0.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Construct
	 *
	 * @return void
	 */
	private function __construct() {
		$this->_load_required();
		do_action( 'enjoyinstagram_init' );

		// sync actions.
		add_action( 'init', [ $this, 'schedule_sync' ], 1 );
		add_filter( 'option_enjoyinstagram_images_captured', [ $this, 'force_image_captured' ] );
		add_filter( 'option_enjoy_instagram_options', [ $this, 'normalize_users' ] );
		$this->_users = (array) get_option( 'enjoy_instagram_options', [] );
	}

	/**
	 * Load required files
	 *
	 * @return void
	 */
	private function _load_required() {
		// common file
		require_once( 'class.enjoyinstagram-settings.php' );
		$this->settings = new EnjoyInstagram_Settings();

		require_once( 'class.enjoyinstagram-api-connection.php' );
		require_once( 'class.enjoyinstagram-db.php' );
		require_once( 'class.enjoyinstagram-scheduler.php' );
		require_once( 'class.enjoyinstagram-shortcodes.php' );
		// widgets
		require_once( 'widgets/widgets.php' );
		require_once( 'widgets/widgets_grid.php' );
		require_once( 'class.enjoyinstagram-dashboard-widget.php' );

		new Enjoy_Instagram_Dashboard_Widgets();

		// require admin class
		if ( is_admin() ) {
			require_once( 'class.enjoyinstagram-admin.php' );
		}

		EI_Scheduler::get_instance()->init();
	}

	/**
	 * Get the users array
	 *
	 * @return array
	 */
	public function get_users() {
		$users = [];
		foreach ( $this->_users as $user ) {
			if ( $user['active'] ) {
				$users[ $user['username'] ] = $user;
			}
		}

		return $users;
	}

	/**
	 * Get the users array
	 *
	 * @return array
	 */
	public function get_all_users() {
		return array_filter( $this->_users );
	}

	/**
	 * Get a single user by id
	 *
	 * @param string $id
	 *
	 * @return array|boolean
	 */
	public function get_user( $id ) {
		return isset( $this->_users[ $id ] ) ? $this->_users[ $id ] : false;
	}

	/**
	 * There is a business user?
	 *
	 * @return string|boolean
	 */
	public function has_business_user() {
		if ( empty( $this->_users ) ) {
			return false;
		}

		foreach ( $this->_users as $id => $data ) {
			if ( ! empty( $data['business'] ) ) {
				return $id;
			}
		}

		return false;
	}

	/**
	 * Add an user to main array
	 *
	 * @param string $id
	 * @param array $data
	 *
	 * @return void
	 */
	public function add_user( $id, $data ) {
		$data['active'] = enjoyinstagram()->is_premium();

		if ( empty( $this->_users ) ) {
			$data['active'] = true;
		}

		$this->_users[ $id ] = $data;

		update_option( 'enjoy_instagram_options', $this->_users );
		// force sync.
		delete_option( 'enjoyinstagram_sync_times' );
	}

	/**
	 * Remove an user
	 *
	 * @param string $id
	 *
	 * @return boolean
	 */
	public function remove_user( $id ) {
		unset( $this->_users[ $id ] );
		update_option( 'enjoy_instagram_options', $this->_users );

		ei_db()->delete_media_by_user( $id ); // delete also media and hashtags

		return true;
	}

	/**
	 * Update a single account data
	 *
	 * @param array $user
	 *
	 * @return void
	 *
	 * @since 6.1.0
	 */
	public function update_user( $user ) {
		if ( ! empty( $user ) ) {
			$this->_users[ $user['username'] ] = array_merge( $this->_users[ $user['username'] ], (array) $user );
			update_option( 'enjoy_instagram_options', $this->_users );
		}
	}

	/**
	 * Schedule event sync. Use cron if active, otherwise use custom
	 *
	 * @param boolean $force If force the update. Valid only for latest
	 *
	 * @return void
	 */
	public function schedule_sync( $force = false ) {

		if ( empty( $this->_users ) ) {
			return;
		}

		$times   = get_option( 'enjoyinstagram_sync_times', [] );
		$current = time();

		foreach ( $this->_users as $user => $user_data ) {
			$latest = isset( $times[ $user ] ) ? intval( $times[ $user ] ) : 0;
			if ( $current > ( $latest + DAY_IN_SECONDS ) ) {
				$this->sync_users_data( $user ); // sync also data
				$this->sync_media_event( $user, 'all' );
				// update time
				$times[ $user ] = $current;
			} elseif ( $force || ( $current > ( $latest + ( 5 * MINUTE_IN_SECONDS ) ) ) ) {
				$this->sync_media_event( $user );
				// update time
				$times[ $user ] = $current;
			}
		}

		update_option( 'enjoyinstagram_sync_times', $times );
	}

	/**
	 * Sync user data scheduled
	 *
	 * @param string $user
	 *
	 * @return void
	 */
	protected function sync_users_data( $user ) {

		if ( ! isset( $this->_users[ $user ] ) ) {
			return;
		}

		$user_data = $this->_users[ $user ];

		$api  = ei_api();
		$data = $api->get_user_profile( $user_data['id'], $user_data['access_token'], $user_data['business'] );

		if ( false === $data && ! empty( $api->last_error ) ) {
			enjoyinstagram_add_notice( $api->last_error, 'error' );
		} else {
			$this->_users[ $user ] = array_merge( $this->_users[ $user ], (array) $data );
		}

		update_option( 'enjoy_instagram_options', $this->_users );
	}

	/**
	 * Sync user media event scheduled
	 *
	 * @param string $to_sync The media to sync (latest|all)
	 * @param string $user
	 *
	 * @return void
	 */
	public function sync_media_event( $user, $to_sync = 'latest' ) {
		if ( ! isset( $this->_users[ $user ] ) ) {
			return;
		}

		$this->_processed = 0;
		$this->_sync( $user, $to_sync );
	}

	/**
	 * Sync media
	 *
	 * @param string $user
	 * @param string $to_sync
	 * @param string $offset
	 *
	 * @return void
	 */
	private function _sync( $user, $to_sync = 'latest', $offset = '' ) {

		$user_data = ! empty( $this->_users[ $user ] ) ? $this->_users[ $user ] : false;
		if ( ! $user_data ) {
			return;
		}

		$limit = get_option( 'enjoyinstagram_images_captured', 20 );
		if ( $this->_processed >= $limit ) {
			return;
		}

		$api    = ei_api();
		$medias = $api->get_user_media( $user_data, 33, $offset );

		if ( empty( $medias['data'] ) && ! empty( $api->last_error ) ) {
			$error_msg = sprintf(
			// translators: %s is the last error returned from the services
				__( 'An error occurred while syncing the media: %s', 'enjoy-instagram' ),
				$api->last_error
			);
			enjoyinstagram_add_notice( $error_msg, 'error' );

			return;
		}

		$cache_path = ei_get_cache_path();

		// ensure cache path exists
		if ( ! file_exists( $cache_path ) ) {
			wp_mkdir_p( $cache_path );
		}

		foreach ( $medias['data'] as $media ) {
			if ( empty( $media['image_id'] ) ) {
				continue;
			}

			if ( $this->_processed >= $limit ) {
				break;
			}

			ei_maybe_cache_image( $media['image_id'], $media['image_url'] );

			$media_id = ei_db()->add_in_main_table( $media );
			if ( $media_id && ! empty( $media['tags'] ) ) {
				foreach ( $media['tags'] as $tag ) {
					ei_db()->add_in_hashtag_table(
						[
							'image_id' => $media_id,
							'hashtag'  => esc_sql( $tag ),
						]
					);
				}
			}

			$this->_processed ++;
		}

		// continue sync.
		if ( 'all' === $to_sync && ! empty( $medias['next'] ) ) {
			$this->_sync( $user, $to_sync, $medias['next'] );
		}
	}

	/**
	 * Force the image limit if not premium
	 *
	 * @param string $val number of downloaded instagram images.
	 *
	 * @return string
	 */
	public function force_image_captured( $val ) {
		if ( ! $this->is_premium() ) {
			return '40';
		}

		return $val;
	}

	/**
	 * Check multi account permissions
	 *
	 * @param array $users
	 *
	 * @return array
	 */
	public function normalize_users( $users ) {
		$users            = array_filter( $users );
		$normalized_users = [];
		$one_active       = false;
		$first_id         = null;

		foreach ( $users as $id => &$user ) {
			if ( ! isset( $user['username'] ) ) {
				continue;
			}

			if ( ! isset( $user['last_token_refresh_timestamp'] ) ) {
				$user['last_token_refresh_timestamp'] = null;
			}

			if ( $this->is_premium() ) {
				$user['active'] = true;
			}

			if ( ! $first_id ) {
				$first_id = $id;
			}

			if ( ! isset( $user['active'] ) ) {
				$user['active'] = $this->is_premium();
			}

			if ( $one_active && $user['active'] && ! $this->is_premium() ) {
				$user['active'] = false;
			}

			if ( $user['active'] ) {
				$one_active = true;
			}

			$normalized_users[ $id ] = $user;
		}

		if ( ! empty( $users ) && ! $one_active ) {
			$normalized_users[ $first_id ]['active'] = true;
		}

		return $normalized_users;
	}

	/**
	 * Returns true if the enjoy-instagram-premium plugin is installed and active
	 *
	 * @return bool
	 */
	public function is_premium() {
		return is_plugin_active( 'enjoy-instagram-premium/enjoyinstagram-premium.php' ) && class_exists( 'Enjoyinstagram_Premium' );
	}
}

/**
 * Unique access to instance of EnjoyInstagram class
 *
 * @return EnjoyInstagram
 */
function enjoyinstagram() {
	return EnjoyInstagram::get_instance();
}
