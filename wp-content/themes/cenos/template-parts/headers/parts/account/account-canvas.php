<aside class="js-offcanvas c-offcanvas is-closed"
       data-offcanvas-options='{"modifiers": "right,overlay"}'
       id="account-canvas">
    <div class="offcanvas-content account_form_content">
        <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
            <?php cenos_svg_icon('close');?>
            <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
        </button>
        <?php get_template_part('template-parts/headers/parts/account/account', 'form_content',['form_mode'=> 'canvas']); ?>
    </div>
</aside>
