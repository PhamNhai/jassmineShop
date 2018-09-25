<?php

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

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
class os_cckEntityField extends JTable
{
  //first table fileds 
  var $fid = null;
  var $fk_eid = null;
  var $asset_id = null;
  var $field_name = null;
  var $field_type = null;
  var $db_field_name = null;
  var $published = null;
  var $params = null;

  /**
   * @param database - A database connector object
   */
  function __construct(&$db)
  {
    parent::__construct('#__os_cck_entity_field', 'fid', $db);
  }
      
  function quoteName($name)
  {
    $return = $this->_db->quoteName($name);
    return $return;
  }

  /**
   * Inserts a new row if id is zero or updates an existing row in the database table
   *
   * Can be overloaded/supplemented by the child class
   *
   * @access public
   * @param boolean If false, null object variables are not updated
   * @return null|string null if successful otherwise returns and error message
   */
  function store($updateNulls = false){
    $new = false;
    if(!$this->fid)$new = true;
    parent::store();
    if($new){
      $this->db_field_name = $this->field_type.'_'.$this->fid;
      parent::store();//update
      $ret = $this->_addField();
      if (!$ret) {
        echo '<span class="cck-error-message">'.get_class($this) . '::store failed - ' . $this->_db->getErrorMsg().'</span>';
        return false;
      } else {
        return true;
      }
    }
  }


