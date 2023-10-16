<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Cenos
 */

?>

<div class="no-results not-found">
	<header class="page-heading">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'cenos' ); ?></h1>
	</header><!-- .page-heading -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p>
				<?php
					/* translators: 1: URL */
					printf( wp_kses(esc_html__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'cenos' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) );
				?>
			</p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'cenos' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'cenos' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</div><!-- .no-results -->
