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
class Fmtpl_Category_Banner extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'fmtpl-category-banner';
    }

    public function get_title()
    {
        return __('Category Banner', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-product-categories fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'image', 'photo', 'banner', 'category', 'woo' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-category-banner');
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
                    '{{WRAPPER}} .fmtpl-category-banner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],
            'overlay' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'overlay_hover' => [
                'label' => __( 'Background Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner:hover .fmtpl-category-banner-overlay' => 'background-color: {{VALUE}};',
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
                'condition' => ['custom_title' => 'yes'],
                'separator' => 'before',
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
                'condition' => [
                    'custom_title' => 'yes'
                ],
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
            'icon_size' =>
            [
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
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title .fmtpl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ],
            'icon_space' =>
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
                'condition' => [
                    'icon[value]!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title.icon-pos-left .fmtpl-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title.icon-pos-right .fmtpl-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title.icon-pos-top .fmtpl-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title.icon-pos-bottom .fmtpl-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ],
            'icon_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title .fmtpl-icon, {{WRAPPER}}' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'icon_color_hover' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title:hover .fmtpl-icon' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'description' => [
                'label' => __( 'Description', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
                'separator' => 'before',
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
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'background-color: {{VALUE}};',
                ],
            ],
            'background_hover_color' =>
                [
                    'label' => __( 'Background Hover Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-category-banner:hover .fmtpl-category-banner-content' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-category-banner' => 'justify-content: {{VALUE}}',
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
                    '{{WRAPPER}} .fmtpl-category-banner' => 'align-items: {{VALUE}}',
                ],

            ],
            'box_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'box_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'border' => [
                'name' => 'border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content',
            ],

            'border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],

            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title, {{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_color_hover' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title:hover, {{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title:hover svg' => 'fill: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' => __( 'Highlight Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_color_hover' => [
                'label' => __( 'Highlight Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title:hover .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'label' => __( 'Highlight Typography', 'fami-templatekits' ),
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-category-banner .fmtpl-category-banner-title .highlight',
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
                    '{{WRAPPER}} .fmtpl-category-banner .banner-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .banner-desc' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .fmtpl-category-banner .banner-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
            ],
            'category_id' => [
                'label' => __('Product Categories', 'fami-templatekits'),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => false,
                'placeholder' => __('Search Category', 'fami-templatekits'),
                'data_options' => [
                    'taxonomy_type' => 'product_cat',
                    'action' => 'fmtpl_taxonomy_list_query'
                ],
            ],
            'show_product_count' => [
                'label' => __( 'Show Product Count', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ],

            'custom_title' => [
                'label' => __( 'Custom Title', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],
            'product_count_title' => [
                'label' => __( 'Product Count Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Product', 'fami-templatekits' ),
                'condition' => ['show_product_count' => 'yes'],
                'separator' => 'before',
            ],
            'count_number_position' => [
                'label' => __('Number Position', 'fami-templatekits'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'fami-templatekits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
            ],
            'count_number_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-products-count' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'count_number_color_hover' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-category-banner .fmtpl-products-count:hover' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'count_number_typography' => [
                'name' => 'count_number_typography',
                'selector' => '{{WRAPPER}} .fmtpl-category-banner .fmtpl-products-count',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
            ],
            // & Description
        ];
        $this->default_control = apply_filters('fmtpl-category-banner-elementor-widget-control', $widget_control);
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
        if (isset($this->default_control['category_id'])) {
            $this->add_control(
                'category_id',
                $this->default_control['category_id']
            );
        }

        if (isset($this->default_control['show_product_count'])) {
            $this->add_control(
                'show_product_count',
                $this->default_control['show_product_count']
            );
        }
        if (isset($this->default_control['product_count_title'])) {
            $this->add_control(
                'product_count_title',
                $this->default_control['product_count_title']
            );
        }

        if (isset($this->default_control['custom_title'])) {
            $this->add_control(
                'custom_title',
                $this->default_control['custom_title']
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
        if (isset($this->default_control['background_hover_color'])) {
            $this->add_control(
                'background_hover_color',
                $this->default_control['background_hover_color']
            );
        }
        //
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
                    'type' => Controls_Manager::HEADING,
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
            $this->add_control(
                'title_bottom_space',
                $this->default_control['title_bottom_space']
            );
        }
        $this->add_control(
            'title_tabs_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->start_controls_tabs( 'title_tabs' );
        $this->start_controls_tab(
            'title_tab_normal',
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
            'title_tab_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['title_color_hover'])) {
            $this->add_control(
                'title_color_hover',
                $this->default_control['title_color_hover']
            );
        }
        if (isset($this->default_control['highlight_title_color_hover'])) {
            $this->add_control(
                'highlight_title_color_hover',
                $this->default_control['highlight_title_color_hover']
            );
        }

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'title_tabs_hr_1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        if (isset($this->default_control['title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['title_typography']
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
        $this->start_controls_tabs( 'icon_tabs' );
        $this->start_controls_tab(
            'icon_tab_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),

            ]
        );
        if (isset($this->default_control['icon_color'])) {
            $this->add_control(
                'icon_color',
                $this->default_control['icon_color']
            );
        }

        $this->end_controls_tab();
        $this->start_controls_tab(
            'icon_tab_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['icon_color_hover'])) {
            $this->add_control(
                'icon_color_hover',
                $this->default_control['icon_color_hover']
            );
        }

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            '_section_desc_style',
            [
                'label' => __( 'Description', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['desc_bottom_space'])) {
            $this->add_control(
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
            '_section_count_style',
            [
                'label' => __( 'Product Count', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_product_count' => 'yes'],
            ]
        );
        if (isset($this->default_control['count_number_position'])) {
            $this->add_control(
                'count_number_position',
                $this->default_control['count_number_position']
            );
        }
        $this->add_control(
            'count_number_tabs_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->start_controls_tabs( 'count_number_tabs' );
        $this->start_controls_tab(
            'count_number_tab_normal',
            [
                'label' => __( 'Normal', 'fami-templatekits' ),

            ]
        );
        if (isset($this->default_control['count_number_color'])) {
            $this->add_control(
                'count_number_color',
                $this->default_control['count_number_color']
            );
        }
        $this->end_controls_tab();
        $this->start_controls_tab(
            'count_number_tab_hover',
            [
                'label' => __( 'Hover', 'fami-templatekits' ),
            ]
        );
        if (isset($this->default_control['count_number_color_hover'])) {
            $this->add_control(
                'count_number_color_hover',
                $this->default_control['count_number_color_hover']
            );
        }

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'count_number_tabs_hr_1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        if (isset($this->default_control['count_number_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['count_number_typography']
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
        $product_cat = false;
        $cat_link = '';
        if (isset($settings['category_id']) && !empty($settings['category_id'])){
            $product_cat = get_term( $settings['category_id'], 'product_cat' );
            if (!empty($product_cat)){
                $cat_link = get_term_link( $product_cat->term_id, 'product_cat' );
            }
        }

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
            $this->add_render_attribute('image', 'src', $settings['image']['url']);
            $this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['image']));
            $this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['image']));
            $settings['image_html'] = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image');
        }
        $html = apply_filters('fmtpl-category-banner-elementor-widget-html','',$settings);
        if (empty($html)){
            //check for old theme version
            $html = apply_filters('fmtpl-category-banner-elementor-widget-control_html','',$settings);
        }
        if (!empty($html)){
            return $html;
        }

        $html = '<div class="fmtpl-elementor-widget fmtpl-category-banner '.$settings['layout'].'">';

        if ( ! empty( $style_str ) ) {
            $html .= '<div class="fmtpl-category-banner-background zoom" '.$style_str.'></div>';
        }

        //$html .= $style_str . '>';
        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="fmtpl-category-banner-overlay"></div>';
        }
        if ($settings['image_html'] != ''){
            if ($cat_link != ''){
                $html .= '<figure class="fmtpl-category-banner-box-img has-link"><a class="banner-img-link" href="'.$cat_link.'">' . $settings['image_html'] . '</a></figure>';
            } else {
                $html .= '<figure class="fmtpl-category-banner-box-img">' . $settings['image_html'] . '</figure>';
            }
        }
        $content_html = '';
        $icon_class = '';
        if (isset($settings['icon_position'])){
            $icon_class = ' icon-pos-'.$settings['icon_position'];
        }
        $content_html .= '<div class="fmtpl-widget-title fmtpl-category-banner-title'.$icon_class.'">';
        if ($icon_str != '' && isset($settings['icon_position']) && ($settings['icon_position'] == 'left' || $settings['icon_position'] == 'top')){
            $content_html .= $icon_str;
        }
        if (isset($settings['custom_title']) && $settings['custom_title'] == 'yes'){
            if (isset($settings['title_text'])) {
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<span class="title-content">';
                if ($cat_link) {
                    $content_html .= '<a class="category-link" href="'.$cat_link.'">'.$title.'</a>';
                } else {
                    $content_html .= $title;
                }
                $content_html .= '</span>';
            }
        } else {
            if ($product_cat) {
                $content_html .= '<span class="title-content">';
                $content_html .= '<a class="category-link" href="'.$cat_link.'">'.$product_cat->name.'</a>';
                $content_html .= '</span>';
            }
        }

        if ($icon_str != '' && ($settings['icon_position'] == 'right' || $settings['icon_position'] == 'bottom')){
            $content_html .= $icon_str;
        }
        $content_html .= '</div>';

        $product_count_title = (isset($settings['product_count_title']) && !empty($settings['product_count_title'])) ? $settings['product_count_title']: __('Products ','fami-templatekits');
        if ($product_cat && isset($settings['show_product_count']) && $settings['show_product_count'] == 'yes'){
            $content_html .= '<div class="fmtpl-products-count '.(isset($settings['count_number_position'])? $settings['count_number_position']:'').'"><span class="products-count-title">'.$product_count_title.'</span><span class="count-value">'.$product_cat->count.'</span></div>';
        }

        if (isset($settings['description'])){
            $content_html .= '<div class="banner-desc">'.$settings['description'].'</div>';
        }

        if ($content_html != '') {
            $html .= '<div class="fmtpl-category-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';
        return $html;
    }
}
