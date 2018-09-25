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
$fParams['type'] = isset($layout_params['fields']['search_'.$fName])?$layout_params['fields']['search_'.$fName]:'';

if($fParams['type'] == '2'){?>
    <tr>
        <td class="search_description">
            <input type="checkbox" value="on" name='os_cck_search_<?php echo $fName?>' checked="checked" >
        </td>
    <tr>
    <?php
    return;
}
if($fParams['type'] == '3'){ ?>
    <tr>
        <td class="search_description">
            <input type="hidden" name='os_cck_search_<?php echo $fName?>' value="on" >
        </td>
    <tr>
    <?php
    return;
}

$query = "SELECT ent.".$fName." FROM #__os_cck_content_entity_".$field->fk_eid." as ent"
          ."\n LEFT JOIN #__os_cck_entity_instance inst ON inst.eiid = ent.fk_eiid"
          ."\n LEFT JOIN #__os_cck_layout as lay ON lay.lid = inst.fk_lid WHERE lay.type ='add_instance'";
$db->setQuery($query);
$prices = $db->loadColumn();

rsort($prices,SORT_NUMERIC);
$max_number = ceil($prices[0]);



if(!$max_number){?>
    <tr>
        <td class="search_description">
            <input type="hidden" name='os_cck_search_<?php echo $fName?>' value="on" >
        </td>
    <tr>
    <?php
    return;
}

$min_number = $prices[count($prices)-1];
if(!$min_number)$min_number = '0';


$i = $layout->lid;

?>
<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
<tr>
    <td>
        <div <?php echo $field_styling?> class="<?php echo $custom_class.$i?> col_box_1">
            <div class="pricefrom_2">
                <?php echo JText::_("COM_OS_CCK_SEARCH_NAMBER_FROM")?>
                <input type="text" id="os_cck_search_<?php echo $fName.$i?>_from" name="os_cck_search_<?php echo $fName?>_from" value="<?php echo $min_number?>" />
                <?php echo JText::_("COM_OS_CCK_SEARCH_NAMBER_To")?>
                <input type="text" id="os_cck_search_<?php echo $fName.$i?>_to" name="os_cck_search_<?php echo $fName?>_to" value="<?php echo $max_number?>"/>
            </div>
            <div id="slider_os_cck_search_<?php echo $fName.$i?>" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
        </div>
    </td>
</tr>
</span>
<script type="text/javascript">


    jQuerCCK(function() {

        jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider({
            min: <?php echo (int) $min_number;?>,
            max: <?php echo (int) $max_number;?>,
            values: [<?php echo (int) $min_number;?>,<?php echo (int) $max_number;?>],
            range: true,
            stop: function(event, ui) {
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val(jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",0));
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val(jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",1));
            },
            slide: function(event, ui){
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val(jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",0));
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val(jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",1));
            }
        });

        jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").change(function(){

            var value1=jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val();
            var value2=jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val();

            if(parseInt(value1) > parseInt(value2))
                {
                    value1 = value2;
                    jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val(value1);
                }

            if(parseInt(value1)<(<?php echo (int) $min_number;?>))
                {
                    jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val(<?php echo (int) $min_number;?>);
                }

            jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",0,value1);
        });

        jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").change(function(){
            var value1=jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_from").val();
            var value2=jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val();

            if (value2 > '<?php echo $max_number?>') {
                value2 = '<?php echo $max_number?>';
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val('<?php echo $max_number?>')
            }

            if(parseInt(value1) > parseInt(value2)){
                value2 = value1;
                jQuerCCK("input#os_cck_search_<?php echo $fName.$i?>_to").val(value2);
            }

          jQuerCCK("#slider_os_cck_search_<?php echo $fName.$i?>").slider("values",1,value2);
      });
    });
</script>