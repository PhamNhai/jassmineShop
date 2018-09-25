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
/**
 * This file provides compatibility for simplemembershipLibrary on Joomla! 1.0.x and Joomla! 1.5
 *
 */
if (!defined('_SIMPLEMEMBERSHIP_COMPAT_FILE_LOADED')) {
    define('_SIMPLEMEMBERSHIP_COMPAT_FILE_LOADED', 1);
    if (class_exists('JConfig')) {
        // These are needed when the Joomla! 1.5 legacy plugin is not enabled
        if (!defined('_JLEGACY')) {
            // TODO: determine what else is needed to work without the legacy plugin
            $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
            include_once ($mosConfig_absolute_path . "/components/com_simplemembership/functions.php");
            if (!class_exists('mosHTML')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/html.php");
            }
            if (!class_exists('category')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/category.php");
            }
            if (!class_exists('mosAdminMenus')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/adminmenus.php");
            }
            if (!class_exists('mosTabs')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/classes.php");
            }
            if (!class_exists('mosMenu')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/menu.php");
            }
            if (!class_exists('mosParameters')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/parameters.php");
            }
            if (!class_exists('mosMambotHandler')) {
                include_once ($mosConfig_absolute_path . "/components/com_simplemembership/includes/mambothandler.php");
            }
            if (!class_exists('JComponentHelper') && !isset($mainframe)) {
                include_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
                include_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
                $mainframe = JFactory::getApplication(defined('_VM_IS_BACKEND') ? 'administrator' : 'site');
            }
            //       if(!isset($mainframe)){
            //         $mainframe = JFactory::getApplication();
            //       }
            //       print_r($mainframe);
            //&& !isset($mainframe)
            jimport('joomla.application.component.helper');
            if (class_exists('JComponentHelper')) {
                $usersConfig = JComponentHelper::getParams('com_users');
                $contentConfig = JComponentHelper::getParams('com_content');
                // User registration settings
                $mosConfig_allowUserRegistration = $GLOBALS['mosConfig_allowUserRegistration'] = $usersConfig->get('allowUserRegistration');
                $mosConfig_useractivation = $GLOBALS['mosConfig_useractivation'] = $usersConfig->get('useractivation');
                // TODO: Do we need these? They are set in the template.
                // Icon display settings
                // (hide pdf, etc has been changed to *show* pdf, etc in J! 1.5)
                $mosConfig_icons = $contentConfig->get('show_icons');
                $mosConfig_hidePdf = 1 - intval($contentConfig->get('show_pdf_icon'));
                $mosConfig_hidePrint = 1 - intval($contentConfig->get('show_print_icon'));
                $mosConfig_hideEmail = 1 - intval($contentConfig->get('show_email_icon'));
            }
            $jconfig = new JConfig();
            // Settings from the Joomla! configuration file
            foreach(get_object_vars($jconfig) as $k => $v) {
                $name = 'mosConfig_' . $k;
                $$name = $GLOBALS[$name] = $v;
            }

            $mosConfig_live_site = $GLOBALS['mosConfig_live_site'] = JURI::root() ;
            $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
            $mosConfig_cachepath = $GLOBALS['mosConfig_cachepath'] = JPATH_BASE . DS . 'cache';
            if (!isset($option)) {
                $option = strtolower(JRequest::getCmd('option', 'com_simplemembership'));
            }
            // The selected language
            $lang = JFactory::getLanguage();
            //    $mosConfig_lang = $GLOBALS['mosConfig_lang'] = strtolower( $lang->getBackwardLang() );
            $mosConfig_lang = $GLOBALS['mosConfig_lang'] = strtolower($lang->get('name')) /* JLanguage::getKnownLanguages () */; // for 1.6
            // $database is directly needed by some functions, so we need to create it here.
            $GLOBALS['database'] = $database = JFactory::getDBO();
            // The $my (user) object
            $GLOBALS['my'] = JFactory::getUser();
            // The permissions object
            $acl = JFactory::getACL();
            $GLOBALS['acl'] = $acl;
            // Version information
            $_VERSION = $GLOBALS['_VERSION'] = new JVersion();
            if (!function_exists('sefreltoabs')) {
                function sefRelToAbs($value) {
                    //Need check!!!
                    //Create a file "router.php" inside /components/com_virtuemart/
                    //$router = JRouter::getInstance('virtuemart');
                    //return $router->build($url);
                    // Replace all &amp; with & as the router doesn't understand &amp;
                    $url = str_replace('&amp;', '&', $value);
                    if (substr(strtolower($url), 0, 9) != "index.php") return $url;
                    $uri = JURI::getInstance();
                    $prefix = $uri->toString(array('scheme', 'host', 'port'));
                    return $prefix . JRoute::_($url);
                    return $url;
                }
            }
            if (!function_exists('editorArea')) {
                function editorArea($name, $content, $hiddenField, $width, $height, $col, $row) {
                    jimport('joomla.html.editor');
                    $editor = JFactory::getEditor();
                    echo $editor->display($hiddenField, $content, $width, $height, $col, $row);
                }
            }
            // Load the menu bar class
            JLoader::register('mosMenuBar_ext', $mosConfig_absolute_path . DS . 'administrator' . DS . 'components' . DS . 'com_simplemembership' . DS . 'menubar_ext.php');
            // Load the user class
            JLoader::register('mosUser', $mosConfig_absolute_path . DS . 'plugins' . DS . 'system' . DS . 'legacy' . DS . 'user.php');
        } else {
            // We need these even when the Joomla! 1.5 legacy plugin is enabled
            // We need to set these when we don't enter as a component or module (like in notify.php)
            if (!isset($usersConfig)) {
                $usersConfig = JComponentHelper::getParams('com_users');
            }
            if (!isset($contentConfig)) {
                $contentConfig = JComponentHelper::getParams('com_content');
            }
            // Paths
            // These are in the legacy plugin as globals, but we need them locally too
            $mosConfig_live_site = $GLOBALS['mosConfig_live_site'];
            $mosConfig_cachepath = $GLOBALS['mosConfig_cachepath'];
            // User registration settings
            $mosConfig_allowUserRegistration = $GLOBALS['mosConfig_allowUserRegistration'] = $usersConfig->get('allowUserRegistration');
            $mosConfig_useractivation = $GLOBALS['mosConfig_useractivation'] = $usersConfig->get('useractivation');
            // TODO: Do we need these? They are set in the template.
            // Icon display settings
            // hide pdf, etc has been changed to show pdf, etc in J! 1.5
            $mosConfig_icons = $contentConfig->get('show_icons');
            $mosConfig_hidePdf = 1 - intval($contentConfig->get('show_pdf_icon'));
            $mosConfig_hidePrint = 1 - intval($contentConfig->get('show_print_icon'));
            $mosConfig_hideEmail = 1 - intval($contentConfig->get('show_email_icon'));
            // TODO: Do we still need this in the latest J! 1.5 SVN?
            // Adjust the time offset
            //        $server_time = date( 'O' ) / 100;
            //        $offset = $mosConfig_offset - $server_time;
            //        $GLOBALS['mosConfig_offset'] = $offset;
            // Version information
            $_VERSION = $GLOBALS['_VERSION'];
        }
    }
}
?>
