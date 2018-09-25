<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/ 

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
 
class baformsModelEmail extends JModelAdmin
{
    public function getTable($type = 'Forms', $prefix = 'formsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getBaitems()
    {
        $input = JFactory::getApplication()->input;
        $id = $input->get('id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, settings')
            ->from('#__baforms_items')
            ->where('`form_id` = ' .$id);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        
        return $result;
    }
 
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.form', 'form', array('control' => 'jform', 'load_data' => $loadData)
        );
 
        if (empty($form))
        {
            return false;
        }
 
        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.form.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
            $id = $data->id;
            if (isset($id)) {
                $elem = '';
                $column = '';
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("settings");
                $query->from("#__baforms_columns");
                $query->where("form_id=" . $id);
                $query->order("id ASC");
                $db->setQuery($query);
                $items = $db->loadObjectList();
                foreach ($items as $item) {
                    $elem .= $item->settings . '|';
                }
                $data->form_columns = $elem;
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("settings, id");
                $query->from("#__baforms_items");
                $query->where("form_id=" . $id);
                $query->order("id ASC");
                $db->setQuery($query);
                $items = $db->loadObjectList();
                foreach ($items as $item) {
                    $column .= json_encode($item) . '|_-_|';
                }
                $data->form_content = $column;
            }
        }
        return $data;
    }

    public function getEmail()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.form.data', array());
        $data = $this->getItem();
        $id = $data->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('email_letter')
            ->from('#__baforms_forms')
            ->where('`id` = ' .$id);
        $db->setQuery($query);
        $letter = $db->loadResult();
        
        return $letter;
    }

    public function save($data)
    {
        if(parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');
            $letter = $_POST['email_letter'];
            $table = JTable::getInstance('Forms', 'FormsTable');
            $table->load($id);
            $table->bind(array('email_letter' => $letter));
            $table->store(); 
            return true;
        } else {
            return false;
        }
    }
}