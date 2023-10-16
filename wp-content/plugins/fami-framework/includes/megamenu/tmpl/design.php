<div id="fmm-panel-design" class="fmm-panel-design fmm-panel">
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Border', 'fami-framework' ) ?></th>
			<td>
				<fieldset>
					<label>
						<input type="checkbox" name="{{ fmm.getFieldName( 'border.left', data.data['menu-item-db-id'] ) }}" value="1" {{ parseInt( data.megaData.border.left ) ? 'checked="checked"' : '' }}>
						<?php esc_html_e( 'Border Left', 'fami-framework' ) ?>
					</label>
				</fieldset>
			</td>
		</tr>
	</table>
</div>
