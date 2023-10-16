<?php

class Direction extends Settings
{

    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {
        $style_sections = [
            'direction_section' => array(
                'panel' => 'mobile_setting',
                'title' => esc_html__('Direction Bar ', 'cenos'),
            ),
        ];
        return array_merge($sections, $style_sections);
    }

    public function getFields($fields)
    {
        $default_direction = ['shop','account','cart'];
        $direction_choices = [
            'shop' => esc_html__('Shop','cenos'),
            'account' => esc_html__('My Account','cenos'),
            'cart' => esc_html__('Cart','cenos'),

        ];
        if (class_exists('YITH_WCWL_Frontend') || (defined('TINVWL_FVERSION') || defined('TINVWL_VERSION'))){
            $direction_choices['wishlist'] = esc_html__( 'Wishlist', 'cenos' );
            $default_direction[] = 'wishlist';
        }
        $direction_choices['menu'] = esc_html__( 'Menu', 'cenos' );
        $default_direction[] = 'menu';
        $direction_fields = [
            'show_direction_bar'             => [
                'type'    => 'toggle',
                'label'   => esc_html__( 'Show Direction Bar', 'cenos' ),
                'section' => 'direction_section',
                'default' => true,
            ],
            'direction_component'  => [
                'type'        => 'sortable',
                'label' => esc_html__('Direction Component', 'cenos'),
                'section' => 'direction_section',
                'default'     => $default_direction,
                'choices'     => $direction_choices,
                'active_callback' => [
                    [
                        'setting'  => 'show_direction_bar',
                        'operator' => '!=',
                        'value'    => false,
                    ]
                ]
            ],
        ];
        return array_merge($fields, $direction_fields);
    }
}

new  Direction();
