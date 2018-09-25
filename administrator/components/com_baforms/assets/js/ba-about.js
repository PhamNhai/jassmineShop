/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

jQuery(document).ready(function(){

    jQuery('#toolbar-language button').on('click', function(){
        jQuery('#language-dialog').modal();
    });

    jQuery('#language-dialog').on('show', function(){
        window.addEventListener("message", listenMessage, false);
        uploadMode = 'language'
    });

    jQuery('#language-dialog').on('hide', function(){
        window.removeEventListener("message", listenMessage, false);
    });

    jQuery('#toolbar-about').find('button').on('click', function(){
        jQuery('#about-dialog').modal();
    });
    
    var massage = '';
    
    jQuery('.update-link').on('click', function(){
        updateComponent();
    });

    jQuery('.ba-import-form').on('click', function(){
        jQuery('#upload-dialog').modal();
    })

    jQuery('.ba-export-form').on('click', function(){
        var exportId = new Array();
        jQuery('.table-striped tbody input[type="checkbox"]').each(function(){
            if (jQuery(this).prop('checked')) {
                var id = jQuery(this).val();
                exportId.push(id);
            }
        });
        if (exportId.length == 0) {
            jQuery('#error-dialog').modal();
        } else {
            exportId = exportId.join(';');
            jQuery.ajax({
                type:"POST",
                dataType:'text',
                url:"index.php?option=com_baforms&view=forms&task=forms.exportForm",
                data:{
                    export_id: exportId,
                },
                success: function(msg){
                    var msg = JSON.parse(msg);
                    if (msg.success) {
                        var iframe = jQuery('<iframe/>', {
                            name:'download-target',
                            id:'download-target',
                            src:'index.php?option=com_baforms&view=forms&task=forms.download&tmpl=component&file='+msg.message,
                            style:'display:none'
                        });
                        jQuery('#download-target').remove();
                        jQuery('body').append(iframe);
                        
                    }
                }
            });
        }
    });

    var iframe,
        uploadMode;

    jQuery('#update-dialog').on('show', function(){
        window.addEventListener("message", listenMessage, false);
        uploadMode = 'update';
    });

    jQuery('#update-dialog').on('hide', function(){
        window.removeEventListener("message", listenMessage, false);
        iframe.remove();
    });

    function updateComponent()
    {
        iframe = jQuery('<iframe/>', {
                name:'update-target',
                id:'update-target',
                src:'https://www.balbooa.com/demo/index.php?option=com_baupdater&view=baforms'
        });
        iframe.appendTo(jQuery('#form-update'));
        iframe.css('width', '440px')
        iframe.css('height', '290px')
        jQuery('#update-dialog').modal();
        if (window.addEventListener) {
            window.addEventListener("message", function(event){listenMessage(event)}, false);
        } else {
            window.attachEvent("onmessage", function(event){listenMessage(event)});
        }
        jQuery('#update-dialog').on('hide', function(){
            iframe.remove();
        });
    }

    function listenMessage(event)
    {
        if (event.origin == 'https://www.balbooa.com') {
            if (uploadMode == 'update') {
                var link = event.data;
                jQuery('#update-dialog').modal('hide');
                iframe.remove();
                jQuery('#message-dialog').modal();
                var flag = link[0] + link[1] + link[2] + link[3];
                if (flag == 'http') {
                    jQuery('#message-dialog p').text('BaForms is updating');
                    jQuery.ajax({
                        type:"POST",
                        dataType:'text',
                        url:"index.php?option=com_baforms&view=submissions&task=forms.updateBaforms&tmpl=component",
                        data:{
                            target:link,
                        },
                        success: function(msg){
                            msg = JSON.parse(msg)
                            if (msg.success) {
                                jQuery('#message-dialog p').text('BaForms is updated now');
                                jQuery('#update-message').remove();
                                jQuery('.update').text(msg.message);
                                jQuery('.ba-update-message').remove();
                            } else {
                                jQuery('#message-dialog p').text('BaForms was not updated, try again');
                            }
                        }
                    });
                } else {
                    jQuery('#message-dialog p').text(link);
                }
            } else if (uploadMode == 'language') {
                jQuery('#language-dialog').modal('hide');
                jQuery.ajax({
                    type:"POST",
                    dataType:'text',
                    url:"index.php?option=com_baforms&task=forms.addLanguage&tmpl=component",
                    data:{
                        ba_url: event.data,
                    },
                    success: function(msg){
                        msg = JSON.parse(msg)
                        jQuery('#language-message-dialog').modal();
                    }
                });
            }
        }
    }
});

