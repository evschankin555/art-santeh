<?php

/**
 * Шаблон Meta информации о продукте в WooCommerce.
 *
 * Этот шаблон отображает мета-информацию о товаре, включая Артикул, Категории, Бренды, Цвет и Коллекцию.
 *
 * @global object $product        - Глобальный объект продукта WooCommerce.
 * @global string $sku            - Артикул продукта.
 * @global array  $color_terms     - Термины таксономии для цвета продукта.
 * @global array  $collection_terms - Термины таксономии для коллекции продукта.
 *
 * @see cenos_get_option()        - Получение опций темы.
 * @see wc_product_sku_enabled()  - Проверяет, включены ли SKU для продуктов.
 * @see get_the_terms()           - Получение термов таксономии для текущего продукта.
 * @see get_term_link()           - Получение ссылки на архив термина.
 *
 * @hook woocommerce_product_meta_start - Действие перед началом вывода мета-информации.
 * @hook woocommerce_product_meta_end   - Действие после вывода мета-информации.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$woo_single_show_sku = cenos_get_option('woo_single_show_sku');
$woo_single_show_cat = cenos_get_option('woo_single_show_cat');
$woo_single_show_tag = cenos_get_option('woo_single_show_tag');
global $product;
$sku = $product->get_sku();

$color_terms = get_the_terms( $product->get_id(), 'pa_color' );
$collection_terms = get_the_terms( $product->get_id(), 'pa_collections' );
?>
<div class="product_meta">

    <?php do_action( 'woocommerce_product_meta_start' ); ?>

    <?php if ($woo_single_show_sku && wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>

        <span class="sku_wrapper"><?php esc_html_e( 'Артикул:', 'cenos' ); ?> <span class="sku"><?php echo ( !empty($sku ) ? $sku : esc_html__( 'N/A', 'cenos' )); ?></span></span>

    <?php endif; ?>
    <?php if ($woo_single_show_cat){
        echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Категория:', 'Категории:', count( $product->get_category_ids() ), 'cenos' ) . ' ', '</span>' );
    }
    ?>

    <?php if ($woo_single_show_tag){
        echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Бренд:', 'Бренды:', count( $product->get_tag_ids() ), 'cenos' ) . ' ', '</span>' );
    }
    ?>

    <?php if ($color_terms): ?>
        <span class="color_attribute"><?php esc_html_e( 'Цвет:', 'cenos' ); ?> <a href="<?php echo get_term_link( $color_terms[0]->term_id, 'pa_color' ); ?>"><?php echo esc_html($color_terms[0]->name); ?></a></span>
    <?php endif; ?>

    <?php


    // Получение имени бренда
    $brand_name = $product->get_tag_ids()[0] ? get_term($product->get_tag_ids()[0])->name : '';

    // Получение имени коллекции с брендом
    $collection_with_brand = $collection_terms[0]->name;

    // Удаление имени бренда из имени коллекции
    $collection_name = str_replace($brand_name, '', $collection_with_brand);
    ?>

    <?php if ($collection_terms): ?>
        <span class="collection_attribute"><?php esc_html_e( 'Коллекция:', 'cenos' ); ?> <a href="<?php echo get_term_link( $collection_terms[0]->term_id, 'pa_collections' ); ?>"><?php echo esc_html($collection_name); ?></a></span>
    <?php endif; ?>


    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
