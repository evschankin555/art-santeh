<?php
if (!function_exists('fmfw_update_customize_preset')) {
    function fmfw_update_customize_preset($preset_json) {
        $mods = get_theme_mods();
        $preset_values = json_decode($preset_json, true);
        if (is_array($preset_values)) {
            foreach ($preset_values as $control_key => $value) {
                if (strpos($control_key,'goto') !== false || strpos($control_key, 'skip_divider') !== false || strpos($control_key, 'group_hd') !== false) {
                    continue;
                }
                $old_value = $value;
                if (isset($mods[ $control_key ])){
                    $old_value = $mods[ $control_key ];
                }
                $mods[ $control_key ] = apply_filters( "pre_set_theme_mod_{$control_key}", $value, $old_value );
            }
            $theme = get_option( 'stylesheet' );
            update_option( "theme_mods_$theme", $mods );
            fmfw_remove_old_customize_file();
            return true;
        }
        return false;
    }
}
if (!function_exists('fmfw_show_preset_element')) {
    function fmfw_show_preset_element($package) {
        if (empty($package))
            return;
        $result = fmfw_get_preset_from_json_dir($package);
        if (!empty($result)) : ?>
            <div class="row">
            <?php foreach ($result as $pre_key => $item) :
                $img_thumb = 'inc/preset/img/'.$package.'/'.$pre_key.'.jpg';
                if (!file_exists(trailingslashit(FAMI_THEME_DIR).$img_thumb)) {
                    $img_thumb = 'inc/preset/img/'.$package.'/'.$pre_key.'.png';
                    if (!file_exists(trailingslashit(FAMI_THEME_DIR).$img_thumb)) {
                        $img_thumb = 'inc/preset/img/placeholder.jpg';
                    }
                }
                ?>
                <div class="col col-md-6 col-xl-6">
                    <div class="card fmfw-card">
                        <img src="<?php echo FAMI_THEME_URI.$img_thumb;?>" class="card-img-top" alt="<?php echo $pre_key;?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $pre_key;?></h5>
                            <div class="actions-wrap">
                                <button class="btn btn-primary fmfw_preset_action" data-package="<?php echo $package;?>" data-version="<?php echo $pre_key;?>">Apply</button>
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif;
    }
}
if (!function_exists('fmfw_get_json_content')) {
    function fmfw_get_json_content($file_path) {
        if (file_exists($file_path)){
            global $wp_filesystem;
            // Initialize the WP filesystem, no more using 'file-put-contents' function
            if (empty($wp_filesystem)) {
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }
            if( $wp_filesystem) {
                return $wp_filesystem->get_contents( $file_path);
            }
        }
        return false;
    }
}

if (!function_exists('fmfw_get_preset_from_json_dir')) {
    function fmfw_get_preset_from_json_dir($dir_name){
        $result = [];
        foreach (glob(FAMI_THEME_DIR.'/inc/preset/json/'.$dir_name.'/*.json') as $filename) {
            $file_json_content = fmfw_get_json_content($filename);
            if (!empty($file_json_content)){
                $file_value = json_decode($file_json_content,true);
                $result[basename($filename,'.json')] = $file_value;
            }
        }
        return $result;
    }
}

if (!function_exists('fmfw_get_default_setting_of_mega_menu')) {
    function fmfw_get_default_setting_of_mega_menu() {
        return apply_filters('fmfw_default_setting_of_mega_menu', array());
    }
}

if (!function_exists('fmfw_remove_old_customize_file')) {
    function fmfw_remove_old_customize_file()
    {
        $customize_files =  ['customize','body_color','main_color'];
        foreach ($customize_files as $file_name) {
            $customize_file = $file_name.'.css';
            if (is_multisite()) {
                $current_blog_id = get_current_blog_id();
                if (!is_main_site($current_blog_id)) {
                    $customize_file = $file_name.'_' . $current_blog_id . '.css';
                }
            }
            $file_customize = FAMI_THEME_DIR . '/assets/css/' . $customize_file;
            if (file_exists($file_customize)) {
                unlink($file_customize);
            }
        }
        delete_transient('fami_woocommerce_products_new');
    }
}
if ( ! function_exists( 'fmfw_is_woocommerce_activated' ) ) {
    /**
     * Returns true if WooCommerce plugin is activated
     *
     * @return bool
     */
    function fmfw_is_woocommerce_activated() {
        return class_exists( 'WooCommerce' );
    }
}
if (!function_exists('fmfw_get_mobile_detect')){
    function fmfw_get_mobile_detect() {
        if (!class_exists('Mobile_Detect')) {
            require_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/lib/Mobile_Detect.php';
        }
        return new Mobile_Detect();
    }
}
if (!function_exists('fmfw_get_block_post_options')) {
    function fmfw_get_block_post_options( ) {
        $none_str = esc_html__('-- None --','fami-framework');
        $post_options = [false => $none_str];
        if (function_exists('fmtpl_get_block_post')){
            $posts = fmtpl_get_block_post('');
            if ( $posts ) {
                foreach ( $posts as $post ) {
                    $post_options[ $post->ID ] = $post->post_title;
                }
            }
        }
        return $post_options;
    }
}
