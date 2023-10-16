<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Cenos
 */



	do_action( 'cenos_before_footer' ); ?>

	<footer class="site-footer">
        <?php
        /**
         * Functions hooked in to cenos_footer action
         *
         * @hooked cenos_footer_widgets - 10
         * @hooked cenos_credit         - 20
         */
        do_action( 'cenos_footer' );
			?>
	</footer><!-- .site-footer -->

	<?php do_action( 'cenos_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
