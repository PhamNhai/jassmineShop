<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');
 

class baformsViewForms extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $about;

    public function display($tpl = null) 
    {
        $this->about = baformsHelper::aboutUs();
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->addToolBar();
        baformsHelper::addSubmenu('forms');
        $this->sidebar = JHtmlSidebar::render();
        foreach ($this->items as &$item)
        {
            $item->order_up = true;
            $item->order_dn = true;
        }
        
        parent::display($tpl);
    }
    
    protected function addToolBar ()
    {
        JToolBarHelper::title(JText::_('FORMS_TITLE'), 'star');
        if (JFactory::getUser()->authorise('core.create', 'com_baforms')) {
            JToolBarHelper::addNew('form.add');
        }
        if (JFactory::getUser()->authorise('core.edit', 'com_baforms')) {
            JToolBarHelper::editList('form.edit');
        }
        if (JFactory::getUser()->authorise('core.duplicate', 'com_baforms')) {
            JToolBarHelper::custom('forms.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
        if (JFactory::getUser()->authorise('core.edit.state', 'com_baforms')) {
            JToolbarHelper::publish('forms.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('forms.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }
        if (JFactory::getUser()->authorise('core.delete', 'com_baforms')) {
            if ($this->state->get('filter.state') == -2) {
                JToolBarHelper::deleteList('', 'forms.delete');
            } else {
                JToolbarHelper::trash('forms.trash');
            }
        }
        if (JFactory::getUser()->authorise('core.admin', 'com_baforms') || JFactory::getUser()->authorise('core.options', 'com_baforms')) {
            JToolBarHelper::preferences('com_baforms');
        }
    }
    
    protected function getSortFields()
    {
        return array(
            'ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'published' => JText::_('JSTATUS'),
            'title' => JText::_('JGLOBAL_TITLE'),
            'id' => JText::_('JGRID_HEADING_ID')
        );
    }
}