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

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
$mosConfig_live_site = $GLOBALS['mosConfig_live_site'] = JURI::root();
$db = $GLOBALS['database'] =JFactory::getDBO();
if (version_compare(JVERSION, '3.0', 'lt')) {
    require_once($mosConfig_absolute_path . '/libraries/joomla/database/table.php');
}

//require_once($mosConfig_absolute_path . "/components/com_os_cck/compat.joomla1.5.php");
require_once($mosConfig_absolute_path . "/administrator/components/com_os_cck/install.os_cck.helper.php");
require_once($mosConfig_absolute_path . "/administrator/components/com_os_cck/admin.os_cck.class.impexp.php");
require_once($mosConfig_absolute_path . "/components/com_os_cck/classes/os_cck.class.php");
require_once($mosConfig_absolute_path . "/components/com_os_cck/classes/os_cck.class.php");

if (version_compare(JVERSION, "1.6.0", "lt")) {
    function com_install()
    {
        return com_install2();
    }
}

function com_install2()
{
    global $db, $mosConfig_absolute_path, $mosConfig_live_site;
//**********************   begin check version PHP   ***********************
    $is_warning = false;

    if ((phpversion()) < 5) {
        ?>
        <center>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <code>Installation status: <font color="red">fault</font></code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <code><font color="red">This component works correctly under PHP version 5.3.1 and higher.</font></code>
                    </td>
                </tr>
            </table>
        </center>

        <?php
        return '<h2><font color="red">Component installation fault</font></h2>';
    }

//******************   end check version PHP   ******************************


//******************   begin check xsl extension   *****************
    if (!(class_exists('XsltProcessor'))) {
        $is_warning = true;
        ?>
        <center>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <code><font color="red">XSL extension not found! In order for csv export to work, you need to
                                compile PHP5 with support for the XSL extension!</font></code>
                    </td>
                </tr>
            </table>
        </center>
    <?php
    }
//*******************   end check xsl extension   **********************
//*************   begin check GD extension   *************************
    if (!(function_exists('imagefontwidth'))) {
        $is_warning = true;
        ?>
        <center>
            <table width="100%" border="0">
                <tr>
                    <td>
                        <code><font color="red">GD extension not found! In order for CAPTCHA picture works correctly,
                                you need to compile PHP5 with support for the GD extension!</font></code>
                    </td>
                </tr>
            </table>
        </center>
    <?php
    }
//************************   end check GD extension   ******************

# Show installation result to user
    ?>
    <style type="text/css">
/*------------------install------------------------------*/
.com_installer .cck-install-block {
    border: 2px solid #1CB09A;
    background: rgba(0, 167, 142, 0.35);
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -o-border-radius: 5px;
    -ms-border-radius: 5px;
    margin: 0 0 25px 0;
}
.com_installer .cck-install-block .cck-logo {
    width: 20%;
    display: inline-block;
    float: left;
    text-align: center;
    padding: 35px 0;
}
.com_installer .cck-install-block .cck-text-install {
    width: 80%;
    display: inline-block;
}
.com_installer .cck-install-block .cck-text-install p a {
    text-decoration: underline;
}
.com_installer .cck-install-block .cck-text-install h2 {
    color: #34404C;
    text-transform: uppercase;
}
</style>
    <div class="cck-install-block">
        <div class="cck-logo">
            <img src="<?php echo $mosConfig_live_site . "/administrator/components/com_os_cck/images/os_cck_logo.png"; ?>">
        </div>
        <div class="cck-text-install">
            <h2>Thank you for installing os content construction kit</h2>
            <p>OradSoft CCK - component for sale and rent Equipment<br/>If you want learn how to use OS CCK please read <a href="http://ordasoft.com/articles/News/OS-CCK-Documentation/">Documentation</a> and <a href="<?php echo $mosConfig_live_site . "administrator/components/com_os_cck/doc/LICENSE.txt"; ?>"
                        target="_blank">Licensing</a></p>
            <p>Check out other extension <a href="http://ordasoft.com/" target="_blank">http://ordasoft.com/</a></p>
        </div>
    </div>

    <?php
    if ($is_warning) return '<h2><font color="red">The OrdaSoft CCK Component installed with a warning about a missing PHP extension! Please read carefully and uninstall OrdaSoft CCK. Next fix your PHP installation and then install OrdaSoft CCK again.</font></h2>';
}

?>
