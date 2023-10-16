<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if (!class_exists('Product_Pins')){
    class Product_Pins extends Widget_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-product-pin';
        }

        public function get_title() {
            return esc_html__( 'Product Pins', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'eicon-map-pin fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'image', 'banner', 'product','pin'];
        }
        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $script_depends = apply_filters('fmtpl-products-pin-script-depends',[]);
            if (!empty($script_depends)){
                foreach ($script_depends as $script){
                    $this->add_script_depends($script);
                }
            }
            $this->add_style_depends('fmtpl-product-pin');
        }
        private function define_widget_control()
        {
            $widget_control = [
                'layout' => [
                    'label' => esc_html__('Layout', 'fami-templatekits'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__('Default', 'fami-templatekits'),
                    ],
                    'style_transfer' => true,
                ],
                'height' => [
                    'label' => esc_html__( 'Height', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1000,
                            'step' => 5,
                        ],
                    ],
                    'default' => [],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'product_id' => [
                    'label' => esc_html__('Products', 'fami-templatekits'),
                    'label_block' => true,
                    'type' => Fmtpl_Select2::TYPE,
                    'multiple' => false,
                    'placeholder' => esc_html__('Search Products', 'fami-templatekits'),
                    'data_options' => [
                        'post_type' => 'product',
                        'action' => 'fmtpl_post_list_query'
                    ],
                ],
                'vertical_pos' => [
                    'label' => esc_html__( 'Vertical Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [  '%', 'px' ],
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
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'horizontal_pos' => [
                    'label' => esc_html__( 'Horizontal Position', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [  '%', 'px' ],
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
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ],

                'adv_pin_style' => [
                    'label' => __( 'Advance Pin Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'On', 'fami-templatekits' ),
                    'label_off' => __( 'Off', 'fami-templatekits' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ],
                'pin_size' => [
                    'label' => __( 'Pin Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 6,
                            'max' => 300,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-link' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'pin_text' => [
                    'label' => __( 'Text', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type button text here', 'fami-templatekits' ),
                    'condition' => [
                        'adv_pin_style!' => 'yes',
                    ],
                ],
                'pin_icon' => [
                    'label' => __( 'Icon', 'fami-templatekits' ),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'condition' => [
                        'adv_pin_style!' => 'yes',
                    ],
                ],
                'pin_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-link' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-link svg' => 'fill:  {{VALUE}};',
                    ],
                    'condition' => [
                        'adv_pin_style!' => 'yes',
                    ],
                ],
                'pin_bg_color' => [
                    'label' => __( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-link' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'adv_pin_style!' => 'yes',
                    ],
                ],
                'adv_pin_content' => [
                    'label' => __( 'Pin Content', 'fami-templatekits' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => '',
                    'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
                    'separator' => 'before',
                    'condition' => [
                        'adv_pin_style' => 'yes',
                    ],
                ],
                'box_horizontal_pos' => [
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
                        'left' => 'left: auto; right: 100%',
                        'center' => 'left: auto; right: 100%; transform: translateX(50%);',
                        'right' => 'left: 100%; right: auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-box' => '{{VALUE}}',
                    ],
                ],
                'box_vertical_pos' => [
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
                        'top' => 'top: auto; bottom: 100%;',
                        'middle' => 'top: auto; bottom: 100%;transform: translateY(50%);',
                        'bottom' => 'top: 100%; bottom: auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-box' => '{{VALUE}}',
                    ],

                ],
                'box_offset' => [
                    'label' => __( 'Box Offset', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item{{CURRENT_ITEM}} .pin-product-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'overlay' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl_product_pin-overlay' => 'background-color: {{VALUE}};',
                    ],
                ],
                'overlay_hover' => [
                    'label' => esc_html__( 'Background Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin:hover .fmtpl_product_pin-overlay' => 'background-color: {{VALUE}};',
                    ],
                ],
                'image' => [
                    'label' => esc_html__( 'Choose Image', 'fami-templatekits' ),
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
                'box_shadow' => [
                    'name' => 'box_shadow',
                    'label' => __( 'Box Shadow', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl_product_pin .pin-product-box',
                ],
                'pin_typography' => [
                    'name' => 'pin_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} .fmtpl_product_pin .pin-product-link .pin_text',
                ],
                'pin_icon_size' => [
                    'label' => __( 'Pin Icon Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 6,
                            'max' => 300,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item .pin_content_wrap .pin-product-link svg, {{WRAPPER}} .fmtpl_product_pin .fmtpl-product-pin-item .pin_content_wrap .pin-product-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ],
            ];
            $this->default_control = apply_filters('fmtpl_product_pin-elementor-widget-control', $widget_control);
        }

        protected function _register_controls()
        {
            $this->define_widget_control();
            $this->start_controls_section('_section_layout', ['label' => esc_html__('Layout', 'fami-templatekits')]);
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
                    'label' => esc_html__('Banner', 'fami-templatekits'),
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
                '_section_product',
                [
                    'label' => esc_html__('Products', 'fami-templatekits'),
                ]
            );
            $repeater = new Repeater();
            if (isset($this->default_control['product_id'])) {
                $repeater->add_control(
                    'product_id',
                    $this->default_control['product_id']
                );
            }
            if (isset($this->default_control['horizontal_pos'])) {
                $repeater->add_responsive_control(
                    'horizontal_pos',
                    $this->default_control['horizontal_pos']
                );
            }
            if (isset($this->default_control['vertical_pos'])) {
                $repeater->add_responsive_control(
                    'vertical_pos',
                    $this->default_control['vertical_pos']
                );
            }
            if (isset($this->default_control['adv_pin_style'])) {
                $repeater->add_control(
                    'adv_pin_style',
                    $this->default_control['adv_pin_style']
                );
            }
            if (isset($this->default_control['pin_size'])) {
                $repeater->add_responsive_control(
                    'pin_size',
                    $this->default_control['pin_size']
                );
            }
            if (isset($this->default_control['pin_text'])) {
                $repeater->add_control(
                    'pin_text',
                    $this->default_control['pin_text']
                );
            }
            if (isset($this->default_control['pin_icon'])) {
                $repeater->add_control(
                    'pin_icon',
                    $this->default_control['pin_icon']
                );
            }
            if (isset($this->default_control['pin_color'])) {
                $repeater->add_control(
                    'pin_color',
                    $this->default_control['pin_color']
                );
            }
            if (isset($this->default_control['pin_bg_color'])) {
                $repeater->add_control(
                    'pin_bg_color',
                    $this->default_control['pin_bg_color']
                );
            }
            if (isset($this->default_control['adv_pin_content'])) {
                $repeater->add_control(
                    'adv_pin_content',
                    $this->default_control['adv_pin_content']
                );
            }
            if (isset($this->default_control['box_horizontal_pos'])) {
                $repeater->add_responsive_control(
                    'box_horizontal_pos',
                    $this->default_control['box_horizontal_pos']
                );
            }
            if (isset($this->default_control['box_vertical_pos'])) {
                $repeater->add_responsive_control(
                    'box_vertical_pos',
                    $this->default_control['box_vertical_pos']
                );
            }
            if (isset($this->default_control['box_offset'])) {
                $repeater->add_responsive_control(
                    'box_offset',
                    $this->default_control['box_offset']
                );
            }
            //
            $this->add_control(
                'selected_product',
                [
                    'label' => '',
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                ]
            );
            $this->end_controls_section();

            $this->start_controls_section(
                '_section_layout_style',
                [
                    'label' => esc_html__( 'Layout', 'fami-templatekits' ),
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
                        'label' => esc_html__('Overlay', 'fami-templatekits'),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );
                $this->start_controls_tabs( 'layout_tabs' );
                $this->start_controls_tab(
                    'tab_layout_normal',
                    [
                        'label' => esc_html__( 'Normal', 'fami-templatekits' ),
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
                        'label' => esc_html__( 'Hover', 'fami-templatekits' ),
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
                '_section_pin_style',
                [
                    'label' => esc_html__( 'Pin', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            if (isset($this->default_control['pin_typography'])) {
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['pin_typography']
                );
            }
            if (isset($this->default_control['pin_icon_size'])) {
                $this->add_responsive_control(
                    'pin_icon_size',
                    $this->default_control['pin_icon_size']
                );
            }
            $this->end_controls_section();
            $this->start_controls_section(
                '_section_box_style',
                [
                    'label' => esc_html__( 'Product Box', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            if (isset($this->default_control['box_shadow'])) {
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    $this->default_control['box_shadow']
                );
            }
            $this->end_controls_section();
        }
        protected function render()
        {
            echo ($this->fmtpl_render());
        }
        protected function fmtpl_render() {
            $settings = $this->get_settings_for_display();
            $style_str = false;
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
            $html = apply_filters('fmtpl_product_pin-elementor-widget-control_html','',$settings);
            if (!empty($html)){
                return $html;
            }
            $html = '<div class="fmtpl-elementor-widget fmtpl_product_pin '.$settings['layout'].'">';
            if ( ! empty( $style_str ) ) {
                $html .= '<div class="fmtpl_product_pin-background" '.$style_str.'></div>';
            }
            if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
                $html .= '<div class="fmtpl_product_pin-overlay"></div>';
            }
            if ($settings['image_html'] != ''){
                $html .= '<figure class="fmtpl_product_pin-box-img">' . $settings['image_html'] . '</figure>';
            }
            if (isset($settings['selected_product']) && !empty($settings['selected_product'])){
                foreach ($settings['selected_product'] as $product_item){
                    $product = false;
                    if (isset($product_item['product_id']) && $product_item['product_id']){
                        $product = wc_get_product($product_item['product_id']);
                    }
                    if (!$product){
                        continue;
                    }

                    $data_pos_str ='';
                    if (isset($product_item['horizontal_pos']) && $product_item['horizontal_pos']['size']){
                        $data_pos_str = ' data-horizontal="'.$product_item['horizontal_pos']['size'].$product_item['horizontal_pos']['unit'].'"';
                    }
                    if (isset($product_item['horizontal_pos_tablet']) && $product_item['horizontal_pos_tablet']['size']){
                        $data_pos_str .= ' data-horizontal_tablet="'.$product_item['horizontal_pos_tablet']['size'].$product_item['horizontal_pos_tablet']['unit'].'"';
                    }
                    if (isset($product_item['horizontal_pos_mobile']) && $product_item['horizontal_pos_mobile']['size']){
                        $data_pos_str .= ' data-horizontal_mobile="'.$product_item['horizontal_pos_mobile']['size'].$product_item['horizontal_pos_mobile']['unit'].'"';
                    }
                    if (isset($product_item['vertical_pos']) && $product_item['vertical_pos']['size']){
                        $data_pos_str .= ' data-vertical="'.$product_item['vertical_pos']['size'].$product_item['vertical_pos']['unit'].'"';
                    }
                    if (isset($product_item['vertical_pos_tablet']) && $product_item['vertical_pos_tablet']['size']){
                        $data_pos_str .= ' data-vertical_tablet="'.$product_item['vertical_pos_tablet']['size'].$product_item['vertical_pos_tablet']['unit'].'"';
                    }
                    if (isset($product_item['vertical_pos_mobile']) && $product_item['vertical_pos_mobile']['size']){
                        $data_pos_str .= ' data-vertical_mobile="'.$product_item['vertical_pos_mobile']['size'].$product_item['vertical_pos_mobile']['unit'].'"';
                    }

                    $product_box_html = '<div class="pin-product-box">';
                    $product_box_html .= '<div class="pin-product-thumb-wraper">';
                    $product_box_html .= wc_get_gallery_image_html($product->get_image_id(),true);
                    $product_box_html .= '</div>';
                    $product_box_html .= sprintf('<h3 class="product-title">%s</h3><div class="product-price">%s</div>',$product->get_name(),$product->get_price_html());
                    $product_box_html .= '</div>';
                    $pin_content = '';
                    if (isset($product_item['adv_pin_style']) && $product_item['adv_pin_style'] == 'yes'){
                        $pin_content = $product_item['adv_pin_content'];
                    } else {
                        if (isset($product_item['pin_icon']) && !empty($product_item['pin_icon'])){
                            $pin_content = '<span class="pin_text">'.$product_item['pin_text'].'</span>';
                        }
                        if (isset($product_item['pin_icon']) && !empty($product_item['pin_icon'])){
                            ob_start();
                            Icons_Manager::render_icon( $product_item[ 'pin_icon' ]);
                            $icon_str = ob_get_clean();
                            if (!empty($icon_str)){
                                $pin_content .= $icon_str;
                            }
                        }
                    }
                    $html .= sprintf('<div class="fmtpl-product-pin-item elementor-repeater-item-%s"%s><div class="pin_content_wrap"><a class="pin-product-link" href="%s" title="%s">%s</a>%s</div></div>',
                        $product_item['_id'],
                        $data_pos_str,
                        $product->get_permalink(),
                        $product->get_name(),
                        $pin_content,
                        $product_box_html
                    );
                }
            }
            $html .='</div>';
            return $html;
        }
    }
}
