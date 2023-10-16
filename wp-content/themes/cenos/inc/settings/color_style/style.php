<?php
class Style extends Settings
{

    public function getPanels($panels)
    {
        $panels['color_style'] = array(
            'title' => esc_html__('Color & style', 'cenos'),
        );
        return $panels;
    }

    public function getSections($sections)
    {
        $style_sections = [
            'button_style' => array(
                'title' => esc_html__('Buttons style ', 'cenos'),
                'panel' => 'color_style',
            ),
            'forms_style' => array(
                'title' => esc_html__('Forms style', 'cenos'),
                'panel' => 'color_style',
            ),
            'color_border' => array(
                'title' => esc_html__('Color & border', 'cenos'),
                'panel' => 'color_style',
            ),
            'nav_style' => array(
                'title' => esc_html__('Pagination Style', 'cenos'),
                'panel' => 'color_style',
            ),
        ];
        return array_merge($sections, $style_sections);
    }

    public function getFields($fields)
    {
        $style_fields = [
            //button primary setting
            'group_hd_btn_heading_default' => [
                'type'        => 'custom',
                'section'     => 'button_style',
                'default'     => '<div class="group-heading-control">' .esc_html__( 'Primary button', 'cenos' ) . '</div>',
            ],
            'btn_default_style'    => [
                'type'        => 'radio-image',
                'label'       => esc_html__( 'Button style', 'cenos' ),
                'section'     => 'button_style',
                'default'     => 'flat',
                'choices'     => [
                    'flat'   => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/btn/btn_flat.jpg',
                    'advance' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/btn/btn_advance.jpg'
                ],
            ],
            'btn_default_bg'    => [
                'type'        => 'color',
                'label'       => esc_html__( 'Background Color', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
                'choices'     => [
                    'alpha' => true,
                ],
            ],
            'btn_default_color'    => [
                'type'        => 'color',
                'label'       => esc_html__( 'Text Color', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
            ],
            'btn_default_bg_hover'    => [
                'type'        => 'color',
                'label'       => esc_html__( 'Background Color Hover', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
                'choices'     => [
                    'alpha' => true,
                ],
            ],
            'btn_default_color_hover'    => [
                'type'        => 'color',
                'label'       => esc_html__( 'Text Color Hover', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
            ],
            'btn_default_border_color'  => [
                'type'        => 'color',
                'label'       => esc_html__( 'Border Color', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                ],
            ],
            'btn_default_border_color_hover'  => [
                'type'        => 'color',
                'label'       => esc_html__( 'Border Color Hover', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '',
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                ],
            ],
            'btn_default_border_style'  => [
                'type'        => 'select',
                'label'       => esc_html__( 'Border Style', 'cenos' ),
                'section'     => 'button_style',
                'default'     => 'solid',
                'choices'     => [
                    'none' => esc_html__( 'None', 'cenos' ),
                    'dotted' => esc_html__( 'dotted', 'cenos' ),
                    'dashed' => esc_html__( 'dashed', 'cenos' ),
                    'solid' => esc_html__( 'solid', 'cenos' ),
                    'double' => esc_html__( 'double', 'cenos' ),
                    'ridge' => esc_html__( 'ridge', 'cenos' ),
                    'inset' => esc_html__( 'inset', 'cenos' ),
                    'outset' => esc_html__( 'outset', 'cenos' ),

                ],
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],

                ],
            ],
            'btn_default_border_width'  => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Border Width', 'cenos' ),
                'description' => esc_html__( 'The border-width property sets the width of an element\'s four borders. This property can have from one to four values.', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '2px',
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                    [
                        'setting' => 'btn_default_border_style',
                        'operator' => '!=',
                        'value' => 'none',
                    ],
                ],
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'btn_default_border_radius'  => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Border Radius', 'cenos' ),
                'section'     => 'button_style',
                'default'     => '5px',
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                    [
                        'setting' => 'btn_default_border_style',
                        'operator' => '!=',
                        'value' => 'none',
                    ],
                ],
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'btn_own_typo' => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Text Button Setting', 'cenos' ),
                'section' => 'button_style',
                'default' => false,
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                ],
            ],
            'btn_default_typography' => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Button Typography', 'cenos' ),
                'section'     => 'button_style',
                'default' => [
                    'font-family' => 'Roboto',
                    'variant' => '300',
                    'font-size' => '14px',
                    'line-height'     => '1.5',
                    'text-transform' => 'none',
                ],
                'active_callback' => [
                    [
                        'setting' => 'btn_default_style',
                        'operator' => '==',
                        'value' => 'advance',
                    ],
                    [
                        'setting' => 'btn_own_typo',
                        'operator' => '==',
                        'value' => true,
                    ],

                ],
            ],
            // forms style
            'form_fields_style'    => [
                'type'        => 'radio-image',
                'label'       =>esc_html__( 'Form Fields Style', 'cenos' ),
                'section'     => 'forms_style',
                'default'     => 'square',
                'choices'     => [
                    'square'  => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/form-style/square.jpg',
                    'round' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/form-style/rounded.jpg',
                    'circle'  => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/form-style/circle.jpg',
                ],
            ],
            //Color & Border
            'main_color'    => [
                'type'        => 'color',
                'label'       => esc_html__( 'Main Color', 'cenos' ),
                'section'     => 'color_border',
                'default'     => '#83b735',
            ],
            
            'link_color'   => [
                'type'        => 'color',
                'label'       => esc_html__( 'Link Color', 'cenos' ),
                'section'     => 'color_border',
                'default'     => '#242424',
            ],
            'link_color_hover'   => [
                'type'        => 'color',
                'label'       => esc_html__( 'Link Color Hover', 'cenos' ),
                'section'     => 'color_border',
                'default'     => '#e36c02',
            ],
            // nav style
            'page_pagination_style' => [
                'type'        => 'radio-image',
                'label'       => esc_html__( 'Style', 'cenos' ),
                'section'     => 'nav_style',
                'default'     => 'style1',
                'choices'     => [
                    'style1'   => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/nav/pagi_style1.jpg',
                    'style2'   => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/nav/pagi_style2.jpg',
                ],
            ],
            'page_pagination_align' => [
                'type' => 'radio-buttonset',
                'label' => esc_html__('Pagination Align', 'cenos'),
                'section' => 'nav_style',
                'default' => 'center',
                'choices' => [
                    'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                    'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                    'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                ]
            ],
        ];
        return array_merge($fields, $style_fields);
    }
}
new Style();
