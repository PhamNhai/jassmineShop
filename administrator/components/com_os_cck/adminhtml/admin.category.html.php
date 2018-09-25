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

class AdminViewCategory{

  static function show(&$rows, $userid, &$pageNav, &$lists, $type){
    global $user, $app, $templateDir, $doc;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_CATEGORIES_MANAGER') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $section = "com_os_cck";
    $onclick = "Joomla.isChecked(this.checked);";
    ?>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
      <table class="adminlist">
        <tr>
            <th width="3%" align="center">
                <input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);"/>
            </th>
            <th align="center" width="5%">
                ID
            </th>
            <th align="left" class="title" width="8%">
                <?php echo JText::_('COM_OS_CCK_HEADER_CATEGORY'); ?>
            </th>
            <th align="left" width="5%">
                <?php echo JText::_('COM_OS_CCK_HEADER_NUMBER'); ?>
            </th>

            <th align="left" width="10%">
                <?php echo JText::_('COM_OS_CCK_HEADER_PUBLISHED'); ?>
            </th>
            <?php
            if ($section <> 'content') {
                ?>
                <th align="left" colspan="2">
                    <?php echo JText::_('COM_OS_CCK_HEADER_REORDER'); ?>
                </th>
            <?php
            }

            ?>
            <th align="left" width="10%">
                <?php echo JText::_('COM_OS_CCK_HEADER_ACCESS'); ?>
            </th>
            <?php
            if ($section == 'content') {
                ?>
                <th width="12%" align="left">
                    Section
                </th>
            <?php
            }
            ?>
            <th align="left" width="12%">
                <?php echo JText::_('COM_OS_CCK_HEADER_CHECKED_OUT'); ?>
            </th>
                        <?php if (version_compare(JVERSION, "3.0.0", "ge")) {
        ?>
            <th width="12%" align="left">
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit"
                           class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo  $pageNav->getLimitBox(); ?>
                </div>
            </th>
    <?php } ?>
        </tr>
        <?php
        $k = 0;
        $i = 0;
        $n = count($rows);
        foreach ($rows as $row) {
            $img = $row->published ? 'tick.png' : 'publish_x.png';
            $task = $row->published ? 'unpublish_categories' : 'publish_categories';
            $alt = $row->published ? 'Published' : 'Unpublished';

            $rentable = ($row->rent_request == "1") ? "<span style='text-shadow: 0 -1px 0 rgba(0,0,0,0.25);color:#fff;background:#42A8C6;border-radius:11px;padding:0 5px 2px 5px;'>[" .
                JText::_("COM_OS_CCK_ALLOW_RENT_REQUEST") . "]</span>" : "";
            $buyable = ($row->buy_request == "1") ? "<span style='text-shadow: 0 -1px 0 rgba(0,0,0,0.25);color:#fff;background:#343434;border-radius:11px;padding:0 5px 2px 5px;'>[" .
                JText::_("COM_OS_CCK_ALLOW_BUY_REQUEST") . "]</span>" : "";
            if (!$row->access) {
                $color_access = 'style="color: green;"';
                $task_access = 'accessregistered_category';
            } else if ($row->access == 1) {
                $color_access = 'style="color: red;"';
                $task_access = 'accessspecial_category';
            } else {
                $color_access = 'style="color: black;"';
                $task_access = 'accesspublic_category';
            }
            $img = "components/com_os_cck/images/{$img}";
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td width="3%" align="center">
                    <?php echo JHTML::_('grid.id',$i, $row->cid, ($row->checked_out_contact_category
                                                              && $row->checked_out_contact_category != $user->id), 'cid'); ?>
                </td>
                <td align="center">
                    <?php echo $row->cid; ?>
                </td>
                <td width="35%">
                    <?php
                    if ($row->checked_out_contact_category && ($row->checked_out_contact_category != $user->id)) {

                        ?>
                        <?php echo $row->name . ' ( ' . $row->title . ' )' ?>
                        &nbsp;[ <i>Checked Out</i> ]
                        <?php
                        echo "&nbsp;" . $rentable . "&nbsp;" . $buyable;
                    } else {

                        ?>
                        <a href="#edit_ctegory"
                           onClick="return listItemTask('cb<?php echo $i; ?>','edit_category')">
                            <?php echo $row->name . ' ( ' . $row->title . ' )'; ?>
                        </a>
                        <?php
                        echo "&nbsp;" . $rentable . "&nbsp;" . $buyable;
                    }

                    ?>
                </td>
                <td align="left">
                    <?php echo $row->nentity; ?>
                </td>

                <td align="left">
                    <?php if(JFactory::getUser()->authorise('publish_categories', 'com_os_cck')
                            || JFactory::getUser()->authorise('unpublish_categories', 'com_os_cck')): ?>
                    <a href="javascript: void(0);"
                       onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                    <?php endif; ?>
                        <img src="<?php echo $img; ?>" width="12" height="12" border="0"
                             alt="<?php echo $alt; ?>"/>
                    <?php if(JFactory::getUser()->authorise('publish_categories', 'com_os_cck')
                        || JFactory::getUser()->authorise('unpublish_categories', 'com_os_cck')): ?>
                    </a>
                    <?php endif; ?>
                </td>
                <!-- old td  >
  <?php echo $i. $pageNav->orderUpIcon($i);?>
  </td>
  <td>
  <?php echo $i. "::".$n.$pageNav->orderDownIcon($i, $n);?>
  </td-->
                <td align="left">
                    <?php
                    echo catOrderUpIcon($row->ordering - 1, $i);?>
                </td>
                <td align="left">
                    <?php
                    echo catOrderDownIcon($row->ordering - 1, $row->all_fields_in_list, $i);?>
                </td>

                <td align="left">
                    <?php echo $row->groups; ?>
                </td>
                <td width="35%">
                    <?php echo $row->checked_out_contact_category ? $row->editor : ""; ?>
                </td>
                <td></td>
                <?php
                $k = 1 - $k;

                ?>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;
        }
        ?>
        <tr>
            <td colspan="11"><?php echo $pageNav->getListFooter(); ?></td>
        </tr>
      </table>
      <input type="hidden" name="option" value="com_os_cck"/>
      <input type="hidden" name="section" value="categories"/>
      <input type="hidden" name="task" value="show_categories"/>
      <input type="hidden" name="chosen" value=""/>
      <input type="hidden" name="act" value=""/>
      <input type="hidden" name="boxchecked" value="0"/>
      <input type="hidden" name="type" value="<?php echo $type; ?>"/>
    </form>
  <?php
  }

  static function showModal(&$rows, $userid, &$pageNav, &$lists, $type){
    global $user, $app, $templateDir, $doc, $moduleId;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_CATEGORIES_MANAGER') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $section = "com_os_cck";
    $onclick = "Joomla.isChecked(this.checked);";
    ?>
    <form action="index.php" method="post" name="manageCategoryModal" id="manageCategoryModal">
    <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
      <table class="manageCategoryModalTable">
        <tr>
            <th align="left" class="title">
                <?php echo JText::_('COM_OS_CCK_HEADER_CATEGORY'); ?>
            </th>
            <th align="left" width="5%">
                <?php echo JText::_('COM_OS_CCK_HEADER_NUMBER'); ?>
            </th>
            <th align="left" width="10%">
                <?php echo JText::_('COM_OS_CCK_HEADER_ACCESS'); ?>
            </th>
            <?php
            if ($section == 'content') {
                ?>
                <th width="12%" align="left">
                    Section
                </th>
            <?php
            }
            ?>
            <th align="left" width="12%">
                ID
            </th>
        </tr>
        <?php
        $k = 0;
        $i = 0;
        $n = count($rows);
        foreach ($rows as $row){ ?>
            <tr onclick="if(window.parent)window.parent.selectCategory(<?php echo $row->cid?>,'<?php echo $moduleId?>')" class="modalRow<?php echo $i % 2; ?>">
                <td width="35%">
                  <?php
                    echo $row->name . ' ( ' . $row->title . ' )';
                  ?>
                </td>
                <td align="left">
                    <?php echo $row->nentity; ?>
                </td>
                <td align="left">
                    <?php echo $row->groups; ?>
                </td>
                <td align="left">
                    <?php echo $row->cid; ?>
                </td>
                <?php
                $k = 1 - $k;
                ?>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;
        }
        ?>
        <tr>
            <td colspan="11"><?php echo $pageNav->getListFooter(); ?></td>
        </tr>
      </table>
      <input type="hidden" name="option" value="com_os_cck"/>
      <input type="hidden" name="section" value="categories"/>
      <input type="hidden" name="task" value="show_categories"/>
      <input type="hidden" name="chosen" value=""/>
      <input type="hidden" name="act" value=""/>
      <input type="hidden" name="boxchecked" value="0"/>
      <input type="hidden" name="type" value="<?php echo $type; ?>"/>
    </form>
  <?php
  }

  static function showCategoryModalPlg(&$rows, $userid, &$pageNav, &$lists, $type, $lid){
    global $user, $app, $templateDir, $doc, $moduleId;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_CATEGORIES_MANAGER') . "</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $section = "com_os_cck";
    $onclick = "Joomla.isChecked(this.checked);";
    ?>
    <form action="index.php" method="post" name="manageCategoryModal" id="manageCategoryModal">
    <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
      <table class="manageCategoryModalTable">
        <tr>
            <th align="left" class="title">
                <?php echo JText::_('COM_OS_CCK_HEADER_CATEGORY'); ?>
            </th>
            <th align="left" width="5%">
                <?php echo JText::_('COM_OS_CCK_HEADER_NUMBER'); ?>
            </th>
            <th align="left" width="10%">
                <?php echo JText::_('COM_OS_CCK_HEADER_ACCESS'); ?>
            </th>
            <?php
            if ($section == 'content') {
                ?>
                <th width="12%" align="left">
                    Section
                </th>
            <?php
            }
            ?>
            <th align="left" width="12%">
                ID
            </th>
        </tr>
        <?php
        $k = 0;
        $i = 0;
        $n = count($rows);
        foreach ($rows as $row){ ?>
          <tr class="modalRow<?php echo $i % 2; ?>"
            onclick="window.location.href = 'index.php?option=com_os_cck&task=select_data_for_editor_button&tmpl=component&lid='+<?php echo $lid?>+'&cat_id='+<?php echo $row->cid?>">
            <td width="35%">
              <?php
                echo $row->name . ' ( ' . $row->title . ' )';
              ?>
            </td>
            <td align="left">
                <?php echo $row->nentity; ?>
            </td>
            <td align="left">
                <?php echo $row->groups; ?>
            </td>
            <td align="left">
                <?php echo $row->cid; ?>
            </td>
            <?php
            $k = 1 - $k;
            ?>
          </tr>
          <?php
          $k = 1 - $k;
          $i++;
        }
        ?>
        <tr>
            <td colspan="11"><?php echo $pageNav->getListFooter(); ?></td>
        </tr>
      </table>
      <input type="hidden" name="option" value="com_os_cck"/>
      <input type="hidden" name="section" value="categories"/>
      <input type="hidden" name="task" value="show_categories"/>
      <input type="hidden" name="chosen" value=""/>
      <input type="hidden" name="act" value=""/>
      <input type="hidden" name="boxchecked" value="0"/>
      <input type="hidden" name="type" value="<?php echo $type; ?>"/>
    </form>
  <?php
  }

  /**
   * Writes the edit form for new and existing categories
   *
   * @param  $ The category object
   * @param string $
   * @param array $
   */
  static function edit(&$row, $section, &$lists, $redirect)
  {

      global $user, $app, $option;
      $doc = JFactory::getDocument();
      $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_CATEGORIES_MANAGER') . "</div>";
      $app = JFactory::getApplication();
      $app->JComponentTitle = $html;

      if ($row->image == "") {
          $row->image = 'blank.png';
      }
      mosMakeHtmlSafe($row, ENT_QUOTES, 'description');

      ?>
      <script language="javascript" type="text/javascript">
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

          Joomla.submitbutton = function (pressbutton, section) {
              var form = document.adminForm;
              if (pressbutton == 'cancel_category') {
                  submitform(pressbutton);
                  return;
              }
              if(pressbutton == 'save_category'){
              }
              if(form.name.value == ""){
                window.scrollTo(0,findPosY(form.name)-100);
                form.name.placeholder = '<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_TITLE');?>';
                form.name.style.borderColor = "#FF0000";
                return;
              }else if (form.title.value == "") {
                  window.scrollTo(0,findPosY(form.title)-100);
                  form.title.placeholder = '<?php echo JText::_('');?>';
                  form.title.style.borderColor = "#FF0000";
                  return;
              } else {
                  <?php getEditorContents('editor1', 'description') ;?>
                  submitform(pressbutton);
              }
          }
      </script>

      <form action="index.php" method="post" name="adminForm" id="adminForm">
          <table width="100%">
              <tr>
                  <td valign="top">


                      <table class="adminform" width="100%">
                          <tr>
                              <td width="20%" align="left">
                                  <?php echo JText::_('COM_OS_CCK_CATEGORIES_HEADER_TITLE'); ?>:
                              </td>
                              <td align="left">
                                  <input class="text_area" type="text" name="title" value="<?php echo $row->title; ?>"
                                         size="50" maxlength="250" title="A short name to appear in menus"/>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <?php echo JText::_('COM_OS_CCK_CATEGORIES_HEADER_NAME'); ?>:
                              </td>
                              <td>
                                  <input class="text_area" type="text" name="name" value="<?php echo $row->name; ?>"
                                         size="50" maxlength="250" title="A short name to appear in menus"/>
                              </td>
                          </tr>
                          <tr>
                              <td align="left">
                                  <?php echo JText::_('COM_OS_CCK_CATEGORIES_PARENTITEM'); ?>:
                              </td>
                              <td>
                                  <?php echo $lists['parent']; ?>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <?php  echo JText::_('COM_OS_CCK_CATEGORIES_HEADER_IMAGE');?>:
                              </td>
                              <td>
                                  <?php
                                  if(substr_count($lists['image'], '<option') == 1){
                                      echo $lists['image'] . '<span style="font-size: 12px; position: absolute;">'.
                                          JText::_('COM_OS_CCK_CATEGORY_LOAD_IMAGE').'<span>';
                                  }else{
                                      echo $lists['image'];
                                  }
                                  ?>
                                  <script language="javascript" type="text/javascript">
                                      var selVal = document.getElementById('image');
                                      if (selVal.options[selVal.selectedIndex].value != ''){
                                          jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
                                      }
                                      else{
                                          jsimg='components/com_os_cck/images/blank.png';
                                      }
                                      document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" '+
                                                     'border="2" alt="<?php echo JText::_('COM_OS_CCK_CATEGORIES_IMAGEPREVIEW');?>" />');
                                  </script>
                              </td>
                          </tr>
                          <tr>
                              <td align="left">
                                  <?php echo JText::_('COM_OS_CCK_HEADER_ACCESS'); ?>:
                              </td>
                              <td>
                                  <?php echo $lists['category']['registrationlevel']; ?>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <?php echo JText::_('COM_OS_CCK_HEADER_PUBLISHED'); ?>:
                              </td>
                              <td>
                                  <?php echo $lists['published']; ?>
                              </td>
                          </tr>
                          <tr>
                              <td valign="top">
                                  <?php echo JText::_('COM_OS_CCK_CATEGORIES_DETAILS'); ?>:
                              </td>
                              <td colspan="2">
                                  <?php
                                  // parameters : areaname, content, hidden field, width, height, rows, cols
                                  editorArea('editor1', $row->description, 'description', '500', '200', '50', '5');
                                  ?>
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
          </table>

          <input type="hidden" name="option" value="com_os_cck"/>
          <input type="hidden" name="section" value="categories"/>
          <input type="hidden" name="task" value=""/>
          <input type="hidden" name="cid" value="<?php echo $row->cid; ?>"/>
          <!--input type="hidden" name="sectionid" value="com_os_cck" /-->
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>"/>
      </form>
  <?php
  }

}
