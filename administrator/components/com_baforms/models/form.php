<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/ 

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
 
class baformsModelForm extends JModelAdmin
{
    public function getTable($type = 'Forms', $prefix = 'formsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
 
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.form', 'form', array('control' => 'jform', 'load_data' => $loadData)
        );
 
        if (empty($form))
        {
            return false;
        }
 
        return $form;
    }

    public function getBaitems()
    {
        $input = JFactory::getApplication()->input;
        $id = $input->get('id');
        if (!empty($id)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id, settings')
                ->from('#__baforms_items')
                ->where('`form_id` = ' .$id);
            $db->setQuery($query);
            $result = $db->loadObjectList();
        } else {
            $result = new stdClass();
        }
        
        return $result;
    }
    
    public function getMapsKey()
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

    public function setMapsKey($key)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('`#__baforms_api`')
            ->set('`key` = '.$db->quote($key))
            ->where('`service` = '.$db->quote('google_maps'));
        $db->setQuery($query);
        $db->setQuery($query)
            ->execute();
    }
 
    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.form.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
            $id = $data->id;
            if (isset($id)) {
                $elem = '';
                $column = '';
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("settings");
                $query->from("#__baforms_columns");
                $query->where("form_id=" . $id);
                $query->order("id ASC");
                $db->setQuery($query);
                $items = $db->loadObjectList();
                foreach ($items as $item) {
                    $elem .= $item->settings . '|';
                }
                $data->form_columns = $elem;
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select("settings, id, custom, options");
                $query->from("#__baforms_items");
                $query->where("form_id=" . $id);
                $query->order("column_id ASC");
                $db->setQuery($query);
                $items = $db->loadObjectList();
                foreach ($items as $item) {
                    $column .= json_encode($item) . '|_-_|';
                }
                $data->form_content = $column;
            }
        }
        return $data;
    }
    
    public function save($data)
    {
        if (empty($data['save_continue_popup_message'])) {
            $msg = '<p>The data you filled in the form has been saved. Please use the following link to ';
            $msg .= 'return to form. The link will expire after 30 days!</p><p>[ba-form-token-link]</p>';
            $msg .= '<p>Enter your email address and link will be emailed to you!</p>';
            $data['save_continue_popup_message'] = $msg;
        }
        $pos = strpos($data['save_continue_popup_message'], '[ba-form-token-link]');
        if ($pos === false) {
            $data['save_continue_popup_message'] .= ' [ba-form-token-link]';
        }
        $pos = strpos($data['save_continue_email'], '[ba-form-token-link]');
        if ($pos === false) {
            $data['save_continue_email'] .= ' [ba-form-token-link]';
        }
        if(parent::save($data)) {
            $this->setMapsKey($_POST['google_maps_apikey']);
            $formId = $this->getState($this->getName() . '.id');
            $db = JFactory::getDBO();
            $query = 'DELETE  FROM `#__baforms_columns` WHERE `form_id`=' . $formId;
            $db->setQuery($query);
            $db->query();
            $query = $db->getQuery(true);
            $query->select('id, settings')
                ->from('#__baforms_items')
                ->where('`form_id` = ' . $formId);
            $db->setQuery($query);
            $old = $db->loadObjectList();
            $array = array();
            $newdata =  explode('|', $data['form_columns']);
            foreach ($newdata as $dat) {
                if ($dat != '') {
                    $table = JTable::getInstance('Columns', 'FormsTable');
                    $table->bind(array('form_id'=>$formId, 'settings'=>$dat));    
                    $table->store();
                }
            }
            $newdata =  explode('|_-_|', $data['form_content']);
            $ind = 0;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('email_letter')
                ->from('#__baforms_forms')
                ->where('`id` = ' .$formId);
            $db->setQuery($query);
            $letter = $db->loadResult();
            foreach ($newdata as $key => $dat) {
                if ($dat != '') {
                    $item = json_decode($dat);
                    $table = JTable::getInstance('Items', 'FormsTable');
                    if ($item->id == 0) {
                        $table->bind(array('form_id' => $formId, 'column_id' => $ind, 'options' => $item->options,
                                    'settings' => $item->settings, 'custom' => $item->custom));
                        $table->store();
                        $array[] = $table->id;
                        if (!empty($letter)) {
                            $this->getNewTr($table->id, $item->settings, $formId);
                        } else {
                            $item->id = $table->id;
                            $newdata[$key] = json_encode($item);
                        }
                    } else {
                        $table->load($item->id);
                        $table->bind(array('form_id' => $formId, 'column_id' => $ind, 'options' => $item->options,
                                    'settings' => $item->settings, 'custom' => $item->custom));
                        $table->store();
                        $array[] = $table->id;
                    }
                    $ind++;
                }
            }
            if (empty($letter)) {
                $this->checkEmail($formId, $newdata);
            }
            foreach ($old as $value) {
                if (!in_array($value->id, $array)) {
                    $query = 'DELETE  FROM `#__baforms_items` WHERE `id`=' . $value->id;
                    $db->setQuery($query);
                    $db->query();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function checkEmail($id, $items)
    {
        $style = $_POST['jform']['email_options'];
        $style = json_decode($style);
        $str = '';
        $letter = '<table style="width: 100%; background-color: #ffffff;';
        $letter .= ' margin: 0 auto;"><tbody><tr class="ba-section"';
        $letter .= '><td style="width:100%; padding: 0 20px;"><table style="width: 100%; background-color: rgba(0,0,0,0);';
        $letter .= ' color: #333333; border-top: 1px solid #f3f3f3; border-right:';
        $letter .= ' 1px solid #f3f3f3; border-bottom: 1px solid #f3f3f3; border-';
        $letter .= 'left: 1px solid #f3f3f3; margin-top: 10px; ';
        $letter .= 'margin-bottom: 10px;"><tbody><tr><td class="droppad_area" ';
        $letter .= 'style="width:100%; padding: 20px;">[replace_all_items]</td>';
        $letter .= '</tr></tbody></table></td></tr></tbody></table>';
        foreach ($items as $item) {
            if (!empty($item)) {
                $item = json_decode($item);
                if ($item->id > 0) {
                    $settings = explode('_-_', $item->settings);
                    if ($settings[0] != 'button') {
                        $type = $settings[2];
                        if ($type != 'image' && $type != 'terms' && $type != 'htmltext' && $type != 'map' &&
                            strpos($settings[0], 'baform') === false) {
                            $settings = explode(';', $settings[3]);
                            $name = baformsHelper::checkItems($settings[0], $type, $settings[2]);
                            $str .= '<div class="droppad_item system-item" data-item="[item=';
                            $str .= $item->id. ']"';
                            if (isset($style->color)) {
                                $str .= ' style="color: '.$style->color.'; font-size: '.$style->size;
                                $str .= 'px; font-weight: '.$style->weight;
                                $str .= '; line-height: 200%; font-family: Helvetica Neue, Helvetica, Arial;"';
                            }
                            $str .= '>[Field='.$name.']</div>';
                        }
                    }
                }
            }
        }
        if ($_POST['jform']['check_ip'] == 1) {
            $str .= '<div class="system-item email-ip-field droppad_item"';
            if (isset($style->color)) {
                $str .= ' style="color: '.$style->color.'; font-size: '.$style->size;
                $str .= 'px; font-weight: '.$style->weight;
                $str .= '; line-height: 200%; font-family: Helvetica Neue, Helvetica, Arial;"';
            }
            $str .='>[Field=Ip Address]</div>';
        }
        if ($_POST['jform']['display_total'] == 1) {
            $str .= '<div class="system-item email-total-field droppad_item"';
            if (isset($style->color)) {
                $str .= ' style="color: '.$style->color.'; font-size: '.$style->size;
                $str .= 'px; font-weight: '.$style->weight;
                $str .= '; line-height: 200%; font-family: Helvetica Neue, Helvetica, Arial;"';
            }
            $str .='>[Field=Total]</div>';
        }
        if ($_POST['jform']['display_total'] == 1 && $_POST['jform']['display_cart'] == 1) {
            $str .= '<div class="system-item email-cart-field droppad_item"';
            if (isset($style->color)) {
                $str .= ' style="color: '.$style->color.'; font-size: '.$style->size;
                $str .= 'px; font-weight: '.$style->weight;
                $str .= '; line-height: 200%; font-family: Helvetica Neue, Helvetica, Arial;"';
            }
            $str .='>[Field=Cart]</div>';
        }
        $letter = str_replace('[replace_all_items]', $str, $letter);
        $table = JTable::getInstance('Forms', 'FormsTable');
        $table->load($id);
        $table->bind(array('email_letter' => $letter));
        $table->store();
    }

    public function getNewTr($id, $settings, $formId)
    {
        $style = $_POST['jform']['email_options'];
        $style = json_decode($style);
        $settings = explode('_-_', $settings);
        if ($settings[0] != 'button') {
            $type = $settings[2];
            if ($type != 'image' && $type != 'htmltext' && $type != 'map' &&
                strpos($settings[0], 'baform') === false) {
                $settings = explode(';', $settings[3]);
                $name = baformsHelper::checkItems($settings[0], $type, $settings[2]);
                $str = '<div class="droppad_item system-item" data-item="[item=';
                $str .= $id. ']"';
                if (isset($style->color)) {
                    $str .= ' style="color: '.$style->color.'; font-size: '.$style->size;
                    $str .= 'px; font-weight: '.$style->weight.'; line-height: 200%; ';
                    $str .= 'font-family: Helvetica Neue, Helvetica, Arial;"';
                }
                $str .='>[Field='.$name.']</div>';
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('email_letter')
                    ->from('#__baforms_forms')
                    ->where('`id` = ' .$formId);
                $db->setQuery($query);
                $body = $db->loadResult();
                $body = @preg_replace("|\[add_new_item\]|", $str, $body, 1);
                $table = JTable::getInstance('Forms', 'FormsTable');
                $table->load($formId);
                $table->bind(array('email_letter' => $body));
                $table->store();
            }
        }
    }
    
    public function delete(&$pks)
    {
        $pks = (array) $pks;
        foreach ($pks as $i => $pk)
        {
            $id = $pk;
            if (parent::delete($pk))
            {
                $this->_db->setQuery("DELETE FROM #__baforms_items WHERE `form_id`=". $id);
                $this->_db->execute();
                $this->_db->setQuery("DELETE FROM #__baforms_columns WHERE `form_id`=". $id);
                $this->_db->execute();  
            } else {
                return false;
            }
        }
        return true;
    }
    
    public function duplicate(&$pks)
    {
        $db = $this->getDbo();
        foreach ($pks as $pk) {
            $table = $this->getTable();
            $table->load($pk, true);
            $table->id = 0;
            $table->title .= ' (1)';
            $table->published = 0;
            $table->store();
            $formId = $this->getState($this->getName() . '.id');
            $query = $db->getQuery(true);
            $query->select("*");
            $query->from("#__baforms_columns");
            $query->where("form_id=" . $pk);
            $query->order("id ASC");
            $db->setQuery($query);
            $id = $table->id;
            $letter = $table->email_letter;
            $items = $db->loadObjectList();
            foreach ($items as $item) {
                if ($item != '') {
                    $table = JTable::getInstance('Columns', 'FormsTable');
                    $table->bind(array('form_id' => $id, 'settings' => $item->settings));
                    $table->store();
                }
            }
            $query = $db->getQuery(true);
            $query->select("*");
            $query->from("#__baforms_items");
            $query->where("form_id=" . $pk);
            $query->order("id ASC");
            $db->setQuery($query);
            $items = $db->loadObjectList();
            foreach ($items as $item) {
                if ($item != '') {
                    $table = JTable::getInstance('Items', 'FormsTable');
                    $table->bind(array('form_id' => $id, 'column_id' => $item->column_id, 'settings' => $item->settings));    
                    $table->store();
                    $letter = str_replace('[item='.$item->id.']', '[item='.$table->id.']', $letter);
                }
            }
            $table = $this->getTable();
            $table->load($id, true);
            $table->bind(array('email_letter' => $letter));
            $table->store();
        }
        
    }
}