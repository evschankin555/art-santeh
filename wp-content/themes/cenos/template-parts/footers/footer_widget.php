<?php
if (!isset($footer_widget)){
    return;
}
$widget_part = str_replace('footer_widget','',$footer_widget);
if ($widget_part == '1' || $widget_part == '2'):
    if (is_active_sidebar('sb-footer-'.$widget_part)):
    ?>
    <div class="footer-section footer_widget <?php echo esc_attr($footer_widget);?>">
        <div class="footer-container">
        <?php
            dynamic_sidebar('sb-footer-'.$widget_part);
        ?>
        </div>
    </div>
<?php endif;
endif;
