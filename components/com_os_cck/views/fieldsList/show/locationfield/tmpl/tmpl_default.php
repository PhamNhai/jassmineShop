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
$field_id = $field->fid;
$zoom = '';
$required = '';

$field_from_params = $layout_params["fields"];
if(isset($field_from_params[$fName.'_required']) && $field_from_params[$fName.'_required']=='on')
    $required = ' required ';
$latitude = isset($value[0]->vlat)?$value[0]->vlat:$field_from_params[$fName.'_map_latitude'];
$longitude = isset($value[0]->vlong)?$value[0]->vlong:$field_from_params[$fName.'_map_longitude'];
$maptype  = $field_from_params[$fName.'_map_type'];
$adress = $field_from_params[$fName.'_map_address'];
$width = isset($field_from_params[$fName]["options"]["width"])?$field_from_params[$fName]["options"]["width"]:100;
$height = isset($field_from_params[$fName]["options"]["height"])?$field_from_params[$fName]["options"]["height"]:300;
if(isset($moduleId) && $moduleId)$field_id.=$moduleId;
isset($instance->eiid)?$field_id.=$instance->eiid:'';
if(isset($field_from_params[$fName.'_map_zoom']) 
    && !empty($field_from_params[$fName.'_map_zoom'])){
    $zoom = $field_from_params[$fName.'_map_zoom'];
}else{
    $zoom = "3";
}
if(!isset($layout_params['field_styling']))$layout_params['field_styling'] = '';
if(!isset($layout_params['custom_class']))$layout_params['custom_class'] = '';
$mapOptions = '';



$mapOptions='scrollwheel: false,'.
            'navigationControl: false,'.
            'mapTypeControl: false,'.
            'scaleControl: false,'.
            'disableDoubleClickZoom: true,'.
            'draggable: false,';
?>

