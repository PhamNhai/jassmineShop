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

$description_show = (isset($lParams['description_'.$fName])) ? 'checked="true"' : '';
$field_prefix = (isset($lParams[$fName.'_prefix'])) ? $lParams[$fName.'_prefix'] : '';
$field_suffix = (isset($lParams[$fName.'_suffix'])) ? $lParams[$fName.'_suffix'] : '';
$fld_alias = (isset($lParams[$fName.'_alias'])) ? $lParams[$fName.'_alias'] : $field->field_name;
$field_tooltip = (isset($lParams[$fName.'_tooltip'])) ? $lParams[$fName.'_tooltip'] : '';
$access_selected = (isset($lParams['access_'.$fName])) ? $lParams['access_'.$fName] : '1';
$width  = (isset($lParams[$fName]['options']['width'])) ? $lParams[$fName]['options']['width'] : 100;
$height = (isset($lParams[$fName]['options']['height'])) ? $lParams[$fName]['options']['height'] : 300;
$fld_require = (isset($lParams[$fName.'_required'])) ? 'checked="true"' : '';

//publish checked default
$fld_published = (isset($lParams[$fName.'_published']) 
                    && $lParams[$fName.'_published'] == 'on') ? 'checked="true"' : '';
$fld_published = (!isset($lParams[$fName.'_published'])) ? 'checked="true"' : $fld_published;
//show name checked default
$fld_name_show = (isset($lParams['showName_'.$fName]) 
                    && $lParams['showName_'.$fName] == 'on') ? 'checked="true"' : '';
$fld_name_show = (!isset($lParams['showName_'.$fName])) ? 'checked="true"' : $fld_name_show;

$map_address = (isset($lParams[$fName.'_map_address'])) ? $lParams[$fName.'_map_address'] : '';
$map_country = (isset($lParams[$fName.'_map_country'])) ? $lParams[$fName.'_map_country'] : '';
$map_region = (isset($lParams[$fName.'_map_region'])) ? $lParams[$fName.'_map_region'] : '';
$map_city = (isset($lParams[$fName.'_map_city'])) ? $lParams[$fName.'_map_city'] : '';
$map_zip_code = (isset($lParams[$fName.'_map_zip_code'])) ? $lParams[$fName.'_map_zip_code'] : '';
$map_type = (isset($lParams[$fName.'_map_type'])) ? $lParams[$fName.'_map_type'] : 'ROADMAP';
$map_zoom = (isset($lParams[$fName.'_map_zoom'])) ? $lParams[$fName.'_map_zoom'] : '7';
$map_latitude = (isset($lParams[$fName.'_map_latitude'])) ? $lParams[$fName.'_map_latitude'] : '49.987317349763764';
$map_longitude = (isset($lParams[$fName.'_map_longitude'])) ? $lParams[$fName.'_map_longitude'] : '36.23565673828125';

$code = '<div class="location-address-block">'."\n".
            '<span class="cck-address">{address}</span>'."\n".
            '<span class="cck-country">{country}</span>'."\n".
            '<span class="cck-region">{region}</span>'."\n".
            '<span class="cck-city">{city}</span>'."\n".
            '<span class="cck-zip">{zip}</span>'."\n".
        '</div>';
$map_map_code = (isset($lParams[$fName.'_map_code'])) ? $lParams[$fName.'_map_code'] : $code;

