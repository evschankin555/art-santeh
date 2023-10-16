<?php
    $contact_info_element = cenos_get_option('contact_info_element');
    if ($contact_info_element && !empty($contact_info_element)):
        $contact_info_show_icon = cenos_get_option('contact_info_show_icon');
        $contact_info_show_title = cenos_get_option('contact_info_show_title');
        $class_tmp_name = ['header-element','contact-info-box'];
        if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
            $class_tmp_name[] =  $args['class_tmp'];
        }?>
        <div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
            <?php foreach ($contact_info_element as $ct):
                $ct_info = cenos_get_option($ct);
                if ($ct_info):
                    if ($contact_info_show_icon == true){
                        $icon_name = apply_filters('cenos_contact_info_icons', [
                            'ct_phone'=> 'phone',
                            'ct_2nd_phone'=> 'mobile',
                            'ct_email'=> 'email',
                            'ct_location'=> 'location',
                            'ct_open_hours'=> 'clock',
                        ]);
                        cenos_svg_icon($icon_name[$ct]);
                    }
                    if ($contact_info_show_title == true && $ct != 'ct_open_hours'):?>
                        <span class="ct-title"><?php echo cenos_get_option($ct.'_label');?></span>
                    <?php endif;?>
                    <span class="ct-info"><?php echo esc_html($ct_info);?></span>
                <?php endif;?>
            <?php endforeach;?>
        </div>
<?php endif; ?>

