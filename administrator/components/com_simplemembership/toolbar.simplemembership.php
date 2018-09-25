<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package simpleMembership
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Anton Getman(ljanton@mail.ru);
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 PRO
 *
 *
 */
//defined('_VM_TOOLBAR_LOADED' ) or define('_VM_TOOLBAR_LOADED', 1 );
$mainframe = $GLOBALS['mainframe'] = JFactory::getApplication(); // for 1.6
if (stristr($_SERVER['PHP_SELF'], 'administrator')) {
    @define('_VM_IS_BACKEND', '1');
}

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;

include_once ($mosConfig_absolute_path . '/components/com_simplemembership/compat.joomla1.5.php');
// ensure this file is being included by a parent file
$path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$section = mosGetParam($_REQUEST, 'section', '');
require_once ($mosConfig_absolute_path . '/administrator/components/com_simplemembership/toolbar_ext.php');
require_once ($mosConfig_absolute_path . '/administrator/components/com_simplemembership/toolbar.simplemembership.html.php');
require_once ($mosConfig_absolute_path . '/components/com_simplemembership/functions.php');
if (version_compare(JVERSION, '1.0.0', 'ge')) $GLOBALS['task'] = $task = mosGetParam($_REQUEST, 'task', '');
if (!isset($task) || empty($task)) $task = '';
if (isset($_REQUEST['section'])) { // section
    $section = $_REQUEST['section'];
}
if (isset($section) && $section == 'group') {
    switch ($task) {
        case "add":
            menucat::NEW_CATEGORY();
            JToolBarHelper::title(JText::_("Simple Membership: Add Category"));
        break;
        case "edit":
            menucat::EDIT_CATEGORY();
            JToolBarHelper::title(JText::_("Simple Membership: Edit Category"));
        break;
        case "add_acl_group":
            menusimplemembership::SAVE_NEWACL();
            JToolBarHelper::title(JText::_("Simple Membership: Add Joomla group"));
        break;
        case "del_acl_group_form":
            menusimplemembership::DEL_ACL();
            settingsLittleThings::addSubmenu("Groups");
            JToolBarHelper::title(JText::_("Simple Membership: Groups"));
        break;
       
        default:
            menucat::SHOW_CATEGORIES();
            settingsLittleThings::addSubmenu("Groups");
            JToolBarHelper::title(JText::_("Simple Membership: Groups"));
        break;
    }
} else {
    switch ($task) {
        case "add":
            menusimplemembership::MENU_SAVE_BACKEND();
            settingsLittleThings::addSubmenu("Groups");
            JToolBarHelper::title(JText::_("Simple Membership: Groups"));
        break;
        case "add_user":
            menusimplemembership::MENU_EDITUSER();
            settingsLittleThings::addSubmenu("Groups ");
            JToolBarHelper::title(JText::_("Simple Membership: Groups"));
        break;
        case "edit_user":
            menusimplemembership::MENU_EDITUSER();
            settingsLittleThings::addSubmenu("Groups ");
            JToolBarHelper::title(JText::_("Simple Membership: Groups"));
        break;
        case "config":
            menusimplemembership::MENU_CONFIG();
            settingsLittleThings::addSubmenu("Settings");
            JToolBarHelper::title(JText::_("Simple Membership: Settings"));
        break;
        case "config_save":
            menusimplemembership::MENU_CONFIG();
            settingsLittleThings::addSubmenu("Settings");
            JToolBarHelper::title(JText::_("Simple Membership: Settings"));
        break;
        case "about":
            menusimplemembership::MENU_ABOUT();
            settingsLittleThings::addSubmenu("About");
            JToolBarHelper::title(JText::_("Simple Membership: About"));
        break;
        case "override":
            menusimplemembership::MENU_OVERRIDE();
            settingsLittleThings::addSubmenu("Override");
            JToolBarHelper::title(JText::_("Simple Membership: Orders"));
        break;
        case "orders":
            menusimplemembership::MENU_ORDERS();
            settingsLittleThings::addSubmenu("Orders");
            JToolBarHelper::title(JText::_("Simple Membership: Orders"));
        break;
        default:
            menusimplemembership::MENU_DEFAULT();
            settingsLittleThings::addSubmenu("Users");
            JToolBarHelper::title(JText::_("Simple Membership: Users"));
        break;
    }
} //else

?>
