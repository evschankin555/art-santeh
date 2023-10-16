<?php
/**
 * Template Name: Full Page Without Footer
 *
 */
get_header();
?>
        <div id="main">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content();?>
            <?php endwhile; ?>
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?>
        </div>
    </div><!-- close #page open on header-->
    <?php wp_footer(); ?>
</body>
</html>