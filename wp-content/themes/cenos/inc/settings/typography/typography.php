<?php
class Typography extends Settings
{

    public function getPanels($panels)
    {
        $panels['typography_panel'] = array(
            'title' => esc_html__('Typography', 'cenos'),
        );
        return $panels;
    }

    public function getSections($sections)
    {
        $style_sections = [
            'main_typo' => array(
                'title' => esc_html__('Body ', 'cenos'),
                'panel' => 'typography_panel',
            ),
            'headline_typo' => array(
                'title' => esc_html__('Headline', 'cenos'),
                'panel' => 'typography_panel',
            ),
            'menu_typo' => array(
                'title' => esc_html__('Menu', 'cenos'),
                'panel' => 'typography_panel',
            ),
        ];
        return array_merge($sections, $style_sections);
    }

    public function getFields($fields)
    {
        $typo_fields = [
            'typo_body' => [
                'type' => 'typography',
                'label' => esc_html__('Body', 'cenos'),
                'description' => esc_html__('Customize the body font', 'cenos'),
                'section' => 'main_typo',
                'default' => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '400',
                    'font-size'      => '14px',
                    'line-height'    => '24px',
                    'letter-spacing' => '0',
                    'color'          => '#777777',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            // Typography headings
            'typo_h1'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 1', 'cenos' ),
                'description' => esc_html__( 'Customize the H1 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '500',
                    'font-size'      => '40px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#242424',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_h2'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 2', 'cenos' ),
                'description' => esc_html__( 'Customize the H2 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '500',
                    'font-size'      => '30px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#23232c',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_h3'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 3', 'cenos' ),
                'description' => esc_html__( 'Customize the H3 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '500',
                    'font-size'      => '20px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#23232c',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_h4'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 4', 'cenos' ),
                'description' => esc_html__( 'Customize the H4 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '400',
                    'font-size'      => '18px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#23232c',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_h5'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 5', 'cenos' ),
                'description' => esc_html__( 'Customize the H5 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '400',
                    'font-size'      => '14px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#23232c',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_h6'                        => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Heading 6', 'cenos' ),
                'description' => esc_html__( 'Customize the H6 font', 'cenos' ),
                'section'     => 'headline_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '400',
                    'font-size'      => '12px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                    'color'          => '#23232c',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            // Typography Menu
            'typo_main_nav' => [
                'type'        => 'typography',
                'label'       => esc_html__( 'Primary Menu', 'cenos' ),
                'section'     => 'menu_typo',
                'default'     => [
                    'font-family'    => 'Rubik',
                    'font-weight'    => '500',
                    'font-size'      => '13px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'text-transform' => 'none',
                ],
                'output'      => [],
                'transport'   => 'refresh',
            ],
            'typo_main_nav_only' => [
                'type'        => 'toggle',
                'label'       => esc_html__( 'Only Main Nav', 'cenos' ),
                'section'     => 'menu_typo',
                'default'     => true,
            ]
        ];
        return array_merge($fields, $typo_fields);
    }
}

new  Typography();
