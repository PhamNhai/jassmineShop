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
<div class="slider tool <?php echo $element->custom; ?>">
<?php
if ($options[0] != '') { ?>
	<label style="font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
		font-weight: <?php echo $formSettings[10]; ?>">
		<span>
			<?php echo htmlspecialchars($options[0]);
                if (!empty($options[1])) { ?>
                    <span class="ba-tooltip">
                    	<?php echo htmlspecialchars($options[1]); ?>
                	</span>
<?php
                }
?>
		</span>
	</label>
<?php
}
?>
	<input type="hidden" class="ba-slider-values" name="<?php echo $element->id; ?>">
	<div class="ba-slider"></div>
	<input type="hidden" value="<?php echo htmlspecialchars($settings[3]); ?>" class="ba-options">
</div>
<?php
$out = ob_get_contents();
ob_end_clean();