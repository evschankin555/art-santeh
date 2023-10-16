<?php
if (!class_exists('Logo_Fields')){
    class Logo_Fields{
        protected $setting;
        public function __construct()
        {
            $this->setting = [
                'logo_slogan' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Display Tagline below Logo', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => false,
                ],
                'logo_text' => [
                    'type' => 'textarea',
                    'label' => esc_html__('Logo text', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting' => 'logo',
                            'operator' => '==',
                            'value' => '',
                        ],
                    ],
                ],
                'logo_font' => [
                    'type' => 'typography',
                    'label' => esc_html__('Logo Font', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => [
                        'font-family' => 'Roboto',
                        'variant' => '700',
                        'font-size' => '30px',
                        'letter-spacing' => '0',
                        'subsets' => ['latin-ext'],
                        'text-transform' => 'uppercase',
                    ],
                    'output' => [
                        [
                            'element' => '.site-branding .logo.text',
                        ],
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'logo',
                            'operator' => '==',
                            'value' => '',
                        ],
                    ],
                ],
                'logo' => [
                    'type' => 'image',
                    'label' => esc_html__('Logo', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/logo.svg',
                    'partial_refresh'    => [
                        'site-branding' => [
                            'selector'        => '.site-branding',
                            'render_callback' => function() {
                                get_template_part( 'template-parts/headers/parts/logo' );
                            },
                        ]
                    ],
                ],
                'logo_box_width' => [
                    'type' => 'slider',
                    'label' => esc_html__('Logo box Width', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => '150',
                    'choices' => [
                        'min' => 50,
                        'max' => 350,
                        'step' => 1,
                        'suffix' => 'px',
                    ],
                ],
                'logo_position' => [
                    'type' => 'spacing',
                    'label' => esc_html__('Logo box Padding', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => [
                        'top' => '0',
                        'bottom' => '0',
                        'left' => '0',
                        'right' => '0',
                    ],
                ],
                'logo_width' => [
                    'type' => 'slider',
                    'label' => esc_html__('Logo Width', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => '100',
                    'choices' => [
                        'min' => 50,
                        'max' => 350,
                        'step' => 1,
                        'suffix' => 'px',
                    ],
                ],
                'logo_align' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Enable Logo Alignment', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => false,
                ],
                'logo_h_align' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Logo - Horizontal Align', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => 'left',
                    'choices' => [
                        'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                        'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                        'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'logo_align',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'logo_v_align' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Logo - Vertical Align', 'cenos'),
                    'section' => 'title_tagline',
                    'default' => 'middle',
                    'choices' => [
                        'top' => '<span class="im-icon-top"></span>',
                        'middle' => '<span class="im-icon-middle"></span>',
                        'bottom' => '<span class="im-icon-bottom"></span>',
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'logo_align',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
            ];
        }

        public function getSetting(){
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Logo_Fields');
