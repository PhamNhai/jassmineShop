/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

jQuery(document).ready(function(){

    var CKE = CKEDITOR.replace('CKE-editor'),
        CKEreplyBody = CKEDITOR.replace('jform[reply_body]'),
        deleteTarget,
        currentItem,
        color,
        titleSettings = jQuery('#jform_title_settings').val(),
        emailSettings = jQuery('#jform_email_options').val();

    emailSettings = JSON.parse(emailSettings);

    if (!emailSettings.bg) {
        emailSettings.color = '#111111';
        emailSettings.size = '14';
        emailSettings.weight = 'normal';
        emailSettings.bg = jQuery('.email-body > table')[0].style.backgroundColor;
        emailSettings.width = jQuery('.email-body > table')[0].style.width.replace('%', '');
        jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
    }
    if (jQuery('.parent-table').length == 0) {
        jQuery('.email-body > table')[0].style.border = '';
        jQuery('.email-body > table')[0].style.backgroundColor = 'transparent';
        jQuery('.email-body > table').addClass('second-table')
        jQuery('.email-body').append('<table class="parent-table"><tbody><tr><td></td></tr></tbody></table>');
        jQuery('.parent-table > tbody > tr > td')[0].appendChild(jQuery('.second-table')[0]);
        jQuery('.parent-table').css({
            'width' : '100%',
            'background-color' : emailSettings.bg
        });
    }
    jQuery('.second-table').css({
        'border-spacing' : 0
    });
    jQuery('.system-item').css({
        'color' : emailSettings.color,
        'font-size' : emailSettings.size+'px',
        'font-weight' : emailSettings.weight,
        'line-height' : '200%',
        'font-family' : 'Helvetica Neue, Helvetica, Arial'
    });

    jQuery('.label-size').val(emailSettings.size);
    jQuery('.lable-weight').each(function(){
        if (jQuery(this).val() == emailSettings.weight) {
            jQuery(this).attr('checked', true);
            return false;
        }
    });

    jQuery('.fields-backdrop').on('click', function(){
        jQuery('#fields-editor').modal('hide');
    });

    jQuery('.lable-weight').on('change', function(){
        emailSettings.weight = jQuery(this).val();
        jQuery('.system-item').css({
            'font-weight' : emailSettings.weight
        });
        jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
        saveLetter();
    });

    jQuery('.ba-tooltip').each(function(){
            var parent = jQuery(this).parent(),
                item = parent.children().first(),
                tooltip = parent.find('.ba-tooltip');
            item.off('mouseenter mouseleave').on('mouseenter', function(){
                var $this = jQuery(this),
                    coord = this.getBoundingClientRect(),
                    top = coord.top,
                    data = tooltip.html(),
                    center = (coord.right - coord.left) / 2;
                    className = tooltip[0].className;
                center = coord.left + center;
                if (tooltip.hasClass('ba-bottom')) {
                    top = coord.bottom;
                }
                jQuery('body').append('<span class="'+className+'">'+data+'</span>');
                jQuery('body > .ba-tooltip').css({
                    'top' : top+'px',
                    'left' : center+'px'
                });
            }).on('mouseleave', function(){
                jQuery('body > .ba-tooltip').remove();
            });
        });

    jQuery('.email-subject').on('click', function(event){
        event.preventDefault();
        fieldMode = 'jform_email_subject';
        jQuery('#fields-editor').modal();
    });

    jQuery('.reply-subject').on('click', function(event){
        event.preventDefault();
        fieldMode = 'jform_reply_subject';
        jQuery('#fields-editor').modal();
    });

    var fieldMode;

    jQuery('#fields-editor .forms-list tbody').on('click', 'a', function(event){
        event.preventDefault();
        var id = jQuery(this).attr('data-shortcode');
        if (fieldMode == 'CKE') {
            var flag = true,
                frame = jQuery('#auto-reply iframe'),
                doc;
            if (frame.length == 0) {
                flag = false;
            } else { 
                doc = frame[0].contentDocument;
            }
            if (flag && doc.getSelection().rangeCount > 0) {
                var iRange = doc.getSelection().getRangeAt(0),
                    i = doc.createElement('span')
                i.innerText = id;
                iRange.insertNode(i);
            } else {
                var data = CKEreplyBody.getData();
                data += id;
                CKEreplyBody.setData(data);
            }
        } else {
            var val = jQuery('#'+fieldMode).val();
            jQuery('#'+fieldMode).val(val+id);
        }
        jQuery('#fields-editor').modal('hide');
    });

    jQuery('.label-size').on('click keyup', function(){
        emailSettings.size = jQuery(this).val();
        if (emailSettings.size == '') {
            emailSettings.size = 0;
        }
        jQuery('.system-item').css({
            'font-size' : emailSettings.size+'px',
        });
        jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
        saveLetter();
    });

    color = rgb2hex(emailSettings.color);
    jQuery('.label-color').minicolors({
        opacity: true,
        change : function(hex){
            emailSettings.color = jQuery(this).minicolors('rgbaString');
            jQuery('.system-item').css({
                'color' : emailSettings.color
            })
            jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
            saveLetter();
        }
    });

    jQuery('.label-color').minicolors('value', color[0]);
    jQuery('.label-color').minicolors('opacity', color[1]);

    color = rgb2hex(emailSettings.bg);

    jQuery('.email-width').val(emailSettings.width);

    jQuery('.email-width').on('click keyup', function(){
        var val = jQuery(this).val();
        if (val < 0) {
            val = 0;
        }
        if (val > 100) {
            val = 100;
        }
        emailSettings.width = val;
        jQuery('.second-table')[0].style.width = val+'%';
        jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
        saveLetter();
    });

    jQuery('.email-bg').minicolors({
        opacity: true,
        change : function(hex){
            hex = jQuery(this).minicolors('rgbaString');
            jQuery('.parent-table').css('background-color', hex);
            emailSettings.bg = hex;
            jQuery('#jform_email_options').val(JSON.stringify(emailSettings));
            saveLetter();
        }
    });

    jQuery('.email-bg').minicolors('value', color[0]);
    jQuery('.email-bg').minicolors('opacity', color[1]);

    jQuery('#delete-aply').on('click', function(event){
        event.preventDefault();
        deleteTarget.remove();
        jQuery('#delete-dialog').modal('hide');
        jQuery('.text-options').hide();
        jQuery('.table-options').hide();
        saveLetter();
    });

    
    CKEDITOR.dtd.$removeEmpty.span = 0;
    CKEDITOR.dtd.$removeEmpty.i = 0;
    CKE.setUiColor('#fafafa');
    CKE.config.allowedContent = true;
    CKE.config.toolbar_Basic =
        [
        { name: 'document',    items : [ 'Source' ] },
        { name: 'styles',      items : [ 'Styles','Format' ] },
        { name: 'colors',      items : [ 'TextColor' ] },
        { name: 'clipboard',   items : [ 'Undo','Redo' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline'] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent',
                                        'Indent','-','Blockquote','-','JustifyLeft',
                                        'JustifyCenter','JustifyRight','JustifyBlock','-' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule'] }
    ];
    CKE.config.toolbar = 'Basic';
    CKEreplyBody.setUiColor('#fafafa');
    CKEreplyBody.config.allowedContent = true;
    CKEreplyBody.config.toolbar_Basic =
        [
        { name: 'document',    items : [ 'Source' ] },
        { name: 'styles',      items : [ 'Styles','Format' ] },
        { name: 'colors',      items : [ 'TextColor' ] },
        { name: 'clipboard',   items : [ 'Undo','Redo' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline'] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent',
                                        'Indent','-','Blockquote','-','JustifyLeft',
                                        'JustifyCenter','JustifyRight','JustifyBlock','-' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Table','HorizontalRule'] }
    ];
    CKEreplyBody.config.toolbar = 'Basic';
    
    jQuery('.reply-body').on('click', function(event){
        event.preventDefault();
        fieldMode = 'CKE';
        jQuery('#fields-editor').modal();
    });

    jQuery('#fields-editor .forms-list tbody').on('click', 'a', function(event){
        event.preventDefault();
        var flag = true,
            frame = jQuery('#auto-reply iframe'),
            doc,
            id = jQuery(this).closest('tr').find('td').text();
        id = jQuery.trim(id);
        if (frame.length == 0) {
            flag = false;
        } else { 
            doc = frame[0].contentDocument;
        }
        if (flag && doc.getSelection().rangeCount > 0) {
            var iRange = doc.getSelection().getRangeAt(0),
                i = doc.createElement('span')
            i.innerText = '[field ID='+id+']';
            iRange.insertNode(i);
        } else {
            var data = CKEreplyBody.getData();
            data += '[field ID='+id+']';
            CKEreplyBody.setData(data);
        }
        jQuery('#fields-editor').modal('hide');
    });

    jQuery('.ba-search').on('keyup', function(){
        var search = jQuery(this).val();
        jQuery('.forms-list tbody .form-title a').each(function(){
            var title = jQuery(this).text();
            title = jQuery.trim(title);
            title = title.toLowerCase();
            search = search.toLowerCase();
            if (title.indexOf(search) < 0) {
                jQuery(this).closest('tr').hide();
            } else {
                jQuery(this).closest('tr').show();
            }
        });
    });

    var icons = '<div class="ba-edit-row"><a class="zmdi zmdi-arrows">';
    icons += '</a><a href="#" class="edit-layout zmdi zmdi-settings"></a><a';
    icons += ' href="#" class="delete-layout zmdi zmdi-close"></a></div>';

    jQuery('.droppad_item').not('.system-item').on('click', function(){
        jQuery('.text-options').show();
        jQuery('.table-options').hide();
        currentItem = jQuery(this);
        jQuery("#myTab").tabs({ active: 0 });
    });

    jQuery('#aply-options').on('click', function(event){
    	event.preventDefault();
    	jQuery('#global-options').modal('hide');
    });

    jQuery('.email-body .ba-section').each(function(){
        jQuery(this).find('.droppad_area').last().prepend(icons);
    });

    jQuery('#jform_display_popup').on('click', DisableOptions);

    jQuery('#jform_button_type').on('change', checkBtnType);
    jQuery('#jform_check_ip').on('change', checkEmailFields);
    jQuery('#jform_display_cart').on('change', checkEmailFields);

    function checkEmailFields()
    {
        if (jQuery("#jform_check_ip").prop("checked")) {
            if (jQuery(".email-ip-field").length == 0) {
                var tr = document.createElement("tr"),
                    td = document.createElement("td"),
                    table = document.createElement("table"),
                    tbody = document.createElement("tbody"),
                    secondTr = document.createElement("tr"),
                    secondTd2 = document.createElement("td"),
                    item = document.createElement("div");
                jQuery(".second-table > tbody").append(tr);
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
                    'color' : emailSettings.color,
                    'font-size' : emailSettings.size+'px',
                    'font-weight' : emailSettings.weight,
                    'line-height' : '200%',
                    'font-family' : 'Helvetica Neue, Helvetica, Arial'
                });
                jQuery('.email-ip-field').parent().prepend(icons);
                makeSortable();
            }            
        } else {
            jQuery(".email-ip-field").remove()
        }
        if (jQuery("#jform_display_total").prop("checked")) {
            if (jQuery(".email-total-field").length == 0) {
                var tr = document.createElement("tr"),
                    td = document.createElement("td"),
                    table = document.createElement("table"),
                    tbody = document.createElement("tbody"),
                    secondTr = document.createElement("tr"),
                    secondTd2 = document.createElement("td"),
                    item = document.createElement("div");
                jQuery(".second-table > tbody").append(tr);
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
                    'color' : emailSettings.color,
                    'font-size' : emailSettings.size+'px',
                    'font-weight' : emailSettings.weight,
                    'line-height' : '200%',
                    'font-family' : 'Helvetica Neue, Helvetica, Arial'
                });
                jQuery('.email-total-field').parent().prepend(icons);
                makeSortable();
            }
        } else {
            jQuery(".email-total-field").remove();
            jQuery(".email-cart-field").remove();
        }
        if (jQuery("#jform_display_cart").prop("checked") && jQuery("#jform_display_total").prop("checked")) {
            if (jQuery(".email-cart-field").length == 0) {
                var tr = document.createElement("tr"),
                    td = document.createElement("td"),
                    table = document.createElement("table"),
                    tbody = document.createElement("tbody"),
                    secondTr = document.createElement("tr"),
                    secondTd2 = document.createElement("td"),
                    item = document.createElement("div");
                jQuery(".second-table > tbody").append(tr);
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
                    'color' : emailSettings.color,
                    'font-size' : emailSettings.size+'px',
                    'font-weight' : emailSettings.weight,
                    'line-height' : '200%',
                    'font-family' : 'Helvetica Neue, Helvetica, Arial'
                });
                jQuery('.email-cart-field').parent().prepend(icons);
                makeSortable();
            }            
        } else {
            jQuery(".email-cart-field").remove();
        }
    }

    function checkBtnType()
    {
        if (jQuery('#jform_button_type').val() == 'button') {
            jQuery('#jform_button_position').removeAttr('disabled');
            jQuery('#button_bg').removeAttr('disabled');
            jQuery('#button_color').removeAttr('disabled');
            jQuery('#jform_button_font_size').removeAttr('disabled');
            jQuery('#jform_button_border').removeAttr('disabled');
        } else {
            jQuery('#jform_button_position').attr('disabled', true);
            jQuery('#button_bg').attr('disabled', true);
            jQuery('#button_color').attr('disabled', true);
            jQuery('#jform_button_font_size').attr('disabled', true);
            jQuery('#jform_button_border').attr('disabled', true);
        }
    }

    function DisableOptions ()
    {
        if (!jQuery('#jform_display_popup').prop("checked")) {
            jQuery('#jform_modal_width').attr('disabled', true);
            jQuery('#jform_button_position').attr('disabled', true);
            jQuery('#button_bg').attr('disabled', true);
            jQuery('#button_color').attr('disabled', true);
            jQuery('#jform_button_lable').attr('disabled', true);
            jQuery('#jform_button_font_size').attr('disabled', true);
            jQuery('#jform_button_border').attr('disabled', true);
            jQuery('#jform_button_type').attr('disabled', true);
            jQuery('[name="popup-font-weight"]').each(function(){
                jQuery(this).attr('disabled', true);
            });
        } else {
            jQuery('#jform_modal_width').removeAttr('disabled');
            jQuery('#jform_button_lable').removeAttr('disabled');
            jQuery('#jform_button_type').removeAttr('disabled');
            checkBtnType();
            jQuery('[name="popup-font-weight"]').each(function(){
                jQuery(this).removeAttr('disabled');
            });
        }
    }
    DisableOptions();
    checkBtnType();

    function displayFormTotal()
    {
        var $this = jQuery('#jform_display_total')[0];
        if (jQuery('#jform_display_total').prop('checked')) {
            jQuery('.ba-total-price').show();
            jQuery('#jform_display_total').parent().find('input, select').not($this).removeAttr('disabled');
            jQuery('#cheсk-options .items-list').removeClass('empty-price');
            jQuery('#jform_alow_captcha').hide().find('option').each(function(){
                jQuery(this).removeAttr('selected');
            }).first().attr('selected', true).parent().prev().hide();
        } else {
            jQuery('#jform_display_total').parent().find('input, select').not($this).not('[type="hidden"]').attr('disabled', true);
            jQuery('.ba-total-price').hide();
            jQuery('#cheсk-options .items-list').addClass('empty-price');
            jQuery('#jform_alow_captcha').show().prev().show();
        }
        checkEmailFields();
    }
    function checkPayment()
    {
        var value = jQuery('#jform_payment_methods').val();
        if (value == 'paypal') {
            jQuery('.webmoney').hide();
            jQuery('.paypal-login').show();
            jQuery('#jform_payment_environment').show().prev().show();
            jQuery('#jform_payment_environment').nextAll().show();
            jQuery('.skrill').hide();
            jQuery('.2checkout').hide();
            jQuery('.payu').hide();
            jQuery('.custom-payment').hide();
            jQuery('.stripe').hide();
        } else if (value == '2checkout') {
            jQuery('.webmoney').hide();
            jQuery('.paypal-login').hide();
            jQuery('.skrill').hide();
            jQuery('#jform_payment_environment').show().prev().show();
            jQuery('#jform_return_url').show().prev().show();
            jQuery('.2checkout').show();
            jQuery('.payu').hide();
            jQuery('.custom-payment').hide();
            jQuery('.stripe').hide();
        } else if (value == 'payu') {
            jQuery('#jform_payment_methods').nextAll().hide();
            jQuery('#jform_payment_environment').show().prev().show();
            jQuery('#jform_return_url').show().prev().show();
            jQuery('.payu').show();
            jQuery('.custom-payment').hide()
            jQuery('.stripe').hide();
        } else if (value == 'skrill') {
            jQuery('.webmoney').hide();
            jQuery('.paypal-login').hide();
            jQuery('#jform_payment_environment').hide().prev().hide();
            jQuery('#jform_payment_environment').nextAll().show();
            jQuery('.skrill').show();
            jQuery('.2checkout').hide();
            jQuery('.payu').hide();
            jQuery('.custom-payment').hide()
            jQuery('.stripe').hide();
        } else if (value == 'webmoney') {
            jQuery('.paypal-login').hide();
            jQuery('.2checkout').hide();
            jQuery('.skrill').hide().nextAll().hide();
            jQuery('.webmoney').show();
            jQuery('.payu').hide();
            jQuery('.custom-payment').hide();
            jQuery('.stripe').hide();
        } else if (value == 'stripe') {
            jQuery('.paypal-login').hide();
            jQuery('.2checkout').hide();
            jQuery('.skrill').hide().nextAll().hide();
            jQuery('.webmoney').hide();
            jQuery('.payu').hide();
            jQuery('.custom-payment').hide();
            jQuery('.stripe').show();
        } else {
            jQuery('.webmoney').hide();
            jQuery('.paypal-login').hide();
            jQuery('.2checkout').hide();
            jQuery('.skrill').hide().nextAll().hide();
            jQuery('.payu').hide();
            jQuery('.custom-payment').show();
        }
        jQuery('.multiple-payment').show();
    }
    displayFormTotal();
    jQuery('#jform_display_total').on('click', displayFormTotal);

    jQuery('#jform_payment_methods').on('change', function(){
        checkPayment();
    });
    
    checkPayment();

    color = rgb2hex(jQuery('#jform_message_bg_rgba').val());
    jQuery('#message_bg').minicolors({
        opacity: true,
        change: function(hex, opacity) {
            if (hex != '') {
                var rgba = jQuery(this).minicolors('rgbaString');
                jQuery('#jform_message_bg_rgba').val(rgba);
            }
        }
    });
    jQuery('#message_bg').minicolors('value', color[0]);
    jQuery('#message_bg').minicolors('opacity', color[1]);

    color = rgb2hex(jQuery('#jform_message_color_rgba').val());
    jQuery('#message_color').minicolors({
        opacity: true,
        change: function(hex, opacity) {
            if (hex != '') {
                var rgba = jQuery(this).minicolors('rgbaString');
                jQuery('#jform_message_color_rgba').val(rgba);
            }
        }
    });
    jQuery('#message_color').minicolors('value', color[0]);
    jQuery('#message_color').minicolors('opacity', color[1]);

    color = rgb2hex(jQuery('#jform_dialog_color_rgba').val());
    jQuery('#dialog_color').minicolors({
        opacity: true,
        change: function(hex, opacity) {
            if (hex != '') {
                var rgba = jQuery(this).minicolors('rgbaString');
                jQuery('#jform_dialog_color_rgba').val(rgba);
            }
        }
    });
    jQuery('#dialog_color').minicolors('value', color[0]);
    jQuery('#dialog_color').minicolors('opacity', color[1]);

    function getCSSrulesString()
    {
        var str = 'h1.cke_panel_grouptitle';
        str += ' {font-size: initial !important;} h1,h2,h3,h4,h5,h6,p, div,';
        str += ' address, pre, span {line-height: 32px !important; text-align:';
        str += ' left !important; font-weight: normal !important; font-family:';
        str += ' initial !important; text-transform: none !important;}';
        str += '.cke_panel_list a, span {line-height: 18px !important;}';
        str += 'h1 {font-size: 22px !important;} h2 {font-size: 20px !important;}';
        str += 'h3 {font-size: 18px !important;} h4 {font-size: 16px !important;}';
        str += 'h5 {font-size: 14px !important;} h6 {font-size: 12px !important;}';
        str += 'p, div, address, pre, span {font-size: 16px !important;}';
        return str;
    }
    var iconsLib = 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css';
    CKEDITOR.config.contentsCss = [getCSSrulesString(), iconsLib];
    
    
    function addColumn(number)
    {
        jQuery('#layout-dialog').modal('hide');
        var str = '<tr class="ba-section"><td style="width: 100%; padding: 0 20px;">',
            width = 100 / number;
        str += '<table style="width: 100%; background-color: rgba(0,0,0,0);';
        str += ' color: #333333; margin-top:10px; margin-bottom : 10px;';
        str += ' border-top: 1px solid #f3f3f3; border-left: 1px solid';
        str += ' #f3f3f3; border-right: 1px solid #f3f3f3; border-';
        str += 'bottom: 1px solid #f3f3f3;"><tbody><tr>';
        for (var i = 0; i < number; i++) {
            str += '<td class="droppad_area" style="width: '+width+'%; padding: 20px;">';
            if (i == number - 1) {
                str += '<div class="ba-edit-row"><a class="zmdi zmdi-arrows"></a>';
                str += '<a href="#" class="edit-layout zmdi zmdi-settings">';
                str += '</a><a href="#" class="delete-layout zmdi zmdi-close"></a></div>';
            }
            str += '</td>';
        }
        str += '</tr></tbody></table></td></tr>';
        jQuery('.second-table> tbody').append(str);
        makeSortable();
        saveLetter();
    }

    function rgb2hex(rgb)
    {
        var parts = rgb.toLowerCase().match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/),
            hex = '#',
            part,
            color = new Array();
        if (parts) {
            for ( var i = 1; i <= 3; i++ ) {
                part = parseInt(parts[i]).toString(16);
                if (part.length < 2) {
                    part = '0'+part;
                }
                hex += part;
            }
            if (!parts[4]) {
                parts[4] = 1;
            }
            color.push(hex);
            color.push(parts[4]);
            
            return color;
        } else {
            color.push(rgb);
            color.push(1);
            
            return color;
        }
    }

    function makeSortable()
    {
        jQuery(".droppad_area").sortable({
            items: '.droppad_item',
            cursorAt: {
                left: 90,
                top: 20
            },
            tolerance: 'pointer',
            start : function(event, ui){
                jQuery(this).sortable('refreshPositions');
            },
            connectWith: ".droppad_area",
            stop: function(event, ui){
                saveLetter();
            }
        }).disableSelection();
        jQuery('.delete-layout').off('click');
        jQuery('.delete-layout').on('click', function(event){
            event.preventDefault();
            deleteTarget = jQuery(this).closest('.ba-section');
            if (deleteTarget.find('.system-item').length > 0) {
                jQuery('#notification-dialog').modal();
                return;
            }
            jQuery('#delete-dialog').modal();
            
        });
        jQuery('.edit-layout').off('click');
        jQuery('.edit-layout').on('click', function(event){
            event.preventDefault();
            jQuery("#myTab").tabs({ active: 0 });
            jQuery('.text-options').hide();
            jQuery('.table-options').show();
            currentItem = jQuery(this).closest('table');
            var style = currentItem[0].style,
                color = rgb2hex(style.backgroundColor);
            jQuery('.table-bg').minicolors('value', color[0]);
            jQuery('.table-bg').minicolors('opacity', color[1]);
            color = rgb2hex(style.color);
            jQuery('.table-color').minicolors('value', color[0]);
            jQuery('.table-color').minicolors('opacity', color[1]);
            color = rgb2hex(style.borderLeftColor);
            jQuery('.table-border-color').minicolors('value', color[0]);
            jQuery('.table-border-color').minicolors('opacity', color[1]);
            jQuery('.table-margin-top').val(style.marginTop.replace('px', ''));
            jQuery('.table-margin-bottom').val(style.marginBottom.replace('px', ''));
            if (style.borderLeftWidth == '1px') {
                jQuery('.table-border-left').attr('checked', true);
            } else {
                jQuery('.table-border-left').removeAttr('checked');
            }
            if (style.borderTopWidth == '1px') {
                jQuery('.table-border-top').attr('checked', true);
            } else {
                jQuery('.table-border-top').removeAttr('checked');
            }
            if (style.borderBottomWidth == '1px') {
                jQuery('.table-border-bottom').attr('checked', true);
            } else {
                jQuery('.table-border-bottom').removeAttr('checked');
            }
            if (style.borderRightWidth == '1px') {
                jQuery('.table-border-right').attr('checked', true);
            } else {
                jQuery('.table-border-right').removeAttr('checked');
            }
        });
        jQuery(".droppad_area").droppable({
            accept: ".tool",
            drop: function( event, ui ) {
                var p = ui.draggable.clone().find('p'),
                    div = document.createElement('div');
                div.className = 'droppad_item';
                div.appendChild(p[0]);
                this.appendChild(div);
                jQuery(div).on('click', function(){
                    jQuery('.text-options').show();
                    jQuery('.table-options').hide();
                    currentItem = jQuery(this);
                    jQuery("#myTab").tabs({ active: 0 });
                });
                saveLetter();
            }
        });
    }

    function saveLetter()
    {
        var letter = jQuery('.email-body').clone();
        letter.find('.ba-edit-row').remove();
        letter = letter.html();
        jQuery('.email_letter').val(jQuery.trim(letter));
    }

    saveLetter();

    jQuery('.table-border-top').on('click', function(){
        var px,
            color = currentItem[0].style.borderTopColor;
        if (jQuery(this).prop('checked')) {
            px = 1;
        } else {
            px = 0;
        }
        currentItem.css('border-top', px+'px solid '+color);
        saveLetter();
    });

    jQuery('.table-border-right').on('click', function(){
        var px,
            color = currentItem[0].style.borderRightColor;
        if (jQuery(this).prop('checked')) {
            px = 1;
        } else {
            px = 0;
        }
        currentItem.css('border-right', px+'px solid '+color);
        saveLetter();
    });

    jQuery('.table-border-bottom').on('click', function(){
        var px,
            color = currentItem[0].style.borderBottomColor;
        if (jQuery(this).prop('checked')) {
            px = 1;
        } else {
            px = 0;
        }
        currentItem.css('border-bottom', px+'px solid '+color);
        saveLetter();
    });

    jQuery('.table-border-left').on('click', function(){
        var px,
            color = currentItem[0].style.borderLeftColor;
        if (jQuery(this).prop('checked')) {
            px = 1;
        } else {
            px = 0;
        }
        currentItem.css('border-left', px+'px solid '+color);
        saveLetter();
    });

    jQuery('.table-margin-top').on('click keyup', function(){
        currentItem.css('margin-top', jQuery(this).val()+'px');
        saveLetter();
    });

    jQuery('.table-margin-bottom').on('click keyup', function(){
        currentItem.css('margin-bottom', jQuery(this).val()+'px');
        saveLetter();
    });

    jQuery('.table-bg').minicolors({
        opacity: true,
        change : function(hex){
            hex = jQuery(this).minicolors('rgbaString');
            currentItem.css('background-color', hex);
            saveLetter();
        }
    });

    jQuery('.table-color').minicolors({
        opacity: true,
        change : function(hex){
            hex = jQuery(this).minicolors('rgbaString');
            currentItem.css('color', hex);
            saveLetter();
        }
    });

    jQuery('.table-border-color').minicolors({
        opacity: true,
        change : function(hex){
            hex = jQuery(this).minicolors('rgbaString');
            currentItem.css('border-color', hex);
            saveLetter();
        }
    });

    jQuery('.open-editor').on('click', function(){
        jQuery('#html-editor').modal();
        CKE.setData(currentItem.html());
    });

    jQuery('.delete-item').on('click', function(){
        jQuery('#delete-dialog').modal();
        deleteTarget = currentItem;
    });

    jQuery('#aply-html').on('click', function(event){
        event.preventDefault();
        currentItem.html(CKE.getData());
        currentItem.find('a').on('click', function(){
        	return false;
        });
        jQuery('#html-editor').modal('hide');
        saveLetter();
    });

    jQuery('.text-items .tool').not('.disabled').draggable({
        helper: "clone",
        stack: "div",
        cursor: "move",
        cancel: null
    });

    jQuery('.second-table > tbody').sortable({
        handle: '.zmdi.zmdi-arrows',
        cursorAt: {
            left: 0,
            top: 0
        },
        start : function(event, ui){
            jQuery(this).sortable('refreshPositions');
        },
        stop : function(){
            saveLetter();
        }
    }).disableSelection();

    makeSortable();
    checkEmailFields()

    jQuery('#fake-tabs li a').on('click', function(event){
        event.preventDefault();
        if (!jQuery(this).hasClass('email-builder')) {
            Joomla.submitbutton('email.buildForm');
        }
    });
    jQuery('#fake-tabs2 li a').on('click', function(event){
        event.preventDefault();
    });

    jQuery('#jform_title').addClass('form-title');
    jQuery('#jform_title').attr('placeholder', 'New Form');
    jQuery('#jform_title-lbl').attr('style', 'display:none');
    
    jQuery('.btn-settings').on('click', function(event){
        event.preventDefault();
        jQuery('#global-options').modal();
        jQuery('#global-options').css('z-index', '1500');
    });

    jQuery('#global-tabs').tabs();

    jQuery( "#myTab" ).tabs();

    jQuery('#add-layout').on('click', function (){
        jQuery('#layout-dialog').modal('show');
    });

    jQuery('.add-column').on('click', function(){
        var n = jQuery(this).attr('data-column');
        addColumn(n);
    });
});