<?php


if (!class_exists('Search_Fields')) {
    class Search_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'search_type' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Search Type', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'button',
                    'choices' => [
                        'button' => esc_html__( 'Search Button', 'cenos' ),
                        'form' => esc_html__( 'Search Form', 'cenos' ),
                    ]
                ],
                'search_display_style'  => [
                    'type' => 'radio-buttonset',
                    'label' =>esc_html__('Search Form Display Type', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'dropdown',
                    'choices' => [
                        'dropdown' =>esc_html__( 'Dropdown', 'cenos' ),
                        'modal' =>esc_html__( 'Modal', 'cenos' ),
                        'canvas' =>esc_html__( 'Off canvas', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'search_type',
                            'operator' => '==',
                            'value' => 'button',
                        ],
                    ],
                ],
                'group_hd_search_heading_1' => [
                    'type'        => 'custom',
                    'section'     => 'head_search',
                    'default'     => '<div class="group-heading-control">' .esc_html__( 'Search form setting', 'cenos' ) . '</div>',
                ],
                'search_form_style'  => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Search Form Style', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'style1',
                    'choices' => [
                        'style1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/search/form_style_1.jpg',
                    ]
                ],
                'search_placeholder_text' => [
                    'type' => 'text',
                    'label' => esc_html__('Text Placeholder', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'Search...'
                ],
                'search_btn_text' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Text in Search Button ', 'cenos'),
                    'section' => 'head_search',
                    'default' => false,
                ],
                'search_btn_text_content' => [
                    'type' => 'text',
                    'label' => esc_html__('Text in Search Button', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'Search',
                    'active_callback' => [
                        [
                            'setting' => 'search_btn_text',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'search_clear_btn' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Clear Button', 'cenos'),
                    'section' => 'head_search',
                    'default' => false,
                ],
                'search_margin' => [
                    'type' => 'dimensions',
                    'label' => esc_html__('Search Align', 'cenos'),
                    'description' => esc_html__('The distance between the My Search and other elements on the Header of page.','cenos'),
                    'section' => 'head_search',
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
                'group_hd_search_heading_2' => [
                    'type'        => 'custom',
                    'section'     => 'head_search',
                    'default'     => '<div class="group-heading-control">' . esc_html__( 'Search Modal Setting', 'cenos' ) . '</div>',
                    'active_callback' => [
                        [
                            'setting' => 'search_type',
                            'operator' => '==',
                            'value' => 'button',
                        ],
                        [
                            'setting' => 'search_display_style',
                            'operator' => '==',
                            'value' => 'modal',
                        ],
                    ],
                ],
                'search_heading_text' => [
                    'type' => 'text',
                    'label' => esc_html__('Heading Text in Search Form ', 'cenos'),
                    'section' => 'head_search',
                    'default' => 'Type the keyword or SKU',
                    'active_callback' => [
                        [
                            'setting' => 'search_type',
                            'operator' => '==',
                            'value' => 'button',
                        ],
                        [
                            'setting' => 'search_display_style',
                            'operator' => '==',
                            'value' => 'modal',
                        ],
                    ],
                ],
                'search_width'  => [
                    'type'        => 'slider',
                    'label'       => esc_html__( 'Search Form Width', 'cenos' ),
                    'section'     => 'head_search',
                    'default'     => 380,
                    'choices'     => [
                        'min'  => 200,
                        'max'  => 800,
                        'step' => 10,
                    ],
                ],
                'group_hd_search_heading_3' => [
                    'type'        => 'custom',
                    'section'     => 'head_search',
                    'default'     => '<div class="group-heading-control">' . esc_html__( 'Ajax Live Search', 'cenos' ) . '</div>',
                ],
                'search_ajax'  => [
                    'type' => 'toggle',
                    'label' => esc_html__('Ajax Search', 'cenos'),
                    'section' => 'head_search',
                    'default' => false,
                ],
                'limit_results_search' => [
                    'type'        => 'number',
                    'label'       => esc_html__( 'Results Ajax Search (Limit Products)', 'cenos' ),
                    'section'     => 'head_search',
                    'default'     => 5,
                    'choices'     => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                    'active_callback' => [
                        [
                            'setting' => 'search_ajax',
                            'operator' => '==',
                            'value' => true,
                        ]
                    ],
                ]
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Search_Fields');
