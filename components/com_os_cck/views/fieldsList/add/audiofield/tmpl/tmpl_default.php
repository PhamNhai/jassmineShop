<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/


$fName = $field->db_field_name;
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$audios = array();
$db = JFactory::getDBO();
if (!empty($entityInstance->eiid)) {
    $db->setQuery("SELECT * FROM #__os_cck_audio_source WHERE fk_eiid=" . $entityInstance->eiid);
    $audios = $db->loadObjectList();
}
if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
if(!isset($layout_params['field_styling']))
    $layout_params['field_styling'] = '';
if(!isset($layout_params['custom_class']))
    $layout_params['custom_class'] = '';
?>
<script>
  var request = null;
  var aid=1;
  function createRequest_audio() {
    if (request != null)
    return;
    try {
      request = new XMLHttpRequest();
    } catch (trymicrosoft) {
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (othermicrosoft) {
            try {
              request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (failed) {
              request = null;
            }
        }
    }
    if (request == null)
      alert(" :-( ___ Error creating request object! ");
  }

  function testInsert_audio(id1,upload){
    for(var i=1; i< upload_audios; i++){
        if(upload.id != ('new_upload_audio'+i) &&
        document.getElementById('new_upload_audio'+i).value == upload.value){
            return false;
      }
    }
    return true;
  }

  function refreshRandNumber_audio(id1,upload){
    id=id1;
    if(testInsert_audio(id1,upload)){
      createRequest_audio();
      var url = "<?php echo JUri::root().'/index.php?option=com_os_cck&task=checkFile&format=raw';
      ?>&file="+upload.value+
      "&path=<?php echo str_replace('\\', '/', JUri::root()) . '/components/com_os_cck/files/audio/'?>";
       try{
        request.onreadystatechange = updateRandNumber1_audio;
        request.open("GET", url,true);
        request.send(null);
        }catch (e)
        {
            alert(e);
        }
    }else{
      alert( "You alredy select this audio file");
      upload.value="";
    }
  }

  function updateRandNumber1_audio(){
    if (request.readyState == 4) {
      document.getElementById("randNumAudio"+aid).innerHTML = request.responseText;
    }
  }

  var upload_audios=0;
  function new_audios(){
    div = document.getElementById("a_items");
    button = document.getElementById("a_add");
    upload_audios++;
    newitem = "<div>"+
                  "<span width='15%'>"+
                       "<?php echo JText::_('COM_OS_CCK_LABEL_AUDIO_UPLOAD') ?>" + upload_audios + ":"+
                  "</span>"+
                  "<span width='85%'>"+
                    "<input style=\"float:left; width:100%\" type=\"file\" "+
                      " onClick=\"jQuerCCK('#new_upload_audio_url"+upload_audios+"').val('');\" " +
                      " onChange=\"refreshRandNumber_audio("+upload_audios+",this);\" "+
                      " name=\"new_upload_audio"+upload_audios+"\" "+
                      " id=\"new_upload_audio"+upload_audios+"\" value=\"\"size=\"45\">"+
                    "<span id=\"randNumAudio"+upload_audios+"\" style=\"color:red;\"></span>"+
                  "</span>"+
                "</div>"+
                "<div><span style=\"text-align:center\"> OR </span></div>";
    newnode = document.createElement("span");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode,button);

    newitem = "<div><span width='15%'>" +
                  "<?php echo JText::_('COM_OS_CCK_LABEL_AUDIO_UPLOAD_URL'); ?>: </span>"+
                  "<span width='85%'>"+
                    "<input style=\"float:left; width:90%\" type=\"text\" "+
                            " name=\"new_upload_audio_url"+upload_audios+"\" "+
                            " id=\"new_upload_audio_url"+upload_audios+
                            "\" value=\"\"size=\"45\"></span>"+
                "</div>";
    newnode=document.createElement("span");
    newnode.innerHTML=newitem;
    div.insertBefore(newnode,button);
    var allowed_aud_files = 5;
    if(upload_audios + <?php echo count($audios); ?> >= allowed_aud_files) {
      button.setAttribute("style","display:none");
    }
  }
</script>
<?php
if (count($audios) > 0) {?>
  <div <?php echo $layout_params['field_styling']?> class="<?php echo $layout_params['custom_class']?>">
    <span width="185"></span>
      <span><span id="error_audio"></span></span>
  </div>
  <div>
    <span valign="top" align="right"><?php echo JText::_("COM_OS_CCK_LABEL_AUDIO")?></span>
    <?php
    for ($i = 0;$i < count($audios);$i++) { ?>
      <div>
        <span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_AUDIO_ATTRIBUTE").($i + 1)?></span>
        <span>
          <?php
          if (isset($audios[$i]->src) && substr($audios[$i]->src, 0, 4) != "http") {?>
            <input type="text" size="60" value="<?php echo JURI::root() . $audios[$i]->src?>" readonly="readonly"/>
            <?php
          }else{?>
            <input type="text" size="60" value="<?php echo $audios[$i]->src?>" readonly="readonly"/>
            <?php
          }?>
        </span>
      </div>
      <div>
        <span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_AUDIO_DELETE")?></span>
        <span>
          <?php
          if(isset($audios[$i]->id)){?>
            <input type="checkbox" name="audio_option_del<?php echo $audios[$i]->id?>" value="<?php echo $audios[$i]->id?>">
            <?php
          }?>
        </span>
      </div>
      <?php
    } ?>
  </div>
  <?php
}?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <span id="a_items">
      <input <?php echo $layout_params['field_styling']?> class="<?php echo $layout_params['custom_class'].$required?>"  type="button" name="new_audio"
      value="<?php echo JText::_('COM_OS_CCK_LABEL_ADD_NEW_AUDIO_FILE'); ?>" onClick="new_audios()"
      id="a_add"/>
  </span>
</span>