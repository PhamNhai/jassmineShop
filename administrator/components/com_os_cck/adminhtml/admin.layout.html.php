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

class AdminViewLayout{
  static function showLayouts($option, & $rows_item, & $list, & $search, & $pageNav, & $sort_arr){


    global $user, $doc, $app, $session;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />".JText::_('COM_OS_CCK_LAYOUTS_MANAGER')."</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $onclick = (version_compare(JVERSION, "1.6.0", "lt")) ? "checkAll(" . count($rows_item) . ");" : "Joomla.checkAll(this);";
    ?>
    <script>
      
      jQuerCCK(document).ready(function(){
        document.adminForm.reset();
      });

    </script>
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
            <?php echo $list['type']; ?>
          </td>
          <td>
            <?php echo $list['entity_list']; ?>
          </td>
          <?php if (version_compare(JVERSION, "3.0.0", "ge")) {
            ?>
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
          <th align="center" class="title" width="5%" nowrap="nowrap">ID</th>
          <th align="left" class="title" nowrap="nowrap">
          	<?php echo JText::_('COM_OS_CCK_LABEL_TITLE'); ?>
          </th>
          <th align="left" class="title" width="16%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_VIEW_TYPE'); ?></th>
          <th align="left" class="title" width="16%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY_TYPE'); ?></th>
          <th align="left" class="title" width="5%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_PUBLIC'); ?></th>
          <th align="left" class="title" width="5%" nowrap="nowrap"><?php echo "Default"; ?></th>
          <th align="left" class="title" width="5%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_CONTROL'); ?></th>
        </tr>
        <?php
        for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
          $row = & $rows_item[$i];
          ?>
          <tr class="row<?php echo $i % 2; ?>">
            <td width="3% " align="center">
              <?php if ($row->checked_out && $row->checked_out != $user->id) { ?>
                  &nbsp;
              <?php
              } else {
                echo JHTML::_('grid.id',$i, $row->lid, ($row->checked_out && $row->checked_out != $user->id), 'lid');
              }
              ?>
            </td>
            <td align="center">
              <a href="#edit_layout" onClick="return listItemTask('cb<?php echo $i; ?>','edit_layout')">
                <?php echo $row->lid; ?>
              </a>  
            </td>
            <td align="left">
              <a href="#edit_layout" onClick="return listItemTask('cb<?php echo $i; ?>','edit_layout')">
                <?php echo $row->title; ?>
              </a>
            </td>
            <?php
            $row->type = getLayoutType($row->type);
            ?>
            <td align="left"><?php echo $row->type; ?></td>
            <td align="left"><?php echo $row->entity; ?></td>
            <?php
            $task = $row->published ? 'unpublish_layouts' : 'publish_layouts';
            $alt = $row->published ? 'Unpublish' : 'Publish';
            $img = $row->published ? 'tick.png' : 'publish_x.png';
            $img = JURI::root()."administrator/components/com_os_cck/images/{$img}";
            $task1 = $row->approved ? 'unapprove_layouts' : 'approve_layouts';
            $alt1 = $row->approved ? 'Undefault' : 'Default';
            $img1 = $row->approved ? 'tick.png' : 'publish_x.png';
            $img1 = JURI::root()."administrator/components/com_os_cck/images/{$img1}";
            ?>
            <td width="5%" align="center">
              <a href="javascript: void(0);"
                 onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                  <img src="<?php echo $img; ?>" width="12" height="12" border="0"
                       alt="<?php echo $alt; ?>"/>
              </a>
            </td>
            <td width="5%" align="center">
              <a href="javascript: void(0);"
                 onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task1; ?>')">
                  <img src="<?php echo $img1; ?>" width="12" height="12" border="0"
                       alt="<?php echo $alt1; ?>"/>
              </a>
            </td>
            <?php
            if ($row->checked_out) {
              ?>
              <td align="center">Checked Out</td>
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
      <input type="hidden" name="task" value="manage_layout"/>
      <input type="hidden" name="boxchecked" value="0"/>
    </form>
  <?php
  }

  static function showLayoutsModal($option, & $rows_item){
    global $user, $doc, $app, $session, $moduleId;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />".JText::_('COM_OS_CCK_LAYOUTS_MANAGER')."</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $onclick = "Joomla.checkAll(this);";
    ?>
    <form action="index.php" method="post" name="manageLayoutModal" id="manageLayoutModal">
      <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="manageLayoutModalTable">
        <tr>
          <th align="left" class="title" nowrap="nowrap">
            <?php echo JText::_('COM_OS_CCK_LABEL_TITLE'); ?>
          </th>
          <th align="left" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_VIEW_TYPE'); ?></th>
          <th align="left" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY_TYPE'); ?></th>
          <th align="left" class="title" width="10%" nowrap="nowrap">ID</th>
        </tr>
        <?php
        for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
          $row = & $rows_item[$i];
          ?>
          <tr class="modalRow<?php echo $i % 2; ?>"
              onclick="if(window.parent)window.parent.selectLayout(<?php echo $row->lid?>,<?php echo $row->fk_eid?>,'<?php echo $row->type?>','<?php echo $moduleId?>')">
            <td class="col1">
                <?php echo $row->title; ?>
            </td>
            <?php
            $row->type = getLayoutType($row->type);
            ?>
            <td align="left"><?php echo $row->type; ?></td>
            <td align="left"><?php echo $row->entity; ?></td>
            <td class="col2"><?php echo $row->lid; ?></td>
          </tr>

          <?php
        }//end for
        ?>
      </table>
      <input type="hidden" name="option" value="<?php echo $option; ?>"/>
      <input type="hidden" name="task" value="manage_layout"/>
      <input type="hidden" name="boxchecked" value="0"/>
    </form>
  <?php
  }

  static function showLayoutsModalPlg($option, & $rows_item){
    global $user, $doc, $app, $session, $moduleId;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />".JText::_('COM_OS_CCK_LAYOUTS_MANAGER')."</div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    $onclick = "Joomla.checkAll(this);";
    ?>
    <form action="index.php" method="post" name="manageLayoutModal" id="manageLayoutModal">
      <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="manageLayoutModalTable">
        <tr>
          <th align="left" class="title" nowrap="nowrap">
            <?php echo JText::_('COM_OS_CCK_LABEL_TITLE'); ?>
          </th>
          <th align="left" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_VIEW_TYPE'); ?></th>
          <th align="left" class="title" width="15%"
              nowrap="nowrap"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY_TYPE'); ?></th>
          <th align="left" class="title" width="10%" nowrap="nowrap">ID</th>
        </tr>
        <?php
        for ($i = 0, $n = count($rows_item); $i < $n; $i++) {
          $row = & $rows_item[$i];
          ?>
          <tr class="modalRow<?php echo $i % 2; ?>"
              onclick="window.location.href = 'index.php?option=com_os_cck&task=select_data_for_editor_button&tmpl=component&lid='+<?php echo $row->lid?>">
            <td class="col1">
                <?php echo $row->title; ?>
            </td>
            <?php
            $row->type = getLayoutType($row->type);
            ?>
            <td align="left"><?php echo $row->type; ?></td>
            <td align="left"><?php echo $row->entity; ?></td>
            <td class="col2"><?php echo $row->lid; ?></td>
          </tr>

          <?php
        }//end for
        ?>
      </table>
      <input type="hidden" name="option" value="<?php echo $option; ?>"/>
      <input type="hidden" name="task" value="manage_layout"/>
      <input type="hidden" name="boxchecked" value="0"/>
    </form>
  <?php
  }

  static function showModalCckButton($option, $layout, $eiid, $cat_id){ ?>
    <form action="index.php" method="post" name="manageLayoutModal" id="manageLayoutModal">
      <h4 class="modal-title" id="attached-layout-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_LAYOUT_MODAL_TITLE")?></h4>
      <div>
        <span>
          <input id="selected_layout" type="text" readonly="" value="<?php echo $layout->lid; ?>">
        </span>
        <span>
          <a class="btn modal-button" rel="{handler: 'iframe', size: {x: 900, y: 550}}"
              href="index.php?option=com_os_cck&task=showLayoutsModalPlg&tmpl=component">
            <?php echo JText::_("COM_OS_CCK_MODAL_SELECT_LAYOUT_BUTTON")?>
          </a>
        </span>
      </div>
      <?php
      if(isset($layout->type) && ($layout->type == 'instance')){ ?>
        <div>
          <span>
            <input id="selected_instance" type="text" readonly="" value="<?php echo $eiid?>">
          </span>
          <span>
            <a class="btn modal-button" rel="{handler: 'iframe', size: {x: 900, y: 550}}"
                href="index.php?option=com_os_cck&task=showInstanceModalPlg&lid=<?php echo $layout->lid?>&fk_eid=<?php echo $layout->fk_eid?>&tmpl=component">
              <?php echo JText::_("COM_OS_CCK_MODAL_SELECT_INSTANCE_BUTTON")?>
            </a>
          </span>
        </div>
        <script type="text/javascript">
          if(<?php echo ($eiid)?$eiid : 0?> > 0){
            window.parent.cckLayoutInsert('{CCKLayout|l-<?php echo $layout->lid; ?>:CCKInstance|i-<?php echo $eiid; ?>|}');
            //window.parent.jInsertEditorText('{CCKLayout|l-<?php echo $layout->lid; ?>:CCKInstance|i-<?php echo $eiid; ?>|}');
            //window.parent.SqueezeBox.close();
          }
        </script>
        <?php
      }

      if(isset($layout->type) && ($layout->type == 'category')){?>
        <div>
          <span>
            <input id="selected_category" type="text" readonly="" value="<?php echo $cat_id?>">
          </span>
          <span>
            <a class="btn modal-button" rel="{handler: 'iframe', size: {x: 900, y: 550}}"
                href="index.php?option=com_os_cck&task=showCategoryModalPlg&lid=<?php echo $layout->lid?>&tmpl=component">
              <?php echo JText::_("COM_OS_CCK_MODAL_SELECT_CATEGORY_BUTTON")?>
            </a>
          </span>
        </div>
        <script type="text/javascript">
          if(<?php echo ($cat_id)? $cat_id : 0 ?> > 0){
            window.parent.cckLayoutInsert('{CCKLayout|l-<?php echo $layout->lid; ?>:CCKCategory|c-<?php echo $cat_id; ?>|}');
            //window.parent.jInsertEditorText('{CCKLayout|l-<?php echo $layout->lid; ?>:CCKCategory|c-<?php echo $cat_id; ?>|}');
            //window.parent.SqueezeBox.close();
          }
        </script>
        <?php
      }
      if(isset($layout->type) && $layout->type != 'category' && $layout->type != 'instance' && $layout->lid){ ?>
        <script type="text/javascript">
          window.parent.cckLayoutInsert('{CCKLayout|l-<?php echo $layout->lid; ?>|}');
          //window.parent.jInsertEditorText('{CCKLayout|l-<?php echo $layout->lid; ?>|}');
          //window.parent.SqueezeBox.close();
        </script>
        <?php
      } ?>
    </form>
    <?php
  }

