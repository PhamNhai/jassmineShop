<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package simpleMembership
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Anton Getman(ljanton@mail.ru);
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 PRO
 *
 *
 */

if (!function_exists('unapproveUsers')){
  function unapproveUsers($bid,$option){
    global $database, $my, $user_configuration, $mosConfig_mailfrom;
    $catid = mosGetParam($_POST, 'catid', array(0));
    $bids = implode(',', $bid);
    $database->setQuery("SELECT * FROM #__simplemembership_users WHERE id IN ($bids)");
    $list_id = $database->loadObjectList();
    $str_id = '';
    if (!$database->query()) {
      echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    $database->setQuery("UPDATE #__simplemembership_users SET approved=0".
        "\n WHERE id IN ($bids)");
    if (!$database->query()) {
      echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    // Get the dispatcher and load the users plugins.
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    $user = new JUser();
    foreach($list_id as $item_id) {
      if ($item_id->current_gid != 0) {
        //// Default group
        $default_group = $user_configuration['default_group'];
        if (empty($default_group ) )
        {

          $default_group = array('2' => '2');
          $default_group_name = 'Registered';
        } else {
          $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
          $database->setQuery($query);
          $dg=explode(",",$default_group);
          $default_group_name = $database->loadColumn();
          $default_group_name = implode(",", $default_group_name);
          $default_group = array_combine($dg, $dg);
        }//// end Default group
        
        $user->load($item_id->fk_users_id);
        $query = "SELECT * FROM #__simplemembership_groups AS sg
          WHERE sg.id=".$item_id->current_gid;
        $database->setQuery($query);
        $usr_group = $database->loadObjectList();
        $usr_group=$usr_group['0'];
        $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
        $user->groups = $default_group;
        $user->usertype = $default_group_name;
        $user->save();
        $query="UPDATE #__simplemembership_users SET want_gid='".$usr_group->id."',
                                                      want_gname='".$usr_group->name."',
                                                      current_gid=-2,
                                                      current_gname='Default',
                                                      expire_date = 0
        WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
        $database->setQuery($query);
        $database->query();
        $sql = "SELECT id FROM #__simplemembership_orders WHERE fk_sm_users_id = '".$item_id->id."'";
        $database->setQuery($sql);
        $order_id = $database->loadResult();
        $txn_type = 'Expired term of the group.(Set default group)';
        $sql = "INSERT INTO `#__simplemembership_orders_details`(fk_order_id, fk_sm_users_id,
                              fk_sm_users_name, fk_sm_users_email,
                              order_date, fk_group_id, txn_type)
                      VALUES ('".$order_id."',
                              '".$item_id->id."',
                              '".$item_id->name."',
                              '".$item_id->email."',
                              now(),
                              '".$item_id->current_gid."',
                              '".$txn_type."')";
        $database->setQuery($sql);
        $database->query();
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
          JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
        } else {
          $a = JFactory::getMailer();
          $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
        }
      }
    }
    if (count($bid) == 1) {
       $row = new mos_alUser($database);
       $row->checkin($bid[0]);
    }
  }
}

function advexpire($current_user = '') {
  global $mosConfig_mailfrom, $user_configuration;
  $database = JFactory::getDBO();
  if ($current_user == '') 
    $current_user = JFactory::getUser();
  if (!class_exists('mos_alUser')) 
    include (JPATH_SITE .'/components/com_simplemembership/simplemembership.class.php');
  if (empty($user_configuration)) include (JPATH_SITE .
      '/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php');
  $date = date('Y-m-d H:i:s');
  $query = "SELECT id FROM #__simplemembership_users " .
          " WHERE current_gid >'0' AND `expire_date` <= now() AND approved='1' 
          AND `expire_date`!='0000-00-00 00:00:00' " .
          " LIMIT 0, 100"; //check when was last approved now it should be unapproved ??
  $database->setQuery($query);
  $list_users_id = $database->loadObjectList();
  $option = mosGetParam($_REQUEST, 'option', 'com_simplemembership');
  foreach($list_users_id as $item){
    $i[]=$item->id;
    $bid=$i;
    unapproveUsers($bid,$option);
  }
}

