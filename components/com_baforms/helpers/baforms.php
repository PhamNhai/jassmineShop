<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.fole');

abstract class baformsHelper 
{
    public static $path;

    public static function addStyle()
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select("alow_captcha")
            ->from("#__baforms_forms")
            ->where("alow_captcha <> ".$db->quote(0))
            ->where("published = 1");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $item) {
            $captch = JCaptcha::getInstance($item->alow_captcha);
            if (is_object($captch)) {
                $captch->initialise($item->alow_captcha);
            }
        }
    }

    public static function getMapsKey()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`key`')
            ->from('`#__baforms_api`')
            ->where('`service` = '.$db->quote('google_maps'));
        $db->setQuery($query);
        $key = $db->loadResult();
        return $key;
    }

    public static function getType($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("display_popup, button_type");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadAssoc();
        return $items;
    }

    public static function loadJQuery($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('load_jquery')
            ->from('#__baforms_forms')
            ->where('`id` = '.$id);
        $db->setQuery($query);
        $res = $db->loadResult();
        
        return $res;
    }
    
    public static function addScripts($cid)
    {
        $doc = JFactory::getDocument();
        $scripts = $doc->_scripts;
        $array = array();
        $map = true;
        $date = true;
        $slider = true;
        $stripe = true;
        $jquery = true;
        foreach ($scripts as $key => $script) {
            if (strpos($key, 'maps.googleapis.com/maps/api/js?libraries=places')) {
                $map = false;
            }
            $key = explode('/', $key);
            $array[] = end($key);
        }
        $html = '';
        if (!in_array('jquery.min.js', $array) && !in_array('jquery.js', $array)) {
            $html .= '<script type="text/javascript" src="' .JUri::root(true). '/media/jui/js/jquery.min.js"></script>';
        }
        foreach ($cid as $id) {
            if (!$jquery || self::loadJQuery($id) == 0) {
                
            } else if (!in_array('jquery.min.js', $array) && !in_array('jquery.js', $array)) {
                $html .= '<script type="text/javascript" src="' .JUri::root(true). '/media/jui/js/jquery.min.js"></script>';
                $jquery = false;
            }
            $payments = self::getPayment($id);
            $submissionsOptions = self::getSubmisionOptions($id);
            $method = $submissionsOptions['payment_methods'];
            if ($stripe && ($payments->multiple_payment == 1 || ($method == 'stripe' && $submissionsOptions['display_total'] == 1))) {
                $html .= '<script type="text/javascript" src="https://checkout.stripe.com/checkout.js"></script>';
                $stripe = false;
            }
            $captcha = self::getCaptcha($id);
            if ($captcha != '0') {
                $captch = JCaptcha::getInstance($captcha);
                $captch->initialise($captcha);
            }
            $elements = self::getElement($id);
            foreach ($elements as $element) {
                $element = explode('_-_', $element->settings);
                if ($element[2] == 'map' || $element[2] == 'address') {
                    if ($map) {
                        $api_key = self::getMapsKey();
                        $src = 'https://maps.googleapis.com/maps/api/js?libraries=places&key='.$api_key;
                        $html .= '<script type="text/javascript" src="'.$src.'"></script>';
                        $map = false;
                    }
                }
                if ($element[2] == 'date' && $date) {
                    $date = false;
                    $html .= '<script type="text/javascript" src="'.JUri::root(true) . '/media/system/js/calendar.js"></script>';
                    $html .= '<script type="text/javascript" src="'.JUri::root(true) . '/media/system/js/calendar-setup.js"></script>';
                    $html .= '<script type="text/javascript">'.self::setCalendar().'</script>';
                    $html .= '<link rel="stylesheet" href="' .JUri::root(true) . '/media/system/css/calendar-jos.css">';
                }
                if ($element[2] == 'slider' && $slider) {
                    $date = true;
                    $html .= '<script type="text/javascript" src="'.JUri::root() . 'components/com_baforms/libraries/bootstrap-slider/bootstrap-slider.js"></script>';
                }
            }
        }        
        $src = 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css';
        $html .= '<link rel="stylesheet" href="'.$src.'">';
        $html .= '<script type="text/javascript" src="' .JUri::root() . 'components/com_baforms/libraries/modal/ba_modal.js"></script>';
        $html .= '<link rel="stylesheet" href="' .JURI::root() . 'components/com_baforms/assets/css/ba-style.css">';
        $html .= '<script type="text/javascript" src="' .JURI::root() . 'components/com_baforms/assets/js/ba-form.js"></script>';
        
        return $html;
    }

    public static function drawScripts($id)
    {
        $doc = JFactory::getDocument();
        $scripts = $doc->_scripts;
        $array = array();
        $map = true;
        foreach ($scripts as $key=>$script) {
            if (strpos($key, 'maps.googleapis.com/maps/api/js?libraries=places')) {
                $map = false;
            }
            $key = explode('/', $key);
            $array[] = end($key);
        }
        $html = '';
        if (!in_array('jquery.min.js', $array) && !in_array('jquery.js', $array)) {
            $html .= '<script type="text/javascript" src="' .JUri::root(true). '/media/jui/js/jquery.min.js"></script>';
        }
        $captcha = self::getCaptcha($id);
        if ($captcha != '0') {
            $captch = JCaptcha::getInstance($captcha);
            $captch->initialise($captcha);
        }
        $elements = self::getElement($id);
        foreach ($elements as $element) {
            $element = explode('_-_', $element->settings);
            if ($element[2] == 'map' || $element[2] == 'address') {
                if ($map) {
                    $api_key = self::getMapsKey();
                    $src = 'https://maps.googleapis.com/maps/api/js?libraries=places&key='.$api_key;
                    $html .= '<script type="text/javascript" src="'.$src.'"></script>';
                }
            }
            if ($element[2] == 'date') {
                $html .= '<script type="text/javascript" src="'.JUri::root(true) . '/media/system/js/calendar.js"></script>';
                $html .= '<script type="text/javascript" src="'.JUri::root(true) . '/media/system/js/calendar-setup.js"></script>';
                $html .= '<script type="text/javascript">'.self::setCalendar().'</script>';
                $html .= '<link rel="stylesheet" href="' .JUri::root(true) . '/media/system/css/calendar-jos.css">';
            }
            if ($element[2] == 'slider') {
                $html .= '<script type="text/javascript" src="'.JUri::root() . 'components/com_baforms/libraries/bootstrap-slider/bootstrap-slider.js"></script>';
            }
        }
        $html .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">';
        $html .= '<script type="text/javascript" src="' .JUri::root() . 'components/com_baforms/libraries/modal/ba_modal.js"></script>';
        $html .= '<link rel="stylesheet" href="' .JURI::root() . 'components/com_baforms/assets/css/ba-style.css">';
        $html .= '<script type="text/javascript" src="' .JURI::root() . 'components/com_baforms/assets/js/ba-form.js"></script>';
        
        return $html; 
    }

    public static function setCalendar()
    {
        $_DN = array(JText::_('SUNDAY'), JText::_('MONDAY'), JText::_('TUESDAY'), JText::_('WEDNESDAY'),
            JText::_('THURSDAY'), JText::_('FRIDAY'), JText::_('SATURDAY'), JText::_('SUNDAY'));
        $_SDN = array(JText::_('SUN'), JText::_('MON'), JText::_('TUE'), JText::_('WED'), JText::_('THU'),
            JText::_('FRI'), JText::_('SAT'), JText::_('SUN'));
        $_MN = array(JText::_('JANUARY'), JText::_('FEBRUARY'), JText::_('MARCH'), JText::_('APRIL'),
            JText::_('MAY'), JText::_('JUNE'), JText::_('JULY'), JText::_('AUGUST'), JText::_('SEPTEMBER'),
            JText::_('OCTOBER'), JText::_('NOVEMBER'), JText::_('DECEMBER'));
        $_SMN = array(JText::_('JANUARY_SHORT'), JText::_('FEBRUARY_SHORT'), JText::_('MARCH_SHORT'),
            JText::_('APRIL_SHORT'), JText::_('MAY_SHORT'), JText::_('JUNE_SHORT'), JText::_('JULY_SHORT'),
            JText::_('AUGUST_SHORT'), JText::_('SEPTEMBER_SHORT'), JText::_('OCTOBER_SHORT'),
            JText::_('NOVEMBER_SHORT'), JText::_('DECEMBER_SHORT'));
        $today = " " . JText::_('JLIB_HTML_BEHAVIOR_TODAY') . " ";
        $_TT = array('INFO' => JText::_('JLIB_HTML_BEHAVIOR_ABOUT_THE_CALENDAR'),
            'ABOUT' => "DHTML Date/Time Selector\n"
            . "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n"
            . "For latest version visit: http://www.dynarch.com/projects/calendar/\n"
            . "Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details."
            . "\n\n" . JText::_('JLIB_HTML_BEHAVIOR_DATE_SELECTION')
            . JText::_('JLIB_HTML_BEHAVIOR_YEAR_SELECT')
            . JText::_('JLIB_HTML_BEHAVIOR_MONTH_SELECT')
            . JText::_('JLIB_HTML_BEHAVIOR_HOLD_MOUSE'),
            'ABOUT_TIME' => "\n\n"
            . "Time selection:\n"
            . "- Click on any of the time parts to increase it\n"
            . "- or Shift-click to decrease it\n"
            . "- or click and drag for faster selection.",
            'PREV_YEAR' => JText::_('JLIB_HTML_BEHAVIOR_PREV_YEAR_HOLD_FOR_MENU'),
            'PREV_MONTH' => JText::_('JLIB_HTML_BEHAVIOR_PREV_MONTH_HOLD_FOR_MENU'),
            'GO_TODAY' => JText::_('JLIB_HTML_BEHAVIOR_GO_TODAY'),
            'NEXT_MONTH' => JText::_('JLIB_HTML_BEHAVIOR_NEXT_MONTH_HOLD_FOR_MENU'),
            'SEL_DATE' => JText::_('JLIB_HTML_BEHAVIOR_SELECT_DATE'),
            'DRAG_TO_MOVE' => JText::_('JLIB_HTML_BEHAVIOR_DRAG_TO_MOVE'),
            'PART_TODAY' => $today,
            'DAY_FIRST' => JText::_('JLIB_HTML_BEHAVIOR_DISPLAY_S_FIRST'),
            'WEEKEND' => JFactory::getLanguage()->getWeekEnd(),
            'CLOSE' => JText::_('JLIB_HTML_BEHAVIOR_CLOSE'),
            'TODAY' => JText::_('JLIB_HTML_BEHAVIOR_TODAY'),
            'TIME_PART' => JText::_('JLIB_HTML_BEHAVIOR_SHIFT_CLICK_OR_DRAG_TO_CHANGE_VALUE'),
            'DEF_DATE_FORMAT' => "%Y-%m-%d",
            'TT_DATE_FORMAT' => JText::_('JLIB_HTML_BEHAVIOR_TT_DATE_FORMAT'),
            'WK' => JText::_('JLIB_HTML_BEHAVIOR_WK'),
            'TIME' => JText::_('JLIB_HTML_BEHAVIOR_TIME')
        );

        return 'Calendar._DN = ' . json_encode($_DN) . ';'
            . ' Calendar._SDN = ' . json_encode($_SDN) . ';'
            . ' Calendar._FD = 0;'
            . ' Calendar._MN = ' . json_encode($_MN) . ';'
            . ' Calendar._SMN = ' . json_encode($_SMN) . ';'
            . ' Calendar._TT = ' . json_encode($_TT) . ';';
    }

    protected static function restoreHTML($formSettings, $element, $submissionsOptions, $elements)
    {
        $html = '';
        $settings = explode('_-_', $element->settings);
        $symbol = $submissionsOptions['currency_symbol'];
        $position = $submissionsOptions['currency_position'];
        $language = JFactory::getLanguage();
        $language->load('com_baforms', JPATH_ADMINISTRATOR);
        $path = self::getPath($settings[2].'.php');
        if ($path) {
            include $path;
            $html .= $out;
        }
        return $html;
    }

    public static function setPath()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('template')
            ->from('#__template_styles')
            ->where('`client_id`=0')
            ->where('`home`=1');
        $db->setQuery($query);
        $template = $db->loadResult();
        self::$path = array();
        $path = JPATH_ROOT . '/templates/'.$template.'/html/com_baforms/form/';
        if (JFolder::exists($path)) {
            self::$path[] = $path;
        }
        $path = JPATH_ROOT . '/components/com_baforms/views/form/tmpl/';
        self::$path[] = $path;
    }

    public static function getPath($name)
    {
        $file = false;
        foreach (self::$path as $value) {
            if (JFile::exists($value.$name)) {
                $file = $value.$name;
                break;
            }
        }

        return $file;
    }

    public static function getSaveOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('save_continue as enable, save_continue_label as label, save_continue_size as size,
            save_continue_weight as weight, save_continue_align as align, save_continue_color as color,
            save_continue_popup_title as title, save_continue_popup_message as message')
            ->from('#__baforms_forms')
            ->where('`id` = '.$id);
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    public static function getTokenData($token)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('data')
            ->from('#__baforms_tokens')
            ->where('`token` = '.$db->quote($token));
        $db->setQuery($query);
        $data = $db->loadResult();
        
        return $data;
    }

    public static function getToken()
    {
        $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
        $bf_token = '';
        if (isset($_GET['bf_token'])) {
            $bf_token = $_GET['bf_token'];
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $db = JFactory::getDbo();
        $now = strtotime(date('Y-m-d G:i:s'));
        $query = $db->getQuery(true)
            ->delete('#__baforms_tokens')
            ->where('`expires` < ' .$db->quote($now));
        $db->setQuery($query);
        $db->execute();
        $query = $db->getQuery(true);
        $query->select('token')
            ->from('#__baforms_tokens');
        if (empty($bf_token)) {
            $query->where('`ip` = '.$db->quote($ip));
        } else {
            $query->where('`token` = '.$db->quote($bf_token), 'OR');
            $query->where('`ip` = '.$db->quote($ip));
        }
        $db->setQuery($query);
        $result = $db->loadResult();
        if (!empty($result)) {
            $token = $result;
        }

        return $token;
    }
    
    public static function drawHTMLPage($id)
    {
        self::setPath();
        $form = self::getForm($id);
        $payments = self::getPayment($id);
        $columns = self::getColumn($id);
        $elements = self::getElement($id);
        $popup = self::getPopup($id);
        $submissionsOptions = self::getSubmisionOptions($id);
        $saveContinue = self::getSaveOptions($id);
        $symbol = $submissionsOptions['currency_symbol'];
        $position = $submissionsOptions['currency_position'];
        $embed = self::getEmbed($id);
        $method = $submissionsOptions['payment_methods'];
        $title = $form[0]->title;
        $titleSettings = $form[0]->title_settings;
        $formSettings = $form[0]->form_settings;
        $formSettings = explode('/', $formSettings);
        $url = 'index.php?option=com_baforms&view=form&form_id='.$id;
        $language = JFactory::getLanguage();
        $language->load('com_baforms', JPATH_ADMINISTRATOR);
        $formStyle = explode(';', $formSettings[9]);
        if (!isset($formSettings[11])) {
            $formSettings[11] = 24;
            $formSettings[12] = '#dedede';
        }
        if (empty($submissionsOptions['message_bg_rgba'])) {
            $submissionsOptions['message_bg_rgba'] = '#ffffff';
        }
        if (empty($submissionsOptions['message_color_rgba'])) {
            $submissionsOptions['message_color_rgba'] = '#333333';
        }
        if (empty($submissionsOptions['dialog_color_rgba'])) {
            $submissionsOptions['dialog_color_rgba'] = 'rgba(0, 0, 0, 0.15)';
        }
        $html = "<div class='com-baforms " .$formSettings[0]. "'>";
        $path = self::getPath('style.php');
        if ($path) {
            include $path;
            $html .= $out;
        }
        $html .= '<div class="modal-scrollable ba-forms-modal" style="display:none; background-color: ';
        $html .= $submissionsOptions['dialog_color_rgba'].';">';
        $html .= '<div class="ba-modal fade hide message-modal"';
        $html .= ' style="color:' .$submissionsOptions['message_color_rgba'];
        $html .= '; background-color: ' .$submissionsOptions['message_bg_rgba'];
        $html .= ';"><a href="#" class="ba-modal-close zmdi zmdi-close"></a>';
        $html .= '<div class="ba-modal-body"><div class="message"></div></div></div></div>';
        if ($popup['display_popup'] == 1) {
            if ($popup['button_type'] != 'link') {
                $html .= '<div class="btn-' .$popup['button_position']. '">';
                $html .= "<input type='button' value='".$popup['button_lable'];
                $html .= "' style='background-color: " .$popup['button_bg'];
                $html .= "; font-weight:" .$popup['button_weight'];
                $html .= "; border-radius:" .$popup['button_border']. "px";
                $html .= "; font-size:" .$popup['button_font_size']. "px";
                $html .= "; color: " .$popup['button_color']. "'";
                $html .= " data-popup='popup-form-".$id."' class='popup-btn'>";
                $html .= '</div>';
            } else {
                $html .= '<a href="#" class="popup-btn" data-popup="popup-form-';
                $html .= $id.'">'.$popup['button_lable'].'</a>';
            }
            $html .= '<div class="modal-scrollable  ba-forms-modal" style="display: none; background-color: ';
            $html .= $submissionsOptions['dialog_color_rgba'].';"><div class="ba-modal';
            $html .= ' fade hide popup-form" id="popup-form-'.$id.'" style="display: none; ';
            $html .= 'width: ' .$popup['modal_width']. 'px">';
            $html .= '<a href="#" class="ba-modal-close zmdi zmdi-close"></a><div class="ba-modal-body">';
        }
        if ($saveContinue->enable == 1) {
            $token = self::getToken();
            $path = self::getPath('save-continue.php');
            if ($path) {
                include $path;
                $html .= $out;
            }
        }
        $html .= '<form novalidate id="baform-'.$id.'" action="' . $url. '"';
        $html .= ' method="post" class="form-validate';
        if ($method != '' && $submissionsOptions['display_total'] == 1) {
            $html .= ' ba-payment';
        }
        $html .= '" enctype="multipart/form-data">';
        $html .= '<div style="' ;
        if ($popup['display_popup'] == 0) {
            $html .= $formStyle[0]. '; ';
        }
        $html .= $formStyle[1]. ';' . $formStyle[2]. ';' . $formStyle[3];
        $html .= '" class="ba-form">';
        if ($submissionsOptions['display_title'] == 1) {
            $html .= '<div class="row-fluid ba-row"><div class="span12" style="' .$titleSettings. '">';
            $html.= $title . '</div></div>';
        }
        $row = '';
        if (empty($columns)) {
            foreach ($elements as $element) {
                $element = explode('_-_', $element->settings);
                if ($element[0] == 'button') {
                    $button = $element[1];
                    $buttonStyle = $element[2];
                    $buttonAligh = $element[3];
                }
            }
        }
        $n = 1;
        $html .= '<div class="page-0">';
        $columnFlag = false;
        foreach ($columns as $column) {
            if (strpos($column->settings, 'first') !== false) {
                $columnFlag = true;
                break;
            }
        }
        foreach ($columns as $column) {
            $column = explode(',',$column->settings);
            if (trim($column[1]) == 'spank') {
                if (count($column) > 6) {
                    $column[3] = $column[3].','.$column[4].','.$column[5].','.$column[6];
                    $column[3] .= ','.$column[7].','.$column[8].','.$column[9];
                    $column[4] = $column[10];
                    $column[5] = $column[11];
                    if (count($column) > 12) {
                        $column[5] .= ','.$column[12].','.$column[13].','.$column[14];
                        $column[5] .= ','.$column[15].','.$column[16].','.$column[17];
                    }
                }
                $prev = $column[3];
                $prev = explode(';', $prev);
                $next = $column[5];
                $next = explode(';', $next);
                if (strpos($prev[3], 'rgb') === false) {
                    $prev[3] = '#'.$prev[3];
                }
                if (strpos($prev[4], 'rgb') === false) {
                    $prev[4] = '#'.$prev[4];
                }
                if (strpos($next[3], 'rgb') === false) {
                    $next[3] = '#'.$next[3];
                }
                if (strpos($next[3], 'rgb') === false) {
                    $next[4] = '#'.$next[4];
                }
                if ($n != 1) {
                    $html .= '<div class="ba-prev"><input type="button" value="';
                    $html .= $prev[0].'" style="border-radius:' .$prev[7];
                    $html .= 'px; background-color: ' .$prev[3]. '; font-size:';
                    $html .= $prev[5]. 'px; font-weight:' .$prev[6]. '; width:';
                    $html .= $prev[1]. 'px; height:' .$prev[2]. 'px; color: ' .$prev[4];
                    $html .= '" class="btn-prev"></div>';
                }
                if ($n == 1) {
                    $last = $prev;
                }
                $html .= '<div class="ba-next"><input type="button" value="';
                $html .= $next[0].'" style="border-radius:' .$next[7];
                $html .= 'px; background-color: ' .$next[3]. '; font-size:';
                $html .= $next[5]. 'px; font-weight:' .$next[6]. '; width:';
                $html .= $next[1]. 'px; height:' .$next[2]. 'px; color: ' .$next[4];
                $html .= '" class="btn-next"></div>';
                if ($saveContinue->enable == 1) {
                    $html .= '<div class="ba-save-continue" style="text-align: ';
                    $html .= $saveContinue->align.';"><a href="#" ';
                    $html .= 'class="get-save-continue" style="font-size: '.$saveContinue->size;
                    $html .= 'px; font-weight: '.$saveContinue->weight.'; color: '.$saveContinue->color;
                    $html .= ';">'.$saveContinue->label.'</a></div>';
                }
                $html .= '</div><div class="page-' .$n. '" style="display:none">';
                $n++;
            }
            if (!$columnFlag) {
                if (trim($column[1]) == 'span12') {
                    $html .= '<div class="row-fluid ba-row">';
                }
                if (trim($column[1]) == 'span6') {
                    if ($row == 1) {
                        $row = 2;
                    }
                    if ($row == '') {
                        $html .= '<div class="row-fluid ba-row">';
                        $row = 1;
                    }
                }
                if (trim($column[1]) == 'span4') {
                    if ($row == 2) {
                        $row = 3;
                    }
                    if ($row == 1) {
                        $row = 2;
                    }
                    if ($row == '') {
                        $html .= '<div class="row-fluid ba-row">';
                        $row = 1;
                    }
                }
                if (trim($column[1]) == 'span3') {
                    if ($row == 3) {
                        $row = 4;
                    }
                    if ($row == 2) {
                        $row = 3;
                    }
                    if ($row == 1) {
                        $row = 2;
                    }
                    if ($row == '') {
                        $html .= '<div class="row-fluid ba-row">';
                        $row = 1;
                    }
                }
            } else {
                if (isset($column[2]) && $column[2] == 'first') {
                    $html .= '<div class="row-fluid ba-row">';
                }
            }
            if (trim($column[1]) != 'spank') {
                $html .= '<div class="' .$column[1]. '">';
                foreach ($elements as $element) {
                    $settings = explode('_-_', $element->settings);
                    if ($settings[0] == 'button') {
                        $button = $settings[1];
                        $buttonStyle = $settings[2];
                        $buttonAligh = $settings[3];
                    }
                    if ($settings[0] == $column[0]) {
                        $html .= self::restoreHTML($formSettings, $element, $submissionsOptions, $elements);
                    }
                }
                $html .= '</div>';
            }
            if (trim($column[1]) == 'span12') {
                $html .= '</div>';
            }
            if (!$columnFlag) {
                if (trim($column[1]) == 'span6') {
                    if ($row == 2) {
                        $html .= '</div>';
                        $row = '';
                    }
                }
                if (trim($column[1]) == 'span4') {
                    if ($row == 3) {
                        $html .= '</div>';
                        $row = '';
                    }
                }
                if (trim($column[1]) == 'span3') {
                    if ($row == 4) {
                        $html .= '</div>';
                        $row = '';
                    }
                }
            } else {
                if (isset($column[2]) && $column[2] == 'last') {
                    $html .= '</div>';
                }
            }
            
        }
        if ($n != 1) {
            $html .= '<div class="ba-prev"><input type="button" value="';
            $html .= $last[0].'" style="border-radius:' .$last[7];
            $html .= 'px; background-color: ' .$last[3]. '; font-size:';
            $html .= $last[5]. 'px; font-weight:' .$last[6]. '; width:';
            $html .= $last[1]. 'px; height:' .$last[2]. 'px; color: ' .$last[4];
            $html .= '" class="btn-prev"></div>';
        }
        if ($submissionsOptions['display_cart'] == 1 && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="baforms_cart" class="forms-cart">';
        }
        $html .= '</div>';
        $html .= '<div class="ba-form-footer">';
        if ($submissionsOptions['display_cart'] == 1 && $submissionsOptions['display_total'] == 1) {
            $path = self::getPath('cart.php');
            if ($path) {
                include $path;
                $html .= $out;
            }
        }
        if ($submissionsOptions['display_total'] == 1) {
            $path = self::getPath('total.php');
            if ($path) {
                include $path;
                $html .= $out;
            }
        }
        $html .= '<div class="ba-submit-cell">';
        if ($payments->multiple_payment == 1 && $submissionsOptions['display_total'] == 1) {
            $html .= '<label style="font-size:' .$formSettings[1]. '; color:';
            $html .= $formSettings[2] .'; font-weight: ';
            $html .= $formSettings[10]. '"><span>';
            $html .= $language->_('SELECT_PAYMENT_METHOD').'</span></label>';
            $html .= '<select name="task" style="height:' .$formSettings[3]. '; ';
            $html .= 'font-size:' .$formSettings[4]. ';color:' .$formSettings[5];
            $html .= '; background-color:' .$formSettings[6]. '; ';
            $html .=  $formSettings[7]. '">';
            if (!empty($payments->seller_id)) {
                $html .= '<option value="form.twoCheckout">2checkout</option>';
            }
            if (!empty($payments->mollie_api_key)) {
                $html .= '<option value="form.mollie">Mollie</option>';
            }
            if (!empty($payments->paypal_email)) {
                $html .= '<option value="form.paypal">Paypal</option>';
            }
            if (!empty($payments->payu_api_key) && !empty($payments->payu_merchant_id) && !empty($payments->payu_account_id)) {
                $html .= '<option value="form.payu">Payu Latam</option>';
            }
            if (!empty($payments->payu_biz_merchant) && !empty($payments->payu_biz_salt)) {
                $html .= '<option value="form.payubiz">Payu Biz</option>';
            }
            if (!empty($payments->skrill_email)) {
                $html .= '<option value="form.skrill">Skrill</option>';
            }
            if (!empty($payments->stripe_api_key)) {
                $html .= '<option value="form.stripe" data-api-key="'.$payments->stripe_api_key;
                $html .= '" data-image="'.JUri::root().$payments->stripe_image;
                $html .= '" data-name="'.$payments->stripe_name.'" data-description="';
                $html .= $payments->stripe_description;
                $html .= '">Stripe</option>';
            }
            if (!empty($payments->webmoney_purse)) {
                $html .= '<option value="form.webmoney">Webmoney</option>';
            }
            if (!empty($payments->ccavenue_merchant_id) && !empty($payments->ccavenue_working_key)) {
                $html .= '<option value="form.ccavenue">CCAvenue</option>';
            }
            if (!empty($payments->custom_payment)) {
                $html .= '<option value="form.save">'.$payments->custom_payment.'</option>';
            }
            $html .= '</select>';
        } else if ($method == 'paypal' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.paypal">';
        } else if ($method == '2checkout' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.twoCheckout">';
        } else if ($method == 'skrill' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.skrill">';
        } else if ($method == 'webmoney' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.webmoney">';
        } else if ($method == 'payubiz' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.payubiz">';
        } else if ($method == 'payu' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.payu">';
        } else if ($method == 'ccavenue' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.ccavenue">';
        } else if ($method == 'stripe' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.stripe" data-api-key="';
            $html .= $payments->stripe_api_key.'" data-image="'.JUri::root().$payments->stripe_image;
            $html .= '" data-name="'.$payments->stripe_name.'" data-description="';
            $html .= $payments->stripe_description.'">';
        } else if ($method == 'mollie' && $submissionsOptions['display_total'] == 1) {
            $html .= '<input type="hidden" name="task" value="form.mollie">';
        } else {
            $html .= '<input type="hidden" name="task" value="form.save">';
        }
        $capt = $submissionsOptions['alow_captcha'];
        if ($capt != '0') {
            $captcha = JCaptcha::getInstance($capt);
            $captcha->initialise($capt);
            $html .= "<div class='tool ba-captcha'>";
            $html .= $captcha->display($capt, $capt, 'g-recaptcha');
            $html .= "</div>";
        }
        if ($submissionsOptions['display_submit'] == 1) {
            $path = self::getPath('submit.php');
            if ($path) {
                include $path;
                $html .= $out;
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" class="theme-color" value="';
        $html .= $form[0]->theme_color. '">';
        $html .= '<input type="hidden" class="save-and-continue" value="'.$saveContinue->enable.'">';
        $html .= '<input type="hidden" class="redirect" value="';
        $html .= $submissionsOptions['redirect_url']. '">';
        $html .= '<input type="hidden" class="currency-code" value="';
        $html .= $submissionsOptions['currency_code']. '">';
        $html .= '<input type="hidden" class="sent-massage" value="';
        $html .= htmlspecialchars($submissionsOptions['sent_massage']). '">';
        $html .= '<input type="hidden" value="' .JURI::root();
        $html .= '" class="admin-dirrectory">';
        if ($saveContinue->enable == 1 && isset($_GET['bf_token'])) {
            $tokenData = self::getTokenData($_GET['bf_token']);
            $html .= '<input type="hidden" name="ba_token" value="'.$_GET['bf_token'].'">';
            $html .= '<input type="hidden" class="ba-token-data" value="'.htmlentities($tokenData, ENT_COMPAT).'">';
        }
        $html .= '<input type="hidden" name="page_url"><input type="hidden" name="page_title">';
        $html .= '<input type="hidden" name="form_id" value="' .$id. '">';
        $html .= '<p style="text-align: center; font-size: 12px; font-style:';
        $html .= ' italic;"><a href="http://www.balbooa.com/joomla-forms">';
        $html .= 'Joomla Forms</a> makes it right. Balbooa.com</p>';
        $html .= '</div>';
        $html .='</form>';
        if ($popup['display_popup'] == 1) {
            $html .= '</div></div></div>';
        }
        $html .= "</div>";
        return $html;
    }
    
    protected static function getSubmisionOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("alow_captcha, display_title, sent_massage, error_massage,
                        redirect_url, display_submit, dialog_color_rgba,
                        message_color_rgba, message_bg_rgba, display_total,
                        currency_code, currency_symbol, payment_methods,
                        display_cart, currency_position");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadAssoc();
        return $items;
    }
    
    public static function getCaptcha($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("alow_captcha");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadResult();
        return $items;
    }

    protected static function getPayment($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("multiple_payment, custom_payment, paypal_email,
                       seller_id, skrill_email, webmoney_purse, payu_api_key,
                       payu_merchant_id, payu_account_id, stripe_api_key,
                       stripe_image, stripe_name, stripe_description, mollie_api_key,
                       payu_biz_merchant, payu_biz_salt, ccavenue_merchant_id, ccavenue_working_key");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObject();
        return $items;
    }

    protected static function getForm($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("title, title_settings, form_settings, theme_color");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    protected static function getColumn($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("settings");
        $query->from("#__baforms_columns");
        $query->where("form_id=" . $id);
        $query->order("id ASC");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }
    
    public static function getElement($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("settings, id, custom, options");
        $query->from("#__baforms_items");
        $query->where("form_id=" . $id);
        $query->order("column_id ASC");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }
    
    public static function checkForm($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("published");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $publish = $db->loadResult();
        if (isset($publish)) {
            if ($publish == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function getEmbed($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("submit_embed");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadResult();
        return $items;
    }

    public static function getPopup($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("display_popup, button_lable, button_position, button_bg,
                        button_color, button_font_size, button_weight,
                        button_border, modal_width, button_type");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $items = $db->loadAssoc();
        return $items;
    }
}