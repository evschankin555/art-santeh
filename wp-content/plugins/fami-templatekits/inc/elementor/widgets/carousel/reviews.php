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
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Reviews')){
    class Fmtpl_Carousel_Reviews extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-reviews';
        }

        public function get_title() {
            return __( 'Carousel Reviews', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'eicon-review fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'reviews', 'rating', 'testimonial', 'carousel' ];
        }

        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $this->add_style_depends('fmtpl-reviews');
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
                'image_size' => [
                    'name' => 'image_size',
                    'default' => 'thumbnail',
                ],
                'show_date' => [
                    'label' => __( 'Show Date', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'fami-templatekits' ),
                    'label_off' => __( 'Hide', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'no',
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
                'item_content' => [
                    'label' => __( 'Content', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXTAREA,
                ],
                'star_rating' =>
                [
                    'label' => __( 'Star', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 5,
                    'step' => 0.1,
                    'default' => 5,
                ],
                'due_date' => [
                    'label' => __( 'Date', 'fami-templatekits' ),
                    'type' => Controls_Manager::DATE_TIME,
                    'default' => date( 'Y-m-d H:i', strtotime( '+0 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                    /* translators: %s: Time zone. */
                    'description' => sprintf( __( 'Date set according to your timezone: %s.', 'fami-templatekits' ), Utils::get_timezone_string() ),
                ],
                'item_image' => [
                    'label'             => __('Image','fami-templatekits'),
                    'type'              => Controls_Manager::MEDIA,
                    'dynamic'       => [ 'active' => true ],
                    'default'      => [
                        'url'   => Utils::get_placeholder_image_src()
                    ],
                    'description'       => __( 'Choose an image for the author', 'fami-templatekits' ),
                    'show_label'        => true,
                ],
                'item_name' => [
                    'label'             => __('Name', 'fami-templatekits'),
                    'type'              => Controls_Manager::TEXT,
                    'default'           => 'John Doe',
                    'dynamic'           => [ 'active' => true ],
                    'label_block'       => true
                ],
                'item_caption' => [
                    'label'             => __('Caption', 'fami-templatekits'),
                    'type'              => Controls_Manager::TEXT,
                    'default'           => '',
                ],
                'item_title' => [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'CEO', 'fami-templatekits' ),
                ],
                'item_image_width' => [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'item_image_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_image_border' => [
                    'name' => 'item_image_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-reviews-image img',
                ],
                'item_image_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],

                'item_content_typography' => [
                    'name' => 'item_content_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-reviews-content',
                ],
                'item_content_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-content' => 'color: {{VALUE}}',
                    ],
                ],
                'item_content_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_name_typography' => [
                    'name' => 'item_name_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-reviews__name',
                ],
                'item_name_color' => [
                    'label' => __( 'Color', 'fmtpl-reviews__name' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__name' => 'color: {{VALUE}}',
                    ],
                ],
                'item_name_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                //item_caption
                'item_caption_typography' => [
                    'name' => 'item_caption_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-reviews-caption',
                ],
                'item_caption_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-caption' => 'color: {{VALUE}}',
                    ],
                ],
                'item_caption_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-reviews__title',
                ],
                'item_title_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__title' => 'color: {{VALUE}}',
                    ],
                ],
                'item_title_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'unmarked_star_style' => [
                    'label' => __( 'Unmarked Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'solid' => [
                            'title' => __( 'Solid', 'fami-templatekits' ),
                            'icon' => 'fa fa-star',
                        ],
                        'outline' => [
                            'title' => __( 'Outline', 'fami-templatekits' ),
                            'icon' => 'fa fa-star-o',
                        ],
                    ],
                    'default' => 'solid',
                ],
                'star_size' => [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'star_space' => [
                    'label' => __( 'Spacing', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
                        'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'stars_color' => [
                    'label' => __('Color', 'fami-templatekits'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
                    ],
                ],
                'stars_unmarked_color' => [
                    'label' => __('Unmarked Color', 'fami-templatekits'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
                    ],
                ],
                'stars_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews-item .elementor-star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_date_typography' => [
                    'name' => 'item_date_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-reviews__date',
                ],
                'item_date_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__date' => 'color: {{VALUE}}',
                    ],
                ],
                'item_date_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-reviews__date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'review_order' => [
                    'label' => __( 'Review Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-reviews .fmtpl-reviews-item .elementor-star-rating' => 'order: {{VALUE}};',
                    ],
                ],
                'caption_order' => [
                    'label' => __( 'Caption Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-reviews .fmtpl-reviews-item .fmtpl-reviews-caption' => 'order: {{VALUE}};',
                    ],
                ],
                'content_order' => [
                    'label' => __( 'Content Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-reviews .fmtpl-reviews-item .fmtpl-reviews-content' => 'order: {{VALUE}};',
                    ],
                ],
                'author_order' => [
                    'label' => __( 'Author Order', 'fami-templatekits' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-carousel-reviews .fmtpl-reviews-item .fmtpl-reviews-author' => 'order: {{VALUE}};',
                    ],
                ],
            ];
            $this->default_control = apply_filters('fmtpl-carousel-reviews-elementor-widget-control', $widget_control);
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
            if (isset($this->default_control['image_size'])) {
                $this->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    $this->default_control['image_size']
                );
            }
            if (isset($this->default_control['show_date'])) {
                $this->add_control(
                   'show_date',
                    $this->default_control['show_date']
                );
            }
            //show_date
            $this->end_controls_section();
            $this->end_injection();

            /*****************************/
            $repeater = new Repeater();
            $this->add_repeater_controls($repeater);

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

            $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __( 'Slide Content', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_control(
                'item_heading_10',
                [
                    'label' => __( 'Author', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['author_order'])) {
                $this->add_control(
                    'author_order',
                    $this->default_control['author_order']
                );
            }
            $this->add_control(
                'item_heading_1',
                [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_image_width'])){
                $this->add_responsive_control(
                    'item_image_width',
                    $this->default_control['item_image_width']
                );
            }
            if (isset($this->default_control['item_image_margin'])){
                $this->add_responsive_control(
                    'item_image_margin',
                    $this->default_control['item_image_margin']
                );
            }
            if (isset($this->default_control['item_image_border'])){
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    $this->default_control['item_image_border']
                );
            }
            if (isset($this->default_control['item_image_border_radius'])){
                $this->add_responsive_control(
                    'item_image_border_radius',
                    $this->default_control['item_image_border_radius']
                );
            }

            $this->add_control(
                'item_heading_2',
                [
                    'label' => __( 'Name', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_name_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_name_typography']
                );
            }
            if (isset($this->default_control['item_name_color'])){
                $this->add_control(
                    'item_name_color',
                    $this->default_control['item_name_color']
                );
            }
            if (isset($this->default_control['item_name_margin'])){
                $this->add_responsive_control(
                    'item_name_margin',
                    $this->default_control['item_name_margin']
                );
            }
            $this->add_control(
                'item_heading_3',
                [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_title_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_title_typography']
                );
            }
            if (isset($this->default_control['item_title_color'])){
                $this->add_control(
                    'item_title_color',
                    $this->default_control['item_title_color']
                );
            }
            if (isset($this->default_control['item_title_margin'])){
                $this->add_responsive_control(
                    'item_title_margin',
                    $this->default_control['item_title_margin']
                );
            }
            $this->add_control(
                'item_heading_4',
                [
                    'label' => __( 'Date', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_date_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_date_typography']
                );
            }
            if (isset($this->default_control['item_date_color'])){
                $this->add_control(
                    'item_date_color',
                    $this->default_control['item_date_color']
                );
            }
            if (isset($this->default_control['item_date_margin'])){
                $this->add_responsive_control(
                    'item_date_margin',
                    $this->default_control['item_date_margin']
                );
            }

            $this->add_control(
                'item_heading_5_1',
                [
                    'label' => __( 'Caption', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['caption_order'])){
                $this->add_control(
                    'caption_order',
                    $this->default_control['caption_order']
                );
            }
            if (isset($this->default_control['item_caption_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_caption_typography']
                );
            }
            if (isset($this->default_control['item_caption_color'])){
                $this->add_control(
                    'item_caption_color',
                    $this->default_control['item_caption_color']
                );
            }
            if (isset($this->default_control['item_caption_margin'])){
                $this->add_responsive_control(
                    'item_caption_margin',
                    $this->default_control['item_caption_margin']
                );
            }

            $this->add_control(
                'item_heading_5',
                [
                    'label' => __( 'Content Box', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['content_order'])){
                $this->add_control(
                    'content_order',
                    $this->default_control['content_order']
                );
            }

            if (isset($this->default_control['item_content_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_content_typography']
                );
            }
            if (isset($this->default_control['item_content_color'])){
                $this->add_control(
                    'item_content_color',
                    $this->default_control['item_content_color']
                );
            }
            if (isset($this->default_control['item_content_margin'])){
                $this->add_responsive_control(
                    'item_content_margin',
                    $this->default_control['item_content_margin']
                );
            }

            $this->add_control(
                'item_heading_6',
                [
                    'label' => __( 'Rating', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['review_order'])) {
                $this->add_control(
                    'review_order',
                    $this->default_control['review_order']
                );
            }
            if (isset($this->default_control['unmarked_star_style'])) {
                $this->add_control(
                    'unmarked_star_style',
                    $this->default_control['unmarked_star_style']
                );
            }

            if (isset($this->default_control['star_size'])) {
                $this->add_control(
                    'star_size',
                    $this->default_control['star_size']
                );
            }

            if (isset($this->default_control['star_space'])) {
                $this->add_control(
                    'star_space',
                    $this->default_control['star_space']
                );
            }

            if (isset($this->default_control['stars_color'])) {
                $this->add_control(
                    'stars_color',
                    $this->default_control['stars_color']
                );
            }
            if (isset($this->default_control['stars_unmarked_color'])) {
                $this->add_control(
                    'stars_unmarked_color',
                    $this->default_control['stars_unmarked_color']
                );
            }
            if (isset($this->default_control['stars_margin'])){
                $this->add_responsive_control(
                    'stars_margin',
                    $this->default_control['stars_margin']
                );
            }
            $this->end_controls_section();
            if (isset($this->default_control['title_color'])){
                $this->update_control('title_color',
                    $this->default_control['title_color']
                );
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
            $html = '';
            $slides_count = isset($settings['slides'])? count( $settings['slides'] ) : 0;
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
            $element_key = 'fmtpl-reviews-item';
            ob_start();
            $this->print_slide($settings['slides'],$settings,$element_key);
            $slide_html = ob_get_clean();
            $html = apply_filters('fmtpl-carousel-reviews-widget-html',$html,$settings,$slide_html);
            if (!empty($html)){
                return $html;
            }
            $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
            $show_arrows_text = (isset($settings['show_arrows_text']) && $settings['show_arrows_text'] == 'yes') ? true: false;
            $html = '<div class="fmtpl-elementor-widget fmtpl-carousel-reviews fmtpl-reviews-layout-'.$layout.' carousel elementor--star-style-star_unicode">';
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

            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper">';

            $html .= '<div class="swiper-container"><div class="swiper-wrapper">';


            $html .= $slide_html;

            $html .= '</div><!-- close swiper-wrapper-->';

            if ( 1 < $slides_count ) {
                    $pagi_class = 'swiper-pagination';
                if (empty($settings['pagination'] )) {
                    $pagi_class .= ' disabled';
                    }
                    $html .= '<div class="'.$pagi_class.'"></div>';
                    $html .= '</div><!-- close swiper-container-->';
                if ( $settings['show_arrows'] == 'yes') {
                    $html .= '<div class="fmtpl-carousel-navigation-wrapper">';
                    $sw_btn_class ='';
                    if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                        $sw_btn_class .= ' hidden_on_mobile';
                    }
                    $html .= '<div class="elementor-swiper-button elementor-swiper-button-prev'.$sw_btn_class.'">';
                    if (isset($settings['prev_icon_str'])){
                        $html .= $settings['prev_icon_str'];
                    } else {
                        $html .= '<i class="eicon-chevron-left" aria-hidden="true"></i>';
                    }
                    if ($show_arrows_text && isset($settings['prev_text']) && !empty($settings['prev_text'])){
                        $html .='<span>'.$settings['prev_text'].'</span>';
                    }
                    $html .='</div>';//close elementor-swiper-button-prev

                    $html .= '<div class="elementor-swiper-button elementor-swiper-button-next'.$sw_btn_class.'">';
                    if ($show_arrows_text && isset($settings['next_text']) && !empty($settings['next_text'])){
                        $html .='<span>'.$settings['next_text'].'</span>';
                    }
                    if (isset($settings['next_icon_str'])){
                        $html .= $settings['next_icon_str'];
                    } else {
                        $html .= '<i class="eicon-chevron-right" aria-hidden="true"></i>';
                    }
                    $html .='</div>';//close elementor-swiper-button-next
                    $html .='</div>';// close fmtpl-carousel-navigation-wrapper
                }
            }
            $html .='</div><!-- close swiper-container--></div><!-- close fmtpl-elementor-swiper--></div><!-- close fmtpl-carousel-reviews-->';
            return $html;
        }

        protected function add_repeater_controls(Repeater $repeater)
        {
            $this->define_widget_control();
            if (isset($this->default_control['item_caption'])) {
                $repeater->add_control(
                    'item_caption',
                    $this->default_control['item_caption']
                );
            }
            if (isset($this->default_control['item_content'])) {
                $repeater->add_control(
                    'item_content',
                    $this->default_control['item_content']
                );
            }
            if (isset($this->default_control['star_rating'])) {
                $repeater->add_control(
                    'star_rating',
                    $this->default_control['star_rating']
                );
            }
            if (isset($this->default_control['due_date'])) {
                $repeater->add_control(
                    'due_date',
                    $this->default_control['due_date']
                );
            }
            if (isset($this->default_control['item_image'])) {
                $repeater->add_control(
                    'item_image',
                    $this->default_control['item_image']
                );
            }
            if (isset($this->default_control['item_name'])) {
                $repeater->add_control(
                    'item_name',
                    $this->default_control['item_name']
                );
            }
            if (isset($this->default_control['item_title'])) {
                $repeater->add_control(
                    'item_title',
                    $this->default_control['item_title']
                );
            }
        }

        protected function get_repeater_defaults() {
            $placeholder_image_src = Utils::get_placeholder_image_src();

            return [
                [
                    'item_caption' => '',
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_caption' => '',
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_caption' => '',
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_caption' => '',
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
            ];
        }

        protected function get_slide_image_url( $slide, array $settings ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['item_image']['id'], 'image_size', $settings );
            if ( ! $image_url ) {
                $image_url = $slide['item_image']['url'];
            }
            return $image_url;
        }

        protected function render_stars( $slide, $settings ) {
            $icon = '&#9733;';
            if ( 'outline' === $settings['unmarked_star_style'] ) {
                $icon = '&#9734;';
            }
            $rating = ((float)$slide['star_rating']) > 5 ? 5 : (float)$slide['star_rating'];
            $floored_rating = (int) $rating;
            $stars_html = '';
            for ( $stars = 1; $stars <= 5; $stars++ ) {
                if ( $stars <= $floored_rating ) {
                    $stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
                } elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
                    $stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
                } else {
                    $stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
                }
            }

            return '<div class="elementor-star-rating">' . $stars_html . '</div>';
        }

        protected function print_slide(array $items, array $settings, $element_key)
        {
            $i = 0;
            foreach ( $settings['slides'] as $index => $slide ) :
                $i++;
                $element_key = 'slide-' . $index . '-' . $i;
                if ( ! empty( $slide['item_image']['url'] ) ) {
                    $this->add_render_attribute( $element_key . '-image', [
                        'src' => $this->get_slide_image_url( $slide, $settings ),
                        'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
                    ] );
                }
                $slide['show_date'] = $settings['show_date'];
                $slide['layout'] = $settings['layout'];
                $slide['star_html'] = $this->render_stars($slide,$settings);
                $slide['img_attr_str'] = $this->get_render_attribute_string( $element_key . '-image' );
                $item_html = apply_filters('fmtpl-carousel-reviews-item-html','',$slide,$settings);
                if (!empty($item_html)):
                    printf('<div class="swiper-slide">%s</div>',$item_html);
                else: ?>
                    <div class="swiper-slide">
                        <div class="fmtpl-reviews-item">
                            <?php echo $slide['star_html'];?>
                            <div class="fmtpl-reviews-caption">
                                <?php echo $slide['item_caption'];?>
                            </div>
                            <div class="fmtpl-reviews-content">
                                <?php echo $slide['item_content'];?>
                            </div>
                            <div class="fmtpl-reviews-author">
                                <?php
                                 if ( !empty($slide['img_attr_str'] )) : ?>
                                    <div class="fmtpl-reviews-image">
                                        <img <?php echo $slide['img_attr_str']; ?>>
                                    </div>
                                <?php endif;?>
                                <div class="fmtpl-reviews-detail">
                                    <div class="fmtpl-reviews-title-wrap">
                                    <?php if ( ! empty( $slide['item_name'] ) ) :?>
                                        <div class="fmtpl-reviews__name"><?php echo $slide['item_name'];?></div>
                                    <?php endif;
                                    if ( ! empty( $slide['item_title'] ) ) :?>
                                        <div class="fmtpl-reviews__title"><?php echo $slide['item_title'];?></div>
                                    <?php endif; ?>
                                    </div>
                                    <?php
                                    //show_date
                                    if ( isset($settings['show_date'],$slide['due_date']) && $settings['show_date'] == 'yes' && ! empty($slide['due_date'])) :?>
                                        <div class="fmtpl-reviews__date"><?php echo $slide['due_date'];?> </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif;
            endforeach;
        }
    }
}

