<?php

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
defined('_JEXEC') or die;

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
require_once($mosConfig_absolute_path . "/components/com_os_cck/classes/instance.class.php");
require_once($mosConfig_absolute_path . "/components/com_os_cck/classes/entity.class.php");


function os_cckBuildRoute(&$query) {
//echo"1111111start os_cckBuildRoute";
//print_r($query);echo"<br>";

    $segments = array();
    $session = JFactory::getSession();
    $db = JFactory::getDBO();

    if (isset($query['option']) && $query['option'] == 'com_os_cck'){ //check component
      // //unset Itemid
      // $segments[0] = (isset($query['Itemid'])) && ($query['Itemid'] != 0) ? $query['Itemid'] : '0';
      // //end

      // //view = task
      // if(isset($query['task'])){
      //   $query['view'] = $query['task'];
      //   unset($query['task']);
      // }//end

      // //main task
      // if(isset($query['view'])){
      //   switch($query['view']){
      //     case "all_categories":
      //         $segments[1] = 'all_categories';
      //     break;

      //     case 'category':

      //         $segments[1] = 'category';
      //         if(isset($query['catid'])){
      //           $segments[] = $query['catid'];
      //           unset($query['catid']);
      //         }

      //         if(isset($query['lid'])){
      //           $segments[] = 'layout:'.$query['lid'];
      //           unset($query['lid']);
      //         }
      //         if(isset($query['moduleId'])){
      //           $segments[] = 'module:'.$query['moduleId'];
      //           unset($query['moduleId']);
      //         }

      //     break;

      //     case 'my_instance':
      //         $segments[1] = 'my-instance';              
      //     break;

      //     case 'instance':

      //         $segments[1] = 'instance';
      //         if(isset($query['id'])){
      //           $segments[] = $query['id'];
      //           unset($query['id']);
      //         }
      //         if(isset($query['lid'])){
      //           $segments[] = 'layout:'.$query['lid'];
      //           unset($query['lid']);
      //         }
      //         if(isset($query['catid'])){
      //           $segments[] = 'category:'.$query['catid'];
      //           unset($query['catid']);
      //         }    
      //         if(isset($query['moduleId'])){
      //           $segments[] = 'module:'.$query['moduleId'];
      //           unset($query['moduleId']);
      //         }          

      //     break;

      //     case 'show_search':

      //         $segments[1] = 'show-search';
      //         if(isset($query['lid'])){
      //           $segments[] = 'layout:'.$query['lid'];
      //           unset($query['lid']);
      //         }
      //         if(isset($query['catid'])){
      //           $segments[] = 'category:'.$query['catid'];
      //           unset($query['catid']);
      //         }
      //         if(isset($query['moduleId'])){
      //           $segments[] = 'module:'.$query['moduleId'];
      //           unset($query['moduleId']);
      //         }

      //     break;
      //   }

      //   unset($query['view']);
      // }else{///????//maybe need layter

      // }
      //end

        // if (isset($query['catid'])) {
        //     if ($query['catid'] > 0) {
        //         $sql_query = "SELECT name  FROM #__os_cck_categories WHERE cid=".intval($query['catid']);
        //         $db->setQuery($sql_query);
        //         $row = null;
        //         $row = $db->loadObject();
        //         if (isset($row)) {
        //             $cattitle = array();
        //             $segments[] = $query['catid']."-".$row->name;
        //             //$segments[] = $row->name;
        //             unset($query['catid']);
        //         }
        //     }
        // }


        // if (isset($query['id'])) {
        //     if ($query['id'] > 0) {
        //         $entityInstance = new os_cckEntityInstance($db);
        //         $entityInstance->load(intval($query['id']));
        //         if (isset($entityInstance)) {
        //             $cattitle = array();
        //             $segments[] = $query['id'];
        //             if(isset($query['itemindex']))
        //                 $segments[] = $query['itemindex'];
        //             unset($query['id']);
        //             unset($query['itemindex']);
        //         }
        //     }
        // }

        // if (isset($query['start'])) {
        //     if(!isset($segments[0])|| $segments[0] == 0)
        //         $segments[0] = (isset($query['Itemid'])) && ($query['Itemid'] != 0) ? $query['Itemid'] : '0';
        //     $segments[] = $query['start'];
        //     if (isset($query['limitstart'])) {
        //         $segments[] = $query['limitstart'];
        //         unset($query['limitstart']);
        //     } else {
        //         $segments[] = $query['start'];
        //     }
        //     unset($query['start']);
        // } else if (isset($query['limitstart'])) {
        //     $segments[] = $query['limitstart'];
        //     unset($query['limitstart']);
        // }
        
        // if(isset($query['moduleId'])){
        //     $segments[] = $query['moduleId'].'-moduleId';
        //     unset($query['moduleId']);
        // }
        
    }
//echo ":111111 end os_cckBuildRoute";
//print_r($query);echo"<br>";

    return $segments;
}

/**
 * Parse the segments of a URL.
 *
 */
