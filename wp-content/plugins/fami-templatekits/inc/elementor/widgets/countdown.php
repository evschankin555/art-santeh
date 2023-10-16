<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;
class Fmtpl_Countdown extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'fmtpl-countdown';
    }

    public function get_title()
    {
        return __('Countdown', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-countdown fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'countdown', 'number', 'timer', 'time', 'date','event' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $script_depends = ['jquery-countdown'];
        foreach ($script_depends as $script){
            $this->add_script_depends($script);
        }
        $this->add_style_depends('fmtpl-countdown');
        $this->add_style_depends('fmtpl-banner');
    }

    private function define_widget_control()
    {
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
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'overlay' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'overlay_hover' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown:hover .fmtpl-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'due_date' => [
                'label' => __( 'Date', 'fami-templatekits' ),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
                /* translators: %s: Time zone. */
                'description' => sprintf( __( 'Date set according to your timezone: %s.', 'fami-templatekits' ), Utils::get_timezone_string() ),
            ],
            'image' => [
                'label' => __( 'Choose Image', 'fami-templatekits' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ],
            'thumbnail' => [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
            ],

            'title_text' =>
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'highlight_title' =>
            [
                'label' => __( 'Highlight Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Highlight', 'fami-templatekits' ),
                'placeholder' => __( 'Enter your Highlight title', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'description' => [
                'label' => __( 'Description', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
            ],
            'text_alignment' => [
                'label' => __( 'Alignment', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'text-align: {{VALUE}}',
                ],
            ],

            'button_text' => [
                'label' => __( 'Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'content_alignment' => [
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
                'prefix_class' => 'fmtpl-content-align-',
            ],
            'background_color' =>
            [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-countdown' => 'justify-content: {{VALUE}}',
                ],
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
                    '{{WRAPPER}} .fmtpl-countdown' => 'align-items: {{VALUE}}',
                ],

            ],
            'content_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'content_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'border' => [
                'name' => 'border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content',
            ],

            'border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-banner-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'box_shadow' => [
                'name' => 'box_shadow',
                'label' => __( 'Box Shadow', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .wrapper',
            ],

            'title_bottom_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],

            'desc_bottom_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .countdown-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .countdown-desc' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .fmtpl-countdown .countdown-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
            ],
            'btn_position' => [
                'label' => __('Button Position', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __('Before Countdown', 'fami-templatekits'),
                    'after' => __('After Countdown', 'fami-templatekits'),
                ],
                'selectors_dictionary' => [
                    'before' => 'order: 1',
                    'after' => 'order: initial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-time' => '{{VALUE}};',
                ],
            ],

            'btn_bottom_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'width: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'color: {{VALUE}};fill:  {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button:hover' => 'color: {{VALUE}}; fill:  {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_border_border!' => '',
                ],
            ],
            'btn_icon_options' => [
                'label' => __( 'Icon Options', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'button_icon[value]',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
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
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'button_icon[value]',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
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
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'button_icon[value]',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
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
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'button_icon[value]',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ],
            'btn_text_options' => [
                'label' => __( 'Button Text', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'button_text',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ],
            'btn_text_typography' => [
                'name' => 'btn_text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-countdown .fmtpl-btn-text',
            ],
            'show_labels' => [
                'label' => __( 'Show Label', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'default' => 'yes',
                'separator' => 'before',
            ],
            'label_display' => [
                'label' => __( 'View', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => __( 'Block', 'fami-templatekits' ),
                    'inline' => __( 'Inline', 'fami-templatekits' ),
                ],
                'default' => 'block',
                'prefix_class' => 'fmtpl-countdown-label-',
                'condition' => [
                    'show_labels!' => '',
                ],
            ],
            'custom_labels' => [
                'label' => __( 'Custom Label', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'show_labels!' => '',
                ],
            ],
            'label_days' => [
                'label' => __( 'Days', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Days', 'fami-templatekits' ),
                'placeholder' => __( 'Days', 'fami-templatekits' ),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                ],
            ],
            'label_hours' => [
                'label' => __( 'Hours', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Hours', 'fami-templatekits' ),
                'placeholder' => __( 'Hours', 'fami-templatekits' ),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                ],
            ],
            'label_minutes' => [
                'label' => __( 'Minutes', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Minutes', 'fami-templatekits' ),
                'placeholder' => __( 'Minutes', 'fami-templatekits' ),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                ],
            ],
            'label_seconds' => [
                'label' => __( 'Seconds', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Seconds', 'fami-templatekits' ),
                'placeholder' => __( 'Seconds', 'fami-templatekits' ),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                ],
            ],
            'countdown_alignment' => [
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
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .box-count' => 'justify-content: {{VALUE}}',
                    '{{WRAPPER}}.fmtpl-countdown-label-block .box-count' => 'align-items: {{VALUE}}',
                ],
                'default' => 'center',
            ],
            'countdown_bottom_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-countdown .fmtpl-countdown-time' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'container_width' => [
                'label' => __( 'Container Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .box-count' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'box_background_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .box-count' => 'background-color: {{VALUE}};',
                ],
            ],
            'box_border' => [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .box-count',
                'separator' => 'before',
            ],
            'box_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .box-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'box_spacing' => [
                'label' => __( 'Space Between', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .box-count:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .box-count:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                ],
            ],
            'box_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .box-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'digits_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .box-count .num' => 'color: {{VALUE}};',
                ],
            ],
            'digits_typography' => [
                'name' => 'digits_typography',
		'selector' => '{{WRAPPER}} .box-count .num',
		'scheme' => Schemes\Typography::TYPOGRAPHY_3,
	    ],
            'digits_width' => [
                'label' => __( 'Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .box-count .num' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'digits_height' => [
                'label' => __( 'Height', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .box-count .num' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'digits_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .box-count .num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'digits_position' => [
                'label' => __( 'Digits Position', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after'  => __( 'After Label', 'fami-templatekits' ),
                    'before' => __( 'Before Label', 'fami-templatekits' ),
                ],
                'prefix_class' => 'fmtpl-digits-position-',
            ],
            'label_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .box-count .text' => 'color: {{VALUE}};',
                ],
            ],
            'label_typography' => [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .box-count .text',
                'scheme' => Schemes\Typography::TYPOGRAPHY_2,
            ]
        ];
        $this->default_control = apply_filters('fmtpl-countdown-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_layout', ['label' => __('Layout', 'fami-templatekits')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
        }
        $this->end_controls_section();

        $this->start_controls_section(
            '_section_image',
            [
                'label' => __('Banner', 'fami-templatekits'),
            ]
        );
        if (isset($this->default_control['image'])) {
            $this->add_control(
                'image',
                $this->default_control['image']
            );
        }
        if (isset($this->default_control['thumbnail'])) {
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                $this->default_control['thumbnail']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_info',
            [
                'label' => __('Content Box', 'fami-templatekits'),
            ]
        );
        if (isset($this->default_control['title_text'])) {
            $this->add_control(
                'title_text',
                $this->default_control['title_text']
            );
        }
        if (isset($this->default_control['highlight_title'])) {
            $this->add_control(
                'highlight_title',
                $this->default_control['highlight_title']
            );
        }
        if (isset($this->default_control['description'])) {
            $this->add_control(
                'description',
                $this->default_control['description']
            );
        }


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
        //button_icon
        $this->end_controls_section();
        if (isset($this->default_control['due_date'])) {
            $this->start_controls_section(
                '_section_date',
                [
                    'label' => __( 'Countdown', 'fami-templatekits' ),
                ]
            );
            $this->add_control(
                'due_date',
                $this->default_control['due_date']
            );
        }

        if (isset($this->default_control['show_labels'])) {
            $this->add_control(
                'show_labels',
                $this->default_control['show_labels']
            );
        }

        if (isset($this->default_control['custom_labels'])) {
            $this->add_control(
                'custom_labels',
                $this->default_control['custom_labels']
            );
        }

        if (isset($this->default_control['label_days'])) {
            $this->add_control(
                'label_days',
                $this->default_control['label_days']
            );
        }

        if (isset($this->default_control['label_hours'])) {
            $this->add_control(
                'label_hours',
                $this->default_control['label_hours']
            );
        }

        if (isset($this->default_control['label_minutes'])) {
            $this->add_control(
                'label_minutes',
                $this->default_control['label_minutes']
            );
        }

        if (isset($this->default_control['label_seconds'])) {
            $this->add_control(
                'label_seconds',
                $this->default_control['label_seconds']
            );
        }
        $this->end_controls_section();

        $this->start_controls_section(
            '_section_layout_style',
            [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['height'])) {
            $this->add_responsive_control(
                'height',
                $this->default_control['height']
            );
        }

        //overlay
        if (isset($this->default_control['overlay']) && isset($this->default_control['overlay_hover'])) {
            $this->add_control(
                'overlay_heading',
                [
                    'label' => __('Overlay', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->start_controls_tabs( 'layout_tabs' );
            $this->start_controls_tab(
                'tab_layout_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            $this->add_control(
                'overlay',
                $this->default_control['overlay']
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_layout_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );
            $this->add_control(
                'overlay_hover',
                $this->default_control['overlay_hover']
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Content Box', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['content_alignment'])) {
            $this->add_control(
                'content_alignment',
                $this->default_control['content_alignment']
            );
        }
        //background_color
        if (isset($this->default_control['background_color'])) {
            $this->add_control(
                'background_color',
                $this->default_control['background_color']
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
        if (isset($this->default_control['horizontal_position'])) {
            $this->add_control(
                'position_heading',
                [
                    'label' => __('Position', 'fami-templatekits'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'horizontal_position',
                $this->default_control['horizontal_position']
            );
        }
        if (isset($this->default_control['vertical_position'])) {
            $this->add_control(
                'vertical_position',
                $this->default_control['vertical_position']
            );
        }

        if (isset($this->default_control['content_margin'])) {
            $this->add_control(
                'margin_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->add_responsive_control(
                'content_margin',
                $this->default_control['content_margin']
            );
        }
        if (isset($this->default_control['content_padding'])) {
            $this->add_responsive_control(
                'content_padding',
                $this->default_control['content_padding']
            );
        }
        //border
        if (isset($this->default_control['border'])) {
            $this->add_control(
                'border',
                [
                    'label' => __('Border Style', 'fami-templatekits'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['border']
            );
        }
        if (isset($this->default_control['border_radius'])) {
            $this->add_responsive_control(
                'border_radius',
                $this->default_control['border_radius']
            );
        }
        if (isset($this->default_control['box_shadow'])) {
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                $this->default_control['box_shadow']
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_title_style',
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['title_bottom_space'])) {
            $this->add_responsive_control(
                'title_bottom_space',
                $this->default_control['title_bottom_space']
            );
        }
        if (isset($this->default_control['title_color'])) {
            $this->add_control(
                'title_color',
                $this->default_control['title_color']
            );
        }

        if (isset($this->default_control['title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['title_typography']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_highlight_title_style',
            [
                'label' => __( 'HighLight Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['highlight_title_color'])) {
            $this->add_control(
                'highlight_title_color',
                $this->default_control['highlight_title_color']
            );
        }
        if (isset($this->default_control['highlight_title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['highlight_title_typography']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_desc_style',
            [
                'label' => __( 'Description', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['desc_bottom_space'])) {
            $this->add_responsive_control(
                'desc_bottom_space',
                $this->default_control['desc_bottom_space']
            );
        }
        if (isset($this->default_control['desc_color'])) {
            $this->add_control(
                'desc_color',
                $this->default_control['desc_color']
            );
        }

        if (isset($this->default_control['desc_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['desc_typography']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_btn_style',
            [
                'label' => __( 'Button', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['btn_position'])) {
            $this->add_responsive_control(
                'btn_position',
                $this->default_control['btn_position']
            );
        }
        if (isset($this->default_control['btn_bottom_space'])) {
            $this->add_responsive_control(
                'btn_bottom_space',
                $this->default_control['btn_bottom_space']
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

        $this->end_controls_tab();
        $this->end_controls_tabs();
        if (isset($this->default_control['btn_border'])) {
            $this->add_control(
                'btn_border_heading',
                [
                    'label' => __('Border Style', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['btn_border']
            );
        }
        if (isset($this->default_control['btn_border_color_hover'])) {
            $this->add_control(
                'btn_border_color_hover',
                $this->default_control['btn_border_color_hover']
            );
        }
        if (isset($this->default_control['btn_border_radius'])) {
            $this->add_responsive_control(
                'btn_border_radius',
                $this->default_control['btn_border_radius']
            );
        }


        if (isset($this->default_control['btn_icon_options'])) {
            $this->add_control(
                'btn_icon_options',
                $this->default_control['btn_icon_options']
            );
        }

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
        $this->start_controls_section(
            'section_countdown_style',
            [
                'label' => __( 'Countdown', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //countdown_alignment
        if (isset($this->default_control['countdown_alignment'])) {
            $this->add_control(
                'countdown_alignment',
                $this->default_control['countdown_alignment']
            );
        }
        if (isset($this->default_control['label_display'])) {
            $this->add_control(
                'label_display',
                $this->default_control['label_display']
            );
        }
        if (isset($this->default_control['countdown_bottom_space'])) {
            $this->add_responsive_control(
                'countdown_bottom_space',
                $this->default_control['countdown_bottom_space']
            );
        }
        if (isset($this->default_control['container_width'])) {
            $this->add_responsive_control(
                'container_width',
                $this->default_control['container_width']
            );
        }

        if (isset($this->default_control['box_background_color'])) {
            $this->add_control(
                'box_background_color',
                $this->default_control['box_background_color']
            );
        }

        if (isset($this->default_control['box_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['box_border']
            );
        }

        if (isset($this->default_control['box_border_radius'])) {
            $this->add_control(
                'box_border_radius',
                $this->default_control['box_border_radius']
            );
        }

        if (isset($this->default_control['box_spacing'])) {
            $this->add_responsive_control(
                'box_spacing',
                $this->default_control['box_spacing']
            );
        }
        if (isset($this->default_control['box_padding'])) {
            $this->add_responsive_control(
                'box_padding',
                $this->default_control['box_padding']
            );
        }

        $this->add_control(
            'heading_digits',
            [
                'label' => __('Digits', 'fami-templatekits'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        if (isset($this->default_control['digits_position'])) {
            $this->add_control(
                'digits_position',
                $this->default_control['digits_position']
            );
        }

        if (isset($this->default_control['digits_color'])) {
            $this->add_control(
                'digits_color',
                $this->default_control['digits_color']
            );
        }

        if (isset($this->default_control['digits_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['digits_typography']
            );
        }
        if (isset($this->default_control['digits_width'])) {
            $this->add_responsive_control(
                'digits_width',
                $this->default_control['digits_width']
            );
        }
        if (isset($this->default_control['digits_height'])) {
            $this->add_responsive_control(
                'digits_height',
                $this->default_control['digits_height']
            );
        }

        if (isset($this->default_control['digits_margin'])) {
            $this->add_responsive_control(
                'digits_margin',
                $this->default_control['digits_margin']
            );
        }



        $this->add_control(
            'heading_label',
            [
                'label' => __( 'Label', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        if (isset($this->default_control['heading_label'])) {
            $this->add_control(
                'heading_label',
                $this->default_control['heading_label']
            );
        }

        if (isset($this->default_control['label_color'])) {
            $this->add_control(
                'label_color',
                $this->default_control['label_color']
            );
        }

        if (isset($this->default_control['label_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['label_typography']
            );
        }

        $this->end_controls_section();
    }

    protected function render()
    {
        echo $this->fmtpl_render();
    }
    protected function fmtpl_render() {
        $settings = $this->get_settings_for_display();
        $html = '<div class="fmtpl-elementor-widget fmtpl-banner fmtpl-countdown '.$settings['layout'].'">';
        $image_html = '';
        $style_str = '';
        if ( ! empty( $settings['image']['url'] ) ) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['image']['id'], 'thumbnail', $settings);
            if (empty($image_src)) {
                $image_src = $settings['image']['url'];
            }
            $style_str = ' style="background-image: url(' . $image_src . ');"';

            $this->add_render_attribute('image', 'src', $settings['image']['url']);
            $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['image']));
            $this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['image']));
            $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image');
            $html .= '<div class="fmtpl-banner-background zoom" '.$style_str.'></div>';
        }

        //$html .= $style_str . '>';
        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="fmtpl-banner-overlay"></div>';
        }
        if ($image_html != ''){
            if ((!isset($settings['button_text']) && !isset($settings['button_icon'])) ||
                (empty($settings['button_text']) && empty($settings['button_icon']['value']))) {
                // don't have button;
                $link_attr_str = '';

                if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
                    $this->add_link_attributes( 'link', $settings['link'] );
                    $link_attr_str =  $this->get_render_attribute_string( 'link' );
                }
                if ($link_attr_str != ''){
                    $html .= '<figure class="fmtpl-banner-box-img has-link"><a class="banner-img-link" '.$link_attr_str.'>' . $image_html . '</a></figure>';
                } else {
                    $html .= '<figure class="fmtpl-banner-box-img">' . $image_html . '</figure>';
                }
                //$content_html .= '<a class="fmtpl-button-default fmtpl-banner-button '.$btn_class.'" ' . $link_attr_str . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';

            } else {
                $html .= '<figure class="fmtpl-banner-box-img">' . $image_html . '</figure>';
            }
        }

        $content_html = '';

        if (isset($settings['title_text'])) {
            $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-widget-title fmtpl-countdown-title ">'.$title.'</div>';
        }

        if (isset($settings['description']) && !empty($settings['description'])){
            $content_html .= '<div class="countdown-desc">'.$settings['description'].'</div>';
        }

        if (isset($settings['due_date'])){
            //content_alignment
            $cd_class = isset($settings['show_labels']) && $settings['show_labels'] == 'yes' ? ' show_label':' hide_lable';
            $attr_str = '';
            if (isset($settings['custom_labels']) && $settings['custom_labels'] == 'yes'){
                if (isset($settings['label_days']) && $settings['label_days']){
                    $attr_str .= ' data-text-day="'.$settings['label_days'].'"';
                }
                if (isset($settings['label_hours']) && $settings['label_hours']){
                    $attr_str .= ' data-text-hour="'.$settings['label_hours'].'"';
                }
                if (isset($settings['label_minutes']) && $settings['label_minutes']){
                    $attr_str .= ' data-text-min="'.$settings['label_minutes'].'"';
                }
                if (isset($settings['label_seconds']) && $settings['label_seconds']){
                    $attr_str .= ' data-text-sec="'.$settings['label_seconds'].'"';
                }
            }
            $content_html .= '<div class="fmtpl-countdown-time'.$cd_class.'" data-due_date="'.esc_attr($settings['due_date']).'"'.$attr_str.'></div>';
        }

        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && !empty($settings['button_icon']['value']))) {
            if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'link', $settings['link'] );
                $link_attr_str =  $this->get_render_attribute_string( 'link' );
            } else {
                $link_attr_str = 'href="#." title=""';
            }
            $icon_str = '';
            if (isset($settings['button_icon']) && $settings['button_icon']) {
                ob_start();
                Icons_Manager::render_icon( $settings[ 'button_icon' ]);
                $icon_str = ob_get_clean();
                if ($icon_str != '') {
                    $icon_str = '<span class="fmtpl-btn-icon">'.$icon_str.'</span>';
                }
            }

            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
            $content_html .= '<a class="fmtpl-button-default fmtpl-countdown-button '.$btn_class.'" ' . $link_attr_str . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
        }

        if ($content_html != '') {
            $html .= '<div class="fmtpl-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';
        return apply_filters('fmtpl-countdown-elementor-widget-control_html',$html,$settings);
    }
}
