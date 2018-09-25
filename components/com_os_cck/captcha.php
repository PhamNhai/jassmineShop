<?php

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/
if (!class_exists('PWImageCCK')) {
class PWImageCCK
{

    public static $image_show_string = 'foo';

    function set_show_string($string)
    {
        self::$image_show_string = $string;
    }

    function get_show_string()
    {
        return self::$image_show_string;
    }

    function get_show_image($fact, $bcol, $fcol)
    {

        $font = 5;
        $cosrate = rand(10, 19);
        $sinrate = rand(10, 18);

        $charwidth = imagefontwidth($font);
        $charheight = imagefontheight($font);
        $width = (strlen(self::$image_show_string) + 2) * $charwidth;
        $height = 2 * $charheight;

        $im = imagecreatetruecolor($width, $height) or die("Cannot Initialize new GD image stream");
        $im2 = imagecreatetruecolor($width * $fact, $height * $fact);
        $bcol = imagecolorallocate($im, $bcol[0], $bcol[1], $bcol[2]);
        $fcol = imagecolorallocate($im, $fcol[0], $fcol[1], $fcol[2]);

        imagefill($im, 0, 0, $bcol);
        imagefill($im2, 0, 0, $bcol);

        $dotcol = imagecolorallocate($im, (abs($this->getR($fcol) - $this->getR($bcol))) / 2.5, (abs($this->getG($fcol) - $this->getG($bcol))) / 2.5, (abs($this->getB($fcol) - $this->getB($bcol))) / 2.5);

        $dotcol2 = imagecolorallocate($im, (abs($this->getR($fcol) - $this->getR($bcol))) / 1.5, (abs($this->getG($fcol) - $this->getG($bcol))) / 1.5, (abs($this->getB($fcol) - $this->getB($bcol))) / 3.5);

        $linecol = imagecolorallocate($im, (abs($this->getR($fcol) - $this->getR($bcol))) / 2.4, (abs($this->getG($fcol) - $this->getG($bcol))) / 2.1, (abs($this->getB($fcol) - $this->getB($bcol))) / 2.5);

        for ($i = 0; $i < $width; $i = $i + rand(1, 5)) {
            for ($j = 0; $j < $height; $j = $j + rand(1, 5)) {
                imagesetpixel($im, $i, $j, $dotcol);
            }
        }

        imagestring($im, $font, $charwidth, $charheight / 2, self::$image_show_string, $fcol);

        for ($j = 0; $j < $height * $fact; $j = $j + rand(3, 6)) {
            imageline($im2, 0, $j, $width * $fact, $j, $linecol);
        }

        for ($i = 0; $i < $width * $fact; $i = $i + rand(4, 9)) {
            imageline($im2, $i, 0, $i, $height * $fact, $linecol);
        }

        for ($i = 0; $i < $width * $fact; $i++) {
            for ($j = 0; $j < $height * $fact; $j++) {
                $x = abs(((cos($i / $cosrate) * 5 + sin($j / $sinrate * 2) * 2 + $i) / $fact)) % $width;
                $y = abs(((sin($j / $sinrate) * 5 + cos($i / $cosrate * 2) * 2 + $j) / $fact)) % $height;
                $col = imagecolorat($im, $x, $y);
                if ($col != $bcol)
                    imagesetpixel($im2, $i, $j, $col);
            }
        }

        for ($j = 0; $j < $height * $fact; $j = $j + rand(1, 5)) {
            for ($i = 0; $i < $width * $fact; $i = $i + rand(1, 5)) {
                imagesetpixel($im2, $i, $j, $dotcol2);
            }
        }

        ob_end_clean();
        ob_start();

        header("Content-type: image/png");
        imagepng($im2);

        imagedestroy($im);
        imagedestroy($im2);
    }

    function PWImage()
    {

    }

    //functions to extract RGB values from combined 24bit color value
    function getR($col)
    {
        return (($col >> 8) >> 8) % 256;
    }

    function getG($col)
    {
        return ($col >> 8) % 256;
    }

    function getB($col)
    {
        return $col % 256;
    }

}

}
