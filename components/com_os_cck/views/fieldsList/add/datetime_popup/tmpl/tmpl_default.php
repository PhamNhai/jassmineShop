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


$field_from_params['ceid'] = $layout->fk_eid;
$field_from_params['lay_type'] = $layout_type;
$field_from_params['field_styling'] = $field_styling;
$field_from_params['custom_class'] = $custom_class;
$field_from_params['form_id'] = isset($field_from_params['form_id'])?$field_from_params['form_id']:'adminForm';

$fName = $field->db_field_name;
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required'] == 'on')
    $required = ' required ';
$readonly = '';
$defVal = $field_from_params[$fName.'_default_val'];
$input_format = isset($field_from_params[$fName.'_input_format'])?trim($field_from_params[$fName.'_input_format']):'15';
$input_time_format = isset($field_from_params[$fName.'_input_time_format'])?trim($field_from_params[$fName.'_input_time_format']):'H:i:s';
$time_value = '';

$step_time = isset($field_from_params[$fName.'_step_time']) ? $field_from_params[$fName.'_step_time'] : '15';

if(trim($step_time) == '') $step_time = false;

if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}

if($defVal == 'CHANGE' || $defVal == 'CREATE' && empty($value)){
    $value = date($input_format);
    $readonly=' readonly ';
}


$value = (isset($value) && $value != '' && $value != "0000-00-00 00:00:00") ? $value : date($input_format);

if($input_time_format != ''){
    $time_value = date($input_time_format , strtotime(data_transform_cck($value,$input_time_format)));
}

if($input_format != ''){
  $date_value = date($input_format, strtotime(data_transform_cck($value,$input_format)));
}else{
  $input_format = "Y-m-d";
  $date_value = date($input_format);
}



if(isset($field_from_params[$fName.'_field_type']) && $field_from_params[$fName.'_field_type'] == 'rent_from'){
    
    $id = 'rent_from_'.$field_from_params['form_id'];
    $date_NA = available_dates_cck($field_from_params['eiid'],$step_time);
    //initial auto first time
    $minutes_for_day = available_times_cck($field_from_params['eiid']);
    $minutes_for_day_rent_to = available_times_cck($field_from_params['eiid'], true, $step_time);

}elseif(isset($field_from_params[$fName.'_field_type']) &&  $field_from_params[$fName.'_field_type'] == 'rent_to'){
    $id = 'rent_until_'.$field_from_params['form_id'];
    $date_NA = available_dates_cck($field_from_params['eiid'],$step_time);
    //initial auto first time
    $minutes_for_day = available_times_cck($field_from_params['eiid']);
    $minutes_for_day_rent_to = available_times_cck($field_from_params['eiid'], true, $step_time);
    

}else{
    $id = 'fi_'.$fName.$field_from_params['form_id'];
}



