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


class BaformsControllerEmail extends JControllerForm
{
    public function save($key = null, $urlVar = null)
    {
        $data = $this->input->post->get('jform', array(), 'array');
        $model = $this->getModel();
        $table = $model->getTable();
        $url = $table->getKeyName();
        parent::save($key = $data['id'], $urlVar = $url);
        
    }

    public function saveAndClose()
    {
        $data  = $this->input->post->get('jform', array(), 'array');
        $model = $this->getModel();
        $model->save($data);
        $redirect = JUri::base().'index.php?option=com_baforms';
        $this->setRedirect($redirect, JText::_('JLIB_APPLICATION_SAVE_SUCCESS'));
    }

    public function cancel($key = null)
    {
    	$app = JFactory::getApplication();
        $app->redirect(JUri::base().'index.php?option=com_baforms');
    }

    public function buildForm()
    {
        $data = $this->input->post->get('jform', array(), 'array');
        $model = $this->getModel();
        $model->save($data);
        $app = JFactory::getApplication();
        $id = $model->getState($model->getName() . '.id');
        $app->redirect(JUri::base().'index.php?option=com_baforms&view=form&layout=edit&id='.$id);
    }
}