<?php
/**
* @package OS Touch Slider.
* @copyright 2016 OrdaSoft.
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/
// No direct access to this file
defined('_JEXEC') or die;

class mod_os_touchsliderInstallerScript{
  /**
   * Method to install the extension
   * $parent is the class calling this method
   * @return void
   */
  function install($parent) {
      //echo '<p>The module has been installed</p>';                
  }

  /**
   * Method to uninstall the extension
   * $parent is the class calling this method
   * @return void
   */
  function uninstall($parent) {
    echo '<p>The module has been uninstalled</p>';                
    // delete folder with images start
    $db = JFactory::getDBO();

    $db->setQuery("SELECT id FROM #__modules WHERE `module` = 'mod_os_touchslider'");
    $ids = $db->loadColumn(); // array of modules ids
    
    if(!function_exists('removeDirectoryOS_TouchSlider')){
      function removeDirectoryOS_TouchSlider($dir) {
        if ($objs = glob($dir."/*")) {
          foreach($objs as $obj) {
            is_dir($obj) ? removeDirectoryOS_TouchSlider($obj) : unlink($obj);
          }
        }
        @rmdir($dir);
      } 
    }
    foreach($ids as $id) {
      $dir = JPATH_ROOT . '/images/os_touchslider_'.$id;
      removeDirectoryOS_TouchSlider($dir);
    }               
  }

  /**
   * Method to update the extension
   * $parent is the class calling this method
   * @return void
   */
  function update($parent) {
      //echo '<p>The module has been updated to version' . $parent->get('manifest')->version) . '</p>';
  }

  /**
   * Method to run before an install/update/uninstall method
   * $parent is the class calling this method
   * $type is the type of change (install, update or discover_install)
   * @return void
   */
  function preflight($type, $parent) {
      //echo '<p>Anything here happens before the installation/update/uninstallation of the module</p>';
  }

  /**
   * Method to run after an install/update/uninstall method
   * $parent is the class calling this method
   * $type is the type of change (install, update or discover_install)
   * @return void
   */
  function postflight($type, $parent) {
      //echo '<p>Anything here happens after the installation/update/uninstallation of the module</p>';
  }
}