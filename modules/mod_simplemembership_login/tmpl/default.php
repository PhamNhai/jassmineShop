<?php
/*
*
* @package simpleMembership
* @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); ; 
* Homepage: http://www.ordasoft.com
* Updated on January, 2014
*/

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) 
  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );


if (version_compare(JVERSION, "3.0.0", "ge")){
    // Post action
    $return_url = getReturnURL_SM2($params,$type);
    // Registration URL
    $registration_url = 'index.php?option=com_simplemembership&task=advregister';
    $action =  'index.php?option=com_users&amp;task=user.'.$type;
    if( $type == 'logout' ) : ?>
     <?php
     $return_url = getReturnURL_SM2($params,$type);
     ?>
     
    <div>
            <form action="<?php echo $action ?>" method="post" name="login" id="login" class="form-inline">
            <?php if( $params->get('greeting') ) : ?>
            <div><?php echo 'Hi' . ' ' . $name ?></div>
            <?php endif; ?>
              <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" /><br />
              <ul>
                  <li><a href="index.php?option=com_simplemembership&task=accdetail">Account detail</a></li>
                  <li><a href="index.php?option=com_simplemembership&task=getMail">Extend the group registration</a></li>
              </ul>
              <input type="hidden" name="op2" value="logout" />
              <input type="hidden" name="return" value="<?php echo $return_url ?>" />
              <input type="hidden" name="lang" value="english" />
              <?php echo JHtml::_('form.token');  ?>
              <input type="hidden" name="message" value="0" />
            </form>
    </div>
      
    <?php else : ?> 
    <div>
      <form action="<?php echo $action ?>" method="post" name="login" id="login">
        <?php if( $params->get('pretext') ) : ?>
        <?php echo $params->get('pretext'); ?>
        <br />
        <?php endif; ?>
        <div class="input-prepend">
                <span class="add-on">
                  <span class="icon-user hasTooltip" title="<?php echo JText::_('JGLOBAL_USERNAME') ?>"></span>
                   <label for="modlgn-username" class="element-invisible"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
                </span>
                <input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="User Name"/>
        </div>
        <br>
        <div class="input-prepend">
                <span class="add-on">
                  <span class="icon-lock hasTooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>">
                  </span>
                  <label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>
                  </label>
                </span>
                <input id="modlgn-passwd" type="password" name="password" class="input-small" 
                  tabindex="0" size="18" placeholder="Password"/>
        </div>
        <br />
        <button type="submit" tabindex="0" name="Submit" class="btn btn-primary">Login</button>

        <ul>
          <li>
            <label for="remember_vmlogin">Remember me</label>
            <input type="checkbox" name="remember" id="remember_vmlogin" value="yes" checked="checked" />
          </li>
          <li><a href="<?php echo $reset_url ?>">Lost Password</a></li>
          <?php if( $remind_url ) : ?>
          <li><a href="<?php echo $remind_url ?>">Forgot Login</a></li>
          <?php endif; ?>
          <?php if($usersConfig->get('allowUserRegistration') == '1' ) : ?>
          <li><a href="<?php echo $registration_url ?>">Create account</a></li>
          <?php endif; ?>
        </ul>
        <input type="hidden" value="login" name="op2" />
        <input type="hidden" value="<?php echo $return_url ?>" name="return" />
        <?php
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
        echo JHtml::_('form.token'); } else { ?>
        <input type="hidden" name="<?php echo $validate; ?>" value="1" />
        <?php } ?>
        <?php echo $params->get('posttext'); ?>
       </form>
    </div>
       
    <?php endif;
}else{
    // Post action
    $return_url = getReturnURL_SM($params,$type);
// Registration URL
$registration_url = 'index.php?option=com_simplemembership&task=advregister';
$action =  'index.php?option=com_users&amp;task=user.'.$type;

if( $type == 'logout' ) : ?>
 <?php $action =  'index.php?option=com_users&amp;task='.$type;?>
<div>
  <form action="<?php echo $action ?>" method="post" name="login" id="login" class="form-inline">
    <?php if( $params->get('greeting') ) : ?>
    <div><?php echo 'Hi' . ' ' . $name ?></div>
    <?php endif; ?>
    <input type="submit" name="Submit" class="button" value="Logout" />
    <br />
    <ul>
      <li><a href="index.php?option=com_simplemembership&task=accdetail">Account detail</a></li>
    </ul>
    <input type="hidden" name="op2" value="logout" />
    <input type="hidden" name="return" value="<?php echo $return_url ?>" />
    <input type="hidden" name="lang" value="english" />
    <input type="hidden" name="message" value="0" />
  </form>
</div>
  
<?php else : ?> 
<div>
  <form action="<?php echo $action ?>" method="post" name="login" id="login-form">
    <?php if( $params->get('pretext') ) : ?>
    <?php echo $params->get('pretext'); ?>
    <br />
    <?php endif; ?>
    <fieldset class="userdata">
      <p id="form-login-username">
        <label for="modlgn-username"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
        <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
      </p>
      <p id="form-login-password">
        <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
        <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
      </p>
      <br />
      <label for="remember_vmlogin">Remember me</label>
      <input type="checkbox" name="remember" id="remember_vmlogin" value="yes" checked="checked" />
      <br />
      <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
    </fieldset>
    <ul>
      <li><a href="<?php echo $reset_url ?>">Lost Password</a></li>
      <?php if( $remind_url ) : ?>
      <li><a href="<?php echo $remind_url ?>">Forgot Login</a></li>
      <?php endif; ?>
      <?php if($usersConfig->get('allowUserRegistration') == '1' ) : ?>
      <li><a href="<?php echo $registration_url ?>">Create account</a></li>
      <?php endif; ?>
      <li><a href="index.php?option=com_simplemembership&task=getMail">Extend the group registration</a></li>
    </ul>
    <input type="hidden" value="login" name="op2" />
    <input type="hidden" value="<?php echo $return_url ?>" name="return" />
                <?php
                if (version_compare(JVERSION, '3.0.0', 'ge')) {
                echo JHtml::_('form.token'); } else { ?>
    <input type="hidden" name="<?php echo $validate; ?>" value="1" />
                <?php } ?>
    <?php echo $params->get('posttext'); ?>
  </form>
</div>
   
<?php endif;
}
?>
<div style="text-align: center;"><a style="font-size: 10px;" href="http://ordasoft.com">Powered by OrdaSoft!</a></div>
