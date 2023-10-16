<?php
/**
 * 1.0.0
 * @package    Cenos
 * @author     Familab <contact@familab.net>
 * @copyright  Copyright (C) 2018 familab.net. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://familab.net
 */

// Prevent direct access to this file
defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );



if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) || version_compare( PHP_VERSION, '5.6', '<' ) ) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

include_once 'inc/init.php';
include_once 'inc/theme-functions.php';

if (current_user_can('edit_theme_options')) {
    include_once 'inc/admin/admin_init.php';
}
// Include customize settings
include_once 'inc/settings/setting_init.php';
include_once 'inc/classes/svgsupport.php';
include_once 'inc/classes/theme.php';
include_once 'inc/svg_icons.php';
include_once 'inc/theme-template-functions.php';
include_once 'inc/theme-template-hooks.php';
if (cenos_is_enabled_maintenance()){
    return;
}

include_once 'inc/widgets/init.php';

if (cenos_is_woocommerce_activated()) {
    include_once 'inc/woocommerce/init.php';
}
//remove slashes before save import from aliexpress
