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
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$field_styling = (isset($layout_params['field_styling']))? $layout_params['field_styling'] : '';
$custom_class = (isset($layout_params['custom_class']))? $layout_params['custom_class'] : '';
$list = CAT_Utils::categoryArray('com_os_cck',$fromSearch);
if($fromSearch == 2)return $list;
$this_treename = '';
$childs_ids = $options = Array();
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
foreach ($list as $item) {
  if (array_key_exists($item->parent_id, $childs_ids))
    $childs_ids[$item->cid] = $item->cid;
}

$options[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_PLEASE_SELECT_OPTION'));

foreach ($list as $item) {
  if ($this_treename) {
    if (strpos($item->title, $this_treename) === false
        && array_key_exists($item->cid, $childs_ids) === false
    ) {
        $options[] = JHTML::_('select.option',$item->cid, $item->title);
    }
  } else {
    $options[] = JHTML::_('select.option',$item->cid, $item->title);
  }
}

//select type



$select_type = '';
$select_type_value = isset($layout_params['fields']['select_type_'.$fName]) ? $layout_params['fields']['select_type_'.$fName] : 1;

if($select_type_value > 1){
  $select_type  = 'multiple="multiple" size="'.$select_type_value.'"';
}


?><span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> ><?php
echo JHTML::_('select.genericlist',$options, 'categories[]', $field_styling.' id="catid" '. $select_type .' class="'.$custom_class.$required.' inputbox" onchange=""', 'value', 'text', $value);
?></span>