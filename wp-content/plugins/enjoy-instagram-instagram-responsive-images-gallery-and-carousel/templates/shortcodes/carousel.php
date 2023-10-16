<?php
/**
 * Carousel shortcode template
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

$theme = '';

if ( isset( $settings['theme'] ) ) {

	if ( 'polaroid' === $settings['theme'] ) {
		$theme = 'ei-polaroid';
	}

	if ( 'showcase' === $settings['theme'] ) {
		$theme = 'ei-showcase';
	}
}
?>

<script type="text/javascript">

	jQuery(function ($) {
		$().fancybox({
			selector: '#reload_enjoyinstagram_carousel_<?php echo $i; ?> .fancybox',
			preventCaptionOverlap: false,
			hash: false,
			animationEffect: "fade",
			transitionEffect: "fade",
			closeExisting: true,
			keyboard: true,
			image: {
				preload: true
			}
		});

		<?php if ( 'true' === $settings['autoreload'] && $settings['autoreload_value'] ) : ?>
		function ReloadEnjoyInstagramCarousel(id) {
			$('#reload_enjoyinstagram_carousel_' + id).load(document.URL + " #reload_enjoyinstagram_carousel_" + id, {}, function () {
				LoadEnjoyInstagramCarousel();
			});
			setTimeout(ReloadEnjoyInstagramCarousel, <?php echo $settings['autoreload_value']; ?>, id);
		}

		setTimeout(ReloadEnjoyInstagramCarousel, <?php echo $settings['autoreload_value']; ?>, <?php echo $i; ?> );
		<?php endif; ?>

		function LoadEnjoyInstagramCarousel() {
			new Swiper('#ei-carousel-<?php echo $i; ?>', {
				loop: <?php echo $settings['loop']; ?>,
				navigation: <?php echo 'true' === $settings['navigation'] ? '{nextEl: ".swiper-button-next-' . $i . '", prevEl: ".swiper-button-prev-' . $i . '"}' : 'false'; ?>,
				pagination: <?php echo 'true' === $settings['dots'] ? '{el: ".swiper-pagination-' . $i . '", type: "bullets", clickable: true, dynamicBullets: true, dynamicMainBullets: 3}' : 'false'; ?>,
				autoplay: <?php echo 'true' === $settings['autoplay'] ? '{delay: ' . $settings['autoplay_timeout'] . ', disableOnInteraction: false, waitForTransition: false}' : 'false'; ?>,
				speed: <?php echo $settings['slidespeed']; ?>,
				spaceBetween: <?php echo intval( $settings['margin'] ); ?>,
				slidesPerView: <?php echo $settings['items_number']; ?>,
				breakpoints: {
					0: {
						slidesPerView: <?php echo $settings['480px']; ?>
					},
					480: {
						slidesPerView: <?php echo 'polaroid' === $settings['theme'] ? 3 : $settings['600px']; ?>
					},
					600: {
						slidesPerView: <?php echo 'polaroid' === $settings['theme'] ? 3 : $settings['768px']; ?>
					},
					768: {
						slidesPerView:<?php echo $settings['1024px']; ?>
					},
					1024: {
						slidesPerView:<?php echo $settings['items_number']; ?>,
					}
				},
				on: {
					update: function () {
						<?php if ( 'ei-showcase' === $theme ) : ?>
						var maxHeight = 0;
						var slides = [];

						for (var i in this.slides) {
							var slide = this.slides[i];
							if (slide.height) {
								maxHeight = Math.max(maxHeight, slide.offsetHeight);
								slides.push(slide);
							}
						}

						slides.forEach(function (s) {
							s.style.height = maxHeight + 'px';
						})
						<?php endif; ?>

						for (var i in this.imagesToLoad) {
							var img = this.imagesToLoad[i];
							if (img && img.className && img.className.indexOf('ig-img') !== -1) {
								img.style.height = img.width + 'px';
							}
						}
					},
				}
			})
		}

		LoadEnjoyInstagramCarousel();
	});

</script>

<?php if ( isset( $settings['theme'] ) && 'default' !== $settings['theme'] ) : ?>
	<style>
		#reload_enjoyinstagram_carousel_<?php echo $i ?> .slide-inner {
			background-color: <?php echo $settings['backcolor']; ?> !important;
			border-color: <?php echo $settings['bordercolor']; ?> !important;
			color: <?php echo $settings['textcolor']; ?> !important;
		}

		#reload_enjoyinstagram_carousel_<?php echo $i ?> .enjoy-instagram-like-wrapper span {
			color: <?php echo $settings['textcolor']; ?> !important;
		}
	</style>
<?php endif; ?>

<div id="reload_enjoyinstagram_carousel_<?php echo $i; ?>">
	<div id="ei-carousel-<?php echo $i; ?>" class="ei-carousel swiper-container <?php echo $theme; ?>">

		<div class="swiper-wrapper">
			<?php
			foreach ( $result as $index => $entry ) {

				$square_thumbnail = 1 === (int) $settings['items_number'] ? $entry['images']['standard_resolution']['url'] : $entry['images']['thumbnail']['url'];
				$link_style       = "style=\"background-image: url('{$square_thumbnail}'); background-size: cover; display: block;\"";
				$is_video         = 'video' === $entry['type'];

				?>
				<div class="swiper-slide <?php echo $is_video ? 'ei-media-type-video' : 'ei-media-type-image'; ?>">

					<div class="slide-inner">

						<?php if ( 'polaroid' === $settings['theme'] ) : ?>

							<?php if ( '' !== $entry['user']['username'] ) { ?>
								<div class="enjoy-instagram-feed-user">
									<?php if ( ! empty( $entry['user']['profile_picture'] ) ) : ?>
										<div class="enjoy-instagram-ig-picture-wrapper">
											<a href="" target="_blank" class="user-ig-picture">
												<img alt="" src=" <?php echo $entry['user']['profile_picture']; ?>"
													style="width:28px; height:28px">

											</a>
										</div>
									<?php endif ?>
									<div class="enjoy-instagram-ig-username-wrapper">
										<a
											href="http://instagram.com/<?php echo $entry['user']['username']; ?>"
											target="_blank" class="user-ig">
											<?php echo $entry['user']['username']; ?>
										</a>
									</div>
								</div>
							<?php } ?>
							<div style=" clear:both"></div>


						<?php endif; ?>

						<?php
						switch ( $settings['link'] ) {
							case 'swipebox':
							case 'fancybox':
								$href = $entry['images']['standard_resolution']['url'];
								?>

								<?php if ( 'igvertical' === $settings['lightbox_style'] ) : ?>
								<a
									data-author-image="<?php echo $entry['user']['profile_picture']; ?>"
									data-media-id="<?php echo $entry['media_id']; ?>"
									data-likes-count="<?php echo $entry['likes']['count']; ?>"
									data-author-username="<?php echo $entry['user']['username']; ?>"
									data-link="<?php echo $entry['link']; ?>"
									data-square-thumbnail="<?php echo $href; ?>"
									data-text="<?php echo $entry['caption']['text']; ?>"
									title="<?php echo $entry['caption']['text']; ?>"
									class="send fancybox" href="#inline-fancy-<?php echo $index + 1; ?>" <?php echo $link_style; ?>>
									<img alt="<?php echo $entry['caption']['text']; ?>" src="<?php echo $square_thumbnail; ?>"
										class="ig-img" style="opacity: 0;">
								</a>
							<?php else : ?>

								<a
									data-author-image="<?php echo $entry['user']['profile_picture']; ?>"
									data-media-id="<?php echo $entry['media_id']; ?>"
									data-likes-count="<?php echo $entry['likes']['count']; ?>"
									data-author-username="<?php echo $entry['user']['username']; ?>"
									data-link="<?php echo $entry['link']; ?>"
									<?php if ( 'yes' === $settings['lightbox_caption'] ) : ?>
										data-caption="<?php echo nl2br( $entry['caption']['text'] ); ?>"
									<?php endif ?>
									data-type="<?php echo $is_video ? 'video' : 'image'; ?>"
									title="<?php echo $entry['caption']['text']; ?>" rel="gallery_swypebox"
									class="fancybox"
									href="<?php echo $href; ?>" <?php echo $link_style; ?>>
									<img alt="<?php echo $entry['caption']['text']; ?>" src="<?php echo $square_thumbnail; ?>"
										class="ig-img" style="opacity: 0;">
								</a>
							<?php endif ?>


								<?php
								break;
							case 'instagram':
								?>
								<a data-author-image="<?php echo $entry['user']['profile_picture']; ?>"
									data-likes-count="<?php echo $entry['likes']['count']; ?>"
									data-media-id="<?php echo $entry['media_id']; ?>"
									data-author-username="<?php echo $entry['user']['username']; ?>"
									data-link="<?php echo $entry['link']; ?>"
									title="<?php echo $entry['caption']['text']; ?>" target="_blank"
									href="<?php echo $entry['link']; ?>" <?php echo $link_style; ?>>
									<img alt="<?php echo $entry['caption']['text']; ?>" src="<?php echo $square_thumbnail; ?>"
										class="ig-img" style="opacity: 0;">
								</a>
								<?php
								break;
							case 'nolink':
								?>
								<a <?php echo $link_style; ?>>
									<img alt="<?php echo $entry['caption']['text']; ?>" src="<?php echo $square_thumbnail; ?>"
										class="ig-img" style="opacity: 0;">
								</a>
								<?php
								break;
							case 'altro':
								?>
								<a data-author-image="<?php echo $entry['user']['profile_picture']; ?>"
									data-media-id="<?php echo $entry['media_id']; ?>"
									data-likes-count="<?php echo $entry['likes']['count']; ?>"
									data-author-username="<?php echo $entry['user']['username']; ?>"
									data-link="<?php echo $entry['link']; ?>"
									title="<?php echo $entry['caption']['text']; ?>" target="_blank"
									href="<?php echo esc_url( $settings['link_altro'] ); ?>" <?php echo $link_style; ?>>
									<img alt="<?php echo $entry['caption']['text']; ?>" src="<?php echo $square_thumbnail; ?>"
										class="ig-img" style="opacity: 0;">
								</a>
								<?php
								break;
						}
						?>

						<?php if ( 'showcase' === $settings['theme'] ) : ?>

							<div class="bottom-polaroid">
								<div class="enjoy-instagram-heart-wrapper">
									<a href="http://instagram.com/<?php echo $entry['user']['username']; ?>" target="_blank"
										class="icon-ig">
										<img alt="" src="<?php echo ENJOYINSTAGRAM_ASSETS_URL; ?>/images/heart-regular.svg" style="width:24px; height:24px">
									</a>
								</div>
								<div class="enjoy-instagram-like-wrapper">
									<a target="_blank" href="<?php echo $entry['link']; ?>">
										<span id="likes_count"><?php echo $entry['likes']['count']; ?></span>
									</a>

								</div>
								<div style=" clear:both"></div>
								<div class="enjoy-instagram-text-wrapper">
									<span><?php echo mb_strimwidth( $entry['caption']['text'], 0, 100, '...' ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( 'polaroid' === $settings['theme'] ) : ?>

							<div class="bottom-polaroid">
								<div class="enjoy-instagram-heart-wrapper">
									<a
										href="http://instagram.com/<?php echo $entry['user']['username']; ?>" target="_blank"
										class="icon-ig">
										<img alt="" src="<?php echo ENJOYINSTAGRAM_ASSETS_URL; ?>/images/heart-regular.svg" style="width:24px; height:24px">
									</a>
								</div>
								<div class="enjoy-instagram-like-wrapper">
									<a target="_blank" href="<?php echo $entry['link']; ?>">
										<span id="likes_count"><?php echo $entry['likes']['count']; ?></span>
									</a>
								</div>
								<div style="clear:both"></div>

							</div>

						<?php endif; ?>
					</div>
				</div>

				<?php
			}
			?>
		</div>
	</div>

	<?php if ( 'true' === $settings['navigation'] ) : ?>
		<div class="swiper-navigation swiper-navigation-<?php echo $i ?>">
			<button class="swiper-button-prev swiper-button-prev-<?php echo $i ?>"><?php echo $settings['navigation_prev']; ?></button>
			<button class="swiper-button-next swiper-button-next-<?php echo $i ?>"><?php echo $settings['navigation_next']; ?></button>
		</div>
	<?php endif; ?>

	<?php if ( 'true' === $settings['dots'] ) : ?>
		<div class="swiper-pagination-wrapper">
			<div class="swiper-pagination swiper-pagination-<?php echo $i ?>"></div>
		</div>
	<?php endif; ?>
</div>

<?php if ( 'igvertical' === $settings['lightbox_style'] ) : ?>
	<?php include ENJOYINSTAGRAM_TEMPLATE_PATH . '/shortcodes/lightbox-styles/igvertical.php'; ?>
<?php endif ?>
