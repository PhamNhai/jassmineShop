<?php
/**
* @package OS Gallery
* @copyright 2016 OrdaSoft
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <ul class="main-gallery-header nav nav-tabs main-nav-tabs" style="display:none;">
        <li><a href="#gallery-main-tab"><?php echo JText::_("COM_OSGALLERY_BUTTON_MAIN")?></a></li>
        <li><a href="#gallery-settings-tab"><?php echo JText::_("COM_OSGALLERY_SETTINGS_BUTTON_MAIN")?></a></li>
    </ul>
    <div class="gallery-main-content-tab tab-content">
        <div id="gallery-main-tab" class="tab-pane fade">
            <div class="span8 os-gallery-wrapp">
                <div id="file-area">
                    <noscript>
                        <p>JavaScript disabled :(</p>
                    </noscript>
                    <script type="text/template" id="qq-template">
                        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
                            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                            </div>
                            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                                <span class="qq-upload-drop-area-text-selector"></span>
                            </div>
                            <div class="qq-upload-button-selector qq-upload-button">
                                <div>Upload a file</div>
                            </div>
                                <span class="qq-drop-processing-selector qq-drop-processing">
                                    <span>Processing dropped files...</span>
                                    <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                                </span>
                            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                                <li>
                                    <div class="qq-progress-bar-container-selector">
                                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                    </div>
                                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                    <span class="qq-upload-file-selector qq-upload-file"></span>
                                    <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                    <span class="qq-upload-size-selector qq-upload-size"></span>
                                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
                                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                </li>
                            </ul>

                            <dialog class="qq-alert-dialog-selector">
                                <div class="qq-dialog-message-selector"></div>
                                <div class="qq-dialog-buttons">
                                    <button type="button" class="qq-cancel-button-selector">Close</button>
                                </div>
                            </dialog>

                            <dialog class="qq-confirm-dialog-selector">
                                <div class="qq-dialog-message-selector"></div>
                                <div class="qq-dialog-buttons">
                                    <button type="button" class="qq-cancel-button-selector">No</button>
                                    <button type="button" class="qq-ok-button-selector">Yes</button>
                                </div>
                            </dialog>

                            <dialog class="qq-prompt-dialog-selector">
                                <div class="qq-dialog-message-selector"></div>
                                <input type="text">
                                <div class="qq-dialog-buttons">
                                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                                    <button type="button" class="qq-ok-button-selector">Ok</button>
                                </div>
                            </dialog>
                        </div>
                    </script>
                    <div id="fine-uploader"></div>
                </div>

                <ul id="osgalery-cat-tabs" class="nav cat-nav-tabs nav-tabs">
                    <?php
                    foreach($categories as $cat){ ?>
                        <li id="order-id-<?php echo $cat->id?>">
                            <a href="#cat-<?php echo $cat->id?>" data-cat-id="<?php echo $cat->id?>"><?php echo $cat->name?></a>
                            <input type="hidden" name="category_names[]" value="<?php echo $cat->id?>|+|<?php echo $cat->name?>" placeholder="">
                            <span class="edit-category-name"><i class="material-icons">mode_edit</i>edit</span>
                            <span class="delete-category"><i class="material-icons">delete</i></span>
                        </li>
                    <?php
                    } ?>
                    <span class="add-new-cat"><i class="material-icons">note_add</i> New Category</span>
                </ul>

                <div id="os-cat-tab-images" class="tab-content">
                    <?php
                    foreach($categories as $cat){
                        ?>
                        <div id="cat-<?php echo $cat->id?>" class="tab-pane fade">
                            <?php
                            if(isset($images[$cat->id])){
                                foreach($images[$cat->id] as $image){
                                    echo '<div id="img-'.$image->id.'" class="img-block" data-image-id="'.$image->id.'">'.
                                            '<span class="delete-image"><i class="material-icons">close</i></span>'.
                                            '<img src="'.JURI::root().'images/com_osgallery/gal-'.$galId.'/thumbnail/'.$image->file_name.'" alt="'.$image->file_name.'">'.
                                            '<input id="img-settings-'.$image->id.'" type="hidden" name="imgSettings['.$image->id.']" value="'.htmlspecialchars($imgParamsArray[$image->id]->params).'">'. // $imgParamsArray[$image->id]->params.
                                        '</div>';
                                }
                            }?>
                            <input class="cat-img-ordering" type="hidden" name="imageOrdering[<?php echo $cat->id?>]" value="">
                            <input id="cat-settings-<?php echo $cat->id;?>" type="hidden" name="catSettings[<?php echo $cat->id; ?>]" value="<?php echo $catParamsArray[$cat->id]->params;?>">
                        </div>
                    <?php
                    } ?>
                </div>
            </div>
<!-- Options for category and each image -->
            <div class="span4">

            </div>
            <div class="category-options-block">
                    <ul class="options-header nav nav-tabs main-nav-tabs">
                        <li><a href="#img-options-block"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_TAB")?></a></li>
                        <li><a href="#cat-options-block"><?php echo JText::_("COM_OSGALLERY_CATEGORY_OPTION_TAB")?></a></li>
                    </ul>
<!-- IMAGE SETTINGS BLOCK -->
                    <div class="category-options-content tab-content">
                        <div id="img-options-block" class="tab-pane fade">
                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_TITLE_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="img-title" type="text" name="imgTitle" value="">
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_ALIAS_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="img-alias" type="text" name="imgAlias" value="" >
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_SHORT_DESCRIPTION_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="img-short-description" type="text" name="imgShortDescription" value="">
                                </span>
                            </div>

                            <div class="type_html_code osg-pro-avaible-string">
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_HTML_LABEL")?></span>
                                <span class="cat-col-2">
                                    <textarea id="img-html" name="imgHtml" type="text" value="" rows="2" cols="10"></textarea>
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_ALT_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="img-alt" type="text" name="imgAlt" value="">
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_LINK_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="img-link" type="text" name="imgLink" value="">
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_LINK_TARGET_LABEL")?></span>
                                <span class="cat-col-2">
                                    <select id="img-link-open" name="linkOpen">
                                        <option value="_blank"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_LINK_TARGET_SELECT1")?></option>
                                        <option value="_self"><?php echo JText::_("COM_OSGALLERY_IMAGE_OPTION_LINK_TARGET_SELECT2")?></option>
                                    </select>
                                </span>
                            </div>

                            <div class="img-html-show osg-pro-avaible-string">
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_SHOW_IMAGE_HTML")?></span>
                                <span class="cat-col-2">
                                    <div id="general-img-html" class="osgallery-checkboxes-block">
                                        <input id="general-img-html-yes" type="radio" name="showImgHtml" value="1" />
                                        <input id="general-img-html-no" type="radio" name="showImgHtml" value="0" checked/>
                                        <label for="general-img-html-yes" data-value="Yes">Yes</label>
                                        <label for="general-img-html-no" data-value="No">No</label>
                                    </div>
                                </span>
                            </div>

                        </div>
<!-- CATEGORY SETTINGS BLOCK -->
                        <div id="cat-options-block" class="tab-pane fade">
                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_CATEGORY_OPTION_ALIAS_LABEL")?></span>
                                <span class="cat-col-2">
                                    <input id="cat-alias" type="text" name="categoryAlias" value="">
                                </span>
                            </div>

                            <div class="cat-show-title-block">
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_CATEGORY_OPTION_SHOW_TITLE_LABEL")?></span>
                                <span class="cat-col-2">
                                    <div class="os-check-box">
                                      <input type="checkbox" value="None" id="cat-show-title" name="check" checked="checked" />
                                      <label for="cat-show-title"></label>
                                    </div>
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_CATEGORY_OPTION_SHOW_TITLE_CAPTION_LABEL")?></span>
                                <span class="cat-col-2">
                                    <div class="os-check-box">
                                      <input type="checkbox" value="None" id="cat-show-cat-title-caption" name="check" checked="checked" />
                                      <label for="cat-show-cat-title-caption"></label>
                                    </div>
                                </span>
                            </div>

                            <div>
                                <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_CATEGORY_OPTION_PUBLISH_LABEL")?></span>
                                <span class="cat-col-2">
                                    <div class="os-check-box">
                                      <input type="checkbox" value="None" id="cat-unpublish" name="check" checked="checked"/>
                                      <label for="cat-unpublish"></label>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

<!-- gallery settings tab -->
        <div id="gallery-settings-tab" class="tab-pane fade">
            <ul id="osgalery-settings-tabs" class="nav settings-nav-tabs nav-tabs">
                <li>
                    <a href="#general-settings"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_TAB_LABEL")?></a>
                </li>
                <li class="osg-pro-avaible">
                    <a href="#os_fancybox-settings"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_TAB_LABEL")?></a>
                </li>
                <li class="osg-pro-avaible">
                    <a href="#watermark-settings"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATEMARK_TAB_LABEL")?></a>
                </li>                
                <li class="osg-pro-avaible">
                    <a href="#social-settings"><?php echo "Social Buttons"?></a>
                </li>
            </ul>
            <div id="os-tab-settings" class="tab-content">
<!-- GENERAL settings -->
                <div id="general-settings" class="tab-pane fade">
                    <div>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERALLAYOUT_LABEL")?></span>
                        <span class="cat-col-2">
                            <select class="gallery-layout" name="galleryLayout">
                                <option selected="" value="defaultTabs">Default</option>
            
                            </select>
                        </span>
                    </div>

                    <div id="masonryLayout" <?php echo ($gallerylayout != "masonry")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERALLAYOUT_MASONRY_LABEL")?></span>
                        <span class="cat-col-2">
                            <select name="masonryLayout">
                                <option <?php echo ($masonryLayout == "default")?'selected="selected"':''?> value="default">default</option>
                                <option <?php echo ($masonryLayout == "horizontal")?'selected="selected"':''?> value="horizontal">horizontal</option>
                                <option <?php echo ($masonryLayout == "vertical")?'selected="selected"':''?> value="vertical">vertical</option>
                            </select>
                        </span>
                    </div>

                    <div class="back-button-text-block" <?php echo ($gallerylayout != "albumMode")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_BACK_BUTTON_LABEL")?></span>
                        <span class="cat-col-2">
                            <input type="text" name="backButtonText" value="<?php echo $backButtonText?>">
                        </span>
                    </div>

                    <div>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_IMAGE_MARGIN_LABEL")?></span>
                        <span class="cat-col-2">
                            <input type="number" min="0" name="image_margin" value="<?php echo $imageMargin?>">
                        </span>
                    </div>

                    <div>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_IMAGE_NUM_COLUMNS_LABEL")?></span>
                        <span class="cat-col-2">
                            <input type="number" min="1" name="num_column" value="<?php echo $numColumn?>">
                        </span>
                    </div>

                    <div id="osgallery-checkboxes-block-general" <?php echo ($gallerylayout == "masonry")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_IMAGE_DECREASE_COLUMN")?></span>
                        <span class="cat-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="general-min-enable-yes" type="radio" name="minImgEnable" value="1" <?php echo $minImgEnable?'checked':''?>/>
                                <input id="general-min-enable-no" type="radio" name="minImgEnable" value="0" <?php echo $minImgEnable?'':'checked'?>/>
                                <label for="general-min-enable-yes" data-value="Yes">Yes</label>
                                <label for="general-min-enable-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div id="minImgSize" <?php echo ($gallerylayout == "masonry")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_IMAGE_DECREASE_COLUMN_SIZE")?></span>
                        <span class="cat-col-2">
                            <input type="number" min="1" name="minImgSize" value="<?php echo $minImgSize?>">
                        </span>
                    </div>

                    <div id="imgWidth" <?php echo ($gallerylayout == "masonry" || $gallerylayout == "fit_rows")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_CROP_IMAGE_WIDTH")?></span>
                        <span class="cat-col-2">
                            <input type="number" min="1" name="imgWidth" value="<?php echo $imgWidth?>">
                        </span>
                    </div>

                    <div id="imgHeight" <?php echo ($gallerylayout == "masonry" || $gallerylayout == "fit_rows")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_CROP_IMAGE_HEIGHT")?></span>
                        <span class="cat-col-2">
                            <input type="number" min="1" name="imgHeight" value="<?php echo $imgHeight?>">
                        </span>
                    </div>

                    <div>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_IMAGEHOVER_LABEL")?></span>
                        <span class="cat-col-2">
                            <select name="imageHover">
                                <option <?php echo ($imagehover == "none")?'selected="selected"':''?> value="none">None</option>
                                <option <?php echo ($imagehover == "dimas")?'selected="selected"':''?> value="dimas">Dimas</option>
                                <option <?php echo ($imagehover == "anet")?'selected="selected"':''?> value="anet">Anet</option>
                            </select>
                        </span>
                    </div>
                    
                    <!-- add download button setting -->
                    <div class="download-show-button osg-pro-avaible-string">
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_ENABLE_DOWNLOAD_BUTTON")?></span>
                        <span class="cat-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="general-min-download-yes" type="radio" name="showDownload" value="1" <?php echo $showDownload?'checked':''?>/>
                                <input id="general-min-download-no" type="radio" name="showDownload" value="0" <?php echo $showDownload?'':'checked'?>/>
                                <label for="general-min-download-yes" data-value="Yes">Yes</label>
                                <label for="general-min-download-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>
                    <!-- end download butoon setting -->

                    <!-- add show alias button setting -->
                    <div class="img-alias-show">
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_ENABLE_IMAGE_ALIAS")?></span>
                        <span class="cat-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="general-min-imgalias-yes" type="radio" name="showImgAlias" value="1" <?php echo $showImgAlias?'checked':''?>/>
                                <input id="general-min-imgalias-no" type="radio" name="showImgAlias" value="0" <?php echo $showImgAlias?'':'checked'?>/>
                                <label for="general-min-imgalias-yes" data-value="Yes">Yes</label>
                                <label for="general-min-imgalias-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

<!--                     <div class="img-html-show">
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_SHOW_IMAGE_HTML")?></span>
                        <span class="cat-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="general-img-html-yes" type="radio" name="showImgHtml" value="1" <?php echo $showImgHtml?'checked':''?>/>
                                <input id="general-img-html-no" type="radio" name="showImgHtml" value="0" <?php echo $showImgHtml?'':'checked'?>/>
                                <label for="general-img-html-yes" data-value="Yes">Yes</label>
                                <label for="general-img-html-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div> -->


                    <!-- end show alias butoon setting -->

                    <!-- add 'load more' option -->
                    <div class="load-more-show-button osg-pro-avaible-string" <?php echo ($gallerylayout == "allInOne")?'style="display:none;"':'';?>>
                        <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_SHOW_LOAD_MORE_BUTTON")?></span>
                        <span class="cat-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="general-min-loadmore-yes" type="radio" name="showLoadMore" value="1" <?php echo $showLoadMore?'checked':''?>/>
                                <input id="general-min-loadmore-no" type="radio" name="showLoadMore" value="0" <?php echo $showLoadMore?'':'checked'?>/>
                                <label for="general-min-loadmore-yes" data-value="Yes">Yes</label>
                                <label for="general-min-loadmore-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>
                    
                    <div id="load-more-block">
                        <div class="load-more-button-text" <?php echo ($gallerylayout == "allInOne")?'style="display:none;"':'';?>>
                            <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_LOADMORE_BUTTON_LABEL")?></span>
                            <span class="cat-col-2">
                                <input type="text" name="loadMoreButtonText" value="<?php echo $loadMoreButtonText?>">
                            </span>
                        </div>

                        <div class="load_more_background">
                            <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_LOADMORE_BUTTON_BACKGROUND")?></span>
                            <span class="cat-col-2">
                                <input  type="text" data-opacity="1.00" value="<?php echo $load_more_background?>" name="load_more_background" size="25">
                            </span>
                        </div>

                        <div class="load-more-number-images" >
                            <span class="cat-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GENERAL_NUMBER_DISPLAY_IMAGES")?></span>
                            <span class="cat-col-2">
                                <input type="number" min="0" name="number_images" value="<?php echo $numberImages;?>">
                            </span>
                        </div>
                    </div>
                   <!-- end load more option -->
                </div>

                <div id="os_fancybox-settings" class="tab-pane fade osg-pro-avaible">
                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_BACKGROUND_COLOR_SELECT_LABEL")?></span>
                        <span class="sett-col-2">
                            <select name="fancy_box_background">
                                <option selected="" value="gray"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_BACKGROUND_COLOR_SELECT_OPTION1")?></option>
                                <option  value="white"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_BACKGROUND_COLOR_SELECT_OPTION2")?></option>
                                <option  value="transparent"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_BACKGROUND_COLOR_SELECT_OPTION3")?></option>
                            </select>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_CLOSE_CLICK_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-close-yes" type="radio" name="click_close" value="1" checked/>
                                <input id="os_fancybox-close-no" type="radio" name="click_close" value="0" />
                                <label for="os_fancybox-close-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-close-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_OPEN_CLOSE_LABEL")?></span>
                        <span class="sett-col-2">
                            <select name="open_close_effect">
                                <option value="elastic">Elastic</option>
                                <option   value="fade">Fade</option>
                                <option selected="" value="none">None</option>
                            </select>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_OPEN_CLOSE_SPEED_LABEL")?></span>
                        <span class="sett-col-2">
                            <input type="text" name="open_close_speed" value="<?php echo $open_close_speed?>"/>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_PREV_NEXT_EFFECT_LABEL")?></span>
                        <span class="sett-col-2">
                            <select name="prev_next_effect">
                                <option  value="elastic">Elastic</option>
                                <option value="fade">Fade</option>
                                <option selected="" value="none">None</option>
                            </select>
                        </span>
                    </div>

                    <div class="os-fancybox-prev-next-speed-block" >
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_PREV_NEXT_SPEED_LABEL")?></span>
                        <span class="sett-col-2">
                            <input type="text" name="prev_next_speed" value="<?php echo $prev_next_speed?>"/>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_IMAGE_TITLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <select name="img_title">
                                <option  value="float">Float</option>
                                <option  selected=""  value="inside">Inside</option>
                                <option  value="outside">Outside</option>
                                <option  value="over">Over</option>
                                <option  value="none">None</option>
                            </select>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_LOOP_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-loop-yes" type="radio" name="loop" value="1" />
                                <input id="os_fancybox-loop-no" type="radio" name="loop" value="0" checked/>
                                <label for="os_fancybox-loop-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-loop-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_HELPERS_BUTTONS_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-helpers-yes" type="radio" name="helper_buttons" value="1" />
                                <input id="os_fancybox-helpers-no" type="radio" name="helper_buttons" value="0" checked/>
                                <label for="os_fancybox-helpers-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-helpers-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_HELPERS_THUMBNAIL_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-thumbnail-yes" type="radio" name="helper_thumbnail" value="1" />
                                <input id="os_fancybox-thumbnail-no" type="radio" name="helper_thumbnail" value="0" checked/>
                                <label for="os_fancybox-thumbnail-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-thumbnail-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div class="thumbnail-help-block">
                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_HELPERS_THUMBNAIL_WIDTH_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="text" name="thumbnail_width" value="<?php echo $thumbnail_width?>"/>
                            </span>
                        </div>

                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_HELPERS_THUMBNAIL_HEIGHT_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="text" name="thumbnail_height" value="<?php echo $thumbnail_height?>"/>
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_PREV_NEXT_ARROWS_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-arrows-yes" type="radio" name="os_fancybox_arrows" value="1" <?php echo $os_fancybox_arrows?'checked':''?>/>
                                <input id="os_fancybox-arrows-no" type="radio" name="os_fancybox_arrows" value="0" <?php echo $os_fancybox_arrows?'':'checked'?>/>
                                <label for="os_fancybox-arrows-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-arrows-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div class="os_fancybox-arrows-pos-block" <?php echo $os_fancybox_arrows?'':'style="display:none;"'?>>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_PREV_NEXT_ARROWS_POSITION")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-arrows-pos-yes" type="radio" name="os_fancybox_arrows_pos" value="1" checked />
                                <input id="os_fancybox-arrows-pos-no" type="radio" name="os_fancybox_arrows_pos" value="0" />
                                <label for="os_fancybox-arrows-pos-yes" data-value="Inside">Inside</label>
                                <label for="os_fancybox-arrows-pos-no" data-value="Outside">Outside</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_CLOSE_BUTTON_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-button-yes" type="radio" name="close_button" value="1" <?php echo $close_button?'checked':''?>/>
                                <input id="os_fancybox-button-no" type="radio" name="close_button" value="0" <?php echo $close_button?'':'checked'?>/>
                                <label for="os_fancybox-button-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-button-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_NEXT_CLICK_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-next-yes" type="radio" name="next_click" value="1" <?php echo $next_click?'checked':''?>/>
                                <input id="os_fancybox-next-no" type="radio" name="next_click" value="0" <?php echo $next_click?'':'checked'?>/>
                                <label for="os_fancybox-next-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-next-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_MOUSE_WHEEL_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-mouse-yes" type="radio" name="mouse_wheel" value="1" />
                                <input id="os_fancybox-mouse-no" type="radio" name="mouse_wheel" value="0" checked />
                                <label for="os_fancybox-mouse-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-mouse-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_AUTOPLAY_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-autoplay-yes" type="radio" name="os_fancybox_autoplay" value="1" <?php echo $os_fancybox_autoplay?'checked':''?>/>
                                <input id="os_fancybox-autoplay-no" type="radio" name="os_fancybox_autoplay" value="0" <?php echo $os_fancybox_autoplay?'':'checked'?>/>
                                <label for="os_fancybox-autoplay-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-autoplay-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div class="autoplay-helper-block">
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_OS_FANCYBOX_AUTOPLAY_SPEED_LABEL")?></span>
                        <span class="sett-col-2">
                            <input type="text" name="autoplay_speed" value="<?php echo $autoplay_speed?>"/>
                        </span>
                    </div>
                </div>
<!-- WATERMARK -->
                <div id="watermark-settings" class="tab-pane fade osg-pro-avaible">
                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-watermark-yes" type="radio" name="watermark_enable" value="1" <?php echo $watermark_enable?'checked':''?>/>
                                <input id="os_fancybox-watermark-no" type="radio" name="watermark_enable" value="0" <?php echo $watermark_enable?'':'checked'?>/>
                                <label for="os_fancybox-watermark-yes" data-value="Yes">Yes</label>
                                <label for="os_fancybox-watermark-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_TYPE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="os_fancybox-watermark-image" type="radio" name="watermark_type" value="1" checked/>
                                <input id="os_fancybox-watermark-text" type="radio" name="watermark_type" value="0" />
                                <label for="os_fancybox-watermark-image" data-value="Image">Image</label>
                                <label for="os_fancybox-watermark-text" data-value="Text">Text</label>
                            </div>
                        </span>
                    </div>

                    <div id="watermark-image-block" <?php echo $watermark_type?'':'style="display:none;"'?>>
                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_SELECT_LABEL")?></span>
                            <span class="sett-col-2">
                                <div class="file-upload">
                                    <button class="file-upload-button" type="button">Select</button>
                                    <div class="none-upload"><?php echo $watermark_file?$watermark_file:'No file chosen.'?></div>
                                    <input id="watermark-input" type="file" name="watermark_file" value="">
                                    <input type="hidden" name="exist_watermark_file" value="<?php echo $watermark_file?>">
                                </div>
                            </span>
                        </div>

                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_SIZE_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="number" min="5" max="100" name="watermark_size" value="<?php echo $watermark_size?>">
                            </span>
                        </div>
                    </div>
                    <div id="watermark-text-block">
                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_TEXT_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="text" name="watermark_text" value="<?php echo $watermark_text?>" maxlength="50">
                                <input type="hidden" name="exist_watermark_text" value="<?php echo $exist_watermark_text?>">
                            </span>
                        </div>

                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_FONT_SIZE_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="number" min="5" max="50" name="watermark_text_size" value="<?php echo $watermark_text_size?>">
                            </span>
                        </div>

                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_FONT_COLOR_LABEL")?></span>
                            <span class="sett-col-2">
                                <input type="text" name="watermark_text_color" value="<?php echo $watermark_text_color?>">
                            </span>
                        </div>

                        <div>
                            <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_ANGLE_LABEL")?></span>
                            <span class="sett-col-2">
                                <div class="osgallery-checkboxes-block">
                                <input id="watermark-angle-0" type="radio" name="watermark_text_angle" <?php echo(($watermark_text_angle == 0)?'checked="checked"':'')?> value="0">
                                <input id="watermark-angle-45" type="radio" name="watermark_text_angle" <?php echo(($watermark_text_angle == 45)?'checked="checked"':'')?> value="45">
                                <input id="watermark-angle-90" type="radio" name="watermark_text_angle" <?php echo(($watermark_text_angle == 90)?'checked="checked"':'')?> value="90">
                                <label for="watermark-angle-0" data-value="0">0</label>
                                <label for="watermark-angle-45" data-value="45">45</label>
                                <label for="watermark-angle-90" data-value="90">90</label>
                            </div>
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_POSITION_LABEL")?></span>
                        <span class="sett-col-2">
                            <select name="watermark_position">
                                <option <?php echo $watermark_position=="top_right"?'selected':''?> value="top_right">Top right</option>
                                <option <?php echo $watermark_position=="top_left"?'selected':''?> value="top_left">Top left</option>
                                <option <?php echo $watermark_position=="center"?'selected':''?> value="center">Center</option>
                                <option <?php echo $watermark_position=="bottom_right"?'selected':''?> value="bottom_right">Bottom right</option>
                                <option <?php echo $watermark_position=="bottom_left"?'selected':''?> value="bottom_left">Bottom left</option>
                            </select>
                            <input type="hidden" name="watermark_position_selected" value="<?php echo $watermark_position?>">
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_WATERMARK_OPACITY_LABEL")?></span>
                        <span class="sett-col-2">
                            <input type="number" min="0" max="100" name="watermark_opacity" value="<?php echo $watermark_opacity?>">
                        </span>
                    </div>
                </div>
<!-- Social Buttons Settings -->
                <div id="social-settings" class="tab-pane fade osg-pro-avaible">

                    <div> 
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_FACEBOOK_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-facebook-yes" type="radio" name="facebook_enable" value="1" />
                                <input id="social-facebook-no" type="radio" name="facebook_enable" value="0" checked/>
                                <label for="social-facebook-yes" data-value="Yes">Yes</label>
                                <label for="social-facebook-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div> 

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_GOOGLEPLUS_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-googleplus-yes" type="radio" name="googleplus_enable" value="1" />
                                <input id="social-googleplus-no" type="radio" name="googleplus_enable" value="0" checked/>
                                <label for="social-googleplus-yes" data-value="Yes">Yes</label>
                                <label for="social-googleplus-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_VKONTACTE_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-vkontacte-yes" type="radio" name="vkontacte_enable" value="1" />
                                <input id="social-vkontacte-no" type="radio" name="vkontacte_enable" value="0" checked/>
                                <label for="social-vkontacte-yes" data-value="Yes">Yes</label>
                                <label for="social-vkontacte-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_ODNOKLASSNIKI_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-odnoklassniki-yes" type="radio" name="odnoklassniki_enable" value="1" />
                                <input id="social-odnoklassniki-no" type="radio" name="odnoklassniki_enable" value="0" checked/>
                                <label for="social-odnoklassniki-yes" data-value="Yes">Yes</label>
                                <label for="social-odnoklassniki-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_TWITTER_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-twitter-yes" type="radio" name="twitter_enable" value="1" />
                                <input id="social-twitter-no" type="radio" name="twitter_enable" value="0" checked/>
                                <label for="social-twitter-yes" data-value="Yes">Yes</label>
                                <label for="social-twitter-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_PINTEREST_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-pinterest-yes" type="radio" name="pinterest_enable" value="1" />
                                <input id="social-pinterest-no" type="radio" name="pinterest_enable" value="0" checked/>
                                <label for="social-pinterest-yes" data-value="Yes">Yes</label>
                                <label for="social-pinterest-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>                    

                    <div>
                        <span class="sett-col-1"><?php echo JText::_("COM_OSGALLERY_SETTINGS_LINKEDIN_ENABLE_LABEL")?></span>
                        <span class="sett-col-2">
                            <div class="osgallery-checkboxes-block">
                                <input id="social-linkedin-yes" type="radio" name="linkedin_enable" value="1" />
                                <input id="social-linkedin-no" type="radio" name="linkedin_enable" value="0" checked/>
                                <label for="social-linkedin-yes" data-value="Yes">Yes</label>
                                <label for="social-linkedin-no" data-value="No">No</label>
                            </div>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <input type="hidden" name="option" value="com_osgallery"/>
    <input type="hidden" name="task" value="save_gallery"/>
    <input id="catOrderIds" type="hidden" name="catOrderIds" value=""/>
    <input id="galerryId" type="hidden" name="galId" value="<?php echo $galId?>"/>
    <input id="hidden-title" type="hidden" name="gallery_title" value="<?php echo $galeryTitle?>"/>
</form>

<script src="components/com_osgallery/assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="components/com_osgallery/assets/js/jquery.slider.minicolors.js" type="text/javascript"></script>
<script src="components/com_osgallery/assets/js/jquery.json.js" type="text/javascript"></script>
<script language="JavaScript">
    var galerryTrigger = true;

    //colorpicker
    jQuery("[name='watermark_text_color'], [name='load_more_background']").minicolors({
        control: "hue",
        defaultValue: "",
        format:"rgb",
        opacity: true,
        position: "top right",
        hideSpeed: 100,
        inline: false,
        theme: "bootstrap",
        change: function(value, opacity) {
          jQuery(this).attr("value",value);
        }
    });

    //fn for find position of dom obj
    function findPosY(obj) {
      var curtop = 0;
      if(obj.offsetParent){
        while(1){
          curtop+=obj.offsetTop;
          if(!obj.offsetParent){
            break;
          }
          obj=obj.offsetParent;
        }
      }else if (obj.y){
        curtop+=obj.y;
      }
      return curtop-100;
    }
  //end

    //on save
    Joomla.submitbutton = function(pressbutton) {
        if(pressbutton =='open_gallery_settings'){
            if(galerryTrigger){
                jQuery("#system-message-container").removeClass('gallery-main');
                jQuery("#system-message-container").addClass('gallery-settings');
                jQuery(".main-gallery-header a:last").tab('show');
                jQuery("#toolbar div:last button").html('Back to Gallery');
                galerryTrigger = false;
            }else{
                jQuery("#system-message-container").removeClass('gallery-settings');
                jQuery("#system-message-container").addClass('gallery-main');
                jQuery(".main-gallery-header a:first").tab('show');
                jQuery("#toolbar div:last button").html('<span class="icon-options"></span>Gallery Settings');
                galerryTrigger = true;
            }
            return;
        }else if(pressbutton!='close_gallery'){
            jQuery("#catOrderIds").val(jQuery("#osgalery-cat-tabs").sortable( "toArray" ));
            jQuery("#os-cat-tab-images div.tab-pane").each(function(index, el) {
               jQuery(this).find(".cat-img-ordering").val(jQuery(this).sortable( "toArray" ));
            });
            //check title
            if(!jQuery('#gallery-title').val()){
                window.scrollTo(0,findPosY(jQuery('#gallery-title'))-100);
                jQuery('#gallery-title').attr("placeholder", "<?php echo JText::_('Cannot be empty'); ?>");
                jQuery('#gallery-title').css("border-color","#FF0000");
                jQuery('#gallery-title').css("background","#FF0000");
                jQuery('#gallery-title').keypress(function() {
                    jQuery('#gallery-title').css("border-color","gray");
                    jQuery('#gallery-title').css("color","inherit");
                });
                return;
            } else if (jQuery('#gallery-title').val()){
                jQuery('#gallery-title').css("background", "inherit");
            }
            document.adminForm.task.value = pressbutton;
            if(pressbutton=='save_gallery'){
                if(jQuery("#new-cat-name")){
                    jQuery(".save-cat-name").trigger('click');
                }

                //grab all form data
                var formData = new FormData(jQuery("#adminForm")[0]);
                html = '<div id="gallery-waiting-spinner"><div class="gallery-wait-spinner">'+
                        'Please wait <div class="gallery-wait-bounce1"></div>'+
                        '<div class="gallery-wait-bounce2"></div>'+
                        '<div class="gallery-wait-bounce3"></div>'+
                        '</div></div>';
                jQuery("body").prepend(html);
                jQuery.ajax({
                    url: '?option=com_osgallery&task=save_gallery&format=raw',
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        jQuery("#gallery-waiting-spinner").remove();
                        try {
                            data = window.JSON.parse(data);
                        } catch (e) {
                            data.success = false;
                        }
                        if (data.success) {
                            params = window.JSON.parse(data.params);
                            jQuery("[name='exist_watermark_file']").val(params.watermark_file);
                            html = '<div class="alert alert-success">'+
                                        '<h4 class="alert-heading">Message</h4>'+
                                        '<div class="alert-message">'+data.message+'</div>'+
                                    '</div>';
                            jQuery("#system-message-container").html(html);
                            jQuery(".category-options-block").addClass("category-options-block-message");
                            setTimeout(function(){
                                jQuery("#system-message-container").empty();
                                 jQuery(".category-options-block").removeClass("category-options-block-message");
                            }, 3000);
                        }else{
                          console.log('oops');
                        }
                    }
                });
            }else{
                if(jQuery("#new-cat-name")){
                    jQuery(".save-cat-name").trigger('click');
                }
                document.adminForm.task.value = pressbutton;
                document.adminForm.submit();
            }
        }else{
            document.adminForm.task.value = pressbutton;
            document.adminForm.submit();
        }
    };

    //global counters
    var catId = <?php echo $activeIndex?>;
    var activeId = jQuery("#osgalery-cat-tabs a:first").data("cat-id");
    var galId = <?php echo $galId?>

    jQuery(".add-new-cat").click(function(event) {
        if(jQuery("#new-cat-name")){
            jQuery(".save-cat-name").trigger('click');
        }
        //native js faster
        catId++;

        //create new li
        var li = document.createElement('li');
        li.id = "order-id-"+catId;
        li.innerHTML = '<a href="#cat-'+catId+'" data-cat-id="'+catId+'">Category Title</a>'+
                        '<input type="hidden" name="category_names[]" value="'+catId+'|+|Category Title" placeholder="">'+
                        '<span class="edit-category-name"><i class="material-icons">mode_edit</i>'+
        'edit</span>'+
                        '<span class="delete-category"><i class="material-icons">delete</i></span>';
        list = document.getElementById("osgalery-cat-tabs");
        list.insertBefore(li, list.children[list.children.length-1]);

        //create new tab content
        var div = document.createElement('div');
        div.id = 'cat-'+catId;
        div.className = 'tab-pane fade';
        div.innerHTML = '<input class="cat-img-ordering" type="hidden" name="imageOrdering['+catId+']" value="">'+
                        '<input id="cat-settings-'+catId+'" type="hidden" name="catSettings['+catId+']" value="{}">';
        list = document.getElementById("os-cat-tab-images");
        list.insertBefore(div, list.children[list.children.length-1]);
        makeTabsCliked();

        //activated tab
        activeId = catId;
        jQuery("#osgalery-cat-tabs a[href='#cat-"+activeId+"'").tab('show');
        //update settings
        catSettings = window.JSON.parse('{}');
        jQuery("#cat-alias").val(catSettings.categoryAlias || '');
        jQuery("#cat-unpublish").prop("checked",!catSettings.categoryUnpublish);
        jQuery("#cat-show-title").prop("checked",catSettings.categoryShowTitle);
        jQuery("#cat-show-cat-title-caption").prop("checked",catSettings.categoryShowTitleCaption);
        jQuery(".category-options-block a:last").tab('show');

        //update settings
        imgSettings = window.JSON.parse('{}');
        jQuery("#img-title").val(imgSettings.imgTitle || '');
        jQuery("#img-alias").val(imgSettings.imgAlias || '');
        jQuery("#img-short-description").val(imgSettings.imgShortDescription || '');
        jQuery("#img-html").val(imgSettings.imgHtml || '');
        jQuery("#img-alt").val(imgSettings.imgAlt || '');
        jQuery("#img-link").val(imgSettings.imgLink || '');
        jQuery("#img-link-open").val(imgSettings.imgLinkOpen || '_blank');
        if ( imgSettings.imgHtmlShow ) {
            if (imgSettings.imgHtmlShow == "yes") 
                jQuery("#general-img-html").find('#general-img-html-yes').attr("checked",true);
            if (imgSettings.imgHtmlShow == "no") 
                jQuery("#general-img-html").find('#general-img-html-no').attr("checked",true);
        } else {
            jQuery("#general-img-html").find('#general-img-html-yes').attr("checked",true);
        }

        //reload uploder params
        uploader.setParams({
            catId: activeId,
            galId: galId
        });
        makeCatSortable();
        catSettingsFunctions();
    });

    //uploaderz`
    var uploader = new qq.FineUploader({
    /* other required config options left out for brevity */
        element: document.getElementById("fine-uploader"),
        template: 'qq-template',
        validation: {
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            sizeLimit: 10 * 1024 * 1024,
        },
        request: {
            endpoint: '<?php echo JURI::current()?>?option=com_osgallery&task=upload_images',
            params: {
              catId: activeId,
              galId: galId
            }
        },
        callbacks: {
            onComplete: function (id, filename, responseJSON) {
                if (!responseJSON.success) {
                }else{
                    //create image
                    fileName = responseJSON.file;
                    ext = responseJSON.ext;
                    imgId = responseJSON.id;
                    image = '<div id="img-'+imgId+'" class="img-block" data-image-id="'+imgId+'">'+
                              '<span class="delete-image"><i class="material-icons">close</i></span>'+
                              '<img src="<?php echo JURI::root()?>images/com_osgallery/gal-'+galId+'/thumbnail/'+fileName+ext+'" alt="'+fileName+'">'+
                              '<input id="img-settings-'+imgId+'" type="hidden" name="imgSettings['+imgId+']" value="{}">'+
                            '</div>';
                    jQuery("#cat-"+activeId).append(image);
                    makeDeleteImage();
                    if(jQuery(".qq-upload-list li").not('.qq-upload-success').length == 0){
                        setTimeout(function(){
                            uploader.clearStoredFiles();
                        }, 5000);
                    }
                    makeCatSortable();
                    imgSettingsFunctions();
                }
            }
        }
    });
