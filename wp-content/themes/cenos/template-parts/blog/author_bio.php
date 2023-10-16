<?php
/**
 * Template used to display Biographical Info.
 *
 * @package Cenos
 */

$blog_post_author_bio = cenos_get_option('blog_post_author_bio');
if ($blog_post_author_bio) :
    $description = get_the_author_meta( 'description' );
    if (!empty($description)) :?>
        <div class="about-me">
            <h6 class="author-title"><?php esc_html_e( 'About author', 'cenos' ); ?></h6>
            <div class="avatar-img">
                <?php echo get_avatar( get_the_author_meta( 'email' ), '180' ); ?>
            </div>
            <div class="about-text">
                <div class="author-info">
                    <h3 class="author-name"><?php the_author(); ?></h3>
                </div>
                <div class="author-desc"><?php the_author_meta( 'description' ); ?></div>
            </div>
        </div>
    <?php endif;
endif;
