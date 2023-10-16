<?php
class Cenos_Theme
{
    /**
     * The theme version.
     *
     * @access public
     * @since 1.0.0
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The theme slug.
     *
     * @access public
     * @since 1.0.0
     * @var string
     */
    public $slug = 'cenos';
    /**
     * Current theme's directory name.
     *
     * @access public
     * @since 1.0.0
     * @var string
     */
    public $template = 'cenos';
    /**
     * The Customizer object.
     *
     * @access public
     * @since 1.0.0
     * @var Customizer
     */
    public $customizer;
    /**
     * The Scripts object.
     *
     * @access public
     * @since 1.0.0
     * @var Scripts
     */
    public $scripts;


    /**
     * The WooCommerce object.
     *
     * @access public
     * @since 1.0.0
     * @var WooCommerce
     */
    public $wc;
    /**
     * A single instance of this object.
     *
     * @static
     * @access private
     * @since 1.0
     * @var Gridd
     */

    public $is_maintenance_mode;

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
     * Constructor.
     *
     * @since 1.0
     * @access private
     */
    private function __construct()
    {
        global $current_theme;
        if (is_null($current_theme)){
            $current_theme = wp_get_theme('cenos');
            if ( ! empty( $current_theme['Template'] ) ) {
                $current_theme = wp_get_theme( $current_theme['Template'] );
                $GLOBALS['current_theme'] = $current_theme;
            }
        }
        $this->version = $current_theme->get('Version');
        $this->slug = get_stylesheet();
        $this->template = get_template();
        // Init Customizer.
        $this->customizer = require 'customizer.php';

        $this->is_maintenance_mode = cenos_is_enabled_maintenance();
        if ($this->is_maintenance_mode){
            return;
        }
        // Init Scripts.
        $this->scripts = require 'scripts.php';


        add_action('after_setup_theme', [$this, 'setup']);
        add_action( 'widgets_init',[$this, 'widgets_init'] );

    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @access public
     * @param array $classes Classes for the body element.
     * @return array
     * @since 1.0
     */

    public function setup()
    {

        // Loads wp-content/languages/themes/cenos-it_IT.mo.
        load_theme_textdomain('cenos', trailingslashit(WP_LANG_DIR) . 'themes');

        // Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
        load_theme_textdomain('cenos', CENOS_STYLESHEET_DIRECTORY . '/languages');

        // Loads wp-content/themes/cenos/languages/it_IT.mo.
        load_theme_textdomain('cenos', CENOS_TEMPLATE_DIRECTORY . '/languages');

        /**
         * Add default posts and comments RSS feed links to head.
         */
        add_theme_support('automatic-feed-links');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        /*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
        add_theme_support(
            'html5', apply_filters(
                'cenos_html5_args', array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'widgets',
                )
            )
        );

        // Supports WooCommerce plugin.
        add_theme_support('woocommerce');
        add_theme_support('fmfw-product-360degree');
        add_theme_support('fmfw-product-video');
        add_theme_support('fmfw-promo-info');
        remove_theme_support( 'product_grid' );

        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-slider' );
        add_theme_support('post-formats', array('gallery', 'video', 'audio'));
        /**
         * Register menu locations.
         */
        register_nav_menus(
            apply_filters(
                'cenos_register_nav_menus', array(
                    'primary' => esc_html__('Primary Menu', 'cenos'),
                    'hamburger' => esc_html__('Hamburger Menu', 'cenos'),
                    'mobile' => esc_html__('Mobile Menu', 'cenos'),
                )
            )
        );


        /**
         * Declare support for title theme feature.
         */
        add_theme_support('title-tag');

        /**
         * Declare support for selective refreshing of widgets.
         */
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for Block Styles.
         */
        add_theme_support('wp-block-styles');

        /**
         * Add support for full and wide align images.
         */
        add_theme_support('align-wide');

        /**
         * Add support for editor styles.
         */
        add_theme_support('editor-styles');

        /**
         * Add support for editor font sizes.
         */
        add_theme_support('editor-font-sizes', array(
            array(
                'name' => esc_html__('Small', 'cenos'),
                'size' => 14,
                'slug' => 'small',
            ),
            array(
                'name' => esc_html__('Normal', 'cenos'),
                'size' => 16,
                'slug' => 'normal',
            ),
            array(
                'name' => esc_html__('Medium', 'cenos'),
                'size' => 23,
                'slug' => 'medium',
            ),
            array(
                'name' => esc_html__('Large', 'cenos'),
                'size' => 26,
                'slug' => 'large',
            ),
            array(
                'name' => esc_html__('Huge', 'cenos'),
                'size' => 37,
                'slug' => 'huge',
            ),
        ));

        /**
         * Add support for responsive embedded content.
         */
        add_theme_support('responsive-embeds');
    }
    public function widgets_init(){
        $title_before = '';
        $title_class  = '';
        $title_after  = '';

        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'cenos' ),
            'id'            => 'sidebar-main',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => $title_before . '<span class="widget-title ' . $title_class . '"><span>',
            'after_title'   => '</span></span>' . $title_after,
        ));
        if (cenos_is_woocommerce_activated()){
            register_sidebar( array(
                'name'          => esc_html__( 'Products Filter', 'cenos' ),
                'id'            => 'product-filter',
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ) );
            register_sidebar( array(
                'name'          => esc_html__( 'Product Sidebar', 'cenos' ),
                'id'            => 'product-sidebar',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<span class="widget-title">',
                'after_title'   => '</span>',
            ));
            register_sidebar( array(
                'name'          => esc_html__( 'Product Sidebar Full Height', 'cenos' ),
                'id'            => 'product-sidebar-full-height',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<span class="widget-title">',
                'after_title'   => '</span>',
            ));
            register_sidebar( array(
                'name'          => esc_html__( 'Hamburger Sidebar', 'cenos' ),
                'id'            => 'hamburger-sidebar',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<span class="widget-title">',
                'after_title'   => '</span>',
            ));
        }
    }

}

$Cenos_theme = Cenos_Theme::get_instance();
