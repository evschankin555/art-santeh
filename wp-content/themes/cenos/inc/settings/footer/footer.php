<?php
class Footer extends Settings
{
    public function getPanels($panels)
    {
        return $panels;
    }
    public function getSections($sections)
    {
        $footer_sections = [
            'footer_general' => [
                'priority' => '20',
                'title' => esc_html__('Footer', 'cenos'),
            ],
        ];
        return array_merge($sections, $footer_sections);
    }

    public function getFields($fields)
    {
        global $block_choices;
        $footer_choices = [
            'copyright' => esc_html__( 'Copyright', 'cenos' ),
            'html'      => esc_html__( 'Html', 'cenos' ),
        ];
        $footer_fields = [
            'footer_post'              => [
                'type'        => 'select',
                'label'       => esc_html__( 'Footer Block', 'cenos' ),
                'section'     => 'footer_general',
                'default'     => 'none',
                'choices'     => $block_choices,
            ],
            'footer_bg'                => [
                'type'        => 'background',
                'label'       => esc_html__( 'Footer Background', 'cenos' ),
                'section'     => 'footer_general',
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
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ],
            ],
            'footer_color' => [
                'type' => 'color',
                'label' => esc_html__('Text Color', 'cenos'),
                'section' => 'footer_general',
                'default' => '',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ],
            ],

            //footer main setting
            'footer_main_group_hd' => [
                'type'        => 'custom',
                'section'     => 'footer_general',
                'default'     => '<div class="group-heading-control">'.esc_html__( 'Footer Main', 'cenos' ).'</div>',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ],
            ],

            'footer_main_bg'                => [
                'type'        => 'background',
                'label'       => esc_html__( 'Background', 'cenos' ),
                'section'     => 'footer_general',
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
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ],
            ],
            'footer_main_color' => [
                'type' => 'color',
                'label' => esc_html__('Text Color', 'cenos'),
                'section' => 'footer_general',
                'default' => '',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ],
            ],
            'footer_main_padding' => [
                'type' => 'dimensions',
                'label' => esc_html__('Padding', 'cenos'),
                'section' => 'footer_general',
                'default'     => [
                    'padding-top'    => '',
                    'padding-bottom' => '',
                    'padding-left'   => '',
                    'padding-right'  => '',
                ],
                'choices'     => [
                    'accept_unitless' => false,
                    'labels' => [
                        'padding-left'  => esc_html__( 'Padding Left', 'cenos' ),
                        'padding-right'  => esc_html__( 'Padding Right', 'cenos' ),
                        'padding-top'  => esc_html__( 'Padding Top', 'cenos' ),
                        'padding-bottom'  => esc_html__( 'Padding Bottom', 'cenos' ),
                    ]
                ],
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ]
            ],
            'footer_main_left'  => [
                'type'        => 'sortable',
                'label' => esc_html__('Left Items', 'cenos'),
                'section' => 'footer_general',
                'default'     => [
                    'copyright',
                ],
                'choices'     => $footer_choices,
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ]
            ],
            'footer_main_center'  => [
                'type'        => 'sortable',
                'label' => esc_html__('Center Items', 'cenos'),
                'section' => 'footer_general',
                'default'     => [],
                'choices'     => $footer_choices,
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ]
            ],
            'footer_main_right'  => [
                'type'        => 'sortable',
                'label' => esc_html__('Right Items', 'cenos'),
                'section' => 'footer_general',
                'default'     => [
                    'html',
                ],
                'choices'     => $footer_choices,
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ]
            ],
            'footer_copyright' => [
                'type'     => 'textarea',
                'label'    => esc_html__( 'Copyright', 'cenos' ),
                'section'  => 'footer_general',
                'default'  => sprintf('<span>%s</span>',esc_html__('Copyright 2022 © Familab', 'cenos')),
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ]
                ]
            ],
            'footer_html_left' => [
                'type'     => 'textarea',
                'label'    => esc_html__( 'Left HTML', 'cenos' ),
                'section'  => 'footer_general',
                'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                'default'  => '',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ],
                    [
                        'setting'  => 'footer_main_left',
                        'operator' => 'contains',
                        'value'    => 'html',
                    ]
                ]
            ],
            'footer_html_center' => [
                'type'     => 'textarea',
                'label'    => esc_html__( 'Center HTML', 'cenos' ),
                'section'  => 'footer_general',
                'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                'default'  => '',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ],
                    [
                        'setting'  => 'footer_main_center',
                        'operator' => 'contains',
                        'value'    => 'html',
                    ]
                ]
            ],
            'footer_html_right' => [
                'type'     => 'textarea',
                'label'    => esc_html__( 'Right HTML', 'cenos' ),
                'section'  => 'footer_general',
                'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                'default'  => '',
                'active_callback' => [
                    [
                        'setting'  => 'footer_post',
                        'operator' => '==',
                        'value'    => 'none',
                    ],
                    [
                        'setting'  => 'footer_main_right',
                        'operator' => 'contains',
                        'value'    => 'html',
                    ]
                ]
            ]
        ];
        return array_merge($fields,$footer_fields);
    }
}

new Footer();
