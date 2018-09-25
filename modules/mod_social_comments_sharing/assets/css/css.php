<?php ///////////////////////////////START CSS FOR COMPUTER DEVICE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
  <style type="text/css">


    #mainButtons<?php echo $modId; ?> .pinterest_follow a{
      cursor: pointer;
      display: block;
      box-sizing: border-box;
      box-shadow: inset 0 0 1px #888;
      border-radius: 3px;
      height: 20px;
      -webkit-font-smoothing: antialiased;
      background: #efefef;
      background-size: 75%;
      position: relative;
      font: 12px "Helvetica Neue", Helvetica, arial, sans-serif;
      color: #555;
      text-align: center;
      vertical-align: baseline;  
      line-height: 20px;
      padding: 1px 4px;
      text-decoration: none;    
    }


  <?php if($params->get('module_style') =='horizontal'): ?> 

  .mainCom{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;

    -webkit-flex-wrap: wrap;
    -moz-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    -o-flex-wrap: wrap;
    flex-wrap: wrap;
  }

  <?php endif; ?>

  #mainButtons<?php echo $modId; ?> {
    z-index: 99999;
  }
  #mainButtons<?php echo $modId; ?> a div {
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    transition: all 0.3s ease-out;
  }

  #mainButtons<?php echo $modId; ?> .at-icon-wrapper {
    width: <?php echo $icon_size; ?> !important;
    height: <?php echo $icon_size; ?> !important;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    transition: all 0.3s ease-out;
  }

  /*#mainButtons<?php echo $modId; ?> .addthis_button_more {
    width: <?php echo $icon_size; ?>;
    height: <?php echo $icon_size; ?>;
    margin: 0 !important;
  }*/
  #mainButtons<?php echo $modId; ?> .at-icon-wrapper svg {
    width: <?php echo $icon_size; ?> !important;
    height: <?php echo $icon_size; ?> !important;
    -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
  }

  <?php
///////////////////////end for all\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

/////////////////////Start for custom blocks\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  if($params->get('module_style') =='horizontal'){?>
    #mainButtons<?php echo $modId; ?> .fb_like_button_container span,#mainButtons<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px !important;*/
    }
    .mainCom{
      padding-left: 8px !important;
    }
    .mainCom .fb_like_button_container,.mainCom .fb_share_button_container,.mainCom .fb_send_button_container,
    .mainCom .cmp_in_container,.mainCom .cmp_twitter_container,.mainCom .cmp_vk_container,.cmp_google_container,
    .mainCom .cmp_ok_container{
      display: inline-block;
      line-height: 13px;
    }
    .mainCom .cmp_ok_container{
      margin-right: 4px;
    }
    .mainCom .fb_like_button_container{
       <?php
      if($params->get('fb_like_verb_to_display') == 'recommend'){
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          margin: 0px 4px 0px 0;
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          margin: 0px 4px 0px 0;
          <?php }else{ ?>
            margin: 0px 4px 0px 0;
        <?php }
      }else{
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          margin: 0px 4px 0px 0;
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          margin: 0px 4px 0px 0;
          <?php }else{ ?>
            margin: 0px 4px 0px 0;
        <?php }
        } ?>
    }
    #mainButtons<?php echo $modId; ?> .fb_send_button_container{
      margin: 0px 4px 0px 0;
    }
    .mainCom .cmp_twitter_container{
      margin-right: 4px;
      position: relative;
    }
    .mainCom .cmp_google_container,.mainCom .cmp_vk_container,.mainCom .cmp_ok_container{
      position: relative;
    }
    .cmp_vk_container a span img {
      vertical-align: bottom;
    }
    .mainCom .cmp_vk_container{
      margin-right: 3px;
      position: relative;
    }
    .mainCom .pluginButtonContainer{
      width: 62px;
    }
    #mainButtons<?php echo $modId; ?> .fb_send_button_container span{
      margin-right: 4px;
    }
    .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_in_container {
      margin-right: 4px;
    }
    .cmp_in_container span {
      line-height: 0!important;
    }
    .cmp_google_container{
      <?php 
          if($params->get('annotation_google') == 'bubble'){?>
          margin: 0px -29px 0px 0;
      <?php }elseif($params->get('annotation_google') == 'inline'){ ?>
          margin: 0px -255px 0px 0;
      <?php }else{ ?>
          margin: 0px 4px 0px 0;
      <?php } ?>
    }
  <?php
  }elseif($params->get('module_style') =='vertical'){ ?>

    #mainButtons<?php echo $modId; ?> .fb_like_button_container span,#mainButtons<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px!important;*/
    }
      .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_vk_container, .fb_share_button_container, .fb_like_button_container{
      margin-bottom: 5px;
    }

