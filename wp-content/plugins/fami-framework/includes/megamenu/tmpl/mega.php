<div id="fmm-panel-mega" class="fmm-panel-mega fmm-panel">
	<p class="mega-settings">
		<span class="setting-field fmm-mega-enable-field">
			<label class="switch">
				<?php esc_html_e( 'Enable mega menu', 'fami-framework' ) ?><br>
				<select name="{{ fmm.getFieldName( 'mega', data.data['menu-item-db-id'] ) }}">
					<option value="0"><?php esc_html_e( 'No', 'fami-framework' ) ?></option>
					<option value="1" {{ parseInt( data.megaData.mega ) ? 'selected="selected"' : '' }}><?php esc_html_e( 'Yes', 'fami-framework' ) ?></option>
				</select>
			</label>
		</span>
        <span class="setting-field fmm-mega-width-field" style="{{ parseInt( data.megaData.mega ) ? '' : 'display: none;' }}">
			<label>
				<?php esc_html_e( 'Container width', 'fami-framework' ) ?><br>
				<select name="{{ fmm.getFieldName( 'width', data.data['menu-item-db-id'] ) }}">
					<option value="container"><?php esc_html_e( 'Default', 'fami-framework' ) ?></option>
					<option value="container-fluid" {{ 'container-fluid' == data.megaData.width ? 'selected="selected"' : '' }}><?php esc_html_e( 'Fluid', 'fami-framework' ) ?></option>
                    <option value="custom" {{ 'custom' == data.megaData.width ? 'selected="selected"' : '' }}><?php esc_html_e( 'Custom', 'fami-framework' ) ?></option>
				</select>
			</label>
		</span>
        <span class="setting-field" style="{{ ('custom' != data.megaData.width || parseInt( data.megaData.mega ) == 0) ? 'display: none;' : '' }}">
            <label>
				<?php esc_html_e( 'Mega panel width', 'fami-framework' ) ?><br>
                <input type="text" name="{{ fmm.getFieldName( 'custom_width', data.data['menu-item-db-id'] ) }}" placeholder="1140px" value="{{ data.megaData.custom_width }}">
			</label>
		</span>
	</p>
    <p class="mega-settings">
        <span class="setting-field fmm-mega-block-field">
			<label class="switch">
				<?php esc_html_e( 'Use Custom Block as Mega Content', 'fami-framework' ) ?><br>
				<select name="{{ fmm.getFieldName( 'post_content', data.data['menu-item-db-id'] ) }}">
					<option value="0"><?php esc_html_e( 'No', 'fami-framework' ) ?></option>
                    <?php
                    $blocks = function_exists('fmtpl_get_block_post')? fmtpl_get_block_post():[];
                    if (!empty($blocks)):
                        foreach ($blocks as $block):?>
                            <option value="<?php echo $block->ID;?>" {{ (parseInt( data.megaData.post_content ) == <?php echo $block->ID;?>) ? 'selected="selected"' : '' }}><?php esc_html_e( $block->post_title) ?></option>
                    <?php
                        endforeach;
                    endif;?>
				</select>
			</label>
		</span>
    </p>
	<div id="fmm-mega-content" class="fmm-mega-content" {{ (parseInt( data.megaData.post_content ) > 0 || (parseInt( data.megaData.mega ) == 0)) ? "style=display:none":""}}>
		<#
		var items = _.filter( data.children, function( item ) {
			return item.subDepth == 0;
		} );
		#>
		<# _.each( items, function( item, index ) { #>

			<div class="fmm-submenu-column" data-width="{{ item.megaData.width }}">
				<ul>
					<li class="menu-item menu-item-depth-{{ item.subDepth }}">
						<# if ( item.megaData.icon ) { #>
						<i class="{{ item.megaData.icon }}"></i>
						<# } #>
						{{{ item.data['menu-item-title'] }}}
						<# if ( item.subDepth == 0 ) { #>
						<span class="fmm-column-handle fmm-resizable-e"><i class="dashicons dashicons-arrow-left-alt2"></i></span>
						<span class="fmm-column-width-label"></span>
						<span class="fmm-column-handle fmm-resizable-w"><i class="dashicons dashicons-arrow-right-alt2"></i></span>
						<input type="hidden" name="{{ fmm.getFieldName( 'width', item.data['menu-item-db-id'] ) }}" value="{{ item.megaData.width }}" class="menu-item-width">
						<# } #>
					</li>
				</ul>
			</div>

		<# } ) #>
	</div>
</div>
