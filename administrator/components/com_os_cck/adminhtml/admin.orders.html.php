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


class AdminViewOrders{

    static function orders($orders, $search, &$pageNav){
        global $user, $app, $doc;



        $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />".JText::_('COM_OS_CCK_SHOW_ORDERS')."</div>";
        $app->JComponentTitle = $html;
        $countOrders = count($orders);
        $app = JFactory::getApplication();
        $input = $app->input;
        $option = $input->get('option', '', 'STRING');
        $optionStatus[] = JHTML::_('select.option','Pending', "Pending");
        $optionStatus[] = JHTML::_('select.option','Completed', 'Completed');
        ?>
        <form action="index.php" method="post" name="adminForm"  class="cck-orders-main"  id="adminForm" >
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="list_13 filters">
                <tr>
                    <td><?php echo JText::_("COM_OS_CCK_SHOW_SEARCH"); ?></td>
                    <td><input type="text" name="search" value="<?php echo $search; ?>"
                               class="inputbox" onChange="document.adminForm.submit();" /></td>
                    <td>
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit" class="element-invisible"><?php
                             echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>

                          <?php echo $pageNav->getLimitBox(); ?>
                        </div>
                    </td>
                </tr>
            </table>
            <table cellpadding="4" cellspacing="0" width="100%" class="table adminlist_05">
                <tr>
                    <th colspan="" name="toggle" width="5%"><input type="checkbox" name="toggle" value=""
                                                                        onClick="Joomla.checkAll(this);" /></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="index.php?option=com_os_cck&task=orders&orderby=id">
                            <?php
                            echo JText::_("COM_OS_CCK_ORDERS_ID");?>
                        </a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="index.php?option=com_os_cck&task=orders&orderby=title">
                            <?php
                            echo JText::_("COM_OS_CCK_ORDERS_TITLE");?>
                        </a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="index.php?option=com_os_cck&task=orders&orderby=email">
                            <?php
                            echo JText::_("COM_OS_CCKORDERS_EMAIL");?>
                        </a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="index.php?option=com_os_cck&task=orders&orderby=order_date">
                            <?php
                            echo JText::_("COM_OS_CCK_ORDERS_DATE");?>
                        </a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="index.php?option=com_os_cck&task=orders&orderby=status">
                            <?php
                            echo JText::_("COM_OS_CCK_ORDERS_STATUS");?>
                        </a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_PRICE");?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_PAID");?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""></th>
                </tr>
                <?php for($i = 0; $i < $countOrders; $i++) { 
                    if($orders[$i]->instance_type == "Rent"){
                        $task = "show_rent_request_instances";
                    }else{
                        $task = "show_buy_request_instances";
                    }
                    ?>
                    <tr class="row<?php echo $i % 2; ?> <?php echo $orders[$i]->notreaded?'not-readed':''?>">
                        <td align = "center"><?php echo JHTML::_('grid.id', $i,$orders[$i]->id, false, 'cb');?></td>
                        <td><?php echo $orders[$i]->id;?></td>
                        <td>
                            <a href="index.php?option=com_os_cck&task=<?php echo $task?>&search=<?php echo $orders[$i]->fk_request_id?>">
                            <?php echo $orders[$i]->instance_title;?>
                            </a>
                        </td>
                        <td><?php echo $orders[$i]->user_email;?></td>
                        <td><?php echo $orders[$i]->order_date;?></td>
                        <td>
                            <?php
                           

                            $status = $orders[$i]->status;

                            if($status == 'Completed' || $status == 'Success'){
                                $status = 'Completed';
                            }
                            $attr = 'class="inputbox input-medium" size="1"
                             onchange="return listItemTask(\'cb'.$i.'\',\'updateOrderStatus\')"';
                             
                            echo JHTML::_('select.genericlist',$optionStatus, 'order_status['.
                              $orders[$i]->id.']', $attr, 'value', 'text', $status);

                 
                     // if(($orders[$i]->order_price == $orders[$i]->paid_price) && ($status == 'Completed' || $status == 'Success') && ($orders[$i]->instance_type == 'Rent') && (!isset($orders[$i]->accept_rent)))
                     //   {
                     //        AdminRent_request::accept_rent_requests($option, array($orders[$i]->fk_request_id));
                     //        // $orders[$i]->accept_rent = 1;
                     //   } 

                            ?>
    


                        </td>
                        <td><?php echo $orders[$i]->order_price." ".$orders[$i]->order_currency;?></td>
                        <td><?php echo $orders[$i]->paid_price." ".$orders[$i]->paid_currency?></td>
                        <td>
                            <a href="<?php echo 'index.php?option=com_os_cck'.
                                                '&task=orders&order_details=order_details&order_id='.$orders[$i]->id ?>">
                            <?php echo JText::_("COM_OS_CCK_ORDERS_DETAILS"); ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php echo $pageNav->getListFooter(); ?>
            <input type="hidden" name="option" value="com_os_cck" />
            <input type="hidden" name="task" value="orders" />
            <input type="hidden" name="sectionid" value="com_os_cck" />
            <input type="hidden" value="0" name="boxchecked" />
        </form><?php
    }

