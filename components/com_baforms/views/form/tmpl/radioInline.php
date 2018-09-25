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
}
$option = str_replace('"', '', $options[2]);
$option = explode('\n', $option);
ob_start();
?>
<div class="ba-radioInline tool <?php echo $element->custom; ?>">
<?php
if ($options[0] != '' && $options[0] != ' *') { ?>
    <label style="font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
        font-weight: <?php echo $formSettings[10]; ?>">
        <span>
            <?php echo htmlspecialchars($options[0]);
            if (!empty($options[1])) {  ?>
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
    $class = '';
    if (isset($itemOptions->imageMap) && isset($itemOptions->imageMap->$i)) {
        $class = 'ba-input-image';
    }
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
    if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1 && $option[$i][1] != '') {
        $attr .= ' data-price="'.$option[$i][1].'"';
    }
    if (isset($options[3]) && $options[3] == 1 && $i == 0) {
        $attr .= ' required';
    }
    if (isset($options[4]) && $options[4] != '' && $options[4] == $i) {
        $attr .= ' checked';
    }
    $text = htmlspecialchars($option[$i][0]);
    if (isset($option[$i][1]) && $submissionsOptions['display_total'] == 1) {
        if ($option[$i][1] != '') {
            if (isset($itemOptions->imageMap) && isset($itemOptions->imageMap->$i)) {
                $text .= '<span>';
                if ($position == 'before') {
                    $text .= $symbol;
                }
                $text .= $option[$i][1];
                if ($position != 'before') {
                    $text .= $symbol;
                }
                $text .= '</span>';
            } else {
                $text .= ' - ';
                if ($position == 'before') {
                    $text .= $symbol;
                }
                $text .= $option[$i][1];
                if ($position != 'before') {
                    $text .= $symbol;
                }
            }
        }
    }
    ?>
    <span style='width: calc(<?php echo $itemOptions->width; ?>% - 20px); font-size: <?php echo $formSettings[4]; ?>;
        color: <?php echo $formSettings[5]; ?>' class="<?php echo $class; ?>">
        <input type='radio' name="<?php echo $element->id; ?>" value='<?php echo $value; ?>'
            <?php echo $attr; ?>/>
<?php
        if (isset($itemOptions->imageMap) && isset($itemOptions->imageMap->$i)) { ?>
        <img src="<?php echo $itemOptions->imageMap->$i; ?>" alt="">
<?php
        }
?>
        <span></span>
<?php
        if (isset($itemOptions->imageMap) && isset($itemOptions->imageMap->$i)) { ?>
        <span class="image-title">
<?php
        }
        echo $text;
        if (isset($itemOptions->imageMap) && isset($itemOptions->imageMap->$i)) { ?>
        </span>
<?php
        }
?>
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