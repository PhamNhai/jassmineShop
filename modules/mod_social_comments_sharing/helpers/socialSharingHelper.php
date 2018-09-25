<?php
/*
* @version 2.1
* @package social sharing
* @copyright 2017 OrdaSoft
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @author 2017 Andrey Kvasnekskiy (akbet@ordasoft.com ), Roman Akoev (akoevroman@gmail.com)
* @description social sharing, sharing WEB pages in LinkedIn, FaceBook, Twitter and Google+ (G+)
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
	

class socialSharingHelper
{
	
	public static function checkApiConnect($api){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);        
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, '2');
        $responce = curl_exec($curl);
    	$responce = $responce ? true : false;

    	return $responce;

	}
	
}	

?>