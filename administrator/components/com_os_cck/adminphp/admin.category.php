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


class AdminCategory{
  static function showCategories(){
    global $db, $user, $option, $menutype, $app, $jConf;
    $grooups = get_group_children_cck();

    $section = "com_os_cck";
    $sectionid = $app->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $app->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $query = "SELECT  c.*, c.checked_out as checked_out_contact_category, " .
        " c.parent_id as parent, u.name AS editor, COUNT(c_con.id) AS cc , c.rent_request, c.buy_request "
        . "\n FROM #__os_cck_categories AS c"
        . "\n LEFT JOIN #__os_cck_categories_connect AS c_con ON c_con.fk_cid=c.cid"
        . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
        . "\n WHERE c.section='$section'"
        . "\n GROUP BY c.cid"
        . "\n ORDER BY section, parent_id, ordering";

    $db->setQuery($query);
    $rows = $db->loadObjectList();

    if(count($rows)>0)
    {
      $date = strtotime(JFactory::getDate()->toSql());
        foreach ($rows as $row) {
          $check = strtotime($row->checked_out_time);
          $remain = 7200 - ($date - $check);
          if (($remain <= 0) && ($row->checked_out != 0)) {
              $db->setQuery("UPDATE #__os_cck_categories SET checked_out=0,checked_out_time=0");
              $db->query();
              $row->checked_out = 0;
              $row->checked_out_time = 0;
          }
      }
    }

    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }

