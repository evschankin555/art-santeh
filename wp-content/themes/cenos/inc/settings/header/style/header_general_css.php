<?php
$header_dimensions = cenos_get_option('header_dimensions');
$header_container_w = 'width: '.cenos_css_unit($header_dimensions).';';
$css .= '.header-container, .footer-container{'.$header_container_w.'}';
$header_height = cenos_get_option('header_height');
if ($header_height != ''){
    $css .= '.header-main{height: '.cenos_css_unit($header_height).';}';
}
$header_bg_color = cenos_get_option('header_bg_color');
if ($header_bg_color != ''){
    $css .= '.site-header:not(.cenos_transparent_header) .header-layout{background-color:'.$header_bg_color.';}';
}
$header_color = cenos_get_option('header_color');
$text_color_hover = cenos_get_option('text_color_hover');
$css .= cenos_header_color_general($header_color,$text_color_hover);


$header_divider = cenos_get_option('header_divider');
if ($header_divider != 'none'){
    $css .='.header-layout{';
    if ($header_divider == 'shadow'){
        $css .='box-shadow: 0 7px 6px -4px rgba(0, 0, 0, 0.03), 0 7px 6px rgba(0, 0, 0, 0.03);border-bottom: none;';
    }else{
        $header_divider_color = cenos_get_option('header_divider_color');
        if ($header_divider_color == ''){
            $header_divider_color = 'transparent';
        }
        $css .='border-bottom-width: 1px;border-bottom-style: solid; border-bottom-color: '.$header_divider_color.';';
    }
    $css .='}';
}
