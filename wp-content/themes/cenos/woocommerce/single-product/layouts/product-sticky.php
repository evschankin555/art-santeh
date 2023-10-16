<?php
$img_col_class = 'col-lg-6';
if (isset($img_col)) {
    $img_col_class = $img_col;
}
$summary_class = apply_filters('cenos_single_product_summary_class',[]);
?>
<div class="product-container"><!-- template: product-sticky -->
    <div class="product-main sticky-layout">
        <div class="row content-row">
            <div class="product-gallery col-12 <?php echo esc_attr($img_col_class);?>">
                <?php
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>
            <div class="<?php echo implode( ' ', $summary_class ); ?>">
                <div class="sticky-wrapper">
                    <div class="sticky-inner">
                    <?php
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                    </div>
                </div>
            </div><!-- .summary -->
        </div><!-- .row -->
    </div><!-- .product-main -->

    <div class="product-footer">
        <?php
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div><!-- .product-footer -->
</div><!-- .product-container -->
