<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class osGalleryHelperAdmin{

    static function displayDefault(){
        $document = JFactory::getDocument();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();

        $query = "SELECT * FROM #__os_gallery ORDER BY id DESC";
        $db->setQuery($query);
        $galleries = $db->loadObjectList();

        //get gallery version
        $xml = @simplexml_load_file(JPATH_BASE . "/components/com_osgallery/osgallery.xml");
        if($xml){
            $galV = (string)$xml->version;
            $creationDate = (string)$xml->creationDate;
            unset($xml);

            //check update
            $url="http://ordasoft.com/xml_update/osgallery.xml";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);

            $data = curl_exec($ch);
            curl_close($ch);

            $xml = simplexml_load_string($data);
            $updateArticleUrl = '#';
            $galVArr = explode(".", $galV);
            $avaibleUpdate = false;
            if($xml && isset($xml->version)){
                $ordasoftGalV = (string)$xml->version;
                $ordasoftGalVArr = explode(".", $ordasoftGalV);
                $ordasoftCreationDate = (string)$xml->creationDate;
                $updateArticleUrl = (string)$xml->updateArticleUrl;
                unset($xml);
                foreach ($galVArr as $k => $galSubV) {
                    if(isset($ordasoftGalVArr[$k])){
                        if((int)$ordasoftGalVArr[$k] < (int)$galSubV){
                            break;
                        }
                        if((int)$ordasoftGalVArr[$k] > (int)$galSubV){
                            $avaibleUpdate = true;
                            break;
                        }
                    }
                }
            }
            if(!self::checkEnableUpdate()) $avaibleUpdate = false; 
        }

        $title = '<span class="title-block os-gallery-title-text">'.JText::_("COM_OSGALLERY_LIST_VIEW_TITLE").'</span>';
        JToolBarHelper::title($title, 'osgallery-title-image');

        if ($user->authorise('core.create', 'com_osgallery')) {
            JToolBarHelper::addNew('new_gallery');
        }
        if (JFactory::getUser()->authorise('core.duplicate', 'com_osgallery')) {
            JToolBarHelper::custom('clone_gallery', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
        if ($user->authorise('core.edit.state', 'com_osgallery')) {
            JToolbarHelper::publish('publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }
        if ($user->authorise('core.delete', 'com_osgallery')) {
            JToolBarHelper::deleteList('', 'delete_gallery');
        }
        $update = 'unavaible';
        if($avaibleUpdate)$update = 'avaible';
        JToolBarHelper::custom('about_gallery', 'gallery-update-'.$update, '', 'About', false);
        if ($user->authorise('core.admin', 'com_osgallery') || $user->authorise('core.options', 'com_osgallery')) {
            JToolBarHelper::preferences('com_osgallery');
        }


        require self::findView('galleryList');
    }

    static function displayGallery($galId){
        $document = JFactory::getDocument();
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $params = new JRegistry;
        $images = array();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/fine-uploader-new.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/jquery-ui.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/jquery.slider.minicolors.css");

        //include needed script
        $document->addScript(JURI::base() . "components/com_osgallery/assets/js/fine-uploader.js");
        // $document->addScript(JURI::base() . "components/com_osgallery/assets/js/jquery.json.js");

        if($galId){
            $query = "SELECT title FROM #__os_gallery WHERE id=$galId";
            $db->setQuery($query);
            $galeryTitle = $db->loadResult();

            //get categories
            $query = "SELECT * FROM #__os_gallery_categories".
                    "\n WHERE fk_gal_id=$galId".
                    "\n ORDER BY ordering ASC";
            $db->setQuery($query);
            $categories =$db->loadObjectList();
            //end

            //get nextCat
            $query = "SELECT max(id) FROM #__os_gallery_categories";
            $db->setQuery($query);
            $activeIndex = $db->loadResult();
            
            //get category params
            $query = "SELECT DISTINCT id,params FROM #__os_gallery_categories".
                "\n WHERE fk_gal_id=".$galId;
            $db->setQuery($query);
            $catParamsArray = $db->loadObjectList('id');

            //getting Images
            $query = "SELECT gim.* , gc.fk_cat_id, cat.name as cat_name FROM #__os_gallery_img as gim ".
                    "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                    "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                    "\n WHERE cat.fk_gal_id=$galId".
                    "\n ORDER BY cat.ordering ASC";
            $db->setQuery($query);
            $result =$db->loadObjectList();
            if($result){
                foreach ($result as $image) {
                    if(!isset($images[$image->fk_cat_id])){
                       $images[$image->fk_cat_id] = array();
                    }
                    $images[$image->fk_cat_id][] = $image;
                }
                //ordering images
                function sortByOrdering($a,$b) {
                    return $a->ordering>$b->ordering;
                }
                foreach ($images as $key => $imageArr) {
                    usort($imageArr, "sortByOrdering");
                    $images[$key] = $imageArr;
                }
                //get image params
                $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                    "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                    "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                    "\n WHERE cat.fk_gal_id=".$galId;
                $db->setQuery($query);
                $imgParamsArray = $db->loadObjectList('id');
            }
        }else{
            //crate new galery // get galerry id
            $query = "INSERT INTO #__os_gallery VALUES ()";
            $db->setQuery($query);
            $db->query();
            $galId = $db->insertid();

            $query = "INSERT INTO #__os_gallery_categories(fk_gal_id,name,params) VALUES ($galId,'Category Title','{}')";
            $db->setQuery($query);
            $db->query();

            //need for f5reload page without bugs//we go to else above if new gallery
            $app->redirect('index.php?option=com_osgallery&task=edit_gallery&galId='.$galId);
        }

        // print_r($imgParamsArray);exit;
        //load params
        $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
        $db->setQuery($query);
        $paramsString = $db->loadResult();

        $params->loadString($paramsString);
        $fancy_box_background = $params->get("fancy_box_background","gray");
        $open_close_effect = $params->get("open_close_effect","none");
        $click_close = $params->get("click_close",1);
        $helper_buttons = $params->get("helper_buttons",0);
        $helper_thumbnail = $params->get("helper_thumbnail",0);
        $loop = $params->get("loop",0);
        $open_close_speed = $params->get("open_close_speed",500);
        $prev_next_effect = $params->get("prev_next_effect","none");
        $prev_next_speed = $params->get("prev_next_speed",500);
        $img_title = $params->get("img_title","inside");
        $thumbnail_width = $params->get("thumbnail_width",50);
        $thumbnail_height = $params->get("thumbnail_height",50);
        $os_fancybox_arrows = $params->get("os_fancybox_arrows",1);
        $os_fancybox_arrows_pos = $params->get("os_fancybox_arrows_pos",1);
        $close_button = $params->get("close_button",1);
        $next_click = $params->get("next_click",0);
        $mouse_wheel = $params->get("mouse_wheel",0);
        $os_fancybox_autoplay = $params->get("os_fancybox_autoplay",0);
        $autoplay_speed = $params->get("autoplay_speed",3000);

        $watermark_position = $params->get("watermark_position","center");
        $watermark_opacity = $params->get("watermark_opacity",30);
        $watermark_size = $params->get("watermark_size",20);
        $watermark_file = $params->get("watermark_file","");
        $watermark_enable = $params->get("watermark_enable",0);
        $watermark_type = $params->get("watermark_type",1);
        $watermark_text = $params->get("watermark_text",'');
        $watermark_text_size = $params->get("watermark_text_size",17);
        $watermark_text_color = $params->get("watermark_text_color",'rgb(0, 0, 0)');
        $watermark_text_angle = $params->get("watermark_text_angle",0);
        $exist_watermark_text = $params->get("exist_watermark_text",'');

        $facebook_enable = $params->get("facebook_enable",0);
        $googleplus_enable = $params->get("googleplus_enable",0);
        $vkontacte_enable = $params->get("vkontacte_enable",0);
        $odnoklassniki_enable = $params->get("odnoklassniki_enable",0);
        $twitter_enable = $params->get("twitter_enable",0);
        $pinterest_enable = $params->get("pinterest_enable",0);
        $linkedin_enable = $params->get("linkedin_enable",0);

        $imageMargin = $params->get("image_margin",5);
        $numColumn = $params->get("num_column",3);
        $gallerylayout = $params->get("galleryLayout","defaultTabs");
        $masonryLayout = $params->get("masonryLayout","");
        $imagehover = $params->get("imageHover","none");
        $minImgEnable = $params->get("minImgEnable",1);
        $minImgSize = $params->get("minImgSize",200);
        $imgWidth = $params->get("imgWidth",600);
        $imgHeight = $params->get("imgHeight",400);
        $showLoadMore = $params->get("showLoadMore",0);
        $showDownload = $params->get("showDownload",0);
        $showImgAlias = $params->get("showImgAlias",0);
        $numberImages = $params->get("number_images",5);
        $loadMoreButtonText = $params->get("loadMoreButtonText",JText::_("COM_OSGALLERY_SETTINGS_GENERAL_LOADMORE_BUTTON_TEXT"));
        $load_more_background = $params->get("load_more_background",'#12BBC5');

        $backButtonText = $params->get("backButtonText",JText::_("COM_OSGALLERY_SETTINGS_GENERAL_BACK_BUTTON_TEXT"));
        $title = '<span class="title-block input-block"><input id="gallery-title" type="text" placeholder="'.JText::_("COM_OSGALLERY_TITLE_LABEL").'" value="'.$galeryTitle.'" oninput="jQuery(\'#hidden-title\').val(jQuery(this).val())"></span>';
        JToolBarHelper::title($title, 'osgallery-title-image');
        if (JFactory::getUser()->authorise('core.edit.state', 'com_osgallery')) {
            JToolBarHelper::save('save_close_galery');
            JToolBarHelper::apply('save_gallery', 'JTOOLBAR_APPLY');
            JToolBarHelper::cancel('close_gallery', 'JTOOLBAR_CLOSE');
            JToolBarHelper::custom('open_gallery_settings', 'options.png', 'options.png', 'Gallery Settings', false);
        }

        require self::findView('gallery');
    }

    static function saveGallery($close){
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $input  = $app->input;

        $categoryNames = $input->get("category_names", '', 'ARRAY');
        $galId = $input->get("galId", 0, 'INT');
        $galleryTitle = $input->get("gallery_title", '', 'STRING');
        if($galId){
            //saving gallery params
            $params = new JRegistry;
            $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
            $db->setQuery($query);
            $paramsString = $db->loadResult();
            $params->loadString($paramsString);

            $params->set("fancy_box_background", $input->get("fancy_box_background","gray","STRING"));
            $params->set("open_close_effect", $input->get("open_close_effect","none","STRING"));
            $params->set("click_close", $input->get("click_close",1,"INT"));
            $params->set("helper_buttons", $input->get("helper_buttons",0,"INT"));
            $params->set("helper_thumbnail", $input->get("helper_thumbnail",0,"INT"));
            $params->set("loop", $input->get("loop",0,"INT"));
            $params->set("open_close_speed", $input->get("open_close_speed",500,"INT"));
            $params->set("prev_next_effect", $input->get("prev_next_effect","none","STRING"));
            $params->set("prev_next_speed", $input->get("prev_next_speed",500,"INT"));
            $params->set("img_title", $input->get("img_title","inside","STRING"));
            $params->set("thumbnail_width", $input->get("thumbnail_width",50,"INT"));
            $params->set("thumbnail_height", $input->get("thumbnail_height",50,"INT"));
            $params->set("os_fancybox_arrows", $input->get("os_fancybox_arrows",1,"INT"));
            $params->set("os_fancybox_arrows_pos", $input->get("os_fancybox_arrows_pos",1,"INT"));
            $params->set("close_button", $input->get("close_button",1,"INT"));
            $params->set("next_click", $input->get("next_click",0,"INT"));
            $params->set("mouse_wheel", $input->get("mouse_wheel",0,"INT"));
            $params->set("os_fancybox_autoplay", $input->get("os_fancybox_autoplay",0,"INT"));
            $params->set("autoplay_speed", $input->get("autoplay_speed",3000,"INT"));
            $params->set("imageHover", $input->get("imageHover",'dimas',"STRING"));
            $watermarkRedraw = false;
            if($params->get("watermark_opacity",30) != $input->get("watermark_opacity",30,"INT")
                || $params->get("watermark_size",20) != $input->get("watermark_size",20,"INT")
                || $params->get("watermark_position","center") != $input->get("watermark_position",'center',"STRING")
                ||$params->get("watermark_position","center") != $input->get("watermark_position_selected",'center',"STRING")
                || $params->get("watermark_type",1) != $input->get("watermark_type",1,"INT")){
                $watermarkRedraw = true;
            }
            $watermarkRedrawText = false;
            if($params->get("watermark_text_color",'rgb(0, 0, 0)') != $input->get("watermark_text_color",'rgb(0, 0, 0)',"STRING")
                || $params->get("watermark_text_size",17) != $input->get("watermark_text_size",17,"INT")
                || $params->get("watermark_text_angle",0) != $input->get("watermark_text_angle",0,"INT")
                || $params->get("watermark_position","center") != $input->get("watermark_position",'center',"STRING")
                || $params->get("watermark_position","center") != $input->get("watermark_position_selected",'center',"STRING")
                || $params->get("watermark_type",1) != $input->get("watermark_type",1,"INT")){
                $watermarkRedrawText = true;
            }
            $params->set("watermark_position", $input->get("watermark_position",'center',"STRING"));
            $params->set("watermark_opacity", $input->get("watermark_opacity",30,"INT"));
            $params->set("watermark_size", $input->get("watermark_size",20,"INT"));
            $params->set("watermark_enable", $input->get("watermark_enable",0,"INT"));
            $params->set("watermark_type", $input->get("watermark_type",1,"INT"));
            $params->set("watermark_text", $input->get("watermark_text",'',"STRING"));
            $params->set("watermark_text_color", $input->get("watermark_text_color",'rgb(0, 0, 0)',"STRING"));
            $params->set("watermark_text_size", $input->get("watermark_text_size",17,"INT"));
            $params->set("watermark_text_angle", $input->get("watermark_text_angle",0,"INT"));
            $params->set("exist_watermark_text", $input->get("exist_watermark_text",'',"STRING"));
            $params->set("watermark_file", $input->get("exist_watermark_file",'',"STRING"));

            $params->set("backButtonText", $input->get("backButtonText",JText::_("COM_OSGALLERY_SETTINGS_GENERAL_BACK_BUTTON_TEXT"),"STRING"));
            $params->set("image_margin", $input->get("image_margin",5,"INT"));
            $params->set("num_column", $input->get("num_column",3,"INT"));
            $params->set("galleryLayout", $input->get("galleryLayout","defaultTabs","STRING"));
            $params->set("masonryLayout", $input->get("masonryLayout","","STRING"));
            $params->set("imageHover", $input->get("imageHover","julia","STRING"));
            $params->set("minImgEnable", $input->get("minImgEnable",1,"INT"));
            $params->set("minImgSize", $input->get("minImgSize",200,"INT"));
            $params->set("imgWidth", $input->get("imgWidth",600,"INT"));
            $params->set("imgHeight", $input->get("imgHeight",400,"INT"));
            $params->set("showLoadMore", $input->get("showLoadMore",0,"INT"));
            $params->set("showDownload", $input->get("showDownload",0,"INT"));
            $params->set("showImgAlias", $input->get("showImgAlias",0,"INT"));
            $params->set("number_images", $input->get("number_images",5,"INT"));
            $params->set("loadMoreButtonText", $input->get("loadMoreButtonText",JText::_("COM_OSGALLERY_SETTINGS_GENERAL_LOADMORE_BUTTON_TEXT"),"STRING"));
            $params->set("load_more_background", $input->get("load_more_background",'#12BBC5',"STRING"));

            $params->set("facebook_enable", $input->get("facebook_enable",0,"INT"));
            $params->set("googleplus_enable", $input->get("googleplus_enable",0,"INT"));
            $params->set("vkontacte_enable", $input->get("vkontacte_enable",0,"INT"));
            $params->set("odnoklassniki_enable", $input->get("odnoklassniki_enable",0,"INT"));
            $params->set("twitter_enable", $input->get("twitter_enable",0,"INT"));
            $params->set("pinterest_enable", $input->get("pinterest_enable",0,"INT"));
            $params->set("linkedin_enable", $input->get("linkedin_enable",0,"INT"));

            if($params->get("watermark_enable")){
                //img watermark
                $pathOrg = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/original/';
                $pathWat = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/original/watermark/';
                if($params->get("watermark_type")== 1){
                    if($watermarkRedraw || (isset($_FILES['watermark_file']) && $_FILES['watermark_file']["size"] > 0
                        && ($_FILES['watermark_file']['name'] != $input->get("exist_watermark_file",'',"STRING")))){
                        $uploaddir = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/original/watermark/';
                        if (!file_exists($uploaddir) || !is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
                        $uploaddir = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/watermark/';
                        if (!file_exists($uploaddir) || !is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
                        if (isset($_FILES['watermark_file']['tmp_name']) && !empty($_FILES['watermark_file']['tmp_name']) && !copy($_FILES['watermark_file']['tmp_name'], $uploaddir.$_FILES['watermark_file']['name'])) {
                            $app->enqueueMessage(JText::_('COM_OSGALLERY_WATERMARK_UPLOAD_ERROR'), 'error');
                        }else{
                            if($_FILES['watermark_file']['name'] != ''){
                                $params->set("watermark_file", $_FILES['watermark_file']['name']);
                            }
                            $files = scandir($pathOrg);
                            foreach($files as $file) {
                                if(strlen($file) > 10){
                                    self::createWaterMark($file, $galId, $params);
                                }
                            }
                        }
                    }
                    $filesOriginal = scandir($pathOrg);
                    $filesWatermark = scandir($pathWat);
                    $diffFile = array_diff($filesOriginal, $filesWatermark);
                    foreach($diffFile as $file) {
                        if(strlen($file) > 10){
                            // print_r($file);exit;
                            self::createWaterMark($file, $galId, $params);
                        }
                    }
                }else{
                    if($watermarkRedrawText || ($input->get("watermark_text",'',"STRING") != $params->get("exist_watermark_text"))){
                        //text watermark
                        $filesOriginal = scandir($pathOrg);
                        foreach($filesOriginal as $file) {
                            if(strlen($file) > 10){
                                self::createWaterMark($file, $galId, $params);
                            }
                        }
                        $params->set("exist_watermark_text", $input->get("watermark_text",'',"STRING"));
                    }else{
                        $filesOriginal = scandir($pathOrg);
                        $filesWatermark = scandir($pathWat);
                        $diffFile = array_diff($filesOriginal, $filesWatermark);
                        foreach($diffFile as $file) {
                            if(strlen($file) > 10){
                                self::createWaterMark($file, $galId, $params);
                            }
                        }
                    }
                }
            }

            /** Add masonry **/
            $dir = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId;
            if($params->get("galleryLayout") == "masonry") {
                if (!file_exists($dir . '/thumbnail_masonry') || !is_dir($dir)) mkdir($dir . '/thumbnail_masonry', 0755, true);
                if (!file_exists($dir . '/thumbnail_masonry/default') || !is_dir($dir)) mkdir($dir . '/thumbnail_masonry/default', 0755, true);
                if (!file_exists($dir . '/thumbnail_masonry/horizontal') || !is_dir($dir)) mkdir($dir . '/thumbnail_masonry/horizontal', 0755, true);
                if (!file_exists($dir . '/thumbnail_masonry/vertical') || !is_dir($dir)) mkdir($dir . '/thumbnail_masonry/vertical', 0755, true);      
                self::createImageThumbnailForMasonry($params->get("masonryLayout"));
            }
            if($params->get("galleryLayout") == "fit_rows") {
                if (!file_exists($dir . '/thumbnail_fitrows') || !is_dir($dir)) mkdir($dir . '/thumbnail_fitrows', 0755, true);
                self::createImageThumbnailForMasonry($params->get("galleryLayout"));
            }

            /*****************/ 

            if($galleryTitle){
                $query = "UPDATE #__os_gallery SET title=".$db->quote($galleryTitle).
                                                    ",params=".$db->Quote($params->toString()).
                        "\n WHERE id=$galId";
                $db->setQuery($query);
                $db->query();
            }
            if($categoryNames){
                foreach($categoryNames as $catName){
                    $catName = explode('|+|', $catName);
                    if(!empty($catName[0]) && !empty($catName[1])){
                        $catId = $catName[0];
                        $catName = $catName[1];
                        $query = "SELECT id FROM #__os_gallery_categories WHERE id=".$catId;
                        $db->setQuery($query);
                        $existCat = $db->loadResult();
                        if($existCat){
                            $query = "UPDATE #__os_gallery_categories SET name=".$db->Quote($catName)." WHERE id=".$catId;
                            $db->setQuery($query);
                            $db->query();
                        }else{
                            $query = "INSERT INTO #__os_gallery_categories(id,fk_gal_id,name)"
                                    ."\n VALUES($catId, $galId,".$db->Quote($catName).")";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                }
            }

            //cat ordering
            $catOrderString = $input->get("catOrderIds", '', 'STRING');
            $catParamsArr = $input->get("catSettings",array(),"ARRAY");
            if($catOrderString){
                $orderArray = explode(',', str_replace('order-id-', '', $catOrderString));
                if(isset($orderArray[0])){
                    foreach ($orderArray as $order => $catId) {
                        $query = "UPDATE #__os_gallery_categories SET ordering=".$order.",".
                                "\n params=".$db->Quote($catParamsArr[$catId])." WHERE id=".$catId;
                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }

            //img ordering
            $imgOrderArrString = $input->get("imageOrdering", '', 'ARRAY');
            $imgParamsArr = $input->get("imgSettings",array(),"ARRAY");
            // print_r($imgParamsArr);exit;
            if(count($imgOrderArrString)){
                foreach ($imgOrderArrString as $catId => $imgIdStr) {
                    if($imgIdStr){
                        $imgIds = explode(',', str_replace('img-', '', $imgIdStr));
                        foreach ($imgIds as $imgOrder => $imdId) {
                            $query = "UPDATE #__os_gallery_img SET ordering=".$imgOrder.",".
                                    "\n params=".$db->Quote($imgParamsArr[$imdId])." WHERE id=".$imdId;
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                }
            }

            $deletedImgIds = $input->get("deletedImgIds", '', 'ARRAY');
            
            // add crop images
            $imgWidth = $input->get("imgWidth",600,"INT");
            $imgHeight = $input->get("imgHeight",400,"INT");

            if ( !isset($deletedImgIds[0])) {
                self::cropImages($galId, $params->get("galleryLayout"), $imgWidth, $imgHeight);
            }
            // end crop

            if(isset($deletedImgIds[0])){
                foreach ($deletedImgIds as $delImgId) {
                    $query = "SELECT gim.file_name FROM #__os_gallery_img as gim ".
                            "\n WHERE id=$delImgId";
                    $db->setQuery($query);
                    $imgForDelete = $db->loadResult();

                    $imageFolderPath = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId;
                    //delete original
                    if(file_exists($imageFolderPath.'/original/'.$imgForDelete)){
                       unlink($imageFolderPath.'/original/'.$imgForDelete);
                    }
                    //delete watermark image
                    if(file_exists($imageFolderPath.'/original/watermark/'.$imgForDelete)){
                       unlink($imageFolderPath.'/original/watermark/'.$imgForDelete);
                    }
                    //delete thumbnail
                    if(file_exists($imageFolderPath.'/thumbnail/'.$imgForDelete.'_600_400_1')){
                        unlink($imageFolderPath.'/thumbnail/'.$imgForDelete.'_600_400_1');
                    }
                    //delete thumbnail
                    if(file_exists($imageFolderPath.'/thumbnail/'.$imgForDelete)){
                        unlink($imageFolderPath.'/thumbnail/'.$imgForDelete);
                    }
                    // delete masonry default
                    if(file_exists($imageFolderPath.'/thumbnail_masonry/default/'.$imgForDelete)){
                       unlink($imageFolderPath.'/thumbnail_masonry/default/'.$imgForDelete);
                    }
                    // delete masonry horizontal
                    if(file_exists($imageFolderPath.'/thumbnail_masonry/horizontal/'.$imgForDelete)){
                       unlink($imageFolderPath.'/thumbnail_masonry/horizontal/'.$imgForDelete);
                    }
                    // delete masonry vertical
                    if(file_exists($imageFolderPath.'/thumbnail_masonry/vertical/'.$imgForDelete)){
                       unlink($imageFolderPath.'/thumbnail_masonry/vertical/'.$imgForDelete);
                    }
                    // delete fit_rows
                    if(file_exists($imageFolderPath.'/thumbnail_fitrows/'.$imgForDelete)){
                       unlink($imageFolderPath.'/thumbnail_fitrows/'.$imgForDelete);
                    }
                    
                    $query = "DELETE FROM #__os_gallery_connect".
                            "\n WHERE fk_gal_img_id=$delImgId";
                    $db->setQuery($query);
                    $db->query();

                    $query = "DELETE FROM #__os_gallery_img".
                            "\n WHERE id=$delImgId";
                    $db->setQuery($query);
                    $db->query();
                }
            }

            $deletedCatIds = $input->get("deletedCatIds", '', 'ARRAY');
            if(isset($deletedCatIds[0])){
                foreach ($deletedCatIds as $delCatId) {
                    //get images name for delete
                    $query = "SELECT gim.file_name FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gc.fk_gal_img_id=gim.id".
                            "\n WHERE gc.fk_cat_id=$delCatId";
                    $db->setQuery($query);
                    $imgForDelete = $db->loadColumn();

                    //delete inages from folder
                    foreach ($imgForDelete as $imgLink) {
                        $imageFolderPath = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId;
                        //delete original
                        if(file_exists($imageFolderPath.'/original/'.$imgLink)){
                           unlink($imageFolderPath.'/original/'.$imgLink);
                        }
                        //delete watermark image
                        if(file_exists($imageFolderPath.'/original/watermark/'.$imgLink)){
                           unlink($imageFolderPath.'/original/watermark/'.$imgLink);
                        }
                        //delete thumbnail
                        if(file_exists($imageFolderPath.'/thumbnail/'.$imgLink.'_600_400_1')){
                            unlink($imageFolderPath.'/thumbnail/'.$imgLink.'_600_400_1');
                        }
                    }

                    //delete img from db
                    $query = "DELETE gim FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gc.fk_gal_img_id=gim.id".
                                "\n WHERE gc.fk_cat_id=$delCatId";
                    $db->setQuery($query);
                    $db->query();

                    //delete img from connect table
                    $query = "DELETE FROM #__os_gallery_connect WHERE fk_cat_id=$delCatId";
                    $db->setQuery($query);
                    $db->query();

                    //delete img cat from db
                    $query = "DELETE FROM #__os_gallery_categories WHERE id=$delCatId";
                    $db->setQuery($query);
                    $db->query();
                }
            }
            if($close){
                $app->enqueueMessage('Gallery successfully saved.');
            }
            else{
                $response = array('success' => true, 'message' => 'Gallery successfully saved.', 'params' =>$params->toString());
                echo json_encode($response);
            }
        }else{
            if($close){
                $app->enqueueMessage('Empty gallery can\'t be saved.', 'error');
            }
            else{
                $response = array('success' => false, 'message' => 'Empty gallery can\'t be saved.');
                echo json_encode($response);
            }
        }

        if($close)
            $app->redirect('index.php?option=com_osgallery');
    }

    static function cloneGallery($galId, $withImage){
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $input  = $app->input;

        if(isset($galId[0])){
            if($withImage){
                $query = "SELECT title, published, params FROM #__os_gallery"
                        ."\n WHERE id = $galId[0]";
                $db->setQuery($query);
                $oldGalData = $db->loadObjectList();
                $oldGalData = $oldGalData[0];

                $query = "INSERT INTO #__os_gallery(title, published, params)"
                        ."\n VALUES('".$oldGalData->title.'(COPY)'."','".$oldGalData->published."','".$oldGalData->params."' )";
                $db->setQuery($query);
                $db->query();
                $newGalleryId = $db->insertid();

                $query = "SELECT * FROM #__os_gallery_categories WHERE fk_gal_id=$galId[0]";
                $db->setQuery($query);
                $result = $db->loadObjectList();
                if(count($result)){
                    foreach ($result as $insertData) {
                        $query = "INSERT INTO #__os_gallery_categories(name, fk_gal_id, ordering, params)"
                                ."\n VALUES('".$insertData->name."',$newGalleryId,"
                                ."\n '".$insertData->ordering."','".$insertData->params."')";
                        $db->setQuery($query);
                        $db->query();
                        $newCatId = $db->insertid();

                        $query = "SELECT galImg.file_name, galImg.src, galImg.ordering, galImg.params"
                                ."\n FROM #__os_gallery_img as galImg"
                                ."\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id=galImg.id"
                                ."\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=galCon.fk_cat_id"
                                ."\n WHERE cat.id = $insertData->id";
                        $db->setQuery($query);
                        $images = $db->loadObjectList();
                        if($images){
                            foreach ($images as $image) {
                                $query = "INSERT INTO #__os_gallery_img(file_name, src, ordering, params)"
                                        ."\n VALUES('".$image->file_name."','".$image->src."'"
                                        ."\n ,'".$image->ordering."','".$image->params."')";
                                $db->setQuery($query);
                                $db->query();

                                $query = "INSERT INTO #__os_gallery_connect(fk_gal_img_id, fk_cat_id) "
                                        ."\n VALUES(".$db->insertid().",".$newCatId.")";
                                $db->setQuery($query);
                                $db->query();
                            }
                        }
                    }
                    //clone images


                    $src = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId[0];
                    if(opendir($src)){
                        $dst = JPATH_BASE . '/../images/com_osgallery/gal-'.$newGalleryId;
                        self::recurse_copy($src,$dst);
                    }
                    $app->enqueueMessage(JText::_("COM_OSGALLERY_DUBLICATE_SUCCESSFULLY"));
                }
            }else{
                $query = "SELECT title, published, params FROM #__os_gallery"
                        ."\n WHERE id = $galId[0]";
                $db->setQuery($query);
                $oldGalData = $db->loadObjectList();
                $oldGalData = $oldGalData[0];

                $query = "INSERT INTO #__os_gallery(title, published, params)"
                        ."\n VALUES('".$oldGalData->title.'(COPY)'."','".$oldGalData->published."','".$oldGalData->params."' )";
                $db->setQuery($query);
                $db->query();

                $query = "INSERT INTO #__os_gallery_categories(fk_gal_id,name,params) VALUES (".$db->insertid().",'Category Title','{}')";
                $db->setQuery($query);
                $db->query();

                $app->enqueueMessage(JText::_("COM_OSGALLERY_DUBLICATE_SUCCESSFULLY"));
            }
        }else{
            $app->enqueueMessage('Please select gallery to clone.', 'error');
        }
        $app->redirect('index.php?option=com_osgallery');
    }

    static function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    static function published($galIds,$publish){
        $db = JFactory::getDbo();
        if(count($galIds)){
            foreach ($galIds as $galId) {
                $query = "UPDATE #__os_gallery SET published=$publish WHERE id=$galId";
                $db->setQuery($query);
                $db->query();
            }
        }
        JFactory::getApplication()->redirect('index.php?option=com_osgallery');
    }

    static function deleteGallery($delGalIds){
        $db = JFactory::getDbo();
        if(count($delGalIds)){
            foreach ($delGalIds as $galId) {
                //delete folder with gallery
                $imageFolderPath = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId;
                self::rrmdir($imageFolderPath);

                //delete img from db
                $query = "DELETE gim FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gc.fk_gal_img_id=gim.id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id".
                            "\n WHERE cat.fk_gal_id=$galId";
                $db->setQuery($query);
                $db->query();

                //delete img from connect table
                $query = "DELETE gc FROM #__os_gallery_connect as gc ".
                        "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id".
                        "\n WHERE cat.fk_gal_id=$galId";
                $db->setQuery($query);
                $db->query();

                //delete cat
                $query = "DELETE cat FROM #__os_gallery_categories as cat ".
                            "\n WHERE cat.fk_gal_id=$galId";
                $db->setQuery($query);
                $db->query();

                //delete gal from db
                $query = "DELETE FROM #__os_gallery WHERE id=$galId";
                $db->setQuery($query);
                $db->query();
            }
        }
        JFactory::getApplication()->redirect('index.php?option=com_osgallery');
    }

    static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        self::rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
        rmdir($dir);
       }
    }

    static function uploadImages(){
        // saving images
        jimport('joomla.application.module.helper');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        $db = JFactory::getDbo();
        $input  = JFactory::getApplication()->input;
        $response = array('success' => false, 'message' => '');
        if (isset($_GET['qqfile'])) {
            $post_form = false;
            $pathinfo = pathinfo(strtolower($_GET['qqfile']));
        } elseif (isset($_FILES['qqfile'])) {
            $post_form = true;
            $pathinfo = pathinfo(strtolower($_FILES['qqfile']['name']));
        } else {
            $response['message'] = 'File is empty, check your file and try again!';
            echo json_encode($response);
            return;
        }
        $filename = JApplication::stringURLSafe($pathinfo['filename']);
        $filename .= self::touchGuid();
        $ext = $pathinfo['extension'];
        $max_filesize = self::toBytes(ini_get('upload_max_filesize'));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $dir = JPATH_BASE . '/../images';
        $catId = $input->get("catId",0,"INT");
        $galId = $input->get("galId",0,"INT");

        //check maxFileSize
        if (self::getFileSize($post_form) > $max_filesize) {
            $response['message'] = "File is too large";
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
        $dir = $dir . '/com_osgallery/gal-'.$galId;
        if (!file_exists($dir) || !is_dir($dir)) mkdir($dir, 0755, true);
        if (!file_exists($dir . '/original') || !is_dir($dir)) mkdir($dir . '/original', 0755, true);
        if (!file_exists($dir . '/original/watermark') || !is_dir($dir)) mkdir($dir . '/original/watermark', 0755, true);
        if (!file_exists($dir . '/thumbnail') || !is_dir($dir)) mkdir($dir . '/thumbnail', 0755, true);
        if (!file_exists($dir . '/watermark') || !is_dir($dir)) mkdir($dir . '/watermark', 0755, true);

        //saving file
        if (!self::fileSave("{$dir}/original/{$filename}.{$ext}", $post_form)) {
            $response['message'] = "Can't save file here: {$dir}/original/{$filename}.{$ext}";
        }else{
            $imagesize = getimagesize("{$dir}/original/{$filename}.{$ext}", $imageinfo);
            self::createImageThumbnail($dir . "/original/{$filename}.{$ext}", $dir .
             "/thumbnail/{$filename}.{$ext}", 600, 400, 1);
            
            //save image to database
            $response['id'] = self::dbSaveImages($filename.'.'.$ext, $catId, $galId);
            $response['galId'] = $galId;
            //end
            $response['success'] = true;
            $response['file'] = $filename;
            $response['ext']= '.'.$ext;
        }
        echo json_encode($response);
        exit;
    }

    protected static function findView($view){
        $Path = JPATH_COMPONENT . '/views/'.$view.'/default.php';

        if (file_exists($Path)){
          return $Path;
        } else {
          echo "Bad layout path to view->".$view.", please write to admin";
          exit;
        }
    }

    protected static function touchGuid(){
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

    protected static function toBytes($val) {
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

        return (int)$val;
    }

    protected static function getFileSize($post_form){
        if($post_form){
            return $_FILES['qqfile']['size'];
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
            if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $dest)) {
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

    static function createWaterMark($file_name, $galId, $params){
        //get imfo about original image
        $original_src = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/original/'.$file_name;
        $original_info = getimagesize($original_src, $original_info);
        $original_width = $original_info[0];
        $original_height = $original_info[1];
        $original_ext = str_replace('image/', '', $original_info['mime']);
        //get create and save function
        $imageCreateFunc = self::getImageCreateFunction($original_ext);
        $imageSaveFunc = self::getImageSaveFunction($original_ext);
        $sImage = $imageCreateFunc($original_src);
        $dImage = imagecreatetruecolor($original_width, $original_height);

        // Make transparent
        if ($original_ext == 'png') {
          imagealphablending($dImage, false);
          imagesavealpha($dImage,true);
          $transparent = imagecolorallocatealpha($dImage, 255, 255, 255, 127);
          imagefilledrectangle($dImage, 0, 0, $original_width, $original_height, $transparent);
        }

        //get watermark
        if($params->get("watermark_type")== 1){
            $mark = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/watermark/'.$params->get("watermark_file");
        }else{
            //create text watermark
            //get size of text
            $text = $params->get("watermark_text");
            $font = JPATH_COMPONENT_ADMINISTRATOR.'/assets/fonts/font.ttf';
            $textSizes = $params->get("watermark_text_size");
            $textAngle = $params->get("watermark_text_angle");
            //size,angle
            $TextSizes = imagettfbbox($textSizes, $textAngle, $font, $text);
            if($textAngle==45){
                $width = $height = abs($TextSizes[5] - $TextSizes[1]);
            }else{
                $width = abs($TextSizes[4] - $TextSizes[0]);
                $height = abs($TextSizes[5] - $TextSizes[1]);
            }

            $mark = imagecreatetruecolor($width, $height);
            imagealphablending($mark, false);
            imagesavealpha($mark,true);
            $white = imagecolorallocatealpha($mark, 255, 255, 255, 127);
            $grey = imagecolorallocate($mark, 128, 128, 128);
            $fontColor = str_replace("rgba(", '', $params->get("watermark_text_color"));
            $fontColor = str_replace(")", '', $fontColor);
            $fontColor = explode(',', $fontColor);
            $r = isset($fontColor[0])?$fontColor[0]:0;
            $g = isset($fontColor[1])?$fontColor[1]:0;
            $b = isset($fontColor[2])?$fontColor[2]:0;
            $fontColor = imagecolorallocate($mark, $r, $g, $b);
            imagefilledrectangle($mark, 0, 0, $width, $height, $white);

            // Тень
            //size,angle
            if($textAngle ==45){
                $margin = $textSizes/2;
                $offset = $height;
            }else if($textAngle == 90){
                $margin = $textSizes;
                $offset = $height;
            }else{
                $margin = 0;
                $offset = $textSizes;
            }
            imagettftext($mark, $textSizes, $textAngle, $margin+1, $offset+1, $grey, $font, $text);
            // Текст
            //size,angle
            if($textAngle ==45){
                $margin = $textSizes/2;
                $offset = $height;
            }else if($textAngle == 90){
                $margin = $textSizes;
                $offset = $height;
            }else{
                $margin = 0;
                $offset = $textSizes;
            }
            imagettftext($mark, $textSizes, $textAngle, $margin, $offset, $fontColor, $font, $text);

            $dest = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/watermark/text_watermark.png';
            imagepng($mark, $dest);
            imagedestroy($mark);
            //end
            //print_r('http://localhost/~akosha/Joomla_clear/images/com_osgallery/gal-'.$galId.'/watermark/text_watermark.png');exit;

            $mark = $dest;
        }
        $watermark_info = getimagesize($mark, $watermark_info);
        $watermark_ext = str_replace('image/', '', $watermark_info['mime']);
        $watermarkCreateFunc = self::getImageCreateFunction($watermark_ext);
        $watermark = $watermarkCreateFunc($mark);
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);

        $margin = 10;
        if($params->get("watermark_type")== 1){
            $sx = $original_width*$params->get("watermark_size")/100;
            $sy = $watermark_height*($sx/$watermark_width);
        }else{
            $sx = ($watermark_width < $original_width)?$watermark_width : $original_width-$original_width*0.5;
            $sy = ($watermark_height < $original_height)?$watermark_height : $original_height-$original_height*0.5;
        }
        $xx = $original_width;
        $yy = $original_height;
        $position = $params->get("watermark_position");

        switch ($position) {
            case 'top_left':
                $x = $margin;
                $y = $margin;
                break;
            case 'top_right':
                $x = $xx - $sx - $margin;
                $y = $margin;
                break;
            case 'bottom_left':
                $x = $margin;
                $y = $yy - $sy - $margin;
                break;
            case 'bottom_right':
                $x = $xx - $sx - $margin;
                $y = $yy - $sy - $margin;
                break;
            case 'center':
                $x = $xx/2-$sx/2;
                $y = $yy/2-$sy/2;
                break;
        }

        //RESIZE watermark save transparrent
        $resize_mark = imagecreatetruecolor($sx, $sy);
        imagealphablending($resize_mark, false);
        imagesavealpha($resize_mark,true);
        $transparent = imagecolorallocatealpha($resize_mark, 255, 255, 255, 127);
        imagecopyresampled($resize_mark, $watermark, 0, 0, 0, 0, $sx, $sy, $watermark_width, $watermark_height);

        //save transparent in main image
        $sImage = self::imagecopymerge_alpha($sImage, $resize_mark, $x, $y, 0, 0, $sx, $sy,$params->get("watermark_opacity"));

        $save_src = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId.'/original/watermark/'.$file_name;
        if ($original_ext == 'png') {
          $imageSaveFunc($sImage, $save_src, 9);
        }
        else if ($original_ext == 'gif') {
          $imageSaveFunc($sImage, $save_src, 100);
        }
        else {
          $imageSaveFunc($sImage, $save_src, 100);
        }
    }

    static function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);

        // copying relevant section from background to the cut resource
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

        // copying relevant section from watermark to the cut resource
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);

        imagealphablending($dst_im, false);
        imagesavealpha($dst_im,true);
        // insert cut resource to destination image
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
        return $dst_im;
    }

    static function cropImages( $galId, $layout, $destWidht, $destHeight ) {

        $db = JFactory::getDbo();
        $imageFolderPath = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId;

        $query = "SELECT gim.id, gim.file_name, cat.id as catId  FROM #__os_gallery_img as gim ".
                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                "\n WHERE cat.fk_gal_id=$galId".
                "\n ORDER BY cat.ordering ASC";
        $db->setQuery($query);
        $images =$db->loadObjectList();

        foreach ($images as $value) {
            $info = getimagesize($imageFolderPath.'/thumbnail/'.$value->file_name, $imageinfo);
            if (($info[0] == $destWidht) && ($info[1] == $destHeight) ) {
                continue; 
            } 

            if(file_exists($imageFolderPath.'/thumbnail/'.$value->file_name)){
                unlink($imageFolderPath.'/thumbnail/'.$value->file_name);
            }

            $query = "DELETE FROM #__os_gallery_connect".
                    "\n WHERE fk_gal_img_id=$value->id";
            $db->setQuery($query);
            $db->query();

            $query = "DELETE FROM #__os_gallery_img".
                    "\n WHERE id=$value->id";
            $db->setQuery($query);
            $db->query();

            self::createImageThumbnail($imageFolderPath . "/original/{$value->file_name}", $imageFolderPath .
             "/thumbnail/{$value->file_name}", $destWidht, $destHeight, 1);
            
            self::dbSaveImages($value->file_name, $value->catId, $galId);            

        }
    }

    static function createImageThumbnailForMasonry($layoutMasonry)
    {
        jimport('joomla.application.module.helper');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        $db = JFactory::getDbo();
        $input  = JFactory::getApplication()->input;
        $catId = $input->get("catId",0);
        $galId = $input->get("galId",0);
        $spaceBetween = $input->get("image_margin",0);
        $numColumns = $input->get("num_column", 3);
        $dir = JPATH_BASE . '/../images/com_osgallery/gal-'.$galId ;
        $images = scandir("{$dir}/original");
        foreach ($images as $image) {
            if (!self::checkImgExtension($image)) continue;
            $pathinfo = pathinfo($image);
            $filename = $pathinfo['filename'];
            $ext = $pathinfo['extension'];
            $max_filesize = self::toBytes(ini_get('upload_max_filesize'));
            
            $destWidht = 600; 
            $destHeight = 400;

            $info = getimagesize("{$dir}/original/{$filename}.{$ext}", $imageinfo);
            $width = $info[0];
            $height = $info[1];
            
            if ($layoutMasonry == "default") {
                $destHeight = round(($height * $destWidht) / $width, 0, PHP_ROUND_HALF_UP);
                $thumbnail = "thumbnail_masonry/{$layoutMasonry}";
            }

            if ($layoutMasonry == "horizontal") {
                if ($height >= $width) {
                    $destHeight = $destWidht + ($spaceBetween * 2)*$numColumns ;
                }
                else {
                    $destWidht = $destHeight * 2 - $spaceBetween *($numColumns-1) ;
                }
                $thumbnail = "thumbnail_masonry/{$layoutMasonry}";         
            }

            if ($layoutMasonry == "vertical") {
                if ($height <= $width) $destHeight = $destWidht  ;
                else {
                    $destHeight = $destWidht * 2 - $spaceBetween;
                    $destWidht -= ($spaceBetween * 2);
                }
                $thumbnail = "thumbnail_masonry/{$layoutMasonry}";
            }

            if ($layoutMasonry == "fit_rows") {
                $destWidht = 1; 
                $destHeight = 400;
                if ($height <= $width) $destWidht = $destHeight* 2;
                else $destWidht = $destHeight ;
                $thumbnail = "thumbnail_fitrows";
            } 

            self::createImageThumbnail($dir . "/original/{$filename}.{$ext}", $dir .
                 "/{$thumbnail}/{$filename}.{$ext}", $destWidht, $destHeight, 1, 100);                
        }
    }

    static function checkImgExtension($image) {
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            foreach ($allowedExtensions as $ext) {
                if (strpos($image, $ext)) return true;
            }
            return false;
    } 

    static function createImageThumbnail($src, $dest, $destWidht, $destHeight ,$crop = true, $quality = 100){
        // Setting the resize parameters
        $info = getimagesize($src, $imageinfo);
        $file_type = '.'.str_replace('image/', '', $info['mime']);
        $width = $info[0];
        $height = $info[1];

        if (file_exists($dest)) {
            return;
        } else {
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
            $CreateNewImage = self::createImage($src, $dest, $destWidht, $destHeight ,$crop, $quality);
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

    static function createImage($src, $dest, $destWidht, $destHeight ,$crop = true, $quality = 100){
        $info = getimagesize($src, $imageinfo);
        $sWidth = $info[0];
        $sHeight = $info[1];
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
                break;
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
                break;
        }
        return $imageSaveFunc;
    }

    static function dbSaveImages($filename, $catId, $galId){
        $db = JFactory::getDbo();

        $query = "INSERT IGNORE INTO #__os_gallery_categories(id,fk_gal_id) ".
            "\n VALUES (".$catId.",".$galId.")";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO #__os_gallery_img(file_name) ".
                "\n VALUES (".$db->Quote($filename).")";
        $db->setQuery($query);
        $db->query();

        $query = "INSERT INTO #__os_gallery_connect(fk_cat_id, fk_gal_img_id) ".
                "\n VALUES (".$catId.",".$db->insertid().")";
        $db->setQuery($query);
        $db->query();

        return $db->insertid();
    }

    static function checkEnableUpdate()
    {
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);
        
        $query
            ->select($db->quoteName(array('enabled')))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('name') . ' LIKE \'plg_quickicon_joomlaupdate\'');

        $db->setQuery($query);
        $test = $db->loadResult();
        return $test;
    } 
}