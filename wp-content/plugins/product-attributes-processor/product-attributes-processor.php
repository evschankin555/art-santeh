<?php

/**
 * Plugin Name: Product Attributes Processor
 * Description: Плагин для создания из атрибутов некоторые метки (цвета, бренды, коллекции).
 * Version: 1.0
 * Author: Евгений
 * Author URI: https://t.me/evsch999
 */

function register_custom_endpoint() {
    register_rest_route('product-attributes-processor/v1', '/process-product-attributes', array(
        'methods'  => 'GET',
        'callback' => 'process_product_attributes567_endpoint',
    ));
}
add_action('rest_api_init', 'register_custom_endpoint');

function process_product_attributes567_endpoint(WP_REST_Request $request) {
    ini_set('memory_limit', '256M');

    $page = $request->get_param('page');
    $batch_size = 2000;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $batch_size,
        'paged' => $page,
    );

    $products = new WP_Query($args);
    $array = [];
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $array[] = $product_id;
            $product = wc_get_product($product_id);

            $manufacturer = $product->get_attribute('pa_производитель');
            $collection = $product->get_attribute('pa_коллекция');
            $color = $product->get_attribute('pa_цвет');

            set_tags_from_attributes($product_id, $manufacturer, $collection, $color);
        }
    }

    wp_reset_postdata();

    return json_encode($array);
}




function set_tags_from_attributes($product_id, $manufacturer, $collection, $color)
{
    if (!empty($manufacturer)) {
        // Check if the manufacturer tag exists, create it if not.
        $manufacturer_term = get_term_by('name', $manufacturer, 'product_tag');
        if (!$manufacturer_term) {
            $manufacturer_term = wp_insert_term($manufacturer, 'product_tag');
            $manufacturer_term = get_term_by('name', $manufacturer, 'product_tag');
        }

        // Assign the manufacturer tag to the product.
        wp_set_post_terms($product_id, array($manufacturer_term->term_id), 'product_tag', true);
    }

    if (!empty($collection)) {
        // Check if the collection tag exists, create it if not.
        $collection_term = get_term_by('name', $collection, 'pa_collections');
        if (!$collection_term) {
            $collection_term = wp_insert_term($collection, 'pa_collections');
            $collection_term = get_term_by('name', $collection, 'pa_collections');
        }

        // Assign the collection tag to the product.
        wp_set_post_terms($product_id, array($collection_term->term_id), 'pa_collections', true);
    }

    if (!empty($color)) {
        // Check if the color tag exists, create it if not.
        $color_term = get_term_by('name', $color, 'pa_color');
        if (!$color_term) {
            $color_term = wp_insert_term($color, 'pa_color');
            $color_term = get_term_by('name', $color, 'pa_color');
        }

        // Assign the color tag to the product.
        wp_set_post_terms($product_id, array($color_term->term_id), 'pa_color', true);
    }
}
