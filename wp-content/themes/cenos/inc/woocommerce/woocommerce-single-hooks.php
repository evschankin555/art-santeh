<?php
add_filter( 'woocommerce_single_product_carousel_options', 'cenos_product_carousel_options' );//flexslider option
add_filter( 'woocommerce_single_product_zoom_enabled','cenos_single_product_image_zoom');
add_filter( 'woocommerce_single_product_photoswipe_enabled','cenos_single_product_image_lightbox');
add_filter( 'cenos_single_product_summary_class','cenos_single_product_summary_class');
add_action('woocommerce_product_thumbnails','cenos_product_thumb_video',100);
//remove all wishlist default hook
add_filter('yith_wcwl_positions', '__return_null');
//cenos_single_product_group_btn
add_filter('woocommerce_product_thumbnails_columns','cenos_single_product_thumbnails_columns');
add_filter('woocommerce_output_related_products_args','cenos_related_products_args');
add_action('woocommerce_before_single_product_summary', 'cenos_show_product_360deg_button',10);
add_action('woocommerce_before_single_product_summary', 'cenos_product_popup_video_button',10);
add_action( 'woocommerce_single_product_summary', 'cenos_product_show_dokan_sold_by', 9 );

$cenos_is_mobile_single_product_layout = cenos_is_mobile_single_product_layout();
if ($cenos_is_mobile_single_product_layout){
    add_action('woocommerce_before_single_product','cenos_shop_control');
    //woocommerce_before_single_product_summary
    add_action('woocommerce_before_single_product_summary','cenos_show_single_wishlist_btn');
    add_action('woocommerce_before_single_product_summary','cenos_woo_share');
} else {
    $woo_single_layout = cenos_get_option('woo_single_layout');
    //
    if ($woo_single_layout != 'wide-gallery'){
        add_action('woocommerce_before_single_product','cenos_shop_control');
    } else {
        //add_action('woocommerce_before_single_product_summary','cenos_shop_control', PHP_INT_MAX);
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        add_action('cenos_single_product_summary_atc_form','woocommerce_template_single_add_to_cart');
    }

    add_filter( 'woocommerce_single_product_image_gallery_classes', 'cenos_single_product_image_gallery_classes');
    add_filter( 'woocommerce_single_product_flexslider_enabled', 'cenos_single_product_flexslider_enabled');
    add_filter( 'woocommerce_gallery_image_size','cenos_single_product_gallery_image_size' );
    add_action( 'woocommerce_single_product_summary', 'cenos_woo_breadcrumb_in_summary', 4);
    add_action( 'woocommerce_share','cenos_woo_share');
    add_action('woocommerce_after_add_to_cart_button','cenos_show_single_wishlist_btn');
    if ('sticky' == cenos_get_option('woo_single_layout') && 'list' == cenos_get_option('woo_single_image_style_for_sticky')) {
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
        add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs',200);
    }
}
add_action( 'woocommerce_after_single_product_summary', 'cenos_product_video_modal');
add_action( 'woocommerce_after_single_product_summary', 'cenos_show_product_360deg_modal');
add_action('woocommerce_after_add_to_cart_form','cenos_woo_promo_info');