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



if (!function_exists('getInstanceCount')) {
  // mask like {|decimal_textfield_31|}
  function getInstanceCountInCategory($cid = ''){
      global $db;

      $section = "com_os_cck";
      $query = "SELECT  COUNT(c_con.id) AS inst_count "
        . "\n FROM #__os_cck_categories AS c"
        . "\n LEFT JOIN #__os_cck_categories_connect AS c_con ON c_con.fk_cid=c.cid"
        . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
        . "\n LEFT JOIN #__os_cck_entity_instance AS ent ON ent.eiid = c_con.fk_eiid"
        . "\n WHERE c.section='$section'  AND c.cid ='".$cid."'  AND ent.published = '1'"
        . "\n AND c.published = 1"
        . "\n GROUP BY c.cid"
        . "\n ORDER BY section, parent_id, ordering";

      $db->setQuery($query);
      $count = $db->loadResult();
  
      return $count;
  }
}



if (!function_exists('addChildSelectToLayout')) {

  function addChildSelectToLayout($fields_list, $entityInstance, $layout_params, $layout_html){
        //skip child selects
    $params = new JRegistry;
    $childs_list = [];
    foreach ($fields_list as $value) {
      $params->loadString($value->params);
      $childs_list = array_merge($childs_list, explode('|',$params->get('child_select')));
    }
    $childs_list = array_unique($childs_list);

    //find child fids
    $childList = array();
    foreach ($fields_list as $key => $field){
      if(in_array($field->fid, $childs_list)) continue;
      if($field->field_type == 'text_select_list'){
        $childList[$field->fid] = recurseSelectTree($field->fid, $entityInstance, false);
      }
    }

    //get assoc fields_list
    $assoc_field_list = array();
    foreach ($fields_list as $key => $field){
      $assoc_field_list[$field->fid] = $field;
    }

    $parent = '';
    //add child placeholder in layout
    foreach ($childList as $parent => $childs) {
      if(count($parent) > 0){
        $childs_string = "";
        if(!is_array($childs)) continue;

        foreach ($childs as $child) {

          $layout_params['fields']['Params_text_select_list_'.$child] = $layout_params['fields']['Params_text_select_list_'.$parent];
          $layout_params['fields']['text_select_list_'.$child.'_published'] = $layout_params['fields']['text_select_list_'.$parent.'_published'];
          $layout_params['fields']['showName_text_select_list_'.$child] = $layout_params['fields']['showName_text_select_list_'.$parent];
          $layout_params['fields']['text_select_list_'.$child.'_alias'] = $layout_params['fields']['text_select_list_'.$parent.'_alias'];
          $layout_params['fields']['text_select_list_'.$child.'_tooltip'] = $layout_params['fields']['text_select_list_'.$parent.'_tooltip'];
          $layout_params['fields']['text_select_list_'.$child.'_prefix'] = $layout_params['fields']['text_select_list_'.$parent.'_prefix'];
          $layout_params['fields']['text_select_list_'.$child.'_suffix'] = $layout_params['fields']['text_select_list_'.$parent.'_suffix'];
          $layout_params['fields']['access_text_select_list_'.$child] = $layout_params['fields']['access_text_select_list_'.$parent];


          $start_wrap = '<div class="drop-item" data-parent="'.$parent.'" data-content=""><span class="f-inform-button"></span><!-- clear back/front title --><span class="field-name" data-field-name="'.$assoc_field_list[$child]->db_field_name.'" data-field-type="text_select_list" style="font-weight:normal;">'.$assoc_field_list[$child]->field_name.'</span><span class="col_box admin_aria_hidden">';

          $end_wrap = '</span><input class="f-params" name="fi_Params_'.$assoc_field_list[$child]->db_field_name.'" type="hidden" value="'.$layout_params['fields']['Params_text_select_list_'.$parent].'"></div>';

          $childs_string .= $start_wrap."{|f-".$child."|}".$end_wrap;


        }
        $layout_html = str_ireplace('<i class="f-parent" fid="'.$parent.'" style="display: none;">{|f-parent-'.$parent.'|}</i>', $childs_string, $layout_html);
      }
    }
    return ['layout_html' => $layout_html,
            'layout_params' => $layout_params,
            'select_parent' => $parent];
  }
}


if (!function_exists('noticeLinkToDocLayout')) {
  // mask like {|decimal_textfield_31|}
  function noticeLinkToDocLayout($db_lay_name = '', $lay_title = '', $doc_link = '#'){ ?>
      <div class="alert-layout">
        <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <strong>Notice!</strong> For more information on how to use the '<?php echo $lay_title ?>', refer to the  
          <b><a target="blank" href="<?php echo $doc_link; ?>">documentation.</a></b><br><span class="btn btn-primary btn-xs not-show-alert" data-dismiss="alert" >Do not show more?</span>
        </div>
      </div>

      <script type="text/javascript">
        var cookie = getCookie('<?php echo $db_lay_name ?>');

        if(cookie != undefined && cookie == 'on'){
          jQuerCCK('.alert-layout').hide();
        }

        function getCookie(name) {
          var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
          ));
          return matches ? decodeURIComponent(matches[1]) : undefined;
        }

        jQuerCCK(document).ready(function(){
          jQuerCCK('.not-show-alert').click(function(){
            document.cookie = "<?php echo $db_lay_name; ?>=on;  path='/'; expires=''; ";
          })
        })
      </script>
  <?php
  }
}


if (!function_exists('replaceMaskCustomCodePHP')) {
  // mask like {|decimal_textfield_31|}
  function replaceMaskCustomCodePHP($instance,$custom_code_str = ''){
      if(!$instance || $custom_code_str == '') return '';
      $fields = [];

      foreach ($instance->getFields() as  $field) {
        $var_name = "cust_field_".$field->fid;
        $fields[$var_name] = $$var_name = $instance->getFieldValue($field);
        $custom_code_str = str_replace('{|'.$field->db_field_name.'|}', '$'.$var_name, $custom_code_str);
      }

      $result = ['custom_code_str' => $custom_code_str, 'variables_arr'=>$fields];
      return $result;
  }
}


if (!function_exists('recurseSelectTree')) {
  function recurseSelectTree($fid, $instance, $array = array()){
      //check parent select and add chield to list

      $fieldObject = $instance->getField($fid);

      $fValue = $instance->getSelectFieldValue($fid);
      $fValue = (isset($fValue) && $fValue) ? $fValue : '';

      if($fValue == '') return $array;

      $fParams = new JRegistry;
      $fParams = $fParams->loadString($fieldObject->params);
      $fChild = explode('|',$fParams->get('child_select',''));
      $fValues = explode('\sprt',$fParams->get('allowed_value',''));

      $fChild = array_combine($fValues, $fChild);

      foreach ($fValues as $value) {
        $value_array = explode('|',$value);
        if($fValue == $value_array[0]) $fValue = $value;
      }
 
      if(isset($fChild[$fValue]) && $fChild[$fValue] != 0){
        $array[] = $fChild[$fValue];
        return recurseSelectTree($fChild[$fValue], $instance, $array);
      }else{
        return $array;
      }

  }
}

if (!function_exists('getMonth')) {
  function getMonth($month){
      switch ($month) {
        case 1:
            $smonth = JText::_('COM_OS_CCK_JANUARY');
            break;
        case 2:
            $smonth = JText::_('COM_OS_CCK_FEBRUARY');
            break;
        case 3:
            $smonth = JText::_('COM_OS_CCK_MARCH');
            break;
        case 4:
            $smonth = JText::_('COM_OS_CCK_APRIL');
            break;
        case 5:
            $smonth = JText::_('COM_OS_CCK_MAY');
            break;
        case 6:
            $smonth = JText::_('COM_OS_CCK_JUNE');
            break;
        case 7:
            $smonth = JText::_('COM_OS_CCK_JULY');
            break;
        case 8:
            $smonth = JText::_('COM_OS_CCK_AUGUST');
            break;
        case 9:
            $smonth = JText::_('COM_OS_CCK_SEPTEMBER');
            break;
        case 10:
            $smonth = JText::_('COM_OS_CCK_OCTOBER');
            break;
        case 11:
            $smonth = JText::_('COM_OS_CCK_NOVEMBER');
            break;
        case 12:
            $smonth = JText::_('COM_OS_CCK_DECEMBER');
            break;
    }

    return $smonth;

  }
}

if (!function_exists('getEventCCK')) {
  function getEventCCK($calenDate,$date){
    foreach ($calenDate as $value) {
      if($value[1] == $date) return array('date'=>$date,'count'=>$value[0]);
    }
    return false;
  }
}

if (!function_exists('getMonthSchedule')) {
  function getMonthSchedule($month, $year, $calendarParams){
    global $database, $realestatemanager_configuration;

      $database = JFactory::getDbo();
      $skip = date("w", mktime(0, 0, 0, $month, 1, $year)) - 1;
      if ($skip < 0){
        $skip = 6;
      }
      $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
      $calendar = '';
      $day = 1;
      $smonth = getMonth($month);

      $calendar = "<div class='schedule_main'>";
      $calendar .= "<div class='schedule_month_year'>$smonth $year</div>";

      //isset events in months
      $compare_date = date('Y-m',strtotime($year."-".$month));
      $isset_date_array = array();
      foreach ($calendarParams['item_infos'] as $key => $value){
        if(!in_array($key,$isset_date_array)){
          $isset_date_array[] = date('Y-m',strtotime($key));
        }
      } 

      if(!in_array($compare_date, $isset_date_array)){
        $calendar .= "<div class='schedule_not_event'>". JText::_('COM_OS_CCK_NO_EVENT') ."</div>";
        $calendar .= "</div>";

        return $calendar;
      }
      //isset events in months

      for ($i = 0; $i < 6; $i++) {
        // $calendar .= '<tr>';
        for ($j = 0; $j < 7; $j++) {
          if (($skip > 0) or ($day > $daysInMonth)){
            // $calendar .= '<td> &nbsp; </td>';
            $skip--;
          }else{ 
            
            $date = strtotime($day.'-'.$month.'-'.$year);
            $date = date('Y-m-d',$date);
            $dateEvent = getEventCCK($calendarParams['calenDate'],$date);

            if(strtotime($date) < strtotime('-1 day') && !$calendarParams['show_past_events']){
              $dateEvent = false;
            }

            if(is_array($dateEvent)){

                $events_string = '';
                if(isset($calendarParams['item_infos'][$dateEvent['date']]) 
                  && is_array($calendarParams['item_infos'][$dateEvent['date']])){
                  $item_infos = $calendarParams['item_infos'][$dateEvent['date']];
                }

                $eiids = array();
                foreach ($item_infos as $date => $item_info) {
                  $eiids[$date] = $item_info['eiid'];
                }

                $instances = array();
                foreach ($calendarParams['instancies'] as $key => $instance) {
                  if(in_array($instance->eiid, $eiids)) $instances[] = $instance;
                }

                $items = array();
                foreach ($instances as $key => $instance) {

                  $items[] = $instance;

                  ob_start();

                    echo "<div class='shedule_event'>";

                    Category::show_attached_layout($calendarParams['option'], $calendarParams['schedule_instance_lid'] ,$calendarParams['layout_fk_eid'], $calendarParams['layout_params'], '', '', 0, $items, '');  

                    echo "</div>";
                  $events_string  .= ob_get_contents();
                  ob_end_clean();

                  $items = array();
                }

                if(isset($calendarParams['calendar_view']) && $calendarParams['calendar_view'] == 'small'){
                  $events_string = '';
                }

                $calendar .= "<div class='schedule_item row row-fluid ".$calendarParams['custom_class']."'>";

                //show/hide header date
                if($calendarParams['show_header'] == 1){
                  $calendar .= "<div ".$calendarParams['field_styling_table_header']." class='schedule_date col-lg-2 span2'>";
                    $calendar .= 
                    "<a href='".JRoute::_("index.php?option=com_os_cck&view=all_instance&lid=".$calendarParams['all_instance_lid']."&event_date=".$dateEvent['date']."&event_date_field=".$calendarParams['event_date_field'].
                      "&Itemid=".$calendarParams['Itemid'])."'>".
                      date($calendarParams['header_format'], strtotime($year."-".$month."-".$day))
                    .'</a>';
                  $calendar .= "</div>";
                }

                  $calendar .= "<div class='schedule_event_list col-lg-10 span10'>";
                    $calendar .= $events_string;
                  $calendar .= "</div>";

                $calendar .= "</div>";
            }
            $day++;
          }
        }
        // $calendar .= '</tr>';
      }

      $calendar .= "</div>";
     
      return $calendar;
  }
}

