<?php
if(class_exists('YITH_WCWL') || ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')) || defined('TINVWL_VERSION'))):
    $my_wishlist_class = ['header-element','wishlist-box'];
    if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
        $my_wishlist_class[] =  $args['class_tmp'];
    }
    $wishlist_btn_class = ['wishlist-btn'];

    if ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')) && function_exists('tinv_url_wishlist_default')) {
        $wishlist_link = tinv_url_wishlist_default();
    } elseif (class_exists('YITH_WCWL')) {
        $wishlist_link = get_permalink( get_option('yith_wcwl_wishlist_page_id') );
    }
    ?>
    <div class="<?php echo esc_attr(implode(' ', $my_wishlist_class)); ?>">
        <a href="<?php echo esc_url($wishlist_link);?>" class="<?php echo esc_attr(implode(' ', $wishlist_btn_class)); ?>">
            <?php
            cenos_svg_icon('heart');
            ?>
        </a>
    </div>
<?php endif;?>
