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
//JPATH_SITE = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
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
$Itemid = $input->get('Itemid', 0, 'INT');
$option = $input->get('option', '', 'STRING');
$lid = $input->get('lid', 0,'INT');
$task = $input->get('task', '', 'STRING');
$view = $input->get('view', '', 'STRING');
$type = $input->get('type', '', 'STRING');
$catid = $input->get('catid', 0, 'INT');
$eiids = $input->get('eiid', array(0), 'ARRAY');
$jConf = JFactory::getConfig();


// paginations
$limit = $input->get('limit', $os_cck_configuration->get('items_on_page'), 'INT');;
$limitstart = $input->get('limitstart', 0, 'INT');

if($task == "" && $view != "" ) $task = $view ;
///////////END GLOBAL VARIABLES DECLARATIONS///////////////////////

require_once(JPATH_SITE."/components/com_os_cck/functions.php");
require_once(JPATH_SITE."/components/com_os_cck/captcha.php");
require_once(JPATH_SITE."/components/com_os_cck/os_cck.html.php");
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
auto_include('/components/com_os_cck/php');
auto_include('/components/com_os_cck/html');
auto_include('/components/com_os_cck/classes');

$doc->addStylesheet(JUri::root() . "components/com_os_cck/assets/css/front_end_style.css");
$doc->addScript(JUri::root() . "/components/com_os_cck/assets/js/jQuerCCK-2.1.4.js");
$doc->addScript(JUri::root() . "components/com_os_cck/assets/js/functions.js");
// $id = intval(mosGetParam($_REQUEST, 'id', 0));


if($option == "com_os_cck") {
  switch ($task) {
    case 'allcategories':
    case 'all_categories':
      Category::listCategories($option, $lid);
    break;

    case 'getISC':
      // $jinput = JFactory::getApplication()->input;

      // $title = $jinput->getCmd('title','');
      // $location = $jinput->getCmd('location','');
      // $event_start = $jinput->getCmd('event_start','');
      // $event_end = $jinput->getCmd('event_end','');
      // $description = $jinput->getCmd('description','');
      // $calendarConstruct = new CalendarUrlConstruct($title, $event_start, $event_end, $description, $location);
      // $calendarConstruct->get("File","ICS");

    break;

    case 'category':
      Category::showCategory($option, $catid,$lid);
    break;

    case 'paypal':
      os_cck_site_controller::paypal();
    break;

    case 'category_with_map':
      Category::showCategory($option, $catid,$lid);
    break;

    case 'instance':
      Instance::showItem($option, array_pop($eiids), $catid, $lid);
    break;

    case 'instance_manager':
      Instance::showInstanceManager('add_instance');
    break;

    case 'edit_instance':
      Instance::editInstance($option, array_pop($eiids));
    break;

    case "publish_instances" :
      Instance::publishInstances($eiids, 1, $option);
    break;

    case "unpublish_instances" :
      Instance::publishInstances($eiids, 0, $option);
    break;

    case "getContent":
      require_once(JPATH_SITE . "/components/com_os_cck/uploader.php");
      break;

    case "approve_instances" :
      Instance::approveInstances($eiids, 1, $option);
    break;

    case "unapprove_instances" :
      Instance::approveInstances($eiids, 0, $option);
    break;

    case "show_rent_request_instances":
      Instance::showInstanceManager('rent_request_instance');
    break;

    case "edit_rent" :
      // $eiids = $eiids[0];
      InstanceManagerRent::edit_rent($option, array_pop($eiids));
    break;

    case "save_rent" :
      $eiids = $eiids[0];
      InstanceManagerRent::saveRent($option, $eiids ,$task);
    break;

    case "rent_return" :
      $return_ids = protectInjectionWithoutQuote('return_id','','ARRAY');
      InstanceManagerRent::rent_return($option, $return_ids);
    break;

    case "show_user_rent_history" :
      InstanceManagerRent::users_rent_history($option);
    break;

    case "edit_rent_request_instance":
      Instance::editRentRequestInstance($option, array_pop($eiids));
    break;

    case "decline_rent_requests" :
      Instance::decline_rent_requests($option, $eiids);
    break;

    case "accept_rent_requests" :
      Instance::accept_rent_requests($option, $eiids);
    break;

    case "show_buy_request_instances":
      Instance::showInstanceManager('buy_request_instance');
    break;

    case "edit_buy_request_instance":
      Instance::editBuyRequestInstance($option, array_pop($eiids));
    break;

    case "remove_buy_request_instance" :
      Instance::remove_buy_request_instance($eiids, $option);
    break;

    case 'save_instance':
      os_cck_site_controller::saveInstance($option);
      break;

    case 'send_buy_request':
      os_cck_site_controller::send_buy_request($option);
      break;

    case 'all_instance':

      $event_date = (JRequest::getVar('event_date') && JRequest::getVar('event_date') != '')?JRequest::getVar('event_date'):false;
      $event_date_field = (JRequest::getVar('event_date_field') && JRequest::getVar('event_date_field') != '')?JRequest::getVar('event_date_field'):false;

      $menu = new JTableMenu($db);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
      $lid = $params->get('all_instance_layout');

      if($event_date && $event_date_field){
        $lid = intval(JRequest::getVar('lid'));
      }

      Instance::show_all_instance($option,$lid);
      break;

    case 'calendar':
      $menu = new JTableMenu($db);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);

      Instance::show_calendar($option,$params->get('calendar_layout'));
      break;

    case 'add_instance':
      global $params;
      $menu = new JTableMenu($db);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
      Instance::show_request_layout($option, $params->get('request_layout'),0);
      break;

    case 'show_request_layout':


      $lid = intval(JRequest::getVar('lid'));

      $eiids = protectInjectionWithoutQuote('eiid','','string');
      Instance::show_request_layout($option, $lid,$eiids);
      break;

    case 'send_rent_request':
      os_cck_site_controller::send_rent_request($option);
      break;
    case 'send_request':
      os_cck_site_controller::send_request($option);
      break;

    case 'send_review_request':
      os_cck_site_controller::send_review_request($option);
      break;

    case 'view':
      Instance::showItem($option, $id, $catid);
      break;
      ///
    case 'show_search':
      Category::showSearch($option, $catid, $lid);
      break;
      ///
    case 'secret_image':
      os_cck_site_controller::secretImageCCK($type);
      break;

    case 'ajax_rent_calcualete':
      $rent_from = protectInjectionWithoutQuote("rent_from",'');
      $rent_until = protectInjectionWithoutQuote("rent_until",'');
      $eiids_ajax_rent = intval(JRequest::getVar("rent_eiid",''));
      $ceid_ajax_rent = intval(JRequest::getVar("rent_ceid",''));
      os_cck_site_controller::ajax_rent_calcualete($eiids_ajax_rent,$ceid_ajax_rent,$rent_from,$rent_until);
      break;

    case 'search':

      Instance::search($option, $input->get('categories', array(0), "ARRAY"));
      break;

    case 'checkCaptcha':
      os_cck_site_controller::checkCaptcha();
      break;

    case 'save_buy_request':
      os_cck_site_controller::saveBuyingRequest($option, $eiids);
      break;

    case 'orderitem':
      os_cck_site_controller::orderitem($option);
      break;

    case 'show_rss_categories':
      os_cck_site_controller::listRssCategories();
      break;

    case 'rent_manage':
      rentManage();
      break;

    case 'reload_captcha':
      $jinput = JFactory::getApplication()->input;
      $captchafName = $jinput->getCmd('captchafName');

      $layout_type = $input->get('captcha_type','','STRING');
      $layout = new os_cckLayout($db);
      $layout->load($lid);
      $layout_params = unserialize($layout->params);

      $layout_html = '';
      $field = new stdClass();
      $field->db_field_name = $captchafName; 

      ob_start();
        require getSiteAddFiledViewPath('com_os_cck', 'captcha_field');
        $html = ob_get_contents();
      ob_end_clean();

      $response = array("html" => $html);
      echo json_encode($response);
      break;

    case "checkFile":
        os_cck_site_controller::checkFile($option);
        break;

    case 'getChildSelect':
    //init variables
    $db = JFactory::getDbo();
    $session = JFactory::getSession();
    $jinput = JFactory::getApplication()->input;

    //get post ajax params
    $parent_field = json_decode($jinput->getRaw('field',false));
    $lid = $jinput->getRaw('lid',false);
    $value = $jinput->getRaw('value',false);
    $current_value = $jinput->getRaw('currentValue', false);

    $unique_parent_id = json_decode($jinput->getRaw('unique_parent_id',''));

    //ger select params
    $params = new JRegistry;
    $params->loadString($parent_field->params);
    //get allowed_values
    $allowed_values = explode('\sprt', $params->get('allowed_value'));
    $tmp_values = [];
    foreach ($allowed_values as $value) {
      $tmp_values[] = explode('|', $value)[0];
    }
    $allowed_values = $tmp_values;
    //get child_select
    $child_select = explode('|',$params->get('child_select'));
    //get allowed_values => child_select array
    $childs = array_combine($allowed_values,$child_select);
    
    if(!isset($childs[$current_value]) || $childs[$current_value] == 0){
      return;
    }

    $child_fid = $childs[$current_value];
    
    $entityInstance = new os_cckEntityInstance($db);
    $entityInstance->load($parent_field->fk_eid);

    $field = $entityInstance->getField($child_fid);
    $unique_parent_field = $entityInstance->getField($unique_parent_id);
    
    $layout = new os_cckLayout($db);
    $layout->load($lid);
    //get necessary datas
    $layout_params = unserialize($layout->params);
    $bootstrap_version = $session->get( 'bootstrap','2');
    $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
    $layout_html = urldecode($layout->layout_html);
    $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
    $field_styling = get_field_styles($unique_parent_field, $layout);
    $custom_class = get_field_custom_class($unique_parent_field, $layout);

    $layout_params['field_styling'] = $field_styling;
    $layout_params['custom_class'] = $custom_class;
    $layout_params['custom_fields'] = unserialize($layout->custom_fields);
    $layout_params['fields']['Params_text_select_list_'.$child_fid] = $layout_params['fields']['Params_text_select_list_'.$unique_parent_id];


    // print_r($custom_class);
    // exit;

    if($layout->type == 'search'){
      require getSiteSearchFiledViewPath('com_os_cck', $field->field_type);
    }else{
      require getSiteAddFiledViewPath('com_os_cck', $field->field_type);
    }
    
    break;


    default:
//		Category::listCategories($catid);
      break;
  }
}

