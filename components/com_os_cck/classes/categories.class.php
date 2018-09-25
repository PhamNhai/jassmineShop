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


/**
 * Book Main Categories table class
 */

class os_cckCategory extends JTable
{

    /** @var int Primary key */
    var $cid = null;
    /** @var int */

    var $parent_id = null;
    /** @var int */
    /** @var int */
    //var $sid=null;
    /** @var string */

    var $asset_id = null;
    /** @var datetime */

    var $title = null;
    /** @var int */

    var $name = null;
    /** @var int */

    var $alias = null;
    /** @var int */

    var $image = null;
    /** @var boolean */

    var $section = null;
    /** @var time */

    var $image_position = null;
    /** @var int */

    var $description = null;
    /** @var varchar(200) */

    var $published = null;
    /** @var varchar(200) */

    var $checked_out = null;
    /** @var varchar(250) */

    var $checked_out_time = null;
    /** @var int */

    var $editor = null;
    /** @var varchar(200) */

    var $ordering = null;
    /** @var varchar(200) */

    var $access = null;
    /** @var varchar(300) */

    var $count = null;
    /** @var int */

    var $params = null;
    /** @var text */

    var $params2 = null;
    /** @var text */

    var $rent_request = null;
    /** @var tinyint(1) */
    var $buy_request = null;

    /** @var tinyint(1) */


    function __construct(&$db)
    {
        parent::__construct('#__os_cck_categories', 'cid', $db);
        $this->access = (int)JFactory::getConfig()->get('access');
    }

    function updateOrder($where = '')
    {
        return $this->reorder($where);
    }

    function publish_array($cid = null, $publish = 1, $user_id = 0)
    {
        $this->publish($cid, $publish, $user_id);
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

    function getEntities()
    {
        $query = "SELECT cei.fk_eid FROM #__os_cck_categories_connect AS ccc "
            . " LEFT JOIN #__os_cck_entity_instance AS cei ON ccc.fk_eiid=cei.eiid "
            . " WHERE ccc.fk_cid='" . $this->cid . "' ";
        $this->_db->setQuery($query);
        $eids = (version_compare(JVERSION, "3.0.0", "lt")) ? $this->_db->loadResultArray() : $this->_db->loadColumn();
        $return = array();
        if (count($eids) > 0) {
            foreach ($eids as $eid) {
                $entity = new os_cckEntity($this->_db);
                $entity->load($eid);
                $return[] = $entity;
            }
        }
        return $return;
    }
}

