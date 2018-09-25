<?php
/*
 * Simplemembership Authentication plugin
 * @copyright 2012 OrdaSoft
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version 3.0 pro
 * @package Simplemembership
 * @subpackage	Authentication.simplemembership
*/

/* ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

$GLOBALS['mainframe'] = $mainframe = JFactory::getApplication();
$GLOBALS['document'] = $document = JFactory::getDocument();
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
require_once ($mosConfig_absolute_path . "/components/com_simplemembership/compat.joomla1.5.php");

class plgAuthenticationplg_simplemembership_authentication extends JPlugin
{
    function onUserAuthenticate($credentials, $options, &$response)
    {
        $response->type = 'Joomla';
        // Joomla does not like blank passwords
        if (empty($credentials['password'])) {
                $response->status = JAuthentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');
                return false;
        }

        // Initialise variables.
        $conditions = '';

        // Get a database object
        $db		= JFactory::getDbo();
        $query	= $db->getQuery(true);

        $query="SELECT id, password FROM #__users WHERE username=". $db->Quote($credentials['username'])."";
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result) {
            $parts	= explode(':', $result->password);
            $crypt	= $parts[0];
            $salt	= @$parts[1];
            $testcrypt = JUserHelper::getCryptedPassword($credentials['password'], $salt);

            if ($crypt == $testcrypt) {
                $user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
                $response->email = $user->email;
                $response->fullname = $user->name;
                if (JFactory::getApplication()->isAdmin()) {
                        $response->language = $user->getParam('admin_language');
                }
                else {
                        $response->language = $user->getParam('language');
                }
                $response->status = JAuthentication::STATUS_SUCCESS;
                $response->error_message = '';
                require_once(JPATH_SITE.'/components/com_simplemembership/syncexpire.php');
                check_users($user);
            } else {
                $response->status = JAuthentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_INVALID_PASS');
            }
        } else {
            $response->status = JAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        }
    }
    
    function onUserAfterLogin($options){
//         require_once(JPATH_SITE.'/components/com_simplemembership/simplemembership.php');
//         check_users();
        require_once (JPATH_SITE.'/components/com_simplemembership/syncexpire.php');
        check_users();

    }
}
