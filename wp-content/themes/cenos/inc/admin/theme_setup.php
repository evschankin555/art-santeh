<?php
if( !class_exists('Fmfw_Theme_Setup')){
    class Fmfw_Theme_Setup
    {
        private static $_instance;
        protected $page_slug;
        protected $page_url;
        protected $steps;
        protected $step;
        protected $tgmpa_instance;
        protected $theme;
        protected $theme_version;
        protected $tgmpa_menu_slug = 'tgmpa-install-plugins';
        protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';
        protected $demo_host = 'cenos.familab.net';
        protected $current_host;
        private $delay_posts = array();
        private $delay_meta = array();
        public static function instance()
        {
            if (!isset(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function __construct()
        {
            global $current_theme;
            $this->theme_version = $current_theme->get('Version');
            $this->page_slug = 'cenos-setup';
            $this->page_url = 'admin.php?page=' . $this->page_slug;
            $site_url = get_site_url();
            $site_url_data = parse_url($site_url);
            $this->current_host = $site_url_data['host'];
            add_filter('woocommerce_enable_setup_wizard','__return_false');
            add_action('after_switch_theme', array($this, 'switch_theme'));
            if (class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
                add_action('init', array($this, 'get_tgmpa_instanse'), 30);
                add_action('init', array($this, 'set_tgmpa_url'), 40);
            }
            add_action('admin_menu', array($this, 'admin_menus'), 30);
            add_action('admin_init', array($this, 'skip_admin_redirects_started_page'), 9);
            //maybe_redirect_to_getting_started
            add_action('admin_init', array($this, 'admin_redirects'), 30);
            add_action('admin_init', array($this, 'setup_wizard'), 30);
            add_filter('tgmpa_load', array($this, 'tgmpa_load'), 10, 1);
            add_action('wp_ajax_cenos_setup_plugins', array($this, 'ajax_plugins'));
            add_action('wp_ajax_cenos_setup_content', array($this, 'ajax_content'));
        }
        //define theme data for Import.
        public function theme_preset_data() {
            return [
                [
                    'slug'=> 'home-01',//page slug
                    'thumb' => 'home1.jpg',
                    'header' => 'header_home_01'// header preset style
                ],
                [
                    'slug'=> 'home-02',
                    'thumb' => 'home2.jpg',
                    'header' => 'header_home_02'
                ],
                [
                    'slug'=> 'home-03',//page slug
                    'thumb' => 'home3.jpg',
                    'header' => 'header_home_03'// header preset style
                ],
                [
                    'slug'=> 'home-04',//page slug
                    'thumb' => 'home4.jpg',
                    'header' => 'header_home_04'// header preset style
                ],
                [
                    'slug'=> 'home-05',//page slug
                    'thumb' => 'home5.jpg',
                    'header' => 'header_home_05'// header preset style
                ],
                [
                    'slug'=> 'home-06',//page slug
                    'thumb' => 'home6.jpg',
                    'header' => 'header_home_06'// header preset style
                ],
                [
                    'slug'=> 'home-07',//page slug
                    'thumb' => 'home7.jpg',
                    'header' => 'header_home_07'// header preset style
                ],
                [
                    'slug'=> 'home-09',//page slug
                    'thumb' => 'home9.jpg',
                    'header' => 'header_home_02'// header preset style
                ],
                [
                    'slug'=> 'home-10',//page slug
                    'thumb' => 'home10.jpg',
                    'header' => 'header_home_10'// header preset style
                ],
                [
                    'slug'=> 'home-11',//page slug
                    'thumb' => 'home11.jpg',
                    'header' => 'header_home_11'// header preset style
                ],
                [
                    'slug'=> 'home-12',//page slug
                    'thumb' => 'home12.jpg',
                    'header' => 'header_home_12'// header preset style
                ],
                [
                    'slug'=> 'home-13',//page slug
                    'thumb' => 'home13.jpg',
                    'header' => 'header_home_05'// header preset style
                ],
                [
                    'slug'=> 'home-14',//page slug
                    'thumb' => 'home14.jpg',
                    'header' => 'header_home_14'// header preset style
                ]
            ];
        }
        public function theme_slider_packages(){
            return ['slide-home-3','slide-home-4','slide-home-5','slide-home-6','slide-home-7','slide-home-9','slide-home-12','slide-home-13'];
        }

        public function tgmpa_load($status)
        {
            return is_admin() || current_user_can('install_themes');
        }
        public function switch_theme()
        {
            set_transient('_cenos_activation_redirect', 1);
        }
        public function skip_admin_redirects_started_page(){
            delete_option('activate-woo-variation-swatches');
            delete_transient('elementor_activation_redirect');
            delete_transient('_tinvwl_activation_redirect');
        }
        public function admin_redirects()
        {
            if (!get_transient('_cenos_activation_redirect') || get_option('cenos_theme_setup_complete', false)) {
                return;
            }
            delete_transient('_cenos_activation_redirect');
            wp_safe_redirect(admin_url($this->page_url));
            exit;
        }
        public function get_tgmpa_instanse()
        {
            $this->tgmpa_instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
        }
        public function set_tgmpa_url()
        {
            $this->tgmpa_menu_slug = (property_exists($this->tgmpa_instance, 'menu')) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
            $tgmpa_parent_slug = (property_exists($this->tgmpa_instance, 'parent_slug') && $this->tgmpa_instance->parent_slug !== 'themes.php') ? 'admin.php' : 'themes.php';
            $this->tgmpa_url = $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug;
        }
        public function admin_menus()
        {
            add_submenu_page(
                'themes.php',
                'Theme Setup Wizard',
                'Theme Setup Wizard',
                'manage_options',
                $this->page_slug,
                [$this, $this->page_slug]
            );
        }
        public function init_wizard_steps()
        {
            $this->steps = [
                'introduction' => [
                    'name' => 'Introduction',
                    'view' => [$this, 'theme_setup_introduction'],
                    'handler' => '',
                ],
            ];
            if (class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
                $this->steps['default_plugins'] = [
                    'name' => 'Plugins',
                    'view' => [$this, 'theme_setup_default_plugins'],
                    'handler' => '',
                ];
            }
            $this->steps['updates'] = [
                'name' => 'Activate',
                'view' => [$this, 'theme_setup_updates'],
                'handler' => '',
            ];
            $this->steps['default_content'] = [
                'name' => 'Content',
                'view' => [$this, 'theme_setup_default_content'],
                'handler' => '',
            ];
            $this->steps['design'] = [
                'name' => 'Logo & Design',
                'view' => [$this, 'theme_setup_logo_design'],
                'handler' => [$this, 'theme_setup_logo_design_save'],
            ];
            $this->steps['next_steps'] = [
                'name' => 'Ready!',
                'view' => [$this, 'theme_setup_ready'],
                'handler' => '',
            ];

        }
        public function setup_wizard()
        {
            if (empty($_GET['page']) || $this->page_slug !== $_GET['page']) {
                return;
            }
            if (ob_get_contents()){
                ob_end_clean();
            }
            global $current_theme;
            wp_enqueue_script('jquery-blockui', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/admin/jquery.blockUI.js', ['jquery'], $this->theme_version);
            wp_enqueue_script('cenos-setup', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/admin/theme_setup.js', ['jquery', 'jquery-blockui'], $this->theme_version);
            wp_localize_script('cenos-setup', 'cenos_setup_params', array(
                'tgm_plugin_nonce' => array(
                    'update' => wp_create_nonce('tgmpa-update'),
                    'install' => wp_create_nonce('tgmpa-install'),
                ),
                'tgm_bulk_url' => admin_url($this->tgmpa_url),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'wpnonce' => wp_create_nonce('cenos_setup_nonce'),
                'verify_text' => '...verifying',
            ));
            wp_enqueue_style('theme-setup', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/admin/theme-setup.css', ['wp-admin', 'dashicons', 'install'], $this->theme_version);
            wp_enqueue_style('wp-admin');
            wp_enqueue_media();
            wp_enqueue_script('media');
            ob_start();
            $this->init_wizard_steps();
            $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));
            $this->setup_wizard_header();
            $this->setup_wizard_steps();
            echo '<div class="cenos-setup-content">';
            if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
                $show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
            }
            $show_content = true;
            if ( $show_content ) {
                isset($this->steps[$this->step]) ? call_user_func($this->steps[$this->step]['view']) : false;
            }
            echo '</div>';
            $this->setup_wizard_footer();
            exit;
        }
        public function get_step_link($step)
        {
            return add_query_arg('step', $step, admin_url('admin.php?page=' . $this->page_slug));
        }
        public function get_next_step_link()
        {
            $keys = array_keys($this->steps);
            return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps)) + 1], remove_query_arg('translation_updated'));
        }
        public function setup_wizard_header()
        {
            ?>
            <!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
            <head>
                <meta name="viewport" content="width=device-width"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                <?php wp_print_scripts('cenos-setup'); ?>
                <?php do_action('admin_print_styles'); ?>
                <?php do_action('admin_print_scripts'); ?>
            </head>
            <body class="cenos-setup wp-core-ui">
            <h1 id="wc-logo">
                <?php
                $image_url = do_shortcode(get_theme_mod('logo', get_template_directory_uri() . '/assets/images/logo.svg'));
                if ($image_url) {
                    $image = '<img class="site-logo" src="%s" alt="%s" />';
                    printf(
                        $image,
                        $image_url,
                        get_bloginfo('name')
                    );
                } ?>
            </h1>
            <?php
        }
        public function setup_wizard_footer()
        {
            ?>
            <a class="wc-return-to-dashboard"
               href="<?php echo esc_url(admin_url()); ?>">Return to the WordPress Dashboard</a>
            </body>
            <?php
            @do_action('admin_footer');
            do_action('admin_print_footer_scripts');
            ?>
            </html>
            <?php
        }
        public function setup_wizard_steps()
        {
            $ouput_steps = $this->steps;
            array_shift($ouput_steps);
            ?>
            <ol class="cenos-setup-steps">
                <?php foreach ($ouput_steps as $step_key => $step) : ?>
                    <li class="<?php
                    $show_link = false;
                    if ($step_key === $this->step) {
                        echo 'active';
                    } elseif (array_search($this->step, array_keys($this->steps)) > array_search($step_key, array_keys($this->steps))) {
                        echo 'done';
                        $show_link = true;
                    }
                    ?>"><?php
                        if ($show_link) {
                            ?>
                            <a href="<?php echo esc_url($this->get_step_link($step_key)); ?>"><?php echo esc_html($step['name']); ?></a>
                            <?php
                        } else {
                            echo esc_html($step['name']);
                        }
                        ?></li>
                <?php endforeach; ?>
            </ol>
            <?php
        }
        public function theme_setup_introduction()
        {
            if (get_option('cenos_theme_setup_complete', false)) {
                ?>
                <h1><?php printf('Welcome to the setup wizard for %s.', wp_get_theme()); ?></h1>
                <p class="lead success"><?php esc_html_e('It looks like you already have setup Cenos.', 'cenos'); ?></p>
                <p class="cenos-setup-actions step">
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button-primary button button-next button-large">Run Setup Wizard Again</a>
                    <a href="<?php echo admin_url('admin.php?page=cenos-panel'); ?>"
                       class="button button-large"><?php esc_html_e('Exit to Cenos Panel', 'cenos'); ?></a>
                </p>
                <?php
            } else {
                ?>
                <h1><?php printf('Welcome to the setup wizard for %s.', wp_get_theme()); ?></h1>
                <p class="lead"><?php printf('Thank you for choosing the %s theme from ThemeForest. This quick setup wizard will help you configure your new website. This wizard will install the required WordPress plugins, default content, logo and tell you a little about Help &amp; Support options. <br/>It should only take 3-5 minutes.', wp_get_theme()); ?></p>
                <p><?php echo('No time right now? If you don\'t want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!'); ?></p>
                <p class="cenos-setup-actions step">
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button-primary button button-large button-next"><?php echo('Let\'s Go!'); ?></a>
                    <a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url('')); ?>"
                       class="button button-large"><?php echo('Not right now'); ?></a>
                </p>
                <?php
            }
        }
        private function _get_plugins()
        {
            $instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
            $plugins = array(
                'all' => array(), // Meaning: all plugins which still have open actions.
                'install' => array(),
                'update' => array(),
                'activate' => array(),
            );
            foreach ($instance->plugins as $slug => $plugin) {
                if (cenos_check_active_plugin($slug) && false === $instance->does_plugin_have_update($slug)) {
                    // No need to display plugins if they are installed, up-to-date and active.
                    continue;
                } else {
                    $plugins['all'][$slug] = $plugin;

                    if (!$instance->is_plugin_installed($slug)) {
                        $plugins['install'][$slug] = $plugin;
                    } else {
                        if (false !== $instance->does_plugin_have_update($slug)) {
                            $plugins['update'][$slug] = $plugin;
                        }
                        if ($instance->can_plugin_activate($slug)) {
                            $plugins['activate'][$slug] = $plugin;
                        }
                    }
                }
            }
            return $plugins;
        }
        public function theme_setup_default_plugins()
        {
            tgmpa_load_bulk_installer();
            // install plugins with TGM.
            if (!class_exists('TGM_Plugin_Activation') || !isset($GLOBALS['tgmpa'])) {
                die('Failed to find TGM');
            }
            $url = wp_nonce_url(add_query_arg(array('plugins' => 'go')), 'cenos');
            $plugins = $this->_get_plugins();
            // copied from TGM
            $method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
            $fields = array_keys($_POST); // Extra fields to pass to WP_Filesystem.
            if (false === ($creds = request_filesystem_credentials(esc_url_raw($url), $method, false, false, $fields))) {
                return true; // Stop the normal page form from displaying, credential request form will be shown.
            }
            // Now we have some credentials, setup WP_Filesystem.
            if (!WP_Filesystem($creds)) {
                // Our credentials were no good, ask the user for them again.
                request_filesystem_credentials(esc_url_raw($url), $method, true, false, $fields);
                return true;
            }
            /* If we arrive here, we have the filesystem */
            $order_plugin = ['elementor','woocommerce'];
            ?>
            <h1>Default Plugins</h1>
            <form method="post">
                <?php
                $plugins = $this->_get_plugins();
                if (count($plugins['all'])) {
                    ?>
                    <p class="lead">This will install the default plugins included with Cenos. You can add and remove plugins later on from within WordPress.</p>
                    <ul class="cenos-wizard-plugins">
                        <?php
                        if (!empty($order_plugin)) {
                            foreach ($order_plugin as $o_plugin) {
                                if (isset($plugins['all'][$o_plugin])) {
                                    $slug = $o_plugin;
                                    $plugin = $plugins['all'][$o_plugin];
                                    unset($plugins['all'][$o_plugin]);
                                } else {
                                    continue;
                                }
                                ?>
                                <li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?>
                                    <span>
    								<?php
                                    $keys = array();
                                    if (isset($plugins['install'][$slug])) {
                                        $keys[] = 'Installation';
                                    }
                                    if (isset($plugins['update'][$slug])) {
                                        $keys[] = 'Update';
                                    }
                                    if (isset($plugins['activate'][$slug])) {
                                        $keys[] = 'Activation';
                                    }
                                    echo implode(' and ', $keys) . ' required';
                                    ?>
    							</span>
                                    <div class="spinner"></div>
                                </li>
                            <?php }
                        }?>
                        <?php foreach ($plugins['all'] as $slug => $plugin) { ?>
                            <li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?>
                                <span>
    								<?php
                                    $keys = array();
                                    if (isset($plugins['install'][$slug])) {
                                        $keys[] = 'Installation';
                                    }
                                    if (isset($plugins['update'][$slug])) {
                                        $keys[] = 'Update';
                                    }
                                    if (isset($plugins['activate'][$slug])) {
                                        $keys[] = 'Activation';
                                    }
                                    echo implode(' and ', $keys) . ' required';
                                    ?>
    							</span>
                                <div class="spinner"></div>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                } else {
                    echo '<p class="lead">Good news! All plugins are already installed and up to date. Please continue.</p>';
                } ?>
                <p class="cenos-setup-actions step">
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button-primary button button-large button-next"
                       data-callback="install_plugins">Continue</a>
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button button-large button-next">Skip this step</a>
                    <?php wp_nonce_field('cenos'); ?>
                </p>
            </form>
            <?php
        }
        public function ajax_plugins()
        {
            if (!check_ajax_referer('cenos_setup_nonce', 'wpnonce') || empty($_POST['slug'])) {
                wp_send_json_error(array('error' => 1, 'message' => esc_html__('No Slug Found', 'cenos')));
            }
            $json = array();
            // send back some json we use to hit up TGM
            $plugins = $this->_get_plugins();
            // what are we doing with this plugin?
            foreach ($plugins['activate'] as $slug => $plugin) {
                if ($_POST['slug'] == $slug) {
                    $json = array(
                        'url' => admin_url($this->tgmpa_url),
                        'plugin' => array($slug),
                        'tgmpa-page' => $this->tgmpa_menu_slug,
                        'plugin_status' => 'all',
                        '_wpnonce' => wp_create_nonce('bulk-plugins'),
                        'action' => 'tgmpa-bulk-activate',
                        'action2' => -1,
                        'message' => 'Activating Plugin',
                    );
                    break;
                }
            }
            foreach ($plugins['update'] as $slug => $plugin) {
                if ($_POST['slug'] == $slug) {
                    $json = array(
                        'url' => admin_url($this->tgmpa_url),
                        'plugin' => array($slug),
                        'tgmpa-page' => $this->tgmpa_menu_slug,
                        'plugin_status' => 'all',
                        '_wpnonce' => wp_create_nonce('bulk-plugins'),
                        'action' => 'tgmpa-bulk-update',
                        'action2' => -1,
                        'message' => 'Updating Plugin',
                    );
                    break;
                }
            }
            foreach ($plugins['install'] as $slug => $plugin) {
                if ($_POST['slug'] == $slug) {
                    $json = array(
                        'url' => admin_url($this->tgmpa_url),
                        'plugin' => array($slug),
                        'tgmpa-page' => $this->tgmpa_menu_slug,
                        'plugin_status' => 'all',
                        '_wpnonce' => wp_create_nonce('bulk-plugins'),
                        'action' => 'tgmpa-bulk-install',
                        'action2' => -1,
                        'message' => 'Installing Plugin',
                    );
                    break;
                }
            }

            if ($json) {
                $json['hash'] = md5(serialize($json)); // used for checking if duplicates happen, move to next plugin
                wp_send_json($json);
            } else {
                wp_send_json(array('done' => 1, 'message' => esc_html__('Success', 'cenos')));
            }
            exit;

        }
        private function _content_default_get() {
            $content = array();
            $content['media_package'] = array(
                'title'            => 'Media Package',
                'description'      => 'This will download default Attachment Images from our server.',
                'pending'          => 'Pending.',
                'installing'       => 'Downloading Attachments.',
                'success'          => 'Success.',
                'install_callback' => array( $this, '_content_download_media'),
                'checked'          => 1,
                // dont check if already have content installed.
            );
            // find out what content is in our default json file.
            $attachment_content['attachment'] = $this->_get_json( 'attachment.json' );

            $main_content = $this->_get_json( 'default.json' );
            if (is_null($main_content)){
                $main_content = [];
            }
            $available_content = array_merge($attachment_content,$main_content);
            foreach ( $available_content as $post_type => $post_data ) {
                if ( count( $post_data ) ) {
                    $first           = current( $post_data );
                    $post_type_title = ! empty( $first['type_title'] ) ? $first['type_title'] : ucwords( $post_type ) . 's';
                    if ( $post_type_title == 'Navigation Menu Items' ) {
                        $post_type_title = 'Navigation';
                    }
                    $content[ $post_type ] = array(
                        'title'            => $post_type_title,
                        'description'      => sprintf( 'This will create default %s as seen in the demo.', $post_type_title ),
                        'pending'          => 'Pending.',
                        'installing'       => 'Installing.',
                        'success'          => 'Success.',
                        'install_callback' => array( $this, '_content_install_type' ),
                        'checked'          => 1
                        // dont check if already have content installed.
                    );
                }
            }
            if (class_exists('Fmfw_Custom_fonts')) {
                $content['fmfw_font'] = array(
                    'title'            => 'Custom Fonts',
                    'description'      => 'Import Custom Fonts.',
                    'pending'          => 'Pending.',
                    'installing'       => 'Installing Fonts.',
                    'success'          => 'Success.',
                    'install_callback' => array( $this, '_content_install_fmfw_font'),
                    'checked'          => 1,
                    // dont check if already have content installed.
                );
            }
            if (class_exists( 'RevSliderSliderImport' )){
                $content['rev_slide'] = array(
                    'title'            => 'Slider Revolution',
                    'description'      => 'Import demo slider for your homepage.',
                    'pending'          => 'Pending.',
                    'installing'       => 'Installing Slider.',
                    'success'          => 'Success.',
                    'install_callback' => array( $this, '_content_install_rev_slide'),
                    'checked'          => 1,
                    // dont check if already have content installed.
                );
            }
            $content['widgets'] = array(
                'title'            => 'Widgets',
                'description'      => 'Insert default sidebar widgets as seen in the demo.',
                'pending'          => 'Pending.',
                'installing'       => 'Installing Default Widgets.',
                'success'          => 'Success.',
                'install_callback' => array( $this, '_content_install_widgets' ),
                'checked'          => 1,
                // dont check if already have content installed.
            );
            $content['settings'] = array(
                'title'            => 'Settings',
                'description'      => 'Configure default settings.',
                'pending'          => 'Pending.',
                'installing'       => 'Installing Default Settings.',
                'success'          => 'Success.',
                'install_callback' => array( $this, '_content_install_settings' ),
                'checked'          => 1,
                // dont check if already have content installed.
            );
            return $content;
        }
        public function theme_setup_default_content()
        {
            $hello_post = get_page_by_title('Hello world!',OBJECT, 'post');
            if (!empty($hello_post) && $hello_post->ID){
                wp_delete_post($hello_post->ID);
            }
            ?>
            <h1>'Install Demo Content'</h1>
            <form method="post">
                <p class="lead">It's time to insert some default content for your new Cenos website. Choose what you would like inserted below and click Continue. It is recommended to leave everything selected. Once inserted, this content can be managed from the WordPress admin dashboard.</p>
                <table class="cenos-setup-pages" cellspacing="0">
                    <thead>
                    <tr>
                        <td class="check"></td>
                        <th class="item"><?php echo('Item'); ?></th>
                        <th class="description"><?php echo('Description'); ?></th>
                        <th class="status"><?php echo('Status'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->_content_default_get() as $slug => $default) { ?>
                        <tr class="cenos_default_content" data-content="<?php echo esc_attr($slug); ?>">
                            <td>
                                <input type="checkbox" name="default_content[<?php echo esc_attr($slug); ?>]"
                                       class="cenos_default_content"
                                       id="default_content_<?php echo esc_attr($slug); ?>"
                                       value="1" <?php echo esc_attr((!isset($default['checked']) || $default['checked']) ? ' checked' : ''); ?>>
                            </td>
                            <td>
                                <label for="default_content_<?php echo esc_attr($slug); ?>"><?php echo esc_html($default['title']); ?></label>
                            </td>
                            <td class="description"><?php echo esc_html($default['description']); ?></td>
                            <td class="status"><span><?php echo esc_html($default['pending']); ?></span>
                                <div class="spinner"></div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <p class="cenos-setup-actions step">
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button-primary button button-large button-next"
                       data-callback="install_content">Continue</a>
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                       class="button button-large button-next">Skip this step</a>
                    <?php wp_nonce_field('cenos-setup'); ?>
                </p>
            </form>
            <?php
        }
        public function ajax_content() {
            set_time_limit( 180 );
            $content = $this->_content_default_get();
            if ( ! check_ajax_referer( 'cenos_setup_nonce', 'wpnonce' ) || empty( $_POST['content'] ) && isset( $content[ $_POST['content'] ] ) ) {
                wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No content Found' ,'cenos') ) );
            }
            $json         = false;
            $this_content = $content[ $_POST['content'] ];
            if ( isset( $_POST['proceed'] ) ) {
                // install the content!
                $this->log( ' -!! STARTING SECTION for ' . $_POST['content'] );
                // init delayed posts from transient.
                $this->delay_posts = get_transient( 'delayed_posts' );
                if ( ! is_array( $this->delay_posts ) ) {
                    $this->delay_posts = array();
                }
                if ( ! empty( $this_content['install_callback'] ) ) {
                    if ( $result = call_user_func( $this_content['install_callback'] ) ) {
                        $this->log( ' -- FINISH. Writing ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts to transient ' );
                        set_transient( 'delayed_posts', $this->delay_posts, 60 * 60 * 24 );
                        if ( is_array( $result ) && isset( $result['retry'] ) ) {
                            // we split the stuff up again.
                            $json = array(
                                'url'         => admin_url( 'admin-ajax.php' ),
                                'action'      => 'cenos_setup_content',
                                'proceed'     => 'true',
                                'retry'       => time(),
                                'retry_count' => $result['retry_count'],
                                'content'     => $_POST['content'],
                                '_wpnonce'    => wp_create_nonce( 'cenos_setup_nonce' ),
                                'message'     => $this_content['installing'],
                                'logs'        => $this->logs,
                                'errors'      => $this->errors,
                            );
                        } else {
                            $json = array(
                                'done'    => 1,
                                'message' => $this_content['success'],
                                'debug'   => $result,
                                'logs'    => $this->logs,
                                'errors'  => $this->errors,
                            );
                        }
                    }
                }
            } else {
                $json = array(
                    'url'      => admin_url( 'admin-ajax.php' ),
                    'action'   => 'cenos_setup_content',
                    'proceed'  => 'true',
                    'content'  => $_POST['content'],
                    '_wpnonce' => wp_create_nonce( 'cenos_setup_nonce' ),
                    'message'  => $this_content['installing'],
                    'logs'     => $this->logs,
                    'errors'   => $this->errors,
                );
            }
            if ( !empty($json) ) {
                $json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
                wp_send_json( $json );
            } else {
                wp_send_json( array(
                    'error'   => 1,
                    'message' => esc_html__( 'Error' ,'cenos'),
                    'logs'    => $this->logs,
                    'errors'  => $this->errors,
                ) );
            }

            exit;

        }
        private function _imported_term_id( $original_term_id, $new_term_id = false ) {
            $terms = get_transient( 'importtermids' );
            if ( ! is_array( $terms ) ) {
                $terms = array();
            }
            if ( $new_term_id ) {
                if ( ! isset( $terms[ $original_term_id ] ) ) {
                    $this->log( 'Insert old TERM ID ' . $original_term_id . ' as new TERM ID: ' . $new_term_id );
                } else if ( $terms[ $original_term_id ] != $new_term_id ) {
                    $this->error( 'Replacement OLD TERM ID ' . $original_term_id . ' overwritten by new TERM ID: ' . $new_term_id );
                }
                $terms[ $original_term_id ] = $new_term_id;
                set_transient( 'importtermids', $terms, 60 * 60 * 24 );
            } else if ( $original_term_id && isset( $terms[ $original_term_id ] ) ) {
                return $terms[ $original_term_id ];
            }

            return false;
        }
        public function vc_post( $post_id = false ) {
            $vc_post_ids = get_transient( 'import_vc_posts' );
            if ( ! is_array( $vc_post_ids ) ) {
                $vc_post_ids = array();
            }
            if ( $post_id ) {
                $vc_post_ids[ $post_id ] = $post_id;
                set_transient( 'import_vc_posts', $vc_post_ids, 60 * 60 * 24 );
            } else {
                $this->log( 'Processing vc pages 2: ' );
                return;
                if ( class_exists( 'Vc_Manager' ) && class_exists( 'Vc_Post_Admin' ) ) {
                    $this->log( $vc_post_ids );
                    $vc_manager = Vc_Manager::getInstance();
                    $vc_base    = $vc_manager->vc();
                    $post_admin = new Vc_Post_Admin();
                    foreach ( $vc_post_ids as $vc_post_id ) {
                        $this->log( 'Save ' . $vc_post_id );
                        $vc_base->buildShortcodesCustomCss( $vc_post_id );
                        $post_admin->save( $vc_post_id );
                        $post_admin->setSettings( $vc_post_id );
                        //twice? bug?
                        $vc_base->buildShortcodesCustomCss( $vc_post_id );
                        $post_admin->save( $vc_post_id );
                        $post_admin->setSettings( $vc_post_id );
                    }
                }
            }
        }
        public function elementor_post( $post_id = false ) {

            // regenrate the CSS for this Elementor post
            if( class_exists( 'Elementor\Post_CSS_File' ) ) {
                $post_css = new Elementor\Post_CSS_File($post_id);
                $post_css->update();
            }

        }
        private function _imported_post_id( $original_id = false, $new_id = false ) {
            if ( is_array( $original_id ) || is_object( $original_id ) ) {
                return false;
            }
            $post_ids = get_transient( 'importpostids' );
            if ( ! is_array( $post_ids ) ) {
                $post_ids = array();
            }
            if ( $new_id ) {
                if ( ! isset( $post_ids[ $original_id ] ) ) {
                    $this->log( 'Insert old ID ' . $original_id . ' as new ID: ' . $new_id );
                } else if ( $post_ids[ $original_id ] != $new_id ) {
                    $this->error( 'Replacement OLD ID ' . $original_id . ' overwritten by new ID: ' . $new_id );
                }
                $post_ids[ $original_id ] = $new_id;
                set_transient( 'importpostids', $post_ids, 60 * 60 * 24 );
            } else if ( $original_id && isset( $post_ids[ $original_id ] ) ) {
                return $post_ids[ $original_id ];
            } else if ( $original_id === false ) {
                return $post_ids;
            }
            return false;
        }
        private function _post_orphans( $original_id = false, $missing_parent_id = false ) {
            $post_ids = get_transient( 'postorphans' );
            if ( ! is_array( $post_ids ) ) {
                $post_ids = array();
            }
            if ( $missing_parent_id ) {
                $post_ids[ $original_id ] = $missing_parent_id;
                set_transient( 'postorphans', $post_ids, 60 * 60 * 24 );
            } else if ( $original_id && isset( $post_ids[ $original_id ] ) ) {
                return $post_ids[ $original_id ];
            } else if ( $original_id === false ) {
                return $post_ids;
            }

            return false;
        }
        private function _cleanup_imported_ids() {
            // loop over all attachments and assign the correct post ids to those attachments.
        }

        private function _delay_post_process( $post_type, $post_data ) {
            if ( ! isset( $this->delay_posts[ $post_type ] ) ) {
                $this->delay_posts[ $post_type ] = array();
            }
            $this->delay_posts[ $post_type ][ $post_data['post_id'] ] = $post_data;

        }

        private function _delay_post_meta_process($post_id,$value = true) {
            if ( ! isset( $this->delay_meta[$post_id] ) ) {
                $this->delay_meta[$post_id] = $value;
            }
        }

        private function _process_post_data( $post_type, $post_data, $delayed = 0, $debug = false ) {
            $f = 'register';
            $this->log( " Processing $post_type " . $post_data['post_id'].' '.$post_data['post_name'] );
            $original_post_data = $post_data;

            if ( $debug ) {
                echo "HERE\n";
            }
            if ( ! post_type_exists( $post_type ) ) {
                return false;
            }

            if ( ! $debug && $this->_imported_post_id( $post_data['post_id'] ) ) {
                return true; // already done :)
            }

            if ( empty( $post_data['post_title'] ) && empty( $post_data['post_name'] ) ) {
                // this is menu items
                $post_data['post_name'] = $post_data['post_id'];
            }

            $post_data['post_type'] = $post_type;

            $post_parent = (int) $post_data['post_parent'];
            if ( $post_parent ) {
                // if we already know the parent, map it to the new local ID
                if ( $this->_imported_post_id( $post_parent ) ) {
                    $post_data['post_parent'] = $this->_imported_post_id( $post_parent );
                    // otherwise record the parent for later
                } else {
                    $this->_post_orphans( intval( $post_data['post_id'] ), $post_parent );
                    $post_data['post_parent'] = 0;
                }
            }
            // check if already exists
            if ( ! $debug ) {
                if ( empty( $post_data['post_title'] ) && ! empty( $post_data['post_name'] ) ) {
                    global $wpdb;
                    $sql     = "
					SELECT ID, post_name, post_parent, post_type
					FROM $wpdb->posts
					WHERE post_name = %s
					AND post_type = %s
				";
                    $pages   = $wpdb->get_results( $wpdb->prepare( $sql, array(
                        $post_data['post_name'],
                        $post_type,
                    ) ), OBJECT_K );
                    $foundid = 0;
                    foreach ( (array) $pages as $page ) {
                        if ( $page->post_name == $post_data['post_name'] && empty( $page->post_title ) ) {
                            $foundid = $page->ID;
                        }
                    }
                    if ( $foundid ) {
                        $this->_imported_post_id( $post_data['post_id'], $foundid );

                        return true;
                    }
                }
                // dont use post_exists because it will dupe up on media with same name but different slug
                if ( ! empty( $post_data['post_title'] ) && ! empty( $post_data['post_name'] ) ) {
                    global $wpdb;
                    $sql     = "
					SELECT ID, post_name, post_parent, post_type
					FROM $wpdb->posts
					WHERE post_name = %s
					AND post_title = %s
					AND post_type = %s
					";
                    $pages   = $wpdb->get_results( $wpdb->prepare( $sql, array(
                        $post_data['post_name'],
                        $post_data['post_title'],
                        $post_type,
                    ) ), OBJECT_K );
                    $foundid = 0;
                    foreach ( (array) $pages as $page ) {
                        if ( $page->post_name == $post_data['post_name'] ) {
                            $foundid = $page->ID;
                        }
                    }
                    if ( $foundid ) {
                        $this->_imported_post_id( $post_data['post_id'], $foundid );

                        return true;
                    }
                }
            }

            // backwards compat with old import format.
            if ( isset( $post_data['meta'] ) ) {
                foreach ( $post_data['meta'] as $key => $meta ) {
                    if(is_array($meta) && count($meta) == 1){
                        $single_meta = current($meta);
                        if(!is_array($single_meta)){
                            $post_data['meta'][$key] = $single_meta;
                        }
                    }
                }
            }

            switch ( $post_type ) {
                case 'attachment':
                    // import media via url
                    if ( ! empty( $post_data['guid'] ) ) {
                        // check if this has already been imported.
                        $old_guid = $post_data['guid'];
                        if ( $this->_imported_post_id( $old_guid ) ) {
                            return true; // alrady done;
                        }
                        // ignore post parent, we haven't imported those yet.
                        $remote_url = $post_data['guid'];

                        $post_data['upload_date'] = date( 'Y/m', strtotime( $post_data['post_date_gmt'] ) );
                        if ( isset( $post_data['meta'] ) ) {
                            foreach ( $post_data['meta'] as $key => $meta ) {
                                if ( $key == '_wp_attached_file' ) {
                                    foreach ( (array) $meta as $meta_val ) {
                                        if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta_val, $matches ) ) {
                                            $post_data['upload_date'] = $matches[0];
                                        }
                                    }
                                }
                            }
                        }

                        $upload = $this->_fetch_remote_file( $remote_url, $post_data );

                        if ( ! is_array( $upload ) || is_wp_error( $upload ) ) {
                            // todo: error
                            $this->error($remote_url .' upload failed');
                            return false;
                        }

                        if ( $info = wp_check_filetype( $upload['file'] ) ) {
                            $post['post_mime_type'] = $info['type'];
                        } else {
                            $this->error($remote_url .' mime type failed');
                            return false;
                        }

                        $post_data['guid'] = $upload['url'];

                        // as per wp-admin/includes/upload.php
                        $post_id = wp_insert_attachment( $post_data, $upload['file'] );
                        if($post_id) {
                            if ( ! empty( $post_data['meta'] ) ) {
                                foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
                                    if($meta_key != '_wp_attached_file' && !empty($meta_val)) {
                                        update_post_meta( $post_id, $meta_key, $meta_val );
                                    }
                                }
                            }
                            wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );
                            // remap resized image URLs, works by stripping the extension and remapping the URL stub.
                            if ( preg_match( '!^image/!', $info['type'] ) ) {
                                $parts = pathinfo( $remote_url );
                                $name  = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

                                $parts_new = pathinfo( $upload['url'] );
                                $name_new  = basename( $parts_new['basename'], ".{$parts_new['extension']}" );
                                $this->_imported_post_id( $parts['dirname'] . '/' . $name, $parts_new['dirname'] . '/' . $name_new );
                            }
                            $this->_imported_post_id( $post_data['post_id'], $post_id );
                        }
                    }
                    break;
                default:
                    // work out if we have to delay this post insertion
                    $replace_meta_vals = array(
                        'logo|page_heading_block|background-image' => array(
                            'post' => true,
                        )
                    );
                    if ( ! empty( $post_data['meta'] ) && is_array( $post_data['meta'] ) ) {
                        // replace any elementor post data:
                        // fix for double json encoded stuff:
                        foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
                            if ( is_string( $meta_val ) && strlen( $meta_val ) && $meta_val[0] == '[' ) {
                                $test_json = @json_decode( $meta_val, true );
                                if ( is_array( $test_json ) ) {
                                    $post_data['meta'][ $meta_key ] = $test_json;
                                }
                            }
                        }
                        $post_data['meta'] = $this->_meta_data_id_update('meta',$post_data['meta'],$post_data['post_id']);

                        if (isset($this->delay_meta[$post_data['post_id']])){
                            $this->delay_meta[$post_data['post_id']] = $post_data['meta'];
                        }
                        // replace menu data:
                        // work out what we're replacing. a tax, page, term etc..
                        if(!empty($post_data['meta']['_menu_item_menu_item_parent'])) {
                            $new_parent_id = $this->_imported_post_id( $post_data['meta']['_menu_item_menu_item_parent'] );
                            if(!$new_parent_id) {
                                if ( $delayed ) {
                                    // already delayed, unable to find this meta value, skip inserting it
                                    $this->error( 'Unable to find replacement. Continue anyway.... content will most likely break..' );
                                } else {
                                    $this->error( 'Unable to find replacement. Delaying.... ' );
                                    $this->_delay_post_process( $post_type, $original_post_data );
                                    return false;
                                }
                            }
                            $post_data['meta']['_menu_item_menu_item_parent'] = $new_parent_id;
                        }
                        if (isset($post_data['meta']['_fm_menu_item_mega']) && !empty($post_data['meta']['_fm_menu_item_mega'])){
                            if (isset($post_data['meta']['_fm_menu_item_mega']['post_content']) && !empty($post_data['meta']['_fm_menu_item_mega']['post_content'])){
                                $new_post_content = $this->_imported_post_id( $post_data['meta']['_fm_menu_item_mega']['post_content']);
                                if(!$new_post_content) {
                                    if ( $delayed ) {
                                        // already delayed, unable to find this meta value, skip inserting it
                                        $this->error( 'Unable to find replacement. Continue anyway.... content will most likely break..' );
                                    } else {
                                        $this->error( 'Unable to find replacement. Delaying.... ' );
                                        $this->_delay_post_process( $post_type, $original_post_data );
                                        return false;
                                    }
                                }
                                $post_data['meta']['_fm_menu_item_mega']['post_content'] = $new_post_content;
                            }
                        }

                        if(!empty($post_data['meta']['_menu_item_url'])) {
                            $item_url_data = parse_url($post_data['meta']['_menu_item_url']);
                            if (isset($item_url_data['host']) && $item_url_data['host'] == $this->demo_host){
                                $post_data['meta']['_menu_item_url'] = str_replace($this->demo_host,$this->current_host,$post_data['meta']['_menu_item_url']);
                            }
                        }
                        if (isset($post_data['meta'][ '_menu_item_type' ])) {
                            switch($post_data['meta'][ '_menu_item_type' ]){
                                case 'post_type':
                                    if(!empty($post_data['meta']['_menu_item_object_id'])) {
                                        $new_parent_id = $this->_imported_post_id( $post_data['meta']['_menu_item_object_id'] );
                                        if(!$new_parent_id) {
                                            if ( $delayed ) {
                                                // already delayed, unable to find this meta value, skip inserting it
                                                $this->error( 'Unable to find replacement. Continue anyway.... content will most likely break..' );
                                            } else {
                                                $this->error( 'Unable to find replacement. Delaying.... ' );
                                                $this->_delay_post_process( $post_type, $original_post_data );
                                                return false;
                                            }
                                        }
                                        $post_data['meta']['_menu_item_object_id'] = $new_parent_id;
                                    }
                                    break;
                                case 'taxonomy':
                                    if(!empty($post_data['meta']['_menu_item_object_id'])) {
                                        $new_parent_id = $this->_imported_term_id( $post_data['meta']['_menu_item_object_id'] );
                                        if(!$new_parent_id) {
                                            if ( $delayed ) {
                                                // already delayed, unable to find this meta value, skip inserting it
                                                $this->error( 'Unable to find replacement. Continue anyway.... content will most likely break..' );
                                            } else {
                                                $this->error( 'Unable to find replacement. Delaying.... ' );
                                                $this->_delay_post_process( $post_type, $original_post_data );
                                                return false;
                                            }
                                        }
                                        $post_data['meta']['_menu_item_object_id'] = $new_parent_id;
                                    }
                                    break;
                            }
                        }
                        // please ignore this horrible loop below:
                        // it was an attempt to automate different visual composer meta key replacements
                        // but I'm not using visual composer any more, so ignoring it.
                        foreach ( $replace_meta_vals as $meta_key_to_replace => $meta_values_to_replace ) {

                            $meta_keys_to_replace   = explode( '|', $meta_key_to_replace );
                            $success                = false;
                            $trying_to_find_replace = false;
                            foreach ( $meta_keys_to_replace as $meta_key ) {

                                if ( ! empty( $post_data['meta'][ $meta_key ] ) ) {

                                    $meta_val = $post_data['meta'][ $meta_key ];

                                    // export gets meta straight from the DB so could have a serialized string
                                    if ( $debug ) {
                                        echo "Meta key: $meta_key \n";
                                        print_r( $meta_val );
                                    }

                                    // if we're replacing a single post/tax value.
                                    if ( isset( $meta_values_to_replace['post'] ) && $meta_values_to_replace['post'] && (int) $meta_val > 0 ) {
                                        $trying_to_find_replace = true;
                                        $new_meta_val           = $this->_imported_post_id( $meta_val );
                                        if ( $new_meta_val ) {
                                            $post_data['meta'][ $meta_key ] = $new_meta_val;
                                            $success                        = true;
                                        } else {
                                            $success = false;
                                            break;
                                        }
                                    }
                                    if ( isset( $meta_values_to_replace['taxonomy'] ) && $meta_values_to_replace['taxonomy'] && (int) $meta_val > 0 ) {
                                        $trying_to_find_replace = true;
                                        $new_meta_val           = $this->_imported_term_id( $meta_val );
                                        if ( $new_meta_val ) {
                                            $post_data['meta'][ $meta_key ] = $new_meta_val;
                                            $success                        = true;
                                        } else {
                                            $success = false;
                                            break;
                                        }
                                    }
                                    if ( is_array( $meta_val ) && isset( $meta_values_to_replace['posts'] ) ) {

                                        foreach ( $meta_values_to_replace['posts'] as $post_array_key ) {

                                            $this->log( 'Trying to find/replace "' . $post_array_key . '"" in the ' . $meta_key . ' sub array:' );

                                            $this_success = false;
                                            array_walk_recursive( $meta_val, function ( &$item, $key ) use ( &$trying_to_find_replace, $post_array_key, &$success, &$this_success, $post_type, $original_post_data, $meta_key, $delayed ) {
                                                if ( $key == $post_array_key && (int) $item > 0 ) {
                                                    $trying_to_find_replace = true;
                                                    $new_insert_id          = $this->_imported_post_id( $item );
                                                    if ( $new_insert_id ) {
                                                        $success      = true;
                                                        $this_success = true;
                                                        $this->log( 'Found' . $meta_key . ' -> ' . $post_array_key . ' replacement POST ID insert for ' . $item . ' ( as ' . $new_insert_id . ' ) ' );
                                                        $item = $new_insert_id;
                                                    } else {
                                                        $this->error( 'Unable to find ' . $meta_key . ' -> ' . $post_array_key . ' POST ID insert for ' . $item . ' ' );
                                                    }
                                                }
                                            } );
                                            if ( $this_success ) {
                                                $post_data['meta'][ $meta_key ] = $meta_val;
                                            }
                                        }
                                        foreach ( $meta_values_to_replace['taxonomies'] as $post_array_key ) {

                                            $this->log( 'Trying to find/replace "' . $post_array_key . '"" TAXONOMY in the ' . $meta_key . ' sub array:' );

                                            $this_success = false;
                                            array_walk_recursive( $meta_val, function ( &$item, $key ) use ( &$trying_to_find_replace, $post_array_key, &$success, &$this_success, $post_type, $original_post_data, $meta_key, $delayed ) {
                                                if ( $key == $post_array_key && (int) $item > 0 ) {
                                                    $trying_to_find_replace = true;
                                                    $new_insert_id          = $this->_imported_term_id( $item );
                                                    if ( $new_insert_id ) {
                                                        $success      = true;
                                                        $this_success = true;
                                                        $this->log( 'Found' . $meta_key . ' -> ' . $post_array_key . ' replacement TAX ID insert for ' . $item . ' ( as ' . $new_insert_id . ' ) ' );
                                                        $item = $new_insert_id;
                                                    } else {
                                                        $this->error( 'Unable to find ' . $meta_key . ' -> ' . $post_array_key . ' TAX ID insert for ' . $item . ' ' );
                                                    }
                                                }
                                            } );

                                            if ( $this_success ) {
                                                $post_data['meta'][ $meta_key ] = $meta_val;
                                            }
                                        }
                                    }

                                    if ( $success ) {
                                        if ( $debug ) {
                                            echo "Meta key AFTER REPLACE: $meta_key \n";
                                            print_r( $post_data['meta'] );
                                        }
                                    }
                                }
                            }
                            if ( $trying_to_find_replace ) {
                                $this->log( 'Trying to find/replace postmeta "' . $meta_key_to_replace . '" ' );
                                if ( ! $success ) {
                                    // failed to find a replacement.
                                    if ( $delayed ) {
                                        // already delayed, unable to find this meta value, skip inserting it
                                        $this->error( 'Unable to find replacement. Continue anyway.... content will most likely break..' );
                                    } else {
                                        $this->error( 'Unable to find replacement. Delaying.... ' );
                                        $this->_delay_post_process( $post_type, $original_post_data );
                                        return false;
                                    }
                                } else {
                                    $this->log( 'SUCCESSSS ' );
                                }
                            }
                        }
                    }

                    $post_data['post_content'] = $this->_parse_gallery_shortcode_content($post_data['post_content']);
                    if ('elementor_library' == $post_type && $post_data['post_name'] == 'default-kit'){
                        $default_kit_posts = get_posts(array('name' => 'default-kit', 'post_type' => 'elementor_library','post_status'=> 'publish'));
                        if (isset($default_kit_posts[0]) && !empty($default_kit_posts[0])){
                            $post_data['ID'] = $default_kit_posts[0]->ID;
                        }
                        if (defined( 'ELEMENTOR_VERSION' )){
                            $this->log('Processing Elementor Default Kit');
                            $post_id = \Elementor\Plugin::instance()->kits_manager->create($post_data);
                            update_option( 'elementor_active_kit', $post_id );
                        } else {
                            $post_id = wp_insert_post( $post_data, true );
                        }
                    } else {
                        $post_id = wp_insert_post( $post_data, true );
                    }
                    // we have to fix up all the visual composer inserted image ids
                    $replace_post_id_keys = array(
                        'parallax_image',
                        'familab_row_image_top',
                        'familab_row_image_bottom',
                        'image',
                        'item', // vc grid
                        'post_id',
                    );
                    foreach ( $replace_post_id_keys as $replace_key ) {
                        if ( preg_match_all( '# ' . $replace_key . '="(\d+)"#', $post_data['post_content'], $matches ) ) {
                            foreach ( $matches[0] as $match_id => $string ) {
                                $new_id = $this->_imported_post_id( $matches[1][ $match_id ] );
                                if ( $new_id ) {
                                    $post_data['post_content'] = str_replace( $string, ' ' . $replace_key . '="' . $new_id . '"', $post_data['post_content'] );
                                } else {
                                    $this->error( 'Unable to find POST replacement for ' . $replace_key . '="' . $matches[1][ $match_id ] . '" in content.' );
                                    if ( $delayed ) {
                                        // already delayed, unable to find this meta value, insert it anyway.

                                    } else {

                                        $this->error( 'Adding ' . $post_data['post_id'] . ' to delay listing.' );
                                        $this->_delay_post_process( $post_type, $original_post_data );

                                        return false;
                                    }
                                }
                            }
                        }
                    }
                    $replace_tax_id_keys = array(
                        'taxonomies',
                    );
                    foreach ( $replace_tax_id_keys as $replace_key ) {
                        if ( preg_match_all( '# ' . $replace_key . '="(\d+)"#', $post_data['post_content'], $matches ) ) {
                            foreach ( $matches[0] as $match_id => $string ) {
                                $new_id = $this->_imported_term_id( $matches[1][ $match_id ] );
                                if ( $new_id ) {
                                    $post_data['post_content'] = str_replace( $string, ' ' . $replace_key . '="' . $new_id . '"', $post_data['post_content'] );
                                } else {
                                    $this->error( 'Unable to find TAXONOMY replacement for ' . $replace_key . '="' . $matches[1][ $match_id ] . '" in content.' );
                                    if ( $delayed ) {
                                        // already delayed, unable to find this meta value, insert it anyway.
                                    } else {
                                        $this->_delay_post_process( $post_type, $original_post_data );
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                    if ( ! is_wp_error( $post_id ) ) {
                        $this->_imported_post_id( $post_data['post_id'], $post_id );
                        // add/update post meta
                        if ( ! empty( $post_data['meta'] ) ) {
                            foreach ( $post_data['meta'] as $meta_key => $meta_val ) {

                                // if the post has a featured image, take note of this in case of remap
                                if ( '_thumbnail_id' == $meta_key ) {
                                    /// find this inserted id and use that instead.
                                    $inserted_id = $this->_imported_post_id( intval( $meta_val ) );
                                    if ( $inserted_id ) {
                                        $meta_val = $inserted_id;
                                    }
                                }
                                update_post_meta( $post_id, $meta_key, $meta_val );
                            }
                        }
                        if (isset($post_data['terms']) && ! empty($post_data['terms'] ) ) {
                            $terms_to_set = array();
                            foreach ( $post_data['terms'] as $term_slug => $terms ) {
                                foreach ( $terms as $term ) {
                                    if ($post_type == 'product' && cenos_is_woocommerce_activated()){
                                        if ( strstr( $term['taxonomy'], 'pa_' ) ) {
                                            if ( ! taxonomy_exists( $term['taxonomy'] ) ) {
                                                $act = $f.'_taxonomy';
                                                $attribute_name = wc_attribute_taxonomy_slug( $term['taxonomy'] );
                                                // Create the taxonomy.
                                                if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies(), true ) ) {
                                                    wc_create_attribute(
                                                        array(
                                                            'name'         => $attribute_name,
                                                            'slug'         => $attribute_name,
                                                            'type'         => 'select',
                                                            'order_by'     => 'menu_order',
                                                            'has_archives' => false,
                                                        )
                                                    );
                                                }
                                                $act(
                                                    $term['taxonomy'],
                                                    apply_filters( 'woocommerce_taxonomy_objects_' . $term['taxonomy'], array( 'product' ) ),
                                                    apply_filters(
                                                        'woocommerce_taxonomy_args_' . $term['taxonomy'],
                                                        array(
                                                            'hierarchical' => true,
                                                            'show_ui'      => false,
                                                            'query_var'    => true,
                                                            'rewrite'      => false,
                                                        )
                                                    )
                                                );
                                            }
                                        }
                                    }
                                    $taxonomy = $term['taxonomy'];
                                    if ( taxonomy_exists( $taxonomy ) ) {
                                        $term_exists = term_exists( $term['slug'], $taxonomy );
                                        $term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
                                        if ( ! $term_id ) {
                                            if ( ! empty( $term['parent'] ) ) {
                                                // see if we have imported this yet?
                                                $term['parent'] = $this->_imported_term_id( $term['parent'] );
                                            }
                                            $t = wp_insert_term( $term['name'], $taxonomy, $term );
                                            if ( ! is_wp_error( $t ) ) {
                                                $term_id = $t['term_id'];
                                            } else {
                                                // todo - error
                                                continue;
                                            }
                                        }
                                        $this->_imported_term_id( $term['term_id'], $term_id );
                                        // add the term meta.
                                        if($term_id && !empty($term['meta']) && is_array($term['meta'])){
                                            foreach($term['meta'] as $meta_key => $meta_val){
                                                // we have to replace certain meta_key/meta_val
                                                // e.g. thumbnail id from woocommerce product categories.
                                                switch($meta_key){
                                                    case 'thumbnail_id':
                                                        if( $new_meta_val = $this->_imported_post_id($meta_val) ){
                                                            // use this new id.
                                                            $meta_val = $new_meta_val;
                                                        }
                                                        break;
                                                }
                                                update_term_meta( $term_id, $meta_key, $meta_val );
                                            }
                                        }
                                        $terms_to_set[ $taxonomy ][] = intval( $term_id );
                                    }
                                }
                            }
                            foreach ( $terms_to_set as $tax => $ids ) {
                                wp_set_post_terms( $post_id, $ids, $tax );
                            }
                        }

                        // procses visual composer just to be sure.
                        if ( strpos( $post_data['post_content'], '[vc_' ) !== false ) {
                            $this->vc_post( $post_id );
                        }
                        if ( !empty($post_data['meta']['_elementor_data']) || !!empty($post_data['meta']['_elementor_css']) ) {
                            $this->elementor_post( $post_id );
                        }
                        if ($post_type == 'mc4wp-form'){
                            update_option( 'mc4wp_default_form_id', $post_id );
                        }
                    }
                    break;
            }
            $this->log("Finish ".$post_data['post_name']);
            return true;
        }
        private function _parse_gallery_shortcode_content($content){
            // we have to format the post content. rewriting images and gallery stuff
            $replace      = $this->_imported_post_id();
            $urls_replace = array();
            foreach ( $replace as $key => $val ) {
                if ( $key && $val && ! is_numeric( $key ) && ! is_numeric( $val ) ) {
                    $urls_replace[ $key ] = $val;
                }
            }
            if ( $urls_replace ) {
                uksort( $urls_replace, array( &$this, 'cmpr_strlen' ) );
                foreach ( $urls_replace as $from_url => $to_url ) {
                    $content = str_replace( $from_url, $to_url, $content );
                }
            }
            if ( preg_match_all( '#\[gallery[^\]]*\]#', $content, $matches ) ) {
                foreach ( $matches[0] as $match_id => $string ) {
                    if ( preg_match( '#ids="([^"]+)"#', $string, $ids_matches ) ) {
                        $ids = explode( ',', $ids_matches[1] );
                        foreach ( $ids as $key => $val ) {
                            $new_id = $val ? $this->_imported_post_id( $val ) : false;
                            if ( ! $new_id ) {
                                unset( $ids[ $key ] );
                            } else {
                                $ids[ $key ] = $new_id;
                            }
                        }
                        $new_ids                   = implode( ',', $ids );
                        $content = str_replace( $ids_matches[0], 'ids="' . $new_ids . '"', $content );
                    }
                }
            }
            // contact form 7 id fixes.
            if ( preg_match_all( '#\[contact-form-7[^\]]*\]#', $content, $matches ) ) {
                foreach ( $matches[0] as $match_id => $string ) {
                    if ( preg_match( '#id="(\d+)"#', $string, $id_match ) ) {
                        $new_id = $this->_imported_post_id( $id_match[1] );
                        if ( $new_id ) {
                            $content = str_replace( $id_match[0], 'id="' . $new_id . '"', $content );
                        } else {
                            // no imported ID found. remove this entry.
                            $content = str_replace( $matches[0], '(insert contact form here)', $content );
                        }
                    }
                }
            }
            $content = str_replace( $this->demo_host, $this->current_host, $content );
            return $content;
        }
        private function _meta_data_id_update($key,$data,$post_id){
            if ($key === 'product_ids' || $key === '_upsell_ids' || $key === '_crosssell_ids' || $key ==='specific'){
                if (is_array($data)){
                    foreach ($data as $sub_key => $value) {
                        $new_meta_val = false;
                        if ( false !== strpos( $value, 'post-' ) ) {
                            $new_id = str_replace('post-', '', $value);
                            // check if this has been imported before
                            $new_id_v = $this->_imported_post_id($new_id);
                            if ($new_id_v){
                                $new_meta_val = 'post-'.$new_id_v;
                            } else {
                                $this->_delay_post_meta_process($post_id);
                                break;
                            }
                        } elseif(false !== strpos( $value, 'tax-' )) {
                            $new_id = str_replace('tax-', '', $value);
                            $new_id_t = $this->_imported_post_id($new_id);
                            if ($new_id_t){
                                $new_meta_val = 'tax-'.$new_id_t;
                            } else {
                                $this->_delay_post_meta_process($post_id);
                                break;
                            }
                        }elseif($value){
                            // check if this has been imported before
                            $new_id_v = $this->_imported_post_id($value);
                            if ($new_id_v){
                                $new_meta_val = $new_id_v;
                            } else {
                                $this->_delay_post_meta_process($post_id);
                                break;
                            }
                        }
                        if ( $new_meta_val ) {
                            $data[$sub_key] = $new_meta_val;
                        }
                    }
                } else {
                    $new_meta_val = $this->_imported_post_id( $data );
                    if ( $new_meta_val ) {
                        $data = $new_meta_val;
                    }
                    else {
                        $this->_delay_post_meta_process($post_id);
                    }
                }
                return $data;
            }
            elseif ($key === '_product_image_gallery' && !empty($data) && $data){
                $tmp_ids = explode(',',$data);
                $tmp_data = '';
                $delimiter = '';
                foreach ($tmp_ids as $tmp_id){
                    $new_id_v = $this->_imported_post_id($tmp_id);
                    if ($new_id_v){
                        $tmp_data .= $delimiter.$this->_imported_post_id( $tmp_id );
                        $delimiter = ',';
                    } else {
                        $this->_delay_post_meta_process($post_id);
                        break;
                    }

                }
                if (!empty($tmp_data) && $tmp_data){
                    $data = $tmp_data;
                }
                return $data;
            }
            if (is_array($data)){
                if (!empty($data)){
                    foreach ($data as $sub_key => $value) {
                        $data[$sub_key] = $this->_meta_data_id_update($sub_key,$value,$post_id);
                    }
                }
            } else {
                if (( $key === 'category_id') && ! empty( $data )) {
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_term_id( $data );
                    if ( $new_meta_val ) {
                        $data = $new_meta_val;
                    } else {
                        $this->_delay_post_meta_process($post_id);
                    }
                }
                if ( $key === 'page' && ! empty( $data ) ) {
                    if ( false !== strpos( $data, 'p.' ) ) {
                        $new_id = str_replace('p.', '', $data);
                        // check if this has been imported before
                        $new_meta_val = $this->_imported_post_id( $new_id );
                        if ( $new_meta_val ) {
                            $data = 'p.' . $new_meta_val;
                        } else {
                            $this->_delay_post_meta_process($post_id);
                        }
                    }else if($data){
                        // check if this has been imported before
                        $new_meta_val = $this->_imported_post_id( $data );
                        if ( $new_meta_val ) {
                            $data = $new_meta_val;
                        }
                        else {
                            $this->_delay_post_meta_process($post_id);
                        }
                    }
                }
                if ( $key === 'post_id' && ! empty( $data )) {
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_post_id( $data );
                    if ( $new_meta_val ) {
                        $data = $new_meta_val;
                    }
                    else {
                        $this->_delay_post_meta_process($post_id);
                    }
                }
                if ( $key === 'id' && ! empty( $data )) {
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_post_id( $data );
                    if ( $new_meta_val ) {
                        $data = $new_meta_val;
                    }
                    else {
                        $this->_delay_post_meta_process($post_id);
                    }
                }
                if ( $key === 'url' && ! empty( $data ) && (strstr( $data, 'localhost' ) || strstr( $data, $this->demo_host ))) {
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_post_id( $data );
                    if ( $new_meta_val ) {
                        $data = $new_meta_val;
                    }
                    else {
                        $this->_delay_post_meta_process($post_id);
                    }
                }
                if ( ($key === 'shortcode' || $key === 'editor') && ! empty( $data ) ) {
                    if (is_string($data)){
                        $data = $this->_parse_gallery_shortcode_content($data);
                    }
                }
            }
            return $data;
        }

        private function _elementor_id_import( &$item, $key ) {
            if (($key == 'product_id' || $key == 'id') && ! empty( $item )) {
                // check if this has been imported before
                $new_meta_val = $this->_imported_post_id( $item );
                if ( $new_meta_val ) {
                    $item = $new_meta_val;
                }
            }
            if ( $key == 'category_id' && ! empty( $item ) ) {
                // check if this has been imported before
                $new_meta_val = $this->_imported_term_id( $item );
                if ( $new_meta_val ) {
                    $item = $new_meta_val;
                }
            }
            if ( $key == 'product_ids' && ! empty( $item )) {
                // check if this has been imported before
                if (!is_array($item)){
                    $item = (array)$item;
                }
                $new_meta_val = [];
                foreach ($item as $item_id){
                    $new_meta_val[] = $this->_imported_post_id( $item_id );
                }
                if ( $new_meta_val ) {
                    $item = $new_meta_val;
                }
            }

            if ( $key == 'page' && ! empty( $item ) ) {

                if ( false !== strpos( $item, 'p.' ) ) {
                    $new_id = str_replace('p.', '', $item);
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_post_id( $new_id );
                    if ( $new_meta_val ) {
                        $item = 'p.' . $new_meta_val;
                    }
                }else if(is_numeric($item)){
                    // check if this has been imported before
                    $new_meta_val = $this->_imported_post_id( $item );
                    if ( $new_meta_val ) {
                        $item = $new_meta_val;
                    }
                }
            }
            if ( $key == 'post_id' && ! empty( $item ) && is_numeric( $item ) ) {
                // check if this has been imported before
                $new_meta_val = $this->_imported_post_id( $item );
                if ( $new_meta_val ) {
                    $item = $new_meta_val;
                }
            }
            if ( $key == 'url' && ! empty( $item ) && (strstr( $item, 'localhost' ) || strstr( $item, $this->demo_host ))) {
                // check if this has been imported before
                $new_meta_val = $this->_imported_post_id( $item );
                if ( $new_meta_val ) {
                    $item = $new_meta_val;
                }
            }
            if ( ($key == 'shortcode' || $key == 'editor') && ! empty( $item ) ) {
                if (is_string($item)){
                    $item = $this->_parse_gallery_shortcode_content($item);
                }
            }
        }
        private function _content_install_type() {
            $post_type = ! empty( $_POST['content'] ) ? $_POST['content'] : false;
            if ($post_type == 'attachment'){
                $all_data['attachment'] = $this->_get_json( 'attachment.json' );
            } else {
                $all_data  = $this->_get_json( 'default.json' );
            }

            if ( ! $post_type || ! isset( $all_data[ $post_type ] ) ) {
                return false;
            }
            $limit = 10 + ( isset( $_REQUEST['retry_count'] ) ? (int) $_REQUEST['retry_count'] : 0 );
            $x     = 0;
            foreach ( $all_data[ $post_type ] as $post_data ) {

                $this->_process_post_data( $post_type, $post_data );

                if ( $x ++ > $limit ) {
                    return array( 'retry' => 1, 'retry_count' => $limit );
                }
            }

            $this->_handle_delayed_posts();

            $this->_handle_post_orphans();

            $this->_handle_delayed_post_meta();

            // now we have to handle any custom SQL queries. This is needed for the events manager to store location and event details.
            $sql = $this->_get_sql( basename( $post_type ) . '.sql' );
            if ( $sql ) {
                global $wpdb;
                // do a find-replace with certain keys.
                if ( preg_match_all( '#__POSTID_(\d+)__#', $sql, $matches ) ) {
                    foreach ( $matches[0] as $match_id => $match ) {
                        $new_id = $this->_imported_post_id( $matches[1][ $match_id ] );
                        if ( ! $new_id ) {
                            $new_id = 0;
                        }
                        $sql = str_replace( $match, $new_id, $sql );
                    }
                }
                $sql  = str_replace( '__DBPREFIX__', $wpdb->prefix, $sql );
                $bits = preg_split( "/;(\s*\n|$)/", $sql );
                foreach ( $bits as $bit ) {
                    $bit = trim( $bit );
                    if ( $bit ) {
                        $wpdb->query( $bit );
                    }
                }
            }

            return true;

        }
        private function _handle_post_orphans() {
            $orphans = $this->_post_orphans();
            foreach ( $orphans as $original_post_id => $original_post_parent_id ) {
                if ( $original_post_parent_id ) {
                    if ( $this->_imported_post_id( $original_post_id ) && $this->_imported_post_id( $original_post_parent_id ) ) {
                        $post_data                = array();
                        $post_data['ID']          = $this->_imported_post_id( $original_post_id );
                        $post_data['post_parent'] = $this->_imported_post_id( $original_post_parent_id );
                        wp_update_post( $post_data );
                        $this->_post_orphans( $original_post_id, 0 ); // ignore future
                    }
                }
            }
        }
        private function _handle_delayed_posts( $last_delay = false ) {

            $this->log( ' ---- Processing ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts' );
            for ( $x = 1; $x < 4; $x ++ ) {
                foreach ( $this->delay_posts as $delayed_post_type => $delayed_post_datas ) {
                    foreach ( $delayed_post_datas as $delayed_post_id => $delayed_post_data ) {
                        if ( $this->_imported_post_id( $delayed_post_data['post_id'] ) ) {
                            $this->log( $x . ' - Successfully processed ' . $delayed_post_type . ' ID ' . $delayed_post_data['post_id'] . ' previously.' );
                            unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
                            $this->log( ' ( ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts remain ) ' );
                        } else if ( $this->_process_post_data( $delayed_post_type, $delayed_post_data, $last_delay ) ) {
                            $this->log( $x . ' - Successfully found delayed replacement for ' . $delayed_post_type . ' ID ' . $delayed_post_data['post_id'] . '.' );
                            // successfully inserted! don't try again.
                            unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
                            $this->log( ' ( ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts remain ) ' );
                        }
                    }
                }
            }
        }
        private function _handle_delayed_post_meta() {
            $this->log( ' ---- Processing ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts meta' );
            foreach ( $this->delay_meta as $delayed_post_id => $delayed_post_meta ) {
                unset($this->delay_meta[$delayed_post_id]);
                $post_meta = $this->_meta_data_id_update('meta',$delayed_post_meta,$delayed_post_id);
                if (!isset($this->delay_meta[$delayed_post_id]) && is_array($post_meta) && !empty($post_meta)){
                    $new_id = $this->_imported_post_id($delayed_post_id);
                    if ($new_id){
                        foreach ($post_meta as $key => $value){
                            update_post_meta($new_id,$key,$value);
                        }
                        $this->log( ' - Successfully processed ID ' . $delayed_post_id . ' previously.' );
                    }
                }
            }
        }
        private function _fetch_remote_file( $url, $post ) {
            // extract the file name and extension from the url
            $file_name  = basename( $url );

            $upload_dir = wp_upload_dir();
            //xu ly api media
            $local_file = trailingslashit( $upload_dir['basedir']) . 'cenos_media_package/' . $file_name;
            $upload     = false;
            if ( is_file( $local_file ) && filesize( $local_file ) > 0 ) {

                $_dest = trailingslashit($upload_dir['basedir'].$upload_dir['subdir']) . $file_name;
                copy( $local_file,  $_dest);
                $upload = array(
                    'file' => $_dest,
                    'url'  => trailingslashit($upload_dir['url']).$file_name,
                );
            }

            if ( ! $upload || (isset($upload['error']) &&  $upload['error'])) {
                // get placeholder file in the upload dir with a unique, sanitized filename
                $upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
                if ( $upload['error'] ) {
                    return new WP_Error( 'upload_dir_error', $upload['error'] );
                }

                // fetch the remote url and write it to the placeholder file

                $max_size = (int) apply_filters( 'import_attachment_size_limit', 0 );

                // we check if this file is uploaded locally in the source folder.
                $response = wp_remote_get( $url );
                $this->log($url . '-- Remote file');
                if ( is_array( $response ) && ! empty( $response['body'] ) && $response['response']['code'] == '200' ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    $headers = $response['headers'];
                    WP_Filesystem();
                    global $wp_filesystem;
                    $wp_filesystem->put_contents( $upload['file'], $response['body'] );

                    //
                } else {
                    // required to download file failed.
                    @unlink( $upload['file'] );
                    $this->error('-- Remote server did not respond');
                    return new WP_Error( 'import_file_error', esc_html__( 'Remote server did not respond' ,'cenos') );
                }

                $filesize = filesize( $upload['file'] );

                if ( 0 == $filesize ) {
                    @unlink( $upload['file'] );
                    $this->error('-- Zero size file downloaded');
                    return new WP_Error( 'import_file_error', esc_html__( 'Zero size file downloaded','cenos' ) );
                }
                $file_info = pathinfo($upload['file']);
                if ($file_info['extension'] != 'svg' && isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
                    @unlink( $upload['file'] );
                    $this->error('-- Remote file is incorrect size:'. $filesize ." VS ".$headers['content-length']);
                    return new WP_Error( 'import_file_error', esc_html__( 'Remote file is incorrect size' ,'cenos') );
                }
                if ( ! empty( $max_size ) && $filesize > $max_size ) {
                    @unlink( $upload['file'] );
                    $this->error(sprintf( esc_html__( 'Remote file is too large, limit is %s' ,'cenos'), size_format( $max_size ) ));
                    return new WP_Error( 'import_file_error', sprintf( esc_html__( 'Remote file is too large, limit is %s' ,'cenos'), size_format( $max_size ) ) );
                }
            }

            // keep track of the old and new urls so we can substitute them later
            $this->_imported_post_id( $url, $upload['url'] );
            $this->_imported_post_id( $post['guid'], $upload['url'] );
            // keep track of the destination if the remote url is redirected somewhere else
            if ( isset( $headers['x-final-location'] ) && $headers['x-final-location'] != $url ) {
                $this->_imported_post_id( $headers['x-final-location'], $upload['url'] );
            }
            $this->log($url . '-- Remote file success');
            return $upload;
        }
        private function _content_download_media(){
            // Time to run the import!
            set_time_limit( 0 );
            $_cpath = ABSPATH . 'wp-content/uploads/';
            $_tmppath = $_cpath . 'cenos_media_package';
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            WP_Filesystem();
            if (! is_dir( $_tmppath )){
                if (!wp_mkdir_p($_tmppath)) {
                    $this->error( ' -!! Error make dir' );
                    return false;
                }
            }
            $package = null;
            $url = CENOS_API_URL.'/media/cenos/full';
            if (!get_transient('cenos_media_package')){
                $package = download_url( $url, 180000 );
                if ( ! is_wp_error( $package ) ) {
                    $unzip = unzip_file( $package, $_tmppath );
                    if ( is_wp_error( $unzip ) ) {
                        $this->error( ' -!! Error unzip' );
                        return false;
                    } else {
                        set_transient('cenos_media_package',true,60 * 60 * 24);
                        $this->log(' -- Unzip FINISH.');
                        return true;
                    }
                } else {
                    $this->error( ' -!! Error download' );
                    return false;
                }
            }
            $this->log('-- Media package is exist');
            return true;
        }
        private function isEmptyDir($dir){
            return (($files = @scandir($dir)) && count($files) <= 2);
        }
        private function _content_install_widgets() {
            // todo: pump these out into the 'content/' folder along with the XML so it's a little nicer to play with
            $import_widget_positions = $this->_get_json( 'widget_positions.json' );
            $import_widget_options   = $this->_get_json( 'widget_options.json' );

            // importing.
            $widget_positions = get_option( 'sidebars_widgets' );
            if ( ! is_array( $widget_positions ) ) {
                $widget_positions = array();
            }

            foreach ( $import_widget_options as $widget_name => $widget_options ) {
                // replace certain elements with updated imported entries.
                foreach ( $widget_options as $widget_option_id => $widget_option ) {

                    // replace TERM ids in widget settings.
                    foreach ( array( 'nav_menu' ) as $key_to_replace ) {
                        if ( ! empty( $widget_option[ $key_to_replace ] ) ) {
                            // check if this one has been imported yet.
                            $new_id = $this->_imported_term_id( $widget_option[ $key_to_replace ] );
                            if ( ! $new_id ) {
                                // do we really clear this out? nah. well. maybe.. hmm.
                            } else {
                                $widget_options[ $widget_option_id ][ $key_to_replace ] = $new_id;
                            }
                        }
                    }
                    // replace POST ids in widget settings.
                    foreach ( array( 'image_id', 'post_id' ) as $key_to_replace ) {
                        if ( ! empty( $widget_option[ $key_to_replace ] ) ) {
                            // check if this one has been imported yet.
                            $new_id = $this->_imported_post_id( $widget_option[ $key_to_replace ] );
                            if ( ! $new_id ) {
                                // do we really clear this out? nah. well. maybe.. hmm.
                            } else {
                                $widget_options[ $widget_option_id ][ $key_to_replace ] = $new_id;
                            }
                        }
                    }
                }
                $existing_options = get_option( 'widget_' . $widget_name, array() );
                if ( ! is_array( $existing_options ) ) {
                    $existing_options = array();
                }
                $new_options = $existing_options + $widget_options;
                update_option( 'widget_' . $widget_name, $new_options );
            }
            update_option( 'sidebars_widgets', array_merge( $widget_positions, $import_widget_positions ) );

            return true;

        }
        public function _content_install_settings() {

            $this->_handle_delayed_posts( true ); // final wrap up of delayed posts.
            $this->vc_post(); // final wrap of vc posts.
            $this->_handle_delayed_post_meta();
            $custom_options = $this->_get_json( 'options.json' );

            // we also want to update the widget area manager options.
            foreach ( $custom_options as $option => $value ) {
                // we have to update widget page numbers with imported page numbers.
                if (
                    preg_match( '#(wam__position_)(\d+)_#', $option, $matches ) ||
                    preg_match( '#(wam__area_)(\d+)_#', $option, $matches )
                ) {
                    $new_page_id = $this->_imported_post_id( $matches[2] );
                    if ( $new_page_id ) {
                        // we have a new page id for this one. import the new setting value.
                        $option = str_replace( $matches[1] . $matches[2] . '_', $matches[1] . $new_page_id . '_', $option );
                    }
                }
                if ( $value && ! empty( $value['custom_logo'] ) ) {
                    $new_logo_id = $this->_imported_post_id( $value['custom_logo'] );
                    if ( $new_logo_id ) {
                        $value['custom_logo'] = $new_logo_id;
                    }
                }
                if ( $option == 'familab_featured_images' ) {
                    $value      = maybe_unserialize( $value );
                    $new_values = array();
                    if ( is_array( $value ) ) {
                        foreach ( $value as $cat_id => $image_id ) {
                            $new_cat_id   = $this->_imported_term_id( $cat_id );
                            $new_image_id = $this->_imported_post_id( $image_id );
                            if ( $new_cat_id && $new_image_id ) {
                                $new_values[ $new_cat_id ] = $new_image_id;
                            }
                        }
                    }
                    $value = $new_values;
                }
                update_option( $option, $value );
            }

            $menu_ids = $this->_get_json( 'menu.json' );
            $save     = array();
            foreach ( $menu_ids as $menu_id => $term_id ) {
                $new_term_id = $this->_imported_term_id( $term_id );
                if ( $new_term_id ) {
                    $save[ $menu_id ] = $new_term_id;
                }
            }
            if ( $save ) {
                set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save ) );
            }

            // set the blog page and the home page.
            $shoppage = get_page_by_title( 'Shop' );
            if ( $shoppage ) {
                update_option( 'woocommerce_shop_page_id', $shoppage->ID );
            }
            $shoppage = get_page_by_title( 'Cart' );
            if ( $shoppage ) {
                update_option( 'woocommerce_cart_page_id', $shoppage->ID );
            }
            $shoppage = get_page_by_title( 'Checkout' );
            if ( $shoppage ) {
                update_option( 'woocommerce_checkout_page_id', $shoppage->ID );
            }
            $shoppage = get_page_by_title( 'My Account' );
            if ( $shoppage ) {
                update_option( 'woocommerce_myaccount_page_id', $shoppage->ID );
            }
            $homepage = get_page_by_title( 'Home' );
            if ( $homepage ) {
                update_option( 'page_on_front', $homepage->ID );
                update_option( 'show_on_front', 'page' );
            }
            $blogpage = get_page_by_title( 'Blog' );
            if ( $blogpage ) {
                update_option( 'page_for_posts', $blogpage->ID );
                update_option( 'show_on_front', 'page' );
            }

            global $wp_rewrite;
            $wp_rewrite->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );
            update_option( 'rewrite_rules', false );
            $wp_rewrite->flush_rules( true );

            return true;
        }
        public function _get_json( $file ) {
            $theme_style = __DIR__ . '/content/';
            if ( is_file( $theme_style . basename( $file ) ) ) {
                WP_Filesystem();
                global $wp_filesystem;
                $file_name = $theme_style . basename( $file );
                if ( file_exists( $file_name ) ) {
                    return json_decode( $wp_filesystem->get_contents( $file_name ), true );
                }
            }
            // backwards compat:
            if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
                WP_Filesystem();
                global $wp_filesystem;
                $file_name = __DIR__ . '/content/' . basename( $file );
                if ( file_exists( $file_name ) ) {
                    return json_decode( $wp_filesystem->get_contents( $file_name ), true );
                }
            }
            return array();
        }
        private function _get_sql( $file ) {
            if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
                WP_Filesystem();
                global $wp_filesystem;
                $file_name = __DIR__ . '/content/' . basename( $file );
                if ( file_exists( $file_name ) ) {
                    return $wp_filesystem->get_contents( $file_name );
                }
            }
            return false;
        }
        public $logs = array();
        public function log( $message ) {
            $this->logs[] = $message;
        }
        public $errors = array();
        public function error( $message ) {
            $this->errors[] = 'ERROR!!!! ' . $message;
        }

        public function theme_setup_logo_design() {
            ?>
            <h1>Logo &amp; Design</h1>
            <h3>Upload Logo</h3>
            <form method="post">
                <table>
                    <tr>
                        <td>
                            <div id="current-logo">
                                <?php $image_url = do_shortcode(get_theme_mod( 'logo', get_template_directory_uri().'/assets/images/logo.svg'));
                                if ( $image_url ) {
                                    $image = '<img class="site-logo" src="%s" alt="%s" />';
                                    printf(
                                        $image,
                                        $image_url,
                                        get_bloginfo( 'name' ),
                                        '200px'
                                    );
                                } ?>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="button button-upload">Upload New Logo</a>
                        </td>
                    </tr>
                </table>
                <p>You can upload and customize this in Theme Options later.</p>

                <hr/>

                <h3 class="preset_title">Select Preset</h3>
                <?php
                $img_url = get_template_directory_uri().'/inc/preset/img/home/';?>
                <div class="theme-presets">
                    <?php
                    $theme_presets = $this->theme_preset_data();
                    if (!empty($theme_presets)):?>
                        <ul>
                            <?php $first_item = true;
                            foreach ($theme_presets as $preset):
                                ?>
                                <li <?php cenos_esc_data($first_item ? 'class="current"':'');?>>
                                    <a href="#" data-style="<?php echo esc_attr($preset['slug']);?>" data-header="<?php echo esc_attr($preset['header']);?>">
                                        <img src="<?php echo esc_url($img_url.$preset['thumb']); ?>">
                                    </a>
                                </li>
                                <?php
                                if ($first_item){
                                    $first_item = false;
                                }
                            endforeach;?>
                        </ul>
                    <?php endif; ?>
                </div>
                <p><strong>NOTE: This works best on a fresh new installation. </strong><br/>* Images are not included. You need to replace the dummy images with your own images.
                    All pages are included in the demo content, so you mix and match this in Theme Options.
                </p>

                <input type="hidden" name="new_logo_id" id="new_logo_id" value="">
                <input type="hidden" name="new_style" id="new_style" value="<?php echo esc_attr($theme_presets[0]['slug']);?>">
                <input type="hidden" name="new_header" id="new_header" value="<?php echo esc_attr($theme_presets[0]['header']);?>">
                <p class="cenos-setup-actions step">
                    <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'cenos' ); ?>" name="save_step" />
                    <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next">Skip this step</a>
                    <?php wp_nonce_field( 'cenos-setup' ); ?>
                </p>
            </form>
            <?php
        }
        public function theme_setup_logo_design_save() {
            check_admin_referer( 'cenos-setup' );
            $new_logo_id = (int) $_POST['new_logo_id'];
            // save this new logo url into the database and calculate the desired height based off the logo width.
            // copied from dtbaker.theme_options.php
            if ( $new_logo_id ) {
                $attr = wp_get_attachment_image_src( $new_logo_id, 'full' );
                if ( $attr && ! empty( $attr[0] )) {
                    set_theme_mod( 'logo', $attr[0] );
                }
            }
            $new_style = isset($_POST['new_style']) ? $_POST['new_style'] : false;
            $new_header = isset($_POST['new_header']) ? $_POST['new_header'] : false;
            if ($new_header){
                $this->theme_preset_setup($new_header,'header');
            }
            if($new_style) {
                // Set homepage
                $homepage = get_posts( array( 'name' => $new_style, 'post_type' => 'page' ) );
                $this->log(print_r($homepage));
                if ( $homepage ) {
                    update_option( 'page_on_front',$homepage[0]->ID );
                    update_option( 'show_on_front', 'page' );
                }
            }
            $customizer = Customizer::get_instance();
            $customize_file = $customizer->get_current_customize_file();
            $customize_style = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$customize_file;
            if (file_exists($customize_style)) {
                unlink($customize_style);
            }
            wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
            exit;
        }

        public function theme_preset_setup($preset_file, $type){
            if ($type == 'header'){
                $file_path = get_template_directory() . '/inc/preset/json/header/'.$preset_file.'.json';
            } elseif ($type == 'shop') {
                $file_path = get_template_directory() . '/inc/preset/json/shop_catalog/'.$preset_file.'.json';
            }
            if (file_exists( $file_path)) {
                global $wp_filesystem;
                $preset_values = false;
                // Initialize the WP filesystem, no more using 'file-put-contents' function
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                if( $wp_filesystem) {
                    $preset_json = $wp_filesystem->get_contents( $file_path);
                    $preset_values = json_decode($preset_json,true);
                }
                if (!empty($preset_values)){
                    foreach ($preset_values as $key => $value) {
                        if($key == 'logo') continue;
                        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
                            $new_v = $this->_imported_post_id($value);
                            if ($new_v){
                                $value = $new_v;
                            }
                        }
                        set_theme_mod($key, $value);
                    }
                }
            }
        }

        public function theme_setup_updates()
        {
            ?>
            <h1>Activate Theme</h1>
            <p class="lead">Enter your Purchase Code for Automatic Theme Updates and access to Support.</p>
            <?php
            $slug = basename(get_template_directory());

            $output = '';

            //get errors so we can show them
            $errors = get_option($slug . '_wup_errors', array());
            delete_option($slug . '_wup_errors'); //delete existing errors as we will handle them next
            //check if we have a purchase code saved already
            $purchase_code = sanitize_text_field(get_option('familab_license_key' . $slug, ''));

            //output errors and notifications
            if (!empty($errors)) {
                foreach ($errors as $key => $error) {
                    echo '<div class="notice-error notice-alt"><p>' . $error . '</p></div>';
                }
            }

            if (!empty($purchase_code)) {
                if (!empty($errors)) {
                    //since there is already a purchase code present - notify the user
                    echo '<div class="notice-warning notice-alt"><p>Purchase code not updated. We will keep the existing one.</p></div>';
                } else {
                    //this means a valid purchase code is present and no errors were found
                    echo '<div class="notice-success notice-alt notice-large">Your <strong>purchase code is valid</strong>. Thank you! Enjoy Cenos Theme and automatic updates.</div>';
                }
            }
            if (empty($purchase_code)) {
                echo '<form class="wupdates_purchase_code" action="" method="post"><p>Find out how to <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">get your purchase code</a> here.</p><input type="hidden" name="familab_action" value="save_license_key" />' .
                    '<input type="text" id="' . sanitize_title($slug) . '_wup_purchase_code" name="license_key"
				              value="' . $purchase_code . '" placeholder="Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )"/><br/>' .
                    '<p class="confirm-check">
  								<input type="checkbox" id="cenos-terms" name="terms" onclick="toggleSubmit(this);">
  								<label for="cenos-terms">Confirm that, according to the Envato License Terms, each license entitles one person for a single project. Creating multiple unregistered installations is a copyright violation. <a href="https://themeforest.net/licenses/standard" target="_blank">More info</a>.</label>
			  				</p>' .
                    '<p class="cenos-setup-actions step">' .
                    '<input type="submit" id="cenos-activate" class="button button-large button-next button-primary disabled" value="Activate"/>' .
                    '<a href="' . esc_url($this->get_next_step_link()) . '" class="button button-large button-next">Skip this step</a>' .
                    '</p>
				      	</form>
				        <script type="text/javascript">
	                        function toggleSubmit(checkbox){
	                          var button = document.getElementById("cenos-activate");
						      if(checkbox.checked) {
						        button.classList.remove("disabled")
						      } else {
						        button.classList.add("disabled")
						      }
						    }
                        </script>';
            } else {
                echo '<form class="wupdates_purchase_code" action="" method="post">' .
                    '<input type="hidden" name="wupdates_pc_theme" value="' . $slug . '" />' .
                    '<input type="text" id="' . sanitize_title($slug) . '_wup_purchase_code" name="' . sanitize_title($slug) . '_wup_purchase_code"
				              value="' . $purchase_code . '" placeholder="Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )"/><br/><br/>' .
                    '<p class="cenos-setup-actions step">' .
                    '<a href="' . esc_url($this->get_next_step_link()) . '" class="button button-primary button-large button-next">' .esc_html__('Continue', 'cenos') . '</a>' .
                    '</p>
				      </form>';
            }
            ?>
            <?php wp_nonce_field('cenos-setup'); ?>

            <?php
        }
        public function theme_setup_ready() {
            if (defined( 'ELEMENTOR_VERSION' )){
                global $elementor_instance;
                if (empty($elementor_instance)){
                    $elementor_instance = \Elementor\Plugin::instance();
                }
                $kit = $elementor_instance->kits_manager->get_active_kit();

                if ($kit->get_id() ) {
                    $this->log('There\'s already an active kit.');
                } else {
                    $created_default_kit = $elementor_instance->kits_manager->create_default();
                    if ($created_default_kit){
                        update_option( 'elementor_active_kit', $created_default_kit );
                    }
                }
            }

            update_option( 'cenos_theme_setup_complete', time() );
            ?>
            <h1>Your Website is Ready!</h1>

            <p class="lead success">Congratulations! The theme has been activated and your website is ready. Login to your WordPress dashboard to make changes and modify any of the default content to suit your needs.</p>
            <p>Please come back and <a href="http://themeforest.net/downloads" target="_blank">leave a 5-star rating</a> if you are happy with this theme.</p>
            <div class="cenos-setup-next-steps">
                <div class="cenos-setup-next-steps-first">
                    <h2>Next Steps</h2>
                    <ul>
                        <?php if(class_exists('woocommerce')) { ?><li class="setup-product"><a class="button  button-primary button-large woocommerce-button" href="<?php echo admin_url().'admin.php?page=wc-setup';?>">Setup WooCommerce (optional)</a></li><?php } ?>
                        <li class="setup-product"><a class="button button-primary button-large" href="https://www.facebook.com/Familab-101219748178022" target="_blank">Join Facebook Group</a></li>
                        <li class="setup-product"><a class="button button-large" href="<?php echo esc_url( home_url() ); ?>">View your new website!</a></li>
                    </ul>
                </div>
                <div class="cenos-setup-next-steps-last">
                    <h2>More Resources</h2>
                    <ul>
                        <li class="documentation"><a href="https://docs.familab.net/">Theme Documentation</a></li>
                        <li class="woocommerce documentation"><a href="https://docs.woocommerce.com/document/woocommerce-101-video-series/">Learn how to use WooCommerce</a></li>
                        <li class="howto"><a href="https://wordpress.org/support/">Learn how to use WordPress</a></li>
                        <li class="rating"><a href="http://themeforest.net/downloads">Leave an Item Rating</a></li>
                    </ul>
                </div>
            </div>
            <?php
        }
        public function _content_install_fmfw_font(){
            $this->_handle_delayed_posts( true ); // final wrap up of delayed posts.
            $this->vc_post(); // final wrap of vc posts.
            $this->_handle_delayed_post_meta();
            $custom_fonts = $this->_get_json( 'custom_fonts.json' );
            foreach ( $custom_fonts as $custom_font ) {
                $term = $custom_font['term'];
                $taxonomy = $term['taxonomy'];
                if ( taxonomy_exists( $taxonomy ) ) {
                    $term_exists = term_exists( $term['slug'], $taxonomy );
                    $term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
                    if ( ! $term_id ) {
                        if ( ! empty( $term['parent'] ) ) {
                            // see if we have imported this yet?
                            $term['parent'] = $this->_imported_term_id( $term['parent'] );
                        }
                        $t = wp_insert_term( $term['name'], $taxonomy, $term );
                        if ( ! is_wp_error( $t ) ) {
                            $term_id = $t['term_id'];
                        } else {
                            // todo - error
                            continue;
                        }
                    }
                    $this->_imported_term_id( $term['term_id'], $term_id );
                    // add the term meta.
                    if($term_id && !empty($custom_font['data']) && is_array($custom_font['data'])){
                        $my_data = $custom_font['data'];
                        foreach ($my_data as $k => $v){
                            $my_data[$k] = $this->_imported_post_id($v);
                        }
                        update_option( "taxonomy_fmfw_custom_fonts_{$term_id}", $my_data );
                    }
                }
            }
            return true;
        }
        public function _content_install_rev_slide(){
            global $wp_filesystem;
            $_cpath = ABSPATH . 'wp-content/uploads/';
            $_tmppath = $_cpath . 'cenos_rev_slide';
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            WP_Filesystem();
            if (! is_dir( $_tmppath )){
                if (!wp_mkdir_p($_tmppath)) {
                    $this->error( ' -!! Error make dir' );
                    return false;
                }
            }
            foreach ($this->theme_slider_packages() as $slider_p){
                $file = $_tmppath.'/'.$slider_p . '.zip';
                if ( ! file_exists( $file ) ) {
                    $url = CENOS_API_URL.'/slider/cenos/'.$slider_p;
                    $package = download_url( $url, 180000 );
                    if ( ! is_wp_error( $package ) ) {
                        $newname = $_tmppath.'/'.$slider_p.'.zip';
                        if ( ! ( rename($package, $newname) || $wp_filesystem->move($package, $newname, true) ) ){
                            $this->error( ' -!! Error Move '.$slider_p );
                        }
                        $this->log(' -- Download '.$slider_p.' FINISH.');
                    } else {
                        $this->error( ' -!! Error download '.$slider_p );
                    }
                }
            }
            $rev_files = glob( $_tmppath .'/*.zip' );
            if ( ! empty( $rev_files ) ) {
                foreach ( $rev_files as $rev_file ) {
                    $_FILES['import_file']['error']    = UPLOAD_ERR_OK;
                    $_FILES['import_file']['tmp_name'] = $rev_file;
                    ob_start();
                    $slider = new RevSliderSliderImport();
                    $slider->import_slider( true );
                    ob_end_clean();
                }
            }
            return true;
        }
        // return the difference in length between two strings
        public function cmpr_strlen( $a, $b ) {
            return strlen( $b ) - strlen( $a );
        }
    }
}
add_action( 'after_setup_theme', 'cenos_theme_setup_wizard', 10 );
function cenos_theme_setup_wizard(){
    Fmfw_Theme_Setup::instance();
}
