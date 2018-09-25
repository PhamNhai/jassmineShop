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
if (count($options) > 2) {
    $data = array_slice($options, 0, count($options) - 1);
    $data = implode(';', $data);
} else {
    $data = $options[0];
}
ob_start();
?>
<div class="ba-terms-conditions tool <?php echo $element->custom; ?>">
	<span>
		<input type="checkbox">
		<span></span>
	</span>
	<div class="terms-content">
		<?php echo $data; ?>
	</div>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();