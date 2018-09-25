<?php
/**
* @package   Bagrid
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

if (isset($_POST['login']) && isset($_POST['password'])) {
    $url = 'http://www.balbooa.com/demo/index.php?option=com_baupdater&task=baforms.checklicense';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    $data = curl_exec($ch);
    curl_close($ch);
    echo $data;exit;
}

$url = 'http://www.balbooa.com/demo/index.php?option=com_baupdater&view=baforms&layout=default_ssl';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);
echo $data;exit;