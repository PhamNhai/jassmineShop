<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package simpleMembership
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Anton Getman(ljanton@mail.ru);
 * Homepage: http://www.ordasoft.com
 * @version: 3.1 PRO
 *
 *
 */
include_once (JPATH_SITE . '/components/com_simplemembership/compat.joomla1.5.php');
$mosConfig_live_site = $GLOBALS['mosConfig_live_site'] = substr_replace(JURI::root(), '', -1, 1);
if (!defined('_JLEGACY')) {
    $GLOBALS['path'] = $mosConfig_live_site . "/components/com_simplemembership/images/";
} else {
    $GLOBALS['path'] = $mosConfig_live_site . "/administrator/components/com_simplemembership/images/";
}
$path = $GLOBALS['path'];
if (!class_exists("DMInstallHelper")) {
    class DMInstallHelper {
        static function getComponentId() {
            static $id;
            if (!$id) {
                global $database;
                $database->setQuery("SELECT extension_id FROM #__extensions WHERE `element`='com_simplemembership'");
                $id = $database->loadResult();
            }
            return $id;
        }
        static function setAdminMenuImages() {
            global $database, $path;
            $id = DMInstallHelper::getComponentId();
            // Main mennu
            $database->setQuery("UPDATE #__menu SET img = 'class:dm_component' WHERE component_id=$id");
            $database->query();
            // Submenus
            $submenus = array();
            $submenus[] = array('img' => 'dm_edit_16.png', 'name' => 'Simple Membership','alias'=>'Simple-Membership');
            $submenus[] = array('img' => 'dm_component_16.png','name' => 'Users','alias'=>'Users');
            $submenus[] = array('img' => 'dm_component_16.png','name' => 'Groups','alias'=>'Groups');
            $submenus[] = array('img' => 'dm_component_16.png', 'name' => 'Categories','alias'=>'Categories');
            $submenus[] = array('img' => 'dm_component_16.png', 'name' => 'Settings','alias'=>'Settings');
            $submenus[] = array('img' => 'dm_component_16.png','name' => 'Extensions','alias'=>'Extensions');
            $submenus[] = array('img' => 'dm_component_16.png','name' => 'Orders/Users','alias'=>'Orders/Users');
            $submenus[] = array('img' => 'dm_credits_16.png', 'name' => 'About','alias'=>'About');
            
        
            foreach($submenus as $key=>$submenu) {
                $database->setQuery("UPDATE #__menu SET img = '" . $submenu['img'] . "'" . "\n WHERE component_id=$id AND title = '" . $submenu['name'] . "';");
                $database->query();
                $database->setQuery("UPDATE #__menu SET alias = '" . $submenu['alias'] . "'" . "\n WHERE component_id=$id AND title = '" . $submenu['name'] . "';");
                $database->query();
            }
        }
    }
}
?>
