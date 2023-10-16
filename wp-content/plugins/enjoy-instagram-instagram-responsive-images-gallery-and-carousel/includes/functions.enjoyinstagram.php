<?php
/**
 * Common functions for plugin Enjoy Instagram
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

/**
 * Get appearance admin settings
 *
 * @return array
 */
function enjoyinstagram_get_appearance_settings() {
	return [
		'link_image_options' => [
			'swipebox'  => __( 'LightBox Effect', 'enjoy-instagram' ),
			'instagram' => __( 'Instagram', 'enjoy-instagram' ),
			'nolink'    => __( 'No Link', 'enjoy-instagram' ),
			'altro'     => __( 'Custom', 'enjoy-instagram' ),
		],
		'grid_animation'     => [
			'random'            => 'Random',
			'fadeInOut'         => 'Fade In Out',
			'rotateLeft'        => 'Rotate Left',
			'rotateRight'       => 'Rotate Right',
			'rotateTop'         => 'Rotate Top',
			'rotateBottom'      => 'Rotate Bottom',
			'rotateLeftScale'   => 'Rotate Left Scale',
			'rotateRightScale'  => 'Rotate Right Scale',
			'rotateTopScale'    => 'Rotate Top Scale',
			'rotateBottomScale' => 'Rotate Bottom Scale',
			'rotate3d'          => 'Rotate 3D',
			'showHide'          => 'Show Hide',
			'slideLeft'         => 'Slide Left',
			'slideRight'        => 'Slide Right',
			'slideTop'          => 'Slide Top',
			'slideBottom'       => 'Slide Bottom',
		],
		'templates'          => [
			'default'  => __( 'Default', 'enjoy-instagram' ),
			'polaroid' => __( 'Polaroid', 'enjoy-instagram' ),
			'showcase' => __( 'Showcase', 'enjoy-instagram' ),
		],
	];
}

/**
 * Check HTTPS
 *
 * @return bool
 */
function ei_is_https() {
	return ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'];
}

/**
 * Defer parsing of JS
 *
 * @param string $url
 *
 * @return string
 */
function enjoyinstagram_defer_parsing_of_js( $url ) {
	if (
		false === strpos( $url, '.js' )
		|| strpos( $url, 'jquery.js' )
		|| false === strpos( $url, 'plugins/enjoy-instagram-premium' )
	) {
		return $url;
	}

	return "$url' defer '";
}

/**
 * Force image https if needed
 *
 * @param array $entry
 *
 * @return array
 */
function enjoyinstagram_force_image_https( $entry ) {
	if ( ei_is_https() ) {
		$entry['image_url']  = str_replace( 'http://', 'https://', $entry['image_url'] );
		$entry['image_link'] = str_replace( 'http://', 'https://', $entry['image_link'] );
	}

	return $entry;
}

/**
 * Format a media for plugin shortcode
 *
 * @param array $media
 * @param array $user_data
 *
 * @return array
 */
function enjoyinstagram_format_media_for_shortcode( $media, $user_data ) {

	$media['type'] = strpos( $media['image_url'], 'video' ) !== false ? 'video' : 'image';

	if ( 'video' === $media['type'] && empty( $media['thumbnail_url'] ) ) {
		// In public hashtag video has no thumb
		$media['thumbnail_url'] = ENJOYINSTAGRAM_ASSETS_URL . '/images/video_placeholder.jpeg';
	}

	// fix for 24 oct 2020, facebook deperecated the old url
	if ( 'image' === $media['type'] ) {
		$media['thumbnail_url'] = $media['image_url'];
	}

	$thumb_cache_exists = file_exists( ei_get_cached_image( $media['image_id'], 320 ) );

	if ( $thumb_cache_exists ) {
		$media['thumbnail_url'] = ei_get_cached_image_url( $media['image_id'], 320 );
	}

	$media['images'] = [
		'thumbnail'           => [ 'url' => $media['thumbnail_url'] ],
		'standard_resolution' => [ 'url' => $media['image_url'] ],
		'medium'              => [ 'url' => ei_get_cached_image_url( $media['image_id'], '640' ) ],
	];

	if ( ei_is_https() ) {
		$media['images']['thumbnail']['url']           = str_replace( 'http://', 'https://', $media['images']['thumbnail']['url'] );
		$media['images']['standard_resolution']['url'] = str_replace( 'http://', 'https://', $media['images']['standard_resolution']['url'] );
	}

	return [
		'media_id' => $media['id'],
		'ig_id'    => $media['image_id'],
		'link'     => $media['image_link'],
		'images'   => $media['images'],
		'type'     => $media['type'],
		'caption'  => [
			'text' => esc_attr( $media['caption'] ),
		],
		'likes'    => [
			'count' => $media['likes'],
		],
		'user'     => [
			'username'        => $media['user'],
			'profile_picture' => ! empty( $user_data ) ? $user_data['profile_picture'] : '',
		],
	];
}

