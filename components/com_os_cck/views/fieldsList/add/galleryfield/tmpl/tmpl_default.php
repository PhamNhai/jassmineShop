<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
$fName = $field->db_field_name;
$moduleID = $field->fid;
$width = isset($field_from_params[$fName]["options"]["width"])?$field_from_params[$fName]["options"]["width"]:100;
$height = isset($field_from_params[$fName]["options"]["height"])?$field_from_params[$fName]["options"]["height"]:300;




$allow_ext = isset($field_from_params[$field->db_field_name . "_allow_ext"])?trim($field_from_params[$field->db_field_name . "_allow_ext"]):'';
$max_upload_size = isset($field_from_params[$field->db_field_name . "_max_upload_size"])?(int)$field_from_params[$field->db_field_name . "_max_upload_size"]:0;//mb

$max_width = isset($field_from_params[$field->db_field_name . "_max_width"])?(int)$field_from_params[$field->db_field_name . "_max_width"]:0;
$max_height = isset($field_from_params[$field->db_field_name . "_max_height"])?(int)$field_from_params[$field->db_field_name . "_max_height"]:0;

$item_limit = isset($field_from_params[$field->db_field_name . "_item_limit"])?(int)$field_from_params[$field->db_field_name . "_item_limit"]:0;

$allow_ext = str_ireplace(' ', '', $allow_ext);

if(strlen($allow_ext)<1){
    $allow_ext = "'jpg', 'jpeg', 'png', 'gif'";
}else{
    $allow_ext = explode(',', $allow_ext);
    $allow_ext_string = array();
    foreach ($allow_ext as $val) {
        $allow_ext_string[] = "'".$val."'";
    }
    $allow_ext = implode(', ', $allow_ext_string);
}

