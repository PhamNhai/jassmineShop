<?php
/**
* @title		Image gallery module
* @website		http://www.joomla51.com
* @copyright	Copyright (C) 2012 Joomla51.com. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
abstract class SlideshowHelper {

	static function getimgList($params) {
		$filter 		= '\.png$|\.gif$|\.jpg$|\.jpeg$|\.bmp$';
		$path			= $params->get('path');	
		$files 			= JFolder::files(JPATH_BASE.$path,$filter);
		
		$i=0;
		$lists = array();
		
		foreach ($files as $file) {
			$images[$i] = new stdClass();
			$lists[$i] = new stdClass();
			$image				= SlideshowHelper::getImages($path.'/'.$file);
			$lists[$i]->title	= JFile::stripExt($file);
			$lists[$i]->image 	= $image->image;
			$i++;
		}
		return $lists; 
	}
	
	static function getImages($image) {	  
		$images = new stdClass();

		$paths = array();
		if (isset($image)) {
			$image_path = $image;
			
			// remove any / that begins the path
			if (substr($image_path, 0 , 1) == '/') $image_path = substr($image_path, 1);
					

			$images->image = $image_path;

		} 
		return $images;
	}		
}	