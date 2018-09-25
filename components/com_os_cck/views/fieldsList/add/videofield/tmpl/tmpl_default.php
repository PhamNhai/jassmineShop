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
$db = JFactory::getDbo();

if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
  $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
  $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
}
$fName = $field->db_field_name;
$required = '';
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$tracks = array();
$videos = array();
$youtubeId = "";
if (!empty($entityInstance->eiid)) {
  $db->setQuery("SELECT * FROM #__os_cck_video_source WHERE fk_eiid=" . $entityInstance->eiid);
  $videos = $db->loadObjectList();
}
$youtube = new stdClass();
for ($i = 0;$i < count($videos);$i++) {
  if (!empty($videos[$i]->youtube)) {
    $youtube->code = $videos[$i]->youtube;
    $youtube->id = $videos[$i]->id;
    break;
  }
}
if (!empty($entityInstance->eiid)) { //check video file
  $db->setQuery("SELECT * FROM #__os_cck_track_source WHERE fk_eiid=" . $entityInstance->eiid);
  $tracks = $db->loadObjectList();
}
if(!isset($layout_params['field_styling']))$layout_params['field_styling'] = '';
if(!isset($layout_params['custom_class']))$layout_params['custom_class'] = '';
?>

<script language="javascript" type="text/javascript">
  var request = null;
  var tid=1;
  function createRequest_track() {
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

  function testInsert_track(id1,upload){
    for(var i=1; i< t_counter; i++){
      if(upload.id != ('new_upload_track'+i) &&
      document.getElementById('new_upload_track'+i).value == upload.value){
        return false;
      }
    }
    return true;
  }

  function refreshRandNumber_track(id1,upload){
    id=id1;
    if(testInsert_track(id1,upload)){
      createRequest_track();
      var url = "<?php echo JURI::root() . "/index.php?option=com_os_cck&task=checkFile&format=raw";?>&file="+upload.value+"&path=<?php echo str_replace("\\", "/", JURI::root()) . '/components/com_os_cck/files/track/'?>";
     try{
      request.onreadystatechange = updateRandNumber_track;
      request.open("GET", url,true);
      request.send(null);
      }catch (e)
      {
        alert(e);
      }
    }
    else
    {
      alert("You alredy select this track file");
      upload.value="";
    }
  }

  function updateRandNumber_track() {
    if (request.readyState == 4) {
      document.getElementById("randNumTrack"+tid).innerHTML = request.responseText;
    }
  }
</script>
<!-- END Ajax load track-->

<!-- START Ajax load video-->
<script language="javascript" type="text/javascript">
  var request = null;
  var vid=1;
  function createRequest_video(){
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

  function testInsertVideo(id1,upload){
    for(var i=1 ;i< v_counter; i++){
      if(upload.id != ('new_upload_video'+i) &&
      document.getElementById('new_upload_video'+i).value == upload.value)
      {
        return false;
      }
    }
    return true;
  }

  function refreshRandNumber_video(id1,upload){
    id=id1;
    if(testInsertVideo(id1,upload)){
      createRequest_video();
      var url = "<?php echo JURI::root() . "/index.php?option=com_os_cck&task=checkFile&format=raw";?>&file="+upload.value+"&path=<?php echo str_replace("\\", "/", JURI::root()) . '/components/com_os_cck/files/video/' ?>";
     try{
      request.onreadystatechange = updateRandNumber_video;
      request.open("GET",url,true);
      request.send(null);
      }catch (e)
      {
        alert(e);
      }
    }
    else
    {
      alert("You alredy select this video file");
      upload.value="";
    }
  }

  function updateRandNumber_video() {
    if (request.readyState == 4) {
      document.getElementById("randNumVideo"+vid).innerHTML = request.responseText;
    }
  }
</script>

<!-- END Ajax load video-->
<script language="javascript" type="text/javascript">
  function changeButtomName() {
    document.getElementById('v_add').value = "<?php echo JText::_('COM_OS_CCK_LABEL_VIDEO_ADD_ALTERNATIVE_VIDEO') ?>";
  }
  var v_counter=0;
  function new_videos(){
    div = document.getElementById("v_items");
    button = document.getElementById("v_add");
    v_counter++;
    newitem='<div>'+
                '<span width="160px">'+
                    "<?php echo JText::_('COM_OS_CCK_LABEL_VIDEO_UPLOAD') ?>"+v_counter+
                  ': '+
                '</span>'+
                '<span width="400px">'+
                  '<input style="float:left; width:100%" type="file"'+
                        " onClick=\"jQuerCCK('#new_upload_video_url"+v_counter+"').val('');jQuerCCK('#new_upload_video_youtube_code"+v_counter+"').val('');\" " +
                        ' value ="" onChange="refreshRandNumber_video('+v_counter+',this);"'+
                        ' name="new_upload_video'+v_counter+'" id="new_upload_video'+v_counter+
                        '" value="" size="45">'+
                  '<span id="randNumVideo'+v_counter+'" style="color:red;"></span>'+
                '</span>'+
              '</div>'+
              '<div><span style="margin:5px;">OR</span></div>';
    newnode = document.createElement("span");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<span width="160px">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_VIDEO_UPLOAD_URL'); ?>" +v_counter+
                    ': '+
                  '</span>'+
                  '<span width="400px">'+
                    '<input style="float:left; width:90%" type="text"'+
                      ' name="new_upload_video_url'+v_counter+'"'+
                      ' id="new_upload_video_url'+v_counter+'" value="" size="45">'+
                  '</span>'+
                '</div>'+
                '<div><span style="margin:5px;">OR</span></div>';
    newnode = document.createElement("span");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<span width="160px">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_VIDEO_UPLOAD_YOUTUBE_CODE'); ?>" +
                    ':'+
                  '</span>'+
                  '<span width="400px">'+
                    '<input style="float:left; width:90%" type="text"'+
                          ' name="new_upload_video_youtube_code'+v_counter+'"'+
                          ' id="new_upload_video_youtube_code'+v_counter+'" value="" size="45">'+
                  '</span>'+
                '</div>'+
              '<?php echo JText::_("COM_OS_CCK_LABEL_PRIOTITY"); ?>'
    newnode=document.createElement("span");
    newnode.innerHTML=newitem;
    div.insertBefore(newnode,button);
    var allowed_files = 5;
    if(v_counter + <?php echo count($videos); ?> >= allowed_files) {
      button.setAttribute("style","display:none");
    }
    changeButtomName();
  }

  var t_counter=0;
  function new_tracks(){
    div = document.getElementById("t_items");
    button = document.getElementById("t_add");
    t_counter++;
    newitem = '<div>'+
                  '<label style="float:left;">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_TRACK_UPLOAD') ?>"+t_counter+
                    ': </label>'+
                  '<div>'+
                    '<input style="float:left; width:100%" type="file"'+
                          'onClick="jQuerCCK(\'#new_upload_track_url"'+t_counter+'\').val(\'\')" value =""'+
                          ' onChange="refreshRandNumber_track('+t_counter+',this);"'+
                          ' name="new_upload_track'+t_counter+'"'+
                          ' id="new_upload_track'+t_counter+'" value="" size="45">'+
                    '<span id="randNumTrack'+t_counter+'" style="color:red;"></span>'+
                  '</div>'+
                '</div>'+
                '<span style="margin:5px;"> OR </span>';
    newnode = document.createElement("div");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<label style="float:left;">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_TRACK_UPLOAD_URL'); ?>"+t_counter+
                    ': </label>'+
                  '<div>'+
                    '<input style="float:left; width:90%" type="text"'+
                          ' name="new_upload_track_url'+t_counter+'"'+
                          ' id="new_upload_track_url'+t_counter+'" value="" size="45">'+
                  '</div>'+
                '</div>';
    newnode = document.createElement("div");
    newnode.innerHTML=newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<label style="float:left;">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_TRACK_UPLOAD_KIND'); ?>"+t_counter+
                    ': '+
                  '</label>'+
                  '<div>'+
                    '<input style="float:left; width:90%" type="text"'+
                          ' name="new_upload_track_kind'+t_counter+'"'+
                          ' id="new_upload_track_kind'+t_counter+'" value="" size="45">'+
                  '</div>'+
                '</div>';
    newnode = document.createElement("div");
    newnode.innerHTML=newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<label style="float:left;">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_TRACK_UPLOAD_SCRLANG'); ?>"+t_counter+
                    ':'+
                  '</label>'+
                  '<div>'+
                    '<input style="float:left; width:90%" type="text"'+
                          ' name="new_upload_track_scrlang'+t_counter+'"'+
                          ' id="new_upload_track_scrlang'+t_counter+'" value="" size="45">'+
                  '</div>'+
                '</div>';
    newnode = document.createElement("div");
    newnode.innerHTML = newitem;
    div.insertBefore(newnode,button);

    newitem = '<div>'+
                  '<label style="float:left;">'+
                      "<?php echo JText::_('COM_OS_CCK_LABEL_TRACK_UPLOAD_LABEL'); ?>"+t_counter+
                    ': '+
                  '</label>'+
                  '<div>'+
                    '<input style="float:left; width:90%" type="text"'+
                          ' name="new_upload_track_label'+t_counter+'"'+
                          ' id="new_upload_track_label'+t_counter+'" value="" size="45">'+
                  '</div>'+
                '</div>';
    newnode = document.createElement("div");
    newnode.innerHTML=newitem;
    div.insertBefore(newnode,button);
  }
</script>

<div>
  <span width="185"></span>
  <span><span id="error_video"></span></span>
</div>
<?php
if (count($videos) > 0 && empty($youtube->code)) {?>
  <div>
    <span colspan="2"></span>
  </div>
  <div>
    <span valign="top" align="left"><?php echo JText::_("COM_OS_CCK_LABEL_VIDEO")?></span>
  </div>
  <?php
  for ($i = 0;$i < count($videos);$i++){?>
    <div>
      <span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_VIDEO_ATTRIBUTE").($i+1)?></span>
      <span>
        <?php
        if(isset($videos[$i]->src) && substr($videos[$i]->src, 0, 4) != "http"
          && empty($videos[$i]->youtube)){?>
            <input type="text" name="video<?php echo $i?>" id="video<?php echo $i?>"
                    size="60" value="<?php echo JURI::root().$videos[$i]->src?>"
                    readonly="readonly"/>
            <?php
          }else{?>
            <input type="text" name="video_url<?php echo $i?>" id="video_url<?php echo $i?>"
                    size="60" value="<?php echo $videos[$i]->src?>"
                    readonly="readonly"/>
            <?php
          }?>
      </span>
    </div>
    <div>
      <label><span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_VIDEO_DELETE")?></span>
      <span>
        <?php
        if(isset($videos[$i]->id)){?>
          <input type="checkbox" name="video_option_del<?php echo $videos[$i]->id?>"
                  value="<?php echo $videos[$i]->id?>">
          <?php
        }?>
      </span></label>
    </div>
    <?php
  }
} else if (!empty($youtube->code)){?>
  <div>
    <span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_VIDEO_ATTRIBUTE")?></span>
    <span>
      <input type="text" name="youtube_code<?php echo $youtube->id?>"
              id="youtube_code<?php echo $youtube->id?>"
              size="60" value="<?php echo $youtube->code?>"/>
    </span>
  </div>
  <div>
    <label><span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_VIDEO_DELETE")?></span>
    <span>
      <input type="checkbox"
            name="youtube_option_del<?php echo $youtube->id?>"
            value="<?php echo $youtube->id?>">
    </span></label>
  </div>
  <?php
}

if(empty($youtube->code) && count($videos) < 5){?>
  <div>
    <span id="v_items">
      <input <?php echo $layout_params['field_styling']?> class="<?php echo $layout_params['custom_class'].$required?>"
          id="v_add" type="button"
          name="new_video"
          value="<?php echo JText::_("COM_OS_CCK_LABEL_ADD_NEW_VIDEO_FILE")?>"
          onClick="new_videos()"/>
    </span>
  </div>
  <?php 
}

if (count($tracks) > 0) {?>
  <div>
    <span valign="top" align="left"><?php echo JText::_("COM_OS_CCK_LABEL_TRACK")?></span>
  </div>
  <?php
  for ($i = 0;$i < count($tracks);$i++) {?>
    <div>
      <span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_TRACK_UPLOAD_URL").($i+1)?></span>
      <span>
        <?php
        if (isset($tracks[$i]->src) && substr($tracks[$i]->src, 0, 4) != "http"){?>
          <input type="text" class="trackitems" size="60"
                value="<?php echo JURI::root().$tracks[$i]->src?>" readonly="readonly"/>
          <?php
        }else{?>
          <input type="text" class="trackitems" size="60"
                value="<?php echo $tracks[$i]->src?>" readonly="readonly"/>
          <?php
        }
        if (!empty($tracks[$i]->kind)){?>
          <input class="trackitems" type="text" size="60"
                  value="<?php echo $tracks[$i]->kind?>" readonly="readonly"/>
          <?php
        }
        if (!empty($tracks[$i]->scrlang)){?>
          <input class="trackitems" type="text" size="60"
                value="<?php echo $tracks[$i]->scrlang?>" readonly="readonly"/>
          <?php
        }
        if (!empty($tracks[$i]->label)){?>
          <input class="trackitems" type="text" size="60"
                value="<?php echo $tracks[$i]->label?>" readonly="readonly"/>
          <?php
        }?>
      </span>'.
    </div>
    <div>
      <label><span align="right"><?php echo JText::_("COM_OS_CCK_LABEL_TRACK_DELETE")?></span>
      <span>
        <?php
        if(isset($tracks[$i]->id)){?>
          <input type="checkbox" name="track_option_del<?php echo $tracks[$i]->id?>" 
                  value="<?php echo $tracks[$i]->id?>">
          <?php
        }?>
      </span></label>
    </div>
    <?php
  }
}
?><span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> ><?php

if($field_from_params[$fName.'_add_track']){?>
  <div>
    <span id="t_items">
      <input <?php echo $layout_params["field_styling"]?>
          class="<?php echo $layout_params['custom_class'].$required?>"
          id="t_add"
          type="button"
          name="new_track"
          value="<?php echo JText::_("COM_OS_CCK_LABEL_ADD_NEW_TRACK")?>"
          onClick="new_tracks()"/>
    </span>
  </div>
  <?php
}?></span>
