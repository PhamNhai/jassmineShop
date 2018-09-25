<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) 
      die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

class AdminRent_history{

  static function users_rent_history($option, $eiid){
    global $db, $user,$app;
    $session = JFactory::getSession();
    // $owner = JRequest::getVar("owner_id",'-1'); //add nik


    $item_sort_param = mosGetParam($_GET, 'sort', 'l.fk_eiid');
    if (is_array($sort_arr = $session->get('eq_itemsort', ''))) {
      if(JRequest::getVar('sorting_direction','')){
        if(JRequest::getVar('sorting_direction')=="ASC"){
          $sort_arr['sorting_direction'] = "DESC";
        }else{
          $sort_arr['sorting_direction'] = "ASC";
        }
      }elseif($session->get('sorting_direction','')){ 
        $sort_arr['sorting_direction'] = $session->get('sorting_direction');
      }else{
        $sort_arr['sorting_direction']="ASC";
      }
      if ($item_sort_param == $sort_arr['field']) {
      } else {
        $sort_arr['field'] = $item_sort_param;
      }

      if($item_sort_param == 'inst_entity'){
        $sort_string = 'ce.name' . " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == 'inst_id'){
        $sort_string = 'l.fk_eiid' . " " . $sort_arr['sorting_direction'];
//////////////////
      }else if($item_sort_param == 'rent_from'){
        $sort_string = 'l.rent_from' . " " . $sort_arr['sorting_direction'];

      }else if($item_sort_param == 'rent_until'){
        $sort_string = 'l.rent_until' . " " . $sort_arr['sorting_direction'];

      }else if($item_sort_param == 'rent_return'){
        $sort_string = 'l.rent_return' . " " . $sort_arr['sorting_direction'];
///////////////
      }else if($item_sort_param == "l.fk_eiid"){
        $sort_string = $item_sort_param . " " . $sort_arr['sorting_direction'];
      }
    } else { 
      $sort_arr = array();
      if(JRequest::getVar('sorting_direction','')){
        $sort_arr['sorting_direction'] = JRequest::getVar('sorting_direction');
      }elseif($session->get('sorting_direction','')){ 
        $sort_arr['sorting_direction'] = $session->get('sorting_direction');
      }else{
        $sort_arr['sorting_direction']="ASC";
      }

      if($item_sort_param == 'inst_entity'){
        $sort_string = 'ce.name'. " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == 'inst_id'. " " . $sort_arr['sorting_direction']){
        $sort_string = 'l.fk_eiid'. " " . $sort_arr['sorting_direction'];
////////////////////////
      }else if($item_sort_param == 'rent_from'){
        $sort_string = 'l.rent_from' . " " . $sort_arr['sorting_direction'];
        
      }else if($item_sort_param == 'rent_until'){
        $sort_string = 'l.rent_until' . " " . $sort_arr['sorting_direction'];

      }else if($item_sort_param == 'rent_return'){
        $sort_string = 'l.rent_return' . " " . $sort_arr['sorting_direction'];
/////////////
      }else if($item_sort_param == "l.fk_eiid"){
        $sort_string = $item_sort_param. " " . $sort_arr['sorting_direction'];
      }
      $sort_arr['field'] = $item_sort_param;
    }

    $session->set('eq_itemsort', $sort_arr);

    $owner = $app->getUserStateFromRequest("owner_id", 'owner_id', '');

    $rows = $show_fields = array();
    if($owner !=-1){
      $select="SELECT a.*, cc.name AS category, l.id as rent_id, cl.params as lay_params,ce.name AS entity, l.rent_from as rent_from, " .
          "\n l.rent_return as rent_return, l.rent_until as rent_until, " .
          "\n l.user_name as user_name, l.user_email as user_email, " .
          "\n cc.rent_request AS for_rent" .
          "\n FROM #__os_cck_entity_instance AS a" .
          "\n LEFT JOIN #__os_cck_categories_connect AS CC_rel ON CC_rel.fk_eiid = a.eiid" .
          "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = CC_rel.fk_cid" .
          "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = a.fk_lid ".
          "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = a.fk_eid ".
          "\n LEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = a.eiid" .
          "\n WHERE l.fk_userid=".$db->Quote($owner).
          "\n OR l.user_name=".$db->Quote($owner)." ORDER BY $sort_string";
      $db->setQuery($select);
      if (!$db->query()) {
        echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
        exit ();
      }
      $rows = $db->loadObjectList();

      // print_r($rows);
      /////

      //id = l.fk_eiid

      $show_fields = $entityEaaray = array();
      if(count($rows)>0){
        foreach ($rows as $row) {
          $lay_params = unserialize($row->lay_params);
          $entityEaaray[] = $row->fk_eid;
          $layoutArray[] = $row->fk_lid;
        }
        foreach(array_unique($entityEaaray) as $key => $value){
          $entity = new os_cckEntity($db);
          $entity->load($value);
          $layout = new os_cckLayout($db);
          $layout->load($layoutArray[$key]);
          $bootstrap_version = $session->get( 'bootstrap','2');
          $layout_html = urldecode($layout->getLayoutHtml($bootstrap_version));
          $layout_params = unserialize($layout->params);
          $extra_fields_list = $entity->getFieldList();
          foreach($extra_fields_list as $Fieldvalue){
            if($Fieldvalue->show_in_instance_menu && strpos($layout_html,"{|f-".$Fieldvalue->fid."|}")){
              $fieldNames[$key]['ent_name'] = $entity->eid;
              $fieldNames[$key]['field_type'] = $Fieldvalue->field_type;
              $fieldNames[$key]['fields'][] = $Fieldvalue->db_field_name;//need for use in search // [][table_name][column_mname]
              $show_fields[$value][]= $Fieldvalue;
            }
          }
        }
        ksort($show_fields);
      }
    }

    


    $userlist[] = JHTML::_('select.option','-1', 'Select User');
    $db->setQuery("SELECT DISTINCT fk_userid AS value, user_name AS text from #__os_cck_rent ORDER BY user_name");
    $userlist = array_merge($userlist, $db->loadObjectList());

    foreach ($userlist as $value) {
        if(!$value->value)
            $value->value = $value->text;
    }


    $usermenu = JHTML::_('select.genericlist',$userlist, 'owner_id', 'class="inputbox input-medium" size="1" 
        onchange="document.adminForm.submit();"', 'value', 'text', $owner);


    
    AdminViewRentHistory:: showUsersRentHistory($option, $rows,$return_item,$history_item, $usermenu, "rent",$show_fields,$usermenu, $sort_arr);
  }

}
