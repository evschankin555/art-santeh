<?php
$show_top_bar = cenos_get_option('show_top_bar');
if (!$show_top_bar){
    return;
}
$top_bar_height = cenos_get_option('top_bar_height');
$tb_h = '';
if ($top_bar_height){
    $tb_h = 'height: '.$top_bar_height.'px;';
}
if ($tb_h != '') {
    $css .= '.top-bar{'.$tb_h.'}';
}
$css .= cenos_background_style('top_bar_bg','.site-header .top-bar');

$top_bar_divider = cenos_get_option('top_bar_divider');
if ($top_bar_divider){
    $top_bar_divider_color = cenos_get_option('top_bar_divider_color');
    if ($top_bar_divider_color != ''){
        $css .='.top-bar{';
        $css .='border-bottom-width: 1px;border-bottom-style: solid; border-bottom-color: '.$top_bar_divider_color.';';
        $css .='}';
    }
}