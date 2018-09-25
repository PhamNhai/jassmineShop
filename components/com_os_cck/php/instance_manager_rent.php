<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) 
      die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

class InstanceManagerRent{

  static function edit_rent($option, $eiid){

    global $db, $os_cck_configuration, $user, $Itemid;
    $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
    $session = JFactory::getSession();;


    if (empty($eiid)) {
      echo "<script> alert('Select an instance to rent'); window.history.go(-1);</script>\n";
      exit;
    }
    
    $select="SELECT a.*, cc.name AS category, l.id as rent_id, cl.params as lay_params,ce.name AS entity, l.rent_from as rent_from, " .
        "\n l.rent_return as rent_return, l.rent_until as rent_until, " .
        "\n l.user_name as user_name, l.user_email as user_email, " .
        "\n cc.rent_request AS for_rent" .
        "\n FROM #__os_cck_entity_instance AS a" .
        "\n LEFT JOIN #__os_cck_categories_connect AS CC_rel ON CC_rel.fk_eiid = a.eiid" .
        "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = CC_rel.fk_cid" .
        "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = a.fk_lid ".
        "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = a.fk_eid ".
        "\n LEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = a.eiid" .
        "\n WHERE a.eiid = " . $eiid . " ORDER BY l.id DESC";
    $db->setQuery($select);
    if (!$db->query()) {
      echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
      exit ();
    };
    $instances = $db->loadObjectList();
    $show_fields = $entityEaaray = $history_instance = $return_instance =array();
    if(count($instances)>0){
      foreach ($instances as $instance) {
        if($instance->rent_return){
          $history_instance[] = $instance;
        }else if($instance->rent_until){
          $return_instance[] = $instance;
        }
        $lay_params = unserialize($instance->lay_params);
        $entityEaaray[] = $instance->fk_eid;
        $layoutArray[] = $instance->fk_lid;
      }

      

      foreach(array_unique($entityEaaray) as $key => $value){
        $entity = new os_cckEntity($db);
        $entity->load($value);
        $layout = new os_cckLayout($db);
        $layout->load($layoutArray[$key]);
        $bootstrap_version = $session->get( 'bootstrap','2');
        $layout_html = urldecode($layout->getLayoutHtml($bootstrap_version));
        $layout_params = unserialize($layout->params);
        $extra_fields_list = $entity->getFieldList();
  
        foreach($extra_fields_list as $Fieldvalue){

          $field_settings = $Fieldvalue->params;
          $field_settings = json_decode($field_settings, true);
          $field_settings = (isset($field_settings['allowed_value']))?$field_settings['allowed_value']:$field_settings['default_value'];
          
    
          if(isset($field_settings->show_in_instance_menu) && $field_settings->show_in_instance_menu
              && strpos($layout_html,"{|f-".$Fieldvalue->fid."|}")){
            $show_fields[$value][]= $Fieldvalue;
          }
        }

      }
      ksort($show_fields);
    }
    //for rent or not
    $count = count($instances);
    // get list of categories
    $userlist[] = JHTML::_('select.option','-1', 'Select User');
    $db->setQuery("SELECT id AS value, email, name AS text from #__users ORDER BY name");
    $user_array = $db->loadObjectList();
    $userlist = array_merge($userlist, $user_array);
    $usermenu = JHTML::_('select.genericlist',$userlist, 'userid',
                         'class="inputbox" size="1" onchange="fill_user(this.value)"', 'value', 'text', '-1');
    $type = 'instance_manager_rent_edit';
      require getLayoutPathCCK::getLayoutPathCom($option, $type);
  }


  static function rent_return($option, $lids){

    global $db, $os_cck_configuration, $Itemid, $app;
    $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
    if (!is_array($lids) || count($lids) < 1 || $lids[0] == 0) {
      echo "<script> alert('Select an instance to return'); window.history.go(-1);</script>\n";
      exit;
    }
    
    for ($i = 0, $n = count($lids); $i < $n; $i++) {
      $rent = new mosCCK_rent($db);
      $rent->load($lids[$i]);
      if ($rent->rent_return != null) {
        echo "<script> alert('instance already returned'); window.history.go(-1);</script>\n";
        exit;
      }
      $rent->rent_return = date("Y-m-d H:i:s");
      if (!$rent->store()) {
        echo "<script> alert('" . addslashes($rent->getError()) . "'); window.history.go(-1); </script>\n";
        exit ();
      }
    }
    $app->redirect("index.php?option=$option&task=edit_rent&eiid[]=$rent->fk_eiid&Itemid=$Itemid",'ok');
  }

