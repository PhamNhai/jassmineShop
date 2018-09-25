/**
* @package OS Touch Slider.
* @copyright 2017 OrdaSoft.
* @author 2017 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com),Sergey Buchastiy(buchastiy1989@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/



function var_dump(obj) {
  var vartext = "";
  for (var prop in obj) {
    if( isNaN( prop.toString() )) {
      vartext += "\t->"+prop+" = "+ eval( "obj."+prop.toString()) +"\n";
    }
    }
    if(typeof obj == "object") {
      return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "") + 
      "\n" + vartext;
    } else {
        return "Type: "+typeof(obj)+"\n" + vartext;
  }
}//end function var_dump

(function () {

  var osSliderSettings = function (container, params) {
    if (!(this instanceof osSliderSettings)) return new osSliderSettings(container, params);

    var defaults = {
        workImage   : '',
        currentTask : '',
        currentEditImgId : 0,
        textId : 0,
        activeTab:1,
        editTextId: -1,
        previousText : '',
        crop : 0,
        parallax : 0,
        parallaxImg : [],
        debugMode: false,
        imageWidth: 400,
        site_path : '',
        imageHeight: 200,
        imageFullTime: [],
        imageFilter: [],
        imageBackground: [],
        textStartTimes: [],
        textEndTimes: [],
        permanentStartTimes: [],
        permanentEndTimes: [],
        moduleId : 0,
        resetSpeed:false,
        lazyLoading : 0,
        lazyLoadingInPrevNext : 0,
        lazyLoadingInPrevNextAmount : 1,
        screenW : jQuerOSS(window).innerWidth(),
        screenH : jQuerOSS(window).innerHeight(),
        imageOrdering : [],
        currentTextOrderId: 0,
        textOrdering : [],
        avaibleGoogleFonts : [],
        avaibleGoogleFontsWeights : [],
        neededGoogleFonts : [],
        neededGoogleFontsWeight : [],
        setupAnimation : {},
        textAnimation : {},
        swiperSlider : {},
        ItemId : 0,
        isUser : -1,
        version : 0,
        setupFonts : ["Nixie One", "Open Sans", "Roboto", "Slabo 27px", "Lato", "Roboto Condensed", "Oswald", "Source Sans Pro",
                  "Montserrat", "Raleway", "Roboto Slab", "Lora", "PT Sans", "Josefin Sans", "Dancing Script",
                  "Satisfy", "Cookie", "Playball", "Great Vibes", "Rochester", "Lobster"]
    };

    for (var param in defaults) {
      if (!params[param]){
        params[param] = defaults[param];
      }
    }
    // slider settings
    var oss = this;

    // Params
    oss.params = params || defaults;

    //debug tip
    if(oss.params.debugMode){
      jQuerOSS(".slider-head-title").html("<span style='color:red'>!!! Debug Mode ON !!!</span>");
    }

    if(!localStorage.getItem('afterImport')){
      localStorage.clear();  
      image_copy_id='';
    }
    
    //bch

    oss.stopSlider = function(){
      if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val()>1 
      || jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()>1){
        oss.params.timer.stop();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide .slide-text").css("opacity",1);
        return true;
      }
      return false;
    }

    oss.capitalizeFirstLetter = function (string) {
      if(string == "") return string;
            return string.charAt(0).toUpperCase() + string.slice(1);
    }


    //for compatibility with more old versions (after resize refactoring)
    oss.setTextAttrValue = function(){

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.swiper-slide-duplicate) .slide-text").each(function(index, el){

          if(jQuerOSS(el).css("font-size")){
            jQuerOSS(el).attr("data-font-size",oss.toSl(jQuerOSS(el).css("font-size")));
          }
          if(jQuerOSS(el).css("border-top-width")){
            jQuerOSS(el).attr("data-border-width",oss.toSl(jQuerOSS(el).css("border-top-width")));
          }
          if(jQuerOSS(el).css("padding-top")){
            jQuerOSS(el).attr("data-padding-top",oss.toSl(jQuerOSS(el).css("padding-top")));
          }
          if(jQuerOSS(el).css("padding-right")){
            jQuerOSS(el).attr("data-padding-right",oss.toSl(jQuerOSS(el).css("padding-right")));
          }
          if(jQuerOSS(el).css("padding-bottom")){
            jQuerOSS(el).attr("data-padding-bottom",oss.toSl(jQuerOSS(el).css("padding-bottom")));
          }
          if(jQuerOSS(el).css("padding-left")){
            jQuerOSS(el).attr("data-padding-left",oss.toSl(jQuerOSS(el).css("padding-left")));
          }

          if(jQuerOSS(el).attr('data-custom-class')){
            jQuerOSS(el).addClass(jQuerOSS(el).attr('data-custom-class'));
          }


          //text-shadow add to argument start
          if(jQuerOSS(el).css("text-shadow")){
            //pixels params add to argument
            var regExpShadow = /([.\d]*px)/ig;

            var text_all_shadow = jQuerOSS(el).css("text-shadow").match(regExpShadow);

            if(Array.isArray(text_all_shadow)){
              jQuerOSS(el).attr("data-text-h-shadow",oss.toSl(text_all_shadow[0]));
              jQuerOSS(el).attr("data-text-v-shadow",oss.toSl(text_all_shadow[1]));
              jQuerOSS(el).attr("data-text-blur-radius",oss.toSl(text_all_shadow[2]));
            }else{
              jQuerOSS(el).attr("data-text-h-shadow",'0');
              jQuerOSS(el).attr("data-text-v-shadow",'0');
              jQuerOSS(el).attr("data-text-blur-radius",'0');
            }

            //color params add to argument
            var regExpShadow = /([rgba]*[(]*[\d.,\s]*[)]+)/;
            var text_shadow = jQuerOSS(el).css('text-shadow').match(regExpShadow);

            if(Array.isArray(text_shadow)){
              jQuerOSS(el).attr("data-text-shadow-colorpicker",text_shadow[0]);
            }else{
              jQuerOSS(el).attr("data-text-shadow-colorpicker",'rgba(255,255,255,1)');
            }
    
          }
          //text-shadow add to argument end

          if(jQuerOSS(el).css("border-top-left-radius")){
            jQuerOSS(el).attr("data-border-radius",oss.toSl(jQuerOSS(el).css("border-top-left-radius")));
          }

        });
  
        return;
    }

    //bch
    // fn-n for delete image
    oss.deleteCurrentImage = function (imgId, moduleId){
      if(oss.params.debugMode){
        console.log("oss.deleteCurrentImage",[imgId, moduleId]);
      }

      if(localStorage.getItem('parent_img_id') == imgId){
          localStorage.clear();
        }
        
      // return;
      jQuerOSS.post("index.php?option=com_ajax&module=os_touchslider&Itemid="+oss.params.ItemId+"&task=delete_image&moduleId="+oss.params.moduleId+"&format=raw",{imgId:imgId},

      function (data) {
        if (data.success) {

          countSlides = jQuerOSS("#slidesPerView-" + oss.params.moduleId).val()

          empty_image = '<div class="swiper-slide"><img class="slide-image" src="'+empty_image_path+'" alt="slider is empty"></div>';

          if(countSlides < 2){
            jQuerOSS('div.empty-image').html(empty_image)
          }

          jQuerOSS(container+" .existing-images .slider-images .slider-image-block img, #os-slider-"+oss.params.moduleId+
                                                          " .swiper-slide img").each(function(index, el){
            if(jQuerOSS(this).attr("data-image-id") == imgId){
              jQuerOSS(this).parent().remove();
            }
          });
          jQuerOSS("#message-block-" + oss.params.moduleId).html('<span class="successful-slider-message">Image deleted.</span>');
          setTimeout(function(){
            jQuerOSS("#message-block-" + oss.params.moduleId).empty();
          }, 5000);
          oss.cancelImgEditor();
          if(typeof(oss.params.textAnimation.start) != 'undefined'){
            delete oss.params.textAnimation.start[imgId];
          }
          if(typeof(oss.params.textAnimation.end) != 'undefined'){
            delete oss.params.textAnimation.end[imgId];
          }
          if(typeof(oss.params.textAnimation.permanent) != 'undefined'){
            delete oss.params.textAnimation.permanent[imgId];
          }

          if(typeof(oss.params.textAnimation.hover) != 'undefined'){
            delete oss.params.textAnimation.hover[imgId];
          }
          oss.resetSlider(true);


        }else{
          jQuerOSS("#message-block-" + oss.params.moduleId).html('<span class="error-slider-message">Something was wrong.(deleteCurrentImage)</span>');
        }
      } , 'json' );
    }
    //end

    oss.changeStyle = function (){
      if(oss.params.debugMode){
        console.log("oss.changeStyle",['without arguments']);
      }
      //resize img


      screenWidth = jQuerOSS(window).innerWidth();
      kw = screenWidth/oss.params.screenW;
      // WxH = jQuerOSS(window).innerWidth()/jQuerOSS(window).innerHeight();

      WxH = false;

      exist_slides = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").length;
      if(kw >0.98)kw=1;
      // if(WxH < 1.5 && kw < 0.8)WxH = true;
      // else WxH = false;
      //enable always auto if checkbox
      if(jQuerOSS("#height-auto-" + oss.params.moduleId).prop('checked')){WxH=true}

      //add or remove object fit = cover
      if(jQuerOSS("#object_fit-" + oss.params.moduleId).prop('checked')){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").removeClass('fit-contain')
                                                                    .removeClass('fit-fill');
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.fit-cover)").addClass('fit-cover');

      }else if(jQuerOSS("#object_fit1-" + oss.params.moduleId).prop('checked')){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").removeClass('fit-cover')
                                                                    .removeClass('fit-fill');
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.fit-contain)").addClass('fit-contain');

      }else if(jQuerOSS("#object_fit2-" + oss.params.moduleId).prop('checked')){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").removeClass('fit-cover')
                                                                    .removeClass('fit-contain');
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.fit-fill)").addClass('fit-fill');

      }else{
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").removeClass('fit-cover')
                                                                    .removeClass('fit-contain')
                                                                    .removeClass('fit-fill');
      }   

      //frevent auto for paralax
       if(oss.params.parallax){WxH = false}

       //horizontal
      if(jQuerOSS(container + " .direction:checked").val() == 'horizontal'){
        //pixels bouth
        if(jQuerOSS(container + " .width-pixels:checked").val()==1 && jQuerOSS(container + " .height-pixels:checked").val()==1){
          //px  == 1 column and img > 1 per slides


          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() > 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px horizontal perView:>1  column:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }

          //px and img > 1 column and img >= 1 per slides
          else if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() >= 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() > 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px horizontal perView:>=1  column:>1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);

            perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()/2;
            if(!perColumn)perColumn = 1;
            height = ((jQuerOSS("#image_height_px-" + oss.params.moduleId).val()/jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()-
                      jQuerOSS("#spaceBetween-" + oss.params.moduleId).val()*(perColumn)/2)*kw)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
          //px and 1 column and img == 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px horizontal perView:==1  column:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            screenHeight = jQuerOSS(window).innerHeight();
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }

        //width=px height=%
        if(jQuerOSS(container + " .width-pixels:checked").val()==1 && jQuerOSS(container + " .height-pixels:checked").val()==0){
          //px  == 1 column and img > 1 per slides
          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() > 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px/% horizontal perView:>1  column:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = screenHeight*(height/100)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
          }
          //px and img > 1 column and img >= 1 per slides
          else if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() >= 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() > 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px/% horizontal perView:>=1  column:>1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = (screenHeight*(height/100))+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);

            perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()/2;
            if(!perColumn)perColumn = 1;
            height = (screenHeight*(jQuerOSS("#image_height_per-" + oss.params.moduleId).val()/100)/jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val())-
                      (jQuerOSS("#spaceBetween-" + oss.params.moduleId).val()*(perColumn)/2)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
          //px and 1 column and img == 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px/% horizontal perView:==1  column:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            screenHeight = jQuerOSS(window).innerHeight();
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            height = screenHeight*(height/100)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }

        //width=% height=px
        if(jQuerOSS(container + " .width-pixels:checked").val()==0 && jQuerOSS(container + " .height-pixels:checked").val()==1){
          //% and img > 1 column and img >= 1 per slides
          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() >= 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() > 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[%/px horizontal perView:>=1  column:>1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val();
            screenWidth = jQuerOSS(window).innerWidth();
            width = width+"%;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);

            perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()/2;
            if(!perColumn)perColumn = 1;
            height = ((jQuerOSS("#image_height_px-" + oss.params.moduleId).val()/jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()-
                      jQuerOSS("#spaceBetween-" + oss.params.moduleId).val()*(perColumn)/2)*kw)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
          //% and 1 column and img > 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[%/px horizontal]",['without arguments']);
            }
            width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val()+"%;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }

        //% bouth
        if(jQuerOSS(container + " .width-pixels:checked").val()==0 && jQuerOSS(container + " .height-pixels:checked").val()==0){
          //% and img > 1 column and img >= 1 per slides
          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() >= 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() > 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[% horizontal perView:>=1  column:>1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val();
            screenWidth = jQuerOSS(window).innerWidth();
            width = width+"%;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = (screenHeight*(height/100))+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);

            perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val()/2;
            if(!perColumn)perColumn = 1;
            height = (screenHeight*(jQuerOSS("#image_height_per-" + oss.params.moduleId).val()/100)/jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val())-
                      (jQuerOSS("#spaceBetween-" + oss.params.moduleId).val()*(perColumn)/2)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
          //% and 1 column and img > 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[% horizontal]",['without arguments']);
            }
            width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val()+"%;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = screenHeight*(height/100)+"px;";
            if(WxH && exist_slides)height="auto;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }
      }
      //vertical
      else{
        //pixels bouth
        if(jQuerOSS(container + " .width-pixels:checked").val()==1 && jQuerOSS(container + " .height-pixels:checked").val()==1){
          //px and img == 1 column and img > 1 per slides
          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() > 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px vertical perView:>1 perColumn:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
          }
          //px and 1 column and img == 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px vertical]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }

        //width=px height=%
        if(jQuerOSS(container + " .width-pixels:checked").val()==1 && jQuerOSS(container + " .height-pixels:checked").val()==0){
            //px and img == 1 column and img > 1 per slides
          if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() > 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px/% vertical perView:>1 perColumn:==1]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = screenHeight*(height/100)+"px;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
          }
          //px and 1 column and img == 1 per slides
          else{
            if(oss.params.debugMode){
              console.log("oss.changeStyle::[px/% vertical]",['without arguments']);
            }
            width = jQuerOSS("#image_width_px-" + oss.params.moduleId).val()*kw+"px;";
            height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
            screenHeight = jQuerOSS(window).innerHeight();
            height = screenHeight*(height/100)+"px;";
            jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
          }
        }

        //width=% height=px
        if(jQuerOSS(container + " .width-pixels:checked").val()==0 && jQuerOSS(container + " .height-pixels:checked").val()==1){
          //% and 1 column and img > 1 per slides
          if(oss.params.debugMode){
            console.log("oss.changeStyle::[%/px vertical]",['without arguments']);
          }
          width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val();
          screenWidth = jQuerOSS(window).innerWidth();
          width = screenWidth*(width/100)+"px;";
          height = jQuerOSS("#image_height_px-" + oss.params.moduleId).val()*kw+"px;";

          jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
        }

        //% bouth
        if(jQuerOSS(container + " .width-pixels:checked").val()==0 && jQuerOSS(container + " .height-pixels:checked").val()==0){
          //% and 1 column and img > 1 per slides
          if(oss.params.debugMode){
            console.log("oss.changeStyle::[% vertical]",['without arguments']);
          }
          width = jQuerOSS("#image_width_per-" + oss.params.moduleId).val();
          screenWidth = jQuerOSS(window).innerWidth();
          width = screenWidth*(width/100)+"px;";
          height = jQuerOSS("#image_height_per-" + oss.params.moduleId).val();
          screenHeight = jQuerOSS(window).innerHeight();
          height = screenHeight*(height/100)+"px;";

          jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container").attr("style","width:"+width+"height:"+height);
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").attr("style","height:"+height);
        }
      }

     

    //end
    }

    oss.toSl = function(cssValue){
      var containerWidth = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .swiper-slide").width(); //600px
      var sl = containerWidth/100; //6px = 1ye
      var textValue = cssValue.replace('px','')/sl;

      return textValue;
    }

    oss.toPx = function(textValue){
      var containerWidth = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .swiper-slide").width(); //600px
      var sl = containerWidth/100; //6px = 1ye
      var pixelsCount = (sl*textValue);

      return pixelsCount;
    }

    oss.resizeSlider = function(){


      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {

    
        fontSize = jQuerOSS(el).attr("data-font-size");
        lineHeight = jQuerOSS(el).attr("data-line-height");
        borderWidth = jQuerOSS(el).attr("data-border-width");
        paddingTop = jQuerOSS(el).attr("data-padding-top");
        paddingRight = jQuerOSS(el).attr("data-padding-right");
        paddingBottom = jQuerOSS(el).attr("data-padding-bottom");
        paddingLeft = jQuerOSS(el).attr("data-padding-left");
        borderRadius = jQuerOSS(el).attr("data-border-radius");

        textHShadow = jQuerOSS(el).attr("data-text-h-shadow");
        textVShadow = jQuerOSS(el).attr("data-text-v-shadow");
        textBlurRadius = jQuerOSS(el).attr("data-text-blur-radius");
        textShadowColorpicker = jQuerOSS(el).attr("data-text-shadow-colorpicker");


        jQuerOSS(el).css("font-size", oss.toPx(fontSize)+"px");
        jQuerOSS(el).css("line-height", '100%');
        jQuerOSS(el).css("border-width", oss.toPx(borderWidth)+"px");
        jQuerOSS(el).css("padding-top",oss.toPx(paddingTop)+"px" );
        jQuerOSS(el).css("padding-right",oss.toPx(paddingRight)+"px" );
        jQuerOSS(el).css("padding-bottom",oss.toPx(paddingBottom)+"px" );
        jQuerOSS(el).css("padding-left",oss.toPx(paddingLeft)+"px" );
        jQuerOSS(el).css("height", 'auto');
        jQuerOSS(el).css("border-radius",oss.toPx(borderRadius)+"px");

        // alert(el);

        jQuerOSS(el).css("text-shadow",textShadowColorpicker+" "+
                                       oss.toPx(textHShadow)+"px "+
                                       oss.toPx(textVShadow)+"px "+
                                       oss.toPx(textBlurRadius)+"px");

        // fix for <h>
        el = jQuerOSS(el).find("h1,h2,h3,h4,h5,h6");
        if(el.length){
          jQuerOSS(el).each(function(index, el) {
            if ( jQuerOSS(el).is( "h1" ) ) {
              jQuerOSS(el).css("font-size", 2+'em');
              jQuerOSS(el).css("line-height", 2+'em');
            }
            if ( jQuerOSS(el).is( "h2" ) ) {
              jQuerOSS(el).css("font-size", 1.5+'em');
              jQuerOSS(el).css("line-height", 1.5+'em');
            }
            if ( jQuerOSS(el).is( "h3" ) ) {
              jQuerOSS(el).css("font-size", 1.17+'em');
              jQuerOSS(el).css("line-height", 1.17+'em');
            }
            if ( jQuerOSS(el).is( "h4" ) ) {
              jQuerOSS(el).css("font-size", 1+'em');
              jQuerOSS(el).css("line-height", 1+'em');
            }
            if ( jQuerOSS(el).is( "h5" ) ) {
              jQuerOSS(el).css("font-size", 0.83+'em');
              jQuerOSS(el).css("line-height", 0.83+'em');
            }
            if ( jQuerOSS(el).is( "h6" ) ) {
              jQuerOSS(el).css("font-size", 0.67+'em');
              jQuerOSS(el).css("line-height", 0.67+'em');
            }
          });
        }

        //arrow
        // if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next")){
        //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").css("height", 44*kw+"px");
        // }

        // if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev")){
        //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").css("height", 44*kw+"px");
        // }
      });
      //end
    }

    oss.addBackgroundToThumbs = function(){
        if(oss.params.debugMode){
          console.log("oss.addBackgroundToThumbs",[]);
        }

        jQuerOSS("#os-slider-"+oss.params.moduleId + " div.swiper-slide").each(function(index,el){
          var image_id = jQuerOSS(el).attr('data-image-id');
          var backgroundColor = jQuerOSS(el).attr('data-image-background');
          jQuerOSS(container + " .slider-image[data-image-id="+image_id+"]").parent('div').css('backgroundColor',backgroundColor);
        })

    }

    oss.addFilterSelect = function(image_id ){
        if(oss.params.debugMode){
          console.log("oss.addFilterSelect",[image_id]);
        }

        if(!image_id) return;

        return '<span data-image-id="'+image_id+'" class="image-filter" style="display:none">Filters: <select data-image-id="'+image_id+'" class="filter-select" name="image_filter['+image_id+']"><option selected="" value="none">None</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="blur">-- Blur --</option><option value="blur1">Blur 1px</option><option value="blur2">Blur 2px</option><option value="blur3">Blur 3px</option><option value="blur5">Blur 5px</option><option value="blur10">Blur 10px</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="grayscale">-- Grayscale --</option><option value="grayscale1">Grayscale 0.1</option><option value="grayscale2">Grayscale 0.2</option><option value="grayscale3">Grayscale 0.3</option><option value="grayscale5">Grayscale 0.5</option><option value="grayscale7">Grayscale 0.7</option><option value="grayscale10">Grayscale 1</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="hue-rotate">-- Hue rotate --</option><option value="hue-rotate90">Hue rotate 90deg</option><option value="hue-rotate180">Hue rotate 180deg</option><option value="hue-rotate270">Hue rotate 270deg</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="instagram">-- Instagram filters --</option><option value="_1977">1977</option><option value="aden">Aden</option> <option value="brannan">Brannan</option><option value="brooklyn">Brooklyn</option><option value="clarendon">Clarendon</option> <option value="earlybird">Earlybird</option><option value="gingham">Gingham</option><option value="hudson">Hudson</option><option value="inkwell">Inkwell</option><option value="kelvin">Kelvin</option><option value="lark">Lark</option><option value="lofi">Lo-Fi</option><option value="maven">Maven</option><option value="mayfair">Mayfair</option><option value="moon">Moon</option><option value="nashville">Nashville</option><option value="perpetua">Perpetua</option><option value="reyes">Reyes</option><option value="rise">Rise</option><option value="slumber">Slumber</option><option value="stinson">Stinson</option><option value="toaster">Toaster</option><option value="valencia">Valencia</option><option value="walden">Walden</option><option value="willow">Willow</option><option value="xpro2">X-pro II</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="other">-- Other filters --</option><option value="brightness">Brightness</option><option value="contrast">Contrast</option><option value="invert">Invert</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="saturate">-- Saturate --</option><option value="saturate2">Saturate 2</option><option value="saturate3">Saturate 3</option><option value="saturate5">Saturate 5</option><option value="saturate7">Saturate 7</option><option value="saturate10">Saturate 10</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="sepia">-- Sepia --</option><option value="sepia1">Sepia 0.1</option><option value="sepia2">Sepia 0.2</option><option value="sepia3">Sepia 0.3</option><option value="sepia5">Sepia 0.5</option><option value="sepia7">Sepia 0.7</option><option value="sepia10">Sepia 1</option><option disabled style="font-weight:bold; color:#3A8BDF; font-style:italic;" value="opacity">-- Transparent --</option><option value="opacity0">Opacity 0</option><option value="opacity1">Opacity 0.1</option><option value="opacity2">Opacity 0.2</option><option value="opacity3">Opacity 0.3</option><option value="opacity5">Opacity 0.5</option><option value="opacity7">Opacity 0.7</option><option value="opacity9">Opacity 0.9</option></select></span>';
    }

    oss.resetSlider = function (reinit){

      if(oss.params.debugMode){
        console.log("oss.resetSlider",[reinit]);
      }
      oss.changeStyle();


      if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() == 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
        jQuerOSS("#spaceBetween-" + oss.params.moduleId).closest('.spaceBetween-block').hide('slow');
      }else{
        jQuerOSS("#spaceBetween-" + oss.params.moduleId).closest('.spaceBetween-block').show('slow');
      }
      if(jQuerOSS(container + " .width-pixels:checked").val() == 1){
        jQuerOSS("#width-pixels-block-" + oss.params.moduleId).show('slow');
        jQuerOSS("#width-percentage-block-" + oss.params.moduleId).hide('slow');
      }else{
        jQuerOSS("#width-pixels-block-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#width-percentage-block-" + oss.params.moduleId).show('slow');
      }

      if(jQuerOSS(container + " .height-pixels:checked").val() == 1){
        jQuerOSS("#height-pixels-block-" + oss.params.moduleId).show('slow');
        jQuerOSS("#height-percentage-block-" + oss.params.moduleId).hide('slow');
      }else{
        jQuerOSS("#height-pixels-block-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#height-percentage-block-" + oss.params.moduleId).show('slow');
      }
      if(jQuerOSS(container + " .slider-effect").val() == 'parallax'){
        jQuerOSS("#height-auto-" + oss.params.moduleId).prop('checked',false);
        jQuerOSS("#height-auto-" + oss.params.moduleId).parents(".option-block").hide();
        if(jQuerOSS(container + " .direction:checked").val() == 'horizontal'){
          jQuerOSS("div[id^='os-slider'] .parallax-bg").css({"width":"130%", "height":"100%"});
        }else{
          jQuerOSS("div[id^='os-slider'] .parallax-bg").css({"width":"100%", "height":"130%"});
        }
      }else{
        jQuerOSS("#height-auto-" + oss.params.moduleId).parents(".option-block").show();
      }
      if(jQuerOSS("#height-auto-" + oss.params.moduleId).prop('checked')){
        jQuerOSS("#image_height_per-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#image_height_px-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#height-pixel-" + oss.params.moduleId).parents(".option-block").hide('slow');
      }else{
        jQuerOSS("#image_height_per-" + oss.params.moduleId).show('slow');
        jQuerOSS("#image_height_px-" + oss.params.moduleId).show('slow');
        jQuerOSS("#height-pixel-" + oss.params.moduleId).parents(".option-block").show('slow');
        if(jQuerOSS(container + " .height-pixels:checked").val() == 1){
          jQuerOSS("#height-pixels-block-" + oss.params.moduleId).show('slow');
          jQuerOSS("#height-percentage-block-" + oss.params.moduleId).hide('slow');
        }else{
          jQuerOSS("#height-pixels-block-" + oss.params.moduleId).hide('slow');
          jQuerOSS("#height-percentage-block-" + oss.params.moduleId).show('slow');
        }
      }

      //autoplay
      if(jQuerOSS("#autoplay-" + oss.params.moduleId).val() > 1){
        jQuerOSS("#autoplay-interaction-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#autoplay-interaction-block-" + oss.params.moduleId).hide('slow');
      }

      //free mode
      if(jQuerOSS(container + " .freeMode:checked").val() == 1){
        jQuerOSS("#free-mode-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#free-mode-block-" + oss.params.moduleId).hide('slow');
      }
      if(jQuerOSS(container + " .freeModeMomentum:checked").val() == 1){
        jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).closest('div.option-block').show('slow');
      }else{
        jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).closest('div.option-block').hide('slow');
      }
      if(jQuerOSS(container + " .freeModeMomentumBounce:checked").val() == 1){
        jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).closest('div.option-block').show('slow');
      }else{
        jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).closest('div.option-block').hide('slow');
      }

      //cube
      if(jQuerOSS(container + " .slider-effect").val() == 'cube'){
        jQuerOSS("#cube-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#cube-animation-block-" + oss.params.moduleId).hide('slow');
      }
      if(parseInt(jQuerOSS(container +" .slideShadows:checked").val()) || parseInt(jQuerOSS(container + " .shadow:checked").val())){
        jQuerOSS("#shadowOffset-" + oss.params.moduleId+", #shadowScale-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#shadowOffset-" + oss.params.moduleId+", #shadowScale-" + oss.params.moduleId).hide('slow');
      }

      //coverflow
      if(jQuerOSS(container + " .slider-effect").val() == 'coverflow'){
        jQuerOSS("#coverflow-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#coverflow-animation-block-" + oss.params.moduleId).hide('slow');
      }

      //flip
      if(jQuerOSS(container + " .slider-effect").val() == 'flip'){
        jQuerOSS("#flip-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#flip-animation-block-" + oss.params.moduleId).hide('slow');
      }
      if(jQuerOSS(container + " .slider-effect").val() == 'coverflow' || jQuerOSS(container + " .slider-effect").val() == 'slide'){
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).parents(".hide-block").show();
      }else{
        jQuerOSS("#slidesPerView-" + oss.params.moduleId+", #slidesPerColumn-" + oss.params.moduleId).val(1);
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).parents(".hide-block").hide();
      }

      //effects
      if(jQuerOSS(container + " .slider-effect").val() == 'custom'){

        // if(!jQuerOSS(container + " .animation-manager-block:visible").length){
        //   oss.params.setupAnimation.start = [];
        //   oss.params.setupAnimation.end = [];
        // }

        jQuerOSS(container + " .animation-manager-block").show('slow');

      }else if(jQuerOSS(container + " .slider-effect").val() != 'slide' 
               && jQuerOSS(container + " .slider-effect").val() != 'fade'
               && jQuerOSS(container + " .slider-effect").val() != 'cube' 
               && jQuerOSS(container + " .slider-effect").val() != 'parallax' 
               && jQuerOSS(container + " .slider-effect").val() != 'coverflow'
               && jQuerOSS(container + " .slider-effect").val() != 'flip' 
               && jQuerOSS(container + " .slider-effect").val() != 'custom'){
    
      jQuerOSS(container + " .animation-manager-block").hide('slow');

        if(jQuerOSS(container + " .slider-effect").val() 
           && jQuerOSS(container + " .slider-effect").val().split("+").length == 2){

            effectArr = jQuerOSS(container + " .slider-effect").val().split("+");
            oss.params.setupAnimation.start = [];
            oss.params.setupAnimation.end = [];
            if(effectArr[0]){
              oss.params.setupAnimation.start.push(effectArr[0]);
            }
            if(effectArr[1]){
              oss.params.setupAnimation.end.push(effectArr[1]);
            }

        }else{
            jQuerOSS(container + " .animation-manager-block").hide('slow');
            oss.params.setupAnimation= {};
            oss.params.setupAnimation.start = [];
            oss.params.setupAnimation.start.push(jQuerOSS(container +" .slider-effect").val());
        }

      }else{
        jQuerOSS(container + " .animation-manager-block").hide('slow');
        oss.params.setupAnimation= {};
      }
      //end

      if(parseInt(jQuerOSS(container + " .pagination:checked").val()) 
         && jQuerOSS(container + " .paginationType:checked").val() == 'progress'){
          jQuerOSS("#os-slider-"+oss.params.moduleId + " .swiper-pagination").removeClass("swiper-pagination-bullets swiper-pagination-fraction");
      }else if(parseInt(jQuerOSS(container + " .pagination:checked").val()) 
               && jQuerOSS(container + " .paginationType:checked").val() == 'fraction'){
        jQuerOSS("#os-slider-"+oss.params.moduleId + " .swiper-pagination").removeClass("swiper-pagination-bullets swiper-pagination-progress");
      }else{
        jQuerOSS("#os-slider-"+oss.params.moduleId + " .swiper-pagination").removeClass("swiper-pagination-fraction swiper-pagination-progress");
      }

      if(jQuerOSS("#paginationCont-" + oss.params.moduleId).prop("checked")){
        jQuerOSS("#pagination-lickable-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+"  .swiper-pagination").show('slow');
      }else{
        jQuerOSS("#pagination-lickable-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+"  .swiper-pagination").hide('slow');
      }

      if(jQuerOSS(container + " .slider-effect").val() == 'parallax'){
        jQuerOSS(container + " #loop1-"+oss.params.moduleId).prop('checked', 'checked');
        jQuerOSS(container + " #loop1-"+oss.params.moduleId).parents(".option-block").parent().hide();
      }else{
        jQuerOSS(container + " #loop1-"+oss.params.moduleId).parents(".option-block").parent().show();
      }

      if(parseInt(jQuerOSS(container + " .loop:checked").val())){
        jQuerOSS("#looped-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#looped-block-" + oss.params.moduleId).hide('slow');
      }
      if(parseInt(jQuerOSS(container + " .prev_next_arrows:checked").val())){

        jQuerOSS("#os-slider-"+oss.params.moduleId + " .swiper-button-prev,#os-slider-"+oss.params.moduleId +" .swiper-button-next").show('slow');

        if(jQuerOSS("#os-slider-"+oss.params.moduleId +" .swiper-button-next").hasClass('hide')) jQuerOSS("#os-slider-"+oss.params.moduleId +" .swiper-button-next").hide()

        if(jQuerOSS("#os-slider-"+oss.params.moduleId +" .swiper-button-prev").hasClass('hide')) jQuerOSS("#os-slider-"+oss.params.moduleId +" .swiper-button-prev").hide()

      }else{
        jQuerOSS("#os-slider-"+oss.params.moduleId + " .swiper-button-next,#os-slider-"+oss.params.moduleId +" .swiper-button-prev").hide('slow');
      }

      if(jQuerOSS(container + " .cropImage:checked").val() > 0){
        jQuerOSS("#crop_wxh_block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#crop_wxh_block-" + oss.params.moduleId).hide('slow');
      }

      if(jQuerOSS(container + " .lazyLoading:checked").val() == 1){
        jQuerOSS("#lazyLoadingInPrevNextBlock-" + oss.params.moduleId).show("slow");
        if(jQuerOSS(container + " .lazyLoadingInPrevNext:checked").val() == 1){
          jQuerOSS("#lazyLoadingAmountBlock-" + oss.params.moduleId).show("slow");
        }else{
          jQuerOSS("#lazyLoadingAmountBlock-" + oss.params.moduleId).hide("slow");
        }
      }else{
        jQuerOSS("#lazyLoadingInPrevNextBlock-" + oss.params.moduleId).hide("slow");
        jQuerOSS("#lazyLoadingAmountBlock-" + oss.params.moduleId).hide("slow");
      }


      oss.makeCopyright(oss.params.activeTab);

      //change slider option
      var params = new Object();
      params.direction = jQuerOSS(container + " .direction:checked").val();
      params.initialSlide = parseInt(jQuerOSS("#initialSlide-" + oss.params.moduleId).val());
      params.autoplay = parseInt(jQuerOSS("#autoplay-" + oss.params.moduleId).val());
      params.autoplayStopOnLast = parseInt(jQuerOSS(container + " .autoplayStopOnLast:checked").val());
      params.autoplayDisableOnInteraction = parseInt(jQuerOSS(container + " .autoplay_interaction:checked").val());
      params.freeMode = parseInt(jQuerOSS(container + " .freeMode:checked").val());
      params.freeModeMomentum = parseInt(jQuerOSS(container + " .freeModeMomentum:checked").val());
      params.freeModeMomentumRatio = parseFloat(jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).val());
      params.freeModeMomentumBounce = parseInt(jQuerOSS(container + " .freeModeMomentumBounce:checked").val());
      params.freeModeMomentumBounceRatio = parseInt(jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).val());
      params.freeModeMinimumVelocity = parseFloat(jQuerOSS("#freeModeMinimumVelocity-" + oss.params.moduleId).val());
      //bch
      imageFullTime = new Array();
      jQuerOSS(container + " .image-time-block .time-input").each(function(index, el){
        imageFullTime[jQuerOSS(el).parent().attr("data-image-id")] = jQuerOSS(el).val();
      });
    
      params.imageFullTime = imageFullTime;

      //filters
      var imageFilter = new Array();
      jQuerOSS(container + " .image-filter-block .filter-select").each(function(index, el){
        imageFilter[jQuerOSS(el).parent().attr("data-image-id")] = jQuerOSS(el).val();
      });
      params.imageFilter = imageFilter;
      //filters


      jQuerOSS(container + " .image-background-block .background-input").val();

       //img background
      var imageBackground = new Array();
      jQuerOSS(container + " .image-background-block .background-input").each(function(index, el){
        imageBackground[jQuerOSS(el).closest('span').attr("data-image-id")] = jQuerOSS(el).val();
      });
      params.imageBackground = imageBackground;
      //img background



      params.effect = jQuerOSS(container + " .slider-effect").val();
      if(params.effect == "coverflow" && oss.params.resetSpeed){
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).val(3);
      }

      params.cube = new Object();
      params.cube.slideShadows = parseInt(jQuerOSS(container + " .slideShadows:checked").val());
      params.cube.shadow = parseInt(jQuerOSS(container + " .shadow:checked").val());
      params.cube.shadowOffset = parseInt(jQuerOSS("#shadowOffset-" + oss.params.moduleId).val());
      params.cube.shadowScale = parseFloat(jQuerOSS("#shadowScale-" + oss.params.moduleId).val());

      params.coverflow = new Object();
      params.coverflow.rotate = parseInt(jQuerOSS("#rotate-" + oss.params.moduleId).val());
      params.coverflow.stretch = parseInt(jQuerOSS("#stretch-" + oss.params.moduleId).val());
      params.coverflow.depth = parseInt(jQuerOSS("#depth-" + oss.params.moduleId).val());
      params.coverflow.modifier = parseInt(jQuerOSS("#modifier-" + oss.params.moduleId).val());
      params.coverflow.coverflowSlideShadows = parseInt(jQuerOSS(container + " .coverflowSlideShadows:checked").val());

      params.flip = new Object();
      params.flip.slideShadows = parseInt(jQuerOSS(container + " .flipSlideShadows:checked").val());
      params.flip.limitRotation = parseInt(jQuerOSS(container + " .flipLimitRotation:checked").val());

      params.spaceBetween = parseInt(jQuerOSS("#spaceBetween-" + oss.params.moduleId).val());
      params.slidesPerView = parseInt(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val());
      params.slidesPerColumn = parseInt(jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val());
      params.slidesPerColumnFill = jQuerOSS(container + " .slidesPerColumnFill:checked").val();

      params.slidesPerGroup = parseInt(jQuerOSS("#slidesPerGroup-" + oss.params.moduleId).val());

      params.centeredSlides = parseInt(jQuerOSS(container + " .centeredSlides:checked").val());

      if(parseInt(jQuerOSS(container + " .pagination:checked").val())){
        params.pagination = "#os-slider-"+oss.params.moduleId+' .swiper-pagination';
      }else{
        params.pagination = 0;
      }
      params.paginationType = jQuerOSS(container + " .paginationType:checked").val();
      params.paginationClickable = parseInt(jQuerOSS(container + " .paginationClickable:checked").val());
      if(parseInt(jQuerOSS(".showScrollbar:checked").val())){
        params.scrollbar = "#os-slider-"+oss.params.moduleId+' .swiper-scrollbar';
      }else{
        params.scrollbar = 0;
      }

      params.scrollbarHide = parseInt(jQuerOSS(container + " .scrollbarHide:checked").val());
      params.scrollbarDraggable = parseInt(jQuerOSS(container + " .scrollbarDraggable:checked").val());

      params.keyboardControl = parseInt(jQuerOSS(container + " .keyboardControl:checked").val());
      params.mousewheelControl = parseInt(jQuerOSS(container + " .mousewheelControl:checked").val());
      params.mousewheelReleaseOnEdges = parseInt(jQuerOSS(container + " .mousewheelReleaseOnEdges:checked").val());
      params.loop = parseInt(jQuerOSS(container + " .loop:checked").val());
      params.slideActiveClass = "#os-slider-"+oss.params.moduleId+' swiper-slide-active';
      params.crop_image = jQuerOSS(container + " .cropImage:checked").val();


      if(params.effect == 'parallax'){
        jQuerOSS("#crop-image-option-block-" + oss.params.moduleId + " .important-animation-message").remove();
        if(jQuerOSS(container + " .lazyLoading:checked").val() == 1){
          jQuerOSS(container+" .selected-layout").prepend('<span class="important-animation-message">Parallax animation not work with Lazy Loading.</span>');
          oss.params.parallax = params.parallax = 0;
        }else{
          oss.params.parallax = params.parallax = 1;
        }
      }else{
        jQuerOSS("#crop-image-option-block-" + oss.params.moduleId + " .important-animation-message").remove();
        oss.params.parallax = params.parallax = 0;
      }
      params.image_width = jQuerOSS("#image_width-" + oss.params.moduleId).val();
      params.image_height = jQuerOSS("#image_height-" + oss.params.moduleId).val();

      params.lazyLoading = jQuerOSS(container + " .lazyLoading:checked").val();
      params.lazyLoadingInPrevNext = jQuerOSS(container + " .lazyLoadingInPrevNext:checked").val();
      params.lazyLoadingInPrevNextAmount = jQuerOSS("#lazyLoadingInPrevNextAmount-" + oss.params.moduleId).val();
      params.setupAnimation = oss.params.setupAnimation;
      params.textAnimation = oss.params.textAnimation;
      //setup speed
      if((typeof params.setupAnimation.start != 'undefined' || typeof params.setupAnimation.end != 'undefined') 
        && oss.params.resetSpeed && jQuerOSS(container + " .slider-effect").val() != 'custom'){
        if(typeof params.setupAnimation.start != 'undefined' && typeof params.setupAnimation.end != 'undefined'){
          if(params.setupAnimation.start == 'flip' && params.setupAnimation.end == ''){
            jQuerOSS("#speed-" + oss.params.moduleId).val(0);
          }
          if(params.setupAnimation.start == 'flip' && params.setupAnimation.end == 'pulse'
            || params.setupAnimation.start == 'shake' && params.setupAnimation.end == 'rotateOut'){
            jQuerOSS("#speed-" + oss.params.moduleId).val(300);
          }
          if(params.setupAnimation.start == 'bounceInLeft' && params.setupAnimation.end == 'swing'
            || params.setupAnimation.start == 'bounce' && params.setupAnimation.end == 'pulse'
            || params.setupAnimation.start == 'pulse' && params.setupAnimation.end == 'bounce'
            || params.setupAnimation.start == 'slideInUp' && params.setupAnimation.end == 'hinge'){
            jQuerOSS("#speed-" + oss.params.moduleId).val(500);
          }
          if(params.setupAnimation.start == 'fadeInLeftBig' && params.setupAnimation.end == 'tada'
            || params.setupAnimation.start == 'fadeInRightBig' && params.setupAnimation.end == 'swing'
            || params.setupAnimation.start == 'fadeInLeftBig' && params.setupAnimation.end == 'swing'
            || params.setupAnimation.start == 'fadeInRightBig' && params.setupAnimation.end == 'tada'
            || params.setupAnimation.start == 'slideInDown' && params.setupAnimation.end == 'bounce'
            || params.setupAnimation.start == 'zoomOut' && params.setupAnimation.end == 'tada'
            || params.setupAnimation.start == 'rotateIn' && params.setupAnimation.end == 'tada'){
            jQuerOSS("#speed-" + oss.params.moduleId).val(1000);
          }
        }
        else if(typeof params.setupAnimation.start != 'undefined' && params.setupAnimation.start != ''){
          if(params.setupAnimation.start == 'bounce'){
            jQuerOSS("#speed-" + oss.params.moduleId).val(500);
          }else{
            jQuerOSS("#speed-" + oss.params.moduleId).val(0);
          }
        }
        else if(typeof params.setupAnimation.end != 'undefined' && params.setupAnimation.end != ''){

        }
      }else if(oss.params.resetSpeed){
        jQuerOSS("#speed-" + oss.params.moduleId).val(1000);
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-prev img").show();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-next img").show();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active img").show();
      }
      //end
      params.speed = parseInt(jQuerOSS("#speed-" + oss.params.moduleId).val());

      // if(kw == 1){
        params.userScreenWidth = screen.width;
        params.userScreenHeight = jQuerOSS(window).innerHeight();
      // }
      if(kw == 1 && jQuerOSS("#width-percentage-block-" + oss.params.moduleId).css('display') == 'none'){
        params.userScreenWidth = jQuerOSS(window).innerWidth();
        params.userScreenHeight = jQuerOSS(window).innerHeight();
      }
        

      if(oss.params.imageOrdering.length > 0){
        params.imageOrdering = oss.params.imageOrdering;
      }else{
        oss.params.imageOrdering = jQuerOSS(container + " .existing-images").sortable('toArray', {attribute: 'data-sortable-id'});
        params.imageOrdering = oss.params.imageOrdering;
      }
      params.textOrdering = oss.params.textOrdering;
      if(reinit){
        //bch
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
          jQuerOSS(this).attr("data-style",jQuerOSS(this).attr("style"));
        });

        oss.reinitSlider(params, oss.params.setupAnimation);
        oss.changeStyle();
        oss.params.swiperSlider.update(true);

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
          jQuerOSS(this).attr("style",jQuerOSS(this).attr("data-style"));
        });


      }else{
        // oss.params.swiperSlider.params = params;
        //need reload page block
        //for crop
        if((jQuerOSS(container + " .cropImage:checked").val() != oss.params.crop)
            || (jQuerOSS("#image_width-" + oss.params.moduleId).val() != oss.params.imageWidth
                || jQuerOSS("#image_height-" + oss.params.moduleId).val() != oss.params.imageHeight)){
          if(!jQuerOSS("#crop-image-option-block-" + oss.params.moduleId + " .important-message").length){
            jQuerOSS("#crop-image-option-block-" + oss.params.moduleId).prepend('<span class="important-message">You need save and reload page to see changes.</span>');
            jQuerOSS("#save-settings-" + oss.params.moduleId).addClass('need-save');
          }
        }else{
          jQuerOSS("#crop-image-option-block-" + oss.params.moduleId + " .important-message").remove();
        }
        //for lazy loading
        if(jQuerOSS(container + " .lazyLoading:checked").val() != oss.params.lazyLoading
          || jQuerOSS(container + " .lazyLoadingInPrevNext:checked").val() != oss.params.lazyLoadingInPrevNext
          || jQuerOSS("#lazyLoadingInPrevNextAmount-" + oss.params.moduleId).val() != oss.params.lazyLoadingInPrevNextAmount){
            jQuerOSS("#lazy-loading-image-option-block-" + oss.params.moduleId + " .important-message").remove();
            if(jQuerOSS(container + " .slider-effect").val() == 'parallax' && jQuerOSS(container + " .lazyLoading:checked").val() ==1){
              jQuerOSS("#lazy-loading-image-option-block-" + oss.params.moduleId).prepend('<span class="important-message">Lazy load not working with Parallax animation.</span>');
              params.lazyLoading = false;
              jQuerOSS("#lazyLoading1-" + oss.params.moduleId).prop("checked", true);
              params.lazyLoadingInPrevNext = false;
              jQuerOSS("#lazyLoadingInPrevNext1-" + oss.params.moduleId).prop("checked", true);
              params.lazyLoadingInPrevNextAmount = 1;
              jQuerOSS("#lazyLoadingInPrevNextAmount-" + oss.params.moduleId).val(1);
            }else{
              jQuerOSS("#lazy-loading-image-option-block-" + oss.params.moduleId).prepend('<span class="important-message">You need save and reload page to see changes.</span>');
            }
            jQuerOSS("#save-settings-" + oss.params.moduleId).addClass('need-save');
        }else{
          jQuerOSS("#lazy-loading-image-option-block-" + oss.params.moduleId + " .important-message").remove();
        }
        //end
        oss.params.swiperSlider.update(true);
      }






      if(parseInt(jQuerOSS(container + " .showScrollbar:checked").val())){
        jQuerOSS("#scrollbar-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+"  .swiper-scrollbar").show('slow');
      }else{
        jQuerOSS("#scrollbar-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+" .swiper-scrollbar").hide('slow');
      }
      params.height_px = parseInt(jQuerOSS("#image_height_px-" + oss.params.moduleId).val());
      params.width_px = parseInt(jQuerOSS("#image_width_px-" + oss.params.moduleId).val());
      params.width_per = parseInt(jQuerOSS("#image_width_per-" + oss.params.moduleId).val());
      params.height_per = parseInt(jQuerOSS("#image_height_per-" + oss.params.moduleId).val());
      params.width_pixels = parseInt(jQuerOSS(container + " .width-pixels:checked").val());
      params.height_pixels = parseInt(jQuerOSS(container + " .height-pixels:checked").val());
      params.height_auto = jQuerOSS("#height-auto-" + oss.params.moduleId).prop('checked');
      params.object_fit = jQuerOSS(container + " .objectFit:checked").val();
      params.prev_next_arrows = parseInt(jQuerOSS(container + " .prev_next_arrows:checked").val());


      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-prev img").show();
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-next img").show();
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active img").show();
        
      oss.stopSlider();

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").each(function(index, el){
        jQuerOSS(el).css('backgroundColor',jQuerOSS(el).attr('data-image-background'));
      });

      oss.makeCustomSlideColorpicker();

      return params;
    }

    //reset slider settings fn-s
    oss.resetSliderSettings = function (){
      if(oss.params.debugMode){
        console.log("oss.resetSliderSettings",['without arguments']);
      }
      
      //change slider option
      var params = new Object();
      params.direction = 'horizontal';
      jQuerOSS("#direction-" + oss.params.moduleId).prop("checked", true);
      params.initialSlide = 0;
      jQuerOSS("#initialSlide-" + oss.params.moduleId).val(0);
      params.autoplay = 3000;
      jQuerOSS("#autoplay-" + oss.params.moduleId).val(3000);
      params.autoplayStopOnLast = false;
      jQuerOSS("#autoplayStopOnLast1-" + oss.params.moduleId).prop("checked", true);
      params.autoplayDisableOnInteraction = false;
      jQuerOSS("#autoplay_interaction1-" + oss.params.moduleId).prop("checked", true);
      params.speed = 300;
      jQuerOSS("#speed-" + oss.params.moduleId).val(300);
      params.freeMode = false;
      jQuerOSS("#freeMode1-" + oss.params.moduleId).prop("checked", true);
      params.freeModeMomentum = true;
      jQuerOSS("#freeModeMomentum-" + oss.params.moduleId).prop("checked", true);
      params.freeModeMomentumRatio = 1;
      jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).val(1);
      params.freeModeMomentumBounce = true;
      jQuerOSS("#freeModeMomentumBounce-" + oss.params.moduleId).prop("checked", true);
      params.freeModeMomentumBounceRatio = 1;
      jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).val(1);
      params.freeModeMinimumVelocity = 0.02;
      jQuerOSS("#freeModeMinimumVelocity-" + oss.params.moduleId).val(0.02);

      params.effect = 'slide';
      jQuerOSS(container + " .slider-effect").val('slide');
      params.cube = new Object();
      params.cube.slideShadows = true;
      jQuerOSS("#slideShadows-" + oss.params.moduleId).prop("checked", true);
      params.cube.shadow = true;
      jQuerOSS("#shadow-" + oss.params.moduleId).prop("checked", true);
      params.cube.shadowOffset = 20;
      jQuerOSS("#shadowOffset-" + oss.params.moduleId).val(20);
      params.cube.shadowScale = 0.94;
      jQuerOSS("#shadowScale-" + oss.params.moduleId).val(0.94);

      params.coverflow = new Object();
      params.coverflow.rotate = 50;
      jQuerOSS("#rotate-" + oss.params.moduleId).val(50);
      params.coverflow.stretch = 0;
      jQuerOSS("#stretch-" + oss.params.moduleId).val(0);
      params.coverflow.depth = 100;
      jQuerOSS("#depth-" + oss.params.moduleId).val(100);
      params.coverflow.modifier = 1;
      jQuerOSS("#modifier-" + oss.params.moduleId).val(1);
      params.coverflow.coverflowSlideShadows = true;
      jQuerOSS("#coverflowSlideShadows-" + oss.params.moduleId).prop("checked", true);

      params.flip = new Object();
      params.flip.slideShadows = true;
      jQuerOSS("#flipSlideShadows-" + oss.params.moduleId).prop("checked", true);
      params.flip.limitRotation = true;
      jQuerOSS("#flipLimitRotation-" + oss.params.moduleId).prop("checked", true);

      params.spaceBetween = 0;
      jQuerOSS("#spaceBetween-" + oss.params.moduleId).val(0);
      params.slidesPerView = 1;
      jQuerOSS("#slidesPerView-" + oss.params.moduleId).val(1);
      params.slidesPerColumn = 1;
      jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val(1);
      params.slidesPerColumnFill = true;
      jQuerOSS("#slidesPerColumnFill-" + oss.params.moduleId).prop("checked", true);

      params.slidesPerGroup = 1;
      jQuerOSS("#slidesPerGroup-" + oss.params.moduleId).val(1);

      params.centeredSlides = false;
      jQuerOSS("#centeredSlides1-" + oss.params.moduleId).prop("checked", true);

      params.pagination = "#os-slider-"+oss.params.moduleId+' .swiper-pagination';
      jQuerOSS("#paginationCont-" + oss.params.moduleId).prop("checked", true);

      params.paginationType = 'bullets';
      jQuerOSS("#paginationType-" + oss.params.moduleId).prop("checked", true);
      params.paginationClickable = false;
      jQuerOSS(container + " .paginationClickable1").prop("checked", true);
      params.scrollbar = false;
      jQuerOSS("#showScrollbar1-" + oss.params.moduleId).prop("checked", true);

      params.scrollbarHide = true;
      jQuerOSS("#scrollbarHide-" + oss.params.moduleId).prop("checked", true);
      params.scrollbarDraggable = false;
      jQuerOSS("#scrollbarDraggable1-" + oss.params.moduleId).prop("checked", true);

      params.keyboardControl = false;
      jQuerOSS("#keyboardControl1-" + oss.params.moduleId).prop("checked", true);
      params.mousewheelControl = false;
      jQuerOSS("#mousewheelControl1-" + oss.params.moduleId).prop("checked", true);
      params.mousewheelReleaseOnEdges = false;
      jQuerOSS("#mousewheelReleaseOnEdges1-" + oss.params.moduleId).prop("checked", true);

      params.loop = false;
      jQuerOSS("#loop1-" + oss.params.moduleId).prop("checked", true);

      params.crop_image = 0;
      jQuerOSS("#crop_image2-" + oss.params.moduleId).prop("checked", true);

      params.object_fit = 0;
      jQuerOSS("#object_fit2-" + oss.params.moduleId).prop("checked", true);

      params.image_width = 400;
      jQuerOSS("#image_width-" + oss.params.moduleId).val(400);
      params.image_height = 200;
      jQuerOSS("#image_height-" + oss.params.moduleId).val(200);

      params.lazyLoading = false;
      jQuerOSS("#lazyLoading1-" + oss.params.moduleId).prop("checked", true);
      params.lazyLoadingInPrevNext = false;
      jQuerOSS("#lazyLoadingInPrevNext1-" + oss.params.moduleId).prop("checked", true);
      params.lazyLoadingInPrevNextAmount = 1;
      jQuerOSS("#lazyLoadingInPrevNextAmount-" + oss.params.moduleId).val(1);




      params.slideActiveClass = 'swiper-slide-active';
      params.userScreenWidth = jQuerOSS(window).innerWidth();

      //set settings
      if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() == 1 && jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val() == 1){
        jQuerOSS("#spaceBetween-" + oss.params.moduleId).closest('.spaceBetween-block').hide('slow');
      }else{
        jQuerOSS("#spaceBetween-" + oss.params.moduleId).closest('.spaceBetween-block').show('slow');
      }

      if(jQuerOSS(container + " .width-pixels:checked").val() == 1){
        jQuerOSS("#width-pixels-block-" + oss.params.moduleId).show('slow');
        jQuerOSS("#width-percentage-block-" + oss.params.moduleId).hide('slow');
      }else{
        jQuerOSS("#width-pixels-block-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#width-percentage-block-" + oss.params.moduleId).show('slow');
      }

      if(jQuerOSS(container + " .height-pixels:checked").val() == 1){
        jQuerOSS("#height-pixels-block-" + oss.params.moduleId).show('slow');
        jQuerOSS("#height-percentage-block-" + oss.params.moduleId).hide('slow');
      }else{
        jQuerOSS("#height-pixels-block-" + oss.params.moduleId).hide('slow');
        jQuerOSS("#height-percentage-block-" + oss.params.moduleId).show('slow');
      }

      jQuerOSS("#height-auto-" + oss.params.moduleId).prop('checked',false);
      // jQuerOSS("#object-fit-" + oss.params.moduleId).prop('checked',false);



      jQuerOSS("#height-auto-" + oss.params.moduleId).parents(".option-block").show();

      //autoplay
      if(jQuerOSS("#autoplay-" + oss.params.moduleId).val() > 1){
        jQuerOSS("#autoplay-interaction-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#autoplay-interaction-block-" + oss.params.moduleId).hide('slow');
      }

      //free mode
      if(jQuerOSS(container + " .freeMode:checked").val() == 1){
        jQuerOSS("#free-mode-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#free-mode-block-" + oss.params.moduleId).hide('slow');
      }
      if(jQuerOSS(container + " .freeModeMomentum:checked").val() == 1){
        jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#freeModeMomentumRatio-" + oss.params.moduleId).hide('slow');
      }
      if(jQuerOSS(container + " .freeModeMomentumBounce:checked").val() == 1){
        jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#freeModeMomentumBounceRatio-" + oss.params.moduleId).hide('slow');
      }

      //cube
      if(jQuerOSS(container + " .slider-effect").val() == 'cube'){
        jQuerOSS("#cube-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#cube-animation-block-" + oss.params.moduleId).hide('slow');
      }
      if(parseInt(jQuerOSS(container + " .slideShadows:checked").val()) || parseInt(jQuerOSS(container + " .shadow:checked").val())){
        jQuerOSS("#shadowOffset-" + oss.params.moduleId + ", #shadowScale-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#shadowOffset-" + oss.params.moduleId + ", #shadowScale-" + oss.params.moduleId).hide('slow');
      }

      //coverflow
      if(jQuerOSS(container + " .slider-effect").val() == 'coverflow'){
        jQuerOSS("#coverflow-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#coverflow-animation-block-" + oss.params.moduleId).hide('slow');
      }

      //flip
      if(jQuerOSS(container + " .slider-effect").val() == 'flip'){
        jQuerOSS("#flip-animation-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#flip-animation-block-" + oss.params.moduleId).hide('slow');
      }
      if(jQuerOSS(container + " .slider-effect").val() == 'coverflow' || jQuerOSS(container + " .slider-effect").val() == 'slide'){
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).parents(".hide-block").show();
      }else{
        jQuerOSS("#slidesPerView-" + oss.params.moduleId+", #slidesPerColumn-" + oss.params.moduleId).val(1);
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).parents(".hide-block").hide();
      }

      //effects
      oss.params.setupAnimation = {};

      jQuerOSS(container + " .animation-manager-block").hide();
      if(parseInt(jQuerOSS(container + " .pagination:checked").val()) && jQuerOSS(container + " .paginationType:checked").val() != 'progress'){
        jQuerOSS("#pagination-lickable-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+"  .swiper-pagination").show('slow');
      }else{
        jQuerOSS("#pagination-lickable-block-" + oss.params.moduleId + ",#os-slider-"+oss.params.moduleId+"  .swiper-pagination").hide('slow');
      }

      if(parseInt(jQuerOSS(container + " .loop:checked").val())){
        jQuerOSS("#looped-block-" + oss.params.moduleId).show('slow');
      }else{
        jQuerOSS("#looped-block-" + oss.params.moduleId).hide('slow');
      }

      if(parseInt(jQuerOSS(container + " .prev_next_arrows:checked").val())){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev,#os-slider-"+oss.params.moduleId+"  .swiper-button-next").show('slow');
      }else{
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next,#os-slider-"+oss.params.moduleId+"  .swiper-button-prev").hide('slow');
      }
      //end

      //reinit slider
      jQuerOSS("#os-slider-"+oss.params.moduleId + " .slide-text").each(function(index, el) {
        jQuerOSS(this).attr("data-style",jQuerOSS(this).attr("style"));
      });

      params.parallax = oss.params.parallax = 0;
      oss.reinitSlider(params, oss.params.setupAnimation);
      oss.changeStyle();
      oss.params.swiperSlider.update(true);
      jQuerOSS("#os-slider-"+oss.params.moduleId + " .slide-text").each(function(index, el) {
        jQuerOSS(this).attr("style",jQuerOSS(this).attr("data-style"));
      });
    }


    oss.cancelImgEditor = function (){
      if(oss.params.debugMode){
        console.log("oss.cancelImgEditor",[]);
      }

      jQuerOSS('#tab2-'+oss.params.moduleId+
             ', #tab3-'+oss.params.moduleId+
             ', #tab4-'+oss.params.moduleId).removeAttr('disabled');

      jQuerOSS(container + " .tab-label").click(function(event) {
        oss.makeCopyright(jQuerOSS(this).attr("data-tab-id"));
      });

      oss.cancelTextEditor();

      jQuerOSS(container + " .slider-images .slider-image-block img").css("width","100%");
      jQuerOSS(container + " .slider-images .slider-image-block img").removeClass('edit-img-active');
      // jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active").removeClass('edit-image');
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").removeClass('edit-image');

      jQuerOSS(container + " .slider-images, " +
              "#images-load-area-" + oss.params.moduleId +", .slider-image-block, .delete-current-image").show('slow');
      jQuerOSS(container + " .back-image-edit," +
              container + " .add-image-text," +
              container + " .paste-image-text," +
              container + " .text-editor-block, .text-styling-block,"+
              container + " .image-time-block span,"+
              container + " .image-background-block > span,"+
              container + " .image-filter-block span").hide('slow');
      oss.params.swiperSlider.unlockSwipes();
      currentEditImgId = 0;
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
        if(jQuerOSS(this).draggable( "instance" )){
          jQuerOSS(this).draggable("destroy");
        }
      });

      oss.checkUnsaved();

      jQuerOSS(container + " .existing-text div").remove();
      jQuerOSS("#os-show-settings-button-" + oss.params.moduleId).show();

    }

    //Text Editor fn-s
    oss.addNewText= function (el){
      if(oss.params.debugMode){
        console.log("oss.addNewText",[el]);
      }
      if(jQuerOSS(container + " .editing-text:visible").length){
        jQuerOSS(container + " .save-text-editor").addClass('save-first');
      }else{
        jQuerOSS(container+" .add-image-text,"+container+" .paste-image-text,"+container+" .back-image-edit,"+container+" .image-time-block,"+container+" .image-filter-block,"+container+" .image-background-block").hide();
        jQuerOSS(container+" .cancel-text-editor,"+container+" .save-text-editor").show();
        oss.params.editTextId = -1;
        //reset text settings
        jQuerOSS(container + " .text-block .text-font-size").val(4);
        jQuerOSS(container + " .text-block .text-font-weight-select").val('normal');
        jQuerOSS(container + " .text-block .text-align-select").val('start');
        jQuerOSS(container + " .text-block .os-gallery-autocomplete-input").val('');
        jQuerOSS(container + " .text-block .text-color-colorpicker").val('');
        jQuerOSS(container + " .text-block .text-padding-top").val('');
        jQuerOSS(container + " .text-block .text-padding-right").val('');
        jQuerOSS(container + " .text-block .text-padding-bottom").val('');
        jQuerOSS(container + " .text-block .text-padding-left").val('');

        jQuerOSS(container + " .text-block .text-h-shadow").val('');
        jQuerOSS(container + " .text-block .text-v-shadow").val('');
        jQuerOSS(container + " .text-block .text-blur-radius").val('');
        jQuerOSS(container + " .text-block .text-shadow-colorpicker").val('');


        jQuerOSS(container + " .text-block .text-block-width").val(0);
        jQuerOSS(container + " .text-block .text-background-colorpicker").val('');
        jQuerOSS(container + " .text-block .text-borer-width").val(0);
        jQuerOSS(container + " .text-block .text-borer-radius").val(0);
        jQuerOSS(container + " .text-block .text-border-colorpicker").val('');
        jQuerOSS(container + " .text-block .text-custom-class").val('');
        jQuerOSS(container + " .text-block .text-time-start-input").val(0);
        jQuerOSS(container + " .text-block .text-time-end-input").val(0);
        jQuerOSS(container + " .text-block .permanent-time-start-input").val(0);
        jQuerOSS(container + " .text-block .permanent-time-end-input").val(0);
        jQuerOSS(container + " .input-colorpicker").minicolors('destroy');

        oss.makeTextColorpicker();

        textStyle = 'style="font-size:'+oss.toPx("4")+'px;line-height:100%;font-weight:normal;width:auto;height:auto;"';

        //end
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('text-active');
        sliderEl = '<div '+textStyle+' class="slide-text text-active" data-image-id="'+currentEditImgId+'" '+
                        'data-text-id="'+(++oss.params.textId)+
                        '" data-text-order-id="'+oss.params.currentTextOrderId+
                        '" data-text-body="{}" ></div>';
        jQuerOSS(oss.params.swiperSlider.slides).each(function(index, slide) {
          if(!jQuerOSS(slide).hasClass('swiper-slide-duplicate')){
            if(jQuerOSS(slide).attr("data-image-id") == currentEditImgId){
              jQuerOSS(slide).append(sliderEl);
            }
          }
        });


        jQuerOSS(container+" .anim-type .start-animations-list,"+
                container+" .anim-type .end-animations-list,"+
                container+" .anim-type .permanent-animations-list,"+
                container+" .anim-type .hover-animations-list").attr("data-text-id" ,oss.params.textId);

        jQuerOSS(container+" .anim-type .start-animations-list,"+
                container+" .anim-type .end-animations-list,"+
                container+" .anim-type .permanent-animations-list,"+
                container+" .anim-type .hover-animations-list").attr("data-image-id" ,currentEditImgId);



        jQuerOSS(container + " .editing-text").val('');
        jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").show("slow");
        oss.textDraggable();
      }
      oss.checkUnsaved();
    }

    oss.textDraggable = function (){
      if(oss.params.debugMode){
        console.log("oss.textDraggable",['without arguments']);
      }

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").draggable({
        containment: "#os-slider-"+oss.params.moduleId,
        drag: function(event, ui){

         if(jQuerOSS(container+" .text-block .text-block-width").val() > 0){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('width',jQuerOSS(container+" .text-block .text-block-width").val()+'%');
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('width','auto');
          }
         jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").height('auto');

        },
        stop: function( event, ui ) {

          jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css("left", ui.position.left*100/ui.helper.parent().width()+'%');
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css("top", ui.position.top*100/ui.helper.parent().height()+'%');

          if(jQuerOSS(container+" .text-block .text-block-width").val() > 0){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('width',jQuerOSS(container+" .text-block .text-block-width").val()+'%');
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('width','auto');
          }
          oss.sortImagesText(oss.params.textOrdering, jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").attr("data-image-id"));

        }
      });
    }

    oss.cancelTextEditor = function (el){
      if(oss.params.debugMode){
        console.log("oss.cancelTextEditor",[el]);
      }


      if(jQuerOSS(el).length){
        jQuerOSS(container + " .existing-text,"+container +"  .image-time-block,"+container +"  .image-filter-block,"+container +"  .image-background-block").show();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
          if(jQuerOSS(this).draggable( "instance" )){
            jQuerOSS(this).draggable("destroy");
          }
        });
        if(oss.params.previousText){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").html(oss.params.previousText);
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").attr("data-text-body", window.JSON.stringify(oss.params.previousText));
          jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").hide("slow");
          oss.params.previousText = '';
        }else{
          //if only 1 text-active ///
          if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").length == 1){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").remove();
            jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").hide("slow");
          }
        }
        jQuerOSS(container + " .save-text-editor").removeClass('save-first');
        jQuerOSS(".cancel-text-editor, .save-text-editor").hide();
        jQuerOSS(".back-image-edit,.add-image-text,.paste-image-text").show();

        oss.makeClickFunction(oss.params.moduleId);

      }

      oss.checkUnsaved();
    }

    oss.saveTextEditor = function (el){

      if(oss.params.debugMode){
        console.log("oss.saveTextEditor",[el]);
      }
      
      if(!jQuerOSS(container + " .editing-text").val()){
        //remove from existing text

        jQuerOSS(container + " .existing-text .slide-text-data").each(function(index, el) {
          if(jQuerOSS(el).attr("data-text-id") == jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").attr("data-text-id")){
            jQuerOSS(el).remove();
          }
        });
        //end
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").remove();
        jQuerOSS("#save-settings-" + oss.params.moduleId).addClass('need-save');
        jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").hide("slow");
      }else if(jQuerOSS(container + " .editing-text").val() && (jQuerOSS(container + " .editing-text").val() != oss.params.previousText)){


        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").addClass('not-saved');
        //new text or edit exist
        //add new text in existing list
        imgId = jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").attr("data-image-id");
        textBody = jQuerOSS(container + " .editing-text").val().substring(0,100);
        textBody = textBody.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        if(oss.params.editTextId != -1){
          dataTextID = oss.params.editTextId;
          newElement = '<span class="icon-menu"></span>'+
                            '<a class="edit-current-text" aria-invalid="false"'+
                            '><i class="fa fa-pencil" aria-hidden="true"></i></a>'+
                            '<a class="copy-current-text oss-pro-avaible" aria-invalid="false"'+
                            '><i class="fa fa-files-o" aria-hidden="true"></i></a>'+
                            '<a class="delete-current-text" aria-invalid="false"'+
                            '><i class="fa fa-close" aria-hidden="true"></i></a>'+

                            // '<input class="text-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-start-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+

                            // '<input class="text-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-end-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+

                            // //permanent time
                            // '<input class="permanent-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-start-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                            
                            // '<input class="permanent-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-end-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                            // //permanent time

                            '<i class="fa fa-question-circle"><span class="text-helper animated fadeInRight">Start and stop text animations!</span></i>'+
                            '<div class="text-line">'+textBody+'</div>';
          jQuerOSS(container + " .existing-text [data-text-id='"+dataTextID+"']").html(newElement);
        }else{
          dataTextID = oss.params.textId;
          newElement = '<div class="slide-text-data" data-image-id="'+imgId+
                              '" data-text-order-id="'+oss.params.currentTextOrderId+
                              '" data-text-id="'+dataTextID+'">'+
                            '<span class="icon-menu"></span>'+
                            '<a class="edit-current-text" aria-invalid="false"'+
                            '><i class="fa fa-pencil" aria-hidden="true"></i></a>'+
                            '<a class="copy-current-text oss-pro-avaible" aria-invalid="false"'+
                            '><i class="fa fa-files-o" aria-hidden="true"></i></a>'+
                            '<a class="delete-current-text" aria-invalid="false"'+
                            '><i class="fa fa-close" aria-hidden="true"></i></a>'+

                            // '<input class="text-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-start-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                            // '<input class="text-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-end-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+

                            // //permanent time
                            // '<input class="permanent-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-start-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                            // '<input class="permanent-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                            //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-end-time")+
                            // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                            // //permanent time

                            '<i class="fa fa-question-circle"><span class="text-helper animated fadeInRight">Start and stop text animations!</span></i>'+
                            '<div class="text-line">'+textBody+'</div>'+
                          '</div>';
          jQuerOSS(container + " .existing-text").append(newElement);
          oss.params.textOrdering[imgId] = jQuerOSS(container+" .existing-text .slide-text-data[data-image-id='"+imgId+"']").parent().sortable('toArray', {attribute: 'data-text-order-id'});
          oss.params.currentTextOrderId++;
        }

        oss.sortImagesText(oss.params.textOrdering, imgId);

        if(jQuerOSS(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active")).draggable( "instance" )){
          jQuerOSS(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active")).draggable("destroy");
        }

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('text-active');
        jQuerOSS(container + " .save-text-editor").removeClass('save-first');
        jQuerOSS(container + " .editing-text").val('');
        jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").hide("slow");
        jQuerOSS(container + " .existing-text").show("slow");
        oss.params.previousText = '';
      }else{
        oss.cancelTextEditor(el);
        oss.sortImagesText(oss.params.textOrdering, jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active").attr("data-image-id"));
      }

      //stop permanent effect
      oss.params.timer.stop();
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('infinite');
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('animated');
      //stop permanent effect

      jQuerOSS(container+" .add-image-text,"+container+" .paste-image-text,"+container+" .back-image-edit,"+container+" .image-time-block,"+container+" .image-filter-block,"+container+" .image-background-block").show();
      jQuerOSS(container+" .cancel-text-editor,"+container+" .save-text-editor").hide();
      oss.checkUnsaved();
      oss.makeClickFunction(oss.params.moduleId);
      oss.makeTimeInput();

    }

    oss.findText = function (id, imageId){
      if(oss.params.debugMode){
        console.log("oss.findText",[id,imageId]);
      }

     jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").unbind('dblclick')

      oss.params.editTextId = id;
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .swiper-slide:not(.swiper-slide-duplicate)").find(".slide-text").each(function(index, el) {
        if(jQuerOSS(el).attr("data-text-id") == id && jQuerOSS(el).attr("data-image-id") == imageId){
          oss.editSlideText(el);
        }
      });
    }

    oss.editSlideText = function (el) {

      if(oss.params.debugMode){
        console.log("oss.editSlideText",[el]);
      }

      if(jQuerOSS(container + " .add-image-text:visible").length){
        jQuerOSS("#os-show-settings-button-" + oss.params.moduleId + ", .existing-text").hide();
        if(jQuerOSS(container + " .editing-text:visible").length && (jQuerOSS(".editing-text").val() != oss.params.previousText)){
          jQuerOSS(container + " .save-text-editor").addClass('save-first');
        }else{
          jQuerOSS(container+" .add-image-text,"+container+" .paste-image-text,"+container+" .back-image-edit,"+container+" .image-time-block,"+container+" .image-filter-block,"+container+" .image-background-block").hide();
          jQuerOSS(container+" .cancel-text-editor,"+container+" .save-text-editor").show();
          //delete previous selected animation in start list

          //add text/iamge id for text list
          jQuerOSS(container+" .start-text-animation .start-animations-list, "+
                  container+" .end-text-animation .end-animations-list, "+
                  container+" .permanent-text-animation .permanent-animations-list, "+
                  container+" .hover-text-animation .hover-animations-list").attr("data-text-id" ,jQuerOSS(el).attr("data-text-id"));
          jQuerOSS(container+" .start-text-animation .start-animations-list, "+
                  container+" .end-text-animation .end-animations-list, "+
                  container+" .permanent-text-animation .permanent-animations-list, "+
                  container+" .hover-text-animation .hover-animations-list").attr("data-image-id" ,jQuerOSS(el).attr("data-image-id"));


          //end
          //add selected value



          if(typeof(oss.params.textAnimation.start) != 'undefined' 
              && oss.params.textAnimation.start[jQuerOSS(el).attr("data-image-id")] 
              && oss.params.textAnimation.start[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]){
            
              jQuerOSS(container+" .start-text-animation .os-gallery-autocomplete-input-anim-start").val(oss.capitalizeFirstLetter(oss.params.textAnimation.start[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]));
          }

          if(typeof(oss.params.textAnimation.end) != 'undefined' 
              && oss.params.textAnimation.end[jQuerOSS(el).attr("data-image-id")] 
              && oss.params.textAnimation.end[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]){
            
              jQuerOSS(container+" .end-text-animation .os-gallery-autocomplete-input-anim-end").val(oss.capitalizeFirstLetter(oss.params.textAnimation.end[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]));
          }

          if(typeof(oss.params.textAnimation.permanent) != 'undefined' 
              && oss.params.textAnimation.permanent[jQuerOSS(el).attr("data-image-id")] 
              && oss.params.textAnimation.permanent[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]){
            
              jQuerOSS(container+" .permanent-text-animation .os-gallery-autocomplete-input-anim-permanent").val(oss.capitalizeFirstLetter(oss.params.textAnimation.permanent[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]));
          }

          if(typeof(oss.params.textAnimation.hover) != 'undefined' 
              && oss.params.textAnimation.hover[jQuerOSS(el).attr("data-image-id")] 
              && oss.params.textAnimation.hover[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]){
            
              jQuerOSS(container+" .hover-text-animation .os-gallery-autocomplete-input-anim-hover").val(oss.capitalizeFirstLetter(oss.params.textAnimation.hover[jQuerOSS(el).attr("data-image-id")][jQuerOSS(el).attr("data-text-id")]));
          }

          currentEditImgId = jQuerOSS(el).attr("data-image-id");
          oss.params.previousText = window.JSON.parse(jQuerOSS(el).attr("data-text-body") || '{}');
          textBody = window.JSON.parse(jQuerOSS(el).attr("data-text-body") || '{}');
          //existing text list
          jQuerOSS(container + " .existing-text div").remove();
          jQuerOSS(oss.params.swiperSlider.slides).each(function(index, slide) {
            if(!jQuerOSS(slide).hasClass('swiper-slide-duplicate')){
              if(jQuerOSS(slide).attr("data-image-id") == jQuerOSS(el).attr("data-image-id")){
                perView = jQuerOSS("#slidesPerView-" + oss.params.moduleId).val();
                perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val();
                if(perView > 1 || perColumn > 1){
                  //what it is? i don't now but it's work.
                  k = Math.floor(index/(perView*perColumn));
                  oss.params.swiperSlider.slideTo(k*perView);
                }else{
                  oss.params.swiperSlider.slideTo(index);
                }
                oss.params.swiperSlider.lockSwipes();
                //create list of existinf text field

                  jQuerOSS(slide).find(".slide-text").each(function(index, el) {
                      //set dinamic id for identificate text
                      text = jQuerOSS(el).html().substring(0,100);
                      text = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                      newElement = '<div class="slide-text-data" data-image-id="'+jQuerOSS(slide).attr("data-image-id")+
                                        '" data-text-order-id="'+jQuerOSS(el).attr("data-text-order-id")+
                                        '" data-text-id="'+jQuerOSS(el).attr("data-text-id")+'">'+
                                    '<span class="icon-menu"></span>'+
                                    '<a class="edit-current-text" aria-invalid="false"'+
                                    '><i class="fa fa-pencil" aria-hidden="true"></i></a>'+
                                    '<a class="copy-current-text oss-pro-avaible" aria-invalid="false"'+
                                    '><i class="fa fa-files-o" aria-hidden="true"></i></a>'+
                                    '<a class="delete-current-text" aria-invalid="false"'+
                                    '><i class="fa fa-close" aria-hidden="true"></i></a>'+

                                    // '<input class="text-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                                    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").attr("data-text-start-time")+
                                    // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                                    // '<input class="text-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                                    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").attr("data-text-end-time")+
                                    // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+

                                    // //permanent time
                                    // '<input class="permanent-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                                    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").attr("data-permanent-start-time")+
                                    // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                                    // '<input class="permanent-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                                    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").attr("data-permanent-end-time")+
                                    // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                                    // //permanent time

                                    '<i class="fa fa-question-circle"><span class="text-helper animated fadeInRight">Start and stop text animations!</span></i>'+
                                    '<div class="text-line">'+text+'</div>'+
                                  '</div>'
                      jQuerOSS(container + " .existing-text").append(newElement);
                      if(oss.params.textId < parseInt(jQuerOSS(el).attr("data-text-id"))){
                        oss.params.textId = jQuerOSS(el).attr("data-text-id");
                      }
                  });
                //end lists
              }
            }
          });
          //end
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('text-active');


          

          //stop permanent effect
          oss.params.timer.stop();
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('infinite');
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('animated');
          //stop permanent effect

          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
            if(jQuerOSS(this).draggable( "instance" )){
              jQuerOSS(this).draggable("destroy");
            }
          });
          jQuerOSS(el).addClass('text-active');

          oss.textDraggable();

          jQuerOSS(container + " .editing-text").val(textBody);
          jQuerOSS(container + " .save-text-editor").removeClass('save-first');
          //css
          jQuerOSS(container + ' .text-color-colorpicker').minicolors('value',jQuerOSS(el).css('color'));
          jQuerOSS(container + ' .text-background-colorpicker').minicolors('value',jQuerOSS(el).css('background-color'));
          jQuerOSS(container + ' .text-border-colorpicker').minicolors('value',jQuerOSS(el).css('borderTopColor'));

          var regExpShadow = /([rgba]*[(]*[\d.,\s]*[)]+)/;
          var text_shadow = jQuerOSS(el).css('text-shadow');
          var text_shadow = text_shadow.match(regExpShadow);

          if(Array.isArray(text_shadow)){
              jQuerOSS(container + ' .text-shadow-colorpicker').minicolors('value',text_shadow[0]);
          }else{
              jQuerOSS(container + ' .text-shadow-colorpicker').minicolors('value','rgba(255,255,255,1)');
          }

          jQuerOSS(container + " .text-custom-class").val(jQuerOSS(el).attr("data-custom-class"));

          jQuerOSS(container + " .text-time-start-input").val(jQuerOSS(el).attr("data-text-start-time"));
          jQuerOSS(container + " .text-time-end-input").val(jQuerOSS(el).attr("data-text-end-time"));
          jQuerOSS(container + " .permanent-time-start-input").val(jQuerOSS(el).attr("data-permanent-start-time"));
          jQuerOSS(container + " .permanent-time-end-input").val(jQuerOSS(el).attr("data-permanent-end-time"));



          
          jQuerOSS(container + " .text-borer-width").val(
            (Math.round(jQuerOSS(el).attr("data-border-width")*10)/10)
          );

          jQuerOSS(container + " .text-borer-radius").val(
            (Math.round(jQuerOSS(el).attr("data-border-radius")*10)/10)
          );

          //text padding
          jQuerOSS(container + " .text-padding-top").val(
            (Math.round(jQuerOSS(el).attr("data-padding-top")*10)/10)
          );

          jQuerOSS(container + " .text-padding-right").val(
            (Math.round(jQuerOSS(el).attr("data-padding-right")*10)/10)
          );

          jQuerOSS(container + " .text-padding-bottom").val(
            (Math.round(jQuerOSS(el).attr("data-padding-bottom")*10)/10)
          );

          jQuerOSS(container + " .text-padding-left").val(
            (Math.round(jQuerOSS(el).attr("data-padding-left")*10)/10)
          );

          // text shadow
          jQuerOSS(container + " .text-h-shadow").val(
            (Math.round(jQuerOSS(el).attr("data-text-h-shadow")*10)/10)
            );
          jQuerOSS(container + " .text-v-shadow").val(
            (Math.round(jQuerOSS(el).attr("data-text-v-shadow")*10)/10)
            );
          jQuerOSS(container + " .text-blur-radius").val(
            (Math.round(jQuerOSS(el).attr("data-text-blur-radius")*10)/10)
            );
          // text shadow
          
          jQuerOSS(container + " .text-block-width").val(jQuerOSS(el).attr("data-text-width") || 0);
          // jQuerOSS(container + " .text-font-size").val(jQuerOSS(el).css("font-size").replace('px',''));

          jQuerOSS(container + " .text-font-size").val(
            (Math.round(jQuerOSS(el).attr("data-font-size")*10)/10)
          );

          //font weight select
          jQuerOSS(container + ' .text-font-weight-select option').remove();
          jQuerOSS(container + ' .text-font-weight-select')
                    .append(jQuerOSS('<option></option>').attr("value", "normal").text("normal"));

          if(oss.params.avaibleGoogleFontsWeights[oss.params.avaibleGoogleFonts.indexOf(jQuerOSS(el).attr("data-font-family"))]){
            fontWeightQuery='';
            fontWeights = oss.params.avaibleGoogleFontsWeights[oss.params.avaibleGoogleFonts.indexOf(jQuerOSS(el).attr("data-font-family"))];
            jQuerOSS(fontWeights)
              .each(function(index, val){
                  jQuerOSS(container + ' .text-font-weight-select')
                    .append(jQuerOSS('<option></option>').attr("value", val).text(val));
              fontWeightQuery += fontWeights[index];
              if(index < fontWeights.length-1)fontWeightQuery += ',';
            });

            //load needed fonts
            WebFont.load({
              google: {
                families: [jQuerOSS(el).attr("data-font-family") +':'+ fontWeightQuery]
              }
            });
            //end
          }
          if(jQuerOSS(el).css("font-weight") == 400 || !jQuerOSS(el).css("font-weight")){
            fWeight = 'normal';
          }else{
            fWeight = jQuerOSS(el).css("font-weight");
          }

          jQuerOSS(container + " .text-font-weight-select").val(fWeight);

          if(jQuerOSS(el).css("text-align")){
            tAlign = jQuerOSS(el).css("text-align");
            // alert(tAlign);
          }else{
            tAlign = 'inherit';
          }

          jQuerOSS(container + " .text-align-select").val(tAlign);
          //end
          //select font
          if(oss.params.setupFonts.indexOf(jQuerOSS(el).css("font-family").replace(/\"/g, "")) != -1){
            fFamily = jQuerOSS(el).css("font-family").replace(/\"/g, "");
          }else{
            fFamily = '';
          }
          jQuerOSS(container + " .os-gallery-autocomplete-input").val(fFamily);
          //
          jQuerOSS(container + " .text-editor-block,"+container+" .text-styling-block").show("slow");
        }
      }else{
        if(jQuerOSS(container + " .existing-images:visible").length){
          oss.params.swiperSlider.lockSwipes();
          oss.imageEdit(jQuerOSS(el).parent().parent().find("img"));
          oss.editSlideText(el);
        }
      }
      oss.checkUnsaved();
    }
    //end

    //delete text
    oss.deleteCurrentText = function (el){
      if(oss.params.debugMode){
        console.log("oss.deleteCurrentText",[el]);
      }

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el2) {

        if(localStorage.getItem('id') == jQuerOSS(el).parent().attr("data-text-id"))
          {
            localStorage.clear();
          }

          if(jQuerOSS(el2).attr("data-text-id") == jQuerOSS(el).parent().attr("data-text-id") 
            && jQuerOSS(el2).attr("data-image-id") == jQuerOSS(el).parent().attr("data-image-id")){
          jQuerOSS(el2).remove();
          jQuerOSS(el).parent().remove();
            if(oss.params.textAnimation.start
                && oss.params.textAnimation.start[jQuerOSS(el).parent().attr("data-image-id")]){
              delete oss.params.textAnimation.start[jQuerOSS(el).parent().attr("data-image-id")][jQuerOSS(el).parent().attr("data-text-id")];
            }
            if(oss.params.textAnimation.end
                && oss.params.textAnimation.end[jQuerOSS(el).parent().attr("data-image-id")]){
              delete oss.params.textAnimation.end[jQuerOSS(el).parent().attr("data-image-id")][jQuerOSS(el).parent().attr("data-text-id")];
            }
            if(oss.params.textAnimation.permanent
                && oss.params.textAnimation.permanent[jQuerOSS(el).parent().attr("data-image-id")]){
              delete oss.params.textAnimation.permanent[jQuerOSS(el).parent().attr("data-image-id")][jQuerOSS(el).parent().attr("data-text-id")];
            }
            if(oss.params.textAnimation.hover
                && oss.params.textAnimation.hover[jQuerOSS(el).parent().attr("data-image-id")]){
              delete oss.params.textAnimation.hover[jQuerOSS(el).parent().attr("data-image-id")][jQuerOSS(el).parent().attr("data-text-id")];
            }

        }
      });
    }
    //end
    
    //sort existing text
    oss.sortImagesText = function (sortArray, imgid){
      if(oss.params.debugMode){
        console.log("oss.sortImagesText",[sortArray]);
      }
      jQuerOSS(sortArray[imgid]).each(function(indexSortArray, sortingId) {
        jQuerOSS(container+" .existing-text .slide-text-data[data-image-id='"+imgid+"']").each(function(indexText, text) {
          if(jQuerOSS(this).attr("data-text-order-id") == sortingId){
            jQuerOSS(this).parent().append(this);
          }
        });
      });
      oss.sortSliderText(sortArray, imgid);
    }

    oss.sortSliderText = function (sortArray, imgid){
      if(oss.params.debugMode){
        console.log("oss.sortSliderText",[sortArray]);
      }

      jQuerOSS(sortArray[imgid]).each(function(indexSortArray, sortingId) {
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id='"+imgid+"']:not(.swiper-slide-duplicate) .slide-text").each(function(indexText, text){
          if(jQuerOSS(this).attr("data-text-order-id") == sortingId){
            jQuerOSS(this).parent().append(this);
          }
        });
      });
    }
    //end

    oss.fileExport = function(){
       if(oss.params.debugMode){
          console.log("oss.fileExport");
        }

          jQuerOSS.post("index.php?option=com_ajax&module=os_touchslider&Itemid="+oss.params.ItemId+"&task=fileExport&moduleId="+oss.params.moduleId+"&format=raw",
          function (data) {
            if (data.success) {
            
               var zipPath = data.path;
               jQuerOSS(container + " .export-responce").text("Load export file");
               jQuerOSS(container + " .export-responce").css('backgroundColor','#3A8BDF');
               jQuerOSS(container + " .export-responce").hover(function() {
                 jQuerOSS(this).css('backgroundColor','#0E9267');
               }, function() {
                 jQuerOSS(this).css('backgroundColor','#3A8BDF');
               });
               jQuerOSS(container).click(function(){
                  jQuerOSS(container + " .export-responce").hide(500);
               })
               jQuerOSS(container + " .export-responce").show(500);
               jQuerOSS(container + " .export-a").attr('href', zipPath);
               jQuerOSS(container + " .export-responce").unbind('click');
               jQuerOSS(container + " .export-responce").click(function(){
                  jQuerOSS(this).hide(500);
               })

            }else{
              
               // jQuerOSS(container + " .option-title").hide(500);
               jQuerOSS(container + " .export-responce").show(500);
               jQuerOSS(container + " .export-responce").text("Export failed");
               jQuerOSS(container + " .export-responce").css('backgroundColor','#f7484e');
               jQuerOSS(container + " .export-responce").unbind('click');
               jQuerOSS(container + " .export-responce").click(function(){
                  jQuerOSS(this).hide(400);

               })
            }
          } , 'json' );
    }

    oss.copyText = function(id, text, parent_img_id){
      if(oss.params.debugMode){
          console.log("oss.copyText",[id,text]);
      }
         localStorage.clear();
         localStorage.setItem('text',text);
         localStorage.setItem('id',id);
         localStorage.setItem('parent_img_id',parent_img_id);
    }

    oss.pasteText = function(){
      if(oss.params.debugMode){
            console.log("oss.pasteText",['accept localStorage data']);
          }

            parentTextBody = localStorage.getItem('text');
            parentTextId = localStorage.getItem('id');
            parent_img_id = localStorage.getItem('parent_img_id');

            // alert(parentTextId);

            if(!parentTextBody || !parentTextId)
            {
               localStorage.clear();
               return;
            } 

            imgId = jQuerOSS("#os-slider-"+oss.params.moduleId+" .edit-image").attr("data-image-id");
            dataTextID = ++oss.params.textId;
            oss.params.currentTextOrderId++;

            displayText = jQuerOSS('#os-slider-'+oss.params.moduleId+' div.slide-text[data-text-id='+parentTextId+'][data-image-id='+parent_img_id+']').clone();

            displayText.addClass('not-saved');
            displayText.attr('data-text-id',dataTextID);
            displayText.attr('data-image-id',imgId);
            displayText.attr('data-text-order-id',oss.params.currentTextOrderId);

            jQuerOSS('#os-slider-'+oss.params.moduleId+' div.swiper-slide[data-image-id='+imgId+']').append(displayText);

            newElement = '<div class="slide-text-data" data-image-id="'+imgId+
                          '" data-text-order-id="'+oss.params.currentTextOrderId+
                          '" data-text-id="'+dataTextID+'">'+
                          '<span class="icon-menu"></span>'+
                          '<a class="edit-current-text" aria-invalid="false"'+
                          '><i class="fa fa-pencil" aria-hidden="true"></i></a>'+
                          //bch
                          '<a class="copy-current-text oss-pro-avaible" aria-invalid="false"'+
                          '><i class="fa fa-files-o" aria-hidden="true"></i></a>'+
                          //bch
                          '<a class="delete-current-text" aria-invalid="false"'+
                          '><i class="fa fa-close" aria-hidden="true"></i></a>'+

                          // '<input class="text-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                          //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-start-time")+
                          // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                          // '<input class="text-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                          //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-text-end-time")+
                          // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+

                          // //permanent time
                          // '<input class="permanent-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                          //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-start-time")+
                          // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                          // '<input class="permanent-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                          //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+dataTextID+"'][data-image-id='"+imgId+"']").attr("data-permanent-end-time")+
                          // '" data-text-id='+dataTextID+' data-image-id="'+imgId+'">'+
                          // //permanent time

                          '<i class="fa fa-question-circle"><span class="text-helper animated fadeInRight">Start and stop text animations!</span></i>'+
                          '<div class="text-line">'+parentTextBody+'</div>'+
                          '</div>';
              jQuerOSS(container + " .existing-text ").append(newElement);

              jQuerOSS(container + " .slide-text-data[data-text-id="+dataTextID+"]").hide();
              jQuerOSS(container + " .slide-text-data[data-text-id="+dataTextID+"]").fadeIn();

              //start - end animation
              if(oss.params.textAnimation.start && oss.params.textAnimation.end){

                if(parent_img_id!=imgId){
                    oss.params.textAnimation.start[imgId] = Array();
                    oss.params.textAnimation.end[imgId] = Array();
                }
               
                if(oss.params.textAnimation.start && oss.params.textAnimation.start[parent_img_id] && oss.params.textAnimation.start[parent_img_id][parentTextId]){
                   oss.params.textAnimation.start[imgId][dataTextID] = oss.params.textAnimation.start[parent_img_id][parentTextId]
                }

                if(oss.params.textAnimation.end && oss.params.textAnimation.end[parent_img_id] && oss.params.textAnimation.end[parent_img_id][parentTextId]){
                  oss.params.textAnimation.end[imgId][dataTextID] = oss.params.textAnimation.end[parent_img_id][parentTextId]
                }
                
              }

              //permanent animation
              if(oss.params.textAnimation.permanent){

                if(parent_img_id!=imgId){
                    oss.params.textAnimation.permanent[imgId] = Array();
                }
               
                if(oss.params.textAnimation.permanent && oss.params.textAnimation.permanent[parent_img_id] && oss.params.textAnimation.permanent[parent_img_id][parentTextId]){
                   oss.params.textAnimation.permanent[imgId][dataTextID] = oss.params.textAnimation.permanent[parent_img_id][parentTextId]
                }
                
              }

              //hover animation
              if(oss.params.textAnimation.hover){

                if(parent_img_id!=imgId){
                    oss.params.textAnimation.hover[imgId] = Array();
                }
               
                if(oss.params.textAnimation.hover && oss.params.textAnimation.hover[parent_img_id] && oss.params.textAnimation.hover[parent_img_id][parentTextId]){
                   oss.params.textAnimation.hover[imgId][dataTextID] = oss.params.textAnimation.hover[parent_img_id][parentTextId]
                }
                
              }
              
              oss.params.textOrdering[imgId] = jQuerOSS(container+" .existing-text .slide-text-data[data-image-id='"+imgId+"']").parent().sortable('toArray', {attribute: 'data-text-order-id'});
              
              jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.swiper-slide-duplicate) .slide-text").css("opacity",1);
              // oss.sortImagesText(oss.params.textOrdering, imgId);
              //end
              if(jQuerOSS(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active")).draggable( "instance" )){
                jQuerOSS(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text.text-active")).draggable("destroy");
              }

              oss.checkUnsaved();
              oss.makeClickFunction(oss.params.moduleId);
              oss.makeTimeInput();

    }


    //bucha
    oss.create_custom_img = function(color){
      if(oss.params.debugMode){
        console.log("oss.create_custom_img",'[color]');
      }

      jQuerOSS.post("index.php?option=com_ajax&module=os_touchslider&Itemid="+oss.params.ItemId+"&moduleId="+oss.params.moduleId+"&format=raw&method=create_custom_img",

        function (responseJSON) {      
          if(oss.params.debugMode){
            console.log("copyImage.responseJSON",[responseJSON]);
          }

          if(responseJSON.img_id == false){
            alert(responseJSON.error);
          }else{

            var img_id = responseJSON.img_id;
            var fileName = responseJSON.fileName;
            var fileName_thumb = responseJSON.fileName_thumb;
            var custom_color = color;
            

            //add thumbnail
            var slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/thumbnail/'+fileName_thumb;
            var image = '<div class="slider-images" data-sortable-id="'+img_id+'">'+
                          '<div class="slider-image-block">'+
                            '<a class="delete-current-image" type="button" aria-invalid="false">'+
                            '<i class="fa fa-close" aria-hidden="true"></i></a>'+
                            '<a class="edit-current-image" type="button" '+
                            'aria-invalid="false" value="-E-">'+
                            '<i class="fa fa-pencil" aria-hidden="true"></i></a>'+

                            //bch
                            '<a class="copy-current-image oss-pro-avaible" aria-invalid="false">'+
                            '<i class="fa fa-files-o" aria-hidden="true"></i></a>'+

                            '<a class="replace-current-image oss-pro-avaible" aria-invalid="false">'+
                            '<i class="fa fa-picture-o" aria-hidden="true"></i></a>'+
                            //bch
                            
                            '<img class="slider-image" src="'+slideSrc+'" alt="'+fileName+'" data-image-id="'+img_id+'">'+
                          '</div>'+
                        '</div>';

            jQuerOSS(container + " .existing-images").append(image);
            //add thumbnail




            //image full time
            var timeInput = '<span data-image-id="'+img_id+'" class="image-full-time" style="display:none;">'+
                         'Image full time, s:<input class="time-input" type="number" name="image_full_time['+img_id+']" min="0" step="0.1" value="">'+
                        '</span>';
            jQuerOSS(container + " .image-time-block").append(timeInput);

            //image filters
                jQuerOSS(container + " .image-filter-block").append(oss.addFilterSelect(img_id));
            //image filters

            //image background
             
              var background = '<span data-image-id="'+img_id+'" class="image-background" style="display:none;">Background:<i title="To use, apply a transparency effect to the image or load a transparent image." class="fa fa-info-circle info_block"></i><input class="background-input custom_color_slide-'+oss.params.moduleId+'" type="text" data-image-id="'+img_id+'" name="image_background'+img_id+'" min="0" step="0.1" value="'+custom_color+'"></span>';

              jQuerOSS(container + " .image-background-block").append(background);
            //image background


            //add original
            slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/original/'+fileName;

            if(oss.params.lazyLoading){
              newSlide = '<img class="swiper-lazy" data-src="'+slideSrc+'" data-image-id="'+img_id+'">'+
                         '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
            }else{
              newSlide = '<img src="'+slideSrc+'" alt="'+fileName+'" data-image-id="'+img_id+'">';
            }

            oss.params.swiperSlider.appendSlide('<div class="swiper-slide" data-image-id="'+img_id+'">'+newSlide+'</div>');
            //add original

            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").attr('data-image-background',custom_color)

            //apply params
              // oss.saveSliderSettings(oss.params.moduleId,true);
              oss.addBackgroundToThumbs();
              oss.makeSortable();
              oss.params.imageOrdering = jQuerOSS(container + " .existing-images").sortable('toArray', {attribute: 'data-sortable-id'});
              oss.sortSliderImages(oss.params.imageOrdering);
              oss.makeClickFunction(oss.params.moduleId);
              oss.resetSlider();
              oss.checkUnsaved();
              oss.makeTimeInput();
            //apply params

          }
        }

      , 'json');

    } 
    //bucha



    if(oss.params.isUser == 1){

      oss.copyImage = function(parent_img_id){

        if(oss.params.debugMode){
              console.log("oss.copyImage",[parent_img_id]);
            }

            jQuerOSS.post("index.php?option=com_ajax&module=os_touchslider&Itemid="+oss.params.ItemId+"&moduleId="+oss.params.moduleId+"&format=raw&image_id="+parent_img_id+"&method=copyImage",

                      function (responseJSON) {
                              
                      if(oss.params.debugMode){
                        console.log("copyImage.responseJSON",[responseJSON]);
                      }

                        parent_img_id = parent_img_id;
                        image_id = responseJSON.id;
                        fileName = responseJSON.file;
                        ext = responseJSON.ext;
                        //paste image 
                        slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/thumbnail/'+fileName+'_150_100_1'+ext;
                        image = '<div class="slider-images" data-sortable-id="'+image_id+'">'+
                                  '<div class="slider-image-block">'+
                                  '<a class="delete-current-image" type="button" aria-invalid="false">'+
                                  '<i class="fa fa-close" aria-hidden="true"></i></a>'+
                                  '<a class="edit-current-image" type="button" '+
                                  'aria-invalid="false" value="-E-">'+
                                  '<i class="fa fa-pencil" aria-hidden="true"></i></a>'+

                                  //bch
                                  '<a class="copy-current-image oss-pro-avaible" aria-invalid="false">'+
                                  '<i class="fa fa-files-o" aria-hidden="true"></i></a>'+

                                  '<a class="replace-current-image oss-pro-avaible" aria-invalid="false">'+
                                  '<i class="fa fa-picture-o" aria-hidden="true"></i></a>'+
                                  //bch
                                  
                                  '<img class="slider-image" src="'+slideSrc+'" alt="'+fileName+ext+'" data-image-id="'+image_id+'">'+
                                '</div>'+
                                '</div>';


                        jQuerOSS(container + " .existing-images").append(image);

                        slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/original/'+fileName+ext;

                        //image full time
                        var timeInput = '<span data-image-id="'+image_id+'" class="image-full-time" style="display:none;">'+
                                     'Image full time, s:<input class="time-input" type="number" name="image_full_time['+image_id+']" min="0" step="0.1" value="'+jQuerOSS(".image-full-time[data-image-id="+parent_img_id+"] input").val()+'">'+
                                    '</span>';
                        jQuerOSS(container + " .image-time-block").append(timeInput);

                        //image filters
                          var filterSelectCopy = jQuerOSS(container+" span.image-filter[data-image-id="+parent_img_id+"]")[0].outerHTML;
                              filterSelectCopy = filterSelectCopy.replaceAll(parent_img_id, image_id);
                              jQuerOSS(container + " .image-filter-block").append(filterSelectCopy);
                        //image filters

                        //image background
                          var backgroundValue = jQuerOSS(container+" span.image-background[data-image-id="+parent_img_id+"] input").val();

                          var backgroundSelectCopy = '<span data-image-id="'+image_id+'" class="image-background" style="display:none;">Background:<i title="To use, apply a transparency effect to the image or load a transparent image." class="fa fa-info-circle info_block"></i><input class="background-input custom_color_slide-'+oss.params.moduleId+'" type="text" data-image-id="'+image_id+'" name="image_background'+image_id+'" min="0" step="0.1" value="'+backgroundValue+'"></span>';

                          jQuerOSS(container + " .image-background-block").append(backgroundSelectCopy);
                        //image background


                        if(oss.params.lazyLoading){
                          newSlide = '<img class="swiper-lazy " data-src="'+slideSrc+'" data-image-id="'+image_id+'">'+
                                     '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
                        }else{
                          newSlide = '<img class="" src="'+slideSrc+'" alt="'+fileName+ext+'" data-image-id="'+image_id+'">';
                        }

                        oss.params.swiperSlider.appendSlide('<div class="swiper-slide" data-image-id="'+image_id+'">'+newSlide+'</div>');

                        //filters
                        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+image_id+"]").not('.swiper-slide-duplicate')
                        .addClass(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+parent_img_id+"]").not('.swiper-slide-duplicate').attr('data-image-filter'))
                        .attr('data-image-filter',jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+parent_img_id+"]").not('.swiper-slide-duplicate').attr('data-image-filter'));

                        //background
                        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+image_id+"]").not('.swiper-slide-duplicate')
                        .css('backgroundColor',jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+parent_img_id+"]").not('.swiper-slide-duplicate').attr('data-image-background'))
                        .attr('data-image-background',jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+parent_img_id+"]").not('.swiper-slide-duplicate').attr('data-image-background'));

                      
                      
                        setTimeout(function(){
                          jQuerOSS(container + " .qqsl-upload-list").empty();
                        }, 5000);
                        //paste image 

                        //paste text
            
                        if(oss.params.textAnimation.start && oss.params.textAnimation.end){
                          oss.params.textAnimation.start[image_id] = [];
                          oss.params.textAnimation.end[image_id] =  [];
                        }

                        if(oss.params.textAnimation.permanent){
                          oss.params.textAnimation.permanent[image_id] = [];
                        }

                         if(oss.params.textAnimation.hover){
                          oss.params.textAnimation.hover[image_id] = [];
                        }
                          
                        displayText = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+parent_img_id+"]").not('.swiper-slide-duplicate').find('.slide-text').clone();

                        displayText.each(function(index, el) {

                           ++oss.params.textId;
                           ++oss.params.currentTextOrderId;

                          if(oss.params.textAnimation.start && oss.params.textAnimation.start[parent_img_id])
                          {
                            oss.params.textAnimation.start[image_id][oss.params.textId] 
                              = oss.params.textAnimation.start[parent_img_id][jQuerOSS(el).attr('data-text-id')]
                          }

                          if(oss.params.textAnimation.end && oss.params.textAnimation.end[parent_img_id])
                          {
                            oss.params.textAnimation.end[image_id][oss.params.textId] 
                              = oss.params.textAnimation.end[parent_img_id][jQuerOSS(el).attr('data-text-id')]
                          }

                          if(oss.params.textAnimation.permanent && oss.params.textAnimation.permanent[parent_img_id])
                          {
                            oss.params.textAnimation.permanent[image_id][oss.params.textId] 
                              = oss.params.textAnimation.permanent[parent_img_id][jQuerOSS(el).attr('data-text-id')]
                          }

                          if(oss.params.textAnimation.hover && oss.params.textAnimation.hover[parent_img_id])
                          {
                            oss.params.textAnimation.hover[image_id][oss.params.textId] 
                              = oss.params.textAnimation.hover[parent_img_id][jQuerOSS(el).attr('data-text-id')]
                          }
                            
                          jQuerOSS(el).attr('data-text-id',oss.params.textId);
                          jQuerOSS(el).attr('data-text-order-id',oss.params.currentTextOrderId);
                          jQuerOSS(el).attr('data-image-id',image_id);

                        });

    
                        jQuerOSS(container + " .image-full-time[data-image-id="+parent_img_id+"]");
                        jQuerOSS('.swiper-slide[data-image-id='+image_id+']').append(displayText);

                        oss.addBackgroundToThumbs();
                        oss.saveSliderSettings(oss.params.moduleId,true);
                        oss.makeSortable();
                        oss.params.imageOrdering = jQuerOSS(container + " .existing-images").sortable('toArray', {attribute: 'data-sortable-id'});
                        oss.sortSliderImages(oss.params.imageOrdering);
                        oss.makeClickFunction(oss.params.moduleId);
                        oss.resetSlider();
                        oss.checkUnsaved();
                        oss.makeTimeInput();
                        //paste text  

                        },'json'  );

      }

    }

  //img click // text edit
  oss.imageEdit = function (el){
    if(oss.params.debugMode){
      console.log("oss.imageEdit",[el]);
    }

    jQuerOSS('#tab2-'+oss.params.moduleId+
             ', #tab3-'+oss.params.moduleId+
             ', #tab4-'+oss.params.moduleId).attr('disabled','disabled');

    jQuerOSS(container + " .tab-label").unbind('click');
    jQuerOSS(container + " .existing-text").html('');
    jQuerOSS(container + " .existing-text").show();
    currentTask = 'imageEditor';
    currentEditImgId = jQuerOSS(el).attr("data-image-id");
    workImage = el;
    jQuerOSS(container + " .slider-images, " +
            "#images-load-area-" + oss.params.moduleId +","+container+" .slider-image-block,"+container+" .delete-current-image").hide('slow');
    jQuerOSS(oss.params.swiperSlider.slides).each(function(index, slide) {
      if(!jQuerOSS(slide).hasClass('swiper-slide-duplicate')){
        if(jQuerOSS(slide).attr("data-image-id") == jQuerOSS(el).attr("data-image-id")){
          jQuerOSS(slide).addClass('edit-image');
          perView = jQuerOSS("#slidesPerView-" + oss.params.moduleId).val();
          perColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val();
          if(perView > 1 || perColumn > 1){
            //what it is? i don't now but it's work.
            k = Math.floor(index/(perView*perColumn));
            oss.params.swiperSlider.slideTo(k*perView);
          }else{
            oss.params.swiperSlider.slideTo(index);
          }
          oss.params.swiperSlider.lockSwipes();
          //create list of existinf text field

            //if ordering incorrect
            function array_unique(array) {
                var unique = {};
                jQuerOSS.each(array, function(x, value) {
                    unique[value] = value;
                });
                return jQuerOSS.map(unique, function(value) { return value; });
            }

            var orderInAttrText = [];

            jQuerOSS(slide).find(".slide-text").each(function(index, el) {
              orderInAttrText[index] = jQuerOSS(el).attr("data-text-order-id");
            })

            var uniqueOrder = array_unique(orderInAttrText).length;
            var allOrder = orderInAttrText.length;

            if(uniqueOrder != allOrder){
              jQuerOSS(slide).find(".slide-text").each(function(index, el) {
                jQuerOSS(el).attr("data-text-order-id",++index);
              })
            }
            //if ordering incorrect
            
            
            jQuerOSS(slide).find(".slide-text").each(function(index, el) {
                //set dinamic id for identificate text

                text = jQuerOSS(el).html().substring(0,100);
                text = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                newElement = '<div class="slide-text-data" data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'" data-text-id="'+jQuerOSS(el).attr("data-text-id")+
                                  '" data-text-order-id="'+jQuerOSS(el).attr("data-text-order-id")+'">'+
                              '<span class="icon-menu"></span>'+
                              
                              '<a class="edit-current-text" aria-invalid="false"'+
                              '><i class="fa fa-pencil" aria-hidden="true"></i></a>'+
                              //bch
                              '<a class="copy-current-text oss-pro-avaible" aria-invalid="false"'+
                              '><i class="fa fa-files-o" aria-hidden="true"></i></a>'+
                              //bch
                              '<a class="delete-current-text" aria-invalid="false"'+
                              '><i class="fa fa-close" aria-hidden="true"></i></a>'+

                              // '<input class="text-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                              //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(slide).attr("data-image-id")+"']").attr("data-text-start-time")+
                              // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                              // '<input class="text-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                              //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(slide).attr("data-image-id")+"']").attr("data-text-end-time")+
                              // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+

                              // //permanent time
                              // '<input class="permanent-time-start-input" placeholder="start time" type="number" min="0" step="0.1" value="'+
                              //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(slide).attr("data-image-id")+"']").attr("data-permanent-start-time")+
                              // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                              // '<input class="permanent-time-end-input" placeholder="stop time" type="number" min="0" step="0.1" value="'+
                              //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(el).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(slide).attr("data-image-id")+"']").attr("data-permanent-end-time")+
                              // '" data-text-id='+jQuerOSS(el).attr("data-text-id")+' data-image-id="'+jQuerOSS(slide).attr("data-image-id")+'">'+
                              //permanent time

                              '<i class="fa fa-question-circle"><span class="text-helper animated fadeInRight">Start and stop text animations!</span></i>'+
                              '<div class="text-line">'+text+'</div>'+
                            '</div>'
                jQuerOSS(container + " .existing-text").append(newElement);
                if(oss.params.textId < parseInt(jQuerOSS(el).attr("data-text-id"))){
                  oss.params.textId = jQuerOSS(el).attr("data-text-id");
                }
            });
          //end list
        }
      }
    });
    
   
    jQuerOSS(container +"  .image-time-block span[data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").show();
    jQuerOSS(container +"  .image-filter-block span[data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").show();
    jQuerOSS(container +"  .image-background-block span[data-image-id='"+jQuerOSS(el).attr("data-image-id")+"']").show();

    //stop permanent effect
    oss.params.timer.stop();
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('infinite');
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('animated');

    //stop permanent effect

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.swiper-slide-duplicate) .slide-text").css("opacity",1);
    jQuerOSS("#os-show-settings-button-" + oss.params.moduleId).hide();
    jQuerOSS(container + " .back-image-edit," +
            container + " .paste-image-text," +
            container + " .add-image-text").show('slow');

    oss.textDraggable();
    oss.checkUnsaved();
    oss.makeClickFunction(oss.params.moduleId);
    oss.sortImagesText(oss.params.textOrdering, currentEditImgId);
    oss.makeTimeInput();
  }
  //end

  oss.makeTimeInput = function (){
    if(oss.params.debugMode){
      console.log("oss.makeTimeInput",['without arguments']);
    }

    // jQuerOSS(".text-time-start-input").on("input",function(){
    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(this).attr("data-image-id")+"']")
    //     .attr("data-text-start-time",jQuerOSS(this).val());
    // });
    // jQuerOSS(".text-time-end-input").on("input",function(){
    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(this).attr("data-image-id")+"']")
    //     .attr("data-text-end-time",jQuerOSS(this).val());
    // });

    // //permanent time
    // jQuerOSS(".permanent-time-start-input").on("input",function(){
    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(this).attr("data-image-id")+"']")
    //     .attr("data-permanent-start-time",jQuerOSS(this).val());
    // });
    // jQuerOSS(".permanent-time-end-input").on("input",function(){
    //   jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"'][data-image-id='"+jQuerOSS(this).attr("data-image-id")+"']")
    //     .attr("data-permanent-end-time",jQuerOSS(this).val());
    // });
    //  //permanent time

  }

  //make copyright
  oss.makeCopyright = function (id){
    oss.params.activeTab = id;
    var correction = -15;
    setTimeout(function() {
      var heightDraggable = jQuerOSS(container + " [id^=tab-content"+id+"]").outerHeight()
                          +jQuerOSS("#dragable-settings-block"+oss.params.moduleId).outerHeight()+correction;
      if(jQuerOSS(".copyright-block").css("top") != heightDraggable+'px'){
        jQuerOSS(".copyright-block").hide();
        jQuerOSS(".copyright-block").css("top",heightDraggable);
        jQuerOSS(".copyright-block").show("slow");
      }
    }, 500);
  }
  //end

  //load minicolor
  oss.makeTextColorpicker = function (){
    if(oss.params.debugMode){
      console.log("oss.makeTextColorpicker",['without arguments']);
    }
    jQuerOSS(container + " .input-colorpicker").minicolors({
      control: "hue",
      defaultValue: "",
      format:"rgb",
      opacity:true,
      position: "top right",
      hideSpeed: 100,
      inline: false,
      theme: "bootstrap",
      show: function(){
        oss.preventDraggable();
      },
      change: function(value, opacity) {
        jQuerOSS(this).attr("value",value);
        if(jQuerOSS(this).hasClass('text-color-colorpicker')){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('color', value);
        }
        else if(jQuerOSS(this).hasClass('text-background-colorpicker')){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('background-color', value);
        }
        else if(jQuerOSS(this).hasClass('text-border-colorpicker')){
          if(value){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('border-color', value);
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('border-color', "rgb(255, 255, 255)");
          }
        }else if(jQuerOSS(this).hasClass('text-shadow-colorpicker')){

          var regExpShadow = /([rgba]*[(]*[\d.,\s]*[)]+)/;
          var text_shadow = jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('text-shadow');
          var text_shadow = text_shadow.replace(regExpShadow,'');

          jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css('text-shadow', value+" "+text_shadow);
   
        }
      }
    });
  }
  //end


  //load minicolor
  oss.makeCustomSlideColorpicker = function (){

    if(oss.params.debugMode){
      console.log("oss.makeTextColorpicker",['without arguments']);
    }

    jQuerOSS(".custom_color_slide-"+oss.params.moduleId).minicolors({
      control: "hue",
      defaultValue: "",
      format:"rgb",
      opacity:true,
      position: "bottom right",
      hideSpeed: 100,
      inline: false,
      theme: "bootstrap",
      show: function(){
        oss.preventDraggable();
      }
    });
  }
  //end


// //load minicolor
//   oss.colorTest = function (selector){

//     if(oss.params.debugMode){
//       console.log("oss.makeTextColorpicker",['without arguments']);
//     }

//     jQuerOSS(selector).minicolors({
//       control: "hue",
//       defaultValue: "",
//       format:"rgb",
//       opacity:true,
//       position: "bottom right",
//       hideSpeed: 100,
//       inline: false,
//       theme: "bootstrap",
//       show: function(){
//         oss.preventDraggable();
//       },
//     });
//   }
//   //end


  //make sortable
  oss.makeSortable = function (){
    if(oss.params.debugMode){
      console.log("oss.makeSortable",['without arguments']);
    }
    jQuerOSS(container + " .existing-images").sortable({
      cancel: null, // Cancel the default events on the controls
      connectWith: container + " .slider-images",
      helper: "clone",
      revert: true,
      tolerance: "intersect",
      stop: function( event, ui ) {
        oss.params.imageOrdering = jQuerOSS(this).sortable('toArray', {attribute: 'data-sortable-id'});
        oss.sortSliderImages(oss.params.imageOrdering);
        oss.resetSlider(true);
      }
    }).disableSelection();
  }
  //end
  //get current text order id
  oss.currentTextOrderId = function (){
    if(oss.params.debugMode){
      console.log("oss.currentTextOrderId",['without arguments']);
    }
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.swiper-slide-duplicate) .slide-text[data-text-order-id]").each(function(index, el){
      if(jQuerOSS(el).attr("data-text-order-id") > oss.params.currentTextOrderId){
        oss.params.currentTextOrderId = jQuerOSS(el).attr("data-text-order-id");
      }
    });
    oss.params.currentTextOrderId++;
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.swiper-slide-duplicate) .slide-text:not([data-text-order-id])").each(function(index, el) {
      jQuerOSS(el).attr("data-text-order-id", oss.params.currentTextOrderId);
      oss.params.currentTextOrderId++;
    });

    //sort slider text according to the array
    jQuerOSS(oss.params.textOrdering).each(function(indexSortArray, sortingIdArr) {
      jQuerOSS(sortingIdArr).each(function(index, sortingId) {
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id='"+indexSortArray+"']:not(.swiper-slide-duplicate) .slide-text").each(function(indexText, text){
          if(jQuerOSS(this).attr("data-text-order-id") == sortingId){
            jQuerOSS(this).parent().append(this);
          }
        });
      });
    });
    //end
  }
  //end
  //make sortable
  oss.makeImgTextSortable = function (){
    if(oss.params.debugMode){
      console.log("oss.makeImgTextSortable",['without arguments']);
    }
    jQuerOSS(container + " .existing-text").sortable({
      cancel: null, // Cancel the default events on the controls
      connectWith: container + " .slide-text-data",
      helper: "clone",
      cancel: ".text-time-start-input, .text-time-end-input, .permanent-time-start-input, .permanent-time-end-input",
      revert: true,
      tolerance: "intersect",
      stop: function( event, ui ) {
        oss.params.textOrdering[jQuerOSS(container + " .slide-text-data").attr("data-image-id")] = jQuerOSS(this).sortable('toArray', {attribute: 'data-text-order-id'});
        oss.sortSliderText(oss.params.textOrdering, jQuerOSS(container + " .slide-text-data").attr("data-image-id"));
      }
    });
  }
  //end

  //func for sorting main slider images
  oss.sortSliderImages = function (ordering){
    if(oss.params.debugMode){
      console.log("oss.sortSliderImages",[ordering]);
    }
    jQuerOSS(ordering).each(function(indexSortArray, sortingId) {
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide").each(function(indexSlide, slide) {
        if(jQuerOSS(this).attr("data-image-id") == sortingId){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper").append(this);
        }
      });
    });
  }
  //end

  //function for check new data
  oss.checkUnsaved = function (){
    if(oss.params.debugMode){
      console.log("oss.checkUnsaved",['without arguments']);
    }
    if(jQuerOSS(container + " .not-saved").length && !jQuerOSS("#save-settings-" + oss.params.moduleId).hasClass('need-save')){
      jQuerOSS("#save-settings-" + oss.params.moduleId).addClass('need-save');
    }
  }
  //end

  //load needed google fonts
  oss.loadNededFonts = function (){
    if(oss.params.debugMode){
      console.log("oss.loadNededFonts",['without arguments']);
    }
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .slide-text").each(function(index, el){
      if(jQuerOSS(this).css("font-family").length && oss.params.setupFonts.indexOf(jQuerOSS(this).css("font-family")) >-1){
        if(jQuerOSS(this).css("font-family").length && oss.params.neededGoogleFonts.indexOf(jQuerOSS(this).css("font-family")) == -1){
          oss.params.neededGoogleFonts.push(jQuerOSS(this).css("font-family"));
          oss.params.neededGoogleFontsWeight[jQuerOSS(this).css("font-family")]=[];
        }
        if(jQuerOSS(this).css("font-weight").length && oss.params.neededGoogleFontsWeight[jQuerOSS(this).css("font-family")].indexOf(jQuerOSS(this).css("font-weight")) == -1){
          oss.params.neededGoogleFontsWeight[jQuerOSS(this).css("font-family")].push(jQuerOSS(this).css("font-weight"));
        }
      }
    });



    jQuerOSS(oss.params.neededGoogleFonts).each(function(fontIndex, font) {
      oss.params.neededGoogleFonts[fontIndex] = oss.params.neededGoogleFonts[fontIndex]
                                        .replace(",",'')
                                        .replace(" ",'+')
                                        .replace(" ",'+')
                                        .replace(new RegExp('\\"', 'gi'),'')
                                        .replace("serif",'Serif')
                                        .replace("sans",'Sans');
      oss.params.neededGoogleFonts[fontIndex] += ':'+oss.params.neededGoogleFontsWeight[font].join(",");
    });

    if(oss.params.neededGoogleFonts.length){
      (function() {
        var wf = document.createElement('link');
        wf.href ='https://fonts.googleapis.com/css?family='+oss.params.neededGoogleFonts.join('|');
        wf.type = 'text/css';
        wf.rel = 'stylesheet';
        var s = document.getElementsByTagName('link')[0];
        s.parentNode.insertBefore(wf, s);
      })();
    }
  }
  //end

  jQuerOSS.get("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyD6jQjeJtf5CnSWC27XJv3iui3Pf-lc2_4&sort=popularity",  {}, function (data) {
    for (var i = 0; i < data.items.length; i++) {
      if(oss.params.setupFonts.indexOf(data.items[i].family) > -1){
        if(data.items[i]){
          oss.params.avaibleGoogleFonts.push(data.items[i].family);
          oss.params.avaibleGoogleFontsWeights.push(data.items[i].variants.filter(Number));
          jQuerOSS(container + ' .text-font-select').append(jQuerOSS('<option></option>').attr("value", data.items[i].family).text(data.items[i].family));
        }
      }
    } 

    WebFont.load({
      google: {
        families: [oss.params.avaibleGoogleFonts.join('|')]
      }
    });

  });
  //end


  oss.autocompleteSlideAnimateSelect = function(){
    if(oss.params.debugMode){
      console.log("oss.autocompleteSlideAnimateSelect",['without arguments']);
    }
    //text start open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-slide-start").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-start").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-start:hidden").show();
    });

    //text end open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-slide-end").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-end").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-end:hidden").show();
    });

    //text start autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-start").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-start").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-start:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-start:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    //text end autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-end").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-end").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-end:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-end:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    //text start add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-start").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-start").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-start").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-start").val(animateSelected);
      }
    });
    //text end add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-slide-end").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-end").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-slide-end").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-end").val(animateSelected);
      }
    });

    //add text effect when load page
    if(typeof(oss.params.setupAnimation.start) != 'undefined'){
        jQuerOSS(container+" .start-slide-animation .os-gallery-autocomplete-input-anim-slide-start").val(
          oss.params.setupAnimation.start[0]
        );
    }
    //add text effect when load page
    if(typeof(oss.params.setupAnimation.end) != 'undefined'){
        jQuerOSS(container+" .end-slide-animation .os-gallery-autocomplete-input-anim-slide-end").val(
          oss.params.setupAnimation.end[0]
        );
    }

  }

  oss.autocompleteAnimateSelect = function(){
    if(oss.params.debugMode){
      console.log("oss.autocompleteAnimateSelect",['without arguments']);
    }

    //animation start open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-start").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-start").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-start:hidden").show();
    });

    //animation end open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-end").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-end").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-end:hidden").show();
    });

    //permanent open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-permanent").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-permanent").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-permanent:hidden").show();
    });

    //hover open/close list
    jQuerOSS(container + " .os-gallery-automplete-show-anim-hover").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-hover").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-anim-hover:hidden").show();
    });


    //animation start autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-start").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-start").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-start:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-start:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    //animation end autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-end").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-end").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-end:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-end:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    //animation permanent autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-permanent").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-permanent").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-permanent:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-permanent:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

     //animation hover autocomplete text
    jQuerOSS(container + " .os-gallery-autocomplete-input-anim-hover").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-hover").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-anim-hover:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-anim-hover:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    //animation start add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-start").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-start").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-start").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-start").val(animateSelected);
      }
    });
    //animation end add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-end").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-end").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-end").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-end").val(animateSelected);
      }
    });

    //animation permanent add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-permanent").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-permanent").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-permanent").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-permanent").val(animateSelected);
      }
    });

    //animation hover add text to input after click
    jQuerOSS(container + " .os-gallery-autocomplete-anim-hover").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-hover").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-anim-hover").addClass('ul-hidden');
        var animateSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input-anim-hover").val(animateSelected);
      }
    });

  }

  oss.autocompleteFontSelect = function (){
    if(oss.params.debugMode){
      console.log("oss.autocompleteFontSelect",['without arguments']);
    }

    oss.params.setupFonts = oss.params.setupFonts.sort();

    jQuerOSS(oss.params.setupFonts).each(function(index, el) {

      if(el){
        // console.log(el);

        li = document.createElement('li');
        li.className = "os-gallery-autocomplete-font";
        li.style = "font-family:'"+el+"';";
        jQuerOSS(li).text(el);
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-fonts").append(li);
      }
    });

    //click on open font
    jQuerOSS(container + " .os-gallery-automplete-show-fonts").click(function(event) {
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-fonts").toggleClass('ul-hidden');
      jQuerOSS(container + " .os-gallery-autocomplete-font:hidden").show();
    });


    //start input
    jQuerOSS(container + " .os-gallery-autocomplete-input").on('input', function(event) {
      enter_text = jQuerOSS(this).val();
      event.preventDefault();
      jQuerOSS(container + " .os-gallery-autocomplete-avaible-fonts").removeClass('ul-hidden')
      jQuerOSS(container + " .os-gallery-autocomplete-font:visible").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) == -1){
          jQuerOSS(el).hide();
        }
      });
      jQuerOSS(container + " .os-gallery-autocomplete-font:hidden").each(function(index, el) {
        if(jQuerOSS(el).text().toLowerCase().indexOf(enter_text) != -1){
          jQuerOSS(el).show();
        }
      });
    });

    jQuerOSS(container + " .os-gallery-autocomplete-font").click(function(event) {
      if(!jQuerOSS(container + " .os-gallery-autocomplete-avaible-fonts").hasClass('ul-hidden')){
        jQuerOSS(container + " .os-gallery-autocomplete-avaible-fonts").addClass('ul-hidden');
        var fontSelected = jQuerOSS(this).text();
        jQuerOSS(container + " .os-gallery-autocomplete-input").val(fontSelected);

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .text-active").css("font-family","'"+fontSelected+"'")
                  .css("font-weight","normal")
                  .attr("data-font-family",fontSelected);
        //fix h1-6
        jQuerOSS(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper .text-active"))
              .find("h1,h2,h3,h4,h5,h6").css("font-family",fontSelected)
              .css("font-weight","normal");

        jQuerOSS(container + " .text-font-weight-select option").remove()
        //create font weight select
        if(oss.params.avaibleGoogleFontsWeights[oss.params.avaibleGoogleFonts.indexOf(fontSelected)]){
          var fontWeightQuery='';
          fontWeights = oss.params.avaibleGoogleFontsWeights[oss.params.avaibleGoogleFonts.indexOf(fontSelected)];
          jQuerOSS(container + " .text-font-weight-select").append(jQuerOSS('<option></option>').attr("value", "normal").text("normal"));
          for (var i = 0; i < fontWeights.length; i++) {
            fontWeightQuery += fontWeights[i];
            if(i < fontWeights.length-1)fontWeightQuery += ',';

            jQuerOSS(container + " .text-font-weight-select")
              .append(jQuerOSS('<option></option>').attr("value", fontWeights[i]).text(fontWeights[i]));
          }
          WebFont.load({
            google: {
              families: [fontSelected +':'+ fontWeightQuery]
            }
          });
        }
      }
    });

  }

  oss.showHideArrows = function (){

      if(jQuerOSS("#os-slider-"+oss.params.moduleId+" img.slide-image").length < 2){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").hide();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").addClass('hide');
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").hide();
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").addClass('hide');
      }

      if(oss.params.swiperSlider.isBeginning && params.loop == 0){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").hide()
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").addClass('hide');

      }else if(parseInt(jQuerOSS(container + " .prev_next_arrows:checked").val())){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").show()
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").removeClass('hide');
      }

      if(oss.params.swiperSlider.isEnd && params.loop == 0){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").hide()
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").addClass('hide');
      }else if(parseInt(jQuerOSS(container + " .prev_next_arrows:checked").val())){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").show()
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").removeClass('hide');
      }
  }

  oss.reinitSlider = function (params, setupAnimation){
    if(oss.params.debugMode){
      console.log("oss.reinitSlider",[params, setupAnimation]);
    }

    oss.params.timer.stop();
    oss.params.swiperSlider.destroy(true, true);

    jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container,#os-slider-"+oss.params.moduleId+"  .swiper-wrapper ,#os-slider-"+oss.params.moduleId+" .swiper-slide,#os-slider-"+oss.params.moduleId+"  .swiper-slide div").removeAttr("style");
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-cube-shadow,#os-slider-"+oss.params.moduleId+" .swiper-slide-shadow-right,#os-slider-"+oss.params.moduleId+" .swiper-slide-shadow-left,#os-slider-"+oss.params.moduleId+" .swiper-scrollbar-drag").remove();
    jQuerOSS("#os-slider-"+oss.params.moduleId+".swiper-container .swiper-pagination").removeClass('swiper-pagination-clickable, swiper-pagination-bullets, swiper-pagination-fraction, swiper-pagination-progress');
    oss.params.swiperSlider = new SwiperSl('#os-slider-'+oss.params.moduleId, {
      // kw_tosave:kw_tosave,
      autoplay:params.autoplay,
      parallax:params.parallax,
      autoplayStopOnLast:params.autoplayStopOnLast,
      autoplayDisableOnInteraction: params.autoplayDisableOnInteraction,
      initialSlide:params.initialSlide,
      direction: params.direction,
      setupAnimation: setupAnimation,
      imageFullTime: params.imageFullTime,
      imageFilter: params.imageFilter,
      imageBackground: params.imageBackground,
      endAnimationEnable:true,
      speed: params.speed,
      spaceBetween: params.spaceBetween,
      slidesPerColumn: params.slidesPerColumn,
      slidesPerColumnFill: params.slidesPerColumnFill,
      slidesPerGroup: params.slidesPerGroup,
      centeredSlides: params.centeredSlides,
      slidesPerView: params.slidesPerView,
      freeMode: params.freeMode,
      freeModeMomentum: params.freeModeMomentum,
      freeModeMomentumRatio: params.freeModeMomentumRatio,
      freeModeMomentumBounce: params.freeModeMomentumBounce,
      freeModeMomentumBounceRatio: params.freeModeMomentumBounceRatio,
      freeModeMinimumVelocity: params.freeModeMinimumVelocity,
      effect: params.effect,
      cube: {
        slideShadows: params.cube.slideShadows,
        shadow: params.cube.shadow,
        shadowOffset: params.cube.shadowOffset,
        shadowScale: params.cube.shadowScale
      },
      coverflow: {
        rotate: params.coverflow.rotate,
        stretch: params.coverflow.stretch,
        depth: params.coverflow.depth,
        modifier: params.coverflow.modifier,
        slideShadows : params.coverflow.coverflowSlideShadows
      },
      flip: {
        slideShadows : params.flip.slideShadows,
        limitRotation: params.flip.limitRotation
      },
      pagination: params.pagination,
      paginationType: params.paginationType,
      paginationClickable: params.paginationClickable,
      nextButton: '',
      prevButton: '',
      scrollbar: params.scrollbar,
      scrollbarHide: params.scrollbarHide,
      scrollbarDraggable: params.scrollbarDraggable,
      keyboardControl: params.keyboardControl,
      mousewheelControl: params.mousewheelControl,
      preloadImages: params.preloadImages,
      lazyLoading: oss.params.lazyLoading,
      lazyLoadingInPrevNext: oss.params.lazyLoadingInPrevNext,
      lazyLoadingInPrevNextAmount: oss.params.lazyLoadingInPrevNextAmount,
      loop: params.loop,
      mousewheelReleaseOnEdges: params.mousewheelReleaseOnEdges,
      slideActiveClass: 'swiper-slide-active',
      onTransitionStart: function (swiper) {
        //if more than 1 slide
        oss.stopSlider();
        //if more than 1 slide
        oss.params.timer.stop();

        if(!oss.stopSlider()){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").fadeTo(0,0);
        }

        //paralax
        if(oss.params.parallax){
          if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active").attr("data-image-id") 
            != jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg").attr("data-image-id")){
              jQuerOSS(oss.params.parallaxImg).each(function(index, img) {
                if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active").attr("data-image-id")
                  == img[2]){
                  jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg")
                    .attr("style","background-image:url("+img[0]+")")
                    .attr("data-image-id",img[2]);
                    jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg").hide();
                    jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg").fadeTo(50, 1);
                    if(jQuerOSS(container + " .direction:checked").val() == 'horizontal'){
                      jQuerOSS("div[id^='os-slider'] .parallax-bg").css({"width":"130%", "height":"100%"});
                    }else{
                      jQuerOSS("div[id^='os-slider'] .parallax-bg").css({"width":"100%", "height":"130%"});
                    }
                }
              });
          }
        }
        //paralax

        if(setupAnimation){
          // setTimeout(function(){
            jQuerOSS(setupAnimation.start).each(function(index, animationClass) {
              jQuerOSS("#os-slider-"+oss.params.moduleId+' .swiper-slide-active').animateCssSlide(animationClass);
            });
          // }, 50);
        }
      },
      onTransitionEnd: function (swiper) {

         //if more than 1 slide
        if(oss.stopSlider()){
            return;
        }
        //if more than 1 slide

        //for arrow show/hide
        oss.showHideArrows();
        //for arrow show/hide

        oss.params.timer.stop();

        imageId = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active").attr("data-image-id");

        oss.params.textStartTimes = [];
        oss.params.textEndTimes = [];
        oss.params.permanentStartTimes = [];
        oss.params.permanentEndTimes = [];
        
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.edit-image) .slide-text:not([data-text-start-time='0'])").each(function(index, el) {

          if(typeof(oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")]) == 'undefined'){
            oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")] = [];
          }
        
          oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")].push(jQuerOSS(el).attr("data-text-id"));
        });


        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.edit-image) .slide-text:not([data-text-end-time='0'])").each(function(index, el) {

          if(typeof(oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")]) == 'undefined'){
            oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")] = [];
          }

          oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")].push(jQuerOSS(el).attr("data-text-id"));
        });

        //permanent effect
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.edit-image) .slide-text:not([data-permanent-start-time='0'])").each(function(index, el) {

          if(typeof(oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")]) == 'undefined'){
            oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")] = [];
          }
        
          oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")].push(jQuerOSS(el).attr("data-text-id"));
        });


        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active:not(.edit-image) .slide-text:not([data-permanent-end-time='0'])").each(function(index, el) {

          if(typeof(oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")]) == 'undefined'){
            oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")] = [];
          }

          oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")].push(jQuerOSS(el).attr("data-text-id"));
        });
        //permanent effect


        oss.params.timer.run(function (timer) {


          if(oss.params.textStartTimes[timer.time/1000]){

            jQuerOSS(oss.params.textStartTimes[timer.time/1000]).each(function(index, textId) {
              if(typeof(oss.params.textAnimation.start) !='undefined' 
                  && oss.params.textAnimation.start[imageId] 
                  && oss.params.textAnimation.start[imageId][textId]){
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextStart(oss.params.textAnimation.start[imageId][textId]);
              }else{
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",1);
              }
            });
            delete oss.params.textStartTimes[timer.time/1000];
          }

          if(oss.params.textEndTimes[timer.time/1000]){

            jQuerOSS(oss.params.textEndTimes[timer.time/1000]).each(function(index, textId) {
              if(typeof(oss.params.textAnimation.end) !='undefined' 
                  && oss.params.textAnimation.end[imageId] 
                  && oss.params.textAnimation.end[imageId][textId]){
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextEnd(oss.params.textAnimation.end[imageId][textId]);
              }else{
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",0);
              }
            });
            delete oss.params.textEndTimes[timer.time/1000];
          } 

          //permanent start
          if(oss.params.permanentStartTimes[timer.time/1000]){

            jQuerOSS(oss.params.permanentStartTimes[timer.time/1000]).each(function(index, textId) {
              if(typeof(oss.params.textAnimation.permanent) !='undefined' 
                  && oss.params.textAnimation.permanent[imageId] 
                  && oss.params.textAnimation.permanent[imageId][textId]){
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextPermanentStart(oss.params.textAnimation.permanent[imageId][textId]);
              }else{
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",1);
              }
            });
            delete oss.params.permanentStartTimes[timer.time/1000];
          }

          //permanent end
          if(oss.params.permanentEndTimes[timer.time/1000]){
            
            jQuerOSS(oss.params.permanentEndTimes[timer.time/1000]).each(function(index, textId) {
              if(typeof(oss.params.textAnimation.permanent) !='undefined' 
                  && oss.params.textAnimation.permanent[imageId] 
                  && oss.params.textAnimation.permanent[imageId][textId]){
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextPermanentStart(oss.params.textAnimation.permanent[imageId][textId]);
              }else{
                jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",0);
              }
            });
            delete oss.params.permanentEndTimes[timer.time/1000];
          }        


        });
      }
    });
    

    //if more than 1 pictures in the same time (arrows always show)
    if(!oss.stopSlider()){
      oss.showHideArrows();
    }  
    
    //text Animation on 1-st slide after reload
    //hide text and show only without timeout//
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-text-start-time='0'],"+
            "#os-slider-"+oss.params.moduleId+" .slide-text:not([data-text-start-time]),"+
            "#os-slider-"+oss.params.moduleId+" .slide-text[data-text-start-time='']").fadeTo(0,1);

    // permanent only without timeout//
    var textPermanentWithoutTime = jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-permanent-start-time='0'],"+
            "#os-slider-"+oss.params.moduleId+" .slide-text[data-permanent-start-time='']");

    function checkAttr(attr){
      if(attr == false || attr == undefined || attr == 'undefined' || attr == 'NAN') return false;
      return true;
    }

    if(checkAttr(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").attr('data-permanent-start-time'))) textPermanentWithoutTime.length = 0;
    if(checkAttr(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").attr('data-text-start-time'))) textPermanentWithoutTime.length = 0;
    if(checkAttr(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").attr('data-permanent-end-time'))) textPermanentWithoutTime.length = 0;
    if(checkAttr(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").attr('data-text-end-time'))) textPermanentWithoutTime.length = 0;

    if(textPermanentWithoutTime.length > 0){
      textPermanentWithoutTime.animateCssTextPermanentStart(oss.params.textAnimation.permanent[textPermanentWithoutTime.attr("data-image-id")][textPermanentWithoutTime.attr("data-text-id")]);
      return;
    }

  

    //make animation for text on first slide


    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active .slide-text:not([data-text-start-time='0'])").each(function(index, el) {

      if(typeof(oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")]) == 'undefined'){
        oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")] = [];
      }

      oss.params.textStartTimes[jQuerOSS(el).attr("data-text-start-time")].push(jQuerOSS(el).attr("data-text-id"));
    });

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active .slide-text:not([data-text-end-time='0'])").each(function(index, el) {

      if(typeof(oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")]) == 'undefined'){
        oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")] = [];
      }

      oss.params.textEndTimes[jQuerOSS(el).attr("data-text-end-time")].push(jQuerOSS(el).attr("data-text-id"));
    });



    //for permanent efect

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active .slide-text:not([data-permanent-start-time='0'])").each(function(index, el) {

      if(typeof(oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")]) == 'undefined'){
        oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")] = [];
      }

      oss.params.permanentStartTimes[jQuerOSS(el).attr("data-permanent-start-time")].push(jQuerOSS(el).attr("data-text-id"));
    });


    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active .slide-text:not([data-permanent-end-time='0'])").each(function(index, el) {

      if(typeof(oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")]) == 'undefined'){
        oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")] = [];
      }

      oss.params.permanentEndTimes[jQuerOSS(el).attr("data-permanent-end-time")].push(jQuerOSS(el).attr("data-text-id"));
    });

    //for permanent efect



    //init animation text
    oss.stopSlider();
    if(params.loop != 0) return;
    
    //image id    
    imageId = jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide.swiper-slide-active").attr("data-image-id");


    oss.params.timer.run(function (timer) {

      //start text Animation //on slider init 1-st slide
      if(oss.params.textStartTimes[timer.time/1000]){

        jQuerOSS(oss.params.textStartTimes[timer.time/1000]).each(function(index, textId) {

          if(typeof(oss.params.textAnimation.start) !='undefined' 
              && oss.params.textAnimation.start[imageId] 
              && oss.params.textAnimation.start[imageId][textId]){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextStart(oss.params.textAnimation.start[imageId][textId]);
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",1);
          }

        });

        delete oss.params.textStartTimes[timer.time/1000];
      }

      //end text Animation //on slider init 1-st slide
      if(oss.params.textEndTimes[timer.time/1000]){


        jQuerOSS(oss.params.textEndTimes[timer.time/1000]).each(function(index, textId) {

          if(typeof(oss.params.textAnimation.end) !='undefined' 
              && oss.params.textAnimation.end[imageId] && oss.params.textAnimation.end[imageId][textId]){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextEnd(oss.params.textAnimation.end[imageId][textId]);
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",0);
          }

        });

        delete oss.params.textEndTimes[timer.time/1000];
      }



      //permanent start 
       if(oss.params.permanentStartTimes[timer.time/1000]){


        jQuerOSS(oss.params.permanentStartTimes[timer.time/1000]).each(function(index, textId) {

          if(typeof(oss.params.textAnimation.permanent) !='undefined' 
              && oss.params.textAnimation.permanent[imageId]
              && oss.params.textAnimation.permanent[imageId][textId]){
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextPermanentStart(oss.params.textAnimation.permanent[imageId][textId]);
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",1);
          }

        });

        delete oss.params.permanentStartTimes[timer.time/1000];
      }
      //permanent start 

      //permanent end 
      if(oss.params.permanentEndTimes[timer.time/1000]){

        jQuerOSS(oss.params.permanentEndTimes[timer.time/1000]).each(function(index, textId) {

          if(typeof(oss.params.textAnimation.permanent) !='undefined' 
              && oss.params.textAnimation.permanent[imageId] && oss.params.textAnimation.permanent[imageId][textId]){
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").animateCssTextPermanentEnd(oss.params.textAnimation.permanent[imageId][textId]);
          }else{
            jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+textId+"']").fadeTo("slow",0);
          }

        });

        delete oss.params.textEndTimes[timer.time/1000];
      }
      //permanent end 

    });



    //end first slide animation

  }

  oss.saveSliderSettings = function (moduleId,copyimage){
    if(oss.params.debugMode){
      console.log("oss.saveSliderSettings",[moduleId]);
    }

    oss.addBackgroundToThumbs();

    var imgText = [];
    if(jQuerOSS(container + " .editing-text:visible").val()){
      oss.saveTextEditor();
    }

    jQuerOSS(container+" .cancel-text-editor,"+container+" .save-text-editor").hide();

    oss.setTextAttrValue();

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide:not(.swiper-slide-duplicate) .slide-text").each(function(index, el){
      

      if(jQuerOSS(el).css("width")){
        jQuerOSS(el).attr("data-width",jQuerOSS(el).css("width").replace('px',''));
      }

      if(jQuerOSS(el).attr("data-custom-class")){
        jQuerOSS(el).addClass(jQuerOSS(el).attr("data-custom-class"));
      }

      jQuerOSS(el).attr("data-style",jQuerOSS(el).attr("style"));

      if(el){
        imgId = jQuerOSS(el).attr("data-image-id");
        if(imgText.indexOf(imgId)){
          imgText[imgId] = [];
        }
        textHtml = jQuerOSS(el).parent().clone();
        jQuerOSS(textHtml).find("img.slide-image").remove();


                var imgTextIndex = []; // for remove text duplicate
        jQuerOSS(textHtml).find("div.slide-text").each(function(index, el) {
          jQuerOSS(el).parent().removeClass('ui-draggable ui-draggable-handle text-active not-saved');

          textId = jQuerOSS(el).attr("data-text-id"); // for remove text duplicate
          if(imgTextIndex.indexOf(textId) === -1 ){
            imgTextIndex[textId] = textId;
            imgText[imgId].push(encodeURI(jQuerOSS(el).clone().wrap('<div>').parent().html()));
          }else {
            // for remove text duplicate
            //continue ;
          }       

        });
      }
    });

  
  
  
    params = oss.resetSlider();

    params.imageFilter = jQuerOSS.toJSON(params.imageFilter);
    params.imageBackground = jQuerOSS.toJSON(params.imageBackground);

    params.imageFullTime = jQuerOSS.toJSON(params.imageFullTime);
    params.textAnimation = jQuerOSS.toJSON(params.textAnimation);
    params.textOrdering = jQuerOSS.toJSON(params.textOrdering);
    params.setupAnimation = jQuerOSS.toJSON(params.setupAnimation);
    params.version = 3.3;

    // params = jQuerOSS.toJSON(params);
    imgText = jQuerOSS.toJSON(imgText);

    if(oss.params.isUser != 1){
       jQuerOSS("#message-block-" + oss.params.moduleId+","+container+" .qqsl-upload-list").html('<span class="successful-slider-message">Changes successfully saved.</span>');
       jQuerOSS("#os-slider-"+oss.params.moduleId).unbind('mouseover');
      jQuerOSS(container).hide('slow');
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").fadeTo(0,0);

      oss.resetSlider(true);
      jQuerOSS("#os-show-settings-button-" + oss.params.moduleId).show();

      oss.stopSlider();
      
      return;
    }

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('text-active');
    jQuerOSS.post("index.php?option=com_ajax&module=os_touchslider&Itemid="+oss.params.ItemId+"&task=save_settings&moduleId="+oss.params.moduleId+"&format=raw",
                 {form_data:params,image_text_data:imgText},


    function (data) {

      if (data.success){

          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").fadeTo(0,0);

          // jQuerOSS("#message-block-" + oss.params.moduleId+","+container+" .qqsl-upload-list").html('<span class="successful-slider-message">Changes successfully saved.</span>');
          if(!copyimage){
          jQuerOSS("#os-slider-"+oss.params.moduleId).unbind('mouseover');
            jQuerOSS(container).hide('slow');
          }
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").removeClass('not-saved');
          jQuerOSS("#save-settings-" + oss.params.moduleId).removeClass('need-save');
          setTimeout(function(){
            jQuerOSS("#message-block-" + oss.params.moduleId+","+container+" .qqsl-upload-list").empty();
          }, 5000);
          oss.cancelImgEditor();
          if(oss.params.parallax == 1){
            oss.enableParalax();
          }
          oss.resetSlider(true);

          jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").fadeTo(0,0);

      }else{
   
          jQuerOSS("#message-block-" + oss.params.moduleId).html('<span class="error-slider-message">Something was wrong.(saveSliderSettings)</span>');
          jQuerOSS(container).hide('slow');
        
      }

      oss.stopSlider();

    } , 'json' );

  }

  //uploader
  if(jQuerOSS('#images-load-area-' + oss.params.moduleId).length){
    
    var uploader = new qqsl.FineUploader({
    
    /* other required config options left out for brevity */
        //multiple: true,
        element: document.getElementById("fine-uploader-"+oss.params.moduleId),
        template: 'qqsl-template-'+oss.params.moduleId,
        validation: {
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'zip'],
            sizeLimit: 10 * 1024 * 1024,
        },
        request: {
            endpoint: oss.params.site_path+'index.php?option=com_ajax&module=os_touchslider&Itemid='+oss.params.ItemId+'&format=json',
            params: {
              id: oss.params.moduleId,
              image_width: oss.params.imageWidth,
              image_height: oss.params.imageHeight,
              crop: oss.params.crop
            }
        },
        callbacks: {

          onComplete: function (id, filename, responseJSON) {

            if (!responseJSON.success) {

              if(responseJSON.id == 'import'){
  
                     jQuerOSS(container + " .import-responce").text("Import failed");
                     jQuerOSS(container + " .import-responce").show(500);
                     jQuerOSS(container + " .import-responce").css('backgroundColor','#f7484e');
                     jQuerOSS(container + " .import-responce").click(function(event) {
                        jQuerOSS(this).hide(500)
                     });

                }

            }else{
              if(oss.params.debugMode){
                console.log("onComplete.upload.image",[responseJSON]);
              }

              jQuerOSS('div.empty-image div').remove();
              //bch
              fileName = responseJSON.file;
              ext = responseJSON.ext;


              if(responseJSON.id == 'import'){
              
                if(responseJSON.success){

                    jQuerOSS(container + " .import-responce").text("Import success");
                    jQuerOSS(container + " .import-responce").show(500);
                    jQuerOSS(container + " .import-responce").css('backgroundColor','#0E9267');
                    jQuerOSS(container + " .import-responce").unbind('click');

                    localStorage.setItem('afterImport', true);

                    var i=3;
                    var interval = setInterval(function(){
                      jQuerOSS(container + " .import-responce").text("Page update after "+i+" seconds");
                      --i;
                      if(i == 0){
                        clearTimeout(interval)
                        location.reload(true)
                      } 
                    },1000)

                  
                    }

                    return;
              }


              if(responseJSON.image_copy_id)
              {
                  image_id = responseJSON.image_copy_id;
                  jQuerOSS(container + " .slider-images[data-sortable-id="+image_id+"]").remove();
              }else{
                  image_id = responseJSON.id;
              }
              //bch

              slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/thumbnail/'+fileName+'_150_100_1'+ext;
              image = '<div class="slider-images" data-sortable-id="'+image_id+'">'+
                        '<div class="slider-image-block">'+
                        '<a class="delete-current-image" type="button" aria-invalid="false">'+
                        '<i class="fa fa-close" aria-hidden="true"></i></a>'+
                        '<a class="edit-current-image" type="button" '+
                        'aria-invalid="false" value="-E-">'+
                        '<i class="fa fa-pencil" aria-hidden="true"></i></a>'+

                        //bch
                        '<a class="copy-current-image oss-pro-avaible" aria-invalid="false">'+
                        '<i class="fa fa-files-o" aria-hidden="true"></i></a>'+

                        '<a class="replace-current-image oss-pro-avaible" aria-invalid="false">'+
                        '<i class="fa fa-picture-o" aria-hidden="true"></i></a>'+
                        //bch
                        
                        '<img class="slider-image" src="'+slideSrc+'" alt="'+fileName+ext+'" data-image-id="'+image_id+'">'+
                        '</div>'+
                      '</div>';

              jQuerOSS(container + " .existing-images").append(image);

              slideSrc = oss.params.site_path+'images/os_touchslider_'+oss.params.moduleId+'/original/'+fileName+ext;


              if(responseJSON.image_copy_id){
                timeInput = jQuerOSS(container + " .image-full-time[data-image-id="+image_id+"]");
                var filterSelectAdd = jQuerOSS(container + " .image-filter[data-image-id="+image_id+"]");
                var backgroundSelectAdd = jQuerOSS(container + " .image-background[data-image-id="+image_id+"]");


              }else{
                jQuerOSS(" .swiper-slide[data-image-id="+image_id+"] img").remove();
                timeInput = '<span data-image-id="'+image_id+'" class="image-full-time" style="display:none;">'+
                           'Image full time, s:<input class="time-input" type="number" name="image_full_time['+image_id+']" min="0" step="0.1" value="">'+
                          '</span>';
                var filterSelectAdd = oss.addFilterSelect(image_id);

                var backgroundSelectAdd = '<span data-image-id="'+image_id+'" class="image-background" style="display:none;">Background:<i title="To use, apply a transparency effect to the image or load a transparent image." class="fa fa-info-circle info_block"></i><input data-image-id="'+image_id+'" class="background-input custom_color_slide-'+oss.params.moduleId+'" type="text" name="image_background'+image_id+'" min="0" step="0.1" value="rgba(255, 255, 255, 1)"></span>';
              }


              jQuerOSS(container + " .image-time-block").append(timeInput);
              jQuerOSS(container + " .image-filter-block").append(filterSelectAdd);
              jQuerOSS(container + " .image-background-block").append(backgroundSelectAdd);


              if(oss.params.lazyLoading){
                newSlide = '<img class="swiper-lazy" data-src="'+slideSrc+'" data-image-id="'+image_id+'">'+
                            '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
              }else{
                newSlide = '<img src="'+slideSrc+'" alt="'+fileName+ext+'" data-image-id="'+image_id+'">';
              }


              if(responseJSON.image_copy_id)
              {
                  jQuerOSS('.swiper-slide[data-image-id="'+image_id+'"').prepend(newSlide);
              }else{
                  oss.params.swiperSlider.appendSlide('<div class="swiper-slide" data-image-id="'+image_id+'">'+newSlide+'</div>');
              }
            

              setTimeout(function(){
                jQuerOSS(container + " .qqsl-upload-list").empty();
              }, 5000);
              oss.makeSortable();
              oss.params.imageOrdering = jQuerOSS(container + " .existing-images").sortable('toArray', {attribute: 'data-sortable-id'});
              oss.sortSliderImages(oss.params.imageOrdering);
              oss.makeClickFunction(oss.params.moduleId);
              oss.resetSlider();

              image_copy_id = false;
            }
          }
        }
    });

  }
  //end

  oss.makeClickFunction = function (modId){
    if(oss.params.debugMode){
      console.log("oss.makeClickFunction",[modId]);
    }


    jQuerOSS(container+" .filter-select").unbind('change');
    jQuerOSS(container+" .filter-select").change(function(){

      var img_id = jQuerOSS(this).attr('data-image-id');
      var filter = jQuerOSS(this).val();

      jQuerOSS(this).find('option[value!='+filter+']').removeAttr('selected');
      jQuerOSS(this).find('option[value='+filter+']').attr('selected','selected');

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").removeClass(function(){
        return jQuerOSS(this).attr('data-image-filter');
      })

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").attr('data-image-filter',filter);
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").addClass(filter);

    })


    jQuerOSS(container+" .background-input").unbind('change');
    jQuerOSS(container+" .background-input").change(function(){

      var img_id = jQuerOSS(this).attr('data-image-id');
      var background = jQuerOSS(this).val();

      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").css('backgroundColor',background);
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img_id+"]").attr('data-image-background',background);

    })

  

    //hover effect
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").unbind('mouseover');

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").mouseover(function(event) {

        if(jQuerOSS(this).css("opacity") == 0) return;
        if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").hasClass('text-active')) return;
        if(jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").parent('div').hasClass('edit-image')) return;


        var afterEffect = true;
        if(jQuerOSS(this).hasClass('text-active')) afterEffect = false;

        if(oss.params.textAnimation.hover 
          && oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")] 
          && oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")]){

           jQuerOSS(this).animateCssTextHover(oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")], afterEffect);
        }

    });


    // jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"']").animateCssTextHover(jQuerOSS(this).attr("data-animation"));

    jQuerOSS(container+" .delete-current-text").unbind('click')
    jQuerOSS(container+" .delete-current-text").click(function(event) {
      oss.deleteCurrentText(this);
    });

    jQuerOSS(container+" .edit-current-text").unbind('click')
    jQuerOSS(container+" .edit-current-text").click(function(event) {
      oss.findText(jQuerOSS(this).parent().attr("data-text-id"),jQuerOSS(this).parent().attr("data-image-id"));
    });

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").unbind('dblclick')
     // jQuerOSS("#os-slider-"+oss.params.moduleId+" .edit-image .slide-text").dblclick(function(event) {
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").dblclick(function(event) {
      oss.findText(jQuerOSS(this).attr("data-text-id"),jQuerOSS(this).attr("data-image-id"));
    });


    //animation select function // add animation on slider-active ready
    jQuerOSS(container + " .start-slide-animation li").click(function(event) {
      if(jQuerOSS(this).hasClass('start-animations-list')){

        //start add animation
        if(jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-start").length
           && oss.params.setupAnimation.start != undefined){
          sel = jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-start");
          oss.params.setupAnimation.start.splice(oss.params.setupAnimation.start.indexOf(jQuerOSS(sel).attr("data-animation")), 1);
        }

        oss.params.setupAnimation.start = [];
        oss.params.setupAnimation.start.push(jQuerOSS(this).attr("data-animation"));
      }else if(jQuerOSS(this).hasClass('os-gallery-autocomplete-input-anim-slide-start')){

        oss.params.setupAnimation.start.splice(oss.params.setupAnimation.start.indexOf(jQuerOSS(this).attr("data-animation")), 1);
      }
      oss.resetSlider(true);
    });
    //end

    //add animation to the end of prev slide
      jQuerOSS(container + " .end-slide-animation li").click(function(event) {
        if(jQuerOSS(this).hasClass('end-animations-list')){
          //end add animation
          if(jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-end").length 
            && oss.params.setupAnimation.end != undefined){
            sel = jQuerOSS(container + " .os-gallery-autocomplete-input-anim-slide-end");
            oss.params.setupAnimation.end.splice(oss.params.setupAnimation.end.indexOf(jQuerOSS(sel).attr("data-animation")), 1);
          }
        
          oss.params.setupAnimation.end = [];
          oss.params.setupAnimation.end.push(jQuerOSS(this).attr("data-animation"));
        }else if(jQuerOSS(this).hasClass('os-gallery-autocomplete-input-anim-slide-end')){
          //end drop animation
          oss.params.setupAnimation.end.splice(oss.params.setupAnimation.end.indexOf(jQuerOSS(this).attr("data-animation")), 1);
        }
        oss.resetSlider(true);
      });
    //end

    //text start animation select function
      jQuerOSS(container + " .start-text-animation li").click(function(event) {

        if(typeof(oss.params.textAnimation.start) == 'undefined'){
          oss.params.textAnimation.start = [];
          oss.params.textAnimation.start[jQuerOSS(this).attr("data-image-id")] = [];
        }

        if(typeof(oss.params.textAnimation.start[jQuerOSS(this).attr("data-image-id")])=='undefined' 
          || !Array.isArray(oss.params.textAnimation.start[jQuerOSS(this).attr("data-image-id")])){
          oss.params.textAnimation.start[jQuerOSS(this).attr("data-image-id")] = [];
        }

        oss.params.textAnimation.start[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")] = jQuerOSS(this).attr("data-animation");

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"']").animateCssTextStart(jQuerOSS(this).attr("data-animation"));

      });
    //end

    //text end animation select function
      jQuerOSS(container + " .end-text-animation li").click(function(event) {

        if(typeof(oss.params.textAnimation.end) == 'undefined'){
          oss.params.textAnimation.end = [];
          oss.params.textAnimation.end[jQuerOSS(this).attr("data-image-id")] = [];
        }

        if(typeof(oss.params.textAnimation.end[jQuerOSS(this).attr("data-image-id")])=='undefined' 
          || !Array.isArray(oss.params.textAnimation.end[jQuerOSS(this).attr("data-image-id")])){
          oss.params.textAnimation.end[jQuerOSS(this).attr("data-image-id")] = [];
        }

        oss.params.textAnimation.end[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")] = jQuerOSS(this).attr("data-animation");

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"']").animateCssTextStart(jQuerOSS(this).attr("data-animation"));

      });
    //end


    //text permanent animation select function
      jQuerOSS(container + " .permanent-text-animation li").click(function(event) {

        if(typeof(oss.params.textAnimation.permanent) == 'undefined'){
          oss.params.textAnimation.permanent = [];
          oss.params.textAnimation.permanent[jQuerOSS(this).attr("data-image-id")] = [];
        }

        if(typeof(oss.params.textAnimation.permanent[jQuerOSS(this).attr("data-image-id")])=='undefined' 
          || !Array.isArray(oss.params.textAnimation.permanent[jQuerOSS(this).attr("data-image-id")])){
          oss.params.textAnimation.permanent[jQuerOSS(this).attr("data-image-id")] = [];
        }

        oss.params.textAnimation.permanent[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")] = jQuerOSS(this).attr("data-animation");

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"']").animateCssTextPermanentStart(jQuerOSS(this).attr("data-animation"));
        
      });

      //text hover animation select function
      jQuerOSS(container + " .hover-text-animation li").click(function(event) {

        if(typeof(oss.params.textAnimation.hover) == 'undefined'){
          oss.params.textAnimation.hover = [];
          oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")] = [];
        }

        if(typeof(oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")])=='undefined' 
          || !Array.isArray(oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")])){
          oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")] = [];
        }

        oss.params.textAnimation.hover[jQuerOSS(this).attr("data-image-id")][jQuerOSS(this).attr("data-text-id")] = jQuerOSS(this).attr("data-animation");

        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-active .slide-text[data-text-id='"+jQuerOSS(this).attr("data-text-id")+"']").animateCssTextHover(jQuerOSS(this).attr("data-animation"),false);
        
      });
    //end

    jQuerOSS(container+" .copy-current-text").unbind('click')
    jQuerOSS(container+" .copy-current-text").click(function(event) {
      event.stopPropagation();
      jQuerOSS(this).fadeOut(300,function()
        {
          jQuerOSS(this).fadeIn(300);
        });

      oss.copyText(jQuerOSS(this).parent().attr("data-text-id"),
                   jQuerOSS(this).parent().find(".text-line").text(),
                   jQuerOSS(this).parent().attr("data-image-id"));
    });

    jQuerOSS(container + " .edit-current-image").unbind('click')
    jQuerOSS(container + " .edit-current-image").click(function(event) {

      // event.isPropagationStopped()
      event.stopPropagation();
      oss.imageEdit(jQuerOSS(this).parent().find('img'));
    });

    jQuerOSS(container + " .delete-current-image").unbind('click')
    jQuerOSS(container + " .delete-current-image").click(function(event) {
      event.stopPropagation();
      oss.deleteCurrentImage(jQuerOSS(this).parent().find(".slider-image").attr("data-image-id"), modId);
    });
  }

  //click fucntion for all settings input
  oss.initContainerOnClick = function (contId, modId){
    if(oss.params.debugMode){
      console.log("oss.initContainerOnClick",[contId, modId]);
    }
    jQuerOSS("#save-settings-"+modId).unbind('click')
    jQuerOSS("#save-settings-"+modId).click(function(event) {
      oss.saveSliderSettings(modId);
    });

    jQuerOSS(container + " .tab-label").click(function(event) {
      oss.makeCopyright(jQuerOSS(this).attr("data-tab-id"));
    });

    jQuerOSS(container + " .selected-layout").change(function(event) {
      oss.makeCopyright(oss.params.activeTab);
    });

    jQuerOSS(container + " .back-image-edit").click(function(event) {
      oss.addBackgroundToThumbs();
      oss.cancelImgEditor();
    });

    jQuerOSS(container + " .add-image-text").click(function(event) {
      oss.addNewText(this);
    });

    //bch
    jQuerOSS(container + " .paste-image-text").click(function(event) {
      
       jQuerOSS(this).fadeOut(300,function()
        {
          jQuerOSS(this).fadeIn(300);
        });
      oss.pasteText();
    });
    //bch

    jQuerOSS(container + " .cancel-text-editor").click(function(event) {
      oss.cancelTextEditor(this);
    });

    jQuerOSS(container + " .save-text-editor").click(function(event) {
      oss.saveTextEditor(this);
      oss.setTextAttrValue();
    });

    //simple reset slider
    jQuerOSS(container + " .easy-reset").click(function(event) {
      oss.params.resetSpeed = false;
      oss.resetSlider();
      oss.resizeSlider()
    });

    //simple reset slider
    jQuerOSS(container + " .animate-reset").click(function(event) {
      oss.resetSlider();
      oss.resizeSlider()
    });

    //simple input reset
    jQuerOSS(container + " .easy-input-reset").on('input', function (e) {
      oss.params.resetSpeed = false;
      oss.resetSlider();
      oss.resizeSlider()
    });

    //hard reset slider
    jQuerOSS(container + " .hard-reset").click(function(event) {

      if(jQuerOSS(container + " .direction:checked").val() == 'vertical'){

        oss.params.slidesPerColumn = 1;
        jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).closest('.option-block').hide();

      }else{

        jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).closest('.option-block').show();
        oss.params.slidesPerColumn = jQuerOSS("#slidesPerColumn-" + oss.params.moduleId).val();

      }

      oss.params.resetSpeed = false;
      oss.resetSlider(true);
      oss.resizeSlider()

    });

    //hard input reset
    jQuerOSS(container + " .hard-input-reset").on('input', function (e) {
      oss.params.resetSpeed = false;

      if(jQuerOSS("#slidesPerView-" + oss.params.moduleId).val() == '')
        jQuerOSS("#slidesPerView-" + oss.params.moduleId).val(1)
      
      if(jQuerOSS("#spaceBetween-" + oss.params.moduleId).val() == '')
        jQuerOSS("#spaceBetween-" + oss.params.moduleId).val(0)
      
      oss.resetSlider(true)
      oss.resizeSlider()
    });

    //hard change reset slider
    jQuerOSS(container + " .hard-change-reset").change(function(event) {

      if(jQuerOSS(this).hasClass('slider-effect')){
        oss.params.resetSpeed = true;
      }else{
        oss.params.resetSpeed = false;
      }

      oss.resetSlider(true);
      oss.resizeSlider()

    });

    jQuerOSS(container + " .reset-settings-button").click(function(event) {
      if(confirm('All settings will be set to default.You confirm reset?')){
        oss.resetSliderSettings();
      };
    });

    jQuerOSS(container + " .editing-text").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').html(jQuerOSS(this).val());
      jQuerOSS('#os-slider-'+modId+' .text-active').attr("data-text-body", window.JSON.stringify(jQuerOSS(this).val()));
      //oss.textDraggable();
    });

    jQuerOSS(container + " .text-font-size").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('font-size',oss.toPx(jQuerOSS(this).val())+'px')
                                                // .css('line-height','100%')
                                                // .css('height','auto')
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-padding-top").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('padding-top',oss.toPx(jQuerOSS(this).val())+'px')
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-padding-right").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('padding-right',oss.toPx(jQuerOSS(this).val())+'px')
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-padding-bottom").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('padding-bottom',oss.toPx(jQuerOSS(this).val())+'px')
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-padding-left").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('padding-left',oss.toPx(jQuerOSS(this).val())+'px')
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-font-weight-select").change(function(event) {
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css("font-weight",this.value);
    });

    jQuerOSS(container + " .text-align-select").change(function(event) {
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .text-active").css("text-align", this.value);
    });

    //text shadow apply style start
    jQuerOSS(container + " .text-h-shadow").on('input', function (e) {
      var text_h_shadow = oss.toPx(jQuerOSS(this).val())+'px';
      var text_v_shadow = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-v-shadow').val())+'px';
      var text_blur_radius = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-blur-radius').val())+'px';
      var text_shadow_colorpicker = jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-shadow-colorpicker').val();

      jQuerOSS('#os-slider-'+modId+' .text-active').css('text-shadow',text_shadow_colorpicker+" "+
                                                                      text_h_shadow+" "+
                                                                      text_v_shadow+" "+
                                                                      text_blur_radius)
                                                                      .addClass('not-saved');
    });

    jQuerOSS(container + " .text-v-shadow").on('input', function (e) {
      var text_h_shadow = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-h-shadow').val())+'px';
      var text_v_shadow = oss.toPx(jQuerOSS(this).val())+'px';
      var text_blur_radius = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-blur-radius').val())+'px';
      var text_shadow_colorpicker = jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-shadow-colorpicker').val();

      jQuerOSS('#os-slider-'+modId+' .text-active').css('text-shadow',text_shadow_colorpicker+" "+
                                                                      text_h_shadow+" "+
                                                                      text_v_shadow+" "+
                                                                      text_blur_radius)
                                                                      .addClass('not-saved');
    });


    jQuerOSS(container + " .text-blur-radius").on('input', function (e) {
      var text_h_shadow = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-h-shadow').val())+'px';
      var text_v_shadow = oss.toPx(jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-v-shadow').val())+'px';
      var text_blur_radius = oss.toPx(jQuerOSS(this).val())+'px';
      var text_shadow_colorpicker = jQuerOSS(this).closest('#tab-text-content2-'+modId).find('.text-shadow-colorpicker').val();
      jQuerOSS('#os-slider-'+modId+' .text-active').css('text-shadow',text_shadow_colorpicker+" "+
                                                                      text_h_shadow+" "+
                                                                      text_v_shadow+" "+
                                                                      text_blur_radius)
                                                                      .addClass('not-saved');
    });

    //text shadow apply style end

    jQuerOSS(container + " .text-block-width").on('input', function (e) {
      curW = (jQuerOSS(this).val()>0)?jQuerOSS(this).val()+'%':'auto';
      jQuerOSS('#os-slider-'+modId+' .text-active').css('width',curW)
                                                .attr('data-text-width',jQuerOSS(this).val())
                                                .addClass('not-saved');
    });

    jQuerOSS(container + " .text-borer-width").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('border-width',oss.toPx(jQuerOSS(this).val())+'px')
      .addClass('not-saved');
    });

    jQuerOSS(container + " .text-custom-class").on('input', function (e) {

      var currentCustClass = jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-custom-class');
      if(currentCustClass) jQuerOSS('#os-slider-'+modId+' .text-active').removeClass(currentCustClass);

      jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-custom-class',jQuerOSS(this).val())
      .addClass('not-saved');

      jQuerOSS('#os-slider-'+modId+' .text-active').addClass(jQuerOSS(this).val())

    });

    jQuerOSS(container + " .text-borer-radius").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').css('border-radius',oss.toPx(jQuerOSS(this).val())+'px')
      .addClass('not-saved');
    });


    jQuerOSS(container + " .text-time-start-input").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-text-start-time',jQuerOSS(this).val())
      .addClass('not-saved');
    });

    jQuerOSS(container + " .text-time-end-input").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-text-end-time',jQuerOSS(this).val())
      .addClass('not-saved');
    });

    jQuerOSS(container + " .permanent-time-start-input").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-permanent-start-time',jQuerOSS(this).val())
      .addClass('not-saved');
    });

    jQuerOSS(container + " .permanent-time-end-input").on('input', function (e) {
      jQuerOSS('#os-slider-'+modId+' .text-active').attr('data-permanent-end-time',jQuerOSS(this).val())
      .addClass('not-saved');
    });


    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
      jQuerOSS(this).attr("data-image-id",jQuerOSS(this).parents(".swiper-slide").attr("data-image-id"));
    });

     //init for free
    jQuerOSS('.oss-pro-avaible').prop('disabled', 'disabled');
    jQuerOSS('.oss-pro-avaible *').prop('disabled', 'disabled');
    if(!jQuerOSS(".ordasoft-copyright").length){
      jQuerOSS("#os-slider-"+oss.params.moduleId).parent().append('<div class="ordasoft-copyright"><a href="http://ordasoft.com" style="font-size: 10px;">Nice try! But you still fail!</br>Powered by OrdaSoft!</a></div>');
    }
    //init for free
  }
  //end

  oss.preventDraggable = function (){
    if(oss.params.debugMode){
      console.log("oss.preventDraggable",['without arguments']);
    }
    jQuerOSS(container).draggable();
      jQuerOSS(container+" .slider-image,"+
            container + " .animation-manager-block,"+container+" input,"+container+" label,"+
            container +" .minicolors-panel").hover(function() {
        jQuerOSS(container).draggable('disable');
      }, function() {
        jQuerOSS(container).draggable('enable');
      });
  }

  oss.enableParalax = function (){
    if(oss.params.debugMode){
      console.log("oss.enableParalax",['without arguments']);
    }
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide .slide-image").each(function(index, el) {
      //add paralax bg
      oss.params.parallaxImg.push([jQuerOSS(el).attr("src"),jQuerOSS(el).attr("alt"),jQuerOSS(el).attr("data-image-id")]);
      if(!jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg").length){
        parallaxBg = '<div '+
                        'class="parallax-bg" '+
                        'style="background-image:url('+jQuerOSS(el).attr("src")+')" '+
                        'data-swiper-parallax="-23%"'+
                        'data-image-id="'+jQuerOSS(el).attr("data-image-id")+'">'+
                      '</div>';
        jQuerOSS("#os-slider-"+oss.params.moduleId).prepend(parallaxBg);
      }
      //end
      jQuerOSS(el).unwrap("#os-slider-"+oss.params.moduleId+" .swiper-slide");
      jQuerOSS(el).remove();
    });
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
      jQuerOSS(el).wrap('<div class="swiper-slide" data-image-id="'+jQuerOSS(el).attr("data-image-id")+'"></div>');
    });
    oss.sortSliderImages(oss.params.imageOrdering);
  }

  oss.compareReversed = function (a, b) {
    return b - a;
  }

  oss.disableParalax = function (){
    if(oss.params.debugMode){
      console.log("oss.disableParalax",['without arguments']);
    }
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").each(function(index, el) {
      jQuerOSS(el).unwrap("#os-slider-"+oss.params.moduleId+" .swiper-slide");
    });
    oss.params.parallaxImg.sort(oss.compareReversed);
    jQuerOSS(oss.params.parallaxImg).each(function(index, img) {
      var imgHtml = '<div class="swiper-slide" data-image-id="'+img[2]+'">'+
                      '<img  class="slide-image" src="'+img[0]+'" alt="'+img[1]+'" data-image-id="'+img[2]+'">'+
                    '</div>';
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-wrapper").prepend(imgHtml);
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text[data-image-id="+img[2]+"]").each(function(index, text){
        jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide[data-image-id="+img[2]+"]").prepend(text);
      });
    });
    jQuerOSS("#os-slider-"+oss.params.moduleId+" .parallax-bg").remove();
    oss.params.parallaxImg = [];
    oss.sortSliderImages(oss.params.imageOrdering);
  }

  oss.timer = function (timeout) {
      var self = this;
      this.interval = timeout ? timeout : 1000;   // Default
      this.time = 0;
      this.run = function (runnable) {
          this.timer = setInterval(function () {
            self.time += self.interval;
            runnable(self); 
         }, this.interval);
      };

      this.stop = function () {
          clearTimeout(this.timer);
          this.time = 0;
      };
  }

  oss.makeNextPrevClickable = function(){

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-next").on('click', function(e) {

        oss.params.timer.stop();
        oss.params.swiperSlider.onClickNext(e);

    });

    jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-button-prev").on('click', function(e) {

      oss.params.timer.stop();
      oss.params.swiperSlider.onClickPrev(e);

    });

  }

  //initialize function
  oss.init = function (){
    if(oss.params.debugMode){
      console.log("oss.init",['without arguments']);
    }
    
    // add animate//included before
    jQuerOSS.fn.extend({
      animateCssSlide: function (animationName, hide) {
          var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
          jQuerOSS("#os-slider-"+oss.params.moduleId+" .swiper-slide-prev").fadeTo(0, 1);
          jQuerOSS(this).addClass('animated ' + animationName).one(animationEnd, function() {
            jQuerOSS(this).removeClass('animated ' + animationName);
          });
      }
    });

    oss.params.timer = new oss.timer(100);

    oss.autocompleteAnimateSelect();
    oss.autocompleteSlideAnimateSelect();

    oss.autocompleteFontSelect();
    oss.initContainerOnClick(container, oss.params.moduleId);
    oss.makeClickFunction(oss.params.moduleId);

    //draggable init for settings block
    oss.preventDraggable();
    //end

    //init show/hide button on slider
    jQuerOSS("#show-settings-" + oss.params.moduleId).click(function(event) {

      if(oss.params.parallax){
        if(jQuerOSS("#dragable-settings-block"+oss.params.moduleId+":visible").length){
          oss.enableParalax();
          oss.resetSlider(true);
        }else{
          oss.disableParalax();
          oss.resetSlider(true);
        }
      }
        jQuerOSS("#os-slider-"+oss.params.moduleId).mouseover(function(event) {
          jQuerOSS("#os-show-settings-button-" + oss.params.moduleId).hide();
        });

      jQuerOSS(container).show('slow');
    });
    //end

  

    if(localStorage.getItem('afterImport') 
      || oss.params.version == 0){
      oss.setTextAttrValue();     
    }

    //check isNew version after new resize
    if(oss.params.version == 0) oss.setTextAttrValue();

    oss.changeStyle();
    oss.makeTextColorpicker();
    oss.loadNededFonts();
    oss.resizeSlider();
    oss.params.swiperSlider.update(true);
    oss.makeSortable();
    oss.currentTextOrderId();
    oss.makeImgTextSortable();
    oss.makeNextPrevClickable();

    String.prototype.replaceAll = function (replaceThis, withThis) {
       var re = new RegExp(replaceThis,"g"); 
       return this.replace(re, withThis);
    };

    jQuerOSS(window).resize(function(event) {
      oss.resetSlider()
      oss.resizeSlider();
    });

    window.addEventListener("orientationchange", function() {
      oss.resetSlider()
      oss.resizeSlider();
    }, false);

    jQuerOSS(window).load(function() {
      if(oss.params.debugMode){
        console.log("oss.init",['without arguments']);
      }
      //string below fix some wrong classes in text ufter save animation
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").attr("class","").addClass('slide-text');
      /*maybe bug with reset slider on init.*/
      oss.resetSlider(true);
      oss.resizeSlider();
      /*end*/
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slide-text").fadeTo(0,0);
      jQuerOSS("#os-slider-"+oss.params.moduleId+" .slider-load-background").remove();

      oss.stopSlider();

      oss.setTextAttrValue();

      if(localStorage.getItem('afterImport')){
      // oss.setTextAttrValue();
        oss.saveSliderSettings();
        localStorage.removeItem('afterImport');
        location.reload(true);
      }

      oss.makeCustomSlideColorpicker();

      oss.addBackgroundToThumbs();

    });

    oss.makeCopyright(oss.params.activeTab);
  }


  oss.init();

  ///parallax
  if(oss.params.parallax){
    oss.enableParalax();
    oss.resetSlider(true);
  }
    //end
    // Return settings instance
    return oss;
  }

  window.osSliderSettings = osSliderSettings;

})();