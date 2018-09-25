<?php
/**
* @package OS Gallery
* @copyright 2017 OrdaSoft
* @author 2017 Andrey Kvasnevskiy(akbet@mail.ru),Dmitriy Smirnov (dmitriiua21@gmail.com)
* @license GNU General Public License version 2 or later;
* @description Ordasoft Image Gallery
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class osGallerySocialButtonsHelper {
    private $url;
    private $img_link;
    private $icons_path;
    private $title;

    /** 
     * Facebook button
     * @param string $url Image url 
     * @param string $img_link Image Link
     * 
     */
    public function __construct($url, $title, $img_link, $icons_path) 
    {
        $this->url = rawurlencode($url);
        $this->img_link = rawurlencode($img_link);
        $this->icons_path = $icons_path;
        $this->title = $title;
    }

    /* Buttons */ 
    public function getFacebookButton()
    {       
        $img_url = $this->icons_path . "/facebook.png"; 
        return '<a class="animated act soc1" rel="noindex, nofollow" href="http://www.facebook.com/sharer.php?u=' . $this->url . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }     
 
    public function getGooglePlusButton()
    {       
        $img_url = $this->icons_path . "/google-plus.png"; 
        return '<a class="animated act soc2" href="https://plus.google.com/share?url='  . $this->url  . '" target="_blanck"><img style="width: 32px; height:32px;" src="' . $img_url . '" /></a>';
    }    

    public function getVkComButton()
    {       
        $img_url = $this->icons_path . "/vkontakte.png"; 
        return '<a class="animated act soc3" href="http://vk.com/share.php?url='  . $this->url .'&amp;title=' . $this->title . '&amp;image=' . $this->img_link . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }

    public function getOdnoklassnikiButton()
    {       
        $img_url = $this->icons_path . "/odnoklassniki.png";
        return '<a class="animated act soc4" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='  . $this->url .'&amp;st.comments=' . $this->title . '&amp;image=' . $this->img_link . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }     

    public function getTwitterButton()
    {       
        $img_url = $this->icons_path . "/twitter.png";
        return '<a class="animated act soc5" href="https://twitter.com/share?url='  . $this->url .'&amp;text=' . $this->title . '&amp;image=' . $this->img_link . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }    

    public function getPinterestButton()
    {       
        $img_url = $this->icons_path . "/pinterest.png";
        return '<a class="animated act soc6" href="http://pinterest.com/pin/create/button/?url='  . $this->url .'&amp;description=' . $this->title . '&amp;media=' . $this->img_link . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }

    public function getLinkedInButton()
    {       
        $img_url = $this->icons_path . "/linkedin.png";
        return '<a class="animated act soc7" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='  . $this->url .'&amp;title=' . $this->title . '&amp;image=' . $this->img_link . '" target="_blanck"><img style="width: 32px; height:32px" src="' . $img_url . '" /></a>';
    }       
}