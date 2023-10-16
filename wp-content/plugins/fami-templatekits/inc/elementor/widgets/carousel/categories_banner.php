<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once FAMI_TPL_DIR. '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Categories_Banner')){
    class Fmtpl_Carousel_Categories_Banner extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-categories-banner';
        }

        public function get_title() {
            return __( 'Categories Carousel Banner', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'fmtpl-product-carousel-banner fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'image', 'banner', 'carousel', 'category', 'categories' ];
        }
        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $this->add_style_depends('fmtpl-categories-banner'); 
        }
        public function define_widget_control() {
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
                'height' => [
                    'label' => __( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1000,
                            'step' => 5,
                        ],

                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .elementor-fmtpl-category-carousel-banner .fmtpl-elementor_image' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner.inner_box_content .elementor-fmtpl-category-carousel-banner' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                //slide item
                'image' => [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ],
                'category_id' => [
                    'label' => __('Category', 'fami-templatekits'),
                    'label_block' => true,
                    'type' => Fmtpl_Select2::TYPE,
                    'multiple' => false,
                    'placeholder' => __('Search Category', 'fami-templatekits'),
                    'data_options' => [
                        'taxonomy_type' => 'product_cat',
                        'action' => 'fmtpl_taxonomy_list_query'
                    ],
                ],
                'item_btn_text' => [
                    'label' => __( 'Button Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type button text here', 'fami-templatekits' ),
                    'label_block' => true,
                ],
                'item_btn_icon' => [
                    'label' => __( 'Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'item_btn_text!' => ''
                    ]
                ],
                'show_product_count' => [
                    'label' => __( 'Show Product Count', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ],

                'custom_title' => [
                    'label' => __( 'Custom Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ],
                'custom_title_text' => [
                    'label' => __( 'Custom Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( '', 'fami-templatekits' ),
                    'condition' => ['custom_title' => 'yes'],
                ],
                'product_count_title' => [
                    'label' => __( 'Product Count Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Product Count', 'fami-templatekits' ),
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'count_number_space' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-products-count' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'count_number_position' => [
                    'label' => __('Number Position', 'fami-templatekits'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'fami-templatekits'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __('Right', 'fami-templatekits'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'count_number_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-products-count' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'count_number_color_hover' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-products-count:hover' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'count_number_typography' => [
                    'name' => 'count_number_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-products-count',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'condition' => ['show_product_count' => 'yes'],
                ],
                'image_size' => [
                    'name' => 'image_size',
                    'default' => 'full',
                ],
                //slide item style
                'item_title_bottom_space' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'inner_box_content' => [
                    'label' => __('Content Box Style', 'fami-templatekits'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'outside' => __('Outside', 'fami-templatekits'),
                        'inside' => __('Inside', 'fami-templatekits'),
                    ],
                    'style_transfer' => true,
                ],
                'box_width' => [
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .inner_box_content .fmtpl-elementor_content' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => ['inner_box_content' => 'inside'],
                ],
                'box_height' => [
                    'label' => __( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .inner_box_content .fmtpl-elementor_content' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => ['inner_box_content' => 'inside'],
                ],
                'horizontal_position' => [
                    'label' => __('Horizontal', 'fami-templatekits'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'fami-templatekits'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'fami-templatekits'),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'fami-templatekits'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'selectors_dictionary' => [
                        'left' => 'flex-start',
                        'center' => 'center',
                        'right' => 'flex-end',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .inner_box_content .elementor-fmtpl-category-carousel-banner' => 'justify-content: {{VALUE}}',
                    ],
                    'condition' => ['inner_box_content' => 'inside'],
                ],
                'vertical_position' => [
                    'label' => __('Vertical', 'fami-templatekits'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'top' => [
                            'title' => __('Top', 'fami-templatekits'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'middle' => [
                            'title' => __('Middle', 'fami-templatekits'),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __('Bottom', 'fami-templatekits'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'selectors_dictionary' => [
                        'top' => 'flex-start',
                        'middle' => 'center',
                        'bottom' => 'flex-end',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .inner_box_content .elementor-fmtpl-category-carousel-banner' => 'align-items: {{VALUE}}',
                    ],
                    'condition' => ['inner_box_content' => 'inside'],
                ],
                'item_content_box_bg' => [
                    'label' => __( 'Content Background', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_content_box_padding' => [
                    'label' => __( 'Padding', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ],
                'item_content_box_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ],
                'item_title_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title, {{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title a' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_title_color_hover' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title:hover, {{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title:hover a' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title, {{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title a',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],
                'item_highlight_title_typography' => [
                    'label' => __( 'Highlight Typography', 'fami-templatekits' ),
                    'name' => 'item_highlight_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-item-title .highlight',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],

                'image_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px','%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'image_bottom_space' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-categories-banner .fmtpl-elementor_image ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'pagination_style' => [
                    'label' => __( 'Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default'  => __( 'Default', 'fami-templatekits' )
                    ],
                    'default' => 'default',
                    'prefix_class' => 'fmtpl-dot-style-',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ],
                'item_btn_width' => [
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
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_btn_height' => [
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
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_btn_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-carousel-item-btn svg' => 'fill: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_btn_color_hover' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_btn_bg_color' =>
                    [
                        'label' => __( 'Background Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'item_btn_bg_color_hover' =>
                    [
                        'label' => __( 'Background Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'item_btn_border' => [
                    'name' => 'item_btn_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-item-btn',
                ],
                'item_btn_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],
                'item_btn_border_color_hover' => [
                    'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'item_btn_border_border!' => '',
                    ],
                ],
                'item_btn_icon_space' => [
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
                        '{{WRAPPER}} .fmtpl-carousel-item-btn.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-carousel-item-btn.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_btn_icon_size' =>  [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 6,
                            'max' => 300,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_btn_icon_position' =>  [
                    'label' => __( 'Icon Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'right',
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
                ],
                'item_btn_text_typography' => [
                    'name' => 'item_btn_text_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-item-btn .fmtpl-btn-text',
                ],
            ];
            $this->default_control = apply_filters('fmtpl-carousel-categories-banner-elementor-widget-control', $widget_control);
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
            $this->end_controls_section();
            $this->end_injection();

            if (isset($this->default_control['height'])){
                $this->start_injection( [
                    'at' => 'before',
                    'of' => '_section_heading_style',
                ] );
                $this->start_controls_section(
                    '_section_layout_style',
                    [
                        'label' => __( 'Layout', 'fami-templatekits' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );

                $this->add_responsive_control(
                    'height',
                    $this->default_control['height']
                );
                $this->end_controls_section();
                $this->end_injection();
            }
            $this->start_injection( [
                'at' => 'after',
                'of' => 'layout',
            ] );
            if (isset($this->default_control['image_size'])) {
                $this->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    $this->default_control['image_size']
                );
            }
            if (isset($this->default_control['show_product_count'])) {
                $this->add_control(
                    'show_product_count',
                    $this->default_control['show_product_count']
                );
            }
            if (isset($this->default_control['product_count_title'])) {
                $this->add_control(
                    'product_count_title',
                    $this->default_control['product_count_title']
                );
            }
            $this->end_injection();

            $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __( 'Category Content', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_control(
                'item_heading_1',
                [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['image_bottom_space'])) {
                $this->add_responsive_control(
                    'image_bottom_space',
                    $this->default_control['image_bottom_space']
                );
            }
            if (isset($this->default_control['image_border_radius'])) {
                $this->add_responsive_control(
                    'image_border_radius',
                    $this->default_control['image_border_radius']
                );
            }
            $this->add_control(
                'item_heading_2_0',
                [
                    'label' => __( 'Content Box', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'none',
                ]
            );
            if (isset($this->default_control['inner_box_content'])) {
                $this->add_control(
                    'inner_box_content',
                    $this->default_control['inner_box_content']
                );
            }
            if (isset($this->default_control['horizontal_position'])) {
                $this->add_responsive_control(
                    'horizontal_position',
                    $this->default_control['horizontal_position']
                );
            }
            if (isset($this->default_control['vertical_position'])) {
                $this->add_responsive_control(
                    'vertical_position',
                    $this->default_control['vertical_position']
                );
            }
            if (isset($this->default_control['box_width'])) {
                $this->add_responsive_control(
                    'box_width',
                    $this->default_control['box_width']
                );
            }
            if (isset($this->default_control['box_height'])) {
                $this->add_responsive_control(
                    'box_height',
                    $this->default_control['box_height']
                );
            }
            if (isset($this->default_control['item_content_box_bg'])) {
                $this->add_control(
                    'item_content_box_bg',
                    $this->default_control['item_content_box_bg']
                );
            }
            if (isset($this->default_control['item_content_box_padding'])) {
                $this->add_responsive_control(
                    'item_content_box_padding',
                    $this->default_control['item_content_box_padding']
                );
            }
            if (isset($this->default_control['item_content_box_margin'])) {
                $this->add_responsive_control(
                    'item_content_box_margin',
                    $this->default_control['item_content_box_margin']
                );
            }
            $this->add_control(
                'item_heading_2',
                [
                    'label' => __( 'Item Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'none',
                ]
            );
            if (isset($this->default_control['item_title_bottom_space'])) {
                $this->add_responsive_control(
                    'item_title_bottom_space',
                    $this->default_control['item_title_bottom_space']
                );
            }

            $this->start_controls_tabs( 'title_tabs' );
            $this->start_controls_tab(
                'title_tab_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_title_color'])) {
                $this->add_control(
                    'item_title_color',
                    $this->default_control['item_title_color']
                );
            }
            if (isset($this->default_control['item_highlight_title_color'])) {
                $this->add_control(
                    'item_highlight_title_color',
                    $this->default_control['item_highlight_title_color']
                );
            }
            $this->end_controls_tab();

            $this->start_controls_tab(
                'title_tab_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_title_color_hover'])) {
                $this->add_control(
                    'item_title_color_hover',
                    $this->default_control['item_title_color_hover']
                );
            }
            if (isset($this->default_control['item_highlight_title_color_hover'])) {
                $this->add_control(
                    'item_highlight_title_color_hover',
                    $this->default_control['item_highlight_title_color_hover']
                );
            }

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'title_tabs_hr_2',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );

            if (isset($this->default_control['item_title_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_title_typography']
                );
            }
            if (isset($this->default_control['item_highlight_title_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_highlight_title_typography']
                );
            }
            if (isset($this->default_control['count_number_position'])) {
                $this->add_control(
                    '_item_count_heading',
                    [
                        'label' => __( 'Product Count', 'fami-templatekits' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                        'condition' => ['show_product_count' => 'yes'],
                    ]
                );


                if (isset($this->default_control['count_number_space'])) {
                    $this->add_responsive_control(
                        'count_number_space',
                        $this->default_control['count_number_space']
                    );
                }
                if (isset($this->default_control['count_number_position'])) {
                    $this->add_control(
                        'count_number_position',
                        $this->default_control['count_number_position']
                    );
                }
                if (isset($this->default_control['count_number_typography'])) {
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        $this->default_control['count_number_typography']
                    );
                }
                $this->start_controls_tabs( 'count_number_tabs' );
                $this->start_controls_tab(
                    'count_number_tab_normal',
                    [
                        'label' => __( 'Normal', 'fami-templatekits' ),

                    ]
                );
                if (isset($this->default_control['count_number_color'])) {
                    $this->add_control(
                        'count_number_color',
                        $this->default_control['count_number_color']
                    );
                }
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'count_number_tab_hover',
                    [
                        'label' => __( 'Hover', 'fami-templatekits' ),
                    ]
                );
                if (isset($this->default_control['count_number_color_hover'])) {
                    $this->add_control(
                        'count_number_color_hover',
                        $this->default_control['count_number_color_hover']
                    );
                }

                $this->end_controls_tab();
                $this->end_controls_tabs();
            }
            if (isset($this->default_control['item_btn_width'])) {
                $this->add_control(
                    '_item_btn_heading',
                    [
                        'label' => __( 'Button Style', 'fami-templatekits' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_responsive_control(
                    'item_btn_width',
                    $this->default_control['item_btn_width']
                );
            }
            if (isset($this->default_control['item_btn_height'])) {
                $this->add_responsive_control(
                    'item_btn_height',
                    $this->default_control['item_btn_height']
                );
            }

            $this->add_control(
                'item_btn_icon_options',
                [
                    'label' => __( 'Icon Options', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_btn_icon_space'])){
                $this->add_responsive_control(
                    'item_btn_icon_space',
                    $this->default_control['item_btn_icon_space']
                );
            }
            if (isset($this->default_control['item_btn_icon_size'])){
                $this->add_responsive_control(
                    'item_btn_icon_size',
                    $this->default_control['item_btn_icon_size']
                );
            }
            if (isset($this->default_control['item_btn_icon_position'])){
                $this->add_responsive_control(
                    'item_btn_icon_position',
                    $this->default_control['item_btn_icon_position']
                );
            }

            $this->add_control(
                'item_btn_text_options',
                [
                    'label' => __( 'Button Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => ['button_text!' => '']
                ]
            );
            if (isset($this->default_control['item_btn_text_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_btn_text_typography']
                );
            }
            if (isset($this->default_control['item_btn_border'])) {
                $this->add_control(
                    'item_btn_border_heading',
                    [
                        'label' => __('Button Border', 'fami-templatekits'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    $this->default_control['item_btn_border']
                );
            }
            if (isset($this->default_control['item_btn_border_color_hover'])) {
                $this->add_control(
                    'item_btn_border_color_hover',
                    $this->default_control['item_btn_border_color_hover']
                );
            }
            if (isset($this->default_control['item_btn_border_radius'])) {
                $this->add_responsive_control(
                    'item_btn_border_radius',
                    $this->default_control['item_btn_border_radius']
                );
            }
            $this->add_control(
                'item_btn_tabs_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->start_controls_tabs( 'item_btn_tabs' );
            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn_color'])) {
                $this->add_control(
                    'item_btn_color',
                    $this->default_control['item_btn_color']
                );
            }
            if (isset($this->default_control['item_btn_bg_color'])) {
                $this->add_control(
                    'item_btn_bg_color',
                    $this->default_control['item_btn_bg_color']
                );
            }
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn_color_hover'])) {
                $this->add_control(
                    'item_btn_color_hover',
                    $this->default_control['item_btn_color_hover']
                );
            }
            if (isset($this->default_control['item_btn_bg_color_hover'])) {
                $this->add_control(
                    'item_btn_bg_color_hover',
                    $this->default_control['item_btn_bg_color_hover']
                );
            }
            $this->end_controls_tab();
            $this->end_controls_tabs();
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
        protected function add_repeater_controls( Repeater $repeater ) {
            $this->define_widget_control();
            if (isset($this->default_control['image'])) {
                $repeater->add_control(
                    'image',
                    $this->default_control['image']
                );
            }
            if (isset($this->default_control['category_id'])) {
                $repeater->add_control(
                    'category_id',
                    $this->default_control['category_id']
                );
            }
            if (isset($this->default_control['custom_title'])) {
                $repeater->add_control(
                    'custom_title',
                    $this->default_control['custom_title']
                );
            }
            if (isset($this->default_control['custom_title_text'])) {
                $repeater->add_control(
                    'custom_title_text',
                    $this->default_control['custom_title_text']
                );
            }
            if (isset($this->default_control['item_btn_text'])) {
                $repeater->add_control(
                    'item_btn_text',
                    $this->default_control['item_btn_text']
                );
            }
            if (isset($this->default_control['item_btn_icon'])) {
                $repeater->add_control(
                    'item_btn_icon',
                    $this->default_control['item_btn_icon']
                );
            }
        }
        protected function get_repeater_defaults() {
            $placeholder_image_src = Utils::get_placeholder_image_src();
            return [
                [
                    'image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
            ];
        }


        protected function print_slide( array $slide, array $settings, $element_key ) {
            $slide_html = apply_filters('fmtpl-carousel-categories-banner-item-html','',$slide, $settings,$element_key);
            if (!empty($slide_html)){
                echo $slide_html;
                return;
            }
            $cat_obj = false;
            if (isset($slide['category_id']) && $slide['category_id']){
                $cat_obj = get_term( $slide['category_id'], 'product_cat' );
                if (empty($cat_obj)){
                    return;
                }
                $cat_link = get_term_link( $cat_obj->term_id, 'product_cat' );
            }
            if (empty($cat_obj)){
                return;
            }
            $this->add_render_attribute( $element_key . '-fmtpl-category-carousel-banner', [
                'class' => 'elementor-fmtpl-category-carousel-banner',
            ] );

            $this->add_render_attribute( $element_key . '-fmtpl-category-carousel-banner', [
                'class' => 'elementor-repeater-item-' . $slide['_id'],
            ] );

            if ( ! empty( $slide['image']['url'] ) ) {
                $this->add_render_attribute($element_key . '-image', [
                    'src' => $this->get_slide_image_url($slide, $settings),
                    'alt' => $cat_obj->name,
                ]);

            } ?>
        <div <?php echo $this->get_render_attribute_string( $element_key . '-fmtpl-category-carousel-banner' ); ?>>
            <?php
            $header_element = 'header_' . $slide['_id'];
            $this->add_render_attribute( $header_element, 'class', 'fmtpl-elementor_header' );
            if ( ! empty( $cat_link ) ) {
                $this->add_render_attribute( $header_element, 'href', $cat_link );
            }
            if ( ! empty( $slide['image']['url'] ) ) : ?>
                <div class="fmtpl-elementor_image">
                    <a <?php echo $this->get_render_attribute_string( $header_element ); ?>>
                        <img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
                    </a>
                </div>
            <?php endif;

            $item_title = '';

            $this->remove_render_attribute($header_element,'class');
            $this->add_render_attribute( $header_element, 'class', 'fmtpl-carousel-item-title' );
            if (isset($slide['custom_title'],$slide['custom_title_text']) &&  $slide['custom_title'] == 'yes'){
                $item_title .= '<h3 class="category-title"><a '.$this->get_render_attribute_string( $header_element ).'>'.$slide['custom_title_text'].'</a></h3>';
            } else {
                $item_title .= '<h3 class="category-title"><a '.$this->get_render_attribute_string( $header_element ).'>'.$cat_obj->name.'</a></h3>';
            }
            if ((isset($slide['item_btn_text']) && !empty($slide['item_btn_text'])) || (isset($slide['item_btn_icon']) && !empty($slide['item_btn_icon']['value']))) {
                $link_attr_str = 'href="'.$cat_link.'" title="'.$slide['item_btn_text'].'"';
                $icon_str = '';
                if (isset($slide['item_btn_icon']) && $slide['item_btn_icon']) {
                    ob_start();
                    Icons_Manager::render_icon($slide['item_btn_icon']);
                    $icon_str = ob_get_clean();
                    if ($icon_str != '') {
                        $icon_str = '<span class="fmtpl-btn-icon">' . $icon_str . '</span>';
                    }
                }
                $btn_class = isset($settings['item_btn_icon_position'])? $settings['item_btn_icon_position'] : 'right';
                $item_btn = sprintf('<a class="fmtpl-button-default fmtpl-carousel-item-btn %s" %s>%s<span class="fmtpl-btn-text">%s</span></a>',
                    $btn_class,
                    $link_attr_str,
                    $icon_str,
                    $slide['item_btn_text']
                );
            }
            if (!empty($item_title) ||!empty($item_btn)):?>
                <div class="fmtpl-elementor_content ">
                    <?php if (!empty($item_title)):?>
                        <div class="fmtpl-item-title">
                            <?php echo  $item_title;?>
                        </div>
                    <?php endif;
                    $product_count_title = (isset($settings['product_count_title']) && !empty($settings['product_count_title'])) ? $settings['product_count_title']: __('Product Count','fami-templatekits');
                    if ($cat_obj && isset($settings['show_product_count']) && $settings['show_product_count'] == 'yes'):?>
                        <div class="fmtpl-products-count <?php echo (isset($settings['count_number_position'])? $settings['count_number_position']:'');?>">
                            <span class="products-count-title"><?php echo $product_count_title ?></span>
                            <span class="count-value"><?php echo $cat_obj->count;?></span></div>
                    <?php
                    endif;
                     if (!empty($item_btn)):?>
                        <?php echo $item_btn;?>
                    <?php endif;?>
                </div>
            <?php endif;?>
            </div>
            <?php
        }

        protected function get_slide_image_url( $slide, array $settings ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

            if ( ! $image_url ) {
                $image_url = $slide['image']['url'];
            }
            return $image_url;
        }
        protected function render() {
            $settings = $this->get_active_settings();
            $this->print_slider($settings);
        }
        protected function print_slider( array $settings = null ) {
            $settings['widget_name'] = $this->get_name();
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
            $slider_content = apply_filters('fmtpl-carousel-categories-banner-widget-html','',$settings);
            if (!empty($slider_content)){
                return $slider_content;
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
            $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
            $arrow_on_heading = false;
            $layout_class =  $this->get_name().'-layout-'.$layout;
            if ($layout == 'fmtpl_style01' || $layout == 'fmtpl_style02'){
                $arrow_on_heading = true;
                $layout_class .= ' arrow-heading';
            }
            $inner_box_content = isset($settings['inner_box_content']) && $settings['inner_box_content']=='inside' ? true : false;
            if ($inner_box_content){
                $layout_class .= ' inner_box_content';
            }
            $slides_count = count( $settings['slides'] );
            $content_html = '';
            if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<div class="fmtpl-carousel-box-title">'.$title.'</div>';
            }
            if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
                $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
            }
            if (isset($settings['description']) && !empty($settings['description'])){
                $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
            }
            ?>
            <div class="fmtpl-elementor-widget fmtpl-elementor-swiper <?php echo $this->get_name().' '.$layout_class;?>">
                <?php if (!empty($content_html) && !$arrow_on_heading):?>
                    <div class="fmtpl-carousel-box-heading">
                        <?php echo $content_html;?>
                    </div>
                <?php endif;?>
                <div class="fmtpl-elementor-swiper">
                    <div class="fmtpl-elementor-main-swiper">
                        <?php if ($arrow_on_heading):?>
                            <div class="fmtpl-carousel-box-heading">
                                <?php if (!empty($content_html)){
                                    echo $content_html;
                                }
                                if (!empty($navi_html)){
                                    echo $navi_html;
                                }
                                ?>
                            </div>
                        <?php endif;?>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <?php
                                $slide_prints_count = 0;
                                foreach ( $settings['slides'] as $index => $slide ) :
                                    $slide_prints_count++;
                                    ob_start();
                                    $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $slide_prints_count );
                                    $slide_html = ob_get_clean();
                                    if (!empty($slide_html)):
                                        ?>
                                        <div class="swiper-slide">
                                            <?php echo $slide_html?>
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                            <?php if ( 1 < $slides_count ) : ?>
                                <?php
                                $pagi_class = 'swiper-pagination';
                                if (empty($settings['pagination'] )) {
                                    $pagi_class .= ' disabled';
                                }
                                ?>
                                <div class="<?php echo $pagi_class;?>"></div>
                            <?php endif; ?>
                        </div>
                        <?php if (!$arrow_on_heading && !empty($navi_html)){
                            echo $navi_html;
                        }?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

