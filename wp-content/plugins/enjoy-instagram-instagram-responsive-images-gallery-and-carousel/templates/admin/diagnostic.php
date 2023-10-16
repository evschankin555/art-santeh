<?php

$users         = enjoyinstagram()->get_all_users();
$cached_images = glob( trailingslashit( ei_get_cache_path() ) . '*' );

?>

<h3><?php _e( 'System info', 'enjoy-instagram' ); ?></h3>

<textarea readonly="readonly" onclick="this.focus();this.select()"
	title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."
	style="width: 100%; max-width: 960px; height: 500px; white-space: pre; font-family: Menlo,Monaco,monospace;">
## ENV INFO ##
Site URL:           <?php echo site_url() . "\n"; ?>
Home URL:           <?php echo home_url() . "\n"; ?>
WordPress Version:  <?php echo get_bloginfo( 'version' ) . "\n"; ?>
PHP Version:        <?php echo phpversion() . "\n"; ?>
SAPI:               <?php echo php_sapi_name() . "\n"; ?>
WEB Server:         <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>
Premium:            <?php echo ( enjoyinstagram()->is_premium() && defined( 'EIP_VERSION' ) ? EIP_VERSION : __( 'No', 'enjoy-instagram' ) ) . "\n"; ?>

## Cache ##
Path:               <?php echo ( file_exists( ei_get_cache_path() ) ? ei_get_cache_path() : 'Unable to create ' . ei_get_cache_path() ) . "\n"; ?>
Images in cache:    <?php echo $cached_images ? count( $cached_images ) : 0 . "\n"; ?>

## USERS: ##
<?php

foreach ( $users as $user ) {
	$token_expire_date       = gmdate( 'Y-m-d H:i:s', $user['token_expires_timestamp'] );
	$token_expires_timestamp = isset( $user['token_expires_timestamp'] ) ? $user['token_expires_timestamp'] . ' (' . $token_expire_date . ')' : null;
	// phpcs:ignore
	var_export(
		[
			'id'                           => $user['id'],
			'token'                        => $user['access_token'],
			'token_expires_timestamp'      => $token_expires_timestamp,
			'last_token_refresh_timestamp' => $user['last_token_refresh_timestamp'],
			'username'                     => $user['username'],
			'business'                     => $user['business'],
			'active'                       => $user['active'],
		]
	);
}
?>

## API RESPONSE ##
<?php
foreach ( $users as $user ) {
	$data  = ei_api()->get_user_profile( $user['id'], $user['access_token'], $user['business'] );
	$error = ei_api()->last_error;

	echo $user['username'] . ': ';
	if ( false === $data && ! empty( $error ) ) {
		echo $error . "\n";
	} else {
		echo "Success!\n";
	}
}

?>

## DATABASE ##
<?php $stats = ei_db()->stats(); ?>
Version:        <?php echo get_option( 'enjoy_instagram_installed_db_version' ) . "\n"; ?>

*Media*
Count:          <?php echo $stats['media']['count'] . "\n"; ?>
	<?php echo $stats['media']['structure'] . "\n"; ?>

*Hashtag*
Count:     	    <?php echo $stats['hashtag']['count'] . "\n"; ?>
	<?php echo $stats['hashtag']['structure'] . "\n"; ?>


## ACTIVE PLUGINS: ##
<?php
$plugins        = get_plugins();
$active_plugins = get_option( 'active_plugins', [] );

foreach ( $plugins as $plugin_path => $plugin ) {
	if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
		continue;
	}

	echo $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
}
?>
</textarea>
<p>
	<a id="ei_reset_db" class="button-secondary"
		href="
		<?php
		echo wp_nonce_url(
			ei_admin()->build_admin_url(
				'diagnostic',
				[ 'action' => 'reset-db' ]
			),
			'reset-db'
		)
		?>
			"><?php _e( 'Re-install Database', 'enjoy-instagram' ); ?></a>
</p>
