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

////////////////////////GLOBAL VARIABLES DECLARATION///////////////////////
// $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
//$mosConfig_live_site = $GLOBALS['mosConfig_live_site'] = JURI::root(); 
global $option,
        $Itemid,
        $doc,
        $user,
        $db,
        $os_cck_configuration,
        $limit,
        $limitstart,
        $app,
        $task,
        $session,
        $jConf;

$app = JFactory::getApplication();
$input = $app->input;
$user = JFactory::getUser();
$doc = JFactory::getDocument();
$db = JFactory::getDBO();
$session = JFactory::getSession();
$os_cck_configuration = JComponentHelper::getParams('com_os_cck');
$jConf = JFactory::getConfig();


$Itemid = $input->get('Itemid', 0, 'INT');;
$option = $input->get('option', '', 'STRING');
$lid = $input->get('lid', 0,'INT');
$fid = $input->get('fid', 0,'INT');
$task = $input->get('task', '', 'STRING');
$view = $input->get('view', '', 'STRING');
$type = $input->get('type', '', 'STRING');
$catid = $input->get('catid', 0, 'INT');
$eiids = $input->get('eiid', array(0), 'ARRAY');
$cid = $input->get('cid', array(0), 'ARRAY');
$cb = $input->get('cb', array(0), 'ARRAY');

///////////END GLOBAL VARIABLES DECLARATIONS///////////////////////
$doc->addScript(JUri::root() . "components/com_os_cck/assets/js/jQuerCCK-2.1.4.js");
$doc->addScript(JUri::root() . "components/com_os_cck/assets/js/functions.js");
$doc->addStylesheet(JUri::root() . 'components/com_os_cck/assets/css/admin_style.css');

require_once(JPATH_SITE . "/administrator/components/com_os_cck/toolbar.os_cck.php");
require_once(JPATH_SITE . "/administrator/components/com_os_cck/admin.os_cck.class.impexp.php");
require_once(JPATH_SITE . "/components/com_os_cck/functions.php");
require_once(JPATH_SITE . "/administrator/components/com_os_cck/os_cck.html.php");
jimport('joomla.html.pagination');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');
if(!function_exists("auto_include")){
    function auto_include($path_to_files)
    {
        $component_path = JPath::clean(JPATH_SITE . $path_to_files);
        if (is_dir($component_path) && ($component_layouts = JFolder::files($component_path, '^[^-]*\.php$', false, true))) {
            foreach ($component_layouts as $i => $file) {
                require_once($file);
            }
        }
    }
}

auto_include('/administrator/components/com_os_cck/adminphp');
auto_include('/administrator/components/com_os_cck/adminhtml');
auto_include('/components/com_os_cck/classes');

