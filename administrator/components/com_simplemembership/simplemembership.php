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

defined('_VM_IS_BACKEND') or define('_VM_IS_BACKEND', '1');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
include_once ($mosConfig_absolute_path . '/components/com_simplemembership/compat.joomla1.5.php');
$GLOBALS['database'] = $database;
$mainframe = $GLOBALS['mainframe'] = JFactory::getApplication();
$my = $GLOBALS['my'];
$css = $mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css';
require_once ($mosConfig_absolute_path . '/administrator/components/com_simplemembership/toolbar.simplemembership.php');
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/simplemembership.class.php");
require_once ($mosConfig_absolute_path . "/administrator/components/com_simplemembership/admin.simplemembership.html.php");
if (version_compare(JVERSION, '3.0.0', 'lt')) include_once ($mosConfig_absolute_path . '/libraries/joomla/html/pagination.php');
require_once ($mosConfig_absolute_path . "/administrator/components/com_simplemembership/admin.simplemembership.class.conf.php");
$GLOBALS['user_configuration'] = $user_configuration;
$GLOBALS['my'] = $my;
$GLOBALS['mosConfig_mailfrom'] = $mosConfig_mailfrom;
// load language
$lang_def_en = 0;
$lang = JFactory::getLanguage();
JFactory::getLanguage()->load('com_users', JPATH_SITE, $lang->getTag(), true); //add language for com_user

$GLOBALS['user_configuration'] = $user_configuration;
$GLOBALS['mosConfig_absolute_path'] = $mosConfig_absolute_path;
$GLOBALS['task'] = $task = mosGetParam($_REQUEST, 'task', '');
$GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_simplemembership');
$bid = mosGetParam($_POST, 'bid', array(0));
$section = mosGetParam($_REQUEST, 'section', '');
require_once ($mosConfig_absolute_path . '/components/com_simplemembership/syncexpire.php');
check_users();

if (isset($section) && $section == 'group') {
  switch ($task) {
    case "edit":
      editGroup($option, $bid[0]);
      break;
    case "add":
      editGroup($option, 0);
      break;
    case "cancel":
      cancelGroup();
      break;
    case "deleteOrder":
      deleteOrder();
      break;
    case "updateOrderStatus":
      updateOrderStatus();
      break;     
    case "publish":
      group_publish();
      break;
    case "unpublish":
      group_unpublish();
      break;
    case "save":
      saveGroup();
      break;
    case "remove":
      removeCategories($option, $bid);
      break;
    case "add_acl_group":
      add_acl_group();
      break;
    case "del_acl_group_form":
      del_acl_group_form();
      break;
    case "show":
    default:
      showCategories();
  }
} else {
  switch ($task) {
    case "send_mail_the_end_approve":
      send_mail_the_end_approve();
      break;
    case "deleteOrder":
  		deleteOrder();
      break;
    case "updateOrderStatus":
      updateOrderStatus();
      break;     
    case "override":
      override();
      break;
    case "categories":
      echo "now work $section=='group , this part not work";
      exit;
      mosRedirect("index.php?option=com_simplemembership&section=group");
      break;
    case "save_user":
      saveUser($option, 0);
      break;
    case "add_user":
      editUser($option, 0);
      break;
    case "edit_user":
      editUser($option, array_pop($bid));
      break;
    case "remove":
      removesimplememberships($bid, $option);
      break;
    case "publish":
      publishUsers($bid, 1, $option);
      break;
    case "unpublish":
      publishUsers($bid, 0, $option);
      break;
    case "approve":
      approveUsers($bid, 1, $option);
      break;
    case "unapprove":
      approveUsers($bid, 0, $option);
      break;
    case "cancel":
      cancelsimplemembership($option);
      break;
    case "config":
      configure($option);
      break;
    case "manage_qmodifications":
      manage_qmodifications($option, "");
      break;
    case "updateOrderStatus":
      updateOrderStatus();
      break;
    case "config_save":
      configure_save($option);
      break;
    case "about":
      HTML_simplemembership::about();
      break;
    case "add_acl_group":
      add_acl_group();
      break;
    case "save_newacl":
      save_newacl();
      break;
    case "del_acl_group":
      del_acl_group();
      break;
    case "sync_ajax":
      sync_ajax();
      break;         
    default:
      showUsers($option);
      break;
  }
} //else


class CAT_Utils {
  function categoryArray() {
      global $database;
      // get a list of the menu items
      $query = "SELECT c.*, c.parent_id AS parent" . "\n FROM #__categories c" . "\n WHERE section='com_simplemembership'" . "\n AND published <> -2" . "\n ORDER BY ordering";
      $database->setQuery($query);
      $items = $database->loadObjectList();
      // establish the hierarchy of the menu
      $children = array();
      // first pass - collect children
      foreach($items as $v) {
          $pt = $v->parent;
          $list = @$children[$pt] ? $children[$pt] : array();
          array_push($list, $v);
          $children[$pt] = $list;
      }
      // second pass - get an indent list of the items
      $array = mosTreeRecurse(0, '', array(), $children);
      return $array;
  }
}
/**
* HTML Class
* Utility class for all HTML drawing classes
* @desc class General HTML creation class. We use it for back/front ends.
*/

