<?php
/**
 * Подключение родительской темы
 *
 * @package CenosChildTheme
 * @since 1.0.0
 */
add_action('wp_enqueue_scripts', 'enqueue_parent_styles');

function enqueue_parent_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

/**
 * Переключение отображения главных страниц
 *
 * Этот блок кода добавляет пункты в админ-бар WordPress, позволяя администраторам
 * переключать главную страницу сайта.
 *
 */


// Добавление кнопок в админ-бар
add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar) {
    if (current_user_can('manage_options')) { // Только для админов
        $admin_bar->add_menu(array(
            'id' => 'home-switcher',
            'title' => 'Переключение главной страницы'
        ));

        for ($i = 1; $i <= 14; $i++) {
            $admin_bar->add_menu(array(
                'id' => 'home-' . sprintf("%02d", $i),
                'parent' => 'home-switcher',
                'title' => 'Установить главной отображение ' . sprintf("%02d", $i),
                'href' => admin_url('admin-post.php?action=set_front_page_' . sprintf("%02d", $i))
            ));
        }
    }
}

// Обработчики переключения
for ($i = 1; $i <= 14; $i++) {
    add_action('admin_post_set_front_page_' . sprintf("%02d", $i), 'set_front_page_handler');
}

// Функция переключения главной страницы
function set_front_page_handler() {
    // Получаем номер страницы из action
    $page_num = explode('_', current_action());
    $page_num = end($page_num);

    // Используем get_page_by_path(), если у вас slug, или get_page_by_title(), если у вас title
    $page = get_page_by_path('home-' . $page_num); // Измените на ваш slug или title

    if ($page) {
        update_option('page_on_front', $page->ID);
        update_option('show_on_front', 'page');

    }
    wp_safe_redirect(home_url());
}

/**
 * Изменяет формат цены в WooCommerce.
 *
 * Данная функция меняет стандартный формат цены WooCommerce так, чтобы сначала шла цена,
 * а затем символ валюты. В данном контексте она не меняет исходное поведение.
 *
 * @param string $format - Текущий формат цены в WooCommerce.
 * @return string - Измененный формат цены.
 *
 * @example
 * Стандартный формат цены WooCommerce — '%1$s%2$s', где:
 * - %1$s — это цена
 * - %2$s — это символ валюты
 * Функция возвращает такой же формат, по сути не изменяя оригинальное поведение.
 */
add_filter( 'woocommerce_price_format', 'custom_woocommerce_price_format' );
function custom_woocommerce_price_format( $format ) {
    return '%1$s%2$s';  // Здесь %1$s — это цена, а %2$s — это символ валюты
}

/**
 * Изменяет отображение цены, убирая десятичные знаки и добавляя пробелы между тысячами.
 *
 * @param float $price - Оригинальная цена.
 * @param array $args - Дополнительные аргументы.
 * @return string - Форматированная строка цены.
 */
add_filter( 'wc_price', 'custom_wc_price', 10, 2 );
function custom_wc_price( $price, $args ) {
    if (is_array($args)) {
        extract( $args );
    }

    $return          = '';
    $num_decimals    = 0;  // Количество десятичных знаков
    $currency        = get_option( 'woocommerce_currency' );
    $decimal_sep     = wc_get_price_decimal_separator();
    $thousands_sep   = ' ';  // Разделитель тысяч — пробел
    $currency_symbol = '₽';  // Символ рубля

    // Убрать все кроме цифр и точек, преобразовать в float
    $price = preg_replace('/[^0-9.]/', '', $price);
    $price = floatval($price);

    // Округление и форматирование числа
    $price = round($price, $num_decimals);  // Округление до 0 десятичных знаков
    $price = number_format($price, $num_decimals, $decimal_sep, $thousands_sep);  // Форматирование

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( '%1$s%2$s', $price, '<span class="woocommerce-Price-currencySymbol">' . $currency_symbol . '</span>');

    return $formatted_price;
}
add_filter('woocommerce_format_weight', 'customize_product_weight', 10, 2);

