<?php
/**
* @version 1.0
* @package OS CCK
* @copyright 2015 OrdaSoft
* @author 2015 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

class plgSystemCck_system extends JPlugin{
  protected static $modules = array();

  protected static $mods = array();
  
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
  
  function onBeforeCompileHead(){
    global $bootstrap;
    $doc = JFactory::getDocument();
    $session = JFactory::getSession();
    $app = JFactory::getApplication();
    $matches  = preg_grep ('/bootstrap.css/i', array_keys($doc->_styleSheets));
    if(!$matches){
      $matches  = preg_grep ('/maxcdn.bootstrapcdn.com\/bootstrap/i', array_keys($doc->_styleSheets));
    }
    $plugin = JPluginHelper::getPlugin('system', 'cck_system');
    $params = new JRegistry;
    $params->loadString($plugin->params);
    if($app->isSite()){
      switch($params->get('bootstrap_version','0')){
        case "1":
          //dont include bootstrap
        break;
        case "2":
          //include bootstrap 2
          $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap2.css");
          $session->set('bootstrap', '2');
        break;
        case "3":
          //include bootstrap 3
          $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css");
          $session->set('bootstrap', '3');
        break;
        default:
          //try detect version of bootstrap
          if(isset($doc->_scripts[JURI::root(true).'/media/jui/js/bootstrap.min.js'])){
            $file = file_get_contents(JPATH_BASE.'/media/jui/js/bootstrap.min.js');
            $match  = preg_match_all ('/Custom version for Joomla!/i', $file);
            if($match){
              $session->set('bootstrap', '2');
            }else{
              $session->set('bootstrap', '3');
              $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css");
            }
          }else if(count($matches)){
            foreach ($matches as $value) {
              //need test if we have bootstrap
              $match  = preg_match_all ('/maxcdn.bootstrapcdn.com\/bootstrap\/2/i', $value);
              if($match){
                $session->set('bootstrap', '2');
              }else{
                $match  = preg_match_all ('/maxcdn.bootstrapcdn.com\/bootstrap\/3/i', $value);
                if($match){
                  $session->set('bootstrap', '3');
                }
              }
            }
          }else{
            $session->set('bootstrap', '3');
            $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css");
          }
      }
    }

    //backend
    if(JRequest::getVar('option','') == 'com_os_cck' && (JRequest::getVar('task','') == 'edit_layout' 
      || (JRequest::getVar('task','') == 'new_layout' && JRequest::getVar('layout_type','')))){
      $doc = JFactory::getDocument();
      // Remove default bootstrap
      unset($doc->_scripts[JURI::root(true).'/media/jui/js/bootstrap.min.js']);
      foreach ($doc->_styleSheets as $key => $value) {
        if(substr_count($key,'templates')){
          unset($doc->_styleSheets [$key]);
        }
      }
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/bootstrap/js/bootstrapCCK.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/js/jquery.minicolors.js");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/css/jquery.minicolors.css");
      //style for codemirror
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/lib/codemirror.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/fold/foldgutter.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/dialog/dialog.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/theme/monokai.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/display/fullscreen.css");
      $doc->addStyleSheet(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/hint/show-hint.css");

      

      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/lib/codemirror.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/search/searchcursor.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/search/search.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/dialog/dialog.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/edit/matchbrackets.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/edit/closebrackets.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/comment/comment.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/wrap/hardwrap.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/fold/foldcode.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/fold/brace-fold.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/javascript/javascript.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/php/php.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/htmlmixed/htmlmixed.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/htmlmixed/htmlmixed.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/css/css.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/xml/xml.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/mode/clike/clike.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/hint/show-hint.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/hint/anyword-hint.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/hint/css-hint.js");

      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/keymap/sublime.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/selection/active-line.js");
      $doc->addScript(JURI::root() . "/components/com_os_cck/assets/codemirror/addon/display/fullscreen.js");


      //need for joomla editor
      // $doc->addScript(JURI::root() . "/media/editors/tinymce/js/tinymce.min.js");
      // $doc->addScript(JURI::root() . "/media/editors/tinymce/js/tiny-close.min.js");
      $doc->addScript(JURI::root() . "/media/system/js/html5fallback.js");
      JHtml::_('behavior.formvalidator');
      JHtml::_('behavior.keepalive');
      JHtml::_('behavior.modal');
      // $doc->addScript("https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.7/jquery.tinymce.min.js");
      //end
      foreach ($doc->_scripts as $script => $type) {
        if(strpos($script, 'isis/js/template.js')){
          unset($doc->_scripts[$script]);
        }
        if(strpos($script, 'html5fallback.js')){
          unset($doc->_scripts[$script]);
        }
      }
    }
  }

  public function onContentPrepare($context, &$article, &$params){
    require_once(JPATH_SITE."/components/com_os_cck/os_cck.php");//need change to all cat inst lay|.php
    $cck_option = 'com_os_cck';
    if(isset($article->introtext)){
      $article_content = $article->introtext;
      $language = JFactory::getLanguage();
      $language->load('com_os_cck');
      //get mask
      preg_match_all('[{CCKLayout\|[a-z]-[0-9]{1,}\|}|'.
              '{CCKLayout\|[a-z]-[0-9]{1,}:CCK[a-zA-Z]{1,10}\|[a-z]-[1-9]{1,}\|}]',$article_content,$matches);
      if(isset($matches[0])){
        foreach ($matches[0] as $key => $match) {
          $eiid = '';
          $lid = '';
          $cat_id = '';
          if(strpos($match, '{CCKLayout|') == 0){
            //if instance or category layout
            if(strpos($match, ':')){
              $match = explode(':', $match);
              $lid = str_replace("{CCKLayout|l-", '', $match[0]);
              if(strpos($match[1], 'CCKInstance|i-') === 0){
                $eiid = str_replace("CCKInstance|i-", '', $match[1]);
                $eiid = str_replace("|}", '', $eiid);
              }else{
                $cat_id = str_replace("CCKCategory|c-", '', $match[1]);
                $cat_id = str_replace("|}", '', $cat_id);
              }
            }else{
              //others layout
              $lid = str_replace("{CCKLayout|l-", '', $match);
              $lid = str_replace("|}", '', $lid);
            }
          }
          //replase content
          if($lid && $eiid){
            //instance layout
            ob_start();
            Instance::showItem($cck_option,$eiid, 0, $lid);
            $article_content = str_replace("{CCKLayout|l-".$lid.":CCKInstance|i-".$eiid."|}", ob_get_contents(), $article_content);
            ob_end_clean();
          }else if($lid && $cat_id){
            //category layout
            ob_start();
            Category::showCategory($cck_option,$cat_id,$lid);
            $article_content = str_replace("{CCKLayout|l-".$lid.":CCKCategory|c-".$cat_id."|}", ob_get_contents(), $article_content);
            ob_end_clean();
          }else{
            //other layout
            ob_start();
            $layout = new os_cckLayout($db);
            $layout->load($lid);
            switch($layout->type){
              case "add_instance":
              case "request_instance":
                Instance::show_request_layout($cck_option ,$lid,0);
                break;

              case "all_categories":
                Category::listCategories($cck_option, $lid);
                break;

              case "all_instance":
                Instance::show_all_instance($cck_option,$lid);
                break;

              case "search":
                Category::showSearch($cck_option,0,$lid);
                break;
            }
            $article_content = str_replace("{CCKLayout|l-".$lid."|}", ob_get_contents(), $article_content);
            ob_end_clean();
          }

        }
        $article->introtext = $article_content;
      }
  /////copy from plugins/content/loadmodule/loadmodule.php/
      // Expression to search for (positions)
      $regex = '/{loadposition\s(.*?)}/i';
      $style = $this->params->def('style', 'none');

      // Expression to search for(modules)
      $regexmod = '/{loadmodule\s(.*?)}/i';
      $stylemod = $this->params->def('style', 'none');

      // Find all instances of plugin and put in $matches for loadposition
      // $matches[0] is full pattern match, $matches[1] is the position
      preg_match_all($regex, $article->introtext, $matches, PREG_SET_ORDER);

      // No matches, skip this
      if ($matches)
      {
        foreach ($matches as $match)
        {
          $matcheslist = explode(',', $match[1]);

          // We may not have a module style so fall back to the plugin default.
          if (!array_key_exists(1, $matcheslist))
          {
            $matcheslist[1] = $style;
          }

          $position = trim($matcheslist[0]);
          $style    = trim($matcheslist[1]);

          $output = $this->_load($position, $style);

          // We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
          $article->introtext = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->introtext, 1);
          $style = $this->params->def('style', 'none');
        }
      }

      // Find all instances of plugin and put in $matchesmod for loadmodule
      preg_match_all($regexmod, $article->introtext, $matchesmod, PREG_SET_ORDER);
      // If no matches, skip this
      if ($matchesmod)
      {
        foreach ($matchesmod as $matchmod)
        {
          $matchesmodlist = explode(',', $matchmod[1]);

          // We may not have a specific module so set to null
          if (!array_key_exists(1, $matchesmodlist))
          {
            $matchesmodlist[1] = null;
          }

          // We may not have a module style so fall back to the plugin default.
          if (!array_key_exists(2, $matchesmodlist))
          {
            $matchesmodlist[2] = $stylemod;
          }

          $module = trim($matchesmodlist[0]);
          $name   = htmlspecialchars_decode(trim($matchesmodlist[1]));
          $stylemod  = trim($matchesmodlist[2]);

          // $match[0] is full pattern match, $match[1] is the module,$match[2] is the title
          $output = $this->_loadmod($module, $name, $stylemod);

          // We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
          $article->introtext = preg_replace("|$matchmod[0]|", addcslashes($output, '\\$'), $article->introtext, 1);
          $stylemod = $this->params->def('style', 'none');
        }
      }

    }
  }

  public function onAfterRender(){
    $cck_option = 'com_os_cck';
    $doc = JFactory::getDocument();
    $app = JFactory::getApplication();
    $db = JFactory::getDBO();
    $html = $app->getBody();
    if ($app->isSite() && $doc->getType() == 'html') {
      $html = $app->getBody();
      $pos = strpos($html, '</head>');
      $head = substr($html, 0, $pos);

      $body = substr($html, $pos);
      if(isset($body)){
        $language = JFactory::getLanguage();
        $language->load('com_os_cck');
        //get mask
        preg_match_all('[{CCKLayout\|[a-z]-[0-9]{1,}\|}|'.
                '{CCKLayout\|[a-z]-[0-9]{1,}:CCK[a-zA-Z]{1,10}\|[a-z]-[1-9]{1,}\|}]',$body,$matches);
        if(isset($matches[0])){
          require_once(JPATH_SITE."/components/com_os_cck/os_cck.php");//need change to all cat inst lay|.php
          foreach ($matches[0] as $key => $match) {
            $eiid = '';
            $lid = '';
            $cat_id = '';
            if(strpos($match, '{CCKLayout|') == 0){
              //if instance or category layout
              if(strpos($match, ':')){
                $match = explode(':', $match);
                $lid = str_replace("{CCKLayout|l-", '', $match[0]);
                if(strpos($match[1], 'CCKInstance|i-') === 0){
                  $eiid = str_replace("CCKInstance|i-", '', $match[1]);
                  $eiid = str_replace("|}", '', $eiid);
                }else{
                  $cat_id = str_replace("CCKCategory|c-", '', $match[1]);
                  $cat_id = str_replace("|}", '', $cat_id);
                }
              }else{
                //others layout
                $lid = str_replace("{CCKLayout|l-", '', $match);
                $lid = str_replace("|}", '', $lid);
              }
            }
            //replase content
            if($lid && $eiid){
              //instance layout
              ob_start();
              Instance::showItem($cck_option,$eiid, 0, $lid);
              $body = str_replace("{CCKLayout|l-".$lid.":CCKInstance|i-".$eiid."|}", ob_get_contents(), $body);
              ob_end_clean();
            }else if($lid && $cat_id){
              //category layout
              ob_start();
              Category::showCategory($cck_option,$cat_id,$lid);
              $body = str_replace("{CCKLayout|l-".$lid.":CCKCategory|c-".$cat_id."|}", ob_get_contents(), $body);
              ob_end_clean();
            }else{
              //other layout
              ob_start();
              $layout = new os_cckLayout($db);
              $layout->load($lid);
              switch($layout->type){
                case "add_instance":
                case "request_instance":
                  Instance::show_request_layout($cck_option ,$lid,0);
                  break;

                case "all_categories":
                  Category::listCategories($cck_option, $lid);
                  break;

                case "all_instance":
                  Instance::show_all_instance($cck_option,$lid);
                  break;

                case "search":
                  Category::showSearch($cck_option,0,$lid);
                  break;
              }
              $body = str_replace("{CCKLayout|l-".$lid."|}", ob_get_contents(), $body);
              ob_end_clean();
            }

          }//end matches

          $head = $this->addStyle($head);
          $app->setBody($head.$body);
        }
      }
    }
  }

  protected function _load($position, $style = 'none')
  {
    self::$modules[$position] = '';
    $document = JFactory::getDocument();
    $renderer = $document->loadRenderer('module');
    $modules  = JModuleHelper::getModules($position);
    $params   = array('style' => $style);
    ob_start();

    foreach ($modules as $module)
    {
      echo $renderer->render($module, $params);
    }

    self::$modules[$position] = ob_get_clean();

    return self::$modules[$position];
  }

  protected function _loadmod($module, $title, $style = 'none')
  {
    self::$mods[$module] = '';
    $document = JFactory::getDocument();
    $renderer = $document->loadRenderer('module');
    $mod      = JModuleHelper::getModule($module, $title);

    // If the module without the mod_ isn't found, try it with mod_.
    // This allows people to enter it either way in the content
    if (!isset($mod))
    {
      $name = 'mod_' . $module;
      $mod  = JModuleHelper::getModule($name, $title);
    }

    $params = array('style' => $style);
    ob_start();

    echo $renderer->render($mod, $params);

    self::$mods[$module] = ob_get_clean();

    return self::$mods[$module];
  }

  public function addStyle($head){
    $session = JFactory::getSession();
    $doc = JFactory::getDocument();
    $app = JFactory::getApplication();

    $matches  = preg_grep ('/bootstrap.css/i', array_keys($doc->_styleSheets));
    if(!$matches){
      $matches  = preg_grep ('/maxcdn.bootstrapcdn.com\/bootstrap/i', array_keys($doc->_styleSheets));
    }
    $plugin = JPluginHelper::getPlugin('system', 'cck_system');
    $params = new JRegistry;
    $params->loadString($plugin->params);
    $link = '';
    if($app->isSite()){
      switch($params->get('bootstrap_version','0')){
        case "1":
          //dont include bootstrap
        break;
        case "2":
          //include bootstrap 2
          $link = JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap2.css";
          $session->set('bootstrap', '2');
        break;
        case "3":
          //include bootstrap 3
          $link = JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css";
          $session->set('bootstrap', '3');
        break;
        default:
          //try detect version of bootstrap
          if(isset($doc->_scripts[JURI::root(true).'/media/jui/js/bootstrap.min.js'])){
            $file = file_get_contents(JPATH_BASE.'/media/jui/js/bootstrap.min.js');
            $match  = preg_match_all ('/Custom version for Joomla!/i', $file);
            if($match){
              $session->set('bootstrap', '2');
            }else{
              $session->set('bootstrap', '3');
              $link = JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css";
            }
          }else if(count($matches)){
            foreach ($matches as $value) {
              //need test if we have bootstrap
              $match  = preg_match_all ('/maxcdn.bootstrapcdn.com\/bootstrap\/2/i', $value);
              if($match){
                $session->set('bootstrap', '2');
              }else{
                $match  = preg_grep ('/maxcdn.bootstrapcdn.com\/bootstrap\/3/i', array_keys($doc->_styleSheets));
                if($match){
                  $session->set('bootstrap', '3');
                }
              }
            }
          }else{
            $session->set('bootstrap', '3');
            $link = JURI::root() . "/components/com_os_cck/assets/bootstrap/css/bootstrap.css";
          }
      }
      if($link)
        $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JUri::root() . "components/com_os_cck/assets/css/front_end_style.css";
    if(!preg_match_all('|front_end_style.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::root() . '/components/com_os_cck/assets/css/jquery-ui-1.10.3.custom.min.css';
    if(!preg_match_all('|jquery-ui-1.10.3.custom.min.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::root() . "components/com_os_cck/assets/css/fine-uploader-new.css";
    if(!preg_match_all('|fine-uploader-new.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JURI::root() . "components/com_os_cck/assets/js/fine-uploader.js";
    if(!preg_match_all('|fine-uploader.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    $link = JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.css';
    if(!preg_match_all('|tabcontent.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JUri::root() . '/components/com_os_cck/assets/lightbox/css/lightbox.css';
    if(!preg_match_all('|lightbox.css|',$head,$matches)){
      $head .= '<link rel="stylesheet" href="'.$link.'">'."\n";
    }

    $link = JUri::root() . "/components/com_os_cck/assets/js/jQuerCCK-2.1.4.js";
    if(!preg_match_all('|jQuerCCK-2.1.4.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
      $head .= '<script type="text/javascript">jQuerCCK=jQuerCCK.noConflict();</script>'."\n";
    }

    $key = '&key='.JComponentHelper::getParams('com_os_cck')->get("google_map_key",'');
    $link = "//maps.google.com/maps/api/js?sensor=false".$key;
    if(!preg_match_all('|maps.google|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    $link = JUri::root() . 'components/com_os_cck/assets/js/jquery.raty.js';
    if(!preg_match_all('|jquery.raty.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    $link = JUri::root() . "/components/com_os_cck/assets/js/jquery-ui-cck-1.10.3.custom.min.js";
    if(!preg_match_all('|jquery-ui-cck-1.10.3.custom.min.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    $link = JURI::root() . '/components/com_os_cck/assets/TABS/tabcontent.js';
    if(!preg_match_all('|tabcontent.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }    

    $link = JURI::root() . '/components/com_os_cck/assets/lightbox/js/lightbox-2.6.min.js';
    if(!preg_match_all('|lightbox-2.6.min.js|',$head,$matches)){
      $head .= '<script type="text/javascript" src="'.$link.'"></script>'."\n";
    }

    return $head;
  }

}