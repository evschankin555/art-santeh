<?php

/**
 * Cart fragment
 *
 * @see cenos_cart_link_fragment()
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
    add_filter( 'woocommerce_add_to_cart_fragments', 'cenos_cart_link_fragment' );
} else {
    add_filter( 'add_to_cart_fragments', 'cenos_cart_link_fragment' );
}
// Get products by group.
add_action( 'pre_get_posts', 'cenos_products_group_query');
add_action( 'woof_products_query', 'cenos_woof_products_group_query');
add_filter('pre_option_posts_per_page','cenos_woof_posts_per_page');

add_filter( 'loop_shop_columns', 'cenos_loop_shop_columns' );
add_filter( 'loop_shop_per_page', 'cenos_shop_products_per_page', 20 );

add_filter( 'woocommerce_product_single_add_to_cart_text', 'cenos_woo_add_to_cart_text', 10 , 2);
add_filter( 'woo_variation_swatches_archive_add_to_cart_text', 'cenos_get_woo_add_to_cart_text', 99);
add_filter( 'woo_variation_swatches_archive_add_to_cart_select_options', 'cenos_get_select_option_text');
add_filter( 'woocommerce_loop_add_to_cart_args', 'cenos_wvs_pro_loop_add_to_cart_args', 21, 2 );
//wvs_pro_select_options_text

add_action('woocommerce_before_main_content','cenos_sidebar_filter_content',11);


// shop & catalog
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    add_action( 'woocommerce_before_shop_loop', 'cenos_sub_categories_row', 50 );
    add_action('woocommerce_before_shop_loop','cenos_shop_control',30);
    add_filter('woocommerce_post_class','cenos_product_cat_class');
    remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash',10);
    add_action('woocommerce_before_shop_loop_item_title','cenos_show_product_loop_flash_content',10);

    add_filter( 'woocommerce_catalog_orderby','cenos_catalog_orderby_text' );
    add_action('woocommerce_before_quantity_input_field','cenos_quantity_group_buttons');
    //filter content
    add_action('cenos_after_shop_control','cenos_dropdown_fillter_content');
    add_action( 'wp_footer', 'cenos_canvas_fillter_content' );

    //remove default woocommerce_archive_description
    remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description');
// Change the markup of sale flash.
add_filter( 'woocommerce_sale_flash', 'cenos_sale_flash', 10, 3 );
//woo account form_end
add_action('woocommerce_login_form_end','cenos_woocommerce_login_form_end');
add_action('woocommerce_register_form_end','cenos_woocommerce_register_form_end');

//woocommerce_before_cart
//woocommerce_before_checkout_form
add_action( 'woocommerce_before_cart', 'cenos_checkout_step', 1 );
add_action( 'woocommerce_before_checkout_form', 'cenos_checkout_step', 1 );
add_action( 'woocommerce_before_thankyou', 'cenos_checkout_step_complete', 1 );


if (class_exists('YITH_WCWL_Frontend')) {
add_filter('yith_wcwl_add_to_wishlist_icon_html','cenos_yith_wcwl_add_to_wishlist_icon_html');
}
add_filter('default_option_yith_woocompare_button_text','cenos_yith_woocompare_button_text');
add_filter('option_yith_woocompare_button_text','cenos_yith_woocompare_button_text');
add_filter('yith_wcqv_button_label','cenos_yith_wcqv_button_label');

if (defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')){
    add_filter('tinvwl_wishlist_button','cenos_tinvwl_wishlist_button');
}
add_filter('fmfw_disable_product_360degree','cenos_is_disable_product_360degree');
include_once 'woocommerce-catalog-hooks.php';
include_once 'woocommerce-single-hooks.php';
