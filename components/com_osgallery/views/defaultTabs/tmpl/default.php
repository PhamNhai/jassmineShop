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
?>
    <div class="os-gallery-tabs-main-<?php echo $galId?>">
        <ul class="osgalery-cat-tabs">
            <?php
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
                ?>
                <li <?php echo (!$currentCatParams->get("categoryShowTitle",true))?'style="display:none;"':''?>>
                    <a href="#cat-<?php echo $catId?>" data-cat-id="<?php echo $catId?>" data-end="<?php echo $numberImages?>"><?php echo $catName?></a>
                </li>
            <?php
            } ?>
        </ul>

        <div class="os-cat-tab-images">
            <?php
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
                ?>
                <!-- Simple category mode-->
                <div id="cat-<?php echo $catId?>" data-cat-id="<?php echo $catId?>" <?php echo $styleCat?> >
                    <?php
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
                            <div class="img-block <?php echo $imageHover ?>-effect" <?php echo $styleImg ?> >

                                <a class="os_fancybox-<?php echo $catId?>"
                                    id="os_image_id-<?php echo $image->id ?>"
                                    rel="group"
                                    target="<?php echo $imgOpen?>"
                                    <?php echo $imgLink?>
                                    href="<?php if (isset($imgHtml->html) && $imgHtml->html != '') {
                                            print '#video-' .  $image->id;
                                        } else {
                                            print $imgLink;
                                        }?>"
                                    data-os_fancybox-title="<?php $showImgAlias ? print $imgAlias : print ''?>"
                                    data-os_fancybox-alias="<?php echo $fancAlias ? $fancAlias : ''?>"
                                    >
                                    <div class="os-gallery-caption">
                                        <?php
                                            if($imgTitle) {
                                                echo "<h3 class='os-gallery-img-title'>$imgTitle</h3>";
                                            }
                                            if($catName && $currentCatParams->get("categoryShowTitleCaption",1)) {
                                                echo "<p class='os-gallery-img-category'>$catName</p>";
                                            }
                                            if($imgShortDesc) {
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
                                            <div id="text-<?php echo $image->id ?>" >

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
                    }?>
                </div>
                <!-- END simple mod-->
            <?php
            } ?>
        </div>

        <?php if ($showLoadMore) { ?>
        <div class="osGallery-button-box">
            <button id="load-more-<?php echo $galId?>" class="load-more-button" style="background:<?php echo $load_more_background; ?>"> 
                    <?php echo $loadMoreButtonText; ?>                
            </button>
        </div>
        <?php } 
        ?>

        <script>
            (function () {
                var osGallery<?php echo $galId?> = function (container, params) {
                    if (!(this instanceof osGallery<?php echo $galId?>)) return new osGallery<?php echo $galId?>(container, params);

                    var defaults = {
                        minImgEnable : 1,
                        spaceBetween: 2.5,
                        minImgSize: 200,
                        numColumns: 3,
                        fancSettings:{
                            wrapCSS: 'os-os_fancybox-window',
                            openEffect: '',
                            openSpeed: 500,
                            closeEffect: '',
                            closeSpeed: 500,
                            prevEffect: '',
                            nextEffect: '',
                            nextSpeed: 800,
                            prevSpeed: 800,
                            loop: 0,
                            closeBtn: 1,
                            arrows: 1,
                            arrowsPosition: 1,
                            nextClick: 0,
                            mouseWheel: 0,
                            autoPlay: 0,
                            playSpeed: 3000,
                            buttons: new Array(),
                            downloadButton: 0                        
                        }
                    };

                    for (var param in defaults) {
                      if (!params[param] && params[param] != 0){
                        params[param] = defaults[param];
                      }
                    }
                    // gallery settings
                    var osg = this;
                    // Params
                    osg.params = params || defaults;

                    osg.getImgBlockWidth = function (numColumns){
                        if(typeof(numColumns) == 'undefined')numColumns = osg.params.numColumns;
                        spaceBetween = osg.params.spaceBetween*2;
                        mainBlockW = jQuerGall(container).width();
                        imgBlockW = ((((mainBlockW-(spaceBetween*numColumns))/numColumns)-1)*100)/mainBlockW;
                        if(osg.params.minImgEnable){
                            if(((imgBlockW*mainBlockW)/100) < osg.params.minImgSize){
                                numColumns--;
                                osg.getImgBlockWidth(numColumns);
                            }
                        }

                        var sizeAwesome = ((imgBlockW*mainBlockW)/100)/11+"px";
                        jQuerGall(container +" .andrea-effect .andrea-zoom-in").css({'width': sizeAwesome, 'height': sizeAwesome });

                        var fontSizetext = ((imgBlockW*mainBlockW)/100)/15+"px";
                        jQuerGall(container +" .img-block").css({'font-size': fontSizetext, 'line-height': fontSizetext });

                        return imgBlockW;
                    }

                    //initialize function
                    osg.init = function (){
                        imgBlockW = osg.getImgBlockWidth();
                        jQuerGall(container+" .img-block").css("width",imgBlockW+"%");

                        jQuerGall(container+" .os-cat-tab-images div[id^='cat-']").each(function(index, el) {
                            catId = jQuerGall(this).data("cat-id");
                            if(catId){

                                jQuerGall(this).find(".os_fancybox-"+catId ).os_fancybox({
                                    beforeShow: function(){
                                        
                                        if(osg.params.fancSettings.arrows && osg.params.fancSettings.arrowsPosition == 0 
                                            && !jQuerGall(".gallery-fanc-next").length ){
                                            jQuerGall(".os_fancybox-nav.os_fancybox-prev,.os_fancybox-nav.os_fancybox-next").remove();
                                            html = '<span title="Previous" class="gallery-fanc-next" style="width: 10%" href="javascript:;"><span></span></span>';
                                            html+= '<span title="Next" class="gallery-fanc-prev" style="width: 10%" href="javascript:;"><span></span></span>';
                                            jQuerGall("body").prepend(html);
                                        }   
                                    },
                                    beforeClose: function(){
                                        var href = window.location.href;
                                        jQuerGall(".gallery-fanc-next,.gallery-fanc-prev").remove();
                                        if (href.indexOf('&os_image_id') > -1)
                                            history.pushState (href, null, href.substring(0, href.indexOf('&os_image_id')));
                                        else 
                                            history.pushState (href, null, href.substring(0, href.indexOf('?os_image_id')));
                                    },
                                    beforeLoad: function() {
                                        var id = this.element.attr('id');
                                        var href = window.location.href;

                                        if (href.indexOf('&os_image_id') > -1) 
                                            history.pushState (null, null, href.substring(0, href.indexOf('&os_image_id') )+ "&" + id);
                                        else if (href.indexOf('?os_image_id') > -1) 
                                            history.pushState (href, null, href.substring(0, href.indexOf('?os_image_id')) + "?" + id);
                                        else if (href.indexOf('?') > -1 && href.indexOf('&') > -1 && href.indexOf('&os_image_id') == -1)
                                            history.pushState(null, null, href + '&' + id);
                                        else if ( href.indexOf('&') == -1 && href.indexOf('?os_image_id') == -1)
                                            history.pushState(null, null, href + '?' + id);

                                    },
                                    afterShow: function() {

                                        var button = osg.params.fancSettings.buttons;
                                        var id = this.element.attr('id');
                                        var naturalWidth = jQuerGall("#img-" + id.split('-')[1]).prop('naturalWidth') ;
                                        var fancAlias = this.element.attr('data-os_fancybox-alias');
                                        var divText = jQuerGall("#text-" + id.split('-')[1]);
                                        var dataHtml = jQuerGall("#data-html-" + id.split('-')[1]).html();

                                        if (fancAlias !== '') {
                                            var textSpan = jQuerGall(".os_fancybox-title span");
                                            if (textSpan.length > 0) {
                                                jQuerGall(".os_fancybox-title span").text(fancAlias);
                                            } else {
                                                jQuerGall(".os_fancybox-title").text(fancAlias);
                                            }
                                        }

                                        divText.css({'width': naturalWidth, 'max-width': '100%'} ).html(dataHtml);

                                        jQuerGall(".os_fancybox-skin .os_fancybox-outer .os_fancybox-inner").each(function(){
                                                
                                            if (!jQuerGall.isEmptyObject(button)) {

                                                jQuerGall(this).css('position', 'relative');
                                                jQuerGall(this).append('<div id="sharing'+ id.split('-')[1] + '" class="sharing"><div class="icons_os" id="icons_os'
                                                    + id.split('-')[1]+'"></div><div class="icons_os_image"></div>'
                                                    + '</div>');

                                                for (var i = 0; i < button[id].length; i++) {
                                                    jQuerGall("#icons_os"+id.split('-')[1]).append(button[id][i]);
                                                };

                                                jQuerGall('[id^=icons_os] a').each(function(){
                                                    jQuerGall(this).on('click', function(e){
                                                        e.preventDefault();
                                                        window.open(this.href, 'default', "height=800,width=600");
                                                    });
                                                });

                                                jQuerGall(".icons_os_image", this).on('click', function(event){

                                                    event.stopPropagation();

                                                    if (jQuerGall(this).parent().find('.icons_os a').hasClass("zoomIn")) {
                                                        jQuerGall(this).parent().find('.icons_os a').removeClass("zoomIn");
                                                        jQuerGall(this).parent().find('.icons_os a').addClass("zoomOut");
                                                    } else {
                                                        jQuerGall(this).parent().find('.icons_os a').removeClass("zoomOut");
                                                        jQuerGall(this).parent().find('.icons_os a').addClass("zoomIn");
                                                    }
                                                });
                                            }

                                            if ( osg.params.fancSettings.downloadButton === 1) {
                                                if (jQuerGall('#sharing' + id.split('-')[1]).length === 0) {
                                                    jQuerGall(this).css('position', 'relative');
                                                    jQuerGall(this).append('<div id="sharing'+ id.split('-')[1] + '" class="sharing"></div>');
                                                } 
                                                jQuerGall('#sharing' + id.split('-')[1]).append(
                                                    '<a href="' + jQuerGall("img", this).attr("src") 
                                                    + '" download>'
                                                    + '<div class="icons_download">' 
                                                    + '</div>' 
                                                    + '</a>');
                                                if (jQuerGall.isEmptyObject(button)) {
                                                    jQuerGall('.icons_download').css('top', '5px');
                                                }
                                            }

                                        });

                                        jQuerGall(".gallery-fanc-next,.gallery-fanc-prev").on('click', function(){
                                            jQuerGall(".gallery-fanc-next,.gallery-fanc-prev").unbind();
                                        });                            
                                    },
                                    wrapCSS    : osg.params.fancSettings.wrapCSS,
                                    openEffect : osg.params.fancSettings.openEffect,
                                    openSpeed  : osg.params.fancSettings.openSpeed,

                                    closeEffect : osg.params.fancSettings.closeEffect,
                                    closeSpeed  : osg.params.fancSettings.closeSpeed,

                                    prevEffect : osg.params.fancSettings.prevEffect,
                                    nextEffect : osg.params.fancSettings.nextEffect,

                                    nextSpeed: osg.params.fancSettings.nextSpeed,
                                    prevSpeed: osg.params.fancSettings.prevSpeed,

                                    loop: osg.params.fancSettings.loop,
                                    closeBtn: osg.params.fancSettings.closeBtn,
                                    arrows: osg.params.fancSettings.arrows,
                                    arrowsPosition: osg.params.fancSettings.arrowsPosition,
                                    nextClick: osg.params.fancSettings.nextClick,
                                    mouseWheel: osg.params.fancSettings.mouseWheel,
                                    autoPlay: osg.params.fancSettings.autoPlay,
                                    playSpeed: osg.params.fancSettings.playSpeed,

                                    helpers : {
                                        <?php echo $os_fancybox_title?>
                                        <?php echo $os_fancybox_background?>
                                        <?php echo $helper_buttons?>
                                        <?php echo $helper_thumbnail?>
                                    },
                                });
                            }
                        });


                        jQuerGall(container+" .os-cat-tab-images div:first-child").show();
                        jQuerGall(container+" .osgalery-cat-tabs li:first-child a").addClass("active");
                        var curCatId = jQuerGall(container+" .osgalery-cat-tabs a.active").attr('data-cat-id');
                        var curEnd = jQuerGall(container+" .osgalery-cat-tabs a.active").attr('data-end');
                        jQuerGall("#load-more-<?php echo $galId?>").attr('data-cat-id', curCatId);
                        jQuerGall("#load-more-<?php echo $galId?>").attr('data-end', curEnd);

                        jQuerGall(container+" .osgalery-cat-tabs a").click(function(e) {
                            e.preventDefault();
                            jQuerGall('li a').removeClass("active");
                            jQuerGall(container+" .os-cat-tab-images>div").hide();
                            jQuerGall(this).addClass("active");
                            curCatId = jQuerGall(container+" .osgalery-cat-tabs a.active").attr('data-cat-id');
                            jQuerGall("#load-more-<?php echo $galId?>").attr('data-cat-id', curCatId);
                            curEnd = jQuerGall(container+" .osgalery-cat-tabs a.active").attr('data-end');
                            if(curEnd != -1)
                                jQuerGall("#load-more-<?php echo $galId?>").removeAttr("disabled");
                            jQuerGall("#load-more-<?php echo $galId?>").attr('data-end', curEnd);
                            jQuerGall(jQuerGall(this).attr("href")).fadeTo(500, 1);

                        });

                        osg.resizeGallery = function (){
                            imgBlockW = osg.getImgBlockWidth();
                            jQuerGall(container+" .img-block").css("width",imgBlockW+"%");
                        }

                        jQuerGall(window).resize(function(event) {
                            osg.resizeGallery();
                        });
                    }
                    osg.loadMore = function() {

                    }
                    osg.init();
                }
                window.osGallery<?php echo $galId?> = osGallery<?php echo $galId?>;
            })();
            jQuerGall(window).on('load',function($) {
                var gallery= new osGallery<?php echo $galId?>(".os-gallery-tabs-main-<?php echo $galId?>",{
                    minImgEnable : <?php echo $minImgEnable?>,
                    spaceBetween: <?php echo $imageMargin?>,
                    minImgSize: <?php echo $minImgSize?>,
                    numColumns: <?php echo $numColumns?>,
                    fancSettings:{
                        wrapCSS: 'os-os_fancybox-window',
                        openEffect: "<?php echo $open_close_effect?>",
                        openSpeed: <?php echo $open_close_speed?>,
                        closeEffect: "<?php echo $open_close_effect?>",
                        closeSpeed: <?php echo $open_close_speed?>,
                        prevEffect: "<?php echo $prev_next_effect?>",
                        nextEffect: "<?php echo $prev_next_effect?>",
                        nextSpeed: <?php echo $prev_next_speed?>,
                        prevSpeed: <?php echo $prev_next_speed?>,
                        loop: <?php echo $loop?>,
                        closeBtn: <?php echo $close_button?>,
                        arrows: <?php echo $os_fancybox_arrows?>,
                        arrowsPosition: <?php echo $os_fancybox_arrows_pos?>,
                        nextClick: <?php echo $next_click?>,
                        mouseWheel: <?php echo $mouse_wheel?>,
                        autoPlay: <?php echo $os_fancybox_autoplay?>,
                        playSpeed: <?php echo $autoplay_speed?>,
                        buttons: new Array(),
                        downloadButton: <?php echo $downloadButton ?>
                    }
                });

                // add load more script
                var limEnd = <?php echo $numberImages?>;
                jQuerGall("#load-more-<?php echo $galId?>").on("click", function() {
                    var path = "index.php?option=com_osgallery&format=raw";
                    jQuerGall.post(
                        path,
                        {
                            task : "loadMoreButton",
                            Itemid: '<?php echo $itemId ?>',
                            end : jQuerGall("#load-more-<?php echo $galId?>").attr('data-end'),
                            catId : jQuerGall("#load-more-<?php echo $galId?>").attr('data-cat-id'),
                            galId : <?php echo $galId?>
                        },
                        function(data) {
                            if(data.success){
                                jQuerGall("#cat-"+data.catId).append(data.html);
                                jQuerGall("#cat-"+ data.catId+ ", #load-more-<?php echo $galId?>").attr('data-end', data.limEnd);
                                if(data.limEnd == -1)
                                    jQuerGall("#load-more-<?php echo $galId?>").attr("disabled","disabled");
                                limEnd = data.limEnd;
                                gallery.resizeGallery();
                            }
                        }, 'json');
                });
                // end load more script

                // add social sharing script
                var href = window.location.href;
                var img_el_id = '';
                var pos = href.indexOf('os_image_id'); 
                if (pos > -1) 
                    img_el_id = href.substring(pos);

                                        
                if(img_el_id && img_el_id.indexOf('os_image_id') > -1)  {
                     if(document.getElementById(img_el_id) !== null){
                         jQuerGall('#' + img_el_id).trigger('click');
                     }
                }
                // end sharing script              
            });
        </script>
        <noscript>Javascript is required to use OS Responsive Image Gallery<a href="http://ordasoft.com/os-responsive-image-gallery" title="OS Responsive Image Gallery">OS Responsive Image Gallery</a> with awesome layouts and nice hover effects, Drag&Drop, Watermark and stunning Fancybox features. 
        Tags: <a
         href="http://ordasoft.com/os-responsive-image-gallery">responsive image gallery</a>, joomla gallery, joomla responsive gallery, best joomla  gallery, image joomla gallery, joomla gallery extension, image gallery module for joomla 3, gallery component for joomla
        </noscript>
        <div class="copyright-block">
          <a href="http://ordasoft.com/" class="copyright-link">&copy;2017 OrdaSoft.com All rights reserved. </a>
        </div>
    </div>
<?php
}