<?php
class Blog extends Settings
{

    public function getPanels($panels)
    {
        $panels['blog'] = [
            'priority' => '12',
            'title'    => esc_html__( 'Blog', 'cenos' ),
        ];
        return $panels;

    }

    public function getSections($sections)
    {
        $blog_sections = [
            'bl_header'     => [
                'title'     => esc_html__( 'Blog Heading', 'cenos' ),
                'panel'     => 'blog',
            ],
            'bl_archive'    => [
                'title'     => esc_html__( 'Blog Archive/Listing', 'cenos' ),
                'panel'     => 'blog',
            ],
            'bl_single_post'=> [
                'title'     => esc_html__( 'Blog Single Post', 'cenos' ),
                'panel'     => 'blog',
            ]
        ];
        return array_merge($sections,$blog_sections);
    }

    public function getFields($fields)
    {
        global $block_choices;
        $site_layout_fields = [
            'blog_heading'            => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Enable Blog Heading', 'cenos' ),
                'description' => esc_html__( 'Enable the blog heading on blog pages.', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => true,
            ],
            'blog_heading_mobile'            => [
                'type'        => 'toggle',
                'label'       =>esc_html__( 'Blog Heading On Mobile', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => true,
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'blog_heading_display'    => [
                'type'            => 'multicheck',
                'label'           => esc_html__( 'Blog Heading Display', 'cenos' ),
                'description'     => esc_html__( 'Select pages you want to display blog heading', 'cenos' ),
                'section'     => 'bl_header',
                'default'         => ['blog','list'],
                'choices'         => [
                    'blog' => esc_html__( 'Blog', 'cenos' ),
                    'list' => esc_html__( 'Archive/Listing', 'cenos' ),
                    'post' => esc_html__( 'Single post', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'blog_heading_post'      => [
                'type'        => 'select',
                'label'       => esc_html__( 'Blog Header Block', 'cenos' ),
                'description'        => esc_html__( 'You can replace the Page Heading with a Custom Block that you can edit in the Page Builder.', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => '',
                'choices'     => $block_choices,
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'blog_heading_layout'      => [
                'type' => 'radio-image',
                'label' => esc_html__('Blog Heading Layout', 'cenos'),
                'section' => 'bl_header',
                'default' => 'layout1',
                'choices' => [
                    'layout1' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/heading_layout1.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
            ],
            'blog_breadcrumb'         => [
                'type' => 'radio-image',
                'label'   => esc_html__( 'Breadcrumb', 'cenos' ),
                'section' => 'bl_header',
                'default' => 'in',
                'choices' => [
                    'none'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_none.jpg',
                    'in' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_in.jpg',
                    'out' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/shop/bread_out.jpg',
                ],
            ],
            'blog_heading_width'      => [
                'type'        => 'radio-buttonset',
                'label'       => esc_html__( 'Blog Heading Width', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => 'full',
                'choices'     => [
                    'full'   => esc_html__( 'Full Width', 'cenos' ),
                    'container' => esc_html__( 'Site container', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
            ],
            'blog_heading_height'     => [
                'type'        => 'dimension',
                'label'       => esc_html__( 'Blog Heading Height', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => '120px',
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
                'choices'     => [
                    'accept_unitless' => false,
                ],
            ],
            'blog_heading_bg'         => [
                'type'        => 'background',
                'label'       => esc_html__( 'Blog Heading Background', 'cenos' ),
                'section'     => 'bl_header',
                'default'     => [
                    'background-color'      => '#f8f8f8',
                    'background-image'      => '',
                    'background-repeat'     => 'repeat',
                    'background-position'   => 'center center',
                    'background-size'       => 'cover',
                    'background-attachment' => 'scroll',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
            ],
            'blog_heading_align'      => [
                'type' => 'radio-buttonset',
                'label' => esc_html__('Text align', 'cenos'),
                'section' => 'bl_header',
                'default' => 'left',
                'choices' => [
                    'left' => '<span class="dashicons dashicons-editor-alignleft"></span>',
                    'center' => '<span class="dashicons dashicons-editor-aligncenter"></span>',
                    'right' => '<span class="dashicons dashicons-editor-alignright"></span>',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
            ],
            'blog_heading_text_color' => [
                'type' => 'color',
                'label' => esc_html__('Text Color', 'cenos'),
                'section' => 'bl_header',
                'default' => '#242424',
            ],
            'blog_heading_padding'    => [
                'type' => 'dimensions',
                'label' => esc_html__('Padding', 'cenos'),
                'section' => 'bl_header',
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
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_post',
                        'operator' => '==',
                        'value'    => false,
                    ],
                ],
            ],
            'blog_heading_divider'       => [
                'type'        => 'toggle',
                'label'   => esc_html__( 'Blog Heading Divider', 'cenos' ),
                'section' => 'bl_header',
                'default'     => false,
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'blog_heading_divider_color'        => [
                'type' => 'color',
                'label' => esc_html__('Divider Color', 'cenos'),
                'section' => 'bl_header',
                'default' => '',
                'active_callback' => [
                    [
                        'setting'  => 'blog_heading',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'blog_heading_divider',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],

            ],
            //bl_archive
            'blog_sidebar_layout' => [
                'type' => 'radio-image',
                'label' => esc_html__('Blog sidebar', 'cenos'),
                'section' => 'bl_archive',
                'default' => 'sidebar-right',
                'choices' => [
                    'none'          => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/full-width.jpg',
                    'sidebar-left'  => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/sidebar-left.jpg',
                    'sidebar-right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/sidebar-right.jpg',
                ],
            ],
            'goto_sidebar_widget' => [
                'type'     => 'custom',
                'section'  => 'bl_archive',
                'default'     => '<div class="goto_section_wrap"><a href="#." class="goto_section_btn button" data-section="widgets">Widgets Manager</a></div>',
                'active_callback' => [
                    [
                        'setting'  => 'blog_sidebar_layout',
                        'operator' => '!=',
                        'value'    => 'none',
                    ],
                ],
            ],
            'blog_list_style' => [
                'type' => 'radio-image',
                'label' => esc_html__('Blog List Style', 'cenos'),
                'section' => 'bl_archive',
                'default' => 'classic',
                'choices' => [
                    'classic'   => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/blog/blog_list.jpg',
                    'grid' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/blog/blog_grid.jpg',
                ],
            ],
            'blog_list_bg'         => [
                'type'        => 'background',
                'label'       => esc_html__( 'Page Background', 'cenos' ),
                'section'     => 'bl_archive',
                'default'     => ['background-color'      => '#ffffff',],
            ],
            'blog_list_action_bar' => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Enable Action Bar', 'cenos' ),
                'description' => esc_html__( 'Enable the Action Bar before Blog list.', 'cenos' ),
                'section'     => 'bl_archive',
                'default'     => false,
                'active_callback' => [
                    [
                        'setting'  => 'blog_sidebar_layout',
                        'operator' => '==',
                        'value'    => 'none',
                    ],
                ],
            ],
            //bl_single_post
            'blog_singular_layout' => [
                'type' => 'radio-image',
                'label' => esc_html__('Singular sidebar', 'cenos'),
                'section' => 'bl_single_post',
                'default' => 'sidebar-right',
                'choices' => [
                    'none'          => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/full-width.jpg',
                    'sidebar-left'  => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/sidebar-left.jpg',
                    'sidebar-right' => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/sidebar-right.jpg',
                ],
            ],
            'blog_single_style' => [
                'type' => 'radio-image',
                'label' => esc_html__('Post Style', 'cenos'),
                'section' => 'bl_single_post',
                'default' => 'classic',
                'choices' => [
                    'classic'   => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/sidebar/full-width.jpg',
                ],
            ],
            'blog_post_nav'     => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Post Navigation', 'cenos' ),
                'section'     => 'bl_single_post',
                'default'     => true,
            ],
            'blog_post_author_bio'     => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Author Bio', 'cenos' ),
                'section'     => 'bl_single_post',
                'default'     => false,
            ],
            'blog_post_share'     => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Post Shares', 'cenos' ),
                'description' => esc_html__( 'Enable share button in single post.', 'cenos' ),
                'section'     => 'bl_single_post',
                'default'     => false,
            ]
        ];
        return array_merge($fields,$site_layout_fields);
    }
}
new Blog();
