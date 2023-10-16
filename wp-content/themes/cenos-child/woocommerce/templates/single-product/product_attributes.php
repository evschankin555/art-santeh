<?php
/**
 * Атрибуты продукта
 *
 * Используется функцией list_attributes() в классе продуктов.
 *
 * Этот шаблон может быть переопределен путем его копирования в yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * ОДНАКО, иногда WooCommerce может потребоваться обновить файлы шаблона, и вам,
 * (разработчику темы), придется скопировать новые файлы в вашу тему, чтобы
 * сохранить совместимость. Мы стараемся делать это как можно реже, но это
 * происходит. Когда это случается, версия файла шаблона будет повышена, и
 * в readme будут перечислены все важные изменения.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
if ( is_object( $product ) ) {
    $product_id = $product->get_id();
} else {
    $product_id = get_the_ID();
}

$productDataRetriever = new ProductDataRetriever($product_id);
$product_attributes = $productDataRetriever->getProductData();

if (!$product_attributes) {
    return;
}
?>

<table class="woocommerce-product-attributes shop_attributes">
    <?php foreach ($product_attributes as $product_attribute_key => $product_attribute) : ?>
        <tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr($product_attribute_key); ?>">
            <th class="woocommerce-product-attributes-item__label"><?php echo wp_kses_post($product_attribute['label']); ?></th>
            <td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post($product_attribute['value']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
