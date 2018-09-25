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
 

$GLOBALS['mainframe'] = $mainframe = JFactory::getApplication();
$GLOBALS['document'] = $document = JFactory::getDocument();
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/compat.joomla1.5.php");
require_once ($mosConfig_absolute_path . '/components/com_simplemembership/openinviter/openinviter.php');
jimport('joomla.html.pagination');
jimport( 'joomla.filesystem.file' );



// load language
$lang_def_en = 0;
$lang = JFactory::getLanguage();
JFactory::getLanguage()->load('com_users', JPATH_SITE, $lang->getTag(), true); //add language for com_users

/** load the html drawing class */
// require_once($mainframe->getPath('front_html'));
// require_once($mainframe->getPath('class'));

require_once ($mosConfig_absolute_path .
             "/administrator/components/com_simplemembership/admin.simplemembership.class.others.php");
require_once ($mosConfig_absolute_path .
             "/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php");
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/functions.php");
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/simplemembership.class.php");
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/simplemembership.html.php");


$GLOBALS['user_configuration'] = $user_configuration;
$GLOBALS['mosConfig_mailfrom'] = $mosConfig_mailfrom;
//$mainframe->setPageTitle(  JText::_("COM_SIMPLEMEMBERSHIP_TITLE")  );
$doc = JFactory::getDocument();
$doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');
$task = trim(mosGetParam($_REQUEST, 'task', ""));
//$id = intval(mosGetParam($_REQUEST, 'id', 0));
$catid = intval(mosGetParam($_REQUEST, 'catid', 0));
$bids = mosGetParam($_REQUEST, 'bid', array(0));
//$uid = mosGetParam($_REQUEST, 'id', 0);
$GLOBALS['Itemid'] = $Itemid = trim(mosGetParam($_REQUEST, 'Itemid', ""));
$session = JFactory::getSession();
$userId = trim(mosGetParam($_REQUEST, 'userId', ""));
//add profile form
jimport('joomla.application.component.modelform');
//class Temp_class_for_profile_form extends JModelForm {


class UsersModelRegistration extends JModelForm {
  public function getForm($data = array(), $loadData = true) {
    // Get the form.
    $form = $this->loadForm('com_users.registration', 'registration',
                           array('control' => 'jform', 'load_data' => $loadData));
    if (empty($form)) {
      return false;
    }
    return $form;
  }
}
//add profile form


function josGetArrayIntsMy($name, $type = NULL) {
  $array = JRequest::getVar($name, array(), 'default', 'array');
  return $array;
}

$cid = josGetArrayIntsMy('cid');
require_once ($mosConfig_absolute_path.'/components/com_simplemembership/syncexpire.php');
check_users();
if (isset($_REQUEST['view'])) 
  $view = mosGetParam($_REQUEST, 'view', '');
if ((!isset($task) OR $task == '') AND isset($view))
  $task = $view;
//print_r($task);exit;
switch ($task) {
  case 'before_end_notify':
    BeforeEndNotify($option);
    break;
  case 'getMail':
  //prolong user account
  case 'userprolong': 
    getMail();
    break;
  case 'buyLink':
    buyLink($option);
    break;
  case 'buyGroup':
    buyGroup();
    break;
  case 'advregister':
  case 'registration':
    advregister();
    break;
  case 'login':
    mosRedirect(JRoute::_("index.php?option=com_users&view=login"));
    exit;
  case 'remindpassword':
    mosRedirect(JRoute::_("index.php?option=com_users&view=remind"));
    exit;
  case 'resetpassword':
    mosRedirect(JRoute::_("index.php?option=com_users&view=reset") );
    exit;
  case 'advexpire':
    advexpire();
    break;
  case 'activate':
    activate();
    break;
  case 'add_user':
    add_user();
    break;
  case 'user_prolong':
    user_prolong();
    break;
  case 'update_user':
    update_user();
    break;
  case "accdetail":
    accdetail($option);
    break;
  case 'show_invite':
    show_invite($option);
    break;
  case 'fetch':
    fetch($option);
    break;
  case 'invite':
    invite($cid, $option);
    break;
  case 'congretulation':
    congretulation($option);
    break;
  case 'show_users':
    show_users();
    break;
  case 'my_account':
    User_account();
    break;
  case 'showUsersProfile':
    ShowUsersProfile($userId);
    break;
  default:        
    User_account();
    break;
}


//display user's account
function User_account() {

  global $database, $my, $mosConfig_live_site, $mosConfig_absolute_path, $Itemid;
  $user_id = $my->id;
  $doc = JFactory::getDocument();
  if ($user_id) {
?>
    <div id="mod_user_profile">
        <?php
    $joom_user = new JUser();
    $joom_user->load($user_id);
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    JForm::addFormPath($mosConfig_absolute_path . '/components/com_simplemembership/forms');
    $user_profile_form = JForm::getInstance('com_users.user', 'user');
    $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.user', $joom_user));
    $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
    $user_profile_form->bind($joom_user);
    $fieldsets = $user_profile_form->getFieldsets();
    $profile_image = $user_profile_form->getValue('file', 'profile', '');
    HTML_simplemembership::ShowUserProfile($user_profile_form, $fieldsets,
                                            $profile_image,  $joom_user);
    $doc->setTitle( JText::_("COM_SIMPLEMEMBERSHIP_MY_ACCOUNT") );
    
  } else {
    echo  JText::_("COM_SIMPLEMEMBERSHIP_LOGIN_PLEASE") ."!!!";

  }

}


//display user's list
function show_users() {
  global $mosConfig_live_site, $database,$Itemid;
  $mainframe = JFactory::getApplication();
  // Params(menu)
  if (version_compare(JVERSION, '3.0', 'ge')) {
      $menu = new JTableMenu($database);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
  } else {
      $app = JFactory::getApplication();
      $menu = $app->getMenu();
      $params = new JRegistry;
      $params = $menu->getParams( $Itemid );
  }//end

  $where = '';
  $show_users_group = $params->get('show_users_group','-2');
  if(empty($show_users_group))$show_users_group = -2;
  if($show_users_group != -2)
    $where = " WHERE sm_grp.id IN ( " . implode(',',$show_users_group) . " ) ";
  // Get pagination request variables
  $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit',
                                               $mainframe->getCfg('list_limit'), 'int');
  $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

  $database->setQuery("SELECT count(*) FROM #__users as usrs
              LEFT JOIN #__simplemembership_users as sm_usrs ON sm_usrs.fk_users_id = usrs.id
              LEFT JOIN #__simplemembership_groups as sm_grp ON sm_grp.id = sm_usrs.current_gid"
              .$where);
  $total = $database->loadResult();

  echo $database->getErrorMsg();

  $pageNav = new JPagination($total, $limitstart, $limit);
  $query = "SELECT usrs.* FROM #__users as usrs"
            ."\n LEFT JOIN #__simplemembership_users as sm_usrs ON sm_usrs.fk_users_id = usrs.id"
            ."\n LEFT JOIN #__simplemembership_groups as sm_grp ON sm_grp.id = sm_usrs.current_gid"
            .$where
            ."\n ORDER BY usrs.name limit " . $pageNav->limitstart . " ," . $pageNav->limit;
  $database->setQuery($query);
  $list = $database->loadObjectList();
  echo $database->getErrorMsg();
  $query = "SELECT `userid`, `username` FROM #__session WHERE guest = 0 AND client_id = 0";
  $database->setQuery($query);
  $UsersL = array();
  $ListUsersOnline = $database->loadObjectList();
  foreach($ListUsersOnline as $ListUser) {
    $UsersL[] = $ListUser->userid;
  }
  $UsOffline = array();
  if (count($UsersL)>0) {
    $UsOnline[] = array_unique($UsersL);
    unset($UsersL);
    foreach($list as $user) {
      $UserL[] = $user->id;
      for ($i = 0;$i < count($UserL);$i++) {
        if (!in_array($UserL[$i], $UsOnline[0])) {
          $UsersL[] = $UserL[$i];
        }
      }
    }        
  } else {
    $UsOnline[0] = Array();
    $UsOffline[] = Array();
  }
  $HTML_simplemembership = new HTML_simplemembership();
  $HTML_simplemembership->UsersList($list, $pageNav, $UsOnline, $UsOffline);
}


