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


class ViewCategory
{

    static function showCategories($option, $categories ,$layout , $layout_params){
			global $db,$os_cck_configuration,$moduleId,$params, $Itemid, $my, $moduleId;
			$type = 'all_categories';

			require getLayoutPathCCK::getLayoutPathCom($option,$type);
    }

    static function showSearch($option, $layout, $layout_params, $button_style=''){
    

      global $app, $db, $acl, $user, $Itemid, $os_cck_configuration, $limit, $total, $limitstart,$moduleId;

      $fromSearch = 1;
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


    static function displayCategory($option, $instancies, $catid, $categories, $currentcat,
                                $is_exist_sub_categories, $layout, $layout_params, $pageNav, $entity_type = ''){
      global $db, $os_cck_configuration,$moduleId,$params, $Itemid, $my, $session;
      $type = 'category';
      require getLayoutPathCCK::getLayoutPathCom($option, $type);
    } //end function

}
