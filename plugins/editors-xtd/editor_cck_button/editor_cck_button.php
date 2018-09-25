<?php
/**
* @version 1.1
* @package OS CCK
* @copyright 2015 OrdaSoft
* @author 2015 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
 // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgButtonEditor_cck_button extends JPlugin {

    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }
    function onDisplay($name)
    {
      
        $js = "
        function cckLayoutInsert(tag) {
                        jInsertEditorText(''+tag+'', '" . $name . "');
                        SqueezeBox.close();
        }";
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);
                
        $button = new JObject;

        if (JFactory::getApplication()->isSite() && (!isset($this->params->enable_frontend)
                                                    || !$this->params->enable_frontend)){
            return $button;
        }

        $icon = 'color-palette';
        $link = 'index.php?option=com_os_cck&task=select_data_for_editor_button&tmpl=component';

        $button->modal   = true;
        $button->class   = 'btn';
        $button->link    = $link;
        $button->text    = 'Add CCK Layout';
        $button->name    = $icon;
        $button->options = "{handler: 'iframe', size: {x:window.getSize().x-100, y: window.getSize().y-100}}";

        return $button;
    }
}
