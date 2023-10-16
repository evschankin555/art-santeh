<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Cenos
 */

get_header(); ?>
<?php
$content_layout_class = apply_filters('cenos_content_layout_class' ,'');
$primary_class = ['content-area'];
$is_cenos_sidebar_active = is_cenos_sidebar_active();
$is_cenos_sidebar_active = false;
if ($is_cenos_sidebar_active){
    $primary_class = ['col col-lg-9'];
}
?>
    <div id="content" class="content <?php echo esc_attr($content_layout_class);?>" tabindex="-1">
        <div class="container">
            <?php if ($is_cenos_sidebar_active):
                $sidebar_layout = cenos_sidebar_layout(); ?>
                <div class="row <?php echo esc_attr($sidebar_layout['layout']);?>">
                <?php do_action('cenos_sidebar');?>
            <?php endif;
                do_action( 'cenos_content_top' );?>
                <div id="primary" class="<?php echo implode( ' ', $primary_class ); ?>"><!-- page  -->
                    <main id="main" class="site-main">
                        <?php
                        while ( have_posts() ) : the_post();
                            if ($Cenos_theme->is_maintenance_mode){
                                the_content();
                            } else {
                                get_template_part( 'template-parts/content', 'page' );
                            }
                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                        endwhile; // End of the loop.
                        ?>
                    </main><!-- #main -->
                </div><!-- #primary -->
            <?php if ($is_cenos_sidebar_active):?>
                </div><!-- .row -->
            <?php endif; ?>
        </div><!-- .container -->
    </div><!-- #content -->
<?php
get_footer();