?>
<script type="text/javascript">
  var unavailabelDates = Array();
  jQuerCCK(document).ready(function() {
    var k=0;
    <?php if(!empty($date_NA)){
      foreach ($date_NA as $N_A){ ?>
        unavailabelDates[k]= '<?php echo $N_A; ?>';
        k++;
      <?php } ?>
    <?php } ?>

    function unavailabelFrom(date) {
      dmy = date.getFullYear() + "-" + ('0'+(date.getMonth() + 1)).slice(-2) +
        "-" + ('0'+date.getDate()).slice(-2);
      if (jQuerCCK.inArray(dmy, unavailabelDates) == -1) {
        return [true, ""];
      } else {
        return [false, "", "Unavailabel"];
      }
    }

    function unavailabelUntil(date) {
      dmy = date.getFullYear() + "-" + ('0'+(date.getMonth() + 1)).slice(-2) +
        "-" + ('0'+(date.getDate()-("<?php  if($os_cck_configuration->get('rent_type') == 0 && $os_cck_configuration->get('by_time', 0) == 0) echo 1;?>"))).slice(-2);
      if (jQuerCCK.inArray(dmy, unavailabelDates) == -1) {
        return [true, ""];
      } else {
        return [false, "", "Unavailabel"];
      }
    }


  <?php
  if($defVal == 'CHANGE' || $defVal == 'CREATE' && empty($value)){
    //datepicker not create
  }elseif(!isset($field_from_params[$fName.'_field_type']) || $field_from_params[$fName.'_field_type'] == 'default'){
    ?>
    jQuerCCK(document).ready(function() {
      jQuerCCK( "#<?php echo $field_from_params['form_id'];?> #<?php echo $id?>" ).datepicker({
        minDate: "+0",
        dateFormat: "<?php echo transforDateFromPhpToJquery($input_format);?>",
      });
    })
    <?php
  }elseif($field_from_params[$fName.'_field_type'] == 'rent_from'){?>
    jQuerCCK( "#rent_from_<?php echo $field_from_params['form_id'];?>" ).datepicker({
      minDate: "+0",
      dateFormat: "<?php echo transforDateFromPhpToJquery($input_format);?>",
      beforeShowDay: unavailabelFrom,
      onClose: function () {
        jQuerCCK.ajax({
          type: "POST",
          url: '<?php echo JUri::current()?>',
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date "),
          success: function( data ) {
            jQuerCCK("#alert_date").html("");
          }
        });
        var rent_from = jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>").val();
        var rent_until = jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>").val();
        var time_from = jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").val();
        var time_until = jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").val();

        jQuerCCK("[name=rent_from]").val(rent_from);
    jQuerCCK("[name=rent_until]").val(rent_until);
    jQuerCCK("[name=time_from]").val(time_from);
    jQuerCCK("[name=time_until]").val(time_until);
        jQuerCCK.ajax({
          type: "POST",
          url: "index.php?option=com_os_cck&task=ajax_rent_calcualete"
            +"&rent_eiid=<?php echo $field_from_params['eiid']; ?>&current_lid=<?php echo $layout->lid?>"+
            "&rent_ceid=<?php echo $field_from_params['ceid']; ?>&rent_from="+
            rent_from+"&rent_until="+rent_until,
          data: { " #do " : " #1 " },
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date"),
          success: function( data ) {
            if(data){
               data = JSON.parse(data);
            }
            if(typeof data.price != 'undefined' && typeof data.currency != 'undefined'){
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> .message-here").html(data.price+' '+data.currency);
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> [name=calculated_price]").val(data.price);
            }
          }
        });
      }
    });
    <?php
  }elseif($field_from_params[$fName.'_field_type'] == 'rent_to'){?>
    jQuerCCK( "#rent_until_<?php echo $field_from_params['form_id'];?>" ).datepicker({
      minDate: "+0",
      dateFormat: "<?php echo transforDateFromPhpToJquery($input_format);?>",
      beforeShowDay: unavailabelUntil,
      onClose: function () {
        jQuerCCK.ajax({
          type: "POST",
          url: '<?php echo JUri::current()?>',
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date "),
          success: function( data ) {
            jQuerCCK("#<?php echo $field_from_params['form_id'];?> #alert_date").html("");
          }
        });
        var rent_from = jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>").val();
        var rent_until = jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>").val();
        var time_from = jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").val();
        var time_until = jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").val();

        jQuerCCK("[name=rent_from]").val(rent_from);
    jQuerCCK("[name=rent_until]").val(rent_until);
    jQuerCCK("[name=time_from]").val(time_from);
    jQuerCCK("[name=time_until]").val(time_until);

        jQuerCCK.ajax({
          type: "POST",
          url: "index.php?option=com_os_cck&task=ajax_rent_calcualete"
            +"&rent_eiid=<?php echo $field_from_params['eiid']; ?>&current_lid=<?php echo $layout->lid?>"+
            "&rent_ceid=<?php echo $field_from_params['ceid']; ?>&rent_from="+
            rent_from+"&rent_until="+rent_until,
          data: { " #do " : " #1 " },
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date"),
          success: function( data ) {
            if(data){
               data = JSON.parse(data);
            }
            
            if(typeof data.price != 'undefined' && typeof data.currency != 'undefined'){
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> .message-here").html(data.price+' '+data.currency);
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> [name=calculated_price]").val(data.price);
            }             
          }
        });
      }
    });
    <?php
  }?>
});
</script>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >

<input <?php echo $layout_params['field_styling']?> 
        class="<?php echo $layout_params['custom_class'].$required?> calendar_input"
        placeholder="<?php echo $field_from_params[$fName.'_placeholder']?>"
        <?php echo $readonly?>
        type="text"
        id="<?php echo $id?>"
        name=""
        value="<?php echo $date_value?>">


<?php
  // if(isset($field_from_params[$fName.'_field_type']) 
  //   && ($field_from_params[$fName.'_field_type'] != 'rent_from' 
  //       || $field_from_params[$fName.'_field_type'] != 'rent_to')){
?>

<?php if($os_cck_configuration->get('by_time', 0) && $time_value != ''): ?>
  <input <?php echo $layout_params['field_styling']?> 
          id="<?php echo $id.'time_picker'?>" 
          class="<?php echo $layout_params['custom_class'].$required?> ui-timepicker-input"
          <?php echo $readonly?>
          type="text" 
          name="" 

          <?php if(isset($field_from_params[$fName.'_field_type']) 
            && $field_from_params[$fName.'_field_type'] == 'rent_to'){
            echo "value='". date($input_time_format,strtotime('23:59:59')) ."'";
          }elseif(isset($field_from_params[$fName.'_field_type']) 
            && $field_from_params[$fName.'_field_type'] == 'rent_from'){
            echo "value='". date($input_time_format,strtotime('00:00:00')) ."'";
          }else{
            echo "value='".$time_value."'";  
          }?>
  >
