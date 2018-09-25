<?php

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
class mosOS_CCK_buying_request extends JTable
{

    /** @var int Primary key */
    var $id = null;
    /** @var int - the entety instance id this rent is assosiated with */
    var $fk_eiid = null;
    /** @var int - the entety inctance id this rent is assosiated with */
    var $fk_userid = null;
    /** @var datetime - when the entety instance realy was/is returned */
    var $buying_request = null;
    /** @var boolean */
    var $checked_out = null;
    /** @var time */
    var $checked_out_time = null;
    /** @var string - the user */
    var $customer_name = null;
    /** @var string – the email */
    var $customer_email = null;
    /** @var string – the phone */
    var $customer_phone = null;
    /** @var string – the comment */
    var $customer_comment = null;

    /** @var int */
    var $status = 0;

    /**
     * @param database - A database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__os_cck_buying_request', 'id', $db);
    }

    /**
     * @return array – name: the string of the user the entety instance is lent to - e-mail: the e-mail address of the user
     */
    function decline()
    {
        if ($this->id == null) {
            return "Method called on a non instant object";
        }
        $this->_db->setQuery("DELETE FROM #__os_cck_buying_request"
            . "\nWHERE id=$this->id"
        );
        if (!$this->_db->query()) {
            return $this->_db->getErrorMsg();
        }
    }
}
