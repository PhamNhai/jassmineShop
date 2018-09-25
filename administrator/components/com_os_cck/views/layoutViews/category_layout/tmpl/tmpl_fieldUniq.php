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
// $fld_name_show = (isset($fields_from_params['showName_'.$fName])) ? 'checked="true"' : "";
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
///////////////
$description_show = (isset($fields_from_params['description_'.$fName])) ? 'checked="true"' : '';
$field_tooltip = (isset($fields_from_params[$fName.'_tooltip'])) ? $fields_from_params[$fName.'_tooltip'] : '';

$max_lenght = (isset($fields_from_params[$fName.'_max_lenght'])) ? $fields_from_params[$fName.'_max_lenght'] : '250';

$label_tags = array();
$label_tags[]  = JHTML::_('select.option','span','span');
$label_tags[]  = JHTML::_('select.option','div','div');


if($fName != 'category_image' && $fName !="joom_pagination" && $fName !="joom_alphabetical"){
    $label_tags[]  = JHTML::_('select.option','h1','h1');
    $label_tags[]  = JHTML::_('select.option','h2','h2');
    $label_tags[]  = JHTML::_('select.option','h3','h3');
    $label_tags[]  = JHTML::_('select.option','h4','h4');
    $label_tags[]  = JHTML::_('select.option','h5','h5');
    $label_tags[]  = JHTML::_('select.option','label','label');
}
if($fName == 'category_image'){
    $image_width = (isset($fields_from_params[$fName]['width'])) ? $fields_from_params[$fName]['width'] : '50';
    $image_height = (isset($fields_from_params[$fName]['height'])) ? $fields_from_params[$fName]['height'] : '50';
}
$field_tag_selected = (isset($fields_from_params['field_tag_'.$fName])) ? $fields_from_params['field_tag_'.$fName] : "span";
$tags = array();
$tags[]  = JHTML::_('select.option','span','span');
$tags[]  = JHTML::_('select.option','div','div');
if($fName != 'category_image' && $fName !="joom_pagination" && $fName !="joom_alphabetical"){
    $tags[]  = JHTML::_('select.option','h1','h1');
    $tags[]  = JHTML::_('select.option','h2','h2');
    $tags[]  = JHTML::_('select.option','h3','h3');
    $tags[]  = JHTML::_('select.option','h4','h4');
    $tags[]  = JHTML::_('select.option','h5','h5');
    $tags[]  = JHTML::_('select.option','label','label');
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
    if($fName == 'category_order_by'){?>

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


<?php  } ?>

<?php if($fName =="category_title" || $fName =="category_image"){?>

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
      <input type="text" size="4" name="fi_<?php echo $fName?>_prefix" value="<?php echo $field_prefix?>">
    </div>
    <div>
      <label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label>
      <input type="text" size="4" name="fi_<?php echo $fName?>_suffix" value="<?php echo $field_suffix?>">
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_MAXLENGTH")?></label>
        <input type="number" min="0" size="4" name="fi_<?php echo $fName?>_max_lenght"  value="<?php echo $max_lenght?>" >
    </div>
    <span class="clearfix" ></span>
<?php } ?>

<?php if($fName =="joom_instance_count"){?>
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
      <input type="text" size="4" name="fi_<?php echo $fName?>_prefix" value="<?php echo $field_prefix?>">
    </div>
    <div>
      <label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label>
      <input type="text" size="4" name="fi_<?php echo $fName?>_suffix" value="<?php echo $field_suffix?>">
    </div>
    <span class="clearfix" ></span>
<?php } ?>

<?php if($fName =="category_map" || $fName =="category_description"){?>

    <div>
      <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_DESCRIPTION")?></label>
      <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_description_<?php echo $fName?>" <?php echo $description_show?>>
    </div>
    <div>
      <label><?php echo JText::_("COM_OS_CCK_LABEL_TOOLTIP")?></label>
      <input type="text" size="4" name="fi_<?php echo $fName?>_tooltip"  value="<?php echo $field_tooltip?>" >
    </div>

<?php } ?>

<?php

   if($fName !="joom_pagination" && $fName != 'category_image'){?>
    <!--     <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_LABEL_SHELL")?></label>
            <?php echo JHTML::_('select.genericlist',$label_tags, 'fi_label_tag_'.$fName,
                    'size="1" class="inputbox" ', 'value', 'text', $label_tag_selected)?>
        </div> -->
        <?php
    }
    if($fName != 'category_map' && $fName != 'category_order_by' && $fName != 'category_image'){ ?>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_FIELD_SHELL")?></label>
            <?php echo JHTML::_('select.genericlist',$tags, 'fi_field_tag_'.$fName,
                    'size="1" class="inputbox" ', 'value', 'text', $field_tag_selected)?>
        </div>
        <?php
    }
    if($fName == 'category_image' && $fName !="joom_pagination"){?>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_CATEGORY_UNIQUE_FIELD_CAT_IMAGE_WIDTH")?></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>[width]"  value="<?php echo $image_width?>" > px
        </div>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_CATEGORY_UNIQUE_FIELD_CAT_IMAGE_HEIGHT")?></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>[height]"  value="<?php echo $image_height?>" > px
        </div>
        <?php
    }
    if($fName !="joom_pagination"){?>
      <!--   <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
            <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" <?php echo $fld_name_show?>>
        </div> -->
   
        <?php
    }
    if($fName == 'category_map'){ ?>
        <div>
            <label><?php echo JText::_('COM_OS_CCK_LABEL_WIDTH_HEIGHT')?></label>
            <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][width]" value="<?php echo $width?>"> x
            <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][height]" value="<?php echo $height?>">px
        </div>
        <?php
    }
    if($fName != 'joom_alphabetical'){    ?>
   
    <?php } ?>    
</div>