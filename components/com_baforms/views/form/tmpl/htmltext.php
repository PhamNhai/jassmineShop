<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

$settings = explode('_-_', $element->settings);
ob_start();
?>
<div class="ba-htmltext tool <?php echo $element->custom; ?>">
    <?php echo $settings[3]; ?>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();