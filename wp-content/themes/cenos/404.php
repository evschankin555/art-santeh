<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Cenos
 */

get_header();
    $content_layout_class = apply_filters('cenos_content_layout_class' ,'');

?>
<div id="content" class="content <?php echo esc_attr($content_layout_class);?>" tabindex="-1">
    <div class="container">
        <div id="primary" class="content-area"><!-- #404 -->

            <main id="main" class="site-main">

                <div class="error-404 not-found">

                    <div class="page-content">

                        <header class="page-heading text-center">
                            <h1 class="page-title-404"><?php esc_html_e( '404', 'cenos' ); ?></h1>
                            <h3 class="page-sub-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'cenos' ); ?></h3>
                        </header><!-- .page-heading -->

                        <p class="text-center"><?php esc_html_e( 'Nothing was found at this location. Try searching, or check out the links below.', 'cenos' ); ?></p>

                        <?php
                        echo '<section class="text-center" aria-label="' . esc_html__( 'Search', 'cenos' ) . '">';

                        if ( cenos_is_woocommerce_activated() ) {
                            the_widget( 'WC_Widget_Product_Search' );
                        } else {
                            get_search_form();
                        }

                        echo '</section> <br><br>';

                        if ( cenos_is_woocommerce_activated() ) {

                            echo '<section class="text-center" aria-label="' . esc_html__( 'Popular Products', 'cenos' ) . '">';

                                echo '<h2>' . esc_html__( 'Popular Products', 'cenos' ) . '</h2>';

                                $shortcode_content = cenos_do_shortcode(
                                    'best_selling_products', array(
                                        'per_page' => 4,
                                        'columns'  => 4,
                                    )
                                );

                                cenos_esc_data($shortcode_content); // WPCS: XSS ok.

                            echo '</section>';
                        }
                        ?>

                    </div><!-- .page-content -->
                </div><!-- .error-404 -->

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .container -->
</div><!-- #content -->
<?php
get_footer();
