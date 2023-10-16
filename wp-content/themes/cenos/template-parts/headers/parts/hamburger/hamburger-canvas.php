<?php
    $hm_logo = cenos_get_option('hm_logo');
    $hm_content_type = cenos_get_option('hm_content_type');
    $hm_canvas_style = cenos_get_option('hm_canvas_style');
    $hm_bottom_element = cenos_get_option('hm_bottom_element');
    $hm_has_bottom_class = !empty($hm_bottom_element) ? ' has_bottom':'';
?>

<aside class="js-offcanvas c-offcanvas is-closed"
       data-offcanvas-options='<?php cenos_esc_data('{"modifiers": "'.$hm_canvas_style.',overlay"}');?>'
       id="hamburger-canvas">
    <div class="offcanvas-content">
        <div class="hamburger-screen-inner<?php echo esc_attr($hm_has_bottom_class);?>">
            <div class="hamburger-screen-head">
                <?php if ($hm_logo == true):?>
                    <div class="hm-logo">
                        <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
                    </div>
                <?php endif;?>
                <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
                    <?php cenos_svg_icon('close');?>
                    <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                </button>
            </div>
            <div class="hamburger-screen-content">
                <?php if ($hm_content_type == 'menu'):?>
                    <nav class="hamburger-navigation default-nav-menu">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'hamburger',
                            'container'      => null,
                            'depth'          => 3,
                        ) );
                        ?>
                    </nav>
                <?php else:
                    dynamic_sidebar('hamburger-sidebar');
                endif;?>
            </div>
            <?php if (!empty($hm_bottom_element)):
                $hm_element = [];
                if (in_array('hm_language',$hm_bottom_element)){
                    ob_start();
                    cenos_language_switcher( 'up');
                    $hm_element['hm_language'] = ob_get_clean();
                }
                if (in_array('hm_currency', $hm_bottom_element)) {
                    ob_start();
                    cenos_currency_switcher( 'up');
                    $hm_element['hm_currency'] = ob_get_clean();
                }
                if (in_array('hm_social' , $hm_bottom_element)) :
                    $hm_social_element = cenos_get_option('hm_social_element');
                    if (!empty($hm_social_element)):
                        ob_start();?>
                        <div class="social-links">
                            <?php cenos_social_icon_list($hm_social_element,true,false);?>
                        </div>
                        <?php $hm_element['hm_social'] = ob_get_clean();
                    endif;
                endif;
                if (in_array('hm_html', $hm_bottom_element)) :
                    $hm_html = cenos_get_option('hm_html');
                    if (!empty($hm_html)):
                        ob_start();?>
                        <div class="html-item">
                            <?php echo do_shortcode($hm_html);?>
                        </div>
                        <?php $hm_element['hm_html'] = ob_get_clean();
                    endif;
                endif;
                ?>
                <div class="hamburger-bottom">
                    <?php
                    foreach ($hm_bottom_element as $hm_item) :
                        if (isset($hm_element[$hm_item])):?>
                            <?php cenos_esc_data($hm_element[$hm_item]);?>
                        <?php endif;?>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
        </div>
    </div>
</aside>
