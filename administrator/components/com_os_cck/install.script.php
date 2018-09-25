<?php

defined('_JEXEC') or die;

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

class com_os_cckInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // $parent is the class calling this method
        $db = JFactory::getDBO();
        $params = new JRegistry;
        $params->set("by_time","0");
        $params->set("rent_type","0");
        $params->set("crop_image","0");
        $params->set("save_database","1");
        $params->set("google_map_key","AIzaSyD4ZY-54e-nzN0-KejXHkUh-D7bbexDMKk");
        $params->set("paypal_currency","USD=1;");
        $params->set("currency_position","1");
        $params->set("use_paypal","0");

        $query = "SELECT * FROM `#__os_cck_version`";
        $db->setQuery($query);
        $isEmpty = $db->loadResult();
        if(!$isEmpty){
            $current_vers = (string)$parent->manifest->version;
            $query = "INSERT INTO `#__os_cck_version` VALUES (null,'".$current_vers."','no previous')";
            $db->setQuery($query);
            $db->query();
        }else{
            self::updateVersion($parent);
        }
        
        $query = "UPDATE #__extensions SET params='".$params->toString()."' WHERE element='com_os_cck'";
        $db->setQuery($query);
        $db->query();
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        require_once(JPATH_SITE . "/administrator/components/com_os_cck/uninstall.os_cck.php");
        com_uninstall();
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        // $parent is the class calling this method
        self::updateVersion($parent);
    }

    static function updateVersion($parent){

        $db = JFactory::getDBO();
        $current_vers = (string)$parent->manifest->version;
        if(file_exists(JPATH_ADMINISTRATOR .'/components/com_os_cck/os_cck.xml')){
            $xml = simplexml_load_file(JPATH_ADMINISTRATOR .'/components/com_os_cck/os_cck.xml');
            $last_vers = (string)$xml->version;
        }else{
            $query = "SELECT current_vers FROM `#__os_cck_version`";
            $db->setQuery($query);
            $last_vers = $db->loadResult();
            if(!$last_vers){
              $last_vers = 'no previous';
            }
        }

        $query = "SELECT current_vers FROM `#__os_cck_version`";
        $db->setQuery($query);
        $result = $db->loadResult();
        if($result){
            $query = "SELECT last_vers FROM #__os_cck_version ";
            $db->setQuery($query);
            $last_versions = $db->loadResult();

            $query = "UPDATE #__os_cck_version SET current_vers = '".$current_vers."', last_vers='".$last_versions." - ".$last_vers."'";
            $db->setQuery($query);
            $db->query();
        }else{
            $query = "INSERT INTO `#__os_cck_version` VALUES (null,'".$current_vers."','".$last_vers."')";
            $db->setQuery($query);
            $db->query();
        }
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)

        $db = JFactory::getDBO();
        $db->setQuery("DELETE FROM #__update_sites WHERE name = 'CCK`s Update'");
        $db->query();
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)


        $database = JFactory::getDBO();

        $table_prefix = $database->getPrefix();
        $tableList = $database->getTableList();

        $ccklibr = '';
        if (array_search($table_prefix . 'os_cck_entity', $tableList)) {
            $database->setQuery("SELECT * FROM #__os_cck_entity");
            $ccklibr = $database->loadResult();
        }

        if(!empty($ccklibr)){
            return;
        }


        $extractPathFiles = JPATH_SITE.'/components/com_os_cck/files';
        $extractPathImages = JPATH_SITE.'/images';
        $extractPathGalleryZip = JPATH_SITE.'/components/com_os_cck/files/gallery.zip';

        $zip = new ZipArchive;
        
        $extract = $zip->open(JPATH_SITE . "/administrator/components/com_os_cck/exports/sample_data.zip");
        if ($extract === TRUE) {
         
            $zip->extractTo($extractPathFiles);
            
        } 

        $extractZip = $zip->open($extractPathGalleryZip);
        $numFiles = $zip->numFiles;
        if ($extractZip === TRUE) {
            
            for ($i=0; $i<$numFiles; $i++) {
                $gallery_folder_name = str_replace(DIRECTORY_SEPARATOR, '', $zip->statIndex($i)['name']);
            }
    
            $zip->extractTo($extractPathImages);
            
        }

        $sqlPath = JPATH_SITE.'/components/com_os_cck/files/sqlcck.sql';
        $sqlContent = file_get_contents($sqlPath);
        $sqlContent = $database->splitSql($sqlContent);

        foreach($sqlContent as $query)
        {
            $database->setQuery($query);
            $result = $database->execute();
        }

        if(file_exists($extractPathFiles.'/sqlcck.sql')) unlink($extractPathFiles.'/sqlcck.sql');
        if(file_exists($extractPathFiles.'/gallery.zip')) unlink($extractPathFiles.'/gallery.zip');


        //sample data

        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        require_once(JPATH_SITE . "/administrator/components/com_os_cck/install.os_cck.php");
        com_install2();
    }

}
