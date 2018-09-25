<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$mapSrc = 'https://maps.google.com/maps/api/js?libraries=places&key='.$this->maps_key;

if (JVERSION >= '3.4.0') {
    JHtml::_('behavior.formvalidator');
} else {
    JHtml::_('behavior.formvalidation');
}
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if (task == "form.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            emailBuild()
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<script type="text/javascript">
    var str = "<div class='btn-wrapper' id='toolbar-integration'>";
    str += "<button class='btn btn-small'><span class='icon-loop'>";
    str += "</span><?php echo JText::_('INTEGRATION'); ?></button></div>";
    jQuery('#toolbar').append(str);
    function checkItems(name, type, place)
    {
        if (name != '') {
            return name;
        } else {
            if (type == 'textarea') {
                if (place != '') {
                    return place;
                } else {
                    return 'Textarea';
                }
            } else if (type == 'textInput') {
                if (place != '') {
                    return place;
                } else {
                    return 'TextInput';
                }
            } else if (type == 'chekInline') {
                return 'ChekInline';
            } else if (type == 'checkMultiple') {
                return 'CheckMultiple';
            } else if (type == 'radioInline') {
                return 'RadioInline';
            } else if (type == 'radioMultiple') {
                return 'RadioMultiple';
            } else if (type == 'dropdown') {
                return 'Dropdown';
            } else if (type == 'selectMultiple') {
                return 'SelectMultiple';
            } else if (type == 'date') {
                return 'Date';
            } else if (type == 'slider') {
                return 'Slider';
            } else if (type == 'email') {
                if (place != '') {
                    return place;
                } else {
                    return 'Email';
                }
            } else if (type == 'address') {
                if (place != '') {
                    return place;
                } else {
                    return 'Address';
                }
            }
        }
    }
    function emailBuild()
    {
        var letter = jQuery("#jform_email_letter").val(),
            emailSettings = jQuery("#jform_email_options").val();
        emailSettings = JSON.parse(emailSettings);
        if (!emailSettings.bg) {
            emailSettings.color = "#111111";
            emailSettings.size = "14";
            emailSettings.weight = "normal";
        }
        if (letter) {
            var div = document.createElement("div");
            div.innerHTML = letter;
            if (jQuery(div).find(".second-table").length == 0) {
                jQuery(div).find("> table").addClass("second-table");
            }
            jQuery(div).find('.system-item').not('.email-cart-field, .email-total-field, .email-ip-field').each(function(){
                var id = jQuery(this).attr('data-item'),
                    item;
                id = id.replace('[item=', '').replace(']', '');
                item = jQuery('.droppad_item').find('> .ba-options[data-id="'+id+'"]')
                if (item.length == 0 || item.closest('.droppad_area').hasClass('condition-area')) {
                    jQuery(this).remove();
                }
            });
            jQuery(".droppad_item").each(function(){
                var type = jQuery.trim(jQuery(this).find("[class*=ba]")[0].className.match("ba-.*")[0].split(" ")[0].split("-")[1]),
                    options = jQuery(this).find("> .ba-options").val(),
                    flag = false,
                    id = jQuery(this).find("> .ba-options").attr("data-id"),
                    str;
                if (jQuery(this).parent().hasClass("condition-area") || type == "image" ||
                    type == "htmltext" || type == "map" || type == 'terms') {
                    return;
                }
                if (!id) {
                    flag = true;
                    str = "[add_new_item]";
                } else {
                    options = options.split(';');
                    if (!options[2]) {
                        options[2] = ''
                    }
                    name = checkItems(options[0], type, options[2]);
                    if (jQuery(div).find('.system-item[data-item="[item='+id+']"]').length == 0) {
                        flag = true;
                        str = '<div class="droppad_item system-item" data-item="[item=';
                        str += id+']" style="color: '+emailSettings.color+'; font-size: ';
                        str += emailSettings.size+'px; font-weight: '+emailSettings.weight;
                        str += '; line-height: 200%; font-family: Helvetica Neue, Helvetica, Arial;"';
                        str += '>[Field='+name+']</div>';
                    } else {
                        jQuery(div).find('.system-item[data-item="[item='+id+']"]').text('[Field='+name+']');
                    }
                }                
                if (flag) {
                    var tr = document.createElement("tr"),
                        td = document.createElement("td"),
                        table = document.createElement("table"),
                        tbody = document.createElement("tbody"),
                        secondTr = document.createElement("tr"),
                        secondTd2 = document.createElement("td");
                    jQuery(div).find(".second-table > tbody").append(tr);
                    tr.className = "ba-section";
                    jQuery(td).css({
                        "width" : "100%",
                        "padding" : "0 20px"
                    });
                    tr.appendChild(td);
                    jQuery(table).css({
                        "width" : "100%",
                        "background-color" : "rgba(0,0,0,0)",
                        "margin-top" : "10px",
                        "margin-bottom" : "10px",
                        "border-top" : "1px solid #f3f3f3",
                        "border-left" : "1px solid #f3f3f3",
                        "border-right" : "1px solid #f3f3f3",
                        "border-bottom" : "1px solid #f3f3f3"
                    });
                    secondTd2.className = "droppad_area"
                    jQuery(secondTd2).css({
                        "width" : "100%",
                        "padding" : "20px"
                    });
                    secondTd2.innerHTML = str;
                    td.appendChild(table);
                    table.appendChild(tbody);
                    tbody.appendChild(secondTr);
                    secondTr.appendChild(secondTd2);
                }
            });
            if (jQuery("#jform_check_ip").prop("checked")) {
                if (jQuery(div).find(".email-ip-field").length == 0) {
                    var tr = document.createElement("tr"),
                        td = document.createElement("td"),
                        table = document.createElement("table"),
                        tbody = document.createElement("tbody"),
                        secondTr = document.createElement("tr"),
                        secondTd2 = document.createElement("td"),
                        item = document.createElement("div");
                    jQuery(div).find(".second-table > tbody").append(tr);
                    tr.className = "ba-section";
                    item.className = "system-item email-ip-field droppad_item";
                    jQuery(td).css({
                        "width" : "100%",
                        "padding" : "0 20px"
                    });
                    tr.appendChild(td);
                    jQuery(table).css({
                        "width" : "100%",
                        "background-color" : "rgba(0,0,0,0)",
                        "margin-top" : "10px",
                        "margin-bottom" : "10px",
                        "border-top" : "1px solid #f3f3f3",
                        "border-left" : "1px solid #f3f3f3",
                        "border-right" : "1px solid #f3f3f3",
                        "border-bottom" : "1px solid #f3f3f3"
                    });
                    secondTd2.className = "droppad_area"
                    jQuery(secondTd2).css({
                        "width" : "100%",
                        "padding" : "20px"
                    });
                    item.innerHTML = "[Field=Ip Address]";
                    td.appendChild(table);
                    table.appendChild(tbody);
                    tbody.appendChild(secondTr);
                    secondTr.appendChild(secondTd2);
                    secondTd2.appendChild(item);
                    jQuery(item).css({
                        "color" : emailSettings.color,
                        "font-size" : emailSettings.size+"px",
                        "font-weight" : emailSettings.weight,
                        "line-height" : "200%",
                        "font-family" : "Helvetica Neue, Helvetica, Arial"
                    });
                }            
            } else {
                jQuery(div).find(".second-table .email-ip-field").remove()
            }
            if (jQuery("#jform_display_total").prop("checked")) {
                if (jQuery(div).find(".email-total-field").length == 0) {
                    var tr = document.createElement("tr"),
                        td = document.createElement("td"),
                        table = document.createElement("table"),
                        tbody = document.createElement("tbody"),
                        secondTr = document.createElement("tr"),
                        secondTd2 = document.createElement("td"),
                        item = document.createElement("div");
                    jQuery(div).find(".second-table > tbody").append(tr);
                    tr.className = "ba-section";
                    item.className = "system-item email-total-field droppad_item";
                    jQuery(td).css({
                        "width" : "100%",
                        "padding" : "0 20px"
                    });
                    tr.appendChild(td);
                    jQuery(table).css({
                        "width" : "100%",
                        "background-color" : "rgba(0,0,0,0)",
                        "margin-top" : "10px",
                        "margin-bottom" : "10px",
                        "border-top" : "1px solid #f3f3f3",
                        "border-left" : "1px solid #f3f3f3",
                        "border-right" : "1px solid #f3f3f3",
                        "border-bottom" : "1px solid #f3f3f3"
                    });
                    secondTd2.className = "droppad_area"
                    jQuery(secondTd2).css({
                        "width" : "100%",
                        "padding" : "20px"
                    });
                    item.innerHTML = "[Field=Total]";
                    td.appendChild(table);
                    table.appendChild(tbody);
                    tbody.appendChild(secondTr);
                    secondTr.appendChild(secondTd2);
                    secondTd2.appendChild(item);
                    jQuery(item).css({
                        "color" : emailSettings.color,
                        "font-size" : emailSettings.size+"px",
                        "font-weight" : emailSettings.weight,
                        "line-height" : "200%",
                        "font-family" : "Helvetica Neue, Helvetica, Arial"
                    });
                }            
            } else {
                jQuery(div).find(".second-table .email-total-field").remove();
                jQuery(div).find(".second-table .email-cart-field").remove();
            }
            if (jQuery("#jform_display_cart").prop("checked") && jQuery("#jform_display_total").prop("checked")) {
                if (jQuery(div).find(".email-cart-field").length == 0) {
                    var tr = document.createElement("tr"),
                        td = document.createElement("td"),
                        table = document.createElement("table"),
                        tbody = document.createElement("tbody"),
                        secondTr = document.createElement("tr"),
                        secondTd2 = document.createElement("td"),
                        item = document.createElement("div");
                    jQuery(div).find(".second-table > tbody").append(tr);
                    tr.className = "ba-section";
                    item.className = "system-item email-cart-field droppad_item";
                    jQuery(td).css({
                        "width" : "100%",
                        "padding" : "0 20px"
                    });
                    tr.appendChild(td);
                    jQuery(table).css({
                        "width" : "100%",
                        "background-color" : "rgba(0,0,0,0)",
                        "margin-top" : "10px",
                        "margin-bottom" : "10px",
                        "border-top" : "1px solid #f3f3f3",
                        "border-left" : "1px solid #f3f3f3",
                        "border-right" : "1px solid #f3f3f3",
                        "border-bottom" : "1px solid #f3f3f3"
                    });
                    secondTd2.className = "droppad_area"
                    jQuery(secondTd2).css({
                        "width" : "100%",
                        "padding" : "20px"
                    });
                    item.innerHTML = "[Field=Cart]";
                    td.appendChild(table);
                    table.appendChild(tbody);
                    tbody.appendChild(secondTr);
                    secondTr.appendChild(secondTd2);
                    secondTd2.appendChild(item);
                    jQuery(item).css({
                        "color" : emailSettings.color,
                        "font-size" : emailSettings.size+"px",
                        "font-weight" : emailSettings.weight,
                        "line-height" : "200%",
                        "font-family" : "Helvetica Neue, Helvetica, Arial"
                    });
                }            
            } else {
                jQuery(div).find(".second-table .email-cart-field").remove();
            }
            jQuery(div).find('.ba-section').each(function(){
                var row = jQuery(this).find('.droppad_area');
                if (row.length > 0 && row.find('.droppad_item').length == 0 && row[0].innerText != '[add_new_item]') {
                    jQuery(this).remove();
                }
            });
            jQuery("#jform_email_letter").val(jQuery(div).html());
        }
    }
</script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="components/com_baforms/assets/css/ba-admin.css?<?php echo $this->about->version; ?>" type="text/css"/>
<div id="theme-rule">
    <style type="text/css" scoped></style>    
</div>
<script src="<?php echo $mapSrc; ?>" type="text/javascript"></script>
<script src="components/com_baforms/assets/js/ba-admin.js?<?php echo $this->about->version; ?>" type="text/javascript"></script>
<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script>
<input type="hidden" id="constant-select" value="<?php echo JText::_('SELECT') ?>">
<div id="integration-dialog" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <h3><?php echo JText::_('INTEGRATION') ?></h3>
    </div>
    <div class="modal-body">
        <label class="column google-maps-integration">
            <img src="components/com_baforms/assets/images/google-maps.png">
            <p>Google Maps</p>
        </label>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<div id="stripe-image-dialog" class="select-image-modal modal hide ba-modal-lg" style="display:none">
    <div class="modal-body">
        <iframe 
        src="index.php?option=com_media&view=images&tmpl=component&asset=com_baforms&author=created_by&fieldid=jform_stripe_image&folder=">
        </iframe>
    </div>
</div>
<div id="marker-image-dialog" class="select-image-modal modal hide ba-modal-lg" style="display:none">
    <div class="modal-body">
        <iframe 
        src="index.php?option=com_media&view=images&tmpl=component&asset=com_baforms&author=created_by&fieldid=jform_marker_image&folder=">
        </iframe>
    </div>
</div>
<div id="select-image-dialog" class="select-image-modal modal hide ba-modal-lg" style="display:none">
    <div class="modal-body">
        <iframe 
        src="index.php?option=com_media&view=images&tmpl=component&asset=com_baforms&author=created_by&fieldid=jform_select_image&folder=">
        </iframe>
    </div>
</div>
<div id="item-upload-dialog" class="select-image-modal modal hide ba-modal-lg" style="display:none">
    <div class="modal-body">
        <iframe 
        src="index.php?option=com_media&view=images&tmpl=component&asset=com_baforms&author=created_by&fieldid=jform_upload_image&folder=">
        </iframe>
    </div>
</div>
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
    echo $this->form->getInput('email_letter');
    echo $this->form->getInput('email_options');
?>
    <div id="add-item-modal" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <h3><?php echo JText::_('ENTER_VALUE'); ?></h3>
        </div>
        <div class="modal-body">
            <textarea placeholder="<?php echo JText::_('ENTER_VALUE_INLINE'); ?>"></textarea>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="item-aply"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="google-maps-integration-modal" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <h3>Google Maps</h3>
        </div>
        <div class="modal-body">
            <label>API Key</label>
            <input type="text" value="<?php echo $this->maps_key; ?>" id="google_maps_apikey" name="google_maps_apikey">
            <input type="button" class="ba-btn apply-google-maps-api-key" value="<?php echo JText::_('APPLY'); ?>">
            <a href="https://developers.google.com/maps/documentation/javascript/" target="_blank" class="ba-btn-primary">Get your API Key</a>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" data-dismiss="modal"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="telegram-integration-modal" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <h3>Telegram</h3>
        </div>
        <div class="modal-body">
            <label>Bot token</label>
            <?php echo $this->form->getInput('telegram_token'); ?>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" data-dismiss="modal"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="mailchimp-integration-dialog" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <h3><?php echo JText::_('MailChimp') ?></h3>
        </div>
        <div class="modal-body">
            <div>
                <label>API key</label>
                <?php echo $this->form->getInput('mailchimp_api_key'); ?>
                <?php echo $this->form->getInput('mailchimp_list_id'); ?>
                <?php echo $this->form->getInput('mailchimp_fields_map'); ?>
                <input type="button" class="ba-btn mailchimp-connect" value="<?php echo JText::_('CONNECT'); ?>">
                <div class="mailchimp-message" style="display: none;"></div>
            </div>
            <div>
                <label><?php echo JText::_('LISTS') ?></label>
                <select class="mailchimp-select-list" disabled>
                    <option value=""><?php echo JText::_('SELECT') ?></option>
                </select>
            </div>
            <label><?php echo JText::_('MATCH_YOUR_FIELDS'); ?></label>
            <div class="merge-fields">
                <div class="mailchimp-email">
                    <label data-field="EMAIL">Email</label>
                    <select class="form-fields" disabled>
                        <option value=""><?php echo JText::_('SELECT'); ?></option>
                    </select>
                </div>
                <div class="mailchimp-fields">
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" data-dismiss="modal"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="delete-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('DELETE_QUESTION') ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="delete-aply"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="google-maps-notification-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text">To use the Google Maps Tool you need to enter Google Maps API Key</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="enter-api-key">Enter API Key</a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="condition-remove-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('ARE_YOU_SURE') ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary apply-condition-remove"><?php echo JText::_('APPLY') ?></a>
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="select-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('SELECT_ITEMS') ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="conditional-notice-dialog" class="modal hide ba-modal-sm" style="display:none">
        <div class="modal-body">
            <p class="modal-text"><?php echo JText::_('SELECT_CONDITIONAL_ITEM') ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="embed-modal" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <h3><?php echo JText::_('EDIT_EMBED'); ?></h3>
        </div>
        <div class="modal-body">
            <?php echo $this->form->getInput('submit_embed'); ?>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="embed-aply"><?php echo JText::_('APPLY') ?></a>
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
    <div id="location-dialog" class="modal hide ba-modal-lg" style="display:none">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">x</a>
            <h3><?php echo JText::_('CHOOSE_LOCATION') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span12">
                    <input type="text" id="place" placeholder="<?php echo JText::_('ENTER_LOCATION') ?>">
                </div>
            </div>
            <div class="row-fluid">    
                <div class="span8">
                    <div class="new-map">
                        <div id="location-map" style="width:100%;height:280px">
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <textarea id="mark-description" placeholder="<?php echo JText::_('ENTER_MARKER') ?>"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn-primary" id="apply-location"><?php echo JText::_('APPLY') ?></a>
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
            <a href="#" class="ba-btn btn-settings"><?php echo JText::_('SETTINGS') ?></a>
        </div>
    </div>
    <!--///////////////////////////////////////////////////////////////////////////////// -->
    <div class ="row-fluid">
        <div class="span2 content">
            <!--Text items Here -->
            <div class="text-items">
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('EMAIL') ?></p>
                    </div>
                    <div class='model'>
                        <label class="label-item"><?php echo JText::_('EMAIL') ?> *</label>
                        <input type="email" class="ba-email" placeholder=""/>
                        <input type="hidden" class="ba-options">
                    </div>
                </div>
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('TEXT_AREA') ?></p>
                    </div>
                    <div class='model'>
                        <label class="label-item"><?php echo JText::_('TEXT_AREA') ?></label>
                        <textarea class="ba-textarea" placeholder=""></textarea>
                        <input type="hidden" class="ba-options">
                    </div>
                </div>
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('TEXT_INPUT') ?></p>
                    </div>
                    <div class='model'>
                        <label class="label-item"><?php echo JText::_('TEXT_INPUT') ?></label>
                        <input type="text" placeholder="" class="ba-textInput"/>
                        <input type="hidden" class="ba-options">
                    </div>
                </div>
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('TEXT') ?></p>
                    </div>
                    <div class='model'>
                        <p class="ba-htmltext"><?php echo JText::_('TEXT') ?></p>
                        <input type="hidden" class="ba-options" value="<?php echo JText::_('TEXT') ?>">
                    </div>
                </div>
            </div>
            <div class="map-field">
                <div class="tool">
                    <div class="textbox">
                        <p><?php echo JText::_('GOOGLE_MAP') ?></p>
                    </div>
                    <div class='model'>
                        <div class="ba-map" style="width:100%;height:400px"></div>
                        <input type="hidden" class="ba-options">
                    </div>
                </div>
            </div>
        </div>
        
        <!--///////////////////////////////////////////////////////////////////////////////// -->
        <div class="span7 content editor">
            <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" id="fake-tabs">
                    <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active">
                        <a href="#" class="ui-tabs-anchor"><?php echo JText::_('FORM'); ?></a>
                    </li>
                    <li class="ui-state-default ui-corner-top">
                        <a href="#" class="ui-tabs-anchor email-builder"><?php echo JText::_('EMAIL'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="ba-content-editor">
                <div class="form-style" style="border: 1px solid #f3f3f3; background-color: #ffffff; border-radius: 2px">
                    <div class="title-form">
                        <h1 style="font-size:26px; font-weight:normal; text-align:left; color:#111111">
                            <span class="title"><?php echo JText::_('NEW_FORM') ?></span>
                            <span class="ba-tooltip"><?php echo JText::_('CLICK_TO_EDIT') ?></span>
                        </h1>
                    </div>
                    <div class="span12" id="content-section">
                        <?php if (!isset($this->item->id) || $this->item->id == '0') {?>
                        <div class="row-fluid">
                            <div id="bacolumn-1" class="span12 droppad_area items">
                                <div class="ba-edit-row">
                                    <a class="zmdi zmdi-arrows"></a>
                                    <a href="#" class="delete-layout zmdi zmdi-close"></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="baforms-cart">
                        <div class="product-cell ba-cart-headline">
                            <div class="product"><?php echo JText::_('ITEM'); ?></div>
                            <div class="price"><?php echo JText::_('PRICE'); ?></div>
                            <div class="quantity"><?php echo JText::_('QUANTITY'); ?></div>
                            <div><?php echo JText::_('TOTAL'); ?></div>
                            <div class="remove-item"></div>
                        </div>
                        <div class="product-cell">
                            <div class="product"><?php echo JText::_('FORM'); ?></div>
                            <div class="price">$36</div>
                            <div class="quantity"><input type="number" value="1" min="1" step="1" data-cost="36"></div>
                            <div class="total">$36</div>
                            <div class="remove-item"><i class="zmdi zmdi-close"></i></div>
                        </div>
                    </div>
                    <div class="ba-total-price">
                        <p><?php echo JText::_('TOTAL') ?>: <span>0</span></p>
                        <span class="ba-tooltip"><?php echo JText::_('CLICK_TO_EDIT') ?></span>
                    </div>
                    <div class="btn-align" style="text-align:left">
                        <input class="ba-btn-submit" id="baform-0" type="button" value="Submit"
                               style="width:15%; height:40px; background-color: #02adea; color: #fafafa; font-size:14px;
                                      font-weight:normal; border-radius:3px; border: none;"/>
                        <span class="ba-tooltip"><?php echo JText::_('CLICK_TO_EDIT') ?></span>
                    </div>
                </div>
            </div>
            <input class="ba-btn-primary" type="button" value="<?php echo JText::_('NEW_ROW') ?>" id="add-layout">
        </div>
        <div class="span3 content options">
            <div id="myTab">
                <ul>
                    <li><a href="#options"><?php echo JText::_('ELEMENT_OPTIONS') ?></a></li>
                    <li><a href="#form-options"><?php echo JText::_('FORM_OPTIONS') ?></a></li>
                </ul>
                <div id="options" style="display:none">
                    <div class="edit-terms" style="display: none;">
                        <lable class="option-label"><?php echo JText::_('LABEL_TEXT') ?></lable>
                        <textarea class="terms-edit-area"></textarea>
                    </div>
                    <div class="image-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('SELECT_IMAGE') ?>:</lable>
                        <a class="modal-trigger" data-modal="select-image-dialog" href="#"><?php echo JText::_('SELECT'); ?></a>
                        <input type="hidden" id="jform_select_image">
                        <lable class="option-label"><?php echo JText::_('ALIGNMENT') ?></lable>
                        <select class="image-align">
                            <option value="left"><?php echo JText::_('LEFT') ?></option>
                            <option value="center"><?php echo JText::_('CENTER') ?></option>
                            <option value="right"><?php echo JText::_('RIGHT') ?></option>
                        </select><br>
                        <lable class="option-label"><?php echo JText::_('WIDTH') ?>, %:</lable>
                        <input type="number" class="ba-width-number-image ba-slider-input" value="0"><br>
                        <lable class="option-label"><?php echo JText::_('ALT') ?></lable>
                        <input type="text" class="image-alt"><br>
                        <lable class="option-label"><?php echo JText::_('ENABLE_LIGHTBOX') ?></lable>
                        <input type="checkBox" class="enable-lightbox"><br>
                        <lable class="option-label"><?php echo JText::_('LIGHTBOX_BG') ?></lable>
                        <input type="text" class="image-lightbox-bg">
                    </div>
                    <div id="text-lable" style="display:none">
                        <lable class="option-label"><?php echo JText::_('LABEL_TEXT') ?></lable>
                        <input type="text" name="label"/><br/>
                    </div>
                    <div id="icons-select" style="display:none">
                        <lable class="option-label"><?php echo JText::_('ICON') ?></lable>
                        <input class="ba-btn" type="button" value="<?php echo JText::_('SELECT'); ?>">
                        <a href="#" class="clear-icon zmdi zmdi-close ba-btn"></a>
                    </div>
                    <div id="text-description" style="display:none">
                        <lable class="option-label"><?php echo JText::_('DESCRIPTION') ?></lable>
                        <input type="text" name="description"/><br/>
                    </div>
                    <div id="place-hold" style="display:none">
                        <lable class="option-label"><?php echo JText::_('PLACEHOLDER') ?></lable>
                        <input type="text" name="place"/><br/>
                    </div>
                    <div id="maxlength" style="display:none">
                        <lable class="option-label">
                            <span>
                                <?php echo JText::_('MAXLENGTH') ?>
                            </span>
                            <span class="ba-tooltip">
                                <?php echo JText::_('MAXLENGTH_TOOLTIP') ?>
                            </span>
                        </lable>
                        <input type="number" class="maxlength" />
                    </div>
                    <div id="input-type" style="display:none">
                        <lable class="option-label"><?php echo JText::_('INPUT_TYPE') ?></lable>
                        <select class="input-type">
                            <option value="regular"><?php echo JText::_('REGULAR') ?></option>
                            <option value="number"><?php echo JText::_('NUMBER') ?></option>
                            <option value="calculation"><?php echo JText::_('CALCULATION') ?></option>
                        </select>
                    </div>
                    <div id="cheсk-options" style="display:none">
                        <div class="items-control-options">
                            <label class="button-alignment ba-btn">
                                <i class="zmdi zmdi-playlist-plus"></i>
                                <span class="ba-tooltip">
                                    <?php echo JText::_('ADD_VALUE'); ?>
                                </span>
                                <input type="radio" class="item-add">
                            </label>
                            <label class="button-alignment ba-btn">
                                <i class="zmdi zmdi-arrow-split"></i>
                                <span class="ba-tooltip">
                                    <?php echo JText::_('CONDITION_LOGIC'); ?>
                                </span>
                                <input type="radio" class="condition-logic">
                            </label>
                            <label class="button-alignment ba-btn">
                                <i class="zmdi zmdi-check"></i>
                                <span class="ba-tooltip">
                                    <?php echo JText::_('JTOOLBAR_DEFAULT'); ?>
                                </span>
                                <input type="radio" class="select-default">
                            </label>
                            <label class="button-alignment ba-btn">
                                <i class="zmdi zmdi-wallpaper"></i>
                                <span class="ba-tooltip">
                                    <?php echo JText::_('UPLOAD_IMAGE'); ?>
                                </span>
                                <input type="radio" class="item-upload-image">
                                <input type="hidden" id="jform_upload_image">
                            </label>
                            <label class="button-alignment ba-btn">
                                <i class="zmdi zmdi-close"></i>
                                <span class="ba-tooltip">
                                    <?php echo JText::_('DELETE'); ?>
                                </span>
                                <input type="radio" class="item-delete">
                            </label>
                            <input type="hidden" id="show_hidden" value="<?php echo JText::_('SHOW_HIDDEN_FIELDS') ?>">
                        </div>
                        <table>
                            <tbody class="items-list"></tbody>
                        </table>
                    </div>
                    <div id="textarea-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('MIN_HEIGHT') ?></lable>
                        <input type="number">
                    </div>
                    <div id="select-size" style="display:none">
                        <label class="option-label"><?php echo JText::_('SELECT_HEIGHT') ?></label>
                        <input type="number">
                    </div>
                    <div id="item-width" style="display:none">
                        <lable class="option-label"><?php echo JText::_('ITEM_WIDTH') ?>, %</lable>
                        <input type="number" min="0" max="100" class="item-width"/>
                    </div>
                    <div id="required" style="display:none">
                        <lable class="option-label"><?php echo JText::_('REQUIRED_FIELD') ?></lable>
                        <input type="checkbox" name="required" value="option1"/>
                    </div>
                    <div id="calendar-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('DISABLE_PREVIOUS_DATE') ?></lable>
                        <input type="checkbox" class="disable-previos" />
                    </div>
                    <div id="button-otions" style="display:none">
                        <lable class="option-label"><?php echo JText::_('LABEL') ?></lable>
                        <input type="text" name="name"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_WIDTH') ?>, %:</lable>
                        <input type="number" id="width"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_HEIGHT') ?></lable>
                        <input type="number" id="height"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_ALIGNMENT') ?></lable>
                        <select class="button-alignment">
                            <option value="left"><?php echo JText::_('LEFT') ?></option>
                            <option value="center"><?php echo JText::_('CENTER') ?></option>
                            <option value="right"><?php echo JText::_('RIGHT') ?></option>
                        </select><br>
                        <lable class="option-label"><?php echo JText::_('BUTTON_BACKGROUND') ?></lable>
                        <input type="text" id="bg-color"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_COLOR') ?></lable>
                        <input type="text" id="text-color"/><br/>
                        <div>
                        <lable class="option-label"><?php echo JText::_('TITLE_SIZE') ?></lable>
                        <input type="number" id="font-size">
                        </div>
                        <lable class="option-label"><?php echo JText::_('TITLE_WEIGHT') ?></lable>
                        <div class="weight_radio">
                            <input type="radio" name="font-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                            <input type="radio" name="font-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                        </div>
                        <p>
                            <label class="option-label"><?php echo JText::_('BORDER_RADIUS') ?>:</label>
                            <input type="number" id="radius" value="3">
                        </p>
                        <lable class="option-label">
                            <?php echo JText::_('EMBED_CODE') ?>
                        </lable>
                        <input type="button" value="<?php echo JText::_('EMBED') ?>" class="submit-embed ba-btn">
                    </div>
                    <div id="breaker-options" style="display: none">
                        <lable class="option-label"><?php echo JText::_('LABEL') ?></lable>
                        <input type="text" name="name" id="break-label"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_WIDTH') ?>, px:</lable>
                        <input type="number" id="break-width"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_HEIGHT') ?></lable>
                        <input type="number" id="break-height"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_BACKGROUND') ?></lable>
                        <input type="text" id="break-bg-color"/><br/>
                        <lable class="option-label"><?php echo JText::_('BUTTON_COLOR') ?></lable>
                        <input type="text" id="break-text-color"/><br/>
                        <lable class="option-label"><?php echo JText::_('TITLE_SIZE') ?></lable>
                        <input type="number" id="break-font-size">
                        <lable class="option-label"><?php echo JText::_('TITLE_WEIGHT') ?></lable>
                        <div class="weight_radio">
                            <input type="radio" name="break-font-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                            <input type="radio" name="break-font-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                        </div>
                        <label class="option-label"><?php echo JText::_('BORDER_RADIUS') ?>:</label>
                        <input type="number" id="break-radius" value="3">
                    </div>
                    <div id="slider-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('MIN') ?></lable>
                        <input type="text" id="min" value="0"/><br/>
                        <lable class="option-label"><?php echo JText::_('MAX') ?></lable>
                        <input type="text" id="max" value="50"/><br/>
                        <lable class="option-label"><?php echo JText::_('STEP') ?></lable>
                        <input type="text" id="step"><br/>
                    </div>
                    <div id="html-options" style="display:none">
                        <input type="button" value="<?php echo JText::_('OPEN_EDITOR') ?>" class="ba-btn" id="html-button">
                        <br>
                        <br>
                    </div>
                    <div id="upload-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('MAX_SIZE') ?></lable>
                        <input type="number" id="upl-size">
                        <lable class="option-label"><?php echo JText::_('FILE_TYPES') ?></lable>
                        <input type="text" id="upl-types">
                    </div>
                    <div id="email-checkbox" style="display:none">
                        <lable class="option-label"><?php echo JText::_('REQUIRED_FIELD') ?></lable>
                        <input type="checkbox" disabled checked />
                        <div class="confirmation">
                            <lable class="option-label"><?php echo JText::_('EMAIL_CONFIRMATION') ?></lable>
                            <input type="checkbox" class="email-confirmation">
                        </div>
                    </div>
                    <div id="map-options" style="display:none">
                        <input class="ba-btn" id="map-location" type="button" value="<?php echo JText::_('CHOOSE_LOCATION') ?>" /><br/>
                        <lable class="option-label"><?php echo JText::_('HEIGHT') ?></lable>
                        <input value="400" type="text" id="map-height">
                        <lable class="option-label"><?php echo JText::_('THEME'); ?></lable>
                        <select id="map-theme">
                            <option value="standart">Standard</option>
                            <option value="silver">Silver</option>
                            <option value="retro">Retro</option>
                            <option value="dark">Dark</option>
                            <option value="night">Night</option>
                            <option value="aubergine">Aubergine</option>
                        </select>
                        <lable class="option-label"><?php echo JText::_('MAP_CONTROLS') ?></lable>
                        <input type="checkbox" checked name="controls" value="1"><br>
                        <lable class="option-label"><?php echo JText::_('DISPLAY_INFOBOX') ?></lable>
                        <input type="checkbox" name="infobox" value="1"><br>
                        <label class="option-label"><?php echo JText::_('SCROLL_ZOOMING') ?></label>
                        <input type="checkbox" class="map-zooming"><br>
                        <label class="option-label"><?php echo JText::_('DRAGGABLE_MAP') ?></label>
                        <input type="checkbox" class="map-draggable"><br>
                        <lable class="option-label"><?php echo JText::_('UPLOAD_MARKER') ?>:</lable>
                        <a class="modal-trigger" data-modal="marker-image-dialog" href="#"><?php echo JText::_('SELECT'); ?></a>
                        <a class="clear-image-field" data-field="jform_marker_image" href="#">
                            <i class="zmdi zmdi-close"></i>
                        </a>
                        <input type="hidden" id="jform_marker_image">
                        <input type="hidden" value="" id="location-options">
                        <input type="hidden" value="" id="marker-position">
                    </div>
                    <div id="custom-css" style="display:none">
                        <lable class="option-label"><?php echo JText::_('CLASS_SUFFIX') ?></lable>
                        <input class="custom-css-suffix" type="text">
                    </div>
                    <div id="delete-buttons" style="display:none">
                        <input type="hidden" value="" id="item-id">
                        <input type="hidden" value="" id="type-item">
                        <input class="ba-btn" id="delete-item" type="button" value="<?php echo JText::_('DELETE') ?>"/>
                    </div>
                    <div class="title-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('FONT_SIZE') ?>:</lable>
                        <input class="title-size" type="number" value="26"><br/>
                        <lable class="option-label"><?php echo JText::_('FONT_WEIGHT') ?></lable>
                        <div class="weight_radio">
                            <input type="radio" name="title-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                            <input type="radio" name="title-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                        </div>
                        <lable class="option-label"><?php echo JText::_('FONT_ALIGNMENT') ?></lable>
                        <select class="title-alignment">
                            <option value="left"><?php echo JText::_('LEFT') ?></option>
                            <option value="center"><?php echo JText::_('CENTER') ?></option>
                            <option value="right"><?php echo JText::_('RIGHT') ?></option>
                        </select>
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR') ?></lable>
                        <input type="text" id="title-color"><br/>
                    </div>
                    <div class="total-options" style="display:none">
                        <lable class="option-label"><?php echo JText::_('LABEL') ?>:</lable>
                        <input class="total-label" type="text"><br/>
                        <lable class="option-label"><?php echo JText::_('ALIGNMENT') ?></lable>
                        <select class="total-alignment">
                            <option value="left"><?php echo JText::_('LEFT') ?></option>
                            <option value="center"><?php echo JText::_('CENTER') ?></option>
                            <option value="right"><?php echo JText::_('RIGHT') ?></option>
                        </select>
                    </div>
                    <div class="save-continue-options" style="display: none;">
                        <p><span><?php echo JText::_('LINK_OPTIONS') ?></span></p><br>
                        <div>
                            <label><?php echo JText::_('LABEL') ?></label>
                            <?php echo $this->form->getInput('save_continue_label'); ?>
                            <label><?php echo JText::_('FONT_SIZE') ?></label>
                            <?php echo $this->form->getInput('save_continue_size'); ?>
                            <label><?php echo JText::_('FONT_WEIGHT') ?></label>
                            <?php echo $this->form->getInput('save_continue_weight'); ?>
                            <div class="weight_radio">
                                <input type="radio" class="save-link-weight" name="save-link" value="normal">
                                <?php echo JText::_('NORMAL') ; ?>
                                <input type="radio" class="save-link-weight" name="save-link" value="bold">
                                <?php echo JText::_("BOLD"); ?>
                            </div>
                            <lable class="option-label"><?php echo JText::_('ALIGNMENT') ?></lable>
                            <?php echo $this->form->getInput('save_continue_align'); ?>
                            <label><?php echo JText::_('FONT_COLOR') ?></label>
                            <?php echo $this->form->getInput('save_continue_color'); ?>
                            <input type="text" class="save-continue-color">
                        </div>
                        <p><span><?php echo JText::_('CONFIRMATION_POPUP') ?></span></p><br>
                        <div>
                            <label><?php echo JText::_('TITLE') ?></label>
                            <?php echo $this->form->getInput('save_continue_popup_title'); ?>
                            <label><?php echo JText::_('MESSAGE') ?></label>
                            <?php echo $this->form->getInput('save_continue_popup_message'); ?>
                        </div>
                        <p><span><?php echo JText::_('EMAIL_SETTINGS') ?></span></p><br>
                        <div>
                            <label><?php echo JText::_('EMAIL_SUBJECT') ?></label>
                            <?php echo $this->form->getInput('save_continue_subject'); ?>
                            <label><?php echo JText::_('EMAIL_BODY') ?></label>
                            <?php echo $this->form->getInput('save_continue_email'); ?>
                        </div>
                    </div>
                </div>
                <div id="form-options">
                    <p><span><?php echo JText::_('FORM_OPTIONS') ?></span></p><br>
                    <div id="tabs-1">
                        <lable class="option-label"><?php echo JText::_('FORM_WIDTH') ?></lable>
                        <input id="form-width" type="number" value="100">
                        <br>
                        <lable class="option-label"><?php echo JText::_('BACKGROUND_COLOR') ?></lable>
                        <input id="form-bgcolor" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('BORDER_COLOR') ?></lable>
                        <input id="form-borcolor" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('BORDER_RADIUS') ?>:</lable>
                        <input id="form-radius" type="number" value="2">
                        <br>
                        <lable class="option-label"><?php echo JText::_('THEME_COLOR') ?></lable>
                        <?php echo $this->form->getInput('theme_color'); ?>
                        <input class="theme-color" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('CLASS_SUFFIX') ?></lable>
                        <input id="form-class" type="text">
                        <br>
                    </div>
                    <p><span><?php echo JText::_('LABEL_OPTIONS') ?></span></p><br>
                    <div id="tabs-2">
                        <lable class="option-label"><?php echo JText::_('FONT_SIZE') ?>:</lable>
                        <input id="label-size" type="number" value="13">
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_WEIGHT') ?></lable>
                        <div class="weight_radio">
                            <input type="radio" name="lable-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                            <input type="radio" name="lable-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                        </div>
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR') ?>:</lable>
                        <input id="label-color" type="text">
                    </div>
                    <p><span><?php echo JText::_('INPUTS_OPTIONS') ?></span></p><br>
                    <div id="tabs-3">
                        <lable class="option-label"><?php echo JText::_('INPUTS_HEIGHT') ?></lable>
                        <input id="input-height" type="number" value="30">
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_SIZE') ?>:</lable>
                        <input id="input-size" type="number" value="13">
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR') ?>:</lable>
                        <input id="input-color" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('BACKGROUND_COLOR') ?></lable>
                        <input id="input-bgcolor" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('BORDER_COLOR') ?></lable>
                        <input id="input-borcolor" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('INPUTS_RADIUS') ?></lable>
                        <input id="input-radius" type="number" value="2">
                    </div>
                    <p><span><?php echo JText::_('ICONS_OPTIONS') ?></span></p><br>
                    <div id="tabs-4">
                        <lable class="option-label"><?php echo JText::_('FONT_COLOR') ?></lable>
                        <input id="icons-color" type="text">
                        <br>
                        <lable class="option-label"><?php echo JText::_('FONT_SIZE') ?></lable>
                        <input id="icons-size" type="number" value="2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="forms.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>