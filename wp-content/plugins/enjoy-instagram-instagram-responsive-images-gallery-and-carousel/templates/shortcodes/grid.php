<?php
/**
 * Grid shortcode template
 *
 * @package EnjoyInstagram
 *
 * @var int $i
 * @var array $settings
 * @var array $result
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_premium = enjoyinstagram()->is_premium();

?>

<script type="text/javascript">
	jQuery(function ($) {
		$().fancybox({
			selector: '#reload_enjoyinstagram_grid_<?php echo $i; ?> .fancybox',
			preventCaptionOverlap: false,
			hash: false,
			animationEffect: "fade",
			transitionEffect: "fade",
			closeExisting: true,
			keyboard: true,
			image: {
				preload: true
			},
		});

		<?php if ( 'true' === $settings['autoreload'] && $settings['autoreload_value'] ) : ?>
		function ReloadEnjoyInstagramGrid(id) {
			jQuery('#reload_enjoyinstagram_grid_' + id).load(document.URL + " #reload_enjoyinstagram_grid_" + id, {}, function () {
				LoadEnjoyInstagramGrid();
			});
			setTimeout(ReloadEnjoyInstagramGrid, <?php echo $settings['autoreload_value']; ?>, id);
		}

		setTimeout(ReloadEnjoyInstagramGrid, <?php echo $settings['autoreload_value']; ?>, <?php echo $i; ?> );
		<?php endif; ?>

		function LoadEnjoyInstagramGrid() {

			var step = ('<?php echo $settings['step']; ?>' === 'random') ? 'random' : '<?php echo $settings['step']; ?>',
				maxStep = ('<?php echo $settings['step']; ?>' === 'random') ? 3 : <?php echo $settings['step']; ?>;

			jQuery('#grid-<?php echo $i; ?>').gridrotator({
				rows: <?php echo $settings['rows']; ?>,
				columns: <?php echo $settings['cols']; ?>,
				margin: <?php echo ( isset( $settings['margin'] ) ) ? $settings['margin'] : '0'; ?>,
				step: step,
				maxStep: maxStep,
				animType: '<?php echo $settings['animation']; ?>',
				animSpeed: <?php echo $settings['animation_speed']; ?>,
				interval: <?php echo $settings['interval']; ?>,
				onhover: <?php echo $settings['onhover']; ?>,
				preventClick: <?php echo 'nolink' === $settings['link'] ? 'true' : 'false'; ?>,
				w1024: {
					rows: <?php echo $is_premium ? $settings['rows_1024px'] : $settings['rows']; ?>,
					columns: <?php echo $is_premium ? $settings['cols_1024px'] : $settings['cols']; ?>
				},
				w768: {
					rows: <?php echo $is_premium ? $settings['rows_768px'] : $settings['rows']; ?>,
					columns: <?php echo $is_premium ? $settings['cols_768px'] : $settings['cols']; ?>
				},
				w600: {
					rows: <?php echo $is_premium ? $settings['rows_600px'] : $settings['rows']; ?>,
					columns: <?php echo $is_premium ? $settings['cols_600px'] : $settings['cols']; ?>
				},
				w480: {
					rows: <?php echo $is_premium ? $settings['rows_480px'] : $settings['rows']; ?>,
					columns: <?php echo $is_premium ? $settings['cols_480px'] : $settings['cols']; ?>
				},
				w320: {
					rows: <?php echo $is_premium ? $settings['rows_480px'] : $settings['rows']; ?>,
					columns: <?php echo $is_premium ? $settings['cols_480px'] : $settings['cols']; ?>
				},
				w150: {
					rows: <?php echo $settings['rows_480px']; ?>,
					columns: <?php echo $settings['cols_480px']; ?>
				}
			});
		}

		LoadEnjoyInstagramGrid();
	});
</script>

<div id="ei-grid-loading-<?php echo $i; ?>">
	<img src="<?php echo ENJOYINSTAGRAM_ASSETS_URL . '/images/loader1.gif'; ?>">
	<?php _e( 'Loading...', 'enjoy-instagram' ); ?>
</div>
<div id="reload_enjoyinstagram_grid_<?php echo $i; ?>">
	<div id="grid-<?php echo $i; ?>" class="ri-grid ri-grid-size-2 ri-shadow">
		<ul id="ei-grid-list-<?php echo $i; ?>" hidden="true">
			<?php
			foreach ( $result as $index => $entry ) {

				$link_attr_plain = '';
				$link            = '';
				$link_close      = '';
				$is_video        = 'video' === $entry['type'];

				$link_attr = [
					'data-author-image'    => isset( $entry['user']['profile_picture'] ) ? esc_attr( $entry['user']['profile_picture'] ) : '',
					'data-likes-count'     => isset( $entry['likes']['count'] ) ? esc_attr( $entry['likes']['count'] ) : '',
					'data-type'            => $is_video ? 'video' : 'image',
					'data-author-username' => isset( $entry['user']['username'] ) ? esc_attr( $entry['user']['username'] ) : '',
					'data-link'            => isset( $entry['link'] ) ? $entry['link'] : '',
					'class'                => $is_video ? 'ei-media-type-video' : 'ei-media-type-image',
					'href'                 => $entry['images']['standard_resolution']['url'],
					'data-media-id'        => $entry['media_id'],
				];

				switch ( $settings['link'] ) {
					case 'swipebox':
					case 'fancybox':
						$link_attr['class'] .= ' fancybox';
						// $link_attr['data-fancybox'] = 'gallery';
						if ( 'yes' === $settings['lightbox_caption'] ) {
							$link_attr['data-caption'] = nl2br( $entry['caption']['text'] );
						}

						if ( 'igvertical' === $settings['lightbox_style'] ) {
							unset( $link_attr['data-caption'] );
							unset( $link_attr['data-type'] );
							$link_attr['href'] = '#inline-fancy-' . ( $index + 1 );
						}

						break;
					case 'instagram':
						$link_attr['data-author-username'] = isset( $entry['user']['username'] ) ? esc_attr( $entry['user']['username'] ) : '';
						$link_attr['href']                 = $link_attr['data-link'];
						break;
					case 'nolink':
						$link_attr = [ 'href' => '#' ];
						break;
					case 'altro':
						$link_attr['data-author-username'] = isset( $entry['user']['username'] ) ? esc_attr( $entry['user']['username'] ) : '';
						$link_attr['href']                 = esc_url( $settings['link_altro'] );
						break;
				}

				foreach ( $link_attr as $key => $value ) { // build plain link attributes
					$link_attr_plain .= ' ' . $key . '="' . $value . '"';
				}
				if ( $link_attr_plain ) {
					$link       = '<a title="' . $entry['caption']['text'] . '" target="_blank" ' . $link_attr_plain . '>';
					$link_close = '</a>';
				}

				$imgsrc = 1 === (int) $settings['cols'] ? $entry['images']['standard_resolution']['url'] : $entry['images']['thumbnail']['url'];

				echo '<li>' . $link . '<img src="' . $imgsrc . '">' . $link_close . '</li>';
			}
			?>
		</ul>
	</div>
</div>

<?php if ( 'igvertical' === $settings['lightbox_style'] ) : ?>
	<?php include ENJOYINSTAGRAM_TEMPLATE_PATH . '/shortcodes/lightbox-styles/igvertical.php'; ?>
<?php endif ?>
