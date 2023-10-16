<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$woo_single_show_sku = cenos_get_option('woo_single_show_sku');
$woo_single_show_cat = cenos_get_option('woo_single_show_cat');
$woo_single_show_tag = cenos_get_option('woo_single_show_tag');
global $product;
$sku = $product->get_sku();
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ($woo_single_show_sku && wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
2
		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'cenos' ); ?> <span class="sku"><?php echo ( !empty($sku ) ? $sku : esc_html__( 'N/A', 'cenos' )); ?></span></span>

	<?php endif; ?>
    <?php if ($woo_single_show_cat){
            echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'cenos' ) . ' ', '</span>' );
        }
    ?>

	<?php if ($woo_single_show_tag){
            echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'cenos' ) . ' ', '</span>' );
        }
    ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
3
</div>
