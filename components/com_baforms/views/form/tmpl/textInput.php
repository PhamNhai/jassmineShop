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
$attr = '';
if (!isset($options[4]) || empty($options[4])) {
    $options[4] = 'regular';
}
if ($options[4] == 'calculation' && $submissionsOptions['display_total'] != 1) {
    $options[4] = 'regular';
}
if (isset($options[3]) && $options[3] == 1) {
    $options[0] .= ' *';
    $attr = ' required';
}
if (!empty($element->options)) {
    $itemOptions = json_decode($element->options);
} else {
    $itemOptions = new stdClass();
}
if (isset($itemOptions->max) && !empty($itemOptions->max)) {
    $attr .= ' maxlength="'.$itemOptions->max.'"';
}
ob_start();
?>
<div class="ba-textInput tool <?php echo $element->custom; ?>">
<?php
if ($options[0] != '' && $options[0] != ' *') { ?>
    <label style="font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
    	font-weight: <?php echo $formSettings[10]; ?>;">
    	<span>
    		<?php echo htmlspecialchars($options[0]);
            if (!empty($options[1])) {  ?>
            <span class="ba-tooltip">
            	<?php echo htmlspecialchars($options[1]); ?>
        	</span>
            <?php } ?>
        </span>
    </label>
<?php }
if (isset($options[5]) && strpos($options[5], 'zmdi') !== false) { ?>
    <div class="container-icon">
<?php
} ?>
		<input type="text" data-type="<?php echo $options[4]; ?>" style="height: <?php echo $formSettings[3]; ?>;
		    font-size: <?php echo $formSettings[4]; ?>; color: <?php echo $formSettings[5]; ?>;
		    background-color: <?php echo $formSettings[6]; ?>; <?php echo $formSettings[7]; ?>;
		    border-radius: <?php echo $formSettings[8]; ?>;" placeholder='<?php echo htmlspecialchars($options[2], ENT_QUOTES); ?>'
	    	name="<?php echo $element->id; ?>" <?php echo $attr; ?>>
<?php
if (isset($options[5]) && strpos($options[5], 'zmdi') !== false) { ?>
	    <div class="icons-cell">
	    	<i style="font-size: <?php echo $formSettings[11]; ?>px; color: <?php echo $formSettings[12]; ?>"
	    		class="<?php echo $options[5]; ?>"></i>
		</div>
	</div>
<?php } ?>
<?php
    if (isset($itemOptions->max) && !empty($itemOptions->max)) {
?>
        <p class="ba-maxlength">0/<?php echo $itemOptions->max; ?></p>
<?php
    }
?>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();