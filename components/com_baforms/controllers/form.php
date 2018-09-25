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
    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

    public function getToken()
    {
        $model = $this->getModel('form');
        $model->setToken();
        exit;
    }    

    public function saveContinue()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->saveContinue();
        }
        exit;
    }

    public function ccavenue()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->ccavenue();
        }
    }

    public function mollie()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->mollie();
        }
    }

    public function stripe()
    {
        $data = $_POST;
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->save($data);
        }
    }

    public function stripeCharges()
    {
        $model = $this->getModel('form');
        $model->stripeCharges();
    }
    
    public function payu()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->payu();
        }
    }

    public function payubiz()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->payubiz();
        }
    }

    public function webmoney()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->webmoney();
        }
    }
    
    public function skrill()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->skrill();
        }
    }
    
    public function twoCheckout()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->twoCheckout();
        }
    }
    
    public function paypal()
    {
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->paypal();
        }
    }
    
    public function save($key = NULL, $urlVar = NULL)
    {
        $data = $_POST;
        if (!empty($_POST['form_id'])) {
            $model = $this->getModel('form');
            $model->save($data);
        }        
    }
}