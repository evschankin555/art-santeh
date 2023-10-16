<?php
    $search_form_style = cenos_get_option('search_form_style');
    $search_placeholder_text = cenos_get_option('search_placeholder_text');
    $search_btn_text = cenos_get_option('search_btn_text');
    $search_btn_text_content = cenos_get_option('search_btn_text_content');
    $search_btn_class = 'icon';
    if ($search_btn_text == true){
        $search_btn_class = 'text';
    }
?>
<form role="search" method="get" class="search_form fm-search-form <?php echo esc_attr($search_form_style);?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" class="search-field search_text_input" placeholder="<?php echo esc_attr($search_placeholder_text); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <?php
    $search_clear_btn = cenos_get_option('search_clear_btn');
    if ($search_clear_btn == true){
        echo '<a href="javascript:void(0);" class="btn_clear_text">'.cenos_get_svg_icon('close').'</a>';
    }
    ?>
    <button class="<?php echo esc_attr($search_btn_class);?>" type="submit" value="<?php echo esc_attr($search_btn_text_content); ?>"><?php cenos_svg_icon('magnify')?><?php cenos_esc_data($search_btn_text_content); ?></button>
</form>
