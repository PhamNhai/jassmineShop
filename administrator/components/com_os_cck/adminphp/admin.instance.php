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


class AdminInstance{
  static function showInstances($option){
    global $db, $app, $jConf;
    $session = JFactory::getSession();


    // $session = JFactory::getSession();
    // $session->destroy();

    // SORTING parameters start
    $session = JFactory::getSession();
    $item_sort_param = mosGetParam($_GET, 'sort', 'jei.eiid');
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
        $sort_string = 'jei.fk_eid' . " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == 'inst_id'){
        $sort_string = 'jei.eiid' . " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == "jei.eiid"){
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
        $sort_string = 'jei.fk_eid'. " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == 'inst_id'. " " . $sort_arr['sorting_direction']){
        $sort_string = 'jei.eiid'. " " . $sort_arr['sorting_direction'];
      }else if($item_sort_param == "jei.eiid"){
        $sort_string = $item_sort_param. " " . $sort_arr['sorting_direction'];
      }
      $sort_arr['field'] = $item_sort_param;
    }

    $session->set('eq_itemsort', $sort_arr);

    //maybe it is search below
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $catid = $app->getUserStateFromRequest("catid{$option}", 'catid', '');
    $pub = $app->getUserStateFromRequest("pub{$option}", 'pub', '');
    $approved = $app->getUserStateFromRequest("appr{$option}", 'appr', '');
    $userid = $app->getUserStateFromRequest("userid{$option}", 'userid', '');
    $search = trim($app->getUserStateFromRequest("search{$option}", 'search', ''));
    $entity_id = $app->getUserStateFromRequest("entity_id{$option}", 'entity_id', '');
    $entities = array();
    $entities[] = array('value' => '', 'text' => 'All entities');
    // $query = "SELECT ent.eid AS value, ent.name AS text FROM #__os_cck_entity as ent"
    //           ."\n LEFT JOIN #__os_cck_layout as lay ON lay.fk_eid = ent.eid WHERE lay.type = 'add_instance' GROUP BY ent.eid";
    $query = "SELECT eid AS value, name AS text FROM #__os_cck_entity ORDER BY name ";

    $db->setQuery($query);
    $ent = $db->loadObjectList("value");


    $entities = (count($ent) > 1) ? array_merge($entities, (array)$ent) : $entities;
    $entity_list = JHTML::_('select.genericlist',$entities, 'entity_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $entity_id);


    $where = $where2 = array();
    $catwhere = "";
    if ($entity_id != '' && isset($ent[$entity_id])) {
        array_push($where, "jei.fk_eid ='{$entity_id}'");
    }

    if ($approved == "appr") {
      array_push($where, "jei.approved = 1");
    } else if ($approved == "not_appr") {
      array_push($where, "jei.approved = 0");
    }

    if ($pub == "pub") {
      array_push($where, "jei.published = 1");
    } else if ($pub == "not_pub") {
      array_push($where, "jei.published = 0");
    }

    if($userid != 0){
      array_push($where, "jei.fk_userid = " . $userid);
    }

    if ($catid > 0) {
      array_push($where, "c.fk_cid='$catid'");
    }

    array_push($where, "cl.type = 'add_instance'");

    //pagination?*
    $selectstring = "SELECT count(DISTINCT jei.eiid) " .
      "\nFROM #__os_cck_entity_instance AS jei" .
      "\nLEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
      "\nLEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid " .
      "\nLEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ";

    if($search || JRequest::getVar('sort','')){
      $fieldNames = $session->get('field_names');
      foreach ($fieldNames as $value){
        foreach ($value['fields'] as $name) {
          if($value['field_type'] == 'categoryfield' && $name == JRequest::getVar('sort','')){
            $sort_string = 'cc.title'. " " . $sort_arr['sorting_direction'];
            continue;
          }
          array_push($where2, '#__os_cck_content_entity_'.$value['ent_name'].'.'.$name." LIKE '%$search%' ");
        }
        $selectstring .= "\nLEFT JOIN #__os_cck_content_entity_".$value['ent_name']." ON #__os_cck_content_entity_".$value['ent_name'].".fk_eiid = jei.eiid ";
      }
      array_push($where2, "jei.eiid LIKE '%$search%' ");
    }

    $selectstring .=  "\nLEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
      "\nLEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = jei.eiid  and l.rent_return is null " .
      "\nLEFT JOIN #__users AS u ON u.id = jei.checked_out " .
      (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");

    if($search){
      $conditions_connect = count($where) ? 'AND' : 'WHERE';
      $selectstring .=  (count($where2) ? "\n".$conditions_connect." (" . implode(' OR ', $where2).')' : "");
    }
    $db->setQuery($selectstring);

    $total = $db->loadResult();
    echo $db->getErrorMsg();
    $pageNav = new JPagination($total, $limitstart, $limit);

    $selectstring = "SELECT jei.*, cl.title as lay_title, cl.type as lay_type, cl.params as lay_params, GROUP_CONCAT(DISTINCT cc.title SEPARATOR ', ') AS category, ce.name AS entity, " .
      "\nl.id as rentid, l.rent_from as rent_from, l.rent_return as rent_return,l.rent_until as rent_until,u.name AS editor " .
      "\nFROM #__os_cck_entity_instance AS jei" .
      "\nLEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
      "\nLEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid " .
      "\nLEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ";

      if($search || JRequest::getVar('sort','')){
        $fieldNames = $session->get('field_names');
        foreach ($fieldNames as $value) {
          foreach ($value['fields'] as $name) {
            if($value['field_type'] == 'categoryfield' && $name == JRequest::getVar('sort','')){
              $sort_string = 'cc.title'. " " . $sort_arr['sorting_direction'];
              continue;
            }
            if(!isset($sort_string) && (isset($item_sort_param) && !empty($item_sort_param))){
              if($item_sort_param == $name){
                $sort_string = '#__os_cck_content_entity_'.$value['ent_name'].'.'.$name." ".$sort_arr['sorting_direction'];
              }
            }
            array_push($where2, '#__os_cck_content_entity_'.$value['ent_name'].'.'.$name." LIKE '%$search%' ");
          }
          $selectstring .= "\nLEFT JOIN #__os_cck_content_entity_".$value['ent_name']." ON #__os_cck_content_entity_".$value['ent_name'].".fk_eiid = jei.eiid ";
        }
        array_push($where2, "jei.eiid LIKE '%$search%' ");
      }

    $selectstring .= "\nLEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
      "\nLEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = jei.eiid  and l.rent_return is null " .
      "\nLEFT JOIN #__users AS u ON u.id = jei.checked_out " .
      (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");

    if($search){
      $conditions_connect = count($where) ? 'AND' : 'WHERE';
      $selectstring .=  (count($where2) ? "\n".$conditions_connect." (" . implode(' OR ', $where2).')' : "");
    }
    $selectstring .= "\n GROUP BY jei.eiid " .
      "\nORDER BY jei.notreaded desc, $sort_string " .
      "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();

    // echo "<pre>";
    // print_r($selectstring);
    // echo "<pre>";
    // exit;

    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }


    $show_fields = $fieldNames = $entityEaaray = array();
    if(count($rows)>0){
      $date = strtotime(JFactory::getDate()->toSql());
      foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($date - $check);
        if (($remain <= 0) && ($row->checked_out != 0)) {
            $db->setQuery("UPDATE #__os_cck_entity_instance SET checked_out=0,checked_out_time=0");
            $db->query();
            $row->checked_out = 0;
            $row->checked_out_time = 0;
        }
        if($row->lay_type != ''){  
          $lay_params = unserialize($row->lay_params);
          $entityEaaray[] = $row->fk_eid;
          $layoutArray[] = $row->fk_lid;
        }


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
    $session->set('field_names', $fieldNames);
    $categories[] = JHTML::_('select.option','-1', JText::_('COM_OS_CCK_LABEL_SELECT_ALL_CATEGORIES'),'value','text');
    //************* begin add for sub category in select in manager houses  *************
    $options = $categories;
    $id = 0;
    $fromSearch=0;
    $list = CAT_Utils::categoryArray('com_os_cck',$fromSearch);
    $cat = new os_cckCategory($db);
    $cat->load($id);

    $this_treename = '';
    foreach ($list as $item) {
      if ($this_treename) {
          if ($item->cid != $cat->cid && strpos($item->title, $this_treename) === false) {
              $options[] = JHTML::_('select.option',$item->cid, $item->title,'value','text');
          }
      } else {
          if ($item->cid != $cat->cid) {
              $options[] = JHTML::_('select.option',$item->cid, $item->title,'value','text');
          } else {
              $this_treename = "$item->title/";
          }
      }
    }

    // print_r($list);exit;
    $clist = JHTML::_('select.genericlist',$options, 'catid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $catid); //new nik edit

    $pubmenu[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_LABEL_SELECT_TO_PUBLIC'),'value','text');
    $pubmenu[] = JHTML::_('select.option','not_pub', JText::_('COM_OS_CCK_LABEL_SELECT_NOT_PUBLIC'),'value','text');
    $pubmenu[] = JHTML::_('select.option','pub', JText::_('COM_OS_CCK_LABEL_SELECT_PUBLIC'),'value','text');
    $publist = JHTML::_('select.genericlist',$pubmenu, 'pub', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $pub);

    $approvedmenu[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_LABEL_SELECT_TO_PUBLIC'),'value','text');
    $approvedmenu[] = JHTML::_('select.option','not_appr', JText::_('COM_OS_CCK_LABEL_UNAPPROVED'),'value','text');
    $approvedmenu[] = JHTML::_('select.option','appr', JText::_('COM_OS_CCK_LABEL_APPROVED'),'value','text');
    $approvedlist = JHTML::_('select.genericlist',$approvedmenu, 'appr', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $approved);

    $select = "SELECT DISTINCT fk_userid FROM #__os_cck_entity_instance";
    $db->setQuery($select);
    $users = $db->loadObjectList();

    $userOpt = array();
    $userOpt[] = JHTML::_('select.option', '', 'All Users','value','text');
    foreach ($users as $user => $value) {
        if($value->fk_userid == 0) $value->fk_userid = JText::_("COM_OS_CCK_LABEL_ALL_OWNERS");
        $userOpt[] = JHTML::_('select.option', $value->fk_userid, @JFactory::getUser($value->fk_userid)->name,'value','text');
    }

    $userslist = JHTML::_('select.genericlist',$userOpt, 'userid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $userid);



    AdminViewInstance::showInstances($option, $rows, $clist, $publist, $search, $pageNav, $sort_arr, $show_fields, $entity_list, $approvedlist, $userslist);
  }

  static function showInstancesModal($option){
    global $db, $app;
    $session = JFactory::getSession();
    $where = array();
    array_push($where, "cl.type = 'add_instance'");
    array_push($where, "jei.published = 1");
    array_push($where, "jei.fk_eid = ".$_REQUEST['fk_eid']);

    $selectstring = "SELECT jei.*, cl.title as lay_title, cl.type as lay_type, cl.params as lay_params, cc.title AS category, ce.name AS entity" .
      "\n FROM #__os_cck_entity_instance AS jei" .
      "\n LEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
      "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid ".
      "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ".
      "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
      (count($where) ? "\n WHERE " . implode(' AND ', $where) : "");
    
    $selectstring .= "\n GROUP BY jei.eiid ORDER BY jei.eiid";



    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();



    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    $show_fields = $fieldNames = $entityEaaray = array();
    if(count($rows)>0){
      foreach ($rows as $row) {
        // $lay_params = unserialize($row->lay_params);
        $entityEaaray[] = $row->fk_eid;
        $layoutArray[] = $row->fk_lid;
      }
      foreach(array_unique($entityEaaray) as $key => $value){
        $entity = new os_cckEntity($db);
        $entity->load($value);
        $layout = new os_cckLayout($db);
        $layout->load($layoutArray[$key]);
        $bootstrap_version = $session->get( 'bootstrap','2');
        // $layout_html = urldecode($layout->getLayoutHtml($bootstrap_version));
        // $layout_params = unserialize($layout->params);
        $extra_fields_list = $entity->getFieldList();
        foreach($extra_fields_list as $Fieldvalue){
          if($Fieldvalue->show_in_instance_menu){
            $fieldNames[$key]['ent_name'] = $entity->eid;
            $fieldNames[$key]['field_type'] = $Fieldvalue->field_type;
            $fieldNames[$key]['fields'][] = $Fieldvalue->db_field_name;//need for use in search // [][table_name][column_mname]
            $show_fields[$value][]= $Fieldvalue;
          }
        }
      }
      ksort($show_fields);
    }
    AdminViewInstance :: showInstancesModal($option, $rows, $clist, $publist, $search, $pageNav, $sort_arr, $show_fields);
  }

  static function showInstanceModalPlg($option){
    global $db, $app;
    $session = JFactory::getSession();
    $where = array();
    $lid = JRequest::getVar('lid','');
    array_push($where, "cl.type = 'add_instance'");
    array_push($where, "jei.published = 1");
    array_push($where, "jei.fk_eid =".$_REQUEST['fk_eid']);

    $selectstring = "SELECT jei.*, cl.title as lay_title, cl.type as lay_type, cl.params as lay_params, cc.title AS category, ce.name AS entity" .
      "\n FROM #__os_cck_entity_instance AS jei" .
      "\n LEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
      "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid ".
      "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ".
      "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
      (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");
    
    $selectstring .= "\nORDER BY jei.eiid";
    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();

    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    $show_fields = $fieldNames = $entityEaaray = array();
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

    AdminViewInstance :: showInstanceModalPlg($option, $rows, $clist, $publist, $search, $pageNav,
                                               $sort_arr, $show_fields, $lid);
  }

  static function editInstance($option, $eiid){
    global $db, $user,$app;

    $doc = JFactory::getDocument();
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/admin_style.css");
    $doc->addScript(JURI::root() . "components/com_os_cck/assets/bootstrap/js/bootstrapCCK.js");
    $session = JFactory::getSession();
    $select_type = JRequest::getVar('lay_type','');
    $entityInstance = new os_cckEntityInstance($db);
    $entityInstance->load(intval($eiid));
    if(intval($eiid)){
      $query="UPDATE #__os_cck_entity_instance SET notreaded=0 WHERE eiid=".intval($eiid);
      $db->setQuery($query);
      $db->query();
    }
    $instance_layout = new os_cckLayout($db);
    $is_new = false;
    if ($eiid === 0) {
      $is_new = true;
    } else {
      $entity = new os_cckEntity($db);
      $entity->load($entityInstance->fk_eid);
      $select_type = $entityInstance->fk_lid; 
    }

    $input = $app->input;
    $lid = $input->getInt('lay_type', false);

    if($lid){
      $select_type = $lid;
    }

    $query = "SELECT c.title,c.lid,c.type,c.params,c.fk_eid,ent.name FROM #__os_cck_layout as c"
            ."\n LEFT JOIN #__os_cck_entity as ent ON c.fk_eid = ent.eid WHERE c.type='add_instance' "
            ."\n AND c.published = '1'";
    $db->setQuery($query);
    $layouts = $db->loadObjectList();


    if(!$layouts){
      $app->redirect("index.php?option=$option&task=manage_layout", JText::_('COM_OS_CCK_CREATE_ADD_LAYOUT'));
    }
    

    $default_layout = $instance_layout->getDefaultLayout($entityInstance->fk_eid,'add_instance');

    $lid_array = [];
    foreach($layouts as $key => $value){
      $lid_array[] = $value->lid;
    }

    if(!in_array($select_type, $lid_array)){
      $select_type = $default_layout;
    }

    foreach($layouts as $key => $value){
      $params = unserialize($value->params);
      $params['views']['instance_type'] = $value->type;
      if($select_type){
        if($select_type == $value->lid){
          $lid = $value->lid;
          $layout_params = $params;  
          $ceid = $value->fk_eid;
        }
      }else{
        if(!$key){//select first option
          $lid = $value->lid;
          $layout_params = $params;
          $ceid = $value->fk_eid;
        }
      }
      $type[]= JHTML::_('select.option',"$value->lid",$value->title.'('.$value->name.')');
    }



    
    // if($is_new){
      $layout_type = JHTML::_('select.genericlist',$type, 'lay_type', 'class="inputbox" size="1" onchange="swich_task('."'edit_instance'".');lay_type_select();"', 'value', 'text', $select_type);
    // }else{
    //   $layout_type = JHTML::_('select.genericlist',$type, 'lay_type', 'class="inputbox" size="1" disabled="disabled"', 'value', 'text', $select_type);
    // }

    $categories = array();
    $db->setQuery("SELECT cid AS value, name AS text FROM #__os_cck_categories" .
                        " ORDER BY ordering");
    $cat = $db->loadObjectList();
    if (count($cat) > 0) $categories = array_merge($categories, $cat);
    $query = "SELECT fk_cid FROM #__os_cck_categories_connect WHERE fk_eiid='" . $entityInstance->eiid . "'";
    $db->setQuery($query);
    $cid = $db->loadResult();
    $clist = HTML::categoryList($cid);


    if ($entityInstance->checked_out && $entityInstance->checked_out <> $user->id) {
      $app->redirect("index.php?option=$option", JText::_('COM_OS_CCK_IS_EDITED'));
    }

    if (!$is_new) {
      $entityInstance->checkout($user->id);
      // $entityInstance->fk_userid = $user->id;
    } else {
      // initialise new record
      $entityInstance->published = 0;
      $entityInstance->approved = 0;
    }

    if ($is_new) $query = 'SELECT eid,name FROM #__os_cck_entity WHERE published="1" ORDER BY name ';
    else $query = 'SELECT eid,name FROM #__os_cck_entity ORDER BY name ';
    $db->setQuery($query);
    $entities_list = $db->loadObjectList();
    if (count($entities_list) < 1) {
        $app->redirect("index.php?option=com_os_cck&task=manage_entities",JText::_("COM_OS_CCK_CREATE_ENTITY"));
    }
    if ($is_new && $ceid != 0) $entityInstance->fk_eid = $ceid;
    $extra_fields_list = $entityInstance->getFields();


    $layout_params['fields'] = $layout_params['fields'];
    $layout_params['instance_type'] = $layout_params['views']['instance_type'];
    $layout_params['categories_list'] = $clist;
    $layout_params['layout_type'] = $layout_type;
    $layout_params['extra_fields_list'] = $extra_fields_list;
    $instance_layout->load($lid);
    $bootstrap_version = $session->get( 'bootstrap','2');
    $instance_layout->layout_html = $instance_layout->getLayoutHtml($bootstrap_version);



    AdminViewInstance::editInstance($option, $entityInstance, $instance_layout, $layout_params);
  }

  static function saveInstance($option){


    global $db, $user,$task, $app;
    $session = JFactory::getSession();
    $post = $_POST;

    if(isset($post['eiid'])) unset($post['eiid']);

    $instance = new os_cckEntityInstance($db);
    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $data['fields_data'] = array();
    
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0) $data['fields_data'][str_replace('fi_', '', $key)] = $var;
    }

    if ($post['id'] != 0) {
      $instance->load($post['id']);
      $data['changed'] = date("Y-m-d H:i:s");
    } else {
      $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".$post['lay_type'];
      $db->setQuery($query);
      $data['fk_eid'] = $db->loadResult();
      $data['created'] = date("Y-m-d H:i:s");
    }
    $data['title'] = JRequest::getVar('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    //$data['fk_userid'] = $user->id;
    if(JRequest::getVar('lay_type',''))
      $data['fk_lid'] = JRequest::getVar('lay_type');
    $data['published'] = 1;
    $data['approved'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $instance->fields_data = '';
    //calculate price


    $instance->instance_currency = isset($_REQUEST['instance_currency'])?$_REQUEST['instance_currency']:'';
    $total_price=0;
    if(isset($_REQUEST['price_fields'])){
      foreach ($_REQUEST['price_fields'] as $price) {
        if(isset($data['fields_data'][$price])){
          $total_price += $data['fields_data'][$price];
        }
      }
    }
    $instance->instance_price = $total_price;
    //end
    $instance->categories = '';
    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    //entity_name, entity_tbale_name
    $entitty = new os_cckEntity($db);
    $entitty->load($instance->fk_eid);
    $instance->_entity_name = $entitty->name;
    $instance->_entity_table_name = "#__os_cck_entity_" . $entitty->name;
    $layout = new os_cckLayout($db);
    $layout->load($instance->fk_lid);
    $layout_params = unserialize($layout->params);
    $bootstrap_version = $session->get( 'bootstrap','2');
    $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
    $instance->_field_list = $entitty->getFieldList($layout->layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);
    $instance->notreaded = 0;
    $instance->featured_clicks = ($data['featured_clicks'] === '')?-1:$data['featured_clicks'];
    $instance->featured_shows = ($data['featured_shows'] === '')?-1:$data['featured_shows'];

    $instance->fk_userid = ($data['fk_userid'] === '')?JFactory::getUser()->id:$data['fk_userid'];
    
    $instance->_layout_params = $layout_params['fields'];
    $instance->fk_lid = $layout->lid;
  //  $instance->add_instance = true;
    $bootstrap_version = $session->get( 'bootstrap','2');
    //$instance->_layout_html = $layout->getLayoutHtml($bootstrap_version);

    // print_r($instance);
    // exit;
    $instance->check();

    //if date field apply data_transform_cck
    foreach ($instance->_field_list as $field) {
      if($field->field_type == 'datetime_popup'){
        $date_format = $layout_params['fields']['datetime_popup_'.$field->fid.'_input_format'];
        $time_format = $layout_params['fields']['datetime_popup_'.$field->fid.'_input_time_format'];
        $format = $date_format.' '.$time_format;
        $date = $instance->fields_data['datetime_popup_'.$field->fid];
        $instance->fields_data['datetime_popup_'.$field->fid] = data_transform_cck($date, $format);
      }
    }

    
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }



    $instance->store();
    switch ($task) {
      case 'apply_instance':
          $app->redirect("index.php?option={$option}&task=edit_instance&eiid[]=" . $instance->eiid);
          break;
      case 'save_instance':
          $app->redirect("index.php?option={$option}");
          break;
    }
  }


  static function removeInstances($eiid, $option){
    global $db,$app;
    if (!is_array($eiid) || count($eiid) < 1) {
      echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
      exit;
    }
    if (count($eiid)) {
      foreach ($eiid as $id) {
        $instance = new os_cckEntityInstance($db);
        $instance->load($id);
        $instance->delete();
      }
    }
    $app->redirect("index.php?option=$option");
  }

  static function publishInstances($eiids, $publish, $option){

    global $db, $user,$app;
    $catid = mosGetParam($_POST, 'catid', array(0));
    if (!is_array($eiids) || count($eiids) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $eiids = implode(',', $eiids);
    $db->setQuery("UPDATE #__os_cck_entity_instance SET published='$publish'"
                    . "\n WHERE eiid IN ($eiids) AND (checked_out=0 OR (checked_out='$user->id'))");
    if (!$db->query()) {
      echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (count($eiids) == 1) {
      $instance = new os_cckEntityInstance($db);
      $instance->checkin($eiids[0]);
    }
    $app->redirect("index.php?option=$option");
  }

  static function approveInstances($eiids, $publish, $option){

    global $db, $user, $app;
    $catid = mosGetParam($_POST, 'catid', array(0));
    if (!is_array($eiids) || count($eiids) < 1) {
      $action = $publish ? 'approve' : 'unapprove';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $eiids = implode(',', $eiids);
    $db->setQuery("UPDATE #__os_cck_entity_instance SET approved='$publish'"
                    . "\n WHERE eiid IN ($eiids) AND (checked_out=0 OR (checked_out='$user->id'))");
    if (!$db->query()) {
      echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (count($eiids) == 1) {
      $instance = new os_cckEntityInstance($db);
      $instance->checkin($eiids[0]);
    }
    $app->redirect("index.php?option=$option");
  }


  /**
   * Cancels an edit operation
   * @param string - The current author option
   */
  static function cancelInstance($option){
    global $db,$app;
    if(JRequest::getVar('id','')){
      $row = new os_cckEntityInstance($db);
      $row->load($_REQUEST['id']);
      $row->checkin();
    }
    $app->redirect("index.php?option=$option");
  }


}
