<?php
/**
 * Moderation settings template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // return if called directly
}

$array_users = enjoyinstagram()->get_users();

?>

	<div class="grid grid-pad">
		<div class="col-1-2 mobile-col-1-2">
			<h2><?php _ex( 'Moderation', 'Moderation setting tab title', 'enjoy-instagram' ); ?></h2>
		</div>
	</div>

	<div class="notice notice-warning">
		<p><?php _e( 'The Moderation Panel is aviable for premium user only.', 'enjoy-instagram' ); ?></p>
		<p>
			<?php
			echo sprintf(
				// translators: Notice box inside the moderation panel
				_x( '<a href="%s" target="_blank">Upgrade to the Premium</a> version to unlock the Moderation Panel.', 'Notice box inside the moderation panel', 'enjoy-instagram' ),
				ei_admin()->get_premium_url(
					[
						'utm_source'   => 'plugin-free',
						'utm_campaign' => 'enjoy-instagram',
					]
				)
			);
			?>
		</p>
	</div>

	<hr/>
	<div class="grid grid-pad ei-premium">
		<div class="col-2-12 mobile-col-1-1">
			<div class="enin-content-title">
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/moderation.png">'; ?>
			</div>
		</div>
		<div class="col-10-12 mobile-col-1-1">
			<div class="grid grid-pad">
				<div class="enin-large-header">
					<?php _e( 'General Settings for Moderation Panel:', 'enjoy-instagram' ); ?>
				</div>
				<div class="col-4-12 mobile-col-1-1">
					<label for="autoreload_moderate_panel">
						<?php _ex( 'AutoRefresh Moderation Panel', 'option label', 'enjoy-instagram' ); ?>
					</label><br>
					<select name="autoreload_moderate_panel" class="ei_sel" id="autoreload_moderate_panel">
						<option value="true" <?php selected( 'true', get_option( 'autoreload_moderate_panel' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
						<option value="false" <?php selected( 'false', get_option( 'autoreload_moderate_panel' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
					</select>
				</div>
				<div class="col-4-12 mobile-col-1-1">
					<label for="autoreload_moderate_panel_value">
						<?php _ex( 'Timeout AutoRefresh (ms)', 'option label', 'enjoy-instagram' ); ?>:
					</label><br>
					<input type="number" name="autoreload_moderate_panel_value" id="autoreload_moderate_panel_value" value="<?php echo get_option( 'autoreload_moderate_panel_value' ); ?>" class="ei_sel"><br />
				</div>
			</div>
			<div class="grid grid-pad">
				<input type="submit" class="button-primary" id="button_enjoyinstagram_advanced_autoreload" name="button_enjoyinstagram_advanced_autoreload"
					value="<?php _e( 'Save Moderation Panel Settings', 'enjoy-instagram' ); ?>">
				<br><hr />
			</div>
			<div class="grid grid-pad">
				<p><?php _e( 'If your latest media are not visible click the button below to manually sync moderation panel.', 'enjoy-instagram' ); ?></p>
				<a href="<?php echo add_query_arg( 'sync_latest_media', '1' ); ?>" class="button-primary"><?php _e( 'Update Moderation Panel', 'enjoy-instagram' ); ?></a>
				<br><hr>
			</div>
			<div class="grid grid-pad">
				<div class="col-6-12 mobile-col-1-1">
					<div class="enin-content">
						<div class="enin-large-header">
							<?php _e( 'Apply moderation to a linked profile', 'enjoy-instagram' ); ?>:
						</div>
						<?php
						if ( is_array( $array_users ) && count( $array_users ) > 0 ) :
							foreach ( $array_users as $userkey => $user ) :
								if ( ! $userkey ) {
									continue;
								}

								?>
								<div class="acco-block" style="margin-bottom:0px;" >
									<div class="acco-1-2">
										<div class="ei_settings_float_block">
											<input type="checkbox" name="users_moderate[]" class="users_moderator" id="users_moderate_<?php echo $userkey; ?>" value="<?php echo $userkey; ?>"
											<label for="users_moderate_<?php echo $userkey; ?>" ><b><?php echo $user['business'] ? $user['full_name'] : $user['username']; ?></b></label>
										</div>
									</div>
									<div class="acco-1-2">
										<div class="ei_settings_float_block"></div>
									</div>
								</div>
								<?php
							endforeach;
							endif;
						?>
						<br />
						<i><?php _e( 'choose user\'s images to apply moderation (link).', 'enjoy-instagram' ); ?></i>
					</div>
				</div>
				<div class="col-6-12 mobile-col-1-1">
					<div class="enin-content">
						<div class="enin-large-header">
							<?php _e( 'Apply moderation to a single Hashtag', 'enjoy-instagram' ); ?>:
						</div>
						<div id="dynamic_pos"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="grid grid-pad" style="text-align:right;">
			<div class="col-1-1 mobile-col-1-1">
				<input type="submit" class="button-primary" style="display:none;" id="button_enjoyinstagram_advanced" name="button_enjoyinstagram_advanced"
					value="<?php _e( 'Save Hashtag', 'enjoy-instagram' ); ?>"/>
			</div>
		</div>
	</div>
	<hr/>

<div id="load_moderation_subpanel" class="ei-premium" >
	<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/moderation-panel.png" style="width:100%">'; ?>
</div>

