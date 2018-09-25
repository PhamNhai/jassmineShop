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

if (stristr($_SERVER['PHP_SELF'], 'administrator')) {
    @define('_CCK_IS_BACKEND', '1');
}
defined('_CCK_TOOLBAR_LOADED') or define('_CCK_TOOLBAR_LOADED', 1);

//include_once(JPATH_ROOT . "/components/com_os_cck/compat.joomla1.5.php");


// ensure this file is being included by a parent file

require_once(JPATH_ROOT . "/administrator/components/com_os_cck/toolbar_ext.php");
require_once(JPATH_ROOT . "/administrator/components/com_os_cck/toolbar.os_cck.html.php");
require_once(JPATH_ROOT . "/components/com_os_cck/functions.php");


$section = mosGetParam($_REQUEST, 'section', 'courses');

if (version_compare(JVERSION, "3.0.0", "ge")) {
    if (isset($_REQUEST['task'])) {
        $task = $_REQUEST['task'];
    } else {
        $task = '';
    }
}
//print_r($task);exit;
switch ($task) {
    case "add_category":
        menucat::NEW_CATEGORY();
        cck_additional::addSubmenu('Categories');
        break;
    case "edit_category":
        menucat::EDIT_CATEGORY();
        cck_additional::addSubmenu('Categories');
        break;
    case "show_categories":
        menucat::SHOW_CATEGORIES();
        cck_additional::addSubmenu('Categories');
        break;

    case "new_instance":
        menuos_cck::MENU_EDIT();
        cck_additional::addSubmenu('Instances');
        break;

    case "edit_instance":
        menuos_cck::MENU_EDIT();
        cck_additional::addSubmenu('Instances');
        break;

    case "orders":
        menuos_cck::MENU_ORDERS();
        cck_additional::addSubmenu('Orders');
        break;

    case "edit_rent":
        menuos_cck::MENU_EDIT_RENT();
        cck_additional::addSubmenu('Instances');
        break;

    case "add_rent":
        menuos_cck::MENU_EDIT_ADD_RENT();
        cck_additional::addSubmenu('Instances');
        break;

    case "rent":
        menuos_cck::MENU_EDIT_RETURN_RENT();
        cck_additional::addSubmenu('Instances');
        break;

    case "save_rent":
        menuos_cck::MENU_EDIT_RENT();
        cck_additional::addSubmenu('Instances');
        break;

    case "users_rent_history":
        menuos_cck::MENU_USER_RENT_HISTORY();
        cck_additional::addSubmenu("User Rent History");
        break;
    

    case "show_rent_request_instances":
        menuos_cck::MENU_RENTREQUESTS();
        cck_additional::addSubmenu('Rent');
        break;

    case "edit_rent_request_instance" :
        menuos_cck::MENU_RENTREQUESTSITEM();
        cck_additional::addSubmenu('Rent');
        break;

    case "show_buy_request_instances":
        menuos_cck::MENU_BUYINGREQUESTS();
        cck_additional::addSubmenu('Buy');
        break;

    case "edit_buy_request_instance" :
        menuos_cck::MENU_BUYINGREQUESTSITEM();
        cck_additional::addSubmenu('Buy');
        break;

    case "config":
        menuos_cck::MENU_CONFIG();
        break;


    case "config_save":
        menuos_cck::MENU_CONFIG();
        break;

    case "about":
        menuos_cck::MENU_ABOUT();
        cck_additional::addSubmenu('About');
        break;

//**************   begin for manage reviews   *********************
    case "manage_review":
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_MANAGE_REVIEW();
        break;

    case "delete_review":
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_DELETE_REVIEW();
        break;

    case "edit_review":
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_EDIT_REVIEW();
        break;

    case "delete_review" :
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_MANAGE_REVIEW();
        break;

    case "show_requests":
        cck_additional::addSubmenu('Requests');
        menuos_cck::MENU_MANAGE_REQUESTS();
        break;

    case "show_request_item":
        cck_additional::addSubmenu('Requests');
        menuos_cck::MENU_MANAGE_SHOW_REQUEST_ITEM();
        break;

    case "sorting_manage_review_date":
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_MANAGE_REVIEW();
        break;

    case "sorting_manage_review_rating":
        cck_additional::addSubmenu('Reviews');
        menuos_cck::MENU_MANAGE_REVIEW();
        break;
//**************   end for manage reviews   ***********************



    case "manage_entities":
        menuos_cck::MENU_MANAGEENTITIES_SHOW();
        cck_additional::addSubmenu('Manage entities');
        break;

    case "edit_entity":
    case "add_entity":
        menuos_cck::MENU_MANAGEENTITIES_EDIT();
        cck_additional::addSubmenu('Manage entities');
        break;

    case "manage_layout":
        menuos_cck::MENU_MANAGELAYOUT_SHOW();
        cck_additional::addSubmenu('Manage views and layouts');
        break;

    case "edit_layout":
        break;

    case "new_layout":
        if(!JRequest::getVar('layout_type','')){
            //menuos_cck::MENU_MANAGELAYOUT_EDIT();
            cck_additional::addSubmenu('Manage views and layouts');
        }
        break;

    case "import":
        menuos_cck::MENU_IMPORT();
        cck_additional::addSubmenu('Import');
        break;

    case "show_instance":
    default:
        menuos_cck::MENU_DEFAULT();
        cck_additional::addSubmenu('Instances');
        break;
}

