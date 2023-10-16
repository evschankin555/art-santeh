<?php
class Header extends Settings
{
    public function getPanels($panels)
    {
        $panels['header'] = [
            'priority' => '11',
            'title' => esc_html__('Header', 'cenos'),
        ];
        return $panels;
    }

    public function getSections($sections)
    {
        $header_sections = [
            'header_general' => [
                'title' => esc_html__('Header General', 'cenos'),
                'panel' => 'header',
            ],
            'topbar' => [
                'title' => esc_html__('Top Bar', 'cenos'),
                'panel' => 'header',
            ],
            'head_layout' => [
                'title' => esc_html__('Header Main', 'cenos'),
                'panel' => 'header',
            ],
            'title_tagline' => [
                'title' => esc_html__('Logo & Site Identity', 'cenos'),
                'panel' => 'header',
            ],
            'head_search' => [
                'title' => esc_html__('Search', 'cenos'),
                'panel' => 'header',
            ],
            'head_cart' => [
                'title' => esc_html__('Cart', 'cenos'),
                'panel' => 'header',
            ],
            'head_account' => [
                'title' => esc_html__('My Account', 'cenos'),
                'panel' => 'header',
            ],
            'head_wishlist' => [
                'title' => esc_html__('Wishlist', 'cenos'),
                'panel' => 'header',
            ],
            'head_compare' => [
                'title' => esc_html__('Compare', 'cenos'),
                'panel' => 'header',
            ],
            'head_contact_info' => [
                'title' => esc_html__('Contact Info', 'cenos'),
                'panel' => 'header',
            ],
            'head_social' => [
                'title' => esc_html__('Social Icon', 'cenos'),
                'panel' => 'header',
            ],
            'head_hm' => [
                'title' => esc_html__('Hamburger Menu', 'cenos'),
                'panel' => 'header',
            ],
            'head_html' => [
                'title' => esc_html__('Html', 'cenos'),
                'panel' => 'header',
            ],
            'head_sticky' => [
                'title' => esc_html__('Sticky header', 'cenos'),
                'panel' => 'header',
            ],
            'head_transparent' => [
                'title' => esc_html__('Transparent header', 'cenos'),
                'panel' => 'header',
            ],
            'head_mobile' => [
                'title' => esc_html__('Mobile header', 'cenos'),
                'panel' => 'header',
            ]

        ];
        return array_merge($sections, $header_sections);
    }

