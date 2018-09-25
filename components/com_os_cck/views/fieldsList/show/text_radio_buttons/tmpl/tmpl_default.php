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
$params = new JRegistry;
$params->loadString($field->params);
$value = (isset($value[0]->data))?$value[0]->data : '';
$return = '';
$arr = array();
$allowed_values = urlencode($params->get('allowed_value'));

if (strpos($allowed_values, '%0D%0A') !== false) $allowed_values = explode('%0D%0A', $allowed_values);
else if (strpos($allowed_values, '%0D') !== false) $allowed_values = explode('%0D', $allowed_values);
else if (strpos($allowed_values, '%0A') !== false) $allowed_values = explode('%0A', $allowed_values);
else return "Bad set 'allow value' for this field!";
foreach ($allowed_values as $item) {
    $key_value = explode('%7C', $item);
    if ($key_value[0] == urlencode($value)) $return = (isset($key_value[1]))?urldecode($key_value[1]):urldecode($key_value[0]);
}
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
    <?php
    if ($return){
        if(!empty($layout_params['fields'][$fName.'_prefix'])){
            echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
        }

        if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
            $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
            $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
        }
        echo urldecode($return);
    
        if(!empty($layout_params['fields'][$fName.'_suffix'])){
            echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
        }
    }?>
</span>