<?php endif; ?>

<input type="hidden" class="<?php echo $id.'hidden'?>" name="fi_<?php echo $fName?>" value="<?php echo $date_value?> <?php echo $time_value;?>">

<?php
  // } 
?>

</span>


<?php 



//$minutes_for_day to json
if(isset($minutes_for_day)){  
  $minutes_for_day = json_encode($minutes_for_day);
}else{
  $minutes_for_day = '{}';
}

if(isset($minutes_for_day_rent_to)){  
  $minutes_for_day_rent_to = json_encode($minutes_for_day_rent_to);
}else{
  $minutes_for_day_rent_to = '{}';
}

?>

<?php 
  if($step_time){
    $step_time = "'forceRoundTime': true,'step': ".(int)$step_time.",";
  }else{
    $step_time = '';
  }
?>

<?php if(!isset($field_from_params[$fName.'_field_type']) || $field_from_params[$fName.'_field_type'] == 'default'):?>

  <script type="text/javascript">

  jQuerCCK(document).ready(function($) {
    

    jQuerCCK("#<?php echo $id;?>, #<?php echo $id;?>time_picker").change(function(event) {

     var date = jQuerCCK("#fi_<?php echo $fName.$field_from_params['form_id'];?>").val();
     var time = jQuerCCK("#fi_<?php echo $fName.$field_from_params['form_id'];?>time_picker").val();

     if(time == undefined) time = '00:00:00';

      if(<?php echo  $os_cck_configuration->get('by_time',0);?>){
        jQuerCCK(".<?php echo $id.'hidden'?>").val(date+" "+time);
      }else{
        jQuerCCK(".<?php echo $id.'hidden'?>").val(date);
      }

    });


   //rent_from timepicker

        jQuerCCK("#<?php echo $id.'time_picker'?>").timepicker(
            { 
              'timeFormat' : "<?php echo transforDateFromPhpToJquery($input_time_format);?>",
              <?php echo $step_time;?>
            }
        );
  });

  </script>

<?php else:?>

<script type="text/javascript">

