<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date		2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC')) die('Direct Access to this location is not allowed.');

jimport( 'joomla.application.component.controller' );

Class Sh404sefClassBasecontroller extends ShlMvcController_Base {

  protected $_context = 'com_sh404sef';

  protected $_defaultController = '';
  protected $_defaultTask = '';
  protected $_defaultModel = '';
  protected $_defaultView = 'default';
  protected $_defaultLayout = 'default';

  protected $_returnController = '';
  protected $_returnTask = '';
  protected $_returnView = 'default';
  protected $_returnLayout = 'default';

  /**
   * Display the view
   */
  public function display($cachable = false, $urlparams = false) {

    // catch up any result message coming from an
    // ajax save for instance, and push that into
    // the application message queue
    $messageCode = JRequest::getCmd( 'sh404sefMsg');
    if (!empty($messageCode)) {
      $msg = JText::_( $messageCode);
      if ($msg != $messageCode) {
        // if no language string exists, JText will
        // return the input string, so only display if
        // we have something to display
        $app = JFactory::getApplication();
        $app->enqueuemessage( $msg);
      }
    }
    // Set the default view name in case it's missing
    $viewName = JRequest::getWord('view');
    if (empty( $viewName)) {
      JRequest::setVar( 'view', $this->_defaultView);
    }

    $document =JFactory::getDocument();

    $viewType = $document->getType();
    $viewName = JRequest::getCmd( 'view', $this->_defaultView);
    $viewLayout = JRequest::getCmd( 'layout', $this->_defaultLayout );

    $view = $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->basePath));

    // Get/Create the model
    if ($model = $this->getModel($viewName)) {
      // store initial context in model
      $model->setContext( $this->_context);

      // Push the model into the view (as default)
      $view->setModel($model, true);

      // and push also the default redirect
      $view->defaultRedirectUrl = $this->_getDefaultRedirect( array( 'layout' => $viewLayout));

    }

    // Set the layout
    $view->setLayout($viewLayout);

    // push controller errors in the view
    $error = $this->getError();
    if (!empty( $error)) {
      $view->setError( $error);
    }

    // Display the view
    $view->display();

  }

  /**
   * Method implementing cancelling the list view
   * Go back to dashboard
   *
   */
  public function dashboard() {

    // define where we want to go : dashboard ie all default values
    $bits = array( 'c' => '', 'task' => '', 'view' => '');

    // get there
    $this->setRedirect( $this->_getDefaultRedirect( $bits));
  }

  /**
   * Enqueue a series of messages in the application message
   * queue, while JApplication::enqueuemessage will do it
   * one at a time
   * @param array of strings $msgs the messages
   * @param string $msgType the message type (same for all messages in $msgs)
   */
  public function enqueuemessages( $msgs, $msgType = null) {

    // nothing to do if no messages
    if (empty( $msgs)) {
      return;
    }

    // get application
    $app = JFactory::getApplication();

    // loop messages and enqueue
    foreach( $msgs as $msg) {
      $app->enqueuemessage( $msg, $msgType);
    }
  }


  /**
   * Builds a (non routed) Joomla url according to default values
   * of controller, task, view and layout
   *
   * @param array $userVars optional set of variables to override default vars, or add more to url
   * @return string the target url, not routed
   */
  protected function _getDefaultRedirect( $userVars = array()) {

    // get default values of the controller
    $defaultVars = array( 'c' => $this->_returnController, 'layout' => $this->_returnLayout, 'view' => $this->_returnView, 'task' => $this->_returnTask);

    // override default vars with user defined vars
    $vars = array_merge( $defaultVars, $userVars);
    
    // strip empty vars
    foreach( $vars as $key => $value) {
      if ($value == '') {
        unset( $vars[$key]);
      }
    }

    return Sh404sefHelperUrl::buildUrl( $vars);
  }


}