/*    .buttonPin a{
      display: none;
    }
*/
    div.fb_share_button_container{
      margin-bottom: 5px;
    }

    div.cmp_twitter_container,
    div.cmp_in_container,
    .mainCom [class^=ig-b-],
    [class^=ig-b-],
     .buttonPin,
     .addthis_button_more,
     .tumblr-share-container{
      /*margin-top: -4px;*/
      height: 20px;
      line-height: 20px;
      margin-bottom: 5px;
    }

     [class^=ig-b-] a{
      height: 20px;
      display: inline-block;
    }

    [class^=ig-b-] img{
      max-height: 100%;
    }

    .fb_iframe_widget span{
      vertical-align: top !important;
    }

    .fb_share_button_container,
    .fb_send_button_container,
    .cmp_google_container{
      line-height: 20px;
    }

    .cmp_google_container{
      height: 20px;
      margin-bottom: 5px;
    }


  <?php
  }elseif($params->get('module_style')!='horizontal' || $params->get('module_style')=='vertical'){
////////////////////////////////////STYLES FOR ALL TOP/LEFT/RIGHT/BOTTOM icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
    #mainButtons<?php echo $modId; ?> .fb_like_button_container span,#mainButtons<?php echo $modId; ?> .fb_send_button_container span{
        /*width: 450px!important;*/
    }
    #mainButtons<?php echo $modId; ?> a div{
        position: relative;
    }
    <?php if($params->get("icon_type", 1) == 2){?>
    #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
      border-radius: 100%;
    }
    <?php } ?>
    .mainCom .fb_like_button_container,
    .mainCom .fb_send_button_container, .mainCom .cmp_ok_container{
        margin-top: 4px;
    }

    span[id$=_count_box]{
        position: absolute;
        bottom: 0;
        left:0;
        right: 0;
        text-align: center;
        color: #000;
        line-height: 18px;
    }
    <?php
    if($params->get('module_style') =='top'){
////////////////////////////////////STYLES FOR TOP icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>

      <?php if($params->get("icon_type", 1) == 3){ ?>
      #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
        border-radius: 0 0 100% 100%;
      }
      <?php  }?>
      #mainButtons<?php echo $modId; ?> a{
        display: inline-block;
         -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons<?php echo $modId; ?>{
        position: fixed;
        top: 0;
      }
      <?php if($params->get("hover_effect",1)){?>
      #mainButtons<?php echo $modId; ?> a div:hover{
        height:  <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php if($params->get("icon_type",1) == 2){?>
          width: <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php }?>
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      <?php }?>

      #mainButtons<?php echo $modId; ?> a{
        vertical-align: top;
      }
      /*---------------top---------------------------*/
      .trigger-top-<?php echo $modId; ?> {
        position: fixed;
        top: 0;
        z-index: 9999;
        background: url(<?php echo $mosConfig_live_site.
              'modules/mod_social_comments_sharing/images/arrows-sprites.png';?>) no-repeat;
        background-position: -24px 5px;
        width: 27px;
        height: 27px;
        opacity: 0.5;
        border: 1px solid transparent;
        padding: 0px;
         box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -o-box-sizing: border-box;
      }
      .trigger-top-<?php echo $modId; ?>:hover {
        opacity: 1;
      }
      .trigger-top-<?php echo $modId; ?>.active {
          background-position: 0px 0px;
          border: 1px solid #ccc;
          padding: 0px;
          margin-top: 5px;
      }
      .top-position-<?php echo $modId; ?>.deactive {
        -webkit-transform: translate3d(0, -100%, 0);
        transform: translate3d(0, -100%, 0 );
        -webkit-transition: all 0.5s ease-out;
        -moz-transition: all 0.5s ease-out;
        transition: all 0.5s ease-out;
      }
      .top-position-<?php echo $modId; ?> {
        -webkit-transform: none;
        transform: none;
        -webkit-transition: all 1s ease-in;
        -moz-transition: all 1s ease-in;
        transition: all 1s ease-in;
      }
    <?php
