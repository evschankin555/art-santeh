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
$first = true;
$on_device = cenos_on_device();
if ( ! empty( $product_tabs ) ) : ?>
<div class="product-page-accordian" id="accordion_tabs">
	<div class="accordion">
		<?php foreach ( $product_tabs as $key => $product_tab ) :
           if (!$on_device && $first) {
               $collapsed_class = 'show';
               $collapsed_btn_class = '';
           } else {
               $collapsed_class = '';
               $collapsed_btn_class = 'collapsed';
           }?>
		<div class="accordion-item card">
            <button class="btn btn-link <?php echo esc_attr($collapsed_btn_class);?>" type="button" data-toggle="collapse" data-target="#accordion_<?php echo esc_attr($key);?>" aria-expanded="false" aria-controls="accordion_<?php echo esc_attr($key);?>">
                <?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ?>
                <?php cenos_svg_icon('minus', 16, 16, 'icon-not-collapsed' ); ?>
                <?php cenos_svg_icon('plus',16, 16, 'icon-collapsed' ); ?>
            </button>
			<div id="accordion_<?php echo esc_attr($key);?>" data-parent="#accordion_tabs" class="collapse accordion-inner <?php echo esc_attr($collapsed_class);?>">
					<?php call_user_func( $product_tab['callback'], $key, $product_tab ) ?>
			</div>
		</div><!-- accordion-item -->
		<?php
            $first = false;
        endforeach; ?>
	</div>
</div>
<?php endif; ?>
