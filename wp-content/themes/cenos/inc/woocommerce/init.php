<?php
include_once CENOS_TEMPLATE_DIRECTORY .'/inc/woocommerce/woocommerce-functions.php';
add_action('wp_loaded','cenos_load_woocommerce',11);

add_action('wp_ajax_nopriv_live_search_products', 'cenos_live_search_products');
add_action('wp_ajax_live_search_products', 'cenos_live_search_products');

add_action('wp_ajax_nopriv_cenos_update_quantity', 'cenos_ajax_update_qty_cart');
add_action('wp_ajax_cenos_update_quantity', 'cenos_ajax_update_qty_cart');

add_action( 'wp_ajax_cenos_get_wishlist_count', 'cenos_get_wishlist_count' );
add_action( 'wp_ajax_nopriv_cenos_get_wishlist_count','cenos_get_wishlist_count' );

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'cenos_woocommerce_gallery_thumbnail_size' );

add_filter('woocommerce_show_page_title','__return_false');
//check hidden quantity option
add_filter( 'woocommerce_quantity_input_max','cenos_woo_quantity_input_max', 10, 2);

// This theme doesn't have a traditional sidebar.
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
//edit add to cart text for external product
remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
add_action( 'woocommerce_external_add_to_cart', 'cenos_woo_external_add_to_cart', 30 );

// Disable redirect to product page while having only one search result
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
add_filter( 'woocommerce_breadcrumb_defaults','cenos_woocommerce_breadcrumb_args');

//change add to cart link html
add_filter('woocommerce_loop_add_to_cart_link','cenos_woo_loop_add_to_cart_link',10,3);
$shop_list_style = cenos_get_option('shop_list_style');
$product_item_style = cenos_get_option('product_item_style');
if ($shop_list_style == 'grid' && $product_item_style == 'default'){
    // default: all hooks by woocommerce plugin.
    return;
}
//remove all wishlist hook
if (class_exists('YITH_WCWL_Frontend')){
    add_filter('yith_wcwl_positions', '__return_null',100);
    add_filter('yith_wcwl_loop_positions', '__return_null',100);
}
if ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION'))){
    remove_action( 'woocommerce_before_add_to_cart_button', 'tinvwl_view_addto_html', 20 );
    remove_action( 'woocommerce_before_add_to_cart_button', 'tinvwl_view_addto_html', 9 );
    remove_action( 'woocommerce_single_product_summary', 'tinvwl_view_addto_htmlout', 29 );
    remove_action( 'woocommerce_after_add_to_cart_button', 'tinvwl_view_addto_html', 0 );
    remove_action( 'woocommerce_single_product_summary', 'tinvwl_view_addto_htmlout', 31 );
    remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
    remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop', 8 );
    remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop' );
    remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop', 20 );
    remove_action( 'woocommerce_before_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
    remove_action( 'woocommerce_product_thumbnails', 'tinvwl_view_addto_html', 21 );
    add_filter( 'tinvwl_allow_addtowishlist_single_product_summary', '__return_false' );
}

if (defined('WOOSQ_VERSION')) {
    add_filter('woosq_button_position','cenos_woosq_button_position');
}

add_action( 'wp_footer', 'cenos_single_sticky_atc' );
add_action( 'wp_footer', 'cenos_scroll_cart' );
add_action( 'wp_footer', 'cenos_scroll_wishlist' );