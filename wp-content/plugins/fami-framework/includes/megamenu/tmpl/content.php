<?php
global $wp_widget_factory;
?>
<div id="fmm-panel-content" class="fmm-panel-content fmm-panel">
	<p>
		<textarea name="{{ fmm.getFieldName( 'content', data.data['menu-item-db-id'] ) }}" class="widefat" rows="20" contenteditable="true">{{{ data.megaData.content }}}</textarea>
	</p>
	<p class="description"><?php esc_html_e( 'Allow HTML and Shortcodes', 'fami-framework' ) ?></p>
	<p class="description"><?php esc_html_e( 'Tip: Build your content inside a page with visual page builder then copy generated content here.', 'fami-framework' ) ?></p>
</div>
