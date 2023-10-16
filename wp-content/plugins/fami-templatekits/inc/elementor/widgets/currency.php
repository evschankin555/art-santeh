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

class Fmtpl_Currency extends Widget_Base {
    private $default_control;
    public function get_name() {
        return 'fmtpl-currency';
    }
    public function get_title() {
        return __( 'Currency Switcher', 'fami-templatekits' );
    }
    public function get_icon() {
        return 'fmtpl-currency fmtpl-widget-icon';
    }
    public function get_categories() {
        return [ 'fami-elements' ];
    }
    public function get_keywords() {
        return ['fami','fm', 'currency', 'switcher' ];
    }

    private function define_widget_control() {
        $widget_control = [
            'content_type' => [
                'label' => __('Content type', 'fami-templatekits'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'fami-templatekits'),
                    'symbol' => __('Symbol', 'fami-templatekits'),
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
                        '{{WRAPPER}} .currency-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_typography' =>
                [
                    'name' => 'title_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .currency-title',
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .currency-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .fmtpl-currency .currency-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-currency .currency-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
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
                    '{{WRAPPER}} .fmtpl-currency .selected, {{WRAPPER}} .fmtpl-currency a' => 'color: {{VALUE}}',
                ],
            ],
            'content_typography' => [
                'name' => 'content_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .fmtpl-currency .selected, {{WRAPPER}} .fmtpl-currency a',
            ],
        ];
        $this->default_control = apply_filters('fmtpl-currency-elementor-widget-control',$widget_control);
    }
    protected function _register_controls()
    {
        $this->define_widget_control();
        $this->start_controls_section('_section_currency', ['label' => __('Content', 'fami-templatekits')]);
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

    }
    protected function render() {
        $settings = $this->get_settings_for_display();

        global $WOOCS;
        if ( empty( $WOOCS ) ) {
            return;
        }
        $fmtpl_currencys = $WOOCS->get_currencies();
        if ( empty( $fmtpl_currencys ) ) {
            return;
        }
        $current_currency = $WOOCS->current_currency;
        ?>
        <div class="fmtpl-elementor-widget fmtpl-currency">
            <?php
            $currency_list = array();
            $current   = '';
            foreach ( (array) $fmtpl_currencys as $code => $currency ) {
                $currency_name = '';
                if (isset($settings['content_type']) && $settings['content_type'] == 'symbol'){
                    $currency_name = $currency['symbol'];
                }else{
                    $currency_name .= $currency['name'];
                }
                if ( $code ==  $current_currency) {
                    $current = $currency;
                    array_unshift( $currency_list, sprintf(
                        '<li class="%s"><a href="%s"  data-value="%s">%s</a></li>',
                        'current currency_item',
                        '#.',
                        esc_attr( $code ),
                        esc_html( $currency_name )
                    ));
                } else {
                    $currency_list[] = sprintf(
                        '<li class="%s"><a href="%s" data-value="%s">%s</a></li>',
                        'currency_item',
                        '#.',
                        esc_attr( $code ),
                        esc_html( $currency_name )
                    );
                }
            }
            ?>

            <div class="currency fmtpl-dropdown">
                <?php
                if (!empty($settings['title'])) :
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
                ?>
                <div class="currency-title widget-title"><?php echo $title;?></div>
                <?php endif;?>
                <div class="dropdown">
		     <span class="current">
		     	<span class="selected">
                        <?php
                            if (isset($settings['content_type']) && $settings['content_type'] == 'code'){
                                echo esc_html($current['symbol']);
                            } else {
                                echo esc_html($current['name']);
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
                        <?php echo implode( "\n\t", $currency_list ); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}
