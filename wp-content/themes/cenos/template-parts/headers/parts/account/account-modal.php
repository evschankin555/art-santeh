<div class="modal" id="fm-account-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="account-modal modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="cenos-close-btn close" data-dismiss="modal" aria-label="Close">
                <?php cenos_svg_icon('close');?>
                <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
            </button>
            <div class="modal-body">
                <div class="account_form_content">
                    <?php get_template_part('template-parts/headers/parts/account/account', 'form_content',['form_mode'=> 'modal']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
