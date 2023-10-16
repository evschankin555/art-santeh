<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
require_once FAMI_TPL_DIR . '/inc/elementor/widgets/carousel/carousel_base.php';

if (!class_exists('Fmtpl_Carousel_Testimonial')){
    class Fmtpl_Carousel_Testimonial extends Fmtpl_Carousel_Base {
        protected $default_control;
        public function get_name() {
            return 'fmtpl-carousel-testimonial';
        }

        public function get_title() {
            return __( 'Carousel Testimonial', 'fami-templatekits' );
        }

        public function get_icon() {
            return 'fmtpl-carousel-testimonial fmtpl-widget-icon';
        }

        public function get_keywords() {
            return ['fami','fm', 'testimonial','client', 'carousel' ];
        }
        public function define_widget_control() {
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
                'image_size' => [
                    'name' => 'image_size',
                    'default' => 'thumbnail',
                ],
                'pagination_style' => [
                    'label' => __( 'Style', 'fami-templatekits' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default'  => __( 'Default', 'fami-templatekits' )
                    ],
                    'default' => 'default',
                    'prefix_class' => 'fmtpl-dot-style-',
                    'condition' => [
                        'pagination' => 'bullets',
                    ],
                ],
                'item_content' => [
                    'label' => __( 'Content', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXTAREA,
                ],
                'item_image' => [
                    'label'             => __('Image','fami-templatekits'),
                    'type'              => Controls_Manager::MEDIA,
                    'dynamic'       => [ 'active' => true ],
                    'default'      => [
                        'url'   => Utils::get_placeholder_image_src()
                    ],
                    'description'       => __( 'Choose an image for the author', 'fami-templatekits' ),
                    'show_label'        => true,
                ],
                'item_name' => [
                    'label'             => __('Name', 'fami-templatekits'),
                    'type'              => Controls_Manager::TEXT,
                    'default'           => 'John Doe',
                    'dynamic'           => [ 'active' => true ],
                    'label_block'       => true
                ],
                'item_title' => [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'CEO', 'fami-templatekits' ),
                ],
                'item_image_width' => [
                    'label' => __( 'Size', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    ],
                ],
                'item_image_gap' => [
                    'label' => __( 'Gap', 'fami-templatekits' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}}',

                    ]
                ],
                'item_image_border' => [
                    'name' => 'item_image_border',
                    'label' => __( 'Border', 'fami-templatekits' ),
                    'selector' => '{{WRAPPER}} .fmtpl-testimonial-image img',
                ],
                'item_image_border_radius' => [
                    'label' => __( 'Border Radius', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],

                'item_content_typography' => [
                    'name' => 'item_content_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-testimonial-content',
                ],
                'item_content_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial-content' => 'color: {{VALUE}}',
                    ],
                ],
                'item_content_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_name_typography' => [
                    'name' => 'item_name_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-testimonial__name',
                ],
                'item_name_color' => [
                    'label' => __( 'Color', 'fmtpl-testimonial__name' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial__name' => 'color: {{VALUE}}',
                    ],
                ],
                'item_name_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
                'item_title_typography' => [
                    'name' => 'item_title_typography',
                    'label' => __( 'Typography', 'fami-templatekits' ),
                    'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                    'selector' => '{{WRAPPER}} .fmtpl-testimonial__title',
                ],
                'item_title_color' => [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial__title' => 'color: {{VALUE}}',
                    ],
                ],
                'item_title_margin' => [
                    'label' => __( 'Margin', 'fami-templatekits' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .fmtpl-testimonial__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ],
            ];
            $this->default_control = apply_filters('fmtpl-carousel-testimonial-elementor-widget-control', $widget_control);

        }
        protected function _register_controls() {
            parent::_register_controls();
            $this->define_widget_control();
            $this->start_injection( [
                'at' => 'before',
                'of' => 'section_heading',
            ] );
            $this->start_controls_section(
                'section_layout',
                [
                    'label' => __( 'Layout', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            if (isset($this->default_control['layout'])) {
                $this->add_control(
                    'layout',
                    $this->default_control['layout']
                );
            }
            if (isset($this->default_control['layout'])) {
                $this->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    $this->default_control['image_size']
                );
            }
            $this->end_controls_section();
            $this->end_injection();

            /*****************************/
            $repeater = new Repeater();
            $this->add_repeater_controls($repeater);



            if (isset($this->default_control['pagination_style'])) {
                $this->start_injection( [
                    'at' => 'after',
                    'of' => 'pagination',
                ] );
                $this->add_control(
                    'pagination_style',
                    $this->default_control['pagination_style']
                );
                $this->end_injection();
            }

            $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __( 'Slide Content', 'fami-templatekits' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_control(
                'item_heading_1',
                [
                    'label' => __( 'Image', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_image_width'])){
                $this->add_responsive_control(
                    'item_image_width',
                    $this->default_control['item_image_width']
                );
            }
            if (isset($this->default_control['item_image_gap'])){
                $this->add_responsive_control(
                    'item_image_gap',
                    $this->default_control['item_image_gap']
                );
            }
            if (isset($this->default_control['item_image_border'])){
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    $this->default_control['item_image_border']
                );
            }
            if (isset($this->default_control['item_image_border_radius'])){
                $this->add_responsive_control(
                    'item_image_border_radius',
                    $this->default_control['item_image_border_radius']
                );
            }

            $this->add_control(
                'item_heading_2',
                [
                    'label' => __( 'Name', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_name_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_name_typography']
                );
            }
            if (isset($this->default_control['item_name_color'])){
                $this->add_control(
                    'item_name_color',
                    $this->default_control['item_name_color']
                );
            }
            if (isset($this->default_control['item_name_margin'])){
                $this->add_responsive_control(
                    'item_name_margin',
                    $this->default_control['item_name_margin']
                );
            }
            $this->add_control(
                'item_heading_3',
                [
                    'label' => __( 'Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_title_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_title_typography']
                );
            }
            if (isset($this->default_control['item_title_color'])){
                $this->add_control(
                    'item_title_color',
                    $this->default_control['item_title_color']
                );
            }
            if (isset($this->default_control['item_title_margin'])){
                $this->add_responsive_control(
                    'item_title_margin',
                    $this->default_control['item_title_margin']
                );
            }
            $this->add_control(
                'item_heading_4',
                [
                    'label' => __( 'Content Box', 'fami-templatekits' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            if (isset($this->default_control['item_content_typography'])){
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    $this->default_control['item_content_typography']
                );
            }
            if (isset($this->default_control['item_content_color'])){
                $this->add_control(
                    'item_content_color',
                    $this->default_control['item_content_color']
                );
            }
            if (isset($this->default_control['item_content_margin'])){
                $this->add_responsive_control(
                    'item_content_margin',
                    $this->default_control['item_content_margin']
                );
            }
            $this->end_controls_section();
            if (isset($this->default_control['slides_per_view'])){
                $this->update_control('slides_per_view',
                    $this->default_control['slides_per_view']
                );
            }
            if (isset($this->default_control['slides_to_scroll'])){
                $this->update_control('slides_to_scroll',
                    $this->default_control['slides_to_scroll']
                );
            }
        }
        protected function render()
        {
            echo $this->fmtpl_render();
        }

        protected function fmtpl_render() {
            $settings = $this->get_settings_for_display();
            $html = '';
            $slides_count = isset($settings['slides'])? count( $settings['slides'] ) : 0;
            if ( $settings['prev_icon'] ) {
                ob_start();
                Icons_Manager::render_icon( $settings['prev_icon'], [ 'aria-hidden' => 'true' ] );
                $settings['prev_icon_str'] = ob_get_clean();
            }
            if ( $settings['next_icon'] ) {
                ob_start();
                Icons_Manager::render_icon( $settings['next_icon'], [ 'aria-hidden' => 'true' ] );
                $settings['next_icon_str'] = ob_get_clean();
            }
            $html = apply_filters('fmtpl-carousel-testimonial-widget-html',$html,$settings);
            if (!empty($html)){
                return $html;
            }
            $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
            $html = '<div class="fmtpl-elementor-widget fmtpl-carousel-testimonial fmtpl-testimonial-layout-'.$layout.' carousel">';
            $content_html = '';
            if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
            }

            if (isset($settings['description']) && !empty($settings['description'])){
                $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
            }

            if ($content_html != '') {
                $html .= '<div class="fmtpl-carousel-box-heading">'.$content_html.'</div>';
            }

            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper swiper-container">';
            $html .= '<div class="swiper-wrapper">';
            $slide_html = apply_filters('fmtpl-carousel-testimonial-item-html','',$settings);
            if (empty($slide_html)) {
                $element_key = 'fmtpl-testimonial-item';
                ob_start();
                $this->print_slide($settings['slides'],$settings,$element_key);
                $slide_html = ob_get_clean();
            }

            $html .= $slide_html;

            $html .= '</div>';

            if ( 1 < $slides_count ) {
                $pagi_class = 'swiper-pagination';
                if (empty($settings['pagination'] )) {
                    $pagi_class .= ' disabled';
                }
                $html .= '<div class="'.$pagi_class.'"></div>';
                if ( $settings['show_arrows'] == 'yes') {
                    $sw_btn_class ='';
                    if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                        $sw_btn_class .= ' hidden_on_mobile';
                    }
                    $html .= '<div class="elementor-swiper-button elementor-swiper-button-prev'.$sw_btn_class.'">';
                    if (isset($settings['prev_icon_str'])){
                        $html .= $settings['prev_icon_str'];
                    } else {
                        $html .= '<i class="eicon-chevron-left" aria-hidden="true"></i>';
                    }
                    $html .='<span>'.__( 'Previous', 'fami-templatekits' ).'</span></div>';
                    $html .= '<div class="elementor-swiper-button elementor-swiper-button-next'.$sw_btn_class.'"><span>'.__( 'Next', 'fami-templatekits' ).'</span>';
                    if (isset($settings['next_icon_str'])){
                        $html .= $settings['next_icon_str'];
                    } else {
                        $html .= '<i class="eicon-chevron-right" aria-hidden="true"></i>';
                    }
                    $html .='</div>';
                }
            }
            $html .='</div></div></div>';
            return $html;
        }

        protected function add_repeater_controls(Repeater $repeater)
        {
            $this->define_widget_control();
            if (isset($this->default_control['item_content'])) {
                $repeater->add_control(
                    'item_content',
                    $this->default_control['item_content']
                );
            }
            if (isset($this->default_control['item_image'])) {
                $repeater->add_control(
                    'item_image',
                    $this->default_control['item_image']
                );
            }
            if (isset($this->default_control['item_name'])) {
                $repeater->add_control(
                    'item_name',
                    $this->default_control['item_name']
                );
            }
            if (isset($this->default_control['item_title'])) {
                $repeater->add_control(
                    'item_title',
                    $this->default_control['item_title']
                );
            }
        }

        protected function get_repeater_defaults() {
            $placeholder_image_src = Utils::get_placeholder_image_src();

            return [
                [
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
                [
                    'item_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fami-templatekits' ),
                    'item_name' => __( 'John Doe', 'fami-templatekits' ),
                    'item_title' => __( 'CEO', 'fami-templatekits' ),
                    'item_image' => [
                        'url' => $placeholder_image_src,
                    ],
                ],
            ];
        }

        protected function get_slide_image_url( $slide, array $settings ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['item_image']['id'], 'image_size', $settings );
            if ( ! $image_url ) {
                $image_url = $slide['item_image']['url'];
            }
            return $image_url;
        }

        protected function print_slide(array $items, array $settings, $element_key)
        {
            $i = 0;
            foreach ( $settings['slides'] as $index => $slide ) :
                $i++;
                $element_key = 'slide-' . $index . '-' . $i;
                if ( ! empty( $slide['item_image']['url'] ) ) {
                    $this->add_render_attribute( $element_key . '-image', [
                        'src' => $this->get_slide_image_url( $slide, $settings ),
                        'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
                    ] );
                }
                ?>
                <div class="swiper-slide">
                    <div class="fmtpl-testimonial-item">
                        <div class="fmtpl-testimonial-content">
                            <?php echo $slide['item_content'];?>
                        </div>
                        <div class="fmtpl-testimonial-author">
                            <?php
                             if ( $slide['item_image']['url'] ) : ?>
                                <div class="fmtpl-testimonial-image">
                                    <img <?php echo $this->get_render_attribute_string( $element_key . '-image' ); ?>>
                                </div>
                            <?php endif;?>
                            <div class="fmtpl-testimonial-detail">
                                <?php if ( ! empty( $slide['item_name'] ) ) :?>
                                    <div class="fmtpl-testimonial__name"><?php echo $slide['item_name'];?></div>
                                <?php endif;
                                if ( ! empty( $slide['item_title'] ) ) :?>
                                    <div class="fmtpl-testimonial__title"><?php echo $slide['item_title'];?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        }
    }
}

