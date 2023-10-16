<?php
$class_tmp_name = ['direction-element','mobile-nav-btn'];
$mobile_title = cenos_get_option('mobile_title'); ?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <a href="#." class="js-offcanvas-trigger" data-offcanvas-trigger="mobile-header-canvas">
        <span><?php cenos_svg_icon('ellipsis');?></span>
        <?php cenos_esc_data($mobile_title);?>
    </a>
</div>