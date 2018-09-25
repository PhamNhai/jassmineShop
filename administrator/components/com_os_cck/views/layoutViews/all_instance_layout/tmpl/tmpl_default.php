<?php
defined('_JEXEC') or die('Restricted access');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/


//getting all params
$layout_params = unserialize($layout->params);
$layout_params['unique_fields'] = ($layout->custom_fields)?unserialize($layout->custom_fields):array();
$rowsNames = array();
if(isset($layout_params['row_ids']) && !empty($layout_params['row_ids'])){
  $rowsIds= explode('|',$layout_params['row_ids']);
  foreach ($rowsIds as $value) {
    if(!empty($value)){
      $rowName = new stdClass();
      $rowName->field_name = 'row_'.$value;
      $rowsNames[] = $rowName;
    }
  }
}else{
  $layout_params['row_ids'] = '';
}
$columnNames = array();
if(isset($layout_params['column_ids']) && !empty($layout_params['column_ids'])){
  $columnIds = explode('|',$layout_params['column_ids']);
  foreach ($columnIds as $value) {
    if(!empty($value)){
      $columnName = new stdClass();
      $columnName->field_name = 'col_'.$value;
      $columnNames[] = $columnName;
    }
  }
}else{
  $layout_params['column_ids'] = '';
}
//get unique max array key
$counter = (count($layout_params['unique_fields']))? max(array_keys($layout_params['unique_fields']))+1 : 1 ;
$fields = $entity->getFieldList();
$fields_from_params = (isset($layout_params['fields']))?$layout_params['fields']:array();
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/editLayout.css");

//custom category field
$all_instance_map = new stdClass();
$all_instance_map->label = JText::_("COM_OS_CCK_ALL_INSTANCE_UNIQUE_FIELD_MAP");
$all_instance_map->db_field_name = 'all_instance_map';
$all_instance_map->description = 'Map for all instance';

$all_instance_order_by = new stdClass();
$all_instance_order_by->label = JText::_("COM_OS_CCK_CATEGORY_UNIQUE_FIELD_CAT_ORDERING");
$all_instance_order_by->db_field_name = 'all_instance_order_by';
$all_instance_order_by->description = 'All instance Order by block for category';

$all_instance_pagination = new stdClass();
$all_instance_pagination->label = JText::_("COM_OS_CCK_CATEGORY_UNIQUE_FIELD_PAGINATION");
$all_instance_pagination->db_field_name = 'joom_pagination';
$all_instance_pagination->description = 'Pagination block for this layout';

$all_instance_alphabetical = new stdClass();
$all_instance_alphabetical->label = 'Alphabetical';
$all_instance_alphabetical->db_field_name = 'joom_alphabetical';
$all_instance_alphabetical->description = 'Alphabetical pagination block for this layout';

$unique_fields = array($all_instance_order_by, 
                       $all_instance_map, 
                       $all_instance_pagination, 
                       $all_instance_alphabetical);
//end

//custom field
$custom_code_field = new stdClass();
$custom_code_field->label = JText::_("COM_OS_CCK_CATEGORY_UNIQUE_CODE_FIELD");
$custom_code_field->db_field_name = 'custom_code_field';
$custom_code_field->description = '';
//end
?>
<div id="messages" class="cck-spiner">
<div class="spiner-bg"></div>
  <div class="sk-cube sk-cube1"></div>
  <div class="sk-cube sk-cube2"></div>
  <div class="sk-cube sk-cube3"></div>
  <div class="sk-cube sk-cube4"></div>
  <div class="sk-cube sk-cube5"></div>
  <div class="sk-cube sk-cube6"></div>
  <div class="sk-cube sk-cube7"></div>
  <div class="sk-cube sk-cube8"></div>
  <div class="sk-cube sk-cube9"></div>
<span id="result-message"></span></div>

<!-- START main drag&drop aria -->
<div class="container-fluid">

<!-- layout notice -->
<?php 
  noticeLinkToDocLayout('all_instance_layout', 'All Instance Layout', 'http://ordasoft.com/News/OS-CCK-Documentation/cck-term-explanation.html#Show Layout');
