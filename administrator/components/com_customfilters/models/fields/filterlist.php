<?php
/**
 * @package customfilters
 * @version $Id: fields/filterlist.php  2012-6-14 sakisTerzis $
 * @author Sakis Terzis (sakis@breakDesigns.net)
 * @copyright	Copyright (C) 2012-2017 breakDesigns.net. All rights reserved
 * @license	GNU/GPL v2
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.access.access');
jimport('joomla.form.formfield');
JHtml::_('behavior.framework', true);

/**
 *
 * Class that generates a filter list
 * @author Sakis Terzis
 */
Class JFormFieldFilterlist extends JFormField{
	/**
	 * Method to get the field input markup.
	 *
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$script="window.addEvent('domready',function(){var filterlist=document.id('cf_filterlist');
		var mysortable=new Sortables(filterlist, {revert:{duration: 500,transition: 'elastic:out'}});

		mysortable.addEvent('complete',function(el){
		document.id('cf_filterlist_hidden').value=JSON.encode(mysortable.serialize());
		});
	});";


		$oldLabelStrings=array(
	'category_flt'=>'virtuemart_category_id',
	'manuf_flt'=>'virtuemart_manufacturer_id',
	'price_flt'=>'price',
	'custom_flt'=>'custom_f');

		$labelStrings=array(
	'q'=>JText::_('COM_MODULES_MOD_CF_FILTERING_KEYWORD_FIELDSET_LABEL'),
	'virtuemart_category_id'=>JText::_('COM_MODULES_MOD_CF_FILTERING_CATEGORIES_FIELDSET_LABEL'),
	'virtuemart_manufacturer_id'=>JText::_('COM_MODULES_MOD_CF_FILTERING_MANUFACTURERS_FIELDSET_LABEL'),
	'price'=>JText::_('COM_MODULES_MOD_CF_FILTERING_PRICE_FIELDSET_LABEL'),
	'custom_f'=>JText::_('COM_MODULES_MOD_CF_FILTERING_CUSTOM_FILTERS_FIELDSET_LABEL'));

		$html='';
		if(!empty($this->value)){
			$value_array_temp=json_decode(str_replace("'",'"' ,$this->value));
			$value_array_temp=(array)$value_array_temp;
			if(count($value_array_temp)==count($labelStrings)){
				if(!in_array('price', $value_array_temp)){//change the price format
					$index=array_search('product_price', $value_array_temp);
					if($index!==false){
						unset($value_array_temp[$index]);
						$value_array_temp[$index]='price';
					}

				}

				if(!in_array('virtuemart_category_id', $value_array_temp)){//old format
					foreach($value_array_temp as &$val){
						$val=$oldLabelStrings[$val];
					}
				}
				ksort($value_array_temp);
			}
			else $value_array_temp=array_keys($labelStrings);
			$value_array=$value_array_temp;
			$value_json=str_replace('"',"'",json_encode($value_array));

		}
		if(empty($value_array)){
			$value_array=array_keys($labelStrings);
			$value_json=str_replace('"',"'",json_encode($value_array));
		}

		if(is_array($value_array) && !empty($value_array)){
			$attr = '';
			// Initialize some field attributes.
			$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
			$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

			$document=JFactory::getDocument();
			$document->addScriptDeclaration($script);
			$document->addStyleSheet(JURI::root().'modules/mod_cf_filtering/assets/style_backend.css');

			$html='<ul id="cf_filterlist" style="font-size:12px;" class="cf_sorting_list">';
			foreach($value_array as $key){
				$html.='<li style="padding-left:8px;" id="'.$key.'">'.$labelStrings[$key].'</li>';
			}
			$html.='</ul>
			<input type="hidden" id="cf_filterlist_hidden" name="'.$this->name.'" value="'.$value_json.'"/>';
			$language=JFactory::getLanguage();
			$language->load('mod_cf_filtering');
		}
    		return $html;
	}

}
