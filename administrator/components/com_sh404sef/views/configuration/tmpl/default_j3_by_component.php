<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date		2016-10-31
 */

defined('JPATH_BASE') or die;

?>

<div class="container-fluid">
<?php
$cycler = ShlSystem_Cycle::getInstance('bycomponent', $step = $this->byComponentItemsCount, 0);
foreach ($this->form->getFieldset($this->currentFieldset->name) as $field) :
	$isNewLine = $cycler->every();
	if ($isNewLine) :
	?>
		<div class="control-group by-components">
		<?php if (!$field->hidden) : ?>
			<div class="shlegend-label">
				<div class="control-label">
					<?php echo $field->label; ?>
				</div>
			</div>
			<div class="controls">
		<?php
		endif;
	endif;
	if (!$isNewLine) :
		$o = '';
		$o .= $field->input;
		$o .= '<div rel="tooltip" class="shinfo-icon-wrapper" title="' . JText::_( $field->description) . '"><i class="icon-question-sign"></div>';
		$o .= '</i>';
		echo $o;
		$element = $field->element;
		if (!empty($element['additionaltext'])) :
		?><span class = "sh404sef-additionaltext">'<?php echo (string) $element['additionaltext']; ?></span>
		<?php
		endif;
	endif;
	if ($isNewLine) :
		?>
			</div>
		</div>
	<?php
	endif;
endforeach;
?>
</div>
