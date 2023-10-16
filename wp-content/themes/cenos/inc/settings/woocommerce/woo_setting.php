<?php

//woocommerce_checkout

class Woo_Setting extends Settings {
    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {
        $shop_sections = [
            'sh_header'     => [
                'title'     => esc_html__( 'Shop Page Heading', 'cenos' ),
                'priority'  => '10',
                'panel'     => 'woocommerce',
            ],
            'sh_control'    => [
                'title'     => esc_html__( 'Shop Control', 'cenos' ),
                'priority'  => '10',
                'panel'     => 'woocommerce',
            ],
            'woo_single'    => [
                'title'     => esc_html__( 'Single Product', 'cenos' ),
                'priority'  => '10',
                'panel'     => 'woocommerce',
            ],
            'sh_badges'    => [
                'title'     => esc_html__( 'Badges', 'cenos' ),
                'priority'  => '10',
                'panel'     => 'woocommerce',
            ],
            'woo_notice'  => [
                'title'     => esc_html__( 'Notice', 'cenos' ),
                'priority'  => '10',
                'panel'     => 'woocommerce',
            ],
        ];
        return array_merge($sections,$shop_sections);
    }

    public function getFields($fields)
    {
        //shop_control
        $woo_fields = [
            //single product setting
            'woo_single_layout'             => [
                'type' => 'radio-image',
                'label' => esc_html__('Product Layout', 'cenos'),
                'section' => 'woo_single',
                'default' => 'no-sidebar',
                'choices' => [
                    'no-sidebar' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout1.jpg',
                    'sidebar-left' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout2.jpg',
                    'sidebar-right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout3.jpg',
                    'sidebar-left-full' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout4.jpg',
                    'sidebar-right-full' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout5.jpg',
                    'wide-gallery' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout6.jpg',
                    'sticky' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_layout7.jpg',
                ]
            ],
            'woo_single_breadcrumb'         => [
                'type' => 'radio-image',
                'label'   => esc_html__( 'WooCommerce Breadcrumb', 'cenos' ),
                'section' => 'woo_single',
                'default' => 'top',
                'choices' => [
                    'none'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_breadcrumb_none.jpg',
                    'top' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_breadcrumb_top.jpg',
                    'summary' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_breadcrumb_summary.jpg',
                ],
            ],
            'woo_single_product_nav'        => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Product Navigation', 'cenos' ),
                'section'     => 'woo_single',
                'description' => esc_html__( 'Display next & previous links on top of product page', 'cenos' ),
                'default'     => false,
            ],
            'woo_single_product_nav_same_cat' => [
                'type'        => 'checkbox',
                'label'       => esc_html__( 'Navigate to products in the same category', 'cenos' ),
                'default'     => false,
                'section'     => 'woo_single',
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_product_nav',
                        'operator' => '==',
                        'value'    => true,
                    ]
                ],
            ],
            'woo_single_product_background' => [
                'type'        => 'background',
                'label'       => esc_html__( 'Product Background', 'cenos' ),
                'section'     => 'woo_single',
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
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right'],
                    ],
                ],
            ],
            'woo_single_gallery_group_hd' => [
                'type'        => 'custom',
                'section'     => 'woo_single',
                'default'     => '<div class="group-heading-control">'.__( 'Gallery', 'cenos' ).'</div>',
            ],
            'woo_single_image_style'         => [
                'type' => 'radio-image',
                'label'   => esc_html__( 'Product Image Style', 'cenos' ),
                'section' => 'woo_single',
                'default' => 'default',
                'choices' => [
                    'default'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_horizontal.jpg',
                    'vertical' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_vertical.jpg',
                    'slider' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_slider.jpg',
                    'grid' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_gallery.jpg',
                    'grid2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_gallery.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ]
                ],
            ],
            'woo_single_image_style_for_sticky'         => [
                'type' => 'radio-image',
                'label'   => esc_html__( 'Product Image Style', 'cenos' ),
                'section' => 'woo_single',
                'default' => 'list',
                'choices' => [
                    'grid' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_gallery.jpg',
                    'grid2' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_gallery.jpg',
                    'list' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/product_layout/product_image_list.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => '==',
                        'value'    => 'sticky',
                    ]
                ],
            ],
            'woo_single_image_width'         => [
                'type' => 'select',
                'label'   =>esc_html__( 'Product Image Width', 'cenos' ),
                'section' => 'woo_single',
                'default' => 'medium',
                'choices'         => array(
                    'large' =>esc_html__( 'Large', 'cenos' ),
                    'medium' =>esc_html__( 'Medium', 'cenos' ),
                    'small' =>esc_html__( 'Small', 'cenos' ),
                ),
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => '!=',
                        'value'    => 'wide-gallery',
                    ]
                ],
            ],
            'woo_single_thumb_item_desktop'     => array(
                'type'    => 'slider',
                'label'   => esc_html__( 'Number Thumbs on Desktop', 'cenos' ),
                'default' => 5,
                'section' => 'woo_single',
                'choices'         => array(
                    'min' => 1,
                    'max' => 10,
                ),
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['vertical']
                    ],
                ]
            ),
            'woo_single_thumb_height_desktop' => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Thumbnails Height on Desktop', 'cenos' ),
                'section'     => 'woo_single',
                'default'     => '615',
                'choices'     => [
                    'accept_unitless' => false,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['vertical']
                    ],
                ]
            ],
            'woo_single_thumb_item_tablet'     => array(
                'type'    => 'slider',
                'label'   => esc_html__( 'Number Thumbs on Tablet', 'cenos' ),
                'default' => 4,
                'section' => 'woo_single',
                'choices'         => array(
                    'min' => 1,
                    'max' => 10,
                ),
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['vertical'],
                    ]
                ]
            ),
            'woo_single_thumb_height_tablet' => [
                'type'        => 'dimension',
                'label'       =>esc_html__( 'Thumbnails Height on Tablet', 'cenos' ),
                'section'     => 'woo_single',
                'default'     => '490',
                'choices'     => [
                    'accept_unitless' => false,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['vertical'],
                    ]
                ]
            ],
            'woo_single_image_zoom'        => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Image Zoom', 'cenos' ),
                'section'     => 'woo_single',
                'description' => esc_html__( 'Show a zoomed version of image when hovering gallery', 'cenos' ),
                'default'     => false,
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['default','vertical','slider'],
                    ]
                ],
            ],
            'woo_single_image_zoom_mobile'        => [
                'type'        => 'toggle',
                'label'       =>esc_html__( 'Image Zoom On Mobile', 'cenos' ),
                'section'     => 'woo_single',
                'default'     => false,
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_zoom',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => 'in',
                        'value'    => ['default','vertical','slider'],
                    ]
                ],
            ],
            'woo_single_image_lightbox'        => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Image Lightbox', 'cenos' ),
                'section'     => 'woo_single',
                'description' => esc_html__( 'Show images in a lightbox when clicking on image in gallery.', 'cenos' ),
                'default'     => false,
            ],

            'woo_single_image_360'        => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Images 360 Degree', 'cenos' ),
                'section'     => 'woo_single',
                'default'     => false,
            ],

            'woo_single_summary_group_hd' => [
                'type'        => 'custom',
                'section'     => 'woo_single',
                'default'     => '<div class="group-heading-control">'.__( 'Summary', 'cenos' ).'</div>',
            ],
            'woo_single_summary_align' => [
                'type' => 'radio-buttonset',
                'label' => esc_html__('Text Align', 'cenos'),
                'section' => 'woo_single',
                'default' => 'left',
                'choices' => [
                    'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                    'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                    'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                ]
            ],
            'woo_single_show_desc'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Show Short Description', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_single_show_sku'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Show SKU', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_single_show_cat'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Show Categories', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_single_show_tag'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Show Tags', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_add_to_cart_icon'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Button Add To Cart Icon', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_add_to_cart_text'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Button Add To Cart Text', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_single_show_quantity'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Show Quantity', 'cenos'),
                'default'   => true,
                'section'   => 'woo_single',
            ],
            'woo_product_share'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Product Sharing', 'cenos'),
                'default'   => false,
                'section'   => 'woo_single',
            ],
            'woo_product_share_items' => [
                'type'        => 'sortable',
                'description'     => esc_html__( 'Select social media for sharing product', 'cenos' ),
                'section'     => 'woo_single',
                'default'     => [],
                'choices'     => [
                    'facebook'   => esc_html__( 'Facebook', 'cenos' ),
                    'twitter'    => esc_html__( 'Twitter', 'cenos' ),
                    'googleplus' => esc_html__( 'Google Plus', 'cenos' ),
                    'pinterest'  => esc_html__( 'Pinterest', 'cenos' ),
                    'linkedin'  => esc_html__( 'Linkedin', 'cenos' ),
                    'tumblr'     => esc_html__( 'Tumblr', 'cenos' ),
                    'telegram'   => esc_html__( 'Telegram', 'cenos' ),
                    'email'      => esc_html__( 'Email', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'woo_product_share',
                        'operator' => '==',
                        'value'    => true,
                    ]
                ],
            ],
            'woo_single_tabs_group_hd' => [
                'type'        => 'custom',
                'section'     => 'woo_single',
                'default'     => '<div class="group-heading-control">'.__( 'Tabs', 'cenos' ).'</div>',
            ],
            'woo_single_tabs_style'     => [
                'type' => 'select',
                'label'   => esc_html__( 'Product Tabs Style', 'cenos' ),
                'section' => 'woo_single',
                'default' => 'default',
                'choices' => [
                    'default'    => esc_html__( 'Default', 'cenos' ),
                    'accordion' => esc_html__( 'Accordion', 'cenos' ),
                    'vertical' => esc_html__( 'Vertical', 'cenos' ),
                    'sections' => esc_html__( 'Sections', 'cenos' ),
                ],
            ],
            'woo_single_video_group_hd' => [
                'type'        => 'custom',
                'section'     => 'woo_single',
                'default'     => '<div class="group-heading-control">'.__( 'Video Options', 'cenos' ).'</div>',
            ],
            'woo_product_video_lightbox'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Lightbox', 'cenos'),
                'default'   => false,
                'section'   => 'woo_single',
                'active_callback' => [
                    [
                        'setting'  => 'woo_single_layout',
                        'operator' => 'in',
                        'value'    => ['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'],
                    ],
                    [
                        'setting'  => 'woo_single_image_style',
                        'operator' => '==',
                        'value'    => 'slide'
                    ]
                ],
            ],
            'woo_product_video_control'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Player Controls', 'cenos'),
                'default'   => false,
                'section'   => 'woo_single',
            ],
            'woo_product_video_loop'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Loop', 'cenos'),
                'default'   => false,
                'section'   => 'woo_single',
            ],
            'woo_product_video_mute'      => [
                'type'      => 'toggle',
                'label'     => esc_html__('Mute', 'cenos'),
                'default'   => false,
                'section'   => 'woo_single',
            ],
        ];
        $shop_field = require_once 'shop_fields.php';
        return array_merge($fields,$woo_fields,$shop_field);
    }
}
new Woo_Setting();
