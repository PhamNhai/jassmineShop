<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;
// load tooltip behavior
JHtml::_('behavior.tooltip');

$print = explode('_-_', $this->print);
?>
<link rel="stylesheet" href="components/com_baforms/assets/css/ba-admin.css" type="text/css"/>
<script type="text/javascript">
	jQuery(document).ready(function(){
        window.print();
    });
</script>
<h1><?php echo $this->printTitle->title; ?></h1><br>
<p><?php echo $this->printTitle->date_time; ?></p>
<div class="row-fluid">
    <div class="span6">
        <table id="submission-data" class="table table-striped">
            <?php foreach ($print as $message) { ?>
            <?php $message = explode('|-_-|', $message); ?>
            <?php if (!empty($message) && isset($message[2]) && $message[2] != 'terms') { ?>
            <tr>
                <td style="width:30%"><?php echo $message[0]; ?></td>
                <td>
                    <?php if ($message[2] != 'upload') { ?>
                        <?php $message[1] = str_replace('<br>', '', $message[1]); ?>
                        <textarea readonly><?php echo $message[1]; ?></textarea>
                    <?php } else { ?>
                        <a target="_blank" href="../images/baforms/<?php echo $message[1]; ?>">View Uploaded File</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </table>
    </div>
</div>