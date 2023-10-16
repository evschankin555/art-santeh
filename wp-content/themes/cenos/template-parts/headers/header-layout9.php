<div class="header-content header-main">
    <div class="header-container">
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <div class="header-control-wrap">
            <div class="search_form_wrapper header-element">
                <div class="search_form_content">
                    <?php get_template_part('template-parts/headers/parts/search/search', 'form_content'); ?>
                </div>
                <div class="search_result"></div>
            </div>
        </div><!-- .header-left-control -->
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
        <div class="header-control-wrap header-right-control">
            <?php do_action('cenos_header_control','right_control'); ?>
        </div><!-- .header-right-control -->
    </div>
</div>
