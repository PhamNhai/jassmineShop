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

class AdminViewEntity
{


    static function showEntities($option, $rows, $pageNav)
    {

        global $doc, $app;
        $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_("COM_OS_CCK_ADMIN_ENTITIES") . "</div>";
        $app = JFactory::getApplication();
        $app->JComponentTitle = $html;
        ?>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist filters">
                <tr>
                    <th width="3%" align="center">
                        <?php
                        $onclick = (version_compare(JVERSION, "1.6.0", "lt")) ? "checkAll(" . count($rows) . ");" : "Joomla.checkAll(this);";
                        ?>
                        <input type="checkbox" name="toggle" value="" onClick="<?php echo $onclick; ?>"/>
                    </th>
                    <th align="left" class="title" width="10%"
                        nowrap="nowrap"><?php echo JText::_("COM_OS_CCK_ENTITIES_NAME"); ?></th>
                    <th align="center" class="title" width="10%"
                        nowrap="nowrap"><?php echo JText::_("COM_OS_CCK_ENTITIES_PUBLISHED"); ?></th>
                    <?php if (version_compare(JVERSION, "3.0.0", "ge")) {  ?>
                    <th width="5%" align="left">
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit"
                                   class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                            <?php echo $pageNav->getLimitBox(); ?>
                        </div>
                    </th>
            <?php } ?>
                </tr>

                <?php   $i = 0;
                foreach ($rows AS $row) {

                    $onclick = (version_compare(JVERSION, "1.6.0", "lt")) ? "isChecked(this.checked);" : "Joomla.isChecked(this.checked);";
                    ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td align="center">
                            <input type="checkbox" id="cb<?php echo $i; ?>" name="eid[]"
                                   value="<?php echo $row->eid; ?>" onClick="<?php echo $onclick; ?>"/>
                        </td>

                        <td align="left" width="10%">
                            <a href="#edit_entety"
                               onClick="return listItemTask('cb<?php echo $i; ?>','edit_entity')"><?php echo $row->name; ?>
                            </a>
                        </td>
                        <?php
                        $task = $row->published ? 'unpublish_entities' : 'publish_entities';
                        $alt = $row->published ? 'Unpublish' : 'Publish';
                        $img = $row->published ? 'tick.png' : 'publish_x.png';
                        $img = "components/com_os_cck/images/{$img}";
                        ?>

                        <td align="center">
                            <?php ?>
                            <a href="javascript: void(0);"
                               onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                                <img src="<?php echo $img; ?>" width="12" height="12" border="0"
                                     alt="<?php echo $alt; ?>"/>
                            </a>
                        </td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                }

                ?>

                <tr>
                    <td colspan="11"><?php echo $pageNav->getListFooter(); ?></td>
                </tr>
            </table>
            <input type="hidden" name="option" value="<?php echo $option; ?>"/>
            <input type="hidden" name="task" value="manage_entities"/>
            <input type="hidden" name="boxchecked" value="0"/>
        </form>
    <?php
    }


    static function editEntity($option, &$row)
    {
        global $doc;
        $app = JFactory::getApplication();
        $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_("COM_OS_CCK_ADMIN_ENTITIES") . "</div>";
        $app->JComponentTitle = $html;
        ?>
        <script language="javascript" type="text/javascript">
            Joomla.submitbutton = function (pressbutton) {
                var form = document.adminForm;
                if (pressbutton == "cancel_edit_entity") {
                    submitform(pressbutton);
                    return true;
                }
                if (form.name.value == '') {
                    alert("<?php echo "Entity name cant be empty"; ?>");
                    return;
                } else {
                    submitform(pressbutton);
                }
            }
        </script>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <div class="cck-add-entity-block">
                <div>
                    <label><?php echo JText::_("COM_OS_CCK_ENTITIES_NAME"); ?></label>
                    <span class="cck-col-2">
                        <input class="text-input" type="text" name="name" value="<?php echo $row->name; ?>"/>
                    </span>
                </div>
            </div>
            <input type="hidden" name="option" value="com_os_cck"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="eid" value="<?php echo $row->eid; ?>"/>
        </form>
    <?php
    }
}
