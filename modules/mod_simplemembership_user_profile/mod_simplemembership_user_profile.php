<?php
/*
*
* @package simpleMembership
* @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); ; 
* Homepage: http://www.ordasoft.com
* Updated on January, 2014
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

global $mosConfig_live_site, $mosConfig_absolute_path, $Itemid, $my;
$doc = JFactory::getDocument(); 
$database = JFactory::getDBO();
$doc->addStyleSheet( $mosConfig_live_site.'/components/com_simplemembership/includes/simplemembership.css' );

if (!function_exists('find_in_rem')) {
  function find_in_rem($current){
    global $database;
    $user_id=false;
    if($current['name'] !==''){
      $query="SELECT id FROM #__users WHERE name LIKE '".$current['name']."'";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'view' || $current['task'] == 'view_house'){  
      if($current['id']){
        $rem_id = $current['id'];
      }else{
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
        } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
        }//end
        if(!$params->get('house','')){
          return;
        }
        $rem_id = $params->get('house','');
      }
      $query="SELECT rh.owner_id FROM #__rem_houses AS rh WHERE rh.id = ".$rem_id ;
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'owner_houses'){
      // Params(RealEstate component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
      }//end
      if($params->get('username',''))
        $user_id = $params->get('username','');
    }
    return $user_id;
  }
}
if (!function_exists('find_in_vm')) {
  function find_in_vm($current){
    global $database;
    $user_id=false;
    if($current['name'] !==''){
      $query="SELECT id FROM #__users WHERE name LIKE '".$current['name']."'";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'view_vehicle'){
      if($current['id']){
        $vm_id = $current['id'];
      }else{
        if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
        } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
        }//end
        if(!$params->get('vehicle','')){
          return;
        }
        $vm_id = $params->get('vehicle','');
      }
      $query="SELECT vm.owner_id " .
        " FROM #__vehiclemanager_vehicles AS vm WHERE vm.id = ".$vm_id;
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'owner_vehicles'){
      // Params(RealEstate component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
      }//end
      if($params->get('username',''))
        $user_id = $params->get('username','');
    }
    return $user_id;
  }
}
if (!function_exists('find_in_bl')) {
  function find_in_bl($current){
    global $database;
    // $my = JFactory::getUser();
    $user_id=false;
    if($current['name'] !==''){
      $query="SELECT id FROM #__users WHERE name LIKE '".$current['name']."'";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if( ($current['task'] == 'view' || $current['task'] == 'view_bl' || $current['task'] == 'view_book')
     && $current['id'] !=0){
      $query="SELECT bl.owner_id FROM #__booklibrary AS bl WHERE bl.id = ".$current['id'];
      $database->setQuery($query);
      $user_id=$database->loadResult();
      return $user_id;
    }
    if($current['task'] == 'owner_books' || $current['task'] == 'view_bl' || $current['task'] == 'view_book'){
      // Params(RealEstate component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
      }//end

      if($params->get('username',''))
        $user_id = $params->get('username','');

      if ( ($current['task'] == 'view_book' || $current['task'] == 'view_bl' ) && $params->get('book', '')) {
        $query = "SELECT owner_id FROM `#__booklibrary` WHERE id=" . $params->get('book', '');
        $database->setQuery($query);
        $user_id=$database->loadResult();
        return $user_id;
      }
    }
    // print_r($params);exit;
    return $user_id;
  }
}
if (!function_exists('find_in_ml')) {
  function find_in_ml($current){

    global $database;
    $user_id=false;
    if($current['name'] !==''){
      $query="SELECT id FROM #__users WHERE name LIKE '".$current['name']."'";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'view' && $current['id'] !=0  ){
      $query="SELECT ml.owner_ID FROM #__medialibrary AS ml WHERE ml.id = ".$current['id'];
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'ownermedias' || $current['task'] == 'view' || 
      $current['task'] == 'displaybook' || $current['task'] == 'displaygame' || 
      $current['task'] == 'displaymusic' || $current['task'] == 'displayvideo' ){
      if($current['other_uid'] ) 
        return $current['other_uid'] ;
      
      // Params(RealEstate component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
          $menu = new JTableMenu($database);
          $menu->load($current['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else {
          $app = JFactory::getApplication();
          $menu = $app->getMenu();
          $params = new JRegistry;
          $params = $menu->getParams( $current['Itemid'] );
      }//end

      if($params->get('username',''))
        $user_id = $params->get('username','');

      if ( ($current['task'] == 'view' || 
          $current['task'] == 'displaybook' || $current['task'] == 'displaygame' || 
          $current['task'] == 'displaymusic' || $current['task'] == 'displayvideo' )
           && $params->get('media', '')) {
        $query = "SELECT ml.owner_ID FROM #__medialibrary AS ml WHERE ml.id = " . $params->get('media', '');
        $database->setQuery($query);
        $user_id=$database->loadResult();
        return $user_id;
      }
    }
    
    return $user_id;
  }
}
if (!function_exists('find_in_ads')) {
  function find_in_ads($current){
    global $database;

    $user_id=false;
    if($current['name'] !==''){
      $query="SELECT id FROM #__users WHERE name LIKE '".$current['name']."'";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }
    if($current['task'] == 'show_alone_advertisement' && $current['id'] !=0 ){
      $query="SELECT user.id FROM #__users AS user " .
       " WHERE user.email = (SELECT a.owneremail FROM #__advertisementboard AS a WHERE a.id = ".$current['id'].") ";
      $database->setQuery($query);
      $user_id=$database->loadResult();
    }

    return $user_id;
  }
}
  $find_in['rem'] = $params->def('rem',0);
  $find_in['vm']  = $params->def('vm',0);
  $find_in['bl']  = $params->def('bl',0);
  $find_in['ml']  = $params->def('ml',0);
  $find_in['ads']  = $params->def('ads',0);

  $view_vh = $params->def('view',0);

  $current_component=JRequest::getVar('option','');
  $current['name']=JRequest::getVar('name','');
  $current['task']=JRequest::getVar('task','');
  $current['view']=JRequest::getVar('view','');
  if(empty($current['task']) && !empty($current['view']))
    $current['task'] = $current['view'];
  $current['id']=JRequest::getVar('id','');
  $current['Itemid'] =JRequest::getVar('Itemid','');
  $current['other_uid'] =JRequest::getVar('other_uid','');
  
 
  switch($current_component){
    case 'com_realestatemanager':
      if($find_in['rem'] == 1){ $user_id=find_in_rem($current);}
      else{$user_id=false;}
      break;
    case 'com_vehiclemanager':
      if($find_in['vm'] == 1){ $user_id=find_in_vm($current);}
      else{$user_id=false;}
      break;
    case 'com_booklibrary':
      if($find_in['bl'] == 1){ $user_id=find_in_bl($current);}
      else{$user_id=false;}
      break;
    case 'com_medialibrary':
        if($find_in['ml'] == 1){ $user_id=find_in_ml($current);}
        else{$user_id=false;}
      break;
    case 'com_advertisementboard':
        if($find_in['ads'] == 1){ $user_id=find_in_ads($current);}
        else{$user_id=false;}
      break;
    default:
      return $user_id=false;
    break;
  }

  $Item = $params->get('Item_id');

  if($Item){
      $Itemid_simp = $Item;
  }else{
      $database =  JFactory::getDBO();
      $database->setQuery("SELECT id
             FROM `#__menu`
             WHERE LOWER(alias) LIKE 'simple-membership' ");
             // WHERE `alias` LIKE 'simple-membership' ");
      $Itemid_simp = $database->loadResult();
  }

  if($user_id != false){
    require JModuleHelper::getLayoutPath('mod_simplemembership_user_profile',$params->get('layout'));
  }
?>
