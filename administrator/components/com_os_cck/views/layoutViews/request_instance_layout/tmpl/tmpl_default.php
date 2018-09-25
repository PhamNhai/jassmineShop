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
$counter = (count($layout_params['unique_fields']))? max(array_keys($layout_params['unique_fields']))+1 : 1;
$fields = $entity->getFieldList();
$fields_from_params = (isset($layout_params['fields']))?$layout_params['fields'] : array();
$fields_from_params['cck_mail'] = unserialize($layout->mail);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/editLayout.css");

//custom field
$custom_code_field = new stdClass();
$custom_code_field->label = JText::_("COM_OS_CCK_CATEGORY_UNIQUE_CODE_FIELD");
$custom_code_field->db_field_name = 'custom_code_field';
$custom_code_field->description = '';
//end

//custom field
// $cck_mail = new stdClass();
// $cck_mail->label = JText::_("COM_OS_CCK_LABEL_MAIL");
// $cck_mail->db_field_name = 'cck_mail';
// $cck_mail->description = '';


$cck_send_button = new stdClass();
$cck_send_button->label = JText::_("COM_OS_CCK_SEND_BUTTON");
$cck_send_button->db_field_name = 'cck_send_button';
$cck_send_button->description = '';

$unique_fields = array($cck_send_button);
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
    noticeLinkToDocLayout('request_instance', 'Request Layout', 'http://ordasoft.com/News/OS-CCK-Documentation/cck-term-explanation.html#Add request form');
  ?>
<!-- /layout notice -->

  <!-- block for main body -->
  <div id="main-block" class="row">

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
        foreach($unique_fields as $field){?>
          <!-- block for field -->
          <div class="field-block">
            <div>
              <!-- popover back/front title -->
              <span class="field-name <?php echo $field->db_field_name.'-label-hidden';
                          echo  (isset($fields_from_params['showName_'.$field->db_field_name])
                                  || !$layout->lid)?
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
        }

        //skip child selects get ids
          $params = new JRegistry;
          $childs_list = [];
          foreach ($fields as $value) {
            $params->loadString($value->params);
            $childs_list = array_merge($childs_list, explode('|',$params->get('child_select')));
          }
          $childs_list = array_unique($childs_list);
        //skip child selects get ids

        for ($i = 0; $i < count($fields); $i++) {
          $field = $fields[$i];
          //skip child selects
            if(in_array($field->fid, $childs_list))continue;
          //skip child selects 
          $required = isset($fields_from_params[$field->db_field_name.'_required']) && $fields_from_params[$field->db_field_name.'_required'] == 'on'?true:false;
          if($field->published == "1"){ ?>
          <!-- block for field -->
            <div class="field-block">
              <div>
                <?php
                if(isset($fields_from_params['description_'.$field->db_field_name])){?>
                <!--   <span class="cck-help-string" data-field-name="<?php echo $field->db_field_name?>"><?php echo $fields_from_params[$field->db_field_name.'_tooltip']?></span> -->
                  <?php
                }?>
                <!-- clear back/front title -->
                <span class="field-name
                      <?php echo $field->db_field_name.'-label-hidden';
                            echo (isset($fields_from_params['showName_'.$field->db_field_name])
                                  || !$layout->lid)?
                                   '':
                                   ' hide-field-name-'.$field->db_field_name;?>"
                      data-field-name="<?php echo $field->db_field_name; ?>"
                      data-field-type="<?php echo $field->field_type?>">
                      <?php echo $field->field_name . ($required?'*' : ''); ?>
                </span>
                <span class="col_box admin_aria_hidden" fid="<?php echo $field->fid; ?>">
                  <?php echo '{|f-'.$field->fid.'|}'; ?>
                </span>
                 <i class="f-parent" fid="<?php if($field->field_type == 'text_select_list') echo $field->fid;?>" style="display: none;"><?php if($field->field_type == 'text_select_list')echo '{|f-parent-'.$field->fid.'|}';?></i>
                <input class="f-params" name="<?php echo 'fi_Params_'.$field->db_field_name ?>" type="hidden" value="{}">
              </div>
            </div>
            <!--END block for field -->
            <?php
          }
        }
