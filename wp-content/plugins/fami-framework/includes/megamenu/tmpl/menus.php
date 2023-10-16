<# if ( data.depth == 0 ) { #>
	<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'fami-framework' ) ?>" data-panel="mega"><?php esc_html_e( 'Mega Menu', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'fami-framework' ) ?>" data-panel="background"><?php esc_html_e( 'Background', 'fami-framework' ) ?></a>
    <a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Badges', 'fami-framework' ) ?>" data-panel="badges"><?php esc_html_e( 'Badges', 'fami-framework' ) ?></a>
	<div class="separator"></div>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'fami-framework' ) ?>" data-panel="icon"><?php esc_html_e( 'Icon', 'fami-framework' ) ?></a>
<# } else if ( data.depth == 1 ) { #>
	<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Setting', 'fami-framework' ) ?>" data-panel="settings"><?php esc_html_e( 'Settings', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Design', 'fami-framework' ) ?>" data-panel="design"><?php esc_html_e( 'Design', 'fami-framework' ) ?></a>
    <a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Badges', 'fami-framework' ) ?>" data-panel="badges"><?php esc_html_e( 'Badges', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Content', 'fami-framework' ) ?>" data-panel="content"><?php esc_html_e( 'Content', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'fami-framework' ) ?>" data-panel="icon"><?php esc_html_e( 'Icon', 'fami-framework' ) ?></a>
<# } else { #>
    <a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Badges', 'fami-framework' ) ?>" data-panel="badges"><?php esc_html_e( 'Badges', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'fami-framework' ) ?>" data-panel="content"><?php esc_html_e( 'Content', 'fami-framework' ) ?></a>
	<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'fami-framework' ) ?>" data-panel="icon"><?php esc_html_e( 'Icon', 'fami-framework' ) ?></a>
<# } #>
