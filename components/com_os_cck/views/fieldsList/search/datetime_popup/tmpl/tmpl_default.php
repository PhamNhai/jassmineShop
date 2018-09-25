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

global $db;

$fName = $field->db_field_name;
$fParams['type'] = isset($layout_params['fields']['search_'.$fName])?$layout_params['fields']['search_'.$fName]:'';
$inputFormat = isset($fields_from_params[$fName.'_input_format'])?$fields_from_params[$fName.'_input_format']:'d-m-Y';

if($fParams['type'] == 1){
?>

  <div>
    <script type="text/javascript">
      jQuerCCK(document).ready(function(){
        jQuerCCK( "#os_cck_search_<?php echo $fName?>_datefrom,#os_cck_search_<?php echo $fName?>_dateto" ).datepicker({
          minDate: "+0",
          dateFormat: '<?php echo transforDateFromPhpToJquery($inputFormat)?>'
        });
      });
    </script>

<?php

    $layout_fk_eid = $layout->fk_eid;
    $layout_type = 'rent_request_instance';

    $layout_one = new os_cckLayout($db);
    $lid = $layout_one->getLayoutParams($layout_fk_eid, $layout_type);
    $layout_one->load($lid);
    $fields_from_params = unserialize($layout_one->params);
    
  ?> 
  <span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <?php

    if($fields_from_params['fields'][$fName."_field_type"] == "rent_from")
          {
              ?>
              <input  <?php echo $field_styling?> class="<?php echo $custom_class?>" type="text" id="os_cck_search_<?php echo $fName?>_datefrom" name="os_cck_search_<?php echo $fName?>_datefrom">
              <?php
          }

    if($fields_from_params['fields'][$fName."_field_type"] == "rent_to")
        {
            ?>
            <input  <?php echo $field_styling?> class="<?php echo $custom_class?>" type="text" id="os_cck_search_<?php echo $fName?>_dateto" name="os_cck_search_<?php echo $fName?>_dateto">
            <?php
        }
  ?></span><?php
?>
     
  </div>

  <?php
  }

  //one default field
  elseif($fParams['type'] == 2){?> 

     <script type="text/javascript">
      jQuerCCK(document).ready(function(){
        jQuerCCK( "#os_cck_search_<?php echo $fName?>_range_eventfrom" ).datepicker({
          dateFormat: '<?php echo transforDateFromPhpToJquery($inputFormat)?>'
        });
      });
    </script>

    <div>
        <span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
              {?>
          rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
          <?php } ?> >

          <input  <?php echo $field_styling?> 
                class="<?php echo $custom_class?>" 
                type="text" 
                id="os_cck_search_<?php echo $fName?>_range_eventfrom" 
                name="os_cck_search_<?php echo $fName?>_range_eventfrom">

        </span>
         
    </div>

  <?php
    }

 //range default field
  elseif($fParams['type'] == 3){?>

    <script type="text/javascript">
      jQuerCCK(document).ready(function(){
        jQuerCCK( "#os_cck_search_<?php echo $fName?>_range_eventfrom, #os_cck_search_<?php echo $fName?>_range_eventto" ).datepicker({
          dateFormat: '<?php echo transforDateFromPhpToJquery($inputFormat)?>'
        });
      });
    </script>

    <div>

        <span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
              {?>
          rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
          <?php } ?> >

          <label>From:</label><input  <?php echo $field_styling?> 
                class="<?php echo $custom_class?>" 
                type="text" 
                id="os_cck_search_<?php echo $fName?>_range_eventfrom" 
                name="os_cck_search_<?php echo $fName?>_range_eventfrom">

          <label>To:</label><input  <?php echo $field_styling?> 
                class="<?php echo $custom_class?>" 
                type="text" 
                id="os_cck_search_<?php echo $fName?>_range_eventto" 
                name="os_cck_search_<?php echo $fName?>_range_eventto">
        </span>
         
    </div>

  <?php
  }

  elseif($fParams['type'] == 4){?>
  <input type="hidden" name="os_cck_search_<?php echo $fName?>" value="on">
  <?php
  }