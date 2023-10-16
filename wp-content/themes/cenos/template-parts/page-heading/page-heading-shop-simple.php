<?php
global $elementor_instance;
$shop_heading_post = cenos_get_option('shop_heading_post');
$wrap_class = ['page-heading-wrap', 'shop-heading-simple'];
$on_device = cenos_on_device();
if ($on_device){
    get_template_part('template-parts/headers/parts/mobile/header-mobile-shop', null,[]);
}
if (isset($classes) && !empty($classes)) {
    $wrap_class[] = $classes;
}
if (!empty($shop_heading_post) && $shop_heading_post != 'none'):?>
    <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
        <?php
        if( $elementor_instance && cenos_is_built_with_elementor($shop_heading_post)){
            cenos_esc_data($elementor_instance->frontend->get_builder_content_for_display($shop_heading_post));
        } else {
            echo do_shortcode(get_post_field('post_content', $shop_heading_post));
        } ?>
    </div>
<?php else:
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
    $shop_heading_simple_height = cenos_get_option('shop_heading_simple_height');
    $shop_heading_text_color = cenos_get_option('shop_heading_text_color');
    $header_content_class = ['page-heading-content', 'shop-heading-content',$page_type, 'heading-color-'.$shop_heading_text_color, 'heading-simple-'.$shop_heading_simple_height];

    ?>
    <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
        <?php cenos_esc_data($container_div);?>
        <div class="<?php echo esc_attr(implode(' ', $header_content_class)); ?>">
            <div class="<?php echo esc_attr($content_container_class);?> page-heading-container shop-heading-container">
                <h1 class="page-heading-title shop-title"><?php cenos_esc_data($page_title); ?></h1>
                <?php woocommerce_breadcrumb();?>
            </div>
        </div>
        <?php cenos_esc_data($close_container);?>
    </div>
<?php endif;?>
