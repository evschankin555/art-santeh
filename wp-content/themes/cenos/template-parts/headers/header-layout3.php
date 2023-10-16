


<div class="header-content header-main">
    <div class="header-container">
        <div class="header-control-wrap header-left-control">
            <?php do_action('cenos_header_control','left_control'); ?>
        </div><!-- .header-left-control -->
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <div class="header-control-wrap header-right-control">
            <?php do_action('cenos_header_control','right_control'); ?>
        </div><!-- .header-right-control -->
    </div>
</div>
<div class="header-content header-bottom">
    <div class="header-container">
        <nav id="site-navigation" class="main-navigation site-navigation">
            <?php
            $menu_class = 'nav-menu';

            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => $menu_class,
                'depth' => 0
            ) ); ?>
        </nav><!-- #site-navigation -->
    </div>
</div>

