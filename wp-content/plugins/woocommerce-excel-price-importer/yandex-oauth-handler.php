<?php
require_once( '../../../../../wp-load.php' );

// Подключаем WordPress Core
require_once('../../../../../wp-load.php');

// Ваш код для обработки OAuth и сохранения токена
if (isset($_GET['access_token'])) {
    $token = sanitize_text_field($_GET['access_token']);
    update_option('yandex_oauth_token', $token);
    wp_redirect(admin_url());
    exit;
} else {
    // Логирование или другие действия
}

// Если требуется, добавьте здесь HTML или другой код



