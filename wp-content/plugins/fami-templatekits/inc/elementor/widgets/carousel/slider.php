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
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!class_exists('Fmtpl_Carousel_Slider')){
    class Fmtpl_Carousel_Slider extends Fmtpl_Carousel_Base {
        protected $default_control;
        protected $enable_heading = false;
        public function get_name() {
            return 'fmtpl-slider';
        }

        public function get_title() {
            return esc_html__( 'Images Slider', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'fmtpl-carousel-image fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'image', 'gallery', 'carousel', 'slider' ];
        }
        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $this->add_style_depends('fmtpl-slider');
            $this->add_style_depends('fmtpl-animate');
        }
        public function define_widget_control() {
            $effect_choise =  [
                '' => 'None',
                'pulse' => 'pulse',
                'bounceIn' => 'bounceIn',
                'bounceInDown' => 'bounceInDown',
                'bounceInLeft' => 'bounceInLeft',
                'bounceInRight' => 'bounceInRight',
                'bounceInUp' => 'bounceInUp',
                'fadeIn' => 'fadeIn',
                'fadeInDown' => 'fadeInDown',
                'fadeInDownBig' => 'fadeInDownBig',
                'fadeInLeft' => 'fadeInLeft',
                'fadeInLeftBig' => 'fadeInLeftBig',
                'fadeInRight' => 'fadeInRight',
                'fadeInRightBig' => 'fadeInRightBig',
                'fadeInUp' => 'fadeInUp',
                'fadeInUpBig' => 'fadeInUpBig',
                'fadeInTopLeft' => 'fadeInTopLeft',
                'fadeInTopRight' => 'fadeInTopRight',
                'fadeInBottomLeft' => 'fadeInBottomLeft',
                'fadeInBottomRight' => 'fadeInBottomRight',
                'lightSpeedInRight' => 'lightSpeedInRight',
                'lightSpeedInLeft' => 'lightSpeedInLeft',
                'zoomIn' => 'zoomIn',
                'slideInDown' => 'slideInDown',
                'slideInLeft' => 'slideInLeft',
                'slideInRight' => 'slideInRight',
                'slideInUp' => 'slideInUp'];
            $widget_control = [
                //slide item
                'item_bg' => [
                    'name' => 'item_bg',
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-slide-background',
                    'fields_options' => [
                        'background' => [
                            'label' => __( 'Background', 'fami-templatekits' ),
                        ],
                    ],
                ],
                'item_image' => [
                    'label' => esc_html__( 'Slide Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'separator' => 'before'
                ],
                'item_img_vertical_alignment' => [
                    'label' => __('Vertical Position', 'fami-templatekits'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'top' => [
                            'title' => __('Top', 'fami-templatekits'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __('Bottom', 'fami-templatekits'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'bottom',
                    'condition' => ['item_image[url]!' => '']
                ],
                'item_img_width' => [
                    'label' => esc_html__( 'Width', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1800,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-elementor_image' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_img_horizontal_alignment' => [
                    'label' => __('Horizontal Position', 'fami-templatekits'),
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
                    'default' => 'left',
                    'condition' => ['item_image[url]!' => '']
                ],
                'item_img_horizontal_pos' => [
                    'label' => __( 'Horizontal Offset', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.horizontal-right .fmtpl-elementor_image' => 'left: auto; right: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.horizontal-left .fmtpl-elementor_image' => 'left: {{SIZE}}{{UNIT}}; right: auto',
                    ],
                    'condition' => ['item_image[url]!' => '']
                ],
                'item_img_vertical_pos' => [
                    'label' => __( 'Vertical Offset', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.vertical-bottom .fmtpl-elementor_image' => 'top: auto; bottom: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.vertical-top .fmtpl-elementor_image' => 'top: {{SIZE}}{{UNIT}}; bottom: auto',
                    ],
                    'condition' => ['item_image[url]!' => '']
                ],
                'item_title' => [
                        'label' => esc_html__( 'Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => '',
                        'placeholder' => esc_html__( 'This is the %highlight% title', 'fami-templatekits' ),
                        'label_block' => true,
                    ],
                'item_highlight_title' => [
                        'label' => esc_html__( 'Highlight Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => '',
                        'placeholder' => esc_html__( 'Enter your Highlight title', 'fami-templatekits' ),
                        'label_block' => true,
                    ],
                'item_sub_title' => [
                        'label' => esc_html__( 'Sub Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => '',
                        'label_block' => true,
                    ],
                'item_description' => [
                    'label' => esc_html__( 'Description', 'fami-templatekits' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => '',
                    'placeholder' => esc_html__( 'Type your description here', 'fami-templatekits' ),
                    'separator' => 'before',
                ],
                'item_link' => [
                    'label' => esc_html__( 'Link', 'fami-templatekits' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => esc_html__( 'https://your-link.com', 'fami-templatekits' ),
                    'show_external' => true,
                    'dynamic' => [
                        'active' => true,
                    ]
                ],
                'item_btn_text' => [
                    'label' => esc_html__( 'Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'Type button text here', 'fami-templatekits' ),
                    'label_block' => true,
                ],
                'item_btn_icon' => [
                    'label' => esc_html__( 'Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'item_btn_text!' => ''
                    ]
                ],
                'item_btn_text2' => [
                    'label' => esc_html__( 'Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'Type button text here', 'fami-templatekits' ),
                    'label_block' => true,
                ],
                'item_btn_icon2' => [
                    'label' => esc_html__( 'Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'item_btn_text2!' => ''
                    ]
                ],
                'item_link2' => [
                    'label' => esc_html__( 'Link', 'fami-templatekits' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => esc_html__( 'https://your-link.com', 'fami-templatekits' ),
                    'show_external' => true,
                    'dynamic' => [
                        'active' => true,
                    ]
                ],
                'image_size' => [
                    'name' => 'image_size',
                    'default' => 'full',
                ],
                //slide item style
                'item_title_effect' => [
                    'label' => esc_html__( 'Title Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'item_title_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_title_effect!' => '',
                    ],
                ],
                'item_title_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_title_effect!' => '',
                    ],
                ],
                'item_title_order' => [
                    'label' => __( 'Title Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-item-title' => 'order: {{VALUE}};',
                    ],
                ],
                'item_title_bottom_space' => [
                    'label' => esc_html__( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-item-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_content_box_bg' => [
                    'label' => esc_html__( 'Content Background', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_content_box_padding' => [
                    'label' => esc_html__( 'Padding', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ],
                'item_content_box_margin' => [
                    'label' => esc_html__( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ],
                'item_content_box_effect' => [
                    'label' => esc_html__( 'Content Box Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'item_content_box_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_content_box_effect!' => '',
                    ],
                ],
                'item_content_box_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_content_box_effect!' => '',
                    ],
                ],
                'item_content_box_width' => [
                    'label' => esc_html__( 'Width', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1800,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_content_box_height' => [
                    'label' => esc_html__( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_title_color' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-item-title, {{WRAPPER}} .fmtpl-item-title a' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_title_color_hover' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-item-title:hover, {{WRAPPER}} .fmtpl-item-title a:hover' => 'color: {{VALUE}};',
                    ],
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-item-title, {{WRAPPER}} .fmtpl-item-title a',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],
                'item_highlight_title_color' => [
                    'label' => esc_html__( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-item-title .highlight' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_highlight_title_color_hover' => [
                    'label' => esc_html__( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-item-title:hover .highlight' => 'color: {{VALUE}};',
                    ],
                ],
                'item_highlight_title_typography' => [
                    'label' => esc_html__( 'Highlight Typography', 'fami-templatekits' ),
                    'name' => 'item_highlight_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-item-title .highlight',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],
                'item_sub_effect' => [
                    'label' => esc_html__( 'Sub Title Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'item_sub_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_sub_effect!' => '',
                    ],
                ],
                'item_sub_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_sub_effect!' => '',
                    ],
                ],
                'item_sub_order' => [
                    'label' => __( 'Sub Title Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-sub-title' => 'order: {{VALUE}};',
                    ],
                ],
                'item_sub_bottom_space' => [
                    'label' => esc_html__( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_sub_color' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-sub-title' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_3,
                    ],
                ],
                'item_sub_typography' => [
                    'name' => 'item_sub_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-sub-title',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                ],
                'item_sub_padding' => [
                    'label' => esc_html__( 'Padding', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ],
                'item_desc_effect' => [
                    'label' => esc_html__( 'Description Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'item_desc_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_desc_effect!' => '',
                    ],
                ],
                'item_desc_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_desc_effect!' => '',
                    ],
                ],
                'item_desc_order' => [
                    'label' => __( 'Description Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-image-desc' => 'order: {{VALUE}};',
                    ],
                ],
                'item_desc_bottom_space' => [
                    'label' => esc_html__( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-image-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_desc_color' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-image-desc' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_3,
                    ],
                ],
                'item_desc_typography' => [
                    'name' => 'item_desc_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-image-desc',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                ],
                'image_effect' => [
                    'label' => esc_html__( 'Image Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'image_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'image_effect!' => '',
                    ],
                ],
                'image_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'image_effect!' => '',
                    ],
                ],
                'image_border_radius' => [
                    'label' => esc_html__( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'item_alignment' => [
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
                    'selectors_dictionary' => [
                        'left' => 'align-items: flex-start; text-align: left;',
                        'center' => 'align-items: center; text-align: center;',
                        'right' => 'align-items: flex-end;text-align: right;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-slide-item' => '{{VALUE}}',
                        '{{WRAPPER}} .fmtpl-swiper-slide .swiper-slide-item .fmtpl-content-container .fmtpl-btn-wrap.vertical' => '{{VALUE}}'
                    ],
                ],
                'item_vertical_alignment' => [
                    'label' => __('Vertical Alignment', 'fami-templatekits'),
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
                    'default' => 'bottom',
                    'selectors_dictionary' => [
                        'top' => 'flex-start',
                        'middle' => 'center',
                        'bottom' => 'flex-end',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content ' => 'justify-content: {{VALUE}}',
                    ],
                ],
                'item_horizontal_position' => [
                    'label' => __('Horizontal Position', 'fami-templatekits'),
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
                        '{{WRAPPER}} .swiper-slide-item .fmtpl-content-container' => 'justify-content: {{VALUE}}',
                    ],
                ],
                'item_vertical_position' => [
                    'label' => __('Vertical Position', 'fami-templatekits'),
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
                        '{{WRAPPER}} .fmtpl-swiper-slide .swiper-slide-item .fmtpl-content-container' => 'align-items: {{VALUE}}',
                    ],

                ],
                'item_btn_effect' => [
                    'label' => esc_html__( 'Button Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise
                ],
                'item_btn_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_btn_effect!' => '',
                    ],
                ],
                'item_btn_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_btn_effect!' => '',
                    ],
                ],
                'item_btn_order' => [
                    'label' => __( 'Button Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-btn-wrap' => 'order: {{VALUE}};',
                    ],
                ],
                'item_btn_space' => [
                    'label' => esc_html__( 'Spacing', 'fami-templatekits' ),
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
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-btn-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_btn_width' => [
                    'label' => esc_html__( 'Width', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Height', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
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
                'item_btn_icon_color' => [
                    'label' => esc_html__( 'Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-carousel-item-btn svg' => 'fill: {{VALUE}};',
                    ],
                ],
                'item_btn_color_hover' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                ],
                'item_btn_icon_color_hover' => [
                    'label' => esc_html__( 'Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-carousel-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                ],
                'item_btn_bg_color' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_btn_bg_color_hover' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_btn_border' => [
                    'name' => 'item_btn_border',
                    'label' => esc_html__( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-item-btn',
                ],
                'item_btn_border_radius' => [
                    'label' => esc_html__( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],
                'item_btn_border_color_hover' => [
                    'label' => esc_html__( 'Border Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-item-btn:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'item_btn_border_border!' => '',
                    ],
                ],
                'item_btn_icon_space' => [
                    'label' => esc_html__( 'Spacing', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Size', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Icon Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'right',
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'fami-templatekits' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'fami-templatekits' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                ],
                'item_btn_text_typography' => [
                    'name' => 'item_btn_text_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-item-btn .fmtpl-btn-text',
                ],


                'item_btn2_color' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-2nd-item-btn' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-2nd-item-btn svg' => 'fill: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_btn2_icon_color' => [
                    'label' => esc_html__( 'Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-2nd-item-btn svg' => 'fill: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-2nd-item-btn > i' => 'color: {{VALUE}};'
                    ],
                ],
                'item_btn2_color_hover' => [
                    'label' => esc_html__( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-2nd-item-btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-2nd-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                ],
                'item_btn2_icon_color_hover' => [
                    'label' => esc_html__( 'Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-2nd-item-btn:hover svg' => 'fill: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-swiper-slide .fmtpl-2nd-item-btn:hover > i' => 'color: {{VALUE}};'
                    ],
                ],
                'item_btn2_bg_color' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-2nd-item-btn' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_btn2_bg_color_hover' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-2nd-item-btn:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
                'item_btn2_view' => [
                    'label' => __( 'Display Style', 'fami-templatekits' ),
                    'label_block' => false,
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'horizontal',
                    'options' => [
                        'horizontal' => [
                            'title' => __( 'Horizontal', 'fami-templatekits' ),
                            'icon' => 'eicon-navigation-horizontal',
                        ],
                        'vertical' => [
                            'title' => __( 'Vertical', 'fami-templatekits' ),
                            'icon' => 'eicon-navigation-vertical',
                        ],
                    ],
                ],
                'item_btn2_space' => [
                    'label' => esc_html__( '2nd Button Spacing', 'fami-templatekits' ),
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
                        '{{WRAPPER}} .fmtpl-btn-wrap.horizontal .fmtpl-2nd-item-btn' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-btn-wrap.vertical .fmtpl-2nd-item-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ],

                'item_divider' => [
                    'label' => __( 'Show Divider', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'default' => 'no',
                ],
                'item_divider_effect' => [
                    'label' => esc_html__( 'Divider Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $effect_choise,
                    'condition' => [
                        'item_divider!' => '',
                    ],
                ],
                'item_divider_duration' => [
                    'label' => esc_html__( 'Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 100,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1000,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_divider_effect!' => '',
                    ],
                ],
                'item_divider_delay' => [
                    'label' => esc_html__( 'Delay', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 100,
                    'default' => 1,
                    'description' => 'in millisecond (ms) only',
                    'condition' => [
                        'item_divider_effect!' => '',
                    ],
                ],
                'item_divider_order' => [
                    'label' => __( 'Divider Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-item-divider' => 'order: {{VALUE}};',
                    ],
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_width' => [
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
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_height' => [
                    'label' => __( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 2,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider' => 'background-color: {{VALUE}};',
                    ],
                    'default' => '#000000',
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-item-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_border_type' => [
                    'name' => 'divider_border_type',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider',
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'item_divider' => 'yes'
                    ]
                ],
                'divider_border_color_hover' => [
                    'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor_content .fmtpl-divider:hover' => 'border-color: {{VALUE}};',
                    ],
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'item_divider',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'divider_border_type_border',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
                'owner_item_setting' => [
                    'label' => __( 'Owner Setting', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'default' => 'no',
                ],
                'owner_item_title_color' => [
                    'label' => esc_html__( 'Title Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-item-title, {{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-item-title a' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ]
                ],

                'owner_item_highlight_title_color' => [
                    'label' => esc_html__( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-item-title .highlight' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_highlight_title_color_hover' => [
                    'label' => esc_html__( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-item-title:hover .highlight' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_sub_color' => [
                    'label' => esc_html__( 'Sub Title Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-sub-title' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_desc_color' => [
                    'label' => esc_html__( 'Description Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-image-desc' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],

                'owner_item_alignment' => [
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
                        '{{WRAPPER}} .swiper-slide-item{{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_vertical_alignment' => [
                    'label' => __('Vertical Alignment', 'fami-templatekits'),
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
                    'default' => 'bottom',
                    'selectors_dictionary' => [
                        'top' => 'flex-start',
                        'middle' => 'center',
                        'bottom' => 'flex-end',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-elementor_content ' => 'justify-content: {{VALUE}}',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_horizontal_position' => [
                    'label' => __('Horizontal Position', 'fami-templatekits'),
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
                        '{{WRAPPER}} .swiper-slide-item{{CURRENT_ITEM}} .fmtpl-content-container' => 'justify-content: {{VALUE}}',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_vertical_position' => [
                    'label' => __('Vertical Position', 'fami-templatekits'),
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
                        '{{WRAPPER}} .fmtpl-swiper-slide .swiper-slide-item{{CURRENT_ITEM}} .fmtpl-content-container' => 'align-items: {{VALUE}}',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],

                'owner_item_btn_color' => [
                    'label' => esc_html__( 'Button Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn' => 'color: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn svg' => 'fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_color_hover' => [
                    'label' => esc_html__( 'Button Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_icon_color' => [
                    'label' => esc_html__( 'Button Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide {{CURRENT_ITEM}} .fmtpl-carousel-item-btn svg' => 'fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_icon_color_hover' => [
                    'label' => esc_html__( 'Button Icon Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-swiper-slide {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover svg' => 'fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_bg_color' => [
                    'label' => esc_html__( 'Button Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_bg_color_hover' => [
                    'label' => esc_html__( 'Button Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_border_color' => [
                    'label' => esc_html__( 'Button Border Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_item_btn_border_color_hover' => [
                    'label' => esc_html__( 'Button Border Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-carousel-item-btn:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_divider_color' => [
                    'label' => __( 'Divider Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-elementor_content .fmtpl-divider' => 'background-color: {{VALUE}};',
                    ],
                    'default' => '#000000',
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],
                'owner_divider_border_color' => [
                    'label' => __( 'Divider Border Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} {{CURRENT_ITEM}} .fmtpl-elementor_content .fmtpl-divider:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ],

                'slide_effect' => [
                    'label' => esc_html__( 'Slide Animation', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'  => __( 'Default', 'fami-templatekits' ),
                        'fade' => __( 'Fade', 'fami-templatekits' ),
                        'masked' => __( 'Masked Slide', 'fami-templatekits' ),
                    ]
                ],
                'slide_width' => [
                    'label' => esc_html__( 'Content Container Width', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1800,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-content-container' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'slide_height' => [
                    'label' => esc_html__( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1800,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 500,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-slide-item' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'arrow_container' => [
                    'label' => __( 'Inside Container', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'fami-templatekits' ),
                    'label_off' => __( 'No', 'fami-templatekits' ),
                    'default' => 'no',
                ],
                'arrow_position' => [
                    'label' => __( 'Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'between',
                    'options' => [
                        'between'  => __( 'Space Between', 'fami-templatekits' ),
                        'left' => __( 'Left', 'fami-templatekits' ),
                        'center' => __( 'Center', 'fami-templatekits' ),
                        'right' => __( 'Right', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'fmtpl-arrow-position-',
                ],
                'arrow_vertical_position' => [
                    'label' => __( 'Vertical Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'bottom',
                    'options' => [
                        'center' => __( 'Center', 'fami-templatekits' ),
                        'bottom' => __( 'Bottom', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'fmtpl-arrow-vertical-position-',
                    'condition' => ['arrow_position' => ['left', 'right']],
                ],
                'arrow_direction' => [
                    'label' => __( 'Arrow Direction', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'row',
                    'options' => [
                        'row' => __( 'Row', 'fami-templatekits' ),
                        'column' => __( 'Column', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'fmtpl-arrow-direction-',
                    'condition' => ['arrow_position' => ['left', 'right']],
                ],
                'arrow_horizontal_indent' => [
                    'label' => __( 'Horizontal Indent', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -200,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.fmtpl-arrow-position-between .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-arrow-position-between .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-arrow-position-left .elementor-swiper-button-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-arrow-position-right .elementor-swiper-button-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'arrow_position!' => 'center'
                    ]
                ],
                'arrow_vertical_indent' => [
                    'label' => __( 'Vertical Indent', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -200,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}:not(.fmtpl-arrow-position-between) .elementor-swiper-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'arrow_position!' => 'between'
                    ]
                ],
                'arrow_spacing' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}:not(.fmtpl-arrow-position-between):not(.fmtpl-arrow-direction-column) .elementor-swiper-button-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-arrow-direction-column:not(.fmtpl-arrow-position-between) .elementor-swiper-button-prev' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'arrow_position!' => 'between'
                    ]
                ],
                'pagination_style' => [
                    'label' => esc_html__( 'Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default'  => esc_html__( 'Default', 'fami-templatekits' )
                    ],
                    'default' => 'default',
                    'prefix_class' => 'fmtpl-dot-style-',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ],
                'pagi_type' => [
                    'label' => __( 'Pagination Type', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'center',
                    'options' => [
                        'left_vertical' => __( 'Left Vertical', 'fami-templatekits' ),
                        'left' => __( 'Left', 'fami-templatekits' ),
                        'center' => __( 'Center', 'fami-templatekits' ),
                        'right' => __( 'Right', 'fami-templatekits' ),
                        'right_vertical' => __( 'Right Vertical', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'fmtpl-pagi-type-',
                    'condition' => [
                        'pagination_position' => 'inside'
                    ]
                ],
                'pagination_space' => [
                    'selectors' => [
                        '{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
                        '{{WRAPPER}} .swiper-container-vertical .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-pagi-type-left_vertical .swiper-container-horizontal .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-pagi-type-left_vertical .swiper-container-vertical .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-pagi-type-right_vertical .swiper-container-horizontal .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.fmtpl-pagi-type-right_vertical .swiper-container-vertical .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                    ],
                ],
            ];
            $this->default_control = apply_filters('fmtpl-slider-elementor-widget-control', $widget_control);
        }
        protected function _register_controls() {
            parent::_register_controls();

            $this->define_widget_control();


            if (isset($this->default_control['image_size'])) {
                $this->start_injection( [
                    'at' => 'before',
                    'of' => 'slides_per_view',
                ] );

                if (isset($this->default_control['image_size'])){
                    $this->add_group_control(
                        Group_Control_Image_Size::get_type(),
                        $this->default_control['image_size']
                    );
                }
                $this->end_injection();
            }
            $this->start_injection( [
                'at' => 'before',
                'of' => 'item_alignment',
            ] );
            if (isset($this->default_control['slide_effect'])) {
                $this->add_control(
                    'slide_effect',
                    $this->default_control['slide_effect']
                );
            }
            if (isset($this->default_control['slide_width'])) {
                $this->add_responsive_control(
                    'slide_width',
                    $this->default_control['slide_width']
                );
            }
            if (isset($this->default_control['slide_height'])) {
                $this->add_responsive_control(
                    'slide_height',
                    $this->default_control['slide_height']
                );
            }
            $this->end_injection();
            $this->start_controls_section(
                'section_item_style',
                [
                    'label' => esc_html__( 'Slide Content', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_control(
                'item_heading_1',
                [
                    'label' => esc_html__( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            //image_effect
            if (isset($this->default_control['image_effect'])) {
                $this->add_control(
                    'image_effect',
                    $this->default_control['image_effect']
                );
            }
            if (isset($this->default_control['image_duration'])) {
                $this->add_control(
                    'image_duration',
                    $this->default_control['image_duration']
                );
            }
            if (isset($this->default_control['image_delay'])) {
                $this->add_control(
                    'image_delay',
                    $this->default_control['image_delay']
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
                    'label' => esc_html__( 'Content Box', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'none',
                ]
            );
            if (isset($this->default_control['item_content_box_effect'])) {
                $this->add_control(
                    'item_content_box_effect',
                    $this->default_control['item_content_box_effect']
                );
            }
            if (isset($this->default_control['item_content_box_duration'])) {
                $this->add_control(
                    'item_content_box_duration',
                    $this->default_control['item_content_box_duration']
                );
            }
            if (isset($this->default_control['item_content_box_delay'])) {
                $this->add_control(
                    'item_content_box_delay',
                    $this->default_control['item_content_box_delay']
                );
            }

            if (isset($this->default_control['item_content_box_width'])) {
                $this->add_responsive_control(
                    'item_content_box_width',
                    $this->default_control['item_content_box_width']
                );
            }
            if (isset($this->default_control['item_content_box_height'])) {
                $this->add_responsive_control(
                    'item_content_box_height',
                    $this->default_control['item_content_box_height']
                );
            }
            //item_alignment
            if (isset($this->default_control['item_alignment'])) {

                $this->remove_control('item_alignment');
                $this->add_responsive_control(
                    'item_alignment',
                    $this->default_control['item_alignment']
                );
            }
            if (isset($this->default_control['item_vertical_alignment'])) {
                $this->add_responsive_control(
                    'item_vertical_alignment',
                    $this->default_control['item_vertical_alignment']
                );
            }
            if (isset($this->default_control['item_horizontal_position'])) {
                $this->add_responsive_control(
                    'item_horizontal_position',
                    $this->default_control['item_horizontal_position']
                );
            }
            if (isset($this->default_control['item_vertical_position'])) {
                $this->add_responsive_control(
                    'item_vertical_position',
                    $this->default_control['item_vertical_position']
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
            //item_content_box_margin
            $this->add_control(
                'item_heading_2',
                [
                    'label' => esc_html__( 'Item Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if (isset($this->default_control['item_title_order'])) {
                $this->add_control(
                    'item_title_order',
                    $this->default_control['item_title_order']
                );
            }
            if (isset($this->default_control['item_title_bottom_space'])) {
                $this->add_responsive_control(
                    'item_title_bottom_space',
                    $this->default_control['item_title_bottom_space']
                );
            }

            if (isset($this->default_control['item_title_effect'])) {
                $this->add_control(
                    'item_title_effect',
                    $this->default_control['item_title_effect']
                );
            }
            if (isset($this->default_control['item_title_duration'])) {
                $this->add_control(
                    'item_title_duration',
                    $this->default_control['item_title_duration']
                );
            }
            if (isset($this->default_control['item_title_delay'])) {
                $this->add_control(
                    'item_title_delay',
                    $this->default_control['item_title_delay']
                );
            }

            $this->start_controls_tabs( 'title_tabs' );
            $this->start_controls_tab(
                'title_tab_normal',
                [
                    'label' => esc_html__( 'Normal', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Hover', 'fami-templatekits' ),
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
            $this->add_control(
                'item_heading_21',
                [
                    'label' => __( 'Item Divider', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_divider'])) {
                $this->add_control(
                    'item_divider',
                    $this->default_control['item_divider']
                );
            }
            if (isset($this->default_control['item_divider_effect'])) {
                $this->add_control(
                    'item_divider_effect',
                    $this->default_control['item_divider_effect']
                );
            }
            if (isset($this->default_control['item_divider_duration'])) {
                $this->add_control(
                    'item_divider_duration',
                    $this->default_control['item_divider_duration']
                );
            }
            if (isset($this->default_control['item_divider_delay'])) {
                $this->add_control(
                    'item_divider_delay',
                    $this->default_control['item_divider_delay']
                );
            }
            if (isset($this->default_control['item_divider_order'])) {
                $this->add_control(
                    'item_divider_order',
                    $this->default_control['item_divider_order']
                );
            }
            if (isset($this->default_control['divider_width'])) {
                $this->add_responsive_control(
                    'divider_width',
                    $this->default_control['divider_width']
                );
            }

            if (isset($this->default_control['divider_height'])) {
                $this->add_responsive_control(
                    'divider_height',
                    $this->default_control['divider_height']
                );
            }

            if (isset($this->default_control['divider_color'])) {
                $this->add_control(
                    'divider_color',
                    $this->default_control['divider_color']
                );
            }
            if (isset($this->default_control['divider_border_type'])) {
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    $this->default_control['divider_border_type']
                );
            }
            if (isset($this->default_control['divider_border_color_hover'])) {
                $this->add_control(
                    'divider_border_color_hover',
                    $this->default_control['divider_border_color_hover']
                );
            }
            if (isset($this->default_control['divider_border_radius'])) {
                $this->add_responsive_control(
                    'divider_border_radius',
                    $this->default_control['divider_border_radius']
                );
            }


            if (isset($this->default_control['divider_margin'])) {
                $this->add_responsive_control(
                    'divider_margin',
                    $this->default_control['divider_margin']
                );
            }
            $this->add_control(
                'item_heading_3_0',
                [
                    'label' => esc_html__( 'Item Sub Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if (isset($this->default_control['item_sub_order'])) {
                $this->add_control(
                    'item_sub_order',
                    $this->default_control['item_sub_order']
                );
            }
            if (isset($this->default_control['item_sub_bottom_space'])) {
                $this->add_responsive_control(
                    'item_sub_bottom_space',
                    $this->default_control['item_sub_bottom_space']
                );
            }
            if (isset($this->default_control['item_sub_effect'])) {
                $this->add_control(
                    'item_sub_effect',
                    $this->default_control['item_sub_effect']
                );
            }
            if (isset($this->default_control['item_sub_duration'])) {
                $this->add_control(
                    'item_sub_duration',
                    $this->default_control['item_sub_duration']
                );
            }
            if (isset($this->default_control['item_sub_delay'])) {
                $this->add_control(
                    'item_sub_delay',
                    $this->default_control['item_sub_delay']
                );
            }
            if (isset($this->default_control['item_sub_color'])) {
                $this->add_control(
                    'item_sub_color',
                    $this->default_control['item_sub_color']
                );
            }
            //item_sub_padding
            if (isset($this->default_control['item_sub_padding'])) {
                $this->add_responsive_control(
                    'item_sub_padding',
                    $this->default_control['item_sub_padding']
                );
            }
            if (isset($this->default_control['item_sub_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_sub_typography']
                );
            }

            $this->add_control(
                'item_heading_3',
                [
                    'label' => esc_html__( 'Item Description', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_desc_order'])) {
                $this->add_control(
                    'item_desc_order',
                    $this->default_control['item_desc_order']
                );
            }
            if (isset($this->default_control['item_desc_bottom_space'])) {
                $this->add_responsive_control(
                    'item_desc_bottom_space',
                    $this->default_control['item_desc_bottom_space']
                );
            }
            if (isset($this->default_control['item_desc_effect'])) {
                $this->add_control(
                    'item_desc_effect',
                    $this->default_control['item_desc_effect']
                );
            }
            if (isset($this->default_control['item_desc_duration'])) {
                $this->add_control(
                    'item_desc_duration',
                    $this->default_control['item_desc_duration']
                );
            }
            if (isset($this->default_control['item_desc_delay'])) {
                $this->add_control(
                    'item_desc_delay',
                    $this->default_control['item_desc_delay']
                );
            }
            if (isset($this->default_control['item_desc_color'])) {
                $this->add_control(
                    'item_desc_color',
                    $this->default_control['item_desc_color']
                );
            }

            if (isset($this->default_control['item_desc_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_desc_typography']
                );
            }
            $this->add_control(
                '_item_btn_heading',
                [
                    'label' => esc_html__( 'Button Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_btn_order'])) {
                $this->add_control(
                    'item_btn_order',
                    $this->default_control['item_btn_order']
                );
            }
            if (isset($this->default_control['item_btn_space'])) {
                $this->add_responsive_control(
                    'item_btn_space',
                    $this->default_control['item_btn_space']
                );
            }

            if (isset($this->default_control['item_btn_width'])) {
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
            if (isset($this->default_control['item_btn_effect'])) {
                $this->add_control(
                    'item_btn_effect',
                    $this->default_control['item_btn_effect']
                );
            }
            if (isset($this->default_control['item_btn_duration'])) {
                $this->add_control(
                    'item_btn_duration',
                    $this->default_control['item_btn_duration']
                );
            }
            if (isset($this->default_control['item_btn_delay'])) {
                $this->add_control(
                    'item_btn_delay',
                    $this->default_control['item_btn_delay']
                );
            }
            $this->add_control(
                'item_btn_icon_options',
                [
                    'label' => esc_html__( 'Icon Options', 'fami-templatekits' ),
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
                    'label' => esc_html__( 'Button Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
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
                        'label' => esc_html__('Button Border', 'fami-templatekits'),
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
                'tab2_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn_color'])) {
                $this->add_control(
                    'item_btn_color',
                    $this->default_control['item_btn_color']
                );
            }
            if (isset($this->default_control['item_btn_icon_color'])) {
                $this->add_control(
                    'item_btn_icon_color',
                    $this->default_control['item_btn_icon_color']
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
                'tab2_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn_color_hover'])) {
                $this->add_control(
                    'item_btn_color_hover',
                    $this->default_control['item_btn_color_hover']
                );
            }
            if (isset($this->default_control['item_btn_icon_color_hover'])) {
                $this->add_control(
                    'item_btn_icon_color_hover',
                    $this->default_control['item_btn_icon_color_hover']
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
            $this->add_control(
                '_item_btn_heading_1',
                [
                    'label' => esc_html__( '2nd Button', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_btn2_view'])) {
                $this->add_control(
                    'item_btn2_view',
                    $this->default_control['item_btn2_view']
                );
            }
            if (isset($this->default_control['item_btn2_space'])) {
                $this->add_control(
                    'item_btn2_space',
                    $this->default_control['item_btn2_space']
                );
            }
            $this->start_controls_tabs( 'item_btn2_tabs' );
            $this->start_controls_tab(
                'tab_button2_normal',
                [
                    'label' => esc_html__( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn2_color'])) {
                $this->add_control(
                    'item_btn2_color',
                    $this->default_control['item_btn2_color']
                );
            }
            if (isset($this->default_control['item_btn2_icon_color'])) {
                $this->add_control(
                    'item_btn2_icon_color',
                    $this->default_control['item_btn2_icon_color']
                );
            }
            if (isset($this->default_control['item_btn2_bg_color'])) {
                $this->add_control(
                    'item_btn2_bg_color',
                    $this->default_control['item_btn2_bg_color']
                );
            }
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['item_btn2_color_hover'])) {
                $this->add_control(
                    'item_btn2_color_hover',
                    $this->default_control['item_btn2_color_hover']
                );
            }
            if (isset($this->default_control['item_btn2_icon_color_hover'])) {
                $this->add_control(
                    'item_btn2_icon_color_hover',
                    $this->default_control['item_btn2_icon_color_hover']
                );
            }
            if (isset($this->default_control['item_btn2_bg_color_hover'])) {
                $this->add_control(
                    'item_btn2_bg_color_hover',
                    $this->default_control['item_btn2_bg_color_hover']
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
            $this->start_injection( [
                'at' => 'before',
                'of' => 'arrows_width',
            ] );
            //arrow_container
            if (isset($this->default_control['arrow_container'])) {
                $this->add_control(
                    'arrow_container',
                    $this->default_control['arrow_container']
                );
            }
            if (isset($this->default_control['arrow_position'])) {
                $this->add_control(
                    'arrow_position',
                    $this->default_control['arrow_position']
                );
            }
            if (isset($this->default_control['arrow_vertical_position'])) {
                $this->add_control(
                    'arrow_vertical_position',
                    $this->default_control['arrow_vertical_position']
                );
            }
            if (isset($this->default_control['arrow_direction'])) {
                $this->add_control(
                    'arrow_direction',
                    $this->default_control['arrow_direction']
                );
            }
            if (isset($this->default_control['arrow_vertical_indent'])) {
                $this->add_responsive_control(
                    'arrow_vertical_indent',
                    $this->default_control['arrow_vertical_indent']
                );
            }
            if (isset($this->default_control['arrow_horizontal_indent'])) {
                $this->add_responsive_control(
                    'arrow_horizontal_indent',
                    $this->default_control['arrow_horizontal_indent']
                );
            }
            if (isset($this->default_control['arrow_spacing'])) {
                $this->add_responsive_control(
                    'arrow_spacing',
                    $this->default_control['arrow_spacing']
                );
            }
            $this->end_injection();
            $this->start_injection( [
                'at' => 'after',
                'of' => 'pagination_position',
            ] );
            if (isset($this->default_control['pagi_type'])) {
                $this->add_control(
                    'pagi_type',
                    $this->default_control['pagi_type']
                );
            }
            $this->end_injection();
            if (isset($this->default_control['pagination_space'])) {
                $this->update_responsive_control('pagination_space',$this->default_control['pagination_space']);
            }

            $unset_control = ['slides_per_view','slides_to_scroll','space_between','item_background'];
            foreach ($unset_control as $u_c){
                $this->remove_control($u_c);
            }
        }

        protected function add_repeater_controls( Repeater $repeater ) {
            $this->define_widget_control();
            if (isset($this->default_control['item_bg'])) {
                $repeater->add_group_control(
                    Group_Control_Background::get_type(),
                    $this->default_control['item_bg']
                );
            }
            if (isset($this->default_control['item_image'])) {
                $repeater->add_control(
                    'item_image',
                    $this->default_control['item_image']
                );
            }
            if (isset($this->default_control['item_img_width'])) {
                $repeater->add_responsive_control(
                    'item_img_width',
                    $this->default_control['item_img_width']
                );
            }

            if (isset($this->default_control['item_img_horizontal_alignment'])) {
                $repeater->add_control(
                    'item_img_horizontal_alignment',
                    $this->default_control['item_img_horizontal_alignment']
                );
            }
            if (isset($this->default_control['item_img_vertical_alignment'])) {
                $repeater->add_control(
                    'item_img_vertical_alignment',
                    $this->default_control['item_img_vertical_alignment']
                );
            }

            if (isset($this->default_control['item_img_horizontal_pos'])) {
                $repeater->add_responsive_control(
                    'item_img_horizontal_pos',
                    $this->default_control['item_img_horizontal_pos']
                );
            }
            if (isset($this->default_control['item_img_vertical_pos'])) {
                $repeater->add_responsive_control(
                    'item_img_vertical_pos',
                    $this->default_control['item_img_vertical_pos']
                );
            }
            if (isset($this->default_control['item_title'])) {
                $repeater->add_control(
                    'item_title',
                    $this->default_control['item_title']
                );
            }
            if (isset($this->default_control['item_sub_title'])) {
                $repeater->add_control(
                    'item_sub_title',
                    $this->default_control['item_sub_title']
                );
            }
            if (isset($this->default_control['item_highlight_title'])) {
                $repeater->add_control(
                    'item_highlight_title',
                    $this->default_control['item_highlight_title']
                );
            }
            if (isset($this->default_control['item_link'])) {
                $repeater->add_control(
                    'item_link',
                    $this->default_control['item_link']
                );
            }
            $repeater->add_control(
                'owner_item_heading_0',
                [
                    'label' => esc_html__( 'Button 1', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
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
            $repeater->add_control(
                'owner_item_heading_0_1',
                [
                    'label' => esc_html__( 'Button 2', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_btn_text2'])) {
                $repeater->add_control(
                    'item_btn_text2',
                    $this->default_control['item_btn_text2']
                );
            }
            if (isset($this->default_control['item_btn_icon2'])) {
                $repeater->add_control(
                    'item_btn_icon2',
                    $this->default_control['item_btn_icon2']
                );
            }
            if (isset($this->default_control['item_link2'])) {
                $repeater->add_control(
                    'item_link2',
                    $this->default_control['item_link2']
                );
            }
            if (isset($this->default_control['item_description'])) {
                $repeater->add_control(
                    'item_description',
                    $this->default_control['item_description']
                );
            }
            if (isset($this->default_control['owner_item_setting'])) {
                $repeater->add_control(
                    'owner_item_setting',
                    $this->default_control['owner_item_setting']
                );
            }

            if (isset($this->default_control['owner_item_alignment'])) {
                $repeater->add_responsive_control(
                    'owner_item_alignment',
                    $this->default_control['owner_item_alignment']
                );
            }
            if (isset($this->default_control['owner_item_vertical_alignment'])) {
                $repeater->add_responsive_control(
                    'owner_item_vertical_alignment',
                    $this->default_control['owner_item_vertical_alignment']
                );
            }
            if (isset($this->default_control['owner_item_horizontal_position'])) {
                $repeater->add_responsive_control(
                    'owner_item_horizontal_position',
                    $this->default_control['owner_item_horizontal_position']
                );
            }
            if (isset($this->default_control['owner_item_vertical_position'])) {
                $repeater->add_responsive_control(
                    'owner_item_vertical_position',
                    $this->default_control['owner_item_vertical_position']
                );
            }
            $repeater->add_control(
                'owner_item_heading_1',
                [
                    'label' => esc_html__( 'Owner Content', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ]
            );
            if (isset($this->default_control['owner_item_title_color'])) {
                $repeater->add_control(
                    'owner_item_title_color',
                    $this->default_control['owner_item_title_color']
                );
            }
            if (isset($this->default_control['owner_item_highlight_title_color'])) {
                $repeater->add_control(
                    'owner_item_highlight_title_color',
                    $this->default_control['owner_item_highlight_title_color']
                );
            }
            if (isset($this->default_control['owner_item_sub_color'])) {
                $repeater->add_control(
                    'owner_item_sub_color',
                    $this->default_control['owner_item_sub_color']
                );
            }
            if (isset($this->default_control['owner_item_desc_color'])) {
                $repeater->add_control(
                    'owner_item_desc_color',
                    $this->default_control['owner_item_desc_color']
                );
            }
            if (isset($this->default_control['owner_divider_color'])) {
                $repeater->add_control(
                    'owner_divider_color',
                    $this->default_control['owner_divider_color']
                );
            }
            if (isset($this->default_control['owner_divider_border_color'])) {
                $repeater->add_control(
                    'owner_divider_border_color',
                    $this->default_control['owner_divider_border_color']
                );
            }
            $repeater->add_control(
                'owner_item_heading_3',
                [
                    'label' => esc_html__( 'Owner Button', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ]
            );

            $repeater->start_controls_tabs( 'owner_btn_tabs' );
            $repeater->start_controls_tab(
                'owner_btn_tab_normal',
                [
                    'label' => esc_html__( 'Normal', 'fami-templatekits' ),
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ]
            );

            if (isset($this->default_control['owner_item_btn_color'])) {
                $repeater->add_control(
                    'owner_item_btn_color',
                    $this->default_control['owner_item_btn_color']
                );
            }
            if (isset($this->default_control['owner_item_btn_bg_color'])) {
                $repeater->add_control(
                    'owner_item_btn_bg_color',
                    $this->default_control['owner_item_btn_bg_color']
                );
            }
            if (isset($this->default_control['owner_item_btn_icon_color'])) {
                $repeater->add_control(
                    'owner_item_btn_icon_color',
                    $this->default_control['owner_item_btn_icon_color']
                );
            }
            //
            if (isset($this->default_control['owner_item_btn_border_color'])) {
                $repeater->add_control(
                    'owner_item_btn_border_color',
                    $this->default_control['owner_item_btn_border_color']
                );
            }
            $repeater->end_controls_tab();
            $repeater->start_controls_tab(
                'owner_btn_tab_hover',
                [
                    'label' => esc_html__( 'Hover', 'fami-templatekits' ),
                    'condition' => [
                        'owner_item_setting' => 'yes',
                    ],
                ]
            );
            if (isset($this->default_control['owner_item_btn_color_hover'])) {
                $repeater->add_control(
                    'owner_item_btn_color_hover',
                    $this->default_control['owner_item_btn_color_hover']
                );
            }
            if (isset($this->default_control['owner_item_btn_icon_color_hover'])) {
                $repeater->add_control(
                    'owner_item_btn_icon_color_hover',
                    $this->default_control['owner_item_btn_icon_color_hover']
                );
            }
            if (isset($this->default_control['owner_item_btn_bg_color_hover'])) {
                $repeater->add_control(
                    'owner_item_btn_bg_color_hover',
                    $this->default_control['owner_item_btn_bg_color_hover']
                );
            }
            if (isset($this->default_control['owner_item_btn_border_color_hover'])) {
                $repeater->add_control(
                    'owner_item_btn_border_color_hover',
                    $this->default_control['owner_item_btn_border_color_hover']
                );
            }
            $repeater->end_controls_tab();
            $repeater->end_controls_tabs();
        }
        protected function get_repeater_defaults() {
            $placeholder_image_src = Utils::get_placeholder_image_src();
            return [
                [
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
            ];
        }
        protected function print_slide( array $slide, array $settings, $element_key ) {
            $slide_html = apply_filters('fmtpl-slider-item-html','',$slide, $settings,$element_key);
            if (!empty($slide_html)){
                echo ($slide_html);
                return;
            }
            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'swiper-slide-item',
            ]);
            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'elementor-repeater-item-' . $slide['_id'],
            ]);
            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'vertical-' . $slide['item_img_vertical_alignment'],
            ]);
            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'horizontal-' . $slide['item_img_horizontal_alignment'],
            ]);
            ?>
            <div class="swiper-slide">
                <div <?php echo( $this->get_render_attribute_string( $element_key . '-fmtpl-image-carousel' )); ?>>
                    <div class="fmtpl-slide-background"></div>
                    <?php $header_element = 'header_' . $slide['_id'];
                    $link_url = empty( $slide['item_link']['url'] ) ? false : $slide['item_link']['url'];
                    $header_tag = ! empty( $link_url ) ? 'a' : 'div';
                    //$this->add_render_attribute( $header_element, 'class', 'fmtpl-elementor_image' );
                    $element_attr = [
                        'class' => 'fmtpl-elementor_image',
                    ];
                    if (isset($settings['image_effect']) && $settings['image_effect']){
                        $element_attr['data-effect'] = $settings['image_effect'];
                        if (isset($settings['image_duration']) && $settings['image_duration']){
                            $element_attr['data-duration'] = $settings['image_duration'];
                        }
                        if (isset($settings['image_delay']) && $settings['image_delay']){
                            $element_attr['data-delay'] = $settings['image_delay'];
                        }
                    }
                    $this->add_render_attribute( $header_element, $element_attr );
                    if ( ! empty( $link_url ) ) {
                        $this->add_render_attribute( $header_element, 'href', $link_url );
                        if ( $slide['item_link']['is_external'] ) {
                            $this->add_render_attribute( $header_element, 'target', '_blank' );
                        }
                        if ( ! empty( $slide['item_link']['nofollow'] ) ) {
                            $this->add_render_attribute( $header_element, 'rel', 'nofollow' );
                        }
                    }
                    if ( ! empty( $slide['item_image']['url'] ) ) :
                        $this->add_render_attribute( $element_key . '-image', [
                            'src' => $this->get_slide_image_url( $slide, $settings ),
                            'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
                        ] ); ?>
                    <<?php echo( $header_tag); ?> <?php echo( $this->get_render_attribute_string( $header_element )); ?>>
                        <img <?php echo( $this->get_render_attribute_string( $element_key . '-image' )); ?>>
                    </<?php echo( $header_tag); ?>>
                    <?php endif;
                    $item_title = '';
                    if (isset($slide['item_title']) && !empty($slide['item_title'])){
                        $this->add_render_attribute( $header_element.'-title', 'class', 'fmtpl-carousel-item-title' );
                        $item_highlight_title = (isset($slide['item_highlight_title']) && !empty($slide['item_highlight_title']))? $slide['item_highlight_title']:'';
                        $title = str_replace('%highlight%','<span class="highlight">'.$item_highlight_title.'</span>',$slide['item_title']);
                        $item_title .= '<'.$header_tag.' '.$this->get_render_attribute_string( $header_element.'-title' ).'>'.$title.'</'.$header_tag.'>';
                    }
                    $item_divider = '';
                    if (isset($settings['item_divider']) && $settings['item_divider'] == 'yes'){
                        $item_divider .= '<div class="fmtpl-divider">&nbsp;</div>';
                    }
                    $item_btn = '';
                    if ((isset($slide['item_btn_text']) && !empty($slide['item_btn_text'])) || (isset($slide['item_btn_icon']) && !empty($slide['item_btn_icon']['value']))) {
                        if (isset($slide['item_link']) && !empty($slide['item_link']['url'])) {
                            $this->add_link_attributes('item_link'.$slide['_id'], $slide['item_link']);
                            $link_attr_str = $this->get_render_attribute_string('item_link'.$slide['_id']);
                        } else {
                            $link_attr_str = 'href="#." title="'.$slide['item_btn_text'].'"';
                        }
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
                    $item_btn2 = '';
                    if ((isset($slide['item_btn_text2']) && !empty($slide['item_btn_text2'])) || (isset($slide['item_btn_icon2']) && !empty($slide['item_btn_icon2']['value']))) {
                        if (isset($slide['item_link2']) && !empty($slide['item_link2']['url'])) {
                            $this->add_link_attributes('item_link2'.$slide['_id'], $slide['item_link2']);
                            $link_attr_str2 = $this->get_render_attribute_string('item_link2'.$slide['_id']);
                        } else {
                            $link_attr_str2 = 'href="#." title="'.$slide['item_btn_text'].'"';
                        }
                        $icon_str2 = '';
                        if (isset($slide['item_btn_icon2']) && $slide['item_btn_icon2']) {
                            ob_start();
                            Icons_Manager::render_icon($slide['item_btn_icon2']);
                            $icon_str2 = ob_get_clean();
                            if ($icon_str2 != '') {
                                $icon_str2 = '<span class="fmtpl-btn-icon">' . $icon_str2 . '</span>';
                            }
                        }
                        $btn_class = isset($settings['item_btn_icon_position'])? $settings['item_btn_icon_position'] : 'right';
                        $item_btn2 = sprintf('<a class="fmtpl-button-default fmtpl-carousel-item-btn fmtpl-2nd-item-btn  %s" %s>%s<span class="fmtpl-btn-text">%s</span></a>',
                            $btn_class,
                            $link_attr_str2,
                            $icon_str2,
                            $slide['item_btn_text2']
                        );
                    }
                    if (!empty($item_title) || !empty($slide['item_sub_title'])|| !empty($slide['item_description']) ||!empty($item_btn) ||!empty($item_btn2)):
                        $box_attr = [
                            'class' => 'fmtpl-elementor_content',
                        ];
                        if (isset($settings['item_content_box_effect']) && $settings['item_content_box_effect']){
                            $box_attr['data-effect'] = $settings['item_content_box_effect'];
                            if (isset($settings['item_content_box_duration']) && $settings['item_content_box_duration']){
                                $box_attr['data-duration'] = $settings['item_content_box_duration'];
                            }
                            if (isset($settings['item_content_box_delay']) && $settings['item_content_box_delay']){
                                $box_attr['data-delay'] = $settings['item_content_box_delay'];
                            }
                        }
                        $this->add_render_attribute( $element_key.'-box', $box_attr );
                        if (!empty($item_title)){
                            $title_attr = [
                                'class' => 'fmtpl-item-title',
                            ];
                            if (isset($settings['item_title_effect']) && $settings['item_title_effect']){
                                $title_attr['data-effect'] = $settings['item_title_effect'];
                                if (isset($settings['item_title_duration']) && $settings['item_title_duration']){
                                    $title_attr['data-duration'] = $settings['item_title_duration'];
                                }
                                if (isset($settings['item_title_delay']) && $settings['item_title_delay']){
                                    $title_attr['data-delay'] = $settings['item_title_delay'];
                                }
                            }
                            $this->add_render_attribute( $element_key.'-title', $title_attr );
                        }
                        if (!empty($item_divider)){
                            $divider_attr = [
                                'class' => 'fmtpl-item-divider',
                            ];
                            if (isset($settings['item_divider_effect']) && $settings['item_divider_effect']){
                                $divider_attr['data-effect'] = $settings['item_divider_effect'];
                                if (isset($settings['item_divider_duration']) && $settings['item_divider_duration']){
                                    $divider_attr['data-duration'] = $settings['item_divider_duration'];
                                }
                                if (isset($settings['item_divider_delay']) && $settings['item_divider_delay']){
                                    $divider_attr['data-delay'] = $settings['item_divider_delay'];
                                }
                            }
                            $this->add_render_attribute( $element_key.'-divider', $divider_attr );
                        }
                        if (!empty($slide['item_sub_title'])){
                            $sub_attr = [
                                'class' => 'fmtpl-sub-title',
                            ];
                            if (isset($settings['item_sub_effect']) && $settings['item_sub_effect']){
                                $sub_attr['data-effect'] = $settings['item_sub_effect'];
                                if (isset($settings['item_sub_duration']) && $settings['item_sub_duration']){
                                    $sub_attr['data-duration'] = $settings['item_sub_duration'];
                                }
                                if (isset($settings['item_sub_delay']) && $settings['item_sub_delay']){
                                    $sub_attr['data-delay'] = $settings['item_sub_delay'];
                                }
                            }
                            $this->add_render_attribute( $element_key.'-sub', $sub_attr );
                        }
                        if (!empty($item_btn) || !empty($item_btn2)){
                            $btn_attr = [
                                'class' => 'fmtpl-btn-wrap',
                            ];
                            if (isset($settings['item_btn2_view']) && $settings['item_btn2_view']){
                                $btn_attr['class'] .= ' '.$settings['item_btn2_view'];
                            }
                            if (isset($settings['item_btn_effect']) && $settings['item_btn_effect']){
                                $btn_attr['data-effect'] = $settings['item_btn_effect'];
                                if (isset($settings['item_btn_duration']) && $settings['item_btn_duration']){
                                    $btn_attr['data-duration'] = $settings['item_btn_duration'];
                                }
                                if (isset($settings['item_btn_delay']) && $settings['item_btn_delay']){
                                    $btn_attr['data-delay'] = $settings['item_btn_delay'];
                                }
                            }
                            $this->add_render_attribute( $element_key.'-btn', $btn_attr );
                        }

                        if (!empty($slide['item_description'])){
                            $description_attr = [
                                'class' => 'fmtpl-image-desc',
                            ];
                            if (isset($settings['item_desc_effect']) && $settings['item_desc_effect']){
                                $description_attr['data-effect'] = $settings['item_desc_effect'];
                                if (isset($settings['item_desc_duration']) && $settings['item_desc_duration']){
                                    $description_attr['data-duration'] = $settings['item_desc_duration'];
                                }
                                if (isset($settings['item_desc_delay']) && $settings['item_desc_delay']){
                                    $description_attr['data-delay'] = $settings['item_desc_delay'];
                                }
                            }
                            $this->add_render_attribute( $element_key.'-description', $description_attr );
                        }
                        ?>
                        <div class="fmtpl-content-container">
                            <div <?php echo( $this->get_render_attribute_string( $element_key.'-box' )); ?>>
                                <?php if (!empty($item_title)):?>
                                    <div <?php echo( $this->get_render_attribute_string( $element_key.'-title' )); ?>>
                                        <?php echo( $item_title);?>
                                    </div>
                                <?php endif;?>
                                <?php if (!empty($item_divider)):?>
                                    <div <?php echo( $this->get_render_attribute_string( $element_key.'-divider' )); ?>>
                                        <?php echo( $item_divider);?>
                                    </div>
                                <?php endif;?>
                                <?php if (!empty($slide['item_sub_title'])):?>
                                    <div <?php echo( $this->get_render_attribute_string( $element_key.'-sub' )); ?>>
                                        <?php echo( $slide['item_sub_title']);?>
                                    </div>
                                <?php endif;?>

                                <?php if (!empty($slide['item_description'])):?>
                                    <div <?php echo( $this->get_render_attribute_string( $element_key.'-description' )); ?>>
                                        <?php echo( $slide['item_description']);?>
                                    </div>
                                <?php endif;?>
                                <?php if (!empty($item_btn) || !empty($item_btn2)):?>
                                    <div <?php echo( $this->get_render_attribute_string( $element_key.'-btn' )); ?>>
                                        <?php if (!empty($item_btn)):?>
                                            <?php echo( $item_btn);?>
                                        <?php endif;?>
                                        <?php if (!empty($item_btn2)):?>
                                            <?php echo( $item_btn2);?>
                                        <?php endif;?>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <?php
        }
        protected function get_slide_image_url( $slide, array $settings ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['item_image']['id'], 'image_size', $settings );

            if ( ! $image_url ) {
                $image_url = $slide['item_image']['url'];
            }
            return $image_url;
        }
        protected function render() {
            $settings = $this->get_active_settings();
            $slider_content = apply_filters('fmtpl-slider-widget-html','',$settings);
            if (!empty($slider_content)){
                echo( $slider_content);
            } else {
                $this->print_slider($settings);
            }
        }
        protected function print_slider( array $settings = null ) {
            if ( null === $settings ) {
                $settings = $this->get_active_settings();
            }
            $default_settings = [
                'container_class' => 'fmtpl-swiper-slide',
            ];
            if (isset($settings['slide_effect'])){
                $default_settings['container_class'] .= ' fmtpl-'.$settings['slide_effect'];
            }

            $settings = array_merge( $default_settings, $settings );
            if (!isset($settings['slides'])){
                return;
            }
            $slides_count = count( $settings['slides'] );
            $layout_class = isset($settings['layout'])? $this->get_name().'-layout-'.$settings['layout']:'';
            $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;

            ?>
            <div class="fmtpl-elementor-widget fmtpl-elementor-swiper <?php echo esc_attr( $this->get_name().' '.$layout_class);?>">
                <div class="fmtpl-elementor-swiper-slide-wrap">
                    <div class="slide_content">
                        <div class="<?php echo esc_attr( $settings['container_class'] ); ?> ">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    foreach ( $settings['slides'] as $index => $slide ) :
                                        $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $slide['_id'] );
                                    endforeach; ?>
                                </div>
                                <?php if ( 1 < $slides_count ) : ?>
                                    <?php
                                    $pagi_class = 'swiper-pagination';
                                    if (empty($settings['pagination'] )) {
                                        $pagi_class .= ' disabled';
                                    }
                                    ?>
                                    <div class="fmtpl-content-container pagination-wrap"><div class="<?php echo esc_attr( $pagi_class);?>"></div></div>
                                <?php endif; ?>
                            </div>
                            <?php if ( 1 < $slides_count  && $settings['show_arrows'] == 'yes') :
                                $sw_btn_class ='';
                                if ($settings['show_arrows_mobile'] == 'no'){
                                    $sw_btn_class .= ' hidden_on_mobile';
                                }
                                if (isset($settings['arrow_container']) && $settings['arrow_container']=='yes'):
                                ?>
                                <div class="fmtpl-content-container fmtpl-swiper-button-wrap">
                                <?php endif;?>
                                    <div class="elementor-swiper-button-wrap">
                                        <div class="elementor-swiper-button elementor-swiper-button-prev<?php echo esc_attr( $sw_btn_class);?>">
                                            <?php if (isset($settings['prev_icon']) && !empty($settings['prev_icon']['value'])):
                                                Icons_Manager::render_icon( $settings[ 'prev_icon' ]);
                                            else:?>
                                                <i class="eicon-chevron-left" aria-hidden="true"></i>
                                            <?php endif;
                                            if ($show_arrows_text && isset($settings['prev_text']) && !empty($settings['prev_text'])):?>
                                                <span><?php echo $settings['prev_text']; ?></span>
                                            <?php endif;?>
                                        </div>
                                        <div class="elementor-swiper-button elementor-swiper-button-next<?php echo esc_attr($sw_btn_class);?>">
                                            <?php if ($show_arrows_text && isset($settings['next_text']) && !empty($settings['next_text'])):?>
                                                <span><?php echo $settings['next_text']; ?></span>
                                            <?php endif;?>
                                            <?php if (isset($settings['next_icon']) && !empty($settings['next_icon']['value'])):
                                                Icons_Manager::render_icon( $settings[ 'next_icon' ]);
                                            else:?>
                                                <i class="eicon-chevron-right" aria-hidden="true"></i>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                <?php if (isset($settings['arrow_container']) && $settings['arrow_container']=='yes'):?>
                                </div>
                                <?php endif;?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