$items = json_decode($value);
?>
<div id="module-sliders_<?php echo $fName?>" >
    <div id="file-area_<?php echo $fName?>">
        <noscript>
            <p>JavaScript disabled :(</p>
        </noscript>
        <script type="text/template" id="qq-template_<?php echo $fName?>">
            <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
                <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                </div>
                <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                    <span class="qq-upload-drop-area-text-selector"></span>
                </div>
                <div class="qq-upload-button-selector qq-upload-button">
                    <div>Upload a file</div>
                </div>
                    <span class="qq-drop-processing-selector qq-drop-processing">
                        <span>Processing dropped files...</span>
                        <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                    </span>
                <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                    <li>
                        <div class="qq-progress-bar-container-selector">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                        </div>
                        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                        <span class="qq-upload-file-selector qq-upload-file"></span>
                        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                        <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                        <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    </li>
                </ul>

                <dialog class="qq-alert-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Close</button>
                    </div>
                </dialog>

                <dialog class="qq-confirm-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">No</button>
                        <button type="button" class="qq-ok-button-selector">Yes</button>
                    </div>
                </dialog>

                <dialog class="qq-prompt-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <input type="text">
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Cancel</button>
                        <button type="button" class="qq-ok-button-selector">Ok</button>
                    </div>
                </dialog>
            </div>
        </script>
        <div id="fine-uploader_<?php echo $fName?>"></div>
    </div>
    <section id="wrapper_<?php echo $fName?>">
        <ul id="images_<?php echo $fName?>"></ul>
        <input id="json_query_<?php echo $fName?>" type="hidden" name="fi_<?php echo $fName?>" value='<?php echo $value?>' type="text">
    </section>
    <input type="hidden" value="Clear all" class="clear_all_btn">
    <div id="dialog-form_<?php echo $fName?>" title="Image options" style="display:none;">
        <fieldset>
        <span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
            <label for="name">Slide link</label>
            <input type="text" name="name_<?php echo $fName?>" id="name_<?php echo $fName?>"
                   class="text ui-widget-content ui-corner-all"/>
            <label for="description">Slide alt tag,name</label>
            <input type="text" name="description" id="description_<?php echo $fName?>" value=""
                   class="text ui-widget-content ui-corner-all"/>
            <input type="hidden" class="where" value="">
            </span>
        </fieldset>
    </div>

    <script>
        (function ($) {
        var field_name = "_<?php echo $fName?>";
            var uploader = new qq.FineUploader({
            /* other required config options left out for brevity */
                element: document.getElementById("fine-uploader"+field_name),
                template: 'qq-template'+field_name,
                validation: {
                    allowedExtensions: [<?php echo $allow_ext;?>],
                    sizeLimit: <?php echo $max_upload_size;?> * 1000 * 1000,
                    image: {
                        'maxWidth' : <?php echo $max_width;?>,
                        'maxHeight' :  <?php echo $max_height;?>
                    },
                    itemLimit: <?php echo $item_limit;?>
                },
               
                request: {
                    endpoint: "<?php echo JURI::base()?>/index.php?option=com_os_cck&no_html=1&task=getContent&format=raw",
                    params: {
                      id: <?php echo $moduleID?>
                    }
                },
                callbacks: {
                    onComplete: function (id, filename, responseJSON) {
                        if (!responseJSON.success) {
                        }
                        else {
                            if ($("input#json_query"+field_name).val() != ""){
                                var $images = JSON.parse($("input#json_query"+field_name).val());
                            }
                            if ($("input#json_query"+field_name).val() != "") {
                                $images.push({'file': responseJSON.file, 'alt': '', 'name': ''});
                                $("#json_query"+field_name).val(JSON.stringify($images));
                                refresh_data(append_button);
                            } else {
                                $images = new Array();
                                $images.push({'file': responseJSON.file, 'alt': '', 'name': ''});
                                $("#json_query"+field_name).val(JSON.stringify($images));
                                refresh_data(append_button);
                            }
                        }
                    }
                }
            });

            function refresh_data(func) {
                setTimeout(function () {
                    if ($("input#json_query"+field_name).val() == "") return;

                    var image_set = JSON.parse($("input#json_query"+field_name).val());
                    var image_mass = new Array();
                    $.each(image_set, function (key, img) {
                        image_mass.push('<li style="width:<?php echo $width?>px;height:<?php echo $height?>px;"><img src="<?php echo JURI::root()?>images/com_os_cck<?php echo $moduleID?>/thumbnail/'+img.file+'" alt="'+img.alt+'"><a item="'+img.file+'" class="rem_item'+field_name+'"></a><a name="'+((typeof img.name == "undefined") ? "" : img.name )+'" description="'+((typeof img.alt == "undefined")?"" : img.alt )+'" item="'+img.file+'" class="edit_item'+field_name+' popup'+field_name+'"></a></li>');
                    });
                    $("ul#images"+field_name).html(image_mass.join(""));
                    func();
                }, 1000);
            }

            $(document).ready(function () {
                refresh_data(append_button);
                $("#images"+field_name).sortable({
                    start: function (event, ui) {
                        ui.item.addClass('active');
                    },
                    stop: function (event, ui) {
                        ui.item.removeClass('active').effect("highlight", { color: '#000' }, 0, function () {
                            var mass = new Array();
                            $.each($('#images_<?php echo $fName?> li img'), function (index, event) {
                                $(this).attr('ordering', parseInt(index, 10));
                                var filename = $(this).attr("src").split('/').pop();
                                mass.push({'file': filename, 'alt': $(this).attr('alt'), 'name': $(this).parent().children("input").val() });
                            });
                            $("#json_query"+field_name).val(JSON.stringify(mass));
                        });
                    }
                });
            });
            //$("#images"+field_name).disableSelection();///is depracated
            $("#images"+field_name).on('dblclick mousedown', '.prevent-select', true)
            function append_button() {
                $(".clear_all_btn").click(function () {
                    var $images = new Array();
                    $("#json_query"+field_name).val(JSON.stringify($images));
                    $("ul#images_<?php echo $fName?> li").fadeOut(1000, function () {
                        $(this).remove();
                    });
                });

                $(".rem_item"+field_name).click(function () {
                    var file = $(this).attr("item");
                    var images = JSON.parse(jQuerCCK("input#json_query"+field_name).val());

                    //   console.log(images);
                    var $rem = $(this).parent();
                    if (images.length > 0) {
                        $.each(images, function (k, img) {
                            if (img.file == file) {
                                $($rem).fadeOut(400, function () {
                                    $(this).remove();
                                });
                                images.splice(k, 1);
                                return false;
                            }
                        });
                        $("#json_query"+field_name).val(JSON.stringify(images));
                    }
                });
                $("#dialog-form"+field_name).dialog({
                    autoOpen: false,
                    height: 285,
                    width: 560,
                    modal: true,
                    buttons: {
                        "Save": function () {
                            var where = $(this).find(".where").val();
                            var $image_set = JSON.parse($("input#json_query"+field_name).val());
                            var tmp_this = this;
                            $.each($image_set, function (k, item) {
                                if (item.file == where) {
                                    $image_set[k].name = $(tmp_this).find("#name"+field_name).val();
                                    $image_set[k].alt = $(tmp_this).find("#description"+field_name).val();
                                    return false;
                                }
                            });
                            $("a.edit_item_<?php echo $fName?>[item='"+$(this).find(".where").val()+"']").attr('name', $(this).find("#name"+field_name).val());
                            $("a.edit_item_<?php echo $fName?>[item='"+$(this).find(".where").val()+"']").attr('description', $(this).find("#description"+field_name).val());
                            $("input#json_query"+field_name).val(JSON.stringify($image_set));
                            $(this).find(".where").val();
                            $(this).dialog("close");
                            $(this).css("height", "1px");
                        }
                    },
                    close: function () {

                    }
                });
                $("a.popup"+field_name).click(function () {
                    $("div#dialog-form_<?php echo $fName?> input#name"+field_name).val($(this).attr('name'));
                    $("div#dialog-form_<?php echo $fName?> input#description"+field_name).val($(this).attr('description'));
                    $("div#dialog-form_<?php echo $fName?> input.where").val($(this).attr('item'));
                    $("#dialog-form"+field_name).css("height", "100%");
                    $("#dialog-form"+field_name).dialog("open");
                });
            }
        })(jQuerCCK);
    </script>
</div>