<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
jimport('joomla.filesystem.folder');
 
class plgSystemBaforms extends JPlugin
{
    public function __construct( &$subject, $config )
    {
        parent::__construct( $subject, $config );
    }

    function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        if ($app->isSite()) {
            $path = JPATH_ROOT . '/components/com_baforms/helpers/baforms.php';
            JLoader::register('baformsHelper', $path);
        }
    }
    
    function onBeforeCompileHead()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if ($app->isSite() && $doc->getType() == 'html') {
            $loaded = JLoader::getClassList();
            if (isset($loaded['baformshelper'])) {
                $a_id = $app->input->get('a_id');
                if (empty($a_id)) {
                    baformsHelper::addStyle();
                }
            }
        }
    }
    
    function onAfterRender()
    {
        $app = JFactory::getApplication();
        $a_id = $app->input->get('a_id');
        $doc = JFactory::getDocument();
        if ($app->isSite() && empty($a_id) && $doc->getType() == 'html') {
            $loaded = JLoader::getClassList();
            if (isset($loaded['baformshelper'])) {
                $html = JResponse::getBody();
                $pos = strpos($html, '</head>');
                $head = substr($html, 0, $pos);
                $body = substr($html, $pos);
                $html = $head.$this->getContent($body);
                JResponse::setBody($html);
            }
        }
    }
    
    function getContent($body)
    {
        $regex = '/\[forms ID=+(.*?)\]/i';
        $array = array();
        preg_match_all($regex, $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $index => $match) {
            $form = explode(',', $match[1]);
            $formId = $form[0];
            if (!empty($formId) && baformsHelper::checkForm($formId)) {
                if (!in_array($formId, $array)) {
                    $array[] = $formId;
                }
                $form = baformsHelper::drawHTMLPage($formId);
                $type = baformsHelper::getType($formId);
                if ($type['button_type'] == 'link' && $type['display_popup'] == 1) {
                    $body = @preg_replace("|\[forms ID=".$formId."\]|", '<a style="display:none" class="baform-replace">[forms ID='.$formId.']</a>', $body, 1);
                    $body = str_replace('</body>', $form.'</body>', $body);
                } else {
                    $body = @preg_replace("|\[forms ID=".$formId."\]|", addcslashes($form, '\\$'), $body, 1);
                }
            }
        }
        if (!empty($array)) {
            $body = baformsHelper::addScripts($array).$body;
        }

        return $body;
    }
}