<?php
$form_mode = '';
if (isset($args['form_mode']) && $args['form_mode'] != ''){
    $form_mode =  $args['form_mode'].'-';
}
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#<?php echo esc_attr($form_mode);?>fm-login" role="tab" aria-controls="<?php echo esc_attr($form_mode);?>fm-login" aria-selected="true"><?php esc_html_e('Login','cenos');?></a>
    </li>
    <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#<?php echo esc_attr($form_mode);?>fm-register" role="tab" aria-controls="<?php echo esc_attr($form_mode);?>fm-register" aria-selected="false"><?php esc_html_e('Register','cenos');?></a>
    </li>
    <?php endif;?>
</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="<?php echo esc_attr($form_mode);?>fm-login" role="tabpanel">
        <?php get_template_part('woocommerce/myaccount/fm-login');?>
    </div>
    <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
    <div class="tab-pane fade" id="<?php echo esc_attr($form_mode);?>fm-register" role="tabpanel">
        <?php get_template_part('woocommerce/myaccount/fm-register');?>
    </div>
    <?php endif;?>
</div>
