<?php
// Подключаем WordPress Core
require_once( '../../../wp-load.php' );

// Ваш код для обработки OAuth и сохранения токена
if (isset($_GET['access_token'])) {
    $token = sanitize_text_field($_GET['access_token']);
    update_option('yandex_oauth_token', $token);
    wp_redirect(admin_url());
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Yandex OAuth Handler</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var fragment = location.hash.substring(1);
            var params = {};
            fragment.split('&').forEach(function(param) {
                var keyValue = param.split('=');
                params[keyValue[0]] = keyValue[1];
            });

            if (params.access_token) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'save_yandex_token',
                        access_token: params.access_token
                    },
                    success: function(response) {
                        if (response.success) {
                            window.close();
                        }
                    }
                });
            }
        });
    </script>
</head>
<body>
<button onclick="window.close()">Close</button>
</body>
</html>
