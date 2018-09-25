<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @description OrdaSoft Content Construction Kit
*/

class Category
{
  static function listCategories($option, $lid = 0){  
    global $app,$os_cck_configuration, $db, $user, $acl, $Itemid ,$moduleId;
    $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
    $currentcat = NULL;
    $session = JFactory::getSession();
    if(!$moduleId){
      // Params(cck component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
        $menu = new JTableMenu($db);
        $menu->load($Itemid);
        $params = new JRegistry;
        $params->loadString($menu->params);
      } else {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $params = new JRegistry;
        $params = $menu->getParams( $Itemid );
      }//end
    }else{
      $mod_row =  JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
      if (! $mod_row->load ( $moduleId )) {
        JError::raiseError ( 500, $mod_row->getError () );
      }
      //module params
      if (version_compare(JVERSION, '3.0', 'ge')) {
        $params = new JRegistry;
        $params->loadString($mod_row->params);
      } else {
        $params = new JRegistry($mod_row->params);
      }//end
      //itemId
      $query = "SELECT id  FROM #__menu WHERE menutype like '%menu%'"
                      . "\n AND link LIKE '%option=com_os_cck%'"
                      . "\n AND params LIKE '%back_button%'"
                      . "\n AND params LIKE '%allcategories_layout%'"
                      . "\n AND published = 1";
      $db->setQuery($query);
      $Itemid = $db->loadResult();
      if($params->get('ItemId'))$Itemid=$params->get('ItemId');
         
    }
    
    $allcategories_layout = new os_cckLayout($db);
    $category_layout = new os_cckLayout($db);
    //(expression ? true_value : false_value)
    if($lid){
      $allcategories_layout->load($lid);
    }else{
      $allcategories_layout->load($params->get('layout')?$params->get('layout') : $params->get('allcategories_layout'));
    }
    if (($allcategories_layout->type != 'all_categories' || $allcategories_layout->fk_eid <= 0) ){
        if(!$params->get('layout'))//component or module
            JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_ALLCATEGORY_LAYOUT") );
        else
            JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_MODULE_ALLCATEGORY_LAYOUT") );
        return;    
    }
    //load category layout params
    $fields_from_params = $all_cat_params=unserialize($allcategories_layout->params);
    $entity_id = $allcategories_layout->fk_eid;
    if($fields_from_params['views']['category_layout'] == -1){//we set default lyout
        if($category_layout->getDefaultLayout($entity_id, 'category')){
            $category_layout->load(intval($category_layout->getDefaultLayout($entity_id, 'category')));
            $fields_from_params = unserialize($category_layout->params);
        }else{
            JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_CATEGORY_LAYOUT"));
            return;   
        }
    }else{
        $category_layout->load($fields_from_params['views']['category_layout']);
        $fields_from_params = unserialize($category_layout->params);
    }//end category layout params
    $header = $params->get('layout') ? "" : set_header_name_cck($menu, $Itemid);
    $params->def('header', $header);
    $params->def('pageclass_sfx', '');
    $params->def('show_search', '1');
    $params->def('back_button', $app->getCfg('back_button'));
    $query = "SELECT c.*,(SELECT COUNT(ccc.fk_cid) FROM #__os_cck_categories_connect AS ccc "
                            . "\n INNER JOIN #__os_cck_entity_instance AS cei ON ccc.fk_eiid=cei.eiid "
                            . "\n INNER JOIN #__os_cck_layout AS ley ON ley.lid = cei.fk_lid "
                            . "\n WHERE ccc.fk_cid=c.cid AND ley.type = 'add_instance' AND cei.fk_eid = '".$allcategories_layout->fk_eid."') AS items, '0' as display "
                            . "\n FROM  #__os_cck_categories AS c "
                            . "\n WHERE c.section='com_os_cck' "
                            . "\n AND c.published = '1' "
                            . "\n ORDER BY c.ordering ";
    $db->setQuery($query);
    $cat_all = $db->loadObjectList();
    for ($i = 0; $i < count($cat_all); $i++) {
      if (os_cck_site_controller::is_exist_curr_and_subcategory_items($cat_all[$i]->cid, $allcategories_layout->fk_eid )) {
        $cat_all[$i]->display = 1;
        if($fields_from_params['views']['sub_category_level'] == 0 && $cat_all[$i]->parent_id > 0)
            $cat_all[$i]->display = 0;
      }
    }

