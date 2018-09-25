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


class AdminViewRequest{

  static function showRequests($option, & $rows_item, & $clist,
                                & $publist, & $search, & $pageNav, & $sort_arr, $show_fields,$entity_list)
  {


      global $doc, $user, $app, $session, $db;
      $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_ADMIN_LABLE_SUBMISSIONS') . "</div>";
      $app = JFactory::getApplication();
      $app->JComponentTitle = $html;
      $onclick = "Joomla.checkAll(this);";
      ?>
      <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist filters">
          <tr>
            <td>
              <div class="search_block">
              <input type="text" placeholder="<?php echo JText::_('COM_OS_CCK_SHOW_SEARCH'); ?>" name="search" value="<?php echo $search; ?>" class="inputbox"
                   onChange="document.adminForm.submit();"/>
              <button type="submit" class="cck_search_button" title="" data-original-title="Search"><span class="icon-search"></span></button>
              </div>
            </td>
            <td>
              <?php echo $publist; ?>
            </td>
            <td>
              <?php echo $clist; ?>
            </td>
            <td>
              <?php echo $entity_list; ?>
            </td>
            <?php if (version_compare(JVERSION, "3.0.0", "ge")) { ?>
              <td>
                <div class="btn-group pull-right hidden-phone">
                  <label for="limit"
                         class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                  <?php echo $pageNav->getLimitBox(); ?>
                </div>
              </td>
            <?php } ?>
          </tr>
        </table>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
          <tr>
            <th width="3%" align="center">
                <input type="checkbox" name="toggle" value="" onClick="<?php echo $onclick; ?>"/>
            </th>
            <th align="center" class="title" width="3%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_INSTANCE_ID'), 'inst_id', $sort_arr,JRequest::getVar('task'));?></th>
            <?php
            foreach($show_fields as $value){
              foreach($value as $field){
                ?>
                <th align="left" class="title" width="15%"
                  nowrap="nowrap"><?php echo HTML_os_cck::sort_head($field->field_name, $field->db_field_name, $sort_arr,JRequest::getVar('task'));?></th>
                <?php
              }
            }
            ?>
            <th align="left" class="title" width="75%"
              nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_REQUEST_DATE'), 'created', $sort_arr,JRequest::getVar('task'));?></th>
          </tr>
          <?php
            $item_session = JFactory::getSession();
            $item_session->set('sorting_direction', $sort_arr['sorting_direction']);
            for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
              $row = & $rows_item[$i];
              ?>
              <tr class="row<?php echo $i % 2; ?> <?php echo $row->notreaded?'not-readed':''?>">
                <td width="3%" align="center">
                  <?php if ($row->checked_out && $row->checked_out != $user->id) { ?>
                    &nbsp;
                  <?php
                  } else {
                    echo JHTML::_('grid.id',$i, $row->eiid, ($row->checked_out && $row->checked_out != $user->id), 'eiid');
                  }
                  ?>
                </td>
                <td align="center"><a href="#show_request_item"
                       onClick="return listItemTask('cb<?php echo $i; ?>','show_request_item')">
                        <?php echo $row->eiid; ?></a></td>
    <!-- **************************************************************** -->
                <?php
                  foreach($show_fields as $key => $value){
                    foreach($value as $field){
                      $html = '';
                      if($row->fk_eid != $key){
                        echo'<td></td>';
                        continue;
                      }
                      if($field->field_type == 'categoryfield'){
                        echo "<td align='left'>$row->category</td>";
                        continue;
                      }
                      ?>
                      <td align="left">
                        <?php
                        $entityInstance = new os_cckEntityInstance($db);
                        $entityInstance->load($row->eiid);
                        $value = $entityInstance->getFieldValue($field);
                        ?>
                        <div style="float:left; margin-right:15px";>
                            <span class="col_box" style="display:block;
                            <?php echo ($field->field_type=='imagefield'
                                        && isset($field->options['width'])
                                        && isset($field->options['height']))? 'width:'.$field->options['width'].'px; height:'.$field->options['height'].'px;':'';?>">
                                <?php
                                    ob_start();
                                      require getSiteShowFiledViewPath('com_os_cck', $field->field_type);
                                      $html .= ob_get_contents();
                                    ob_end_clean();
                                    echo $html;
                                ?>
                            </span>
                        </div>
                      </td>
                      <?php
                    }
                  }
                ?>
                <td align="left"><?php echo $row->created; ?></td>
            </tr>
          <?php
          }//end for
          
