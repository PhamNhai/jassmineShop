<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;


class PlgButtonBaforms extends JPlugin
{
	public function onDisplay($name)
	{
		$js = "
		function formsSelectForm(id) {
				jInsertEditorText('[forms ID='+id+']', '" . $name . "');
				SqueezeBox.close();
                jModalClose();
		  }";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		JHtml::_('behavior.modal');

		$link = 'index.php?option=com_baforms&amp;view=forms&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$button = new JObject;
		$button->set('modal', true);
		$button->set('class', 'btn');
		$button->set('link', $link);
		$button->set('text', 'Forms');
		$button->set('name', 'star');
		$button->set('options', "{handler: 'iframe', size: {x: 740, y: 545}}");

		return $button;
	}
}
