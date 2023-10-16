<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Cenos
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area" aria-label="<?php esc_attr_e( 'Post Comments', 'cenos' ); ?>">
	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
				printf( // WPCS: XSS OK.
					/* translators: 1: number of comments, 2: post title */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'cenos' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Comment Navigation Above', 'cenos' ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'cenos' ); ?></span>
			<div class="nav-previous"><?php previous_comments_link(__( '&larr; Older Comments', 'cenos' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link(__( 'Newer Comments &rarr;', 'cenos' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'      => 'ol',
						'short_ping' => true,
                        'avatar_size' => 50,
						'callback'   => 'cenos_comment',
					)
				);
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Comment Navigation Below', 'cenos' ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'cenos' ); ?></span>
			<div class="nav-previous"><?php previous_comments_link(__( '&larr; Older Comments', 'cenos' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link(__( 'Newer Comments &rarr;', 'cenos' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
			<?php
		endif; // Check for comment navigation.

	endif;

	if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'cenos' ); ?></p>
		<?php
	endif;

	$args = apply_filters(
		'cenos_comment_form_args', array(
			'title_reply_before' => '<span id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</span>',
            'comment_field'        => sprintf(
                '<p class="comment-form-comment">%s %s</p>',
                sprintf(
                    '<label for="comment">%s%s</label>',
				    _x( 'Comment', 'noun' ,'cenos'),
                    ' <span class="required">*</span>'
                ),
                '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>'
            ),
		)
	);

	comment_form( $args );
	?>

</section><!-- #comments -->
