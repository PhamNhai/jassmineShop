<?php 
/**
* @package OS Touch Slider.
* @copyright 2016 OrdaSoft.
* @author 2016 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev(akoevroman@gmail.com).
* @link http://ordasoft.com/os-touch-slider-joomla-responsive-slideshow
* @license GNU General Public License version 2 or later;
* @description OrdaSoft Responsive Touch Slider.
*/
defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgAjaxPlg_os_touchslider extends JPlugin
{

    function onAjaxPlg_os_touchslider()
    {
        $db = JFactory::getDbo();
        $input  = JFactory::getApplication()->input;

        $task = $input->get('task', '');
        $moduleId = $input->get('moduleId', '');
        if($task == "dublicate_slider" && $moduleId){
            $query = "SELECT asset_id,
                             title,
                             note,
                             content,
                             ordering,
                             position,
                             checked_out,
                             checked_out_time,
                             publish_up,
                             publish_down,
                             published,
                             module,
                             access,
                             showtitle,
                             params,
                             client_id,
                             language"
                        ."\n FROM #__modules"
                        ."\n WHERE id = $moduleId";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            $result = $result[0];

            $query = "SELECT menuid"
                        ."\n FROM #__modules_menu"
                        ."\n WHERE moduleid = $moduleId";
            $db->setQuery($query);
            $sliderMenuIds = $db->loadColumn();

            $query = "INSERT INTO #__modules(asset_id, title, note, content, ordering, position, checked_out,checked_out_time,publish_up,publish_down,published,module, access,showtitle,params,client_id,language)".
                    "\n VALUES(".$db->quote($result->asset_id).",
                                ".$db->quote($result->title.'(copy)').",
                                ".$db->quote($result->note).",
                                ".$db->quote($result->content).",
                                ".$db->quote($result->ordering).",
                                ".$db->quote($result->position).",
                                0,
                                '0000-00-00 00:00:00',
                                ".$db->quote($result->publish_up).",
                                ".$db->quote($result->publish_down).",
                                ".$db->quote($result->published).",
                                ".$db->quote($result->module).",
                                ".$db->quote($result->access).",
                                ".$db->quote($result->showtitle).",
                                ".$db->quote($result->params).",
                                ".$db->quote($result->client_id).",
                                ".$db->quote($result->language).")";
            $db->setQuery($query);
            $db->query();
            $newSliderId = $db->insertid();

            if(count($sliderMenuIds)){
                foreach ($sliderMenuIds as $menuId) {
                    $query = "INSERT INTO #__modules_menu(moduleid, menuid)".
                    "\n VALUES($newSliderId,$menuId)";
                    $db->setQuery($query);
                    $db->query();
                }
            }

            $query = "SELECT id,file_name,params FROM #__os_touch_slider".
                    "\n WHERE module_id=$moduleId";
            $db->setQuery($query);
            $sliderTempData = $db->loadObjectList();

            $sliderIds = array();
            foreach ($sliderTempData as $value) {
                $query = "INSERT INTO #__os_touch_slider(file_name, module_id, params) ".
                        "\n VALUES('$value->file_name', $newSliderId, '$value->params')";
                $db->setQuery($query);
                $db->query();

                $sliderIds[$value->id] = $db->insertid();
            }

            //ordering
            $params = new JRegistry;
            $params->loadString($sliderTempData[0]->params);
            $ordering = $params->get("imageOrdering");
            if(count($ordering)){
                foreach ($ordering as $key => $value) {
                    $ordering[$key] = $sliderIds[$value];
                }
                $params->set("imageOrdering", $ordering);
            }
            $params = $params->toString();
            $query = "UPDATE #__os_touch_slider SET params='$params' WHERE module_id=$newSliderId";
            $db->setQuery($query);
            $db->query();

            foreach ($sliderIds as $oldId => $newId) {
                $query ="SELECT text_html FROM #__os_touch_slider_text WHERE fk_ts_img_id=$oldId AND fk_ts_id=$moduleId";
                $db->setQuery($query);
                $textData = $db->loadObjectList();
                if(count($textData)){
                    foreach ($textData as $value) {
                        $query ="INSERT INTO #__os_touch_slider_text(fk_ts_id, fk_ts_img_id, text_html)".
                                "\n VALUES($newSliderId, $newId, '$value->text_html')";
                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }

        }
        //copy images
        $src = JPATH_BASE . '/../images/os_touchslider_'.$moduleId;
        $dst = JPATH_BASE . '/../images/os_touchslider_'.$newSliderId;
        self::recurse_copy($src,$dst);

        $response = array('success' => true, 'newId' => $newSliderId);
        echo json_encode($response);
    }

    static function recurse_copy($src,$dst) {
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }
}