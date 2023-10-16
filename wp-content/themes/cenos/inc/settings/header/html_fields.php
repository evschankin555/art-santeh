<?php
if (!class_exists('Html_fields')) {
    class Html_fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'html_left_1' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Left Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'left_control',
                            'operator' => 'contains',
                            'value'    => 'html_left_1',
                        ]
                    ],
                ],
                'html_right_1' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Right Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'right_control',
                            'operator' => 'contains',
                            'value'    => 'html_right_1',
                        ]
                    ],
                ],
                'html_topbar_left' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Topbar Left Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'topbar_left',
                            'operator' => 'contains',
                            'value'    => 'html_topbar_left',
                        ]
                    ],
                ],
                'html_topbar_center' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Topbar Center Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'topbar_center',
                            'operator' => 'contains',
                            'value'    => 'html_topbar_center',
                        ]
                    ],
                ],
                'html_topbar_right' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Topbar Right Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'topbar_right',
                            'operator' => 'contains',
                            'value'    => 'html_topbar_right',
                        ]
                    ],
                ],
                'html_bottom_left' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Bottom Left Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'bottom_left_control',
                            'operator' => 'contains',
                            'value'    => 'html_bottom_left',
                        ]
                    ],
                ],
                'html_bottom_right' => [
                    'type'     => 'editor',
                    'label'    => esc_html__( 'HTML in Bottom Right Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' => esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'bottom_right_control',
                            'operator' => 'contains',
                            'value'    => 'html_bottom_right',
                        ]
                    ],
                ],
                'html_bottom_vertical' => [
                    'type'     => 'editor',
                    'label'    =>esc_html__( 'HTML in Bottom Control', 'cenos' ),
                    'section'  => 'head_html',
                    'description' =>esc_html__('Add Any HTML or Shortcode here...','cenos'),
                    'default'  => '<span>html box</span>',
                    'active_callback' => [
                        [
                            'setting'  => 'bottom_control',
                            'operator' => 'contains',
                            'value'    => 'html_bottom_vertical',
                        ]
                    ],
                ]
            ];
	    if (!current_theme_supports('header_vertical')){
                unset( $this->setting['html_bottom_vertical']);
            }
        }
        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Html_fields');