/**
 * Get hashtag from caption
 *
 * @param string $caption
 *
 * @return array
 * @since 11.0.0
 */
function enjoyinstagram_get_hashtag_from_caption( $caption ) {
	$hashtags = [];
	preg_match_all( '/#[^#\s]+/', $caption, $hashtags );

	if ( empty( $hashtags ) ) {
		return [];
	}

	$hashtags = array_shift( $hashtags );
	$hashtags = array_map( 'enjoyinstagram_remove_sharp', $hashtags );

	return $hashtags;
}

/**
 * Remove sharp from an hashtag
 *
 * @param string $hashtag
 *
 * @return string
 */
function enjoyinstagram_remove_sharp( $hashtag ) {
	return str_replace( '#', '', $hashtag );
}

/**
 * Add a flash message. It will be displayed in the admin_notice section
 *
 * @param string $message
 * @param string $type
 *
 * @return void
 */
function enjoyinstagram_add_notice( $message, $type ) {
	$notices   = enjoyinstagram_get_notices();
	$notices[] = [
		'message' => $message,
		'type'    => $type,
	];
	set_transient( 'enjoyinstagram_notices', $notices );
}

/**
 * Return the flash messages that will be displayed in admin_notice section
 *
 * @param bool $delete
 *
 * @return array
 */
function enjoyinstagram_get_notices( $delete = true ) {
	$notices = get_transient( 'enjoyinstagram_notices' );
	if ( ! $notices ) {
		return [];
	}

	if ( $delete ) {
		delete_transient( 'enjoyinstagram_notices' );
	}

	return $notices;
}

/**
 * Return the hashtags from a given text
 *
 * @param string $string
 *
 * @return array
 */
function enjoyinstagram_extract_hashtags( $string ) {
	$regex = '/(^|[^0-9A-Z&\/\?]+)([#＃]+)([0-9A-Z_]*[A-Z_]+[a-z0-9_üÀ-ÖØ-öø-ÿ]*)/iu';
	preg_match_all( $regex, $string, $matches );

	return $matches[3];
}

/**
 * Queries the IG local dataset.
 *
 * @param string $type type of query: user, hashtag or public_hashtag .
 * @param array $args query parameters.
 * @param string $moderate moderate value from shortcode.
 *
 * @return array images
 */
function ei_get_images( $type, $args, $moderate = '' ) {
	$data            = [];
	$images_captured = get_option( 'enjoyinstagram_images_captured', 20 );
	// set user and hashtag to read write actions.
	$user          = isset( $args['user'] ) ? trim( $args['user'] ) : '';
	$hashtag       = isset( $args['hashtag'] ) ? array_filter( $args['hashtag'] ) : [];
	$medias        = [];
	$users         = enjoyinstagram()->get_users();
	$user_business = enjoyinstagram()->has_business_user();

	if ( 'user' === $type ) {
		$hashtag = enjoyinstagram()->is_premium() ? $hashtag : [];
		// get media.
		$medias = ei_db()->get_shortcode_media_user(
			$user,
			'false' === $moderate ? $hashtag : [],
			'true' === $moderate ? 'approved' : false,
			$images_captured
		);
	} elseif ( 'hashtag' === $type ) {
		$medias = ei_db()->get_shortcode_media_user(
			$user,
			$hashtag,
			'true' === $moderate ? 'approved' : false,
			$images_captured
		);
	} elseif ( 'public_hashtag' === $type && ! empty( $hashtag ) && $user_business ) {
		if ( 'true' === $moderate ) {
			$medias = ei_db()->get_shortcode_media_hashtag( $hashtag, 'approved', $images_captured );
		} else {
			foreach ( $hashtag as $single_hashtag ) {
				$tr_key      = 'ei-hashtag-' . $single_hashtag;
				$temp_medias = get_transient( $tr_key );

				if ( empty( $temp_medias ) ) {
					$temp_medias = ei_api()->search_business_hashtag(
						$users[ $user_business ],
						$single_hashtag
					);

					if ( ! empty( $temp_medias ) ) {
						set_transient( $tr_key, $temp_medias, MINUTE_IN_SECONDS * 3 );
					}
				}

				$medias = array_merge( $medias, $temp_medias );
			}
		}
	}

	$count = 0;

	foreach ( $medias as $media ) {

		if ( $count <= 10 ) {
			if ( ei_maybe_cache_image( $media['image_id'], $media['image_url'] ) ) {
				$count ++;
			}
		}

		$data[] = enjoyinstagram_format_media_for_shortcode(
			$media,
			isset( $users[ $user ] ) ? $users[ $user ] : []
		);
	}

	return $data;
}

