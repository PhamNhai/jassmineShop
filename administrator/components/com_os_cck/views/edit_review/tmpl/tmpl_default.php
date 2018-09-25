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


$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
$doc->addScript(JUri::root() . 'components/com_os_cck/assets/js/jquery.raty.js');

$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js","text/javascript",true);
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");

$key = 'key='.$os_cck_configuration->get("google_map_key",'');
$doc->addScript('//maps.google.com/maps/api/js?'.$key);

//timepicker
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery.cck_timepicker.css");
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery.cck_timepicker.js","text/javascript",true);

$moduleId = '';
$show_type = 1;
$count = 0;
$hidden = '';
if(!$layout)return;
$fields_list = $layout->field_list;
$layout_type = $layout->type;
$from = false;
$to = false;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
  <?php
  $layout_html = urldecode($layout->layout_html);
  $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
  $field_from_params = $layout_params["fields"];


  //add child selects to layout
  $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
  $layout_html = $addChildSelectToLayout['layout_html'];
  $layout_params = $addChildSelectToLayout['layout_params'];
  $parent = $addChildSelectToLayout['select_parent'];
  $layout->params = serialize($layout_params);
  //add child selects to layout

  
  for ($i = 0, $nnn = count($fields_list); $i < $nnn; $i++) {
    $html='';
    $field = $fields_list[$i];
    $field_styling = get_field_styles($field, $layout);
    $custom_class = isset($layout_params['fields'][$field->db_field_name.'_custom_class'])?$layout_params['fields'][$field->db_field_name.'_custom_class']:'';
    $layout_params['field_styling'] = $field_styling;
    $layout_params['custom_class'] = $custom_class;
    $value = '';
    if(strpos($layout_html,"{|f-cck_mail|}")){
          $layout_html = str_replace("{|f-cck_mail|}",'', $layout_html);
          //continue;
    }

    $layout_html = str_ireplace(array('Button Send','MAIL'),'', $layout_html);


    if($field->field_type == "captcha_field"){
      $layout_html = str_replace("{|f-".$field->fid."|}",'', $layout_html);
      continue;
    }
    if(strpos($layout_html,"{|f-".$field->fid."|}")){
      $value = $entityInstance->getFieldValue($field);
        if($field->field_type == 'rating_field' && isset($layout_params['fields'][$field->db_field_name.'_average'])
            && $layout_params['fields'][$field->db_field_name.'_average'] == 'on'){
          $value = get_average_rating($field, $layout, $entityInstance);
        }
      if(!isset($field_from_params[$field->db_field_name.'_published']) 
        || $field_from_params[$field->db_field_name.'_published'] != 'on'){
        $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
        continue;
      }
      //check access
      if(isset($field_from_params['showName_'.$field->db_field_name]) &&
          $field_from_params['showName_'.$field->db_field_name] == 'on'){
        $layout_html = str_replace($field->db_field_name.'-label-hidden', $html, $layout_html);
      }
      $value = (isset($value[0]->data)) ? $value[0]->data : '';
      $hidden = '';
      $fromSearch = 0;
      $field_from_params['ceid'] = $layout->fk_eid;
      $field_from_params['lay_type'] = $layout_type;
      // $field_from_params['form_id']= $layout_type.'_Form_'.$count.$moduleId;
      $field_from_params['form_id']= 'adminForm';
      $field_from_params['field_styling']= $field_styling;
      $field_from_params['custom_class']= $custom_class;
      ob_start();
        require getSiteAddFiledViewPath('com_os_cck', $field->field_type);
        $html .= ob_get_contents();
      ob_end_clean();
      $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
    }

  }
  //start render custom code
  $layout_params['custom_fields'] = unserialize($layout->custom_fields);
  if(count($layout_params['custom_fields'])){
    foreach ($layout_params['custom_fields'] as $cust_key => $custom_field) {
      if(strpos($layout_html,"{|f-custom_code_field_".$cust_key."|}")){
        //check access
        if(isset($custom_field['custom_code_field_'.$cust_key.'_access'])
            && $custom_field['custom_code_field_'.$cust_key.'_access'] != '1'){
          $user = JFactory::getUser();
          if(!checkAccess_cck($custom_field['custom_code_field_'.$cust_key.'_access'], $user->groups)){
            $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", '', $layout_html);
            continue;
          }
        }
        // $dispatcher = JDispatcher::getInstance();
        // JPluginHelper::importPlugin('content');
        // $plug_row = new stdClass();
        // $plug_row->text = $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
        // $dispatcher->trigger('onContentPrepare', array('com_os_cck', &$plug_row, &$plug_params, 0));
        // $custom_field['custom_code_field_'.$cust_key.'_custom_code'] = $plug_row->text;
        //if below fn works , that this is add_instance view
        $code_type = $custom_field['custom_code_field_'.$cust_key.'_custom_code_type'];
        if($code_type == 'SCRIPT'){
          $custom_code = '<script type="text/javascript">';
          $custom_code .= $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
          $custom_code .= '</script>';
          $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_code, $layout_html);
        }elseif($code_type == 'PHP'){
          ob_start();
          $custom_code = eval($custom_field['custom_code_field_'.$cust_key.'_custom_code']);
          $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", ob_get_contents(), $layout_html);
          ob_end_clean();
        }elseif($code_type == 'CSS'){
          $custom_css = '<style>'.$custom_field['custom_code_field_'.$cust_key.'_custom_code'].'</style>';
          $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_css, $layout_html);
        }else{
          $custom_code = $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
          $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_code, $layout_html);
        }
      }
    }
  }

  $layout_html = str_replace(array('{|f-cck_send_button|}','Button Send'), '', $layout_html);


  echo $layout_html;
  ?>
  <script type="text/javascript">
    jQuerCCK("span[class*='hide-field-name-'] , .delete-field, .delete-row").remove();
  </script>
  <input type="hidden" name="id" value="<?php echo $entityInstance->eiid; ?>"/>
  <input type="hidden" name="option" value="<?php echo $option; ?>"/>
  <input type="hidden" name="boxchecked" value="0"/>
  <input id="inst_task" type="hidden" name="task" value="save_review"/>
</form>
<?php


AdminReview::showParentInstance('com_os_cck', $layout_params['parent_instance']);
