<?php

if (!class_exists('Shop_Fields')) {
    class Shop_Fields
    {
        protected $setting;

        public function __construct()
        {
            global $block_choices;
            $this->setting = [
                // shop header
                'shop_heading'                   => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Enable Shop Heading', 'cenos' ),
                    'description' => esc_html__( 'Enable the heading on Product Catalog.', 'cenos' ),
                    'section'     => 'sh_header',
                    'default'     => true,
                ],
                'shop_heading_mobile'                   => [
                    'type'        => 'toggle',
                    'label'       =>esc_html__( 'Shop Heading On Mobile', 'cenos' ),
                    'section'     => 'sh_header',
                    'default'     => true,
                ],
                'shop_heading_post'              => [
                    'type'        => 'select',
                    'label'       => esc_html__( 'Heading Block', 'cenos' ),
                    'description' => esc_html__( 'You can replace the Shop Heading with a Custom Block that you can edit in the Page Builder.', 'cenos' ),
                    'section'     => 'sh_header',
                    'default'     => false,
                    'choices'     => $block_choices,
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'shop_heading_layout'            => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Heading Layout', 'cenos'),
                    'section' => 'sh_header',
                    'default' => 'simple',
                    'choices' => [
                        'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/heading_layout1.jpg',
                        'simple' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/heading_layout_simple.jpg',
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],
                ],
                'shop_categories_desc'       => [
                    'type'        => 'toggle',
                    'label'   => esc_html__( 'Show Categories Description', 'cenos' ),
                    'section' => 'sh_header',
                    'default'     => false,
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_width'             => [
                    'type'        => 'radio-buttonset',
                    'label'       => esc_html__( 'Heading Width', 'cenos' ),
                    'section'     => 'sh_header',
                    'default'     => 'full',
                    'choices'     => [
                        'full'   => esc_html__( 'Full Width', 'cenos' ),
                        'container' => esc_html__( 'Site container', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],
                ],
                'shop_heading_height'            => [
                    'type'        => 'dimension',
                    'label'       => esc_html__( 'Heading Height', 'cenos' ),
                    'section'     => 'sh_header',
                    'default'     => '400px',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                    'choices'     => [
                        'accept_unitless' => false,
                    ],
                ],
                'shop_heading_bg'                => [
                    'type'        => 'background',
                    'label'       => esc_html__( 'Heading Background', 'cenos' ),
                    'section'     => 'sh_header',
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
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_simple_height'             => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Heading Height', 'cenos'),
                    'section' => 'sh_header',
                    'default' => 'small',
                    'choices' => [
                        'small' => esc_html__( 'Small', 'cenos' ),
                        'medium' => esc_html__( 'Medium', 'cenos' ),
                        'large' => esc_html__( 'Large', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '==',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_simple_background' => [
                    'type' => 'color',
                    'label' =>esc_html__('Background', 'cenos'),
                    'section' => 'sh_header',
                    'default' => '#f8f8f8',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '==',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_text_color'        => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Text Color', 'cenos'),
                    'section' => 'sh_header',
                    'default' => 'dark',
                    'choices' => [
                        'dark' => esc_attr__( 'Dark', 'cenos' ),
                        'light'    => esc_attr__( 'Light', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],

                ],
                'shop_heading_align'             => [
                    'type' => 'radio-buttonset',
                    'label' => esc_html__('Text align', 'cenos'),
                    'section' => 'sh_header',
                    'default' => 'left',
                    'choices' => [
                        'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                        'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                        'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_padding'           => [
                    'type' => 'dimensions',
                    'label' => esc_html__('Padding', 'cenos'),
                    'section' => 'sh_header',
                    'default' => [
                        'padding-left' => '',
                        'padding-right' => '',
                        'padding-top' => '',
                        'padding-bottom' => '',
                    ],
                    'choices'     => [
                        'accept_unitless' => false,
                        'labels' => [
                            'padding-left'  => esc_html__( 'Padding Left', 'cenos' ),
                            'padding-right'  => esc_html__( 'Padding Right', 'cenos' ),
                            'padding-top'  => esc_html__( 'Padding Top', 'cenos' ),
                            'padding-bottom'  => esc_html__( 'Padding Bottom', 'cenos' ),
                        ],
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_post',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_heading_divider'       => [
                    'type'        => 'toggle',
                    'label'   => esc_html__( 'Shop Heading Divider', 'cenos' ),
                    'section' => 'sh_header',
                    'default'     => true,
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                'shop_heading_divider_color'        => [
                    'type' => 'color',
                    'label' => esc_html__('Divider Color', 'cenos'),
                    'section' => 'sh_header',
                    'default' => '#e8e8e8',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_divider',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],

                ],
                //products control
                'shop_control_layout'           => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Shop Control Layout', 'cenos'),
                    'section' => 'sh_control',
                    'default' => 'layout1',
                    'choices' => [
                        'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop_layout1.jpg',
                    ],
                ],
                'shop_control_categories'       => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Categories Carousel', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => false,
                ],
                'shop_categories_position'       => [
                    'type' => 'radio-buttonset',
                    'label'   =>esc_html__( 'Show Categories On', 'cenos' ),
                    'section' => 'sh_control',
                    'default' => 'control',
                    'choices' => [
                        'control' => esc_attr__( 'Shop Control', 'cenos' ),
                        'heading'    => esc_attr__( 'Shop Heading', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_control_use_tabs'         => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Enable Products Tabs', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => false,
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],
                ],
                'shop_control_product_tabs'     => [
                    'type' => 'radio-buttonset',
                    'label'   => esc_html__( 'Tabs', 'cenos' ),
                    'section' => 'sh_control',
                    'default' => 'group',
                    'choices' => [
                        'group'    => esc_attr__( 'Groups', 'cenos' ),
                        'category' => esc_attr__( 'Categories', 'cenos' ),
                        'tag'      => esc_attr__( 'Tags', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_use_tabs',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],
                ],
                'shop_control_category_items'   => [
                    'type'            => 'text',
                    'label'   => esc_html__( 'Product categories', 'cenos' ),
                    'description'     => esc_html__( 'Enter category names, separate by commas. Leave empty to get all categories. Enter a number to get limit number of top categories.', 'cenos' ),
                    'default'         => 3,
                    'section' => 'sh_control',
                    'active_callback' => [
                        [
                            [
                                [
                                    'setting'  => 'shop_control_categories',
                                    'operator' => '==',
                                    'value'    => true,
                                ]
                            ],
                            [
                                [
                                    'setting'  => 'shop_control_categories',
                                    'operator' => '==',
                                    'value'    => false,
                                ],
                                [
                                    'setting'  => 'shop_control_product_tabs',
                                    'operator' => '==',
                                    'value'    => 'category',
                                ],
                            ],
                        ],
                    ]
                ],
                'shop_control_tag_items'        => [
                    'type'            => 'text',
                    'label'   => esc_html__( 'Product Tags', 'cenos' ),
                    'description'     => esc_html__( 'Enter tag names, separate by commas. Leave empty to get all tags. Enter a number to get limit number of top tags.', 'cenos' ),
                    'default'         => 3,
                    'section' => 'sh_control',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_control_use_tabs',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_control_product_tabs',
                            'operator' => '==',
                            'value'    => 'tag',
                        ]
                    ]
                ],
                'shop_control_sub_category'     => [
                    'type'            => 'toggle',
                    'label'           => esc_html__( 'Replace by sub-categories', 'cenos' ),
                    'default'         => false,
                    'section' => 'sh_control',
                    'active_callback' => [
                        [
                            [
                                [
                                    'setting'  => 'shop_control_categories',
                                    'operator' => '==',
                                    'value'    => true,
                                ]
                            ],
                            [
                                [
                                    'setting'  => 'shop_control_categories',
                                    'operator' => '==',
                                    'value'    => false,
                                ],
                                [
                                    'setting'  => 'shop_control_product_tabs',
                                    'operator' => '==',
                                    'value'    => 'category',
                                ],
                            ],
                        ],
                    ]
                ],
                'shop_control_tabs_groups'      => [
                    'type'            => 'multicheck',
                    'default'         => [ 'best_sellers', 'new', 'sale' ],
                    'section' => 'sh_control',
                    'choices'         =>[
                        'best_sellers' => esc_attr__( 'Best Sellers', 'cenos' ),
                        'featured'     => esc_attr__( 'Hot Products', 'cenos' ),
                        'new'          => esc_attr__( 'New Products', 'cenos' ),
                        'sale'         => esc_attr__( 'Sale Products', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => false,
                        ],
                        [
                            'setting'  => 'shop_control_use_tabs',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_control_product_tabs',
                            'operator' => '==',
                            'value'    => 'group',
                        ],
                    ],
                ],
                'shop_categories_display'     => [
                    'type' => 'radio-buttonset',
                    'label'   =>esc_html__( 'Categories Position', 'cenos' ),
                    'section' => 'sh_control',
                    'default' => 'shop_control',
                    'choices' => [
                        'shop_control'    =>esc_html__( 'Shop Control', 'cenos' ),
                        'shop_heading' =>esc_html__( 'Shop Heading', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_use_tabs',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_control_categories',
                            'operator' => '==',
                            'value'    => false,
                        ],
                    ],
                ],

                'shop_control_quick_search'     => [
                    'type'        => 'toggle',
                    'label'       =>esc_html__( 'Quick search', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => false,
                ],
                'shop_control_woo_short'        => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'WooCommerce Sort', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => true,
                ],
                'shop_control_woo_result_count' => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'WooCommerce Result Count', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => true,
                ],

                'shop_control_filter'           => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Enable Products Filter', 'cenos' ),
                    'section'     => 'sh_control',
                    'default'     => true,
                ],
                'shop_control_filter_style'     => [
                    'type' => 'radio-buttonset',
                    'label'   => esc_html__( 'Products Filter Style', 'cenos' ),
                    'section' => 'sh_control',
                    'default' => 'dropdown',
                    'choices' => [
                        'dropdown'    => esc_attr__( 'Dropdown', 'cenos' ),
                        'off_canvas' => esc_attr__( 'Off Canvas', 'cenos' ),
                        'sidebar' => esc_attr__( 'Sidebar', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_filter',
                            'operator' => '==',
                            'value'    => true,
                        ]
                    ],
                ],
                'shop_control_filter_pos'     => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Filter Content Position', 'cenos'),
                    'section' => 'sh_control',
                    'default' => 'right',
                    'choices' => [
                        'left' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout1.jpg',
                        'right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/menu/layout2.jpg',
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_filter',
                            'operator' => '==',
                            'value'    => true,
                        ],
                        [
                            'setting'  => 'shop_control_filter_style',
                            'operator' => 'in',
                            'value'    => ['off_canvas','sidebar'],
                        ],
                    ],
                ],
                'shop_control_filter_text'     => [
                    'type' => 'text',
                    'section'     => 'sh_control',
                    'label' => esc_html__( 'Filter Title', 'cenos' ),
                    'default'   => 'Filter',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_control_filter',
                            'operator' => '==',
                            'value'    => true,
                        ],
                    ],
                ],
                //product catalog
                'shop_setting_group_hd1' => [
                    'type'        => 'custom',
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => '<div class="group-heading-control">'.__( 'Shop Settings', 'cenos' ).'</div>',
                    'priority'    =>  11,
                ],
                'shop_setting_breadcrumb'         => [
                    'type' => 'radio-image',
                    'label'   => esc_html__( 'WooCommerce Breadcrumb', 'cenos' ),
                    'section' => 'woocommerce_product_catalog',
                    'default' => 'in',
                    'priority'    =>  11,
                    'choices' => [
                        'none'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_none.jpg',
                        'in' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_in.jpg',
                        'out' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_out.jpg',
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_heading_layout',
                            'operator' => '!=',
                            'value'    => 'simple',
                        ],
                    ],
                ],
                'shop_page_layout' => [
                    'type' => 'select',
                    'label' => esc_html__('Shop layout', 'cenos'),
                    'section' => 'woocommerce_product_catalog',
                    'default' => 'default',
                    'priority'    =>  11,
                    'choices' => [
                        'default' => esc_html__( 'Default', 'cenos' ),
                        'background' => esc_html__( 'Background', 'cenos' ),
                        'full-width' =>esc_html__( 'Full Width', 'cenos' ),
                    ],
                ],
                'shop_page_background' => [
                    'type'        => 'background',
                    'section'     => 'woocommerce_product_catalog',
                    'priority'    =>  11,
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
                            'setting'  => 'shop_page_layout',
                            'operator' => '==',
                            'value'    => 'background',
                        ],
                    ],
                ],
                'shop_skip_divider_1' => [
                    'type'        => 'custom',
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => '<div><hr/></div>',
                    'priority'    =>  11,
                ],
                'shop_products_per_page'   => [
                    'label'       => esc_html__( 'Products per page', 'cenos' ),
                    'type'        => 'number',
                    'description' => esc_html__('Number of products per page','cenos'),
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => 12,
                    'priority'    =>  11,
                    'choices'     => [
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1,
                    ],

                ],
                'shop_columns'   => [
                    'label'       => esc_html__( 'Products per row', 'cenos' ),
                    'type'        => 'slider',
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => 4,
                    'priority'    =>  11,
                    'choices'     => [
                        'min'  => 1,
                        'max'  => 6,
                        'step' => 1,
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => '==',
                            'value'    => 'grid',
                        ],
                    ],
                ],
                'shop_columns_tablet'   => [
                    'label'       => esc_html__( 'Products per row on Tablet', 'cenos' ),
                    'type'        => 'slider',
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => 3,
                    'priority'    =>  11,
                    'choices'     => [
                        'min'  => 1,
                        'max'  => 4,
                        'step' => 1,
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => '==',
                            'value'    => 'grid',
                        ],
                    ],
                ],
                'shop_columns_mobile'   => [
                    'label'       => esc_html__( 'Products per row on Mobile', 'cenos' ),
                    'type'        => 'slider',
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => 2,
                    'priority'    =>  11,
                    'choices'     => [
                        'min'  => 1,
                        'max'  => 2,
                        'step' => 1,
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => '==',
                            'value'    => 'grid',
                        ],
                    ],
                ],

                'shop_list_style' => [
                    'type' => 'radio-image',
                    'priority'    =>  11,
                    'label' => esc_html__('List Style', 'cenos'),
                    'section' => 'woocommerce_product_catalog',
                    'default' => 'grid',
                    'choices' => [
                        'grid' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/products_grid.jpg',
                        'list' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/products_list.jpg',
                        'list2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/products_list_full.jpg',
                    ],
                ],
                'shop_group_hd2' => [
                    'type'        => 'custom',
                    'priority'    =>  11,
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => '<div class="group-heading-control">'.__( 'Product Item', 'cenos' ).'</div>',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => 'in',
                            'value' => ['grid'],
                        ],
                    ]
                ],
                'product_item_style' => [
                    'type' => 'radio-image',
                    'label' => esc_html__('Style', 'cenos'),
                    'section' => 'woocommerce_product_catalog',
                    'default' => 'style-1',
                    'priority'    =>  11,
                    'choices' => [
                        'slider' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/slider.jpg',
                        'border' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/border.jpg',
                        'style-1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style1.jpg',
                        'style-2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style2.jpg',
                        'style-3' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style3.jpg',
                        'style-4' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style4.jpg',
                        'style-5' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style5.jpg',
                        'style-6' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style6.jpg',
                        'style-7' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style7.jpg',
                        'style-clean' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product-items/style_clean.jpg',
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => 'in',
                            'value' => ['grid'],
                        ],
                    ]
                ],
                'product_item_hover_image' => [
                    'type' => 'select',
                    'label' => esc_html__('Product Image Hover', 'cenos'),
                    'section' => 'woocommerce_product_catalog',
                    'default' => 'default',
                    'priority'    =>  11,
                    'choices' => [
                        'default' => esc_html__('Default', 'cenos'),
                        'second' => esc_html__('Second Image', 'cenos'),
                        'zoom' => esc_html__('Zoom', 'cenos'),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_list_style',
                            'operator' => 'in',
                            'value' => ['grid'],
                        ],
                        [
                            'setting'  => 'product_item_style',
                            'operator' => 'in',
                            'value' => ['style-1','style-2','style-3','border','style-4','style-5','style-6',],
                        ],
                    ]
                ],
                'product_item_category'          => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Show Category', 'cenos' ),
                    'section' => 'woocommerce_product_catalog',
                    'default'     => true,
                    'priority'    =>  11,
                    'active_callback' => [
                        [
                            'setting'  => 'product_item_style',
                            'operator' => '!=',
                            'value' => 'default',
                        ],
                    ]
                ],
                'product_item_rating'          => [
                    'type'        => 'toggle',
                    'label'       => esc_html__( 'Show Stars Rating', 'cenos' ),
                    'section' => 'woocommerce_product_catalog',
                    'default'     => true,
                    'priority'    =>  11,
                    'active_callback' => [
                        [
                            'setting'  => 'product_item_style',
                            'operator' => '!=',
                            'value' => 'default',
                        ],
                    ]
                ],
                //Stars Rating
                // badges
                'shop_badges' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Badges', 'cenos' ),
                    'section'     => 'sh_badges',
                    'default'     => ['hot','new','sale'],
                    'choices'     => [
                        'hot' => esc_html__( 'Hot (Featured Products)', 'cenos' ),
                        'new' => esc_html__( 'New', 'cenos' ),
                        'sale' => esc_html__( 'Sale', 'cenos' ),
                        'sold_out' => esc_html__( 'Sold Out', 'cenos' ),
                    ],
                ],
                'shop_badge_skip_divider_0' => [
                    'type'        => 'custom',
                    'section'     => 'sh_badges',
                    'default'     => '<div class="fm_customize_divide"><hr/></div>',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sale',
                        ],
                    ],
                ],
                'shop_badge_sale_type'     => [
                    'type'            => 'radio',
                    'label'           => esc_html__( 'Sale Badge Type', 'cenos' ),
                    'section' => 'sh_badges',
                    'default'         => 'percent',
                    'choices'         => [
                        'percent' => esc_html__( 'Percentage', 'cenos' ),
                        'text'    => esc_html__( 'Text', 'cenos' ),
                    ],
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sale',
                        ],
                    ],
                ],
                'shop_badge_sale_text'     => [
                    'type'            => 'text',
                    'section' => 'sh_badges',
                    'label'           => esc_html__( 'Sale Badge Text', 'cenos' ),
                    'tooltip'         => esc_html__( 'Use {%} to display discount percentages', 'cenos' ),
                    'default'         => esc_html__( 'Sale!', 'cenos' ),
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sale',
                        ],
                        [
                            'setting'  => 'shop_badge_sale_type',
                            'operator' => '=',
                            'value'    => 'text',
                        ],
                    ],
                ],
                'shop_badge_sale_color'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Sale Badge Text Color', 'cenos' ),
                    'section' => 'sh_badges',
                    'default'         => '#ffffff',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sale',
                        ],
                    ],
                ],
                'shop_badge_sale_bg'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Sale Badge Background', 'cenos' ),
                    'section' => 'sh_badges',
                    'default'         => '#55a61d',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sale',
                        ],
                    ],
                ],
                //new
                'shop_badge_skip_divider_1' => [
                    'type'        => 'custom',
                    'section'     => 'sh_badges',
                    'default'     => '<div class="fm_customize_divide"><hr/></div>',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'new',
                        ],
                    ],
                ],
                'shop_badge_new_text'      => [
                    'type'            => 'text',
                    'label'           => esc_html__( 'New Badge Text', 'cenos' ),
                    'section'     => 'sh_badges',
                    'default'         => esc_attr__( 'New', 'cenos' ),
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'new',
                        ],
                    ],
                ],
                'shop_badge_newness'       => [
                    'type'            => 'number',
                    'description'     => esc_html__( 'Number of days for which the product is considered \'new\'', 'cenos' ),
                    'section'     => 'sh_badges',
                    'default'         => 7,
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'new',
                        ],
                    ],
                ],
                'shop_badge_new_color'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'New Badge Text Color', 'cenos' ),
                    'default'         => '#666666',
                    'section'         => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'new',
                        ],
                    ],
                ],
                'shop_badge_new_bg'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'New Badge Background', 'cenos' ),
                    'default'         => '#ff9331',
                    'section'         => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'new',
                        ],
                    ],
                ],
                //hot
                'shop_badge_skip_divider_2' => [
                    'type'        => 'custom',
                    'section'     => 'sh_badges',
                    'default'     => '<div class="fm_customize_divide"><hr/></div>',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'hot',
                        ],
                    ],
                ],
                'shop_badge_hot_text' => [
                    'type'            => 'text',
                    'label'           => esc_html__( 'Featured Badge Text', 'cenos' ),
                    'default'         => esc_attr__( 'Hot', 'cenos' ),
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'hot',
                        ],
                    ],
                ],
                'shop_badge_hot_color'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Hot Badge Text Color', 'cenos' ),
                    'default'         => '#ffffff',
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'hot',
                        ],
                    ],
                ],
                'shop_badge_hot_bg'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Hot Badge Background', 'cenos' ),
                    'default'         => '#fe4819',
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'hot',
                        ],
                    ],
                ],
                //Sold Out
                'shop_badge_skip_divider_3' => [
                    'type'        => 'custom',
                    'section'     => 'sh_badges',
                    'default'     => '<div class="fm_customize_divide"><hr/></div>',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sold_out',
                        ],
                    ],
                ],
                'shop_badge_sold_out_text' => [
                    'type'            => 'text',
                    'label'           => esc_html__( 'Sold Out Badge Text', 'cenos' ),
                    'default'         => esc_attr__( 'Sold Out', 'cenos' ),
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sold_out',
                        ],
                    ],
                ],
                'shop_badge_sold_out_color'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Sold Out Badge Text Color', 'cenos' ),
                    'default'         => '#fb1c1c',
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sold_out',
                        ],
                    ],
                ],
                'shop_badge_sold_out_bg'  => [
                    'type'            => 'color',
                    'label'           => esc_html__( 'Sold Out Badge Background', 'cenos' ),
                    'default'         => '#8e908f',
                    'section'     => 'sh_badges',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_badges',
                            'operator' => 'contains',
                            'value'    => 'sold_out',
                        ],
                    ],
                ],
                //notice
                'shop_added_to_cart_notice' => [
                    'type'            => 'toggle',
                    'label'           => esc_html__( 'Added to Cart Notification', 'cenos' ),
                    'description'     => esc_html__( 'Display a message when a product was added to cart.', 'cenos' ),
                    'default'         => false,
                    'section'     => 'woo_notice',
                ],
                'shop_added_to_cart_message' => [
                    'type'            => 'text',
                    'description'     => esc_html__( 'Message', 'cenos' ),
                    'default'         => esc_attr__( 'was added to cart successfully', 'cenos' ),
                    'section'     => 'woo_notice',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_added_to_cart_notice',
                            'operator' => '=',
                            'value'    => true,
                        ],
                    ],
                ],
                'shop_cart_notice_name' => [
                    'type'            => 'toggle',
                    'label'           => esc_html__( 'Show product title', 'cenos' ),
                    'description'     => esc_html__( 'Display product title on Notification.', 'cenos' ),
                    'default'         => true,
                    'section'     => 'woo_notice',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_added_to_cart_notice',
                            'operator' => '=',
                            'value'    => true,
                        ],
                    ],
                ],
                'shop_cart_notice_image' => [
                    'type'            => 'toggle',
                    'label'           => esc_html__( 'Show product image', 'cenos' ),
                    'description'     => esc_html__( 'Display product image on Notification.', 'cenos' ),
                    'default'         => true,
                    'section'     => 'woo_notice',
                    'active_callback' => [
                        [
                            'setting'  => 'shop_added_to_cart_notice',
                            'operator' => '=',
                            'value'    => true,
                        ],
                    ],
                ],
            ];

            if (cenos_woof_ajax_pagination()) {
                $this->setting['woo_pagination_type'] = [
                    'type'        => 'radio-buttonset',
                    'label'       => esc_html__( 'Filter Pagination Type', 'cenos' ),
                    'section'     => 'woocommerce_product_catalog',
                    'default'     => 'numeric',
                    'choices'     => [
                        'numeric'   => esc_html__( 'Numeric', 'cenos' ),
                        'load' => esc_html__( 'Load More', 'cenos' ),
                        'scroll' => esc_html__( 'Infinite Scroll', 'cenos' ),
                    ]
                ];
            }
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Shop_Fields');
