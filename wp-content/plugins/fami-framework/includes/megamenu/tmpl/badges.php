<div id="fmm-panel-badges" class="fmm-panel-badges fmm-panel">
    <table class="form-table">
        <tr>
            <th scope="row"><?php esc_html_e( 'Badge Title', 'fami-framework' ) ?></th>
            <td>
                <input type="text" name="{{ fmm.getFieldName( 'badge_title', data.data['menu-item-db-id'] ) }}" value="{{ data.megaData.badge_title }}">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Badge Text Color', 'fami-framework' ) ?></th>
            <td>
                <label>
                    <input type="text" class="badge-color-picker" name="{{ fmm.getFieldName( 'badge_color', data.data['menu-item-db-id'] ) }}" value="{{ data.megaData.badge_color }}">
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Badge Background Color', 'fami-framework' ) ?></th>
            <td>
                <label>
                    <input type="text" class="badge-color-picker" name="{{ fmm.getFieldName( 'badge_bg_color', data.data['menu-item-db-id'] ) }}" value="{{ data.megaData.badge_bg_color }}">
                </label>
            </td>
        </tr>
    </table>
</div>
