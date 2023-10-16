<?php
class YandexOAuthHandler {
    public function __construct() {
        add_action('wp_ajax_save_yandex_token', [$this, 'save_yandex_token']);
        add_action('wp_ajax_nopriv_save_yandex_token', [$this, 'save_yandex_token']);

        add_action('init', [$this, 'handle_yandex_oauth_callback']);
        add_action('generate_rewrite_rules', [$this, 'add_custom_rewrite_rule']);
        add_filter('query_vars', [$this, 'add_custom_query_var']);
        add_action('template_redirect', [$this, 'template_redirect_intercept'], 1);
        add_filter('template_include', [$this, 'override_template'], 99);
        add_action('wp_footer', [$this, 'display_yandex_oauth_status']);
    }

    public function handle_yandex_oauth_callback() {
        if (isset($_GET['page']) && $_GET['page'] == 'yandex_oauth_callback') {
            if (isset($_GET['access_token'])) {
                $token = sanitize_text_field($_GET['access_token']);
                update_option('yandex_oauth_token', $token);
                error_log('Token updated, redirecting...');
                wp_redirect(admin_url());
                exit;
            } else {
                error_log('No access_token found.');
            }
        }
    }

    public function add_custom_query_var($vars){
        $vars[] = 'yandex_oauth_callback';
        return $vars;
    }

    public function add_custom_rewrite_rule($wp_rewrite){
        $new_rules = array(
            '^yandex_oauth_callback$' => 'index.php?yandex_oauth_callback=1'
        );
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    public function template_redirect_intercept(){
        global $wp_query;
        if (isset($wp_query->query_vars['yandex_oauth_callback'])) {
            if (isset($_GET['access_token'])) {
                $token = sanitize_text_field($_GET['access_token']);
                update_option('yandex_oauth_token', $token);
                error_log('Token updated in template_redirect, redirecting...');
                wp_redirect(admin_url());
                exit;
            } else {
                error_log('No access_token found in template_redirect.');
            }
        }
    }

    public function override_template($template) {
        if (is_page('yandex_oauth_callback')) {
            $new_template = locate_template(array('yandex-oauth-handler.php'));
            if ('' != $new_template) {
                return $new_template;
            }
        }
        return $template;
    }

    public function display_yandex_oauth_status() {
        $status = get_option('yandex_oauth_token') ? 'Authenticated' : 'Not authenticated';
        echo '<div id="yandex_oauth_token">' . $status . '</div>';
    }
    public function save_yandex_token() {
        if (isset($_POST['access_token'])) {
            $token = sanitize_text_field($_POST['access_token']);
            update_option('yandex_oauth_token', $token);
            wp_send_json_success(['message' => 'Token saved']);
        } else {
            wp_send_json_error(['message' => 'No access_token found']);
        }
    }
}
