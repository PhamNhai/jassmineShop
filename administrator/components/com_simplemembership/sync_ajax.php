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
$params_for_sync = JRequest::getVar('params_for_sync');
$sub_task = $params_for_sync['sub_task'];
switch ($sub_task) {
case "sync":
    sync_user($params_for_sync);
break;
case "expire":
    exp_user($params_for_sync);
break;
}

function sync_user($params_for_sync) {
  $database = JFactory::getDBO();
  if (empty($user_configuration)) include (JPATH_SITE . 
    '/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php');
  if (!class_exists('mos_alUser')) include (JPATH_SITE . 
    '/components/com_simplemembership/simplemembership.class.php');
////////// Default group
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
  }
/////////// END DEFAULT GROUP
  if ($params_for_sync['count_adv_users'] != 0) {
    $count_adv_users = $params_for_sync['count_adv_users'];
  } else {
    $query = "SELECT COUNT(*) FROM #__simplemembership_users";
    $database->setQuery($query);
    $count_adv_users = $database->loadResult();
  }
  if ($params_for_sync['ia'] != 0) $ia = $params_for_sync['ia'];
  else $ia = 0;
  if ($params_for_sync['ia_d'] != 0) $ia_d = $params_for_sync['ia_d'];
  else $ia_d = 0;
  if (($ia * 100 - $ia_d) <= floor($count_adv_users)) {
    //this hren for correct work because count_adv_users can be differnt, because they can be deleted
    $start_ia = 100 * $ia - $ia_d;
    $query = "SELECT * FROM #__simplemembership_users ORDER BY id LIMIT " . $start_ia . ", 100 ";
    $database->setQuery($query);
    $adv_users = $database->loadObjectList();

    foreach($adv_users as $adv_user) { //if there aren't users in user delete from adv_users
      $query = "select name from #__users where id='" . $adv_user->fk_users_id . "'";
      $database->setQuery($query);
      $joom_result = $database->loadResult();
      if ($joom_result == '') {
        $al_user = new mos_alUser($database);
        $al_user->load($adv_user->id);
        $al_user->delete();
        $ia_d = $ia_d + 1;
      }
    }
    $query = "SELECT COUNT(*) FROM #__simplemembership_users";
    $database->setQuery($query);
    $count_adv_users = $database->loadResult();
    $ia = $ia + 1;
    $params_for_sync['format'] = 'raw';
    $params_for_sync['option'] = 'com_simplemembership';
    $params_for_sync['ia_d'] = $ia_d;
    $params_for_sync['ia'] = $ia;
    $params_for_sync['count_adv_users'] = $count_adv_users;
    $params_for_sync['sub_task'] = 'sync';
    $params_for_sync['task'] = 'sync_ajax';        
    echo json_encode($params_for_sync);
    exit;
  }
  if ($params_for_sync['count_joom_users'] != 0) {
    $count_joom_users = $params_for_sync['count_joom_users'];
  } else {
    $query = "SELECT COUNT(*) FROM #__users";
    $database->setQuery($query);
    $count_joom_users = $database->loadResult();
  }
  if ($params_for_sync['ij'] != 0) $ij = $params_for_sync['ij'];
  else $ij = 0;
  if ($ij <= floor($count_joom_users / 100)) {
    $query = "SELECT * FROM #__users  LIMIT " . $ij * 100 . ", 100";
    $database->setQuery($query);
    $joom_users = $database->loadObjectList();
    foreach($joom_users as $user) {
      $query = "SELECT * FROM #__simplemembership_users WHERE fk_users_id='" . $user->id . "'";
      $database->setQuery($query);
      $adv_result = $database->loadObject();
      if (!$adv_result) {
        $al_user = new mos_alUser($database);
        $al_user->name = $user->name;
        $al_user->username = $user->username;
        $al_user->email = $user->email;
        $al_user->current_gid = -2;
        $al_user->current_gname = 'Default';
        $al_user->fk_users_id = $user->id;
        $al_user->block = $user->block;
        //$al_user->approved = 1;
        $al_user->expire_date = 0;
        $al_user->store();
      } else {
        $al_user = new mos_alUser($database);
        $al_user->load($adv_result->id);
        $al_user->name = $user->name;
        $al_user->username = $user->username;
        $al_user->email = $user->email;
        $al_user->block = $user->block;
        $sql="SELECT * FROM #__user_usergroup_map WHERE user_id=".$user->id.
                      " AND group_id = 8";
        $database->setQuery($query);
        $result = $database->loadObjectList();
        if(!$result[0]){
          $al_user->current_gid = -2;
          $al_user->current_gname = 'Default';
          $al_user->approved = 1;
          $al_user->last_approved = date("Y-m-d H:i:s");
        }
        $al_user->store();
      }
    }
    $ij++;
    $params_for_sync['format'] = 'raw';
    $params_for_sync['option'] = 'com_simplemembership';
    $params_for_sync['ij'] = $ij;
    $params_for_sync['count_joom_users'] = $count_joom_users;
    $params_for_sync['sub_task'] = 'sync';
    $params_for_sync['task'] = 'sync_ajax';
    echo json_encode($params_for_sync);
    exit;
  }
  $params_for_sync['format'] = 'raw';
  $params_for_sync['option'] = 'com_simplemembership';
  $params_for_sync['status'] = 'sync_user_ok';
  $params_for_sync['sub_task'] = 'expire';
  $params_for_sync['task'] = 'sync_ajax';    
  echo json_encode($params_for_sync);
  exit;
}

