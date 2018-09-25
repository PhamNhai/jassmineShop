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
?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>

<div class="parent_instance_block">
  <div id="instance_block" class="tabcontent">
      <?php
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
      $layout_html = urldecode($layout->layout_html);
      $fields_list = $layout->field_list;
      $layout_type = $layout->type;


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
        $layout_params['field_styling'] = $field_styling;
        $custom_class = isset($layout_params['fields'][$field->db_field_name.'_custom_class'])?$layout_params['fields'][$field->db_field_name.'_custom_class']:'';
        //get tag
        $shell_tag = isset($layout_params['fields']['field_tag_'.$field->db_field_name])?$layout_params['fields']['field_tag_'.$field->db_field_name]:'div';
        //end
        if(strpos($layout_html,"{|f-".$field->fid."|}")){
          //check access
          $value = $entityInstance->getFieldValue($field);
          if($field->field_type == 'rating_field' && isset($layout_params['fields'][$field->db_field_name.'_average'])
              && $layout_params['fields'][$field->db_field_name.'_average'] == 'on'){
            $value = get_average_rating($field, $layout, $entityInstance);
          }
          if(empty($value)){
            $layout_html = str_replace("{|f-".$field->fid."|}", '', $layout_html);
            continue;
          }
          if(isset($layout_params['fields']['access_'.$field->db_field_name])
              && $layout_params['fields']['access_'.$field->db_field_name] != '1'){
            $user = JFactory::getUser();
            if(!checkAccess_cck($layout_params['fields']['access_'.$field->db_field_name], $user->groups)){
              $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
              continue;
            }else{
              $layout_html = str_replace($field->db_field_name.'-label-hidden', $html, $layout_html);
            }
          }
          if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
              $layout_params['fields']['showName_'.$field->db_field_name] == 'on' ){
            $layout_html = str_replace($field->db_field_name.'-label-hidden', $html, $layout_html);
          }
          //i don't sure but maybe it is empty field check
          if (@$value[0]->data == '' && $field->field_type != 'locationfield'
              && $field->field_type != 'audiofield' && $field->field_type != 'videofield'
              && $field->field_type != 'text_single_checkbox_onoff') {
            $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
            continue;
          }
          if ($field->field_type == 'datetime_popup' && $value[0]->data == '0000-00-00 00:00:00') {
            $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
            continue;
          }
          if ($field->field_type == 'locationfield' && !$value[0]->address) {
            $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
            continue;
          }
          ////end
          if(isset($layout_params['fields'][$field->db_field_name]['options'])){
            $field->options = $layout_params['fields'][$field->db_field_name]['options'];
          }

          // $global_settings = unserialize($field->global_settings);
          // if(isset($global_settings['title_field']) && $global_settings['title_field']){
          //   if(isset($value[0]->data) && !empty($value[0]->data))$title = $value[0]->data;
          //   else $title = $entityInstance->eiid;
          //   $layout_params['title'] = $title;
          // }
          $image_css = ($field->field_type=='imagefield' && isset($field->options['width'])
                        && isset($field->options['height']))?
                        'width:'.$field->options['width'].'px; height:'.$field->options['height'].'px;':
                        '';
          $width_heigth = (isset($field->options['width'])) ? ' width="' . $field->options['width'] . 'px" ' : '';
          $width_heigth .= (isset($field->options['height'])) ? ' height="' . $field->options['height'] . 'px" ' : '';

  $html .='<'.$shell_tag.$width_heigth.' class="col_box '.$custom_class.'">';
              $layout_params['instance_currency'] = $entityInstance->instance_currency;
              ob_start();
                require getSiteShowFiledViewPath('com_os_cck', $field->field_type);
                $html .= ob_get_contents();
              ob_end_clean();
  $html .='</'.$shell_tag.'>';
        }
        $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
        $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
      } 
      //start render custom code
      if(isset($layout_params['custom_fields']) && count($layout_params['custom_fields'])){
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

      //$layout_html = str_replace("{|f-cck_instance_navigation|}", '', $layout_html);
      $layout_html = str_replace("Instance Navigation", '',$layout_html);
      $layout_html = preg_replace('/([{][|][\w\d[:punct:]]*[|][}])/', '', $layout_html);

      echo $layout_html;
      ?>
    <script type="text/javascript">
      jQuerCCK("span[class*='hide-field-name-'] , .delete-field, .delete-row, .delete-layout").remove();
    </script>
  </div>
  <!--end instance-->
</div>
<!--end parent block -->
