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
$required = '';
$step = '1';
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
$max = str_pad('',$field_from_params[$fName.'_digits'], 9);
$placeholder = isset($layout_params['fields'][$field->db_field_name."_placeholder"])?$layout_params['fields'][$field->db_field_name."_placeholder"]:'';
if($field_from_params[$fName.'_digits_points'] != 0){
    $step = str_pad(0, ($field_from_params[$fName.'_digits']+1),0);
    $step = substr_replace($step,'.',(strlen($step)-1-$field_from_params[$fName.'_digits_points']),1);
}
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$os_cck_configuration = JComponentHelper::getParams('com_os_cck');

if(isset($field_from_params[$fName.'_price_field']) && $field_from_params[$fName.'_price_field'] && strlen($os_cck_configuration->get('paypal_currency',''))){
    $paypal_currency = cck_getCurrency($os_cck_configuration);
    $currencyOpt = array();
    foreach ($paypal_currency as $carrencyArr) {
      $currencyOpt[] = JHTML::_('select.option', $carrencyArr['sign'], $carrencyArr['signAlias'], "value", "text");
    }
    $currency = (isset($layout_params['currency']) && !empty($layout_params['currency']))?$layout_params['currency'] : '';
    $currencySelect = JHTML::_('select.genericlist', $currencyOpt, "instance_currency", 'class="cck-currency"', 'value', 'text', $currency);

?><span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> ><?php

    if($os_cck_configuration->get('currency_position','0')){
      //before
      if(!isset($show_currency)){
        echo $currencySelect;
        $show_currency = true;
      }?>
      <input <?php echo $layout_params['field_styling']?> 
            class="<?php echo $layout_params['custom_class'].$required?> price_field" 
            type="number" 
            step="<?php echo $step?>" 
            min ="0"
            max="<?php echo $max?>"
            placeholder="<?php echo $placeholder?>"
            name="fi_<?php echo $field->db_field_name?>" 
            value="<?php echo $value?>"/>
      <?php
    }else{
        //after?>
        <input <?php echo $layout_params['field_styling']?> 
            class="<?php echo $layout_params['custom_class'].$required?> price_field"
            type="number"
            step="<?php echo $step?>"
            min ="0"
            max="<?php echo $max?>"
            placeholder="<?php echo $placeholder?>"
            name="fi_<?php echo $field->db_field_name?>"
            value="<?php echo $value?>"/>
        <?php
        if(!isset($show_currency)){
            echo $currencySelect;
            $show_currency = true;
        }
    } ?>
    <input type="hidden" name="price_fields[]" value="<?php echo $field->db_field_name?>">
    <?php
}else{ ?>
    <input <?php echo $layout_params['field_styling']?> 
        class="<?php echo $layout_params['custom_class'].$required?>"
        type="number"
        step="<?php echo $step?>"
        min ="0"
        max="<?php echo $max?>"
        placeholder="<?php echo $placeholder?>"
        name="fi_<?php echo $field->db_field_name?>"
        value="<?php echo $value?>"/>
    <?php
}?></span>