function exp_user($params_for_sync) {
  global $mosConfig_mailfrom,$user_configuration;
  $database = JFactory::getDBO();
  if (!class_exists('mos_alUser')) include (JPATH_SITE .
    '/components/com_simplemembership/simplemembership.class.php');
  if (empty($user_configuration)) include (JPATH_SITE .
    '/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php');
  //// Default group
  $default_group = $user_configuration['default_group'];
  if (empty($default_group ) )
  {
    $default_group_name = 'Registered';
  } else {
    $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
    $database->setQuery($query);
    $default_group_name = $database->loadColumn();
    $default_group_name = implode(",", $default_group_name);
  }////end Default group
  if ($params_for_sync['count_exp_users'] != 0) {
    $count_exp_users = $params_for_sync['count_exp_users'];
  } else {
    $query = "SELECT count(*) FROM #__simplemembership_users " .
          " WHERE current_gid >'0' AND `expire_date` <= now() 
            AND approved='1' AND `expire_date`!='0000-00-00 00:00:00' ";
    $database->setQuery($query);
    $count_exp_users = $database->loadResult();
  }
  if ($params_for_sync['ie'] != 0) $ie = $params_for_sync['ie'];
  else $ie = 0;
  if ($params_for_sync['ie_d'] != 0) $ie_d = $params_for_sync['ie_d'];
  else $ie_d = 0;
  if (($ie * 100 - $ie_d) <= floor($count_exp_users / 100)) {
    $start_ie = 100 * $ie - $ie_d;
    $query = "SELECT id FROM #__simplemembership_users " .
          " WHERE current_gid >'0' AND `expire_date` <= now() 
          AND approved='1' AND `expire_date`!='0000-00-00 00:00:00' " .
          " LIMIT " . $start_ie . ", 100";
    $database->setQuery($query);
    $list_joom_id = $database->loadObjectList();
    foreach($list_joom_id as $item) {
      $i[]=$item->id;
      $bid=$i;
      $bids = implode(',', $bid);
      $database->setQuery("SELECT * FROM #__simplemembership_users WHERE id IN ($bids)");
      $list_id = $database->loadObjectList();
      $str_id = '';
      $database->setQuery("UPDATE #__simplemembership_users SET approved=0".
        "\n WHERE id IN ($bids)");
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
                                                        approved = 0,
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
        $ie_d = $ie_d + 1;
      }
    }
    $ie++;
    $params_for_sync['format'] = 'raw';
    $params_for_sync['option'] = 'com_simplemembership';
    $params_for_sync['ie'] = $ie;
    $params_for_sync['ie_d'] = $ie_d;
    $params_for_sync['count_exp_users'] = $count_exp_users;
    $params_for_sync['sub_task'] = 'expire';
    $params_for_sync['task'] = 'sync_ajax';    
    echo json_encode($params_for_sync);
    exit;
  }
  $params_for_sync['format'] = 'raw';
  $params_for_sync['option'] = 'com_simplemembership';
  $params_for_sync['status'] = 'exp_user_ok';
  echo json_encode($params_for_sync);
  exit;
}
