<?php
/**
 * Legacy class, use {@link JTableCategory} instead
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage	3.0
 * This file provides compatibility for simplemembership Library on Joomla 3.0! 
 *
 */
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
// Register legacy classes for autoloading
JLoader::register('JTableCategory', JPATH_LIBRARIES . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'category.php');
if (!class_exists('mosCategory')) {
    class mosCategory extends JTableCategory {
        /**
         * Constructor
         */
        function __construct(&$db) {
            parent::__construct($db);
        }
        function mosCategory(&$db) {
            parent::__construct($db);
        }
        /**
         * Legacy Method, use {@link JTable::reorder()} instead
         * @deprecated As of 1.5
         */
        function updateOrder($where = '') {
            return $this->reorder($where);
        }
        /**
         * Legacy Method, use {@link JTable::publish()} instead
         * @deprecated As of 1.0.3
         */
        function publish_array($cid = null, $publish = 1, $user_id = 0) {
            $this->publish($cid, $publish, $user_id);
        }
    }
}