/**
 * Returns an image from the enjoy-instagram database
 *
 * @param string $field Either 'id' or 'image_id'
 * @param string|int $value Search for this value
 *
 * @return array|null
 *
 * @since 6.2.0
 */
function ei_get_image_by( $field, $value ) {
	$field = in_array( $field, [ 'id', 'image_id' ], true ) ? $field : 'id';

	$results = ei_db()->get_media(
		[
			[
				'key'   => $field,
				'value' => $value,
			],
		]
	);

	if ( ! empty( $results ) ) {
		return enjoyinstagram_format_media_for_shortcode( $results[0], [] );
	}

	return null;
}

/**
 * Returns the public cache base url
 *
 * @return string
 *
 * @since 5.2.0
 */
function ei_get_cache_url() {
	$upload   = wp_upload_dir();
	$base_url = $upload['baseurl'];

	if ( is_ssl() ) {
		str_replace( 'http:', 'https:', $base_url );
	}

	return apply_filters( 'ei_cache_url', trailingslashit( $base_url ) . ENJOYINSTAGRAM_CACHE_DIR_NAME );
}

/**
 * Returns the chache base path directory
 *
 * @return string
 *
 * @since 5.2.0
 */
function ei_get_cache_path() {
	$upload = wp_upload_dir();

	return apply_filters( 'ei_cache_path', trailingslashit( $upload['basedir'] ) . ENJOYINSTAGRAM_CACHE_DIR_NAME );
}

/**
 * Returns the path to a cached image
 *
 * @param string $media_id
 * @param int|string $size
 *
 * @return string
 *
 * @since 5.2.0
 */
function ei_get_cached_image( $media_id, $size ) {
	return trailingslashit( ei_get_cache_path() ) . $media_id . '_' . $size . '.jpg';
}

/**
 * Returns the public url to a cached image
 *
 * @param string $media_id
 * @param int $size
 *
 * @return string
 *
 * @since 5.2.0
 */
function ei_get_cached_image_url( $media_id, $size ) {
	return trailingslashit( ei_get_cache_url() ) . basename( ei_get_cached_image( $media_id, $size ) );
}

/**
 * Purge the cache directory
 *
 * @return void
 *
 * @since 5.2.0
 */
function ei_delete_cached_images() {
	$image_files = glob( trailingslashit( ei_get_cache_path() ) . '*' );

	if ( $image_files ) {
		foreach ( $image_files as $file ) { // iterate files.
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
	}
}

/**
 * Removes a single image from the cache
 *
 * @param string $id
 *
 * @return void
 *
 * @since 6.0.0
 */
function ei_delete_cached_image( $id ) {
	$resize_sizes   = apply_filters( 'ei_resize_sizes', [ 320, 640 ] );
	$resize_sizes[] = 'full';

	foreach ( $resize_sizes as $width ) {
		$cached_file = ei_get_cached_image( $id, $width );

		if ( file_exists( $cached_file ) && is_file( $cached_file ) ) {
			unlink( $cached_file );
		}
	}
}

/**
 * @param string $id
 * @param string $url
 * @param bool $original
 *
 * @return bool
 *
 * @since 5.2.0
 */
function ei_maybe_cache_image( $id, $url, $original = false ) {

	$resize_sizes = apply_filters( 'ei_resize_sizes', [ 320, 640 ] );

	if ( $original ) {
		$resize_sizes[] = 'full';
	}

	foreach ( $resize_sizes as $width ) {

		if ( ! is_int( $width ) && 'full' !== $width ) {
			continue;
		}

		$cached_file = ei_get_cached_image( $id, $width );

		if ( ! file_exists( $cached_file ) ) {
			$editor = wp_get_image_editor( $url );

			if ( is_wp_error( $editor ) ) {
				return false;
			}

			if ( 'full' !== $width ) {
				$editor->resize( $width, null );
			}

			if ( ! $editor->save( $cached_file ) ) {
				return false;
			}
		}
	}

	return true;
}
