<?php
/**
* @package   BaGrid
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

class baformsViewIcons extends JViewLegacy
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $input = JFactory::getApplication()->input;
        $doc = JFactory::getDocument();
        $doc->setTitle('Gridbox Editor');
        $doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
        $doc->addScript(JUri::root(true) . '/media/jui/js/bootstrap.min.js');
        $doc->addScript(JURI::root() . 'administrator/components/com_baforms/assets/js/ba-icons.js');
        $doc->addStyleSheet(JURI::root() . 'administrator/components/com_baforms/assets/css/ba-admin.css');
        $doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css');
        parent::display($tpl);
    }
}