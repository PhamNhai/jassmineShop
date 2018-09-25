<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');

class baformsViewEmail extends JViewLegacy
{
	public $email;
	public $items;
	
	public function display ($tpl = null)
	{
		$this->email = $this->get('Email');
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->form = $this->get('Form');
		$this->items = $this->get('Baitems');
		$this->addToolBar();
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
        $doc->addScript(JUri::root(true) . '/media/jui/js/jquery.minicolors.min.js');
        $doc->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js');
        $doc->addStyleSheet(JUri::root(true) . '/media/jui/css/jquery.minicolors.css');
        $doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css');

        parent::display($tpl);
	}

	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('EMAIL_EDIT'), 'star');
        JToolBarHelper::apply('email.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('email.saveAndClose');
		JToolBarHelper::cancel('email.cancel', 'JTOOLBAR_CLOSE');
	}
}