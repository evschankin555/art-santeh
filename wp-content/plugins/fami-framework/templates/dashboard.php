<?php
    $tab_active = isset($_GET['tab']) ? $_GET['tab'] : '';
    //$current_theme = Fami_Framework::instance()->current_theme;
    $theme_update = Fmfw_Theme_Update::instance();
    $transient_update_themes = get_option('_site_transient_update_themes');
    if (isset($transient_update_themes->response[FAMI_THEME_SLUG]['new_version'])) {
        $theme_version = $transient_update_themes->response[FAMI_THEME_SLUG]['new_version'];
    } elseif (isset($transient_update_themes->checked[FAMI_THEME_SLUG])) {
        $theme_version = $transient_update_themes->checked[FAMI_THEME_SLUG];
    } else {
        $requested_ver =  $theme_update->theme_version();
        if (isset($requested_ver['new_version']))
            $theme_version = $requested_ver['new_version'];
        else
            $theme_version = FAMI_THEME_VERSION;
    }

    $has_update =  $theme_update->theme_has_update();
    $server_info = [
        'docs' => 'https://docs.familab.net/',
        'faq' => 'https://support.familab.net/knowledgebase/',
        'support' => 'https://support.familab.net/submit-ticket/',
    ];
    $server_info = apply_filters('fmfw_server_infomation',$server_info);
