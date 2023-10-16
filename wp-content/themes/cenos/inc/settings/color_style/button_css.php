<?php

if ( ! function_exists( 'cenos_button_css' ) ) {
    function cenos_button_css($type = 'default'){
        $advan_btn = false;
        $css = '';
        $btn_style = cenos_get_option('btn_'.$type.'_style');
        $bg_color = cenos_get_option('btn_'.$type.'_bg');
        $text_color = cenos_get_option('btn_'.$type.'_color');
        $bg_color_hover = cenos_get_option('btn_'.$type.'_bg_hover');
        $text_color_hover = cenos_get_option('btn_'.$type.'_color_hover');
        $class_button_woo = '.familab_theme #respond input#submit.btn-'.$btn_style.', .familab_theme a.button.btn-'.$btn_style.', .familab_theme button.button.btn-'.$btn_style.', .familab_theme input.button.btn-'.$btn_style;
        $class_button_woo_hover = '.familab_theme #respond input#submit.btn-'.$btn_style.':hover, .familab_theme a.button.btn-'.$btn_style.':hover, .familab_theme button.button.btn-'.$btn_style.':hover, .familab_theme input.button.btn-'.$btn_style.':hover';
        $class_button_woo_special = '.btn-woo-'.$btn_style.'.familab_theme button, .btn-woo-'.$btn_style.'.familab_theme a.button, .btn-woo-'.$btn_style.'.familab_theme button.button, .btn-woo-'.$btn_style.'.familab_theme #respond input#submit, .btn-woo-'.$btn_style.'.familab_theme #respond input.button, .btn-woo-'.$btn_style.'.familab_theme .widget_shopping_cart .buttons a, .btn-woo-'.$btn_style.'.familab_theme #respond input#submit.alt, .btn-woo-'.$btn_style.'.familab_theme a.button.alt, .btn-woo-'.$btn_style.'.familab_theme button.button.alt, .btn-woo-'.$btn_style.'.familab_theme input.button.alt';
        $class_button_woo_special_hover = '.btn-woo-'.$btn_style.'.familab_theme button:hover, .btn-woo-'.$btn_style.'.familab_theme a.button:hover, .btn-woo-'.$btn_style.'.familab_theme button.button:hover, .btn-woo-'.$btn_style.'.familab_theme #respond input#submit:hover, .btn-woo-'.$btn_style.'.familab_theme #respond input.button:hover, .btn-woo-'.$btn_style.'.familab_theme .widget_shopping_cart .buttons a:hover, .btn-woo-'.$btn_style.'.familab_theme #respond input#submit.alt:hover, .btn-woo-'.$btn_style.'.familab_theme a.button.alt:hover, .btn-woo-'.$btn_style.'.familab_theme button.button.alt:hover, .btn-woo-'.$btn_style.'.familab_theme input.button.alt:hover';
        $class_advance = '.btn-'.$type.'.btn-advance';
        $class_advance_hover = '.btn-'.$type.'.btn-advance:hover';
        $class = $class_button_woo .', '. $class_button_woo_special . ', .btn-'.$type.'.btn-'.$btn_style;
        $advance_css = '';
        $advance_css_hover = '';
        if ($btn_style == 'advance'){
            $advan_btn = true;
            $btn_border_style = cenos_get_option('btn_'.$type.'_border_style');
            if ($btn_border_style != 'none'){
                $advance_css = 'border-style:'.$btn_border_style.';';
                $btn_border_width = cenos_get_option('btn_'.$type.'_border_width');
                if ($btn_border_width != ''){
                    $advance_css .= 'border-width:'.cenos_css_unit($btn_border_width,4).';';
                }
                $btn_border_radius = cenos_get_option('btn_'.$type.'_border_radius');
                if ($btn_border_radius != '') {
                    $advance_css .= 'border-radius:' . cenos_css_unit($btn_border_radius, 4) . ';';
                }
                $border_color = cenos_get_option('btn_'.$type.'_border_color').';';
                if ($border_color != '' && $border_color != ';') {
                    $advance_css .= 'border-color:' . $border_color;
                }
                $border_color_hover = cenos_get_option('btn_'.$type.'_border_color_hover');
                if ($border_color_hover != '') {
                    $advance_css_hover .= 'border-color:' . $border_color_hover;
                }
            }
            if (cenos_get_option('btn_own_typo')){
                $btn_typo = cenos_get_option('btn_'.$type.'_typography');
                if ($btn_typo && isset($btn_typo['font-size']) && $btn_typo['font-size'] != ''){
                    $advance_css .= 'font-size:'.cenos_css_unit($btn_typo['font-size']).';';
                }
                if ($btn_typo && isset($btn_typo['text-transform'])){
                    $advance_css .= 'text-transform:'.$btn_typo['text-transform'].';';
                }
                if ($btn_typo && isset($btn_typo['line-height']) && $btn_typo['line-height'] != ''){
                    $advance_css .= 'line-height:'.cenos_css_unit($btn_typo['line-height']).';';
                }
            }

        }
        //nomal style
        $css_string = '';

        if ($bg_color != ''){
            $css_string .= 'background-color:'.$bg_color.';';
        }
        if ($text_color != ''){
            $css_string .= 'color:'.$text_color.';';
        }
        if ($css_string != ''){
            $css = $class.' {'.$css_string.'}';
        }

        if ($advan_btn && $advance_css != ''){
            $css .= $class_advance.' , '.$class_button_woo.' , '.$class_button_woo_special. '{'.$advance_css.'}';
        }
        if ($advan_btn && $advance_css_hover != ''){
            $css .= $class_advance_hover.' , '.$class_button_woo_hover.' , '.$class_button_woo_special_hover. '{'.$advance_css_hover.'}';
        }

        //hove style
        $css_string = '';
        if ($bg_color_hover != ''){
            $css_string .= 'background-color:'.$bg_color_hover.';';
        }
        if ($text_color_hover != ''){
            $css_string .= 'color:'.$text_color_hover.';';
        }
        if ($css_string != ''){
             $class_hover = $class_button_woo_hover .', '. $class_button_woo_special_hover . ', .btn-'.$type.'.btn-'.$btn_style.':hover';
            $css .= $class_hover.' {'.$css_string.'}';
        }
        return $css;
    }

}
$css .= cenos_button_css('default');
