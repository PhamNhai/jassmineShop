<?php 
/*
* @version 2.1
* @package social sharing
* @copyright 2012 OrdaSoft
* @author 2012 Andrey Kvasnekskiy (akbet@ordasoft.com ), Roman Akoev (akoevroman@gmail.com)
* @description social sharing, sharing WEB pages in LinkedIn, FaceBook, Twitter and Google+ (G+)
*/
// No direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');

class JFormFieldSharing extends JFormField {
  protected $type = 'Sharing';
  public $_name = 'Sharing';

  protected function getLabel() {
    return '';
  }

  protected function _build($moduleID, $name, $value) {
    $mosConfig_live_site=JURI::base();
    $mod_row =  JTable::getInstance ( 'Module', 'JTable' );
    $mod_row->load ( $moduleID );
    $params = new JRegistry;
    $params->loadString($mod_row->params);

    $document = JFactory::getDocument();
    $app = JFactory::getApplication();
    $document->addStyleSheet($mosConfig_live_site .'../modules/mod_social_comments_sharing/assets/css/admin.css');
    $document->addStyleSheet($mosConfig_live_site .'../modules/mod_social_comments_sharing/assets/css/jquery-ui.min.css');
    $document->addScript($mosConfig_live_site .'../modules/mod_social_comments_sharing/assets/js/jquery-ui.min.js'); 

    ?>
    <script>
      var mod_path = "<?php echo $mosConfig_live_site ?>"+'../modules/mod_social_comments_sharing/images/';
      var list = "<?php echo $params->get('soc_ordering')?>";//string facebook,google,of......
      if(!list){
        list='facebook,google,twitter,linkedin,vk,ok,add_this,tumblr,pinterest,instagram';
      }
      list = list.split(',');///to array ['facebook','google',....]
      jQuery( document ).ready(function() {

///////////////START PREVIEW BLOCK\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
      var mod_style = jQuery('#jform_params_module_style').val();
      newitem = '<div id="preview_modstyle"><div>';
      jQuery('.span9').append(newitem);
      jQuery(".span9 .control-group").wrapAll("<div class='control-block'></div>")
      var div = jQuery('#preview_modstyle');
      newitem= '<img alt="Preview" src="'+mod_path+'mod-'+mod_style+'.png">';
      div.html(newitem);

      jQuery('#jform_params_module_style').change(function() {
        var mod_style = jQuery(this).val();
        newitem= '<img alt="Preview" src="'+mod_path+'mod-'+mod_style+'.png">';
        div.html(newitem);
      });
      
///////////////END PREVIEW BLOCK\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

///////////////START CHANGE MODULE HEADER\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
     var hedDiv = jQuery('.container-title');
     newitem= '<h1 class="page-title"><span class="icon-cube module"></span>Module Manager: Module Social comments and sharing pro</h1>';
     hedDiv.html(newitem);
///////////////END CHANGE MODULE HEADER\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

///////////////START SORTABLE BLOCK\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        //add id for sortable and not sortable li
//        jQuery( "#myTabTabs li:eq(0),#myTabTabs li:eq(1),#myTabTabs li:last" ).addClass("unsortable");
        jQuery( "#myTabTabs li:eq(0),#myTabTabs li:eq(1),#myTabTabs li:nth-last-child(1),#myTabTabs li:nth-last-child(2)" ).addClass("unsortable");
        
        var first = jQuery( "#myTabTabs li:last-child" );
        jQuery( "#myTabTabs" ).find( "a[href$='-facebook']").parent().attr('id', 'facebook');
        jQuery( "#myTabTabs" ).find( "a[href$='-google']").parent().attr('id', 'google');
        jQuery( "#myTabTabs" ).find( "a[href$='-twitter']").parent().attr('id', 'twitter');
        jQuery( "#myTabTabs" ).find( "a[href$='-linkedin']").parent().attr('id', 'linkedin');
        jQuery( "#myTabTabs" ).find( "a[href$='-vk']").parent().attr('id', 'vk');
        jQuery( "#myTabTabs" ).find( "a[href$='-add_this']").parent().attr('id', 'add_this');
        jQuery( "#myTabTabs" ).find( "a[href$='-ok']").parent().attr('id', 'ok');
        jQuery( "#myTabTabs" ).find( "a[href$='-tumblr']").parent().attr('id', 'tumblr');
        jQuery( "#myTabTabs" ).find( "a[href$='-pinterest']").parent().attr('id', 'pinterest');
        jQuery( "#myTabTabs" ).find( "a[href$='-instagram']").parent().attr('id', 'instagram');
        jQuery( "#myTabTabs" ).find( "a[href$='-disqus']").parent().attr('id', 'disqus').addClass("unsortable");

        //reorder li after save
         list.each(function (key, value) {
           first = jQuery( "#myTabTabs" ).find( "a[href$='-"+key+"']").parent().insertAfter(first);
           return;
         });

        //make li sortable
         jQuery( "#myTabTabs" ).sortable({
           items:"li:not(.unsortable)",
           scroll: false,
           axis: "x",
           'update': function (event, ui) {
             var order = jQuery(this).sortable('toArray');
             jQuery( "#jform_params_soc_ordering" ).val(order);
             }
         });
      });
///////////////END SORTABLE BLOCK\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    </script>
    <?php
  }

  protected function getInput() {
      JHtml::_('behavior.framework', true);
      JHtml::_('behavior.modal');

      $moduleID = $this->form->getValue('id');
      return $this->_build($moduleID, $this->name, $this->value);
  }

}