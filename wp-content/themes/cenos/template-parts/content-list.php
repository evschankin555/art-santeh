<?php
/**
 * Template used to display post content.
 *
 * @package Cenos
 */
$post_class ='col col-sm-12 cenos-post-item post-list';
$word = 70;
$post_thumb_class = ' no-thumbnail';
if (has_post_thumbnail()){
    $post_thumb_class = ' has-thumbnail';
}
$post_link = get_permalink();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
    <div class="post-inner<?php echo esc_attr($post_thumb_class);?>">
        <div class="entry-image-attachment">
            <?php cenos_post_thumbnail( 'large' ); ?>
        </div>
        <div class="info">
            <div class="post-item-head">
                <div class="post-categories">
                    <?php cenos_post_taxonomy('categories', false);?>
                </div>
                <?php the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( $post_link ) ), '</a></h2>' );?>
            </div>
            <div class="metas">
                <?php cenos_post_meta(['posted_on','author'], false);?>
            </div>
            <div class="entry-content excerpt">
                <?php
                $the_excerpt  = get_the_excerpt();
                $excerpt_more = apply_filters( 'excerpt_more', '' );
                echo wp_trim_words(apply_filters('the_excerpt', $the_excerpt), $word, $excerpt_more);
                ?>
            </div>
        </div>
    </div>
</article>
