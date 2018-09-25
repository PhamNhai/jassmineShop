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

class BaformsControllerSubmissions extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'submission', $prefix = 'baformsModel', $config = array()) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
	}
    
    public function changeState()
    {
        $id = $_POST['form_id'];
        $model = $this->getModel();
        $model->changeState($id);
    }
    
    public function exportXML()
    {
        $data = $_POST['exportData'];
        $data = str_replace('*', '', $data);
        $data = explode('|__|', $data);
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $root = $doc->createElement('submissions');
        $root = $doc->appendChild($root);
        foreach($data as $item) {
            if (!empty($item)) {
                $item = json_decode($item);
                $date = $this->getDate($item->id);
                $postroot = $doc->createElement(str_replace(' ', '_', $item->form));
                $postroot = $root->appendChild($postroot);
                $title = $doc->createElement('date');
                $title = $postroot->appendChild($title);
                $text = $doc->createTextNode($date);
                $text = $title->appendChild($text);
                $message = explode('_-_', $item->message);
                foreach($message as $mes) {
                    if (!empty($mes)) {
                        $mes = strip_tags($mes);
                        $mes = explode('|-_-|', $mes);
                        $patern = array('~', '`', '!', '@', '"', '#', '№', '$', ';',
                                   '%', '^', '&', '?', '*', '(', ')', '-', '+',
                                   '=', '/', '|', '.', "'", ',', '\\');
                        $replace = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                                   ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                                   ' ', ' ', ' ', ' ', ' ', ' ', ' ');
                        $mes[0] = str_replace($patern, $replace, $mes[0]);
                        if ($mes[1] == ';') {
                            $mes[1] = '';
                        }
                        $title = $doc->createElement(str_replace(' ', '_', $mes[0]));
                        $title = $postroot->appendChild($title);
                        $text = $doc->createTextNode($mes[1]);
                        $text = $title->appendChild($text);
                    }
                }
            }
        }
        $file =  '/tmp/baform.xml';
        $bytes = $doc->save(JPATH_ROOT.$file); 
        if ($bytes) {
            echo new JResponseJson(true, JPATH_ROOT.$file);
        } else {
            echo new JResponseJson(false, '', true);
        }
        jexit();
    }
    
    public function exportCSV()
    {
        $data = $_POST['exportData'];
        $data = explode('|__|', $data);
        $list = array();
        $form = '';
        foreach($data as $item) {
            if (!empty($item)) {
                $item = json_decode($item);
                $date = $this->getDate($item->id);
                $title = array();
                $info = array();
                $title[] = 'Date';
                $title[] = 'Id';
                $info[] = $date;
                $info[] = $item->id;                
                $message = explode('_-_', $item->message);
                foreach($message as $mes) {
                    if (!empty($mes)) {
                        $mes = strip_tags($mes);
                        $mes = explode('|-_-|', $mes);
                        if ($mes[1] == ';') {
                            $mes[1] = '';
                        }
                        $title[] = $mes[0];
                        $info[] = $mes[1];
                    }
                }
                
                if ($item->form != $form) {
                    $list[] = array($item->form);
                    $list[] = $title;
                }
                $list[] = $info;
                $form = $item->form;
            }
        }
        $file =  '/tmp/baform.csv';
        $fp = fopen(JPATH_ROOT.$file, 'w');
        fwrite ($fp, b"\xEF\xBB\xBF");
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        echo new JResponseJson(true, JPATH_ROOT.$file);
        jexit();
    }
    
    public function getDate($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("date_time");
        $query->from("#__baforms_submissions");
        $query->where("id=" . $id);
        $db->setQuery($query);
		$items = $db->loadResult();
        return $items;
    }
    
    public function doanload()
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
}