//////////////////////////////////// END STYLES FOR TOP icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='left'){
////////////////////////////////////STYLES FOR LEFT icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      <?php if($params->get("icon_type", 1) == 3){ ?>
      #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
        border-radius: 0 100% 100% 0;
      }
      <?php  }?>
      #mainButtons<?php echo $modId; ?>{
        position: fixed;
        left: 0;
      }
      <?php if($params->get("hover_effect",1)){?>
      #mainButtons<?php echo $modId; ?> a div:hover{
        width:  <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php if($params->get("icon_type",1) == 2){?>
          height: <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php }?>
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      <?php }?>
      /*---------------------left------------------------------*/
      .trigger-left-<?php echo $modId; ?> {
        position: fixed;
        left: 5px;
        z-index: 9999;
        background: url(<?php echo $mosConfig_live_site.
              'modules/mod_social_comments_sharing/images/arrows-sprites.png';?>) no-repeat;
        background-position:-24px 0px;
        width: 27px;
        height: 27px;
        opacity: 0.5;
        border: 1px solid transparent;
        padding: 0px;
         box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -o-box-sizing: border-box;
      }
      .trigger-left-<?php echo $modId; ?>:hover {
        opacity: 1;
      }
      .trigger-left-<?php echo $modId; ?>.active {
          background-position: 0px 0px;
          border: 1px solid #ccc;
          padding: 0px;
      }
      .left-position-<?php echo $modId; ?>.deactive {
        -webkit-transform: translate3d(-100%, 0, 0);
        transform: translate3d(-100%, 0, 0);
        -webkit-transition: all 0.5s ease-out;
        -moz-transition: all 0.5s ease-out;
        transition: all 0.5s ease-out;
      }
      .left-position-<?php echo $modId; ?> {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
        -webkit-transition: all 1s ease-in;
        -moz-transition: all 1s ease-in;
        transition: all 1s ease-in;
      }
      <?php
////////////////////////////////////END STYLES FOR LEFT icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='right'){
////////////////////////////////////STYLES FOR RIGHT icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      <?php if($params->get("icon_type", 1) == 3){ ?>
      #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
        border-radius: 100% 0  0 100%;
      }
      <?php  }?>
      #mainButtons<?php echo $modId; ?>{
        position: fixed;
        right: 0;
      }
      <?php if($params->get("hover_effect",1)){?>
      #mainButtons<?php echo $modId; ?> a div:hover{
        width:  <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php if($params->get("icon_type",1) == 2){?>
          height: <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php }?>
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      <?php }?>
      #mainButtons<?php echo $modId; ?> a div{
        float: right;
        clear: both;
      }
      /*---------------------right-----------------------------*/
      .trigger-right-<?php echo $modId; ?> {
        position: fixed;
        right: 0;
        z-index: 9999;
        background: url(<?php echo $mosConfig_live_site.
              'modules/mod_social_comments_sharing/images/arrows-sprites.png';?>) no-repeat;
        background-position: -24px 0px;
        width: 27px;
        height: 27px;
        opacity: 0.5;
        border: 1px solid transparent;
        padding: 0px;
        margin: 0 5px 0 0;
         box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -o-box-sizing: border-box;
      }
      .trigger-right-<?php echo $modId; ?>:hover {
        opacity: 1;
      }
      .trigger-right-<?php echo $modId; ?>.active {
        background-position: 0px 0px;
        border: 1px solid #ccc;
        padding: 0px;
      }
      .right-position-<?php echo $modId; ?>.deactive {
        -webkit-transform: translate3d(100%, 0, 0);
        transform: translate3d(100%, 0, 0 );
        -webkit-transition: all 0.5s ease-out;
        -moz-transition: all 0.5s ease-out;
        transition: all 0.5s ease-out;
      }
      .right-position-<?php echo $modId; ?> {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0 );
        -webkit-transition: all 1s ease-in;
        -moz-transition: all 1s ease-in;
        transition: all 1s ease-in;
      }

    <?php
