<?php
if (!cenos_is_woocommerce_activated() ) {
    return;
}
$cart_box_class = ['header-element', 'cart_box'];
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $cart_box_class[] =  $args['class_tmp'];
}

$cart_style = 'canvas';
$cart_btn_attr = 'data-offcanvas-trigger="cart-canvas"';
$cart_link = '#.';

global $woocommerce;

?>
<div class="<?php echo esc_attr(implode(' ', $cart_box_class)); ?>">
    <a href="<?php cenos_esc_data($cart_link);?>" title="<?php esc_attr_e( 'View your shopping cart', 'cenos' ); ?>" class=" cart-btn cart-<?php echo esc_attr($cart_style);?>" <?php cenos_esc_data($cart_btn_attr)?>>
        <?php
        cenos_svg_icon('bag-shop');
        ?>
        <?php cenos_cart_link();?>
    </a>
</div>
