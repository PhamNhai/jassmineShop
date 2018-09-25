<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class JFormFieldGalleryList extends JFormField{
  protected function getInput(){
    $db = JFactory::getDBO();
    $input = JFactory::getApplication()->input;
    $menuId = $input->get("id",0,"INT");
    $app = JFactory::getApplication();
    if($menuId) {
      $db->setQuery("SELECT `params` FROM `#__menu` WHERE `id` = ".$menuId);
      $params = json_decode($db->loadResult());
    }
    $query = "SELECT * FROM #__os_gallery";
    $db->setQuery($query);
    $galleries = $db->loadObjectList();

    $options=array();
    if(count($galleries)){
        foreach ($galleries as $gallery) {
            $options[] = JHtmlSelect::option($gallery->id, $gallery->title);
        }
    }
    $selected = isset($params->gallery_list)?$params->gallery_list:'';
    $html = JHtmlSelect::genericlist($options, $this->name,
                'size="1" multiple="true" class="inputbox" ', 'value', 'text', $selected);

    return $html;
  }
}