if (!function_exists('getMonthCal')) {
  function getMonthCal($month, $year, $calendarParams){
    global $database, $realestatemanager_configuration;
      
      $database = JFactory::getDbo();
      $skip = date("w", mktime(0, 0, 0, $month, 1, $year)) - 1;
      if ($skip < 0){
        $skip = 6;
      }
      $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));      
    /*******************************get only rent days*****************************/  

      $calendar = '';
      $day = 1;
      $smonth = getMonth($month);
      $calendar = '<div class="table-responsive"><table '.$calendarParams['field_styling'] .' class="cck_tableC table '.$calendarParams['custom_class'].'">
      <tr class="year">
        <th '.$calendarParams['field_styling_table_header'].' colspan = "7">' . $smonth . ' ' . $year . '</th>
      </tr>
      <tr class="days">
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_MON') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_TUE') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_WED') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_THU') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_FRI') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_SAT') . '</th>
        <th '.$calendarParams['field_styling_table_header'].'>' . JText::_('COM_OS_CCK_SUN') . '</th>
      </tr>'
      ;
      for ($i = 0; $i < 6; $i++) {
        $calendar .= '<tr>';
        for ($j = 0; $j < 7; $j++) {
          if (($skip > 0) or ($day > $daysInMonth)){
            $calendar .= '<td> &nbsp; </td>';
            $skip--;
          }else{ 
            
            $date = strtotime($day.'-'.$month.'-'.$year);
            $date = date('Y-m-d',$date);
            $dateEvent = getEventCCK($calendarParams['calenDate'],$date);

            if(strtotime($date) < strtotime('-1 day') && !$calendarParams['show_past_events']){
              $dateEvent = false;
            }

            if(is_array($dateEvent)){

                $events_string = '';

                if(isset($calendarParams['item_infos'][$dateEvent['date']]) 
                  && is_array($calendarParams['item_infos'][$dateEvent['date']])){
                  $item_infos = $calendarParams['item_infos'][$dateEvent['date']];
                }

                foreach ($item_infos as $key => $item_info) {

                  if($calendarParams['show_time'] != 1){
                    $item_info['time'] = '';
                  }

                   $events_string .=  "<div class='event_string'><a href='".JRoute::_("index.php?option=com_os_cck&view=instance&lid=".$calendarParams['instance_lid']."&eiid[0]=".$item_info['eiid'].
                    "&Itemid=".$calendarParams['Itemid'])."'>".$item_info['time'].' '.$item_info['title']."</a></div>";
                }

                if(isset($calendarParams['calendar_view']) && $calendarParams['calendar_view'] == 'small'){
                  $events_string = '';
                }

             
                $calendar .= "
                <td class='dateTrue marker_event_date'>
                  <div class='whole_cell'>

                    <div class='event_date'>
                      <a href='".JRoute::_("index.php?option=com_os_cck&view=all_instance&lid=".$calendarParams['all_instance_lid'].
                        "&event_date=".$dateEvent['date'].
                        "&event_date_field=".$calendarParams['event_date_field'].
                        "&Itemid=".$calendarParams['Itemid'])."'>
                        <span class='showDay'>$day</span>
                        <!-- <span class='showCount'>{$dateEvent['count']}</span> --!>
                      </a>
                    </div>
                    <!-- collection event in div tags --!>
                    $events_string
                  
                  </div>
                </td>";
            }else{
              $calendar .= "<td class='dateTrue' ><div class='whole_cell'>$day</div></td>";
            }
            
            $day++;
          }
        }
        $calendar .= '</tr>';
      }
      $calendar .= '</table></div>';
     
      return $calendar;
  }
}

if (!function_exists('getCalendar')) {
  function getCalendar($month, $year, $calendarParams){
    $month = (int) $month;
    $year = (int) $year;

    if ($month == 1){
        $month1 = 12;
        $year1 = $year - 1;
    }else{
        $month1 = $month - 1;
        $year1 = $year;
    }

    if ($month == 12){
      $month2 = 1;
      $month3 = 2;
      $month4 = 3;
      $month5 = 4;
      $month6 = 5;
      $month7 = 6;
      $month8 = 7;
      $month9 = 8;
      $month10 = 9;
      $month11 =10;
      $month12 = 11;
      $month13 = 12;
      $year2 = $year3 = $year4 = $year5 
      = $year5 = $year6 = $year7 = $year8
      = $year9 = $year10 = $year11 = $year12 = $year13  = $year + 1;
    }else{
      $month2 = $month + 1;
      $month3 = $month + 2;
      $month4 = $month + 3;
      $month5 = $month + 4;
      $month6 = $month + 5;
      $month7 = $month + 6;
      $month8 = $month + 7;
      $month9 = $month + 8;
      $month10 = $month + 9;
      $month11 = $month + 10;
      $month12 = $month + 11;
      $month13 = $month + 12;
      $year2 =$year3 = $year4 =$year5
      = $year6 =$year7 = $year8 =$year9
      = $year10 =$year11 = $year12 =$year13 = $year;
    }

    if($month3 > 12){
      $month3 = $month3 - 12;
      $year3 = $year + 1;
    }

    if($month4 > 12){
      $month4 = $month4 - 12;
      $year4 = $year + 1;
    }

    if($month5 > 12){
      $month5 = $month5 - 12;
      $year5 = $year + 1;
    }

    if($month6 > 12){
      $month6 = $month6 - 12;
      $year6 = $year + 1;
    }

    if($month7 > 12){
      $month7 = $month7 - 12;
      $year7 = $year + 1;
    }

    if($month8 > 12){
      $month8 = $month8 - 12;
      $year8 = $year + 1;
    }

    if($month9 > 12){
      $month9 = $month9 - 12;
      $year9 = $year + 1;
    }

    if($month10 > 12){
      $month10 = $month10 - 12;
      $year10 = $year + 1;
    }

    if($month11 > 12){
      $month11 = $month11 - 12;
      $year11 = $year + 1;
    }

    if($month12 > 12){
      $month12 = $month12 - 12;
      $year12 = $year + 1;
    }

    if($month13 > 12){
      $month13 = $month13 - 12;
      $year13 = $year + 1;
    }

    $function_name = ($calendarParams['calendar_view'] == 'schedule')?'getMonthSchedule':'getMonthCal';

    $calendar = new stdClass();
    $calendar->tab1 = $function_name($month1, $year1, $calendarParams);
    $calendar->tab2 = $function_name($month, $year, $calendarParams);
    $calendar->tab3 = $function_name($month2, $year2, $calendarParams);
    $calendar->tab4 = $function_name($month3, $year3, $calendarParams);
    $calendar->tab5 = $function_name($month4, $year4, $calendarParams);
    $calendar->tab6 = $function_name($month5, $year5, $calendarParams);
    $calendar->tab7 = $function_name($month6, $year6, $calendarParams);
    $calendar->tab8 = $function_name($month7, $year7, $calendarParams);
    $calendar->tab9 = $function_name($month8, $year8, $calendarParams);
    $calendar->tab10 = $function_name($month9, $year9, $calendarParams);
    $calendar->tab11 = $function_name($month10, $year10, $calendarParams);
    $calendar->tab12 = $function_name($month11, $year11, $calendarParams);
    $calendar->tab13 = $function_name($month12, $year12, $calendarParams);
    $calendar->tab14 = $function_name($month13, $year13, $calendarParams);

    return $calendar;
  }
}

if(!function_exists('protectInjection')){
    function createSettingsOptions($fields, $default = false){
      $options = array();
      if($default) $options[] = JHTML::_('select.option','-1','none');

      foreach($fields as $value) {
          $options[] = JHTML::_('select.option',$value['value'],$value['text']);
      }
      return $options;
    }
}


if(!function_exists('protectInjection')){
    function protectInjection($element, $def = '', $filter = "STRING",$bypass_get=false){
        global $db;

        if(!$bypass_get){
                $value = JFactory::getApplication()->input->get($element, $def, $filter);
                // $value = $element;
             }else{
                $value = $element;
             }

        if(empty($value)) return $value; 


        if(is_array($value)){
            $start_array = $value;
        }else {
            $hash_string_start = md5($value);
        } 

        $value = str_ireplace(array("/*","*/","select", "insert", "update", "drop", "delete", "alter"), "", $value);

        if(is_array($value)){
            $end_array = $value;
        }else {
            $hash_string_end = md5($value);
        } 

        if((!is_array($value) && $hash_string_start != $hash_string_end)
            ||
            is_array($value) && count(array_diff($start_array, $end_array)))
        {
            return protectInjectionWithoutQuote($value, $def , $filter ,true);
        }

        return $db->quote($value);
    }

}

if(!function_exists('protectInjectionWithoutQuote')){
    function protectInjectionWithoutQuote($element, $def = '', $filter = "STRING",$bypass_get=false){
global $db;
        if(!$bypass_get){
                $value = JFactory::getApplication()->input->get($element, $def, $filter);
                // $value = $element;
             }else{
                $value = $element;
             }

        if(empty($value)) return $value; 

        if(is_array($value)){
            $start_array = $value;
        }else {
            $hash_string_start = md5($value);
        } 

        $value = str_ireplace(array("/*","*/","select", "insert", "update", "drop", "delete", "alter"), "", $value);

        if(is_array($value)){
            $end_array = $value;
        }else {
            $hash_string_end = md5($value);
        } 

        if((!is_array($value) && $hash_string_start != $hash_string_end)
            ||
            is_array($value) && count(array_diff($start_array, $end_array)))
        {
            return protectInjectionWithoutQuote($value, $def , $filter ,true);
        }


        if(is_array($value)) return $value;

        return $db->escape($value);
    }

}
if(!function_exists('prepere_field_for_show')){
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
}

if(!function_exists('frontend_cck_sort_head')){
  function frontend_cck_sort_head($title, $fieldname, $sort_arr,$task=''){
    global $Itemid;
    $Itemid = ($Itemid == 0 || empty($Itemid))?intval($_REQUEST['Itemid']) : $Itemid;
    $img_str = "";
    if($task)
      $task = "&amp;task=$task";
    $direction=$sort_arr['sorting_direction'];
    if ($sort_arr['sorting_direction'] == 'ASC') {
      $img_path = JURI::root().'components/com_os_cck/images/arrow_up.png';
      $img_str = "<img src=\"$img_path\" width=\"12\" height=\"12\" border=\"0\" alt='Sorted up' />";
    } else {
      $img_path = JURI::root().'components/com_os_cck/images/arrow_down.png';
      $img_str = "<img src=\"$img_path\" width=\"12\" height=\"12\" border=\"0\" alt='Sorted up' />";
    }
    $str = "<a href='".JURI::root().
    "index.php?option=com_os_cck$task&amp;sort=$fieldname&amp;sorting_direction=$direction&amp;Itemid=$Itemid"."'>".
    $title .
    $img_str .
    "</a>";
    return $str;
  }
}

