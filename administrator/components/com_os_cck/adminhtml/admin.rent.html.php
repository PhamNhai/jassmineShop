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

class AdminViewRent{

  static function showRentItems($option, & $rows_item, & $return_item, &$history_item, & $userlist, $type, $show_fields){
    global $user, $db, $task, $os_cck_configuration;
    // Load mooTools

    JHtml::_('behavior.framework', true);
    $doc = JFactory::getDocument();
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" .
     JText::_('COM_OS_CCK_RENT_SHOW') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css");
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/admin_style.css");
    if (version_compare(JVERSION, "3.0.0", "lt"))
      $doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery-1.7.1.min.js");
    $doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js","text/javascript",true);

    //timepicker
    $doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/jquery.cck_timepicker.css");
    $doc->addScript(JURI::root() . "components/com_os_cck/assets/js/jquery.cck_timepicker.js","text/javascript",true);

    $eiid = $_REQUEST['eiid'];
    $eiid_fordate = $eiid[0];
    $date_NA = available_dates_cck($eiid_fordate);


    ?>

    <?php if($os_cck_configuration->get('by_time')): ?>
      <script type="text/javascript">

        jQuerCCK(document).ready(function() {
              //rent_from timepicker
          jQuerCCK("#time_from, #time_until").timepicker(
              { 
                'timeFormat' : "H:i:s",
                'step':30
              }
          );

          jQuerCCK("#rent_from, #time_from").change(function(event) {
             var date = jQuerCCK('#rent_from').val();
             var time = jQuerCCK('#time_from').val();

             jQuerCCK("[name = rent_from]").val(date+" "+time);
          });

          jQuerCCK("#rent_until, #time_until").change(function(event) {
             var date = jQuerCCK('#rent_until').val();
             var time = jQuerCCK('#time_until').val();

             jQuerCCK("[name = rent_until]").val(date+" "+time);
          });

      });

      </script>

    <?php else: ?>

        <script type="text/javascript">

      jQuerCCK(document).ready(function() {

        jQuerCCK("#rent_from").change(function(event) {
           var date = jQuerCCK('#rent_from').val();
           jQuerCCK("[name = rent_from]").val(date);
        });

        jQuerCCK("#rent_until").change(function(event) {
           var date = jQuerCCK('#rent_until').val();
           jQuerCCK("[name = rent_until]").val(date);
        });

    });

    </script>

    <?php endif; ?>


    <script type="text/javascript">
      var unavailableDates = Array();
      jQuerCCK(document).ready(function() {
        var k=0;
        <?php if(!empty($date_NA)){
          foreach ($date_NA as $N_A){ ?>
            unavailableDates[k]= '<?php echo $N_A; ?>';
            k++;
          <?php } ?>
        <?php } ?>

        function unavailableFrom(date) {
          dmy = date.getFullYear() + "-" + ('0'+(date.getMonth() + 1)).slice(-2) +
            "-" + ('0'+date.getDate()).slice(-2);
          if (jQuerCCK.inArray(dmy, unavailableDates) == -1) {
            return [true, ""];
          } else {
            return [false, "", "Unavailable"];
          }
        }

         function unavailableUntil(date) {
          dmy = date.getFullYear() + "-" + ('0'+(date.getMonth() + 1)).slice(-2) +
            "-" + ('0'+(date.getDate()-("<?php  if($os_cck_configuration->get('rent_type')==0 && $os_cck_configuration->get('by_time') == 0) echo 1;?>"))).slice(-2);
          if (jQuerCCK.inArray(dmy, unavailableDates) == -1) {
            return [true, ""];
          } else {
            return [false, "", "Unavailable"];
          }
        }

        jQuerCCK( "#rent_from" ).datepicker({
          <?php 
          if($task == "add_rent") echo 'minDate: "+0", beforeShowDay: unavailableFrom,';
          ?>
          dateFormat: "<?php echo transforDateFromPhpToJquery('d-m-Y');?>"
          
        });

        jQuerCCK( "#rent_until" ).datepicker({
          <?php 
          if($task == "add_rent") echo 'minDate: "+0", beforeShowDay: unavailableUntil,';
          ?>
          dateFormat: "<?php echo transforDateFromPhpToJquery('d-m-Y');?>"
          
        });


      });

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

      Joomla.submitbutton = function(pressbutton, section) {
        var form = document.adminForm;

        if(pressbutton == 'save_rent'){
          if(form.userid.value <= 0 && form.user_name.value == '' && form.user_email.value == ''){
            window.scrollTo(0,findPosY(form.userid)-100);
            document.getElementById('error_massage').innerHTML = "<?php echo 'Please select user to rent instances'; ?>";
            form.userid.style.borderColor = "#FF0000";
            form.userid.style.color = "#FF0000";
            document.getElementById('error_massage').style.color = "#FF0000";
            return;
          }else{
            form.userid.style.borderColor = "#CCCCCC";
            form.userid.style.color = "#333333";
          }

          if(jQuerCCK(form).find('#rent_from').val() == ''){
          alert("Please, choose date from");
          return;
          }
          if(jQuerCCK(form).find('#rent_until').val() == ''){
            alert("Please, choose date until");
            return;
          }

          if(<?php echo  $os_cck_configuration->get('by_time');?>){

            if(jQuerCCK(form).find('#time_from').val() == ''){
              alert("Please, choose time from");
              return;
            }
            if(jQuerCCK(form).find('#time_until').val() == ''){
              alert("Please, choose time until");
              return;
            }
            
          }
          
        }else if(pressbutton == 'save_edit_rent'){
          if(form.userid.value <= 0 && form.user_name.value == '' && form.user_email.value == ''){
            window.scrollTo(0,findPosY(form.userid)-100);
            document.getElementById('error_massage').innerHTML = "<?php echo 'Please select user to rent instances'; ?>";
            form.userid.style.borderColor = "#FF0000";
            form.userid.style.color = "#FF0000";
            document.getElementById('error_massage').style.color = "#FF0000";
            return;
          }else{
            form.userid.style.borderColor = "#CCCCCC";
            form.userid.style.color = "#333333";
          }
          if(jQuerCCK(form).find('#rent_from').val() == ''){
          alert("Please, choose date from");
          return;
          }
          if(jQuerCCK(form).find('#rent_until').val() == ''){
            alert("Please, choose date until");
            return;
          }
          
          if(<?php echo  $os_cck_configuration->get('by_time');?>){

            if(jQuerCCK(form).find('#time_from').val() == ''){
              alert("Please, choose time from");
              return;
            }
            if(jQuerCCK(form).find('#time_until').val() == ''){
              alert("Please, choose time until");
              return;
            }
            
          }

          form.task.value = 'save_edit_rent';
        }else if(pressbutton == 'add_rent') {

          form.task.value = 'add_rent';
        }else{
          form.task.value = 'rent_return';
        }
        form.submit();
      }
    </script>
    <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
      <?php
      if ($type == "rent"  or $type == "edit_rent" or $type == "edit_rent" ) { ?>
        <div id="error_massage"></div>
        <h2>Rent:</h2>
        <table cellpadding="4" cellspacing="0" border="0" width="100%">
          <tr>
            <td align="left" nowrap="nowrap">
              <?php echo JText::_('COM_OS_CCK_LABEL_RENT_TO') . ':'; ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
              <?php echo $userlist; ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
              <?php echo JText::_('COM_OS_CCK_LABEL_RENT_USER') . ':'; ?>
            </td>
          </tr>
          <tr>
            <td align="left">
              <input type="text" name="user_name" class="inputbox"/>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
                <?php echo JText::_('COM_OS_CCK_LABEL_RENT_EMAIL') . ':'; ?>
            </td>
          </tr>
          <tr>
            <td>
                <input type="text" name="user_email" class="inputbox"/>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
              <?php echo "Rent from:"; ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
              <input type="text" id="rent_from" name="">
            </td>
          </tr>

          <?php if($os_cck_configuration->get('by_time')): ?>
            <tr>
              <td align="left" nowrap="nowrap">
                <input type="text" id="time_from" name="" placeholder="Time from">
              </td>
            </tr>
          <?php endif;?>

          <input type="hidden" name="rent_from">
          <tr>
            <td align="left" nowrap="nowrap">
              <?php echo JText::_('COM_OS_CCK_LABEL_RENT_TIME'); ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap">
              <input type="text" id="rent_until" name="">
            </td>
          </tr>

            <?php if($os_cck_configuration->get('by_time')): ?>
              <tr>
                <td align="left" nowrap="nowrap">
                  <input type="text" id="time_until" name="" placeholder="Time from">
                </td>
              </tr>
            <?php endif;?>

          <input type="hidden" name="rent_until">
        </table>
        <?php
      }
      ?>

      <h2>Rent Return:</h2>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
        <tr>
          <th width="5%" align="left">
          </th>
          <th width="5%">#</th>
          <th align="center" class="title" width="5%" nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_INSTANCE_ID');?></th>
          <?php
          foreach($show_fields as $value){
            foreach($value as $field){
              ?>
              <th align="center" class="title" width="15%"
                nowrap="nowrap"><?php echo $field->field_name;?></th>
              <?php
            }
          }
          ?>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY');?></th>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_FROM'); ?></th>
          <th align="center" class="title" width="20%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_UNTIL'); ?></th>
          <th align="center" class="title" width="20%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_RETURN'); ?></th>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_TO'); ?></th>
        </tr>
        <?php
          for ($i = 0, $n = count($return_item); $i < $n; $i++) {
            $row = & $return_item[$i];
            ?>
            <tr <?php echo ($row->rent_return)? 'style="color:#0C6B00";' : 'style="color:#630712"'?> class="row<?php echo $i % 2; ?>">
              <td width="20" align="left">
                <?php if ($row->checked_out && $row->checked_out != $user->id) { ?>
                  &nbsp;
                <?php
                } else {
                  echo
                  '<input id="cb'.$i.'" type="checkbox" onclick="Joomla.isChecked(this.checked);" value="'.$row->rent_id.'" name="return_id[]">';
                }
                ?>
              </td>
              <td align="center"><?php echo ($i+1); ?></td>
              <td align="center"><?php echo $row->eiid; ?></td>
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
  <!-- **************************************************************** -->

              <td align="center"><?php echo $row->entity; ?></td>
              <td align="center"><?php echo $row->rent_from ?></td>
              <td align="center"><?php echo $row->rent_until ?></td>
              <td align="center"><?php echo ($row->rent_return)?$row->rent_return : '<b>In Rent</b>'?></td>
              <td align="center"><?php echo ($row->user_name || $row->user_email)?
                                            $row->user_name . ":  " . $row->user_email:'' ?>
              </td>
          </tr>
        <?php
        }//end for
        ?>
      </table>
      <h2>Rent History:</h2>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
        <tr>
          <th width="5%">#</th>
          <th align="center" class="title" width="5%" nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_INSTANCE_ID');?></th>
          <?php
          foreach($show_fields as $value){
            foreach($value as $field){
              ?>
              <th align="center" class="title" width="15%"
                nowrap="nowrap"><?php echo $field->field_name;?></th>
              <?php
            }
          }
          ?>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY');?></th>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_FROM'); ?></th>
          <th align="center" class="title" width="20%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_UNTIL'); ?></th>
          <th align="center" class="title" width="20%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_RETURN'); ?></th>
          <th align="center" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_TO'); ?></th>
        </tr>
        <?php
          for ($i = 0, $n = count($history_item); $i < $n; $i++) {
            $row = & $history_item[$i];
            ?>
            <tr <?php echo ($row->rent_return)? 'style="color:#0C6B00";' : 'style="color:#630712"'?> class="row<?php echo $i % 2; ?>">
              <td align="center"><?php echo ($i+1); ?></td>
              <td align="center"><?php echo $row->eiid; ?></td>
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
  <!-- **************************************************************** -->
              <td align="center"><?php echo $row->entity; ?></td>
              <td align="center"><?php echo $row->rent_from ?></td>
              <td align="center"><?php echo $row->rent_until ?></td>
              <td align="center"><?php echo ($row->rent_return)?$row->rent_return : '<b>In Rent</b>'?></td>
              <td align="center"><?php echo ($row->user_name || $row->user_email)?
                                            $row->user_name . ":  " . $row->user_email:'' ?>
              </td>
          </tr>
        <?php
        }//end for
        ?>
      </table>
      <input type="hidden" name="eiid" value="<?php echo $eiid_fordate?>"/>
      <input type="hidden" name="option" value="<?php echo $option; ?>"/>
      <input type="hidden" name="task" value="save_rent"/>
      <input type="hidden" name="boxchecked" value="1"/>
      <input type="hidden" name="save" value="1"/>
    </form>
    <?php
  }

}
