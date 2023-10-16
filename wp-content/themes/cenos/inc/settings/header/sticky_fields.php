<?php
if (!class_exists('Sticky_Fields')) {
    class Sticky_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'show_sticky' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Sticky', 'cenos'),
                    'section' => 'head_sticky',
                    'default' => false,
                ],

                'sticky_logo' => [
                    'type' => 'image',
                    'label' => esc_html__('Sticky Logo', 'cenos'),
                    'description' => esc_html__( 'Description', 'cenos' ),
                    'section' => 'head_sticky',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'sticky_advance' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Sticky Advance Style', 'cenos'),
                    'section' => 'head_sticky',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'sticky_height'     => array(
                    'type'    => 'slider',
                    'label'   => esc_html__( 'Height', 'cenos' ),
                    'default' => '80',
                    'section' => 'head_sticky',
                    'choices'         => array(
                        'min' => 50,
                        'max' => 400,
                    ),
                    'active_callback' => array(
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'sticky_advance',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ),
                ),
                'sticky_bg_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Background Color', 'cenos'),
                    'section' => 'head_sticky',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'sticky_advance',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'sticky_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Text Color', 'cenos'),
                    'section' => 'head_sticky',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'sticky_advance',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'sticky_hover_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Text Hover Color', 'cenos'),
                    'section' => 'head_sticky',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting'  => 'show_sticky',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'sticky_advance',
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
return cenos_customize_field_setting('Sticky_Fields');
