<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

ob_start();
?>
<div class="ba-total-price">
    <p style="text-align: <?php echo $formSettings[14]; ?>; font-size: <?php echo $formSettings[1]; ?>;
        color: <?php echo $formSettings[2]; ?>; font-weight: <?php echo $formSettings[10]; ?>;">
        <?php echo $formSettings[13].': ';
    if ($position == 'before') {  ?>
        <span>
            <?php echo $symbol; ?>
        </span>
<?php
    }
?>
        <span class="ba-price">0</span>
<?php
    if ($position != 'before') { ?>
        <span>
            <?php echo $symbol; ?>
        </span>
<?php
}
?>
        <input type="hidden" name="ba_total" value="0">
    </p>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();