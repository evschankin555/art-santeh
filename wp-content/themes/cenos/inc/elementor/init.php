<?php

use Elementor\Core\Responsive\Responsive;
class Cenos_Elementor_Package {

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

        $this->include_require();
        add_filter('fmtpl-newsletter-elementor-widget-control',[$this,'cenos_newsletter_elementor_widget_control']);
        //fmtpl elementor widget control
        add_filter('fmtpl-banner-elementor-widget-control',[$this,'fmtpl_banner_elementor_widget_control']);
        add_filter('fmtpl-category-banner-elementor-widget-control',[$this,'fmtpl_category_banner_elementor_widget_control']);
        add_filter('fmtpl-products-grid-elementor-widget-control',[$this,'fmtpl_products_grid_elementor_widget_control']);
        add_filter('fmtpl-carousel-products-elementor-widget-control',[$this,'fmtpl_products_carousel_elementor_widget_control']);
        add_filter('fmtpl-products-tabs-elementor-widget-control',[$this,'fmtpl_products_tabs_elementor_widget_control']);
        add_filter('fmtpl-carousel-images-elementor-widget-control',[$this,'fmtpl_carousel_images_elementor_widget_control']);
        add_filter('fmtpl-carousel-posts-elementor-widget-control',[$this,'fmtpl_carousel_posts_elementor_widget_control']);
        add_filter('fmtpl-carousel-reviews-elementor-widget-control',[$this,'fmtpl_carousel_reviews_elementor_widget_control']);
        add_filter('fmtpl-instagram-elementor-widget-control',[$this,'fmtpl_instagram_elementor_widget_control']);
        add_filter('fmtpl-menu-elementor-widget-control',[$this,'fmtpl_menu_elementor_widget_control']);
        add_filter('fmtpl-products-sc-html','fmtpl_products_grid_item_style',10,3);
        add_filter('fmtpl-carousel-products-widget-html','fmtpl_carousel_products_widget_html',10,3);
        add_filter('fmtpl-carousel-posts-widget-html','fmtpl_carousel_posts_widget_html',10,3);
        add_filter('fmtpl-carousel-reviews-widget-html','fmtpl_carousel_reviews_widget_html',10,3);
        add_filter('fmtpl-instagram-widget-html','fmtpl_instagram_widget_html',10,3);
        add_filter('fmtpl-banner-elementor-widget-html','fmtpl_banner_elementor_widget_html',10,2);
        add_filter('fmtpl-category-banner-elementor-widget-html','fmtpl_category_banner_html',10,2);
    }

    public function fmtpl_instagram_elementor_widget_control($control){
        $control['layout']['options'] = [
            'layout1' =>esc_html__( 'Layout 1', 'cenos' ),
            'layout2' =>esc_html__( 'Layout 2', 'cenos' ),
            'layout3' =>esc_html__( 'Layout 3', 'cenos' ),
        ];
        $control['layout']['default'] = 'layout1';
        return $control;
    }
    public function fmtpl_products_tabs_elementor_widget_control($control) {
        $fmtpl_products_item_style = $this->fmtpl_products_item_style();
        $control['layout']['options'] = [
            'default' => __('Default', 'cenos'),
        ];
        unset($fmtpl_products_item_style['slider']);
        $control['item_style']['options'] = $fmtpl_products_item_style;
        return $control;
    }
    public function fmtpl_carousel_images_elementor_widget_control($control) {
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'cenos_carousel_image_01' =>esc_html__( 'Cenos Layout 01', 'cenos' ),
            'cenos_carousel_image_02' =>esc_html__( 'Cenos Layout 02', 'cenos' ),
            'cenos_carousel_image_03' =>esc_html__( 'Cenos Layout 03', 'cenos' ),
        ];
        $control['pagination_style']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'rectangle' =>esc_html__( 'Rectangle', 'cenos' ),
            'circle' =>esc_html__( 'Circle', 'cenos' ),
        ];
        return $control;
    }
    public function fmtpl_carousel_posts_elementor_widget_control($control) {
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'cenos_carousel_posts_01' =>esc_html__( 'Cenos Layout 01', 'cenos' ),
            'cenos_carousel_posts_02' =>esc_html__( 'Cenos Layout 02', 'cenos' ),
        ];
        $control['pagination_style']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'rectangle' =>esc_html__( 'Rectangle', 'cenos' ),
            'circle' =>esc_html__( 'Circle', 'cenos' ),
        ];

        return $control;
    }
    public function fmtpl_carousel_reviews_elementor_widget_control($control) {
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'cenos_01' =>esc_html__( 'Cenos Layout 01', 'cenos' ),
        ];
        $control['pagination_style']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'rectangle' =>esc_html__( 'Rectangle', 'cenos' ),
            'circle' =>esc_html__( 'Circle', 'cenos' ),
        ];
        $control['title_color']['selectors'] = [
            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title' => 'color: {{VALUE}};',
            '{{WRAPPER}} .fmtpl-reviews-layout-cenos_01 .fmtpl-carousel-box-heading .fmtpl-carousel-box-title .highlight' => 'background-color: {{VALUE}};',
        ];
        $control['item_image_gap']['selectors'] = [
            '{{WRAPPER}} .fmtpl-carousel-reviews:not(.fmtpl-reviews-layout-cenos_01) .fmtpl-reviews-image' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            '{{WRAPPER}} .fmtpl-reviews-layout-cenos_01 .fmtpl-reviews-image' => 'margin-right: {{SIZE}}{{UNIT}}',
        ];
        return $control;
    }
    public function fmtpl_products_grid_elementor_widget_control($control) {
        $fmtpl_products_item_style = $this->fmtpl_products_item_style();
        $control['item_style']['options'] = $fmtpl_products_item_style;
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'cenos_layout1' =>esc_html__( 'Cenos Layout 01', 'cenos' ),
        ];
        $control['hover_img']['options'] = $this->fmtpl_product_hover_image();
        $control['hover_img']['condition'] = ['item_style!'=> 'slider'];
        return $control;
    }
    public function fmtpl_products_carousel_elementor_widget_control($control) {
        $fmtpl_products_item_style = $this->fmtpl_products_item_style();
        unset($fmtpl_products_item_style['slider']);
        $control['item_style']['options'] = $fmtpl_products_item_style;
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'layout1' =>esc_html__( 'Cenos Layout 01', 'cenos' ),
            'layout2' =>esc_html__( 'Cenos Layout 02', 'cenos' ),
            'layout3' =>esc_html__( 'Cenos Layout 03', 'cenos' ),
            'layout4' =>esc_html__( 'Cenos Layout 04', 'cenos' ),
        ];
        $control['pagination_style']['options'] = [
                'default' =>esc_html__( 'Default', 'cenos' ),
                'rectangle' =>esc_html__( 'Rectangle', 'cenos' ),
                'circle' =>esc_html__( 'Circle', 'cenos' ),
        ];
        return $control;
    }
    public function fmtpl_banner_elementor_widget_control($control) {
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default Style', 'cenos' ),
            'cenos_banner_simple_01' =>esc_html__( 'Simple Style 01', 'cenos' ),
            'cenos_banner_simple_02' =>esc_html__( 'Simple Style 02', 'cenos' ),
            'cenos_banner_parallax' =>esc_html__( 'Parallax Style', 'cenos' ),
        ];
        $control['title_color']['selectors'] = [
            '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title' => 'color: {{VALUE}};',
            '{{WRAPPER}} .fmtpl-banner.cenos_banner_simple_02 .fmtpl-banner-title .highlight' => 'background-color: {{VALUE}};',
        ];
        $unused_control  = [
            'sub_title',
            'sub_title_color',
            'sub_title_typography',
            'sub_title_bottom_space',
            'show_divider',
	        'divider_width',
	        'divider_height',
	        'divider_color',
	        'divider_margin',
	        'divider_border_type',
	        'divider_border_radius',
	        'divider_border_color_hover',
	        'divider_position',
            'effect'
        ];
        foreach ($unused_control as $control_key){
            if (isset($control[$control_key])){
                unset($control[$control_key]);
            }
        }
        return $control;
    }
    public function fmtpl_category_banner_elementor_widget_control($control){
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'cenos_layout1' =>esc_html__( 'Cenos Layout 1', 'cenos' ),
            'cenos_layout2' =>esc_html__( 'Cenos Layout 2', 'cenos' ),
            'cenos_outside1' =>esc_html__( 'Cenos Outside 1', 'cenos' ),
            'cenos_outside2' =>esc_html__( 'Cenos Outside 2', 'cenos' ),
            'cenos_outside3' =>esc_html__( 'Cenos Outside 2', 'cenos' ),
        ];
        if (isset($control['background_hover_color'])){
            unset($control['background_hover_color']);
        }
        return $control;
    }
    public function include_require(){
        if (defined('FAMI_TPL_VER')){
            include_once 'widgets/init.php';
        }
        include_once 'fmtpl_template_functions.php';//template for fmtpl-products-grid
    }

    public function cenos_newsletter_elementor_widget_control($control) {
        $control['layout']['options'] = [
            'layout1' =>esc_html__( 'Layout 1', 'cenos' ),
            'layout2' =>esc_html__( 'Layout 2', 'cenos' ),
            'layout3' =>esc_html__( 'Layout 3', 'cenos' ),
        ];
        return $control;
    }
    public function fmtpl_products_item_style() {
        return [
            'style-1' =>esc_html__( 'Layout 1', 'cenos' ),
            'style-2' =>esc_html__( 'Layout 2', 'cenos' ),
            'style-3' =>esc_html__( 'Layout 3', 'cenos' ),
            'style-4' =>esc_html__( 'Layout 4', 'cenos' ),
            'style-5' =>esc_html__( 'Layout 5', 'cenos' ),
            'style-6' =>esc_html__( 'Layout 6', 'cenos' ),
            'style-7' =>esc_html__( 'Layout 7', 'cenos' ),
            'border' =>esc_html__( 'Border', 'cenos' ),
            'slider' => esc_html__( 'Slider', 'cenos' ),
            'style-clean' => esc_html__( 'Clean', 'cenos' ),
        ];
    }

    public function fmtpl_menu_elementor_widget_control($control){
        $control['layout']['options'] = [
            'default' =>esc_html__( 'Default', 'cenos' ),
            'horizontal' =>esc_html__( 'Horizontal', 'cenos' ),
        ];
        return $control;
    }
    public function fmtpl_product_hover_image() {
        return [
            'default' => esc_html__('Default', 'cenos'),
            'second' => esc_html__('Second Image', 'cenos'),
            'zoom' => esc_html__('Zoom', 'cenos'),
        ];
    }
}
// Instantiate Plugin Class
Cenos_Elementor_Package::instance();

