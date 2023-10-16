<?php
/**
 * This class handles DB connection and other DB stuff
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

class EnjoyInstagram_DB {

	/**
	 * Main table name
	 *
	 * @var string
	 */
	protected $main_table = 'enjoy_instagram_media';

	/**
	 * Main table name
	 *
	 * @var string
	 */
	protected $hashtags_table = 'enjoy_instagram_hashtags';

	/**
	 * DB version
	 *
	 * @var string
	 */
	protected $db_version = '1.0.5';

	/**
	 * Single plugin instance
	 *
	 * @var EnjoyInstagram_DB
	 */
	protected static $instance;

	/**
	 * Returns single instance of the class
	 *
	 * @return EnjoyInstagram_DB
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
	public function __construct() {
		$this->init();
	}

	/**
	 * Init class variables
	 *
	 * @return void
	 */
	public function init() {
		global $wpdb;

		$this->main_table     = $wpdb->prefix . $this->main_table;
		$this->hashtags_table = $wpdb->prefix . $this->hashtags_table;

		// add tables
		$this->add_tables();
	}

	/**
	 * Add plugin tables
	 *
	 * @return void
	 * @since 9.0.0
	 */
	public function add_tables() {

		$current_db_version = get_option( 'enjoy_instagram_installed_db_version', '0' );
		if ( version_compare( $current_db_version, $this->db_version, '>=' ) ) {
			return;
		}

		global $wpdb;

		$sqls            = [];
		$charset_collate = $wpdb->get_charset_collate();

		$sqls['enjoy_instagram_media'] = "CREATE TABLE IF NOT EXISTS $this->main_table (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    image_id  varchar(255) DEFAULT '' NOT NULL,
                    image_link varchar(255) DEFAULT '' NOT NULL,
                    image_url text DEFAULT '' NOT NULL,
                    thumbnail_url text DEFAULT '' NOT NULL,
                    user varchar(225) DEFAULT '' NOT NULL,
                    caption longtext DEFAULT '' NOT NULL,
                    likes mediumint(9) DEFAULT 0 NOT NULL,
                    moderate varchar(20) DEFAULT '' NOT NULL,
                    date bigint(20) DEFAULT 0 NOT NULL,
                    UNIQUE KEY id (id)
                    ) $charset_collate;";

		$sqls['enjoy_instagram_hashtags'] = "CREATE TABLE IF NOT EXISTS $this->hashtags_table (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    image_id bigint(20) DEFAULT 0 NOT NULL,
                    hashtag varchar(255) DEFAULT '' NOT NULL,
                    moderate varchar(20) DEFAULT '' NOT NULL,
                    UNIQUE KEY id (id)
                    ) $charset_collate;";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		foreach ( $sqls as $sql ) {
			dbDelta( $sql );
		}

		switch ( $current_db_version ) {
			case '1.0.0':
				$wpdb->query( 'ALTER TABLE ' . $this->main_table . ' ADD date bigint(20) DEFAULT 0 NOT NULL' );
				delete_option( 'enjoyinstagram_sync_times' );
				break;
			case '1.0.2':
				$wpdb->query( 'ALTER TABLE ' . $this->main_table . " ADD thumbnail_url text DEFAULT '' NOT NULL AFTER image_url" );
				delete_option( 'enjoyinstagram_sync_times' );
				break;
			case '1.0.5':
				wp_mkdir_p( ei_get_cache_path() );
				break;
		}

		update_option( 'enjoy_instagram_installed_db_version', $this->db_version );
	}

	/**
	 * Resets the whole database.
	 *
	 * @return void
	 */
	public function reset() {
		global $wpdb;

		update_option( 'enjoy_instagram_installed_db_version', '0' );
		$wpdb->query( 'DROP TABLE ' . $this->main_table );
		$wpdb->query( 'DROP TABLE ' . $this->hashtags_table );
		update_option( 'enjoyinstagram_sync_times', [] );
	}

	/**
	 * Build where clause for queries
	 *
	 * @param array $where
	 *
	 * @return string
	 */
	public static function build_where( $where ) {
		$return = '1=1';

		if ( empty( $where ) ) {
			return $return;
		}

		foreach ( $where as $condition ) {
			if ( ! isset( $condition['compare'] ) ) {
				$condition['compare'] = '=';
			}

			if ( ! isset( $condition['relation'] ) ) {
				$condition['relation'] = 'AND';
			}

			$value = $condition['value'];
			if ( is_array( $value ) ) {
				$value = implode( "','", $value );
				$value = "('{$value}')";
			} elseif ( is_string( $value ) ) {
				$value = "'{$value}'";
			}

			$return .= " {$condition['relation']} {$condition['key']} {$condition['compare']} $value";
		}

		return $return;
	}

	/**
	 * Get media approved
	 *
	 * @param array $where
	 *
	 * @return mixed
	 */
	public function get_media( $where = [] ) {
		global $wpdb;
		$where = self::build_where( $where );

		return $wpdb->get_results( 'SELECT * FROM ' . $this->main_table . ' WHERE ' . $where . ' ORDER BY date DESC', ARRAY_A );
	}

	/**
	 * Get media approved
	 *
	 * @param array $where
	 *
	 * @return mixed
	 */
	public function get_media_by_hashtag( $where = [] ) {
		global $wpdb;
		$where = self::build_where( $where );

		$ids = $wpdb->get_col( 'SELECT DISTINCT image_id FROM ' . $this->hashtags_table . ' WHERE ' . $where );

		if ( empty( $ids ) ) {
			return [];
		}

		$ids = implode( ',', $ids );

		return $wpdb->get_results(
			'SELECT * FROM ' . $this->main_table . ' WHERE id IN (' . $ids . ') ORDER BY date DESC',
			ARRAY_A
		);
	}

	/**
	 * Moderate an image
	 *
	 * @param string $type
	 * @param array $args
	 *
	 * @return boolean
	 */
	public function moderate_image( $type, $args ) {
		global $wpdb;

		// phpcs:ignore
		extract( $args );

		/** @var string $moderate */
		/** @var string $value */

		if ( empty( $media_id ) ) {
			return false;
		}

		if ( 'hashtag' === $type ) {
			$wpdb->query( 'UPDATE ' . $this->hashtags_table . " SET moderate = '" . $moderate . "' WHERE hashtag = '" . $value . "' AND image_id = " . $media_id );
		} else {
			$wpdb->query( 'UPDATE ' . $this->main_table . " SET moderate = '" . $moderate . "' WHERE id = " . $media_id );
		}

		return true;
	}

	/**
	 * Get media for shortcode by user
	 *
	 * @param string $user
	 * @param array $hashtags
	 * @param boolean|string $moderate
	 * @param boolean|integer $limit
	 *
	 * @return array
	 */
	public function get_shortcode_media_user( $user, $hashtags = [], $moderate = false, $limit = false ) {
		global $wpdb;

		if ( false === $limit ) {
			$limit = get_option( 'enjoyinstagram_images_captured', 20 );
		}

		$where = "user = '{$user}'";

		if ( false !== $moderate ) {
			$where .= " AND moderate = '" . $moderate . "'";
		}

		if ( ! empty( $hashtags ) ) {
			$hashtags = implode( "','", $hashtags );
			$hashtags = str_replace( '#', '', $hashtags );
			$ids      = $wpdb->get_col( 'SELECT DISTINCT image_id FROM ' . $this->hashtags_table . " WHERE hashtag IN ('" . $hashtags . "')" );
			if ( empty( $ids ) ) {
				return [];
			}

			$ids   = implode( "','", $ids );
			$where = $where . " AND id IN ('{$ids}')";
		}

		$result = $wpdb->get_results(
			'SELECT * FROM ' . $this->main_table . ' WHERE ' . $where . ' ORDER BY date DESC LIMIT ' . $limit,
			ARRAY_A
		);

		return $result;
	}

	/**
	 * Get images id by hashtags
	 *
	 * @param array $hashtags
	 * @param boolean|string $moderate
	 * @param int|bool $limit
	 *
	 * @return array
	 */
	public function get_shortcode_media_hashtag( $hashtags, $moderate = false, $limit = false ) {
		global $wpdb;

		if ( false === $limit ) {
			$limit = get_option( 'enjoyinstagram_images_captured', 20 );
		}

		if ( empty( $hashtags ) ) {
			return [];
		}

		$hashtags       = implode( "','", $hashtags );
		$hashtags       = str_replace( '#', '', $hashtags );
		$where_moderate = ( false !== $moderate ) ? " AND moderate = '" . $moderate . "'" : '';
		$ids            = $wpdb->get_col( 'SELECT DISTINCT image_id FROM ' . $this->hashtags_table . " WHERE hashtag IN ('" . $hashtags . "') " . $where_moderate );
		if ( empty( $ids ) ) {
			return [];
		}

		$ids    = implode( "','", $ids );
		$result = $wpdb->get_results(
			'SELECT * FROM ' . $this->main_table . " WHERE id IN ('" . $ids . "') ORDER BY date DESC LIMIT " . $limit,
			ARRAY_A
		);

		return $result;
	}

	/**
	 * Add in main table
	 *
	 * @param array $data
	 *
	 * @return boolean|integer
	 */
	public function add_in_main_table( $data ) {
		global $wpdb;

		if ( empty( $data['image_id'] ) ) {
			return false;
		}

		$id         = $wpdb->get_var( 'SELECT id FROM ' . $this->main_table . " WHERE image_id = '" . $data['image_id'] . "'" );
		$table_cols = [
			'image_id'      => '%s',
			'image_link'    => '%s',
			'image_url'     => '%s',
			'thumbnail_url' => '%s',
			'user'          => '%s',
			'caption'       => '%s',
			'likes'         => '%d',
			'moderate'      => '%s',
			'date'          => '%d',
		];

		$insert = array_intersect_key( $data, $table_cols );
		$format = array_merge( $insert, array_intersect_key( $table_cols, $data ) );

		if ( empty( $id ) ) {
			$wpdb->insert( $this->main_table, $insert, $format );
			$id = $wpdb->insert_id;
		} else { // update
			$wpdb->update( $this->main_table, $insert, [ 'id' => $id ], $format, [ 'id' => '%d' ] );
		}

		return $id;
	}

	/**
	 * Delete a media
	 *
	 * @param string|integer $id
	 *
	 * @return void
	 */
	public function delete_media( $id ) {
		global $wpdb;

		$id = intval( $id );

		$wpdb->query( 'DELETE FROM ' . $this->main_table . ' WHERE id = ' . $id );
		$wpdb->query( 'DELETE FROM ' . $this->hashtags_table . ' WHERE image_id = ' . $id );
	}

	/**
	 * Delete media by user
	 *
	 * @param string $user_id
	 *
	 * @return void
	 */
	public function delete_media_by_user( $user_id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $this->main_table . ' WHERE user = %s', $user_id ) );
		$wpdb->query( 'DELETE FROM ' . $this->hashtags_table . ' WHERE image_id NOT IN ( SELECT id FROM ' . $this->main_table . ' )' );
	}

	/**
	 * Clean all media related data
	 *
	 * @return void
	 */
	public function clear_all() {
		global $wpdb;

		$wpdb->query( 'DELETE FROM ' . $this->main_table );
		$wpdb->query( 'DELETE FROM ' . $this->hashtags_table );
	}

	/**
	 * Add in hashtag table
	 *
	 * @param array $data
	 *
	 * @return boolean|integer
	 */
	public function add_in_hashtag_table( $data ) {
		global $wpdb;

		if ( empty( $data['image_id'] ) || empty( $data['hashtag'] ) ) {
			return false;
		}

		$id = $wpdb->get_var( 'SELECT id FROM ' . $this->hashtags_table . " WHERE image_id = '" . $data['image_id'] . "' AND hashtag = '" . $data['hashtag'] . "'" );
		if ( $id ) {
			return false;
		}

		$table_cols = [
			'image_id' => '%s',
			'hashtag'  => '%s',
			'moderate' => '%s',
		];

		$format = array_intersect_key( $table_cols, $data );
		$insert = array_intersect_key( $data, $table_cols );

		$wpdb->insert( $this->hashtags_table, $insert, $format );
		$id = $wpdb->insert_id;

		return $id;
	}

	/**
	 * Check if given hashtag exists in table
	 *
	 * @param string $hashtag
	 *
	 * @return boolean
	 */
	public function hashtag_exists( $hashtag ) {
		global $wpdb;
		$hashtag = esc_sql( $hashtag );
		$hashtag = $wpdb->get_var( 'SELECT hashtag FROM ' . $this->hashtags_table . " WHERE hashtag = '" . $hashtag . "' LIMIT 1" );

		return ! ! $hashtag;
	}

	/**
	 * Check if given user has media
	 *
	 * @param string $user
	 *
	 * @return boolean
	 */
	public function user_has_media( $user ) {
		global $wpdb;
		$user = $wpdb->get_var( 'SELECT user FROM ' . $this->main_table . " WHERE user = '" . $user . "' LIMIT 1" );

		return ! ! $user;
	}

	/**
	 * Builds the db stats for media and hashtags
	 *
	 * @return array[]
	 */
	public function stats() {
		global $wpdb;

		$media_approved   = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $this->main_table . ' WHERE moderate = "approved"' );
		$hashtag_approved = $wpdb->get_var( 'SELECT COUNT(DISTINCT(image_id)) FROM ' . $this->hashtags_table . ' WHERE moderate = "approved"' );

		$media_rejected   = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $this->main_table . ' WHERE moderate = "rejected"' );
		$hashtag_rejected = $wpdb->get_var( 'SELECT COUNT(DISTINCT(image_id)) FROM ' . $this->hashtags_table . ' WHERE moderate = "rejected"' );

		return [
			'media'              => [
				'count'     => $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $this->main_table ),
				'structure' => $wpdb->get_var( 'SHOW CREATE TABLE ' . $this->main_table, 1 ),
			],
			'hashtag'            => [
				'count'     => $wpdb->get_var( 'SELECT COUNT(DISTINCT(hashtag)) FROM ' . $this->hashtags_table ),
				'structure' => $wpdb->get_var( 'SHOW CREATE TABLE ' . $this->hashtags_table, 1 ),
			],
			'moderated_approved' => [
				'count' => $media_approved + $hashtag_approved,
			],
			'moderated_rejected' => [
				'count' => $media_rejected + $hashtag_rejected,
			],
		];
	}
}

/**
 * Unique access to instance of EnjoyInstagram_DB class
 *
 * @return EnjoyInstagram_DB
 */
function ei_db() {
	return EnjoyInstagram_DB::get_instance();
}

ei_db();
