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
$doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/css/front_end_style.css");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
?>
<!--*************************************************************START Search Layout*******************************************************************-->
<div class="cck-body">
  <script type="text/javascript" src="<?php echo JURI::root() ?>/components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js"></script>
  <?php
  $buttonText = isset($layout_params["button_name"]) ? $layout_params["button_name"] : JText::_("COM_OS_CCK_LABEL_SEARCH_BUTTON");

  if(isset($layout_params['show_type']) && $layout_params['show_type'] == 3){
    $Itemid  = intval(JRequest::getVar('Itemid'));
    $link_catid = '';
    if(isset($layout_params['catid'])){
      $link_catid = "&amp;catid=".$layout_params['catid'];
    }
    $link = "index.php?option=com_os_cck&amp;task=show_search&amp;lid=".$layout->lid.$link_catid."&amp;Itemid=$Itemid";
    echo '<a '.$button_style['field_styling'].' class="'.$button_style['custom_class'].'" href="'.JRoute::_($link).'">'.$buttonText.'</a>';

    echo "</div>"; //close <div class="cck-body">
    return;
  }

  if(isset($layout_params['show_type']) && $layout_params['show_type'] == 2){ 
    ?>
    <input <?php echo $button_style['field_styling'];?> type="button" class="button btn btn-info <?php echo $button_style['custom_class'];?>" value="<?php echo $buttonText; ?>"
        onclick="jQuerCCK('#request_wrapper_<?php echo $layout->type?><?php echo ($moduleId) ? '_cckmod_'.$moduleId : '' ;?>').stop().fadeToggle();"/>
    <div class="search_request_wrapper" id="request_wrapper_<?php echo $layout->type?><?php echo ($moduleId) ? '_cckmod_'.$moduleId : '' ;?>" style="display: none;">
    <?php
  }

  if(isset($layout_params['views']['show_layout_title']) && $layout_params['views']['show_layout_title'])
  {
    echo "<h3>";
      echo $layout_params['views']['layout_title'];
    echo "</h3>";
  }

  $field = new stdClass();
  $field->db_field_name = 'form-'.$layout->lid;
  $form_styling = get_field_styles($field, $layout);
  $form_custom_class = get_field_custom_class($field, $layout);

  $entityInstance = new os_cckEntityInstance($db);
  $entityInstance->fk_eid = $layout->fk_eid;
  
  ?>
  <form class="<?php echo $form_custom_class; ?>" <?php echo $form_styling; ?> action="<?php echo JRoute::_('index.php'); ?>" method="GET" name="serchForm" id="searchForm">
    <div class="adminlist cck_search">

      <div class="search_checkbox">
        <?php
        $layout_html = urldecode($layout->layout_html);
        $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
        $fields_from_params = $layout_params["fields"];
        $fields_list = $fields;
        
        //add child selects to layout
        $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
        $layout_html = $addChildSelectToLayout['layout_html'];
        $layout_params = $addChildSelectToLayout['layout_params'];
        $parent = $addChildSelectToLayout['select_parent'];
        $layout->params = serialize($layout_params);
        //add child selects to layout



   if(strpos($layout_html,"{|f-cck_search_field|}") 
    && isset($layout_params['fields']['cck_search_field_published']) 
    && $layout_params['fields']['cck_search_field_published'] == 'on'){
  
    ob_start();

    ?>
  
      <div>
          <span class="col_inline">
              <?php if(isset($layout_params['fields']['showName_cck_search_field']) && $layout_params['fields']['showName_cck_search_field'] == 'on') 
              {
               echo isset($layout_params['fields']['cck_search_field_alias'])?$layout_params['fields']['cck_search_field_alias']: JText::_('COM_OS_CCK_SHOW_SEARCH');
              }

               ?>
          </span>
          <span class="col_inline">
              <input type="text" name="search" value="" class="inputbox"
              placeholder="<?php echo $layout_params['fields']['cck_search_field_placeholder'];?>"/>
          </span>
      </div>

    <?php 
      $layout_html = str_replace("{|f-cck_search_field|}", ob_get_contents(), $layout_html);
      ob_end_clean();
    }else{
      $layout_html = str_replace("{|f-cck_search_field|}", '', $layout_html);
    }


        foreach ($fields as $field){
          $html = '';
          if(strpos($layout_html,"{|f-".$field->fid."|}")){
            $field_styling = get_field_styles($field, $layout);
            $custom_class = get_field_custom_class($field, $layout);
            $layout_params['field_styling'] = $field_styling;

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
            //end render

            if(isset($layout_params['fields']['access_'.$field->db_field_name])
                && $layout_params['fields']['access_'.$field->db_field_name] != '1'){
              $user = JFactory::getUser();
              if(!checkAccess_cck($layout_params['fields']['access_'.$field->db_field_name], $user->groups)){
                $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
                continue;
              }
            }

            ob_start();
              require getSiteSearchFiledViewPath('com_os_cck', $field->field_type);
              $html .= ob_get_contents();
            ob_end_clean();

            if(isset($layout_params['fields'][$field->db_field_name.'_published']) && 
              $layout_params['fields'][$field->db_field_name.'_published'] == 'on')
            {
              $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
            }else{
              $layout_html = str_replace("{|f-".$field->fid."|}", '', $layout_html);
            }

            
          }
        }

        if(isset($layout_params['attachedModule'])){
          foreach ($layout_params['attachedModule'] as $attachedModule) {
            if($attachedModule){
              if(strpos($layout_html,"{|m-".$attachedModule->id."|}")){
                $field->db_field_name = 'm_'.$attachedModule->id;
                $field_styling = get_field_styles($field, $layout);
                $custom_class = get_field_custom_class($field, $layout);
                $module = JModuleHelper::getModule($attachedModule->type,$attachedModule->title);
                $options  = array('style' => 'xhtml');
                $html = '<div class="'.$custom_class.'" '.$field_styling.'>'.JModuleHelper::renderModule($module,$options).'</div>';
                $layout_html = str_replace("{|m-".$attachedModule->id."|}", $html, $layout_html);
              }
            }
          }
        }


        ob_start(); 
        $buttonText = isset($layout_params["views"]["layout_button_text"])
                    && !empty($layout_params["views"]["layout_button_text"])?$layout_params["views"]["layout_button_text"]:JText::_('COM_OS_CCK_SEARCH_BUTTON');
        ?>
          <div>
            <input class="button btn btn-primary" type="submit" value="<?php echo $buttonText;?>">
          </div>
        <?php
        $layout_html = str_replace("{|f-cck_send_button|}", ob_get_contents(), $layout_html);
        ob_end_clean();

        echo $layout_html;
        ?>
      </div>
    </div>
    <input type="hidden" value="search" name="task">
    <input type="hidden" name="lid" value="<?php echo $layout->lid; ?>">
    <?php if($moduleId)echo'<input type="hidden" value="'.$moduleId.'" name="moduleId">';?>
    <input type="hidden" value="com_os_cck" name="option">
    <input type="hidden" value="<?php echo $Itemid ?>" name="Itemid">
  </form>

  <?php if(isset($layout_params['show_type']) && $layout_params['show_type'] == 2) echo '</div>'; ?>
  
  <script>
    jQuerCCK("*[class*='hide-field-name-'] , .delete-field, .delete-row, .delete-layout").remove();

    jQuerCCK("input.f-params").remove();

    jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
      jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"))
    });
    jQuerCCK("div[class *='cck-row-']").addClass('row');
    //jQuerCCK("span.cck-help-string").remove();
    jQuerCCK(function () {
      jQuerCCK('[data-toggle="tooltip"]').tooltip()
    })
  </script>
</div>
