<?php
$footer_post = cenos_get_option('footer_post');
if ($footer_post == 'none'){
    $css .= cenos_background_style('footer_bg','.footer-wrap');
    $css .= cenos_color_style('footer_color','.site-footer');
    $css .= cenos_background_style('footer_main_bg','.footer_main');
    $css .= cenos_color_style('footer_main_color','.footer_main');
    $css .= cenos_dimension_style('footer_main_padding','.footer_main');
}
