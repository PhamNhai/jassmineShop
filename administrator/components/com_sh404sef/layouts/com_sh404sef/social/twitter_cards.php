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
defined('_JEXEC') or die('');

/**
 * JLayout params
 * card_type
 * site_account
 * creator
 * description
 * url
 * image
 */
?>
<!-- sh404SEF Twitter cards -->
<meta name="twitter:card" content="<?php echo $displayData['card_type']; ?>" />
<?php if (!empty($displayData['site_account'])) : ?>
<meta name="twitter:site" content="<?php echo $displayData['site_account']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['creator'])) : ?>
<meta name="twitter:creator" content="<?php echo $displayData['creator']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['title'])) : ?>
<meta name="twitter:title" content="<?php echo $displayData['title']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['description'])) : ?>
<meta name="twitter:description" content="<?php echo $displayData['description']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['url'])) : ?>
<meta name="twitter:url" content="<?php echo $this->escape($displayData['url']); ?>" />
<?php endif; ?>
<?php if (!empty($displayData['image'])) : ?>
<meta name="twitter:image" content="<?php echo ShlSystem_Route::absolutify($displayData['image'], true); ?>" />
<?php endif; ?>
<!-- sh404SEF Twitter cards - end -->

