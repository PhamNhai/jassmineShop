<?php
/**
* @package OS Touch Slider.
* @copyright 2017 OrdaSoft.
* @author 2017 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com),Sergey Buchastiy(buchastiy1989@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/



defined('_JEXEC') or die;


if($module->id==0)return;
//setters&getters->
require_once dirname(__FILE__) . '/helper.php';
$doc = JFactory::getDocument();
//stylesheet
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/swiper.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/animate.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/font-awesome.min.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/fine-uploader-new-sl.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/jquery.slider.minicolors.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/jquery-ui.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/cssgram.min.css");
$doc->addStyleSheet(JURI::root() . "modules/mod_os_touchslider/assets/css/style.css");

//script
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/jQuerOSS.js");
$doc->addScriptDeclaration("jQuerOSS=jQuerOSS.noConflict();");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/swiper-sl.js");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/jquery-ui.js");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/fine-uploader-sl.js");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/jquery.slider.minicolors.js");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/jquery.json.js");
//include settings // webfonts library needed
$doc->addScript("https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js");
$doc->addScript(JURI::root() . "modules/mod_os_touchslider/assets/js/os.touchslider.main.js");


//params from xml
$suffix = $params->get('moduleclass_sfx','');
//end
//->view variables
$unsortableImages = modOsTouchSliderHelper::getSliderImages($module->id);
$sliderText = modOsTouchSliderHelper::getSliderText($module->id);
$params = modOsTouchSliderHelper::getSliderParams($module->id);
//sort images
if(count($params->get("imageOrdering"))){
  foreach ($params->get("imageOrdering") as $key => $orderId) {
    if(isset($unsortableImages[$orderId])){
      $images[$key] = $unsortableImages[$orderId];
      unset($unsortableImages[$orderId]);
    }
  }
}//end sort
if($unsortableImages){
  foreach ($unsortableImages as $image) {
    $images[] = $image;
  }
}
$crop = $params->get('crop_image',0);
$pixels = $params->get('pixels',1)?'px':'%';
$parallax = $params->get("effect","slide")=='parallax'?1:0;

if($params->get('pixels',1)){
  $slider_width = $params->get('width_px',400);
  $slider_height = $params->get('height_px',200);
}else{
  $slider_width = $params->get('width_per',100);
  $slider_height = $params->get('height_per',100);
}
$image_width = $params->get('image_width',400);
$image_height = $params->get('image_height',200);

//style
$container_style = 'style="width:'.$slider_width.$pixels.';height:'.$slider_height.$pixels.';"';

if(isset($images)){
  foreach ($images as $image) {
    if($crop){
      $src = JPATH_BASE.'/images/os_touchslider_'.$module->id.'/original/'.$image->file_name;

      $pathinfo = pathinfo(strtolower($src));
      $ext = $pathinfo['extension'];
      $ext = '.'.$ext;
      $fileName = $pathinfo['filename'];
      $dest = JPATH_BASE.'/images/os_touchslider_'.$module->id.'/slideshow/'.$fileName.'_'.$image_width.'_'.$image_height.'_'.$crop.$ext;
      $slide_dest = JURI::root().'images/os_touchslider_'.$module->id.'/slideshow/'.$fileName.'_'.$image_width.'_'.$image_height.'_'.$crop.$ext;
      if(!file_exists($slide_dest)){
        modOsTouchSliderHelper::createimageThumbnail($src, $dest, $image_width, $image_height ,$crop);
        if(!file_exists($slide_dest)){
          $image->image_path = $slide_dest;
        }
      }
    }else{
      $image->image_path = JURI::root().'/images/os_touchslider_'.$module->id.'/original/'.$image->file_name;
    }
    $src = JPATH_BASE.'/images/os_touchslider_'.$module->id.'/original/'.$image->file_name;
    $pathinfo = pathinfo(strtolower($src));

    $ext = isset($pathinfo['extension'])?$pathinfo['extension']:'';
    $ext = '.'.$ext;
    $fileName = $pathinfo['filename'];

    $dest = JPATH_BASE.'/images/os_touchslider_'.$module->id.'/slideshow/'.$fileName.'_150_100_1'.$ext;
    $slide_dest = JURI::root().'images/os_touchslider_'.$module->id.'/slideshow/'.$fileName.'_150_100_1'.$ext;
    if(!file_exists($slide_dest)){
      modOsTouchSliderHelper::createimageThumbnail($src, $dest, 150, 100 ,1);
      $image->thumbnail_path = $slide_dest;
    }
  }
}

//get itemId need for correct com_ajax query
$request = JFactory::getApplication()->input;
$app = JFactory::getApplication();
if($app->getMenu()->getActive()){
  $itemId = $app->getMenu()->getActive()->id;
}elseif($request->getRaw('ItemId',false)){
  $itemId = $request->get('ItemId');
}else{
  $itemId = 101;
}
//get sldier version
$xml = @simplexml_load_file(JPATH_BASE . "/modules/mod_os_touchslider/mod_os_touchslider.xml");
$sliderV = '';
$creationDate = '';
if($xml){
  $sliderV = (string)$xml->version;
  $creationDate = (string)$xml->creationDate;

  unset($xml);

  //check update
  $avaibleUpdate = false;
  $url="http://ordasoft.com/xml_update/mod_os_touchslider.xml";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 1);

  $data = curl_exec($ch);
  curl_close($ch);

  $xml = simplexml_load_string($data);
  $updateArticleUrl = '#';
  $sliderVArr = explode(".", $sliderV);
  if($xml && isset($xml->version)){
    $ordasoftV = (string)$xml->version;
    $ordasoftVArr = explode(".", $ordasoftV);
    $ordasoftCreationDate = (string)$xml->creationDate;
    $updateArticleUrl = (string)$xml->updateArticleUrl;
    foreach ($sliderVArr as $k => $sliderSubV) {
      if(isset($ordasoftVArr[$k])){
        if((int)$ordasoftVArr[$k] < (int)$sliderSubV){
          break;
        }
        if((int)$ordasoftVArr[$k] > (int)$sliderSubV){
          $avaibleUpdate = true;
          break;
        }
      }
    }
  }
  unset($xml);
}

//->view
require JModuleHelper::getLayoutPath('mod_os_touchslider', $params->get('layout', 'default'));
