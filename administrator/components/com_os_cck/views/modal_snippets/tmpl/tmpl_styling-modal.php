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
<!-- Styling Modal -->
<div class="modal fade" id="styling-modal" tabindex="-1" role="dialog" aria-labelledby="styling-modal-Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" id="styling-modal-Label"><?php echo JText::_("COM_OS_CCK_STYLING")?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <?php
          styling_options($fields, $fields_from_params);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Styling Modal -->
