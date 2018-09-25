<?php 
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
$doc->addScript(JURI::root() . 'components/com_os_cck/assets/js/jquery.raty.js');

$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js","text/javascript",true);
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");
$key = 'key='.$os_cck_configuration->get("google_map_key",'');
$doc->addScript('//maps.google.com/maps/api/js?'.$key);
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery.cck_timepicker.css");
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery.cck_timepicker.js","text/javascript",true);

$hidden = '';
?>
<script>
    var checkExpr = [];
</script>


<!-- tabs -->
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
  <?php
    $options = Array();
    echo JHtml::_('tabs.start', 'instance-tab', $options);
    echo JHtml::_('tabs.panel', JText::_('COM_OS_CCK_ADMIN_INSTANCE_MAIN'), 'panel_1_id');
  ?>
    <div class="edit_instance">
      <div class="row content-row">
        <span>
        <strong><?php echo JText::_('COM_OS_CCK_LABLE_SELECT_TYPE') ?>:</strong></span>
        <span align="left">
          <?php echo $layout_params['layout_type']; ?>
        </span>
      </div>


  <?php
  $layout_html = urldecode($layout->layout_html);
  $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
  $layout_params['custom_fields'] = unserialize($layout->custom_fields);
  $field_from_params = $layout_params["fields"];
  $fields_list = $layout_params['extra_fields_list'];
  $layout_type = $layout->type;

  //add child selects to layout
  $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
  $layout_html = $addChildSelectToLayout['layout_html'];
  $layout_params = $addChildSelectToLayout['layout_params'];
  $parent = $addChildSelectToLayout['select_parent'];
  $layout->params = serialize($layout_params);
  //add child selects to layout


  for ($i = 0; $i < count($fields_list); $i++){

    $html = '';
    $value = '';
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
    //end render custom code

    //delete inappropriate short cods
    if(strpos($layout_html,"{|f-cck_mail|}")){
      $layout_html = str_replace("{|f-cck_mail|}",'', $layout_html);
    }
    if(strpos($layout_html,"{|f-cck_send_button|}")){
      $layout_html = str_replace("{|f-cck_send_button|}",'', $layout_html);
    }
    if($field->field_type == "captcha_field"){
      $layout_html = str_replace("{|f-".$field->fid."|}",'', $layout_html);
      continue;
    }
    //delete inappropriate short cods


    if(strpos($layout_html,"{|f-".$field->fid."|}")){
      
      //get field value
      $value = $entityInstance->getFieldValue($field);

      //get field value if this rating field
      if($field->field_type == 'rating_field' 
        && isset($layout_params['fields'][$field->db_field_name.'_average'])
        && $layout_params['fields'][$field->db_field_name.'_average'] == 'on'){

        $value = get_average_rating($field, $layout, $entityInstance);
      }
      //get field value if this rating field

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

      if($field->field_type == 'categoryfield'){
        $cats = $value;
        $value = array();
        if(is_array($cats)){
          foreach ($cats as $id){
            if(isset($id->catid)){
              $value[] = $id->catid;
            }
          }
        }  
      }else if($field->field_type == 'locationfield'){
        $value = isset($value[0]) ? $value = $value[0] : '';
      }else{
        $value = (isset($value[0]->data)) ? $value[0]->data : '';
      }
      //check from search or not
      
      $fromSearch = 0;
      $field_from_params['ceid'] = $layout->fk_eid;
      $field_from_params['lay_type'] = $layout_type;
      $field_from_params['field_styling']= $field_styling;
      $field_from_params['custom_class']= $custom_class;
      $field_from_params['form_id'] = "adminForm";

      //attach field
      ob_start();
        require getSiteAddFiledViewPath('com_os_cck', $field->field_type);
      $html .= ob_get_contents();
      ob_end_clean();
      //attach field
      
      //add _suffix  
      if(!empty($field_from_params[$field->db_field_name.'_suffix'])){
        $html.= '<span class="'.$field->db_field_name.'_suffix'.'">'.$field_from_params[$field->db_field_name.'_suffix'].'</span>';
      }
      //add _suffix

      //add field to layout / replace shortcode
      $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
    }
  }
 
    //render layout    
    echo $layout_html;
  ?>


    <input type="hidden" name="id" value="<?php echo $entityInstance->eiid; ?>"/>
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php if(trim($entityInstance->eiid) != ''):?>
      <input id="inst_id" type="hidden" name="eiid[]" value="<?php echo $entityInstance->eiid;?>"/>
    <?php endif;?>
    <input id="inst_task" type="hidden" name="task" value="apply_instance"/>
  </div>

  <script type="text/javascript">
    jQuerCCK("*[class*='hide-field-name-'] , .delete-field, .delete-row, .delete-layout").remove();
    jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
      jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"));
    });
  </script>

  <?php
  echo JHtml::_('tabs.panel', JText::_('COM_OS_CCK_ADMIN_INSTANCE_SETTINGS'), 'panel_2_id');
  ?>

  <div calss="instance-settings-block">
    <div class="settings-block">
      <div calss="settings-header">
        <label><?php echo JText::_('COM_OS_CCK_LABEL_ADVERTISMENT')?>:</label>
      </div>
      <div calss="settings-content">
        <span class="col-1"><?php echo JText::_('COM_OS_CCK_LABEL_FEATURED_CLICKS')?>:</span>
        <span class="col-2">
          <input type="number" name="featured_clicks" value="<?php echo ($entityInstance->featured_clicks <= -1)?'':$entityInstance->featured_clicks?>" min="0" step="1">
        </span>
      </div>
      <div calss="settings-content">
        <span class="col-1"><?php echo JText::_('COM_OS_CCK_LABEL_FEATURED_SHOWS')?>:</span>
        <span class="col-2">
          <input type="number" name="featured_shows" value="<?php echo ($entityInstance->featured_shows <= -1)?'':$entityInstance->featured_shows?>" min="0" step="1">
        </span>
      </div>
      <div calss="settings-content">
        <span class="col-1"><?php echo JText::_('COM_OS_CCK_LABEL_FEATURED_OWNER_ID')?>:</span>
        <span class="col-2">
          <input type="number" name="fk_userid" value="<?php echo ($entityInstance->fk_userid <= 0)?'<?php echo JFactory::getUser()->id;?>':$entityInstance->fk_userid?>" min="1">
        </span>
      </div>

    </div>  
  </div>

  <?php
    echo JHtml::_('tabs.end');
  ?>
