<?php

if ( ! function_exists( 'cenos_typography_css' ) ) {
    /**
     * Get typography CSS base on settings
     *
     * @since 1.1.6
     */
    function cenos_typography_css() {
        $css        = '';
        $main_nav_selector = '.site-header .main-navigation, .site-header .main-navigation a';
        $typo_main_nav_only = cenos_get_option('typo_main_nav_only');
        if ($typo_main_nav_only){
            $main_nav_selector = '.site-header .main-navigation > ul > li > a, .site-header .main-navigation > .nav-menu > ul > li > a';
        }
        $settings = [
            'typo_body'         => 'body',
            'typo_h1'           => 'h1, .h1',
            'typo_h2'           => 'h2, .h2',
            'typo_h3'           => 'h3, .h3',
            'typo_h4'           => 'h4, .h4',
            'typo_h5'           => 'h5, .h5',
            'typo_h6'           => 'h6, .h6',
            'announcement_typo' => '.cenos-announcement-box',
            'typo_main_nav'     => $main_nav_selector,
        ];
        $typo_color_selector = [
            'typo_body'         => '',
            'typo_h1'           => '',
            'typo_h2'           => '',
            'typo_h3'           => '',
            'typo_h4'           => '',
            'typo_h5'           => '',
            'typo_h6'           => '',
            'announcement_typo' => '',
            'typo_main_nav'     => '',
        ];
        foreach ( $settings as $setting => $selector ) {
            if (isset($typo_color_selector[$setting]) && !empty($typo_color_selector[$setting])){
                if (is_array($typo_color_selector[$setting])){
                    $typo_color_selector[$setting] = implode(',' ,$typo_color_selector[$setting]);
                }
            }
            $typography = cenos_get_option( $setting );
            $style      = '';
            if ($typography && !empty($typography)){
                foreach ($typography as $property => $value){
                    if (!empty($value)){
                        if ($property == 'variant'){
                            $typography['variant'] = ( 400 === $value || '400' === $value ) ? 'regular' : $value;
                            // Get font-weight from variant.
                            $typography['font-weight'] = filter_var( $typography['variant'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                            $typography['font-weight'] = ( 'regular' === $typography['variant'] || 'italic' === $typography['variant'] ) ? 400 : absint( $typography['font-weight'] );

                            // Get font-style from variant.
                            if ( ! isset( $typography['font-style'] ) ) {
                                $typography['font-style'] = ( false === strpos( $typography['variant'], 'italic' ) ) ? 'normal' : 'italic';
                            }
                            $style .= 'font-style: ' . $typography['font-style'] . ';';
                            $style .= 'font-weight: ' . $typography['font-weight'] . ';';

                        } elseif ($property != 'color'){
                            $style .= $property . ': ' . $value . ';';
                        }
                    }
                }
            }
            if ( ! empty( $style ) ) {
                $css .= $selector . '{' . $style . '}';
            }
            if (isset($typography['color']) && $typography['color'] != ''){
                $color_selector = (isset($typo_color_selector[$setting]) && !empty($typo_color_selector[$setting]))? $typo_color_selector[$setting]: $selector;
                $css .= $color_selector.' {color:'.$typography['color'].';}';
                $css .= $color_selector.' button.close svg { fill: '.$typography['color'].';}';
                $prefix = '';
                if ($setting == 'typo_main_nav'){
                    if ($typo_main_nav_only){
                        $prefix = ' > ';
                    }
                }
                if ($setting != 'typo_body' && $setting != 'typo_main_nav'){
                    $fill_selector = str_replace(',',$prefix.' svg.fill,',$color_selector).$prefix.' svg.fill';
                    $stroke_selector = str_replace(',',$prefix.' svg.stroke,',$color_selector).$prefix.' svg.stroke';
                    $css .= $stroke_selector.'{stroke:'.$typography['color'].';}';
                    $css .= $fill_selector.'{fill:'.$typography['color'].';}';
                }
            }
        }
        return $css;
    }
}
$css .= cenos_typography_css();
