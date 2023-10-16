<div id="fmm-panel-settings" class="fmm-panel-settings fmm-panel">
	<# if ( data.depth == 1 ) { #>

		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Hide label', 'fami-framework' ) ?></th>
				<td>
					<label>
						<input type="checkbox" name="{{ fmm.getFieldName( 'hide_text', data.data['menu-item-db-id'] ) }}" value="1" {{ parseInt( data.megaData.hide_text ) ? 'checked="checked"' : '' }}>
						<?php esc_html_e( 'Hide menu item text', 'fami-framework' ) ?>
					</label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Disable link', 'fami-framework' ) ?></th>
				<td>
					<label>
						<input type="checkbox" name="{{ fmm.getFieldName( 'disable_link', data.data['menu-item-db-id'] ) }}" value="1" {{ parseInt( data.megaData.disable_link ) ? 'checked="checked"' : '' }}>
						<?php esc_html_e( 'Disable menu item link', 'fami-framework' ) ?>
					</label>
				</td>
			</tr>
		</table>

	<# } #>
</div>
