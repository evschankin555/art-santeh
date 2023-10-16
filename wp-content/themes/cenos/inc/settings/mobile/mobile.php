<?php





class Mobile_Setting extends Settings
{

    public function getPanels($panels)
    {
        $panels['mobile_setting'] = [
            'priority' => '301',
            'title' =>esc_html__('Mobile', 'cenos'),
        ];
        return $panels;
    }

    public function getSections($sections)
    {
        $mobile_sections = [
            'm_product_section' => array(
                'panel' => 'mobile_setting',
                'title' => esc_html__('Single Product', 'cenos'),
            ),
            'm_product_item' => [
                'panel' => 'mobile_setting',
                'title' => esc_html__('Product Items', 'cenos'),
            ]
        ];
        return array_merge($sections, $mobile_sections);
    }

    public function getFields($fields)
    {
        $header_fields = [
            'mobile_single_product' => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Mobile Single Template', 'cenos' ),
                'section' => 'm_product_section',
                'default' => false,
            ],
            'mobile_single_product_layout' => [
                'type' => 'radio-image',
                'label' =>esc_html__('Mobile Single Product Layout', 'cenos'),
                'section' => 'm_product_section',
                'default' => 'layout_01',
                'choices' => [
                    'layout_01' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/mobile_product_layout1.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'mobile_single_product',
                        'operator' => '==',
                        'value'    => true,
                    ]
                ],
            ],
            'mobile_single_product_tab' => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Mobile Owner Tabs Style', 'cenos' ),
                'section' => 'm_product_section',
                'default' => false,
            ],
            'mobile_single_tabs_style'     => [
                'type' => 'select',
                'label'   =>esc_html__( 'Product Tabs Style', 'cenos' ),
                'section' => 'm_product_section',
                'default' => 'default',
                'choices' => [
                    'default'    => esc_html__( 'Default', 'cenos' ),
                    'accordion' => esc_html__( 'Accordion', 'cenos' ),
                    'vertical' => esc_html__( 'Vertical', 'cenos' ),
                    'sections' => esc_html__( 'Sections', 'cenos' ),
                    //'canvas' => esc_html__( 'Off Canvas', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'mobile_single_product_tab',
                        'operator' => '==',
                        'value'    => true,
                    ]
                ],
            ],
            'single_sticky_atc'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Sticky Add To Cart', 'cenos'),
                'default'   => true,
                'section'   => 'm_product_section',
            ],
            'mobile_product_items' => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Mobile Product Items Template', 'cenos' ),
                'section' => 'm_product_item',
                'default' => false,
            ],
            'mobile_product_items_style' => [
                'type' => 'radio-image',
                'label' =>esc_html__('Mobile Product Items Style', 'cenos'),
                'section' => 'm_product_item',
                'default' => 'layout_m_01',
                'choices' => [
                    'layout_m_01' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/layout_m_01.jpg',
                    'layout_m_02' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/layout_m_02.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'mobile_product_items',
                        'operator' => '==',
                        'value'    => true,
                    ]
                ],
            ],
        ];
        return array_merge($fields, $header_fields);
    }
}

new Mobile_Setting();