$map_as_show = (isset($lParams[$fName.'_map_as_show'])) ? $lParams[$fName.'_map_as_show'] : 'false';
$map_hide_address = (isset($lParams[$fName.'_map_hide_address'])) ? $lParams[$fName.'_map_hide_address'] : 'false';
$map_hide_map = (isset($lParams[$fName.'_map_hide_map'])) ? $lParams[$fName.'_map_hide_map'] : 'false';
?>
<div id="options-field-<?php echo $fName?>">
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SETUP_LOCATION")?></label>
        <input type="button" onclick="openLocationModal('<?php echo $fName?>')" data-field-name="<?php echo $fName?>" value="Open">
        <input id="<?php echo $fName?>_map_address" type="hidden" name="fi_<?php echo $fName?>_map_address" value="<?php echo $map_address?>">
        <input id="<?php echo $fName?>_map_country" type="hidden" name="fi_<?php echo $fName?>_map_country" value="<?php echo $map_country?>">
        <input id="<?php echo $fName?>_map_region" type="hidden" name="fi_<?php echo $fName?>_map_region" value="<?php echo $map_region?>">
        <input id="<?php echo $fName?>_map_city" type="hidden" name="fi_<?php echo $fName?>_map_city" value="<?php echo $map_city?>">
        <input id="<?php echo $fName?>_map_zip_code" type="hidden" name="fi_<?php echo $fName?>_map_zip_code" value="<?php echo $map_zip_code?>">
        <input id="<?php echo $fName?>_map_type" type="hidden" name="fi_<?php echo $fName?>_map_type" value="<?php echo $map_type?>">
        <input id="<?php echo $fName?>_map_zoom" type="hidden" name="fi_<?php echo $fName?>_map_zoom" value="<?php echo $map_zoom?>">
        <input id="<?php echo $fName?>_map_latitude" type="hidden" name="fi_<?php echo $fName?>_map_latitude" value="<?php echo $map_latitude?>">
        <input id="<?php echo $fName?>_map_longitude" type="hidden" name="fi_<?php echo $fName?>_map_longitude" value="<?php echo $map_longitude?>">
        <input id="<?php echo $fName?>_map_as_show" type="hidden" name="fi_<?php echo $fName?>_map_as_show" value="<?php echo $map_as_show?>">
        <input id="<?php echo $fName?>_map_hide_address" type="hidden" name="fi_<?php echo $fName?>_map_hide_address" value="<?php echo $map_hide_address?>">
        <input id="<?php echo $fName?>_map_hide_map" type="hidden" name="fi_<?php echo $fName?>_map_hide_map" value="<?php echo $map_hide_map?>">
        <textarea style="display:none!important;" id="<?php echo $fName?>_map_code" name="fi_<?php echo $fName?>_map_code"><?php echo $map_map_code?></textarea>
    </div>
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published"
        <?php echo $fld_published?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_PUBLISHED")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_published" <?php echo $fld_published?>>
        
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_REQUIRED")?></label>
        <input class="require-checkbox" type="checkbox" data-field-name="<?php echo $fName?>" name="fi_<?php echo $fName?>_required" <?php echo $fld_require?>>
    </div>
    <div>
        <input type="hidden" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" 
        <?php echo $fld_name_show?> value="0">
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_FIELD_NAME")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_showName_<?php echo $fName?>" <?php echo $fld_name_show?>>
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_ALIAS")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_alias"  value="<?php echo $fld_alias?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SHOW_DESCRIPTION")?></label>
        <input type="checkbox" data-field-name="<?php echo $fName?>" name="fi_description_<?php echo $fName?>" <?php echo $description_show?>>
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_TOOLTIP")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_tooltip"  value="<?php echo $field_tooltip?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_PREFIX")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_prefix"  value="<?php echo $field_prefix?>" >
    </div>
    <div>
        <label><?php echo JText::_("COM_OS_CCK_LABEL_SUFFIX")?></label>
        <input type="text" size="4" name="fi_<?php echo $fName?>_suffix"  value="<?php echo $field_suffix?>" >
    </div>
    <div>
        <label><?php echo JText::_('COM_OS_CCK_LABEL_WIDTH_HEIGHT')?></label>
        <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][width]"  value="<?php echo $width?>" > %
        <input class="width-height" type="text" size="4" name="fi_<?php echo $fName?>[options][height]"  value="<?php echo $height?>" > px
    </div>

</div>