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

$access_selected = (isset($fields_from_params['access_'.$fName])) ? $fields_from_params['access_'.$fName] : '1';

$calendar_selected = (isset($fields_from_params[$fName.'_calendars_list'])) ? $fields_from_params[$fName.'_calendars_list'] : array('google','ical','outlook','yahoo');

$label_tag_selected = (isset($fields_from_params['label_tag_'.$fName])) ? $fields_from_params['label_tag_'.$fName] : "div";

$event_title = (isset($fields_from_params[$fName.'_event_title'])) ? $fields_from_params[$fName.'_event_title'] : '-1';
$event_date_start = (isset($fields_from_params[$fName.'_event_date_start'])) ? $fields_from_params[$fName.'_event_date_start'] : '-1';
$event_date_end = (isset($fields_from_params[$fName.'_event_date_end'])) ? $fields_from_params[$fName.'_event_date_end'] : '-1';
$event_description = (isset($fields_from_params[$fName.'_event_description'])) ? $fields_from_params[$fName.'_event_description'] : '-1';
$event_location = (isset($fields_from_params[$fName.'_event_location'])) ? $fields_from_params[$fName.'_event_location'] : array('-1');

$event_items_location = (isset($fields_from_params[$fName.'_event_items_location'])) ? $fields_from_params[$fName.'_event_items_location'] : array('address');


$label_tags = array();
$label_tags[]  = JHTML::_('select.option','span','span');
$label_tags[]  = JHTML::_('select.option','div','div');

$toCalendar = array();
    $toCalendar[]  = JHTML::_('select.option','google','Google Calendar');
    $toCalendar[]  = JHTML::_('select.option','ical','ICal Calendar');
    $toCalendar[]  = JHTML::_('select.option','outlook','Outlook Calendar');
    $toCalendar[]  = JHTML::_('select.option','yahoo','Yahoo Calendar');

$locationItemsOptions = array();

    $locationItemsOptions[] = JHTML::_('select.option','address','Address');
    $locationItemsOptions[] = JHTML::_('select.option','country','Country');
    $locationItemsOptions[] = JHTML::_('select.option','region','Region');
    $locationItemsOptions[] = JHTML::_('select.option','city','City');
    $locationItemsOptions[] = JHTML::_('select.option','zipcode','ZipCode');

$gtree = get_group_children_tree_cck();

$titleField = $entity->getEventTitleFieldList();
$dateField = $entity->getDatePopupFieldList();
$descriptionField = $entity->getTextAreaFieldList();
$locationField = $entity->getLocationFieldList();

//title list
$titleOptions = createSettingsOptions($titleField, true);
//date list
$dateOptions = createSettingsOptions($dateField, true);
//description list
$descriptionOptions = createSettingsOptions($descriptionField, true);
//location list
$locationOptions = createSettingsOptions($locationField, true);
?>
<div id="options-field-<?php echo $fName?>">
    

    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" 
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" 
        <?php echo $fld_published?> value="1">
    </div>

    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_LABEL_SHELL")?></label>
        <?php echo JHTML::_('select.genericlist',$label_tags, 'fi_label_tag_'. $fName,
                            'size="1" class="inputbox" ', 'value', 'text', $label_tag_selected);?>
    </div>

        
</div>