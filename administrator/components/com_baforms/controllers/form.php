<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');


class BaformsControllerForm extends JControllerForm
{
    public function save($key = null, $urlVar = null)
    {
        $data = $this->input->post->get('jform', array(), 'array');
        $model = $this->getModel();
        $table = $model->getTable();
        $url = $table->getKeyName();
        parent::save($key = $data['id'], $urlVar = $url);
        
    }

    public function getSession()
    {
        $session = JFactory::getSession();
        echo new JResponseJson($session->getState());
        exit;
    }

    public function buildEmail()
    {
        $data = $this->input->post->get('jform', array(), 'array');
        $model = $this->getModel();
        $model->save($data);
        $app = JFactory::getApplication();
        $id = $model->getState($model->getName() . '.id');
        $app->redirect(JUri::base().'index.php?option=com_baforms&view=email&layout=edit&id='.$id);
    }

    public function connectMailChimp()
    {
        $apikey = $_POST['api_key'];
        $host = substr($apikey, strpos($apikey, '-') + 1);
        $auth = base64_encode( 'user:'. $apikey );
        $data = array('apikey' => $apikey);
        $json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://' . $host . '.api.mailchimp.com/3.0/lists');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '. $auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        if ($result) {
            echo new JResponseJson(true, $result);
        } else {
            echo new JResponseJson(false, 'API Key Invalid');
        }
        jexit();
    }

    public function getMailChimpFields()
    {
        $apikey = $_POST['api_key'];
        $listId = $_POST['list_id'];
        $host = substr($apikey, strpos($apikey, '-') + 1);
        $auth = base64_encode( 'user:'. $apikey );
        $data = array('apikey' => $apikey);
        $json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://' . $host . '.api.mailchimp.com/3.0/lists/'.$listId.'/merge-fields');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '. $auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        if ($result) {
            echo new JResponseJson(true, $result);
        } else {
            echo new JResponseJson(true, $result);
        }
        jexit();
    }
}