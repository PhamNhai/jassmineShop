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

class osGalleryHelperSite{

    static function displayView($galIds = array()){
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        $input = $app->input;
        $menu = $app->getMenu()->getActive();

        $params = new JRegistry;
        $menuParams = new JRegistry;
        $task = $input->getCmd('task', '');
        $view = $input->getCmd('view', '');

        //include css
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/os-gallery.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/css/font-awesome.min.css");
        $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquery.os_fancyboxGall.css");

        //js
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/jQuery/jQuerGall-2.2.4.js");
        $document->addScriptDeclaration("jQuerGall=jQuerGall.noConflict();");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/jquery.os_fancyboxGall.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/imagesloadedGall.pkgd.min.js");
        $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/isotope/isotope.pkgd.min.js");

        $menuParams = $menu->params;
        $itemId = $menu->id;
        $imgEnd = '';

        if($galIds){
        }else{
            $galIds = $menuParams->get("gallery_list",array());
        }

        if (!$galIds && $input->get('galId')) {
            $galIds[] = $input->get('galId');
        }
        $buttons = array();
        foreach ($galIds as $galId) {
            if($galId){
                //load params
                $query = "SELECT params FROM #__os_gallery WHERE id=$galId";
                $db->setQuery($query);
                $paramsString = $db->loadResult();
                if($paramsString){
                    $params->loadString($paramsString);
                }
            }

            // LoadMore button
            $showLoadMore = $params->get("showLoadMore", 0);
            $numberImages = $params->get("number_images", 5);
            $downloadButton = $params->get("showDownload", 0);
            $showImgAlias = $params->get("showImgAlias", 0);

            if ($input->get('end', '')){
                $end =$input->get('end');
            }
            else{
                $end = 0;
            }
            // ---------------
            if($galId){
                if (!$showLoadMore ) {
                    // getting Images
                    $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId FROM #__os_gallery_img as gim ".
                            "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                            "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                            "\n WHERE cat.fk_gal_id =$galId AND gal.published".
                            "\n ORDER BY cat.ordering ASC" ;
                    $db->setQuery($query);
                    $result =$db->loadObjectList();

                } else {

                    if ($input->get("catId", '')) {
                        $cat_id_array =  array();
                        $cat_id_array[] = $input->get("catId");
                    } else {
                        $query = "SELECT DISTINCT fk_cat_id FROM #__os_gallery_connect";
                        $db->setQuery($query);
                        $cat_id_array = $db->loadColumn();
                    }

                    $result = array();
                    foreach ($cat_id_array as $cat_id) {
                        // getting Images
                        $query = "SELECT  gim.* , gc.fk_cat_id, cat.name as cat_name, gal.id as galId, cat.ordering as cat_ordering FROM #__os_gallery_img as gim ".
                                "\n LEFT JOIN #__os_gallery_connect as gc ON gim.id=gc.fk_gal_img_id".
                                "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id=gc.fk_cat_id ".
                                "\n LEFT JOIN #__os_gallery as gal ON gal.id=cat.fk_gal_id ".
                                "\n WHERE cat.fk_gal_id =$galId AND gal.published AND gc.fk_cat_id=$cat_id" .
                                "\n ORDER BY gim.ordering ASC" . 
                                "\n LIMIT $end," . ($numberImages + 1);
                        $db->setQuery($query);
                        $imgArr = $db->loadObjectList();

                        if (count($imgArr) < ($numberImages+1)) {
                            $imgEnd = -1;
                        } else {
                             unset($imgArr[count($imgArr)-1]);
                        }
                        $result = array_merge($result, $imgArr);
                    }
                    // ordering categories
                    usort($result, function($a, $b) {
                        return $a->cat_ordering>$b->cat_ordering;
                    });
                }

                if($result){

                    $images = array();
                    foreach ($result as $image) {
                        if(!isset($images[$image->fk_cat_id])){
                           $images[$image->fk_cat_id] = array();
                        }
                        $images[$image->fk_cat_id][] = $image;
                    }

                    //ordering images
                    foreach ($images as $key => $imageArr) {
                        usort($imageArr, "self::sortByOrdering");
                        $images[$key] = $imageArr;
                    }

                    //get cat params array
                    $query = "SELECT DISTINCT id,params FROM #__os_gallery_categories".
                            "\n WHERE fk_gal_id =$galId";
                    $db->setQuery($query);
                    $catParamsArray = $db->loadObjectList('id');

                    //get cat params array
                    $query = "SELECT DISTINCT galImg.id,galImg.params FROM #__os_gallery_img as galImg".
                            "\n LEFT JOIN #__os_gallery_connect as galCon ON galCon.fk_gal_img_id = galImg.id".
                            "\n LEFT JOIN #__os_gallery_categories as cat ON cat.id = galCon.fk_cat_id".
                            "\n WHERE cat.fk_gal_id =$galId";
                    $db->setQuery($query);
                    $imgParamsArray = $db->loadObjectList('id');
                }else{
                    $images = array();
                    $imgParamsArray = array("1"=>(object) array("params"=>'{}'));
                    $catParamsArray = array("1"=>(object) array("params"=>'{}'));
                }
            }else{
                $app->enqueueMessage("Select gallery for menu(".$itemId.").", 'error');
                return;
            }
            //include some css and script
            if($params->get("helper_buttons",0)){
                $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancybox-buttons.css");
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancyboxGall-buttons.js");
            }

            if($params->get("helper_thumbnail")){
                $document->addStyleSheet(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancybox-thumbs.css");
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.os_fancyboxGall-thumbs.js");
            }
            if($params->get("mouse_wheel",1)){
                $document->addScript(JURI::base() . "components/com_osgallery/assets/libraries/os_fancybox/helpers/jquery.mousewheel-3.0.6.pack.js");
            }

            //setup some variables
            $click_close = $params->get("click_close",1);
            $os_fancybox_background = $params->get("fancy_box_background","transparent");
            if($params->get("img_title") == 'none'){
                $os_fancybox_title = "title : null,\n";
            }else{
                $os_fancybox_title = "title : {type : '".$params->get("img_title")."'},\n";
            }

            if($os_fancybox_background == 'white'){
                $os_fancybox_background = "overlay : {locked: false,closeClick : ".$click_close.",css : {'background' : 'rgba(238,238,238,0.85)'}}\n";
            }else if($os_fancybox_background == 'transparent'){
                $os_fancybox_background = "overlay : {locked: false,closeClick : ".$click_close.",css : {'background' : 'rgba(238,238,238,0)'}}\n";
            }else{
                $os_fancybox_background = "overlay : {locked: false,closeClick : ".$click_close.",css : {'background' : 'rgba(0, 0, 0, 0.75)'}}\n";
            }

            $helper_buttons = '';
            if($params->get("helper_buttons",0)){
                $helper_buttons = ",buttons : {}\n";
            }
            $helper_thumbnail = '';
            if($params->get("helper_thumbnail")){
                $helper_thumbnail = ',thumbs : {width  : '.$params->get("thumbnail_width").
                                                ',height : '.$params->get("thumbnail_height").'}';
            }

            $open_close_effect = $params->get("open_close_effect","fade");
            $open_close_speed = $params->get("open_close_speed",500);
            $prev_next_effect = $params->get("prev_next_effect","elastic");
            $prev_next_speed = $params->get("prev_next_speed",500);
            $loop = $params->get("loop",1);
            $os_fancybox_arrows = $params->get("os_fancybox_arrows",1);
            $os_fancybox_arrows_pos = $params->get("os_fancybox_arrows_pos",0);
            $close_button = $params->get("close_button",1);
            $next_click = $params->get("next_click",0);
            $mouse_wheel = $params->get("mouse_wheel",1);
            $os_fancybox_autoplay = $params->get("os_fancybox_autoplay",0);
            $autoplay_speed = $params->get("autoplay_speed",3000);
            $backText = $params->get("backButtonText",'back');
            $numColumns = $params->get("num_column",4);
            $minImgEnable = $params->get("minImgEnable",1);
            $minImgSize = $params->get("minImgSize",225);
            $imageMargin = $params->get("image_margin")/2;
            $loadMoreButtonText = $params->get("loadMoreButtonText", "Load More");
            $load_more_background = $params->get('load_more_background', '#12BBC5') ;                      

            $imageHover = $params->get("imageHover", "none");
            $galleryLayout = $params->get("galleryLayout", "defaultTabs");
            $masonryLayout = $params->get("masonryLayout", "default");


            if(!$task && $view)$task = $view;
            if ($task != "loadMoreButton")
                require self::findView($galleryLayout);
            else {
                ob_start();
                    require self::findView($galleryLayout, "loadMore");
                    $html = ob_get_contents();
                ob_end_clean();
                $catId = $input->get("catId");
                if ($imgEnd == -1) {
                    $end = $imgEnd;
                }else{
                    $end = $end + $numberImages;
                }
                echo json_encode(array("success"=>true, "html"=>$html, "limEnd"=>$end, "catId"=>$catId));exit;

            }
        }
    }

    static function sortByOrdering($a,$b) {
        return $a->ordering>$b->ordering;
    }

    protected static function findView($type, $view='default'){
        $Path = JPATH_SITE . '/components/com_osgallery/views/'.$type. '/tmpl/' . $view . '.php';

        if (file_exists($Path)){
          return $Path;
        } else {
          echo "Bad layout path to view->".$view.", please write to admin";
          exit;
        }
    }

    public static function getSocialOptions($params) {
        $methods = array();
        if ($params->get('facebook_enable')) $methods[] = 'FacebookButton';
        if ($params->get('googleplus_enable')) $methods[] = 'GooglePlusButton';
        if ($params->get('vkontacte_enable')) $methods[] = 'VkComButton';
        if ($params->get('odnoklassniki_enable')) $methods[] = 'OdnoklassnikiButton';
        if ($params->get('twitter_enable')) $methods[] = 'TwitterButton';  
        if ($params->get('pinterest_enable')) $methods[] = 'PinterestButton';
        if ($params->get('linkedin_enable')) $methods[] = 'LinkedInButton';

        return $methods;
    }

    private static function callSocialButtonsMethods($obj, $method) {
        $method = 'get' . $method;
        if (method_exists($obj, $method)) return $obj->$method();  
    }


}