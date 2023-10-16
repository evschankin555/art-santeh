<aside class="js-offcanvas c-offcanvas is-closed"
       data-offcanvas-options='{"modifiers": "right,overlay"}'
       id="cart-canvas">
    <div class="offcanvas-content">
        <div class="cart_box_head">
            <h3><?php esc_html_e('Shopping cart','cenos') ?></h3>
            <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
                <?php cenos_svg_icon('close');?>
                <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
            </button>
        </div>
        <div class="cart_box_content">
            <div class="widget_shopping_cart_content"></div>
        </div>
    </div>
</aside>

