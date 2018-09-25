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

// Set some global property
$document = JFactory::getDocument();

//include needed style
$document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/admin.css");
//include icons
$document->addStyleSheet("//fonts.googleapis.com/icon?family=Material+Icons");

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_osgallery'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// require helper file
JLoader::register('osGalleryHelperAdmin', JPATH_COMPONENT . '/helpers/osGalleryHelperAdmin.php');

// Perform the Request task
$input = JFactory::getApplication()->input;
$task = $input->getCmd('task', '');
$galId = $input->getCmd('galId', '');
// print_r($task);exit;
switch ($task) {
    case "upload_images":
        osGalleryHelperAdmin::uploadImages();
        break;

    case "new_gallery":
        osGalleryHelperAdmin::displayGallery(0);
        break;

    case "clone_gallery":
        osGalleryHelperAdmin::cloneGallery($galId, $input->getCmd('with_image', 0));
        break;

    case "unpublish":
        osGalleryHelperAdmin::published($galId, 0);
        break;

    case "publish":
        osGalleryHelperAdmin::published($galId, 1);
        break;

    case "delete_gallery":
        osGalleryHelperAdmin::deleteGallery($galId);
        break;

    case "edit_gallery":
        osGalleryHelperAdmin::displayGallery($galId);
        break;

    case "save_gallery":
        osGalleryHelperAdmin::saveGallery(false);
        break;

    case "save_close_galery":
        osGalleryHelperAdmin::saveGallery(true);
        break;

    case "close_gallery":
    default:
        osGalleryHelperAdmin::displayDefault();
        break;
}