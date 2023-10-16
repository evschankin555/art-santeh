<?php
if (cenos_is_woocommerce_activated()):
    $my_account_class = ['header-element','my-account-box'];
    if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
        $my_account_class[] =  $args['class_tmp'];
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
    ?>
    <div class="<?php echo esc_attr(implode(' ', $my_account_class)); ?>">
        <a href="<?php cenos_esc_data($account_link);?>" class="<?php echo esc_attr(implode(' ', $account_btn_class)); ?>" <?php cenos_esc_data($account_btn_attr)?>>
            <?php
            cenos_svg_icon('user');
            ?>
        </a>
    </div>
<?php endif;?>