function customize_product_weight($formatted_weight, $weight) {
    if ($weight) {
        return (float) $weight / 10 . ' кг'; // деление на 10 для примера
    }
    return $formatted_weight;
}
/**
 * Выводит вкладки товаров на странице магазина.
 *
 * @function
 * @param {?string} $type - Тип вкладок для отображения (категории, теги, или группы товаров). Если null, будет использоваться значение из настроек.
 *
 * @example
 *
 * cenos_shop_product_tabs('category');
 *
 * @description
 *
 * Функция проверяет, будут ли отображаться товары и настройки магазина перед выводом вкладок.
 * Создает массив вкладок в зависимости от выбранного типа (категории, теги, или группы).
 *
 */

if (!function_exists('cenos_shop_product_tabs')) {
    function cenos_shop_product_tabs($type = null) {
        if (!woocommerce_products_will_display()) {
            return;
        }

        $shop_control_use_tabs = cenos_get_option('shop_control_use_tabs');
        if (!$shop_control_use_tabs) {
            return;
        }

        if (is_null($type)) {
            $type = cenos_get_option('shop_control_product_tabs');
        }

        $tabs = array();
        $active_all = true;

        if (is_product_taxonomy()) {
            $queried = get_queried_object();
            $base_url = get_term_link($queried);
        } else {
            $base_url = wc_get_page_permalink('shop');
        }

        $labels = array(
            'best_sellers' => esc_html__('Лучшие продажи', 'cenos'),
            'featured'     => esc_html__('Горячие товары', 'cenos'),
            'new'          => esc_html__('Новинки', 'cenos'),
            'sale'         => esc_html__('Распродажа', 'cenos'),
            'all_products' => esc_html__('Все бренды', 'cenos')
        );

        if (in_array($type, ['category', 'tag'])) {
            $taxonomy = 'category' == $type ? 'product_cat' : 'product_tag';
            //$terms = cenos_get_terms_for_shop_toolbar($type);

            $terms = cenos_get_terms_for_shop_toolbar('collection');


            if (empty($terms)) {
                return;
            }

            usort($terms, function($a, $b) {
                return $b->count <=> $a->count;
            });
            foreach ($terms as $term) {
                if ($term->slug === 'uncategorized') {
                    continue;
                }
                $active = false;
                if (is_tax($taxonomy, $term->slug)) {
                    $active = true;
                    $active_all = false;
                }

                $tabs[] = sprintf(
                    '<a href="%s" class="tab-%s %s">%s<span class="term-count">(%s)</span></a>',
                    esc_url(get_term_link($term)),
                    esc_attr($term->slug),
                    $active ? 'active' : '',
                    esc_html($term->name),
                    esc_html($term->count)
                );

            }
        } else {
            $groups = (array)cenos_get_option('shop_control_tabs_groups');

            if (empty($groups)) {
                return;
            }

            foreach ($groups as $group) {
                $active = false;
                if (isset($_GET['products_group']) && $group == $_GET['products_group']) {
                    $active = true;
                    $active_all = false;
                }

                $tabs[] = sprintf(
                    '<a href="%s" class="tab-%s %s">%s</a>',
                    esc_url(add_query_arg(['products_group' => $group], $base_url)),
                    esc_attr($group),
                    $active ? 'active' : '',
                    $labels[$group]
                );
            }
        }

        if (empty($tabs)) {
            return;
        }

        array_unshift(
            $tabs,
            sprintf(
                '<a href="%s" class="tab-all %s">%s</a>',
                'group' == $type ? esc_url($base_url) : esc_url(wc_get_page_permalink('shop')),
                $active_all ? 'active' : '',
                $labels['all_products']
            )
        );

        echo '<div class="cenos-products-tabs">';
        foreach ($tabs as $tab) {
            echo trim($tab);
        }
        echo '</div>';

        add_filter('cenos_shop_control_class', function ($classes) {
            $classes[] = 'control_has_product_tabs';
            return $classes;
        });
    }
}

