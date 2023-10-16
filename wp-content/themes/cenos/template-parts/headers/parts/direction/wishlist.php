<?php
if(class_exists('YITH_WCWL') || defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')):
    $my_wishlist_class = ['direction-element','wishlist-box'];
    if (isset($class_tmp) && $class_tmp != ''){
        $my_wishlist_class[] =  $class_tmp;
    }
    //if want show whishlist title uncomment this block
    $my_wishlist_title = cenos_get_option('wishlist_title');
    $wishlist_btn_class = ['wishlist-btn'];
    $wishlist_counter = cenos_get_option('wishlist_counter');
    if ((defined('TINVWL_FVERSION')|| defined('TINVWL_VERSION')) && function_exists('tinv_url_wishlist_default')) {
        $wishlist_link = tinv_url_wishlist_default();
        if ($wishlist_counter){
            $wishlist_count_nb ='0';
            global $wl;
            if (empty($wl)){
                $wl = tinv_wishlist_get();
                $GLOBALS['wl'] = $wl;
            }
            if ($wl){
                $wl_products = new TInvWL_Product($wl);
                $wishlist_count_nb = $wl_products->get_wishlist(array( 'count' => 9999999 ),true);
            }
        }
    } elseif (class_exists('YITH_WCWL')) {
        $wishlist_link = get_permalink( get_option('yith_wcwl_wishlist_page_id') );
        if ($wishlist_counter){
            $wishlist_count_nb = yith_wcwl_count_all_products();
        }
    }
    if ($wishlist_counter){
        $wishlist_btn_class[] = 'has-counter';
    }

    ?>
    <div class="<?php echo esc_attr(implode(' ', $my_wishlist_class)); ?>">
        <a href="<?php echo esc_url($wishlist_link);?>" class="<?php echo esc_attr(implode(' ', $wishlist_btn_class)); ?>">
            <span><?php cenos_svg_icon('heart'); ?></span>
            <span><?php echo cenos_esc_data($my_wishlist_title);?></span>
            <?php if ($wishlist_counter):?>
            <span class="wishlist_counter"><?php echo esc_html($wishlist_count_nb)?></span>
            <?php endif;?>
        </a>
    </div>
<?php endif;?>
