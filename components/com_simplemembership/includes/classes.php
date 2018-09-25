<?php
/**
 * Legacy class, replaced by full MVC implementation.  See {@link JController}
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage	3.0
 * This file provides compatibility for simplemembership Library on Joomla 3.0! 
 *
 */
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
JLoader::register('JPaneTabs', JPATH_LIBRARIES . DS . 'joomla' . DS . 'html' . DS . 'pane.php');
if (!class_exists('mosAbstractTasker')) {
    class mosAbstractTasker {
        function __construct() {
            jexit('mosAbstractTasker deprecated, use JController instead');
        }
    }
}
/**
 * Legacy class, removed
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage    3.0
 */
if (!class_exists('mosEmpty')) {
    class mosEmpty {
        function def($key, $value = '') {
            return 1;
        }
        function get($key, $default = '') {
            return 1;
        }
    }
}
/**
 * Legacy class, removed
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage    3.0
 */
if (!class_exists('MENU_Default')) {
    class MENU_Default {
        function __construct() {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::addNew();
            JToolBarHelper::editList();
            JToolBarHelper::deleteList();
            JToolBarHelper::spacer();
        }
    }
}
/**
 * Legacy class, use {@link JPanel} instead
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage    3.0
 */
if (!class_exists('mosTabs') && version_compare(JVERSION, '3.0.0', 'lt')) {
    class mosTabs extends JPaneTabs {
        var $useCookies = false;
        function __construct($useCookies, $xhtml = null) {
            parent::__construct(array('useCookies' => $useCookies));
        }
        function startTab($tabText, $paneid) {
            echo $this->startPanel($tabText, $paneid);
        }
        function endTab() {
            echo $this->endPanel();
        }
        function startPane($tabText) {
            echo parent::startPane($tabText);
        }
        function endPane() {
            echo parent::endPane();
        }
    }
}
