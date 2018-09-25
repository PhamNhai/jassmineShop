/**
* @package   BAforms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

jQuery(document).ready(function(){
    
    var exportData = [];
    
    jQuery('.show_submissions').on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        jQuery(this).parent().parent().removeClass('ba-new');
        jQuery(this).parent().parent().addClass('ba-default');
        var id = jQuery(this).parent().parent().find('.item-id').text();
        id = jQuery.trim(id);
        jQuery.ajax({
            type:"POST",
            dataType:'text',
            url:"index.php?option=com_baforms&view=submissions&task=submissions.changestate&tmpl=component",
            data:{
                form_id:id,
            }
        });
        var data = jQuery(this).parent().find('input[type="hidden"]').val(),
            item = '',
            str = "";
        data = data.split('_-_');
        for (var i = 0; i < data.length; i++) {
            item = data[i].split('|-_-|');
            if (jQuery.trim(item[0]) != '' && item[2] != 'terms') {
                str += '<tr><td style="width:30%">'+item[0]+' :</td><td>';
                if(item[2] == 'upload') {
                    item[1] = jQuery.trim(item[1]);
                    if (item[1] != '') {
                        str += '<a target="_blank" href="../images/baforms/'+item[1]+'">View Uploaded File</a>';
                    }
                } else {
                	item[1] = item[1].replace(new RegExp('<br>', 'g'), '');
                    if (item[1] == ';') {
                        item[1] = ''
                    }
                    str += '<textarea readonly>'+item[1]+'</textarea>';
                }
                str += '</td></tr>';
            }
        }
        jQuery('#submission-data').html(str);
    });
    
    jQuery('.export').on('click', function(){
        var flag = false;
        jQuery('.submissions-list tbody input[type="checkbox"]').each(function(){
            if (jQuery(this).prop('checked')) {
                flag = true;
                return false;
            }
        });
        if (flag) {
            jQuery('#export-dialog').modal();
        } else {
            jQuery('#error-dialog .not-selected').show();
            jQuery('#error-dialog .many-selected').hide();
            jQuery('#error-dialog').modal();
        }
    });
    
    jQuery('.print-submission').on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
        var number = 0,
            id;
        jQuery('.submissions-list tbody input[type="checkbox"]').each(function(){
            if (jQuery(this).prop('checked')) {
                id = jQuery(this).val();
                number++;
            }
        });
        if (number == 0) {
            jQuery('#error-dialog .not-selected').show();
            jQuery('#error-dialog .many-selected').hide();
            jQuery('#error-dialog').modal();
        } else if (number > 1) {
            jQuery('#error-dialog .not-selected').hide();
            jQuery('#error-dialog .many-selected').show();
            jQuery('#error-dialog').modal();
        } else {
            var url = window.location.href;
            url += '&tmpl=component&print='+id;
            window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no')
        }
    });
    
    jQuery('#export-dialog').on('show', function(){
        
        jQuery('.submissions-list tbody input[type="checkbox"]').each(function(){
            if (jQuery(this).prop('checked')) {
                var message = jQuery(this).closest('tr').find('input[type="hidden"]').val();
                var form = jQuery(this).closest('tr').find('.show_submissions').text();
                var id = jQuery(this).closest('tr').find('.item-id').text();;
                id = jQuery.trim(id);
                var data = {};
                data.message = message;
                data.form = form;
                data.id = id;
                data = JSON.stringify(data);
                exportData.push(data);
            }
        });
        exportData = exportData.join('|__|');
    });
    
    jQuery('#export-dialog').on('hide', function(){
        exportData = [];
    });
    
    jQuery('.xml-upload').on('click', function(){
        jQuery.ajax({
            type:"POST",
            dataType:'text',
            url:"index.php?option=com_baforms&view=submissions&task=submissions.exportXML",
            data:{
                exportData:exportData,
            },
            success: function(msg){
                var msg = JSON.parse(msg);
                if (msg.success) {
                    var iframe = jQuery('<iframe/>', {
                        name:'form-target',
                        id:'form-target',
                        src:'index.php?option=com_baforms&view=submissions&task=submissions.doanload&tmpl=component&file='+msg.message,
                        style:'display:none'
                    });
                    jQuery('#form-target').remove();
                    jQuery('body').append(iframe);
                    
                }
            }
        });
    });
    
    jQuery('.csv-upload').on('click', function(){
        jQuery.ajax({
            type:"POST",
            dataType:'text',
            url:"index.php?option=com_baforms&view=submissions&task=submissions.exportCSV",
            data:{
                exportData:exportData,
            },
            success: function(msg){
                var msg = JSON.parse(msg);
                if (msg.success) {
                    var iframe = jQuery('<iframe/>', {
                        name:'form-target',
                        id:'form-target',
                        src:'index.php?option=com_baforms&view=submissions&task=submissions.doanload&tmpl=component&file='+msg.message,
                        style:'display:none'
                    });
                    jQuery('#form-target').remove();
                    jQuery('body').append(iframe);
                    
                }
            }
        });
    });
});