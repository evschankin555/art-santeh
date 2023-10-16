<?php
$class_tmp_name = ['header-element','language-box'];
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $class_tmp_name[] =  $args['class_tmp'];
}
?>
<div class="<?php echo esc_attr(implode(' ', $class_tmp_name)); ?>">
    <?php cenos_language_switcher();?>
</div>
