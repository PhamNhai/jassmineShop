<?php

/**
 *
 * @package VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.0 Pro
 *
 * */
defined('_JEXEC') or die;

function simplemembershipBuildRoute($query)
{ //print_r($query);echo"<br>START Build route<br>";
    $segments = array();
    $db = JFactory::getDBO();

    $JSite = new JSite();
    $menu = $JSite->getMenu();
    if (isset($query['Itemid']))
    {
        if (!isset($query['view'])  && !isset($query['task'])){
            @$query['view'] = $menu->getItem($query['Itemid'])->query['view'];
        }
    }
    if (isset($query['option']) && $query['option'] == 'com_simplemembership')
    { //check component
        $segments[0] = (isset($query['Itemid'])) && ($query['Itemid'] != 0) ? $query['Itemid'] : '0';
        if ((isset($query['view'])) && (!isset($query['task'])))
        {
            $query['task'] = $query['view'];
        }       
        if (isset($query['task']))
        {
            $segments[1] = $query['task'];
        }
        unset($query['task']);
        unset($query['view']);
        
        if (!empty($query['lang']))
        {
            unset($query['lang']);
        }
        // if (isset($query['Itemid']))
        // {
        //     unset($query['Itemid']);
        // }
        // if (!empty($query['Itemid']))
        // {
        //     $query['Itemid'] = "";
        //     unset($query['Itemid']);
        // }

        if (isset($query['name']))
        {
            $segments[] = JFilterOutput::stringURLSafe($query['name']);
            unset($query['name']);
        }

        if (isset($query['user']))
        {
            $segments[] = $query['user'];
            unset($query['user']);
        }        
    
        if(isset($query['layout']))
        {
            $segments[] = $query['layout'];
            unset($query['layout']);
        }
        if(isset($query['view']))
        {
            $segments[] = $query['view'];
            unset($query['view']);
        }    
        if(isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        }
        if(isset($query['tab']))
        {
            $segments[] = $query['tab'];
            unset($query['tab']);
        }
        if(isset($query['is_show_data']))
        {
            $segments[] = $query['is_show_data'];
            unset($query['is_show_data']);
        }
        if(isset($query['userId']))
        {
            $segments[] = $query['userId'];
            unset($query['userId']);
        }
        if(isset($query['add_advertisement']))
        {
            $segments[] = $query['add_advertisement'];
            unset($query['add_advertisement']);
        }
        if(isset($query['show_alone_advertisement']))
        {
            $segments[] = $query['show_alone_advertisement'];
            unset($query['show_alone_advertisement']);
        }
        if(isset($query['edit_advertisement']))
        {
            $segments[] = $query['edit_advertisement'];
            unset($query['edit_advertisement']);
        }
        // for vechicles
        if(isset($query['edit_my_cars']))
        {
            $segments[] = $query['edit_my_cars'];
            unset($query['edit_my_cars']);
        }
        if(isset($query['is_show_data']))
        {
            $segments[] = $query['is_show_data'];
            unset($query['is_show_data']);
        }
        if(isset($query['view_vehicle']))
        {
            $segments[] = $query['view_vehicle'];
            unset($query['view_vehicle']);
        }
        if(isset($query['catid']))
        {
            $segments[] = $query['catid'];
            unset($query['catid']);
        } 
        
    }
  //  unset($query);

    return $segments;
}
/**
 * Parse the segments of a URL.
 * */
