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


$fld_published = (isset($fields_from_params[$fName.'_published']) && $fields_from_params[$fName.'_published'] == 1) ? 'checked="true"' : '';
$fld_published = (!isset($fields_from_params[$fName.'_published'])) ? 'checked="true"' : $fld_published;

$field_prefix = (isset($fields_from_params[$fName.'_prefix'])) ? $fields_from_params[$fName.'_prefix'] : '';
$field_suffix = (isset($fields_from_params[$fName.'_suffix'])) ? $fields_from_params[$fName.'_suffix'] : '';
    
$fld_name_show = (isset($fields_from_params['showName_'.$fName])) ? 'checked="true"' : "";

$show_month = (isset($fields_from_params['showMonth_'.$fName])) ? 'checked="true"' : "";
$show_year = (isset($fields_from_params['showYear_'.$fName])) ? 'checked="true"' : "";

$calendar_view_selected = (isset($fields_from_params['calendar_view_'.$fName])) ? $fields_from_params['calendar_view_'.$fName] : 'small';
$access_selected = (isset($fields_from_params['access_'.$fName])) ? $fields_from_params['access_'.$fName] : '1';
$months_selected = (isset($fields_from_params['months_'.$fName])) ? $fields_from_params['months_'.$fName] : array(2);

$width   = (isset($fields_from_params[$fName]['options']['width'])) ? $fields_from_params[$fName]['options']['width'] : '';
$height  = (isset($fields_from_params[$fName]['options']['height'])) ? $fields_from_params[$fName]['options']['height'] : '';
$return = '';
$gtree = get_group_children_tree_cck();

$label_tag_selected = (isset($fields_from_params['label_tag_'.$fName])) ? $fields_from_params['label_tag_'.$fName] : "div";
$label_tags = array();
$label_tags[]  = JHTML::_('select.option','span','span');
$label_tags[]  = JHTML::_('select.option','div','div');

$calendar_views = array();
    $calendar_views[] = JHTML::_('select.option','small','small');
    $calendar_views[] = JHTML::_('select.option','large','large');
    $calendar_views[] = JHTML::_('select.option','schedule','schedule');
    

$field_output_format = (isset($fields_from_params[$fName.'_output_format'])) ? $fields_from_params[$fName.'_output_format'] : 'Y-m-d';
$field_output_header_format = (isset($fields_from_params[$fName.'_output_header_format'])) ? $fields_from_params[$fName.'_output_header_format'] : 'Y-m-d';

$show_time = (isset($fields_from_params[$fName.'_show_time'])) ? $fields_from_params[$fName.'_show_time'] : '1';

$show_header_date_time = (isset($fields_from_params[$fName.'_show_header_date_time'])) ? $fields_from_params[$fName.'_show_header_date_time'] : '1';

$event_title = (isset($fields_from_params[$fName.'_event_title'])) ? $fields_from_params[$fName.'_event_title'] : '-1';

$schedule_instance_lid = isset($fields_from_params[$fName.'_schedule_instance_lid']) ? $fields_from_params[$fName.'_schedule_instance_lid'] : '-1';

$selected_link = isset($fields_from_params[$fName.'_link_field']) ? $fields_from_params[$fName.'_link_field'] : '';


$yes_no = array();
    $yes_no[]  = JHTML::_('select.option','0','no');
    $yes_no[]  = JHTML::_('select.option','1','yes');

$months = array();
    $months[]  = JHTML::_('select.option','1','last');
    $months[]  = JHTML::_('select.option','2','current');
    $months[]  = JHTML::_('select.option','3','next1');
    $months[]  = JHTML::_('select.option','4','next2');
    $months[]  = JHTML::_('select.option','5','next3');
    $months[]  = JHTML::_('select.option','6','next4');
    $months[]  = JHTML::_('select.option','7','next5');
    $months[]  = JHTML::_('select.option','8','next6');
    $months[]  = JHTML::_('select.option','9','next7');
    $months[]  = JHTML::_('select.option','10','next8');
    $months[]  = JHTML::_('select.option','11','next9');
    $months[]  = JHTML::_('select.option','12','next10');
    $months[]  = JHTML::_('select.option','13','next11');
    $months[]  = JHTML::_('select.option','14','next12');


$eventTitleFields = $entity->getEventTitleFieldList();

$event_title_options = array();
$event_title_options[] = JHTML::_('select.option','-1','default');

foreach($eventTitleFields as $title) {
    $event_title_options[] = JHTML::_('select.option',$title['value'],$title['text']);
}


$db = JFactory::getDbo();
$query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
      ."\n WHERE c.type='instance' "
      ."\n AND c.published = '1' AND c.fk_eid = {$layout->fk_eid}";
$db->setQuery($query);
$instance_layout_list = $db->loadObjectList('lid');

$instanceLayout = array();
$instanceLayout[] = JHTML::_('select.option','-1','default');

foreach ($instance_layout_list as $value) {
  $instanceLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
}


 //get link for instance in schedule
$field_for_link = array();
$fields = $entity->getFieldList();
foreach ($fields as $value) {
    if($value->field_type == 'text_textfield' || $value->field_type == 'datetime_popup'
      || $value->field_type == 'decimal_textfield' || $value->field_type == 'imagefield'
      || $value->field_type == 'text_radio_buttons' || $value->field_type == 'text_select_list'
      || $value->field_type == 'text_single_checkbox_onoff' || $value->field_type == 'text_textarea')
    {
      $field_for_link[]  = JHTML::_('select.option',$value->fid,$value->field_name);
    }
}


