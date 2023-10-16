<?php
class General extends Settings
{

    public function getPanels($panels)
    {
        $panels['general'] = array(
            'priority' => 10,
            'title'    => esc_html__( 'General', 'cenos' ),
            );
        return $panels;
    }

    public function getSections($sections)
    {
        $general_sections = [
            'maintenance'   => [
                'title'    => esc_html__( 'Maintenance', 'cenos' ),
                'panel'    => 'general',
            ],
            'preloader'     => [
                'title'    => esc_html__( 'Preloader', 'cenos' ),
                'panel'    => 'general',
            ],
            'popup'     => [
                'title'    => esc_html__( 'Popup', 'cenos' ),
                'panel'    => 'general',
            ],
            'ct_info' => [
                'title'    => esc_html__( 'Contact info', 'cenos' ),
                'panel'    => 'general',
            ],
            'social_link' => [
                'title'    => esc_html__( 'Social Follow', 'cenos' ),
                'panel'    => 'general',
            ]
        ];
        return array_merge($sections,$general_sections);
        return $sections;
    }

    public function getFields($fields)
    {
        global $page_choices;
        $general_field = [
            //maintenance
            'maintenance_enable'    => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Enable Maintenance Mode', 'cenos' ),
                'description' => esc_html__( 'Put your site into maintenance mode', 'cenos' ),
                'default'     => false,
                'section'     => 'maintenance',
            ],
            'maintenance_mode'      => [
                'type'        => 'radio-buttonset',
                'label'       => esc_html__( 'Maintenance Mode', 'cenos' ),
                'description' => sprintf(esc_html__( 'If you are putting your site into maintenance mode for a longer perior of time, you should set this to "Coming Soon". Maintenance will return HTTP 503, Comming Soon will set HTTP to 200. <a href="%s" target="_blank">Learn more</a>', 'cenos' ), 'https://yoast.com/http-503-site-maintenance-seo/' ),
                'section'     => 'maintenance',
                'default'     => 'maintenance',
                'choices'     => [
                    'maintenance' => esc_attr__( 'Maintenance', 'cenos' ),
                    'coming_soon' => esc_attr__( 'Coming Soon', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'maintenance_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'maintenance_page'      => [
                'type'    => 'select',
                'label'   => esc_html__( 'Maintenance Page', 'cenos' ),
                'default' => 0,
                'section'     => 'maintenance',
                'choices'     => $page_choices,
                'active_callback' => [
                    [
                        'setting'  => 'maintenance_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],

            //preloader
            'preloader'                      => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Enable Preloader', 'cenos' ),
                'description' => esc_html__( 'Show a waiting screen when page is loading', 'cenos' ),
                'section'     => 'preloader',
                'default'     => false,
            ],
            'preloader_background_color'     => [
                'type'            => 'color',
                'label'           => esc_html__( 'Background Color', 'cenos' ),
                'section'         => 'preloader',
                'default'         => 'rgba(255,255,255,0.95)',
                'choices'         => [
                    'alpha' => true,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'preloader',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            // Popup
            'popup_enable'                          => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Enable Popup', 'cenos' ),
                'description' => esc_html__( 'Show a popup after website loaded.', 'cenos' ),
                'section'     => 'popup',
                'default'     => false,
            ],
            'popup_layout'                   => [
                'type'            => 'radio-image',
                'label'           =>esc_html__( 'Popup Layout', 'cenos' ),
                'description'     =>esc_html__( 'Select the popup layout', 'cenos' ),
                'section'         => 'popup',
                'default'         => 'modal',
                'choices'         => [
                    '1-column'    => CENOS_TEMPLATE_DIRECTORY_URI . 'assets/images/popup/popup-1.jpg',
                ],
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'popup_overlay_color'            => [
                'type'            => 'color',
                'label'           =>esc_html__( 'Overlay Color', 'cenos' ),
                'description'     =>esc_html__( 'Pickup the background color for popup overlay', 'cenos' ),
                'section'         => 'popup',
                'default'         => 'rgba(35,35,44,0.5)',
                'choices'         => [
                    'alpha' => true,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'popup_image'                    => [
                'type'            => 'image',
                'label'           =>esc_html__( 'Banner Image', 'cenos' ),
                'description'     =>esc_html__( 'Upload popup banner image', 'cenos' ),
                'section'         => 'popup',
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'popup_layout',
                        'operator' => '==',
                        'value'    => 'modal',
                    ],
                ],
            ],
            'popup_content'                  => [
                'type'            => 'editor',
                'label'           => esc_html__( 'Popup Content', 'cenos' ),
                'description'     => esc_html__( 'Enter popup content. HTML and shortcodes are allowed.', 'cenos' ),
                'section'         => 'popup',
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'popup_frequency'                => [
                'type'            => 'number',
                'label'           => esc_html__( 'Frequency', 'cenos' ),
                'description'     => esc_html__( 'Do NOT show the popup to the same visitor again until this much day has passed.', 'cenos' ),
                'section'         => 'popup',
                'default'         => 0,
                'choices'         => [
                    'min'  => 0,
                    'step' => 1,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'popup_visible'                  => [
                'type'            => 'select',
                'label'           => esc_html__( 'Popup Visible', 'cenos' ),
                'description'     => esc_html__( 'Select when the popup appear', 'cenos' ),
                'section'         => 'popup',
                'default'         => 'loaded',
                'choices'         => [
                    'loaded' => esc_html__( 'Right after page loads', 'cenos' ),
                    'delay'  => esc_html__( 'Wait for seconds', 'cenos' ),
                ],
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                ],
            ],
            'popup_visible_delay'            => [
                'type'            => 'number',
                'label'           => esc_html__( 'Delay Time', 'cenos' ),
                'description'     => esc_html__( 'Set how many seconds after the page loads before the popup is displayed.', 'cenos' ),
                'section'         => 'popup',
                'default'         => 5,
                'choices'         => [
                    'min'  => 0,
                    'step' => 1,
                ],
                'active_callback' => [
                    [
                        'setting'  => 'popup_enable',
                        'operator' => '==',
                        'value'    => true,
                    ],
                    [
                        'setting'  => 'popup_visible',
                        'operator' => '==',
                        'value'    => 'delay',
                    ],
                ],
            ],
            //Contact info
            'ct_phone_label' => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Phone Label', 'cenos' ),
                'default' => 'Phone',
            ],
            'ct_phone' => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Phone Number', 'cenos' ),
                'default' => '+1-212-818-3320',
            ],
            'ct_2nd_phone_label' => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Mobile Phone Label', 'cenos' ),
                'default' => 'Mobile Phone',
            ],
            'ct_2nd_phone' => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Mobile Phone Number', 'cenos' ),
                'default' => '+1-917-913-1068',
            ],
            'ct_email_label'  => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Email label', 'cenos' ),
                'default' => 'Email',
            ],
            'ct_email'  => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Email', 'cenos' ),
                'default' => 'example@gmail.com',
            ],
            'ct_location_label'  => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Location label', 'cenos' ),
                'default' => 'Location',
            ],
            'ct_location'  => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Location', 'cenos' ),
                'default' => '4188  Small Street NY',
            ],
            'ct_open_hours' => [
                'type' => 'text',
                'section'     => 'ct_info',
                'label' => esc_html__( 'Open hours', 'cenos' ),
                'default' => '8:00 - 17:00',
            ],
            //social link
            'facebook_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Facebook.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'twitter_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Twitter.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'google-plus_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Google Plus.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'instagram_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Instagram.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'youtube_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Youtube.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'vimeo_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Vimeo.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'linkedin_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Linkedin.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'dribbble_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Dribbble.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'behance_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Behance.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'tumblr_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Tumblr.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'pinterest_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'Pinterest.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            'rss_link' => [
                'type'        => 'generic',
                'label'       => esc_html__( 'RSS.', 'cenos' ),
                'section'     => 'social_link',
                'default'     => '',
                'choices'     => [
                    'element'  => 'input',
                    'type'  => 'url'
                ],
            ],
            //RSS
        ];
        return array_merge($fields,$general_field);
    }
}
new General();
