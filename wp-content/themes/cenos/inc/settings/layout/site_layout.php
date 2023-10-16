<?php
class Site_layout extends Settings
{

    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {
        $site_layout_sections = [
            'layout'     => [
                'priority' => '10',
                'title'    => esc_html__( 'Site Layout', 'cenos' ),
            ]
        ];
        return array_merge($sections,$site_layout_sections);
    }

    public function getFields($fields)
    {
        $site_layout_fields = [
            'site_layout'                    => [
                'type'        => 'radio-buttonset',
                'label'       => esc_html__( 'Site Layout', 'cenos' ),
                'section'     => 'layout',
                'default'     => 'full',
                'choices'     => [
                    'full'   => esc_html__( 'Full Width', 'cenos' ),
                    'boxed' => esc_html__( 'Boxed', 'cenos' ),
                    'framed'  => esc_html__( 'Framed', 'cenos' ),
                ],

            ],
            'site_width'                     => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Site Boxed/Framed Width', 'cenos' ),
                'section'     => 'layout',
                'default'     => '1440px',
                'active_callback' => [
                    [
                        'setting'  => 'site_layout',
                        'operator' => 'in',
                        'value'    => ['boxed','framed'],
                    ],
                ],
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'content_box_shadow'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Content Box Shadow', 'cenos' ),
                'section' => 'layout',
                'default' => false,
                'active_callback' => [
                    [
                        'setting'  => 'site_layout',
                        'operator' => 'in',
                        'value'    => ['boxed','framed'],
                    ],
                ],
            ],
            'framed_margin' => array(
                'type' => 'dimensions',
                'label' => esc_html__('Framed Margin', 'cenos'),
                'section' => 'layout',
                'default' => [
                    'margin-top' => '30px',
                    'margin-bottom' => '30px',
                ],
                'choices'     => [
                    'accept_unitless' => false,
                    'labels' => [
                        'margin-top'  => esc_html__( 'Margin Top', 'cenos' ),
                        'margin-bottom'  => esc_html__( 'Margin Bottom', 'cenos' ),
                    ],
                ],
                'active_callback' => [
                    [
                        'setting'  => 'site_layout',
                        'operator' => '==',
                        'value'    => 'framed',
                    ],
                ],
            ),
            'container_width'                => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Container Width', 'cenos' ),
                'section'     => 'layout',
                'default'     => '1440px',
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'site_bg'                        => [
                'type'        => 'background',
                'label'       => esc_html__( 'Site Background', 'cenos' ),
                'section'     => 'layout',
                'default'     => [
                    'background-color'      => '',
                    'background-image'      => '',
                    'background-repeat'     => 'repeat',
                    'background-position'   => 'center center',
                    'background-size'       => 'cover',
                    'background-attachment' => 'scroll',
                ],
            ],
            'site_advance_style'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Site Advance Style', 'cenos' ),
                'section' => 'layout',
                'default' => false,
            ],
            'site_content_bg'                        => [
                'type'        => 'background',
                'label'       => esc_html__( 'Content Background', 'cenos' ),
                'section'     => 'layout',
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
                        'setting'  => 'site_advance_style',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'site_content_dimensions' => [
                'type' => 'dimensions',
                'label' => esc_html__('Content Padding', 'cenos'),
                'section' => 'layout',
                'default' => [
                    'padding-top' => '50px',
                    'padding-bottom' => '50px',
                ],
                'choices'     => [
                    'accept_unitless' => false,
                    'labels' => [
                        'padding-top'  => esc_html__( 'Padding Top', 'cenos' ),
                        'padding-bottom'  => esc_html__( 'Padding Bottom', 'cenos' ),
                    ],
                ],
                'active_callback' => [
                    [
                        'setting'  => 'site_advance_style',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
        ];
        return array_merge($fields,$site_layout_fields);
    }
}
new Site_layout();
