<?php
/**
 *
 * Customfilters default tmpl
 *
 * @package		customfilters
 * @author		Sakis Terz
 * @link		http://breakdesigns.net
 * @copyright	Copyright (c) 2012-2017 breakdesigns.net. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *				customfilters is free software. This version may have been modified
 *				pursuant to the GNU General Public License, and as distributed
 *				it includes or is derivative of works licensed under the GNU
 *				General Public License or other free or open source software
 *				licenses.
 * @version $Id: default.php 1 2011-11-14 19:21:00Z sakis $
 */

// no direct access
defined('_JEXEC') or die;

foreach($this->products as $p){
print_r($p->product_name);
}