////////////////////////////////////END STYLES FOR RIGHT icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='bottom'){
////////////////////////////////////STYLES FOR BOTTOM icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      <?php if($params->get("icon_type", 1) == 3){ ?>
      #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
        border-radius: 100% 100% 0 0;
      }
      <?php  }?>
      #mainButtons<?php echo $modId; ?> a{
        display: inline-block;
      }
      <?php if($params->get("hover_effect",1)){?>
      #mainButtons<?php echo $modId; ?> a div:hover{
        height:  <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php if($params->get("icon_type",1) == 2){?>
          width: <?php echo $icon_size_number + $icon_size_number *0.2 .'px !important'; ?>;
        <?php }?>
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      <?php }?>
      #mainButtons<?php echo $modId; ?>{
        position: fixed;
        bottom: 0;
        line-height: 0px;
      }

      /*------------------bottom------------------------*/
      .trigger-bottom-<?php echo $modId; ?> {
        position: fixed;
        bottom: 0;
        z-index: 9999;
        background: url(<?php echo $mosConfig_live_site.
              'modules/mod_social_comments_sharing/images/arrows-sprites.png';?>) no-repeat;
        background-position: -24px -5px;
        width: 27px;
        height: 27px;
        opacity: 0.5;
        border: 1px solid transparent;
        padding: 0px;
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -o-box-sizing: border-box;
      }
      .trigger-bottom-<?php echo $modId; ?>:hover {
        opacity: 1;
      }
      .trigger-bottom-<?php echo $modId; ?>.active {
        background-position: 0px 0px;
        border: 1px solid #ccc;
        padding: 0px;
        margin-bottom: 5px;
      }
      .bottom-position-<?php echo $modId; ?>.deactive {
        -webkit-transform: translate3d(0, 100%, 0);
        transform: translate3d(0, 100%, 0 );
        -webkit-transition: all 0.5s ease-out;
        -moz-transition: all 0.5s ease-out;
        transition: all 0.5s ease-out;
      }
      .bottom-position-<?php echo $modId; ?> {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0 );
        -webkit-transition: all 1s ease-in;
        -moz-transition: all 1s ease-in;
        transition: all 1s ease-in;
      }

      <?php
    }elseif($params->get('module_style') =='horizontal_icon'){?>
      <?php if($params->get("icon_type", 1) == 3){ ?>
      #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
        border-radius: 100% 100% 0 0;
      }
      <?php  }?>
      #mainButtons<?php echo $modId; ?> a{
        display: inline-block;
      }
      <?php
    }
    elseif($params->get('module_style') =='vertical_icon'){
      if($params->get("icon_type", 1) == 3){ ?>
        #mainButtons<?php echo $modId; ?> a div,#mainButtons<?php echo $modId; ?> .at-icon-wrapper{
          border-radius: 100% 0 0 100%;
        }
        <?php
      }
    }?>
    #mainButtons<?php echo $modId; ?> #fb_share_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face.png';?>);
      background-position: center;
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #facebook-share-dialog {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }
    #mainButtons<?php echo $modId; ?> #liked_in_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/liked_in.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #twitter_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/twitter_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #google_plus_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/google_plus_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #vk_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/vk_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #addthis_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/addthis-icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #instagram_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/instagram_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #pinterest_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }

    #mainButtons<?php echo $modId; ?> #pinterest_follow_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_follow_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #tumblr_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/tumblr_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #odnoklasniki_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/odnoklasniki_icon.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }
    #mainButtons<?php echo $modId; ?> #fb_send_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face_send.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%; /
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size; ?>;
      height: <?php echo $icon_size; ?>;
    }

    <?php
  }
  ////////////////////////////////////END STYLES FOR ALL TOP/LEFT/RIGHT/BOTTOM icons\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  ?>
</style>
<?php ///////////////////////////////END CSS FOR COMPUTER DEVICE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


///////////////////////////////START CSS FOR TABLET DEVICE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$icon_size_number_tablet=$icon_size_number*0.75;
$icon_size_tablet = $icon_size_number_tablet.'px';
////////////////////////////////////STYLES FOR ALL TABLET\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
?>
  <style type="text/css">
  <?php if($params->get("icon_type", 1) == 2){?>
    #mainButtons_tablet<?php echo $modId; ?> a div{
      border-radius: 100%;
    }
  <?php } ?>
  <?php if($params->get("icon_type", 1) == 3){ ?>
    #mainButtons_tablet<?php echo $modId; ?> a div{
      border-radius: 0 0 100% 100%;
    }
    #mainButtons_tablet<?php echo $modId; ?>.bottom-position a div{
      border-radius: 100% 100% 0 0;
    }
    #mainButtons_tablet<?php echo $modId; ?>.left-position a div{
      border-radius: 0 100% 100% 0;
    }
    #mainButtons_tablet<?php echo $modId; ?>.right-position a div{
      border-radius: 100% 0 0 100%;
    }
  <?php  }?>

  #mainButtons_tablet<?php echo $modId; ?> {
    z-index: 99999;
  }
  #mainButtons_tablet<?php echo $modId; ?> a div {
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    transition: all 0.3s ease-out;
  }
  <?php
///////////////////////end for all\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