?>
<!-- /layout notice -->

  <!-- block for main body -->
  <div id="main-block" class="row">
      <!-- block for title -->
      <!-- all fields block -->
      <div id="fields-block" class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
        <div id="fields-block-title">
          <div class="fields-search"><input class="cck-main-field-search" type="text" placeholder="Search"></div>
          <div class="fields-title">Fields</div>
        </div>
        <!-- custom block for field -->
        <div class="field-block">
          <div>
            <!-- popover back/front title -->
            <span class="field-name <?php echo $custom_code_field->db_field_name.'-'.$counter.'-label-hidden';
                        echo  (isset($fields_from_params['showName_'.$custom_code_field->db_field_name.'_'.$counter])
                                || !$layout->lid)?
                               '':
                               ' hide-field-name-'.$custom_code_field->db_field_name.'_'.$counter;?>"
                data-field-name="<?php echo $custom_code_field->db_field_name.'_'.$counter; ?>">
                <?php echo $custom_code_field->label; ?>
            </span>
            <span class="col_box admin_aria_hidden">
              <?php echo '{|f-'.$custom_code_field->db_field_name.'_'.$counter.'|}'; ?>
            </span>
          </div>
        </div>
        <!--END custom block for field -->
        <?php
        foreach($unique_fields as $field) {?>
          <!-- block for field -->

          <div class="field-block">
            <div>
              <!-- popover back/front title -->
              <span class="field-name <?php echo $field->db_field_name.'-label-hidden';
                          echo  (isset($fields_from_params['showName_'.$field->db_field_name]))?
                                 '':
                                 ' hide-field-name-'.$field->db_field_name;?>"
                  data-field-name="<?php echo $field->db_field_name; ?>">
                  <?php echo $field->label; ?>
              </span>
              <span class="col_box admin_aria_hidden">
                <?php echo '{|f-'.$field->db_field_name.'|}'; ?>
              </span>
              <input class="f-params" name="<?php echo 'fi_Params_'.$field->db_field_name ?>" type="hidden" value="{}">
            </div>
          </div>
          <!--END block for field -->
          <?php
        } ?>
        <!-- attached block -->
        <div id="attached-block">
          <div class="layout-title">Attach Layouts</div>
          <input id="add-layout" class="new-layout" type="button" aria-invalid="false" value="Add New">

          <div class="layout-title">Attach Modules</div>
          <input id="add-module" class="new-layout" type="button" aria-invalid="false" value="Add New">
        </div>
        <!-- End attached block -->
      </div>
      <!-- END all fields block -->

      <!-- editor main aria -->
      <div id="editor-block" class="col-lg-7 col-md-6 col-sm-8 col-xs-12">
        <div id="content-block">
          <?php echo $layout->html; ?>
        </div>
        <input class="form-params" name="form-params" type="hidden" value='<?php echo (isset($layout_params['form_params']))?$layout_params['form_params']:'{}';?>'>
        <input id="add-row" class="new-row" type="button" aria-invalid="false" value="New Row">
      </div>
      <!--END editor main aria -->

      <!-- block for options -->
      <div id="field_options" class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
        <ul>
          <li><a href="#options-tab-1"><?php echo JText::_("COM_OS_CCK_EDIT_LAYOUT_FIELD_OPTION")?></a></li>
          <li><a href="#options-tab-2"><?php echo JText::_("COM_OS_CCK_EDIT_LAYOUT_BLOCK_OPTION")?></a></li>
          <li><a href="#options-tab-3"><?php echo JText::_("COM_OS_CCK_EDIT_LAYOUT_FORM_OPTION")?></a></li>
        </ul>
        <!-- START OPTIONS TABS -->
        <div id="options-tab-1">
          <div id="fields-options-accordion">
            <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_MAIN_OPTIONS") ?></h3>
            <div class="main-fields-content">
              <?php
              foreach ($unique_fields as $field) {
                require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'all_instance_layout', 'fieldUniq');
              }
              if(isset($layout_params['views']['show_request_layout'])){
                foreach ($layout_params['views']['show_request_layout'] as $key => $value) {
                  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'all_instance_layout', 'layoutOptions');
                }
              }
              if(count($layout_params['unique_fields'])){
                foreach ($layout_params['unique_fields'] as $key => $custom_options){
                  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'all_instance_layout', 'customField');
                }
              } ?>
            </div>
            <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_CSS_OPTIONS") ?></h3>
            <div class="styling-field-content">
              <?php
                styling_options($layout,'f','block');
              ?>
            </div>
          </div>
        </div>
        <div id="options-tab-2">
          <div id="block-options-accordion">
            <h3 id="row-styling"><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_ROW_CSS_OPTIONS")?></h3>
            <div class="styling-row-content">
              <?php styling_options($layout,'row');?>
            </div>
            <h3 id="column-styling"><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_COLUMN_CSS_OPTIONS")?></h3>
            <div class="styling-column-content">
              <?php styling_options($layout,'col');?>
            </div>
          </div>
        </div>
        <div id="options-tab-3">
          <div id="form-options-accordion">


          <h3><?php echo JText::_("COM_OS_CCK_LAYOUT_COUNT_INST_GRID") ?></h3>
            <div class="main-fields-content">

               <!-- main one -->
              <div class="category-options g_instance_grid">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_COUNT_INST_GRID'); ?></label>
                <?php  echo $layout->instance_grid;?>
              </div>
              <!-- main one -->

               <!-- main two -->
              <div class="category-options g_auto_custom">
               <label><?php echo JText::_('COM_OS_CCK_LAYOUT_CALCULATION_TYPE'); ?></label>
                <?php  echo $layout->auto_custom;?>
              </div>
              <!-- main two -->
              <div class="g_auto">
                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_COUNT_INST_COLUNMS'); ?></label>
                  <?php  echo $layout->count_inst_columns;?>
                </div>
                
                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_MIN_WIDTH'); ?></label>
                  <?php  echo $layout->lay_min_width;?>
                </div>

                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_SPACE_BETWEEN'); ?></label>
                  <?php  echo $layout->space_between;?>
                </div>
              </div>

              <div class="category-options g_custom">
                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_RESOLUTION_ONE'); ?></label>
                  <span style="text-valign;middle;" ><?php  echo $layout->resolition_one;?></span>
                </div>

                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_RESOLUTION_TWO'); ?></label>
                  <?php  echo $layout->resolition_two;?>
                </div>

                <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_RESOLUTION_THREE'); ?></label>
                  <?php  echo $layout->resolition_three;?>
                </div>

                 <div class="category-options">
                  <label><?php echo JText::_('COM_OS_CCK_LAYOUT_RESOLUTION_FOUR'); ?></label>
                  <?php  echo $layout->resolition_four;?>
                </div>
              </div>

            </div>




          <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_MAIN_OPTIONS") ?></h3>
            <div class="main-fields-content">

             
              <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_TITLE'); ?></label>
                <?php  echo $layout->layout_title;?>
              </div>
  
               <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_SHOW_LAYOUT_TITLE'); ?></label>
                <?php  echo $layout->show_layout_title;?>
              </div>
             <!--  <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_SORT_BY'); ?></label>
                <?php echo $layout->indexed; ?>
              </div> -->


              <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_FEATURED'); ?>
                   <i title="<?php echo JText::_("COM_OS_CCK_LAYOUT_FEATURED_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i>
                </label>
                <?php echo $layout->featured; ?>
              </div>


           <!--    <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_ORDER_BY'); ?></label>
                <?php echo $layout->sortType; ?>
              </div>
              <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_ORDER_BY_FIELDS'); ?></label>
                <?php echo $layout->orderByFields; ?>
              </div> -->
              <div class="category-options">
                <label><?php echo JText::_("COM_OS_CCK_LAYOUT_INSTANCE_LIMIT"); ?> 
                <i title="<?php echo JText::_("COM_OS_CCK_LAYOUT_INSTANCE_LIMIT_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
                <input type="number" name="vi_limit" value="<?php echo $layout->limit ?>" />
              </div>
              <div class="category-options">
                <label><?php echo JText::_("COM_OS_CCK_LAYOUT_PAGE"); ?></label>
                <input type="text" name="vi_pagenator_limit"
                       value="<?php echo (isset($layout_params['views']['pagenator_limit'])) ? $layout_params['views']['pagenator_limit'] : "10"; ?>">
              </div>
              <div class="category-options">
                <label><?php echo JText::_("COM_OS_CCK_LABEL_LINK_FIELD"); ?> <i title="<?php echo JText::_("COM_OS_CCK_LABEL_LINK_FIELD_TOOLTIP")?>" class="glyphicon glyphicon-info-sign date_tooltip"></i></label>
                <?php echo $layout->link_field; ?>
              </div>

              <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_HEADER_ITEM_LAYOUT'); ?></label>
                <?php echo $layout->instanceLayout; ?>
              </div>

            </div>
            <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_CSS_OPTIONS") ?></h3>
            <div class="styling-field-content">
              <?php styling_options($layout,'form');?>
            </div>
          </div>
        </div>
        <!-- END options tabs -->
      </div>
  </div>
