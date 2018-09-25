<?php 
defined('_JEXEC') or die('Restricted access');
/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
 ?>
<!-- Layout Modal -->
<div class="modal fade" id="location-field-modal" tabindex="-1" role="dialog" aria-labelledby="location-field-modalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="location-field-modalLabel">Location Settings.</h4>
      </div>
      <div class="modal-body">
        <div class="location-modal-address-block">
          <div class="modal-address-block address">
            <label>Address,{address}</label>
            <input id="location-modal-address" type="text" size="4" value="">
          </div>
          <div class="modal-address-block country">
            <label>Country,{country}</label>
            <input id="location-modal-country" type="text" size="4" value="">
          </div>
          <div class="modal-address-block region">
            <label>Region,{region}</label>
            <input id="location-modal-region" type="text" size="4" value="">
          </div>
          <div class="modal-address-block city">
            <label>City,{city}</label>
            <input id="location-modal-city" type="text" size="4" value="">
          </div>
          <div class="modal-address-block index">
            <label>Zip code,{zip}</label>
            <input id="location-modal-zip-code" type="text" size="4" value="">
          </div>
          <div class="modal-code-block code">
            <label>Display HTML</label>
            <textarea id="location-modal-code-editor"></textarea>
          </div>
          <input id="location-modal-latitude" type="hidden" value="">
          <input id="location-modal-longitude" type="hidden" value="">
          <input id="location-modal-zoom" type="hidden" value="">
        </div>
        <div class="location-modal-map-block">
        <div class="modal-map-block">
          <label>Map type</label>
          <select id="location-map-type">
            <option value="ROADMAP">Roadmap</option>
            <option value="SATELLITE">Satellite</option>
            <option value="HYBRID">Hybrid</option>
            <option value="TERRAIN">Terrain</option>
          </select>
        </div>
        <div class="location-modal-as-show-block">
          <label>Display as show field</label>
          <span class="location-option-block">
            <div class="location-option">
              <input id="location-modal-as-show1" name="location-modal-as-show" class="as-show"  type="radio" value="1">Yes
            </div>
            <div class="location-option">
              <input id="location-modal-as-show2" name="location-modal-as-show" class="as-show"  type="radio" value="0">No
            </div>
          </span>
        </div>

        <div class="location-modal-hide-adress-block">
          <label>Hide address block</label>
          <span class="location-option-block">
            <div class="location-option">
              <input id="location-modal-hide-address1" name="location-modal-hide-address" class="as-show"  type="radio" value="1">Yes
            </div>
            <div class="location-option">
              <input id="location-modal-hide-address2" name="location-modal-hide-address" class="as-show"  type="radio" value="0">No
            </div>
          </span>
        </div>

        <div class="location-modal-hide-map-block">
          <label>Hide map block</label>
          <span class="location-option-block">
            <div class="location-option">
              <input id="location-modal-hide-map1" name="location-modal-hide-map" class="as-show"  type="radio" value="1">Yes
            </div>
            <div class="location-option">
              <input id="location-modal-hide-map2" name="location-modal-hide-map" class="as-show"  type="radio" value="0">No
            </div>
          </span>
        </div>
          <input type="button" onclick="setupModalLocation()" value="Find Location">
          <div id="location-modal-map"></div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="close-add-field-modal btn btn-primary" onclick="saveLocationModalSave()">Save</button>
      <button type="button" class="close-add-field-modal btn btn-primary" onclick="saveLocationModalClose()">Cancel</button>
    </div>
  </div>
</div>
<!-- Layout Modal -->