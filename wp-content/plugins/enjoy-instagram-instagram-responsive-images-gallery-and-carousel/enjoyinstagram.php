<?php
/**
 * Enjoy instagram
 *
 * @package           EnjoyInstagram
 * @author            Mediabeta Srl
 * @copyright         Copyright (C) 2020, Mediabeta Srl
 * @license           http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name:       Enjoy Instagram
 * Plugin URI:        https://www.mediabetaprojects.com/enjoy-instagram-premium/
 * Description:       Instagram Responsive Images Gallery and Carousel, works with Shortcodes and Widgets.
 * Version:           6.2.2
 * Requires at least: 4.0
 * Requires PHP:      7.2
 * Author:            Mediabeta Srl
 * Author URI:        http://www.mediabeta.com/chi-siamo/
 * Text Domain:       enjoy-instagram
 * Domain Path:       /languages/
 * License:           GPL v3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

! defined( 'ENJOYINSTAGRAM_VERSION' ) && define( 'ENJOYINSTAGRAM_VERSION', '6.2.2' );
! defined( 'ENJOYINSTAGRAM_FILE' ) && define( 'ENJOYINSTAGRAM_FILE', __FILE__ );
! defined( 'ENJOYINSTAGRAM_URL' ) && define( 'ENJOYINSTAGRAM_URL', plugin_dir_url( __FILE__ ) );
! defined( 'ENJOYINSTAGRAM_DIR' ) && define( 'ENJOYINSTAGRAM_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'ENJOYINSTAGRAM_ASSETS_URL' ) && define( 'ENJOYINSTAGRAM_ASSETS_URL', ENJOYINSTAGRAM_URL . 'assets' );
! defined( 'ENJOYINSTAGRAM_TEMPLATE_PATH' ) && define( 'ENJOYINSTAGRAM_TEMPLATE_PATH', ENJOYINSTAGRAM_DIR . 'templates' );
! defined( 'ENJOYINSTAGRAM_FB_APP_ID' ) && define( 'ENJOYINSTAGRAM_FB_APP_ID', '773612959700549' );
! defined( 'ENJOYINSTAGRAM_APP_ID' ) && define( 'ENJOYINSTAGRAM_APP_ID', '1367115243477960' );
! defined( 'ENJOYINSTAGRAM_GRAPH_API_REDIRECT' ) && define( 'ENJOYINSTAGRAM_GRAPH_API_REDIRECT', 'https://www.mediabetaprojects.com/enjoy-instagram-api/graph-api-redirect.php' );
! defined( 'ENJOYINSTAGRAM_BASIC_DISPLAY_API_REDIRECT' ) && define( 'ENJOYINSTAGRAM_BASIC_DISPLAY_API_REDIRECT', 'https://www.mediabetaprojects.com/enjoy-instagram-api/basic-display-redirect.php' );
! defined( 'ENJOYINSTAGRAM_CACHE_DIR_NAME' ) && define( 'ENJOYINSTAGRAM_CACHE_DIR_NAME', 'ei-cache' );
! defined( 'ENJOYINSTAGRAM_REFRESH_THRESHOLD_OFFSET' ) && define( 'ENJOYINSTAGRAM_REFRESH_THRESHOLD_OFFSET', 40 * DAY_IN_SECONDS );

include_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! is_plugin_active( 'enjoy-instagram-premium/enjoyinstagram.php' ) ) {
	/**
	 * Register plugin base settings
	 *
	 * @return void
	 */
	function ei_require_activation_class() {
		require_once 'includes/class.enjoyinstagram-activation.php';
	}

	register_activation_hook( __FILE__, 'ei_require_activation_class' );

	function ei_deactivation() {
		require_once 'includes/class.enjoyinstagram-scheduler.php';
		EI_Scheduler::get_instance()->unregister();
	}

	register_deactivation_hook( __FILE__, 'ei_deactivation' );

	/**
	 * Bootstraps enjoy instagram plugin
	 *
	 * @return void
	 */
	function ei_init() {
		load_plugin_textdomain( 'enjoyinstagram', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once 'includes/functions.enjoyinstagram.php';
		require_once 'includes/class.enjoyinstagram.php';
		enjoyinstagram();
	}

	add_action( 'plugins_loaded', 'ei_init' );
} else {
	/**
	 * Shows admin errors related to an old versione of the premium plugin
	 *
	 * @return void
	 */
	function ei_disable_premium_notice() {
		?>
		<div class="error notice">
			<p>
				<?php _e( 'Please deactivate Enjoy Instagram Premium plugin', 'enjoy-instagram' ); ?>
			</p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'ei_disable_premium_notice' );
}
