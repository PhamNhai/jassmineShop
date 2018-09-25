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
$fName = $custom_code_field->db_field_name.'_'.$key;
$params = $custom_options;
$field_prefix = (isset($params[$fName.'_prefix'])) ? $params[$fName.'_prefix'] : '';
$field_suffix = (isset($params[$fName.'_suffix'])) ? $params[$fName.'_suffix'] : '';
$fld_name_show = (isset($params['showName_'.$fName])) ? 'checked="true"' : "";
$field_custom_code = (isset($params[$fName.'_custom_code'])) ? $params[$fName.'_custom_code'] : '';
$access_selected = (isset($params[$fName.'_access'])) ? $params[$fName.'_access'] : '1';
$gtree = get_group_children_tree_cck();

$selected_html   = ($params[$fName.'_custom_code_type'] == 'HTML')   ? 'selected' : '';
$selected_php    = ($params[$fName.'_custom_code_type'] == 'PHP')    ? 'selected' : '';
$selected_script = ($params[$fName.'_custom_code_type'] == 'SCRIPT') ? 'selected' : '';
$selected_css    = ($params[$fName.'_custom_code_type'] == 'CSS')    ? 'selected' : '';
$selected_text   = ($params[$fName.'_custom_code_type'] == 'TEXT')    ? 'selected' : '';
?>
<div id="options-field-<?php echo $fName?>">


  <div>
    <label><?php echo JText::_("COM_OS_CCK_LABEL_CUSTOM_CODE_TYPE")?></label>
    <select class="editor-type" rows="10" cols="30" name="code_field_unique[<?php echo $key?>][<?php echo $fName ?>_custom_code_type]">
      <option <?php echo $selected_text ?> value="TEXT">TEXT</option>'
      <option <?php echo $selected_html?> value="HTML">HTML</option>'
      <option <?php echo $selected_php?> value="PHP">PHP</option>'
      <option <?php echo $selected_script?> value="SCRIPT">SCRIPT</option>'
      <option <?php echo $selected_css?> value="CSS">CSS</option>'
    </select>
  </div>
  <div>
    <label><?php echo JText::_("COM_OS_CCK_LABEL_CUSTOM_CODE")?></label>
    <!-- <input id="add-field-mask-custom" class="new-mask" type="button" aria-invalid="false" value="+field"> -->
    <span class="editor-button">Editor</span>
    <textarea class="custom-code-editor" rows="10" cols="30" name="code_field_unique[<?php echo $key ?>][<?php echo $fName?>_custom_code]"><?php echo $field_custom_code?></textarea>
  </div>
</div>