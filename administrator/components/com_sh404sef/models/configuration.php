<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date		2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

include_once str_replace('.php', '.' . Sh404sefConfigurationEdition::$id . '.php', basename(__FILE__));
