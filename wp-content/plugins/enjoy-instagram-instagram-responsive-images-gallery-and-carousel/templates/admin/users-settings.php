<?php
/**
 * Users settings template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // return if called directly
}

$users = enjoyinstagram()->get_all_users();

if ( empty( $users ) ) : ?>

	<p style="font-size:14px;">
		<?php _e( 'Thank you for you choise! <strong>Enjoy Instagram - Responsive gallery</strong> is a plugin lovingly developed for you by ', 'enjoy-instagram' ); ?>
		<a href="http://www.mediabeta.com" target="_blank">Mediabeta</a>.
	</p>
	<p style="font-size:14px;">By using this plugin, you are agreeing to the <a
			href="http://instagram.com/about/legal/terms/api/" target="_blank">Instagram API Terms of Use</a>.</p>
	<p style="font-size:14px;">
		<?php _e( 'If you are a Business Instagram account connect to Facebook Graph API using the button below to get latest API features and hashtag search', 'enjoy-instagram' ); ?>
	</p>
	<a href="<?php echo ei_admin()->get_facebook_connect_url(); ?>" class="button-primary">
		<?php _e( 'Add new user - Business Instagram Account', 'enjoy-instagram' ); ?>
	</a>
	<a href="#" class="enjoy-instagram-help"
		title="<?php _e( 'Basic Display user have access to everything from basic profile with full name, bio, website, following count, followers count, profile picture, feed of posts and hashtag feed', 'enjoy-instagram' ); ?>">[?]</a>

	<p style="font-size:14px;">
		<?php _e( 'Click the button below to connect a standard Instagram account', 'enjoy-instagram' ); ?>
	</p>
	<a href="<?php echo ei_admin()->get_instagram_login_url(); ?>" class="button-secondary">
		<?php _e( 'Add new user - Personal Instagram Account', 'enjoy-instagram' ); ?>
	</a>
	<a href="#"
		class="enjoy-instagram-help"
		title="<?php _e( 'Basic Display user have access to profile info such as username, media count, user id and feed of posts', 'enjoy-instagram' ); ?>">
		[?]
	</a>

<?php else : ?>

	<div class="grid grid-pad">
		<div class="col-1-1 mobile-col-1-1">
			<h2><?php esc_html_e( 'Linked Instagram Profiles', 'enjoy-instagram' ); ?></h2>
			<hr/>
		</div>
	</div>
	<div class="grid grid-pad">
		<div class="col-2-12 mobile-col-1-1">
			<div class="enin-content-title" style="text-align:center;">
				<img src="<?php echo esc_attr( ENJOYINSTAGRAM_ASSETS_URL . '/images/users.png' ); ?>">
				<input type="button" id="button_add_new_user" value="Add New User" class="button-primary ei_top" <?php echo disabled( ! enjoyinstagram()->is_premium() ); ?>>
				<p>
					<?php
					if ( ! enjoyinstagram()->is_premium() ) {
						echo sprintf(
						// translators: Text with link to a premium url.
							__( '<a href="%s">Upgrade to the Premium</a> version to unlock the multi account', 'enjoy-instagram' ),
							ei_admin()->get_premium_url(
								[
									'utm_source'   => 'plugin-free',
									'utm_campaign' => 'enjoy-instagram',
								]
							)
						);
					}
					?>
				</p>
			</div>
		</div>
		<div class="col-10-12 mobile-col-1-1"
			style="border-left:1px dashed #C9C9C9; padding:0.5em; padding-top:0; margin-top:0;">
			<?php foreach ( $users as $user_id => $user ) : ?>
				<?php if ( $user['username'] ) : ?>
					<table class="form-table">
						<tbody>
						<tr>
							<th scope="row" style="align:left;">
								<div id="enjoy_user_profile">
									<?php if ( $user['business'] ) : ?>
										<img class="enjoy_user_profile" src="<?php echo $user['profile_picture']; ?>">
									<?php endif; ?>

									<?php

									$remove_user_url = ei_admin()->build_admin_url(
										'users-settings',
										[
											'user_id' => $user_id,
											'action'  => 'enjoyinstagram-remove-user',
										]
									);

									$clear_user_data_url = ei_admin()->build_admin_url(
										'users-settings',
										[
											'user_id' => $user_id,
											'action'  => 'enjoyinstagram-clear-user-data',
										]
									);

									$activate_user_url = ei_admin()->build_admin_url(
										'users-settings',
										[
											'user_id' => $user_id,
											'action'  => 'enjoyinstagram-activate-user',
										]
									);

									?>

									<a href="<?php echo $remove_user_url; ?>" id="button_logout_<?php echo $user_id; ?>" class="button-primary ei_top">
										<?php _e( 'Unlink User', 'enjoy-instagram' ); ?>
									</a>
									<a href="<?php echo $clear_user_data_url; ?>" id="button_clear_data_<?php echo $user_id; ?>" class="button-primary ei_top">
										<?php _e( 'Clear User Data', 'enjoy-instagram' ); ?>
									</a>
									<?php if ( ! $user['active'] ) : ?>
										<a href="<?php echo $activate_user_url; ?>" id="button_activate_user_<?php echo $user_id; ?>" class="button ei_top">
											<?php _e( 'Activate this account', 'enjoy-instagram' ); ?>
										</a>
									<?php endif; ?>
								</div>
							</th>
							<td>
								<div id="enjoy_user_block" class="<?php echo $user['active'] ? 'active' : 'disabled'; ?>">
									<h3>
										<?php echo ! empty( $user['full_name'] ) ? $user['full_name'] : $user['username']; ?>

										<?php if ( ! $user['active'] ) : ?>
											<span class="disabled-tag">
												<?php _e( 'Disabled', 'enjoy-instagram' ); ?>
											</span>
										<?php endif; ?>


										<?php if ( $user['business'] ) : ?>
											<span class="business-tag">
												<?php _e( 'Business', 'enjoy-instagram' ); ?>
											</span>
										<?php endif; ?>
									</h3>
									<p><i><?php echo $user['bio']; ?></i></p>
									<hr/>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php do_action( 'enjoyinstagram_after_user_accounts' ); ?>

		</div>
	</div>

<?php endif; ?>
