<?php
$summary_class = apply_filters('cenos_single_product_summary_class',[]);
?>
<div class="product-container"><!-- template: wide-gallery -->
    <div class="product-main wide-gallery">
        <div class="product-gallery">
            <?php
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>
        <?php cenos_shop_control();?>
        <div class="product_summary_wrap">
            <div class="<?php echo implode( ' ', $summary_class ); ?>">
                <?php
                do_action( 'woocommerce_single_product_summary' );
                ?>
            </div><!-- .summary -->
            <div class="product-info summary atc-form">
                <?php
                do_action( 'cenos_single_product_summary_atc_form' );
                ?>
            </div><!-- .summary -->
        </div>
    </div><!-- .product-main -->
    <div class="product-footer">
        <?php
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div><!-- .product-footer -->
</div><!-- .product-container -->
