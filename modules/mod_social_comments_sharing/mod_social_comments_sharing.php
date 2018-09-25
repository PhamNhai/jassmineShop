<?php
/*
* @version 2.1
* @package social sharing
* @copyright 2012 OrdaSoft
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @author 2012 Andrey Kvasnekskiy (akbet@ordasoft.com )
* @description social sharing, sharing WEB pages in LinkedIn, FaceBook, Twitter and Google+ (G+)
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('socialSharingHelper', JPATH_SITE . '/modules/mod_social_comments_sharing/helpers/socialSharingHelper.php');

require JModuleHelper::getLayoutPath('mod_social_comments_sharing',$params->get('layout', 'default'));