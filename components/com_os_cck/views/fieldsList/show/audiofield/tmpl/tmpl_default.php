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

$audioSrc = array();
$audioType = array();
$audios = $value;
foreach($audios as $audio) {
  if (!empty($audio->src)) {
    $audioSrc[] = $audio->src;
    $audioType[] = $audio->type;
  }
}
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <?php
  if (!empty($audioSrc)) {
    if(!empty($layout_params['fields'][$fName.'_prefix'])){
      echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
    }
    if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
      $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
      $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
    }
    ?>
    <audio controls>
      <?php
      for ($j = 0;$j < count($audioSrc);$j++) {
        if (!strstr($audioSrc[$j], "http")) {?>
          <source src="<?php echo JURI::root().$audioSrc[$j]?>" type="<?php echo $audioType[$j]?>">
          <?php
        }else{ ?>
          <source src="<?php echo $audioSrc[$j]?>" type="<?php echo $audioType[$j]?>">
          <?php
        }
      }?>
    </audio>
    <?php
    if(!empty($layout_params['fields'][$fName.'_suffix'])){
      echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
    }
  }?>
</span>