  static function saveRent($option, $eiid,$task = ""){
 
    global $db, $os_cck_configuration, $Itemid, $app;
    $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
    $rent_id = protectInjectionWithoutQuote("return_id", '','ARRAY');
    if ($eiid == "" ) {
      echo "<script> alert('Select an instance to rent'); window.history.go(-1);</script>\n";
      exit;
    }
    $query = "SELECT * FROM #__os_cck_rent where fk_eiid IN( " . $eiid . ") AND rent_return is NULL ";
    $db->setQuery($query);
    $rentTerm = $db->loadObjectList();
    $rent = new mosCCK_rent($db);
    if ($task == "save_rent"  && isset($rent_id[0]) )
      $rent->load($rent_id[0]);
    $rent->rent_from = $rent_from = date("Y-m-d H:i:s", strtotime(protectInjectionWithoutQuote('rent_from','')));
    $rent->rent_until = $rent_until =date("Y-m-d H:i:s", strtotime(protectInjectionWithoutQuote( 'rent_until','')));
    if (empty($rent_until) || strlen($rent_until) < 2) $rent_until = protectInjectionWithoutQuote('rent_until','');
    if ($rent_from > $rent_until) {
      echo "<script> alert('" . $rent_from . " more then " . $rent_until . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    // $rent_from = substr($rent_from, 0, 10);
    // $rent_until = substr($rent_until, 0, 10);
    if (isset($rentTerm[0])) {
      for ($e = 0, $m = count($rentTerm); $e < $m; $e++) {
        if ($task == "save_rent" && isset($rent_id[0]) && $rent_id[0] == $rentTerm[$e]->id)
          continue;
        // $rentTerm[$e]->rent_from = substr($rentTerm[$e]->rent_from, 0, 10);
        // $rentTerm[$e]->rent_until = substr($rentTerm[$e]->rent_until, 0, 10);
        //проверка  аренды

        //rent check
        if($os_cck_configuration->get('by_time')){    //by day

          if (($rent_from >= $rentTerm[$e]->rent_from && $rent_from < $rentTerm[$e]->rent_until) 
            || ($rent_from <= $rentTerm[$e]->rent_from && $rent_until >= $rentTerm[$e]->rent_until) 
            || ($rent_until > $rentTerm[$e]->rent_from && $rent_until <= $rentTerm[$e]->rent_until)) {
            echo "<script> alert('Sorry , this object already rent out from " . 
              $rentTerm[$e]->rent_from . " to " . $rentTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
            exit ();}

        }elseif($os_cck_configuration->get('rent_type')){

          $rent_from = date('Y-m-d',strtotime($rent_from));
          $rent_until = date('Y-m-d',strtotime($rent_until));
          $rentTerm[$e]->rent_from = date('Y-m-d',strtotime($rentTerm[$e]->rent_from));
          $rentTerm[$e]->rent_until = date('Y-m-d',strtotime($rentTerm[$e]->rent_until));

           if (($rent_from >= $rentTerm[$e]->rent_from && $rent_from <= $rentTerm[$e]->rent_until) 
            || ($rent_from <= $rentTerm[$e]->rent_from && $rent_until >= $rentTerm[$e]->rent_until) 
            || ($rent_until >= $rentTerm[$e]->rent_from && $rent_until <= $rentTerm[$e]->rent_until)) {
            echo "<script> alert('Sorry , this object already rent out from " . 
              $rentTerm[$e]->rent_from . " to " . $rentTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
            exit ();}

        }else{  //by night

          $rent_from = date('Y-m-d',strtotime($rent_from));
          $rent_until = date('Y-m-d',strtotime($rent_until));
          $rentTerm[$e]->rent_from = date('Y-m-d',strtotime($rentTerm[$e]->rent_from));
          $rentTerm[$e]->rent_until = date('Y-m-d',strtotime($rentTerm[$e]->rent_until));

          if (($rent_from > $rentTerm[$e]->rent_from && $rent_from < $rentTerm[$e]->rent_until) 
            || ($rent_from < $rentTerm[$e]->rent_from && $rent_until > $rentTerm[$e]->rent_until) 
            || ($rent_until > $rentTerm[$e]->rent_from && $rent_until < $rentTerm[$e]->rent_until)) {
            echo "<script> alert('Sorry , this object already rent out from " . 
              $rentTerm[$e]->rent_from . " to " . $rentTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
            exit ();}

        }

      }
    }
    $rent->fk_eiid = protectInjectionWithoutQuote('eiid','');
    $userid = protectInjectionWithoutQuote('userid','');
    if ($userid == "-1") {
      $rent->user_name = protectInjectionWithoutQuote('user_name', '');
      $rent->user_email = protectInjectionWithoutQuote('user_email', '');
    }else{
      $rent->fk_userid = intval($userid);
      $query = "SELECT name, email FROM #__users WHERE id=$userid";
      $db->setQuery($query);
      $user = $db->loadObjectList();
      $rent->user_name = $user[0]->name;
      $rent->user_email = $user[0]->email;
    }
    $rent->fk_eiid = $eiid;
    if (!$rent->check($rent)) {
      echo "<script> alert('" . addslashes($rent->getError()) . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    if (!$rent->store()) {
      echo "<script> alert('" . addslashes($rent->getError()) . "'); window.history.go(-1); </script>\n";
      exit ();
    }
    $rent->checkin();
      


    $app->redirect("index.php?option=$option&eiid=$eiid&task=edit_rent&Itemid=$Itemid",'ok');
  }

  static function users_rent_history($option){
    global $db,$os_cck_configuration, $user, $mainframe ,$Itemid, $moduleId;
    $Itemid = ($Itemid == 0 || empty($Itemid)) ? intval($_REQUEST['Itemid']) : $Itemid;
    $user = JFactory::getUser();
    if(!$moduleId){
      // Params(cck component menu)
      if (version_compare(JVERSION, '3.0', 'ge')) {
        $menu = new JTableMenu($db);
        $menu->load($Itemid);
        $params = new JRegistry;
        $params->loadString($menu->params);
      } else {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $params = new JRegistry;
        $params = $menu->getParams( $Itemid );
      }//end
    }else{
      $mod_row =  JTable::getInstance ( 'Module', 'JTable' );//load module tables and params
      if (! $mod_row->load ( $moduleId )) {
        JError::raiseError ( 500, $mod_row->getError () );
      }
      //module params
      if (version_compare(JVERSION, '3.0', 'ge')) {
        $params = new JRegistry;
        $params->loadString($mod_row->params);
      } else {
        $params = new JRegistry($mod_row->params);
      }//end
    }
    $session = JFactory::getSession();
    $owner = $user->id; //add nik    
    $rows = $show_fields = array();
    if($owner !=-1){
      $select="SELECT a.*, cc.name AS category, l.id as rent_id, cl.params as lay_params,ce.name AS entity, l.rent_from as rent_from, " .
          "\n l.rent_return as rent_return, l.rent_until as rent_until, " .
          "\n l.user_name as user_name, l.user_email as user_email, " .
          "\n cc.rent_request AS for_rent" .
          "\n FROM #__os_cck_entity_instance AS a" .
          "\n LEFT JOIN #__os_cck_categories_connect AS CC_rel ON CC_rel.fk_eiid = a.eiid" .
          "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = CC_rel.fk_cid" .
          "\n LEFT JOIN #__os_cck_layout AS cl ON cl.lid = a.fk_lid ".
          "\n LEFT JOIN #__os_cck_entity AS ce ON ce.eid = a.fk_eid ".
          "\n LEFT JOIN #__os_cck_rent AS l ON l.fk_eiid = a.eiid" .
          "\n WHERE l.fk_userid=".$db->Quote($owner).
          "\n OR l.user_name=".$db->Quote($owner)." ORDER BY l.id DESC";
      $db->setQuery($select);
      if (!$db->query()) {
        echo "<script> alert('" . addslashes($db->getErrorMsg()) . "'); window.history.go(-1); </script>\n";
        exit ();
      }
      $instances = $db->loadObjectList();

      $show_fields = $entityEaaray = array();


      if(count($instances)>0){
        foreach ($instances as $row) {
          $lay_params = unserialize($row->lay_params);
          $entityEaaray[] = $row->fk_eid;
          $layoutArray[] = $row->fk_lid;
        }
        foreach(array_unique($entityEaaray) as $key => $value){
          $entity = new os_cckEntity($db);
          $entity->load($value);
          $layout = new os_cckLayout($db);
          $layout->load($layoutArray[$key]);
          $bootstrap_version = $session->get( 'bootstrap','2');
          $layout_html = urldecode($layout->getLayoutHtml($bootstrap_version));
          $layout_params = unserialize($layout->params);
          $extra_fields_list = $entity->getFieldList();


        foreach($extra_fields_list as $Fieldvalue){

          $field_settings = $Fieldvalue->params;
          $field_settings = json_decode($field_settings, true);
          $field_settings = (isset($field_settings['allowed_value']))?$field_settings['allowed_value']:$field_settings['default_value'];
          if(isset($field_settings->show_in_instance_menu) && $field_settings->show_in_instance_menu
              && strpos($layout_html,"{|f-".$Fieldvalue->fid."|}")){
            $show_fields[$value][]= $Fieldvalue;
          }
        }
        }
        ksort($show_fields);
      }
    }

    $userlist[] = JHTML::_('select.option','-1', 'Select User');
    $db->setQuery("SELECT DISTINCT fk_userid AS value, user_name AS text from #__os_cck_rent ORDER BY user_name");
    $userlist = array_merge($userlist, $db->loadObjectList());
    foreach ($userlist as $value) {
        if(!$value->value)
            $value->value = $value->text;
    }
    $usermenu = JHTML::_('select.genericlist',$userlist, 'owner_id', 'class="inputbox input-medium" size="1" 
        onchange="document.adminForm.submit();"', 'value', 'text', $owner);

    $type = 'instance_manager_rent_history';
    require getLayoutPathCCK::getLayoutPathCom($option, $type);
  }

}
