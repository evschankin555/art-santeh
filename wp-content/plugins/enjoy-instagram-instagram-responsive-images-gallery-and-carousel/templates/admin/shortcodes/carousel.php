<?php
/**
 * Carousel settings template
 *
 * @package EnjoyInstagram
 */

/** @var array $users */
/** @var array $appearance_settings */

?>

<form method="post" action="options.php" class="display_content_tabs">
	<?php $settings = enjoyinstagram()->settings->get_carousel_settings(); ?>
	<?php settings_fields( 'enjoyinstagram_carousel_options' ); ?>
	<div id="enin-tab-content1" class="enin-tab-content">
		<div class="label_moderate acco-block ei-premium">
			<div class="acco-1-4" style="width: 100px;">
				<div class="ei_settings_float_block">
					<img src="<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/moderation.png'; ?>" alt="moderation" width="80">
				</div>
			</div>
			<div class="acco-1-4">
				<div class="ei_settings_float_block">
					<label for="enjoyinstagram_carousel_moderate">
						<?php _e( 'Moderate', 'enjoy-instagram' ); ?>:
					</label>
					<br/>
					<select name="enjoyinstagram_carousel_moderate"
						id="enjoyinstagram_carousel_moderate" class="ei_sel">
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
								<input type="radio" name="enjoyinstagram_carousel_user_hashtag" id="label_enjoyinstagram_user_or_hashtag" value="user" <?php checked( 'user', $settings['user_hashtag'] ); ?>>
								<label for="label_enjoyinstagram_user_or_hashtag"><?php _ex( 'of Your Profile', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<input type="radio" name="enjoyinstagram_carousel_user_hashtag" id="label_enjoyinstagram_user_or_hashtag_1" value="hashtag" <?php checked( 'hashtag', $settings['user_hashtag'] ); ?>>
								<label for="label_enjoyinstagram_user_or_hashtag_1"><?php _ex( 'by Hashtag', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<?php if ( enjoyinstagram()->has_business_user() ) : ?>
									<input type="radio" name="enjoyinstagram_carousel_user_hashtag" id="label_enjoyinstagram_user_or_hashtag_2"
										value="public_hashtag" <?php checked( 'public_hashtag', $settings['user_hashtag'] ); ?>>
									<label for="label_enjoyinstagram_user_or_hashtag_2">
										<?php _ex( 'by Public Hashtag', 'option label', 'enjoy-instagram' ); ?>
									</label>
								<?php endif; ?>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<div id="enjoyinstagram_carousel_user_hashtag" class="desc" data-deps="enjoyinstagram_carousel_user_hashtag"
									data-deps_values="user,false">
									<label for="enjoyinstagram_user_carousel"></label><select id="enjoyinstagram_user_carousel" name="enjoyinstagram_carousel_user">
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

								<div id="enjoyinstagram_user_or_hashtag_hashtag_carousel"
									class="desc"
									data-deps="enjoyinstagram_carousel_user_hashtag"
									data-deps_values="hashtag|public_hashtag,false">
									<label for="enjoyinstagram_hashtag_popup_carousel"></label><input type="text"
										id="enjoyinstagram_hashtag_popup_carousel"
										name="enjoyinstagram_carousel_hashtag"
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
				<div class="acco-block-opener"><?php _e( 'Carousel Settings', 'enjoy-instagram' ); ?></div>
				<div class="acco-block-container">
					<div class="acco-block">
						<div class="enin-medium-header"><?php _ex( 'GENERAL OPTIONS', 'option section title', 'enjoy-instagram' ); ?></div>
						<div class="acco-1-4">
							<div class="ei_block">
								<div class="ei_settings_float_block ei_fixed">
									<label for="enjoyinstagram_carousel_items_number">
										<?php _ex( 'Images displayed at a time:', 'widget option label', 'enjoy-instagram' ); ?>
									</label>
									<br/>
									<select name="enjoyinstagram_carousel_items_number" class="ei_sel"
										id="enjoyinstagram_carousel_items_number">
										<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
											<option value="<?php echo $i; ?>" <?php selected( $i, $settings['items_number'] ); ?>>
												<?php echo '&nbsp;' . $i; ?>
											</option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="acco-1-4 ei-premium">
							<div class="ei_block">
								<div class="ei_settings_float_block ei_fixed">
									<label for="show_resolution_carousel">
										<?php _ex( 'Show different number of images for different screen resolution ?', 'option label', 'enjoy-instagram' ); ?>
									</label>
									<br/><input type="checkbox" name="show_resolution_carousel"
										id="show_resolution_carousel" value="on">
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
								<?php _ex( 'Link Image to', 'option label', 'enjoy-instagram' ); ?>:
							</div>
						</div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<input type="radio" id="enjoyinstagram_carousel_link_1" name="enjoyinstagram_carousel_link"
									value="swipebox" <?php checked( 'swipebox', $settings['link'] ); ?>>
								<label for="enjoyinstagram_carousel_link_1"><?php _ex( 'LightBox Effect', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<input type="radio" id="enjoyinstagram_carousel_link_2" name="enjoyinstagram_carousel_link"
									value="instagram" <?php checked( 'instagram', $settings['link'] ); ?>>
								<label for="enjoyinstagram_carousel_link_2"><?php _ex( 'Instagram', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<input type="radio" id="enjoyinstagram_carousel_link_3" name="enjoyinstagram_carousel_link"
									value="nolink" <?php checked( 'nolink', $settings['link'] ); ?>>
								<label for="enjoyinstagram_carousel_link_3"><?php _ex( 'No Link', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<input type="radio" id="enjoyinstagram_carousel_link_4" name="enjoyinstagram_carousel_link"
									value="altro" <?php checked( 'altro', $settings['link'] ); ?>>
								<label for="enjoyinstagram_carousel_link_4"><?php _ex( 'Custom', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<input type="text" name="enjoyinstagram_carousel_link_altro"
									data-deps="enjoyinstagram_carousel_link"
									data-deps_values="altro,false"
									value="<?php echo $settings['link_altro']; ?>"
									id="enjoyinstagram_carousel_link_altro"
								>
							</div>
						</div>

					</div>

					<div class="ei-premium">
						<div class="acco-block">
							<div class="enin-medium-header"><?php _ex( 'CAROUSEL NAVIGATION', 'option section title', 'enjoy-instagram' ); ?></div>
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label
										for="enjoyinstagram_carousel_navigation">
										<?php _ex( 'Navigation buttons', 'option label', 'enjoy-instagram' ); ?>:
									</label><br>
									<select name="enjoyinstagram_carousel_navigation"
										id="enjoyinstagram_carousel_navigation" class="ei_sel">
										<option value="true" <?php selected( 'true', $settings['navigation'] ); ?>>
											<?php _e( 'Yes', 'enjoy-instagram' ); ?>
										</option>
										<option value="false" <?php selected( 'false', $settings['navigation'] ); ?>>
											<?php _e( 'No', 'enjoy-instagram' ); ?>
										</option>
									</select>
								</div>
							</div>
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label
										for="enjoyinstagram_carousel_navigation_prev">
										<?php _ex( 'Prev Button Text', 'option label', 'enjoy-instagram' ); ?>:
									</label>
									<br/>
									<input type="text"
										value="<?php echo $settings['navigation_prev']; ?>"
										name="enjoyinstagram_carousel_navigation_prev"
										id="enjoyinstagram_carousel_navigation_prev">
								</div>
							</div>
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label
										for="enjoyinstagram_carousel_navigation_next">
										<?php _ex( 'Next Button Text', 'option label', 'enjoy-instagram' ); ?>:
									</label>
									<br/>
									<input type="text"
										value="<?php echo $settings['navigation_next']; ?>"
										name="enjoyinstagram_carousel_navigation_next"
										id="enjoyinstagram_carousel_navigation_next">
								</div>
							</div>
						</div>
						<div class="acco-block">
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label
										for="enjoyinstagram_carousel_slidespeed">
										<?php _ex( 'Slide Speed (milliseconds)', 'option label', 'enjoy-instagram' ); ?>:
									</label><br>
									<input type="number" name="enjoyinstagram_carousel_slidespeed"
										value="<?php echo $settings['slidespeed']; ?>"
										class="ei_sel" id="enjoyinstagram_carousel_slidespeed">
								</div>
							</div>
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label for="enjoyinstagram_carousel_dots">
										<?php _ex( 'Dots', 'option label', 'enjoy-instagram' ); ?>:
									</label><br/>
									<select name="enjoyinstagram_carousel_dots" class="ei_sel"
										id="enjoyinstagram_carousel_dots">
										<option value="true" <?php selected( 'true', $settings['dots'] ); ?>>
											<?php _e( 'Yes', 'enjoy-instagram' ); ?>
										</option>
										<option value="false" <?php selected( 'false', $settings['dots'] ); ?>>
											<?php _e( 'No', 'enjoy-instagram' ); ?>
										</option>
									</select>
								</div>
							</div>
							<div class="acco-1-3">
								<div class="ei_settings_float_block">
									<label for="enjoyinstagram_carousel_loop">
										<?php _ex( 'Loop', 'option label', 'enjoy-instagram' ); ?>:
									</label><br/>
									<select name="enjoyinstagram_carousel_loop" class="ei_sel"
										id="enjoyinstagram_carousel_loop">
										<option value="true" <?php selected( 'true', $settings['loop'] ); ?>>
											<?php _e( 'Yes', 'enjoy-instagram' ); ?>
										</option>
										<option value="false" <?php selected( 'false', $settings['loop'] ); ?>>
											<?php _e( 'No', 'enjoy-instagram' ); ?>
										</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="acco-block zebra ei-premium">
						<div class="enin-medium-header">
							<?php _ex( 'AUTOPLAY', 'option section title', 'enjoy-instagram' ); ?>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_carousel_autoplay">
									<?php _ex( 'AutoPlay', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_carousel_autoplay" class="ei_sel"
									id="enjoyinstagram_carousel_autoplay">
									<option value="true" <?php selected( 'true', $settings['autoplay'] ); ?>>
										<?php _e( 'Yes', 'enjoy-instagram' ); ?>
									</option>
									<option value="false" <?php selected( 'false', $settings['autoplay'] ); ?>>
										<?php _e( 'No', 'enjoy-instagram' ); ?>
									</option>
								</select>
							</div>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label
									for="enjoyinstagram_carousel_autoplay_timeout">
									<?php _ex( 'Timeout Autoplay', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<input type="number" name="enjoyinstagram_carousel_autoplay_timeout"
									id="enjoyinstagram_carousel_autoplay_timeout"
									value="<?php echo $settings['autoplay_timeout']; ?>"
									class="ei_sel"><br/>
							</div>
						</div>
					</div>

					<div class="acco-block ei-premium">
						<div class="enin-medium-header">
							<?php _ex( 'LIGHTBOX OPTIONS', 'option section title', 'enjoy-instagram' ); ?>
						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_carousel_lightbox_caption">
									<?php _ex( 'Lightbox Caption', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_carousel_lightbox_caption" class="ei_sel"
									id="enjoyinstagram_carousel_lightbox_caption">
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
								<label for="enjoyinstagram_carousel_lightbox_style">
									<?php _ex( 'Lightbox Style', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<select name="enjoyinstagram_carousel_lightbox_style" class="ei_sel"
									id="enjoyinstagram_carousel_lightbox_style">
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
						<div class="enin-medium-header"><?php _ex( 'AUTORELOAD', 'option section title', 'enjoy-instagram' ); ?>

						</div>
						<div class="acco-1-3">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_carousel_autoreload">
									<?php _ex( 'Autoreload', 'option label', 'enjoy-instagram' ); ?>
								</label><br/>
								<select name="enjoyinstagram_carousel_autoreload" class="ei_sel"
									id="enjoyinstagram_carousel_autoreload">
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
								<label
									for="enjoyinstagram_carousel_autoreload_value">
									<?php _ex( 'Time to Reload (milliseconds)', 'option label', 'enjoy-instagram' ); ?>:
								</label><br/>
								<input type="number" name="enjoyinstagram_carousel_autoreload_value"
									id="enjoyinstagram_carousel_autoreload_value"
									value="<?php echo $settings['autoreload_value']; ?>"
									class="ei_sel"><br/>
							</div>
						</div>
					</div>
					<div class="acco-block">
						<div class="enin-medium-header"><?php _ex( 'STYLE', 'option section title', 'enjoy-instagram' ); ?></div>
						<div class="acco-1-4">
							<div class="ei_settings_float_block">
								<label for="enjoyinstagram_carousel_theme"><?php _ex( 'Select template', 'option label', 'enjoy-instagram' ); ?></label><br/>
								<select name="enjoyinstagram_carousel_theme" class="ei_sel"
									id="enjoyinstagram_carousel_theme">
									<?php foreach ( $appearance_settings['templates'] as $value => $label ) : ?>
										<option
											value="<?php echo $value; ?>"
											<?php selected( $value, $settings['theme'] ); ?>>
											<?php echo $label; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="acco-1-4 ei-premium">
							<div class="ei_settings_float_block">
								<label
									for="enjoyinstagram_carousel_margin"><?php _ex( 'Margin of each Image', 'option label', 'enjoy-instagram' ); ?>:</label><br/>
								<input type="number" name="enjoyinstagram_carousel_margin"
									id="enjoyinstagram_carousel_margin"
									value="<?php echo $settings['margin']; ?>"
									class="ei_sel"><br/>
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
