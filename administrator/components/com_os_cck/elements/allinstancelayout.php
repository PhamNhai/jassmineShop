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

JHTML::_('behavior.modal', 'a.modal-button');
$doc = JFactory::getDocument();
$doc->addScript(JURI::root().'/components/com_os_cck/assets/js/functions.js');
$doc->addStyleSheet(JURI::root().'/components/com_os_cck/assets/css/admin_style.css');

class JFormFieldAllInstancelayout extends JFormField{
  protected function getInput(){
    $db = JFactory::getDBO();
    $menuId = 0;
    if(JRequest::getVar('id') != '') {
      $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
      $params = json_decode($db->loadResult());
    }
    $link = JRoute::_('index.php?option=com_os_cck&task=manage_layout_modal&layout_type=all_instance&tmpl=component');
    $rel="{handler: 'iframe', size: {x: 900, y: 550}}";
    $lid = (isset($params->all_instance_layout) 
            && !empty($params->all_instance_layout))? $params->all_instance_layout : '';
    $html = '<input id="selected_layout" type="text" name="'.$this->name.'" value="'.$lid.'" readonly>';
    $html .= '<div class="fixedform">'.
                '<a class="btn modal-button" href="'.$link.'" rel="'.$rel.'">'.
                  'Select layout'.
                '</a>'.
              '</div>';
    return $html;
  }
}