if (!function_exists('get_field_styles')) {
  function get_field_styles($field, $layout){
    $layout_params = unserialize($layout->params);
    $params = new JRegistry;
    if($field->db_field_name == 'form-'.$layout->lid){
      $params = $params->loadString($layout_params['form_params']);
    }else{

      if(isset($layout_params['fields']['Params_'.$field->db_field_name])){
        $params = $params->loadString($layout_params['fields']['Params_'.$field->db_field_name]);
      }
    }

    //styling
    //margin
    if($params->get("margin")){
      $marginPx = 'px';
    }else{
      $marginPx = '%';
    }


    if($params->get('marginTop','') == '0'){
      $margin_top = '0';
    }else{
      $margin_top = ($params->get('marginTop',''))? $params->get('marginTop').$marginPx : '';
    }

    if($params->get('marginRight','') == '0'){
      $margin_right = '0';
    }else{
      $margin_right = ($params->get('marginRight',''))? $params->get('marginRight').$marginPx : '';
    }

    if($params->get('marginBottom','') == '0'){
      $margin_bottom = '0';
    }else{
      $margin_bottom = ($params->get('marginBottom',''))? $params->get('marginBottom').$marginPx : '';
    }

    if($params->get('marginLeft','') == '0'){
      $margin_left = '0';
    }else{
      $margin_left = ($params->get('marginLeft',''))? $params->get('marginLeft').$marginPx : '';
    }


    // $margin_top = ($params->get('marginTop',''))? $params->get('marginTop').$marginPx : '';
    // $margin_right = ($params->get('marginRight',''))? $params->get('marginRight').$marginPx : '';
    // $margin_bottom = ($params->get('marginBottom',''))? $params->get('marginBottom').$marginPx : '';
    // $margin_left = ($params->get('marginLeft',''))? $params->get('marginLeft').$marginPx : '';

    //padding
    if($params->get("padding")){
      $paddingPx = 'px';
    }else{
      $paddingPx = '%';
    }

    if($params->get('paddingTop','') == '0'){
      $padding_top = '0';
    }else{
      $padding_top = ($params->get('paddingTop',''))? $params->get('paddingTop').$paddingPx : '';
    }

    if($params->get('paddingRight','') == '0'){
      $padding_right = '0';
    }else{
      $padding_right = ($params->get('paddingRight',''))? $params->get('paddingRight').$paddingPx : '';
    }

    if($params->get('paddingBottom','') == '0'){
      $padding_bottom = '0';
    }else{
      $padding_bottom = ($params->get('paddingBottom',''))? $params->get('paddingBottom').$paddingPx : '';
    }

    if($params->get('paddingLeft','') == '0'){
      $padding_left = '0';
    }else{
      $padding_left = ($params->get('paddingLeft',''))? $params->get('paddingLeft').$paddingPx : '';
    }



    // $padding_top = ($params->get('paddingTop',''))? $params->get('paddingTop').$paddingPx : '';
    // $padding_right = ($params->get('paddingRight',''))? $params->get('paddingRight').$paddingPx : '';
    // $padding_bottom = ($params->get('paddingBottom',''))? $params->get('paddingBottom').$paddingPx : '';
    // $padding_left = ($params->get('paddingLeft',''))? $params->get('paddingLeft').$paddingPx : '';

    //background-color
    $background_color = ($params->get('backgroundColor',''))? $params->get('backgroundColor') : '';
    //border
    $border = ($params->get('borderSize',''))? $params->get('borderSize').'px' : '';
    //border-color
    $border_color = ($params->get('borderColor',''))? $params->get('borderColor') : '';
    //custom class
    $custom_class = ($params->get('customClass',''))? $params->get('customClass') : '';
    //end styling
    //font_size
    $font_size = ($params->get('fontSize',''))? $params->get('fontSize') : '';
    //font_weight
    $font_weight = $params->get('fontWeight', 1);

    //font_color
    $font_color = ($params->get('fontColor',''))? $params->get('fontColor') : '';

    $field_style = 'style="';
    //margin

    if($margin_top || $margin_top == 0)
      $field_style .= 'margin-top:'.$margin_top.';';
    if($margin_right || $margin_right == 0)
      $field_style .= 'margin-right:'.$margin_right.';';
    if($margin_bottom || $margin_bottom == 0)
      $field_style .= 'margin-bottom:'.$margin_bottom.';';
    if($margin_left || $margin_left == 0)
      $field_style .= 'margin-left:'.$margin_left.';';
    //padding
    if($padding_top || $padding_top == 0)
      $field_style .= 'padding-top:'.$padding_top.';';
    if($padding_right || $padding_right == 0)
      $field_style .= 'padding-right:'.$padding_right.';';
    if($padding_bottom || $padding_bottom == 0)
      $field_style .= 'padding-bottom:'.$padding_bottom.';';
    if($padding_left || $padding_left == 0)
      $field_style .= 'padding-left:'.$padding_left.';';
    //background-color
    if($background_color)
      $field_style .= 'background-color:'.$background_color.';';
    //border
    if($border)
      $field_style .= 'border:'.$border.' solid;';
    //border-color
    if($border_color)
      $field_style .= 'border-color:'.$border_color.';';
    //border-color
    if($font_size){
      $field_style .= 'font-size:'.$font_size.'px;';
      $field_style .= 'line-height:'.$font_size.'px;';
    }
    //border-color

    if($font_weight)
      $field_style .= 'font-weight:normal;';
    else
      $field_style .= 'font-weight:bold;';
    //border-color
    if($font_color)
      $field_style .= 'color:'.$font_color.';';
    $field_style .= '"';

    return $field_style;
  }
}

if (!function_exists('get_field_styles_table_header')) {
  function get_field_styles_table_header($field, $layout){
    $layout_params = unserialize($layout->params);
    $params = new JRegistry;
    if($field->db_field_name == 'form-'.$layout->lid){
      $params = $params->loadString($layout_params['form_params']);
    }else{

      if(isset($layout_params['fields']['Params_'.$field->db_field_name])){
        $params = $params->loadString($layout_params['fields']['Params_'.$field->db_field_name]);
      }
    }

    //styling
    //background-color
    $background_color = ($params->get('backgroundTableHeaderColor',''))? $params->get('backgroundTableHeaderColor') : '';
    //font_color
    $font_color = ($params->get('fontTableHeaderColor',''))? $params->get('fontTableHeaderColor') : '';
    $field_style = 'style="';

    //background-color
    if($background_color)
      $field_style .= 'background-color:'.$background_color.';';
    
    if($font_color)
      $field_style .= 'color:'.$font_color.';';
    $field_style .= '"';

    return $field_style;
  }
}


if (!function_exists('get_field_styles_without_Style')) {
  function get_field_styles_without_Style($field, $layout){
    $layout_params = unserialize($layout->params);
    $params = new JRegistry;
    if($field->db_field_name == 'form-'.$layout->lid){
      $params = $params->loadString($layout_params['form_params']);
    }else{
      if(isset($layout_params['fields']['Params_'.$field->db_field_name])){
        $params = $params->loadString($layout_params['fields']['Params_'.$field->db_field_name]);
      }
    }

    //styling
    //margin
    if($params->get("margin")){
      $marginPx = 'px';
    }else{
      $marginPx = '%';
    }

    if($params->get('marginTop','') == '0'){
      $margin_top = '0';
    }else{
      $margin_top = ($params->get('marginTop',''))? $params->get('marginTop').$marginPx : '';
    }

    if($params->get('marginRight','') == '0'){
      $margin_right = '0';
    }else{
      $margin_right = ($params->get('marginRight',''))? $params->get('marginRight').$marginPx : '';
    }

    if($params->get('marginBottom','') == '0'){
      $margin_bottom = '0';
    }else{
      $margin_bottom = ($params->get('marginBottom',''))? $params->get('marginBottom').$marginPx : '';
    }

    if($params->get('marginLeft','') == '0'){
      $margin_left = '0';
    }else{
      $margin_left = ($params->get('marginLeft',''))? $params->get('marginLeft').$marginPx : '';
    }
    // $margin_top = ($params->get('marginTop',''))? $params->get('marginTop').$marginPx : '';
    // $margin_right = ($params->get('marginRight',''))? $params->get('marginRight').$marginPx : '';
    // $margin_bottom = ($params->get('marginBottom',''))? $params->get('marginBottom').$marginPx : '';
    // $margin_left = ($params->get('marginLeft',''))? $params->get('marginLeft').$marginPx : '';
    //padding
    if($params->get("padding")){
      $paddingPx = 'px';
    }else{
      $paddingPx = '%';
    }

    if($params->get('paddingTop','') == '0'){
      $padding_top = '0';
    }else{
      $padding_top = ($params->get('paddingTop',''))? $params->get('paddingTop').$paddingPx : '';
    }

    if($params->get('paddingRight','') == '0'){
      $padding_right = '0';
    }else{
      $padding_right = ($params->get('paddingRight',''))? $params->get('paddingRight').$paddingPx : '';
    }

    if($params->get('paddingBottom','') == '0'){
      $padding_bottom = '0';
    }else{
      $padding_bottom = ($params->get('paddingBottom',''))? $params->get('paddingBottom').$paddingPx : '';
    }

    if($params->get('paddingLeft','') == '0'){
      $padding_left = '0';
    }else{
      $padding_left = ($params->get('paddingLeft',''))? $params->get('paddingLeft').$paddingPx : '';
    }
    // $padding_top = ($params->get('paddingTop',''))? $params->get('paddingTop').$paddingPx : '';
    // $padding_right = ($params->get('paddingRight',''))? $params->get('paddingRight').$paddingPx : '';
    // $padding_bottom = ($params->get('paddingBottom',''))? $params->get('paddingBottom').$paddingPx : '';
    // $padding_left = ($params->get('paddingLeft',''))? $params->get('paddingLeft').$paddingPx : '';
    //background-color
    $background_color = ($params->get('backgroundColor',''))? $params->get('backgroundColor') : '';
    //border
    $border = ($params->get('borderSize',''))? $params->get('borderSize').'px' : '';
    //border-color
    $border_color = ($params->get('borderColor',''))? $params->get('borderColor') : '';
    //custom class
    $custom_class = ($params->get('customClass',''))? $params->get('customClass') : '';
    //end styling
    //font_size
    $font_size = ($params->get('fontSize',''))? $params->get('fontSize') : '';
    //font_weight
    $font_weight = $params->get('fontWeight', 1);

    //font_color
    $font_color = ($params->get('fontColor',''))? $params->get('fontColor') : '';

    $field_style = '';
    //margin

    if($margin_top || $margin_top == 0)
      $field_style .= 'margin-top:'.$margin_top.';';
    if($margin_right || $margin_right == 0)
      $field_style .= 'margin-right:'.$margin_right.';';
    if($margin_bottom || $margin_bottom == 0)
      $field_style .= 'margin-bottom:'.$margin_bottom.';';
    if($margin_left || $margin_left == 0)
      $field_style .= 'margin-left:'.$margin_left.';';
    //padding
    if($padding_top || $padding_top == 0)
      $field_style .= 'padding-top:'.$padding_top.';';
    if($padding_right || $padding_right == 0)
      $field_style .= 'padding-right:'.$padding_right.';';
    if($padding_bottom || $padding_bottom == 0)
      $field_style .= 'padding-bottom:'.$padding_bottom.';';
    if($padding_left || $padding_left == 0)
      $field_style .= 'padding-left:'.$padding_left.';';
    //background-color
    if($background_color)
      $field_style .= 'background-color:'.$background_color.';';
    //border
    if($border)
      $field_style .= 'border:'.$border.' solid;';
    //border-color
    if($border_color)
      $field_style .= 'border-color:'.$border_color.';';
    //border-color
    if($font_size){
      $field_style .= 'font-size:'.$font_size.'px;';
      $field_style .= 'line-height:'.$font_size.'px;';
    }
    //border-color

    if($font_weight)
      $field_style .= 'font-weight:normal;';
    else
      $field_style .= 'font-weight:bold;';
    //border-color
    if($font_color)
      $field_style .= 'color:'.$font_color.';';
    // $field_style .= '"';

    return $field_style;
  }
}


if (!function_exists('get_field_custom_class')) {
  function get_field_custom_class($field, $layout){
    $layout_params = unserialize($layout->params);
    $params = new JRegistry;
    if($field->db_field_name == 'form-'.$layout->lid){
      $params = $params->loadString($layout_params['form_params']);
    }else{
      if(isset($layout_params['fields']['Params_'.$field->db_field_name])){
        $params = $params->loadString($layout_params['fields']['Params_'.$field->db_field_name]);
      }
    }

    $custom_class = ($params->get('customClass',''))? $params->get('customClass') : '';

    return $custom_class;
  }
}


if (!function_exists('showFieldType')) {
      function showFieldType($field_type){

          $field_types['datetime_popup'] = 'Date';
          $field_types['filefield'] = 'File Field';
          $field_types['videofield'] = 'Video Field';
          $field_types['audiofield'] = 'Audio Field';
          $field_types['categoryfield'] = 'Category';
          $field_types['imagefield'] = 'Image Field';
          $field_types['text_radio_buttons'] = 'Radio buttons';
          $field_types['text_select_list'] = 'Select list';
          $field_types['text_single_checkbox_onoff'] = 'Checkbox';
          $field_types['text_textarea'] = 'Text Area';
          $field_types['text_textfield'] = 'Text';
          $field_types['text_url'] = 'Url';
          $field_types['galleryfield'] = 'Gallery Field';
          $field_types['locationfield'] = 'Location map';
          $field_types['decimal_textfield'] = 'Number Field';
          $field_types['captcha_field'] = 'Captcha Field';
          $field_types['rating_field'] = 'Rating Field';

          return $field_types[$field_type];
      }
  }