function group_publish() {
  global $database;
  if (array_key_exists('bid', $_POST)) $in = implode(',', $_POST['bid']);
  else mosRedirect('index.php?option=com_simplemembership&section=group', 'At least one item must be selected');
  $query = "update #__simplemembership_groups set published='1' where id IN ($in)";
  $database->setQuery($query);
  $database->query();
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
function group_unpublish() {
  global $database;
  if (array_key_exists('bid', $_POST)) $in = implode(',', $_POST['bid']);
  else mosRedirect('index.php?option=com_simplemembership&section=group', 'At least one item must be selected');
  $query = "update #__simplemembership_groups set published='0' where id IN ($in)";
  $database->setQuery($query);
  $database->query();
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
function add_acl_group() {
  global $acl, $mosConfig_absolute_path;
  $option = 'com_simplemembership';
  $group_children_tree = array();
  include_once ($mosConfig_absolute_path . '/administrator/components/com_users/models/groups.php'); // for J 1.6
  //$model = JModel::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
  $model = new UsersModelGroups();
  foreach($g = $model->getItems() as $k => $v) { // $g contains basic usergroup items info
      $group_title = '.';
      for ($i = 1;$i <= $g[$k]->level;$i++) {
          $group_title.= '-';
      }
      $group_title.= '-' . $g[$k]->title;
      $group_children_tree[] = mosHTML::makeOption($g[$k]->id, $group_title);
  }
  $gtree[] = mosHTML::makeOption('-2', 'Everyone');
  $gtree = array_merge($gtree, $group_children_tree);
  $group_children_tree = array();
  $gtree = array_merge($gtree, $group_children_tree);
  $lists['acl_group_select'] = mosHTML::selectList($gtree, 'acl_group_select', 'size="4" ', 'value', 'text');
  HTML_simplemembership::add_acl_group_form($option, $lists);
}
function del_acl_group_form() {
  global $acl, $database;
  $option = 'com_simplemembership';
  $query = "select * from #__usergroups where id>'0'
   AND title<>'USERS'
   AND title<>'ROOT'
 AND title<>'Public'
 AND title<>'Super Users'
   AND title<>'Customer Group'
   AND title<>'Administrator'
   AND title<>'Registered'
   AND title<>'Author'
   AND title<>'Editor'
   AND title<>'Publisher'
   AND title<>'Manager'
 AND title<>'Super Administrator'
 AND title<>'Shop Suppliers'
";
  $database->setQuery($query);
  $lists['custom_groups'] = '';
  $groups = $database->loadObjectList();
  foreach($groups as $group) $lists['custom_groups'].= '<input type="radio" name="bid[]" value="' . $group->id . '">' . $group->title . '<br />';
  HTML_simplemembership::del_acl_group_form($option, $lists);
}


function sync_ajax() {
  global $mosConfig_absolute_path;
  require_once ($mosConfig_absolute_path . '/administrator/components/com_simplemembership/sync_ajax.php');

}

function rebuild_tree($parent_id, $left) {
  global $database;
  // the right value of this node is the left value + 1
  $right = $left + 1;
  // get all children of this node
  $query = 'SELECT id FROM #__usergroups ' . 'WHERE parent_id="' . $parent_id . '";';
  $database->setQuery($query);
  $groups = $database->loadObjectList();
  foreach($groups as $group) {
      // recursive execution of this function for each
      // child of this node
      // $right is the current right value, which is
      // incremented by the rebuild_tree function
      $right = rebuild_tree($group->id, $right);
  }
  // we've got the left value, and now that we've processed
  // the children of this node we also know the right value
  $query = 'UPDATE #__usergroups SET lft=' . $left . ', rgt=' . $right . ' WHERE id="' . $parent_id . '";';
  $database->setQuery($query);
  $database->query();
  // return the right value of this node + 1
  return $right + 1;
}
function del_acl_group() {
  //    print_r($_POST);exit;
  global $database;
  if (!is_array($_POST['bid'])) mosRedirect('index.php?option=com_simplemembership&section=group', 'No selected items');
  $bid = (int)$_POST['bid'][0];
  $query = "delete from #__usergroups where id='$bid'";
  $database->setQuery($query);
  $database->query();
  rebuild_tree(0, 1);
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
function save_newacl() {
  global $database, $mainframe;
  if ($_POST['acl_name'] == '') {
      $mainframe->enqueueMessage('Joomla group name must not be empty!!. ', 'error');
      mosRedirect("index.php?option=com_simplemembership&section=group");
  }
  $acl_name = $_POST['acl_name'];
  $acl_group_select = (int)$_POST['acl_group_select'];
  if ($acl_group_select == '') {
      $mainframe->enqueueMessage('Parent group must be present. ', 'error');
      mosRedirect("index.php?option=com_simplemembership&section=group");
  }
  $query = "insert into #__usergroups (title, parent_id) VALUES ('" . ($acl_name) . "','$acl_group_select')";
  $database->setQuery($query);
  $database->query();
  //print_r($database);  exit;
  rebuild_tree(0, 1);
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
function showCategories() {
  global $database, $my, $option, $menutype, $mainframe, $mosConfig_list_limit, $mosConfig_fromname;
  $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
  $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
  $query = "SELECT * from #__simplemembership_groups order by name";
  $database->setQuery($query);
  $rows = $database->loadObjectList();
  if ($database->getErrorNum()) {
      echo $database->stderr();
      return false;
  }
  $where = array();
  $database->setQuery("SELECT count(*) FROM #__simplemembership_groups AS a" . (count($where)));
  $total = $database->loadResult();
  echo $database->getErrorMsg();
  $pageNav = new JPagination($total, $limitstart, $limit);
  // establish the hierarchy of the categories
  HTML_Categories::show($rows, $pageNav);
}
function editGroup($section = '', $uid = 0) {
  global $database, $my, $acl;
  global $mosConfig_absolute_path,$user_configuration, $mosConfig_live_site;
  
  $edit = false;
  if (array_key_exists('bid', $_POST)) {
      $id = $_POST['bid'][0];
      $query = "select * from #__simplemembership_groups where id='$id'";
      $database->setQuery($query);
      $row = $database->loadObjectList();
      $row = $row[0];
      $query = "SELECT jgroup_id FROM #__simplemembership_group_joomgroup WHERE mgroup_id='$id'";
      $database->setQuery($query);
      if (version_compare(JVERSION, '3.0.0', 'ge')) {
          $row->acl_gid = $database->loadColumn();
      } else {
          $row->acl_gid = $database->loadResultArray();
      }
      $edit = true;
  } else $row = '';
  $group_children_tree = array();
  include_once ($mosConfig_absolute_path . '/administrator/components/com_users/models/groups.php');
  if (version_compare(JVERSION, '3.0.0', 'lt')) {
      $model = JModel::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
  } else {
      $model = JModelLegacy::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
  }
  foreach($g = $model->getItems() as $k => $v) { // $g contains basic usergroup items info
      $group_title = '.';
      for ($i = 1;$i <= $g[$k]->level;$i++) {
          $group_title.= '-';
      }
      $group_title.= '-' . $g[$k]->title;
      $group_children_tree[] = mosHTML::makeOption($g[$k]->id, $group_title);
  }
  $gtree = $group_children_tree;
  if ($edit) $lists['acl_group_select'] = mosHTML::selectList($gtree, 'acl_group_select[]', 'size="4" multiple', 'value', 'text', $row->acl_gid);
  else $lists['acl_group_select'] = mosHTML::selectList($gtree, 'acl_group_select[]', 'size="4" multiple', 'value', 'text');
  $type = mosGetParam($_REQUEST, 'type', '');
  $redirect = mosGetParam($_POST, 'section', '');;
  
  $auto_approve_list[] = mosHTML::makeOption(0,  JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_MANUAL") );
  $auto_approve_list[] = mosHTML::makeOption(1,  JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_AUTO") );
  $auto_approve_list[] = mosHTML::makeOption(2,  JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_AFTER_PRODUCT_BUY") );
  if ($edit) $lists['auto_approve'] = mosHTML::selectList($auto_approve_list, 'auto_approve', 'size="1" onchange="approveValueChange();"', 'value', 'text', $row->auto_approve);
  else $lists['auto_approve'] = mosHTML::selectList($auto_approve_list, 'auto_approve', 'size="1" onchange="approveValueChange();"', 'value', 'text');
  
  $expire_units_list[] = mosHTML::makeOption('D',  JText::_("COM_SIMPLEMEMBERSHIP_EXPIRE_DAY") );
  $expire_units_list[] = mosHTML::makeOption('W',  JText::_("COM_SIMPLEMEMBERSHIP_EXPIRE_WEEK") );
  $expire_units_list[] = mosHTML::makeOption('M',  JText::_("COM_SIMPLEMEMBERSHIP_EXPIRE_MONTH") );
  $expire_units_list[] = mosHTML::makeOption('Y',  JText::_("COM_SIMPLEMEMBERSHIP_EXPIRE_YEAR") );
  if ($edit) $lists['expire_units'] = mosHTML::selectList($expire_units_list, 'expire_units', 'size="1" onchange="expireValueChange();"', 'value', 'text', $row->expire_units);
  else $lists['expire_units'] = mosHTML::selectList($expire_units_list, 'expire_units', 'size="1" onchange="expireValueChange();"', 'value', 'text');
  
  if (isset($id)) {
      $where_current_group = "WHERE id<>" . $id;
  } else $where_current_group = "";
  
  $config_for_trigger = new JConfig();
  $prfbd = $config_for_trigger->dbprefix;
  
  //    print_r($_POST);exit;
  HTML_Categories::edit($section, $lists, $redirect, $row);
}
function saveGroup() {
  global $database, $mosConfig_live_site;
  $post = $_POST;
  foreach($_POST as $key => $val) {
      if ($key == 'acl_group_select') {
          continue;
      }
      if (get_magic_quotes_gpc()) {
          $val = stripslashes($val);
      }
      $val = trim($val);
      $post[$key] = $val;
  }
  $expire_range = JRequest::getVar('expire_range','');
  $expire_units = JRequest::getVar('expire_units','');
  $price =  JRequest::getVar('price');
  $currency= JRequest::getVar('currency');////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  $defcurrency=array('AUD','CAD','CZK','DKK','EUR','HKD','HUF','JPY','NOK','NZD','PLN','SGD','SEK','CHF','USD','RUB');
   if (!in_array($currency,$defcurrency) && $post['auto_approve'] == 2) {
      echo "<script> alert('Please select the correct currency value'); window.history.go(-1);</script>\n";
      exit;
  }
  $acl_group = @$post['acl_group_select'];
  if (count($acl_group) < 1) {
      echo "<script> alert('Joomla group must be selected'); window.history.go(-1);</script>\n";
      exit;
  }
      $product_name = '';
      $link = '';
      $product_id = '';
  if ($post['title'] == '') {
      echo "<script> alert('Group name must be present'); window.history.go(-1);</script>\n";
      exit;
  }
  if (array_key_exists('id', $_POST) && (int)$_POST['id'] != '') {
      $id = (int)$_POST['id'];
      $query = "SELECT ug.title FROM #__usergroups as ug WHERE ug.id IN (" . implode($acl_group, ',') . ") ";
      $database->setQuery($query);
      if (version_compare(JVERSION, '3.0.0', 'ge')) {
          $j_groups = $database->loadColumn();
      } else {
          $j_groups = $database->loadResultArray();
      }
      // $j_groups = $name = JRequest::getVar('acl_group_select');
      $j_groups = implode($j_groups, ',');
      $query = "update #__simplemembership_groups SET
                      name='" . ($post['title']) . "',
                      acl_group='" . $j_groups . "',
                      expire_range='" . $expire_range . "',
                      expire_units='" . $expire_units . "',
                      price = '".$price."',
                      currency_code = '".$currency."',
                      product_id='$product_id',
                      product_name='" . ($product_name) . "',
                      auto_approve='" . ($post['auto_approve']) . "',
                      link='" . ($link) . "',
                      notes='" . ($post['notes']) . "'
                       WHERE id='$id'";

      $database->setQuery($query);
      $database->query();
      $query = "DELETE FROM #__simplemembership_group_joomgroup WHERE mgroup_id='$id'";
      $database->setQuery($query);
      $database->query();
      foreach($acl_group as $group) {
          $query = "INSERT INTO #__simplemembership_group_joomgroup SET mgroup_id='$id', jgroup_id='$group'";
          $database->setQuery($query);
          $database->query();
      }
  } else {
      //////////what group is selected
      $id = (int)$_POST['id'];
      $query = "SELECT ug.title FROM #__usergroups as ug WHERE ug.id IN (" . implode($acl_group, ',') . ") ";
      $database->setQuery($query);
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
          $j_groups = $database->loadColumn();
      } else {
          $j_groups = $database->loadResultArray();
      }
      // $j_groups = $name = JRequest::getVar('acl_group_select');
      $j_groups = implode($j_groups, ',');
      //////////end selections
      
      $query = "insert into #__simplemembership_groups (name,acl_group,price,currency_code,
                                                          expire_range,expire_units,product_id,auto_approve,
                                                          product_name,link,notes)
                                          VALUES('" . ($post['title']) . "','" . $j_groups . "','".$price."',
                                                  '".$currency."','" . $expire_range . "',
                                                  '" . $expire_units . "','$product_id',
                                                  '" . ($post['auto_approve']). "','" .  ($product_name) . "','$link',
                                                  '" . ($post['notes']) . "')";

      $database->setQuery($query);
      $database->query();
      $query = "SELECT id FROM #__simplemembership_groups ORDER BY #__simplemembership_groups.id DESC LIMIT 0 , 1";
      $database->setQuery($query);
      $id = $database->loadResult();
      foreach($acl_group as $group) {
          $query = "INSERT INTO #__simplemembership_group_joomgroup SET mgroup_id='$id', jgroup_id='$group'";
          $database->setQuery($query);
          $database->query();
      }
  }
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
function removeCategoriesFromDB($cid) {
  global $database, $my;
  if (array_key_exists('bid', $_POST)) if (is_array($_POST['bid'])) {
      $bid = $_POST['bid'];
      $instr = implode(',', $bid);
      $sql = "DELETE FROM #__simplemembership_groups WHERE id IN ($instr) ";
      $database->setQuery($sql);
      $database->query();
  }
}
/**
* Deletes one or more categories from the categories table
*
* @param string $ The name of the category section
* @param array $ An array of unique category id numbers
*/
function removeCategories($section, $cid) {
  //    print_r($_POST);exit;
  global $database;
  if (count($cid) < 1) {
      echo "<script> alert('Select a category to delete'); window.history.go(-1);</script>\n";
      exit;
  }
  foreach($cid as $catid) {
      removeCategoriesFromDB($catid);
  }
  $msg = (count($err) > 1 ? "Categories " :  JText::_("COM_SIMPLEMEMBERSHIP_CATEGORIES_NAME")  . " ") .  JText::_("COM_SIMPLEMEMBERSHIP_DELETED") ;
  mosRedirect('index.php?option=com_simplemembership&section=group&mosmsg=' . $msg);
}
/**
* Publishes or Unpublishes one or more categories
*
* @param string $ The name of the category section
* @param integer $ A unique category id (passed from an edit form)
* @param array $ An array of unique category id numbers
* @param integer $ 0 if unpublishing, 1 if publishing
* @param string $ The name of the current user
*/
function publishCategories($section, $categoryid = null, $cid = null, $publish = 1) {
  global $database, $my;
  if (!is_array($cid)) {
    $cid = array();
  }
  if ($categoryid) {
    $cid[] = $categoryid;
  }
  if (count($cid) < 1) {
    $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
    echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $cids = implode(',', $cid);
  $query = "UPDATE #__categories SET published='$publish'" . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))";
  $database->setQuery($query);
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }
  if (count($cid) == 1) {
    $row = new mosCategory($database);
    $row->checkin($cid[0]);
  }
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
/**
* Cancels an edit operation
*
* @param string $ The name of the category section
* @param integer $ A unique category id
*/
function cancelGroup() {
  global $database;
  $row = new mosCategory($database);
  $row->bind($_POST);
  $row->checkin();
  mosRedirect('index.php?option=com_simplemembership&section=group');
}
/**
* changes the access level of a record
*
* @param integer $ The increment to reorder by
*/
function removesimplememberships($bid, $option) {
  global $database;
  if (!is_array($bid) || count($bid) < 1) {
    echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
    exit;
  }
  if (count($bid)) {
    $bids = implode(',', $bid);
    $database->setQuery("select fk_users_id,id,name,email,current_gid FROM #__simplemembership_users WHERE id IN ($bids)");
    $jom_userlist = $database->loadObjectList();
    foreach($jom_userlist as $user) {
      $database->setQuery("DELETE FROM #__users WHERE id='$user->fk_users_id'");
      $database->query();
      $database->setQuery("DELETE FROM #__user_usergroup_map WHERE user_id='$user->fk_users_id'");
      $database->query();
      $orderId = checkOrder($user->id);
      $database->setQuery("INSERT INTO #__simplemembership_orders_details(fk_sm_users_name,
                                        fk_sm_users_email,order_date,txn_type,fk_order_id,fk_group_id)
                  VALUES ('".$user->name."','".$user->email."',now(),
                          'User deleted.',$orderId,$user->current_gid)");
      $database->query();
    }
    $database->setQuery("DELETE FROM #__simplemembership_users WHERE id IN ($bids)");
    if (!$database->query()) {
      echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    }
  }
  mosRedirect("index.php?option=$option");
}
function showUsers($option) {
  global $database, $mainframe, $mosConfig_list_limit;
  advexpire();
  sync_users();
  $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
  $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
  $advgid = $mainframe->getUserStateFromRequest("advgid{$option}", 'advgid', '-2'); //old 0
  $pub = $mainframe->getUserStateFromRequest("pub{$option}", 'pub', '-1'); //add nik
  $enable = $mainframe->getUserStateFromRequest("enable{$option}", 'enable', '-1'); //add nik
  $search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
  $search = $database->getEscaped(trim(strtolower($search)));
  $where = array();
  if(isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
  }
  if ($pub == "pub") {
    array_push($where, "approved = 1");
  } else if ($pub == "not_pub") {
    array_push($where, "approved = 0");
  }

  if($advgid > 0 && ($pub == "pub" || $pub == -1)){
    array_push($where, "current_gid =".$advgid);
  }elseif($advgid == 0 && ($pub == "pub" || $pub == -1)){
    array_push($where, "current_gid =-2");
  }elseif($advgid > 0 && ($pub == "not_pub" || $pub == -1)){
    array_push($where, "want_gid = ".$advgid);
  }elseif($advgid == 0 && ($pub == "not_pub" || $pub == -1)){
    array_push($where, "want_gid = -2");
  }
  if ($enable == "enable") {
    array_push($where, "block = 0");
  } else if ($enable == "not_enable") {
    array_push($where, "block = 1");
  }
  if ($search) {
    array_push($where, "(LOWER(sm_users.name) LIKE '%$search%' 
                        OR LOWER(sm_users.username) LIKE '%$search%' 
                        OR LOWER(sm_users.email) LIKE '%$search%')");
  }
   $query = "SELECT distinct count(*) FROM #__simplemembership_users AS sm_users "  
  . "\n LEFT JOIN #__user_usergroup_map AS j_u_gr_map ON j_u_gr_map.user_id = sm_users.fk_users_id " 
  . "\n LEFT JOIN #__usergroups AS usr_gr ON usr_gr.id = j_u_gr_map.group_id " 
  . "\n LEFT JOIN #__simplemembership_groups AS sm_group ON sm_users.current_gid = sm_group.id "  
  . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "");
  $database->setQuery($query);
  $total = $database->loadResult();
  echo $database->getErrorMsg();
  $pageNav = new JPagination($total, $limitstart, $limit);
  $selectstring = "SELECT distinct sm_users.*,sm_group.acl_group,group_concat(distinct title) as jg_titles,( "
  . "\n SELECT group_concat(distinct title) FROM #__simplemembership_users AS sm_usersd "
  . "\n LEFT JOIN #__simplemembership_group_joomgroup AS sm_joom_gr ON sm_usersd.want_gid = sm_joom_gr.mgroup_id "
  . "\n LEFT JOIN #__usergroups AS usr_gr2 ON sm_joom_gr.jgroup_id = usr_gr2.id"
  . "\n ) as want_jg_titles FROM #__simplemembership_users AS sm_users "  
  . "\n LEFT JOIN #__user_usergroup_map AS j_u_gr_map ON j_u_gr_map.user_id = sm_users.fk_users_id " 
  . "\n LEFT JOIN #__usergroups AS usr_gr ON usr_gr.id = j_u_gr_map.group_id " 
  . "\n LEFT JOIN #__simplemembership_groups AS sm_group ON sm_users.current_gid = sm_group.id "  
  . (count($where) ? "\nWHERE " . implode(' AND ', $where) : "") 
  . "\n GROUP BY sm_users.id"
  . "\n ORDER BY sm_users.name" . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
  $database->setQuery($selectstring);
  $rows = $database->loadObjectList();
  if ($database->getErrorNum()) {
      echo $database->stderr();
      return false;
  }
  $categories[] = mosHTML::makeOption('0',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_CATEGORIES") );
  $categories[] = mosHTML::makeOption('-1',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_ALL_CATEGORIES") );
  $pubmenu[] = mosHTML::makeOption('-1',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_ALL_PUBLIC") );
  $pubmenu[] = mosHTML::makeOption('not_pub',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_NOT_PUBLIC") );
  $pubmenu[] = mosHTML::makeOption('pub',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_PUBLIC") );
  $publist = mosHTML::selectList($pubmenu, 'pub',
          'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $pub);
  $enablemenu[] = mosHTML::makeOption('-1',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_ALL_ENABLE") );
  $enablemenu[] = mosHTML::makeOption('not_enable',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_NOT_ENABLE") );
  $enablemenu[] = mosHTML::makeOption('enable',  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_SELECT_ENABLE") );
  $enablelist = mosHTML::selectList($enablemenu, 'enable',
             'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $enable);
  $options = array();
  $query = "select * from #__simplemembership_groups";
  $database->setQuery($query);
  $pr_lis = $database->loadObjectList();
  $options[] = mosHTML::makeOption(-2, 'All groups');
  $options[] = mosHTML::makeOption(-1, 'Other group');
  $options[] = mosHTML::makeOption(0, 'Default group');
  foreach($pr_lis as $item) $options[] = mosHTML::makeOption($item->id, $item->name);
  $olist = mosHTML::selectList($options, 'advgid',
         'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $advgid);
  HTML_simplemembership::showUsers($option, $rows, $publist, $search, $pageNav, $olist, $enablelist);
}


function saveUser() {
  if($_POST['password']===$_POST['password2']){
    global $database, $my, $user_configuration, $mainframe, $mosConfig_live_site;
    if ($_POST['name'] == '' || $_POST['email'] == '' || $_POST['username'] == '') {
      $mainframe->enqueueMessage('Fields Name, UserName and email must be filled!. ', 'error');
      mosRedirect("index.php?option=com_simplemembership");
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

    if (array_key_exists('bid', $_POST)) $bid = (int)$_POST['bid'];
    else $bid = 0;
    $want_gid = (int)$_POST['wanted_gid'];
    $user = new JUser();
    if ($bid != 0) {
        $query = "SELECT * from #__simplemembership_users where id='$bid'";
        $database->setQuery($query);
        $tmp_us = $database->loadObject();
        $adv_uid = $tmp_us->id;
        $joom_id = $tmp_us->fk_users_id;
        $adv_wg = $tmp_us->want_gid;
        $adv_dl = $tmp_us->expire_date;
        $user->load($joom_id);
        $data_for_bind = $_POST;
        $data_for_bind['id'] = $joom_id;
        $user->bind($data_for_bind);
        if($want_gid != -3){
          if ($want_gid != -1 && $want_gid != -2) {
            $query = "select * from #__simplemembership_groups where id='$want_gid'";
            $database->setQuery($query);
            $group = $database->loadObjectList();
            $group = $group[0];
            $g_acl_gid = $group->acl_gid;
          } else {
            $g_acl_gid = $default_group;
            $g_dl = 0;
          }
        if (($adv_wg != $g_acl_gid)) {
          if ($want_gid != -2) {
            $user->groups = $default_group;
            $user->usertype = $default_group_name;
          }
            $user->groups = $default_group;
            $user->usertype = $default_group_name;
        }
      }
    } else {
      $user->bind($_POST);
      $user->groups = $default_group;
      $user->usertype = $default_group_name;
    }
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->username = $_POST['username'];
    $user->registerDate = date('Y-m-d h:i:s');
    if ($user->save()) {
      $al_user = new mos_alUser($database);
      if ($bid != 0) $al_user->load($bid);
      $al_user->bind($_POST);
      $al_user->fk_users_id = $user->id;
      if($want_gid != -3){
        if ($want_gid != -2) {
          $query = "select * from #__simplemembership_groups where id='$want_gid'";
          $database->setQuery($query);
          $group = $database->loadObjectList();
          $group = $group[0];
          $al_user->current_gid = -2;
          $al_user->current_gname = 'Default';
          $al_user->approved = 0;
          $al_user->want_gid = $group->id;
          $al_user->want_gname = $group->name;
          $al_user->expire_date = 0;
        } else {
          $al_user->expire_date = 0;
          $al_user->current_gid = -2;
          $al_user->current_gname = 'Default';
          $user->expire_date = 0;
          $al_user->approved = 1;
          $al_user->block = $user->block;
        }
      }else{
        $al_user->expire_date = 0;
        $al_user->current_gid = -2;
        $al_user->current_gname = 'Default';
        $user->expire_date = 0;
        $al_user->approved = 1;
        $al_user->block = $user->block;
      }
      if($al_user->store()){
        if($al_user->current_gid != $want_gid && $want_gid != -2 && $want_gid != -3 && $bid != 0){
          $order_id = checkOrder($al_user->id);
          $txn_type = 'Update user by admin.Set new group.';
          $sql = "INSERT INTO `#__simplemembership_orders_details`(fk_order_id, fk_sm_users_id, fk_sm_users_name,
                                fk_sm_users_email, order_date, fk_group_id, txn_type)
                        VALUES ('".$order_id."',
                                '".$al_user->id."',
                                '".$al_user->name."',
                                '".$al_user->email."',
                                now(),
                                '".$want_gid."',
                                '".$txn_type."')";
          $database->setQuery($sql);
          $database->query();
        }
        if ($bid == 0) {
          $order_id = checkOrder($al_user->id);//create order for new user
          $sql = "INSERT INTO #__simplemembership_orders_details(fk_order_id,fk_sm_users_id,order_date,
                                fk_sm_users_name,
                                fk_sm_users_email,txn_type)
                  VALUES ('".$order_id."','".$al_user->id."', now(),'".$al_user->name."',
                          '".$al_user->email."','User created.')";
          $database->setQuery($sql);
          $database->query();
        }
      }else{
        $mainframe->enqueueMessage('SM user not created.');
      }
      $mainframe->enqueueMessage('User created.');
    } else {
      $mainframe->enqueueMessage('User not created. ' . $user->getError(), 'error');
    }
    mosRedirect("index.php?option=com_simplemembership");
  }else{
    echo"<script> alert('Passwords do not match. Please try again.'); window.history.go(-1);</script>\n";;
  }
}


function editUser($option, $bid) {
  global $database, $my, $mosConfig_live_site, $user_configuration, $mosConfig_absolute_path;
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
  }////end default group

  $user = new mos_alUser($database);
  $user->load(intval($bid));
  $joom_user = new JUser();
  if ($bid) {
    $joom_user->load($user->fk_users_id);
  }
  $joom_user->params = json_decode($joom_user->params, true);
  $options = array();
  $query = "select * from #__simplemembership_groups where published='1'";
  $database->setQuery($query);
  $pr_lis = $database->loadObjectList();
  if($user->fk_users_id){
    $query="SELECT * FROM #__user_usergroup_map WHERE user_id=".$user->fk_users_id.
          " AND group_id = 8 OR group_id = 7";
    $database->setQuery($query);
    $result = $database->loadObjectList();
  }
  $is_admin = false;
  if(isset($result['0']) && !empty($result['0']) && ($user->current_gid == -2)){
    $is_admin = true;
    $options[] = mosHTML::makeOption(-3,'Current(Administration)');
  }
  $options[] = mosHTML::makeOption(-2,'Default('.$default_group_name.')');
  foreach($pr_lis as $item) {
    if ($item->id == $user->current_gid)
      $name_of_group = $item->name;
    $options[] = mosHTML::makeOption($item->id, $item->name);
  }
  if($is_admin)
    $user->current_gid = -3;
  if (array_key_exists(0, $options)) 
    $olist = mosHTML::selectList($options, 'wanted_gid', '', 'value', 'text', $user->current_gid);
  else $olist = 'Default('.$default_group_name[0] . ')';
  $last_approved_date = strtotime($user->last_approved);
  $cur_date = date_create(date("Y-m-d H:i:s"));
  $exp_date = date_create($user->expire_date);
  $interval = date_diff($exp_date,$cur_date);
  if ($interval->invert == 0) {
    if (isset($name_of_group) && $name_of_group != '') {
      $msg = '<br/>This user is exired for group <b>' . $name_of_group;
    } else {
      $msg = '<br/>This user can be in ones of these groups';
    }
  }
  if ($interval->invert == 1) {
    $msg = '<br/>This user can be in this group for next ' .$interval->format('%d%'). 'day(s)';
  }
  $dispatcher = JDispatcher::getInstance();
  JPluginHelper::importPlugin('user');
  JForm::addFormPath($mosConfig_absolute_path . '/components/com_simplemembership/forms');
  $user_profile_form = JForm::getInstance('com_users.user', 'user');
  $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.user', $joom_user));
  $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
  $user_profile_form->bind($joom_user);
  HTML_simplemembership::editUser($option, $user, $olist, $bid, $msg, $user_profile_form);
}


/**
* Deletes one or more records
* @param array -An array of unique category id numbers
* @param string -The current author option
*/
function publishUsers($bid, $publish, $option) {
  global $database, $my, $user_configuration, $mosConfig_mailfrom;
  $catid = mosGetParam($_POST, 'catid', array(0));
  if (!is_array($bid) || count($bid) < 1) {
    $action = $publish ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $bids = implode(',', $bid);
  $database->setQuery("select * from #__simplemembership_users " . "\nWHERE id IN ($bids)");
  $list_id = $database->loadObjectList();
  $str_id = '';
  foreach($list_id as $item_id) {
    $arr[] = $item_id->fk_users_id;
  }
  $str_id = implode(',', $arr);
  $database->setQuery("UPDATE #__users SET block='$publish' " . "\nWHERE id IN ($str_id)");
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }
  $date_string = '';
  $database->setQuery("UPDATE #__simplemembership_users SET block='$publish' " . $date_string . "\nWHERE id IN ($bids)");
  if ($publish) foreach($list_id as $item_id) {
    if (version_compare(JVERSION, '3.0.0', 'lt')) {
      JUtility::sendMail($mosConfig_mailfrom,$user_configuration['senders_email'], $item_id->email, 'account enabled', stripslashes($user_configuration['user_enabled_msg']), 1);
    } else {
      $a = JFactory::getMailer();
      $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account enabled', stripslashes($user_configuration['user_enabled_msg']), 1);
    }
  }
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }
  if (count($bid) == 1) {
    $row = new mos_alUser($database);
    $row->checkin($bid[0]);
  }
  mosRedirect("index.php?option=$option");
}


function approveUsers($bid, $approve, $option) {
  global $database, $my, $user_configuration, $mosConfig_mailfrom;
  $catid = mosGetParam($_POST, 'catid', array(0));
  if (!is_array($bid) || count($bid) < 1) {
    $action = $approve ? 'approve' : 'unapprove';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $bids = implode(',', $bid);
  $date = date("Y-m-d H:i:s");
  $date_string = '';
  if ($approve == 1) {
    $date_string = ",last_approved='$date'";
  }
  $database->setQuery("select * from #__simplemembership_users " . "\nWHERE id IN ($bids)");
  $list_id = $database->loadObjectList();
  $str_id = '';
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }


  $database->setQuery("UPDATE #__simplemembership_users SET approved='$approve' " . $date_string . "\nWHERE id IN ($bids)");
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }

   

  // Get the dispatcher and load the users plugins.
  $dispatcher = JDispatcher::getInstance();
  JPluginHelper::importPlugin('user');
  $user = new JUser();


  /*----------------------------------------------start approve user----------------------------------------------------------------*/
  if ($approve) {
    foreach($list_id as $item_id) {

      $query="SELECT * FROM #__user_usergroup_map WHERE user_id=".$item_id->fk_users_id.
            " AND group_id = 8 OR group_id = 7";
            $database->setQuery($query);
            $is_super_user = $database->loadObjectList();

      $want_gid = $item_id->want_gid;
      if ($want_gid != 0) {
        $query = "SELECT ug.id FROM #__usergroups AS ug
                LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id 
                WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" . $item_id->id . ")";
        $database->setQuery($query);
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
          $want_groups = $database->loadColumn();
        } else {
          $want_groups = $database->loadResultArray();
        }
        $query = "SELECT ug.title FROM #__usergroups AS ug"
            . "\n LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id "
            . "\n WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" . $item_id->id . ")";
        $database->setQuery($query);
        $want_groups_name = $database->loadColumn();
        $want_groups_name = implode(",", $want_groups_name);
        echo $database->getErrorMsg();
        $want_groups = array_combine($want_groups, $want_groups);
        $user->load($item_id->fk_users_id);
        $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
        $user->groups = $want_groups; 
        $user->usertype = $want_groups_name;
/*---------------------------------------start day_last in groups--------------------------------------------------------------*/
        $query = "SELECT * FROM #__simplemembership_groups AS sg
            WHERE sg.id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" . $item_id->id . ")";
        $database->setQuery($query);
        $usr_group = $database->loadObjectList();
        $usr_group=$usr_group['0'];
        $current_gid = $usr_group->id;
/*---------------------------------------end day_last in groups--------------------------------------------------------------*/
        $user->save();
        $query="UPDATE #__simplemembership_users SET expire_date='".expireDate($usr_group)."',
								current_gid='".$usr_group->id."',
								current_gname='".$usr_group->name."',
                last_approved=now()
                WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
        $database->setQuery($query);
        $database->query();
      }else{
          //// Default group
          $current_gid ='';
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
          $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
          if(empty($is_super_user))  $user->groups = $default_group;
          if(empty($is_super_user))  $user->usertype = $default_group_name;
          $user->save();

          $query="UPDATE #__simplemembership_users SET current_gid=-2,
    					current_gname='Default',
              expire_date = 0,
              last_approved=now()
            	WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
	        $database->setQuery($query);
	        $database->query();  
          }
        $sql = "SELECT id FROM #__simplemembership_orders WHERE fk_sm_users_id = '".$item_id->id."'";
        $database->setQuery($sql);
        $order_id = $database->loadResult();
        $txn_type = 'Approved by admin';
        $sql = "INSERT INTO `#__simplemembership_orders_details`(fk_order_id, fk_sm_users_id, 
                              fk_sm_users_name, fk_sm_users_email,
                              order_date ,fk_group_id, txn_type)
                      VALUES ('".$order_id."',
                              '".$item_id->id."',
                              '".$item_id->name."',
                              '".$item_id->email."',
                              now(),
                              '".$current_gid."',
                              '".$txn_type."')";
        $database->setQuery($sql);
        $database->query();
      if (version_compare(JVERSION, '3.0.0', 'lt')) {
          JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
      } else {
          $a = JFactory::getMailer();
          $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email,
             'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
      }
    }
  }/*----------------------------------------------end approve user----------------------------------------------------------------*/
  
  /*----------------------------------------------start unapprove user----------------------------------------------------------------*/
  if (!$approve) {
    foreach($list_id as $item_id) {

      $query="SELECT * FROM #__user_usergroup_map WHERE user_id=".$item_id->fk_users_id.
            " AND group_id = 8 OR group_id = 7";
            $database->setQuery($query);
            $is_super_user = $database->loadObjectList();

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
        $query = "SELECT * FROM #__simplemembership_groups
                  WHERE id = ".$item_id->current_gid;
        $database->setQuery($query);
        $usr_group = $database->loadObjectList();
        $usr_group = $usr_group['0'];
        if($usr_group)
          $where = "want_gid='".$usr_group->id."',
                    want_gname='".$usr_group->name."',";
        $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
        if(empty($is_super_user)) $user->groups = $default_group;
        if(empty($is_super_user)) $user->usertype = $default_group_name;
        $user->save();
        $query="UPDATE #__simplemembership_users SET  ".$where."
					current_gid=-2,
					current_gname='Default',
          expire_date = 0
    			WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
        $database->setQuery($query);
        $database->query();
        $sql = "SELECT id FROM #__simplemembership_orders WHERE fk_sm_users_id = '".$item_id->id."'";
        $database->setQuery($sql);
        $order_id = $database->loadResult();
        $txn_type = 'Unapproved by admin';
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
      }
      if (version_compare(JVERSION, '3.0.0', 'lt')) {
        JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
      } else {
        $a = JFactory::getMailer();
        $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
      }
    }
  }
  /*----------------------------------------------end unapprove user----------------------------------------------------------------*/
  if (count($bid) == 1) {
      $row = new mos_alUser($database);
      $row->checkin($bid[0]);
  }
  mosRedirect("index.php?option=$option");
}
/**
* Moves the order of a record
* @param integer - The increment to reorder by
*/
function ordersimplememberships($bid, $inc, $option) {
  global $database;
  $user = new mos_alUser($database);
  $user->load($bid);
  $user->move($inc);
  mosRedirect("index.php?option=$option");
}

/**
* Cancels an edit operation
* @param string - The current author option
*/
function cancelsimplemembership($option) {
  global $database;
  $row = new mos_alUser($database);
  $row->bind($_POST);
  $row->checkin();
  mosRedirect("index.php?option=$option");
}


function configure($option) {
  global $my, $user_configuration, $acl, $mosConfig_live_site, $mosConfig_absolute_path;
  $yesno[] = mosHTML::makeOption('1',  JText::_("COM_SIMPLEMEMBERSHIP_YES") );
  $yesno[] = mosHTML::makeOption('0',  JText::_("COM_SIMPLEMEMBERSHIP_NO") );
  $group_children_tree = array();
  include_once ($mosConfig_absolute_path . '/administrator/components/com_users/models/groups.php');
  if (version_compare(JVERSION, '3.0.0', 'lt')) {
    $model = JModel::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
  } else {
    $model = JModelLegacy::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
  }
  foreach($g = $model->getItems() as $k => $v) { // $g contains basic usergroup items info
    $group_title = '.';
    for ($i = 1;$i <= $g[$k]->level;$i++) {
        $group_title.= '-';
    }
    $group_title.= '-' . $g[$k]->title;
    $group_children_tree[] = mosHTML::makeOption($g[$k]->id, $group_title);
  }
  $gtree[] = mosHTML::makeOption('-2', 'Everyone');
  $gtree = array_merge($gtree, $group_children_tree);
  $f = array();
  $s = explode(',', $user_configuration['other_profiles']);
  for ($i = 0;$i < count($s);$i++) $f[] = mosHTML::makeOption($s[$i]);
  $lists['other_profiles'] = mosHTML::selectList($gtree, 'other_profiles[]', 'size="9" multiple="" ', 'value', 'text', $f);
 //********   begin add send mail for admin  ******************
  $lists['preregister'] = stripslashes($user_configuration['preregister']);
  $lists['activationmsg'] = stripslashes($user_configuration['activationmsg']);
  //add for show subcategory
 

  $lists['useradd_notification_email'] = '<input type="text" name="useradd_notification_email" value="' . $user_configuration['useradd_notification_email'] . '" class="inputbox" size="50" maxlength="50" title="" />';
  $lists['senders_email'] = '<input type="text" name="senders_email" value="' . $user_configuration['senders_email'] . '" class="inputbox" size="50" maxlength="50" title="" />';
  //notify before end expire
  $lists['before_end_notify'] = mosHTML::RadioList($yesno, 'before_end_notify', 'class="inputbox"', $user_configuration['before_end_notify'], 'value', 'text');
  $lists['before_end_notify_days'] = '<input type="text" name="before_end_notify_days" value="' . $user_configuration['before_end_notify_days'] . '" class="inputbox" size="2" maxlength="2" title="" />';
  $lists['before_end_notify_email'] = '<input type="text" name="before_end_notify_email" value="' . $user_configuration['before_end_notify_email'] . '" class="inputbox" size="50" maxlength="50" title="" />';

  $f = array();
  $s = explode(',', $user_configuration['default_group']);
  for ($i = 0;$i < count($s);$i++) $f[] = mosHTML::makeOption($s[$i]);
  $lists['default_group'] = mosHTML::selectList($gtree, 'default_group[]', 'size="4" multiple="" ', 'value', 'text', $f);

  $user_id = $my->id;
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

  foreach ($fieldsets as $fieldset) {
    if($fieldset->label && $fieldset->name!='user_details' && $fieldset->name!='settings') $allow_fieldsets[] = mosHTML::makeOption($fieldset->name,JText::_($fieldset->label));
  }

  //by default rewrite!!!
  $user_configuration['allow_fieldsets'] = isset($user_configuration['allow_fieldsets']) ? $user_configuration['allow_fieldsets'] : 'profile';

  //allow_fieldsets
  $f = array();
  $s = explode(',', $user_configuration['allow_fieldsets']);
  for ($i = 0;$i < count($s);$i++) $f[] = mosHTML::makeOption($s[$i]);
  $lists['allow_fieldsets'] = mosHTML::selectList($allow_fieldsets , 'allow_fieldsets[]', 'size="4" multiple="" ', 'value', 'text', $f);

  $lists['jcron_link'] = $mosConfig_live_site . '/index.php?option=com_simplemembership&task=advexpire';
  $lists['admin_created_msg'] = stripslashes($user_configuration['admin_created_msg']);
  $lists['user_created_msg'] = stripslashes($user_configuration['user_created_msg']);
  $lists['user_enabled_msg'] = stripslashes($user_configuration['user_enabled_msg']);
  $lists['user_approve_msg'] = stripslashes($user_configuration['user_approve_msg']);
  $lists['acl_group_set_email'] = mosHTML::RadioList($yesno, 'acl_group_set_email', 'class="inputbox"', $user_configuration['acl_group_set_email'], 'value', 'text');
  $lists['user_disapprove_msg'] = stripslashes($user_configuration['user_disapprove_msg']);
  $lists['admin_created_msg_for_user'] = stripslashes($user_configuration['admin_created_msg_for_user']);
  $lists['admin_created_msg_for_admin'] = stripslashes($user_configuration['admin_created_msg_for_admin']);
 
  
  HTML_simplemembership::showConfiguration($lists, $option);
}


function configure_save($option) {
  global $my, $user_configuration;
  //*********   begin add send mail for admin   *******
  $str = '';
  $supArr = JArrayHelper::getValue($_POST, 'other_profiles', 0);
  for ($i = 0;$i < count($supArr);$i++) {
    $str.= $supArr[$i] . ',';
  }
  $str = substr($str, 0, -1);
  $user_configuration['other_profiles'] = $str;
  $user_configuration['activationmsg'] = addslashes($_POST['activationmsg']);
  $user_configuration['preregister'] = addslashes($_POST['preregister']);
  $str = '';
  $supArr = mosGetParam($_POST, 'paypal_buy_registrationlevel', 0);
  for ($i = 0; $i < count($supArr); $i++)
    $str.=$supArr[$i] . ',';
  $str = substr($str, 0, -1);

  $user_configuration['acl_group_set_email'] = mosGetParam($_POST, 'acl_group_set_email', 0);
  $user_configuration['useradd_notification_email'] = mosGetParam($_POST, 'useradd_notification_email', "");
  $user_configuration['senders_email'] = mosGetParam($_POST, 'senders_email', "");
  $str = '';
  $supArr = JArrayHelper::getValue($_POST, 'default_group', 0);
  for ($i = 0;$i < count($supArr);$i++) {
      $str.= $supArr[$i] . ',';
  }
  $str = substr($str, 0, -1);
  $user_configuration['default_group'] = $str;

  //allow_fieldsets
  $str = '';
  $supArr = JArrayHelper::getValue($_POST, 'allow_fieldsets', 0);
  for ($i = 0;$i < count($supArr);$i++) {
      $str.= $supArr[$i] . ',';
  }
  $str = substr($str, 0, -1);
  $user_configuration['allow_fieldsets'] = $str;

  $user_configuration['admin_created_msg'] = addslashes($_POST['admin_created_msg']);
  $user_configuration['user_created_msg'] = addslashes($_POST['user_created_msg']);
  $user_configuration['user_enabled_msg'] = addslashes($_POST['user_enabled_msg']);
  $user_configuration['user_approve_msg'] = addslashes($_POST['user_approve_msg']);
  $user_configuration['user_disapprove_msg'] = addslashes($_POST['user_disapprove_msg']);
  $user_configuration['before_end_notify'] = mosGetParam($_POST, 'before_end_notify', 0);
  $user_configuration['before_end_notify_days'] = mosGetParam($_POST, 'before_end_notify_days', "2");
  $user_configuration['before_end_notify_email'] = mosGetParam($_POST, 'before_end_notify_email', "");
  $user_configuration['add_user_email']['address'] = mosGetParam($_POST, 'add_user_email_address', "");
  $user_configuration['admin_created_msg_for_user'] = addslashes($_POST['admin_created_msg_for_user']);
  $user_configuration['admin_created_msg_for_admin'] = addslashes($_POST['admin_created_msg_for_admin']);
  
  
  mos_alUserOthers::setParams();
  configure($option);
}






function override() { 
  global $database, $my, $user_configuration, $acl, $mosConfig_live_site, $mosConfig_absolute_path;
  
  HTML_simplemembership::override();
}

function orders() { 
  global $database, $my, $user_configuration, $acl, $mosConfig_live_site, $mosConfig_absolute_path;
  $order = '';
  $search = '';
  $where = '';
  if(isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
    $where = "WHERE u.email LIKE '%{$search}%' OR u.name LIKE '%{$search}%'";
  }
  if(isset($_GET['orderby']) && $_GET['orderby'] == 'user') {
      $order = 'ORDER BY user';
  }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'email') {
      $order = 'ORDER BY u.email';
  }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'status') {
      $order = "ORDER BY o.status = 'Completed' DESC";
  }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'date') {
      $order = "ORDER BY o.order_date <> 'NULL' DESC";
  }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'id') {
      $order = "ORDER BY o.id  DESC";
  }
  if(isset($_REQUEST['order_details'])){
    $order_id = JRequest::getVar("order_id");
    $sql = "SELECT u.id as userId, u.name as user, ".
                "\n o.status, o.fk_group_id,o.fk_sm_users_name as username,o.fk_sm_users_email as email,o.id,o.txn_type,o.payment_details, o.order_date, o.order_price, o.order_currency_code,".
                "\n g.name, g.price as group_price, g.currency_code as group_currency_code". 
        "\n FROM #__simplemembership_orders_details AS o ". 
        "\n LEFT JOIN #__simplemembership_users AS u ". 
        "\n ON o.fk_sm_users_id = u.id ". 
        "\n LEFT JOIN #__simplemembership_groups AS g ".
        "\n ON o.fk_group_id = g.id WHERE o.fk_order_id=".$order_id.' ORDER BY o.order_date DESC';
  $database->setQuery($sql);
  $orders = $database->loadObjectList();
  HTML_simplemembership::order_details($orders, $search);
  }else{
    $sql = "SELECT u.id as userId, u.name as user, ".
                    "o.status,o.fk_sm_users_name as username,o.fk_sm_users_email as email, o.id, o.order_date, o.order_price, o.order_currency_code,".
                    "g.name, g.price as group_price, g.currency_code as group_currency_code". 
            "\n FROM #__simplemembership_orders AS o ". 
            "\n LEFT JOIN #__simplemembership_users AS u ". 
            "\n ON o.fk_sm_users_id = u.id ". 
            "\n LEFT JOIN #__simplemembership_groups AS g ".
            "\n ON o.fk_group_id = g.id ". $order." ".$where;
  $database->setQuery($sql);
  $orders = $database->loadobjectList();
  HTML_simplemembership::orders($orders, $search);
  }
}


function updateOrderStatus() {
  global $database;   
  $orderId = $_POST['cb'];
  $status = $_POST['order_status'];
  $status = $status[$orderId[0]];
  $option = $_POST['option'];
  $sql = "UPDATE #__simplemembership_orders SET status = '".$status."' WHERE id = ".$orderId[0]."";
  $database->setQuery($sql);
  $database->query();
  $sql = "SELECT * FROM #__simplemembership_orders WHERE id = ".$orderId[0]."";
  $database->setQuery($sql);
  $order = $database->loadobjectList();
  $order = $order['0'];
  $order->txn_type = 'changed by the administrator';
  $sql = "INSERT INTO `#__simplemembership_orders_details`(fk_order_id,fk_sm_users_id,status,order_date,
                        fk_sm_users_name, fk_sm_users_email, fk_group_id,
                        txn_type,txn_id,payer_id,payer_status,order_price,
                        order_currency_code)
                VALUES ('".$orderId[0]."',
                        '".$order->fk_sm_users_id."',
                        '".$order->status."',
                        now(),
                        '".$order->fk_sm_users_name."',
                        '".$order->fk_sm_users_email."',
                        '".$order->fk_group_id."',
                        '".$order->txn_type."',
                        '".$order->txn_id."',
                        '".$order->payer_id."',
                        '".$order->payer_status."',
                        '".$order->order_price."',
                        '".$order->order_currency_code."')";
  $database->setQuery($sql);
  $database->query();
  
  $bid[] = $order->fk_sm_users_id ;
  $approve = ($order->status == "Completed")?(1):(0);
  approveUsersByOrderStatusChange($bid, $approve, $order);  
  
  mosRedirect("index.php?option=$option&task=orders");
}

function approveUsersByOrderStatusChange($bid, $approve, $order) {
  global $database, $my, $user_configuration, $mosConfig_mailfrom;
  $catid = mosGetParam($_POST, 'catid', array(0));

  $bids = implode(',', $bid);
  $date = date("Y-m-d H:i:s");
  $date_string = '';
  if ($approve == 1) {
    $date_string = ",last_approved='$date'";
  }
  $database->setQuery("select * from #__simplemembership_users " . "\nWHERE id IN ($bids)");
  $list_id = $database->loadObjectList();
  $str_id = '';
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }
  $database->setQuery("UPDATE #__simplemembership_users SET approved='$approve' " .
   $date_string . "\nWHERE id IN ($bids)");
  if (!$database->query()) {
    echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
    exit();
  }
  // Get the dispatcher and load the users plugins.
  $dispatcher = JDispatcher::getInstance();
  JPluginHelper::importPlugin('user');
  $user = new JUser();
  /*----------------------------------------------start approve user----------------------------------------------------------------*/
  if ($approve) {
    foreach($list_id as $item_id) {
      $want_gid = $order->fk_group_id;
      if ($want_gid != 0) {
        $query = "SELECT ug.id FROM #__usergroups AS ug
                LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id 
                WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" .
                 $item_id->id . ")";
        $database->setQuery($query);
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
          $want_groups = $database->loadColumn();
        } else {
          $want_groups = $database->loadResultArray();
        }
        $query = "SELECT ug.title FROM #__usergroups AS ug"
            . "\n LEFT JOIN #__simplemembership_group_joomgroup as sgj ON ug.id=sgj.jgroup_id "
            . "\n WHERE sgj.mgroup_id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" .
             $item_id->id . ")";
        $database->setQuery($query);
        $want_groups_name = $database->loadColumn();
        $want_groups_name = implode(",", $want_groups_name);
        echo $database->getErrorMsg();
        $want_groups = array_combine($want_groups, $want_groups);
        $user->load($item_id->fk_users_id);
        $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
        $user->groups = $want_groups; 
        $user->usertype = $want_groups_name;
        $user->block = 0; 
        $user->activation = 0;

/*---------------------------------------start day_last in groups--------------------------------------------------------------*/
        $query = "SELECT * FROM #__simplemembership_groups AS sg
            WHERE sg.id=(SELECT want_gid FROM #__simplemembership_users WHERE id=" . $item_id->id . ")";
        $database->setQuery($query);
        $usr_group = $database->loadObjectList();
        $usr_group=$usr_group['0'];
        $current_gid = $usr_group->id;
/*---------------------------------------end day_last in groups--------------------------------------------------------------*/
        $user->save();
        $query="UPDATE #__simplemembership_users SET expire_date='".expireDate($usr_group)."',
                current_gid='".$usr_group->id."',
                current_gname='".$usr_group->name."',
                last_approved=now()
                WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
        $database->setQuery($query);
        $database->query();
      }else{
          //// Default group
          $current_gid ='';
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
          $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
          if(empty($is_super_user)) $user->groups = $default_group;
          $user->usertype = $default_group_name;
          $user->block = 0; 
          $user->activation = 0;          
          $user->save();
          $query="UPDATE #__simplemembership_users SET current_gid=-2,
                          current_gname='Default',
                          expire_date = 0,
                          last_approved=now()
                          WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
          $database->setQuery($query);
          $database->query();  
      }

      sync_users($user);
      
      if (version_compare(JVERSION, '3.0.0', 'lt')) {
          JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
      } else {
          $a = JFactory::getMailer();
          $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account approved', stripslashes($user_configuration['user_approve_msg']), 1);
      }
    }
  }/*----------------------------------------------end approve user----------------------------------------------------------------*/
  
  /*----------------------------------------------start unapprove user----------------------------------------------------------------*/
  if (!$approve) {
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
        $query = "SELECT * FROM #__simplemembership_groups
                  WHERE id = ".$item_id->current_gid;
        $database->setQuery($query);
        $usr_group = $database->loadObjectList();
        $usr_group = $usr_group['0'];
        if($usr_group)
          $where = "want_gid='".$usr_group->id."',
                    want_gname='".$usr_group->name."',";
        $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.profile', $user));
        $user->groups = $default_group;
        $user->usertype = $default_group_name;
        $user->save();
        $query="UPDATE #__simplemembership_users SET  ".$where."
                    current_gid=-2,
                    current_gname='Default',
                    expire_date = 0
                    WHERE #__simplemembership_users.fk_users_id='".$item_id->fk_users_id."'";
        $database->setQuery($query);
        $database->query();

      }
      if (version_compare(JVERSION, '3.0.0', 'lt')) {
        JUtility::sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
      } else {
        $a = JFactory::getMailer();
        $a->sendMail($mosConfig_mailfrom, $user_configuration['senders_email'], $item_id->email, 'account disapproved', stripslashes($user_configuration['user_disapprove_msg']), 1);
      }
    }
  }
  /*----------------------------------------------end unapprove user----------------------------------------------------------------*/
  if (count($bid) == 1) {
      $row = new mos_alUser($database);
      $row->checkin($bid[0]);
  }
}