//display user's profile
function ShowUsersProfile($userId) {
  global $database, $mosConfig_live_site, $mosConfig_absolute_path, $user_configuration;
  $doc = JFactory::getDocument();
  $doc->addStyleSheet($mosConfig_live_site . 
    '/components/com_simplemembership/includes/simplemembership.css');
  $database = JFactory::getDBO();
  $user = JFactory::getUser();
  $acl = JFactory::getACL();
  if (!checkAccess_SM($user_configuration['other_profiles'], 'NORECURSE', userGID_SM($user->id), $acl)) {
    echo  JText::_("COM_SIMPLEMEMBERSHIP_HAVE_NOT_RIGHT") ;
    return;
  }
  $user_id = $userId;
  ?>
  <div id="mod_user_profile">
  <?php
  $joom_user = new JUser();
  $joom_user->load($user_id);
  $dispatcher = JDispatcher::getInstance();
  JPluginHelper::importPlugin('user');
  JForm::addFormPath($mosConfig_absolute_path . '/components/com_simplemembership/forms');
  $user_profile_form = JForm::getInstance('com_users.profile', 'registration');  
  $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.module', $joom_user));
  $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
  $user_profile_form->bind($joom_user);
  $fieldsets = $user_profile_form->getFieldsets();
  $profile_image = $user_profile_form->getValue('file', 'profile', '');
  $HTML_simplemembership = new HTML_simplemembership();
  $HTML_simplemembership->ShowUserProfile($user_profile_form, 
                                          $fieldsets, $profile_image, $joom_user);
}


function ers($ers) {
  $contents = "<table cellspacing='0' cellpadding='0' 
                      style='border:1px solid red;' align='center' class='tbErrorMsgGrad'>
                <tr>
                  <td valign='middle' style='padding:3px' valign='middle' class='tbErrorMsg'>
                    <img src='/images/ers.gif'>
                  </td>
                  <td valign='middle' style='color:red;padding:5px;'>";
  foreach($ers as $key => $error) $contents.= "{$error}<br >";
  $contents.= "</td></tr></table><br >";
  return $contents;
}


