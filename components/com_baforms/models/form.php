<?php
/**
* @package   BaForms
* @author    Balbooa http://www.balbooa.com/
* @copyright Copyright @ Balbooa
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/ 

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');
 
class baformsModelForm extends JModelAdmin
{
    private $letter;
    private $condition = array();
    private $condMap = array();
    private $mailchimpMap = array();
    private $emailStyle;
    private $submission;

    public function stripeCharges()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('stripe_secret_key')
            ->from('#__baforms_forms')
            ->where('`id` = '.$_POST['form_id']);
        $db->setQuery($query);
        $key = $db->loadResult();
        $token = $_POST['ba_token'];
        $total = $_POST['total'];
        $currency = $_POST['currency'];
        $token = json_decode($token);
        $array = array('amount' => $total, 'currency' => $currency,
            'card' => $token->id, 'description' => $token->email);
        $url = 'https://api.stripe.com/v1/charges';
        $langVersion = phpversion();
        $uname = php_uname();
        $ua = array('bindings_version' => '1.13.0', 'lang' => 'php',
            'lang_version' => $langVersion, 'publisher' => 'stripe', 'uname' => $uname);
        $headers = array('X-Stripe-Client-User-Agent: ' . json_encode($ua),
            'User-Agent: Stripe/v1 PhpBindings/1.13.0',
            'Authorization: Bearer ' . $key);
        $curl = curl_init();
        $opts[CURLOPT_POST] = 1;
        $opts[CURLOPT_POSTFIELDS] = $this->encode($array);
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 80;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_HTTPHEADER] = $headers;
        $opts[CURLOPT_SSL_VERIFYPEER] = false;
        curl_setopt_array($curl, $opts);
        $rbody = curl_exec($curl);
        echo $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        exit();
    }

    public function encode($arr, $prefix=null)
    {
        if (!is_array($arr))
            return $arr;
        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v))
                continue;
            if ($prefix && $k && !is_int($k))
                $k = $prefix."[".$k."]";
            else if ($prefix)
                $k = $prefix."[]";
            if (is_array($v)) {
                $r[] = $this->encode($v, $k, true);
            } else {
                $r[] = urlencode($k)."=".urlencode($v);
            }
        }

        return implode("&", $r);
    }

    public function setToken()
    {
        $obj = new stdClass();
        $obj->token = $_POST['ba_token'];
        $obj->data = $_POST['data'];
        $obj->expires = strtotime('+30 days');
        $obj->ip = $_SERVER['REMOTE_ADDR'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__baforms_tokens');
        $query->where('`token` = '.$db->quote($_POST['ba_token']));
        $db->setQuery($query);
        $result = $db->loadResult();
        if (empty($result)) {
            $db->insertObject('#__baforms_tokens', $obj);
        } else {
            $obj->id = $result;
            $db->updateObject('#__baforms_tokens', $obj, 'id');
        }

        return $obj->token;
    }

    public function removeToken($token)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->delete('#__baforms_tokens')
            ->where('`token` = ' .$db->quote($token));
        $db->setQuery($query);
        $db->execute();
    }

    public function saveContinue()
    {
        $id = $_POST['form_id'];
        $email = $_POST['ba_email'];
        $options = $this->getEmailOptions($id);
        $body = $this->getSaveContinue($id);
        $link = $_POST['ba_link'];
        $token = '<p><a href="'.$link.'" target="_blank">'.$link.'</a></p>';
        $msg = $body->save_continue_email;
        $msg = str_replace('[ba-form-token-link]', $token, $msg);
        $mailer = JFactory::getMailer();
        if (!empty($options[0]->sender_email)) {
            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $sender = array($options[0]->sender_email, $options[0]->sender_name);
            $mailer->setSender($sender);
            $mailer->setSubject($body->save_continue_subject);
            $mailer->addRecipient($email);
            $mailer->setBody($msg);
            $mailer->Send();
        }
    }

    public function getSaveContinue($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('save_continue_subject, save_continue_email')
            ->from('#__baforms_forms')
            ->where('`id` = '.$id);
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    public function getCheckSum($MerchantId, $Amount, $OrderId ,$URL, $WorkingKey)
    {
        $str = "$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);

        return $adler;
    }

    public function adler32($adler, $str)
    {
        $BASE = 65521;
        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for($i = 0; $i < strlen($str); $i++){
            $s1 = ($s1 + Ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
        }

        return $this->leftshift($s2, 16) + $s1;
    }
    
    public function leftshift($str, $num)
    {
        $str = DecBin($str);
        for($i = 0; $i < (64 - strlen($str)); $i++){
            $str = "0".$str;
        }
        for($i = 0; $i < $num; $i++){
            $str = $str."0";
            $str = substr($str, 1);
        }

        return $this->cdec($str);
    }
    
    public function cdec($num)
    {
        $dec = 0;
        for ($n = 0; $n < strlen($num); $n++){
            $temp = $num[$n];
            $dec = $dec + $temp*pow(2 , strlen($num) - $n - 1);
        }

        return $dec;
    }

    public function ccavenue()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('ccavenue_merchant_id, ccavenue_working_key, return_url')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $ccavenue = $db->loadObject();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $str = '';
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $label .= ' - ' .$value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = $row->str;
                                        break;
                                    }
                                }
                            }
                            $str .= $label. '; ';
                        }
                    }
                }                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $str .= $el. '; ';                        
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                break;
                            }
                        }
                    }
                    $str .= $element. '; ';
                }
                
            }
        }
        $merchant = $ccavenue->ccavenue_merchant_id;
        $working = $ccavenue->ccavenue_working_key;
        $return = $ccavenue->return_url;
        $order = strtotime(date('Y-m-d H:i:s'));
        $total = $_POST['ba_total'];
        $checksum = $this->getCheckSum($merchant, $total, $order, $return, $working);
        $array = array( "Merchant_Id" => $merchant, "Amount" => $total, "Order_Id" => $order,
            "Redirect_Url" => $return, "Checksum" => $checksum, 'Merchant_param' => $str);
        $this->save($_POST);
        ?>
        <form id="payment_form" name="payment_form" action="https://www.ccavenue.com/shopzone/cc_details.jsp" method="post">
            <?php foreach ($array as $key => $value) {
                echo '<input type="hidden" name="' .$key. '" value="' .htmlspecialchars($value). '" />';
            } ?>
        </form>
        <script type="text/javascript">
            document.payment_form.submit();
        </script>
        <?php 
        exit;
    }

    public function mollie()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('mollie_api_key, return_url')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $mollie = $db->loadObject();
        $str = '';
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $label .= ' - ' .$value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = $row->str;
                                        break;
                                    }
                                }
                            }
                            $str .= $label. '; ';
                        }
                    }
                }                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $str .= $el. '; ';                        
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                break;
                            }
                        }
                    }
                    $str .= $element. '; ';
                }
                
            }
        }
        $order_id = strtotime(date('Y-m-d H:i:s'));;
        $array = array(
            "amount"       => $_POST['ba_total'],
            "description"  => $str,
            "redirectUrl"  => $mollie->return_url,            
            "metadata"     => array(
                "order_id" => $order_id,
            ),
        );
        $ch = curl_init();
        $url = 'https://api.mollie.nl/v1/payments';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $version = array();
        $curl_version = curl_version();
        $version[] = "Mollie/1.5.1";
        $version[] =  "PHP/" . phpversion();
        $version[] =  "cURL/" . $curl_version["version"];
        $version[] =  $curl_version["ssl_version"];
        $user_agent = join(' ', $version);
        $request_headers = array(
            "Accept: application/json",
            "Authorization: Bearer ".$mollie->mollie_api_key,
            "User-Agent: {$user_agent}",
            "X-Mollie-Client-Info: " . php_uname(),
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        $request_headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        $body = curl_exec($ch);
        if (strpos(curl_error($ch), "certificate subject name 'mollie.nl' does not match target host") !== FALSE) {
            $request_headers[] = "X-Mollie-Debug: old OpenSSL found";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $body = curl_exec($ch);
        }
        if (curl_errno($ch)) {
            $message = "Unable to communicate with Mollie (".curl_errno($ch)."): " . curl_error($ch) . ".";
            curl_close($ch);
            echo '<input type="hidden" id="mollie-data" value="'.$message.'">';
        } else {
            curl_close($ch);
            $link = json_decode($body);
            if (isset($link->error)) {
                echo '<input type="hidden" id="mollie-data" value="'.$link->error->message.'">';
            } else {
                echo '<input type="hidden" id="mollie-data" value="'.$link->links->paymentUrl.'">';
                $this->save($_POST);
            }
        }
?>
            <script language="JavaScript">
                
                var intervalId = setInterval(sec,12);
                function sec()
                {
                    var msg = document.getElementById("mollie-data").value;
                    if (msg) {
                        clearInterval(intervalId);
                        window.parent.postMessage(msg, "*");
                    }
                    
                }
            </script>

<?php
        exit;
    }

    public function payubiz()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('return_url, cancel_url, payment_environment, payu_biz_merchant, payu_biz_salt')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $payu = $db->loadObject();
        if ($payu->payment_environment == 'sandbox') {
            $url = 'https://test.payu.in/_payment';
        } else {
            $url = 'https://secure.payu.in/_payment';
        }
        $str = '';
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $label .= ' - ' .$value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = $row->str;
                                        break;
                                    }
                                }
                            }
                            $str .= $label. '; ';
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $str .= $el. '; ';                        
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                break;
                            }
                        }
                    }
                    $str .= $element. '; ';
                }
                
            }
        }
        $email = '';
        $query = $db->getQuery(true);
        $query->select('id, settings')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $key => $item) {
            $settings = explode('_-_', $item->settings);
            if ($settings[2] == 'email') {
                $email = $_POST[$item->id];
                break;
            }
        }
        $hash = $payu->payu_biz_merchant.'|'.strtotime(date('Y-m-d H:i:s')).'|'.$_POST['ba_total'];
        $hash .= '|'.$str.'||'.$email.'|||||||||||'.$payu->payu_biz_salt;
        $hash = hash('sha512',$hash);
        $post = Array(
            "key" => $payu->payu_biz_merchant,
            "txnid" => strtotime(date('Y-m-d H:i:s')),
            "productinfo" => $str,
            "amount" => $_POST['ba_total'],
            "firstname" => '',
            "email" => $email,
            "hash" => $hash,
            "surl" => $payu->return_url,
            "furl" => $payu->cancel_url,
            'curl' => $payu->cancel_url,
            "udf1" => "",
            "udf2" => "",
            "udf3" => "",
            "udf4" => "",
            "udf5" => "",
        );
        $this->save($_POST);
        ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post">
            <?php foreach ($post as $key => $value) {
                echo '<input type="hidden" name="' .$key. '" value="' .htmlspecialchars($value). '" />';
            } ?>
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php 
        exit;
    }

    public function payu()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('currency_code, payu_api_key, payu_merchant_id, return_url,
                        payu_account_id, payment_environment')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $payu = $db->loadObject();
        if ($payu->payment_environment == 'sandbox') {
            $url = 'https://sandbox.gateway.payulatam.com/ppp-web-gateway';
        } else {
            $url = 'https://gateway.payulatam.com/ppp-web-gateway/';
        }
        $str = '';
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $label .= ' - ' .$value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = $row->str;
                                        break;
                                    }
                                }
                            }
                            $str .= $label. '; ';
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $str .= $el. '; ';                        
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                break;
                            }
                        }
                    }
                    $str .= $element. '; ';
                }
                
            }
        }
        $email = '';
        $query = $db->getQuery(true);
        $query->select('id, settings')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        foreach ($items as $key => $item) {
            $settings = explode('_-_', $item->settings);
            if ($settings[2] == 'email') {
                $email = $_POST[$item->id];
                break;
            }
        }
        $ref = strtotime(date('Y-m-d H:i:s'));
        $sig = $payu->payu_api_key. "~".$payu->payu_merchant_id."~";
        $sig .= $ref."~".$_POST['ba_total']."~".$payu->currency_code;
        $signature = md5($sig);
        $this->save($_POST);
        ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post">
            <input name="merchantId" type="hidden" value="<?php echo $payu->payu_merchant_id; ?>">
            <input name="accountId" type="hidden" value="<?php echo $payu->payu_account_id; ?>">
            <input name="description" type="hidden" value="<?php echo $str; ?>">
            <input name="referenceCode" type="hidden" value="<?php echo $ref; ?>">
            <input name="amount" type="hidden" value="<?php echo $_POST['ba_total']; ?>">
            <input name="tax" type="hidden" value="0">
            <input name="taxReturnBase" type="hidden" value="0">
            <input name="currency" type="hidden" value="<?php echo $payu->currency_code; ?>">
            <input name="signature" type="hidden" value="<?php echo $signature ?>">
            <?php if (isset($email)) { ?>
                <input name="buyerEmail" type="hidden" value="<?php echo $email; ?>">
            <?php } ?>
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php 
        exit;
    }


    public function webmoney()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('webmoney_purse')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $webmoney = $db->loadResult();
        $url = 'https://merchant.webmoney.ru/lmi/payment.asp';
        $str = '';
        $this->save($_POST);
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $label .= ' - ' .$value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = $row->str;
                                        break;
                                    }
                                }
                            }
                            $str .= $label. '; ';
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $str .= $el. '; ';                        
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                break;
                            }
                        }
                    }
                    $str .= $element. '; ';
                }
                
            }
        }
        ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post" accept-charset="windows-1251">
            <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo $_POST['ba_total']; ?>">
            <input type="hidden" name="LMI_PAYMENT_DESC" value="<?php echo $str; ?>">
            <input type="hidden" name="LMI_PAYEE_PURSE" value="<?php echo $webmoney; ?>">
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php 
        exit;
    }
    
    public function skrill()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('return_url, cancel_url, currency_symbol, currency_code, skrill_email')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $skrill = $db->loadObject();
        $url = 'https://www.moneybookers.com/app/payment.pl';
        $this->save($_POST);
        $i = 1; ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post">
            <input type="hidden" name="pay_to_email" value="<?php echo $skrill->skrill_email; ?>">
            <input type="hidden" name="return_url" value="<?php echo $skrill->return_url; ?>">
            <input type="hidden" name="cancel_url" value="<?php echo $skrill->cancel_url; ?>">
            <input type="hidden" name="currency" value="<?php echo $skrill->currency_code; ?>">
            <input type="hidden" name="language" value="EN">
            <input type="hidden" name="amount" value="<?php echo $_POST['ba_total']; ?>">
        <?php
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            $label = array();
                            if (!empty($sett[0])) {
                                $label[0] = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label[0] = $sett[1];
                            } else {
                                $label[0] = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $price = $label[1] = $value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $label = explode(' - ', $row->str);
                                        break;
                                    }
                                }
                            }
                            ?>
            <input type="hidden" name="detail<?php echo $i; ?>_description" value="<?php echo $label[0]; ?>">
            <input type="hidden" name="detail<?php echo $i; ?>_text" value="<?php echo $label[1]; ?>">
            <input type="hidden" name="amount<?php echo $i; ?>" value="<?php echo $price; ?>">
                        <?php
                            $i++;
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $el = $value->str;
                                    break;
                                }
                            }
                        }
                        $label = explode(' - ', $el);
                        $price = str_replace($skrill->currency_symbol, '', $label[1]);
                        
                        ?>
            <input type="hidden" name="detail<?php echo $i; ?>_description" value="<?php echo $label[0]; ?>">
            <input type="hidden" name="detail<?php echo $i; ?>_text" value="<?php echo $label[1]; ?>">
            <input type="hidden" name="amount<?php echo $i; ?>" value="<?php echo $price; ?>">
                        <?php
                        $i++;
                    }
                } else {
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $element = $value->str;
                                $label = explode(' - ', $element);
                                break;
                            }
                        }
                    }
                    $price = str_replace($skrill->currency_symbol, '', $label[1]);
                    ?>
            <input type="hidden" name="detail<?php echo $i; ?>_description" value="<?php echo $label[0]; ?>">
            <input type="hidden" name="detail<?php echo $i; ?>_text" value="<?php echo $label[1]; ?>">
            <input type="hidden" name="amount<?php echo $i; ?>" value="<?php echo $price; ?>">
                    <?php
                    $i++;
                }
                
            }
        }
        ?>
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php 
        exit;
    }
    
    public function twoCheckout()
    {
        $data = $_POST;
        $id = $_POST['form_id'];
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('return_url, currency_symbol, seller_id, payment_environment')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $checkout = $db->loadObject();
        if ($checkout->payment_environment == 'sandbox') {
            $url = 'https://sandbox.2checkout.com/checkout/purchase';
        } else {
            $url = 'https://www.2checkout.com/checkout/purchase';
        }
        $this->save($_POST);
        $i = 0; ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post">
            <input type="hidden" name="sid" value="<?php echo $checkout->seller_id; ?>">
            <input type="hidden" name="mode" value="2CO">
            <input type="hidden" name="pay_method" value="PPI">
            <input type="hidden" name="x_receipt_link_url" value="<?php echo $checkout->return_url; ?>">
        <?php
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            $quantity = 1;
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $price = $value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $quantity = $row->quantity;
                                        break;
                                    }
                                }
                            }
                            ?>
            <input type="hidden" name="li_<?php echo $i; ?>_name" value="<?php echo $label; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_price" value="<?php echo $price; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_quantity" value="<?php echo $quantity; ?>">
                        <?php
                            $i++;
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        $label = explode(' - ', $el);
                        $price = str_replace($checkout->currency_symbol, '', $label[1]);
                        $quantity = 1;
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $quantity = $value->quantity;
                                    break;
                                }
                            }
                        }
                        ?>
            <input type="hidden" name="li_<?php echo $i; ?>_name" value="<?php echo $label[0]; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_price" value="<?php echo $price; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_quantity" value="<?php echo $quantity; ?>">
                        <?php
                        $i++;
                    }
                } else {
                    $price = str_replace($checkout->currency_symbol, '', $label[1]);
                    $quantity = 1;
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $quantity = $value->quantity;
                                break;
                            }
                        }
                    }
                    ?>
            <input type="hidden" name="li_<?php echo $i; ?>_name" value="<?php echo $label[0]; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_price" value="<?php echo $price; ?>">
            <input type="hidden" name="li_<?php echo $i; ?>_quantity" value="<?php echo $quantity; ?>">
                    <?php
                    $i++;
                }
                
            }
        }
        ?>
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php 
        exit;
    }
    
    public function paypal()
    {
        $data = $_POST;
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
            unset($data['baforms_cart']);
        } else {
            $cart = array();
        }
        $id = $_POST['form_id'];
        $total = $_POST['ba_total'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->select('paypal_email, payment_environment, return_url,
                        cancel_url, currency_symbol, currency_code')
            ->from('#__baforms_forms')
            ->where('id='. $id);
        $db->setQuery($query);
        $paypal = $db->loadObject();
        if ($paypal->payment_environment == 'sandbox') {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr';
        }
        $i = 1;
        $array = array();
        foreach ($data as $key => $value) {
            foreach ($items as $item) {
                if ($key == $item->id) {
                    $settings = $item->settings;
                    $settings = explode('_-_', $settings);
                    if ($settings[2] == 'textInput') {
                        $sett = explode(';', $settings[3]);
                        if ($sett[4] == 'calculation') {
                            if (!empty($sett[0])) {
                                $label = $sett[0];
                            } else if (!empty($sett[1])) {
                                $label = $sett[1];
                            } else {
                                $label = '';
                            }
                            if (empty($value)) {
                                break;
                            }
                            $price = $value;
                            foreach ($cart as $row) {
                                if (!empty($row)) {
                                    $row = json_decode($row);
                                    if ($key == $row->id) {
                                        $price = $price * $row->quantity;
                                        break;
                                    }
                                }
                            }
                            $array['amount_'.$i] = $price;
                            $array['item_name_'.$i] = $label;
                            $i++;
                        }
                    }
                    
                }
                
            }
        }
        foreach ($data as $key => $element) {
            if (is_array($element)) {
                foreach ($element as $el) {
                    $label = explode(' - ', $el);
                }
            } else {
                $label = explode(' - ', $element);
            }
            if (isset($label[1])) {
                if (is_array($element)) {
                    foreach ($element as $el) {
                        $label = explode(' - ', $el);
                        $price = str_replace($paypal->currency_symbol, '', $label[1]);
                        foreach ($cart as $value) {
                            if (!empty($value)) {
                                $value = json_decode($value);
                                if ($key == $value->id && $el == $value->product) {
                                    $price = $price * $value->quantity;
                                    break;
                                }
                            }
                        }
                        $array['amount_'.$i] = $price;
                        $array['item_name_'.$i] = $label[0];
                        $i++;
                    }
                } else {
                    $price = str_replace($paypal->currency_symbol, '', $label[1]);
                    foreach ($cart as $value) {
                        if (!empty($value)) {
                            $value = json_decode($value);
                            if ($key == $value->id) {
                                $price = $price * $value->quantity;
                                break;
                            }
                        }
                    }
                    $array['amount_'.$i] = $price;
                    $array['item_name_'.$i] = $label[0];
                    $i++;
                }
            }
        }
        $this->save($_POST);

        ?>
        <form id="payment-form" action="<?php echo $url; ?>" method="post">
            <input type="hidden" name="cmd" value="_ext-enter">
            <input type="hidden" name="redirect_cmd" value="_cart">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="business" value="<?php echo $paypal->paypal_email; ?>">
            <input type="hidden" name="receiver_email" value="<?php echo $paypal->paypal_email; ?>">
            <input type="hidden" name="currency_code" value="<?php echo $paypal->currency_code; ?>">
            <input type="hidden" name="return" value="<?php echo $paypal->return_url; ?>">
            <input type="hidden" name="cancel_return" value="<?php echo $paypal->cancel_url; ?>">
            <input type="hidden" name="rm" value="2">
            <input type="hidden" name="shipping" value="0">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="charset" value="utf-8">
        <?php foreach ($array as $key => $value) { ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
        <?php } ?>    
        </form>
        <script type="text/javascript">
            document.getElementById('payment-form').submit();
        </script>
        <?php
        exit;
    }
    
    public function getForm($data = array(), $loadData = true)
    {
        
    }
    
    public function saveUpload($fileName, $maxSize, $types, $id, $i)
    {
        $types = explode(',', $types);
        $maxSize = 1048576 * $maxSize;
        $dir = JPATH_BASE . '/images/baforms';
        $badExt = array('php', 'phps', 'php3', 'php4', 'phtml', 'pl',
                        'py', 'jsp', 'asp', 'htm', 'shtml', 'sh',
                        'cgi', 'htaccess', 'exe', 'dll');
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir);
        }
        $dir = $dir.'/form_'.$id;
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir);
        }
        $type = explode('.', $_FILES[$fileName]['name'][$i]);
        $type = end($type);
        $type = trim(strtolower($type));
        if (!in_array($type, $badExt)) {
            foreach ($types as $allow) {
                if (trim($allow) == $type) {
                    if($_FILES[$fileName]['size'][$i] < $maxSize) {
                        $newFile = rand(666666, 666666666666). '_' .$_FILES[$fileName]['name'][$i];
                        $file = $dir ."/".$newFile;
                        if (move_uploaded_file($_FILES[$fileName]['tmp_name'][$i], $file)) {
                            return $newFile;
                        }
                    }
                    break;
                }
            }
        } else {
            return false;
        }
    }

    public function getLetter($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('email_letter')
            ->from('#__baforms_forms')
            ->where('`id` = ' .$id);
        $db->setQuery($query);
        $letter = $db->loadResult();
        $query = $db->getQuery(true);
        $query->select('id, settings')
            ->from('#__baforms_items')
            ->where('`form_id` = ' .$id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $str = '';
        if (empty($letter)) {
            $letter = '<table style="width: 100%; background-color: #ffffff;';
            $letter .= ' border: 1px solid #f3f3f3; margin: 0 auto;"><tbody><tr class="ba-section"';
            $letter .= '><td style="width:100%; padding: 0 20px;"><table style="width: 100%; background-color: rgba(0,0,0,0);';
            $letter .= ' color: #333333; border-top: 1px solid #f3f3f3; border-right:';
            $letter .= ' 1px solid #f3f3f3; border-bottom: 1px solid #f3f3f3; border-';
            $letter .= 'left: 1px solid #f3f3f3; margin-top: 10px; ';
            $letter .= 'margin-bottom: 10px;"><tbody><tr><td class="droppad_area" ';
            $letter .= 'style="width:100%; padding: 20px;">[replace_all_items]</td>';
            $letter .= '</tr></tbody></table></td></tr></tbody></table>';
            foreach ($items as $item) {
                $settings = explode('_-_', $item->settings);
                if ($settings[0] != 'button') {
                    $type = $settings[2];
                    if ($type != 'image' && $type != 'htmltext' && $type != 'map' &&
                        strpos($settings[0], 'baform') === false) {
                        $settings = explode(';', $settings[3]);
                        $name = $this->checkItems($settings[0], $type, $settings[2]);
                        $str .= '<div class="droppad_item system-item" data-item="[item=';
                        $str .= $item->id. ']">[Field='.$name.']</div>';
                    }
                }
            }
        }
        $letter = str_replace('[replace_all_items]', $str, $letter);
        
        $this->letter = $letter;
    }

    public function changeLetter($name, $data, $item)
    {
        $body = $this->letter;
        $pos = strpos($body, 'item='.$item->id. ']');
        $pos = strpos($body, '>', $pos);
        $pos2 = strpos($body, '</div>', $pos);
        $start = substr($body, 0, $pos);
        $end = substr($body, $pos2);
        $str = '<b>'.$name.'</b>: '. nl2br($data);
        $this->letter = $start.'>'.$str.$end;
    }

    public function addLetterTotal($name, $total, $cart)
    {
        $letter = $this->letter;
        $language = JFactory::getLanguage();
        $language->load('com_baforms', JPATH_ADMINISTRATOR);
        $str = '<b>' .$name.'</b>: '.$total;
        $letter = str_replace("[Field=Total]", $str, $letter);
        if (!empty($cart)) {
            $str = '<table style="width: 100%; background-color: rgba(0,0,0,0);';
            $str .= ' margin-top:10px; margin-bottom : 10px;';
            $str .= ' border-top: 1px solid #f3f3f3; border-left: 1px solid';
            $str .= ' #f3f3f3; border-right: 1px solid #f3f3f3; border-';
            $str .= 'bottom: 1px solid #f3f3f3; color:inherit;"><tbody style="';
            $str .= 'color:inherit;"><tr style="color:inherit;';
            $str .= '"><td style="padding: 20px; color:inherit;"><b>'.$language->_("ITEM");
            $str .= '</b></td><td style="padding: 20px; color:inherit;"><b>'.$language->_("PRICE");
            $str .= '</b></td><td style="padding: 20px; color:inherit;"><b>'.$language->_("QUANTITY");
            $str .= '</b></td><td style="padding: 20px; color:inherit;"><b>'.$language->_("TOTAL");
            $str .= '</b></td></tr>';
            foreach ($cart as $value) {
                if (!empty($value)) {
                    $value = json_decode($value);
                    $product = explode(' - ', $value->product);
                    $price = explode(' - ', $value->str);
                    
                    $str .= '<tr><td style="padding: 20px; color:inherit;">'.$product[0];
                    $str .= '</td><td style="padding: 20px; color:inherit;">'.$product[1];
                    $str .= '</td><td style="padding: 20px; color:inherit;">';
                    $str .= $value->quantity.'</td><td style="padding: 20px; color:inherit;">';
                    $str .= $price[1].'</td></tr>';
                }
            }
            $str .= '</tbody></table>';
            $letter = str_replace("[Field=Cart]", $str, $letter);
        }
        $this->letter = $letter;
    }

    public function restoreData()
    {
        $array = array();
        foreach ($this->condition as $key => $condition) {
            $settings = explode('_-_', $condition->item->settings);
            if (strpos($settings[0], 'baform') === false) {
                $array[$key][] = $condition;
            } else {
                $array[$this->condMap[$settings[0]]][] = $condition;
            }
        }
        foreach ($array as $condition) {
            $str = '';
            foreach ($condition as $value) {
                $str .= '<b>'.$value->name.'</b>: '. nl2br($value->str).'<br>';
            }
            $pos = strpos($this->letter, 'item='.$condition[0]->item->id. ']');
            $pos = strpos($this->letter, '>', $pos);
            $pos2 = strpos($this->letter, '</div>', $pos);
            $start = substr($this->letter, 0, $pos);
            $end = substr($this->letter, $pos2);            
            $this->letter = $start.'>'.$str.$end;
        }
    }

    public function addMailchimpSubscribe($form)
    {
        if (!empty($form['mailchimp_api_key']) && !empty($form['mailchimp_list_id'])) {
            $apiKey = $form['mailchimp_api_key'];
            $listId = $form['mailchimp_list_id'];
            $email_address = $_POST[$this->mailchimpMap['EMAIL']];
            $memberId = md5(strtolower($email_address));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
            $merge_fields = array();
            foreach ($this->mailchimpMap as $key => $value) {
                if ($key != 'EMAIL' && isset($_POST[$this->mailchimpMap[$key]])) {
                    $merge_fields[$key] = $_POST[$this->mailchimpMap[$key]];
                }
            }
            $array = array(
                'email_address' => $email_address,
                'status' => 'subscribed',
                'merge_fields' => $merge_fields
            );
            if (empty($merge_fields)) {
                unset($array['merge_fields']);
            }
            $json = json_encode($array);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    public function checkTelegramExt($file)
    {
        switch(JFile::getExt($file)) {
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
                return array('sendPhoto', 'photo');
            case 'mp3':
                return array('sendAudio', 'audio');
            case 'mp4':
                return array('sendVideo', 'video');
            default:
                return array('sendDocument', 'document');
        }
    }

    public function telegramAction($submissionData, $id)
    {
        $message = explode('_-_', $submissionData);
        $text = '';
        $files = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('telegram_token')
            ->from('#__baforms_forms')
            ->where('`id` = ' .$id);
        $db->setQuery($query);
        $token = $db->loadResult();
        $url = 'https://api.telegram.org/bot'.$token;
        if (!empty($token) && function_exists('curl_init')) {
            $data = $this->getContentsCurl($url.'/getUpdates');
            $data = json_decode($data);
            if (!empty($data->result)) {
                $chats = array();
                foreach ($message as $key => $value) {
                    if ($value != '') {
                        $value = explode('|-_-|', $value);
                        if ($value[2] == 'upload' && $value[1] != '') {
                            $files[] = $value[1];
                        } else if ($value[2] != 'terms') {
                            $t = str_replace('<br>', '', $value[1]);
                            $t = str_replace('<br/>', '', $t);
                            $value[0] = str_replace('*', '', $value[0]);
                            $text .= '<b>'.$value[0]. '</b> : ' .$t.'';
                            $text .= '                                                                                        ';
                        }
                    }
                }
                foreach ($data->result as $key => $value) {
                    $result = $value;
                    $chat_id = $result->message->chat->id;
                    if (!in_array($chat_id, $chats)) {
                        $chats[] = $chat_id;
                        $html = '<form target="telegram-target-'.$key.'" id="telegram-form-'.$key.'" method="post"';
                        $html .= ' enctype="multipart/form-data" action="';
                        $html .= $url.'/sendMessage"><input type="hidden" value="';
                        $html .= $chat_id.'" name="chat_id"><input type="hidden" value="HTML" name="parse_mode">';
                        $html .= '<input type="hidden" value="'.$text.'" name="text"></form><iframe name="';
                        $html .= 'telegram-target-'.$key.'" id="telegram-target-'.$key.'" style="display: none;"></iframe>';
                        $html .= '<script type="text/javascript">';
                        $html .= 'document.getElementById("telegram-form-'.$key.'").submit()</script>';
                        echo $html;
                        foreach ($files as $file) {
                            $method = $this->checkTelegramExt($file);
                            $uri = $url.'/'.$method[0].'?chat_id='.$chat_id.'&'.$method[1].'=';
                            $uri .= JUri::root(). 'images/baforms/' .$file;
                            $this->getContentsCurl($uri);
                        }
                    }
                }
            }
        }
    }

    public function getContentsCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function replaceFields($body)
    {
        $regex = '/\[field ID=+(.*?)\]/i';
        preg_match_all($regex, $body, $matches, PREG_SET_ORDER);
        if ($matches) {
            foreach ($matches as $match) {
                if (isset($_POST[$match[1]])) {
                    $body = str_replace('[field ID='.$match[1].']', $_POST[$match[1]], $body);
                } else {
                    $body = str_replace('[field ID='.$match[1].']', '', $body);
                }
            }
        }
        $lang = JFactory::getLanguage();
        $lang->load('com_baforms', JPATH_ADMINISTRATOR);
        $body = str_replace('[submission ID]', $this->submission, $body);
        $body = str_replace('[page title]', $_POST['page_title'], $body);
        $body = str_replace('[page URL]', $_POST['page_url'], $body);
        return $body;
    }
    
    public function sendEmail($title, $msg, $id, $email)
    {
        $this->restoreData();
        $options = $this->getEmailOptions($id);
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array($config->get('mailfrom'), $config->get('fromname') );
        $recipient = $options[0]->email_recipient;
        $recipient = explode(',', $recipient);
        $msg = explode('_-_', $msg);
        $files = array();
        foreach ($msg as $mess) {
            if ($mess != '') {
                $mess = explode('|-_-|', $mess);
                if ($mess[2] == 'upload' && $mess[1] != '') {
                    array_push($files, JPATH_ROOT . '/images/baforms/' .$mess[1]);
                }
            }
        }
        foreach ($recipient as $key => $recip) {
            $recipient[$key] = str_replace('...', '', $recip);
            if ($recip == '') {
                unset($recipient[$key]);
            }
        }
        $options[0]->reply_body = $this->replaceFields($options[0]->reply_body);
        $options[0]->email_subject = $this->replaceFields($options[0]->email_subject);
        $options[0]->reply_subject = $this->replaceFields($options[0]->reply_subject);
        if (!empty($recipient)) {
            $subject = $options[0]->email_subject;
            if (!empty($files)) {
                $mailer->addAttachment($files);
            }
            if ($options[0]->add_sender_email*1 === 1 && !empty($email)) {
                $mailer->addReplyTo($email);
            }
            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $body = $this->letter;
            $mailer->setSender($sender);
            $mailer->setSubject($subject);
            $mailer->addRecipient($recipient);
            $mailer->setBody($body);
            $mailer->Send();
        }
        if (!empty($options[0]->sender_email) && !empty($email)) {
            $mailer = JFactory::getMailer();
            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $sender = array($options[0]->sender_email, $options[0]->sender_name);
            $mailer->setSender($sender);
            $subject = $options[0]->reply_subject;
            $mailer->setSubject($subject);
            $mailer->addRecipient($email);
            $body = $options[0]->reply_body;
            if ($options[0]->copy_submitted_data*1 === 1) {
                $body .= '<br>' .$this->letter;
                if (!empty($files)) {
                    $mailer->addAttachment($files);
                }
            }
            $mailer->setBody($body);
            $mailer->Send();
        }
    }
    
    public function getEmailOptions($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('email_recipient, email_subject, email_body, sender_name,
                        sender_email, reply_subject, reply_body, add_sender_email,
                        copy_submitted_data');
        $query->from('#__baforms_forms');
        $query->where('id='.$id);
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }
    
    public function checkItems($item, $type, $place)
    {
        if ($item != '') {
            return $item;
        } else {
            if ($type == 'textarea') {
                if ($place != '') {
                    return $place;
                } else {
                    return 'Textarea';
                }
            }
            if ($type == 'textInput') {
                if ($place != '') {
                    return $place;
                } else {
                    return 'TextInput';
                }
            }
            if ($type == 'chekInline') {
                return 'ChekInline';
            }
            if ($type == 'checkMultiple') {
                return 'CheckMultiple';
            }
            if ($type == 'radioInline') {
                return 'RadioInline';
            }
            if ($type == 'radioMultiple') {
                return 'RadioMultiple';
            }
            if ($type == 'dropdown') {
                return 'Dropdown';
            }
            if ($type == 'selectMultiple') {
                return 'SelectMultiple';
            }
            if ($type == 'date') {
                return 'Date';
            }
            if ($type == 'slider') {
                return 'Slider';
            }
            if ($type == 'email') {
                if ($place != '') {
                    return $place;
                } else {
                    return 'Email';
                }
            }
            if ($type == 'address') {
                if ($place != '') {
                    return $place;
                } else {
                    return 'Address';
                }
            }
        }
    }

    public function conditionParent($item, $items, $data, $form)
    {
        $settings = explode('_-_', $item->settings);
        $symbol = $form['currency_symbol'];
        $position = $form['currency_position'];
        if (strpos($settings[0], 'baform') !== false) {
            foreach ($items as $value) {
                $sett = explode('_-_', $value->settings);                
                if ($settings[0] == $sett[1]) {
                    if ($this->conditionParent($value, $items, $data, $form)) {
                        $val = explode(';', $sett[3]);
                        $val = explode('\n', $val[2]);
                        foreach ($val as $ind => $v) {
                            if (isset($data['ba_total'])) {
                                $v = explode('====', $v);
                                if (isset($v[1]) && !empty($v[1])) {
                                    if ($position == 'before') {
                                        $val[$ind] = $v[0].' - '.$symbol.$v[1];
                                    } else {
                                        $val[$ind] = $v[0].' - '.$v[1].$symbol;
                                    }
                                } else {
                                    $val[$ind] = str_replace('====', '', $val[$ind]);
                                    $val[$ind] = trim($val[$ind]);
                                }
                            } else {
                                $val[$ind] = str_replace('====', '', $val[$ind]);
                                $val[$ind] = trim($val[$ind]);
                            }
                        }
                        if (isset($data[$value->id]) && $data[$value->id] == str_replace('"', '', $val[$settings[4]])) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }                    
                    break;
                }
            }
        }
        return true;        
    }

    public function checkCondition($item, $items, $name, $str, $form)
    {
        $settings = explode('_-_', $item->settings);
        if (isset($settings[5]) && strlen($settings[5]) > 0) {
            if ($this->conditionParent($item, $items, $_POST, $form)) {
                $obj = new stdClass();
                $obj->name = $name;
                $obj->item = $item;
                $obj->str = $str;
                $this->condition[$item->id] = $obj;
            }
            return false;
        } else if (strpos($settings[0], 'baform') !== false) {
            if ($this->conditionParent($item, $items, $_POST, $form)) {
                $obj = new stdClass();
                $obj->name = $name;
                $obj->item = $item;
                $obj->str = $str;
                $this->condition[$item->id] = $obj;
            }
            return false;   
        } else {
            return true;
        }
    }

    public function setCondMap($items, $form)
    {
        $obj = $form['mailchimp_fields_map'];
        $obj = json_decode($obj);
        foreach ($items as $item) {
            $settings = explode('_-_', $item->settings);
            if (strpos($settings[0], 'baform') !== false) {
                $key = $this->getCondParent($settings[0], $items);
                $this->condMap[$settings[0]] = $key;
            }
            foreach ($obj as $key => $value) {
                if ($settings[1] == $value) {
                    $this->mailchimpMap[$key] = $item->id;
                }
            }
        }
    }

    public function getCondParent($key, $items)
    {
        foreach ($items as $item) {
            $settings = explode('_-_', $item->settings);
            if ($key == $settings[1]) {
                if (strpos($settings[0], 'baform') === false) {
                    return $item->id;
                } else {
                    return $this->getCondParent($settings[0], $items);
                }
            }
        }
    }
    
    public function save($data)
    {
        if (isset($_POST['baforms_cart'])) {
            $cart = $_POST['baforms_cart'];
            $cart = explode(';', $cart);
        } else {
            $cart = array();
        }
        $id = $data['form_id'];
        $flag = true;
        $email = '';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("title, alow_captcha, sent_massage, error_massage,
                        check_ip, currency_symbol, currency_position,
                        mailchimp_api_key, mailchimp_list_id, mailchimp_fields_map");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $form = $db->loadAssoc();
        $query = $db->getQuery(true);
        $query->select("email_options");
        $query->from("#__baforms_forms");
        $query->where("id=" . $id);
        $db->setQuery($query);
        $this->emailStyle = $db->loadResult();
        $title = $form['title'];
        $capt = $form['alow_captcha'];
        $succes = $form['sent_massage'];
        $error = $form['error_massage'];
        $submissionData = '';
        $query = $db->getQuery(true);
        $query->select('settings, id')
            ->from('#__baforms_items')
            ->where('form_id='. $id)
            ->order("column_id ASC");
        $db->setQuery($query);
        $items = $db->loadObjectList();
        $captName = array();
        if ($capt != '0') {
            $captcha = JCaptcha::getInstance($capt, array('namespace' => 'anything'));
            if (isset($data[$capt])) {
                $answer = $captcha->checkAnswer($data[$capt]);
                if ($answer) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            } else {
                foreach ($data as $key=> $dat) {
                    if ($key != 'task' && $key != 'form_id') {
                        array_push($captName, $key);
                    }
                }
                foreach ($items as $key=> $item) {
                    $item = $item->id;
                    for ($i = 0; $i < count($captName); $i++) {
                        if ($item == $captName[$i]) {
                            unset($captName[$i]);
                            sort($captName);
                        }
                    }
                }
                if (isset($captName[0])) {
                    $answer = $captcha->checkAnswer($data[$captName[0]]);
                } else {
                    $answer = $captcha->checkAnswer('anything');
                }
                if ($answer) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            }
        }
        $this->getLetter($id);
        if ($flag) {
            $this->setCondMap($items, $form);
            foreach ($items as $item) {
                if ($flag) {
                    $itm = explode('_-_', $item->settings);
                    if ($itm[0] != 'button') {
                        $type = trim($itm[2]);
                        $itm = explode(';', $itm[3]);
                        if ($type == 'textarea' || $type == 'textInput' || $type == 'chekInline' ||
                            $type == 'checkMultiple' || $type == 'radioInline' || $type == 'radioMultiple' ||
                            $type == 'dropdown' || $type == 'selectMultiple') {
                            $required = $itm[3];
                            $itm = trim($this->checkItems($itm[0], $type, $itm[2]));
                            $name = $itm;
                            $itm = str_replace(' ', '_', $itm);
                            if ($this->conditionParent($item, $items, $data, $form)) {
                                if ($required == 1) {
                                    if (!empty($data[$item->id])) {
                                        $flag = true;
                                    } else {
                                        $flag = false;
                                    }
                                } else {
                                    $flag = true;
                                }
                            } else {
                                $flag = true;
                            }
                            
                        } else if ($type == 'email') {
                            $itm = trim($this->checkItems($itm[0], $type, $itm[2]));
                            $name = $itm;
                            $itm = str_replace(' ', '_', $itm);
                            if ($this->conditionParent($item, $items, $data, $form)) {
                                $reg = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/";
                                if(!empty($data[$item->id]) && preg_match($reg, $data[$item->id])) {
                                    $email = $data[$item->id];
                                    $flag = true;
                                } else {
                                    $flag = false;
                                }
                            } else {
                                $flag = true;
                            }
                            
                        } else {
                            $itm = trim($this->checkItems($itm[0], $type, ''));
                            $name = $itm;
                            $itm = str_replace(' ', '_', $itm);
                        }
                        if ($flag) {
                            foreach ($data as $key => $elem) {
                                if ($key != "form_id" && $key != "task") {
                                    if ($item->id == $key) {
                                        if (is_array($elem)) {
                                            $message = '';
                                            foreach ($elem as $ind => $element) {
                                                foreach ($cart as $value) {
                                                    if (!empty($value)) {
                                                        $value = json_decode($value);
                                                        if ($value->id == $key && $value->product == $element) {
                                                            $element = $value->str;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if ($ind == 0) {
                                                    $message .= '<br>';
                                                }
                                                $message .= strip_tags($element). ';<br>';
                                            }
                                            $submissionData .= $name. '|-_-|' .$message. '|-_-|'.$type. '_-_';
                                            if ($this->checkCondition($item, $items, $name, $message, $form)) {
                                                $this->changeLetter($name, $message, $item);
                                            }
                                        } else {
                                            foreach ($cart as $value) {
                                                if (!empty($value)) {
                                                    $value = json_decode($value);
                                                    if ($value->id == $key) {
                                                        $elem = $value->str;
                                                        break;
                                                    }
                                                }
                                            }
                                            $submissionData .= $name. '|-_-|' .strip_tags($elem). '|-_-|'.$type. '_-_';
                                            if ($this->checkCondition($item, $items, $name, strip_tags($elem), $form)) {
                                                $this->changeLetter($name, strip_tags($elem), $item);
                                            }                                            
                                        }
                                    }
                                }
                            }
                            if (!array_key_exists($item->id, $data) && $type != 'image' &&
                                $type != 'map' && $type != 'htmltext' && $type != 'upload' && $type != 'terms') {
                                $submissionData .= $name. '|-_-||-_-|'.$type. '_-_';
                                if ($this->checkCondition($item, $items, $name, '', $form)) {
                                    $this->changeLetter($name, '', $item);
                                }
                            }
                        }
                    }
                }
            }
            if (isset($data['ba_total'])) {
                $total = $data['ba_total'];
                if ($form['currency_position'] == 'before') {
                    $total = $form['currency_symbol'].$total;
                } else {
                    $total .= $form['currency_symbol'];
                }
                $submissionData .= 'Total|-_-|' .$total. '|-_-|total_-_';
                $this->addLetterTotal('Total', $total, $cart);
            }
            if ($flag) {
                if (!empty($_FILES)) {
                    foreach ($_FILES as $key => $file) {
                        if ($file['error'][0] === 0 && $flag) {
                            foreach ($items as $item) {
                                if ($key == $item->id) {
                                    $options = $item->settings;
                                    $options = explode('_-_', $options);
                                    $type = trim($options[2]);
                                    $options = explode(';', $options[3]);
                                    $length = count($file['error']);
                                    for ($i = 0; $i < $length; $i++) {
                                        $link = $this->saveUpload($key, $options[2], $options[3], $id, $i);
                                        if ($link) {
                                            $key = str_replace('_', ' ', $key);
                                            $submissionData .= $options[0]. '|-_-|' .'form_'.$id.'/'.$link. '|-_-|' .$type. '_-_';
                                            if ($this->checkCondition($item, $items, $options[0], '', $form)) {
                                                $this->changeLetter($options[0], '', $item);
                                            }
                                        } else {
                                            $flag = false;
                                        }
                                    }
                                    break;
                                }
                            }
                        } else if ($file['error'][0] === 4) {
                            foreach ($items as $item) {
                                if ($key == $item->id) {
                                    $options = $item->settings;
                                    $options = explode('_-_', $options);
                                    $type = trim($options[2]);
                                    $options = explode(';', $options[3]);
                                    $submissionData .= $options[0]. '|-_-||-_-|' .$type. '_-_';
                                    if ($this->checkCondition($item, $items, $options[0], '', $form)) {
                                        $this->changeLetter($options[0], '', $item);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            if ($form['check_ip']) {
                $submissionData .= 'Ip Address|-_-|'.$_SERVER['REMOTE_ADDR'].'|-_-|textInput';
                $str = '<b>Ip Address</b> : '.$_SERVER['REMOTE_ADDR'];
                $this->letter = @preg_replace("|\[Field=Ip Address\]|", $str, $this->letter, 1);
            }
            if ($flag) {
                $columns = array('title, mesage, date_time');
                $date = date('Y-m-d');
                $values = array($db->quote($title), $db->quote($submissionData), $db->quote($date));
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->insert('#__baforms_submissions');
                $query->columns($columns);
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $this->submission = $db->insertid();
                $this->addMailchimpSubscribe($form);
                $this->telegramAction($submissionData, $id);
                $this->sendEmail($title, $submissionData, $id, $email);
                echo '<input id="form-sys-mesage" type="hidden" value="' .htmlspecialchars($succes, ENT_QUOTES). '">';
            } else {
                echo '<input id="form-sys-mesage" type="hidden" value="' .htmlspecialchars($error, ENT_QUOTES). '">';
            }
            if (isset($_POST['ba_token']) && !empty($_POST['ba_token'])) {
                $this->removeToken($_POST['ba_token']);
            }
        } else {
            echo '<input id="form-sys-mesage" type="hidden" value="' .htmlspecialchars($error, ENT_QUOTES). '">';
        }
        if ($data['task'] == 'form.save') {
?>
            <script type="text/javascript">
                var obj = {
                    type : 'baform',
                    msg : document.getElementById("form-sys-mesage").value
                };
                window.parent.postMessage(obj, "*");
            </script>
<?php
            exit;
        }
    }
    
}