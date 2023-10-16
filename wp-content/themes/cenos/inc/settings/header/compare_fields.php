<?php
if (!class_exists('Compare_Fields')) {
    class Compare_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'show_compare_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show icon', 'cenos'),
                    'section' => 'head_compare',
                    'default' => true,
                ],
                'show_compare_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show title', 'cenos'),
                    'section' => 'head_compare',
                    'default' => false,
                ],
                'compare_title' => [
                    'type' => 'text',
                    'label' => esc_html__('My account title', 'cenos'),
                    'description' => esc_html__( 'Display this title for non logged in users.', 'cenos' ),
                    'section' => 'head_compare',
                    'default' => 'Compare',
                    'active_callback' => [
                        [
                            'setting' => 'show_account_title',
                            'operator' => '==',
                            'value' => 1,
                        ],
                    ],
                ],
                'compare_margin' => [
                    'type' => 'dimensions',
                    'label' => esc_html__('Wishlist box margin', 'cenos'),
                    'section' => 'head_compare',
                    'default' => [
                        'margin-top' => '',
                        'margin-right' => '',
                        'margin-bottom' => '',
                        'margin-left' => '',
                    ],
                    'choices'     => [
                        'accept_unitless' => false,
                        'labels' => [
                            'margin-top'  => esc_html__( 'Margin Top', 'cenos' ),
                            'margin-right'  => esc_html__( 'Margin Right', 'cenos' ),
                            'margin-bottom'  => esc_html__( 'Margin Bottom', 'cenos' ),
                            'margin-left'  => esc_html__( 'Margin Left', 'cenos' ),
                        ],
                    ]
                ],
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Compare_Fields');
