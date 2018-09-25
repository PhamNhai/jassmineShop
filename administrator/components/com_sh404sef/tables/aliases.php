<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date        2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC'))
{
	die();
}

jimport('joomla.database.table');

class Sh404sefTableAliases extends JTable
{
	/**
	 * Current row id
	 *
	 * @var   integer
	 * @access  public
	 */
	public $id = 0;

	/**
	 * Non-sef url associated with the alias
	 *
	 * @var   string
	 * @access  public
	 */
	public $newurl = '';

	/**
	 * Alias to the non-sef url associated with the alias
	 *
	 * @var   string
	 * @access  public
	 */
	public $alias = '';

	/**
	 * Type of alias
	 *
	 * Can be
	 *   Sh404sefHelperGeneral::COM_SH404SEF_URLTYPE_ALIAS (=0) for a regular alias
	 *   Sh404sefHelperGeneral::COM_SH404SEF_URLTYPE_PAGEID (=1) for an auto created pageid
	 *
	 * @var   integer
	 * @access  public
	 */
	public $type = Sh404sefHelperGeneral::COM_SH404SEF_URLTYPE_ALIAS;

	/**
	 * Object constructor
	 *
	 * @access public
	 * @param object $db JDatabase object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sh404sef_aliases', 'id', $db);
	}

	/**
	 * Pre-save checks
	 */
	public function check()
	{
		// condition : we can't have 2 records with same alias. So if user
		// wants to save a record with a pre-existing alias, this has to be
		// for the same non-sef url found in the existing record, or else
		// that's an error
		// if existing,
		if (!empty($this->id))
		{
			return true;
		}

		// if new record, check there is no record with same pageid
		// but not same non-sef
		$count = ShlDbHelper::count($this->_tbl, '*', $this->_db->quoteName('alias') . ' = ? and ' . $this->_db->quoteName('newurl') . ' <> ?', array($this->alias, $this->newurl));
		if (!empty($count))
		{
			$this->setError(JText::_('Cannot save alias : this alias already exists in the database.'));
			return false;
		}

		// alias target must be a non-sef
		if (JString::substr($this->newurl, 0, 9) != 'index.php')
		{
			$this->setError(JText::_('COM_SH404SEF_BADURL'));
			return false;
		}

		return true;
	}

}
