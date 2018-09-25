<?php
/**
* @package   BaGrids
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

abstract class baformsHtmlFilters
{
    public static function stateOptions()
    {
        $options = array();
 
        $options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
        $options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
        $options[] = JHtml::_('select.option', '-2', 'JTRASHED');
 
        return $options;
    }
}