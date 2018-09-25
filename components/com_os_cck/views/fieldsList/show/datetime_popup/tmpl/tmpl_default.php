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
$value = (isset($value[0]->data))?$value[0]->data : '';
// $format_date=(isset($layout_params["fields"][$fName.'_input_format']) && $layout_params["fields"][$fName.'_input_format']!="")?$layout_params["fields"][$fName.'_input_format'] : "Y-m-d";
// $format_time=(isset($layout_params["fields"][$fName.'_input_time_format']) && $layout_params["fields"][$fName.'_input_time_format']!="")?$layout_params["fields"][$fName.'_input_time_format'] : "H:i:s";

// $value = date($format_date." ".$format_time, strtotime($value));

  $format_date=(isset($layout_params["fields"][$fName.'_output_format']) && $layout_params["fields"][$fName.'_output_format']!="")?$layout_params["fields"][$fName.'_output_format'] : "Y-m-d H:i:s";

  $value = date($format_date, strtotime(data_transform_cck($value, $format_date)));
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
    <?php
    if ($value){
        if(!empty($layout_params['fields'][$fName.'_prefix'])){
            echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
        }
    
        if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
            $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
            $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
        }
        echo $value;
    
        if(!empty($layout_params['fields'][$fName.'_suffix'])){
            echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
        }
    }?>
</span>