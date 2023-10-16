<?php
/**
 * This class handles the wp dashbaord widget display
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

/**
 * Class Enjoy_Instagram_Dashboard_Widgets
 *
 * @since 6.1.0
 */
class Enjoy_Instagram_Dashboard_Widgets {
	/**
	 * Enjoy_Instagram_Dashboard_Widgets constructor.
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', [ $this, 'create_widgets' ], - 10000 );
	}

	/**
	 * Adds widgets handler to the wp dashboard
	 *
	 * @return void
	 */
	public function create_widgets() {
		wp_add_dashboard_widget( 'ei_dashboard_widget_stats', __( 'Enjoy Instagram Stats', 'enjoy-instagram' ), [ $this, 'show_stats_widget' ] );
	}

	/**
	 * Show the stats widget
	 *
	 * @return void
	 */
	public function show_stats_widget() {
		wp_enqueue_style( 'ei-icons', ENJOYINSTAGRAM_ASSETS_URL . '/css/ei-icons.css', [], ENJOYINSTAGRAM_VERSION );
		wp_enqueue_style( 'ei-dashboard-widget', ENJOYINSTAGRAM_ASSETS_URL . '/css/dashboard-widget.css', [], ENJOYINSTAGRAM_VERSION );

		$db_stats = ei_db()->stats();

		?>
		<div class="ei-stats-wrapper">
			<div class="ei-stats-left">
				<img src="<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/icon-256x256.jpg'; ?>">
				<a href="<?php echo ei_admin()->get_tab_url( 'shortcode-settings' ); ?>">
					<?php _e( 'Create shortcode', 'default' ); ?>
				</a>
			</div>
			<div class="ei-stats">
				<ul>
					<li>
						<span>
							<i class="ei-icons-users"></i>
							<?php _e( 'Linked accounts', 'enjoy-instagram' ); ?>
						</span>
						<span><strong><?php echo count( enjoyinstagram()->get_users() ); ?></strong></span>
					</li>
					<li>
					<span>
						<i class="ei-icons-instagram"></i>
						<?php _e( 'Images captured', 'enjoy-instagram' ); ?>
					</span>
						<span><strong><?php echo $db_stats['media']['count']; ?></strong></span>
					</li>
					<li>
						<span>
							<i class="ei-icons-hash"></i>
							<?php _e( 'Hashtags', 'enjoy-instagram' ); ?>
						</span>
						<span><strong><?php echo $db_stats['hashtag']['count']; ?></strong></span>
					</li>
					<?php if ( enjoyinstagram()->is_premium() ) : ?>
						<li>
							<span>
								<i class="ei-icons-thumbs-up"></i>
								<?php _e( 'Moderated - Approved', 'enjoy-instagram' ); ?>
							</span>
							<span><strong><?php echo $db_stats['moderated_approved']['count']; ?></strong></span>
						</li>
						<li>
							<span>
								<i class="ei-icons-thumbs-down"></i>
								<?php _e( 'Moderatd - Rejected', 'enjoy-instagram' ); ?>
							</span>
							<span><strong><?php echo $db_stats['moderated_rejected']['count']; ?></strong></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>
		<?php if ( ! enjoyinstagram()->is_premium() ) : ?>
			<div class="ei-premium-link">
				<?php
				echo sprintf(
				// translators: Message for premium link
					__( 'If you need more features <a href="%s" target="_blank">Try the premium version!</a>', 'enjoy-instagram' ),
					ei_admin()->get_premium_url(
						[
							'utm_source'   => 'website',
							'utm_medium'   => 'backend-widget',
							'utm_campaign' => 'try-the-premium-version',
						]
					)
				)
				?>
			</div>
		<?php endif; ?>
		<?php
	}
}

