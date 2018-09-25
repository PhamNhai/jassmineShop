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

/**
 * Input:
 *
 * 'entity_type'
 * 'entity_url'
 * 'entity_name'
 * 'profiles'
 */
// Security check to ensure this file is being included by a parent file.
defined('_JEXEC') or die();

$displayData['sameAs'] = array();
foreach ($displayData['profiles'] as $profile)
{
	$displayData['sameAs'][] = $profile;
}
unset($displayData['profiles']);
?>
<!-- Google social profiles markup-->
<script type="application/ld+json">
<?php echo json_encode($displayData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

</script>
<!-- End of Google social profiles markup-->
