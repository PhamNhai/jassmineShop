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

class AdminField{

  static function showLayoutFields($option){
    global $db, $app, $jConf;
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $pub = $app->getUserStateFromRequest("pub{$option}", 'pub', '');
    $entity_id = $app->input->get("fk_eid",'');
    $where = array();
    array_push($where, "ef.fk_eid = e.eid ");
    if ($entity_id != '') {
      array_push($where, "ef.fk_eid ='{$entity_id}'");
    }
    // if ($pub == "pub") {
    //   array_push($where, "ef.published = 1");
    // } else if ($pub == "not_pub") {
    //   array_push($where, "ef.published = 0");
    // }

    $query = "SELECT COUNT(ef.fid) FROM #__os_cck_entity_field AS ef, #__os_cck_entity AS e " .
        (count($where) ? " WHERE " . implode(' AND ', $where) : "");

    $db->setQuery($query);
    $total = $db->loadResult();
    $pageNav = new JPagination($total, $limitstart, $limit);

    $query = "SELECT ef.* , e.name  FROM #__os_cck_entity_field AS ef, #__os_cck_entity AS e " .
        (count($where) ? " WHERE " . implode(' AND ', $where) : "") .
        " ORDER BY  ef.field_name";

    $db->setQuery($query);
    $extrafield_list = $db->loadObjectList();
    $entities = array();
    $entities[] = array('value' => '', 'text' => 'All entities');
    $query = "SELECT eid AS value, name AS text FROM #__os_cck_entity ORDER BY name";
    $db->setQuery($query);
    $ent = $db->loadObjectList();
    $entities = (count($ent) > 1) ? array_merge($entities, (array)$ent) : $entities;
    $entity_list = JHTML::_('select.genericlist',$entities, 'entity_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $entity_id);

    $pubmenu[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_LABEL_SELECT_TO_PUBLIC'));
    $pubmenu[] = JHTML::_('select.option','-1', JText::_('COM_OS_CCK_LABEL_SELECT_ALL_PUBLIC'));
    $pubmenu[] = JHTML::_('select.option','not_pub', JText::_('COM_OS_CCK_LABEL_SELECT_NOT_PUBLIC'));
    $pubmenu[] = JHTML::_('select.option','pub', JText::_('COM_OS_CCK_LABEL_SELECT_PUBLIC'));
    $publist = JHTML::_('select.genericlist',$pubmenu, 'pub', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $pub);

    AdminViewField :: showLayoutFields($option, $extrafield_list, $pageNav, $entity_list, $publist);
  }

  static function addLayoutField($option,$fid){
    global $db;
    $params = new JRegistry;
    if($fid){
      $field = new os_cckEntityField($db);
      $field->load($fid);
      $params->loadString($field->params);
    }else{
      $field = '';
    }
    $field_types = array();
    $field_types[] = JHTML::_('select.option','audiofield', 'Audio Field');
    $field_types[] = JHTML::_('select.option','captcha_field', 'Captcha');
    $field_types[] = JHTML::_('select.option','categoryfield', 'Category');
    $field_types[] = JHTML::_('select.option','text_single_checkbox_onoff', 'Checkbox');
    $field_types[] = JHTML::_('select.option','datetime_popup', 'Date');
    $field_types[] = JHTML::_('select.option','filefield', 'File Field');
    $field_types[] = JHTML::_('select.option','galleryfield', 'Gallery');
    $field_types[] = JHTML::_('select.option','imagefield', 'Image Field');
    $field_types[] = JHTML::_('select.option','locationfield', 'Location map');
    $field_types[] = JHTML::_('select.option','decimal_textfield', 'Number Field');
    $field_types[] = JHTML::_('select.option','text_radio_buttons', 'Radio buttons');
    $field_types[] = JHTML::_('select.option','rating_field', 'Rating Field');
    $field_types[] = JHTML::_('select.option','text_select_list', 'Select list');
    $field_types[] = JHTML::_('select.option','text_textfield', 'Text');
    $field_types[] = JHTML::_('select.option','text_textarea', 'Text Area');
    $field_types[] = JHTML::_('select.option','text_url', 'Url');
    $field_types[] = JHTML::_('select.option','videofield', 'Video Field');   

    if($fid){
      //print_r($field_types);exit;
      $field_type_input = '<input id="field_type" type="hidden" name="field_type" value="'.$field->field_type.'">'.$field->field_type;  
    }else{
      $field_type_input = JHTML::_('select.genericlist',$field_types, 'field_type',
                         'size="1" onchange="checkFieldType(jQuerCCK(this).val())"', 'value', 'text', 'text_textfield');
    }

    AdminViewField::addLayoutField($option, $field_type_input, $params, $field);
  }

  static function saveLayoutField($fid){
    global $db, $mosConfig_absolute_path;
    $input = JFactory::getApplication()->input;
    $params = new JRegistry;
    $fieldName = trim($input->get("field_name","","STRING"));
    $fieldType = $input->get("field_type","","STRING");
   

    if(trim($fieldType) != 'text_select_list'){
      $allowed_value = $input->get("allowed_value",false,"STRING");
      $str_param = $allowed_value;
      $child_param = '';
    }else{

      $allowed_value = array_diff($input->get("allowed_value", array(),"ARRAY"), array(''));
      $childSelect = $input->get("child_select",array(''),"ARRAY");

      //create string for save in params
      if(count($allowed_value) > 1){
        $str_param = '';
        $child_param = '';
        foreach ($allowed_value as $key => $value) {
          if(!empty($value) && $value != end($allowed_value)){
            $str_param .= $value.'\sprt';
            $child_param .= $childSelect[$key].'|';
          }else{
            $str_param .= $value;
            $child_param .= $childSelect[$key];
          }
        }
      }else{
        $str_param = array_pop($allowed_value);
        $child_param = array_pop($childSelect);
      }
      
    }

    //child selects


    $default_value = $input->get("default_value",'',"STRING");
    $fk_eid = $input->get("fk_eid",0,"INT");
    $params->set("allowed_value",$str_param);
    $params->set("child_select",$child_param);
    $params->set("default_value",$default_value);


    $field = new os_cckEntityField($db);
    if($fid){
      $field->load($fid);
    }
    $field->field_name = $fieldName;
    $field->params = $params->toString();
    $field->field_type = $fieldType;
    $field->fk_eid = $fk_eid;
    if (!$field->store()) {
      JError::raiseWarning(0,addslashes($field->getError()));
    }
  }

  static function saveLayoutFieldName($option){
    global $db, $mosConfig_absolute_path;
    $input  = JFactory::getApplication()->input;
    
    $fieldName = $input->get("field_name","","STRING");
    $fk_eid = $input->get("fk_eid",0,"INT");
    $fid = $input->get("fid",0,"INT");

    if($fid) {
      $field = new os_cckEntityField($db);
      $field->load($fid);
      $field->field_name = $fieldName;
    }

    if (!$field->store()) {
      JError::raiseWarning(0,addslashes($field->getError()));
    }

    echo '<span class="cck-success-message">Field(s) deleted successful.</span>';
    self::showLayoutFields($option);
  }

  static function deleteField($option){ 
    global $db;
    $input  = JFactory::getApplication()->input;
    $fidArr = $input->get("fid",array(),"ARRAY");
    foreach ($fidArr as $field_id) {
      $field = new os_cckEntityField($db);
      $field->load($field_id);
      //delete field from layout
      $query = "SELECT id,layout_html FROM #__os_cck_layout_html";
      $db->setQuery($query);
      $layoutArray = $db->loadObjectList();
      foreach ($layoutArray as $layoutArr) {
        $html = urldecode($layoutArr->layout_html);
        if(!empty($html)){
          $html = htmlspecialchars($html);
          $dom = new DOMDocument;
          $dom->loadHTML($html, LIBXML_HTML_NODEFDTD);
          $finder = new DomXPath($dom);
          $classname="drop-item";
          $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
          foreach ($nodes as $contentNode) {
            if(strpos($contentNode->textContent, '{|f-'.$field_id.'|}') != ''){
              $contentNode->parentNode->removeChild($contentNode);
              $layout_html = $dom->saveHTML();
              $layout_html = str_replace('<html><body>', '', $layout_html);
              $layout_html = str_replace('</body></html>', '', $layout_html);
              $layout_html = urlencode($dom->saveHTML());
              $query = "UPDATE #__os_cck_layout_html SET layout_html='$layout_html' WHERE id=$layoutArr->id";
              $db->setQuery($query);
              $db->query();
            }
          }
        }
      }
      //end layout
      $field->delete();
    }
    echo '<span class="cck-success-message">Field(s) deleted successful.</span>';
    self::showLayoutFields($option);
  }

  static function publishFields($publish, $option){
    global $db, $my;
    $input  = JFactory::getApplication()->input;
    $fid = $input->get("fid",0,"INT");
    $task = $input->get("task",'',"STRING");
    if(!$fid){
      echo '<span class="cck-error-message">Select an item to '.addslashes($task).'</span>';
      self::showLayoutFields($option);
      return;
    }

    $db->setQuery("UPDATE #__os_cck_entity_field SET published='$publish' WHERE fid = $fid");
    if (!$db->query()) {
      echo '<span class="cck-error-message">'.addslashes($db->getErrorMsg()).'</span>';
      self::showLayoutFields($option);
      return;
    }
    self::showLayoutFields($option);
  }

  static function show_in_ins($show, $option){
    global $db, $my;
    $input  = JFactory::getApplication()->input;
    $fid = $input->get("fid",0,"INT");
    $task = $input->get("task",'',"STRING");
    if(!$fid){
      echo '<span class="cck-error-message">Select an item to '.addslashes($task).'</span>';
      self::showLayoutFields($option);
      return;
    }

    $db->setQuery("UPDATE #__os_cck_entity_field SET show_in_instance_menu='$show' WHERE fid = $fid");
    if (!$db->query()) {
      echo '<span class="cck-error-message">'.addslashes($db->getErrorMsg()).'</span>';
      self::showLayoutFields($option);
      return;
    }
    self::showLayoutFields($option);
  }

  static function getSelectList(){
    global $db, $app;
    
    $fk_eid = $app->input->get("fk_eid",'');
    $query = "SELECT fid,field_name FROM #__os_cck_entity_field WHERE field_type = 'text_select_list' AND  fk_eid = '". $fk_eid ."' ORDER BY fid ASC";
    $db->setQuery($query);
    $result = $db->loadAssocList();
    return $result;
  }


}
