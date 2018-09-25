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

class os_cckFile extends JTable
{

    /** @var name for define second table */
    var $fid = null;
    var $filename = null;
    var $filepath = null;
    var $filemime = null;
    var $filesize = null;
    var $status = null;
    var $timestamp = null;

    function __construct(&$db)
    {
        parent::__construct('#__os_cck_files', 'fid', $db);
    }

    function delete($pk = NULL)
    {

        $k = $this->_tbl_key;
        if ($oid) {
            $this->$k = $oid;
        }

        $filepath = JPATH_SITE . $this->filepath;
        $pathinfo = pathinfo($filepath);
        $files_dir = JPath::clean($pathinfo['dirname']);
        $files =  array();
        if(trim($pathinfo['filename']) != "" ) $files = JFolder::files($files_dir, "^{$pathinfo['filename']}.*\.{$pathinfo['extension']}$", false, true);
        foreach ($files as $file) {
            unlink($file);
        }

        if (version_compare(JVERSION, "3.0.0", "ge")) {

            $pk = (is_null($oid)) ? $this->$k : $oid;

            // If no primary key is given, return false.
            if ($pk === null) {
                throw new UnexpectedValueException('Null primary key not allowed.');
            }

            // Delete the row by primary key.
            $query = $this->_db->getQuery(true);
            $query->delete();
            $query->from($this->_tbl);
            $query->where($this->_tbl_key . ' = ' . $this->_db->quote($pk));
            $this->_db->setQuery($query);

            // Check for a database error.
            $this->_db->execute();

            $ret = true;
        } else {
            $ret = parent::delete($this->fid);
        }

        return $ret;
    }

}

?>
