<?php
if (!class_exists('Mobile_Fields')) {
    class Mobile_Fields
    {
        protected $setting;

        public function __construct()
        {
            $social_fields = cenos_customize_field_setting('Social_Fields');
            $social_fields['si_element']['section'] = 'head_mobile';
            $social_fields['si_element']['active_callback'] = [
                [
                    'setting'  => 'mobile_hd_bottom',
                    'operator' => 'contains',
                    'value'    => 'mhd_social',
                ]
            ];
            $new_social_field = $social_fields['si_element'];

            $this->setting = [
                'group_hd_mobile_heading_1' => [
                    'type'        => 'custom',
                    'section'     => 'head_mobile',
                    'default'     => '<div class="group-heading-control top">' .esc_html__( 'Layout Settings', 'cenos' ) . '</div>',
                ],
                'header_mobile_layout' => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Header  Layout', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => 'layout1',
                    'choices' => [
                        'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/mobile/layout1.png',
                        'layout2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/mobile/layout2.png',
                        'layout3' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/mobile/layout3.png',
                        'layout4' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/mobile/layout4.png',
                    ],
                ],
                'show_sticky_mobile' => [
                    'type' => 'toggle',
                    'label' =>esc_html__('Mobile Header Sticky', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => false,
                ],
                'mobile_right_control' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Right Control Elements', 'cenos' ),
                    'section'     => 'head_mobile',
                    'default'     => ['search', 'cart'],
                    'choices'     => [
                        'search' => esc_html__( 'Search', 'cenos' ),
                        'cart' => esc_html__( 'Cart', 'cenos' ),
                        'account' => esc_html__( 'My Account', 'cenos' ),
                        'wishlist' => esc_html__( 'Wishlist', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'header_mobile_layout',
                            'operator' => '!=',
                            'value' => 'layout4',
                        ],
                    ],
                ],
                'mobile_own_logo' => [
                    'type' => 'toggle',
                    'label' =>esc_html__('Enable Mobile Logo', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => false,
                ],
                'mobile_logo' => [
                    'type' => 'image',
                    'label' => esc_html__('Logo', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => '',
                    'active_callback' => [
                        [
                            'setting' => 'mobile_own_logo',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'show_mobile_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => true,
                ],
                'hd_mobile_icon' => [
                    'type' => 'radio-image',
                    'label' =>esc_html__('Icon', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => 'ellipsis',
                    'choices' => [
                        'hamburger' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/hamburger.svg',
                        'ellipsis' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/ellipsis.svg',
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'show_mobile_icon',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'show_mobile_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Title', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => false,
                ],
                'mobile_title' => [
                    'type' => 'text',
                    'label' => esc_html__('Title', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => 'Menu',
                    'active_callback' => [
                        [
                            'setting' => 'show_mobile_title',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'group_hd_mobile_heading_2' => [
                    'type'        => 'custom',
                    'section'     => 'head_mobile',
                    'default'     => '<div class="group-heading-control">' .esc_html__( 'Menu Contents', 'cenos' ) . '</div>',
                ],
                'mobile_hd_canvas_style' => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Menu Canvas Style', 'cenos'),
                    'section' => 'head_mobile',
                    'default' => 'full-fade',
                    'choices' => [
                        'left' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout1.jpg',
                        'right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout2.jpg',
                        'full-fade' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout3.jpg',
                    ],
                ],
                'mobile_hd_bottom' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Bottom Elements', 'cenos' ),
                    'section'     => 'head_mobile',
                    'default'     => [],
                    'choices'     => [
                        'mhd_language' => esc_html__( 'Language', 'cenos' ),
                        'mhd_currency' => esc_html__( 'Currency', 'cenos' ),
                        'mhd_social' => esc_html__( 'Social', 'cenos' ),
                        'mhd_html' => esc_html__( 'HTML', 'cenos' ),
                    ],
                ],
                'mobile_hd_html' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML On Mobile Header', 'cenos' ),
                    'section'  => 'head_mobile',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '',
                    'active_callback' => [
                        [
                            'setting'  => 'mobile_hd_bottom',
                            'operator' => 'contains',
                            'value'    => 'mhd_html',
                        ]
                    ],
                ],
            ];
            $this->setting['mobile_hd_social_element'] = $new_social_field;
            $other_mobile_setting = [
                'group_hd_mobile_heading_3' => [
                    'type'        => 'custom',
                    'section'     => 'head_mobile',
                    'default'     => '<div class="group-heading-control">' .esc_html__( 'Mobile Breakpoint', 'cenos' ) . '</div>',
                ],
                'mobile_breakpoint' => [
                    'type' => 'radio-buttonset',
                    'section' => 'head_mobile',
                    'default' => 'tablet',
                    'choices' => [
                        'tablet' => esc_html__('Tablet (<1025px)', 'cenos'),
                        'mobile' => esc_html__('Mobile (<768px)', 'cenos'),
                    ],
                ],

            ];
            $this->setting = array_merge($this->setting,$other_mobile_setting);
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Mobile_Fields');
