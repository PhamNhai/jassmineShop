/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.1.3465
 * @date    2016-10-31
 */

function shAjaxHandler(task, options, closewindow) {

  var form = jQuery('#adminForm');
	jQuery('#adminForm input[name=task]').val(task);
	jQuery('#adminForm input[name=format]').val('raw');
	jQuery('#adminForm input[name=shajax]').val('1');

	// Create a progress indicator
    weeblrApp.spinner.start('toolbar-sh404sef-spinner');

    // clear previous message
    var msgBox = jQuery("#sh-message-box").empty();

	// Set the options of the form"s Request handler.
	var onSuccessFn = function(response) {
	  
	  // restore form
	  jQuery('#adminForm input[name=task]').val('');
	  jQuery('#adminForm input[name=format]').val('html');
	  jQuery('#adminForm input[name=shajax]').val('0');
	  
		//alert(response);
		var root, status, message;
		try {
			root = response.documentElement;
			status = root.getElementsByTagName("status").item(0).firstChild.nodeValue;
			message = "<div class='alert alert-success'>" + root.getElementsByTagName("message").item(0).firstChild.nodeValue + '</div>';
		} catch (err) {
			status = 'failure';
			message = "<div class='alert alert-error'>Sorry, something went wrong on the server while performing this action. Please retry or cancel.</div>";
		}

		// remove progress indicator
        weeblrApp.spinner.stop('toolbar-sh404sef-spinner');

		// insert results
		if (status == "success") {
			msgBox.html(message);
			if (closewindow) {
				setTimeout("shlBootstrap.closeModal()", 1500);
			} else {
				setTimeout("jQuery('#sh-message-box').empty()", 3000);
			}
		} else if (status == 'redirect') {
			setTimeout("parent.window.location='" + message + "';", 100);
			shlBootstrap.closeModal();
		} else {
			msgBox.html(message);
			setTimeout("jQuery('#sh-message-box').empty();", 5000);
		}

	};

	// Send the form.
	jQuery.post('index.php', form.serialize())
  .always(onSuccessFn);
};
