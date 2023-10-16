<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
class Fmtpl_Products_Grid extends Widget_Base
{
    protected $default_control;


    public function get_name()
    {
        return 'fmtpl-products-grid';
    }

    public function get_title()
    {
        return __('Products Grid', 'fami-templatekits');
    }

    public function get_icon()
    {
        return 'eicon-products fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'woocommerce', 'shop', 'store', 'product', 'archive', 'carousel' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $script_depends = apply_filters('fmtpl-products-grid-script-depends',[]);
        if (!empty($script_depends)){
            foreach ($script_depends as $script){
                $this->add_script_depends($script);
            }
        }
        $this->add_style_depends('fmtpl-product-grid');
    }
    protected function define_widget_control()
    {
        $products_item_style = apply_filters('fmtpl_products_item_style',['default' => __('Default Theme Style', 'fami-templatekits')]);
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
            'item_style' => [
                'label' => __('Product Item Style', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $products_item_style,
                'style_transfer' => true,
            ],
            'product_img' => [
                'label' => __('Product Image Size', 'fami-templatekits'),
                'name' => 'product_img', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'woocommerce_thumbnail',
                'exclude' => [ 'custom' ],
                'separator' => 'none',
            ],
            'hover_img' => [
                'label' => __('Product Image Hover', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'second' => __('Second Image', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],

            //Show Stars Rating
            'show_category' => [
                'label' => __('Show Category', 'fami-templatekits'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],

            'show_rating' => [
                'label' => __('Show Stars Rating', 'fami-templatekits'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',

            ],
            'columns' => [
                'label' => __( 'Columns', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 12,
            ],
            'title_text' =>
                [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' =>  __( 'This is the %highlight% title', 'fami-templatekits' ),
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
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
            ],
            'button_text' => [
                'label' => __( 'Text', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Button Text', 'fami-templatekits' ),
                'placeholder' => __( 'Type button text here', 'fami-templatekits' ),
                'label_block' => true,
            ],
            'ajax_button' => [
                'label' => __( 'Ajax load more', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Enable', 'fami-templatekits' ),
                'label_off' => __( 'Disable', 'fami-templatekits' ),
                'default' => 'no',
            ],
            'link' => [
                'label' => __( 'Link', 'fami-templatekits' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'fami-templatekits' ),
                'show_external' => true,
                'condition' => ['ajax_button!' => 'yes']
            ],
            'button_icon' => [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ],

            'source' => [
                'label' => __('Product Item Style', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bestselling',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'bestselling' => __('Bestselling', 'fami-templatekits'),
                    'latest' => __('Latest', 'fami-templatekits'),
                    'featured' => __('Featured', 'fami-templatekits'),
                    'top_rated' => __('Top rated', 'fami-templatekits'),
                    'on_sale' => __('On-sale', 'fami-templatekits'),
                    'manual' => __('Manual Selection', 'fami-templatekits'),
                ],
                'style_transfer' => true,
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
                'condition' => ['source!' => 'manual']
            ],
            'product_ids' => [
                'label' => __('Products', 'fami-templatekits'),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => true,
                'placeholder' => __('Search Products', 'fami-templatekits'),
                'data_options' => [
                    'post_type' => 'product',
                    'action' => 'fmtpl_post_list_query'
                ],
                'condition' => ['source' => 'manual']
            ],
            'newness' => [
                'label' => __( 'Number of days newness', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 1,
                'condition' => ['source' => 'latest']
            ],
            'max_items' => [
                'label' => __( 'Max Items', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'condition' => ['source!' => 'manual']
            ],
            'orderby' => [
                'label' => __( 'Order By', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __( 'Date', 'fami-templatekits' ),
                    'title' => __( 'Title', 'fami-templatekits' ),
                    'price' => __( 'Price', 'fami-templatekits' ),
                    'popularity' => __( 'Popularity', 'fami-templatekits' ),
                    'rating' => __( 'Rating', 'fami-templatekits' ),
                    'rand' => __( 'Random', 'fami-templatekits' ),
                    'menu_order' => __( 'Menu Order', 'fami-templatekits' ),
                ],
            ],
            'order' => [
                'label' => __( 'Order', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => __( 'ASC', 'fami-templatekits' ),
                    'desc' => __( 'DESC', 'fami-templatekits' ),
                ],
            ],

            //style content
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-content' => 'text-align: {{VALUE}}',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-products-title',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-products-title .highlight',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ],
            'desc_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-desc' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'desc_typography' => [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-products-desc',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
            ],
            'btn_position' => [
                'label' => __('Button Position', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __('Before Products', 'fami-templatekits'),
                    'after' => __('After Products', 'fami-templatekits'),
                ],
                'style_transfer' => true,
            ],
            'btn_align' => [
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button-wrapper' => 'text-align: {{VALUE}}',
                ],
            ],
            'btn_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ],

            'btn_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'color: {{VALUE}};fill: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
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
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_bg_color_hover' =>
                [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ],
            'btn_border' => [
                'name' => 'btn_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-products-button',
            ],

            'btn_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],
            'btn_border_color_hover' => [
                'label' => __( 'Border Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button:hover' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button.left .fmtpl-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-products .fmtpl-products-button.right .fmtpl-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['button_icon[value]!' => '']
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
                    '{{WRAPPER}} .fmtpl-products .fmtpl-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['button_icon[value]!' => '']
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
                'condition' => ['button_icon[value]!' => '']
            ],
            'btn_text_options' => [
                'label' => __( 'Button Text', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ],
            'btn_text_typography' => [
                'name' => 'btn_text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-products .fmtpl-btn-text',
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
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_type' => [
                'name' => 'divider_border_type',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider',
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],

            'divider_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_divider' => 'yes'
                ]
            ],
            'divider_border_color_hover' => [
                'label' => __( 'Border Hover Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-products-content .fmtpl-divider:hover' => 'border-color: {{VALUE}};',
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
        $this->default_control = apply_filters('fmtpl-products-grid-elementor-widget-control', $widget_control);
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

        if (isset($this->default_control['item_style'])) {
            $this->add_control(
                'item_style',
                $this->default_control['item_style']
            );
        }
        if (isset($this->default_control['product_img'])){
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                $this->default_control['product_img']
            );
        }
        if (isset($this->default_control['hover_img'])) {
            $this->add_control(
                'hover_img',
                $this->default_control['hover_img']
            );
        }
        //


        if (isset($this->default_control['show_category'])) {
            $this->add_control(
                'show_category',
                $this->default_control['show_category']
            );
        }

        if (isset($this->default_control['show_rating'])) {
            $this->add_control(
                'show_rating',
                $this->default_control['show_rating']
            );
        }
        /*if (isset($this->default_control['show_pagination'])) {
            $this->add_control(
                'show_pagination',
                $this->default_control['show_pagination']
            );
        }*/
        //
        //columns
        if (isset($this->default_control['columns'])) {
            $this->add_responsive_control(
                'columns',
                $this->default_control['columns']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_heading',
            [
                'label' => __('Content', 'fami-templatekits'),
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
        if (isset($this->default_control['button_icon'])) {
            $this->add_control(
                'button_icon',
                $this->default_control['button_icon']
            );
        }
        if (isset($this->default_control['ajax_button'])) {
            $this->add_control(
                'ajax_button',
                $this->default_control['ajax_button']
            );
        }
        if (isset($this->default_control['link'])) {
            $this->add_control(
                'link',
                $this->default_control['link']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_product_query',
            [
                'label' => __('Products Query', 'fami-templatekits'),
            ]
        );
        if (isset($this->default_control['source'])) {
            $this->add_control(
                'source',
                $this->default_control['source']
            );
        }
        if (isset($this->default_control['category_id'])) {
            $this->add_control(
                'category_id',
                $this->default_control['category_id']
            );
        }
        if (isset($this->default_control['product_ids'])) {
            $this->add_control(
                'product_ids',
                $this->default_control['product_ids']
            );
        }
        //max_items
        if (isset($this->default_control['max_items'])) {
            $this->add_control(
                'max_items',
                $this->default_control['max_items']
            );
        }
        if (isset($this->default_control['orderby'])) {
            $this->add_control(
                'orderby',
                $this->default_control['orderby']
            );
        }
        if (isset($this->default_control['order'])) {
            $this->add_control(
                'order',
                $this->default_control['order']
            );
        }
        $this->end_controls_section();



        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Content', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['content_alignment'])) {
            $this->add_responsive_control(
                'content_alignment',
                $this->default_control['content_alignment']
            );
        }
        if (isset($this->default_control['title_bottom_space'])) {
            $this->add_control(
                '_title_heading',
                [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
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
        if (isset($this->default_control['highlight_title_color'])) {
            $this->add_control(
                '_highlight_title_heading',
                [
                    'label' => __( 'Highlight Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
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
            '_divide_heading',
            [
                'label' => __( 'Divider', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
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
                    'label' => __('Divider Border Style', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
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
        if (isset($this->default_control['desc_bottom_space'])) {
            $this->add_control(
                '_desc_heading',
                [
                    'label' => __( 'Description', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
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
            $this->add_control(
                '_btn_heading',
                [
                    'label' => __( 'Button Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
        if (isset($this->default_control['btn_position'])) {
            $this->add_responsive_control(
                'btn_position',
                $this->default_control['btn_position']
            );
        }
        if (isset($this->default_control['btn_align'])) {
            $this->add_control(
                'btn_align',
                $this->default_control['btn_align']
            );
        }
        if (isset($this->default_control['btn_margin'])) {
            $this->add_responsive_control(
                'btn_margin',
                $this->default_control['btn_margin']
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
        if (isset($this->default_control['btn_border'])) {
            $this->add_control(
                'btn_border_heading',
                [
                    'label' => __('Button Border', 'fami-templatekits'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['btn_border']
            );
        }
        if (isset($this->default_control['btn_border_radius'])) {
            $this->add_responsive_control(
                'btn_border_radius',
                $this->default_control['btn_border_radius']
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
        if (isset($this->default_control['btn_border_color_hover'])) {
            $this->add_control(
                'btn_border_color_hover',
                $this->default_control['btn_border_color_hover']
            );
        }


        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
            'btn_icon_options',
            [
                'label' => __( 'Icon Options', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['button_icon[value]!' => '']
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
    }

    protected function render()
    {
        echo $this->fmtpl_render();
    }

    protected function fmtpl_render() {
        $settings = $this->get_settings_for_display();
        $settings['link_attr_str'] = '';
        $settings['icon_str'] = '';
        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && !empty($settings['button_icon']['value']))) {
            if (isset($settings['link']) && ! empty( $settings['link']['url'] ) ) {
                $this->add_link_attributes( 'link', $settings['link'] );
                $link_attr_str =  $this->get_render_attribute_string( 'link' );
            } else {
                $link_attr_str = 'href="#." title=""';
            }
            $settings['link_attr_str'] = $link_attr_str;
            $icon_str = '';
            if (isset($settings['button_icon']) && $settings['button_icon']) {
                ob_start();
                Icons_Manager::render_icon( $settings[ 'button_icon' ]);
                $icon_str = ob_get_clean();
                if ($icon_str != '') {
                    $icon_str = '<span class="fmtpl-btn-icon">'.$icon_str.'</span>';
                }
            }
            $settings['icon_str'] = $icon_str;
        }
        $html = apply_filters('fmtpl-products-grid-widget-control_html','',$settings);
        if (!empty($html)){
            return $html;
        }
        $data_string = '';
        $layout_class = isset($settings['layout']) ? $settings['layout'] : 'default';
        $fmtpl_query = fmtpl_getProducts($settings);
        if (isset($settings['ajax_button']) && $settings['ajax_button'] == 'yes'){
            $data_setting = fmtpl_get_products_elementor_settings($settings);
            $data_string = ' data-settings ="'.urlencode(json_encode($data_setting)).'"';
            $data_string .= ' data-page="1"';
            if ($fmtpl_query->max_num_pages < 2){
                $layout_class .= ' disabled_load';
            }
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-products fmtpl-products-layout-'.$layout_class.' grid woocommerce"'.$data_string.'>';
        $content_html = '';
        if (isset($settings['title_text']) && !empty($settings['title_text'])) {
            $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-widget-title fmtpl-products-title">'.$title.'</div>';
        }
        if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
            $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
        }
        if (isset($settings['description']) && !empty($settings['description'])){
            $content_html .= '<div class="fmtpl-products-desc">'.$settings['description'].'</div>';
        }
        $button_html = '';
        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || isset($settings['icon_str'])) {
            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
            if (isset($settings['ajax_button']) && $settings['ajax_button'] == 'yes'){
                $btn_class .= ' ajax_load_product_btn';
            }
            $button_html .= '<div class="fmtpl-products-button-wrapper"><a class="fmtpl-button-default fmtpl-products-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$settings['icon_str'].'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
            if (isset($settings['ajax_button']) && $settings['ajax_button'] == 'yes'){
                $button_html .= '<span class="fmtpl_ajax_notice">'.esc_html__('All products have been loaded','fami-templatekits').'</span>';
            }
            $button_html .='</div>';
        }
        if ($content_html != '') {
            $html .= '<div class="fmtpl-products-content">'.$content_html.'</div>';
        }
        if (isset($settings['btn_position']) && $settings['btn_position'] == 'before' && !empty($button_html)) {
            $html .= $button_html;
        }

        $html .= fmtpl_getProducts_Html($settings,$fmtpl_query);
        if (isset($settings['btn_position']) && $settings['btn_position'] == 'after' && !empty($button_html)) {
            $html .= $button_html;
        }
        $html .='</div>';
        return $html;
    }
}
