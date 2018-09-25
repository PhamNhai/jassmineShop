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

require_once(JPATH_ROOT . "/components/com_os_cck/functions.php");
require_once(JPATH_SITE . "/administrator/components/com_os_cck/menubar_ext.php");

class menucat
{

    static function NEW_CATEGORY()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_category');
        mosMenuBar_cckext::cancel('cancel_category');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function EDIT_CATEGORY()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_category');
        mosMenuBar_cckext::cancel('cancel_category');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function SHOW_CATEGORIES()
    {
        mosMenuBar_cckext::startTable();
        if (JFactory::getUser()->authorise('publish_categories', 'com_os_cck'))
        {
            mosMenuBar_cckext::publishList('publish_categories');
        }
        if (JFactory::getUser()->authorise('unpublish_categories', 'com_os_cck'))
        {
            mosMenuBar_cckext::unpublishList('unpublish_categories');
        }
        if (JFactory::getUser()->authorise('add_category', 'com_os_cck'))
        {
        mosMenuBar_cckext::addNew('add_category', JText::_('COM_OS_CCK_HEADER_ADD'));
        mosMenuBar_cckext::editList('edit_category');
        mosMenuBar_cckext::deleteList('You confirm delete category?', 'remove_categories');
        mosMenuBar_cckext::spacer();
        }
        mosMenuBar_cckext::endTable();
    }

    static function DEFAULT_CATEGORIES()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::publishList();
        mosMenuBar_cckext::unpublishList();
        if (JFactory::getUser()->authorise('add_category', 'com_os_cck'))
        {
        mosMenuBar_cckext::addNew('add', JText::_('COM_OS_CCK_HEADER_ADD'));
        mosMenuBar_cckext::editList();
        mosMenuBar_cckext::deleteList();
        }
        mosMenuBar_cckext::endTable();
    }
}

//////*****************************************************//////////*
class menuos_cck
{
//instance
    static function MENU_IMPORT()
    {
        // mosMenuBar_cckext::deleteList('', 'deleteOrder',JText::_("COM_OS_CCK_BUTTON_DELETE_ORDER"));
        // mosMenuBar_cckext::publishList('is_readed_orders','Mark as viewed',true);
    }

    static function MENU_ORDERS()
    {
        mosMenuBar_cckext::deleteList('', 'deleteOrder',JText::_("COM_OS_CCK_BUTTON_DELETE_ORDER"));
        mosMenuBar_cckext::publishList('is_readed_orders','Mark as viewed',true);
    }
        
    static function MENU_DEFAULT()
    {   

        mosMenuBar_cckext::editList('edit_instance', "Edit", true);
        if (JFactory::getUser()->authorise('publish_instances', 'com_os_cck'))
        {
            mosMenuBar_cckext::publishList('publish_instances');
        }

        if (JFactory::getUser()->authorise('unpublish_instances', 'com_os_cck'))
        {
            mosMenuBar_cckext::unpublishList('unpublish_instances');
            mosMenuBar_cckext::spacer();
        }

        if (JFactory::getUser()->authorise('new_instance', 'com_os_cck'))
        {
            mosMenuBar_cckext::addNew('new_instance', JText::_('COM_OS_CCK_HEADER_ADD'));
            mosMenuBar_cckext::spacer();
            mosMenuBar_cckext::deleteList('You confirm remove instance ?', 'remove_instances');
            mosMenuBar_cckext::spacer();
        }

        if (JFactory::getUser()->authorise('access_to_rent_requests', 'com_os_cck'))
        {
          //mosMenuBar_cckext::NewCustom('rent', 'adminForm', "../administrator/components/com_os_cck/images/dm_lend.png", "../administrator/components/com_os_cck/images/dm_lend_32.png", 'Rent Item', 'Rent item', true, 'adminForm');
           mosMenuBar_cckext::editList('rent', 'Rent return');
            mosMenuBar_cckext::editList('edit_rent', 'Edit rent');
            mosMenuBar_cckext::editList('add_rent', 'Rent');
        }

        mosMenuBar_cckext::publishList('is_readed','Mark as viewed',true);

        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::endTable();
   }