if(!function_exists('available_times_cck')){
  function available_times_cck($eiid, $rent_to = false, $step_time = 0){
   global $db, $os_cck_configuration;

    $query = "SELECT rent_from, rent_until
              FROM #__os_cck_rent WHERE fk_eiid='".$eiid."'
              AND rent_return is null";
    $db->setQuery($query);
    $calenDate = $db->loadObjectList();

    $minutes_for_day = array();

    foreach($calenDate as $calenDate){
      $not_av_from_begin = new DateTime($calenDate->rent_from);
      $not_av_until_end = new DateTime($calenDate->rent_until);
      
      //if until date ,add one step
      if($rent_to && $step_time != 0){

          if($not_av_from_begin->format("H:i:s") != '00:00:00'){
            $not_av_from_begin->modify('+'.$step_time.' minutes');
          }
          $not_av_until_end->modify('+'.$step_time.' minutes');
      }
  
        //range time in one day
        if($not_av_from_begin->format("Y-m-d") == $not_av_until_end->format("Y-m-d")){
          if(!isset($minutes_for_day[$not_av_from_begin->format("Y-m-d")])){
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")] = array();
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")][] = array($not_av_from_begin->format("H:i:s"),
                                                                           $not_av_until_end->format("H:i:s"));
          }else{
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")][] = array($not_av_from_begin->format("H:i:s"),
                                                                           $not_av_until_end->format("H:i:s"));
          }
        }

        //time to end or from start day
        if(strtotime($not_av_from_begin->format("Y-m-d")) < strtotime($not_av_until_end->format("Y-m-d"))){
          if(!isset($minutes_for_day[$not_av_from_begin->format("Y-m-d")])){
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")] = array();
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")][] = array($not_av_from_begin->format("H:i:s"),
                                                                           '23:59:59');
          }else{
            $minutes_for_day[$not_av_from_begin->format("Y-m-d")][] = array($not_av_from_begin->format("H:i:s"),
                                                                           '23:59:59');
          }

          if(!isset($minutes_for_day[$not_av_until_end->format("Y-m-d")])){
            $minutes_for_day[$not_av_until_end->format("Y-m-d")] = array();
            $minutes_for_day[$not_av_until_end->format("Y-m-d")][] = array('00:00:00',
                                                                           $not_av_until_end->format("H:i:s"));
          }else{
            $minutes_for_day[$not_av_until_end->format("Y-m-d")][] = array('00:00:00',
                                                                           $not_av_until_end->format("H:i:s"));
          }
        }

      }

      // print_r($minutes_for_day);
      // exit;

      return $minutes_for_day;
  }
}

if(!function_exists('available_dates_cck')){
  function available_dates_cck($eiid, $step_time = 0){
   global $db, $os_cck_configuration;
    $date_NA=[];
    $query = "SELECT rent_from, rent_until
              FROM #__os_cck_rent WHERE fk_eiid='".$eiid."'
              AND rent_return is null";
    $db->setQuery($query);
    $calenDate = $db->loadObjectList();


    $minutes_sum = array();

    foreach($calenDate as $calenDate){
      $not_av_from = $calenDate->rent_from;
      $not_av_until = $calenDate->rent_until;
      $not_av_from_begin = new DateTime($not_av_from);
      $not_av_until_end = new DateTime($not_av_until);
      
      if($os_cck_configuration->get('rent_type') && $os_cck_configuration->get('by_time') == 0){
        $not_av_until_end = $not_av_until_end->modify( '+1 day' );
      }

      $interval = new DateInterval('P1D');
      $daterange = new DatePeriod($not_av_from_begin, $interval, $not_av_until_end);

      foreach($daterange as $datess){
        $date_NA[] = $datess->format("Y-m-d");
        $date_NA[] = $datess->format("d-m-Y");
      }

      if($os_cck_configuration->get('by_time')){

          //range time in one day
          if($not_av_from_begin->format("Y-m-d") == $not_av_until_end->format("Y-m-d")){

            $time_interval = date_diff($not_av_from_begin,$not_av_until_end);
            $minutes = ($time_interval->h*60)+$time_interval->i;

            if(!isset($minutes_sum[$not_av_from_begin->format("Y-m-d")])){
              $minutes_sum[$not_av_from_begin->format("Y-m-d")] = $minutes;
            }else{
              $minutes_sum[$not_av_from_begin->format("Y-m-d")] += $minutes;
            }
          }

          //time to end or from start day
          if(strtotime($not_av_from_begin->format("Y-m-d")) < strtotime($not_av_until_end->format("Y-m-d"))){
            $end_rest_min =  new DateTime(date("Y-m-d 23:59:59",strtotime($not_av_from)));
            $start_rest_min =  new DateTime(date("Y-m-d 00:00:00",strtotime($not_av_until)));

            $time_interval_end = date_diff($not_av_from_begin,$end_rest_min);
            $minutes_end = ($time_interval_end->h*60)+$time_interval_end->i;

            $time_interval_start = date_diff($start_rest_min,$not_av_until_end);
            $minutes_start = ($time_interval_start->h*60)+$time_interval_start->i;

            if(!isset($minutes_sum[$not_av_from_begin->format("Y-m-d")])){
              $minutes_sum[$not_av_from_begin->format("Y-m-d")] = $minutes_end;
            }else{
              $minutes_sum[$not_av_from_begin->format("Y-m-d")] += $minutes_end;
            }

            if(!isset($minutes_sum[$not_av_until_end->format("Y-m-d")])){
              $minutes_sum[$not_av_until_end->format("Y-m-d")] = $minutes_start;
            }else{
              $minutes_sum[$not_av_until_end->format("Y-m-d")] += $minutes_start;
            }
          }

          // if($date_NA != ''){
          //   if(isset($date_NA[array_search(date("Y-m-d",strtotime($calenDate->rent_from)), $date_NA)])){
          //     unset($date_NA[array_search(date("Y-m-d",strtotime($calenDate->rent_from)), $date_NA)]);
          //     unset($date_NA[array_search(date("Y-m-d",strtotime($calenDate->rent_until)), $date_NA)]);
          //   };
          // }

      }

    }

      //if isset rent
    if($os_cck_configuration->get('by_time')){
      if(count($minutes_sum) > 0){
        foreach ($minutes_sum as $date => $mins) {
          //if whole day is busy - disable it
          if($mins > (1440 - $step_time)){
            $date_NA[] = $date;
          }
        }
      }
    }


      return $date_NA;
  }
}


// if(!function_exists('first_available_time')){
//   function first_available_time($minutes_for_day, $step_time = false){
//    global $db, $os_cck_configuration;

//     $start_time_list = array(); 

//     foreach ($minutes_for_day as $date => $time) {
          
//       if($time[0][0] != '00:00:00'){
//         $start_time = '00:00:00';
//       }else{

//         $start_time = '';

//           foreach ($time as $range) {
//             if($start_time == ''){
//               $start_time = $range[1];
//               continue;
//             }

//             if($range[0] == $start_time){
//               $start_time = $range[1];
//               continue;
//             }else{
//               break;
//             }

//           }
//       }


//       if($step_time){
//         $start_time_list[$date.'_replace_from'] = $start_time;
//         $start_time = date('H:i:s',strtotime($start_time)+((int)$step_time*60));
//         $start_time_list[$date.'_replace_to'] = date('H:i:s',strtotime($start_time)+((int)$step_time*60));
//       }
      
//       $start_time_list[$date] = $start_time;

//     }

//     return $start_time_list;

//   }
// }



if(!function_exists('recursive_array_replace')){
  function recursive_array_replace($find, $replace, $array) {
      if (!is_array($array)) {
          return str_replace($find, $replace, $array);
      }
      $newArray = array();
      foreach ($array as $key => $value) {
          $newArray[$key] = recursive_array_replace($find, $replace, $value);
      }
      return $newArray;
  }
}

// if(!function_exists('availabel_dates_cck')){
//   function availabel_dates_cck($eiid){
//    global $db;
//     $date_NA='';
//     $query = "SELECT rent_from, rent_until
//               FROM #__os_cck_rent WHERE fk_eiid='".$eiid."'
//               AND rent_return is null";
//     $db->setQuery($query);
//     $calenDate = $db->loadObjectList();
//      foreach($calenDate as $calenDate){
//       $not_av_from = $calenDate->rent_from;
//       $not_av_until = $calenDate->rent_until;
//       $not_av_from_begin = new DateTime( $not_av_from);
//       $not_av_until_end = new DateTime( $not_av_until);
//       $not_av_until_end = $not_av_until_end->modify( '+1 day' );
//       $interval = new DateInterval('P1D');
//       $daterange = new DatePeriod($not_av_from_begin, $interval, $not_av_until_end);
//         foreach($daterange as $datess){
//           $date_NA[] = $datess->format("Y-m-d ");
//           $date_NA[] = $datess->format("d-m-Y");
//         }
//       }
//     return $date_NA;
//   }
// }

if(!function_exists('createImage')){
  function createImage($imgSrc, $imgDest, $width, $height, $crop = true, $quality = 100) {
    if (JFile::exists($imgDest)) {
      $info = getimagesize($imgDest, $imageinfo);
      if (($info[0] == $width) && ($info[1] == $height)) {
        return;
      }
    }
    if (JFile::exists($imgSrc)) {
      $info = getimagesize($imgSrc, $imageinfo);
      $sWidth = $info[0];
      $sHeight = $info[1];
      resize_img($imgSrc, $imgDest, $width, $height, $crop, $quality);
    }
  }
}

/**
 * Compiles a list of records
 * @param database - A database connector object
 * select categories
 */
if(!function_exists('show_image_cck')){
  function show_image_cck($value, $width, $height){
    global $os_cck_configuration;
    //разделение имени файла на имя и расширение
    $file_path = JPATH_ROOT . $value;
    $file_inf = pathinfo($file_path);
    $file_dir = $file_inf['dirname'] . '/';
    $file_type = '.' . $file_inf['extension'];
    $file_name = $file_inf['filename'];;
    // Setting the resize parameters
    list($width_main, $height_main) = getimagesize($file_path);
    $imgCretionType = ($os_cck_configuration->get('crop_image'))? '_crop' : '_resize' ;
    $size = "_" . $width . "_" . $height;
    if (file_exists($file_dir . $file_name . $size  . $imgCretionType . $file_type)) {
        return str_replace(JPATH_ROOT, '', $file_dir . $file_name . $size  . $imgCretionType . $file_type);
    } else {

      if($height != 0){
        if ($width_main < $height_main) {
        if ($height_main > $height) {
            $k = $height_main / $height;
        } else if ($width_main > $width) {
            $k = $width_main / $width;
        } else $k = 1;
        
      }else{

        if ($width_main > $width) {
            $k = $width_main / $width;
        } else if ($height_main > $height) {
            $k = $height_main / $height;
        } else $k = 1;

      }
    }
     if($height == 0) $k = 1;

      $w_ = $width_main / $k;
      $h_ = $height_main / $k;
            
    }

    if($os_cck_configuration->get('crop_image','0')){
        $CreateNewImage = createImage($file_path, $file_dir . $file_name . $size  . $imgCretionType . $file_type
                                      , $width, $height);
        return str_replace(JPATH_ROOT, '', $file_dir . $file_name . $size  . $imgCretionType . $file_type);
    }
    // Creating the Canvas
    $tn = imagecreatetruecolor($w_, $h_);

      if ($file_type == '.png') {
        imagealphablending($tn, false);
        imagesavealpha($tn,true);
        $transparent = imagecolorallocatealpha($tn, 255, 255, 255, 127);
        imagefilledrectangle($tn, 0, 0, $w_, $h_, $transparent);
      }
      
    //проверка типа
    switch (strtolower($file_type)) {
        case '.png':
            //echo 'type of your image is PNG';
            $source = imagecreatefrompng($file_dir . $file_name . $file_type);
            $file = imagecopyresized($tn, $source, 0, 0, 0, 0, $w_, $h_, $width_main, $height_main);
            imagepng($tn, $file_dir . $file_name . $size  . $imgCretionType . $file_type);
            break;
        case '.jpg':
            //echo 'type of your image is JPG';
            $source = imagecreatefromjpeg($file_dir . $file_name . $file_type);
            $file = imagecopyresized($tn, $source, 0, 0, 0, 0, $w_, $h_, $width_main, $height_main);
            imagejpeg($tn, $file_dir . $file_name . $size  . $imgCretionType . $file_type);

            break;
        case '.jpeg':
            //echo 'type of your image is JPEG';
            $source = imagecreatefromjpeg($file_dir . $file_name . $file_type);
            $file = imagecopyresized($tn, $source, 0, 0, 0, 0, $w_, $h_, $width_main, $height_main);
            imagejpeg($tn, $file_dir . $file_name . $size  . $imgCretionType . $file_type);

            break;
        case '.gif':
            //echo 'type of your image is GIF';
            $source = imagecreatefromgif($file_dir . $file_name . $file_type);
            $file = imagecopyresized($tn, $source, 0, 0, 0, 0, $w_, $h_, $width_main, $height_main);
            imagegif($tn, $file_dir . $file_name . $size  . $imgCretionType . $file_type);
            break;
        default:
            echo 'not support';
            return;
    }
    return str_replace(JPATH_ROOT, '', $file_dir . $file_name . $size  . $imgCretionType . $file_type);
  }
}

