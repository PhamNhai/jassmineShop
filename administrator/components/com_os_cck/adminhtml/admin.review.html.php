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


class AdminViewReview{
  static function showReviews($option, & $rows_item, & $clist,
                                & $publist, & $search, & $pageNav, & $sort_arr, $show_fields,$entity_list){
    global $doc, $user, $app, $session, $db;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_SHOW') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $onclick = (version_compare(JVERSION, "1.6.0", "lt")) ? "checkAll(" . count($rows_item) . ");" : "Joomla.checkAll(this);";
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
              <th align="left" class="title" width="5%"
                nowrap="nowrap"><?php echo HTML_os_cck::sort_head($field->field_name, $field->db_field_name, $sort_arr,JRequest::getVar('task'));?></th>
              <?php
            }
          }
          ?>
<!--             <th align="center" class="title"
              nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_TITLE'), 'title', $sort_arr,JRequest::getVar('task')); ?></th> -->
          <th align="left" class="title" width="5%"
              nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_LABEL_ENTITY'), 'inst_entity', $sort_arr,JRequest::getVar('task'));?></th>
          <th align="left" class="title" width="10%"
              nowrap="nowrap"><?php echo HTML_os_cck::sort_head(JText::_('COM_OS_CCK_REVIEW_DATE'), 'created', $sort_arr,JRequest::getVar('task'));?></th>
          <th align="left" class="title" width="70%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_PUBLIC'); ?></th>
          <th align="left" class="title" width="5%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_APPROVED'); ?></th>
          <th align="left" class="title" width="5%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_CONTROL'); ?></th>
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
              <td align="center"><a href="#edit_review"
                     onClick="return listItemTask('cb<?php echo $i; ?>','edit_review')">
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
  <!-- **************************************************************** -->
              <td align="left"><?php echo $row->entity; ?></td>
              <td align="left"><?php echo $row->created; ?></td>
              <?php
              $task = $row->published ? 'unpublish_reviews' : 'publish_reviews';
              $alt = $row->published ? 'Unpublish' : 'Publish';
              $img = $row->published ? 'tick.png' : 'publish_x.png';
              $task1 = $row->approved ? 'unapprove_reviews' : 'approve_reviews';
              $alt1 = $row->approved ? 'Unapproved' : 'Approved';
              $img1 = $row->approved ? 'tick.png' : 'publish_x.png';
              $img = "components/com_os_cck/images/{$img}";
              $img1 = "components/com_os_cck/images/{$img1}";
              ?>
              <td width="5%" align="left">
              <?php if (JFactory::getUser()->authorise('publish_reviews', 'com_os_cck')): ?>
                  <a href="javascript: void(0);"
                     onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
              <?php endif; ?>
                      <img src="<?php echo $img; ?>" width="12" height="12" border="0"
                           alt="<?php echo $alt; ?>"/>
              <?php if (JFactory::getUser()->authorise('publish_reviews', 'com_os_cck')): ?>
                  </a>
              <?php endif; ?>
              </td>
              <td width="5%" align="left">
                <?php if (JFactory::getUser()->authorise('publish_reviews', 'com_os_cck')): ?>
                <a href="javascript: void(0);"
                   onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task1; ?>')">
                <?php endif; ?>
                    <img src="<?php echo $img1; ?>" width="12" height="12" border="0"
                         alt="<?php echo $alt1; ?>"/>
                <?php if (JFactory::getUser()->authorise('publish_reviews', 'com_os_cck')): ?>
                </a>
                <?php endif; ?>
              </td>
              <?php
              if ($row->checked_out) {
                ?>
                <td align="left"><?php echo $row->editor; ?></td>
              <?php } else { ?>
                <td align="left">&nbsp;</td>
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
      <input type="hidden" name="task" value="manage_review"/>
      <input type="hidden" name="boxchecked" value="0"/>
    </form>
    <?php
  }

  static function editReview($option, $entityInstance, $layout, $layout_params){
    global $session, $os_cck_configuration;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_SHOW') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck','edit_review');
  }

  static function showParentInstance($option, $entityInstance, $layout, $layout_params){
    global $session, $os_cck_configuration;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_SHOW') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck','parent_instance');
  }

}
