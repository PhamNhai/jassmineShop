<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/simplemembership/plg_simplemembership_get_ads/buttons.css');
        global $option;
        $path = JPATH_SITE.DS.'components'.DS.'com_advertisementboard'.DS.'advertisementboard.php';
        if (!function_exists('viewUserAds')){
            $false = false;
            if (file_exists($path)){
                ob_start();
                $user=Jfactory::getUser();
                $db=Jfactory::getDBO();
                $id=Jrequest::getVAr('userId');
                $task = "";
                if (isset($_REQUEST['task'])) $task=$_REQUEST['task'];
                if (isset($_REQUEST['view'])) {
                    $view = mosGetParam($_REQUEST, 'view', '');
                    if ((!isset($task) OR $task == '') AND isset($view)) $task = $view;
                }
                if($id==''){
                  $id=$user->id;
                }
                $_GLOBALS['option'] = $option = "com_simplemembership";

                switch ($task){
                    case 'my_ads':
                    case 'show_my_ads':
                    case 'my_account': 
                        $_GLOBALS['task'] = $task = "show_my_ads";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'add_advertisement':
                        $_GLOBALS['task'] = $task = "add_advertisement";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'save_advertisement':
                        $_GLOBALS['task'] = $task = "save_advertisement";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'edit_advertisement':
                        $_GLOBALS['task'] = $task = "edit_advertisement";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'show_alone_advertisement':
                        $_GLOBALS['task'] = $task = "show_alone_advertisement";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'unpublish_ads':
                        $_GLOBALS['task'] = $task = "unpublish_ads";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'publish_ads':
                        $_GLOBALS['task'] = $task = "publish_ads";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    case 'delete_ads':
                        $_GLOBALS['task'] = $task = "delete_ads";
                        $_GLOBALS['option'] = $option = "com_simplemembership";
                        break;
                    default:
                        $_GLOBALS['task'] = $task = "view_user_ads";
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
                JError::raiseWarning( 0, 'View viewUserAds not supported. File not found.' );
                return $false;
            }
            return $view;
        } ?>
