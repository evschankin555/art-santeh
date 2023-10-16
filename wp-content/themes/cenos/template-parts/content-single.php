<?php
/**
 * Template used to display post content on single pages.
 *
 * @package Cenos
 */
$blog_single_style = cenos_get_option('blog_single_style');
if ($blog_single_style !='classic') :
    get_template_part( 'template-parts/content-single', $blog_single_style );
else :
    $post_class ='single-post';
    $show_single_heading = false;
    $bl_heading = cenos_get_option('blog_heading');
    $blog_heading_display = cenos_get_option('blog_heading_display');
    if ($bl_heading == true && in_array('post',$blog_heading_display)){
        $show_single_heading = true;
    }
?>
<!-- #Content single-## -->
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
    <div class="post-header">
        <?php if (!$show_single_heading) :?>
            <div class="post-categories">
                <?php cenos_post_taxonomy('categories', false);?>
            </div>
            <?php the_title( '<h1 class="entry-title">', '</h1>' );?>
            <div class="metas">
                <?php cenos_post_meta(['posted_on','author'], false);?>
                <?php cenos_post_meta(['comments'], true);?>
            </div>
        <?php endif;?>
    </div>
    <?php cenos_post_content();?>
    <div class="info-bottom clearfix">
        <?php cenos_post_taxonomy('tags', false);
          if (cenos_get_option('blog_post_share')){
              get_template_part( 'template-parts/blog/share' );
          }
        ?>
    </div>
    <?php
    get_template_part( 'template-parts/blog/author_bio' );
    if (cenos_get_option('blog_post_nav')):?>
    <div class="footer-post">
        <?php cenos_post_nav(); ?>
    </div>
    <?php endif;
    ?>
</article><!-- #post-## -->
    <!-- #related post-## -->
<?php endif;

get_template_part( 'template-parts/blog/related' );
