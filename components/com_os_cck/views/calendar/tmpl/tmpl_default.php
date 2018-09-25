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

$key = 'key='.$os_cck_configuration->get("google_map_key",'');
$doc->addScript('//maps.google.com/maps/api/js?'.$key);
// $doc->addScript(JUri::root() . 'components/com_os_cck/assets/js/jQuerCCK.raty.js');
// $doc->addScript(JUri::root() . "/components/com_os_cck/assets/js/jQuerCCK-ui-cck-1.10.3.custom.min.js");
// $doc->addStyleSheet(JUri::root() . '/components/com_os_cck/assets/css/jQuerCCK-ui-1.10.3.custom.min.css');
$doc->addScript(JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js");
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css");
$doc->addStyleSheet(JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.css');
$doc->addScript(JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.js');
$doc->addStyleSheet( JUri::root().'/components/com_os_cck/assets/lightbox/css/lightbox.css');
$doc->addScriptDeclaration('jQuerCCK=jQuerCCK.noConflict();');
$doc->addScript(JURI::root() . '/components/com_os_cck/assets/lightbox/js/lightbox-2.6.min.js');
?>

<div class="cck-body">

  <?php
  //layout title
  if(isset($layout_params['views']['show_layout_title']) && $layout_params['views']['show_layout_title']){
    echo "<h3>";
      echo $layout_params['views']['layout_title'];
    echo "</h3>";
  }

  $user = JFactory::getUser();
  $field = new stdClass();

  $field->db_field_name = 'form-'.$layout->lid;

  $form_styling = get_field_styles($field, $layout);
  $form_custom_class = get_field_custom_class($field, $layout);

  ?>  

  <!-- layout wrapper -->

  <div class="<?php echo $form_custom_class; ?> cat_fields" <?php echo $form_styling; ?> >
          
        <?php
        $layout_html = urldecode($layout->layout_html);
        $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
        ?>

      <?php
        
        //calendar styles and custom class
          $field->db_field_name = 'calendar_table';
          $calendarParams['field_styling'] = get_field_styles($field, $layout);
          $calendarParams['field_styling_table_header'] = get_field_styles_table_header($field, $layout);
          $calendarParams['custom_class'] = get_field_custom_class($field, $layout);

        //block month-year var calculated
          if (isset($_POST['month'.$showLayModId]) && isset($_POST['year'.$showLayModId])) {
            $month = $_POST['month'.$showLayModId];
            $year = $_POST['year'.$showLayModId];
            $calendar = getCalendar($month, $year,$calendarParams);
          } else {
            //if not set month
            if($current_month == date('Y-m') || empty($current_month)){
              $month = date("m", mktime(0, 0, 0, date('m'), 1, date('Y')));
              $year = date("Y", mktime(0, 0, 0, date('m'), 1, date('Y')));
            //if set client current month
            }else{
              $parseDate = date_parse($current_month);
              $month = $parseDate['month'];
              $year = $parseDate['year'];
            }
            $calendar = getCalendar($month, $year,$calendarParams);
          }

          if($month != 12){
            $nextMonth = $month+1;
            $nextYear = $year;
          }else{
            $nextMonth = 1;
            $nextYear = $year+1;
          }

          if($month != 1){
            $prevMonth = $month-1;
            $prevYear = $year;
          }else{
            $prevMonth = 12;
            $prevYear = $year-1;
          }

        //bllock month-year var calculated
      ?>

  
      <?php

          if(isset($layout_params['fields']['calendar_table_published']) 
            && $layout_params['fields']['calendar_table_published']){

            if(strpos($layout_html,"{|f-calendar_table|}")){

              $field->db_field_name = 'calendar_table';

              //access check
              if(isset($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name])
                    && $layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name] != '1' 
                    && !checkAccess_cck($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name], $user->groups)){
                $layout_html = str_replace("{|f-".$field->db_field_name."|}", '', $layout_html);
              }

              $field_styling = get_field_styles($field, $layout);
              $custom_class = get_field_custom_class($field, $layout);
              $shell_tag = isset($layout_params['calendar_layout_params']['fields']['label_tag_calendar_table'])?$layout_params['calendar_layout_params']['fields']['label_tag_calendar_table']:'span';

              ob_start();
                echo '<'.$shell_tag . '  class="'.$custom_class.'">';

              ?>
              <div>
                <div class="cck_tableC basictable">
                  <div class="row_01">

                    <?php 
                    if(isset($layout_params['calendar_layout_params']['fields']['months_'.$field->db_field_name])
                      && is_array($layout_params['calendar_layout_params']['fields']['months_'.$field->db_field_name])){

                      $tabArr = $layout_params['calendar_layout_params']['fields']['months_'.$field->db_field_name]; 
                    ?>

                      <?php foreach ($tabArr as $tab):?>
                         
                        <?php  $tab = "tab".$tab; ?>

                        <div <?php echo $field_styling;?> class="col_01"><?php echo $calendar->$tab; ?></div>

                      <?php endforeach;?>
                      
                    <?php
                    }
                    ?>

                  </div>
                </div>
              </div>

              <?php
              echo '</'.$shell_tag.'>';
              $layout_html = str_replace("{|f-calendar_table|}", ob_get_contents(), $layout_html);
              ob_end_clean();
            }
          }else{
            $layout_html = str_replace("{|f-calendar_table|}", '', $layout_html);
          }


         if(isset($layout_params['fields']['calendar_pagination_published']) 
            && $layout_params['fields']['calendar_pagination_published']){

            //calendar pagination render
            if(strpos($layout_html,"{|f-calendar_pagination|}")){

              $field->db_field_name = 'calendar_pagination';

              //access check
              if(isset($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name])
                    && $layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name] != '1' 
                    && !checkAccess_cck($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name], $user->groups)){
                $layout_html = str_replace("{|f-".$field->db_field_name."|}", '', $layout_html);
              }

              $field_styling = get_field_styles($field, $layout);
              $custom_class = get_field_custom_class($field, $layout);
              $shell_tag = isset($layout_params['calendar_layout_params']['fields']['label_tag_calendar_pagination'])?$layout_params['calendar_layout_params']['fields']['label_tag_calendar_pagination']:'span';


              ob_start();
                echo '<'.$shell_tag . ' class="' . $custom_class . '" >';

              ?>
              <div class="row center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 span6" style="text-align: left;">
                  <form action="" method="post" name="calendar" >
                    <input type="hidden" name="month<?php echo $showLayModId?>" value="<?php echo $prevMonth;?>">
                    <input type="hidden" name="year<?php echo $showLayModId?>" value="<?php echo $prevYear;?>" >
                    <input  <?php echo $field_styling;?> type="submit" value="<?php echo JText::_('COM_OS_CCK_PREV_BUTTON')?>" >
                  </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 span6" style="text-align: right;">
                  <form action="" method="post" name="calendar" >
                    <input type="hidden" name="month<?php echo $showLayModId?>" value="<?php echo $nextMonth;?>">
                    <input type="hidden" name="year<?php echo $showLayModId?>" value="<?php echo $nextYear;?>" >
                    <input <?php echo $field_styling;?> type="submit" value="<?php echo JText::_('COM_OS_CCK_NEXT_BUTTON')?>" >
                  </form>
                </div>
              </div>
              <?php

                echo '</'.$shell_tag.'>';
              $layout_html = str_replace("{|f-calendar_pagination|}", ob_get_contents(), $layout_html);
              ob_end_clean();
            }
          }else{
            $layout_html = str_replace("{|f-calendar_pagination|}", '', $layout_html);
          }


          if(isset($layout_params['fields']['calendar_month_year_published']) 
            && $layout_params['fields']['calendar_month_year_published']){

            //calendar month-year
            if(strpos($layout_html,"{|f-calendar_month_year|}")){

              $field->db_field_name = 'calendar_month_year';

              //access check
              if(isset($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name])
                    && $layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name] != '1' 
                    && !checkAccess_cck($layout_params['calendar_layout_params']['fields']['access_'.$field->db_field_name], $user->groups)){
                $layout_html = str_replace("{|f-".$field->db_field_name."|}", '', $layout_html);
              }

              $field_styling = get_field_styles_without_Style($field, $layout);
              $custom_class = get_field_custom_class($field, $layout);
              $shell_tag = isset($layout_params['calendar_layout_params']['fields']['label_tag_calendar_month_year'])?$layout_params['calendar_layout_params']['fields']['label_tag_calendar_pagination']:'span';

              ob_start();
                echo '<'.$shell_tag . '>';

              ?>

              <form action="" method="post" name="calendar" >

              <?php


                if(isset($layout_params['calendar_layout_params']['fields']['showMonth_calendar_month_year'])){
                  $hideMouth = 'display:none'; 
                }else{
                  $hideMouth = '';
                }

                if(isset($layout_params['calendar_layout_params']['fields']['showYear_calendar_month_year'])){
                  $hideYear = 'display:none'; 
                }else{
                  $hideYear = '';
                }

              ?>

                <select style="<?php echo $hideMouth.";".$field_styling?>"  name="month<?php echo $showLayModId?>" class="inputbox <?php echo $custom_class?>" onChange="form.submit()">
                  <option value="1" <?php if ($month == '1') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_JANUARY'); ?>
                  </option>
                  <option value="2" <?php if ($month == '2') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_FEBRUARY'); ?>
                  </option>
                  <option value="3" <?php if ($month == '3') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_MARCH'); ?>
                  </option>
                  <option value="4" <?php if ($month == '4') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_APRIL'); ?>
                  </option>
                  <option value="5" <?php if ($month == '5') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_MAY'); ?>
                  </option>
                  <option value="6" <?php if ($month == '6') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_JUNE'); ?>
                  </option>
                  <option value="7" <?php if ($month == '7') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_JULY'); ?>
                  </option>
                  <option value="8" <?php if ($month == '8') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_AUGUST'); ?>
                  </option>
                  <option value="9" <?php if ($month == '9') echo "selected" ?> >
                   <?php echo JText::_('COM_OS_CCK_SEPTEMBER'); ?>
                  </option>
                  <option value="10" <?php if ($month == '10') echo "selected" ?> >
                   <?php echo JText::_('COM_OS_CCK_OCTOBER'); ?>
                  </option>
                  <option value="11" <?php if ($month == '11') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_NOVEMBER'); ?>
                  </option>
                  <option value="12" <?php if ($month == '12') echo "selected" ?> >
                    <?php echo JText::_('COM_OS_CCK_DECEMBER'); ?>
                  </option>
                </select>              

                <select style="<?php echo $hideYear.';'.$field_styling;?>" name="year<?php echo $showLayModId?>" class="inputbox <?php echo $custom_class?>"  onChange="form.submit()">

                <?php 
                for($i = 1967; $i < 2067; $i++):?>

                <option value="<?php echo $i?>" <?php if ($year == $i) echo "selected" ?> ><?php echo $i?></option>
                  
                <?php endfor;?>  
                </select>

              </form>

              <?php

              echo '</'.$shell_tag.'>';
              $layout_html = str_replace("{|f-calendar_month_year|}", ob_get_contents(), $layout_html);
              ob_end_clean();
            }
          }else{
            $layout_html = str_replace("{|f-calendar_month_year|}", '', $layout_html);
          }


            //modules
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
            //modules

            //custom code
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
            //custom code

            //vars for attach layout
            $layout_params['has_price'] = 0;
            $entityInstance = new stdClass();
            $entityInstance->eiid = $calendarParams['all_instance_lid'];
            //layout
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
            //layout


            echo $layout_html;

    ?>


     <!-- layout wrapper --> 
  </div>

    <script>
      jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
        jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"))
      });

      jQuerCCK("div[class *='cck-row-']").addClass('row');
      jQuerCCK("*[class*='hide-field-name-'] , .delete-field, .delete-row, .delete-layout").remove();
      jQuerCCK(".content-row[class*='cck-row-']").each(function(index, el) {
        if(!jQuerCCK(el).find(".drop-item").length){
          jQuerCCK(el).remove();
        }
      });
      
      //jQuerCCK("span.cck-help-string").remove();
      jQuerCCK(function () {
        jQuerCCK('[data-toggle="tooltip"]').tooltip()
      });
    </script>
  
</div>



    