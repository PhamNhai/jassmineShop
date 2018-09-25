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

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass( 'text');

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 */
class JFormFieldShlegend extends JFormField
{
  /**
   * The form field type.
   *
   * @var    string
   * @since  11.1
   */
  protected $type = 'shlegend';

  /**
   * Method to get certain otherwise inaccessible properties from the form field object.
   *
   * @param   string  $name  The property name for which to the the value.
   *
   * @return  mixed  The property value or null.
   *
   */
  public function __get($name)
  {

    switch ($name)
    {
      case 'element':
        return $this->$name;
        break;
    }

    $value = parent::__get( $name);
    return $value;
  }

  public function getInput() {

  	return '';
  }

  public function getLabel() {

  	return '<legend>' . JText::_($this->element['label']) . '</legend>';
  }

}
