<?php
$class_tmp_name = ['header-element','hamburger-box'];
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $class_tmp_name[] =  $args['class_tmp'];
}
$show_hm_icon = cenos_get_option('show_hm_icon');
$show_hm_title = cenos_get_option('show_hm_title');
?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <a href="#." class="hamburger-menu js-offcanvas-trigger" data-offcanvas-trigger="hamburger-canvas">
        <?php if ($show_hm_icon || !$show_hm_title) {
            $hm_icon = cenos_get_option('hm_icon');
            if ($hm_icon == 'ellipsis') {
                cenos_svg_icon('ellipsis');
            } else {
                cenos_svg_icon('hamburger');
            }
        }
        if ($show_hm_title):
            $hm_title = cenos_get_option('hm_title'); ?>
            <span class="hamburger-title">
            <?php echo esc_html($hm_title);?>
            </span>
        <?php endif;?>
    </a>
</div>
