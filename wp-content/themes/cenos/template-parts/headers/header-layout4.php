<?php
/**
 * Template part for displaying header with logo left, menu, and right control ( default ).
 *
 * @since Cenos 1.0.0
 */

?>

<div class="header-content header-main">
    <div class="header-container">
        <div class="header-control-wrap header-left-control">
            <?php do_action('cenos_header_control','left_control'); ?>
        </div><!-- .header-left-control -->

        <nav id="site-navigation" class="main-navigation site-navigation logo-center-menu">
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

