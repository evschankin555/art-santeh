<?php
/**
 * The template part for displaying the popup.
 *
 * @package Cenos
 */
?>
<div class="popup-modal modal fade" id="popup-modal" tabindex="-1" role="dialog" aria-labelledby="popup-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="popup-image">
                    <?php
                    if ( $popup_banner = cenos_get_option( 'popup_image' ) ) {
                        if ( '1-column' == cenos_get_option( 'popup_layout' ) ) {
                            printf( '<div class="popup-image-holder" style="background-image: url(%s)"></div>', esc_url( $popup_banner ) );
                        } else {
                            printf( '<img src="%s">', esc_url( $popup_banner ) );
                        }
                    }
                    ?>
                </div>
                <div class="popup-content">
                    <div class="popup-content-wrapper">
                        <?php echo do_shortcode( wp_kses_post( cenos_get_option( 'popup_content' ) ) ); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="disable_popup_again"><?php esc_attr_e('Don\'t show this pop-up again!','cenos');?></a>
            </div>
        </div>
    </div>
</div>