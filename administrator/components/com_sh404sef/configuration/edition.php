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
class Sh404sefConfigurationEdition
{
	public static $id = 'full';
	public static $name = '';

	public static function is($edition)
	{
		return $edition == self::id;
	}
}
