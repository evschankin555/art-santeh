<?php
$default_woo = false;
// Remove product wrapper link.
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
// Wrap product into div.
add_action( 'woocommerce_before_shop_loop_item', 'cenos_product_item_wrapper_open', 1 );
// close on template

//modify product thumbnail by cenos.
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
add_action( 'woocommerce_before_shop_loop_item_title', 'cenos_template_loop_product_thumbnail' );

//add product link to product title
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
add_action( 'woocommerce_shop_loop_item_title', 'cenos_product_item_title');

add_action( 'woocommerce_before_shop_loop_item_title', 'cenos_product_item_infos_wrapper_open' , PHP_INT_MAX );
add_action( 'cenos_product_item_thumb_action','cenos_product_item_thumb_right_action');
add_action( 'cenos_product_item_thumb_action','cenos_product_item_thumb_bottom_action');
add_action( 'woocommerce_shop_loop_item_title', 'cenos_product_show_dokan_sold_by' , 6 );
if (cenos_on_device() && cenos_get_option('mobile_product_items_style')){
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action( 'woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 7 );
    remove_action( 'woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 30 );
} else {
    $shop_list_style = cenos_get_option('shop_list_style');
    $product_item_style = cenos_get_option('product_item_style');
    if ($shop_list_style == 'grid' && $product_item_style == 'default'){
        // default: all hooks by woocommerce plugin.
        $default_woo = true;
    }
    $product_item_rating = cenos_get_option('product_item_rating');
    if (!$product_item_rating) {
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    }
}
if (!$default_woo){
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price');
    remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart');
    //remove YITH_Woocompare hook
    if (class_exists('YITH_Woocompare')) {
        global $yith_woocompare;
        $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
        if( $yith_woocompare->is_frontend() || $is_ajax ) {
            if( $is_ajax ){
                if( !class_exists( 'YITH_Woocompare_Frontend' ) ){
                    $file_name = YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
                    if( file_exists( $file_name ) ){
                        require_once( $file_name );
                    }
                }
                $yith_woocompare->obj = new YITH_Woocompare_Frontend();
            }
            remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
        }
    }
    //remove YITH_WCQV hooks
    if (class_exists('YITH_WCQV_Frontend')) {
        remove_action('woocommerce_after_shop_loop_item',[YITH_WCQV_Frontend::get_instance(),'yith_add_quick_view_button'], 15);
    }
    remove_action( 'woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 7 );
    remove_action( 'woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 30 );
}
add_action( 'woocommerce_shop_loop_item_title', 'cenos_product_show_dokan_sold_by' , 6 );