?>      
        <!-- new field block -->
        <div id="new-field-block">
          <input id="add-field" class="new-field" type="button" aria-invalid="false" value="Add New">
        </div>
        <!-- End new field block -->

        <!-- attached block -->
        <div id="attached-block">
          <div class="layout-title">Attach Modules</div>
          <input id="add-module" class="new-layout" type="button" aria-invalid="false" value="Add New">
        </div>
      </div>
      <!-- END all fields block -->

      <!-- editor main aria -->
      <div id="editor-block" class="col-lg-7 col-md-6 col-sm-8 col-xs-12">
        <div id="content-block">
          <?php echo (isset($layout->html))?$layout->html:''; ?>
        </div>
          <input class="form-params" name="form-params" type="hidden" value='<?php echo (isset($layout_params['form_params']))?$layout_params['form_params']:'{}';?>'>
          <input id="add-row" class="new-row" type="button" value="New Row" aria-invalid="false">
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
                require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'add_instance_layout', 'fieldUniq');
              }
              for ($i = 0; $i < count($fields); $i++) {
                $field = $fields[$i];
                if($field->published == "1"){
                  echo show_edite_add_form_field_layout($field, 'add', $fields_from_params,$layout->type);
                }
              }
              if(count($layout_params['unique_fields'])){
                foreach ($layout_params['unique_fields'] as $key => $custom_options){
                  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'add_instance_layout', 'customField');
                }
              } ?>
            </div>
            <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_CSS_OPTIONS") ?></h3>
            <div class="styling-field-content">
              <?php
              styling_options($layout,'f');
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
          <h3><?php echo JText::_("COM_OS_CCK_STYLING_LABEL_ACCORDION_FIELDS_MAIN_OPTIONS") ?></h3>
            <div class="main-fields-content">

              <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_LAYOUT_TITLE'); ?></label>
                <?php  print_r($layout->layout_title);?>
              </div>
      
               <div class="category-options">
                <label><?php echo JText::_('COM_OS_CCK_SHOW_LAYOUT_TITLE'); ?></label>
                <?php  print_r($layout->show_layout_title);?>
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
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'location-field-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'field-custom-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'add-field-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'layout-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'attached-module-modal');
  require getLayoutPathCCK::getAdminLayoutViewPath('com_os_cck', 'modal_snippets', 'field-mail-modal');
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
      accept: ".field-block, .attached-module-block",
      drop: function( event, ui ) {
        var draggable = ui.draggable;
        draggable.find("div:first-child").addClass("drop-item");
        
        var f_parent = draggable.find("div:first-child").find('i.f-parent');
        draggable.find("div:first-child").after('<i class="f-parent" fid="'+f_parent.attr('fid')+'" style="display: none;">'+f_parent.text()+'</i>');
        f_parent.remove();

        if(draggable.hasClass("field-block")){
          //field block
          delete_button = '<span class="delete-field"></span>';
          inform_button = '<span class="f-inform-button"></span>';
        }else if(draggable.hasClass("attached-module-block")){
          inform_button = '<span class="m-inform-button"></span>';
          delete_button = '<span class="delete-module"></span>';
        }
        draggable.find(".drop-item").prepend(inform_button);
        draggable.find(".drop-item").append(delete_button);

        //show name // test
        if(draggable.find(".field-name").length
            && draggable.find(".field-name").data("field-name").indexOf("custom_code_field_") == -1){
          jQuerCCK("input[name='fi_showName_"+draggable.find(".field-name").data("field-name")+"']").prop("checked", true);
          draggable.find(".field-name").removeClass('hide-field-name-'+draggable.find(".field-name").data("field-name"));
        }

        jQuerCCK(this).append(draggable.html());
        if(draggable.hasClass("field-block")){
          
          //field block
          del_field();
          hide_options();
          if(!jQuerCCK("#options-field-"+draggable.find(".field-name").data('field-name')).length){
            addFieldOptions(draggable.find(".field-name").data('field-name'));
          }
          makeOptions();
          jQuerCCK("#field_options #options-field-"+draggable.find(".field-name").data("field-name")).show();
        }else if(draggable.hasClass("attached-module-block")){
          
          hide_options();
          makeOptions();
          del_module();
          addHiddenModuleIds(draggable.find(".module-name").data("field-name"));//(modId)
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
    make_attached_module();
    make_field_mask();
    make_field_mask_custom();
    make_popover();
    make_remove_joomla_bars();
    make_synchronize_fields();
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
    del_field();
    del_module();
    makeDeleteRow();
    make_rows_options();
    make_accordion();
    makeOptions();
    make_add_field();
    make_colorpicker();
    make_editor();
    make_required();
    makeSearchInFields();
    jQuerCCK("#messages").removeClass('cck-spiner');
//end
  });
// --------------------------------------------------READY BLOCK END-----------------------------\\
</script>
