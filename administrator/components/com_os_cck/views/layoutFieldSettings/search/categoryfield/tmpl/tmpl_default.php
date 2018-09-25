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
$ftype = $field->field_type;
$showSearchOptions = array();
$fld_name_show = (isset($lParams['showName_'.$fName])) ? 'checked="true"' : "";
$description_show = (isset($lParams['description_'.$fName]))? 'checked="true"' : '';
$field_prefix = (isset($lParams[$fName.'_prefix']))? $lParams[$fName.'_prefix'] : '';
$field_suffix = (isset($lParams[$fName.'_suffix']))? $lParams[$fName.'_suffix'] : '';
$fld_alias = (isset($lParams[$fName.'_alias']))? $lParams[$fName.'_alias'] : $field->field_name;
$field_tooltip = (isset($lParams[$fName.'_tooltip']))? $lParams[$fName.'_tooltip'] : '';
$access_selected = (isset($lParams['access_'.$fName]))? $lParams['access_'.$fName] : '1';
$select_type = (isset($lParams['select_type_'.$fName]))? $lParams['select_type_'.$fName] : '1';


if(isset($lParams[$fName.'_nofirst']) && $lParams[$fName.'_nofirst'] == 'nofirst'){
    $fld_published = (isset($lParams[$fName.'_published'])) ? 'checked="true"' : '';
}else{
    $fld_published = (isset($field->published)) ? 'checked="true"' : '';
    $fld_published = (isset($lParams[$fName.'_published'])) ? 'checked="true"' : $fld_published;
}
//search params
if($ftype == 'decimal_textfield'){
  $showSearchOptions[] = JHTML::_('select.option','1','Show search by range');
  $showSearchOptions[] = JHTML::_('select.option','2','Show search by value');
  $showSearchOptions[] = JHTML::_('select.option','3','Search by value');
  $showSearchOptions[] = JHTML::_('select.option','0','Hide');
}elseif($ftype == 'text_single_checkbox_onoff'){
  $showSearchOptions[] = JHTML::_('select.option','1','Show');
  $showSearchOptions[] = JHTML::_('select.option','0','Hide');
}else{
  $showSearchOptions[] = JHTML::_('select.option','1','Show');
  $showSearchOptions[] = JHTML::_('select.option','2','Search');
  $showSearchOptions[] = JHTML::_('select.option','0','Hide');
}

$select_type_option = [];

$select_type_option[] = JHTML::_('select.option','1','Single select');
$select_type_option[] = JHTML::_('select.option','2','Size 2');
$select_type_option[] = JHTML::_('select.option','3','Size 3');
$select_type_option[] = JHTML::_('select.option','4','Size 4');
$select_type_option[] = JHTML::_('select.option','5','Size 5');
$select_type_option[] = JHTML::_('select.option','6','Size 6');
$select_type_option[] = JHTML::_('select.option','7','Size 7');
$select_type_option[] = JHTML::_('select.option','8','Size 8');
$select_type_option[] = JHTML::_('select.option','9','Size 9');
$select_type_option[] = JHTML::_('select.option','10','Size 10');

?>
<div id="options-field-<?php echo $fName?>">
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" <?php echo $fld_published?>>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_nofirst" value="nofirst">
    </div>
    <div>
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
        <label><?php echo JText::_("COM_OS_CCK_LAYOUT_SELECT_TYPE")?></label>
        <?php echo JHTML::_('select.genericlist', $select_type_option, 'fi_select_type_'.$fName, '','value', 'text',$select_type)?>
    </div>

    <input type="hidden" value="<?php echo $field->fid?>" name="os_cck_search_<?php echo $fName?>[fid]">
    <input type="hidden" value="1" name="os_cck_search_<?php echo $fName?>[type]">
</div>