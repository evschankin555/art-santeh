<?php
/*
 * Plugin Name: Fami Framework
 * Plugin URI: https://familab.net/
 * Description: Core functions for WordPress theme
 * Author: Familab
 * Version: 1.1.4
 * Author URI: https://familab.net/
 * Text Domain: fami-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
if ( ! defined( 'FMFW_VERSION' ) ) {
    define('FMFW_VERSION', '1.1.4');
}
if ( ! defined( 'FAMI_FRAMEWORK_PLUGIN_URL' ) ) {
    define('FAMI_FRAMEWORK_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if ( ! defined( 'FAMI_FRAMEWORK_PLUGIN_DIR' ) ) {
    define('FAMI_FRAMEWORK_PLUGIN_DIR', dirname(__FILE__));
}
if ( ! defined( 'FAMI_THEME_DIR' ) ) {
    define('FAMI_THEME_DIR', get_template_directory());
}

if ( ! defined( 'FAMI_THEME_URI' ) ) {
    define( 'FAMI_THEME_URI', trailingslashit ( get_template_directory_uri() ));
}
if (!defined('FAMILAB_API_URL')) {
    define('FAMILAB_API_URL', 'https://api.familab.net');
}
if (!defined('FAMILAB_DOC_URL')) {
    define('FAMILAB_DOC_URL', 'https://docs.familab.net');
}
include_once 'fmfw-functions.php';
if(!class_exists('Fami_Framework')) {
	class Fami_Framework{
        private static $_instance;
        private $async_scripts = [];

        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		public function __construct() {
            $current_theme = $this->get_current_theme();
            if (!defined('FAMI_THEME_VERSION')) {
                define('FAMI_THEME_VERSION', $current_theme->version);
                define('FAMI_THEME_NAME', $current_theme->name);
                define('FAMI_THEME_SLUG', $current_theme->template);
            }

            include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/megamenu/fm_mega_menu.php';
            include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/market_check.php';
            include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/theme_update.php';
            include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/plugin_update.php';
            include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/custom_fonts.php';
            if (fmfw_is_woocommerce_activated()) {
                add_action('init', [$this, 'woo_init']);
                include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/woo/fm_product_cat_background.php';
                include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/woo/product-360degree.php';
                include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/woo/product-video.php';
                include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/woo/promo.php';
                include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/woo/brand.php';
            }

            add_action('admin_init', [$this, 'admin_init']);
            $this->async_scripts = apply_filters( 'fami_framework_async_scripts', $this->async_scripts );
            add_filter( 'script_loader_tag', [ $this, 'add_async_attribute' ], 10, 2 );
            add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 20 );
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts_and_styles']);
            add_action('widgets_init', array($this, 'register_widgets'));
            add_action('wp_ajax_preset_action', [$this, 'preset_action_process']);
            if (defined('WOOSQ_VERSION')){
                add_filter('get_user_metadata',[$this, 'fmfw_user_meta_value'],10,3);
            }
            $this->load_textdomain();
		}

        public function load_textdomain() {
            load_plugin_textdomain( 'fami-framework', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
        private function get_current_theme(){
            $theme = wp_get_theme();
            if (!empty($theme['Template'])) {
                $theme = wp_get_theme($theme['Template']);
            }
            return $theme;
        }

        public function fmfw_user_meta_value($value,$user_id,$meta_key){
            if (strpos($meta_key,'thunk_notice_ignore') !== false){
                return true;
            }
            return $value;
        }

	    public function admin_init() {
	        if (defined( 'RWMB_VER' )){
                require_once FAMI_FRAMEWORK_PLUGIN_DIR . '/includes/metabox/meta-box-tabs.php';
                require_once FAMI_FRAMEWORK_PLUGIN_DIR . '/includes/metabox/conditional-logic.php';
            }
            remove_filter( 'pre_update_option_woocommerce_thumbnail_image_width', 'wvs_clear_transient' );
            remove_filter( 'pre_update_option_woocommerce_thumbnail_cropping', 'wvs_clear_transient' );
        }

        public function register_widgets()
        {
            $widgets = apply_filters('fmfw_widgets', array());
            if ($widgets) {
                foreach ($widgets as $class => $w_list) {
                    if ($class == 'no_required') {
                        $this->add_widget($w_list);
                    } else {
                        if (class_exists($class)) {
                            $this->add_widget($w_list);
                        }
                    }
                }
            }
        }
        public function add_widget($widgets = array())
        {
            foreach ($widgets as $w) {
                register_widget($w);
            }
        }
        public function enqueue_admin_scripts_and_styles($hook_suffix) {
            if (isset($_GET['page']) && $_GET['page'] == 'fami-dashboard') {
                wp_enqueue_script( 'bootstrap', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/3rd-party/bootstrap/js/bootstrap.min.js' ,['jquery'],'4.3.1',false);
                wp_enqueue_script( 'fami-admin-script', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/js/admin_script.js' ,['jquery'],FMFW_VERSION,false);
                wp_localize_script('fami-admin-script', 'ajax_var', array(
                    'url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('fmfw-preset-nonce')
                ));
                wp_enqueue_script( 'igrowl', FAMI_FRAMEWORK_PLUGIN_URL. '/assets/3rd-party/iGrowl/js/igrowl.min.js', array( 'jquery' ), '3.0.1', true );

                wp_enqueue_style( 'igrowl', FAMI_FRAMEWORK_PLUGIN_URL. '/assets/3rd-party/iGrowl/css/igrowl.min.css', array(), '3.0.1' );
                wp_enqueue_style('bootstrap',FAMI_FRAMEWORK_PLUGIN_URL . '/assets/3rd-party/bootstrap/css/bootstrap.min.css',[],'4.3.1');
                wp_enqueue_style('fami-admin-style',FAMI_FRAMEWORK_PLUGIN_URL . '/assets/css/admin_style.css',[],FMFW_VERSION);
            }
        }

        public function preset_action_process() {
	    $result = [];
	    $nonce = $_POST['nonce'];
	    if (wp_verify_nonce($nonce,'fmfw-preset-nonce')){
                $package = $_POST['package'];
                $version = $_POST['version'];
                $file_json = FAMI_THEME_DIR.'/inc/preset/json/'.$package.'/'.$version.'.json';
                if (!file_exists($file_json)) {
                    $result['status'] = false;
                    $result['msg'] = 'file: `/'.$package.'/'.$version.'.json` not exists.';
                } else {
                    $file_json_content = fmfw_get_json_content($file_json);
                    if (fmfw_update_customize_preset($file_json_content)) {
                        $result['status'] = true;
                        $result['msg'] = 'Preset `'.$package.'/'.$version.'.json` applied.';
                    } else {
                        $result['status'] = false;
                        $result['msg'] = 'Syntax error, malformed JSON.';
                    }
                }
                //$result['msg'] = 'Security ok!';
            } else {
                $result['msg'] = 'Security fail!';
            }
            wp_send_json($result);
        }
        /**
         * Add async to scripts.
         *
         * @access public
         * @since 1.0
         * @param string $tag    The script tag.
         * @param string $handle The script handle.
         * @return string
         */
        public function add_async_attribute( $tag, $handle ) {
            foreach ( $this->async_scripts as $script ) {
                if ( $script === $handle ) {
                    return str_replace( ' src', ' async="async" src', $tag );
                }
            }
            return $tag;
        }

        public function register_admin_menu() {
            add_menu_page(
                __( 'FamiLab', 'fami-framework' ),
                __( 'FamiLab', 'fami-framework' ),
                'manage_options',
                'fami-dashboard',
                [ $this, 'display_settings_page' ],
                '',
                '2'
            );
        }
        public function display_settings_page() {
            include_once (dirname(__FILE__).'/templates/dashboard.php');
        }
        public function woo_init() {
            //Move subcategories to a new list
            remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
        }

	}
}
function fmfw_initialize(){
    Fami_Framework::instance();
}
add_action('plugins_loaded','fmfw_initialize');
