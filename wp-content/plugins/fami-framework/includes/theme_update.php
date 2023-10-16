<?php
if( !class_exists('Fmfw_Theme_Update')){
    class Fmfw_Theme_Update{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ),999 );
            add_action( 'upgrader_process_complete', array( $this, 'clear_theme_update_transient' ),99 );
        }
        public function check_for_update($transient) {
            $exclude_themes = ['helik'];
            if (in_array(FAMI_THEME_SLUG,$exclude_themes)){
                return $transient;
            }
            $this->clear_theme_update_transient();
            $update = $this->theme_version();
            if ($update && $this->theme_has_update()){
                $response = array(
                    'url'         => esc_url(FAMILAB_DOC_URL.'/'.FAMI_THEME_SLUG.'/changelogs/'),
                    'new_version' => $update['new_version'],
                );
                $transient->response[ FAMI_THEME_SLUG ] = $response;
                // If the purchase code is valide, user can get the update package
                $license_key = get_option('familab_license_key'.FAMI_THEME_SLUG);
                $market = Fmfw_Market_Check::instance();
                $check_license = $market->check_license($license_key);
                if ($check_license) {
                    $parse = parse_url(get_site_url());
                    $back_uri = $parse['host'];
                    $transient->response[ FAMI_THEME_SLUG ]['package'] = FAMILAB_API_URL.'/package/'.FAMI_THEME_SLUG.'/'.$license_key.'/'.md5($back_uri).'/'.$update['new_version'].'.zip';
                } else {
                    unset( $transient->response[ FAMI_THEME_SLUG ]['package'] );
                }
            }
            return $transient;
        }

        public function clear_theme_update_transient() {
            delete_transient('theme_version_' . FAMI_THEME_SLUG);
            delete_transient('theme_changelog_' . FAMI_THEME_SLUG);
        }

        public function theme_version()
        {
            $theme_version = get_transient('theme_version_' . FAMI_THEME_SLUG);
            if ($theme_version === false) {
                $new_vesion = 0;
                $updates = $this->RequestChangeLog();
                if (is_array($updates)) {
                    foreach ($updates as $key => $val) {
                        if (version_compare($key, FAMI_THEME_VERSION) == 1) {
                            $new_vesion = array();
                            $new_vesion['new_version'] = $key;
                            $new_vesion['time'] = $val['time'];
                            break;
                        }
                    }
                }
                set_transient('theme_version_' . FAMI_THEME_SLUG, $new_vesion, DAY_IN_SECONDS);
            } else {
                $new_vesion = $theme_version;
            }
            return $new_vesion;
        }
        public function theme_has_update()
        {
            $theme_version = $this->theme_version();
            if (isset($theme_version['new_version']) && version_compare($theme_version['new_version'], FAMI_THEME_VERSION, '>')) {
                return true;
            } else {
                return false;
            }
        }
        public function RequestChangeLog()
        {
            $theme_changelog = get_transient('theme_changelog_' . FAMI_THEME_SLUG);
            if ($theme_changelog === false) {
                $parse = parse_url(get_site_url());
                $back_uri = $parse['host'];
                //new-changelog
                $request = wp_remote_get(FAMILAB_API_URL . '/new-changelog/' . FAMI_THEME_SLUG.'/'.$back_uri, array('timeout' => 120));
                if (is_wp_error($request)) {
                    set_transient('theme_changelog_' . FAMI_THEME_SLUG, null, DAY_IN_SECONDS);
                    return;
                } else {
                    $theme_changelog = wp_remote_retrieve_body($request);
                    set_transient('theme_changelog_' . FAMI_THEME_SLUG, $theme_changelog, DAY_IN_SECONDS);
                    return json_decode($theme_changelog, true);
                }
            } else {
                if (is_null($theme_changelog)) {
                    return;
                }
            }
            return json_decode($theme_changelog, true);
        }
    }
}

Fmfw_Theme_Update::instance();
