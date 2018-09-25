/**
* @package   BaGrid
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

jQuery(document).ready(function(){
    jQuery('.ba-icons-search').on('keyup', function(){
        var search = jQuery(this).val();
        jQuery('.ba-icon-item').each(function(){
            var title = jQuery(this).find('.ba-icon-title').text();
            title = jQuery.trim(title);
            title = title.toLowerCase();
            search = search.toLowerCase();
            if (title.indexOf(search) < 0) {
                jQuery(this).hide();
            } else {
                jQuery(this).show();
            }
        });
        jQuery('.icons-section').each(function(){
            var flag = true;
            jQuery(this).find('.ba-icon-item').each(function(){
                if (this.style.display == 'none') {
                    flag = false;
                } else {
                    flag = true;
                    return false;
                }
            });
            if (flag) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        });
    });
    
    
    jQuery('.ba-icon-item').on('click', function(){
        var msg = jQuery(this).find('i')[0].className;
        window.parent.postMessage(msg, "*");
    })
}); 
