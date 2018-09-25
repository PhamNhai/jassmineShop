<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_osgalleryInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // $parent is the class calling this method

    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        // delete folder with images start
        $db = JFactory::getDBO();
        if(!function_exists('removeGalleriesDirectory')){
            function removeGalleriesDirectory($dir) {
              if ($objs = glob($dir."/*")) {
                foreach($objs as $obj) {
                  is_dir($obj) ? removeGalleriesDirectory($obj) : unlink($obj);
                }
              }
              @rmdir($dir);
            } 
        }
        $dir = JPATH_ROOT . '/images/com_osgallery/';
        removeGalleriesDirectory($dir);
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        // $parent is the class calling this method
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        $db = JFactory::getDBO();
        $db->setQuery("DELETE FROM #__update_sites WHERE name = 'Gallery`s Update'");
        $db->query();
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
    }

}