  //Example for show how call addField function -for test and debug only
  function _addField(){ 

      switch ($this->field_type) {
          //setup columns params
          case "videofield":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
//------------------------------------------------------------------
          case "audiofield":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
//------------------------------------------------------------------
          case "categoryfield":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '1'
              )
              );
            break;
//------------------------------------------------------------------
          case "captcha_field":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
            break;
//------------------------------------------------------------------
          case "rating_field":
              $db_columns = array('value1' => array
              (
                  'type' => 'varchar',
                  'size' => '255',
                  'not_null' => '',
                  'sortable' => '1'
              )
              );
            break;
//------------------------------------------------------------------
          case "datetime_popup":
              $db_columns = array('value1' => array
              (
                'type' => 'timestamp',
                'not_null' => '',
                'sortable' => '1'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_textfield":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '1'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_url":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_textarea":
              $db_columns = array('value1' => array
              (
                'type' => 'text',
                'size' => 'big',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_select_list":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_radio_buttons":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
//------------------------------------------------------------------
          case "text_single_checkbox_onoff":
              $db_columns = array('value1' => array
              (
                'type' => 'varchar',
                'size' => '255',
                'not_null' => '',
                'sortable' => '0'
              )
              );
              break;
          case "decimal_textfield":
              $db_columns = array('value1' => array
              (
                'type' => 'int',
                'size' => '100',
                'not_null' => '',
                'sortable' => '1'
              )
              );
              break;
//------------------------------------------------------------------
          case "galleryfield":
              $db_columns = array(
                  'value1' => array
                  (
                      'type' => 'int',
                      'not_null' => ''
                  ),
                  'value2' => array
                  (
                      'type' => 'int',
                      'size' => '11',
                      'not_null' => ''
                  ),
                  'value3' => array
                  (
                      'type' => 'text',
                      'size' => 'big',
                      'not_null' => ''
                  )
              );
              break;
//------------------------------------------------------------------
          case "locationfield":
              //unique field custom insert in function bellow
              $db_columns = array();
              break;
          case "imagefield":
              $db_columns = array(
                'value1' => array
                (
                    'type' => 'int',
                    'not_null' => ''
                ),
                'value2' => array
                (
                    'type' => 'tinyint',
                    'size' => '4',
                    'not_null' => ''
                ),
                'value3' => array
                (
                    'type' => 'text',
                    'size' => 'big',
                    'not_null' => ''
                )
              );
              break;
//------------------------------------------------------------------
          case "filefield":
              $db_columns = array(
                'value1' => array
                (
                    'type' => 'int',
                    'not_null' => ''
                ),
                'value2' => array
                (
                    'type' => 'tinyint',
                    'size' => '4',
                    'not_null' => ''
                ),
                'value3' => array
                (
                    'type' => 'text',
                    'size' => 'big',
                    'not_null' => ''
                )
              );
              break;
//------------------------------------------------------------------
          default:
              echo '<span class="cck-error-message">Undefine field type ' . $this->field_type . ' in addField - it not supported.</span>';
              return false;
      }

      return $this->_addFieldToDB($db_columns);
  }

  function _addFieldToDB($db_columns){ 
    if ($this->field_type== 'locationfield') {
      //this table alredy exist so only add column to it
      $query = "ALTER TABLE #__os_cck_content_entity_" . $this->fk_eid .
          " ADD COLUMN " . $this->db_field_name."_vlat double" .
          ", ADD COLUMN " . $this->db_field_name."_vlong double" .
          ", ADD COLUMN " . $this->db_field_name."_zoom int" .
          ", ADD COLUMN " . $this->db_field_name."_address varchar(255) ".
          ", ADD COLUMN " . $this->db_field_name."_country varchar(255) ".
          ", ADD COLUMN " . $this->db_field_name."_region varchar(255) ".
          ", ADD COLUMN " . $this->db_field_name."_city varchar(255) ".
          ", ADD COLUMN " . $this->db_field_name."_zipcode varchar(255) ";
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
          //error add new column
          echo '<span class="cck-error-message">' . $this->setError($this->_db->getErrorMsg()) . '</span>';
          return false;
      }
    } 
    else if (count($db_columns) == 1) {
      $db_columns = $db_columns['value1'];
      //this table alredy exist so only add column to it
      $query = "ALTER TABLE #__os_cck_content_entity_" . $this->fk_eid .
          " ADD COLUMN " . $this->quoteName($this->db_field_name) . $this->_build_table_filed_type($db_columns);
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        //error add new column
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }
      if ($db_columns['sortable']) {
        //IF NEED ADD INDEX FOR THIS FIELD 
        $query = "ALTER TABLE #__os_cck_content_entity_".$this->fk_eid.
            " ADD KEY " . $this->quoteName($this->db_field_name) .
            " ( " . $this->quoteName($this->db_field_name) .
            ((isset($db_columns['size']) && $db_columns['size'] == "big") ? ("( 200 )") : (""))
            . " )";
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
          //error add new column
          echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
          return false;
        }
      }
    }
    else if(count($db_columns) > 1) {
      //this table alredy exist so only add column to it
      $query = "ALTER TABLE #__os_cck_content_entity_".$this->fk_eid.
          " ADD COLUMN " . $this->db_field_name."_fid "
          . $this->_build_table_filed_type($db_columns['value1']) .
          ", ADD COLUMN " . $this->db_field_name."_list "
          . $this->_build_table_filed_type($db_columns['value2']) .
          ", ADD COLUMN " . $this->db_field_name."_data "
          . $this->_build_table_filed_type($db_columns['value3']);
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        //error add new column
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }
    } else {
      //sanity check
      echo '<span class="cck-error-message">Undefine field type in _addFieldToDB - it not supported.</span>';
      return false;
    }

    return true;
  }

  function _is_filed_exist($field_name){
    $query = 'SELECT field_name as count FROM #__os_cck_entity_field  where fk_eid = ' . $this->fk_eid . ' ';
    $this->_db->setQuery($query);
    $tmp = $this->_db->loadColumn();
    if (count($tmp) == 0)
      return false;
    foreach ($tmp as $key => $value) {
      if ($value == $field_name)
        return true;
    }
    return false;
  }

  function _build_table_filed_type($db_columns){
    switch ($db_columns['type']) {
      case "text":
          if ($db_columns['size'] == "big")
              return " longtext " . (($db_columns['not_null']) ? ("NOT NULL") : (""));
          else {
              echo '<span class="cck-error-message">Bad set field type in _build_table_filed_type - it not supported.</span>';
              return "";
          }
          break;

      case "varchar":
          return " varchar (" . $db_columns['size'] . ") " . (($db_columns['not_null']) ? ("NOT NULL") : (""));
          break;

      case "int":
          return " int (11) " . (($db_columns['not_null']) ? ("NOT NULL") : (""));
          break;

      case "tinyint":
          return " tinyint (" . $db_columns['size'] . ") " . (($db_columns['not_null']) ? ("NOT NULL") : (""));
          break;

      case "decimal":
          return " decimal (" . $db_columns['precision'] . "," . $db_columns['scale'] . ") " .
          (($db_columns['not_null']) ? ("NOT NULL") : (""));

      case "float":
          return " float " . (($db_columns['not_null']) ? ("NOT NULL") : (""));

      case "datetime":
          return " timestamp " .(($db_columns['not_null']) ? ("NOT NULL") : (""));

      case "timestamp":
          return " timestamp " . (($db_columns['not_null']) ? ("NOT NULL") : ("DEFAULT 0"));
          break;

      default:
          echo '<span class="cck-error-message">Bad set field type in _build_table_filed_type - field type unknow.</span>';
          return "";
    }
    echo '<span class="cck-error-message">Bad set field type in _build_table_filed_type - field type unknow.</span>';
    return "";
  }

  /**
   * Default delete method
   *
   * can be overloaded/supplemented by the child class
   *
   * @access public
   * @return true if successful otherwise returns and error message
   */
  function delete($oid = null){
    $this->_deleteField();
    $ret = parent::delete();

    return $ret;
  }

  function _deleteField()
  {   
    if($this->field_type == "locationfield"){
      $query = 'ALTER TABLE #__os_cck_content_entity_' . $this->fk_eid .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_vlat") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_vlong") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_address") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_region") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_country") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_city") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_zipcode") . ", " .
          " DROP COLUMN " . $this->quoteName($this->db_field_name."_zoom");
      $this->_db->setQuery($query);
      if ($this->_db->query()) {
          $this->_updateSearchParams();
          return true;
      } else {
          echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
          return false;
      }
    }
    else if($this->field_type == "galleryfield" || $this->field_type == "filefield" || $this->field_type == "imagefield"){
      //in table leave only field what need remove and key field so table may remove
      if ($this->field_type == "filefield" || $this->field_type == "imagefield") {
        //first remove all files and images from disk and from file table
        $this->_dropAllImagesFile();
      }
      $query = 'ALTER TABLE #__os_cck_content_entity_'.$this->fk_eid.
                  " DROP COLUMN " . $this->quoteName($this->db_field_name. "_fid").", ".
                  " DROP COLUMN " . $this->quoteName($this->db_field_name. "_list"). ", ".
                  " DROP COLUMN " . $this->quoteName($this->db_field_name. "_data");
      $this->_db->setQuery($query);
      if ($this->_db->query()) {
        $this->_updateSearchParams();
        return true;
      } else {
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }
    }else{
      $query = 'ALTER TABLE #__os_cck_content_entity_'.$this->fk_eid.
          " DROP COLUMN ".$this->quoteName($this->db_field_name);
      $this->_db->setQuery($query);
      if ($this->_db->query()) {
          $this->_updateSearchParams();
          return true;
      } else {
          echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
          return false;
      }
    }
  }
  
  function _updateSearchParams(){
      $name = 'cck_search_'.$this->db_field_name;
      $query = "SELECT * from #__os_cck_layout WHERE type='search'";
      $this->_db->setQuery($query);
      $tmp = $this->_db->loadObjectList();
      foreach ($tmp as $layout) {
          $params = unserialize($layout->params);
          unset($params['search_params'][$name]);
          $query = "UPDATE #__os_cck_layout SET params='".serialize($params)."' WHERE lid='".$layout->lid."'";
          $this->_db->setQuery($query);
          $this->_db->query();
      }
  }
  
  //drop images and files from main tabel and connect table and from disk when we do table ater with column drop
  function _dropAllImagesFile()
  {
      //first remove all files and images from disk and from file table
      //remove parrent images and file      
      $query = "select filepath from #__os_cck_files f, #__os_cck_content_entity_" . $this->fk_eid . " ftn " .
          " where f.fid = ftn." . $this->db_field_name . "_fid";
      $this->_db->setQuery($query);
      $tmp = $this->_db->loadObjectList();
      foreach ($tmp as $file) {
        @unlink(JPATH_SITE .$file->filepath); 
      }

      //remove all child images from disk and from file table
      $query = "select filepath from #__os_cck_files f, #__os_cck_content_entity_" . $this->fk_eid . " ftn, " .
          " #__os_cck_child_parent_connect fcpc " .
          " where fcpc.fid_parent = ftn." . $this->db_field_name . "_fid and f.fid = fcpc.fid_child  ";
      $this->_db->setQuery($query);
      $tmp = $this->_db->loadObjectList();
      foreach ($tmp as $file){
        @unlink(JPATH_SITE . $file->filepath);
      }

      //remove all child images from file table
      $query = 'DELETE FROM #__os_cck_files where exists ' .
          '( SELECT * FROM #__os_cck_content_entity_'.$this->fk_eid.', ' .
          '  #__os_cck_child_parent_connect   ' .
          ' where #__os_cck_files.fid = #__os_cck_child_parent_connect.fid_child' .
          ' and #__os_cck_child_parent_connect.fid_parent = #__os_cck_content_entity_'.
          $this->fk_eid.".".$this->db_field_name . "_fid )";
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }

      //remove all child images from connect table
      $query = 'DELETE FROM #__os_cck_child_parent_connect where exists ' .
          '( SELECT * FROM #__os_cck_content_entity_'.$this->fk_eid.
          ' where #__os_cck_child_parent_connect.fid_parent = #__os_cck_content_entity_'.
          $this->fk_eid.".".$this->db_field_name."_fid )";
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }

      //remove all parent images from main file table    
      $query = 'DELETE FROM #__os_cck_files where exists ' .
          '( SELECT * FROM #__os_cck_content_entity_'.$this->fk_eid.
          ' where #__os_cck_files.fid = #__os_cck_content_entity_'.$this->fk_eid.".".$this->db_field_name."_fid )";
          
      $this->_db->setQuery($query);
      if (!$this->_db->query()) {
        echo '<span class="cck-error-message">'.$this->setError($this->_db->getErrorMsg()).'</span>';
        return false;
      }
      
      return true;
  }

}


