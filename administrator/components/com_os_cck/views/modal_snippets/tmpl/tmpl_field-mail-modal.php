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
<!-- Mail field Modal -->
<div class="modal fade" id="field-mail-modal" tabindex="-1" role="dialog" aria-labelledby="field-mail-modal-Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" id="field-mail-modal-Label"><?php echo JText::_("COM_OS_CCK_LABEL_MAIL_FIELD_MODAL")?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <span class="col-lg-7"><label>Title</label></span>
          <span class="col-lg-3"><label>Mask</label></span>
          <span class="col-lg-2"><label>ID</label></span>
        </div>
        <?php
        if(isset($layout->field_mask_list)){
          foreach ($layout->field_mask_list as $key => $value) { ?>
           <?php
            $lay_params = unserialize($layout->params);
            $field_title = isset($lay_params['fields'][$value->title.'_alias'])?$lay_params['fields'][$value->title.'_alias']:'';?>
            <div id="field-mail-row-<?php echo $value->fid; ?>" class="row"
                  onclick="addMask('<?php echo $value->mask; ?>');">
              <span class="col-lg-7"><?php echo !empty($field_title)?$field_title:$value->title; ?></span>
              <span class="col-lg-3"><?php echo $value->mask; ?></span>
              <span class="col-lg-2"><?php echo $value->fid; ?></span>
            </div>
            <?php
          }
        } ?>
      </div>
    </div>
  </div>
</div>
<!-- Mail field Modal -->