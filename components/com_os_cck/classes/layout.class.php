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

class os_cckLayout extends JTable
{

    var $lid = null;
    var $title = null;
    var $type = null;
    var $fk_eid = null;
    var $params = null;
    var $checked_out = null;
    var $checked_out_time = null;
    var $published = null;
    var $approved = null;
    var $created = null;
    var $changed = null;

    function __construct(&$db)
    {
        parent::__construct('#__os_cck_layout', 'lid', $db);
    }

    function quoteName($name)
    {
        if (version_compare(JVERSION, "3.0.0", "lt")) {
            $return = $this->_db->NameQuote($name);
        } else {
            $return = $this->_db->quoteName($name);
        }
        return $return;
    }

    function getLayoutParams($fk_eid, $type)
    {
        $query = "SELECT lid FROM #__os_cck_layout WHERE fk_eid = '" . $fk_eid . "' AND type = '" . $type ."'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return $result;
    }

    function getDefaultLayout($fk_eid, $type)
    {   
        $query = "SELECT lid FROM #__os_cck_layout WHERE fk_eid = '" . $fk_eid . "' AND type = '" . $type . "' AND published='1'  AND approved='1'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return $result;
    }

    function getDefaultField($fk_eid, $type)
    {   
        $query = "SELECT db_field_name FROM #__os_cck_entity_field WHERE fk_eid = '" . $fk_eid . "' AND field_type = '" . $type . "' AND  published='1'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return $result;
    }

    function getDefaultLayoutCheck($lid, $type, $fk_eid)
    {   
        $query = "SELECT lid FROM #__os_cck_layout WHERE  fk_eid = '" . $fk_eid . "' AND  lid != '" . $lid . "' AND type = '" . $type . "' AND approved = '1' AND published='1'";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return $result;
    }
    
    function getEntityName($fk_eid)
    {   
        $query = "SELECT e.`name` FROM `#__os_cck_entity` e WHERE e.`eid` = $fk_eid";
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();
        return $result;
    }

    function getLayoutHtml($bootstrap_version){ 

       

        $query = "SELECT layout_html FROM #__os_cck_layout as c "
            ."\n LEFT JOIN #__os_cck_layout_html as ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid=$this->lid"
            ."\n AND c.published = '1' AND bootstrap = '".$bootstrap_version."'";


        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();



        if(!isset($result) || empty($result)){
          $query = "SELECT type,title FROM #__os_cck_layout as c"
            ."\n WHERE c.lid=$this->lid";

          $this->_db->setQuery($query);
          $result = $this->_db->loadAssoc();
          
          if($result['type'] == 'calendar') return;

          JError::raiseNotice(100, $result['title'].JText::_("COM_OS_CCK_ERROR_EMPTY_LAYOUT"));
          return;
        }else{
            
          return $result;
        }
    }


}

?>
