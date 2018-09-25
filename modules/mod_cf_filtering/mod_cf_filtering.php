<?php
/**
 * @package		customfilters
 * @subpackage	mod_cf_filtering
 * @copyright	Copyright (C) 2012-2017 breakdesigns.net . All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

jimport('joomla.language.helper');

// Include the syndicate functions only once
require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customfilters' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'tools.php';
require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customfilters' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'input.php';
require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customfilters' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'output.php';

if (! class_exists('VmCompatibility')) {
    require (JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_customfilters' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'vmcompatibility.php');
}
    // get the helper classes
require_once dirname(__FILE__) . '/helper.php';
require_once dirname(__FILE__) . '/optionsHelper.php';
require_once dirname(__FILE__) . '/renderHelper.php';

// load the Virtuemart configuration
require_once (JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_virtuemart' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'config.php');
VmConfig::loadConfig();

JText::script('MOD_CF_FILTERING_INVALID_CHARACTER');
JText::script('MOD_CF_FILTERING_PRICE_MIN_PRICE_CANNOT_EXCEED_MAX_PRICE');
JText::script('MOD_CF_FILTERING_MIN_CHARACTERS_LIMIT');

$jlang = JFactory::getLanguage();
$jlang->load('com_customfilters');
$jlang->load('com_virtuemart');

// Set the current language code
if (! defined('VMLANG')) {
    $languages = JLanguageHelper::getLanguages('lang_code');
    $siteLang = $jlang->getTag();
    $siteLang = strtolower(strtr($siteLang, '-', '_'));
} else {
    $siteLang = VMLANG;
}

if (! defined('JLANGPRFX')) {
    define('JLANGPRFX', $siteLang);
}

// Set the shop's default language
$shop_default_lang = VmConfig::$defaultLang;
if (! defined('VM_SHOP_LANG_PRFX')) {
    define('VM_SHOP_LANG_PRFX', $shop_default_lang);
}


$modObj = new ModCfFilteringHelper();
$filters_render_array = $modObj->getFilters($params, $module);
$filter_headers_array = $modObj->getFltHeaders();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// calls the layout which will be used
// template overrides can be done
require (JModuleHelper::getLayoutPath('mod_cf_filtering', $params->get('layout', 'default')));
