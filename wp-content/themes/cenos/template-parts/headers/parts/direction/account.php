<?php
if (cenos_is_woocommerce_activated()):
    $my_account_class = ['direction-element','my-account-box'];
    if (isset($class_tmp) && $class_tmp != ''){
        $my_account_class[] =  $class_tmp;
    }
    if(is_account_page()){
        $my_account_class[] = 'active';
    }
    $is_user_logged_in = is_user_logged_in();
    $my_account_title = '';
    $account_link = '#.';
    $account_btn_attr = '';
    $account_btn_class = ['account-btn', 'account-canvas'];

    if ($is_user_logged_in){
        $account_link = get_permalink(  wc_get_page_id( 'myaccount' )  );
    } else {
        $account_btn_class[] = 'js-offcanvas-trigger';
        $account_btn_attr = 'data-offcanvas-trigger="account-canvas"';
    }

    //if want show account title uncomment this block
    if ($is_user_logged_in){
        $my_account_class[] = 'logged';
        $current_user = wp_get_current_user();
        $my_account_title = $current_user->display_name;
    } else {
        $my_account_title = cenos_get_option('account_title');
    }

    ?>
    <div class="<?php echo esc_attr(implode(' ', $my_account_class)); ?>">
        <a href="<?php cenos_esc_data($account_link);?>" class="<?php echo esc_attr(implode(' ', $account_btn_class)); ?>" <?php cenos_esc_data($account_btn_attr)?>>
            <span><?php cenos_svg_icon('user');?></span>
            <?php echo cenos_esc_data($my_account_title);?>
        </a>
    </div>
<?php endif;?>

