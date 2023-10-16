<div class="post-item-share">
    <h3 class="title"><?php esc_html_e('Share:','cenos');?></h3>
    <div class="cenos-blog-share">
        <a title="<?php echo sprintf( esc_attr__( 'Share "%s" on Facebook', 'cenos'), get_the_title() ); ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>&display=popup" target="_blank">
            <?php cenos_svg_icon('facebook');?>
	    </a>
        <a title="<?php echo sprintf( esc_attr__( 'Post status "%s" on Twitter', 'cenos'), get_the_title() ); ?>" href="https://twitter.com/home?status=<?php the_permalink(); ?>" target="_blank">
            <?php cenos_svg_icon('twitter');?>
	    </a>
        <a title="<?php echo sprintf( esc_attr__( 'Share "%s" on Google Plus', 'cenos'), get_the_title() ); ?>"  href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank">
            <?php cenos_svg_icon('google-plus');?>
	    </a>
	    <a title="<?php echo sprintf( esc_attr__( 'Pin "%s" on Pinterest', 'cenos'), get_the_title() ); ?>" href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo esc_url( get_the_post_thumbnail_url('full')); ?>&amp;description=<?php echo urlencode( get_the_excerpt() ); ?>" target="_blank">
            <?php cenos_svg_icon('instagram');?>
	    </a>
	    <a title="<?php echo sprintf( esc_attr__( 'Share "%s" on LinkedIn', 'cenos'), get_the_title() ); ?>"  href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>&amp;summary=<?php echo urlencode( get_the_excerpt() ); ?>&amp;source=<?php echo urlencode( get_bloginfo( 'name' ) ); ?>" target="_blank">
            <?php cenos_svg_icon('linkedin');?>
	    </a>
    </div>
</div>
