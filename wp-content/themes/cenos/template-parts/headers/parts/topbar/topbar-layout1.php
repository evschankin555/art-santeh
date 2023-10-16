<?php
$topbar_left = cenos_get_option('topbar_left');
if ($topbar_left && !empty($topbar_left)):?>
<div class="top-bar-control top-bar-left-control">
    <?php do_action('cenos_header_control','topbar_left'); ?>
</div>
<?php endif;
$topbar_right = cenos_get_option('topbar_right');
if ($topbar_right && !empty($topbar_right)):?>
<div class="top-bar-control top-bar-right-control">
    <?php do_action('cenos_header_control','topbar_right'); ?>
</div>
<?php endif;
