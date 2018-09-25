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

// require helper file
JLoader::register('osGalleryHelperSite', JPATH_COMPONENT . '/helpers/osGalleryHelperSite.php');
// Perform the Request task
// print_r($_REQUEST);exit;
$input = JFactory::getApplication()->input;
$task = $input->getCmd('task', '');
$view = $input->getCmd('view', '');
if(!$task && $view)$task = $view;

switch ($task) {
    case "defaultTabs":
        osGalleryHelperSite::displayView();
        break;
        
    case "loadMoreButton":
        osGalleryHelperSite::displayView();
        break;

    default:
        echo 'errror: '.$task;
        break;
}