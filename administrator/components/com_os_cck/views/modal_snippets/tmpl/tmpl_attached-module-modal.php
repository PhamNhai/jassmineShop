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
<!-- Attached Module Modal -->
<div class="modal fade" id="attached-module-modal" tabindex="-1" role="dialog" aria-labelledby="attached-module-modal-Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" id="attached-module-modal-Label"><?php echo JText::_("COM_OS_CCK_ATTACHED_MODULE_MODAL_TITLE")?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <span class="col-lg-6"><label>Title</label></span>
          <span class="col-lg-4"><label>Type</label></span>
          <span class="col-lg-2"><label>ID</label></span>
        </div>
        <?php
        foreach ($layout->module_list as $key => $value) { ?>
          <div id="attached-module-row-<?php echo $value->lid; ?>" class="row"
                onclick="addAttachedModule('<?php echo $value->id; ?>','<?php echo $value->title; ?>');">
            <span class="col-lg-6"><?php echo $value->title; ?></span>
            <span class="col-lg-4"><?php echo $value->type; ?></span>
            <span class="col-lg-2"><?php echo $value->id; ?></span>
          </div>
          <?php
        } ?>
      </div>
    </div>
  </div>
</div>
<!-- Attached Module Modal -->