if(!function_exists('resize_img')){
  function resize_img($imgSrc, $imgDest, $tmp_width, $tmp_height, $crop = true, $quality = 100) {
      $info = getimagesize($imgSrc, $imageinfo);
      $sWidth = $info[0];
      $sHeight = $info[1];
      $quality = 80;

      if ($sHeight / $sWidth > $tmp_height / $tmp_width) {
          $width = $sWidth;
          $height = round(($tmp_height * $sWidth) / $tmp_width);
          $sx = 0;
          $sy = round(($sHeight - $height) / 3);
      }
      else {
          $height = $sHeight;
          $width = round(($sHeight * $tmp_width) / $tmp_height);
          $sx = round(($sWidth - $width) / 2);
          $sy = 0;
      }

      if (!$crop) {
          $sx = 0;
          $sy = 0;
          $width = $sWidth;
          $height = $sHeight;
      }

      $ext = str_replace('image/', '', $info['mime']);
      $imageCreateFunc = getImageCreateFunction($ext);
      $imageSaveFunc = getImageSaveFunction($ext);

      $sImage = $imageCreateFunc($imgSrc);
      $dImage = imagecreatetruecolor($tmp_width, $tmp_height);

      // Make transparent
      if ($ext == 'png') {
          imagealphablending($dImage, false);
          imagesavealpha($dImage,true);
          $transparent = imagecolorallocatealpha($dImage, 255, 255, 255, 127);
          imagefilledrectangle($dImage, 0, 0, $tmp_width, $tmp_height, $transparent);
      }

      imagecopyresampled($dImage, $sImage, 0, 0, $sx, $sy, $tmp_width, $tmp_height, $width, $height);

      // print_r($dImage.'------');
      // print_r($imgDest.'------');
      // print_r($quality.'------');
      // exit;

      if ($ext == 'png') {
          $imageSaveFunc($dImage, $imgDest,  (($quality)/10)-1);
      }
      else if ($ext == 'gif') {
          $imageSaveFunc($dImage, $imgDest, $quality);
      }
      else {
          $imageSaveFunc($dImage, $imgDest, $quality);
      }
  }
}
if(!function_exists('getImageCreateFunction')){
  function getImageCreateFunction($type) {
      switch ($type) {
          case 'jpeg':
          case 'jpg':
              $imageCreateFunc = 'imagecreatefromjpeg';
              break;

          case 'png':
              $imageCreateFunc = 'imagecreatefrompng';
              break;

          case 'bmp':
              $imageCreateFunc = 'imagecreatefrombmp';
              break;

          case 'gif':
              $imageCreateFunc = 'imagecreatefromgif';
              break;

          case 'vnd.wap.wbmp':
              $imageCreateFunc = 'imagecreatefromwbmp';
              break;

          case 'xbm':
              $imageCreateFunc = 'imagecreatefromxbm';
              break;

          default:
              $imageCreateFunc = 'imagecreatefromjpeg';
      }

      return $imageCreateFunc;
  }
}
if(!function_exists('getImageSaveFunction')){
  function getImageSaveFunction($type) {
      switch ($type) {
          case 'jpeg':
              $imageSaveFunc = 'imagejpeg';
              break;

          case 'png':
              $imageSaveFunc = 'imagepng';
              break;

          case 'bmp':
              $imageSaveFunc = 'imagebmp';
              break;

          case 'gif':
              $imageSaveFunc = 'imagegif';
              break;

          case 'vnd.wap.wbmp':
              $imageSaveFunc = 'imagewbmp';
              break;

          case 'xbm':
              $imageSaveFunc = 'imagexbm';
              break;

          default:
              $imageSaveFunc = 'imagejpeg';
      }

      return $imageSaveFunc;
  }
}

/**
* Legacy function, use {@link JArrayHelper::getValue()} instead
*
* @deprecated  As of version 1.5
*/
if (!function_exists('mosGetParam')) {
  function mosGetParam(&$arr, $name, $def = null, $mask = 0)
  {
      // Static input filters for specific settings
      static $noHtmlFilter = null;
      static $safeHtmlFilter = null;

      $var = JArrayHelper::getValue($arr, $name, $def, '');

      // If the no trim flag is not set, trim the variable
      if (!($mask & 1) && is_string($var)) {
          $var = trim($var);
      }

      // Now we handle input filtering
      if ($mask & 2) {
          // If the allow html flag is set, apply a safe html filter to the variable
          if (is_null($safeHtmlFilter)) {
              $safeHtmlFilter =  JFilterInput::getInstance(null, null, 1, 1);
          }
          $var = $safeHtmlFilter->clean($var, 'none');
      } elseif ($mask & 4) {
          // If the allow raw flag is set, do not modify the variable
          $var = $var;
      } else {
          // Since no allow flags were set, we will apply the most strict filter to the variable
          if (is_null($noHtmlFilter)) {
              $noHtmlFilter =  JFilterInput::getInstance( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
          }
          $var = $noHtmlFilter->clean($var, 'none');
      }
      return $var;
  }
}

/**
* Legacy function, use {@link JEditor::save()} or {@link JEditor::getContent()} instead
*
* @deprecated  As of version 1.5
*/
if (!function_exists('getEditorContents')) {
  function getEditorContents($editorArea, $hiddenField)
  {
      jimport('joomla.html.editor');
      $editor = JFactory::getEditor();
      echo $editor->save($hiddenField);
  }
}

/**
* Legacy function, use {@link JFilterOutput::objectHTMLSafe()} instead
*
* @deprecated  As of version 1.5
*/
if (!function_exists('mosMakeHtmlSafe')) {
  function mosMakeHtmlSafe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '')
  {
      JFilterOutput::objectHTMLSafe($mixed, $quote_style, $exclude_keys);
  }
}


/**
* Legacy function to replaces &amp; with & for xhtml compliance
*
* @deprecated  As of version 1.5
*/
if (!function_exists('mosTreeRecurse')) {
  function mosTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
  {
      jimport('joomla.html.html');
      return JHTML::_('menu.treerecurse', $id, $indent, $list, $children, $maxlevel, $level, $type);
  }
}


if (!function_exists('os_cckTreeRecurse')) {
  function os_cckTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
  {

      if (@$children[$id] && $level <= $maxlevel) {
          $parent_id = $id;
          foreach ($children[$id] as $v) {
              $id = $v->cid;

              if ($type) {
                  $pre = " "; //'<sup>|_</sup>_';
                  $spacer = '. -- ';
              } else {
                  $pre = "- ";
                  $spacer = ' . -';
              }

              if ($v->parent == 0) {
                  $txt = $v->name;
              } else {
                  $txt = $pre . $v->name;
              }
              $pt = $v->parent;
              $list[$id] = $v;
              $list[$id]->name = "$indent$txt";
              $list[$id]->children = count(@$children[$id]);
              $list[$id]->all_fields_in_list = count(@$children[$parent_id]);

              $list = os_cckTreeRecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
          }
      }
      return $list;
  }
}

if (!function_exists('show_search_field')) {
  function show_search_field($field, $value,$params){
    $showSearchOptions = array();
    $fld_name_show = (isset($params['showName_'.$field->db_field_name])) ? 'checked="true"' : "";
    //search params
    if($field->field_type == 'decimal_textfield'){
      $showSearchOptions[] = JHTML::_('select.option','1','Show search by range');
      $showSearchOptions[] = JHTML::_('select.option','2','Show search by value');
      $showSearchOptions[] = JHTML::_('select.option','3','Search by value');
      $showSearchOptions[] = JHTML::_('select.option','0','Hide');
    }elseif($field->field_type == 'text_single_checkbox_onoff'){
      $showSearchOptions[] = JHTML::_('select.option','1','Show');
      $showSearchOptions[] = JHTML::_('select.option','0','Hide');
    }else{
      $showSearchOptions[] = JHTML::_('select.option','1','Show');
      $showSearchOptions[] = JHTML::_('select.option','2','Search');
      $showSearchOptions[] = JHTML::_('select.option','0','Hide');
    }
    if ($field->published != "1") return "";

    $ftype = $field->field_type;
    $global_settings = unserialize($field->global_settings);
    $db_columns = unserialize($field->db_columns);
    if (array_key_exists('value', $db_columns)) $db_columns = $db_columns['value'];
    $sufix = '';
    //search in text field
    if ($ftype == 'decimal_textfield' || $ftype == 'datetime_popup' || $ftype == 'text_radio_buttons' || $ftype == 'text_textfield' || $ftype == 'text_textarea' || $ftype == 'text_url' || $ftype == 'text_single_checkbox_onoff' || $ftype == 'text_select_list') {
      if ($field->multiple != 1) $sufix = '[' . $multiple . ']';
      $selectedFieldTitle = (isset($params['search_params']['cck_search_'.$field->db_field_name]['type'])) ? $params['search_params']['cck_search_'.$field->field_name]['type'] : '';
      $list = '<div><label>'.JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME").'</label> <input type="checkbox" data-field-name="'.$field->field_name.'" name="fi_showName_'. $field->field_name . '" ' . $fld_name_show . ' ></div> ';
      $list .= JHTML::_('select.genericlist',$showSearchOptions, 'os_cck_search_'.$field->db_field_name.'[type]',
                                            'size="1" class="inputbox" ', 'value', 'text',$selectedFieldTitle);
      $list .='<input type="hidden" name="os_cck_search_'.$field->db_field_name.'[fid]" value="'.$field->fid.'">';
      return $list;
    }
  }
}

if (!function_exists('data_transform_cck')){
  function data_transform_cck($date, $format) {
    global $db;
    if (strstr($date, "00:00:00") OR strlen($date) < 11) {
      $formatForDateFormat = 'Y-m-d';
    } else {
      $formatForDateFormat = 'Y-m-d H:i:s';
    }
    $formatForCreateObjDate = str_replace("%","",$format);
    if(function_exists('date_format')){
      $dateObject = date_create_from_format($formatForCreateObjDate, $date);
      if($dateObject){
        $date = date_format($dateObject, $formatForDateFormat);
      }else{
        $dateObject = date_create_from_format($formatForDateFormat, $date);
        if($dateObject){
          $date = date_format($dateObject, $formatForDateFormat);
        }
      }
    }else{
      $query = "SELECT STR_TO_DATE('$date','$format')";
      $db->setQuery($query);
      $normaDat = $db->loadResult();
      if(strlen($normaDat) > 0){
        $date = $normaDat;
      }
    }
    return $date;
  }
}

if (!function_exists('date_to_data_ms')){
  function date_to_data_ms($data_string){             // 2014-01-25 covetr to date in ms
    global $db;
    if($data_string){
      $rent_mas = explode('-', $data_string);
      $month=$rent_mas[1];
      $day=$rent_mas[2];
      $year=$rent_mas[0];
      $rent_ms = mktime ( 0 ,0, 0, $month , $day , $year);
      return $rent_ms;
    }else{
      exit;
    }
  }
}
if(!function_exists('cck_getCurrency')){
  function cck_getCurrency($params){
    $currency_string = $params->get("paypal_currency");
    $currency_arr = explode(';', $currency_string);
    $currency_list = array();
    foreach ($currency_arr as $currency) {
      $l = explode("=", $currency);
      if(count($l) == 2){
        $signStr = $l[0];
        $signAlias = $l[0];
        $sign = explode("|", $signStr);
        if(count($sign) == 2){
          $signStr = $sign[0];
          $signAlias = $sign[1];
        }
        $coefficient = $l[1];
        $currency_list[] = array("sign"=>$signStr,"signAlias"=>$signAlias,"coefficient"=>$coefficient);
      }
    }

    return $currency_list;
  }
}

