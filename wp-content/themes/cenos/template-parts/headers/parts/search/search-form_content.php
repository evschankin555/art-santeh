<?php
$serch_product_mode = true;
if (is_home() || is_category() || is_archive() || is_singular( 'post' )){
    $serch_product_mode = false;
}
if (cenos_is_woocommerce_activated()){
    if (is_shop() || is_product_category() || is_product_taxonomy() || is_product()){
        $serch_product_mode = true;
    }
} else {
    $serch_product_mode = false;
}

if(function_exists('get_product_search_form') && $serch_product_mode) {
  echo  get_product_search_form();
} else {
  echo  get_search_form();
}
