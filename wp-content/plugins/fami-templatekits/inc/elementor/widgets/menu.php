<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
class Fmtpl_Nav_Menu extends Widget_Base
{
    private $default_control;
    private $menu_options;

    public function get_name()
    {
        return 'fmtpl-menu';
    }

    public function get_title()
    {
        return __('Nav Menu', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-menu');
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();

        $options = [];

        foreach ( $menus as $menu ) {
            $options[ $menu->slug ] = $menu->name;
        }

        return $options;
    }

    private function define_widget_control()
    {
        $this->menu_options = $this->get_available_menus();
        $menu_default = '';
        if (!empty($this->menu_options)){
            $menu_default = array_keys( $this->menu_options )[0];
        }

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
            'title' => [
                'label' => __('Title', 'fami-templatekits'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'label_block' => true,
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
            'nav_menu' => [
                'label' => esc_html__('Menu', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->menu_options,
                'default' => $menu_default,
                'label_block' => true,
                'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'fami-templatekits' ), admin_url( 'nav-menus.php' ) ),
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
                'selectors_dictionary' => [
                    'left' => 'text-align: left;margin-right: auto',
                    'center' => 'text-align: center;margin-right: auto; margin-left: auto',
                    'right' => 'text-align: right;margin-left: auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu' => '{{VALUE}}',
                    '{{WRAPPER}} .fmtpl-menu .menu-item:after' => '{{VALUE}}',
                ],
            ],
            'content_padding' => [
                'label' => __('Padding', 'fami-templatekits'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ],
            'show_divider' => [
                'label' => __( 'Show Divider', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'default' => 'no',
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
                    '{{WRAPPER}} .fmtpl-menu .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-menu .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .fmtpl-divider' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-menu .fmtpl-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_type' => [
                'name' => 'divider_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-menu .fmtpl-divider',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'title_color' => [
                    'label' => __('Color', 'fami-templatekits'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-menu-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'title!' => '',
                    ],
                    'separator' => 'before',
                ],
            'title_typography' => [
                    'name' => 'title_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .fmtpl-menu-title',
                    'condition' => [
                        'title!' => '',
                    ],
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu-title .highlight' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'highlight_title!' => '',
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .fmtpl-menu-title .highlight',
                'condition' => [
                    'title!' => '',
                ],
            ],
            'title_padding' => [
                'label' => __('Padding', 'fami-templatekits'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ],
            'item_color' => [
                'label' => __('Color', 'fami-templatekits'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .menu-item, {{WRAPPER}} .fmtpl-menu .menu-item a' => 'color: {{VALUE}}',
                ],
            ],
            'item_color_hover' => [
                'label' => __('Color', 'fami-templatekits'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .menu-item:hover, {{WRAPPER}} .fmtpl-menu .menu-item a:hover' => 'color: {{VALUE}}',
                ],
            ],
            'item_typography' => [
                'name' => 'item_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' =>   '{{WRAPPER}} .fmtpl-menu .menu-item, {{WRAPPER}} .fmtpl-menu .menu-item a',
            ],
            'show_item_divider' => [
                'label' => __( 'Show Items Divider', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'default' => 'no',
            ],

            'item_divider_width' => [
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
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_height' => [
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
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after' => 'background-color: {{VALUE}};',
                ],
                'default' => '#000000',
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_border_type' => [
                'name' => 'item_divider_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after',
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_item_divider' => 'yes'
                ]
            ],
            'item_divider_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-menu .has_item_divider .menu-item:hover:after' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'show_item_divider',
                            'operator' => '==',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'item_divider_border_type_border',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
            ],
        ];
        $this->default_control = apply_filters('fmtpl-menu-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_menu', ['label' => __('Settings', 'fami-templatekits')]);
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
        if (isset($this->default_control['nav_menu'])) {
            $this->add_control(
                'nav_menu',
                $this->default_control['nav_menu']
            );
        }

        $this->end_controls_section();
        $this->start_controls_section(
            'nav_menu_content_style',
            [
                'label' => __('General', 'fami-templatekits'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['content_alignment'])) {
            $this->add_responsive_control(
                'content_alignment',
                $this->default_control['content_alignment']
            );
        }
        if (isset($this->default_control['content_vertical_position'])) {
            $this->add_control(
                'content_vertical_position',
                $this->default_control['content_vertical_position']
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
            'nav_menu_title_style',
            [
                'label' => __('Title', 'fami-templatekits'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => ''
                ]
            ]
        );
        if (isset($this->default_control['show_divider'])) {
            $this->add_control(
                'show_divider',
                $this->default_control['show_divider']
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
        if (isset($this->default_control['divider_border_color_hover'])) {
            $this->add_control(
                'divider_border_color_hover',
                $this->default_control['divider_border_color_hover']
            );
        }

        if (isset($this->default_control['divider_margin'])) {
            $this->add_responsive_control(
                'divider_margin',
                $this->default_control['divider_margin']
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
        $this->add_control(
            'title_hd1',
            [
                'label' => __( 'Highlight Title', 'plugin-name' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
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
        $this->add_control(
            'title_hd2',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        if (isset($this->default_control['title_padding'])) {
            $this->add_responsive_control(
                'title_padding',
                $this->default_control['title_padding']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'nav_menu_item_style',
            [
                'label' => __('Menu Items', 'fami-templatekits'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'item_tabs' );
        $this->start_controls_tab(
            'item_tab_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['item_color'])) {
            $this->add_control(
                'item_color',
                $this->default_control['item_color']
            );
        }
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_tab_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['item_color_hover'])) {
            $this->add_control(
                'item_color_hover',
                $this->default_control['item_color_hover']
            );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'item_hd1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        if (isset($this->default_control['item_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['item_typography']
            );
        }
        if (isset($this->default_control['show_item_divider'])) {
            $this->add_control(
                'show_item_divider',
                $this->default_control['show_item_divider']
            );
        }

        if (isset($this->default_control['item_divider_width'])) {
            $this->add_responsive_control(
                'item_divider_width',
                $this->default_control['item_divider_width']
            );
        }

        if (isset($this->default_control['item_divider_height'])) {
            $this->add_responsive_control(
                'item_divider_height',
                $this->default_control['item_divider_height']
            );
        }

        if (isset($this->default_control['item_divider_color'])) {
            $this->add_control(
                'item_divider_color',
                $this->default_control['item_divider_color']
            );
        }
        if (isset($this->default_control['item_divider_border_type'])) {
            $this->add_control(
                'item_divider_border_heading',
                [
                    'label' => __('Divider Border Style', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'show_item_divider' => 'yes'
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['item_divider_border_type']
            );
        }
        if (isset($this->default_control['item_divider_border_radius'])) {
            $this->add_responsive_control(
                'item_divider_border_radius',
                $this->default_control['item_divider_border_radius']
            );
        }
        if (isset($this->default_control['item_divider_border_color_hover'])) {
            $this->add_control(
                'item_divider_border_color_hover',
                $this->default_control['item_divider_border_color_hover']
            );
        }

        if (isset($this->default_control['item_divider_margin'])) {
            $this->add_responsive_control(
                'item_divider_margin',
                $this->default_control['item_divider_margin']
            );
        }
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $menu = get_term_by('slug', $settings['nav_menu'], 'nav_menu');
        $heading = '';
        if (!empty($settings['title'])){
            $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
            $heading = '<h3 class="fmtpl-menu-title widget-title">'.$title.'</h3>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $heading .= '<div class="fmtpl-divider">&nbsp;</div>';
        }
        $item_divider_class = '';
        if (isset($settings['show_item_divider']) && $settings['show_item_divider'] == 'yes'){
            $item_divider_class = ' has_item_divider';
        }
        ?>
        <div class="fmtpl-elementor-widget fmtpl-menu <?php echo($settings['layout']); ?>">
            <?php if (!empty($heading)) :?>
                <div class="fmtpl-menu-heading">
                    <?php echo $heading;?>
                </div>
            <?php endif; ?>
            <div class="nav_menu_content<?php echo $item_divider_class?>">
                <?php
                if ( !is_wp_error( $menu ) && is_object( $menu ) && !empty( $menu ) ) {
                    $nav_menu = ! empty( $menu->term_id) ? wp_get_nav_menu_object( $menu->term_id) : false;
                    if(!$nav_menu){
                        return;
                    }
                    $nav_menu_args = array(
                        'fallback_cb' => '',
                        'menu'        => $nav_menu
                    );
                    wp_nav_menu( $nav_menu_args);

                } else {
                    echo esc_html__( 'No content.', 'urus' );
                }
                ?>
            </div>
        </div>
        <?php
    }
}
