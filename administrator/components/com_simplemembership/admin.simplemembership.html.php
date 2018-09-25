<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package simpleMembership
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Anton Getman(ljanton@mail.ru);
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 PRO
 *
 *
 */
//require_once ($mosConfig_absolute_path . "/libraries/joomla/factory.php");
require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
/**
 *
 * @package simplemembership
 *
 *
 */
// ensure this file is being included by a parent file
$bid = mosGetParam($_POST, 'bid', array(0));
require_once ($mosConfig_absolute_path . "/administrator/components/com_simplemembership/admin.simplemembership.class.others.php");
function catOrderDownIcon($i, $n, $index, $task = 'orderdown', $alt = 'Move Down') {
    if ($i < $n - 1) {
        return '<a href="#reorder" onclick="return listItemTask(\'cb' . $index . '\',\'' . $task . '\')" title="' . $alt . '">
            <img src="images/downarrow.png" width="12" height="12" border="0" alt="' . $alt . '" />
        </a>';
    } else {
        return '&nbsp;';
    }
}
function catOrderUpIcon($i, $index, $task = 'orderup', $alt = 'Move Up') {
    if ($i > 0) {
        return '<a href="#reorder" onclick="return listItemTask(\'cb' . $index . '\',\'' . $task . '\')" title="' . $alt . '">
            <img src="images/uparrow.png" width="12" height="12" border="0" alt="' . $alt . '" />
        </a>';
    } else {
        return '&nbsp;';
    }
}
class HTML_Categories {
    static function show($rows, $pageNav) {
        global $my, $mainframe, $mosConfig_live_site;
        $section = "group";
        $section_name = "simplemembershipLibrary";
        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');
        ?>
        <form id="adminForm" name="adminForm" action="index.php" method="post" >
        <table cellpadding="4" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="30%"></td>
                <td width="70%" class="simplemembership_manager_caption" valign='bottom'>
                    <?php echo  JText::_("COM_SIMPLEMEMBERSHIP_CATEGORIES_MANAGER") ; ?>
                </td>
                <?php
                if (version_compare(JVERSION, '3.0.0', 'ge')) { ?>
                    <td>
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit" class="element-invisible">
                                <?php
                                echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');
                                ?>
                            </label>
                            <?php
                            echo $pageNav->getLimitBox(); ?>
                        </div>
                    </td>
                <?php
                } ?>
            </tr>
        </table>
        <table class="adminlistgroups">
            <tr class="cat-header">
                <th width="20">
                    <input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" />
                </th>
                <th align="center" class="title" width="20%"><?php echo  JText::_(
                    "COM_SIMPLEMEMBERSHIP_ADMIN_GROUP_NAME") ; ?></th>
                <th align="center" width="15%" ><?php echo  JText::_(
                    "COM_SIMPLEMEMBERSHIP_ADMIN_GROUP_ACLGROUPNAME") ; ?></th>
                <th align="center" width="10%"><?php echo  JText::_(
                    "COM_SIMPLEMEMBERSHIP_ADMIN_GROUP_INTERVAL") ; ?></th>
                <th align="center" width="10%"><?php echo  ucfirst(strtolower(JText::_(
                    "COM_SIMPLEMEMBERSHIP_ORDER_PRICE"))); ?></th>
                <th align="center" width="10%"><?php echo  JText::_(
                    "COM_SIMPLEMEMBERSHIP_AUTO_APPROVE"); ?></th>
                <th align="center" ><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_GROUP_NOTES") ; ?></th>
                <th align="center" width="40px" ><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_GROUP_PUBLISH") ; ?></th>
            </tr>
            <?php
            $k = 0;
            $i = 0;
            foreach($rows as $row) { ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td width="20"><?php echo mosHTML::idBox($i, $row->id, false, 'bid'); ?></td>
                    <td align="center">
                        <a href="#edit" onClick="return listItemTask('cb<?php echo $i; ?>','edit');">
                            <?php echo $row->name; ?>
                        </a>
                    </td>
                    <td align="center"><?php echo $row->acl_group; ?></td>
                    <td align="center"><?php echo $row->expire_range.' '.$row->expire_units; ?></td>

                    <td><?php  echo $row->price . ' ' . $row->currency_code;  ?> </td>

                    <?php $approve = $row->auto_approve;

                        switch ($approve) {

                            case '0':
                            $approve = JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_MANUAL");
                            break;
                            case '1':
                            $approve = JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_AUTO");
                            break;
                            case '2':
                            $approve = JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE_AFTER_PRODUCT_BUY");
                            break;
                        }?>

                    <td><?php echo $approve;?></td>
                    <td align="center"><?php echo $row->notes; ?></td>
                    
                    <td align="center"><?php
                        $task = $row->published ? 'unpublish' : 'publish';
                        $alt = $row->published ? 'Publish' : 'Unpublish';
                        $icon = $row->published ? 'icon-publish' : 'icon-unpublish';
                        $img = $row->published ? 'tick.png' : 'publish_x.png';
                        if (version_compare(JVERSION, '3.0.0', 'lt')) { ?>
                            <a href="javascript: void(0);"
                                onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                            <img src="templates/bluestork/images/admin/<?php echo $img; ?>" width="12" height="12"
                                border="0" alt="<?php echo $alt; ?>" /> </a>
                    </td>
                        <?php
                        } else { ?>  
                            <a  class="btn btn-micro active" href="javascript: void(0);"
                                    onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')"
                                    data-original-title="<?php echo $alt; ?>">
                                <i class="<?php echo $icon; ?>"></i>
                            </a>
                    </td>
                        <?php
                        }
                    $k = 1 - $k; ?>
                </tr>
                <?php
                $k = 1 - $k;
                $i++;
            } ?>
        </table>
        <input type="hidden" name="option" value="com_simplemembership" /> 
        <input type="hidden" name="section" value="group" /> 
        <input type="hidden"    name="task" value="" /> 
        <input type="hidden" name="chosen" value="" />
        <input type="hidden" name="act" value="" /> 
        <input type="hidden"    name="boxchecked" value="0" />
        </form>
<?php
    }
    /**
     * Writes the edit form for new and existing categories
     *
     * @param mosCategory $ The category object
     * @param string $
     * @param array $
     */
    static function edit($section, &$lists, $redirect, $row) {
        global $my, $mosConfig_live_site, $mainframe, $option,$user_configuration;
        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');
        ?>
        
        <script language="javascript" type="text/javascript">
            Joomla.submitbutton = function (pressbutton, section) {
                var form = document.adminForm;
                if (pressbutton == 'cancel') {
                    submitform( pressbutton );
                    return;
                }
                if (!form.acl_group_select.value) {
                    alert("<?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_MUST_SELECT_JOOMLA_GROUP") ; ?>");
                    return;
                }
                if(form.auto_approve.value == 2 && !form.price.value){
                    alert("<?php echo  JText::_("COM_SIMPLEMEMBERSHIP_NEED_GROUP_PRICE") ; ?>");
                    return;
                }
                if (!form.title.value) {
                    alert("<?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_MUST_SELECT_NAME") ; ?>");
                    return;
                } else {
                    submitform(pressbutton);
                }
            }
   
            function approveValueChange(){
                var form = document.adminForm
                if(form.auto_approve.value == '1'){
                    form.price.value = '';
                    form.price.readOnly = true;
                    form.currency.readOnly = true;
                }else{
                    form.price.readOnly = false;
                    form.currency.readOnly = false;
                }
            }
            if (document.addEventListener)
                document.addEventListener("DOMContentLoaded", expireValueChange, false)
        </script>
        <form id="adminForm" name="adminForm" action="index.php" method="post" >
            <table>
                <tr>
                    <th class="simplemembership_manager_caption" align="left"><?php
                        if (is_object($row)) echo $row->id ?  JText::_("COM_SIMPLEMEMBERSHIP_HEADER_EDIT")  :  JText::_("COM_SIMPLEMEMBERSHIP_HEADER_ADD") ; ?>
                        <?php echo  JText::_("COM_SIMPLEMEMBERSHIP_CATEGORY") ; ?> <?php if (is_object($row)) echo $row->name; ?>
                    </th>
                </tr>
            </table>
            <table class="adminform" width="60%">
                <tr>
                    <th colspan="2"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_CATEGORIES__DETAILS") ; ?></th>
                </tr>
                <tr>
                    <td width="350px"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_EDIT_TITLE") ; ?>:</td>
                    <td align="left"><input class="text_area" type="text" name="title"
                                            value="<?php if (is_object($row)) echo $row->name; ?>" size="50" /></td>
                </tr>
                <tr>
                    <td width="350px"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_EDIT_ACLSELECT") ; ?>:</td>
                    <td align="left"><?php echo $lists['acl_group_select']; ?></td>
                </tr>
         
                <tr>
                    <td width="350px"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_AUTO_APPROVE") ; ?>:</td>
                    <td align="left"><?php echo $lists['auto_approve']; ?></td>
                </tr>
                <tr>
                    <td width="350px"><?php echo 'Price' ?>:</td>
                    <td align="left"><input class="text_area" type="text" name="price"
                                            value="<?php if (is_object($row)) echo $row->price;?>" size="50" /></td>
                </tr>
                <tr>
                    <td width="350px"><?php echo 'Currency' ?>:
                        <span style="float:right;margin-bottom: 10px;">
                            <?php
                            echo mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_SHOW_CURRENCY_VALUE") );
                            ?>
                        </span>
                    </td>
                    <td align="left">
                        <input class="text_area" type="text" name="currency"
                                            value="<?php if (is_object($row)){echo $row->currency_code;}
                                                            else{echo"USD";} ?>" size="50" />
                    </td>
                </tr>
                <tr>
                    <td width="350px"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_LINK") ; ?>:</td>
                    <td align="left">
                    <input class="text_area" type="text" name="group_link"
                            value="<?php if (is_object($row))
                            echo $mosConfig_live_site.
                                    '/index.php?option=com_simplemembership&task=advregister&group_selected='.
                                    $row->name; ?>"
                            size="50" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_GROUP_EDIT_NOTES") ; ?>:</td>
                </tr>
                <tr>
                    <td colspan="2"><?php
                        if (is_object($row)) editorArea('editor1', $row->notes, 'notes', '500', '200', '50', '5');
                        else editorArea('editor1', '', 'notes', '500', '200', '50', '5');?>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="option" value="com_simplemembership" />
            <input type="hidden" name="section" value="group" /> 
            <input type="hidden" name="task" value="" /> 
            <input type="hidden" name="id" value="<?php echo (is_object($row))?$row->id:''; ?>" > 
            <input type="hidden" name="sectionid" value="com_simplemembership" /> 
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        </form>
            <?php
    }
}
class HTML_simplemembership {
    static function add_acl_group_form($option, $lists) {
        global $user_configuration, $my, $mosConfig_live_site, $mainframe;
        $doc = JFactory::getDocument(); ?>
        <script language="javascript" type="text/javascript">
            function trim(string){
               return string.replace(/(^\s+)|(\s+$)/g, "");
            }
            function submitbutton(pressbutton) {
               submitform( pressbutton );
            }
        </script>
        <form action="index.php?option=<?php echo $option; ?>" method="post" name="adminForm" id="adminForm"
                                                                                charset=enctype="multipart/form-data">
            <table cellpadding="4" cellspacing="1" border="0" width="100%"    class="adminform">
                <tr>
                    <td valign="top" align="left"><strong><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_PARENT_GROUP") ; ?>:</strong>
                    </td>
                    <td align="left"><?php echo $lists['acl_group_select']; ?></td>
                </tr>
                <tr>
                    <td valign="top" align="left"><strong><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ACLNAME") ; ?>:</strong>
                    </td>
                    <td align="left"><input class="inputbox" type="text" name="acl_name"    size="80" value="" /></td>
                </tr>
            </table>
            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" name="task" value="" />
        </form>
    <?php
    }
    static function del_acl_group_form($option, $lists) {
        global $user_configuration, $my, $mosConfig_live_site, $mainframe;

        $doc = JFactory::getDocument(); ?>
        <script language="javascript" type="text/javascript">
            function trim(string){
               return string.replace(/(^\s+)|(\s+$)/g, "");
            }
            function submitbutton(pressbutton) {
               submitform( pressbutton );
            }
        </script>
        <form action="index.php?option=<?php echo $option; ?>&task=del_acl_group" method="post"
                                                    name="adminForm" id="adminForm"    enctype="multipart/form-data">
            <table cellpadding="4" cellspacing="1" border="0" width="100%"    class="adminform">
                <tr>
                    <td valign="top" align="left"><strong><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_TODELETE_GROUP") ; ?>:</strong>
                    </td>
                    <td align="left"><?php echo $lists['custom_groups']; ?></td>
                </tr>         
            </table>
            <input type="hidden" name="boxchecked" value="1" />
            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" name="task" value="del_acl_group" />
        </form>
    <?php
    }
    static function showUsers($option, $rows, &$publist, &$search, &$pageNav, $olist, $enablelist) {
        global $my, $mosConfig_live_site, $mainframe, $session,$user_configuration,$database;


        // for 1.6
        global $doc;
        $doc = JFactory::getDocument();
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
            JHTML::_("behavior.mootools");
        } else {
            JHtml::_('behavior.framework');
        }
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');
    ?>
        <form action="index.php" method="post" id="adminForm" name="adminForm">
        <script type="text/javascript">
            function sync_user(params_for_sync) {
                var url_for_sync= '<?php echo $mosConfig_live_site ?>/administrator/index.php';
                var data_for_url=new String();
                data_for_url+='format='+params_for_sync['format']+'&';
                data_for_url+='option='+params_for_sync['option']+'&';
                data_for_url+='task='+params_for_sync['task']+'&';
                data_for_url+='params_for_sync[sub_task]='+params_for_sync['sub_task']+'&';
                data_for_url+='params_for_sync[ia]='+params_for_sync['ia']+'&';
                data_for_url+='params_for_sync[ia_d]='+params_for_sync['ia_d']+'&';
                data_for_url+='params_for_sync[count_adv_users]='+params_for_sync['count_adv_users']+'&';
                data_for_url+='params_for_sync[ij]='+params_for_sync['ij']+'&';
                data_for_url+='params_for_sync[count_joom_users]='+params_for_sync['count_joom_users']+'&';
                data_for_url+='params_for_sync[ie]='+params_for_sync['ie']+'&';
                data_for_url+='params_for_sync[ie_d]='+params_for_sync['ie_d']+'&';
                data_for_url+='params_for_sync[count_exp_users]='+params_for_sync['count_exp_users']+'&';
                data_for_url+='params_for_sync[status]='+params_for_sync['status'];
                ajaxxx = new Request.JSON({
                    url:url_for_sync,
                    method: 'post',
                    data: data_for_url,
                    update:update_divs(params_for_sync),
                    onSuccess: function(response) {
                        if(response){
                            var data_query = response;
                            if(!data_query) alert('ERROR!!!'+response);
                            if(data_query['status']=='sync_user_ok') {
                                data_query['sub_task']='expire';
                                data_query['task']='sync_ajax';
                                sync_user(data_query);
                            }
                            else if(data_query['status']=='exp_user_ok') {
                                $('sync_button').disabled = false;
                                $('loader_user').setStyle('display', 'none');
                                alert('Users were synchronized, and now the page will be reloaded');
                                window.location.reload();
                            }
                            else if(data_query['status']=='0'){
                                sync_user(data_query);
                            }
                        }
                        else alert('ERROR');
                    },
                    onFailure: function(text){ alert('ERROR'+text); },
                    onError:function(text){ alert('ERROR'+text); }
                }).send();
            }
            function update_divs(params_for_sync){
                var proccess_msg;
                var proccess_ia=make_percent(params_for_sync['ia'],params_for_sync['count_adv_users']);
                var proccess_ij=make_percent(params_for_sync['ij'],params_for_sync['count_joom_users']);
                var proccess_ie=make_percent(params_for_sync['ie'],params_for_sync['count_exp_users']);
                proccess_msg='COMPLETED:</br> Memebership users: '+
                    proccess_ia+'% </br> JOOMLA user: '+
                    proccess_ij+'% </br> Expired users: '+
                    proccess_ie+'%';
                $('loader_user').setStyle('display', 'block');
                $('status_user').innerHTML='<b style="color:red">Please wait DO NOT BROWSE</b></br>'+proccess_msg;
                $('sync_button').disabled = true;
            }
            function make_percent(i_users,count_users){
                var return_percent;
                if(count_users <= 100){
                    if(i_users >0){
                        return_percent=100;
                    }else
                        return_percent=0;
                }else {
                    return_percent=(i_users*100*100)/count_users;
                }
                if(return_percent >100) return_percent=100;
                return Math.floor(return_percent);
            }
            function start_sync(){
                var params_for_sync=new Array();
                params_for_sync['format']='raw';
                params_for_sync['option']='com_simplemembership';
                params_for_sync['task']='sync_ajax';
                params_for_sync['sub_task']='sync';
                params_for_sync['ia']='0';
                params_for_sync['ia_d']='0';
                params_for_sync['count_adv_users']='0';
                params_for_sync['ij']='0';
                params_for_sync['count_joom_users']='0';
                params_for_sync['ie']='0';
                params_for_sync['ie_d']='0';
                params_for_sync['count_exp_users']='0';
                params_for_sync['status']='0';
                sync_user(params_for_sync);
            }
        </script> 

