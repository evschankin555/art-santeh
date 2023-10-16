<?php
/**
 * The template for displaying search results pages.
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
                <div id="primary" class="<?php echo implode( ' ', $primary_class ); ?>"> <!-- #search -->
                    <main id="main" class="site-main">
                        <?php if ( have_posts() ) : the_post();?>
                            <header class="page-heading">
                                <h1 class="page-title">
                                    <?php
                                    /* translators: %s: search term */
                                    printf( esc_attr__( 'Search Results for: %s', 'cenos' ), '<span>' . get_search_query() . '</span>' );
                                    ?>
                                </h1>
                            </header><!-- .page-heading -->
                            <?php
                            get_template_part( 'loop' );
                        else :
                            get_template_part( 'template-parts/content', 'none' );
                        endif;
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
