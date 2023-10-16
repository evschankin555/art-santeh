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
$product_item_style = cenos_get_option('mobile_product_items_style');
$after_item_title_rating = false;
$price_wrapper = false;
$show_cat = false;
$show_swatch = false;
$item_wishlist_btn = false;

switch ($product_item_style) {
    case 'layout_m_01':
        $after_item_title_rating = true;
        break;
    case 'layout_m_02':
        $show_cat = true;
        $price_wrapper = true;
        $show_swatch = true;
        break;
    default:
        break;
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
    if ($show_cat){
        cenos_product_item_category();
    }


	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked cenos_product_item_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

    if ($after_item_title_rating){
        //border, style 2, style 3, style 4, style 5, list, list2
        woocommerce_template_loop_rating();
    }

    if ($show_swatch && function_exists('wvs_pro_archive_variation_template')){
        wvs_pro_archive_variation_template();
    }

    if ($price_wrapper){
        cenos_product_item_price_wrapper_open();
    }
    woocommerce_template_loop_price();
    if ($price_wrapper){
        cenos_show_loop_wishlist_btn();
        cenos_div_wrapper_close();
    }
    if ($product_item_style != 'default'){
        cenos_div_wrapper_close();
    }
	?>
</li>
