<?php


if (!class_exists('Social_Fields')) {
    class Social_Fields
    {
        protected $setting;

        protected $choices;

        public function __construct()
        {
            $this->choices = [
                'facebook' => esc_html__( 'Facebook', 'cenos' ),
                'twitter' => esc_html__( 'Twitter', 'cenos' ),
                'google-plus' => esc_html__( 'Google Plus info', 'cenos' ),
                'instagram' => esc_html__( 'Instagram', 'cenos' ),
                'youtube' => esc_html__( 'Youtube', 'cenos' ),
                'vimeo' => esc_html__( 'Vimeo', 'cenos' ),
                'linkedin' => esc_html__( 'Linkedin', 'cenos' ),
                'dribbble' => esc_html__( 'Dribbble', 'cenos' ),
                'behance' => esc_html__( 'Behance', 'cenos' ),
                'tumblr' => esc_html__( 'Tumblr', 'cenos' ),
                'pinterest' => esc_html__( 'Pinterest', 'cenos' ),
                'rss' => esc_html__( 'RSS', 'cenos' ),
            ];
            $this->setting = [
                'si_show_icon' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show icon', 'cenos'),
                    'section' => 'head_social',
                    'default' => true,
                ],
                'si_show_title' => [
                    'type' => 'toggle',
                    'label' => esc_html__('Show title', 'cenos'),
                    'section' => 'head_social',
                    'default' => false,
                ],
                'si_element' => [
                    'type'        => 'sortable',
                    'label'       => esc_html__( 'Social elements', 'cenos' ),
                    'section'     => 'head_social',
                    'default'     => [
                        'facebook',
                        'twitter',
                    ],
                    'choices'     => $this->choices,
                ],
                'goto_si_group' => [
                    'type'        => 'custom',
                    'section'     => 'head_social',
                    'default'     => '<div><hr/></div>',
                ],
                'goto_sl' => [
                    'type'     => 'custom',
                    'section'  => 'head_social',
                    'default'     => '<div class="goto_section_wrap"><a href="#." class="goto_section_btn button" data-section="social_link">Social link setting</a></div>',
                ],
            ];
        }

        public function getSetting()
        {
            return $this->setting;
        }
        public function getChoices(){
            return $this->choices;
        }
    }
}
return cenos_customize_field_setting('Social_Fields');