//end

    //some click function
    function makeTabsCliked(){
        jQuery(".main-nav-tabs a,.settings-nav-tabs a").click(function(e){
            e.preventDefault();
            jQuery(this).tab('show');
        });

        //fn-for edit cat name
        jQuery(".edit-category-name").unbind('click');
        jQuery(".edit-category-name").click(function(event){
            if(jQuery("#new-cat-name")){
                jQuery(".save-cat-name").trigger('click');
            }
            jQuery("#osgalery-cat-tabs").sortable( "disable" );
            //short selectors
            li = jQuery(this).parent();
            a = li.find("a");
            li.children().hide();

            //add some tools for type new name
            li.append('<input id="new-cat-name" class="edit-cat-name" type="text" '+
                    'name="save_image" placeholder="type smth..." value="'+a.text()+'">'+
                    '<span class="save-cat-name edit-cat-name">Save</span>');
            //focus on input last symbol
            jQuery("#new-cat-name").focus();
            temp=jQuery("#new-cat-name").val();
            jQuery("#new-cat-name").val('');
            jQuery("#new-cat-name").val(temp);

            //save new name
            jQuery(".save-cat-name").click(function(event) {
                a.text(jQuery("#new-cat-name").val());
                li.find("input:not(#new-cat-name)").val(a.data("cat-id")+'|+|'+jQuery("#new-cat-name").val());
                jQuery(".edit-cat-name").remove();
                li.children().show();
                jQuery("#osgalery-cat-tabs").sortable( "enable" );
            });

            //esc
            jQuery(document).keyup(function(e) {
                if (e.keyCode == 27) { // escape key maps to keycode `27`
                    jQuery(".edit-cat-name").remove();
                    li.children().show();
                    jQuery("#osgalery-cat-tabs").sortable( "enable" );
                }
            });

            //endter
            jQuery(document).keypress(function(e) {
                if(e.which == 13) {
                    jQuery(".save-cat-name").trigger( "click" );
                }
            });
        });

        //fn-s for delete cat with photos // we will delete photos later after save // maybe add restore button
        jQuery(".delete-category").click(function(event) {
            if(jQuery("#osgalery-cat-tabs li").length == 1){
                html = '<div class="alert alert-error">'+
                            '<h4 class="alert-heading">Message</h4>'+
                            '<div class="alert-message">You must have at list 1 category!</div>'+
                        '</div>';
                jQuery("#system-message-container").html(html)
                setTimeout(function(){
                  jQuery("#system-message-container").empty();
                }, 5000);
                return;
            }
            li = jQuery(this).parent();
            a = li.find("a");
            catId = a.data("cat-id");
            jQuery("#adminForm").append('<input type="hidden" name="deletedCatIds[]" value="'+catId+'">')
            jQuery(li).fadeOut(500, function(){ jQuery(this).remove();});
            jQuery("#cat-"+catId).fadeOut(500, function(){ jQuery(this).remove();});
            //activated 1st tab// if we delete current 1-st tab
            if(activeId == catId){
                //show first
                jQuery("#osgalery-cat-tabs a:first").tab('show');
                //get new activeId
                activeId = jQuery("#osgalery-cat-tabs a:first").data("cat-id");

                //reload uploder params
                uploader.setParams({
                    catId: activeId,
                    galId: galId
                })
            }
        });
    }

    function parceOptions(string){
        try{
            string = decodeURI(string);
            return window.JSON.parse(string);
        }catch(err){
            return window.JSON.parse('{}');
        }
    }

    //function for make category tab and images sortable
    function makeCatSortable(){
        jQuery( "#osgalery-cat-tabs" ).sortable({
            handle: 'a',
            axis: "x",
            items: "> li"
        });

        jQuery("#os-cat-tab-images div").sortable({
            cancel: null, // Cancel the default events on the controls
            helper: "clone",
            revert: true,
            tolerance: "intersect",
            handle: 'img',
            items: "> .img-block"
        });
    }
    //cat settings functions
    function catSettingsFunctions(){
        //initialise first tab settings
        var catSettings = parceOptions(jQuery("#cat-settings-"+activeId).val());
        jQuery("#cat-alias").val(catSettings.categoryAlias || '');
        jQuery("#cat-unpublish").prop("checked",!catSettings.categoryUnpublish);
        jQuery("#cat-show-title").prop("checked",catSettings.categoryShowTitle);
        jQuery("#cat-show-cat-title-caption").prop("checked",catSettings.categoryShowTitleCaption);
        //end

        //change cat click function
        jQuery(".cat-nav-tabs a").click(function(e){
            e.preventDefault();
            jQuery(this).tab('show');
            jQuery(".category-options-block a:last").tab('show');
            activeId = jQuery(this).data("cat-id");
            //reload uploder params
            uploader.setParams({
                catId: activeId,
                galId: galId
            })

            //update settings
            catSettings = parceOptions(jQuery("#cat-settings-"+activeId).val());
            if(Object.keys(catSettings).length == 0 ) {
                jQuery("#cat-alias").val('');
                jQuery("#cat-unpublish").prop("checked",true);
                jQuery("#cat-show-title").prop("checked",true);
                jQuery("#cat-show-cat-title-caption").prop("checked",true);

            }else {
                jQuery("#cat-alias").val(catSettings.categoryAlias || '');
                jQuery("#cat-unpublish").prop("checked",!catSettings.categoryUnpublish);
                jQuery("#cat-show-title").prop("checked",catSettings.categoryShowTitle);
                jQuery("#cat-show-cat-title-caption").prop("checked",catSettings.categoryShowTitleCaption);
            }
        });
        //end

        //change options // maybe need improve on save. // now we save every option immediately when change value
        jQuery("#cat-layout, #cat-unpublish, #cat-show-title, #cat-show-cat-title-caption,#cat-alias").on('customCat', function (e) {
            //get params from jsonString
            catSettings = parceOptions(jQuery("#cat-settings-"+activeId).val());
            catSettings.categoryAlias = checkSpecialChar(jQuery("#cat-alias").val());
            catSettings.categoryUnpublish = !jQuery("#cat-unpublish").prop("checked");
            catSettings.categoryShowTitle = jQuery("#cat-show-title").prop("checked");
            catSettings.categoryShowTitleCaption = jQuery("#cat-show-cat-title-caption").prop("checked");

            //set params to Json
            jQuery("#cat-settings-"+activeId).val(encodeURI(window.JSON.stringify(catSettings)));
        });

        jQuery("#cat-layout, #cat-unpublish, #cat-show-title, #cat-show-cat-title-caption").change(function(event) {
            jQuery(this).trigger( "customCat");
        });
        jQuery("#cat-alias").on('input', function (e) {
            jQuery(this).trigger( "customCat");
        });
        //end
    }

    function checkSpecialChar(string){
        return string.replace(new RegExp('\\<', 'ig'),'').replace(new RegExp('\\>', 'ig'),'').replace(new RegExp('\\:', 'ig'),'');
    }

    // function escapeHtml(text) {
    //     return text
    //         .replace(/&/g, "&amp;")
    //         .replace(/</g, "&lt;")
    //         .replace(/>/g, "&gt;")
    //         .replace(/"/g, "&quot;")
    //         .replace(/'/g, "&#039;");
    // }

    ///img settings function
    function imgSettingsFunctions(){
        //change img click function
        jQuery("#os-cat-tab-images div[id^='img-']").click(function(e){
            jQuery("#os-cat-tab-images div[id^='img-']").removeClass('active-img-block');
            jQuery(this).addClass('active-img-block');
            jQuery(".category-options-block a:first").tab('show');
            imageId = jQuery(this).data("image-id");

            //update settings
            imgSettings = parceOptions(jQuery("#img-settings-"+imageId).val());
            jQuery("#img-title").val(imgSettings.imgTitle || '');
            jQuery("#img-alias").val(imgSettings.imgAlias || '');

            jQuery("#img-short-description").val(imgSettings.imgShortDescription || '');
            if (typeof(imgSettings.imgHtml) === "object" && imgSettings.imgHtml.html !== undefined ) 
                jQuery("#img-html").val(imgSettings.imgHtml.html);
            else 
                jQuery("#img-html").val('');
            jQuery("#img-alt").val(imgSettings.imgAlt || '');
            jQuery("#img-link").val(imgSettings.imgLink || '');
            jQuery("#img-link-open").val(imgSettings.imgLinkOpen || '_blank');
            if ( imgSettings.imgHtmlShow ) {
                if (imgSettings.imgHtmlShow == "yes") 
                    jQuery("#general-img-html").find('#general-img-html-yes').attr("checked",true);
                if (imgSettings.imgHtmlShow == "no") 
                    jQuery("#general-img-html").find('#general-img-html-no').attr("checked",true);
            } else {
                jQuery("#general-img-html").find('#general-img-html-yes').attr("checked",true);
            }

            //change options // maybe need improve on save. // now we save every option immediately when change value
            jQuery("#img-title, #img-alias, #img-short-description,"+
                    " #img-alt, #img-link, #img-link-open, #img-html, #general-img-html").on('customImg', function (e) {
                //get params from jsonString
                imgSettings = parceOptions(jQuery("#img-settings-"+imageId).val());
                imgSettings.imgTitle = checkSpecialChar(jQuery("#img-title").val());
                imgSettings.imgAlias = checkSpecialChar(jQuery("#img-alias").val());

                imgSettings.imgShortDescription = checkSpecialChar(jQuery("#img-short-description").val());
                imgSettings.imgHtml = {};
                
                if (jQuery("#img-html").val() || jQuery("#img-html").val() == '')
                    imgSettings.imgHtml.html = jQuery("#img-html").val();
                else
                    imgSettings.imgHtml.html = jQuery.quoteString(jQuery("#img-html").val());
                
                imgSettings.imgAlt = checkSpecialChar(jQuery("#img-alt").val());
                imgSettings.imgLink = jQuery("#img-link").val();
                imgSettings.imgLinkOpen = jQuery("#img-link-open").val();
                jQuery("#img-settings-"+imageId).val(encodeURI(window.JSON.stringify(imgSettings)));
            });

            jQuery("#img-link-open").change(function(event) {
                jQuery(this).trigger( "customImg");
            });
            jQuery("#img-title, #img-alias, #img-short-description, #img-alt, #img-link, #img-html, #general-img-html").on('input', function (e) {
                jQuery(this).trigger( "customImg");
            });
            //end
        });
        //end
    }

    function optionsClickFunctions(){
        if(jQuery("input[name='watermark_type']").prop('checked')){
            jQuery("#watermark-image-block").show();
            jQuery("#watermark-text-block").hide();
        }else{
            jQuery("#watermark-image-block").hide();
            jQuery("#watermark-text-block").show();
        }
        jQuery("input[name='watermark_type']").change(function(event) {
            if(jQuery(this).val() == 1){
                jQuery("#watermark-image-block").show();
                jQuery("#watermark-text-block").hide();
            }else{
                jQuery("#watermark-image-block").hide();
                jQuery("#watermark-text-block").show();
            }
        });

        if(jQuery("input[name='showLoadMore']").prop('checked')){
            jQuery("#load-more-block").hide();
            jQuery("#load-more-block").show();
        }else{
            jQuery("#load-more-block").show();
            jQuery("#load-more-block").hide();
        }
        jQuery("input[name='showLoadMore']").change(function(event) {
            if(jQuery(this).val() == 0){
                jQuery("#load-more-block").show();
                jQuery("#load-more-block").hide();
            }else{
                jQuery("#load-more-block").hide();
                jQuery("#load-more-block").show();
            }
        });

        jQuery("[name='os_fancybox_arrows']").change(function(event) {
            if(jQuery(this).val() == 1){
                jQuery(".os_fancybox-arrows-pos-block").show("slow");
            }else{
                jQuery(".os_fancybox-arrows-pos-block").hide("slow");
            }
        });

        jQuery("[name='prev_next_effect']").change(function(event) {
            if(jQuery(this).val() != 'none'){
                jQuery(".os-fancybox-prev-next-speed-block").show("slow");
            }else{
                jQuery(".os-fancybox-prev-next-speed-block").hide("slow");
            }
        });
    }

    function makeDeleteImage(){
        jQuery(".delete-image").click(function(event) {
            imgBlock = jQuery(this).parent();
            imgId = jQuery(imgBlock).data("image-id");
            jQuery(imgBlock).fadeOut(600, function(){ jQuery(this).remove();});
            jQuery("#adminForm").append('<input type="hidden" name="deletedImgIds[]" value="'+imgId+'">')
        });
    }

    function addCheckedToMainImgCheckbox () {
        jQuery(".img-html-show input").click(function(e) {
            var id = jQuery(this).attr('id');
            var imageId = jQuery('.active-img-block').data("image-id");
            var imgSettings = parceOptions(jQuery("#img-settings-"+imageId).val());

            if (id == 'general-img-html-yes') {
                jQuery(this).attr('checked', 'checked');
                jQuery(".img-html-show").find('#general-img-html-no').attr("checked",false);
                imgSettings.imgHtmlShow = "yes";
                jQuery("#img-settings-"+imageId).val(encodeURI(window.JSON.stringify(imgSettings)));
            } else {
                jQuery(this).attr('checked', 'checked');
                jQuery(".img-html-show").find('#general-img-html-yes').attr("checked",false);
                imgSettings.imgHtmlShow = "no";
                jQuery("#img-settings-"+imageId).val(encodeURI(window.JSON.stringify(imgSettings)));
            }
        });
    }

    jQuery(document).ready(function(){
        makeTabsCliked();
        makeCatSortable();
        makeDeleteImage();
        catSettingsFunctions();
        imgSettingsFunctions();
        optionsClickFunctions();
        addCheckedToMainImgCheckbox();
        jQuery("#osgalery-cat-tabs a:first,#osgalery-cat-tabs a:first,"+
                ".category-options-block a:last-child,.main-gallery-header a:first,.settings-nav-tabs a:first").tab('show');
        jQuery("#watermark-input").change(function(event) {
            var filename = jQuery('#watermark-input').val().replace(/C:\\fakepath\\/i, '')
            jQuery(".none-upload").html(filename);
        });

        if(jQuery("[name='galleryLayout']").val() == "allInOne" || jQuery("[name='galleryLayout']").val() == "albumMode") {
            jQuery(".cat-show-title-block").hide();
        }else{
            jQuery(".cat-show-title-block").show();
        }
        
        jQuery("[name='galleryLayout']").change(function(event) {
            if(jQuery(this).val() != "defaultTabs"){
                jQuery(".cat-show-title-block").hide();
            }else{
                jQuery(".cat-show-title-block").show();
            }
        });        
        jQuery("[name='galleryLayout']").change(function(event) {
            if(jQuery(this).val() == "allInOne") {
                // add load more settings
                jQuery(".load-more-show-button").hide(1100);
                jQuery(".load-more-button-text").hide(800);
                jQuery(".load_more_background").hide(400);
                jQuery(".load-more-number-images").hide();
                jQuery(".load-more-number-images").find('input').attr('disabled','disabled');
            } else {
                jQuery(".cat-show-title-block").show();
                // add load more settings
                jQuery(".load-more-show-button").show(500);
                jQuery(".load-more-button-text").show(800);
                jQuery(".load_more_background").show(400);
                jQuery(".load-more-number-images").show();
            }
        });
        jQuery(".gallery-layout").change(function(event) {
            if(jQuery(this).val() == "albumMode"){
                jQuery(".back-button-text-block").show("slow");
            }else{
                jQuery(".back-button-text-block").hide("slow");
            }
        });        
        jQuery(".gallery-layout").change(function(event) {
            if(jQuery(this).val() == "masonry"){
                jQuery("#osgallery-checkboxes-block-general").hide("slow");
                jQuery("#minImgSize").hide("slow");
                jQuery("#imgWidth").hide("slow");
                jQuery("#imgHeight").hide("slow");
                jQuery("#masonryLayout").show("slow");
            }else{
                jQuery("#minImgSize").show("slow");
                jQuery("#imgWidth").show("slow");
                jQuery("#imgHeight").show("slow");
                jQuery("#masonryLayout").hide("slow");
            }
        });

       
            jQuery('.osg-pro-avaible, .osg-pro-avaible-string').prop('disabled', 'disabled');
            jQuery('.osg-pro-avaible *, .osg-pro-avaible-string *').prop('disabled', 'disabled');
        
        jQuery(".gallery-layout").change(function(event) {
            if(jQuery(this).val() == "fit_rows"){
                jQuery("#osgallery-checkboxes-block-general").hide("slow");
                jQuery("#minImgSize").hide("slow");
                jQuery("#imgWidth").hide("slow");
                jQuery("#imgHeight").hide("slow");
            }
        });
        jQuery("#system-message-container").addClass('gallery-main');
    });

     jQuery(window).scroll(function(){
        if(jQuery(window).scrollTop() >= 47) {
           jQuery(".category-options-block").addClass("category-options-block-fixed");
          } else {
           jQuery(".category-options-block").removeClass("category-options-block-fixed");
         }
    });

</script>