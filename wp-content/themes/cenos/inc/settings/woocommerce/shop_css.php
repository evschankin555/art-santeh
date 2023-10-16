<?php
$shop_page_layout = cenos_get_option('shop_page_layout');

$shop_heading = cenos_get_option('shop_heading');
if ($shop_heading == true){
    $shop_heading_layout = cenos_get_option('shop_heading_layout');
    if ($shop_heading_layout != 'simple') {
        $shop_heading_bg = cenos_get_option('shop_heading_bg');
        $shop_heading_ovelay = false;
        if (isset($shop_heading_bg['background-image']) && $shop_heading_bg['background-image'] !=''){
            $css .= '.shop-heading-content{background-image: url('.$shop_heading_bg['background-image'].');';
            foreach ($shop_heading_bg as $key => $value){
                if ($key !== 'background-image' && $value != ''){
                    $css .=  $key.':'.$value.';';
                }
            }
            $css .= '}';
            $shop_heading_ovelay = true;
        }
        if (isset($shop_heading_bg['background-color']) && $shop_heading_bg['background-color'] !='') {
            if ($shop_heading_ovelay){
                $css .= '.shop-heading-content .page-heading-overlay{background-color: '.cenos_overlay_color($shop_heading_bg['background-color']).';}';
            } else {
                $css .= '.shop-heading-content{background-color: '.$shop_heading_bg['background-color'].';}';
            }
        }
        $shop_heading_height = cenos_get_option('shop_heading_height');
        if ($shop_heading_height){
            $shop_heading_height = cenos_css_unit($shop_heading_height);
            $css .= '.shop-heading-content{height:'.$shop_heading_height.';}';
        }
        $css .= cenos_dimension_style('shop_heading_padding','.shop-heading-content');
    } else {
        $shop_heading_simple_background = cenos_get_option('shop_heading_simple_background');
        if ($shop_heading_simple_background){
            $css .= '.shop-heading-simple .shop-heading-content{background-color:'.$shop_heading_simple_background.';}';
        }
    }
}
if ($shop_page_layout == 'background') {
    $css .= cenos_background_style('shop_page_background','.shop-page-layout-background #content');
}

$shop_badge_sale_bg = cenos_get_option('shop_badge_sale_bg');
$sale_bg = '';
if ($shop_badge_sale_bg != '') {
    $sale_bg = 'background-color: '.$shop_badge_sale_bg.';';
}
$shop_badge_sale_color = cenos_get_option('shop_badge_sale_color');
$sale_color = '';
if ($shop_badge_sale_color != '') {
    $sale_color = 'color: '.$shop_badge_sale_color.';';
}
if ($sale_bg != '' || $sale_color != '') {
    $css .= '.woocommerce-badge.on_sale {'.$sale_color.$sale_bg.';}';
}

$shop_badge_new_bg = cenos_get_option('shop_badge_new_bg');
$new_bg = '';
if ($shop_badge_new_bg != '') {
    $new_bg = 'background-color: '.$shop_badge_new_bg.';';
}
$shop_badge_new_color = cenos_get_option('shop_badge_new_color');
$new_color = '';
if ($shop_badge_new_color != '') {
    $new_color = 'color: '.$shop_badge_new_color.';';
}
if ($new_bg != '' || $new_color != '') {
    $css .= '.woocommerce-badge.new {'.$new_color.$new_bg.';}';
}

$shop_badge_hot_bg = cenos_get_option('shop_badge_hot_bg');
$hot_bg = '';
if ($shop_badge_hot_bg != '') {
    $hot_bg = 'background-color: '.$shop_badge_hot_bg.';';
}
$shop_badge_hot_color = cenos_get_option('shop_badge_hot_color');
$hot_color = '';
if ($shop_badge_hot_color != '') {
    $hot_color = 'color: '.$shop_badge_hot_color.';';
}
if ($hot_bg != '' || $hot_color != '') {
    $css .= '.woocommerce-badge.featured {'.$hot_color.$hot_bg.';}';
}


$shop_badge_sold_out_bg = cenos_get_option('shop_badge_sold_out_bg');
$sold_out_bg = '';
if ($shop_badge_sold_out_bg != '') {
    $sold_out_bg = 'background-color: '.$shop_badge_sold_out_bg.';';
}
$shop_badge_sold_out_color = cenos_get_option('shop_badge_sold_out_color');
$sold_out_color = '';
if ($shop_badge_sold_out_color != '') {
    $sold_out_color = 'color: '.$shop_badge_sold_out_color.';';
}
if ($sold_out_bg != '' || $sold_out_color != '') {
    $css .= '.woocommerce-badge.sold_out {'.$sold_out_color.$sold_out_bg.';}';
}
    $css .= cenos_background_style('woo_single_product_background','.product-main.has_background:before');

if (cenos_get_option('shop_heading_divider')) {
    $shop_heading_divider_color = cenos_get_option('shop_heading_divider_color');
    if ($shop_heading_divider_color != '') {
        $css .= '.page-heading-wrap.has-divider .shop-heading-content{border-color: ' . $shop_heading_divider_color . ';}';
    }
}
$woo_single_thumb_height_tablet = cenos_css_unit(cenos_get_option('woo_single_thumb_height_tablet'));
if ($woo_single_thumb_height_tablet){
    $css .='@media (min-width: 992px) {.single-product .woocommerce-product-gallery.gallery_style_vertical .gallery_thumb_swiper{height: '.$woo_single_thumb_height_tablet.';}}';
}
$woo_single_thumb_height_desktop = cenos_css_unit(cenos_get_option('woo_single_thumb_height_desktop'));
if ($woo_single_thumb_height_desktop){
    $css .='@media (min-width: 1200px) {.single-product .woocommerce-product-gallery.gallery_style_vertical .gallery_thumb_swiper{height: '.$woo_single_thumb_height_desktop.';}}';
}