          ?>
          <tr>
            <td colspan="13"><?php echo $pageNav->getListFooter(); ?></td>
          </tr>
        </table>
        <input type="hidden" name="option" value="<?php echo $option; ?>"/>
        <input type="hidden" name="task" value="show_requests"/>
        <input type="hidden" name="boxchecked" value="0"/>
      </form>

  <?php
  }

  static function showRequestItem($option, & $entityInstance, & $str_list){


    global $os_cck_configuration,$user, $app, $session, $doc;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_ADMIN_LABLE_SUBMISSIONS') . "</div>";
    $app->JComponentTitle = $html;
    $doc->addStyleSheet(JURI::root()."components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
    $doc->addStyleSheet(JURI::root()."components/com_os_cck/assets/css/admin_style.css");
    
    $doc->addScript(JURI::root()."components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js","text/javascript",true);
    $doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");
    $doc->addScript(JUri::root().'components/com_os_cck/assets/js/jquery.raty.js');
    $doc->addStyleSheet( JUri::root().'/components/com_os_cck/assets/lightbox/css/lightbox.css');
    $doc->addScriptDeclaration('jQuerCCK=jQuerCCK.noConflict();');
    $doc->addScript(JUri::root().'/components/com_os_cck/assets/lightbox/js/lightbox-2.6.min.js');
    $doc->addScript('http://maps.google.com/maps/api/js');

    if(empty($str_list['parent_instance'])){
      $div_id = 'instance';
    }else{
      $div_id = 'psubmission';
    } ?>
    <div id="<?php echo $div_id; ?>">
      <?php
      $layout_params = $str_list['layout_params'];
      $layout = $str_list['layout'];
      $bootstrap_version = $session->get( 'bootstrap','2');
      $layout->layout_html = $layout->getLayoutHtml($bootstrap_version);
      $layout_html = urldecode($layout->layout_html);
      $field_from_params = $layout_params["fields"];
      $fields_list = $str_list['extra_fields_list'];

      //add child selects to layout
      $addChildSelectToLayout = addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html);
      $layout_html = $addChildSelectToLayout['layout_html'];
      $layout_params = $addChildSelectToLayout['layout_params'];
      $parent = $addChildSelectToLayout['select_parent'];
      $layout->params = serialize($layout_params);
      //add child selects to layout
        
      
      for ($i = 0, $nnn = count($str_list['extra_fields_list']); $i < $nnn; $i++) {
        $html='';


        $field = $str_list['extra_fields_list'][$i];

        $field_styling = get_field_styles($field, $layout);
        $layout_params['field_styling'] = $field_styling;
        $custom_class = isset($layout_params['fields'][$field->db_field_name.'_custom_class'])?$layout_params['fields'][$field->db_field_name.'_custom_class']:'';
        //get tag
        $shell_tag = isset($layout_params['fields']['field_tag_'.$field->db_field_name])?$layout_params['fields']['field_tag_'.$field->db_field_name]:'div';
        //end



        if(strpos($layout_html,"{|f-cck_mail|}")){
          $layout_html = str_replace("{|f-cck_mail|}",'', $layout_html);
          //continue;
        }

        $layout_html = str_ireplace(array('Button Send','MAIL'),'', $layout_html);
        
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
          if ($field->field_type == 'locationfield' && !$value[0]->{$field->db_field_name . "_address"}) {
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
            //$custom_field['custom_code_field_'.$cust_key.'_custom_code'] = $plug_row->text;
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
      $layout_html = str_replace(array("{|f-cck_send_button|}","Button Send"), '', $layout_html);
      echo $layout_html;
      ?>
    </div>
    <?php
    if(!empty($str_list['parent_instance'])){
      AdminRequest::showRequestItem($option, $str_list['parent_instance']);
    }
  }

}
