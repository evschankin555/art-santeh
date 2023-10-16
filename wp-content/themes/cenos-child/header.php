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
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'cenos_before_site' ); ?>

<style>
    .logo-img.logo-dark.img-responsive{
        max-width: 150px;
    }
    .woocommerce table.shop_attributes th{
        width: 245px;
    }
    .single-product #content .type-product .product-container table.shop_attributes tr th {
        text-transform: none !important;
    }
    .woof_container .woof_container_inner input[type="search"]{
        border: none;
        padding: 10px;
    }
    .product_meta a{
        color: #8d8d8d !important;
        font-weight: 400 !important;
    }
    .term-count {
        font-size: 0.8em;
        vertical-align: super;
    }
    #order_review table.woocommerce-checkout-review-order-table tbody tr.cart_item td{
        min-width: 110px;
    }
    .woocs_auto_switcher{
        display: none !important;
    }
    article.type-post img{
        width: 100%;
    }
    .elementor-8527 .elementor-element.elementor-element-3f0b2b9 .elementor-image-box-wrapper{
        text-align: center;
        position: relative;
    }
    .elementor.elementor-8527 .elementor-widget:not(.elementor-widget-text-editor):not(.elementor-widget-theme-post-content) figure {
        margin: 0;
        min-height: 185px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }
</style>
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


