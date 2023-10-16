<?php

if (!class_exists('Fmfw_Plugin_Update')) {
    class Fmfw_Plugin_Update{
        private static $_instance;
        private $plugins;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function __construct() {
            add_filter('pre_set_site_transient_update_plugins',array($this,'transient_update_plugins'),999);

        }
        public function getPluginsVersion(){
            $plugins_version = get_transient('fmfw_plugins_version');
            if ($plugins_version === false) {
                $request = wp_remote_get( FAMILAB_API_URL .'/plugins/'.FAMI_THEME_SLUG, array( 'timeout' => 120 ) );
                if ( is_wp_error( $request ) ) {
                    return;
                }else{
                    $plugins_version = wp_remote_retrieve_body( $request );
                    set_transient('fmfw_plugins_version',$plugins_version,DAY_IN_SECONDS);
                    return json_decode( $plugins_version, true );
                }
            }else{
                if (is_null($plugins_version)){
                    return;
                }
            }
            return json_decode( $plugins_version, true );
        }
        public function transient_update_plugins($checked_data){
            //Comment out these two lines during testing.
            if (empty($checked_data->checked))
                return $checked_data;
            if (class_exists('TGM_Plugin_Activation')){
                $tgm_instance = TGM_Plugin_Activation::get_instance();
                $this->plugins = $tgm_instance->plugins;
                delete_transient('fmfw_plugins_version');
                $plugin_version = $this->getPluginsVersion();
                if (empty($plugin_version)){
                    return $checked_data;
                }
                $installed_plugins = $tgm_instance->get_plugins();
                foreach ($plugin_version as $plugin_slug => $host_version){
                    if (isset($this->plugins[$plugin_slug])){
                        if ($this->plugins[$plugin_slug]['source_type'] != 'external'){
                            continue;
                        }
                        $file_path = $this->plugins[$plugin_slug]['file_path'];
                        if (isset($installed_plugins[$file_path])){
                            if (version_compare($host_version, $installed_plugins[$file_path]['Version'],'>')){
                                if ( empty( $checked_data->response[ $file_path ] ) ) {
                                    $checked_data->response[ $file_path ] = new stdClass;
                                }
                                $checked_data->response[ $file_path ]->slug        = $plugin_slug;
                                $checked_data->response[ $file_path ]->plugin      = $file_path;
                                $checked_data->response[ $file_path ]->new_version = $host_version;
                                $checked_data->response[ $file_path ]->package     = $this->plugins[$plugin_slug]['source'];
                                if ( empty( $checked_data->response[ $file_path ]->url ) && ! empty( $this->plugins[$plugin_slug]['external_url'] ) ) {
                                    $checked_data->response[ $file_path ]->url = $this->plugins[$plugin_slug]['external_url'];
                                }
                            }
                        }
                    }
                }
            }
            return $checked_data;
        }
    }
}
Fmfw_Plugin_Update::instance();