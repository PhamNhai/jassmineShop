<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

?>
<div id="global-options" class="modal hide ba-modal-md" style="display:none">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">x</a>
        <h3><?php echo JText::_('FORM_SETTINGS') ?></h3>
    </div>
    <div class="modal-body">
        <div id="global-tabs">
            <ul>
                <li><a href="#form-submission"><?php echo JText::_('FORM_SUBMISSION') ?></a></li>
                <li><a href="#emails"><?php echo JText::_('EMAIL_NOTIFICATIONS') ?></a></li>
                <li><a href="#auto-reply"><?php echo JText::_('EMAIL_REPLY') ?></a></li>
            </ul>
            <div id="form-submission">
                <label><?php echo JText::_('DISPLAY_TITLE') ?></label>
                <input type="hidden" name="jform[display_title]" value="0">
                <?php echo baformsHelper::defaultCheckboxes('display_title', $this->form); ?>
                <label><?php echo JText::_('DISPLAY_SUBMIT') ?></label>
                <input type="hidden" name="jform[display_submit]" value="0">
                <?php echo baformsHelper::defaultCheckboxes('display_submit', $this->form); ?>
                <label><?php echo JText::_('ALLOW_CAPTCHA') ?></label>
                <?php echo $this->form->getInput('alow_captcha'); ?>
                <label><?php echo JText::_('MESSAGE_BG') ?></label>
                <input type="text" id="message_bg">
                <?php echo $this->form->getInput('message_bg_rgba'); ?>
                <label><?php echo JText::_('MESSAGE_COLOR') ?></label>
                <input type="text" id="message_color">
                <?php echo $this->form->getInput('message_color_rgba'); ?>
                <label><?php echo JText::_('LIGHTBOX_BG') ?></label>
                <input type="text" id="dialog_color">
                <?php echo $this->form->getInput('dialog_color_rgba'); ?>
                <label><?php echo JText::_('SENT_MESSAGE') ?></label>
                <?php echo $this->form->getInput('sent_massage'); ?>
                <label><?php echo JText::_('ERROR_MESSAGE') ?></label>
                <?php echo $this->form->getInput('error_massage'); ?>
                <label><?php echo JText::_('CHECK_IP') ?></label>
                <input type="hidden" name="jform[check_ip]" value="0">
                <?php echo $this->form->getInput('check_ip'); ?>
                <label><?php echo JText::_('REIDRECTION_URL') ?></label>
                <?php echo $this->form->getInput('redirect_url'); ?>
                <input type="hidden" name="jform[save_continue]" value="0">
                <label>
                    <span>
                        <?php echo JText::_('LOAD_JQUERY') ?>
                    </span>
                    <span class="ba-tooltip">
                        <?php echo JText::_('LOAD_JQUERY_TOOLTIP'); ?>
                    </span>
                </label>
                <input type="hidden" name="jform[load_jquery]" value="0">
                <?php echo baformsHelper::defaultCheckboxes('load_jquery', $this->form); ?>
            </div>
            <div id="emails">
                <label><?php echo JText::_('EMAIL_RECIPIENT') ?></label>
                <?php echo $this->form->getInput('email_recipient'); ?>
                <label><?php echo JText::_('EMAIL_SUBJECT') ?></label>
                <?php echo $this->form->getInput('email_subject'); ?>
                <label><?php echo JText::_('REPLY_TO_SUBMITTER') ?></label>
                <input type="hidden" name="jform[add_sender_email]" value="0">
                <?php echo $this->form->getInput('add_sender_email'); ?>
            </div>
            <div id="auto-reply">
                <label><?php echo JText::_('SENDER_NAME') ?></label>
                <?php echo $this->form->getInput('sender_name'); ?>
                <label><?php echo JText::_('SENDER_EMAIL') ?></label>
                <?php echo $this->form->getInput('sender_email'); ?>
                <label><?php echo JText::_('EMAIL_SUBJECT') ?></label>
                <?php echo $this->form->getInput('reply_subject'); ?>
                <label><?php echo JText::_('EMAIL_BODY') ?></label>
                <?php echo $this->form->getInput('reply_body'); ?>
                <label><?php echo JText::_('COPY_SUBMITED_DATA'); ?></label>
                <input type="hidden" name="jform[copy_submitted_data]" value="0">
                <?php echo $this->form->getInput('copy_submitted_data'); ?>
            </div>
            <div id="popup" style="display: none;">
                <input type="hidden" name="jform[display_popup]" value="0">
                <label><?php echo JText::_('MODAL_WIDTH') ?>:</label>
                <?php echo $this->form->getInput('modal_width'); ?>
                <label><?php echo JText::_('LABEL') ?></label>
                <?php echo $this->form->getInput('button_lable'); ?>
                <label><?php echo JText::_('TYPE') ?></label>
                <?php echo $this->form->getInput('button_type'); ?>
                <label><?php echo JText::_('POSITION') ?></label>
                <?php echo $this->form->getInput('button_position'); ?>
                <label><?php echo JText::_('BUTTON_BACKGROUND') ?></label>
                <input type="text" id="button_bg">
                <?php echo $this->form->getInput('button_bg'); ?>
                <label><?php echo JText::_('BUTTON_COLOR') ?></label>
                <input type="text" id="button_color">
                <?php echo $this->form->getInput('button_color'); ?>
                <label><?php echo JText::_('FONT_SIZE') ?></label>
                <?php echo $this->form->getInput('button_font_size'); ?>
                <label><?php echo JText::_('TITLE_WEIGHT') ?></label>
                <?php echo $this->form->getInput('button_weight'); ?>
                <input type="radio" name="popup-font-weight" value ="normal"><?php echo JText::_('NORMAL') ?>
                <input type="radio" name="popup-font-weight" value ="bold"><?php echo JText::_('BOLD') ?>
                <label><?php echo JText::_('BORDER_RADIUS') ?></label>
                <?php echo $this->form->getInput('button_border'); ?>
            </div>
            <div id="payment" style="display: none;">
                <label><?php echo JText::_('DISPLAY_TOTAL') ?></label>
                <input type="hidden" name="jform[display_total]" value="0">
                <label><?php echo JText::_('DISPLAY_CART') ?></label>
                <input type="hidden" name="jform[display_cart]" value="0">
                <label><?php echo JText::_('CURRENCY_CODE') ?></label>
                <?php echo $this->form->getInput('currency_code'); ?>
                <label><?php echo JText::_('CURRENCY_SYMBOL') ?></label>
                <?php echo $this->form->getInput('currency_symbol'); ?>
                <label><?php echo JText::_('CURRENCY_POSITION') ?></label>
                <?php echo $this->form->getInput('currency_position'); ?>
                <label><?php echo JText::_('PAYMENT_METHODS') ?></label>
                <?php echo $this->form->getInput('payment_methods'); ?>
                <div class="paypal-login">
                    <label><?php echo JText::_('PAYPAL_EMAIL') ?></label>
                    <?php echo $this->form->getInput('paypal_email'); ?>
                </div>
                <div class="2checkout">
                    <label><?php echo JText::_('ACCOUNT_NUMBER') ?></label>
                    <?php echo $this->form->getInput('seller_id'); ?>
                </div>
                <div class="skrill">
                    <label><?php echo JText::_('SKRILL_EMAIL') ?></label>
                    <?php echo $this->form->getInput('skrill_email'); ?>
                </div>
                <div class="webmoney">
                    <label><?php echo JText::_('WEBMONEY_PURSE') ?></label>
                    <?php echo $this->form->getInput('webmoney_purse'); ?>
                </div>
                <div class="custom-payment">
                    <label><?php echo JText::_('LABEL') ?></label>
                    <?php echo $this->form->getInput('custom_payment'); ?>
                </div>
                <div class="payu">
                    <label><?php echo JText::_('API_KEY') ?></label>
                    <?php echo $this->form->getInput('payu_api_key'); ?>
                    <label><?php echo JText::_('MERCHANT_ID') ?></label>
                    <?php echo $this->form->getInput('payu_merchant_id'); ?>
                    <label><?php echo JText::_('ACOUNT_ID') ?></label>
                    <?php echo $this->form->getInput('payu_account_id'); ?>
                </div>
                <div class="payu-biz">
                    <label>Merchant key</label>
                    <?php echo $this->form->getInput('payu_biz_merchant'); ?>
                    <label>Salt</label>
                    <?php echo $this->form->getInput('payu_biz_salt'); ?>
                </div>
                <div class="stripe">
                    <label><?php echo JText::_('API_KEY') ?></label>
                    <?php echo $this->form->getInput('stripe_api_key'); ?>
                    <label><?php echo JText::_('SECRET_KEY') ?></label>
                    <?php echo $this->form->getInput('stripe_secret_key'); ?>
                    <label><?php echo JText::_('IMAGE') ?></label>
                    <a class="modal-trigger" data-modal="stripe-image-dialog" href="#"><?php echo JText::_('SELECT'); ?></a>
                    <a class="clear-image-field" data-field="jform_stripe_image" href="#">
                        <i class="zmdi zmdi-close"></i>
                    </a>
                    <?php echo $this->form->getInput('stripe_image'); ?>
                    <label><?php echo JText::_('NAME') ?></label>
                    <?php echo $this->form->getInput('stripe_name'); ?>
                    <label><?php echo JText::_('DESCRIPTION') ?></label>
                    <?php echo $this->form->getInput('stripe_description'); ?>
                </div>
                <div class="ccavenue">
                    <label><?php echo JText::_('MERCHANT_ID') ?></label>
                    <?php echo $this->form->getInput('ccavenue_merchant_id'); ?>
                    <label><?php echo JText::_('WORKING_KEY') ?></label>
                    <?php echo $this->form->getInput('ccavenue_working_key'); ?>                        
                </div>
                <div class="mollie">
                    <label><?php echo JText::_('API_KEY') ?></label>
                    <?php echo $this->form->getInput('mollie_api_key'); ?>
                </div>
                <label><?php echo JText::_('ENVIRONMENT') ?></label>
                <?php echo $this->form->getInput('payment_environment'); ?>
                <label><?php echo JText::_('RETURN_URL') ?></label>
                <?php echo $this->form->getInput('return_url'); ?>
                <label><?php echo JText::_('CANCEL_URL') ?></label>
                <?php echo $this->form->getInput('cancel_url'); ?>
                <div class="multiple-payment">
                    <label>
                        <span>
                            <?php echo JText::_('MULTIPLE_PAYMENT') ?>
                        </span>
                        <span class="ba-tooltip">
                            <?php echo JText::_('MULTIPLE_PAYMENT_TOOLTIP') ?>
                        </span>
                    </label>
                    <input type="hidden" name="jform[multiple_payment]" value="0">
                    <?php echo $this->form->getInput('multiple_payment'); ?>
                </div>                    
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="ba-btn-primary" data-dismiss="modal"><?php echo JText::_('APPLY') ?></a>
        <a href="#" class="ba-btn" data-dismiss="modal"><?php echo JText::_('CLOSE') ?></a>
    </div>
</div>