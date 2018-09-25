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
if (isset($options[4]) && $options[4] == 1) {
    $options[0] .= ' *';
    $required = 'required';
}
ob_start();
?>
<div class="ba-upload tool <?php echo $element->custom; ?>">
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
    <input class='ba-upload' type='file' <?php echo $required; ?> multiple name="<?php echo $element->id;?>[]">
    <span style="font-size: 12px; font-style: italic; color: #999999;">
        <?php echo JText::_('MAXIMUM_FILE_SIZE').' '.$options[2].'mb ('.$options[3].')'; ?>
    </span>
    <input type="hidden" class="upl-size" value="<?php echo $options[2]; ?>">
    <input type="hidden" class="upl-type" value="<?php echo $options[3]; ?>">
    <input type="hidden" class="upl-error">
</div>
<?php
$out = ob_get_contents();
ob_end_clean();