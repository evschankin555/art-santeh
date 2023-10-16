<?php
/**
 * Template part for displaying the preloader.
 *
 * @package Cenos
 */

$preloader_type = cenos_get_option('preloader_type' );
?>
<div id="preloader" class="preloader preloader-<?php echo esc_attr($preloader_type) ?>">
	<?php
	switch ( $preloader_type ) {
		case 'image':
			$image = cenos_get_option( 'preloader_image' );
			break;

		case 'external':
			$image = cenos_get_option( 'preloader_url' );
			break;

		default:
			$image = false;
			break;
	}

	if ( ! $image ) {
		echo '<span class="preloader-icon loading"></span>';
	} else {
		$image = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Preloader', 'cenos' ) . '">';
		echo '<span class="preloader-icon">' . $image . '</span>';
	}
	?>
</div>