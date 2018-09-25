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

class AdminLayout{
  static function showLayouts($option){
    global $db, $app, $jConf;
    $query = 'SELECT eid,name FROM #__os_cck_entity WHERE published="1" ORDER BY name ';
    $db->setQuery($query);
    $entities_list = $db->loadObjectList();

    if (count($entities_list) < 1) {
      $app->redirect("index.php?option=com_os_cck&task=manage_entities",
        JText::_("COM_OS_CCK_CREATE_ENTITY"));
    }
    // SORTING parameters start
    $item_session = JFactory::getSession();
    //  $item_session->set('eq_itemsort','');
    $item_sort_param = mosGetParam($_GET, 'sort', '');
    $item_sort_param = preg_replace('/[^A-Za-z0-9_]*/', '', $item_sort_param);
    if ($item_sort_param == '') {
      if (is_array($sort_arr = $item_session->get('eq_itemsort', ''))) {
        $sort_string = $sort_arr['field'] . " " . $sort_arr['sorting_direction'];
      } else {
        $sort_string = 'title';
        $sort_arr = array();
        $sort_arr['field'] = 'title';
        $sort_arr['sorting_direction'] = '';
        $item_session->set('eq_itemsort', $sort_arr);
      }
    } else {
      if (is_array($sort_arr = $item_session->get('eq_itemsort', ''))) {
        if ($item_sort_param == $sort_arr['field']) {
          if ($sort_arr['sorting_direction'] == 'DESC')
            $sort_arr['sorting_direction'] = '';
          else
            $sort_arr['sorting_direction'] = 'DESC';
        } else {
          $sort_arr['field'] = $item_sort_param;
          $sort_arr['sorting_direction'] = '';
        }
        $sort_string = $sort_arr['field'] . " " . $sort_arr['sorting_direction'];
      } else {
        $sort_string = 'title';
        $sort_arr = array();
        $sort_arr['field'] = 'title';
        $sort_arr['sorting_direction'] = '';
      }
      $item_session->set('eq_itemsort', $sort_arr);
    }
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $pub = $app->getUserStateFromRequest("pub{$option}", 'pub', '-1');
    $type = $app->getUserStateFromRequest("sort_field_type{$option}", 'sort_field_type', '-1');
    $search = $app->getUserStateFromRequest("search{$option}", 'search', '');
    $entity_id = $app->getUserStateFromRequest("entity_id{$option}", 'entity_id', '');
    $where = array();
    $catwhere = "  ";
    if ($type == 'instance') {
      array_push($where, "( cl.type='instance' )");
    }
    if ($type == 'all_instance') {
      array_push($where, "( cl.type='all_instance' )");
    }
    if ($type == 'calendar') {
      array_push($where, "( cl.type='calendar' )");
    }
    if ($type == 'show_review_instance') {
      array_push($where, "( cl.type='show_review_instance' )");
    }
    if ($type == 'add_instance') {
      array_push($where, "( cl.type='add_instance' )");
    }
    if ($type == 'request_instance') {
      array_push($where, "( cl.type='request_instance' )");
    }
    if ($type == 'buy_request_instance') {
      array_push($where, "( cl.type='buy_request_instance' )");
    }
    if ($type == 'rent_request_instance') {
      array_push($where, "( cl.type='rent_request_instance' )");
    }
    if ($type == 'review_instance') {
      array_push($where, "( cl.type='review_instance' )");
    }
    if ($type == 'category') {
      array_push($where, "( cl.type='category' )");
    }
    if ($type == 'all_categories') {
      array_push($where, "( cl.type='all_categories' )");
    }

    if ($type == 'search') {
      array_push($where, "( cl.type='search' )");
    }
    if ($search) {
      array_push($where, "(LOWER(cl.title) LIKE '%$search%' )");
    }
    if ($entity_id != '') {
      array_push($where, "ce.eid ='{$entity_id}'");
    }
    $db->setQuery(" SELECT COUNT(cl.lid) FROM #__os_cck_layout AS cl " .
                    " \n LEFT JOIN  #__os_cck_categories AS cc ON cl.fk_eid=cc.cid " .
                    " \n LEFT JOIN  #__os_cck_entity   AS ce ON cl.fk_eid=ce.eid " .
                    (count($where) ?
                    " \n WHERE " . implode(' AND ', $where) : "  "));
    $total = $db->loadResult();
    $pageNav = new JPagination($total, $limitstart, $limit);
    $selectstring = " SELECT cl.*, cc.name AS category,  ce.name AS entity FROM #__os_cck_layout AS cl " .
                    " \n LEFT JOIN  #__os_cck_categories AS cc ON cl.fk_eid=cc.cid " .
                    " \n LEFT JOIN  #__os_cck_entity   AS ce ON cl.fk_eid=ce.eid " .
                    (count($where) ?
                    " \n WHERE " . implode(' AND ', $where) : "  ") .
                    " \n ORDER BY ce.eid, cl.type " .
                    " \n LIMIT $pageNav->limitstart,$pageNav->limit; ";
    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();

    if(count($rows)>0)
    {
      $date = strtotime(JFactory::getDate()->toSql());
      foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($date - $check);
        if (($remain <= 0) && ($row->checked_out != 0)) {
            $db->setQuery("UPDATE #__os_cck_layout SET checked_out=0,checked_out_time=0");
            $db->query();
            $row->checked_out = 0;
            $row->checked_out_time = 0;
        }
      }
    }

    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    $type_layout[] = JHTML::_('select.option','', JText::_('COM_OS_CCK_LABEL_SELECT_ALL_VIEW_TYPE'));
    $type_layout[] = JHTML::_('select.option','all_instance', 'Show all Instances');
    $type_layout[] = JHTML::_('select.option','calendar', 'Show Calendar');
    $type_layout[] = JHTML::_('select.option','instance', 'Show instance');
    $type_layout[] = JHTML::_('select.option','all_categories', 'Show all categories');
    $type_layout[] = JHTML::_('select.option','category', 'Show category');
    $type_layout[] = JHTML::_('select.option','show_review_instance', 'Show review');
    $type_layout[] = JHTML::_('select.option','search', 'Show Search');
    $type_layout[] = JHTML::_('select.option','add_instance', 'Add instance');
    $type_layout[] = JHTML::_('select.option','request_instance', 'Add request form');
    $type_layout[] = JHTML::_('select.option','buy_request_instance', 'Add buy request form');
    $type_layout[] = JHTML::_('select.option','rent_request_instance', 'Add rent request form');
    $type_layout[] = JHTML::_('select.option','review_instance', 'Add review form');
    $type_list = JHTML::_('select.genericlist',$type_layout, 'sort_field_type',
                                       'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $type);
    $list = array();
    $list['type'] = $type_list;
    $entities = array();
    $entities[] = array('value' => '', 'text' => 'All entities');
    $query = "SELECT eid AS value, name AS text FROM #__os_cck_entity  ORDER BY name";
    $db->setQuery($query);
    $ent = $db->loadObjectList();
    $entities = (count($ent) > 1) ? array_merge($entities, (array)$ent) : $entities;
    $list['entity_list'] = JHTML::_('select.genericlist',$entities, 'entity_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $entity_id);
    AdminViewLayout :: showLayouts($option, $rows, $list, $search, $pageNav, $sort_arr);
  }

  static function showLayoutsModal($option){
    global $db, $app, $moduleId;
    $moduleId = JRequest::getVar('module_id','');
    $query = 'SELECT eid,name FROM #__os_cck_entity WHERE published="1" ORDER BY name ';
    $db->setQuery($query);
    $entities_list = $db->loadObjectList();
    if (count($entities_list) < 1) {
      JError::raiseWarning(0, JText::_("Create layout first!"));
      return;
    }
    // $entity_id = $app->getUserStateFromRequest("entity_id{$option}", 'entity_id', '');

    $entity_id = '';
    $where = array();
//andrew add need check
    if ($entity_id != '') {
      array_push($where, "ce.eid ='{$entity_id}'");
    }
    if($moduleId){
//andrew added comment meed check    
//      array_push($where, "cl.type != 'review_instance'");
//      array_push($where, "cl.type != 'rent_request_instance'");
//      array_push($where, "cl.type != 'buy_request_instance'");
    }else{
      if(JRequest::getVar('layout_type') == 'request_instance'){
        array_push($where, "cl.type ='".JRequest::getVar('layout_type')."' OR cl.type='add_instance'");

      }else{
        array_push($where, "cl.type ='".JRequest::getVar('layout_type')."'");
      }
    }
//end andrew added need chyeck    
    array_push($where, "cl.published = 1");
    $selectstring = " SELECT cl.*, cc.name AS category,  ce.name AS entity FROM #__os_cck_layout AS cl " .
                    " \n LEFT JOIN  #__os_cck_categories AS cc ON cl.fk_eid=cc.cid " .
                    " \n LEFT JOIN  #__os_cck_entity   AS ce ON cl.fk_eid=ce.eid " .
                    (count($where) ?
                    " \n WHERE " . implode(' AND ', $where) : "  ") .
                    " \n ORDER BY ce.eid, cl.type ";
    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    AdminViewLayout :: showLayoutsModal($option, $rows);
  }

  static function showLayoutsModalPlg($option){
    global $db, $app, $moduleId;
    $moduleId = JRequest::getVar('module_id','');
    $query = 'SELECT eid,name FROM #__os_cck_entity WHERE published="1" ORDER BY name ';
    $db->setQuery($query);
    $entities_list = $db->loadObjectList();
    if (count($entities_list) < 1) {
        $app->redirect("index.php?option=com_os_cck&task=manage_entities",
        JText::_("COM_OS_CCK_CREATE_ENTITY"));
    }
    $entity_id = $app->getUserStateFromRequest("entity_id{$option}", 'entity_id', '');
    $entity_id = '';
    $where = array();
    if ($entity_id != '') {
      array_push($where, "ce.eid ='{$entity_id}'");
    }

    array_push($where, "cl.type != 'review_instance'");
    array_push($where, "cl.type != 'rent_request_instance'");
    array_push($where, "cl.type != 'buy_request_instance'");

    array_push($where, "cl.published = 1");
    $selectstring = " SELECT cl.*, cc.name AS category,  ce.name AS entity FROM #__os_cck_layout AS cl " .
                    " \n LEFT JOIN  #__os_cck_categories AS cc ON cl.fk_eid=cc.cid " .
                    " \n LEFT JOIN  #__os_cck_entity   AS ce ON cl.fk_eid=ce.eid " .
                    (count($where) ?
                    " \n WHERE " . implode(' AND ', $where) : "  ") .
                    " \n ORDER BY ce.eid, cl.type ";
    $db->setQuery($selectstring);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
      echo $db->stderr();
      return false;
    }
    AdminViewLayout :: showLayoutsModalPlg($option, $rows);
  }