/**
 * Получает термины для панели инструментов магазина в зависимости от указанного типа (категории или тега).
 *
 * @function
 * @param {string} $type - Тип терминов для получения ('category' или 'tag').
 * @param {?bool} $use_sub_cat - Использовать подкатегории или нет. Если null, будет использоваться значение из настроек.
 *
 * @return {array} Возвращает массив терминов или пустой массив, если термины не найдены или произошла ошибка.
 *
 * @example
 *
 * $terms = cenos_get_terms_for_shop_toolbar('category');
 *
 */

if (!function_exists('cenos_get_terms_for_shop_toolbar')) {
    function cenos_get_terms_for_shop_toolbar($type = 'category', $use_sub_cat = null){
        $terms = array();
        $taxonomy = 'category' == $type ? 'product_cat' : 'product_tag';
        $slugs = trim(cenos_get_option('shop_control_'.$type.'_items'));

        if (is_null($use_sub_cat)) {
            $use_sub_cat = cenos_get_option('shop_control_sub_'.$type);
        }

        if (is_tax($taxonomy) && $use_sub_cat) {
            $queried = get_queried_object();
            $args = array(
                'taxonomy' => $taxonomy,
                'parent' => $queried->term_id,
                'orderby' => 'menu_order',
            );

            if (is_numeric($slugs)) {
                $args['number'] = intval($slugs);
            }

            $the_query = new WP_Term_Query($args);
            $terms = $the_query->get_terms();

        } else {
            if (is_numeric($slugs)) {
                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'menu_order',
                    'parent' => 0,
                    'number' => intval($slugs),
                ));

                $terms = $the_query->get_terms();

            } elseif (!empty($slugs)) {
                $slugs = explode(',', $slugs);

                if (empty($slugs)) {
                    return;
                }

                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'slug__in',
                    'slug' => $slugs,
                ));

                $terms = $the_query->get_terms();

            } else {
                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'menu_order',
                    'parent' => 0,
                ));

                $terms = $the_query->get_terms();
            }
        }

        // Additional logic for collection
        if ($type === 'collection') {
            $meta_query_args = array(
                array(
                    'key' => 'collection',
                    'compare' => 'EXISTS'
                )
            );

            $args = array(
                'taxonomy' => 'product_cat',
                'meta_query' => $meta_query_args
            );

            $the_query = new WP_Term_Query($args);
            $collection_terms = $the_query->get_terms();

            if (!is_wp_error($collection_terms) && !empty($collection_terms)) {
                $terms = array_merge($terms, $collection_terms);
            }
        }

        if (empty($terms) || is_wp_error($terms)) {
            return [];
        }
        usort($terms, function($a, $b) {
            return $b->count <=> $a->count;
        });

        return $terms;
    }
}

/**
 * Изменяет текст сортировки каталога товаров.
 *
 * @function
 * @param {array} $options - Исходный массив параметров сортировки.
 *
 * @returns {array} - Обновленный массив параметров сортировки с измененным текстом.
 *
 * @example
 *
 * $options = ['menu_order' => 'Sort', 'price' => 'Price'];
 * $newOptions = cenos_catalog_orderby_text($options);
 *
 * @description
 *
 * Функция изменяет текст сортировки по умолчанию ("menu_order") на заданный.
 * Возвращает обновленный массив параметров сортировки.
 *
 */
if (!function_exists('cenos_catalog_orderby_text')){
    function cenos_catalog_orderby_text($options){
        $options['menu_order'] = esc_attr__( 'Упорядочить по', 'cenos' );
        return $options;
    }
}

