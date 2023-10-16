<?php
global $elementor_instance;
$shop_heading_post = cenos_get_option('shop_heading_post');
$wrap_class = ['page-heading-wrap'];
$on_device = cenos_on_device();
if ($on_device){
    get_template_part('template-parts/headers/parts/mobile/header-mobile-shop', null,[]);
}
if (isset($args['classes']) && !empty($args['classes'])) {
    $wrap_class[] = $args['classes'];
}
if (cenos_get_option('shop_heading_divider')){
    $wrap_class[] = 'has-divider';
}
if (!empty($shop_heading_post) && $shop_heading_post!= 'none'):?>
    <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
        <?php
        if( $elementor_instance && cenos_is_built_with_elementor($shop_heading_post)){
            cenos_esc_data($elementor_instance->frontend->get_builder_content_for_display($shop_heading_post));
        } else {
            echo do_shortcode(get_post_field('post_content', $shop_heading_post));
        }
        ?>
    </div>
<?php else:

    $page_type = '';
    if (isset($args['page_type'])){
        $page_type = $args['page_type'];
    }
    $shop_heading_width = cenos_get_option('shop_heading_width');
    $container_div = '';
    $close_container  = '';
    $content_container_class = 'container';
    if ($shop_heading_width == 'container'){
        $container_div = '<div class="container">';
        $close_container = '</div>';
        $content_container_class = '';
    }
    $page_title = woocommerce_page_title(false);
    $shop_heading_bg = cenos_get_option('shop_heading_bg');
    $overlay_div = '';
    if (isset($shop_heading_bg['background-image']) && !empty($shop_heading_bg['background-image'])
        && isset($shop_heading_bg['background-color']) && !empty($shop_heading_bg['background-color']))
    {
        $overlay_div = '<div class="page-heading-overlay">&nbsp;</div>';
    }
    $shop_heading_text_color = cenos_get_option('shop_heading_text_color');
    $shop_heading_align = cenos_get_option('shop_heading_align');
    $header_content_class = ['page-heading-content', 'shop-heading-content',$page_type,'heading-'.$shop_heading_align, 'heading-color-'.$shop_heading_text_color];
    $shop_setting_breadcrumb = cenos_get_option('shop_setting_breadcrumb');
    ?>
    <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
        <?php cenos_esc_data($container_div);?>
        <div class="<?php echo esc_attr(implode(' ', $header_content_class)); ?>">
            <?php cenos_esc_data($overlay_div); ?>
            <div class="<?php echo esc_attr($content_container_class);?> page-heading-container shop-heading-container">
                <h1 class="page-heading-title shop-title"><?php cenos_esc_data($page_title); ?></h1>
                <?php
                    if (is_product_taxonomy()) {
                        $shop_categories_desc = cenos_get_option('shop_categories_desc');
                        if ($shop_categories_desc) {
                            woocommerce_taxonomy_archive_description();
                        }
                    }
                    if ($shop_setting_breadcrumb == 'in') {
                        woocommerce_breadcrumb();
                    }
                    if (!$on_device && cenos_get_option('shop_control_categories') == true){
                        if (cenos_get_option('shop_categories_position') == 'heading') {
                            cenos_shop_categories_carousel();
                        }
                    }
                ?>
            </div>
        </div>
        <?php cenos_esc_data($close_container);?>
    </div>
<?php endif;?>