if(!function_exists('calculatePriceCCK')){
  function calculatePriceCCK ($eiid,$ceid,$rent_from,$rent_until,$lid = 0){
    global $db, $session, $app;
    $os_cck_config = JComponentHelper::getParams('com_os_cck');
    $entityInstance = new os_cckEntityInstance($db);
    $entityInstance->load(intval($eiid));
    $layout = new os_cckLayout($db);
    $layout->load($entityInstance->fk_lid);
    $layout_params = unserialize($layout->params);
    $layout_fields = $layout_params['fields'];
    $fields = $entityInstance->getFields();
    $price = 0;


    foreach ($fields as $field) {
      if($field->field_type == 'decimal_textfield'){
        if(isset($layout_fields[$field->db_field_name.'_price_field']) && $layout_fields[$field->db_field_name.'_price_field']){
          $value = $entityInstance->getFieldValue($field);
          $price += $value[0]->data;
        }
      }
    }

    $entity = new os_cckEntity($db);
    $entity->load(intval($ceid));
    $lid = $lid?$lid:$app->input->get("current_lid");
    $layout->load($lid);
    $layout_params = unserialize($layout->params);

    $layout_fields = $layout_params['fields'];
    $bootstrap_version = $session->get( 'bootstrap','2');
    $layout_html = $layout->getLayoutHtml($bootstrap_version);
    $fields = $entity->getFieldList($layout_html);

    

    foreach ($fields as $field) {
      if($field->field_type == 'datetime_popup'){

        if($layout_fields[$field->db_field_name.'_field_type'] == 'rent_from')
        {
          $rent_from_format = $layout_fields[$field->db_field_name.'_input_format'];
        }

        if($layout_fields[$field->db_field_name.'_field_type'] == 'rent_to')
        {
          $rent_until_format = $layout_fields[$field->db_field_name.'_input_format'];
        }

      }
    }


    if($os_cck_config->get('by_time') == 1){
        
      $rent_from = strtotime($rent_from);
      $rent_until = strtotime($rent_until);

      if($rent_from > $rent_until){
        $returnArr["price"] = 0;
        $returnArr["currency"] = $entityInstance->instance_currency;
        return $returnArr;
      }

      $price_per_time = isset($layout_params['views']['price_per_period']) ? $layout_params['views']['price_per_period'] : 60;

      $diff = ($rent_until - $rent_from)/60;
      $count_period = $diff/$price_per_time;

      $sum_price = round($count_period * $price);
      $returnArr["price"] = $sum_price;
      $returnArr["currency"] = $entityInstance->instance_currency;

      return $returnArr;

    }


    $rent_from = data_transform_cck($rent_from, $rent_from_format);
    $rent_until = data_transform_cck($rent_until, $rent_until_format);
    if($rent_from >$rent_until){
      $returnArr["price"] = 0;
      $returnArr["currency"] = $entityInstance->instance_currency;
      return $returnArr;
    }
    $rent_from_ms = date_to_data_ms($rent_from);
    $rent_to_ms = date_to_data_ms($rent_until);
    //by day
    if($os_cck_config->get("rent_type",0) && $os_cck_config->get('by_time') == 0){
      $rent_to_ms = $rent_to_ms + (60*60*24);
    }
    $count_day = (($rent_to_ms - $rent_from_ms)/60/60/24);
    $sum_price = $count_day * $price;
    $returnArr["price"] = $sum_price;
    $returnArr["currency"] = $entityInstance->instance_currency;
    return $returnArr;
  }
}


  if(!function_exists('styling_options')) {
    function styling_options($layout, $type, $element = ''){

      $input_style = 'Input Style';
      $label_style = 'Label Style';
      // $button_style = 'Button style';
      if($type == 'form' || $type == 'col' || $type == 'row' || $element == 'block'){
        $input_style = '';
      }
      ?>
      <div id="styling-<?php echo $type?>" class="row">
        <?php
        if($type != 'l'){


          if($input_style){?>
            <h2><?php echo $input_style ?></h2>
            <?php
          }?>

          <!-- <h2 class="style-for-block" ><?php echo $button_style;?></h2> -->
          <div class="style-for-block">
          <div class="cck-margin">

          <div class="table_header">
              <h2>Table header</h2>
              <div>
                <span class="colum-1">
                  <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BACKGROUND_COLOR")?></label>
                </span>
                <span class="colum-2">
                  <input class="background-colorpicker background-table-header-color" type="text" value="" data-opacity="1.00">
                </span>
              </div>

              <div>
                <span class="colum-1">
                  <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_COLOR")?></label>
                </span>
                <span class="colum-2">
                  <input class="font-colorpicker font-table-header-color" type="text" value="" data-opacity="1.00">
                </span>
              </div>
            <h2>Table body</h2>
          </div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_MARGIN")?></label>
            </span>

            <span class="colum-2 option">
              <div class="optionCckl">
                <input id="<?php echo $type?>-margin_px_1" name="<?php echo $type?>-margin_px" class="pixels margin-pixels"  type="radio" value="px" checked="checked">
                <label class="checken" for="<?php echo $type?>-margin_px_1"></label>
              </div>
              <div class="optionCckr">
                <input id="<?php echo $type?>-margin_px_2" name="<?php echo $type?>-margin_px" class="pixels margin-pixels" type="radio" value="%">
                <label class="checken" for="<?php echo $type?>-margin_px_2"></label>
              </div>
            </span>

            <span class="colum-3">
              <span class="marg">
                <label>top</label>
                <input class="styling_number margin-top" type="number" value="">
              </span>
              <span class="marg">
                <label>right</label>
                <input class="styling_number margin-right" type="number" value="">
                </span>
                <span class="marg">
                <label>bottom</label>
                <input class="styling_number margin-bottom" type="number" value="">
                </span>
                <span class="marg">
                <label>left</label>
                <input class="styling_number margin-left" type="number" value="">
              </span>
            </span>
          </div>
          <div class="cck-padding">
            <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_PADDING")?></label>
            </span>
            <span class="colum-2 option">
                <div class="optionCckl">
                  <input id="<?php echo $type?>-padding_px_1" name="<?php echo $type?>-padding_px" class="pixels padding-pixels" type="radio" value="px" checked="checked">
                  <label class="checken" for="<?php echo $type?>-padding_px_1"></label>
              </div>
              <div class="optionCckr">
                  <input id="<?php echo $type?>-padding_px_2" name="<?php echo $type?>-padding_px" class="pixels padding-pixels" type="radio" value="%">
                  <label class="checken" for="<?php echo $type?>-padding_px_2"></label>
              </div>
            </span>
            <span class="colum-3">
              <span class="marg">
                <label>top</label>
                <input class="styling_number padding-top" type="number" value="">
                </span>
                <span class="marg">
                <label>right</label>
                <input class="styling_number padding-right" type="number" value="">
                </span>
                <span class="marg">
                <label>bottom</label>
                <input class="styling_number padding-bottom" type="number" value="">
                </span>
                <span class="marg">
                <label>left</label>
                <input class="styling_number padding-left" type="number" value="">
                </span>
            </span>
          </div>
              <div>
                <span class="colum-1">
                  <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BACKGROUND_COLOR")?></label>
                </span>
                <span class="colum-2">
                  <input class="background-colorpicker background-color" type="text" value="" data-opacity="1.00">
                </span>
              </div>
            <div>
              <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BORDER")?></label>
              </span>
              <span class="colum-2">
                <input class="styling_number border-size" type="number" value="">
              </span>
            </div>
            <div>
              <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BORDER_COLOR")?></label>
              </span>
              <span class="colum-2">
                <input class="border-colorpicker border-color" type="text" value="" data-opacity="1.00">
              </span>
            </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_SIZE")?></label>
            </span>
            <span class="colum-2">
              <input class="styling_number font-size" type="number" value="">
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_WEIGHT")?></label>
            </span>
            <span class="colum-2 option">
              <div class="optionCckl_font">
                  <input id="<?php echo $type?>-font_weight_1" name="<?php echo $type?>-font_weight" class="pixels font-weight" type="radio" value="normal" checked="checked">
                  <label class="checken" for="<?php echo $type?>-font_weight_1"></label>
              </div>
              <div class="optionCckr_font">
                  <input id="<?php echo $type?>-font_weight_2" name="<?php echo $type?>-font_weight" class="pixels font-weight" type="radio" value="bold">
                  <label class="checken" for="<?php echo $type?>-font_weight_2"></label>
              </div>
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_COLOR")?></label>
            </span>
            <span class="colum-2">
              <input class="font-colorpicker font-color" type="text" value="" data-opacity="1.00">
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_CLASS")?></label>
            </span>
            <span class="colum-2">
              <input class="custom-class" type="text" value="">
            </span>
          </div>
        </div>
          <?php
        }
        if($type != 'form' && $type != 'row' && $type != 'col' && $element != 'block'){?>
          <h2><?php echo $label_style ?></h2>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_MARGIN")?></label>
            </span>
            <span class="colum-2 option">
              <div class="optionCckl">
                <input id="<?php echo $type?>-label_margin_px_1" name="<?php echo $type?>-label_margin_px" class="pixels label-margin-pixels"  type="radio" value="px" checked="checked">
                <label class="checken" for="<?php echo $type?>-label_margin_px_1"></label>
              </div>
              <div class="optionCckr">
                <input id="<?php echo $type?>-label_margin_px_2" name="<?php echo $type?>-label_margin_px" class="pixels label-margin-pixels" type="radio" value="%">
                <label class="checken" for="<?php echo $type?>-label_margin_px_2"></label>
              </div>
            </span>
            <span class="colum-3">
              <span class="marg">
                <label>top</label>
                <input class="styling_number label-margin-top" type="number" value="">
              </span>
              <span class="marg">
                <label>right</label>
                <input class="styling_number label-margin-right" type="number" value="">
              </span>
              <span class="marg">
                <label>bottom</label>
                <input class="styling_number label-margin-bottom" type="number" value="">
              </span>
              <span class="marg">
                <label>left</label>
                <input class="styling_number label-margin-left" type="number" value="">
              </span>
            </span>
          </div>
            <div>
              <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_PADDING")?></label>
              </span>
              <span class="colum-2 option">
                  <div class="optionCckl">
                    <input id="<?php echo $type?>-label_padding_px_1" name="<?php echo $type?>-label_padding_px" class="pixels label-padding-pixels" type="radio" value="px" checked="checked">
                    <label class="checken" for="<?php echo $type?>-label_padding_px_1"></label>
                </div>
                <div class="optionCckr">
                    <input id="<?php echo $type?>-label_padding_px_2" name="<?php echo $type?>-label_padding_px" class="pixels label-padding-pixels label-padding-top" type="radio" value="%">
                    <label class="checken" for="<?php echo $type?>-label_padding_px_2"></label>
                </div>
              </span>
              <span class="colum-3">
                <span class="marg">
                  <label>top</label>
                  <input class="styling_number label-padding-top" type="number" value="">
                </span>
                <span class="marg">
                  <label>right</label>
                  <input class="styling_number label-padding-right" type="number" value="">
                </span>
                <span class="marg">
                <label>bottom</label>
                  <input class="styling_number label-padding-bottom" type="number" value="">
                </span>
                <span class="marg">
                <label>left</label>
                  <input class="styling_number label-padding-left" type="number" value="">
                </span>
              </span>
            </div>
          <?php
          if($layout->type == 'instance'){?>
              <div>
                <span class="colum-1">
                  <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BACKGROUND_COLOR")?></label>
                </span>
                <span class="colum-2">
                  <input class="label-background-colorpicker label-background-color" type="text" value="" data-opacity="1.00">
                </span>
              </div>
            <div>
              <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BORDER")?></label>
              </span>
              <span class="colum-2">
                <input class="styling_number label-border-size" type="number" value="">
              </span>
            </div>
            <div>
              <span class="colum-1">
                <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_BORDER_COLOR")?></label>
              </span>
              <span class="colum-2">
                <input class="label-border-colorpicker label-border-color" type="text" value="" data-opacity="1.00">
              </span>
            </div>
            <?php
          }?>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_SIZE")?></label>
            </span>
            <span class="colum-2">
              <input class="styling_number label-font-size" type="number" value="">
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_WEIGHT")?></label>
            </span>
            <span class="colum-2 option">
                <div class="optionCckl_font">
                    <input id="<?php echo $type?>-label_font_weight_1" name="<?php echo $type?>-label_font_weight" class="pixels label-font-weight" type="radio" value="normal" checked="checked">
                    <label class="checken" for="<?php echo $type?>-label_font_weight_1"></label>
                </div>
                <div class="optionCckr_font">
                    <input id="<?php echo $type?>-label_font_weight_2" name="<?php echo $type?>-label_font_weight" class="pixels label-font-weight" type="radio" value="bold">
                    <label class="checken" for="<?php echo $type?>-label_font_weight_2"></label>
                </div>
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_FONT_COLOR")?></label>
            </span>
            <span class="colum-2">
              <input class="label-font-colorpicker label-font-color" type="text" value="" data-opacity="1.00">
            </span>
          </div>
          <div>
            <span class="colum-1">
              <label><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_CLASS")?></label>
            </span>
            <span class="colum-2">
              <input class="label-custom-class" type="text" value="">
            </span>
          </div>
          <?php
        } ?>

        <style type="text/css">
          #<?php echo $type?>-label_font_weight_1:checked + label,
          #<?php echo $type?>-label_font_weight_2:checked + label,
          #<?php echo $type?>-label_padding_px_1:checked + label,
          #<?php echo $type?>-label_padding_px_2:checked + label,
          #<?php echo $type?>-label_margin_px_1:checked + label,
          #<?php echo $type?>-label_margin_px_2:checked + label,
          #<?php echo $type?>-font_weight_1:checked + label,
          #<?php echo $type?>-font_weight_2:checked + label,
          #<?php echo $type?>-margin_px_1:checked + label,
          #<?php echo $type?>-margin_px_2:checked + label,
          #<?php echo $type?>-padding_px_1:checked + label,
          #<?php echo $type?>-padding_px_2:checked + label {
            z-index: 1;
            background: linear-gradient(top, #1CB09A 0%, #1CB09A 40%, #1B7D6F 100%);
            background: -webkit-linear-gradient(top, #1CB09A 0%, #1CB09A 40%, #1B7D6F 100%);
            background: -moz-linear-gradient(top, #1CB09A 0%, #1CB09A 40%, #1B7D6F 100%);
            box-shadow: 0px 0px 2px 0px #1CB09A;
            -webkit-box-shadow: 0px 0px 2px 0px #1CB09A;
            -moz-box-shadow: 0px 0px 2px 0px #1CB09A;
            -o-box-shadow: 0px 0px 2px 0px #1CB09A;
          }
        </style>
      </div>
      <?php
    }
  }

  if (!function_exists('getEditorContents')) {
    function getEditorContents($editorArea, $hiddenField) {
        jimport('joomla.html.editor');
        $editor = JFactory::getEditor();
        echo $editor->save($hiddenField);
    }
  }

  function getAdminFiledSettingsViewPath ($components, $layoutType, $type, $viewName = 'default'){
    $fSettingsPath = JPATH_BASE . '/components/' . $components . '/views/layoutFieldSettings/'.$layoutType.'/'.$type.'/tmpl/tmpl_'.$viewName.'.php';
    if(file_exists($fSettingsPath)){
      return $fSettingsPath;
    } else {
      echo "Bad layout path, please write to admin";
      exit;
    }
  }

  function getSiteAddFiledViewPath ($components, $type, $viewName = 'default'){
    $addFieldsListPath = JPATH_SITE . '/components/' . $components . '/views/fieldsList/add/'.$type.'/tmpl/tmpl_'.$viewName.'.php';

    if(file_exists($addFieldsListPath)){
      return $addFieldsListPath;
    } else {
      echo "Bad layout path, please write to admin";
      exit;
    }
  }

  function getSiteShowFiledViewPath ($components, $type, $viewName = 'default'){
    $addFieldsListPath = JPATH_SITE . '/components/' . $components . '/views/fieldsList/show/'.$type.'/tmpl/tmpl_'.$viewName.'.php';

    if(file_exists($addFieldsListPath)){
      return $addFieldsListPath;
    } else {
      echo "Bad layout path, please write to admin";
      exit;
    }
  }

  function getSiteSearchFiledViewPath ($components, $type, $viewName = 'default'){
    $addFieldsListPath = JPATH_SITE . '/components/' . $components . '/views/fieldsList/search/'.$type.'/tmpl/tmpl_'.$viewName.'.php';

    if(file_exists($addFieldsListPath)){
      return $addFieldsListPath;
    } else {
      echo "Bad layout path, please write to admin";
      exit;
    }
  }

  if (!function_exists('show_edite_add_form_field_layout')) {
    function show_edite_add_form_field_layout($field, $showType, $lParams,$layoutType = ''){
      $gtree = get_group_children_tree_cck();
      require getAdminFiledSettingsViewPath('com_os_cck', $showType,$field->field_type);
    }
  }

if (!function_exists('avaibleUpdateCCK')) {
  function avaibleUpdateCCK(){
    $avaibleUpdate = false;
    $xml = @simplexml_load_file(JURI::base() . "components/com_os_cck/os_cck.xml");
    $cckV = '';
    $creationDate = '';
    if($xml){
      $cckV = (string)$xml->version;
      $creationDate = (string)$xml->creationDate;
    
      unset($xml);

      $url="http://ordasoft.com/xml_update/os_cck.xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($data);
        $updateArticleUrl = '#';
        $cckVArr = explode(".", $cckV);
        if($xml && isset($xml->version)){
          $ordasoftV = (string)$xml->version;
          $ordasoftVArr = explode(".", $ordasoftV);
          $ordasoftCreationDate = (string)$xml->creationDate;
          $updateArticleUrl = (string)$xml->updateArticleUrl;
          foreach ($cckVArr as $k => $cckSubV) {
            if(isset($ordasoftVArr[$k])){
              if((int)$ordasoftVArr[$k] < (int)$cckSubV){
                break;
              }
              if((int)$ordasoftVArr[$k] > (int)$cckSubV){
                $avaibleUpdate = true;
                break;
              }
            }
          }
        }
        unset($xml);
      }
      return $avaibleUpdate;
  }
}

class cck_additional
{

  // An updated back-end SubMenu helper
  static function addSubmenu($vName)
  {   
      $db = JFactory::getDBO();

      //check update
      $avaibleUpdate = avaibleUpdateCCK();

      $query = "SELECT count(i.eiid) FROM #__os_cck_entity_instance as i "
                ."\n LEFT JOIN #__os_cck_layout as l ON l.lid=i.fk_lid "
                ."\n WHERE l.type = 'add_instance' AND i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_INSTANCE').$notReadText,
          'index.php?option=com_os_cck&amp;task=show_instance',
          $vName == 'Instances'
      );
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_CATEGORIES'),
          'index.php?option=com_os_cck&amp;task=show_categories',
          $vName == 'Categories'
      );
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_ENTITIES'),
          'index.php?option=com_os_cck&amp;task=manage_entities',
          $vName == 'Manage entities'
      );
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_LAYOUTS'),
          'index.php?option=com_os_cck&amp;task=manage_layout',
          $vName == 'Manage views and layouts'
      );

      $query = "SELECT count(i.eiid) FROM #__os_cck_entity_instance as i "
                ."\n LEFT JOIN #__os_cck_layout as l ON l.lid=i.fk_lid "
                ."\n WHERE l.type = 'request_instance' AND i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_SUBMISSIONS').$notReadText,
          'index.php?option=com_os_cck&amp;task=show_requests',
          $vName == 'Requests'
      );

      $query = "SELECT count(i.eiid) FROM #__os_cck_entity_instance as i "
                ."\n LEFT JOIN #__os_cck_layout as l ON l.lid=i.fk_lid "
                ."\n WHERE l.type = 'rent_request_instance' AND i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_RENT').$notReadText,
          'index.php?option=com_os_cck&amp;task=show_rent_request_instances',
          $vName == 'Rent'
      );
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_RENT_HISTORY'),
         'index.php?option=com_os_cck&task=users_rent_history', $vName == 'User Rent History'
      );

      $query = "SELECT count(i.eiid) FROM #__os_cck_entity_instance as i "
                ."\n LEFT JOIN #__os_cck_layout as l ON l.lid=i.fk_lid "
                ."\n WHERE l.type = 'buy_request_instance' AND i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_BUY').$notReadText,
          'index.php?option=com_os_cck&amp;task=show_buy_request_instances',
          $vName == 'Buy'
      );

      $query = "SELECT count(i.eiid) FROM #__os_cck_entity_instance as i "
                ."\n LEFT JOIN #__os_cck_layout as l ON l.lid=i.fk_lid "
                ."\n WHERE l.type = 'review_instance' AND i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_REVIEWS').$notReadText,
          'index.php?option=com_os_cck&amp;task=manage_review',
          $vName == 'Reviews'
      );


      $query = "SELECT count(i.id) FROM #__os_cck_orders as i WHERE i.notreaded = 1";
      $db->setQuery($query);
      $notRead = $db->loadResult();

      $notReadText = '';
      if($notRead)$notReadText = '<span class="cck-not-readed">'.$notRead.'</span>';

      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_ORDERS').$notReadText,
          'index.php?option=com_os_cck&amp;task=orders',
          $vName == 'Orders'
      );

      
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_IMPORT'),
          'index.php?option=com_os_cck&amp;task=import',
          $vName == 'Import'
      );


      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_SETTINGS'),
          'index.php?option=com_config&view=component&component=com_os_cck',
          $vName == 'Settings'
      );
