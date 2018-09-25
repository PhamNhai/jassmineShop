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

$video_with = (isset($layout_params["fields"][$fName]["options"]["width"]))?$layout_params["fields"][$fName]["options"]["width"]:'420';
$video_height = (isset($layout_params["fields"][$fName]["options"]["height"]))?$layout_params["fields"][$fName]["options"]["height"]:'315';
$youtubeCode = "";
$videoSrc = array();
$videoSrcHttp = "";
$videoType = array();
$videos = isset($value[0])?$value[0]:[];
foreach($videos as $video){
  if ($video->youtube){
    $youtubeCode = $video->youtube;
  }else if($video->src){
    $videoSrc[] = $video->src;
    if($video->type)
      $videoType[] = $video->type;
  }
}
if(count($videoSrc)){
  if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
      $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
      $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
  }
}
?>

<span <?php if(isset($layout_params['fields']['description_'.$fName]) && $layout_params['fields']['description_'.$fName]=='on' && !empty($layout_params['fields'][$fName.'_tooltip']))
        {?>
rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $layout_params['fields'][$fName.'_tooltip'];?>"
    <?php } ?> >
  <?php
  if(!empty($layout_params['fields'][$fName.'_prefix'])){
    echo '<span class="cck-prefix">'.$layout_params['fields'][$fName.'_prefix'].'</span>';
  }?>

  <video width="<?php echo $video_with?>" height="<?php echo $video_height?>" controls>
    <?php
    for ($j = 0;$j < count($videoSrc);$j++) {
        if(!strstr($videoSrc[$j], "http") && $videoType) {
          echo '<source src="'.JURI::root().$videoSrc[$j].'" type="'.$videoType[$j].'">';
        }else{
          echo '<source src="'.$videoSrc[$j].'" type="'.$videoType[$j].'">';
       }
    }
    if (isset($value[1]) && !empty($value[1])) {
      $tracks = $value[1];
      for ($j = 0;$j < count($tracks);$j++) {
        ($j==0)?$default='default="default"':$default='';
        if (!strstr($tracks[$j]->src, "http")) {
          $html .= '<track src="' . JURI::root().$tracks[$j]->src . '"'.
                  ' kind="' . $tracks[$j]->kind .'"'.
                  ' srclang="' . $tracks[$j]->scrlang .'"'.
                  ' label="' . $tracks[$j]->label . '" '.$default.'>';
        }else{
          $html .= '<track src="' .$src . '"'.
                  ' kind="' . $tracks[$j]->kind .'"'.
                  ' srclang="' . $tracks[$j]->scrlang .'"'.
                  ' label="'.$tracks[$j]->label.'" '.$default.'>';
        }
      }
    }?>
  </video>

  <?php
  if(!empty($layout_params['fields'][$fName.'_suffix'])){
    echo '<span class="cck-suffix">'.$layout_params['fields'][$fName.'_suffix'].'</span>';
  }?>
</span>