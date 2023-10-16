<?php
/**
 * Template used to display post content.
 *
 * @package Cenos
 */

$blog_list_style = cenos_get_option('blog_list_style');
get_template_part( 'template-parts/content-list', $blog_list_style );


