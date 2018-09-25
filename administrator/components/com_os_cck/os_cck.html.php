<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) 
  die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

class HTML_os_cck{

  static function sort_head($title, $fieldname, $sort_arr,$task=''){
    $img_str = "";
    if($task)
        $task = "&amp;task=$task";
    $direction=$sort_arr['sorting_direction'];
    if ($sort_arr['sorting_direction'] == 'ASC') {
      $img_path = 'components/com_os_cck/images/arrow_up.png';
      $img_str = "<img src=\"$img_path\" width=\"12\" height=\"12\" border=\"0\" alt='Sorted up' />";
    } else {
      $img_path = 'components/com_os_cck/images/arrow_down.png';
      $img_str = "<img src=\"$img_path\" width=\"12\" height=\"12\" border=\"0\" alt='Sorted up' />";
    }
    $str = "<a href='" . JURI::root() . "/administrator/index.php?option=com_os_cck$task&amp;sort=$fieldname&amp;sorting_direction=$direction"."'>".
    $img_str .
    $title .
    "</a>";
    return $str;
  }

  //------------------------------------------------------------------
  static function about($avaibleUpdate){
    global $app;
    $html = "<div class='os_cck_caption' ><img src='./components/com_os_cck/images/os_cck_logo.png' alt ='Config' />" . JText::_('COM_OS_CCK_ADMIN_ABOUT') . "</div>";
    $app->JComponentTitle = $html;
    ?>
    <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
    <?php if($avaibleUpdate){?>
      <span class="cck-new-version">Avaible new version.</span>
    <?php }?>
      <?php
      $options = Array();
      echo JHtml::_('tabs.start', 'aboutPane', $options);
      echo JHtml::_('tabs.panel', JText::_('COM_OS_CCK_ADMIN_ABOUT_ABOUT'), 'panel_1_id');
      ?>
      <table class="adminform">
        <tr>
          <td>
            <img src="./components/com_os_cck/images/os_cck_logo2.png" alt="OS_CCK logo"/>
          </td>
        </tr>
        <tr>
          <td>
            <h3><?PHP echo JText::_('COM_OS_CCK__HTML_ABOUT'); ?></h3>
            <?PHP echo JText::_('COM_OS_CCK_HTML_ABOUT_INTRO'); ?>
          </td>
        </tr>
      </table>
      <?php
      //******************************   tab--2 about   **************************************
      echo JHtml::_('tabs.panel', JText::_('COM_OS_CCK_ADMIN_ABOUT_RELEASENOTE'), 'panel_2_id');
      include_once("./components/com_os_cck/doc/releasenote.php");
      //******************************   tab--3 about--changelog.txt   ***********************
      echo JHtml::_('tabs.panel', JText::_('COM_OS_CCK_ADMIN_ABOUT_CHANGELOG'), 'panel_2_id');
      include_once("./components/com_os_cck/doc/changelog.html");
      echo JHtml::_('tabs.end');
      ?>
    </form>
    <?php
  }
}

