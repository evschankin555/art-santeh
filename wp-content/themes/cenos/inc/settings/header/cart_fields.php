<?php
if (!class_exists('Cart_Fields')) {
    class Cart_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'cart_style' => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Cart Style', 'cenos'),
                    'section' => 'head_cart',
                    'default' => 'dropdown',
                    'choices' => [
                        'dropdown' => esc_html__( 'Dropdown', 'cenos' ),
                        'canvas' => esc_html__( 'Off Canvas', 'cenos' ),
                        'link' => esc_html__( 'Link Only', 'cenos' ),
                    ]
                ],
                'show_cart_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_cart',
                    'default' => true,
                ],
                'show_cart_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Title', 'cenos'),
                    'section' => 'head_cart',
                    'default' => false,
                ],
                'cart_title' => [
                    'type' => 'text',
                    'label' => esc_html__('Cart Title', 'cenos'),
                    'section' => 'head_cart',
                    'default' => 'Cart',
                    'active_callback' => [
                        [
                            'setting' => 'show_cart_title',
                            'operator' => '==',
                            'value' => true,
                        ],
                    ],
                ],
                'show_cart_counter' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Counter', 'cenos'),
                    'description' => esc_html__('Show number of items in the cart.','cenos'),
                    'section' => 'head_cart',
                    'default' => false,
                ],
                //
                'show_cart_total' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Total', 'cenos'),
                    'description' => esc_html__('Show the sub total (after calculation).','cenos'),
                    'section' => 'head_cart',
                    'default' => false,
                ],
                'show_cart_empty' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Enable Empty Cart Box', 'cenos'),
                    'section' => 'head_cart',
                    'default' => false,
                    'active_callback' => [
                        [
                            'setting' => 'cart_style',
                            'operator' => '!=',
                            'value' => 'link',
                        ],
                    ],
                ],
                'cart_margin' => [
                    'type' => 'dimensions',
                    'label' => esc_html__('Cart Margin', 'cenos'),
                    'description' => esc_html__('The distance between the Cart and other elements on the Header of page.','cenos'),
                    'section' => 'head_cart',
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
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Cart_Fields');
