<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

class pkg_baformsInstallerScript
{
    public function install($parent) {}

	public function uninstall($parent)
    {
        $dir = JPATH_ROOT . '/images/baforms';
        
        function removeDirectory($dir) {
            if ($objs = glob($dir."/*")) {
                foreach($objs as $obj) {
                    is_dir($obj) ? removeDirectory($obj) : unlink($obj);
                }
            }
            rmdir($dir);
        }
        
        if (file_exists($dir) || is_dir($dir)) {
            removeDirectory($dir);
        }
    }

	public function update($parent) {}

	public function preflight($type, $parent) {}

    public function postflight($type, $parent) {
        $db = JFactory::getDbo();
		$query = $db->getQuery(true);
        $query->update('#__extensions')
            ->set('enabled = 1')
            ->where('element='.$db->quote('baforms'))
            ->where('folder='.$db->quote('editors-xtd'));
        $db->setQuery($query);
		$db->execute();
        $query = $db->getQuery(true);
        $query->update('#__extensions')
            ->set('enabled = 1')
            ->where('element='.$db->quote('baforms'))
            ->where('folder='.$db->quote('system'));
        $db->setQuery($query);
		$db->execute();
		$conf = JFactory::getConfig();
		$options = array( 'defaultgroup' => '', 'storage' => $conf->get('cache_handler', ''),
						  'caching' => true, 'cachebase' => $conf->get('cache_path', JPATH_SITE . '/cache') );
	  	$cache = JCache::getInstance('', $options);
	  	$data = $cache->getAll();
	  	if ($data) {
	  		foreach ($data as $item) {
	  			$cache->clean($item->group);
	  		}
	  	}
		$cache = JFactory::getCache('');
		$cache->gc();
    }
}