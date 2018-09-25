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

/*
This view created from default view.Content inside div#fields-block
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
$fields_from_params['cck_mail'] = unserialize($layout->mail);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . "components/com_os_cck/assets/css/editLayout.css");

//custom field
$custom_code_field = new stdClass();
$custom_code_field->label = JText::_("COM_OS_CCK_CATEGORY_UNIQUE_CODE_FIELD");
$custom_code_field->field_name = 'custom_code_field';
$custom_code_field->description = '';
//end

// //custom field
// $cck_mail = new stdClass();
// $cck_mail->label = JText::_("COM_OS_CCK_LABEL_MAIL");
// $cck_mail->field_name = 'cck_mail';
// $cck_mail->description = '';

$cck_send_button = new stdClass();
$cck_send_button->label = JText::_("COM_OS_CCK_SEND_BUTTON");
$cck_send_button->field_name = 'cck_send_button';
$cck_send_button->description = '';

$unique_fields = array($cck_send_button);
//end
?>
<div id="fields-block-title">
  <div class="fields-search"><input class="cck-main-field-search" type="text" placeholder="Search"></div>
  <div class="fields-title">Fields</div>
</div>
<!-- custom block for field -->
<div class="field-block">
  <div>
    <!-- popover back/front title -->
    <span class="field-name <?php echo $custom_code_field->field_name.'-'.$counter.'-label-hidden';
                echo  (isset($fields_from_params['showName_'.$custom_code_field->field_name.'_'.$counter])
                        || !$layout->lid)?
                       '':' hide-field-name-'.$custom_code_field->field_name.'_'.$counter;?>"
        data-field-name="<?php echo $custom_code_field->field_name.'_'.$counter; ?>">
        <?php echo $custom_code_field->label; ?>
    </span>
    <span class="col_box admin_aria_hidden">
      <?php echo '{|f-'.$custom_code_field->field_name.'_'.$counter.'|}'; ?>
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
      <span class="field-name <?php echo $field->field_name.'-label-hidden';
                  echo  (isset($fields_from_params['showName_'.$field->field_name])
                          || !$layout->lid)?
                         '':
                         ' hide-field-name-'.$field->field_name;?>"
          data-field-name="<?php echo $field->field_name; ?>">
          <?php echo $field->label; ?>
      </span>
      <span class="col_box admin_aria_hidden">
        <?php echo '{|f-'.$field->field_name.'|}'; ?>
      </span>
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
<!-- End attached block -->