<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use \Elementor\Utils;

class Fmtpl_Product_Tabs_Carousel extends Fmtpl_Carousel_Base{
    protected $default_control;

    public function get_name()
    {
        return 'fmtpl-product-tabs-carousel';
    }

    public function get_title()
    {
        return __('Products Tabs Carousel', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-product-tabs fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'woocommerce', 'shop', 'product', 'archive', 'carousel','tab' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $script_depends = ['swiper'];
        foreach ($script_depends as $script){
            $this->add_script_depends($script);
        }

        $this->add_style_depends('fmtpl-products-tabs-carousel');
    }
    protected function define_widget_control()
    {
        $products_item_style = apply_filters('fmtpl_products_item_style',['default' => __('Default Theme Style', 'fami-templatekits')]);
        $widget_control = [
            'layout' => [
                'label' => __('Layout', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'fmtpl_style01' => __('Style 01', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],

            'item_style' => [
                'label' => __('Product Item Style', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $products_item_style,
                'style_transfer' => true,
            ],
            'product_img' => [
                'label' => __('Product Image Size', 'fami-templatekits'),
                'name' => 'product_img', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'woocommerce_thumbnail',
                'exclude' => [ 'custom' ],
                'separator' => 'none',
            ],
            'hover_img' => [
                'label' => __('Product Image Hover', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'second' => __('Second Image', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],//Show Stars Rating
            'show_category' => [
                'label' => __('Show Category', 'fami-templatekits'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],

            'show_rating' => [
                'label' => __('Show Stars Rating', 'fami-templatekits'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],
            'link' => [
                'label' => __( 'Link', 'fami-templatekits' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'fami-templatekits' ),
                'show_external' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ],
            'button_text' => [
                'label' => __( 'Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __( 'Type button text here', 'fami-templatekits' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ],
            'button_icon' => [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ],
            //tabs fields
            'tab_title' => [
                'label' => __( 'Tab Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Tab Title' , 'fami-templatekits' ),
                'label_block' => true,
            ],
            'tab_icon_type' => [
                'label' => __( 'Icon Type', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'icon',
                'options' => [
                    'icon' => [
                        'title' => __( 'Icon', 'fami-templatekits' ),
                        'icon' => 'eicon-font-awesome',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'fami-templatekits' ),
                        'icon' => 'eicon-upload',
                    ],
                ],
                'toggle' => true,
            ],
            'tab_img_icon' => [
                'label' => __( 'Choose Image', 'fami-templatekits' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => ['tab_icon_type' => 'image']
            ],
            'tab_icon' => [
                'label' => __( 'Tab Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['tab_icon_type' => 'icon']
            ],
            'source' => [
                'label' => __('Product Item Style', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bestselling',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'bestselling' => __('Bestselling', 'fami-templatekits'),
                    'latest' => __('Latest', 'fami-templatekits'),
                    'featured' => __('Featured', 'fami-templatekits'),
                    'top_rated' => __('Top rated', 'fami-templatekits'),
                    'on_sale' => __('On-sale', 'fami-templatekits'),
                    'manual' => __('Manual Selection', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],
            'category_id' => [
                'label' => __('Product Categories', 'fami-templatekits'),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => false,
                'placeholder' => __('Search Category', 'fami-templatekits'),
                'data_options' => [
                    'taxonomy_type' => 'product_cat',
                    'action' => 'fmtpl_taxonomy_list_query'
                ],
                'condition' => ['source!' => 'manual']
            ],
            'product_ids' => [
                'label' => __('Products', 'fami-templatekits'),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => true,
                'placeholder' => __('Search Products', 'fami-templatekits'),
                'data_options' => [
                    'post_type' => 'product',
                    'action' => 'fmtpl_post_list_query'
                ],
                'condition' => ['source' => 'manual']
            ],
            'newness' => [
                'label' => __( 'Number of days newness', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 1,
                'condition' => ['source' => 'latest']
            ],
            'max_items' => [
                'label' => __( 'Max Items', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'condition' => ['source!' => 'manual']
            ],
            'orderby' => [
                'label' => __( 'Order By', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __( 'Date', 'fami-templatekits' ),
                    'title' => __( 'Title', 'fami-templatekits' ),
                    'price' => __( 'Price', 'fami-templatekits' ),
                    'popularity' => __( 'Popularity', 'fami-templatekits' ),
                    'rating' => __( 'Rating', 'fami-templatekits' ),
                    'rand' => __( 'Random', 'fami-templatekits' ),
                    'menu_order' => __( 'Menu Order', 'fami-templatekits' ),
                ],
            ],
            'order' => [
                'label' => __( 'Order', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => __( 'ASC', 'fami-templatekits' ),
                    'desc' => __( 'DESC', 'fami-templatekits' ),
                ],
            ],


            'btn_width' => [
                'label' => __( 'Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ],
            'btn_height' => [
                'label' => __( 'Height', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button' => 'color: {{VALUE}};fill:  {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'btn_color_hover' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'btn_bg_color' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button:hover' => 'border-color: {{VALUE}};',
                ],
            ],
            'btn_icon_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-products-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['button_icon[value]!' => '']

            ],
            'btn_icon_size' =>  [
                'label' => __( 'Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['button_icon[value]!' => '']
            ],
            'btn_icon_position' =>  [
                'label' => __( 'Icon Position', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => ['button_icon[value]!' => '']
            ],
            'btn_text_typography' => [
                'name' => 'btn_text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-btn-text',
            ],
            //tab title style
            'tab_title_width' => [
                'label' => __( 'Min Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link' => 'min-width: {{SIZE}}{{UNIT}};',
                ],

            ],
            'tab_title_alignment' => [
                'label' => __('Tab Title Alignment', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'right' => 'justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .nav-tabs' => '{{VALUE}};',
                ],
            ],
            'tab_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_title_hover_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link.active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link.active svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_title_bg_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link' => 'background-color: {{VALUE}};fill: {{VALUE}};',
                ],
            ],
            'tab_title_bg_hover_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link:hover,{{WRAPPER}} .fmtpl-products-tabs .fmtpl-tab-link.active' => 'background-color: {{VALUE}};',
                ],
            ],
            'tab_title_border' => [
                'name' => 'tab_title_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .fmtpl-tab-link',
                'separator' => 'before',
            ],
            'list_item_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .fmtpl-tab-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_title_border_hover_color' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .fmtpl-tab-link:hover, {{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .fmtpl-tab-link.active' => 'border-color: {{VALUE}};',

                ],
            ],
            'tab_title_typography' => [
                'name' => 'tab_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,

            ],
            'tab_icon_color' => [
                'label' => __( 'Icon Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_icon_color_hover' => [
                'label' => __( 'Icon Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link:hover svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],

            'tab_icon_space' => [
                'label' => __( 'Icon Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .nav-tabs.left .fmtpl-tab-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .nav-tabs.top .fmtpl-tab-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .nav-tabs.right .fmtpl-tab-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ],

            'tab_icon_size' => [
                'label' => __( 'Icon Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-icon *' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ],

            'tab_icon_position' => [
                'label' => __( 'Icon Position', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'fami-templatekits' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
            ],
            'tab_title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_title_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tab-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_bottom_space' => [
                'label' => __( 'Content Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-product-tabs-carousel .fmtpl-tabs-wrapper .nav-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
        ];
        $this->default_control = apply_filters('fmtpl-product-tabs-carousel-elementor-widget-control', $widget_control);
    }
    protected function _register_controls()
    {
        parent::_register_controls();
        $this->define_widget_control();
        $this->start_injection( [
            'at' => 'before',
            'of' => 'section_heading',
        ] );
        $this->start_controls_section('_section_layout', ['label' => __('Layout', 'fami-templatekits')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
        }
        if (isset($this->default_control['item_style'])) {
            $this->add_control(
                'item_style',
                $this->default_control['item_style']
            );
        }
        if (isset($this->default_control['product_img'])){
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                $this->default_control['product_img']
            );
        }
        if (isset($this->default_control['hover_img'])) {
            $this->add_control(
                'hover_img',
                $this->default_control['hover_img']
            );
        }
        if (isset($this->default_control['show_category'])) {
            $this->add_control(
                'show_category',
                $this->default_control['show_category']
            );
        }
        if (isset($this->default_control['show_rating'])) {
            $this->add_control(
                'show_rating',
                $this->default_control['show_rating']
            );
        }
        $this->end_controls_section();
        $this->end_injection();
        $this->start_controls_section(
            '_section_heading',
            [
                'label' => __('Button', 'fami-templatekits'),
            ]
        );

        if (isset($this->default_control['button_text'])) {
            $this->add_control(
                'button_heading',
                [
                    'label' => __( 'Button', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'button_text',
                $this->default_control['button_text']
            );
        }

        if (isset($this->default_control['link'])) {
            $this->add_control(
                'link',
                $this->default_control['link']
            );
        }
        if (isset($this->default_control['button_icon'])) {
            $this->add_control(
                'button_icon',
                $this->default_control['button_icon']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_product_query',
            [
                'label' => __('Tabs', 'fami-templatekits'),
            ]
        );
        $repeater = new Repeater();
        //tab_title
        if (isset($this->default_control['tab_title'])) {
            $repeater->add_control(
                'tab_title',
                $this->default_control['tab_title']
            );
        }
        if (isset($this->default_control['tab_icon_type'])) {
            $repeater->add_control(
                'tab_icon_type',
                $this->default_control['tab_icon_type']
            );
        }
        if (isset($this->default_control['tab_img_icon'])) {
            $repeater->add_control(
                'tab_img_icon',
                $this->default_control['tab_img_icon']
            );
        }
        if (isset($this->default_control['tab_icon'])) {
            $repeater->add_control(
                'tab_icon',
                $this->default_control['tab_icon']
            );
        }


        if (isset($this->default_control['source'])) {
            $repeater->add_control(
                'source',
                $this->default_control['source']
            );
        }
        if (isset($this->default_control['category_id'])) {
            $repeater->add_control(
                'category_id',
                $this->default_control['category_id']
            );
        }
        if (isset($this->default_control['product_ids'])) {
            $repeater->add_control(
                'product_ids',
                $this->default_control['product_ids']
            );
        }
        //max_items
        if (isset($this->default_control['max_items'])) {
            $repeater->add_control(
                'max_items',
                $this->default_control['max_items']
            );
        }
        if (isset($this->default_control['orderby'])) {
            $repeater->add_control(
                'orderby',
                $this->default_control['orderby']
            );
        }
        if (isset($this->default_control['order'])) {
            $repeater->add_control(
                'order',
                $this->default_control['order']
            );
        }
        $this->add_control(
            'tabs',
            [
                'label' => __( 'Products Tabs', 'fami-templatekits' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => __( 'Featured Products', 'fami-templatekits' ),
                        'source' => 'featured'
                    ],
                    [
                        'tab_title' => __( 'Best Selling Products', 'fami-templatekits' ),
                        'source' => 'bestselling'
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Button', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        if (isset($this->default_control['btn_width'])) {
            $this->add_responsive_control(
                'btn_width',
                $this->default_control['btn_width']
            );
        }
        if (isset($this->default_control['btn_height'])) {
            $this->add_responsive_control(
                'btn_height',
                $this->default_control['btn_height']
            );
        }
        $this->add_control(
            'btn_tabs_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->start_controls_tabs( 'btn_tabs' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['btn_color'])) {
            $this->add_control(
                'btn_color',
                $this->default_control['btn_color']
            );
        }
        if (isset($this->default_control['btn_bg_color'])) {
            $this->add_control(
                'btn_bg_color',
                $this->default_control['btn_bg_color']
            );
        }
        if (isset($this->default_control['btn_border'])) {
            $this->add_control(
                'btn_border_heading',
                [
                    'label' => __('Button Border', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['btn_border']
            );
        }
        if (isset($this->default_control['btn_border_radius'])) {
            $this->add_responsive_control(
                'btn_border_radius',
                $this->default_control['btn_border_radius']
            );
        }

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );

        if (isset($this->default_control['btn_color_hover'])) {
            $this->add_control(
                'btn_color_hover',
                $this->default_control['btn_color_hover']
            );
        }
        if (isset($this->default_control['btn_bg_color_hover'])) {
            $this->add_control(
                'btn_bg_color_hover',
                $this->default_control['btn_bg_color_hover']
            );
        }
        if (isset($this->default_control['btn_border_color_hover'])) {
            $this->add_control(
                'btn_border_color_hover',
                $this->default_control['btn_border_color_hover']
            );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'btn_tabs_hr_1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'btn_icon_options',
            [
                'label' => __( 'Icon Options', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['button_icon[value]!' => '']
            ]
        );
        if (isset($this->default_control['btn_icon_space'])){
            $this->add_responsive_control(
                'btn_icon_space',
                $this->default_control['btn_icon_space']
            );
        }
        if (isset($this->default_control['btn_icon_size'])){
            $this->add_responsive_control(
                'btn_icon_size',
                $this->default_control['btn_icon_size']
            );
        }
        if (isset($this->default_control['btn_icon_position'])){
            $this->add_responsive_control(
                'btn_icon_position',
                $this->default_control['btn_icon_position']
            );
        }

            $this->add_control(
                'btn_text_options',
            [
                'label' => __( 'Button Text', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['button_text!' => '']
            ]
            );

        if (isset($this->default_control['btn_text_typography'])){
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['btn_text_typography']
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_tab_style',
            [
                'label' => __( 'Tabs Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['tab_title_width'])) {
            $this->add_responsive_control(
                'tab_title_width',
                $this->default_control['tab_title_width']
            );
        }
        if (isset($this->default_control['tab_title_typography'])){
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['tab_title_typography']
            );
        }
        if (isset($this->default_control['tab_title_alignment'])) {
            $this->add_responsive_control(
                'tab_title_alignment',
                $this->default_control['tab_title_alignment']
            );
        }
        if (isset($this->default_control['tab_title_color'],$this->default_control['tab_title_hover_color']) ||
            isset($this->default_control['tab_icon_color'],$this->default_control['tab_icon_color_hover']) ||
            isset($this->default_control['tab_title_bg_color'],$this->default_control['tab_title_bg_hover_color']))
        {
            $this->start_controls_tabs( 'tab_title_tabs' );
            $this->start_controls_tab(
                'tab_title_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['tab_title_color'])) {
            $this->add_control(
                'tab_title_color',
                $this->default_control['tab_title_color']
            );
            }
            if (isset($this->default_control['tab_icon_color'])) {
                $this->add_control(
                    'tab_icon_color',
                    $this->default_control['tab_icon_color']
                );
            }
            if (isset($this->default_control['tab_title_bg_color'])) {
                $this->add_control(
                    'tab_title_bg_color',
                    $this->default_control['tab_title_bg_color']
                );
            }
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_title_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['tab_title_hover_color'])) {
            $this->add_control(
                'tab_title_hover_color',
                $this->default_control['tab_title_hover_color']
            );
            }
            if (isset($this->default_control['tab_icon_color_hover'])) {
                $this->add_control(
                    'tab_icon_color_hover',
                    $this->default_control['tab_icon_color_hover']
                );
            }
            if (isset($this->default_control['tab_title_bg_hover_color'])) {
                $this->add_control(
                    'tab_title_bg_hover_color',
                    $this->default_control['tab_title_bg_hover_color']
                );
            }
            $this->end_controls_tab();
            $this->end_controls_tabs();
        }

        if (isset($this->default_control['tab_title_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['tab_title_border']
            );
        }
        if (isset($this->default_control['list_item_border_radius'])) {
            $this->add_responsive_control(
                'list_item_border_radius',
                $this->default_control['list_item_border_radius']
            );
        }
        if (isset($this->default_control['tab_title_border_hover_color'])) {
            $this->add_control(
                'tab_title_border_hover_color',
                $this->default_control['tab_title_border_hover_color']
            );
        }
        if (isset($this->default_control['tab_icon_position'])) {
            $this->add_control(
                'tab_icon_position',
                $this->default_control['tab_icon_position']
            );
        }
        if (isset($this->default_control['tab_icon_space'])) {
            $this->add_responsive_control(
                'tab_icon_space',
                $this->default_control['tab_icon_space']
            );
        }
        if (isset($this->default_control['tab_icon_size'])) {
            $this->add_control(
                'tab_icon_size',
                $this->default_control['tab_icon_size']
            );
        }
        if (isset($this->default_control['tab_title_padding'])) {
            $this->add_responsive_control(
                'tab_title_padding',
                $this->default_control['tab_title_padding']
            );
        }
        if (isset($this->default_control['tab_title_margin'])) {
            $this->add_responsive_control(
                'tab_title_margin',
                $this->default_control['tab_title_margin']
            );
        }
        if (isset($this->default_control['tab_bottom_space'])) {
            $this->add_responsive_control(
                'tab_bottom_space',
                $this->default_control['tab_bottom_space']
            );
        }
        $this->end_controls_section();
        if (isset($this->default_control['arrow_size'], $this->default_control['arrow_color'])){
            $this->start_controls_section(
                '_section_arrow_style',
                [
                    'label' => __( 'Arrow', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'arrow_size',
                $this->default_control['arrow_size']
            );
            $this->add_control(
                'arrow_color',
                $this->default_control['arrow_color']
            );
            $this->end_controls_section();
        }
    }
    protected function render()

    {
        echo $this->fmtpl_render();
    }
    protected function fmtpl_render() {
        $settings = $this->get_settings_for_display();
        if (isset($settings['product_img_size']) && !empty($settings['product_img_size'])){
            wc_setup_loop(
                array(
                    'product_loop_image_size' => $settings['product_img_size']
                )
            );
        }
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        $settings['button_icon_str'] = '';
        if ((isset($settings['button_icon']) && ($settings['button_icon']['value']))) {
            ob_start();
            Icons_Manager::render_icon( $settings[ 'button_icon' ]);
            $settings['button_icon_str'] = ob_get_clean();
        }
        $settings['link_attr_str'] = '';
        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (!empty($settings['button_icon_str']))) {
            if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'link', $settings['link'] );
                $settings['link_attr_str'] =  $this->get_render_attribute_string( 'link' );
            } else {
                $settings['link_attr_str'] = 'href="#." title=""';
            }
        }
        if (isset($settings['tabs']) && !empty($settings['tabs'])) {
            foreach ($settings['tabs'] as $tab_key => $tab){
                $tab_icon_str = '';
                if (isset($tab['tab_icon']) && $tab['tab_icon']) {
                    ob_start();
                    Icons_Manager::render_icon( $tab[ 'tab_icon' ]);
                    $tab_icon_str = ob_get_clean();
                } elseif (isset($tab['tab_img_icon']) && !empty($tab['tab_img_icon']['url'])) {
                    $this->add_render_attribute('image', 'src', $tab['tab_img_icon']['url']);
                    $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($tab['tab_img_icon']));
                    $this->add_render_attribute('image', 'title', Control_Media::get_image_title($tab['tab_img_icon']));
                    $tab_icon_str = Group_Control_Image_Size::get_attachment_image_html($tab, 'thumbnail', 'tab_img_icon');
                }
                $settings['tabs'][$tab_key]['tab_icon_str'] = $tab_icon_str;
            }
        }
        $show_arrows = (isset($settings['show_arrows']) && $settings['show_arrows'] == 'yes') ? true: false;
        $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;
        if ($show_arrows && (isset($settings['prev_icon']) || isset($settings['next_icon']))){
            if (isset($settings['prev_icon'])){
                ob_start();
                Icons_Manager::render_icon( $settings[ 'prev_icon' ]);
                $settings['prev_icon_str'] = ob_get_clean();
            }
            if (isset($settings['next_icon'])) {
                ob_start();
                Icons_Manager::render_icon( $settings[ 'next_icon' ]);
                $settings['next_icon_str'] = ob_get_clean();
            }
        }
        $html = apply_filters('fmtpl-product-tabs-carousel-widget-control_html','',$settings);
        if (!empty($html)){
            return $html;
        }
        $tab_on_heading = false;
        if ($layout == 'fmtpl_style01'){
            $tab_on_heading = true;
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-products fmtpl-product-tabs-carousel fmtpl-product-tabs-carousel-layout-'.$layout.' woocommerce'.($tab_on_heading ? ' tab-heading':'').'">';
        $content_html = '';
        if (isset($settings['title_text']) && !empty($settings['title_text'])) {
            $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-widget-title fmtpl-product-tabs-carousel-title fmtpl-carousel-box-title">'.$title.'</div>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
        }
        if (isset($settings['description']) && !empty($settings['description'])){
            $content_html .= '<div class="fmtpl-products-desc">'.$settings['description'].'</div>';
        }

        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (!empty($settings['button_icon_str']))) {
            $icon_str = '';
            if (!empty($settings['button_icon_str'])) {
                $icon_str = '<span class="fmtpl-btn-icon">'.$settings['button_icon_str'].'</span>';
            }
            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
            $content_html .= '<a class="fmtpl-button-default fmtpl-products-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
        }
        if ($content_html != '' && !$tab_on_heading) {
            $html .= '<div class="fmtpl-products-content fmtpl-carousel-box-heading">'.$content_html.'</div>';
        }
        $tab_html = '';
        if (isset($settings['tabs']) && !empty($settings['tabs'])) {
            $tab_nav_class = isset($settings['tab_icon_position'])? ' '.$settings['tab_icon_position'] : ' left';
            $settings['list_style'] = 'carousel';
            $tab_html_nav = '';
            if ($content_html != '' && $tab_on_heading) {
                $tab_html_nav .= '<div class="fmtpl-products-content fmtpl-carousel-box-heading">'.$content_html.'</div>';
            }
            $tab_html_nav .= sprintf('<ul class="nav nav-tabs%s" role="tablist">',$tab_nav_class);

            //$tab_html_nav = sprintf('<ul class="nav nav-tabs%s" role="tablist">',$tab_nav_class);
            $tab_active = true;
            $tab_content = '<div class="tab-content">';
            $navi_html = '';
            unset($settings['show_arrows']);
            if ( $show_arrows) {
                $sw_btn_class ='';
                if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                    $sw_btn_class .= ' hidden_on_mobile';
                }
                $navi_html .= '<div class="fmtpl-carousel-navigation-wrapper">';
                $navi_html .= '<div class="elementor-swiper-button elementor-swiper-button-prev'.$sw_btn_class.'">';
                if (isset($settings['prev_icon_str'])){
                    $navi_html .= $settings['prev_icon_str'];
                } else {
                    $navi_html .= '<i class="eicon-chevron-left" aria-hidden="true"></i>';
                }
                if ($show_arrows_text && isset($settings['prev_text']) && !empty($settings['prev_text'])){
                    $navi_html .='<span>'.$settings['prev_text'].'</span>';
                }
                $navi_html .='</div>';//close elementor-swiper-button-prev

                $navi_html .= '<div class="elementor-swiper-button elementor-swiper-button-next'.$sw_btn_class.'">';
                if ($show_arrows_text && isset($settings['next_text']) && !empty($settings['next_text'])){
                    $navi_html .='<span>'.$settings['next_text'].'</span>';
                }
                if (isset($settings['next_icon_str'])){
                    $navi_html .= $settings['next_icon_str'];
                } else {
                    $navi_html .= '<i class="eicon-chevron-right" aria-hidden="true"></i>';
                }
                $navi_html .='</div>';//close elementor-swiper-button-next
                $navi_html .='</div>';// close fmtpl-carousel-navigation-wrapper
            }
            foreach ($settings['tabs'] as $tab){
                $tab_icon_str = '';
                if ($tab['tab_icon_str'] != '') {
                    $tab_icon_str = '<span class="fmtpl-tab-icon">'.$tab['tab_icon_str'].'</span>';
                }
                $nav_tab_link_class = $tab_active ? ' active':'';
                $tab_html_nav .= sprintf('<li class="nav-item"><a class="fmtpl-tab-link nav-link%1$s" id="%2$s-tab" data-toggle="tab" href="#%2$s" role="tab" aria-controls="%2$s" aria-selected="true">%3$s<span>%4$s</span></a></li>',
                    $nav_tab_link_class,
                    'fmtpl_tab_'.$tab['_id'],
                    $tab_icon_str,
                    $tab['tab_title']
                );
                $fmtpl_query = fmtpl_getProducts($tab);
                $tab_content_html = '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper">';
                $tab_content_html .= '<div class="swiper-container">';
                $tab_content_html .= fmtpl_getProducts_Html($settings,$fmtpl_query);
                $tab_content_html .= '</div>';
                if ($show_arrows){
                    $tab_content_html .= $navi_html;
                }
                $tab_content_html .= '</div></div>';
                $tab_content .= sprintf('<div class="tab-pane fade%1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">%3$s</div>',
                    $tab_active ? ' show active':'',
                    'fmtpl_tab_'.$tab['_id'],
                    $tab_content_html
                );
                $tab_active = false;
            }
            $tab_content .= '</div><!-- close tab-content--> ';//
            $tab_html_nav .= '</ul>';
            $tab_html .= sprintf('<div class="fmtpl-tabs-wrapper"><div class="fmtpl-tabs-nav-wrap">%s</div>%s</div><!-- close fmtpl-tabs-wrapper--> ',$tab_html_nav,$tab_content);
        }
        $html .= $tab_html;
        $html .= '</div><!--close fmtpl-product-tabs-carousel-->';
        return $html;
    }

    protected function add_repeater_controls(Repeater $repeater)
    {
        // TODO: Implement add_repeater_controls() method.
    }

    protected function get_repeater_defaults()
    {
        // TODO: Implement get_repeater_defaults() method.
    }

    protected function print_slide(array $slide, array $settings, $element_key)
    {
        // TODO: Implement print_slide() method.
    }
}