    public function getFields($fields)
    {
        $control_element = [
            'search' => esc_html__( 'Search', 'cenos' ),
            'contact' => esc_html__( 'Contact Info', 'cenos' ),
            'social_icon' => esc_html__( 'Social Icon', 'cenos' ),
            'hamburger' => esc_html__( 'Hamburger Menu', 'cenos' ),
        ];
        $top_control_element = [];
        $bottom_control_element = [
            'contact' =>esc_html__( 'Contact Info', 'cenos' ),
            'social_icon' => esc_html__( 'Social Icon', 'cenos' ),
        ];
        if (cenos_is_woocommerce_activated()){
            $control_element['cart'] =esc_html__( 'Cart', 'cenos' );
            $control_element['account'] =esc_html__( 'My Account', 'cenos' );
            $top_control_element['cart'] = $control_element['cart'];
            $top_control_element['account'] = $control_element['account'];
        }
        if (class_exists('YITH_WCWL_Frontend') || ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')))){
            $control_element['wishlist'] =esc_html__( 'Wishlist', 'cenos' );
            $top_control_element['wishlist'] = $control_element['wishlist'];
        }
        if (class_exists( 'WOOCS' ) ) {
            $control_element['currency']  =esc_html__( 'Currency', 'cenos' );
            $bottom_control_element['currency'] = $control_element['currency'];
        }
        if (cenos_is_multi_language()){
            $control_element['language']  =esc_html__( 'Language', 'cenos' );
            $bottom_control_element['language'] = $control_element['language'];
        }

        $header_fields = [
            //general section
            'header_dimensions' => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Header container width', 'cenos' ),
                'section'     => 'header_general',
                'default'     => '1440px',
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'header_bg_color' => [
                'type' => 'color',
                'label' => esc_html__('Background Color', 'cenos'),
                'section' => 'header_general',
                'default' => '',
            ],
            'header_color' => [
                'type' => 'color',
                'label' => esc_html__('Text Color', 'cenos'),
                'section' => 'header_general',
                'default' => '',
            ],
            'text_color_hover' => [
                'type' => 'color',
                'label' => esc_html__('Text Hover Color', 'cenos'),
                'section' => 'header_general',
                'default' => '',
            ],
            'header_divider' => [
                'type' => 'radio-buttonset',
                'label' => esc_html__('Header Divider', 'cenos'),
                'section' => 'header_general',
                'default' => 'border',
                'choices' => [
                    'none' => esc_html__('None', 'cenos'),
                    'shadow' => esc_html__('Box shadow', 'cenos'),
                    'border' => esc_html__('Border', 'cenos'),
                ],
            ],
            'header_divider_color' => [
                'type' => 'color',
                'label' => esc_html__('Divider Color', 'cenos'),
                'section' => 'header_general',
                'default' => '#e4e4e4',
                'active_callback' => [
                    [
                        'setting'  => 'header_divider',
                        'operator' => '==',
                        'value'    => 'border',
                    ],
                ],
            ],
            'header_quick_menu' => [
                'type' => 'toggle',
                'label' => esc_html__('Mega Menu Speedup', 'cenos'),
                'section' => 'header_general',
                'default' => false,
            ],

            //header layout
            'header_vertical' => [
                'type' => 'toggle',
                'label' =>esc_html__('Header Vertical', 'cenos'),
                'section' => 'head_layout',
                'default' => false,
            ],
            'header_height'     => array(
                'type'    => 'slider',
                'label'   => esc_html__( 'Height', 'cenos' ),
                'default' => '',
                'section' => 'head_layout',
                'choices'         => array(
                    'min' => 50,
                    'max' => 400,
                ),
                'active_callback' => [
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
            ),
            'header_layout' => [
                'type' => 'radio-image',
                'label' => esc_html__('Header Layout', 'cenos'),
                'section' => 'head_layout',
                'default' => 'layout1',
                'choices' => [
                    'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout1.jpg',
                    'layout2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout2.png',
                    'layout3' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout3.jpg',
                    'layout4' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout4.jpg',
                    'layout5' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout5.jpg',
                    'layout6' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout6.jpg',
                    'layout7' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout7.jpg',
                    'layout8' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout8.jpg',
                    'layout9' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout9.jpg',
                    'layout10' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/header/layout10.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
            ],
            'site_nav_align' => [
                'type' => 'radio-buttonset',
                'label' => esc_html__('Primary Nav Align', 'cenos'),
                'section' => 'head_layout',
                'default' => 'left',
                'choices' => [
                    'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                    'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                    'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'header_layout',
                        'operator' => 'in',
                        'value'    => ['layout1'],
                    ],
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
            ],
            'left_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Left Control Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_left_1' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => [],
                'active_callback' => [
                    [
                        'setting'  => 'header_layout',
                        'operator' => 'in',
                        'value'    => ['layout3','layout4','layout5', 'layout10'],
                    ],
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
                'choices'     => array_merge($control_element,array('html_left_1' => esc_html__( 'Html', 'cenos' ))),
                'partial_refresh'    => [
                    'header-left-control' => [
                        'selector'        => '.header-left-control',
                        'render_callback' => function() {
                            do_action('cenos_header_control','left_control');
                        },
                    ]
                ],

            ],
            'right_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Right Control Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_right_1' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => ['search'],
                'choices'     => array_merge($control_element,['html_right_1' => esc_html__( 'Html', 'cenos' )]),
                'partial_refresh'    => [
                    'header-right-control' => [
                        'selector'        => '.header-right-control',
                        'render_callback' => function() {
                            do_action('cenos_header_control','right_control');
                        },
                    ]
                ],
                'active_callback' => [
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
            ],
            'bottom_left_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Bottom Left Control Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_bottom_left' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => [
                ],
                'active_callback' => [
                    [
                        'setting'  => 'header_layout',
                        'operator' => 'in',
                        'value'    => ['layout10'],
                    ],
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
                'choices'     => array_merge($control_element,array('html_bottom_left' => esc_html__( 'Html', 'cenos' ))),
            ],
            'bottom_right_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Bottom Right Control Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_bottom_right' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => [],
                'choices'     => array_merge($control_element,['html_bottom_right' => esc_html__( 'Html', 'cenos' )]),
                'active_callback' => [
                    [
                        'setting'  => 'header_layout',
                        'operator' => 'in',
                        'value'    => ['layout10'],
                    ],
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '!=',
                        'value'    => true,
                    ],
                ],
            ],
            'top_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Top Control Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_bottom_left' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => [
                ],
                'active_callback' => [
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
                'choices'     => $top_control_element,
            ],
            'bottom_control' => [
                'type'        => 'sortable',
                'label'       => esc_html__( 'Bottom Elements', 'cenos' ),
                'section'     => 'head_layout',
                'items_section' => [
                    'search' => 'head_search',
                    'cart' => 'head_cart',
                    'account' => 'head_account',
                    'wishlist' => 'head_wishlist',
                    'html_bottom_right' => 'head_html',
                    'contact' => 'head_contact_info',
                    'hamburger' => 'head_hm',
                    'social_icon' => 'head_social'
                ],
                'default'     => [],
                'choices'     => array_merge($bottom_control_element,['html_bottom_vertical' =>esc_html__( 'Html', 'cenos' )]),
                'active_callback' => [
                    [
                        'setting'  => 'header_vertical',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
        ];
        $logo_field = require_once 'logo_fields.php';
        $search_field = require_once 'search_fields.php';
        $cart_field = require_once 'cart_fields.php';
        $account_field = require_once 'account_fields.php';
        $wishlist_field = [];
        if(class_exists( 'YITH_WCWL' ) || (defined('TINVWL_FVERSION') || defined('TINVWL_VERSION'))) {
            $wishlist_field = require_once 'wishlist_fields.php';
        }
        $contact_field = require_once 'contact_fields.php';
        $html_field = require_once 'html_fields.php';
        $social_field = require_once 'social_fields.php';
        $hambuger_field = require_once 'hamburger_fields.php';
        $top_bar_field = require_once 'topbar_fields.php';
        $sticky_field = require_once 'sticky_fields.php';
        $transparent_field = require_once 'transparent_fields.php';
        $mobile_field = require_once 'mobile_fields.php';
        return array_merge($fields, $header_fields,$logo_field,$search_field,$cart_field,$account_field,$wishlist_field,$contact_field,$html_field,$social_field,$hambuger_field,$top_bar_field,$sticky_field,$transparent_field,$mobile_field);
    }
}

new Header();