    static function orders_details($orders, $search, &$pageNav){
        global $user, $app, $doc, $app;
        $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />".JText::_('COM_OS_CCK_SHOW_ORDERS')."</div>";
        $app->JComponentTitle = $html;
        $countOrders = count($orders);
        $orderId = JRequest::getVar('order_id');
        $optionStatus[] = JHTML::_('select.option','Pending', "Pending");
        $optionStatus[] = JHTML::_('select.option','Completed', 'Completed');
        ?>
        <form action="index.php" method="post" name="adminForm"  class="cck-orders-details-main"  id="adminForm" >
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="list_13">
                <tr>
                    <td><?php echo JText::_("COM_OS_CCK_SHOW_SEARCH"); ?></td>
                    <td><input type="text" name="search" value="<?php echo $search; ?>"
                               class="inputbox" onChange="document.adminForm.submit();" /></td>
                    <td>
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit" class="element-invisible"><?php
                             echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>

                          <?php echo $pageNav->getLimitBox(); ?>
                        </div>
                    </td>
                </tr>
            </table>
            <table cellpadding="4" cellspacing="0" width="100%" class="table adminlist_05">
                <tr>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="<?php echo 'index.php?option=com_os_cck&task=orders'.
                                    '&orderby=user&order_details=order_details&order_id='.$orderId?>"><?php echo "User";?></a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo "Username";?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="<?php echo 'index.php?option=com_os_cck&task=orders'.
                                    '&orderby=email&order_details=order_details&order_id='.$orderId ?>">
                            <?php
                            echo JText::_("COM_OS_CCKORDERS_EMAIL");?></a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="<?php echo 'index.php?option=com_os_cck&task=orders'.
                                    '&orderby=order_date&order_details=order_details&order_id='.
                                    $orderId ?>"><?php echo JText::_("COM_OS_CCK_ORDERS_DATE");?></a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan="">
                        <a href="<?php echo 'index.php?option=com_os_cck&task=orders'.
                         '&orderby=status&order_details=order_details&order_id='.$orderId ?>"><?php
                          echo JText::_("COM_OS_CCK_ORDERS_STATUS");?></a>
                    </th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_TITLE");?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_PRICE");?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_PAID");?></th>
                    <th align = "center" nowrap="nowrap" class="title" colspan=""><?php echo JText::_("COM_OS_CCK_ORDERS_DETAILS");?></th>
                </tr>
                <?php
                for($i = 0; $i < $countOrders; $i++) {
                    $payment_details = unserialize($orders[$i]->payment_details);
                    $details_text='';
                    if($orders[$i]->txn_type)
                        $details_text = JText::_("COM_OS_CCK_ORDERS_DET_ACCEPT").$orders[$i]->txn_type;
                    if(!empty($payment_details)){
                        if(isset($payment_details['view']))
                            $details_text .= '<br>'.JText::_("COM_OS_CCK_ORDERS_DET_SYSTEM").$payment_details['view'];
                        if(isset($payment_details['payer_email']))
                            $details_text .= '<br>'.JText::_("COM_OS_CCK_ORDERS_DET_EMAIL").'<br>'.$payment_details['payer_email'];
                        if(isset($payment_details['pending_reason']))
                            $details_text .= '<br>'.JText::_("COM_OS_CCK_ORDERS_DET_REASON").'<br>'.$payment_details['pending_reason'];
                    }
                    ?>
                    <tr>
                        <td>
                            <?php if(!isset($orders[$i]->user_name) && $orders[$i]->user_name == '') {
                                echo JText::_("COM_OS_CCK_LABEL_ANONYMOUS");
                            } else {
                                echo $orders[$i]->user_name;
                            }?>
                        </td>
                        <td><?php if(!isset($orders[$i]->username) && $orders[$i]->username == '' ) {
                                echo JText::_("COM_OS_CCK_LABEL_ANONYMOUS");
                            } else {
                                echo $orders[$i]->username;
                            }?>
                        </td>
                        <td><?php echo $orders[$i]->user_email;?></td>
                        <td><?php echo $orders[$i]->order_date;?></td>
                        <td>
                            <?php
                            echo $orders[$i]->status;
                            ?>
                        </td>
                        <td>
                            <a href="index.php?option=com_os_cck&task=show_rent_request_instances&search=<?php echo $orders[$i]->fk_request_id?>">
                                <?php echo $orders[$i]->instance_title;?>
                            </a>
                        </td>
                        <td><?php echo $orders[$i]->i_price." ".$orders[$i]->i_unit;?></td>
                        <td><?php echo $orders[$i]->order_price." ".$orders[$i]->order_currency;?></td>
                        <td><?php echo $details_text?></td>
                    </tr>
                <?php } ?>
            </table>
            <?php echo $pageNav->getListFooter(); ?>
            <input type="hidden" name="option" value="com_os_cck" />
            <input type="hidden" name="task" value="orders" />
            <input type="hidden" name="order_id" value="<?php echo $orderId;?>" />
            <input type="hidden" name="order_details" value="order_details" />
            <input type="hidden" name="sectionid" value="com_os_cck" />
            <input type="hidden" value="0" name="boxchecked" />
        </form><?php
    }
}