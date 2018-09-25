<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPSubscribe
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="itp-subscribe<?php echo $moduleclass_sfx; ?>">
    <?php if($params->get("mail_form")){?>
    <form style="<?php echo $params->get("feedburner_style");?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $params->get("feedburner_uri");?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
        <p style="text-align:left;"><?php echo $headText;?></p>
        <p><input type="text" style="width:<?php echo $params->get("feedburner_iw"); ?>px" name="email" class="inputbox" /></p>
        <input type="hidden" value="<?php echo $params->get("feedburner_uri");?>" name="uri" />
        <input type="hidden" name="loc" value="<?php echo $params->get("feedburner_l");?>" />
        <input type="submit" class="button" value="<?php echo $feedburnerButton; ?>" />
    </form>
    <div style="clear:both;">&nbsp;</div>
    <?php }?>
    
    <?php if($params->get("eform", "")){?>
    <?php echo $params->get("eform");?>
    <?php }?>
    
    <?php
    $style = $params->get('icons_set'); 
    
    if ( false !== strpos( $style, "big" ) ) {
    	$dir = $style;
    	$style = "big";
    }
    
    switch ( $style ) {
    
    	case "big" :?>
    	
    	   <?php if ( $params->get('show_rss') ) {?>
                <a type="application/rss+xml" rel="alternate" href="<?php echo $params->get('rss_url'); ?>" <?php if($params->get('rss_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $rssTitle; ?>" title="<?php echo $rssTitle; ?>" src="modules/mod_itpsubscribe/images/<?php echo basename( $dir ); ?>/rss.png"/></a>
            <?php }?>
            
            <?php if ( $params->get('show_facebook') ) {?>  
                <a href="<?php echo $params->get('facebook_url'); ?>" <?php if($params->get('facebook_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $facebookTitle; ?>" title="<?php echo $facebookTitle; ?>" src="modules/mod_itpsubscribe/images/<?php echo basename( $dir ); ?>/facebook.png"/></a>
            <?php }?>
            
            <?php if ( $params->get('show_google') ) {?>
                <a href="<?php echo $params->get('google_url'); ?>" <?php if($params->get('google_open_new')) { echo 'target="_blank"';}?> ><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $googleTitle; ?>" title="<?php echo $googleTitle; ?>" src="modules/mod_itpsubscribe/images/<?php echo basename( $dir ); ?>/google.png"/></a>
            <?php }?>
            
    		<?php if ( $params->get('show_twitter') ) {?>  
    	        <a href="<?php echo $params->get('twitter_url'); ?>" <?php if($params->get('twitter_open_new')) { echo 'target="_blank"';}?> ><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $twitterTitle; ?>" title="<?php echo $twitterTitle; ?>" src="modules/mod_itpsubscribe/images/<?php echo basename( $dir ); ?>/twitter.png"/></a>
    	    <?php }?>
    	    
    	    <?php if ( $params->get('show_email') ) {?>
                <a href="<?php echo $params->get('email_url'); ?>" <?php if($params->get('email_open_new')) { echo 'target="_blank"';}?> ><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $emailTitle; ?>" title="<?php echo $emailTitle; ?>" src="modules/mod_itpsubscribe/images/<?php echo basename( $dir ); ?>/email.png"/></a>
            <?php }?>
        
        <?php break; ?>
        
    	    <?php default: // SMALL ?>
    	    
    	    <?php if ( $params->get('show_rss') ) {?>
    	       <div>
    		    <a type="application/rss+xml" rel="alternate" href="<?php echo $params->get('rss_url'); ?>" <?php if($params->get('rss_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $rssTitle; ?>" src="modules/mod_itpsubscribe/images/small/rss.png" width="16" height="16" /></a>
    			<a type="application/rss+xml" rel="alternate" href="<?php echo $params->get('rss_url'); ?>" <?php if($params->get('rss_open_new')) { echo 'target="_blank"';}?>><?php echo $rssTitle; ?></a>
    		  </div>
    	    <?php }?>
    	    
    	    <?php if ( $params->get('show_email') ) {?>
    	       <div>
    			<a href="<?php echo $params->get('email_url'); ?>" <?php if($params->get('email_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $emailTitle; ?>" src="modules/mod_itpsubscribe/images/small/email.png" width="16" height="16" /></a>
    			<a href="<?php echo $params->get('email_url'); ?>" <?php if($params->get('email_open_new')) { echo 'target="_blank"';}?>><?php echo $emailTitle; ?></a>
    		  </div>
    		<?php }?>
    		
    		<?php if ( $params->get('show_facebook') ) {?> 
              <div>
                <a href="<?php echo $params->get('facebook_url'); ?>" <?php if($params->get('facebook_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $facebookTitle; ?>" src="modules/mod_itpsubscribe/images/small/facebook.png" width="16" height="16" /></a>
                <a href="<?php echo $params->get('facebook_url'); ?>" <?php if($params->get('facebook_open_new')) { echo 'target="_blank"';}?>><?php echo $facebookTitle; ?></a>
            </div>
            <?php }?>
    		
    		<?php if ( $params->get('show_google') ) {?>	
    		  <div>
    			<a href="<?php echo $params->get('google_url'); ?>" <?php if($params->get('google_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $googleTitle; ?>" src="modules/mod_itpsubscribe/images/small/google.png" width="16" height="16" /></a>
    			<a href="<?php echo $params->get('google_url'); ?>" <?php if($params->get('google_open_new')) { echo 'target="_blank"';}?>><?php echo $googleTitle; ?></a>
    		</div>
    		<?php }?>
    		
    		<?php if ( $params->get('show_twitter') ) {?>	
    		  <div>
    			<a href="<?php echo $params->get('twitter_url'); ?>" <?php if($params->get('twitter_open_new')) { echo 'target="_blank"';}?>><img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $twitterTitle; ?>" src="modules/mod_itpsubscribe/images/small/twitter.png" width="16" height="16" /></a>
    			<a href="<?php echo $params->get('twitter_url'); ?>" <?php if($params->get('twitter_open_new')) { echo 'target="_blank"';}?>><?php echo $twitterTitle; ?></a>
    		</div>
    		<?php }?>
    	
        <?php break; ?>
        
    <?php 
    } ?>
    <?php echo ItpSubscribeHelper::getExtraButtons($params);?>
    <?php
    // Showing the extra twitter icon 
    if ( $params->get('show_extra_twitter') ) {
    	$extra_twitter_icon = $params->get('extra_twitter_icon');
    ?>
              <div >
                <a href="<?php echo $params->get('extra_twitter_url'); ?>" <?php if($params->get('extra_twitter_open_new')) { echo 'target="_blank"';}?>>
                <img style="border: 0pt none ; vertical-align: middle; " alt="<?php echo $extraTwitterTitle; ?>" title="<?php echo $extraTwitterTitle; ?>" src="modules/mod_itpsubscribe/images/twitters/<?php echo basename($extra_twitter_icon); ?>"/>
                </a>
              </div>
    <?php } ?> 
</div>
