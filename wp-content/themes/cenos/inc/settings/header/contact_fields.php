<?php


if (!class_exists('Contact_Fields')) {
    class Contact_Fields
    {
        protected $setting;

        public function __construct()
        {
            $this->setting = [
                'contact_info_show_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Icon', 'cenos'),
                    'section' => 'head_contact_info',
                    'default' => true,
                ],
                'contact_info_show_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show Title', 'cenos'),
                    'section' => 'head_contact_info',
                    'default' => true,
                ],
                'contact_info_element' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Contact Info Elements', 'cenos' ),
                    'section'     => 'head_contact_info',
                    'default'     => [
                        'ct_phone',
                        'ct_email',
                    ],
                    'choices'     => [
                        'ct_phone' => esc_html__( 'Phone Info', 'cenos' ),
                        'ct_2nd_phone' => esc_html__( 'Second phone Info', 'cenos' ),
                        'ct_email' => esc_html__( 'Email Info', 'cenos' ),
                        'ct_location' => esc_html__( 'Location Info', 'cenos' ),
                        'ct_open_hours' => esc_html__( 'Open hours Info', 'cenos' ),
                    ],

                ],
                'goto_ct_group' => [
                    'type'        => 'custom',
                    'section'     => 'head_contact_info',
                    'default'     => '<div><hr/></div>',
                ],

                'goto_ct' => [
                    'type'     => 'custom',
                    'section'  => 'head_contact_info',
                    'default'     => '<div class="goto_section_wrap"><a href="#." class="goto_section_btn button" data-section="ct_info">Contact Info Setting</a></div>',
                ],
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
    }
}
return cenos_customize_field_setting('Contact_Fields');
