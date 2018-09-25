var price_num = 0;
var item_num = 0;
var offer_num = 0;
var cck;

cck = {
    gallery_main_img: function (val, fid) {
        var img_name = jQuerCCK(val).parent().find("img").attr("src").split("/").pop();
        jQuerCCK.post("index.php?option=com_os_cck&task=ajax_instance&id=" + jQuerCCK("[name='id']").val() + "&format=raw", { img: img_name, do: "main", "f_id": fid});
        jQuerCCK("[value='Main']").val("Set Main").removeAttr("disabled");
        jQuerCCK(val).val("Main").attr("disabled", "disabled");
    },
    gallery_delete_img: function (val, fid) {
        var img_name = jQuerCCK(val).parent().find("img").attr("src").split("/").pop();
        jQuerCCK.post("index.php?option=com_os_cck&task=ajax_instance&id=" + jQuerCCK("[name='id']").val() + "&format=raw", { img: img_name, do: "delete", 'f_id': fid});
        jQuerCCK(val).parent().parent().fadeOut(500, function () {
            jQuerCCK(this).remove();
        });
    }
};

function new_extra_field() {
    field_div = document.getElementById("select_items");
    button = document.getElementById("add_select_field");
    newitem = "<strong>" + ++item_num + ". </strong> Option name:\n" +
        "<input type='text' name='add_selectitem[" + item_num + "][0]' value='' size='15' maxsize='50'>\n" +
        "Option value: <input id='pp" + price_num + "' type='text' name='add_selectitem[" + item_num + "][1]' value='' size='15' maxsize='50'><br />";
    newnode = document.createElement("span");
    newnode.innerHTML = newitem;
    field_div.insertBefore(newnode, button);
}

var photos = 0;
function new_photos() {
    div = document.getElementById("items");
    button = document.getElementById("add");
    photos++;
    newitem = "<strong>" + "Photo " + photos + ": </strong>";
    newitem += "<input type=\"file\" name=\"new_photo_file[]";
    newitem += "\" value=\"\"size=\"45\"><br>";
    newnode = document.createElement("span");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode, button);
}


function selectLayout(id, fk_eid, type, module_id){
    document.getElementById("selected_layout").value = id;
    if(module_id){
        document.getElementById("layout_type").value = type;
        if(type == 'instance' || type == 'category'){
            document.getElementById('jform_params_show_type').parentNode.parentNode.style.display = 'none';
            document.getElementById("instance").parentNode.parentNode.style.display = '';
            document.getElementById("instance").value = '';
            if(type == 'instance'){
                document.getElementById("changeLink").href =
                                "index.php?option=com_os_cck&task=show_instance_modal&layout_type="+type
                                +"&fk_eid="+fk_eid+"&module_id="+module_id+"&tmpl=component";
                document.getElementById("jform_params_instance-lbl").innerHTML = 'Select Instance';
                document.getElementById("changeLink").innerHTML = 'Select Instance';
            }else{
                document.getElementById("changeLink").href =
                                 "index.php?option=com_os_cck&task=show_categories_modal&layout_type="+type
                                 +"&fk_eid="+fk_eid+"&module_id="+module_id+"&tmpl=component";
                document.getElementById("changeLink").innerHTML = 'Select Category';
                document.getElementById("jform_params_instance-lbl").innerHTML = 'Select Category';
            }
        }else if(type == 'add_instance' || type == 'request_instance'){
            document.getElementById('jform_params_show_type').parentNode.parentNode.style.display = '';
            document.getElementById('instance').parentNode.parentNode.style.display = 'none';
        }else{
            document.getElementById('jform_params_show_type').parentNode.parentNode.style.display = 'none';
            document.getElementById('instance').parentNode.parentNode.style.display = 'none';
            document.getElementById("instance").value = '';
        }
    }else{
        if(type == 'instance'){
            document.getElementById("instance").parentNode.parentNode.style.display = '';
            document.getElementById("instance").value = '';
            document.getElementById("instance").value = '';
            document.getElementById("changeLink").href = "index.php?option=com_os_cck&task=show_instance_modal&layout_type="+type+"&fk_eid="
                                                        +fk_eid+"&tmpl=component";
        }
        if(type == 'category'){
            document.getElementById("category").parentNode.parentNode.style.display = '';
            document.getElementById("category").value = '';
            document.getElementById("changeLink").href = "index.php?option=com_os_cck&task=show_categories_modal&layout_type="+type+"&fk_eid="
                                                        +fk_eid+"&tmpl=component";
        }
    }
    SqueezeBox.close();
}

