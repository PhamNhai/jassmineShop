<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/simplemembership/plg_simplemembership_get_books/buttons.css');
        global $option;
        $path = JPATH_SITE.DS.'components'.DS.'com_booklibrary'.DS.'booklibrary.php';
        if (!function_exists('showMyBooks')){ 
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
                    $_GLOBALS['task'] = $task = "view_user_books";
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
                  case 'view_user_books':
                        $_GLOBALS['task'] = $task = "view_user_books";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_history_books':  
                        $_GLOBALS['task'] = $task = "rent_history_books";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'show_my_books':     
                    case 'showmybooks':     
                        $_GLOBALS['task'] = $task = "showmybooks";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'add_book_fe':
                        $_GLOBALS['task'] = $task = "add_book_fe";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_book_fe':
                        $_GLOBALS['task'] = $task = "save_book_fe";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_book':
                        $_GLOBALS['task'] = $task = "edit_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break; 
                    case 'view_bl':
                        $_GLOBALS['task'] = $task = "view_bl";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_request_bl':
                        $_GLOBALS['task'] = $task = "lend_request_bl";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_lend_request_bl':
                        $_GLOBALS['task'] = $task = "save_lend_request_bl";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_before_end_notify':
                        $_GLOBALS['task'] = $task = "lend_before_end_notify";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'rent_requests_cb_books':
                        $_GLOBALS['task'] = $task = "rent_requests_cb_books";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;          
                    case 'lend_return':
                        $_GLOBALS['task'] = $task = "lend_return";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'accept_rent_requests_cb_book':
                        $_GLOBALS['task'] = $task = "accept_rent_requests_cb_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'decline_rent_requests_cb_book':
                        $_GLOBALS['task'] = $task = "decline_rent_requests_cb_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                   case 'review_bl':
                        $_GLOBALS['task'] = $task = "review_bl";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_book':
                        $_GLOBALS['task'] = $task = "lend_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'lend_return_book':
                        $_GLOBALS['task'] = $task = "lend_return_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'publish_book':
                        $_GLOBALS['task'] = $task = "publish_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'unpublish_book':
                        $_GLOBALS['task'] = $task = "unpublish_book";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'remove':
                        $_GLOBALS['task'] = $task = "remove";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'suggestion':
                        $_GLOBALS['task'] = $task = "suggestion";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                        
                    default: 
                    //    $_SESSION['vm_user'] = $username;
                        $_GLOBALS['task'] = $task = "view_user_books";
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
                JError::raiseWarning( 0, 'View showMyBooks not supported. File not found.' );
                return $false;
            }
            return $view;
        } ?>