function fetch($option) {
  global $database, $my, $mosConfig_absolute_path;
  //from openinviter
  $inviter = new OpenInviter();
  $email_providers = $inviter->getPlugins();
  set_time_limit(0);
  //From openinviter
  $ers = array();
  $import_ok = false;
  if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    $email_box = mosGetParam($_REQUEST, 'email_box', '');
    $password_box = mosGetParam($_REQUEST, 'password_box', '');
    $provider_box = mosGetParam($_REQUEST, 'provider_box', '');
    if (empty($email_box)) $ers['email'] =  JText::_("COM_SIMPLEMEMBERSHIP_EMAIL_MISSING") ;
    if (empty($password_box)) $ers['password'] =  JText::_("COM_SIMPLEMEMBERSHIP_PASSWORD_MISSING") ;
    if (empty($provider_box)) $ers['provider'] =  JText::_("COM_SIMPLEMEMBERSHIP_PROVIDER_MISSING") ;
    if (count($ers) == 0) {
      $inviter->startPlugin($provider_box);
      if (!isset($inviter)) $ers['inviter'] =  JText::_("COM_SIMPLEMEMBERSHIP_INVALID_EMAIL_PROVIDER") ;
      elseif (!$inviter->login($email_box, $password_box)) $ers['login'] =  JText::_("COM_SIMPLEMEMBERSHIP_INCORRECT_LOGIN") ;
      elseif (!$contacts = $inviter->getMyContacts()) $ers['contacts'] = _NO_CONTACTS_FETCHED;
      else {
        $inviter->logout();
        $import_ok = true;
      }
    }
  }
  $contents = (count($ers) != 0 ? ers($ers) : '');
  if (!$import_ok) {
    foreach($ers AS $error) $errmsg.= $error . ' ';
    mosRedirect("index.php?option=$option&task=show_invite", $errmsg);
  }
  $str = "";
  if ($import_ok) {
    if (count($contacts) == 0) $contents = _EMPTY_ARRAY;
    else {
      $msg = "";
      foreach($contacts as $email => $name) {
        $name = addslashes($name);
        $query = "INSERT IGNORE INTO #__simplememberships (`invitee_email`,`invitee_name`,
                                                            `invited_by_email`,`msg`) ".
         " VALUES ('" . $database->getEscaped($email) . "','" . $database->getEscaped($name) . "','" .
          $database->getEscaped($YOUR_EMAIL) . "','" . $database->getEscaped($msg) . "')";
        $database->setQuery($query);
        $database->query();
        if ($database->getErrorNum()) {
          echo $database->stderr();
          return false;
        }
      }
    }
    $query = "SELECT * from #__simplememberships WHERE `invited_by_email`= 
                                    '$YOUR_EMAIL' ORDER BY `invitee_name`";
    $database->setQuery($query);
    $rows = $database->loadObjectList();
    if ($database->getErrorNum()) {
      echo $database->stderr();
      return false;
    }
    $name = '';
    if ($my->id) $name = $my->name;
    $HTML_simplemembership = new HTML_simplemembership();
    $HTML_simplemembership->display($option, $rows, $YOUR_EMAIL, $name, $contents);
  }
}
function invite($cid, $option) {
  global $database, $mosConfig_live_site, $mosConfig_mailfrom, $mosConfig_fromname;
  $query = "SELECT * FROM #__simplemembership_config";
  $database->setQuery($query);
  $database->loadObject($conf);
  if ($database->getErrorNum()) {
    echo $database->stderr();
    return false;
  }
  josSpoofCheck();
  if (!is_array($cid) || count($cid) < 1) {
    echo "<script> alert('Select contact to invite'); window.history.go(-1);</script>\n";
    exit;
  }
  $from_email = mosGetParam($_REQUEST, 'from_email', '');
  $from_name = mosGetParam($_REQUEST, 'name', '');
  $msg = mosGetParam($_REQUEST, 'msg', '');
  $default_msg = mosGetParam($_REQUEST, 'default_mesg', '');
  $msg = $msg . '<br/><br/>' . $default_msg;
  $msgtosave = addslashes($msg);
  $n = 0;
  //Warning: Hardcoded text
  $admin_sub = "JoomInvites sent";
  $admin_msg = "Hi Admin,<br />$from_name ($from_email) has sent invitation to :-<br/><br />";
  mosArrayToInts($cid);
  foreach($cid AS $id) {
    $query = "UPDATE #__simplememberships " .
     " SET `invited_by_name`='$from_name', `to_be_invited`=1, `last_sent`=CURDATE(),
       `msg`='$msgtosave' WHERE `id`=$id";
    $database->setQuery($query);
    $database->query();
    if ($database->getErrorNum()) {
      echo $database->stderr();
      return false;
    }
    $query = "SELECT `invitee_name`, `invitee_email` FROM #__simplememberships WHERE `id`=$id";
    $database->setQuery($query);
    $result = $database->loadObjectList();
    if ($database->getErrorNum()) {
      echo $database->stderr();
      return false;
    }
    $name = $result[0]->invitee_name;
    $to = $result[0]->invitee_email;
    $subject = $conf->custom_subject;
    $default_msg = $conf->msg;
    $subject = preg_replace('/{user}/', $from_name, $subject);
    $subject = preg_replace('/{my_site}/', $mosConfig_live_site, $subject);
    $body = "Hi $name,<br/>";
    $body.= stripslashes($msg);
    $body.= "<br/><br/>$default_msg";
    $mailfrom = ($conf->email_from_user ? $from_email : $mosConfig_mailfrom);
    $fromname = ($conf->email_from_user ? $from_name : $mosConfig_fromname);
    mosMail($mailfrom, $fromname, $to, $subject, $body, 1, NULL, NULL, null, $from_email, $from_name);
    $admin_msg.= "$name ($to).<br/>";
    $n++;
  }
  if ($conf->bcc_admin) {
    mosMail($mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_mailfrom, $admin_sub, $admin_msg, 1);
  }
  //Warning: Hardcoded text
  mosRedirect("index.php?option=$option&task=congretulation",
               "Successfully invited $n contacts, invite more friends at any time.");
}
function show_invite($option) {
  $HTML_simplemembership = new HTML_simplemembership();
  $HTML_simplemembership->front($option);
}
function congretulation($option) {
  $HTML_simplemembership = new HTML_simplemembership();
  $HTML_simplemembership->congretulation($option);
}
function activate() {
  global $database, $user_configuration,$mainframe,$mosConfig_mailfrom;
  $msg_body = $user_configuration['activationmsg'];
  if (array_key_exists('activation', $_GET)) {
    $activation =  JRequest::getVar('activation');
    $query = "select * from #__users where activation='$activation'";
    //$query = "select * from #__users ";
    $database->setQuery($query);
    $user_res_tmp = $database->loadObjectList();
    if (array_key_exists(0, $user_res_tmp)) {
      $user_res = $user_res_tmp[0];
      $uid = $user_res->id;
      $query = "select * from #__simplemembership_users where fk_users_id='$uid'";
      $database->setQuery($query);
      $adv_user_tmp = $database->loadObjectList();
      $adv_user = $adv_user_tmp[0];
      $ug_id = $adv_user->want_gid;
      $query = "update #__users set block='0' where id='$uid'";
      $database->setQuery($query);
      $database->query();
      // $query = "update #__users set activation='' where id='$uid'";
      // $database->setQuery($query);
      // $database->query();
      $query = "update #__simplemembership_users set block='0' where fk_users_id='$uid'";
      $database->setQuery($query);
      $database->query();
      $add_text = '';
      if ($ug_id != 0) {
      $query = "SELECT * FROM #__simplemembership_groups where id='$ug_id'";
        $database->setQuery($query);
        $group = $database->loadObjectList();
        $groupx = $group[0];
        if ($groupx->auto_approve == 1) {
          // Get the dispatcher and load the users plugins.
          $dispatcher = JDispatcher::getInstance();
          JPluginHelper::importPlugin('user');
          $user = new JUser();
          $query = "SELECT ug.id FROM #__usergroups AS ug
                  LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id 
                  WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" .
                     $adv_user->id . ")";
          $database->setQuery($query);
          if (version_compare(JVERSION, '3.0.0', 'ge')) {
            $want_groups = $database->loadColumn();
          } else {
            $want_groups = $database->loadResultArray();
          }
          $query = "SELECT ug.title FROM #__usergroups AS ug"
              . "\n LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id "
              . "\n WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" .
                 $adv_user->id . ")";
          $database->setQuery($query);
          $want_groups_name = $database->loadColumn();
          $want_groups_name = implode(",", $want_groups_name);
          echo $database->getErrorMsg();
          $want_groups = array_combine($want_groups, $want_groups);
          $user->load($adv_user->fk_users_id);
          $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
          $user->groups = $want_groups; 
          $user->usertype = $want_groups_name;
          $user->block = 0;
          $user->activation = 0;
  /*---------------------------------------start day_last in groups-----------------------------------*/
          $query = "SELECT * FROM #__simplemembership_groups AS sg
              WHERE sg.id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" . $adv_user->id . ")";
          $database->setQuery($query);
          $usr_group = $database->loadObjectList();
          $usr_group=$usr_group['0'];
          $current_gid = $usr_group->id;
  /*---------------------------------------end day_last in groups-------------------------------------*/
          $user->save();
          $query="UPDATE #__simplemembership_users SET expire_date='".expireDate($usr_group)."',
                                                      current_gid='".$usr_group->id."',
                                                      current_gname='".$usr_group->name."',
                                                      approved = 1,
                                                      last_approved=now()
                  WHERE #__simplemembership_users.fk_users_id='".$adv_user->fk_users_id."'";
          $database->setQuery($query);
          $database->query();
          $sql = "SELECT id FROM #__simplemembership_orders WHERE fk_sm_users_id = '".$adv_user->id."'";
          $database->setQuery($sql);
          $order_id = $database->loadResult();
          $txn_type = 'Approved by link';
          $sql = "INSERT INTO `#__simplemembership_orders_details`(fk_order_id, fk_sm_users_id, 
                                fk_sm_users_name, fk_sm_users_email,
                                order_date ,fk_group_id, txn_type)
                        VALUES ('".$order_id."',
                                '".$adv_user->id."',
                                '".$adv_user->name."',
                                '".$adv_user->email."',
                                now(),
                                '".$current_gid."',
                                '".$txn_type."')";
          $database->setQuery($sql);
          $database->query();
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
            JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'],
             $adv_user->email, 'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
        } else {
            $a = JFactory::getMailer();
            $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], 
              $adv_user->email, 'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
        }
          $add_text =  JText::_("COM_SIMPLEMEMBERSHIP_YOU_ACCOUNT_ACTIVATED") ;
        } else if ($groupx->auto_approve == 2) {
          if ($groupx->product_name != '') {
            $add_text.= '<br />'. JText::_("COM_SIMPLEMEMBERSHIP_NEED_BUY_PRODUCR") .'(' . $groupx->product_name . ') ';
          }
          if ($groupx->link != '') {
            $add_text.=  JText::_("COM_SIMPLEMEMBERSHIP_AND_FOLLOW") .' <a href="' . $groupx->link .
               '"><strong>link</strong></a> ';
          }
        } else if ($groupx->auto_approve == 0) {
          if ($groupx->product_name != '') {
            $add_text.= '<br />'. JText::_("COM_SIMPLEMEMBERSHIP_NEED_BUY_PRODUCR") .'(' . $groupx->product_name . ') ';
          }
          if ($groupx->link != '') {
            $add_text.=  JText::_("COM_SIMPLEMEMBERSHIP_AND_FOLLOW") .' <a href="' . $groupx->link . 
              '"><strong>link</strong></a> ';
          }
          $add_text.=  JText::_("COM_SIMPLEMEMBERSHIP_AFTER_CHECK_ADMIN_WILL_APPROVE_YOU") ;
        }
      } else{
          // Get the dispatcher and load the users plugins.
          $dispatcher = JDispatcher::getInstance();
          JPluginHelper::importPlugin('user');
          $user = new JUser();
          $user->load($uid);
          $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
          $user->activation = 0;     
          $user->save();   
      }
      $msg = '<div class="componentheading">'. JText::_("COM_SIMPLEMEMBERSHIP_ACTIVATION_COMPLETE") .
        '!</div><div class="message">' . $msg_body . $add_text . '</div>
        <br/>
        <a href="index.php?option=com_simplemembership&task=show_invite">'.
         JText::_("COM_SIMPLEMEMBERSHIP_INVATE_FRIENDS") .'</a>';
      } else $msg = '<div class="componentheading">'.
               JText::_("COM_SIMPLEMEMBERSHIP_ACTIVATION_NOT_COMPLETE") .'!</div><div class="message">'.
               JText::_("COM_SIMPLEMEMBERSHIP_INVALIDE_CODE") .'.</div>';
  } else $msg = '<div class="componentheading">'.
           JText::_("COM_SIMPLEMEMBERSHIP_ACTIVATION_NOT_COMPLETE") .'!</div><div class="message">'.
           JText::_("COM_SIMPLEMEMBERSHIP_INVALIDE_ACTIVATION_LINK") .'.</div>';
    $mainframe->enqueueMessage($msg);
    mosRedirect("index.php");   
}


