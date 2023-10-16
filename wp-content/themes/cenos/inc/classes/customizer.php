<?php
/**
 * Cenos Customizer Class
 *
 * @package  Cenos
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Customizer
{
    /**
     * Customize panels
     *
     * @var array
     */
    protected $panels = array();
    protected $sections = array();
    protected $fields = array();
    protected $theme = '';
    private static $instance;
    /**
     * Get an instance of this object.
     *
     * @static
     * @access public
     * @return Gridd
     * @since 1.0
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * The class constructor
     *
     * @param array $config
     */
    public function __construct( ) {
        add_action('init',[$this,'init']);
    }

    public function init(){
        $this->get_settings();
        if (current_user_can('edit_theme_options')){
            add_action('customize_save_after', array($this,'remove_old_customize_file'),99);
            add_action( 'customize_register', array($this,'customize_modify'));
            $this->register();
            add_filter('cenos_demo_customize_fields',[$this,'get_fields']);
            add_filter('cenos_demo_customize_sections',[$this,'get_sections']);
        }
    }

    public function get_fields(){
        return $this->fields;
    }
    public function get_sections() {
        return $this->sections;
    }
    /**
     * Get settings
     */
    public function get_settings() {
        $this->theme = get_stylesheet();
        // Register panels
        $panels = array();
        // Register sections
        $sections = array();
        // Register fields
        $fields = array();
        $this->panels   = apply_filters( 'cenos_customize_panels', $panels );
        $this->sections = apply_filters( 'cenos_customize_sections', $sections );
        $this->fields   = apply_filters( 'cenos_customize_fields', $fields );
    }

    /**
     * Register settings
     */
    public function register() {
        if ( ! class_exists( 'Kirki' ) ) {
            return;
        }
        /**
         * Add the theme configuration
         */
        if ( ! empty( $this->theme ) ) {
            Kirki::add_config( $this->theme, array(
                'capability'  => 'edit_theme_options',
                'option_type' => 'theme_mod',
            ) );
        }

        /**
         * Add panels
         */
        if ( ! empty( $this->panels ) ) {
            foreach ( $this->panels as $panel => $settings ) {
                Kirki::add_panel( $panel, $settings );
            }
        }

        /**
         * Add sections
         */
        if ( ! empty( $this->sections ) ) {
            foreach ( $this->sections as $section => $settings ) {
                Kirki::add_section( $section, $settings );
            }
        }

        /**
         * Add fields
         */
        if ( ! empty( $this->theme ) && ! empty( $this->fields ) ) {
            foreach ( $this->fields as $name => $settings ) {
                if ( ! isset( $settings['settings'] ) ) {
                    $settings['settings'] = $name;
                }
                Kirki::add_field( $this->theme , $settings );
            }
        }
    }

    /**
     * Get customize setting value
     *
     * @param string $name
     *
     * @return bool|string
     */
    public function get_option( $name ) {
        $default = $this->get_option_default( $name );
        return cenos_get_theme_mod( $name, $default );
    }

    /**
     * Get default option values
     *
     * @param $name
     *
     * @return mixed
     */
    public function get_option_default( $name ) {
        if ( ! isset( $this->fields[ $name ] ) ) {
            return false;
        }
        return isset( $this->fields[ $name ]['default'] ) ? $this->fields[ $name ]['default'] : false;
    }

    /**
     * Move some default sections to `general` panel that registered by theme
     *
     * @param object $wp_customize
     */
    public function customize_modify( $wp_customize ) {
        $wp_customize->get_section( 'static_front_page' )->panel = 'general';
        $woo_panel =  $wp_customize->get_panel( 'woocommerce' );
        if (!empty($woo_panel)) {
            $woo_panel->title = esc_html__('Shop (WooCommerce)','cenos');
            $woo_panel->priority = '12';
        }
    }

    /**
     * Re general css with customize setting.
     *
     * @access public
     * @since 1.0
     * @return string css code
     */
    public function general_style($file_name = "customize") {
        if ($file_name == 'customize'){
            $preview = is_customize_preview();//global var use in some file _css.php
            $css = '';
            //css from customize setting
            //general style for site before detail component style
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/typography/typography_css.php';
            // color setting & button setting
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/color_style/color_style_css.php';
            //detail component style
            //site layout, background setting
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/layout/site_layout_css.php';
            //header  setting
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/header/style/header_css.php';
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/announcement/announcement_css.php';
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/blog/blog_css.php';
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/woocommerce/shop_css.php';
            require_once CENOS_TEMPLATE_DIRECTORY.'/inc/settings/footer/footer_css.php';
            return $css;
        }
        if (strpos($file_name,'main_color') !== false){
            $m_color = '#e36c02';
            $pre_main_color = CENOS_TEMPLATE_DIRECTORY.'/assets/css/pre_main_color.css';
            $pre_main_color_content = '';
            if ( file_exists( $pre_main_color ) ) {
                global $wp_filesystem;
                require_once ( ABSPATH . '/wp-admin/includes/file.php' );
                WP_Filesystem();
                $pre_main_color_content = $wp_filesystem->get_contents( $pre_main_color );
                $main_color = cenos_get_option('main_color');
                if ($main_color != '' && $main_color != $m_color){
                    $pre_main_color_content = str_replace($m_color,$main_color,$pre_main_color_content);
                }
            }
            return $pre_main_color_content;
        }
        if (strpos($file_name,'body_color') !== false){
            $b_color = '#777';
            $pre_body_color = CENOS_TEMPLATE_DIRECTORY.'/assets/css/pre_body_color.css';
            $pre_body_color_content = '';
            if ( file_exists( $pre_body_color ) ) {
                global $wp_filesystem;
                require_once ( ABSPATH . '/wp-admin/includes/file.php' );
                WP_Filesystem();
                $pre_body_color_content = $wp_filesystem->get_contents( $pre_body_color );
                $body_color = cenos_get_option('typo_body');
                if (isset($body_color['color']) && $body_color['color'] != '' && $body_color['color'] != $b_color){
                    $pre_body_color_content = str_replace($b_color,$body_color['color'],$pre_body_color_content);
                }
            }
            return $pre_body_color_content;
        }
    }

    public function get_current_customize_file($file_name = "customize"){
        global $customize_file;
        if (is_null($customize_file) || !isset($customize_file[$file_name])){
            $customize_file[$file_name] = $file_name.'.css';
            if (is_multisite()){
                $current_blog_id = get_current_blog_id();
                if (!is_main_site($current_blog_id)){
                    $customize_file[$file_name] = $file_name.'_'.$current_blog_id.'.css';
                }
            }
            $GLOBALS['customize_file'] = $customize_file;
        }
        return $customize_file[$file_name];
    }


    public function remove_old_customize_file(){
        $customize_files =  ['customize','body_color','main_color'];
        foreach ($customize_files as $file_name){
            $customize_file = $this->get_current_customize_file($file_name);
            $file_customize = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$customize_file;
            if (file_exists($file_customize)){
                unlink($file_customize);
            }
        }
        delete_transient( 'cenos_woocommerce_products_new' );
    }
    /**
     * Re general css with customize setting.
     *
     * @access public
     * @since 1.0
     * @return bool
     */
    public function general_customize_file($file_name = "customize"){
        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        $customize_file = $this->get_current_customize_file($file_name);

        $file_customize = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$customize_file;
        $css = $this->general_style($file_name);
        if( $wp_filesystem && $css != '') {
            return $wp_filesystem->put_contents( $file_customize, $css, FS_CHMOD_FILE );
        }
        return false;
    }
}



/**
 * Global variable
 */
return Customizer::get_instance();
