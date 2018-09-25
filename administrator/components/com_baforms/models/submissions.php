<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class baformsModelSubmissions extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('id', 'title');
        }
        header('Content-type: text/html; charset=utf-8');
        parent::__construct($config);
    }
    
    protected function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, title, mesage, date_time, submission_state');
        $query->from('#__baforms_submissions');
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`title` LIKE ' . $search, 'OR')->where('`mesage` LIKE ' . $search);
        }
        $orderCol = $this->state->get('list.ordering', 'title');
		$orderDirn = $this->state->get('list.direction', 'desc');
		if ($orderCol == 'ordering') {
			$orderCol = 'title ' . $orderDirn . ', ordering';
		}
		$query->order($db->quoteName('id') . ' ' . $orderDirn);
        
        return $query;
    }
    
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
    
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('title', 'desc');
    }
   
}