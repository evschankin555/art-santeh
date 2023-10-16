<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
    $search_form_style = cenos_get_option('search_form_style');
    $search_placeholder_text = cenos_get_option('search_placeholder_text');
    $search_btn_text = cenos_get_option('search_btn_text');
    $search_btn_class = 'icon';
    $search_btn_text_content = cenos_get_option('search_btn_text_content');
    if ($search_btn_text == true){
        $search_btn_class = 'text';
    }
    $ajax_search = cenos_get_option('search_ajax');
    if ($ajax_search){
        $search_form_style .= ' ajax_search';
    }
?>
<form role="search" method="get" class="woocommerce wc_search_form fm-search-form <?php echo esc_attr($search_form_style);?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" class="search-field search_text_input" placeholder="<?php echo esc_attr($search_placeholder_text); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <?php
    $search_clear_btn = cenos_get_option('search_clear_btn');
    if ($search_clear_btn == true):?>
        <a href="javascript:void(0);" class="btn_clear_text">
            <?php cenos_svg_icon('close')?>
        </a>
    <?php
    endif;
    ?>
    <input type="hidden" name="post_type" value="product" />
    <button class="<?php echo esc_attr($search_btn_class);?>" type="submit" value="<?php echo esc_attr($search_btn_text_content); ?>"><?php cenos_svg_icon('magnify')?><?php cenos_esc_data($search_btn_text_content); ?></button>
</form>

