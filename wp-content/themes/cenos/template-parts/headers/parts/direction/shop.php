<?php
$class_tmp_name = ['direction-element','shop-link'];
if (isset($class_tmp) && $class_tmp != ''){
    $class_tmp_name[] =  $class_tmp;
}
if (!is_shop()){
    $shop_page_id = get_option('woocommerce_shop_page_id');
    $shop_link = get_permalink($shop_page_id);
} else {
    $class_tmp_name[] =  'active';
    $shop_link = '#.';
}

?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <?php printf('<a href="%1$s" title="%2$s"><span>%3$s</span> %2$s</a>',
        $shop_link,
        esc_html__('Shop','cenos'),
        cenos_get_svg_icon('market')
    );
    ?>
</div>