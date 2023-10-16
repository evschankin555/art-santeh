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
require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Images')){
    class Fmtpl_Carousel_Images extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-images';
        }

        public function get_title() {
            return __( 'Carousel Images', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'fmtpl-carousel-image fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'image', 'gallery', 'carousel' ];
        }
        public function define_widget_control() {
            $widget_control = [
                'layout' => [
                    'label' => __('Layout', 'fami-templatekits'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __('Default', 'fami-templatekits'),
                    ],
                    'style_transfer' => true,
                ],
                //slide item
                'image' => [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ],
                'item_title' =>
                    [
                        'label' => __( 'Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => '',
                        'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                        'label_block' => true,
                    ],
                'item_highlight_title' =>
                    [
                        'label' => __( 'Highlight Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => '',
                        'placeholder' => __( 'Enter your Highlight title', 'fami-templatekits' ),
                        'label_block' => true,
                    ],
                'item_description' => [
                    'label' => __( 'Description', 'fami-templatekits' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => '',
                    'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
                ],
                'item_link' => [
                    'label' => __( 'Link', 'fami-templatekits' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://your-link.com', 'fami-templatekits' ),
                    'show_external' => true,
                    'dynamic' => [
                        'active' => true,
                    ]
                ],
                'item_btn_text' => [
                    'label' => __( 'Text', 'fami-templatekits' ),
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
                'image_size' => [
                    'name' => 'image_size',
                    'default' => 'full',
                    'separator' => 'before',
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
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
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
                'item_title_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title, {{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title .fmtpl-carousel-item-title:before' => 'background-color: {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title:hover, {{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title:hover a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title:hover .fmtpl-carousel-item-title:before' => 'background-color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title, {{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title a',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],
                'item_highlight_title_color' => [
                    'label' => __( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title .highlight' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],

                'item_highlight_title_color_hover' => [
                    'label' => __( 'Highlight Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title:hover .highlight' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],

                'item_highlight_title_typography' => [
                    'label' => __( 'Highlight Typography', 'fami-templatekits' ),
                    'name' => 'item_highlight_title_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-item-title .highlight',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                ],

                'item_desc_bottom_space' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-image-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'item_desc_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-image-desc' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_3,
                    ],
                ],
                'item_desc_typography' => [
                    'name' => 'item_desc_typography',
                    'selector' => '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-image-desc',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                ],

                'image_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
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
                        '{{WRAPPER}} .fmtpl-carousel-images .fmtpl-elementor_image ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                    'label' => __( 'Border Color', 'fami-templatekits' ),
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
            $this->default_control = apply_filters('fmtpl-carousel-images-elementor-widget-control', $widget_control);
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

            if (isset($this->default_control['image_size'])) {
                $this->start_injection( [
                    'at' => 'before',
                    'of' => 'slides_per_view',
                ] );
                $this->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    $this->default_control['image_size']
                );
                $this->end_injection();
            }

            $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __( 'Slide Content', 'fami-templatekits' ),
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
            //
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
            $this->add_control(
                'item_heading_3',
                [
                    'label' => __( 'Item Description', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if (isset($this->default_control['item_desc_bottom_space'])) {
                $this->add_responsive_control(
                    'item_desc_bottom_space',
                    $this->default_control['item_desc_bottom_space']
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
            if (isset($this->default_control['item_title'])) {
                $repeater->add_control(
                    'item_title',
                    $this->default_control['item_title']
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
            if (isset($this->default_control['item_description'])) {
                $repeater->add_control(
                    'item_description',
                    $this->default_control['item_description']
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
            $slide_html = apply_filters('fmtpl-carousel-images-item-html','',$slide, $settings,$element_key);
            if (!empty($slide_html)){
                echo $slide_html;
                return;
            }
            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'elementor-fmtpl-image-carousel',
            ] );

            $this->add_render_attribute( $element_key . '-fmtpl-image-carousel', [
                'class' => 'elementor-repeater-item-' . $slide['_id'],
            ] );

            if ( ! empty( $slide['image']['url'] ) ) {
                $this->add_render_attribute( $element_key . '-image', [
                    'src' => $this->get_slide_image_url( $slide, $settings ),
                    'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
                ] );
            } ?>
        <div <?php echo $this->get_render_attribute_string( $element_key . '-fmtpl-image-carousel' ); ?>>
            <?php
            $link_url = empty( $slide['item_link']['url'] ) ? false : $slide['item_link']['url'];
            $header_tag = ! empty( $link_url ) ? 'a' : 'div';
            $header_element = 'header_' . $slide['_id'];
            $this->add_render_attribute( $header_element, 'class', 'fmtpl-elementor_header' );
            if ( ! empty( $link_url ) ) {
                $this->add_render_attribute( $header_element, 'href', $link_url );
                if ( $slide['item_link']['is_external'] ) {
                    $this->add_render_attribute( $header_element, 'target', '_blank' );
                }
                if ( ! empty( $slide['item_link']['nofollow'] ) ) {
                    $this->add_render_attribute( $header_element, 'rel', 'nofollow' );
                }
            }
            ?>
            <?php if ( $slide['image']['url'] || ! empty( $slide['name'] ) || ! empty( $slide['title'] ) ) :
                if ( $slide['image']['url'] ) : ?>
                    <div class="fmtpl-elementor_image">
                    <<?php echo $header_tag; ?> <?php echo $this->get_render_attribute_string( $header_element ); ?>>
                    <img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
                    </<?php echo $header_tag; ?>>
                    </div>
                <?php endif;
            endif;
            $item_title = '';
            if (isset($slide['item_title']) && !empty($slide['item_title'])){
                $this->remove_render_attribute($header_element,'class');
                $this->add_render_attribute( $header_element, 'class', 'fmtpl-carousel-item-title' );
                $item_highlight_title = (isset($slide['item_highlight_title']) && !empty($slide['item_highlight_title']))? $slide['item_highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$item_highlight_title.'</span>',$slide['item_title']);
                $item_title .= '<'.$header_tag.' '.$this->get_render_attribute_string( $header_element ).'>'.$title.'</'.$header_tag.'>';
            }
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
            if (!empty($item_title) || !empty($slide['item_description']) ||!empty($item_btn)):?>
                <div class="fmtpl-elementor_content">
                    <?php if (!empty($item_title)):?>
                        <div class="fmtpl-item-title">
                            <?php echo  $item_title;?>
                        </div>
                    <?php endif;?>
                    <?php if (!empty($slide['item_description'])):?>
                        <div class="fmtpl-image-desc">
                            <?php echo $slide['item_description'];?>
                        </div>
                    <?php endif;?>
                    <?php if (!empty($item_btn)):?>
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
            $slider_content = apply_filters('fmtpl-carousel-images-widget-html','',$settings);
            if (!empty($slider_content)){
                echo $slider_content;
            } else {
                $this->print_slider($settings);
            }
        }
    }
}