  static function showModalCckButton($option){
    global $db;
    $layout = new os_cckLayout($db);
    $layout->lid = JRequest::getVar('lid','');
    if($layout->lid){
      $layout->load($layout->lid);
    }
    $eiid = JRequest::getVar('eiid','');
    $cat_id = JRequest::getVar('cat_id','');

    AdminViewLayout :: showModalCckButton($option, $layout, $eiid, $cat_id);
  }

  static function newLayout($option){
    global $db, $user, $os_cck_configuration ,$doc,$app;
    $layout = new os_cckLayout($db);
    $entity = new os_cckEntity($db);
    //check plugin
    $query = "SELECT extension_id FROM #__extensions WHERE element='cck_system' AND enabled='1' ";
    $db->setQuery($query);
    $pluginId = $db->loadResult();
    if(empty($pluginId)){
      $app->redirect("index.php?option=com_os_cck&task=manage_layout", JText::_("COM_OS_CCK_PUBLISH_PLUGIN"));
    }

    $entities = array();
    $query = 'SELECT eid AS value, name  AS text FROM #__os_cck_entity WHERE published="1" ORDER BY name ';
    $db->setQuery($query);
    $ent = $db->loadObjectList();
    if (count($ent) > 0) $entities = array_merge($entities, (array)$ent);
    $selected ='';
    if(isset($_POST['entity_id']) && !empty($_POST['entity_id']))
      $selected = $_POST['entity_id'];
    $style = '';
    if(count($ent) == 1){$style = 'style="display:none;"';}
    $entities_list = JHTML::_('select.genericlist',$entities, 'entity_id',
                                           'size="1" '.$style.' class="inputbox"', 'value', 'text', $selected);
    $views = array();
    $views[] = array('value' => '', 'text' => 'Select type');
    $views[] = array('value' => 'all_instance', 'text' => 'Show all Instances');
    $views[] = array('value' => 'calendar', 'text' => 'Show calendar');
    $views[] = array('value' => 'instance', 'text' => 'Show instance');
    $views[] = array('value' => 'all_categories', 'text' => 'Show all categories');
    $views[] = array('value' => 'category', 'text' => 'Show category');
    $views[] = array('value' => 'show_review_instance', 'text' => 'Show review');
    $views[] = array('value' => 'search', 'text' => 'Show Search');
    $views[] = array('value' => 'add_instance', 'text' => 'Add instance');
    $views[] = array('value' => 'request_instance', 'text' => 'Add request form');
    $views[] = array('value' => 'buy_request_instance', 'text' => 'Add buy request form');
    $views[] = array('value' => 'rent_request_instance', 'text' => 'Add rent request form');
    $views[] = array('value' => 'review_instance', 'text' => 'Add review form');
    $view_type_list = JHTML::_('select.genericlist',$views, 'layout_type',
                                            'size="1" class="inputbox" onchange="layout_type_select(this);"', 'value', 'text', '');
    $str_list = array();
    if(count($ent) == 1)$str_list['entities_list_hidden'] = true;
    else$str_list['entities_list_hidden'] = false;
    $str_list['entities_list'] = $entities_list;
    $str_list['view_type_list'] = $view_type_list;

    AdminViewLayout :: newLayout($option, $layout, $str_list);
  }

  static function addNewLayout($option){
    global $db;
    $input  = JFactory::getApplication()->input;
    $layout_type = $input->get('layout_type','',"STRING");
    $entity_id = $input->get('entity_id',0,'INT');
    if($layout_type && $entity_id){
      $layout = new os_cckLayout($db);
      $layout->fk_eid = $entity_id;
      $layout->type = $layout_type;

      // $layout->title = $layout_type;

      $layout->store();
      $lid = $layout->lid;

      // print_r($lid);
      // exit;
      $query = "INSERT INTO #__os_cck_layout_html(fk_lid,layout_html,bootstrap)".
                "\n VALUE(".$lid.",'','3')";
      $db->setQuery($query);
      $db->query();

      $query = "INSERT INTO #__os_cck_layout_html(fk_lid,layout_html,bootstrap)".
                "\n VALUE(".$lid.",'','2')";
      $db->setQuery($query);
      $db->query();

      JFactory::getApplication()->redirect(JURI::root()."administrator/index.php?option=com_os_cck&task=edit_layout&lid[]=".$lid);
    }else{
      JFactory::getApplication()->enqueueMessage("Can`t create layout.Please contact with Ordasoft support team.", 'error');
    }
  }


  static function copyLayout($option, $lid, $customTask = false){

    global $db, $app;

    if (!is_array($lid) || count($lid) != 1) {
      echo "<script> alert('Select only one item to copy'); window.history.go(-1);</script>\n";
      exit;
    }

    $lid = (int) $lid[0];

    //for #__os_cck_layout && #__os_cck_layout_html tables
    $query = "DROP TABLE IF EXISTS #__os_cck_layout_temp;
              CREATE TABLE #__os_cck_layout_temp AS SELECT * FROM #__os_cck_layout WHERE lid = '$lid';
              ALTER TABLE #__os_cck_layout_temp MODIFY lid int(11) DEFAULT NULL;
              UPDATE #__os_cck_layout_temp SET lid = NULL;
              INSERT INTO #__os_cck_layout SELECT * FROM #__os_cck_layout_temp;
              DROP TABLE IF EXISTS #__os_cck_layout_temp;
              CREATE TABLE #__os_cck_layout_temp AS SELECT * FROM #__os_cck_layout_html WHERE fk_lid = '$lid';
              ALTER TABLE #__os_cck_layout_temp MODIFY id int(11) DEFAULT NULL;
              UPDATE #__os_cck_layout_temp SET id = NULL;
              UPDATE #__os_cck_layout_temp SET fk_lid = (SELECT MAX(lid) FROM #__os_cck_layout);
              INSERT INTO #__os_cck_layout_html SELECT * FROM #__os_cck_layout_temp;
              DROP TABLE IF EXISTS #__os_cck_layout_temp;";

    $db = JFactory::getDbo();
    $queries = $db->splitSql($query);

    foreach($queries AS $sql) {
        $db->setQuery($sql);
        $db->execute();
    }

    $app->redirect("index.php?option={$option}&task=manage_layout");

  }


