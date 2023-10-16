<?php
/**
 * Vertical Lightbox style
 *
 * @package EnjoyInstagram
 *
 * @var int $i
 * @var array $settings
 * @var array $result
 */

?>

<div class="gallery-container-fancy">
	<?php foreach ( $result as $index => $entry ) : ?>

		<?php
		$src     = $entry['images']['standard_resolution']['url'];
		$caption = $entry['caption']['text'];
		?>

		<div class="inline-fancy" style="display:none" id="inline-fancy-<?php echo $index + 1; ?>">

			<?php if ( 'video' === $entry['type'] ) : ?>
				<div class="video">
					<video class="fancybox-video" controls controlsList="nodownload" poster="">
						<source src="<?php echo $src; ?>" type="video/mp4">
					</video>
				</div>
			<?php else : ?>
				<div class="img-big"><img alt="" src="<?php echo $src; ?>"/></div>
			<?php endif; ?>

			<div class="caption-container">
				<div class="top-caption">
					<div class="enjoy-instagram-username">
						<?php if ( $entry['user']['profile_picture'] ) : ?>
							<a href="https://instagram.com/<?php echo $entry['user']['username']; ?>" target="_blank" class="icon-ig">
								<img alt="" src=" <?php echo $entry['user']['profile_picture']; ?>" style="width:28px; height:28px">
							</a>
						<?php endif; ?>
						<a href="https://instagram.com/<?php echo $entry['user']['username']; ?>" target="_blank" class="username">
							<?php echo ! empty( $entry['user']['username'] ) ? $entry['user']['username'] : 'Instagram.com'; ?>
						</a>
					</div>

					<div class="likes_count">
						<img alt="" src="<?php echo ENJOYINSTAGRAM_ASSETS_URL; ?>/images/heart-regular.svg" style="width:24px; height:24px">
						<?php echo $entry['likes']['count']; ?>
					</div>
				</div>

				<?php if ( 'yes' === $settings['lightbox_caption'] ) : ?>
					<div class="caption-text"><?php echo nl2br( $caption ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
