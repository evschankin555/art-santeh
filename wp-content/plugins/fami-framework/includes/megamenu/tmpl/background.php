<# var itemId = data.data['menu-item-db-id']; #>
<div id="fmm-panel-background" class="fmm-panel-background fmm-panel">
	<p class="background-image">
		<label><?php esc_html_e( 'Background Image', 'fami-framework' ) ?></label><br>
		<span class="background-image-preview">
			<# if ( data.megaData.background.image ) { #>
				<img src="{{ data.megaData.background.image }}">
			<# } #>
		</span>

		<button type="button" class="button remove-button <# if ( ! data.megaData.background.image ) { print( 'hidden' ) } #>"><?php esc_html_e( 'Remove', 'fami-framework' ) ?></button>
		<button type="button" class="button upload-button" id="background_image-button"><?php esc_html_e( 'Select Image', 'fami-framework' ) ?></button>

		<input type="hidden" name="{{ fmm.getFieldName( 'background.image', itemId ) }}" value="{{ data.megaData.background.image }}">
	</p>

	<p class="background-color">
		<label><?php esc_html_e( 'Background Color', 'fami-framework' ) ?></label><br>
		<input type="text" class="background-color-picker" name="{{ fmm.getFieldName( 'background.color', itemId ) }}" value="{{ data.megaData.background.color }}">
	</p>

	<p class="background-repeat">
		<label><?php esc_html_e( 'Background Repeat', 'fami-framework' ) ?></label><br>
		<select name="{{ fmm.getFieldName( 'background.repeat', itemId ) }}">
			<option value="no-repeat" {{ 'no-repeat' == data.megaData.background.repeat ? 'selected="selected"' : '' }}><?php esc_html_e( 'No Repeat', 'fami-framework' ) ?></option>
			<option value="repeat" {{ 'repeat' == data.megaData.background.repeat ? 'selected="selected"' : '' }}><?php esc_html_e( 'Tile', 'fami-framework' ) ?></option>
			<option value="repeat-x" {{ 'repeat-x' == data.megaData.background.repeat ? 'selected="selected"' : '' }}><?php esc_html_e( 'Tile Horizontally', 'fami-framework' ) ?></option>
			<option value="repeat-y" {{ 'repeat-y' == data.megaData.background.repeat ? 'selected="selected"' : '' }}><?php esc_html_e( 'Tile Vertically', 'fami-framework' ) ?></option>
		</select>
	</p>

	<p class="background-position background-position-x">
		<label><?php esc_html_e( 'Background Position', 'fami-framework' ) ?></label><br>

		<select name="{{ fmm.getFieldName( 'background.position.x', itemId ) }}">
			<option value="left" {{ 'left' == data.megaData.background.position.x ? 'selected="selected"' : '' }}><?php esc_html_e( 'Left', 'fami-framework' ) ?></option>
			<option value="center" {{ 'center' == data.megaData.background.position.x ? 'selected="selected"' : '' }}><?php esc_html_e( 'Center', 'fami-framework' ) ?></option>
			<option value="right" {{ 'right' == data.megaData.background.position.x ? 'selected="selected"' : '' }}><?php esc_html_e( 'Right', 'fami-framework' ) ?></option>
			<option value="custom" {{ 'custom' == data.megaData.background.position.x ? 'selected="selected"' : '' }}><?php esc_html_e( 'Custom', 'fami-framework' ) ?></option>
		</select>

		<input
			type="text"
			name="{{ fmm.getFieldName( 'background.position.custom.x', itemId ) }}"
			value="{{ data.megaData.background.position.custom.x }}"
			class="{{ 'custom' != data.megaData.background.position.x ? 'hidden' : '' }}">
	</p>

	<p class="background-position background-position-y">
		<select name="{{ fmm.getFieldName( 'background.position.y', itemId ) }}">
			<option value="top" {{ 'top' == data.megaData.background.position.y ? 'selected="selected"' : '' }}><?php esc_html_e( 'Top', 'fami-framework' ) ?></option>
			<option value="center" {{ 'center' == data.megaData.background.position.y ? 'selected="selected"' : '' }}><?php esc_html_e( 'Middle', 'fami-framework' ) ?></option>
			<option value="bottom" {{ 'bottom' == data.megaData.background.position.y ? 'selected="selected"' : '' }}><?php esc_html_e( 'Bottom', 'fami-framework' ) ?></option>
			<option value="custom" {{ 'custom' == data.megaData.background.position.y ? 'selected="selected"' : '' }}><?php esc_html_e( 'Custom', 'fami-framework' ) ?></option>
		</select>
		<input
			type="text"
			name="{{ fmm.getFieldName( 'background.position.custom.y', itemId ) }}"
			value="{{ data.megaData.background.position.custom.y }}"
			class="{{ 'custom' != data.megaData.background.position.y ? 'hidden' : '' }}">
	</p>

	<p class="background-attachment">
		<label><?php esc_html_e( 'Background Attachment', 'fami-framework' ) ?></label><br>
		<select name="{{ fmm.getFieldName( 'background.attachment', itemId ) }}">
			<option value="scroll" {{ 'scroll' == data.megaData.background.attachment ? 'selected="selected"'  : '' }}><?php esc_html_e( 'Scroll', 'fami-framework' ) ?></option>
			<option value="fixed" {{ 'fixed' == data.megaData.background.attachment ? 'selected="selected"'  : '' }}><?php esc_html_e( 'Fixed', 'fami-framework' ) ?></option>
		</select>
	</p>

	<p class="background-size">
		<label><?php esc_html_e( 'Background Size', 'fami-framework' ) ?></label><br>
		<select name="{{ fmm.getFieldName( 'background.size', itemId ) }}">
			<option value=""><?php esc_html_e( 'Default', 'fami-framework' ) ?></option>
			<option value="cover" {{ 'cover' == data.megaData.background.size ? 'selected="selected"' : '' }}><?php esc_html_e( 'Cover', 'fami-framework' ) ?></option>
			<option value="contain" {{ 'contain' == data.megaData.background.size ? 'selected="selected"' : '' }}><?php esc_html_e( 'Contain', 'fami-framework' ) ?></option>
		</select>
	</p>
</div>
