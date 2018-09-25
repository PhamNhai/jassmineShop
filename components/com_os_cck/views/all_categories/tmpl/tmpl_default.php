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

if(!function_exists('showAllCat')){
  function showAllCat($option, $id, $categories, $params, $Itemid, $deep,$layout,$layout_params) {
    global $mosConfig_live_site,$moduleId;
    $deep++;
    for ($i = 0; $i < count($categories); $i++) {
      if (($id == $categories[$i]->parent_id) && ($categories[$i]->display == 1)) {
        $layout_html = urldecode($layout->layout_html);
        $field = new stdClass();
        // $field->db_field_name = $layout_params['key'];
        // $field_styling = get_field_styles($field, $layout);
        // $custom_class = get_field_custom_class($field, $layout);

        $field->db_field_name = "l".$layout_params['key'];
        $field_styling = get_field_styles($field, $layout);
        $custom_class = get_field_custom_class($field, $layout);
        $button_style = array('field_styling'=>$field_styling,'custom_class'=>$custom_class);

        //get tag
        $shell_tag = isset($layout_params['fields']['field_tag_'.$field->db_field_name])?$layout_params['fields']['field_tag_'.$field->db_field_name]:'div';
        //end
        //if below fn works , that this is add_instance view
        echo '<'.$shell_tag.' class="cat_instance_body'.$custom_class.'" '.$field_styling.'>';

        Category::show_attached_layout($option, $layout_params['key'], $layout->fk_eid, $layout_params,
                                       $layout_params['show_type'],'',$categories[$i]->cid, $categories, $button_style);
        echo '</'.$shell_tag.'>';
      }//end if
    }//end for
  }//end fn
}
if(isset($layout_params['views']['show_layout_title']) && $layout_params['views']['show_layout_title'])
  {
        echo "<h3>";
          echo $layout_params['views']['layout_title'];
        echo "</h3>";
  }
$field = new stdClass();
$field->db_field_name = 'form-'.$layout->lid;
$form_styling = get_field_styles($field, $layout);
$form_custom_class = get_field_custom_class($field, $layout);

