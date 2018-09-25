<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

class AdminViewImportExport
{

	
	static function import(&$step = 1,&$data = array()) {

		$html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Import' />" . JText::_('COM_OS_CCK_IMPORT') . "</div>";
	    $app = JFactory::getApplication();
	    $app->JComponentTitle = $html;

		if($step === 1){

		?>
		<h3> Import </h3><hr/>
		<form method="POST" action="index.php?option=com_os_cck&task=import&step=2" class="form-horizontal" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="importData">Zip File for import</label>
				<div class="controls">
					<input id="importData" type="file" name="importData" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn">Upload</button>
				</div>
			</div>
		</form>
		<?php 
		}elseif($step === 2){

			if(isset($data['error'])) 
				{
					echo JFactory::getApplication()->enqueueMessage($data['error'], 'Error');
				}else{
					echo JFactory::getApplication()->enqueueMessage('Archive loaded successfully', 'Message');
				}
			?>

			&nbsp&nbsp<button type="button" class="btn btn-small" onclick="history.go(-1);" >Back</button>
			<?php
			
		}
	}
	

}
