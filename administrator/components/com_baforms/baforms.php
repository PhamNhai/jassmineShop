<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_baforms')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('baformsHelper', dirname(__FILE__) . '/helpers/baforms.php');

JHtml::addIncludePath(dirname(__FILE__) . '/helpers/html');

// Get an instance of the controller prefixed
$controller = JControllerLegacy::getInstance('baforms');
 
// Perform the Request task and Execute request task
$controller->execute(JFactory::getApplication()->input->getCmd('task', 'display'));
 
// Redirect if set by the controller
$controller->redirect();