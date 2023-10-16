<div class="container">
    <div class="site-branding">
        <?php get_template_part( 'template-parts/headers/parts/mobile/logo' ); ?>
    </div><!-- .site-branding -->
    <div class="header-control-wrap header-mobile-right-control">
        <?php do_action('cenos_header_control','mobile_right_control'); ?>
    </div><!-- .header-mobile-right-control -->
    <div class="mobile-nav-btn">
        <a href="#." class="js-offcanvas-trigger" data-offcanvas-trigger="mobile-header-canvas">
            <?php isset($args['mobile_btn_content'])? cenos_esc_data($args['mobile_btn_content']): cenos_svg_icon('hamburger');?>
        </a>
    </div>
</div>