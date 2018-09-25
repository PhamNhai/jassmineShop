<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;
// load tooltip behavior
JHtml::_('behavior.tooltip');

$sortFields = $this->getSortFields();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$user = JFactory::getUser();
$checkUpdate = baformsHelper::checkUpdate($this->about->version);
$language = JFactory::getLanguage();
$language->load('com_languages', JPATH_ADMINISTRATOR);
?>
<link rel="stylesheet" href="components/com_baforms/assets/css/ba-admin.css" type="text/css"/>
<script src="components/com_baforms/assets/js/ba-about.js" type="text/javascript"></script>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
        Joomla.tableOrdering(order, dirn, '');
	}
    var str = "<div class='btn-wrapper' id='toolbar-language'>";
    str += "<button value='About' class='btn btn-small'><span class='icon-star'>";
    str += "</span><?php echo $language->_('COM_LANGUAGES_HEADING_LANGUAGE'); ?></button></div>";
    str += "<div class='btn-wrapper' id='toolbar-about'>";
    str += "<button value='About' class='btn btn-small'><span class='icon-bookmark'>";
    str += "</span><?php echo JText::_('ABOUT') ?></button></div>";
    jQuery('#toolbar').append(str);
</script>
<?php if (!$checkUpdate) { ?>
<div class="ba-update-message">
    <h3><?php echo JText::_('UPDATE_AVAILABLE') ?></h3>
    <p><?php echo JText::_('UPDATE_MESSAGE') ?></p>
    <input type="button" class="update-link ba-btn-primary" value="<?php echo JText::_('UPDATE') ?>">
</div>
<div id="update-dialog" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <h3>Account login</h3>
    </div>
    <div class="modal-body">
        <div id="form-update">
            
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<div id="message-dialog" class="ba-modal-sm modal hide" style="display:none">
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<?php } ?>
<div id="language-message-dialog" class="ba-modal-sm modal hide" style="display:none">
    <div class="modal-body">
        <p><?php echo $language->_('COM_LANGUAGES_VIEW_INSTALLED_TITLE'); ?></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<div id="language-dialog" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <h3><?php echo $language->_('COM_LANGUAGES_INSTALL'); ?></h3>
    </div>
    <div class="modal-body">
        <iframe src="https://www.balbooa.com/demo/index.php?option=com_baupdater&view=baforms&layout=language"></iframe>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>
<form action="<?php echo JRoute::_('index.php?option=com_baforms'); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div id="about-dialog" class="modal hide ba-modal-md" style="display:none">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">x</a>
            <h3><?php echo JText::_('ABOUT') ?></h3>
        </div>
        <div class="modal-body">
            <div id="form-about">
                <div class="update-message" id="update-message" <?php
                    if ($checkUpdate) {
                     ?>style="display:none"<?php 
                    } ?>>
                    <?php if (!$checkUpdate) { ?>
                        Your Forms version is outdated.
                        <input type="button" class="update-link ba-btn-primary" value="<?php echo JText::_('UPDATE') ?>">
                    <?php } ?>
                </div>
                <label>Website:</label>
                <a target="_blank" href="<?php echo $this->about->authorUrl; ?>">www.balbooa.com</a><br>
                <label>License:</label>
                GNU Public License version 2.0. <br>
                <label>Copyright:</label>
                © 2016 Balbooa All Rights Reserved. <br>
                <label>Email:</label>
                <?php echo $this->about->authorEmail; ?><br>
                <label>Version:</label>
                <span class="update"> <?php echo $this->about->version; ?>
                </span>
                <br>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="upload-dialog" class="ba-modal-md modal hide" style="display:none">
        <div class="modal-header">
            <h3><?php echo JText::_('UPLOAD_FORM') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid tab-pane" id="import-theme">
                <input type="file" name="ba-files[]">
                <input type="button" class="ba-btn-primary" value="<?php echo JText::_('UPLOAD') ?>"
                       onclick="Joomla.submitbutton('forms.uploadTheme')">
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div id="error-dialog" class="ba-modal-sm modal hide" style="display:none">
        <div class="modal-body">
            <p class="not-selected"><?php echo JText::_('ITEM_NOT_SELECTED'); ?></p>
        </div>
        <div class="modal-footer">
            <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
        </div>
    </div>
    <div class="span2" id="j-sidebar-container">
        <?php echo $this->sidebar; ?>
    </div>
    <div class="span10" id="j-main-container">
        <div id="filter-bar">
            <label for="filter_search" class="element-invisible">
                <?php echo JText::_('SEARCH_FORM?') ?>
            </label>
            <input type="text" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   placeholder="<?php echo JText::_('SEARCH') ?>">
            <button type="submit" class="ba-btn " 
                    >
                <i class="icon-search"></i>
            </button>
            <button type="button" class="ba-btn" onclick="document.id('filter_search').value='';this.form.submit();">
                <i class="icon-remove"></i>
            </button>
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
            <div class="btn-group pull-right hidden-phone">
                <select name="filter_state" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('SELECT_STATUS');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('baformshtml.filters.stateOptions'),
                                        'value', 'text', $this->state->get('filter.state'), true);?>
                </select>
            </div>
        </div>
        <div class="clearfix"> </div>
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="1%">
                            <input type="checkbox" name="checkall-toggle" value=""
                                   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="1%" class="nowrap center">
							<?php echo JText::_('JSTATUS'); ?>
						</th>
                        <th>
                            <?php echo JText::_('Forms'); ?>
                        </th>
                        <th width="1%">
                            <?php echo JText::_('ID'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($this->items as $i => $item) : 
                        $canChange  = $user->authorise('core.edit.state', '.forms.' . $item->id); ?>
                    <tr>
                        <td>
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'forms.', $canChange); ?>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_baforms&task=form.edit&id='. $item->id); ?>">
                                <?php echo $item->title; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $item->id; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>