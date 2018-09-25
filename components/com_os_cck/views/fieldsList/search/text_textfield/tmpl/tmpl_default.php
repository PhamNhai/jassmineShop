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

?><span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> ><?php

if($fParams['type'] == 1){?>
    <input <?php echo $field_styling?> class="<?php echo $custom_class?>" type="checkbox" name="os_cck_search_<?php echo $fName?>" checked="checked" value="on">
    <?php
} elseif($fParams['type'] == 2){?>
    <input <?php echo $field_styling?> class="<?php echo $custom_class?>" type="hidden" name="os_cck_search_<?php echo $fName?>" value="on">
    <?php
}

?></span>