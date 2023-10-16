<?php
if (!cenos_is_woocommerce_activated() ) {
    return;
}
$cart_box_class = ['header-element', 'cart_box'];

if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $cart_box_class[] =  $args['class_tmp'];
}
$cart_style = cenos_get_option('cart_style');
$cart_btn_attr = '';
$cart_link = '#.';
if ($cart_style == 'canvas'){
    $cart_btn_attr = 'data-offcanvas-trigger="cart-canvas"';
} elseif ($cart_style == 'dropdown'){
    $cart_box_class[] = 'hover_dropdown_wrapper';
} else {
    $cart_link = wc_get_cart_url();
}
$show_cart_icon = cenos_get_option('show_cart_icon');
$show_cart_title = cenos_get_option('show_cart_title');
$cart_title = '';
if ($show_cart_title == true){
    $cart_title = '<span class="cart-title">'.cenos_get_option('cart_title').'</span>';
}
global $woocommerce;

?>
<div class="<?php echo esc_attr(implode(' ', $cart_box_class)); ?>">
    <a href="<?php cenos_esc_data($cart_link);?>" title="<?php esc_attr_e( 'View your shopping cart', 'cenos' ); ?>" class=" cart-btn cart-<?php esc_attr($cart_style);?>" <?php cenos_esc_data($cart_btn_attr)?>>
        <?php if ($show_cart_icon == true || $show_cart_title == false){
            cenos_svg_icon('bag-shop');
        }
        cenos_esc_data($cart_title);
        ?>
        <?php cenos_cart_link();?>
    </a>
    <?php if ($cart_style == 'dropdown'):
        $show_cart_empty = cenos_get_option('show_cart_empty');
        $cart_contents_count = intval( $woocommerce->cart->get_cart_contents_count() );
        ?>
        <?php if ($show_cart_empty == true || $cart_contents_count > 0):?>
        <div class="cart_box_content dropdown_content">
            <div class="widget_shopping_cart_content"></div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
