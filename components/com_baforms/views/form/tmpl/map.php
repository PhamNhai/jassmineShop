<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

$settings = explode('_-_', $element->settings);
$options = explode(';', $settings[3]);
ob_start();
?>
<div>
	<div class="ba-map tool <?php echo $element->custom; ?>" style="width: <?php echo $options[3]; ?>%;
		height: <?php echo $options[4]; ?>px;"></div>
	<input type='hidden' value='<?php echo str_replace("'", '-_-', $settings[3]); ?>' class='ba-options'>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();