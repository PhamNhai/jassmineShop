<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

class BaformsControllerEmails extends JControllerAdmin
{
    public function getModel($name = 'email', $prefix = 'baformsModel', $config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
	}
}