/**
 * Выводит блоки фильтрации для различных таксономий.
 *
 * @function
 * @param {array} $taxonomies - Массив всех таксономий.
 * @param {string} $tax_slug - Символьный код текущей таксономии.
 * @param {array} $terms - Массив терминов текущей таксономии.
 * @param {string} $exclude_tax_key - Символьный код исключаемой таксономии.
 * @param {array} $taxonomies_info - Информация о всех таксономиях.
 * @param {array} $additional_taxes - Дополнительные таксономии.
 * @param {array} $woof_settings - Настройки фильтра.
 * @param {array} $args - Дополнительные параметры.
 * @param {int} $counter - Счетчик для уникальности контейнеров.
 *
 * @description
 *
 * Эта функция отвечает за вывод блоков фильтрации на фронтенде сайта.
 * Она поддерживает различные типы фильтров: чекбокс, радио, выпадающий список и т.д.
 */

if (!function_exists('woof_print_tax') && !is_admin()) {

    function woof_print_tax($taxonomies, $tax_slug, $terms, $exclude_tax_key, $taxonomies_info, $additional_taxes, $woof_settings, $args, $counter) {



        if ($exclude_tax_key == $tax_slug) {
            if (empty($terms)) {
                return;
            }
        }


        if (empty($terms)) {
            return;
        }
        //***

        if (!woof_only($tax_slug, 'taxonomy')) {
            return;
        }

        //***

        if ($tax_slug === 'product_cat') {  // Проверяем, является ли это категорией продукта
            $terms = array_filter($terms, function($term) {
                return $term['slug'] !== 'uncategorized';  // Исключаем терм "Без рубрики"
            });
        }
        usort($terms, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $terms = array_filter($terms, function($term) {
            return $term['count'] > 0;
        });
        $args['taxonomy_info'] = $taxonomies_info[$tax_slug];
        $args['tax_slug'] = $tax_slug;
        $args['terms'] = $terms;
        $args['all_terms_hierarchy'] = $taxonomies[$tax_slug];
        $args['additional_taxes'] = $additional_taxes;

        //***
        $woof_container_styles = "";
        if ($woof_settings['tax_type'][$tax_slug] == 'radio' OR $woof_settings['tax_type'][$tax_slug] == 'checkbox') {
            if (isset(woof()->settings['tax_block_height']) && woof()->settings['tax_block_height'][$tax_slug] > 0) {
                $woof_container_styles = "max-height:" . sanitize_text_field(woof()->settings['tax_block_height'][$tax_slug]) . "px; overflow-y: auto;";
            }
        }
        //***
        //https://wordpress.org/support/topic/adding-classes-woof_container-div
        $primax_class = sanitize_key(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]));
        ?>
    <div data-css-class="woof_container_<?php echo esc_attr($tax_slug) ?>" class="woof_container woof_container_<?php echo esc_attr($woof_settings['tax_type'][$tax_slug]) ?> woof_container_<?php echo esc_attr($tax_slug) ?> woof_container_<?php echo esc_attr($counter) ?> woof_container_<?php echo esc_attr($primax_class) ?>">
        <div class="woof_container_overlay_item"></div>
    <div class="woof_container_inner woof_container_inner_<?php echo esc_attr($primax_class) ?>">
        <?php
        $css_classes = "woof_block_html_items";
        $show_toggle = 0;
        if (isset(woof()->settings['show_toggle_button'][$tax_slug])) {
            $show_toggle = (int) woof()->settings['show_toggle_button'][$tax_slug];
        }
        $tooltip_text = "";
        if (isset(woof()->settings['tooltip_text'][$tax_slug])) {
            $tooltip_text = woof()->settings['tooltip_text'][$tax_slug];
        }
        //***
        $search_query = woof()->get_request_data();
        $block_is_closed = true;
        if (in_array($tax_slug, array_keys($search_query))) {
            $block_is_closed = false;
        }
        if ($show_toggle === 1 AND!in_array($tax_slug, array_keys($search_query))) {
            $css_classes .= " woof_closed_block";
        }

        if ($show_toggle === 2 AND!in_array($tax_slug, array_keys($search_query))) {
            $block_is_closed = false;
        }

        if (in_array($show_toggle, array(1, 2))) {
            $block_is_closed = apply_filters('woof_block_toggle_state', $block_is_closed);
            if ($block_is_closed) {
                $css_classes .= " woof_closed_block";
            } else {
                $css_classes = str_replace('woof_closed_block', '', $css_classes);
            }
        }

        //***
        switch ($woof_settings['tax_type'][$tax_slug]) {
            case 'checkbox':
                if (woof()->settings['show_title_label'][$tax_slug]) {
                    ?>
                    <<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php echo esc_html(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug])) ?>
                    <?php WOOF_HELPER::draw_tooltipe(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]), $tooltip_text) ?>
                    <?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?>
                    </<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php
                }

                if (!empty($woof_container_styles)) {
                    $css_classes .= " woof_section_scrolled";
                }
                ?>
                <div class="<?php echo esc_attr($css_classes) ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo wp_kses_post(wp_unslash($woof_container_styles)) ?>"<?php endif; ?>>
                    <?php
                    woof()->render_html_e(apply_filters('woof_html_types_view_checkbox', WOOF_PATH . 'views/html_types/checkbox.php'), $args);
                    ?>
                </div>
                <?php
                break;
            case 'select':
                if (woof()->settings['show_title_label'][$tax_slug]) {
                    ?>
                    <<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php echo esc_html(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug])) ?>
                    <?php WOOF_HELPER::draw_tooltipe(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]), $tooltip_text) ?>
                    <?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?>
                    </<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php
                }
                ?>
                <div class="<?php echo esc_html($css_classes) ?>">
                    <?php
                    woof()->render_html_e(apply_filters('woof_html_types_view_select', WOOF_PATH . 'views/html_types/select.php'), $args);
                    ?>
                </div>
                <?php
                break;
            case 'mselect':
                if (woof()->settings['show_title_label'][$tax_slug]) {
                    ?>
                    <<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php echo esc_html(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug])) ?>
                    <?php WOOF_HELPER::draw_tooltipe(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]), $tooltip_text) ?>
                    <?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?>
                    </<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php
                }
                ?>
                <div class="<?php echo esc_html($css_classes) ?>">
                    <?php
                    woof()->render_html_e(apply_filters('woof_html_types_view_mselect', WOOF_PATH . 'views/html_types/mselect.php'), $args);
                    ?>
                </div>
                <?php
                break;

            default:
                if (woof()->settings['show_title_label'][$tax_slug]) {
                    $title = WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]);
                    $title = explode('^', $title); //for hierarchy drop-down and any future manipulations
                    if (isset($title[1])) {
                        $title = $title[1];
                    } else {
                        $title = $title[0];
                    }
                    ?>
                    <<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php esc_html_e($title) ?>
                    <?php WOOF_HELPER::draw_tooltipe($title, $tooltip_text) ?>
                    <?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?>
                    </<?php esc_html_e(apply_filters('woof_title_tag', 'h4')); ?>>
                    <?php
                }

                if (!empty($woof_container_styles)) {
                    $css_classes .= " woof_section_scrolled";
                }
                ?>

                <div class="<?php echo esc_attr($css_classes) ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo wp_kses_post(wp_unslash($woof_container_styles)) ?>"<?php endif; ?>>
                    <?php
                    if (!empty(WOOF_EXT::$includes['taxonomy_type_objects'])) {
                        $is_custom = false;
                        foreach (WOOF_EXT::$includes['taxonomy_type_objects'] as $obj) {
                            if ($obj->html_type == $woof_settings['tax_type'][$tax_slug]) {
                                $is_custom = true;
                                $args['woof_settings'] = $woof_settings;
                                $args['taxonomies_info'] = $taxonomies_info;

                                woof()->render_html_e($obj->get_html_type_view(), $args);
                                break;
                            }
                        }


                        if (!$is_custom) {
                            woof()->render_html_e(apply_filters('woof_html_types_view_radio', WOOF_PATH . 'views/html_types/radio.php'), $args);
                        }
                    } else {
                        woof()->render_html_e(apply_filters('woof_html_types_view_radio', WOOF_PATH . 'views/html_types/radio.php'), $args);
                    }
                    ?>

                </div>
                <?php
                break;
        }
        ?>

        <input type="hidden" name="woof_t_<?php echo esc_attr($tax_slug) ?>" value="<?php echo esc_html($taxonomies_info[$tax_slug]->labels->name) ?>" /><!-- for red button search nav panel -->

        </div>
        </div>
        <?php
    }

}
add_filter('gettext', 'change_related_products_text', 10, 3);


