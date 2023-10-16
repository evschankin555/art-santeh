<div class="display_content_tabs">
	<div class="into_display_content_tabs">
		<div class="acco-block-container">
			<div class="acco-block">
				<p><?php _e( 'This layout is available for premium user only', 'enjoy-instagram' ); ?></p>
				<p>
					<?php
					echo sprintf(
					// translators: Notice box inside the moderation panel
						_x( '<a href="%s" target="_blank">Upgrade to the Premium</a> version to unlock this layout.', 'Notice box inside the layout panel', 'enjoy-instagram' ),
						ei_admin()->get_premium_url(
							[
								'utm_source'   => 'plugin-free',
								'utm_campaign' => 'enjoy-instagram',
							]
						)
					);
					?>
				</p>
				<div class="ei-premium">
					<img style="max-width: 100%; height: auto;" src="<?php echo ENJOYINSTAGRAM_ASSETS_URL; ?>/images/album-preview.jpg"/>
				</div>
			</div>
		</div>
	</div>
</div>