    static function MENU_SAVE_BACKEND()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::save('save_instance');
        mosMenuBar_cckext::apply('apply_instance', 'Apply');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::cancel();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_EDIT()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_instance');
        mosMenuBar_cckext::apply('apply_instance', 'Apply');
        mosMenuBar_cckext::cancel('cancel_instance');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGEENTITIES_SHOW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::addNew('add_entity', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_ADDENTITY'));
        mosMenuBar_cckext::editList('edit_entity', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_EDITENTITY'));
        mosMenuBar_cckext::deleteList('When you delete entities, you will have to remove all data for these entities and all fields for these entities. Do continue?', 'delete_entity', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DELETEENTITY'));
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGEENTITIES_EDIT()
    {

        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_entity');
        mosMenuBar_cckext::cancel('cancel_edit_entity');
        mosMenuBar_cckext::endTable();
    }

    static function MENU_NEW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save();
        mosMenuBar_cckext::cancel('cancel_instance');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_DELETE_REVIEW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save();
        mosMenuBar_cckext::spacer();

        //*******************  begin add for review edit  **********************
        mosMenuBar_cckext::editList('edit_review', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_EDIT_REVIEW'));
        mosMenuBar_cckext::deleteList('You confirm delete review ?', 'delete_review', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DELETE_REVIEW'));
        //*******************  end add for review edit  ************************

        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::cancel();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_EDIT_REVIEW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_review');
        mosMenuBar_cckext::cancel('cancel_review');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();

    }

    static function MENU_CANCEL()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::cancel(); //old valid  mosMenuBar::cancel();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_CONFIG()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('config_save');
        mosMenuBar_cckext::cancel();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGE_REQUESTS()
    {
        // /mosMenuBar_cckext::back();
        mosMenuBar_cckext::deleteList('You confirm remove item?', 'remove_request_item');
        mosMenuBar_cckext::publishList('is_readed','Mark as viewed',true);
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGE_SHOW_REQUEST_ITEM()
    {
        mosMenuBar_cckext::back();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::endTable();
    }
//**************   begin for manage reviews   *********************
    static function MENU_MANAGE_REVIEW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::deleteList('You confirm delete review ?', 'delete_review', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DELETE_REVIEW'));
        mosMenuBar_cckext::endTable();
        mosMenuBar_cckext::publishList('is_readed','Mark as viewed',true);
    }

    // static function MENU_MANAGE_REVIEW_EDIT()
    // {
    //     mosMenuBar_cckext::startTable();
    //     mosMenuBar_cckext::save('update_edit_manage_review');
    //     mosMenuBar_cckext::spacer();
    //     mosMenuBar_cckext::cancel('cancel_edit_manage_review');
    //     mosMenuBar_cckext::endTable();
    // }

    // static function MENU_MANAGE_REVIEW_EDIT_EDIT()
    // {
    //     mosMenuBar_cckext::startTable();
    //     mosMenuBar_cckext::editList('edit_review', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_EDIT_REVIEW'));
    //     mosMenuBar_cckext::spacer();
    //     mosMenuBar_cckext::deleteList('You confirm delete review ?', 'delete_manage_review', JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DELETE_REVIEW'));
    //     mosMenuBar_cckext::endTable();
    // }

//**************   end for manage reviews   ***********************

    static function MENU_EDIT_RENT() {
        mosMenuBar_cckext::startTable();

        // mosMenuBar_cckext::NewCustom('save_rent', 'adminForm', "../administrator/components/com_os_cck/images/dm_lend.png", "../administrator/components/com_os_cck/images/dm_lend_32.png", JText::_('COM_OS_CCK_TOOLBAR_RENT_ITEMS'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_RENT'), true, 'adminForm');

        mosMenuBar_cckext::NewCustom('save_edit_rent', 'adminForm', "../administrator/components/com_os_cck/images/dm_lend.png", "../administrator/components/com_os_cck/images/dm_lend_32.png",
         JText::_('COM_OS_CCK_TOOLBAR_RENT_ITEMS'), 
         JText::_('COM_OS_CCK_TOOLBAR_ADMIN_EDIT_RENT'), true, 'adminForm');

        // mosMenuBar_cckext::NewCustom('rent_return', 'adminForm',
        //     "../administrator/components/com_os_cck/images/dm_lend_return.png",
        //     "../administrator/components/com_os_cck/images/dm_lend_return_32.png",
        //     JText::_('COM_OS_CCK_TOOLBAR_RETURN_ITEMS'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_RETURN'),
        //     true, 'adminForm');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_EDIT_RETURN_RENT(){
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::NewCustom('rent_return', 'adminForm',
            "../administrator/components/com_os_cck/images/dm_lend_return.png",
            "../administrator/components/com_os_cck/images/dm_lend_return_32.png",
            JText::_('COM_OS_CCK_TOOLBAR_RETURN_ITEMS'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_RETURN'),
            true, 'adminForm');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_EDIT_ADD_RENT(){
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::NewCustom('save_rent', 'adminForm', "../administrator/components/com_os_cck/images/dm_lend.png", "../administrator/components/com_os_cck/images/dm_lend_32.png", JText::_('COM_OS_CCK_TOOLBAR_RENT_ITEMS'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_RENT'), true, 'adminForm');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }


    static function MENU_USER_RENT_HISTORY(){
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::spacer();
       // mosMenuBar_cckext::back();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_RENTREQUESTS()
    {
        global $mosConfig_absolute_path;
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::NewCustom('accept_rent_requests', 'adminForm',
            '../administrator/components/com_os_cck/images/dm_accept.png',
            '../administrator/components/com_os_cck/images/dm_accept_32.png',
            JText::_('COM_OS_CCK_TOOLBAR_ACCEPT_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_ACCEPT'),
            true, 'adminForm');

        mosMenuBar_cckext::NewCustom('decline_rent_requests', 'adminForm',
            '../administrator/components/com_os_cck/images/dm_decline.png',
            '../administrator/components/com_os_cck/images/dm_decline_32.png',
            JText::_('COM_OS_CCK_TOOLBAR_DECLINE_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DECLINE'),
            true, 'adminForm');
        //mosMenuBar_cckext::back();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
        mosMenuBar_cckext::publishList('is_readed','Mark as viewed',true);
    }

    static function MENU_RENTREQUESTSITEM(){
       
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::NewCustom('accept_rent_requests', 'adminForm',
            '../administrator/components/com_os_cck/images/dm_accept.png',
            '../administrator/components/com_os_cck/images/dm_accept_32.png',
            JText::_('COM_OS_CCK_TOOLBAR_ACCEPT_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_ACCEPT'),
            true, 'adminForm');

        mosMenuBar_cckext::NewCustom('decline_rent_requests', 'adminForm',
            '../administrator/components/com_os_cck/images/dm_decline.png',
            '../administrator/components/com_os_cck/images/dm_decline_32.png',
            JText::_('COM_OS_CCK_TOOLBAR_DECLINE_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DECLINE'),
            true, 'adminForm');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::back();
        
    }

    static function MENU_BUYINGREQUESTS()
    {
        // mosMenuBar_cckext::startTable();
        // mosMenuBar_cckext::NewCustom('accept_buying_requests', 'adminForm',
        //     '../administrator/components/com_os_cck/images/dm_accept.png',
        //     '../administrator/components/com_os_cck/images/dm_accept_32.png',
        //     JText::_('COM_OS_CCK_TOOLBAR_ACCEPT_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_ACCEPT'), true, 'adminForm');
        // mosMenuBar_cckext::NewCustom('decline_buying_requests', 'adminForm',
        //     '../administrator/components/com_os_cck/images/dm_decline.png',
        //     '../administrator/components/com_os_cck/images/dm_decline_32.png',
        //     JText::_('COM_OS_CCK_TOOLBAR_DECLINE_REQUEST'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_DECLINE'), true, 'adminForm');
        // mosMenuBar_cckext::cancel();
        //mosMenuBar_cckext::back();
        mosMenuBar_cckext::deleteList('You confirm remove item?', 'remove_buy_request_item');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
        mosMenuBar_cckext::publishList('is_readed','Mark as viewed',true);
    }

    static function MENU_BUYINGREQUESTSITEM()
    {
        mosMenuBar_cckext::back();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_RENT_RETURN()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::NewCustom('rent_return', 'adminForm',
            "../administrator/components/com_os_cck/images/dm_lend_return.png",
            "../administrator/components/com_os_cck/images/dm_lend_return_32.png",
            JText::_('COM_OS_CCK_TOOLBAR_RETURN_ITEMS'), JText::_('COM_OS_CCK_TOOLBAR_ADMIN_RETURN'),
            true, 'adminForm');
        mosMenuBar_cckext::cancel();
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_ABOUT()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::cancel();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGELAYOUT_SHOW()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::editList('edit_layout', "Edit", true);
        mosMenuBar_cckext::save2copy('copy_layout', "Copy", true);
        mosMenuBar_cckext::publishList('publish_layouts');
        mosMenuBar_cckext::unpublishList('unpublish_layouts');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::addNew('new_layout');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::deleteList('You confirm delete layout?', 'remove_layouts');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::endTable();
    }

    static function MENU_MANAGELAYOUT_EDIT()
    {
        mosMenuBar_cckext::startTable();
        mosMenuBar_cckext::save('save_layout');
        mosMenuBar_cckext::apply('apply_layout', 'Apply');
        mosMenuBar_cckext::spacer();
        mosMenuBar_cckext::cancel('manage_layout');
        mosMenuBar_cckext::endTable();
    }

}
