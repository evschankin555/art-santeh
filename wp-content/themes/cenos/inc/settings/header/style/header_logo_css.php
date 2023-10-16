<?php
$logo_box_width = cenos_get_option('logo_box_width');
$logo_position = cenos_get_option('logo_position');
$css .= '.site-branding{width:'.$logo_box_width.'px;';
if ($logo_position){
    foreach ($logo_position as $pos => $value){
        $css_value = cenos_css_unit($value);
        if ($css_value != ''){
            $css .= 'padding-'.$pos.':'.$css_value.';';
        }
    }
}

$logo_align = (bool)cenos_get_option('logo_align');
if ($logo_align == true){
    $logo_h_align = cenos_get_option('logo_h_align');
    $css .= 'text-align:'.$logo_h_align.';';
    $logo_v_align = cenos_get_option('logo_v_align');
    $v_align_value = ['top'=>'flex-start','middle'=>'center','bottom'=>'flex-end'];
    $css .= 'align-self:'.$v_align_value[$logo_v_align].';';
} elseif ($preview) {
    $css .= 'text-align: inherit;';
    $css .= 'align-self: inherit;';
}
$css .='}';
/* logo on header layout */
$css .= '.header-layout3 .header-main {grid-template-columns: 1fr '.$logo_box_width.'px 1fr}';
$css .= '.header-layout5 .header-main {grid-template-columns: 1fr '.$logo_box_width.'px 1fr}';
$css .= '.header-layout6 .header-main {grid-template-columns: 1fr '.$logo_box_width.'px 1fr}';

$logo_width  = cenos_get_option( 'logo_width' );
if ($logo_width){
    $css .= '.site-logo{max-width:'.$logo_width.'px;}';
}
//logo font with logo text
$logo = cenos_get_option('logo');

if ($logo == ''){
    $logo_font = cenos_get_option('logo_font');
    $css .= '.site-branding .logo.text{';
    foreach ($logo_font as $attr => $value){
        if ($attr != 'subsets' && $value != ''){
            $css .= $attr.':'.$value.';';
        }

    }
    $css .= '}';
}
