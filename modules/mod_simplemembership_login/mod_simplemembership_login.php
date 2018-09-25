<?php
/*
*
* @package simpleMembership
* @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); ; 
* Homepage: http://www.ordasoft.com
* Updated on January, 2014
*/

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) 
  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

if(!function_exists('getReturnURL_SM')) {
  function getReturnURL_SM($params, $type)
  {
    $app    = JFactory::getApplication();
    $router = $app->getRouter();
    $url = null;
    if ($itemid = $params->get($type)){
      $db     = JFactory::getDbo();
      $query  = $db->getQuery(true)
        ->select($db->quoteName('link'))
        ->from($db->quoteName('#__menu'))
        ->where($db->quoteName('published') . '=1')
        ->where($db->quoteName('id') . '=' . $db->quote($itemid));
      $db->setQuery($query);
      if ($link = $db->loadResult()){
        if ($router->getMode() == JROUTER_MODE_SEF){
          $url = 'index.php?Itemid=' . $itemid;
        }else{
          $url = $link . '&Itemid=' . $itemid;
        }
      }
    }
    if (!$url){
      // Stay on the same page
      $uri = clone JUri::getInstance();
      $vars = $router->parse($uri);
      unset($vars['lang']);
      if ($router->getMode() == JROUTER_MODE_SEF){
        if (isset($vars['Itemid'])){
          $itemid = $vars['Itemid'];
          $menu = $app->getMenu();
          $item = $menu->getItem($itemid);
          unset($vars['Itemid']);
          if (isset($item) && $vars == $item->query){
            $url = 'index.php?Itemid=' . $itemid;
          }else{
            $url = 'index.php?' . JUri::buildQuery($vars) . '&Itemid=' . $itemid;
          }
        }else{
          $url = 'index.php?' . JUri::buildQuery($vars);
        }
      }else{
        $url = 'index.php?' . JUri::buildQuery($vars);
      }
    }
    return base64_encode($url);
  }
}
if(!function_exists('getReturnURL_SM2')) {
  function getReturnURL_SM2($params, $type)
  {
    $app  = JFactory::getApplication();
    $item = $app->getMenu()->getItem($params->get($type));
    if ($item)
    {
      $url = 'index.php?Itemid=' . $item->id;
    }
    else
    {
      // Stay on the same page
      $url = JUri::getInstance()->toString();
    }
    return  base64_encode($url);
  }
}

global $mosConfig_absolute_path, $mosConfig_allowUserRegistration;
$usersConfig =JComponentHelper::getParams( 'com_users' );
//	Show login or logout?
  $user = JFactory::getUser();
  $type = (!$user->get('guest')) ? 'logout' : 'login';
  
// Determine settings based on CMS version
  if( $type == 'login' ) {
    // Lost password
    $reset_url = JRoute::_( 'index.php?option=com_users&amp;view=reset' );
    // User name reminder (Joomla 1.5 only)
    $remind_url = JRoute::_( 'index.php?option=com_users&amp;view=remind' );
    // Set the validation value
    if (version_compare(JVERSION, '3.0.0', 'lt')) 
    $validate = JUtility::getToken();
  } else {
    $database =JFactory::getDBO();
    $joom_id = $user->id;
    $query = "SELECT u.*, uum.group_id as gid from #__users as u 
              LEFT JOIN #__user_usergroup_map as uum ON uum.user_id=u.id WHERE id='$joom_id'";
    $database->setQuery($query);
    $juser = $database->loadObject();
    $email = $juser->email;
    $id = $juser->id;
    $name = $juser->name;
    $username = $juser->username;
    if (version_compare(JVERSION, '3.0.0','lt')) {
      $query = "update #__simplemembership_users set name='$name', 
                        username='$username',
                        email='$email' where fk_users_id='$id' ";
    } else {
      $query = "update #__simplemembership_users set name='$name',
                        username='$username',
                        email='$email' where fk_users_id='$id' ";
    }
    $database->setQuery($query);
    $database->query();
    // Set the greeting name
    $user = JFactory::getUser();
    $name = ( $params->get( 'name') ) ? $user->name : $user->username;
  }
require JModuleHelper::getLayoutPath('mod_simplemembership_login', $params->get('layout', 'default'));