function update_user() {
  if(JRequest::getVar('password1')===JRequest::getVar('password2')){
    global $database, $my, $user_configuration, $mainframe, $mosConfig_live_site,$mosConfig_mailfrom;
    $activate = md5(time());
    $activation_link = '<a href="' . $mosConfig_live_site .
    '/index.php?option=com_simplemembership&task=activate&activation=' . $activate . '">Activation link</a>';
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
    if ($_POST['name'] == '' || $_POST['email1'] == '' || $_POST['username'] == '') {
      $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_MUST_BE_FILLED") , 'error');
      mosRedirect("index.php?option=com_simplemembership");
    }
    $bid = $my->id;
    $want_gid = (int)$_POST['wanted_gid'];
    $user = new JUser();
    $query = "SELECT * from #__simplemembership_users where fk_users_id='$bid'";
    $database->setQuery($query);
    $sm_user = $database->loadObject();
    $user->load($sm_user->fk_users_id);
    if ($want_gid != -1 && $want_gid != -2) {
      $query = "select * from #__simplemembership_groups where id='$want_gid'";
      $database->setQuery($query);
      $group = $database->loadObjectList();
      $group = $group[0];
    }
    if (isset($_POST['password1'])) $_POST['password'] = $_POST['password1'];
    $user->bind($_POST);
    if($want_gid != -3){
      if ($want_gid == -2 || $want_gid == -1) {
        $user->groups = $default_group;
        $user->usertype = $default_group_name;
      }else{
        $user->usertype = $group->acl_group;
      }
    }
    $user->name = $_POST['name'];
    $user->email = $_POST['email1'];
    $user->username = $_POST['username'];
    $user->registerDate = date('Y-m-d h:i:s');
    $user->activation = $activate;
    if ($user->save()) {
      $al_user = new mos_alUser($database);
      $al_user->load($sm_user->id);
      $al_user->bind($_POST);
      $al_user->fk_users_id = $user->id;
      $current_gid = $al_user->current_gid;
      if($want_gid != -3){
        if($current_gid == $want_gid){
            $al_user->current_gid = $want_gid;
            $al_user->current_gname = $group->name;
            $al_user->approved = 1;
        }else{
          if ($want_gid != -1) {
            $query = "select * from #__simplemembership_groups where id='$want_gid'";
            $database->setQuery($query);
            $group = $database->loadObjectList();
            $group = $group[0];
            $al_user->want_gid = $group->id;
            $al_user->want_gname = $group->name;
            $al_user->expire_date = 0;
            $al_user->approved = 0;
          } else {
            $al_user->current_gid = -2;
            $al_user->current_gname = 'Default';
            $al_user->expire_date = 0;
            $al_user->approved = 1;
            $al_user->last_approved = date("Y-m-d H:i:s");
          }
        }
      }else{
        $al_user->expire_date = 0;
        $al_user->current_gid = -2;
        $al_user->current_gname = 'Default';
        $user->expire_date = 0;
        $al_user->approved = 1;
        $al_user->block = $user->block;
      }
      $is_created = $al_user->store();
      if($want_gid != -3){
        if ($want_gid != -1 && $want_gid != -2) {
          $order_id = checkOrder($al_user->id);//create order for new user
          $sql = "UPDATE  #__simplemembership_orders SET order_date = 'now()',
                                                        fk_group_id='".$al_user->want_gid."',
                                                        status = 'P',
                                                        order_price = 0
                  WHERE id = '".$order_id."'";
          $database->setQuery($sql);
          $database->query();
          $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id,order_date, 
                                          fk_sm_users_name, fk_sm_users_email,status,fk_group_id,txn_type)
                  VALUES ('".$order_id."','".$al_user->id."',now(),'".$al_user->name."',
                          '".$al_user->email."','Pending',
                          '".$al_user->want_gid."','".'User updated.(Set group '.$al_user->want_gname.')'."')";
          $database->setQuery($sql);
          $database->query();
        }else{
          $order_id = checkOrder($al_user->id);//create order for new user
          $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id,
                                              fk_sm_users_name, fk_sm_users_email,order_date,txn_type)
                  VALUES ('".$order_id."','".$al_user->id."',
                             '".$al_user->name."', '".$al_user->email."',
                             now(),'User updated.(Set default group)')";
          $database->setQuery($sql);
          $database->query();
        }
      }
      if($current_gid != $group->id || ($current_gid == -2 && $want_gid == -1) || $al_user->approved = 0){
        if ($is_created && isset($group) && $group->auto_approve == 1 ){
          $user_acc_create_msg = JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_LINK") . $activation_link . " .\n" . $add_text;
          if (version_compare(JVERSION, '3.0.0', 'lt')) {
            JUTility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'],
                                 $al_user->email, 'account update',
                                 $user_acc_create_msg, 1);
          } else {
            $a = JFactory::getMailer();
            $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'],
                        $al_user->email, 'account update', $user_acc_create_msg, 1);
          }
        }
        if ($is_created && isset($group) && $group->auto_approve == 0 )
          $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_UPDATE_ACTIVATE_MANUAL") );
        if ($is_created && isset($group) && $group->auto_approve == 1 )
          $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_UPDATE_ACTIVATE_BY_LINK") );
        if(!empty($group->price) && !empty($group->currency_code) 
            && $group->auto_approve == 2 || !empty($group->price) && $group->auto_approve == 0){
            mosRedirect("index.php?option=com_simplemembership&task=user_prolong&gid=".$group->id);
        }
      }else{
        $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_UPDATE_ALLREADY_IN_GROUP_MESSAGE") );
      }
      $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_USER_UPDATED") );
      mosRedirect("index.php?option=com_simplemembership&task=accdetail");
    }
  }else{
    echo"<script> alert('". JText::_("COM_SIMPLEMEMBERSHIP_PASSWORDS_DO_NOT_MATCH") ."'); window.history.go(-1);</script>\n";;
  }
}


function buyGroup($data) {
  return;
}
   

function user_prolong() {
  global $database, $my, $user_configuration, $mainframe, $mosConfig_live_site, $mosConfig_mailfrom;
  $current_user = JFactory::getUser();
  
  if( empty($current_user->email)) {  
    header("Location: index.php?option=com_simplemembership&task=getMail&msg=".
       JText::_("COM_SIMPLEMEMBERSHIP_LOGIN_FIRST") ."!!!");
  }
  if( trim($current_user->email) != trim(JRequest::getVar('email1'))  ) {  
    header("Location: index.php?option=com_simplemembership&task=getMail&msg=".
       JText::_("COM_SIMPLEMEMBERSHIP_TYPE_CORRECT_EMAIL") ."!!!!");
  }
  
  
  $em = (!empty($current_user->email))? $current_user->email : JRequest::getVar('email1');
  if (isset($em)) {
    $sql = "SELECT * FROM #__simplemembership_users WHERE email='".$em."'";
    $database->setQuery($sql);
    $userId = $database->loadObjectList();


    if(!empty($userId[0]->id) ) {

      $gid = JRequest::getVar('gid');
      $query = "SELECT * FROM #__simplemembership_groups where published='1' 
                AND id ='".$gid."' ";
      $database->setQuery($query);
      $pr_lis = $database->loadObjectList();

      if($current_user->id && !empty($pr_lis['0']->price) ){
        $order_id = checkOrder($userId[0]->id,$gid);
        $sql = "UPDATE #__simplemembership_orders SET order_date = now(),
                      fk_group_id = '".$gid."',
                      status = 'P'
                WHERE fk_sm_users_id = '".$userId[0]->id."'";
        $database->setQuery($sql);
        $database->query();
        $data = array('userIdOrder'=>$userId[0]->id, 'OrderID'=>$order_id );
        $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id, order_date,
                                             fk_sm_users_name,fk_sm_users_email, fk_group_id, status,txn_type)"
            . "\n VALUES ('".$order_id."','".$userId[0]->id."',now(),'".$userId[0]->name."',
                          '".$userId[0]->email."', '".$gid."', 'Pending','Clicked buy link in acc. details')";
        $database->setQuery($sql);
        $database->query();
        buyGroup($data);
      }else{
        if (array_key_exists('gid', $_REQUEST)) {
          $gid = JRequest::getVar('gid');
          $al_user = new mos_alUser($database);
          $al_user->want_gid = $want_gid = $gid;
            /////////////////////////////    start register user's new group   //////////////////	
          global $database, $my, $user_configuration, $mainframe, $mosConfig_live_site;
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
          $bid = $my->id;
          $dispatcher = JDispatcher::getInstance();
          JPluginHelper::importPlugin('user');
          $user = new JUser();
          $query = "SELECT * from #__simplemembership_users where email='".$em."'";
          $database->setQuery($query);
          $sm_user = $database->loadObject();
          $user->load($sm_user->fk_users_id);
          $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
          if ($want_gid != -1 && $want_gid != -2) {
            $query = "select * from #__simplemembership_groups where id='$want_gid'";
            $database->setQuery($query);
            $group = $database->loadObjectList();
            $group = $group[0];
          }
          $user->bind($_POST);
          $user->name = $_POST['name'];
          $user->email = $_POST['email1'];
          $user->username = $_POST['username'];
          $user->registerDate = date('Y-m-d h:i:s');
          if ($want_gid == -2 || $want_gid == -1) {
            $user->usertype = $default_group_name;
          }else{
            $user->usertype = $group->acl_group;
          }
          if(isset($group) && $group->auto_approve == 1){
            $activate = md5(time());
            $activation_link = '<a href="' . $mosConfig_live_site .
             '/index.php?option=com_simplemembership&task=activate&activation=' . $activate 
             . '">Activation link</a>';
            $user->activation = $activate;
          }
          if ($user->save()) {
            $al_user->load($sm_user->id);
            $al_user->bind($_POST);
            $al_user->fk_users_id = $user->id;
            $current_gid = $al_user->current_gid;
            if($current_gid == $want_gid){
                $al_user->current_gid = $want_gid;
                $al_user->current_gname = $group->name;
                $al_user->approved = 1;
            }else{
              if ($want_gid != -1) {
                $query = "select * from #__simplemembership_groups where id='$want_gid'";
                $database->setQuery($query);
                $group = $database->loadObjectList();
                $group = $group[0];
                $al_user->want_gid = $group->id;
                $al_user->want_gname = $group->name;
                $al_user->expire_date = 0;
                $al_user->approved = 0;
              } else {
                $default_group= implode(",", $default_group);
                $al_user->current_gid = -2;
                $al_user->current_gname = 'Default';
                $al_user->expire_date = 0;
                $al_user->approved = 1;
                $al_user->last_approved = date("Y-m-d H:i:s");
              }
            }
            $al_user->store();
            if ($want_gid != -1 && $want_gid != -2) {
              $order_id = checkOrder($al_user->id);//create order for new user
              $sql = "UPDATE  #__simplemembership_orders SET order_date = 'now()',
                                                            fk_group_id='".$al_user->want_gid."',
                                                            status = 'P'
                      WHERE id = '".$order_id."'";
              $database->setQuery($sql);
              $database->query();
              $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id, 
                        fk_sm_users_name, fk_sm_users_email,order_date,status,fk_group_id,txn_type)
                      VALUES ('".$order_id."','".$al_user->id."','".$al_user->name."',
                              '".$al_user->email."', now(),'Pending',
                              '".$al_user->want_gid."','User updated.(Set not default group)')";
              $database->setQuery($sql);
              $database->query();
            }else{
              $order_id = checkOrder($al_user->id);//create order for new user
              $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id,
                                                  fk_sm_users_name, fk_sm_users_email,order_date,txn_type)
                      VALUES ('".$order_id."','".$al_user->id."',
                                 '".$al_user->name."', '".$al_user->email."', now(),
                                 'User updated.(Set default group)')";
              $database->setQuery($sql);
              $database->query();
            }
            if($current_gid == $want_gid || ($current_gid == -2 && $want_gid == -1)){
              $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_UPDATE_ALLREADY_IN_GROUP_MESSAGE") );  
              return;
            }            
            if (isset($group) && $group->auto_approve == 1 ){
              $user_msg =  JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_LINK") . $activation_link;
              if (version_compare(JVERSION, '3.0.0', 'lt')) {
                JUTility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $al_user->email,
                                   JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $user_msg, 1);
              } else {
                $a = JFactory::getMailer();
                $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], 
                            $al_user->email, JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $user_msg, 1);
              }
              $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_PROLONG_BY_LINK") );
            }
            $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_USER_UPDATED") );
          } else {
            $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_USER_CAN_NOT_UPDATED") );
          }
        }