function change_related_products_text($translated_text, $text, $domain) {
    if ($domain === 'cenos') {
        $translations = array(
            'Related products' => 'Похожие',
            'Shopping cart' => 'Корзина',
            'Shopping Cart' => 'Корзина',
            'Shipping options will be updated during checkout.' => 'Варианты доставки будут обновлены во время оформления заказа.',
            'Checkout' => 'Оформление заказа',
            'Order Complete' => 'Заказ завершен',
            'Cart' => 'Корзина',
            'Subtotal' => 'Итого',
            'Your order' => 'Ваш заказ',
            'Total' => 'Итого',
            'Latest Posts' => 'Новости',
            'Update cart' => 'Обновить корзину',
            'Search' => 'Поиск',
            'Categories' => 'Категории',
            'All' => 'Все',
            'Recent posts' => 'Последние записи',
            'Posted' => 'Опубликовано',
            'Archives' => 'Архивы',
            'Meta' => 'Мета',
        );

        if (isset($translations[$text])) {
            return $translations[$text];
        }
    }

    return $translated_text;
}

add_filter('woocommerce_get_privacy_policy_text', 'change_privacy_policy_text', 10, 2);

function change_privacy_policy_text($text, $type) {
    if ($type === 'checkout') {
        $text = sprintf(__('Ваши персональные данные будут использованы для обработки вашего заказа, поддержки вашего опыта на этом веб-сайте и для других целей, описанных в нашей %s.', 'woocommerce'), '[privacy_policy]');
    }

    if ($type === 'registration') {
        $text = sprintf(__('Ваши персональные данные будут использованы для поддержки вашего опыта на этом веб-сайте, управления доступом к вашему аккаунту и для других целей, описанных в нашей %s.', 'woocommerce'), '[privacy_policy]');
    }

    return $text;
}
add_filter('breadcrumb_trail', 'customize_my_other_breadcrumb', 10, 2);

