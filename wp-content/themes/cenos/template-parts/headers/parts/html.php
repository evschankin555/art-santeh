<?php
if (!isset($args['html_part']) || empty($args['html_part'])){
    return;
}
$html_part = $args['html_part'];
$value = cenos_get_option($html_part);
$class_tmp_name = ['header-element',$html_part];
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $class_tmp_name[] =  $args['class_tmp'];
}
?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <?php echo do_shortcode($value);?>
</div>
