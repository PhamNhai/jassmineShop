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
?>
<script type="text/javascript">
  //default accordion active state
  var fieldActiveState = 0;
  var formActiveState = 0;
  var blockActiveState = 0;
  var debug = false;
  //fn for saving accordion state
  function saveAccordionState(){
    if(debug){
      console.log('saveAccordionState()');
    }
    fieldActiveState = jQuerCCK( "#fields-options-accordion" ).accordion( "option", "active" );
    if(fieldActiveState == false) fieldActiveState = 0;
    formActiveState = jQuerCCK( "#form-options-accordion" ).accordion( "option", "active" );
    if(formActiveState == false) formActiveState = 0;
    blockActiveState = jQuerCCK( "#block-options-accordion" ).accordion( "option", "active" );
    if(blockActiveState == false) blockActiveState = 0;
  }
//end
  //fn for find position of dom obj
    function findPosY(obj) {
      if(debug){
      console.log('findPosY('+obj+')');
      }
      var curtop = 0;
      if(obj.offsetParent){
        while(1){
          curtop+=obj.offsetTop;
          if(!obj.offsetParent){
            break;
          }
          obj=obj.offsetParent;
        }
      }else if (obj.y){
        curtop+=obj.y;
      }
      return curtop-100;
    }
  //end
    //fn for layout field after drop// adding options
    function addOptionForLayout(key){
      if(debug){
        console.log('addOptionForLayout('+key+')');
      }
      jQuerCCK.post("index.php?option=com_os_cck&task=addOptionForLayout&key="+key+"&lid="+jQuerCCK("#lid").val()+"&layout_type="+jQuerCCK("[name='type']").val()+"&entity_id="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
      {},
      function (data) {
        jQuerCCK("#ui-accordion-fields-options-accordion-panel-0").append(data.html);
        jQuerCCK(".main-fields-content select[id^='vi_show_type_']").on('change',function() {
          if(jQuerCCK(this).val() != 1){
            jQuerCCK("#ui-accordion-fields-options-accordion-header-1").slideDown();
            jQuerCCK('#styling-f>div,h2').not('.style-for-block').hide();
          }else{
            jQuerCCK("#ui-accordion-fields-options-accordion-header-1, #ui-accordion-fields-options-accordion-panel-1").slideUp();
          }
        });
      } , 'json' );


    }
  //end

    //insert text in cursor place
    jQuerCCK.fn.extend({
        insertAtCaret: function(myValue){
            return this.each(function(i) {
                if (document.selection) {
                    // Для браузеров типа Internet Explorer
                    this.focus();
                    var sel = document.selection.createRange();
                    sel.text = myValue;
                    this.focus();
                }
                else if (this.selectionStart || this.selectionStart == '0') {
                    // Для браузеров типа Firefox и других Webkit-ов
                    var startPos = this.selectionStart;
                    var endPos = this.selectionEnd;
                    var scrollTop = this.scrollTop;
                    this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                    this.focus();
                    this.selectionStart = startPos + myValue.length;
                    this.selectionEnd = startPos + myValue.length;
                    this.scrollTop = scrollTop;
                } else {
                    this.value += myValue;
                    this.focus();
                }
            })
        }
    });
    //insert text in cursor place

    //fn for addMask
    function addMask(mask){
      if(debug){
        console.log('addMask('+mask+')');
      }
      
      //if this is mail field 
      // var mail_body = jQuerCCK("#cck_mail_body").val();
      // mail_body += ' '+mask;
      jQuerCCK("#cck_mail_body").val(mask);
      jQuerCCK('#field-mail-modal').modal('hide');
    }
  //end

      

  //fn for addMaskCustom
    function addMaskCustom(mask){
      if(debug){
        console.log('addMaskCustom('+mask+')');
      }
      //if this is custom field 
      var custom_body_name = jQuerCCK('#field-custom-modal').attr('custom_code_field_name');
      // var custom_body = jQuerCCK(".custom-code-editor[name='"+custom_body_name+"']").val();
      // custom_body += ' '+mask;
      jQuerCCK(".custom-code-editor[name='"+custom_body_name+"']").insertAtCaret(mask);

      jQuerCCK('#field-custom-modal').modal('hide');
    }
  //end

    //fn for saving module ids in layout params
    function addHiddenModuleIds(modId){
      if(debug){
        console.log('addHiddenModuleIds('+modId+')');
      }
      if(jQuerCCK("#attached_module_ids").attr("value")){
        modId = jQuerCCK("#attached_module_ids").attr("value")+'|_|'+modId+'|_|';
        jQuerCCK("#attached_module_ids").attr("value",modId);
      }else{
        modId = '|_|'+modId+'|_|';
        jQuerCCK("#attached_module_ids").attr("value",modId);
      }
    }
  //end
    //delete from hiden module ids
    function deleteModuleIds(modId){
      if(debug){
        console.log('deleteModuleIds('+modId+')');
      }
      moduleIds = jQuerCCK("#attached_module_ids").attr("value");
      moduleIds = moduleIds.replace('|_|'+modId+'|_|','');
      jQuerCCK("#attached_module_ids").attr("value",moduleIds);
    }
  //end
  
    //fn for unique options
    function add_unique_option(count, showAddFieldButton = false){
      if(debug){
        console.log('add_unique_option('+count+')');
      }
      count--;
      db_field_name = '<?php echo $custom_code_field->db_field_name ?>'+'_'+(count);
      html = '<div id="options-field-custom_code_field_'+(count)+'">'+
                '<div style="display:none;"><label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label> <input type="checkbox" data-field-name="'+db_field_name+'" name="code_field_unique['+(count)+'][showName_'+db_field_name+']" ></div>'+
                '<div style="display:none;"><label><?php echo JText::_("COM_OS_CCK_LABEL_PREFIX")?></label> <input type="text" size="4" name="code_field_unique['+(count)+']['+db_field_name+'_prefix]"  value="" ></div>'+
                '<div style="display:none;"><label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label> <input type="text" size="4" name="code_field_unique['+(count)+']['+db_field_name+'_suffix]"  value="" ></div>'+
                '<div><label>Access</label>'+
                  '<select class="inputbox" multiple="multiple" name="code_field_unique['+(count)+']['+db_field_name+'_access][]">';
                    <?php
                    foreach($layout->gtree as $value){ ?>
                      html +='<option <?php echo ($value->value == "1")? "selected" :""; ?>'+
                              ' value="<?php echo $value->value; ?>"><?php echo $value->text; ?></option>';
                      <?php
                    } ?>


        if(showAddFieldButton){
          var addFieldButton = '<input id="add-field-mask-custom" class="new-mask" type="button" aria-invalid="false" value="+field">';
        }else{
          var addFieldButton = '';
        }

        html +=  '</select></div>'+
                '<div><label><?php echo JText::_("COM_OS_CCK_LABEL_CUSTOM_CODE_TYPE") ?></label>'+
                    '<select class="editor-type" rows="10" cols="30" name="code_field_unique['+(count)+']['+db_field_name+'_custom_code_type]">'+
                      '<option value="TEXT">TEXT</option>'+
                      '<option value="HTML">HTML</option>'+
                      '<option value="PHP">PHP</option>'+
                      '<option value="SCRIPT">SCRIPT</option>'+
                      '<option value="CSS">CSS</option>'+
                    '</select></div>'+
                '<div><label><?php echo JText::_("COM_OS_CCK_LABEL_CUSTOM_CODE") ?></label>'+addFieldButton+'<span class="editor-button">Editor</span> <textarea class="custom-code-editor" rows="10" cols="30" name="code_field_unique['+(count)+']['+db_field_name+'_custom_code]"></textarea></div>'+
              '</div>';
      jQuerCCK("#ui-accordion-fields-options-accordion-panel-0").append(html);
    }
  //end

    //fn for empty title
    function empty_title(){
      if(debug){
        console.log('empty_title()');
      }
      window.scrollTo(0,findPosY(jQuerCCK('#layout-title'))-100);
      jQuerCCK('#layout-title input').attr("placeholder", "<?php echo JText::_('COM_OS_CCK_LAYOUT_ERROR_TITLE'); ?>");
      jQuerCCK('#layout-title input').css("border-color","#FF0000");
      jQuerCCK('#layout-title input').css("color","#FF0000");
      jQuerCCK('#layout-title input').keypress(function() {
        jQuerCCK('#layout-title input').css("border-color","gray");
        jQuerCCK('#layout-title input').css("color","inherit");
      });
    }
  //end
    //fn fot delete row
    function del_field(){
      if(debug){
        console.log('del_field()');
      }

   

      jQuerCCK("#content-block .delete-field").on('click',function() {
        if(debug){
          console.log('#content-block .delete-field::click');
        }

        field_block = jQuerCCK(this).parent();

        var f_parent = field_block.next('i.f-parent');
        field_block.next('i.f-parent').remove();

        jQuerCCK(this).parent().remove();

        field_block.find('.col_box.admin_aria_hidden').after(f_parent);

        //check unique fields
        if(jQuerCCK(field_block).find(".field-name")){
          if(jQuerCCK(field_block).find(".field-name").data("field-name").indexOf("custom_code_field_") >= 0
              || jQuerCCK(field_block).find(".field-name").data("field-name") == 'joom_pagination'
              || jQuerCCK(field_block).find(".field-name").data("field-name") == 'joom_alphabetical'
              || jQuerCCK(field_block).find(".field-name").data("field-name") == 'calendar_month_year'
              || jQuerCCK(field_block).find(".field-name").data("field-name") == 'calendar_pagination'
              ){
            // jQuerCCK("#options-field-"+jQuerCCK(field_block).find(".field-name").data("field-name")).remove();
            return;
          }
        }

        //return field to fields-block
        field_block.find(".delete-field, .f-inform-button").remove();
        field_block.removeClass("drop-item").addClass("field-block");
        field_block.wrapInner( "<div></div>" )
        if(jQuerCCK("#new-field-block").length){
          jQuerCCK("#new-field-block").before(field_block);
        }else{
          jQuerCCK("#attached-block").before(field_block);

        }

        make_draggable();
      });
    }
  //end
    //fn fo delete layout row
    function del_layout(){
      if(debug){
        console.log('del_layout()');
      }
      jQuerCCK("#content-block .delete-layout").on('click',function() {
        if(debug){
          console.log('#content-block .delete-layout::click');
        }
        var closest_resizable = jQuerCCK(this).closest('.resizable')
        field_block = jQuerCCK(this).parent();
        field_block.find(".delete-layout, .l-inform-button").remove();
        field_block.removeClass("drop-item").addClass("attached-layout-block");
        field_block.wrapInner( "<div></div>" )
        //removing options for this field
        jQuerCCK("#options-field-"+field_block.find(".layout-name").data("field-name")).remove();
        jQuerCCK(this).parent().remove();
        jQuerCCK("#add-layout").before(field_block);
        make_attachedDraggable();
      });
    }
  //end
    //fn fo delete module row
    function del_module(){
      if(debug){
        console.log('del_module()');
      }
      jQuerCCK("#content-block .delete-module").on('click',function() {
        if(debug){
          console.log('#content-block .delete-module::click');
        }
        var closest_resizable = jQuerCCK(this).closest('.resizable')
        field_block = jQuerCCK(this).parent();
        //del module id from moduleIds value
        deleteModuleIds(field_block.find(".module-name").data("field-name"));
      //end
        field_block.find(".delete-module, .m-inform-button").remove();
        field_block.removeClass("drop-item").addClass("attached-module-block");
        field_block.wrapInner( "<div></div>" )
        //removing options for this field
        jQuerCCK("#options-field-"+field_block.find(".module-name").data("field-name")).remove();
        jQuerCCK(this).parent().remove();
        jQuerCCK("#add-module").before(field_block);
        make_attachedDraggable();
      });
    }
  //end
    //fn for adding attached layout
    function addAttachedLayout(lid, lname){
      if(debug){
        console.log('addAttachedLayout('+lid+','+lname+')');
      }
      html_item = '<div class="attached-layout-block ">'+
                    '<div>'+
                      '<span class="layout-name '+lid+'-label-hidden" data-field-name="'+lid+'">'+lname+'</span>'+
                      '<div class="col_box admin_aria_hidden">{|l-'+lid+'|}</div>'+
                      '<input class="f-params" name="fi_Params_l'+lid+'" type="hidden" value="{}">'+
                    '</div>'+
                  '</div>';
      jQuerCCK('#attached-layout-modal').modal('hide');
      showSaveButtons();
      jQuerCCK("#add-layout").before(html_item);
      jQuerCCK("#attached-layout-row-"+lid).remove();
      make_attachedDraggable();
      make_droppable();
    }
  //end
    //fn for adding attached layout
    function addAttachedModule(id, mname){
      if(debug){
        console.log('addAttachedModule('+id+','+mname+')');
      }
      html_item = '<div class="attached-module-block ">'+
                    '<div>'+
                      '<span class="module-name" data-field-name="m_'+id+'">'+mname+'</span>'+
                      '<div class="col_box admin_aria_hidden">{|m-'+id+'|}</div>'+
                    '</div>'+
                  '</div>';
      jQuerCCK('#attached-module-modal').modal('hide');
      showSaveButtons();
      jQuerCCK("#add-module").before(html_item);
      jQuerCCK("#attached-module-row-"+id).remove();
      make_attachedDraggable();
      make_droppable();
    }
  //end
    //fn sfor save label styling
    function save_label_styling(){
      if(debug){
        console.log('save_label_styling()');
      }
      jQuerCCK.each( jQuerCCK("#content-block .drop-item .field-name,"+
                            "#content-block .drop-item .layout-name"), function(i, element){
        if(jQuerCCK(element).parent().find(".f-params").val()){

          labelOptions = window.JSON.parse(jQuerCCK(element).parent().find(".f-params").val());
          //get value
          field_label_margin_top =labelOptions.labelMarginTop;
          field_label_margin_right =labelOptions.labelMarginRight;
          field_label_margin_bottom = labelOptions.labelMarginBottom;
          field_label_margin_left =labelOptions.labelMarginLeft;
          if(labelOptions.labelMargin){
            field_label_margin_px = 'px';
          }
          else{
            field_label_margin_px = '%';
          }
          if(labelOptions.labelPadding){
            field_label_padding_px = 'px';
          }
          else{
            field_label_padding_px = '%';
          }
          field_label_padding_top = labelOptions.labelPaddingTop;
          field_label_padding_right = labelOptions.labelPaddingRight;
          field_label_padding_bottom = labelOptions.labelPaddingBottom;
          field_label_padding_left = labelOptions.labelPaddingLeft;
          field_label_border = labelOptions.labelBorderSize;
          field_label_custom_class = labelOptions.labelCustomClass;
          field_label_border_color = labelOptions.labelBorderColor;
          field_label_background_color = labelOptions.labelBackgroundColor;
          field_label_font_size = labelOptions.labelFontSize;
          if(typeof(labelOptions.labelFontWeight) == 'undefined'){
            field_label_font_weight = 1;
          }else{
            if(labelOptions.labelFontWeight ==='' || labelOptions.labelFontWeight){
              field_label_font_weight = 1;
            }else{
              field_label_font_weight = 0;
            }
          }
          field_label_font_color = labelOptions.labelFontColor;
          //end
          //set style variables
          label_margin = '';
          if(field_label_margin_top){
            label_margin += 'margin-top:'+field_label_margin_top+field_label_margin_px+';';
          }
          if(field_label_margin_right){
            label_margin += 'margin-right:'+field_label_margin_right+field_label_margin_px+';';
          }
          if(field_label_margin_bottom){
            label_margin += 'margin-bottom:'+field_label_margin_bottom+field_label_margin_px+';';
          }
          if(field_label_margin_left){
            label_margin += 'margin-left:'+field_label_margin_left+field_label_margin_px+';';
          }
          label_padding = '';
          if(field_label_padding_top){
            label_padding += 'padding-top:'+field_label_padding_top+field_label_padding_px+';';
          }
          if(field_label_padding_right){
            label_padding += 'padding-right:'+field_label_padding_right+field_label_padding_px+';';
          }
          if(field_label_padding_bottom){
            label_padding += 'padding-bottom:'+field_label_padding_bottom+field_label_padding_px+';';
          }
          if(field_label_padding_left){
            label_padding += 'padding-left:'+field_label_padding_left+field_label_padding_px+';';
          }
          if(field_label_custom_class){
            jQuerCCK(element).addClass(field_label_custom_class);
          }
          label_border = '';
          if(field_label_border){
            if(!field_label_border_color)field_label_border_color = 'white';
            label_border += 'border:'+field_label_border+'px solid '+field_label_border_color+';';
          }
          label_background_color = '';
          if(field_label_background_color){
            label_border += 'background-color:'+field_label_background_color+';';
          }
          label_font_size = '';
          if(field_label_font_size){
            label_font_size += 'font-size:'+field_label_font_size+'px;';
            label_font_size += 'line-height:'+field_label_font_size+'px;';
          }
          label_font_weight = '';
          if(field_label_font_weight){
            label_font_weight += 'font-weight:normal;';
          }else{
            label_font_weight += 'font-weight:bold;';
          }
          label_font_color = '';
          if(field_label_font_color){
            label_font_color += 'color:'+field_label_font_color+';';
          }
          //end
          //set style atribute
          label_style = '';
          if(label_margin || label_padding || label_border || label_background_color || field_label_font_size
             || field_label_font_weight || field_label_font_color || label_font_weight){
            label_style = label_margin+label_padding+label_border+label_background_color+label_font_size+
                          label_font_weight+label_font_color;
          }
          if(label_style){
            jQuerCCK(element).attr("data-label-styling",label_style);
          }else{
            jQuerCCK(element).attr("data-label-styling",'');
          }
          //end
        }
      });
    }
  //end
    //fn for save block styling
    function save_block_styling(){
      if(debug){
        console.log('save_block_styling()');
      }

      jQuerCCK.each( jQuerCCK("#editor-block .drop-area, #editor-block .content-row"), function(i, element){
        if(jQuerCCK(element).data('row-number')){
          field_name = 'row';
          optionsBlock = window.JSON.parse(jQuerCCK(element).find(".row-params").val());
        }else if(jQuerCCK(element).data('column-number')){
          field_name = 'col';
          optionsBlock = window.JSON.parse(jQuerCCK(element).find(".col-params").val());
        }
        //get value
        margin_top = optionsBlock.marginTop;
        margin_right = optionsBlock.marginRight;
        margin_bottom = optionsBlock.marginBottom;
        margin_left = optionsBlock.marginLeft;
        if(optionsBlock.margin){
          margin_px = 'px';
        }
        else{
          margin_px = '%';
        }
        if(optionsBlock.padding){
          padding_px = 'px';
        }
        else{
          padding_px = '%';
        }
        padding_top = optionsBlock.paddingTop;

        padding_right = optionsBlock.paddingRight;
        padding_bottom = optionsBlock.paddingBottom;
        padding_left = optionsBlock.paddingLeft;
        border = optionsBlock.borderSize;
        custom_class = optionsBlock.customClass;
        border_color = optionsBlock.borderColor;
        background_color = optionsBlock.backgroundColor;
        font_size = optionsBlock.fontSize;
        font_weight = optionsBlock.fontWeight;
        font_color = optionsBlock.fontColor;
        //end
        //set style variables
        margin = '';
        if(margin_top){
          margin += 'margin-top:'+margin_top+margin_px+';';
        }
        if(margin_right){
          margin += 'margin-right:'+margin_right+margin_px+';';
        }
        if(margin_bottom){
          margin += 'margin-bottom:'+margin_bottom+margin_px+';';
        }
        if(margin_left){
          margin += 'margin-left:'+margin_left+margin_px+';';
        }
        padding = '';
        if(padding_top){
          padding += 'padding-top:'+padding_top+padding_px+';';
        }
        if(padding_right){
          padding += 'padding-right:'+padding_right+padding_px+';';
        }
        if(padding_bottom){
          padding += 'padding-bottom:'+padding_bottom+padding_px+';';
        }
        if(padding_left){
          padding += 'padding-left:'+padding_left+padding_px+';';
        }
        if(custom_class){
          jQuerCCK(element).addClass(custom_class);
        }
        border_style = '';
        if(border){
          if(!border_color)border_color = 'white';
          border_style += 'border:'+border+'px solid '+border_color+';';
        }
        background_color_style = '';
        if(background_color){
          background_color_style += 'background-color:'+background_color+';';
        }
        font_size_style = '';
        if(font_size){
          font_size_style += 'font-size:'+font_size+'px;';
        }
        font_weight_style = '';
        if(font_weight){
          font_weight_style += 'font-weight:'+font_weight+';';
        }
        font_color_style = '';
        if(font_color){
          font_color_style += 'color:'+font_color+';';
        }
        //end
        //set style atribute
        style = '';
        if(margin || padding || border_style || background_color_style || font_size_style
           || font_weight_style || font_color_style){
          style = margin+padding+border_style+background_color_style+font_size_style+
                        font_weight_style+font_color_style;
        }
        if(style){
          jQuerCCK(element).attr("data-block-styling",style);
        }
        //end
      });
    }
  //end
    //fn for change tag
    function change_tag_shell(htmlstring){
      if(debug){
        console.log('change_tag_shell('+htmlstring+')');
      }
      jQuerCCK.each( jQuerCCK(htmlstring).find(".field-name"), function(i, element){
        field_name = jQuerCCK(element).data('field-name');
        label_tag = jQuerCCK("#fi_label_tag_"+field_name).val();
        if(label_tag && label_tag != 'span'){
          // Create a new element and assign it attributes from the current element
          var NewElement = jQuerCCK("<"+label_tag+" />");
          jQuerCCK.each(element.attributes, function(i, attrib){
            jQuerCCK(NewElement).attr(attrib.name, attrib.value);
          });
          // Replace the current element with the new one and carry over the contents
          jQuerCCK(element).replaceWith(function () {
            return jQuerCCK(NewElement).append(jQuerCCK(element).contents());
          });
        }
      });
      
    }
  //end

    function applyAliasTooltip(htmlstring){
      if(debug){
        console.log('applyFieldAlias('+htmlstring+')');
      }
      jQuerCCK.each( jQuerCCK(htmlstring).find(".field-name"), function(i, element){
        field_name = jQuerCCK(element).data('field-name');
        if(jQuerCCK("input[name='fi_"+field_name+"_alias']").length){
          fieldAlias = jQuerCCK("input[name='fi_"+field_name+"_alias']").val();
          jQuerCCK(element).html(fieldAlias);
        }
        if(jQuerCCK("input[name='fi_"+field_name+"_tooltip']").length){
          fieldTooltip = jQuerCCK("input[name='fi_"+field_name+"_tooltip']").val();
          jQuerCCK(element).parents(".drop-item").attr("data-content",fieldTooltip);
        }
      });

      jQuerCCK.each( jQuerCCK(htmlstring).find(".layout-name"), function(i, element){
        field_name = jQuerCCK(element).data('field-name');
        if(jQuerCCK("input[name='vi_show_request_layout_alias["+field_name+"][]']").length){
          fieldAlias = jQuerCCK("input[name='vi_show_request_layout_alias["+field_name+"][]']").val();
          jQuerCCK(element).html(fieldAlias);
        }
        if(jQuerCCK("input[name='vi_show_request_layout_alias["+field_name+"][]']").length){
          fieldTooltip = jQuerCCK("input[name='vi_show_request_layout_alias["+field_name+"][]']").val();
          jQuerCCK(element).parents(".drop-item").attr("data-content",fieldTooltip);
        }
      });
    }

    //fn for return span shell in drop-item
    function return_span_shell(){
      if(debug){
        console.log('return_span_shell()');
      }
      
      jQuerCCK.each( jQuerCCK(".drop-item .field-name"), function(i, element){
        field_name = jQuerCCK(element).data('field-name');
        label_tag = jQuerCCK("#fi_label_tag_"+field_name).val();
        if(label_tag && label_tag != 'span'){
          // Create a new element and assign it attributes from the current element
          var NewElement = jQuerCCK("<span />");
          jQuerCCK.each(this.attributes, function(i, attrib){
            jQuerCCK(NewElement).attr(attrib.name, attrib.value);
          });
          // Replace the current element with the new one and carry over the contents
          jQuerCCK(this).replaceWith(function () {
            return jQuerCCK(NewElement).append(jQuerCCK(this).contents());
          });
        }
      });

    }
    //end
    //function for save and apply layout
    function saveLayout(task_name){
      if(debug){
        console.log('saveLayout('+task_name+')');
      }
      //save styling
      writeSettings();
      //remove width: 175 px
      jQuerCCK.each( jQuerCCK("div.admin_aria_hidden"), function(i, element){
        //jQuerCCK(element).attr("style","");
        jQuerCCK(element).removeAttr("style") ; 
      });
      //end
      //save layout styling
      save_label_styling();
      //save block styling
      save_block_styling();
      //end
      var htmlstring = jQuerCCK("#content-block").clone();
      change_tag_shell(htmlstring);
      applyAliasTooltip(htmlstring);
      htmlstring = jQuerCCK(htmlstring).html();
      var form = jQuerCCK("#adminForm").serialize();
      form_params = jQuerCCK("#editor-block .form-params").val();
      fieldsParams = jQuerCCK("#content-block .f-params").val();
      jQuerCCK.post("index.php?option=com_os_cck&task="+task_name+"&format=raw",
                   {form_data:form, layout_html:htmlstring,
                    columns_number: columnNumber, rows_number: rowNumber,
                    form_params:form_params,fieldsParams:fieldsParams},
      function (data) {
        if (data.status == "<?php echo JText::_("COM_OS_CCK_SUCCESS"); ?>") {
          if(data.lid){
            jQuerCCK("#lid").val(data.lid);
          }
          if(task_name == 'apply_layout'){
            jQuerCCK("#messages").addClass("alert alert-success");
            jQuerCCK('#result-message').html("<?php echo JText::_('COM_OS_CCK_LAYOUT_SAVE_SUCCESSFUL');?>");
            setTimeout("jQuerCCK('#result-message').html('');jQuerCCK('#messages').removeClass('alert alert-success');", 3000);
          }else{//save layout
            jQuerCCK("#messages").addClass("alert alert-success");
            jQuerCCK('#result-message').html("<?php echo JText::_('COM_OS_CCK_LAYOUT_SAVE_SUCCESSFUL');?>");
            setTimeout("jQuerCCK('#result-message').html('');jQuerCCK('#messages').removeClass('alert alert-success');", 950);
            setTimeout("window.location = 'index.php?option=com_os_cck&task=manage_layout'", 1000);
          }
        }else if(data.status == 'cancel'){
            window.location = 'index.php?option=com_os_cck&task=manage_layout';
        } else {
          if(data.status == 'title'){
            empty_title();
            return;
          }
          jQuerCCK("#messages").addClass("alert alert-danger");
          jQuerCCK('#result-message').html("<?php echo JText::_('COM_OS_CCK_FUCKING_ERROR');?>");
          setTimeout("jQuerCCK('#result-message').html('');jQuerCCK('#messages').removeClass('alert alert-danger');", 3000);
        }
      } , 'json' );
    }
    //end
    //add row fn
    var columnNumber = <?php echo isset($layout_params['columns_number'])?$layout_params['columns_number']:1;?>;
    var rowNumber = <?php echo isset($layout_params['rows_number'])?$layout_params['rows_number']:1;?>;

    function addRow(number){
      if(debug){
        console.log('addRow('+number+')');
      }
      var n = 12/number;
      var str = '<div class="row content-row cck-row-'+rowNumber+'" data-row-number="'+(rowNumber++)+'">';
      for (var i = 0; i<number; i++) {
        if (number == 1) {
          str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
        }
        if (number == 2) {
          if (i == 1) {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          } else {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          }
        }
        if (number == 3) {
          if (i == 2) {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          } else {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          }
        }
        if (number == 4) {
          if (i == 3) {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          } else {
            str += '<div id="cck_col-'+(columnNumber++)+'" class="resizable col-lg-' +n+ ' col-md-' +n+ ' col-sm-' +n+ ' col-xs-' +n+ ' drop-area column-sortable items" data-column-number="'+columnNumber+'"><input class="col-params" type="hidden" value="{}"></div>';
          }
        }

      }
      str += '<span class="delete-row"></span><input class="row-params" type="hidden" value="{}"></div>';
      jQuerCCK("#content-block").append(str);
      make_colorpicker();
      makeDeleteRow();
      make_resize_grid();
      make_droppable();
      make_sortable();
      makeOptions();
      //scroll bottom
      window.scrollTo(0,findPosY(document.getElementById("add-row"))-350);
    }
  //end
  // --------------------------------------------------READY BLOCK START-----------------------------\\
    //triger click functions
    jQuerCCK("select.editor-type").change(function(event) {
      make_editor();
    });
    //init codemirror
    function make_editor(){
      if(debug){
        console.log('make_editor()');
      }
      jQuerCCK(".editor-button, .save-editor-button").unbind('click');
      jQuerCCK(".editor-button").on('click',function(event) {
        jQuerCCK(".joomla-editor").hide();
        //jQuerCCK("#editor-area").attr("style","");
        jQuerCCK(".CodeMirror").remove();
        //tinyMCE.remove();
        if(debug){
          console.log('.editor-button::click');
        }
        jQuerCCK('#editor-modal').css('z-index', '1500');
        jQuerCCK('#editor-modal').modal();
        mode = jQuerCCK("select.editor-type:visible").parents("div[id^='options-field-custom_code_field']").find("select.editor-type").val();
        textValue = jQuerCCK(".custom-code-editor:visible").val();
        if(mode != "TEXT" || !joomlaEditor){

          if(mode == "HTML"){
            mode = "text/html";
          }else if(mode == "PHP" || mode == "SCRIPT"){
            mode = "javascript";
          }else if(mode == "CSS"){
            mode = "text/css";
          }else{
            mode = "text/html";
          }

          CodeMirror.commands.autocomplete = function(cm) {
            cm.showHint({hint: CodeMirror.hint.anyword});
          }
          var editor = CodeMirror.fromTextArea(document.getElementById('editor-area'), {
            lineNumbers: true,
            mode: mode,
            keyMap: "sublime",
            autoCloseBrackets: true,
            matchBrackets: true,
            showCursorWhenSelecting: true,
            theme: "monokai",
            styleActiveLine: true,
            tabSize: 2,
            extraKeys: {
              "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
              },
              "Esc": function(cm) {
                if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
              },
              "Ctrl-Space": "autocomplete"
            }
          });
          editor.setValue(textValue);
          setTimeout(function() {
              editor.refresh();
          },1000);
          editor.on("change", function(cm, change) {
            jQuerCCK(".custom-code-editor:visible").val(editor.getValue());
          })
          jQuerCCK(".save-editor-button").on('click',function(event) {
            jQuerCCK('#editor-modal').modal("hide");
          });
        }else{
         // tinymce.init({});
          tinyMCE.activeEditor.setContent(textValue);

          <?php if($editor != "codemirror"){?>
            jQuerCCK(".joomla-editor").show();

            <?php 
              // echo $editor->setContent('cckEditor','textValue');
            ?>

            jQuerCCK(".save-editor-button").on('click',function(event) {
              jQuerCCK(".custom-code-editor:visible").val(<?php echo str_replace(';','',$editor->getContent('cckEditor'))?>);
              jQuerCCK('#editor-modal').modal("hide");
            });
          <?php }?>
          // tinymce.init({
          //   selector: 'textarea#editor-area',
          //   setup: function (editor) {
          //     editor.on('change', function () {
          //       jQuerCCK(".custom-code-editor:visible").val(editor.getContent());
          //     });
          //   },
          //   height: 300,
          //   plugins: [
          //     'advlist autolink lists link image charmap preview anchor',
          //     'searchreplace visualblocks code fullscreen',
          //     'insertdatetime media table contextmenu paste'
          //   ],
          //   toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image code',
          //   content_css: [
          //     '//www.tinymce.com/css/codepen.min.css'
          //   ]
          // });
          // tinyMCE.activeEditor.setContent(textValue);
          
        }
        
      });
    }
    //end

    function make_required(){
      jQuerCCK(".require-checkbox").click(function(event) {
        if(jQuerCCK(this).prop('checked')){
          jQuerCCK(".field-name[data-field-name='"+jQuerCCK(this).attr("data-field-name")+"']")
            .html(jQuerCCK(".field-name[data-field-name='"+jQuerCCK(this).attr("data-field-name")+"']").html()+'*');
          jQuerCCK("[name=fi_"+jQuerCCK(this).attr("data-field-name")+"_alias]").val(jQuerCCK("[name=fi_"+jQuerCCK(this).attr("data-field-name")+"_alias]").val()+'*');
        }else{
          jQuerCCK(".field-name[data-field-name='"+jQuerCCK(this).attr("data-field-name")+"']")
            .html(jQuerCCK(".field-name[data-field-name='"+jQuerCCK(this).attr("data-field-name")+"']").html().replace('*',''));
          jQuerCCK("[name=fi_"+jQuerCCK(this).attr("data-field-name")+"_alias]").val(jQuerCCK("[name=fi_"+jQuerCCK(this).attr("data-field-name")+"_alias]").val().replace('*',''));
        }
      });
    }

    //modal for add column
    function make_add_row(){
      if(debug){
        console.log('make_add_row()');
      }
      jQuerCCK("#layout-modal .culumn-num").on('click',function() {
        if(debug){
          console.log('#layout-modal .culumn-num::click');
        }
        var num = jQuerCCK(this).attr('data-column');
        addRow (num);
        jQuerCCK('#layout-modal').modal('hide');
      });

      jQuerCCK("#add-row").on('click',function() {
        if(debug){
          console.log('#add-row::click');
        }
        writeSettings();
        jQuerCCK('#layout-modal').css('z-index', '1500');
        jQuerCCK('#layout-modal').modal();
      });
    }
  //end
    //modal for  attached layout
    function make_attached_layout(){
      if(debug){
        console.log('make_attached_layout()');
      }
      jQuerCCK("#add-layout").on('click',function() {
        if(debug){
          console.log('#add-layout::click');
        }
        jQuerCCK('#attached-layout-modal').css('z-index', '1500');
        jQuerCCK('#attached-layout-modal').modal();
        hideSaveButtons();
      });
    }
  //end
    //modal for  attached module
    function make_attached_module(){
      if(debug){
        console.log('make_attached_module()');
      }
      jQuerCCK("#add-module").on('click',function() {
        if(debug){
          console.log('#add-module::click');
        }
        jQuerCCK('#attached-module-modal').css('z-index', '1500');
        jQuerCCK('#attached-module-modal').modal();
        hideSaveButtons();
      });
    }
  //end

  function addLayoutField(fid){
    if(fid){
      jQuerCCK.post("index.php?option=com_os_cck&task=editLayoutField&fid="+fid+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {},
      function (data) {
        jQuerCCK("#add-field-modal .modal-body").html(data.html);
      } , 'json' );
    }else{
      jQuerCCK.post("index.php?option=com_os_cck&task=addLayoutField&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {},
      function (data) {
        jQuerCCK("#add-field-modal .modal-body").html(data.html);
      } , 'json' );
    }
  }

  function checkFieldType(val){
    if(val == 'text_single_checkbox_onoff' || val == 'text_radio_buttons'){
      jQuerCCK("#allowed-block, #default-block").show();
      switch (val) {
        case 'text_single_checkbox_onoff':
          jQuerCCK("#allowed-value").val("on|on\noff|off");
          jQuerCCK("#default-value").val("");
          break;
        
        case 'text_radio_buttons':
          jQuerCCK("#allowed-value").val("1|Yes\n0|No");
          jQuerCCK("#default-value").val("");
          break;
          
      }
    }else if(val == 'text_select_list'){
      jQuerCCK("#allowed-block-select, #default-block-select").show();
        //  case 'text_select_list':
        //  jQuerCCK("#allowed-value").val("One\nTwo\nThree");
        //  jQuerCCK("#default-value").val("");
        //  break; 
    }else{
      jQuerCCK("#allowed-block, #default-block,#allowed-block-select, #default-block-select").hide();
    }
  }  

  function checkboxFields(el){
    if(jQuerCCK(el).attr("name") == "toggle"){
      jQuerCCK(".field-id-checkbox").prop("checked",jQuerCCK(el).prop('checked'));
    }
  }

  function deleteLayoutField(){
    if(!jQuerCCK(".field-id-checkbox:checked").length){
      alert("Please select field for delete!");
      return;
    }

    var fid = [];
    jQuerCCK(".field-id-checkbox:checked").each(function(index, el) {
      fid.push(jQuerCCK(el).val());
    });
    jQuerCCK.post("index.php?option=com_os_cck&task=deleteLayoutField&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {fid: fid},
    function (data) {
      jQuerCCK("#add-field-modal .modal-body").html(data.html);
    } , 'json' );
  }

  function pubLayoutFields(fid, task){
    jQuerCCK.post("index.php?option=com_os_cck&task="+task+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {fid: fid},
    function (data) {
      jQuerCCK("#add-field-modal .modal-body").html(data.html);
    } , 'json' );
  }

  function inInstLayoutFields(fid, task){
    jQuerCCK.post("index.php?option=com_os_cck&task="+task+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {fid: fid},
    function (data) {
      jQuerCCK("#add-field-modal .modal-body").html(data.html);
    } , 'json' );
  }

  function saveLayoutField(fid){
    
    //get allow_value out of inputs   
    var allowed_values_select = [];
    jQuerCCK(".allowed-value").each(function(key, el){
      allowed_values_select.push(jQuerCCK(el).val());
    })

    //get child_select out of inputs   
    var child_assoc = [];
    jQuerCCK(".child-assoc").each(function(key, el){
      child_assoc.push(jQuerCCK(el).val());
    })

    if (jQuerCCK("#global_settings_label").val() == '') {
      window.scrollTo(0,findPosY(jQuerCCK("#global_settings_label"))-100);
      jQuerCCK("#global_settings_label").attr("placeholder", "<?php echo JText::_('COM_OS_CCK_ERROR_FIELD_LABLE');?>");
      jQuerCCK("#global_settings_label").css("borderColor", "#FF0000");
      jQuerCCK("#global_settings_label").css("color", "#FF0000");
      return;
    }

    if(jQuerCCK("#field_name").val() == ''){
        jQuerCCK("#field_name").css('borderColor','red');
        return;
    }

    if(jQuerCCK("#field_type").val() == 'text_single_checkbox_onoff' 
      || jQuerCCK("#field_type").val() == 'text_radio_buttons'){
      var allowed_values = jQuerCCK("#allowed-value").val();
    }else{
      var allowed_values = allowed_values_select;
    }


    jQuerCCK.post("index.php?option=com_os_cck&task=saveLayoutField&fid="+fid+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
      { 
        field_name: jQuerCCK("#field_name").val(),
        field_type: jQuerCCK("#field_type").val(),
        allowed_value: allowed_values,
        child_select: child_assoc,
        default_value: jQuerCCK("#default-value").val()
      },
    function (data) {
      cckBack('showFieldList');
      jQuerCCK("#add-field-modal .modal-body").append(data.html);
    } , 'json' );
  }

  function updateLayoutFieldList(){
    jQuerCCK.post("index.php?option=com_os_cck&task=updateLayoutFieldList&lid[]="+jQuerCCK("#lid").val()+"&layout_type="+jQuerCCK("[name='type']").val()+"&entity_id="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
      {},
    function (data) {
      jQuerCCK("#fields-block").html(data.html);
      make_attached_layout();
      make_attached_module();
      make_synchronize_fields();
      make_sortable();
      make_droppable();
      make_draggable();
      makeOptions();
      make_add_field();
      makeSearchInFields();
    } , 'json' );
  }

  function editLayoutField(el, fieldId){
    text = jQuerCCK(el).parent().find(".field-name-list").text();
    html = '<span class="edit-name-block">'+
              '<input class="new-field-name" type="text" value="'+text+'">'+
              '<span class="save-new-layout-field-name" onclick="saveNewFieldName(this,'+fieldId+')">Save</span>'+
              '<span class="cancel-edit-firld-name" onclick="cancelEditFieldName(this,'+fieldId+')">Cancel</span>'+
            '</span>';
    jQuerCCK(el).parent().find(".field-name-list")
      .hide()
      .parent()
      .append(html);
    jQuerCCK(jQuerCCK(el).parent().find(".edit-field-name-icon")).hide();
  }

  function cancelEditFieldName(el, fid){
    jQuerCCK(el).parent().parent()
      .find(".edit-name-block")
      .remove();
    jQuerCCK(".field-name-list, .edit-field-name-icon").show();
  }

  function saveNewFieldName(el, fid){
    fieldInput = jQuerCCK(el).parent().find(".new-field-name");
    if (jQuerCCK(fieldInput).val() == '') {
      window.scrollTo(0,findPosY(jQuerCCK(fieldInput))-100);
      jQuerCCK(fieldInput).attr("placeholder", "<?php echo JText::_('COM_OS_CCK_ERROR_FIELD_LABLE');?>");
      jQuerCCK(fieldInput).css("borderColor", "#FF0000");
      jQuerCCK(fieldInput).css("color", "#FF0000");
      return;
    }

    jQuerCCK.post("index.php?option=com_os_cck&task=saveLayoutFieldName&fid="+fid+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
      { 
        field_name: jQuerCCK(fieldInput).val(),
      },
    function (data) {
      jQuerCCK("#add-field-modal .modal-body").html(data.html);
    } , 'json' );
  }

  function cckBack(task){
    jQuerCCK.post("index.php?option=com_os_cck&task="+task+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",{},
    function (data) {
        jQuerCCK("#add-field-modal .modal-body").html(data.html);
        makeSearchInEntityFields();
    } , 'json' );
  }

  //modal for  add-field module
    function make_add_field(){
      if(debug){
        console.log('make_add_field()');
      }
      jQuerCCK("#add-field").on('click',function() {
        if(debug){
          console.log('#add-field::click');
        }
        jQuerCCK('#add-field-modal').css('z-index', '1500');
        jQuerCCK('#add-field-modal').modal({
          backdrop:'static',
          keyboard:false
        });
      });
      jQuerCCK('#add-field-modal').on('shown.bs.modal', function (e) {
        hideSaveButtons();
        jQuerCCK.post("index.php?option=com_os_cck&task=showFieldList&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                     {},
        function (data) {
          jQuerCCK("#add-field-modal .modal-body").html(data.html);
          makeSearchInEntityFields();
        } , 'json' );
      })
    }
  //end

    function hideSaveButtons(){
      if(debug){
          console.log('hideSaveButtons()');
      }
      jQuerCCK("#layout-buttons").hide();
    }

    function showSaveButtons(){
      if(debug){
          console.log('showSaveButtons()');
      }
      jQuerCCK("#layout-buttons").show();
    }

    function addFieldModalClose(){
      jQuerCCK('#add-field-modal').modal('hide');
      jQuerCCK("#add-field-modal .modal-body").html('');
      updateLayoutFieldList();
      showSaveButtons();
    }

    //fn make field mask
    function make_field_mask(){
      if(debug){
        console.log('make_field_mask()');
      }
      jQuerCCK("#add-field-mask").on('click', function() {
        if(debug){
          console.log('#add-field-mask::click');
        }

        jQuerCCK('#field-mail-modal').css('z-index', '1500');
        jQuerCCK('#field-mail-modal').modal();
      });
    }
  //end

   //fn make field mask
    function make_field_mask_custom(){
      if(debug){
        console.log('make_field_mask_custom()');
      }
      jQuerCCK("#field_options").on('click', '#add-field-mask-custom',function() {
        if(debug){
          console.log('#add-field-mask::click');
        }

        jQuerCCK('#field-custom-modal').attr('custom_code_field_name',jQuerCCK(this).parent('div').find('textarea').attr('name')); 
        jQuerCCK('#field-custom-modal').css('z-index', '1500');
        jQuerCCK('#field-custom-modal').modal();
      });
    }

  //end
    //fn for popover hide/show
    function make_popover(){
      if(debug){
        console.log('make_popover()');
      }
      jQuerCCK("input[name^='fi_description_']").on('click',function() {

        if(debug){
          console.log("input[name^='fi_description_']::click");
        }
      
        fieldName = jQuerCCK(this).data("field-name");

        objthis = this

        // if(this.checked){
        //   jQuerCCK(".admin .field-name[data-field-name='"+fieldName+"']").parent()
        //     .prepend('<span class="cck-help-string" data-field-name="'+fieldName+'">'+jQuerCCK("[name='fi_"+fieldName+"_tooltip']").val()+'</span>');
        // }else{
        //   jQuerCCK(".admin .cck-help-string[data-field-name='"+fieldName+"']").remove();
        // }

      });

      //   jQuerCCK("input[name*='_tooltip']").keyup(function() {

      //   jQuerCCK(".admin .cck-help-string[data-field-name='"+fieldName+"']").remove();

      //   if(objthis.checked){
      //     jQuerCCK(".admin .field-name[data-field-name='"+fieldName+"']").parent()
      //     .prepend('<span class="cck-help-string" data-field-name="'+fieldName+'">'+jQuerCCK("[name='fi_"+fieldName+"_tooltip']").val()+'</span>');
      //   }else{
      //     jQuerCCK(".admin .cck-help-string[data-field-name='"+fieldName+"']").remove();
      //   }
        
      // });


    }
  //end

  //modal for  location-field 
    function openLocationModal(fildName){
      hideSaveButtons();
      if(debug){
        console.log('openLocationModal('+fildName+')');
      }
      jQuerCCK('#location-field-modal').css('z-index', '1500');
      jQuerCCK('#location-field-modal').modal({
        backdrop:'static',
        keyboard:false
      });
      jQuerCCK("#location-modal-address").val(jQuerCCK("#"+fieldName+"_map_address").val());
      jQuerCCK("#location-modal-country").val(jQuerCCK("#"+fieldName+"_map_country").val());
      jQuerCCK("#location-modal-region").val(jQuerCCK("#"+fieldName+"_map_region").val());
      jQuerCCK("#location-modal-city").val(jQuerCCK("#"+fieldName+"_map_city").val());
      jQuerCCK("#location-modal-zip-code").val(jQuerCCK("#"+fieldName+"_map_zip_code").val());
      jQuerCCK("#location-modal-latitude").val(jQuerCCK("#"+fieldName+"_map_latitude").val());
      jQuerCCK("#location-modal-longitude").val(jQuerCCK("#"+fieldName+"_map_longitude").val());
      jQuerCCK("#location-modal-zoom").val(jQuerCCK("#"+fieldName+"_map_zoom").val());
      jQuerCCK("#location-map-type").val(jQuerCCK("#"+fieldName+"_map_type").val());
      jQuerCCK("#location-modal-code-editor").val(jQuerCCK("#"+fieldName+"_map_code").val());

      if(jQuerCCK("#"+fieldName+"_map_as_show").val() == 'true'){
        jQuerCCK("#location-modal-as-show1").prop('checked', 'checked');
      }else{
        jQuerCCK("#location-modal-as-show2").prop('checked', 'checked');
      }
      if(jQuerCCK("#"+fieldName+"_map_hide_address").val() == 'true'){
        jQuerCCK("#location-modal-hide-address1").prop('checked', 'checked');
      }else{
        jQuerCCK("#location-modal-hide-address2").prop('checked', 'checked');
      }
      if(jQuerCCK("#"+fieldName+"_map_hide_map").val() == 'true'){
        jQuerCCK("#location-modal-hide-map1").prop('checked', 'checked');
      }else{
        jQuerCCK("#location-modal-hide-map2").prop('checked', 'checked');
      }

      enableModalMap(fildName);
      enableLocationCodeEditor();
    }
  //end

  function enableLocationCodeEditor(){
    CodeMirror.commands.autocomplete = function(cm) {
      cm.showHint({hint: CodeMirror.hint.anyword});
    }
    jQuerCCK(".CodeMirror").remove();
    var editor = CodeMirror.fromTextArea(document.getElementById('location-modal-code-editor'), {
      lineNumbers: true,
      mode: 'text/html',
      keyMap: "sublime",
      autoCloseBrackets: true,
      matchBrackets: true,
      showCursorWhenSelecting: true,
      theme: "monokai",
      styleActiveLine: true,
      tabSize: 2,
      extraKeys: {
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        },
        "Ctrl-Space": "autocomplete"
      }
    });
    editor.on("change", function(cm, change) {
      jQuerCCK("#location-modal-code-editor").val(editor.getValue());
    })
  }

  //location modal close
  function saveLocationModalSave(){
    //save params from modal to hidden location field settings
    jQuerCCK("#"+fieldName+"_map_address").val(jQuerCCK("#location-modal-address").val());
    jQuerCCK("#"+fieldName+"_map_country").val(jQuerCCK("#location-modal-country").val());
    jQuerCCK("#"+fieldName+"_map_region").val(jQuerCCK("#location-modal-region").val());
    jQuerCCK("#"+fieldName+"_map_city").val(jQuerCCK("#location-modal-city").val());
    jQuerCCK("#"+fieldName+"_map_zip_code").val(jQuerCCK("#location-modal-zip-code").val());
    jQuerCCK("#"+fieldName+"_map_latitude").val(jQuerCCK("#location-modal-latitude").val());
    jQuerCCK("#"+fieldName+"_map_longitude").val(jQuerCCK("#location-modal-longitude").val());
    jQuerCCK("#"+fieldName+"_map_zoom").val(jQuerCCK("#location-modal-zoom").val());
    jQuerCCK("#"+fieldName+"_map_type").val(jQuerCCK("#location-map-type").val());
    jQuerCCK("#"+fieldName+"_map_code").val(jQuerCCK("#location-modal-code-editor").val());
    jQuerCCK("#"+fieldName+"_map_as_show").val(jQuerCCK("input[name='location-modal-as-show']").prop("checked"));
    jQuerCCK("#"+fieldName+"_map_hide_address").val(jQuerCCK("input[name='location-modal-hide-address']").prop("checked"));
    jQuerCCK("#"+fieldName+"_map_hide_map").val(jQuerCCK("input[name='location-modal-hide-map']").prop("checked"));

    jQuerCCK('#location-field-modal').modal('hide');
    showSaveButtons();
  }
  //end
  function saveLocationModalClose(){
    jQuerCCK('#location-field-modal').modal('hide');
    showSaveButtons();
  }

  //location modal map
  function enableModalMap(fName){
    var map;
    var marker = null;
    var mapOptions;
    var image = {url: '../components/com_os_cck/images/marker.png'};
    var myOptions = {
        zoom: eval(jQuerCCK("#location-modal-zoom").val()),
        scrollwheel: false,
        center: new google.maps.LatLng(jQuerCCK("#location-modal-latitude").val(),
                                    jQuerCCK("#location-modal-longitude").val()),
        zoomControlOptions: {
           style: google.maps.ZoomControlStyle.LARGE
        },
        mapTypeId: eval('google.maps.MapTypeId.'+jQuerCCK("#location-map-type").val())
    };
    var map = new google.maps.Map(document.getElementById("location-modal-map"), myOptions);
    geocoder = new google.maps.Geocoder();
    var bounds = new google.maps.LatLngBounds ();
    //Set the marker coordinates
    var lastmarker = new google.maps.Marker({
        icon: image,
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(jQuerCCK("#location-modal-latitude").val(),
                                    jQuerCCK("#location-modal-longitude").val())
    });
    lastmarker.setMap(map);

    //If the zoom, then store it in the field map_zoom
    google.maps.event.addListener(map,"zoom_changed", function(){
        jQuerCCK("#location-modal-zoom").val(map.getZoom());
    });
    google.maps.event.addListener(map,"click", function(e){
        //Initialize marker
        marker = new google.maps.Marker({
          icon: image,
          animation: google.maps.Animation.DROP,
          position: new google.maps.LatLng(e.latLng.lat(),e.latLng.lng())
        });

        //Delete marker
        if(lastmarker) lastmarker.setMap(null);;
        //Add marker to the map
        marker.setMap(map);
        //Output marker information
        jQuerCCK("#location-modal-latitude").val(e.latLng.lat());
        jQuerCCK("#location-modal-longitude").val(e.latLng.lng());
        //Memory marker to delete
        lastmarker = marker;
    });
    jQuerCCK("#location-map-type").change(function(event) {
      enableModalMap();
    });
  }

  function updateCoordinates(latlng){
    if(latlng) 
    {
      jQuerCCK("#location-modal-latitude").val(latlng.lat());
      jQuerCCK("#location-modal-longitude").val(latlng.lng());
    }
  }

  function toggleBounce() {
    if (marker.getAnimation() != null) {
      marker.setAnimation(null);
    } else {
      marker.setAnimation(google.maps.Animation.BOUNCE);
    }
  }

  function setupModalLocation(){
    var marker;
    var myOptions = {
      zoom: eval(jQuerCCK("#location-modal-zoom").val()),
      scrollwheel: false,
      zoomControlOptions: {
         style: google.maps.ZoomControlStyle.LARGE
      },
      mapTypeId: eval('google.maps.MapTypeId.'+jQuerCCK("#location-map-type").val())
    };
    var map = new google.maps.Map(document.getElementById("location-modal-map"), myOptions);
    var address = jQuerCCK("#location-modal-address").val() +" "+jQuerCCK("#location-modal-country").val()
                  +" "+jQuerCCK("#location-modal-region").val()+" "+jQuerCCK("#location-modal-city").val()
                  +" "+jQuerCCK("#location-modal-zip-code").val();
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          updateCoordinates(results[0].geometry.location);
          
          var image = {url: '../components/com_os_cck/images/marker.png'};
          if (marker) marker.setMap(null);
          marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location,
              icon: image,
              animation: google.maps.Animation.DROP
          });
          google.maps.event.addListener(marker, 'click', toggleBounce);
          google.maps.event.addListener(marker, "dragend", function() {
              updateCoordinates(marker.getPosition());
          });
          jQuerCCK("#location-modal-address").css("borderColor", "#ccc");
          jQuerCCK("#location-modal-address").css("color", "#555");
      } else {
        enableModalMap();
        jQuerCCK("#location-modal-address").css("borderColor", "#FF0000");
        jQuerCCK("#location-modal-address").css("color", "#FF0000");
        jQuerCCK("#location-modal-latitude,#location-modal-longitude").val("");
      }
    });
  }
  //end location modal map

    function clearStyleSettings($type){
      switch($type){
        case "row":
          //set settings
          jQuerCCK("#styling-row .margin-pixels:first").prop("checked",true);
          jQuerCCK("#styling-row .margin-top").val('');
          jQuerCCK("#styling-row .margin-right").val('');
          jQuerCCK("#styling-row .margin-bottom").val('');
          jQuerCCK("#styling-row .margin-left").val('');
          jQuerCCK("#styling-row .padding-pixels:first").prop("checked",true);
          jQuerCCK("#styling-row .padding-top").val('');
          jQuerCCK("#styling-row .padding-right").val('');
          jQuerCCK("#styling-row .padding-bottom").val('');
          jQuerCCK("#styling-row .padding-left").val('');
          jQuerCCK("#styling-row .background-color").val('');
          jQuerCCK("#styling-row .border-size").val('');
          jQuerCCK("#styling-row .border-color").val('');
          jQuerCCK("#styling-row .font-size").val('');
          jQuerCCK("#styling-row .font-weight:first").prop("checked",true);
          jQuerCCK("#styling-row .font-color").val('');
          jQuerCCK("#styling-row .custom-class").val('');
        break;

        case "col":
          //set settings
          jQuerCCK("#styling-col .margin-pixels:first").prop("checked",true);
          jQuerCCK("#styling-col .margin-top").val('');
          jQuerCCK("#styling-col .margin-right").val('');
          jQuerCCK("#styling-col .margin-bottom").val('');
          jQuerCCK("#styling-col .margin-left").val('');
          jQuerCCK("#styling-col .padding-pixels:first").prop("checked",true);
          jQuerCCK("#styling-col .padding-top").val('');
          jQuerCCK("#styling-col .padding-right").val('');
          jQuerCCK("#styling-col .padding-bottom").val('');
          jQuerCCK("#styling-col .padding-left").val('');
          jQuerCCK("#styling-col .background-color").val('');
          jQuerCCK("#styling-col .border-size").val('');
          jQuerCCK("#styling-col .border-color").val('');
          jQuerCCK("#styling-col .font-size").val('');
          jQuerCCK("#styling-col .font-weight:first").prop("checked",true);
          jQuerCCK("#styling-col .font-color").val('');
          jQuerCCK("#styling-col .custom-class").val('');
        break;

        case "f":
          //set settings
          //input
          jQuerCCK("#styling-f .background-table-header-color").val('');
          jQuerCCK("#styling-f .font-table-header-color").val('');

          jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .margin-top").val('');
          jQuerCCK("#styling-f .margin-right").val('');
          jQuerCCK("#styling-f .margin-bottom").val('');
          jQuerCCK("#styling-f .margin-left").val('');
          jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .padding-top").val('');
          jQuerCCK("#styling-f .padding-right").val('');
          jQuerCCK("#styling-f .padding-bottom").val('');
          jQuerCCK("#styling-f .padding-left").val('');
          jQuerCCK("#styling-f .background-color").val('');
          jQuerCCK("#styling-f .border-size").val('');
          jQuerCCK("#styling-f .border-color").val('');
          jQuerCCK("#styling-f .font-size").val('');
          jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
          jQuerCCK("#styling-f .font-color").val('');
          jQuerCCK("#styling-f .custom-class").val('');

          //label
          jQuerCCK("#styling-f .label-margin-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .label-margin-top").val('');
          jQuerCCK("#styling-f .label-margin-right").val('');
          jQuerCCK("#styling-f .label-margin-bottom").val('');
          jQuerCCK("#styling-f .label-margin-left").val('');
          jQuerCCK("#styling-f .label-padding-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .label-padding-top").val('');
          jQuerCCK("#styling-f .label-padding-right").val('');
          jQuerCCK("#styling-f .label-padding-bottom").val('');
          jQuerCCK("#styling-f .label-padding-left").val('');
          jQuerCCK("#styling-f .label-background-color").val('');
          jQuerCCK("#styling-f .label-border-size").val('');
          jQuerCCK("#styling-f .label-border-color").val('');
          jQuerCCK("#styling-f .label-font-size").val('');
          jQuerCCK("#styling-f .label-font-weight:first").prop("checked",true);
          jQuerCCK("#styling-f .label-font-color").val('');
          jQuerCCK("#styling-f .label-custom-class").val('');
        break;

        case "l":
          //set settings
          //input
          jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .margin-top").val('');
          jQuerCCK("#styling-f .margin-right").val('');
          jQuerCCK("#styling-f .margin-bottom").val('');
          jQuerCCK("#styling-f .margin-left").val('');
          jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
          jQuerCCK("#styling-f .padding-top").val('');
          jQuerCCK("#styling-f .padding-right").val('');
          jQuerCCK("#styling-f .padding-bottom").val('');
          jQuerCCK("#styling-f .padding-left").val('');
          jQuerCCK("#styling-f .background-color").val('');
          jQuerCCK("#styling-f .border-size").val('');
          jQuerCCK("#styling-f .border-color").val('');
          jQuerCCK("#styling-f .font-size").val('');
          jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
          jQuerCCK("#styling-f .font-color").val('');
          jQuerCCK("#styling-f .custom-class").val('');
        break;
      }
    }

    //fn-s for readind settings from json string in hidden input
    function readSettings($type){
      switch($type){
        case "row":
          if(jQuerCCK("#content-block .row-active-settings").length){
            row = jQuerCCK("#content-block .row-active-settings .row-params");
            rowSettings = window.JSON.parse(jQuerCCK(row).val());
            //set settings
            if(typeof(rowSettings.margin) == 'undefined'){
              jQuerCCK("#styling-row .margin-pixels:first").prop("checked",true);
            }else{
              if(rowSettings.margin){
                jQuerCCK("#styling-row .margin-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-row .margin-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-row .margin-top").val(rowSettings.marginTop);
            jQuerCCK("#styling-row .margin-right").val(rowSettings.marginRight);
            jQuerCCK("#styling-row .margin-bottom").val(rowSettings.marginBottom);
            jQuerCCK("#styling-row .margin-left").val(rowSettings.marginLeft);
            if(typeof(rowSettings.padding) == 'undefined'){
              jQuerCCK("#styling-row .padding-pixels:first").prop("checked",true);
            }else{
              if(rowSettings.padding){
                jQuerCCK("#styling-row .padding-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-row .padding-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-row .padding-top").val(rowSettings.paddingTop);
            jQuerCCK("#styling-row .padding-right").val(rowSettings.paddingRight);
            jQuerCCK("#styling-row .padding-bottom").val(rowSettings.paddingBottom);
            jQuerCCK("#styling-row .padding-left").val(rowSettings.paddingLeft);
            jQuerCCK("#styling-row .background-color").val(rowSettings.backgroundColor);
            jQuerCCK("#styling-row .background-color").minicolors("value",rowSettings.backgroundColor);
            jQuerCCK("#styling-row .border-size").val(rowSettings.borderSize);
            jQuerCCK("#styling-row .border-color").val(rowSettings.borderColor);
            jQuerCCK("#styling-row .border-color").minicolors("value",rowSettings.borderColor);
            jQuerCCK("#styling-row .font-size").val(rowSettings.fontSize);
            if(typeof(rowSettings.fontWeight) == 'undefined'){
              jQuerCCK("#styling-row .font-weight:first").prop("checked",true);
            }else{
              if(rowSettings.fontWeight){
                jQuerCCK("#styling-row .font-weight:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-row .font-weight:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-row .font-color").val(rowSettings.fontColor);
            jQuerCCK("#styling-row .font-color").minicolors("value",rowSettings.fontColor);
            jQuerCCK("#styling-row .custom-class").val(rowSettings.customClass);
          }else{
            clearStyleSettings('row');
          }
        break;

        case "col":
          if(jQuerCCK("#content-block .col-active-settings").length){
            col = jQuerCCK("#content-block .col-active-settings .col-params");
            colSettings = window.JSON.parse(jQuerCCK(col).val());
            //set settings
            if(typeof(colSettings.margin) == 'undefined'){
              jQuerCCK("#styling-col .margin-pixels:first").prop("checked",true);
            }else{
              if(colSettings.margin){
                jQuerCCK("#styling-col .margin-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-col .margin-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-col .margin-top").val(colSettings.marginTop);
            jQuerCCK("#styling-col .margin-right").val(colSettings.marginRight);
            jQuerCCK("#styling-col .margin-bottom").val(colSettings.marginBottom);
            jQuerCCK("#styling-col .margin-left").val(colSettings.marginLeft);
            if(typeof(colSettings.padding) == 'undefined'){
              jQuerCCK("#styling-col .padding-pixels:first").prop("checked",true);
            }else{
              if(colSettings.padding){
                jQuerCCK("#styling-col .padding-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-col .padding-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-col .padding-top").val(colSettings.paddingTop);
            jQuerCCK("#styling-col .padding-right").val(colSettings.paddingRight);
            jQuerCCK("#styling-col .padding-bottom").val(colSettings.paddingBottom);
            jQuerCCK("#styling-col .padding-left").val(colSettings.paddingLeft);
            jQuerCCK("#styling-col .background-color").val(colSettings.backgroundColor);
            jQuerCCK("#styling-col .background-color").minicolors("value",colSettings.backgroundColor);
            jQuerCCK("#styling-col .border-size").val(colSettings.borderSize);
            jQuerCCK("#styling-col .border-color").val(colSettings.borderColor);
            jQuerCCK("#styling-col .border-color").minicolors("value",colSettings.borderColor);
            jQuerCCK("#styling-col .font-size").val(colSettings.fontSize);
            if(typeof(colSettings.fontWeight) == 'undefined'){
              jQuerCCK("#styling-col .font-weight:first").prop("checked",true);
            }else{
              if(colSettings.fontWeight){
                jQuerCCK("#styling-col .font-weight:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-col .font-weight:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-col .font-color").val(colSettings.fontColor);
            jQuerCCK("#styling-col .font-color").minicolors("value",colSettings.fontColor);
            jQuerCCK("#styling-col .custom-class").val(colSettings.customClass);
          }else{
            clearStyleSettings('col');
          }
        break;

        case "f":
          if(jQuerCCK("#content-block .active-field .field-name").length 
            && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='custom_code_field']").length
            && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_mail']").length
            && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_search_field']").length
            && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_send_button']").length
            ){
            f = jQuerCCK("#content-block .active-field .f-params");
            fSettings = window.JSON.parse(jQuerCCK(f).val());
            //set settings
            //input
            if(typeof(fSettings.margin) == 'undefined'){
              jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
            }else{
              if(fSettings.margin){
                jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .margin-pixels:last").prop("checked",true);
              }
            }


            jQuerCCK("#styling-f .background-table-header-color").val(fSettings.backgroundTableHeaderColor);
            jQuerCCK("#styling-f .background-table-header-color").minicolors("value",fSettings.backgroundTableHeaderColor);
            jQuerCCK("#styling-f .font-table-header-color").val(fSettings.fontTableHeaderColor);
            jQuerCCK("#styling-f .font-table-header-color").minicolors("value",fSettings.fontTableHeaderColor);


            jQuerCCK("#styling-f .margin-top").val(fSettings.marginTop);
            jQuerCCK("#styling-f .margin-right").val(fSettings.marginRight);
            jQuerCCK("#styling-f .margin-bottom").val(fSettings.marginBottom);
            jQuerCCK("#styling-f .margin-left").val(fSettings.marginLeft);
            if(typeof(fSettings.padding) == 'undefined'){
              jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
            }else{
              if(fSettings.padding){
                jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .padding-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .padding-top").val(fSettings.paddingTop);
            jQuerCCK("#styling-f .padding-right").val(fSettings.paddingRight);
            jQuerCCK("#styling-f .padding-bottom").val(fSettings.paddingBottom);
            jQuerCCK("#styling-f .padding-left").val(fSettings.paddingLeft);
            if(jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'locationfield'){
              jQuerCCK("#styling-f .padding-pixels").parent().parent().parent().hide();
            }else{
              jQuerCCK("#styling-f .padding-pixels").parent().parent().parent().show();
            }


            jQuerCCK("#styling-f .background-color").val(fSettings.backgroundColor);
            jQuerCCK("#styling-f .background-color").minicolors("value",fSettings.backgroundColor);
            jQuerCCK("#styling-f .border-size").val(fSettings.borderSize);
            jQuerCCK("#styling-f .border-color").val(fSettings.borderColor);
            jQuerCCK("#styling-f .border-color").minicolors("value",fSettings.borderColor);
            if(jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_single_checkbox_onoff'
                || jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_radio_buttons'
                || jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_select_list'){
              jQuerCCK("#styling-f .background-color,#styling-f .border-color").parent().parent().parent().hide();
            jQuerCCK("#styling-f .border-size").parent().parent().hide();
            }else{
              jQuerCCK("#styling-f .background-color,#styling-f .border-color").parent().parent().parent().show();
              jQuerCCK("#styling-f .border-size").parent().parent().show();
            }
            jQuerCCK("#styling-f .font-size").val(fSettings.fontSize);

            if(typeof(fSettings.fontWeight) == 'undefined'){
              jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
            }else{
              if(fSettings.fontWeight || fSettings.fontWeight === ''){
                jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .font-weight:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .font-color").val(fSettings.fontColor);
            jQuerCCK("#styling-f .font-color").minicolors("value",fSettings.fontColor);
            jQuerCCK("#styling-f .custom-class").val(fSettings.customClass);

            //label
            if(typeof(fSettings.labelMargin) == 'undefined'){
              jQuerCCK("#styling-f .label-margin-pixels:first").prop("checked",true);
            }else{
              if(fSettings.labelMargin){
                jQuerCCK("#styling-f .label-margin-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .label-margin-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .label-margin-top").val(fSettings.labelMarginTop);
            jQuerCCK("#styling-f .label-margin-right").val(fSettings.labelMarginRight);
            jQuerCCK("#styling-f .label-margin-bottom").val(fSettings.labelMarginBottom);
            jQuerCCK("#styling-f .label-margin-left").val(fSettings.labelMarginLeft);
            if(typeof(fSettings.labelPadding) == 'undefined'){
              jQuerCCK("#styling-f .label-padding-pixels:first").prop("checked",true);
            }else{
              if(fSettings.labelPadding){
                jQuerCCK("#styling-f .label-padding-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .label-padding-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .label-padding-top").val(fSettings.labelPaddingTop);
            jQuerCCK("#styling-f .label-padding-right").val(fSettings.labelPaddingRight);
            jQuerCCK("#styling-f .label-padding-bottom").val(fSettings.labelPaddingBottom);
            jQuerCCK("#styling-f .label-padding-left").val(fSettings.labelPaddingLeft);
            jQuerCCK("#styling-f .label-background-color").val(fSettings.labelBackgroundColor);
            jQuerCCK("#styling-f .label-background-color").minicolors("value",fSettings.labelBackgroundColor);
            jQuerCCK("#styling-f .label-border-size").val(fSettings.labelBorderSize);
            jQuerCCK("#styling-f .label-border-color").val(fSettings.labelBorderColor);
            jQuerCCK("#styling-f .label-border-color").minicolors("value",fSettings.labelBorderColor);
            jQuerCCK("#styling-f .label-font-size").val(fSettings.labelFontSize);
            if(typeof(fSettings.labelFontWeight) == 'undefined'){
              jQuerCCK("#styling-f .label-font-weight:first").prop("checked",true);
            }else{
              if(fSettings.labelFontWeight || fSettings.labelFontWeight === ''){
                jQuerCCK("#styling-f .label-font-weight:first").prop("checked",true);
              }else{
                jQuerCCK("#styling-f .label-font-weight:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .label-font-color").val(fSettings.labelFontColor);
            jQuerCCK("#styling-f .label-font-color").minicolors("value",fSettings.labelFontColor);
            jQuerCCK("#styling-f .label-custom-class").val(fSettings.labelCustomClass);
          }else{
            clearStyleSettings('f');
          }
        break;


        case "l":
          if(jQuerCCK("#content-block .active-field .layout-name").length){
            f = jQuerCCK("#content-block .active-field .f-params");
            fSettings = window.JSON.parse(jQuerCCK(f).val());
            //set settings
            //input
            if(typeof(fSettings.margin) == 'undefined'){
              jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
            }else{
              if(fSettings.margin){
                jQuerCCK("#styling-f .margin-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .margin-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .margin-top").val(fSettings.marginTop);
            jQuerCCK("#styling-f .margin-right").val(fSettings.marginRight);
            jQuerCCK("#styling-f .margin-bottom").val(fSettings.marginBottom);
            jQuerCCK("#styling-f .margin-left").val(fSettings.marginLeft);
            if(typeof(fSettings.padding) == 'undefined'){
              jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
            }else{
              if(fSettings.padding){
                jQuerCCK("#styling-f .padding-pixels:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .padding-pixels:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .padding-top").val(fSettings.paddingTop);
            jQuerCCK("#styling-f .padding-right").val(fSettings.paddingRight);
            jQuerCCK("#styling-f .padding-bottom").val(fSettings.paddingBottom);
            jQuerCCK("#styling-f .padding-left").val(fSettings.paddingLeft);
            if(jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'locationfield'){
              jQuerCCK("#styling-f .padding-pixels").parent().parent().parent().hide();
            }else{
              jQuerCCK("#styling-f .padding-pixels").parent().parent().parent().show();
            }


            jQuerCCK("#styling-f .background-color").val(fSettings.backgroundColor);
            jQuerCCK("#styling-f .background-color").minicolors("value",fSettings.backgroundColor);
            jQuerCCK("#styling-f .border-size").val(fSettings.borderSize);
            jQuerCCK("#styling-f .border-color").val(fSettings.borderColor);
            jQuerCCK("#styling-f .border-color").minicolors("value",fSettings.borderColor);
            if(jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_single_checkbox_onoff'
                || jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_radio_buttons'
                || jQuerCCK(".drop-item.active-field .field-name").data("field-type") == 'text_select_list'){
              jQuerCCK("#styling-f .background-color,#styling-f .border-color").parent().parent().parent().hide();
            jQuerCCK("#styling-f .border-size").parent().parent().hide();
            }else{
              jQuerCCK("#styling-f .background-color,#styling-f .border-color").parent().parent().parent().show();
              jQuerCCK("#styling-f .border-size").parent().parent().show();
            }
            jQuerCCK("#styling-f .font-size").val(fSettings.fontSize);

            if(typeof(fSettings.fontWeight) == 'undefined'){
              jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
            }else{
              if(fSettings.fontWeight || fSettings.fontWeight === ''){
                jQuerCCK("#styling-f .font-weight:first").prop("checked",true);
              }
              else{
                jQuerCCK("#styling-f .font-weight:last").prop("checked",true);
              }
            }
            jQuerCCK("#styling-f .font-color").val(fSettings.fontColor);
            jQuerCCK("#styling-f .font-color").minicolors("value",fSettings.fontColor);
            jQuerCCK("#styling-f .custom-class").val(fSettings.customClass);

          }else{
            clearStyleSettings('f');
          }
        break;



        default:
          form = jQuerCCK("#editor-block .form-params");
          formSettings = window.JSON.parse(jQuerCCK(form).val());
          //set settings
          if(formSettings.margin){
            jQuerCCK("#styling-form .margin-pixels:first").prop("checked",true);
          }
          else{
            jQuerCCK("#styling-form .margin-pixels:last").prop("checked",true);
          }
          jQuerCCK("#styling-form .margin-top").val(formSettings.marginTop);
          jQuerCCK("#styling-form .margin-right").val(formSettings.marginRight);
          jQuerCCK("#styling-form .margin-bottom").val(formSettings.marginBottom);
          jQuerCCK("#styling-form .margin-left").val(formSettings.marginLeft);
          if(formSettings.padding){
            jQuerCCK("#styling-form .padding-pixels:first").prop("checked",true);
          }
          else{
            jQuerCCK("#styling-form .padding-pixels:last").prop("checked",true);
          }
          jQuerCCK("#styling-form .padding-top").val(formSettings.paddingTop);
          jQuerCCK("#styling-form .padding-right").val(formSettings.paddingRight);
          jQuerCCK("#styling-form .padding-bottom").val(formSettings.paddingBottom);
          jQuerCCK("#styling-form .padding-left").val(formSettings.paddingLeft);
          jQuerCCK("#styling-form .background-color").val(formSettings.backgroundColor);
          jQuerCCK("#styling-form .background-color").minicolors("value",formSettings.backgroundColor);
          jQuerCCK("#styling-form .border-size").val(formSettings.borderSize);
          jQuerCCK("#styling-form .border-color").val(formSettings.borderColor);
          jQuerCCK("#styling-form .border-color").minicolors("value",formSettings.borderColor);
          jQuerCCK("#styling-form .font-size").val(formSettings.fontSize);
          if(formSettings.fontWeight){
            jQuerCCK("#styling-form .font-weight:first").prop("checked",true);
          }
          else{
            jQuerCCK("#styling-form .font-weight:last").prop("checked",true);
          }
          jQuerCCK("#styling-form .font-color").val(formSettings.fontColor);
          jQuerCCK("#styling-form .font-color").minicolors("value",formSettings.fontColor);
          jQuerCCK("#styling-form .custom-class").val(formSettings.customClass);
        break;
      }
    }
    //end

    //save settings
    function writeSettings(){
      if(jQuerCCK("#content-block .active").length){
        if(jQuerCCK("#content-block .row-active-settings").length){
          row = jQuerCCK("#content-block .row-active-settings .row-params");
          // save row settings
          rowSettings = jQuerCCK();
          rowSettings.margin = jQuerCCK("#styling-row .margin-pixels").prop("checked");
          rowSettings.marginTop = jQuerCCK("#styling-row .margin-top").val();
          rowSettings.marginRight = jQuerCCK("#styling-row .margin-right").val();
          rowSettings.marginBottom = jQuerCCK("#styling-row .margin-bottom").val();
          rowSettings.marginLeft = jQuerCCK("#styling-row .margin-left").val();
          rowSettings.padding = jQuerCCK("#styling-row .padding-pixels").prop("checked");
          rowSettings.paddingTop = jQuerCCK("#styling-row .padding-top").val();
          rowSettings.paddingRight = jQuerCCK("#styling-row .padding-right").val();
          rowSettings.paddingBottom = jQuerCCK("#styling-row .padding-bottom").val();
          rowSettings.paddingLeft = jQuerCCK("#styling-row .padding-left").val();
          rowSettings.backgroundColor = jQuerCCK("#styling-row .background-color").val();
          rowSettings.borderSize = jQuerCCK("#styling-row .border-size").val();
          rowSettings.borderColor = jQuerCCK("#styling-row .border-color").val();
          rowSettings.fontSize = jQuerCCK("#styling-row .font-size").val();
          if(jQuerCCK("#styling-row .font-weight").prop("checked")){
            rowSettings.fontWeight = 1;
          }else{
            rowSettings.fontWeight = 0;
          }
          rowSettings.fontColor = jQuerCCK("#styling-row .font-color").val();
          rowSettings.customClass = jQuerCCK("#styling-row .custom-class").val();
          //wrrite
          jQuerCCK(row).val(window.JSON.stringify(rowSettings));
        }
        if(jQuerCCK("#content-block .col-active-settings").length){
          col = jQuerCCK("#content-block .col-active-settings .col-params");
          // save col settings
          colSettings = jQuerCCK();
          colSettings.margin = jQuerCCK("#styling-col .margin-pixels").prop("checked");
          colSettings.marginTop = jQuerCCK("#styling-col .margin-top").val();
          colSettings.marginRight = jQuerCCK("#styling-col .margin-right").val();
          colSettings.marginBottom = jQuerCCK("#styling-col .margin-bottom").val();
          colSettings.marginLeft = jQuerCCK("#styling-col .margin-left").val();
          colSettings.padding = jQuerCCK("#styling-col .padding-pixels").prop("checked");
          colSettings.paddingTop = jQuerCCK("#styling-col .padding-top").val();
          colSettings.paddingRight = jQuerCCK("#styling-col .padding-right").val();
          colSettings.paddingBottom = jQuerCCK("#styling-col .padding-bottom").val();
          colSettings.paddingLeft = jQuerCCK("#styling-col .padding-left").val();
          colSettings.backgroundColor = jQuerCCK("#styling-col .background-color").val();
          colSettings.borderSize = jQuerCCK("#styling-col .border-size").val();
          colSettings.borderColor = jQuerCCK("#styling-col .border-color").val();
          colSettings.fontSize = jQuerCCK("#styling-col .font-size").val();
          if(jQuerCCK("#styling-col .font-weight").prop("checked")){
            colSettings.fontWeight = 1;
          }else{
            colSettings.fontWeight = 0;
          }
          colSettings.fontColor = jQuerCCK("#styling-col .font-color").val();
          colSettings.customClass = jQuerCCK("#styling-col .custom-class").val();
          //wrrite
          jQuerCCK(col).val(window.JSON.stringify(colSettings));
        }
      }
      if(jQuerCCK("#content-block .active-field .field-name").length
          && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='custom_code_field']").length
          && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_mail']").length
          && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_search_field']").length
          // && !jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_send_button']").length
          ){

        f = jQuerCCK("#content-block .active-field .f-params");
        // save field settings
        fSettings = jQuerCCK();
        //input

        fSettings.backgroundTableHeaderColor = jQuerCCK("#styling-f .background-table-header-color").val();
        fSettings.fontTableHeaderColor = jQuerCCK("#styling-f .font-table-header-color").val();

        fSettings.margin = jQuerCCK("#styling-f .margin-pixels").prop("checked");
        fSettings.marginTop = jQuerCCK("#styling-f .margin-top").val();
        fSettings.marginRight = jQuerCCK("#styling-f .margin-right").val();
        fSettings.marginBottom = jQuerCCK("#styling-f .margin-bottom").val();
        fSettings.marginLeft = jQuerCCK("#styling-f .margin-left").val();
        fSettings.padding = jQuerCCK("#styling-f .padding-pixels").prop("checked");
        fSettings.paddingTop = jQuerCCK("#styling-f .padding-top").val();
        fSettings.paddingRight = jQuerCCK("#styling-f .padding-right").val();
        fSettings.paddingBottom = jQuerCCK("#styling-f .padding-bottom").val();
        fSettings.paddingLeft = jQuerCCK("#styling-f .padding-left").val();
        fSettings.backgroundColor = jQuerCCK("#styling-f .background-color").val();
        fSettings.borderSize = jQuerCCK("#styling-f .border-size").val();
        fSettings.borderColor = jQuerCCK("#styling-f .border-color").val();
        fSettings.fontSize = jQuerCCK("#styling-f .font-size").val();
        if(jQuerCCK("#styling-f .font-weight").prop("checked")){
          fSettings.fontWeight = 1;
        }else{
          fSettings.fontWeight = 0;
        }
        fSettings.fontColor = jQuerCCK("#styling-f .font-color").val();
        fSettings.customClass = jQuerCCK("#styling-f .custom-class").val();
        //label
        fSettings.labelMargin = jQuerCCK("#styling-f .label-margin-pixels").prop("checked");
        fSettings.labelMarginTop = jQuerCCK("#styling-f .label-margin-top").val();
        fSettings.labelMarginRight = jQuerCCK("#styling-f .label-margin-right").val();
        fSettings.labelMarginBottom = jQuerCCK("#styling-f .label-margin-bottom").val();
        fSettings.labelMarginLeft = jQuerCCK("#styling-f .label-margin-left").val();
        fSettings.labelPadding = jQuerCCK("#styling-f .label-padding-pixels").prop("checked");
        fSettings.labelPaddingTop = jQuerCCK("#styling-f .label-padding-top").val();
        fSettings.labelPaddingRight = jQuerCCK("#styling-f .label-padding-right").val();
        fSettings.labelPaddingBottom = jQuerCCK("#styling-f .label-padding-bottom").val();
        fSettings.labelPaddingLeft = jQuerCCK("#styling-f .label-padding-left").val();
        fSettings.labelBackgroundColor = jQuerCCK("#styling-f .label-background-color").val();
        fSettings.labelBorderSize = jQuerCCK("#styling-f .label-border-size").val();
        fSettings.labelBorderColor = jQuerCCK("#styling-f .label-border-color").val();
        fSettings.labelFontSize = jQuerCCK("#styling-f .label-font-size").val();
        fSettings.labelFontWeight = jQuerCCK("#styling-f .label-font-weight").prop("checked");
        if(jQuerCCK("#styling-f .label-font-weight").prop("checked")){
          fSettings.labelFontWeight = 1;
        }else{
          fSettings.labelFontWeight = 0;
        }
        fSettings.labelFontColor = jQuerCCK("#styling-f .label-font-color").val();
        fSettings.labelCustomClass = jQuerCCK("#styling-f .label-custom-class").val();
        //wrrite
        jQuerCCK(f).val(window.JSON.stringify(fSettings));
      }


      if(jQuerCCK("#content-block .active-field .layout-name").length){

        f = jQuerCCK("#content-block .active-field .f-params");
        // save field settings
        fSettings = jQuerCCK();
        //input
        fSettings.margin = jQuerCCK("#styling-f .margin-pixels").prop("checked");
        fSettings.marginTop = jQuerCCK("#styling-f .margin-top").val();
        fSettings.marginRight = jQuerCCK("#styling-f .margin-right").val();
        fSettings.marginBottom = jQuerCCK("#styling-f .margin-bottom").val();
        fSettings.marginLeft = jQuerCCK("#styling-f .margin-left").val();
        fSettings.padding = jQuerCCK("#styling-f .padding-pixels").prop("checked");
        fSettings.paddingTop = jQuerCCK("#styling-f .padding-top").val();
        fSettings.paddingRight = jQuerCCK("#styling-f .padding-right").val();
        fSettings.paddingBottom = jQuerCCK("#styling-f .padding-bottom").val();
        fSettings.paddingLeft = jQuerCCK("#styling-f .padding-left").val();
        fSettings.backgroundColor = jQuerCCK("#styling-f .background-color").val();
        fSettings.borderSize = jQuerCCK("#styling-f .border-size").val();
        fSettings.borderColor = jQuerCCK("#styling-f .border-color").val();
        fSettings.fontSize = jQuerCCK("#styling-f .font-size").val();
        if(jQuerCCK("#styling-f .font-weight").prop("checked")){
          fSettings.fontWeight = 1;
        }else{
          fSettings.fontWeight = 0;
        }
        fSettings.fontColor = jQuerCCK("#styling-f .font-color").val();
        fSettings.customClass = jQuerCCK("#styling-f .custom-class").val();
        //wrrite
        jQuerCCK(f).val(window.JSON.stringify(fSettings));

      }


      if(jQuerCCK("#styling-form:visible").length){
        form = jQuerCCK("#editor-block .form-params");
        // save form settings
        formSettings = jQuerCCK();
        //input
        formSettings.margin = jQuerCCK("#styling-form .margin-pixels").prop("checked");
        formSettings.marginTop = jQuerCCK("#styling-form .margin-top").val();
        formSettings.marginRight = jQuerCCK("#styling-form .margin-right").val();
        formSettings.marginBottom = jQuerCCK("#styling-form .margin-bottom").val();
        formSettings.marginLeft = jQuerCCK("#styling-form .margin-left").val();
        formSettings.padding = jQuerCCK("#styling-form .padding-pixels").prop("checked");
        formSettings.paddingTop = jQuerCCK("#styling-form .padding-top").val();
        formSettings.paddingRight = jQuerCCK("#styling-form .padding-right").val();
        formSettings.paddingBottom = jQuerCCK("#styling-form .padding-bottom").val();
        formSettings.paddingLeft = jQuerCCK("#styling-form .padding-left").val();
        formSettings.backgroundColor = jQuerCCK("#styling-form .background-color").val();
        formSettings.borderSize = jQuerCCK("#styling-form .border-size").val();
        formSettings.borderColor = jQuerCCK("#styling-form .border-color").val();
        formSettings.fontSize = jQuerCCK("#styling-form .font-size").val();
        if(jQuerCCK("#styling-form .font-weight").prop("checked")){
          formSettings.fontWeight = 1;
        }else{
          formSettings.fontWeight = 0;
        }
        formSettings.fontColor = jQuerCCK("#styling-form .font-color").val();
        formSettings.customClass = jQuerCCK("#styling-form .custom-class").val();
        //wrrite
        jQuerCCK(form).val(window.JSON.stringify(formSettings));
      }
    }
    //end

    //fn for hide options
    function hide_options(){
      jQuerCCK("#field_options div[id^='options-field-']").hide();
      jQuerCCK("#content-block .drop-area, #content-block .content-row, #content-block").removeClass("active col-active-settings row-active-settings");
      jQuerCCK("#content-block .drop-item").removeClass('active-field col-active-settings row-active-settings');
    }
    //end

    function addFieldOptions(fName){

      jQuerCCK.post("index.php?option=com_os_cck&task=addFieldOptions&lid="+jQuerCCK("#lid").val()+"&layout_type="+jQuerCCK("[name='type']").val()+"&fieldName="+fName+"&fk_eid="+jQuerCCK("[name='fk_eid']").val()+"&format=raw",
                 {},
      function (data) {
        jQuerCCK("#ui-accordion-fields-options-accordion-panel-0").append(data.html);
        make_required();
        make_popover();
      } , 'json' );
    }

    function makeSearchInFields(){
      jQuerCCK(".cck-main-field-search").on('keyup', function(event) {
        input = jQuerCCK(this).val();
        if(input != ''){
          jQuerCCK("#fields-block .field-block ").each(function(index, el) {
            //slowwwww//jQuerCCK(el).find(".field-name").text().search(eval("/"+input+"/ig"))
            //fast//jQuerCCK(el).find(".field-name").text().search(input)
            if(jQuerCCK(el).find(".field-name").text().search(new RegExp(input,"i")) >=0){
              jQuerCCK(el).show();
            }else{
              jQuerCCK(el).hide();
            }
          });
        }else{
          jQuerCCK("#fields-block .field-block ").show();
        }
      });
    }

    function makeSearchInEntityFields(){
      jQuerCCK(".cck-entity-field-search").on('keyup', function(event) {
        input = jQuerCCK(this).val();
        if(input != ''){
          jQuerCCK(".adminlist tr:not(:first-child)").each(function(index, el) {
            //slowwwww//jQuerCCK(el).find(".field-name").text().search(eval("/"+input+"/ig"))
            //fast//jQuerCCK(el).find(".field-name").text().search(input)
            if(jQuerCCK(el).find(".field-name-list").text().search(new RegExp(input,"i")) >=0 
              || jQuerCCK(el).find(".field-id").text().search(new RegExp(input,"i")) >=0
              || jQuerCCK(el).find(".field-type").text().search(new RegExp(input,"i")) >=0){
              jQuerCCK(el).show();
            }else{
              jQuerCCK(el).hide();
            }
          });
        }else{
          jQuerCCK(".adminlist tr").show();
        }
      });
    }

    //fn for show/hide options
    function makeOptions (){
      if(debug){
        console.log('makeOptions()');
      }
      hide_options();
      jQuerCCK("#content-block .drop-item").on('click',function() {
        if(debug){
          console.log('#content-block .drop-item::click');
        }
        //check if active 1-st tab
        if(jQuerCCK( "#field_options" ).tabs( "option", "active" ) == 0){
          jQuerCCK("#fields-options-accordion").show();
          writeSettings();
          hide_options();
          jQuerCCK("#content-block .drop-item").removeClass('active-field');
          jQuerCCK(this).addClass('active-field');
          readSettings('f');
          
          if(jQuerCCK(this).find(".field-name").length){
            fieldName = jQuerCCK(this).find(".field-name").data('field-name');
          }else if(jQuerCCK(this).find(".module-name").length){
            fieldName = jQuerCCK(this).find(".module-name").data('field-name');
          }else{
            fieldName = jQuerCCK(this).find(".layout-name").data('field-name');
          }
          jQuerCCK("#field_options #options-field-"+fieldName).show();
          jQuerCCK("#field_options #styling-f").show();
          saveAccordionState();
          if(jQuerCCK("#content-block .active-field span[data-field-name^='custom_code_field']").length
            || jQuerCCK("#content-block .active-field span[data-field-name^='cck_mail']").length
            // || jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_search_field']").length
            // || jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_send_button']").length
            || jQuerCCK("#content-block .active-field .module-name").length
            // || jQuerCCK("#content-block .active-field .layout-name").length
            ){

            jQuerCCK("#ui-accordion-fields-options-accordion-header-1, #ui-accordion-fields-options-accordion-panel-1").hide();

          }else{

            jQuerCCK('#styling-f>h2.style-for-block').hide();

            jQuerCCK('.table_header').hide();

            if(jQuerCCK("#content-block .active-field div[data-field-name='calendar_table']").length){
              jQuerCCK('.table_header').show();
            }

            if(fieldActiveState){
              jQuerCCK("#ui-accordion-fields-options-accordion-panel-1").show();  
            }
            jQuerCCK("#ui-accordion-fields-options-accordion-header-1").show();

              //for buttons in add layouts
            if(jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_send_button']").length
              || jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_cal_import']").length
              || jQuerCCK("#content-block .active-field .field-name[data-field-name^='cck_instance_navigation']").length){
              jQuerCCK('#styling-f>div,h2').not('.style-for-block').hide();
            }else{
              jQuerCCK('#styling-f>div,h2').not('.style-for-block').show();
            }


              //block for layout dropdown style list for button
            if(jQuerCCK("#content-block .active-field .layout-name").length){
              var layout_name = jQuerCCK(this).children('span.layout-name').attr('data-field-name');

              if(jQuerCCK(".main-fields-content select[id='vi_show_type_request_layout"+layout_name+"']").val() != 1){

                readSettings('l');
                jQuerCCK("#ui-accordion-fields-options-accordion-header-1").slideDown();
                jQuerCCK('#styling-f>div,h2').not('.style-for-block').hide();

                jQuerCCK('#styling-f>h2.style-for-block').show();

              }else{
                jQuerCCK("#ui-accordion-fields-options-accordion-header-1, #ui-accordion-fields-options-accordion-panel-1").hide();
              }

            }

          }
          jQuerCCK( "#fields-options-accordion" ).accordion( "option", "active", fieldActiveState );
          //form
          jQuerCCK("div[id^='styling-form']").show();
          jQuerCCK("#form-options-accordion").accordion( "option", "active", formActiveState );
          //block
          //row
          //hide module--field options
          if(jQuerCCK(this).find(".module-name").length){
            jQuerCCK("#fields-options-accordion").hide();
          }
        }
      });
    
      jQuerCCK(".main-fields-content select[id^='vi_show_type_']").on('change',function() {
          if(jQuerCCK(this).val() != 1){
            jQuerCCK("#ui-accordion-fields-options-accordion-header-1").slideDown();
            jQuerCCK('#styling-f>div,h2').not('.style-for-block').hide();
            readSettings('l');
          }else{
            jQuerCCK("#ui-accordion-fields-options-accordion-header-1, #ui-accordion-fields-options-accordion-panel-1").slideUp();
          }

      });

      jQuerCCK(".modal-header .close, #attached-layout-modal, #attached-module-modal").on('click',function() {
        if(debug){
          console.log('.modal-header .close::click');
        }
        showSaveButtons();
      });

      //block //column show/hide
      jQuerCCK("#content-block .drop-area").on('click',function() {
        if(debug){
          console.log('#content-block .drop-area::click');
        }
        //save settings
        writeSettings();
        //
        //we in field options
        if(!jQuerCCK(this).find(".drop-item.active-field").length){
          jQuerCCK("#content-block .drop-item").removeClass("active-field");
        }
        //row
        jQuerCCK("#editor-block .content-row, #editor-block .drop-area, #editor-block").removeClass('active row-active-settings col-active-settings')
        saveAccordionState();
        jQuerCCK( "#block-options-accordion" ).accordion( "option", "active", blockActiveState );
        if(jQuerCCK( "#field_options" ).tabs( "option", "active" ) == 1){
          if(blockActiveState){
            jQuerCCK(this).addClass('active col-active-settings');
          }else{
            jQuerCCK(this).parent('div.row').addClass('active row-active-settings');
          }
        }
        colId = jQuerCCK(this).data("column-number");
        rowId = jQuerCCK(this).parent().data("row-number");
        fieldName = jQuerCCK(this).find(".drop-item.active-field .field-name").data("field-name");
        jQuerCCK("#styling-row").attr("data-row-id", rowId);
        jQuerCCK("#styling-col").attr("data-col-id", colId);
        jQuerCCK("#styling-f").attr("data-field-name", fieldName);
        readSettings('col');
        readSettings('row');
      });
      //fn for show/hide form options
      jQuerCCK("#field_options #ui-id-1").on('click',function() {
        writeSettings();
        if(debug){
          console.log("#field_options #ui-id-1::click");
        }
        jQuerCCK("#content-block .content-row, #content-block .drop-area, #editor-block").removeClass("active");
        if(fieldActiveState == 0){//active field main option
          if(jQuerCCK("#fields-options-accordion div[id^='options-field-']:visible").length){
            fieldName = jQuerCCK("#fields-options-accordion div[id^='options-field-']:visible").attr('id');
            if(fieldName){
              fieldName = fieldName.replace('options-field-','');
              jQuerCCK("#content-block .drop-item span[data-field-name='"+fieldName+"']").parent().addClass("active-field");
            }
          }
        }else{//field styling option
          if(jQuerCCK("#fields-options-accordion #styling-f:visible").length){
            fieldName = jQuerCCK("#fields-options-accordion #styling-f:visible").attr('data-field-name');
            if(fieldName){
              jQuerCCK("#content-block .drop-item span[data-field-name='"+fieldName+"']").parent().addClass("active-field");
            }
          }
        }
        readSettings('l');
        readSettings('f');
        saveAccordionState();
      });
    //end
      //fn for change active element on block option click
      jQuerCCK("#field_options #ui-id-2").on('click',function(){
        if(debug){
          console.log('#field_options #ui-id-2::click');
        }
        jQuerCCK('.table_header').hide();
        jQuerCCK("#editor-block").removeClass("active col-active-settings row-active-settings");
        activeField = jQuerCCK("#content-block").find(".drop-item.active-field").length;
        if(!activeField){//no active field
          activeRow = jQuerCCK("#content-block").find(".drop-area.active").length;
          if(activeRow && blockActiveState == 0){//row style //allready active
            activeRow = jQuerCCK("#content-block").find(".drop-area.active");
            jQuerCCK(activeRow).removeClass("active col-active-settings row-active-settings");
            jQuerCCK(activeRow).parent("div.content-row").addClass("active row-active-settings");
          }else if(!activeRow && blockActiveState == 0){//row style
            rowName = jQuerCCK("#block-options-accordion #styling-row:visible").attr('data-row-id');
            if(rowName){
              jQuerCCK("#content-block div[data-row-number='"+rowName+"']").addClass("active row-active-settings");
            }
          }else if(!activeRow && blockActiveState == 1){//column style
            columnName = jQuerCCK("#block-options-accordion #styling-col:visible").attr('data-col-id');
            if(columnName){
              jQuerCCK("#content-block .content-row div[data-col-number='"+columnName+"']").addClass("active col-active-settings");
            }
          }
        }else{
          activeField = jQuerCCK("#content-block").find(".drop-item.active-field");
          jQuerCCK(activeField).removeClass("active-field");
          if(blockActiveState == 0){//row style
            jQuerCCK(activeField).parent().parent("div.content-row").addClass("active row-active-settings");
          }else{//column style
            jQuerCCK(activeField).parent("div.drop-area").addClass("active col-active-settings");
          }
        }
        readSettings('col');
        readSettings('row');
        saveAccordionState();
      });
    //end
      //fn for click on row styles
      jQuerCCK("#field_options #row-styling").on('click',function() {
        if(debug){
          console.log('#field_options #row-styling::click');
        }
        writeSettings();
        setTimeout(function(){
          if(jQuerCCK("#options-tab-2 #styling-row:visible").length){
            jQuerCCK("#content-block .drop-area").removeClass("active row-active-settings col-active-settings");
            rowId = jQuerCCK("#options-tab-2 #styling-row:visible").attr('data-row-id');
            if(rowId){
              jQuerCCK("#content-block div[data-row-number='"+rowId+"']").addClass("active row-active-settings");
            }
          }
          readSettings('row');
          saveAccordionState();
        }, 500);
      });
    //end
      //fn for click on column styles
      jQuerCCK("#field_options #column-styling").on('click',function() {
        if(debug){
          console.log('#field_options #column-styling::click');
        }
        writeSettings();
        setTimeout(function(){
          if(jQuerCCK("#options-tab-2 #styling-col:visible").length){
            jQuerCCK("#content-block .content-row").removeClass("active row-active-settings col-active-settings");
            colId = jQuerCCK("#options-tab-2 #styling-col:visible").attr('data-col-id');
            if(colId){
              jQuerCCK("#content-block div[data-column-number='"+colId+"']").addClass("active col-active-settings");
            }
          }
          readSettings('col');
          saveAccordionState();
        }, 500);
      });
    //end
      //fn for show/hide form options
      jQuerCCK("#field_options #ui-id-3").on('click',function() {
        if(debug){
          console.log('#field_options #ui-id-3::click');
        }
        jQuerCCK('.table_header').hide();
        writeSettings();
        jQuerCCK("#content-block .content-row, #content-block .drop-area").removeClass("active");
        jQuerCCK("#content-block .drop-item").removeClass('active-field');
        jQuerCCK("#editor-block").addClass("active");
        jQuerCCK("div[id^='styling-form-']").show();
        jQuerCCK("#form-options-accordion").accordion( "option", "active", formActiveState );
        saveAccordionState();
        readSettings();
      });
      make_editor();
    }
  //end
    //fn fot delete row
    function makeDeleteRow(){
      if(debug){
        console.log('makeDeleteRow()');
      }
      jQuerCCK("#content-block .delete-row").on('click',function() {
        if(debug){
          console.log('#content-block .delete-row::click');
        }
        //return fields and layouts to it's place
        jQuerCCK(this).parent().find(".drop-item").each(function( index ) {
          if(jQuerCCK(this).find(".f-inform-button").length){
            //field block
            jQuerCCK(this).find(".delete-row, .delete-field, span[class$='inform-button']").remove();
            //check unique fields
            if(jQuerCCK(this).find(".field-name").data("field-name").indexOf("custom_code_field_") >= 0
              || jQuerCCK(this).find(".field-name").data("field-name") == 'joom_pagination'
              || jQuerCCK(this).find(".field-name").data("field-name") == 'joom_alphabetical'
              || jQuerCCK(this).find(".field-name").data("field-name") == 'calendar_month_year'
              || jQuerCCK(this).find(".field-name").data("field-name") == 'calendar_pagination'
              ){
              jQuerCCK(this).parent().remove();
              return;
            }

            //return field to fields-block
            jQuerCCK(this).removeClass("drop-item").addClass("field-block");
            jQuerCCK(this).wrapInner( "<div></div>" );

            if(jQuerCCK("#new-field-block").length){
              jQuerCCK("#new-field-block").before(jQuerCCK(this));
            }else{
              jQuerCCK("#attached-block").before(jQuerCCK(this));
            }

          }
          if(jQuerCCK(this).find(".m-inform-button").length){
            //module block
            jQuerCCK(this).find(".delete-row, .delete-module, span[class$='inform-button']").remove();
            jQuerCCK(this).removeClass("drop-item").addClass("attached-module-block");
            jQuerCCK(this).wrapInner( "<div></div>" );
            jQuerCCK("#add-module").before(jQuerCCK(this));
          }
          if(jQuerCCK(this).find(".l-inform-button").length){
            //layout block
            jQuerCCK(this).find(".delete-row, .delete-layout, span[class$='inform-button']").remove();
            jQuerCCK(this).removeClass("drop-item").addClass("attached-layout-block");
            jQuerCCK(this).wrapInner( "<div></div>" );
            jQuerCCK("#add-layout").before(jQuerCCK(this));
          }
        });
      //end
        //remove row
        jQuerCCK(this).parent().remove();
        make_attachedDraggable();
        make_draggable();
      });
    }
  //end

  //end clicked funs

    //remove joomla bars
    function make_remove_joomla_bars(){
      if(debug){
        console.log('make_remove_joomla_bars()');
      }
      jQuerCCK(".navbar-inverse, .btn-subhead, #status, .header .container-logo").remove();
    }
  //end
    //synchronize fields
    function make_synchronize_fields(){
      if(debug){
        console.log('make_synchronize_fields()');
      }

      jQuerCCK( "#content-block .drop-item .field-name" ).each(function( index ) {
        existField = jQuerCCK(this).data("field-name").trim();
        Textfield = jQuerCCK(this);
        field_exist = false;
        jQuerCCK( "#fields-block .field-block .field-name" ).each(function( index ) {
          if(jQuerCCK(this).data("field-name").trim() == existField 
            && jQuerCCK(this).data('field-name') != 'joom_pagination'
            && jQuerCCK(this).data('field-name') != 'joom_alphabetical'
            && jQuerCCK(this).data('field-name') != 'calendar_month_year'
            && jQuerCCK(this).data('field-name') != 'calendar_pagination'){
            field_exist = true;
            fieldPopover=jQuerCCK(this).parent().data("content")||'';
            if(fieldPopover){
              jQuerCCK(Textfield).parent().attr("data-content",fieldPopover);
            }
            fieldText = jQuerCCK(this).text().trim();
            Textfield.text(fieldText);
            jQuerCCK(this).parent().parent().remove();
          }
        });
        if(!field_exist && jQuerCCK(this).data('field-name').indexOf("custom_code_field_") < 0 
            && jQuerCCK(this).data('field-name') != 'joom_pagination'
            && jQuerCCK(this).data('field-name') != 'joom_alphabetical'
            && jQuerCCK(this).data('field-name') != 'calendar_month_year'
            && jQuerCCK(this).data('field-name') != 'calendar_pagination'){
          jQuerCCK(this).parent().remove();
        }
      });
    }
  //end
    //synchronize layout
    function make_synchronize_layout(){
      if(debug){
        console.log('make_synchronize_layout()');
      }
      jQuerCCK("#content-block .drop-item .layout-name").each(function( index ) {
        //jQuerCCK(this).parent().prepend('<span class="l-inform-button"></span>');
        if(jQuerCCK("#attached-layout-row-"+jQuerCCK(this).attr("data-field-name")).length){
          layName = jQuerCCK("#attached-layout-row-"+jQuerCCK(this).attr("data-field-name")+" span:first").html();
          jQuerCCK("#editor-block .layout-name[data-field-name='"+jQuerCCK(this).attr("data-field-name")+"']").html(layName);
          jQuerCCK("#attached-layout-row-"+jQuerCCK(this).attr("data-field-name")).remove();
        }else{
          jQuerCCK(this).parent().remove();
        }
      });
    }
  //end
  //fn for title hide/show
    function make_showHideTitle(){
      if(debug){
        console.log('make_showHideTitle()');
      }
      jQuerCCK("input[name^='fi_showName_'],input[name*='showName_custom_code_field_'],input[name*='vi_show_request_layout_name']").on('click',function() {
        if(debug){
          console.log("input[name^='fi_showName_'],input[name*='showName_custom_code_field_'],input[name*='vi_show_request_layout_name']::click");
        }
        field_name = jQuerCCK(this).data('field-name');
        if(this.checked){
          jQuerCCK('span[data-field-name='+field_name+']').removeClass("hide-field-name-"+field_name);
        }else{
          jQuerCCK('span[data-field-name='+field_name+']').addClass("hide-field-name-"+field_name);
        }
      });
    }
  //end
    //fn for tabs
    function makeTabs(){
      if(debug){
        console.log('makeTabs()');
      }
      jQuerCCK( "#field_options" ).tabs();
    }
  //end
    //accordion
    function make_accordion(){
      if(debug){
        console.log('make_accordion()');
      }
      jQuerCCK( "#fields-options-accordion,#form-options-accordion,#block-options-accordion" ).accordion({
        active: false,
        collapsible: true,
        icons:false,
        heightStyle: "content"
      });
    }
  //end
  //draggable for fields
    function make_draggable(){
      if(debug){
        console.log('make_draggable()');
      }
      jQuerCCK( ".field-block" ).draggable({
        helper: "clone",
        cursor: "move",
        cancel: null
      });
    }
  //end
    //draggable for attached layout
    function make_attachedDraggable(){
      if(debug){
        console.log('make_attachedDraggable()');
      }
      jQuerCCK( ".attached-layout-block, .attached-module-block " ).draggable({
        helper: "clone",
        cursor: "move",
        cancel: null
      });
    }
  //end
    //sortable fn
    function make_sortable(){
      if(debug){
        console.log('make_sortable()');
      }
      //add sortable for row
      jQuerCCK("#content-block").sortable({
        revert: true
      }).disableSelection();

      //sort content column
      jQuerCCK(".drop-area").sortable({
        cancel: null, // Cancel the default events on the controls
        connectWith: ".drop-area",
        start: function( event, ui ) {
          jQuerCCK(".drop-area").addClass("activeSortable");
          jQuerCCK(this).find(".drop-item").addClass("current-sort");
          jQuerCCK(this).find(".drop-item").width(165);
          var fid = jQuerCCK(ui.item).find('.col_box.admin_aria_hidden').attr('fid');
          fParent = jQuerCCK(this).find("i.f-parent[fid="+fid+"]").remove();

        },
        stop: function( event, ui ) {
          jQuerCCK(".drop-area").removeClass("activeSortable");
          jQuerCCK(this).find(".drop-item").removeClass("current-sort");
          jQuerCCK(this).find(".drop-item").width("");
          jQuerCCK(ui.item).after(fParent);

        }
      }).disableSelection();
    }
  //end
    //options for column and row
    function make_rows_options(){
      if(debug){
        console.log('make_rows_options()');
      }
      jQuerCCK.each( jQuerCCK("#content-block .row"), function(i, element){
        row_index = jQuerCCK(this).data("row-number");
        //styling-row-form-row-15
      });
    }
  //end
    //make colorpicker
    function make_colorpicker(){
      if(debug){
        console.log('make_colorpicker()');
      }
      jQuerCCK("#field_options .background-colorpicker, #field_options .border-colorpicker,#field_options .label-border-colorpicker,#field_options .label-background-colorpicker,#field_options .font-colorpicker,#field_options .label-font-colorpicker").minicolors({
        control: "hue",
        defaultValue: "",
        format:"rgb",
        opacity:true,
        position: "bottom right",
        hideSpeed: 100,
        inline: false,
        theme: "bootstrap",
        change: function(value, opacity) {
          jQuerCCK(this).attr("value",value);
        }
      });
    }



    function make_resize_grid(){
      if(debug){
        console.log('make_resize_grid()');
      }

       var resizableEl = jQuerCCK('.resizable').not(':nth-last-child(-n+3)');
       var columns = 12;

       var totalCol; // this is filled by start event handler
       var updateClass = function(el, col, nextWidth) {
         if(nextWidth) el.css('width', nextWidth); // remove width, our class already has it
          el.removeClass(function(index, className) {
            return (className.match(/(^|\s)col-\S+/g) || []).join(' ');
          }).addClass('col-lg-'+col+' col-md-'+col+' col-sm-'+col+' col-xs-'+col);
        };

        
      jQuerCCK('.resizable').not(':nth-last-child(-n+3)').resizable({
        handles: 'e',
        start: function(event, ui) {
          var
            fullWidth = resizableEl.parent().width(),
            columnWidth = fullWidth / columns,
            target = ui.element,
            next = target.next(),
            targetCol = Math.round((target.outerWidth(true)) / columnWidth),
            nextCol = Math.round((next.outerWidth(true)) / columnWidth);
          // set totalColumns globally
          totalCol = targetCol + nextCol;
          startNext = next.width();

          target.resizable('option', 'minWidth', columnWidth / 2);
          target.resizable('option', 'maxWidth', ((totalCol - 1) * columnWidth));
        },
        stop: function(event, ui){
          var target = ui.element; //current element
          var next = target.next(); //next element
          next.css('width', '');
          target.css('width', '');

        },
        resize: function(event, ui) {
            
           var fullWidth = resizableEl.parent().width();
           var columnWidth = fullWidth / columns;

           var target = ui.element; //current element
           var next = target.next(); //next element

           var targetColumnCount = Math.round((target.outerWidth(true)) / columnWidth); //(column count) target.width / one col width = 600/100 = 6
           var nextColumnCount = Math.round((next.outerWidth(true)) / columnWidth); //(column count) next.width / one col width = 600/100 = 6
           var targetSet = totalCol - nextColumnCount;
           var nextSet = totalCol - targetColumnCount;

            // console.log('fullWidth - '+fullWidth);

            // console.log('columnWidth - '+columnWidth);

            // console.log('targetColumnCount - '+targetColumnCount);
            // console.log('nextColumnCount - '+nextColumnCount);
            // console.log('------------------------------------------');

            // console.log(jQuerCCK('div.resizable-columns').width());
            // console.log(ui.element.width())

          var origSize = ui.originalSize.width;
          var curSize = ui.element.width();
          var diffSize = curSize - origSize;
          var nextWidth = startNext - diffSize;

            // next.width(next.width() - diffSize)
            // alert(columnWidth)

          // Just showing class names inside headings
          // target.find('.col-helper').text('col-' + targetSet);
          // next.find('.col-helper').text('col-' + nextSet);

           updateClass(target, targetSet, false);
           updateClass(next, nextSet, nextWidth);
        },
      });

     // })

    }


  //end
</script>
