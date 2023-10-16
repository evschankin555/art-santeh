<?php

class Cenos_Elementor_Widget {

    /**
     * Instance
     *
     * @since 1.2.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;
    private $theme_version;
    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.2.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.2.0
     * @access public
     */
    public function __construct() {
        global $current_theme;
        $this->theme_version = $current_theme->get('Version');
        //register dependencies jquery
        add_action( 'elementor/frontend/after_register_scripts', [$this, 'register_depends_js'] );

        add_action('elementor/frontend/after_register_styles', [$this, 'register_depends_css']);
        //js for theme's elementor widgets
        add_action( 'elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js'] );
        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ],20 );
        //custom style for elementor editor backend

    }


    /**


     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     * $elementor_instance = \Elementor\Plugin::instance();
     * require_once CENOS_STYLESHEET_DIRECTORY . '/inc/elementor/widgets/button.php';
     * $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Button() );
     */

    public function register_widgets() {
        $elementor_instance = \Elementor\Plugin::instance();
        require_once CENOS_TEMPLATE_DIRECTORY.'/inc/elementor/widgets/advance_banner.php';
        $elementor_instance->widgets_manager->register_widget_type( new Cenos_Advance_Banner() );
        if (cenos_is_woocommerce_activated()){
            require_once CENOS_TEMPLATE_DIRECTORY . '/inc/elementor/widgets/category_bg.php';
            $elementor_instance->widgets_manager->register_widget_type( new Cenos_Category_Background() );
        }
    }

    public function register_depends_css() {
        wp_register_style( 'cenos_adv_banner', CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/css/adv_banner.css',null, $this->theme_version );
        wp_enqueue_style( 'cenos_category_bg', CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/css/category_bg.css',null, $this->theme_version );
    }

    public function register_depends_js(){
        wp_register_script('three',CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/js/three.min.js',[],$this->theme_version, true);
        wp_register_script('tweenmax',CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/js/TweenMax.min.js',[],'1.20.3', true);
        wp_register_script('hover',CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/js/hover.js',[],$this->theme_version, true);
    }

    public function elementor_js() {
        wp_enqueue_script( 'cenos-elementor', CENOS_TEMPLATE_DIRECTORY_URI. 'inc/elementor/widgets/assets/js/cenos_elementor.js',array( 'jquery', 'elementor-frontend' ), $this->theme_version, true );
    }

}
// Instantiate Class
Cenos_Elementor_Widget::instance();



