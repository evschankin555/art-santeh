<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
    $_SERVER['HTTPS']='on';
}
$_SERVER['SERVER_PORT'] = 443;

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'slava1983_2023' );

/** Database username */
define( 'DB_USER', 'slava1983_2023' );

/** Database password */
define( 'DB_PASSWORD', 'uIqFV7R&' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'GQb^rT<NfPBb~< _ZA8u^8-0Ln,:K2BEw)`^0E5k+#p]DET{S:J<B;/gv*_+2Ca%' );
define( 'SECURE_AUTH_KEY',  'jufBBCB2@HmK#7Xi-DVkWzx97&:Gu1kDO$HcF>MLRR,`3x=V@]U6)8=Yml[y#~&-' );
define( 'LOGGED_IN_KEY',    'TH#..Rtw0r#,=IuS/|yf+b`)dAS!)p-#IQC:74doxbKR)%TDUez>2tIv0?s!ytk^' );
define( 'NONCE_KEY',        'qH+u;CLyTH,C).t5bm>aY68y;_`2l79x@8IZUC|MZf]bC,rnj&>9_v}+)jWvd_.?' );
define( 'AUTH_SALT',        'bd2F~BKeW[p1sS0$.GbvPJUf,`iAT/u82ZS4S`6cU+=0O407{:J,Dmfz7I8fR6_F' );
define( 'SECURE_AUTH_SALT', 'w3#+;kCml@[[2jWQt&Nf0Q^6Bane]uIkCd#9_hx/l/@yBdlj@q4CH+}Afs+!B}zs' );
define( 'LOGGED_IN_SALT',   '|%M8Bi8|7CY(#{Y{B}Q7U>$bSmA}[W2o<.OMrsWuLn]OW4X65Sj0)XmlS<# 1$qA' );
define( 'NONCE_SALT',       'qod0b ,Lz+o2C7;H_z5N4`w%`;Wbu4=iy[*D_(o^[hV_EpHc(g$Th7hSX@)|SjKk' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/*define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );*/

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
