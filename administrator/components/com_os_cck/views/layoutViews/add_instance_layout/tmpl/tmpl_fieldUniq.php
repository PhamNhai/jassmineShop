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
$fName = $field->db_field_name;
$mail_value      = (isset($fields_from_params['cck_mail'][$fName.'_body'])) ? $fields_from_params['cck_mail'][$fName.'_body'] : '';
$mail_subject    = (isset($fields_from_params['cck_mail'][$fName.'_subject'])) ? $fields_from_params['cck_mail'][$fName.'_subject'] : '';
$mail_recipient  = (isset($fields_from_params['cck_mail'][$fName.'_recipient'])) ? $fields_from_params['cck_mail'][$fName.'_recipient'] : '';
$mail_encoding   = (isset($fields_from_params['cck_mail'][$fName.'_encoding'])) ? $fields_from_params['cck_mail'][$fName.'_encoding'] : '';
$access_selected = (isset($fields_from_params['cck_mail'][$fName.'_access'])) ? $fields_from_params['cck_mail'][$fName.'_access'] : '1';
$buttonText = isset($layout_params["views"]["layout_button_text"])?$layout_params["views"]["layout_button_text"]:'';
$gtree = get_group_children_tree_cck();
$options[]  = JHTML::_('select.option','1','HTML');
$options[]  = JHTML::_('select.option','0','Text');
?>
<div id="options-field-<?php echo $fName?>">
    <?php 
    if($fName == "cck_mail"){?>

        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_MAIL_ENCODING")?></label>
            <?php echo JHTML::_('select.genericlist',$options, 'cck_mail['.$fName.'_encoding]',
                                'size="1" class="inputbox" ', 'value', 'text', $mail_encoding)?>
        </div>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_MAIL_RECIPIENT")?></label>
            <input type="text" placeholder="<?php echo JText::_('COM_OS_CCK_PLACEHOLDER_MAIL_RECIPIENT')?>" name="cck_mail[<?php echo $fName?>_recipient]"  value="<?php echo $mail_recipient?>">
        </div>
        <div>
            <label><?php echo JText::_("COM_OS_CCK_LABEL_MAIL_SUBJECT")?></label>
            <input type="text" placeholder="<?php echo JText::_('COM_OS_CCK_PLASEHOLDER_MAIL_SUBJECT')?>" name="cck_mail[<?php echo $fName?>_subject]"  value="<?php echo $mail_subject?>">
        </div>
        <div>
            <input id="add-field-mask" class="new-mask" type="button" aria-invalid="false" value="+field">
            <label><?php echo JText::_("COM_OS_CCK_LABEL_MAIL_OPTIONS")?></label>
            <textarea id="<?php echo $fName?>_body" placeholder="<?php echo JText::_('COM_OS_CCK_PLASEHOLDER_MAIL_BODY')?>" rows="10" cols="30" name="cck_mail[<?php echo $fName?>_body]" ><?php echo $mail_value?></textarea>
        </div>
    <?php 
    }else{?>
        <div>
            <label><?php echo JText::_('COM_OS_CCK_LAYOUT_BUTTON_TEXT')?></label>
            <input type="text" value="<?php echo $buttonText?>" placeholder="Type form button text...." name="vi_layout_button_text">
        </div>
    <?php 
    }?>
</div>