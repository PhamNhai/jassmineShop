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

$st = "      ";
$algoritm = mt_rand(1, 2);
switch ($algoritm) {
  case 1:
    for ($j = 0; $j < 6; $j += 2) {
        $st = substr_replace($st, chr(mt_rand(97, 122)), $j, 1); //abc
        $st = substr_replace($st, chr(mt_rand(50, 57)), $j + 1, 1); //23456789
    }
    break;
  case 2:
    for ($j = 0; $j < 6; $j += 2) {
        $st = substr_replace($st, chr(mt_rand(50, 57)), $j, 1); //23456789
        $st = substr_replace($st, chr(mt_rand(97, 122)), $j + 1, 1); //abc
    }
    break;
}
//****** begin search in $st simbol 'o, l, i, j, t, f'   ************
$st_validator = "olijtf";
for ($j = 0; $j < 6; $j++) {
  for ($i_cap = 0; $i_cap < strlen($st_validator); $i_cap++) {
    if ($st[$j] == $st_validator[$i_cap]) {
      $st[$j] = chr(mt_rand(117, 122)); //uvwxyz
    }
  }
}
//*****   end search in $st simbol 'o, l, i, j, t, f'  *****************
$session = JFactory::getSession();

// if($task != 'onCloseDatapicker' && $task != 'ajax_rent_calcualete')
// {
    $session->set('captcha_'.$layout_type.'_keystring', $st);
// }


if(!isset($layout_params['custom_class']))
  $layout_params['custom_class'] = '';
if(isset($field) && isset($layout_params['fields']['showName_' . $fName]) &&
  $layout_params['fields']['showName_' . $fName] == 'on'){
  $layout_html = str_replace($fName . '-label-hidden', '', $layout_html);
}
?>
<div class="<?php echo $layout_params['custom_class']?>">
  <script>
    var reload_captcha_<?php echo $layout_type?> = null;
    (function ($) {
      reload_captcha_<?php echo $layout_type?> = function (moduleId,type) {
        var status = ""
        var fName = "<?php echo $fName ?>";
        $.post("index.php?option=com_os_cck&captcha_type="+type+"&task=reload_captcha&captchafName="+fName+"&lid=<?php echo $layout->lid?>&format=raw",
        function (data) {
          jQuerCCK("#captcha-block-<?php echo $layout_type?>").html(data.html);
        } , "json" )
      }
    })(jQuerCCK)
  </script>
  <div id="captcha-block-<?php echo $layout_type?>">
    <?php
    if(isset($_REQUEST['error']) && $_REQUEST['error'] != ""){?>
      <font style='color:red'><?php echo protectInjectionWithoutQuote('error')?></font><br />
      <?php
    }
    $name_user = "";
    if (isset($_REQUEST['name_user'])) $name_user = protectInjectionWithoutQuote('name_user','','STRING');?>

      <?php $link = JRoute::_("index.php?option=com_os_cck&task=secret_image&type=$layout_type&ttt=" . rand(0,15000));?>
      <img id="captcha_img_<?php echo $layout_type?>" 
            src="<?php echo $link;?>"
            alt="CAPTCHA_picture"/>
      <img id="captcha_img_<?php echo $layout_type?>" 
          src="<?php echo JUri::root()?>/components/com_os_cck/images/reload.png" 
          onClick="reload_captcha_<?php echo $layout_type.'(0,'.$db->Quote($layout_type).')'?>"
          alt="reload" />
  </div>
  <?php
  if($task != "reload_captcha"){?>
    <div>
      <?php echo JText::_("COM_OS_CCK_LABEL_REVIEW_KEYGUEST")?>
    </div>
    <span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
    <div>
      <input <?php echo $layout_params['field_styling']?> class="inputbox" type="text" name="keyguest" size="6" maxlength="6"/>
      <input class="inputbox" type="hidden" name="type" value="<?php echo $layout_type;?>"/>
    </div>
    </span>
  <?php 
  }?>
</div>