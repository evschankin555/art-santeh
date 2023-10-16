<# var itemId = data.data['menu-item-db-id']; #>
<div id="fmm-panel-icon" class="fmm-panel-icon fmm-panel">
    <p class="icon-image">
        <label><?php esc_html_e( 'Icon', 'fami-framework' ) ?></label><br>
        <span class="icon-preview">
			<# if ( data.megaData.icon.image ) { #>
				<img src="{{ data.megaData.icon.image }}">
			<# } #>
		</span>
        <button type="button" class="button remove-button <# if ( ! data.megaData.icon.image ) { print( 'hidden' ) } #>"><?php esc_html_e( 'Remove', 'fami-framework' ) ?></button>
        <button type="button" class="button upload-button" id="icon_image-button"><?php esc_html_e( 'Select Icon', 'fami-framework' ) ?></button>
        <input type="hidden" name="{{ fmm.getFieldName( 'icon.image', itemId ) }}" value="{{ data.megaData.icon.image }}">
    </p>
</div>