class os_cck_site_controller{
  //os cck controller class

static function prepere_field_for_show($field, $value,$row=0)
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

  static function checkFile() {
      $path = protectInjectionWithoutQuote("path");
      $filename = basename(protectInjectionWithoutQuote("file"));
      $file = $path . $filename;
      if (file_exists($file)) {
          echo "The file with such name already is!";
      } else {
          echo "";
      }
  }


  static function paypal(){
    global $db;
    $operation=protectInjectionWithoutQuote('operation');
    if(isset($operation) && $operation == 'success') {
        $dispatcher = JDispatcher::getInstance();
        $plugin_name = 'paypal';
        $plugin = JPluginHelper::importPlugin( 'payment',$plugin_name);
        $a = '';
        $userName = '';
        $userEmail = '';
        $html = $dispatcher->trigger('validateIPN');
        if(isset($html[0]))$html = $html[0];
        if(JRequest::getVar('payer_email','') || count($html)>2){
            $userId  = JRequest::getVar('userId','');
            if($userId){
                $sql = "SELECT  name,username,email FROM  `#__users` WHERE id= '".$userId."'";
                $db->setQuery($sql);
                $result = $db->loadObjectList();
                $result = $result['0'];
                $userName = $result->name;
                $userEmail = $result->email;
            }
            if(!$userName)$userName = protectInjectionWithoutQuote('first_name');
            if(!$userEmail)$userEmail = protectInjectionWithoutQuote('payer_email');
            $instId = intval(JRequest::getVar('instId'));
            if($instId){
                if(count($html)>2){///paralel payment
                    // if($html['payKey']){
                    //     $query = "SELECT id FROM #__rem_orders_details "
                    //             ."\n WHERE txn_id = '".$html['payKey']."' "
                    //             ."\n AND status='".$html['responseEnvelope']['ack']."'";
                    //     $db->setQuery($query);
                    //     $result = $db->loadResult();
                    //     if(!empty($result)){
                    //         JError::raiseWarning(0,_REALESTATE_MANAGER_PAYPAL_F5_ERROR);
                    //         return;
                    //     }
                    // }
                    // $status = $html['responseEnvelope']['ack'];
                    // $payer_id = '';
                    // $txn_id = $html['payKey'];
                    // $txn_type = 'comission_payment';
                    // $order_currency_code = JRequest::getVar('currency_code');
                    // $orderId = JRequest::getVar('orderId');
                    // $payer_status = '';
                    // $mc_gross = 0;
                    // $userEmail = $html['senderEmail'];
                    // $html['pending_reason'] = 'Receiver List:<br>________________________';
                    // foreach ($html['paymentInfoList']['paymentInfo'] as $value) {
                    //     $mc_gross += $value['receiver']['amount'];
                    //     $html['pending_reason'] .= '<br>Email:'.$value['receiver']['email']
                    //                             .'<br>Amount:'.$value['receiver']['amount']
                    //                             .'<br>Status:'.$value['senderTransactionStatus'];
                    //     if($value['senderTransactionStatus'] == 'PENDING'){
                    //         $html['pending_reason'] .= '<br>Reason:'.$value['pendingReason'];
                    //     }
                    //     $html['pending_reason'] .= '<br>________________________';
                    // }
                    // $raw_data = serialize($html);
                }else{
                    $status = protectInjectionWithoutQuote('payment_status');
                    $payer_id = intval(JRequest::getVar('payer_id'));
                    $txn_id = intval(JRequest::getVar('txn_id'));
                    $txn_type = protectInjectionWithoutQuote('txn_type');
                    $payer_status = JRequest::getVar('payer_status');
                    $mc_gross = protectInjectionWithoutQuote('mc_gross');
                    $order_currency_code = protectInjectionWithoutQuote('mc_currency');
                    $orderId = intval(JRequest::getVar('orderId'));
                    $raw_data = serialize($_REQUEST);
                }               
               
                $sql = "UPDATE #__os_cck_orders SET order_date = now(), status='" . $status . "',
                        payer_id='".$payer_id."',
                        order_price='".$mc_gross."',
                        order_currency='".$order_currency_code."',
                        txn_id='".$txn_id."',
                        txn_type='".$txn_type."',
                        paid_price='".$mc_gross."',
                        notreaded=1,
                        paid_currency='".$order_currency_code."',
                        payer_status='".$payer_status."' WHERE id = '".$orderId."'";
                $db->setQuery($sql);
                $db->query();
                $itemName = protectInjectionWithoutQuote("item_name",'');
                $sql = "INSERT INTO #__os_cck_orders_details( fk_order_id, fk_user_id, fk_instance_id,
                                                              instance_title, user_email, user_name, status,
                                                              order_date,txn_type, txn_id, payer_id, payer_status,
                                                              order_price, order_currency, payment_details)
                        VALUES ('".$orderId."','".$userId."','". $instId ."',
                                '".$itemName."','".$userEmail."','".$userName."','".$status."',
                                now(),'".$txn_type."','".$txn_id."',  '".$payer_id."',
                                '".$payer_status."',  '".$mc_gross."', '".$order_currency_code."',
                                ".$db->Quote($raw_data).")";
                $db->setQuery($sql);
                $db->query();
            }else{
                JError::raiseWarning(0,JText::_("COM_OS_CCK_PAYPAL_ERROR"));
                return;
            }
            echo JText::_("COM_OS_CCK_PAYPAL_SUCCESS_PAYMENT");
        }
    } elseif(isset($_GET['operation']) && JRequest::getVar('operation') == 'cancel') {
        echo JText::_("COM_OS_CCK_PAYPAL_UNSUCCESS_PAYMENT");
    }
  }

  static function ajax_rent_calcualete($eiids,$ceid,$rent_from,$rent_until){

    $resulArr = calculatePriceCCK($eiids,$ceid,$rent_from,$rent_until);
    echo json_encode(array("price"=>$resulArr["price"],"currency"=>$resulArr["currency"]));
    exit;
  }

  static function saveInstance($option){
    global $db, $user,$task, $Itemid, $app;
    $post = $_POST;

    if(isset($post['eiid'])) unset($post['eiid']);
    // Params(cck component menu)
    $menu = new JTableMenu($db);
    $menu->load($Itemid);
    $params = new JRegistry;
    $params->loadString($menu->params);
    //end


    $query = "SELECT c.title,c.lid,c.params,c.fk_eid ,c.mail, ch.layout_html FROM #__os_cck_layout AS c"
            ."\n LEFT JOIN #__os_cck_entity_instance AS ei ON c.lid = ei.fk_lid"
            ."\n LEFT JOIN #__os_cck_layout_html AS ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid = ".protectInjectionWithoutQuote('lay_type');


    $db->setQuery($query);
    $layout = $db->loadObjectList();
    $layout_params = unserialize($layout[0]->params);

   
    $instance = new os_cckEntityInstance($db);
    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $data['fields_data'] = array();
    $price = 0;
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0){
        $key = str_replace('fi_', '', $key);
        $data['fields_data'][$key] = $var;
        if(isset($layout_params["fields"][$key."_price_field"])){
          $price += $var;
        }
      }else{
        continue;
      }
    }
    $data["instance_price"] = $price;
    if (isset($post['id']) && $post['id'] != 0) {
      $instance->load($post['id']);
      $data['changed'] = date("Y-m-d H:i:s");
    } else {
      $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".intval(JRequest::getVar('lay_type'));
      $db->setQuery($query);
      $data['fk_eid'] = $db->loadResult();
      $data['created'] = date("Y-m-d H:i:s");
    }
    $data['title'] = protectInjectionWithoutQuote('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    $data['fk_userid'] = $user->id;
    $data['fk_lid'] = protectInjectionWithoutQuote('lay_type','');

    if(checkAccess_cck($layout_params['views']['access_publish'], $user->groups))
    {
        if(isset($layout_params['views']['layout_publish_on_add'])){
          $data['published'] = 1;
        }else{
          $data['published'] = 0;
        }
    }else{
        $data['published'] = 0;
    }
        
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $instance->fields_data = '';
    $instance->categories = '';

    if(checkAccess_cck($layout_params['views']['access_approved'], $user->groups))
    {
        if(isset($layout_params['views']['layout_approve_on_add'])){
          $data['approved'] = 1;
        }else{
          $data['approved'] = 0;
        }
        
    }else{
          $data['approved'] = 0;
    }
  
    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    //entity_name, entity_tbale_name
    $entitty = new os_cckEntity($db);
    $entitty->load($instance->fk_eid);
    $instance->_entity_name = $entitty->name;
    $instance->_field_list = $entitty->getFieldList($layout[0]->layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);
    $instance->_layout_params = $layout_params['fields'];
    $instance->_layout_html = $layout[0]->layout_html;

    $instance->check();
    
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }
    $fields_for_mail = array();

    foreach($instance->getFields() as $field)
    {

        $field_val = $instance->getFieldValue($field);

        if(isset($field_val[0])) $field_val = $field_val[0];

        if(isset($field_val->data))
        {
           $fields_for_mail[$field->db_field_name] = $field_val->data;
        }

        if($field->field_type == 'datetime_popup'){
          unset($fields_for_mail[$field->db_field_name]);
        }

    }


    $fields_for_mail = array_merge($fields_for_mail, $data['fields_data']);

    $layout_mail = new os_cckLayout($db);
    $layout_mail->load($instance->fk_lid);
    $mail = unserialize($layout_mail->mail);

    foreach($fields_for_mail as $key => $field)
    {
        $mail['cck_mail_body'] = str_replace("{|".$key."|}", $field, $mail['cck_mail_body']);
    }

     //if date field apply data_transform_cck
    foreach ($instance->_field_list as $field) {
      if($field->field_type == 'datetime_popup'){
        $date_format = $layout_params['fields']['datetime_popup_'.$field->fid.'_input_format'];
        $time_format = $layout_params['fields']['datetime_popup_'.$field->fid.'_input_time_format'];
        $format = $date_format.' '.$time_format;
        $date = $instance->fields_data['datetime_popup_'.$field->fid];
        $instance->fields_data['datetime_popup_'.$field->fid] = data_transform_cck($date, $format);
      }
    }


    $instance->store();
    $Itemid  = intval(JRequest::getVar('Itemid'));
    $id = intval(JRequest::getVar('eiid'));
    $catid = intval(JRequest::getVar('catid',''));

    $layout_html = urldecode($layout[0]->layout_html);

    if(strpos($layout_html,"{|f-cck_mail|}")){

      // $mail = unserialize($layout[0]->mail);
      $mail_body = $mail['cck_mail_body'];
      //check access
      if(isset($mail['cck_mail_access'])){
        $user = JFactory::getUser();      
        if(checkAccess_cck($mail['cck_mail_access'], $user->groups)){

        }
      }
    }//end


    if($catid)
      $catid = '&catid='.$catid;
    if(!empty($id) && $id > 0){
    //    print_r($_SERVER['HTTP_REFERER']);
    // exit;
      //JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid")
      $app->redirect('index.php?option=com_os_cck&task=instance_manager&Itemid='.$Itemid,JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }elseif($post['redirect'] == 'instance_manager'){
    
      $app->redirect('index.php?option=com_os_cck&task=instance_manager&Itemid='.$Itemid,JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }else{
      
      $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }

  }

  static function send_buy_request($option){
    global $db, $user,$task;
    $post = JRequest::get('post');
    $parent_instance = intval(JRequest::getVar('fk_eiid'));
    $instance = new os_cckEntityInstance($db);
    $parentIns = new os_cckEntityInstance($db);
    $parentIns->load($parent_instance);
    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $data['fields_data'] = array();
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0) $data['fields_data'][str_replace('fi_', '', $key)] = $var;
    }
    //   print_r($data['fields_data']);
    // exit;
    $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $data['fk_eid'] = $db->loadResult();
    $data['created'] = date("Y-m-d H:i:s");

    $data['title'] = protectInjectionWithoutQuote('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    $data['fk_userid'] = $user->id;
    $data['fk_lid'] = protectInjectionWithoutQuote('lay_type','');
    $data['published'] = 1;
    $data['approved'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $instance->fields_data = '';
    $instance->categories = '';
    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    //entity_name, entity_tbale_name
    $entitty = new os_cckEntity($db);
    $entitty->load($instance->fk_eid);
    $instance->_entity_name = $entitty->name;
    $instance->_entity_table_name = "#__os_cck_entity_" . $entitty->name;
    $query = "SELECT c.title,c.lid,c.params,c.fk_eid ,c.mail, ch.layout_html FROM #__os_cck_layout AS c"
            ."\n LEFT JOIN #__os_cck_entity_instance AS ei ON c.lid = ei.fk_lid"
            ."\n LEFT JOIN #__os_cck_layout_html AS ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid = ".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $layout = $db->loadObjectList();
    $instance->_field_list = $entitty->getFieldList($layout[0]->layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);
    $layout_params = unserialize($layout[0]->params);
    $instance->_layout_params = $layout_params['fields'];
    $instance->_layout_html = $layout[0]->layout_html;
    $instance->instance_price = $parentIns->instance_price; 
    $instance->instance_currency = $parentIns->instance_currency;
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }


    $fields_for_mail = array();

    foreach($parentIns->getFields() as $field)
    {

         $field_val = $parentIns->getFieldValue($field);

          if(isset($field_val[0])) $field_val = $field_val[0];


          if(isset($field_val->data))
          {
             $fields_for_mail[$field->db_field_name] = $field_val->data;
          }



         if($field->field_type == 'datetime_popup'){

          unset($fields_for_mail[$field->db_field_name]);

         }


      }

    $fields_for_mail = array_merge($fields_for_mail, $data['fields_data']);



    $layout_mail = new os_cckLayout($db);
    $layout_mail->load($instance->fk_lid);
    $mail = unserialize($layout_mail->mail);

    
    
    foreach($fields_for_mail as $key => $field)
    {

        $mail['cck_mail_body'] = str_replace("{|".$key."|}", $field, $mail['cck_mail_body']);

    }


    $instance->store();
    $query = "INSERT INTO #__os_cck_child_parent_connect (media_type,fid_parent,fid_child)"
            ."\n VALUES ('instance',$parent_instance,$instance->eiid)";
    $db->setQuery($query);
    $db->query();


    $layout_html = urldecode($layout[0]->layout_html);

    if(strpos($layout_html,"{|f-cck_mail|}")){

      // $mail = unserialize($layout[0]->mail);
      $mail_body = $mail['cck_mail_body'];
      //check access
      if(isset($mail['cck_mail_access'])){
        $user = JFactory::getUser();      
        if(checkAccess_cck($mail['cck_mail_access'], $user->groups)){

        }
      }
    }//end



    $Itemid  = intval(JRequest::getVar('Itemid'));
    $id = intval(JRequest::getVar('fk_eiid'));
    $catid = intval(JRequest::getVar('catid',''));
    if($catid)
      $catid = '&catid='.$catid;

    //JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid")
    $backLink = JRoute::_($_SERVER['HTTP_REFERER']);
    //mosRedirect($backLink,JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    $paypalStatus = false;
    $os_cck_configuration = JComponentHelper::getParams('com_os_cck');
    if($os_cck_configuration->get("use_paypal",0)){
      $query = "SELECT enabled FROM #__extensions WHERE element='paypal'";
      $db->setQuery($query);
      $status = $db->loadResult();
      if($status){
        $paypalStatus = true;
      }
    }
    //insert into orders
    $userId= $user->get("id","");
    $userEmail = $user->get("email","");
    $userName = $user->get("name","");
    $sql = "SELECT instance_price, instance_currency FROM #__os_cck_entity_instance WHERE eiid='".$parent_instance."'";
    $db->setQuery($sql);
    $res = $db->loadObjectList();
    $instTitle = protectInjectionWithoutQuote('title','');

    $sql = "INSERT INTO  #__os_cck_orders(fk_user_id, fk_instance_id, fk_request_id,instance_type, instance_title, user_email , user_name , status,
                                        txn_type, order_date , order_price, order_currency, notreaded)
            VALUES ('".$userId."', '".$parent_instance."','".$instance->eiid."','Buy', '".$instTitle."', '".$userEmail."',
                    '".$userName."', 'Pending', 'Buy request',now(),'".$res['0']->instance_price."', '".$res['0']->instance_currency."', 1)";
    $db->setQuery($sql);
    $db->query();
    $orderId = $db->insertid();
    $sql = "INSERT INTO #__os_cck_orders_details( fk_order_id, fk_user_id, fk_instance_id,
                    instance_title, user_email, user_name, status,
                    order_date,txn_type)
            VALUES (".$orderId.",'".$userId."','". $parent_instance ."',
                    '".$instTitle."','".$userEmail."','".$userName."','Pending',
                    now(),'Buy request')";
    $db->setQuery($sql);
    $db->query();
    $_REQUEST['OrderID'] =$orderId;
    $_REQUEST['userId'] = $userId;

    //end
    $instance->title = $instTitle;
    HTML_os_cck::showBuyRequestThanks($backLink, $paypalStatus, $instance);

  }

  static function send_rent_request($option){
    global $db, $user,$task, $app;
    $input = $app->input;
    $post = JRequest::get('post');
    $Itemid  =intval(JRequest::getVar('Itemid'));
    $id = intval(JRequest::getVar('fk_eiid'));
    $catid = intval(JRequest::getVar('catid',''));
    if($catid)
      $catid = '&catid='.$catid;

    // if(JPluginHelper::isEnabled('payment','paypal')){
    //   if(empty($post['calculated_price'])){
    //     $app->redirect(JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid"),JText::_("COM_OS_CCK_LABEL_REQUEST_PRICE_ERROR"));
    //   }
    // }

    $parent_instance = intval(JRequest::getVar('fk_eiid'));
    $instance = new os_cckEntityInstance($db);
    $parentIns = new os_cckEntityInstance($db);
    $parentIns->load($parent_instance);
    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $rent_from = $data['rent_from'].' '.$data['time_from'];
    $rent_until = $data['rent_until'].' '.$data['time_until'];
    
    $data['fields_data'] = array();
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0) $data['fields_data'][str_replace('fi_', '', $key)] = $var;
    }

    $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $data['fk_eid'] = $db->loadResult();
    $data['created'] = date("Y-m-d H:i:s");
    $data['title'] = protectInjectionWithoutQuote('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    $data['fk_userid'] = $user->id;
    $data['fk_lid'] = protectInjectionWithoutQuote('lay_type','');
    $data['published'] = 1;
    $data['approved'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $entitty = new os_cckEntity($db);
    $entitty->load($data['fk_eid']);
    $instance->_entity_name = $entitty->name;
    $instance->_entity_table_name = "#__os_cck_entity_" . $entitty->name;

    $instance->instance_currency = $parentIns->instance_currency;

    //entity_name, entity_tbale_name
    $query = "SELECT c.title,c.lid,c.params,c.fk_eid ,c.mail, ch.layout_html FROM #__os_cck_layout AS c"
            ."\n LEFT JOIN #__os_cck_entity_instance AS ei ON c.lid = ei.fk_lid"
            ."\n LEFT JOIN #__os_cck_layout_html AS ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid = ".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $layout = $db->loadObjectList();
    $layout_params = unserialize($layout[0]->params);
    $instance->_layout_params = $layout_params['fields'];
    $instance->_layout_html = $layout[0]->layout_html;
    $instance->_field_list = $entitty->getFieldList($instance->_layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);

    $calculate_price = array();

    foreach ($instance->_field_list as $field) {

      if($field->field_type == 'datetime_popup'){
        if($instance->_layout_params[$field->db_field_name.'_field_type'] == 'rent_from'){
          if($input->get('fi_'.$field->db_field_name, "")){
            $rentFrom = $input->get('fi_'.$field->db_field_name, "");
          }
        }
        if($instance->_layout_params[$field->db_field_name.'_field_type'] == 'rent_to'){
          if($input->get('fi_'.$field->db_field_name, "")){
            $rentTo = $input->get('fi_'.$field->db_field_name, "");
          }
        }
      }

      if($field->field_type == 'decimal_textfield'){
        if($instance->_layout_params[$field->db_field_name.'_price_field']){
          $data['fields_data'][$field->db_field_name] = protectInjectionWithoutQuote('calculated_price','0');
        }
      }
    }

    if(isset($rentFrom) && isset($rentTo)){
      $calculate_price = calculatePriceCCK($parent_instance,$data['fk_eid'],$rentFrom,$rentTo, $layout[0]->lid);
    }else{
      $calculate_price["price"] = 0;
    }

    $instance->instance_price = $calculate_price["price"];
    $instance->fields_data = '';
    $instance->categories = '';
    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }

    $fields_for_mail = array();
    foreach($parentIns->getFields() as $field){

       $field_val = $parentIns->getFieldValue($field);

        if(isset($field_val[0])) $field_val = $field_val[0];

        if(isset($field_val->data))
        {
           $fields_for_mail[$field->db_field_name] = $field_val->data;
        }
       if($field->field_type == 'datetime_popup'){

        if($instance->_layout_params[$field->db_field_name.'_field_type'] == 'rent_from'){
          if($input->get('fi_'.$field->db_field_name, "")){
            $fields_for_mail[$field->db_field_name] = $input->get('fi_'.$field->db_field_name, "");
          }
        }
        if($instance->_layout_params[$field->db_field_name.'_field_type'] == 'rent_to'){
          if($input->get('fi_'.$field->db_field_name, "")){
            $fields_for_mail[$field->db_field_name] = $input->get('fi_'.$field->db_field_name, "");
          }
        }

       }
    }


    $fields_for_mail = array_merge($fields_for_mail, $data['fields_data']);



    $layout_mail = new os_cckLayout($db);
    $layout_mail->load($instance->fk_lid);
    $mail = unserialize($layout_mail->mail);

    

    foreach($fields_for_mail as $key => $field){
        $mail['cck_mail_body'] = str_replace("{|".$key."|}", $field, $mail['cck_mail_body']);
    }


    $instance->store();
    $query = "INSERT INTO #__os_cck_child_parent_connect (media_type,fid_parent,fid_child)"
            ."\n VALUES ('instance',$parent_instance,$instance->eiid)";
    $db->setQuery($query);
    $db->query();

    //mail block
    $layout_html = urldecode($layout[0]->layout_html);

    if(strpos($layout_html,"{|f-cck_mail|}")){

      // $mail = unserialize($layout[0]->mail);
      $mail_body = $mail['cck_mail_body'];
      //check access
      if(isset($mail['cck_mail_access'])){
        $user = JFactory::getUser();      
        if(checkAccess_cck($mail['cck_mail_access'], $user->groups)){


        }
      }
    }//end



    $Itemid  = intval(JRequest::getVar('Itemid'));
    $id = intval(JRequest::getVar('fk_eiid'));
    $catid = intval(JRequest::getVar('catid',''));
    if($catid)
      $catid = '&catid='.$catid;
    //JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid")
    //mosRedirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));

    $backLink = JRoute::_($_SERVER['HTTP_REFERER']);
    $paypalStatus = false;
    $os_cck_configuration = JComponentHelper::getParams('com_os_cck');
    if($os_cck_configuration->get("use_paypal",0)){
      $query = "SELECT enabled FROM #__extensions WHERE element='paypal'";
      $db->setQuery($query);
      $status = $db->loadResult();
      if($status){
        $paypalStatus = true;
      }
    }
    //insert into orders
    $userId= $user->get("id","");
    $userEmail = $user->get("email","");
    $userName = $user->get("name","");
    $sql = "SELECT instance_price, instance_currency FROM #__os_cck_entity_instance WHERE eiid='".$parent_instance."'";
    $db->setQuery($sql);
    $res = $db->loadObjectList();
    $instTitle = protectInjectionWithoutQuote('title','');
    //calculate price for rent
    // $calcPrice = calculatePriceCCK($parent_instance,$instance->fk_eid,$rent_from,$rent_until);

    //$calcPrice = $calculate_price;
    //$instance->instance_price = $calcPrice[0];
    //end
    $sql = "INSERT INTO  #__os_cck_orders(fk_user_id, fk_instance_id,fk_request_id,instance_type, instance_title, user_email , user_name , status,
                                        txn_type, order_date , order_price, order_currency, notreaded)
            VALUES ('".$userId."', '".$parent_instance."','".$instance->eiid."','Rent', '".$instTitle."', '".$userEmail."',
                    '".$userName."', 'Pending', 'Rent request',now(),'".$instance->instance_price."', '".$res['0']->instance_currency."', 1)";
    $db->setQuery($sql);
    $db->query();
    $orderId = $db->insertid();
    $sql = "INSERT INTO #__os_cck_orders_details( fk_order_id, fk_user_id, fk_instance_id,
                    instance_title, user_email, user_name, status,
                    order_date,txn_type)
            VALUES (".$orderId.",'".$userId."','". $parent_instance ."',
                    '".$instTitle."','".$userEmail."','".$userName."','Pending',
                    now(),'Rent request')";
    $db->setQuery($sql);
    $db->query();
    $_REQUEST['OrderID'] =$orderId;
    $_REQUEST['userId'] = $userId;

    //end
    $instance->title = $instTitle;
    HTML_os_cck::showRentRequestThanks($backLink, $paypalStatus, $instance);

  }

  static function send_request($option){
    global $db, $user,$task,$app;
    $post = JRequest::get('post');
    $parent_instance = intval(JRequest::getVar('fk_eiid'));
    $instance = new os_cckEntityInstance($db);
    $parentIns = new os_cckEntityInstance($db);
    $parentIns->load($parent_instance);

    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $data['fields_data'] = array();
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0) $data['fields_data'][str_replace('fi_', '', $key)] = $var;
    }
    $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $data['fk_eid'] = $db->loadResult();
    $data['created'] = date("Y-m-d H:i:s");

    $data['title'] = protectInjectionWithoutQuote('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    $data['fk_userid'] = $user->id;
    $data['fk_lid'] = protectInjectionWithoutQuote('lay_type','');
    $data['published'] = 1;
    $data['approved'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $instance->fields_data = '';
    $instance->categories = '';
    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    //entity_name, entity_tbale_name
    $entitty = new os_cckEntity($db);
    $entitty->load($instance->fk_eid);
    $instance->_entity_name = $entitty->name;
    $instance->_entity_table_name = "#__os_cck_entity_" . $entitty->name;
    
    $query = "SELECT c.title,c.lid,c.params,c.fk_eid ,c.mail, ch.layout_html FROM #__os_cck_layout AS c"
            ."\n LEFT JOIN #__os_cck_entity_instance AS ei ON c.lid = ei.fk_lid"
            ."\n LEFT JOIN #__os_cck_layout_html AS ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid = ".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $layout = $db->loadObjectList();
    $layout_params = unserialize($layout[0]->params);
    $instance->_layout_params = $layout_params['fields'];
    $instance->_layout_html = $layout[0]->layout_html;

    $instance->_field_list = $entitty->getFieldList($instance->_layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }


   $fields_for_mail = array();

    foreach($parentIns->getFields() as $field)
    {

         $field_val = $parentIns->getFieldValue($field);

          if(isset($field_val[0])) $field_val = $field_val[0];


          if(isset($field_val->data))
          {
             $fields_for_mail[$field->db_field_name] = $field_val->data;
          }

        if($field->field_type == 'datetime_popup'){

          unset($fields_for_mail[$field->db_field_name]);

         }

      }
    

    $fields_for_mail = array_merge($fields_for_mail, $data['fields_data']);



    $layout_mail = new os_cckLayout($db);
    $layout_mail->load($instance->fk_lid);
    $mail = unserialize($layout_mail->mail);

    
     foreach($fields_for_mail as $key => $field)
    {

        $mail['cck_mail_body'] = str_replace("{|".$key."|}", $field, $mail['cck_mail_body']);

    }



    $instance->store();
    if($parent_instance){
      $query = "INSERT INTO #__os_cck_child_parent_connect (media_type,fid_parent,fid_child)"
              ."\n VALUES ('instance',$parent_instance,$instance->eiid)";
      $db->setQuery($query);
      $db->query();
    }



 

    $layout_html = urldecode($layout[0]->layout_html);

    if(strpos($layout_html,"{|f-cck_mail|}")){

      // $mail = unserialize($layout[0]->mail);
      $mail_body = $mail['cck_mail_body'];
      //check access
      if(isset($mail['cck_mail_access'])){
        $user = JFactory::getUser();      
        if(checkAccess_cck($mail['cck_mail_access'], $user->groups)){


        }
      }
    }//end

    $Itemid  = protectInjectionWithoutQuote('Itemid');
    $id = protectInjectionWithoutQuote('eiid');
    if($id){
      $catid = protectInjectionWithoutQuote('catid','');
      if($catid)
        $catid = '&catid='.$catid;
      //JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid")
      $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }else{
      $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));//maybe need add in all request function HTTP_REFERER //no need get request item to set return url
    }

  }

  static function send_review_request($option){
    global $db, $user,$task, $Itemid,$app;
    // Params(cck component menu)
    $menu = new JTableMenu($db);
    $menu->load($Itemid);
    $params = new JRegistry;
    $params->loadString($menu->params);
    
    $post = JRequest::get('post');

    $parent_instance = protectInjectionWithoutQuote('fk_eiid');
    $instance = new os_cckEntityInstance($db);
    $parentIns = new os_cckEntityInstance($db);
    $parentIns->load($parent_instance);
    $data = $post;

    //select add clild firlds for sale
    $select_list = array();
    foreach ($data as $key => $value) {
       if(stripos($key,'fi_text_select_list_') !== false){
        $select_id = str_ireplace('fi_text_select_list_', '', $key);
          $select_list[] = $instance->getField($select_id);
       }
    }
    //select add clild firlds for sale

    $data['fields_data'] = array();
    foreach ($post as $key => $var) {
      if (strpos($key, 'fi_') === 0) $data['fields_data'][str_replace('fi_', '', $key)] = $var;
    }

    $query = "SELECT c.fk_eid FROM #__os_cck_layout as c WHERE c.lid=".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $data['fk_eid'] = $db->loadResult();
    $data['created'] = date("Y-m-d H:i:s");

    $data['title'] = protectInjectionWithoutQuote('title','');
    $data['asset_id'] = 0;
    if(!isset($post['categories'])){
      $data['categories'] = array();
    }
    $data['fk_userid'] = $user->id;
    $data['fk_lid'] = protectInjectionWithoutQuote('lay_type','');
    $data['published'] = 1;
    $data['checked_out'] = 0;
    $data['checked_out_time'] = 0;
    $data['teid'] = 0;
    $instance->fields_data = '';
    $instance->categories = '';
    $query = "SELECT c.title,c.lid,c.params,c.fk_eid ,c.mail, ch.layout_html FROM #__os_cck_layout AS c"
            ."\n LEFT JOIN #__os_cck_entity_instance AS ei ON c.lid = ei.fk_lid"
            ."\n LEFT JOIN #__os_cck_layout_html AS ch ON c.lid = ch.fk_lid"
            ."\n WHERE c.lid = ".protectInjectionWithoutQuote('lay_type');
    $db->setQuery($query);
    $layout = $db->loadObjectList();
    $layout_params = unserialize($layout[0]->params);


    if(checkAccess_cck($layout_params['views']['access_approved'], $user->groups))
    {
        if(isset($layout_params['views']['layout_approve_on_add'])){
          $data['approved'] = 1;
        }else{
          $data['approved'] = 0;
        }
  
    }else{
        $data['approved'] = 0;
    }        


    if(checkAccess_cck($layout_params['views']['access_publish'], $user->groups))
    {
        if(isset($layout_params['views']['layout_publish_on_add'])){
          $data['published'] = 1;
        }else{
          $data['published'] = 0;
        }

    }else{
        $data['published'] = 0;
    }



    if (!$instance->bind($data)) {
      echo "<script> alert('Entity with this name alredy exist'); window.history.go(-1); </script>\n";
      exit ();
    }
    //entity_name, entity_tbale_name
    $entitty = new os_cckEntity($db);
    $entitty->load($instance->fk_eid);
    $instance->_entity_name = $entitty->name;
    $instance->_entity_table_name = "#__os_cck_entity_" . $entitty->name;
    $instance->_layout_params = $layout_params['fields'];
    $instance->_layout_html = $layout[0]->layout_html;
    $instance->_field_list = $entitty->getFieldList($instance->_layout_html);
    $instance->_field_list = array_merge($instance->_field_list, $select_list);
    if (!$instance->require_check()) {
      echo "<script> alert('Please fill the required fields!'); window.history.go(-1); </script>\n";
      exit ();
    }
   $fields_for_mail = array();

    foreach($parentIns->getFields() as $field)
    {

         $field_val = $parentIns->getFieldValue($field);

          if(isset($field_val[0])) $field_val = $field_val[0];


          if(isset($field_val->data))
          {
             $fields_for_mail[$field->db_field_name] = $field_val->data;
          }



         if($field->field_type == 'datetime_popup'){

          unset($fields_for_mail[$field->db_field_name]);

         }


      }


    $fields_for_mail = array_merge($fields_for_mail, $data['fields_data']);



    $layout_mail = new os_cckLayout($db);
    $layout_mail->load($instance->fk_lid);
    $mail = unserialize($layout_mail->mail);

    
    foreach($fields_for_mail as $key => $field)
    {
        $mail['cck_mail_body'] = str_replace("{|".$key."|}", $field, $mail['cck_mail_body']);
    }

    $instance->store();
    $query = "INSERT INTO #__os_cck_child_parent_connect (media_type,fid_parent,fid_child)"
            ."\n VALUES ('instance',$parent_instance,$instance->eiid)";
    $db->setQuery($query);
    $db->query();


    $layout_html = urldecode($layout[0]->layout_html);

    if(strpos($layout_html,"{|f-cck_mail|}")){

      // $mail = unserialize($layout[0]->mail);
      $mail_body = $mail['cck_mail_body'];
      //check access
      if(isset($mail['cck_mail_access'])){
        $user = JFactory::getUser();      
        if(checkAccess_cck($mail['cck_mail_access'], $user->groups)){

        }
      }
    }//end


    $Itemid  = protectInjectionWithoutQuote('Itemid');
    $id = protectInjectionWithoutQuote('fk_eiid');
    if($id){
      $catid = protectInjectionWithoutQuote('catid','');
      if($catid)
        $catid = '&catid='.$catid;
      //JRoute::_("index.php?option=$option&view=instance&id=$id"."$catid&Itemid=$Itemid")
      $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }else{
      //JRoute::_("index.php?option=$option&view=add_instance&Itemid=$Itemid")
      $app->redirect(JRoute::_($_SERVER['HTTP_REFERER']),JText::_("COM_OS_CCK_LABEL_REQUEST_THANKS"));
    }

  }

  static function orderitem($option){
    global $db, $user,$app;
    $id = (int)$_POST['itemid'];
    $buy_quantity = (int)$_POST['buy_quantity'];
    $priceid = (int)$_POST['priceid'];
    $date = date("Y-m-d");
    $query = "select * from #__os_cck_items where id='$id'";
    $db->setQuery($query);
    $item = loadObjectList();
    if (array_key_exists(0, $item))
      $item = $item[0];
    if ($item->quantity < $buy_quantity) {
      $app->redirect(JRoute::_('index.php?option=com_os_cck'), 'Order not created: exceed item quantity.');
      exit;
    }
    if ($item->published != 1) {
      $app->redirect(JRoute::_('index.php?option=com_os_cck'), 'Order not created: wrong item.');
      exit;
    }
    $query = "insert into #__os_cck_order (itemid, quantity, userid, date_income, status, price_plan) VALUES
              ('$id', '$buy_quantity', '$user->id', '$date', 'pending', '$priceid')";
    $db->setQuery($query);
    $db->query();
    $app->redirect(JRoute::_('index.php?option=com_os_cck'), 'Your order successfuly added');
  }

  static function saveBuyingRequest($option, $eiids)
  {
    global $app, $db, $user, $Itemid, $acl;
    global $os_cck_configuration, $mosConfig_mailfrom;
    $buying_request = new mosOS_CCK_buying_request($db);
    if (!$buying_request->bind($_POST)) {
        echo $buying_request->getError();
        exit ();
    }
    $buying_request->buying_request_date = date("Y-m-d H:i:s");
    if (!$buying_request->store()) {
	    $status =  JText::_("COM_OS_CCK_WARNING");
      $info = "Please check fields and try again later! ". " error:" . $buying_request->getError();
      echo json_encode(array("status"=>$status,"info"=>$info));
      exit ();

    } else {
	   $status =  JText::_("COM_OS_CCK_SUCCESS");
      $info = JText::_("COM_OS_CCK_LABEL_BUYING_REQUEST_THANKS");
      echo json_encode(array("status"=>$status,"info"=>$info));
      exit ();
	  }
  	$currentcat = NULL;
  	// Parameters
  	$menu =new mosMenu( $db );
  	$menu->load( $Itemid );
  	$params =new mosParameters( $menu->params );
  	$params->def( 'header', $menu->name );
  	$params->def( 'pageclass_sfx', '' );
  	$params->def( 'show_search', '1' );
  	$params->def( 'back_button', $app->getCfg( 'back_button' ) );
  	$currentcat->descrip = JText::_("COM_OS_CCK_LABEL_BUYING_REQUEST_THANKS");
  	// page image
  	$currentcat->img = "./components/com_os_cck/images/em_logo.jpg";
  	$currentcat->header = $params->get( 'header' );
  	//sending notification
  	if( ($os_cck_configuration['buyingrequest_email_show']) && trim($os_cck_configuration['buyingrequest_email_address']) != "" )
    {
  		$params->def( 'show_email', 1 );
  		if (checkAccess($os_cck_configuration['buyingrequest_email_registrationlevel'],'RECURSE', userGID($user->id), $acl)) {
  			$params->def( 'show_input_email', 1);
  		}
  	}
  }

  static function checkCaptcha(){
      global $db, $acl,$user;
  //*********************   begin compare to key   ***************************ч
      $session = JFactory::getSession();
      $type = protectInjectionWithoutQuote('captcha_type','');
      if(isset($_POST['keyguest']) && ($type == 'review_instance'))
        $password = $session->get('captcha_review_instance_keystring', 'default');
      if(isset($_POST['keyguest']) && ($type == 'rent_request_instance'))
        $password = $session->get('captcha_rent_request_instance_keystring', 'default');
      if(isset($_POST['keyguest']) && ($type == 'buy_request_instance'))
        $password = $session->get('captcha_buy_request_instance_keystring', 'default');
      if(isset($_POST['keyguest']) && ($type == 'add_instance'))
        $password = $session->get('captcha_add_instance_keystring', 'default');
      if(isset($_POST['keyguest']) && ($type == 'request_instance'))
        $password = $session->get('captcha_request_instance_keystring', 'default');
  //**********************   end compare to key   *****************************
      
      if (isset($_POST['keyguest']) && ($_POST['keyguest'] != $password)) {
          print_r( json_encode(array('status'=> false)));
          exit ();
      }else{
          $status =  JText::_("COM_OS_CCK_SUCCESS");
          print_r( json_encode(array("status"=>$status)));
          exit ();
      }
  }

  //this function check - is exist houses in this folder and folders under this category
  static function is_exist_curr_and_subcategory_items($catid,$eid){
    global $db, $user;
    $query = "SELECT * FROM #__os_cck_categories AS cc"
      . "\n INNER JOIN #__os_cck_categories_connect AS a ON a.fk_cid = cc.cid"
      . "\n INNER JOIN #__os_cck_entity_instance AS cei ON cei.eiid = a.fk_eiid AND cei.fk_eid ='".$eid."' "
      . "\n WHERE  cc.cid='$catid' "
      . "\n AND cc.published = '1'"
      . "\n GROUP BY cc.cid"
      . "\n ORDER BY cc.ordering";
    $db->setQuery($query);
    $categories = $db->loadObjectList();
    if($catid == 8){
    count($categories);
   }
    if (count($categories) != 0) return $categories;

    $query = "SELECT cid "
        . "FROM #__os_cck_categories AS cc "
        . " WHERE parent_id='{$catid}' AND published='1' AND section='com_os_cck' ";
    $db->setQuery($query);
    $categories = $db->loadObjectList();

    if (count($categories) == 0) return false;
    foreach ($categories as $k) {
      if (self::is_exist_curr_and_subcategory_items($k->cid,$eid)) return $categories;
    }
    return false;
  }

  //end function

  //*****************************************************************************

  //this function check - is exist folders under this category
  static function is_exist_subcategory_items($catid){
    global $db, $user;
    $query = "SELECT *, COUNT(a.fk_cid) AS numlinks FROM #__os_cck_categories AS cc" .
      "\n JOIN #__os_cck_categories_connect AS a ON a.fk_cid = cc.cid" .
      "\n WHERE  cc.parent_id='$catid' " .
      "\n GROUP BY cc.cid" .
      "\n ORDER BY cc.ordering";
    $db->setQuery($query);
    $categories = $db->loadObjectList();

    if (count($categories) != 0) return $categories;
    if (count($categories) == 0) return false;

    foreach ($categories as $k) {
      if (is_exist_subcategory_items($k->id)) return $categories;
    }
    return false;
  }

  //end function


  /**
   * This function is used to show a list of all houses
   */


  static function cck_constructPathway($data, $type){
    global $app, $db, $option, $Itemid;
    switch ($type) {
      case "category":
        print_r($data);exit;
        break;
    }
    $query = "SELECT * FROM #__os_cck_categories ";
    $db->setQuery($query);
    $rows = $db->loadObjectlist('cid');
    $pid = $cat->cid;
    $pathway = array();
    $pathway_name = array();
    while ($pid != 0) {
      $cat = @$rows[$pid];
      $pathway[] = JRoute::_('index.php?option=' . $option . '&view=category&catid=' . @$cat->cid . '&Itemid=' . $Itemid);
      $pathway_name[] = @$cat->name;
      $pid = @$cat->parent_id;
    }
    $breadcrumbs = $app->getPathWay();
    $pathway = array_reverse($pathway);
    $pathway_name = array_reverse($pathway_name);
    $path_way = $app->getPathway();
    for ($i = 0, $n = count($pathway); $i < $n; $i++) {
      $path_way->addItem($pathway_name[$i], $pathway[$i]);
    }
  }

  static function secretImageCCK($type){
    $session = JFactory::getSession();
    if($type=='review_instance'){
      $pas = $session->get('captcha_review_instance_keystring', 'default');
    }
    if($type=='rent_request_instance'){
      $pas = $session->get('captcha_rent_request_instance_keystring', 'default');
    }
    if($type=='buy_request_instance'){
      $pas = $session->get('captcha_buy_request_instance_keystring', 'default');
    }
    if($type=='request_instance'){
      $pas = $session->get('captcha_request_instance_keystring', 'default');
    }
    if($type=='add_instance'){
      $pas = $session->get('captcha_add_instance_keystring', 'default');
    }
    $new_img = new PWImageCCK();
    $new_img->set_show_string($pas);
    $new_img->get_show_image(2.2, array(mt_rand(0, 50), mt_rand(0, 50), mt_rand(0, 50)), array(mt_rand(200, 255),
        mt_rand(200, 255), mt_rand(200, 255)));
    exit();
  }



  static function userGID($oID){
    global $db, $ueConfig;
    if ($oID > 0) {
      $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id = '" . $oID . "'";
      $db->setQuery($query);
      $gid = $db->loadResult();
      return $gid;
    } else return 0;
  }



  static function listRssCategories(){
    global $db;
    $Itemid = intval($_GET['Itemid']);
    $catid = protectInjectionWithoutQuote('catid');
    if ($catid == "") $where_catid = "";
    else $where_catid = " AND c.id=" . $catid;
    $query = "SELECT c.id as catid, c.title as ctitle, a.id,"
             ."\n a.description,a.title,a.image,a.edok_link,a.date,a.price"
             ."\n FROM #__categories as c, #__os_cck_items as a"
             ."\n LEFT JOIN #__os_cck_adequacy as b ON a.id=b.itemid"
             ."\n WHERE b.cat_id=c.id AND a.published='1' AND c.published='1'" . $where_catid;
    $db->setQuery($query);
    $cat_all = $db->loadObjectList();
    HTML_os_cck :: showRssCategories($cat_all, $catid);
  }
}