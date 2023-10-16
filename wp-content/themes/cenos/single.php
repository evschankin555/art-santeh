<?php
/**
 * The template for displaying all single posts.
 *
 * @package Cenos
 */

get_header(); ?>
    <?php
    $content_layout_class = apply_filters('cenos_content_layout_class' ,'');
    $primary_class = ['content-area'];
    $is_cenos_sidebar_active = is_cenos_sidebar_active();
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
                <div id="primary" class="<?php echo implode( ' ', $primary_class ); ?>"><!-- #single -->
                    <main id="main" class="site-main">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            do_action( 'cenos_single_post_before' );
                            get_template_part( 'template-parts/content', 'single' );
                            do_action( 'cenos_single_post_after' );

                        endwhile; // End of the loop.
                        if ( ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
                            ?>

                            <div class="comments-wrapper section-inner">

                                <?php comments_template(); ?>

                            </div><!-- .comments-wrapper -->

                            <?php
                        }
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
