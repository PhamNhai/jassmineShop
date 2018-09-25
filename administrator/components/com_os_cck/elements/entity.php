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
class JElementEntity extends JElement{
    var $_name = 'entity';
    protected function getInput(){
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $value = ($selected_entity == '') ? $this->value : $selected_entity;
        $entities = array();
        $db = JFactory::getDBO();
        $query = "SELECT ce.eid AS entity, ce.name AS title FROM #__os_cck_entity AS ce WHERE published='1' ";
        $db->setQuery($query);
        $entities = $db->loadObjectList();

        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($entities as $item) $options[] = JHtml::_('select.option', $item->entity, $item->title);
        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" onchange=\"javascript: window.location.href=window.location.href+'&entity_type='+this.options[this.selectedIndex].value;\" ", 'value', 'text', $value, $this->name);
    }
}

class JFormFieldAllcategorieslayout extends JFormField
{
    protected function getInput()
    {

        $db = JFactory::getDBO();
        $menuId = 0;
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $layouts = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type='all_categories' AND type_id=(SELECT cl1.type_id FROM #__os_cck_layout AS cl1 WHERE cl1.lid='" . $this->value . "') ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type='all_categories' AND type_id='" . $selected_entity . "' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }
        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($layouts as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}

class JFormFieldCategorylayout extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $layouts = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'category%' AND type_id=(SELECT cl1.type_id FROM #__os_cck_layout AS cl1 WHERE cl1.lid='" . $this->value . "') ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'category%' AND type_id='" . $selected_entity . "' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }
        if ($selected_entity == '' && $this->value != '' && count($layouts) == 0) {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'category%' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }


        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($layouts as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}

class JFormFieldSearchlayout extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $layouts = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'search%' AND type_id=(SELECT cl1.type_id FROM #__os_cck_layout AS cl1 WHERE cl1.lid='" . $this->value . "') ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'search%' AND type_id='" . $selected_entity . "' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }
        if ($selected_entity == '' && $this->value != '' && count($layouts) == 0) {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type LIKE 'search%' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }

        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($layouts as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}

class JFormFieldEntitylayout extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $layouts = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type='entity' AND type_id=(SELECT cl1.type_id FROM #__os_cck_layout AS cl1 WHERE cl1.lid='" . $this->value . "')";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = "SELECT cl.lid AS value, cl.title AS title FROM #__os_cck_layout AS cl WHERE cl.published='1' AND cl.type='entity' AND type_id='" . $selected_entity . "' ";
            $db->setQuery($query);
            $layouts = $db->loadObjectList();
        }

        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($layouts as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}

class JFormFieldEntityinstance extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }
        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $instancies = array();
        if ($selected_entity == '' && $this->value != '') {
            $query = " SELECT cei.eiid AS value, cei.title AS title FROM #__os_cck_entity_instance AS cei
          WHERE cei.published='1' 
          AND cei.fk_eid=(SELECT cei1.fk_eid FROM #__os_cck_entity_instance AS cei1 WHERE cei1.eiid='" . $this->value . "' ) ";
            $db->setQuery($query);
            $instancies = $db->loadObjectList();
        } else if ($selected_entity != '') {
            $query = " SELECT cei.eiid AS value, cei.title AS title FROM #__os_cck_entity_instance AS cei
          WHERE cei.published='1' 
          AND cei.fk_eid='" . $selected_entity . "' ";
            $db->setQuery($query);
            $instancies = $db->loadObjectList();
        }

        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($instancies as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}

class JFormFieldCategory extends JFormField
{
    protected function getInput()
    {
        $db = JFactory::getDBO();
        if(JRequest::getVar('id') != '') {
            $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".JRequest::getVar('id'));
            $params = json_decode($db->loadResult());
            $menuId = $params->entity_type;
        }

        $selected_entity = JRequest::getVar('entity_type', $menuId);
        $categories = array();
        if ($selected_entity == '' && $this->value != '') { ///suppose category can contain any entities
            $query = " SELECT cc.cid  AS value, cc.title AS title FROM #__os_cck_categories AS cc  WHERE cc.published='1' ";
            $db->setQuery($query);
            $categories = $db->loadObjectList();
        } else if ($selected_entity != '') { ///suppose category can contain any entities
            $query = " SELECT cc.cid  AS value, cc.title AS title FROM #__os_cck_categories AS cc  WHERE cc.published='1' ";
            $db->setQuery($query);
            $categories = $db->loadObjectList();
        }
        $options = array();
        $options[] = JHtml::_('select.option', '', 'Select');
        foreach ($categories as $item) $options[] = JHtml::_('select.option', $item->value, $item->title);

        return JHTML::_('select.genericlist', $options, $this->name, "class=\"inputbox\" ", 'value', 'text', $this->value, $this->name);
    }
}
