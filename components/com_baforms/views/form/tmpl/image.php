<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

$settings = explode('_-_', $element->settings);
$options = explode(';', $settings[3]);
$className = $element->custom;
$data = '';
if ($options[4] == 1) {
    $className .= ' ba-lightbox-image';
}
if ($options[4] == 1) {
    $data .= 'data-lightbox="'.$options[5].'"';
}
ob_start();
?>
<div class="ba-image tool <?php echo $className; ?>" style="text-align: <?php echo $options[1]; ?>">
    <img src="<?php echo JUri::root().$options[0]; ?>" class="ba-image" alt="<?php echo htmlspecialchars($options[3], ENT_QUOTES); ?>"
        style="width: <?php echo $options[2]; ?>%;" <?php echo $data; ?>>
</div>
<?php
$out = ob_get_contents();
ob_end_clean();