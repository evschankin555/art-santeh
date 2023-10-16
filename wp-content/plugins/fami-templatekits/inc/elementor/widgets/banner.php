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
class Fmtpl_Banner extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'fmtpl-banner';
    }

    public function get_title()
    {
        return __('Simple Banner', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-image fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'image', 'photo', 'visual', 'box' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
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
                    '{{WRAPPER}} .fmtpl-banner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'overlay' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'overlay_hover' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner:hover .fmtpl-banner-overlay' => 'background-color: {{VALUE}};',
                ],
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
            'effect' => [
                'label' => __('Hover Effect', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'zoom',
                'options' => [
                    'zoom' => __('Zoom Out', 'fami-templatekits'),
                ],
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
            'sub_title' => [
                'label' => __( 'Sub Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'label_block' => true,
            ],
            'show_divider' => [
                'label' => __( 'Show Divider', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'default' => 'no',
            ],
            'description' => [
                'label' => __( 'Description', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( 'This is the description. Lorem ipsum dolor
sit amet consectetur adipiscing', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'text-align: {{VALUE}}',
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'height: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'text-align: {{VALUE}}',
                ],
            ],
            'background_color' =>
            [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-banner' => 'justify-content: {{VALUE}}',
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
                    '{{WRAPPER}} .fmtpl-banner' => 'align-items: {{VALUE}}',
                ],

            ],
            'box_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'box_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'border' => [
                'name' => 'border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content',
            ],

            'border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'box_shadow' => [
                'name' => 'box_shadow',
                'label' => __( 'Box Shadow', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content',
            ],
            'title_order' => [
                'label' => __( 'Title Order', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-content .fmtpl-banner-title' => 'order: {{VALUE}};',
                ],
                'condition' => [
                    'item_divider' => 'yes'
                ]
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],

            'sub_title_color' => [
                'label' => __( 'Sub Title Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-sub-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'sub_title_typography' => [
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-sub-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'sub_title_bottom_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-banner .banner-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .banner-desc' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .fmtpl-banner .banner-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button' => 'color: {{VALUE}};fill:  {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button:hover' => 'color: {{VALUE}};fill:  {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_border_border!' => '',
                ],
            ],
            // & Description
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
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_height' => [
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
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider' => 'background-color: {{VALUE}};',
                ],
                'default' => '#000000',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_type' => [
                'name' => 'divider_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],

            'divider_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ],
            ],
            'divider_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner-content .fmtpl-divider:hover' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'show_divider',
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
            'divider_position' => [
                'label' => __('Position', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __('Top', 'fami-templatekits'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __('Right', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bottom' => [
                        'title' => __('Bottom', 'fami-templatekits'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'bottom',
                'condition' => [
                    'show_divider' => 'yes'
                ],
                'prefix_class' => 'fmtpl-divider-position-',
            ],
        ];
        $this->default_control = apply_filters('fmtpl-banner-elementor-widget-control', $widget_control);
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
        if (isset($this->default_control['effect'])) {
            $this->add_control(
                'effect',
                $this->default_control['effect']
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
        if (isset($this->default_control['sub_title'])) {
            $this->add_control(
                'sub_title',
                $this->default_control['sub_title']
            );
        }
        if (isset($this->default_control['show_divider'])) {
            $this->add_control(
                'show_divider',
                $this->default_control['show_divider']
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
        if (isset($this->default_control['description'])) {
            $this->add_control(
                'description',
                $this->default_control['description']
            );
        }
        //button_icon
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
                    'type' => \Elementor\Controls_Manager::HEADING,
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

        if (isset($this->default_control['box_margin'])) {
            $this->add_control(
                'margin_hr',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );
            $this->add_responsive_control(
                'box_margin',
                $this->default_control['box_margin']
            );
        }
        if (isset($this->default_control['box_padding'])) {
            $this->add_responsive_control(
                'box_padding',
                $this->default_control['box_padding']
            );
        }
        //border
        if (isset($this->default_control['border'])) {
            $this->add_control(
                'box_border_hd',
                [
                    'label' => __('Border Style', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
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
            '_section_sub_title_style',
            [
                'label' => __( 'Sub Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'sub_title!' =>''
                ],
            ]
        );
        if (isset($this->default_control['sub_title_bottom_space'])) {
            $this->add_responsive_control(
                'sub_title_bottom_space',
                $this->default_control['sub_title_bottom_space']
            );
        }
        if (isset($this->default_control['sub_title_color'])) {
            $this->add_control(
                'sub_title_color',
                $this->default_control['sub_title_color']
            );
        }
        if (isset($this->default_control['sub_title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['sub_title_typography']
            );
        }
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
        if (isset($this->default_control['divider_position'])) {
            $this->add_control(
                'divider_position',
                $this->default_control['divider_position']
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
            ]
        );
        $this->add_responsive_control(
            'btn_icon_space',
            [
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
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-banner-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
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
            ]
        );
        $this->add_responsive_control(
            'btn_icon_size',
            [
                'label' => __( 'Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-banner .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
            ]
        );
        $this->add_control(
            'btn_icon_position',
            [
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
            ]
        );
        $this->add_control(
            'btn_text_options',
            [
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
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-banner .fmtpl-btn-text',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        echo $this->fmtpl_render();
    }
    protected function fmtpl_render() {
        $settings = $this->get_settings_for_display();
        $style_str = '';
        $settings['image_src'] = '';
        $settings['image_html'] = '';
        if ( ! empty( $settings['image']['url'] ) ) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['image']['id'], 'thumbnail', $settings);
            if (empty($image_src)) {
                $image_src = $settings['image']['url'];
            }
            $settings['image_src'] =  addslashes($image_src);
            $style_str .= ' style="background-image: url(' . $image_src . ');"';
            $settings['style_str'] = $style_str;
            $this->add_render_attribute('image', 'src', $settings['image']['url']);
            $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['image']));
            $this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['image']));
            $settings['image_html'] = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image');
        }
        $settings['link_attr_str'] = '';
        if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'link', $settings['link'] );
            $settings['link_attr_str'] =  $this->get_render_attribute_string( 'link' );
        }
        $settings['icon_str'] = '';
        if (isset($settings['button_icon']) && $settings['button_icon']) {
            ob_start();
            Icons_Manager::render_icon( $settings[ 'button_icon' ]);
            $settings['icon_str'] = ob_get_clean();
            if ($settings['icon_str'] != '') {
                $settings['icon_str'] = '<span class="fmtpl-btn-icon">'.$settings['icon_str'].'</span>';
            }
        }
        $html = apply_filters('fmtpl-banner-elementor-widget-html','',$settings);
        if (empty($html)){
            //check for old theme version
            $html = apply_filters('fmtpl-banner-elementor-widget-control_html','',$settings);
        }
        if (!empty($html)){
            return $html;
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-banner '.$settings['layout'].'">';
        if ( ! empty( $style_str ) ) {
            $effect_class = isset($settings['effect']) ? $settings['effect'] : 'zoom';
            $html .= '<div class="fmtpl-banner-background '.$effect_class.'" '.$style_str.'></div>';
        }

        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="fmtpl-banner-overlay"></div>';
        }
        if ($settings['image_html'] != ''){
            if ($settings['link_attr_str'] != ''){
                $html .= '<figure class="fmtpl-banner-box-img has-link"><a class="banner-img-link" '.$settings['link_attr_str'].'>' . $settings['image_html'] . '</a></figure>';
            } else {
                $html .= '<figure class="fmtpl-banner-box-img">' . $settings['image_html'] . '</figure>';
            }
        }
        $content_html = '';

        if (isset($settings['title_text'])) {
            $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-widget-title fmtpl-banner-title ">'.$title.'</div>';
        }
        if (isset($settings['sub_title']) && !empty($settings['sub_title'])){
            $content_html .= '<div class="fmtpl-sub-title">'.$settings['sub_title'].'</div>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
        }
        if (isset($settings['description'])){
            $content_html .= '<div class="banner-desc">'.$settings['description'].'</div>';
        }

        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && !empty($settings['button_icon']['value']))) {


            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';

            $content_html .= '<a class="fmtpl-button-default fmtpl-banner-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$settings['icon_str'].'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
        }

        if ($content_html != '') {
            $html .= '<div class="fmtpl-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';


        return $html;
    }
}
