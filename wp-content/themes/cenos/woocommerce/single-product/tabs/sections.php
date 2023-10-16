<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
<div class="product-page-sections">
	<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
	<div class="product-section">
            <div class="section-tab-header">
                 <h5><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ?></h5>
            </div>

            <div class="section-tab-content">
                <div class="panel entry-content">
                    <?php call_user_func( $product_tab['callback'], $key, $product_tab ) ?>
                </div>
            </div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