jQuerCCK(document).ready(function() {



  jQuerCCK("#<?php echo 'fi_'.$fName;?>, #<?php echo 'fi_'.$fName.'time_picker';?>").change(function(event) {
     var date = jQuerCCK("#<?php echo 'fi_'.$fName;?>").val();
     var time = jQuerCCK("#<?php echo 'fi_'.$fName.'time_picker';?>").val();
     if(time == undefined) time = '00:00:00';
     jQuerCCK(".<?php echo 'fi_'.$fName.'hidden'?>").val(date+" "+time);
  });


  jQuerCCK("#rent_from_<?php echo $field_from_params['form_id'];?>, #rent_until_<?php echo $field_from_params['form_id'];?>").change(function(event) {

    var date = new Date(jQuerCCK(this).val());
    date = date.getFullYear()  + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);

      //get unavailable time rent_from_
      unavailable_time = [];
      minutes_for_day = JSON.parse('<?php echo $minutes_for_day;?>');
      if(minutes_for_day[date] !== undefined){
        unavailable_time = minutes_for_day[date];
      }
       
      //get unavailable time rent_until_
      unavailable_time_rent_to = [];
      minutes_for_day_rent_to = JSON.parse('<?php echo $minutes_for_day_rent_to;?>');
      if(minutes_for_day_rent_to[date] !== undefined){
         unavailable_time_rent_to = minutes_for_day_rent_to[date];
      }

    //rent_from timepicker
    if(jQuerCCK(this).attr('id') == 'rent_from_'+"<?php  echo $field_from_params['form_id']?>"){  
      jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").timepicker('remove');
      jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").timepicker(
          { 
            'timeFormat' : "<?php echo transforDateFromPhpToJquery($input_time_format);?>",
            'disableTimeRanges' : unavailable_time,
            <?php echo $step_time;?>
          }
      );
    }

    //rent_until timepicker
    if(jQuerCCK(this).attr('id') == 'rent_until_'+"<?php  echo $field_from_params['form_id']?>"){
      jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").timepicker('remove');
      jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").timepicker(
          { 
            'timeFormat' : "<?php echo transforDateFromPhpToJquery($input_time_format);?>",
            'disableTimeRanges' : unavailable_time_rent_to,
            <?php echo $step_time;?>
          }
      );
    }  
    //rent_from_ time default
    if(jQuerCCK(this).attr('id') == 'rent_from_'+"<?php  echo $field_from_params['form_id']?>"){  
      if(minutes_for_day[date] == undefined){
        jQuerCCK('#rent_from_'+"<?php  echo $field_from_params['form_id']?>"+"time_picker").val('00:00:00');
      }else{
        jQuerCCK('#rent_from_'+"<?php  echo $field_from_params['form_id']?>"+"time_picker").val('');
      }
    }

    //rent_until_ time default
    if(jQuerCCK(this).attr('id') == 'rent_until_'+"<?php  echo $field_from_params['form_id']?>"){
      if(minutes_for_day_rent_to[date] == undefined){
        jQuerCCK("#rent_until_"+"<?php  echo $field_from_params['form_id']?>"+"time_picker").val('23:59:59');
      }else{
        jQuerCCK("#rent_until_"+"<?php  echo $field_from_params['form_id']?>"+"time_picker").val('');
      }
    }

  });

  
  //add date time to hidden datetime field
  jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>, #<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").change(function(event) {
  
   var date = jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>").val();
   var time = jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").val();
   if(time == undefined) time = '00:00:00';
    if(<?php echo  $os_cck_configuration->get('by_time', 0);?>){
      jQuerCCK(".rent_from_<?php echo $field_from_params['form_id'].'hidden'?>").val(date+" "+time);
    }else{
      jQuerCCK(".rent_from_<?php echo $field_from_params['form_id'].'hidden'?>").val(date);
    }

    var rent_from = jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>").val();
    var rent_until = jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>").val();
    var time_from = jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").val();
    var time_until = jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").val();

    jQuerCCK("[name=rent_from]").val(rent_from);
    jQuerCCK("[name=rent_until]").val(rent_until);
    jQuerCCK("[name=time_from]").val(time_from);
    jQuerCCK("[name=time_until]").val(time_until);

    jQuerCCK.ajax({
          type: "POST",
          url: "index.php?option=com_os_cck&task=ajax_rent_calcualete"
            +"&rent_eiid=<?php echo $field_from_params['eiid']; ?>&current_lid=<?php echo $layout->lid?>"+
            "&rent_ceid=<?php echo $field_from_params['ceid']; ?>&rent_from="+
            rent_from+" "+time_from+"&rent_until="+rent_until+" "+time_until,
          data: { " #do " : " #1 " },
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date"),
          success: function( data ) {
            if(data){
               data = JSON.parse(data);
            }
            // console.log(data)
            if(typeof data.price != 'undefined' && typeof data.currency != 'undefined'){
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> .message-here").html(data.price+' '+data.currency);
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> [name=calculated_price]").val(data.price);
            }             
          }
        });

  });

  //add date time to hidden datetime field
  jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>, #<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").change(function(event) {

   var date = jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>").val();
   var time = jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").val();
   if(time == undefined) time = '00:00:00';
    if(<?php echo  $os_cck_configuration->get('by_time', 0);?>){
      jQuerCCK(".rent_until_<?php echo $field_from_params['form_id'].'hidden'?>").val(date+" "+time);
    }else{
      jQuerCCK(".rent_until_<?php echo $field_from_params['form_id'].'hidden'?>").val(date);
    }

    var rent_from = jQuerCCK("#rent_from_<?php echo $field_from_params['form_id']?>").val();
    var rent_until = jQuerCCK("#rent_until_<?php echo $field_from_params['form_id']?>").val();
    var time_from = jQuerCCK("#<?php echo 'rent_from_'.$field_from_params['form_id'].'time_picker'?>").val();
    var time_until = jQuerCCK("#<?php echo 'rent_until_'.$field_from_params['form_id'].'time_picker'?>").val();

    jQuerCCK("[name=rent_from]").val(rent_from);
    jQuerCCK("[name=rent_until]").val(rent_until);
    jQuerCCK("[name=time_from]").val(time_from);
    jQuerCCK("[name=time_until]").val(time_until);

    jQuerCCK.ajax({
          type: "POST",
          url: "index.php?option=com_os_cck&task=ajax_rent_calcualete"
            +"&rent_eiid=<?php echo $field_from_params['eiid']; ?>&current_lid=<?php echo $layout->lid?>"+
            "&rent_ceid=<?php echo $field_from_params['ceid']; ?>&rent_from="+
            rent_from+" "+time_from+"&rent_until="+rent_until+" "+time_until,
          data: { " #do " : " #1 " },
          update: jQuerCCK("#<?php echo $field_from_params['form_id'];?>  #alert_date"),
          success: function( data ) {
            if(data){
               data = JSON.parse(data);
            }
            
            if(typeof data.price != 'undefined' && typeof data.currency != 'undefined'){
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> .message-here").html(data.price+' '+data.currency);
              jQuerCCK("#<?php echo $field_from_params['form_id'];?> [name=calculated_price]").val(data.price);
            }             
          }
        });

  });




  
})
</script>

<?php endif; ?>


