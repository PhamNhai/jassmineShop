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

class AdminViewRentHistory{

  static function showUsersRentHistory($option, & $rows_item, & $return_item, &$history_item, & $userlist, $type, $show_fields,$usermenu, $sort_arr){
    global $user, $db;
    // Load mooTools
    JHtml::_('behavior.framework', true);
    $doc = JFactory::getDocument();
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_RENT_HISTORY_SHOW') . "</div>";
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

    ?>
    <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
    <form action="index.php" method="post" name="adminForm" id="adminForm" >
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="filters">
        <tr>
            <td nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_USER') .$userlist; ?></td>
        </tr>
      </table>
      <input type="hidden" name="task" value="users_rent_history"/>
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
    </form>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
       <!--  <th width="5%" align="left">
        </th> -->
        <!-- <th align="left" class="title" width="5%" nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_INSTANCE_ID');?></th> -->


        <th align="center" class="title" width="3%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_INSTANCE_ID'), 'inst_id', $sort_arr,JRequest::getVar('task'));?></th>
        <?php
        foreach($show_fields as $value){
          foreach($value as $field){
            ?>
            <th align="left" class="title" width="15%"
              nowrap="nowrap"><?php echo $field->field_name;?></th>
            <?php
          }
        }
        ?>
        <!-- <th align="left" class="title" width="15%"
            nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY');?></th> -->

        <th align="left" class="title" width="15%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_ENTITY'), 'inst_entity', $sort_arr,JRequest::getVar('task'));?></th>

        <!-- <th align="left" class="title" width="15%"
            nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_FROM'); ?></th> -->

        <th align="left" class="title" width="15%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_RENT_FROM'), 'rent_from', $sort_arr,JRequest::getVar('task'));?></th>

        <!-- <th align="left" class="title" width="20%"
            nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_UNTIL'); ?></th>
        <th align="left" class="title" width="20%"
            nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT_RETURN'); ?></th> -->

        <th align="left" class="title" width="15%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_RENT_UNTIL'), 'rent_until', $sort_arr,JRequest::getVar('task'));?></th>

        <th align="left" class="title" width="15%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_RENT_RETURN'), 'rent_return', $sort_arr,JRequest::getVar('task'));?></th>

      </tr>
      <?php
        for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
          $row = & $rows_item[$i];
          ?>
          <tr <?php echo ($row->rent_return)? 'style="color:#0C6B00";' : 'style="color:#630712"'?> class="row<?php echo $i % 2; ?>">
            <td align="left"><?php echo $row->eiid; ?></td>
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
            </td>
<!-- **************************************************************** -->
            <td align="left"><?php echo $row->entity; ?></td>
            <td align="left"><?php echo $row->rent_from ?></td>
            <td align="left"><?php echo $row->rent_until ?></td>
            <td align="left"><?php echo ($row->rent_return)?$row->rent_return : '<b>In Rent</b>'?></td>
        </tr>
      <?php
      }//end for
      ?>
    </table>
    <?php
  }

}
