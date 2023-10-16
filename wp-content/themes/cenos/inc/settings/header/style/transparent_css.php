<?php
$option_transparent = cenos_get_option('show_transparent');
$transparent_selector = '';
if ($option_transparent) {
    $transparent_bg = cenos_get_option('transparent_bg');
    if ($transparent_bg){
        $transparent_bg_color = cenos_get_option('transparent_bg_color');
        if (!empty($transparent_bg_color)){
            $transparent_page = cenos_get_option('transparent_page');
            if ($transparent_page == 'all'){
                $transparent_selector = '.cenos_transparent_header';
            } else {
                $transparent_selector = 'body.home .cenos_transparent_header';
            }
            $css .= $transparent_selector.'{background-color:'.$transparent_bg_color.';}';
        }

        $transparent_color = cenos_get_option('transparent_color');
        $transparent_color_hover = cenos_get_option('transparent_color_hover');
        $css .= cenos_header_color_general($transparent_color,$transparent_color_hover,'transparent');
        if (!empty($transparent_selector)){
            if (!empty($transparent_color_hover)){
                $css .= $transparent_selector.' .site-header.cenos_transparent_header .site-navigation a:after{';
                $css .= 'background-color:'.$transparent_color_hover.';';
                $css .='}';
            } elseif (!empty($transparent_color)){
                $css .= $transparent_selector.' .site-header.cenos_transparent_header .site-navigation a:after{';
                $css .= 'background-color:'.$transparent_color.';';
                $css .='}';
            }
        }
    }
}

