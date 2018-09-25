<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
$fName = $field->db_field_name;
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$type = array();
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
?>
<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
<input <?php echo $layout_params['field_styling']?> 
        class="<?php echo $layout_params['custom_class'].$required?> text_area"
        type="file"
        name="fi_<?php echo $field->db_field_name?>"
        value="<?php echo $value?>"/>
</span>
<?php
if ($value != '' && $required) {?>
    <input <?php echo $layout_params['field_styling']?> 
        class="<?php echo $layout_params['custom_class'].$required?> text_area"
        type="text"
        name="fi_<?php echo $field->db_field_name?>"
        value="<?php echo $value?>"/>
    <input class="text_area" type="file" name="fi_<?php echo $fName?>" value=""/>
    <?php
}
if ($value != '' && !$required) {?>
    <input <?php echo $layout_params['field_styling']?> 
        class="<?php echo $layout_params['custom_class'].$required?> text_area"
        type="text"
        name="fi_<?php echo $field->db_field_name?>"
        value="<?php echo $value?>"/>
    <label>Delete<input type="checkbox" name="delete_fi_<?php echo $fName?>" value="delete"></label>
    <?php
}