if(isset($layout_params["views"]["instance_grid"]) && $layout_params["views"]["instance_grid"] == 1){ ?>

            <script>
              
               jQuerCCK(document).ready(function(){

                jQuerCCK(".cat_instance_body").parent().addClass('flex-block');

               });

            </script>

            <?php  }

            $unique_class = 'all_category_unique_class_'.$layout->lid;

            $cck_wrapper_instance = (isset($layout_params["views"]["instance_grid"]) && $layout_params["views"]["instance_grid"] == 1)?"cck-wrapper-allcat":"";

            $resolutions = ($cck_wrapper_instance != "" && isset($layout_params["views"]["auto_custom"]) && $layout_params["views"]["auto_custom"] == "custom")?"instance-col-lg-".$layout_params["views"]["resolition_one"]." "."instance-col-md-".$layout_params["views"]["resolition_two"]." "."instance-col-sm-".$layout_params["views"]["resolition_three"]." "."instance-col-xs-".$layout_params["views"]["resolition_four"]." ":'';

            if($cck_wrapper_instance != "" &&  $resolutions == ""){

              $count_inst_columns = (isset($layout_params["views"]["count_inst_columns"]))?$layout_params["views"]["count_inst_columns"]:"";
              $min_width = (isset($layout_params["views"]["lay_min_width"]))?$layout_params["views"]["lay_min_width"]:"";
              $space_between = (isset($layout_params["views"]["space_between"]))?$layout_params["views"]["space_between"]:"";

              ?>
                <script type="text/javascript">

                jQuerCCK( document ).ready(function() {

                    var cckGridLayout1 = function (container, params) {

                        if (!(this instanceof cckGridLayout1)) return new cckGridLayout1(container, params);

                        var defaults = {
                            minImgEnable : 1,
                            spaceBetween: 0,
                            minImgSize: 200,
                            numColumns: 4,
                        };

                        for (var param in defaults) {
                            if (!params[param]){
                            params[param] = defaults[param];
                            }
                        }
                        // gallery settings
                        var cckGrid = this;
                        // Params
                        cckGrid.params = params || defaults;

                            cckGrid.getImgBlockWidth = function (numColumns){
                                if(typeof(numColumns) == 'undefined')numColumns = cckGrid.params.numColumns;
                                spaceBetween = cckGrid.params.spaceBetween;
                                mainBlockW = jQuerCCK(container).width();
                                imgBlockW = ((((mainBlockW-(spaceBetween*numColumns))/numColumns)-1)*100)/mainBlockW;

                                if(cckGrid.params.minImgEnable){

                                    if(((imgBlockW*mainBlockW)/100) < cckGrid.params.minImgSize){
                                    numColumns--;
                                    cckGrid.getImgBlockWidth(numColumns);
                                    }
                                }

                                return imgBlockW;
                            }

                        //initialize function
                            cckGrid.init = function (){
                            imgBlockW = cckGrid.getImgBlockWidth();

                        // jQuerCCK(container+" .item").css("width",imgBlockW+"%");


                            jQuerCCK(container+" .cat_instance_body").css({
                            'width': imgBlockW+"%",
                            'margin':  cckGrid.params.spaceBetween / 2 
                            });

                            cckGrid.resizeGallery = function (){
                                imgBlockW = cckGrid.getImgBlockWidth();
                                jQuerCCK(container+" .cat_instance_body").css("width",imgBlockW+"%");
                            }

                            jQuerCCK(window).resize(function(event) {
                            cckGrid.resizeGallery();
                            });
                        }

                        cckGrid.init();
                    }


                window.cckGridLayout1 = cckGridLayout1;

                 });


                jQuerCCK(document).ready(function() {

                    var gallery = new cckGridLayout1(".<?php echo $unique_class; ?>",{
                        minImgEnable : 1,
                        spaceBetween: <?php echo $space_between; ?>,
                        minImgSize: <?php echo $min_width; ?>,
                        numColumns: <?php echo $count_inst_columns; ?>,
                    });



                  });

                </script>
              <?php

            }
