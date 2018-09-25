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
<div class="baforms-cart" style="<?php echo $formSettings[7]; ?>; font-size: <?php echo $formSettings[4]; ?>;
    color: <?php echo $formSettings[5]; ?>">
    <div class="product-cell ba-cart-headline" style="<?php echo str_replace('border', 'border-bottom', $formSettings[7]); ?>;
        font-size: <?php echo $formSettings[1]; ?>; color: <?php echo $formSettings[2]; ?>;
        font-weight: <?php echo $formSettings[10]; ?>;">
        <div class="product"><?php echo JText::_("ITEM"); ?></div>
        <div class="price"><?php echo JText::_("PRICE"); ?></div>
        <div class="quantity"><?php echo JText::_("QUANTITY"); ?></div>
        <div class="total"><?php echo $language->_("TOTAL"); ?></div>
        <div class="remove-item"></div>
    </div>
</div>
<input type="hidden" class="cart-currency" value="<?php echo $symbol; ?>">
<input type="hidden" class="cart-position" value="<?php echo $position; ?>">
<?php
$out = ob_get_contents();
ob_end_clean();