    $user = JFactory::getUser();
    $categories= array();
    for ($i = 0; $i < count($cat_all); $i++) {
      if (($cat_all[$i]->params !== implode(',',array_diff(explode(',',$cat_all[$i]->params),$user->groups)))
          || $cat_all[$i]->params == 1) {
          $categories[]=$cat_all[$i];
      }
    } 

    if (empty($categories))
        JError::raiseWarning(0, JText::_("COM_OS_CCK_NOCAT_ALLCATEGORY_LAYOUT") );
    $currentcat = new stdclass();
    $currentcat->header = $params->get('header');
    // page description
    $currentcat->descrip = JText::_("COM_OS_CCK_DESC");
    // used to show table rows in alternating colours
    $tabclass = array('sectiontableentry1', 'sectiontableentry2');
    $params->set('all_cat_layout_params',$all_cat_params);
    $params->set('cat_layout_params',$fields_from_params);
    $layout_params = unserialize($allcategories_layout->params);
    $layout_params['custom_fields'] = unserialize($allcategories_layout->custom_fields);
    $layout_params['all_cat_layout_params'] = $fields_from_params;
    $bootstrap_version = $session->get( 'bootstrap','2');
    $allcategories_layout->layout_html = $allcategories_layout->getLayoutHtml($bootstrap_version);
    if(isset($layout_params['attachedModuleIds'])){
      $layout_params['attachedModule'] = explode('|_|',$layout_params['attachedModuleIds']);
      $mids = array();
      foreach ($layout_params['attachedModule'] as $attachedModuleId) {
        if($attachedModuleId){
          $mids[] = $attachedModuleId;
        }
      }
      if($mids){
        $mids = str_replace('m_', '', $mids);
        $mids = implode(',', $mids);
        $query = "SELECT id, title, module as type FROM #__modules WHERE id IN (" . $mids . ")";
        $db->setQuery($query);
        $layout_params['attachedModule'] = $db->loadObjectList('id');
      }
    }

