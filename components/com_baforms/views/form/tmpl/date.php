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
if (isset($options[1]) && $options[1] == 1) {
    $options[0] .= ' *';
    $element->custom .= ' required';
}
if (isset($options[2]) && $options[2] == 1) {
    $element->custom .= ' disable-previous-date';
}
ob_start();
?>
<div class="ba-date tool <?php echo $element->custom; ?>">
<?php
if ($options[0] != '' && $options[0] != ' *') { ?>
    <label style="font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
    	font-weight: <?php echo $formSettings[10]; ?>">
		<?php echo htmlspecialchars($options[0]); ?>
	</label>
<?php
}
?>
	<div class="container-icon">
		<input type="text" name="<?php echo $element->id; ?>" style="height: <?php echo $formSettings[3]; ?>;
			font-size: <?php echo $formSettings[4]; ?>; color: <?php echo $formSettings[5]; ?>;
			background-color: <?php echo $formSettings[6].'; '.$formSettings[7]; ?>;
			border-radius: <?php echo $formSettings[8]; ?>" id="date_<?php echo $element->id; ?>" value="" readonly>
        <div class="icons-cell">
        	<i style="font-size: <?php echo $formSettings[11]; ?>px; color: <?php echo $formSettings[12]; ?>"
        		class="zmdi zmdi-calendar-alt"></i>
        </div>
    </div>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();