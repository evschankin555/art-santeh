<?php
add_filter( 'rwmb_meta_boxes', 'cenos_register_meta_boxes' );
function cenos_register_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      => 'Customize Page Options',
        'post_types' => 'page',
        'tabs'      => array(
            'header'    => array(
                'label' => 'Header',
                'icon'  => 'dashicons-schedule', // Custom icon, using image
            ),
            'layout' => array(
                'label' => 'Layout',
                'icon'  => 'dashicons-layout', // Dashicon
            ),
            'heading'  => array(
                'label' => 'Heading',
                'icon'  => 'dashicons-welcome-widgets-menus', // Dashicon
            ),
        ),

        // Tab style: 'default', 'box' or 'left'. Optional
        'tab_style' => 'default',
        // Show meta box wrapper around tabs? true (default) or false. Optional
        'tab_wrapper' => true,

        'fields' => [
            //Header
            [
                'name' => esc_html__( 'Enable Own Page Header', 'cenos' ),
                'id'   => 'page_header_option',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'header',
            ],
            [
                'name'          => esc_html__( 'Logo', 'cenos' ),
                'id'            => 'logo',
                'type'          => 'image_advanced',
                'max_file_uploads' => 1,
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Header container width', 'cenos' ),
                'id'          => 'header_dimensions',
                'type'        => 'text',
                'std'  => '',
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Background Color', 'cenos' ),
                'id'            => 'header_bg_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Text Color', 'cenos' ),
                'id'            => 'header_color',
                'type'          => 'color',
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Text Hover Color', 'cenos' ),
                'id'            => 'header_hover_color',
                'type'          => 'color',
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],

            [
                'id'       => 'header_divider',
                'name'     => esc_html__('Header Divider', 'cenos'),
                'type'     => 'button_group',
                'std'      => 'none',
                'options'  => [
                    'none' => esc_html__('None', 'cenos'),
                    'shadow' => esc_html__('Box shadow', 'cenos'),
                    'border' => esc_html__('Border', 'cenos'),
                ],
                'inline'   => true,
                'tab'  => 'header',
                'visible'   => ['page_header_option', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Divider Color', 'cenos' ),
                'id'            => 'header_divider_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => [
                    ['page_header_option', '=', 1],
                    ['header_divider', '=', 'border'],
                ]
            ],
            [
                'type' => 'divider',
                'tab'  => 'header',
            ],
            [
                'name' => esc_html__( 'Own Transparent Header Settings', 'cenos' ),
                'id'   => 'page_header_transparent',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'header',
            ],
            [
                'name' => esc_html__( 'Enable Own Settings', 'cenos' ),
                'id'   => 'page_header_transparent_bg',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'header',
                'visible'   => ['page_header_transparent', '=', 1 ],
            ],
            [
                'name'          => esc_html__( 'Background Color', 'cenos' ),
                'id'            => 'page_header_transparent_bg_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => [
                    ['page_header_transparent', '=', 1],
                    ['page_header_transparent_bg', '=', 1],
                ]
            ],
            [
                'name'          => esc_html__( 'Text Color', 'cenos' ),
                'id'            => 'page_header_transparent_text_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => [
                    ['page_header_transparent', '=', 1],
                    ['page_header_transparent_bg', '=', 1],
                ]
            ],
            [
                'name'          => esc_html__( 'Text Hover Color', 'cenos' ),
                'id'            => 'page_header_transparent_text_hover_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => [
                    ['page_header_transparent', '=', 1],
                    ['page_header_transparent_bg', '=', 1],
                ]
            ],
            [
                'type' => 'divider',
                'tab'  => 'header',
            ],

            [
                'name' => esc_html__( 'Own Topbar Setting', 'cenos' ),
                'id'   => 'page_topbar_background_setting',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'header',
            ],
            [
                'id'   => 'page_topbar_background',
                'name' => esc_html__( 'Topbar Background', 'cenos' ),
                'type' => 'background',
                'tab'  => 'header',
                'visible'   => [
                    ['page_topbar_background_setting', '=', 1]
                ],
            ],
            [
                'id'       => 'page_topbar_color',
                'name'     => esc_html__('Topbar Color', 'cenos'),
                'type'     => 'button_group',
                'std'      => 'default',
                'options'  => [
                    'default' => esc_html__('Default', 'cenos'),
                    'dark' => esc_html__('Dark', 'cenos'),
                    'light' => esc_html__('Light', 'cenos'),
                ],
                'inline'   => true,
                'tab'  => 'header',
            ],
            [
                'name' => esc_html__( 'Own Topbar Divider', 'cenos' ),
                'id'   => 'page_topbar_divider_setting',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'header',
            ],
            [
                'name'          => esc_html__( 'Divider Color', 'cenos' ),
                'id'            => 'page_topbar_divider_color',
                'type'          => 'color',
                'alpha_channel' => true,
                'tab'  => 'header',
                'visible'   => [
                    ['page_topbar_divider_setting', '=', 1]
                ]
            ],

            //page layout
            [
                'name' => esc_html__( 'Own Page Layout Settings', 'cenos' ),
                'id'   => 'page_layout_option',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'layout',
            ],
            [
                'name'              => esc_html__( 'Sidebar Layout', 'cenos' ),
                'id'                => 'page_layout',
                'type'              => 'image_select',
                'options'           => [
                    'none'          => CENOS_TEMPLATE_DIRECTORY_URI.'assets/images/sidebar/full-width.jpg',
                    'sidebar-left'  => CENOS_TEMPLATE_DIRECTORY_URI.'assets/images/sidebar/sidebar-left.jpg',
                    'sidebar-right' => CENOS_TEMPLATE_DIRECTORY_URI.'assets/images/sidebar/sidebar-right.jpg',
                ],
                'std'           => 'none',
                'visible' => ['page_layout_option', '=', 1 ],
                'tab'  => 'layout',
            ],
            [
                'name'      => esc_html__( 'Sidebar for page layout', 'cenos' ),
                'id'        => 'page_used_sidebar',
                'type'      => 'select',
                'placeholder'     => 'Select an Item',
                'options'   => cenos_get_sidebars(),
                'tab'       => 'layout',
                'visible'   => [
                    ['page_layout_option', '=', 1],
                    ['page_layout', '!=', 'none'],
                ]
            ],
            [
                'type' => 'divider'
            ],
            [
                'name'    => 'Content Padding Top',
                'id'      => 'content_padding_top',
                'type'    => 'number',
                'min'  => 0,
                'step' => 1,
                'visible' => ['page_layout_option', '=', 1 ],
                'tab'       => 'layout',
                'desc'   => 'px',
            ],
            [
                'name'    => 'Content Padding Bottom',
                'id'      => 'content_padding_bottom',
                'type'    => 'number',
                'min'  => 0,
                'step' => 1,
                'visible' => ['page_layout_option', '=', 1 ],
                'tab'       => 'layout',
                'desc'   => 'px',
            ],
            //page heading
            [
                'name' => esc_html__( 'Own Page Heading Settings', 'cenos' ),
                'id'   => 'page_heading_option',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'heading',
            ],
            [
                'name' => esc_html__( 'Use Page Heading', 'cenos' ),
                'id'   => 'use_page_heading',
                'type' => 'switch',
                'std'  => 1, //If it's checked by default
                'tab'  => 'heading',
                'visible'   => ['page_heading_option', '=', 1 ],
            ],
            [
                'name'       => esc_html__( 'Select Block', 'cenos' ),
                'desc'       => esc_html__( 'You can replace the Page Heading with a Custom Block that you can edit in the Page Builder.', 'cenos' ),
                'id'         => 'page_heading_block',
                'type'       => 'select',
                'options'    => cenos_get_block_post_options(),
                'tab'  => 'heading',
                'visible'   => [
                    ['page_heading_option', '=', 1],
                    ['use_page_heading', '=', 1]
                ],
            ],
            //enable page_heading_layout layout if has more than one.
            [
                'name'              => esc_html__( 'Heading Layout', 'cenos' ),
                'id'                => 'page_heading_layout',
                'type'              => 'image_select',
                'options'           => [
                    'layout1'          => CENOS_TEMPLATE_DIRECTORY_URI.'assets/images/heading_layout1.jpg',
                ],
                'std'           => 'layout1',
                'class' => 'cenos_large_image_select',
                'tab'  => 'heading',
                'visible'   => [
                    ['page_heading_option', '=', 1],
                    ['use_page_heading', '=', 1]
                ],
                'hidden'    => ['page_heading_block', '>', 0 ],
            ],
            [
                'id'   => 'page_heading_background',
                'name' => esc_html__( 'Heading Background', 'cenos' ),
                'type' => 'background',
                'tab'  => 'heading',
                'visible'   => [
                    ['page_heading_option', '=', 1],
                    ['use_page_heading', '=', 1]
                ],
                'hidden'    => ['page_heading_block', '>', 0 ],
            ],
            [
                'name'          => esc_html__( 'Heading Color', 'cenos' ),
                'id'            => 'page_heading_color',
                'type'          => 'color',
                'tab'  => 'heading',
                'visible'   => [
                    ['page_heading_option', '=', 1],
                    ['use_page_heading', '=', 1]
                ],
                'hidden'    => ['page_heading_block', '>', 0 ],
            ],
            [
                'name'    => 'Blog Heading Height',
                'id'      => 'page_heading_height',
                'type'    => 'number',
                'min'  => 0,
                'step' => 1,
                'visible'   => [
                    'page_heading_option', '=', 1,
                    'use_page_heading', '=', 1
                ],
                'tab'       => 'heading',
                'desc'   => 'px',
            ],
            [
                'type' => 'divider',
                'tab'  => 'heading',
            ],
            [
                'name' => esc_html__( 'Own Breadcrumb Settings', 'cenos' ),
                'id'   => 'page_breadcrumb_setting',
                'type' => 'switch',
                'std'  => 0, //If it's checked by default
                'tab'  => 'heading',
            ],
            [
                'name'              => esc_html__( 'Breadcrumb', 'cenos' ),
                'id'                => 'page_breadcrumb',
                'type'              => 'image_select',
                'options'           => [
                    'none'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_none.jpg',
                    'in' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_in.jpg',
                    'out' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_out.jpg',
                ],
                'std'           => 'in',
                //'class' => 'cenos_large_image_select',
                'tab'  => 'heading',
                'visible'   => ['page_breadcrumb_setting', '=', 1 ],
            ],
        ]
    );

    return $meta_boxes;
}