function os_cckParseRoute($segments) {
//echo"22222 start os_cckParseRoute";
// print_r($segments);echo"<br>";

    $db = JFactory::getDBO();
    $vars = array();
    $vars['option'] = 'com_os_cck';
    // $menu = @JSite::getMenu();
    // $count = count($segments);
    // if (!is_numeric($segments[0])) {
    //   array_unshift($segments, "0");
    // }
    // $menu->setActive($segments[0]);
    // $vars['Itemid'] = $segments[0];

    // if(isset($segments[1])){
    //   switch($segments[1]){

    //     case 'category':

    //         $vars['view']  = $segments[1];
    //         $vars['catid'] = (isset($segments[2]))? $segments[2] : 0;
    //         $vars['lid']   = (isset($segments[3]))? str_replace("layout:", '', $segments[3]) : 0;
    //         $vars['moduleId'] = (isset($segments[4]))? str_replace("module:", '', $segments[4]) : 0;

    //     break;

    //     case 'instance':

    //         $vars['view']  = $segments[1];
    //         $vars['id']    = (isset($segments[2]))? $segments[2] : 0;
    //         $vars['lid']   = (isset($segments[3]))? str_replace("layout:"  , '', $segments[3]) : 0;
    //         $vars['catid'] = (isset($segments[4]))? str_replace("category:", '', $segments[4]) : 0;
    //         $vars['moduleId'] = (isset($segments[5]))? str_replace("module:", '', $segments[5]) : 0;

    //     break;

    //     case 'my:insetance':
    //         $vars['task']  = 'my_instance';
    //     break;

    //     case 'show:search':

    //         $vars['task']  = 'show_search';
    //         $vars['lid']   = (isset($segments[2]))? str_replace("layout:"  , '', $segments[2]) : 0;
    //         $vars['catid'] = (isset($segments[3]))? str_replace("category:", '', $segments[3]) : 0;
    //         $vars['moduleId'] = (isset($segments[4]))? str_replace("module:", '', $segments[4]) : 0;

    //     break;
    //   }
    // }else{

    //   if (version_compare(JVERSION, '3.0', 'ge')) {
    //     $menu = new JTableMenu($db);
    //     $menu->load($segments[0]);
    //     $params = new JRegistry;
    //     $params->loadString($menu->params);
    //   } else {
    //     $menu = JSite::getMenu();
    //     $params = new JRegistry;
    //     $params = $menu->getParams( $segments[0] );
    //   }

    //   if($params->get('allcategories_layout','')){
    //     $vars['task'] = 'all_categories';
    //   }

    //   if($params->get('all_instance_layout','')){
    //     $vars['task'] = 'all_instance';
    //   }

    //   if($params->get('instance_layout','')){
    //     $vars['task'] = 'instance';
    //     $vars['id']   = $params->get('instance',0); 
    //     $vars['lid']  = $params->get('instance_layout',0);
    //   }

    //   if($params->get('category_layout','')){
    //     $vars['task']  = 'category';
    //     $vars['lid']   = $params->get('category_layout',0);
    //     $vars['catid'] = $params->get('category',0);
    //   }

    //   if($params->get('search_layout','')){
    //     $vars['task']  = 'show_search';
    //     $vars['lid']   = $params->get('search_layout',0);
    //   }

    //   if($params->get('request_layout','')){
    //     $vars['task']  = 'add_instance';
    //     $vars['lid']   = $params->get('request_layout',0);
    //   }
    // }
    // if(isset($segments[1])){
    //     if($count == 3  && $segments[1] != "category"
    //         && $segments[1] != "instance" && $segments[1] != "show:search"){
            
    //         if($params->get('category','')){
    //             $vars['catid'] = $params->get('category');
    //             $vars['view'] = 'category';
    //             $vars['start'] = intval($segments[1]);
    //             $vars['limitstart'] = intval($segments[2]);
    //         }
    //         if($params->get('search_layout','')){
    //             $vars['start'] = intval($segments[1]);
    //             $vars['limitstart'] = intval($segments[2]);
    //         }
    //     }
        
    //     if ($segments[1] == "category" && isset($segments[2])) {
    //         $vars['view'] = 'category';
    //         $vars['catid'] = intval($segments[2]);
    //         if($count == 4)
    //             $vars['moduleId'] = intval($segments[3]);
    //         if($count > 3 && isset($segments[4])){
    //             $vars['start'] = intval($segments[3]);
    //             $vars['limitstart'] = intval($segments[4]);
    //             if(isset($segments[5]))
    //                 $vars['moduleId'] = intval($segments[5]);
    //         }
    //     }
    //     if($segments[1] == "show:search" && isset($segments[2])){
    //          $vars['view'] = 'show_search';
    //          $vars['catid'] = intval($segments[2]);
    //     }
    //     if (($segments[1] == "instance") && isset($segments[2])) {
    //         $vars['view'] = 'instance';
    //         $vars['catid'] = intval($segments[2]);
    //         if(isset($segments[3]))
    //             $vars['id'] = intval($segments[3]);
    //         if(isset($segments[4]))
    //             $vars['itemindex'] = intval($segments[4]);
    //         if(isset($segments[5]))
    //             $vars['moduleId'] = intval($segments[5]);
    //     }
    // }
    
//echo"22222 start os_cckParseRoute";
//print_r($query);echo"<br>";

    return $vars;
}
