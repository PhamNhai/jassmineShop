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
<div class="modal-scrollable ba-forms-modal" style="display: none; background-color: <?php echo $submissionsOptions['dialog_color_rgba']; ?>">
	<div class="ba-modal fade hide save-and-continue-modal" style="color: <?php echo $submissionsOptions['message_color_rgba']; ?>;
		background-color: <?php echo $submissionsOptions['message_bg_rgba']; ?>;">
		<a href="#" class="ba-modal-close zmdi zmdi-close"></a>
		<div class="ba-modal-body">
			<h3><?php echo $saveContinue->title; ?></h3>
			<div class="save-message">

			</div>
			<div class="save-email-wrapper">
				<input class="save-email" type="email">
				<input type="hidden" class="save-popup-message" value="<?php echo htmlentities($saveContinue->message, ENT_COMPAT); ?>">
				<input type="hidden" class="save-popup-token" value="<?php echo $token; ?>">
				<div class="send-btn-wrapper">
					<a href="#" class="ba-btn send-save"><?php echo JText::_('SEND'); ?></a>
					<img src="components/com_baforms/assets/images/reload.svg" alt="">
					<i class="zmdi zmdi-check"></i>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();