//print_r($task);exit;
switch ($task) {
/* FIELD ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
    case "show_in_ins":
        ob_start();
            AdminField::show_in_ins(1, $option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "not_show_in_ins":
        ob_start();
            AdminField::show_in_ins(0, $option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "saveLayoutField":
        ob_start();
            AdminField::saveLayoutField($fid);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;
    case "saveLayoutFieldName":
        ob_start();
            AdminField::saveLayoutFieldName($option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "publish_fields":
        ob_start();
            AdminField::publishFields(1, $option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "unpublish_fields":
        ob_start();
            AdminField::publishFields(0, $option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "deleteLayoutField":
        ob_start();
            AdminField::deleteField($option);
            $html = ob_get_contents();
        ob_end_clean();
        
        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "showFieldList":
        ob_start();
            AdminField::showLayoutFields($option);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "addLayoutField":
    case "editLayoutField":
 
        ob_start();

            AdminField::addLayoutField($option,$fid);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;

    case "addOptionForLayout":
        $lid = $input->get("lid",0,"INT");
        $key = $input->get("key",'',"STRING");
        $layout_type = $input->get("layout_type",'',"STRING");
        $layout_type.='_layout';
        $layout = new os_cckLayout($db);
        $layout->load($lid);
        $layout_params = unserialize($layout->params);
        ob_start();
            require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', $layout_type, 'layoutOptions');
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;
/* END FIELD ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

/* LAYOUT ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
    case "addFieldOptions":
        $lid = $input->get("lid",0,"INT");
        $layout_type = $input->get("layout_type",'',"STRING");
        if($layout_type == 'add_instance' || $layout_type == 'request_instance'
            || $layout_type == 'rent_request_instance' || $layout_type == 'buy_request_instance'
            || $layout_type == 'review_instance'){
            $viewType = 'add';
        }else{
            $viewType = 'show';
        }
        $fName = $input->get("fieldName",'',"STRING");
        if($fName && $layout_type && $lid){
            $query = "SELECT *".
                    "\n FROM #__os_cck_entity_field WHERE db_field_name=".$db->Quote($fName);
            $db->setQuery($query);
            $field = $db->loadObjectList();
            $field = $field[0];
            $layout = new os_cckLayout($db);
            $layout->load($lid);
            $layout_params = unserialize($layout->params);
            $fields_from_params = (isset($layout_params['fields']))?$layout_params['fields']:array();

            ob_start();
                show_edite_add_form_field_layout($field, $viewType, $fields_from_params,$layout_type);
                $html = ob_get_contents();
            ob_end_clean();

            $response = array('html' => $html);
            echo json_encode($response);
        }else{
            $response = array('html' => "Error while creating option.Please refresh page.");
            echo json_encode($response);
        }
        break;

    case "updateLayoutFieldList":
        ob_start();
            AdminLayout::updateLayoutFieldList($option, $lid[0]);
            $html = ob_get_contents();
        ob_end_clean();

        $response = array('html' => $html);
        echo json_encode($response);
        break;        
/* END LAYOUT ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

    case "is_readed":
        setReaded($eiids);
    break;

    case "is_readed_orders":
        setReadedOrders();
    break;

    case "settings":
        $app->redirect("index.php?option=com_config&view=component&component=com_os_cck");
        break;

    case "new_instance":
        AdminInstance::editInstance($option, 0);
        break;
    
    case "getContent":
        require_once(JPATH_SITE . "/administrator/components/com_os_cck/helpers/uploader.php");
        break;
    
    case "orders":
        AdminOrders::showOrders();
        break;

    case "deleteOrder":
        AdminOrders::deleteOrder($cb, $option);
        break;

    case "updateOrderStatus":
        AdminOrders::updateOrderStatus();
        break; 

    case "edit_instance" :
    
        AdminInstance::editInstance($option, array_pop($eiids));
        break;

    case "save_instance" :
    case "apply_instance" :
        AdminInstance::saveInstance($option);
        break;

    case "remove_instances" :
        AdminInstance::removeInstances($eiids, $option);
        break;

    case "publish_instances" :
        AdminInstance::publishInstances($eiids, 1, $option);
        break;

    case "unpublish_instances" :
        AdminInstance::publishInstances($eiids, 0, $option);
        break;

    case "approve_instances" :
        AdminInstance::approveInstances($eiids, 1, $option);
        break;

    case "unapprove_instances" :
        AdminInstance::approveInstances($eiids, 0, $option);
        break;

    case "cancel_instance" :
        AdminInstance::cancelInstance($option);
        break;
/* END INSTANCE ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
/* CATEGORY     ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
    case "show_categories" :
        AdminCategory::showCategories();
        break;

    case "edit_category" :
        AdminCategory::editCategory($option, $cid[0]);
        break;

    case "add_category":
        AdminCategory::editCategory($option, 0);
        break;

    case "cancel_category":
        AdminCategory::cancelCategory();
        break;

    case "save_category":
        AdminCategory::saveCategory();
        break;

    case "remove_categories":
        AdminCategory::removeCategories($option, $cid);
        break;

    case "publish_categories":
        AdminCategory::publishCategories("com_os_cck", $id, $cid, 1);
        break;

    case "unpublish_categories":
        AdminCategory::publishCategories("com_os_cck", $id, $cid, 0);
        break;

    case "orderup_category":
        AdminCategory::orderCategory($cid[0], -1);
        break;

    case "orderdown_category":
        AdminCategory::orderCategory($cid[0], 1);
        break;

    case "accesspublic_category":
        AdminCategory::accessCategory($cid[0], 0);
        break;

    case "accessregistered_category":
        AdminCategory::accessCategory($cid[0], 1);
        break;

    case "accessspecial_category":
        AdminCategory::accessCategory($cid[0], 2);
        break;

/* ENDCATEGORY ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

/* REVIEW ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

    case "manage_review" :
        AdminReview:: showReviews($option, "");
        break;

    case "edit_review" :
        AdminReview:: editReview($option, array_pop($eiids));
        break;

    case "delete_review" :
        AdminReview::removeReviews($eiids, $option);
        break;

    case "save_review" :
    case "cancel_review" :
        AdminReview::saveReview($option);
        break;

    case "publish_reviews" :
        AdminReview::publishReviews($eiids, 1, $option);
        break;

    case "unpublish_reviews" :
        AdminReview::publishReviews($eiids, 0, $option);
        break;

    case "approve_reviews" :
        AdminReview::approveReviews($eiids, 1, $option);
        break;

    case "unapprove_reviews" :
        AdminReview::approveReviews($eiids, 0, $option);
        break;

/* END REVIEW ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

/* ENTITY ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

    case "publish_entities":
        AdminEntity::publishEntities(1, $option);
        break;
    case "unpublish_entities":
        AdminEntity::publishEntities(0, $option);
        break;

    case "manage_entities":
        AdminEntity::showEntities($option);
        break;

    case "add_entity":
        AdminEntity::editEntity($option, '');
        break;

    case "edit_entity":
        $post = JRequest::get('post', JREQUEST_ALLOWHTML);
        if (array_key_exists('name', $post)) {
            $entity_type_name = $post['name'];
            AdminEntity::editEntity($option, $entity_type_name[0]);
        } else AdminEntity::editEntity($option, "");
        break;

    case "save_entity":
        AdminEntity::saveEntity($option);
        break;

    case "delete_entity":
        AdminEntity::deleteEntity($option);
        break;

    case "cancel_edit_entity":
        AdminEntity::cancelEditEntity();
        break;

/* END ENTITY ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

/* SETTINGS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
    case "config" :
        AdminSettings::configure($option);
        break;

    case "config_save" :
        AdminSettings::configure_save_frontend($option);
        AdminSettings::configure_save_backend($option);
        AdminSettings::configure($option);
        break;

/*END SETTINGS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

/* LAYOUTS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

    case "manage_layout" :
        AdminLayout::showLayouts($option);
        break;

    case "showLayoutsModalPlg":
        AdminLayout::showLayoutsModalPlg($option);
        break;

    case "select_data_for_editor_button":
        AdminLayout::showModalCckButton($option);
        break;

    case "showInstanceModalPlg";
        AdminInstance::showInstanceModalPlg($option);
        break;

    case "showCategoryModalPlg":
        AdminCategory::showCategoryModalPlg($option);
        break;

    case "new_layout" :
        AdminLayout::newLayout($option);
        break;

    case "add_new_layout" :
        AdminLayout::addNewLayout($option);
        break;

    case "edit_layout" :
        AdminLayout::editLayout($option, $lid[0]);
        break;

    case "copy_layout" :
        AdminLayout::copyLayout($option, $lid);
        break;

    case "save_layout" :
    case "apply_layout" :
    case "cancel_layout" :
        AdminLayout::saveLayout($option);
        break;

    case "publish_layouts" :
        AdminLayout::publishLayouts($lid, 1, $option);
        break;

    case "unpublish_layouts" :
        AdminLayout::publishLayouts($lid, 0, $option);
        break;

    case "approve_layouts" :
        AdminLayout::approveLayouts($lid, 1, $option);
        break;

    case "unapprove_layouts" :
        AdminLayout::approveLayouts($lid, 0, $option);
        break;

    case "remove_layouts" :
        AdminLayout::removeLayouts($lid, $option);
        break;
/* END LAYOUTS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/
    case "edit_rent" :
        AdminRent::edit_rent($option, $eiids);
        break;

    case "rent" :
        AdminRent::edit_rent($option, $eiids);
        break;

    case "add_rent" :
        AdminRent::edit_rent($option, $eiids);
        break;

    case "save_rent" :
        AdminRent::saveRent($option, array_pop($eiids) ,$task);
        break;

    case "save_edit_rent" :
        AdminRent::saveRent($option, array_pop($eiids) ,$task);
        break;

    case "rent_return" :
        $eiid = JRequest::getVar('return_id','');
        AdminRent::rent_return($option, $eiid);
        break;

    case "show_rent_request_instances" :
        AdminRent_request::showRentRequestInstances($option);
        break;

    case "edit_rent_request_instance" :
        AdminRent_request::editRentRequestInstance($option, array_pop($eiids));
        break;

    case "remove_rent_request_item" :
        AdminRent_request::remove_rent_request_item($eiids, $option);
        break;

    case "accept_rent_requests" :
        AdminRent_request::accept_rent_requests($option, $eiids);
        break;

    case "decline_rent_requests" :
        AdminRent_request::decline_rent_requests($option, $eiids);
        break;

    case "accept_buying_requests" :
        AdminRent_request::accept_buying_requests($option, $eiids);
    break;

    case "users_rent_history" :
        AdminRent_history::users_rent_history($option, $eiids);
        break;        

    case "show_buy_request_instances" :
        AdminBuy_request::showBuyRequestInstances($option);
        break;

    case "edit_buy_request_instance" :
        AdminBuy_request::editBuyRequestInstance($option, array_pop($eiids));
        break;

    case "remove_buy_request_item" :
        AdminBuy_request::remove_buy_request_item($eiids, $option);
        break;

    case "change_status":
        AdminBuy_request::change_status($option);
        break;

    case "import" :
        AdminImportExport::import();
        break;

    case "about" :
        cckAbout();
        break;

    case "delete_review" :
        $ids = explode(',', $eiids[0]);
        delete_review($option, $ids[1]);
        editEntityInstance($option, $ids[0], $entity_type);
        break;

    case "edit_review" :
        $ids = explode(',', $eiids[0]);
        edit_review($option, $ids[1], $ids[0]);
        break;

    case "update_review" :
        $title = mosGetParam($_POST, 'title');
        $comment = mosGetParam($_POST, 'comment');
        $rating = mosGetParam($_POST, 'rating');
        $item_id = mosGetParam($_POST, 'house_id');
        $review_id = mosGetParam($_POST, 'review_id');

        update_review($title, $comment, $rating, $review_id);
        editEntityInstance($option, $item_id, $entity_type);
        break;

    case "cancel_review_edit" :
        $item_id = mosGetParam($_POST, 'house_id');
        editEntityInstance($option, $item_id, $entity_type);
        break;

//******  begin add for button print in Manager houses  ***********

    case "show_requests":
        AdminRequest::showRequests($option);
        break;

    case "show_request_item":
        AdminRequest::showRequestItem($option, array_pop($eiids));
        break;

    case "remove_request_item" :
        AdminRequest::removeRequestItem($eiids, $option);
        break;

    case "show_instance_modal" :
        AdminInstance::showInstancesModal($option);
        break;

    case "show_categories_modal" :
        AdminCategory::showCategoriesModal($option);
        break;

    case "manage_layout_modal" :
        AdminLayout::showLayoutsModal($option);
        break;

    case "checkFile":
        checkFile($option);
        break;

    case "show_instance":
    default :
        AdminInstance::showInstances($option);
        break;


//******  end add for Entity Manager  *************
}

function setReaded($eiids){
    global $db, $app;
    $eiids = implode(",",$eiids);
    $query = "UPDATE #__os_cck_entity_instance SET notreaded=0 WHERE eiid IN($eiids)";
    $db->setQuery($query);
    $db->query();

    $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']));
}

function setReadedOrders(){
    global $db, $app;
    $orderIds = $_POST['cb'];
    $orderIds = implode(",",$orderIds);
    $query = "UPDATE #__os_cck_orders  SET notreaded=0 WHERE id IN($orderIds)";
    $db->setQuery($query);
    $db->query();

    $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']));
}

function checkFile() {
    $path = $_GET["path"];
    $filename = basename($_GET["file"]);
    $file = $path . $filename;
    if (file_exists($file)) {
        echo "The file with such name already is!";
    } else {
        echo "";
    }
}

function cckAbout(){
    //check update
    $avaibleUpdate = avaibleUpdateCCK();
    HTML_os_cck :: about($avaibleUpdate);
}

function prepere_field_for_show($field, $value,$row=0)
{
    global $moduleId ;
    $field->options['strlen'] = 100;
    $field->options['width'] = 100;
    $field->options['height'] = 100;
    if ($field->published != "1") return "";
    $ftype = $field->field_type;
    $global_settings = unserialize($field->global_settings);
    $db_columns = unserialize($field->db_columns);
    $sufix = '';
    if ($ftype == 'text_textfield') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        if ($value != '') $return = (strlen($value) > $field->options['strlen']) ? substr($value, 0, $field->options['strlen']) . "..." : $value;
        return $return;
    }elseif ($ftype == 'categoryfield') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        if ($value != '') $return = $value;
        return $return;
    }elseif ($ftype == 'decimal_textfield') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        if ($value != '') $return = $value;
        return $return;
    }elseif ($ftype == 'rating_field') {
        return '<img src="'.JURI::root().'/components/com_os_cck/images/rating-'.($value->data*2).'.png"
                alt="'.$value->data.'" border="0"/>&nbsp;';
    } else if ($ftype == 'datetime_popup') {
        $value = (isset($value->data) && $value->data != '0000-00-00 00:00:00') ? $value->data : '';
        $format=($global_settings['output_format']!="") ? ($global_settings['output_format']) : ($global_settings['input_format']);
        $value = date(str_replace('%','',$format), strtotime($value));
        return $value;
    } else if ($ftype == 'filefield') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        if ($value != '') $return = '<a href="' . JURI::root() . $value . '" > download </a>';
        return $return;
    } else if ($ftype == 'imagefield') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        $width_heigth = (isset($field->options['width'])) ? ' width="' . $field->options['width'] . 'px" ' : '';
        $width_heigth .= (isset($field->options['height'])) ? ' height="' . $field->options['height'] . 'px" ' : '';
        if ($value != '') {
            $image = show_image_cck($value, $field->options['width'], $field->options['height']);
            $return = '<img src="' . JURI::root() . $image . '"  /><br />';
        }
        return $return;
    } else if ($ftype == 'locationfield') {
        $layout_params = array();
        $layout_params['map_field_name'] = $field->field_name;
        $width = (isset($field->options['width'])) ? $field->options['width'] : '';
        $heigth = (isset($field->options['height'])) ? $field->options['height'] : '';
        $field->settings = unserialize($field->global_settings);
        if($row){
            $fieldName=str_replace(' ','_',($row->title.'_'.$field->field_name));
        }else{
            $fieldName=str_replace(' ','_',$field->field_name);
        }
        ob_start();
        showLocationMap($layout_params,false, $fieldName
            , $value->{$field->field_name . "_vlat"}
            , $value->{$field->field_name . "_vlong"}
            , $value->{$field->field_name . "_zoom"}
            , $value->{$field->field_name . "_adress"}
            , $field->settings['maptype']
            , $width
            , $heigth

        );
    
        $return = ob_get_contents();
        ob_end_clean();

        return $return;
    } else if ($ftype == 'galleryfield') {
        
        $width_heigth = (isset($field->options['width'])) ? 'width:' . $field->options['width'] . 'px;' : '';
        $width_heigth .= (isset($field->options['height'])) ? ' height:' . $field->options['height'] . 'px;' : '';
        $return = '';

        if(!empty($value) && $images = json_decode(($value->data))){
            $return .= "<div class='gallery_".$field->field_name."' id='gallery_".$field->field_name."'>";
            $images = json_decode(($value->data));

            foreach ($images as $image) {
                if (!isset($_REQUEST['view']) || $_REQUEST['view'] != 'category')
                    $return .= '<a href="' . JURI::root() . 'images/com_os_cck' . $field->fid . '/original/' . $image->file . '" data-lightbox="roadtrip_'.$field->field_name.$moduleId.'" title="' . $image->alt . '">';
                $return .= '<img style="' . $width_heigth . '" src="' . JURI::root() . 'images/com_os_cck' . $field->fid . '/thumbnail/' . $image->file . '"  />';
                if (!isset($_REQUEST['view']) || $_REQUEST['view'] != 'category') $return .= '</a>';
                if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'category') break;
            }
            return $return . "</div>";
        }
    } else if ($ftype == 'text_radio_buttons') {
        $value = (isset($value->data)) ? $value->data : '';
      $return = '';
      $arr = array();
      $allowed_values = urlencode($global_settings['allowed_values']);
      if (strpos($allowed_values, '%0D%0A') !== false) $allowed_values = explode('%0D%0A', $allowed_values);
      else if (strpos($allowed_values, '%0D') !== false) $allowed_values = explode('%0D', $allowed_values);
      else if (strpos($allowed_values, '%0A') !== false) $allowed_values = explode('%0A', $allowed_values);
      else return "Bad set 'allow value' for this field!";
      foreach ($allowed_values as $item) {
          $key_value = explode('%7C', $item);
          if ($key_value[0] == $value) $return = (isset($key_value[1]))?urldecode($key_value[1]):urldecode($key_value[0]);
      }
      return $return;
    } else if ($ftype == 'text_select_list') {
        $value = (isset($value->data)) ? $value->data : '';
          $return = '';
          $arr = array();
          $allowed_values = $global_settings['allowed_values'];
          $allowed_values = urlencode($allowed_values);
          if (strpos($allowed_values, '%0D%0A') !== false) $allowed_values = explode('%0D%0A', $allowed_values);
          else if (strpos($allowed_values, '%0D') !== false) $allowed_values = explode('%0D', $allowed_values);
          else if (strpos($allowed_values, '%0A') !== false) $allowed_values = explode('%0A', $allowed_values);
          else return "Bad set 'allow value' for this field!";
          foreach ($allowed_values as $key => $allow_value) {
            $allow_value = str_replace("+", ' ', $allow_value);
            $allowed_values[$key] = trim(urldecode($allow_value));
          }
          foreach ($allowed_values as $item) {
              //echo ":1111111111:".$item.":1111111111:".$value.":1111111111:";
              $key_value = explode('|', $item);
              if(!isset($key_value[0]) && isset($key_value[1])){
                $key_value[0] = str_replace(' ', '', $key_value[1]);
              }
              //print_r($key_value);
              if($key_value[0] !== 0){
                if ($key_value[0] == $value and count($key_value) > 1) $return = $key_value[1];
                else if ($key_value[0] == $value) $return = $key_value[0];
              }
          }
          return $return;
    } else if ($ftype == 'text_single_checkbox_onoff') {
        $value = (isset($value->data)) ? $value->data : '';
      $return = '';
      $arr = array();
      $allowed_values = str_replace(' ', '', $global_settings['allowed_values']);
      $allowed_values = urlencode($allowed_values);
      if (strpos($allowed_values, '%0D%0A') !== false) $allowed_values = explode('%0D%0A', $allowed_values);
      else if (strpos($allowed_values, '%0D') !== false) $allowed_values = explode('%0D', $allowed_values);
      else if (strpos($allowed_values, '%0A') !== false) $allowed_values = explode('%0A', $allowed_values);

      foreach ($allowed_values as $key => $item) {
        $key_value = explode('%7C', $item);
        if (isset($key_value[0]) && isset($key_value[1]) && $key_value[0] == $value) $return = urldecode($key_value[1]);
        else if (isset($key_value[0]) && !isset($key_value[1]) && $key_value[0] == $value) $return = urldecode($key_value[0]);
        else if (!isset($key_value[0]) && isset($key_value[1]) && $key_value[1] == $value) $return = urldecode($key_value[1]);
        else if (count($allowed_values) == 2 && empty($value) && $key == 0){
          if (isset($key_value[0]) && isset($key_value[1])) 
            $return = urldecode($key_value[1]);
          else
            $return = urldecode($key_value[0]);
        }
      }
      return $return;

    } else if ($ftype == 'text_textarea') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
//       if($value!=''){
//         $return = '<textarea class="text_area" type="text"  rows="'.$field->options['rows'].'" cols="'.$field->options['cols'].'" readonly="true" > '.$value.'</textarea>' ;
//       }
        if ($value != '') $return = (strlen($value) > $field->options['strlen']) ? substr($value, 0, $field->options['strlen']) . "..." : $value;

        return $return;

    } else if ($ftype == 'text_url') {
        $value = (isset($value->data)) ? $value->data : '';
        $return = '';
        if ($value != '' && $value != 'http://') {
            $return = '<a href="' . $value . '">' . $value . '</a>';
        }
        return $return;
    } else {
        return 'Canity test. Error - Bad type sellected!';
    }
}

function edit_review($option, $review_id, $item_id)
{
    global $db;
    $db->setQuery("SELECT * FROM #__os_cck_review WHERE id=" . $review_id . " ");
    $review = $db->loadObjectList();
    echo $db->getErrorMsg();
    HTML_os_cck :: edit_review($option, $item_id, $review);
}


function delete_review($option, $id)
{
    global $db;
    //delete review where id =.. ;
    $db->setQuery("DELETE FROM #__os_cck_review WHERE #__os_cck_review.id=" . $id . ";");
    $db->query();
    echo $db->getErrorMsg();
}

function getDeepLevel($catid, $cat_all,$deep = 0) {
  global $moduleId,$max_dip;
  $deep++;
  for ($i = 0; $i < count($cat_all); $i++) {
    if (($catid == $cat_all[$i]->parent_id)){
      if($max_dip < $deep-1){
        $max_dip = $deep-1;
      }
      getDeepLevel($cat_all[$i]->cid, $cat_all,$deep,$max_dip);
    }//end if
  }//end for
  return $max_dip;
}//end fn