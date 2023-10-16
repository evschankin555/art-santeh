<?php
if (!cenos_is_woocommerce_activated() ) {
    return;
}
$cart_box_class = ['direction-element', 'cart_box'];
if (isset($class_tmp) && $class_tmp != ''){
    $cart_box_class[] =  $class_tmp;
}

$cart_style = 'canvas';
$cart_btn_attr = 'data-offcanvas-trigger="cart-canvas"';
$cart_link = '#.';

$cart_title = '<span class="cart-title">'.cenos_get_option('cart_title').'</span>';

global $woocommerce;

?>
<div class="<?php echo esc_attr(implode(' ', $cart_box_class)); ?>">
    <a href="<?php cenos_esc_data($cart_link);?>" title="<?php esc_attr_e( 'View your shopping cart', 'cenos' ); ?>" class=" cart-btn cart-<?php echo esc_attr($cart_style);?>" <?php cenos_esc_data($cart_btn_attr)?>>
        <span><?php cenos_svg_icon('bag-shop');?></span>
        <?php cenos_esc_data($cart_title); ?>
        <?php cenos_cart_link();?>
    </a>
</div>
