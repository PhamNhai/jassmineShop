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

$width_heigth = (!empty($layout_params["fields"][$fName]["options"]["width"]))? 'width:'.$layout_params["fields"][$fName]["options"]["width"].'px;' : 'width:100px;';
$width_heigth .= (!empty($layout_params["fields"][$fName]["options"]["height"]))? ' height:'.$layout_params["fields"][$fName]["options"]["height"].'px;' : 'height:100px;';
?>

<span class="cck-image-box" <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
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


    $image = show_image_cck($value, $field->options['width'], $field->options['height']);

    if(!isset($title)) $title = "";
    // print_r($value);
    $width = isset($field->options['width'])?"width:".$field->options['width']."px;":'';
    $height = isset($field->options['height'])?"height:".$field->options['height']."px;":'';
    echo '<img style="'.$width.$height.'" src="'.JURI::root().$image.'" alt="' . $title . '" />';

    if(!empty($layout_params['fields'][$fName.'_suffix'])){
      echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
    }
  }?>
</span>