  static function editLayout($option, $lid, $customTask = false){
    global $db, $user, $os_cck_configuration ,$doc;
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
    $doc->addScript(JURI::root()."components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js");
    $document = JFactory::getDocument();
    $key = 'key='.$os_cck_configuration->get("google_map_key",'');
    $document->addScript('//maps.google.com/maps/api/js?'.$key, true, true);

    $layout = new os_cckLayout($db);
    $layout->load(intval($lid));
    

    $layout->checkout($user->id);

    $query = "SELECT layout_html FROM #__os_cck_layout_html WHERE fk_lid=$layout->lid AND bootstrap = '3'";
    $db->setQuery($query);
    $result= $db->loadResult();
    $layout->html = urldecode($result);

    if ($layout->type != '' && $layout->fk_eid != '') {
      $entity_id = $layout->fk_eid;
      $layout_type = $layout->type;
      $query = "SELECT * FROM #__os_cck_entity_field WHERE published='1' AND fk_eid='" . $entity_id . "' ORDER BY fid ";
      $db->setQuery($query);
      $extra_fields_list = $db->loadObjectList();
      if (count($extra_fields_list) < 1) {
        $extra_fields_list = array();
      }
      $query = "SELECT title, id ,module as type FROM #__modules as m "
              ."\n WHERE m.published = '1'";
      $db->setQuery($query);
      $layout->module_list = $db->loadObjectList('id');
      //get fields for mail modal
      $query = "SELECT db_field_name as title, fid FROM #__os_cck_entity_field as f "
              ."\n WHERE f.published = '1' AND f.field_type != 'captcha_field' AND f.field_type != 'galleryfield'"
              ."\n AND f.field_type != 'videofield' AND f.field_type != 'audiofield'"
              ."\n AND f.field_type != 'rating_field' AND f.field_type != 'locationfield' AND fk_eid = ".$entity_id;
      $db->setQuery($query);
      $field_mask_list = $db->loadObjectList('fid');
      if(!empty($field_mask_list)){
        foreach($field_mask_list as $mail_field){
          $mail_field->mask = '{|'.$mail_field->title.'|}';
        }
        $layout->field_mask_list = $field_mask_list;
      }

      //get fields for custom code modal
      $query = "SELECT db_field_name as title, fid FROM #__os_cck_entity_field as f "
              ."\n WHERE f.published = '1' AND fk_eid = ".$entity_id;
      $db->setQuery($query);
      $field_mask_list = $db->loadObjectList('fid');
      if(!empty($field_mask_list)){
        foreach($field_mask_list as $custom_field){
          $custom_field->mask = '{|'.$custom_field->title.'|}';
        }
        $layout->custom_field_mask_list = $field_mask_list;
      }

      $gtree = get_group_children_tree_cck();
      $layout->gtree = $gtree;
      switch ($layout_type){
        case "instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          // $show_reviews = (isset($params['views']['show_reviews'])) ? $params['views']['show_reviews'] : '1';
          // $show_navigation = (isset($params['views']['show_navigation'])) ? $params['views']['show_navigation'] : '0';
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          //get items layouts
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE c.type ='add_instance' "
                  ."\n OR c.type = 'request_instance' OR c.type = 'buy_request_instance' "
                  ."\n OR c.type = 'rent_request_instance' OR c.type = 'review_instance' "
                  ."\n OR c.type = 'show_review_instance'"
                  ."\n AND c.published = '1'";
          $db->setQuery($query);
          $layout->layout_list = $db->loadObjectList('lid');
          $gtree = get_group_children_tree_cck();
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          // $layout->show_navigation = JHTML::_('select.genericlist',$test, 'vi_show_navigation',
          //                                               'size="1" class="inputbox" ', 'value', 'text', $show_navigation);
          // $layout->show_reviews =  JHTML::_('select.genericlist',$gtree, 'vi_show_reviews[]',
          //     'size="" multiple="multiple" class="inputbox" ','value', 'text', $show_reviews);

          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          if($customTask){
            AdminViewLayout::updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout::editLayout($option, $layout, $entity);
          return;
        break;

        case "all_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $idxFields = $entity->getIndexedFieldList();
          $display = AdminLayout::getTmpls('/components/com_os_cck/views/category/tmpl');
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $tmpl = (isset($params['views']['tmpl'])) ? $params['views']['tmpl'] : '';
          $selected_orderFields = (isset($params['views']['order_by_fields'])) ? $params['views']['order_by_fields'] : '';
          $selected_link = (isset($params['views']['link_field'])) ? $params['views']['link_field'] : '';
          $show_map = (isset($params['views']['show_map'])) ? $params['views']['show_map'] : '';
          $cat_hits = (isset($params['views']['cat_hits'])) ? $params['views']['cat_hits'] : '';
          $index = (isset($params['views']['indexed'])) ? $params['views']['indexed'] : '';
          $sortby = (isset($params['views']['sortType'])) ? $params['views']['sortType'] : '';
          $layout->limit = (isset($params['views']['limit'])) ? $params['views']['limit'] : '0';
          $instanceLayoutSelected = (isset($params['views']['instance_layout'])) ? $params['views']['instance_layout'] : '-1';
          $tmpl_list = JHTML::_('select.genericlist',$display, 'vi_tmpl', 'size="1" class="inputbox" ', 'value', 'text', $tmpl);
          $layout->tmpl_list = $tmpl_list;
          $indexedOptions = array();
          $field_for_order = array();
          $indexedOptions[] = JHTML::_('select.option','ceid','Id');
          $field_for_order[] = JHTML::_('select.option','ceid','Id');
          foreach($idxFields as $idx) {
            $indexedOptions[] = JHTML::_('select.option',$idx['value'],$idx['text']);
            $field_for_order[]  = JHTML::_('select.option',$idx['value'],$idx['text']);
          }
          if(count($field_for_order) <= 1){
            $layout->orderByFields = 'Need at least 2 sortable fields';
          }else{
            $layout->orderByFields = JHTML::_('select.genericlist',$field_for_order, 'vi_order_by_fields[]',
                      'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_orderFields);
          }

          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          $test = array();
          $test[]  = JHTML::_('select.option','1','1');
          $test[]  = JHTML::_('select.option','2','2');
          $test[]  = JHTML::_('select.option','3','3');
          $test[]  = JHTML::_('select.option','4','4');
          $test[]  = JHTML::_('select.option','5','5');


          $count_inst_columns = (isset($params['views']['count_inst_columns'])) ? $params['views']['count_inst_columns'] : '4';
          $layout->count_inst_columns = JHTML::_('select.genericlist',$test, 'vi_count_inst_columns',
                                                        'size="1" class="inputbox" ', 'value', 'text', $count_inst_columns);

          //resolutions

          $resolition_one = (isset($params['views']['resolition_one'])) ? $params['views']['resolition_one'] : '5';
          $layout->resolition_one = JHTML::_('select.genericlist',$test, 'vi_resolition_one',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_one);

          $resolition_two = (isset($params['views']['resolition_two'])) ? $params['views']['resolition_two'] : '4';
          $layout->resolition_two = JHTML::_('select.genericlist',$test, 'vi_resolition_two',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_two);

          $resolition_three = (isset($params['views']['resolition_three'])) ? $params['views']['resolition_three'] : '3';
          $layout->resolition_three = JHTML::_('select.genericlist',$test, 'vi_resolition_three',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_three);

          $resolition_four = (isset($params['views']['resolition_four'])) ? $params['views']['resolition_four'] : '2';
          $layout->resolition_four = JHTML::_('select.genericlist',$test, 'vi_resolition_four',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_four);

          //resolutions
          
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          $instance_grid = (isset($params['views']['instance_grid'])) ? $params['views']['instance_grid'] : '0';
          $layout->instance_grid = JHTML::_('select.genericlist',$test, 'vi_instance_grid',
                                                        'size="1" class="inputbox" ', 'value', 'text', $instance_grid);

          $test = array();
          $test[]  = JHTML::_('select.option','auto','auto');
          $test[]  = JHTML::_('select.option','custom','custom');

          $auto_custom = (isset($params['views']['auto_custom'])) ? $params['views']['auto_custom'] : '0';
          $layout->auto_custom = JHTML::_('select.genericlist',$test, 'vi_auto_custom',
                                                        'size="1" class="inputbox" ', 'value', 'text', $auto_custom);

          $space_between = (isset($params['views']['space_between'])) ? $params['views']['space_between'] : '0';
          $layout->space_between = "<input type='number' min='0' name='vi_space_between' value='".$space_between."'>";

          $lay_min_width = (isset($params['views']['lay_min_width'])) ? $params['views']['lay_min_width'] : '200';
          $layout->lay_min_width = "<input type='number' min='0' name='vi_lay_min_width' value='".$lay_min_width."'>";



          //get items layouts
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE (c.type = 'search' OR c.type='instance')"
                  ."\n AND c.published = '1' AND c.fk_eid = $entity_id";
          $db->setQuery($query);
          $layout->layout_list = $db->loadObjectList('lid');
          $instance_layout_list  = array();
          $instanceLayout = array();
          $instanceLayout[] = JHTML::_('select.option','-1','default');
          foreach ($layout->layout_list as $value) {
            if($value->type == 'instance'){
              $instance_layout_list[$value->lid] = $value->lid;
              $instanceLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
            }
          }
          $layout->instanceLayout = JHTML::_('select.genericlist',$instanceLayout, 'vi_instance_layout',
                                        'size="1" class="inputbox" ', 'value', 'text',$instanceLayoutSelected);
          $layout->instance_layout_list =  $instance_layout_list;
          //end
          $sortType = array(JHTML::_('select.option','ASC','ASC'), JHTML::_('select.option','DESC','DESC'));
          $layout->sortType = JHTML::_('select.genericlist',$sortType, 'vi_sortType',
                                                  'size="1" class="inputbox" ', 'value', 'text', $sortby);
          $layout->indexed = JHTML::_('select.genericlist',$indexedOptions, 'vi_indexed',
                                                 'size="1" class="inputbox" ', 'value', 'text', $index);
          $layout->indexed_options = $indexedOptions;
          $gtree = get_group_children_tree_cck();
          $type_show = array();
          $type_show[]  = JHTML::_('select.option','1','Form');
          $type_show[]  = JHTML::_('select.option','2','Button with dropdown form');
          $type_show[]  = JHTML::_('select.option','3','Button with redirect');
          $layout->show_type = $type_show;
          $layout->group_access = $gtree;
          
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          
          $featured = (isset($params['views']['featured'])) ? $params['views']['featured'] : 0;
          $layout->featured = JHTML::_('select.genericlist',$test, 'vi_featured',
                                                    'size="1" class="inputbox" ', 'value', 'text', $featured);

          $layout->show_map = JHTML::_('select.genericlist',$test, 'vi_show_map',
                                                    'size="1" class="inputbox" ', 'value', 'text', $show_map);
          $layout->cat_hits = JHTML::_('select.genericlist',$test, 'vi_cat_hits',
                                                        'size="1" class="inputbox" ', 'value', 'text', $cat_hits);
          $field_for_link = array();
          $fields = $entity->getFieldList();

          foreach ($fields as $value) {
            if($value->field_type == 'text_textfield' || $value->field_type == 'datetime_popup'
              || $value->field_type == 'decimal_textfield' || $value->field_type == 'imagefield'
              || $value->field_type == 'text_radio_buttons' || $value->field_type == 'text_select_list'
              || $value->field_type == 'text_single_checkbox_onoff' || $value->field_type == 'text_textarea')
            {
              $field_for_link[]  = JHTML::_('select.option',$value->fid,$value->field_name);
            }
          }

          $layout->link_field = JHTML::_('select.genericlist',$field_for_link, 'vi_link_field[]',
                            'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_link);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;


        case "calendar":

          $entity = new os_cckEntity($db);
          $entity->load($entity_id);

          //get_params
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          //get title fields list
          $eventTitleFields = $entity->getEventTitleFieldList();
          //get date fields list
          $eventDateFields = $entity->getDatePopupFieldList();
          // //title field
          // $event_title = (isset($params['views']['event_title'])) ? $params['views']['event_title'] : '-1';
          //sort by field
          $event_sort_by = (isset($params['views']['event_sort_by'])) ? $params['views']['event_sort_by'] : '-1';
          //date field
          $event_date = (isset($params['views']['event_date'])) ? $params['views']['event_date'] : '-1';
          // $event_date_from = (isset($params['views']['event_date_from'])) ? $params['views']['event_date_from'] : '-1';
          // $event_date_to = (isset($params['views']['event_date_to'])) ? $params['views']['event_date_to'] : '-1';
          //asc-desc
          $order_by = (isset($params['views']['sortType'])) ? $params['views']['sortType'] : '';
          //instance_layout
          $instanceLayoutSelected = (isset($params['views']['instance_layout'])) ? $params['views']['instance_layout'] : '-1';
          //all_instance_layout
          $all_instanceLayoutSelected = (isset($params['views']['all_instance_layout'])) ? $params['views']['all_instance_layout'] : '-1';

          //calendar with mouth select
          $monthCalendar = (isset($params['views']['month_calendar'])) ? $params['views']['month_calendar'] : '';
          $layout->month_calendar = "<input type='month' min='1967-01' max='2067-12' name='vi_month_calendar' value='".$monthCalendar."'>";

          // //event title field
          // $event_title_options = array();
          // $event_title_options[] = JHTML::_('select.option','-1','default');

          // foreach($eventTitleFields as $title) {
          //   $event_title_options[] = JHTML::_('select.option',$title['value'],$title['text']);
          // }

          //event sort by field
          $event_sort_by_options = array();
          $event_sort_by_options[] = JHTML::_('select.option','-1','time');

          foreach($eventTitleFields as $title) {
            $event_sort_by_options[] = JHTML::_('select.option',$title['value'],$title['text']);
          }

          //event date field, field date for query 
          $eventDateOptions = array();
          $eventDateOptions[] = JHTML::_('select.option','-1','default');
          foreach($eventDateFields as $eventDate) {
            $eventDateOptions[] = JHTML::_('select.option',$eventDate['value'],$eventDate['text']);
          }

          //layout title and show/hide it
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          //show past events
          $test = array();
          $test[]  = JHTML::_('select.option','1','Yes');
          $test[]  = JHTML::_('select.option','0','No');
          $show_past_events = (isset($params['views']['show_past_events'])) ? $params['views']['show_past_events'] : '1';
          $layout->show_past_events = JHTML::_('select.genericlist',$test, 'vi_show_past_events',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_past_events);

          //attached layouts
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE c.type ='add_instance' "
                  ."\n OR c.type = 'request_instance'"
                  ."\n AND c.published = '1'";
          $db->setQuery($query);
          $layout->layout_list = $db->loadObjectList('lid');

          //get layouts INSTANCE
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE c.type='instance' "
                  ."\n AND c.published = '1' AND c.fk_eid = $entity_id";
          $db->setQuery($query);
          $layout->instance_layout_list = $db->loadObjectList('lid');
          $instance_layout_list  = array();
          $instanceLayout = array();
          $instanceLayout[] = JHTML::_('select.option','-1','default');
          foreach ($layout->instance_layout_list as $value) {
            if($value->type == 'instance'){
              $instance_layout_list[$value->lid] = $value->lid;
              $instanceLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
            }
          }
          $layout->instanceLayout = JHTML::_('select.genericlist',$instanceLayout, 'vi_instance_layout',
                                        'size="1" class="inputbox" ', 'value', 'text',$instanceLayoutSelected);
          $layout->instance_layout_list =  $instance_layout_list;



          //get layouts ALL INSTANCE
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE c.type='all_instance' "
                  ."\n AND c.published = '1' AND c.fk_eid = $entity_id";
          $db->setQuery($query);
          $layout->all_instance_layout_list = $db->loadObjectList('lid');
          $all_instance_layout_list  = array();
          $all_instanceLayout = array();
          $all_instanceLayout[] = JHTML::_('select.option','-1','default');
          foreach ($layout->all_instance_layout_list as $value) {
            if($value->type == 'all_instance'){
              $all_instance_layout_list[$value->lid] = $value->lid;
              $all_instanceLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
            }
          }
          $layout->all_instanceLayout = JHTML::_('select.genericlist',$all_instanceLayout, 'vi_all_instance_layout',
                                        'size="1" class="inputbox" ', 'value', 'text',$all_instanceLayoutSelected);
          $layout->all_instance_layout_list =  $all_instance_layout_list;

          //sort by
          $sortType = array(JHTML::_('select.option','ASC','ASC'), JHTML::_('select.option','DESC','DESC'));

          $layout->sortType = JHTML::_('select.genericlist',$sortType, 'vi_sortType',
                                                  'size="1" class="inputbox" ', 'value', 'text', $order_by);

          //title
          // $layout->event_title = JHTML::_('select.genericlist',$event_title_options, 'vi_event_title',
          //                                        'size="1" class="inputbox" ', 'value', 'text', $event_title);

           //sort by
          $layout->event_sort_by = JHTML::_('select.genericlist',$event_sort_by_options, 'vi_event_sort_by',
                                                 'size="1" class="inputbox" ', 'value', 'text', $event_sort_by);


          $layout->event_date = JHTML::_('select.genericlist',$eventDateOptions, 'vi_event_date',
                                                 'size="1" class="inputbox" ', 'value', 'text', $event_date);

          // //date from
          // $layout->event_date_from = JHTML::_('select.genericlist',$eventDateOptions, 'vi_event_date_from',
          //                                        'size="1" class="inputbox" ', 'value', 'text', $event_date_from);

          // //date to
          // $layout->event_date_to = JHTML::_('select.genericlist',$eventDateOptions, 'vi_event_date_to',
          //                                        'size="1" class="inputbox" ', 'value', 'text', $event_date_to);


          // $gtree = get_group_children_tree_cck();
          // $type_show = array();
          // $type_show[]  = JHTML::_('select.option','1','Form');
          // $type_show[]  = JHTML::_('select.option','2','Button with dropdown form');
          // $type_show[]  = JHTML::_('select.option','3','Button with redirect');
          // $layout->show_type = $type_show;
          // $layout->group_access = $gtree;

          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "category":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $idxFields = $entity->getIndexedFieldList();
          $display = AdminLayout::getTmpls('/components/com_os_cck/views/category/tmpl');
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $tmpl = (isset($params['views']['tmpl'])) ? $params['views']['tmpl'] : '';
          $sub_category_level = (isset($params['views']['sub_category_level'])) ? $params['views']['sub_category_level'] : '';
          $selected_link = (isset($params['views']['link_field'])) ? $params['views']['link_field'] : '';
          $selected_orderFields = (isset($params['views']['order_by_fields'])) ? $params['views']['order_by_fields'] : '';
          $cat_hits = (isset($params['views']['cat_hits'])) ? $params['views']['cat_hits'] : '';
          $cat_image = (isset($params['views']['cat_image'])) ? $params['views']['cat_image'] : '';
          $cat_desc = (isset($params['views']['cat_desc'])) ? $params['views']['cat_desc'] : '';
          $index = (isset($params['views']['indexed'])) ? $params['views']['indexed'] : '';
          $sortby = (isset($params['views']['sortType'])) ? $params['views']['sortType'] : '';
          $layout->limit = (isset($params['views']['limit'])) ? $params['views']['limit'] : '0';
          $instanceLayoutSelected = (isset($params['views']['instance_layout'])) ? $params['views']['instance_layout'] : '';
          $tmpl_list = JHTML::_('select.genericlist',$display, 'vi_tmpl', 'size="1" class="inputbox" ', 'value', 'text', $tmpl);
          $layout->tmpl_list = $tmpl_list;
          $indexedOptions = array();
          $field_for_order = array();
          $indexedOptions[] = JHTML::_('select.option','ceid','Id');
          $field_for_order[] = JHTML::_('select.option','ceid','Id');
          foreach($idxFields as $idx) {
            $indexedOptions[] = JHTML::_('select.option',$idx['value'],$idx['text']);
            $field_for_order[]  = JHTML::_('select.option',$idx['value'],$idx['text']);
          }
          if(count($field_for_order) <= 1){
            $layout->orderByFields = 'Need at least 2 sortable fields';
          }else{
            $layout->orderByFields = JHTML::_('select.genericlist',$field_for_order, 'vi_order_by_fields[]',
                      'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_orderFields);
          }



          $test = array();
          $test[]  = JHTML::_('select.option','1','1');
          $test[]  = JHTML::_('select.option','2','2');
          $test[]  = JHTML::_('select.option','3','3');
          $test[]  = JHTML::_('select.option','4','4');
          $test[]  = JHTML::_('select.option','5','5');

          $count_inst_columns = (isset($params['views']['count_inst_columns'])) ? $params['views']['count_inst_columns'] : '4';
          $layout->count_inst_columns = JHTML::_('select.genericlist',$test, 'vi_count_inst_columns',
                                                        'size="1" class="inputbox" ', 'value', 'text', $count_inst_columns);
          //resolutions

          $resolition_one = (isset($params['views']['resolition_one'])) ? $params['views']['resolition_one'] : '5';
          $layout->resolition_one = JHTML::_('select.genericlist',$test, 'vi_resolition_one',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_one);

          $resolition_two = (isset($params['views']['resolition_two'])) ? $params['views']['resolition_two'] : '4';
          $layout->resolition_two = JHTML::_('select.genericlist',$test, 'vi_resolition_two',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_two);

          $resolition_three = (isset($params['views']['resolition_three'])) ? $params['views']['resolition_three'] : '3';
          $layout->resolition_three = JHTML::_('select.genericlist',$test, 'vi_resolition_three',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_three);

          $resolition_four = (isset($params['views']['resolition_four'])) ? $params['views']['resolition_four'] : '2';
          $layout->resolition_four = JHTML::_('select.genericlist',$test, 'vi_resolition_four',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_four);
          //resolutions
          
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          $instance_grid = (isset($params['views']['instance_grid'])) ? $params['views']['instance_grid'] : '0';
          $layout->instance_grid = JHTML::_('select.genericlist',$test, 'vi_instance_grid',
                                                        'size="1" class="inputbox" ', 'value', 'text', $instance_grid);

          $test = array();
          $test[]  = JHTML::_('select.option','auto','auto');
          $test[]  = JHTML::_('select.option','custom','custom');

          $auto_custom = (isset($params['views']['auto_custom'])) ? $params['views']['auto_custom'] : '0';
          $layout->auto_custom = JHTML::_('select.genericlist',$test, 'vi_auto_custom',
                                                        'size="1" class="inputbox" ', 'value', 'text', $auto_custom);

          $space_between = (isset($params['views']['space_between'])) ? $params['views']['space_between'] : '0';
          $layout->space_between = "<input type='number' min='0' name='vi_space_between' value='".$space_between."'>";

          $lay_min_width = (isset($params['views']['lay_min_width'])) ? $params['views']['lay_min_width'] : '200';
          $layout->lay_min_width = "<input type='number' min='0' name='vi_lay_min_width' value='".$lay_min_width."'>";




          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          //get items layouts
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE (((c.type = 'search' OR c.type='instance' "
                  ."\n OR c.type='add_instance') AND c.fk_eid = $entity_id) OR c.type='request_instance')"
                  ."\n AND c.published = '1' ";
          $db->setQuery($query);
          $layout->layout_list = $db->loadObjectList('lid');
          $instance_layout_list  = array();
          $instanceLayout = array();
          $instanceLayout[] = JHTML::_('select.option','-1','default');
          foreach ($layout->layout_list as $value) {
            if($value->type == 'instance'){
              $instance_layout_list[$value->lid] = $value->lid;
              $instanceLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
            }
          }
          $layout->instanceLayout = JHTML::_('select.genericlist',$instanceLayout, 'vi_instance_layout',
                                        'size="1" class="inputbox" ', 'value', 'text',$instanceLayoutSelected);
          $layout->instance_layout_list =  $instance_layout_list;
          $gtree = get_group_children_tree_cck();
          $layout->group_access = $gtree;
          $layout->fields_list = $extra_fields_list;
          $type_show = array();
          $type_show[]  = JHTML::_('select.option','1','Form');
          $type_show[]  = JHTML::_('select.option','2','Button with dropdown form');
          $type_show[]  = JHTML::_('select.option','3','Button with redirect');
          $layout->show_type = $type_show;
          $sortType = array(JHTML::_('select.option','ASC','ASC'), JHTML::_('select.option','DESC','DESC'));
          $layout->sortType = JHTML::_('select.genericlist',$sortType, 'vi_sortType',
                                                  'size="1" class="inputbox" ', 'value', 'text', $sortby);

          $layout->indexed = JHTML::_('select.genericlist',$indexedOptions, 'vi_indexed',
                                                 'size="1" class="inputbox" ', 'value', 'text', $index);
          $layout->indexed_options = $indexedOptions;


          $layout->indexed = JHTML::_('select.genericlist',$indexedOptions, 'vi_indexed',
                                                 'size="1" class="inputbox" ', 'value', 'text', $index);
          $layout->indexed_options = $indexedOptions;

          $query = "SELECT * FROM #__os_cck_categories as c WHERE c.published = '1'";
          $db->setQuery($query);
          $cat_all = $db->loadObjectList();
          $max_depp = getDeepLevel(0,$cat_all);
          $levels = array();
          for($i=0;$i<=$max_depp;$i++){
            if($i == 0){
              $levels[]  = JHTML::_('select.option',$i,'Off');
            }else if($i == $max_depp){
              $levels[]  = JHTML::_('select.option',$i,'Max');
            }else{
              $levels[]  = JHTML::_('select.option',$i,$i);
            }
          }
          $layout->sub_category_level = JHTML::_('select.genericlist',$levels, 'vi_sub_category_level',
                                                    'size="1" class="inputbox" ', 'value', 'text', $sub_category_level);
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          
          $featured = (isset($params['views']['featured'])) ? $params['views']['featured'] : 0;
          $layout->featured = JHTML::_('select.genericlist',$test, 'vi_featured',
                                                    'size="1" class="inputbox" ', 'value', 'text', $featured);

          $layout->cat_hits = JHTML::_('select.genericlist',$test, 'vi_cat_hits',
                                                        'size="1" class="inputbox" ', 'value', 'text', $cat_hits);
          $layout->cat_image = JHTML::_('select.genericlist',$test, 'vi_cat_image',
                                                        'size="1" class="inputbox" ', 'value', 'text', $cat_image);
          $layout->cat_desc = JHTML::_('select.genericlist',$test, 'vi_cat_desc',
                                                        'size="1" class="inputbox" ', 'value', 'text', $cat_desc);


          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');

          $fields = $entity->getFieldList();
          $field_for_link = array();
          foreach ($fields as $value) {
            if($value->field_type == 'text_textfield' || $value->field_type == 'datetime_popup'
              || $value->field_type == 'decimal_textfield' || $value->field_type == 'imagefield'
              || $value->field_type == 'text_radio_buttons' || $value->field_type == 'text_select_list'
              || $value->field_type == 'text_single_checkbox_onoff' || $value->field_type == 'text_textarea')
            {
              $field_for_link[]  = JHTML::_('select.option',$value->fid,$value->field_name);
            }
          }
          $layout->link_field = JHTML::_('select.genericlist',$field_for_link, 'vi_link_field[]',
                                                        'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_link);

          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "add_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $send_email_admin = (isset($params['views']['send_email_admin'])) ? $params['views']['send_email_admin'] : '';
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          $layout->send_email_admin = JHTML::_('select.genericlist',$test, 'vi_send_email_admin',
                                                    'size="1" class="inputbox" ', 'value', 'text', $send_email_admin);


          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
        break;

        case "request_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
        break;

        case "review_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "show_review_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $no_review_message = (isset($params['views']['no_review_message'])) ? $params['views']['no_review_message'] : '1';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Yes');
          $test[]  = JHTML::_('select.option','0','No');
          $layout->no_review_message = JHTML::_('select.genericlist',$test, 'vi_no_review_message',
                                                        'size="1" class="inputbox" ', 'value', 'text', $no_review_message);

          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "buy_request_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "rent_request_instance":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          
          $price_per_period = (isset($params['views']['price_per_period'])) ? $params['views']['price_per_period'] : '60';


          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';


          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";

          if($os_cck_configuration ->get('by_time') != 1){

            $layout->price_per_period = "<input type='text' disabled='' title='Available when \"Date fields with time\" is \"no\"'' value='24 hours'>";
          }else{
            $layout->price_per_period = "<input type='text' name='vi_price_per_period' value='".$price_per_period."'>";
          }


          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          //print_r($params);
          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "search":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $layoutSelected = (isset($params['views']['all_instance_layout'])) ? $params['views']['all_instance_layout'] : '-1';
          if(isset($params['views']['indexed'])){
            $index = (isset($params['views']['indexed'])) ? $params['views']['indexed'] : '';
          }
          $selectedField = (isset($params['views']['fieldList']) && $params != '') ? $params['views']['fieldList'] : '';
          $fieldList = array(JHTML::_('select.option','title','title'));
          foreach($entity->getFieldList() as $field) {
            $fieldList[] = JHTML::_('select.option',$field->field_name,$field->field_name);
          }

          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          //get category layouts
          $query = "SELECT c.title,c.lid FROM #__os_cck_layout as c WHERE c.fk_eid = $entity_id"
                  . "\n AND c.published = '1'"
                  . "\n AND c.type='all_instance'";
          $db->setQuery($query);
          $total = $db->loadObjectList();
          $all_instanceLayout = array();
          if(count($total) > 0){
            $all_instanceLayout[] = JHTML::_('select.option','-1','default');
            foreach($total as $value){
              $all_instanceLayout[]= JHTML::_('select.option',"$value->lid","$value->title");
            }
          }else{
            $all_instanceLayout[] = JHTML::_('select.option','0','No layout');
          }//end
          $layout->all_instanceLayout = JHTML::_('select.genericlist',$all_instanceLayout,'vi_all_instance_layout',
                                                        'size="1" class="inputbox" ', 'value', 'text',$layoutSelected);
          $layout->fields_list = $extra_fields_list;
          /////////////////////////////////params for search////////////
          //search title
          $selectedFieldTitle = (isset($params['views']['search_title'])) ? $params['views']['search_title'] : '';
          $showSearchOptions = array();
          $showSearchOptions[] = JHTML::_('select.option','1','Show');
          $showSearchOptions[] = JHTML::_('select.option','2','Search');
          $showSearchOptions[] = JHTML::_('select.option','0','Hide');
          $layout->showSearchTitle = JHTML::_('select.genericlist',$showSearchOptions, 'vi_search_title',
                                              'size="1" class="inputbox" ', 'value', 'text',$selectedFieldTitle);

          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;

        case "all_categories":
          $entity = new os_cckEntity($db);
          $entity->load($entity_id);
          $display = AdminLayout::getTmpls('/components/com_os_cck/views/all_categories/tmpl');
          $params = ($layout->params != '') ? unserialize($layout->params) : '';
          $tmpl = (isset($params['views']['tmpl'])) ? $params['views']['tmpl'] : '';
          $categoryLayoutSelected = (isset($params['views']['category_layout'])) ? $params['views']['category_layout'] : '';
          $selected_link = (isset($params['views']['link_field'])) ? $params['views']['link_field'] : '';
          $layoutSelected = (isset($params['views']['category_layout'])) ? $params['views']['category_layout'] : '';
          $gtree = get_group_children_tree_cck();
          $layout->group_access = $gtree;
          $type_show = array();
          $type_show[]  = JHTML::_('select.option','1','Form');
          $type_show[]  = JHTML::_('select.option','2','Button with dropdown form');
          $type_show[]  = JHTML::_('select.option','3','Button with redirect');
          $layout->show_type = $type_show;
          $query = "SELECT c.title, c.lid ,c.params,c.type FROM #__os_cck_layout as c "
                  ."\n WHERE (((c.type = 'search' OR c.type='category' "
                  ."\n OR c.type='add_instance') AND c.fk_eid = $entity_id) OR c.type='request_instance')"
                  ."\n AND c.published = '1' ";
          $db->setQuery($query);
          $layout->layout_list = $db->loadObjectList('lid');
          $category_layout_list = array();
          $categoryLayout = array();
          $categoryLayout[] = JHTML::_('select.option','-1','default');
          foreach($layout->layout_list as $value){
            if($value->type == 'category'){
              $category_layout_list[$value->lid] = $value->lid;
              $categoryLayout[] =  JHTML::_('select.option',$value->lid,$value->title);
            }
          }
          $layout->categoryLayout = JHTML::_('select.genericlist',$categoryLayout, 'vi_category_layout',
                                        'size="1" class="inputbox" ', 'value', 'text',$categoryLayoutSelected);
          $layout->category_layout_list =  $category_layout_list;
                    $query = "SELECT * FROM #__os_cck_categories as c WHERE c.published = '1'";
          $db->setQuery($query);
          $cat_all = $db->loadObjectList();
          $max_depp = getDeepLevel(0,$cat_all);
          $levels = array();
          for($i=0;$i<=$max_depp;$i++){
            if($i == 0){
              $levels[]  = JHTML::_('select.option',$i,'Off');
            }else if($i == $max_depp){
              $levels[]  = JHTML::_('select.option',$i,'Max');
            }else{
              $levels[]  = JHTML::_('select.option',$i,$i);
            }
          }

           $test = array();
          $test[]  = JHTML::_('select.option','1','1');
          $test[]  = JHTML::_('select.option','2','2');
          $test[]  = JHTML::_('select.option','3','3');
          $test[]  = JHTML::_('select.option','4','4');
          $test[]  = JHTML::_('select.option','5','5');

          $count_inst_columns = (isset($params['views']['count_inst_columns'])) ? $params['views']['count_inst_columns'] : '4';
          $layout->count_inst_columns = JHTML::_('select.genericlist',$test, 'vi_count_inst_columns',
                                                        'size="1" class="inputbox" ', 'value', 'text', $count_inst_columns);
          //resolutions

          $resolition_one = (isset($params['views']['resolition_one'])) ? $params['views']['resolition_one'] : '5';
          $layout->resolition_one = JHTML::_('select.genericlist',$test, 'vi_resolition_one',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_one);

          $resolition_two = (isset($params['views']['resolition_two'])) ? $params['views']['resolition_two'] : '4';
          $layout->resolition_two = JHTML::_('select.genericlist',$test, 'vi_resolition_two',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_two);

          $resolition_three = (isset($params['views']['resolition_three'])) ? $params['views']['resolition_three'] : '3';
          $layout->resolition_three = JHTML::_('select.genericlist',$test, 'vi_resolition_three',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_three);

          $resolition_four = (isset($params['views']['resolition_four'])) ? $params['views']['resolition_four'] : '2';
          $layout->resolition_four = JHTML::_('select.genericlist',$test, 'vi_resolition_four',
                                                        'size="1" class="inputbox" ', 'value', 'text', $resolition_four);
          //resolutions
          
          $test = array();
          $test[]  = JHTML::_('select.option','1','On');
          $test[]  = JHTML::_('select.option','0','Off');
          $instance_grid = (isset($params['views']['instance_grid'])) ? $params['views']['instance_grid'] : '0';
          $layout->instance_grid = JHTML::_('select.genericlist',$test, 'vi_instance_grid',
                                                        'size="1" class="inputbox" ', 'value', 'text', $instance_grid);

          $test = array();
          $test[]  = JHTML::_('select.option','auto','auto');
          $test[]  = JHTML::_('select.option','custom','custom');

          $auto_custom = (isset($params['views']['auto_custom'])) ? $params['views']['auto_custom'] : '0';
          $layout->auto_custom = JHTML::_('select.genericlist',$test, 'vi_auto_custom',
                                                        'size="1" class="inputbox" ', 'value', 'text', $auto_custom);

          $space_between = (isset($params['views']['space_between'])) ? $params['views']['space_between'] : '0';
          $layout->space_between = "<input type='number' min='0' name='vi_space_between' value='".$space_between."'>";

          $lay_min_width = (isset($params['views']['lay_min_width'])) ? $params['views']['lay_min_width'] : '200';
          $layout->lay_min_width = "<input type='number' min='0' name='vi_lay_min_width' value='".$lay_min_width."'>";

          
          //end
          $field_for_link = array();
          $field_for_link[]  = JHTML::_('select.option','cat_title','Category Title');
          $field_for_link[]  = JHTML::_('select.option','cat_image','Category Image');
          $layout->link_field = JHTML::_('select.genericlist',$field_for_link, 'vi_link_field[]',
                                        'size="4" multiple="multiple" class="inputbox" ', 'value', 'text', $selected_link);
          $layout_title = (isset($params['views']['layout_title'])) ? $params['views']['layout_title'] : '';
          $show_layout_title = (isset($params['views']['show_layout_title'])) ? $params['views']['show_layout_title'] : '0';
          $test = array();
          $test[]  = JHTML::_('select.option','1','Show');
          $test[]  = JHTML::_('select.option','0','Hide');
          $layout->layout_title = "<input type='text' name='vi_layout_title' value='".$layout_title."'>";
          $layout->show_layout_title = JHTML::_('select.genericlist',$test, 'vi_show_layout_title',
                                                        'size="1" class="inputbox" ', 'value', 'text', $show_layout_title);

          if($customTask){
            AdminViewLayout :: updateLayoutFieldList($option, $layout, $entity);
            break;
          }
          AdminViewLayout :: editLayout($option, $layout, $entity);
          return;
        break;
      }
    }
  }

  static function saveLayout($option){
    global $db, $user, $os_cck_configuration, $task, $app;
    $data = array();
    $form_data = JRequest::getVar('form_data','');
  

    //for parsing string to variables
    $data['code_field_unique'] = array();
    for(;strlen($form_data) > 10000;){
      $variables_substring = substr($form_data,0,10000);
      $lenth = strrpos($variables_substring,'&');
      $variables_substring = substr($variables_substring,0,$lenth);
      parse_str($variables_substring, $post);
      $data = array_merge($data,$post);
      //fix bug for unique fields
      if(isset($post['code_field_unique'])){
        foreach ($post['code_field_unique'] as $key => $value) {
          foreach ($value as $stringKey => $stringValue) {
            $data['code_field_unique'][$key][$stringKey] = $stringValue;
          }
        }
      }
      //end
      $form_data = substr($form_data,$lenth,strlen($form_data));
    }
    parse_str($form_data, $post);
    //fix bug for unique fields
    if(isset($post['code_field_unique'])){
      foreach ($post['code_field_unique'] as $key => $value) {
        foreach ($value as $stringKey => $stringValue) {
          $data['code_field_unique'][$key][$stringKey] = $stringValue;
        }
      }
      unset($post['code_field_unique']);
    }
    //end
    $data = array_merge($data,$post);
  //end
    $layout = new os_cckLayout($db);




    if((!isset($data['title']) || empty($data['title'])) && $task != 'cancel_layout'){
      echo json_encode(array("status"=>"title"));
      exit();
    }

    if ($data['type'] == 'instance' || $data['type'] == 'add_instance'
        || $data['type'] == 'request_instance' || $data['type'] == 'buy_request_instance'
        || $data['type'] == 'rent_request_instance' || $data['type'] == 'all_instance'
        || $data['type'] == 'calendar'
        || $data['type'] == 'review_instance' || $data['type'] == 'show_review_instance') {
      $data['params']['fields'] = array();
      $data['params']['views'] = array();
      foreach ($data as $key => $var) {
        if(strpos($key, 'default_value')){
          $var = trim($var);
        }
        if (strpos($key, 'fi_') === 0) $data['params']['fields'][str_replace('fi_', '', $key)] = $var;
        if (strpos($key, 'vi_') === 0) $data['params']['views'][str_replace('vi_', '', $key)] = $var;
      }
    }
    if ($data['type'] == 'category' || $data['type'] == 'all_categories' || $data['type'] == 'search') {
      $data['params']['fields'] = array();
      $data['params']['views'] = array();
      foreach ($data as $key => $var) {
          if (strpos($key, 'fi_') === 0) $data['params']['fields'][str_replace('fi_', '', $key)] = $var;
          if (strpos($key, 'vi_') === 0) $data['params']['views'][str_replace('vi_', '', $key)] = $var;
          if (strpos($key, 'os_') === 0) $data['params']['search_params'][str_replace('os_', '', $key)] = $var;
      }
    }
    $layout->load($data['lid']);
    $data['changed'] = date("Y-m-d H:i:s");

    if (!$layout->getDefaultLayoutCheck($layout->lid, $layout->type, $layout->fk_eid)){
        $layout->approved = 1;
    }
    else{
        $layout->approved = 0;
    }

    $data['approved'] = $layout->approved;
    $data['title'] = $data['title'];
    $data['published'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;

//print_r($_REQUEST['fieldsParams']);exit;
    $data['params']['form_params'] = $_REQUEST['form_params'];
    $data['params']['columns_number'] = $_REQUEST['columns_number'];
    $data['params']['rows_number'] = $_REQUEST['rows_number'];
    $data['params']['attachedModuleIds'] = $data['attached_module_ids'];
    $data['params']['row_ids'] = $data['row_ids'];
    $data['params']['column_ids'] = $data['column_ids'];
    if(isset($data['cck_mail'])){
      $data['mail'] = serialize($data['cck_mail']);
    }
    $data['params'] = serialize($data['params']);
    if(isset($data['code_field_unique'])){
      $data['custom_fields'] = serialize($data['code_field_unique']);
    }else{
      $data['custom_fields'] = serialize(array());
    }
    if (!$layout->bind($data)) {
      echo json_encode(array("status"=>"error"));
      exit ();
    }

    if($task == 'cancel_layout'){
       $layout->checkin();

      $layoutForCheck = new os_cckLayout($db);
      $layoutForCheck->load($layout->lid);

      if(empty($layoutForCheck->title) && empty($layoutForCheck->params)){
        $layoutForCheck->delete($layoutForCheck->lid);
      }

      echo json_encode(array("status"=>'cancel',"lid"=>$layout->lid));
      return;
    }


    // print_r($layout);
    // exit;

    $layout->store();

    $html = urlencode(trim($_REQUEST['layout_html']));
    $bootstrap2 = str_replace('class="row', 'class="row-fluid', trim($_REQUEST['layout_html']));
    $bootstrap2 = trim($_REQUEST['layout_html']);
    for($i=1;$i<=12;$i++){
      $bootstrap2 = str_replace("col-lg-".$i, "span".$i, $bootstrap2);
    }
    $bootstrap2 = str_replace('class="row', 'class="row-fluid', $bootstrap2);
    $bootstrap2 = urlencode(trim($bootstrap2));

    $query = "UPDATE #__os_cck_layout_html SET fk_lid='".$layout->lid."',layout_html='".$html."' "
              ."\n WHERE fk_lid='".$layout->lid."' AND bootstrap='3'";
    $db->setQuery($query);
    $db->query();

    $query = "UPDATE #__os_cck_layout_html SET fk_lid='".$layout->lid."',layout_html='".$bootstrap2."' "
              ."\n WHERE fk_lid='".$layout->lid."' AND bootstrap='2'";
    $db->setQuery($query);
    $db->query();
    $status =  JText::_("COM_OS_CCK_SUCCESS");
    echo json_encode(array("status"=>$status,"lid"=>$layout->lid));
  }

  static function updateLayoutFieldList($option, $lid){
    self::editLayout($option, $lid, true);
  }

  static function getTmpls($tmpl_path){
    global $db;
    $path = JPath::clean(JPATH_ROOT . $tmpl_path);
    $query = "SELECT template FROM #__template_styles WHERE client_id=0 AND home=1";
    $db->setQuery($query);
    $template = $db->loadResult();
    $altpath = JPATH_SITE . '/templates/' . $template . '/html/' .str_replace('/components/','',$tmpl_path);
    $return = array();
    $result = array();
    $layouts1 = array();
    $layouts3 = array();
    if (is_dir($path) && ($layouts1 = JFolder::files($path, '^tmpl_.*\.php$', false, true))) {
      foreach ($layouts1 as $i => $file) {
        $select_file_name = pathinfo($file);
        $select_file_name = $select_file_name['filename'];
        $layouts3[] = $select_file_name;
      }
    }
    $layouts2 = array();
    $layouts4 = array();
    if (is_dir($altpath) && ($layouts2 = JFolder::files($altpath, '^tmpl_.*\.php$', false, true))) {
      foreach ($layouts2 as $i => $file) {
        $select_file_name = pathinfo($file);
        $select_file_name = $select_file_name['filename'];
        $layouts4[] =  $select_file_name;
      }
    }
    $return = array_merge($layouts3,$layouts4);
    $return = array_unique($return);
    foreach ($return as $tmpl) {
      $result[] = array('value' => str_replace("tmpl_", "", basename($tmpl, ".php")),
                        'text' => str_replace("tmpl_", "", basename($tmpl, ".php")));
    }
    return $result;
  }


  static function removeLayouts($lid, $option){
    global $db, $app, $option;

    if (!is_array($lid) || count($lid) < 1) {
      echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
      exit;
    }

    if (count($lid)) {
      foreach ($lid as $id) {
        $query = "SELECT eiid FROM #__os_cck_entity_instance WHERE fk_lid = ".$id;
        $db->setQuery($query);
        $eiid = $db->loadResult();

        if($eiid){

          // for add_instance, buy_request_instance,  rent_request_instance ,request_instance,review_instance
          $layout = new os_cckLayout($db);
          $layout->load($id);

          $query = "SELECT lid FROM #__os_cck_layout WHERE type = '" . $layout->type . "'";
          $query .= " AND lid != ".$id;
          $db->setQuery($query);
          $lay_id = $db->loadResult();

          if($lay_id){
            $query = "UPDATE #__os_cck_entity_instance SET fk_lid = ".$lay_id." WHERE fk_lid = ".$id;
            $db->setQuery($query);
            if($db->execute()) $layout->delete();
          }else{  
            echo "<script> alert('You can not delete this layout'); window.history.go(-1);</script>\n";
            exit;
          }

        }else{
          $layout = new os_cckLayout($db);
          $layout->load($id);
          $layout->delete();
        }

      }
    }

    // if (count($lid)) {
    //   foreach ($lid as $id) {
    //     $instance = new os_cckLayout($db);
    //     $instance->load($id);
    //     $instance->delete();
    //   }
    // }

    $app->redirect("index.php?option={$option}&task=manage_layout");
  }


  static function publishLayouts($lid, $publish, $option){
    global $db, $user,$app;
    if (!is_array($lid) || count($lid) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
    }
    $lids = implode(',', $lid);
    $db->setQuery("UPDATE #__os_cck_layout SET published='$publish'" .
    "\nWHERE lid IN ($lids) AND (checked_out=0 OR (checked_out='$user->id'))");
    if (!$db->query()) {
      echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (count($lid) == 1) {
      $layout = new os_cckLayout($db);
      $layout->checkin($lid[0]);
    }
    $app->redirect("index.php?option={$option}&task=manage_layout");
  }

  static function approveLayouts($lid, $approve, $option){

    global $db, $user, $app;
    if (!is_array($lid) || count($lid) < 1) {
      $action = $approve ? 'publish' : 'unpublish';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
    }

    $lids = implode(',', $lid);


    foreach ($lid as $id) {

      $db->setQuery("SELECT cl.fk_eid, cl.type FROM #__os_cck_layout AS cl WHERE cl.lid='" . $id . "'");
      $db->query();
      $layout = $db->loadAssoc();

      $db->setQuery("SELECT COUNT(lid) FROM #__os_cck_layout WHERE fk_eid = " . $layout['fk_eid'] ." AND type = '" . $layout['type']."' AND approved = 1");
      $db->query();
      $count = $db->loadResult();

      if($count != 1 || $approve)
      {
          $db->setQuery("UPDATE #__os_cck_layout AS cl SET cl.approved='0' "
                              . "\n WHERE cl.type='" . $layout['type'] . "' "
                              . "\n AND  cl.fk_eid='" . $layout['fk_eid'] . "' ");
          $db->query();
      }  
      
      if ($approve == 1) {
          $db->setQuery("UPDATE #__os_cck_layout AS cl SET cl.approved='1' WHERE cl.lid='" . $id . "' ");
          $db->query();
      }

    }

    $app->redirect("index.php?option={$option}&task=manage_layout");

  }

}
