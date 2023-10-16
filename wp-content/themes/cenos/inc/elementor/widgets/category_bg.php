<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;
use Elementor\Repeater;

class Cenos_Category_Background extends Widget_Base
{
    private $default_control;

    public function get_name()
    {
        return 'cenos-category-background';
    }

    public function get_title()
    {
        return esc_html__('Cenos Product Category Background', 'cenos');
    }

    public function get_icon()
    {
        return 'eicon-background fmtpl-widget-icon';
    }

    public function get_categories()
    {
        return ['fami-elements'];
    }

    public function get_keywords() {
        return ['fami','fm', 'image', 'photo', 'background', 'category', 'woo', 'cenos' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('cenos_category_bg');
    }

    private function define_widget_control()
    {
        $widget_control = [
            'layout' => [
                'label' =>esc_html__('Layout', 'cenos'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' =>esc_html__('Default', 'cenos'),
                ],
                'style_transfer' => true,
            ],
            'overlay' => [
                'label' =>esc_html__( 'Background Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .cenos-category-background-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'overlay_hover' => [
                'label' =>esc_html__( 'Background Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background:hover .cenos-category-background-overlay' => 'background-color: {{VALUE}};',
                ],
            ],
            'box_padding' => [
                'label' =>esc_html__( 'Padding', 'cenos' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .category_content_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ],

            'image' => [
                'label' =>esc_html__( 'Choose Image', 'cenos' ),
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
                'label' =>esc_html__( 'Title', 'cenos' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' =>esc_html__( 'This is the %highlight% title', 'cenos' ),
                'placeholder' =>esc_html__( 'This is the %highlight% title', 'cenos' ),
                'label_block' => true,
                'condition' => ['custom_title' => 'yes'],
                'separator' => 'before',
            ],
            'highlight_title' =>
            [
                'label' =>esc_html__( 'Highlight Title', 'cenos' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' =>esc_html__( 'Highlight', 'cenos' ),
                'placeholder' =>esc_html__( 'Enter your Highlight title', 'cenos' ),
                'label_block' => true,
                'condition' => [
                    'custom_title' => 'yes'
                ],
            ],

            'border_color' => [
                'label' =>esc_html__( 'Border Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item' => 'border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'title_stroke_text' => [
                'label' =>esc_html__( 'Stroke Text', 'cenos' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Enable', 'cenos' ),
                'label_off' =>esc_html__( 'Disable', 'cenos' ),
                'default' => 'no',
            ],
            'title_stroke_color' => [
                'label' =>esc_html__( 'Stroke Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item .cat-link.stroke' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text' => 'yes'
                ]
            ],
            'title_color' => [
                'label' =>esc_html__( 'Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item .cat-link' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text!' => 'yes'
                ]
            ],
            'title_color_hover' => [
                'label' =>esc_html__( 'Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item.selected .cat-link' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text!' => 'yes'
                ]
            ],
            'title_typography' => [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cenos-category-background .categories-item .cat-name',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'highlight_title_color' => [
                'label' =>esc_html__( 'Highlight Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item .cat-name .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text!' => 'yes'
                ]
            ],
            'highlight_title_color_hover' => [
                'label' =>esc_html__( 'Highlight Color', 'cenos' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cenos-category-background .categories-item.selected .cat-name .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
                'condition' => [
                    'title_stroke_text!' => 'yes'
                ]
            ],
            'highlight_title_typography' => [
                'label' =>esc_html__( 'Highlight Typography', 'cenos' ),
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .cenos-category-background .categories-item .cat-name .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],

            'category_id' => [
                'label' =>esc_html__('Product Categories', 'cenos'),
                'label_block' => true,
                'type' => Fmtpl_Select2::TYPE,
                'multiple' => false,
                'placeholder' =>esc_html__('Search Category', 'cenos'),
                'data_options' => [
                    'taxonomy_type' => 'product_cat',
                    'action' => 'fmtpl_taxonomy_list_query'
                ],
            ],

            'custom_title' => [
                'label' =>esc_html__( 'Custom Title', 'cenos' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Show', 'cenos' ),
                'label_off' =>esc_html__( 'Hide', 'cenos' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],
        ];
        $this->default_control = apply_filters('cenos-category-background-elementor-widget-control', $widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_layout', ['label' =>esc_html__('Layout', 'cenos')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
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
            '_section_cats',
            [
                'label' =>esc_html__('Categories', 'cenos'),
            ]
        );
        $repeater = new Repeater();
        if (isset($this->default_control['category_id'])) {
            $repeater->add_control(
                'category_id',
                $this->default_control['category_id']
            );
        }
        if (isset($this->default_control['image'])) {
            $repeater->add_control(
                'image',
                $this->default_control['image']
            );
        }
        if (isset($this->default_control['custom_title'])) {
            $repeater->add_control(
                'custom_title',
                $this->default_control['custom_title']
            );
        }

        if (isset($this->default_control['title_text'])) {
            $repeater->add_control(
                'title_text',
                $this->default_control['title_text']
            );
        }
        if (isset($this->default_control['highlight_title'])) {
            $repeater->add_control(
                'highlight_title',
                $this->default_control['highlight_title']
            );
        }
        $this->add_control(
            'category_ids',
            [
                'label' =>esc_html__( 'Category List', 'cenos' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_layout_style',
            [
                'label' =>esc_html__( 'Layout', 'cenos' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['border_color'])) {
            $this->add_control(
                'border_color',
                $this->default_control['border_color']
            );
        }
        if (isset($this->default_control['box_padding'])) {
            $this->add_control(
                'box_padding',
                $this->default_control['box_padding']
            );
        }
        //overlay
        if (isset($this->default_control['overlay']) && isset($this->default_control['overlay_hover'])) {
            $this->add_control(
                'overlay_heading',
                [
                    'label' =>esc_html__('Overlay', 'cenos'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->start_controls_tabs( 'layout_tabs' );
            $this->start_controls_tab(
                'tab_layout_normal',
                [
                    'label' =>esc_html__( 'Normal', 'cenos' ),
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
                    'label' =>esc_html__( 'Hover', 'cenos' ),
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
            '_section_title_style',
            [
                'label' =>esc_html__( 'Title', 'cenos' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['title_stroke_text'])) {
            $this->add_control(
                'title_stroke_text',
                $this->default_control['title_stroke_text']
            );
        }
        if (isset($this->default_control['title_stroke_color'])) {
            $this->add_control(
                'title_stroke_color',
                $this->default_control['title_stroke_color']
            );
        }

        $this->start_controls_tabs( 'title_tabs',[
            'condition' => [
                'title_stroke_text!' => 'yes'
            ]
        ] );
        $this->start_controls_tab(
            'title_tab_normal',
            [
                'label' =>esc_html__( 'Normal', 'cenos' ),

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
                'label' =>esc_html__( 'Hover', 'cenos' ),
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
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $html = '<div class="fmtpl-elementor-widget cenos-category-background '.$settings['layout'].'">';
        $category_items = $settings['category_ids'];
        $category_items_html = '';
        $category_items_bg = '';
        $first_item = true;
        if (!empty($category_items)) {
            foreach ($category_items as $cat_item){
                if (empty($cat_item['category_id'])){
                    continue;
                }
                $cat_info = get_term( $cat_item['category_id'], 'product_cat' );
                if (empty($cat_info)){
                    continue;
                }
                $style_str = '';
                if ( ! empty( $cat_item['image']['url'] ) ) {
                    $image_src = Group_Control_Image_Size::get_attachment_image_src($cat_item['image']['id'], 'thumbnail', $settings);
                    if (empty($image_src)) {
                        $image_src = $cat_item['image']['url'];
                    }
                    $style_str .= ' style="background-image: url(' . $image_src . ');"';
                }
                $cat_link = get_term_link( $cat_info->term_id, 'product_cat' );
                if (isset($cat_item['custom_title'],$cat_item['title_text']) && $cat_item['custom_title'] == 'yes' && !empty($cat_item['title_text'])){
                    $highlight_title = isset($cat_item['highlight_title'])? $cat_item['highlight_title']:'';
                    $item_title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$cat_item['title_text']);
                } else {
                    $item_title = $cat_info->name;
                }
                $title_class = 'cat-link';
                if (isset($settings['title_stroke_text']) && $settings['title_stroke_text'] == 'yes'){
                    $title_class .= ' stroke';
                }
                $category_items_html .= sprintf('<div class="categories-item%s" data-id="cat-item-%s"><h3 class="cat-name"><a class="%s" href="%s">%s</a></h3></div>',
                    $first_item ? ' selected':'',
                    $cat_item['_id'],
                    $title_class,
                    $cat_link,
                    $item_title
                );
                $category_items_bg .= sprintf('<div id="cat-item-%s" class="cat-bg%s"%s>&nbsp;</div>',
                    $cat_item['_id'],
                    $first_item ? ' selected':'',
                    $style_str
                );
                $first_item = false;
            }
        }

        if (!empty($category_items_html)):
        $html .= '<div class="category_content_wrap">'.$category_items_html.'</div>';
        endif;
        $html .= $category_items_bg;
        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="cenos-category-background-overlay">&nbsp;</div>';
        }
        $html .='</div>';
        cenos_esc_data($html);
    }
}