/////////////////////Start for custom blocks\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  if($params->get('module_style') =='horizontal'){
/////////////////////Start for GORIZONTAL\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
    #mainButtons_tablet<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_tablet<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px!important;*/
    }

    .mainCom{
      padding-left: 8px !important;
    }
    .mainCom .fb_like_button_container,.mainCom .fb_share_button_container,.mainCom .fb_send_button_container,
    .mainCom .cmp_in_container,.mainCom .cmp_twitter_container,.mainCom .cmp_vk_container,.cmp_google_container,
    .mainCom .cmp_ok_container{
      display: inline-block;
      line-height: 10px;
    }
    .mainCom .cmp_ok_container{
      margin-right: 4px;
    }



    .mainCom .fb_like_button_container{
       <?php
      if($params->get('fb_like_verb_to_display') == 'recommend'){
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          /*margin: 0px -353px 0px 0;*/
          margin-right: 4px;
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          /*margin: 0px -126px 0px 0;*/
          margin-right: 4px;
          <?php }else{ ?>
            /*margin: 0px -324px 0px 0;*/
            margin-right: 4px;
        <?php }
      }else{
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          /*margin: 0px -398px 0px 0;*/
          margin-right: 4px;
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          /*margin: 0px -213px 0px 0;*/
          margin-right: 4px;
          <?php }else{ ?>
            /*margin: 0px -369px 0px 0;*/
            margin-right: 4px;
        <?php }
        } ?>
    }

 

    .addthis_button_more {
      display: inline-block;
      z-index: 999;
      position: relative;
    }

    #mainButtons<?php echo $modId; ?> #addthis_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/addthis-icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: 20px;
      height: 20px;
      border-radius: 2px;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      -o-border-radius: 2px;
      display: inline-block;
      margin-right: 3px;
    }
  #mainButtons<?php echo $modId; ?> #addthis_icon:hover {
      opacity: 0.8;
    }

    #mainButtons_tablet<?php echo $modId; ?> .fb_send_button_container{
      margin: 0px -398px 0px 0;
    }
    .mainCom .cmp_twitter_container{
      margin-right: 4px;
      position: relative;
    }
    .mainCom .cmp_google_container,.mainCom .cmp_vk_container,.mainCom .cmp_ok_container{
      position: relative;
    }
    .cmp_vk_container a span img {
      vertical-align: bottom;
    }
    .mainCom .cmp_vk_container{
      margin-right: 3px;
      position: relative;
    }
    .mainCom .pluginButtonContainer{
      width: 62px;
    }
    #mainButtons_tablet<?php echo $modId; ?> .fb_send_button_container span{
      margin-right: 4px;
    }
    .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_in_container {
      margin-right: 4px;
    }
    .cmp_in_container span {
      line-height: 0!important;
    }

    #mainButtons<?php echo $modId; ?> [class^=ig-b-] {
        position: relative;
        /*top: -7px;*/
        margin-right: 4px;
    }
    #mainButtons<?php echo $modId; ?> [class^=ig-b-] img {
        height: 21px;
    }
    #mainButtons<?php echo $modId; ?> span[class^=PIN_] {
        margin-right: 4px;
    }
    #mainButtons<?php echo $modId; ?> [data-pin-log="button_follow"] {
        top: -6px;
    }

     #mainButtons<?php echo $modId; ?> .pinterest_follow{
      margin-right: 4px;
      margin-left: 4px;

     }



    .cmp_google_container{
      <?php 

          if($params->get('annotation_google') == 'bubble'){?>
          margin: 0px -29px 0px 0;
      <?php }elseif($params->get('annotation_google') == 'inline'){ ?>
          margin: 0px -255px 0px 0;
      <?php }else{ ?>
          margin: 0px 4px 0px 0;
      <?php } ?>
    }

    .tumblr-share-container{
      margin-right: 2px;
    }


  <?php
/////////////////////END GORIZONTAL\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  }elseif($params->get('module_style') =='vertical'){
/////////////////////Start VERTICAL\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>

#mainButtons<?php echo $modId; ?> .pinterest_follow a{
  display: inline-block;
  margin-bottom: 5px;
}

.fb_send_button_container{
  margin-bottom: 5px;
}

.fb-send.fb_iframe_widget{
  line-height: 20px;
  vertical-align: top;
}

