<?php
/**
 * Legacy class, replaced by full MVC implementation.  See {@link JController}
 *
 * @deprecated    As of version 1.5
 * @package    Joomla.Legacy
 * @subpackage	3.0
 * This file provides compatibility for simplemembership Library on Joomla 3.0! 
 *
 */
// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');
if (!class_exists('mosHTML')) {
    class mosHTML {
        /**
         * Legacy function, use {@link JHTML::_('select.option')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function makeOption($value, $text = '', $value_name = 'value', $text_name = 'text') {
            return JHTML::_('select.option', $value, $text, $value_name, $text_name);
        }
        /**
         * Legacy function, use {@link JHTML::_('select.genericlist')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function selectList(&$arr, $tag_name, $tag_attribs, $key, $text, $selected = NULL, $idtag = false, $flag = false) {
            return JHTML::_('select.genericlist', $arr, $tag_name, $tag_attribs, $key, $text, $selected, $idtag, $flag);
        }
        /**
         * Legacy function, use {@link JHTML::_('select.integerlist')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function integerSelectList($start, $end, $inc, $tag_name, $tag_attribs, $selected, $format = "") {
            return JHTML::_('select.integerlist', $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format);
        }
        /**
         * Legacy function, use {@link JHTML::_('select.radiolist')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function radioList(&$arr, $tag_name, $tag_attribs, $selected = null, $key = 'value', $text = 'text', $idtag = false) {
            return JHTML::_('select.radiolist', $arr, $tag_name, $tag_attribs, $key, $text, $selected, $idtag);
        }
        /**
         * Legacy function, use {@link JHTML::_('select.booleanlist')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function yesnoRadioList($tag_name, $tag_attribs, $selected, $yes = 'yes', $no = 'no', $id = false) {
            return JHTML::_('select.booleanlist', $tag_name, $tag_attribs, $selected, $yes, $no, $id);
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function writableCell($folder, $relative = 1, $text = '', $visible = 1) {
            $writeable = '<b><font color="green">' . JText::_('Writable') . '</font></b>';
            $unwriteable = '<b><font color="red">' . JText::_('Unwritable') . '</font></b>';
            echo '<tr>';
            echo '<td class="item">';
            echo $text;
            if ($visible) {
                echo $folder . '/';
            }
            echo '</td>';
            echo '<td >';
            if ($relative) {
                echo is_writable("../$folder") ? $writeable : $unwriteable;
            } else {
                echo is_writable("$folder") ? $writeable : $unwriteable;
            }
            echo '</td>';
            echo '</tr>';
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function monthSelectList($tag_name, $tag_attribs, $selected) {
            $arr = array(mosHTML::makeOption('01', JText::_('JANUARY_SHORT')), mosHTML::makeOption('02', JText::_('FEBRUARY_SHORT')), mosHTML::makeOption('03', JText::_('MARCH_SHORT')), mosHTML::makeOption('04', JText::_('APRIL_SHORT')), mosHTML::makeOption('05', JText::_('MAY_SHORT')), mosHTML::makeOption('06', JText::_('JUNE_SHORT')), mosHTML::makeOption('07', JText::_('JULY_SHORT')), mosHTML::makeOption('08', JText::_('AUGUST_SHORT')), mosHTML::makeOption('09', JText::_('SEPTEMBER_SHORT')), mosHTML::makeOption('10', JText::_('OCTOBER_SHORT')), mosHTML::makeOption('11', JText::_('NOVEMBER_SHORT')), mosHTML::makeOption('12', JText::_('DECEMBER_SHORT')));
            return mosHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
        }
        //// End Select Category list ////
        
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function treeSelectList(&$src_list, $src_id, $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected) {
            // establish the hierarchy of the menu
            $children = array();
            // first pass - collect children
            foreach($src_list as $v) {
                $pt = $v->parent;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
            // second pass - get an indent list of the items
            $ilist = JHTML::_('menu.treerecurse', 0, '', array(), $children);
            // assemble menu items to the array
            $this_treename = '';
            foreach($ilist as $item) {
                if ($this_treename) {
                    if ($item->id != $src_id && strpos($item->treename, $this_treename) === false) {
                        $tgt_list[] = mosHTML::makeOption($item->id, $item->treename);
                    }
                } else {
                    if ($item->id != $src_id) {
                        $tgt_list[] = mosHTML::makeOption($item->id, $item->treename);
                    } else {
                        $this_treename = "$item->treename/";
                    }
                }
            }
            // build the html select list
            return mosHTML::selectList($tgt_list, $tag_name, $tag_attribs, $key, $text, $selected);
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function yesnoSelectList($tag_name, $tag_attribs, $selected, $yes = 'yes', $no = 'no') {
            $arr = array(mosHTML::makeOption(0, JText::_($no)), mosHTML::makeOption(1, JText::_($yes)),);
            return mosHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', (int)$selected);
        }
        /**
         * Legacy function, use {@link JHTML::_('grid.id')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function idBox($rowNum, $recId, $checkedOut = false, $name = 'cid') {
            return JHTML::_('grid.id', $rowNum, $recId, $checkedOut, $name);
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function sortIcon($text, $base_href, $field, $state = 'none') {
            $alts = array('none' => JText::_('No Sorting'), 'asc' => JText::_('Sort Ascending'), 'desc' => JText::_('Sort Descending'),);
            $next_state = 'asc';
            if ($state == 'asc') {
                $next_state = 'desc';
            } else if ($state == 'desc') {
                $next_state = 'none';
            }
            if ($state == 'none') {
                $img = '';
            } else {
                $img = "<img src=\"images/sort_$state.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"{$alts[$next_state]}\" />";
            }
            $html = "<a href=\"$base_href&field=$field&order=$next_state\">" . JText::_($text) . '&nbsp;&nbsp;' . $img . "</a>";
            return $html;
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function CloseButton(&$params, $hide_js = NULL) {
            // displays close button in Pop-up window
            if ($params->get('popup') && !$hide_js) {
?>
              <div align="center" style="margin-top: 30px; margin-bottom: 30px;">
                  <script type="text/javascript">
                      document.write('<a href="#" onclick="javascript:window.close();"><span class="small"><?php echo JText::_('Close Window'); ?></span></a>');
                  </script>
                  <?php
                if ($_SERVER['HTTP_REFERER'] != "") {
                    echo '<noscript>';
                    echo '<a href="' . $_SERVER['HTTP_REFERER'] . '"><span class="small">' . JText::_('BACK') . '</span></a>';
                    echo '</noscript>';
                }
?>
              </div>
              <?php
            }
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function BackButton(&$params, $hide_js = NULL) {
            // Back Button
            if ($params->get('back_button') && !$params->get('popup') && !$hide_js) {
?>
              <div class="back_button">
                  <a href='javascript:history.go(-1)'>
                      <?php echo JText::_('BACK'); ?></a>
              </div>
              <?php
            }
        }
        /**
         * Legacy function, use {@link JFilterOutput::cleanText()} instead
         *
         * @deprecated    As of version 1.5
         */
        static function cleanText(&$text) {
            return JFilterOutput::cleanText($text);
        }
        /**
         * Legacy function, deprecated
         *
         * @deprecated    As of version 1.5
         */
        static function PrintIcon(&$row, &$params, $hide_js, $link, $status = NULL) {
            if ($params->get('print') && !$hide_js) {
                // use default settings if none declared
                if (!$status) {
                    $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
                }
                // checks template image directory for image, if non found default are loaded
                if ($params->get('icons')) {
                    $image = mosAdminMenus::ImageCheck('printButton.png', '/images/M_images/', NULL, NULL, JText::_('Print'), JText::_('Print'));
                } else {
                    $image = JText::_('ICON_SEP') . '&nbsp;' . JText::_('Print') . '&nbsp;' . JText::_('ICON_SEP');
                }
                if ($params->get('popup') && !$hide_js) {
                    // Print Preply button - used when viewing page
                    
?>
                  <script type="text/javascript">
                      document.write('<td align="right" width="100%" class="buttonheading">');
                      document.write('<a href="#" onclick="javascript:window.print(); return false" title="<?php echo JText::_('Print'); ?>">');
                      document.write('<?php echo $image; ?>');
                      document.write('</a>');
                      document.write('</td>');
                  </script>
                  <?php
                } else {
                    // Print Button - used in pop-up window
                    
?>
                  <td align="right" width="100%" class="buttonheading">
                  <a href="<?php echo $link; ?>" onclick="window.open('<?php echo $link; ?>','win2','<?php echo $status; ?>'); return false;" title="<?php echo JText::_('Print'); ?>">
                      <?php echo $image; ?></a>
                  </td>
                  <?php
                }
            }
        }
        /**
         * Legacy function, use {@link JHTML::_('email.cloak')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function emailCloaking($mail, $mailto = 1, $text = '', $email = 1) {
            return JHTML::_('email.cloak', $mail, $mailto, $text, $email);
        }
        /**
         * Legacy function, use {@link JHTML::_('behavior.keepalive')} instead
         *
         * @deprecated    As of version 1.5
         */
        static function keepAlive() {
            echo JHTML::_('behavior.keepalive');
        }
    }
}