function selectInstance(id){
    document.getElementById("instance").value = id;
    SqueezeBox.close();
}

function selectCategory(id, module_id){

    if(module_id){
        document.getElementById("instance").value = id;
    }else{
        document.getElementById("category").value = id;
    }
    SqueezeBox.close();
}


function MM_findObj(n, d){ //v4.01
    var p,i,x;
    if(!d) d=document;
    if((p=n.indexOf("?"))>0&&parent.frames.length){
        d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
    }
    if(!(x=d[n])&&d.all) x=d.all[n];
    for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
    if(!x && d.getElementById) x=d.getElementById(n);
    return x;
}


function MM_swapImage(){ //v3.0
    var i,j=0,x,a=MM_swapImage.arguments;
    document.MM_sr=new Array;
    for(i=0;i<(a.length-2);i+=3)
    if ((x=MM_findObj(a[i]))!=null){
        document.MM_sr[j++]=x;
        if (!x.oSrc) x.oSrc=x.src;
        x.src=a[i+2];
    }
}


function MM_swapImgRestore(){ //v3.0
    var i,x,a=document.MM_sr;
    for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}


function MM_preloadImages(){ //v3.0
    var d=document;
    if(d.images){
        if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length,a=MM_preloadImages.arguments;
        for(i=0; i<a.length; i++) if (a[i].indexOf("#")!=0){
            d.MM_p[j]=new Image;
            d.MM_p[j++].src=a[i];
        }
    }
}
jQuerCCK(document).ready(function() {
    //highlight 
    jQuerCCK('table.filters select, table.filters input[name=search]').each(function(index, value){
        if(jQuerCCK(value).val() != '-1' 
            && jQuerCCK(value).val() != '0'
            && jQuerCCK(value).val() != ''
            && jQuerCCK(value).val() != ' ')
        {
            jQuerCCK(value).css('border','2px solid #46a546');
        }
    })

});

function addChildSelect(field_json = false,lid = false,value = false,path = false,fid = false, unique_parent_id){
    jQuerCCK("select[id^=fi_text_select_list_"+fid+"], select[id^=os_cck_search_text_select_list_"+fid+"]").unbind('change');

    jQuerCCK("select[id^=fi_text_select_list_"+fid+"], select[id^=os_cck_search_text_select_list_"+fid+"]").change(function(){

        var field = JSON.stringify(field_json);
        var selectObject = jQuerCCK(this);
        var currentValue = jQuerCCK(this).val();


        if(selectObject.closest('.drop-item').attr('data-parent') != undefined){
          unique_parent_id = selectObject.closest('.drop-item').attr('data-parent');
        }

        jQuerCCK.ajax({
              type:"POST",
              url: path+"/index.php?option=com_os_cck&task=getChildSelect&format=raw",
              data:"field="+field+"&lid="+lid+"&value="+value+"&currentValue="+currentValue+"&unique_parent_id="+unique_parent_id,
              success:function(data){
              
              // if(data){

              //   var selectParams = jQuerCCK(data).find('span.select-params').data('selectParams');
                
              //   selectObject.parent('span').find('~ span').remove()
              //   selectObject.closest('.col_box').append(data);
              //   selectObject.parent('span').find('~ span').css('opacity','0').animate({opacity:1},600);

              // }else{


              //   selectObject.parent('span').find('~ span').animate({opacity:0}, 600,function(){
              //     jQuerCCK(this).remove();
              //   });

              // }

                if(data){

                  var selectParams = jQuerCCK(data).find('span.select-params').data('selectParams');
              
                  var html_start = '<div class="drop-item" data-parent="'+unique_parent_id+'" data-content=""><span class="f-inform-button"></span><!-- clear back/front title --><span class="field-name" data-field-name="'+selectParams.db_field_name+'" data-field-type="text_select_list" style="font-weight:normal;">'+selectParams.field_name+'</span><span class="col_box admin_aria_hidden">';
                  
                  var html_end = '</span><input class="f-params" name="fi_Params_'+selectParams.db_field_name+'" type="hidden" value="{}"></div>';

                  data = html_start+data+html_end;
                 
                  selectObject.closest('.drop-item').find('~ .drop-item[data-parent='+unique_parent_id+']').remove()
                  selectObject.closest('.drop-item').after(data);
                  selectObject.closest('.drop-item').find('~ .drop-item[data-parent='+unique_parent_id+']').css('display','none').show(400);

                }else{

                  selectObject.closest('.drop-item').find('~ .drop-item[data-parent='+unique_parent_id+']').hide(400,function(){
                    jQuerCCK(this).remove();
                  });

                }
              
            }
        })
    })
}




