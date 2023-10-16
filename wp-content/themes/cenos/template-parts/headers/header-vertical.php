<div class="header-content header-main">
    <div class="header-top">
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <div class="header-control-wrap header-top-control">
            <?php do_action('cenos_header_control','top_control'); ?>
        </div><!-- .header-right-control -->
    </div>
    <div class="search_form_wrapper header-element">
        <div class="search_form_content">
            <?php get_template_part('template-parts/headers/parts/search/search', 'form_content'); ?>
        </div>
    </div>
    <a href="#." class="menu-switch-link">
        <?php cenos_svg_icon('hamburger');?>
        <span class="menu-switch-title"><?php esc_html_e('Menu','cenos');?></span>
    </a>
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
    <div class="header-control-wrap header-bottom-control">
        <?php do_action('cenos_header_control','bottom_control'); ?>
    </div><!-- .header-right-control -->
</div>
<div class="header-vertical-switch sw_close">
    <a href="#." class="header-vertical-switch-btn">
        <span class="icon-open">
            <?php cenos_svg_icon('angle-left');?>
        </span>
    </a>
</div>
