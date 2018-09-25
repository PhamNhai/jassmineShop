<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 * @version        $Id: menubar.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package        Joomla.Legacy
 * @subpackage    3.0
 * @copyright    Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// Check to ensure this file is within the rest of the framework
//defined('JPATH_BASE') or die();
// Register legacy classes for autoloading
//JLoader::register('JToolbarHelper' , JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');

/**
 * Legacy class, use {@link JToolbarHelper} instead
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage    3.0
 */
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
require_once ($mosConfig_absolute_path . "/administrator/components/com_simplemembership/toolbar_ext.php");
if (!class_exists('mosMenuBar_ext')) {
    class mosMenuBar_ext extends JToolbarHelper_ext {
        /**
         * @deprecated As of Version 1.5
         */
        static function startTable() {
            return;
        }
        /**
         * @deprecated As of Version 1.5
         */
        static function endTable() {
            return;
        }
        /**
         * Default $task has been changed to edit instead of new
         *
         * @deprecated As of Version 1.5
         */
        /*    function addNew($task = 'new', $alt = 'New')
        {
        parent::addNew($task, $alt);
        }
        
        /**
        * Default $task has been changed to edit instead of new
        *
        * @deprecated As of Version 1.5
        */
        /*function addNewX($task = 'new', $alt = 'New')
        {
        parent::addNew($task, $alt);
        }
        
        /**
        * Deprecated
        *
        * @deprecated As of Version 1.5
        */
        static function saveedit() {
            parent::save('saveedit');
        }
    }
}
