<?php
/**
 * init global var for our theme.
 * Defines constants for our theme.
 * @since Cenos 1.0.0
 */

$current_theme = wp_get_theme('cenos');
if ( ! empty( $current_theme['Template'] ) ) {
    $current_theme = wp_get_theme( $current_theme['Template'] );
    $GLOBALS['current_theme'] = $current_theme;
}

if (!defined('CENOS_API_URL')) {
    define('CENOS_API_URL', 'https://api.familab.net');
}
if ( ! defined( 'CENOS_TEMPLATE_DIRECTORY' ) ) {
    define( 'CENOS_TEMPLATE_DIRECTORY', get_template_directory() );
}
if ( ! defined( 'CENOS_TEMPLATE_DIRECTORY_URI' ) ) {
    define( 'CENOS_TEMPLATE_DIRECTORY_URI', trailingslashit ( get_template_directory_uri() ));
}
if ( ! defined( 'CENOS_STYLESHEET_DIRECTORY' ) ) {
    define( 'CENOS_STYLESHEET_DIRECTORY', trailingslashit ( get_stylesheet_directory() ));
}
if ( ! defined( 'CENOS_STYLESHEET_DIRECTORY_URI' ) ) {
    define( 'CENOS_STYLESHEET_DIRECTORY_URI', trailingslashit ( get_stylesheet_directory_uri() ));
}

$cenos_mods = get_theme_mods();

include_once CENOS_TEMPLATE_DIRECTORY.'/inc/classes/walker_mega_menu.php';
include_once CENOS_TEMPLATE_DIRECTORY.'/inc/classes/walker_default_menu.php';
include_once CENOS_TEMPLATE_DIRECTORY.'/inc/classes/walker_page_menu.php';
include_once CENOS_TEMPLATE_DIRECTORY.'/inc/lib/breadcrumbs.php';
if (function_exists('fmfw_get_mobile_detect')){
    $detect = fmfw_get_mobile_detect();
} else {
    $detect = false;
}

global $elementor_instance;
if (empty($elementor_instance) && defined( 'ELEMENTOR_VERSION' )) {
    $elementor_instance = \Elementor\Plugin::instance();
    require_once CENOS_TEMPLATE_DIRECTORY.'/inc/elementor/init.php';
}
