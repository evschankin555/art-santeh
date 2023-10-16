<?php
$parent_link = '';
$home_lnk = home_url();
$parent_class = ['parent-term-link'];
$drop_down_content = '';
if (is_shop()){
    $parent_link = $home_lnk;
    $parent_title = esc_html__('Shop','cenos');
} elseif (is_product_taxonomy()) {
    $queried = get_queried_object();
    if (isset($queried->taxonomy) && $queried->taxonomy == 'product_cat' && $queried->parent) {
        $parent_tearm = get_term( $queried->parent );
        $parent_title = $parent_tearm->name;
        $parent_link = get_term_link($parent_tearm);
    } else {
        $shop_page_id = get_option( 'woocommerce_shop_page_id' );
        if ($shop_page_id){
            $parent_link = get_permalink($shop_page_id);
            $parent_title = $queried->name;
        }
    }
}
?>
<div class="woo_shop_header">
    <?php printf('<a href="%1$s" class="%2$s" title="%3$s">%4$s</a>',
        $parent_link,
        implode(' ',$parent_class),
        $parent_title,
        cenos_get_svg_icon('angle-left')
    );?>
    <?php cenos_shop_categories_dropdown();?>
    <a class="go_home" href="<?php echo esc_url( home_url() ) ?>" title="<?php echo esc_attr(get_bloginfo( 'name' ));?>">
        <?php cenos_svg_icon('home-fill');?>
    </a>
</div>