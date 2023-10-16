<?php
/**
 * Cenos Included Plugins
 *
 * @package  Cenos
 * @since    1.0.0
 */

require CENOS_TEMPLATE_DIRECTORY . '/inc/lib/tgmpa/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'cenos_register_required_plugins' );

/**
 * Calls tgmpa() with $plugins & $config arrays
 */
function cenos_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
        array(
            'name'               => 'Fami FrameWork',
            'slug'               => 'fami-framework',
            'source'             => CenosGetPluginDownloadLink('fami-framework'),
            'required'           => true,
            'source_type'        => 'external',
        ),
        array(
            'name'               => 'Fami Template Kits',
            'slug'               => 'fami-templatekits',
            'source'             => CenosGetPluginDownloadLink('fami-templatekits'),
            'required'           => true,
            'source_type'        => 'external',
        ),
        array(
            'name'     => esc_html__( 'Kirki Customizer Framework', 'cenos' ),
            'slug'     => 'kirki',
            'required' => true,
        ),
        array(
            'name'      => esc_html__('Meta Box','cenos'),
            'slug'      => 'meta-box',
            'required'  => true,
        ),
	array(
            'name'    => esc_html__( 'WooCommerce', 'cenos' ),
            'slug'    => 'woocommerce',
            'version' => '4.0.1',
            'required'  => true,
		),
        array(
            'name'    => esc_html__( 'Elementor', 'cenos' ),
            'slug'    => 'elementor',
            'required'  => false,
        ),
        array(
            'name'               => 'Slider Revolution',
            'slug'               => 'revslider',
            'source'             => CenosGetPluginDownloadLink('revslider'),
            'required'           => false,
            'source_type'        => 'external',
        ),
        array(
            'name'     => esc_html__( 'Variation Swatches for WooCommerce', 'cenos' ),
            'slug'     => 'woo-variation-swatches',
            'required' => false,
        ),
        array(
            'name'               => 'WOOF - WooCommerce Products Filter',
            'slug'               => 'woocommerce-products-filter',
            'source'             => CenosGetPluginDownloadLink('woocommerce-products-filter'),
            'required'           => false,
            'source_type'        => 'external',
        ),
        array(
            'name'               => 'Variation Swatches for WooCommerce - Pro',
            'slug'               => 'woo-variation-swatches-pro',
            'source'             => CenosGetPluginDownloadLink('woo-variation-swatches-pro'),
            'required'           => false,
            'recommended' => true,
            'source_type'        => 'external',
        ),
        array(
            'name'     => esc_html__( 'WooCommerce Currency Switcher', 'cenos' ),
            'slug'     => 'woocommerce-currency-switcher',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'Contact Form by WPForms', 'cenos' ),
            'slug'     => 'wpforms-lite',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'MailChimp for WordPress', 'cenos' ),
            'slug'     => 'mailchimp-for-wp',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'TI WooCommerce Wishlist', 'cenos' ),
            'slug'     => 'ti-woocommerce-wishlist',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'Enjoy Instagram', 'cenos' ),
            'slug'     => 'enjoy-instagram-instagram-responsive-images-gallery-and-carousel',
            'required' => false,
        ),
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'cenos' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'cenos' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'cenos' ), // translators: %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'cenos' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ,'cenos'), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ,'cenos'), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ,'cenos'), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ,'cenos'), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ,'cenos'), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ,'cenos'), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ,'cenos'), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ,'cenos'), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ,'cenos'),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ,'cenos'),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'cenos' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'cenos' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'cenos' ), // %s = dashboard link.
			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),
	);

	tgmpa( $plugins, $config );

}
if (!function_exists('CenosGetPluginDownloadLink')) {
    function CenosGetPluginDownloadLink($slug){
        global $current_theme;
        return CENOS_API_URL.'/plugin/'.$current_theme['Template'].'/'.$slug;
    }
}