    ViewCategory::showCategories($option, $categories ,$allcategories_layout , $layout_params);
  }

  static function showCategory($option, $catid, $lid,$parent_layout_params = 0){ 

    global $app, $db, $acl, $user, $doc, $session;
    global $Itemid, $os_cck_configuration, $limit, $total, $limitstart,$moduleId ,$session;


    $Itemid = intval($_REQUEST['Itemid']);
    $moduleId =(($moduleId == 0
                 || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
    $category = new os_cckCategory($db);
    $category->load($catid);



    $currentcat = NULL;


    if(!$moduleId){
      // Params(cck component menu)
      $menu = new JTableMenu($db);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);

    }else{

      $mod_row =  JTable::getInstance ( 'Module', 'JTable' );//load module tables and params

      if (! $mod_row->load ( $moduleId )) {
        JError::raiseError ( 500, $mod_row->getError () );
      }
      //module params
      $params = new JRegistry;
      $params->loadString($mod_row->params);
      //itemId


      $value = ($params->get('layout_type') == 'category')?
                              'category_layout' : 'map_category_layout';

      $query = "SELECT id  FROM #__menu WHERE menutype like '%menu%'"
                      . "\n AND link LIKE '%option=com_os_cck%'"
                      . "\n AND params LIKE '%back_button%'"
                      . "\n AND params LIKE '%".$value."%'"
                      . "\n AND published = 1";
      $db->setQuery($query);
      $Itemid = $db->loadResult();
       if($params->get('ItemId'))$Itemid=$params->get('ItemId');
    }

    
    $entity_layout = new os_cckLayout($db);
    $category_layout = new os_cckLayout($db);

    
    if(!$lid || $lid == -1){
      if($params->get('allcategories_layout') || $params->get('layout_type') == 'all_categories'){
        $all_category_layout = new os_cckLayout($db);
        $all_category_layout->load($params->get('layout')?
                                   $params->get('layout') : $params->get('allcategories_layout'));
        $fields_from_params = unserialize($all_category_layout->params);
        $entity_id = $all_category_layout->fk_eid;
        if($fields_from_params['views']['category_layout'] == -1){//we set default lyout
          if($category_layout->getDefaultLayout($entity_id, 'category')){
            $category_layout->load(intval($category_layout->getDefaultLayout($entity_id, 'category')));
            $fields_from_params = unserialize($category_layout->params);
          }else{
            JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_CATEGORY_LAYOUT"));
            return;   
          }
        }else{
          $category_layout->load($fields_from_params['views']['category_layout']);
          if(!$category_layout->lid){
            JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_CATEGORY_LAYOUT"));
            return;
          }
          $fields_from_params = unserialize($category_layout->params);
        }
      }//end all_cat menu
      //from category menu
      
      if($params->get('category_layout') || $params->get('layout_type') == 'category'){
          
        $category_layout->load($params->get('layout')?$params->get('layout') : $params->get('category_layout'));
        if(!$category_layout->published){
            JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_CATEGORY_LAYOUT"));
            return;
        }
        $fields_from_params = unserialize($category_layout->params);
        $entity_id = $category_layout->fk_eid;
        if (!$entity_id){
          if(!$params->get('layout'))//component or module
              JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_CATEGORY_LAYOUT"));
          else
              JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_MODULE_CATEGORY_LAYOUT"));
          return;
        }
        if($catid == 0){
           $catid = $params->get('layout')?$params->get('item') : $params->get('category');
        }
        if($catid < 1){
          if(!$params->get('layout'))//component or module
              JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_CATEGORY"));
          else
              JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_MODULE_CATEGORY"));
          return;
        }
        
        if($params->get('category_layout'))
            $category->load($params->get('category'));
        else
            $category->load($params->get('item'));
      }//end category menu
    
      $catid = ($catid == 0) ? $params->get('category') : $catid;
      $search_layout = new os_cckLayout($db);
      $search_layout->load($entity_layout->getDefaultLayout($entity_id, 'search'));
      if($search_layout->published == 0){
          $params->def('show_search','0');
      }else{
          $params->def('show_search','1');
      }//end show serch
    }else{

      $category_layout->load($lid);
      $fields_from_params = unserialize($category_layout->params);
      $entity_id = $category_layout->fk_eid;
    }


    if($category_layout->type != 'all_categories' && $category_layout->type != 'category'){
      JError::raiseWarning(0, "Layout type load error Category|All Category layout ID: ".$category_layout->lid);
      return;
    }
    $limit = $fields_from_params['views']['pagenator_limit'];

    if(isset($fields_from_params['views']['limit'])){
        $max_items = $fields_from_params['views']['limit'];
    }else{
        $max_items = 0;
    }

    
    if(isset($catid) && !empty($catid)){ //is not isset category (skip access checking)

      $db->setQuery("SELECT * FROM `#__os_cck_categories` e WHERE e.`cid` = {$catid}");
      $carCatParams =$db->loadObjectList();
      if(!$carCatParams){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_CATEGORY"));
        return;
      }


      $carCatParams = $carCatParams[0];
      //user access to category
      $user = JFactory::getUser();
      if (($carCatParams->params == implode(',',array_diff(explode(',',$carCatParams->params),$user->groups)))
          && $carCatParams->params != 1) {
          JError::raiseWarning(0, JText::_("COM_OS_CCK_ACCESS_CATEGORY"));
          return;
      }//end  
    }

    if($category_layout->fk_eid){
        $entity_name = $category_layout->fk_eid;
    }else{
        return;
    }


    //creating order by list
    $fields_from_params["views"]["sortField"][] = isset($fields_from_params["fields"]["indexed_category_order_by"])?$fields_from_params["fields"]["indexed_category_order_by"]:'';
    $entity = new os_cckEntity($db);
    $entity->load($entity_id);
    $fields_list = $entity->getFieldList();
    if(isset($fields_from_params["fields"]['order_by_fields_category_order_by']) && isset($fields_from_params["views"]["sortField"][0])){
      foreach ($fields_from_params["fields"]['order_by_fields_category_order_by'] as $key => $value) {
        if($value != $fields_from_params["views"]["sortField"][0]){
          $fields_from_params["views"]["sortField"][$key] = array();
          foreach ($fields_list as $entity_field) {
            if($entity_field->db_field_name == $value){
              $fields_from_params["views"]["sortField"][$key]['value'] = $value;
              $fields_from_params["views"]["sortField"][$key]['text'] = $entity_field->field_name;
              $fields_from_params['views']['order_by_fields'][] = $value;              
            }
          }
        }
      }
    }

    //end
    //get params after reload page with new order direction
    //session need if we are used pagination(save our sort params in all pages)
    //default value set in cat layout params
    if(isset($_REQUEST['order_direction']) && !empty($_REQUEST['order_direction'])){
      $fields_from_params["views"]["sortType_category_order_by"] = protectInjectionWithoutQuote('order_direction','');//need for order by desc//asc
    }elseif($session->get('order_direction','')){ 
      $fields_from_params["views"]["sortType_category_order_by"] = $session->get('order_direction');
    }



    if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])){
      $fields_from_params["views"]["selected"] = protectInjectionWithoutQuote('order_field','');//need for order by field name
    }elseif($session->get('selected','')){ 
      $fields_from_params["views"]["selected"] = $session->get('selected');
    }else
      $fields_from_params["views"]["selected"] = isset($fields_from_params["fields"]["indexed_category_order_by"])?$fields_from_params["fields"]["indexed_category_order_by"]:'';
        //end sort params
    $header = $params->get('layout') ? "$category_layout->title" : set_header_name_cck($menu, $Itemid);
    $params->def('header', $header);
    $params->def('pageclass_sfx', '');
    $params->def('category_name', $category->name);




    //alfabetical pagination

    $sp = 0;
    if (array_key_exists("sp", $_REQUEST)){
      $sp = JFactory::getApplication()->input->getInt('sp', 0);
    }
    $where = '';
    $list_str = array();
    if (array_key_exists("letindex", $_REQUEST)) {
        $search = JFactory::getApplication()->input->getCmd("letindex",'');

        if(isset($_REQUEST['now_indexed']) && $search != 'all'){

          if($sp == 1 && $_REQUEST['now_indexed'] == $fields_from_params["views"]["selected"]){
            $where = " AND LOWER(instance." . $_REQUEST['now_indexed'] . ") LIKE '$search%' ";
          }else{
            $where = '';
          }
        }
    }

    if(strstr($fields_from_params["views"]["selected"], 'text_textfield') !== false){

       $query = " SELECT DISTINCT UPPER(SUBSTRING(instance.".$fields_from_params["views"]["selected"].", 1,1)) AS symb FROM  #__os_cck_entity_instance AS ei "
          . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
          . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
          . "\n LEFT JOIN `#__os_cck_content_entity_$entity_name` as instance ON instance.`fk_eiid` = ei.eiid "
          . "\n WHERE ei.published='1' "
          . "\n AND ei.approved='1' "
          . "\n AND lay.type='add_instance' "
          . "\n AND ccc.fk_cid={$catid} "
          . "\n AND ei.fk_eid='" . $entity_id . "' GROUP BY ei.eiid "; 

        $db->setQuery($query);
        $tmp_arr = $db->loadObjectList();

        if(count($tmp_arr)>1){

          $symb_list_str = '<ul>';
          foreach($tmp_arr as $symbol){

            if(empty($symbol->symb)){
              continue;
            }

          $symb_list_str.= '<li><a href="index.php?option=' . $option . 
          '&view=category&letindex=' . $symbol->symb . '&sp=1&Itemid=' . $Itemid . 
            '&now_indexed=' . $fields_from_params["views"]["selected"] . '">' . 
          $symbol->symb . '</a></li> ';
            
          }

          //check string not empty
          $temp_var =  strip_tags($symb_list_str) ;
          if(!empty($temp_var) ){
            $symb_list_str.= '<li><a href="index.php?option=' . $option . 
            '&view=category&letindex=all&sp=1&Itemid=' . $Itemid . 
            '&now_indexed=' . $fields_from_params["views"]["selected"] . '">all</a></li> ';
          }
          
          $symb_list_str.= "</ul>";
          $list_str['symbol_list'] = $symb_list_str;
        }else{
          $list_str['symbol_list'] = false;
        }
    }else{
      $list_str['symbol_list'] = false;
    }

    //alfabetical pagination


    $query = " SELECT COUNT(DISTINCT ei.eiid) FROM  #__os_cck_entity_instance AS ei "
            . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
            . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
            . "\n LEFT JOIN `#__os_cck_content_entity_$entity_name` as instance ON instance.`fk_eiid` = ei.eiid "
            . "\n WHERE ei.published='1' "
            . "\n AND ei.approved='1' "
            // . "\n AND ei.featured_clicks!='0' "
            // . "\n AND ei.featured_shows!='0' "
            . "\n AND lay.type='add_instance' "
            . "\n AND ccc.fk_cid={$catid} "
            . "\n AND ei.fk_eid='" . $entity_id . "' $where ";
    $db->setQuery($query);
    $total = $db->loadResult();

    if($max_items != '0' && $max_items < $total ) {
      if($limit > $max_items )$limit = $max_items;
      $total = $max_items;
    }




     if( isset($fields_from_params['views']['selected']) && 
         ( !isset( $fields_from_params['views']['order_by_fields'] ) || !is_array( $fields_from_params['views']['order_by_fields'] ) ||
          ( is_array( $fields_from_params['views']['order_by_fields'] ) && !in_array($fields_from_params["views"]["selected"],$fields_from_params['views']['order_by_fields']) 
          ) 
         )
        )
     {
          $fields_from_params["views"]["selected"] = isset($fields_from_params["fields"]["indexed_category_order_by"])?$fields_from_params["fields"]["indexed_category_order_by"]:'';
     }




    $pageNav = new JPagination($total, $limitstart, $limit);
    $query = " SELECT ei.eiid FROM  #__os_cck_entity_instance AS ei "
            . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
            . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
            . "\n LEFT JOIN `#__os_cck_content_entity_$entity_name` as instance ON instance.`fk_eiid` = ei.eiid "
            . "\n WHERE ei.published='1' "
            . "\n AND ei.approved='1' "
            // . "\n AND ei.featured_clicks!='0' "
            // . "\n AND ei.featured_shows!='0' "
            . "\n AND lay.type='add_instance' "
            . "\n AND ccc.fk_cid={$catid} "
            . "\n AND ei.fk_eid='" . $entity_id . "' $where GROUP BY ei.eiid ";

        if(isset($fields_from_params["fields"]["indexed_category_order_by"])){ // if selected sortable field
          $orderby = (!empty($fields_from_params["views"]["sortType_category_order_by"])) ? $fields_from_params["views"]["sortType_category_order_by"] : 'ASC';
          if (isset($fields_from_params["views"]["selected"]) && !empty( $fields_from_params["views"]["selected"])) {
            if($fields_from_params["views"]["selected"] == 'title')
              $query .= "ORDER BY ei.title $orderby ";
            else
              $query .= "ORDER BY instance.`{$fields_from_params["views"]["selected"]}` $orderby ";
          }elseif($fields_from_params["fields"]["indexed_category_order_by"] == 'eid') {
                  $query .= "ORDER BY ei.eiid $orderby ";
          }elseif ($fields_from_params["fields"]["indexed_category_order_by"] == 'title') {
                  $query .= "ORDER BY ei.title $orderby ";
          }else { // for other fields
                  $query .= "ORDER BY instance.`{$fields_from_params["fields"]["indexed_category_order_by"]}` $orderby ";
          }
        }


    $session = JFactory::getSession();
    $session->set( 'queryItemIds', $query );//need for pagination in instances//we save our query to know how to sort ourinstance//
    $query .= " LIMIT $pageNav->limitstart, $pageNav->limit ";
    $db->setQuery($query); 
    $items = (version_compare(JVERSION, "3.0.0", "lt")) ? $db->loadResultArray() : $db->loadColumn();
    $instancies = array();
    foreach ($items as $item) {
        $instance = new os_cckEntityInstance($db);
        $instance->load($item);
        $instancies[] = $instance;
    }
    //////////////////////////////////////////////////////////////////
    $query = " SELECT c.*, (SELECT COUNT(ccc.fk_cid) "
            . "\n FROM #__os_cck_categories_connect AS ccc WHERE ccc.fk_cid=c.cid) AS items, '0' AS display "
            . "\n FROM  #__os_cck_categories AS c WHERE section='com_os_cck' ORDER BY ordering ";
    $db->setQuery($query);
    $cat_all1 = $db->loadObjectList();
    if (count($cat_all1) > 0) {
        foreach ($cat_all1 as $cat_item1) {
            $categories[] = $cat_item1;
        }
        for ($i = 0; $i < count($categories); $i++) {
          if (os_cck_site_controller::is_exist_curr_and_subcategory_items($categories[$i]->cid, $entity_id)) $categories[$i]->display = 1;
        }
    } else $categories = array();
    //cck_constructPathway($category, 'category');
    $params->def('show_rating', 1);
    $params->def('hits', 1);
    $params->def('back_button', $app->getCfg('back_button'));
    $currentcat = new stdclass();

    $currentcat->descrip = $category->description;
    // page image
    $currentcat->img = null;
    $path = JURI::root() . '/images/stories/';
    if ($category->image != null && count($category->image) > 0) {
        $currentcat->img = $path . $category->image;
        $currentcat->align = $category->image_position;
    }
    $currentcat->header = $params->get('header');
    $currentcat->header = $currentcat->header . ": " . $category->title;
    $currentcat->title = $category->title;
    $currentcat->inst_count = getInstanceCountInCategory($category->cid);


    $tabclass = array('sectiontableentry1', 'sectiontableentry2');
    if(!$instancies && !os_cck_site_controller::is_exist_curr_and_subcategory_items($catid,$entity_id)){
        echo '<div style="text-align:center"><h2>'.JText::_("COM_OS_CCK_CATEGORY_IS_EMPTY").'</h2></div>';
    }
    $layout_params = unserialize($category_layout->params);
    $layout_params['custom_fields'] = unserialize($category_layout->custom_fields);
    $layout_params['cat_layout_params'] = $fields_from_params;
    $bootstrap_version = $session->get( 'bootstrap','2');
    $category_layout->layout_html = $category_layout->getLayoutHtml($bootstrap_version);
    $layout_params['parent_layout_params'] = $parent_layout_params;
    $layout_params['catid'] = $catid;
    if(isset($layout_params['attachedModuleIds'])){
      $layout_params['attachedModule'] = explode('|_|',$layout_params['attachedModuleIds']);
      $mids = array();
      foreach ($layout_params['attachedModule'] as $attachedModuleId) {
        if($attachedModuleId){
          $mids[] = $attachedModuleId;
        }
      }
      if($mids){
        $mids = str_replace('m_', '', $mids);
        $mids = implode(',', $mids);
        $query = "SELECT id, title, module as type FROM #__modules WHERE id IN (" . $mids . ")";
        $db->setQuery($query);
        $layout_params['attachedModule'] = $db->loadObjectList('id');
      }
    }
    $is_exist_sub_categories = os_cck_site_controller::is_exist_curr_and_subcategory_items($catid, $entity_id); 

    $layout = $category_layout;

    $type = 'category';

    require getLayoutPathCCK::getLayoutPathCom($option, $type);
  }
  
  static function showSearch($option, $catid,$lid = 0){ 

    global $app, $db, $acl, $user, $Itemid, $os_cck_configuration, $limit, $total, $limitstart,$moduleId;
    $Itemid = intval($_REQUEST['Itemid']);//id our menu
    $moduleId =(($moduleId == 0|| empty($moduleId)) 
                  && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
    $session = JFactory::getSession();
    if(!$moduleId){
      // Params(cck component menu)
      $menu = new JTableMenu($db);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
    }else{
      $mod_row =  JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
      if (! $mod_row->load ( $moduleId )) {
        JError::raiseError ( 500, $mod_row->getError () );
      }
      //module params
      $params = new JRegistry;
      $params->loadString($mod_row->params);
      //itemId
      $query = "SELECT id  FROM #__menu WHERE menutype like '%menu%'"
                      . "\n AND link LIKE '%option=com_os_cck%'"
                      . "\n AND params LIKE '%search_layout%'"
                      . "\n AND link LIKE '%show_search%'"
                      . "\n AND published = 1";
      $db->setQuery($query);
      $Itemid = $db->loadResult();
      if($params->get('ItemId'))$Itemid=$params->get('ItemId');
    }
    $layout = new os_cckLayout($db);
    $category_layout = new os_cckLayout($db);
    $fromSearch = 1;
    //end params
    if(!$lid){
      if($params->get('search_layout') || $params->get('layout_type') == 'search'){
        $layout->load($params->get('layout')?$params->get('layout') : $params->get('search_layout'));
        if(!$layout->lid || !$layout->published){
          JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_SEARCH_LAYOUT"));
          return;   
        }
      }//end search menu
    }else{
      $layout->load($lid);
    }
    if (!$layout->lid){
      JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_SEARCH_LAYOUT"));
      return;
    }
    $layout_params = unserialize($layout->params);
    $layout_params['custom_fields'] = unserialize($layout->custom_fields);
    $bootstrap_version = $session->get( 'bootstrap','2');
    $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
    $layout_params['catid'] = $catid;
    if(isset($layout_params['attachedModuleIds'])){
      $layout_params['attachedModule'] = explode('|_|',$layout_params['attachedModuleIds']);
      $mids = array();
      foreach ($layout_params['attachedModule'] as $attachedModuleId) {
        if($attachedModuleId){
          $mids[] = $attachedModuleId;
        }
      }
      if($mids){
        $mids = str_replace('m_', '', $mids);
        $mids = implode(',', $mids);
        $query = "SELECT id, title, module as type FROM #__modules WHERE id IN (" . $mids . ")";
        $db->setQuery($query);
        $layout_params['attachedModule'] = $db->loadObjectList('id');
      }
    }

    $ids = array();
    foreach($layout_params['search_params'] as $key => $var){
      if(isset($var["fid"]))$ids[] = $var["fid"];
    }

    if(count($ids)){
      $ids = implode(",", $ids);
      $query="SELECT ef.* FROM #__os_cck_entity_field as ef WHERE ef.fid IN($ids)";
      $db->setQuery($query);
      $fields = $db->loadObjectList("fid");
    }

  

    $type = 'show_search';
    require getLayoutPathCCK::getLayoutPathCom($option, $type);
    
  }

  static function show_attached_layout($option, $lid, $eiid, $category_params,$show_type = 0, $button_name = '', $catid = 0,$instancies = array(),$button_style=''){
    global $params,$db, $os_cck_configuration, $doc, $moduleId, $Itemid;
    $session = JFactory::getSession();
    $bootstrap_version = $session->get('bootstrap','2');
    $layout =  new os_cckLayout($db);
    $layout->load($lid);
    if(!$layout->lid){
      JError::raiseWarning(0, "Layout ID:".$lid." doesn't exists!" );
      return;
    }
    if($layout->type == 'instance' || $layout->type == 'all_instance'){
      foreach ($instancies as $key => $instance) {

        $featured = (isset($category_params['views']['featured'])?$category_params['views']['featured']:'0');

        if((isset($featured) && $featured != 0) 
          && ($instance->featured_clicks < 1 && $instance->featured_shows < 1)) continue;

        $layout = new os_cckLayout($db);
        $layout->load($lid);
        $bootstrap_version = $session->get( 'bootstrap','2');
        $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
        $layout_params = unserialize($layout->params);
        $layout_params['custom_fields'] = unserialize($layout->custom_fields);
        // $layout_params['views']['show_navigation'] = 0;
        $layout_params['has_price'] = 0;

        $layout_params['nextInstId'] = 0;
        $layout_params['prevInstId'] = 0;
        $layout->field_list = $instance->getFields();
        foreach ($layout->field_list as $field) {
          $html = urldecode($layout->layout_html);
          if(strpos($html,"{|f-".$field->fid."|}")){
            $layout_fields = $layout_params['fields'];
            if($field->field_type == 'decimal_textfield' && $layout_params['has_price'] == 0){
              if($layout_fields[$field->db_field_name.'_price_field']){
                $layout_params['has_price'] = $layout_fields[$field->db_field_name.'_price_field'];
              }
            }
          }
        }
        if($layout->type == 'all_instance'){
          $catid = $instance->cat_id;
        }
        if ($catid > 0) {
          $query = "SELECT * FROM #__os_cck_categories WHERE cid='{$catid}'";
          $db->setQuery($query);
          $category = $db->loadObjectList();
        } else {    
          $query = "SELECT * FROM #__os_cck_categories AS cc "
                  . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON cc.cid=ccc.fk_cid "
                  . "\n WHERE ccc.fk_eiid=" . $instance->eiid . ""
                  . "\n LIMIT 0,1";
          $db->setQuery($query);
          $category = $db->loadObjectList();
        }
        if($category)
          $category = $category[0];
        if(isset($category_params['views']['link_field'])){
          $layout_params['views']['link_field'] = $category_params['views']['link_field'];
        }
        
        if($category_params['views']['instance_layout'] == '-1'){
          $layout_params['views']['instance_layout'] = $layout->getDefaultLayout($instance->fk_eid, 'instance');
        }else{
          $layout_params['views']['instance_layout'] = $category_params['views']['instance_layout'];
        }
        if(isset($layout_params['attachedModuleIds'])){
          $layout_params['attachedModule'] = explode('|_|',$layout_params['attachedModuleIds']);
          $mids = array();
          foreach ($layout_params['attachedModule'] as $attachedModuleId) {
            if($attachedModuleId){
              $mids[] = $attachedModuleId;
            }
          }
          if($mids){
            $mids = str_replace('m_', '', $mids);
            $mids = implode(',', $mids);
            $query = "SELECT id, title, module as type FROM #__modules WHERE id IN (" . $mids . ")";
            $db->setQuery($query);
            $layout_params['attachedModule'] = $db->loadObjectList('id');
          }
        }
        $entityInstance = $instance;
        $type = 'instance';

        if(!isset($category_params['parent_layout_params']['all_cat_layout_params'])){
          require getLayoutPathCCK::getLayoutPathCom($option,$type);
        }
  
      }
    }elseif($layout->type == 'search'){


      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
      $layout_params = unserialize($layout->params);
      $layout_params['custom_fields'] = unserialize($layout->custom_fields);
      $layout_fields = $layout_params['fields'];
      $layout->parent_eiid = $eiid;
      if($show_type){
        $layout_params['show_type'] = $show_type;
      }
      $layout_params['button_name'] = $button_name;
      $layout_params['catid'] = $catid;

      ViewCategory::showSearch($option, $layout, $layout_params, $button_style);

      
    }elseif($layout->type == 'add_instance' || $layout->type == 'request_instance'){
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
      $layout_params = unserialize($layout->params);
      $layout_params['custom_fields'] = unserialize($layout->custom_fields);
      $layout_fields = $layout_params['fields'];
      $layout->parent_eiid = $eiid;
      $entityInstance = new os_cckEntityInstance($db);
      $entityInstance->fk_eid = $layout->fk_eid;
      $layout->field_list = $entityInstance->getFields();
      if($show_type){
        $layout_params['show_type'] = $show_type;
      }
      $layout_params['button_name'] = $button_name;
      $layout_params['catid'] = $catid;
      if(isset($layout_params['attachedModuleIds'])){
        $layout_params['attachedModule'] = explode('|_|',$layout_params['attachedModuleIds']);
        $mids = array();
        foreach ($layout_params['attachedModule'] as $attachedModuleId) {
          if($attachedModuleId){
            $mids[] = $attachedModuleId;
          }
        }
        if($mids){
          $mids = str_replace('m_', '', $mids);
          $mids = implode(',', $mids);
          $query = "SELECT id, title, module as type FROM #__modules WHERE id IN (" . $mids . ")";
          $db->setQuery($query);
          $layout_params['attachedModule'] = $db->loadObjectList('id');
        }
      }
      
      $type = $layout->type;
      require getLayoutPathCCK::getLayoutPathCom($option,$type);
    }elseif($layout->type == 'category'){
      Category::showCategory($option, $catid,$lid,$category_params);
    }
  }
}
