<?php
if( !class_exists('Fmfw_Market_Check')){
    class Fmfw_Market_Check{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            add_action('admin_init',array($this,'save_license_key'));
        }
        public function license_form(){
            if (isset($_GET['refresh_data']) && $_GET['refresh_data'] == 'fmfw_reset'){
                delete_transient('check_license'.FAMI_THEME_SLUG);
                delete_transient('check_familab_remote');
                delete_transient('check_license'.FAMI_THEME_SLUG);
                delete_transient('theme_version_' . FAMI_THEME_SLUG);
                delete_transient('theme_changelog_' . FAMI_THEME_SLUG);
            }
            $license_key = get_option('familab_license_key'.FAMI_THEME_SLUG);
            $check_license = false;
            if($license_key ==""){
                $license_key = get_option('familab_license_key');
                if ($license_key !=""){
                    update_option('familab_license_key'.FAMI_THEME_SLUG,$license_key);
                }
            }
            ?>
            <?php if($license_key !=""){
                $check_license_result = $this->check_license($license_key,false,true);
                $has_full_info = false;
                if (is_array($check_license_result) && isset($check_license_result['status'])){
                    $license_status = $check_license_result['status'];
                    $has_full_info = true;
                }else{
                    $license_status = $check_license_result;
                }
                if(!$license_status){
                    $check_license = false;
                    ?>
                    <div class="familab-alert familab-alert-warning">
                        <p>
                            <?php
                            if ($has_full_info):
                                if (isset($check_license_result['msg'])):?>
                                    <strong>
                                        <?php esc_html_e($check_license_result['msg']);?>
                                    </strong>
                                <?php endif;
                                if (isset($check_license_result['domain_active'])):
                                    $current_domain = parse_url(get_site_url());
                                    if ($current_domain['host'] != $check_license_result['domain_active']):?>
                                        <strong>
                                            <?php esc_html_e('The license is already activated by another domain!','fami-framework');?>
                                        </strong>
                                        <span><?php esc_html_e('If you need assistance, please leave your ticket via our support system','fami-framework');?></span>
                                        <a class="welcome-icon dashicons-editor-help"
                                           target="_blank"
                                           href="https://support.familab.net/submit-ticket/"><?php esc_html_e('Request Support', 'fami-framework'); ?></a>
                                    <?php endif;
                                endif
                                ?>
                            <?php else: ?>
                                <strong>
                                    <?php esc_html_e('License has not been verify. ','fami-framework');?>
                                </strong>
                            <?php endif;?>
                        </p>
                        <?php
                        if (isset($check_license_result['status'])){
                            echo '<pre style="display: none">';
                            print_r($check_license_result);
                            echo '</pre>';
                        }
                        ?>
                    </div>
                <?php }else{
                    $check_license = true;
                    ?>
                    <div class="familab-alert familab-alert-notice">
                        <p>
                            <strong><?php esc_html_e('License has been activated','fami-framework');?></strong>
                        </p>
                    </div>
                <?php }
                ?>
            <?php }
            if (!$check_license) {
                ?>
                <div class="postbox-content">
                    <p>
                        <?php printf(esc_html__( 'In order to install sample data, automatic update theme you need to validate %s purchase code and validate %s today for premium support,', 'fami-framework' ),FAMI_THEME_NAME,FAMI_THEME_NAME); ?>
                        <br/>
                        <?php esc_html_e( 'latest updates, eCommerce guides, and much more.', 'fami-framework' )
                        ?>
                        <a target="_blank"
                           href="https://support.familab.net/knowledgebase/how-to-get-envato-purchase-code/"><?php esc_html_e( 'How to get envato purchase code.', 'fami-framework' ); ?></a>
                    </p>
                    <form class="license-form"
                          action="<?php echo esc_url( admin_url( 'admin.php?page=fami-dashboard&tab=license' ) ); ?>"
                          method="post">
                        <p>
                            <input placeholder="<?php esc_attr_e( 'Purchase Code', 'fami-framework' ); ?>"
                                   class="input-text" type="text" name="license_key"
                                   value="<?php echo esc_attr( $license_key ); ?>">
                        </p>
                        <button class="save_license_key tr-button"><?php esc_html_e( 'Save', 'fami-framework' ); ?></button>
                        <input type="hidden" name="familab_action" value="save_license_key">
                    </form>
                </div>
                <?php
            }

            if ($check_license && $this->theme_has_update()){
                $update_url = wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . base64_encode( FAMI_THEME_SLUG ) ), 'upgrade-theme_' . FAMI_THEME_SLUG );
                $html = sprintf('<a href="%1$s" %2$s>%3$s</a>.' ,
                    $update_url,
                    sprintf( 'aria-label="%s" id="update-theme" data-slug="%s"',
                        esc_attr( sprintf( esc_attr__( 'Update %s Now' ,'fami-framework'), FAMI_THEME_NAME ) ),
                        FAMI_THEME_SLUG
                    ),
                    esc_html__('Update Now','fami-framework')
                );
                $fmdebug = false;
                if ($fmdebug){
                    $parse = parse_url(get_site_url());
                    $back_uri = $parse['host'];
                    $update = Fmfw_Theme_Update::instance()->theme_version();
                    echo FAMILAB_API_URL.'/package/'.FAMI_THEME_SLUG.'/'.$license_key.'/'.md5($back_uri).'/'.$update['new_version'].'.zip';
                }
                echo ($html);
            }
        }
        public function theme_has_update(){
            $transient_update_themes = get_option('_site_transient_update_themes');
            $avaiable_update = false;
            if (isset($transient_update_themes->response[FAMI_THEME_SLUG]['new_version'])){
                $theme_version = $transient_update_themes->response[FAMI_THEME_SLUG]['new_version'];
            }elseif (isset($transient_update_themes->checked[FAMI_THEME_SLUG])){
                $theme_version = $transient_update_themes->checked[FAMI_THEME_SLUG];
            }else{
                $theme_version =  FAMI_THEME_VERSION;
            }
            if ( version_compare( $theme_version, FAMI_THEME_VERSION ) == 1 ) {
                $avaiable_update = true;
            }
            return $avaiable_update;
        }
        public function check_license($license_key = '',$skip_transient = false, $full_info = false){
            if ($license_key == '')
                $license_key = get_option('familab_license_key'.FAMI_THEME_SLUG);
            if (!$license_key == ''){
                $site_code = base64_encode(get_site_url());
                $familab_salt = get_option('familab_salt'.$site_code);
                if ($familab_salt==''){
                    $license_transient = get_transient('check_license'.FAMI_THEME_SLUG);
                    if (empty($license_transient) || $skip_transient){
                        $url = FAMILAB_API_URL.'/purchasecode/'.FAMI_THEME_SLUG.'/'.$license_key.'/'.$site_code;
                        $reponsive = wp_remote_get($url);
                        if( !is_wp_error($reponsive)){
                            $result = isset($reponsive['body']) ? json_decode($reponsive['body']) : false;
                            if (!$result){
                                set_transient('check_license'.FAMI_THEME_SLUG,'no',DAY_IN_SECONDS);
                                return false;
                            }
                            if( $result->status ){
                                update_option('familab_salt'.$site_code,$result->salt,false);
                                set_transient('check_license'.FAMI_THEME_SLUG,'yes',DAY_IN_SECONDS);
                                return true;
                            }else{
                                if ($full_info){
                                    return (array)$result;
                                }
                            }
                        }
                    } else {
                        if ($license_transient == 'yes'){
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                }else{
                    return true;
                }
            }
            return false;
        }
        public function save_license_key(){
            if( isset($_POST['familab_action']) && $_POST['familab_action'] == 'save_license_key'){
                $license_key = isset($_POST['license_key'])? $_POST['license_key'] :'';
                delete_transient('check_license'.FAMI_THEME_SLUG);
                update_option('familab_license_key'.FAMI_THEME_SLUG,$license_key);
                $this->check_license($license_key,true);
            }
        }
    }
}
Fmfw_Market_Check::instance();
