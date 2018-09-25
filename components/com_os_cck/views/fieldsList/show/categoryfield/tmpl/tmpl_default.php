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
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
    <?php

    $show_count = (isset($layout_params['fields'][$fName.'_show_count'])) ? $layout_params['fields'][$fName.'_show_count'] : '0';
    $max_lenght = (isset($layout_params['fields'][$fName.'_max_lenght'])) ? $layout_params['fields'][$fName.'_max_lenght'] : '250';

    if (isset($value[0]->title) && $value[0]->title != ''){
        if(!empty($layout_params['fields'][$fName.'_prefix'])){
            echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
        }

        if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
            $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
            $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
        }

        //  print_r($value);
        // exit;
        
        //category output
        if($show_count == 0 || $show_count > count($value)) $show_count = count($value);

        $category_output = '';
        for ($icount = 0; $icount < $show_count; $icount++) { 
            $category_output .= $value[$icount]->title;
            if($icount < ($show_count-1)){
              $category_output .= " ,";
            }

            if(strlen($category_output) > $max_lenght){
              $category_output = substr($category_output, 0, $max_lenght)."...";
            } 
        }

        echo $category_output;

        if(!empty($layout_params['fields'][$fName.'_suffix'])){
            echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
        }
    }?>
</span>