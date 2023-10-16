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
        <div class="site-branding">
            <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
        </div><!-- .site-branding -->
        <div class="header-control-wrap header-right-control">
            <?php do_action('cenos_header_control','right_control'); ?>
        </div><!-- .header-right-control -->
    </div>
</div>

