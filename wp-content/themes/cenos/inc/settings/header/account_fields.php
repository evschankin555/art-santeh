<?php
if (!class_exists('Account_Fields')) {
    class Account_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'account_style' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Login Form Style', 'cenos'),
                    'section' => 'head_account',
                    'default' => 'modal',
                    'choices' => [
                        'modal' => esc_html__( 'Modal', 'cenos' ),
                        'canvas' => esc_html__( 'Off canvas', 'cenos' ),
                        'link' => esc_html__( 'Link only', 'cenos' ),
                    ]
                ],
                'show_account_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_account',
                    'default' => true,
                ],
                'show_account_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Title', 'cenos'),
                    'section' => 'head_account',
                    'default' => false,
                ],
                'account_title' => [
                    'type' => 'text',
                    'label' => esc_html__('My Account Title', 'cenos'),
                    'description' => esc_html__( 'Display this title for non logged in users.', 'cenos' ),
                    'section' => 'head_account',
                    'default' => 'Login',
                    'active_callback' => [
                        [
                            'setting' => 'show_account_title',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'show_user_name' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Username', 'cenos'),
                    'description' => esc_html__( 'Display username after logged in.', 'cenos' ),
                    'section' => 'head_account',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting' => 'show_account_title',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'account_margin' => [
                    'type' => 'dimensions',
                    'label' => esc_html__('My Account Align', 'cenos'),
                    'description' => esc_html__('The distance between the My Account and other elements on the Header of page.','cenos'),
                    'section' => 'head_account',
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
return cenos_customize_field_setting('Account_Fields');