</div>
<!-- END main drag-drop aria -->


<!-- ADD MODALS -->
<?php
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'editor-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'layout-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'attached-layout-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'attached-module-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'styling-modal');
?>
<!-- ADD MODALS -->


<!-- hidden option block -->
<input type="hidden" name="vi_instance_type" value="add"/>
<input id="lid" type="hidden" name="lid" value="<?php echo $layout->lid; ?>"/>
<input id="attached_module_ids" type="hidden" name="attached_module_ids"
      value="<?php echo (isset($layout_params['attachedModuleIds']))?$layout_params['attachedModuleIds'] : '' ;?>">
<input id="row_ids" type="hidden" name="row_ids" value="<?php echo $layout_params['row_ids']; ?>">
<input id="column_ids" type="hidden" name="column_ids" value="<?php echo $layout_params['column_ids']; ?>">
<!-- end hidden option -->
<?php
require_once(JPATH_SITE . "/administrator/components/com_os_cck/views/editLayoutFunctions.php");
?>
<!-- script for drop joomla menu and buttons sidebar -->
<script type="text/javascript">
  //counters block
  var cust_count = <?php echo $counter?>;
//end
  //fn drop for rows
  function make_droppable(){
    jQuerCCK(".drop-area").droppable({
      activeClass: "activeDroppable",
      accept: ".field-block, .attached-layout-block, .attached-module-block",
      drop: function( event, ui ) {
        var draggable = ui.draggable;
        draggable.find("div:first-child").addClass("drop-item");
        if(draggable.hasClass("field-block")){
          //field block
          delete_button = '<span class="delete-field"></span>';
          inform_button = '<span class="f-inform-button"></span>';
        }else if(draggable.hasClass("attached-module-block")){
          inform_button = '<span class="m-inform-button"></span>';
          delete_button = '<span class="delete-module"></span>';
        }else{
          //attached layout block
          inform_button = '<span class="l-inform-button"></span>';
          delete_button = '<span class="delete-layout"></span>';
        }
        draggable.find(".drop-item").prepend(inform_button);
        draggable.find(".drop-item").append(delete_button);
        jQuerCCK(this).append(draggable.html());
        if(draggable.hasClass("field-block")){
          //field block
          
          del_field();
          hide_options();
          makeOptions();
          jQuerCCK("#field_options div[id^='options-field-']").hide();
          jQuerCCK("#field_options #options-field-"+draggable.find(".field-name").data("field-name")).show();
        }else if(draggable.hasClass("attached-module-block")){
          
          hide_options();
          makeOptions();
          del_module();
          addHiddenModuleIds(draggable.find(".module-name").data("field-name"));//(modId)
        }else{
          //attached layout block
          
          addOptionForLayout(draggable.find(".layout-name").data("field-name"));
          del_layout();
          hide_options();
          makeOptions();
          make_showHideTitle();
          jQuerCCK("#field_options div[id^='options-field-']").hide();
          jQuerCCK("#field_options #options-field-"+draggable.find(".layout-name").data("field-name")).show();
        }
        if(draggable.find(".field-name").length
            && draggable.find(".field-name").data("field-name").indexOf("custom_code_field_") >= 0){
          //if we drag custom field
          //start block with cutom_field_counter//we change field data-name,class and inner hidden text
          cust_count++;
          //delete current class
          draggable.find(".field-name").removeClass("hide-field-name-"+jQuerCCK(this).find(".field-name").data("field-name"));
          //delete previos class
          draggable.find(".field-name").removeClass("hide-field-name-custom_code_field_"+(cust_count-1));
          draggable.find(".field-name").removeClass("custom_code_field-"+(cust_count-1)+"-label-hidden");
          //add new class
          draggable.find(".field-name").addClass("hide-field-name-custom_code_field_"+cust_count);
          draggable.find(".field-name").addClass("custom_code_field-"+(cust_count)+"-label-hidden");
          //change curent data-field-name
          draggable.find(".field-name").attr("data-field-name","custom_code_field_"+(cust_count));
          //change current hfield hidden
          draggable.find(".admin_aria_hidden").text("{|f-custom_code_field_"+cust_count+"|}");
          //remove x buttons
          draggable.find(".f-inform-button").remove();
          draggable.find(".delete-field").remove();
          //end
          //start dinamic create unique option
          add_unique_option(cust_count);
          make_showHideTitle();
          hide_options();
          makeOptions();
          jQuerCCK("#field_options #options-field-custom_code_field_"+(cust_count-1)).show();
          //end
        }else if(draggable.find(".field-name").length
            && (draggable.find(".field-name").data("field-name") == "joom_pagination"
                        || draggable.find(".field-name").data("field-name") == "joom_alphabetical"
                        )){
           //field block
          del_field();
          hide_options();
          makeOptions();
          jQuerCCK("#field_options div[id^='options-field-']").hide();
          jQuerCCK("#field_options #options-field-"+draggable.find(".field-name").data("field-name")).show();
          //remove x buttons
          draggable.find(".f-inform-button").remove();
          draggable.find(".delete-field").remove();
          draggable.find("div:first-child").removeClass("drop-item")
        }else{
          //remove dragable field from field block
          draggable.remove();
        }
      }
    });
  }
