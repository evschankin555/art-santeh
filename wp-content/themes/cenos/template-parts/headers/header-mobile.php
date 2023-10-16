<?php
    if (isset($args['header_mobile_layout'])) {
        $header_mobile_layout = $args['header_mobile_layout'];
    } else {
        $header_mobile_layout = 'layout1';
    }
    $header_mobile_class = ['header-mobile','header-mobile-'.$header_mobile_layout];
    $show_sticky_mobile = cenos_get_option('show_sticky_mobile');
    $not_device_hidden_class = cenos_not_device_hidden_class();
    $header_mobile_class[] = $not_device_hidden_class;
    if ($show_sticky_mobile) {
        $header_mobile_class[] = 'header-mobile-sticky fm-header-sticky';
    }
    $show_mobile_icon = cenos_get_option('show_mobile_icon');
    $show_mobile_title = cenos_get_option('show_mobile_title');
    ob_start();
    if ($show_mobile_icon || !$show_mobile_title) {
        $hd_mobile_icon = cenos_get_option('hd_mobile_icon');
        if ($hd_mobile_icon == 'ellipsis') {
            cenos_svg_icon('ellipsis');
        } else {
            cenos_svg_icon('hamburger');
        }
    }
    if ($show_mobile_title) :
        $mobile_title = cenos_get_option('mobile_title'); ?>
        <span class="mobile-title">
            <?php echo esc_html($mobile_title); ?>
        </span>
    <?php endif;
    $mobile_btn_content = ob_get_clean();
?>
<div class="<?php echo esc_attr( implode( ' ', $header_mobile_class ) ); ?>">
    <?php get_template_part( 'template-parts/headers/parts/mobile/header-mobile-'.$header_mobile_layout , null,['mobile_btn_content' => $mobile_btn_content]); ?>
</div>
<?php if ($show_sticky_mobile):?>
    <div class="header-mobile-spacer <?php echo esc_attr($not_device_hidden_class);?>"></div>
<?php
endif;

