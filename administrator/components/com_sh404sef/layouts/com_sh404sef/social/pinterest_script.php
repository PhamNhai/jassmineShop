<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date        2016-10-31
 */

// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC'))
{
	die('Direct Access to this location is not allowed.');
}

?>
<!-- Pinterest script -->
<script type='text/javascript'>

	(function () {
		window.PinIt = window.PinIt || {loaded: false};
		if (window.PinIt.loaded) return;
		window.PinIt.loaded = true;
		function async_load() {
			var s = document.createElement('script');
			s.type = 'text/javascript';
			s.async = true;
			if (window.location.protocol == 'https:')
			//s.src = 'https://assets.pinterest.com/js/pinit.js';
				s.src = '<?php echo JURI::base(); ?>media/com_sh404sef/pinterest/pinit.js';
		else
			//s.src = 'http://assets.pinterest.com/js/pinit.js';
			s.src = '<?php echo JURI::base(); ?>media/com_sh404sef/pinterest/pinit.js';
			var x = document.getElementsByTagName('script')[0];
			x.parentNode.insertBefore(s, x);
		}

		if (window.attachEvent)
			window.attachEvent('onload', async_load);
		else
			window.addEventListener('load', async_load, false);
	})();
</script>
<!-- End of Pinterest script -->
