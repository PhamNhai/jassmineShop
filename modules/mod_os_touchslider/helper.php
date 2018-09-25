<?php

defined('_JEXEC') or die;
/**
* @package OS Touch Slider.
* @copyright 2017 OrdaSoft.
* @author 2017 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com),Sergey Buchastiy(buchastiy1989@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/

class modOsTouchSliderHelper{
  /**
   *
   * @param   array  $params An object containing the module parameters
   *
   * @access public
   */    

  public static function getAjax() {
    // saving images


    jimport('joomla.application.module.helper');
    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.file');
    $db = JFactory::getDbo();
    $input  = JFactory::getApplication()->input;

    $task = $input->get('task', '');
    $moduleId = $input->getInt('moduleId',0);

    if($task == 'save_settings'){
      $query = "SELECT params FROM #__os_touch_slider WHERE id=".$moduleId;
      $db->setQuery($query);
      $moduleParams = $db->loadResult();

     
      $params = new JRegistry;
      $params->loadString($moduleParams);
      $frontendParams = $input->get('form_data',array(),'ARRAY');

      if(isset($frontendParams['width_pixels']) && $frontendParams['width_pixels']){
        $params->set("width_pixels", $frontendParams['width_pixels']);
        $params->set("width_px", isset($frontendParams['width_px'])? $frontendParams['width_px'] : 400);
      }else{
        $params->set("width_pixels", $frontendParams['width_pixels']);
        $params->set("width_per", isset($frontendParams['width_per'])? $frontendParams['width_per'] : 100);
      }
      if(isset($frontendParams['height_pixels']) && $frontendParams['height_pixels']){
        $params->set("height_pixels", $frontendParams['height_pixels']);
        $params->set("height_px", isset($frontendParams['height_px'])? $frontendParams['height_px'] : 200);
      }else{
        $params->set("height_pixels", $frontendParams['height_pixels']);
        $params->set("height_per", isset($frontendParams['height_per'])? $frontendParams['height_per'] : 100);
      }
      $params->set("height_auto", (isset($frontendParams['height_auto'])&& $frontendParams['height_auto'] != 'false')? $frontendParams['height_auto'] : 0);
      $params->set("direction", isset($frontendParams['direction'])? $frontendParams['direction'] : 'horizontal');
      $params->set("initialSlide", isset($frontendParams['initialSlide'])? $frontendParams['initialSlide'] : 0);
      $params->set("autoplay", isset($frontendParams['autoplay'])? $frontendParams['autoplay'] : 0);
      $params->set("autoplayStopOnLast", isset($frontendParams['autoplayStopOnLast'])? $frontendParams['autoplayStopOnLast'] : 0);
      $params->set("autoplayDisableOnInteraction", isset($frontendParams['autoplayDisableOnInteraction'])? $frontendParams['autoplayDisableOnInteraction'] : 0);
      $params->set("speed", isset($frontendParams['speed'])? $frontendParams['speed'] : 300);
      $params->set("effect", isset($frontendParams['effect'])? $frontendParams['effect'] : "slide");
      $params->set("freeMode", isset($frontendParams['freeMode'])? $frontendParams['freeMode'] : 0);
      $params->set("freeModeMomentum", isset($frontendParams['freeModeMomentum'])? $frontendParams['freeModeMomentum'] : 1);
      $params->set("freeModeMomentumRatio", isset($frontendParams['freeModeMomentumRatio'])? $frontendParams['freeModeMomentumRatio'] : 1);
      $params->set("freeModeMomentumBounce", isset($frontendParams['freeModeMomentumBounce'])? $frontendParams['freeModeMomentumBounce'] : 1);
      $params->set("freeModeMomentumBounceRatio", isset($frontendParams['freeModeMomentumBounceRatio'])? $frontendParams['freeModeMomentumBounceRatio'] : 1);
      $params->set("freeModeMinimumVelocity", isset($frontendParams['freeModeMinimumVelocity'])? $frontendParams['freeModeMinimumVelocity'] : 0.02);
      $cube = isset($frontendParams['cube'])? $frontendParams['cube'] : '';
      $params->set("slideShadows", isset($cube['slideShadows'])? $cube['slideShadows'] : 1);
      $params->set("shadow", isset($cube['shadow'])? $cube['shadow'] : 1);
      $params->set("shadowOffset", isset($cube['shadowOffset'])? $cube['shadowOffset'] : 40);
      $params->set("shadowScale", isset($cube['shadowScale'])? $cube['shadowScale'] : 0.94);
      $coverflow = isset($frontendParams['coverflow'])? $frontendParams['coverflow'] : '';
      $params->set("rotate", isset($coverflow['rotate'])? $coverflow['rotate'] : 50);
      $params->set("stretch", isset($coverflow['stretch'])? $coverflow['stretch'] : 0);
      $params->set("depth", isset($coverflow['depth'])? $coverflow['depth'] : 10);
      $params->set("modifier", isset($coverflow['modifier'])? $coverflow['modifier'] : 1);
      $params->set("coverflowSlideShadows", isset($coverflow['coverflowSlideShadows'])? $coverflow['coverflowSlideShadows'] : 1);
      $flip = isset($frontendParams['flip'])? $frontendParams['flip'] : '';
      $params->set("slideShadows", isset($flip['slideShadows'])? $flip['slideShadows'] : 1);
      $params->set("limitRotation", isset($flip['limitRotation'])? $flip['limitRotation'] : 1);
      $params->set("spaceBetween", isset($frontendParams['spaceBetween'])? $frontendParams['spaceBetween'] : 0);
      $params->set("slidesPerView", isset($frontendParams['slidesPerView'])? $frontendParams['slidesPerView'] : 1);
      $params->set("slidesPerColumn", isset($frontendParams['slidesPerColumn'])? $frontendParams['slidesPerColumn'] : 1);
      $params->set("slidesPerColumnFill", isset($frontendParams['slidesPerColumnFill'])? $frontendParams['slidesPerColumnFill'] : 'column');
      $params->set("slidesPerGroup", isset($frontendParams['slidesPerGroup'])? $frontendParams['slidesPerGroup'] : 1);
      $params->set("centeredSlides", isset($frontendParams['centeredSlides'])? $frontendParams['centeredSlides'] : 0);
      $params->set("pagination", isset($frontendParams['pagination'])? $frontendParams['pagination'] : '.swiper-pagination');
      $params->set("paginationType", isset($frontendParams['paginationType'])? $frontendParams['paginationType'] : 'bullets');
      $params->set("paginationClickable", isset($frontendParams['paginationClickable'])? $frontendParams['paginationClickable'] : 1);
      $params->set("scrollbar", isset($frontendParams['scrollbar'])? $frontendParams['scrollbar'] : '.swiper-scrollbar');
      $params->set("scrollbarHide", isset($frontendParams['scrollbarHide'])? $frontendParams['scrollbarHide'] : 1);
      $params->set("scrollbarDraggable", isset($frontendParams['scrollbarDraggable'])? $frontendParams['scrollbarDraggable'] : 0);
      $params->set("keyboardControl", isset($frontendParams['keyboardControl'])? $frontendParams['keyboardControl'] : 0);
      $params->set("mousewheelControl", isset($frontendParams['mousewheelControl'])? $frontendParams['mousewheelControl'] : 0);
      $params->set("mousewheelReleaseOnEdges", isset($frontendParams['mousewheelReleaseOnEdges'])? $frontendParams['mousewheelReleaseOnEdges'] : 0);
      $params->set("loop", isset($frontendParams['loop'])? $frontendParams['loop'] : 0);
      $params->set("userScreenWidth", isset($frontendParams['userScreenWidth'])? $frontendParams['userScreenWidth'] : 0);
      $params->set("prev_next_arrows", isset($frontendParams['prev_next_arrows'])? $frontendParams['prev_next_arrows'] : 0);
      $params->set("imageOrdering", isset($frontendParams['imageOrdering'])? $frontendParams['imageOrdering'] : array());
      $params->set("textOrdering", isset($frontendParams['textOrdering'])? $frontendParams['textOrdering'] : '[]');
      $params->set("crop_image", isset($frontendParams['crop_image'])? $frontendParams['crop_image'] : 0);
      $params->set("image_width", isset($frontendParams['image_width'])? $frontendParams['image_width'] : 400);
      $params->set("image_height", isset($frontendParams['image_height'])? $frontendParams['image_height'] : 200);
      $params->set("lazyLoading", isset($frontendParams['lazyLoading'])? $frontendParams['lazyLoading'] : 0);
      $params->set("lazyLoadingInPrevNext", isset($frontendParams['lazyLoadingInPrevNext'])? $frontendParams['lazyLoadingInPrevNext'] : 0);
      $params->set("lazyLoadingInPrevNextAmount", isset($frontendParams['lazyLoadingInPrevNextAmount'])? $frontendParams['lazyLoadingInPrevNextAmount'] : 1);
      $params->set("setupAnimation", isset($frontendParams['setupAnimation'])? json_decode($frontendParams['setupAnimation']) : array());
      $params->set("textAnimation", isset($frontendParams['textAnimation'])? json_decode($frontendParams['textAnimation']) : array());
      $params->set("imageFullTime", isset($frontendParams['imageFullTime']) ? json_decode($frontendParams['imageFullTime']) : array());
      $params->set("imageFilter", isset($frontendParams['imageFilter']) ? json_decode($frontendParams['imageFilter']) : array());

      $params->set("imageBackground", isset($frontendParams['imageBackground']) ? json_decode($frontendParams['imageBackground']) : array());


      $params->set("version", isset($frontendParams['version']) ? $frontendParams['version'] : '');

      $params->set("object_fit", isset($frontendParams['object_fit'])? $frontendParams['object_fit'] : 0);

      $query = "UPDATE #__os_touch_slider SET params=".$db->Quote($params->toString())." WHERE module_id=".$moduleId;
      $db->setQuery($query);
      $db->query();
      $textArray = $input->get('image_text_data','','RAW');
      $textArray = json_decode($textArray);


      if(count($textArray)){

        $query = "SELECT DISTINCT fk_ts_img_id FROM #__os_touch_slider_text WHERE fk_ts_id=$moduleId";
        $db->setQuery($query);
        $existText = $db->loadObjectList('fk_ts_img_id');

        //every image

        foreach ($textArray as $imgId => $textHtmlAray){
          if($imgId && count($textHtmlAray)){
            if(isset($existText[$imgId])){
              unset($existText[$imgId]);
            }

            $query2 = "DELETE FROM #__os_touch_slider_text WHERE fk_ts_img_id=$imgId AND fk_ts_id=$moduleId";
            $db->setQuery($query2);
            $db->query();
            //every text
            foreach ($textHtmlAray as $textHtml) {

              $query2 = "INSERT INTO #__os_touch_slider_text(fk_ts_id, fk_ts_img_id, text_html)".
                        "\n VALUES($moduleId, $imgId, ".$db->quote($textHtml).")";
              $db->setQuery($query2);
              $db->query();
            }
          }

          if(count($existText)){

            foreach ($existText as $imgId => $value) {
              $query2 = "DELETE FROM #__os_touch_slider_text WHERE fk_ts_img_id=$imgId AND fk_ts_id=$moduleId";
              $db->setQuery($query2);
              $db->query();
            }
          }
        }
      }else{
        $query2 = "DELETE FROM #__os_touch_slider_text WHERE fk_ts_id=$moduleId";
        $db->setQuery($query2);
        $db->query();
      }

      //delete export folder
      self::delFolder(JPATH_ROOT . '/modules/mod_os_touchslider/export');

    //end
      $response = array('success' => true, 'message' => '');
      echo json_encode($response);
    }else if($task == 'delete_image'){

      $imgId = $input->get('imgId','');

      
      if(!$imgId){
        $response = array('success' => false, 'message' => 'Image id is empty!');
        echo json_encode($response);
        exit;
      }
      $query = "SELECT file_name FROM #__os_touch_slider WHERE id = ".$imgId;
      $db->setQuery($query);
      $file_name = $db->loadResult();

      $query2 = "DELETE FROM #__os_touch_slider_text WHERE fk_ts_img_id=$imgId AND fk_ts_id=$moduleId";
      $db->setQuery($query2);
      $db->query();
      $query = "DELETE FROM #__os_touch_slider WHERE id =".$imgId;
      $db->setQuery($query)->query();

      $ext = strtolower(substr($file_name, strrpos($file_name,".")));
      $file_name = substr($file_name,0,(-strlen($ext)));


      if(file_exists(JPATH_BASE.'/images/os_touchslider_'.$moduleId.'/original/'.$file_name.$ext))  unlink(JPATH_BASE.'/images/os_touchslider_'.$moduleId.'/original/'.$file_name.$ext);
 
      $mask = JPATH_BASE.'/images/os_touchslider_'.$moduleId.'/slideshow/'.strtolower($file_name).'*';
      array_map("unlink", glob( $mask ));

      $mask = JPATH_BASE.'/images/os_touchslider_'.$moduleId.'/thumbnail/'.$file_name.'*';
      array_map("unlink", glob( $mask ));


      $response = array('success' => true, 'message' => '');
      echo json_encode($response);
    }elseif(isset($_FILES['qqslfile']) && $_FILES['qqslfile']['type'] == 'application/zip'){

          $fncresponse = self::fileImport($_FILES['qqslfile']);
          
          if($fncresponse){
            $response['success'] = true;
          }else{
            $response['success'] = false;
          }

          $response['id'] = 'import';
          echo json_encode($response);
          exit;

      }elseif($task == "fileExport"){
          
          $result = self::fileExport();

          if($result){
            $response['success'] = true;
            $response['path'] = $result;
          }else{
            $response['success'] = $result;
          }
            echo json_encode($response);

      }elseif($task == "checkOldVers"){
          
          $response['success'] = self::checkOldVers();
          echo json_encode($response);

      }else{

      $response = array('success' => false, 'message' => '');
      if (isset($_GET['qqslfile'])) {
        $post_form = false;
      } elseif (isset($_FILES['qqslfile'])) {
        $post_form = true;
      } else {
        $response['message'] = 'File is empty, check your file and try again!';
        echo json_encode($response);
        return;
      }
      //setup some variables
      $pathinfo = pathinfo(strtolower(self::getFileName($post_form)));
      $filename = JApplication::stringURLSafe($pathinfo['filename']);
      $filename .= self::touch_guid();
      $ext = $pathinfo['extension'];
      $max_filesize = self::toBytes(ini_get('upload_max_filesize'));
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
      $moduleId = $input->getInt('id', 0);
      $dir = JPATH_BASE . '/images';

      //check maxFileSize
      if (self::getFileSize($post_form) > $max_filesize) {
        $response['message'] = "File is too largest";
        echo json_encode($response);
        return;
      }
      if (self::getFileSize($post_form) == 0) {
        $response['message'] = "File is empty, check your file and try again";
        echo json_encode($response);
        return;
      }
      if (!in_array(strtolower($ext), $allowedExtensions)) {
        $response['message'] = "Invalid extension, allowed: ".implode(", ",$allowedExtensions);
        echo json_encode($response);
        return;
      }
      //check and create folder
      $dir = $dir . '/os_touchslider_' . $moduleId;
      if (!file_exists($dir) || !is_dir($dir)) mkdir($dir);
      if (!file_exists($dir . '/original') || !is_dir($dir)) mkdir($dir . '/original');
      if (!file_exists($dir . '/slideshow') || !is_dir($dir)) mkdir($dir . '/slideshow');
      if (!file_exists($dir . '/thumbnail') || !is_dir($dir)) mkdir($dir . '/thumbnail');

      //saving file
      $ext = '.'.$ext;
      if (!self::fileSave("{$dir}/original/{$filename}{$ext}", $post_form)) {
        $response['message'] = "Can't save file here: {$dir}/original/{$filename}{$ext}";
      }else{
        $imagesize = getimagesize("{$dir}/original/{$filename}{$ext}", $imageinfo);
        self::createimageThumbnail($dir . "/original/{$filename}{$ext}", $dir . "/thumbnail/{$filename}_150_100_1{$ext}", 150, 100, 1);

        $image_width = $input->getInt('image_width', 0);
        $image_height = $input->getInt('image_height', 0);
        $crop = $input->getInt('crop', 0);
        if($image_width && $image_height && $crop){
          $destSlideshow = JPATH_BASE.'/images/os_touchslider_'.$moduleId.'/slideshow/'.$filename.'_'.$image_width.'_'.$image_height.'_'.$crop.$ext;
          self::createimageThumbnail($dir . "/original/{$filename}{$ext}", $destSlideshow, $image_width, $image_height ,$crop);
        }
        //save image to database

       
        if(self::getImageIdCopyName($post_form))
          {
            $id = self::getImageIdCopyName($post_form);
            $response['image_copy_id'] = self::dbUpdateImages($id, $filename.$ext, $moduleId);
          }
        else
          {
            $response['id'] = self::dbSaveImages($filename.$ext, $moduleId);
          }

        $response['success'] = true;
        $response['file'] = $filename;
        $response['ext'] = $ext;
      }

      echo json_encode($response);
      exit;
    }
  }

  static function delFolder($dir){
    if(!is_dir($dir)) return;
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
    (is_dir("$dir/$file")) ? self::delFolder("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  }

  static function getFileName($post_form){
    if($post_form){
      return $_FILES['qqslfile']['name'];
    }else{
      return $_GET['qqslfile'];
    }
  }

  static function checkOldVers(){
     $db = JFactory::getDbo();

     $query = "SELECT params FROM `#__os_touch_slider` WHERE 1";
     $db->setQuery($query);
     $result = $db->loadResult();

     if(!$result) return false;
     $object = json_decode($result);      

     if(isset($object->version)) return false;

   

     return true;
  }



    static function getImageIdCopyName($post_form){
    if($post_form){
      if(isset($_POST['image_copy_id']))
      {
        return $_POST['image_copy_id'];
      }
      else
      {
        return false;
      }
      
    }
  }

  static function toBytes($val) {
    if (empty($val)){
            return 0;
        }

        $val = trim($val);
        preg_match('#([0-9]+)[\s]*([a-z]+)#i', $val, $matches);
        $last = '';

        if (isset($matches[2])){
            $last = $matches[2];
        }

        if (isset($matches[1])){
            $val = (int) $matches[1];
        }

        switch (strtolower($last)){
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }

        return (int) $val;
  }

  static function getFileSize($post_form){
    if($post_form){
      return $_FILES['qqslfile']['size'];
    }else{
      if (isset($_SERVER["CONTENT_LENGTH"])) {
        return (int) $_SERVER["CONTENT_LENGTH"];
      } else {
        throw new Exception('Getting content length is not supported.');
      }
    }
  }

  static function fileSave($dest, $post_form){
    if($post_form){
      if (!move_uploaded_file($_FILES['qqslfile']['tmp_name'], $dest)) {
        return false;
      }
      return true;
    }else{
      $input = fopen("php://input", "r");
      $temp = tmpfile();
      if(!$temp){
        $temp = fopen("ImageTempFile.txt","w+");
      }
      $realSize = stream_copy_to_stream($input, $temp);
      fclose($input);
      if ($realSize != self::getFileSize($post_form)) {
        return false;
      }
      $target = fopen($dest, "w");
      fseek($temp, 0, SEEK_SET);
      stream_copy_to_stream($temp, $target);
      fclose($target);
      return true;
    }
  }

  static function createimageThumbnail($src, $dest, $destWidht, $destHeight ,$crop = true, $quality = 95){
    // Setting the resize parameters
    $info = @getimagesize($src, $imageinfo);
    $file_type = '.'.str_replace('image/', '', $info['mime']);
    $width = $info[0];
    $height = $info[1];

    if (file_exists($dest)) {
      return;
    }else{
      if ($width < $height) {
        if ($height > $destHeight) {
          $k = $height / $destHeight;
        } else if ($width > $destWidht) {
          $k = $width / $destWidht;
        }
        else
          $k = 1;
      } else {
        if ($width > $destWidht) {
          $k = $width / $destWidht;
        } else if ($height > $destHeight) {
          $k = $height / $destHeight;
        }
        else
          $k = 1;
      }
      $w_ = $width / $k;
      $h_ = $height / $k;
    }

    if($crop == 1){ 
      $CreateNewImage = self::cck_createImage($src, $dest, $destWidht, $destHeight ,$crop, $quality);
      return;
    }
    // Creating the Canvas
    $tn = imagecreatetruecolor($w_, $h_);
    switch (strtolower($file_type)) {
        case '.png':
            $source = imagecreatefrompng($src);
            $file = imagecopyresampled($tn, $source, 0, 0, 0, 0, $w_, $h_, $width, $height);
            imagepng($tn, $dest);
            break;
        case '.jpg':
            $source = imagecreatefromjpeg($src);
            $file = imagecopyresampled($tn, $source, 0, 0, 0, 0, $w_, $h_, $width, $height);
            imagejpeg($tn, $dest);
            break;
        case '.jpeg':
            $source = imagecreatefromjpeg($src);
            $file = imagecopyresampled($tn, $source, 0, 0, 0, 0, $w_, $h_, $width, $height);
            imagejpeg($tn, $dest);

            break;
        case '.gif':
            $source = imagecreatefromgif($src);
            $file = imagecopyresampled($tn, $source, 0, 0, 0, 0, $w_, $h_, $width, $height);
            imagegif($tn, $dest);
            break;
        default:
            echo 'not support';
            return;
    }
  }

  static function cck_createImage($src, $dest, $destWidht, $destHeight ,$crop = true, $quality = 95){
    $info = @getimagesize($src, $imageinfo);
    $sWidth = $info[0];
    $sHeight = $info[1];

    if(!$sWidth || !$sHeight) return;

    if ($sHeight / $sWidth > $destHeight / $destWidht) {
      $width = $sWidth;
      $height = round(($destHeight * $sWidth) / $destWidht);
      $sx = 0;
      $sy = round(($sHeight - $height) / 3);
    }
    else {
      $height = $sHeight;
      $width = round(($sHeight * $destWidht) / $destHeight);
      $sx = round(($sWidth - $width) / 2);
      $sy = 0;
    }

    if (!$crop) {
      $sx = 0;
      $sy = 0;
      $width = $sWidth;
      $height = $sHeight;
    }

    $ext = str_replace('image/', '', $info['mime']);
    $imageCreateFunc = self::getImageCreateFunction($ext);
    $imageSaveFunc = self::getImageSaveFunction($ext);

    $sImage = $imageCreateFunc($src);
    $dImage = imagecreatetruecolor($destWidht, $destHeight);

    // Make transparent
    if ($ext == 'png') {
      imagealphablending($dImage, false);
      imagesavealpha($dImage,true);
      $transparent = imagecolorallocatealpha($dImage, 255, 255, 255, 127);
      imagefilledrectangle($dImage, 0, 0, $destWidht, $destHeight, $transparent);
    }
    imagecopyresampled($dImage, $sImage, 0, 0, $sx, $sy, $destWidht, $destHeight, $width, $height);

    if ($ext == 'png') {
      $imageSaveFunc($dImage, $dest, 9);
    }
    else if ($ext == 'gif') {
      $imageSaveFunc($dImage, $dest);
    }
    else {
      $imageSaveFunc($dImage, $dest, $quality);
    }
  }

  static function getImageCreateFunction($type){
    switch ($type) {
      case 'jpeg':
      case 'jpg':
        $imageCreateFunc = 'imagecreatefromjpeg';
        break;

      case 'png':
        $imageCreateFunc = 'imagecreatefrompng';
        break;

      case 'bmp':
        $imageCreateFunc = 'imagecreatefrombmp';
        break;

      case 'gif':
        $imageCreateFunc = 'imagecreatefromgif';
        break;

      case 'vnd.wap.wbmp':
        $imageCreateFunc = 'imagecreatefromwbmp';
        break;

      case 'xbm':
        $imageCreateFunc = 'imagecreatefromxbm';
        break;

      default:
        $imageCreateFunc = 'imagecreatefromjpeg';
    }
    return $imageCreateFunc;
  }

  static function getImageSaveFunction($type) {
    switch ($type) {
      case 'jpeg':
        $imageSaveFunc = 'imagejpeg';
        break;

      case 'png':
        $imageSaveFunc = 'imagepng';
        break;

      case 'bmp':
        $imageSaveFunc = 'imagebmp';
        break;

      case 'gif':
        $imageSaveFunc = 'imagegif';
        break;

      case 'vnd.wap.wbmp':
        $imageSaveFunc = 'imagewbmp';
        break;

      case 'xbm':
        $imageSaveFunc = 'imagexbm';
        break;

      default:
        $imageSaveFunc = 'imagejpeg';
    }
    return $imageSaveFunc;
  }

  static function dbSaveImages($filename, $moduleId){
    $db = JFactory::getDbo();

    $query = "INSERT INTO #__os_touch_slider(file_name,module_id) ".
            "\n VALUES (".$db->Quote($filename).",'".$moduleId."')";
    $db->setQuery($query);
    $db->query();

    return $db->insertid();
  }

  static function dbUpdateImages($id, $filename, $moduleId){
    $db = JFactory::getDbo();

    $query = "UPDATE  #__os_touch_slider ".
            "\n SET  file_name = " . $db->Quote($filename) . ", module_id = " . $moduleId . " WHERE id = ".$id;
    $db->setQuery($query);
    $db->query();

    return $id;
  }

  static function getSliderImages($moduleId){
    $db = JFactory::getDbo();

    $query = "SELECT * FROM #__os_touch_slider".
            "\n WHERE module_id = ".$moduleId;
    $db->setQuery($query);
    $images = $db->loadObjectList("id");
    return $images;
  }

  static function getSliderText($moduleId){
    $db = JFactory::getDbo();

    $query = "SELECT fk_ts_img_id,text_html FROM #__os_touch_slider_text".
            "\n WHERE fk_ts_id = ".$moduleId;
    $db->setQuery($query);
    $sliderText = $db->loadObjectList();
    $sliderTextArr = array();
    foreach ($sliderText as $slideText) {
      if(!isset($sliderTextArr[$slideText->fk_ts_img_id]))$sliderTextArr[$slideText->fk_ts_img_id] = array();
      array_push($sliderTextArr[$slideText->fk_ts_img_id], $slideText->text_html);
    }

    return $sliderTextArr;
  }

  static function getSliderParams($moduleId){
    $db = JFactory::getDbo();
    $params = new JRegistry;

    $query = "SELECT params FROM #__os_touch_slider WHERE module_id = ".$moduleId;
    $db->setQuery($query);
    $moduleParams = $db->loadResult();
    $params->loadString($moduleParams);

    return $params;
  }

  static function touch_guid(){
    if (function_exists('com_create_guid')){
        return trim(com_create_guid(), '{}');
    }else{
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); 
        $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
        return $uuid;
    }
  }

  static function create_custom_imgAjax(){
    $input  = JFactory::getApplication()->input;
    $module_id = $input->getInt('moduleId','');
    $text_color = $input->getInt('textColor','');

    $response = self::dbAddCustomImg($module_id);

    echo json_encode($response);
  }


  static function dbAddCustomImg($module_id){
      $db = JFactory::getDbo();
      $report = array();
      $touch_guid = self::touch_guid();
      $module_root = JPATH_ROOT.'/modules/mod_os_touchslider/';

      $custom_image = $module_root.'/assets/images/custom.png';

      if(!file_exists(JPATH_BASE . '/images/os_touchslider_' . $module_id)) mkdir(JPATH_BASE . '/images/os_touchslider_' . $module_id);
      if(!file_exists(JPATH_BASE . '/images/os_touchslider_' . $module_id . '/original/')) mkdir(JPATH_BASE . '/images/os_touchslider_' . $module_id . '/original/');
      if(!file_exists(JPATH_BASE . '/images/os_touchslider_' . $module_id . '/thumbnail/')) mkdir(JPATH_BASE . '/images/os_touchslider_' . $module_id . '/thumbnail/');

      $new_custom_image = JPATH_BASE . '/images/os_touchslider_' . $module_id . '/original/custom'.$touch_guid.'.png';
      $new_thumb_custom_image = JPATH_BASE . '/images/os_touchslider_' . $module_id . '/thumbnail/custom'.$touch_guid.'_150_100_1.png';

      $image = imagecreatetruecolor(1, 1);

      $transparent = imagecolorallocatealpha($image, 0, 0, 0, 0);
      imagefill($image, 0, 0, $transparent);

      $trcolor = ImageColorAllocate($image, 0, 0, 0);
      ImageColorTransparent($image , $trcolor);

      imagepng($image, $new_custom_image);
      imagepng($image, $new_thumb_custom_image);

      if(!copy($custom_image,$new_custom_image) || !copy($custom_image,$new_thumb_custom_image)){
        $report['error'] = "Image creating error";
        $report['img_id'] = false;
        return $report;
      }

      $query = "INSERT INTO #__os_touch_slider(file_name,module_id,params) ".
            "\n VALUES ('".'custom'.$touch_guid.'.png'."','".$module_id."','{}')";
      $db->setQuery($query);
      if(!$db->query()){
        $report['error'] = "Adding to DB error";
        $report['img_id'] = false;
        return $report;
      }

      $insertID = $db->insertid();
      $report['fileName'] = 'custom'.$touch_guid.'.png';
      $report['fileName_thumb'] = 'custom'.$touch_guid.'_150_100_1.png';
      $report['img_id'] = $insertID;
      return $report;

  }


  static function copyImageAjax()
  {
      jimport('joomla.application.module.helper');
      jimport('joomla.filesystem.folder');
      jimport('joomla.filesystem.file');
      $db = JFactory::getDbo();
      $input  = JFactory::getApplication()->input;

      $image_id = $input->getInt('image_id','');
      $module_id = $input->getInt('moduleId','');
      
      $response = self::dbCopyImage($image_id, $module_id);
      echo json_encode($response);

  }


  static function dbCopyImage($image_id, $module_id)
  {
    $db = JFactory::getDbo();

    $query = "SELECT file_name,module_id,src,params FROM #__os_touch_slider WHERE id = ".$image_id." && module_id = ".$module_id;
    $db->setQuery($query);
    $imageInfo = $db->loadAssoc();

    $old_filename = $imageInfo['file_name'];
    $module_id = $imageInfo['module_id'];
    $src = $imageInfo['src'];
    $params = $imageInfo['params'];


    $ext = strtolower(substr($old_filename, strrpos($old_filename,".")));
    $new_filename = substr($old_filename,0,-36+(-strlen($ext)));
    $touch_guid = self::touch_guid();
    $new_filename = $new_filename.$touch_guid.$ext;
    

    $folders = array('original','thumbnail');

    foreach ($folders as $folder) {
      self::saveCopyFile($old_filename, $new_filename, $folder, $ext, $module_id);
    }
    
    $query = "INSERT INTO #__os_touch_slider(file_name,module_id,src,params) ".
            "\n VALUES ('".$new_filename."','".$module_id."','".$src."','".$params."')";
    $db->setQuery($query);
    $db->query();
    $insertID = $db->insertid();

    //select - insert text
    $query = "SELECT fk_ts_id,fk_ts_img_id,text_html FROM #__os_touch_slider_text WHERE fk_ts_img_id = ".$image_id." && fk_ts_id = ".$module_id;
    $db->setQuery($query);
    $db->query();
    $num_rows = $db->getNumRows();
    if($num_rows)
    {
      $textInfo = $db->loadAssoc();

      $query = "INSERT INTO #__os_touch_slider_text(fk_ts_id,fk_ts_img_id,text_html) ".
              "\n VALUES ('".$module_id."','".$insertID."','".$textInfo['text_html']."')";
      $db->setQuery($query);
      $db->query();
    }
    

    $file = substr($new_filename,0,-strlen($ext));

    $response = array('id'=>$insertID,'file'=>$file,'ext'=>$ext);

    return $response;
    
    
  }




  static function saveCopyFile($old_filename, $new_filename, $folder, $ext, $module_id)
  {

      if($folder == 'thumbnail'){
        $old_filename = substr($old_filename,0,-(strlen($ext)));
        $new_filename = substr($new_filename,0,-(strlen($ext)));
        $old_filename = $old_filename . '_150_100_1' . $ext;
        $new_filename = $new_filename . '_150_100_1' . $ext;
      }

      $dir = JPATH_BASE . '/images';
      $dir = $dir . '/os_touchslider_' . $module_id . '/' . $folder;

      $copy_filename = $dir.'/'.$old_filename;
      $paste_filename = $dir.'/'.$new_filename;


      if(!file_exists($copy_filename) && $folder == 'thumbnail'){

          $dir = JPATH_BASE . '/images/os_touchslider_' . $module_id . '/original/' . str_replace('_150_100_1','',$new_filename);
          $dir_thumb = JPATH_BASE . '/images/os_touchslider_' . $module_id . '/thumbnail/' . $new_filename;
          self::createimageThumbnail($dir, $dir_thumb, 150, 100, 1);

      }else{

          if (!copy($copy_filename, $paste_filename)) {
            echo "не удалось скопировать $copy_filename...\n";
          }

      }

  }


  static function fileImport($importData){
    
    $database = JFactory::getDbo();
    $input  = JFactory::getApplication()->input;
    $moduleId = $input->getInt('id', 0);
    $params = new JRegistry;
    $install_mysql = JPATH_ROOT . '/modules/mod_os_touchslider/sql/mysql/install.mysql.utf8.sql';
    $imagePath = JPATH_ROOT.'/images/os_touchslider_'.$moduleId;
    $time_stamp = date('Y_m_d_H_i_s');

    rename($imagePath,$imagePath.'_backup_'. $time_stamp);
    mkdir($imagePath);

    $zip = new ZipArchive;

    $extract = $zip->open($importData['tmp_name']);

    if ($extract === TRUE) {
        $zip->extractTo($imagePath);
    }else{
        return false;
    }

    $imageXml = $imagePath . '/xml/touchSlider.xml';
    $textXml= $imagePath . '/xml/touchSliderText.xml';

    if(!file_exists($imageXml) || !file_exists($textXml)) return false;

    $objectImage = simplexml_load_file($imageXml);
    $objectText = simplexml_load_file($textXml); 

    //database preparation
    // rename old tables
    $query = "RENAME TABLE #__os_touch_slider TO #__os_touch_slider_backup_" . $time_stamp;
    $database->setQuery($query);
    if(!$database->execute()) return false;

    $query = "RENAME TABLE #__os_touch_slider_text TO #__os_touch_slider_text_backup_" . $time_stamp;
    $database->setQuery($query);
    if(!$database->execute()) return false;

    // add new database
    $query = "CREATE TABLE IF NOT EXISTS #__os_touch_slider (
              `id` int(11) unsigned NOT NULL auto_increment,
              `file_name` varchar(255) DEFAULT NULL,
              `module_id` int(11) unsigned DEFAULT NULL,
              `src` varchar(255) DEFAULT NULL,
              `params` TEXT NOT NULL,
               PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

    $database->setQuery($query);
    if(!$database->execute()) return false;

    $query = "CREATE TABLE IF NOT EXISTS #__os_touch_slider_text (
              `id` int(11) unsigned NOT NULL auto_increment,
              `fk_ts_id` varchar(255) DEFAULT NULL,
              `fk_ts_img_id` varchar(255) DEFAULT NULL,
              `text_html` LONGBLOB DEFAULT NULL,
               PRIMARY KEY (`id`),
               FOREIGN KEY (`fk_ts_id`) REFERENCES #__os_touch_slider(`module_id`),
               FOREIGN KEY (`fk_ts_img_id`) REFERENCES #__os_touch_slider(`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

    $database->setQuery($query);
    if(!$database->execute()) return false;     


    foreach ($objectImage->node as $image) {

      $query = "INSERT INTO #__os_touch_slider VALUES(" . $database->quote($image->node[0]) . "," .$database->quote($image->node[1]). "," .$moduleId. "," .$database->quote($image->node[3]). "," .$database->quote($image->node[4]).")";
      $database->setQuery($query);
      $database->execute();

    }

    foreach ($objectText->node as $text) {

      $query = "INSERT INTO #__os_touch_slider_text VALUES(null," .$moduleId. "," .$database->quote($text->node[2]). "," .$database->quote(htmlspecialchars($text->node[3])). ")";
      $database->setQuery($query);
      $database->execute();

    }

    if(file_exists($imageXml)) unlink($imageXml);
    if(file_exists($textXml)) unlink($textXml);
     
    if(is_dir($imagePath.'/xml')) rmdir($imagePath.'/xml');

    return true;

  }

  static function fileExport(){
     
    //define variable
      $db = JFactory::getDbo();
      $input  = JFactory::getApplication()->input;
      $moduleId = $input->getInt('moduleId', 0);
      $exportFolder = JPATH_ROOT . '/modules/mod_os_touchslider/export';
      $exportFolderXml = $exportFolder . '/xml';

      $imgFolder = JPATH_ROOT . '/images/os_touchslider_' . $moduleId;
      $jRegXml = new JRegistryFormatXml();
      $exportArhivePath = $exportFolder.'/OSSliderExport_modId_'.$moduleId.'.zip'; //название архива
      $zip = new ZipArchive;

    //create folder
      if(!is_dir($exportFolder)) mkdir($exportFolder);
      if(!is_dir($exportFolderXml)) mkdir($exportFolderXml);

    //get os_touch_slider & os_touch_slider_text data
      $touchSlider = self::getSliderData('os_touch_slider', $moduleId);
      $touchSliderText = self::getSliderData('os_touch_slider_text', $moduleId);

    //put object in xml
      $touchSliderXml = $jRegXml->objectToString($touchSlider);
      $touchSliderTextXml = $jRegXml->objectToString($touchSliderText);

    //create xml files
      file_put_contents($exportFolderXml . '/touchSlider.xml', $touchSliderXml);
      file_put_contents($exportFolderXml . '/touchSliderText.xml', $touchSliderTextXml);

    //get filelist xml, original, thumbnail
      $filelistXml = scandir($exportFolderXml);
      $filelistOrig = scandir($imgFolder . '/original');
      $filelistThumb = scandir($imgFolder . '/thumbnail');

    //remove archive before new create
      if(file_exists($exportArhivePath)) unlink($exportArhivePath);

    //archiving
      if ($zip->open($exportArhivePath, ZipArchive::CREATE) === TRUE){
        //add original to archive
        foreach($filelistOrig as $file){
          if ($file != '.' && $file != '..') $zip->addFile($imgFolder . '/original/' . $file, 'original/' . $file);
        }
        //add thumbnail to archive
        foreach($filelistThumb as $file){
          if ($file != '.' && $file != '..') $zip->addFile($imgFolder . '/thumbnail/' . $file, 'thumbnail/' . $file);
        }
        //add slideshow to archive
        $zip->addEmptyDir('slideshow');
        //add slideshow to archive
        foreach($filelistXml as $file){
          if ($file != '.' && $file != '..') $zip->addFile($exportFolder . '/xml/' . $file, 'xml/' . $file);
        }

      }else{
        return false;
      }
      
      $zip->close();

      $exportArhivePath = JURI::base().'/modules/mod_os_touchslider/export/OSSliderExport_modId_'.$moduleId.'.zip';
      return $exportArhivePath;

  }

  static function getSliderData($tableName, $moduleId){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__' . $tableName);
    if($tableName == 'os_touch_slider_text'){
      $query->where('fk_ts_id = '.$moduleId);
    }else{  
      $query->where('module_id = ' . $moduleId);
    }
    $db->setQuery($query);
    if($db->loadObjectList()) return $db->loadObjectList();
    return false;
  }



  // static function objToArr($obj){
  //     if(!is_object($obj)) return $obj;

  //     $arr = array();

  //     foreach ($obj as $key => $value) {
  //       $arr[$key] = $value;
  //     }

  //     return $arr;
  // }

//   static function fileImport($importData)
//   {
//       $moduleId = $_POST['id'];
//       $database = JFactory::getDbo();

//       $params = new JRegistry;                            //get params
//       $query = "SELECT params FROM #__os_touch_slider WHERE module_id = ".$moduleId;
//       $database->setQuery($query);
//       $moduleParams = $database->loadResult();

//       if(!empty($moduleParams)) $moduleParams = json_decode($moduleParams); //if slider not empty

//       $query = $database->getQuery(true);
//       $query->select('MAX(id)');
//       $query->from('#__os_touch_slider');
//       $database->setQuery($query);
//       $last_id = $database->loadResult();
//       $extractPathImages = JPATH_ROOT.'/images/os_touchslider_'.$moduleId;

//       $zip = new ZipArchive;
//       $extract = $zip->open($importData['tmp_name']);

//       if ($extract === TRUE) {
//           $zip->extractTo($extractPathImages);
//       }else{
//           return false;
//       }

//       $imagePath = $extractPathImages.'/xmlDB/images.xml';
//       $textPath = $extractPathImages.'/xmlDB/text.xml';
//       $indexPath = $extractPathImages.'/xmlDB/index.html';

//       if(!file_exists($imagePath) || !file_exists($textPath)) return false;

//       $objectImage = simplexml_load_file($imagePath);
//       $objectText = simplexml_load_file($textPath);      


//       $query = "SELECT params FROM #__os_touch_slider";
//       $database->setQuery($query);
//       $isEmpty = $database->loadResult();

//       if(empty($moduleParams) && empty($isEmpty)){

//           foreach ($objectImage->node as $image) {

//             $query = "INSERT INTO #__os_touch_slider VALUES(" . $database->quote($image->node[0]) . "," .$database->quote($image->node[1]). "," .$moduleId. "," .$database->quote($image->node[3]). "," .$database->quote($image->node[4]).")";
//             $database->setQuery($query);
//             $database->execute();
            
//           }

//           foreach ($objectText->node as $text) {

//             $query = "INSERT INTO #__os_touch_slider_text VALUES(null," .$moduleId. "," .$database->quote($text->node[2]). "," .$database->quote(htmlspecialchars($text->node[3])). ")";
//             $database->setQuery($query);
//             $database->execute();
//           }

//           if(file_exists($imagePath)) unlink($imagePath);
//           if(file_exists($textPath)) unlink($textPath);
//           if(file_exists($indexPath)) unlink($indexPath);
           
//           if(is_dir($extractPathImages.'/xmlDB')) rmdir($extractPathImages.'/xmlDB');

//           return true;

//       }elseif(empty($moduleParams) && !empty($isEmpty)){

//         // exit;
//           //////////////////////////////////////////////////////

//        $OldIds = $NewIds = array();

//        $image_info = $objectImage->node[0]->node[4]->__toString();
//        $image_info = json_decode($image_info);

//       foreach($objectImage->node as $image)
//       {
//           $old_id = (int)$image->node[0];
//           $newId = $image->node[0] = ++$last_id;
//           $image->node[2] = $moduleId;

//           /////////////////
//            // $image_info->textAnimation->start = (array)$image_info->textAnimation->start;
//            // $image_info->textAnimation->end = (array)$image_info->textAnimation->end;
//            // $image_info->textAnimation->permanent = (array)$image_info->textAnimation->permanent;
//            // $image_info->textAnimation->hover = (array)$image_info->textAnimation->hover;
//            // $image_info->imageFullTime = (array)$image_info->imageFullTime;
//           ////////////////////////////
//           for($i = 0;$i<(int)$newId;$i++) {
//               if(!array_key_exists($i,$image_info->textAnimation->start)) $image_info->textAnimation->start[$i]='';
//               if(!array_key_exists($i,$image_info->textAnimation->end)) $image_info->textAnimation->end[$i]='';
//               if(isset($image_info->textAnimation->permanent) && !array_key_exists($i,$image_info->textAnimation->permanent)) $image_info->textAnimation->permanent[$i]='';
//               if(isset($image_info->textAnimation->hover) && !array_key_exists($i,$image_info->textAnimation->hover)) $image_info->textAnimation->hover[$i]='';
//               if(!array_key_exists($i,$image_info->imageFullTime)) $image_info->imageFullTime[$i]='';
//           }


//           $image_info->textAnimation->start[(int)$newId] = $image_info->textAnimation->start[(int)$old_id];
//           $image_info->textAnimation->start[(int)$old_id]='';
//           $image_info->textAnimation->end[(int)$newId] = $image_info->textAnimation->end[(int)$old_id];
//           $image_info->textAnimation->end[(int)$old_id]='';
//           if(isset($image_info->textAnimation->permanent)) $image_info->textAnimation->permanent[(int)$newId] = $image_info->textAnimation->permanent[(int)$old_id];
//           if(isset($image_info->textAnimation->permanent))$image_info->textAnimation->permanent[(int)$old_id]='';
//           if(isset($image_info->textAnimation->hover))$image_info->textAnimation->hover[(int)$newId] = $image_info->textAnimation->hover[(int)$old_id];
//           if(isset($image_info->textAnimation->hover))$image_info->textAnimation->hover[(int)$old_id]='';
//           $image_info->imageFullTime[(int)$newId] = $image_info->imageFullTime[(int)$old_id];
//           $image_info->imageFullTime[(int)$old_id]='';

          
//           // $image_info = json_encode($image_info);

//           $OldIds[] = (int)$old_id;
//           $NewIds[] = (int)$newId;

//       }

//       // print_r($image);
//       // exit;
//       foreach ($objectText->node as $text) {
//          $text->node[1] = $moduleId;
//          $text->node[2] = str_replace($OldIds, $NewIds, $text->node[2]);
//       }

//       foreach ($objectImage->node as $image) {
//           $query = "INSERT INTO #__os_touch_slider VALUES (" . $database->quote($image->node[0]) . "," .$database->quote($image->node[1]). "," .$database->quote($image->node[2]). "," .$database->quote($image->node[3]). ",'')";
//           $database->setQuery($query);
//           $database->execute();
//       }


//       foreach ($objectText->node as $text) {

//           $query = "INSERT INTO #__os_touch_slider_text VALUES(null," .$database->quote($text->node[1]). "," .$database->quote($text->node[2]). "," .$database->quote(htmlspecialchars($text->node[3])). ")";
//           $database->setQuery($query);
//           $database->execute();
//       }


//         $image_info = json_encode($image_info);

//         //params save
//         $query = "UPDATE #__os_touch_slider SET params = ".$database->quote($image_info)." WHERE module_id = ".$moduleId;
//         $database->setQuery($query);
//         $database->query();
//         //params save


//        if(file_exists($imagePath)) unlink($imagePath);
//        if(file_exists($textPath)) unlink($textPath);
//        if(file_exists($textPath)) unlink($textPath);    
//        if(is_dir($extractPathImages.'/xmlDB')) rmdir($extractPathImages.'/xmlDB');

//        return true;

//         ////////////////////////////////////////////////////////
//       }

//       $OldIds = $NewIds = array();

//       $image_info = $objectImage->node[0]->node[4]->__toString();
//        $image_info = json_decode($image_info);


//       foreach($objectImage->node as $image){

//         $old_id = (int)$image->node[0];
//         $newId = $image->node[0] = ++$last_id;
//         $image->node[2] = $moduleId;

//         // $image_info = $image->node[4]->__toString();
//         // $image_info = json_decode($image_info);
        
//         if(!empty($moduleParams)){

//           if(isset($image_info->textAnimation->start)) $image_info->textAnimation->start = self::objToArr($image_info->textAnimation->start);
//           if(isset($image_info->textAnimation->end)) $image_info->textAnimation->end = self::objToArr($image_info->textAnimation->end);
//           if(isset($image_info->textAnimation->permanent)) $image_info->textAnimation->permanent = self::objToArr($image_info->textAnimation->permanent);
//           if(isset($image_info->textAnimation->hover)) $image_info->textAnimation->hover = self::objToArr($image_info->textAnimation->hover);
//           if(isset($image_info->imageFullTime)) $image_info->imageFullTime = self::objToArr($image_info->imageFullTime);


//           if(isset($moduleParams->textAnimation->start)) $moduleParams->textAnimation->start = self::objToArr($moduleParams->textAnimation->start);
//           if(isset($moduleParams->textAnimation->end)) $moduleParams->textAnimation->end = self::objToArr($moduleParams->textAnimation->end);
//           if(isset($moduleParams->textAnimation->permanent)) $moduleParams->textAnimation->permanent = self::objToArr($moduleParams->textAnimation->permanent);
//           if(isset($moduleParams->textAnimation->hover)) $moduleParams->textAnimation->hover = self::objToArr($moduleParams->textAnimation->hover);
//           if(isset($moduleParams->imageFullTime))$moduleParams->imageFullTime = self::objToArr($moduleParams->imageFullTime);


//           for($i = 0;$i<(int)$newId;$i++) {

//               if(isset($moduleParams->textAnimation->start) && !array_key_exists($i,$moduleParams->textAnimation->start)) $moduleParams->textAnimation->start[$i]='';
//               if(isset($moduleParams->textAnimation->end) && !array_key_exists($i,$moduleParams->textAnimation->end)) $moduleParams->textAnimation->end[$i]='';
//                if(isset($moduleParams->textAnimation->permanent) && !array_key_exists($i,$moduleParams->textAnimation->permanent)) $moduleParams->textAnimation->permanent[$i]='';
//               if(isset($moduleParams->textAnimation->hover) && !array_key_exists($i,$moduleParams->textAnimation->hover)) $moduleParams->textAnimation->hover[$i]='';
//               if(isset($moduleParams->imageFullTime) && !array_key_exists($i,$moduleParams->imageFullTime)) $moduleParams->imageFullTime[$i]='';
//           }

//           if(isset($moduleParams->textAnimation->start) && isset($image_info->textAnimation->start))
//             @$moduleParams->textAnimation->start[(int)$newId] = $image_info->textAnimation->start[(int)$old_id];
      
//           if(isset($moduleParams->textAnimation->end) && isset($image_info->textAnimation->end)) 
//             @$moduleParams->textAnimation->end[(int)$newId] = $image_info->textAnimation->end[(int)$old_id];

//           if(isset($moduleParams->textAnimation->permanent) && isset($image_info->textAnimation->permanent))
//             @$moduleParams->textAnimation->permanent[(int)$newId] = $image_info->textAnimation->permanent[(int)$old_id];
      
//           if(isset($moduleParams->textAnimation->hover) && isset($image_info->textAnimation->hover)) 
//             @$moduleParams->textAnimation->hover[(int)$newId] = $image_info->textAnimation->hover[(int)$old_id];

//           if(isset($moduleParams->imageFullTime) && isset($image_info->imageFullTime)) 
//             @$moduleParams->imageFullTime[(int)$newId] = $image_info->imageFullTime[(int)$old_id];

//           if(isset($moduleParams->imageOrdering)) 
//             @$moduleParams->imageOrdering[] = (int)$newId;

//         }

//         // $image->node[4] = json_encode($image_info);

//         $OldIds[] = (int)$old_id;
//         $NewIds[] = (int)$newId;
//       }

// // exit;

//       if(!empty($moduleParams)){
//           $moduleParams = (array)$moduleParams;
//       }
         
//       // exit;
//       foreach ($objectText->node as $text) {
//          $text->node[1] = $moduleId;
//          $text->node[2] = str_replace($OldIds, $NewIds, $text->node[2]);
//       }

//       foreach ($objectImage->node as $image) {

//           $query = "INSERT INTO #__os_touch_slider VALUES(" . $database->quote($image->node[0]) . "," .$database->quote($image->node[1]). "," .$moduleId. "," .$database->quote($image->node[3]).",'')";
//           $database->setQuery($query);
//           $database->execute();
//       }

//       foreach ($objectText->node as $text) {

//           $query = "INSERT INTO #__os_touch_slider_text VALUES(null," .$moduleId. "," .$database->quote($text->node[2]). "," .$database->quote(htmlspecialchars($text->node[3])). ")";
//           $database->setQuery($query);
//           $database->execute();
//       }

//         // $moduleParams['width_px'] = '800';
//         // $moduleParams['height_px'] = '400';

//         $moduleParams = json_encode($moduleParams);

//         //params save
//         $query = "UPDATE #__os_touch_slider SET params = ".$database->quote($moduleParams)." WHERE module_id = ".$moduleId;
//         $database->setQuery($query);
//         $database->query();
//         //params save


//        if(file_exists($imagePath)) unlink($imagePath);
//        if(file_exists($textPath)) unlink($textPath); 
//        if(file_exists($indexPath)) unlink($indexPath); 
//        if(is_dir($extractPathImages.'/xmlDB')) rmdir($extractPathImages.'/xmlDB');

//        return true;

//   }

//   static function fileExport()
//   {
       
//           $nameArhive = false;

//           $input  = JFactory::getApplication()->input;
//           $moduleId = $input->getInt('moduleId',0);
//           $pathToImportExport = JPATH_ROOT.'/modules/mod_os_touchslider/importexport';
//           $pathToImages = JPATH_ROOT.'/images';
//           $pathToImagesSlider = $pathToImages.'/os_touchslider_'.$moduleId;

//           $db = JFactory::getDbo();
//           $query = $db->getQuery(true);
//           $query->select('*');
//           $query->from('#__os_touch_slider');
//           $query->where('module_id = '.$moduleId);
//           $db->setQuery($query);
//           $imageExportObject = $db->loadObjectList();

//           $query = $db->getQuery(true);
//           $query->select('*');
//           $query->from('#__os_touch_slider_text');
//           $query->where('fk_ts_id = '.$moduleId);
//           $db->setQuery($query);
//           $textExportObject = $db->loadObjectList();

//           $jRegXml = new JRegistryFormatXml();

//           $imageExportXml = $jRegXml->objectToString($imageExportObject);
//           $textExportXml = $jRegXml->objectToString($textExportObject);

//           if(!is_dir($pathToImportExport.'/xmlDB')) mkdir($pathToImportExport.'/xmlDB') ; 
//           file_put_contents($pathToImportExport.'/xmlDB/images.xml', $imageExportXml);
//           file_put_contents($pathToImportExport.'/xmlDB/text.xml', $textExportXml);


//           $pathdir = $pathToImagesSlider.'/original/'; // путь к папке, файлы которой будем архивировать
//           $nameArhive = $pathToImportExport.'/export_OS_Slider.zip'; //название архива
//           $zip = new ZipArchive; // класс для работы с архивами

//           $filelistXml = scandir($pathToImportExport.'/xmlDB');
//           $filelistOrig = scandir($pathToImagesSlider.'/original');
//           $filelistThumb = scandir($pathToImagesSlider.'/thumbnail');
//           // $filelistSlideShow = scandir($pathToImagesSlider.'/slideshow');

//           if(file_exists($nameArhive)) unlink($nameArhive);

//           if ($zip->open($nameArhive, ZipArchive::CREATE) === TRUE){ // создаем архив, если все прошло удачно продолжаем

//               foreach($filelistOrig as $file){ // перебираем все файлы из нашей папки
//                       if ($file != '.' && $file != '..'){ // проверяем файл ли мы взяли из папки
//                           $zip->addFile($pathToImagesSlider.'/original/'.$file, '/original/'.$file); // и архивируем           
//                       }
//               }

//               foreach($filelistThumb as $file){ // перебираем все файлы из нашей папки
//                       if ($file != '.' && $file != '..'){ // проверяем файл ли мы взяли из папки
//                           $zip->addFile($pathToImagesSlider.'/thumbnail/'.$file, '/thumbnail/'.$file); // и архивируем           
//                       }
//               }

//               // foreach($filelistSlideShow as $file){ // перебираем все файлы из нашей папки
//               //         if ($file != '.' && $file != '..'){ // проверяем файл ли мы взяли из папки
//               //             $zip->addFile($pathToImagesSlider.'/slideshow/'.$file, '/slideshow/'.$file); // и архивируем           
//               //         }
//               // }

//               $zip->addEmptyDir('slideshow');

//               foreach($filelistXml as $file){ // перебираем все файлы из нашей папки
//                       if ($file != '.' && $file != '..'){ // проверяем файл ли мы взяли из папки
//                           $zip->addFile($pathToImportExport.'/xmlDB/'.$file, '/xmlDB/'.$file); // и архивируем           
//                       }
//               }
//           }else{
//             return false;
//           }

//           $zip->close();
//           $nameArhive = JURI::base().'/modules/mod_os_touchslider/importexport/export_OS_Slider.zip';
          
//             return $nameArhive;
//   }


}