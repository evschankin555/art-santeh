<?php
/**
 * Moderation settings template
 *
 * @package EnjoyInstagram
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // return if called directly.
}

$settings_tags = apply_filters(
	'enjoyinstagram_shortcode_settings_tabs',
	[
		'mb'       => [
			'name'      => __( 'Carousel View', 'enjoy-instagram' ),
			'view_file' => ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/shortcodes/carousel.php',
		],
		'mb_grid'  => [
			'name'      => __( 'Grid View', 'enjoy-instagram' ),
			'view_file' => ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/shortcodes/grid.php',
		],
		'polaroid' => [
			'name'      => __( 'Polaroid View', 'enjoy-instagram' ),
			'view_file' => ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/shortcodes/polaroid.php',
		],
		'album'    => [
			'name'      => __( 'Album View', 'enjoy-instagram' ),
			'view_file' => ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/shortcodes/album.php',
		],
		'badge'    => [
			'name'      => __( 'User Badge', 'enjoy-instagram' ),
			'view_file' => ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/shortcodes/badge.php',
		],
	]
);

$users               = enjoyinstagram()->get_users();
$appearance_settings = enjoyinstagram_get_appearance_settings();
$users_moderate      = get_option( 'users_moderate', [] );
$users_moderate      = is_array( $users_moderate ) ? array_filter( $users_moderate ) : [];
$hashtag_moderate    = get_option( 'hashtag_moderate', [] );
$hashtag_moderate    = is_array( $hashtag_moderate ) ? array_filter( $hashtag_moderate ) : [];
?>

<div class="shortcode-settings-form">
	<div class="main">
		<section class="enjoy_tabs">

			<?php $i = 1; ?>
			<?php foreach ( $settings_tags as $tab_id => $setting_tab ) : ?>

				<input id="enjoy_tab-<?php echo $i; ?>" type="radio" name="enjoy_tab_checked" value="<?php echo esc_attr( $tab_id ); ?>" <?php echo 1 === $i ? 'checked="checked"' : ''; ?>/>
				<label for="enjoy_tab-<?php echo $i; ?>" class="enjoy_tab-label-<?php echo $i; ?>">
					<?php echo esc_html( $setting_tab['name'] ); ?>
				</label>

				<?php $i ++; ?>
			<?php endforeach; ?>

		</section>

		<section class="enjoy_tabs_content">

			<?php $i = 1; ?>
			<?php foreach ( $settings_tags as $tab_id => $setting_tab ) : ?>

				<div id="<?php echo esc_attr( $tab_id ); ?>"<?php echo 1 === $i ? ' class="active"' : ''; ?>>
					<?php if ( file_exists( $setting_tab['view_file'] ) ) : ?>
						<?php require $setting_tab['view_file']; ?>
					<?php endif ?>
				</div>

				<?php $i ++; ?>
			<?php endforeach; ?>

		</section>
	</div>
	<div class="shortcode-preview">
		<p class="code"></p>
		<span class="copy"><?php esc_html_e( 'Click to Copy', 'enjoy-instagram' ); ?></span>
	</div>
</div>
