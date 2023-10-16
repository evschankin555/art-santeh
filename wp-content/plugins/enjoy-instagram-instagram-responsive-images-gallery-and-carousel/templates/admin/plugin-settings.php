<?php
/**
 * Main plugin settings template
 */

/** @var array $tabs */
/** @var string $active_tab */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // return if called directly
}

?>
<div class="wrap">
	<div class="ei_block">
		<div class="ei_left_block">
			<div class="ei_hard_block">
				<img src="<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/enjoyinstagram.png'; ?>">
			</div>

			<div class="ei_twitter_block">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.mediabeta.com/enjoy-instagram/"
					data-text="<?php _e( 'I\'ve just installed Enjoy Instagram for WordPress. Awesome!', 'enjoy-instagram' ); ?>" data-hashtags="wordpress">
					<?php _e( 'Tweet', 'enjoy-instagram' ); ?>
				</a>
				<script>
					!function (d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
						if (!d.getElementById(id)) {
							js = d.createElement(s);
							js.id = id;
							js.src = p + '://platform.twitter.com/widgets.js';
							fjs.parentNode.insertBefore(js, fjs);
						}
					}(document, 'script', 'twitter-wjs');
				</script>
			</div>

			<div id="fb-root"></div>
			<script>
				(function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s);
					js.id = id;
					js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&appId=<?php echo ENJOYINSTAGRAM_FB_APP_ID; ?>&version=v2.0";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
			<div class="ei_facebook_block">
				<div class="fb-like" data-href="http://www.mediabeta.com/enjoy-instagram/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
			</div>
		</div>

		<div id="buy_me_a_coffee" style="background:url(<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/buymeacoffee.png'; ?>)#fff no-repeat; ">
			<div class="pad_coffee">
				<span class="coffee_title"><?php _e( 'Buy me a coffee!', 'enjoy-instagram' ); ?></span>
				<p><span><?php _e( 'If you liked our work please consider to make a kind donation through Paypal.', 'enjoy-instagram' ); ?></span></p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="encrypted"
						value="-----BEGIN PKCS7-----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-----END PKCS7-----">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
	</div>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab_id => $tab ) : ?>
			<a class="nav-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>" href="<?php echo ei_admin()->get_tab_url( $tab_id ); ?>">
				<?php echo is_array( $tab ) ? $tab['name'] : $tab; ?>
			</a>
		<?php endforeach; ?>
	</h2>
	<?php
	// include template
	if ( $active_tab ) {
		$tab  = $tabs[ $active_tab ];
		$view = is_array( $tab ) ? $tab['view_file'] : ENJOYINSTAGRAM_TEMPLATE_PATH . '/admin/' . $active_tab . '.php';
		require_once $view;
	}

	?>
</div>
