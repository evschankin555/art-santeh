<?php
/**
 * Prevent switching to Cenos on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Cenos 1.0.0
 */

function cenos_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'cenos_upgrade_notice' );
}
add_action( 'after_switch_theme', 'cenos_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Cenos on WordPress versions prior to 5.0.
 *
 * @since Cenos 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function cenos_upgrade_notice() {
	printf( '<div class="error"><p>%s</p></div>', esc_html( cenos_compat_message() ) );
}

function cenos_compat_message() {
    if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
        return sprintf(
        /* Translators: 1 is the required WordPress version and 2 is the user's current version. */
            esc_html__( 'Cenos requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.', 'cenos' ),
            '5.0',
            $GLOBALS['wp_version']
        );
    }

    if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
        return sprintf(
        /* Translators: 1 is the required PHP version and 2 is the user's current version. */
            esc_html__( 'Cenos requires at least PHP version %1$s. You are running version %2$s. Please upgrade and try again.', 'cenos' ),
            '5.6',
            PHP_VERSION
        );
    }

    return '';
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 5.0.
 *
 * @since Cenos 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function cenos_customize() {
	wp_die(
        esc_html( cenos_compat_message() ),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'cenos_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 5.0.
 *
 * @since Cenos 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function cenos_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( esc_html( cenos_compat_message() ) );
	}
}
add_action( 'template_redirect', 'cenos_preview' );
