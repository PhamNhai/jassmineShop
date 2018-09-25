<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date        2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC'))
{
	die('Direct Access to this location is not allowed.');
}

class Sh404sefHelperOgp
{
	protected static $_definitions = null;

	public static function getDefinitions()
	{
		if (is_null(self::$_definitions))
		{
			self::$_definitions = ShlSystem_Xml::fromFile(sh404SEF_ADMIN_ABS_PATH . 'helpers/ogp.xml');
		}

		return self::$_definitions;
	}

	/**
	 * Method to create a select list of possible Open Graph object types
	 *
	 * @access  public
	 *
	 * @param int ID of current item
	 * @param string name of select list
	 * @param boolean if true, changing selected item will submit the form (assume is an "adminForm")
	 * @param boolean , if true, a line 'Select all' is inserted at the start of the list
	 * @param string the "Select all" to be displayed, if $addSelectAll is true
	 *
	 * @return  string HTML output
	 */
	public static function buildOpenGraphTypesList($current, $name, $autoSubmit = false, $addSelectDefault = false, $selectDefaultTitle = '',
	                                               $customSubmit = '')
	{
		// build html options
		$data = array();
		foreach (self::getDefinitions() as $typeDef)
		{
			$data[] = array('id' => $typeDef['name'], 'title' => JText::_($typeDef['title']));
		}

		// add select all option
		if ($addSelectDefault)
		{
			$selectDefault = array('id' => SH404SEF_OPTION_VALUE_USE_DEFAULT, 'title' => $selectDefaultTitle);
			array_unshift($data, $selectDefault);
		}

		// use helper to build html
		$list = Sh404sefHelperHtml::buildSelectList($data, $current, $name, $autoSubmit, $addSelectAll = false, $selectAllTitle = '', $customSubmit);

		// return list
		return $list;
	}

	public static function buildOpenGraphTags()
	{
		$tags = array(
			'openGraphData' => '',
			'ogNameSpace' => '',
			'fbNameSpace' => ''
		);

		// get sh404sef config
		$config = Sh404sefFactory::getConfig();
		$pageInfo = Sh404sefFactory::getPageInfo();

		if (empty($config->shMetaManagementActivated) || !isset($config) || empty($pageInfo->currentNonSefUrl)
			|| (!empty($pageInfo->httpStatus) && $pageInfo->httpStatus == 404)
		)
		{
			return $tags;
		}

		$customData = Sh404sefHelperMetadata::getCustomMetaDataFromDb();

		// user can disable per url
		if ($customData->og_enable == SH404SEF_OPTION_VALUE_NO
			|| (empty($config->enableOpenGraphData) && $customData->og_enable == SH404SEF_OPTION_VALUE_USE_DEFAULT)
		)
		{
			return $tags;
		}

		$displayData = array();

		// add locale -  FB use underscore in language tags
		$displayData['locale'] = str_replace('-', '_', JFactory::getLanguage()->getTag());

		// insert title
		if (!empty($pageInfo->pageTitle))
		{
			$displayData['page_title'] = $pageInfo->pageTitle;
		}

		// insert description
		if ((($config->ogEnableDescription && $customData->og_enable_description == SH404SEF_OPTION_VALUE_USE_DEFAULT)
				|| $customData->og_enable_description == SH404SEF_OPTION_VALUE_YES) && !empty($pageInfo->pageDescription)
		)
		{
			$displayData['description'] = $pageInfo->pageDescription;
		}

		// insert type
		$content = $customData->og_type == SH404SEF_OPTION_VALUE_USE_DEFAULT ? $config->ogType : $customData->og_type;
		if (!empty($content))
		{
			$displayData['type'] = $content;
		}

		// insert url. If any, we insert the canonical url rather than current, to consolidate
		$content = empty($pageInfo->pageCanonicalUrl) ? $pageInfo->currentSefUrl : $pageInfo->pageCanonicalUrl;
		$content = Sh404sefHelperUrl::stripTrackingVarsFromSef($content);
		$displayData['url'] = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

		// insert image
		$content = empty($customData->og_image) ? $config->ogImage : $customData->og_image;
		if (!empty($content))
		{
			$displayData['image'] = ShlSystem_Route::absolutify($content, true);
			$secure = JUri::getInstance()->isSSL();
			if ($secure)
			{
				$displayData['image_secure_url'] = ShlSystem_Route::absolutify($content, true);
			}
			$dimensions = ShlHtmlContent_Image::getImageSize($displayData['image']);
			if (!empty($dimensions['width']))
			{
				$displayData['image_width'] = $dimensions['width'];
			}
			if (!empty($dimensions['height']))
			{
				$displayData['image_height'] = $dimensions['height'];
			}
		}

		// insert site name
		if (($config->ogEnableSiteName && $customData->og_enable_site_name == SH404SEF_OPTION_VALUE_USE_DEFAULT)
			|| $customData->og_enable_site_name == SH404SEF_OPTION_VALUE_YES
		)
		{
			$content = empty($customData->og_site_name) ? $config->ogSiteName : $customData->og_site_name;
			$content = empty($content) ? JFactory::getApplication()->getCfg('sitename') : $content;
			if (!empty($content))
			{
				$content = Sh404sefHelperMetadata::cleanUpDesc($content);
				$displayData['site_name'] = $content;
			}
		}

		if (!empty($displayData))
		{
			$tags['ogNameSpace'] = 'xmlns:og="http://ogp.me/ns#"';
		}

		if ((!empty($config->fbAdminIds) && $customData->og_enable_fb_admin_ids == SH404SEF_OPTION_VALUE_USE_DEFAULT)
			|| $customData->og_enable_fb_admin_ids == SH404SEF_OPTION_VALUE_YES
		)
		{
			$content = empty($customData->fb_admin_ids) ? $config->fbAdminIds : $customData->fb_admin_ids;
			if ($customData->og_enable_fb_admin_ids != SH404SEF_OPTION_VALUE_NO && !empty($content))
			{
				$displayData['fb_admins'] = $content;
				$tags['fbNameSpace'] = 'xmlns:fb="https://www.facebook.com/2008/fbml"';
			}
		}

		// application id
		$appId = empty($config->fbAppId) ? Sh404sefFactory::getPConfig()->facebookDefaultAppId : $config->fbAppId;
		$displayData['app_id'] = $appId;

		$tags['openGraphData'] = ShlMvcLayout_Helper::render('com_sh404sef.social.ogp', $displayData);

		return $tags;
	}

	/**
	 * Detect whether an article Full or intro image are suitable as OGP image
	 *
	 * @param Object $contentObject
	 *
	 * @return string
	 */
	public static function detectComContentImages($contentObject)
	{
		if (!empty($contentObject) && !empty($contentObject->images))
		{
			$imageDef = new JRegistry($contentObject->images);
			$possibleImages = array('image_fulltext', 'image_intro');
			foreach ($possibleImages as $possibleImage)
			{
				$image = $imageDef->get($possibleImage);
				if (!empty($image))
				{
					$fakeContent = '<img src="' . $image . '">';
					$bestImage = ShlHtmlContent_Image::getBestImage($fakeContent, $firstImage = 1, Sh404sefFactory::getPConfig()->facebookImageSize);
					if (!empty($bestImage))
					{
						return $bestImage;
					}
				}
			}
		}

		return '';
	}
}
