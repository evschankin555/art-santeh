<?php
/**
 * Grid settings template
 *
 * @package EnjoyInstagram
 */

/** @var array $users */
/** @var array $appearance_settings */

?>

<form method="post" action="options.php" class="display_content_tabs">
	<?php $settings = enjoyinstagram()->settings->get_grid_settings(); ?>
	<?php settings_fields( 'enjoyinstagram_grid_options' ); ?>
	<div id="enin-tab-content2" class="enin-tab-content">
		<div class="label_moderate acco-block ei-premium">
			<div class="acco-1-4" style="width: 100px;">
				<div class="ei_settings_float_block">
					<img src="<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/moderation.png'; ?>"
						width="80" alt="moderation">
				</div>
			</div>
			<div class="acco-1-4">
				<div class="ei_settings_float_block">
					<label for="enjoyinstagram_grid_moderate"><?php _e( 'Moderate', 'enjoy-instagram' ); ?>:</label><br/>
					<select name="enjoyinstagram_grid_moderate" id="enjoyinstagram_grid_moderate" class="ei_sel">
						<option value="true"><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
						<option value="false" selected><?php _e( 'No', 'enjoy-instagram' ); ?></option>
					</select>
				</div>
			</div>
		</div>
		<div class="shortcode-options">
			<div class="into_display_content_tabs">
				<div class="acco-block-opener"><?php _e( 'Inclusion Mode', 'enjoy-instagram' ); ?></div>
				<div class="acco-block-container">
					<div class="acco-block">
						<div class="enin-medium-header"><?php _ex( 'Show Pics', 'option label', 'enjoy-instagram' ); ?></div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<input type="radio" name="enjoyinstagram_grid_user_hashtag" id="enjoyinstagram_user_or_hashtag_grid_1" value="user" <?php checked( 'user', $settings['user_hashtag'] ); ?>>
								<label for="enjoyinstagram_user_or_hashtag_grid_1">
									<?php _ex( 'of Your Profile', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<input type="radio" name="enjoyinstagram_grid_user_hashtag" id="enjoyinstagram_user_or_hashtag_grid_2" value="hashtag" <?php checked( 'hashtag', $settings['user_hashtag'] ); ?>>
								<label for="enjoyinstagram_user_or_hashtag_grid_2">
									<?php _ex( 'by Hashtag', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<?php if ( enjoyinstagram()->has_business_user() ) : ?>
									<input type="radio" name="enjoyinstagram_grid_user_hashtag"
										id="enjoyinstagram_user_or_hashtag_grid_3"
										value="public_hashtag" <?php checked( 'public_hashtag', $settings['user_hashtag'] ); ?>>
									<label
										for="enjoyinstagram_user_or_hashtag_grid_3">
										<?php _ex( 'by Public Hashtag', 'option label', 'enjoy-instagram' ); ?>
									</label><br/>
								<?php endif; ?>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<div id="enjoyinstagram_grid_user_hashtag" class="desc" data-deps="enjoyinstagram_grid_user_hashtag"
									data-deps_values="user,false">
									<label for="enjoyinstagram_user_grid"></label>
									<select id="enjoyinstagram_user_grid" name="enjoyinstagram_grid_user">
										<?php foreach ( $users as $user ) : ?>
											<?php if ( isset( $user['username'] ) && $user['username'] ) : ?>
												<option value="<?php echo $user['username']; ?>"
													data-api="<?php echo $user['business'] ? 'graph' : 'basic-display'; ?>">
													<?php echo $user['username']; ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>

								<div id="enjoyinstagram_user_or_hashtag_hashtag_grid"
									class="desc"
									data-deps="enjoyinstagram_grid_user_hashtag"
									data-deps_values="hashtag|public_hashtag,false">
									<label for="enjoyinstagram_hashtag_popup_grid"></label>
									<input type="text"
										id="enjoyinstagram_hashtag_popup_grid"
										name="enjoyinstagram_grid_hashtag"
										value="<?php echo $settings['hashtag']; ?>"/><br/>
									<span>
														<?php _e( 'Insert hashtags without \'#\' separated by comma', 'enjoy-instagram' ); ?>
													</span>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="into_display_content_tabs closed">
				<div class="acco-block-opener">
					<?php _ex( 'Grid settings', 'option section title', 'enjoy-instagram' ); ?>
				</div>

				<div class="acco-block-container">
					<div class="acco-block">
						<div class="enin-medium-header">
							<?php _ex( 'GENERAL OPTIONS', 'option section title', 'enjoy-instagram' ); ?>:
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<?php _e( 'Number of Images ( COLS x ROWS )', 'enjoy-instagram' ); ?>
							</div>
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_cols"><?php _ex( 'Number of Columns:', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<select name="enjoyinstagram_grid_cols" id="enjoyinstagram_grid_cols"
									class="ei_sel">
									<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
										<option value="<?php echo $i; ?>" <?php selected( $i, $settings['cols'] ); ?>>
											<?php echo '&nbsp;' . $i; ?>
										</option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_rows"><?php _ex( 'Number of Rows:', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<select name="enjoyinstagram_grid_rows" id="enjoyinstagram_grid_rows"
									class="ei_sel">
									<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
										<option value="<?php echo $i; ?>" <?php selected( $i, $settings['rows'] ); ?>>
											<?php echo '&nbsp;' . $i; ?>
										</option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<div class="acco-1-4 ei-premium">
							<div class="ei_block">
								<div class="ei_settings_float_block ei_fixed">
									<label for="show_resolution_carousel">
										<?php _ex( 'Show different number of images for different screen resolution ?', 'option label', 'enjoy-instagram' ); ?>
									</label>
									<br/><input type="checkbox" name="show_resolution_grid"
										id="show_resolution_grid" value="on">
								</div>
							</div>
						</div>
					</div>

					<div class="acco-block zebra ei-premium">
						<div class="enin-medium-header">
							<?php _ex( 'WHEN YOU CLICK ON AN IMAGE', 'option section title', 'enjoy-instagram' ); ?>
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<?php _ex( 'Link Image to:', 'option label', 'enjoy-instagram' ); ?>
							</div>
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<input type="radio" id="enjoyinstagram_grid_link_1"
									name="enjoyinstagram_grid_link"
									value="swipebox" <?php checked( 'swipebox', $settings['link'] ); ?>>
								<label for="enjoyinstagram_grid_link_1">
									<?php _ex( 'LightBox Effect', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<input type="radio" id="enjoyinstagram_grid_link_2"
									name="enjoyinstagram_grid_link"
									value="instagram" <?php checked( 'instagram', $settings['link'] ); ?>>
								<label for="enjoyinstagram_grid_link_2">
									<?php _ex( 'Instagram', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<input type="radio" id="enjoyinstagram_grid_link_3"
									name="enjoyinstagram_grid_link"
									value="nolink" <?php checked( 'nolink', $settings['link'] ); ?>>
								<label for="enjoyinstagram_grid_link_3">
									<?php _ex( 'No Link', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<input type="radio" id="enjoyinstagram_grid_link_4"
									name="enjoyinstagram_grid_link"
									value="altro" <?php checked( 'altro', $settings['link'] ); ?>>
								<label for="enjoyinstagram_grid_link_4">
									<?php _ex( 'Custom', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<input type="text" name="enjoyinstagram_grid_link_altro"
									data-deps="enjoyinstagram_grid_link"
									data-deps_values="altro,false"
									value="<?php echo $settings['link']; ?>"
									id="enjoyinstagram_grid_link_altro">
							</div>
						</div>
					</div>
					<div class="acco-block ei-premium">
						<div class="enin-medium-header"><?php _ex( 'GRID OPTIONS', 'option section title', 'enjoy-instagram' ); ?></div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_step">
									<?php _ex( 'Number of items that are replaced at the same time', 'option label', 'enjoy-instagram' ); ?>:
								</label><br>
								<select name="enjoyinstagram_grid_step" id="enjoyinstagram_grid_step"
									class="ei_sel">
									<option value="random" <?php selected( 'random', $settings['step'] ); ?>>
										<?php _e( 'Random', 'enjoy-instagram' ); ?>
									</option>
									<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
										<option value="<?php echo $i; ?>" <?php selected( $i, $settings['step'] ); ?>>
											<?php echo '&nbsp;' . $i; ?>
										</option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_interval">
									<?php _ex( 'Interval the images will be replaced (milliseconds):', 'option label', 'enjoy-instagram' ); ?>
								</label><br>
								<input type="number" min="300"
									value="<?php echo $settings['interval']; ?>"
									name="enjoyinstagram_grid_interval" class="ei_sel"
									id="enjoyinstagram_grid_interval">
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_onhover">
									<?php _ex( 'Images will switch when mouse on hover:', 'option label', 'enjoy-instagram' ); ?>
								</label><br>
								<select name="enjoyinstagram_grid_onhover" class="ei_sel"
									id="enjoyinstagram_grid_onhover">
									<option value="true" <?php selected( 'true', $settings['onhover'] ); ?>>
										<?php _e( 'Yes', 'enjoy-instagram' ); ?>
									</option>
									<option value="false" <?php selected( 'false', $settings['onhover'] ); ?>>
										<?php _e( 'No', 'enjoy-instagram' ); ?>
									</option>
								</select>
							</div>
						</div>
					</div>
					<div class="acco-block zebra ei-premium">
						<div class="enin-medium-header"><?php _ex( 'ANIMATION', 'option section title', 'enjoy-instagram' ); ?>:
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_animation_speed">
									<?php _ex( 'Animation Speed ( milliseconds )', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<input type="number"
									value="<?php echo $settings['animation_speed']; ?>"
									name="enjoyinstagram_grid_animation_speed"
									class="ei_sel"
									id="enjoyinstagram_grid_animation_speed">
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_animation">
									<?php _ex( 'Animate Type', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_grid_animation"
									id="enjoyinstagram_grid_animation" class="ei_sel">
									<?php foreach ( $appearance_settings['grid_animation'] as $key => $label ) : ?>
										<option value="<?php echo $key; ?>" <?php selected( $key, $settings['animation'] ); ?>>
											<?php echo $label; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class=" acco-block ei-premium">
						<div class="enin-medium-header"><?php _ex( 'LIGHTBOX OPTIONS', 'option section title', 'enjoy-instagram' ); ?>:
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_lightbox_caption">
									<?php _ex( 'Lightbox Caption', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_grid_lightbox_caption" class="ei_sel"
									id="enjoyinstagram_grid_lightbox_caption">
									<option value="yes" <?php selected( 'yes', $settings['lightbox_caption'] ); ?>>
										<?php _e( 'Yes', 'enjoy-instagram' ); ?>
									</option>
									<option value="no" <?php selected( 'no', $settings['lightbox_caption'] ); ?>>
										<?php _e( 'No', 'enjoy-instagram' ); ?>
									</option>
								</select>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_lightbox_style">
									<?php _ex( 'Lightbox Style', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_grid_lightbox_style" class="ei_sel"
									id="enjoyinstagram_grid_lightbox_style">
									<option value="default" <?php selected( 'default', $settings['lightbox_style_default'] ); ?>>
										<?php _e( 'Leonardo', 'enjoy-instagram' ); ?>
									</option>
									<option value="igvertical" <?php selected( 'igvertical', $settings['lightbox_style_default'] ); ?>>
										<?php _e( 'Michelangelo', 'enjoy-instagram' ); ?>
									</option>
								</select>
							</div>
						</div>
					</div>
					<div class="acco-block zebra ei-premium">
						<div class="enin-medium-header"><?php _ex( 'AUTORELOAD', 'option section title', 'enjoy-instagram' ); ?>:
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_autoreload">
									<?php _ex( 'Autoreload', 'option label', 'enjoy-instagram' ); ?>:
								</label><br>
								<select name="enjoyinstagram_grid_autoreload" class="ei_sel"
									id="enjoyinstagram_grid_autoreload">
									<option value="true" <?php selected( 'true', $settings['autoreload'] ); ?>>
										<?php _e( 'Yes', 'enjoy-instagram' ); ?>
									</option>
									<option value="false" <?php selected( 'false', $settings['autoreload'] ); ?>>
										<?php _e( 'No', 'enjoy-instagram' ); ?>
									</option>
								</select>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_grid_autoreload_value">
									<?php _ex( 'Time to Reload (milliseconds)', 'option label', 'enjoy-instagram' ); ?>:
								</label><br>
								<input type="number"
									value="<?php echo $settings['autoreload_value']; ?>"
									name="enjoyinstagram_grid_autoreload_value"
									class="ei_sel"
									id="enjoyinstagram_grid_autoreload_value">
							</div>
						</div>
					</div>
					<div class="acco-block">
						<div class="enin-medium-header"><?php _ex( 'STYLE', 'option section title', 'enjoy-instagram' ); ?></div>
						<div class="acco-1-4 ei-premium">
							<div class="ei_settings_float_block">
								<div class="ei_settings_float_block">
									<label
										for="enjoyinstagram_grid_margin"><?php _ex( 'Margin of each Image', 'option label', 'enjoy-instagram' ); ?>:</label><br/>
									<input type="number" name="enjoyinstagram_grid_margin"
										id="enjoyinstagram_grid_margin"
										value="<?php echo $settings['margin']; ?>"
										class="ei_sel"><br/>
								</div>
							</div>
						</div>
					</div>
					<div class="acco-block ">
						<?php submit_button(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
