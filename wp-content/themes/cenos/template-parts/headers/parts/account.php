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
    $show_account_title = cenos_get_option('show_account_title');
    $show_account_icon = cenos_get_option('show_account_icon');
    $account_link = '#.';

    $account_style = cenos_get_option('account_style');
    $account_btn_attr = '';
    $account_btn_class = ['account-btn', 'account-'.$account_style];

    if ($is_user_logged_in){
        $account_link = get_permalink(  wc_get_page_id( 'myaccount' )  );
    } else {
        switch ($account_style){
            case 'modal':
                $account_btn_attr = 'data-toggle="modal" data-target="#fm-account-modal"';
                break;
            case 'canvas':
                $account_btn_class[] = 'js-offcanvas-trigger';
                $account_btn_attr = 'data-offcanvas-trigger="account-canvas"';
                break;
            default:
                $account_link = get_permalink(  wc_get_page_id( 'myaccount' )  );
                break;
        }
    }
    if ($show_account_title == true){
        if ($is_user_logged_in){
            $my_account_class[] = 'logged';
            $show_username = cenos_get_option('show_user_name');
            if ($show_username == true){
                $current_user = wp_get_current_user();
                $my_account_title = $current_user->display_name;
            } else {
                $my_account_title = esc_html__('My account','cenos');
            }
        } else {
            $my_account_title = cenos_get_option('account_title');
        }
    }
    ?>
    <div class="<?php echo esc_attr(implode(' ', $my_account_class)); ?>">
        <a href="<?php cenos_esc_data($account_link);?>" class="<?php echo esc_attr(implode(' ', $account_btn_class)); ?>" <?php cenos_esc_data($account_btn_attr)?>>
            <?php
            if ($show_account_icon == true || $show_account_title == false){
                cenos_svg_icon('user');
            }
            if (!empty($my_account_title)){
                echo '<span>'.esc_html($my_account_title).'</span>';
            }

            ?>
        </a>
    </div>
<?php endif;?>