</form>

<script language="javascript" type="text/javascript">
  
  jQuerCCK(document).ready(function() {
    jQuerCCK("[make='disabled']").attr('disabled','disabled');
    jQuerCCK(['id^=cck_col']).removeClass('resizable');
    jQuerCCK('.ui-resizable-handle').remove();
  });

  function lay_type_select() {
    document.forms["adminForm"].submit();;
    return;
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

  function trim(string) {
    return string.replace(/(^\s+)|(\s+$)/g, "");
  }

  function add_new_files(show_new_file, hide_button) {
    document.getElementById(show_new_file).style.display = '';
    document.getElementById(hide_button).style.display = 'none';
  }

  function swich_task(task){
    if(task == 'save_instance' || task == 'send_request'){
      document.getElementById('inst_task').value = 'save_instance';
    }
    if(task == 'edit_instance'){
      document.getElementById('inst_task').value = 'edit_instance';
    }
    if(task == 'cancel_instance'){
      document.getElementById('inst_task').value = 'cancel_instance';
    }
  }

  Joomla.submitbutton = function submitForm (task){
    var add = document.getElementById("categories");
    var form = document.adminForm;
    var required_fields = form.getElementsByClassName("required");
    var post_max_size = <?php echo return_bytes(ini_get('post_max_size')) ?>;
    var upl_max_fsize = <?php echo return_bytes(ini_get('upload_max_filesize')) ?>;
    var file_upl = <?php echo ini_get('file_uploads') ?>;
    var total_file_size = 0;

    if(task == 'cancel_instance'){
      swich_task(task);
      form.submit();
      return;
    }

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

    if(jQuerCCK("[name*='fi_decimal_textfield_']").length){
      jQuerCCK("[name*='fi_decimal_textfield_']").each(function(index, el) {
        if(jQuerCCK(this).val()){
          step = jQuerCCK(this).attr("step");
          if(step.indexOf(".") >= 0){
            point = step.length - 1 - step.indexOf(".");
            numb = step.length-1-point;
          }else{
            numb = jQuerCCK(this).attr("max");
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
            if(numb < parseInt(jQuerCCK(this).val())){
              window.scrollTo(0,findPosY(el)-100);
              jQuerCCK(el).css("borderColor", "#FF0000");
              jQuerCCK(el).css("color", "#FF0000");
              alert("Max: "+numb);
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
      swich_task(task);
      form.submit();
    }

</script>
<!--************************   end change review ***********************-->
