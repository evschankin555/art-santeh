<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Cenos
 */

get_header(); ?>
    <?php
    $content_layout_class = apply_filters('cenos_content_layout_class' ,'');
    $primary_class = ['content-area'];
    $is_cenos_sidebar_active = is_cenos_sidebar_active();
    if ($is_cenos_sidebar_active){
        $primary_class = ['col'];
    }
    ?>
<div id="content" class="content <?php echo esc_attr($content_layout_class);?>" tabindex="-1">
    <div class="container">
        <?php do_action( 'cenos_content_top' );?>
        <?php if ($is_cenos_sidebar_active):
            $sidebar_layout = cenos_sidebar_layout();
            ?>
            <div class="row <?php echo esc_attr($sidebar_layout['layout']);?>">
            <?php do_action('cenos_sidebar');?>
         <?php else:?>
            <div class="archive-before-content">
                <?php do_action('cenos_archive_before_content');?>
            </div>
        <?php endif;?>

        <div id="primary" class="<?php echo implode( ' ', $primary_class ); ?>"> <!-- #archive -->
            <main id="main" class="site-main">
            <?php if ( have_posts() ) :
                get_template_part( 'loop' );
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif;?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ($is_cenos_sidebar_active):?>
            </div><!-- .row -->
        <?php endif; ?>
    </div><!-- .container -->
</div><!-- #content -->
<?php
get_footer();
