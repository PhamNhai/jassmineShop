<?php

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @description OrdaSoft Content Construction Kit
*/

class os_cckEntityInstance extends JTable
{
  /** @var array with all fields */
  //first table fileds 
  var $eiid = null;
  var $fk_lid = null;
  var $fk_eid = null;
  var $title = null;
  var $asset_id = null;
  var $fk_userid = null;
  var $published = null;
  var $approved = null;
  var $created = null;
  var $changed = null;
  var $checked_out = null;
  var $checked_out_time = null;
  var $teid = null;
  var $hits = null;
  var $instance_price = null;
  var $instance_currency = null;
  var $notreaded = null;
  var $featured_clicks = null;
  var $featured_shows = null;

  /**
   * @param database - A database connector object
   */
  function __construct(&$db)
  {
      parent::__construct('#__os_cck_entity_instance', 'eiid', $db);
  }


  function checkMimeTypeFromFiles($ext) {
    $this->_db->setQuery("SELECT mime_ext FROM #__os_cck_mime_types WHERE mime_type=".$this->_db->quote($ext));
    $type = $this->_db->loadColumn();
    if(!$type)
      $type = 'unknown';
    return $type;
  }

  function check(){

    $db =  JFactory::getDBO();
    $layout_params = $this->_layout_params;

    foreach ($this->_field_list as $field) {
     
      //image-field
      if($field->field_type == 'imagefield'  && $_FILES['fi_'.$field->db_field_name]['error'] == ""){
        
        $imagesize = getimagesize($_FILES['fi_'.$field->db_field_name]['tmp_name']);
        $width = $imagesize[0];
        $height = $imagesize[1];
        $max_upload_size = (int)$layout_params[$field->db_field_name . "_max_upload_size"];//mb

        $allow_ext = trim($layout_params[$field->db_field_name . "_allow_ext"]);
        if(strlen($allow_ext)<1){
          $allow_ext = "jpg,jpeg,png,gif";
        }
        $allow_ext = str_ireplace(' ', '', $allow_ext);
        $allow_ext_array = explode(',', $allow_ext);
        
        $max_width = (int)$layout_params[$field->db_field_name . "_max_width"];
        $max_height = (int)$layout_params[$field->db_field_name . "_max_height"];

        $ext = $this->checkMimeTypeFromFiles($_FILES['fi_'.$field->db_field_name]['type']);

        if($ext == 'unknown'){
          $ext = pathinfo($_FILES['fi_'.$field->db_field_name]['name'], PATHINFO_EXTENSION);
          if(isset($allow_ext_array) && is_array($allow_ext_array) && array_search($ext, $allow_ext_array) === false){
            echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                  window.history.go(-1); </script>\n";
            exit;
          }
        }else{
          $temp_array = array_intersect($ext, $allow_ext_array) ;
          if(isset($allow_ext_array) && is_array($allow_ext_array) && empty($temp_array) ){
            echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                  window.history.go(-1); </script>\n";
            exit;
          }
        }

        //max upload size
        if($max_upload_size != 0 && $_FILES['fi_'.$field->db_field_name]['size'] > ($max_upload_size*1000000)){
           echo "<script> alert('". $_FILES['fi_'.$field->db_field_name]['name'] ." ".JText::_('COM_OS_CCK_IMAGE_FILE_TOO_LARGE')." " . $max_upload_size . "mb'); 
             window.history.go(-1); </script>\n";
           exit;
        }

        //max width & max height
        if($max_width != 0 && $width>$max_width){
          echo "<script> alert('".JText::_('COM_OS_CCK_MAX_WIDTH_FOR_IMAGE')." ". $max_width ."px'); 
                window.history.go(-1); </script>\n";
          exit;
        }elseif($max_height != 0 && $height>$max_height){
          echo "<script> alert('".JText::_('COM_OS_CCK_MAX_HEIGHT_FOR_IMAGE')." ". $max_height ."px'); 
                window.history.go(-1); </script>\n";
          exit;
        }

      }
      
      //file-field
      if($field->field_type == 'filefield' && $_FILES['fi_'.$field->db_field_name]['error'] == ""){

        $max_upload_size = (int)$layout_params[$field->db_field_name . "_max_upload_size"];//mb
        $allow_ext = trim($layout_params[$field->db_field_name . "_allow_ext"]);
        $allow_ext = str_ireplace(' ', '', $allow_ext);
        if($allow_ext != ""){
          $allow_ext_array = explode(',', $allow_ext);
        }

        $ext = $this->checkMimeTypeFromFiles($_FILES['fi_'.$field->db_field_name]['type']);

        if($ext == 'unknown'){
          $ext = pathinfo($_FILES['fi_'.$field->db_field_name]['name'], PATHINFO_EXTENSION);
          if(isset($allow_ext_array) && is_array($allow_ext_array) && array_search($ext, $allow_ext_array) === false){
            echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                  window.history.go(-1); </script>\n";
            exit;
          }
        }else{
          $temp_array = array_intersect($ext, $allow_ext_array) ;
          if(isset($allow_ext_array) && is_array($allow_ext_array) && empty($temp_array) ){
            echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                  window.history.go(-1); </script>\n";
            exit;
          }
        }

        if($max_upload_size != 0 && $_FILES['fi_'.$field->db_field_name]['size'] > ($max_upload_size*1000000)){
           echo "<script> alert('". $_FILES['fi_'.$field->db_field_name]['name'] ." ".JText::_('COM_OS_CCK_FILE_TOO_LARGE')." " . $max_upload_size . "mb'); 
             window.history.go(-1); </script>\n";
           exit;
        }

      }

      //audiofield
      if($field->field_type == 'audiofield'){
        
        for($i=1;isset($_FILES['new_upload_audio'.$i]);$i++){

          $max_upload_size = (int)$layout_params[$field->db_field_name . "_max_upload_size"];//mb
          $allow_ext = trim($layout_params[$field->db_field_name . "_allow_ext"]);
          $allow_ext = str_ireplace(' ', '', $allow_ext);
          if($allow_ext != ""){
            $allow_ext_array = explode(',', $allow_ext);
          }

          $ext = $this->checkMimeTypeFromFiles($_FILES['new_upload_audio'.$i]['type']);

          if($ext == 'unknown'){
            $ext = pathinfo($_FILES['new_upload_audio'.$i]['name'], PATHINFO_EXTENSION);
            if(isset($allow_ext_array) && is_array($allow_ext_array) && array_search($ext, $allow_ext_array) === false){
              echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                    window.history.go(-1); </script>\n";
              exit;
            }
          }else{
            $temp_array = array_intersect($ext, $allow_ext_array) ;
            if(isset($allow_ext_array) && is_array($allow_ext_array) && empty($temp_array) ){
              echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                    window.history.go(-1); </script>\n";
              exit;
            }
          }

          if($max_upload_size != 0 && $_FILES['new_upload_audio'.$i]['size'] > ($max_upload_size*1000000)){
             echo "<script> alert('". $_FILES['new_upload_audio'.$i]['name'] ." ".JText::_('COM_OS_CCK_AUDIO_FILE_TOO_LARGE')." " . $max_upload_size . "mb'); 
               window.history.go(-1); </script>\n";
             exit;
          }
        }

      }

      //videofield
      if($field->field_type == 'videofield' ){
      
        for($i=1;isset($_FILES['new_upload_video'.$i]);$i++){

          $max_upload_size = (int)$layout_params[$field->db_field_name . "_max_upload_size"];//mb
          $allow_ext = trim($layout_params[$field->db_field_name . "_allow_ext"]);
          $allow_ext = str_ireplace(' ', '', $allow_ext);
          if($allow_ext != ""){
            $allow_ext_array = explode(',', $allow_ext);
          }

          $ext = $this->checkMimeTypeFromFiles($_FILES['new_upload_video'.$i]['type']);

          if($ext == 'unknown'){
            $ext = pathinfo($_FILES['new_upload_video'.$i]['name'], PATHINFO_EXTENSION);
            if(isset($allow_ext_array) && is_array($allow_ext_array) && array_search($ext, $allow_ext_array) === false){
              echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                    window.history.go(-1); </script>\n";
              exit;
            }
          }else{
            $temp_array = array_intersect($ext, $allow_ext_array) ;
            if(isset($allow_ext_array) && is_array($allow_ext_array) && empty($temp_array) ){
              echo "<script> alert('".JText::_('COM_OS_CCK_EXT_NOT_SUPPORTED')." ".$allow_ext."'); 
                    window.history.go(-1); </script>\n";
              exit;
            }
          }

          if($max_upload_size != 0 && $_FILES['new_upload_video'.$i]['size'] > ($max_upload_size*1000000)){
             echo "<script> alert('". $_FILES['new_upload_video'.$i]['name'] ." ".JText::_('COM_OS_CCK_VIDEO_FILE_TOO_LARGE')." " . $max_upload_size . "mb'); 
               window.history.go(-1); </script>\n";
             exit;
          }

        }

      }
      
    }

    // Forbidden string in extension (e.g. php matched .php, .xxx.php, .php.xxx and so on)
      // 'forbidden_extensions'       => array(
      //   'php', 'phps', 'pht', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7', 'inc', 'pl', 'cgi', 'fcgi', 'java', 'jar', 'py',

   
  }

