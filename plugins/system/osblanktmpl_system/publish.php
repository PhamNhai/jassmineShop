<?php
/**
* @package OS Blank template
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru)
* @license GNU General Public License version 2 or later;
* @description Ordasoft OS Blank template
*/
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Script file for the plg_system_example plugin    
 */
class plgSystemOsBlankTmpl_systemInstallerScript{
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
            $q->where("element = 'osblanktmpl_system'");
            $q->where("type = 'plugin'", 'AND');
            $q->where("folder = 'system'", 'AND');
            $db->setQuery($q);
            method_exists($db, 'execute') ? $db->execute() : $db->query();
        }
        catch (Exception $e)
        {
           throw $e;
        }
  }
} 


