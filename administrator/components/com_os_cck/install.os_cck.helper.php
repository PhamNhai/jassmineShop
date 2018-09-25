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


//include_once($mosConfig_live_site . '/components/com_os_cck/compat.joomla1.5.php');

if (!defined('_JLEGACY')) {
    $GLOBALS['path'] = $mosConfig_live_site . "/components/com_os_cck/images/";
} else {
    $GLOBALS['path'] = $mosConfig_live_site . "/administrator/components/com_os_cck/images/";
}
$path = $GLOBALS['path'];
class DMInstallHelper
{
    function getComponentId()
    {
        static $id;
//OS_CCK
        if (!$id) {
            global $db;
            $db->setQuery("SELECT id FROM #__components WHERE name= 'OS_CCK'");
            $id = $db->loadResult();
        }
        return $id;
    }


    function setAdminMenuImages()
    {
        global $db, $path;

        $id = DMInstallHelper::getComponentId();
        $test = DMInstallHelper::getComponentId();

        // Main mennu
        $db->setQuery("UPDATE #__components SET admin_menu_img = '" . $path . "dm_component_16.png' WHERE id=$id");
        $db->query();

        // Submenus
        $submenus = array();
        $submenus[] = array('image' => $path . 'dm_edit_16.png',      'name' => 'Instances');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Categories');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Manage Entities');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Manage Views and Layouts');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Submissions');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Rent Instances');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Users Rent History');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Buy Instances');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Review Instances');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Orders');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Import');
        $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Settings');
        $submenus[] = array('image' => $path . 'dm_credits_16.png',   'name' => 'About');
        foreach ($submenus as $submenu) {
            $update = "UPDATE #__components SET admin_menu_img = '" . $submenu['image'] . "'" .
                "\n WHERE parent=$id AND name = '" . $submenu['name'] . "' ";

            $db->setQuery($update);
            $db->query();
        }
    }
}

?>
