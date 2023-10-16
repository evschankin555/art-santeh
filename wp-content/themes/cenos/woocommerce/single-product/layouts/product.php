<?php
    $img_col_class = 'col-lg-6';
    if (isset($args['img_col'])) {
        $img_col_class = $args['img_col'];
    }
    $summary_class = apply_filters('cenos_single_product_summary_class',[]);
    $p_main_class = isset($args['woo_single_layout'])? $args['woo_single_layout']:'';
    $p_main_class .= (isset($args['has_background']) && !empty($args['has_background']))? ' has_background':'';
?>
<div class="product-container"><!-- template: product default -->
    <div class="product-main <?php echo esc_attr($p_main_class);?>">
        <?php do_action('cenos_before_product_main_content');?>
        <div class="product-content">
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
        </div>
    </div><!-- .product-main -->

    <div class="product-footer">
        <?php
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div><!-- .product-footer -->
</div><!-- .product-container -->