////////////////////////////////////////////    end register user's new group   ///////////////////////		
        $order_id = checkOrder($userId[0]->id,$gid);
        $sql = "UPDATE #__simplemembership_orders 
                SET order_date = now(),
                    fk_group_id = '".$gid."',
                    status = 'P'
                WHERE fk_sm_users_id = '".$userId[0]->id."'";
        $database->setQuery($sql);
        $database->query();
        $data = array('userIdOrder'=>$userId[0]->id, 'OrderID'=>$order_id );
        $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,
                    fk_sm_users_id,
                    fk_sm_users_name,
                    fk_sm_users_email,
                    order_date,
                    fk_group_id,
                    status)"
          ."\n VALUES ('".$order_id."',
                    '".$userId[0]->id."',
                    '".$userId[0]->name."',
                    '".$userId[0]->email."',
                    now(),
                    '".$gid."',
                    'Pending')";
        $database->setQuery($sql);
        $database->query();
        buyGroup($data);
      }        
    }      
  } else {
    $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_USER_NOT_REGISTERED") , 'error');
  }
}


function add_user() {
  
    global $database, $my, $user_configuration, $mainframe, $mosConfig_live_site,
      $mosConfig_absolute_path, $mosConfig_mailfrom;

    $err_msg = "";
    if($_POST['password2'] != $_POST['password1']){
      $err_msg =  JText::_("COM_SIMPLEMEMBERSHIP_PASSWORD_MISMATCH") ;
      $mainframe->enqueueMessage( $err_msg , 'error');
    }
    if($_POST['email1'] != $_POST['email2']){
      $err_msg =  JText::_("COM_SIMPLEMEMBERSHIP_EMAIL_MISMATCH") ;
      $mainframe->enqueueMessage( $err_msg , 'error');
    }
    if($err_msg != "") {
      //if error in before registration
      
      //// Default group
      $default_group = $user_configuration['default_group'];
      if (empty($default_group)) {
          $default_group = '2';
          $default_group_name = 'Registered';
      } else {
          $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( $default_group )";
          $database->setQuery($query);
          if (version_compare(JVERSION, '3.0.0', 'ge')) {
              $default_group_name = $database->loadColumn();
          } else {
              $default_group_name = $database->loadResultArray();
          }
          $database->getErrorMsg();
          $default_group_name = implode(",", $default_group_name);
          //$default_group=array_combine($default_group,$default_group);
          $f = array();
          $s = explode(',', $user_configuration['default_group']);
          for ($i = 0;$i < count($s);$i++) {
              $f[] = mosHTML::makeOption($s[$i]);
          }
          $default_group = $f;
      }
      $options = array();
      $query = "select * from #__simplemembership_groups where published='1'";
      $database->setQuery($query);
      $pr_lis = $database->loadObjectList();
      $preregister = stripslashes($user_configuration['preregister']);
      if (array_key_exists(0, $pr_lis)) $options[] = mosHTML::makeOption(-1, $default_group_name .
         ' (Free user)');
      $group_selected = JRequest::getVar('group_selected','');
      foreach($pr_lis as $item) {
          $details = (!empty($item->price) && !empty($item->currency_code))?
                          ' (Cost: '.$item->price.$item->currency_code.')':' (Free group)' ;
          $options[] = mosHTML::makeOption($item->id, $item->name.$details);
          if($group_selected == $item->name)$group_selected = $item->id;
      }
      if (array_key_exists(0, $options)) $olist = mosHTML::selectList($options, 'gid', '',
         'value', 'text',$group_selected);
      else $olist = '';    

      
      //add profile form
      // Get the dispatcher and load the users plugins.
      $dispatcher = JDispatcher::getInstance();
      JPluginHelper::importPlugin('user');
      JForm::addFormPath($mosConfig_absolute_path . '/components/com_users/models/forms');
      $user_profile_form = JForm::getInstance('com_users.registration', 'registration');
      
      $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $_POST ));
      $user_profile_form->bind($_POST);
      $ToSLink = $dispatcher->trigger('TermsOfService');
      $HTML_simplemembership = new HTML_simplemembership();
      $HTML_simplemembership->advregister_form($olist, $preregister, $user_profile_form, $ToSLink);
      return ;
    }   
  
  
  $activate = md5(time());
  jimport( 'joomla.application.component.helper' );
  $params = JComponentHelper::getParams("com_users") ;



  $is_self = $params->get("useractivation");
  
