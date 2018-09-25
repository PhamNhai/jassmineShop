<?php
defined('JPATH_BASE') or die;

class shoe_store_v1InstallerScript{

    function postflight($type, $parent) {
				$db = JFactory::getDbo();
				$stdParams = trim(file_get_contents(__DIR__.'/admin/default_DB.txt'));

				if(!empty($stdParams)){
					$stdParamsToDb = addslashes($stdParams);
					$query = "UPDATE #__template_styles SET params = '".$stdParamsToDb."' WHERE template = '".$this->getTemplateName()."'";
					$db->setQuery($query);
					$result = $db->query();
				}

				return true;
    }

    function getTemplateName(){
			$xml = simplexml_load_file(__DIR__.'/templateDetails.xml');
			$templateName = $xml->name->__toString();
			return $templateName;
		}

}


 ?>