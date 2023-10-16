<?php
//head_hm
if (!class_exists('Hamburger_Fields')) {
    class Hamburger_Fields
    {
        protected $setting;

        public function __construct()
        {

            $social_fields = cenos_customize_field_setting('Social_Fields');
            $social_fields['si_element']['section'] = 'head_hm';
            $social_fields['si_element']['active_callback'] = [
                [
                    'setting'  => 'hm_bottom_element',
                    'operator' => 'contains',
                    'value'    => 'hm_social',
                ]
            ];
            $new_social_field = $social_fields['si_element'];
            $this->setting = [
                'hm_canvas_style' => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Hamburger Canvas Style', 'cenos'),
                    'section' => 'head_hm',
                    'default' => 'full-fade',
                    'choices' => [
                        'left' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout1.jpg',
                        'right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout2.jpg',
                        'full-fade' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout3.jpg',
                    ],
                ],
                'hm_logo' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Logo', 'cenos'),
                    'description' => 'Display the site logo on top of Hamburger menu',
                    'section' => 'head_hm',
                    'default' => true,
                ],
                'hm_content_type' => [
                    'type' => 'radio-buttonset',
                    'label' =>esc_html__('Content Type', 'cenos'),
                    'section' => 'head_hm',
                    'default' => 'menu',
                    'choices' => [
                        'menu' =>esc_html__( 'Menu', 'cenos' ),
                        'widget' =>esc_html__( 'Widget', 'cenos' ),
                    ]
                ],
                'hm_bottom_element' => [
                    'type'        => 'sortable',
                    'label'       =>esc_html__( 'Bottom Elements', 'cenos' ),
                    'section'     => 'head_hm',
                    'default'     => [],
                    'choices'     => [
                        'hm_language' =>esc_html__( 'Language', 'cenos' ),
                        'hm_currency' =>esc_html__( 'Currency', 'cenos' ),
                        'hm_social' =>esc_html__( 'Social', 'cenos' ),
                        'hm_html' =>esc_html__( 'HTML', 'cenos' ),
                    ],
                ],
                'hm_html' => [
                    'type'     => 'editor',
                    'label'    =>esc_html__( 'HTML On Hamburger Menu', 'cenos' ),
                    'section'  => 'head_hm',
                    'description' =>esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '',
                    'active_callback' => [
                        [
                            'setting'  => 'hm_bottom_element',
                            'operator' => 'contains',
                            'value'    => 'hm_html',
                        ]
                    ],
                ],
            ];
            $this->setting['hm_social_element'] = $new_social_field;
            $other_setting = [
                'hm_bg' => [
                    'type'        => 'background',
                    'label'       =>esc_html__( 'Background', 'cenos' ),
                    'section'     => 'head_hm',
                    'default'     => [
                        'background-color'      => '',
                        'background-image'      => '',
                        'background-repeat'     => 'no-repeat',
                        'background-position'   => 'center center',
                        'background-size'       => 'cover',
                        'background-attachment' => 'scroll',
                    ]
                ],
                'hm_color' => [
                    'type' => 'color',
                    'label' =>esc_html__('Text Color', 'cenos'),
                    'section' => 'head_hm',
                    'default' => '',
                ],
                'show_hm_icon' => [
                    'type' => 'toggle',
                    'label' =>esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_hm',
                    'default' => true,
                ],
                'hm_icon' => [
                    'type' => 'radio-image',
                    'label' =>esc_html__('Icon', 'cenos'),
                    'section' => 'head_hm',
                    'default' => 'hamburger',
                    'choices' => [
                        'hamburger' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/hamburger.svg',
                        'ellipsis' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/ellipsis.svg',
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'show_hm_icon',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'show_hm_title' => [
                    'type' => 'toggle',
                    'label' =>esc_html__('Show Title', 'cenos'),
                    'section' => 'head_hm',
                    'default' => false,
                ],
                'hm_title' => [
                    'type' => 'text',
                    'label' =>esc_html__('Title', 'cenos'),
                    'section' => 'head_hm',
                    'default' => 'Menu',
                    'active_callback' => [
                        [
                            'setting' => 'show_hm_title',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
            ];
            $this->setting = array_merge($this->setting,$other_setting);
        }

        public function getSetting()
        {

            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Hamburger_Fields');
