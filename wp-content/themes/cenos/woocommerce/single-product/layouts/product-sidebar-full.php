<?php
$img_col_class = 'col-lg-6';
if (isset($img_col)) {
    $img_col_class = $img_col;
}
$summary_class = apply_filters('cenos_single_product_summary_class',[]);
ob_start();
if (is_active_sidebar( 'product-sidebar-full-height' ) ) {
    dynamic_sidebar('product-sidebar-full-height');
} elseif (is_active_sidebar( 'product-sidebar' ) ) {
    dynamic_sidebar('product-sidebar');
}
$product_sidebar_html = ob_get_clean();
$product_content_class = 'product-content';
if (!empty($product_sidebar_html)){
    $product_content_class .= ' col-lg-9';
}
?>
<div class="product-container"><!-- template: product-sidebar-full -->
    <div class="product-main <?php echo esc_attr(isset($woo_single_layout)? $woo_single_layout:'');?>">
        <div class="row">
            <div class="col <?php echo esc_attr($product_content_class);?>">
                <div class="row content-row">
                    <div class="product-gallery col-12 <?php echo esc_attr($img_col_class);?>">
                        <?php
                        do_action( 'woocommerce_before_single_product_summary' );
                        ?>
                    </div>

                    <div class="<?php echo implode( ' ', $summary_class ); ?>">
                        <?php
                        do_action( 'woocommerce_single_product_summary' );
                        ?>

                    </div><!-- .summary -->
                </div><!-- .row -->
                <div class="product-footer">
                    <?php
                    do_action( 'woocommerce_after_single_product_summary' );
                    ?>
                </div><!-- .product-footer -->
            </div>
            <?php
            ob_start();
            do_action('cenos_before_product_sidebar');
            $cenos_before_product_sidebar = ob_get_clean();
            if (is_active_sidebar( 'product-sidebar-full-height' ) || is_active_sidebar( 'product-sidebar' ) || !empty($fami_before_product_sidebar)):
                ?>
                <div id="product-sidebar" class="col col-lg-3 product-sidebar">
                    <?php
                    if (!empty($cenos_before_product_sidebar)){
                        cenos_esc_data($cenos_before_product_sidebar);
                    }
                    if (!empty($product_sidebar_html)){
                        cenos_esc_data($product_sidebar_html);
                    }
                    ?>
                </div>
            <?php endif;?>
        </div>
    </div><!-- .product-main -->
</div><!-- .product-container -->