function deleteOrder() {
  global $database;

  $orderIds = $_POST['cb'];
  $option = $_POST['option'];
  foreach($orderIds as $key=>$orderId){
    $sql = "DELETE FROM #__simplemembership_orders WHERE id = ".$orderId." ";
    $database->setQuery($sql);
    $database->query();
    $sql = "DELETE FROM #__simplemembership_orders_details WHERE fk_order_id = ".$orderId." ";
    $database->setQuery($sql);
    $database->query();
  }
  mosRedirect("index.php?option=$option&task=orders");
}


function send_mail_the_end_approve()
{
  global $database;
  $dateNow = date('Y-m-d');
  $dateNowArray = explode('-', $dateNow);
  $dateNowUnixTime = mktime(0, 0, 0, date($dateNowArray[1]), date($dateNowArray[2]),   date($dateNowArray[0]));
  $dateTheEndApproveUnixTime = $dateNowUnixTime + (86400)*3;
  $dateTheEndApprove = date('Y-m-d', $dateTheEndApproveUnixTime);
  $sql = "SELECT * FROM #__simplemembership_users WHERE last_approved = '".$dateTheEndApprove."'";
  $database->setQuery($sql);
  $usersTheEndApprove = $database->loadObjectList();
}


function expireDate($group){
  $expire_days = 0;
  $expire_month = 0;
  $expire_years = 0;
  if($group->expire_units == 'D')
    $expire_days = $group->expire_range;
  if($group->expire_units == 'W')
    $expire_days = $group->expire_range * 7;
  if($group->expire_units == 'M')
    $expire_month = $group->expire_range;
  if($group->expire_units == 'Y')
    $expire_years = $group->expire_range;
  $expire_date=date('Y-m-d H:i:s',
                    mktime(date("H"),
                           date("i"),
                           date("s"),
                           date("m")+$expire_month,
                           date("d")+$expire_days,
                           date("Y")+$expire_years));
  return $expire_date;
}
