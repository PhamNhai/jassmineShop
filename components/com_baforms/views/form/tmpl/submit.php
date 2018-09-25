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
<div class="row-fluid ba-row">
	<div class="span12" style="<?php echo $buttonAligh; ?>">
		<input class="ba-btn-submit" type="submit" style="<?php echo $buttonStyle; ?>"
			value="<?php echo $button; ?>" <?php echo $embed; ?>>
    </div>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();