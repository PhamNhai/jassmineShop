<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/


defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

class plgSystemOsGallery_system extends JPlugin{
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

  public function onContentPrepare($context, &$article, &$params){
    $app = JFactory::getApplication();
    $doc = JFactory::getDocument();
    $html = $app->getBody();
    if ($app->isSite() && $doc->getType() == 'html') {
      JLoader::register('osGalleryHelperSite', JPATH_SITE . "/components/com_osgallery/helpers/osGalleryHelperSite.php");
      if(isset($article->introtext)){
        $article_content = $article->introtext;
        preg_match_all('{os-gal-[0-9]{1,}}',$article_content,$matches);
        if(isset($matches[0]) && count($matches[0])){
          foreach ($matches[0] as $key => $shortCode) {
            if(strpos("os-gal-", $shortCode) == 0){
              $galId = str_replace('os-gal-', '', $shortCode);
              $galIds = array(0=>$galId);
              //other layout
              ob_start();
                osGalleryHelperSite::displayView($galIds);
                $article_content = str_replace("{os-gal-".$galId."}", ob_get_contents(), $article_content);
              ob_end_clean();
            }
          }
        }
        $article->introtext = $article_content;
      }
    }
  }

  public function onAfterRender()
  {
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
      JLoader::register('osGalleryHelperSite', JPATH_SITE . "/components/com_osgallery/helpers/osGalleryHelperSite.php");
      if(isset($body)){
        preg_match_all('{os-gal-[0-9]{1,}}',$body,$matches);
        if(isset($matches[0]) && count($matches[0])){
          $buttons = false;
          $thumbnail = false;
          $wheel = false;
          foreach ($matches[0] as $key => $shortCode) {
            if(strpos("os-gal-", $shortCode) == 0){
              $galId = str_replace('os-gal-', '', $shortCode);
              //load params
              $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
              $db->setQuery($query);
              $paramsString = $db->loadResult();
              if($paramsString){
                  $params->loadString($paramsString);
              }
              if($params->get("helper_buttons"))$buttons = true;
              if($params->get("helper_thumbnail"))$thumbnail = true;
              if($params->get("mouse_wheel",1))$wheel = true;
              $galIds = array(0=>$galId);
              //other layout
              ob_start();
                osGalleryHelperSite::displayView($galIds);
                $body = str_replace("{os-gal-".$galId."}", ob_get_contents(), $body);
              ob_end_clean();
            }
          }
          $head = $this->addStyle($head, $buttons, $thumbnail, $wheel);
        }
      }
      $app->setBody($head.$body);
    }
  }

  public function addStyle($head, $buttons, $thumbnail, $wheel){
    $link = JURI::base() . 'components/com_osgallery/assets/css/os-gallery.css';
    if(!preg_match_all('|os-gallery.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::base() . 'components/com_osgallery/assets/css/font-awesome.min.css';
    if(!preg_match_all('|font-awesome.min.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/jquery.os_fancyboxGall.css';
    if(!preg_match_all('|jquery.os_fancyboxGall|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::base() . 'components/com_osgallery/assets/libraries/jQuery/jQuerGall-2.2.4.js';
    if(!preg_match_all('|jQuerGall-2.2.4.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/jquery.os_fancyboxGall.js';
    if(!preg_match_all('|jquery.os_fancyboxGall.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    if($buttons){
      $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancybox-buttons.css';
      if(!preg_match_all('|jquery.os_fancybox-buttons.css|',$head,$matches)){
        $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
      }

      $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancyboxGall-buttons.js';
      if(!preg_match_all('|jquery.os_fancyboxGall-buttons.js|',$head,$matches)){
        $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
      }
    }

    if($thumbnail){
      $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancybox-thumbs.css';
      if(!preg_match_all('|jquery.os_fancybox-thumbs.css|',$head,$matches)){
        $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
      }

      $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancyboxGall-thumbs.js';
      if(!preg_match_all('|jquery.os_fancyboxGall-thumbs.js|',$head,$matches)){
        $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
      }
    }

    if($wheel){
      $link = JURI::base() . 'components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.mousewheel-3.0.6.pack.js';
      if(!preg_match_all('|jquery.mousewheel-3.0.6.pack.js|',$head,$matches)){
        $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
      }
    }

    return $head;
  }
}