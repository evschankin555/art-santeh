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
class Fmtpl_Title extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'fmtpl-title';
    }

    public function get_title()
    {
        return __('Heading Title', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-heading fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'Heading', 'title'];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-heading-title');
    }

    private function define_widget_control()
    {
        $widget_control = [
            'layout' => [
                'label' => __('Layout', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __( 'Horizontal', 'fami-templatekits' ),
                    'vertical' => __( 'Vertical', 'fami-templatekits' ),
                ],
                'style_transfer' => true,
            ],
            'title_text' => [
                'label' => __( 'Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'label_block' => true,
                'separator' => 'before',
            ],

            'highlight_title' => [
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
                'default' => __( '', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'show_icon' => [
                'label' => __( 'Show Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'default' => 'no',
            ],

            'icon' => [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'show_icon' => 'yes'
                ]
            ],

            'icon_position' => [
                'label' => __('Icon Position', 'fami-templatekits'),
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
                'default' => 'top',
                'condition' => [
                    'icon[value]!' => ''
                ],
            ],
            'icon_size' => [
                'label' => __( 'Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'condition' => [
                    'icon[value]!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-heading-title  .fmtpl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ],
            'icon_space' => [
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
                'condition' => [
                    'icon[value]!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-heading-title .icon-pos-left .fmtpl-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-heading-title .icon-pos-right .fmtpl-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-heading-title .icon-pos-top .fmtpl-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-heading-title .icon-pos-bottom .fmtpl-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ],
            'icon_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-heading-title  .fmtpl-icon' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
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
                'default' => '',
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
                'separator' => 'before',
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
                'prefix_class' => 'fmtpl-content-align-',
            ],
            'content_position' => [
                'label' => __('Position', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Start', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('End', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'fmtpl-content-position-',
            ],
            'background_color' =>
            [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tittle-content-wrap' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-tittle-content-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .fmtpl-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-title-wrap, {{WRAPPER}} .fmtpl-title-wrap a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-title-wrap .title-content.stroke' => '-webkit-text-fill-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],

            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-title-wrap',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'title_stroke_text' => [
                'label' => __( 'Stroke Text', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Enable', 'fami-templatekits' ),
                'label_off' => __( 'Disable', 'fami-templatekits' ),
                'default' => 'no',
            ],
            'title_stroke_color' => [
                'label' => __( 'Stroke Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-title-wrap .title-content.stroke' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text' => 'yes'
                ]
            ],

            'highlight_title_color' => [
                'label' => __( 'Highlight Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-title-wrap .highlight' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-title-wrap .highlight.stroke' => '-webkit-text-fill-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],

            'highlight_title_typography' => [
                'label' => __( 'Highlight Typography', 'fami-templatekits' ),
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-title-wrap .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_stroke_text' => [
                'label' => __( 'Highlight Stroke Text', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Enable', 'fami-templatekits' ),
                'label_off' => __( 'Disable', 'fami-templatekits' ),
                'default' => 'no',
            ],
            'highlight_title_stroke_color' => [
                'label' => __( 'Highlight Stroke Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-title-wrap .highlight.stroke' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'highlight_title_stroke_text' => 'yes'
                ]
            ],

            'sub_title_color' => [
                'label' => __( 'Sub title Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-sub-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],

            'sub_title_typography' => [
                'label' => __( 'Sub title Typography', 'fami-templatekits' ),
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-sub-title',
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
                    '{{WRAPPER}} .fmtpl-title-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-title-desc, {{WRAPPER}} .fmtpl-title-desc p' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_3,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .fmtpl-title-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
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
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
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
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_type' => [
                'name' => 'divider_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],

            'divider_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-heading-title .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
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
        $this->default_control = apply_filters('fmtpl-title-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_layout', ['label' => __('Settings', 'fami-templatekits')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
        }

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
        if (isset($this->default_control['show_icon'])) {
            $this->add_control(
                'show_icon',
                $this->default_control['show_icon']
            );
        }

        if (isset($this->default_control['icon'])) {
            $this->add_control(
                'icon',
                $this->default_control['icon']
            );
        }

        if (isset($this->default_control['show_divider'])) {
            $this->add_control(
                'show_divider',
                $this->default_control['show_divider']
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
            '_section_content_style',
            [
                'label' => __( 'Content Box', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        if (isset($this->default_control['content_position'])) {
            $this->add_control(
                'content_position',
                $this->default_control['content_position']
            );
        }
        if (isset($this->default_control['text_alignment'])) {
            $this->add_responsive_control(
                'text_alignment',
                $this->default_control['text_alignment']
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

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_title_style',
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['title_stroke_text'])) {
            $this->add_control(
                'title_stroke_text',
                $this->default_control['title_stroke_text']
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
            'title_tabs_hr_1',
            [
                'label' => __( 'Highlight Title', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        if (isset($this->default_control['highlight_title_stroke_text'])) {
            $this->add_control(
                'highlight_title_stroke_text',
                $this->default_control['highlight_title_stroke_text']
            );
        }
        if (isset($this->default_control['highlight_title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['highlight_title_typography']
            );
        }
        if (isset($this->default_control['sub_title_bottom_space']) || isset($this->default_control['sub_title_typography'])){
            $this->add_control(
                'title_tabs_hr_3',
                [
                    'label' => __( 'Sub Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['sub_title_bottom_space']) ){
                $this->add_responsive_control(
                    'sub_title_bottom_space',
                    $this->default_control['sub_title_bottom_space']
                );
            }
            if (isset($this->default_control['sub_title_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['sub_title_typography']
                );
            }
        }
        $this->add_control(
            'title_tabs_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        if (isset($this->default_control['title_color'])) {
            $this->add_control(
                'title_color',
                $this->default_control['title_color']
            );
        }
        if (isset($this->default_control['title_stroke_color'])) {
            $this->add_control(
                'title_stroke_color',
                $this->default_control['title_stroke_color']
            );
        }
        if (isset($this->default_control['highlight_title_color'])) {
            $this->add_control(
                'highlight_title_color',
                $this->default_control['highlight_title_color']
            );
        }

        if (isset($this->default_control['highlight_title_stroke_color'])) {
            $this->add_control(
                'highlight_title_stroke_color',
                $this->default_control['highlight_title_stroke_color']
            );
        }

        if (isset($this->default_control['sub_title_color'])) {
            $this->add_control(
                'sub_title_color',
                $this->default_control['sub_title_color']
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon[value]!' => ''
                ],
            ]
        );
        if (isset($this->default_control['icon_position'])) {
            $this->add_control(
                'icon_position',
                $this->default_control['icon_position']
            );
        }
        if (isset($this->default_control['icon_size'])) {
            $this->add_responsive_control(
                'icon_size',
                $this->default_control['icon_size']
            );
        }
        if (isset($this->default_control['icon_space'])) {
            $this->add_responsive_control(
                'icon_space',
                $this->default_control['icon_space']
            );
        }
        if (isset($this->default_control['icon_color'])) {
            $this->add_control(
                'icon_color',
                $this->default_control['icon_color']
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
    }

    protected function render()
    {
        echo $this->fmtpl_render();
    }
    protected function fmtpl_render() {
        $settings = $this->get_settings_for_display();
        $icon_str = '';
        //show_icon
        if (isset($settings['show_icon']) && $settings['show_icon'] == 'yes' && isset($settings['icon']) && $settings['icon']) {
            ob_start();
            Icons_Manager::render_icon( $settings[ 'icon' ]);
            $icon_str = ob_get_clean();
            if ($icon_str != '') {
                $icon_str = '<span class="fmtpl-icon">'.$icon_str.'</span>';
            }
        }
        $settings['icon_str'] = $icon_str;
        $html = apply_filters('fmtpl-title-elementor-widget-html','',$settings);
        if (empty($html)){
            //check for old theme version
            $html = apply_filters('fmtpl-title-elementor-widget-control_html','',$settings);
        }
        if (!empty($html)){
            return $html;
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-heading-title '.$settings['layout'].'"><div class="fmtpl-tittle-content-wrap">';

        $icon_class = '';
        if (isset($settings['icon_position'])){
            $icon_class = ' icon-pos-'.$settings['icon_position'];
        }
        $html .= '<div class="fmtpl-widget-title fmtpl-title-wrap'.$icon_class.'">';
        if ($icon_str != '' && isset($settings['icon_position']) && ($settings['icon_position'] == 'left' || $settings['icon_position'] == 'top')){
            $html .= $icon_str;
        }
        if (isset($settings['title_text'])) {
            $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';

            $highlight_str = sprintf('<span class="%s">%s</span>',
                (isset($settings['highlight_title_stroke_text']) && $settings['highlight_title_stroke_text'] == 'yes')? 'highlight stroke':'highlight',
                $highlight_title
            );
            $title = str_replace('%highlight%',$highlight_str,$settings['title_text']);
            $title_class = 'title-content';
            if (isset($settings['title_stroke_text']) && $settings['title_stroke_text'] == 'yes'){
                $title_class .= ' stroke';
            }
            $html .= '<span class="'.$title_class.'">'.$title.'</span>';
        }

        if ($icon_str != '' && ($settings['icon_position'] == 'right' || $settings['icon_position'] == 'bottom')){
            $html .= $icon_str;
        }
        $html .= '</div>';
        if (isset($settings['sub_title']) && !empty($settings['sub_title'])){
            $html .= '<div class="fmtpl-sub-title">'.$settings['sub_title'].'</div>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $html .= '<div class="fmtpl-divider-wrap"><div class="fmtpl-divider">&nbsp;</div></div>';
        }

        if (isset($settings['description'])){
            $html .= '<div class="fmtpl-title-desc">'.$settings['description'].'</div>';
        }
        $html .='</div></div>';
        return $html;
    }
}
