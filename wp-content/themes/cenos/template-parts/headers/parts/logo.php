<?php
/**
 * The template part for displaying the main logo on header
 *
 * @package Cenos
 */
$logo       = cenos_get_option( 'logo' );
$logo_type = 'image';
$use_page_logo = false;
if ((cenos_is_woocommerce_activated() && (is_shop() || is_product_taxonomy())) || is_page()){
    if (is_page()){
        $page_id = get_the_ID();
    } else {
        $page_id = get_option( 'woocommerce_shop_page_id' );
    }

    $page_header_option = cenos_get_post_meta($page_id,'page_header_option',true);
    if ($page_header_option) {
        $page_logo = cenos_get_post_meta($page_id,'logo',true);
        if (!empty($page_logo)){
            $page_logo_url = wp_get_attachment_url($page_logo);
            if (!empty($page_logo_url)){
                $logo = $page_logo_url;
                $use_page_logo = true;
            }
        }
    }
}
if ( ! $logo) {
    $logo = cenos_get_option( 'logo_text' );
    if ($logo == ''){
        $logo = get_bloginfo( 'name' );
    }
    $logo_type = 'text';
}
$logo_width  = cenos_get_option( 'logo_width' );
$logo_position = cenos_get_option('logo_position');

$logo_width  = $logo_width > 0 ? ' width="' . esc_attr( $logo_width ) . '"' : '';
$logo_class = ['site-logo'];
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $logo_class[] =  $args['class_tmp'];
}
$show_sticky = cenos_get_option('show_sticky');
if ($show_sticky == true){
    $sticky_logo  = cenos_get_option( 'sticky_logo' );
    if ($sticky_logo !=''){
        $logo_class[] = 'has-sticky-logo';
    }
}
$custom_transparent_page = cenos_get_option('custom_transparent_page');
if (cenos_is_transparent_header()) {
    $transparent_logo = cenos_get_option('transparent_logo');
    if ($transparent_logo !=''){
        $logo_class[] = 'has-transparent-logo';
    }
}
?>
<div class="<?php echo esc_attr(implode(' ', $logo_class)); ?>">
    <a href="<?php echo esc_url( home_url() ) ?>" class="logo <?php echo esc_attr($logo_type)?>">
        <?php if (isset($transparent_logo) && $transparent_logo !='' && !$use_page_logo) :?>
            <img src="<?php echo esc_url( $transparent_logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo-img transparent-logo img-responsive" <?php cenos_esc_data($logo_width ); ?>>
        <?php elseif ( 'text' == $logo_type ) : ?>
            <span class="logo-text"><?php cenos_esc_data( $logo ) ?></span>
        <?php else : ?>
            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo-img logo-dark img-responsive" <?php cenos_esc_data($logo_width); ?>>
        <?php endif;?>
        <?php if (isset($sticky_logo) && $sticky_logo !='') :?>
            <img src="<?php echo esc_url( $sticky_logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo-img sticky-logo img-responsive" <?php cenos_esc_data($logo_width ); ?>>
        <?php endif;?>
    </a>
</div>
<?php if (cenos_get_option('logo_slogan') &&(( $description = get_bloginfo( 'description', 'display' ) ) || is_customize_preview() )) : ?>
    <p class="site-description"><?php cenos_esc_data($description); /* WPCS: xss ok. */ ?></p>
<?php endif; ?>

