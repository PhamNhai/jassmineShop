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
$field_placeholder = (isset($lParams[$fName.'_placeholder'])) ? $lParams[$fName.'_placeholder'] : '';
$field_input_format = (isset($lParams[$fName.'_input_format'])) ? $lParams[$fName.'_input_format'] : 'd-m-Y';
$field_input_time_format = (isset($lParams[$fName.'_input_time_format'])) ? $lParams[$fName.'_input_time_format'] : 'H:i:s';
$field_output_format = (isset($lParams[$fName.'_output_format'])) ? $lParams[$fName.'_output_format'] : 'd-m-Y';

$fld_require = (isset($lParams[$fName.'_required'])) ? 'checked="true"' : '';

$field_step_time = (isset($lParams[$fName.'_step_time'])) ? $lParams[$fName.'_step_time'] : '15';

//publish checked default
$fld_published = (isset($lParams[$fName.'_published']) 
                    && $lParams[$fName.'_published'] == 'on') ? 'checked="true"' : '';
$fld_published = (!isset($lParams[$fName.'_published'])) ? 'checked="true"' : $fld_published;

//show name checked default
$fld_name_show = (isset($lParams['showName_'.$fName]) 
                    && $lParams['showName_'.$fName] == 'on') ? 'checked="true"' : '';
$fld_name_show = (!isset($lParams['showName_'.$fName])) ? 'checked="true"' : $fld_name_show;

$default = array();
$default[] = JHTML::_('select.option','EMPTY','Empty field');
$default[] = JHTML::_('select.option','CREATE','Сreation date/time');
$default[] = JHTML::_('select.option','CHANGE','Date/time of change');
$default_val = (isset($lParams[$fName.'_default_val'])) ? $lParams[$fName.'_default_val'] : '';
?>
<div id="options-field-<?php echo $fName?>">
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published"
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" <?php echo $fld_published?>>
    </div>
    <?php
    if($layoutType == 'rent_request_instance'){
        $types = array();
        $types[] = JHTML::_('select.option','default','Default');
        $types[] = JHTML::_('select.option','rent_from','Rent From');
        $types[] = JHTML::_('select.option','rent_to','Rent To');
        $default_type = (isset($lParams[$fName.'_field_type'])) ? $lParams[$fName.'_field_type'] : 'default';
        ?>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_FIELD_TYPE")?></label>
            <?php echo JHTML::_('select.genericlist', $types, 'fi_'.$fName.'_field_type', '','value', 'text',$default_type)?>
        </div>
        <?php
    }
    ?>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_REQUIRED")?></label>
        <input class="require-checkbox" type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_required" <?php echo $fld_require?>>
    </div>
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" 
        <?php echo $fld_name_show?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" <?php echo $fld_name_show?>>
    </div>
    <div>
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
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_PLACEHOLDER")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_placeholder"  value="<?php echo $field_placeholder?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_DATE_INPUT_FORMAT")?> <i title="<?php echo JText::_("COM_OS_CCK_DATE_FIELD_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_input_format"  value="<?php echo $field_input_format?>" >
    </div>
        <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_TIME_INPUT_FORMAT")?> <i title="<?php echo JText::_("COM_OS_CCK_TIME_FIELD_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_input_time_format"  value="<?php echo $field_input_time_format?>" >
    </div>

    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_STEP_TIME")?></label>
        <input type="number" size="4" name="fi_<?php echo $fName?>_step_time"  value="<?php echo $field_step_time?>" >
    </div>

    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_FIELD_DEFAULT")?></label>
        <?php echo JHTML::_('select.genericlist', $default, 'fi_'.$fName.'_default_val', '','value', 'text',$default_val)?>
    </div>
     <div style="display:none;">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_OUTPUT_FORMAT")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_output_format"  value="<?php echo $field_output_format?>" >
    </div>
</div>