.fb_send_button_container{
  margin-bottom: 5px;
}

    .addthis_button_more {
      display: block;
      z-index: 999;
      position: relative;
    }

    #mainButtons<?php echo $modId; ?> #addthis_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/addthis-icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: 20px;
      height: 20px;
      border-radius: 2px;
      -webkit-border-radius: 2px;
      -moz-border-radius: 2px;
      -o-border-radius: 2px;
      display: inline-block;
      margin-right: 3px;
    }
    #mainButtons<?php echo $modId; ?> #addthis_icon:hover {
      opacity: 0.8;
    }
    .mainCom [class^=ig-b-] {
            display: block;
        }
    .mainCom span[class^=PIN_] {
        display: block;
    }

    .mainCom .buttonPin{
        margin-bottom: 5px;
    }
     .mainCom span[class^=PIN_] span[class^=PIN_] {
          margin: 0;
      }

    #mainButtons_tablet<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_tablet<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px!important;*/
    }
      .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_vk_container, .fb_share_button_container, .fb_like_button_container{
      margin-bottom: 5px;
    }

  <?php
/////////////////////END VERTICAL\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  }elseif($params->get('module_style')!='horizontal' || $params->get('module_style')=='vertical'){
/////////////////////All styles FOR TOP/RIGHT/BOTTOM/LEFT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
    #mainButtons_tablet<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_tablet<?php echo $modId; ?> .fb_send_button_container span{
        /*width: 450px!important;*/
    }
    #mainButtons_tablet<?php echo $modId; ?> a div{
        position: relative;
    }

    .mainCom .fb_like_button_container,
    .mainCom .fb_send_button_container, .mainCom .cmp_ok_container{
        margin-top: 4px;
    }
    span[id$=_count_box]{
        position: absolute;
        bottom: 0;
        left:0;
        right: 0;
        text-align: center;
        color: #000;
        line-height: 18px;
    }

    <?php
/////////////////////END styles FOR TOP/RIGHT/BOTTOM/LEFT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    if($params->get('module_style') =='top'){
/////////////////////Styles FOR TOP ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      #mainButtons_tablet<?php echo $modId; ?> a{
        display: inline-block;
      }
      #mainButtons_tablet<?php echo $modId; ?>{
        position: fixed;
        top: 0;
      }
      #mainButtons_tablet<?php echo $modId; ?> a div:hover{
        height:  <?php echo $icon_size_number_tablet + $icon_size_number_tablet *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_tablet<?php echo $modId; ?> a{
        vertical-align: top;
      }

    <?php
/////////////////////END FOR TOP ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='left'){
/////////////////////Styles FOR LEFT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      #mainButtons_tablet<?php echo $modId; ?>{
        position: fixed;
        left: 0;
      }
      #mainButtons_tablet<?php echo $modId; ?> a div:hover{
        width:  <?php echo $icon_size_number_tablet + $icon_size_number_tablet *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }

      <?php
/////////////////////END Styles FOR LEFT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='right'){
/////////////////////Styles FOR RIGHT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      #mainButtons_tablet<?php echo $modId; ?>{
        position: fixed;
        right: 0;
      }
      #mainButtons_tablet<?php echo $modId; ?> a div:hover{
        width:  <?php echo $icon_size_number_tablet + $icon_size_number_tablet *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_tablet<?php echo $modId; ?> a div{
        float: right;
        clear: both;
      }

    <?php
/////////////////////END Styles FOR RIGHT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='bottom'){
/////////////////////Styles FOR BOTTOM ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      #mainButtons_tablet<?php echo $modId; ?> a{
        display: inline-block;
      }
      #mainButtons_tablet<?php echo $modId; ?> a div:hover{
        height:  <?php echo $icon_size_number_tablet + $icon_size_number_tablet *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_tablet<?php echo $modId; ?>{
        position: fixed;
        bottom: 0;
        line-height: 10px;
      }
      <?php
/////////////////////END Styles FOR BOTTOM ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    }elseif($params->get('module_style') =='horizontal_icon'){
/////////////////////Styles FOR GORIZONTAL ICON\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
      #mainButtons_tablet<?php echo $modId; ?> a{
        display: inline-block;
      }
      <?php
    }
