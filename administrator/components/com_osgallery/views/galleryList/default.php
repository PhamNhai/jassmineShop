<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div id="galleries-block">
        <div class="galleries-header">
            <span class="span1 col-1">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
            </span>
            <span class="span1 col-2"><?php echo JText::_("COM_OSGALLERY_LIST_HEADER_ID")?></span>
            <span class="span4 col-3"><?php echo JText::_("COM_OSGALLERY_LIST_HEADER_TILE")?></span>
            <span class="span3 col-4"><?php echo JText::_("COM_OSGALLERY_LIST_HEADER_PUBLISHED")?></span>
            <span class="span3 col-5 short-code"><?php echo JText::_("COM_OSGALLERY_LIST_HEADER_SHORT_CODE")?>
                <span class="short-code-tooltip">
                    You can copy this shortcode and paste into article or custom html module for showing OS Gallery.
                </span>
                <i class="material-icons">help_outline</i>
            </span>
        </div>
        <?php
        if($galleries){
            foreach ($galleries as $i => $gallery) {
                $canChange  = $user->authorise('core.edit.state', 'com_osgallery');
                ?>
                <div class="gallery-block">
                    <span class="span1 col-1">
                        <?php echo JHtml::_('grid.id', $i, $gallery->id, false, 'galId'); ?>
                    </span>
                    <span class="span1 col-2">
                        <?php
                        if (JFactory::getUser()->authorise('core.edit', 'com_bagallery')) {
                        ?>
                        <a href="index.php?option=com_osgallery&task=edit_gallery&galId=<?php echo $gallery->id?>" class="editable-title">
                            <?php echo $gallery->id; ?>
                        </a>
                        <?php
                        }else{
                            echo $gallery->id;
                        }?>
                    </span>
                    <span class="span4 col-3">
                        <?php
                        if (JFactory::getUser()->authorise('core.edit', 'com_bagallery')) {
                        ?>
                        <a href="index.php?option=com_osgallery&task=edit_gallery&galId=<?php echo $gallery->id?>" class="editable-title">
                            <?php echo $gallery->title; ?>
                        </a>
                        <?php
                        }else{
                            echo $gallery->title;
                        }?>
                    </span>
                    <span class="span3 col-4">
                        <?php echo JHtml::_('jgrid.published', $gallery->published, $i, '', $canChange); ?>
                    </span>
                    <span class="span3 col-2">{os-gal-<?php echo $gallery->id?>}</span>
                </div>
            <?php
            }
        }?>
    </div>
    <input type="hidden" name="option" value="com_osgallery"/>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="new_gallery"/>
    <input type="hidden" name="with_image" value="0"/>
</form>

<div id="confirm-modal" class="modal hide fade">
  <div class="modal-body">
    <p>You want clone gallery with image?</p>
  </div>
  <div class="modal-footer">
    <a id="with-image" href="#" class="btn">With Image</a>
    <a id="no-image" href="#" class="btn btn-primary">Empty Gallery</a>
  </div>
</div>

<div id="confirm-delete-modal" class="modal hide fade">
  <div class="modal-body">
    <p>Do you really want to delete gallery? You can't undo this action.</p>
  </div>
  <div class="modal-footer">
    <a id="yes-delete" href="#" class="btn">Yes</a>
    <a id="no-delete" href="#" class="btn btn-primary">No</a>
  </div>
</div>

<div id="about-modal" class="modal hide fade">
  <div class="modal-body">
    <div class="about-image">
        <img src="<?php echo JURI::base().'components/com_osgallery/assets/images/os-image-gallery.png'?>" alt="OS Gallery">
    </div>
  </div>
  <div class="modal-footer">
        <span class="span3">
            <label class="about-col-1"><?php echo JText::_("COM_OSGALLERY_VERSION")?></label><span id="gallery-version" class="about-col-2"><?php echo $galV.' Light'?></span>
        </span>
        <span class="span3">
            <label class="about-col-1"><?php echo JText::_("COM_OSGALLERY_CREATION_DATE")?></label><span id="gallery-version" class="about-col-2"><?php echo $creationDate?></span>
        </span>
        <span class="span3">
            <?php if($avaibleUpdate){?>
                <a target="_blank" href="<?php echo $updateArticleUrl?>"><label class="about-col-1 new-version">New version: <span class="version-osgallery"><?php echo $ordasoftGalV?></span> of Gallery avaible</label></a>
            <?php }?>
        </span>
        <span class="span3 submit-close">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        </span>
  </div>
</div>

<script>
    Joomla.submitbutton = function(pressbutton) {
        document.adminForm.task.value = pressbutton;
        if(pressbutton == 'clone_gallery'){
            jQuery("#confirm-modal").modal('show');
            jQuery("#with-image").click(function(event) {
                document.adminForm.with_image.value = 1;
                jQuery("#confirm-modal").modal('hide');
                document.adminForm.submit();
            });

            jQuery("#no-image").click(function(event) {
                document.adminForm.with_image.value = 0;
                jQuery("#confirm-modal").modal('hide');
                document.adminForm.submit();
            });
            return;
        }if(pressbutton == 'delete_gallery'){
            jQuery("#confirm-delete-modal").modal('show');
            jQuery("#yes-delete").click(function(event) {
                jQuery("#confirm-delete-modal").modal('hide');
                document.adminForm.submit();
            });

            jQuery("#no-delete").click(function(event) {
                jQuery("#confirm-delete-modal").modal('hide');
                return;
            });
            return;
        }else if(pressbutton == "about_gallery"){
            jQuery("#about-modal").modal('show');
            return;
        }else{
            document.adminForm.submit();
        }
    }

    function listItemTask(id, task, frmName){
        var form = document.adminForm;
        cb = eval( id );
        if (cb) {
            cb.checked = true;
            form.task.value = task;
            form.submit();
        }
        return false;
    }

    jQuery(document).ready(function(){
        setTimeout(function(){
            jQuery("#system-message-container").empty();
        }, 3000);
    });
</script>