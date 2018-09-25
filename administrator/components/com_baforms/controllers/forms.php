<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
jimport('joomla.filesystem.file');

class BaformsControllerForms extends JControllerAdmin
{
	public function getModel($name = 'form', $prefix = 'baformsModel', $config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
	}
    
    public function duplicate()
    {
        $pks = $this->input->getVar('cid', array(), 'post', 'array');
        $model = $this->getModel();
        $model->duplicate($pks);
        $this->setMessage(JText::plural('FORM_DUPLICATED', count($pks)));
        $this->setRedirect('index.php?option=com_baforms&view=forms');
    }
    
    public function updateBaforms()
    {
        $target = $_POST['target'];
        $config = JFactory::getConfig();
        $path = $config->get('tmp_path') . '/pkg_BaForms.zip';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($path, "w+");
        fputs($file, $data);
        fclose($file);
        JArchive::extract($path, $config->get('tmp_path') . '/pkg_BaForms');
        $installer = JInstaller::getInstance();
        $result = $installer->update($config->get('tmp_path') . '/pkg_BaForms');
        JFile::delete($path);
        JFolder::delete( $config->get('tmp_path') . '/pkg_BaForms' );
        $verion = baformsHelper::aboutUs();
        if ($result) {
            echo new JResponseJson($result, $verion->version);
        } else {
            echo new JResponseJson($result, '', true);
        }
        jexit();
    }

    public function addLanguage()
    {
        $url = $_POST['ba_url'];
        $name = explode('/', $url);
        $name = end($name);
        $config = JFactory::getConfig();
        $path = $config->get('tmp_path') . '/'. $name;
        $name = explode('.', $name);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($path, "w+");
        fputs($file, $data);
        fclose($file);
        JArchive::extract($path, $config->get('tmp_path') . '/' .$name[0]);
        $installer = JInstaller::getInstance();
        $result = $installer->install($config->get('tmp_path') . '/'. $name[0]);
        JFile::delete($path);
        JFolder::delete( $config->get('tmp_path') . '/' .$name[0]);
        if ($result) {
            echo new JResponseJson($result, '');
        } else {
            echo new JResponseJson($result, '', true);
        }
        jexit();
    }

    public function exportForm()
    {
        $db = JFactory::getDbo();
        $export = $_POST['export_id'];
        $export = explode(';', $export);
        $themes = array();
        $model = $this->getModel();
        $table = $model->getTable();
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $root = $doc->createElement('baforms');
        $root = $doc->appendChild($root);
        $baform = $doc->createElement('baform');
        $baform = $root->appendChild($baform);
        $themeElement = $doc->createElement('themes');
        $themeElement = $root->appendChild($themeElement);
        $params = array();
        foreach ($export as $id) {
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('`#__baforms_forms`')
                ->where('`id` = ' .$id);
            $db->setQuery($query);
            $object = $db->loadObject();
            $form = $doc->createElement('form');
            $form = $baform->appendChild($form);
            foreach ($object as $key => $value) {
                $title = $doc->createElement($key);
                $title = $form->appendChild($title);
                $data = $doc->createTextNode($value);
                $data = $title->appendChild($data);
            }
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('`#__baforms_columns`')
                ->where('`form_id` = ' .$id);
            $db->setQuery($query);
            $objects = $db->loadObjectList();
            $columns = $doc->createElement('columns');
            $columns = $baform->appendChild($columns);
            foreach ($objects as $key => $object) {
                $column = $doc->createElement('column');
                $column = $columns->appendChild($column);
                foreach ($object as $key => $value) {
                    $title = $doc->createElement($key);
                    $title = $column->appendChild($title);
                    $data = $doc->createTextNode($value);
                    $data = $title->appendChild($data);
                }
            }
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('`#__baforms_items`')
                ->where('`form_id` = ' .$id);
            $db->setQuery($query);
            $objects = $db->loadObjectList();
            $items = $doc->createElement('items');
            $items = $baform->appendChild($items);
            foreach ($objects as $key => $object) {
                $item = $doc->createElement('item');
                $item = $items->appendChild($item);
                foreach ($object as $key => $value) {
                    $title = $doc->createElement($key);
                    $title = $item->appendChild($title);
                    $data = $doc->createTextNode($value);
                    $data = $title->appendChild($data);
                }
            }
        }
        $file =  '/tmp/forms.xml';
        $bytes = $doc->save(JPATH_ROOT.$file); 
        if ($bytes) {
            echo new JResponseJson(true, JPATH_ROOT.$file);
        } else {
            echo new JResponseJson(false, '', true);
        }
        jexit();
    }

    public function download()
    {
        $file = $_GET['file'];
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public function uploadTheme()
    {
        $model = $this->getModel();
        $input = JFactory::getApplication()->input;
        $items = $input->files->get('ba-files', '', 'array');
        $redirect = 'index.php?option=com_baforms&view=forms';
        foreach($items as $item) {
            $ext = strtolower(JFile::getExt($item['name']));
            if ($ext == 'xml') {
                $name = JPATH_ROOT. '/tmp/' .$item['name'];
                if(!JFile::upload( $item['tmp_name'], $name)) {
                    $this->setRedirect($redirect, JText::_('UPLOAD_ERROR'), 'error');
                    return false;
                }
            } else  {
                $this->setRedirect($redirect, JText::_('UPLOAD_ERROR'), 'error');
                return false;
            }
        }
        $xml = simplexml_load_file($name);
        $array = array();
        $columns = $xml->baform->columns;
        $items = $xml->baform->items;
        foreach ($xml->baform->form as $ind => $form) {
            foreach ($form as $key => $value) {
                if ((string)$key != 'id') {
                    $array[(string)$key] = (string)$value;
                }
            }
            $baforms_items = array();
            $table = JTable::getInstance('Forms', 'FormsTable');
            $table->bind($array);
            $table->store();
            $id = $table->id;
            foreach ($columns as $column) {
                foreach ($column as $key => $col) {
                    if ((string)$col->form_id == (string)$form->id) {
                        $table = JTable::getInstance('Columns', 'FormsTable');
                        $table->bind(array('form_id' => $id, 'settings' => (string)$col->settings));
                        $table->store();
                    }
                }
            }
            foreach ($items as $item) {
                foreach ($item as $value) {
                    if ((string)$value->form_id == (string)$form->id) {
                        $iId = (string)$value->id;
                        $table = JTable::getInstance('Items', 'FormsTable');
                        $table->bind(array('form_id' => $id, 'column_id' => (string)$value->column_id,
                                           'settings' => (string)$value->settings));
                        $table->store();
                        $baforms_items[$iId] = $table->id;
                    }
                }
            }
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('email_letter, reply_body')
                ->from('#__baforms_forms')
                ->where('`id` = '.$id);
            $db->setQuery($query);
            $letter = $db->loadObject();
            $regex = '/\[item=+(.*?)\]/i';
            preg_match_all($regex, $letter->email_letter, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $iId = $match[1];
                $letter->email_letter = str_replace('[item='.$iId.']', '[item='.$baforms_items[$iId].']', $letter->email_letter);
            }
            $regex = '/\[field ID=+(.*?)\]/i';
            preg_match_all($regex, $letter->reply_body, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $iId = $match[1];
                $letter->reply_body = str_replace('[field ID='.$iId.']', '[field ID='.$baforms_items[$iId].']', $letter->reply_body);
            }
            $letter->id = $id;
            $db->updateObject('#__baforms_forms', $letter, 'id');
        }
        $this->setRedirect($redirect, JText::_('SUCCESS_UPLOAD'));
        return true;
    }
}