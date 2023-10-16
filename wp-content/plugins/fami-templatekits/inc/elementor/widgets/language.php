<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;

class Fmtpl_Language extends Widget_Base {
    private $default_control;
    public function get_name() {
        return 'fmtpl-language';
    }
    public function get_title() {
        return __( 'Language Switcher', 'fami-templatekits' );
    }
    public function get_icon() {
        return 'eicon-typography fmtpl-widget-icon';
    }
    public function get_categories() {
        return [ 'fami-elements' ];
    }
    public function get_keywords() {
        return ['fami','fm', 'language', 'switcher' ];
    }

    private function define_widget_control() {
        $widget_control = [
            'content_type' => [
                'label' => __('Content type', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'code' => __('Code', 'fami-templatekits'),
                ],
            ],

            'show_flag' => [
                'label' => __( 'Display Flag', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'content_type' => 'default',
                ],
            ],
            'native_name' => [
                'label' => __( 'Native language name', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'content_type' => 'default',
                ],
            ],
            'translated_name' => [
                'label' => __( 'Language name in current language', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'content_type' => 'default',
                ],
            ],
            'title' => [
                'label' => __( 'Title', 'fami-templatekits' ),
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
            'title_color' =>
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .language-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_typography' =>
                [
                    'name' => 'title_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .language-title',
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .language-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-language .language-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-language .language-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'flag_space' => [
                'label' => __( 'Spacing', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .flag' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_flag' => 'yes',
                ],
            ],
            'drop_icon' => [
                'label' => __( 'Dropdown Icon', 'fami-templatekits' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ],
            'content_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-language .selected, {{WRAPPER}} .fmtpl-language a' => 'color: {{VALUE}}',
                ],
            ],
            'content_typography' => [
                'name' => 'content_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-language .selected, {{WRAPPER}} .fmtpl-language a',
            ],
        ];
        $this->default_control = apply_filters('fmtpl-language-elementor-widget-control',$widget_control);
    }
    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_language', ['label' => __('Content', 'fami-templatekits')]);
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

            if (isset($this->default_control['content_type'])) {
                $this->add_control(
                    'content_type',
                    $this->default_control['content_type']
                );
            }
            if (isset($this->default_control['show_flag'])) {
                $this->add_control(
                    'show_flag',
                    $this->default_control['show_flag']
                );
            }
            if (isset($this->default_control['native_name'])) {
                $this->add_control(
                    'native_name',
                    $this->default_control['native_name']
                );
            }
            if (isset($this->default_control['translated_name'])) {
                $this->add_control(
                    'translated_name',
                    $this->default_control['translated_name']
                );
            }
            if (isset($this->default_control['drop_icon'])) {
                $this->add_control(
                    'drop_icon',
                    $this->default_control['drop_icon']
                );
            }
        $this->end_controls_section();
        $this->start_controls_section(
            '_content_style',
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => '',
                ],
            ]
        );
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
        if (isset($this->default_control['title_padding'])) {
            $this->add_responsive_control(
                'title_padding',
                $this->default_control['title_padding']
            );
        }
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
        $this->end_controls_section();
        $this->start_controls_section(
            '_name_style',
            [
                'label' => __( 'Content', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        if (isset($this->default_control['content_color'])) {
            $this->add_control(
                'content_color',
                $this->default_control['content_color']
            );
        }
        if (isset($this->default_control['content_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['content_typography']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_flag_style',
            [
                'label' => __( 'Language Flag', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_flag' => 'yes',
                ],
            ]
        );
        if (isset($this->default_control['flag_space'])){
            $this->add_responsive_control(
                'flag_space',
                $this->default_control['flag_space']
            );
        }
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        global $fmtpl_languages;
        if ( empty( $fmtpl_languages ) ) {
            return;
        }
        ?>
        <div class="fmtpl-elementor-widget fmtpl-language">
            <?php
            $lang_list = array();
            $current   = '';
            foreach ( (array) $fmtpl_languages as $code => $language ) {
                $flag_html = '';
                $language_name = '';
                $pre_open = '';
                $pre_close = '';
                if (isset($settings['content_type']) && $settings['content_type'] == 'code'){
                    $language_name = $language['language_code'];
                }else{
                    if (isset($settings['show_flag']) && $settings['show_flag'] == 'yes'){
                        $flag_html = sprintf('<span class="lang_flag"><img src="%s" alt="%s"/></span>',
                            $language['country_flag_url'],
                            $language['native_name']
                        );
                    }
                    if (isset($settings['native_name']) && $settings['native_name']){
                        $language_name .= $language['native_name'];
                        $pre_open = ' (';
                        $pre_close = ')';
                    }
                    if (isset($settings['translated_name']) && $settings['translated_name']){
                        $language_name .= $pre_open.$language['translated_name'].$pre_close;
                    }
                }
                if ( ! $language['active'] ) {
                    $lang_list[] = sprintf(
                        '<li class="%s"><a href="%s">%s%s</a></li>',
                        esc_attr( $code ),
                        esc_url( $language['url'] ),
                        $flag_html,
                        esc_html( $language_name )
                    );
                } else {
                    $current = $language;
                    array_unshift( $lang_list, sprintf(
                        '<li class="%s"><a href="%s">%s%s</a></li>',
                        esc_attr( $code ),
                        '#.',
                        $flag_html,
                        esc_html( $language_name )
                    ) );
                }
            }
            ?>

            <div class="language fmtpl-dropdown">
                <?php
                if (!empty($settings['title'])) :
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
                ?>
                <div class="language-title widget-title"><?php echo $title;?></div>
                <?php endif;?>
                <div class="dropdown">
				<span class="current">
					<span class="selected">
                        <?php
                        if (isset($settings['show_flag']) && $settings['show_flag'] == 'yes') {
                            printf('<span class="lang_flag"><img src="%s" alt="%s"/></span>',
                                $current['country_flag_url'],
                                (isset($settings['content_type']) && $settings['content_type'] == 'code')? '':$current['native_name']
                            );
                        }
                        ?>
                        <?php
                            if (isset($settings['content_type']) && $settings['content_type'] == 'code'){
                                echo esc_html($current['language_code']);
                            } elseif ((isset($settings['native_name']) && $settings['native_name']) || (isset($settings['translated_name']) && $settings['translated_name'])) {
                            echo esc_html($current['native_name']);
                            }
                            ?>
                    </span>
                    <?php if (isset($settings[ 'drop_icon' ]) && $settings[ 'drop_icon' ]['value']):?>
                    <span class="fmtpl-dropdow-icon">
                        <?php Icons_Manager::render_icon( $settings[ 'drop_icon' ]);?>
                    </span>
                    <?php endif;?>
				</span>
                    <ul>
                        <?php echo implode( "\n\t", $lang_list ); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}
