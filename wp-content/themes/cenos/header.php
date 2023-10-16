<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @since Cenos 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'cenos_before_site' ); ?>

<div id="page" class="site-content">
	<?php do_action( 'cenos_before_header' ); ?>
	<header id="masthead" class="<?php echo esc_attr(cenos_get_site_header_class());?>">
        <div class="header-wrapper">
            <?php
            /**
             * Functions hooked into cenos_header action
             * @hooked cenos_header                     - 10
             */
            do_action( 'cenos_header' );
            ?>
        </div>
	</header><!-- #masthead -->
	<?php
	/**
	 * Functions hooked in to cenos_before_content
	 *
	 * @hooked cenos_page_heading - 10
	 */
	do_action( 'cenos_before_content' );
	?>


