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
if(count($images)){  
    foreach($images as $catId => $catImages){
        $currentCatParams = new JRegistry;
        $currentCatParams = $currentCatParams->loadString(urldecode($catParamsArray[$catId]->params));
        if($currentCatParams->get("categoryUnpublish",false))continue;
        if($currentCatParams->get("categoryAlias",'')){
            $catName = $currentCatParams->get("categoryAlias",'');
        }else if(isset($catImages[0])){
            $catName = $catImages[0]->cat_name;
        }else{
            $catName = 'no name';
        }
        $styleImg = 'style="margin:'.$imageMargin.'px;"';
        $styleCat = 'style="padding:'.$imageMargin.'px;display:none!important;"';
        if($catImages){
            foreach($catImages as $image){
                $currentImgParams = new JRegistry;
                $currentImgParams = $currentImgParams->loadString(urldecode($imgParamsArray[$image->id]->params));
                $imgAlt = ($currentImgParams->get("imgAlt",''))? $currentImgParams->get("imgAlt",'') : $image->file_name;
                $imgTitle = $currentImgParams->get("imgTitle",'');
                $imgShortDesc = $currentImgParams->get("imgShortDescription",'');
                $imgHtml = $currentImgParams->get("imgHtml", '');
                $imgHtmlShow = $currentImgParams->get("imgHtmlShow", 'yes');

                if($params->get("watermark_enable",false)){
                    $imgLink = JURI::root().'images/com_osgallery/gal-'.$image->galId.'/original/watermark/'.$image->file_name;
                }else{
                    $imgLink = JURI::root().'images/com_osgallery/gal-'.$image->galId.'/original/'.$image->file_name;
                }
                $imgOpen = '';
                if($currentImgParams->get("imgLink",'')){
                    $imgLink = $currentImgParams->get("imgLink");
                    $imgOpen = $currentImgParams->get("imgLinkOpen",'_blank');
                }
                if($currentImgParams->get("imgAlias",'')){
                    $imgAlias = $currentImgParams->get("imgAlias",'');
                }else{
                    $imgAlias = $image->file_name;
                }
                $fancAlias = '';
                if (is_numeric($imgAlias)) {
                    $fancAlias = $imgAlias;
                    $imgAlias = "flag";
                }
                ?>
                <div class="img-block <?php echo $imageHover ?>-effect zoomIn animated" <?php echo $styleImg ?> >

                    <a class="os_fancybox-<?php echo $catId?>"
                        id="image-<?php echo $image->id ?>"
                        rel="group"
                        target="<?php echo $imgOpen?>"
                        href="<?php echo $imgLink?>"
                        data-os_fancybox-title="<?php $showImgAlias ? print $imgAlias : print ''?>"
                        data-os_fancybox-alias="<?php echo $fancAlias ? $fancAlias : ''?>"
                        >
                        <div class="os-gallery-caption">
                            <?php
                            if($imgTitle){
                                echo "<h3 class='os-gallery-img-title'>$imgTitle</h3>";
                            }
                            if($catName && $currentCatParams->get("categoryShowTitleCaption",1)){
                                echo "<p class='os-gallery-img-category'>$catName</p>";
                            }
                            if($imgShortDesc){
                                echo "<p class='os-gallery-img-desc'>$imgShortDesc</p>";
                            }
                            ?>
                        </div>
                        <img src="<?php echo JURI::root()?>images/com_osgallery/gal-<?php echo $image->galId?>/thumbnail/<?php echo $image->file_name?>" alt="<?php echo $imgAlt?>">

                        <?php  if (isset($imgHtml->html) && $imgHtml->html != '') { ?>
                            <div id = "video-<?php echo $image->id ?>" style="display:none">
                                <?php if ($imgHtmlShow == 'yes') { ?>
                                    <div id="img-<?php echo $image->id ?>">                                                
                                        <img src="<?php echo JURI::root()?>images/com_osgallery/gal-<?php echo $image->galId?>/original/<?php echo $image->file_name?>" alt="<?php echo $imgAlt?>" style="position: relative;">
                                    </div>
                                <?php } ?>
                                <div id="text-<?php echo $image->id ?>">

                                </div>
                            </div>
                        <?php } ?>

                        <span class='andrea-zoom-in'></span>
                    </a>

                    <?php  if (isset($imgHtml->html) && $imgHtml->html != '') { ?>
                        <div id="data-html-<?php echo $image->id ?>" style="display:none">
                            <?php echo $imgHtml->html ; ?>
                        </div>
                    <?php } ?>

                </div>
                <?php
            }
        } 
    } 
}