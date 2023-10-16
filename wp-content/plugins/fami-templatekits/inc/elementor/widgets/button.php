<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;
class Fmtpl_Button extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'fmtpl-button';
    }

    public function get_title()
    {
        return __('Button', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-button fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'button'];
    }

    private function define_widget_control()
    {
        $widget_control = [
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
            'btn_alignment' => [
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
                    '{{WRAPPER}} .fmtpl-elementor-widget.fmtpl-button' => 'text-align: {{VALUE}}',
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
                'default' => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button svg' => 'fill:  {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button:hover' => 'color:  {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button:hover svg' => 'fill:  {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-button .fmtpl-button-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-button .fmtpl-button-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-button .fmtpl-button-button',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'btn_border_border!' => '',
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
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-button .fmtpl-button-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-button .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
            'btn_text_typography' => [
                'name' => 'btn_text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-button .fmtpl-btn-text',
            ]
        ];
        $this->default_control = apply_filters('fmtpl-button-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section(
            '_section_info',
            [
                'label' => __('Settings', 'fami-templatekits'),
            ]
        );
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
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_btn_style',
            [
                'label' => __( 'Button', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //btn_alignment
        if (isset($this->default_control['btn_alignment'])) {
            $this->add_responsive_control(
                'btn_alignment',
                $this->default_control['btn_alignment']
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
        if (isset($this->default_control['btn_text_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['btn_text_typography']
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
        $html = '<div class="fmtpl-elementor-widget fmtpl-button">';
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

            $html .= '<a class="fmtpl-button-default fmtpl-button-button '.$btn_class.'" ' . $link_attr_str . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
        }
        $html .='</div>';
        return apply_filters('fmtpl-button-elementor-widget-control_html',$html,$settings);
    }
}