//   print_r($params);exit;

  $activation_link = '<a href="' . $mosConfig_live_site .
   '/index.php?option=com_simplemembership&task=activate&activation=' . $activate . '">'
    . JText::_("COM_SIMPLEMEMBERSHIP_ACTIVATION_LINK") .'</a>';
  if($_POST['email1'] != $_POST['email2']){
    echo "<script> alert('". JText::_("COM_SIMPLEMEMBERSHIP_EMAIL_MISMATCH") .".'); window.history.go(-1);</script>\n";
    exit;
  }
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
  }
  
  $user = new JUser();
  if (isset($_POST['password1'])) 
    $_POST['password'] = $_POST['password1'];
  $user->bind($_POST);
  $user->name = $_POST['name'];
  $user->username = $_POST['username'];
  $user->email = $_POST['email1'];
  $user->groups = $default_group;
  $user->usertype = $default_group_name;
  $user->block = 1;
  $user->activation = $activate;
  $user->registerDate = date('Y-m-d h:i:s');
  $postregister_msg = '';

  if ($user->save()) {
    $al_user = new mos_alUser($database);
    $al_user->bind($_POST);
    $al_user->email = $user->email;
    $al_user->fk_users_id = $user->id;
    if (array_key_exists('gid', $_POST) && $_POST['gid'] != -1) {
      $query = "SELECT * FROM #__simplemembership_groups WHERE id='" . JRequest::getVar('gid') . "'";
      $database->setQuery($query);
      $group = $database->loadObjectList();
      $group = $group[0];
      $al_user->current_gid = -2;
      $al_user->current_gname = 'Default';
      $al_user->want_gid = JRequest::getVar('gid') ;
      $al_user->want_gname = $group->name;
      $al_user->expire_date = 0;
      $al_user->approved = 0;
      $add_text = ($group->link != '') ? 'And please buy "' .
      $group->product_name . '" from here: <a href"' . $group->link . '">Product</a>' : '';
      $admin_acc_create_msg = stripslashes($user_configuration['admin_created_msg']) . "<br>" .
                               JText::_("COM_SIMPLEMEMBERSHIP_USEREDIT_USERNAME") . ': ' . JRequest::getVar('username') . "<br>" .
                               JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SIMPLEMEMBERSHIPGROUP") . ':' . $al_user->want_gid;
      $postregister_msg = $group->notes;
    } else {

      $al_user->expire_date = 0;
      $al_user->current_gid = -2;
      $al_user->current_gname = 'Default';
      $user->expire_date = 0;
      $al_user->last_approved = date("Y-m-d H:i:s");
      //$al_user->approved = 1;
      $add_text = '';
      $admin_acc_create_msg = stripslashes($user_configuration['admin_created_msg']) . "<br>"
       .  JText::_("COM_SIMPLEMEMBERSHIP_USEREDIT_USERNAME")  . ': ' . JRequest::getVar('username') . "<br>" .
         JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SIMPLEMEMBERSHIPGROUP")  . ' ' . $default_group_name;
      $postregister_msg = '';
    }
    $al_user->block = 1;
    $is_created = $al_user->store();

    if (array_key_exists('gid', $_POST) && $_POST['gid'] != -1) {
      $order_id = checkOrder($al_user->id);//create order for new user
      $sql = "UPDATE  #__simplemembership_orders SET order_date = 'now()',
                                                    fk_group_id='".$al_user->want_gid."',
                                                    status = 'P'
              WHERE id = '".$order_id."'";
      $database->setQuery($sql);
      $database->query();
      $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id, 
                  fk_sm_users_name, fk_sm_users_email,order_date,status,fk_group_id,txn_type)
              VALUES ('".$order_id."','".$al_user->id."',now(),'".$al_user->name."',
                      '".$al_user->email."', 'Pending',
                      '".$al_user->want_gid."','User created.(Not default group)')";
      $database->setQuery($sql);
      $database->query();
    }else{
      $order_id = checkOrder($al_user->id);//create order for new user
      $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id,
                  fk_sm_users_name, fk_sm_users_email,order_date,fk_group_id,txn_type)
              VALUES ('".$order_id."','".$al_user->id."',
                         '".$al_user->name."', '".$al_user->email."', now(),-2,'User created.')";
      $database->setQuery($sql);
      $database->query();
    }

    $user_acc_create_msg = '';
    if ($is_created && isset($group) && $group->auto_approve == 0 )
      $user_acc_create_msg = stripslashes($user_configuration['user_created_msg']) .
      "\n" . JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_MANUAL") ;
    if ($is_created && isset($group) && $group->auto_approve == 1 )
      $user_acc_create_msg = stripslashes($user_configuration['user_created_msg']) .
      "\n" . JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_LINK") . $activation_link . " .\n" . $add_text;
    if ($is_created && isset($group) && $group->auto_approve == 2 )
      $user_acc_create_msg = stripslashes($user_configuration['user_created_msg']) .
      "\n" . JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_BUY") ;
    if ($is_created && $user_acc_create_msg == ''){
        $user_acc_create_msg = stripslashes($user_configuration['user_created_msg']) ;
        if($is_self ==1 )
          $user_acc_create_msg .= "\n" . JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_LINK") . $activation_link . " .\n";
    }

    // bch
    if($_POST['password1'] && $params['sendpassword'])
    {
        $user_acc_create_msg .= "\n" . JText::_("COM_SIMPLEMEMBERSHIP_USEREDIT_PASSWORD") ." ". $_POST['password1']. " .\n";
    }

    if (version_compare(JVERSION, '3.0.0', 'lt')) {
      if ($user_configuration['acl_group_set_email'] 
            &&  !empty($user_configuration['useradd_notification_email']))
        JUTility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'],
        $user_configuration['useradd_notification_email'], JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $admin_acc_create_msg, 1);
        JUTility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $al_user->email,
                             JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $user_acc_create_msg, 1);
    } else {
      $a = JFactory::getMailer();
      if ( $user_configuration['acl_group_set_email'] &&
        !empty($user_configuration['useradd_notification_email'])){
          $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], 
                      $user_configuration['useradd_notification_email'],
                      JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $admin_acc_create_msg, 1);
      }
      $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], 
                  $al_user->email, JText::_("COM_SIMPLEMEMBERSHIP_ACCOUNT_CREATION"), $user_acc_create_msg, 1);
    }

//               print_r("2222222333333332222");exit;
    if ($is_created && $postregister_msg == '') 
      $mainframe->enqueueMessage(stripslashes($user_configuration['user_created_msg']));
    elseif ($postregister_msg != '') 
      $mainframe->enqueueMessage($postregister_msg);
    if ($is_created && isset($group) && $group->auto_approve == 0 )
      $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_MANUAL") );
    else if ($is_created && isset($group) && $group->auto_approve == 1 )
      $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_BY_LINK") );
    else if ($is_created && isset($group) && $group->auto_approve == 2 ){
      $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_BUY") );
      $data = array('userIdOrder'=>$al_user->id, 'OrderID'=>$order_id );
      buyGroup($data);
    }else if($is_self ==1 ) $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_ACC_ACTIVATE_BY_LINK") );
  } else {
    $mainframe->enqueueMessage( JText::_("COM_SIMPLEMEMBERSHIP_USER_NOT_REGISTERED") . $user->getError(), 'error');
  }   


}

