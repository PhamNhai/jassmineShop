<?php
defined('_JEXEC') or die('Restricted access');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
$fName = $field->db_field_name;
$field_prefix = (isset($fields_from_params[$fName.'_prefix'])) ? $fields_from_params[$fName.'_prefix'] : '';
$field_suffix = (isset($fields_from_params[$fName.'_suffix'])) ? $fields_from_params[$fName.'_suffix'] : '';
$fld_name_show = (isset($fields_from_params['showName_'.$fName])) ? 'checked="true"' : "";
$access_selected = (isset($fields_from_params['access_'.$fName])) ? $fields_from_params['access_'.$fName] : '1';


$width   = (isset($fields_from_params[$fName]['options']['width'])) ? $fields_from_params[$fName]['options']['width'] : '';
$height  = (isset($fields_from_params[$fName]['options']['height'])) ? $fields_from_params[$fName]['options']['height'] : '';

$selected_orderFields = (isset($fields_from_params['order_by_fields_'.$fName])) ? $fields_from_params['order_by_fields_'.$fName] : '';
$sortType_selected = (isset($fields_from_params['sortType_'.$fName])) ? $fields_from_params['sortType_'.$fName] : '';

$selected_orderFieldsDefault = (isset($fields_from_params['indexed_'.$fName])) ? $fields_from_params['indexed_'.$fName] : '';

$fld_published = (isset($fields_from_params[$fName.'_published']) 
                    && $fields_from_params[$fName.'_published'] == 'on') ? 'checked="true"' : '';
$fld_published = (!isset($fields_from_params[$fName.'_published'])) ? 'checked="true"' : $fld_published;

$return = '';
$gtree = get_group_children_tree_cck();

$label_tag_selected = (isset($fields_from_params['label_tag_'.$fName])) ? $fields_from_params['label_tag_'.$fName] : "span";
$label_tags = array();
$label_tags[]  = JHTML::_('select.option','span','span');
$label_tags[]  = JHTML::_('select.option','div','div');
if($fName !="joom_pagination" && $fName !="joom_alphabetical"){
    $label_tags[]  = JHTML::_('select.option','h1','h1');
    $label_tags[]  = JHTML::_('select.option','h2','h2');
    $label_tags[]  = JHTML::_('select.option','h3','h3');
    $label_tags[]  = JHTML::_('select.option','h4','h4');
    $label_tags[]  = JHTML::_('select.option','h5','h5');
    $label_tags[]  = JHTML::_('select.option','label','label');
}


//sort fields
$idxFields = $entity->getIndexedFieldList();
$field_for_order = array();
$field_for_order[] = JHTML::_('select.option','ceid','Id');

foreach($idxFields as $idx) {
    $field_for_order[]  = JHTML::_('select.option',$idx['value'],$idx['text']);
}
if(count($field_for_order) <= 1){
    $orderByFields = 'Need at least 2 sortable fields';
}else{
    $orderByFields =  JHTML::_('select.genericlist',$field_for_order, 'fi_order_by_fields_' . $fName. '[]',
                      'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_orderFields);
}


// order by
$orderByFieldsDefault =  JHTML::_('select.genericlist',$field_for_order, 'fi_indexed_' . $fName,
                      ' class="inputbox" ', 'value', 'text', $selected_orderFieldsDefault);
$sortType_option = array(JHTML::_('select.option','ASC','ASC'), JHTML::_('select.option','DESC','DESC'));
$sortType = JHTML::_('select.genericlist', $sortType_option, 'fi_sortType_' . $fName, '','value', 'text',$sortType_selected);


?>
<div id="options-field-<?php echo $fName?>">
    <?php
    if($fName =="all_instance_order_by"){?>
    

    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published"
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" <?php echo $fld_published?>>
    </div>

        <div class="category-options">
            <label><?php echo JText::_('COM_OS_CCK_LAYOUT_ORDER_BY_FIELDS_DEFAULT'); ?></label>
            <?php echo $orderByFieldsDefault; ?>
        </div>
        <div class="category-options">
            <label><?php echo JText::_('COM_OS_CCK_LAYOUT_ORDER_BY'); ?></label>
            <?php echo $sortType; ?>
        </div>
        <div class="category-options">
            <label><?php echo JText::_('COM_OS_CCK_LAYOUT_ORDER_BY_FIELDS'); ?>
                <i title="<?php echo JText::_("COM_OS_CCK_LAYOUT_ORDER_BY_FIELDS_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i>
            </label>
            <?php echo $orderByFields; ?>
        </div>
    
    <?php 
    }

    if($fName !="joom_pagination" && $fName !="joom_alphabetical"){?>


        <?php
    }
    if($fName !="all_instance_order_by" && $fName !="all_instance_map"){
    ?>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_FIELD_SHELL")?></label>
            <?php echo JHTML::_('select.genericlist',$label_tags, 'fi_label_tag_'. $fName,
                                'size="1" class="inputbox" ', 'value', 'text', $label_tag_selected);?>
        </div>
    <?php
    }

    if($fName !="joom_pagination" 
        && $fName !="joom_alphabetical" 
        && $fName !="all_instance_order_by" 
        && $fName !="all_instance_map"){ ?>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
            <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" <?php echo $fld_name_show?>>
        </div>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_PREFIX")?></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>_prefix" value="<?php echo $field_prefix?>" >
        </div>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>_suffix" value="<?php echo $field_suffix?>" >
        </div>
        <?php
    }else{ ?>


   <!--      <div style="display:none!important;">
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
            <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>">
        </div> -->
        <?php
    } 
    if($fName == 'all_instance_map'){?>
        <div>
            <label><?php echo JText::_('COM_OS_CCK_LABEL_WIDTH_HEIGHT')?></label>
            <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][width]" value="<?php echo $width?>"> x
            <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][height]" value="<?php echo $height?>"> px
        </div>
        <?php
    }?>
</div>