function simplemembershipParseRoute($segments)
{
    //print_r($segments);echo 'simplemembershipParseRoute start<br/>';
    $db = JFactory::getDBO();
    $vars = array();
    
    $count = count($segments);
    $vars['option'] = 'com_simplemembership';

    $JSite = new JSite();
    $menu = $JSite->getMenu();
    $menu->setActive($segments[0]);
    $vars['Itemid'] = $segments[0];
    
    if(isset($segments[1])&&($segments[1]=='my_account' || $segments[1]=='show_users' ||$segments[1]=='userprolong'
            || $segments[1]=='show_add' || $segments[1]=='registration' || $segments[1]=='resetpassword'
            || $segments[1]=='login'
            || $segments[1]=='remindpassword'  || $segments[1]=='show_my_ads' || $segments[1]=='my_vehicles') ){
        $vars['task'] = $segments[1];
        if(isset($segments[2])&& isset($segments[3])){
        $vars['tab'] = $segments[2];
        $vars['is_show_data'] = $segments[3];
        }
    }     
    elseif(isset($segments[1])&&($segments[1]=='add_advertisement'||$segments[1]=='showUsersProfile')){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])){
        $vars['userId'] = $segments[2];
        }
    }
    elseif(isset($segments[1])&& ($segments[1]=='show_alone_advertisement'||$segments[1]=='edit_advertisement')){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])){
        $vars['id'] = $segments[2];
        }
    }
    //for vehicle
    elseif(isset($segments[1]) && $segments[1]=='edit_my_cars'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2];
        $vars['is_show_data'] = $segments[3];
        }
    }
    elseif(isset($segments[1]) && $segments[1]=='view_vehicle'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])&&isset($segments[4])&&isset($segments[5])){
        $vars['id'] = $segments[2];
        $vars['tab'] = $segments[3]; 
        $vars['is_show_data'] = $segments[4];
        $vars['catid'] = $segments[5];
        }
    }
    elseif(isset($segments[1]) && ($segments[1]=='rent_request_vehicle'or $segments[1]=='edit_vehicle'or$segments[1]=='save_add_vehicle')){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2];         
        $vars['is_show_data'] = $segments[3];        
        }
    }
   // for realestate
    elseif(isset($segments[1]) && $segments[1]=='view_user_houses'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])&&isset($segments[4])&&isset($segments[5])){
        $vars['name'] = $segments[2];         
        $vars['user'] = $segments[3];
        $vars['tab'] = $segments[4];
        $vars['is_show_data'] = $segments[5];
        }
    }
    elseif(isset($segments[1]) && $segments[1]=='my_houses'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2];
        $vars['is_show_data'] = $segments[3];
        }
    }
    elseif(isset($segments[1]) && $segments[1]=='edit_house'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])&&isset($segments[4])){
        $vars['id'] = $segments[2];
        $vars['tab'] = $segments[3];
        $vars['is_show_data'] = $segments[4];
        
        }
    }
     elseif(isset($segments[1]) && $segments[1]=='save_add'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2];
        $vars['is_show_data'] = $segments[3];
        
        }
    }
    elseif(isset($segments[1]) && $segments[1]=='view'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])&&isset($segments[4])&&isset($segments[5])){
        $vars['id'] = $segments[2];
        $vars['tab'] = $segments[3];
        $vars['is_show_data'] = $segments[4];
        $vars['catid'] = $segments[5];
        
        }
    }
    elseif(isset($segments[1]) && $segments[1]=='buying_request'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])){
        $vars['tab'] = $segments[2];        
        } 
    }
    elseif(isset($segments[1]) && ($segments[1]=='buying_requests' || $segments[1]=='rent_requests')){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2]; 
        $vars['is_show_data'] = $segments[3];
        } 
    }
    elseif(isset($segments[1]) && $segments[1]=='show_add'){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        if(isset($segments[2])&&isset($segments[3])){
        $vars['tab'] = $segments[2]; 
        $vars['is_show_data'] = $segments[3];
        } 
    }
    elseif(isset($segments[1]) && ($segments[1]=='edit_house' && isset($segments[2]))){
        $vars['Itemid']=$segments[0];
        $vars['task'] = $segments[1];
        $vars['tab'] = $segments[2]; 
    }
    
    
    if(isset($GLOBALS['select_com'])){
        if($GLOBALS['select_com'] == 'com_advertisementboard'){
            
        }
        elseif($GLOBALS['select_com'] == 'com_vehiclemanager'){
            
        }
        elseif($GLOBALS['select_com'] == 'com_realestatemanager'){
        }
    }
    //print_r($vars);
    return $vars;
    
}