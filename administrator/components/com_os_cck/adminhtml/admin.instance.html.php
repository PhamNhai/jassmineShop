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


class AdminViewInstance{

  static function showInstances($option, & $rows_item, & $clist,
                                & $publist, & $search, & $pageNav, & $sort_arr, $show_fields,$entity_list, & $approvedlist,& $userslist){
      global $doc, $user, $app, $session, $db;
      $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_SHOW') . "</div>";
      $app = JFactory::getApplication();
      $app->JComponentTitle = $html;
      $onclick = "Joomla.checkAll(this);";
      ?>
      <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist  instances_list filters">
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
              <?php echo $approvedlist; ?>
            </td>
            <td>
              <?php echo $userslist; ?>
            </td>
            <td>
              <?php echo $clist; ?>
            </td>
            <td>
              <?php echo $entity_list; ?>
            </td>
            <td>
              <div class="btn-group pull-right hidden-phone">
                <label for="limit"
                       class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                <?php echo $pageNav->getLimitBox(); ?>
              </div>
            </td>
          </tr>
        </table>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
          <tr>
            <th width="3%" align="center">
                <input type="checkbox" name="toggle" value="" onClick="<?php echo $onclick; ?>"/>
            </th>
            <th align="center" class="title" width="5%" nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_INSTANCE_ID'), 'inst_id', $sort_arr);?></th>
            <?php
            foreach($show_fields as $value){
              foreach($value as $field){
                ?>
                <th align="left" class="title" width="10%"
                  nowrap="nowrap"><?php echo HTML_os_cck::sort_head($field->field_name, $field->db_field_name, $sort_arr);?></th>
                <?php
              }
            }
            ?>
            <th align="left" class="title" width="7%"
                nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_ENTITY'), 'inst_entity', $sort_arr);?></th>
            <?php if (JFactory::getUser()->authorise('access_to_rent_requests', 'com_os_cck')): ?>
            <th align="center" class="title" width="70%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_RENT'); ?></th>
            <?php endif; ?>
            <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_PUBLIC'); ?></th>
            <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_APPROVED'); ?></th>
            <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_CONTROL'); ?></th>
          </tr>
          <?php
            $session->set('sorting_direction', $sort_arr['sorting_direction']);
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
                <td align="center"><a href="?option=com_os_cck&task=edit_instance&eiid[]=<?php echo $row->eiid?>">
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
                        <div style="margin-right:15px";>
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
                  } ?>
                <td align="left"><?php echo $row->entity; ?></td>
                <?php if (JFactory::getUser()->authorise('access_to_rent_requests', 'com_os_cck')): ?>
                <td align="center">
                  <?php
                  if ($row->rent_from == null) {
                    ?>
                    <a href="javascript: void(0);"
                       onClick="return listItemTask('cb<?php echo $i; ?>','rent')">
                        <img src='./components/com_os_cck/images/lend_f2.png' align='middle' width='15'
                             height='15' border='0' alt='Rent out'/>
                        <br/>
                    </a>
                  <?php
                  } else {
                    ?>
                    <a href="javascript: void(0);"
                       onClick="return listItemTask('cb<?php echo $i; ?>','rent')">
                        <img src='./components/com_os_cck/images/lend_return_f2.png' align='middle'
                             width='15' height='15' border='0' alt='Return item'/>
                        <br/>
                    </a>
                  <?php
                  }
                  ?>
                </td>
                <?php endif; ?>
                <?php
                $task = $row->published ? 'unpublish_instances' : 'publish_instances';
                $alt = $row->published ? 'Unpublish' : 'Publish';
                $img = $row->published ? 'tick.png' : 'publish_x.png';
                $task1 = $row->approved ? 'unapprove_instances' : 'approve_instances';
                $alt1 = $row->approved ? 'Unapproved' : 'Approved';
                $img1 = $row->approved ? 'tick.png' : 'publish_x.png';
                $img = "components/com_os_cck/images/{$img}";
                $img1 = "components/com_os_cck/images/{$img1}";
                ?>
                <td width="5%" align="center">
                <?php if (JFactory::getUser()->authorise('publish_instances', 'com_os_cck')): ?>
                    <a href="javascript: void(0);"
                       onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>','adminForm')">
                <?php endif; ?>
                        <img src="<?php echo $img; ?>" width="12" height="12" border="0"
                             alt="<?php echo $alt; ?>"/>
                <?php if (JFactory::getUser()->authorise('publish_instances', 'com_os_cck')): ?>
                    </a>
                <?php endif; ?>
                </td>
                <td width="5%" align="center">
                  <?php if (JFactory::getUser()->authorise('publish_instances', 'com_os_cck')): ?>
                  <a href="javascript: void(0);"
                     onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task1; ?>','adminForm')">
                  <?php endif; ?>
                      <img src="<?php echo $img1; ?>" width="12" height="12" border="0"
                           alt="<?php echo $alt1; ?>"/>
                  <?php if (JFactory::getUser()->authorise('publish_instances', 'com_os_cck')): ?>
                  </a>
                  <?php endif; ?>
                </td>
                <?php
                if ($row->checked_out) {
                  ?>
                  <td align="center"><?php echo $row->editor;?></td>
                <?php } else { ?>
                  <td align="center">&nbsp;</td>
                <?php } ?>
            </tr>
          <?php
          }//end for
          ?>
          <tr>
            <td colspan="13"><?php echo $pageNav->getListFooter(); ?></td>
          </tr>
        </table>
        <input type="hidden" name="option" value="<?php echo $option; ?>"/>
        <input type="hidden" name="task" value="blablabla"/>
        <input type="hidden" name="boxchecked" value="0"/>
      </form>

  <?php
  }

  static function showInstancesModal($option, & $rows_item, & $clist,
                                & $publist, & $search, & $pageNav, & $sort_arr, $show_fields)
  {
      global $doc, $user, $app, $session, $templateDir,$db;
      ?>
      <form action="index.php" method="post" name="manageInstanceModal" id="manageInstanceModal">
      <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_MODAL_SELECT_INSTANCE_BUTTON")?></h4>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="manageInstanceModalTable">
          <tr>
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
            <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY');?></th>
            <th align="center" class="title" width="2%" nowrap="nowrap">
                <?php echo JText::_('COM_OS_CCK_LABEL_INSTANCE_ID');?></th>
          </tr>
          <?php
            $session->set('sorting_direction', $sort_arr['sorting_direction']);
            for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
              $row = & $rows_item[$i];
              ?>
              <tr onclick="if(window.parent)window.parent.selectInstance(<?php echo $row->eiid?>)" class="modalRow<?php echo $i % 2; ?>">
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
                        <div style="margin-right:15px";>
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
                  } ?>
                <td align="left"><?php echo $row->entity; ?></td>
                <td align="center"><?php echo $row->eiid; ?></td>
            </tr>
          <?php
          }//end for
          ?>
        </table>
        <input type="hidden" name="option" value="<?php echo $option; ?>"/>
        <input type="hidden" name="task" value="blablabla"/>
        <input type="hidden" name="boxchecked" value="0"/>
      </form>

  <?php
  }

  static function showInstanceModalPlg($option, & $rows_item, & $clist,
                                & $publist, & $search, & $pageNav, & $sort_arr, $show_fields, $lid)
  {
      global $doc, $user, $app, $session, $templateDir,$db;
      ?>
      <form action="index.php" method="post" name="manageInstanceModal" id="manageInstanceModal">
      <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="manageInstanceModalTable">
          <tr>
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
            <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY');?></th>
            <th align="center" class="title" width="2%" nowrap="nowrap">
                <?php echo JText::_('COM_OS_CCK_LABEL_INSTANCE_ID');?></th>
          </tr>
          <?php
            $session->set('sorting_direction', $sort_arr['sorting_direction']);
            for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
              $row = & $rows_item[$i];
              ?>
              <tr class="modalRow<?php echo $i % 2; ?>"
              onclick="window.location.href = 'index.php?option=com_os_cck&task=select_data_for_editor_button&tmpl=component&lid=<?php echo $lid?>&eiid='+<?php echo $row->eiid?>">
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
                        <div style="margin-right:15px";>
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
                  } ?>
                <td align="left"><?php echo $row->entity; ?></td>
                <td align="center"><?php echo $row->eiid; ?></td>
            </tr>
          <?php
          }//end for
          ?>
        </table>
        <input type="hidden" name="option" value="<?php echo $option; ?>"/>
        <input type="hidden" name="task" value="show_instance"/>
        <input type="hidden" name="boxchecked" value="0"/>
      </form>

  <?php
  }

  static function editInstance($option, $entityInstance, $layout, $layout_params){
    global $os_cck_configuration,$user, $app;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_SHOW') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $type = 'editInstance';

    require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
  }

}
