<?php
/**
 * Template Name: Full Page Scroll
 *
 */
get_header();
?>
        <div id="fullpage">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content( );?>
            <?php endwhile; ?>
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>
        </div>
    </div><!-- close #page open on header-->
    <?php wp_footer(); ?>
</body>
</html>