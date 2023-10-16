<?php
    $si_element = cenos_get_option('si_element');
    if ($si_element && !empty($si_element)):
        $si_show_icon = cenos_get_option('si_show_icon');
        $si_show_title = cenos_get_option('si_show_title');
        $class_tmp_name = ['header-element','social-icon-box'];
        if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
            $class_tmp_name[] =  $args['class_tmp'];
        }
?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <?php cenos_social_icon_list($si_element,$si_show_icon,$si_show_title)?>
</div>
<?php
endif;
?>
