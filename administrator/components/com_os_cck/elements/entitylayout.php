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


class JFormFieldEntitylayout extends JFormField{   
    protected function getInput()
    {   
        $db = JFactory::getDBO();
        $menuId = 0;
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_layout;
        }
        $selected_entity = JRequest::getVar('entitylayout', $menuId);
        $value = ($selected_entity == '') ? $this->value : $selected_entity;
        $layouts = array();
        $query = "SELECT cl.lid AS value, cl.title AS title, tt.name AS entity, tt.eid AS entity_id FROM #__os_cck_layout AS cl
        LEFT JOIN #__os_cck_entity AS tt ON cl.fk_eid = tt.eid
        WHERE cl.published='1' AND cl.type='instance'";
        $db->setQuery($query);
        $layouts = $db->loadObjectList();
        $options = array();
        if($selected_entity <= 1 || empty($layouts)){
            $options[] = JHtml::_('select.option', '', 'Select');
        }
        foreach ($layouts as $item) $options[] = JHtml::_('select.option', $item->value, $item->title.' ('.$item->entity.')');
        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" onchange=\"javascript: window.location.href=window.location.href+'&entitylayout='+this.options[this.selectedIndex].value;\" ", 'value', 'text', $value, $this->name);
    }
}

class JFormFieldEntityinstance extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        $menuId = 0;
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_layout;
        }
        $selected_entity = JRequest::getVar('entitylayout', $menuId);
        $selected_entity = $db->loadResult($db->setQuery("SELECT tt.eid FROM `#__os_cck_layout` AS cl "
                                                                . "\n LEFT JOIN #__os_cck_entity AS tt ON cl.fk_eid = tt.eid "
                                                                . "\n WHERE cl.lid=".$selected_entity));
        $instancies = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = " SELECT cei.eiid AS value, cei.title AS title FROM #__os_cck_entity_instance AS cei "
                    . "\n WHERE cei.published='1' "
                    . "\n AND cei.fk_eid=(SELECT cei1.fk_eid FROM #__os_cck_entity_instance AS cei1 WHERE cei1.eiid='" . $this->value . "' ) ";
            $db->setQuery($query);
            $instancies = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = " SELECT cei.eiid AS value, cei.title AS title FROM #__os_cck_entity_instance AS cei "
                    . "\n WHERE cei.published='1' "
                    . "\n AND cei.fk_eid='" . $selected_entity . "' ";
            $db->setQuery($query);
            $instancies = $db->loadObjectList();
        }
        $options = array();
        if(empty($instancies)){
            $options[] = JHtml::_('select.option', '', 'Select');
        }
        foreach ($instancies as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);
        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}
