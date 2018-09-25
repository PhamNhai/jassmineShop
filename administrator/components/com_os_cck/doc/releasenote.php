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

// load language 
global $mosConfig_absolute_path, $mosConfig_lang, $mosConfig_live_site,$os_cck_configuration;
?>

<table class="adminform">
  <tr>
    <td>
      <h3><?php echo JText::_('COM_OS_CCK_DOC_GENERAL_INFO'); ?></h3>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <strong> <?php echo JText::_('COM_OS_CCK_DOC_VERSION'); ?></strong>
    </td>
    <td>
      <strong>v <?php echo '3.3.6';  ?></strong>
    </td>
  </tr>
  <tr>
    <td>
      <strong><?php echo JText::_('COM_OS_CCK_DOC_RELEASE_DATE'); ?></strong>
    </td>
    <td>
      <strong><?php echo 'February 2018' ?></strong>
    </td>
  </tr>
  <tr>
    <td>
      <strong><?php echo JText::_('COM_OS_CCK_DOC_PROJECTLINK'); ?></strong>
    </td>
    <td>
      <strong>
        <a href="http://www.ordasoft.com" target="blank">www.ordasoft.com</a>
      </strong>
    </td>
  </tr>
  <tr>
    <td>
      <strong><?php echo JText::_('COM_OS_CCK_DOC_PROJECT_HOST'); ?></strong>
    </td>
    <td>
      <strong>Andrey Kvasnevskiy (<a href="mailto:akbet@ordasoft.com">akbet@ordasoft.com</a>)
      </strong>
    </td>
  </tr>
  <tr>
    <td valign="top">
        <strong><?php echo JText::_('COM_OS_CCK_DOC_LICENSE'); ?></strong>
    </td>
    <td>
      <strong>
          <a href="<?php echo JURI::base() . "components/com_os_cck/doc/LICENSE.txt"; ?>"
             target="blank">License</a>
      </strong>
      <br/>
      <?php echo JText::_('COM_OS_CCK_DOC_WARRANTY'); ?>
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>README:</strong>
    </td>
    <td>
      <strong>
          <a href="<?php echo JURI::base() . "components/com_os_cck/doc/README.txt"; ?>"
             target="blank">README</a>
      </strong>
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong><?php echo JText::_('COM_OS_CCK_DOC_DEVELOP'); ?></strong>
    </td>
    <td>
      <ul>
         <li><b>v 3.3</b> - Orda Soft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru">akbet@mail.ru</a>)<br>Roman Akoev(<a href="mailto:akoevroman@gmail.com">akoevroman@gmail.com</a>)
        <br>Buchastiy Sergey(<a href="mailto:buchastiy1989@gmail.com">buchastiy1989@gmail.com</a>)</li>
        <li><b>v 2.5</b> - Orda Soft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru">akbet@mail.ru</a>)<br>Roman Akoev(<a href="mailto:akoevroman@gmail.com">akoevroman@gmail.com</a>)
        <br>Buchastiy Sergey(<a href="mailto:buchastiy1989@gmail.com">buchastiy1989@gmail.com</a>)
        <li><b>v 2.0</b> - Orda Soft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru">akbet@mail.ru</a>)<br>Roman Akoev(<a href="mailto:akoevroman@gmail.com">akoevroman@gmail.com</a>)
        <br>Buchastiy Sergey(<a href="mailto:buchastiy1989@gmail.com">buchastiy1989@gmail.com</a>)
        <li><b>v 1.1</b> - Orda Soft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru">akbet@mail.ru</a>)<br>Roman Akoev(<a href="mailto:akoevroman@gmail.com">akoevroman@gmail.com</a>)
        <li><b>v 1.0</b> - Orda Soft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru">akbet@mail.ru</a>)<br>Roman Akoev(<a href="mailto:akoevroman@gmail.com">akoevroman@gmail.com</a>)
        </li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      &nbsp;
    </td>
  </tr>
</table>

