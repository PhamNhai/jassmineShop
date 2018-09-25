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


/**
* Class from data defination for Entity
*/

class os_cckEntity extends JTable{

  var $_entity_name = null;

  /** @var name for define second table */
  var $eid = null;
  var $asset_id = null;
  var $name = null;
  var $fk_userid = null;
  var $published = null;
  var $approved = null;
  var $created = null;
  var $changed = null;
  var $checked_out = null;
  var $checked_out_time = null;

  /**
   * @param database - A database connector object
   */
  function __construct(&$db){
    parent::__construct('#__os_cck_entity', 'eid', $db);
  }

  function quoteName($name){
    $return = $this->_db->quoteName($name);
    return $return;
  }

  /**
   * Check exist this type entity
   *
   * @param string  entity_name    The table type to instantiate
   * @param array $options Configuration array for model. Optional.
   * @return database A database object
   * @since 1.5
   */
  static function is_entity_name_exist($entity_name, $config = array()){
    $db = JFactory::getDBO();

    $db->setQuery("SELECT name FROM #__os_cck_entity WHERE name = '$entity_name' ");
    $tmp = $db->loadResult();
    if (count($tmp) == 0) {
      return false;
    }
    return true;
  }


  // overloaded check function
  // function check(){
  // }

  /**
   * Default delete method
   *
   * can be overloaded/supplemented by the child class
   *
   * @access public
   * @return true if successful otherwise returns and error message
   */
  function delete($oid = null){
    $app = JFactory::getApplication();
    $k = $this->_tbl_key;
    if ($oid) {
      $this->$k = $oid;
    }
    if (!$this->is_entity_name_exist($this->name)) {
      $app->enqueueMessage("Before delete Entity you must save it!", 'error');
      return false;
    }
    $fields = $this->getFieldList();
    $lastField = count($fields);
    $count = 0;
    if($lastField){
      foreach ($fields as $field) {
        $count++;
        $os_cckEntityField = new os_cckEntityField($this->_db);
        $os_cckEntityField->load($field->fid);
        $os_cckEntityField->delete();
      }
    }
    $query = "DROP TABLE #__os_cck_content_entity_" . $this->eid;
    $this->_db->setQuery($query);
    if (!$this->_db->query()) {
      //error create new table
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    $pk = (is_null($oid)) ? $this->$k : $oid;
    // If no primary key is given, return false.
    if ($pk === null) {
      throw new UnexpectedValueException('Null primary key not allowed.');
    }
    // Delete the row by primary key.
    $query = $this->_db->getQuery(true);
    $query->delete();
    $query->from($this->_tbl);
    $query->where($this->_tbl_key . ' = ' . $this->_db->quote($pk));
    $this->_db->setQuery($query);
    // Check for a database error.
    $this->_db->execute();
    $ret = true;

    return $ret;
  }

  function getField($fid){
    $query = "SELECT * FROM #__os_cck_entity_field WHERE fid = '".$fid."'";
    $this->_db->setQuery($query);
    return $this->_db->loadObject();
  }

  function getFieldList($layout_html = ''){
    $query = ' SELECT f.* FROM #__os_cck_entity_field AS f '.
            ' WHERE f.fk_eid = ' . $this->eid . ' ORDER BY field_name';
    $this->_db->setQuery($query);
    $result = $this->_db->loadObjectList();
    //select only exist layout field for NEXT update/store function
    if($layout_html){
      $fields = array();
      $layout_html = urldecode($layout_html);
      foreach ($result as $field) {
        if(strpos($layout_html,"{|f-".$field->fid."|}")){
          $fields[] = $field;
        }
      }
    }else{
      $fields = $result;
    }
    return $fields;
  }

  function store($updateNulls = false){
    $new = false;
    if(!$this->eid)$new = true;
    parent::store();
    if($new && $this->eid){
      $query = "CREATE TABLE #__os_cck_content_entity_" . $this->eid .
        " (`ceid` int(11) unsigned NOT NULL AUTO_INCREMENT, " .
        " `fk_eiid` int(11) unsigned NOT NULL , " .
        " PRIMARY KEY `ceid` (`ceid`), foreign KEY  (`fk_eiid`) REFERENCES #__os_cck_entity_instance(`eiid`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        //error create new table
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }
    return true;
  }

  function getIndexedFieldList(){
    $fields = $this->getFieldList();
    $indexed = array();
    foreach($fields as $key => $field) {
      if($field->field_type == "categoryfield" || $field->field_type == "rating_field"
        || $field->field_type == "datetime_popup" || $field->field_type == "decimal_textfield"
        || $field->field_type == "decimal_textfield" || $field->field_type == "text_textfield"){
        $indexed[$key]['text'] = $field->field_name;
        $indexed[$key]['value'] = $field->db_field_name;
      }
    }
    return $indexed;
  }

  function getEventTitleFieldList(){
    $fields = $this->getFieldList();
    $indexed = array();

    foreach($fields as $key => $field) {
      if($field->field_type == "text_textfield"){
        $indexed[$key]['text'] = $field->field_name;
        $indexed[$key]['value'] = $field->db_field_name;
      }
    }
    return $indexed;
  }

  function getDatePopupFieldList(){
    $fields = $this->getFieldList();
    $indexed = array();
    foreach($fields as $key => $field) {
      if($field->field_type == "datetime_popup"){
        $indexed[$key]['text'] = $field->field_name;
        $indexed[$key]['value'] = $field->db_field_name;
      }
    }
    return $indexed;
  }

  function getTextAreaFieldList(){
    $fields = $this->getFieldList();
    $indexed = array();
    foreach($fields as $key => $field) {
      if($field->field_type == "text_textarea"){
        $indexed[$key]['text'] = $field->field_name;
        $indexed[$key]['value'] = $field->db_field_name;
      }
    }
    return $indexed;
  }

  function getLocationFieldList(){
    $fields = $this->getFieldList();
    $indexed = array();
    foreach($fields as $key => $field) {
      if($field->field_type == "locationfield"){
        $indexed[$key]['text'] = $field->field_name;
        $indexed[$key]['value'] = $field->db_field_name;
      }
    }
    return $indexed;
  }




}
