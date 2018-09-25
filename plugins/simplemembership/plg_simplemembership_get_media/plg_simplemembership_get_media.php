<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/simplemembership/plg_simplemembership_get_media/buttons.css');
        global $option;
        $path = JPATH_SITE.DS.'components'.DS.'com_medialibrary'.DS.'medialibrary.php';
        if (!function_exists('showMyMedia')){
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
                    $_GLOBALS['task'] = $task = "view_user_medias";
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
                    case 'mymedias':
                        $_GLOBALS['task'] = $task = "mymedias";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'ownermedias':
                        $_GLOBALS['task'] = $task = "ownermedias";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'show_add_media':
                        $_GLOBALS['task'] = $task = "show_add_media";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save':
                        $_GLOBALS['task'] = $task = "save";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_media':
                        $_GLOBALS['task'] = $task = "edit_media";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'view':
                        $_GLOBALS['task'] = $task = "view";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend':
                        $_GLOBALS['task'] = $task = "lend";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_return':
                        $_GLOBALS['task'] = $task = "lend_return";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_history':
                        $_GLOBALS['task'] = $task = "lend_history";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_requests':
                        $_GLOBALS['task'] = $task = "lend_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_request':
                        $_GLOBALS['task'] = $task = "lend_request";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_lend_request':
                        $_GLOBALS['task'] = $task = "save_lend_request";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_lend_requests':
                        $_GLOBALS['task'] = $task = "accept_lend_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_lend_requests':
                        $_GLOBALS['task'] = $task = "decline_lend_requests";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'publish':
                        $_GLOBALS['task'] = $task = "publish";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'unpublish':
                        $_GLOBALS['task'] = $task = "unpublish";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'delete':
                        $_GLOBALS['task'] = $task = "delete";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'suggestion':
                        $_GLOBALS['task'] = $task = "suggestion";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'review':
                        $_GLOBALS['task'] = $task = "review";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'add_to_cart':
                        $_GLOBALS['task'] = $task = "add_to_cart";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break; 
                    case 'show_cart':
                        $_GLOBALS['task'] = $task = "show_cart";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'cart_event':
                        $_GLOBALS['task'] = $task = "cart_event";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    default:
                        $_GLOBALS['task'] = $task = "ownermedias";
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
                JError::raiseWarning( 0, 'View showMyMedias not supported. File not found.' );
                return $false;
            }
            return $view;
        } ?>
