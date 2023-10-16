<?php
/**
 * The template part for displaying the main logo on header
 *
 * @package Cenos
 */
$mobile_own_logo = cenos_get_option('mobile_own_logo');
$logo = false;
if ($mobile_own_logo) {
    $logo = cenos_get_option('mobile_logo');
}
if (!$logo){
    $logo       = cenos_get_option( 'logo' );
}
$logo_type = 'image';
if ( ! $logo) {
    $logo = cenos_get_option( 'logo_text' );
    if ($logo == ''){
        $logo = get_bloginfo( 'name' );
    }
    $logo_type = 'text';
}
$logo_class = ['site-logo'];
$site_title = get_bloginfo( 'name' );
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $logo_class[] =  $args['class_tmp'];
}
?>
<div class="<?php echo esc_attr(implode(' ', $logo_class)); ?>">
    <a href="<?php echo esc_url( home_url() ) ?>" class="logo <?php echo esc_attr($logo_type)?>" title="<?php echo esc_attr($site_title);?>">
        <?php if ( 'text' == $logo_type ) : ?>
            <span class="logo-text"><?php cenos_esc_data( $logo ) ?></span>
        <?php else : ?>
            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr($site_title); ?>" class="img-responsive">
        <?php endif; ?>
    </a>
</div>

