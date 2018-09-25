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

$fName = $field->db_field_name;
if(isset($layout->type) && $layout->type == 'buy_request_instance'){
  echo '<input class="hidden-price" type="hidden" name="fi_'.$fName.'" value="'.$value[0]->data.'">';
}

if(isset($layout_params["fields"][$fName."_price_field"]) && $layout_params["fields"][$fName."_price_field"] && strlen($os_cck_configuration->get('paypal_currency',''))){
  $paypal_currency = isset($layout_params['instance_currency'])?$layout_params['instance_currency']:'';
  if($os_cck_configuration->get('currency_position','0')){
    $val = $paypal_currency.' ';
    $val .= (isset($value[0]->data))? $value[0]->data : '0';
  }else{
    $val = (isset($value[0]->data))? $value[0]->data : '0';
    $val.= ' '.$paypal_currency;
  }
}else{
  $val = (isset($value[0]->data))?$value[0]->data : '';
}

?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <?php
  if ($val){
      if(!empty($layout_params['fields'][$fName.'_prefix'])){
          echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
      }

      if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
          $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
          $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
      }
      echo $val;

    if(!empty($layout_params['fields'][$fName.'_suffix'])){
        echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
    }
  }?>
</span>