?>
<div class="container-fluid tr-container import-sample-data-wrap">
    <div class="dashboard-head text-center hidden">
        <div class="fmfw-theme-thumb">
            <img class="dashboard-logo" src="">
        </div>
        <div class="theme-desc">

        </div>
    </div>
    <div id="tr-tabs-container">
        <ul class="nav-tab-wrapper">
            <li class="tr-tab <?php if ($tab_active == '' || $tab_active == 'welcome') {
                echo 'active';
            } ?>" data-tab="welcome"><?php esc_html_e('Welcome', 'fami-framework'); ?></li>
            <li class="tr-tab <?php if ($tab_active == 'preset') {
                echo 'active';
            } ?>" data-tab="preset"><?php esc_html_e('Customize Preset', 'fami-framework'); ?></li>
            <li class="tr-tab <?php if ($tab_active == 'license') {
                echo 'active';
            } ?>" data-tab="license"><?php esc_html_e('License', 'fami-framework'); ?></li>

            <li class="tr-tab <?php if ($tab_active == 'system') {
                echo 'active';
            } ?>" data-tab="system"><?php esc_html_e('System', 'fami-framework'); ?></li>
        </ul>
        <div class="tab-content tr-tab-content">
            <div id="welcome" class="tab-pane <?php echo ($tab_active == '' || $tab_active == 'welcome')? 'active':'';?>" role="tabpanel">
                <div class="container content-left">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="postbox tr-box">
                                <h1>
                                    <?php esc_html_e('Welcome to', 'fami-framework');
                                    echo ' ' . FAMI_THEME_NAME; ?>
                                </h1>
                                <div class="welcome-text">
                                    <?php printf('%s<br/>%s',
                                        sprintf(__('Thank you for choosing %s. This is a great theme for any e-commerce purpose or simply blogging, news.', 'fami-framework'),
                                            FAMI_THEME_NAME
                                        ),
                                        sprintf('You can easily customize your website without the knowledge of code.', 'fami-framework')); ?>
                                </div>
                                <div class="row">
                                    <div class="col-xl-8 col-lg-12">
                                        <div class="text-center">
                                            <img class="theme_demo"
                                                 src="<?php echo esc_url(FAMI_THEME_URI . '/assets/images/theme_demo.png') ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12">
                                        <div class="welcome-panel tr-panel">
                                            <h3>Theme update</h3>
                                            <div class="welcome-icon dashicons-megaphone">
                                                <div class="version-info">
                                                    <div class="vesion-title">
                                                        <?php echo 'Installed Version' ?>
                                                    </div>
                                                    <div class="versions">
                                                        <?php echo FAMI_THEME_VERSION;?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="welcome-icon dashicons-cloud">
                                                <div class="version-info">
                                                    <?php if (isset($theme_version) && $has_update) { ?>
                                                        <div class="vesion-title"><?php esc_html_e('Latest Available Version', 'fami-framework'); ?></div>
                                                        <div class="versions"><?php echo esc_html($theme_version) ?></div>
                                                    <?php } else { ?>
                                                        <div class="vesion-title"><?php esc_html_e('Your theme is up to date!', 'fami-framework'); ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php if ($has_update) { ?>
                                                <div class="update-info">
                                                    <?php esc_html_e('The latest version of this theme', 'fami-framework'); ?>
                                                    <br>
                                                    <?php esc_html_e('is available, ', 'fami-framework'); ?>
                                                    <p>
                                                        <a class="tr-tab-ex show-update tr-button" href="#"
                                                           data-tab="update"><?php esc_html_e('update today!', 'fami-framework'); ?></a>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="welcome-panel-content">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-12">
                                        <div class="postbox tr-box">
                                            <div class="welcome-panel">
                                                <h3><?php esc_html_e('Quick Setings', 'fami-framework'); ?></h3>
                                                <ul>
                                                    <li>
                                                        <a class="welcome-icon dashicons-edit" href="<?php echo esc_url(admin_url('themes.php?page='.FAMI_THEME_SLUG.'-setup')); ?>"
                                                           data-tab="import">
                                                            <?php esc_html_e('Theme Setup Wizard', 'fami-framework'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="welcome-icon dashicons-admin-generic" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
                                                            <?php esc_html_e('Theme Options', 'fami-framework'); ?>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12">
                                        <div class="postbox tr-box">
                                            <div class="welcome-panel">
                                                <h3><?php esc_html_e('Support', 'fami-framework'); ?></h3>
                                                <ul>
                                                    <?php if (!empty($server_info) && isset($server_info['docs'])):?>
                                                        <li><a class="welcome-icon dashicons-media-document"
                                                               target="_blank"
                                                               href="<?php echo $server_info['docs'].FAMI_THEME_SLUG;?>"><?php esc_html_e('Online Documentation', 'fami-framework'); ?></a>
                                                        </li>
                                                    <?php endif;?>
                                                    <?php if (!empty($server_info) && isset($server_info['faq'])):?>
                                                        <li><a class="welcome-icon dashicons-editor-ol"
                                                               target="_blank"
                                                               href="<?php echo $server_info['faq'];?>"><?php esc_html_e('FAQs', 'fami-framework'); ?></a>
                                                        </li>
                                                    <?php endif;?>
                                                    <?php if (!empty($server_info) && isset($server_info['support'])):?>
                                                        <li><a class="welcome-icon dashicons-editor-help"
                                                               target="_blank"
                                                               href="<?php echo $server_info['support'];?>"><?php esc_html_e('Request Support', 'fami-framework'); ?></a>
                                                        </li>
                                                    <?php endif;?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="changelog">
                                <?php
                                $changelog = $theme_update->RequestChangeLog();
                                if (is_array($changelog) && count($changelog) > 0) {
                                    $nb_ver = 0;
                                    ?>
                                    <table class="wp-list-table widefat striped changelogs">
                                        <thead>
                                        <th>Time</th>
                                        <th>Version</th>
                                        <th>Description</th>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($changelog as $ver => $l) {
                                            $nb_ver++;
                                            if ($nb_ver > 6){
                                                break;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo ($l['time']); ?></td>
                                                <td><?php echo ($ver); ?></td>
                                                <td><?php echo ($l['desc']); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="preset" class="tab-pane <?php echo ($tab_active == 'preset')? 'active':''; ?>" role="tabpanel">
                <?php include 'preset.php';?>
            </div>
            <div id="license" class="tab-pane <?php echo ($tab_active == 'license')? 'active':''; ?>" role="tabpanel">
                <?php Fmfw_Market_Check::instance()->license_form();?>
            </div>
            <div id="system" class="tab-pane <?php echo ($tab_active == 'system') ? 'active':'';?>" role="tabpanel">
                <div class="postbox-content">
                    <h2><?php esc_html_e('Server Environment', 'fami-framework') ?></h2>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('Server Info', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php echo esc_html($_SERVER['SERVER_SOFTWARE']); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('Information about the web server that is currently hosting your site.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('PHP Version', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php if (function_exists('phpversion')) {
                                    $php_version = esc_html(phpversion());
                                    if (version_compare($php_version, '5.6', '<')) {
                                        echo '<mark class="error">' . FAMILAB_THEME_NAME . esc_html__(' requires PHP version 5.6 or greater. Please contact your hosting provider to upgrade PHP version.', 'fami-framework') . '</mark>';
                                    } else {
                                        echo ($php_version);
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('The version of PHP installed on your hosting server.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <?php if (function_exists('ini_get')) : ?>
                        <div class="container content-left fmfw-wrap">
                            <div class="row fmfw-row">
                                <div class="col-sm-2 title">
                                    <?php _e('PHP Post Max Size', 'fami-framework'); ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php echo ini_get('post_max_size'); ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php _e('The largest file size that can be contained in one post.', 'fami-framework') ?>
                                </div>
                            </div>
                        </div>
                        <div class="container content-left fmfw-wrap">
                            <div class="row fmfw-row">
                                <div class="col-sm-2 title">
                                    <?php _e('PHP Time Limit', 'fami-framework'); ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php
                                    $time_limit = ini_get('max_execution_time');
                                    if ($time_limit > 0 && $time_limit < 180) {
                                        echo '<mark class="error">' . sprintf(__('%s - We recommend setting max execution time to at least 180. See: <a href="%s" target="_blank">Increasing max execution to PHP</a>', 'fami-framework'), $time_limit, 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded') . '</mark>';
                                    } else {
                                        echo '<mark class="yes">' . $time_limit . '</mark>';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php _e('The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'fami-framework') ?>
                                </div>
                            </div>
                        </div>
                        <div class="container content-left fmfw-wrap">
                            <div class="row fmfw-row">
                                <div class="col-sm-2 title">
                                    <?php _e('PHP Max Input Vars', 'fami-framework'); ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php
                                    $max_input_vars = ini_get('max_input_vars');
                                    if ($max_input_vars < 5000) {
                                        echo '<mark class="error">' . sprintf(__('%s - We recommend setting max input vars to at least 5000, this limitation will truncate POST data such as menus.', 'fami-framework'), $max_input_vars) . '</mark>';
                                    } else {
                                        echo '<mark class="yes">' . $max_input_vars . '</mark>';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-5">
                                    <?php _e('The maximum number of variables your server can use for a single function to avoid overloads.', 'fami-framework') ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('MySQL Version', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                global $wpdb;
                                echo ($wpdb->db_version());
                                ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('The version of MySQL installed on your hosting server.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('Max Upload Size', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php echo size_format(wp_max_upload_size()); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('The largest file size that can be uploaded to your WordPress installation.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('Default Timezone is UTC', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $default_timezone = date_default_timezone_get();
                                if ('UTC' !== $default_timezone) {
                                    echo '<mark class="error">&#10005; ' . sprintf(__('Default timezone is %s - it should be UTC', 'fami-framework'), $default_timezone) . '</mark>';
                                } else {
                                    echo '<mark class="yes">&#10004;</mark>';
                                } ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('The default timezone for your server.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('fsockopen/cURL', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                if (function_exists('fsockopen') || function_exists('curl_init')) {
                                    echo '<mark class="yes">&#10004;</mark>';
                                } else {
                                    echo '<mark class="error">&#10005; ' . _e('Your server does not have fsockopen or cURL enabled. Please contact your hosting provider to enable it.', 'fami-framework') . '</mark>';
                                } ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('Plugins may use it when communicating with remote services.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('DOMDocument', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                if (class_exists('DOMDocument')) {
                                    echo '<mark class="yes">&#10004;</mark>';
                                } else {
                                    echo '<mark class="error">&#10005; ' . sprintf(__('Your server does not have <a href="%s">the DOM extension</a> class enabled. Please contact your hosting provider to enable it.', 'fami-framework'), 'http://php.net/manual/en/intro.dom.php') . '</mark>';
                                } ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('WordPress Importer use DOMDocument.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('XMLReader', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                if (class_exists('XMLReader')) {
                                    echo '<mark class="yes">&#10004;</mark>';
                                } else {
                                    echo '<mark class="error">&#10005; ' . sprintf(__('Your server does not have <a href="%s">the XMLReader extension</a> class enabled. Please contact your hosting provider to enable it.', 'fami-framework'), 'http://php.net/manual/en/intro.xmlreader.php') . '</mark>';
                                } ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('WordPress Importer use XMLReader.', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container content-left fmfw-wrap">
                        <div class="row fmfw-row">
                            <div class="col-sm-2 title">
                                <?php _e('Sever status', 'fami-framework'); ?>
                            </div>
                            <div class="col-sm-5">
                                <?php
                                $check_familab_remote = get_transient('check_familab_remote');
                                if ($check_familab_remote === false) {
                                    $response = wp_remote_get(FAMILAB_API_URL . '/changelog/' . FAMI_THEME_SLUG, array('timeout' => 120));
                                    if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300) {
                                        set_transient('check_familab_remote', 'yes', DAY_IN_SECONDS);
                                        echo '<mark class="yes">&#10004;</mark>';
                                    } else {
                                        set_transient('check_familab_remote', 'no', DAY_IN_SECONDS);
                                        echo '<mark class="error">&#10005; ' . _e(' WordPress function <a href="https://codex.wordpress.org/Function_Reference/wp_remote_get">wp_remote_get()</a> test failed. Please contact your hosting provider to enable it.', 'fami-framework') . '</mark>';
                                    }
                                } else {
                                    if ($check_familab_remote == 'yes') {
                                        echo '<mark class="yes">&#10004;</mark>';
                                    } else {
                                        echo '<mark class="error">&#10005; ' . _e(' WordPress function <a href="https://codex.wordpress.org/Function_Reference/wp_remote_get">wp_remote_get()</a> test failed. Please contact your hosting provider to enable it.', 'fami-framework') . '</mark>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-sm-5">
                                <?php _e('Retrieve the raw response from the HTTP request using the GET method. (wp_remote_get function)', 'fami-framework') ?>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo esc_url(admin_url( 'admin.php?page=fami-dashboard&refresh_data=fmfw_reset' )); ?>"><?php esc_html_e('Refresh Theme Info', 'fami-framework') ?></a>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