<div <?php echo $layout_params['field_styling']?> class="<?php echo $layout_params['custom_class']?>">
  <script>
    // if(typeof(bitches) !== 'undefined'){
    //   bitches.push({vlat: '<?php echo $latitude;?>',
    //                vlong: '<?php echo $longitude;?>',
    //                adress: '<?php echo $value[0]->address?addslashes($value[0]->address) : ltrim($field_from_params[$fName.'_map_address']); ?>'});
    // }
  </script>
  <?php
  if($field_from_params[$fName.'_map_hide_map'] == 'false'){?>
    <script type="text/javascript">
        function initialize_<?php echo $field_id?>() {
            var lastmarker = null;
            var mouse = true;
            var draggable = true;
            var image = {url: '<?php echo JURI::root()?>/components/com_os_cck/images/marker.png'};
            //   var marker = null;
            var myLatlng = new google.maps.LatLng(<?php echo $latitude?$latitude:0;echo",";echo $longitude?$longitude:0; ?>);
            var mapOptions = {
              <?php echo $mapOptions?>
                center: myLatlng,
                zoom: <?php echo $zoom; ?>,
                mapTypeId: google.maps.MapTypeId.<?php echo $maptype ?>
            };
            var map = new google.maps.Map(document.getElementById("map_canvas<?php echo $field_id?>"),mapOptions);
            var bounds = new google.maps.LatLngBounds();
            var marker = new google.maps.Marker({
                icon: image,
                animation: google.maps.Animation.DROP,
                position: myLatlng,
                map: map,
                title: "<?php echo $adress; ?>"
            });

            google.maps.event.addListener(marker, 'click', toggleBounce);
            google.maps.event.addListener(marker, "dragend", function () {
                updateCoordinates(marker.getPosition());
            });

            function updateCoordinates(latlng) {
                if (latlng) {
                    document.getElementById("<?php echo $fName?>_map_latitude").value = latlng.lat();
                    document.getElementById("<?php echo $fName?>_map_longitude").value = latlng.lng();
                }
            }

            function toggleBounce() {
              if (marker.getAnimation() != null) {
                marker.setAnimation(null);
              } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
              }
            }

            function codeAddress() {
                var geocoder = new google.maps.Geocoder();
                myOptions = {
                    zoom: <?php echo $zoom; ?>,
                    scrollwheel: false,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.LARGE
                    },
                    mapTypeId: google.maps.MapTypeId.<?php echo $maptype ?>
                }
                map = new google.maps.Map(document.getElementById("map_canvas<?php echo $field_id?>"), myOptions);
                var address = document.getElementById("<?php echo $fName?>_map_address").value +
                       " " + document.getElementById("<?php echo $fName?>_map_country").value +
                       " " + document.getElementById("<?php echo $fName?>_map_region").value +
                       " " + document.getElementById("<?php echo $fName?>_map_city").value +
                       " " + document.getElementById("<?php echo $fName?>_map_zip_code").value;
                if(typeof(bitches) !== 'undefined'){
                  bitches.push({vlat: '<?php echo $latitude;?>',
                               vlong: '<?php echo $longitude;?>',
                               adress:address});
                }
                geocoder.geocode({
                    'address': address
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        updateCoordinates(results[0].geometry.location);
                        if (marker) marker.setMap(null);
                        marker = new google.maps.Marker({
                            map: map,
                            icon: image,
                            animation: google.maps.Animation.DROP,
                            position: results[0].geometry.location,
                        });
                        google.maps.event.addListener(marker, 'click', toggleBounce);
                        google.maps.event.addListener(marker, "dragend", function () {
                            updateCoordinates(marker.getPosition());
                        });
                    } else {
                        alert("<?php echo JText::_('COM_OS_CCK_ERROR_FIND_LOCATION')?>");
                    }
                });
            }
            initialize_<?php echo $field_id?>.codeAddress = codeAddress;
        } // end of intialize
        google.maps.event.addDomListener(window, 'load', initialize_<?php echo $field_id?>);
      </script>
      <?php
    }?>
    <input id="<?php echo $fName?>_map_latitude" type="hidden" value="<?php echo $latitude?>" name="fi_<?php echo $fName?>_map_latitude">
    <input id="<?php echo $fName?>_map_longitude" type="hidden" value="<?php echo $longitude?>" name="fi_<?php echo $fName?>_map_longitude">
    <input id="<?php echo $fName?>_map_zoom" type="hidden" value="<?php echo $zoom?>" name="fi_<?php echo $fName?>_map_zoom">
    <?php
    if($field_from_params[$fName.'_map_hide_address'] == 'false'){
      if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
          $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
          $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
      }
      $addressBlock = $field_from_params[$fName.'_map_code'];
      $mapCode = $value[0]->address?$value[0]->address : ltrim($field_from_params[$fName.'_map_address']);
      $addressBlock = str_replace('{address}', $mapCode, $addressBlock);

      $mapCode = $value[0]->country?$value[0]->country : ltrim($field_from_params[$fName.'_map_country']);
      $addressBlock = str_replace('{country}', $mapCode, $addressBlock);

      $mapCode = $value[0]->region?$value[0]->region : ltrim($field_from_params[$fName.'_map_region']);
      $addressBlock = str_replace('{region}', $mapCode, $addressBlock);

      $mapCode = $value[0]->city?$value[0]->city : ltrim($field_from_params[$fName.'_map_city']);
      $addressBlock = str_replace('{city}', $mapCode, $addressBlock);

      $mapCode = $value[0]->zipcode?$value[0]->zipcode : ltrim($field_from_params[$fName.'_map_zip_code']);
      $addressBlock = str_replace('{zip}', $mapCode, $addressBlock);

      echo $addressBlock;
    }
    if($field_from_params[$fName.'_map_hide_map'] == 'false'){
      if(isset($layout_params['fields']['showName_'.$field->db_field_name]) &&
          $layout_params['fields']['showName_'.$field->db_field_name] == 'on'){
          $layout_html = str_replace($field->db_field_name.'-label-hidden', '', $layout_html);
      }
      ?>
      <div style="width:<?php echo $width?>%;height:<?php echo $height?>px;" id="map_canvas<?php echo $field_id ?>" style="width:100%;height:300px;"></div>
      <?php
    }?>
</div>