function accdetail($option) {
  global $database, $my, $user_configuration,$mosConfig_live_site, $mosConfig_absolute_path;
  $user_id = $my->id;
  if ($user_id) {
    $jom_id = $my->id;
    $query = "select id from #__simplemembership_users where fk_users_id='$jom_id'";
    $database->setQuery($query);
    $bid = $database->loadResult();
    // default group end
    $default_group = $user_configuration['default_group'];
    if (empty($default_group)) {
      $default_group = array('2' => '2');
      $default_group_name = 'Registered';
    } else {
      $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
      $database->setQuery($query);
      if (version_compare(JVERSION, '3.0.0', 'ge')) {
        $default_group_name = $database->loadColumn();
      } else {
        $default_group_name = $database->loadResultArray();
      }
      $default_group_name = implode(",", $default_group_name);
      //$default_group=array_combine($default_group,$default_group);
      $f = array();
      $s = explode(',', $user_configuration['default_group']);
      for ($i = 0;$i < count($s);$i++) {
        $f[] = mosHTML::makeOption($s[$i]);
      }
      $default_group = $f;
    }
    $user = new mos_alUser($database);
    $user->load(intval($bid));
    $options = array();
    $query = "SELECT * FROM #__simplemembership_groups WHERE published='1'";
    $database->setQuery($query);
    $pr_lis = $database->loadObjectList();
    $joom_user = new JUser();
    $joom_user->load($user->fk_users_id);
    if($user->approved == 0)$user->expire_date = 0;
    $joom_user->params = json_decode($joom_user->params, true);
    if($user->fk_users_id){
      $query = "SELECT * FROM #__user_usergroup_map WHERE user_id=".$user->fk_users_id.
          " AND group_id = 8 OR group_id = 7";
      $database->setQuery($query);
      $result = $database->loadObjectList();
    }
    $is_admin = false;
    if(isset($result['0']) && !empty($result['0']) && ($user->current_gid == -2)){
      $is_admin = true;
      $options[] = mosHTML::makeOption(-3,'Current(Administration)');
    }
    $options[] = mosHTML::makeOption(-1, 'Default('.$default_group_name.')');
    $group_selected = JRequest::getVar('group_selected','');
    foreach($pr_lis as $item) {
      $details = (!empty($item->price) && !empty($item->currency_code))?
                      ' (Cost: '.$item->price.$item->currency_code.')':' (Free group)' ;
      $options[] = mosHTML::makeOption($item->id, $item->name.$details);
      if($group_selected == $item->name)
        $group_selected = $item->id;
    }
    if($is_admin)
      $user->current_gid = -3;
    if (array_key_exists(0, $options)){
      $olist = mosHTML::selectList($options, 'wanted_gid', '', 'value', 'text',
      (empty($group_selected) && $user->approved==1)?$user->current_gid:$group_selected);
    }else{
      $olist = 'Default('.$default_group_name . ')';
    }
    $last_approved_date = strtotime($user->last_approved);
    $cur_date = date_create(date("Y-m-d H:i:s"));
    $exp_date = date_create($user->expire_date);
    $interval='';
    $want_group ='';
    if($user->want_gid > 0){
      $query = "SELECT * FROM #__simplemembership_groups WHERE id=".$user->want_gid."";
      $database->setQuery($query);
      $want_group = $database->loadObjectList();
      $want_group = $want_group['0'];
    }
    if($exp_date)
      $interval = date_diff($exp_date,$cur_date);
    ///if current user in default group
    if ((!is_object($interval) && empty($interval->invert) || is_object($interval) && $interval->invert == 0) 
      && !empty($want_group) ) {
      if ($user->approved == 0
        && $want_group->auto_approve == 2 && !empty($want_group->price)){
          $msg = '<br/>'. JText::_("COM_SIMPLEMEMBERSHIP_YOU_ARE_EXPIRED_FOR_GROUP") .' <b>' . $want_group->name .
           '</b>, '. JText::_("COM_SIMPLEMEMBERSHIP_PLEASE_BUY_THIS_PRODUCT") .' <a href="' . $mosConfig_live_site .
            '/index.php?option=com_simplemembership&task=buyLink'. '">link</a>';
      } elseif((isset($name_of_group) && $name_of_group != '' ) 
                  && $user->approved == 0 && $want_group->auto_approve == 1){
        $msg = '<br/><b>'. JText::_("COM_SIMPLEMEMBERSHIP_WE_SEND_ACTIVATION_LINK") .'!</b>';
      }else if($user->approved == 0 && $want_group->auto_approve == 0 && !empty($want_group->price)){
        $msg = '<br/>'. JText::_("COM_SIMPLEMEMBERSHIP_SELECTED_PAID_GROUP") .'('.$want_group->name.
          ') '. JText::_("COM_SIMPLEMEMBERSHIP_WITH_APPROVE_BY_ADMINISTRATOR") .' <a href="' . $mosConfig_live_site .
            '/index.php?option=com_simplemembership&task=buyLink'. '">link</a>'
            .' <br>'. JText::_("COM_SIMPLEMEMBERSHIP_WAIT_FOR_CONFIRMATION") ;
      }else if($user->approved == 0 && $want_group->auto_approve == 0){
        $msg = '<br/>'. JText::_("COM_SIMPLEMEMBERSHIP_SELECTED_GROUP") .'('.$want_group->name.
        ') '. JText::_("COM_SIMPLEMEMBERSHIP_WITH_APPROVE_BY_ADMINISTRATOR2") ;
      }else {
        $msg = '<br/>'. JText::_("COM_SIMPLEMEMBERSHIP_IN_ONE_OF_THESE_GROUPS") ;
      }
    }
    //if user in not default group and has expire range
    $msg='';
    if (is_object($interval) && $interval->invert == 1 && $user->approved == 1) {
      $msg = '<br/>'.  JText::_("COM_SIMPLEMEMBERSHIP_IN_ONE_OF_THESE_GROUPS_NEXT") ;
      if($interval->format('%y%')>0)
        $msg.=$interval->format('%y%').  JText::_("COM_SIMPLEMEMBERSHIP_YEARS") ;
      if($interval->format('%m%')>0)
        $msg.=$interval->format('%m%').  JText::_("COM_SIMPLEMEMBERSHIP_MONTHS") ;
      if($interval->format('%d%')>0)
        $msg.=$interval->format('%d%').  JText::_("COM_SIMPLEMEMBERSHIP_DAYS") ;
        $msg.=$interval->format('%H%'). JText::_("COM_SIMPLEMEMBERSHIP_HOURES") ;
    }

    // Get the dispatcher and load the users plugins.
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    JForm::addFormPath($mosConfig_absolute_path . '/components/com_simplemembership/forms');
    $user_profile_form = JForm::getInstance('com_users.profile', 'update_user');
    $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $joom_user));
    $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
    $user_profile_form->bind($joom_user);
    $ToSLink = $dispatcher->trigger('TermsOfService');
    $HTML_simplemembership = new HTML_simplemembership();
    $HTML_simplemembership->accdetail($user, $olist, $option, $msg, $user_profile_form, $ToSLink);
  } else {
    header("Location:index.php");
  }
}

function buyLink($option) {
  global $database,$my;
  $userId = $my->id;
  $sql = "SELECT * FROM #__simplemembership_users WHERE fk_users_id = '".$userId."'";
  $database->setQuery($sql);
  $user= $database->loadObjectList();
  $user=$user['0'];
  mosRedirect("index.php?option=$option&task=user_prolong&gid=".$user->want_gid.
              "&email1=".$user->email, $errmsg);
}

function getMail() { 
  if(isset($_GET['msg'])) {
    $error = urldecode(JRequest::getVar('msg'));
    echo "<p style='color:red;'>".$error."</p><br>";
  } 

  $user = JFactory::getUser();
  
  if( !isset($user->email) ){
    echo "<p style='color:red;'>Please login first</p><br>";
    return;
  }
    
  ?>
  <form action="<?php echo sefRelToAbs("index.php?option=com_simplemembership&amp;");?>" method='get'>
    <label><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_SUBSCRIPTION_RENEWAL") ; ?>:<label><br>
    <input id='emailUser' type='text' name='email' value=""><br>
    <input id='emailSubmit' type='submit' value='Continue'>
    <input type='hidden' name='task' value='advregister' >
    <input type='hidden' name='prolong' value='p' >
    <input type='hidden' name='option' value='com_simplemembership' >
  </form>
  <script>

  window.onload = function(){
   var emailSubmit = document.getElementById('emailSubmit');
   if(emailSubmit){
    emailSubmit.onclick = function(event){
      event = event || window.event;
      var eMail = document.getElementById('emailUser').value;
        if(eMail == ""){
          event.preventDefault();
          alert("Write Your Email!!");
        }
      }
    }
  }
  </script>
  <?php
}


