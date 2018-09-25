<?php
/**
* @package OS Touch Slider.
* @copyright 2016 OrdaSoft.
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/
// No direct access
defined('_JEXEC') or die('Restricted access');

class plgAjaxPlg_os_touchsliderInstallerScript{
  /**
   * Method to run after the plugin install, update, or discover_update actions have completed.
   *
   * @return void
   */
  public function postflight($route, $adapter) {
    // Get a database connector object
        $db = JFactory::getDbo();
    
        try
        {
            // Enable plugin by default
            $q = $db->getQuery(true);
     
            $q->update('#__extensions');
            $q->set(array('enabled = 1', 'ordering = 9999'));
            $q->where("element = 'plg_os_touchslider'");
            $q->where("type = 'plugin'", 'AND');
            $q->where("folder = 'ajax'", 'AND');
            $db->setQuery($q);
            method_exists($db, 'execute') ? $db->execute() : $db->query();
        }
        catch (Exception $e)
        {
           throw $e;
        }
  }
} 


