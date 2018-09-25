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
if (isset($options[3])) {
    if ($options[3] == 1) {
        $options[0] .= ' *';
    }
}
$option = str_replace('"', '', $options[2]);
$option = explode('\n', $option);
ob_start();
?>
<div class="ba-radioMultiple tool <?php echo $element->custom; ?>">
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
            }
?>
        </span>
    </label>
<?php
}
for ($i = 0; $i < count($option); $i++) {
    $option[$i] = explode('====', $option[$i]);
    $value = htmlspecialchars($option[$i][0], ENT_QUOTES);
    if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1) {
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
    $attr = '';
    if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1) {
        if ($option[$i][1] != '') {
            $attr .= ' data-price="'.$option[$i][1].'"';
        }
    }
    if (isset($options[3])) {
        if ($options[3] == 1) {
            $attr .= ' required';
        }
    }
    if (isset($options[4]) && $options[4] != '' && $options[4] == $i) {
        $attr .= ' checked';
    }
?>
    <span style='font-size: <?php echo $formSettings[4]; ?>; color: <?php echo $formSettings[5]; ?>'>
        <input type='radio' name="<?php echo $element->id; ?>" value='<?php echo $value; ?>'
            <?php echo $attr; ?>/>
        <span></span>
        <?php echo $value; ?>
    </span>
<?php
}
if (isset($settings[5]) && strlen($settings[5]) > 0) {
    $conditions = explode(';', $settings[5]);
    foreach ($conditions as $condition) { ?>
    <div class="condition-area" data-condition="<?php echo $condition; ?>">
<?php
    foreach ($elements as $value) {
        $sett = explode('_-_', $value->settings);
        if ($settings[1] == $sett[0] && $sett[4] === $condition) {
            echo self::restoreHTML($formSettings, $value, $submissionsOptions, $elements);
        }
    }
?>
    </div>
<?php
    }
}
?>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();