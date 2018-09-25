/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

var ba_jQuery = jQuery,
    mapStyles = {
    'standart' : [],
    'silver' : [
        {
            "elementType": "geometry",
            "stylers": [{
                "color": "#f5f5f5"
            }]
        },
        {
            "elementType": "labels.icon",
            "stylers": [{
                "visibility": "off"
            }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#616161"
            }]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#f5f5f5"
            }]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#bdbdbd"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [{
                "color": "#eeeeee"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [{
                "color": "#e5e5e5"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#9e9e9e"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [{
                "color": "#ffffff"
            }]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [{
                "color": "#dadada"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#616161"
            }]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#9e9e9e"
            }]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [{
                "color": "#e5e5e5"
            }]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [{
                "color": "#eeeeee"
            }]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{
                "color": "#c9c9c9"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#9e9e9e"
            }]
        }
    ],
    'retro' : [
        {
            "elementType": "geometry",
            "stylers": [{
                "color": "#ebe3cd"
            }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#523735"
            }]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#f5f1e6"
            }]
        },
        {
            "featureType": "administrative",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#c9b2a6"
            }]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#dcd2be"
            }]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#ae9e90"
            }]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [{
                "color": "#dfd2ae"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [{
                "color": "#dfd2ae"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#93817c"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [{
                "color": "#a5b076"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#447530"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [{
                "color": "#f5f1e6"
            }]
        },
        {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [{
                "color": "#fdfcf8"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [{
                "color": "#f8c967"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#e9bc62"
            }]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry",
            "stylers": [{
                "color": "#e98d58"
            }]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#db8555"
            }]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#806b63"
            }]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [{
                "color": "#dfd2ae"
            }]
        },
        {
            "featureType": "transit.line",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#8f7d77"
            }]
        },
        {
            "featureType": "transit.line",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#ebe3cd"
            }]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [{
                "color": "#dfd2ae"
            }]
        },
        {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [{
                "color": "#b9d3c2"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#92998d"
            }]
        }
    ],
    'dark' : [
        {
            "elementType": "geometry",
            "stylers": [{
                "color": "#212121"
            }]
        },
        {
            "elementType": "labels.icon",
            "stylers": [{
                "visibility": "off"
            }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#212121"
            }]
        },
        {
            "featureType": "administrative",
            "elementType": "geometry",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "featureType": "administrative.country",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#9e9e9e"
            }]
        },
        {
            "featureType": "administrative.land_parcel",
            "stylers": [{
                "visibility": "off"
            }]
        },
        {
            "featureType": "administrative.locality",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#bdbdbd"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [{
                "color": "#181818"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#616161"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#1b1b1b"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry.fill",
            "stylers": [{
                "color": "#2c2c2c"
            }]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#8a8a8a"
            }]
        },
        {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [{
                "color": "#373737"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [{
                "color": "#3c3c3c"
            }]
        },
        {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry",
            "stylers": [{
                "color": "#4e4e4e"
            }]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#616161"
            }]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#757575"
            }]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{
                "color": "#000000"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#3d3d3d"
            }]
        }
    ],
    'night' : [
        {
            "elementType": "geometry",
            "stylers": [{
                "color": "#242f3e"
            }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#746855"
            }]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#242f3e"
            }]
        },
        {
            "featureType": "administrative.locality",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#d59563"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#d59563"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [{
                "color": "#263c3f"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#6b9a76"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [{
                "color": "#38414e"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#212a37"
            }]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#9ca5b3"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [{
                "color": "#746855"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#1f2835"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#f3d19c"
            }]
        },
        {
            "featureType": "transit",
            "elementType": "geometry",
            "stylers": [{
                "color": "#2f3948"
            }]
        },
        {
            "featureType": "transit.station",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#d59563"
            }]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{
                "color": "#17263c"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#515c6d"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#17263c"
            }]
        }
    ],
    'aubergine' : [
        {
            "elementType": "geometry",
            "stylers": [{
                "color": "#1d2c4d"
            }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#8ec3b9"
            }]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#1a3646"
            }]
        },
        {
            "featureType": "administrative.country",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#4b6878"
            }]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#64779e"
            }]
        },
        {
            "featureType": "administrative.province",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#4b6878"
            }]
        },
        {
            "featureType": "landscape.man_made",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#334e87"
            }]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [{
                "color": "#023e58"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [{
                "color": "#283d6a"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#6f9ba5"
            }]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#1d2c4d"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [{
                "color": "#023e58"
            }]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#3C7680"
            }]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [{
                "color": "#304a7d"
            }]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#98a5be"
            }]
        },
        {
            "featureType": "road",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#1d2c4d"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [{
                "color": "#2c6675"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [{
                "color": "#255763"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#b0d5ce"
            }]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#023e58"
            }]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#98a5be"
            }]
        },
        {
            "featureType": "transit",
            "elementType": "labels.text.stroke",
            "stylers": [{
                "color": "#1d2c4d"
            }]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry.fill",
            "stylers": [{
                "color": "#283d6a"
            }]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [{
                "color": "#3a4762"
            }]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{
                "color": "#0e1626"
            }]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [{
                "color": "#4e6d70"
            }]
        }
    ]
};

