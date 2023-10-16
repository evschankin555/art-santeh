<?php

class Fami_Elementor_Plugin {

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
        $current_theme = fmtpl_get_current_theme();
        $this->theme_version = $current_theme->version;
        require_once FAMI_TPL_DIR.'/inc/elementor/classes/select2-handler.php';
        require_once(FAMI_TPL_DIR.'/inc/elementor/custom_css.php');
        require_once(FAMI_TPL_DIR.'/inc/elementor/owner_elementor.php');
        add_action( 'wp_ajax_fmtpl_get_products_html', 'fmtpl_ajax_get_products_html' );
        add_action( 'wp_ajax_nopriv_fmtpl_get_products_html', 'fmtpl_ajax_get_products_html' );

        add_action( 'elementor/frontend/after_register_scripts', [$this, 'register_depends_js'] );
        add_action( 'elementor/frontend/after_register_styles', [$this, 'register_depends_css']);
        add_action( 'elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js'] );
        add_action( 'elementor/frontend/after_enqueue_styles', [$this, 'elementor_css'] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'elementor_widget_category' ] );
        // Register custom controls
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        add_action( 'elementor/editor/after_enqueue_scripts',[$this,'elementor_editor_enqueue'] );
    }


    /**


     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widgets() {
        require_once FAMI_TPL_DIR.'/inc/elementor/controls/select2.php';
        // Its is now safe to include Widgets files
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/reviews.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/images.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/products.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/posts.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/testimonial.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/categories_banner.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/slider.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/images-gallery.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/posts/post-list.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/menu.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/banner.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/inner_banner.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/outside_banner.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/countdown.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/title.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/button.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/tabs.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/writer_banner.php';
        require_once FAMI_TPL_DIR . '/inc/elementor/widgets/smooth_banner.php';
        $elementor_instance = \Elementor\Plugin::instance();

        // Register Widgets
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Reviews());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Images());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Posts());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Testimonial());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Categories_Banner());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Slider());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Images_Gallery());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Post_List());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Nav_Menu());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Banner());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Inner_Banner());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Outside_Banner());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Countdown());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Title());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Button());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Tabs());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Writer_Banner());
        $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Smooth_Banner());

        if (defined('ICL_SITEPRESS_VERSION') && ICL_SITEPRESS_VERSION){
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/language.php';
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Language() );
        }
        if (defined('WOOCS_VERSION') && WOOCS_VERSION){
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/currency.php';
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Currency() );
        }

        if (fmtpl_is_woocommerce_activated()) {
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/products/grid.php';
            //require_once FAMI_TPL_DIR . '/inc/elementor/widgets/products/carousel.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/products/tabs.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/category_banner.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/product_banner.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/products/deal.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/product_tabs.php';
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/products/pin.php';
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Products_Grid() );
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Products_Tabs() );
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Category_Banner() );
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Products() );
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Carousel_Banner());
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Product_Deal());
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_Product_Tabs_Carousel());
            $elementor_instance->widgets_manager->register_widget_type( new Product_Pins());
        }

        if (defined('MC4WP_VERSION')) {
            require_once FAMI_TPL_DIR . '/inc/elementor/widgets/newsletter.php';
            $elementor_instance->widgets_manager->register_widget_type( new Fmtpl_NewsLetter() );
        }

        if (defined('ENJOYINSTAGRAM_VERSION') && ENJOYINSTAGRAM_VERSION) {
            require_once FAMI_TPL_DIR .'/inc/elementor/widgets/instagram.php';
            $elementor_instance->widgets_manager->register_widget_type(new Fmtpl_Instagram());
        }
    }


    public function elementor_widget_category($widgets_manager) {
        \Elementor\Plugin::$instance->elements_manager->add_category(
            'fami-elements',
            [
                'title' => __('Fami Addons','fami-templatekits'),
            ],
            1
        );
    }
    public function register_controls( \Elementor\Controls_Manager $controls_Manager ) {
        require_once FAMI_TPL_DIR . '/inc/elementor/controls/select2.php';
        \Elementor\Plugin::$instance->controls_manager->register_control( Fmtpl_Select2::TYPE, new Fmtpl_Select2() );
    }

    public function register_depends_js(){
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        wp_register_script( 'jquery-countdown', FAMI_TPL_URL . 'assets/js/jquery.countdown'.$suffix.'.js' , ['jquery'], '2.2.0', true );
        wp_register_script('three',FAMI_TPL_URL. 'assets/js/three.min.js',[],FAMI_TPL_VER, true);
        wp_register_script('tweenmax',FAMI_TPL_URL. 'assets/js/TweenMax.min.js',[],'1.20.3', true);
        wp_register_script('hover',FAMI_TPL_URL. 'assets/js/hover.js',[],FAMI_TPL_VER, true);
    }

    public function register_depends_css() {
        wp_register_style( 'fmtpl-countdown', FAMI_TPL_URL. 'assets/css/fmtpl-countdown.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-post-list', FAMI_TPL_URL. 'assets/css/fmtpl-post-list.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-banner', FAMI_TPL_URL. 'assets/css/fmtpl-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-inner-banner', FAMI_TPL_URL. 'assets/css/fmtpl-inner-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-outside-banner', FAMI_TPL_URL. 'assets/css/fmtpl-outside-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-heading-title', FAMI_TPL_URL. 'assets/css/fmtpl-heading-title.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-category-banner', FAMI_TPL_URL. 'assets/css/fmtpl-category-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-products-tabs', FAMI_TPL_URL. 'assets/css/fmtpl-products-tabs.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-tabs', FAMI_TPL_URL. 'assets/css/fmtpl-tabs.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-product-deal', FAMI_TPL_URL. 'assets/css/fmtpl-product-deal.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-writer-banner', FAMI_TPL_URL. 'assets/css/fmtpl-writer-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-newsletter', FAMI_TPL_URL. 'assets/css/fmtpl-newsletter.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-instagram', FAMI_TPL_URL. 'assets/css/fmtpl-instagram.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-slider', FAMI_TPL_URL. 'assets/css/fmtpl-slider.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-menu', FAMI_TPL_URL. 'assets/css/fmtpl-menu.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-smooth-banner', FAMI_TPL_URL. 'assets/css/smooth-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-product-carousel', FAMI_TPL_URL. 'assets/css/fmtpl-product-carousel.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-products-tabs-carousel', FAMI_TPL_URL. 'assets/css/fmtpl-products-tabs-carousel.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-reviews', FAMI_TPL_URL. 'assets/css/fmtpl-reviews.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-categories-banner', FAMI_TPL_URL. 'assets/css/fmtpl-categories-banner.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-product-grid', FAMI_TPL_URL. 'assets/css/fmtpl-product-grid.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-product-pin', FAMI_TPL_URL. 'assets/css/fmtpl_product_pin.css',null, FAMI_TPL_VER );
        wp_register_style( 'fmtpl-animate', FAMI_TPL_URL. 'assets/css/animate.css',null, '4.1.1' );
    }

    public function elementor_js() {
        wp_enqueue_script( 'fmtpl-addons', FAMI_TPL_URL. 'assets/js/fmtpl-addons.js',array( 'jquery', 'elementor-frontend' ), FAMI_TPL_VER, true );
        $fmtpl_js_var = [
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'    => wp_create_nonce('ajax-nonce'),
        ];

        $fmtpl_js_var['countDown_text'] = [
            'w' => esc_html__('Weeks','locphat'),
            's' => esc_html__('Secs','locphat'),
            'm' => esc_html__('Mins','locphat'),
            'h' => esc_html__('Hours','locphat'),
            'd' => esc_html__('Days','locphat'),
        ];
        wp_localize_script( 'fmtpl-addons', 'Fmtpl_JsVars', $fmtpl_js_var);
    }

    public function elementor_css() {
        wp_enqueue_style( 'fmtpl-addons', FAMI_TPL_URL. 'assets/css/fmtpl-addons.css',null, FAMI_TPL_VER );
    }
    public function elementor_editor_enqueue() {
        wp_enqueue_style( 'fmtpl-addons', FAMI_TPL_URL. 'assets/css/fmtpl-editor.css',null, FAMI_TPL_VER );
    }
}

// Instantiate Plugin Class
Fami_Elementor_Plugin::instance();



