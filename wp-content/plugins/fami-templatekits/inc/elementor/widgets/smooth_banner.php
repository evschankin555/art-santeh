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
class Fmtpl_Smooth_Banner extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'smooth-banner';
    }

    public function get_title()
    {
        return esc_html__('Smooth Banner', 'fami-templatekits');
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
        return ['fami','fm', 'image', 'photo', 'visual', 'box', 'adv', 'fami-templatekits' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $script_depends = ['three','tweenmax','hover'];
        foreach ($script_depends as $script){
            $this->add_script_depends($script);
        }
        $this->add_style_depends('fmtpl-smooth-banner');
    }

    private function define_widget_control()
    {
        $effects = $this->effects();
        $effect_options = [];
        $default_effect = false;
        $eff_label = esc_html__('Style','fami-templatekits');
        foreach ($effects as $key => $eff){
            if (!$default_effect){
                $default_effect = $key;
            }
            $effect_options[$key] = $eff_label.' '.$key;
        }

        $widget_control = [
            'layout' => [
                'label' =>esc_html__('Layout', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' =>esc_html__('Default', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],
            'height' => [
                'label' =>esc_html__( 'Height', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'overlay' => [
                'label' =>esc_html__( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'overlay_hover' => [
                'label' =>esc_html__( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner:hover .smooth-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'image' => [
                'label' =>esc_html__( 'Choose Image', 'fami-templatekits' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ],
            '2nd_image' => [
                'label' =>esc_html__( 'Second Image', 'fami-templatekits' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ],
            'effect' => [
                'label' =>esc_html__('Effect', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => $default_effect,
                'options' => $effect_options,
                'style_transfer' => true,
            ],
            'thumbnail' => [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
            ],
            'title_text' =>
            [
                'label' =>esc_html__( 'Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' =>esc_html__( 'This is the %highlight% title', 'fami-templatekits' ),
                'placeholder' =>esc_html__( 'This is the %highlight% title', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'highlight_title' =>
            [
                'label' =>esc_html__( 'Highlight Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' =>esc_html__( 'Highlight', 'fami-templatekits' ),
                'placeholder' =>esc_html__( 'Enter your Highlight title', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'sub_title' => [
                'label' =>esc_html__( 'Sub Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'label_block' => true,
            ],
            'show_divider' => [
                'label' =>esc_html__( 'Show Divider', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Show', 'fami-templatekits' ),
                'label_off' =>esc_html__( 'Hide', 'fami-templatekits' ),
                'default' => 'no',
            ],
            'description' => [
                'label' =>esc_html__( 'Description', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' =>esc_html__( 'This is the description. Lorem ipsum dolor
sit amet consectetur adipiscing', 'fami-templatekits' ),
                'placeholder' =>esc_html__( 'Type your description here', 'fami-templatekits' ),
            ],
            'text_alignment' => [
                'label' =>esc_html__( 'Alignment', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' =>esc_html__( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' =>esc_html__( 'Center', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' =>esc_html__( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'text-align: {{VALUE}}',
                ],
            ],

            'button_text' => [
                'label' =>esc_html__( 'Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' =>esc_html__( 'Button Text', 'fami-templatekits' ),
                'placeholder' =>esc_html__( 'Type button text here', 'fami-templatekits' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ],

            'link' => [
                'label' =>esc_html__( 'Link', 'fami-templatekits' ),
                'type' => Controls_Manager::URL,
                'placeholder' =>esc_html__( 'https://your-link.com', 'fami-templatekits' ),
                'show_external' => true,
                'dynamic' => [
                    'active' => true,
                ]
            ],
            'button_icon' => [
                'label' =>esc_html__( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ],

            'box_width' => [
                'label' =>esc_html__( 'Width', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'box_height' => [
                'label' =>esc_html__( 'Height', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'content_alignment' => [
                'label' =>esc_html__('Alignment', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' =>esc_html__('Left', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' =>esc_html__('Center', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' =>esc_html__('Right', 'fami-templatekits'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'text-align: {{VALUE}}',
                ],
            ],
            'background_color' =>
            [
                'label' =>esc_html__( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'background-color: {{VALUE}};',
                ],
            ],
            'horizontal_position' => [
                'label' =>esc_html__('Horizontal', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' =>esc_html__('Left', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' =>esc_html__('Center', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' =>esc_html__('Right', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner' => 'justify-content: {{VALUE}}',
                ],
            ],
            'vertical_position' => [
                'label' =>esc_html__('Vertical', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' =>esc_html__('Top', 'fami-templatekits'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' =>esc_html__('Middle', 'fami-templatekits'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' =>esc_html__('Bottom', 'fami-templatekits'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner' => 'align-items: {{VALUE}}',
                ],

            ],
            'box_margin' => [
                'label' =>esc_html__( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'box_padding' => [
                'label' =>esc_html__( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'border' => [
                'name' => 'border',
                'label' =>esc_html__( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .smooth-banner .smooth-banner-content',
            ],

            'border_radius' => [
                'label' =>esc_html__( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'box_shadow' => [
                'name' => 'box_shadow',
                'label' =>esc_html__( 'Box Shadow', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .smooth-banner .smooth-banner-content',
            ],
            'title_order' => [
                'label' => __( 'Title Order', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-title' => 'order: {{VALUE}};',
                ],
            ],
            'title_bottom_space' => [
                'label' =>esc_html__( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'title_color' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-title, {{WRAPPER}} .smooth-banner .smooth-banner-title a' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_hover_color' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner:hover .smooth-banner-title, {{WRAPPER}} .smooth-banner:hover .smooth-banner-title a' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .smooth-banner .smooth-banner-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' =>esc_html__( 'Highlight Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_hover_color' => [
                'label' =>esc_html__( 'Highlight Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner:hover .smooth-banner-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .smooth-banner .smooth-banner-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],

            'sub_title_color' => [
                'label' =>esc_html__( 'Sub Title Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .fmtpl-smooth-sub-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'sub_title_typography' => [
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .smooth-banner .fmtpl-smooth-sub-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'sub_title_bottom_space' => [
                'label' =>esc_html__( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .fmtpl-smooth-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_order' => [
                'label' => __( 'Description Order', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .banner-desc' => 'order: {{VALUE}};',
                ],
            ],
            'desc_bottom_space' => [
                'label' =>esc_html__( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .banner-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .banner-desc' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .smooth-banner .banner-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
            ],
            'btn_order' => [
                'label' => __( 'Button Order', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .fmtpl-smooth-button-wrap' => 'order: {{VALUE}};',
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
                    '{{WRAPPER}} .smooth-banner .fmtpl-smooth-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'btn_width' => [
                'label' =>esc_html__( 'Width', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'btn_height' => [
                'label' =>esc_html__( 'Height', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button' => 'color: {{VALUE}};fill:  {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'btn_color_hover' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button:hover' => 'color: {{VALUE}};fill:  {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'btn_bg_color' =>
                [
                    'label' =>esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .smooth-banner .smooth-banner-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' =>esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .smooth-banner .smooth-banner-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' =>esc_html__( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .smooth-banner .smooth-banner-button',
            ],

            'btn_border_radius' => [
                'label' =>esc_html__( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' =>esc_html__( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_border_border!' => '',
                ],
            ],
            'divider_order' => [
                'label' => __( 'Divider Order', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .fmtpl-divider-wrap' => 'order: {{VALUE}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_width' => [
                'label' =>esc_html__( 'Width', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_height' => [
                'label' =>esc_html__( 'Height', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_color' => [
                'label' =>esc_html__( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider' => 'background-color: {{VALUE}};',
                ],
                'default' => '#000000',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_margin' => [
                'label' =>esc_html__( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_type' => [
                'name' => 'divider_border_type',
                'label' =>esc_html__( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],

            'divider_border_radius' => [
                'label' =>esc_html__( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_color_hover' => [
                'label' =>esc_html__( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner-content .fmtpl-smooth-divider:hover' => 'border-color: {{VALUE}};',
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
        ];
        $this->default_control = apply_filters('smooth-banner-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_layout', ['label' =>esc_html__('Layout', 'fami-templatekits')]);
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
                'label' =>esc_html__('Banner', 'fami-templatekits'),
            ]
        );
        if (isset($this->default_control['image'])) {
            $this->add_control(
                'image',
                $this->default_control['image']
            );
        }
        if (isset($this->default_control['2nd_image'])) {
            $this->add_control(
                '2nd_image',
                $this->default_control['2nd_image']
            );
        }
        if (isset($this->default_control['effect'])) {
            $this->add_control(
                'effect',
                $this->default_control['effect']
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
                'label' =>esc_html__('Content Box', 'fami-templatekits'),
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
                    'label' =>esc_html__( 'Button', 'fami-templatekits' ),
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
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_layout_style',
            [
                'label' =>esc_html__( 'Layout', 'fami-templatekits' ),
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
                    'label' =>esc_html__('Overlay', 'fami-templatekits'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->start_controls_tabs( 'layout_tabs' );
            $this->start_controls_tab(
                'tab_layout_normal',
                [
                    'label' =>esc_html__( 'Normal', 'fami-templatekits' ),
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
                    'label' =>esc_html__( 'Hover', 'fami-templatekits' ),
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
                'label' =>esc_html__( 'Content Box', 'fami-templatekits' ),
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
                    'label' =>esc_html__('Position', 'fami-templatekits'),
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
                    'label' =>esc_html__('Border Style', 'fami-templatekits'),
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
                'label' =>esc_html__( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['title_order'])) {
            $this->add_control(
                'title_order',
                $this->default_control['title_order']
            );
        }
        if (isset($this->default_control['title_bottom_space'])) {
            $this->add_responsive_control(
                'title_bottom_space',
                $this->default_control['title_bottom_space']
            );
        }
        if (isset($this->default_control['title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['title_typography']
            );
        }

        $this->add_control(
            '_highlight_hd2',
            [
                'label' => __( 'HighLight Title', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        if (isset($this->default_control['highlight_title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['highlight_title_typography']
            );
        }


        $this->start_controls_tabs( 'adv_bn_tabs' );
        $this->start_controls_tab(
            'tab_adv_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['title_color'])) {
            $this->add_control(
                'title_color',
                $this->default_control['title_color']
            );
        }
        if (isset($this->default_control['highlight_title_color'])) {
            $this->add_control(
                'highlight_title_color',
                $this->default_control['highlight_title_color']
            );
        }

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_adv_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );

        if (isset($this->default_control['title_hover_color'])) {
            $this->add_control(
                'title_hover_color',
                $this->default_control['title_hover_color']
            );
        }
        if (isset($this->default_control['highlight_title_hover_color'])) {
            $this->add_control(
                'highlight_title_hover_color',
                $this->default_control['highlight_title_hover_color']
            );
        }

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_sub_title_style',
            [
                'label' =>esc_html__( 'Sub Title', 'fami-templatekits' ),
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
                'label' =>esc_html__( 'Divider', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ]
        );
        if (isset($this->default_control['divider_order'])) {
            $this->add_control(
                'divider_order',
                $this->default_control['divider_order']
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
                    'label' =>esc_html__('Border Style', 'fami-templatekits'),
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
                'label' =>esc_html__( 'Description', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['desc_order'])) {
            $this->add_control(
                'desc_order',
                $this->default_control['desc_order']
            );
        }
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
                'label' =>esc_html__( 'Button', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['btn_order'])) {
            $this->add_control(
                'btn_order',
                $this->default_control['btn_order']
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
                'label' =>esc_html__( 'Normal', 'fami-templatekits' ),
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
                'label' =>esc_html__( 'Hover', 'fami-templatekits' ),
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
                    'label' =>esc_html__('Border Style', 'fami-templatekits'),
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
                'label' =>esc_html__( 'Icon Options', 'fami-templatekits' ),
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
                'label' =>esc_html__( 'Spacing', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button.left .fmtpl-smooth-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .smooth-banner .smooth-banner-button.right .fmtpl-smooth-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
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
                'label' =>esc_html__( 'Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .smooth-banner .fmtpl-smooth-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
                'label' =>esc_html__( 'Icon Position', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' =>esc_html__( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' =>esc_html__( 'Right', 'fami-templatekits' ),
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
                'label' =>esc_html__( 'Button Text', 'fami-templatekits' ),
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
                'selector' => '{{WRAPPER}} .smooth-banner .fmtpl-smooth-btn-text',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        echo($this->cenos_render());
    }
    protected function effects(){
        $effects = [
            '1' => [
                'displacement' => '1.jpg',
                'intensity' => '-0.4',
                'speedIn' => '0.7',
                'speedOut' => '0.3',
                'easing' => 'Sine.easeOut'
            ],
            '2' => [
                'displacement' => '2.jpg',
                'intensity' => '0.6',
                'speedIn' => '1',
                'speedOut' => '1',
            ],
            '3' => [
                'displacement' => '4.png',
                'intensity' => '0.2',
                'speedIn' => '1.6',
                'speedOut' => '1.6'
            ],
            '4' => [
                'displacement' => '6.jpg',
                'intensity' => '0.6',
                'speedIn' => '1.2',
                'speedOut' => '0.5',
            ],
            '5' => [
                'displacement' => '7.jpg',
                'intensity' => '0.9',
                'speedIn' => '0.8',
                'speedOut' => '0.4',
                'easing' => 'Circ.easeOut'
            ],
            '6' => [
                'displacement' => '8.jpg',
                'intensity' => '-0.65',
                'speedIn' => '1.2',
                'speedOut' => '1.2'
            ],
            '7' => [
                'displacement' => '10.jpg',
                'intensity' => '0.7',
                'speedIn' => '1',
                'speedOut' => '0.5',
                'easing' => 'Power2.easeOut'
            ],
            '8' => [
                'displacement' => '11.jpg',
                'intensity' => '0.4',
                'speedIn' => '1',
                'speedOut' => '1',
            ],
            '9' => [
                'displacement' => '13.jpg',
                'intensity' => '0.2',
            ],
            '10' => [
                'displacement' => '15.jpg',
                'intensity' => '-0.1',
                'speedIn' => '0.4',
                'speedOut' => '0.4',
                'easing' => 'power2.easeInOut'
            ],
            '11' => [
                'displacement' => '8.jpg',
                'intensity' => '-0.8',
            ],
        ];
        return apply_filters('fmtpm_advance_banner_effects',$effects);
    }
    protected function cenos_render() {
        $settings = $this->get_settings_for_display();
        $settings['image_src'] = '';
        $settings['image_html'] = '';
        if ( ! empty( $settings['image']['url'] ) ) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['image']['id'], 'thumbnail', $settings);
            if (empty($image_src)) {
                $image_src = $settings['image']['url'];
            }
            $settings['image_src'] =  addslashes($image_src);

            $this->add_render_attribute('image', 'src', $settings['image']['url']);
            $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['image']));
            $this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['image']));
            $settings['image_html'] = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image');
        }
        $settings['2nd_image_src'] = '';
        $settings['2nd_image_html'] = '';
        if ( ! empty( $settings['2nd_image']['url'] ) ) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['2nd_image']['id'], 'thumbnail', $settings);
            if (empty($image_src)) {
                $image_src = $settings['2nd_image']['url'];
            }
            $settings['2nd_image_src'] =  addslashes($image_src);

            $this->add_render_attribute('2nd_image', 'src', $settings['2nd_image']['url']);
            $this->add_render_attribute('2nd_image', 'alt', Control_Media::get_image_alt($settings['2nd_image']));
            $this->add_render_attribute('2nd_image', 'title', Control_Media::get_image_title($settings['2nd_image']));
            $settings['2nd_image_html'] = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', '2nd_image');
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
                $settings['icon_str'] = '<span class="fmtpl-smooth-btn-icon">'.$settings['icon_str'].'</span>';
            }
        }
        $html = apply_filters('smooth-banner-elementor-widget-control_html','',$settings);
        if (!empty($html)){
            return $html;
        }
        $html = '<div class="fmtpl-smooth-elementor-widget smooth-banner '.$settings['layout'].'">';

        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="smooth-banner-overlay"></div>';
        }
        $effect_setting_str = '';
        if (isset($settings['effect']) && $settings['effect']){
            $effects = $this->effects();
            if (isset($effects[$settings['effect']]) && !empty($effects[$settings['effect']])){
                foreach ($effects[$settings['effect']] as $e_k => $e_v){
                    if ('displacement' == $e_k){
                        $e_v = FAMI_TPL_URL.'assets/img/displacement/'.$e_v;
                    }
                    $effect_setting_str .= ' data-'.$e_k.'="'.$e_v.'"';
                }
            }
        }

        if ($settings['image_html'] != '' && $settings['2nd_image_html'] != '' ){
            $html .= '<figure class="smooth-banner-box-img"'.$effect_setting_str.'>' . $settings['image_html'] .$settings['2nd_image_html']. '</figure>';
        }

        $content_html = ''; 

        if (isset($settings['title_text'])) {
            $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-smooth-widget-title smooth-banner-title "><a ' . $settings['link_attr_str'] . '>'.$title.'</a></div>';
        }
        if (isset($settings['sub_title']) && !empty($settings['sub_title'])){
            $content_html .= '<div class="fmtpl-smooth-sub-title">'.$settings['sub_title'].'</div>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $content_html .= '<div class="fmtpl-divider-wrap"><div class="fmtpl-smooth-divider">&nbsp;</div></div>';
        }
        if (isset($settings['description'])){
            $content_html .= '<div class="banner-desc">'.$settings['description'].'</div>';
        }

        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && !empty($settings['button_icon']['value']))) {
            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
            $content_html .= '<div class="fmtpl-smooth-button-wrap"><a class="fmtpl-smooth-button-default smooth-banner-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$settings['icon_str'].'<span class="fmtpl-smooth-btn-text">'.$settings['button_text'].'</span></a></div>';
        }

        if ($content_html != '') {
            $html .= '<div class="smooth-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';
        return $html;
    }
}
