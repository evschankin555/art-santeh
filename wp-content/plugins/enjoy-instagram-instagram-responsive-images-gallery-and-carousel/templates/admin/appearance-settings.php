<?php
/**
 * Appearance settings template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // return if called directly
}

$settings = enjoyinstagram_get_appearance_settings();

?>

<form id="enjoyinstagram-appearance-settings" method="post" action="options.php" novalidate>
	<?php settings_fields( 'enjoyinstagram_options_default_group' ); ?>


	<div class="grid grid-pad">
		<div class="col-1-2 mobile-col-1-2">
			<h2><?php _e( 'Set default appareance setting', 'enjoy-instagram' ); ?></h2>
		</div>
		<div class="col-1-2 mobile-col-1-2" style="text-align:right;">
			<input type="submit" class="button-primary" id="button_enjoyinstagram_advanced_default" name="button_enjoyinstagram_advanced_default" value="<?php _e( 'Save Settings', 'enjoy-instagram' ); ?>"/>
		</div>
	</div>
	<hr/>
	<!-- CSS and JS defer -->
	<div class="grid grid-pad">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_code_opt" class="highlighted"><?php _e( 'JS and CSS defer', 'enjoy-instagram' ); ?></label>
			</div>
		</div>
		<div class="col-1-2 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'JS and CSS defer toggle', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<?php _e( 'The plugin is configured to optimize page loading by deferring CSS and JS files.', 'enjoy-instagram' ); ?><br/>
						<?php _e( 'However, this may cause issues with thirdy party plugins or themes. If this happens, please disable this option.', 'enjoy-instagram' ); ?>
						<hr/>
						<input name="enjoyinstagram_code_opt" id="enjoyinstagram_code_opt" type="checkbox" value="true" <?php checked( 'true', get_option( 'enjoyinstagram_code_opt' ) ); ?>>
						<label for="enjoyinstagram_code_opt"><?php _ex( 'Defer JS and CSS loading', 'option label', 'enjoy-instagram' ); ?></label>
						<hr/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- images captured -->
	<div class="grid grid-pad <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_images_captured" class="highlighted"><?php _e( 'Instagram API settings', 'enjoy-instagram' ); ?></label>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/carousel.png">'; ?>
			</div>
		</div>

		<div class="col-1-2 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header">
						<label for="enjoyinstagram_images_captured">
							<?php _ex( 'Images Captured from Instagram', 'option label', 'enjoy-instagram' ); ?>
						</label>
					</div>
					<div class="enin-block">
						<b><span style="color:#900;"><?php _e( 'BE CAREFUL', 'enjoy-instagram' ); ?>.</span><br/>
							<?php _e( 'If you choose too many, may cause degraded performance.<br>It is recommended not to exceed.', 'enjoy-instagram' ); ?>
						</b>
						<hr/>
						<input name="enjoyinstagram_images_captured" id="enjoyinstagram_images_captured" class="ei_sel"
							type="number" min="20" value="<?php echo get_option( 'enjoyinstagram_images_captured' ); ?>"/>
						<?php _ex( 'images', 'Images Captured from Instagram option label', 'enjoy-instagram' ); ?>
						<hr/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- carousel -->
	<div class="grid grid-pad <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label class="highlighted"><?php _e( 'Carousel settings', 'enjoy-instagram' ); ?></label>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/carousel.png">'; ?>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'General options', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_items_number_default" class="ei_settings_float_block">
							<?php _ex( 'Images displayed at a time', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_items_number_default" id="enjoyinstagram_carousel_items_number_default" class="enin-full">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>"<?php selected( $i, get_option( 'enjoyinstagram_carousel_items_number_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_dots_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Dots', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_dots_default" class="ei_sel" id="enjoyinstagram_carousel_dots_default">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_dots_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_dots_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'When you click on an image', 'Label for option carousel link default', 'enjoy-instagram' ); ?></div>
					<label for="enjoyinstagram_carousel_link_default" class="enin-field">
						<?php _ex( 'Link Image to', 'option label', 'enjoy-instagram' ); ?>
					</label>
					<div class="enin-field">
						<?php foreach ( $settings['link_image_options'] as $key => $value ) : ?>
							<input type="radio" name="enjoyinstagram_carousel_link_default" value="<?php echo $key; ?>" <?php checked( $key, get_option( 'enjoyinstagram_carousel_link_default' ) ); ?>>
							<?php echo $value; ?><br/>
						<?php endforeach; ?>
						<input type="text" name="enjoyinstagram_carousel_link_altro_default" data-deps="enjoyinstagram_carousel_link_default" data-deps_value="altro"
							value="<?php echo get_option( 'enjoyinstagram_carousel_link_altro_default' ); ?>" id="enjoyinstagram_carousel_link_altro_default">
					</div>
				</div>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Navigation', 'Label for navigation options block', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_navigation_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Navigation buttons', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_navigation_default" class="ei_sel" id="enjoyinstagram_carousel_navigation_default">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_navigation_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_navigation_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div id="div_text_navigation" data-deps="enjoyinstagram_carousel_navigation_default" data-deps_value="true">
						<div class="enin-block">
							<label for="enjoyinstagram_carousel_navigation_prev_default" class="ei_settings_float_block ei_fixed">
								<?php _ex( 'Prev Button Text', 'option label', 'enjoy-instagram' ); ?>:
							</label>
							<input type="text" value="<?php echo get_option( 'enjoyinstagram_carousel_navigation_prev_default' ); ?>"
								name="enjoyinstagram_carousel_navigation_prev_default" id="enjoyinstagram_carousel_navigation_prev_default" class="ei_sel"><br/>
						</div>
						<div class="enin-block">
							<label for="enjoyinstagram_carousel_navigation_next_default" class="ei_settings_float_block ei_fixed">
								<?php _ex( 'Next Button Text', 'option label', 'enjoy-instagram' ); ?>:
							</label>
							<input type="text" value="<?php echo get_option( 'enjoyinstagram_carousel_navigation_next_default' ); ?>"
								name="enjoyinstagram_carousel_navigation_next_default" id="enjoyinstagram_carousel_navigation_next_default" class="ei_sel">
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="grid grid-pad <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-empty"></div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Autoplay', 'Label for autoplay option block', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_autoplay_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'AutoPlay', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_autoplay_default" class="ei_sel" id="enjoyinstagram_carousel_autoplay_default">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_autoplay_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_autoplay_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div id="div_autoplay_yes" data-deps="enjoyinstagram_carousel_autoplay_default" data-deps_value="true">
						<div class="enin-block">
							<label for="enjoyinstagram_carousel_autoplay_timeout_default" class="ei_settings_float_block ei_fixed">
								<?php _ex( 'Timeout Autoplay', 'option label', 'enjoy-instagram' ); ?>:
							</label>
							<input type="number" name="enjoyinstagram_carousel_autoplay_timeout_default" id="enjoyinstagram_carousel_autoplay_timeout_default" value="<?php echo get_option( 'enjoyinstagram_carousel_autoplay_timeout_default' ); ?>" class="ei_sel">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Other settings', 'Label for common options block', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_loop_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Loop', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_loop_default" class="ei_sel" id="enjoyinstagram_carousel_loop_default">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_loop_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_loop_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_slidespeed_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Slide Speed (ms)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_carousel_slidespeed_default" value="<?php echo get_option( 'enjoyinstagram_carousel_slidespeed_default' ); ?>" class="ei_sel" id="enjoyinstagram_carousel_slidespeed_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_margin_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Margin (px)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_carousel_margin_default" class="ei_sel" value="<?php echo get_option( 'enjoyinstagram_carousel_margin_default' ); ?>" id="enjoyinstagram_carousel_margin_default">
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Responsive settings', 'enjoy-instagram' ); ?></div>
					<div class="enin-small-header"><?php _e( 'Number of images showed', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_480px_default" class="ei_settings_float_block ei_fixed">480px:</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_carousel_480px_default' ); ?>" name="enjoyinstagram_carousel_480px_default" class="ei_sel" id="enjoyinstagram_carousel_480px_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_600px_default" class="ei_settings_float_block ei_fixed">600px:</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_carousel_600px_default' ); ?>" name="enjoyinstagram_carousel_600px_default" class="ei_sel" id="enjoyinstagram_carousel_600px_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_768px_default" class="ei_settings_float_block ei_fixed">768px:</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_carousel_768px_default' ); ?>" name="enjoyinstagram_carousel_768px_default" class="ei_sel" id="enjoyinstagram_carousel_768px_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_1024px_default" class="ei_settings_float_block ei_fixed">1024px:</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_carousel_1024px_default' ); ?>" name="enjoyinstagram_carousel_1024px_default" class="ei_sel" id="enjoyinstagram_carousel_1024px_default">
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- Grid view -->
	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_carousel_grid" class="highlighted"><?php _e( 'Grid view settings', 'enjoy-instagram' ); ?>:</label><br/><br/>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/grid-view.png">'; ?>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'General settings', 'Label for grid general settings', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_cols_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_cols_default" id="enjoyinstagram_grid_cols_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_cols_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_rows_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_rows_default" id="enjoyinstagram_grid_rows_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_rows_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_margin_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Margin (px)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_grid_margin_default" class="ei_sel" value="<?php echo get_option( 'enjoyinstagram_grid_margin_default' ); ?>" id="enjoyinstagram_grid_margin_default">
					</div>
				</div>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'When you click on an image', 'Label for option grid link default', 'enjoy-instagram' ); ?></div>
				</div>

				<label for="enjoyinstagram_grid_link_default" class="enin-field">
					<?php _ex( 'Link Image to', 'option label', 'enjoy-instagram' ); ?>:
				</label>
				<div class="enin-field">
					<?php foreach ( $settings['link_image_options'] as $key => $value ) : ?>
						<input type="radio" name="enjoyinstagram_grid_link_default" value="<?php echo $key; ?>" <?php checked( $key, get_option( 'enjoyinstagram_grid_link_default' ) ); ?>>
						<?php echo $value; ?><br/>
					<?php endforeach; ?>
					<input type="text" name="enjoyinstagram_grid_link_altro_default" data-deps="enjoyinstagram_grid_link_default" data-deps_value="altro"
						value="<?php echo get_option( 'enjoyinstagram_grid_link_altro_default' ); ?>" id="enjoyinstagram_grid_link_altro_default">
				</div>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Images alternance', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_step_default" class="enin-full-label">
							<?php _ex( 'Number of items that are replaced at the same time', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_step_default" id="enjoyinstagram_grid_step_default" class="enin-full">
							<option value="random" <?php selected( 'random', get_option( 'enjoyinstagram_grid_step_default' ) ); ?>><?php _e( 'Random', 'enjoy-instagram' ); ?></option>
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_step_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_animation_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Animation Type', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_animation_default" id="enjoyinstagram_grid_animation_default" class="ei_sel">
							<?php foreach ( $settings['grid_animation'] as $key => $value ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $key, get_option( 'enjoyinstagram_grid_animation_default' ) ); ?>><?php echo $value; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 hide-on-mobile">
			<div class="enin-content-empty"></div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Animation', 'Label for grid animation settings', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_animation_speed_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Animation Speed (ms)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_grid_animation_speed_default' ); ?>" name="enjoyinstagram_grid_animation_speed_default" class="ei_sel" id="enjoyinstagram_grid_animation_speed_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_interval_default">
							<?php _ex( 'Time between image replace(ms)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<div class="enin-full">
							<input type="number" min="300" value="<?php echo get_option( 'enjoyinstagram_grid_interval_default' ); ?>" name="enjoyinstagram_grid_interval_default" class="ei_sel" id="enjoyinstagram_grid_interval_default">
						</div>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_onhover_default">
							<?php _ex( 'Switch Images on mouse hover', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<div class="enin-full">
							<select name="enjoyinstagram_grid_onhover_default" class="ei_sel" id="enjoyinstagram_grid_onhover_default">
								<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_grid_onhover_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
								<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_grid_onhover_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Responsive settings 1/2', 'enjoy-instagram' ); ?></div>
					<div class="enin-small-header">480px</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_cols_480px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_cols_480px_default" id="enjoyinstagram_grid_cols_480px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_cols_480px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_rows_480px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_rows_480px_default" id="enjoyinstagram_grid_rows_480px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_rows_480px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-small-header">600px</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_cols_600px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_cols_600px_default" id="enjoyinstagram_grid_cols_600px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_cols_600px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_rows_600px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_rows_600px_default" id="enjoyinstagram_grid_rows_600px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_rows_600px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Responsive settings 2/2', 'enjoy-instagram' ); ?></div>
					<div class="enin-small-header">768px</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_cols_768px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_cols_768px_default" id="enjoyinstagram_grid_cols_768px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_cols_768px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_rows_768px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_rows_768px_default" id="enjoyinstagram_grid_rows_768px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_rows_768px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-small-header">1024px</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_cols_1024px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_cols_1024px_default" id="enjoyinstagram_grid_cols_1024px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_cols_1024px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_grid_rows_1024px_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_grid_rows_1024px_default" id="enjoyinstagram_grid_rows_1024px_default" class="ei_sel">
							<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( $i, get_option( 'enjoyinstagram_grid_rows_1024px_default' ) ); ?>>
									<?php echo '&nbsp;' . $i; ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- polaroid -->
	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_carousel_polaroid" class="highlighted"><?php _e( 'Polaroid view settings:', 'enjoy-instagram' ); ?></label><br/><br/>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/polaroid.png">'; ?>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Appearance', 'Label for appearance polaroid settings', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_polaroid_background_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Background #', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="text" name="enjoyinstagram_polaroid_background_default" class="ei_sel" value="<?php echo get_option( 'enjoyinstagram_polaroid_background_default' ); ?>" id="enjoyinstagram_polaroid_background_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_polaroid_start_button_default">
							<?php _ex( 'Show "View Gallery" Start Button', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<div class="enin-full">
							<select name="enjoyinstagram_polaroid_start_button_default" id="enjoyinstagram_polaroid_start_button_default" class="ei_sel">
								<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_polaroid_start_button_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
								<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_polaroid_start_button_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
							</select>
						</div>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_polaroid_back_default">
							<?php _ex( 'Show Info on Back ( Instagram Photo Description Content )', 'option label', 'enjoy-instagram' ); ?>
						</label>
						<div class="enin-full">
							<select name="enjoyinstagram_polaroid_back_default" id="enjoyinstagram_polaroid_back_default" class="ei_sel">
								<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_polaroid_back_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
								<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_polaroid_back_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Polaroid border', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_polaroid_border_width_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Width (px)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="text" name="enjoyinstagram_polaroid_border_width_default" class="ei_sel" value="<?php echo get_option( 'enjoyinstagram_polaroid_border_width_default' ); ?>" id="enjoyinstagram_polaroid_border_width_default">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_polaroid_border_color_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Color #', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="text" name="enjoyinstagram_polaroid_border_color_default" class="ei_sel" value="<?php echo get_option( 'enjoyinstagram_polaroid_border_color_default' ); ?>" id="enjoyinstagram_polaroid_border_width_default">
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'When you click on an image', 'option label', 'enjoy-instagram' ); ?></div>
					<label for="enjoyinstagram_polaroid_link_default" class="enin-field">
						<?php _ex( 'Link Image to', 'option label', 'enjoy-instagram' ); ?>:
					</label>
					<div class="enin-field">
						<?php foreach ( $settings['link_image_options'] as $key => $value ) : ?>
							<input type="radio" name="enjoyinstagram_polaroid_link_default" value="<?php echo $key; ?>" <?php checked( $key, get_option( 'enjoyinstagram_polaroid_link_default' ) ); ?>>
							<?php echo $value; ?><br/>
						<?php endforeach; ?>
						<input type="text" name="enjoyinstagram_polaroid_link_altro_default" data-deps="enjoyinstagram_polaroid_link_default" data-deps_value="altro"
							value="<?php echo get_option( 'enjoyinstagram_polaroid_link_altro_default' ); ?>" id="enjoyinstagram_polaroid_link_altro_default">
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- Album -->
	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label class="highlighted"><?php _e( 'Album view settings', 'enjoy-instagram' ); ?>:</label><br/><br/>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/album-view.png">'; ?>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'Appearance', 'Label for appearance album settings', 'enjoy-instagram' ); ?></div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_hover_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Show on hover', 'option label', 'enjoy-instagram' ); ?>
						</label>
						<select name="enjoyinstagram_album_hover_default" id="enjoyinstagram_album_hover_default" class="ei_sel" style="width:140px;">
							<option value="likes" <?php selected( 'likes', get_option( 'enjoyinstagram_album_hover_default' ) ); ?>><?php _e( 'Likes Count', 'enjoy-instagram' ); ?></option>
							<option value="exce" <?php selected( 'exce', get_option( 'enjoyinstagram_album_hover_default' ) ); ?>><?php _e( 'Caption Exceprt', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _ex( 'When you click on an image', 'Label for option album link default', 'enjoy-instagram' ); ?></div>
					<label for="enjoyinstagram_album_link_default" class="enin-field">
						<?php _ex( 'Link Image to', 'option label', 'enjoy-instagram' ); ?>:
					</label>
					<div class="enin-field">
						<?php foreach ( $settings['link_image_options'] as $key => $value ) : ?>
							<input type="radio" name="enjoyinstagram_album_link_default" value="<?php echo $key; ?>" <?php checked( $key, get_option( 'enjoyinstagram_album_link_default' ) ); ?>>
							<?php echo $value; ?><br/>
						<?php endforeach; ?>
						<input type="text" name="enjoyinstagram_album_link_altro_default" data-deps="enjoyinstagram_album_link_default" data-deps_value="altro"
							value="<?php echo get_option( 'enjoyinstagram_album_link_altro_default' ); ?>" id="enjoyinstagram_album_link_altro_default">
					</div>
				</div>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'LightBox Options', 'enjoy-instagram' ); ?>:</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_likes_count_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Show Likes Count', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_likes_count_default" id="enjoyinstagram_carousel_likes_count_default" class="ei_sel">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_likes_count_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_likes_count_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_image_author_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Show Author', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_carousel_image_author_default" id="enjoyinstagram_carousel_image_author_default" class="ei_sel">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_carousel_image_author_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_carousel_image_author_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1 hide-on-mobile">
			<div class="enin-content-empty"></div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Effects', 'enjoy-instagram' ); ?>:</div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_random_angle_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Random Angle', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_album_random_angle_default" id="enjoyinstagram_album_random_angle_default" class="ei_sel">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_album_random_angle_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_album_random_angle_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_delay_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Image Delay', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_album_delay_default" class="ei_sel" id="enjoyinstagram_album_delay_default" value="<?php echo get_option( 'enjoyinstagram_album_delay_default' ); ?>">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_margin_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Images margin', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_album_margin_default" class="ei_sel" id="enjoyinstagram_album_margin_default" value="<?php echo get_option( 'enjoyinstagram_album_margin_default' ); ?>">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_animation_in_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Speed Animation In:', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_album_animation_in_default" class="ei_sel" id="enjoyinstagram_album_animation_in_default" value="<?php echo get_option( 'enjoyinstagram_album_animation_in_default' ); ?>">
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_album_animation_out_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Speed Animation Out', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" name="enjoyinstagram_album_animation_out_default" class="ei_sel" id="enjoyinstagram_album_animation_out_default" value="<?php echo get_option( 'enjoyinstagram_album_animation_out_default' ); ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<!-- lightbox + other settings -->
	<div class="grid grid-pad  <?php echo enjoyinstagram()->is_premium() ? '' : 'ei-premium'; ?>">
		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_lightbox_options" class="highlighted"><?php _e( 'LightBox Default Settings', 'enjoy-instagram' ); ?>:</label><br/><br/>
				<?php echo '<img src="' . ENJOYINSTAGRAM_ASSETS_URL . '/images/lightbox.png" >'; ?>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Lightbox settings', 'enjoy-instagram' ); ?>:</div>
					<div class="enin-block">
						<label for="enjoyinstagram_carousel_autoreload_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Lightbox Caption', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_lightbox_caption_default" id="enjoyinstagram_lightbox_caption_default" class="ei_sel">
							<option value="yes" <?php selected( 'yes', get_option( 'enjoyinstagram_lightbox_caption_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="no" <?php selected( 'no', get_option( 'enjoyinstagram_lightbox_caption_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>


					<div class="enin-block">
						<label for="enjoyinstagram_carousel_autoreload_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'Lightbox Style', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_lightbox_style_default" id="enjoyinstagram_lightbox_style_default" class="ei_sel">
							<option value="default" <?php selected( 'default', get_option( 'enjoyinstagram_lightbox_style_default' ) ); ?>><?php _e( 'Leonardo', 'enjoy-instagram' ); ?></option>
							<option value="igvertical" <?php selected( 'igvertical', get_option( 'enjoyinstagram_lightbox_style_default' ) ); ?>><?php _e( 'Michelangelo', 'enjoy-instagram' ); ?></option>
						</select>
					</div>

				</div>
			</div>
		</div>


		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content-title">
				<label for="enjoyinstagram_other_options" class="highlighted"><?php _e( 'Other Settings', 'enjoy-instagram' ); ?>:</label>
			</div>
		</div>

		<div class="col-1-4 mobile-col-1-1">
			<div class="enin-content">
				<div class="ei_block">
					<div class="enin-medium-header"><?php _e( 'Auto-refresh', 'enjoy-instagram' ); ?>:</div>
					<div class="enin-block">
						<label for="enjoyinstagram_autoreload_default" class="ei_settings_float_block ei_fixed">
							<?php _ex( 'AutoReload', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<select name="enjoyinstagram_autoreload_default" id="enjoyinstagram_autoreload_default" class="ei_sel">
							<option value="true" <?php selected( 'true', get_option( 'enjoyinstagram_autoreload_default' ) ); ?>><?php _e( 'Yes', 'enjoy-instagram' ); ?></option>
							<option value="false" <?php selected( 'false', get_option( 'enjoyinstagram_autoreload_default' ) ); ?>><?php _e( 'No', 'enjoy-instagram' ); ?></option>
						</select>
					</div>
					<div class="enin-block">
						<label for="enjoyinstagram_autoreload_value_default" class="ei_settings_float_block ei_fixed enin-label">
							<?php _ex( 'Interval (ms)', 'option label', 'enjoy-instagram' ); ?>:
						</label>
						<input type="number" value="<?php echo get_option( 'enjoyinstagram_autoreload_value_default' ); ?>" name="enjoyinstagram_autoreload_value_default" class="ei_sel" id="enjoyinstagram_autoreload_value_default">
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<input type="submit" class="button-primary" id="button_enjoyinstagram_advanced_default" name="button_enjoyinstagram_advanced_default" value="<?php _e( 'Save Settings', 'enjoy-instagram' ); ?>" style="float:right;   margin-bottom:1em;"/>
</form>

