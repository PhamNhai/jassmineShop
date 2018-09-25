<?php
/**
* @version 1.1
* @package OS CCK
* @copyright 2015 OrdaSoft
* @author 2015 Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @description OrdaSoft Content Construction Kit
*/

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

class qqUploadedFileXhr
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
     */
    function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()) {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    function getName()
    {
        return protectInjectionWithoutQuote('qqfile');
    }

    function getSize()
    {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
            return false;
        }
        return true;
    }

    function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    function getSize()
    {
        return $_FILES['qqfile']['size'];
    }

}

function toBytes($val)
    {
        if (empty($val)){
            return 0;
        }

        $val = trim($val);
        preg_match('#([0-9]+)[\s]*([a-z]+)#i', $val, $matches);
        $last = '';

        if (isset($matches[2])){
            $last = $matches[2];
        }

        if (isset($matches[1])){
            $val = (int) $matches[1];
        }

        switch (strtolower($last)){
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }

        return (int) $val;
    }

/**********************************************************************************************************************/
/**********************************************************************************************************************/

$jpath = explode('/', dirname(__FILE__));
for ($i = 1; $i <= 3; $i++) {
    unset($jpath[count($jpath) - 1]);
}

if (!defined('_JDEFINES')) {
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('administrator');


if (isset($_GET['qqfile'])) {
    $file = new qqUploadedFileXhr();
} elseif (isset($_FILES['qqfile'])) {
    $file = new qqUploadedFileForm();
} else {
    $file = false;
}

$pathinfo = pathinfo($file->getName());
$filename = JApplication::stringURLSafe($pathinfo['filename']);

$ext = $pathinfo['extension'];

// Max size to upload (10MB)
// $sizeLimit = 2 * 1024 * 1024;
$postSize = toBytes(ini_get('post_max_size'));
$uploadSize = toBytes(ini_get('upload_max_filesize'));

// allowed extensions to upload
// $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
$response = array('success' => false, 'message' => '');
$moduleID = JRequest::getInt('id', 0);
$dir = JPATH_ROOT . '/images';

$user = JFactory::getUser();
if(!$file) {
    $response['message'] = "No files are found!";
} else if ($file->getSize() == 0) {
    $response['message'] = "File is empty, check your file and try again";
} 
// else if ($file->getSize() > $sizeLimit) {
//     $response['message'] = "File is too largest";
//}
 else if ($uploadSize < $file->getSize()) {
    $response['message'] = "Size of {$file->getSize()} is too large";
} else if (!is_writable($dir)) {
    $response['message'] = "Directory {$dir} is not writable";
} 
// else if (!in_array(strtolower($ext), $allowedExtensions)) {
//     $response['message'] = "Invalid extension, allowed: " . implode(", ", $allowedExtensions);
// } 
else {

    require_once JPATH_BASE . '/../components/com_os_cck/functions.php';


    $dir = $dir . '/com_os_cck' . $moduleID;
    if (!file_exists($dir) || !is_dir($dir)) mkdir($dir);
    if (!file_exists($dir . '/original') || !is_dir($dir)) mkdir($dir . '/original');
    if (!file_exists($dir . '/thumbnail') || !is_dir($dir)) mkdir($dir . '/thumbnail');
    // for not replace files
    $i = '';
    while (file_exists($dir . "/original/{$filename}{$i}.{$ext}")) {
        $i++;
    }
    $filename = "{$filename}{$i}.{$ext}";

    if (!$file->save("{$dir}/original/{$filename}")) {
        $response['message'] = "Can't save file here: {$dir}/original/{$filename}";
    } else {
        $imagesize = getimagesize("{$dir}/original/{$filename}", $imageinfo);
        $mime = $imagesize['mime'];

        resize_img($dir . "/original/{$filename}", $dir . "/thumbnail/{$filename}", 640, 480);
        //resize_img($dir . "/original/{$filename}", $dir . "/original/{$filename}", 800, 600);
        $response['success'] = true;
        $response['file'] = strtolower($filename);
    }
}
echo json_encode($response);
