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
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/front_end_style.css");

$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js","text/javascript",true);
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");
$doc->addScript(JUri::root() . 'components/com_os_cck/assets/js/jquery.raty.js');
$key = 'key='.$os_cck_configuration->get("google_map_key",'');
$doc->addScript('//maps.google.com/maps/api/js?'.$key);
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery.cck_timepicker.css");
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery.cck_timepicker.js","text/javascript",true);

if(isset($params) && $params->get('show_type','')){
  $show_type = $params->get('show_type');
}elseif(isset($layout_params['show_type'])){
  $show_type = $layout_params['show_type'];
}else{
  $show_type = 1;
}

?>
<div class="cck-body">

  <script>
    var checkExpr = [];
  </script>

  <?php
  $hidden = '';

  if(!$layout) {
    echo "</div>"; //close <div class="cck-body">
    return;
  }

  if(isset($layout_params['views']['show_layout_title']) 
    && $layout_params['views']['show_layout_title']){
    echo "<h3>";
      echo $layout_params['views']['layout_title'];
    echo "</h3>";
  }

  $fields_list = $layout->field_list;
  $layout_type = $layout->type;
  $unique_form_id = $layout_type.'_Form_'.$layout->parent_eiid.$moduleId;
  $field_from_params = $layout_params['fields'];
  $layout_html = urldecode($layout->layout_html);
  $layout_html = str_replace('data-label-styling', 'style',  $layout_html);

  //add child selects to layout
  $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
  $layout_html = $addChildSelectToLayout['layout_html'];
  $layout_params = $addChildSelectToLayout['layout_params'];
  $parent = $addChildSelectToLayout['select_parent'];
  $layout->params = serialize($layout_params);
  //add child selects to layout
   
  if($show_type == 3){
    $Itemid  = JRequest::getVar('Itemid');
    if(isset($layout->fk_eid)){
      $link_eiid = "&eiid=$layout->parent_eiid";
    }
    $link = JRoute::_(JURI::root()."index.php?option=com_os_cck&task=show_request_layout$link_eiid&lid=".$layout->lid."&Itemid=$Itemid");?>
    <a <?php echo $button_style['field_styling'];?> class="button <?php echo $button_style['custom_class'];?>" href="<?php echo $link ?>"> <?php echo ($layout_params['button_name'])?$layout_params['button_name'] : JText::_('COM_OS_CCK_BUTTON_SHOW_FORM_BUY_REQUEST'); ?>
    </a>
    <?php
    echo "</div>"; //close <div class="cck-body">
    return;
  }
  
  $from = false;
  $to = false;

  if(!$layout_params['has_price']){
    JError::raiseWarning(0, JText::_("COM_OS_CCK_ERROR_NO_PRICE_FIELD") );
    echo "</div>"; //close <div class="cck-body">
    return;
  }
  
  if($show_type == 2){?>
    <input <?php echo $button_style['field_styling'];?> type="button" class="button btn btn-info <?php echo $button_style['custom_class'];?>" value="<?php echo ($layout_params['button_name'])?$layout_params['button_name'] : JText::_('COM_OS_CCK_BUTTON_SHOW_FORM_BUY_REQUEST'); ?>"
          onclick="jQuerCCK('#request_wrapper_<?php echo $layout_type?><?php echo ($moduleId) ? '_cckmod_'.$moduleId : '' ;?>').stop().fadeToggle();"/>  
    <div class="rent_request_wrapper" id="request_wrapper_<?php echo $layout_type?><?php echo ($moduleId) ? '_cckmod_'.$moduleId : '' ;?>" style="display: none;">
    <?php
  }

  $field = new stdClass();
  $field->db_field_name = 'form-'.$layout->lid;
  $form_styling = get_field_styles($field, $layout);
  $form_custom_class = get_field_custom_class($field, $layout);

  ?>
  <form class="<?php echo $form_custom_class; ?>" <?php echo $form_styling; ?> action="index.php" method="post" name="<?php echo $unique_form_id?>" id="<?php echo $unique_form_id?>" enctype="multipart/form-data">
    
    <?php
    if(isset($layout_params['title'])){
      echo '<input type="hidden" name="title" value="'.$layout_params['title'].'">';
    }

    for ($i = 0, $nnn = count($fields_list); $i < $nnn; $i++) {
      
      $html = '';
      $field = $fields_list[$i];
      $field_styling = get_field_styles($field, $layout);
      $custom_class = get_field_custom_class($field, $layout);
      $layout_params['field_styling'] = $field_styling;
      $layout_params['custom_class'] = $custom_class;
      
      //start render custom code
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
            $dispatcher = JDispatcher::getInstance();
            JPluginHelper::importPlugin('content');
            $plug_row = new stdClass();
            $plug_row->text = $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
            $dispatcher->trigger('onContentPrepare', array('com_os_cck', &$plug_row, &$plug_params, 0));
            $custom_field['custom_code_field_'.$cust_key.'_custom_code'] = $plug_row->text;
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
      //end render custom code

      //delete inappropriate short cods
      if(strpos($layout_html,"{|f-cck_mail|}")){
        $layout_html = str_replace("{|f-cck_mail|}",'', $layout_html);
      }
      //delete inappropriate short cods

      if(strpos($layout_html,"{|f-".$field->fid."|}")){

        //publish\unpublish check
        if((!isset($field_from_params[$field->db_field_name.'_published']) 
            || $field_from_params[$field->db_field_name.'_published'] != 'on')){
          $layout_html = str_replace("{|f-".$field->fid."|}", '', $layout_html);
          continue;
        }
        //publish\unpublish check

        //check access
        if(isset($field_from_params['access_'.$field->db_field_name])
            && $field_from_params['access_'.$field->db_field_name] != '1'){
          $user = JFactory::getUser();
          if(!checkAccess_cck($field_from_params['access_'.$field->db_field_name], $user->groups)){
            $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
            continue;
          }
        }
        //check access
        
        //regular expression
        if(isset($field_from_params[$field->db_field_name.'_check_expression'])){
          ?>
          <script>
            checkExpr[checkExpr.length] = ["<?php echo $field->db_field_name;?>",
                                           encodeURI(<?php echo $field_from_params[$field->db_field_name.'_check_expression']?>),
                                           "<?php echo $field_from_params[$field->db_field_name.'_error_text'];?>"];
          </script>
          <?php
        }
        //regular expression

        //add prefix_
        if(!empty($field_from_params[$field->db_field_name.'_prefix'])){
          $html .= '<span class="'.$field->db_field_name.'_prefix'.'">'.$field_from_params[$field->db_field_name.'_prefix'].'</span>';
        }
        //add prefix_

        $hidden = '';
        $value = '';

        if($field->field_type == 'decimal_textfield' &&  $layout_type == 'buy_request_instance' 
            && $layout_params["fields"][$field->db_field_name."_price_field"]){
          $entityInstance = new os_cckEntityInstance($db);
          $entityInstance->load(intval($layout->parent_eiid));
          $value = $entityInstance->getFieldValue($field);
          $layout_params['instance_currency'] = $entityInstance->instance_currency;
          ob_start();
            require getSiteShowFiledViewPath('com_os_cck', $field->field_type);
            $html .= ob_get_contents();
          ob_end_clean();
        }else {
          $fromSearch = 0;
          $field_from_params['ceid'] = $layout->fk_eid;
          $field_from_params['eiid'] = $layout->parent_eiid;
          $field_from_params['lay_type'] = $layout_type;
          $field_from_params['form_id']= $layout_type.'_Form_'.$layout->parent_eiid.$moduleId;
          $field_from_params['field_styling']= $field_styling;
          $field_from_params['custom_class']= $custom_class;
          ob_start();
          require getSiteAddFiledViewPath('com_os_cck', $field->field_type);
          $html .= ob_get_contents();
          ob_end_clean();
        }

        //add _suffix  
        if(!empty($field_from_params[$field->db_field_name.'_suffix'])){
          $html.= '<span class="'.$field->db_field_name.'_suffix'.'">'.$field_from_params[$field->db_field_name.'_suffix'].'</span>';
        }
        //add _suffix

        //add field to layout / replace shortcode
        $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);

      }
    }
    
    if(isset($layout_params['attachedModule'])){
      foreach ($layout_params['attachedModule'] as $attachedModule) {
        if($attachedModule){
          if(strpos($layout_html,"{|m-".$attachedModule->id."|}")){
            $field->field_name = 'm_'.$attachedModule->id;
            $field_styling = get_field_styles($field, $layout);
            $custom_class = isset($layout_params['fields'][$field->field_name.'_custom_class'])?$layout_params['fields'][$field->field_name.'_custom_class']:'';
            $module = JModuleHelper::getModule($attachedModule->type,$attachedModule->title);
            $options  = array('style' => 'xhtml');
            $html = '<div class="'.$custom_class.'" '.$field_styling.'>'.JModuleHelper::renderModule($module,$options).'</div>';
            $layout_html = str_replace("{|m-".$attachedModule->id."|}", $html, $layout_html);
          }
        }
      }
    }

    //send button
    $field->db_field_name = 'cck_send_button';
    $field_styling = get_field_styles($field, $layout);
    $custom_class = get_field_custom_class($field, $layout);
    
    ob_start();
      echo $hidden;
      $buttonText = isset($layout_params["views"]["layout_button_text"])
                    && !empty($layout_params["views"]["layout_button_text"])?$layout_params["views"]["layout_button_text"]:JText::_('COM_OS_CCK_BUTTON_FORM_BUY_REQUEST');?>
      <input <?php echo $field_styling; ?> type="button" name="buy_request_button" value="<?php echo $buttonText; ?>"
                      class="button <?php echo $custom_class; ?>" onclick="javascript:sendRequest<?php echo $layout->type?>('send_buy_request',<?php echo $unique_form_id?>);">
      <?php
      $layout_html = str_replace("{|f-cck_send_button|}", ob_get_contents(), $layout_html);
    ob_end_clean();
    //send button

    //render layout
    echo $layout_html;  
    ?>

    <div class="message-here"></div>
    <input type="hidden"  name="calculated_price" value="">
    <input type="hidden"  name="rent_from" value="">
    <input type="hidden"  name="rent_until" value="">
    <input type="hidden" name="option" value="com_os_cck"/>
    <input type="hidden" name="lay_type" value="<?php echo $layout->lid?>"/>
    <input type="hidden" name="task" value="save_instance"/>
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>"/>
    <input type="hidden" name="moduleId" value="<?php echo $moduleId ?>"/>
    <input type="hidden" name="catid" value="<?php echo JRequest::getVar('catid',''); ?>"/>
    <input type="hidden" name="fk_eiid" value="<?php echo (isset($layout->parent_eiid) && !empty($layout->parent_eiid))? $layout->parent_eiid : '' ?>"/>
    <input type="hidden" name="lid" value="<?php echo (JRequest::getVar('lid',''))?JRequest::getVar('lid'):$layout->lid ; ?>"/>
  </form>

  <?php if($show_type == 2) echo "</div>";?>
  
  <script>
    jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
      jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"))
    });
  jQuerCCK("div[class *='cck-row-']").addClass('row');
    var send_request = null;
    var send_buy_request = null;
    var review_captcha_request = null;
    var check_captcha_request_<?php echo $layout->type?> = null;
    (function ($) {
      check_captcha_request_<?php echo $layout->type?> = function (moduleId,form,type) {
        var status = "";
        if(moduleId){
          form=form+'_'+moduleId;
        }
        $.post("index.php?option=com_os_cck&captcha_type="+type+"&task=checkCaptcha&format=raw", $(form.keyguest).serialize(),
        function (data) {
          if (data.status == '<?php echo JText::_("COM_OS_CCK_SUCCESS"); ?>') {
            form.submit();
          }else{
            reload_captcha_<?php echo $layout->type?>(moduleId,type);
            jQuerCCK("[name='keyguest']").val('');
            alert("<?php echo JText::_("COM_OS_CCK_INFOTEXT_JS_WRONG_CAPTCHA");?>");
          }
        } , 'json' );
      }
    })(jQuerCCK);

  //*****************   begin add for show/hiden button "Add review" ********************
    function button_hidden(is_hide,moduleId) {
      var moduleSuffix = '';
      if (moduleId){
        moduleSuffix = '_cckmod_'+moduleId;
      }
      if (moduleId) {
        var el = document.getElementById('button_hidden_review'+moduleSuffix);
        var el2 = document.getElementById('hidden_review'+moduleSuffix);
      }else{
        var el = document.getElementById('button_hidden_review');
        var el2 = document.getElementById('hidden_review');
      }
      if (is_hide) {
        el.style.display = 'none';
        el2.style.display = 'block';
      } else {
        el.style.display = 'block';
        el2.style.display = 'none';
      }
    }


    function findPosY(obj) {
      var curtop = 0;
      if (obj.offsetParent) {
        while (1) {
          curtop+=obj.offsetTop;
          if (!obj.offsetParent) {
            break;
          }
          obj=obj.offsetParent;
        }
      } else if (obj.y) {
        curtop+=obj.y;
      }
      return curtop-20;
    }

    function sendRequest<?php echo $layout->type?> (task,form_name){
      var form = form_name;
      var required_fields = form.getElementsByClassName("required");
      var post_max_size = <?php echo return_bytes(ini_get('post_max_size')) ?>;
      var upl_max_fsize = <?php echo return_bytes(ini_get('upload_max_filesize')) ?>;
      var file_upl = <?php echo ini_get('file_uploads') ?>;
      var total_file_size = 0;

      for (i = 1;document.getElementById('new_upload_video'+i); i++){
        if(document.getElementById('new_upload_video'+i).files.length){
          total_file_size += document.getElementById('new_upload_video'+i).files[0].size;
          if(document.getElementById('new_upload_video'+i).value != ''){
            if(!file_upl){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_video'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_UPLOAD_OFF'); ?>";
              document.getElementById('new_upload_video'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_video'+i).style.color = "#FF0000";
              document.getElementById('error_video').style.color = "#FF0000";
              return;
            }
            if(document.getElementById('new_upload_video'+i).files[0].size >= post_max_size){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_video'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_POST_MAX_SIZE'); ?>";
              document.getElementById('new_upload_video'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_video'+i).style.color = "#FF0000";
              document.getElementById('error_video').style.color = "#FF0000";
              return;
            }
            if(document.getElementById('new_upload_video'+i).files[0].size >= upl_max_fsize){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_video'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_UPLOAD_MAX_SIZE'); ?>";
              document.getElementById('new_upload_video'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_video'+i).style.color = "#FF0000";
              document.getElementById('error_video').style.color = "#FF0000";
              return;
            }
          }
        }
      }

      for (i = 1;document.getElementById('new_upload_audio'+i); i++){
        if(document.getElementById('new_upload_audio'+i).files.length){
          if(document.getElementById('new_upload_audio'+i).value != ''){
            total_file_size += document.getElementById('new_upload_audio'+i).files[0].size;
            if(!file_upl){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_audio'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_UPLOAD_OFF'); ?>";
              document.getElementById('new_upload_audio'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_audio'+i).style.color = "#FF0000";
              document.getElementById('error_audio').style.color = "#FF0000";
              return;
            }
            if(document.getElementById('new_upload_audio'+i).files[0].size >= post_max_size){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_audio'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_POST_MAX_SIZE'); ?>";
              document.getElementById('new_upload_audio'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_audio'+i).style.color = "#FF0000";
              document.getElementById('error_audio').style.color = "#FF0000";
              return;
            }
            if(document.getElementById('new_upload_audio'+i).files[0].size >= upl_max_fsize){
              window.scrollTo(0,findPosY(document.getElementById('new_upload_audio'+i))-100);
              document.getElementById('error_video').innerHTML = "<?php
               echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_UPLOAD_MAX_SIZE'); ?>";
              document.getElementById('new_upload_audio'+i).style.borderColor = "#FF0000";
              document.getElementById('new_upload_audio'+i).style.color = "#FF0000";
              document.getElementById('error_audio').style.color = "#FF0000";
              return;
            }
          }
        }
      }

      if(total_file_size >= post_max_size){
        if(document.getElementById('error_video')){
          window.scrollTo(0,findPosY(document.getElementById('error_video'))-100);
          document.getElementById('error_video').innerHTML = "<?php
           echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_POST_MAX_SIZE'); ?>";
          document.getElementById('error_video').style.borderColor = "#FF0000";
          document.getElementById('error_video').style.color = "#FF0000";
          document.getElementById('error_video').style.color = "#FF0000";
          return;
        }
        if(document.getElementById('error_audio')){
          window.scrollTo(0,findPosY(document.getElementById('error_audio'))-100);
          document.getElementById('error_audio').innerHTML = "<?php
           echo JText::_('COM_OS_CCK_SETTINGS_VIDEO_ERROR_POST_MAX_SIZE'); ?>";
          document.getElementById('error_audio').style.borderColor = "#FF0000";
          document.getElementById('error_audio').style.color = "#FF0000";
          document.getElementById('error_audio').style.color = "#FF0000";
          return;
        }
      }

      if(form.new_upload_track_url1){
        for (i = 1;document.getElementById('new_upload_track_url'+i); i++) {
          if(document.getElementById('new_upload_track'+i).value != ''
            || document.getElementById('new_upload_track_url'+i).value != ''){
              if(document.getElementById('new_upload_track_kind'+i).value == ''){
                window.scrollTo(0,findPosY(document.getElementById('new_upload_track_kind'+i))-100);
                document.getElementById('new_upload_track_kind'+i).placeholder = "<?php
                 echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_TRACK_KIND'); ?>";
                document.getElementById('new_upload_track_kind'+i).style.borderColor = "#FF0000";
                document.getElementById('new_upload_track_kind'+i).style.color = "#FF0000";
                return;
              }else if(document.getElementById('new_upload_track_scrlang'+i).value == ''){
                window.scrollTo(0,findPosY(document.getElementById('new_upload_track_scrlang'+i))-100);
                document.getElementById('new_upload_track_scrlang'+i).placeholder = "<?php
                 echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_TRACK_LANGUAGE'); ?>";
                document.getElementById('new_upload_track_scrlang'+i).style.borderColor = "#FF0000";
                document.getElementById('new_upload_track_scrlang'+i).style.color = "#FF0000";
                return;
              }else if(document.getElementById('new_upload_track_label'+i).value == ''){
                window.scrollTo(0,findPosY(document.getElementById('new_upload_track_label'+i))-100);
                document.getElementById('new_upload_track_label'+i).placeholder = "<?php
                 echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_TRACK_TITLE'); ?>";
                document.getElementById('new_upload_track_label'+i).style.borderColor = "#FF0000";
                document.getElementById('new_upload_track_label'+i).style.color = "#FF0000";
                return;
              }
            }
          }
        }
        ret = false;
        jQuerCCK(required_fields).each(function(index, el) {
          if(jQuerCCK(el).attr("name") == "new_audio"){
            audio = false;
            jQuerCCK("[id*='new_upload_audio']").each(function(index, input) {
              if(jQuerCCK(input).val()){
                audio = true;
              }
            });
            if(!audio){
              //alert("<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_REQUIRED_AUDIO');?>");
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              ret = true;
              return false;
            }
          }else if(jQuerCCK(el).attr("name") == "new_video"){
            video = false;
            jQuerCCK("[id*='new_upload_video']").each(function(index, input) {
              if(jQuerCCK(input).val()){
                video = true;
              }
            });
            if(!video){
              //alert("<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_REQUIRED_VIDEO');?>");
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              ret = true;
              return false;
            }
          }else if(jQuerCCK(".rev_rating").length){
            if(!jQuerCCK("[name*='fi_rating_field_']").val()){
              //alert("<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_REQUIRED');?>");
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              ret = true;
              return false;
            }
          }else if(jQuerCCK(el).parent().find("[name*='text_radio_buttons']").length){
            if(!jQuerCCK(el).parents(".controls").find("[name*='text_radio_buttons']:checked").length){
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).parent().css("borderColor", "#FF0000");
              jQuerCCK(el).parent().css("color", "#FF0000");
              ret = true;
              return false;
            }
          }else if(jQuerCCK(el).parent().find("[name*='single_checkbox_onoff']").length){
            if(jQuerCCK(el).prop('checked') == false){
              window.scrollTo(0,findPosY(el)-100);
              alert("<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_REQUIRED');?>");
              ret = true;
              return false;
            }
          }else if(jQuerCCK(el).parent().find("[name*='fi_text_url_']").length){
            if(!jQuerCCK(el).val() || jQuerCCK(el).val() == 'http://'){
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              ret = true;
              return false;
            }
          }else{
            if(!jQuerCCK(el).val() || jQuerCCK(el).val() == 0){
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              ret = true;
              return false;
            }
          }
        });

         if(jQuerCCK("input[name*='fi_decimal_textfield_']:not(.hidden-price)").length){
          jQuerCCK("input[name*='fi_decimal_textfield_']:not(.hidden-price)").each(function(index, el) {
            if(jQuerCCK(this).val()){
              step = jQuerCCK(this).attr("step");
              if(step.indexOf(".") >= 0){
                point = step.length - 1 - step.indexOf(".");
                numb = step.length-1-point;
              }else{
                numb = step.length;
                point = 0;
              }

              if(step.indexOf(".") >= 0){
                vallen = jQuerCCK(this).val().length;
                if(numb < vallen-1-jQuerCCK(this).val().indexOf(".")){
                  window.scrollTo(0,findPosY(el)-100);
                  jQuerCCK(el).css("borderColor", "#FF0000");
                  jQuerCCK(el).css("color", "#FF0000");
                  alert("Format: "+step);
                  ret = true;
                  return false;
                }
                if(point < vallen - 1 - jQuerCCK(this).val().indexOf(".")){
                  window.scrollTo(0,findPosY(el)-100);
                  jQuerCCK(el).css("borderColor", "#FF0000");
                  jQuerCCK(el).css("color", "#FF0000");
                  alert("Format: "+step);
                  ret = true;
                  return false;
                }
              }else{
                if(numb < jQuerCCK(this).val().length){
                  window.scrollTo(0,findPosY(el)-100);
                  jQuerCCK(el).css("borderColor", "#FF0000");
                  jQuerCCK(el).css("color", "#FF0000");
                  alert("Format: "+step);
                  ret = true;
                  return false;
                }
              }
            }
          });
        }
        
        if(ret){
          return;
        }

        var error = false;
        //check for regular exp // set array of field=>exp in fields above(checkExpr)
        jQuerCCK(checkExpr).each(function(index, el) {
          if(typeof el == "object"){
            regEx = eval(decodeURI(el[1]));
            if(regEx){
              str = jQuerCCK("[name='fi_"+el[0]+"']").val();
              if(!regEx.test(str)){
                if(el[2]){
                  errorText = el[2];
                }else{
                  errorText = '';
                }
                window.scrollTo(0,findPosY(jQuerCCK("[name='fi_"+el[0]+"']"))-100);
                jQuerCCK("[name='fi_"+el[0]+"']").attr("placeholder", errorText);
                jQuerCCK("[name='fi_"+el[0]+"']").css("borderColor", "#FF0000");
                jQuerCCK("[name='fi_"+el[0]+"']").css("color","#FF0000");
                error = true;
              };
            }
          }
        });
        if(error){return;}
        type = 'buy_request_instance';
        form.task.value = 'send_buy_request';
        if(form.querySelector('div[id^="captcha-block"]')){
          check_captcha_request_<?php echo $layout->type?>(0,form,type);
        }else{
          form.submit();
        }
    }
    jQuerCCK("*[class*='hide-field-name-'] , .delete-field, .delete-row").remove();
    //jQuerCCK("span.cck-help-string").remove();
    jQuerCCK(function () {
      jQuerCCK('[data-toggle="tooltip"]').tooltip()
    })
  </script>
</div>
