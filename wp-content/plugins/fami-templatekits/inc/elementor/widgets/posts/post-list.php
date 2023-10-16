<?php
/**
 * Post List widget class
 *
 * @package Fami Template Kits
 */


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes;

defined( 'ABSPATH' ) || die();



class Fmtpl_Post_List extends Widget_Base {
    private $default_control;
	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */

    public function get_name() {
        return 'fmtpl-post-list';
    }

	public function get_title () {
		return __( 'Post List', 'fami-templatekits' );
	}

    public function get_categories() {
        return [ 'fami-elements' ];
    }

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'eicon-post-list fmtpl-widget-icon';
	}

	public function get_keywords () {
		return ['fami','fm', 'posts', 'post','page', 'post-list', 'list', 'news' ];
	}

	/**
	 * Get a list of All Post Types
	 *
	 * @return array
	 */
	public function get_post_types () {
		//$post_types = fmtpl_get_post_types( [],[ 'elementor_library', 'attachment' ] );
        $post_types = [
            'post' => 'Post',
            'page' => 'Page'
        ];
		return $post_types;
	}
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-post-list');
    }

    private function define_widget_control(){
        $post_type = $this->get_post_types();
        $widget_control = [
            'post_type' => [
                'label' => __( 'Source', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'options' => $post_type,
                'default' => key( $post_type ),
            ],
            'show_post_by' => [
                'label' => __( 'Show post by:', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'recent',
                'options' => [
                    'recent' => __( 'Recent Post', 'fami-templatekits' ),
                    'selected' => __( 'Selected Post', 'fami-templatekits' ),
                ],

            ],
            'posts_per_page' => [
                'label' => __( 'Item Limit', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'dynamic' => [ 'active' => true ],
                'condition' => [
                    'show_post_by' => [ 'recent' ]
                ]
            ],
            //items
            'item_title' => [
                'label' => __( 'Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Customize Title', 'fami-templatekits' ),
            ],
            'post_id' => [
                'label' => __( 'Select Post', 'fami-templatekits' ),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => false,
                'placeholder' => 'Search Post',
                'data_options' => [
                    'post_type' => 'post',
                    'action' => 'fmtpl_post_list_query'
                ],
            ],
            //end items
            'view' => [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'label_block' => false,
                'type' => Controls_Manager::CHOOSE,
                'default' => 'list',
                'options' => [
                    'list' => [
                        'title' => __( 'List', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => __( 'Inline', 'fami-templatekits' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'style_transfer' => true,
            ],
            'feature_image' => [
                'label' => __( 'Featured Image', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
            ],
            'post_image' => [
                'name' => 'post_image',
                'default' => 'thumbnail',
                'exclude' => [
                    'custom'
                ],
                'condition' => [
                    'feature_image' => 'yes'
                ]
            ],
            'list_icon' => [
                'label' => __( 'List Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'feature_image!' => 'yes'
                ]
            ],
            'icon' => [
                'label' => __( 'Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'far fa-check-circle',
                    'library' => 'reguler'
                ],
                'condition' => [
                    'list_icon' => 'yes',
                    'feature_image!' => 'yes'
                ]
            ],
            'category_data' => [
                'label' => __( 'Category', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'post_type' => 'post',
                ]
            ],
            'category_icon' => [
                'label' => __( 'Category Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-folder-open',
                    'library' => 'reguler',
                ],
                'condition' => [
                    'category_data' => 'yes',
                    'post_type' => 'post',
                ]
            ],
            'excerpt' => [
                'label' => __( 'Show Excerpt', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
            ],
            'meta' => [
                'label' => __( 'Show Meta', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
            ],
            'author_meta' => [
                'label' => __( 'Author', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'meta' => 'yes',
                ]
            ],
            'author_icon' => [
                'label' => __( 'Author Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-user',
                    'library' => 'reguler',
                ],
                'condition' => [
                    'meta' => 'yes',
                    'author_meta' => 'yes',
                ]
            ],
            'date_meta' => [
                'label' => __( 'Date', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'meta' => 'yes',
                ]
            ],
            'date_icon' => [
                'label' => __('Date Icon', 'fami-templatekits'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-calendar-check',
                    'library' => 'reguler',
                ],
                'condition' => [
                    'meta' => 'yes',
                    'date_meta' => 'yes',
                ]
            ],
            'meta_position' => [
                'label' => __('Meta Position', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => __('Top', 'fami-templatekits'),
                    'bottom' => __('Bottom', 'fami-templatekits'),
                ],
                'condition' => [
                    'meta' => 'yes',
                ]
            ],
            'title_tag' => [
                'label' => __( 'Title HTML Tag', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => __( 'H1', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2' => [
                        'title' => __( 'H2', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3' => [
                        'title' => __( 'H3', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4' => [
                        'title' => __( 'H4', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5' => [
                        'title' => __( 'H5', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6' => [
                        'title' => __( 'H6', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-h6'
                    ],
                    'span' => [
                        'title' => __( 'span', 'fami-templatekits' ),
                        'icon' => 'eicon-editor-code'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
            ],
            'item_align' => [
                'label' => __( 'Alignment', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'fami-templatekits' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start',
                    'center' => 'justify-content: center',
                    'right' => 'justify-content: flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-inline' => '{{VALUE}};'
                ],
                'condition' => [
                    'view' => 'inline',
                ]
            ],
            'list_item_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
            'list_item_padding' => [
                'label' => __('Padding', 'fami-templatekits'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
            'list_item_background' => [
                'name' => 'list_item_background',
                'label' => __('Background', 'fami-templatekits'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item',
            ],
            'list_item_box_shadow' => [
                'name' => 'list_item_box_shadow',
                'label' => __( 'Box Shadow', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item',
            ],
            'list_item_border' => [
                'name' => 'list_item_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item',
            ],
            'list_item_border_radius' => [
                'label' => __( 'Border Radius', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
            'advance_style' => [
                'label' => __( 'Advance Style', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'On', 'fami-templatekits' ),
                'label_off' => __( 'Off', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => '',
            ],
            'list_item_first' => [
                'label' => __( 'First Item', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'list_item_first_child_margin' => [
                'label' => __('Margin', 'fami-templatekits'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'list_item_first_child_border' => [
                'name' => 'list_item_first_child_border',
                'label' => __('Border', 'fami-templatekits'),
                'selector' => '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item:first-child',
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'list_item_last' => [
                'label' => __( 'Last Item', 'fami-templatekits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'list_item_last_child_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'list_item_last_child_border' => [
                'name' => 'list_item_last_child_border',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item:last-child',
                'condition' => [
                    'advance_style' => 'yes',
                ]
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'fami-templatekits' ),
                'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .fmtpl-post-list-title',
            ],
            'title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-title, {{WRAPPER}} .fmtpl-post-list-title a' => 'color: {{VALUE}}',
                ],
            ],
            'title_hvr_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list .fmtpl-post-list-item .fmtpl-post-list-title a:hover' => 'color: {{VALUE}}',
                ],
            ],
            'icon_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} span.fmtpl-post-list-icon' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'condition' => [
                    'feature_image!' => 'yes',
                    'list_icon' => 'yes',
                ]
            ],
            'icon_size' => [
                'label' => __( 'Font Size', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} span.fmtpl-post-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_image!' => 'yes',
                    'list_icon' => 'yes',
                ]
            ],
            'icon_line_height' => [
                'label' => __( 'Line Height', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} span.fmtpl-post-list-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_image!' => 'yes',
                    'list_icon' => 'yes',
                ]
            ],
            'image_width' => [
                'label' => __( 'Image Width', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-item a img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_image' => 'yes',
                ]
            ],
            'image_boder' => [
                'name' => 'image_boder',
                'label' => __( 'Border', 'fami-templatekits' ),
                'selector' => '{{WRAPPER}} .fmtpl-post-list-item a img',
                'condition' => [
                    'feature_image' => 'yes',
                ]
            ],
            'image_boder_radius' => [
                'label' => __('Border Radius', 'fami-templatekits'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-item a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'feature_image' => 'yes',
                ]
            ],
            'icon_margin_right' => [
                'label' => __('Margin Right', 'fami-templatekits'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} span.fmtpl-post-list-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-post-list-item a img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ],
            'excerpt_typography' => [
                'name' => 'excerpt_typography',
                'label' => __('Typography', 'fami-templatekits'),
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .fmtpl_post_excerpt',
            ],
            'excerpt_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl_post_excerpt' => 'color: {{VALUE}};',
                ],
            ],
            'excerpt_box_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl_post_excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
            'meta_typography' => [
                'name' => 'meta_typography',
                'label' => __( 'Typography', 'fami-templatekits' ),
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .fmtpl-post-list-meta-wrap span',
            ],
            'meta_color' => [
                'label' => __('Color', 'fami-templatekits'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span' => 'color: {{VALUE}};',
                ],
            ],
            'meta_space' => [
                'label' => __('Space Between', 'fami-templatekits'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span.fmtpl-post-meta' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span.fmtpl-post-meta:last-child' => 'margin-right: 0;',
                ],
            ],
            'meta_box_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
            'meta_icon_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span svg' => 'fill: {{VALUE}};',
                ],
            ],
            'meta_icon_space' => [
                'label' => __('Space Between', 'fami-templatekits'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-meta-wrap span i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ],
            'category_typography' => [
                'name' => 'category_typography',
                'label' => __('Typography', 'fami-templatekits'),
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .fmtpl-post-list-category, {{WRAPPER}} .fmtpl-post-list-category a',
            ],
            'category_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-category, {{WRAPPER}} .fmtpl-post-list-category a' => 'color: {{VALUE}}; fill:  {{VALUE}};',
                ],
            ],
            'category_hvr_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-category:hover, {{WRAPPER}} .fmtpl-post-list-category a:hover' => 'color: {{VALUE}};',
                ],
            ],
            'category_box_margin' => [
                'label' => __( 'Margin', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-post-list-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ],
        ];
        $this->default_control = apply_filters('fmtpl-post-list-elementor-widget-control', $widget_control);
    }
	protected function _register_controls () {

        $this->define_widget_control();
		$this->start_controls_section(
			'_section_post_list',
			[
				'label' => __( 'List', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        if (isset($this->default_control['post_type'])){
            $this->add_control(
                'post_type',
                $this->default_control['post_type']
            );
        }

        if (isset($this->default_control['show_post_by'])) {
            $this->add_control(
                'show_post_by',
                $this->default_control['show_post_by']
            );
        }
        if (isset($this->default_control['posts_per_page'])) {
            $this->add_control(
                'posts_per_page',
                $this->default_control['posts_per_page']
            );
        }

        $repeater_post = new Repeater();

        if (isset($this->default_control['item_title'])) {
            $repeater_post->add_control(
                'post_item_title',
                $this->default_control['item_title']
            );
        }
        if (isset($this->default_control['post_id'])) {
            $repeater_post->add_control(
                'post_id',
                $this->default_control['post_id']
            );
        }

        $this->add_control(
            'selected_list_post',
            [
                'label' => '',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_post->get_controls(),
                'title_field' => '{{ post_item_title }}',
                'condition' => [
                    'show_post_by' => 'selected',
                    'post_type' => 'post'
                ],
            ]
        );

        $repeater_page = new Repeater();
        if (isset($this->default_control['item_title'])) {
            $repeater_page->add_control(
                'page_item_title',
                $this->default_control['item_title']
            );
        }
        if (isset($this->default_control['post_id'])) {
            $this->default_control['post_id']['data_options'] = [
                'post_type' => 'page',
                'action' => 'fmtpl_post_list_query'
            ];
            $this->default_control['post_id']['placeholder'] = __( 'Search Page', 'fami-templatekits' );
            $repeater_page->add_control(
                'page_id',
                $this->default_control['post_id']
            );
        }
        $this->add_control(
            'selected_list_page',
            [
                'label' => '',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_page->get_controls(),
                'title_field' => '{{ page_item_title }}',
                'condition' => [
                    'show_post_by' => 'selected',
                    'post_type' => 'page'
                ],
            ]
        );

		$this->end_controls_section();

		//Settings
		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        if (isset($this->default_control['view'])) {
            $this->add_control(
                'view',
                $this->default_control['view']
            );
        }
        if (isset($this->default_control['feature_image'])) {
            $this->add_control(
                'feature_image',
                $this->default_control['feature_image']
            );
        }
        if (isset($this->default_control['post_image'])) {
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                $this->default_control['post_image']
            );
        }
        if (isset($this->default_control['list_icon'])) {
            $this->add_control(
                'list_icon',
                $this->default_control['list_icon']
            );
        }
        if (isset($this->default_control['icon'])) {
            $this->add_control(
                'icon',
                $this->default_control['icon']
            );
        }
        if (isset($this->default_control['category_data'])) {
            $this->add_control(
                'category_data',
                $this->default_control['category_data']
            );
        }

        if (isset($this->default_control['category_icon'])) {
            $this->add_control(
                'category_icon',
                $this->default_control['category_icon']
            );
        }

        if (isset($this->default_control['excerpt'])) {
            $this->add_control(
                'excerpt',
                $this->default_control['excerpt']
            );
        }

        if (isset($this->default_control['meta'])) {
            $this->add_control(
                'meta',
                $this->default_control['meta']
            );
        }

        if (isset($this->default_control['author_meta'])) {
            $this->add_control(
                'author_meta',
                $this->default_control['author_meta']
            );
        }

        if (isset($this->default_control['author_icon'])) {
            $this->add_control(
                'author_icon',
                $this->default_control['author_icon']
            );
        }

        if (isset($this->default_control['date_meta'])) {
            $this->add_control(
                'date_meta',
                $this->default_control['date_meta']
            );
        }

        if (isset($this->default_control['date_icon'])) {
            $this->add_control(
                'date_icon',
                $this->default_control['date_icon']
            );
        }

        if (isset($this->default_control['meta_position'])) {
            $this->add_control(
                'meta_position',
                $this->default_control['meta_position']
            );
        }

        if (isset($this->default_control['title_tag'])) {
            $this->add_control(
                'title_tag',
                $this->default_control['title_tag']
            );
        }

        if (isset($this->default_control['item_align'])) {
            $this->add_responsive_control(
                'item_align',
                $this->default_control['item_align']
            );
        }
		$this->end_controls_section();

		$this->start_controls_section(
			'_section_post_list_style',
			[
				'label' => __( 'List', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_common',
			[
				'label' => __( 'Common', 'fami-templatekits' ),
				'type' => Controls_Manager::HEADING,
			]
		);
        if (isset($this->default_control['list_item_margin'])) {
            $this->add_responsive_control(
                'list_item_margin',
                $this->default_control['list_item_margin']
            );
        }

        if (isset($this->default_control['list_item_padding'])) {
            $this->add_responsive_control(
                'list_item_padding',
                $this->default_control['list_item_padding']
            );
        }

        if (isset($this->default_control['list_item_box_shadow'])) {
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                $this->default_control['list_item_box_shadow']
            );
        }

        if (isset($this->default_control['list_item_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['list_item_border']
            );
        }

        if (isset($this->default_control['list_item_border_radius'])) {
            $this->add_responsive_control(
                'list_item_border_radius',
                $this->default_control['list_item_border_radius']
            );
        }

        if (isset($this->default_control['advance_style'])) {
            $this->add_control(
                'advance_style',
                $this->default_control['advance_style']
            );
        }

        if (isset($this->default_control['list_item_first'])) {
            $this->add_responsive_control(
                'list_item_first',
                $this->default_control['list_item_first']
            );
        }

        if (isset($this->default_control['list_item_first_child_margin'])) {
            $this->add_responsive_control(
                'list_item_first_child_margin',
                $this->default_control['list_item_first_child_margin']
            );
        }

        if (isset($this->default_control['list_item_first_child_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['list_item_first_child_border']
            );
        }

        if (isset($this->default_control['list_item_last'])) {
            $this->add_responsive_control(
                'list_item_last',
                $this->default_control['list_item_last']
            );
        }

        if (isset($this->default_control['list_item_last_child_margin'])) {
            $this->add_responsive_control(
                'list_item_last_child_margin',
                $this->default_control['list_item_last_child_margin']
            );
        }

        if (isset($this->default_control['list_item_last_child_border'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['list_item_last_child_border']
            );
        }

		$this->end_controls_section();

		//Title Style
		$this->start_controls_section(
			'_section_post_list_title_style',
			[
				'label' => __( 'Post Title', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        if (isset($this->default_control['title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['title_typography']
            );
        }
        if (isset($this->default_control['title_color'],$this->default_control['title_hvr_color'])) {
            $this->add_control(
                'title_tabs_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->start_controls_tabs('title_tabs');
            $this->start_controls_tab(
                'title_normal_tab',
                [
                    'label' => __('Normal', 'fami-templatekits'),
                ]
            );


            $this->add_control(
                'title_color',
                $this->default_control['title_color']
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'title_hover_tab',
                [
                    'label' => __('Hover', 'fami-templatekits'),
                ]
            );

            $this->add_control(
                'title_hvr_color',
                $this->default_control['title_hvr_color']
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();
        }

		$this->end_controls_section();
		//List Icon Style
		$this->start_controls_section(
			'_section_list_icon_feature_iamge_style',
			[
				'label' => __( 'Icon & Feature Image', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'feature_image',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'list_icon',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				],
			]
		);
        if (isset($this->default_control['icon_color'])) {
            $this->add_control(
                'icon_color',
                $this->default_control['icon_color']
            );
        }
        if (isset($this->default_control['icon_size'])) {
            $this->add_responsive_control(
                'icon_size',
                $this->default_control['icon_size']
            );
        }

        if (isset($this->default_control['icon_line_height'])) {
            $this->add_responsive_control(
                'icon_line_height',
                $this->default_control['icon_line_height']
            );
        }

        if (isset($this->default_control['image_width'])) {
            $this->add_responsive_control(
                'image_width',
                $this->default_control['image_width']
            );
        }

        if (isset($this->default_control['image_boder'])) {
            $this->add_group_control(
                Group_Control_Border::get_type(),
                $this->default_control['image_boder']
            );
        }

        if (isset($this->default_control['image_boder_radius'])) {
            $this->add_responsive_control(
                'image_boder_radius',
                $this->default_control['image_boder_radius']
            );
        }

        if (isset($this->default_control['icon_margin_right'])) {
            $this->add_responsive_control(
                'icon_margin_right',
                $this->default_control['icon_margin_right']
            );
        }

		$this->end_controls_section();

        //List Category Style
        $this->start_controls_section(
            '_section_category_style',
            [
                'label' => __( 'Category', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'category_data' => 'yes',
                ]
            ]
        );

        if (isset($this->default_control['category_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['category_typography']
            );
        }
        if (isset($this->default_control['category_color'],$this->default_control['category_hvr_color'])) {
            $this->add_control(
                'category_tabs_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );
            $this->start_controls_tabs('category_tabs');
            $this->start_controls_tab(
                'category_normal_tab',
                [
                    'label' => __('Normal', 'fami-templatekits'),
                ]
            );


            $this->add_control(
                'category_color',
                $this->default_control['category_color']
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'category_hover_tab',
                [
                    'label' => __('Hover', 'fami-templatekits'),
                ]
            );

            $this->add_control(
                'category_hvr_color',
                $this->default_control['category_hvr_color']
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();
        }

        if (isset($this->default_control['category_box_margin'])) {
            $this->add_responsive_control(
                'category_box_margin',
                $this->default_control['category_box_margin']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_list_excerpt_style',
            [
                'label' => __( 'Excerpt', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'excerpt' => 'yes',
                ]
            ]
        );

        if (isset($this->default_control['excerpt_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['excerpt_typography']
            );
        }

        if (isset($this->default_control['excerpt_color'])) {
            $this->add_control(
                'excerpt_color',
                $this->default_control['excerpt_color']
            );
        }

        if (isset($this->default_control['excerpt_box_margin'])) {
            $this->add_responsive_control(
                'excerpt_box_margin',
                $this->default_control['excerpt_box_margin']
            );
        }

        $this->end_controls_section();

		//List Meta Style
		$this->start_controls_section(
			'_section_list_meta_style',
			[
				'label' => __( 'Meta', 'fami-templatekits' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta' => 'yes',
				]
			]
		);
        if (isset($this->default_control['meta_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['meta_typography']
            );
        }

        if (isset($this->default_control['meta_color'])) {
            $this->add_control(
                'meta_color',
                $this->default_control['meta_color']
            );
        }

        if (isset($this->default_control['meta_space'])) {
            $this->add_responsive_control(
                'meta_space',
                $this->default_control['meta_space']
            );
        }

        if (isset($this->default_control['meta_box_margin'])) {
            $this->add_responsive_control(
                'meta_box_margin',
                $this->default_control['meta_box_margin']
            );
        }

		$this->add_responsive_control(
			'meta_icon_heading',
			[
				'label' => __( 'Meta Icon', 'fami-templatekits' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        if (isset($this->default_control['meta_icon_color'])) {
            $this->add_control(
                'meta_icon_color',
                $this->default_control['meta_icon_color']
            );
        }

        if (isset($this->default_control['meta_icon_space'])) {
            $this->add_responsive_control(
                'meta_icon_space',
                $this->default_control['meta_icon_space']
            );
        }

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		if ( ! $settings['post_type'] ) return;
		$args = [
			'post_status' => 'publish',
			'post_type' => $settings['post_type'],
		];
		if ( 'recent' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		$customize_title = [];
		$ids = [];
		if ( 'selected' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = -1;
			$lists = $settings['selected_list_' . $settings['post_type']];
			if ( ! empty( $lists ) ) {
				foreach ( $lists as $index => $value ) {
					$ids[] = $value[$settings['post_type'].'_id'];
					if ( $value[$settings['post_type'].'_item_title'] ) $customize_title[$value[$settings['post_type'].'_id']] = $value[$settings['post_type'].'_item_title'];
				}
			}
			$args['post__in'] = (array) $ids;
			$args['orderby'] = 'post__in';
		}

		if ( 'selected' === $settings['show_post_by'] && empty( $ids ) ) {
			$posts = [];
		} else {
			$posts = get_posts( $args );
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'fmtpl-post-list-wrapper', 'fmtpl-elementor-widget' ] );
		$this->add_render_attribute( 'wrapper-inner', 'class', [ 'fmtpl-post-list' ] );
		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'wrapper-inner', 'class', [ 'fmtpl-post-list-inline' ] );
		}
		$this->add_render_attribute( 'item', 'class', [ 'fmtpl-post-list-item' ] );

		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?> >
					<?php foreach ( $posts as $post ): ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
                            <?php
                                $post_thumb_html = '';
                                if ( 'yes' === $settings['feature_image'] ):
                                    $post_thumb_html .= get_the_post_thumbnail( $post->ID, $settings['post_image_size'] );
                                elseif ( 'yes' === $settings['list_icon'] && $settings['icon'] ) :
                                    $post_thumb_html .=  '<span class="fmtpl-post-list-icon">';
                                    ob_start();
                                    Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                                    $post_thumb_html .= ob_get_clean();
                                    $post_thumb_html .=  '</span>';
                                endif;
                                if (!empty($post_thumb_html)):
                            ?>
                            <div class="fmtpl-post-list-thumb">
                                <a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
                                    <?php echo $post_thumb_html;?>
                                </a>
                            </div>
                                <?php endif;?>
                            <div class="fmtpl-post-list-content">
                                <?php if ( 'post' === $settings['post_type'] && 'yes' === $settings['category_data'] ):
                                    $categories = get_the_category( $post->ID );
                                    if (!empty($categories)):
                                    ?>
                                    <span class="fmtpl-post-list-category">
                                        <a href="<?php echo get_category_link($categories[0]);?>" title="<?php echo $categories[0]->name;?>">
                                        <?php if ( $settings['category_icon'] ):
                                            Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
                                        endif;
                                        echo esc_html( $categories[0]->name ); ?>
                                        </a>
                                    </span>
                                    <?php endif;
                                endif;
                                $title = $post->post_title;
                                if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID, $customize_title ) ) {
                                    $title = $customize_title[$post->ID];
                                }
                                if ( 'top' !== $settings['meta_position'] && $title ) {
                                    printf( '<%1$s %2$s><a href="%3$s">%4$s</a></%1$s>',
                                        tag_escape( $settings['title_tag'] ),
                                        'class="fmtpl-post-list-title"',
                                        esc_url( get_the_permalink( $post->ID ) ),
                                        esc_html( $title )
                                    );
                                    if ('yes' == $settings['excerpt']) {
                                        printf( '<div class="fmtpl_post_excerpt">%1$s</div>',
                                             $post->post_excerpt
                                        );
                                    }
                                }
                                ?>
                                <?php if ( 'yes' === $settings['meta'] ): ?>
                                    <div class="fmtpl-post-list-meta-wrap">

                                        <?php if ( 'yes' === $settings['author_meta'] ):
                                            $author_link = get_the_author_meta( 'url', $post->post_author  );
                                            ?>
                                            <a class="fmtpl-post-meta fmtpl-post-list-author" href="<?php echo esc_url($author_link);?>">
												<?php if ( $settings['author_icon'] ):
                                                    Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
                                                endif;
                                                echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ( 'yes' === $settings['date_meta'] ): ?>
                                            <span class="fmtpl-post-meta fmtpl-post-list-date">
													<?php if ( $settings['date_icon'] ):
                                                        Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
                                                    endif;
                                                    echo get_the_date( "M d, Y" ,$post);
                                                    ?>
												</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php
                                if ( 'top' === $settings['meta_position'] && $title ) {
                                    printf( '<%1$s %2$s><a href="%3$s">%4$s</a></%1$s>',
                                        tag_escape( $settings['title_tag'] ),
                                        'class="fmtpl-post-list-title"',
                                        esc_url( get_the_permalink( $post->ID ) ),
                                        esc_html( $title )
                                    );
                                    if ('yes' == $settings['excerpt']) {
                                        printf( '<div class="fmtpl_post_excerpt">%1$s</div>',
                                            esc_html($post->post_excerpt)
                                        );
                                    }
                                }
                                ?>
                            </div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		else:
			printf( '%1$s %2$s %3$s',
				__( 'No ', 'fami-templatekits' ),
				esc_html( $settings['post_type'] ),
				__( 'Found', 'fami-templatekits' )
			);
		endif;
	}
}
