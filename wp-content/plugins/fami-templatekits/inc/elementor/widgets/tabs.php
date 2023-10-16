<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use \Elementor\Utils;

class Fmtpl_Tabs extends Widget_Base {

    private $default_control;
	
	public function get_name() {
		return 'fmtpl-tabs';
	}

	public function get_title() {
		return __( 'Tabs', 'fami-templatekits' );
	}

	public function get_icon() {
		return 'eicon-tabs fmtpl-widget-icon';
	}

    public function get_categories()
    {
        return ['fami-elements'];
    }

	public function get_keywords() {
		return ['fami','fm', 'tabs', 'accordion', 'toggle' ];
	}
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-tabs');
    }
    private function define_widget_control(){
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
            'tab_title' => [
                'label' => __( 'Title & Description', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Tab Title', 'fami-templatekits' ),
                'placeholder' => __( 'Tab Title', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'tab_content' => [
                'label' => __( 'Content', 'fami-templatekits' ),
                'default' => __( 'Tab Content', 'fami-templatekits' ),
                'placeholder' => __( 'Tab Content', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'show_label' => false,
                'dynamic' => [
                    'active' => false,
                ],
            ],
            'tab_icon_type' => [
                'label' => __( 'Icon Type', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'icon',
                'options' => [
                    'icon' => [
                        'title' => __( 'Icon', 'fami-templatekits' ),
                        'icon' => 'eicon-font-awesome',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'fami-templatekits' ),
                        'icon' => 'eicon-upload',
                    ],
                ],
                'toggle' => true,
            ],
            'tab_img_icon' => [
                'label' => __( 'Choose Image', 'fami-templatekits' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => ['tab_icon_type' => 'image']
            ],
            'tab_icon' => [
                'label' => __( 'Tab Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'condition' => ['tab_icon_type' => 'icon']
            ],
            'tab_title_width' => [
                'label' => __( 'Min Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link' => 'min-width: {{SIZE}}{{UNIT}};',
                ],

            ],

            'tab_title_alignment' => [
                'label' => __('Tab Title Alignment', 'fami-templatekits'),
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
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'right' => 'justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .nav-tabs' => '{{VALUE}};',
                ],
            ],
            'tab_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_title_hover_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover, {{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link.active, {{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover i, {{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link.active i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover svg, {{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link.active svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_title_bg_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link' => 'background-color: {{VALUE}};fill: {{VALUE}};',
                ],
            ],
            'tab_title_bg_hover_color' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover,{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link.active' => 'background-color: {{VALUE}};',
                ],
            ],
            'tab_title_border' => [
                'name' => 'tab_title_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .fmtpl-tab-link',
            ],
            'tab_title_border_hover_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .fmtpl-tab-link:hover, {{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .fmtpl-tab-link.active' => 'border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_4,
                ],
                'condition' => [
                    'tab_title_border_border!' => '',
                ],
            ],

            'list_item_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .fmtpl-tab-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_title_typography' => [
                'name' => 'tab_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link',
                'scheme' => Schemes\Typography::TYPOGRAPHY_2,
            ],
            'tab_icon_color' => [
                'label' => __( 'Icon Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_icon_color_hover' => [
                'label' => __( 'Icon Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link:hover svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'tab_icon_space' => [
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
                    '{{WRAPPER}} .fmtpl-tabs .nav-tabs.left .fmtpl-tab-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-tabs .nav-tabs.top .fmtpl-tab-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-tabs .nav-tabs.right .fmtpl-tab-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ],
            'tab_icon_size' => [
                'label' => __( 'Icon Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-icon *' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ],
            'tab_icon_position' => [
                'label' => __( 'Icon Position', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'fami-templatekits' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
            ],
            'tab_title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_title_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tab-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'tab_bottom_space' => [
                'label' => __( 'Content Spacing', 'fami-templatekits' ),
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
                    '{{WRAPPER}} .fmtpl-tabs .fmtpl-tabs-wrapper .nav-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'content_color' =>[
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-tabs .tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_3,
                ]
            ],
            'tabs_content_typography' => [
                'name' => 'tabs_content_typography',
                'selector' => '{{WRAPPER}} .fmtpl-tabs .tab-content',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
            ],
        ];
        $this->default_control = apply_filters('fmtpl-tabs-elementor-widget-control', $widget_control);
    }
	protected function _register_controls() {
        $this->define_widget_control();
        if (isset($this->default_control['layout'])) {
            $this->start_controls_section('_section_layout', ['label' => __('Layout', 'fami-templatekits')]);
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
            $this->end_controls_section();
        }

		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Tabs', 'fami-templatekits' ),
			]
		);
		$repeater = new Repeater();
        if (isset($this->default_control['tab_title'])) {
            $repeater->add_control(
                'tab_title',
                $this->default_control['tab_title']
            );
        }
        if (isset($this->default_control['tab_icon_type'])) {
            $repeater->add_control(
                'tab_icon_type',
                $this->default_control['tab_icon_type']
            );
        }
        if (isset($this->default_control['tab_img_icon'])) {
            $repeater->add_control(
                'tab_img_icon',
                $this->default_control['tab_img_icon']
            );
        }
        if (isset($this->default_control['tab_icon'])) {
            $repeater->add_control(
                'tab_icon',
                $this->default_control['tab_icon']
            );
        }
        if (isset($this->default_control['tab_content'])) {
            $repeater->add_control(
                'tab_content',
                $this->default_control['tab_content']
            );
        }
        if (sizeof($repeater->get_controls())> 1) {
            $this->add_control(
                'tabs',
                [
                    'label' => __('Tabs Items', 'fami-templatekits'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'tab_title' => __('Tab #1', 'fami-templatekits'),
                            'tab_content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits'),
                        ],
                        [
                            'tab_title' => __('Tab #2', 'fami-templatekits'),
                            'tab_content' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits'),
                        ],
                    ],
                    'title_field' => '{{{ tab_title }}}',
                ]
            );
        }
		$this->end_controls_section();

        $this->start_controls_section(
            '_section_tab_style',
            [
                'label' => __( 'Tabs Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['tab_title_width'])) {
            $this->add_responsive_control(
                'tab_title_width',
                $this->default_control['tab_title_width']
            );
        }
        if (isset($this->default_control['tab_title_typography'])){
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['tab_title_typography']
            );
        }
        if (isset($this->default_control['tab_title_alignment'])) {
            $this->add_control(
                'tab_title_alignment',
                $this->default_control['tab_title_alignment']
            );
        }
        if (isset($this->default_control['tab_title_color'],$this->default_control['tab_title_hover_color']) ||
            isset($this->default_control['tab_icon_color'],$this->default_control['tab_icon_color_hover']) ||
            isset($this->default_control['tab_title_bg_color'],$this->default_control['tab_title_bg_hover_color']))
        {
            $this->start_controls_tabs( 'tab_title_tabs' );
            $this->start_controls_tab(
                'tab_title_normal',
                [
                    'label' => __( 'Normal', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['tab_title_color'])) {
                $this->add_control(
                    'tab_title_color',
                    $this->default_control['tab_title_color']
                );
            }
            if (isset($this->default_control['tab_icon_color'])) {
                $this->add_control(
                    'tab_icon_color',
                    $this->default_control['tab_icon_color']
                );
            }
            if (isset($this->default_control['tab_title_bg_color'])) {
                $this->add_control(
                    'tab_title_bg_color',
                    $this->default_control['tab_title_bg_color']
                );
            }
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_title_hover',
                [
                    'label' => __( 'Hover', 'fami-templatekits' ),
                ]
            );
            if (isset($this->default_control['tab_title_hover_color'])) {
                $this->add_control(
                    'tab_title_hover_color',
                    $this->default_control['tab_title_hover_color']
                );
            }
            if (isset($this->default_control['tab_icon_color_hover'])) {
                $this->add_control(
                    'tab_icon_color_hover',
                    $this->default_control['tab_icon_color_hover']
                );
            }
            if (isset($this->default_control['tab_title_bg_hover_color'])) {
                $this->add_control(
                    'tab_title_bg_hover_color',
                    $this->default_control['tab_title_bg_hover_color']
                );
            }
            $this->end_controls_tab();
            $this->end_controls_tabs();
        }

        if (isset($this->default_control['tab_title_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['tab_title_border']
            );
        }
        if (isset($this->default_control['tab_title_border_hover_color'])) {
            $this->add_control(
                'tab_title_border_hover_color',
                $this->default_control['tab_title_border_hover_color']
            );
        }
        if (isset($this->default_control['list_item_border_radius'])) {
            $this->add_responsive_control(
                'list_item_border_radius',
                $this->default_control['list_item_border_radius']
            );
        }
        if (isset($this->default_control['tab_icon_position'])) {
            $this->add_control(
                'tab_icon_position',
                $this->default_control['tab_icon_position']
            );
        }
        if (isset($this->default_control['tab_icon_space'])) {
            $this->add_responsive_control(
                'tab_icon_space',
                $this->default_control['tab_icon_space']
            );
        }
        if (isset($this->default_control['tab_icon_size'])) {
            $this->add_control(
                'tab_icon_size',
                $this->default_control['tab_icon_size']
            );
        }
        if (isset($this->default_control['tab_title_padding'])) {
            $this->add_responsive_control(
                'tab_title_padding',
                $this->default_control['tab_title_padding']
            );
        }
        if (isset($this->default_control['tab_title_margin'])) {
            $this->add_responsive_control(
                'tab_title_margin',
                $this->default_control['tab_title_margin']
            );
        }
        if (isset($this->default_control['tab_bottom_space'])) {
            $this->add_responsive_control(
                'tab_bottom_space',
                $this->default_control['tab_bottom_space']
            );
        }
        $this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => __( 'Tabs Content', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        if (isset($this->default_control['content_color'])) {
            $this->add_control(
                'content_color',
                $this->default_control['content_color']
            );
        }

        if (isset($this->default_control['tabs_content_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['tabs_content_typography']
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
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        if (isset($settings['tabs']) && !empty($settings['tabs'])) {
            foreach ($settings['tabs'] as $tab_key => $tab){
                $tab_icon_str = '';
                if (isset($tab['tab_icon']) && $tab['tab_icon']) {
                    ob_start();
                    Icons_Manager::render_icon( $tab[ 'tab_icon' ]);
                    $tab_icon_str = ob_get_clean();
                } elseif (isset($tab['tab_img_icon']) && !empty($tab['tab_img_icon']['url'])) {
                    $this->add_render_attribute('image', 'src', $tab['tab_img_icon']['url']);
                    $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($tab['tab_img_icon']));
                    $this->add_render_attribute('image', 'title', Control_Media::get_image_title($tab['tab_img_icon']));
                    $tab_icon_str = Group_Control_Image_Size::get_attachment_image_html($tab, 'thumbnail', 'tab_img_icon');
                }
                $settings['tabs'][$tab_key]['tab_icon_str'] = $tab_icon_str;
            }
        }
        $html = apply_filters('fmtpl-tabs-widget-control_html','',$settings);
        if (!empty($html)){
            return $html;
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-tabs fmtpl-tabs-layout-'.$layout.'">';
        $tab_html = '';
        if (isset($settings['tabs']) && !empty($settings['tabs'])) {
            $tab_nav_class = isset($settings['tab_icon_position'])? ' '.$settings['tab_icon_position'] : ' left';
            $tab_html_nav = sprintf('<ul class="nav nav-tabs%s" role="tablist">',$tab_nav_class);
            $tab_active = true;
            $tab_content = '<div class="tab-content">';
            foreach ($settings['tabs'] as $tab){
                $tab_icon_str = '';
                if ($tab['tab_icon_str'] != '') {
                    $tab_icon_str = '<span class="fmtpl-tab-icon">'.$tab['tab_icon_str'].'</span>';
                }
                $nav_tab_link_class = $tab_active ? ' active':'';
                $tab_html_nav .= sprintf('<li class="nav-item"><a class="fmtpl-tab-link nav-link%1$s" id="%2$s-tab" data-toggle="tab" href="#%2$s" role="tab" aria-controls="%2$s" aria-selected="true">%3$s<span>%4$s</span></a></li>',
                    $nav_tab_link_class,
                    'fmtpl_tab_'.$tab['_id'],
                    $tab_icon_str,
                    $tab['tab_title']
                );
                $tab_content_html = $this->parse_text_editor( $tab['tab_content'] );
                $tab_content .= sprintf('<div class="tab-pane fade%1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">%3$s</div>',
                    $tab_active ? ' show active':'',
                    'fmtpl_tab_'.$tab['_id'],
                    $tab_content_html
                );
                $tab_active = false;
            }
            $tab_content .= '</div><!-- close tab-content--> ';//

            $tab_html_nav .= '</ul>';
            $tab_html .= sprintf('<div class="fmtpl-tabs-wrapper">%s %s</div><!-- close fmtpl-tabs-wrapper--> ',$tab_html_nav,$tab_content);
        }
        $html .= $tab_html;
        $html .= '</div><!--close fmtpl-tabs-->';
        return $html;
    }
}
