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

// JToolBar extends Object
require_once(JPATH_SITE . "/administrator/includes/toolbar.php");


class JToolBarHelper_cckext extends JToolBarHelper
{
    static function NewSave($task = '', $page, $icon = '', $iconOver = '', $alt = '', $listSelect = true, $formName = "adminForm")
    {
        $bar = JToolBar::getInstance('toolbar');

        if (empty($task)) {
            $image_name = uniqid("img_");
        } else {
            $image_name = $task;
        }

        if ($icon && $iconOver) {
            $bar->appendButton('NewSave', "<a class=\"toolbar\"
                                  onmouseout=\"MM_swapImgRestore();\"
                                  onmouseover=\"MM_swapImage('$image_name','','$iconOver',1);\">
				  <img name=\"$image_name\" title = \"$alt\" src=\"$icon\" alt=\"$alt\" border=\"0\" 
                                                                                           align=\"middle\"/>
						&nbsp;<br/>$alt</a>");
        } else {
            // The button is just a link then!
            $bar->appendButton('Custom', "<a class=\"toolbar\" title = \"$alt\" href=\"{$href}\">&nbsp;$alt<a>");
        }
    }

    static function NewCustom_I($task = '', $page, $icon = '', $iconOver = '', $text = '', $alt = '', $listSelect = true, $formName = "adminForm")
    {
        global $VM_LANG;

        $bar = JToolBar::getInstance('toolbar');


        if ($listSelect) {
            if (empty($func))
                $href = "javascript:if (document.adminForm.import_type.value == 0){ alert('$text');}
                                                    else{submitbutton('$task')}";
            else
                $href = "javascript:submitbutton('$task')";
        } else {
            $href = "javascript:submitbutton('$task')";
        }
        if (empty($task)) {
            $image_name = uniqid("img_");
        } else {
            $image_name = $task;
        }
        if ($icon && $iconOver) {
            $bar->appendButton('Custom', "<a class=\"toolbar\" href=\"$href\" onmouseout=\"MM_swapImgRestore();\"  onmouseover=\"MM_swapImage('$image_name','','$iconOver',1);\">
            <img name=\"$image_name\"title = \"$alt\" src=\"$icon\" alt=\"$alt\" border=\"0\" align=\"middle\" />
            &nbsp;<br/>$alt</a>");

        } else {
            // The button is just a link then!
            $bar->appendButton('Custom', "<a class=\"toolbar\" title = \"$alt\" href=\"$href\">&nbsp;$alt</a>");
        }
    }


    static function NewCustom_E($task = '', $page, $icon = '', $iconOver = '', $text = '', $alt = '', $listSelect = true, $formName = "adminForm")
    {
        global $VM_LANG;

        $bar = JToolBar::getInstance('toolbar');


        if ($listSelect) {
            if (empty($func))
                $href = "javascript:if (document.adminForm.export_type.value == 0){ alert('$text');}
                                                    else{submitbutton('$task')}";
            else
                $href = "javascript:submitbutton('$task')";
        } else {
            $href = "javascript:submitbutton('$task')";
        }
        if (empty($task)) {
            $image_name = uniqid("img_");
        } else {
            $image_name = $task;
        }
        if ($icon && $iconOver) {
            $bar->appendButton('Custom', "<a class=\"toolbar\" href=\"$href\" onmouseout=\"MM_swapImgRestore();\"  onmouseover=\"MM_swapImage('$image_name','','$iconOver',1);\">
						<img name=\"$image_name\" title = \"$alt\" src=\"$icon\" alt=\"$alt\" border=\"0\" align=\"middle\" />
						&nbsp;<br/>$alt</a>");

        } else {
            // The button is just a link then!
            $bar->appendButton('Custom', "<a class=\"toolbar\" title = \"$alt\" href=\"$href\">&nbsp;$alt</a>");
        }
    }


    static function NewCustom($task = '', $page, $icon = '', $iconOver = '', $text = '', $alt = '', $listSelect = true, $formName = "adminForm")
    {
        global $VM_LANG;

        $bar = JToolBar::getInstance('toolbar');
        if ($listSelect) {
            if (empty($func))
                $href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('$text');}else{Joomla.submitbutton('$task','$formName', '$page')}";
            else
                $href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('$text');}else{vm_submitListFunc('$task','$formName', '$func')}";
        } else {
            $href = "javascript:Joomla.submitbutton('$task','$formName', '$page')";
        }
        if (empty($task)) {
            $image_name = uniqid("img_");
        } else {
            $image_name = $task;
        }
        if ($icon && $iconOver) {
            $bar->appendButton('Custom', "<a class=\"toolbar btn btn-small\" href=\"$href\" onmouseout=\"MM_swapImgRestore();\"  onmouseover=\"MM_swapImage('$image_name','','$iconOver',1);\">
						<img width=\"12\" height=\"12\" name=\"$image_name\" title = \"$alt\" src=\"$icon\" alt=\"$alt\" border=\"0\" align=\"middle\" /> $alt</a>");

        } else {
            // The button is just a link then!
            $bar->appendButton('Custom', "<a class=\"toolbar\" title = \"$alt\" href=\"$href\">&nbsp;$alt<a>");
        }
    }
}
