<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;

class Fmtpl_NewsLetter extends Widget_Base {
    private $default_control;
    public function get_name() {
        return 'fmtpl-newsletter';
    }
    public function get_title() {
        return __( 'Newsletter', 'fami-templatekits' );
    }
    public function get_icon() {
        return 'eicon-mailchimp fmtpl-widget-icon';
    }
    public function get_categories() {
        return [ 'fami-elements' ];
    }
    public function get_keywords() {
        return ['fami','fm', 'newsletter', 'news', 'letter', 'mailchimp','mc4wp' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-newsletter');
    }

    private function define_widget_control() {
        $widget_control = [
            'layout' => [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'fami-templatekits' ),
                ],
                'style_transfer' => true,
            ],
            'title' => [
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
                'default' => __( '', 'fami-templatekits' ),
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
            ],
            'content_alignment' => [
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
                    '{{WRAPPER}} .fmtpl-newsletter, {{WRAPPER}} .newsletter-form-content input[type="email"]' => 'text-align: {{VALUE}}',
                ],
            ],
            'content_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-newsletter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'title_color' =>
            [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ],
            'title_typography' =>
            [
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .newsletter-title',
                'condition' => [
                    'title!' => '',
                ],
            ],
            'title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-newsletter .newsletter-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-newsletter .newsletter-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'desc_color' =>
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .newsletter-desc' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'description!' => '',
                    ],
                ],
            'desc_typography' =>
            [
                'name' => 'desc_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .newsletter-desc',
                'condition' => [
                    'description!' => '',
                ],
            ],
            'desc_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'description!' => '',
                ],
            ],
            'button_text_color' => [
                'label' => __( 'Text Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit] svg, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit] svg' => 'fill: {{VALUE}};',
                ],
            ],
            'background_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]' => 'background-color: {{VALUE}};',
                ],
            ],
            'hover_color' => [
                'label' => __( 'Text Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:hover, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:focus, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:hover, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:hover svg, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:focus svg, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:hover svg, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:focus svg' => 'fill: {{VALUE}};',
                ],
            ],
            'button_background_hover_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:hover, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type=submit]:focus, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:hover, {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields button[type=submit]:focus' => 'background-color: {{VALUE}};',
                ],
            ],
            'form_width' => [
                'label' => __( 'Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'form_btn_width' => [
                'label' => __( 'Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content input[type=submit], {{WRAPPER}} .newsletter-form-content button[type=submit]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .newsletter-form-content input[type=submit], {{WRAPPER}} .newsletter-form-content button[type=submit]',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content input[type=submit], {{WRAPPER}} .newsletter-form-content button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content input[type=submit]:hover, {{WRAPPER}} .newsletter-form-content button[type=submit]:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_border_border!' => ''
                ],
            ],
            'btn_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content input[type=submit], {{WRAPPER}} .newsletter-form-content button[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_typography' =>
            [
                'name' => 'btn_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .newsletter-form-content input[type=submit], {{WRAPPER}} .newsletter-form-content button[type=submit]',
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
                            'name' => 'btn_icon[value]',
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
                    '{{WRAPPER}} .fmtpl_newsletter_btn.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl_newsletter_btn.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'btn_icon[value]',
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
                    '{{WRAPPER}} .fmtpl_newsletter_btn .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'btn_icon[value]',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ],
            'input_border_type' => [
                'name' => 'email_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]',
            ],
            'input_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'input_color' => [
                'label' => __( 'Text Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ],
            'input_typography' =>
            [
                'name' => 'input_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]',
            ],
            'input_alignment' => [
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
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"]' => 'text-align: {{VALUE}}',
                ],
            ],
            'input_bg_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]' => 'background-color: {{VALUE}};',
                ],
            ],
            'input_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'input_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="email"], {{WRAPPER}} .newsletter-form-content .mc4wp-form-fields input[type="text"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_text' => [
                'label' => __( 'Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Subscribe', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'btn_icon' => [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ]
        ];
        $this->default_control = apply_filters('fmtpl-newsletter-elementor-widget-control',$widget_control);
    }
    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_newsletter', ['label' => __('Settings', 'fami-templatekits')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
        }

        if (isset($this->default_control['title'])) {
            $this->add_control(
                'title',
                $this->default_control['title']
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

        $this->end_controls_section();

        $this->start_controls_section('_section_newsletter_btn', ['label' => __('Submit Button', 'fami-templatekits')]);
        if (isset($this->default_control['btn_text'])) {
            $this->add_control(
                'btn_text',
                $this->default_control['btn_text']
            );
        }
        if (isset($this->default_control['btn_icon'])) {
            $this->add_control(
                'btn_icon',
                $this->default_control['btn_icon']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'newsletter_content_style',
            [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['content_alignment'])) {
            $this->add_responsive_control(
                'content_alignment',
                $this->default_control['content_alignment']
            );
        }

        if (isset($this->default_control['content_padding'])) {
            $this->add_responsive_control(
                'content_padding',
                $this->default_control['content_padding']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'newsletter_title_style',
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                     'title!' => ''
                ]
            ]
        );
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
        if (isset($this->default_control['title_padding'])) {
            $this->add_responsive_control(
                'title_padding',
                $this->default_control['title_padding']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_highlight_title_style',
            [
                'label' => __( 'HighLight Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'highlight_title!' => ''
                ]
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
            'newsletter_desc_style',
            [
                'label' => __( 'Description', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'description!' => ''
                ]
            ]
        );
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
        if (isset($this->default_control['desc_padding'])) {
            $this->add_responsive_control(
                'desc_padding',
                $this->default_control['desc_padding']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'newsletter_form_style',
            [
                'label' => __( 'MC4WP Form', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        if (isset($this->default_control['form_width'])){
            $this->add_responsive_control(
                'form_width',
                $this->default_control['form_width']
            );
        }

        $this->add_control(
            'form_hd1',
            [
                'label' => __( 'Button', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        if (isset($this->default_control['btn_border'])) {
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
        if (isset($this->default_control['btn_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['btn_typography']
            );
        }
        if (isset($this->default_control['form_btn_width'])){
            $this->add_responsive_control(
                'form_btn_width',
                $this->default_control['form_btn_width']
            );
        }
        if (isset($this->default_control['btn_padding'])) {
            $this->add_responsive_control(
                'btn_padding',
                $this->default_control['btn_padding']
            );
        }

        $this->start_controls_tabs( 'mc4wp_background_tabs' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['button_text_color'])) {
            $this->add_control(
                'button_text_color',
                $this->default_control['button_text_color']
            );
        }
        if (isset($this->default_control['background_color'])) {
            $this->add_control(
                'background_color',
                $this->default_control['background_color']
            );
        }
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );

        if (isset($this->default_control['hover_color'])) {
            $this->add_control(
                'hover_color',
                $this->default_control['hover_color']
            );
        }
        if (isset($this->default_control['button_background_hover_color'])) {
            $this->add_control(
                'button_background_hover_color',
                $this->default_control['button_background_hover_color']
            );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'form_hd2_1',
            [
                'label' => __( 'Button Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        if (isset($this->default_control['btn_icon_position'])) {
            $this->add_control(
                'btn_icon_position',
                $this->default_control['btn_icon_position']
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



        $this->add_control(
            'form_hd2',
            [
                'label' => __( 'Input Text', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        if (isset($this->default_control['input_color'])) {
            $this->add_control(
                'input_color',
                $this->default_control['input_color']
            );
        }
        if (isset($this->default_control['input_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['input_typography']
            );
        }
        if (isset($this->default_control['input_border_type'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['input_border_type']
            );
        }
        if (isset($this->default_control['input_border_radius'])) {
            $this->add_responsive_control(
                'input_border_radius',
                $this->default_control['input_border_radius']
            );
        }
        if (isset($this->default_control['input_bg_color'])) {
            $this->add_control(
                'input_bg_color',
                $this->default_control['input_bg_color']
            );
        }
        if (isset($this->default_control['input_padding'])) {
            $this->add_responsive_control(
                'input_padding',
                $this->default_control['input_padding']
            );
        }
        if (isset($this->default_control['input_margin'])) {
            $this->add_responsive_control(
                'input_margin',
                $this->default_control['input_margin']
            );
        }
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['layout'])) :
            $form_id = (int) get_option( 'mc4wp_default_form_id', 0 );
            ob_start();
            mc4wp_show_form( $form_id );
            $form_content = ob_get_clean();
            $re = '/<input.*?type="submit".*?>/';
            preg_match($re, $form_content, $matches, PREG_UNMATCHED_AS_NULL, 0);
            $submit_str = '';
            if (!empty($matches)){
                $submit_str = $matches[0];
            }
            $btn_icon_str = '';
            if (isset($settings['btn_icon']) && $settings['btn_icon']) {
                ob_start();
                Icons_Manager::render_icon( $settings[ 'btn_icon' ]);
                $btn_icon_str = ob_get_clean();
            }
            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';

            $submit_button = sprintf(
                '<button type="submit" class="fmtpl_newsletter_btn %s">%s %s</button>',
                $btn_class,
                '<span class="fmtpl-btn-icon">'.$btn_icon_str.'</span>',
                isset($settings['btn_text'])? $settings['btn_text']: esc_html__('Subscribe','fami-templatekits')
            );
            if (!empty($submit_str)){
                $form_content = str_replace($submit_str,$submit_button,$form_content);
            } ?>
            <div class="fmtpl-elementor-widget fmtpl-newsletter <?php echo($settings['layout']);?>">
                <?php
                if (!empty($settings['title'])) :
                    $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                    $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
                    ?>
                    <div class="newsletter-title widget-title"><?php echo $title;?></div>
                <?php endif;?>
                <?php if (!empty($settings['description'])) :?>
                    <div class="newsletter-desc"><?php echo $settings['description'];?></div>
                <?php endif;?>
                <?php printf('<div class="newsletter-form-content">%s</div>',$form_content); ?>
            </div>
        <?php endif;
    }
}
