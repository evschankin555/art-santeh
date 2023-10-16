<?php
$show_top_bar = cenos_get_option('show_top_bar');
if ($show_top_bar == true) :
    $top_bar_layout_class[] = cenos_device_hidden_class();
    $top_bar_layout = cenos_get_option('top_bar_layout');
    $top_bar_layout_class[] = 'top-bar';
    $top_bar_layout_class[] = 'top-bar-'.$top_bar_layout;
    $default_color = true;
    $page_id = false;
    if (is_page()) {
        $page_id = get_the_ID();
    } elseif (cenos_is_woocommerce_activated() && (is_shop() || is_product_taxonomy())){
        $page_id = get_option('woocommerce_shop_page_id');
    }
    if ($page_id){
        $page_topbar_color = cenos_get_post_meta($page_id,'page_topbar_color',true);
        if (!empty($page_topbar_color) && $page_topbar_color != 'default'){
            $default_color = false;
            $top_bar_layout_class[] = $page_topbar_color;
        }
    }
    if ($default_color){
        $top_bar_layout_class[] = cenos_get_option('top_bar_color');
    }
    if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
        $top_bar_layout_class[] =  $args['class_tmp'];
    }
    ?>
    <div id="topbar" class="<?php echo esc_attr(implode(' ', $top_bar_layout_class)); ?>">
        <div class="header-container">
            <?php get_template_part( 'template-parts/headers/parts/topbar/topbar', $top_bar_layout); ?>
        </div>
    </div>
<?php endif; ?>
