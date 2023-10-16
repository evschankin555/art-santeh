<?php
$show_announcement = cenos_get_option('show_announcement');
if (!$show_announcement){
    return;
}
$css .= cenos_background_style('announcement_bg','.cenos-announcement-box');
