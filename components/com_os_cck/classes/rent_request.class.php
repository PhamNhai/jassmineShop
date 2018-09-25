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

if (version_compare(JVERSION, '3.0', 'lt')) {
    require_once(JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table.php');
}
jimport("joomla.database.table");
class mosOS_CCK_rent_request extends JTable
{


    function __construct(&$db)
    {
        parent::__construct('#__os_cck_rent_request', 'id', $db);
        /*$this->mosDBTable( '#__os_cck_review', 'id', $db );*/
    }

    function accept(){
      global $my;
      if ($this->id == null) {
        return "Method called on a non instant object";
      }
      $this->check($my->id);
      //create new lend dataset
      $rent = new mosCCK_rent($this->_db);
      $instance = new os_cckEntityInstance($this->_db);
      $instance->check($my->id);
      $instance->load($this->fk_mediaid);
      $rent->fk_eiid = $this->fk_eiid;
      $rent->fk_userid = $this->fk_userid;
      $rent->user_name = $this->user_name;
      $rent->user_email = $this->user_email;
      $rent->user_mailing = $this->user_mailing;
      $rent->rent_from = $this->rent_from; // date("Y-m-d H:i:s");
      $rent->rent_until = $this->rent_until;
      $data = JFactory::getDBO();
      //lend check start
      $query = "SELECT * FROM #__os_cck_rent WHERE fk_eiid = " . $this->fk_eiid .
          " AND rent_return IS NULL ";
      $data->setQuery($query);
      $lendTerm = $data->loadObjectList();
      $lend_from = $rent->rent_from;
      $lend_until = $rent->rent_until;
      
      if (isset($lendTerm[0])) {
        for ($e = 0, $m = count($lendTerm); $e < $m; $e++) {
          // $lendTerm[$e]->rent_from = substr($lendTerm[$e]->rent_from, 0, 10);
          // $lendTerm[$e]->rent_until = substr($lendTerm[$e]->rent_until, 0, 10);
          //проверка  аренды


          if (($lend_from >= $lendTerm[$e]->rent_from && $lend_from < $lendTerm[$e]->rent_until)
              || ($lend_from <= $lendTerm[$e]->rent_from && $lend_until >= $lendTerm[$e]->rent_until)
              || ($lend_until > $lendTerm[$e]->rent_from && $lend_until <= $lendTerm[$e]->rent_until)
          ) {
            echo "<script> alert('Sorry lend out from " . $lendTerm[$e]->rent_from .
                " until " . $lendTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
            exit ();
          }
        }
      }
      //if end(end lend check)
      if (!$rent->store()) {
        return $rent->getError();
      }
      $this->status = 1;
      if (!$this->store()) {
        return $this->getError();
      }
      return null;
    }

    function decline(){
      if ($this->id == null) {
        return "Method called on a non instant object";
      }
      $this->status = 2;
      if (!$this->store()) {
        return $this->getError();
      }
      return null;
    }
}

