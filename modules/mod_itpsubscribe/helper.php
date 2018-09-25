<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPSubscribe
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

class ItpSubscribeHelper{
    
    /**
     * Generate a code for the extra buttons
     */
    public static function getExtraButtons($params) {

        $html  = "";
        // Extra buttons
        for($i=1; $i < 6;$i++) {
            $btnName = "ebuttons" . $i;
            $extraButton = $params->get($btnName, "");
            if(!empty($extraButton)) {
                $html  .= $extraButton;
            }
        }
        
        return $html;
    }
    
}