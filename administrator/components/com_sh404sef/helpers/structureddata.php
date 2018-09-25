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
defined('_JEXEC') or die();

/**
 * Builds a full set of Structured data for search engines
 * Class Sh404sefHelperStructureddata
 */
class Sh404sefHelperStructureddata
{
	/**
	 * Builds a full set of Structured data for the current page
	 *
	 * @return string
	 */
	public static function buildStructuredData()
	{
		$structuredData = array();

		$config = Sh404sefFactory::getConfig();
		if (empty($config->shMetaManagementActivated))
		{
			return '';
		}

		$structuredData[] = self::buildSitename($config);
		$structuredData[] = self::buildSitelinksSearch($config);
		$structuredData[] = self::buildSocialProfiles($config);
		$structuredData[] = self::buildLogoContacts($config);
		$structuredData[] = self::buildBreadcrumb($config);

		return implode("\n", $structuredData);
	}

	/**
	 * Builds Sitename structured data
	 *
	 * @param $config
	 * @return string
	 */
	private static function buildSitename($config)
	{
		if (empty($config->insertGoogleSitename) || !Sh404sefHelperUrl::isHomepage())
		{
			return '';
		}

		// prepare markup data
		$siteName = $config->sd_sitename;
		$siteName = empty($siteName) ? JFactory::getConfig()->get('sitename') : $siteName;
		$displayData = array(
			'@context' => 'http://schema.org',
			'@type' => 'WebSite',
			'name' => $siteName,
			'url' => JUri::current()
		);

		// actually insert the tags
		$markup = ShlMvcLayout_Helper::render('com_sh404sef.markup.google_sitename', $displayData);

		return $markup;
	}

	/**
	 * Builds Logo and Contacts structured data
	 *
	 * @param $config
	 * @return string
	 */
	private static function buildLogoContacts($config)
	{
		if (empty($config->sd_logo_url) && empty($config->sd_contactpoint_1_phone) && empty($config->sd_contactpoint_2_phone))
		{
			return '';
		}

		// prepare markup data
		$orgUrl = empty($config->sd_logo_contacts_organization_url) ? JURI::root() : $config->sd_logo_contacts_organization_url;
		$displayData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Organization',
			'url' => JString::trim($orgUrl)
		);

		if (!empty($config->sd_logo_url))
		{
			$displayData['logo'] = ShlSystem_Route::absolutify(JString::trim($config->sd_logo_url), true);
		}

		$contactPoints = array();
		for ($i = 1; $i <= 2; $i++)
		{
			$propName = 'sd_contactpoint_' . $i . '_phone';
			$phone = JString::trim($config->$propName);
			if (!empty($phone))
			{
				$propName = 'sd_contactpoint_' . $i . '_type';
				$item = array(
					'@type' => 'ContactPoint',
					'telephone' => $phone,
					'contactType' => JString::trim($config->$propName)
				);
				$propName = 'sd_contactpoint_' . $i . '_option';
				$option = $config->$propName;
				if (!empty($option))
				{
					$item['contactOption'] = $option;
				}
				$propName = 'sd_contactpoint_' . $i . '_area';
				$areas = ShlSystem_Strings::stringToCleanedArray($config->$propName);
				if (!empty($areas))
				{
					$item['areaServed'] = $areas;
				}

				$propName = 'sd_contactpoint_' . $i . '_language';
				$languages = ShlSystem_Strings::stringToCleanedArray($config->$propName);
				if (!empty($languages))
				{
					$item['availableLanguage'] = $languages;
				}
				$contactPoints[] = $item;
			}
		}
		if (!empty($contactPoints))
		{
			$displayData['contactPoint'] = $contactPoints;
		}

		// actually insert the tags
		$markup = ShlMvcLayout_Helper::render('com_sh404sef.markup.sd_logo_contacts', $displayData);

