<?php
defined('_JEXEC') or die('Restricted access');

/**
* @version 1.0
* @package OS CCK
* @copyright 2015 OrdaSoft
* @author 2015 Andrey Kvasnevsliy(akbet@mail.ru)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit 
*/
?>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function(){ 
    if(!document.getElementById('instance').value){
      document.getElementById('instance').parentNode.parentNode.style.display = 'none';
    }
    document.getElementById('layout_type').parentNode.parentNode.style.display = 'none';
    if(document.getElementById('layout_type').value == 'add_instance' 
        || document.getElementById('layout_type').value == 'request_instance'){
      document.getElementById('jform_params_show_type').parentNode.parentNode.style.display = '';
    }else{
      document.getElementById('jform_params_show_type').parentNode.parentNode.style.display = 'none';
    }
  }, false);
</script>
<?php
JHTML::_('behavior.modal', 'a.modal-button');
$doc = JFactory::getDocument();
$doc->addScript(JURI::root().'/components/com_os_cck/assets/js/functions.js');
$doc->addStyleSheet(JURI::root().'/components/com_os_cck/assets/css/admin_style.css');

  class JFormFieldLayouttype extends JFormField{
    protected function getInput(){
      $db = JFactory::getDBO();
      $module_id = JRequest::getVar('id','');
      $params_string = '';
      if($module_id !=''){//get current module's params; 
        $query = "SELECT params FROM #__modules WHERE id=".$module_id." ";
        $db->setQuery($query);
        $params_string = $db->loadResult();
      }
      if(version_compare(JVERSION, '3.0', 'ge')) {
        $params = new JRegistry;
        $params->loadString($params_string);
      }
      else {
        $params = new JParameter($params_string);
      }
      $lay_type = ($params->get('layout_type',''))? $params->get('layout_type') : '';
      return '<input id="layout_type" type="hidden" name='.$this->name.' value="'.$lay_type.'">';
    }
}

class JFormFieldLayout extends JFormField{
  protected function getInput(){
    $db = JFactory::getDBO();
    $module_id = JRequest::getVar('id','');
    $params_string = '';
    if($module_id !=''){//get current module's params; 
      $query = "SELECT params FROM #__modules WHERE id=".$module_id." ";
      $db->setQuery($query);
      $params_string = $db->loadResult();
    }
    if(version_compare(JVERSION, '3.0', 'ge')) {
      $params = new JRegistry;
      $params->loadString($params_string);
    }
    else {
      $params = new JParameter($params_string);
    }
    $link = JRoute::_('index.php?option=com_os_cck&task=manage_layout_modal&module_id='.$module_id.'&tmpl=component');
    $rel="{handler: 'iframe', size: {x: 900, y: 550}}";
    $lid = ($params->get('layout',''))? $params->get('layout') : '';
    $html = '<input id="selected_layout" type="text" name="'.$this->name.'" value="'.$lid.'" readonly>';
    $html .= '<div class="fixedform">'.
                '<a class="btn modal-button" href="'.$link.'" rel="'.$rel.'">'.
                  'Select layout'.
                '</a>'.
              '</div>';
    return $html;
  }
}

class JFormFieldInstance extends JFormField{
  protected function getInput(){
    $db = JFactory::getDBO();
    $module_id = JRequest::getVar('id','');
    $params_string = '';
    if($module_id !=''){//get current module's params; 
      $query = "SELECT params FROM #__modules WHERE id=".$module_id." ";
      $db->setQuery($query);
      $params_string = $db->loadResult();
    }
    if(version_compare(JVERSION, '3.0', 'ge')) {
      $params = new JRegistry;
      $params->loadString($params_string);
    }
    else {
      $params = new JParameter($params_string);
    }
    $selected_entity = $eiid = '';
    if($params->get('layout','')){
      $selected_entity = $db->loadResult($db->setQuery("SELECT tt.eid FROM `#__os_cck_layout` AS cl "
                                                            . "\n LEFT JOIN #__os_cck_entity AS tt ON cl.fk_eid = tt.eid "
                                                            . "\n WHERE cl.lid=".$params->get('layout')));
      $eiid = $params->get('instance','');
    }
    $ceid = ($selected_entity)? '&fk_eid='.$selected_entity : '';
    $link = JRoute::_('index.php?option=com_os_cck&task=show_instance_modal'.$ceid.'&tmpl=component');
    $rel="{handler: 'iframe', size: {x: 900, y: 550}}";
    $html = '<input id="instance" type="text" name="'.$this->name.'" value="'.$eiid.'" readonly>';
    $html .= '<div class="fixedform">'.
                '<a id="changeLink" class="btn modal-button" href="'.$link.'" rel="'.$rel.'">'.
                  'Select instance'.
                '</a>'.
              '</div>';
    return $html;
  }
}
