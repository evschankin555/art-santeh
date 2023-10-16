<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Posts')){
    class Fmtpl_Carousel_Posts extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-posts';
        }

        public function get_title() {
            return __( 'Carousel Posts', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'eicon-post-slider fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'blog','post', 'carousel' ];
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
                'show_post_by' => [
                    'label' => __( 'Show post by:', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent',
                    'options' => [
                        'recent' => __( 'Recent Post', 'fami-templatekits' ),
                        'selected' => __( 'Selected Post', 'fami-templatekits' ),
                    ],
                ],
                'posts_per_page' => [
                    'label' => __( 'Item Limit', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3,
                    'dynamic' => [ 'active' => true ],
                    'condition' => [
                        'show_post_by' => [ 'recent' ]
                    ]
                ],
                'item_title' => [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => __( 'Customize Title', 'fami-templatekits' ),
                ],
                'post_id' => [
                    'label' => __( 'Select Post', 'fami-templatekits' ),
                    'label_block' => true,
                    'type' => Fmtpl_Select2::TYPE,
                    'multiple' => false,
                    'placeholder' => 'Search Post',
                    'data_options' => [
                        'post_type' => 'post',
                        'action' => 'fmtpl_post_list_query'
                    ],
                ],
                //post item
                'feature_image' => [
                    'label' => __( 'Featured Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'post_image' => [
                    'name' => 'post_image',
                    'default' => 'medium',
                    /*'exclude' => [
                        'custom'
                    ],*/
                    'condition' => [
                        'feature_image' => 'yes'
                    ]
                ],
                'category_data' => [
                    'label' => __( 'Category', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'category_icon' => [
                    'label' => __( 'Category Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [],
                    'condition' => [
                        'category_data' => 'yes',
                    ]
                ],
                'excerpt' => [
                    'label' => __( 'Show Excerpt', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'meta' => [
                    'label' => __( 'Show Meta', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'author_meta' => [
                    'label' => __( 'Author', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'condition' => [
                        'meta' => 'yes',
                    ]
                ],
                'author_icon' => [
                    'label' => __( 'Author Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [],
                    'condition' => [
                        'meta' => 'yes',
                        'author_meta' => 'yes',
                    ]
                ],
                'date_meta' => [
                    'label' => __( 'Date', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'condition' => [
                        'meta' => 'yes',
                    ]
                ],
                'date_icon' => [
                    'label' => __('Date Icon', 'fami-templatekits'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [],
                    'condition' => [
                        'meta' => 'yes',
                        'date_meta' => 'yes',
                    ]
                ],
                'comment_meta' => [
                    'label' => __( 'Comment', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'meta' => 'yes',
                    ]
                ],
                'comment_icon' => [
                    'label' => __('Comment Icon', 'fami-templatekits'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [],
                    'condition' => [
                        'meta' => 'yes',
                        'comment_meta' => 'yes',
                    ]
                ],
                'read_more' => [
                    'label' => __( 'Show Read More', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => '',
                ],
                'read_more_text' => [
                    'label' => __( 'Read More Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => 'Read More',
                    'placeholder' => __( 'Enter Read More text here.', 'fami-templatekits' ),
                    'condition' => [
                        'read_more' => 'yes',
                    ]
                ],
                'read_more_icon' => [
                    'label' => __('Read More Icon', 'fami-templatekits'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [],
                    'condition' => [
                        'read_more' => 'yes',
                    ]
                ],

                'image_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'category_typography' => [
                    'name' => 'category_typography',
                    'label' => __('Typography', 'fami-templatekits'),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .fmtpl-post-category, {{WRAPPER}} .fmtpl-post-category a',
                ],
                'category_icon_space' => [
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
                        '{{WRAPPER}} .fmtpl-post-category .category_icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'category_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-category, {{WRAPPER}} .fmtpl-post-category a' => 'color: {{VALUE}};',
                    ],
                ],
                'category_hvr_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-category:hover, {{WRAPPER}} .fmtpl-post-category a:hover' => 'color: {{VALUE}};',
                    ],
                ],
                'category_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-post-title',
                ],
                'item_title_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-carousel-item .fmtpl-post-title, {{WRAPPER}} .fmtpl-post-carousel-item .fmtpl-post-title a' => 'color: {{VALUE}}',
                    ],
                ],
                'item_title_hvr_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-carousel-item .fmtpl-post-title a:hover' => 'color: {{VALUE}}',
                    ],
                ],
                'item_title_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-carousel-item .fmtpl-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],

                'excerpt_typography' => [
                    'name' => 'excerpt_typography',
                    'label' => __('Typography', 'fami-templatekits'),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .fmtpl_post_excerpt',
                ],
                'excerpt_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_post_excerpt' => 'color: {{VALUE}};',
                    ],
                ],
                'excerpt_box_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_post_excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],

                'meta_typography' => [
                    'name' => 'meta_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .fmtpl-post-meta-wrap span',
                ],
                'meta_title_color' => [
                    'label' => __('Meta Title Color', 'fami-templatekits'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span.meta-title' => 'color: {{VALUE}};',
                    ],
                ],
                'meta_color' => [
                    'label' => __('Meta Content Color', 'fami-templatekits'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span,{{WRAPPER}} .fmtpl-post-meta-wrap .fmtpl-post-meta, {{WRAPPER}} .fmtpl-post-meta-wrap span a,{{WRAPPER}} .fmtpl-post-meta-wrap .fmtpl-post-meta a' => 'color: {{VALUE}};',
                    ],
                ],
                'meta_space' => [
                    'label' => __('Space Between', 'fami-templatekits'),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span.fmtpl-post-meta' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span.fmtpl-post-meta:last-child' => 'margin-right: 0;',
                    ],
                ],
                'meta_box_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'meta_icon_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span svg' => 'fill: {{VALUE}};',
                    ],
                ],
                'meta_icon_size' =>  [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 6,
                            'max' => 300,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span.meta_icon ' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'meta_icon_space' => [
                    'label' => __('Space Between', 'fami-templatekits'),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-meta-wrap span i, {{WRAPPER}} .fmtpl-post-meta-wrap span svg' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ],

                'read_more_padding' => [
                    'label' => __( 'Padding', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'read_more_typography' => [
                    'name' => 'read_more_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .fmtpl-post-readmore, {{WRAPPER}} .fmtpl-post-readmore a',
                ],
                'read_more_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'read_more_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore, {{WRAPPER}} .fmtpl-post-readmore a' => 'color: {{VALUE}};fill:{{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'read_more_color_hover' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore:hover, {{WRAPPER}} .fmtpl-post-readmore a:hover' => 'color: {{VALUE}};fill:{{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Schemes\Color::get_type(),
                        'value' => Schemes\Color::COLOR_1,
                    ],
                ],
                'read_more_bg_color' =>
                    [
                        'label' => __( 'Background Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-post-readmore' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'read_more_bg_color_hover' =>
                    [
                        'label' => __( 'Background Color', 'fami-templatekits' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .fmtpl-post-readmore:hover' => 'background-color: {{VALUE}};',
                        ],
                    ],
                'read_more_border' => [
                    'name' => 'read_more_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-post-readmore',
                ],

                'read_more_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],
                'read_more_border_color_hover' => [
                    'label' => __( 'Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-post-readmore:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'read_more_border_border!' => '',
                    ],
                ],
                'read_more_icon_space' =>  [
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
                        '{{WRAPPER}} .fmtpl-carousel-item-btn.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .fmtpl-carousel-item-btn.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'read_more_icon[value]',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
                'read_more_icon_size' => [
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
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'read_more_icon[value]',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
                    ],
                ],
                'read_more_icon_position' => [
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
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'read_more_icon[value]',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
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
            ];
            $this->default_control = apply_filters('fmtpl-carousel-posts-elementor-widget-control', $widget_control);
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
            /*****************************/
            $this->start_injection( [
                'at' => 'before',
                'of' => 'section_additional_options',
            ] );
            $this->start_controls_section(
                '_section_post_list',
                [
                    'label' => __( 'List', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            if (isset($this->default_control['show_post_by'])) {
                $this->add_control(
                    'show_post_by',
                    $this->default_control['show_post_by']
                );
            }
            if (isset($this->default_control['posts_per_page'])) {
                $this->add_control(
                    'posts_per_page',
                    $this->default_control['posts_per_page']
                );
            }
            $repeater_post = new Repeater();

            if (isset($this->default_control['item_title'])) {
                $repeater_post->add_control(
                    'item_title',
                    $this->default_control['item_title']
                );
            }
            if (isset($this->default_control['post_id'])) {
                $repeater_post->add_control(
                    'post_id',
                    $this->default_control['post_id']
                );
            }

            $this->add_control(
                'selected_list_post',
                [
                    'label' => '',
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater_post->get_controls(),
                    'title_field' => '{{ item_title }}',
                    'condition' => [
                        'show_post_by' => 'selected',
                    ],
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section(
                '_section_settings',
                [
                    'label' => __( 'Post Settings', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            if (isset($this->default_control['feature_image'])) {
                $this->add_control(
                    'feature_image',
                    $this->default_control['feature_image']
                );
            }
            if (isset($this->default_control['post_image'])) {
                $this->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    $this->default_control['post_image']
                );
            }
            if (isset($this->default_control['category_data'])) {
                $this->add_control(
                    'category_data',
                    $this->default_control['category_data']
                );
            }

            if (isset($this->default_control['category_icon'])) {
                $this->add_control(
                    'category_icon',
                    $this->default_control['category_icon']
                );
            }

            if (isset($this->default_control['excerpt'])) {
                $this->add_control(
                    'excerpt',
                    $this->default_control['excerpt']
                );
            }
            $this->add_control(
                'meta_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            if (isset($this->default_control['meta'])) {
                $this->add_control(
                    'meta',
                    $this->default_control['meta']
                );
            }

            if (isset($this->default_control['author_meta'])) {
                $this->add_control(
                    'author_meta',
                    $this->default_control['author_meta']
                );
            }

            if (isset($this->default_control['author_icon'])) {
                $this->add_control(
                    'author_icon',
                    $this->default_control['author_icon']
                );
            }
            if (isset($this->default_control['comment_meta'])) {
                $this->add_control(
                    'comment_meta',
                    $this->default_control['comment_meta']
                );
            }

            if (isset($this->default_control['comment_icon'])) {
                $this->add_control(
                    'comment_icon',
                    $this->default_control['comment_icon']
                );
            }
            if (isset($this->default_control['date_meta'])) {
                $this->add_control(
                    'date_meta',
                    $this->default_control['date_meta']
                );
            }

            if (isset($this->default_control['date_icon'])) {
                $this->add_control(
                    'date_icon',
                    $this->default_control['date_icon']
                );
            }
            $this->add_control(
                'read_more_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            if (isset($this->default_control['read_more'])) {
                $this->add_control(
                    'read_more',
                    $this->default_control['read_more']
                );
            }
            if (isset($this->default_control['read_more_text'])) {
                $this->add_control(
                    'read_more_text',
                    $this->default_control['read_more_text']
                );
            }
            if (isset($this->default_control['read_more_icon'])) {
                $this->add_control(
                    'read_more_icon',
                    $this->default_control['read_more_icon']
                );
            }

            $this->end_controls_section();
            $this->end_injection();

            $this->start_controls_section(
                '_section_category_style',
                [
                    'label' => __( 'Post', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_control(
                'post_heading1_1',
                [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'none',
                ]
            );
            if (isset($this->default_control['image_margin'])) {
                $this->add_responsive_control(
                    'image_margin',
                    $this->default_control['image_margin']
                );
            }
            $this->add_control(
                'post_heading1',
                [
                    'label' => __( 'Category', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['category_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['category_typography']
                );
            }
            if (isset($this->default_control['category_icon_space'])) {
                $this->add_control(
                    'category_icon_space',
                    $this->default_control['category_icon_space']
                );
            }
            //category_icon_space
            if (isset($this->default_control['category_color'],$this->default_control['category_hvr_color'])) {
                $this->add_control(
                    'category_tabs_hr',
                    [
                        'type' => Controls_Manager::DIVIDER,
                    ]
                );
                $this->start_controls_tabs('category_tabs');
                $this->start_controls_tab(
                    'category_normal_tab',
                    [
                        'label' => __('Normal', 'fami-templatekits'),
                    ]
                );


                $this->add_control(
                    'category_color',
                    $this->default_control['category_color']
                );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'category_hover_tab',
                    [
                        'label' => __('Hover', 'fami-templatekits'),
                    ]
                );

                $this->add_control(
                    'category_hvr_color',
                    $this->default_control['category_hvr_color']
                );

                $this->end_controls_tab();
                $this->end_controls_tabs();
            }

            if (isset($this->default_control['category_margin'])) {
                $this->add_responsive_control(
                    'category_margin',
                    $this->default_control['category_margin']
                );
            }
            $this->add_control(
                'post_heading2',
                [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            if (isset($this->default_control['item_title_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_title_typography']
                );
            }
            if (isset($this->default_control['item_title_color'],$this->default_control['item_title_hvr_color'])) {
                $this->add_control(
                    'item_title_tabs_hr',
                    [
                        'type' => Controls_Manager::DIVIDER,
                    ]
                );
                $this->start_controls_tabs('item_title_tabs');
                $this->start_controls_tab(
                    'item_title_normal_tab',
                    [
                        'label' => __('Normal', 'fami-templatekits'),
                    ]
                );


                $this->add_control(
                    'item_title_color',
                    $this->default_control['item_title_color']
                );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'item_title_hover_tab',
                    [
                        'label' => __('Hover', 'fami-templatekits'),
                    ]
                );

                $this->add_control(
                    'item_title_hvr_color',
                    $this->default_control['item_title_hvr_color']
                );

                $this->end_controls_tab();
                $this->end_controls_tabs();
            }

            if (isset($this->default_control['item_title_margin'])) {
                $this->add_responsive_control(
                    'item_title_margin',
                    $this->default_control['item_title_margin']
                );
            }
            $this->add_control(
                'post_heading3',
                [
                    'label' => __( 'Excerpt', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            if (isset($this->default_control['excerpt_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['excerpt_typography']
                );
            }

            if (isset($this->default_control['excerpt_color'])) {
                $this->add_control(
                    'excerpt_color',
                    $this->default_control['excerpt_color']
                );
            }

            if (isset($this->default_control['excerpt_box_margin'])) {
                $this->add_responsive_control(
                    'excerpt_box_margin',
                    $this->default_control['excerpt_box_margin']
                );
            }
            $this->add_control(
                'post_heading4',
                [
                    'label' => __( 'Meta', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if (isset($this->default_control['meta_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['meta_typography']
                );
            }

            if (isset($this->default_control['meta_title_color'])) {
                $this->add_control(
                    'meta_title_color',
                    $this->default_control['meta_title_color']
                );
            }
            if (isset($this->default_control['meta_color'])) {
                $this->add_control(
                    'meta_color',
                    $this->default_control['meta_color']
                );
            }

            if (isset($this->default_control['meta_space'])) {
                $this->add_responsive_control(
                    'meta_space',
                    $this->default_control['meta_space']
                );
            }

            if (isset($this->default_control['meta_box_margin'])) {
                $this->add_responsive_control(
                    'meta_box_margin',
                    $this->default_control['meta_box_margin']
                );
            }

            $this->add_responsive_control(
                'meta_icon_heading',
                [
                    'label' => __( 'Meta Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            if (isset($this->default_control['meta_icon_color'])) {
                $this->add_control(
                    'meta_icon_color',
                    $this->default_control['meta_icon_color']
                );
            }
            if (isset($this->default_control['meta_icon_size'])) {
                $this->add_responsive_control(
                    'meta_icon_size',
                    $this->default_control['meta_icon_size']
                );
            }

            if (isset($this->default_control['meta_icon_space'])) {
                $this->add_responsive_control(
                    'meta_icon_space',
                    $this->default_control['meta_icon_space']
                );
            }
            $this->end_controls_section();

            $this->start_controls_section(
                '_section_readmore_style',
                [
                    'label' => __( 'Read More', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'read_more!' => ''
                    ]
                ]
            );
            if (isset($this->default_control['read_more_padding'])) {
                $this->add_responsive_control(
                    'read_more_padding',
                    $this->default_control['read_more_padding']
                );
            }
            if (isset($this->default_control['read_more_margin'])) {
                $this->add_responsive_control(
                    'read_more_margin',
                    $this->default_control['read_more_margin']
                );
            }
            if (isset($this->default_control['read_more_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['read_more_typography']
                );
            }
            $this->start_controls_tabs( 'read_more_tabs' );
            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['read_more_color'])) {
                $this->add_control(
                    'read_more_color',
                    $this->default_control['read_more_color']
                );
            }
            if (isset($this->default_control['read_more_bg_color'])) {
                $this->add_control(
                    'read_more_bg_color',
                    $this->default_control['read_more_bg_color']
                );
            }

            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );

            if (isset($this->default_control['read_more_color_hover'])) {
                $this->add_control(
                    'read_more_color_hover',
                    $this->default_control['read_more_color_hover']
                );
            }
            if (isset($this->default_control['read_more_bg_color_hover'])) {
                $this->add_control(
                    'read_more_bg_color_hover',
                    $this->default_control['read_more_bg_color_hover']
                );
            }

            $this->end_controls_tab();
            $this->end_controls_tabs();

            if (isset($this->default_control['read_more_border'])) {
                $this->add_control(
                    'read_more_border_heading',
                    [
                        'label' => __('Border Style', 'fami-templatekits'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    $this->default_control['read_more_border']
                );
            }
            if (isset($this->default_control['read_more_border_color_hover'])) {
                $this->add_control(
                    'read_more_border_color_hover',
                    $this->default_control['read_more_border_color_hover']
                );
            }
            if (isset($this->default_control['read_more_border_radius'])) {
                $this->add_responsive_control(
                    'read_more_border_radius',
                    $this->default_control['read_more_border_radius']
                );
            }

            $this->add_control(
                'read_more_tabs_hr_1',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->add_control(
                'read_more_icon_options',
                [
                    'label' => __( 'Icon Options', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'read_more_icon[value]',
                                'operator' => '!=',
                                'value' => '',
                            ],
                        ],
                    ],
                ]
            );
            if (isset($this->default_control['read_more_icon_space'])) {
                $this->add_responsive_control(
                    'read_more_icon_space',
                    $this->default_control['read_more_icon_space']
                );
            }
            if (isset($this->default_control['read_more_icon_size'])) {
                $this->add_responsive_control(
                    'read_more_icon_size',
                    $this->default_control['read_more_icon_size']
                );
            }

            if (isset($this->default_control['read_more_icon_position'])) {
                $this->add_control(
                    'read_more_icon_position',
                    $this->default_control['read_more_icon_position']
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
            //$settings['widget_name'] = $this->get_name();
            $args = [
                'post_status' => 'publish',
                'post_type' => 'post',
            ];
            if ( 'recent' === $settings['show_post_by'] ) {
                $args['posts_per_page'] = $settings['posts_per_page'];
            }
            $customize_title = [];
            $ids = [];
            if ( 'selected' === $settings['show_post_by'] ) {
                $args['posts_per_page'] = -1;
                $lists = $settings['selected_list_post'];
                if ( ! empty( $lists ) ) {
                    foreach ( $lists as $index => $value ) {
                        $ids[] = $value['post_id'];
                        if ( $value['item_title'] ) $customize_title[$value['post_id']] = $value['item_title'];
                    }
                }
                $args['post__in'] = (array) $ids;
                $args['orderby'] = 'post__in';
            }

            $settings['customize_title'] = $customize_title;
            if ( 'selected' === $settings['show_post_by'] && empty( $ids ) ) {
                $posts = [];
            } else {
                $posts = get_posts( $args );
            }
            $post_count = count($posts);
            $html = '';
            if ($post_count > 0){
                $settings['post_count'] = $post_count;
                if ((isset($settings['read_more_icon']) && !empty($settings['read_more_icon']['value']))) {
                    $read_more_icon_str = '';
                    if (isset($settings['read_more_icon']) && $settings['read_more_icon']) {
                        ob_start();
                        Icons_Manager::render_icon( $settings[ 'read_more_icon' ]);
                        $read_more_icon_str = ob_get_clean();
                        if ($read_more_icon_str != '') {
                            $read_more_icon_str = '<span class="fmtpl-btn-icon">'.$read_more_icon_str.'</span>';
                        }
                    }
                    $read_more_class = isset($settings['read_more_icon_position'])? $settings['read_more_icon_position'] : 'left';
                    $settings['read_more_icon_str'] = $read_more_icon_str;
                    $settings['read_more_class'] = $read_more_class;
                }
                if (isset($settings['author_icon']) && $settings['author_icon']){
                    ob_start();
                    Icons_Manager::render_icon($settings['author_icon'], ['aria-hidden' => 'true']);
                    $settings['author_icon_str'] = sprintf('<span class="meta_icon author_icon">%s</span>',
                        ob_get_clean()
                    );
                }
                if (isset($settings['date_icon']) && $settings['date_icon'] ) {
                    ob_start();
                    Icons_Manager::render_icon($settings['date_icon'], ['aria-hidden' => 'true']);
                    $settings['date_icon_str'] = sprintf('<span class="meta_icon date_icon">%s</span>',
                        ob_get_clean()
                    );
                }
                if (isset($settings['category_icon']) && $settings['category_icon'] && $settings['category_icon']['value'] ) {
                    ob_start();
                    Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
                    $settings['category_icon_str'] = sprintf('<span class="meta_icon category_icon">%s</span>',
                        ob_get_clean()
                    );
                }
                if (isset($settings['comment_icon']) && $settings['comment_icon'] && $settings['comment_icon']['value'] ) {
                    ob_start();
                    Icons_Manager::render_icon( $settings['comment_icon'], [ 'aria-hidden' => 'true' ] );
                    $settings['comment_icon_str'] = sprintf('<span class="meta_icon comment_icon">%s</span>',
                        ob_get_clean()
                    );
                }

                if ( $settings['prev_icon'] ) {
                    ob_start();
                    Icons_Manager::render_icon( $settings['prev_icon'], [ 'aria-hidden' => 'true' ] );
                    $settings['prev_icon_str'] = ob_get_clean();
                }
                if ( $settings['next_icon'] ) {
                    ob_start();
                    Icons_Manager::render_icon( $settings['next_icon'], [ 'aria-hidden' => 'true' ] );
                    $settings['next_icon_str'] = ob_get_clean();
                }

                $html = apply_filters('fmtpl-carousel-posts-widget-html',$html,$settings,$posts);

                if (!empty($html)){
                    return $html;
                }
                $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
                $html = '<div class="fmtpl-elementor-widget fmtpl-post-list fmtpl-post-layout-'.$layout.' carousel">';
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

                if ($content_html != '') {
                    $html .= '<div class="fmtpl-carousel-box-heading">'.$content_html.'</div>';
                }

                $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper"><div class="swiper-container">';
                $html .= '<div class="swiper-wrapper">';
                $slide_html = apply_filters('fmtpl-post-carousel-item-html','',$posts,$settings);
                if (empty($slide_html)) {
                    $element_key = 'fmtpl-post-item';
                    ob_start();
                    $this->print_slide($posts,$settings,$element_key);
                    $slide_html = ob_get_clean();
                }
                $html .= $slide_html;
                $html .= '</div>';
                if ( 1 < $post_count ) {
                    $pagi_class = 'swiper-pagination';
                    if (empty($settings['pagination'] )) {
                        $pagi_class .= ' disabled';
                    }
                    $html .= '<div class="'.$pagi_class.'"></div>';
                }
                $html .= '</div>';// close swiper-container
                if ( 1 < $post_count ) {
                    $navi_html = '';
                    if ( $settings['show_arrows'] == 'yes') {
                        $sw_btn_class ='';
                        if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                            $sw_btn_class .= ' hidden_on_mobile';
                        }
                        $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;
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

                    if (!empty($navi_html)){
                        $html .= $navi_html;
                    }
                }
                $html .='</div></div></div>';
            }
            return $html;
        }

        protected function add_repeater_controls(Repeater $repeater)
        {
        }

        protected function get_repeater_defaults()
        {
        }

        protected function print_slide(array $posts, array $settings, $element_key)
        {
            $customize_title = isset($settings['customize_title'])? $settings['customize_title']:[];
            $read_more_icon_str = isset($settings['read_more_icon_str'])? $settings['read_more_icon_str']:'';
            $read_more_class = isset($settings['read_more_class'])? $settings['read_more_class']:'';
            $post_image_size = $settings['post_image_size'];
            if ($post_image_size == 'custom' && isset($settings['post_image_custom_dimension'],$settings['post_image_custom_dimension']['width'],$settings['post_image_custom_dimension']['height'])){
                $post_image_size = [$settings['post_image_custom_dimension']['width'],$settings['post_image_custom_dimension']['height']];
            }
            foreach ($posts as $post):
                $post_link = get_the_permalink( $post->ID );
                ?>
                <div class="swiper-slide">
                    <div class="fmtpl-post-carousel-item">
                        <div class="fmtpl-post-thumb">
                            <a href="<?php echo esc_url( $post_link ); ?>">
                                <?php if ( 'yes' === $settings['feature_image'] ):
                                    if (has_post_thumbnail($post)):
                                        echo get_the_post_thumbnail( $post->ID, $post_image_size );
                                    else:?>
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP88B8AAuUB8e2ujYwAAAAASUVORK5CYII=" alt="<?php the_title(); ?>" />
                                    <?php endif;
                                endif; ?>
                            </a>
                        </div>
                        <div class="fmtpl-post-content">
                            <?php if ('yes' === $settings['category_data'] ):
                                $categories = get_the_category( $post->ID );
                                if (!empty($categories)):?>
                                    <div class="fmtpl-post-category">
                                        <a href="<?php echo get_category_link($categories[0]);?>" title="<?php echo $categories[0]->name;?>">
                                            <?php if (isset($settings['category_icon_str']) && !empty($settings['category_icon_str'])) {
                                                echo $settings['category_icon_str'];
                                            }
                                            echo esc_html( $categories[0]->name ); ?>
                                        </a>
                                    </div>
                                <?php endif;
                            endif;
                            $title = $post->post_title;
                            if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID, $customize_title ) ) {
                                $title = $customize_title[$post->ID];
                            }

                            printf( '<h3 %1$s><a href="%2$s">%3$s</a></h3>',
                                'class="fmtpl-post-title"',
                                esc_url( $post_link ),
                                esc_html( $title )
                            );

                            if ('yes' == $settings['excerpt']) {
                                printf( '<div class="fmtpl_post_excerpt">%1$s</div>',
                                    $post->post_excerpt
                                );
                            } ?>
                            <?php if ( isset($settings['read_more'],$settings['read_more_text']) && $settings['read_more']):?>
                                <span class="fmtpl-post-readmore">
                                    <?php
                                    printf('<a class="fmtpl-button-default fmtpl-carousel-item-btn %s" href="%s" title="%s">%s %s</a>',
                                        $read_more_class,
                                        esc_url( $post_link ),
                                        esc_html( $title ),
                                        $read_more_icon_str,
                                        $settings['read_more_text']
                                    );?>
                                </span>
                            <?php endif; ?>
                            <?php if ( 'yes' === $settings['meta'] ): ?>
                                <div class="fmtpl-post-meta-wrap">
                                    <?php if (isset($settings['author_meta']) && 'yes' === $settings['author_meta'] ):
                                        $author_link = get_the_author_meta( 'url', $post->post_author  );
                                        ?>
                                        <a class="fmtpl-post-meta fmtpl-post-author" href="<?php echo esc_url($author_link);?>">
                                            <?php if (isset($settings['author_icon_str']) && !empty($settings['author_icon_str'])){
                                                echo $settings['author_icon_str'];
                                            }?>
                                            <span class="meta-title">
                                                <?php esc_html_e('by:','fami-templatekits');?>
                                            </span>
                                            <?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( 'yes' === $settings['date_meta'] ): ?>
                                        <span class="fmtpl-post-meta fmtpl-post-date">
                                            <?php if (isset($settings['date_icon_str']) && !empty($settings['date_icon_str'])){
                                                echo $settings['date_icon_str'];
                                            }
                                            echo apply_filters('fmtpl_post_date_format_html',get_the_date( "M d, Y" ,$post),$post);
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (isset($settings['comment_meta']) && 'yes' === $settings['comment_meta'] && !post_password_required() && comments_open($post->ID) ): ?>
                                        <?php
                                        $comments_number = get_comments_number_text(false, __('1 Comment', 'fami-templatekits'), __('% Comments', 'fami-templatekits'), $post->ID);
                                        printf(
                                            '<span class="fmtpl-post-meta fmtpl-post-comments">%s%s</span>',
                                            (isset($settings['comment_icon_str'])  && !empty($settings['comment_icon_str']))? $settings['comment_icon_str']:'',//icon string
                                            $comments_number
                                        );
                                        ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        }
    }
}

