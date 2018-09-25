<?php 
defined('_JEXEC') or die('Restricted access');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
 ?>
<!-- Layout Modal -->
<div class="modal fade" id="layout-modal" tabindex="-1" role="dialog" aria-labelledby="layout-modalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" id="layout-modalLabel"><?php echo JText::_("COM_OS_CCK_EDIT_LAYOUT_MODAL_TITLE")?></h4>
      </div>
      <div class="modal-body">
        <label class="columns">
          <input class="culumn-num" type="radio" data-column="1" value="" name="column1">
          <img src="<?php echo JURI::root();?>/administrator/components/com_os_cck/images/1col.png" alt="">
        </label>
        <label class="columns">
          <input class="culumn-num" type="radio" data-column="2" value="" name="column1">
          <img src="<?php echo JURI::root();?>/administrator/components/com_os_cck/images/2col.png" alt="">
        </label>
        <label class="columns">
          <input class="culumn-num" type="radio" data-column="3" value="" name="column1">
          <img src="<?php echo JURI::root();?>/administrator/components/com_os_cck/images/3col.png" alt="">
        </label>
        <label class="columns">
          <input class="culumn-num" type="radio" data-column="4" value="" name="column1">
          <img src="<?php echo JURI::root();?>/administrator/components/com_os_cck/images/4col.png" alt="">
        </label>
      </div>
    </div>
  </div>
</div>
<!-- Layout Modal -->