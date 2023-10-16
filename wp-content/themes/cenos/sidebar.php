<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Cenos
 */

if ( ! is_active_sidebar( 'sidebar-main' ) ) {
	return;
}
?>
<div id="secondary" class="widget-area" role="complementary">
    <?php do_action( 'before_sidebar' ); ?>
    <?php if ( ! dynamic_sidebar( 'sidebar-main' ) ) : ?>
    <?php endif; // end sidebar widget area ?>
</div><!-- #secondary -->
