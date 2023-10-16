<?php
class Announcement extends Settings
{

    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {
        $style_sections = [
            'announcement_section' => array(
                'priority' => 300,
                'title' => esc_html__('Announcement Bar ', 'cenos'),
            ),
        ];
        return array_merge($sections, $style_sections);
    }

    public function getFields($fields)
    {
        $ann_fields = [
            'show_announcement'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Show Announcement Bar', 'cenos' ),
                'section' => 'announcement_section',
                'default' => false,
            ],
            'show_announcement_mobile' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show On Mobile', 'cenos'),
                    'section' => 'announcement_section',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting'  => 'show_announcement',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
            'announcement_position'                    => [
                'type'        => 'radio-buttonset',
                'label'       => esc_html__( 'Display Position', 'cenos' ),
                'section'     => 'announcement_section',
                'default'     => 'top',
                'choices'     => [
                    'top'   => esc_html__( 'Top of the Page', 'cenos' ),
                    'bottom' => esc_html__( 'Bottom of the Page', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_layout' => [
                'type' => 'radio-image',
                'label' => esc_html__('Announcement Bar Layout', 'cenos'),
                'section' => 'announcement_section',
                'default' => 'layout1',
                'choices' => [
                    'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/promo_layout.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_close_btn'                    => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Show Close Button', 'cenos' ),
                'section' => 'announcement_section',
                'default' => true,
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_dismiss'                    => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Enable Dismissing', 'cenos' ),
                'description' => esc_html__('It’s possible to dismiss Announcement Bar','cenos'),
                'section' => 'announcement_section',
                'default' => false,
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'announcement_close_btn',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                ],
            ],
            'announcement_msg' => [
                'type'     => 'editor',
                'label'    => esc_html__( 'Message', 'cenos' ),
                'section'  => 'announcement_section',
                'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                'default'  => 'Annual Sale, 20% off on All Order',
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                ],
            ],
            'announcement_skip_divider_1' => [
                'type'        => 'custom',
                'section'     => 'announcement_section',
                'default'     => '<div class="fm_customize_divide"><hr/></div>',
                'active_callback' => [
                    [
                        'setting' => 'show_announcement',
                        'operator' => '==',
                        'value' => 1,
                    ],
                ],
            ],
            'show_announcement_btn'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Show Clickable Button', 'cenos' ),
                'section' => 'announcement_section',
                'default' => false,
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_btn_text' => [
                'type' => 'text',
                'label' => esc_html__('Button Text', 'cenos'),
                'section' => 'announcement_section',
                'default' => 'Check it Now',
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'show_announcement_btn',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_btn_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Button link URL.', 'cenos' ),
                'section'     => 'announcement_section',
                'default'     => '#',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'show_announcement_btn',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_btn_target' => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Open Link on New Tab', 'cenos' ),
                'section' => 'announcement_section',
                'default' => false,
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'show_announcement_btn',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_skip_divider_2' => [
                'type'        => 'custom',
                'section'     => 'announcement_section',
                'default'     => '<div class="fm_customize_divide"><hr/></div>',
                'active_callback' => [
                    [
                        'setting' => 'show_announcement',
                        'operator' => '==',
                        'value' => true,
                    ],
                ],
            ],
            'announcement_typo' => [
                'type' => 'typography',
                'label' => esc_html__('Announcement Typography', 'cenos'),
                'description' => esc_html__('Customize the Announcement font', 'cenos'),
                'section' => 'announcement_section',
                'default' => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '400',
                    'font-size'      => '14px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'color'          => '#333333',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_bg'                        => [
                'type'        => 'background',
                'label'       => esc_html__( 'Announcement Background', 'cenos' ),
                'section'     => 'announcement_section',
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
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],

            'announcement_skip_divider_3' => [
                'type'        => 'custom',
                'section'     => 'announcement_section',
                'default'     => '<div class="fm_customize_divide"><hr/></div>',
                'active_callback' => [
                    [
                        'setting' => 'show_announcement',
                        'operator' => '==',
                        'value' => true,
                    ],
                ],
            ],
            'show_announcement_countdown'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Show Countdown', 'cenos' ),
                'section' => 'announcement_section',
                'default' => false,
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'announcement_date' => [
                'type'        => 'date',
                'label'       => esc_html__( 'Countdown Time', 'cenos' ),
                'section'     => 'announcement_section',
                'default'     => '',
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                    [
                        'setting'  => 'show_announcement_countdown',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                ],
            ],
            'announcement_after_msg' => [
                'type'     => 'editor',
                'label'    => esc_html__( 'Text after time', 'cenos' ),
                'section'  => 'announcement_section',
                'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                'default'  => '',
                'active_callback' => [
                    [
                        'setting'  => 'show_announcement',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                    [
                        'setting'  => 'show_announcement_countdown',
                        'operator' => '==',
                        'value'    => 1,
                    ],
                ],
            ],
        ];
        return array_merge($fields, $ann_fields);
    }
}

new  Announcement();
