<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

if (version_compare(JVERSION, '3.0', 'lt')) {
    require_once(JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table.php');
}
jimport("joomla.database.table");
class mosOS_CCK_review extends JTable
{

    /** @var int Primary key */
    var $id = null;
    /** @var int - the house id this lend is assosiated with */
    var $fk_eiid = null;
    /** @var the user of the user who reviewed; can also be null if not set */
    var $user_name = null;
    /** @var the user of the user who reviewed; can also be null if not set */
    var $user_email = null;
    /** @var datetime - date when adding this review */
    var $date = null;
    /** @var comment - the comment to this */
    var $comment = null;
    /** @var titel */
    var $title = null;
    /** @var rating */
    var $rating = 0;
    /** @var boolean */
    var $checked_out = null;
    /** @var time */
    var $checked_out_time = null;

    /**
     * @param database - A database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__os_cck_review', 'id', $db);
        /*$this->mosDBTable( '#__os_cck_review', 'id', $db );*/
    }

    function quoteName($name)
    {
        if (version_compare(JVERSION, "3.0.0", "lt")) {
            $return = $this->_db->NameQuote($name);
        } else {
            $return = $this->_db->quoteName($name);
        }
        return $return;
    }

    /**
     * @return array - name: the string of the user the house is lent to - e-mail: the e-mail address of the user
     */
    function getReviewFrom($userid)
    {
        if ($userid != null && $userid != 0) {
            $this->_db->setQuery("SELECT name, email from #__users where id=$userid");
            $help = $this->_db->loadRow();
            $this->user_name = $help[0];
            $this->user_email = $help[1];
        } else {
            $this->user_name = JText::_("COM_OS_CCK_LABEL_ANONYMOUS");
            $this->user_email = null;
        }
    }


    //function toXML(& $xmlDoc)
    //{
    //
    //    //create and append name element
    //    $retVal = & $xmlDoc->createElement("review");
    //
    //    $user_name = & $xmlDoc->createElement("user_name");
    //    $user_name->appendChild($xmlDoc->createTextNode($this->user_name));
    //    $retVal->appendChild($user_name);
    //
    //    $user_email = & $xmlDoc->createElement("user_email");
    //    $user_email->appendChild($xmlDoc->createTextNode($this->user_email));
    //    $retVal->appendChild($user_email);
    //
    //    $rating = & $xmlDoc->createElement("rating");
    //    $rating->appendChild($xmlDoc->createTextNode($this->rating));
    //    $retVal->appendChild($rating);
    //
    //    $date = & $xmlDoc->createElement("date");
    //    $date->appendChild($xmlDoc->createTextNode($this->date));
    //    $retVal->appendChild($date);
    //
    //    $title = & $xmlDoc->createElement("title");
    //    $title->appendChild($xmlDoc->createCDATASection($this->title));
    //    $retVal->appendChild($title);
    //
    //    $comment = & $xmlDoc->createElement("comment");
    //    $comment->appendChild($xmlDoc->createCDATASection($this->comment));
    //    $retVal->appendChild($comment);
    //
    //    return $retVal;
    //}
    //
    //function toXML2()
    //{
    //
    //    $retVal = "<review>\n";
    //
    //    $retVal .= "<user_name>" . $this->user_name . "</user_name>\n";
    //    $retVal .= "<user_email>" . $this->user_email . "</user_email>\n";
    //    $retVal .= "<rating>" . $this->rating . "</rating>\n";
    //    $retVal .= "<date>" . $this->date . "</date>\n";
    //    $retVal .= "<title><![CDATA[" . $this->title . "]]></title>\n";
    //    $retVal .= "<comment><![CDATA[" . $this->comment . "]]></comment>\n";
    //
    //    $retVal .= "</review>\n";
    //
    //    return $retVal;
    //
    //}

}

