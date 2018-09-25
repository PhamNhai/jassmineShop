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
$fParams['type'] = isset($layout_params['fields']['search_'.$fName])?$layout_params['fields']['search_'.$fName]:'';
$params = new JRegistry;
$params->loadString($field->params);
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
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
$allowed_values = urlencode($allowed_values);
if (strpos($allowed_values, '%0D%0A') !== false)
    $allowed_values = explode('%0D%0A', $allowed_values);
else if (strpos($allowed_values, '%0D') !== false)
    $allowed_values = explode('%0D', $allowed_values);
else if (strpos($allowed_values, '%0A') !== false)
    $allowed_values = explode('%0A', $allowed_values);
else
    return "Bad set 'allow value' for this field!";
foreach ($allowed_values as $item) {
    $key_value = explode('%7C', $item);
    if (isset($key_value[0]) && isset($key_value[1]))
        $arr[] = JHTML::_('select.option', urldecode($key_value[0]), urldecode($key_value[1]), "value", "text");
    else if (isset($key_value[0]) && !isset($key_value[1]))
        $arr[] = JHTML::_('select.option', urldecode($key_value[0]), urldecode($key_value[0]), "value", "text");
    else if (!isset($key_value[0]) && isset($key_value[1]))
        $arr[] = JHTML::_('select.option', urldecode($key_value[1]), urldecode($key_value[1]), "value", "text");
    else
        return "Bad set 'allow value' for this field!";
}

$key = 'value';
$text = 'text';
$default_values = explode('|',$params->get("default_value"));
$value = (isset($value))?$value:$default_values[0];

?><span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> ><?php

echo JHTML::_('select.radiolist', $arr, "os_cck_search_".$fName, $field_styling.' class="'.$custom_class.$required.'"', $key, $text, $value);

?></span>