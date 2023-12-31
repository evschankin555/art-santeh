<?php
/**
 * This class handles API connection
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

class Enjoy_Instagram_Api_Connection {

	/**
	 * Single plugin instance
	 *
	 * @var Enjoy_Instagram_Api_Connection
	 */
	protected static $instance;

	/**
	 * Last error during api calls
	 *
	 * @var string
	 */
	public $last_error = '';

	/**
	 * Returns single instance of the class
	 *
	 * @return Enjoy_Instagram_Api_Connection
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param string $segment path in the graphql endpoint.
	 * @param string $access_token user access token.
	 * @param bool $is_business true if is a business account.
	 *
	 * @return array|bool
	 */
	public function get_user_profile( $segment, $access_token, $is_business ) {

		$meta   = [];
		$params = [
			'access_token' => $access_token,
			'fields'       => $is_business ? 'media_count,username,website,name,profile_picture_url,biography' : 'id,media_count,username,account_type',
		];

		$response = $this->_get_remote_data( $segment, $params, $is_business );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( $is_business ) {
			$meta_params   = [
				'fields'       => 'followers_count,media_count,follows_count',
				'access_token' => $access_token,
			];
			$meta_response = $this->_get_remote_data( $response['id'], $meta_params, $is_business );

			if ( ! is_wp_error( $meta_response ) ) {
				$meta = $meta_response;
			}
		}

		return [
			'business'        => $is_business,
			'username'        => $response['username'],
			'website'         => $is_business && isset( $response['website'] ) ? $response['website'] : '',
			'profile_picture' => $is_business && isset( $response['profile_picture_url'] ) ? $response['profile_picture_url'] : '',
			'bio'             => $is_business && isset( $response['biography'] ) ? $response['biography'] : '',
			'full_name'       => $is_business && isset( $response['name'] ) ? $response['name'] : '',
			'id'              => $response['id'],
			'count'           => $response['media_count'],
			'meta'            => $meta,
		];
	}

	/**
	 * Get user profiles
	 *
	 * @param string $access_token
	 * @param bool $is_business_profile
	 *
	 * @return array|false
	 */
	public function get_user_accounts( $access_token, $is_business_profile ) {

		$params['access_token'] = $access_token;
		$accounts               = [];
		$profiles               = [];

		if ( $is_business_profile ) {
			// check is the profile is a real business user
			$accounts = $this->get_business_accounts( $access_token );

			if ( empty( $accounts ) ) {
				$this->last_error = __(
					'There was an error with account connection; please, make sure to be a business account and try again!',
					'enjoy-instagram'
				);

				return false;
			}
		}

		// for basic display api users.
		if ( empty( $accounts ) ) {
			$accounts[] = [
				'id'           => 'me',
				'access_token' => $access_token,
			];
		}

		foreach ( $accounts as $acc ) {

			$profile = $this->get_user_profile( $acc['id'], $acc['access_token'], $is_business_profile );
			if ( is_array( $profile ) ) {
				$profile['access_token'] = $acc['access_token'];
				$profiles[]              = $profile;
			}
		}

		return $profiles;
	}

	/**
	 * Returns the IG user media files
	 *
	 * @param array $user
	 * @param int $limit
	 * @param string $next
	 *
	 * @return array
	 */
	public function get_user_media( $user, $limit = 20, $next = '' ) {

		$limit  = min( $limit, 200 );
		$return = [
			'data' => [],
			'next' => '',
		];

		if ( empty( $next ) ) {
			$response = $this->_get_remote_data(
				"{$user['id']}/media",
				[
					'fields'       => 'media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink,like_count,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}',
					'access_token' => $user['access_token'],
					'limit'        => $limit,
				],
				$user['business']
			);
		} else {
			$response = $this->_get_remote_data( $next );
		}

		if ( is_wp_error( $response ) ) {
			return $return;
		}

		$return['data'] = $this->map_media( $response['data'] );

		if ( isset( $response['paging'] ) && isset( $response['paging']['next'] ) && ! empty( $response['paging']['next'] ) ) {
			$return['next'] = $response['paging']['next'];
		}

		return $return;
	}

	/**
	 * Fetch remote API data
	 *
	 * @param string $segment
	 * @param array $params
	 * @param bool $graph_api
	 *
	 * @return array|WP_Error
	 */
	private function _get_remote_data( $segment = '', $params = [], $graph_api = true ) {

		if ( strpos( $segment, 'http' ) !== false ) {
			$url = $segment;
		} else {
			$api_url = $graph_api ? 'https://graph.facebook.com/' : 'https://graph.instagram.com/';
			$url     = $api_url . $segment . '?' . http_build_query( $params );
		}

		$args = [
			'timeout'   => 60,
			'sslverify' => false,
		];

		$this->last_error = '';
		$response         = wp_remote_get( $url, $args );

		if ( ! is_wp_error( $response ) ) {
			// certain ways of representing the html for double quotes causes errors so replaced here.
			$response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );

			if ( isset( $response['error'] ) ) {
				$this->last_error = $response['error']['message'];
				$response         = new WP_Error( $response['error']['code'], $response['error']['message'] );
			}
		}

		return $response;
	}

	/**
	 * Returns the id of each instagram account linked to the given access token
	 *
	 * @param string $access_token
	 *
	 * @return array
	 */
	public function get_business_accounts( $access_token ) {

		$data = $this->_get_remote_data(
			'me/accounts',
			[
				'access_token' => $access_token,
				'fields'       => 'instagram_business_account,access_token,name',
				'limit'        => 500,
			]
		);

		$accounts = [];

		if ( is_wp_error( $data ) || ! isset( $data['data'] ) ) {
			return $accounts;
		}

		foreach ( $data['data'] as $account ) {

			if ( empty( $account['instagram_business_account'] ) || empty( $account['instagram_business_account']['id'] ) ) {
				continue;
			}

			$accounts[] = [
				'id'           => $account['instagram_business_account']['id'],
				'access_token' => $account['access_token'],
			];
		}

		return $accounts;
	}

	/**
	 * Search business hashtag
	 *
	 * Get first the hashtag ID
	 * GET graph.facebook.com/ig_hashtag_search?user_id=17841405309211844&q=bluebottle&access_token={access-token}
	 * then get top media
	 * GET graph.facebook.com/{hashtag-id}/top_media?user_id={user-id}&fields=id,media_type,comments_count,like_count&access_token={access-token}
	 *
	 * @param array $user
	 * @param string $hashtag
	 *
	 * @return array
	 */
	public function search_business_hashtag( $user, $hashtag ) {

		$params       = [
			'user_id'      => $user['id'],
			'access_token' => $user['access_token'],
			'q'            => $hashtag,
		];
		$hashtag_data = $this->_get_remote_data( 'ig_hashtag_search', $params );

		if ( is_wp_error( $hashtag_data ) ) {
			return [];
		}

		if ( empty( $hashtag_data['data'] ) ) {
			return [];
		}

		$hashtag    = array_shift( $hashtag_data['data'] );
		$hashtag_id = intval( $hashtag['id'] );
		$params     = [
			'user_id'      => $user['id'],
			'access_token' => $user['access_token'],
			'fields'       => 'id,permalink,media_url,caption,like_count,media_type',
			'limit'        => min( 50, get_option( 'enjoyinstagram_images_captured' ) ),
		];
		$medias     = $this->_get_remote_data( "{$hashtag_id}/top_media", $params );

		if ( is_wp_error( $medias ) ) {
			return [];
		}

		if ( empty( $medias['data'] ) ) {
			return [];
		}

		$filtered_medias = [];
		foreach ( $medias['data'] as $media ) {
			if ( isset( $media['media_url'] ) && $media['media_url'] ) {
				$filtered_medias[] = $media;
			}
		}

		return $this->map_media( $filtered_medias );
	}

	/**
	 * Refresh the user access token
	 *
	 * @param array $user
	 *
	 * @return array|false
	 *
	 * @since 6.1.0
	 */
	public function refresh_access_token( $user ) {
		if ( ! $user['business'] ) {
			$resp = $this->_get_remote_data(
				'refresh_access_token',
				[
					'grant_type'   => 'ig_refresh_token',
					'access_token' => $user['access_token'],
				],
				false
			);

			if ( is_wp_error( $resp ) ) {
				$this->last_error = sprintf(
				// translators: api error while refreshing the user access token
					__(
						'There was an error while refreshing the access token form user %1$s: %2$s',
						'enjoy-instagram'
					),
					$user['username'],
					$resp->get_error_message()
				);

				return false;
			}

			return $resp;
		}
	}

	/**
	 * Map instagram media in a custom format
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function map_media( $data ) {

		$return = [];

		foreach ( $data as $media ) {

			$caption = isset( $media['caption'] ) ? sanitize_text_field( $media['caption'] ) : '';

			$thumb = null;

			if ( isset( $media['thumbnail_url'] ) ) {
				$thumb = $media['thumbnail_url'];
			} elseif ( 'IMAGE' === $media['media_type'] || 'CAROUSEL_ALBUM' === $media['media_type'] ) {
				$thumb = $media['permalink'] . 'media/';
			}

			$return[] = [
				'image_id'      => trim( $media['id'] ),
				'image_link'    => $media['permalink'],
				'image_url'     => $media['media_url'],
				'thumbnail_url' => $thumb,
				'user'          => isset( $media['username'] ) ? $media['username'] : '',
				'caption'       => isset( $media['caption'] ) ? $media['caption'] : '',
				'likes'         => isset( $media['like_count'] ) ? $media['like_count'] : 0,
				'tags'          => enjoyinstagram_extract_hashtags( $caption ),
				'date'          => isset( $media['timestamp'] ) ? strtotime( $media['timestamp'] ) : '',
			];
		}

		return $return;
	}
}

/**
 * Unique access to instance of Enjoy_Instagram_Api_Connection class
 *
 * @return Enjoy_Instagram_Api_Connection
 */
function ei_api() {
	return Enjoy_Instagram_Api_Connection::get_instance();
}