		return $markup;
	}

	/**
	 * Builds Social profiles structured data
	 *
	 * @param $config
	 * @return string
	 */
	private static function buildSocialProfiles($config)
	{
		$socialProfiles = JString::trim($config->sd_social_profiles);
		if (empty($socialProfiles))
		{
			return '';
		}

		// prepare markup data
		$profiles = ShlSystem_Strings::stringToCleanedArray($socialProfiles, "\n");
		if (empty($profiles))
		{
			return '';
		}
		$entityUrl = empty($config->sd_social_profiles_org_url) ? JURI::root() : $config->sd_social_profiles_org_url;
		$entityName = empty($config->sd_social_profiles_org_name) ? JFactory::getConfig()->get('sitename') : $config->sd_social_profiles_org_name;
		$displayData = array(
			'@context' => 'http://schema.org',
			'@type' => $config->sd_social_profiles_org_type,
			'url' => JString::trim($entityUrl),
			'name' => JString::trim($entityName),
			'profiles' => $profiles
		);

		// actually insert the tags
		$markup = ShlMvcLayout_Helper::render('com_sh404sef.markup.sd_social_profiles', $displayData);

		return $markup;
	}

	/**
	 * Builds Site links search structured data
	 *
	 * @param $config
	 * @return string
	 */
	private static function buildSitelinksSearch($config)
	{
		$sefConfig = Sh404sefFactory::getConfig();
		if (empty($sefConfig->insertGoogleSitelinksSearch))
		{
			return '';
		}

		// prepare markup data
		$prefix = rtrim(str_replace(JUri::base(true), '', Juri::base(false)), '/');
		$target = empty($sefConfig->insertGoogleSitelinksSearchCustom) ?
			$prefix . JRoute::_('index.php?option=com_search&searchword=') . '{search_term_string}' :
			$sefConfig->insertGoogleSitelinksSearchCustom;

		$displayData = array(
			'@context' => 'http://schema.org',
			'@type' => 'WebSite',
			'url' => Juri::base(false),
			'potentialAction' => array(
				'@type' => 'SearchAction',
				'target' => $target,
				'query-input' => 'required name=search_term_string'
			)
		);

		// actually insert the tags
		$markup = ShlMvcLayout_Helper::render('com_sh404sef.markup.google_sitelinks_search', $displayData);

		return $markup;
	}

	/**
	 * Builds Breadcrumb structured data
	 *
	 * @param $config
	 * @return string
	 */
	private static function buildBreadcrumb($config)
	{
		$markup = '';

		// bail out if not set
		if (empty($config->insertGoogleBreadcrumb))
		{
			return $markup;
		}

		$breadcrumb = JFactory::getApplication()->getPathway();
		$breadcrumbItems = empty($breadcrumb) ? null : $breadcrumb->getPathway();
		$displayData = array();
		if (!empty($breadcrumbItems))
		{
			// add other crumbs
			foreach ($breadcrumbItems as $key => $item)
			{
				$itemData = array(
					'position' => $key + 2,
					'id' => $item->link,
					'name' => $item->name
				);
				$displayData['items'][] = $itemData;
			}
			if (!empty($displayData))
			{
				// load breadcrumb module language and params
				$module = JModuleHelper::getModule('mod_breadcrumbs');
				$lang = JFactory::getLanguage();
				$lang->load('mod_breadcrumbs', JPATH_BASE, null, false, true) ||
				$lang->load('mod_breadcrumbs', JPATH_BASE . '/modules/mod_breadcrumbs', null, false, true);

				if (!empty($module) && !empty($module->id))
				{
					$params = new JRegistry;
					$params->loadString($module->params);
					$homeTitle = htmlspecialchars($params->get('homeText', JText::_('MOD_BREADCRUMBS_HOME')));
				}
				else
				{
					$homeTitle = JText::_('MOD_BREADCRUMBS_HOME');
				}
				// home link
				if (JLanguageMultilang::isEnabled())
				{
					$home = JFactory::getApplication()->getMenu()->getDefault($lang->getTag());
				}
				else
				{
					$home = JFactory::getApplication()->getMenu()->getDefault();
				}
				// insert home crumb
				array_unshift(
					$displayData['items'], array(
						                     'position' => 1,
						                     'id' => 'index.php?Itemid=' . $home->id,
						                     'name' => $homeTitle
					                     )
				);
			}
		}

		if (!empty($displayData))
		{
			$markup = ShlMvcLayout_Helper::render('com_sh404sef.markup.google_breadcrumb', $displayData);
		}

		return $markup;
	}
}
