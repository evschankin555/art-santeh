<?php

$announcement_class = ['cenos-announcement-box','layout2'];
if (isset($args['position']) && $args['position'] != ''){
    $announcement_class[] = 'announcement-'.$args['position'];
}
if (isset($args['classes']) && $args['classes'] != ''){
    $announcement_class[] = $args['classes'];
}
$announcement_close_btn = cenos_get_option('announcement_close_btn');
$announcement_msg = cenos_get_option('announcement_msg');
$show_announcement_countdown = cenos_get_option('show_announcement_countdown');
if ($show_announcement_countdown == true){
    $announcement_date =  cenos_get_option('announcement_date');
    $announcement_after_msg = cenos_get_option('announcement_after_msg');
}
$show_announcement_btn = cenos_get_option('show_announcement_btn');
if ($show_announcement_btn == true){
    $announcement_btn_text = cenos_get_option('announcement_btn_text');
    $announcement_btn_link = cenos_get_option('announcement_btn_link');
    $btn_target = '';
    $announcement_btn_target = cenos_get_option('announcement_btn_target');
    if ($announcement_btn_target == true){
        $btn_target = 'target="_blank"';
    }
}
?>
<div class="<?php echo esc_attr(implode(' ', $announcement_class)); ?>">
    <?php if ($announcement_close_btn == true):
        $announcement_dismiss = cenos_get_option('announcement_dismiss');
        $dismiss_data = '';
        if ($announcement_dismiss) {
            $dismiss_data = 'data-dismiss="true"';
        }
    ?>
        <button type="button" class="close" <?php cenos_esc_data($dismiss_data);?>><?php cenos_svg_icon('close') ?></button>
    <?php endif;?>
    <div class="announcement-msg">
        <?php echo do_shortcode($announcement_msg);?>
    </div>
    <?php if ($show_announcement_countdown == true):?>
        <div class="fm-countdown-time" data-countdown="<?php cenos_esc_data($announcement_date);?>">

        </div>
        <?php if ($announcement_after_msg != ''):?>
        <div class="announcement-msg-after-time">
            <?php echo do_shortcode($announcement_after_msg);?>
        </div>
        <?php endif;?>
    <?php endif;?>
    <?php if ($show_announcement_btn == true):?>
        <div class="announcement_btn">
            <a class="<?php echo cenos_button_class(); ?>" href="<?php echo esc_url($announcement_btn_link);?>" <?php cenos_esc_data($btn_target);?> ><?php echo esc_html($announcement_btn_text);?></a>
        </div>
    <?php endif;?>
</div>
