<?php


if (!class_exists('Wishlist_Fields')) {
    class Wishlist_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'show_wishlist_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_wishlist',
                    'default' => true,
                ],
                'show_wishlist_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Title', 'cenos'),
                    'section' => 'head_wishlist',
                    'default' => false,
                ],
                'wishlist_title' => [
                    'type' => 'text',
                    'label' => esc_html__('Wishlist Title', 'cenos'),
                    'section' => 'head_wishlist',
                    'default' => 'Wishlist',
                ],
                'wishlist_counter' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Counter', 'cenos'),
                    'description' => esc_html__('Show number of items in the wishlist.','cenos'),
                    'section' => 'head_wishlist',
                    'default' => false,
                ],
                'wishlist_margin' => [
                    'type' => 'dimensions',
                    'label' => esc_html__('Wishlist Align', 'cenos'),
                    'description' => esc_html__('The distance between the Wishlist and other elements on the Header of page.','cenos'),
                    'section' => 'head_wishlist',
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
return cenos_customize_field_setting('Wishlist_Fields');
