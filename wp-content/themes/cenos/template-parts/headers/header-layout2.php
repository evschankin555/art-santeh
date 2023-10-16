<div class="header-content header-main">
    <div class="header-container">
        <div class="hamburger-menu-wrap">
            <?php get_template_part('template-parts/headers/parts/hamburger');?>
        </div>
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <div class="header-control-wrap header-right-control">
            <?php do_action('cenos_header_control','right_control'); ?>
        </div><!-- .header-right-control -->
    </div>
</div>