/////////////////////END Styles FOR GORIZONTAL ICON\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?>
    #mainButtons_tablet<?php echo $modId; ?> #fb_share_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face.png';?>);
      background-position: center;
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #facebook-share-dialog {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }
    #mainButtons_tablet<?php echo $modId; ?> #liked_in_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/liked_in.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #twitter_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/twitter_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #google_plus_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/google_plus_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #vk_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/vk_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #addthis_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/addthis-icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #instagram_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/instagram_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #pinterest_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #pinterest_icon:hover{
      cursor: pointer;
    }
    #mainButtons_tablet<?php echo $modId; ?> #pinterest_follow_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_follow_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #pinterest_follow_icon:hover {
      cursor: pointer;
    }
    #mainButtons_tablet<?php echo $modId; ?> #tumblr_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/tumblr_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #odnoklasniki_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/odnoklasniki_icon.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }
    #mainButtons_tablet<?php echo $modId; ?> #fb_send_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face_send.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%; /
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_tablet; ?>;
      height: <?php echo $icon_size_tablet; ?>;
    }

    <?php
  }
  ?>
</style>
<?php ///////////////////////////////END CSS FOR TABLET DEVICE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


///////////////////////////////START CSS FOR MOBILE DEVICE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$icon_size_number_mobile=$icon_size_number*0.5;
$icon_size_mobile = $icon_size_number_mobile.'px';
?>
  <style type="text/css">
  <?php if($params->get("icon_type", 1) == 2){?>
    #mainButtons_mobile<?php echo $modId; ?> a div{
      border-radius: 100%;
    }
  <?php } ?>
  <?php if($params->get("icon_type", 1) == 3){ ?>
    #mainButtons_mobile<?php echo $modId; ?> a div{
      border-radius: 0 0 100% 100%;
    }
  <?php  }?>
  #mainButtons_mobile<?php echo $modId; ?> {
    z-index: 99999;
  }
  #mainButtons_mobile<?php echo $modId; ?> a div {
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    transition: all 0.3s ease-out;
  }
  <?php
///////////////////////end for all\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