  function quoteName($name){
    $return = $this->_db->quoteName($name);
    return $return;
  }

  function load($id = null, $bool = true){
    parent::load($id);
  }

  public function getReviews($eiid = 0){
    $query = "SELECT ei2.*,u.*,l.params FROM #__os_cck_entity_instance as ei"
              ."\n LEFT JOIN #__os_cck_child_parent_connect as pc ON pc.fid_parent = ei.eiid"
              ."\n LEFT JOIN #__os_cck_entity_instance as ei2 ON pc.fid_child = ei2.eiid"
              ."\n LEFT JOIN #__os_cck_layout as l ON l.lid = ei2.fk_lid"
              ."\n LEFT JOIN #__users as u ON u.id = ei2.fk_userid"
              ."\n WHERE fid_parent=$this->eiid AND l.type = 'review_instance' AND ei2.published = 1 AND ei2.approved = 1";
    $this->_db->setQuery($query);
    return $this->_db->loadObjectList();
  }

  /**
   * Binds a named array/hash to this object
   *
   * Can be overloaded/supplemented by the child class
   *
   * @access  public
   * @param $from mixed An associative array or object
   * @param $ignore mixed An array or space separated list of fields not to bind
   * @return  boolean
   */
  function bind($from, $ignore = array()){
    $fromArray = is_array($from);
    $fromObject = is_object($from);
    if (!$fromArray && !$fromObject) {
      $this->setError(get_class($this) . '::bind failed. Invalid from argument');
      return false;
    }
    if (!is_array($ignore)) {
      $ignore = explode(' ', $ignore);
    }
    foreach ($this->getProperties() as $k => $v) {
      // internal attributes of an object are ignored
      if (!in_array($k, $ignore)) {
        if ($fromArray && isset($from[$k])) {
            $this->$k = $from[$k];
        } else if ($fromObject && isset($from->$k)) {
            $this->$k = $from->$k;
        }
      }
    }
    return true;
  }
  
  function require_check(){
    $db =  JFactory::getDBO();
    foreach ($this->_field_list as $field) {
      if($field->field_type == 'captcha_field' || $field->field_type == 'galleryfield')continue;
      if(!isset($this->_layout_params[$field->db_field_name.'_required']) || $this->_layout_params[$field->db_field_name.'_required'] != 'on' ) continue;
      if($this->_layout_params[$field->db_field_name.'_published'] != 'on') continue;
      if($field->published == 0) continue;
      $field->value = '';
      foreach ($this->fields_data as $key => $var) {
        if ($field->db_field_name == $key)
          $field->value = $var;
      }
      if($field->field_type=='filefield' || $field->field_type=='imagefield'){
        $file = new os_cckFile($db);
        $old_data = $this->getFieldValue($field);
        if(empty($old_data) && (!$old_data['0']->fid && $_FILES['fi_'.$field->db_field_name]['error'] == 4)){
          return false;
        }
      }
      if($field->field_type=='videofield'){
        $old_data = $this->getFieldValue($field);
        if(empty($old_data['video']) || ($_FILES['fi_'.$field->db_field_name]['error'] == 4)){
          return false;
        }
      }
      if($field->field_type=='audio'){
        $old_data = $this->getFieldValue($field);
        if(empty($old_data['audio']) || ($_FILES['fi_'.$field->db_field_name]['error'] == 4)){
          return false;
        }
      }
      if($field->field_type == 'locationfield') {
        $adress = protectInjectionWithoutQuote('fi_'.$field->db_field_name . "_map_address");
        $vlat = protectInjectionWithoutQuote('fi_'.$field->db_field_name   . "_map_latitude");
        $vlong = protectInjectionWithoutQuote('fi_'.$field->db_field_name  . "_map_longitude");
        $zoom = protectInjectionWithoutQuote('fi_'.$field->db_field_name   . "_map_zoom");
        if(empty($adress)
          || empty($vlat)
          || empty($vlong)
          || empty($zoom)){
          return false;
      }
      }
      if ($field->field_type == 'text_url') {
        if(strlen($field->value) < 8){
          return false;
        }
      }
      if ($field->field_type == 'text_select_list') {
        if(empty($field->value) || $field->value === 0){
          return false;
        }
      }
      if ($field->field_type == 'categoryfield') {
        $categories = protectInjectionWithoutQuote("categories","");
        if(!isset($categories[0]) && empty($categories[0])){
          return false;
        }
      }
      if($field->field_type!='filefield'
        && $field->field_type!='text_single_checkbox_onoff'
        && $field->field_type!='text_radio_buttons'
        && $field->field_type!='locationfield'
        && $field->field_type!='text_select_list'
        && $field->field_type!='imagefield'
        && $field->field_type!='text_url'
        && $field->field_type!='videofield'
        && $field->field_type!='audiofield'
        && $field->field_type!='categoryfield'
        && !$field->value){
          return false;
      }
    }
    return true;
  }
  
