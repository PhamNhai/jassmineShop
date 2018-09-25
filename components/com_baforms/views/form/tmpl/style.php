<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

if (strpos($form[0]->theme_color, '#') === false) {
    $theme_color = explode(',', $form[0]->theme_color);
    $theme_color[3] = '1)';
    $theme_color = implode(',', $theme_color);
    $shadow_color = explode(',', $form[0]->theme_color);
    $shadow_color[3] = '0.3)';
    $shadow_color = implode(',', $shadow_color);
} else {
    $theme_color = $shadow_color = $form[0]->theme_color;
}
ob_start();
?>
<style type="text/css">
.calendar thead td.title {
    background: <?php echo $theme_color; ?> !important;
}
#baform-<?php echo $id; ?> .ba-form input:focus,
#baform-<?php echo $id; ?> .ba-form textarea:focus,
#baform-<?php echo $id; ?> .ba-form select:focus,
#baform-<?php echo $id; ?> .ba-form input[type="radio"]:checked + span:after,
#baform-<?php echo $id; ?> .ba-form input[type="checkbox"]:checked + span:after,
#baform-<?php echo $id; ?> .ba-form input[type="radio"]:hover + span:before,
#baform-<?php echo $id; ?> .ba-form input[type="checkbox"]:hover + span:before,
#baform-<?php echo $id; ?> .ba-form .ba-input-image:hover input + img + span,
#baform-<?php echo $id; ?> .ba-form .ba-input-image input:checked + img + span {
    border-color: <?php echo $form[0]->theme_color; ?> !important;
}
#baform-<?php echo $id; ?> .ba-form .ba-input-image:hover {
    border: 2px solid <?php echo $form[0]->theme_color; ?>;
}
.calendar thead td.title:after {
    border-color: <?php echo $theme_color; ?> !important;
}
.calendar thead td.title,
.calendar thead tr:first-child {
    background: <?php echo $theme_color; ?> !important;
}
#baform-<?php echo $id; ?> .ba-form .slider-handle:active,
#baform-<?php echo $id; ?> .ba-form .slider-handle:hover {
     box-shadow: 0px 0px 0px 10px <?php echo $shadow_color; ?> !important;
     -webkit-box-shadow: 0px 0px 0px 10px <?php echo $shadow_color; ?> !important;
}
#baform-<?php echo $id; ?> .ba-form input[type="radio"]:checked + span:after,
#baform-<?php echo $id; ?> .ba-form input[type="checkbox"]:checked + span:after,
#baform-<?php echo $id; ?> .ba-form .slider-handle,
.calendar .daysrow .day.selected {
    background: <?php echo $form[0]->theme_color; ?> !important;
}
#baform-<?php echo $id; ?> .ba-form .slider-track,
#baform-<?php echo $id; ?> .ba-form .ba-input-image input:checked + img + span {
    background-color: <?php echo $form[0]->theme_color; ?> !important;
}
.calendar thead .weekend {
    color: <?php echo $form[0]->theme_color; ?> !important;
}
</style>
<?php
$out = ob_get_contents();
ob_end_clean();