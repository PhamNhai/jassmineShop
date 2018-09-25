<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

if (JVERSION >= '3.4.0') {
    JHtml::_('behavior.formvalidator');
} else {
    JHtml::_('behavior.formvalidation');
}
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if (task == "form.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://maps.google.com/maps/api/js?libraries=places" type="text/javascript"></script>
<script src="components/com_baforms/assets/js/ba-email.js" type="text/javascript"></script>
<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script>
<link rel="stylesheet" href="components/com_baforms/assets/css/ba-admin.css" type="text/css"/>
<div id="fields-editor" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <h3><?php echo JText::_('FIELDS'); ?></h3>
    </div>
    <div class="modal-body">
        <div class="search-bar">
            <input type="text" class="ba-search" placeholder="<?php echo JText::_('SEARCH'); ?>">
        </div>
        <div class="forms-table">
            <table class="forms-list">
                <tbody>
<?php           
                    echo baformsHelper::drawFieldEditor($this->items);
?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<div class="fields-backdrop" data-dismiss="modal"></div>
<form action="<?php echo JRoute::_('index.php?option=com_baforms&layout=edit&id='); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
            
<?php
    echo $this->form->getInput('id');
    echo $this->form->getInput('title_settings');
    echo $this->form->getInput('form_settings');
    echo $this->form->getInput('form_content');
    echo $this->form->getInput('form_columns');
    echo $this->form->getInput('email_options');
?>
    <input type="hidden" class="email_letter" name="email_letter">
    <div id="delete-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('DELETE_QUESTION') ?></p>            
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="delete-aply"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="notification-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('NOTIFICATION_MESSAGE'); ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="html-editor" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">x</a>
            <h3>Edit text</h3>
        </div>
        <div class="modal-body">
            <textarea name="CKE-editor"></textarea>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="aply-html"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="icons-upload-modal" class="ba-modal-xl modal ba-modal-dialog hide" style="display:none">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">x</a>
            <h3 class="ba-modal-header"><?php echo JText::_('SELECT_ICON'); ?></h3>
        </div>
        <div class="modal-body">
            <iframe src="<?php echo JUri::base(). 'index.php?option=com_baforms&view=icons&tmpl=component'; ?>" width="100%" height="487"></iframe>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="layout-dialog" class="modal hide ba-modal-lg" style="display:none">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">x</a>
            <h3><?php echo JText::_('NUMBER_COLUMNS') ?></h3>
        </div>
        <div class="modal-body">
            <label class="column">
                <input type="radio" name="radioMultiple" value="" data-column="1" class="add-column">
                <img src="components/com_baforms/assets/images/1col.png">
                <p><?php echo JText::_('ONE') ?></p>
            </label>
            <label class="column">
                <input type="radio" name="radioMultiple" value="" data-column="2" class="add-column">
                <img src="components/com_baforms/assets/images/2col.png">
                <p><?php echo JText::_('TWO') ?></p>
            </label>
            <label class="column">
                <input type="radio" name="radioMultiple" value="" data-column="3" class="add-column">
                <img src="components/com_baforms/assets/images/3col.png">
                <p><?php echo JText::_('THREE') ?></p>
            </label>
            <label class="column">
                <input type="radio" name="radioMultiple" value="" data-column="4" class="add-column">
                <img src="components/com_baforms/assets/images/4col.png">
                <p><?php echo JText::_('FOUR') ?></p>
            </label>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <?php include JPATH_COMPONENT_ADMINISTRATOR.'/views/layout/settings.php'; ?>
    <!--///////////////////////////////////////////////////////////////////////////////// -->
    <!--Title Here -->
    <div class ="row-fluid">
        <div class="span11">
            <?php 
                echo $this->form->getLabel('title');
                echo $this->form->getInput('title');
            ?>
        </div>
        <div class="span1 btn-settings-cell">
            <a href="#" class="ba-btn btn-settings" ><?php echo JText::_('SETTINGS') ?></a>
        </div>
    </div>
    <!--///////////////////////////////////////////////////////////////////////////////// -->
    <div class ="row-fluid">
        <div class="span2 content">
            <!--Text items Here -->
            <div class="text-items">
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('TEXT') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!--///////////////////////////////////////////////////////////////////////////////// -->
        <div class="span7 content editor">
            <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" id="fake-tabs">
                    <li class="ui-state-default ui-corner-top">
                        <a href="#" class="ui-tabs-anchor"><?php echo JText::_('FORM'); ?></a>
                    </li>
                    <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active">
                        <a href="#" class="ui-tabs-anchor email-builder"><?php echo JText::_('EMAIL'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="ba-content-editor">
                <div class="email-body">
                    <?php echo $this->email; ?>
                </div>
            </div>
            <input class="ba-btn-primary" type="button" value="<?php echo JText::_('NEW_ROW') ?>" id="add-layout">
        </div>
        <div class="span3 content options">
            <div id="myTab">
                <ul>
                    <li><a href="#element-options"><?php echo JText::_('ELEMENT_OPTIONS') ?></a></li>
                    <li><a href="#email-options"><?php echo JText::_('EMAIL_OPTIONS') ?></a></li>
                </ul>
                <div id="element-options">
                    <div class="text-options" style="display:none">
                        <input class="open-editor ba-btn" type="button" value="<?php echo JText::_('OPEN_EDITOR'); ?>">
                        <br><br>
                        <input class="delete-item ba-btn" type="button" value="<?php echo JText::_('DELETE'); ?>">
                    </div>
                    <div class="table-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('BACKGROUND_COLOR'); ?></lable>
                        <input type="text" class="table-bg">
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR'); ?></lable>
                        <input type="text" class="table-color">
                        <lable class="option-label"><?php echo JText::_('BORDER_COLOR'); ?></lable>
                        <input type="text" class="table-border-color">
                        <lable class="option-label"><?php echo JText::_('BORDER_TOP'); ?></lable>
                        <input type="checkbox" class="table-border-top"><br>
                        <lable class="option-label"><?php echo JText::_('BORDER_RIGHT'); ?></lable>
                        <input type="checkbox" class="table-border-right"><br>
                        <lable class="option-label"><?php echo JText::_('BORDER_BOTTOM'); ?></lable>
                        <input type="checkbox" class="table-border-bottom"><br>
                        <lable class="option-label"><?php echo JText::_('BORDER_LEFT'); ?></lable>
                        <input type="checkbox" class="table-border-left"><br>
                        <lable class="option-label"><?php echo JText::_('MARGIN_TOP'); ?></lable>
                        <input type="number" class="table-margin-top">
                        <lable class="option-label"><?php echo JText::_('MARGIN_BOTTOM'); ?></lable>
                        <input type="number" class="table-margin-bottom">
                    </div>
                </div>
                <div id="email-options">
                    <div id="tabs-1">
                        <lable class="option-label"><?php echo JText::_('BACKGROUND_COLOR'); ?></lable>
                        <input type="text" class="email-bg">
                        <lable class="option-label"><?php echo JText::_('WIDTH'); ?>, %</lable>
                        <input type="number" class="email-width">
                    </div>
                    <div id="tabs-2">
                        <p><span><?php echo JText::_('LABEL_OPTIONS') ?></span></p><br>
                        <lable class="option-label"><?php echo JText::_('FONT_SIZE') ?>:</lable>
                        <input class="label-size" type="number">
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_WEIGHT') ?></lable>
                        <div class="weight_radio">
                            <input type="radio" class="lable-weight" name="lable-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                            <input type="radio" class="lable-weight" name="lable-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                        </div>
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR') ?>:</lable>
                        <input class="label-color" type="text">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="forms.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>