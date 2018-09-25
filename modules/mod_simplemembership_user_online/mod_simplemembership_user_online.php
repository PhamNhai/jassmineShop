<?php
/*
*
* @package simpleMembership
* @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); ; 
* Homepage: http://www.ordasoft.com
* Updated on January, 2014
*/

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

global $database, $my, $mosConfig_live_site,$mosConfig_absolute_path;
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path']	= JPATH_SITE;
require_once($mosConfig_absolute_path."/components/com_simplemembership/compat.joomla1.5.php");
// load language
$lang = JFactory::getLanguage();
JFactory::getLanguage()->load('com_users', JPATH_SITE, $lang->getTag(), true);//add language for com_users
$doc = JFactory::getDocument();  
$doc->addStyleSheet( $mosConfig_live_site.'/components/com_simplemembership/includes/simplemembership.css' );
$database = JFactory::getDBO();
$user = JFactory::getUser();
$sufix = $params->get('moduleclass_sfx');
$Item = $params->get('Item_id');
$ListUser[0]= ''; 

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
  
$database = JFactory::getDBO();
$mod_row =  JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
if (! $mod_row->load ($module->id)) {
    JError::raiseError ( 500, $mod_row->getError () );
}
//module params
if (version_compare(JVERSION, '3.0', 'ge')) {
    $params = new JRegistry;
    $params->loadString($mod_row->params);
} else {
    $params = new JRegistry($mod_row->params);
}//end
$where = '';
$show_users_group = $params->get('show_users_group','-2');
if(empty($show_users_group))$show_users_group = -2;
if($show_users_group != -2)
  $where = " WHERE sm_grp.id IN ( " . implode(',',$show_users_group) . " ) ";
$query = "SELECT usrs.* FROM #__users as usrs"
            ."\n LEFT JOIN #__simplemembership_users as sm_usrs ON sm_usrs.fk_users_id = usrs.id"
            ."\n LEFT JOIN #__simplemembership_groups as sm_grp ON sm_grp.id = sm_usrs.current_gid"
            .$where
            ."\n ORDER BY usrs.lastvisitDate";
$database->setQuery($query);
$usersList = $database->LoadObjectList();
$profile_image_controll='';
$ListUser = array();
foreach($usersList as $user){
    $ListUser[0][]=$user->id;
    $ListUser[1][]=$user->name;    
    $user_id=$user->id;
    $joom_user = new JUser();
    $joom_user->load($user_id);
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    JForm::addFormPath($mosConfig_absolute_path.'/components/com_simplemembership/forms');
    $user_profile_form = JForm::getInstance('com_users.module', 'registration');
    $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.module', $joom_user));
    $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
    $user_profile_form->bind($joom_user);
    $profile_image=$user_profile_form->getValue('file','profile','');
    if($user_profile_form->getValue('file','profile','')&& $profile_image != $profile_image_controll){
        $mas_img[1][] = " <img src='".$mosConfig_live_site.$user_profile_form->getValue('file','profile','').
                        "' style='height:".$params->get('img_height')."px; width:".$params->get('img_width')."px;'>";
        $mas_img[0][] = $user->id;
        $profile_image_controll =  $user_profile_form->getValue('file','profile','');
    }else{
        $mas_img[1][] = "<img src=".$mosConfig_live_site
                        ."/components/com_simplemembership/images/default.gif style='height:"
                        .$params->get('img_height')."px; width:".$params->get('img_width')."px;'>";
        $mas_img[0][] = $user->id;
    }
}
require JModuleHelper::getLayoutPath('mod_simplemembership_user_online', $params->get('layout', 'default'));
