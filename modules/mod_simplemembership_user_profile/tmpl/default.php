<?php
/*
*
* @package simpleMembership
* @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); ;
* Homepage: http://www.ordasoft.com
* Updated on January, 2014
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

    <script type="text/javascript">
   
     /* for hide label */
      if (window.jQuery) {
    
        jQuery(document).ready(function () {

            jQuery("#mod_simple_membership_user_profile label").removeClass('hasTooltip').attr('data-original-title', '');

        });

      }

    </script>

  <div id="mod_simple_membership_user_profile">
    <?php
    $joom_user = new JUser();
    $joom_user->load($user_id);
    $dispatcher = JDispatcher::getInstance();
    JPluginHelper::importPlugin('user');
    JForm::addFormPath($mosConfig_absolute_path.'/components/com_simplemembership/forms');
    $user_profile_form = JForm::getInstance('com_users.module', 'registration');
    $results2 = $dispatcher->trigger('onContentPrepareData', array('com_users.module', $joom_user));
    $results = $dispatcher->trigger('onContentPrepareForm', array($user_profile_form, $joom_user));
    $user_profile_form->bind($joom_user);
    $fields=$user_profile_form->getFieldset('profile');
    $profile_image=$user_profile_form->getValue('file','profile','');
    // print_r($profile_image);exit;
    if($view_vh == 0){ ?>
      <table class="profileTable">
      <?php 
      if($profile_image !=''){?>
        <tr>
        <td colspan="2">
          <img src="<?php echo JURI::base().$user_profile_form->getValue('file','profile','')?>" style="max-width:200px;">
        </td>
        </tr>
      <?php
      }else{
        ?>
        <tr>
          <td colspan="2">
            <img src="<?php echo $mosConfig_live_site . '/components/com_simplemembership/images/default.gif' ?>" style="max-width:200px;">
          </td>
        </tr>
      <?php
      }
      ?>
        <tr>
        <td colspan="2">
   <?php if ($user_id != $my->id) { ?>
            <a href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&Itemid='.
             $Itemid_simp.'&task=showUsersProfile&userId='.$user_id.''); ?>" >
            <?php echo "<b>" . $joom_user->name . "</b>"; ?></a>
  <?php  } else { ?>
          <a href="<?php echo sefRelToAbs('index.php?option=com_simplemembership&view='.
            'my_account&layout=myaccount&Itemid='.$Itemid_simp.''); ?>" >
            <?php echo "<b>" . $joom_user->name . "</b>"; ?></a>
  <?php  } ?>
        </td>
        </tr>
    <?php
      foreach($fields as $field){
        if($field->hidden) {
          //non
        }
        else{
          if($field->name != 'profile[file]' && $field->value !==''){
            ?>
            <tr>
            <td>
              <?php
              echo $field->label; ?>
            </td>
            <td>
              <?php
                echo $field->value;
              ?>
            </td>
            </tr>
          <?php
          }
        }
      }?>
      </table>
      <?php
    }
    else if($view_vh == 1){?>
      <table>
      <tr>
      <?php
      if($profile_image !=''){?>
        <td>
          <img src="<?php echo $mosConfig_live_site.$user_profile_form->getValue('file','profile','')?>" style="max-height:100px">
        </td>
      <?php
      }?>
        <td>
          <?php echo "<b>".$joom_user->name."</b>";?>
        </td>
      <?php
      foreach($fields as $field){
        if($field->hidden) {
          //non
        }
        else{
          if($field->name != 'profile[file]' && $field->value !==''){
            ?>
            <td><table><tr><td>
              <?php
              echo $field->label; ?>
            </td></tr>
            <tr><td>
              <?php
                echo $field->value;
              ?>
            </td></tr></table></td>
          <?php
          }
        }
      }
      ?>
      </tr>
      </table>
    <?php
    }
    ?>
  </div>