/*		JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_IMPORT_EXPORT'),
          'index.php?option=com_os_cck&amp;task=import_export',
          $vName == 'Import export'
      );
      */
      $notRead = '';
      if($avaibleUpdate)$notRead = '<span class="cck-not-readed">1</span>';
      
      JSubMenuHelper::addEntry(
          JText::_('COM_OS_CCK_MENU_ABOUT').$notRead,
          'index.php?option=com_os_cck&amp;task=about',
          $vName == 'About'
      );

  }

} // end of cck_additional class


if (!function_exists('set_header_name_cck')) {
  function set_header_name_cck($menu, $Itemid){
    $app = JFactory::getApplication();
    $menu1 = $app->getMenu();
    if (isset($menu1->getItem($Itemid)->title)) {
        $menu_name = $menu1->getItem($Itemid)->title;
        return $menu_name;
    }
  }
}

if (!function_exists('user_guid_cck')) {
  function user_guid_cck($oID){
    global $db, $ueConfig;
    if ($oID > 0) {
      $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id  = '" . $oID . "'";
      $db->setQuery($query);
      $gids = $db->loadAssocList();
      if (count($gids) > 0) {
          $ret = '';
          foreach ($gids as $gid) {
              if ($ret != "") $ret .= ',';
              $ret .= $gid['group_id'];
          }
          return $ret;
      } else return 1;
    } else return 1;
  }
}


if (!function_exists('positions_cck')) {
  function positions_cck($position, $params = array()){
    $dispatcher = JDispatcher::getInstance();
    $err_state = ini_get('display_errors');
    ini_set('display_errors', 'Off');
    $plug_row->text = $position; // load the var into plugin_row object
    $plug_row->params = $params;
    JPluginHelper::importPlugin('content');
    $offset = 0;
    $results = $dispatcher->trigger('onContentPrepare', array('com_os_cck', &$plug_row, &$plug_params, $offset)); //run mambot onPrepareContent on plug_row object
    echo $plug_row->text; //echo new content out
    ini_set('display_errors', $err_state);
  }
}


if (!function_exists('get_group_children_cck')) {
  function get_group_children_cck(){
    global $acl, $db;
    $query = 'SELECT `id`,`title` FROM #__usergroups';
    $db->setQuery($query);
    $rows = $db->loadObjectList();
    foreach ($rows as $k => $v) {
        $id_group = $rows[$k]->id;
        $group_name = $rows[$k]->title;
        $groups[$id_group] = $group_name;
    }
    return $groups;
  }
}

if (!function_exists('catOrderDownIcon')) {
  function catOrderDownIcon($i, $n, $index, $task = 'orderdown_category', $alt = 'Move Down'){
      if ($i < $n - 1) {
        return '<a href="#reorder" onclick="return listItemTask(\'cb' . $index . '\',\'' . $task . '\')" title="' . $alt . '">
        <img src="'.JURI::root().'components/com_os_cck/images/arrow_down.png" width="12" height="12" border="0" alt="' . $alt . '" />
        </a>';
      } else return '&nbsp;';
  }
}

if (!function_exists('editorArea')) {
  function editorArea($name, $content, $hiddenField, $width, $height, $col, $row){
    jimport('joomla.html.editor');
    $editor = JFactory::getEditor();
    echo $editor->display($hiddenField, $content, $width, $height, $col, $row);
  }
}

