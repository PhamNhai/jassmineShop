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

require_once(JPATH_SITE . "/administrator/components/com_os_cck/toolbar_ext.php");
jimport('joomla.application.component.view');


class mosMenuBar_cckext extends JToolbarHelper_cckext
{
    /**
     * @deprecated As of Version 1.5
     */
    static function startTable()
    {
        return;
    }

    /**
     * @deprecated As of Version 1.5
     */
    static function endTable()
    {
        return;
    }

    /**
     * Default $task has been changed to edit instead of new
     *
     * @deprecated As of Version 1.5
     */
// 	static function addNew($task = 'new', $alt = 'New')
// 	{
// 		parent::addNew($task, $alt);
// 	}
// 
// 	/**
// 	 * Default $task has been changed to edit instead of new
// 	 *
// 	 * @deprecated As of Version 1.5
// 	 */
// 	static function addNewX($task = 'new', $alt = 'New')
// 	{
// 		parent::addNew($task, $alt);
// 	}

    /**
     * Deprecated
     *
     * @deprecated As of Version 1.5
     */
    static function saveedit()
    {
        parent::save('saveedit');
    }

}
