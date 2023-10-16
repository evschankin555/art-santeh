<?php
$shop_id = get_option( 'woocommerce_shop_page_id' );
$shop_link = get_permalink($shop_id);
?>
<div class="single_product_header">
    <a href="<?php echo esc_url($shop_link);?>" title="<?php esc_attr_e('Shop','cenos');?>">
        <?php cenos_svg_icon('angle-left');?>
    </a>
</div>