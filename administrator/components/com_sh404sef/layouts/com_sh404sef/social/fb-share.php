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

/**
 * Input:
 *
 * $displayData['fbLayout']
 * $displayData['url']
 */
// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC')) die('Direct Access to this location is not allowed.');

?>
<!-- Facebook share button -->
<fb:share-button href="<?php echo $displayData['url']; ?>" layout="<?php echo $displayData['fbLayout']; ?>">
</fb:share-button>
<!-- Facebook share button -->