  static function newLayout($option, $layout, $str_list){
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />"
                      .JText::_('COM_OS_CCK_LAYOUTS_MANAGER')." > </div>";
    $app = JFactory::getApplication();
    $app->JComponentTitle = $html;
    ?>
    <script language="javascript" type="text/javascript">
      function layout_type_select(param){
        if(document.forms["adminForm"].entity_id.value != ''){
          if (param.id == 'entity_id') {
            document.forms["adminForm"].elements["fk_eid"].value = param.options[param.selectedIndex].value;
          }
          document.forms["adminForm"].elements["task"].value = "add_new_layout";
          if(param.options[param.selectedIndex].value != '')
            document.forms["adminForm"].submit();
          return;
        }
      }
    </script>
    <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
      <table cellpadding="4" cellspacing="1" border="0" width="auto" class="adminform" id="adminform">
        <tr id="tr_entity" style="display:block;">
          <td valign="top" align="left" width="165px">
            <strong style="width=165px;"><?php echo JText::_('COM_OS_CCK_LABEL_ENTITY'); ?>: </strong>
          </td>
          <td align="left">
            <?php echo $str_list['entities_list']; ?>
          </td>
        </tr>
        <tr id="tr_select_layout" style="display:block;">
          <td valign="top" align="left" width="165px">
            <strong><?php echo JText::_('COM_OS_CCK_LABEL_SELECT_VIEW_TYPE'); ?>:</strong>
          </td>
          <td align="left">
            <?php echo $str_list['view_type_list']; ?>
          </td>
        </tr>
      <table>
      <input type="hidden" name="type" value="<?php echo $layout->type; ?>"/>
      <input type="hidden" name="fk_eid" value="<?php echo $layout->fk_eid; ?>"/>
      <input type="hidden" name="option" value="<?php echo $option; ?>"/>
      <input type="hidden" name="task" value="save_layout"/>
    </form>
    <?php
  }

