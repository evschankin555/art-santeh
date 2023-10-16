<?php
    $mobile_hd_canvas_style = cenos_get_option('mobile_hd_canvas_style','left');
    $mobile_hd_bottom = cenos_get_option('mobile_hd_bottom');
?>
<aside class="js-offcanvas c-offcanvas is-closed"
       data-offcanvas-options='<?php cenos_esc_data('{"modifiers": "'.$mobile_hd_canvas_style.',overlay"}');?>'
       id="mobile-header-canvas">
    <div class="offcanvas-content">
        <div class="mobile-header-screen-inner">
            <div class="mobile-header-title">
                <h3><?php esc_html_e('Menu','cenos');?></h3>
                <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
                    <?php cenos_svg_icon('close');?>
                    <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                </button>
            </div>
            <nav class="mobile-navigation default-nav-menu">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'mobile',
                    'container'      => null,
                    'depth'          => 3,
                ));
                ?>
            </nav>
            <?php if (!empty($mobile_hd_bottom)) :
                $mobile_element = [];
                if (in_array('mhd_language',$mobile_hd_bottom)){
                    ob_start();
                    cenos_language_switcher( 'up');
                    $mobile_element['mhd_language'] = ob_get_clean();
                }
                if (in_array('mhd_currency', $mobile_hd_bottom)) {
                    ob_start();
                    cenos_currency_switcher( 'up');
                    $mobile_element['mhd_currency'] = ob_get_clean();
                }
                if (in_array('mhd_social' , $mobile_hd_bottom)) :
                    $mhd_social_element = cenos_get_option('mobile_hd_social_element');
                    if (!empty($mhd_social_element)):
                        ob_start();?>
                        <div class="social-links">
                        <?php cenos_social_icon_list($mhd_social_element,true,false);?>
                        </div>
                        <?php $mobile_element['mhd_social'] = ob_get_clean();
                    endif;
                endif;
                if (in_array('mhd_html', $mobile_hd_bottom)) :
                    $mobile_hd_html = cenos_get_option('mobile_hd_html');
                    if (!empty($mobile_hd_html)):
                        ob_start();?>
                        <div class="html-item">
                            <?php echo do_shortcode($mobile_hd_html);?>
                        </div>
                        <?php $mobile_element['mhd_html'] = ob_get_clean();
                    endif;
                endif;
            ?>
                <div class="mobile-header-bottom">
                    <?php
                    foreach ($mobile_hd_bottom as $hd_item) :
                        if (isset($mobile_element[$hd_item])):?>
                            <?php cenos_esc_data($mobile_element[$hd_item]);?>
                        <?php endif;?>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
        </div>
    </div>
</aside>