// $shedule_instance_lid = 
// $schedule_instance_options
?>
<div id="options-field-<?php echo $fName?>">

    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" 
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" 
        <?php echo $fld_published?> value="1">
    </div>
    

<?php if($fName == 'calendar_month_year'):?>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_MONTH")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showMonth_<?php echo $fName?>" <?php echo $show_month?>>
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_YEAR")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showYear_<?php echo $fName?>" <?php echo $show_year?>>
    </div>

<?php endif;?>

<?php if($fName == 'calendar_table'):?>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_MONTH_LIST")?> <i title="<?php echo JText::_("COM_OS_CCK_LABEL_SHOW_MONTH_LIST_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
            <?php echo JHTML::_('select.genericlist', $months, 'fi_months_' . $fName. '[]', 'multiple="true"','value', 'text',$months_selected)?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_CALENDAR_VIEW")?> <i title="<?php echo JText::_("COM_OS_CCK_LABEL_CALENDAR_VIEW_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
            <?php echo JHTML::_('select.genericlist', $calendar_views, 'fi_calendar_view_' . $fName, 'size="1" class="inputbox" ','value', 'text',$calendar_view_selected)?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_EVENT_TITLE")?></label>
            <?php echo JHTML::_('select.genericlist', $event_title_options,  'fi_'. $fName .'_event_title', '','value', 'text',$event_title)?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_LABEL_SHOW_TIME")?></label>
            <?php echo JHTML::_('select.genericlist',$yes_no, 'fi_'. $fName .'_show_time',
                                'size="1" class="inputbox" ', 'value', 'text', $show_time);?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_OUTPUT_TIME_FORMAT")?> <i title="<?php echo JText::_("COM_OS_CCK_LABEL_SHOW_OUTPUT_TIME_FORMAT_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>_output_format"  value="<?php echo $field_output_format?>" >
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHEDULE_INSTANCE")?> <i title="<?php echo JText::_("COM_OS_CCK_LABEL_SHEDULE_INSTANCE_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
            <?php echo JHTML::_('select.genericlist', $instanceLayout,  'fi_'. $fName .'_schedule_instance_lid', '','value', 'text', $schedule_instance_lid)?>
        </div>
        <br>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_LINK_FIELD_SCHEDULE")?>
                <i title="<?php echo JText::_("COM_OS_CCK_LABEL_LINK_FIELD_SCHEDULE_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i>
            </label>
            <?php echo JHTML::_('select.genericlist', $field_for_link,  'fi_'. $fName .'_link_field[]', 'multiple="true" ','value', 'text', $selected_link)?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_HEADER_DATE_TIME")?></label>
            <?php echo JHTML::_('select.genericlist',$yes_no, 'fi_'. $fName .'_show_header_date_time',
                                'size="1" class="inputbox" ', 'value', 'text', $show_header_date_time);?>
        </div>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_OUTPUT_HEADER_FORMAT")?> <i title="<?php echo JText::_("COM_OS_CCK_DATE_FIELD_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
            <input type="text" size="4" name="fi_<?php echo $fName?>_output_header_format"  value="<?php echo $field_output_header_format?>" >
        </div>

<?php endif;?>

<?php if($fName == 'calendar_pagination' || $fName == 'calendar_month_year' || $fName == 'calendar_table'):?>

        <div style="clear:both">
            <label><?php echo JText::_("COM_OS_CCK_LABEL_LABEL_SHELL")?></label>
            <?php echo JHTML::_('select.genericlist',$label_tags, 'fi_label_tag_'. $fName,
                                'size="1" class="inputbox" ', 'value', 'text', $label_tag_selected);?>
        </div>
   
<?php endif; ?>



</div>

<script type="text/javascript">
    
jQuerCCK(document).ready(function() {

    jQuerCCK('select[name^=fi_calendar_view_]').trigger('change');

    jQuerCCK('select[name^=fi_calendar_view_]').unbind('change');
    jQuerCCK('select[name^=fi_calendar_view_]').change(function(event) {

       if(jQuerCCK(this).val() == 'small'){
            jQuerCCK('[name=fi_calendar_table_event_title]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_output_format]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_output_header_format]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_show_time]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_show_header_date_time]').parent('div').slideUp();

            jQuerCCK('[name=fi_calendar_table_schedule_instance_lid]').parent('div').slideUp();
            jQuerCCK('[name^=fi_calendar_table_link_field]').parent('div').slideUp();
       }else if(jQuerCCK(this).val() == 'large'){
            jQuerCCK('[name=fi_calendar_table_event_title]').parent('div').slideDown();
            jQuerCCK('[name=fi_calendar_table_output_format]').parent('div').slideDown();
            jQuerCCK('[name=fi_calendar_table_output_header_format]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_show_time]').parent('div').slideDown();
            jQuerCCK('[name=fi_calendar_table_show_header_date_time]').parent('div').slideUp();

            jQuerCCK('[name=fi_calendar_table_schedule_instance_lid]').parent('div').slideUp();
            jQuerCCK('[name^=fi_calendar_table_link_field]').parent('div').slideUp();
       }else if(jQuerCCK(this).val() == 'schedule'){
            jQuerCCK('[name=fi_calendar_table_event_title]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_output_format]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_output_header_format]').parent('div').slideDown();
            jQuerCCK('[name=fi_calendar_table_show_time]').parent('div').slideUp();
            jQuerCCK('[name=fi_calendar_table_show_header_date_time]').parent('div').slideDown();

            jQuerCCK('[name=fi_calendar_table_schedule_instance_lid]').parent('div').slideDown();
            jQuerCCK('[name^=fi_calendar_table_link_field]').parent('div').slideDown();
       }

    });
    
});


</script>