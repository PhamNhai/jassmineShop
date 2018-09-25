<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/simplemembership/plg_simplemembership_get_houses/buttons.css');
        global $option;
        $path = JPATH_SITE.DS.'components'.DS.'com_realestatemanager'.DS.'realestatemanager.php';
        if (!function_exists('showMyHouses')){
            $false = false;
            if (file_exists($path)){
                ob_start();
                $user=Jfactory::getUser();
                $db=Jfactory::getDBO();
                $id=Jrequest::getVAr('userId');
                $task = "";
                if (isset($_REQUEST['task'])) $task=$_REQUEST['task'];

                if($id==''){
                  $id=$user->id;
                }
                $query='SELECT * FROM #__users WHERE id='.$id;
                $db->setQuery($query);
                $info=$db->loadObject();
                $_SESSION['rem_user'] = $info->name;
                $_REQUEST['Ownername']='on';
                $_REQUEST['exactly']='on';
                $_GLOBALS['option'] = $option = "com_simplemembership";

                switch ($task){
                    case 'show_my_houses': 
                    case 'edit_my_houses': 
                        $_GLOBALS['task'] = $task = "edit_my_houses";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_rent':
                    case 'edit_rent_houses':
                        $_GLOBALS['task'] = $task = "edit_rent_houses";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'showmyhouses':
                        $_GLOBALS['task'] = $task = "showmyhouses";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'show_add':
                        $_GLOBALS['task'] = $task = "show_add";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_add':
                        $_GLOBALS['task'] = $task = "save_add";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_house':
                        $_GLOBALS['task'] = $task = "edit_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'view_house':
                        $_GLOBALS['task'] = $task = "view_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent':
                        $_GLOBALS['task'] = $task = "rent";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_return':
                        $_GLOBALS['task'] = $task = "rent_return";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_history':
                        $_GLOBALS['task'] = $task = "rent_history";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_request':
                        $_GLOBALS['task'] = $task = "rent_request";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_rent_request':
                        $_GLOBALS['task'] = $task = "save_rent_request";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_requests': 
                        $_GLOBALS['task'] = $task = "rent_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_rent_requests':
                        $_GLOBALS['task'] = $task = "accept_rent_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_rent_requests':
                        $_GLOBALS['task'] = $task = "decline_rent_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'buying_requests':
                        $_GLOBALS['task'] = $task = "buying_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'buying_request':
                        $_GLOBALS['task'] = $task = "buying_request";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_buying_requests':
                        $_GLOBALS['task'] = $task = "accept_buying_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_buying_requests':
                        $_GLOBALS['task'] = $task = "decline_buying_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'unpublish_house':
                        $_GLOBALS['task'] = $task = "unpublish_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'publish_house':
                        $_GLOBALS['task'] = $task = "publish_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'delete_house':
                        $_GLOBALS['task'] = $task = "delete_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'review_house':
                        $_GLOBALS['task'] = $task = "review_house";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'ajax_rent_calcualete': 
                        $_GLOBALS['task'] = $task = "ajax_rent_calcualete";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                    default:
                        $_GLOBALS['task'] = $task = "view_user_houses";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                    ?>
                    <?php
                        break;
                }
                require_once($path);
                $view = ob_get_contents();
                ob_end_clean();
                print_r($view);
             
            } else{
                JError::raiseWarning( 0, 'View showMyHouses not supported. File not found.' );
                return $false;
            }
            return $view;
        } ?>