function sync_users($current_user = '') {
  $database = JFactory::getDBO();
  if ($current_user == '') $current_user = JFactory::getUser();
  if (!class_exists('mos_alUser')) include (JPATH_SITE .
     '/components/com_simplemembership/simplemembership.class.php');
  if (empty($user_configuration)) include (JPATH_SITE .
   '/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php');
  //// Default group
  $default_group = $user_configuration['default_group'];
  if (empty($default_group ) )
  {
    $default_group = array('2' => '2');
    $default_group_name = 'Registered';
  } else {
    $query = "SELECT ug.title 
              FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
    $database->setQuery($query);
    $dg=explode(",",$default_group);
    $default_group_name = $database->loadColumn();
    $default_group_name = implode(",", $default_group_name);
    $default_group = array_combine($dg, $dg);
  }//// end Default group
  $query = "SELECT * 
            FROM #__simplemembership_users WHERE fk_users_id=" . $current_user->id . " ";
  $database->setQuery($query);
  $adv_users = $database->loadObjectList();
  foreach($adv_users as $adv_user) {
    $query = "SELECT name 
              FROM #__users 
              WHERE id='" . $adv_user->fk_users_id . "'";
    $database->setQuery($query);
    $joom_result = $database->loadResult();
    if ($joom_result == '') {
      $al_user = new mos_alUser($database);
      $al_user->load($adv_user->id);
      $al_user->delete();
    }
  }
  $query = "SELECT * 
            FROM #__users AS u " .
          " LEFT JOIN #__user_usergroup_map AS ugm ON u.id=ugm.user_id WHERE u.id=" .
          $current_user->id . " GROUP BY u.id";
  $database->setQuery($query);
  $joom_users = $database->loadObjectList();
  foreach($joom_users as $user) {
    $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id='" . $user->id . "'";
    $database->setQuery($query);
    if (version_compare(JVERSION, '3.0.0', 'ge')) {
      //$user->group_id = $database->loadResult();
      $user->group_id = $database->loadColumn(0);
      foreach($user->group_id as $group_id_item) {
        $query = 'SELECT title FROM #__usergroups WHERE id=' . $database->quote($group_id_item);
        $database->setQuery($query);
  //      $user->usertype = $database->loadResult();
        if(isset($user->usertype)) $user->usertype = array_merge($user->usertype, $database->loadColumn(0));
        else $user->usertype = $database->loadColumn(0);
      }
    } else {
      $user->group_id = $database->loadResultArray();
    }
    if (isset($user->group_id) && count($user->group_id) > 1) {
      $user->group_id = array_combine($user->group_id, $user->group_id);
    }
    if (isset($user->group_id) && count($user->group_id) < 2 && count($user->group_id) > 0) {
      $user->group_id = array($user->group_id[0] => $user->group_id[0]);
    }
    $user->group_id = array_combine($user->group_id, $user->group_id);
    $query = "SELECT * FROM #__simplemembership_users WHERE fk_users_id='" . $user->id . "'";
    $database->setQuery($query);
    $adv_result = $database->loadObject();
    if (!$adv_result) {
      $al_user = new mos_alUser($database);
      $al_user->name = $user->name;
      $al_user->username = $user->username;
      $al_user->email = $user->email;
      $al_user->fk_users_id = $user->id;
      $al_user->block = $user->block;
      $al_user->current_gid = -2;
      $al_user->current_gname = 'Default';
      $al_user->want_gid = 0;
      $al_user->want_gname = '';
      $al_user->approved = $user->block;
      $al_user->expire_date = 0;
      $al_user->store();
    } else {
      $al_user = new mos_alUser($database);
      $al_user->load($adv_result->id);
      $al_user->name = $user->name;
      $al_user->username = $user->username;
      $al_user->email = $user->email;
      $al_user->block = $user->block;
      $al_user->store();
    }
  }
}


function check_users($current_user = '') {
  global $user_configuration, $mosConfig_mailfrom;
  if ($current_user == '') $current_user = JFactory::getUser();
  sync_users($current_user);

  if (!class_exists('mos_alUser')) include (JPATH_SITE .
   '/components/com_simplemembership/simplemembership.class.php');
  if (empty($user_configuration)) include (JPATH_SITE .
     '/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php');
  $interval = 24;
  $database = JFactory::getDBO();
  $query = "select id from #__simplemembership_check where `last_check` < DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND id = 1";
  $database->setQuery($query);
  $result = $database->loadResult();
  if($result){
    advexpire($current_user);
  
    $query = "update #__simplemembership_check SET last_check='" . date('Y-m-d H:i:s', time()) . "' where id='1'";
    $database->setQuery($query);
    $database->query();
  }  
}
