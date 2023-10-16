<?php
/**
 * This class handles all admin actions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

class EnjoyInstagram_Admin {

	/**
	 * Single plugin instance
	 *
	 * @since 9.0.0
	 * @var EnjoyInstagram_Admin
	 */
	protected static $instance;

	/**
	 * Plugin options page name
	 *
	 * @var string
	 */
	protected $_options_page = 'enjoyinstagram_plugin_options';

	/**
	 * Plugin options page name
	 *
	 * @var array
	 */
	protected $_tabs = [];

	/**
	 * Plugin options page name
	 *
	 * @var array
	 */
	protected $_plugin_options = [];

	/**
	 * Returns single instance of the class
	 *
	 * @return EnjoyInstagram_Admin
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

		$this->init();
		add_action( 'admin_menu', [ $this, 'add_admin_menus' ], 0 );
		add_action( 'admin_notices', [ $this, 'print_notices' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles_scripts' ] );
		add_filter(
			'plugin_action_links_' . plugin_basename( ENJOYINSTAGRAM_DIR . '/' . basename( ENJOYINSTAGRAM_FILE ) ),
			[ $this, 'settings_link' ]
		);

		if ( ! enjoyinstagram()->is_premium() ) {
			add_filter( 'plugin_row_meta', [ $this, 'premium_link' ], 10, 2 );
		}

		// add/remove user.
		add_action( 'admin_init', [ $this, 'handle_api_login' ], 1 );
		add_action( 'admin_init', [ $this, 'remove_user' ], 1 );
		add_action( 'admin_init', [ $this, 'clear_user_data' ], 1 );
		add_action( 'admin_init', [ $this, 'activate_user' ], 1 );
		add_action( 'admin_init', [ $this, 'reset_database' ], - 1 );
		add_action( 'admin_init', [ $this, 'sync_media_action' ], 1 );
	}

	/**
	 * Check if current page is a plugin admin page
	 *
	 * @return boolean
	 */
	public function is_admin_page() {
		return isset( $_GET['page'] ) && $_GET['page'] === $this->_options_page;
	}

	/**
	 * Print notices in plugin admin pages
	 *
	 * @return void
	 */
	public function print_notices() {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		$notices = enjoyinstagram_get_notices();

		if ( ! $notices ) {
			return;
		}

		foreach ( $notices as $notice ) {
			?>
			<div class="updated settings-error <?php echo $notice['type']; ?> is-dismissible">
				<p><?php echo $notice['message']; ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Init admin class variables
	 *
	 * @return void
	 */
	protected function init() {
		// init tabs
		$this->_tabs = apply_filters(
			'enjoyinstagram_plugin_admin_tabs',
			[
				'users-settings'      => __( 'Users', 'enjoy-instagram' ),
				'appearance-settings' => __( 'Appearance Settings', 'enjoy-instagram' ),
				'moderation-settings' => __( 'Moderation Panel', 'enjoy-instagram' ),
				'shortcode-settings'  => __( 'Create your shortcode', 'enjoy-instagram' ),
				'diagnostic'          => __( 'Diagnostic', 'enjoy-instagram' ),
			]
		);
	}

	/**
	 * Build an admin url
	 *
	 * @param string $tab
	 * @param array $params
	 *
	 * @return string
	 */
	public function build_admin_url( $tab, $params = [] ) {
		$params_string = '';
		foreach ( $params as $key => $value ) {
			$params_string .= '&' . $key . '=' . $value;
		}

		return admin_url( "admin.php?page={$this->_options_page}&tab={$tab}{$params_string}" );
	}

	public function get_menu_slug() {
		return $this->_options_page;
	}

	/**
	 * Enqueue plugin styles and scripts
	 *
	 * @return void
	 */
	public function admin_styles_scripts() {
		global $wp_scripts;

		wp_register_style(
			'enjoyinstagram-admin-style',
			ENJOYINSTAGRAM_ASSETS_URL . '/css/admin.css',
			[],
			ENJOYINSTAGRAM_VERSION
		);
		wp_register_style(
			'accordion',
			ENJOYINSTAGRAM_ASSETS_URL . '/css/accordion.min.css',
			[],
			ENJOYINSTAGRAM_VERSION
		);

		wp_register_script(
			'enjoyinstagram-admin-script',
			ENJOYINSTAGRAM_ASSETS_URL . '/js/admin.js',
			[ 'jquery', 'jquery-ui-tooltip' ],
			ENJOYINSTAGRAM_VERSION,
			true
		);

		wp_localize_script(
			'enjoyinstagram-admin-script',
			'ei_admin',
			[
				'premium_url'            => $this->get_premium_url(
					[
						'utm_source'   => 'plugin-free',
						'utm_campaign' => 'enjoy-instagram',
					]
				),
				'premium_button_title'   => __( 'Go Premium', 'enjoy-instagram' ),
				'premium_button_tooltip' => __( 'Upgrade to the premium version to unlock these features. A coupon is waiting for you!', 'enjoy-instagram' ),
			]
		);

		wp_register_script(
			'enjoy_instagram',
			ENJOYINSTAGRAM_ASSETS_URL . '/js/enjoy_instagram.min.js',
			[ 'jquery' ],
			ENJOYINSTAGRAM_VERSION,
			true
		);

		if ( $this->is_admin_page() ) {
			wp_enqueue_style( 'enjoyinstagram-admin-style' );
			wp_enqueue_style( 'accordion' );
			wp_enqueue_script( 'enjoyinstagram-admin-script' );
			wp_enqueue_script( 'enjoy_instagram' );
			wp_enqueue_script(
				'fb_graph_api',
				'https://connect.facebook.net/it_IT/sdk.js#xfbml=1&autoLogAppEvents=1&version=v3.3&appId=' . ENJOYINSTAGRAM_FB_APP_ID
			);

			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_style(
				'plugin_name-admin-ui-css',
				'//ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css',
				[],
				ENJOYINSTAGRAM_VERSION
			);

		}
	}

	/**
	 * Add admin menu under Settings
	 *
	 * @return void
	 */
	public function add_admin_menus() {

		$icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA0OCA0OCIgdmlld0JveD0iMCAwIDQ4IDQ4IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im00MSAxNGgtMnYtMmMwLTIuMi0xLjgtNC00LTRoLTI4Yy0yLjIgMC00IDEuOC00IDR2MjBjMCAyLjIgMS44IDQgNCA0aDJ2MmMwIDIuMiAxLjggNCA0IDRoMjhjMi4yIDAgNC0xLjggNC00di0yMGMwLTIuMi0xLjgtNC00LTR6bS0xNy4xIDE5LjdjMCAuNC0uMS41LS41LjUtMy41IDAtNi45IDAtMTAuNCAwLS41IDAtLjUtLjItLjUtLjYgMC0zLjIgMC02LjQgMC05LjVzMC02LjMgMC05LjRjMC0uNyAwLS43LjctLjdoMTAuMWMuNyAwIC43IDAgLjcuN3YyLjNjMCAuNS0uMS42LS42LjYtMiAwLTQgMC01LjkgMC0uNSAwLS42LjItLjYuNnYzYzAgLjcgMCAuNy43LjdoNS4zYy41IDAgLjcuMi42Ljd2Mi40YzAgLjYgMCAuNi0uNy43LTEuOCAwLTMuNiAwLTUuNCAwLS41IDAtLjYuMi0uNi42djMuOWMwIC41LjIuNi42LjZoNS45Yy41IDAgLjYuMS42LjYtLjEuNi0uMSAxLjUgMCAyLjN6bTkuNy0xOS45Yy0uNCAwLS43IDAtMS4xIDBzLS41LjEtLjUuNXYuNCAxOC4zYzAgMS40LS4yIDIuNy0uOSA0LS44IDEuNS0yIDIuMi0zLjYgMi40LTEgLjEtMiAwLTMtLjItLjQtLjEtLjUtLjMtLjUtLjcgMC0xLjEgMC0yLjIgMC0zLjMgMC0uNC4xLS41LjUtLjQuNS4yIDEuMS4yIDEuNi4yLjcgMCAxLjItLjQgMS41LTFzLjMtMS4yLjMtMS44YzAtMi45IDAtNS43IDAtOC42IDAtMyAwLTYuMSAwLTkuMSAwLS43IDAtLjctLjYtLjctLjQgMC0uOCAwLTEuMiAwLS41IDAtLjYtLjItLjYtLjYgMC0uOSAwLTEuNyAwLTIuNiAwLS42IDAtLjYuNi0uNmg3LjUuMWMuNCAwIC41LjIuNS41djIuOGMtLjIuMy0uMy41LS42LjV6IiBmaWxsPSIjZjU3YzAwIi8+PC9zdmc+';

		add_menu_page(
			__( 'Enjoy Instagram', 'enjoy-instagram' ),
			__( 'Enjoy Instagram', 'enjoy-instagram' ),
			'read',
			$this->get_menu_slug(),
			[ $this, 'output_options_page' ],
			$icon
		);

		add_submenu_page(
			$this->get_menu_slug(),
			__( 'Settings', 'enjoy-instagram' ),
			__( 'Settings', 'enjoy-instagram' ),
			'read',
			$this->get_menu_slug(),
			[ $this, 'output_options_page' ],
			- 1
		);
	}

	/**
	 * Add plugin settings link
	 *
	 * @param string[] $links list of html links.
	 *
	 * @return string[]
	 */
	public function settings_link( $links ) {
		$links[] = '<a href="admin.php?page=' . $this->_options_page . '">' . __( 'Settings', 'enjoy-instagram' ) . '</a>';
		$links[] = '<a href="widgets.php">' . __( 'Widgets', 'enjoy-instagram' ) . '</a>';

		return $links;
	}

	/**
	 * Adds the premium link in the plugin list row
	 *
	 * @param string[] $links list of html links.
	 * @param string $file curent plugin file
	 *
	 * @return string[]
	 */
	public function premium_link( $links, $file ) {
		if ( plugin_basename( ENJOYINSTAGRAM_DIR . '/' . basename( ENJOYINSTAGRAM_FILE ) ) !== $file ) {
			return $links;
		}

		$premium_url = $this->get_premium_url(
			[
				'utm_source'   => 'plugins-page',
				'utm_medium'   => 'plugin-row',
				'utm_campaign' => 'admin',
			]
		);

		$premium_link = sprintf( '<a href="%s" style="color: red;">%s</a>', $premium_url, __( 'Try Enjoy Instagram Premium', 'enjoy-instagram' ) );
		array_push( $links, $premium_link );

		return $links;
	}

	/**
	 * Creates the url for purchasing the premium version
	 *
	 * @param array $query_params an associative array of query variables.
	 *
	 * @return string
	 */
	public function get_premium_url( $query_params ) {
		return esc_url(
			add_query_arg(
				$query_params,
				'https://www.mediabetaprojects.com/enjoy-instagram-premium/coupon-code-enjoy-instagram-premium/'
			)
		);
	}

	/**
	 * Get plugin admin tabs
	 *
	 * @sine 9.0.0
	 * @return array
	 */
	public function get_tabs() {
		return $this->_tabs;
	}

	/**
	 * Get current active tab or return the first one
	 *
	 * @return string
	 * @since 9.0.0
	 */
	public function get_active_tab() {
		if ( ! is_array( $this->_tabs ) ) {
			return '';
		}

		$c = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
		reset( $this->_tabs );

		return isset( $this->_tabs[ $c ] ) ? $c : key( $this->_tabs );
	}

	/**
	 * Get admin tab url
	 *
	 * @param string $tab
	 *
	 * @return string
	 */
	public function get_tab_url( $tab ) {
		return add_query_arg(
			[
				'page' => $this->_options_page,
				'tab'  => $tab,
			],
			admin_url( 'admin.php' )
		);
	}

	/**
	 * Output plugin options page
	 *
	 * @return void
	 */
	public function output_options_page() {
		$tabs       = $this->get_tabs();
		$active_tab = $this->get_active_tab();

		include ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/plugin-settings.php';
	}

	/**
	 * Invoked after the user has logged in with his instagram account
	 *
	 * @return void
	 */
	public function handle_api_login() {

		if ( ! $this->is_admin_page() || ! isset( $_GET['access_token'] ) || ! isset( $_GET['api'] ) ) {
			return;
		}

		$is_business  = 'graph' === $_GET['api'];
		$access_token = sanitize_text_field( $_GET['access_token'] );
		$expires_in   = sanitize_text_field( $_GET['expires_in'] );
		$redirect_uri = $this->build_admin_url( 'users-settings' );
		$data         = ei_api()->get_user_accounts( $access_token, $is_business );

		if ( false === $data ) {
			$message = ei_api()->last_error;
			enjoyinstagram_add_notice( $message, 'error' );
		} elseif ( empty( $data ) ) {
			$message = __( 'No account found for this user', 'enjoy-instagram' );
			enjoyinstagram_add_notice( $message, 'error' );
		} else {
			foreach ( $data as $profile ) {
				$profile['last_token_refresh_timestamp'] = null;
				$profile['access_token']                 = $access_token;
				$profile['token_expires_timestamp']      = time() + (int) $expires_in;
				enjoyinstagram()->add_user( $profile['username'], $profile );
				enjoyinstagram_add_notice(
				// translators: Notice message.
					sprintf( __( 'User %s successfully added', 'enjoy-instagram' ), $profile['username'] ),
					'notice'
				);
			}
		}

		wp_redirect( $redirect_uri );
		exit;

	}

	/**
	 * Remove and user from plugin
	 *
	 * @return void
	 */
	public function remove_user() {

		if (
			! isset( $_GET['user_id'] )
			|| ! isset( $_GET['action'] )
			|| 'enjoyinstagram-remove-user' !== $_GET['action']
			|| ! isset( $_GET['tab'] ) || 'users-settings' !== $_GET['tab'] ) {
			return;
		}

		$id = trim( $_GET['user_id'] );
		enjoyinstagram()->remove_user( $id );

		// redirect to main settings page
		wp_redirect( $this->build_admin_url( 'users-settings' ) );
		exit;
	}

	/**
	 * Clear a user data from tables
	 *
	 * @return void
	 */
	public function clear_user_data() {

		if (
			! isset( $_GET['user_id'] )
			|| ! isset( $_GET['action'] )
			|| 'enjoyinstagram-clear-user-data' !== $_GET['action']
			|| ! isset( $_GET['tab'] ) || 'users-settings' !== $_GET['tab'] ) {
			return;
		}

		$id = trim( $_GET['user_id'] );
		ei_db()->delete_media_by_user( $id );
		ei_delete_cached_images();

		// redirect to main settings page
		wp_redirect( $this->build_admin_url( 'users-settings' ) );
		exit;
	}

	/**
	 * Clear a user data from tables
	 *
	 * @return void
	 */
	public function activate_user() {
		if (
			! isset( $_GET['user_id'] )
			|| ! isset( $_GET['action'] )
			|| 'enjoyinstagram-activate-user' !== $_GET['action']
			|| ! isset( $_GET['tab'] ) || 'users-settings' !== $_GET['tab'] ) {
			return;
		}

		$id = trim( $_GET['user_id'] );
		if ( ! enjoyinstagram()->is_premium() ) {
			$users = enjoyinstagram()->get_all_users();

			foreach ( $users as &$user ) {
				$user['active'] = $user['username'] === $id;
			}

			update_option( 'enjoy_instagram_options', $users );
			update_option( 'enjoyinstagram_sync_times', [] );
			enjoyinstagram_add_notice( __( 'User activated', 'enjoy-instagram' ), 'success' );
		}

		// redirect to main settings page
		wp_redirect( $this->build_admin_url( 'users-settings' ) );
		exit;
	}

	/**
	 * Re-install plugin database
	 *
	 * @return void
	 */
	public function reset_database() {

		$action = 'reset-db';

		if (
			! $this->is_admin_page()
			|| ! isset( $_GET['action'] )
			|| $_GET['action'] !== $action
			|| ! isset( $_GET['_wpnonce'] )
			|| ! wp_verify_nonce( $_GET['_wpnonce'], $action ) ) {
			return;
		}

		try {
			ei_db()->reset();
			enjoyinstagram_add_notice( __( 'Database initialized', 'enjoy-instagram' ), 'success' );
		} catch ( Exception $e ) {
			enjoyinstagram_add_notice(
			// translators: notice
				sprintf( __( 'Database inizialization error: %s', 'enjoy-instagram' ), $e->getMessage() ),
				'error'
			);
		}

		wp_redirect( $this->build_admin_url( 'diagnostic' ) );
		exit;
	}

	/**
	 * Get Instagram login url
	 *
	 * @return string
	 */
	public function get_instagram_login_url() {
		$return_url = admin_url( "admin.php?page={$this->_options_page}" );

		return add_query_arg(
			[
				'app_id'        => ENJOYINSTAGRAM_APP_ID,
				'redirect_uri'  => ENJOYINSTAGRAM_BASIC_DISPLAY_API_REDIRECT,
				'response_type' => 'code',
				'scope'         => 'user_profile,user_media',
				'state'         => base64_encode( $return_url ),
			],
			'https://api.instagram.com/oauth/authorize'
		);
	}

	/**
	 * Create url to connect with FB
	 *
	 * @return string Connection url
	 */
	public function get_facebook_connect_url() {
		$admin_url = admin_url( 'admin.php?page=' . $this->_options_page );

		$auth_url = add_query_arg(
			[
				'response_type' => 'token',
				'client_id'     => ENJOYINSTAGRAM_FB_APP_ID,
				'redirect_uri'  => ENJOYINSTAGRAM_GRAPH_API_REDIRECT,
				'scope'         => 'pages_show_list,instagram_basic',
			],
			'https://www.facebook.com/dialog/oauth'
		);

		$auth_url .= '&state=' . base64_encode( $admin_url );

		return $auth_url;
	}

	/**
	 * Sync media action manually from moderation panel
	 *
	 * @return void
	 */
	public function sync_media_action() {
		if ( ! isset( $_GET['sync_latest_media'] ) || ! $this->is_admin_page() || empty( $_GET['tab'] ) ) {
			return;
		}
		// sync.
		enjoyinstagram()->schedule_sync( true );

		wp_redirect( remove_query_arg( 'sync_latest_media' ) );
		exit;
	}
}

/**
 * Unique access to instance of EnjoyInstagram_Admin class
 *
 * @return EnjoyInstagram_Admin
 */
function ei_admin() {
	return EnjoyInstagram_Admin::get_instance();
}

ei_admin();
