<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package simpleMembership
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Anton Getman(ljanton@mail.ru);
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 PRO
 *
 *
 */
/**
 * simplemembership database table class
 */
class mos_alUser extends JTable {
    var $id = NULL;
    // int(11) NOT NULL auto_increment,
    var $fk_users_id = NULL;
    //int(11) default NULL,
    var $name = NULL;
    //` varchar(255) NOT NULL default '',
    var $username = NULL;
    //` varchar(150) NOT NULL default '',
    var $email = NULL;
    //` varchar(100) NOT NULL default '',
    var $block = NULL;
    //` tinyint(4) NOT NULL default '0',
    var $last_approved = NULL;
    //` date NOT NULL,
    var $current_gid = NULL;
    //` int(11) NOT NULL,
    var $current_gname = NULL;
    //` varchar(255) NOT NULL,
    var $want_gname= NULL;
    //` varchar(100) NOT NULL,
    var $want_gid = NULL;
    //  int(11) NOT NULL,
    var $approved = NULL;
    //` tinyint(4) NOT NULL,
    var $expire_date = NULL;
    //` datetime NOT NULL,
    
    /**
     * @param database - A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__simplemembership_users', 'id', $db);
    }
    // overloaded check function
    function check() {
        return true;
    }
    /**
     * @param string - Target search string
     * not used at the moment
     */
    function search($text, $state = '', $sectionPrefix = '') {
        $text = trim($text);
        /** if ($text == '') { **/
        return array();
    }
}
?>