function advregister() {
  global $database, $user_configuration, $mosConfig_absolute_path,$mosConfig_live_site;
  $user = JFactory::getUser();

  if( isset($user->email) && (!isset($_GET['prolong']) && !isset($_POST['prolong']) )) {
    User_account();
    return;
  }
    
  if( ( isset($_GET['email']) || isset($_POST['email']) ) 
      && isset($_REQUEST['prolong']) &&  $_REQUEST['prolong'] == 'p' 
      && (isset($user->email) && !empty($user->email)) ) {
    


    if( trim($user->email) != trim(JRequest::getVar('email'))  ) {  
      header("Location: index.php?option=com_simplemembership&task=getMail&msg=".
         JText::_("COM_SIMPLEMEMBERSHIP_TYPE_CORRECT_EMAIL") ."!!!!");
    }
        
    $email = (isset($user->email) && !empty($user->email))? $user->email : JRequest::getVar('email');
    $sql = "SELECT * FROM #__users WHERE email ='".$email."'";
    $database->setQuery($sql);
    $user = $database->loadObjectList();
    if(empty($user)) {  
      header("Location: index.php?option=com_simplemembership&task=getMail&msg=".
         JText::_("COM_SIMPLEMEMBERSHIP_EMAIL_IS_NOT_REGISTERED") ."!!!!");
    }
    //// Default group
    $default_group = $user_configuration['default_group'];
    if (count(explode(',', $default_group)) < 1) {
      $default_group_name = 'Registered';
    } else {
      $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
      $database->setQuery($query);
      $default_group_name = $database->loadColumn();
      $default_group_name = implode(",", $default_group_name);
    }////end Default group

    $options = array();
    $query = "select * from #__simplemembership_groups where published='1'";
    $database->setQuery($query);
    $pr_lis = $database->loadObjectList();
    $preregister = stripslashes($user_configuration['preregister']);
    if (array_key_exists(0, $pr_lis))
      $options[] = mosHTML::makeOption(-1, 'Default('.$default_group_name.')');
    $group_selected = JRequest::getVar('group_selected','');
    foreach($pr_lis as $item) {
      $details = (!empty($item->price) && !empty($item->currency_code))?
                  ' (Cost:'.$item->price.$item->currency_code.')':' (Free group)' ;
      $options[] = mosHTML::makeOption($item->id, $item->name.$details);
      if($group_selected == $item->name){
        $group_selected = $item->id;
        if(empty($item->price) || $item->auto_approve!=2){//redirect if it's free group
            mosRedirect($mosConfig_live_site.
                '/index.php?option=com_simplemembership&task=accdetail&group_selected='.$item->name);
        }
      }
    }
    if (array_key_exists(0, $options)) 
      $olist = mosHTML::selectList($options, 'gid', '', 'value', 'text',$group_selected);
    else $olist = '';
    $HTML_simplemembership = new HTML_simplemembership();
    $HTML_simplemembership->advregister_prolong($olist, $user);
  } else {
    sync_users();
    //// Default group
    $default_group = $user_configuration['default_group'];
    if (empty($default_group)) {
      $default_group = '2';
      $default_group_name = 'Registered';
    } else {
      $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( $default_group )";
      $database->setQuery($query);
      if (version_compare(JVERSION, '3.0.0', 'ge')) {
        $default_group_name = $database->loadColumn();
      } else {
        $default_group_name = $database->loadResultArray();
      }
      $database->getErrorMsg();
      $default_group_name = implode(",", $default_group_name);
      //$default_group=array_combine($default_group,$default_group);
      $f = array();
      $s = explode(',', $user_configuration['default_group']);
      for ($i = 0;$i < count($s);$i++) {
        $f[] = mosHTML::makeOption($s[$i]);
      }
      $default_group = $f;
    }
    $options = array();
    $query = "select * from #__simplemembership_groups where published='1'";
    $database->setQuery($query);
    $pr_lis = $database->loadObjectList();
    $preregister = stripslashes($user_configuration['preregister']);
    if (array_key_exists(0, $pr_lis)) 
      $options[] = mosHTML::makeOption(-1, 'Default('.$default_group_name.')');
    $group_selected = JRequest::getVar('group_selected','');
    foreach($pr_lis as $item) {
      $details = (!empty($item->price) && !empty($item->currency_code))?
                      ' (Cost: '.$item->price.$item->currency_code.')':' (Free group)' ;
      $options[] = mosHTML::makeOption($item->id, $item->name.$details);
      if($group_selected == $item->name)$group_selected = $item->id;
    }
    if (array_key_exists(0, $options)) 
      $olist = mosHTML::selectList($options, 'gid', '', 'value', 'text',$group_selected);
    else $olist = '';
    //add profile form
    // Get the dispatcher and load the users plugins.
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    JForm::addFormPath($mosConfig_absolute_path . '/components/com_users/models/forms');
    $user_profile_form = JForm::getInstance('com_users.registration', 'registration');

    $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $data = array()));
    $ToSLink = $dispatcher->trigger('TermsOfService');

    // print_r($user_profile_form) ; exit ;    
    $HTML_simplemembership = new HTML_simplemembership();
    $HTML_simplemembership->advregister_form($olist, $preregister, $user_profile_form, $ToSLink);
  }
}
//*****************************************************************************

if (!function_exists('secretImage')) {
  function secretImage() {
    $session = JFactory::getSession();
    $pas = $session->get('captcha_keystring', 'default');
    $new_img = new PWImage();
    $new_img->set_show_string($pas);
    $new_img->get_show_image(2.2, array(mt_rand(0, 50), mt_rand(0, 50), mt_rand(0, 50)),
     array(mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255)));
    exit();
  }
}


function checkFolderAcess($folder_list, $current_folder) {
  $pattern = "/^($current_folder,)|(,$current_folder,)|^($current_folder)$|(,$current_folder)$/";
  if (preg_match($pattern, $folder_list)) {
    return true;
  }
  return false;
}
function checkAccess_SM($accessgroupid, $recurse, $usersgroupid, $acl) {
  //parse usergroups
  $tempArr = array();
  $tempArr = explode(',', $accessgroupid);
  for ($i = 0;$i < count($tempArr);$i++) {
    if ($tempArr[$i] == $usersgroupid || $tempArr[$i] == -2) {
      return 1;
    } else if ($recurse == 'RECURSE') {
      return 1;
    }
  }
  return 0;
}
function userGID_SM($oID) {
  $database = JFactory::getDBO();
  if ($oID > 0) {
    $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id  = '" . $oID . "'";
    $database->setQuery($query);
    $gids = $database->loadAssocList();
    if (count($gids) > 0) {
      $ret = '';
      foreach($gids as $gid) {
        if ($ret != "") $ret.= ',';
        $ret.= $gid['group_id'];
      }
      return $ret;
    } else return -2;
  } else return -2;
}


function BeforeEndNotify($option) {
  global $database, $user_configuration, $Itemid, $mosConfig_mailfrom;
  $message_to_owner = array();
  $send_email = 0;
  if (($user_configuration['before_end_notify']) 
        && is_numeric($user_configuration['before_end_notify_days'])) {
    $send_email = 1;
  }
  if ($send_email) {
    $mail_to_admin = explode(",", $user_configuration['before_end_notify_email']);
//    $mail_to_admin = $user_configuration['before_end_notify_email'];
    $query = "SELECT u.id, u.name, u.username, u.email, u.last_approved, "
      . " g.id AS groupId, g.price, g.name AS groupName, "
      . " o.id AS orderId  "
      . " FROM #__simplemembership_users AS u  "
      . " LEFT JOIN #__simplemembership_orders AS o ON  u.id = o.fk_sm_users_id" 
      . " LEFT JOIN #__simplemembership_groups AS g ON  g.id = o.fk_group_id" 
      . " WHERE "
      . "  (TIMESTAMPDIFF(DAY, now(),u.expire_date ) ) = " . $user_configuration['before_end_notify_days']
      . " ;";
    $database->setQuery($query);
    $items = $database->loadObjectList();
    echo $database->getErrorMsg();

    $all_in_one_massageForAdmin = "";
    for($i = 0; $i < count($items); $i++) {

      $massageForAdmin = $user_configuration['admin_created_msg_for_admin'];
      $massageForUser = $user_configuration['admin_created_msg_for_user'];
      
      $massageForUser = str_replace("{username}", $items[$i]->username, $massageForUser);
      $massageForUser = str_replace("{user_email}", $items[$i]->email, $massageForUser);
      $massageForUser = str_replace("{id}", $items[$i]->id, $massageForUser);
      $massageForUser = str_replace("{GroupName}", $items[$i]->groupName, $massageForUser);

      if ( trim($user_configuration['before_end_notify_email']) != "" ) {
          $massageForAdmin = str_replace("{username}", $items[$i]->username, $massageForAdmin);
          $massageForAdmin = str_replace("{user_email}", $items[$i]->email, $massageForAdmin);
          $massageForAdmin = str_replace("{id}", $items[$i]->id, $massageForAdmin);
          $massageForAdmin = str_replace("{GroupName}", $items[$i]->groupName, $massageForAdmin);
          $all_in_one_massageForAdmin .= "<br />" . $massageForAdmin ;
      }
      
      $mail_to_owner = $items[$i]->email;
      if (version_compare(JVERSION, '3.0.0', 'lt')) {
        JUTility::sendMail($mosConfig_mailfrom, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_ON .
           " {$items[$i]->groupName}",
        $mail_to_owner, "Subscription expire Notice On {$items[$i]->groupName}!", $massageForUser, true);
      } else {
        $a = JFactory::getMailer();
        $a->sendMail($mosConfig_mailfrom, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_ON .
          " {$items[$i]->groupName}",
        $mail_to_owner, "Subscription expire Notice On {$items[$i]->groupName}!", $massageForUser, 1);
      }
    }
    if ( trim($all_in_one_massageForAdmin) != "" ) {
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
          JUTility::sendMail($mosConfig_mailfrom, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_FOR ,
          $mail_to_admin, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_FOR , $all_in_one_massageForAdmin, true);
        } else {
          $a = JFactory::getMailer();
          $ret = $a->sendMail($mosConfig_mailfrom, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_FOR ,
          $mail_to_admin, _SIMPLEMEMBERSHIP_SUBSCRIPTION_EXPIRE_NOTICE_FOR , $all_in_one_massageForAdmin, 1);
        }
    }    
  }
}
