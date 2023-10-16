<?php
$blog_heading = cenos_get_option('blog_heading');
if ($blog_heading == true){
    $blog_heading_bg = cenos_get_option('blog_heading_bg');
    $blog_heading_ovelay = false;
    if (isset($blog_heading_bg['background-image']) && $blog_heading_bg['background-image'] !=''){
        $css .= '.blog-heading-content{background-image: url('.$blog_heading_bg['background-image'].');';
        foreach ($blog_heading_bg as $key => $value){
            if ($key !== 'background-image' && $value != ''){
                $css .=  $key.':'.$value.';';
            }
        }
        $css .= '}';
        $blog_heading_ovelay = true;
    }
    if (isset($blog_heading_bg['background-color']) && $blog_heading_bg['background-color'] !='') {
        if ($blog_heading_ovelay){
            $css .= '.blog-heading-content .page-heading-overlay{background-color: '.cenos_overlay_color($blog_heading_bg['background-color']).';}';
        } else {
            $css .= '.blog-heading-content{background-color: '.$blog_heading_bg['background-color'].';}';
        }
    }
    $blog_header_selector = '.blog-heading-content';
    $blog_heading_text_color = cenos_get_option('blog_heading_text_color');
    if ($blog_heading_text_color !=''){
        $css .= $blog_header_selector.','.$blog_header_selector.' p,'.$blog_header_selector.' a,'.$blog_header_selector.' .page-heading-title{color:'.$blog_heading_text_color.';}';
        $css .= $blog_header_selector.' svg.stroke{stroke:'.$blog_heading_text_color.';}';
        $css .= $blog_header_selector.' svg.fill{fill:'.$blog_heading_text_color.';}';
    }

    $blog_heading_height = cenos_get_option('blog_heading_height');
    if ($blog_heading_height){
        $blog_heading_height = cenos_css_unit($blog_heading_height);
        $css .= '.blog-heading-content{height:'.$blog_heading_height.';}';
    }
    $css .= cenos_dimension_style('blog_heading_padding','.blog-heading-content');
    //blog_list_bg
    $css .= cenos_background_style('blog_list_bg','.blog .content, .archive:not(.woocommerce-page) .content');

    if (cenos_get_option('blog_heading_divider')){
        $blog_heading_divider_color = cenos_get_option('blog_heading_divider_color');
        if ($blog_heading_divider_color != ''){
            $css .= '.page-heading-wrap.has-divider .blog-heading-content{border-color: '.$blog_heading_divider_color.';}';
        }
    }
}
