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

$description_show = (isset($lParams['description_'.$fName])) ? 'checked="true"' : '';
$field_prefix = (isset($lParams[$fName.'_prefix'])) ? $lParams[$fName.'_prefix'] : '';
$field_suffix = (isset($lParams[$fName.'_suffix'])) ? $lParams[$fName.'_suffix'] : '';
$fld_alias = (isset($lParams[$fName.'_alias'])) ? $lParams[$fName.'_alias'] : $field->field_name;
$field_tooltip = (isset($lParams[$fName.'_tooltip'])) ? $lParams[$fName.'_tooltip'] : '';
$access_selected = (isset($lParams['access_'.$fName])) ? $lParams['access_'.$fName] : '1';
$width  = (isset($lParams[$fName]['options']['width'])) ? $lParams[$fName]['options']['width'] : 100;
$height = (isset($lParams[$fName]['options']['height'])) ? $lParams[$fName]['options']['height'] : 100;

//publish checked default
$fld_published = (isset($lParams[$fName.'_published']) 
                    && $lParams[$fName.'_published'] == 'on') ? 'checked="true"' : '';
$fld_published = (!isset($lParams[$fName.'_published'])) ? 'checked="true"' : $fld_published;

//show name checked default
$fld_name_show = (isset($lParams['showName_'.$fName]) 
                    && $lParams['showName_'.$fName] == 'on') ? 'checked="true"' : '';
$fld_name_show = (!isset($lParams['showName_'.$fName])) ? 'checked="true"' : $fld_name_show;

$max_width = (isset($lParams[$fName.'_max_width'])) ? (int)$lParams[$fName.'_max_width'] : 0;
$max_height = (isset($lParams[$fName.'_max_height'])) ? (int)$lParams[$fName.'_max_height'] : 'jpg,png,bmp,jpeg,gif,tiff';
$allow_ext = (isset($lParams[$fName.'_allow_ext'])) ? $lParams[$fName.'_allow_ext'] : '';
$max_upload_size = (isset($lParams[$fName.'_max_upload_size'])) ? (int)$lParams[$fName.'_max_upload_size'] : 0;
$item_limit = (isset($lParams[$fName.'_item_limit'])) ? (int)$lParams[$fName.'_item_limit'] : 0;

?>
<div id="options-field-<?php echo $fName?>">
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published"
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" <?php echo $fld_published?>>
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" <?php echo $fld_name_show?>>
    </div>
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" 
        <?php echo $fld_name_show?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_ALIAS")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_alias"  value="<?php echo $fld_alias?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_DESCRIPTION")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_description_<?php echo $fName?>" <?php echo $description_show?>>
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_TOOLTIP")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_tooltip"  value="<?php echo $field_tooltip?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_PREFIX")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_prefix"  value="<?php echo $field_prefix?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_suffix"  value="<?php echo $field_suffix?>" >
    </div>
    <div>
        <label><?php echo JText::_('COM_OS_CCK_LABEL_WIDTH_HEIGHT')?>
            <i title="<?php echo JText::_("COM_OS_CCK_GALLERY_FIELD_FOR_ONE_IMAGE")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i>
        </label>
        <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][width]"  value="<?php echo $width?>" > x
        <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][height]"  value="<?php echo $height?>" >px
    </div>

    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_MAX_WIDTH")?></label>
        <input type="number" step="1" size="4" name="fi_<?php echo $fName?>_max_width"  value="<?php echo $max_width?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_MAX_HEIGHT")?></label>
        <input type="number" step="1" size="4" name="fi_<?php echo $fName?>_max_height"  value="<?php echo $max_height?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_ALLOW_EXT")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_allow_ext"  value="<?php echo $allow_ext?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_MAX_ITEM_LIMIT")?></label>
        <input type="number" step="1" size="4" name="fi_<?php echo $fName?>_item_limit"  value="<?php echo $item_limit?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_MAX_UPLOAD_SIZE")?></label>
        <input type="number" step="0.1" size="4" name="fi_<?php echo $fName?>_max_upload_size"  value="<?php echo $max_upload_size?>" >
    </div>

</div>