<?php
$show_categories = false;
?>
<div class="before-shop-control">
<?php
$shop_setting_breadcrumb = cenos_get_option('shop_setting_breadcrumb');
$shop_heading_layout = cenos_get_option('shop_heading_layout');
if ($shop_setting_breadcrumb == 'out' && $shop_heading_layout != 'simple'){
    woocommerce_breadcrumb();
}
$on_device = cenos_on_device();
if (!$on_device && cenos_get_option('shop_control_categories') == true){
    if (cenos_get_option('shop_categories_position') == 'control' || cenos_get_option('shop_heading_layout') == 'simple') {
        cenos_shop_categories_carousel();
    }
    $show_categories = true;
}

$shop_control_quick_search = cenos_get_option('shop_control_quick_search');
if (!$on_device && $shop_control_quick_search) {
    cenos_shop_quick_search();
}?>
</div><!--//before-shop-control-->
<div class="main-shop-control">
<?php
$shop_control_filter = cenos_get_option('shop_control_filter');
if (!$on_device && $shop_control_filter){
    cenos_shop_filter_button();
}

$shop_control_woo_result_count = cenos_get_option('shop_control_woo_result_count');
if ($shop_control_woo_result_count){
    woocommerce_result_count();
}

$shop_control_woo_short = cenos_get_option('shop_control_woo_short');
if ($shop_control_woo_short){
    woocommerce_catalog_ordering();
}
if (!$on_device && !$show_categories){
    cenos_shop_product_tabs();
}

do_action('cenos_main_shop_control'); ?>
</div><!--//main-shop-control-->
<div class="after-shop-control">
    <?php do_action('cenos_after_shop_control');?>
</div>