//end

// --------------------------------------------------READY BLOCK START-----------------------------\\
  jQuerCCK( document ).ready(function() {
    //make fun-s
    make_add_row();
    make_attached_layout();
    make_attached_module();
    make_popover();
    make_remove_joomla_bars();
    make_synchronize_fields();
    make_synchronize_layout();
    //resizable
    jQuerCCK('[id^=cck_col-]').addClass('resizable');
    jQuerCCK('.ui-resizable-handle').remove();
    make_resize_grid();
    //resizable
    make_sortable();
    make_droppable();
    makeTabs();
    make_draggable();
    make_showHideTitle();
    make_accordion();
    del_field();
    del_module();
    makeOptions();
    makeDeleteRow();
    del_layout();
    make_colorpicker();
    make_editor();
    makeSearchInFields();
    jQuerCCK("#messages").removeClass('cck-spiner');

    function grid_panel(){
      jQuerCCK('.g_auto, .g_custom, .g_auto_custom').hide();
      if(jQuerCCK('.g_instance_grid select').val() == 1){
        jQuerCCK('.g_auto, .g_custom, .g_auto_custom').show();

        if(jQuerCCK('.g_auto_custom select').val() == 'auto'){
          jQuerCCK('.g_auto').show()
          jQuerCCK('.g_custom').hide()
        }else{
          jQuerCCK('.g_custom').show()
          jQuerCCK('.g_auto').hide()
        }
      }  
      return;
    }

    grid_panel();
      
    jQuerCCK('.g_instance_grid select, .g_auto_custom select').change(function(event) {
      grid_panel();
    });
    
//end
  });
// --------------------------------------------------READY BLOCK END-----------------------------\\
</script>