document.addEventListener('DOMContentLoaded', function(){
    ba_jQuery('.com-baforms').each(function(){
        var baForm = ba_jQuery(this),
            saveModal = baForm.find('.save-and-continue-modal'),
            messageModal = ba_jQuery(this).find('.message-modal'),
            form = baForm.find('form'),
            files,
            popupModal = baForm.find('.ba-modal.popup-form'),
            iframe,
            theme = baForm.find('.theme-color').val(),
            baTotal = baForm.find('.ba-total-price'),
            cart = baForm.find('.baforms-cart'),
            symbol = baForm.find('.cart-currency').val(),
            symbolPos = baForm.find('.cart-position').val(),
            saveContinue = baForm.find('.save-and-continue').val(),
            stripe;

        baForm.find('.ba-input-image').closest('.tool').addClass('ba-image-input');

        baForm.find('.ba-maxlength').each(function(){
            var $this = jQuery(this),
                input = $this.parent().find('input[type=text], textarea');
            input.on('input', function(){
                var length = this.value.replace(new RegExp('\n', 'g'), 'br').length,
                    str = length+'/'+this.maxLength;
                $this.text(str)
                if (length >= this.maxLength) {
                    $this.addClass('ba-maxlength-alert');
                } else {
                    $this.removeClass('ba-maxlength-alert');
                }
            })
        });

        baForm.find('.ba-tooltip').parent().on('mouseenter', function(){
            var coord = this.getBoundingClientRect(),
                top = coord.top,
                left = coord.left;
            left = left + (coord.right - coord.left) / 2;
            jQuery(this).find('.ba-tooltip').css({
                'top' : top+'px',
                'left' : left+'px'
            })
        });

        baForm.find('.ba-chekInline span.ba-input-image').on('click', function(){
            var checkbox = jQuery(this).find('input');
            if (!checkbox.prop('checked')) {
                checkbox.prop('checked', true);
                jQuery(this).css('border', '2px solid '+theme);
            } else {
                checkbox.prop('checked', false);
                jQuery(this).css('border', '');
            }
            checkbox.trigger('change');
        });
        baForm.find('.ba-radioInline > span.ba-input-image').on('click', function(){
            var checkbox = jQuery(this).find('input');
            if (!checkbox.prop('checked')) {
                checkbox.prop('checked', true);
                checkbox.trigger('change');
                jQuery(this).css('border', '2px solid '+theme);
            }
        });
        baForm.find('.ba-radioInline.ba-image-input > span input').on('change', function(){
            jQuery(this).closest('.tool').find('> span.ba-input-image').css('border', '');
        })

        baForm.find('input[data-type="calculation"]').on('keyup', function(){
            if (baTotal.length == 0) {
                return;
            }
            var price = ba_jQuery(this).val() * 1,
                name = ba_jQuery(this).attr('name'),
                total = baTotal.find('.ba-price').text()*1,
                label = '';
            if (ba_jQuery(this).closest('.tool').find('> label').length > 0){
                label = ba_jQuery(this).closest('.tool').find('> label > span')[0].innerText;
                label = label.replace(' *', '');
            }
            if (isNaN(price)) {
                ba_jQuery(this).addClass('ba-alert');
                if (prices[name]) {
                    var quantity = 1
                    if (cart.length > 0) {
                        if (cart.find('.product-cell[data-id="'+name+'"]').length > 0) {
                            quantity = Math.round(cart.find('.product-cell[data-id="'+name+'"] .quantity input').val());
                            cart.find('.product-cell[data-id="'+name+'"]').remove();
                        }
                    }
                    total = total - prices[name]  * quantity;
                    prices[name] = 0;
                    total = Math.round10(total, -2);
                    baTotal.find('.ba-price').text(total);
                    baTotal.find('input[name="ba_total"]').val(total);
                }                
                return false;
            }
            ba_jQuery(this).removeClass('ba-alert');
            if (price > 999999999) {
                price = 999999999;
                jQuery(this).val(price)
            }
            if (!label) {
                label = ba_jQuery(this).attr('placeholder');
            }
            if (prices[name]) {
                var quantity = 1
                if (cart.length > 0) {
                    quantity = Math.round(cart.find('.product-cell[data-id="'+name+'"] .quantity input').val());
                    cart.find('.product-cell[data-id="'+name+'"]').remove();
                }
                total = total - prices[name]  * quantity;
            }
            var value;
            if (symbolPos == 'before') {
                value = symbol+price;
            } else {
                value = price+symbol
            }
            addToCart(label, value, name, price);
            if (price == 0) {
                cart.find('.product-cell[data-id="'+name+'"]').remove();
            }
            cart.find('.product-cell[data-id="'+name+'"] .remove-item i').on('click', function(){
                
            });
            prices[name] = price * 1;
            total = total + price * 1;
            total = Math.round10(total, -2);
            baTotal.find('.ba-price').text(total);
            baTotal.find('input[name="ba_total"]').val(total);
        });

        baForm.find('input').on('keydown', function(event){
            if (event.keyCode == 13) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        baForm.find('.ba-address').each(function(){
            var input = jQuery(this).find('input[type="text"]'),
                autocomplete = new google.maps.places.Autocomplete(input[0]);
        });

        function conditionShow()
        {
            baForm.find('.ba-dropdown > select, .ba-dropdown > .container-icon select').on('change', function(){
                var parent = jQuery(this).closest('.ba-dropdown'),
                    height = 20;                
                if (parent.find('> .condition-area').length == 0) {
                    return;
                }
                jQuery(this).find('option').each(function(ind, el){
                    if (jQuery(this).prop('selected')) {
                        var h,
                            flag = false;
                        if (ind > 0) {
                            ind = ind - 1;
                            flag = true;
                        }
                        parent.parentsUntil('.ba-row').each(function(){
                            this.style.height = '';
                        });
                        if (parent.find('> .condition-area.selected').length > 0) {
                            parent.find('> .condition-area.selected').addClass('close-condition');
                            height = parent.find('.close-condition').height();
                            parent.find(' > [data-condition="'+ind+'"]').height(height);
                            parent.find('> .condition-area').removeClass('selected');
                            parent.find('> .condition-area .condition-area').removeClass('selected');
                        } else {
                            parent.find(' > [data-condition="'+ind+'"]').height(0);
                        }
                        if (flag && parent.find(' > [data-condition="'+ind+'"]').length > 0) {
                            parent.find(' > [data-condition="'+ind+'"]').addClass('selected');
                            parent.find(' > [data-condition="'+ind+'"]')[0].style.height = '';
                            h = parent.find(' > [data-condition="'+ind+'"]').height();
                            parent.find(' > [data-condition="'+ind+'"]').animate({
                                'height' : h * 1 + 3
                            }, 600, function(){
                                parent.find('.close-condition').removeClass('close-condition');
                                var top = parent.find(' > [data-condition="'+ind+'"]').position().top;
                                parent.find(' > [data-condition="'+ind+'"]').css('top', top+'px');
                                parent.closest('.ba-form')[0].style.height = '';
                                height = parent.closest('.ba-form').height();
                                parent.closest('.ba-form').height(height);
                            })
                            parent.closest('.ba-form')[0].style.height = '';
                        }
                        if (!flag || parent.find(' > [data-condition="'+ind+'"]').length == 0) {
                            parent.css('margin-bottom', height+'px');
                            parent.animate({
                                'margin-bottom' : 20
                            }, 600, function(){
                                parent.find('.close-condition').removeClass('close-condition');
                                parent.closest('.ba-form')[0].style.height = '';
                                height = parent.closest('.ba-form').height();
                                parent.closest('.ba-form').height(height);
                            });
                            parent.closest('.ba-form')[0].style.height = '';
                        }
                        var item = parent.find('> .condition-area');
                        clearCondition(item);
                        return false;
                    }
                });
                refreshMap();
            });
            baForm.find('.ba-radioInline > span input, .ba-radioMultiple > span input').on('change', function(){
                var parent = jQuery(this).closest('.tool'),
                    height = 20,
                    $this = this;
                if (parent.find('> .condition-area').length == 0) {
                    return;
                }
                parent.find(' > span input').each(function(ind){
                    if (this == $this) {
                        var conditionArea = parent.find(' > [data-condition="'+ind+'"]'),
                        	h,
                        	conditions = parent.find('> .condition-area.selected');
                        parent.parentsUntil('.ba-row').each(function(){
                            this.style.height = '';
                        });
                        conditionArea.addClass('selected');
                        h = conditionArea.height();
                        if (conditions.length > 0) {
                            conditions.addClass('close-condition');
                            height = parent.find('.close-condition').height();
                            conditionArea.height(height);
                            conditions.removeClass('selected');
                            parent.find('> .condition-area .condition-area').removeClass('selected');
                        } else {
                            conditionArea.height(0);
                        }
                        conditionArea.animate({
                            'height' : h * 1 + 3
                        }, 600, function(){
                            parent.find('.close-condition').removeClass('close-condition');
                            var top = conditionArea.position().top;
                            conditionArea.css('top', top+'px');
                            parent.closest('.ba-form')[0].style.height = '';
                            height = parent.closest('.ba-form').height();
                            parent.closest('.ba-form').height(height);
                        })
                        parent.closest('.ba-form')[0].style.height = '';
                        if (conditionArea.length == 0) {
                            parent.css('margin-bottom', height+'px');
                            parent.animate({
                                'margin-bottom' : 20
                            }, 600, function(){
                                parent.find('.close-condition').removeClass('close-condition');
                                parent.closest('.ba-form')[0].style.height = '';
                                height = parent.closest('.ba-form').height();
                                parent.closest('.ba-form').height(height);
                            })
                        }
                        var item = parent.find('> .condition-area');
                        clearCondition(item);
                        return false;
                    }
                });
                refreshMap();
            });
        }

        function clearCondition(item)
        {
            var total = baTotal.find('input[name="ba_total"]').val(),
                totalPrice = 0;
            item.find('span.ba-input-image').css('border', '');
            item.find('.ba-alert').removeClass('ba-alert');
            item.find('input').each(function(){
                var type = ba_jQuery(this).attr('type');
                if (type == 'radio' || type == 'checkbox') {
                    if (ba_jQuery(this).prop('checked') && ba_jQuery(this).attr('data-price')) {
                        var quantity = 1,
                            name = this.name;
                        if (cart.find('.product-cell[data-id="'+name+'"]').length > 0) {
                            quantity = cart.find('.product-cell[data-id="'+name+'"] .quantity input').val();
                        }
                        totalPrice += ba_jQuery(this).attr('data-price') * quantity;                        
                        cart.find('.product-cell[data-id="'+name+'"]').find('.remove-item i.zmdi').trigger('click');
                        if (prices[name]) {
                            delete(prices[name]);
                        }
                    }
                    ba_jQuery(this).prop('checked', false);
                }
                if (type == 'email') {
                    ba_jQuery(this).val('');
                }
                if (ba_jQuery(this).parent().hasClass('ba-textInput') || ba_jQuery(this).parent().parent().hasClass('ba-textInput')) {
                    ba_jQuery(this).val('');
                }
            });
            item.find('textarea').each(function () {
                ba_jQuery(this).val('');
            });
            item.find('select').each(function(){
                ba_jQuery(this).find('option').each(function(){
                    if (ba_jQuery(this).prop('selected') && ba_jQuery(this).attr('data-price')) {
                        var quantity = 1,
                            name = jQuery(this).parent()[0].name.replace('[]', '');
                        if (cart.find('.product-cell[data-id="'+name+'"]').length > 0) {
                            quantity = cart.find('.product-cell[data-id="'+name+'"] .quantity input').val();
                        }
                        totalPrice += ba_jQuery(this).attr('data-price') * quantity;
                        cart.find('.product-cell[data-id="'+name+'"]').find('.remove-item i.zmdi').trigger('click');
                        if (prices[name]) {
                            delete(prices[name]);
                        }
                    }
                    ba_jQuery(this).removeAttr('selected');
                });
            });
            total = total - totalPrice;
            baTotal.find('input[name="ba_total"]').val(total);
            baTotal.find('.ba-price').text(total);
        }
        
        function refreshMap()
        {
            ba_jQuery('.ba-map.tool').each(function(){
                var options = ba_jQuery(this).parent().find('.ba-options').val(),
                    zoom = true,
                    draggable = true,
                    image;
                options = options.replace('-_-', "'");
                options = options.split(';');
                if (options[8] == 0) {
                    zoom = false;
                }
                if (options[9] == 0) {
                    draggable = false;
                }
                if (!options[10]) {
                    options[10] = 'standart';
                }
                if (options[0] != '') {
                    options[0] = options[0].replace(new RegExp('\\n', 'g'), '\\n');
                    var option = JSON.parse(options[0]);
                    if (typeof(option.center) == 'string') {
                        option.center = option.center.split(',');
                        option.center = {
                            lat : option.center[0]*1,
                            lng : option.center[1]*1
                        }
                    }
                    if (options[6] == 1) {
                        option.scrollwheel = zoom;
                        option.navigationControl = true;
                        option.mapTypeControl = true;
                        option.scaleControl = true;
                        option.draggable = draggable;
                        option.zoomControl = true;
                        option.disableDefaultUI = false;
                        option.disableDoubleClickZoom = false;
                    }
                } else {
                    if (options[6] == 0) {
                        option = {
                            center : {
                                lat : 42.345573,
                                lng : -71.098326
                            },
                            zoom: 14,
                            scrollwheel: zoom,
                            navigationControl: false,
                            mapTypeControl: false,
                            scaleControl: false,
                            draggable: draggable,
                            zoomControl: false,
                            disableDefaultUI: true,
                            disableDoubleClickZoom: true,
                        }
                    } else {
                        option = {
                            center : {
                                lat : 42.345573,
                                lng : -71.098326
                            },
                            zoom: 14,
                            scrollwheel: zoom,
                            navigationControl: true,
                            mapTypeControl: true,
                            scaleControl: true,
                            draggable: draggable,
                            zoomControl: true,
                            disableDefaultUI: false,
                            disableDoubleClickZoom: false,
                        }
                    }
                }
                if (options[7] != '') {
                    image = ba_jQuery('.admin-dirrectory').val()+options[7];
                } else {
                    image = options[7];
                }
                var content = options[2],
                    flag = options[5],
                    map = new google.maps.Map(jQuery(this)[0], option),
                    marker = '';
                map.setOptions({styles: mapStyles[options[10]]});
                if (options[1] != '') {
                    options[1] = options[1].replace(new RegExp('\\n', 'g'),'\\n');
                    var mark = JSON.parse(options[1]);
                    var keys = [];
                    for (var key in mark) {
                        keys.push(key);
                    }
                    marker = new google.maps.Marker({
                        position: {
                            lat : mark[keys[0]]*1,
                            lng : mark[keys[1]]*1
                        },
                        map: map,
                        icon : image
                    });
                    if (content != '') {
                        content = content.replace(new RegExp('---', 'g'), ';')
                        var infowindow = new google.maps.InfoWindow({
                            content : content
                        });
                        if (flag == 1) {
                            infowindow.open(map, marker);
                        }
                        marker.addListener('click', function(event){
                            infowindow.open(map, marker);
                        });
                    }
                }
            });
        }
        
        function prepareUpload(event)
        {
            ba_jQuery(this).parent().find('.upl-error').val('');
            ba_jQuery(this).removeClass('ba-alert');
            files = event.target.files;
            if (files.length != 0) {
                var size = ba_jQuery(this).parent().find('.upl-size').val(),
                    types = ba_jQuery(this).parent().find('.upl-type').val(),
                    len = files.length,
                    tLen;
                types = types.split(',');
                size = 1048576 * size;
                tLen = types.length;
                for (var i = 0; i < len; i++) {
                    if (files[i].size < size) {
                        var type = files[i].name.split('.'),
                            flag = true;
                        type = type[type.length-1].toLowerCase()
                        for (var j = 0; j < tLen; j++) {
                            if (type != ba_jQuery.trim(types[j].toLowerCase())) {
                                flag = false;
                            } else {
                                flag = true;
                                break;
                            }
                        }
                        if (!flag) {
                            ba_jQuery(this).addClass('ba-alert');
                            ba_jQuery(this).parent().find('.upl-error').val('error');
                            break;
                        }
                    }  else {
                        ba_jQuery(this).addClass('ba-alert');
                        ba_jQuery(this).parent().find('.upl-error').val('error');
                        break;
                    }
                }
            } else {
                if (ba_jQuery(this).prop('required')) {
                    ba_jQuery(this).parent().find('.upl-error').val('error');
                    ba_jQuery(this).addClass('ba-alert');
                } else {
                    ba_jQuery(this).parent().find('.upl-error').val('');
                    ba_jQuery(this).removeClass('ba-alert');
                }                
            }
        }

        function checkAlert(item)
        {
            item.find('.tool').each(function() {
                var tool = ba_jQuery(this),
                    condArea = jQuery(this).closest('.condition-area');
                if (condArea.length > 0 && !condArea.hasClass('selected')) {
                    return;
                }
                if (tool.hasClass('ba-email')) {
                    var reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/,
                        value = tool.find('input[type="email"]').first().val();
                    if(!reg.test(value)) {
                        tool.find('input[type="email"]').first().addClass('ba-alert');
                    } else {
                        tool.find('input[type="email"]').first().removeClass('ba-alert');
                    }
                } else if (tool.hasClass('ba-terms-conditions')) {
                    if (tool.find('> span input').prop('checked')) {
                        tool.removeClass('ba-alert');
                    } else {
                        tool.addClass('ba-alert');
                    }
                } else if (tool.hasClass('confirm-email')) {
                    var reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/,
                        value = tool.find('input[type="email"]').val(),
                        email = tool.parent().find('input[type="email"]').first().val();
                    if (reg.test(value) && value && value == email) {
                        tool.find('input[type="email"]').first().removeClass('ba-alert');
                    } else {
                        tool.find('input[type="email"]').first().addClass('ba-alert');
                    }
                } else if (tool.hasClass('ba-upload')) {
                    var item = tool.find('.ba-upload')
                        required = item.prop('required'),
                        value = item.val();
                    if (tool.find('.upl-error').val() == 'error' || (required && item[0].files.length == 0)) {
                        item.addClass('ba-alert');
                    } else {
                        item.removeClass('ba-alert');
                    }
                } else if (tool.hasClass('ba-textarea')) {
                    var required = tool.find('textarea').prop('required'),
                        value = tool.find('textarea').val();
                    if (required) {
                        if(ba_jQuery.trim(value) == '') {
                            tool.find('textarea').addClass('ba-alert');
                        } else {
                            tool.find('textarea').removeClass('ba-alert');
                        }
                    }
                } else if (tool.hasClass('ba-textInput')) {
                    var required = tool.find('input[type="text"]').prop('required'),
                        type = tool.find('input[type="text"]').attr('data-type'),
                        value = tool.find('input[type="text"]').val();
                    if (required) {
                        if(ba_jQuery.trim(value) == '') {
                            tool.find('input[type="text"]').addClass('ba-alert');
                        } else {
                            if (type == 'number') {
                                if (ba_jQuery.isNumeric(value)) {
                                    tool.find('input[type="text"]').removeClass('ba-alert');
                                } else {
                                    tool.find('input[type="text"]').addClass('ba-alert');
                                }
                            } else {
                                tool.find('input[type="text"]').removeClass('ba-alert');
                            }
                        }
                    }
                } else if (tool.hasClass('ba-chekInline') || tool.hasClass('ba-checkMultiple')) {
                    var checkFlag = false;
                    if (tool.find('.required').length == 0) {
                        return;
                    }
                    tool.find('.required input[type="checkbox"]').each(function(){
                        if (ba_jQuery(this).prop('checked')) {
                            checkFlag = true;
                            return false;
                        }
                    });
                    if(!checkFlag) {
                        tool.addClass('ba-alert');
                    } else {
                        tool.removeClass('ba-alert');
                    }
                } else if (tool.hasClass('ba-radioInline') || tool.hasClass('ba-radioMultiple')) {
                    var checkFlag = false,
                        required = tool.find(' > span input[type="radio"]').first().prop('required');
                    if (required) {
                        tool.find(' > span input[type="radio"]').each(function(){
                            if (ba_jQuery(this).prop('checked')) {
                                checkFlag = true;
                                return false;
                            }
                        });
                        if(!checkFlag) {
                            tool.addClass('ba-alert');
                        } else {
                            tool.removeClass('ba-alert');
                        }
                    }                    
                } else if (tool.hasClass('ba-dropdown') || tool.hasClass('ba-selectMultiple')) {
                    var select = tool.find('> select'),
                        checkFlag = false,
                        required;
                    if (select.length == 0) {
                        select = tool.find('> .container-icon select');
                    }
                    required = select.prop('required');
                    if (required) {
                        select.find('option').each(function(){
                            if (ba_jQuery(this).prop('selected') && ba_jQuery(this).val()) {
                                checkFlag = true;
                                return false;
                            }
                        });
                        if(!checkFlag) {
                            select.addClass('ba-alert');
                        } else {
                            select.removeClass('ba-alert');
                        }
                    }
                } else if (tool.hasClass('ba-address')) {
                    var required = tool.find('input[type="text"]').prop('required'),
                        value = tool.find('input[type="text"]').val();
                    if (required) {
                        if(ba_jQuery.trim(value) == '') {
                            tool.find('input[type="text"]').addClass('ba-alert');
                        } else {
                            tool.find('input[type="text"]').removeClass('ba-alert');
                        }
                    }
                } else if (tool.hasClass('ba-date')) {
                    var required = tool.hasClass('required'),
                        value = tool.find('input[type="text"]').val();
                    if (required) {
                        if(ba_jQuery.trim(value) == '') {
                            tool.find('input[type="text"]').addClass('ba-alert');
                        } else {
                            tool.find('input[type="text"]').removeClass('ba-alert');
                        }
                    }
                }
            });
            if (item.find('.ba-captcha input[type="text"]').length != 0) {
                var captcha = item.find('.ba-captcha input[type="text"]').val();
                if (captcha == '') {
                    item.find('.ba-captcha input[type="text"]').addClass('ba-alert');
                } else {
                    item.find('.ba-captcha input[type="text"]').removeClass('ba-alert');
                }
            }
        }
        
        function sendMassage(event) 
        {
            checkAlert(form);
            if (form.find('.ba-alert').length > 0) {
                event.stopPropagation();
                event.preventDefault();
                var alert = form.find('.ba-alert').first();
                if (!alert.hasClass('tool')) {
                    alert = alert.closest('.tool');
                }
                var position = alert.offset().top
                ba_jQuery('html, body').animate({
                    scrollTop: position - 150
                }, 'slow');
            } else {
                if (cart.length > 0) {
                    var obj = {},
                        str = new Array(),
                        quantity = cart.find('.product-cell').first().find('.quantity').text();
                    cart.find('.product-cell').each(function(){
                        var id = jQuery(this).attr('data-id');
                        if (id) {
                            obj.id = id.replace('[]', '');
                            obj.product = jQuery(this).attr('data-product');
                            obj.quantity = Math.round(jQuery(this).find('.quantity input').val())
                            var real = obj.product.split(' - ');
                            real[0] += ' ('+jQuery.trim(quantity)+': '+obj.quantity+')';
                            real[1] = real[1].replace(symbol, '');
                            real[1] = obj.quantity * real[1];
                            if (symbolPos == 'before') {
                                real[1] = symbol + real[1];
                            } else {
                                real[1] = real[1] + symbol;
                            }
                            real = real.join(' - ');
                            obj.str = real;
                            str.push(JSON.stringify(obj));
                        }
                    });
                    str = str.join(';');
                    jQuery(this).find('.forms-cart').val(str)
                }
                jQuery(this).find('input[name="page_url"]').val(window.location.href);
                jQuery(this).find('input[name="page_title"]').val(jQuery('title')[0].innerText.trim());
                var payment = jQuery(this).find('[name="task"]').val();
                if (payment == 'form.save' || payment == 'form.mollie') {
                    jQuery(this).removeClass('ba-payment');
                    ba_jQuery(this).attr('target', 'form-target');
                } else if (payment == 'form.stripe') {
                    jQuery(this).addClass('ba-payment');
                    ba_jQuery(this).attr('target', 'form-target');
                    iframe = ba_jQuery('<iframe/>', {
                        name:'form-target',
                        id:'form-target'
                    });
                    ba_jQuery('#form-target').remove();
                    iframe.appendTo(ba_jQuery('body'));
                    ba_jQuery(iframe).attr('style', 'display:none');
                    var api_key = form.find('[value="form.stripe"]').attr('data-api-key'),
                        image = form.find('[value="form.stripe"]').attr('data-image'),
                        total = baTotal.find('.ba-price').text() * 100,
                        name = form.find('[value="form.stripe"]').attr('data-name'),
                        description = form.find('[value="form.stripe"]').attr('data-description');
                    if (!stripe) {
                        stripe = StripeCheckout.configure({
                            key: api_key,
                            image: image,
                            name: name,
                            description: description,
                            locale: 'auto',
                            currency: form.find('.currency-code').val(),
                            token: function(token) {
                                form.append('<input type="hidden" name="stripeTokenId" value="'+token.id+'" />');
                                HTMLFormElement.prototype.submit.call(form[0]);
                                jQuery.ajax({
                                    type:"POST",
                                    dataType:'text',
                                    url:"?option=com_baforms&task=form.stripeCharges&tmpl=component",
                                    data : {
                                        ba_token : JSON.stringify(token),
                                        form_id : form.find('[name="form_id"]').val(),
                                        total : total,
                                        currency : form.find('.currency-code').val().toLowerCase()
                                    },
                                    success: function(msg){
                                        clearFields();
                                    }
                                });
                            }
                        });
                    }
                    stripe.open({
                        amount: total
                    });
                } else {
                    jQuery(this).addClass('ba-payment');
                    ba_jQuery(this).removeAttr('target');
                }
                if (!jQuery(this).hasClass('ba-payment')) {
                    iframe = ba_jQuery('<iframe/>', {
                        name:'form-target',
                        id:'form-target'
                    });
                    ba_jQuery('#form-target').remove();
                    iframe.appendTo(ba_jQuery('body'));
                    ba_jQuery(iframe).attr('style', 'display:none');
                    var item = ba_jQuery(this),
                        dir = ba_jQuery('.admin-dirrectory').val();
                    messageModal.ba_modal();
                    messageModal.parent().show();
                    messageModal.find('.message').html('<img src="'+dir+'components/com_baforms/assets/images/reload.svg">');    
                }
            }
        }

        function clearFields()
        {
            form.find('span.ba-input-image').css('border', '');
            form.find('input').each(function(){
                var type = ba_jQuery(this).attr('type');
                if (type == 'radio' || type == 'checkbox') {
                    ba_jQuery(this).prop('checked', false);
                }
                if (type == 'email') {
                    ba_jQuery(this).val('');
                }
                if (ba_jQuery(this).closest('.tool').hasClass('ba-textInput') ||
                    ba_jQuery(this).closest('.tool').hasClass('ba-address')) {
                    ba_jQuery(this).val('');
                }
            });
            baTotal.find('input[name="ba_total"]').val('0');
            baTotal.find('.ba-price').text('0');
            prices = [];
            form.find('textarea').each(function () {
                ba_jQuery(this).val('');
            });
            cart.find('.product-cell').not('.ba-cart-headline').remove();
            form.find('select').each(function(){
                ba_jQuery(this).find('option').each(function(){
                    ba_jQuery(this).removeAttr('selected');
                });
            });
            form.find('.condition-area').removeClass('selected');
            form.find('.ba-form')[0].style.height = ''
            var redirect =  form.find('.redirect').val();
            if (redirect != '') {
                setTimeout(function(){
                    var redirect =  form.find('.redirect').val();
                    window.location = redirect;
                }, 2000);
            }
        }
        
        function listenMessage(event) {
            if (typeof(event.data) == 'object' && event.data.type == 'baform') {
                var payment = form.find('[name="task"]').val();
                if (payment == 'form.mollie') {
                    var message = event.data;
                    if (event.data.msg.indexOf('http') == -1) {
                        setTimeout(function(){
                            messageModal.find('.message img').addClass('reload-hide');
                            setTimeout(function(){
                                messageModal.find('.message').html('<div class="message-text">'+event.data.msg+'</div>');
                            }, 500);
                        }, 1500);
                    } else {
                        location.href = event.data.msg;
                    }
                    return false;
                }
                window.removeEventListener("message", listenMessage, false);
                form.find('.ba-captcha').find('input[type="text"]').val('');
                var mesage = ba_jQuery(iframe).contents().find('#form-sys-mesage').val();
                setTimeout(function(){
                    messageModal.find('.message img').addClass('reload-hide');
                    setTimeout(function(){
                        messageModal.find('.message').html('<div class="message-text">'+mesage+'</div>');
                    }, 500);
                }, 1500);
                var success = form.find('.sent-massage').val();
                if (popupModal.length > 0) {
                    form.closest('.popup-form').ba_modal('hide');
                    form.closest('modal-scrollable').hide();
                }
                if (success == mesage) {
                    clearFields();
                }
            }
        }
        
        messageModal.on('show', function(){
            window.addEventListener("message", listenMessage, false);
        });
        messageModal.on('hide', function(){
            window.removeEventListener("message", listenMessage, false);
            messageModal.parent().addClass('hide-animation');
            setTimeout(function(){
                messageModal.parent().removeClass('hide-animation');
            }, 500);
        });

        var now = new Date();
        now = new Date(now.getFullYear(), now.getMonth(), now.getDate()).valueOf();

        baForm.find('.tool.ba-date').each(function(){
            var text = ba_jQuery(this).find('input[type="text"]').attr('id'),
                flag = false;
            if (ba_jQuery(this).hasClass('disable-previous-date')) {
                flag = true;
            }
            Calendar.setup({
                inputField: text,
                ifFormat: "%d %B %Y",
                align: "Tl",
                singleClick: true,
                firstDay: 0,
                disableFunc: function(date){
                    if (flag) {
                        var date = new Date(date);
                        date = new Date(date.getFullYear(), date.getMonth(), date.getDate()).valueOf()
                        if (now > date) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                },
            });
        });
        
        baForm.find('.ba-textInput input').on('keyup', function(){
            var type = ba_jQuery(this).attr('data-type'),
                value = ba_jQuery(this).val();
            if (type == 'number') {
                if (ba_jQuery.isNumeric(value)) {
                    ba_jQuery(this).removeClass('ba-alert');
                } else {
                    ba_jQuery(this).addClass('ba-alert');
                }
            }
        });

        if (baForm.find('.ba-form .btn-next').length > 0) {
            baForm.find('.ba-submit-cell').hide();
        }
        
        baForm.find('.ba-form .btn-next').on('click', function() {
            setTimeout(refreshMap, 100)
            var parent = ba_jQuery(this).parent().parent(),
                id = parent.attr('class'),
                formParent = form.find('.ba-form');
                n = id.substr(5),
                position = formParent.offset().top;
            checkAlert(parent);
            if (parent.find('.ba-alert').length == 0) {
                var height = formParent.height(),
                    nextParent = formParent.find('.page-'+(++n)),
                    title = formParent.find(' > .ba-row');
                if (title.length > 0) {
                    title = title.height();
                }
                if (nextParent.find('.btn-next').length == 0) {
                    formParent.find('.ba-submit-cell').show();
                }
                nextParent.find('.condition-area.selected').css('height', '');
                formParent.height(height);
                parent.hide();
                nextParent.show();
                height = nextParent.height();
                height += formParent.find('.ba-form-footer').height();
                if (typeof(title) == 'number') {
                    height += title;
                }
                parent.closest('.ba-form').height(height);
            } else {
                var alert = parent.find('.ba-alert').first();
                if (!alert.hasClass('tool')) {
                    alert = alert.closest('.tool');
                }
                position = alert.offset().top - 150;
            }
            ba_jQuery('html, body').animate({
                scrollTop: position
            }, 'slow');
        });
        
        baForm.find('.tool input[type="text"], .tool textarea').on('blur', function(){
            if (ba_jQuery(this).hasClass('ba-alert')) {
                var value = ba_jQuery(this).val(),
                    type = ba_jQuery(this).attr('data-type');
                if (ba_jQuery.trim(value) != '') {
                    if (type == 'number') {
                        if (ba_jQuery.isNumeric(value)) {
                            ba_jQuery(this).removeClass('ba-alert');
                        }
                    } else if (type == 'calculation') {

                    } else {
                        ba_jQuery(this).removeClass('ba-alert');
                    }
                }
            }
        });
        
        baForm.find('.tool input[type="email"]').on('blur', function(){
            if (ba_jQuery(this).hasClass('ba-alert')) {
                var reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/,
                    value = ba_jQuery(this).val();
                if(reg.test(value)) {
                    ba_jQuery(this).removeClass('ba-alert');
                }                
            }
        });
        
        baForm.find('.tool select').on('change', function(){
            if (ba_jQuery(this).hasClass('ba-alert')) {
                var value = ba_jQuery(this).val();
                if (ba_jQuery.trim(value) != '') {
                    ba_jQuery(this).removeClass('ba-alert');
                }
            }
        });
        
        baForm.find('.tool input[type="checkbox"], .tool input[type="radio"]').on('change', function(){
            ba_jQuery(this).closest('.tool').removeClass('ba-alert');
        });
        
        baForm.find('.ba-form .btn-prev').on('click', function(){
            setTimeout(refreshMap, 50)
            var parent = ba_jQuery(this).parent().parent(),
                id = parent.attr('class'),
                n = id.substr(5),
                formParent = form.find('.ba-form');
                height = formParent.height(),
                nextParent = parent.parent().find('.page-'+(--n)),
                title = formParent.find(' > .ba-row');
            formParent.find('.ba-submit-cell').hide();
            if (title.length > 0) {
                title = title.height();
            }
            formParent.height(height);
            parent.hide();
            nextParent.show();
            height = nextParent.height();
            height += formParent.find('.ba-form-footer').height();
            if (typeof(title) == 'number') {
                height += title;
            }
            parent.closest('.ba-form').height(height);
            var position = formParent.offset().top;
            ba_jQuery('html, body').animate({
                scrollTop: position
            }, 'slow');
        });
        
        refreshMap();
        conditionShow();

        function decimalAdjust(type, value, exp)
        {
            if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
            }
            value = +value;
            exp = +exp;
            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
            }
            value = value.toString().split('e');
            value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }

        if (!Math.round10) {
            Math.round10 = function(value, exp) {
                return decimalAdjust('round', value, exp);
            };
        }
        
        var prices = new Array();
        baForm.find('input[data-price]').on('change', function(event){
            var price = jQuery(this).attr('data-price')*1,
                name = jQuery(this).attr('name'),
                total = baTotal.find('.ba-price').text()*1,
                label = jQuery(this).val();
            label = label.split(' - ');
            if (jQuery(this).attr('type') == 'radio') {
                if (!prices[name]) {
                    prices[name] = price;
                    addToCart(label[0], label[1], name, price);
                    var radio = jQuery(this).closest('div').find('input[name="'+name+'"]').not('[data-price]');
                    radio.off('click.ba_total')
                    radio.one('click.ba_total', function(){
                        var name = jQuery(this).attr('name'),
                            total = baTotal.find('.ba-price').text()*1
                        if (cart.length > 0) {
                            var quantity = Math.round(cart.find('.product-cell[data-id="'+name+'"] .quantity input').val());
                            total = total - prices[name]  * quantity;
                        } else {
                            total = total - prices[name] * 1;
                        }
                        prices[name] = 0;
                        total = Math.round10(total, -2);
                        baTotal.find('.ba-price').text(total);
                        baTotal.find('input[name="ba_total"]').val(total);
                    });
                } else {
                    if (cart.length > 0) {
                        var quantity = Math.round(cart.find('.product-cell[data-id="'+name+'"] .quantity input').val());
                        total = total - prices[name]  * quantity;
                    } else {
                        total = total - prices[name] * 1;
                    }
                    cart.find('.product-cell[data-id="'+name+'"]').remove();
                    addToCart(label[0], label[1], name, price);
                    prices[name] = price;
                }
                total = total + price;
            } else {
                if (jQuery(this).prop('checked')) {
                    addToCart(label[0], label[1], name, price);
                    total = total + price;
                } else {
                    var str = '.product-cell[data-id="'+name+'"][data-product="';
                    str += jQuery(event.currentTarget).val()+'"]';
                    if (cart.length > 0) {
                        var quantity = Math.round(cart.find(str).find('.quantity input').val());
                        total = total - price * quantity;
                    } else {
                        total = total - price * 1;
                    }
                    cart.find(str).remove();
                }
            }
            total = Math.round10(total, -2);
            baTotal.find('.ba-price').text(total);
            baTotal.find('input[name="ba_total"]').val(total);
        });
        
        baForm.find('.tool select').on('change', function(){
            if (jQuery(this).find('[data-price]').length > 0) {
                var value = jQuery(this).val(),
                    option = jQuery(this).find('option[value="'+value+'"]'),
                    price = option.attr('data-price')*1,
                    name = jQuery(this).attr('name'),
                    total = baTotal.find('.ba-price').text() * 1,
                    label = jQuery(this).val();
                if (isNaN(price)) {
                    price = 0;
                }
                if (jQuery(this).attr('multiple')) {
                    if (!prices[name]) {
                        prices[name] = new Array();
                    } else {
                        for (var i = 0; i < prices[name].length; i++) {
                            var str = '.product-cell[data-id="'+name+'"]';
                            str += '[data-product="'+prices[name][i].value+'"]'
                            if (cart.length > 0) {
                                var quantity = Math.round(cart.find(str).find('.quantity input').val());
                                total = total - prices[name][i].price  * quantity;
                            } else {
                                total = total - prices[name][i].price * 1;
                            }
                            cart.find(str).first().remove();
                        }
                        prices[name] = [];
                    }
                    if (jQuery(this).val()) {
                        for (var i = 0; i < value.length; i++) {
                            label = value[i].split(' - ');
                            option = jQuery(this).find('option[value="'+value[i]+'"]');
                            price = option.attr('data-price')*1
                            if (!isNaN(price)) {
                                addToCart(label[0], label[1], name, price);
                                var obj = {
                                    price : price,
                                    value : value[i]
                                }
                                prices[name].push(obj);
                                total  = total + price;
                            }                            
                        }
                    }
                } else {
                    label = label.split(' - ');
                    if (!prices[name]) {
                        if (label[1]) {
                            prices[name] = price;
                            addToCart(label[0], label[1], name, price);
                        }                        
                    } else {
                        if (cart.length > 0) {
                            var quantity = Math.round(cart.find('.product-cell[data-id="'+name+'"] .quantity input').val());
                            total = total - prices[name]  * quantity;
                        } else {
                            total = total - prices[name] * 1;
                        }
                        prices[name] = price;
                        cart.find('.product-cell[data-id="'+name+'"]').remove();
                        if (label[1]) {
                            addToCart(label[0], label[1], name, price);
                        }
                    }
                    total = total + price;
                }
                total = Math.round10(total, -2);
                baTotal.find('.ba-price').text(total);
                baTotal.find('input[name="ba_total"]').val(total);
            }
        });



        if (!form.parent().hasClass('ba-modal-body')) {
            form.find('.tool select').each(function(){
                var $this = ba_jQuery(this);
                if ($this.val()) {
                    $this.trigger('change');
                }
            });
            form.find('input[checked]').trigger('change');
            form.find('input[checked]').each(function(){
            	if (jQuery(this).closest('span').hasClass('ba-input-image')) {
	            	jQuery(this).closest('span').css('border', '2px solid '+theme);
	            }
            });
        }
        
        baForm.find('.popup-btn').on('click', function(event){
            event.preventDefault();
            ba_jQuery('body').addClass('ba-forms-modal');
            popupModal.ba_modal();
            popupModal.on('hide', function(){
                var scrollable = popupModal.parent();
                ba_jQuery('body').removeClass('ba-forms-modal');
                scrollable.addClass('hide-animation');
                setTimeout(function(){
                    scrollable.removeClass('hide-animation');
                }, 500);
            });
            setTimeout(function(){
                form.find('.tool select').trigger('change');
                form.find('input[checked]').trigger('change');
            }, 600);
            popupModal.parent().show();
            setTimeout(refreshMap, 300);
        });
        
        baForm.find('.tool').find('input[type=file]').on('change', prepareUpload);
        
        baForm.find('.ba-form').parent().on('submit', sendMassage);
        if (baForm.find('.popup-btn').length > 0) {
            if (baForm.find('.popup-btn')[0].localName == 'a') {
                var id = baForm.find('[name="form_id"]').val();
                jQuery('.baform-replace').each(function(){
                    if (jQuery.trim(jQuery(this).text()) == '[forms ID='+id+']') {
                        jQuery(this).replaceWith(baForm.find('.popup-btn')[0])
                        return false;
                    }
                })
            }
        }

        baForm.find('.ba-modal-close').on('click', function(event){
            event.preventDefault();
            ba_jQuery(this).parent().ba_modal('hide');
        });
        baForm.find('.ba-lightbox-image img').on('click.lightbox', function(){
            jQuery('.ba-image-backdrop').remove();
            var div = document.createElement('div'),
                width = this.width,
                height = this.height,
                backdrop = document.createElement('div'),
                offset = jQuery(this).offset(),
                imgHeight = this.naturalHeight,
                modalTop,
                imgWidth = this.naturalWidth,
                modal = jQuery(div),
                target = jQuery(window).height()-100,
                flag = true,
                img = document.createElement('img'),
                left,
                wWidth = jQuery(window).width()*1,
                wHeigth = jQuery(window).height()*1,
                bg = jQuery(this).attr('data-lightbox');
            img.src = this.src;
            div.className = 'ba-image-modal';
            div.style.top = offset.top * 1 - jQuery(window).scrollTop() * 1+'px';
            div.style.left = offset.left+'px';
            div.style.width = width+'px';
            div.appendChild(img);
            img.style.width = width+'px';
            img.style.height = height+'px';
            backdrop.className = 'ba-image-backdrop';
            backdrop.style.backgroundColor = bg;
            jQuery(backdrop).on('click', function(){
                jQuery(this).addClass('image-lightbox-out');
                modal.animate({
                    'width' : width,
                    'height' : height,
                    'left' : offset.left,
                    'top' : offset.top * 1 - jQuery(window).scrollTop() * 1
                }, '500', function(){
                    jQuery('.ba-image-backdrop').remove();
                });
                modal.find('img').animate({
                    'width' : width,
                    'height' : height,
                    'left' : offset.left,
                    'top' : offset.top * 1 - jQuery(window).scrollTop() * 1
                }, '500');
            });
            jQuery('body').append(div);
            modal.wrap(backdrop);
            if (wWidth > 1024) {
                if (imgWidth * 1 < wWidth && imgHeight * 1 < wHeigth) {
                
                } else {
                    if (imgWidth > imgHeight) {
                        var percent = target/imgWidth;
                        flag = false;
                    } else {
                        var percent = target/imgHeight;
                        flag = true;
                    }
                    imgWidth = imgWidth * percent;
                    imgHeight = imgHeight * percent;
                    if (imgWidth > wWidth) {
                        imgWidth = imgWidth * percent;
                        imgHeight = imgHeight * percent;
                    }
                    if (!flag) {
                        var percent = imgWidth / imgHeight;
                        imgHeight = target;
                        imgWidth = imgHeight * percent;
                        if (wWidth - 100 < imgWidth) {
                            imgWidth = wHeigth - 100;
                            imgHeight = imgWidth / percent;
                        }
                    }
                }
            } else {
                var percent = imgWidth / imgHeight;
                if (percent >= 1) {
                    imgWidth = wWidth * 0.90;
                    imgHeight = imgWidth / percent;
                    if (wHeigth - imgHeight < wHeigth * 0.1) {
                        imgHeight = wHeigth * 0.90;
                        imgWidth = imgHeight * percent;
                    }
                } else {
                    imgHeight = wHeigth * 0.90;
                    imgWidth = imgHeight * percent;
                    if (wWidth -imgWidth < wWidth * 0.1) {
                        imgWidth = wWidth * 0.90;
                        imgHeight = imgWidth / percent;
                    }
                }
            }
            modalTop = (wHeigth - imgHeight)/2;
            left = (wWidth - imgWidth)/2;
            modal.animate({
                'width' : Math.round(imgWidth),
                'height' : Math.round(imgHeight),
                'left' : Math.round(left),
                'top' : Math.round(modalTop)
            }, '500');
            modal.find('img').animate({
                'width' : Math.round(imgWidth),
                'height' : Math.round(imgHeight),
                'left' : Math.round(left),
                'top' : Math.round(modalTop)
            }, '500');
        });
            
        function addToCart(product, price, id, cost)
        {
            if (cart.length > 0) {
                var str = '<div class="product-cell" data-id="'+id;
                str += '" data-product="'+product+' - '+price;
                str += '"><div class="product">';
                str += product+'</div><div class="price">'+price;
                str += '</div><div class="quantity"><input type="number" ';
                str += 'value="1" min="1" step="1" data-cost="'+cost;
                str += '"></div><div class="total">'+price;
                str += '</div><div class="remove-item"><i class="zmdi zmdi';
                str += '-close"></i></div></div>';
                cart.append(str);
                cart.find('.remove-item i.zmdi').off('click');
                cart.find('.remove-item i.zmdi').on('click', removeCart);
                cart.find('.quantity input').off();
                cart.find('.quantity input').on('click keyup', function(){
                    var value = jQuery(this).val(),
                        price = jQuery(this).attr('data-cost'),
                        cost;
                    if (value < 1) {
                        value = 1;
                        jQuery(this).val(1);
                    }
                    value = Math.round(value);
                    cost = value * price;
                    cost = Math.round10(cost, -2);
                    if (symbolPos == 'before') {
                        cost = symbol + cost;
                    } else {
                        cost = cost + symbol;
                    }
                    var total = baTotal.find('.ba-price').text()*1,
                        currentPrice = jQuery(this).closest('.product-cell').find('.total').text();
                    currentPrice = currentPrice.replace(symbol, '');
                    total = total - currentPrice;
                    total = total + price * value;
                    total = Math.round10(total, -2);
                    baTotal.find('.ba-price').text(total);
                    baTotal.find('input[name="ba_total"]').val(total);
                    jQuery(this).closest('.product-cell').find('.total').text(cost);
                });
                cart.closest('.ba-form')[0].style.height = '';
            }
        }

        function removeCart()
        {
            var item = jQuery(this).closest('.product-cell'),
                id = item.attr('data-id'),
                value = item.attr('data-product'),
                element = jQuery('[name="'+id+'"]'),
                price = item.find('.total').text();
            if (element.attr('type') == 'checkbox') {
                jQuery('[name="'+id+'"][value="'+value+'"]').prop('checked', false).trigger('change');
                jQuery('[name="'+id+'"][value="'+value+'"]').closest('span').css('border', '');
            } else if (element.attr('type') == 'radio') {
                var total = baTotal.find('.ba-price').text()*1,
                    quantity = Math.round(cart.find('.product-cell[data-id="'+id+'"] .quantity input').val());
                total = total - prices[id]  * quantity;
                delete(prices[id]);
                total = Math.round10(total, -2);
                baTotal.find('.ba-price').text(total);
                baTotal.find('input[name="ba_total"]').val(total);
                item.remove();
                var input = jQuery('[name="'+id+'"][value="'+value+'"]');
                input.prop('checked', false).closest('span').css('border', '');
                if (input.closest('.tool').find('> .condition-area').length > 0) {
                    input.closest('.tool').find('> .condition-area.selected').removeClass('selected');
                    clearCondition(input.closest('.tool').find('> .condition-area'));
                }
            } else {
                element.find('option[value="'+value+'"]').removeAttr('selected');
                if (!element.attr('multiple')) {
                    element.find('option').first().attr('selected', true);
                }
                element.trigger('change');
            }
            element.closest('.ba-form')[0].style.height = '';
        }

        if (saveContinue == 1) {
            baForm.find('.get-save-continue').on('click', function(event){
                event.preventDefault();
                var obj = {};
                form.find('input[type="text"][name], textarea[name], input[type="email"][name]').each(function(){
                    var name = this.name,
                        value = this.value;
                    value = jQuery.trim(value);
                    obj[name] = value;
                });
                form.find('input[type="radio"][name]').each(function(){
                    if (jQuery(this).prop('checked')) {
                        var name = this.name,
                            value = this.value;
                        value = jQuery.trim(value);
                        obj[name] = value;
                    }
                });
                form.find('input[type="checkbox"][name]').each(function(i){
                    var name = this.name,
                        key,
                        value;
                    if (!obj[name]) {
                        obj[name] = {};
                    }
                    if (jQuery(this).prop('checked')) {
                        value = this.value;
                        value = jQuery.trim(value);
                        obj[name][i] = value;
                    }
                });
                form.find('select[name]').each(function(){
                    var name = this.name,
                        key,
                        value;
                    if (!obj[name]) {
                        obj[name] = {};
                    }
                    jQuery(this).find('option').each(function(i){
                        if (jQuery(this).prop('selected')) {
                            value = this.value;
                            value = jQuery.trim(value);
                            obj[name][i] = value;
                        }
                    });
                });
                if (form.find('.ba-terms-conditions.tool').length > 0) {
                    var key = 0;
                    if (jQuery('.ba-terms-conditions.tool input').prop('checked')) {
                        key = 1;
                    }
                    obj.terms = key;
                }
                form.find('.ba-slider-values').each(function(){
                    var name = this.name,
                        value = this.value;
                    obj[name] = value;
                });
                obj = JSON.stringify(obj);
                var token = saveModal.find('.save-popup-token').val(),
                    url = window.location.href,
                    message = saveModal.find('.save-popup-message').val(),
                    link = '<p class="ba-token" ',
                    pos;
                if (url.indexOf('?') > 0) {
                    if ((pos = token.indexOf('bf_token=')) > 0) {
                        url = url.substr(0, pos);
                        url += 'bf_token='+token;
                    } else {
                        url += '&bf_token='+token;
                    }
                } else {
                    url += '?bf_token='+token;
                }
                link += '>'+url+'</p>';
                message = message.replace('[ba-form-token-link]', link);
                saveModal.find('.save-message').html(message);
                saveModal.ba_modal();
                saveModal.parent().show();
                jQuery.ajax({
                    type:"POST",
                    dataType:'text',
                    url:"?option=com_baforms&task=form.getToken&tmpl=component",
                    data : {
                        data : obj,
                        ba_token : token
                    },
                    success: function(msg){
                    }
                });
            });
            saveModal.on('hide', function(){
                saveModal.parent().addClass('hide-animation');
                setTimeout(function(){
                    saveModal.parent().removeClass('hide-animation');
                }, 500);
            });
            baForm.find('.send-save').on('click', function(event){
                event.preventDefault();
                var $this = jQuery(this),
                	wrapper = $this.closest('.save-email-wrapper'),
                	container = $this.closest('.ba-modal-body'),
                    link = container.find('.ba-token').text(),
                    email = container.find('.save-email').val(),
                    obj = {},
                    reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
                if(!reg.test(email)) {
                    container.find('.save-email').addClass('ba-alert');
                } else {
                	if ($this.attr('data-disable')) {
                		return false;
                	}
                	$this.attr('data-disable', true);
                	wrapper.addClass('save-animation-in');
                	setTimeout(function(){
                		wrapper.removeClass('save-animation-in').addClass('save-animation-out');
                		setTimeout(function(){
                			wrapper.removeClass('save-animation-out');
                			$this.removeAttr('data-disable');
                		}, 2000);
                	}, 2000);
                    container.find('.save-email').removeClass('ba-alert');
                    jQuery.ajax({
                        type : "POST",
                        dataType : 'text',
                        url : "?option=com_baforms&task=form.saveContinue&tmpl=component",
                        data : {
                            ba_link : link,
                            ba_email : email,
                            form_id : form.find('[name="form_id"]').val()
                        },
                        success: function(msg){
                            
                        }
                    });
                }
            });
            var obj = form.find('.ba-token-data').val();
            if (obj) {
                obj = JSON.parse(obj);
                baForm.find('.tool input[name], .tool textarea[name], .tool select[name]').each(function(){
                    if (this.localName == 'textarea' || this.type == 'text' || this.type == 'email') {
                        this.value = obj[this.name];
                        jQuery(this).trigger('change').trigger('keyup').trigger('input');
                    } else if (this.type == 'radio') {
                        if (this.value.trim() == obj[this.name]) {
                            jQuery(this).prop('checked', true).trigger('change').trigger('click').trigger('input');
                        }
                    } else if (this.type == 'checkbox') {
                        var name = this.name;
                        if (obj[name]) {
                            for (key in obj[name]) {
                                if (this.value.trim() == obj[name][key]) {
                                    jQuery(this).prop('checked', true);
                                    break;
                                }
                            }
                        }
                    } else if (this.localName == 'select') {
                        var name = this.name;
                        if (obj[name]) {
                            jQuery(this).find('option').each(function(){
                                for (key in obj[name]) {
                                    if (this.value.trim() == obj[name][key]) {
                                        jQuery(this).attr('selected', true);
                                        break;
                                    }
                                }
                            });
                            jQuery(this).trigger('change').trigger('click').trigger('input');
                        }
                    }
                });
                if (jQuery('.ba-terms-conditions.tool').length > 0) {
                    var key = obj.terms;
                    if (key == 1) {
                        jQuery('.ba-terms-conditions.tool input').prop('checked', true);
                    }
                }
            }
        }

        baForm.find('.ba-slider').each(function(){
            var options = ba_jQuery(this).parent().find('.ba-options').val(),
                values = new Array(),
                input = jQuery(this).closest('.tool').find('.ba-slider-values')[0],
                name = input.name;
            options = options.split(';');
            values.push(options[2]);
            values.push(options[3]);
            if (saveContinue == 1 && obj && obj[name]) {
                values = obj[name].split(' — ');
            }
            ba_jQuery(this).slider({
                min: options[2],
                max: options[3],
                step: options[4],
                value: [values[0], values[1]]
            });
        });
    });
});