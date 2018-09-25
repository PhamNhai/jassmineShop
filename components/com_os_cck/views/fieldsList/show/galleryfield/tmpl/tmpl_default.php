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


//$i = substr(microtime(),2,4);

  //print_r($i);
// // exit;

$fName = $field->db_field_name;

$width_heigth = (!empty($layout_params["fields"][$fName]["options"]["width"]))? 'width:'.$layout_params["fields"][$fName]["options"]["width"].'px;' : 'width:100px;';
$width_heigth .= (!empty($layout_params["fields"][$fName]["options"]["height"]))? ' height:'.$layout_params["fields"][$fName]["options"]["height"].'px;' : 'height:100px;';
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <?php


  if(!empty($value[0]) && $images = json_decode($value[0]->data)){
    if(!empty($layout_params['fields'][$fName.'_prefix'])){
      echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
    }
  
    if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
        $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
        $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
    }
    ?>
    <div class="gallery_<?php echo $fName?>" id="gallery_<?php echo $fName?>">
    <?php

    // print_r($images);
    // exit;
    //bug with $title variable!!!

      foreach ($images as $image){

          // if (!isset($_REQUEST['view']) || $_REQUEST['view'] != 'category')
            echo '<a href="'.JURI::root().'images/com_os_cck'.$field->fid.'/original/'.$image->file.'" data-lightbox="roadtrip_'.$fName.$moduleId.'" title="' . $image->name . '">';
            echo '<img style="'.$width_heigth.'" src="'.JURI::root().'images/com_os_cck'.$field->fid.'/thumbnail/'.$image->file.'" alt="' . $image->alt . '"/>';
          // if (!isset($_REQUEST['view']) || $_REQUEST['view'] != 'category') echo'</a>';
            echo'</a>';
          // if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'category') break;
      }?>
    </div>
    <?php

    if(!empty($layout_params['fields'][$fName.'_suffix'])){
      echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
    }
  }?>
</span>