  static function editLayout($option, $layout, $entity){


    global $counter;
    $layTypeText = str_replace('_', ' ', $layout->type);
    $layTypeText = ucfirst($layTypeText);
?>
    <script language="javascript" type="text/javascript">
      function trim(string) {
        return string.replace(/(^\s+)|(\s+$)/g, "");
      }

      function submitbutton(pressbutton) {
        var add = document.getElementById("categories");
        if (pressbutton == 'save' || pressbutton == 'apply') {
          var form = document.adminForm;
          if (trim(form.title.value) == '') {
            alert("<?php echo JText::_('COM_OS_CCK_ADMIN_INFOTEXT_JS_EDIT_TITLE');?>");
            return;
          }else{
            submitform(pressbutton);
          }
        }else{
          submitform(pressbutton);
        }
      }

      function delete_layout_item(a){
        a.parentNode.parentNode.remove();
      }
    </script>
    <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
      <div class="form-inner-html">
        <header class="admin-header">
          <div class="row">
            <div class="os_cck_caption col-lg-4 col-md-5 col-sm-6 col-xs-12" >
              <?php
                    echo "<img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />"
                              .JText::_('COM_OS_CCK_LAYOUTS_MANAGER').' > '.$layTypeText;
              ?>
            </div>
            
        <!-- block for title -->
              <div id="layout-title" class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
                  <input type="text" class="inputbox" placeholder="<?php echo JText::_('COM_OS_CCK_LAYOUT_PLACEHOLDER_TITLE')?>" name="title" value="<?php echo $layout->title; ?>"/>
                </div>
              <div id="title-block" class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <div id="layout-buttons" >
                  <?php
                  if ($layout->lid){?>
                    <div class="apply">
                      <input onclick="saveLayout('apply_layout');" type="button" aria-invalid="false" value="Apply">
                    </div>
                  <?php
                  }?>
                  <div class="save">
                    <input onclick="saveLayout('save_layout');" type="button" aria-invalid="false" value="Save & Close">
                  </div>
                  <div class="cancel">
                    <input onclick="saveLayout('cancel_layout')" type="button" aria-invalid="false" value="Cancel">
                  </div>
                </div>
              </div>
              <!-- END block for title -->
          </div>
        </header>
        <?php
        if($layout->type == 'instance'){
          $type = 'instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'all_instance'){
          $type = 'all_instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'calendar'){
          $type = 'calendar_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'category'){
          $type = 'category_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'request_instance'){
          $type = 'request_instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'review_instance'){
          $type = 'review_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'show_review_instance'){
          $type = 'show_review_instance';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'buy_request_instance'){
          $type = 'buy_request_instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'rent_request_instance'){
          $type = 'rent_request_instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if($layout->type == 'add_instance'){
          $type = 'add_instance_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if ($layout->type == 'search') {
          $type = 'search_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        if ($layout->type == 'all_categories') {
          $type = 'all_categories_layout';
          require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type);
        }
        ?>
      </div>
      <input type="hidden" name="type" value="<?php echo $layout->type; ?>"/>
      <input type="hidden" name="fk_eid" value="<?php echo $layout->fk_eid; ?>"/>
      <input type="hidden" name="option" value="<?php echo $option; ?>"/>
      <input type="hidden" name="task" value="save_layout"/>
    </form>
    <?php
  }

  static function updateLayoutFieldList($option, $layout, $entity){
    if($layout->type == 'instance'){
      $type = 'instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'all_instance'){
      $type = 'all_instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'calendar'){
      $type = 'calendar_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'category'){
      $type = 'category_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'request_instance'){
      $type = 'request_instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'review_instance'){
      $type = 'review_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'show_review_instance'){
      $type = 'show_review_instance';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'buy_request_instance'){
      $type = 'buy_request_instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'rent_request_instance'){
      $type = 'rent_request_instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if($layout->type == 'add_instance'){
      $type = 'add_instance_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if ($layout->type == 'search') {
      $type = 'search_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
    if ($layout->type == 'all_categories') {
      $type = 'all_categories_layout';
      require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck',$type, 'fieldList');
    }
  }
}