/////////////////////Start for custom blocks\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  if($params->get('module_style') =='horizontal'){?>
    #mainButtons_mobile<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_mobile<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px!important;*/
    }
    .mainCom{
      padding-left: 8px!important;
    }
    .mainCom .fb_like_button_container,.mainCom .fb_share_button_container,.mainCom .fb_send_button_container,
    .mainCom .cmp_in_container,.mainCom .cmp_twitter_container,.mainCom .cmp_vk_container,.cmp_google_container,
    .mainCom .cmp_ok_container{
      display: inline-block;
      line-height: 10px;
    }
    .mainCom .cmp_ok_container{
      margin-right: 4px;
    }
    .mainCom .fb_like_button_container{
       <?php
      if($params->get('fb_like_verb_to_display') == 'recommend'){
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          /*margin: 0px -353px 0px 0;*/
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          /*margin: 0px -126px 0px 0;*/
          <?php }else{ ?>
            /*margin: 0px -324px 0px 0;*/
        <?php }
      }else{
        if($params->get('fb_like_layout_style') =='box_count' || $params->get('fb_like_layout_style') =='button' ){?>
          /*margin: 0px -398px 0px 0;*/
        <?php }elseif($params->get('fb_like_layout_style') == 'standard'){ ?>
          /*margin: 0px -213px 0px 0;*/
          <?php }else{ ?>
            /*margin: 0px -369px 0px 0;*/
        <?php }
        } ?>
    }
    #mainButtons_mobile<?php echo $modId; ?> .fb_send_button_container{
      margin: 0px -398px 0px 0;
    }
    .mainCom .cmp_twitter_container{
      margin-right: 4px;
      position: relative;
    }
    .mainCom .cmp_google_container,.mainCom .cmp_vk_container,.mainCom .cmp_ok_container{
      position: relative;
    }
    .cmp_vk_container a span img {
      vertical-align: bottom;
    }
    .mainCom .cmp_vk_container{
      margin-right: 3px;
      position: relative;
    }
    .mainCom .pluginButtonContainer{
      width: 62px;
    }
    #mainButtons_mobile<?php echo $modId; ?> .fb_send_button_container span{
      margin-right: 4px;
    }
    .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_in_container {
      margin-right: 4px;
    }
    .cmp_in_container span {
      line-height: 0!important;
    }

    .cmp_google_container{
      <?php 
          if($params->get('annotation_google') == 'bubble'){?>
          margin: 0px 4px 0px 0;
      <?php }elseif($params->get('annotation_google') == 'inline'){ ?>
          margin: 0px 4px 0px 0;
      <?php }else{ ?>
          margin: 0px 4px 0px 0;
      <?php } ?>
    }
  <?php
  }elseif($params->get('module_style') =='vertical'){ ?>
    #mainButtons_mobile<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_mobile<?php echo $modId; ?> .fb_send_button_container span{
      /*width: 450px!important;*/
    }
      .cmp_vk_container table tr td a, #vkshare_cnt0 {
      height: auto!important;
    }
    .cmp_vk_container, .fb_share_button_container, .fb_like_button_container{
      margin-bottom: 5px;
    }
  <?php
  }elseif($params->get('module_style')!='horizontal' || $params->get('module_style')=='vertical'){ ?>
    #mainButtons_mobile<?php echo $modId; ?> .fb_like_button_container span,#mainButtons_mobile<?php echo $modId; ?> .fb_send_button_container span{
        /*width: 450px!important;*/
    }
    #mainButtons_mobile<?php echo $modId; ?> a div{
        position: relative;
    }
    .mainCom .fb_like_button_container,
    .mainCom .fb_send_button_container, .mainCom .cmp_ok_container{
        margin-top: 4px;
    }
    span[id$=_count_box]{
        position: absolute;
        bottom: 0;
        left:0;
        right: 0;
        text-align: center;
        color: #000;
        line-height: 18px;
    }


    <?php
    if($params->get('module_style') =='top'){?>
      #mainButtons_mobile<?php echo $modId; ?> a,{
        display: inline-block;
      }
      #mainButtons_mobile<?php echo $modId; ?>{
        position: fixed;
        top: 0;
      }
      #mainButtons_mobile<?php echo $modId; ?> a div:hover,{
        height:  <?php echo $icon_size_number_mobile + $icon_size_number_mobile *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_mobile<?php echo $modId; ?> a,{
        vertical-align: top;
      }
    <?php
    }elseif($params->get('module_style') =='left'){?>
      #mainButtons_mobile<?php echo $modId; ?>{
        position: fixed;
        left: 0;
      }
      #mainButtons_mobile<?php echo $modId; ?> a div:hover,{
        width:  <?php echo $icon_size_number_mobile + $icon_size_number_mobile *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      <?php
    }elseif($params->get('module_style') =='right'){?>
      #mainButtons_mobile<?php echo $modId; ?>{
        position: fixed;
        right: 0;
      }
      #mainButtons_mobile<?php echo $modId; ?> a div:hover{
        width:  <?php echo $icon_size_number_mobile + $icon_size_number_mobile *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_mobile<?php echo $modId; ?> a div{
        float: right;
        clear: both;
      }

    <?php
    }elseif($params->get('module_style') =='bottom'){ ?>
      #mainButtons_mobile<?php echo $modId; ?> a,{
        display: inline-block;
      }
      #mainButtons_mobile<?php echo $modId; ?> a div:hover{
        height:  <?php echo $icon_size_number_mobile + $icon_size_number_mobile *0.2 .'px !important'; ?>;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
      }
      #mainButtons_mobile<?php echo $modId; ?>{
        position: fixed;
        bottom: 0;
        line-height: 10px;
      }
      <?php
    }elseif($params->get('module_style') =='horizontal_icon'){?>
      #mainButtons_mobile<?php echo $modId; ?> a{
        display: inline-block;
      }
      <?php
    }?>
    #mainButtons_mobile<?php echo $modId; ?> #fb_share_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face.png';?>);
      background-position: center;
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #facebook-share-dialog {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }
    #mainButtons_mobile<?php echo $modId; ?> #liked_in_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/liked_in.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #twitter_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/twitter_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #google_plus_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/google_plus_icon.png';?>);
      -moz-background-size: 100%; /* Firefox 3.6+ */
      -webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
      -o-background-size: 100%; /* Opera 9.6+ */
      background-size: 100%; /* Современные браузеры */
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #instagram_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/instagram_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #pinterest_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #pinterest_follow_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/pinterest_follow_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #tumblr_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/tumblr_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #odnoklasniki_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/odnoklasniki_icon.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #fb_send_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/face_send.png';?>);
      -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%; /
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #vk_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/vk_icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    #mainButtons_mobile<?php echo $modId; ?> #addthis_icon{
      background-image: url(<?php echo $mosConfig_live_site.
          'modules/mod_social_comments_sharing/images/addthis-icon.png';?>);
          -moz-background-size: 100%;
      -webkit-background-size: 100%;
      -o-background-size: 100%;
      background-size: 100%;
      background-position: center;
      width: <?php echo $icon_size_mobile; ?>;
      height: <?php echo $icon_size_mobile; ?>;
    }
    <?php
  }
  /////////////////////END All styles FOR TOP/RIGHT/BOTTOM/LEFT ICONs\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  ?>
  /*----------------------------hide or show button--------------------------------*/
</style>