?>
<div class="cck-body">
  <div class="<?php echo $cck_wrapper_instance.' '.$unique_class.' '.$resolutions.' '.$form_custom_class; ?> all_cat_main" <?php echo $form_styling; ?> >
    <?php
    $layout_html = urldecode($layout->layout_html);
    if(isset($layout_params['views']['show_request_layout'])){
      foreach ($layout_params['views']['show_request_layout'] as $key => $value) {
        $layout_params['show_type'] = isset($layout_params['views']['show_type_request_layout'][$key][0])?$layout_params['views']['show_type_request_layout'][$key][0]:1;
        if(strpos($layout_html,"{|l-".$key."|}")){
          $layout_params['key'] = $key;
          //if below fn works , that this is add_instance view

          if(isset($layout_params['views']['show_request_layout'][$layout_params['key']])
              && $layout_params['views']['show_request_layout'][$layout_params['key']] != '1'){
            $user = JFactory::getUser();
            if(!checkAccess_cck($layout_params['views']['show_request_layout'][$layout_params['key']], $user->groups)){
              $layout_html = str_replace("{|l-".$key."|}", '', $layout_html);
              continue;
            }
          }
          if(isset($layout_params['views']['show_request_layout_name'][$layout_params['key']]) &&
              isset($layout_params['views']['show_request_layout_name'][$layout_params['key']][0]) &&
              $layout_params['views']['show_request_layout_name'][$layout_params['key']][0] == 'on'){
            $layout_html = str_replace($layout_params['key'].'-label-hidden', '', $layout_html);
          }
          ob_start();
          showAllCat($option, 0, $categories, $params, $Itemid, 0,$layout,$layout_params);
          $layout_html = str_replace("{|l-".$key."|}", ob_get_contents(), $layout_html);
          ob_end_clean();
        }
      }
    }
    //start render custom code
    if(count($layout_params['custom_fields'])){
      foreach ($layout_params['custom_fields'] as $cust_key => $custom_field) {
        if(strpos($layout_html,"{|f-custom_code_field_".$cust_key."|}")){
          //check access
          if(isset($custom_field['custom_code_field_'.$cust_key.'_access'])
              && $custom_field['custom_code_field_'.$cust_key.'_access'] != '1'){
            $user = JFactory::getUser();
            if(!checkAccess_cck($custom_field['custom_code_field_'.$cust_key.'_access'], $user->groups)){
              $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", '', $layout_html);
              continue;
            }
          }
          $dispatcher = JDispatcher::getInstance();
          JPluginHelper::importPlugin('content');
          $plug_row = new stdClass();
          $plug_row->text = $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
          $dispatcher->trigger('onContentPrepare', array('com_os_cck', &$plug_row, &$plug_params, 0));
          $custom_field['custom_code_field_'.$cust_key.'_custom_code'] = $plug_row->text;
          //if below fn works , that this is add_instance view
          $code_type = $custom_field['custom_code_field_'.$cust_key.'_custom_code_type'];
          if($code_type == 'SCRIPT'){
            $custom_code = '<script type="text/javascript">';
            $custom_code .= $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
            $custom_code .= '</script>';
            $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_code, $layout_html);
          }elseif($code_type == 'PHP'){
            ob_start();
            $custom_code = eval($custom_field['custom_code_field_'.$cust_key.'_custom_code']);
            $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", ob_get_contents(), $layout_html);
            ob_end_clean();
          }elseif($code_type == 'CSS'){
            $custom_css = '<style>'.$custom_field['custom_code_field_'.$cust_key.'_custom_code'].'</style>';
            $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_css, $layout_html);
          }else{
            $custom_code = $custom_field['custom_code_field_'.$cust_key.'_custom_code'];
            $layout_html = str_replace("{|f-custom_code_field_".$cust_key."|}", $custom_code, $layout_html);
          }
        }
      }
    }
    //end render
    if(isset($layout_params['attachedModule'])){
      foreach ($layout_params['attachedModule'] as $attachedModule) {
        if($attachedModule){
          if(strpos($layout_html,"{|m-".$attachedModule->id."|}")){
            $field = new stdClass();
            $field->db_field_name = 'm_'.$attachedModule->id;
            $field_styling = get_field_styles($field, $layout);
            $custom_class = get_field_custom_class($field, $layout);
            //get tag
            $shell_tag = isset($layout_params['fields']['field_tag_'.$field->db_field_name])?$layout_params['fields']['field_tag_'.$field->db_field_name]:'div';
            //end
            $module = JModuleHelper::getModule($attachedModule->type,$attachedModule->title);
            $options  = array('style' => 'xhtml');
            $html = '<'.$shell_tag.' class="'.$custom_class.'" '.$field_styling.'>'.JModuleHelper::renderModule($module,$options).'</'.$shell_tag.'>';
            $layout_html = str_replace("{|m-".$attachedModule->id."|}", $html, $layout_html);
          }
        }
      }
    }
    $layout_html = str_replace('data-label-styling', 'style',  $layout_html);
    echo $layout_html;
    ?>
  </div>
  <script>
    jQuerCCK("div[class *='cck-row-'], div[id *='cck_col-']").each(function(index, el) {
      jQuerCCK(el).attr("style",jQuerCCK(el).data("block-styling"))
    });
    jQuerCCK("div[class *='cck-row-']").addClass('row');
    //jQuerCCK("span.cck-help-string").remove();
    jQuerCCK(function () {
      jQuerCCK('[data-toggle="tooltip"]').tooltip()
    })
  </script>
</div>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>