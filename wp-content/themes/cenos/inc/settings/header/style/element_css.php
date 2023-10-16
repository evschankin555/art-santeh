<?php
$css .= cenos_dimension_style('search_margin','.search_box');
$css .= cenos_dimension_style('cart_margin','.cart_box');
$css .= cenos_dimension_style('account_margin','.my-account-box');
$css .= cenos_dimension_style('wishlist_margin','.wishlist-box');
$css .= cenos_dimension_style('compare_margin','.compare-box');

$search_width = cenos_get_option('search_width');
$search_width_style = '';
if ($search_width){
    $search_width_style = 'width: '.$search_width.'px;';
}
if ($search_width_style != '') {
    $css .= '@media (min-width: 1200px) {.fm-search-form{'.$search_width_style.'}}';
}
