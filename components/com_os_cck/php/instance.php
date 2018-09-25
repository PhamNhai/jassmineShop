<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @description OrdaSoft Content Construction Kit
*/


class Instance{

    static function search($option, $catid){

  
      global $app, $db, $user, $mosConfig_absolute_path, $doc,
             $Itemid, $limit, $total, $limitstart, $search,$moduleId, $os_cck_configuration;


      $Itemid = intval($_REQUEST['Itemid']);
      $moduleId =(($moduleId == 0 || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
      $instancies = $names = array();
      $search = "";
      $params="";
      $multiple_query ="";
      $layout="";
      $pageNav="";
      $currentcat = NULL;
      $comp_search = array();
      $comp_search_and = array();
      $where = array();
      $fild_name = array();
      $multiple_table_name = array();
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
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $params = new JRegistry;
          $params->loadString($mod_row->params);
        } else {
          $params = new JRegistry($mod_row->params);
        }//end
      }
      $layout = new os_cckLayout($db);
      $search_layout = new os_cckLayout($db);
     //from os cck menu item(Search)
      if($params->get('search_layout') || $params->get('layout_type') == 'search'){
        $search_layout->load($params->get('layout')?$params->get('layout') : $params->get('search_layout'));
        if(!$search_layout->fk_eid){
          JError::raiseWarning(0, JText::_("COM_OS_CCK_REBILD_MENU"));
          return;
        }
      }else{
        if(protectInjectionWithoutQuote('lid','')){
          $search_layout->load(protectInjectionWithoutQuote('lid'));
        }else{
          JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_SEARCH_LAYOUT"));
          return;
        }
      }//end search layouts
      $entity_id = $search_layout->fk_eid;
      $fields_from_params = unserialize($search_layout->params);


      if($fields_from_params['views']['all_instance_layout'] == -1){//we set default lyout
        if($layout->getDefaultLayout($entity_id, 'all_instance')){
          $layout->load(intval($layout->getDefaultLayout($entity_id, 'all_instance')));
          $fields_from_params = unserialize($layout->params);

        }else{
          JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ALL_INSTANCE_LAYOUT"));
          return;
        }
      }else{
        $layout->load($fields_from_params['views']['all_instance_layout']);
        $fields_from_params = unserialize($layout->params);
      }
      if(empty($layout->lid)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ALL_INSTANCE_LAYOUT"));
        return;
      }
      $limit = $fields_from_params['views']['pagenator_limit'];
      if(isset($fields_from_params['views']['limit'])){
      $max_items = $fields_from_params['views']['limit'];
      }else{
        $max_items = 0;
      }

      $header = $layout->title;
      $params->def('header', $header);
      $params->def('pageclass_sfx', '');
      $params->def('show_rating', 1);
      $params->def('hits', 1);
      $params->def('back_button', $app->getCfg('back_button'));

      if (array_key_exists("search", $_REQUEST)) {
        $search = urldecode(protectInjectionWithoutQuote('search','','STRING'));
        $search = addslashes($search);
      }

       ////////start create search request

      if (isset($_REQUEST['search_title'])) {
        $comp_search[] = "LOWER(c.title) LIKE '%$search%'";
      }
      $reaquest = JRequest::get('request');
      //maybe shit code below
      foreach($reaquest AS $key => $var){

        if(strpos($key, '_datefrom')===strlen($key)-9){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_datefrom','',
            str_replace('os_cck_search_', '', $key))));
        }elseif(strpos($key, '_dateto')===strlen($key)-7){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_dateto','',
            str_replace('os_cck_search_', '', $key))));

        }elseif(strpos($key, '_from')===strlen($key)-5){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_from','',
            str_replace('os_cck_search_', '', $key))));
        }elseif(strpos($key, '_to')===strlen($key)-3){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_to','',
            str_replace('os_cck_search_', '', $key))));

        }elseif(strpos($key, '_range_eventfrom')===strlen($key)-16){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_range_eventfrom','',
            str_replace('os_cck_search_', '', $key))));

        }elseif(strpos($key, '_range_eventto')===strlen($key)-14){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('_range_eventto','',
            str_replace('os_cck_search_', '', $key))));

        }elseif(strpos($key, 'os_cck_search_') === 0){
          array_push($fild_name, 'fld.db_field_name='.$db->quote(str_replace('os_cck_search_', '', $key)));
        }
      }//end

      $fild_name = array_unique($fild_name);

      //all names and types of tables/fields
      if($fild_name){
        $query = "SELECT fld.db_field_name, fld.field_type "
                  ."\n FROM #__os_cck_entity_field AS fld "
                  ."\n WHERE ".implode(' OR ', $fild_name)." ORDER BY fid";
        $db->setQuery($query);
        $fields = $db->loadAssocList();
      
        //end
        $allowed_values='';

        $date_from = '0000-00-00';
        $date_to = '0000-00-00';

            // print_r($fields);
            //       exit;

        foreach($fields as $field){


            if($field['field_type'] == 'text_single_checkbox_onoff' &&
             $_REQUEST['os_cck_search_'.$field['db_field_name']] == 'on'){ //checkbox
                $comp_search_and[] = "LOWER(entcont." .$field['db_field_name']." != '')";
            }
            elseif($field['field_type'] == 'text_select_list' && 
              $_REQUEST['os_cck_search_'.$field['db_field_name']] != 'all' 
              && $_REQUEST['os_cck_search_'.$field['db_field_name']] != '0' ){//list field
                $comp_search_and[] = "LOWER(entcont." .$field['db_field_name']. ") LIKE ".
                  $db->Quote(protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'],''));

               

            }
            elseif($field['field_type'] == 'text_radio_buttons'){//radioButton field
                $comp_search_and[] = "LOWER(entcont." .$field['db_field_name']. ") LIKE ".
                  $db->Quote(protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'],''));
            }
            elseif($field['field_type'] == 'decimal_textfield' 
              && isset($_REQUEST['os_cck_search_'.$field['db_field_name'].'_from']) 
              && isset($_REQUEST['os_cck_search_'.$field['db_field_name'].'_to'])){

              //print_r($multiple);exit;//NumberField field
                $price_from = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_from','')*1;//may be necessary to improve the int number test later
                $price_to = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_to','')*1;//

                if($price_to){

                  $comp_search_and[] = "(LOWER(entcont." .$field['db_field_name']. ")) BETWEEN ".($price_from)
                  .' AND '.($price_to);

                }
            }

            elseif($field['field_type'] == 'datetime_popup' && 
              (isset($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventfrom']))){//date/time field


                if(isset($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventto'])){

                  if(!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventfrom']) 
                      && empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventto'])){
                    $range_date_from = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_range_eventfrom','');
                    $range_date_from = date_create($range_date_from)->Format('Y-m-d H:i:s');

                    $comp_search_and[] = "entcont." .$field['db_field_name']. " BETWEEN '".($range_date_from)
                  ."' AND  '2067-01-01 00:00:00'";

                    print_r($comp_search_and);

                  }

                  if(empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventfrom']) 
                      && !empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventto'])){
                    $range_date_to = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_range_eventto','');
                    $range_date_to = date_create($range_date_to)->Format('Y-m-d')." 23:59:59";

                    $comp_search_and[] = "entcont." .$field['db_field_name']. " BETWEEN '1967-01-01 00:00:00'". 
                    " AND  '".($range_date_to)."'";
                  }

                  if(!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventfrom']) 
                      && !empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventto'])){
                    $range_date_from = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_range_eventfrom','');
                    $range_date_to = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_range_eventto','');
                    $range_date_from = date_create($range_date_from)->Format('Y-m-d H:i:s');
                    $range_date_to = date_create($range_date_to)->Format('Y-m-d H:i:s');

                    $comp_search_and[] = "entcont." .$field['db_field_name']. " BETWEEN '".($range_date_from)
                    ."' AND  '".($range_date_to)."'";
                  }

                }else{

                  if(!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_range_eventfrom'])){
                    $range_date_from = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_range_eventfrom','');
                    $range_date_from = date_create($range_date_from)->Format('Y-m-d');

                    $comp_search_and[] = "entcont." .$field['db_field_name']. " LIKE '%".($range_date_from)
                    ."%'";
                  }

                }

            }

            elseif($field['field_type'] == 'datetime_popup' && 
              (!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_dateto']) || 
              !empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_datefrom']))){//date/time field

                if(!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_datefrom']))
                {
                  $date_from = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_datefrom','');
                }
                 
                if(!empty($_REQUEST['os_cck_search_'.$field['db_field_name'].'_dateto'])){
                    $date_to = protectInjectionWithoutQuote('os_cck_search_'.$field['db_field_name'].'_dateto','');
                }

            }else
                $comp_search[] = "LOWER(entcont." .$field['db_field_name']. ") LIKE '%$search%'";//not multiple field
        }
      }


      ////////creating database search request
      if($catid[0]!=0){
          $k=0;
          $addwhere='';
          foreach($catid as $item){
            $k++;
            if($k==1)$addwhere.='(';
            if($k < count($catid))
                $addwhere.="categ.cid=".$item." OR ";
            else
                $addwhere.="categ.cid=".$item.")";
          }
          array_push($where, $addwhere);
          
      }
      
      array_push($where, "c.published='1'");
      array_push($where, "c.approved='1'");

      array_push($where, "(categ.published IS NULL OR categ.published ='1')");

      $comp_search_str = implode(' OR ', $comp_search);
      if ($comp_search_str != '') {
          array_push($where, '(' . $comp_search_str . ')');
      }
      $comp_search_str_and = implode(' AND ', $comp_search_and);
      if ($comp_search_str_and != '') {
          array_push($where, '(' . $comp_search_str_and . ')');
      }
      array_push($where, "c.fk_eid=".$entity_id);
      array_push($where, "lay.type = 'add_instance'");
      $search_date_from = '';
      $search_date_until = '';
      if(isset($date_from)) $search_date_from = date_create($date_from)->Format('Y-m-d');
      if(isset($date_to)) $search_date_until = date_create($date_to)->Format('Y-m-d');
      $RentSQL = '';

      if($os_cck_configuration->get('rent_type') || $os_cck_configuration->get('by_time')){
          $sign = '=';      
      }else{
          $sign = '';
      }  

      $RentSQL = " entcont.fk_eiid NOT IN (select dd.fk_eiid from #__os_cck_rent AS dd
      where ((dd.rent_until >".$sign." '" . $search_date_from . "' and dd.rent_from <= '" . $search_date_from . "') or " .
          " (dd.rent_from <".$sign." '" . $search_date_until . "' and dd.rent_until >= '" . $search_date_until . "' ) or " .
          " (dd.rent_from >".$sign."'" . $search_date_from . "' and dd.rent_until <".$sign." '" . $search_date_until . "'))  AND dd.rent_return IS NULL) ";
  

      if (trim($RentSQL) != "")
          array_push($where, $RentSQL);

      //query for paginator limit
      $query = "SELECT COUNT(DISTINCT c.eiid) FROM #__os_cck_entity_instance as c "//items table
        . "\n LEFT JOIN #__os_cck_content_entity_".$entity_id." AS entcont ON c.eiid = entcont.fk_eiid"//table for text field
        . "\n LEFT JOIN #__os_cck_categories_connect AS catcon ON c.eiid = catcon.fk_eiid"//need for conect entity and categories tables
        
        . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid = c.fk_lid"//need for conect entity and categories tables
        .$multiple_query
        . "\n LEFT JOIN #__os_cck_categories AS categ ON catcon.fk_cid = categ.cid"//category table
        .((count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));
        // print_r($query);exit;
      $db->setQuery($query);
      $total = $db->loadResult();
      if($max_items != '0' && $max_items < $total ) {
          $total = $max_items;
      }
      $pageNav = new JPagination($total, $limitstart, $limit);


      //serch query
      $query = "SELECT distinct c.eiid,catcon.fk_cid FROM #__os_cck_entity_instance as c "
        . "\n LEFT JOIN #__os_cck_content_entity_".$entity_id." AS entcont ON c.eiid = entcont.fk_eiid"//table for text field
        . "\n LEFT JOIN #__os_cck_categories_connect AS catcon ON c.eiid = catcon.fk_eiid"//need for conect entity and categories tables
        . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid = c.fk_lid"//need for conect entity and categories tables
        . $multiple_query
        . "\n LEFT JOIN #__os_cck_categories AS categ ON catcon.fk_cid = categ.cid"//category table
        // .$RentSQL_JOIN_1 . $RentSQL_JOIN_2 
        . ((count($where) ? "\nWHERE " . implode(' AND ', $where) : ""))
        . "\n GROUP BY c.eiid LIMIT $pageNav->limitstart, $pageNav->limit ";

      $db->setQuery($query);
      $items = $db->loadObjectList();


      foreach ($items as $key => $item) {
        $instance = new os_cckEntityInstance($db);
        $instance->load($item->eiid);
        $instancies[$key] = $instance;
        $instancies[$key]->cat_id = $item->fk_cid;
      }
      ////////end search request


      if (!isset($instancies[0])) {
          print_r("<h1 >".JText::_("COM_OS_CCK_LABEL_SEARCH_NOTHING_FOUND") . " </h1><br><br> ");
          return;
      }
      $layout_params = unserialize($layout->params);
      $layout_params['all_instance_layout_params'] = $fields_from_params;
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
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

      $type =  'all_instance';

      require getLayoutPathCCK::getLayoutPathCom($option, $type);
    }

    static function showItem($option, $eiid, $catid, $lid = 0){


      global $app, $db, $user, $Itemid, $os_cck_configuration,$moduleId,$limitstart, $doc;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $moduleId = (($moduleId == 0 || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
      if(!$moduleId){
        // Params(cck component menu)
        $menu = new JTableMenu($db);
        $menu->load($Itemid);
        $params = new JRegistry;
        $params->loadString($menu->params);
      }else{
        $mod_row = JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
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
      }
      $session = JFactory::getSession();
      $nextInstId = 0;
      $prevInstId = 0;
     
      $queryItemIds = $session->get("queryItemIds");
      $category_layout = new os_cckLayout($db);
      $all_instance_layout = new os_cckLayout($db);
      $layout = new os_cckLayout($db);
      $entityInstance = new os_cckEntityInstance($db);
      $search_layout = new os_cckLayout($db);

      if(!$lid){


        //if we set menu all_cat(category_layout layout take from all_cat layout,
        //entity_layout and entity_type from category layout(set in the backend))
        //if we have a module then params takes from module
        if($params->get('allcategories_layout') || $params->get('layout_type') == 'all_categories'){
          $all_category_layout = new os_cckLayout($db);
          $all_category_layout->load($params->get('layout')?$params->get('layout') : $params->get('allcategories_layout'));//(expression ? true_value : false_value)
          $fields_from_params = unserialize($all_category_layout->params);
          $entity_id = $all_category_layout->fk_eid;
          //if we set default category lyout
          if($fields_from_params['views']['category_layout'] == 1){
            if($category_layout->getDefaultLayout($entity_id, 'category')){
                $category_layout->load(intval($category_layout->getDefaultLayout($entity_id, 'category')));
                $fields_from_params = unserialize($category_layout->params);
            }
          }else{
            $category_layout->load($fields_from_params['views']['category_layout']);
            $fields_from_params = unserialize($category_layout->params);
          }
          //if we set default items lyout
          if($fields_from_params['views']['item_layout'] == 1){
            if($layout->getDefaultLayout($entity_id, 'instance')){
              $layout->load(intval($layout->getDefaultLayout($entity_id, 'instance')));
            }else{
              JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
              return;
            }
          }else{
              $layout->load($fields_from_params['views']['item_layout']);
          }
        }//end all_cat menu

        //if we display category menu
        if($params->get('category_layout') || $params->get('layout_type') == 'category'){
          $category_layout->load($params->get('layout')?$params->get('layout') : $params->get('category_layout'));
          $fields_from_params = unserialize($category_layout->params);
          $entity_id = $category_layout->fk_eid;
          if($fields_from_params['views']['item_layout'] == 1){
            if($layout->getDefaultLayout($entity_id, 'instance')){
              $layout->load(intval($layout->getDefaultLayout($entity_id, 'instance')));
            }else{
              JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
              return;
            }
          }else{
            $layout->load($fields_from_params['views']['item_layout']);
          }
        }//end category menu

        //if we display category with map menu


        if($params->get('all_instance_layout')){
          $all_instance_layout->load($params->get('all_instance_layout'));
          $fields_from_params = unserialize($all_instance_layout->params);
          $entity_id = $all_instance_layout->fk_eid;
          if($fields_from_params['views']['item_layout'] == 1){
            if($layout->getDefaultLayout($entity_id, 'instance')){
              $layout->load(intval($layout->getDefaultLayout($entity_id, 'instance')));
            }else{
              JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
              return;
            }
          }else{
            $layout->load($fields_from_params['views']['item_layout']);
          }
        }//end category with map menu

        //if we display one item menu
        if($params->get('instance_layout') || $params->get('layout_type') == 'instance'){
          $layout->load($params->get('layout')?$params->get('layout') : $params->get('instance_layout'));
          if(!$layout->fk_eid || !$layout->published){
            JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_ENTITY_LAYOUT"));
            return;
          }

          $entity_id = $layout->fk_eid;
          $entityInstance->load($params->get('instance'));
            if(!$entityInstance->eiid){
              JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_INSTANCE"));
              return;
            }
        }else{



          $fields_from_params = unserialize($layout->params);

          if(isset($layout_params['fields']['cck_instance_navigation_published']) 
        && $layout_params['fields']['cck_instance_navigation_published']){
            //query for pagination//itemindex -- index number of instance in cat-instances array
            $andCID = '';


            if($catid > 0)
              $andCID = "AND cc.fk_cid=$catid";
            $query = "SELECT eiid FROM #__os_cck_entity_instance as i "
                    ."\n LEFT JOIN #__os_cck_layout as l ON l.lid = i.fk_lid "
                    ."\n LEFT JOIN #__os_cck_categories_connect as cc ON cc.fk_eiid = i.eiid "
                    ."\n WHERE l.type ='add_instance' ".$andCID." AND i.published=1 "
                    ."\n AND i.approved=1   AND i.eiid > ".intval($eiid)
                    ."\n ORDER BY eiid LIMIT 1";
            $db->setQuery($query);
            $nextInstId = $db->loadResult();
            
            $query = "SELECT eiid FROM #__os_cck_entity_instance as i "
                    ."\n LEFT JOIN #__os_cck_layout as l ON l.lid = i.fk_lid "
                    ."\n LEFT JOIN #__os_cck_categories_connect as cc ON cc.fk_eiid = i.eiid "
                    ."\n WHERE l.type ='add_instance' ".$andCID." AND i.published=1 "
                    ."\n AND i.approved=1   AND i.eiid < ".intval($eiid)
                    ."\n ORDER BY eiid LIMIT 1";
            $db->setQuery($query);
            $prevInstId = $db->loadResult();



            if(isset($_REQUEST['next']) && $_REQUEST['next'] > 0 && $nextInstId){
              $entityInstance->load(intval($nextInstId));
            }else if(isset($_REQUEST['prev']) && $_REQUEST['prev'] > 0 && $prevInstId){
              $entityInstance->load(intval($prevInstId));
            }else{
              $entityInstance->load(intval($eiid));
            }
          }
        }//end item menu
        //if we display search menu
        if($params->get('search_layout') || $params->get('layout_type') == 'search'){
          $search_layout->load($params->get('layout')?$params->get('layout') : $params->get('search_layout'));
          $entity_id = $search_layout->fk_eid;
          $fields_from_params = unserialize($search_layout->params);
          if($fields_from_params['views']['all_instance_layout'] == 1){//we set default lyout
            if($category_layout->getDefaultLayout($entity_id, 'category')){
              $category_layout->load(intval($category_layout->getDefaultLayout($entity_id, 'category')));
              $fields_from_params = unserialize($category_layout->params);
            }else{
              JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_CATEGORY_LAYOUT"));
              return;
            }
          }else{
            $category_layout->load($fields_from_params['views']['all_instance_layout']);
            $fields_from_params = unserialize($category_layout->params);
          }
          if($fields_from_params['views']['item_layout'] == 1){
            if($layout->getDefaultLayout($entity_id, 'instance')){
              $layout->load(intval($layout->getDefaultLayout($entity_id, 'instance')));
            }else{
              JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
              return;
            }
          }else{
            $layout->load($fields_from_params['views']['item_layout']);
          }
        }//end search menu


      }else{


        $layout->load($lid);
        $fields_from_params = unserialize($layout->params);
        // if(isset($fields_from_params['views']['show_navigation'])){
          //query for pagination//itemindex -- index number of instance in cat-instances array
          $andCID = '';
          if($catid > 0)


          $andCID = "AND cc.fk_cid=$catid";
          $query = "SELECT eiid FROM #__os_cck_entity_instance as i "
                  ."\n LEFT JOIN #__os_cck_layout as l ON l.lid = i.fk_lid "
                  ."\n LEFT JOIN #__os_cck_categories_connect as cc ON cc.fk_eiid = i.eiid "
                  ."\n WHERE l.type ='add_instance' ".$andCID." AND i.published=1 "
                  ."\n AND i.approved=1   AND i.eiid > ".intval($eiid)
                    ."\n ORDER BY eiid LIMIT 1";
          $db->setQuery($query);
          $nextInstId = $db->loadResult();

          $query = "SELECT eiid FROM #__os_cck_entity_instance as i "
                  ."\n LEFT JOIN #__os_cck_layout as l ON l.lid = i.fk_lid "
                  ."\n LEFT JOIN #__os_cck_categories_connect as cc ON cc.fk_eiid = i.eiid "
                  ."\n WHERE l.type ='add_instance' ".$andCID." AND i.published=1 "
                  ."\n AND i.approved=1   AND i.eiid < ".intval($eiid)
                    ."\n ORDER BY eiid LIMIT 1";
          $db->setQuery($query);
          $prevInstId = $db->loadResult();


          if(isset($_REQUEST['next']) && $_REQUEST['next'] > 0 && $nextInstId){
            $entityInstance->load(intval($nextInstId));
          }else if(isset($_REQUEST['prev']) && $_REQUEST['prev'] > 0 && $prevInstId){
            $entityInstance->load(intval($prevInstId));
          }else{
            $entityInstance->load(intval($eiid));
          }
        // }else{
        //   $entityInstance->load(intval($eiid));
        // }

        //features
        if($entityInstance->featured_clicks == 0){
          //return;
        }else{
          $featured_clicks = 0;
          if($entityInstance->featured_clicks != 0 && $entityInstance->featured_clicks != ''){
            $featured_clicks = 1;
          }
          $query = "UPDATE #__os_cck_entity_instance SET "
                    ."\n featured_clicks = featured_clicks-".$featured_clicks
                    ."\n WHERE eiid=".$entityInstance->eiid;
          $db->setQuery($query);
          $db->query();
        }
      }


      


      if(!$layout->title || $layout->type != 'instance'){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_INSTANCE"));
        return;
      }
      if (!$entityInstance->eiid){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_INSTANCE") );
        return;
      }
      if(!$entityInstance->published || !$entityInstance->approved){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_INSTANCE_ERROR_APPROVE_PUBLISH") );
        return;
      }
      $header = $category_layout->title;
      $params->def('header', $header);
      $params->def('pageclass_sfx', '');
      if ($catid > 0) {
        $query = "SELECT * FROM #__os_cck_categories WHERE cid='{$catid}'";
        $db->setQuery($query);
        $category = $db->loadObjectList();
        //cck_constructPathway($category[0]);
        $pathway = JRoute::_('index.php?option=' . $option . '&task=showCategory&catid=' . 
          $category[0]->cid . '&Itemid=' . $Itemid);
        $pathway_name = $category[0]->name;
        $app->getPathway()->addItem($entityInstance->title, $pathway);

      } else {


        $query = "SELECT * FROM #__os_cck_categories AS cc "
                . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON cc.cid=ccc.fk_cid "
                . "\n WHERE ccc.fk_eiid=" . $entityInstance->eiid . ""
                . "\n LIMIT 0,1";
        $db->setQuery($query);
        $category = $db->loadObjectList();
        $app->getPathway()->addItem($entityInstance->title, $entityInstance->title);

      }
      if($category)
        $category = $category[0];

      //Record the hit
      $sql = "UPDATE #__os_cck_entity_instance SET hits = hits + 1 WHERE eiid = " . $entityInstance->eiid . "";
      $db->setQuery($sql);
      $db->query();
      $params->def('pageclass_sfx', '');
      $params->def('item_description', 1);
      $params->def('back_button', $app->getCfg('back_button'));
      $currentcat = new stdclass();
      $currentcat->header = $params->get('header');
      $currentcat->header = $currentcat->header . ": " . $entityInstance->title;
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
      $layout_params = unserialize($layout->params);
      $layout_params['custom_fields'] = unserialize($layout->custom_fields);
      $layout_params['nextInstId'] = $nextInstId;
      $layout_params['prevInstId'] = $prevInstId;

      $layout_params['has_price'] = 0;
      $layout->field_list = $entityInstance->getFields();
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
      //features
      if($entityInstance->featured_shows == 0){
        //return;
      }else{
        $featured_shows = 0;
        if($entityInstance->featured_shows != 0 && $entityInstance->featured_shows != ''){
          $featured_shows = 1;
        }
        $query = "UPDATE #__os_cck_entity_instance SET "
                  ."\n featured_shows = featured_shows-".$featured_shows
                  ."\n WHERE eiid=".$entityInstance->eiid;
        $db->setQuery($query);
        $db->query();
      }

      //features
      $type = 'instance';

      //check access for category

      if(isset($category) && !empty($category)){ //is not isset category (skip access checking)

        $db->setQuery("SELECT * FROM `#__os_cck_categories` e WHERE e.`cid` = {$category->cid}");
        $carCatParams =$db->loadObjectList();

        if(!$carCatParams){
          JError::raiseWarning(0, JText::_("COM_OS_CCK_ACCESS_CATEGORY"));
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
      //check access for category

      require getLayoutPathCCK::getLayoutPathCom($option,$type);
    }

    static function showInstanceManager($instance_type){

      global $option,$os_cck_configuration, $Itemid, $moduleId, $db, $app, $templateDir, $user;
      $session = JFactory::getSession();
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $moduleId = (($moduleId == 0 || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
      if(!$moduleId){
        // Params(cck component menu)
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($db);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
        }else{
          $app    = JFactory::getApplication();
          $menu   = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $Itemid );
        }//end
      }else{
        $mod_row = JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
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
      }
      $instanceManagerAccess = $params->get('instance_manager_access','');

      $show_type = $params->get('show_type','1');//1-own//2-all
      $user = JFactory::getUser();

      if (!checkAccess_cck($instanceManagerAccess, $user->groups)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ACCESS_INSTANCE_MANAGER"));
        return;
      }
      if($show_type == 1 && !$user->id){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_LOGIN_FIRST"));
        return;
      }

      //ordering
      $item_sort_param = protectInjectionWithoutQuote('sort', 'jei.eiid');
      if(is_array($sort_arr = $session->get('eq_itemsort', ''))){
        if(protectInjectionWithoutQuote('sorting_direction','')){
          if(protectInjectionWithoutQuote('sorting_direction')=="ASC"){
            $sort_arr['sorting_direction'] = "DESC";
          }else{
            $sort_arr['sorting_direction'] = "ASC";
          }
        }elseif($session->get('sorting_direction','')){ 
          $sort_arr['sorting_direction'] = $session->get('sorting_direction');
        }else{
          $sort_arr['sorting_direction']="ASC";
        }
        if($item_sort_param == $sort_arr['field']){
        }else if($item_sort_param == "jei.eiid"){
          $sort_arr['field'] = $item_sort_param;
        }

        if($item_sort_param == 'inst_entity'){
          $sort_string = 'jei.fk_eid' . " " . $sort_arr['sorting_direction'];
        }else if($item_sort_param == 'inst_id'){
          $sort_string = 'jei.eiid' . " " . $sort_arr['sorting_direction'];
        }else if($item_sort_param == "jei.eiid"){
          $sort_string = $sort_arr['field'] . " " . $sort_arr['sorting_direction'];
        }
      }else{
        if(protectInjectionWithoutQuote('sorting_direction','')){
          $sort_arr['sorting_direction'] = protectInjectionWithoutQuote('sorting_direction');
        }elseif($session->get('sorting_direction','')){ 
          $sort_arr['sorting_direction'] = $session->get('sorting_direction');
        }else{
          if(!isset($sort_arr) || !is_array($sort_arr)) $sort_arr = array();
          $sort_arr['sorting_direction']="ASC";
        }

        if($item_sort_param == 'inst_entity'){
          $sort_string = 'jei.fk_eid'. " " . $sort_arr['sorting_direction'];
        }else if($item_sort_param == 'inst_id'. " " . $sort_arr['sorting_direction']){
          $sort_string = 'jei.eiid'. " " . $sort_arr['sorting_direction'];
        }else if($item_sort_param == "jei.eiid"){
          $sort_string = $item_sort_param . " " . $sort_arr['sorting_direction'];
        }
        $sort_arr['field'] = $item_sort_param;
      }
      $session->set('eq_itemsort', $sort_arr);

      //end

      $where = $where2 = array();
      $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', 10);
      $limitstart = $app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
      $search = $app->getUserStateFromRequest("search{$option}", 'search', '');
      $pub = $app->getUserStateFromRequest("pub{$option}", 'pub', '');
      $catid = $app->getUserStateFromRequest("catid{$option}", 'catid', '');
      $entity_id = $app->getUserStateFromRequest("entity_id{$option}", 'entity_id', '');
      $owner = $app->getUserStateFromRequest("owner_id{$option}", 'owner_id', '');
      $entity_list = implode(',',$params->get('entity_list',array()));

    
      $ent_query = "SELECT eid FROM #__os_cck_entity";
      $db->setQuery($ent_query);
      $exists_entities = $db->loadColumn();
      $temp_param = $params->get('entity_list',array()) ;
      if( !empty( $temp_param ) ){
        foreach ($params->get('entity_list',array()) as $entity_id) { 
          if(!in_array($entity_id, $exists_entities)){

            JFactory::getApplication()->enqueueMessage('The entity with the id '.$entity_id.' is removed, we recommend that you delete it from the Instance Manager menu.', 'error');
          }
        }
      }


      if(empty($entity_list)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_ENTITY"));
        return;
      }
      if ($pub == "pub") {
        array_push($where, "jei.published = 1");
      } else if ($pub == "not_pub") {
        array_push($where, "jei.published = 0");
      }
      if ($catid > 0) {
        array_push($where, "c.fk_cid='$catid'");
      }

      if($owner > 0){
        array_push($where, "jei.fk_userid='$owner'");
      }
      if ($entity_id != '') {
        array_push($where, "jei.fk_eid ='{$entity_id}'");
      }else{
        array_push($where, "jei.fk_eid IN ({$entity_list})");
      }
      array_push($where, "cl.type = '".$instance_type."'");

      //create some special condition for instance/rent/buy select
      if($instance_type == 'add_instance' && $show_type == 1){
        array_push($where, "jei.fk_userid = '".$user->id."'");
      }else if(($instance_type == 'rent_request_instance' || $instance_type == 'buy_request_instance')
                && $show_type == 1){
        $subquery = "SELECT jei2.eiid FROM #__os_cck_entity_instance as jei2".
                    "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei2.fk_lid ".
                    "\n WHERE cl.type = 'add_instance' AND jei2.fk_eid IN ({$entity_list})".
                    "\n AND jei2.fk_userid=".$user->id;
                    $db->setQuery($subquery);
        array_push($where, "conn.fid_parent IN (".$subquery.") ");
      }
      //end condition
      //get instances // buy request = lay.type->buy_request_... in instances 
                      // rent request = lay.type->rent_request_... in instances
      //query for pagination
      $selectstring = "SELECT count(DISTINCT jei.eiid)".
        "\n FROM #__os_cck_entity_instance AS jei" .
        "\n LEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
        "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid " .
        "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ";

        if($search || protectInjectionWithoutQuote('sort','')){
          $fieldNames = $session->get('field_names');
          foreach ($fieldNames as $value){
            foreach ($value['fields'] as $name){
              if($value['field_type'] == 'categoryfield' && $name == protectInjectionWithoutQuote('sort','')){
                $sort_string = 'cc.title'. " " . $sort_arr['sorting_direction'];
                continue;
              }
              array_push($where2, '#__os_cck_content_entity_'.$value['ent_name'].'.'.$name." LIKE '%$search%' ");
            }
            $selectstring .= "\nLEFT JOIN #__os_cck_content_entity_".$value['ent_name'].
              " ON #__os_cck_content_entity_".$value['ent_name'].".fk_eiid = jei.eiid ";
          }
          array_push($where2, "jei.eiid LIKE '%$search%' ");
        }

      $selectstring .= "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
        "\n LEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = jei.eiid  and l.rent_return is null " .
        "\n LEFT JOIN #__os_cck_child_parent_connect AS conn ON jei.eiid = conn.fid_child " .
        "\n LEFT JOIN #__users AS u ON u.id = jei.checked_out ".
        (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");
        
        $db->setQuery($selectstring);
        if($search){
          $selectstring .=  (count($where2) ? "\nAND (" . implode(' OR ', $where2).')' : "");
        }

      $db->setQuery($selectstring);

      $total = $db->loadResult();

      //end
      //pagination
      $pageNav = new JPagination($total, $limitstart, $limit);
      //end
      $selectstring = "SELECT jei.*, GROUP_CONCAT(DISTINCT cc.title SEPARATOR ', ') AS category, ce.name AS entity,".
        "\n cl.params as lay_params, u.name AS editor, l.rent_from as rent_from".
        "\n FROM #__os_cck_entity_instance AS jei" .
        "\n LEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
        "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid " .
        "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = jei.fk_eid ";

        if($search || protectInjectionWithoutQuote('sort','')){
          $fieldNames = $session->get('field_names');
          foreach ($fieldNames as $value){
            foreach ($value['fields'] as $name){
              if($value['field_type'] == 'categoryfield' && $name == protectInjectionWithoutQuote('sort','')){
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
            $selectstring .= "\nLEFT JOIN #__os_cck_content_entity_".$value['ent_name'].
              " ON #__os_cck_content_entity_".$value['ent_name'].".fk_eiid = jei.eiid ";
          }
          array_push($where2, "jei.eiid LIKE '%$search%' ");
        }

      $selectstring .= "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = jei.fk_lid ".
        "\n LEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = jei.eiid  and l.rent_return is null " .
        "\n LEFT JOIN #__os_cck_child_parent_connect AS conn ON jei.eiid = conn.fid_child " .
        "\n LEFT JOIN #__users AS u ON u.id = jei.checked_out ".
        (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");
        
        $db->setQuery($selectstring);
        if($search){
          $selectstring .=  (count($where2) ? "\nAND (" . implode(' OR ', $where2).')' : "");
        }

      $selectstring .= "\n GROUP BY jei.eiid ".
      "\n ORDER BY $sort_string " .
      "\n LIMIT $pageNav->limitstart,$pageNav->limit;";

      $db->setQuery($selectstring);
      $instancies = $db->loadObjectList();

      if(count($instancies)>0){
          $date = strtotime(JFactory::getDate()->toSql());
          foreach ($instancies as $row) {
            $check = strtotime($row->checked_out_time);
            $remain = 7200 - ($date - $check);
            if (($remain <= 0) && ($row->checked_out != 0)) {
                $db->setQuery("UPDATE #__os_cck_entity_instance SET checked_out=0,checked_out_time=0");
                $db->query();
                $row->checked_out = 0;
                $row->checked_out_time = 0;
            }
        }
      }
      //get show in instance field

      $show_fields = $fieldNames = $entityEaaray = array();
      if(count($instancies)>0){
        foreach ($instancies as $instance) {
          $entityEaaray[] = $instance->fk_eid;
          $layoutArray[] = $instance->fk_lid;
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
            if($Fieldvalue->show_in_instance_menu ){
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
      //end getting field
      //end getting instance
      $layout_params = array();
      //category list start 
      $categories[] = JHTML::_('select.option','-1', JText::_('COM_OS_CCK_LABEL_SELECT_ALL_CATEGORIES'),'value','text');
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
        }else{
          if($item->cid != $cat->cid){
            $options[] = JHTML::_('select.option',$item->cid, $item->title,'value','text');
          } else {
            $this_treename = "$item->title/";
          }
        }
      }
      $clist = JHTML::_('select.genericlist',$options, 'catid', 
        'class="inputbox" size="1" onchange="document.instance_manager_search.submit();"', 'value', 
        'text', $catid); //new nik edit
      //end
      //publist
      $pubmenu[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_LABEL_SELECT_TO_PUBLIC'),'value','text');
      $pubmenu[] = JHTML::_('select.option','not_pub', JText::_('COM_OS_CCK_LABEL_SELECT_NOT_PUBLIC'),'value','text');
      $pubmenu[] = JHTML::_('select.option','pub', JText::_('COM_OS_CCK_LABEL_SELECT_PUBLIC'),'value','text');
      $publist = JHTML::_('select.genericlist',$pubmenu, 'pub', 
        'class="inputbox" size="1" onchange="document.instance_manager_search.submit();"', 'value', 'text', $pub);
      //end
      //userlist
      $usermenu = '';
      if($show_type == 2){
        $userlist[] = JHTML::_('select.option','-1', 'Select User');
        $db->setQuery("SELECT DISTINCT id AS value, name AS text from #__users ORDER BY name");
        $userlist = array_merge($userlist, $db->loadObjectList());
        foreach ($userlist as $value) {
            if(!$value->value)
                $value->value = $value->text;
        }
        $usermenu = JHTML::_('select.genericlist',$userlist, 'owner_id', 
          'class="inputbox input-medium" size="1" onchange="document.instance_manager_search.submit();"',
                             'value', 'text', $owner);
      }
      //end
      //entity list
      $entities = array();
      $entities[] = array('value' => '', 'text' => 'All entities');
      $query = "SELECT ent.eid AS value, ent.name AS text FROM #__os_cck_entity as ent"
                ."\n WHERE ent.eid IN ($entity_list)";
      $db->setQuery($query);
      $ent = $db->loadObjectList();
      $entities = (count($ent) > 1) ? array_merge($entities, (array)$ent) : $entities;
      if(count($ent) > 1){
        $entity_list = JHTML::_('select.genericlist',$entities, 'entity_id', 
          'class="inputbox" size="1" onchange="document.instance_manager_search.submit();"', 'value', 'text', $entity_id);
      }else{
        $entity_list = '';
      }
      //end
      if($instance_type == 'add_instance'){
        $type = 'instance_manager';
        require getLayoutPathCCK::getLayoutPathCom($option, $type);
      }else if($instance_type == 'rent_request_instance'){
        $type = 'rent_requests_manager';
        require getLayoutPathCCK::getLayoutPathCom($option, $type);
      }else if($instance_type == 'buy_request_instance'){
        $type = 'buy_requests_manager';
        require getLayoutPathCCK::getLayoutPathCom($option, $type);
      }
    }

    static function publishInstances($eiid, $publish, $option){

      global $db, $os_cck_configuration, $user, $Itemid, $app;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $catid = protectInjectionWithoutQuote('catid', array());
      if (!is_array($eiid) || count($eiid) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
      }
      $eiids = implode(',', $eiid);
      $db->setQuery("UPDATE #__os_cck_entity_instance SET published='$publish'"
                      . "\n WHERE eiid IN ($eiids) AND (checked_out=0 OR (checked_out='$user->id'))");
      if (!$db->query()) {
        echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
        exit ();
      }
      if (count($eiid) == 1) {
        $instance = new os_cckEntityInstance($db);
        $instance->checkin($eiid[0]);
      }
      $app->redirect("index.php?option=$option&task=instance_manager&Itemid=".$Itemid,'ok');
    }

    static function approveInstances($eiid, $publish, $option){
      global $db, $os_cck_configuration, $user, $Itemid, $app;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $catid = protectInjectionWithoutQuote('catid', array());
      if (!is_array($eiid) || count($eiid) < 1) {
        $action = $publish ? 'approve' : 'unapprove';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
      }
      $eiids = implode(',', $eiid);
      $db->setQuery("UPDATE #__os_cck_entity_instance SET approved='$publish'"
                      . "\n WHERE eiid IN ($eiids) AND (checked_out=0 OR (checked_out='$user->id'))");
      if (!$db->query()) {
        echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
        exit ();
      }
      if (count($eiid) == 1) {
        $instance = new os_cckEntityInstance($db);
        $instance->checkin($eiid[0]);
      }
      $app->redirect("index.php?option=$option&task=instance_manager&Itemid=".$Itemid,'ok');
    }

    static function remove_buy_request_instance($eiids, $option){
      global $db, $os_cck_configuration, $Itemid, $app;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      if (!is_array($eiids) || count($eiids) < 1) {
        echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
        exit;
      }
      if (count($eiids)) {
        foreach ($eiids as $eiid) {
          $instance = new os_cckEntityInstance($db);
          $instance->load($eiid);
          $instance->delete();
        }
      }
      $app->redirect("index.php?option=$option&task=show_buy_request_instances&Itemid=".$Itemid,'ok');
    }

    static function editRentRequestInstance($option, $eiid){
      global $db, $os_cck_configuration, $user, $app, $Itemid;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $select_type = protectInjectionWithoutQuote('lay_type','');
      $entityInstance = new os_cckEntityInstance($db);
      $entityInstance->load(intval($eiid));
      if(!$entityInstance->eiid){
        return;
      }
      $extra_fields_list = $entityInstance->getFields();
      $query = "SELECT fid_parent FROM #__os_cck_child_parent_connect WHERE fid_child = $entityInstance->eiid";
      $db->setQuery($query);
      $parent_instance = $db->loadResult();
      
      $layout = new os_cckLayout($db);
      if(empty($parent_instance)){
        if($layout->getDefaultLayout($entityInstance->fk_eid, 'instance')){
          $layout->load(intval($layout->getDefaultLayout($entityInstance->fk_eid, 'instance')));
        }else{
          JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
          return;   
        }
      }else{
        $layout->load(intval($entityInstance->fk_lid));
      }
      
     $layout_params = unserialize($layout->params);

      $str_list = array();
      $str_list['layout_params'] = $layout_params;
      $str_list['layout'] = $layout;
      $str_list['parent_instance'] = $parent_instance;
      $str_list['extra_fields_list'] = $extra_fields_list;
      //print_r($entityInstance);exit;
      //AdminViewRent_request :: editRentRequestInstance($option, $entityInstance, $str_list);
      $type = 'edit_rent_request';
      require getLayoutPathCCK::getLayoutPathCom($option, $type);
    }

    static function editBuyRequestInstance($option, $eiid){
      global $db, $os_cck_configuration, $user, $app, $Itemid;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $select_type = protectInjectionWithoutQuote('lay_type','');
      $entityInstance = new os_cckEntityInstance($db);
      $entityInstance->load(intval($eiid));
      if(!$entityInstance->eiid){
        return;
      }
      $extra_fields_list = $entityInstance->getFields();
      $query = "SELECT fid_parent FROM #__os_cck_child_parent_connect WHERE fid_child = $entityInstance->eiid";
      $db->setQuery($query);
      $parent_instance = $db->loadResult();
      
      $layout = new os_cckLayout($db);
      if(empty($parent_instance)){
        if($layout->getDefaultLayout($entityInstance->fk_eid, 'instance')){
          $layout->load(intval($layout->getDefaultLayout($entityInstance->fk_eid, 'instance')));
        }else{
          JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY_LAYOUT"));
          return;   
        }
      }else{
        $layout->load(intval($entityInstance->fk_lid));
      }
      
      $layout_params = unserialize($layout->params);

      $str_list = array();
      $str_list['layout_params'] = $layout_params;
      $str_list['layout'] = $layout;
      $str_list['parent_instance'] = $parent_instance;
      $str_list['extra_fields_list'] = $extra_fields_list;
      //print_r($entityInstance);exit;
      //AdminViewRent_request :: editRentRequestInstance($option, $entityInstance, $str_list);
      $type = 'edit_buy_request';
      require getLayoutPathCCK::getLayoutPathCom($option, $type);
    }

    static function editInstance($option, $eiid){
      global $db, $os_cck_configuration, $user, $app, $Itemid, $moduleId;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      $session = JFactory::getSession();
      $select_type = protectInjectionWithoutQuote('lay_type','');
      $entityInstance = new os_cckEntityInstance($db);
      $entityInstance->load(intval($eiid));
      $layout = new os_cckLayout($db);
      $is_new = false;
      if ($eiid === 0 || (isset($eiid[0]) && $eiid[0] == 0)) {
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


      //get layouts
      if(!$moduleId){
        // Params(cck component menu)
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($db);
          $menu->load($Itemid);
          $menu_params = new JRegistry;
          $menu_params->loadString($menu->params);
        }else{
          $app    = JFactory::getApplication();
          $menu   = $app->getMenu();
          $menu_params = new JRegistry;
          $menu_params = $menu->getParams( $Itemid );
        }//end
      }else{
        $mod_row = JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
        if (! $mod_row->load ( $moduleId )) {
          JError::raiseError ( 500, $mod_row->getError () );
        }
        //module params
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu_params = new JRegistry;
          $menu_params->loadString($mod_row->params);
        } else {
          $menu_params = new JRegistry($mod_row->params);
        }//end
      }
      $groupAccess = $menu_params->get('instance_manager_access','1');
      $show_type = $menu_params->get('show_type','1');//1-own//2-all
      $user = JFactory::getUser();
      if (!checkAccess_cck($groupAccess, $user->groups)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ACCESS_INSTANCE_MANAGER"));
        return;
      }
      $entity_list = $menu_params->get('entity_list',array());
      if(empty($entity_list)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_ENTITY"));
        return;
      }
      $entity_list = implode(',', $entity_list);
      $query = "SELECT c.title,c.lid,c.custom_fields,c.type,c.params,c.fk_eid,ent.name FROM #__os_cck_layout as c"
              ."\n LEFT JOIN #__os_cck_entity as ent ON c.fk_eid = ent.eid WHERE c.type = 'add_instance' AND fk_eid IN ($entity_list) ";
      $db->setQuery($query);
      $layouts = $db->loadObjectList();

      if(!$layouts){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ADD_LAYOUT"));
        return;
      }

      // print_r($entityInstance->fk_eid);
      // exit;

      $default_layout = $layout->getDefaultLayout($entityInstance->fk_eid,'add_instance');

      $lid_array = [];
      foreach($layouts as $key => $value){
        $lid_array[] = $value->lid;
      }

      if(!in_array($select_type, $lid_array)){
        $select_type = $default_layout;
      }


      foreach($layouts as $key => $value){
        $params = unserialize($value->params);
        $params['instance_type'] = $value->type;
        $params['custom_fields'] = unserialize($value->custom_fields);
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
        $layout_type = JHTML::_('select.genericlist',$type, 'lay_type', 
          'class="inputbox" size="1" onchange="swich_task('."'edit_instance'".');lay_type_select();"',
           'value', 'text', $select_type);
      // }else{
      //   $layout_type = JHTML::_('select.genericlist',$type, 'lay_type', 
      //     'class="inputbox" size="1" disabled="disabled"', 'value', 'text', $select_type);
      // }
      //   

      // $lay_type = $select_type;

// echo $lay_type;

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
        JError::raiseWarning(0, JText::_("COM_OS_CCK_IS_EDITED"));
        return;
      }

      if (!$is_new) {
        $entityInstance->checkout($user->id);
        $entityInstance->fk_userid = $user->id;
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
        JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_ENTITY"));
        return;
      }
      if ($is_new && $ceid != 0) $entityInstance->fk_eid = $ceid;
      $extra_fields_list = $entityInstance->getFields();
      if (count($extra_fields_list) < 1) {
        JError::raiseWarning(0, JText::_("COM_OS_CCK_CREATE_FIELD"));
        return;  
      }
      $layout_params['categories_list'] = $clist;
      $layout_params['layout_type'] = $layout_type;
      $layout_params['extra_fields_list'] = $extra_fields_list;
      $layout->load($lid);
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
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

      $type = 'editInstance';
      require getLayoutPathCCK::getLayoutPathCom('com_os_cck',$type);
    }

    static function decline_rent_requests($option, $eiids){
      global $db, $os_cck_configuration, $Itemid, $app;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
      foreach ($eiids as $eiid) {
        $rent_request = new os_cckEntityInstance($db);
        $rent_request->load($eiid);
        $tmp = $rent_request->decline();
        if ($tmp != null) {
          echo "<script> alert('" . addslashes($tmp) . "'); window.history.go(-1); </script>\n";
          exit ();
        }
      }

      $app->redirect("index.php?option=$option&task=show_rent_request_instances&Itemid=".$Itemid,'ok');
    }

    static function accept_rent_requests($option, $eiids){

      
      global $db, $os_cck_configuration, $Itemid, $app;
      $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;


      foreach ($eiids as $eiid) {

        $query = "SELECT fid_parent FROM #__os_cck_child_parent_connect WHERE fid_child=$eiid";

        $db->setQuery($query);
        $parent_id = $db->loadResult();

        $rent_request = new os_cckEntityInstance($db);
        $rent_request->load($parent_id);
        $rent_request->child_id = $eiid;

        $entity = new os_cckEntity($db);
        $entity->load($rent_request->fk_eid);
        $rent_request->entity_name = $entity->name;

        $tmp = $rent_request->accept();

        if ($tmp != null) {
          echo "<script> alert('" . addslashes($tmp) . "'); window.history.go(-1); </script>\n";
          exit ();
        }
      }

      $app->redirect("index.php?option=$option&task=show_rent_request_instances&Itemid=".$Itemid,'ok');
    }

    static function  show_all_instance($option, $lid){


      global $app, $db, $user ,$doc;
      global $Itemid, $os_cck_configuration, $limit, $total, $limitstart,$moduleId;
      $Itemid = intval($_REQUEST['Itemid']);
      $moduleId =(($moduleId == 0
                   || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
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
                        . "\n AND params LIKE '%back_button%'"
                        . "\n AND params LIKE '%all_instance%'"
                        . "\n AND published = 1";
        $db->setQuery($query);
        $Itemid = $db->loadResult();
         if($params->get('ItemId'))$Itemid=$params->get('ItemId');
      }
      $layout = new os_cckLayout($db);
      $layout->load($lid);


      if(empty($layout->title) || $layout->type != 'all_instance'){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_MENU_NO_LAYOUT"));
        return;
      }
      $entity_layout = new os_cckLayout($db);
      if($params->get('all_instance_layout') || $params->get('layout') || $layout->title){
        $fields_from_params = unserialize($layout->params);
        $entity_id = $layout->fk_eid;
      }//end all instance menu


      $limit = $fields_from_params['views']['pagenator_limit'];
      if(isset($fields_from_params['views']['limit'])){
          $max_items = $fields_from_params['views']['limit'];
      }else{
          $max_items = 0;
      }

      //creating order by list

      $fields_from_params["views"]["sortField"][] = isset($fields_from_params["fields"]["indexed_all_instance_order_by"])?$fields_from_params["fields"]["indexed_all_instance_order_by"]:array();
      $entity = new os_cckEntity($db);
      $entity->load($entity_id);
      $fields_list = $entity->getFieldList();
      if(isset($fields_from_params["fields"]['order_by_fields_all_instance_order_by']) && isset($fields_from_params["views"]["sortField"][0])){
        foreach ($fields_from_params["fields"]['order_by_fields_all_instance_order_by'] as $key => $value) {
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

      $item_session = JFactory::getSession();

      //$item_session->destroy();




      if(isset($_REQUEST['order_direction']) && !empty($_REQUEST['order_direction'])){
          $fields_from_params["fields"]["sortType_all_instance_order_by"] = protectInjectionWithoutQuote('order_direction','');//need for order by desc//asc
      }elseif($item_session->get('order_direction','')){
          $fields_from_params["fields"]["sortType_all_instance_order_by"] = $item_session->get('order_direction');
      }
      if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])){
          $fields_from_params["views"]["selected"] = protectInjectionWithoutQuote('order_field','');//need for order by field name
      }elseif($item_session->get('selected','')){
          $fields_from_params["views"]["selected"] = $item_session->get('selected');
      }else
          $fields_from_params["views"]["selected"] = isset($fields_from_params["fields"]["indexed_all_instance_order_by"])?$fields_from_params["fields"]["indexed_all_instance_order_by"]:'';
          //end sort params
      $header = $layout->title;
      $params->def('header', $header);
      $params->def('pageclass_sfx', '');



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

      $event_date = (JRequest::getVar('event_date'))?JRequest::getVar('event_date'):false;
      $event_date_field = (JRequest::getVar('event_date_field'))?JRequest::getVar('event_date_field'):false;

      if($event_date_field && $event_date){
        $event_where = " AND " . $event_date_field . " LIKE '%" . $event_date . "%'";
      }else{
        $event_where = '';
      }




      if(strstr($fields_from_params["views"]["selected"], 'text_textfield') !== false){

          $query = " SELECT  DISTINCT UPPER(SUBSTRING(instance.".$fields_from_params["views"]["selected"].", 1,1)) AS symb FROM  #__os_cck_entity_instance AS ei "
              . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
              . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
              . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
              . "\n WHERE ei.published='1' "
              . "\n AND ei.approved='1' "
              . "\n AND lay.type='add_instance' "
              . "\n AND ei.fk_eid='" . $entity_id . "' $event_where GROUP BY ei.eiid ";

          $db->setQuery($query);
          $tmp_arr = $db->loadObjectList();

          if(count($tmp_arr)>1){

          $symb_list_str = '<ul>';
          foreach($tmp_arr as $symbol){
              if(empty($symbol->symb)){
                continue;
              }

              if($event_where!=''){
                $symb_list_str.= '<li><a href="index.php?option=' . $option . 
                '&view=all_instance&letindex=' . $symbol->symb . '&sp=1&Itemid=' . $Itemid . '&now_indexed=' . $fields_from_params["views"]["selected"] . '&event_date='.$event_date.'&event_date_field='.$event_date_field.'&lid='.$lid.'">' . 
                $symbol->symb . '</a></li> ';
              }else{
                $symb_list_str.= '<li><a href="index.php?option=' . $option . 
                '&view=all_instance&letindex=' . $symbol->symb . '&sp=1&Itemid=' . $Itemid . 
                  '&now_indexed=' . $fields_from_params["views"]["selected"] . '">' . 
                $symbol->symb . '</a></li> ';
              }

          }

          //check string not empty
          $temp_var =  strip_tags($symb_list_str) ;
          if(!empty( $temp_var ) ){

              if($event_where!=''){
                $symb_list_str.= '<li><a href="index.php?option=' . $option . 
                '&view=all_instance&letindex=all&sp=1&Itemid=' . $Itemid . '&now_indexed=' . $fields_from_params["views"]["selected"] . '&event_date='.$event_date.'&event_date_field='.$event_date_field.'&lid='.$lid.'">all</a></li> ';
              }else{
                $symb_list_str.= '<li><a href="index.php?option=' . $option . 
                '&view=all_instance&letindex=all&sp=1&Itemid=' . $Itemid . 
                  '&now_indexed=' . $fields_from_params["views"]["selected"] . '">all</a></li> ';
              }
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


      $query = " SELECT COUNT(ei.eiid) FROM  #__os_cck_entity_instance AS ei "
              . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
              . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
              . "\n WHERE ei.published='1' "
              . "\n AND ei.approved='1' "
              . "\n AND lay.type='add_instance' "
              . "\n AND ei.fk_eid='" . $entity_id . "' $where $event_where ";
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
          $fields_from_params["views"]["selected"] = isset($fields_from_params["fields"]["indexed_all_instance_order_by"])?$fields_from_params["fields"]["indexed_all_instance_order_by"]:'';
     }

     $pageNav = new JPagination($total, $limitstart, $limit);


     $query = " SELECT  ei.eiid,ccc.fk_cid FROM  #__os_cck_entity_instance AS ei "
              . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
              . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
              . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
              . "\n WHERE ei.published='1' "
              . "\n AND ei.approved='1' "
              . "\n AND lay.type='add_instance' "
              . "\n AND ei.fk_eid='" . $entity_id . "' $where $event_where GROUP BY ei.eiid ";

        if(isset($fields_from_params["fields"]["indexed_all_instance_order_by"])) { // if selected sortable field
          $orderby = (!empty($fields_from_params["fields"]["sortType_all_instance_order_by"])) ? $fields_from_params["fields"]["sortType_all_instance_order_by"] : 'ASC';

          if (isset($fields_from_params["views"]["selected"]) && !empty( $fields_from_params["views"]["selected"])) {
              if($fields_from_params["views"]["selected"] == 'title')
                  $query .= "ORDER BY ei.title $orderby ";
              else
                  $query .= "ORDER BY instance.`{$fields_from_params["views"]["selected"]}` $orderby ";
          }elseif($fields_from_params["fields"]["indexed_all_instance_order_by"] == 'eid') {
                  $query .= "ORDER BY ei.eiid $orderby ";
          }elseif ($fields_from_params["fields"]["indexed_all_instance_order_by"] == 'title') {
                  $query .= "ORDER BY ei.title $orderby ";
          }else { // for other fields
                  $query .= "ORDER BY instance.`{$fields_from_params["fields"]["indexed_all_instance_order_by"]}` $orderby ";
          }
        }
      $session = JFactory::getSession();
      $session->set( 'queryItemIds', $query );//need for pagination in instances//we save our query to know how to sort ourinstance//
      $query .= " LIMIT $pageNav->limitstart, $pageNav->limit ";
      $db->setQuery($query);
      $items = $db->loadObjectList();
      $instancies = array();



      foreach ($items as $key => $item) {
        
        if(isset($item->fk_cid) && !empty($item->fk_cid)){ //is not isset category (skip access checking)
        //check access for category
          $db->setQuery("SELECT * FROM `#__os_cck_categories` e WHERE e.`cid` = " . (int)$item->fk_cid);
          $carCatParams = $db->loadObjectList();

          if(!$carCatParams){
              continue;
          }

          $carCatParams = $carCatParams[0];
          //user access to category
          $user = JFactory::getUser();
          if (($carCatParams->params == implode(',',array_diff(explode(',',$carCatParams->params),$user->groups)))
              && $carCatParams->params != 1) {
              continue;
          }//end  
        }
        //check access for category

        $instance = new os_cckEntityInstance($db);
        $instance->load($item->eiid);
        $instancies[$key] = $instance;
        $instancies[$key]->cat_id = $item->fk_cid;


      }
      //////////////////////////////////////////////////////////////////
      $params->def('show_rating', 1);
      $params->def('hits', 1);
      $params->def('back_button', $app->getCfg('back_button'));
      if(!$instancies){
          echo '<div style="text-align:center"><h2>'.JText::_("COM_OS_CCK_NO_INSTANCE").'</h2></div>';
      }
      $layout_params = unserialize($layout->params);
      $layout_params['custom_fields'] = unserialize($layout->custom_fields);
      $layout_params['all_instance_layout_params'] = $fields_from_params;
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
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

      $type =  'all_instance';
      // $layout = $
      require getLayoutPathCCK::getLayoutPathCom($option, $type);
    }



    static function show_calendar($option, $lid,$showLayModId = ''){

    global $app, $db, $user ,$doc;
    global $Itemid, $os_cck_configuration, $limit, $total, $limitstart, $moduleId;

    $Itemid = intval($_REQUEST['Itemid']);
    $moduleId =(($moduleId == 0
                 || empty($moduleId)) && isset($_REQUEST['moduleId'])) ? intval($_REQUEST['moduleId']) : $moduleId;
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
                      . "\n AND params LIKE '%back_button%'"
                      . "\n AND params LIKE '%calendar%'"
                      . "\n AND published = 1";
      $db->setQuery($query);
      $Itemid = $db->loadResult();
       if($params->get('ItemId'))$Itemid=$params->get('ItemId');
    }
    $layout = new os_cckLayout($db);
    $layout->load($lid);
    if(empty($layout->title) || $layout->type != 'calendar'){
      JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_MENU_NO_LAYOUT"));
      return;
    }
    // $entity_layout = new os_cckLayout($db);

    if($params->get('calendar_layout') || $params->get('layout') || $layout->title){
      $fields_from_params = unserialize($layout->params);
      $entity_id = $layout->fk_eid;
    }
    //end all instance menu

    $session = JFactory::getSession();

    $layout_params = unserialize($layout->params);
    $layout_params['custom_fields'] = unserialize($layout->custom_fields);
    $layout_params['calendar_layout_params'] = $fields_from_params;
    $bootstrap_version = $session->get( 'bootstrap','2');
    $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);

 
    //calendar start

    //modules
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
    //modules

    $current_month = isset($fields_from_params['views']['month_calendar'])?$fields_from_params['views']['month_calendar']:date('Y-m');
    $event_title = isset($layout_params['calendar_layout_params']['fields']['calendar_table_event_title']) ? $layout_params['calendar_layout_params']['fields']['calendar_table_event_title'] : 0;
    $instance_lid = $fields_from_params['views']['instance_layout'];
    $all_instance_lid = $fields_from_params['views']['all_instance_layout'];
    $show_past_events = $fields_from_params['views']['show_past_events'];
    $event_date_field = isset($fields_from_params['views']['event_date'])?$fields_from_params['views']['event_date']:false;
    $sortType = isset($fields_from_params['fields']['sortType_all_instance_order_by'])?$fields_from_params['fields']['sortType_all_instance_order_by']:'ASC';
    $event_sort_by = $fields_from_params['views']['event_sort_by'];
    $schedule_instance_lid = $layout_params['calendar_layout_params']['fields']['calendar_table_schedule_instance_lid'];


    //check isset needs layouts and fields

    //shedule instance
    if($schedule_instance_lid == '-1'){
          $schedule_instance_lid = $layout->getDefaultLayout($entity_id, 'instance');
      if(empty($schedule_instance_lid)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_CALENDAR_INST_NO_LAYOUT"));
        return;
      }
    }
    //instance
    if($instance_lid == '-1'){
          $instance_lid = $layout->getDefaultLayout($entity_id, 'instance');
      if(empty($instance_lid)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_CALENDAR_INST_NO_LAYOUT"));
        return;
      }
    }
    //all instance
    if($all_instance_lid == '-1'){
          $all_instance_lid = $layout->getDefaultLayout($entity_id, 'all_instance');
      if(empty($all_instance_lid)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_CALENDAR_ALL_INST_NO_LAYOUT"));
        return;
      }
    }
    //data field
    if($event_date_field == '-1'){
        $event_date_field = $layout->getDefaultField($entity_id, 'datetime_popup');
      if(empty($event_date_field)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_CALENDAR_DATE_NO_FIELD"));
        return;
      }
    }
    //title field
    if($event_title == '-1'){
        $event_title = $layout->getDefaultField($entity_id, 'text_textfield');
      if(empty($event_title)){
        JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_CALENDAR_TEXT_NO_FIELD"));
        return;
      }
    }

    $query = " SELECT count(instance.".$event_date_field."), DATE(instance.".$event_date_field.") FROM  #__os_cck_entity_instance AS ei "
            . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
            . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
            . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
            . "\n WHERE ei.published='1' "
            . "\n AND ei.approved='1' "
            . "\n AND lay.type='add_instance' "
            . "\n AND instance.".$event_date_field."!='' "
            . "\n AND ei.fk_eid='" . $entity_id . "' GROUP BY DATE(instance.".$event_date_field.") ";

    $db->setQuery($query);
    $calenDate = $db->loadRowList();

    
    $calendarParams = array();
    $calendarParams['all_instance_lid'] = $all_instance_lid;
    $calendarParams['event_date_field'] = $event_date_field;
    $calendarParams['calenDate'] = $calenDate;
    $calendarParams['show_past_events'] = $show_past_events;
    $calendarParams['instance_lid'] = $instance_lid;
    $calendarParams['event_title'] = $event_title;
    $calendarParams['calendar_view'] =  $layout_params['calendar_layout_params']['fields']['calendar_view_calendar_table'];
    $calendarParams['date_format'] = $layout_params['calendar_layout_params']['fields']['calendar_table_output_format'];
    $calendarParams['show_time'] = $layout_params['calendar_layout_params']['fields']['calendar_table_show_time'];
    $calendarParams['header_format'] = $layout_params['calendar_layout_params']['fields']['calendar_table_output_header_format'];
    $calendarParams['show_header'] = $layout_params['calendar_layout_params']['fields']['calendar_table_show_header_date_time'];
    $calendarParams['schedule_instance_lid'] = $schedule_instance_lid;



    $eiids = array();
    $item_infos = array();

    if($event_sort_by == -1){
      $event_sort_by = 'TIME(time)';
    }else{
      $event_sort_by = 'instance.'.$event_sort_by;
    }

    foreach ($calenDate as $key => $value) {

      $event_where = " AND " . $event_date_field . " LIKE '%" . $value[1] . "%' ";

        $query = " SELECT  ei.eiid, instance.".$event_title." as title, instance.".$event_date_field." as time FROM  #__os_cck_entity_instance AS ei "
            . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
            . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
            . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
            . "\n WHERE ei.published='1' "
            . "\n AND ei.approved='1' "
            . "\n AND lay.type='add_instance' "
            . "\n AND ei.fk_eid='" . $entity_id . "' $event_where GROUP BY ei.eiid ORDER BY $event_sort_by $sortType ";
        $db->setQuery($query);

        $item_infos[$value[1]] = $db->loadAssocList();

    }

    //to time formnat
    foreach ($item_infos as $key => $value) {
      foreach ($value as $key1 => $value1) {
        $item_infos[$key][$key1]['time'] = date($calendarParams['date_format'],strtotime($value1['time']));
      }
    }

    $calendarParams['item_infos'] = $item_infos;


    //get instances for schedule layout
    $query = " SELECT  ei.eiid,ccc.fk_cid FROM  #__os_cck_entity_instance AS ei "
              . "\n LEFT JOIN #__os_cck_categories_connect AS ccc ON ccc.fk_eiid=ei.eiid "
              . "\n LEFT JOIN #__os_cck_layout AS lay ON lay.lid=ei.fk_lid "
              . "\n LEFT JOIN `#__os_cck_content_entity_$layout->fk_eid` as instance ON instance.`fk_eiid` = ei.eiid "
              . "\n WHERE ei.published='1' "
              . "\n AND ei.approved='1' "
              . "\n AND lay.type='add_instance' ";
    $db->setQuery($query);
    $items = $db->loadObjectList();

    $instancies = [];

    foreach ($items as $key => $item) {
      
          if(isset($item->fk_cid) && !empty($item->fk_cid)){ //is not isset category (skip access checking)
          //check access for category
            $db->setQuery("SELECT * FROM `#__os_cck_categories` e WHERE e.`cid` = " . (int)$item->fk_cid);
            $carCatParams = $db->loadObjectList();
            if(!$carCatParams){
                continue;
            }

            $carCatParams = $carCatParams[0];
            //user access to category
            $user = JFactory::getUser();
            if (($carCatParams->params == implode(',',array_diff(explode(',',$carCatParams->params),$user->groups)))
                && $carCatParams->params != 1) {
                continue;
            }//end  
          }
          //check access for category

          $instance = new os_cckEntityInstance($db);
          $instance->load($item->eiid);
          $instancies[$key] = $instance;
          $instancies[$key]->cat_id = $item->fk_cid;
      }

      // echo "<pre>";
      // print_r($$instancies);

      $calendarParams['instancies'] = $instancies;
      $calendarParams['option'] = $option;
      $calendarParams['layout_fk_eid'] = $layout->fk_eid;
      $calendarParams['layout_params'] = $layout_params;
      $calendarParams['Itemid'] = $Itemid;

    // Category::show_attached_layout($option, $instance_lid, $layout->fk_eid, $layout_params, '', '', 0, $instancies, '');  

    // exit;
    $type =  'calendar';
    require getLayoutPathCCK::getLayoutPathCom($option, $type);
  }



  static function show_request_layout($option, $lid,$eiid,$show_type = 1,$button_name = '', $has_price = 0,$title = '', $button_style = ''){
      
    global $os_cck_configuration, $db, $moduleId, $task, $doc;
    $entityInstance = new os_cckEntityInstance($db);
    $session = JFactory::getSession();
    $bootstrap_version = $session->get('bootstrap','2');
    $layout = new os_cckLayout($db);
    $layout->load($lid);
    if(!$layout->lid){
      JError::raiseWarning(0, JText::_("COM_OS_CCK_SELECT_ADD_INSTANCE") );
      return;
    }
    $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
    $layout_params = unserialize($layout->params);
    $layout_params['custom_fields'] = unserialize($layout->custom_fields);
    $layout_fields = $layout_params['fields'];
    $layout_params['has_price'] = $has_price;
    $layout_params['title'] = $title;
    $layout->parent_eiid = $eiid;
    if($show_type)
      $layout_params['show_type'] = $show_type;
    $layout_params['button_name'] = $button_name;
    if($layout->type == 'show_review_instance'){

      $entityInstance->load($eiid);
      $reviews = $entityInstance->getReviews();


      if ($reviews != null && count($reviews) > 0) {
        foreach ($reviews as $review) {
          $entityInstance = new os_cckEntityInstance($db);
          $entityInstance->load($review->eiid);
          $layout->field_list = $entityInstance->getFields();
          //load attached module ids
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
        
          require getLayoutPathCCK::getLayoutPathCom($option,$layout->type);
        }
      }else{
        if($layout_params['views']['no_review_message']){
          echo '<span class="first-review">'.JText::_("COM_OS_CCK_SELECT_NO_REVIEW").'</span>';
        }
      }
    }else{
      $entityInstance->fk_eid = $layout->fk_eid;
      $layout->field_list = $entityInstance->getFields();
      if($layout_params['has_price'] == 0){
        $parentInstance = new os_cckEntityInstance($db);
        $parentInstance->load($eiid);
        $field_list = $parentInstance->getFields();
        foreach ($field_list as $field) {
          $layout_fields = $layout_params['fields'];
          if($field->field_type == 'decimal_textfield' && $layout_params['has_price'] == 0){
            if(isset($layout_fields[$field->db_field_name.'_price_field']) 
                && $layout_fields[$field->db_field_name.'_price_field']){
              $layout_params['has_price'] = $layout_fields[$field->db_field_name.'_price_field'];
            }
          }
        }
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

      $type = $layout->type;
      require getLayoutPathCCK::getLayoutPathCom($option,$type);
    }
  }
}
