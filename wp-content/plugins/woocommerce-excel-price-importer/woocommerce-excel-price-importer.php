<?php
/**
 * Plugin Name: WooCommerce Excel Price Importer
 * Description: Универсальный плагин для импорта и обновления товаров в WooCommerce из Excel прайс-листов. Включает в себя визуальный интерфейс для удобной настройки и управления импортом.
 * Version: 1.0
 * Author: Евгений
 * Author URI: https://t.me/evsch999
 */

define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once plugin_dir_path(__FILE__) . 'inc/initializers/Initializer.php';

// Run the initializer
$initializer = new Initializer();
function download_and_save_image_from_yandex_disk() {
    $image_url = 'https://disk.yandex.ru/d/xVUfMGLnK_yTog/Fixsen/Magic%20Black/FX_45005A_01.jpg';
    $upload_dir = wp_upload_dir(); // Получаем папку для загрузки изображений
    $image_filename = basename($image_url); // Имя файла

    // Формируем путь для сохранения
    $target_path = trailingslashit($upload_dir['path']) . $image_filename;

    // Используем функцию WordPress для сохранения файла
    $response = wp_safe_remote_get($image_url);

    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $body = wp_remote_retrieve_body($response);

        // Сохраняем изображение на сервере
        if (file_put_contents($target_path, $body)) {
            echo 'Изображение успешно сохранено: ' . $target_path;
        } else {
            echo 'Ошибка при сохранении изображения.';
        }
    } else {
        echo 'Ошибка при загрузке изображения.';
    }
}

// Запускаем функцию
//download_and_save_image_from_yandex_disk();
