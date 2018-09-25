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
global $mosConfig_absolute_path, $mosConfig_lang;
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];

include_once ( $mosConfig_absolute_path . '/components/com_simplemembership/compat.joomla1.5.php');
//*** Get language files
require_once ($mosConfig_absolute_path . "/administrator/components/com_simplemembership/menubar_ext.php");

class menucat {
    static function NEW_CATEGORY() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save();
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function EDIT_CATEGORY() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save();
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function SHOW_CATEGORIES() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::publishList();
        mosMenuBar_ext::unpublishList();
        mosMenuBar_ext::addNew('add', 'New', false);
        mosMenuBar_ext::deleteList();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::addNew('add_acl_group', 'Add joomla group', false);
        if (version_compare(JVERSION, '3.0.0', 'lt')) {
            mosMenuBar_ext::CustomMenu('del_acl_group_form', "../administrator/components/com_simplemembership/images/jdel.gif", "../administrator/components/com_simplemembership/images/jdel.gif", 'Del joomla group', 'Del joomla group');
        } else mosMenuBar_ext::trash('del_acl_group_form', 'Del joomla group', false); // for joomla 3
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function DEFAULT_CATEGORIES() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::publishList();
        mosMenuBar_ext::unpublishList();
        mosMenuBar_ext::addNew();
        mosMenuBar_ext::editList();
        mosMenuBar_ext::deleteList();
        mosMenuBar_ext::endTable();
    }
}
class menusimplemembership {
    static function MENU_NEW() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save();
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function SAVE_NEWACL() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save('save_newacl');
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function MENU_EDITUSER() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save('save_user');
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function DEL_ACL() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::deleteList('', 'del_acl_group', 'Delete group');
        mosMenuBar_ext::cancel();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function MENU_CANCEL() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::back(); //old valid  mosMenuBar::cancel();
        //mosMenuBar::help();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function MENU_CONFIG() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::save('config_save');
        mosMenuBar_ext::cancel();
        //mosMenuBar::help();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }

    static function MENU_DEFAULT() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::deleteList();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::addNew('add_user', 'New user');
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function MENU_SAVE_BACKEND() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::save();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::back();
        mosMenuBar_ext::spacer();
        mosMenuBar_ext::endTable();
    }
    static function MENU_ABOUT() {
        mosMenuBar_ext::startTable();
        mosMenuBar_ext::back();
        mosMenuBar_ext::endTable();
    }
    static function MENU_OVERRIDE() {
        mosMenuBar_ext::addNew('add', 'New', false);
    }
    static function MENU_ORDERS() {
        mosMenuBar_ext::deleteList('Do you really want to delete orders?', 'deleteOrder','Delete Order');
    }
}
?>
