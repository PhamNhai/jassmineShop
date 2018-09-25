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
$required = '';
if (!empty($element->options)) {
    $itemOptions = json_decode($element->options);
} else {
    $itemOptions = new stdClass();
}
if (!isset($itemOptions->width)) {
    $itemOptions->width = 25;
}
if (isset($options[3]) && $options[3] == 1) {
    $options[0] .= ' *';
    $required = 'required';
}
$option = str_replace('"', '', $options[2]);
$option = explode('\n', $option);
ob_start();
?>
<div class="ba-checkMultiple tool <?php echo$element->custom; ?>">
<?php
if ($options[0] != '' && $options[0] != ' *') { ?>
    <label style="font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
		font-weight: <?php echo $formSettings[10]; ?>">
		<span>
			<?php echo htmlspecialchars($options[0]);
            if (!empty($options[1])) { ?>
            <span class="ba-tooltip">
            	<?php echo htmlspecialchars($options[1]); ?>
        	</span>
<?php
            } ?>
        </span>
    </label>
<?php
}
?>
	<div class="<?php echo $required; ?>">
<?php
	for ($i = 0; $i < count($option); $i++) {
        $option[$i] = explode('====', $option[$i]);
        $value = htmlspecialchars($option[$i][0], ENT_QUOTES);
        if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1) {
            if ($option[$i][1] != '') {
                if ($option[$i][1] != '') {
                    $value .= ' - ';
                    if ($position == 'before') {
                        $value .= $symbol;
                    }
                    $value .= $option[$i][1];
                    if ($position != 'before') {
                        $value .= $symbol;
                    }
                }
            }
        }
        $attr = '';
        if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1) {
            if ($option[$i][1] != '') {
                $attr .= ' data-price="'.$option[$i][1].'"';
            }
        }
        if (isset($options[4]) && $options[4] != '' && $options[4] == $i) {
            $attr .= ' checked';
        }
        ?>
        <span style='font-size: <?php echo $formSettings[4]; ?>; color: <?php echo $formSettings[5]; ?>'>
        	<input type='checkbox' name="<?php echo $element->id; ?>[]" value="<?php echo $value; ?>"
                <?php echo $attr; ?>/>
            <span></span>
            <?php echo $value; ?>
        </span>
<?php
	}
?>
	</div>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();