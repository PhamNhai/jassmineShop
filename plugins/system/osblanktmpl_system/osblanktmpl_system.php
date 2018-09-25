<?php
/**
* @package OS Blank template
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru)
* @description Ordasoft OS Blank template
*/


defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

class plgSystemOsBlankTmpl_system extends JPlugin{
/**
* Constructor.
* @access protected
* @param object $subject The object to observe
* @param array   $config  An array that holds the plugin configuration
* @since 1.0
*/

  public function __construct( &$subject, $config ){
    parent::__construct( $subject, $config );
  }

  // public function onContentPrepare($context, &$article, &$params){
  //   $app = JFactory::getApplication();
  //   $doc = JFactory::getDocument();
  //   //echo "wwwwwwwwwwwwwwww".JURI::root(true) . '/media/system/js/frontediting.js';
  //   unset($doc->_scripts[JURI::root(true) . '/media/system/js/frontediting.js']);
  //   //unset($doc->_scripts);
  //   //print_r($doc->_scripts);

    
  //   return true;
    
  // }

  public function onAfterRender()
  {
    // print_r(JURI::getInstance()->toString());exit;
    $uri = JURI::getInstance()->toString();
    $app = JFactory::getApplication();
    $doc = JFactory::getDocument();
    $db = JFactory::getDBO();
    $params = new JRegistry;
    $html = $app->getBody();
    if ($app->isSite() && $doc->getType() == 'html') {
      $html = $app->getBody();
      $pos = strpos($html, '</head>');
      $head = substr($html, 0, $pos);
      $body = substr($html, $pos);
      


  //<script src="/~andrew/joomla_364/media/system/js/frontediting.js"></script>
     $regex="[<script src\"?'?.*media/system/js/frontediting.js\"?'?.*></script>]";
     if(preg_match($regex,$head,$matches))
     {
       $head=preg_replace($regex, "", $head);
     }


      $app->setBody($head.$body);
    }
  }

}