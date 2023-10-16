<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('Fmtpl_Carousel_Base')) {
    abstract class Fmtpl_Carousel_Base extends Widget_Base {
        private $slide_prints_count = 0;
        protected $enable_heading = true;

        public function get_categories() {
            return [ 'fami-elements' ];
        }
        abstract protected function add_repeater_controls( Repeater $repeater );

        abstract protected function get_repeater_defaults();

        abstract protected function print_slide( array $slide, array $settings, $element_key );

        protected function _register_controls() {
            if ($this->enable_heading){
                $this->start_controls_section(
                    'section_heading',
                    [
                        'label' => __('Heading', 'fami-templatekits'),
                    ]
                );
                $this->add_control(
                    'title_text',
                    [
                        'label' => __( 'Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                        'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                        'label_block' => true,
                    ]
                );
                $this->add_control(
                    'highlight_title',
                    [
                        'label' => __( 'Highlight Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => __( 'Highlight', 'fami-templatekits' ),
                        'placeholder' => __( 'Enter your Highlight title', 'fami-templatekits' ),
                        'label_block' => true,
                    ]
                );
                $this->add_control(
                    'show_divider',
                    [
                        'label' => __( 'Show Divider', 'fami-templatekits' ),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __( 'Show', 'fami-templatekits' ),
                        'label_off' => __( 'Hide', 'fami-templatekits' ),
                        'default' => 'no',
                    ]
                );
                $this->add_control(
                    'description',
                    [
                        'label' => __( 'Description', 'fami-templatekits' ),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => '',
                        'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
                    ]
                );
                $this->end_controls_section();
                $this->start_controls_section(
                    '_section_heading_style',
                    [
                        'label' => __( 'Heading', 'fami-templatekits' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );
                $this->add_responsive_control(
                    'content_alignment',
                    [
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
                            '{{WRAPPER}} .fmtpl-carousel-box-heading' => 'text-align: {{VALUE}}',
                        ],
                    ]
                );
                $this->add_control(
                    '_title_heading',
                    [
                        'label' => __( 'Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_responsive_control(
                    'title_bottom_space',
                    [
                        'label' => __( 'Spacing', 'fami-templatekits' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_control(
                    'title_color',
                    [
                        'label' => __( 'Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title' => 'color: {{VALUE}};',
                        ],
                        'scheme' => [
                            'type' => Schemes\Color::get_type(),
                            'value' => Schemes\Color::COLOR_1,
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'title_typography',
                        'selector' => '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title',
                        'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    ]
                );
                $this->add_control(
                    '_highlight_title_heading',
                    [
                        'label' => __( 'Highlight Title', 'fami-templatekits' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_control(
                    'highlight_title_color',
                    [
                        'label' => __( 'Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title .highlight' => 'color: {{VALUE}};',
                        ],
                        'scheme' => [
                            'type' => Schemes\Color::get_type(),
                            'value' => Schemes\Color::COLOR_1,
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'highlight_title_typography',
                        'selector' => '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-title .highlight',
                        'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    ]
                );
                $this->add_control(
                    '_desc_heading',
                    [
                        'label' => __( 'Description', 'fami-templatekits' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_responsive_control(
                    'desc_bottom_space',
                    [
                        'label' => __( 'Spacing', 'fami-templatekits' ),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_control(
                    'desc_color',
                    [
                        'label' => __( 'Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-desc' => 'color: {{VALUE}};',
                        ],
                        'scheme' => [
                            'type' => Schemes\Color::get_type(),
                            'value' => Schemes\Color::COLOR_1,
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'desc_typography',
                        'selector' => '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-carousel-box-desc',
                        'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    ]
                );
                $this->end_controls_section();
                $this->start_controls_section(
                    '_section_divider_style',
                    [
                        'label' => __( 'Divider', 'fami-templatekits' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'divider_width',
                    [
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
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'divider_height',
                    [
                        'label' => __( 'Height', 'fami-templatekits' ),
                        'type' => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 20,
                                'step' => 1,
                            ],
                        ],
                        'default' => [
                            'unit' => 'px',
                            'size' => 2,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_control(
                    'divider_color',
                    [
                        'label' => __( 'Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider' => 'background-color: {{VALUE}};',
                        ],
                        'default' => '#000000',
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_control(
                    'divider_border_heading',
                    [
                        'label' => __('Border Style', 'fami-templatekits'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'divider_border_type',
                        'label' => __( 'Border', 'fami-templatekits' ),
                        'selector' => '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider',
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'divider_border_radius',
                    [
                        'label' => __( 'Border Radius', 'fami-templatekits' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'divider_margin',
                    [
                        'label' => __( 'Margin', 'fami-templatekits' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-carousel-box-heading .fmtpl-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'condition' => [
                            'show_divider' => 'yes'
                        ]
                    ]
                );
                $this->end_controls_section();
            }
            $repeater = new Repeater();
            $this->add_repeater_controls( $repeater );

            if (sizeof($repeater->get_controls())> 1) {
                $this->start_controls_section(
                    'section_slides',
                    [
                        'label' => __( 'Slides', 'fami-templatekits' ),
                        'tab' => Controls_Manager::TAB_CONTENT,
                    ]
                );
                $this->add_control(
                    'slides',
                    [
                        'label' => __( 'Slides', 'fami-templatekits' ),
                        'type' => Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => $this->get_repeater_defaults(),
                        'separator' => 'after',
                    ]
                );
                $this->end_controls_section();
            }
            $this->start_controls_section(
                'section_additional_options',
                [
                    'label' => __( 'Carousel Options', 'fami-templatekits' ),
                ]
            );
            $slides_per_view = range( 1, 10 );
            $slides_per_view = array_combine( $slides_per_view, $slides_per_view );

            $this->add_responsive_control(
                'slides_per_view',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => __( 'Slides Per View', 'fami-templatekits' ),
                    'options' => $slides_per_view,
                    'default' => 4,
                    'frontend_available' => true,
                ]
            );

            $this->add_responsive_control(
                'slides_to_scroll',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => __( 'Slides to Scroll', 'fami-templatekits' ),
                    'description' => __( 'Set how many slides are scrolled per swipe.', 'fami-templatekits' ),
                    'default' => 1,
                    'options' => $slides_per_view,
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'show_arrows',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => __( 'Arrows', 'fami-templatekits' ),
                    'default' => 'yes',
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'frontend_available' => true,
                ]
            );
            $this->add_control(
                'show_arrows_mobile',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => __( 'Arrow on Mobile', 'fami-templatekits' ),
                    'default' => 'no',
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'frontend_available' => true,
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'show_arrows_text',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => __( 'Arrows Text', 'fami-templatekits' ),
                    'default' => 'no',
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'pagination',
                [
                    'label' => __( 'Pagination', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'bullets',
                    'options' => [
                        '' => __( 'None', 'fami-templatekits' ),
                        'bullets' => __( 'Dots', 'fami-templatekits' ),
                        'fraction' => __( 'Fraction', 'fami-templatekits' ),
                        'progressbar' => __( 'Progress', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'elementor-pagination-type-',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'speed',
                [
                    'label' => __( 'Transition Duration', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 500,
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'autoplay',
                [
                    'label' => __( 'Autoplay', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'separator' => 'before',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'autoplay_speed',
                [
                    'label' => __( 'Autoplay Speed', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5000,
                    'condition' => [
                        'autoplay' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'loop',
                [
                    'label' => __( 'Infinite Loop', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'pause_on_interaction',
                [
                    'label' => __( 'Pause on Interaction', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'condition' => [
                        'autoplay' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $this->add_responsive_control(
                'space_between',
                [
                    'label' => __( 'Space Between', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 100,
                        ],
                    ],
                    'desktop_default' => [
                        'size' => 10,
                    ],
                    'tablet_default' => [
                        'size' => 10,
                    ],
                    'mobile_default' => [
                        'size' => 10,
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                    'separator' => 'before',
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                '_section_arrow_icon',
                [
                    'label' => __( 'Navigation Arrow', 'fami-templatekits' ),
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'prev_icon',
                [
                    'label' => __( 'Previous Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'prev_text',
                [
                    'label' => __( 'Previous Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default'  => __( 'Previous', 'fami-templatekits' ),
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'show_arrows',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'show_arrows_text',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
                ]
            );
            $this->add_control(
                'next_icon',
                [
                    'label' => __( 'Next Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'next_text',
                [
                    'label' => __( 'Next Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default'  => __( 'Next', 'fami-templatekits' ),
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'show_arrows',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'show_arrows_text',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
                ]
            );
            $this->end_controls_section();

            $this->start_controls_section(
                'section_slides_style',
                [
                    'label' => __( 'Slides', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .swiper-slide',
                    'fields_options' => [
                        'background' => [
                            'label' => __( 'Background', 'fami-templatekits' ),
                        ],
                    ],
                ]
            );
            $this->add_control(
                'item_alignment',
                [
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
                        '{{WRAPPER}} .swiper-slide' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'slide_padding',
                [
                    'label' => __( 'Padding', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'slide_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .swiper-slide',
                ]
            );
            $this->add_responsive_control(
                'slide_border_radius',
                [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_section();

            $this->start_controls_section(
                'section_navigation',
                [
                    'label' => __( 'Navigation Arrows', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'arrows_width',
                [
                    'label' => __( 'Width', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 120,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_responsive_control(
                'arrows_height',
                [
                    'label' => __( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 120,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'arrows_size',
                [
                    'label' => __( 'Icon Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 20,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .elementor-swiper-button svg' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'arrows_space',
                [
                    'label' => __( 'Icon Space', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev > span' => 'margin-left: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next > span' => 'margin-right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'show_arrows_text' => 'yes',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'arrows_typography',
                    'selector' => '{{WRAPPER}} .elementor-swiper-button > span',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    'condition' => [
                        'show_arrows_text' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'arrow_tabs_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->start_controls_tabs( 'arrow_tabs' );
            $this->start_controls_tab(
                'arrow_tab_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),

                ]
            );
            $this->add_control(
                'arrows_color',
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementor-swiper-button svg' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'arrows_bg_color',
                [
                    'label' => __( 'Background', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab();
            $this->start_controls_tab(
                'arrow_tab_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );

            $this->add_control(
                'arrows_color_hover',
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .elementor-swiper-button:hover svg' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'arrows_bg_color_hover',
                [
                    'label' => __( 'Background', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_control(
                'arrows_border_headding',
                [
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'arrow_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .elementor-swiper-button',
                ]
            );

            $this->add_control(
                'arrow_border_color_hover',
                [
                    'label' => __( 'Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'arrow_border_border!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'arrow_border_radius',
                [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'arrow_left_margin',
                [
                    'label' => __( 'Arrow Left Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_responsive_control(
                'arrow_right_margin',
                [
                    'label' => __( 'Arrow Right Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_arrows' => 'yes',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'arrow_box_shadow',
                    'label' => __( 'Box Shadow', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .elementor-swiper-button',
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'section_pagination',
                [
                    'label' => __( 'Pagination', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'pagination_position',
                [
                    'label' => __( 'Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'outside',
                    'options' => [
                        'outside' => __( 'Outside', 'fami-templatekits' ),
                        'inside' => __( 'Inside', 'fami-templatekits' ),
                    ],
                    'prefix_class' => 'elementor-pagination-position-',
                    'condition' => [
                        'pagination!' => '',
                    ],
                ]
            );

            $this->add_control(
                'pagination_size',
                [
                    'label' => __( 'Width', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'pagination!' => '',
                    ],
                ]
            );
            $this->add_control(
                'pagination_size_height',
                [
                    'label' => __( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );
            $this->add_responsive_control(
                'pagination_space',
                [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
                        '{{WRAPPER}} .swiper-container-vertical .swiper-pagination-bullets .swiper-pagination-bullet:not(:last-child)' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );
            $this->start_controls_tabs( 'pagi_tabs' );
            $this->start_controls_tab(
                'pagi_tab_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),

                ]
            );
            $this->add_control(
                'pagination_color',
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullet, {{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'pagination!' => '',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'pagi_tab_active',
                [
                    'label' => __( 'Active', 'fami-templatekits' ),
                ]
            );
            $this->add_control(
                'pagination_color_active',
                [
                    'label' => __( 'Active Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
                    ],
                    'condition' => [
                        'pagination!' => '',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'pagi_border_type',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );
            $this->add_control(
                'pagi_border_active_color',
                [
                    'label' => __( 'Border Active Color', 'auros-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
                    ],
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'pagination',
                                'operator' => '==',
                                'value' => 'bullets',
                            ],
                            [
                                'name' => 'pagi_border_type_border',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
                    ],
                ]
            );
            $this->add_responsive_control(
                'pagi_border_radius',
                [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-elementor-main-swiper .swiper-pagination.swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .swiper-pagination.swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'default' => [
                        'top' => 50,
                        'right' => 50,
                        'bottom' => 50,
                        'left' => 50,
                        'unit' => '%',
                    ],
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'pagi_shadow',
                    'label' => __( 'Box Shadow', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );
            $this->add_responsive_control(
                'pagi_margin',
                [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ]
            );
            $this->end_controls_section();
            $update_control = apply_filters('fmtpl-carousel-base-widget-control', []);
            if (!empty($update_control)){
                foreach ($update_control as $u_k => $u_e){
                    $this->update_control($u_k,$u_e);
                }
            }
        }

        protected function print_slider( array $settings = null ) {
            if ( null === $settings ) {
                $settings = $this->get_active_settings();
            }
            $default_settings = apply_filters('fmtpl_carousel_element_default_settings', [
                'container_class' => 'fmtpl-elementor-main-swiper',
            ]);
            $settings = array_merge( $default_settings, $settings );
            if (!isset($settings['slides'])){
                return;
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
            $layout_class = isset($settings['layout'])? $this->get_name().'-layout-'.$settings['layout']:'';
            $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;
            ?>
            <div class="fmtpl-elementor-widget fmtpl-elementor-swiper <?php echo $this->get_name().' '.$layout_class;?>">
                <?php if (!empty($content_html)):?>
                    <div class="fmtpl-carousel-box-heading">
                        <?php echo $content_html;?>
                    </div>
                <?php endif;?>
                <div class="<?php echo esc_attr( $settings['container_class'] ); ?> ">
                    <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ( $settings['slides'] as $index => $slide ) :
                            $this->slide_prints_count++;
                            ob_start();
                            $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count );
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
                    <?php if (1 < $slides_count && $settings['show_arrows'] == 'yes' ) :
                        $sw_btn_class ='';
                        if ($settings['show_arrows_mobile'] == 'no'){
                            $sw_btn_class .= ' hidden_on_mobile';
                        }
                        ?>
                        <div class="elementor-swiper-button-wrap fmtpl-carousel-navigation-wrapper">
                            <div class="elementor-swiper-button elementor-swiper-button-prev<?php echo $sw_btn_class;?>">
                                <?php if (isset($settings['prev_icon']) && !empty($settings['prev_icon']['value'])):
                                    Icons_Manager::render_icon( $settings[ 'prev_icon' ]);
                                else:?>
                                    <i class="eicon-chevron-left" aria-hidden="true"></i>
                                <?php endif;
                                if ($show_arrows_text && isset($settings['prev_text']) && !empty($settings['prev_text'])):?>
                                    <span><?php echo $settings['prev_text']; ?></span>
                                <?php endif;?>
                            </div>
                            <div class="elementor-swiper-button elementor-swiper-button-next<?php echo $sw_btn_class;?>">
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
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        protected function render() {
            $settings = $this->get_active_settings();
            $this->print_slider($settings);
        }
    }
}
