<?php


if (!class_exists('Transparent_Fields')) {
    class Transparent_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'show_transparent' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Transparent Header', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => false,
                ],
                'transparent_page' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Apply On Page', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => 'home',
                    'choices' => [
                        'all' => esc_html__('All page', 'cenos'),
                        'home' =>esc_html__('Home Page', 'cenos'),
                        'custom' => esc_html__('Custom Page', 'cenos'),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'custom_transparent_page' => [
                    'type'            => 'multicheck',
                    'label'           => esc_html__( 'Transparent Header Display', 'cenos' ),
                    'description'     => esc_html__( 'Select pages you want to display Transparent Header', 'cenos' ),
                    'section'     => 'head_transparent',
                    'default'         => ['home'],
                    'choices'         => [
                        'home' => esc_html__( 'Home', 'cenos' ),
                        'shop' => esc_html__( 'Shop / Product Category', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'transparent_page',
                            'operator' => '==',
                            'value'    => 'custom',
                        ]
                    ],
                ],
                'transparent_bg' => [
                    'type' => 'toggle',
                    'label' =>esc_html__('Transparent Background', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'transparent_bg_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Background Color', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'transparent_bg',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'transparent_logo' => [
                    'type' => 'image',
                    'label' => esc_html__('Transparent Logo', 'cenos'),
                    'description' => esc_html__( 'Logo on Transparent Header', 'cenos' ),
                    'section' => 'head_transparent',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
	                        'setting'  => 'transparent_bg',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'transparent_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Text Color', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'transparent_bg',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'transparent_color_hover' => [
                    'type' => 'color',
                    'label' => esc_html__('Text Hover Color', 'cenos'),
                    'section' => 'head_transparent',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_transparent',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'transparent_bg',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Transparent_Fields');