    foreach ($rows as $k => $v) {
      foreach ($rows as $k1 => $v1) {
        if ($v->cid == $v1->parent_id) $rows[$k]->cc += $v1->cc;
      }

      $rows[$k]->nentity = ($rows[$k]->cc == 0) ? "-" : "<a href=\"index.php?option=com_os_cck&section=categories&catid=" . $v->cid . "\">" . ($v->cc) . "</a>";

      $curgroup = array();
      $ss = explode(',', $v->params);
      foreach ($ss as $s) {
        if ($s == '') $s = '1';
        $curgroup[] = $grooups[$s];
      }
      $rows[$k]->groups = implode(', ', $curgroup);
    }

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
      $pt = $v->parent_id;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push($list, $v);
      $children[$pt] = $list;
    }

    // second pass - get an indent list of the items
    $list = os_cckTreeRecurse(0, '', array(), $children, max(0, $levellimit - 1));
    $total = count($list);
    $pageNav = new JPagination($total, $limitstart, $limit);

    $levellist = JHTML::_('select.integerlist',1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);

    AdminViewCategory::show($list, $user->id, $pageNav, $lists, 'other');
  }

  static function showCategoriesModal(){
    global $db, $user, $option, $menutype, $app, $jConf, $moduleId;
    $grooups = get_group_children_cck();
    $moduleId = JRequest::getVar('module_id','');
    $section = "com_os_cck";
    $sectionid = $app->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $app->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $query = "SELECT  c.*, c.checked_out as checked_out_contact_category, " .
        " c.parent_id as parent, u.name AS editor, COUNT(c_con.id) AS cc , c.rent_request, c.buy_request "
        . "\n FROM #__os_cck_categories AS c"
        . "\n LEFT JOIN #__os_cck_categories_connect AS c_con ON c_con.fk_cid=c.cid"
        . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
        . "\n WHERE c.section='$section'"
        . "\n AND c.published = 1"
        . "\n GROUP BY c.cid"
        . "\n ORDER BY section, parent_id, ordering";

    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }

    foreach ($rows as $k => $v) {
      foreach ($rows as $k1 => $v1) {
        if ($v->cid == $v1->parent_id) $rows[$k]->cc += $v1->cc;
      }

      $rows[$k]->nentity = ($rows[$k]->cc == 0) ? "-" : $v->cc;

      $curgroup = array();
      $ss = explode(',', $v->params);
      foreach ($ss as $s) {
        if ($s == '') $s = '1';
        $curgroup[] = $grooups[$s];
      }
      $rows[$k]->groups = implode(', ', $curgroup);
    }

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
      $pt = $v->parent_id;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push($list, $v);
      $children[$pt] = $list;
    }

    // second pass - get an indent list of the items
    $list = os_cckTreeRecurse(0, '', array(), $children, max(0, $levellimit - 1));
    $total = count($list);
    $pageNav = new JPagination($total, $limitstart, $limit);

    $levellist = JHTML::_('select.integerlist',1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);

    AdminViewCategory::showModal($list, $user->id, $pageNav, $lists, 'other');
  }

  static function showCategoryModalPlg($option){
    global $db, $user, $option, $menutype, $app, $jConf, $moduleId;
    $grooups = get_group_children_cck();
    $moduleId = JRequest::getVar('module_id','');
    $section = "com_os_cck";
    $sectionid = $app->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
    $limitstart = $app->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $app->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);
    $lid = JRequest::getVar('lid','');
    $query = "SELECT  c.*, c.checked_out as checked_out_contact_category, " .
        " c.parent_id as parent, u.name AS editor, COUNT(c_con.id) AS cc , c.rent_request, c.buy_request "
        . "\n FROM #__os_cck_categories AS c"
        . "\n LEFT JOIN #__os_cck_categories_connect AS c_con ON c_con.fk_cid=c.cid"
        . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
        . "\n WHERE c.section='$section'"
        . "\n AND c.published = 1"
        . "\n GROUP BY c.cid"
        . "\n ORDER BY section, parent_id, ordering";

    $db->setQuery($query);
    $rows = $db->loadObjectList();
    if ($db->getErrorNum()) {
        echo $db->stderr();
        return false;
    }

    foreach ($rows as $k => $v) {
      foreach ($rows as $k1 => $v1) {
        if ($v->cid == $v1->parent_id) $rows[$k]->cc += $v1->cc;
      }

      $rows[$k]->nentity = ($rows[$k]->cc == 0) ? "-" : $v->cc;

      $curgroup = array();
      $ss = explode(',', $v->params);
      foreach ($ss as $s) {
        if ($s == '') $s = '1';
        $curgroup[] = $grooups[$s];
      }
      $rows[$k]->groups = implode(', ', $curgroup);
    }

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
      $pt = $v->parent_id;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push($list, $v);
      $children[$pt] = $list;
    }

    // second pass - get an indent list of the items
    $list = os_cckTreeRecurse(0, '', array(), $children, max(0, $levellimit - 1));
    $total = count($list);
    $pageNav = new JPagination($total, $limitstart, $limit);

    $levellist = JHTML::_('select.integerlist',1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);

    AdminViewCategory::showCategoryModalPlg($list, $user->id, $pageNav, $lists, 'other', $lid);
  }

  static function editCategory($section = '', $uid = 0){ 
      global $db, $user,$app;

      $type = mosGetParam($_REQUEST, 'type', '');
      $redirect = mosGetParam($_POST, 'section', '');;
      $row = new os_cckCategory($db);
      // load the row from the db table
      $row->load($uid);
      // fail if checked out not by 'me'
      if ($row->checked_out && $row->checked_out <> $user->id) {
          $app->redirect('index.php?option=com_os_cck&task=show_categories', 'The category ' . $row->title . ' is currently being edited by another administrator');
      }

      if ($uid) {
        
          // existing record
          $row->checkout($user->id);
          // code for Link Menu
      } else {
          // new record
          $row->section = $section;
          $row->published = 1;
      }
      // make order list

      $order = array();

      $db->setQuery("SELECT COUNT(*) FROM #__os_cck_categories WHERE section='$row->section'");
      
      $max = intval($db->loadResult()) + 1;
      for ($i = 1; $i < $max; $i++) {
          $order[] = JHTML::_('select.option',$i);
      }
      // build the html select list for ordering
      $query = "SELECT ordering AS value, title AS text"
          . "\n FROM #__os_cck_categories"
          . "\n WHERE section = '$row->section'"
          . "\n ORDER BY ordering";
      if (version_compare(JVERSION, "3.0.0", "lt")) {
          $lists['ordering'] = JHTML::_('list.specificordering',$row, $uid, $query);
      } else {
          $lists['ordering'] = JHTML::_('list.ordering',$row, $query,$attribs = null, $uid);
      }
      // build the select list for the image positions
      $active = ($row->image_position ? $row->image_position : 'left');
      $lists['image_position'] = JHTML::_('list.positions','image_position', $active, null, 0, 0);
      // Imagelist
      $lists['image'] = HTML::imageList('image', $row->image);
      $lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $row->published);
      //print_r($row);exit;
      // build the html select list for paraent item
      $options = array();
      $options[] = JHTML::_('select.option','0', JText::_('COM_OS_CCK_A_SELECT_TOP'));

      //***********access category
      $gtree = get_group_children_tree_cck();

      $f = array();
      $s = explode(',', $row->params);
      for ($i = 0; $i < count($s); $i++)
          $f[] = JHTML::_('select.option',$s[$i]); 
      if(empty($f))
          $f[] = '1';
      $lists['category']['registrationlevel'] = JHTML::_('select.genericlist',$gtree, 'category_registrationlevel[]', 'size="" multiple="multiple"', 'value', 'text', $f);
      //********end access category
      $lists['parent'] = HTML::categoryParentList($row->cid, "", $options);
      
      AdminViewCategory::edit($row, $section, $lists, $redirect);
  }

  static function saveCategory()
  {
      global $db,$app;

      $row = new os_cckCategory($db);
      $post = JRequest::get('post', JREQUEST_ALLOWHTML);
      if(empty($post['title']) || empty($post['name'])){
        echo "<script> alert('Fill require fields!'); window.history.go(-1); </script>\n";
        exit();
      }
      if (!$row->bind($post)) {
          echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
          exit();
      }

      $row->section = 'com_os_cck';
      $row->parent_id = $_REQUEST['parent_id'];

      if (!$row->check()) {
          echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
          exit();
      }

      //****set access level
      $row->params = implode(',', mosGetParam($_POST, 'category_registrationlevel', ''));
      
      //****end set access level
      if ($row->params == "") $row->params = "1";
      
      if (!$row->store()) {
          echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
          exit();
      }

      $row->checkin();
      $row->updateOrder("section='$row->section' AND parent_id='$row->parent_id'");

      $app->redirect('index.php?option=com_os_cck&task=show_categories');
  }


  //this function check - is exist houses in this folder and folders under this category
  static function is_exist_curr_and_subcategory_items($catid)
  {
      global $db, $user;
      $query = "SELECT *, COUNT(a.fk_cid) AS numlinks FROM #__os_cck_categories AS cc"
          . "\n  JOIN #__os_cck_categories_connect AS a ON a.fk_cid = cc.cid"
          . "\n WHERE  cc.cid='$catid' "
          . "\n GROUP BY cc.cid"
          . "\n ORDER BY cc.ordering";
      $db->setQuery($query);
      $categories = $db->loadObjectList();
      if (count($categories) != 0) return true;

      $query = "SELECT cid "
          . "FROM #__os_cck_categories AS cc "
          . " WHERE parent_id='$catid' ";
      $db->setQuery($query);
      $categories = $db->loadObjectList();
      if (count($categories) == 0) return false;

      foreach ($categories as $k) {
          if (AdminCategory::is_exist_curr_and_subcategory_items($k->cid)) return true;
      }
      return false;
  }

  //end function


  static function removeCategoriesFromDB($cid)
  {
      global $db, $user;

      $query = "SELECT cid  "
          . "FROM #__os_cck_categories AS cc "
          . " WHERE parent_id='$cid' ";
      $db->setQuery($query);
      $categories = $db->loadObjectList();
     // print_r($categories);exit;
      //    echo $db->getErrorMsg() ;

      if (count($categories) != 0) {
          //delete child
          foreach ($categories as $k) {
              AdminCategory::removeCategoriesFromDB($k->cid);
          }
      }

      $sql = "DELETE FROM #__os_cck_categories WHERE cid = $cid ";
      $db->setQuery($sql);
      $db->query();

  }

  static function removeCategories($section, $cid)
  {
      global $db,$app;

      if (count($cid) < 1) {
           $app->redirect('index.php?option=com_os_cck&task=show_categories',JText::_('COM_OS_CCK_SELECT_CAT_DELETE'));
      }

      foreach ($cid as $catid) {
          if (AdminCategory::is_exist_curr_and_subcategory_items($catid)) {
              $app->redirect('index.php?option=com_os_cck&task=show_categories',JText::_('COM_OS_CCK_CONTAIN_INCTANCES'));
          }
      }

      foreach ($cid as $catid) {
          AdminCategory::removeCategoriesFromDB($catid);
      }


      $msg = "Categories " . OS_CCK_DELETED;
      $app->redirect('index.php?option=com_os_cck&task=show_categories&mosmsg=' . $msg);
  }

  static function publishCategories($section, $categoryid = null, $cid = null, $publish = 1)
  {
      global $db, $user, $app;

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

      $query = "UPDATE #__os_cck_categories SET published='$publish'"
          . "\nWHERE cid IN ($cids) AND (checked_out=0 OR (checked_out='$user->id'))";
      $db->setQuery($query);
      if (!$db->query()) {
          echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
          exit();
      }

      if (count($cid) == 1) {
          $row = new os_cckCategory($db);
          $row->checkin($cid[0]);
      }

      $app->redirect('index.php?option=com_os_cck&task=show_categories');
  }

  static function cancelCategory()
  {
      global $db, $app;

      $row = new os_cckCategory($db);
      $row->bind($_POST);
      $row->checkin();
      $app->redirect('index.php?option=com_os_cck&task=show_categories');
  }

  static function orderCategory($uid, $inc)
  {
      global $db, $app;
      $row = new os_cckCategory($db);
      $row->load($uid);
      if ($row->ordering == 1 && $inc == -1) $app->redirect('index.php?option=com_os_cck&task=show_categories');

      $new_order = $row->ordering + $inc;

      //change ordering - for other element
      $query = "UPDATE #__os_cck_categories SET ordering='" . ($row->ordering) . "'"
          . "\nWHERE parent_id = $row->parent_id and ordering=$new_order";
      $db->setQuery($query);
      $db->query();

      //change ordering - for this element
      $query = "UPDATE #__os_cck_categories SET ordering='" . $new_order . "'"
          . "\nWHERE cid = $uid";
      $db->setQuery($query);
      $db->query();

      $app->redirect('index.php?option=com_os_cck&task=show_categories');

  }

  static function accessCategory($uid, $access)
  {
      global $db, $app;

      $row = new os_cckCategory($db);
      $row->load($uid);
      $row->access = $access;

      if (!$row->check()) {
          return $row->getError();
      }
      if (!$row->store()) {
          return $row->getError();
      }

      $app->redirect('index.php?option=com_os_cck&task=show_categories');
  }

}
