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
$mod_id = (isset($moduleId)) ? '_cckmod_'.$moduleId : ''; 
$fName = $field->db_field_name;
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
?>
<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
<div class="rev_rating">
    <span <?php echo $layout_params['field_styling']?> 
        id="star<?php echo $mod_id?>" 
        class="<?php echo $layout_params['custom_class'].$required?>">
    </span>
</div>
</span>
<script type="text/javascript">
    os_img_path = "<?php echo JURI::root()?>/components/com_os_cck/images/";
    jQuerCCK(document).ready(function(){
        var modId = '<?php echo $mod_id ;?>';
        jQuerCCK("#<?php echo $field_from_params['form_id'];?>"+' #star'+modId).raty({
            scoreName: "<?php echo 'fi_' . $field->db_field_name;?>",
            score: "<?php echo $value;?>",
            starHalf: os_img_path+'star-half.png',
            starOff: os_img_path+'star-off.png',
            starOn: os_img_path+'star-on.png'
        });
    });
</script>