  /**
   * Inserts a new row if id is zero or updates an existing row in the database table
   *
   * Can be overloaded/supplemented by the child class
   *
   * @access public
   * @param boolean If false, null object variables are not updated
   * @return null|string null if successful otherwise returns and error message
   */
  function store($updateNulls = false){
    global $app, $global_settings;
    parent::store();
    $query = "SHOW TABLES LIKE '".$this->_db->getprefix()."os_cck_content_entity_".$this->fk_eid."' ";
    $this->_db->setquery($query);
    $table_exists = $this->_db->loadresult();
    if ($table_exists) {
      $query = " SELECT * FROM #__os_cck_content_entity_" . $this->fk_eid . " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadResult();
      if (!$result) {
        $query = " INSERT INTO #__os_cck_content_entity_" . $this->fk_eid . " SET fk_eiid='" . $this->eiid . "' ";
        $this->_db->setQuery($query);
        $this->_db->query();
      }
    }

    if(isset($this->_field_list)){
      foreach ($this->_field_list as $field) {
  	    if($field->published == 0) continue;
          $field->value = '';
        foreach ($this->fields_data as $key => $var) {
          if ($field->db_field_name == $key)
            $field->value = $var;
        }
        if($field->field_type == 'categoryfield'){
          $query = " DELETE FROM  #__os_cck_categories_connect WHERE fk_eiid='" . $this->eiid . "' ";
          $this->_db->setQuery($query);
          $this->_db->query();
          echo $this->_db->getErrorMsg();
          if (count($this->categories) > 0) {
            $insert = array();
            foreach ($this->categories as $temp) {
              $insert[] = " ( " . $this->eiid . ", " . $temp . " ) ";
            }
            $query = " INSERT INTO #__os_cck_categories_connect (fk_eiid, fk_cid) VALUES " . implode(", ", $insert);
            $this->_db->setQuery($query);
            $this->_db->query();
          }
        }
        if ($field->field_type == 'text_url') {
          if(strlen($field->value) < 8)$field->value = '';
        }
        if($field->field_type == 'decimal_textfield'){
          if(empty($field->value) && $field->value != '0'){
            $query = " UPDATE #__os_cck_content_entity_" . $this->fk_eid . 
              " SET " . $this->_db->quoteName($field->db_field_name) . "= NULL WHERE fk_eiid='" . $this->eiid . "' ";
            $this->_db->setQuery($query);
            $this->_db->query();
            continue;                       
          }
        }
        if ($field->field_type == 'datetime_popup') {
          if($global_settings['default_field_value'] == 'CHANGE'){
            $date = new DateTime();
            $field->value = $date->format('Y-m-d H:i:s');
          }
          if($global_settings['default_field_value'] == 'CREATE'){
            $query = " SELECT created FROM #__os_cck_entity_instance WHERE eiid='" . $this->eiid . "' ";
            $this->_db->setQuery($query);
            $field->value = $this->_db->loadResult();
          }
          $field->value = date('Y-m-d H:i:s',strtotime($field->value));  
        }
        if ($field->field_type == 'filefield' || $field->field_type == 'imagefield') {
          if (!($field = $this->_insertFiles($field)))
            continue;
        }
        if ($field->field_type == 'locationfield') {
          $adress = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_address",null,"STRING"));
          $country = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_country",null,"STRING"));
          $city = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_city",null,"STRING"));
          $region = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_region]",null,"STRING"));
          $zip = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_zip_code",null,"STRING"));
          $latitude = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_latitude",null,"STRING"));
          $longitude = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_longitude",null,"STRING"));
          $zoom = $this->_db->Quote($app->input->get('fi_'.$field->db_field_name . "_map_zoom",null,"STRING"));
          
          $query = " UPDATE #__os_cck_content_entity_" . $this->fk_eid . " SET " .
              $this->_db->quoteName($field->db_field_name."_vlat")." = ".$latitude.",".
              $this->_db->quoteName($field->db_field_name."_vlong")."=".$longitude.",".
              $this->_db->quoteName($field->db_field_name."_zoom")."=".$zoom.",".
              $this->_db->quoteName($field->db_field_name."_country")."=".$country.",".
              $this->_db->quoteName($field->db_field_name."_city")."=".$city.",".
              $this->_db->quoteName($field->db_field_name."_region")."=".$region.",".
              $this->_db->quoteName($field->db_field_name."_zipcode")."=".$zip.",".              
              $this->_db->quoteName($field->db_field_name."_address")."=".$adress."  ".
                " WHERE fk_eiid='" . $this->eiid . "' ";
          $this->_db->setQuery($query);
          $this->_db->query();
        } else if($field->field_type == 'videofield'){
      ////////////////////////////STORE video/track functions START\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          for ($j = 1;isset($_FILES['new_upload_track' . $j]) 
            || array_key_exists('new_upload_track_url' . $j, $_POST);$j++) {
              $track_name = '';
              if (isset($_FILES['new_upload_track' . $j]) && $_FILES['new_upload_track' . $j]['name'] != "") {
                //storing e-Document
                $track = JRequest::getVar('new_upload_track' . $j, '', 'files');
                $code = $this->guid();
                $track_name = $code . '_' . $this->filter($track['name']);
                if (intval($track['error']) > 0 && intval($track['error']) < 4) {
                  echo "<script> alert('" . COM_OS_CCK_LABEL_TRACK_UPLOAD_ERROR . " - " .
                                       $track_name . "'); window.history.go(-1); </script>\n";
                  exit();
                } else if (intval($track['error']) != 4) {
                  $track_new = JPATH_SITE .'/components/com_os_cck/files/track/'. $track_name;
                  if (!move_uploaded_file($track['tmp_name'], $track_new)) {
                    echo "<script> alert('" . COM_OS_CCK_LABEL_TRACK_UPLOAD_ERROR . " - " .
                                         $track_name . "'); window.history.go(-1); </script>\n";
                    exit();
                  }
                }
              }
              if (array_key_exists('new_upload_track_kind' . $j, $_POST) 
                && $_POST['new_upload_track_kind' . $j] != "") {
                  $uploadTrackKind = JRequest::getVar('new_upload_track_kind' . $j, '', 'post');
                  $uploadTrackKind = strip_tags(trim($uploadTrackKind));
              }
              if (array_key_exists('new_upload_track_scrlang' . $j, $_POST) 
                && $_POST['new_upload_track_scrlang' . $j] != "") {
                  $uploadTrackScrlang = JRequest::getVar('new_upload_track_scrlang' . $j, '', 'post');
                  $uploadTrackScrlang = strip_tags(trim($uploadTrackScrlang));
              }
              if (array_key_exists('new_upload_track_label' . $j, $_POST) 
                && $_POST['new_upload_track_label' . $j] != "") {
                  $uploadTrackLabel = JRequest::getVar('new_upload_track_label' . $j, '', 'post');
                  $uploadTrackLabel = strip_tags(trim($uploadTrackLabel));
              }
              if (array_key_exists('new_upload_track_url' . $j, $_POST) && $_POST['new_upload_track_url' . $j] != "") {
                $uploadTrackURL = JRequest::getVar('new_upload_track_url' . $j, '', 'post');
                $uploadTrackURL = strip_tags(trim($uploadTrackURL));
                if (empty($track_name) && !empty($uploadTrackURL))
                  $this->saveTracks($this->eiid, $uploadTrackURL, $uploadTrackKind, $uploadTrackScrlang, $uploadTrackLabel);          
              }
              if (!empty($track_name)) 
                $this->saveTracks($this->eiid, $track_name, $uploadTrackKind, $uploadTrackScrlang, $uploadTrackLabel);
          }

          for ($j = 1;isset($_FILES['new_upload_video' . $j]) 
            || array_key_exists('new_upload_video_url' . $j, $_POST) 
            || array_key_exists('new_upload_video_youtube_code' . $j, $_POST);$j++) {
              $video_name = '';
              if (isset($_FILES['new_upload_video' . $j]) && $_FILES['new_upload_video' . $j]['name'] != "") {
                //storing e-Document
                $video = JRequest::getVar('new_upload_video' . $j, '', 'files');
                $ext = pathinfo($video['name'], PATHINFO_EXTENSION);
                $type = $this->checkMimeType($ext);
                $code = $this->guid();
                $video_name = $code . '_' . $this->filter($video['name']);
                if (intval($video['error']) > 0 && intval($video['error']) < 4) {
                  echo "<script> alert('" . COM_OS_CCK_LABEL_VIDEO_UPLOAD_ERROR . " - " .
                                         $video_name . "'); window.history.go(-1); </script>\n";
                  exit();
                } else if (intval($video['error']) != 4) {
                  $video_new = JPATH_SITE . '/components/com_os_cck/files/video/'  . $video_name;
                  if (!move_uploaded_file($video['tmp_name'], $video_new)) {
                    echo "<script> alert('" . COM_OS_CCK_LABEL_VIDEO_UPLOAD_ERROR . " - " .
                                         $video_name . "'); window.history.go(-1); </script>\n";
                    exit();
                  }
                  $this->saveVideos($video_name, $this->eiid, $type);
                }
              }
              if (array_key_exists('new_upload_video_url' . $j, $_POST) && $_POST['new_upload_video_url' . $j] != "") {
                $uploadVideoURL = JRequest::getVar('new_upload_video_url' . $j, '', 'post');
                $uploadVideoURL = strip_tags(trim($uploadVideoURL));
                $end = explode(".", $uploadVideoURL);
                $ext = end($end);
                $type = $this->checkMimeType($ext);
                if(empty($video_name) && !empty($uploadVideoURL))
                  $this->saveVideos($uploadVideoURL, $this->eiid, $type);
              }
              if (array_key_exists('new_upload_video_youtube_code' . $j, $_POST) 
                && $_POST['new_upload_video_youtube_code' . $j] != "") {
                  $uploadVideoYoutubeCode = JRequest::getVar('new_upload_video_youtube_code' . $j, '', 'post');
                  $uploadVideoYoutubeCode = strip_tags(trim($uploadVideoYoutubeCode));
                  $this->saveYouTubeCode($uploadVideoYoutubeCode, $this->eiid);
              }
          }
      ////////////////////////////STORE video/track functions END\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
      ////////////////////////////DELETE video/track functions END\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $this->deleteTracks($this->eiid);
          $this->deleteVideos($this->eiid);
      ////////////////////////////DELETE video/track functions END\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        }else if($field->field_type == 'audiofield'){
////////////////////////////STORE audio functions START\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          for ($j = 1;isset($_FILES['new_upload_audio' . $j]) 
                || array_key_exists('new_upload_audio_url' . $j, $_POST);$j++) {
            if (isset($_FILES['new_upload_audio' . $j]) && $_FILES['new_upload_audio' . $j]['name'] != "") {
              //storing e-Document
              $audio = JRequest::getVar('new_upload_audio' . $j, '', 'files');
              $ext = pathinfo($audio['name'], PATHINFO_EXTENSION);
              $type = $this->checkMimeType($ext);
              $code = $this->guid();
              $audio_name = $code . '_' . $this->filter($audio['name']);
              //mime_content_type($file_name);
              //if( !isset($_FILES['new_upload_file'.$i]) ) continue;
              if (intval($audio['error']) > 0 && intval($audio['error']) < 4) {
                  echo "<script> alert('" . JText::_('COM_OS_CCK_LABEL_AUDIO_UPLOAD_ERROR') . " - " . 
                    $audio_name . "'); window.history.go(-1); </script>\n";
                  exit();
              } else if (intval($audio['error']) != 4) {
                  $audio_new = JPATH_SITE . '/components/com_os_cck/files/audio/' . $audio_name;
                  if (!move_uploaded_file($audio['tmp_name'], $audio_new)) {
                      echo "<script> alert('" . JText::_('COM_OS_CCK_LABEL_AUDIO_UPLOAD_ERROR') . " - " . 
                        $audio_name . "'); window.history.go(-1); </script>\n";
                      exit();
                  }
                  $this->saveAudios($audio_name, $this->eiid, $type);
              }
            }
            if (array_key_exists('new_upload_audio_url' . $j, $_POST) && $_POST['new_upload_audio_url' . $j] != "") {
              if (isset($_FILES['new_upload_audio' . $j]) && $_FILES['new_upload_audio' . $j]['name'] == "") {
                  $uploadAudioURL = JRequest::getVar('new_upload_audio_url' . $j, '', 'post');
                  $uploadAudioURL = strip_tags(trim($uploadAudioURL));
                  $end = explode(".", $uploadAudioURL);
                  $ext = end($end);
                  $type = $this->checkMimeType($ext);
                  $this->saveAudios($uploadAudioURL, $this->eiid, $type);
              }
            }
          }
////////////////////////////STORE audio functions STOP\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////////DELETE AUDIO functions END\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
          $this->deleteAudios($this->eiid);
////////////////////////////DELETE AUDIO functions END\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        }else if ($field->field_type == "galleryfield" 
                  || $field->field_type == "imagefield"
                  || $field->field_type == "filefield") {
          $query = " UPDATE #__os_cck_content_entity_" . $this->fk_eid . " SET " .
              $field->db_field_name . "_fid  ='" . $field->fid . "' , " .
              $field->db_field_name . "_list ='' , " .
              $field->db_field_name . "_data  ='" . $field->value . "'  " .
              " WHERE fk_eiid='" . $this->eiid . "' ";
          $this->_db->setQuery($query);
          $this->_db->query();
        }else{
          $query = " UPDATE #__os_cck_content_entity_" . $this->fk_eid . 
            " SET " . $this->_db->quoteName($field->db_field_name) . "=" . $this->_db->Quote($field->value) . 
            " WHERE fk_eiid='" . $this->eiid . "' ";
          $this->_db->setQuery($query);
          $this->_db->query();
        }
      }
    }
    //end foreach _list_field
  }

  function _insertFiles($field){
      $db =  JFactory::getDBO();
      $file = new os_cckFile($db);
      @ $uploadPath = ($field->field_type == 'imagefield') ?
          JPATH_SITE . '/components/com_os_cck/files/images/' .
                      $field->db_field_name . "_" . time() . "_" .
                      $_FILES["fi_" . $field->db_field_name]['name'] :
          JPATH_SITE . '/components/com_os_cck/files/' . $field->db_field_name . "_" .
                      time() . "_" . $_FILES["fi_" . $field->db_field_name]['name'];
      $old_data = $this->getFieldValue($field);
      $file = $this->_saveFile($field, $uploadPath, '', $old_data[0]);
      $field->fid = ($file) ? $file->fid : $old_data[0]->fid;
      $field->value = ($file) ? $file->filepath : $old_data[0]->data;
      return $field;
  }

  function _saveFile($field, $uploadPath, $multiple_num = '', $old_data){
      //prepare for multiple or not multiple
      $fieldName = $field->db_field_name;
      $db =  JFactory::getDBO();
      $file = new os_cckFile($db);
      if (isset($_REQUEST["delete_fi_" . $fieldName]) || isset($_REQUEST["delete_fi_" . $fieldName . "_" . $multiple_num])) {
          $file_del = ($multiple_num === '') ?
              protectInjectionWithoutQuote("delete_fi_" . $fieldName) : protectInjectionWithoutQuote("delete_fi_" . $fieldName . "_" . $multiple_num);
          if (isset($file_del) && $file_del != '') {
              $file->load($old_data->fid);
              $file->delete();
              $file->fid = '';
              $file->filepath = '';
              $file->filename = '';
              return $file;
          }
      }
      $fieldName = "fi_" . $fieldName;
      if(($field->field_type == 'imagefield' || $field->field_type == 'filefield')
         && ($field->required && !empty($_FILES[$fieldName]['name'] ) && $old_data->fid)){ 
          $file->load($old_data->fid);
          $file->delete();
          $file->fid = '';
          $file->filepath = '';
          $file->filename = '';
      }
      if (!(isset($_FILES[$fieldName]) || isset($_FILES[$fieldName . "_" . $multiple_num]['name'])))
          return false;
      $array['error'] = ($multiple_num === '') ? $_FILES[$fieldName]['error'] : $_FILES[$fieldName . "_" . $multiple_num]['error'];
      $array['name'] = ($multiple_num === '') ? $_FILES[$fieldName]['name'] : $_FILES[$fieldName . "_" . $multiple_num]['name'];
      $array['tmp_name'] = ($multiple_num === '') ? $_FILES[$fieldName]['tmp_name'] : $_FILES[$fieldName . "_" . $multiple_num]['tmp_name'];
      $array['type'] = ($multiple_num === '') ? $_FILES[$fieldName]['type'] : $_FILES[$fieldName . "_" . $multiple_num]['type'];
      $array['size'] = ($multiple_num === '') ? $_FILES[$fieldName]['size'] : $_FILES[$fieldName . "_" . $multiple_num]['size'];
      if ($array['name'] == '')
          return false;
      if ($array['error'] > 0) {
          switch ($array['error']) {
              case 1:
                  echo JText::_('FILE TO LARGE THAN PHP INI ALLOWS');
                  return false;
              case 2:
                  echo JText::_('FILE TO LARGE THAN HTML FORM ALLOWS');
                  return false;
              case 3:
                  echo JText::_('ERROR PARTIAL UPLOAD');
                  return false;
              case 4:
                  echo JText::_('ERROR NO FILE');
                  return false;
          }
      }
      if (!JFile::upload($array['tmp_name'], $uploadPath)) {
          echo JText::_('ERROR MOVING FILE');
          echo "<pre>";
          var_dump($fieldName);
          print_r($_FILES);
          print_r("----" . $uploadPath);
          print_r($array);
          echo "</pre>";
          return false;
      }

      $file->filename = $array['name'];
      $file->filepath = str_replace(JPATH_SITE, '', $uploadPath);
      $file->filemime = $array['type'];
      $file->filesize = $array['size'];
      $file->status = 1;
      $file->timestamp = time();
      $file->store();
      return $file;
  }

  function getFields( $reload = false ){
    $query = "SELECT * FROM #__os_cck_entity_field WHERE published='1' AND fk_eid='" . $this->fk_eid .
       "' ORDER BY fk_eid, fid ";
    $this->_db->setQuery($query);
    return $this->_db->loadObjectList();
  }

  function getField($fid){
    
    $query = "SELECT * FROM #__os_cck_entity_field WHERE fid = '".$fid."'";
    $this->_db->setQuery($query);
    return $this->_db->loadObject();
    
  }

  function getSelectFieldValue($fid){
    if(!$this->eiid) return '';

      $query = " SELECT " . $this->_db->quoteName('text_select_list_'.(int)$fid) . 
        " AS data FROM #__os_cck_content_entity_" . $this->fk_eid . " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadResult();
    
    return $result;
  }


  function getFieldValue($field, $multiple_num = 0){
    if(!$this->eiid)return '';
    if ($field->field_type == 'videofield') {
      $query = " SELECT * FROM #__os_cck_video_source WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result[0] = $this->_db->loadObjectList();
      $query = " SELECT * FROM #__os_cck_track_source WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result[1] = $this->_db->loadObjectList();
    }elseif ($field->field_type == 'audiofield') {
      $query = " SELECT * FROM #__os_cck_audio_source WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }elseif ($field->field_type == 'categoryfield') {
      $query = "SELECT cc.title AS title ,cc.cid as catid" .
          "\n FROM #__os_cck_entity_instance AS jei " .
          "\n LEFT JOIN #__os_cck_categories_connect AS c ON jei.eiid=c.fk_eiid " .
          "\n LEFT JOIN #__os_cck_categories AS cc ON cc.cid = c.fk_cid " .
          "\n WHERE jei.eiid=$this->eiid";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }elseif ($field->field_type == 'imagefield' || $field->field_type == 'filefield') {
      $query = " SELECT " . $field->db_field_name . "_data AS data, " . 
        $field->db_field_name . "_fid AS fid FROM #__os_cck_content_entity_" . $this->fk_eid . 
        " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }elseif ($field->field_type == 'locationfield'){
      $query = " SELECT ".$field->db_field_name . "_address AS address, ".
          $field->db_field_name . "_vlat AS vlat, ".
          $field->db_field_name . "_vlong AS vlong, ".
          $field->db_field_name . "_zoom AS zoom, ".
          $field->db_field_name . "_country AS country, ".
          $field->db_field_name . "_region AS region, ".
          $field->db_field_name . "_city AS city, ".
          $field->db_field_name . "_zipcode AS zipcode".
          " FROM #__os_cck_content_entity_" . $this->fk_eid . 
          " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }elseif ($field->field_type == 'galleryfield'){
      $query = " SELECT " . $field->db_field_name . "_data AS data, " . 
        $field->db_field_name . "_fid AS fid FROM #__os_cck_content_entity_" . $this->fk_eid . 
        " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }else{
      $query = " SELECT " . $this->_db->quoteName($field->db_field_name) . 
        " AS data FROM #__os_cck_content_entity_" . $this->fk_eid . " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObjectList();
    }

    return $result;
  }

    function getFieldValueCalImport($field_name, $location = false){

    if($field_name == '-1' || !$this->eiid){
      $emptyObject = new stdClass();
      $emptyObject->data = '';
      return $emptyObject;
    }
    if ($location){
      $query = " SELECT ".$field_name . "_address AS address, ".
          $field_name . "_vlat AS vlat, ".
          $field_name . "_vlong AS vlong, ".
          $field_name . "_zoom AS zoom, ".
          $field_name . "_country AS country, ".
          $field_name . "_region AS region, ".
          $field_name . "_city AS city, ".
          $field_name . "_zipcode AS zipcode".
          " FROM #__os_cck_content_entity_" . $this->fk_eid . 
          " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObject();
    }else{
      $query = " SELECT " . $this->_db->quoteName($field_name) . 
        " AS data FROM #__os_cck_content_entity_" . $this->fk_eid . " WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $result = $this->_db->loadObject();
    }

    return $result;
  }

  function delete($oid = null){
      $k = $this->_tbl_key;
      if ($oid) {
        $this->$k = $oid;
      }
      $query = " DELETE FROM  #__os_cck_categories_connect WHERE fk_eiid='" . $this->eiid . "' ";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();
      foreach ($this->getFields() as $field) {
        if ($field->field_type == 'filefield' || $field->field_type == 'imagefield'
            || $field->field_type == 'videofield' || $field->field_type == 'audiofield') {
          $file = new os_cckFile($this->_db);
          $value = $this->getFieldValue($field);
          if(isset($value['video']) || isset($value['track'])){
            foreach ($value['video'] as $video) {
              if(file_exists(JPATH_SITE . $video->src))
                unlink(JPATH_SITE . $video->src);
            }
            foreach ($value['track'] as $track) {
              if(file_exists(JPATH_SITE . $track->src))
                unlink(JPATH_SITE . $track->src); 
            }
          }
          if(isset($value['audio'])){
            foreach ($value['audio'] as $audio) {
              if(file_exists(JPATH_SITE . $audio->src))
                unlink(JPATH_SITE . $audio->src); 
            }
          }
          if($value[0]->fid != 0 ){
            $file->load($value[0]->fid);
            $file->delete();
          }
        }
      }

      // print_r($this->eiid);
      // exit;

      $query = "DELETE FROM #__os_cck_content_entity_{$this->fk_eid} WHERE fk_eiid='{$this->eiid}'";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();

      $query = "DELETE FROM #__os_cck_entity_instance WHERE eiid={$this->eiid}";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();

      $query = "DELETE FROM #__os_cck_video_source WHERE  fk_eiid={$this->eiid}";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();

      $query = "DELETE FROM #__os_cck_track_source WHERE  fk_eiid={$this->eiid}";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();

      $query = "DELETE FROM #__os_cck_audio_source WHERE  fk_eiid={$this->eiid}";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();
      
      //bch
      $query = "DELETE FROM #__os_cck_child_parent_connect WHERE fid_child = {$this->eiid}";
      $this->_db->setQuery($query);
      $this->_db->query();
      echo $this->_db->getErrorMsg();
      //bch

      return true;
  }

  function _is_filed_exist($field_name)
  {
      foreach ($this->getProperties() as $name => $value) {
          if ($name == $field_name) {
              return true;
          }
      }
      return false;
  }

  function _getFieldType($fieldName)
  {

      $query = 'SELECT f.field_type FROM #__os_cck_content_entity_field_instance as fi,  #__os_cck_content_entity_field as f ' .
          ' where fi.field_name = f.field_name and fi.entity_name = "' . $this->fk_eid . '" ' .
          ' and f.fieldName = "' . $fieldName . '"';
      $this->_db->setQuery($query);
      $tmp = (version_compare(JVERSION, "3.0.0", "lt")) ? $this->_db->loadResultArray() : $this->_db->loadColumn();
      if (count($tmp) == 0) {
          JError::raiseWarning(0, 'Bad set field type in _build_table_filed_type - field type unknow.');
          return false;
      }

      return $tmp->field_type;
  }

  function getFieldDetails($fieldName){
      //check exist fields with this name
      if (!$this->_is_filed_exist($fieldName)) {
          JError::raiseWarning(0, 'The fields' . $fieldName . ' not exist for this entity.');
          return false;
      }

      $query = "select * FROM #__os_cck_content_entity_field_instance " .
          " where  fieldName = '" . $fieldName . "' and entity_name = '" . $this->entity_name . "' ";
      $this->_db->setQuery($query);
      $os_cck_content_entity_field_instance = (version_compare(JVERSION, "3.0.0", "lt")) ? $this->_db->loadResultArray() : $this->_db->loadColumn();
      if (count($os_cck_content_entity_field_instance) == 0) {
          JError::raiseWarning(0, 'The fields' . $fieldName . ' not defined for this entity .');
          return false;
      }

      $query = 'SELECT f.* FROM #__os_cck_content_entity_field_instance as fi,  #__os_cck_content_entity_field as f ' .
          ' where fi.field_name = f.field_name and fi.entity_name = "' . $this->fk_eid . '" ' .
          ' and f.fieldName = "' . $fieldName . '"';
      $this->_db->setQuery($query);
      $os_cck_content_entity_field = (version_compare(JVERSION, "3.0.0", "lt")) ? $this->_db->loadResultArray() : $this->_db->loadColumn();
      if (count($os_cck_content_entity_field) == 0) {
          JError::raiseWarning(0, 'The fields' . $fieldName . ' not defined for this entity .');
          return false;
      }

      $os_cck_content_entity_field["global_settings"] = unserialize($os_cck_content_entity_field["global_settings"]);
      $os_cck_content_entity_field["db_columns"] = unserialize($os_cck_content_entity_field["db_columns"]);
      $os_cck_content_entity_field_instance["widget_settings"] =
          unserialize($os_cck_content_entity_field_instance["widget_settings"]);
      $os_cck_content_entity_field_instance["display_settings"] =
          unserialize($os_cck_content_entity_field_instance["display_settings"]);

      $tmp['os_cck_content_entity_field_instance'] = $os_cck_content_entity_field_instance;
      $tmp['os_cck_content_entity_field'] = $os_cck_content_entity_field;

      return $tmp;
  }

  function getAllLends($exclusion=""){

  	$this->_db->setQuery("SELECT id FROM #__os_cck_rent \n".
  						"WHERE fk_eiid='$this->eiid' " . $exclusion . " ORDER BY id");
  	if(version_compare(JVERSION, '3.0', 'lt')) {
  	  $tmp = $this->_db->loadResultArray();
  	} else {
  	  $tmp = $this->_db->loadColumn();
  	}

  	$retVal = array();
  	for($i = 0, $j = count($tmp); $i < $j; $i++ ){
  		$help = new mosCCK_rent($this->_db);
  		$help->load(intval($tmp[$i]));
  		$retVal[$i] = $help;
  	}
  	return $retVal;
  }

  function saveTracks($eiid, $src, $uploadTrackKind, $uploadTrackScrlang, $uploadTrackLabel) {    
    $location = '/components/com_os_cck/files/track/'.$src;
    if ($src != "" && !strstr($src, "http")) {
      $query = "INSERT INTO #__os_cck_track_source (fk_eiid,src,kind,scrlang,label)".
                "\n VALUE ($eiid,
                          '" . $location . "',
                          '" . $uploadTrackKind . "',
                          '" . $uploadTrackScrlang . "',
                          '" . $uploadTrackLabel . "')";
    }else{
      $query ="INSERT INTO #__os_cck_track_source (fk_eiid,src,kind,scrlang,label)".
              "\n VALUE ($eiid,
                        '" . $src."',
                        '" . $uploadTrackKind . "',
                        '" . $uploadTrackScrlang . "',
                        '" . $uploadTrackLabel . "')";
    }
    $this->_db->setQuery($query);
    $this->_db->query();
  }


  function saveAudios($src, $eiid, $type) {


    $location = 'components/com_os_cck/files/audio/'.$src;

    if ($src != "" && strstr($src, "http")) {

      $query = "INSERT INTO #__os_cck_audio_source(fk_eiid,src,type) 
                VALUE($eiid,
                  " . $this->_db->quote($src) . ",
                  '" . $type . "')";
    }else{

      $db = JFactory::getDBO();
      $query ="INSERT INTO #__os_cck_audio_source(fk_eiid,src,type) 
                     VALUE($eiid,
                        " . $this->_db->quote($location) . ",
                        '" . $type . "')";
    }

    $this->_db->setQuery($query);
    $this->_db->query();
  }



  function saveVideos($src, $eiid, $type) {
  $location = '/components/com_os_cck/files/video/'.$src;
  if ($src != "" && strstr($src, "http")) {
    $query = "INSERT INTO #__os_cck_video_source( fk_eiid, src, type)".
                                                "\n VALUE($eiid,'" . $this->_db->quote($src) . "', '" . $type . "')";
  }else{
    $query = "INSERT INTO #__os_cck_video_source( fk_eiid,src,type)".
              "\n VALUE($eiid,
                      '".$location."',
                      '".$type."')";
  }
  $this->_db->setQuery($query);
  $this->_db->query();
  }



  function saveYouTubeCode($youtube_code, $eiid) {
    $this->_db->setQuery("SELECT id FROM #__os_cck_video_source 
                          WHERE youtube != '' 
                          AND fk_eiid = $eiid");
    $this->_db->query();
    $youtubeId = $this->_db->LoadResult();
  if ($youtube_code != '' && !empty($youtubeId)) {
    $query = "UPDATE #__os_cck_video_source".
              "\n SET youtube = '" . $youtube_code . "'".
              "\n WHERE id = $youtubeId";
  } else {
    $query = "INSERT INTO #__os_cck_video_source (fk_eiid,youtube)". 
              "\n VALUE($eiid,'" . $youtube_code . "')";
  }
  $this->_db->setQuery($query);
  $this->_db->query();
  }


  function checkMimeType($ext) {
    $this->_db->setQuery("SELECT mime_type FROM #__os_cck_mime_types WHERE mime_ext=".$this->_db->quote($ext));
    $type = $this->_db->loadResult();
    if(!$type)
      $type = 'unknown';
    return $type;
  }


  function filter($value) {
    $value = str_replace(array("/", "|", "\\", "?", ":", ";", "*", "#", "%", "$", "+", "=", ";", " "), "_", $value);
    return $value;
  }


  function guid() {
    if (function_exists('com_create_guid')) {
      return com_create_guid();
    } else {
      mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = chr(45); // "-"
      $uuid = //chr(123)// "{"
      substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
      //.chr(125);// "}"
      return $uuid;
    }
  }  


  function deleteTracks($eiid) {
    $this->_db->setQuery("SELECT id FROM #__os_cck_track_source where fk_eiid = $eiid;");
    $tdiles_id = $this->_db->loadColumn();
    $deleteTr_id = array();
    foreach($tdiles_id as $key => $value) {
      if (isset($_POST['track_option_del' . $value])) {
        array_push($deleteTr_id, JRequest::getVar('track_option_del' . $value, '', 'post'));
      }
    }
    if ($deleteTr_id) {
      $del_tid = implode(',', $deleteTr_id);
      $sql = "SELECT src FROM #__os_cck_track_source WHERE id IN (" .$del_tid . ")";
      $this->_db->setQuery($sql);
      $tracks = $this->_db->loadColumn();
      if ($tracks) {
        foreach($tracks as $name) {
          if (substr($name, 0, 4) != "http") unlink(JPATH_SITE . $name);
        }
      }
      $sql = "DELETE FROM #__os_cck_track_source WHERE (id IN (" . $del_tid . ")) 
              AND (fk_eiid = $eiid)";
      $this->_db->setQuery($sql);
      $this->_db->query();
    }
  }

  function deleteAudios($eiid, $removeaudio = 0) {
    $this->_db->setQuery("SELECT id FROM #__os_cck_audio_source where fk_eiid = $eiid;");
    $adiles_id = $this->_db->loadColumn();
    $deleteAud_id = array();
    if ($removeaudio) {
      $deleteAud_id = $adiles_id;
    } else {
      foreach($adiles_id as $key => $value) {
        if (isset($_POST['audio_option_del' . $value])) {
          array_push($deleteAud_id, JRequest::getVar('audio_option_del' . $value, '', 'post'));
        }
      }
    }
    if (isset($deleteAud_id['0']) && $deleteAud_id['0']) {
      $del_id = "";
      $sql = "SELECT src FROM #__os_cck_audio_source WHERE id IN (";
      foreach($deleteAud_id as $aid_id) $del_id.= $aid_id . ",";
      $sql.= $del_id . "0)";
      $this->_db->setQuery($sql);
      $audios = $this->_db->loadColumn();
      if ($audios) {
        foreach($audios as $name) {
          if (substr($name, 0, 4) != "http") unlink(JPATH_SITE . $name);
        }
      }
      $sql = "DELETE FROM #__os_cck_audio_source WHERE (id IN (" . $del_id . "0)) and (fk_eiid=$eiid)";
      $this->_db->setQuery($sql);
      $this->_db->query();
    }
  }

  function deleteVideos($eiid) {
    $this->_db->setQuery("SELECT id FROM #__os_cck_video_source where fk_eiid = $eiid;");
    $vdiles_id = $this->_db->loadColumn();
    $deleteVid_id = array();
    foreach($vdiles_id as $key => $value) {
      if (isset($_POST['video_option_del' . $value])) {
        array_push($deleteVid_id, JRequest::getVar('video_option_del' . $value, '', 'post'));
      }
    }
    if ($deleteVid_id) {
      $del_id = implode(',', $deleteVid_id);
      $sql = "SELECT src FROM #__os_cck_video_source WHERE id IN (". $del_id . ")";
      $this->_db->setQuery($sql);
      $videos = $this->_db->loadColumn();
      if ($videos) {
        foreach($videos as $name) {
          if (substr($name, 0, 4) != "http" && file_exists(JPATH_SITE . $name)) 
            unlink(JPATH_SITE . $name);
        }
      }
      $sql = "DELETE FROM #__os_cck_video_source 
              WHERE (id IN (" . $del_id . ")) 
              AND (fk_eiid=$eiid)";
      $this->_db->setQuery($sql);
      $this->_db->query();
    }
    $this->_db->setQuery("SELECT id FROM #__os_cck_video_source where fk_eiid = $eiid AND youtube IS NOT NULL;");
    $youtubeid = $this->_db->loadResult();
    if (!empty($youtubeid)) {
      if (isset($_POST['youtube_option_del' . $youtubeid])) {
        $y_t_id = intval(mosGetParam($_REQUEST, 'youtube_option_del' . $youtubeid, ''));
        $sql = "DELETE FROM #__os_cck_video_source 
                WHERE id = $y_t_id 
                AND fk_eiid=$eiid";
        $this->_db->setQuery($sql);
        $this->_db->query();
      }
    }
  }

  function accept(){
    global $my, $os_cck_configuration;
    if ($this->eiid == null) {
      return "Method called on a non instant object";
    }


    //$my = JFactory::getUser();
    //$this->check($my->id);

    //create new lend dataset
    $rent = new mosCCK_rent($this->_db);
    $rent->fk_userid = $this->fk_userid;
    $query = "SELECT name, email FROM #__users WHERE id=$this->fk_userid";
    $this->_db->setQuery($query);
    $user = $this->_db->loadObjectList();
    if($user){
      $rent->user_name = $user[0]->name;
      $rent->user_email = $user[0]->email;
    }


    $child_instance = new os_cckEntityInstance($this->_db);
    $child_instance->load($this->child_id);
    unset($child_instance->child_id);
    $fields_list = $child_instance->getFields();

    $layout = new os_cckLayout($this->_db);
    $layout->load($child_instance->fk_lid);
    $fields_from_params = unserialize($layout->params);

    foreach ($fields_list as $value) {
      if($value->field_type == 'datetime_popup'){
          if($fields_from_params['fields'][$value->db_field_name."_field_type"] == "rent_from"){
              $rent_from = $child_instance->getFieldValue($value);
          }

          if($fields_from_params['fields'][$value->db_field_name."_field_type"] == "rent_to"){
              $rent_to = $child_instance->getFieldValue($value);
          }
      }
    }

    $rent->rent_from = $rent_from[0]->data; // date("Y-m-d H:i:s");
    $rent->rent_until = $rent_to[0]->data;
    //lend check start
    $rent->fk_eiid = $this->eiid;
    $query = "SELECT * FROM #__os_cck_rent WHERE fk_eiid = " . $this->eiid .
        " AND rent_return IS NULL ";
    $this->_db->setQuery($query);
    $lendTerm = $this->_db->loadObjectList();

    $lend_from = $rent->rent_from;
    $lend_until = $rent->rent_until;

    if (isset($lendTerm[0])) {
      for ($e = 0, $m = count($lendTerm); $e < $m; $e++) {

        //rent check
        if($os_cck_configuration->get('by_time')){    //by day

          if(($lend_from >= $lendTerm[$e]->rent_from && $lend_from < $lendTerm[$e]->rent_until)
           || 
           ($lend_from <= $lendTerm[$e]->rent_from && $lend_until >= $lendTerm[$e]->rent_until)
           || 
           ($lend_until > $lendTerm[$e]->rent_from && $lend_until <= $lendTerm[$e]->rent_until)
          ){
          echo "<script> alert('Sorry out from " . $lendTerm[$e]->rent_from .
              " until " . $lendTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
          exit ();}

        }elseif($os_cck_configuration->get('rent_type')){


          $lend_from = date('Y-m-d',strtotime($lend_from));
          $lend_until = date('Y-m-d',strtotime($lend_until));
          $lendTerm[$e]->rent_from = date('Y-m-d',strtotime($lendTerm[$e]->rent_from));
          $lendTerm[$e]->rent_until = date('Y-m-d',strtotime($lendTerm[$e]->rent_until));

          if(($lend_from >= $lendTerm[$e]->rent_from && $lend_from <= $lendTerm[$e]->rent_until)
           || 
           ($lend_from <= $lendTerm[$e]->rent_from && $lend_until >= $lendTerm[$e]->rent_until)
           || 
           ($lend_until >= $lendTerm[$e]->rent_from && $lend_until <= $lendTerm[$e]->rent_until)
          ){
          echo "<script> alert('Sorry out from " . $lendTerm[$e]->rent_from .
              " until " . $lendTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
          exit ();}


        }else{  //by night

          $lend_from = date('Y-m-d',strtotime($lend_from));
          $lend_until = date('Y-m-d',strtotime($lend_until));
          $lendTerm[$e]->rent_from = date('Y-m-d',strtotime($lendTerm[$e]->rent_from));
          $lendTerm[$e]->rent_until = date('Y-m-d',strtotime($lendTerm[$e]->rent_until));

          if(($lend_from > $lendTerm[$e]->rent_from && $lend_from < $lendTerm[$e]->rent_until)
           || 
           ($lend_from < $lendTerm[$e]->rent_from && $lend_until > $lendTerm[$e]->rent_until)
           || 
           ($lend_until > $lendTerm[$e]->rent_from && $lend_until < $lendTerm[$e]->rent_until)
          ){
          echo "<script> alert('Sorry out from " . $lendTerm[$e]->rent_from .
              " until " . $lendTerm[$e]->rent_until . "'); window.history.go(-1); </script>\n";
          exit ();}
          
        }

      }
    }
    //if end(end lend check)
    if (!$rent->store()) {
      return $rent->getError();
    }
    if (!$child_instance->delete()) {
      return $child_instance->getError();
    }
    return null;
  }

  function decline(){
    if ($this->eiid == null) {
      return "Method called on a non instant object";
    }
    if (!$this->delete()) {
      return $rent->getError();
    }
    return null;
  }

}


