<aside class="js-offcanvas c-offcanvas is-closed"
       data-offcanvas-options='{"modifiers": "top,overlay"}'
       id="search-canvas">
    <div class="offcanvas-content">
        <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
            <?php cenos_svg_icon('close');?>
            <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
        </button>
        <?php $search_heading_text = cenos_get_option('search_heading_text');?>
        <h2><?php echo esc_html($search_heading_text) ?></h2>
        <div class="search_form_wrapper">
            <div class="search_form_content">
                <?php get_template_part('template-parts/headers/parts/search/search', 'form_content'); ?>
            </div>
        </div>
    </div>
</aside>
