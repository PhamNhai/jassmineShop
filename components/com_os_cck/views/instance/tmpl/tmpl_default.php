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

$key = 'key='.$os_cck_configuration->get("google_map_key","AIzaSyD4ZY-54e-nzN0-KejXHkUh-D7bbexDMKk");
$doc->addScript('//maps.google.com/maps/api/js?'.$key);
$doc->addScript(JUri::root() . 'components/com_os_cck/assets/js/jquery.raty.js');
$doc->addScript(JUri::root() . "/components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js");
$doc->addScript(JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.js');
$doc->addScript(JURI::root() . '/components/com_os_cck/assets/lightbox/js/lightbox-2.6.min.js');
$doc->addStyleSheet(JUri::root() . '/components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css');
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");
$doc->addStyleSheet(JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.css');
$doc->addStyleSheet(JUri::root() . '/components/com_os_cck/assets/lightbox/css/lightbox.css');
$doc->addScriptDeclaration('jQuerCCK=jQuerCCK.noConflict();');
?>
<div class="cck-body instance_body">
  <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
  <?php

  JPluginHelper::importPlugin('content');
  $dispatcher = JDispatcher::getInstance();

  $Itemid .= ($moduleId) ? '&moduleId=' . $moduleId : '';
  $layout_html = urldecode($layout->layout_html);

  if(isset($layout_params['views']['show_layout_title']) && $layout_params['views']['show_layout_title'])
  {
    echo "<h3>";
      echo $layout_params['views']['layout_title'];
    echo "</h3>";
  }

  $field = new stdClass();
  $field->db_field_name = 'form-'.$layout->lid;
  $form_styling = get_field_styles($field, $layout);
  $field_from_params = $layout_params["fields"];
  $form_custom_class = get_field_custom_class($field, $layout);
  $fields_list = $layout->field_list;

  //add child selects to layout
  $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
  $layout_html = $addChildSelectToLayout['layout_html'];
  $layout_params = $addChildSelectToLayout['layout_params'];
  $parent = $addChildSelectToLayout['select_parent'];
  $layout->params = serialize($layout_params);
  //add child selects to layout

  ?>
  <!--Начало табов-->
  <div class="<?php echo $form_custom_class; ?> instance_block" <?php echo $form_styling; ?> >
      <?php

      for ($i = 0, $nnn = count($fields_list); $i < $nnn; $i++) {
        
        $html = '';
        $field = $fields_list[$i];
        $field_styling = get_field_styles($field, $layout);
        $layout_params['field_styling'] = $field_styling;
        $custom_class = get_field_custom_class($field, $layout);
        $shell_tag = isset($layout_params['fields']['field_tag_'.$field->db_field_name])?$layout_params['fields']['field_tag_'.$field->db_field_name]:'div';

        //start render custom code
        if(count($layout_params['custom_fields'])){
          foreach ($layout_params['custom_fields'] as $cust_key => $custom_field) {
            if(strpos($layout_html,"{|f-custom_code_field_".$cust_key."|}")){
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
                  //replace mask like {|decimal_textfield_31|}
                $func_result = replaceMaskCustomCodePHP($entityInstance,$plug_row->text);
                  //get custom field content
                $custom_field['custom_code_field_'.$cust_key.'_custom_code'] = $func_result['custom_code_str'];
                  //get variables for custom code
                extract($func_result['variables_arr']);
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
        //end render custom 


        if(strpos($layout_html,"{|f-".$field->fid."|}")){
          //check access
          $value = $entityInstance->getFieldValue($field);

          if($field->field_type == 'rating_field' && isset($layout_params['fields'][$field->db_field_name.'_average'])
              && $layout_params['fields'][$field->db_field_name.'_average'] == 'on'){
            $value = get_average_rating($field, $layout, $entityInstance);
          }
          
          if(isset($layout_params['fields'][$field->db_field_name]['options']['strlen']) &&
            $layout_params['fields'][$field->db_field_name]['options']['strlen'])
          {
            $field->len = $layout_params['fields'][$field->db_field_name]['options']['strlen'];
          }

          if(isset($field->len) && strlen($value[0]->data) > $field->len)
          {
              $value[0]->data = substr($value[0]->data,0,$field->len);
          }
             
          if(isset($layout_params['fields'][$field->db_field_name.'_published'])){
            $field->published = true;
          }else{
            $field->published = false;
          }

          if(empty($value) || !$field->published){
            $layout_html = str_replace("{|f-".$field->fid."|}", '', $layout_html);
            continue;
          }

          if(isset($layout_params['fields']['access_'.$field->db_field_name])
              && $layout_params['fields']['access_'.$field->db_field_name] != '1'){
            $user = JFactory::getUser();
            if(!checkAccess_cck($layout_params['fields']['access_'.$field->db_field_name], $user->groups)){
              $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
              continue;
            }
          }

          if(isset($layout_params['fields'][$field->db_field_name]['options'])){
            $field->options = $layout_params['fields'][$field->db_field_name]['options'];
          }

          if(isset($layout_params['fields'][$field->db_field_name.'_title_field']) 
              && $layout_params['fields'][$field->db_field_name.'_title_field']){
            if(isset($value[0]->data) && !empty($value[0]->data))$title = $value[0]->data;
            else $title = $entityInstance->eiid;
            $layout_params['title'] = $title;
          }
          
          $image_css = ($field->field_type=='imagefield' && isset($field->options['width'])
                        && isset($field->options['height']))?
                        'width:'.$field->options['width'].'px; height:'.$field->options['height'].'px;':
                        '';
          $width_heigth = (isset($field->options['width'])) ? ' width="' . $field->options['width'] . 'px" ' : '';
          $width_heigth .= (isset($field->options['height'])) ? ' height="' . $field->options['height'] . 'px" ' : '';
          $field_styling = substr_replace($field_styling, ' display:block;'.$image_css.'"', strlen($field_styling)-1, strlen($field_styling));
          $a_styling = $field_styling;

          //for calendar schedule view
          if(isset($category_params['calendar_layout_params']['fields']['calendar_view_calendar_table'])
            && $category_params['calendar_layout_params']['fields']['calendar_view_calendar_table'] == 'schedule'){

            $layout_params['views']['link_field'] = 
                      (isset($category_params['calendar_layout_params']['fields']['calendar_table_link_field'])) ? 
                      $category_params['calendar_layout_params']['fields']['calendar_table_link_field'] : 
                      array();
          }

          $html .='<'.$shell_tag.$width_heigth.' '.$field_styling.' class="col_box '.$custom_class.'">';
                if(isset($layout_params['views']['link_field'])
                    && (array_search ( $field->fid , $layout_params['views']['link_field']) > 0
                    || array_search ( $field->fid , $layout_params['views']['link_field']) === 0)){
                  $modId = ($moduleId) ? '&moduleId=' . $moduleId : '';

                  $cat_id = (isset($category->cid))?'&amp;catid='.$category->cid : '&amp;catid=0';
                  $link = 'index.php?option=com_os_cck&amp;view=instance&amp;eiid[]='
                  . $entityInstance->eiid .'&amp;lid='.$layout_params['views']['instance_layout']
                  . $cat_id . '&amp;Itemid=' . $Itemid . $modId;

                  ob_start();
                    $html .= "<a  href='".JRoute::_($link)."'>";
                    require getSiteShowFiledViewPath('com_os_cck', $field->field_type);
                    $html .= ob_get_contents();
                    $html.="</a>";
                  ob_end_clean();
                }else{
                  $layout_params['instance_currency'] = $entityInstance->instance_currency;
                  ob_start();
                    require getSiteShowFiledViewPath('com_os_cck', $field->field_type);
                    $html .= ob_get_contents();
                  ob_end_clean();
                }


          $html .='</'.$shell_tag.'>';
        }

          
        $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
        $layout_html = str_replace("{|f-".$field->fid."|}", $html, $layout_html);
          
        

      }
      
      if(isset($layout_params['views']['show_request_layout'])){
        foreach ($layout_params['views']['show_request_layout'] as $key => $value) {

          if(isset($layout_params['views']['show_type_request_layout'][$key][0])){
            $show_type = $layout_params['views']['show_type_request_layout'][$key][0];
          }else{
            $show_type = 1;
          }

          $field->db_field_name = "l".$key;
          $field_styling = get_field_styles($field, $layout);
          $custom_class = get_field_custom_class($field, $layout);
          $button_style = array('field_styling'=>$field_styling,'custom_class'=>$custom_class);

          $button_name = isset($layout_params['views']['show_request_layout_button_name'][$key])?$layout_params['views']['show_request_layout_button_name'][$key][0]:'';
          if(strpos($layout_html,"{|l-".$key."|}")){
            $field = new stdClass();
            $field->db_field_name = $key;
            if(isset($layout_params['views']['show_request_layout_name'][$key]) &&
                isset($layout_params['views']['show_request_layout_name'][$key][0]) &&
                $layout_params['views']['show_request_layout_name'][$key][0] == 'on'){
              $layout_html = str_replace($key.'-label-hidden', '', $layout_html);
            }
            $field_styling = get_field_styles($field, $layout);
            $custom_class = get_field_custom_class($field, $layout);
            //if below fn works , that this is add_instance view
            $layout_params['title'] = isset($layout_params['title'])?$layout_params['title']:'';
            ob_start();
            echo '<div class="'.$custom_class.'" '.$field_styling.'>';

            Instance::show_request_layout($option, $key, $entityInstance->eiid, $show_type,$button_name , $layout_params['has_price'],$layout_params['title'], $button_style);
            echo '</div>';  
            $layout_html = str_replace("{|l-".$key."|}", ob_get_contents(), $layout_html);
            ob_end_clean();
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


     

       if(isset($layout_params['fields']['cck_instance_navigation_published']) 
        && $layout_params['fields']['cck_instance_navigation_published']){
        
        $user = JFactory::getUser();
        if(isset($layout_params['fields']['access_cck_instance_navigation'])
              && $layout_params['fields']['access_cck_instance_navigation'] != '1' 
              && !checkAccess_cck($layout_params['fields']['access_cck_instance_navigation'], $user->groups)){
          $layout_html = str_replace("{|f-cck_instance_navigation|}", '', $layout_html);
        }

        $modId = ($moduleId) ? '&moduleId=' . $moduleId : '';
        $catId = isset($category->cid)?'&amp;catid='.$category->cid:'';
        $link = 'index.php?option=com_os_cck&amp;view=instance&amp;lid='.$layout->lid. $catId. '&amp;Itemid=' . $Itemid . $modId;
        $prev = '&amp;eiid[0]='.$layout_params['prevInstId'];
        $next = '&amp;eiid[0]='.$layout_params['nextInstId'];
        $field->db_field_name = 'cck_instance_navigation';
        $field_styling = get_field_styles($field, $layout);

        ob_start();
        ?>
        <div id="os_pagination" >
          <?php
          if($layout_params['prevInstId']){
            ?>
            <div>
              <a <?php echo $field_styling?> href="<?php echo $link.$prev;?>">
                  Prev
              </a>
            </div>
            <?php
          }
          if($layout_params['nextInstId']){
            ?>
            <div>
              <a <?php echo $field_styling?> href="<?php echo $link.$next?>">
                  Next
              </a>
            </div>
            <?php
          } ?>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
          $layout_html = str_replace("{|f-cck_instance_navigation|}", $html, $layout_html);
      }else{
          $layout_html = str_replace("{|f-cck_instance_navigation|}", $html, $layout_html);
      }
      echo $layout_html; 

      ?>
  </div>
</div>
<script type="text/javascript">
  jQuerCCK("*[class*='hide-field-name-'] , .delete-field, .delete-row, .delete-layout").remove();

  
  jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
    jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"));
  });
jQuerCCK("div[class *='cck-row-']").addClass('row');
////jQuerCCK("span.cck-help-string").remove()

jQuerCCK(function () {
  jQuerCCK('[data-toggle="tooltip"]').tooltip()
})

</script>
