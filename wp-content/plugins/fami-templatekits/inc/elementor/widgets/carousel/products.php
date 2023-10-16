<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Products')){
    class Fmtpl_Carousel_Products extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-products';
        }

        public function get_title() {
            return __( 'Products Carousel', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'fmtpl-carousel-product fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'woocommerce', 'shop', 'store', 'product', 'carousel' ];
        }

        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $this->add_style_depends('fmtpl-product-carousel');
        }

        public function define_widget_control() {
            $products_item_style = apply_filters('fmtpl_products_item_style',['default' => __('Default Theme Style', 'fami-templatekits')]);
            $widget_control = [
                'layout' => [
                    'label' => __('Layout', 'fami-templatekits'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __('Default', 'fami-templatekits'),
                        'fmtpl_style01' => __('Style 01', 'fami-templatekits'),
                        'fmtpl_style02' => __('Style 02', 'fami-templatekits'),
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
                ],
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
                'link' => [
                    'label' => __( 'Link', 'fami-templatekits' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'fami-templatekits' ),
                    'show_external' => true,
                    'dynamic' => [
                        'active' => true,
                    ]
                ],
                'button_icon' => [
                    'label' => __( 'Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
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
                'btn_position' => [
                    'label' => __('Button Position', 'fami-templatekits'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'before',
                    'options' => [
                        'before' => __('Before Products', 'fami-templatekits'),
                        'after' => __('After Products', 'fami-templatekits'),
                    ],
                    'style_transfer' => true,
                ],
                'btn_align' => [
                    'label' => __('Alignment', 'fami-templatekits'),
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
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button-wrapper' => 'text-align: {{VALUE}}',
                    ],
                ],
                'btn_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'width: {{SIZE}}{{UNIT}};',
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
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],

                'btn_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'color: {{VALUE}};fill: {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
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
                            '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'btn_bg_color_hover' =>
                    [
                        'label' => __( 'Background Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'btn_border' => [
                    'name' => 'btn_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-products-button',
                ],

                'btn_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],
                'btn_border_color_hover' => [
                    'label' => __( 'Border Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'border-color: {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => ['button_icon[value]!' => '']
                ],
                'btn_icon_size' => [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 6,
                            'max' => 300,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => ['button_icon[value]!' => '']
                ],
                'btn_icon_position' => [
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

                'btn_text_options' => [
                    'label' => __( 'Button Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => ['button_icon[value]!' => '']
                ],
                'btn_text_typography' => [
                    'name' => 'btn_text_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-btn-text',
                ],
                'pagination_style' =>  [
                    'label' => __( 'Dot style', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __( 'Default', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'fmtpl-dot-style-',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ],
            ];
            $this->default_control = apply_filters('fmtpl-carousel-products-elementor-widget-control', $widget_control);
        }
        protected function _register_controls() {
            parent::_register_controls();
            $this->define_widget_control();
            $this->start_injection( [
                'at' => 'before',
                'of' => 'section_heading',
            ] );
            $this->start_controls_section(
                'section_layout',
                [
                    'label' => __( 'Layout', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
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

            $this->start_injection( [
                'at' => 'before',
                'of' => 'section_additional_options',
            ] );
            $this->start_controls_section(
                '_section_product_query',
                [
                    'label' => __('Products Query', 'fami-templatekits'),
                ]
            );
            if (isset($this->default_control['source'])) {
                $this->add_control(
                    'source',
                    $this->default_control['source']
                );
            }
            if (isset($this->default_control['category_id'])) {
                $this->add_control(
                    'category_id',
                    $this->default_control['category_id']
                );
            }
            if (isset($this->default_control['product_ids'])) {
                $this->add_control(
                    'product_ids',
                    $this->default_control['product_ids']
                );
            }
            //max_items
            if (isset($this->default_control['max_items'])) {
                $this->add_control(
                    'max_items',
                    $this->default_control['max_items']
                );
            }
            if (isset($this->default_control['orderby'])) {
                $this->add_control(
                    'orderby',
                    $this->default_control['orderby']
                );
            }
            if (isset($this->default_control['order'])) {
                $this->add_control(
                    'order',
                    $this->default_control['order']
                );
            }
            $this->end_controls_section();
            $this->end_injection();
            if (isset($this->default_control['button_text'])) {
                if (isset($this->default_control['button_text']['condition'])){
                    $this->start_controls_section(
                        '_section_button',
                        [
                            'label' => __( 'Action Button', 'fami-templatekits' ),
                            'condition' => $this->default_control['button_text']['condition']
                        ]
                    );
                } else {
                    $this->start_controls_section(
                        '_section_button',
                        [
                            'label' => __( 'Action Button', 'fami-templatekits' ),
                        ]
                    );
                }

                $this->add_control(
                    'button_text',
                    $this->default_control['button_text']
                );
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
            }

            /*****************************/
            $this->start_controls_section(
                '_section_button_style',
                [
                    'label' => __( 'Action Button', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => ['button_text!'=> '']
                ]
            );

            $this->add_control(
                '_btn_heading',
                [
                    'label' => __( 'Button Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['btn_position'])) {
                $this->add_responsive_control(
                    'btn_position',
                    $this->default_control['btn_position']
                );
            }
            if (isset($this->default_control['btn_align'])) {
                $this->add_control(
                    'btn_align',
                    $this->default_control['btn_align']
                );
            }
            if (isset($this->default_control['btn_margin'])) {
                $this->add_responsive_control(
                    'btn_margin',
                    $this->default_control['btn_margin']
                );
            }
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

            if (isset($this->default_control['btn_icon_space'])) {
                $this->add_responsive_control(
                    'btn_icon_space',
                    $this->default_control['btn_icon_space']
                );
            }

            if (isset($this->default_control['btn_icon_size'])) {
                $this->add_responsive_control(
                    'btn_icon_size',
                    $this->default_control['btn_icon_size']
                );
            }
            if (isset($this->default_control['btn_icon_position'])) {
                $this->add_control(
                    'btn_icon_position',
                    $this->default_control['btn_icon_position']
                );
            }
            if (isset($this->default_control['btn_text_options'])) {
                $this->add_control(
                    'btn_text_options',
                    $this->default_control['btn_text_options']
                );
            }
            if (isset($this->default_control['btn_text_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['btn_text_typography']
                );
            }
            $this->end_controls_section();
            if (isset($this->default_control['pagination_style'])) {
                $this->start_injection( [
                    'at' => 'after',
                    'of' => 'pagination',
                ] );
                $this->add_control(
                    'pagination_style',
                    $this->default_control['pagination_style']
                );
                $this->end_injection();
            }
            if (isset($this->default_control['slides_per_view'])){
                $this->update_control('slides_per_view',
                    $this->default_control['slides_per_view']
                );
            }
            if (isset($this->default_control['slides_to_scroll'])){
                $this->update_control('slides_to_scroll',
                    $this->default_control['slides_to_scroll']
                );
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
            $settings['widget_name'] = $this->get_name();
            if (isset($settings['button_icon']) && $settings['button_icon']) {
                $icon_str = '';
                ob_start();
                Icons_Manager::render_icon( $settings[ 'button_icon' ]);
                $icon_str = ob_get_clean();
                if ($icon_str != '') {
                    $icon_str = '<span class="fmtpl-btn-icon">'.$icon_str.'</span>';
                }
                if (!empty($icon_str)){
                    $settings['button_icon_str'] = $icon_str;
                }
            }
            $show_arrows = (isset($settings['show_arrows']) && $settings['show_arrows'] == 'yes') ? true: false;
            $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;
            if ($show_arrows && (isset($settings['prev_icon']) || isset($settings['next_icon']))){
                if (isset($settings['prev_icon'])){
                    ob_start();
                    Icons_Manager::render_icon($settings[ 'prev_icon']);
                    $settings['prev_icon_str'] = ob_get_clean();
                }
                if (isset($settings['next_icon'])) {
                    ob_start();
                    Icons_Manager::render_icon( $settings[ 'next_icon']);
                    $settings['next_icon_str'] = ob_get_clean();
                }
            }
            $fmtpl_query = fmtpl_getProducts($settings);
            $html = apply_filters('fmtpl-carousel-products-widget-html','',$settings, $fmtpl_query);
            if (!empty($html)){
                return $html;
            }
            if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'link', $settings['link'] );
                $link_attr_str =  $this->get_render_attribute_string( 'link' );
            } else {
                $link_attr_str = 'href="#." title=""';
            }
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
            $settings['link_attr_str'] = $link_attr_str;
            $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
            $arrow_on_heading = false;
            if ($layout == 'fmtpl_style01' || $layout == 'fmtpl_style02'){
                $arrow_on_heading = true;
            }

            $html = '<div class="fmtpl-elementor-widget fmtpl-products fmtpl-products-layout-'.$layout.' carousel woocommerce'.($arrow_on_heading ? ' arrow-heading':'').'">';
            $content_html = '';
            if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
            }
            if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
                $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
            }
            if (isset($settings['description']) && !empty($settings['description'])){
                $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
            }
            $button_html = '';
            if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && ($settings['button_icon']['value']))) {
                $icon_str = isset($settings['button_icon_str'])?$settings['button_icon_str']:'';
                $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
                //$content_html
                $button_html .= '<div class="fmtpl-products-button-wrapper"><a class="fmtpl-button-default fmtpl-products-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a></div>';
            }
            if ($content_html != '' && !$arrow_on_heading) {
                $html .= '<div class="fmtpl-carousel-box-heading">'.$content_html.'</div>';
            }
            if (isset($settings['btn_position']) && $settings['btn_position'] == 'before' && !empty($button_html)) {
                $html .= $button_html;
            }
            //get products
            $settings['list_style'] = 'carousel';
            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper">';
            if ($arrow_on_heading) {
                $html .= '<div class="fmtpl-carousel-box-heading">';
                if ($content_html != '' && $arrow_on_heading) {
                   $html .= $content_html;
                }
                if (!empty($navi_html)){
                    $html .= $navi_html;
                }
                $html .= '</div>';//close fmtpl-carousel-box-heading
            }
            $html .= '<div class="swiper-container">';
            $html .= fmtpl_getProducts_Html($settings,$fmtpl_query);
            $html .='</div>';
            if (!$arrow_on_heading && !empty($navi_html)){
                $html .= $navi_html;
            }
            if (isset($settings['btn_position']) && $settings['btn_position'] == 'after' && !empty($button_html)) {
                $html .= $button_html;
            }
            $html .='</div></div></div>';
            return $html;
        }

        protected function add_repeater_controls(Repeater $repeater)
        {
        }

        protected function get_repeater_defaults()
        {
        }

        protected function print_slide(array $slide, array $settings, $element_key)
        {
        }
    }
}

