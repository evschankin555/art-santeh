<?php
class ProductImageChecker {
    public function checkMissingImages() {
        global $wpdb;
        $steps = [];

        $products = $wpdb->get_results("SELECT post_id, meta_value as sku FROM $wpdb->postmeta WHERE meta_key='_sku'");

        foreach ($products as $product) {
            $product_id = $product->post_id;
            $sku = $product->sku;

            $main_image = get_post_thumbnail_id($product_id);

            if (empty($main_image)) {
                $steps[] = "У продукта с артикулом {$sku} отсутствует основная картинка.";
            }
        }

        return $steps;
    }
}
add_action('wp_ajax_check_missing_images', 'check_missing_images');

function check_missing_images() {
    $checker = new ProductImageChecker();
    $result = $checker->checkMissingImages();

    echo json_encode($result);
    wp_die();
}
