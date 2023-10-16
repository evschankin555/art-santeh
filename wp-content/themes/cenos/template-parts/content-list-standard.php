<?php
/**
 * Template used to display post content.
 *
 * @package Cenos
 */

$post_class ='col col-sm-12 cenos-post-item post-standard';
$post_thumb_class = ' no-thumbnail';
if (has_post_thumbnail()){
    $post_thumb_class = ' has-thumbnail';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
    <div class="post-inner<?php echo esc_attr($post_thumb_class);?>">
        <div class="entry-image-attachment">
            <?php cenos_post_thumbnail( 'large' ); ?>
        </div>
        <div class="info">
            <div class="post-item-head">
                <span class="date">
                    <span class="day"><?php echo get_the_date('d');?></span>
                    <span class="month"><?php echo get_the_date('M');?></span>
                </span>
                <div class="post-categories">
                    <?php cenos_post_taxonomy('categories');?>
                </div>
                <?php the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );?>
            </div>
            <?php if ('summary' === cenos_get_option('blog_content')) :?>
                <div class="entry-content excerpt">
                    <?php the_excerpt();?>
                </div>
            <?php else:?>
                <div class="entry-content">
                    <?php the_content(
                        sprintf(
                        /* translators: %s: post title */
                           esc_html__( 'Continue reading %s', 'cenos' ),
                            '<span class="screen-reader-text">' . get_the_title() . '</span>'
                        )
                    );?>
                </div>
            <?php endif;?>
            <div class="metas">
                <?php cenos_post_meta(['author','comments','sticky_post']);?>
            </div>
        </div>
    </div>
</article>
