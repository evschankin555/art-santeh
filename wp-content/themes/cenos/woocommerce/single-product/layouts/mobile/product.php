<?php
    $summary_class = apply_filters('cenos_single_product_summary_class',[]);
    $template_class = ['product-container'];
    $template_class[] = isset($args['temp_class'])? 'mobile-single-'.$args['temp_class'] : 'mobile-single-default';
?>
<div class="<?php echo implode(' ',$template_class);?>"><!-- template: product default -->
    <div class="product-main">
        <div class="product-content">
            <div class="product-gallery">
                <?php
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>
            <div class="<?php echo implode( ' ', $summary_class ); ?>">
                <?php
                do_action( 'woocommerce_single_product_summary' );
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
