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
 

class baformsViewSubmissions extends JViewLegacy
{
    protected $items;
    
    protected $pagination;
    
    protected $state;
    
    protected $about;
    
    protected $print;
    
    protected $printTitle;
    
    /**
     * View display method
     * @return void
     */
    public function display($tpl = null) 
    {
        $input = JFactory::getApplication()->input;
        $print = $input->get('print');
        if (!empty($print)) {
            $this->print = $this->getPrintData($print);
            $this->printTitle = $this->getPrintTitle($print);
            $this->setLayout('print');
        } else {
            $this->about = baformsHelper::aboutUs();
            $this->items = $this->get('Items');
            $this->pagination = $this->get('Pagination');
            $this->state = $this->get('State');
            $this->addToolBar();
            baformsHelper::addSubmenu('Submissions');
            $this->sidebar = JHtmlSidebar::render();
            foreach ($this->items as &$item) {
                $item->order_up = true;
                $item->order_dn = true;
            }
        }
        // Display the template
        parent::display($tpl);
    }
    
    protected function addToolBar ()
    {
        JToolBarHelper::title(JText::_('SUBMISSIONS_TITLE'), 'star');
        if (JFactory::getUser()->authorise('core.delete', 'com_baforms')) {
          JToolBarHelper::deleteList('', 'submissions.delete');
        }
        if (JFactory::getUser()->authorise('core.admin', 'com_baforms') || JFactory::getUser()->authorise('core.options', 'com_baforms')) {
            JToolBarHelper::preferences('com_baforms');
        }
    }
    
    protected function getPrintData($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mesage')
            ->from('#__baforms_submissions')
            ->where('`id`=' .$id);
        $db->setQuery($query);
        
        return $db->loadResult();
    }
    
    protected function getPrintTitle($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('title, date_time')
            ->from('#__baforms_submissions')
            ->where('`id`=' .$id);
        $db->setQuery($query);
        
        return $db->loadObject();
    }
    
    protected function getSortFields()
    {
        $array = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("title");
        $query->from("#__baforms_submissions");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach($items as $item) {
            if (!in_array($item->title, $array)) {
                array_push($array, $item->title);
            }
        }
        return $array;
    }
    
}