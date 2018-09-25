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

?>
<!-- sh404SEF OGP tags -->
<meta property="og:locale" content="<?php echo $displayData['locale']; ?>" />
<?php if (!empty($displayData['page_title'])) : ?>
<meta property="og:title" content="<?php echo $displayData['page_title']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['description'])) : ?>
<meta property="og:description" content="<?php echo $displayData['description']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['type'])) : ?>
<meta property="og:type" content="<?php echo $displayData['type']; ?>" />
<?php endif; ?>
<meta property="og:url" content="<?php echo $displayData['url']; ?>" />
<?php if (!empty($displayData['image'])) : ?>
<meta property="og:image" content="<?php echo $displayData['image']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['image_width'])) : ?>
<meta property="og:image:width" content="<?php echo $displayData['image_width']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['image_height'])) : ?>
<meta property="og:image:height" content="<?php echo $displayData['image_height']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['image_secure_url'])) : ?>
<meta property="og:image:secure_url" content="<?php echo $displayData['image_secure_url']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['site_name'])) : ?>
<meta property="og:site_name" content="<?php echo $displayData['site_name']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['fb_admins'])) : ?>
<meta property="fb:admins" content="<?php echo $displayData['fb_admins']; ?>" />
<?php endif; ?>
<?php if (!empty($displayData['app_id'])) : ?>
<meta property="fb:app_id" content="<?php echo $displayData['app_id']; ?>" />
<?php endif; ?>
<!-- sh404SEF OGP tags - end -->
