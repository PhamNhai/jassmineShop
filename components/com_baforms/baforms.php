<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// Get an instance of the controller prefixed 
$controller = JControllerLegacy::getInstance('baforms');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->getCmd('task', 'display'));

// Redirect if set by the controller
$controller->redirect();