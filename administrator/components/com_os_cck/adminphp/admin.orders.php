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

class AdminOrders{

    static function showOrders(){
        global $db, $user, $option, $doc, $jConf, $app;
        $search = '';
        $order = 'ORDER BY o.notreaded desc,o.id  DESC';
        $where = '';
         if(isset($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
            $where = "WHERE o.user_email LIKE '%{$search}%' OR o.user_name LIKE '%{$search}%' OR o.id LIKE '%{$search}%'";
        }
        if(isset($_GET['orderby']) && $_GET['orderby'] == 'user') {
            $order = 'ORDER BY o.notreaded desc,o.user_name';
        }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'email') {
            $order = 'ORDER BY o.notreaded desc,o.user_email ASC';
        }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'status') {
            $order = "ORDER BY o.notreaded desc,o.status = 'Completed' DESC";
        }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'order_date') {
            $order = "ORDER BY o.notreaded desc,o.order_date  DESC";
        }elseif(isset($_GET['orderby']) && $_GET['orderby'] == 'id') {
            $order = "ORDER BY o.notreaded desc,o.id  ASC";
        }

        $limit = $app->getUserStateFromRequest("viewlistlimit", 'limit', $jConf->get("list_limit",10));
        $limitstart = $app->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

        if(isset($_REQUEST['order_details'])){
            $order = "ORDER BY o.order_date  DESC";
            if(isset($_GET['orderby']) && $_GET['orderby'] == 'order_date') {
                $order = "ORDER BY o.order_date  ASC";
            }
            if($where)
                $where = "WHERE o.user_email LIKE '%{$search}%' OR o.user_name LIKE '%{$search}%' 
                            AND fk_order_id = ".$_REQUEST['order_id']."";
            else
                $where = "WHERE fk_order_id = ".$_REQUEST['order_id']."";
            $sql = "SELECT count(*)  ".
                    " FROM #__os_cck_orders_details AS o ".
                    " LEFT JOIN #__users AS u ".
                    " ON o.fk_user_id = u.id ".
                    " LEFT JOIN #__os_cck_entity_instance AS eii ".
                    " ON o.fk_instance_id = eii.eiid ".
                    " LEFT JOIN #__os_cck_orders AS ccko ".
                    " ON ccko.id = o.fk_order_id ".
                    $where." ".$order ;
            $db->setQuery($sql);
            $total = $db->loadResult();
            $pageNav = new JPagination($total, $limitstart, $limit);
            $sql = "SELECT u.username, ".
                           "o.*, ".
                           "ccko.fk_request_id,ccko.order_price as i_price, ccko.order_currency as i_unit".
                   " FROM #__os_cck_orders_details AS o ".
                   " LEFT JOIN #__users AS u ".
                   " ON o.fk_user_id = u.id ".
                   " LEFT JOIN #__os_cck_entity_instance AS eii ".
                   " ON o.fk_instance_id = eii.eiid ".
                   " LEFT JOIN #__os_cck_orders AS ccko ".
                   " ON ccko.id = o.fk_order_id ".
                    $where." ".$order. " LIMIT " . $pageNav->limitstart." , ". $pageNav->limit;
            $db->setQuery($sql);
            $orders = $db->loadobjectList();

            $query = "UPDATE #__os_cck_orders SET notreaded=0 WHERE id=".$_REQUEST['order_id'];
            $db->setQuery($query);
            $db->query();

            AdminViewOrders::orders_details($orders, $search, $pageNav);
        }else{
            $sql = "SELECT count(*)  ".
                    " FROM #__os_cck_orders AS o ".
                    " LEFT JOIN #__users AS u ".
                    " ON o.fk_user_id = u.id ".
                    " LEFT JOIN #__os_cck_entity_instance AS eii ".
                   " ON o.fk_instance_id = eii.eiid ". $where ." ".$order;
            $db->setQuery($sql);
            $total = $db->loadResult();
            $pageNav = new JPagination($total, $limitstart, $limit);
            $sql = "SELECT u.id as userId, u.username, ".
               "o.*".
               " FROM #__os_cck_orders AS o ".
               " LEFT JOIN #__users AS u ".
               " ON o.fk_user_id = u.id ".
               " LEFT JOIN #__os_cck_entity_instance AS eii ".
               " ON o.fk_instance_id = eii.eiid ". $where.
                $order. " LIMIT " . $pageNav->limitstart." , ". $pageNav->limit;
            $db->setQuery($sql);
            $orders = $db->loadobjectList();

            AdminViewOrders::orders($orders, $search, $pageNav);
        }
    }

    static function updateOrderStatus() {
        global $db, $option,$app;
        $orderId = $_POST['cb'];
        $status = $_POST['order_status'];
        $status = $status[$orderId[0]];
        $option = $_POST['option'];
        $sql = "UPDATE #__os_cck_orders SET status = '".$status."' WHERE id = ".$orderId[0]."";
        $db->setQuery($sql);
        $db->query();
        $sql = "SELECT * FROM #__os_cck_orders WHERE id = ".$orderId[0]."";
        $db->setQuery($sql);
        $order = $db->loadobjectList();
        $order = $order['0'];
        $order->txn_type = 'Order status changed (set:'.$status.') by the administrator';
        $sql = "INSERT INTO #__os_cck_orders_details( fk_order_id, fk_user_id, fk_instance_id,
                                                              instance_title, user_email, user_name, status,
                                                              order_date,txn_type, txn_id, payer_id, payer_status,
                                                              payment_details)
                        VALUES ('".$order->id."','".$order->fk_user_id."','". $order->fk_instance_id ."',
                                '".$order->instance_title."','".$order->user_email."','".$order->user_name."','".$order->status."',
                                now(),'".$order->txn_type."','".$order->txn_id."',  '".$order->payer_id."',
                                '".$order->payer_status."', ".$db->Quote($raw_data).")";
        $db->setQuery($sql);
        $db->query();
        $app->redirect("index.php?option=$option&task=orders");
    }


    static function deleteOrder($cb, $option)
    {

        global $db, $app;

        foreach($cb as $key=>$orderId){
            $sql = "DELETE FROM #__os_cck_orders WHERE id = ".$orderId." ";
            $db->setQuery($sql);
            $db->query();

            $sql = "DELETE FROM #__os_cck_orders_details WHERE fk_order_id = ".$orderId." ";
            $db->setQuery($sql);
            $db->query();
        }

        $app->redirect("index.php?option=$option&task=orders");

    }

    

}