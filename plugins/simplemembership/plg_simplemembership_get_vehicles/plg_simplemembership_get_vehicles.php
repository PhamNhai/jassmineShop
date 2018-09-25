<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

$document = JFactory::getDocument();
$document->addStyleSheet('plugins/simplemembership/plg_simplemembership_get_vehicles/buttons.css');
      global $option;
        $path = JPATH_SITE.DS.'components'.DS.'com_vehiclemanager'.DS.'vehiclemanager.php';
        if (!function_exists('showMyCars')){
            $false = false;
            if (file_exists($path)){
                ob_start();
                $user=Jfactory::getUser();
                $db=Jfactory::getDBO();
                $id=Jrequest::getVAr('userId');
                $task = "";
                if (isset($_REQUEST['task'])) $task=$_REQUEST['task'];
                if($id !=='' && $user->id == $id  && $task == "showUsersProfile")
                {
                    $_GLOBALS['task'] = $task = "view_user_vehicles";
                }
                if($id==''){
                  $id=$user->id;
                }
                $query='SELECT * FROM #__users WHERE id='.$id;
                $db->setQuery($query);
                $info=$db->loadObject();
                $_SESSION['vm_user'] = $info->name;
                $_REQUEST['Ownername']='on';
                $_REQUEST['exactly']='on';
                $_GLOBALS['option'] = $option = "com_simplemembership";

                switch ($task){
                    case 'view_user_vehicles':
                        $_GLOBALS['task'] = $task = "view_user_vehicles";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'my_vehicles':
                        $_GLOBALS['task'] = $task = "my_vehicles";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_my_cars':
                        $_GLOBALS['task'] = $task = "edit_my_cars";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'show_add_vehicle':
                        $_GLOBALS['task'] = $task = "show_add_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_add_vehicle':
                        $_GLOBALS['task'] = $task = "save_add_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_vehicle':
                        $_GLOBALS['task'] = $task = "edit_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'view_vehicle':
                        $_GLOBALS['task'] = $task = "view_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_vehicle':
                        $_GLOBALS['task'] = $task = "rent_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_return_vehicle':
                        $_GLOBALS['task'] = $task = "rent_return_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_history_vehicle':
                        $_GLOBALS['task'] = $task = "rent_history_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_request_vehicle':
                        $_GLOBALS['task'] = $task = "rent_request_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_rent_request_vehicle':
                        $_GLOBALS['task'] = $task = "save_rent_request_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'buying_request_vehicle':
                        $_GLOBALS['task'] = $task = "buying_request_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_requests_vehicle':
                        $_GLOBALS['task'] = $task = "rent_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_rent_requests_vehicle':
                        $_GLOBALS['task'] = $task = "accept_rent_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_rent_requests_vehicle':
                        $_GLOBALS['task'] = $task = "decline_rent_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'buying_requests_vehicle':
                        $_GLOBALS['task'] = $task = "buying_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_buying_requests_vehicle':
                        $_GLOBALS['task'] = $task = "accept_buying_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_buying_requests_vehicle':
                        $_GLOBALS['task'] = $task = "decline_buying_requests_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'publish_vehicle':
                        $_GLOBALS['task'] = $task = "publish_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'unpublish_vehicle':
                        $_GLOBALS['task'] = $task = "unpublish_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'delete_vehicle':
                        $_GLOBALS['task'] = $task = "delete_vehicle";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'review_veh':
                        $_GLOBALS['task'] = $task = "review_veh";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'ajax_rent_calcualete': 
                        $_GLOBALS['task'] = $task = "ajax_rent_calcualete";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                    default:
                        $_GLOBALS['task'] = $task = "owner_vehicles";
                        //$_GLOBALS['task'] = $task = "view_user_vehicles";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                    ?>
                 <!--      <script>
                            jQuerREL('.tab').ready(function() {
                                jQuerREL('.tab').mouseup(function() {
                                    setTimeout('vm_initialize2()',10);
                                });
                            });
                        </script>-->
                    <?php
                        break;
                }
                require_once($path);
                $view = ob_get_contents();
                ob_end_clean();
                print_r($view);
             
            } else{
                JError::raiseWarning( 0, 'View showMyCars not supported. File not found.' );
                return $false;
            }
            return $view;
        } 
 

