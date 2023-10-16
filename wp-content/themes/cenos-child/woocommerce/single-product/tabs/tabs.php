<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$woo_single_tabs_style = cenos_get_option('woo_single_tabs_style');

if (cenos_on_device() && cenos_get_option('mobile_single_product_tab')){
    $woo_single_tabs_style = cenos_get_option('mobile_single_tabs_style');
}

if ($woo_single_tabs_style == 'sections') {
    wc_get_template_part( 'single-product/tabs/sections' );
    return;
}

if ($woo_single_tabs_style == 'accordion') {
    wc_get_template_part( 'single-product/tabs/accordion' );
    return;
}

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$cenos_tabs_class = ['cenos-wc-tabs'];

if ('vertical' == $woo_single_tabs_style) {
    $cenos_tabs_class[] = 'vertical';
}
function get_product_attributes($product_id) {
    $product_attributes = array();

    $product = wc_get_product($product_id);

    if ($product) {
        $attributes = $product->get_attributes();

        foreach ($attributes as $attribute) {
            $attribute_label = wc_attribute_label($attribute->get_name()); // Получаем более информативное название атрибута
            $attribute_options = $attribute->get_terms(); // Значения атрибута
            $attribute_values = wp_list_pluck($attribute_options, 'name');

            // Фильтруем пустые значения
            $attribute_values = array_filter($attribute_values);

            if (!empty($attribute_values)) {
                $product_attributes[] = array(
                    'label' => $attribute_label,
                    'value' => implode(", ", $attribute_values),
                );
            }
        }
    }

    return $product_attributes;
}


if ( ! empty( $product_tabs ) ) : ?>
    <div class="woocommerce-tabs wc-tabs-wrapper <?php echo implode( ' ', $cenos_tabs_class ); ?>">
        <ul class="tabs wc-tabs cenos-tabs-title" role="tablist">
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                    <a href="#tab-<?php echo esc_attr( $key ); ?>">
                        <?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
            <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                <?php
                if ( $key === 'additional_information' ) {
                    global $product;
                    if ( is_object( $product ) ) {
                        $product_id = $product->get_id();
                    } else {
                        $product_id = get_the_ID();
                    }

                    $productDataRetriever = new ProductDataRetriever($product_id);
                    $product_attributes1 = $productDataRetriever->getSelectedProductData();
                    $product_attributes2 = get_product_attributes($product_id);

                    $product_attributes = array_merge($product_attributes1, $product_attributes2);

                    if ($product_attributes) : ?>
                        <table class="woocommerce-product-attributes shop_attributes">


                            <?php foreach ($product_attributes as $product_attribute_key => $product_attribute) : ?>
                                <tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr($product_attribute_key); ?>">
                                    <th class="woocommerce-product-attributes-item__label"><?php echo wp_kses_post($product_attribute['label']); ?></th>
                                    <td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post($product_attribute['value']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; // End if ($product_attributes)
                } else {
                    if ( isset( $product_tab['callback'] ) ) {
                        call_user_func( $product_tab['callback'], $key, $product_tab );
                    }
                }
                ?>
            </div>
        <?php endforeach; ?>

        <?php do_action( 'woocommerce_product_after_tabs' ); ?>
    </div>

<?php endif; ?>