if (!function_exists('catOrderUpIcon')) {
  function catOrderUpIcon($i, $index, $task = 'orderup_category', $alt = 'Move Up')
  {
      if ($i > 0) {
        return '<a href="#reorder" onclick="return listItemTask(\'cb' . $index . '\',\'' . $task . '\')" title="' . $alt . '">
        <img src="'.JURI::root().'components/com_os_cck/images/arrow_up.png" width="12" height="12" border="0" alt="' . $alt . '" />
        </a>';
      } else return '&nbsp;';
  }
}

if (!function_exists('get_group_children_tree_cck')) {
  function get_group_children_tree_cck(){
    global $acl;
    $group_children_tree = array();
    include_once(JPATH_SITE . '/administrator/components/com_users/models/groups.php');
    if (version_compare(JVERSION, "3.0.0", "ge")) {
        $model = JModelLegacy::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
    } else {
        $model = JModel::getInstance('Groups', 'UsersModel', array('ignore_request' => true));
    }
    foreach ($g = $model->getItems() as $k => $v) { // $g contains basic usergroup items info
        $group_title = '.';
        for ($i = 1; $i <= $g[$k]->level; $i++) $group_title .= '-';
        $group_title .= '-' . $g[$k]->title;
        $group_children_tree[] = JHTML::_('select.option',$g[$k]->id, $group_title);
    }
    return $group_children_tree;
  }
}

if(!class_exists('CAT_Utils')){
  class CAT_Utils
  {

      static function categoryArray($section = "",$fromSearch)
      {
          global $db;
          // get a list of the menu items
          $query = "SELECT c.*, c.parent_id AS parent"
              . "\n FROM #__os_cck_categories c"
              . "\n WHERE " . (($section != "") ? (" section='" . $section . "' AND ") : (""))
              . "\n c.published = 1"
              . "\n ORDER BY ordering";
          $db->setQuery($query);
          $items = $db->loadObjectList();

          if($fromSearch){
              $cat_all = $items;
              $user = JFactory::getUser();
              $cat_all1= array();

              for ($i = 0; $i < count($cat_all); $i++) {
                  if (($cat_all[$i]->params !== implode(',',array_diff(explode(',',$cat_all[$i]->params),$user->groups))) || $cat_all[$i]->params == 1) {
                      $cat_all1[]=$cat_all[$i];
                  }
              }
              $items = $cat_all1;
              if($fromSearch == 2)return $items;
          }
          //echo $db->stderr();


          // establish the hierarchy of the menu
          $children = array();
          // first pass - collect children
          foreach ($items as $v) {
              $pt = $v->parent_id;
              $list = @$children[$pt] ? $children[$pt] : array();
              array_push($list, $v);
              $children[$pt] = $list;
          }//print_r($children);exit;
          // second pass - get an indent list of the items
          $array = os_cckTreeRecurse(0, '', array(), $children);
          return $array;
      }

  }
}

if (!function_exists('mosReadDirectory')) {
  function mosReadDirectory($path, $filter = '.', $recurse = false, $fullpath = false)
  {
      $arr = array(null);

      // Get the files and folders
      jimport('joomla.filesystem.folder');
      $files = JFolder::files($path, $filter, $recurse, $fullpath);
      $folders = JFolder::folders($path, $filter, $recurse, $fullpath);
      // Merge files and folders into one array
      $arr = array_merge($files, $folders);
      // Sort them all
      asort($arr);
      return $arr;
  }
}

/**
* HTML Class
* Utility class for all HTML drawing classes
* @desc class General HTML creation class. We use it for back/front ends.
*/
if(!class_exists('HTML')){
  class HTML
  {
      // TODO :: merge categoryList and categoryParentList
      // add filter option ?
      static function categoryList($id,$fromSearch=0,$layout_params = ''){
        $field_styling = (isset($layout_params['field_styling']))? $layout_params['field_styling'] : '';
        $custom_class = (isset($layout_params['custom_class']))? $layout_params['custom_class'] : '';
        $list = CAT_Utils::categoryArray('com_os_cck',$fromSearch);
        if($fromSearch == 2)return $list;
        $this_treename = '';
        $childs_ids = $options = Array();
        foreach ($list as $item) {
          if (array_key_exists($item->parent_id, $childs_ids))
            $childs_ids[$item->cid] = $item->cid;
        }
        foreach ($list as $item) {
          if ($this_treename) {
            if (strpos($item->title, $this_treename) === false
                && array_key_exists($item->cid, $childs_ids) === false
            ) {
                $options[] = JHTML::_('select.option',$item->cid, $item->title);
            }
          } else {
            $options[] = JHTML::_('select.option',$item->cid, $item->title);
          }
        }
        $parent = JHTML::_('select.genericlist',$options, 'categories[]', $field_styling.' id="catid" multiple="multiple" class="'.$custom_class.' inputbox" size="4" onchange=""', 'value', 'text',$id);
        return $parent;
      }

      static function categoryParentList($id, $action, $options = array())
      {
          global $db;
          $fromSearch = 0;
          $list = CAT_Utils::categoryArray('com_os_cck',$fromSearch);
          $cat = new os_cckCategory($db);
          $cat->load($id);
          $this_treename = '';
          $childs_ids = Array();
          foreach ($list as $item) {
              if ($item->cid == $cat->cid || array_key_exists($item->parent_id, $childs_ids))
                  $childs_ids[$item->cid] = $item->cid;
          }
          foreach ($list as $item) {
              if ($this_treename) {
                  if ($item->cid != $cat->cid
                      && strpos($item->title, $this_treename) === false
                      && array_key_exists($item->cid, $childs_ids) === false
                  ) {
                      $options[] = JHTML::_('select.option',$item->cid, $item->title);
                  }
              } else {
                  if ($item->cid != $cat->cid) {
                      $options[] = JHTML::_('select.option',$item->cid, $item->title);
                  } else {
                      $this_treename = "$item->title/";
                  }
              }
          }

          $parent = null;
          $parent = JHTML::_('select.genericlist',$options, 'parent_id',
              'class="inputbox" size="1"', 'value', 'text', $cat->parent_id);
          return $parent;

      }

      static function imageList($name, &$active, $javascript = null, $directory = null)
      {
          if (!$javascript) {
              $javascript = "onchange=\"javascript:if (document.adminForm." . $name .
                  ".options[selectedIndex].value!='')    " .
                  "{document.imagelib.src='../images/stories/' + document.adminForm."
                  . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
          }
          if (!$directory) {
              $directory = '/images/stories';
          }

          if (!file_exists(JPATH_SITE . $directory)) {
              @mkdir(JPATH_SITE . $directory, 0777);
          }

          $imageFiles = mosReadDirectory(JPATH_SITE . $directory);
          $images = array(JHTML::_('select.option','', JText::_('COM_OS_CCK_A_SELECT_IMAGE')));
          foreach ($imageFiles as $file) {
              if (preg_match("/bmp|gif|jpeg|jpg|png/i", $file)) {
                  $images[] = JHTML::_('select.option',$file);
              }
          }
          $images = JHTML::_('select.genericlist',$images, $name, 'id="' . $name . '" class="inputbox" size="1" '
              . $javascript, 'value', 'text', $active);
          return $images;

      }

  }
}

if (!function_exists('checkAccess_cck')) {
  function checkAccess_cck($accessgroupid, $usersgroupid) {
    if(empty($accessgroupid))return 0;
      if (!is_array($usersgroupid)) {
          $usersgroupid = explode(',', $usersgroupid);
      }
      //parse usergroups
      $tempArr = array();
      if (!is_array($usersgroupid)) {
          $tempArr = explode(',', $accessgroupid);
      }else{
         $tempArr = $accessgroupid;
      }

      for ($i = 0; $i < count($tempArr); $i++) {
          if (((!is_array($usersgroupid) && $tempArr[$i] == $usersgroupid) OR
                 (is_array($usersgroupid) && in_array($tempArr[$i], $usersgroupid))) || $tempArr[$i] == 1 ) {//|| $usersgroupid[$i] == 8//SuperUser
              //allow access
              return true;
          }
      } // end for
      //deny access
      return 0;
  }
}



if(!class_exists('getLayoutPathCCK')){
class getLayoutPathCCK{

  static function getLayoutPathCom($components,$type, $layout = 'default'){
    $template = JFactory::getApplication()->getTemplate();
    $defaultLayout = $layout;

    if (strpos($layout, ':') !== false){
      // Get the template and file name from the string
      $temp = explode(':', $layout);
      $template = ($temp[0] == '_') ? $template : $temp[0];
      $layout = $temp[1];
      $defaultLayout = ($temp[1]) ? $temp[1] : 'default';
    }

    // Build the template and base path for the layout
    $tPath = JPATH_THEMES . '/' . $template . '/html/' . $components . '/'.$type.'/tmpl_'. $layout . '.php';
    $cPath = JPATH_BASE . '/components/' . $components . '/views/'.$type.'/tmpl/tmpl_'.$layout.'.php';
    $dPath = JPATH_BASE . '/components/' . $components . '/views/'.$type.'/tmpl/tmpl_default.php';

    // If the template has a layout override use it
    //print_r(file_exists($tPath));exit;
    if (file_exists($tPath)){
      return $tPath;
    }
    else if (file_exists($cPath)){
      return $cPath;
    }
    else if (file_exists($dPath)){
      return $dPath;
    } else {
      echo "Bad layout path, please write to admin";
      exit;
    }
  }


  static function getAdminLayoutViewPath($components, $type, $viewName = 'default'){

      $Path = JPATH_BASE . '/components/' . $components . '/views/'.$type.'/tmpl/tmpl_'.$viewName.'.php';
      $layoutPath = JPATH_BASE . '/components/' . $components . '/views/layoutViews/'.$type.'/tmpl/tmpl_'.$viewName.'.php';
      if (file_exists($Path)){
        return $Path;
      }else if(file_exists($layoutPath)){

        return $layoutPath;
      } else {
        echo "Bad layout path, please write to admin";
        exit;
      }

    }
  }

  if(!function_exists('return_bytes')){
  function return_bytes($val)
    {
        if (empty($val)){
            return 0;
        }

        $val = trim($val);
        preg_match('#([0-9]+)[\s]*([a-z]+)#i', $val, $matches);
        $last = '';

        if (isset($matches[2])){
            $last = $matches[2];
        }

        if (isset($matches[1])){
            $val = (int) $matches[1];
        }

        switch (strtolower($last)){
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }

        return (int) $val;
    }
  }

  if(!function_exists('transforDateFromPhpToJquery')){
    function transforDateFromPhpToJquery($format){
      $DateToFormat = str_replace("d",'dd',(str_replace("m",'mm',(str_replace("Y",'yy',(
        str_replace('%','',$format)))))));
      return $DateToFormat;
    }
  }

  if(!function_exists('get_average_rating')){
    function get_average_rating($field, $layout, $entityInstance){
      global $db;
      $query = "SELECT AVG(cei.$field->db_field_name) FROM #__os_cck_child_parent_connect as ch "
                  ."\n LEFT JOIN #__os_cck_entity_instance as ei ON ch.fid_child = ei.eiid"
                  ."\n LEFT JOIN #__os_cck_layout as lay ON lay.lid = ei.fk_lid"
                  ."\n LEFT JOIN #__os_cck_content_entity_$entityInstance->fk_eid as cei ON cei.fk_eiid = ei.eiid"
                  ."\n WHERE ch.fid_parent=$entityInstance->eiid AND lay.type='review_instance'";
      $db->setQuery($query);
      $value[0] = new stdClass();
      $value[0]->data = round($db->loadResult() * 2) / 2;

      return $value;
    }
  }

  if(!function_exists('getLayoutType')){
    function getLayoutType($type){
      switch($type){
        case "add_instance":
            $type = "Add Instance";
          break;

        case "all_categories":
          $type = "All Categories";
          break;

        case "all_instance":
          $type = "All Instance";
          break;

        case "calendar":
          $type = "Calendar";
          break;  

        case "category":
          $type = "Show Category";
          break;

        case "instance":
          $type = "Show Instance";
          break;

        case "search":
          $type = "Show Search";
          break;

        case "request_instance":
          $type = "Add Request Instance";
          break;

        case "rent_request_instance":
          $type = "Add Rent Request Instance";
          break;

        case "buy_request_instance":
          $type = "Add Buy Request Instance";
          break;

        case "review_instance":
          $type = "Add Review Instance";
          break;

        case "show_review_instance":
          $type = "Show Review Instance";
          break;
      }
      return $type;
    }
  }
}
