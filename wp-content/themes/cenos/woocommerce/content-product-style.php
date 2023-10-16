<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
// Ensure visibility.
if (is_null($product) || empty( $product ) || ! $product->is_visible() ) {
    return;
}
$shop_list_style = cenos_get_option('shop_list_style');
$shop_list_style = wc_get_loop_prop('shop_list_style', $shop_list_style);
$product_item_style = cenos_get_option('product_item_style');
$sc = wc_get_loop_prop('is_fmtpl_widget', false);
if ($sc){
    $shop_list_style = 'grid';
}
$product_item_style = wc_get_loop_prop('product_item_style', $product_item_style);
$top_infos_wrapper = false;
$title_wrapper = false;
$wishlist_on_title = false;
$after_item_title_price = false;
$after_item_title_rating = false;
$loop_excerpt = false;
$button_wrapper = false;
$item_cart_btn = false;
$item_compare_btn = false;
$item_quickview_btn = false;
$item_wishlist_btn = false;
if ($shop_list_style == 'list'){
    $after_item_title_rating = true;
    $loop_excerpt = true;
    $button_wrapper = true;
    $item_cart_btn = true;
    $item_compare_btn = true;
    $item_quickview_btn = true;
    $item_wishlist_btn = true;
}
if ($shop_list_style == 'list2'){
    $title_wrapper = true;
    $wishlist_on_title = true;
    $after_item_title_rating = true;
    $after_item_title_price = true;
    $loop_excerpt = true;
    $button_wrapper = true;
    $item_cart_btn = true;
    $item_compare_btn = true;
    $item_quickview_btn = true;
}
switch ($product_item_style) {
    case 'slider':
        $top_infos_wrapper = true;
        $title_wrapper = true;
        $wishlist_on_title = true;
        $button_wrapper = true;
        $item_cart_btn = true;
        $item_compare_btn = false;
        $after_item_title_price = true;
        break;
    case 'border':
    case 'style-2':
    case 'style-4':
    case 'style-7':
        $after_item_title_rating = true;
        $after_item_title_price = true;
        break;
    case 'style-1':
        $top_infos_wrapper = true;
        $item_cart_btn = true;
        $after_item_title_price = true;
        break;
    case 'style-3':
        $after_item_title_rating = true;
        $after_item_title_price = true;
        $item_cart_btn = true;
        break;
    case 'style-5':
        $after_item_title_rating = true;
        $title_wrapper = true;
        $wishlist_on_title = true;
        break;
    case 'style-6':
        $top_infos_wrapper = true;
        $title_wrapper = true;
        break;
    case 'style-clean':
        $top_infos_wrapper = true;
        $title_wrapper = true;
        $wishlist_on_title = true;
        $after_item_title_price = true;
        break;
    default:
        break;
}
$product_item_rating = cenos_get_option('product_item_rating');
$product_item_rating = wc_get_loop_prop('product_item_rating', $product_item_rating);
if (!$product_item_rating){
    $after_item_title_rating = false;
}

?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked cenos_product_item_wrapper_open - 1
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked cenos_show_product_loop_flash_content - 10
	 * @hooked cenos_template_loop_product_thumbnail - 10
     * @hooked cenos_product_item_infos_wrapper_open PHP_INT_MAX
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

    if ($top_infos_wrapper){
        //slider style-1 style-6
        cenos_product_item_top_infos_wrapper_open();
    }
    cenos_product_item_category();//all style
    if ($top_infos_wrapper){
        //slider style-1 style-6
        if ($product_item_rating) {
            woocommerce_template_loop_rating();
        }
        cenos_div_wrapper_close();
    }
    if ($title_wrapper){
        //slider style-5 style-6, list2
        cenos_product_item_title_wrapper_open();
    }
	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked cenos_product_item_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );
    if ($title_wrapper){
        //slider style-5 style-6 list2
        if ($wishlist_on_title){
            //slider style-5 list2
            cenos_show_loop_wishlist_btn();
        } else {
            //style-6
            woocommerce_template_loop_price();
        }
        cenos_div_wrapper_close();
    }

    if ($after_item_title_rating){
        //border, style 2, style 3, style 4, style 5, list, list2
        woocommerce_template_loop_rating();
    }
    if ($after_item_title_price){
        //slider, border, style 1,style 2, style 3, style 4, list2
        woocommerce_template_loop_price();
    }
	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	if ($loop_excerpt){
	    //list, list2
        cenos_woocommerce_shop_loop_excerpt();
    }

    if ($shop_list_style == 'list'){
        cenos_div_wrapper_close();
    }

    if ($button_wrapper) {
        //slider, list2, list
        cenos_product_item_button_wrapper_open();
    }
    if ($shop_list_style == 'list'){
        woocommerce_template_loop_price();
    }

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
    if ($item_cart_btn){
        //slider, list2, list, style 1, style 3
        woocommerce_template_loop_add_to_cart();
    }
    if ($item_wishlist_btn){
        //list
        cenos_show_loop_wishlist_btn();
    }
    if ($item_compare_btn){
        //slider, list2, list
        cenos_show_compare_btn();
    }
    if ($item_quickview_btn) {
        //list2, list
        cenos_show_quickview_btn();
    }
    if ($button_wrapper) {
        //slider, list2, list
        cenos_div_wrapper_close();
    }

    if ($shop_list_style != 'list'){
        cenos_div_wrapper_close();
    }
    if ($product_item_style != 'default'){
        cenos_div_wrapper_close();
    }
	?>
</li>