function customize_my_other_breadcrumb($breadcrumb, $args) {
    $dom = new DOMDocument;
    @$dom->loadHTML(mb_convert_encoding($breadcrumb, 'HTML-ENTITIES', 'UTF-8'));

    $ul = $dom->getElementsByTagName('ul')->item(0);

    // Проходимся по всем элементам списка, чтобы изменить нужные нам тексты
    foreach ($ul->childNodes as $li) {
        if ($li->nodeType == 1) { // DOMElement
            $span = $li->getElementsByTagName('span')->item(0);
            if ($span && $span->getAttribute('itemprop') === 'name') {
                $text = $span->nodeValue;

                if ($text === 'Home') {
                    $span->nodeValue = 'Главная';
                } elseif ($text === 'Checkout') {
                    $span->nodeValue = 'Оформление заказа';
                }
            }
        }
    }

    $newBreadcrumb = $dom->saveHTML($ul);
    return $newBreadcrumb;
}
function custom_image_sizes($sizes) {
    // Удалите размеры, которые вам не нужны
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['medium_large']);
    unset($sizes['large']);
    unset($sizes['150x150']);
    unset($sizes['50x50']);
    unset($sizes['768x768']);
    unset($sizes['100x100']);
    //unset($sizes['115x115']);

    // Добавьте только необходимые размеры превью
    $sizes['300x300'] = 'Custom Size 1';
    $sizes['600x600'] = 'Custom Size 2';

    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'custom_image_sizes');
