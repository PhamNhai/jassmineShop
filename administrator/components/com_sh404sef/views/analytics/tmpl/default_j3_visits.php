<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author       Yannick Gaultier
 * @copyright    (c) Yannick Gaultier - Weeblr llc - 2016
 * @package      sh404SEF
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version      4.8.1.3465
 * @date        2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC'))
{
	die('Direct Access to this location is not allowed.');
}
?>
<h2><?php echo Sh404sefHelperAnalytics::getDataTypeTitle(); ?></h2>
<div class="analytics-report-image">
	<img src="<?php echo $this->analytics->analyticsData->images['visits']; ?>"/>
</div>