        <table cellpadding="4" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="30%"></td>
                <td width="40%" class="simplemembership_manager_caption" valign='bottom' align="center">
                    <?php
                    echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_USERS") ;
                    ?>
                </td>
                <td width="30%" valign='bottom' align="right">
                    <div id="status_user" style="font-size:17px;"></div>
                    <div id="loader_user" style="display:none;">
                        <img src="<?php echo $mosConfig_live_site; ?>/components/com_simplemembership/images/loader-line.gif">
                    </div>
                    <input type="button" value="Sync users" id="sync_button" onclick="start_sync();">
                </td>
            </tr>
        </table>
        <table cellpadding="4" cellspacing="0" border="0" width="100%"
            class="adminlist">
            <tr>
                <td><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_SHOW_SEARCH") ; ?></td>
                <td>
                    <input type="text" name="search" value="<?php echo $search; ?>"
                            class="inputbox" onChange="document.adminForm.submit();" />
                </td>
                <td><?php echo $publist; ?></td>
                <td><?php echo $olist; ?></td>
                <td><?php echo $enablelist; ?></td>
                <?php
                if (version_compare(JVERSION, '3.0.0', 'ge')) { ?>
                    <td>
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit" class="element-invisible"><?php
                                echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
                            </label>
                            <?php
                            echo $pageNav->getLimitBox(); ?>
                        </div>
                    </td>
                <?php
                } ?>
            </tr>
        </table>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlistusers">
            <tr class="cat-header">
                <th width="20"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERNAME") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USEREMAIL") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERGROUP") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERWANTED_STATUS") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERENABLED") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERAPPROVED") ; ?></th>
                <th align="center" class="title" nowrap="nowrap"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_LABEL_USERLASTAPPROVED") ; ?></th>
            </tr>
            <?php
            //// Default group
                $default_group = $user_configuration['default_group'];
                if (empty($default_group ) )
                {
                  $default_group = array('2' => '2');
                  $default_group_name = 'Registered';
                } else {
                  $query = "SELECT ug.title FROM #__usergroups AS ug WHERE ug.id IN ( " . $default_group . " )";
                  $database->setQuery($query);
                  $dg=explode(",",$default_group);
                  $default_group_name = $database->loadColumn();
                  $default_group_name = implode(",", $default_group_name);
                  $default_group = array_combine($dg, $dg);
                }
            for ($i = 0, $n = count($rows);$i < $n;$i++) {
                $row = $rows[$i];
                ///start filter for default and other group
                if(isset($_POST['advgid']) && $_POST['advgid'] == -1 
                    && ($default_group_name == $row->jg_titles || $row->jg_titles == $row->acl_group))continue;

                if(isset($_POST['advgid']) && $_POST['advgid'] == 0
                    && !($default_group_name == $row->jg_titles || $row->jg_titles == $row->acl_group))continue;
                ////end
            ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td width="20" align="left">
                        <?php
                        echo mosHTML::idBox($i, $row->id, false, 'bid');
                        ?>
                    </td>
                    <td align="left">
                        <a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','edit_user')" >
                            <?php
                            echo $row->name;
                            ?>
                        </a>
                    </td>
                    <td align="center"><?php echo $row->email; ?></td>
                    <td align="center">
                        <?php 
                        // echo 111111;
                        // print_r($row);
                        // exit();


                        // echo ($default_group_name == $row->jg_titles || $row->jg_titles == $row->acl_group)? 
                           echo     $row->current_gname."(".$row->jg_titles.")";
                                    // 'Other'."(".$row->jg_titles.")";?>
                    </td>
                    <?php
                    $task = $row->block ? 'unpublish' : 'publish';
                    $alt = $row->block ? 'Publish' : 'Unpublish';
                    $img = $row->block ? 'publish_x.png' : 'tick.png';
                    $publish_ico = $row->block ? 'icon-unpublish' : 'icon-publish';
                    if (version_compare(JVERSION, '3.0.0', 'lt')) {
                    ?>

                        <td align="center">
                            <?php
                            if(is_array($row->want_group)) {
                                foreach($row->want_group as $want_group) {
                                    echo $want_group . " ";
                                } 
                            }else
                                echo $row->want_group;
                            ?>
                        </td>
                        <td width="5%" align="center"><a href="javascript: void(0);"
                            onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                        <img src="templates/bluestork/images/admin/<?php echo $img; ?>" width="12" height="12" border="0"
                            alt="<?php echo $alt; ?>" /> </a></td>
                    <?php
                    } else {
                    ?>
                        <td align="center">
                            <?php
                            if($row->want_gid)
                                echo $row->want_gname."(".$row->want_jg_titles.")";
                            ?>
                        </td>
                        <td width="5%" align="center">
                            <a class="btn btn-micro active" href="javascript: void(0);"
                                            onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')"
                                            rel="tooltip" data-original-title="<?php echo $alt; ?>">
                                <i class="<?php echo $publish_ico; ?>"></i>
                            </a>
                        </td>
                    <?php
                    }
                    $task = $row->approved ? 'unapprove' : 'approve';
                    $alt = $row->approved ? 'Unapprove' : 'Approve';
                    $img = $row->approved ?  'tick.png' : 'publish_x.png';
                    $publish_ico = $row->approved ? 'icon-publish' : 'icon-unpublish';
                    if (version_compare(JVERSION, '3.0.0', 'lt')) {
                    ?>
                        <td width="5%" align="center">
                            <a href="javascript: void(0);"
                                onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')">
                                <img src="templates/bluestork/images/admin/<?php echo $img; ?>" width="12"
                                                                    height="12" border="0" alt="<?php echo $alt; ?>" />
                            </a>
                        </td>
                        <td align="center">
                    <?php
                    } else {
                    ?>
                        <td width="5%" align="center">
                            <a class="btn btn-micro active" href="javascript: void(0);"
                                        onClick="return listItemTask('cb<?php echo $i; ?>','<?php echo $task; ?>')"
                                        rel="tooltip" data-original-title="<?php echo $alt; ?>">
                                <i class="<?php echo $publish_ico; ?>"></i>
                            </a>
                        </td>
                        <td align="center">
                    <?php
                    }
                    $date_temp = $row->last_approved;
                    if ($date_temp == '' || $date_temp == '0000-00-00') echo 'never';
                    else echo $date_temp; ?>
                        </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="13"><?php echo $pageNav->getListFooter(); ?></td>
            </tr>
        </table>
        <input type="hidden" name="option" value="<?php echo $option; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden"    name="boxchecked" value="0" />
    </form>
    <?php
    }
    /**
     * Writes the edit form for new and existing records
     *
     */
    static function editUser($option, &$row, &$olist, $bid, $msg, $user_profile_form) {
        global $user_configuration, $my, $mosConfig_live_site, $mainframe;

        $doc = JFactory::getDocument();
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
            JHTML::_("behavior.mootools");
        } else {
            JHtml::_('behavior.framework');
        }
        ?>
        <script language="javascript" type="text/javascript">
            function trim(string){
               return string.replace(/(^\s+)|(\s+$)/g, "");
            }
            function submitbutton(pressbutton) {
               submitform( pressbutton );
            }
        </script>

        <form action="index.php" method="post" name="adminForm" id="adminForm"
            enctype="multipart/form-data" class="form-validate" enctype="multipart/form-data">
            <fieldset class="adminform">
                <legend>
                  Account Details
                </legend>
                    <ul class="adminformlist">
                        <li>
                            <label id="name-lbl" class="hasTip required"  title="Name::Enter your full name" for="name">
                                Name:
                            <span class="star">&nbsp;*</span> 
                            </label>
                            <input name="name" id="name" size="40" value="<?php echo $row->name; ?>"
                                                                    class="inputbox required" maxlength="50" type="text"> 
                        </li>
                        <li>
                            <label id="username-lbl" for="username"  title="Username::Enter your desired user name"
                                   class="hasTip required">
                                Username:
                                <span class="star">&nbsp;*</span>
                            </label>
                            <input id="username" name="username" size="40" value="<?php echo $row->username; ?>"
                                                class="inputbox required validate-username" maxlength="25" type="text">
                        </li>
                        <li>
                            <label id="emailmsg" for="email" class="hasTip required"  title="Email Address::Enter your email address">
                                E-mail:
                                <span class="star">&nbsp;*</span>
                            </label>
                            <input id="email" name="email" size="40" value="<?php echo $row->email; ?>"
                                                class="inputbox required validate-email" maxlength="100" type="text">
                        </li>
                        <li>
                            <label id="mmsg" for="want_gid" class="hasTip" title="Group::Please select Member Group">
                                MemberGroup:
                            </label>
                            <?php echo $olist; ?>
                        </li>
                        <li>
                            <label id="pwmsg" for="password" class="hasTip <?php if ($bid == 0) echo "required"; ?>"
                                    title="Password::Enter your desired password - Enter a minimum of 4 characters">
                                Password: 
                                <?php if ($bid == 0) echo '<span class="star">&nbsp;*</span>'; ?>
                            </label>
                            <input class="inputbox required validate-password" id="password"
                                   name="password" size="40" value="" type="password">
                        </li>
                        <li>
                            <label id="pw2msg" for="password2" class="hasTip <?php if ($bid == 0) echo "required"; ?>"
                                                                title="Confirm Password::Confirm your password">
                                Verify Password:
                                <?php if ($bid == 0) echo '<span class="star">&nbsp;*</span>'; ?>
                            </label>
                            <input class="inputbox required validate-passverify" id="password2"
                                   name="password2" size="40" value="" type="password">
                        </li>
                    </ul>
            </fieldset>
            <?php
            foreach($user_profile_form->getFieldsets() as $fieldset) {
                if ($fieldset->name == 'user_details') continue;
                $fields = $user_profile_form->getFieldset($fieldset->name);
                if (count($fields)) { //print profile fields
                    ?>
                    <fieldset class="adminform">
                        <?php
                        if (isset($fieldset->label)) {
                        ?>
                            <legend>
                                <?php echo JText::_($fieldset->label); ?>
                            </legend>
                            <ul class="adminformlist">
                        <?php
                        }
                        foreach($fields as $field) {
                            if ($field->hidden) {
                                $field->input;
                            } else {
                                ?>
                                <li>
                                    <?php
                                    echo $field->label;
                                    echo $field->input;
                                    if ($field->name == 'profile[file]') {
                                        if ($user_profile_form->getValue('file', 'profile', '') !== '') { ?>
                                            <input type="button" value="DELETE"
                                                   onclick="document.getElementById('profile_photo').style.display='none';
                                                   document.getElementById('file_path').value='';"/>
                                            <div id='profile_photo' style="display:block;max-width:500px;">
                                                <img src="<?php echo $mosConfig_live_site .
                                                $user_profile_form->getValue('file', 'profile', '') ?>" style="max-width:500px;">
                                            </div>
                                        <?php
                                        }
                                    }
                                        ?>
                                </li>
                            <?php
                            }
                        } ?>
                            </ul>
                    </fieldset>
            <?php
                }
            }
            ?>
            <input type="hidden" id="file_path" name="file_path"
                   value="<?php echo $user_profile_form->getValue('file', 'profile', ''); ?>" />
            <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
            <input type="hidden" name="bid" value="<?php echo $bid; ?>" />
            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden"    name="task" value="" />
        </form>
    <?php
    }
    
    static function showConfiguration($lists, $option) {
        global $my, $mosConfig_live_site, $mainframe, $act, $task;
        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');
        ?>
        <div id="overDiv" style="position: absolute; visibility: hidden; z-index: 10000;"></div>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" >
            <tr>
                <td width="30%"></td>
                <td width="70%" class="simplemembership_manager_caption" valign='bottom'>
                    <?php
                    echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_FRONTEND") ;
                    ?>
                </td>
            </tr>
        </table>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
            <?php
            if (version_compare(JVERSION, "3.0.0", "ge")) {
                $options = Array();
                echo JHtml::_('tabs.start', 'configurePane', $options);
                echo JHtml::_('tabs.panel', JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_1") ,
                 'panel_1_configurePane');
            } else {
                $tabs = new mosTabs(7);
                $tabs->startPane("configurePane");
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_1") , "panel_1_configurePane");
            }
            ?>
            <div style="clear: both;"></div>
            <h2><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_SENDERS_EMAIL") ; ?></h2>
            <table class="adminform">
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_SENDERS_EMAIL") ;?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_(
                        "COM_SIMPLEMEMBERSHIP_ADMIN_SENDERS_EMAIL_TOOLTIP") ); ?></td>
                    <td><?php echo $lists['senders_email']; ?></td>
                </tr>
            </table>
            <h2><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_REGISTRATION_OPTIONS") ; ?></h2>
            <table class="adminform">
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERS_WHO_CAN_SEE_THE_OTHER_PROFILES") ; ?>:</td>
                    <td><?php echo $lists['other_profiles']; ?></td>
                    <td widht="25xp">
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
            </table>
            <h2><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BACKEND_GROUP") ; ?></h2>
            <table class="adminform adminform55">
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_DEFAULT_GROUP") ; ?>:</td>
                    <td width="20">
                        <?php
                        echo mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_DEFAULT_GROUP_TT_BODY") );
                        ?>
                    </td>
                    <td><?php echo $lists['default_group']; ?></td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
            </table>
            
            <div style="clear: both;"></div>
            <h2><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_SHOW_USER_TABS") ; ?></h2>
            <table class="adminform">
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_SHOW_USER_TABS") ;?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_(
                        "COM_SIMPLEMEMBERSHIP_ADMIN_SHOW_USER_TABS_TOOLTIP") ); ?></td>
                    <td><?php echo $lists['allow_fieldsets']; ?></td>
                </tr>
            </table>

            <?php
            if (version_compare(JVERSION, "3.0.0", "ge")) {
                echo JHtml::_('tabs.panel', JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_2") ,
                 'panel_2_configurePane');
            } else {
                $tabs->endTab();
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_2") , "panel_2_configurePane");
            }
            ?>
            <table>
                <h2><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BACKEND_GROUP_NOTIFICATION") ; ?></h2>
            </table>
            <table class="adminform">
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ADDUSER_EMAIL") ;?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_(
                    "COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ADDUSER_EMAIL_TT_BODY") ); ?></td>
                    <td><?php echo $lists['useradd_notification_email']; ?></td>
                </tr>
                <tr>
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ADDUSER_EMAIL_SEND") ; ?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_(
                        "COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ADDUSER_EMAIL_SEND_TT_BODY")); ?></td>
                    <td><?php echo $lists['acl_group_set_email']; ?></td>
                    <td colspan="3" ></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                        <b><?php
                        echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ADMINACCCREATENOTIFY") ; ?>:</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor1', $lists['admin_created_msg'],'admin_created_msg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                    <hr />
                    </td>
                </tr>
            </table>
            <?php
            if (version_compare(JVERSION, "3.0.0", "ge")) {
                echo JHtml::_('tabs.panel',  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_3") ,
                 'panel_2_configurePane');
            } else {
                $tabs->endTab();
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_3") , "panel_2_configurePane");
            }
            ?>
                <table class="adminform">
                <tr>
                    <td colspan="6" align="left">
                       <b> <?php
                        echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_PREREGISTERMSG") .
                                mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_PREREGISTERMSG_TOOLTIP") );
                        ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor1', $lists['preregister'], 'preregister', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ACTIVATION") .
                    mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_ACTIVATION_TOOLTIP") ); ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor2', $lists['activationmsg'], 'activationmsg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
            </table>
            <table class="adminform">
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCCREATENOTIFY") .
                    mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCCREATENOTIFY_TOOLTIP") );?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor2', $lists['user_created_msg'],
                                                    'user_created_msg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCENABLENOTIFY") .
                    mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCENABLENOTIFY_TOOLTIP") ); ?></b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor3', $lists['user_enabled_msg'], 'user_enabled_msg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCAPPROVENOTIFY") ; ?>:</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor4', $lists['user_approve_msg'], 'user_approve_msg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                   <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_USERACCDISAPPROVENOTIFY") ; ?>:</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor4', $lists['user_disapprove_msg'],
                                                    'user_disapprove_msg', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
            </table>
            <?php
            if (version_compare(JVERSION, "3.0.0", "ge")) {
                echo JHtml::_('tabs.panel', JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_4") ,
                 'panel_3_configurePane');
            } else {
                $tabs->endTab();
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_LABEL_SETTINGS_TAB_4") , "panel_3_configurePane");
            }
            ?>
            <table class="adminform adminform55">
                <tr>   
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_TT_HEAD") ; ?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_TT_BODY") ); ?></td>
                    <td><?php echo $lists['before_end_notify']; ?></td>
                    <td><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_TT_BODY")  ?>:</td>
                </tr>   
                <tr>   
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_DAYS") ; ?></td>
                    <td width="20"><?php echo mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_DAYS_TT_BODY") ); ?></td>
                    <td><?php echo $lists['before_end_notify_days']; ?></td>
                </tr>   
                <tr>   
                    <td width="185"><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_EMAIL") ; ?>:</td>
                    <td width="20"><?php echo mosToolTip( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_EMAIL_TT_BODY") ); ?></td>
                    <td><?php echo $lists['before_end_notify_email']; ?></td>
                    <td><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_EMAIL_NOTIFICATION_BEFORE_END") ; ?></td>
                    <td><?php echo  JText::_(
                        "COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_BEFORE_END_NOTIFY_EMAIL_DESCTIPTION") ; ?></td>
                </tr>
            </table>
            <table class="adminform">
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_MASSAGEFORTHEEND_GROUP_FOR_USER") ; ?>:</b>
                    </td>
                </tr>
                <tr  class="myClass">
                    <td  colspan="6">
                        <?php
                        editorArea('editor1', $lists['admin_created_msg_for_user'],
                                                    'admin_created_msg_for_user', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="6" align="left">
                    <b><?php echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_CONFIG_MASSAGEFORTHEEND_GROUP_FOR_ADMIN") ; ?>:<b>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <?php
                        editorArea('editor1', $lists['admin_created_msg_for_admin'],
                                                    'admin_created_msg_for_admin', 450, 100, '5', '5');
                        ?>
                    </td>
                </tr>
            </table>
      
        
            <?php
            if (version_compare(JVERSION, "3.0.0", "ge")) {
                echo JHtml::_('tabs.end');
            } else {
                $tabs->endTab();
                $tabs->endPane();
            }
            ?>
            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" name="task" value="config_save" />
        </form>
        <?php
    }

    static function override() {
        //$link = JRoute::_(
        //     'index.php?option=com_os_cck&task=manage_layout_modal&layout_type=all_categories&tmpl=component');
        JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
        $link_override = JRoute::_('index.php?option=com_languages&view=overrides');
        $link_override_new = JRoute::_('index.php?option=com_languages&view=override&layout=edit');
        $rel="{handler: 'iframe', size: {x: 900, y: 550}}";
        $lid = (isset($params->allcategories_layout) 
                && !empty($params->allcategories_layout))? $params->allcategories_layout : '';
        ?>
        <div class="fixedform">
            <h3><a class="modal" href="<?php echo $link_override;?>" rel="<?php echo $rel;?>">
              Overrides
            </a></h3>
            <h3><a class="modal" href="<?php echo $link_override_new;?>" rel="<?php echo $rel;?>">
              Create new overrides
            </a></h3>
        </div>
        <?php
    }



    static function about() {
        global $mosConfig_live_site, $mainframe;

        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_simplemembership/includes/simplemembership.css');

        if (version_compare(JVERSION, '3.0.0', 'lt')) {
            $tabs = new mosTabs(0);
            ?>
            <div id="overDiv" style="position: absolute; visibility: hidden; z-index: 10000;"></div>
            <table cellpadding="4" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="simplemembership_manager_caption" valign='bottom'>
                        <?php
                        echo  JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT") ;
                        ?>
                    </td>
                </tr>
            </table>
            <form action="index.php" method="post" name="adminForm" id="adminForm">
            <?php

            $tabs->startPane("aboutPane");
            $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_ABOUT") , "display-page");

            ?>
                <table class="adminform">
                    <tr>
                        <td width="80%">
                            <h3><?PHP echo  JText::_("COM_SIMPLEMEMBERSHIP__HTML_ABOUT") ; ?></h3>
                            <?PHP echo  JText::_("COM_SIMPLEMEMBERSHIP__HTML_ABOUT_INTRO") ; ?>
                        </td>
                        <td width="20%">
                            <img src="../components/com_simplemembership/images/simplemembership.jpg" align="right"
                                                                                        alt=alt="simplemembership" />
                        </td>
                    </tr>
                </table>
                <?php
                $tabs->endTab();
                //******************************   tab--2 about   **************************************
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_RELEASENOTE") , "display-page");
                include_once ("./components/com_simplemembership/doc/releasenote.php");
                $tabs->endTab();
                //******************************   tab--3 about--changelog.txt   ***********************
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_CHANGELOG") , "display-page");
                include_once ("./components/com_simplemembership/doc/changelog.html");
                $tabs->endTab();
                //******************************   tab--4 about--help   ***********************
                $tabs->startTab( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_HELP") , "display-page");
                include_once ("./components/com_simplemembership/doc/help.html");
                $tabs->endTab();
                $tabs->endPane();
                ?>
            </form>
        <?php
        } //end of version compare
        else {
            // joomla 3 panes
            $options = array();
            echo JHtml::_('tabs.start', 'aboutPane', $options);
            echo JHtml::_('tabs.panel', JText::_( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_ABOUT") ), 'panel_1_id');
            ?>
            <table class="adminform">
                <tr>
                    <td width="80%">
                        <h3><?PHP echo  JText::_("COM_SIMPLEMEMBERSHIP__HTML_ABOUT") ; ?></h3>
                        <?PHP echo  JText::_("COM_SIMPLEMEMBERSHIP__HTML_ABOUT_INTRO") ; ?>
                    </td>
                    <td width="20%">
                        <img src="../components/com_simplemembership/images/simplemembership.jpg" align="right" />
                    </td>
                </tr>
            </table>
            <?php
            echo JHtml::_('tabs.panel', JText::_( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_RELEASENOTE") ), 'panel_2_id');
            include_once ("./components/com_simplemembership/doc/releasenote.php");
            echo JHtml::_('tabs.panel', JText::_( JText::_("COM_SIMPLEMEMBERSHIP_ADMIN_ABOUT_CHANGELOG") ), 'panel_3_id');
            include_once ("./components/com_simplemembership/doc/changelog.html");
            echo JHtml::_('tabs.end');
        }
    }
    
    static function getErrMsg($err_code) {
        switch ($err_code) {
            case 'dug':
                $str = 'Unappropriated joomla group for this simplemembership group';
            break;
            case 'texp':
                $str = 'Time expired';
            break;
            default:
                $str = '';
            break;
        }
        return $str;
    }
}
?>
