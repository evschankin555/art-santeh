<?php
/**
 * Template part for displaying header with logo left, menu, and right control ( default ).
 *
 * @since Cenos 1.0.0
 */

$site_nav_align = 'nav-'.cenos_get_option('site_nav_align','left');
?>
<div class="header-content header-main">
    <div class="header-container">
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <nav id="site-navigation" class="main-navigation site-navigation <?php echo esc_attr($site_nav_align);?>">
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
