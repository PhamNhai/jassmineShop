<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/ 

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
 
class baformsModelSubmission extends JModelAdmin
{
    public function getTable($type = 'Submissions', $prefix = 'formsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
    
    public function getForm($data = array(), $loadData = true)
    {
        
        $form = $this->loadForm(
            $this->option . '.submissions', 'submissions', array('control' => 'jform', 'load_data' => $loadData)
        );
 
        if (empty($form))
        {
            return false;
        }
 
        return $form;
    }
    
    public function changeState($id)
    {
        $db = $this->getDbo();
        $query = 'UPDATE `#__baforms_submissions` SET `submission_state`=0 WHERE `id`='. $id;
        $db->setQuery($query);
        if ($db->execute($query)) {
            return 'succes';
        } else {
            return 'false';
        }
    }
    
    public function delete(&$pks)
	{
        $pks = (array) $pks;
        $dir = JPATH_ROOT. '/images/baforms/';
        $db = JFactory::getDBO();
        foreach ($pks as $pk) {
            $query = $db->getQuery(true);
            $id = $pk;
            $files = array();
            $query->select("mesage");
            $query->from("#__baforms_submissions");
            $query->where("id=" . $pk);
            $db->setQuery($query);
            $items = $db->loadResult();
            $items = explode('_-_', $items);
            foreach ($items as $item) {
                if ($item != '') {
                    $item = explode('|-_-|', $item);
                    if ($item[2] == 'upload') {
                        if ($item[1] != '') {
                            array_push($files, $item[1]);
                        }
                    }
                }
            }
            if (parent::delete($pk)) {
                foreach ($files as $file) {
                    unlink($dir.$file);
                }
            } else {
                return false;
            }
        }
        return true;
    }
    
}