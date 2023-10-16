<?php


if (!class_exists('Topbar_Fields')) {
    class Topbar_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'show_top_bar' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Topbar', 'cenos'),
                    'description' => esc_html__( 'Display a bar on the top', 'cenos' ),
                    'section' => 'topbar',
                    'default' => false,
                ],

                'top_bar_height'     => [
                    'type'    => 'slider',
                    'label'   => esc_html__( 'Height', 'cenos' ),
                    'default' => '40',
                    'section' => 'topbar',
                    'choices'         => [
                        'min' => 30,
                        'max' => 150,
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'show_top_bar',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'top_bar_bg'                        => [
                    'type'        => 'background',
                    'label'       => esc_html__( 'Topbar background', 'cenos' ),
                    'section'     => 'topbar',
                    'default'     => [
                        'background-color'      => '',
                        'background-image'      => '',
                        'background-repeat'     => 'repeat',
                        'background-position'   => 'center center',
                        'background-size'       => 'cover',
                        'background-attachment' => 'scroll',
                    ],
                    'active_callback' => [
                       [
                            'setting'  => 'show_top_bar',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],

                'top_bar_color' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Text Color', 'cenos'),
                    'section' => 'topbar',
                    'default' => 'dark',
                    'choices' => [
                        'dark' => esc_html__('Dark', 'cenos'),
                        'light' => esc_html__('Light', 'cenos'),
                    ],
                ],
                'top_bar_divider' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Topbar Divider', 'cenos'),
                    'section' => 'topbar',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting'  => 'show_top_bar',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],

                ],
                'top_bar_divider_color' => [
                    'type' => 'color',
                    'label' => esc_html__('Divider Color', 'cenos'),
                    'section' => 'topbar',
                    'default' => '#e8e8e8',
                    'active_callback' => [
                        [
                            'setting'  => 'show_top_bar',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'top_bar_divider',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'top_bar_layout' => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Topbar layout', 'cenos'),
                    'section' => 'topbar',
                    'default' => 'layout1',
                    'choices' => [
                        'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/topbar/layout1.jpg',
                        'layout2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/topbar/layout2.jpg'
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'show_top_bar',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'topbar_group_hd' => [
                    'type'        => 'custom',
                    'section'     => 'topbar',
                    'default'     => '<div class="group-heading-control">' .esc_html__( 'Topbar Elements', 'cenos' ) . '</div>',
                    'active_callback' => [
                        [
                            'setting' => 'show_top_bar',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'topbar_left' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Topbar left Elements', 'cenos' ),
                    'section'     => 'topbar',
                    'items_section' => [
                        'search' => 'head_search',
                        'cart' => 'head_cart',
                        'account' => 'head_account',
                        'wishlist' => 'head_wishlist',
                        'html_topbar_left' => 'head_html',
                        'contact' => 'head_contact_info',
                        'hamburger' => 'head_hm',
                        'social_icon' => 'head_social'
                    ],
                    'default'     => [
                        'contact',
                    ],
                    'choices'     => [
                        'account' => esc_html__( 'My Account', 'cenos' ),
                        'contact' => esc_html__( 'Contact Info', 'cenos' ),
                        'social_icon' => esc_html__( 'Social Icon', 'cenos' ),
                        'hamburger' => esc_html__( 'Hamburger Menu', 'cenos' ),
                        'language'  => esc_html__( 'Language', 'cenos' ),
                        'currency'  => esc_html__( 'Currency', 'cenos' ),
                        'html_topbar_left' => esc_html__( 'Html', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'show_top_bar',
                            'operator' => '==',
                            'value' => true,
                        ],
                        [
                          'setting' => 'top_bar_layout',
                          'operator' => 'in',
                          'value' => ['layout1','layout2'],
                        ],
                    ],
                    'partial_refresh'    => [
                        'topbar-left-control' => [
                            'selector'        => '.top-bar-left-control',
                            'render_callback' => function() {
                                do_action('cenos_header_control','topbar_left');
                            },
                        ]
                    ],
                ],
                'topbar_center' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Topbar Center Elements', 'cenos' ),
                    'section'     => 'topbar',
                    'items_section' => [
                        'search' => 'head_search',
                        'cart' => 'head_cart',
                        'account' => 'head_account',
                        'wishlist' => 'head_wishlist',
                        'html_topbar_center' => 'head_html',
                        'contact' => 'head_contact_info',
                        'hamburger' => 'head_hm',
                        'social_icon' => 'head_social'
                    ],
                    'default'     => [
                        'contact',
                        'social_icon',
                    ],
                    'choices'     => [
                        'account' => esc_html__( 'My Account', 'cenos' ),
                        'contact' => esc_html__( 'Contact Info', 'cenos' ),
                        'social_icon' => esc_html__( 'Social Icon', 'cenos' ),
                        'hamburger' => esc_html__( 'Hamburger Menu', 'cenos' ),
                        'language'  => esc_html__( 'Language', 'cenos' ),
                        'currency'  => esc_html__( 'Currency', 'cenos' ),
                        'html_topbar_center' => esc_html__( 'Html', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'show_top_bar',
                            'operator' => '==',
                            'value' => true,
                        ],
                        [
                            'setting' => 'top_bar_layout',
                            'operator' => '==',
                            'value' => 'layout2',
                        ],
                    ],
                    'partial_refresh'    => [
                        'topbar-center-control' => [
                            'selector'        => '.top-bar-center-control',
                            'render_callback' => function() {
                                do_action('cenos_header_control','topbar_center');
                            },
                        ]
                    ],
                ],
                'topbar_right' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Topbar Right Elements', 'cenos' ),
                    'section'     => 'topbar',
                    'items_section' => [
                        'search' => 'head_search',
                        'cart' => 'head_cart',
                        'account' => 'head_account',
                        'wishlist' => 'head_wishlist',
                        'html_topbar_right' => 'head_html',
                        'contact' => 'head_contact_info',
                        'hamburger' => 'head_hm',
                        'social_icon' => 'head_social'
                    ],
                    'default'     => [
                        'social_icon',
                    ],
                    'choices'     => [
                        'account' => esc_html__( 'My Account', 'cenos' ),
                        'contact' => esc_html__( 'Contact Info', 'cenos' ),
                        'social_icon' => esc_html__( 'Social Icon', 'cenos' ),
                        'hamburger' => esc_html__( 'Hamburger Menu', 'cenos' ),
                        'language'  => esc_html__( 'Language', 'cenos' ),
                        'currency'  => esc_html__( 'Currency', 'cenos' ),
                        'html_topbar_right' => esc_html__( 'Html', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'show_top_bar',
                            'operator' => '==',
                            'value' => true,
                        ],
                        [
                            'setting' => 'top_bar_layout',
                            'operator' => 'in',
                            'value' => ['layout1','layout2'],
                        ],
                    ],
                    'partial_refresh'    => [
                        'topbar-right-control' => [
                            'selector'        => '.top-bar-right-control',
                            'render_callback' => function() {
                                do_action('cenos_header_control','topbar_right');
                            },
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
return cenos_customize_field_setting('Topbar_Fields');
