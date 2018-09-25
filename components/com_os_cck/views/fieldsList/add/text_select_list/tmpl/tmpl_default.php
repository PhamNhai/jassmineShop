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



if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
$fName = $field->db_field_name;
$fParams['type'] = isset($layout_params['fields']['search_'.$fName])?$layout_params['fields']['search_'.$fName]:'';
$params = new JRegistry;
$params->loadString($field->params);
if($fParams['type'] == 2){?>
    <input <?php echo $field_styling?> class="<?php echo $custom_class?>" type="hidden" name="os_cck_search_<?php echo $fName?>" value="on">
    <?php
    return;
}
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$arr = array();

$allowed_values = $params->get("allowed_value");

if (strpos($allowed_values, '\sprt') !== false){
    $allowed_values = explode('\sprt', $allowed_values);
}else{
    return "Bad set 'allow value' for this field!";
}

foreach ($allowed_values as $key => $allow_value) {
    $allowed_values[$key] = trim(urldecode($allow_value));
}


// if(!$allowed_values)
$arr[] = JHTML::_('select.option', 0, 'Select value', "value", "text");

foreach ($allowed_values as $item) {
    $key_value = explode('|', $item);
    if(!isset($key_value[0]) && isset($key_value[1])){
      $key_value[0] =$key_value[1];
    }
    if (isset($key_value[0]) && isset($key_value[1]))
        $arr[] = JHTML::_('select.option', $key_value[0], $key_value[1], "value", "text");
    else if (isset($key_value[0]) && !isset($key_value[1]))
        $arr[] = JHTML::_('select.option', $key_value[0], $key_value[0], "value", "text");
    else if (!isset($key_value[0]) && isset($key_value[1]))
        $arr[] = JHTML::_('select.option', $key_value[1], $key_value[1], "value", "text");
    else 
        return "Bad set 'allow value' for this field!";
}


$tag_name = "fi_".$field->db_field_name;
$key = 'value';
$text = 'text';
$value = ($value != '') ? $value : $params->get("default_value");
$field_json = json_encode($field);

?>
<span   

<?php if(isset($layout_params['fields']['description_'.$fName]) 
            && $layout_params['fields']['description_'.$fName]=='on' 
            && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
    rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
        <?php } ?> ><?php


//select type
$select_type = '';
// $select_type_value = isset($layout_params['fields']['select_type_'.$fName]) ? $layout_params['fields']['select_type_'.$fName] : 1;

// if($select_type_value > 1){
//   $select_type  = 'multiple="multiple" size="'.$select_type_value.'"';
// }


echo JHTML::_('select.genericlist', $arr, $tag_name, $layout_params['field_styling'].' class="'.$layout_params['custom_class'].$required.'" ' . $select_type, $key, $text, $value);

    if(isset($parent)){
      $unique_parent_id = $parent;
    }else{
      $unique_parent_id = '';
    }

?>
    
    <span class="select-params" data-select-params='<?php echo $field_json;?>' style="display: none" ></span>    
</span>
<!-- select-params -->


<script>
 
    addChildSelect(<?php echo $field_json;?>, "<?php echo $layout->lid;?>", "<?php echo $value;?>", "<?php echo JURI::root()?>","<?php echo $field->fid;?>","<?php echo $unique_parent_id;?>");

</script>