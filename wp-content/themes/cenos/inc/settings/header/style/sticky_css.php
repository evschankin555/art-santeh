<?php
$sticky_advance = cenos_get_option('sticky_advance');
if ($sticky_advance == true){
    $sticky_height = cenos_get_option('sticky_height');
    $css .= '.site-header .header-layout.headroom--pinned.headroom--not-top .header-main{height:'.$sticky_height.'px;}';
    $sticky_bg_color = cenos_get_option('sticky_bg_color');
    if ($sticky_bg_color != ''){
        $css .= '.site-header .header-layout.headroom--pinned.headroom--not-top{background-color:'.$sticky_bg_color.';}';
    }
    $sticky_color = cenos_get_option('sticky_color');
    $sticky_hover_color = cenos_get_option('sticky_hover_color');
    $css .= cenos_header_color_general($sticky_color,$sticky_hover_color,'sticky');
}
