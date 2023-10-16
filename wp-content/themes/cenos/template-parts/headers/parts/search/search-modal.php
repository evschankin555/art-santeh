<div class="modal" id="fm-search-modal" tabindex="-1" role="dialog" aria-labelledby="fm-search-modalLabel" aria-hidden="true">
    <div class="search-modal modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php $search_heading_text = cenos_get_option('search_heading_text');?>
                <h2 class="modal-title" id="fm-search-modalLabel"><?php echo esc_html($search_heading_text) ?></h2>
                <button type="button" class="cenos-close-btn close" data-dismiss="modal" aria-label="Close">
                    <?php cenos_svg_icon('close');?>
                    <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                </button>
            </div>
            <div class="modal-body search_form_wrapper">
                <div class="search_form_content">
                    <?php get_template_part('template-parts/headers/parts/search/search', 'form_content'); ?>
                </div>
                <div class="search_result"></div>
            </div>
        </div>
    </div>
</div>
