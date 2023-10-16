<?php
if ( ! function_exists( 'cenos_color_css' ) ) {
    function cenos_color_css(){
        $css_color = '';
        // Get Variant Color
        $color_link = cenos_get_option('link_color');
        $color_link_hover = cenos_get_option('link_color_hover');
        // Get Selectors
        $selectors_link = [
            'a'
        ];
        $class_link = implode(', ', $selectors_link);
        $class_link_hover = implode(':hover, ', $selectors_link).':hover';
        // Gen CSS
        $css_color .= $class_link.' {color: '. $color_link .'}';
        $css_color .= $class_link_hover.' {color: '. $color_link_hover .'}';
        return $css_color;
    }
}

$css .= '';
$form_fields_style = cenos_get_option('form_fields_style');
$css .= cenos_color_css();
require_once 'button_css.php';

